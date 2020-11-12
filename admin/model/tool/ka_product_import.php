<?php
/*
  Project: CSV Product Import
  Author : karapuz <support@ka-station.com>

  Version: 4 ($Revision: 97 $)

*/

class ModelToolKaProductImport extends KaModel {

	// constants

	protected $sec_per_cycle    = 10;
	protected $enclosure        = '"';
	//	protected $escape           = '\\'; - not supported;
	protected $default_attribute_group_name = 'unassigned';
	protected $curl_resolve_attempts = 3;

	protected $extended_types          = array('select', 'radio', 'checkbox', 'image');
	protected $options_with_def_values = array('text', 'textarea', 'date', 'time', 'datetime');
	protected $option_types            = array('select', 'radio', 'checkbox', 'image', 'text', 'textarea',
	                                     'file', 'date', 'time', 'datetime');
	protected $options_with_images     = array('select', 'radio', 'checkbox', 'image');

	protected $record_types = array(
		'product_option_id' => 1,
		'product_option_value_id' => 2,
	);
	
	protected $delimiters = array(
		"\t"   => 'tab',
		";"  => 'semicolon ";"',
		','  => 'comma ","',
		'|'  => 'pipe "|"',
		' '  => 'space " "',
	);
	
	// session variables
	protected $stat;

	//temporary vaiables
	protected $columns;
	protected $messages;
	protected $kalog = null;
	protected $default_attribute_group_id;
	protected $product_mark = ''; // current product identfier in format like '(model: MK1233): '
	protected $kaformat;
	
	protected $key_fields = null;
	protected $org_error_handler = null;

	function onLoad() {
	
		parent::onLoad();
		
 		$this->kalog = new Log('ka_product_import.log');
 		$this->kaformat = new KaFormat($this->registry);
 		
		if (!isset($this->session->data['ka_pi_m_stat'])) 
			$this->session->data['ka_pi_m_stat'] = array();
		$this->stat = &$this->session->data['ka_pi_m_stat'];

 		$upd = $this->config->get('ka_pi_update_interval');
 		if ($upd >= 5 && $upd <= 25) {
 			$this->sec_per_cycle = $upd;
 		}
		$this->load->model('catalog/product');
		
		$key_fields = $this->config->get('ka_pi_key_fields');
		if (!is_array($key_fields) || empty($key_fields)) {
			$key_fields = array('model');
		}
		$this->key_fields = $key_fields;

		$this->org_error_handler = set_error_handler(array($this, 'import_error_handler'));	
		
		$this->file = new KaFileUTF8();		
	}
	
	public function import_error_handler($errno, $errstr, $errfile, $errline) {

		if (empty($this->stat['status'])) {
			if ($errno == E_WARNING) {
				if (preg_match("/iconv stream filter.*invalid multibyte/", $errstr)) {
					return true;
				}
			}
		}

		return call_user_func_array($this->org_error_handler, func_get_args());
	}

 	/*
 		This function is used for writing messages to log and displaying them instantly during development.
 	*/
 	public function report($msg) {

 		if (defined('KA_DEBUG')) {
 			echo $msg;
 		}

		$this->kalog->write($msg);
 	}

	public function isDBPrepared() {

		$res = $this->kadb->safeQuery("SHOW TABLES LIKE '" . DB_PREFIX . "ka_import_profiles'");
		if (empty($res->rows)) {
			return false;
		}

		$res = $this->kadb->safeQuery("SHOW TABLES LIKE '" . DB_PREFIX . "ka_product_import'");		
		if (empty($res->rows)) {
			return false;
		}

		$res = $this->kadb->safeQuery("DESCRIBE `" . DB_PREFIX . "product` 'skip_import'");
		if (empty($res->rows)) {
			return false;
		}

		$res = $this->kadb->safeQuery("DESCRIBE `" . DB_PREFIX . "ka_product_import` 'is_new'");
		if (empty($res->rows)) {
			return false;
		}
				
		return true;
	}

	public function getSecPerCycle() {
		return $this->sec_per_cycle;
	}

	
	public function getCustomerGroupByName($customer_group) {

		static $customer_groups;

		if (isset($customer_groups[$customer_group])) {
			return $customer_groups[$customer_group];
		}

		$qry = $this->db->query("SELECT cgd.customer_group_id FROM " . DB_PREFIX . "customer_group cg
			INNER JOIN " . DB_PREFIX . "customer_group_description cgd
				ON cg.customer_group_id = cgd.customer_group_id
			WHERE
				cgd.name = '$customer_group'"
		);

		
		if (empty($qry->row)) {
			$customer_groups[$customer_group] = 0;
			return 0;
		}
		
		$customer_groups[$customer_group] = $qry->row['customer_group_id'];
						
		return $qry->row['customer_group_id'];
	}
	
	
	protected function addImportMessage($msg, $type = 'W') {
		static $too_many = false;

		if ($too_many ) return false;

		$prefix = '';
		if ($type == 'W') {
			$prefix = 'WARNING';
		} else if ($type == 'E') {
	  		$prefix = 'ERROR';
	  	} elseif ($type == 'I') {
		  	$prefix = 'INFO';
		}


		if (!empty($this->messages) && count($this->messages) > 200) {
			$too_many = true;
	  		$msg = "too many messages...";
	  	} else {
		  	$msg = $prefix . ': ' . $msg;
		}

	  	$this->kalog->write("Message: " . $msg);

		$this->messages[] = $msg;
	}


	protected function insertToStores($entity, $entity_id, $stores) {

		$table = $entity . "_to_store";
		$field = $entity . "_id";

		foreach($stores as $sv) {
		 	$rec = array(
		 		'store_id' => $sv,
		 		$field => $entity_id
		 	);
		 	$this->kadb->queryInsert($table, $rec, true);
		}

		return true;
	}

	protected function removeFromStores($entity, $entity_id, $stores) {

		$table = $entity . "_to_store";
		$field = $entity . "_id";

		if (!is_array($stores)) {
			$stores = array($stores);
		}
		
		if (empty($stores)) {
			return false;
		}

		foreach($stores as $sv) {
			$this->db->query("DELETE FROM " . DB_PREFIX . $table . " WHERE 
				$field = '" . intval($entity_id) . "' AND store_id = '" . intval($sv) . "'"
			);
		}

		return true;
	}
	
	/*
		PARAMETERS:
			..
			$category_chain - encoded html string
			..
	*/
	protected function saveCategory($product_id, $category_chain, $clear_cache = false) {

		if (empty($category_chain)) {
			return false;
		}

		if (!empty($this->params['cat_separator'])) {
			$category_chain = $this->kaformat->strip($category_chain, $this->params['cat_separator']);
			$category_names = explode($this->params['cat_separator'], $category_chain);
		} else {
			$category_names = array($category_chain);
		}

		$categories = array();

		$parent_id   = 0;
		$category_id = 0;
		
		if (empty($this->params['ka_pi_compare_as_is'])) {
			$name_comparison = "TRIM(CONVERT(name using utf8)) LIKE ";
		} else {
			$name_comparison = "name = ";
		}
		
		foreach ($category_names as $ck => $cv) {

			$cv = trim($cv);

			$new_category = false;
			
			// we use convert function here to make comparison case-insensitive
			// http://dev.mysql.com/doc/refman/5.0/en/cast-functions.html#function_convert
			//
			// http://dev.mysql.com/doc/refman/5.0/en/string-comparison-functions.html#operator_like
			//
			$sel = $this->db->query("SELECT c.category_id FROM " . DB_PREFIX . "category_description cd
				INNER JOIN " . DB_PREFIX . "category c ON cd.category_id=c.category_id
				WHERE 
					language_id = '" . $this->params['language_id'] . "' AND 
					$name_comparison '". $this->db->escape($this->db->escape($cv)) . "'
					AND parent_id = '$parent_id'"
			);

			if (empty($sel->row)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category SET 
					parent_id = '$parent_id',
					status ='1',
					image = '',
					date_modified = NOW(), date_added = NOW()
				");
				$category_id = $this->db->getLastId();
				$is_new      = true;

				$rec = array(
					'category_id' => $category_id,
					'language_id' => $this->params['language_id'],
					'name'        => $cv
				);
				$this->kadb->queryInsert('category_description', $rec);
				$this->stat['categories_created']++;

				$level = 0;
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$parent_id . "' ORDER BY `level` ASC");
				
				foreach ($query->rows as $result) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");
					$level++;
				}
				$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");
				
			} else {
				$category_id = $sel->row['category_id'];
			}

			// insert category to stores
			if (!$this->insertToStores('category', $category_id, $this->params['store_ids'])) {
				$this->addImportMessage("Saving the record to stores has failed");
			}

			$parent_id = $category_id;
		}

		if (empty($category_id)) {
			return false;
		}

		$rec = array(
			'product_id'  => $product_id,
			'category_id' => $category_id,
		);
		$this->kadb->queryInsert('product_to_category', $rec, true);
		
		if ($clear_cache) {
			$this->cache->delete('category');
		}
				
		return true;
	}


	protected function isUrl($path) {
    	return preg_match('/^(http|https|ftp):\/\//isS', $path);
	}


	/*
		RETURNS:
			false - on error
			array - on success. It looks like:
				array(
					'status'
						'http_version'  =>
						'status_code'   =>
						'reason_phrase' =>
					'headers'
						'<hdr1>' => value
						'<hdr2>' => value
				)
	*/
	protected function parseHttpHeader($header) {
	
		if (!preg_match("/^(.*)\s(.*)\s(.*)\x0D\x0A/U", $header, $matches)) {
			return false;
		}

		$status = array(
			'http_version'  => $matches[1],
			'status_code'   => $matches[2],
			'reason_phrase' => $matches[3]
		);
		
		$headers = array();		
		$header_lines = explode("\x0D\x0A", $header);
		
		foreach ($header_lines as $line) {
			$pair        = array();
			$value_start = strpos($line, ': ');
			$name        = substr($line, 0, $value_start);
			$value       = substr($line, $value_start + 2);
						
			$headers[strtolower($name)] = $value;
		}
		
		$result = array(
			'status' => $status,
			'headers' => $headers
		);
		
		return $result;					
	}

	//
	// http://www.w3.org/Protocols/rfc2616/rfc2616-sec6.html
	//
	public function getFileContentsByUrl($url) {

		$message = null;
		$this->lastError = '';
		
		if (function_exists('curl_init')) {
		
			$tmp_url        = $url;
			$redirect_count = 0;
			$parsed_url = parse_url($url);

			do {			
				$headers = '';
				$message = null;
				
				if (preg_match("/^\/\/.*/", $tmp_url)) {
					$tmp_url = "http:" . $tmp_url;
				}
				
				$parsed_tmp_url = parse_url($tmp_url);
				
				$curl = curl_init($tmp_url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_HEADER, true);
				curl_setopt($curl, CURLOPT_TIMEOUT, 23);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				
				// add custom headers to emulate a regular user activity so it will prevent bans
				// by the user-agent string
				//
				$opt_headers = array();
				
				if (preg_match("/dropbox\.com/i", $parsed_url['host'])) {
					$opt_headers[] = "User-Agent: Wget/1.11.4";
				} else {
					$opt_headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0";
				}
				
				$opt_headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0";
 				$opt_headers[] = "Accept: */*";
 				$opt_headers[] = "Host: " . $parsed_tmp_url['host'];
 				$opt_headers[] = "Connection: Keep-Alive";
				
				if (!empty($opt_headers)) {
					curl_setopt($curl, CURLOPT_HTTPHEADER, $opt_headers);
				}
				
				// use more attempts to resolve host name. Sometimes curl fails at resolving 
				// a valid host name.
				//
				$resolve_attempt = 0;
				while (true) {
					$response = curl_exec($curl);
					
					if ($response === false) {
					
						// curl_error code 6 means 'could not resolve host name'
						//
						if (curl_errno($curl) == 6) {
							if ($resolve_attempt++ < $this->curl_resolve_attempts) {
								continue;
							}
						}
						$this->lastError = 'CURL error (' . curl_errno($curl) . '): ' . curl_error($curl);
					}
					
					break;					
				}				
				curl_close($curl);
				
				if ($response === false) {
					break;
				}
				
				$msg_start    = strpos($response, "\x0D\x0A\x0D\x0A");
				$header_block = substr($response, 0, $msg_start);
				$headers      = $this->parseHttpHeader($header_block);				
				if (empty($headers)) {
					if (strlen($response) > 1000) {
						$this->lastError = 'No headers received. Response size is ' . strlen($response);
					} else {
						$this->lastError = 'No headers received. Response is "' . $response . '"';
					}
					break;
				}

				if ($headers['status']['status_code'] >= 200 && $headers['status']['status_code'] < 300) {
					$message = substr($response, $msg_start+4);
					break;
					
				} elseif ($headers['status']['status_code'] >= 300 && $headers['status']['status_code'] < 400) {
					$tmp_url = $headers['headers']['location'];
					continue;
				} else {
					$this->lastError = 'Invalid status code: ' . $headers['status']['status_code'];
					break;
				}
				
			} while (++$redirect_count < 5);
			
		} else {
			if (ini_get('allow_url_fopen')) {
				$message = file_get_contents($url);
			}
		}

		return $message;
	}

		
	protected function normalizeFilename($filename) {

		$chars = array('\\','/','=','.','+','*','?','[','^',']','(','$',']','&','<','>');
   		$filename = str_replace($chars, "_", $filename);

   		return $filename;
	}

	/*
		RETURNS:
			$file - path with file name within image directory or FALSE on error.

		File name can be theortically converted to the right charset but we do not support
		it at this moment.
		$file  = iconv('utf-8', 'Windows-1251', $image);
	*/
	protected function getImageFile($image) {
		$this->lastError = '';
	
		if (empty($image))
			return false;

		$image = trim($image);
		
		$file = '';
		if ($this->isUrl($image)) {

		    $url_info = @parse_url($image);

		    if (empty($url_info)) {
	    		$this->lastError = "Invalid URL data $url";
	    		return false;
			}

	    	// 1) get relative image directory to $images_dir
			//
		    $fullname  = '';
		    $images_dir = str_replace("\\", '/', $this->params['incoming_images_dir']);
      
	    	if (!empty($url_info['path'])) {
	    		$url_info['path'] = urldecode($url_info['path']);
	    		
			    $path_info = pathinfo($url_info['path']);
			    $path_info['dirname'] = $this->kaformat->strip($path_info['dirname'], array("\\","/"));

			    if (!empty($path_info['dirname'])) {
		    		$images_dir = $images_dir . $path_info['dirname'] . '/';
		    		if (!file_exists(DIR_IMAGE . $images_dir)) {
			    		if (!mkdir(DIR_IMAGE . $images_dir, 0755, true)) {
			    			$this->lastError = "Script cannot create directory: $images_dir";
			    			return false;
		    			}
			    	}
			    }
			    
			    // skip downloading files if they exist on the server
			    // it works for direct URLs only.
			    //
			    if (!empty($this->params['skip_img_download'])) {
					if (empty($url_info['query']) && !empty($path_info['extension'])) {
						$_file = $images_dir . $path_info['basename'];
						if (is_file(DIR_IMAGE . $_file) && filesize(DIR_IMAGE . $_file) > 0) {
							return $_file;
						}
					}
				}
			}
		
			// 2) download file and parse the path
			//
			$image = htmlspecialchars_decode($image);
		    $tmp = str_replace(array(' '), array('%20'), $image);

		    $content = $this->getFileContentsByUrl($tmp);
	    	if (empty($content)) {
		    	$this->lastError = "File content is empty for $tmp (" . $this->lastError . ")";
		    	return false;
	    	}

	    	// save the image to a temporary file
	    	//
		  	$tmp_file = tempnam(DIR_IMAGE . $images_dir, "tmp");
		  	
		  	$size = file_put_contents($tmp_file, $content);
		  	if (empty($size)) {
		  		$this->lastError = "Cannot save new image file: $tmp_file";
			  	return false;
			}

    		$image_info = getimagesize($tmp_file);
    		if (empty($image_info)) {
				$this->lastError = "getimagesize returned empty info for the file: $image";
				return false;
			}
			
			// 3) get a complete image file path
			//
			if (!empty($url_info['query'])) {
				$filename = '';
				if (!empty($path_info['basename'])) {
					$filename = $path_info['basename'];
				}
				$query = $this->normalizeFilename($url_info['query']);
				$filename = $filename . $query . image_type_to_extension($image_info[2]);

			} else {
				$filename = $path_info['basename'];
				if (empty($path_info['extension'])) {
					$filename = $filename . image_type_to_extension($image_info[2]);
				}
			}

			// 4) move the image file to the incoming directory
			//
			if (is_file(DIR_IMAGE . $images_dir . $filename)) {
				@unlink(DIR_IMAGE . $images_dir . $filename);
			}
			
			if (!is_file(DIR_IMAGE . $images_dir . $filename)) {
				if (!rename($tmp_file, DIR_IMAGE . $images_dir . $filename)) {
					$this->lastError = "rename operation failed. from $tmp_file to " . DIR_IMAGE . $images_dir . $filename;
					return false;
				}

				if (!chmod(DIR_IMAGE . $images_dir . $filename, 0644)) {
					$this->lastError = "chmod failed for file: $filename";
					return false;
				}
			}

		   	$file = $images_dir . $filename;
		   	
		} else {
			
			//
			// if the image is a regular file
			//
			$file = $this->params['images_dir'].$image;
			if (!is_file(DIR_IMAGE . $file)) {
				$this->lastError = "File not found " . DIR_IMAGE . $file;
				return false;
			}
		}

		return $file;
	}

	protected function getVideo($path) {
		$v_file = urldecode($path);
		if (strpos($v_file,'http') !== FALSE) {
		 	$url_info = @parse_url($v_file);
			if (empty($url_info)) {
				$this->lastError = "Invalid URL data $v_file";
				return false;
			}
		}else{
			$video_file = DIR_IMAGE . $v_file;
			if (!file_exists($video_file)){
				$this->lastError = "Video file not found at URL $v_file";
				return false;
			}
		}
		return urlencode($v_file);
	}

	protected function getVideoPoster($path) {
		$v_file = urldecode($path);
		$video_file = DIR_IMAGE . $v_file;
		if (!file_exists($video_file)){
			$this->lastError = "Video Thumbnail file not found at URL $v_file";
			return false;
		}
		return urlencode($v_file);
	}


	protected function generateProductUrl($id, $name) {
	
		if (empty($name) || empty($id)) {
			$this->kalog->write("generateProductUrl:empty parameters");
			return false;
		}
	
		$url = KaUrlify::filter($name);
		if (empty($url)) {
			$this->kalog->write("generateProductUrl: filter returned empty string");
			return false;
		}
		
		$qry = $this->db->query("SELECT url_alias_id FROM " . DB_PREFIX . "url_alias WHERE 
			keyword='" . $this->db->escape($url) . "'");
			
		if (empty($qry->row)) {
			return $url;
		}
		
		$url = $url . "-p-" . $id;
		$qry = $this->db->query("SELECT url_alias_id FROM " . DB_PREFIX . "url_alias WHERE 
			keyword='" . $this->db->escape($url) . "'");

		if (empty($qry->row)) {
			return $url;
		}

		$this->kalog->write("generateProductUrl: cannot find suitable string");
				
		return false;
	}
	
	
	/*
		TRUE  - success
		FALSE - fail. See lastError for details.
	*/
	public function openFile($params) {

		if (empty($params['file']) || !is_file($params['file'])) {
			$this->lastError = "File '" . $params['file'] ."' does not exist.";
			return false;
		}

		$this->params = $params;
		
		$options = array();
		
		if (!empty($params['opt_enable_macfix'])) {
			$options['enable_macfix'] = true;
		}
		
		if (!$this->file->fopen($params['file'], 'r', $options, $params['charset'])) {
			$this->lastError = $this->file->getLastError();
			return false;
		}
		
		return true;
	}

	
	public function readColumns($delimiter) {
	
		if (empty($this->file->handle)) {
			$this->lastError = "readColumns: file handle is not valid.";
			return false;
		}
	
		rewind($this->file->handle);
		
		$columns = fgetcsv($this->file->handle, 0, $delimiter, $this->enclosure);
		if (empty($columns)) {
			return false;
		}
				
		foreach ($columns as &$cv) {
			$cv = trim($cv);
		}

    	return $columns;
	}

	/*
		PARAMETERS:
			$matches - an array with field sets
				array(
					'fields' =>
									array(
										'field' => 'model',
										'required' => true,
										'copy'  => true,
										'name'  => 'Model',
										'descr' => 'A unique product code required by Opencart'
									),
									...
					'options'    =>
					'attributes' =>
					...
			 	
			$columns - an array with column names like
				arrray(
					0 => 'model'
					1 => 'name'
					...
				);

		RETURNS:
			true  - on success. In this case the matches array is extended with th 'column' value
							containing the column name associated with the field.

			false - error.
	*/
	public function findMatches(&$matches, $columns) {

		$tmp = array();

		foreach ($columns as $ck => $cv) {
			$cv = utf8_strtolower($cv, 'utf-8');
			$tmp[$cv] = $ck;
		}
		$columns = $tmp;

		/*
			'set name' => (
				<field id>
				<readable name for users>
				<prefix>
			);
		*/
		$sets = array(
			'fields'        => array('field', 'name', ''), 
			'attributes'    => array('attribute_id', 'name', 'attribute:'),
			'filter_groups' => array('filter_group_id', 'name', 'filter group:'), 
			'options'       => array('option_id', 'name', 'simple option:'),
			'ext_options'   => array('field', 'name', 'option:'),
			'discounts'     => array('field', 'name', 'discount:'),
			'specials'      => array('field', 'name', 'special:'),
			'reward_points' => array('field', 'name', 'reward point:'),
			'product_profiles' => array('field', 'name', 'product profile:'),
			'reviews' => array('field', 'name', 'review:'),
		);

		foreach ($sets as $sk => $sv) {
		
			if (!isset($matches[$sk])) {
				continue;
			}
			
			foreach ($matches[$sk] as $mk => $mv) {

				if (isset($mv['column'])) {
					continue;
				}
			
				$field = utf8_strtolower($mv[$sv[0]], 'utf-8');
				$name  = utf8_strtolower($mv[$sv[1]], 'utf-8');

				if (isset($columns[$sv[2]. $field])) {
					$mv['column'] = $columns[$sv[2]. $field];
					
				} elseif (isset($columns[$sv[2]. $name])) {
					$mv['column'] = $columns[$sv[2]. $name];
				} else {
					$mv['column'] = 0;
				}
				$matches[$sk][$mk] = $mv;
			}
		}
		
		return true;
	}

	/*
		get product information from the row.

		RETURNS:
			product_id - if product exists
			false      - if product does NOT exist
			
		    lastError  - message. If it is set, the product will not be imported!
	*/
	protected function getProductId($data) {

		$this->lastError = '';
		// get product_id. It finds an existing product or creates a new one.

		$where = array();

		if (!empty($data['product_id'])) {
		
			$where[] = "product_id='" . $this->db->escape($data['product_id']) . "'";
			
		} else {
		
			if (isset($data['model']) && in_array('model', $this->key_fields)) {
				if (!empty($this->params['field_lengths']['model'])) {
					if (utf8_strlen($data['model']) > $this->params['field_lengths']['model']) {
						$this->lastError = 'Model field (' . $data['model'] . ') exceeds the maximum field size(' .
							$this->params['field_lengths']['model'] ."). Product is skipped.";
					};
				}				
				$where[] = "model='" . $this->db->escape($data['model']) . "'";
			}

			if (isset($data['sku']) && in_array('sku', $this->key_fields)) {
				if (!empty($this->params['field_lengths']['sku'])) {
					if (utf8_strlen($data['sku']) > $this->params['field_lengths']['sku']) {
						$this->lastError = 'SKU field (' . $data['sku'] . ') exceeds the maximum field size(' .
							$this->params['field_lengths']['sku'] ."). Product is skipped.";
					};
				}				
				$where[] = "sku='" . $this->db->escape($data['sku']) . "'";
			}
			
			if (isset($data['upc']) && in_array('upc', $this->key_fields)) {
				if (!empty($this->params['field_lengths']['upc'])) {
					if (utf8_strlen($data['upc']) > $this->params['field_lengths']['upc']) {
						$this->lastError = 'UPC field (' . $data['upc'] . ') exceeds the maximum field size(' .
							$this->params['field_lengths']['upc'] ."). Product is skipped.";
					};
				}				
				$where[] = "upc='" . $this->db->escape($data['upc']) . "'";
			}
		}
		
		if (empty($where)) {
			$this->lastError = 'key fields are empty';
			return false;
		}

		$sel = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product AS p 
			WHERE " . implode(" AND ", $where));

		$product_id = (isset($sel->row['product_id'])) ?$sel->row['product_id'] : 0;

		return $product_id;
	}
	
	
	protected function isImportable($product_id) {

		if (empty($product_id)) {
			return true;
		}
		
		$qry = $this->db->query("SELECT skip_import FROM " . DB_PREFIX . "product 
			WHERE 
				product_id = '" . (int) $product_id . "' 
				AND skip_import = 0"
		);

		if (empty($qry->row)) {
			return false;
		}
		
		return true;
	}

	
	/*
		Update existing product. The product record should be created before for new products.

		Returns:
			true  - success
			false - fail 
	*/
	protected function updateProduct($product_id, $data, $is_new) {

		if (empty($product_id)) {
			return false;
		}

		$product = array();

		// set the product status
		//
		if (isset($data['status'])) {
			$product['status'] = (in_array($data['status'], array('1','Y'))) ? 1 : 0;

		} elseif ($is_new) {

			// keep in mind: the option can have an empty value
			//
			if ($this->params['status_for_new_products'] == 'enabled') {
				$product['status'] = 1;
			} elseif ($this->params['status_for_new_products'] == 'disabled') {
				$product['status'] = 0;
			} else {
				if (!empty($data['quantity']) && $data['quantity'] > 0) {
					$product['status'] = 1;
				} else {
					$product['status'] = 0;
				}
			}

		} else {
			// keep in mind: the option can have an empty value
			//
			if ($this->params['status_for_existing_products'] == 'enabled') {
				$product['status'] = 1;
			} elseif ($this->params['status_for_existing_products'] == 'disabled') {
				$product['status'] = 0;
			} elseif ($this->params['status_for_existing_products'] == 'enabled_gt_0') {
				if (!empty($data['quantity']) && $data['quantity'] > 0) {
					$product['status'] = 1;
				} elseif (isset($data['quantity']) && strlen(trim($data['quantity']))) {
					$product['status'] = 0;
				}
			}
		}

		// get a manufacturer id
		//
		if (isset($data['manufacturer'])) {
			$sel = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer AS m
				WHERE name='" . $this->db->escape($data['manufacturer']) . "'");

			if (!empty($sel->row['manufacturer_id'])) {
				$manufacturer_id = $sel->row['manufacturer_id'];
			} elseif (!empty($data['manufacturer'])) {
				$rec = array(
					'name' => $data['manufacturer'],
				);
				$manufacturer_id = $this->kadb->queryInsert("manufacturer", $rec);
			} else {
				$manufacturer_id = 0;
			}
			$product['manufacturer_id'] = $manufacturer_id;

			// insert a new manufacturer to the stores
			//
			if (!empty($manufacturer_id)) {
				if (!$this->insertToStores('manufacturer', $manufacturer_id, $this->params['store_ids'])) {
					$this->addImportMessage("Saving the record to stores has failed");
				}
			}
		}

		// get a tax class id
		//
		if (isset($data['tax_class'])) {
			$sel = $this->db->query("SELECT tax_class_id FROM " . DB_PREFIX . "tax_class AS t
				WHERE title='" . $this->db->escape($data['tax_class']) . "'");

			if (empty($sel->row) && !empty($data['tax_class'])) {
				$this->addImportMessage($this->product_mark . "Tax class name '$data[tax_class]' not found");
			}
			$tax_class_id = (isset($sel->row['tax_class_id'])) ?$sel->row['tax_class_id'] : 0;
			$product['tax_class_id'] = $tax_class_id;
		}

		// Weight. Sample value: 10.000Kg
		//
		if (isset($data['weight'])) {
			$pair = $this->kaformat->parseWeight($data['weight']);
			$product['weight']          = $pair['value'];
			$product['weight_class_id'] = $pair['weight_class_id'];
		} elseif ($is_new) {
			$product['weight_class_id'] = $this->config->get('config_weight_class_id');
		}

		// Dimensions. Sample value: 23.07Cm
		//
		$length_params = array('length', 'height', 'width');
		foreach ($length_params as $lv) {

			if (isset($data[$lv])) {
				$pair = $this->kaformat->parseLength($data[$lv]);
				$product[$lv]               = $pair['value'];
				$product['length_class_id'] = $pair['length_class_id'];
			} elseif ($is_new) {
				$product['length_class_id'] = $this->config->get('config_length_class_id');
			}
		}
		
		// insert the product to the selected store
		//
	    if (!$this->insertToStores('product', $product_id, $this->params['store_ids'])) {
    		$this->addImportMessage("Saving the record to stores has failed");
	    }

		$lang = $this->updateProductDescription($product_id, $data);
				
		// stock status
		//
		if (!empty($data['stock_status'])) {
			$qry = $this->db->query("SELECT stock_status_id FROM " . DB_PREFIX . "stock_status 
				WHERE '" . $this->db->escape(utf8_strtolower($data['stock_status'], 'utf-8')) . "' LIKE LOWER(name)
					AND language_id = '" . $this->params['language_id'] . "'");
					
			if (empty($qry->row)) {
				$this->addImportMessage($this->product_mark . "stock status not found - $data[stock_status]");
				if ($is_new) {
					$product['stock_status_id'] = $this->config->get('config_stock_status_id');
				}
			} else {
				$product['stock_status_id'] = $qry->row['stock_status_id'];
			}
		} elseif ($is_new) {
			$product['stock_status_id'] = $this->config->get('config_stock_status_id');
		}

		// update price
		//
		if (isset($data['price']) && strlen($data['price'])) {
			$product['price'] = $this->kaformat->formatPrice($data['price']);
			if (!empty($this->params['price_multiplier'])) {
				$product['price'] = $product['price'] * $this->params['price_multiplier'];
			}
		}

		// insert an image
		//
		if (isset($data['image'])) {
			if (!empty($data['image'])) {
				$file = $this->getImageFile($data['image']);
				if ($file === false) {
					if (!empty($this->lastError)) {
						$this->addImportMessage($this->product_mark . "image cannot be saved - " . $this->lastError);
					}
				} elseif (!empty($file)) {
					$product['image'] = $file;
				}
			} else {
				$product['image'] = '';
			}
		}

		// insert video
		//
		if (isset($data['video_link'])) {
			if (!empty($data['video_link'])) {
				//check if more than one video
				$p = $data['video_link'];
				$file = false;
				$pp= explode(",",$p);

				if (count($pp) > 1){
					//multiple paths detected.
					$files = array();
					foreach ($pp as $pfile){
						$file = $this->getVideo($pfile);
						if ($file === false){
							if (!empty($this->lastError)) {
								$this->addImportMessage($this->product_mark . "Video cannot be saved - " . $this->lastError);
							}
						}elseif(!empty($file)){
							array_push($files, $file);
						}
					}
					$product['video_link'] = implode(",", $files);
				}else{
					$file = $this->getVideo($data['video_link']);
					if ($file === false) {
						if (!empty($this->lastError)) {
							$this->addImportMessage($this->product_mark . "Video cannot be saved - " . $this->lastError);
						}
					} elseif (!empty($file)) {
						$product['video_link'] = $file;
					}
				}
			} else {
				$product['video_link'] = '';
			}
		}

		// insert video
		//
		if (isset($data['video_p_link'])) {
			if (!empty($data['video_p_link'])) {
				$file = $this->getVideoPoster($data['video_p_link']);
				if ($file === false) {
					if (!empty($this->lastError)) {
						$this->addImportMessage($this->product_mark . "Video Thumbnail cannot be saved - " . $this->lastError);
					}
				} elseif (!empty($file)) {
					$product['video_p_link'] = $file;
				}
			} else {
				$product['video_p_link'] = '';
			}
		}

		// insert date available
		//
		if (!empty($data['date_available'])) {
			if (!$this->kaformat->formatDate($data['date_available'])) {
				$this->addImportMessage($this->product_mark . "Wrong date format in 'date available' field. We recommend to use YYYY-MM-DD. Ex. 2012-11-28");
				if ($is_new) {
					$product['date_available'] = '2000-01-01';
				}
			} else {
				if ($data['date_available'] != '0000-00-00') {
					$product['date_available'] = $data['date_available'];
				} else {
					$product['date_available'] = '2000-01-01';
				}
			}
		} elseif ($is_new) {
			$product['date_available'] = '2000-01-01';
		}

		
		$this->kadb->queryUpdate('product', $product, "product_id='$product_id'");

		// insert seo keyword
		//		
		if (isset($data['seo_keyword']) || !empty($this->params['opt_generate_seo_keyword'])) {
	
			if (!empty($data['seo_keyword'])) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . $product_id. "'");
				
			} elseif (!empty($this->params['opt_generate_seo_keyword'])) {
				$qry = $this->db->query("SELECT url_alias_id FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . $product_id. "'");

				if (empty($qry->row) && !empty($lang['name'])) {
					$data['seo_keyword'] = $this->generateProductUrl($product_id, $lang['name']);
					if (empty($data['seo_keyword'])) {
						$this->addImportMessage($this->product_mark . "Unable to generate SEO friendly URL for product ($lang[name])");
					}
				}
			}
			
			if (!empty($data['seo_keyword'])) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET 
					query = 'product_id=" . (int)$product_id . "', 
					keyword = '" . $this->db->escape($data['seo_keyword']) . "'"
				);
			}
		}

		// produt layout
		//		
		if (isset($data['layout'])) {
			$qry = $this->db->query('SELECT * FROM ' . DB_PREFIX . "layout WHERE
				name = '" . $this->db->escape($data['layout']) . "'"
			);
			
			if (!empty($qry->row['layout_id'])) {
				$layout_id = $qry->row['layout_id'];
			} else {
				$layout_id = 0;
			}
			
			foreach ($this->params['store_ids'] as $store_id) {
				$this->db->query("REPLACE INTO " . DB_PREFIX . "product_to_layout SET
					product_id = '" . (int)$product_id . "', 
					store_id   = '" . (int)$store_id . "', 
					layout_id  = '" . (int)$layout_id . "'"
				);
			}
		}

		return true;
	}

	/*
		
	*/
	protected function updateProductDescription($product_id, $data) {

		if (empty($product_id)) {
			return false;
		}
		
		// insert language-specific options for the product
		//
		$lang = array(
			'product_id'  => $product_id,
			'language_id' => $this->params['language_id'],
		);
		
		if (isset($data['name'])) {
			$lang['name'] = trim($data['name']);
		}

		if (isset($data['description'])) 
			$lang['description'] = trim($data['description']);

		if (isset($data['meta_title']))
			$lang['meta_title'] = trim($data['meta_title']);
			
		if (isset($data['meta_description']))
			$lang['meta_description'] = trim($data['meta_description']);

		if (isset($data['meta_keyword']))
			$lang['meta_keyword'] = trim($data['meta_keyword']);

		if (isset($data['product_tags']))
			$lang['tag'] = trim($data['product_tags']);

		// update product description or insert new one
		//
		$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description
			WHERE 
				product_id = '" . $product_id . "'
				AND language_id = '" . $this->params['language_id'] . "'"
		);
		if (!empty($qry->row)) {
			$lang = array_merge($qry->row, $lang);
		}
		
		$this->kadb->queryInsert('product_description', $lang, true);
		
		return $lang;
	}
	
	/*
		Assign categories to product by category_id or by category name.
		
	*/
	protected function saveCategories($product_id, $data, $delete_old, $is_new) {

		$category_assigned = false;
		
		if (!empty($data['category_id']) || !empty($data['category'])) {
			if ($delete_old) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category
					WHERE product_id = '$product_id'");
			}
		}
		
		$multicat_sep = $this->params['multicat_sep'];
		$cats_list    = array();

		// assign categories by category_id
		//
		if (!empty($data['category_id'])) {

			if (!empty($multicat_sep)) {
				$cats_list = explode($multicat_sep, $data['category_id']);	
			} else {
				$cats_list = array($data['category_id']);
			}
			foreach ($cats_list as $cat) {
				$cat = (int)$cat;
				$qry = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category 
					WHERE category_id = '" . $cat . "'"
				);

				if (!empty($qry->row)) {
					$rec = array(
						'product_id'  => $product_id,
						'category_id' => $qry->row['category_id'],
					);
					$this->kadb->queryInsert('product_to_category', $rec, true);
					$category_assigned = true;
				} else {
					$this->addImportMessage($this->product_mark . " category ID not found '$cat'");
				}
			}
		}

		// assign categories by name
		//				
		if (!empty($data['category']) && empty($data['category_id'])) {

			// insert categories
			//
			if (!empty($data['category'])) {
				if (!empty($multicat_sep)) {
					$cats_list = explode($multicat_sep, $data['category']);
				} else {
					$cats_list = array($data['category']);
				}
				
				foreach ($cats_list as $cat) {
					if ($this->saveCategory($product_id, $cat)) {
						$category_assigned = true;
					}
				}
			}
		}

		// assign the default category for new products if no categories were assigned
		//
		if (!$category_assigned && $is_new) {
			if (!empty($this->params['default_category_id'])) {
				$category_id = $this->params['default_category_id'];
				$rec = array(
					'product_id'  => $product_id,
					'category_id' => $category_id,
				);
			}
			$this->kadb->queryInsert('product_to_category', $rec, true);			
		}
		
		$this->cache->delete('category');
	}


	protected function saveAdditionalImages($product_id, $data, $delete_old, $is_new) {

		if (empty($data['additional_image'])) {
			return true;
		}

		if ($delete_old) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_image
				WHERE product_id = '$product_id'");
		}

		// insert an additional product image
		//
		
		if (!empty($this->params['ka_pi_image_separator'])) {
			$images = explode($this->params['ka_pi_image_separator'], $data['additional_image']);
		} else {
			$images = array($data['additional_image']);
		}

		$sort_order = 0;
		if (!$delete_old && !$is_new) {
			$qry = $this->db->query("SELECT sort_order FROM " . DB_PREFIX . "product_image 
				WHERE product_id = '" . (int)$product_id . "'"
			);
			if (!empty($qry->row['sort_order'])) {
				$sort_order = $qry->row['sort_order'];
			}
		}
		
		foreach ($images as $image) {
		
			if (empty($image))
				continue;
				
			$file = $this->getImageFile($image);
			if ($file === false) {
				$this->addImportMessage($this->product_mark . "image cannot be saved - " . $this->lastError);

			} elseif (!empty($file)) {

				$qry = $this->db->query("SELECT product_image_id FROM " . DB_PREFIX . "product_image
					WHERE image = '" . $this->db->escape($file) . "' AND product_id = '$product_id'");

				if (empty($qry->row)) {
				
					$sort_order += 5;
				
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET 
						product_id = '" . $product_id . "', 
						sort_order = " . $sort_order . ",
						image = '" . $this->db->escape($file) . "'"
					);
				}
			}
		}
	}


	protected function saveAttributes($row, $product, $delete_old, $is_first_product) {

		if (empty($this->params['matches']['attributes'])) {
			return true;
		}

		if ($delete_old) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute
				WHERE
					product_id = '$product[product_id]' 
					AND	language_id = '" .$this->params['language_id'] . "'"
			);
		}

		$data = array();
		foreach ($this->params['matches']['attributes'] as $ak => $av) {
			if (empty($av['column']))
				continue;

			$val = $row[$av['column']];
			if (!$is_first_product) {
				continue;
			}
			
			if (strlen($val) == 0 || strcasecmp($val, '[DELETE]') == 0)  {
			
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute
					WHERE
						product_id = '$product[product_id]' 
						AND	attribute_id = '" .$av['attribute_id'] . "'"
				);
				
			} else {
				$rec = array(
					'product_id'   => $product['product_id'],
					'attribute_id' => $av['attribute_id'],
					'language_id'  => $this->params['language_id'],
					'text'         => $val
				);

				$this->kadb->queryInsert('product_attribute', $rec, true);
			}
		}

		return true;
	}

	
	protected function saveFilters($row, $product, $delete_old) {

		if (empty($this->params['matches']['filter_groups'])) {
			return true;
		}

		if ($delete_old) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter
				WHERE
					product_id = '$product[product_id]'"
			);
		}

		if (empty($this->params['ka_pi_compare_as_is'])) {
			$name_comparison = "TRIM(CONVERT(name using utf8)) LIKE ";
		} else {
			$name_comparison = "name = ";
		}
		
		$data = array();
		foreach ($this->params['matches']['filter_groups'] as $ak => $av) {
			if (empty($av['column']))
				continue;

			$val = $row[$av['column']];

			$sep = $this->config->get('ka_pi_general_separator');
			if (!empty($sep)) {
				$filter_values = explode($sep, $val);
			} else {
				$filter_values = array($val);
			}
			
			foreach ($filter_values as $fv) {
			
				if (empty($fv)) {
					continue;
				}
						
				// find the filter_id
				//
				$filter_id = false;
				$filter_group_id = $av['filter_group_id'];
				$fv = trim($fv);
				
				$qry = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "filter_description WHERE 
					language_id = '" . $this->params['language_id'] . "' 
					AND $name_comparison '". $this->db->escape($this->db->escape($fv)) . "' 
					AND filter_group_id = '$filter_group_id'"
				);
				
				// create a new filter if required
				//
				if (empty($qry->row)) {
				
					// add a new filter value
					//
					$this->db->query("INSERT INTO " . DB_PREFIX . "filter SET 
						filter_group_id = '" . (int)$filter_group_id . "', 
						sort_order = 0"
					);
					$filter_id = $this->db->getLastId();
					
					if (empty($filter_id)) {
						$this->report('filter was not created');
						continue;
					}
						
					$rec = array(
						'filter_id'       => $filter_id,
						'filter_group_id' => $filter_group_id,
						'language_id'     => $this->params['language_id'],
						'name'            => $fv
					);
					$this->kadb->queryInsert('filter_description', $rec);

				} else {
					$filter_id = $qry->row['filter_id'];
				}
				
				// assign the filter
				//
				$this->db->query("REPLACE INTO " . DB_PREFIX . "product_filter SET 
					product_id = '" . (int)$product['product_id'] . "', 
					filter_id = '" . (int)$filter_id . "'"
				);
			}
		}

		return true;
	}

	
	protected function saveReviews($row, $product, $delete_old) {

		if (empty($this->params['matches']['reviews'])) {
			return true;
		}

		if ($delete_old) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "review
				WHERE
					product_id = '$product[product_id]'"
			);
		}

		$rec = array();
		
		foreach ($this->params['matches']['reviews'] as $ak => $av) {
				
			if (!isset($av['column']) || strlen($av['column']) == 0)
				continue;

			$val = $row[$av['column']];
			$rec[$av['field']] = $val;
		}
		
		if (empty($rec['author']) || empty($rec['text'])) {
			return false;
		}
		
		$rec['product_id'] = $product['product_id'];
		
		if (empty($rec['date_added'])) {
			if (!empty($rec['date_modified'])) {
				$rec['date_added'] = $rec['date_modified'];
			}
		}
		
		if (!empty($rec['date_added'])) {
			$rec['date_added'] = $this->parseDate($rec['date_added']);
		}
		
		if (!empty($rec['date_modified'])) {
			$rec['date_modified'] = $this->parseDate($rec['date_modified']);
		}

		$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "review WHERE
			author = '" . $rec['author'] . "' AND
			product_id = '" . $rec['product_id'] . "'
		");

		if (!empty($qry->rows)) {

			if (isset($rec['status']) && strlen($rec['status']) == 0) {
				unset($rec['status']);
			}
		
			$rec = array_merge($qry->rows[0], $rec);
			unset($rec['review_id']);
			
			$this->kadb->queryUpdate("review", $rec, "review_id = '" . $qry->row['review_id'] . "'");
			
		} else {
			if (empty($rec['status'])) {
				$rec['status'] = 1;
			}
			$this->kadb->queryInsert('review', $rec);					
		}
		
		return true;		
	}

		
	/*
		Function saves one product option.

		PARAMETERS:
			...
			option['value'] - it can be empty for text options (and maybe other option types)
			...
			
		RETURNS:
			true  - success
			false - error / fail
	*/	
	protected function saveOption($product, $option) {

		$is_new = false;
		$data = array();
	
		// validate parameters
		//
		$option['type'] = strtolower($option['type']);
		if (!in_array($option['type'], $this->option_types)) {
			$this->addImportMessage($this->product_mark . "Invalid option type - $option[type]");
			return false;
		}

		$option['value'] = trim($option['value']);

		// STAGE 1: find the option in the store
		//
		$qry = $this->db->query("SELECT o.option_id FROM `" . DB_PREFIX ."option` o
			INNER JOIN " . DB_PREFIX . "option_description od ON o.option_id = od.option_id
			WHERE
				language_id = '" . $this->params['language_id'] . "' 
				AND	o.type='$option[type]' 
				AND od.name='" . $this->db->escape($option['name']) . "'"
		);

		if (empty($qry->row)) {

			// if the option is NOT found
			//
			if (empty($this->params['opt_create_options'])) {
				$this->addImportMessage("Option '$option[name]' does not exist in the store. If you want to create options from the file then 
					enable the appropriate setting on the extension settings page."
				);
				return false;
			}

			$rec = array(
				'type'   => $option['type'],
			);
			
			if (!empty($option['group_sort_order'])) {
				$rec['sort_order'] = $option['group_sort_order'];
			};

			$option_id = $this->kadb->queryInsert('option', $rec);
			if (empty($option_id)) {
				$this->addImportMessage("Cannot create a new option");
				return false;
			}
			
			$is_new = true;

			$rec = array(
				'option_id'   => $option_id,
				'language_id' => $this->params['language_id'],
				'name'        => $option['name']
			);
			$this->kadb->queryInsert('option_description', $rec);

			$this->addImportMessage("New option created - $option[name]", 'I');

			// repeat option request
			//
			$qry = $this->db->query("SELECT o.option_id FROM `" . DB_PREFIX ."option` o
				INNER JOIN " . DB_PREFIX . "option_description od ON o.option_id = od.option_id
				WHERE
					language_id = '" . $this->params['language_id'] . "' 
					AND	o.type='$option[type]' 
					AND od.name='" . $this->db->escape($option['name']) . "'"
			);

		} else {
		
			// update group sort order for existing option group
			//
			if (!empty($option['group_sort_order'])) {
				$rec = array(
					'sort_order' => $option['group_sort_order'],
				);
				$this->kadb->queryUpdate('option', $rec, "option_id = '" . $qry->row['option_id'] . "'");
			}		
		}

		//
		// STAGE 2: option found/created and we are going to assing it to a product
		//		
		$option_id = $option['option_id'] = $qry->row['option_id'];

		// find product option id or insert a new one
		//
	   	$qry = $this->db->query("SELECT product_option_id FROM " . DB_PREFIX . "product_option WHERE
	 		product_id='$product[product_id]' AND option_id='$option[option_id]'"
	 	);
   			
		$rec = array(
			'required' => $option['required'],
		);
		if ($this->options_with_def_values) {
			$rec['value'] = $option['value'];
		}
   		
	   	if (empty($qry->row['product_option_id'])) {

			$rec['product_id'] = $product['product_id'];
			$rec['option_id']  = $option['option_id'];
			
			$product_option_id = $this->kadb->queryInsert('product_option', $rec);
		} else {
			$product_option_id = $qry->row['product_option_id'];
			$this->kadb->queryUpdate('product_option', $rec, "product_option_id = '$product_option_id'");
		}
		
		$this->registerRecord($product['product_id'], $this->record_types['product_option_id'], $product_option_id);

		/*
			There are two option types in Opencart:
				simple   - user enters a custom value manually
				extended - options with predefined values
		*/
		if (in_array($option['type'], $this->extended_types)) {

			// find option value or insert a new one
			//
			$qry = $this->db->query("SELECT option_value_id FROM " . DB_PREFIX . "option_value_description WHERE 
				option_id = '" . $option_id . "'
				AND language_id = '" . $this->params['language_id'] . "'
				AND name='" . $this->db->escape($option['value']) . "'");
			
			if (empty($qry->row['option_value_id'])) {
				$rec = array(
					'option_id' => $option['option_id'],					
				);
				
				$option_value_id = $this->kadb->queryInsert('option_value', $rec);

				$rec = array(
					'option_id'       => $option['option_id'],
					'option_value_id' => $option_value_id,
					'language_id'     => $this->params['language_id'],
					'name'            => $option['value']
				);

				$this->kadb->queryInsert('option_value_description', $rec);

			} else {
				$option_value_id = $qry->row['option_value_id'];
			}

			//
			// collect in $rec array extra option parameters and update the option if required
			//
			$rec = array();
			
			if (in_array($option['type'], $this->options_with_images) && !empty($option['image'])) {
				$file = $this->getImageFile($option['image']);
				if ($file === false) {
					$this->addImportMessage("image cannot be saved - " . $this->lastError);
				} else {
					$rec['image'] = $file;
				}
			}
			
			if (isset($option['sort_order'])) {
				$rec['sort_order'] = $option['sort_order'];
			}
			
			if (!empty($rec)) {
				$this->kadb->queryUpdate('option_value', $rec, "option_value_id = '$option_value_id'");
			}
			
			$rec = array(
				'product_option_id' => $product_option_id,
				'product_id'        => $product['product_id'],
				'option_id'         => $option_id,
				'option_value_id'   => $option_value_id
			);
			
			if (isset($option['quantity'])) {
				$rec['quantity'] = $option['quantity'];
			}
			
			if (isset($option['subtract'])) {
				$rec['subtract'] = $option['subtract'];
			}
			
			if (isset($option['price'])) {
				$rec['price']        = abs($this->kaformat->formatPrice($option['price']));
				$rec['price_prefix'] = ($option['price'] < 0 ? '-':'+');
			}
			
			if (isset($option['points'])) {
				$rec['points']        = abs($option['points']);
				$rec['points_prefix'] = ($option['points'] < 0 ? '-':'+');				
			}

			if (isset($option['weight'])) {
				$rec['weight']        = abs($option['weight']);
				$rec['weight_prefix'] = ($option['weight'] < 0 ? '-':'+');
			}

    		$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE
    	 		product_option_id='$product_option_id'
	     		AND option_value_id='$option_value_id' 
	     	");
			
	     	if (!empty($qry->rows)) {
	     	
	     		$product_option_value_id = (int)$qry->row['product_option_value_id'];
	     	
	     		if ($qry->num_rows > 1) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE
						product_option_id = '$product_option_id'
						AND option_value_id = '$option_value_id'
						AND product_option_value_id <> '" . $product_option_value_id  . "'
					");
				}
	     	
	     		$this->kadb->queryUpdate('product_option_value', $rec, "product_option_value_id = '" . (int) $qry->row['product_option_value_id'] . "'");
			} else {
				$product_option_value_id = $this->kadb->queryInsert('product_option_value', $rec);
			}
			
			$this->registerRecord($product['product_id'], $this->record_types['product_option_value_id'], $product_option_value_id);
		}

		return true;
		
	}
	

	protected function saveOptions($row, $product, $delete_old) {

		// STAGE 1: process simple options from the selected columns
		//
		if (!empty($this->params['matches']['options'])) {
		
			foreach ($this->params['matches']['options'] as $ok => $ov) {
				if (empty($ov['column']))
					continue;

				$val = $row[$ov['column']];
				
				if (empty($val)) {
					continue;
				}
				
				if (!empty($this->params['ka_pi_options_separator'])) {
					$option_values = explode($this->params['ka_pi_options_separator'], $val);
				} else {
					$option_values = array($val);
				}
				
				foreach ($option_values as $ovalue) {
					
					$option = array(
						'name'     => $ov['name'],
						'type'     => $ov['type'],
						'value'    => $ovalue,
						'required' => (!empty($ov['required']))
					);
					
					$this->saveOption($product, $option);
				}
			}
		}
		
		// STAGE 2: process extended options
		//
		if (!empty($this->params['matches']['ext_options'])) {
			$option = array();
			foreach ($this->params['matches']['ext_options'] as $ck => $cv) {
				if (empty($cv['column']))
					continue;

				$val = $row[$cv['column']];
				$option[$cv['field']] = trim($val);
			}
			
			if (!empty($option['name'])) {
			
				if (!empty($this->params['ka_pi_options_separator'])) {
				
					$multi_options = array();
					$option_keys = array('value', 'quantity', 'subtract', 'image', 'price', 'points', 'weight', 'sort_order');

					$max_option_length = 0;
					foreach ($option_keys as $key) {
						if (isset($option[$key])) {
							$multi_options[$key] = explode($this->params['ka_pi_options_separator'], $option[$key]);
							$max_option_length = max($max_option_length, count($multi_options[$key]));
						}
					}
					
					for ($i = 0; $i < $max_option_length; $i++) {
						$tmp_option = $option;
						
						foreach ($multi_options as $key => $val) {
							if (isset($multi_options[$key][$i])) {
								$tmp_option[$key] = $multi_options[$key][$i];
							} else {
								$tmp_option[$key] = $multi_options[$key][count($option[$key]) - 1];
							}
						}
						
						$this->saveOption($product, $tmp_option);
					}
					
				} else {
					$this->saveOption($product, $option);
				}
			}
		}
	}


	protected function saveDiscounts($row, $product, $delete_old) {

		if (empty($this->params['matches']['discounts'])) {
			return true;
		}

		if ($delete_old) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '$product[product_id]'");
		}

		$data = array();

		$record_valid = false;		
		foreach ($this->params['matches']['discounts'] as $ak => $av) {
		
			if (empty($av['column']))
				continue;

			$val = trim($row[$av['column']]);

			if ($av['field'] == 'price') {
				if (strlen($val) > 0) {
					$record_valid = true;
				}
				$val = $this->kaformat->formatPrice($val);

			} elseif (in_array($av['field'], array('date_start', 'date_end'))) {

				if (!$this->kaformat->formatDate($val)) {
					if (!empty($val)) {
						$this->addImportMessage("Wrong date format in 'discount' record. product_id = $product[product_id]");
					}
					$val = '0000-00-00';
				}

			} elseif ($av['field'] == 'customer_group') {
				$data['customer_group_id'] = $this->getCustomerGroupByName($val);
				continue;
			}

			$data[$av['field']] = $val;
		}
		
		if (!$record_valid) {
			return false;
		}

		if (empty($data['customer_group_id'])) {
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}
				
		if (!(isset($data['customer_group_id']) && isset($data['quantity']) &&
			isset($data['price']))
		) {
			return false;
		}
		
		$data['product_id'] = $product['product_id'];
				
		// key fields: product_id, customer_group, quantity, date_start, date_end
		//
		$where = "product_id = '$data[product_id]'
			AND customer_group_id = '$data[customer_group_id]'
			AND quantity = '$data[quantity]'
		";
		
		if (!empty($data['date_start'])) {
			$where .= " AND date_start = '$data[date_start]'";
		}
		if (!empty($data['date_end'])) {
			$where .= " AND date_end = '$data[date_end]'";
		}

		$qry = $this->db->query("SELECT product_discount_id FROM " . DB_PREFIX . "product_discount
			WHERE $where
		");
		
		if (!empty($qry->row)) {
			$this->kadb->queryUpdate('product_discount', $data, "product_discount_id = '" . $qry->row['product_discount_id'] . "'");
		} else {		
			$this->kadb->queryInsert('product_discount', $data); 
		}

		return true;
	}

	public function getProductDiscountPercentage($data)
	{
		$discount_percent = 0;
		$product_id = isset($data['product_id']) ? $data['product_id'] : 0;
		$query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product WHERE product_id = '$product_id'");
		$price = $query->row ? $query->row['price'] : 0;
		$new_price = $data['price'];
		if($price && $new_price)
		{
			$discount = $price - $new_price;
			$discount_percent = ($discount * 100 ) / $price;
		}
		return $discount_percent;
	}

	protected function saveSpecials($row, $product, $delete_old) {

		if (empty($this->params['matches']['specials'])) {
			return true;
		}
		
		if ($delete_old) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '$product[product_id]'");
		}

		$data = array();
		
		foreach ($this->params['matches']['specials'] as $ak => $av) {
			if (empty($av['column']))
				continue;

			$val = trim($row[$av['column']]);

			if ($av['field'] == 'price') {
				$val = $this->kaformat->formatPrice($val);

			} elseif (in_array($av['field'], array('date_start', 'date_end'))) {

				if (!$this->kaformat->formatDate($val)) {
					if (!empty($val)) {
						$this->addImportMessage("Wrong date format in 'special' record. product_id = $product[product_id]");
					}
					$val = '0000-00-00';
				}

			} elseif ($av['field'] == 'customer_group') {
				$data['customer_group_id'] = $this->getCustomerGroupByName($val);
				continue;
			}

			$data[$av['field']] = $val;
		}
		
		if (empty($data['customer_group_id']) && empty($data['price'])) {
			return true;
		}

		$data['product_id'] = $product['product_id'];

		if (empty($data['customer_group_id'])) {
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}
		
		if (!isset($data['priority'])) {
			$data['priority'] = 1;
		}

		if (!(isset($data['customer_group_id']) && isset($data['price']))) {
			return false;
		}

		// key fields: customer_group, date_start, date_end
		//
		$where = " product_id = '$data[product_id]'
			AND customer_group_id = '$data[customer_group_id]'
		";
		
		if (!empty($data['date_start'])) {
			$where .= " AND date_start = '$data[date_start]'";
		}
		if (!empty($data['date_end'])) {
			$where .= " AND date_end = '$data[date_end]'";
		}
		
		$qry = $this->db->query("SELECT product_special_id FROM " . DB_PREFIX . "product_special
			WHERE $where
		");
		
		if (!empty($qry->row)) {
			$this->kadb->queryUpdate('product_special', $data, "product_special_id = '" . $qry->row['product_special_id'] . "'");
		} else {
			$this->kadb->queryInsert('product_special', $data);
		}
		
		return true;
	}


	protected function saveRewardPoints($row, $product, $delete_old) {

		if (empty($this->params['matches']['reward_points'])) {
			return true;
		}
		
		if ($delete_old) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '$product[product_id]'");
		}

		$data = array();
		foreach ($this->params['matches']['reward_points'] as $ak => $av) {
			if (empty($av['column']))
				continue;

			$val = $row[$av['column']];

			if ($av['field'] == 'customer_group') {				
				$data['customer_group_id'] = $this->getCustomerGroupByName($val);
				continue;
			}

			$data[$av['field']] = $val;
		}

		if (empty($data['points'])) {
			return false;
		}

		$data['product_id'] = $product['product_id'];

		if (empty($data['customer_group_id'])) {
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE 
			product_id = '$product[product_id]'
			AND customer_group_id = '$data[customer_group_id]'
		");
		$this->kadb->queryInsert('product_reward', $data);

		return true;
	}

	
	/* 
		$data     - array of file data split by columns
		$product  - product data array
	*/
	protected function saveRelatedProducts($data, $product, $delete_old) {

		if ($delete_old) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '$product[product_id]' AND is_wwell = 0");
		}

		if (empty($data['related_product'])) {
			return true;
		}
	
		// get the array of related models
		//
		$related = array();
		$sep     = $this->config->get('ka_pi_related_products_separator');		
		if (!empty($sep)) {
			$related = explode(",", $data['related_product']);
		} else {
			$related = array($data['related_product']);
		}
		
		foreach ($related as $rv) {
			if (empty($rv)) {
				continue;
			}

			$qry = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product
				WHERE model = '" . $this->db->escape($rv) . "'");
			
			if (empty($qry->row)) {
				continue;
			}

			// link to all products with found model regardless of their number
			//
			foreach ($qry->rows as $row) {
			
				$rec = array(
					'product_id' => $product['product_id'],
					'related_id' => $row['product_id'],
					'is_wwell'   => '0'
				);
				$this->kadb->queryInsert('product_related', $rec, true);

				$rec = array(
					'product_id' => $row['product_id'],
					'related_id' => $product['product_id'],
					'is_wwell'   => '0'
				);
				$this->kadb->queryInsert('product_related', $rec, true);
			}
		}

		return true;
	}

	protected function saveWwellProducts($data, $product, $delete_old) {

		if ($delete_old) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '$product[product_id]' AND is_wwell = 1");
		}
		
		if (empty($data['is_wwell'])) {
			return true;
		}
	
	
		// get the array of related models
		//
		$related = array();
		$sep     = $this->config->get('ka_pi_related_products_separator');		
		if (!empty($sep)) {
			$related = explode(",", $data['is_wwell']);
		} else {
			$related = array($data['is_wwell']);
		}

		
		foreach ($related as $rv) {
			if (empty($rv)) {
				continue;
			}

			$qry = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product
				WHERE model = '" . $this->db->escape($rv) . "'");
			
			if (empty($qry->row)) {
				continue;
			}

			// link to all products with found model regardless of their number
			//
			foreach ($qry->rows as $row) {
			
				$rec = array(
					'product_id' => $product['product_id'],
					'related_id' => $row['product_id'],
					'is_wwell'   => '1'
				);
				$this->kadb->queryInsert('product_related', $rec, true);

				$rec = array(
					'product_id' => $row['product_id'],
					'related_id' => $product['product_id'],
					'is_wwell'   => '1'
				);
				$this->kadb->queryInsert('product_related', $rec, true);
			}
		}

		return true;
	}


	protected function saveProductProfiles($row, $product, $delete_old) {

		if (empty($this->params['matches']['product_profiles'])) {
			return true;
		}
		
		if ($delete_old) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_profile WHERE product_id = '$product[product_id]'");
		}

		$data = array();
		foreach ($this->params['matches']['product_profiles'] as $ak => $av) {
			if (empty($av['column']))
				continue;

			$val = $row[$av['column']];

			if ($av['field'] == 'customer_group') {
				$data['customer_group_id'] = $this->getCustomerGroupByName($val);
				if (empty($data['customer_group_id'])) {
					return false;
				}
				continue;
				
			} elseif ($av['field'] == 'name') {
				$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "profile_description");
				if (empty($qry->row)) {
					return false;
				}
				$data['profile_id'] = $qry->row['profile_id'];
				continue;
			}

			$data[$av['field']] = $val;
		}

		$data['product_id'] = $product['product_id'];

		if (empty($data['customer_group_id'])) {
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_profile WHERE 
			product_id = '$product[product_id]'
			AND customer_group_id = '$data[customer_group_id]'
		");
		$this->kadb->queryInsert('product_profile', $data);

		return true;
	}

		
	protected function saveDownloads($data, $product, $delete_old) {

		if (empty($data['downloads'])) {
			return true;
		}
	
		if ($delete_old) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '$product[product_id]'");
		}
	
		$downloads = array();
		$sep = $this->config->get('ka_pi_general_separator');
		if (!empty($sep)) {
			$downloads = explode($sep, $data['downloads']);
		} else {
			$downloads = array($data['downloads']);
		}
		
		foreach ($downloads as $dv) {
			if (empty($dv)) {
				continue;
			}

			// 1) detect file parts from the file name
			//
			$ext = $dest_filename = $mask = '';
			$info = pathinfo($dv);

			if ($this->params['file_name_postfix'] == 'generate') {				
				$mask = $dv;
				$ext  = md5(mt_rand());
				
			} elseif ($this->params['file_name_postfix'] == 'detect') {
				$mask = $info['filename'];
				$ext  = $info['extension'];
				
			} else {
				$mask = $dv;
			}
			
			$filename = $mask . (!empty($ext) ? '.'.$ext : '');
			
			// 2) find this file in downloads
			//			
			$qry = $this->db->query('SELECT * FROM ' . DB_PREFIX . "download WHERE
				mask = '" . $this->db->escape($mask) . "'"
			);
			
			if (!empty($qry->row)) {
				$download_id = $qry->row['download_id'];
			} else {
			
				$data = array(
					'src_file'  => $dv,
					'filename'  => $filename,
					'mask'      => $mask,
				);
				$download_id = $this->addDownload($data);
			}
			
			// 3) connect product and download record
			//
			if (!empty($download_id)) {
				$rec = array(
					'product_id'  => $product['product_id'],
					'download_id' => $download_id
				);
				$this->kadb->queryInsert('product_to_download', $rec, true);
			}
		}

		return true;
	}
	
	
	protected function addDownload($data) {
	
		$src_file = $this->params['download_source_dir'] . $this->kaformat->strip($data['src_file'], array("\\", "/"));
		
		// 1) copy the file to downloads directory
		//
		if (!file_exists($src_file)) {
			$this->addImportMessage("File does not exist: $src_file");
			return false;			
		}
		
		$dest_file = DIR_DOWNLOAD . $data['filename'];
		if (!copy($src_file, $dest_file)) {
			$this->addImportMessage("Cannot copy file from $src_file to $dest_file.");
			return false;
		}
		
		// 2) add a new record to the database
		//
      	$this->db->query("INSERT INTO " . DB_PREFIX . "download SET 
      		filename   = '" . $this->db->escape($data['filename']) . "', 
      		mask       = '" . $this->db->escape($data['mask']) . "', 
      		date_added = NOW()"
      	);

      	$download_id = $this->db->getLastId(); 
      	
       	$this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET 
       		download_id = '" . (int)$download_id . "', 
       		language_id = '" . (int)$this->params['language_id'] . "', 
       		name = '" . $this->db->escape($data['mask']) . "'"
       	);
       	
       	return $download_id;
	}
	

	/*
		it works with varchar(...) type only right now
		
		RETURNS:
			array where keys are fields, values are field lengths
	*/
	protected function getFieldLengths($table, $fields) {
	
		$qry = $this->db->query("DESCRIBE `$table`");
		if (empty($qry->rows) || empty($fields)) {
			return false;
		}
		
		$ret = array_combine($fields, array_fill(0, count($fields), 0));
		foreach ($qry->rows as $f) {

			if (!in_array($f['Field'], $fields)) {
				continue;
			}
		
			if (!preg_match("/varchar\((\d*)\)/", $f['Type'], $matches)) {
				continue;
			}
			
			$ret[$f['Field']] = intval($matches[1]);
		}
		
		return $ret;	
	}
	
	
	public function initImport($params) {

		if (!$this->openFile($params)) {
			$this->report("initImport: file was not loaded. Last Error:" . $this->lastError);
			return false;
		}
		
		// clean up the temporary table		
		//
		$this->db->query("DELETE FROM " . DB_PREFIX . "ka_product_import
				WHERE 
					token = '" . $this->session->data['token'] . "'
					OR TIMESTAMPDIFF(HOUR, added_at, NOW()) > 168"
		);


		$this->params = $params;

		$this->params['images_dir'] = $this->kaformat->strip($this->params['images_dir'], array("\\", "/"));
		if (!empty($this->params['images_dir'])) {
			$this->params['images_dir'] = $this->params['images_dir'] . '/';
		}

		// store relative path in incoming images directory 
		// important: if incoming_images_dir exists then it should ends with slash
		//
		$incoming_images_dir = '';
		if (!empty($this->params['incoming_images_dir'])) {
			$this->params['incoming_images_dir'] = $this->kaformat->strip($this->params['incoming_images_dir'], array("\\", "/"));
			if (!empty($this->params['incoming_images_dir'])) {
				$incoming_images_dir = $this->params['incoming_images_dir'] . '/';
			}
		}
		$this->params['incoming_images_dir'] = $incoming_images_dir;

		// remove sets if they are not required in the current import
		//
		$sets = $this->getFieldSets();
		$this->copyMatches($sets, $params['matches'], $params['columns']);
		
		foreach ($sets as $sk => $sv) {
		
			$has_column = false;
			foreach ($sv as $msk => $msv) {
				if (isset($msv['column'])) {
					$has_column = true;
				}
			}
			
			if (!$has_column) {
				unset($sets[$sk]);
			}
		}
		
		$this->params['matches'] = $sets;

		$this->params['status_for_new_products']      = $this->config->get('ka_pi_status_for_new_products');
		$this->params['status_for_existing_products'] = $this->config->get('ka_pi_status_for_existing_products');
		$this->params['ka_pi_options_separator']      = $this->config->get('ka_pi_options_separator');
		$this->params['skip_img_download']            = $this->config->get('ka_pi_skip_img_download');
		$this->params['ka_pi_image_separator']        = str_replace(array('\r','\n'), array("\r", "\n"), $this->config->get('ka_pi_image_separator'));

		$this->params['cat_separator'] = $this->params['cat_separator'];
		$this->params['multicat_sep']  = $this->config->get('ka_pi_multicat_separator');
		
		$this->params['opt_create_options']       = $this->config->get('ka_pi_create_options');
		$this->params['opt_compare_as_is']        = $this->config->get('ka_pi_compare_as_is');
		$this->params['opt_generate_seo_keyword'] = $this->config->get('ka_pi_generate_seo_keyword');
		
		$download_source_dir = '';
		if (!empty($this->params['download_source_dir'])) {
			$this->params['download_source_dir'] = $this->kaformat->strip($this->params['download_source_dir'], array("\\", "/"));
			if (!empty($this->params['download_source_dir'])) {
				$download_source_dir = dirname(DIR_APPLICATION) . DIRECTORY_SEPARATOR . $this->params['download_source_dir'] . '/';
			}
		}
		$this->params['download_source_dir'] = $download_source_dir;
		
		$this->params['field_lengths'] = $this->getFieldLengths(DB_PREFIX . 'product', array('model', 'sku', 'upc'));
		
		$this->stat = array(
			'filesize'         => filesize($params['file']),
			'offset'           => 0,

			'started_at'       => time(),

			'lines_processed'  => 0,
			'products_created' => 0,			
			'products_updated' => 0,
			'products_deleted' => 0,

			'categories_created' => 0,

			'errors'           => array(),
			'status'           => 'not_started',
			'col_count'        => count($params['columns']),
		);
		
		$this->kalog->write("Import started. Parameters: " . var_export($this->stat, true));

		return true;
	}


	/*
	*/
	protected function disableNotImportedProducts() {
		
		$this->db->query("UPDATE " . DB_PREFIX . "product p INNER JOIN 
			" . DB_PREFIX. "product_to_store pts ON p.product_id = pts.product_id
			SET p.status='0' 
			WHERE 
				p.product_id NOT IN (
					SELECT product_id FROM " . DB_PREFIX . "ka_product_import 
						WHERE token = '" . $this->session->data['token'] . "'
				)
				AND pts.store_id IN ('" . implode("','", $this->params['store_ids']) . "')
		");
	}
	
	
	protected function registerRecord($product_id, $type, $id) {
		$rec = array(
			'product_id' => $product_id,
			'record_type' => $type,
			'record_id'  => $id,
			'token' => $this->session->data['token'],
		);
		$this->kadb->queryInsert('ka_import_records', $rec, true);
	}

		
	protected function deleteRecords() {
		
		if ($this->params['update_mode'] == 'replace') {
				
			if (!empty($this->params['matches']['ext_options'])) {
	
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE 
					product_id IN (SELECT product_id FROM " . DB_PREFIX . "ka_product_import 
							WHERE token = '" . $this->session->data['token'] . "'
							AND is_new = 0
					) AND
					product_option_id NOT IN (SELECT record_id FROM " . DB_PREFIX . "ka_import_records
						WHERE record_type = '" . (int)$this->record_types['product_option_id'] . "'
						AND token = '" . $this->session->data['token'] . "'
					)
				");
				
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE 
					product_id IN (SELECT product_id FROM " . DB_PREFIX . "ka_product_import 
							WHERE token = '" . $this->session->data['token'] . "'
							AND is_new = 0
					) AND
					product_option_value_id NOT IN (SELECT record_id FROM " . DB_PREFIX . "ka_import_records
						WHERE record_type = '" . (int)$this->record_types['product_option_value_id'] . "'
						AND token = '" . $this->session->data['token'] . "'
					)
				");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "ka_import_records
			WHERE 
				token = '" . $this->db->escape($this->session->data['token']) . "'
				OR TIMESTAMPDIFF(HOUR, added_at, NOW()) > 168
				OR added_at = '0000-00-00 00:00:00'
		");		
	}
	

	/*
		This function is supposed to be called from an external object multiple times. But first you
		will need to call initImport() to define import parameters.

		Import status can be checked by requesting getImportStat() function and verifying $status
		parameter.
	*/
	public function processImport() {

		// switch error output to our stream
		//
		if (!defined('KA_DEBUG')) {
			$old_config_error_display = $this->config->get('config_error_display');
			$this->config->set('config_error_display', false);
		}
		$this->process();
		if (!defined('KA_DEBUG')) {
			$this->config->set('config_error_display', $old_config_error_display);
		}

		return;
	}

	/*
		function updates $this->stat array.
		
		Import status can be determined by 
			$this->stat['status']  - completed, in_progress, error, not_started
			$this->stat['message'] - last import fatal error
	*/
	protected function process() {

		$this->kaformat = new KaFormat($this->registry, $this->params['language_id']);
	
		if ($this->stat['status'] == 'completed') {
			return;
		}
		
		$max_execution_time = @ini_get('max_execution_time');
		if ($max_execution_time > 5 && $max_execution_time < $this->sec_per_cycle) {
			$this->sec_per_cycle = $max_execution_time - 3;
		}
		
		$started_at = time();
		
		if (!$this->openFile($this->params)) {
			$this->addImportMessage("Cannot open file: " . $this->params['file'], 'E');
			$this->stat['status']  = 'error';
			return;
		}

		$col_count = $this->stat['col_count'];
		$this->load->model('tool/image');

		if ($this->stat['offset']) {
			if ($this->file->fseek($this->stat['offset']) == -1) {
				$this->addImportMessage("Cannot offset at $this->stat[offset] in file: $file.", 'E');
				$this->stat['status']  = 'error';
				return;
			}
		} else {
			$tmp = fgetcsv($this->file->handle, 0, $this->params['delimiter'], $this->enclosure);
			$this->stat['lines_processed'] = 1;
			if (is_null($tmp) || count($tmp) != $col_count) {
				$this->addImportMessage("File header does not match the initial file header.", 'E');
				$this->stat['status']  = 'error';
				return;
			}
		}

		$status = 'error';
		while (1) {

			if (time() - $started_at > $this->sec_per_cycle) {
				$status = 'in_progress';
				break;
			}
			
			if (!($row = fgetcsv($this->file->handle, 0, $this->params['delimiter'], $this->enclosure))) {
				break;
			}
				
			$this->stat['lines_processed']++;
			$row = $this->request->clean($row);

			if (!is_array($row)) {
				$this->addImportMessage('File reading error. File ended unexpectedly.');
				continue;
			}
			
			// compare number of read values against the number of columns in the header
			//
			$row_count = count($row);
			if ($row_count < $col_count) {
				if ($row_count == 1) {
					continue;
				}

				// extend the line with empty values. MS Excel may 'optimize' a CSV file and remove
				// trailing empty cells
				//
				$tail = array_fill($row_count, $col_count - $row_count, '');
				$row = array_merge($row, $tail);
				
			} elseif ($row_count > $col_count) {
				$row = array_slice($row, 0, $col_count);
			}
				

			// delete previous product records like 'specials', 'discounts' etc.
			//
			$delete_old_param = ($this->params['update_mode'] == 'replace');

			$product          = array();
			$data             = array();
			$is_new           = false;

			$is_first_product = true;		// first occurence of the product in the file
			$delete_old       = false;  	// 

			// fill in associated product fields
			//
			if (empty($this->params['matches']['fields'])) {
				$this->addImportMessage("Import script lost parameters. Aborting...");
				$status = 'error';
				break;
			}

			foreach ($this->params['matches']['fields'] as $fk => $fv) {
				if (!isset($fv['column']))
					continue;

				$data[$fv['field']] = trim($row[$fv['column']]);
				
				if (!empty($fv['copy'])) {
					$product[$fv['field']] = $data[$fv['field']];
				}
			}

			if (!empty($data['model'])) {
				$this->product_mark = '(model:' . $data['model'] . '): ';
			} else {
				$this->product_mark = '';
			}
			
			// get product id
			//
			$product_id = $this->getProductId($data);
			if (!empty($this->lastError)) {
				$this->addImportMessage($this->lastError . " Line " . $this->stat['lines_processed']);
				continue;
			}
			
			// here we have two separate ways
			// 1) delete product
			// 2) go through insert/update procedure
			//
			if (!empty($product_id) && !$this->isImportable($product_id)) {
				continue;
				
			} elseif (!empty($data['delete_product_flag'])) {
				if (!empty($product_id)) {
					$this->model_catalog_product->deleteProduct($product_id);
					$this->stat['products_deleted']++;
				}
				
			} elseif (!empty($data['remove_from_store'])) {
			
				if (!empty($product_id)) {
					$this->removeFromStores('product', $product_id, $this->params['store_ids']);
					$this->stat['products_updated']++;
				}
				
			} else {

				if (empty($product_id)) {

					if (!empty($this->params['skip_new_products'])) {
						continue;
					}
					
					if (!empty($this->params['tpl_product_id'])) {
						if (!empty($data['product_id'])) {
							$this->addImportMessage('Product template is not used when product_id is specified in the file');
						} else {
							$this->model_catalog_product->copyProduct($this->params['tpl_product_id']);
							$product_id = $this->model_catalog_product->getLastProductId();
						}
					}
					
					if (empty($product_id)) {
					
						if (empty($data['name'])) {
							$this->addImportMessage("Product name is not specified for a new product. Line is skipped: " . $this->stat['lines_processed']);
							continue;
						}
					
						if (empty($data['product_id'])) {
							$this->db->query("INSERT INTO " . DB_PREFIX . 'product SET date_modified = NOW(), date_added = NOW()');
							$product_id = $this->db->getLastId();
						} else {
							$this->db->query("REPLACE INTO " . DB_PREFIX . "product SET date_modified = NOW(), date_added = NOW(), product_id = '" . $this->db->escape($data['product_id']) . "'");
							$product_id = $data['product_id'];
						}
						
						if (empty($product_id)) {
							$this->addImportMessage("Insert operation failed.");
							continue;
						}
					}

					$is_new = true;
					$this->stat['products_created']++;
				}

				// check if we already updated the product
				//
				$qry = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "ka_product_import
					WHERE product_id = '$product_id'
					AND token='" . $this->session->data['token'] . "';"
				);

				if (empty($qry->row)) {
					$rec = array(
						'product_id' => $product_id,
						'is_new'     => ($is_new ? 1 : 0),
						'token'      => $this->session->data['token']
					);
					$this->kadb->queryInsert('ka_product_import', $rec);
				} else {
					$is_first_product = false;
				}
				
				// update product fields once
				//
				if ($is_first_product) {
					$this->kadb->queryUpdate('product', $product, "product_id='$product_id'");
										
					if (!$this->updateProduct($product_id, $data, $is_new)) {
						continue;
					}
					if (!$is_new) {
						$this->stat['products_updated']++;
					}
				}

				if ($delete_old_param && $is_first_product) {
					$delete_old = true;
				}
				
				$product['product_id'] = $product_id;

				$this->saveCategories($product_id, $data, $delete_old, $is_new);

				$this->saveAdditionalImages($product_id, $data, $delete_old, $is_new);

				$this->saveAttributes($row, $product, $delete_old, $is_first_product);
				
				$this->saveFilters($row, $product, $delete_old);

				$this->saveOptions($row, $product, $delete_old);

				$this->saveDiscounts($row, $product, $delete_old);

				$this->saveSpecials($row, $product, $delete_old);

				$this->saveRewardPoints($row, $product, $delete_old);

				$this->saveProductProfiles($row, $product, $delete_old);
								
				$this->saveRelatedProducts($data, $product, true);

				$this->saveWwellProducts($data, $product, true);
				
				$this->saveDownloads($data, $product, $delete_old);
				
				$this->saveReviews($row, $product, $delete_old);
			}
		}

		$this->cache->delete('product');

	    if (feof($this->file->handle)) {
	    
	    	fclose($this->file->handle);

    		$this->stat['status'] = 'completed';
    		$this->stat['offset'] = $this->stat['filesize'];
	    	$this->kalog->write("Import completed. Parameters: " . var_export($this->stat, true));
	    	
	    	// rename the import file if required
	    	//
	    	if ($this->params['location'] == 'server' && !empty($this->params['rename_file'])) {
		    	$path_parts = pathinfo($this->params['file']);
	    	
		    	$dest_file  = $path_parts['dirname'] . DIRECTORY_SEPARATOR . $path_parts['filename'] 
					. '.' . 'processed_at_' . date("Ymd-His") 
					. '.' . $path_parts['extension'];
				if (!rename($this->params['file'], $dest_file)) {
					$this->addImportMessage("rename operation failed. from " .$this->params['file'] . " to " . $dest_file);
				}
			}
			
			if (!empty($this->params['disable_not_imported_products'])) {
				$this->disableNotImportedProducts();
			}
			
			$this->deleteRecords();
			
			// clean up the temporary table
			//
			$this->db->query("DELETE FROM " . DB_PREFIX . "ka_product_import
					WHERE token = '" . $this->session->data['token'] . "'"
			);

	    } else if ($status == 'error') {
	    	fclose($this->file->handle);
			$this->stat['status'] = $status;
			
		} else {
			$this->stat['offset'] = ftell($this->file->handle);
			$this->stat['status'] = 'in_progress';
			fclose($this->file->handle);
		}

	    return;
	}

	
	public function getProfiles() {
		$qry = $this->db->query("SELECT import_profile_id, name FROM " . DB_PREFIX . "ka_import_profiles");

		$profiles = array();				
		if (!empty($qry->rows)) {
			foreach ($qry->rows as $row) {
				$profiles[$row['import_profile_id']] = $row['name'];
			}
		}
				
		return $profiles;
	}


	public function deleteProfile($profile_id) {

		$this->db->query("DELETE FROM " . DB_PREFIX. "ka_import_profiles WHERE import_profile_id = '" . $this->db->escape($profile_id) . "'");
			
		return true;
	}
	
		
	/*
		returns:
			array - on success
			false - on error
	*/
	public function getProfileParams($profile_id) {
	
		$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "ka_import_profiles WHERE import_profile_id = '" . $this->db->escape($profile_id) . "'");
		if (empty($qry->rows)) {
			return false;
		}

		if (!empty($qry->row['params'])) {
			$params = unserialize($qry->row['params']);
		} else {
			$params = array();
		}

		return $params;
	}
	
	
	/*
		returns:
			true  - on success
			false - on error
	*/
	public function setProfileParams($profile_id, $name, $params) {
	
		if (empty($profile_id)) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "ka_import_profiles
				SET 
					name = '" . $this->db->escape($name) . "'
			");
			$profile_id = $this->db->getLastId();
		}
		
		$this->db->query("UPDATE " . DB_PREFIX . "ka_import_profiles
			SET 
				name = '" . $this->db->escape($name) . "',
				params = '" . $this->db->escape(serialize($params)) . "'
			WHERE
				import_profile_id = '" . $this->db->escape($profile_id) . "'
		");

		return true;
	}


	public function getImportMessages() {
		return $this->messages;
	}

	public function getImportStat() {
	 	return $this->stat;
	}


	/*
		Extends array with custom fields from the product table only.
		
	*/	
	protected function addCustomProductFields(&$fields) {
		$default_fields = array('product_id', 'model', 'sku', 'upc', 'ean', 'jan', 'isbn', 'mpn',
			'location', 'quantity', 'stock_status_id', 'image', 'manufacturer_id', 'shipping',
			'price', 'points', 'tax_class_id', 'date_available', 'weight', 'weight_class_id',
			'length', 'width', 'height', 'length_class_id', 'subtract', 'minimum', 'sort_order', 'skip_import',
			'status', 'date_added', 'date_modified', 'viewed'
		);
		
		if (!empty($fields)) {
			foreach ($fields as $field) {
				$default_fields[] = $field['field'];
			}
		}
	
		$qry = $this->db->query('SHOW FIELDS FROM ' . DB_PREFIX . 'product');
		if (empty($qry->rows)) {
			return false;
		}
		
		foreach ($qry->rows as $row) {
			if (in_array($row['Field'], $default_fields)) {
				continue;
			}
			
			$field = array(
				'field' => $row['Field'],
				'copy'  => true,
				'name'  => $row['Field'],
				'descr' => 'Custom field. Type: ' . $row['Type']
			);
			
			$fields[] = $field;
		}
		
		return true;
	}
	
	
	public function getFieldSets() {

		$fields = array(
			'model' => array(
				'field' => 'model',
				'required' => false,
				'copy'  => true,
				'name'  => 'Model',
				'descr' => 'A unique product code required by Opencart'
			),
			'sku' => array(
				'field' => 'sku',
				'copy'  => true,
				'name'  => 'SKU',
				'descr' => ''
			),
			'upc' => array(
				'field' => 'upc',
				'copy'  => true,
				'name'  => 'UPC',
				'descr' => 'Universal Product Code',
			),			
			'ean' => array(
				'field' => 'ean',
				'copy'  => true,
				'name'  => 'EAN',
				'descr' => 'European Article Number',
			),
			'jan' => array(
				'field' => 'jan',
				'copy'  => true,
				'name'  => 'JAN',
				'descr' => 'Japanese Article Number',
			),
			'isbn' => array(
				'field' => 'isbn',
				'copy'  => true,
				'name'  => 'ISBN',
				'descr' => 'International Standard Book Number',
			),
			'mpn' => array(
				'field' => 'mpn',
				'copy'  => true,
				'name'  => 'MPN',
				'descr' => 'Manufacturer Part Number',
			),			
			array(
				'field' => 'name',
				'name'  => 'Name',
				'descr' => 'Product name',
			),
			array(
				'field'    => 'description',
				'name'     => 'Description',
				'descr'    => 'Product description',
			),
			array(
				'field'    => 'category_id',
				'name'     => 'Category ID',
				'descr'    => "If this field is specified then the 'category name' field will be ignored",
			),
			array(
				'field'    => 'category',
				'name'     => 'Category Name',
				'descr'    => 'Full category path. Example: category' . $this->params['cat_separator'] . 'subcategory1' . $this->params['cat_separator'] . 'subcategory2',
				'tip'      => htmlspecialchars('This product import extension can find or create categories by name.<br/>If you need to import other category fields please take a look at "<a href="http://www.opencart.com/index.php?route=extension/extension/info&extension_id=22692" target="_blank">CSV Category Import</a>" extension.'),
			),
			array(
				'field' => 'location',
				'copy'  => true,
				'name'  => 'Location',
				'descr' => 'This field is not used in front-end but it can be defined for products'
			),
			array(
				'field' => 'quantity',
				'copy'  => true,
				'name'  => 'Quantity',
				'descr' => ''
			),			
			array(
				'field' => 'minimum',
				'copy'  => true,
				'name'  => 'Minimum Quantity',
				'descr' => ''
			),
			'subtract' => array(
				'field' => 'subtract',
				'copy'  => true,
				'name'  => 'Subtract Stock',
				'descr' => "1 - Yes, 0 - No."
			),
			'stock_status' => array(
				'field' => 'stock_status',
				'name'  => 'Out of Stock Status',
				'descr' => 'Name of the stock status. Only stock statuses registered in the store are processed.'
			),			 
			'shipping' => array(
				'field' => 'shipping',
				'copy'  => true,
				'name'  => 'Requires Shipping',
				'descr' => '1 - Yes, 0 - No.'
			),
			array(
				'field' => 'status',
				'name'  => 'Status',
				'descr' => "Status 'Enabled' can be defined by '1' or 'Y'. If the status column is not used then behavior depends on the extension settings."
			),			
			array(
				'field' => 'image',
				'name'  => 'Main Product Image',
				'descr' => "A relative path to the image file within 'image' directory or URL."
			),
			array(
				'field' => 'additional_image',
				'name'  => 'Additional Product Image',
				'descr' => "A relative path to the image file within 'image' directory or URL."
			),
			array(				
				'field' => 'manufacturer',
				'name'  => 'Manufacturer',
				'descr' => 'Manufacturer name'
			),
			array(
				'field' => 'price',
				'name'  => 'Price',
				'descr' => 'Regular product price in primary currency (' . $this->config->get('config_currency') . ')',
			),
			array(
				'field' => 'tax_class',
				'name'  => 'Tax class',
				'descr' => 'Tax class'
			),
			array(
				'field' => 'weight',
				'name'  => 'Weight',
				'descr' => 'Weight class units (declared in the store) can be used with the value. Example: 15.98lbs (no spaces).'
			),
			array(
				'field' => 'length',
				'name'  => 'Length',
				'descr' => 'Length class units (declared in the store) can be used with the value. Example: 1.70m (no spaces)'
			),
			array(
				'field' => 'width',
				'name'  => 'Width',
				'descr' => 'Length class units (declared in the store) can be used with the value. Example: 1.70m (no spaces)'
			),
			array(
				'field' => 'height',
				'name'  => 'Height',
				'descr' => 'Length class units (declared in the store) can be used with the value. Example: 1.70m (no spaces)'
			),
			array(
				'field' => 'meta_keyword',
				'name'  => 'Meta tag keywords',
				'descr' => ''
			),
			array(
				'field' => 'meta_title',
				'name'  => 'Meta title',
				'descr' => ''
			),
			array(
				'field' => 'meta_description',
				'name'  => 'Meta tag description',
				'descr' => ''
			),
			'points' => array(
				'field' => 'points',
				'copy'  => true,
				'name'  => 'Points Required',
				'descr' => 'Number of reward points required to make purchase'
			),
			'sort_order' => array(
				'field' => 'sort_order',
				'copy'  => true,
				'name'  => 'Sort Order',
				'descr' => ''
			),
			array(
				'field' => 'seo_keyword',
				'name'  => 'SEO Keyword',
				'descr' => 'SEO friendly URL for the product. Make sure that it is unique in the store.'
			),
			array(
				'field' => 'product_tags',
				'name'  => 'Product Tags',
				'descr' => 'List of product tags separated by comma'
			),
			array(
				'field' => 'date_available',
				'name'  => 'Date Available',
				'descr' => 'Format: YYYY-MM-DD, Example: 2012-03-25'
			),
			array(
				'field' => 'related_product',
				'name'  => 'Related Product',
				'descr' => 'model identifier of the related product',
			),
			array(
				'field' => 'is_wwell',
				'name'  => 'Works Well Products',
				'descr' => 'model identifier of the Works Well product',
			),
			'downloads' => array(
				'field' => 'downloads',
				'name'  => 'Downloads',
				'descr' => 'Downloadable file(s)',
			),
			'layout'    => array(
				'field' => 'layout',
				'name'  => 'Laytout',
				'descr' => 'Product layout'
			),
		);
		
		$enable_delete_flag = true;
		if ($enable_delete_flag) {
			$fields[] = array(
				'field' => 'delete_product_flag',
				'name'  => '"Delete Product" Flag',
				'descr' => 'Any non-empty value will be treated as positive confirmation, be careful',
			);
			$fields[] = array(
				'field' => 'remove_from_store',
				'name'  => 'Remove from Store',
				'descr' => "Set this flag to a non empty value in order to remove the product from the stores selected on the prevous step (without real deletion from the database). It might be useful for multi-store solutions.",
			);
		}
		
		if ($this->config->get('ka_pi_enable_product_id')) {
			$product_id_field = array(
				'field' => 'product_id',
				'name'  => 'product_id',
				'descr' => 'You import this value at your own risk.'
			);
			array_unshift($fields, $product_id_field);
		}

		$this->addCustomProductFields($fields);
		
		foreach ($this->key_fields as $kfk => $kfv) {
			$fields[$kfv]['required'] = true;
		}
		
		$specials = array(
			array(
				'field' => 'customer_group',
				'name'  => 'Customer Group',
				'descr' => ''
			),
			array(
				'field' => 'priority',
				'name'  => 'Prioirity',
				'descr' => ''
			),
			array(
				'field'    => 'price',
				'name'     => 'Price',
				'descr'    => ''
			),
			array(
				'field' => 'date_start',
				'name'  => 'Date Start',
				'descr' => 'Format: YYYY-MM-DD, Example: 2012-03-25'
			),
			array(
				'field' => 'date_end',
				'name'  => 'Date End',
				'descr' => 'Format: YYYY-MM-DD, Example: 2012-03-25'
			),
		);			

		$discounts = array(
			array(
				'field' => 'customer_group',
				'name'  => 'Customer Group',
				'descr' => ''
			),
			'quantity' => array(
				'field' => 'quantity',
				'name'  => 'Quantity',
				'descr' => ''
			),
			'priority' => array(
				'field' => 'priority',
				'name'  => 'Prioirity',
				'descr' => ''
			),
			'price' => array(
				'field' => 'price',
				'name'  => 'Price',
				'descr' => ''
			),
			'discount_percent' => array(
				'field' => 'discount_percent',
				'name'  => 'Discount Percentage',
				'descr' => ''
			),
			'date_start' => array(
				'field' => 'date_start',
				'name'  => 'Date Start',
				'descr' => 'Format: YYYY-MM-DD, Example: 2012-03-25'
			),
			'date_end' => array(
				'field' => 'date_end',
				'name'  => 'Date End',
				'descr' => 'Format: YYYY-MM-DD, Example: 2012-03-25'
			),
		);			

		$reward_points = array(
			'customer_group' => array(
				'field' => 'customer_group',
				'name'  => 'Customer Group',
				'descr' => '',
			),
			'points' => array(
				'field'    => 'points',
				'name'     => 'Reward Points',
				'descr'    => '',
			),
		);

		$ext_options = array(
			'name' => array(
				'field' => 'name',
				'name'  => 'Option Name',
				'descr' => 'required'
			),
			'type' => array(
				'field' => 'type',
				'name'  => 'Option Type',
				'descr' => 'required'
			),
			'value' => array(
				'field' => 'value',
				'name'  => 'Option Value',
				'descr' => 'required'
			),
			'required' => array(
				'field' => 'required',
				'name'  => 'Option Required',
				'descr' => ''
			),
			'image' => array(
				'field' => 'image',
				'name'  => 'Option Image',
				'descr' => ''
			),
			'sort_order' => array(
				'field' => 'sort_order',
				'name'  => 'Value Sort Order',
				'descr' => 'Sort order for option values'
			),
			'group_sort_order' => array(
				'field' => 'group_sort_order',
				'name'  => 'Group Sort Order',
				'descr' => 'Sort order for option groups. Empty cells are skipped.'
			),
			'quantity' => array(
				'field' => 'quantity',
				'name'  => 'Option Quantity',
				'descr' => ''
			),
			'subtract' => array(
				'field' => 'subtract',
				'name'  => 'Option Subtract',
				'descr' => ''
			),
			'price' => array(
				'field' => 'price',
				'name'  => 'Option Price',
				'descr' => ''
			),
			'points' => array(
				'field' => 'points',
				'name'  => 'Option Points',
				'descr' => ''
			),
			'weight' => array(
				'field' => 'weight',
				'name'  => 'Option Weight',
				'descr' => ''
			),
		);

		$reviews = array(
			'author' => array(
				'field' => 'author',
				'name'  => 'Author',
				'descr' => 'Text field. Mandatory.'
			),
			'text' => array(
				'field' => 'text',
				'name'  => 'Review Text',
				'descr' => 'Mandatory.'
			),
			'rating' => array(
				'field' => 'rating',
				'name'  => 'Rating',
				'descr' => 'Mandatory. number (1 - 5)'
			),
			'status' => array(
				'field' => 'status',
				'name'  => 'Status',
				'descr' => '1 - enabled (default value), 0 - disabled'
			),
			'date_added' => array(
				'field' => 'date_added',
				'name'  => 'Date Added',
				'descr' => 'Recommended format:YYYY-MM-DD HH:MM:SS'
			),
			'date_modified' => array(
				'field' => 'date_modified',
				'name'  => 'Date Modified',
				'descr' => 'Recommended format:YYYY-MM-DD HH:MM:SS'
			),
		);
		
		$sets = array(
			'fields'        => $fields,
			'discounts'     => $discounts,
			'specials'      => $specials,
			'reward_points' => $reward_points,
			'ext_options'   => $ext_options,
			'reviews'       => $reviews,
		);

		$this->load->model('catalog/attribute');
		$sets['attributes'] = $this->model_catalog_attribute->getAttributes();

		$this->load->model('catalog/option');
		$sets['options'] = $this->model_catalog_option->getOptions();
				
		$this->load->model('catalog/filter');
		$sets['filter_groups']   = $this->model_catalog_filter->getFilterGroups();
		
		$sets['product_profiles'] = array(
			'name' => array(
				'field'    => 'name',
				'name'     => 'Profile name',
				'descr'    => '',
			),
			'customer_group' => array(
				'field' => 'customer_group',
				'name'  => 'Customer Group',
				'descr' => '',
			),
		);
				
		return $sets;
	}

	public function getCharsets() {
		$arr = array(
			'ISO-8859-1'   => 'ISO-8859-1 (Western Europe)',
			'ISO-8859-5'   => 'ISO-8859-5 (Cyrillc, DOS)',
			'UTF-16'       => 'UNICODE (MS Excel text format)',
			'KOI-8R'       => 'KOI-8R (Cyrillic, Unix)',
			'UTF-7'        => 'UTF-7',
			'UTF-8'        => 'UTF-8',
			'windows-1250' => 'windows-1250 (Central European languages)',
			'windows-1251' => 'windows-1251 (Cyrillc)',
			'windows-1252' => 'windows-1252 (Western languages)',
			'windows-1253' => 'windows-1253 (Greek)',
			'windows-1254' => 'windows-1254 (Turkish)',
			'windows-1255' => 'windows-1255 (Hebrew)',
			'windows-1256' => 'windows-1256 (Arabic)',
			'windows-1257' => 'windows-1257 (Baltic languages)',
			'windows-1258' => 'windows-1258 (Vietnamese)',
			'big5'         => 'Chinese Traditional (Big5)',
			'CP932'        => 'CP932 (Japanese)',
		);

		return $arr;
	}

	/*
		$matches - sets array
		$matches - hash array of column names			
	*/
	public function copyMatches(&$sets, $matches, $columns) {

		// remove empty columns except the first one meaning 'not selected'
		//
		foreach ($columns as $ck => $cv) {
			if ($ck == 0 || strlen($cv)) {
				$tmp[$cv] = $ck;
			}
		}
		$columns = $tmp;

		foreach ($sets as $sk => $sv) {
		
			foreach ($sv as $f_idx => $f_data) {
				if ($sk == 'filter_groups') {
					$f_key = $f_data['filter_group_id'];
					
				} elseif ($sk == 'attributes') {
					$f_key = $f_data['attribute_id'];
					
				} elseif ($sk == 'options') {
					$f_key = $f_data['option_id'];
					
					if (isset($matches['required_options'][$f_key])) {
						$sets[$sk][$f_idx]['required'] = true;
					}
					
				} else {
					$f_key = (isset($f_data['field']) ? $f_data['field']:$f_idx);
				}
				
				if (isset($matches[$sk][$f_key])) {
					if (isset($columns[$matches[$sk][$f_key]])) {
						$sets[$sk][$f_idx]['column'] = $columns[$matches[$sk][$f_key]];
					}
				}
			}
		}
		
		return true;
	}

			
	public function getDelimiters() {
		return $this->delimiters;
	}

	
	/*
		
	
	*/
	public function requestSchedulerOperations() {
	
		if (!$this->db->isKaInstalled('ka_product_import')) {
			return false;
		}
	
		$ret = array(
			'import' => 'Import'
		);
	
		return $ret;
	}
	
	
	public function requestSchedulerOperationParams($operation) {
	
		$ret = array(
			'profile' => array(
				'title' => 'Import Profile',
				'type'  => 'select',
				'required' => true,
			),
		);

		$ret['profile']['options'] = $this->getProfiles();

		return $ret;
	}

			
	protected function initSchedulerImport($profile_id) {
	
		/* profile parameters:
		
				'charset'             => 'UTF-8'
				'cat_separator'       => '///',
				'delimiter'           => 's',
				'store_id'            => array(0),
				'sort_by'             => 'name',
				'image_path'          => 'path',
				'category_ids'        => array(),
				...
		*/
		$params = $this->getProfileParams($profile_id);
		
		if (empty($params)) {
			$this->lastError = "Profile not found";
			return false;
		}
		
		if (!$this->initImport($params)) {
			return false;
		}

		return true;
	}


	/*
		return a code:			
			finished      - operation is complete
			not_finished  - still working (additional calls needed)
	*/
	public function runSchedulerOperation($operation, $params, &$stat) {

		$ret = 'finished';

		if (!$this->db->isKaInstalled('ka_product_import')) {
			return $ret;
		}

		$this->load->language('tool/ka_product_import');

		if ($operation != 'import') {
			$stats['error'] = "Unsupported operation code";
			return $ret;
		}

		if (empty($params['profile'])) {
			$stats['error'] = "Unsupported parameters were passed to the module.";
			return $ret;
		}

		if (empty($this->params) || empty($stat)) {
			if (!$this->initSchedulerImport($params['profile'])) {
				$this->report('initSchedulerImport request was failed');
				return $ret;
			}
		}

		$this->processImport();
		
		$stat['Lines Processed']    = $this->stat['lines_processed'];
		$stat['Products Created']   = $this->stat['products_created'];
		$stat['Products Updated']   = $this->stat['products_updated'];
		$stat['Products Deleted']   = $this->stat['products_deleted'];
		$stat['Categories Created'] = $this->stat['categories_created'];
		
		$stat['Time Passed']        = $this->kaformat->formatPassedTime(time() - $this->stat['started_at']);
		$stat['File Size']          = $this->kaformat->convertToMegabyte($this->stat['filesize']);
		
		if ($this->stat['status'] == 'in_progress') {
			$stat['Completion At'] = sprintf("%.2f%%", $this->stat['offset'] / ($this->stat['filesize']/100));
			$ret = 'not_finished';
			
		} elseif ($this->stat['status'] == 'completed') {
			$stat['Completion At'] = sprintf("%.2f%%", 100);
			$ret = 'finished';
			$this->params = null;
			$this->stat   = null;
		}
		
		return $ret;
	}


    public static function str_getcsv($input, $delimiter = ",", $enclosure = '"', $escape = "\\") {

        $mem_size = 1 * 1024 * 1024;
        
    	if (strlen($input) >= $mem_size) {
    		return false;
    	}
    
        $fp = fopen("php://temp/maxmemory:$mem_size", 'r+');
        fputs($fp, $input);
        rewind($fp);

        $data = fgetcsv($fp, 1000, $delimiter, $enclosure);

        fclose($fp);
        return $data;
    }
    
    public function parseDate($str) {
    
    	$res = '';

    	$date = strtotime($str);
    	if (!empty($date)) {
	    	$res = date("Y-m-d H:i:s", $date);
	    }
	    
	    return $res;
    }
		
	public function examineFileData($file) {

		$file = base64_decode($file);
		$file = substr($file, 0, 256);
		
		$result = array(
			'error' => '',
			'charset' => '',
			'delimiter' => '',
		);
		
		if (ord($file[0]) == 0xd0 && ord($file[1]) == 0xcf) {
			$result['error'] = 'The file looks like a native MS Excel file. The extension works with CSV files only. Save your MS Excel file as UNICODE text file and try to import the data again.';
			return $result;
		}
		
		if ((ord($file[0]) == 0xff && ord($file[1]) == 0xfe)
		) {
			$result['charset'] = 'UTF-16';
		}		

		// try to detect a field delimiter
		//
		$max = array(
			'delimiter' => '',
			'count' => 0,
		);		
		foreach ($this->delimiters as $delimiter => $name) {
			if (in_array($delimiter, array(' '))) {
				continue;
			}
		
			$arr = self::str_getcsv($file, $delimiter);
			
			if (count($arr) > $max['count']) {
				$max['delimiter'] = $delimiter;
				$max['count'] = count($arr);
			}
		}
		
		if ($max['count'] >= 4) {
			$result['delimiter'] = $max['delimiter'];
		}
		
		return $result;
	}
}

?>