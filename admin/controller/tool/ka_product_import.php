<?php 
/*
  Project: CSV Product Import
  Author : karapuz <support@ka-station.com>

  Version: 4 ($Revision: 93 $)

*/

class ControllerToolKaProductImport extends KaController { 

	protected $tmp_dir;
	protected $store_root_dir;
	protected $store_images_dir;
	protected $kaformat = null;

	protected function onLoad() {

		parent::onLoad();

		KaElements::init($this->registry);
		$this->kaformat = new KaFormat($this->registry, $this->config->get('config_language_id'));

		$this->tmp_dir          = DIR_CACHE;
		$this->store_root_dir   = dirname(DIR_APPLICATION);
		$this->store_images_dir = dirname(DIR_IMAGE) . DIRECTORY_SEPARATOR . basename(DIR_IMAGE);

		if (!$this->validate()) {
			return $this->redirect($this->url->link('error/permission', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->load->model('tool/ka_product_import');
		
		if (!$this->db->isKaInstalled('ka_product_import')) {
			$this->addTopMessage("The extension is not installed");
			return $this->redirect($this->url->link('extension/ka_extensions', 'token=' . $this->session->data['token'], 'SSL'));
		}

 		$this->loadLanguage('ka_extensions/ka_product_import');
		$this->data['heading_title']    = $this->language->get('heading_title');

		$this->data['store_images_dir'] = $this->store_images_dir;
		$this->data['store_root_dir']   = $this->store_root_dir;

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/ka_product_import', 'token=' . $this->session->data['token'], 'SSL'),
		);	
		
		$this->document->addScript('view/javascript/ka.alert.js');
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
		);
	}

	protected function prepareOutput() {
		$this->document->setTitle($this->data['heading_title']);
		
		if (empty($this->template)) {
			$this->template = 'tool/ka_product_import.tpl';
		}

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'tool/ka_product_import')) {
			return FALSE;
		}

		return TRUE;
	}


	protected function getStores() {

		$this->load->model('setting/store');
		$stores = $this->model_setting_store->getStores();

		$stores[] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG,
		);

		return $stores;
	}


	// 
	// public actions
	//

	public function index() { // step1

		$this->load->model('catalog/product');
		
		// do we need to re-install the extension?
		//
		if (!$this->model_tool_ka_product_import->isDBPrepared()) {
			$this->data['is_wrong_db'] = true;
			$this->template = 'tool/ka_product_import.tpl';
			$this->prepareOutput();
			return;
		}

		if (empty($this->params) || ($this->request->server['REQUEST_METHOD'] == 'GET' && empty($this->session->data['save_params']))) {
			$this->params = array(
				'update_mode'         => 'add',
				'cat_separator'       => '///',
				'location'            => 'local',
				'language_id'         => $this->config->get('config_language_id'),
				'store_ids'           => array(0),
				'step'                => 1,
				'images_dir'          => '',
				'incoming_images_dir' => 'data' . DIRECTORY_SEPARATOR . 'incoming',
				'default_category_id' => 0,
				'charset'             => 'ISO-8859-1',
				'charset_option'      => 'predefined',
				'delimiter'           => ';',
				'delimiter_option'    => 'predefined',
				'profile_name'        => '', // for the second step
				'profile_id'          => '', // for the first step
				'file_path'           => '',
				'file_name'           => '',
				'rename_file'         => true,
				'price_multiplier'    => '',
				'disable_not_imported_products' => false,
				'skip_new_products'   => false,
				'download_source_dir' => 'files',
				'file_name_postfix'   => 'generate',
				'tpl_product_id' => 0,
			);

			$this->params['iconv_exists']       = function_exists('iconv');
			$this->params['filter_exists']      = in_array('convert.iconv.*', stream_get_filters());
			$this->params['image_urls_allowed'] = false;
			if (ini_get('allow_url_fopen') || function_exists('curl_version')) {
				$this->params['image_urls_allowed'] = true;
			}
			
			$this->params['opt_enable_macfix'] = $this->config->get('ka_pi_enable_macfix');
	 	}

		$profiles = $this->model_tool_ka_product_import->getProfiles();
		$this->data['profiles'] = $profiles;
	 	
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {

			if (!isset($this->request->post['images_dir'])) {
				$this->addTopMessage("Wrong post parameters. Please verify that the file size is less than the maximum upload limit.", 'E');
				$this->session->data['save_params'] = true;
			 	return $this->redirect($this->url->link('tool/ka_product_import', 'token=' . $this->session->data['token'], 'SSL'));
			}
		
			$msg = '';
			if ($this->request->post['mode'] == 'load_profile') {
			
				$this->session->data['save_params'] = true;
				$this->params = array_merge($this->params, $this->model_tool_ka_product_import->getProfileParams($this->request->post['profile_id']));
				if (!empty($this->params)) {
					$this->params['profile_id'] = $this->request->post['profile_id'];
					$this->addTopMessage("Profile has been loaded succesfully");
				} else {
					$this->addTopMessage("Operation failed", 'E');
				}
				
				return $this->redirect($this->url->link('tool/ka_product_import', 'token=' . $this->session->data['token'], 'SSL'));
				
			} elseif ($this->request->post['mode'] == 'delete_profile') {
			
				$this->model_tool_ka_product_import->deleteProfile($this->request->post['profile_id']);
				$this->session->data['save_params'] = true;
				$this->addTopMessage("Profile has been deleted succesfully");
				
				return $this->redirect($this->url->link('tool/ka_product_import', 'token=' . $this->session->data['token'], 'SSL'));
			}
		
			$this->params['images_dir']          = $this->request->post['images_dir'];
			$this->params['incoming_images_dir'] = $this->request->post['incoming_images_dir'];
			$this->params['location']            = $this->request->post['location'];
			$this->params['language_id']         = $this->request->post['language_id'];
			$this->params['cat_separator']       = $this->request->post['cat_separator']; 
			$this->params['update_mode']         = $this->request->post['update_mode']; 
			$this->params['price_multiplier']    = doubleval(str_replace(',', '.', $this->request->post['price_multiplier']));
			$this->params['rename_file']         = (!empty($this->request->post['rename_file'])) ? true:false;

			$this->params['delimiter_option'] = $this->request->post['delimiter_option'];
			if ($this->params['delimiter_option'] == 'predefined') {
				$this->params['delimiter'] = $this->request->post['delimiter']; 
			} else {
				$this->params['delimiter'] = trim($this->request->post['custom_delimiter']); 
			}
			
			$this->params['charset_option'] = $this->request->post['charset_option'];
			if ($this->params['charset_option'] == 'predefined') {
				$this->params['charset']        = $this->request->post['charset'];
			} else {
				$this->params['charset'] = $this->request->post['custom_charset'];
			}

			if (!empty($this->request->post['store_ids'])) {
				$this->params['store_ids']     = $this->request->post['store_ids'];
			} else {
				$this->params['store_ids']     = array(0);
			}
			
			$this->params['disable_not_imported_products'] = (isset($this->request->post['disable_not_imported_products'])) ? true : false;
			$this->params['skip_new_products']   = (isset($this->request->post['skip_new_products'])) ? true : false;
			$this->params['download_source_dir'] = $this->request->post['download_source_dir'];
			$this->params['file_name_postfix']   = $this->request->post['file_name_postfix'];

			if (isset($this->request->post['default_category_id'])) {
				$this->params['default_category_id'] = (int) $this->request->post['default_category_id'];
			}

			if ($this->params['location'] == 'server') {
				$this->params['file_path'] = $this->kaformat->strip($this->request->post['file_path'], array('/','\\'));
				$this->params['file']      = $this->store_root_dir . DIRECTORY_SEPARATOR . $this->params['file_path'];

				if (!file_exists($this->params['file'])) {
					$msg = $msg . $this->language->get('error_file_not_found');
				}

			} else {
			
				if (!empty($this->request->post['is_file_uploaded'])) {
				
					if (!file_exists($this->params['file'])) {
						$this->params['file'] = '';
						$this->params['file_name'] = '';
					}
				
				} elseif (!empty($this->request->files['file']) && is_uploaded_file($this->request->files['file']['tmp_name'])) {
				
					$filename = $this->request->files['file']['name'] . '.' . md5(rand());
					if (move_uploaded_file($this->request->files['file']['tmp_name'], $this->tmp_dir . $filename)) {
					  $this->params['file'] = $this->tmp_dir . $filename;
					  $this->params['file_name'] = $this->request->files['file']['name'];
					} else {
						$msg = $msg . str_replace('{dest_dir}', $this->tmp_dir, $this->language->get('error_cannot_move_file'));
					}
				}
				
				if (empty($this->params['file'])) {
					$msg = $msg . $this->language->get('error_file_not_found');
			 	}
		 	}
		 	
			if (!empty($this->request->post['tpl_product_id'])) {
				$product = $this->model_catalog_product->getProduct($this->request->post['tpl_product_id']);
				if (empty($product)) {
					$msg .= "Template product not found";
				} else {
					$this->params['tpl_product_id'] = $product['product_id'];
				}
			} else {
				$this->params['tpl_product_id'] = 0;
			}

		 	if (empty($msg)) {
				$params = $this->params;
				if ($this->model_tool_ka_product_import->openFile($params)) {
					$this->params['columns'] = $this->model_tool_ka_product_import->readColumns($params['delimiter']);
					if (count($this->params['columns']) == 1) {
						$msg .= "Wrong field delimiter or incorrect file format.";
					}
				} else {
					$msg .= $this->model_tool_ka_product_import->getLastError();
				}
			}
			
			if (!empty($msg)) {
				$this->addTopMessage($msg, 'E');
				$this->session->data['save_params'] = true;
			 	return $this->redirect($this->url->link('tool/ka_product_import', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			return $this->redirect($this->url->link('tool/ka_product_import/step2', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->session->data['save_params'] = false;
		$this->params['step'] = 1;
		
		$this->load->model('setting/store');
		$this->data['stores'] = $this->getStores();

		$this->load->model('catalog/category');
		if ($this->model_catalog_category->getTotalCategories() < 700) {
			$this->data['categories'] = $this->model_catalog_category->getCategories(0);
		} else {
			$this->data['categories'] = array();
		}
		$this->data['charsets']   = $this->model_tool_ka_product_import->getCharsets();
		$this->data['delimiters'] = $this->model_tool_ka_product_import->getDelimiters();

		if (!empty($this->params['file']) && file_exists($this->params['file']) && is_file($this->params['file'])) {
		
			$file_data = file_get_contents($this->params['file'], false, NULL, -1, 1024 * 8);
			$results = $this->model_tool_ka_product_import->examineFileData($file_data);
		
			if ($results['charset'] == $this->params['charset']) {
				$this->data['charset_is_ok'] = true;
			}
			
			if ($results['delimiter'] == $this->params['delimiter']) {
				$this->data['delimiter_is_ok'] = true;
			}
		}
		
		// tpl product will be displayed on a store where vqmod patch was applied to standard files
		//
		$this->data['enable_tpl_product'] = method_exists($this->model_catalog_product, 'getLastProductId');
		
		if (!empty($this->params['tpl_product_id'])) {
			$product = $this->model_catalog_product->getProduct($this->params['tpl_product_id']);
			if (!empty($product)) {
				$this->data['tpl_product'] = $product;
			}
		}
		
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		$this->data['action']    = $this->url->link('tool/ka_product_import', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['params']      = $this->params;

		$this->data['max_server_file_size'] = $this->getUploadMaxFilesize();
		$this->data['max_file_size'] = $this->kaformat->convertToMegabyte($this->getUploadMaxFilesize());

		$this->data['settings_page'] = $this->url->link('ka_extensions/ka_product_import', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['product_url'] = $this->url->link('catalog/product/edit', '', 'SSL') . '&token=' . $this->session->data['token'];
		
		return $this->prepareOutput();
	}


	/*
		The function updates $this->params['matches'] array with assigned columns and some other parameters.

		POST REQUEST:
			fields[<fieldid>     => <column position in the file>
			discounts[<fieldid>] => <column position in the file>
			...
	*/
	protected function updateMatches() {

		$sets = $this->model_tool_ka_product_import->getFieldSets();
		
		foreach ($sets as $sk => $sv) {

			if (empty($sv)) {
				continue;
			}
					
			$fields = $this->request->post[$sk];
			
			foreach ($sv as $f_idx => $f_data) {
			
				if ($sk == 'filter_groups') {
					$f_key = $f_data['filter_group_id'];
					
				} elseif ($sk == 'attributes') {
					$f_key = $f_data['attribute_id'];
					
				} elseif ($sk == 'options') {
					$f_key = $f_data['option_id'];
					
				} else {
					$f_key = (isset($f_data['field']) ? $f_data['field']:$f_idx);
				}
				
				if (isset($fields[$f_key])) {
					if ($fields[$f_key] > 0) {
						$matches[$sk][$f_key] = $this->params['columns'][$fields[$f_key]-1];
					} else {
						$matches[$sk][$f_key] = '';
					}
				}
			}
		}

		$matches['required_options'] = (isset($this->request->post['required_options'])) ? $this->request->post['required_options'] : array();
		
		$this->params['matches'] = $matches;

		return true;
	}


	public function step2() { // step2

		$this->params['step'] = 2;

		$this->data['columns'] = $this->params['columns'];
		array_unshift($this->data['columns'], '');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		
			$this->updateMatches();
			
			$sets = $this->model_tool_ka_product_import->getFieldSets();
			$this->model_tool_ka_product_import->copyMatches($sets, $this->params['matches'], $this->data['columns']);
			
			$errors_found = false;			
			foreach ($sets['fields'] as $field) {
				if (!empty($field['required']) && empty($field['column'])) {
					$this->addTopMessage(sprintf($this->language->get('error_field_required'), $field['name']), 'E');
					$errors_found = true;
				}
			}
			
			if ($errors_found) {
				return $this->redirect($this->url->link('tool/ka_product_import/step2', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			if ($this->request->post['mode'] == 'save_profile') {
			
				if (empty($this->request->post['profile_name'])) {
					$this->addTopMessage("Profile name is empty", "E");
					
				} else {
				
					// we will create new profile always on saving
					//				
					if ($this->model_tool_ka_product_import->setProfileParams(0, $this->request->post['profile_name'], $this->params)) {
						$this->addTopMessage("Profile has been saved succesfully");
					}
				}
			
				return $this->redirect($this->url->link('tool/ka_product_import/step2', 'token=' . $this->session->data['token'], 'SSL'));
			}
						
			return $this->redirect($this->url->link('tool/ka_product_import/step3', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$sets = $this->model_tool_ka_product_import->getFieldSets();

		//
		// $matches - stores array of fields and assigned columns
		// $columns - list of columns in the file
		//
		if (!empty($this->params['matches'])) {
			$this->model_tool_ka_product_import->copyMatches($sets, $this->params['matches'], $this->data['columns']);
		}

		$this->model_tool_ka_product_import->findMatches($sets, $this->data['columns']);
		$this->data['matches'] = $sets;
		
		$this->data['attribute_page_url'] = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], 'SSL');		
		$this->data['filter_page_url']    = $this->url->link('catalog/filter', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['option_page_url']    = $this->url->link('catalog/option', 'token=' . $this->session->data['token'], 'SSL');
    	$this->data['action']             = $this->url->link('tool/ka_product_import/step2', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['back_action']        = $this->url->link('tool/ka_product_import', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['params']             = $this->params;

		$this->data['filesize']           = $this->kaformat->convertToMegabyte(filesize($this->params['file']));
		$this->data['backup_link']        = $this->url->link('tool/backup', 'token=' . $this->session->data['token'], 'SSL');
		
		if (!empty($this->session->data['hide_backup_warning'])) {
			$this->data['hide_backup_warning'] = true;
		}
		
		return $this->prepareOutput();
	}

	public function step3() { // step3

		$this->params['step'] = 3;

		$params = $this->params;
		if (!$this->model_tool_ka_product_import->initImport($params)) {
			$this->addTopMessage($this->model_tool_ka_product_import->getLastError(), 'E');
			return $this->redirect($this->url->link('tool/ka_product_import/step2', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['done_action']        = $this->url->link('tool/ka_product_import', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['params']             = $this->params;
		$sec = $this->model_tool_ka_product_import->getSecPerCycle();
		$this->data['update_interval']    = $sec.' - ' .($sec + 5);

		// format=raw&tmpl=component - these parameters are used for compatibility with Mojoshop
		//
 		$this->data['page_url'] = str_replace('&amp;', '&', $this->url->link('tool/ka_product_import/stat', 'format=raw&tmpl=component&token=' . $this->session->data['token'], 'SSL'));
		$this->template = 'tool/ka_product_import.tpl';
		return $this->prepareOutput();
	}


	/*
		The function is called by ajax script and it outputs information in json format.

		json format:
			status - in progress, completed, error;
			...    - extra import parameters.
	*/
	public function stat() {

		if ($this->params['step'] != 3) {
			$this->addTopMessage('This script can be requested at step 3 only', 'E');
			return $this->redirect($this->url->link('tool/ka_product_import/step2', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->load->model('tool/ka_product_import');

		$this->model_tool_ka_product_import->processImport();

		$stat                  = $this->model_tool_ka_product_import->getImportStat();
		$stat['messages']      = $this->model_tool_ka_product_import->getImportMessages();
		$stat['time_passed']   = $this->kaformat->formatPassedTime(time() - $stat['started_at']);
		$stat['completion_at'] = sprintf("%.2f%%", $stat['offset'] / ($stat['filesize']/100));
	
 		$this->response->setOutput(json_encode($stat));
	}


	public function getUploadMaxFilesize() {
		static $max_filesize;

		if (!isset($max_filesize)) {
			$post_max_size = $this->kaformat->convertToByte(ini_get('post_max_size'));
			$upload_max_filesize = $this->kaformat->convertToByte(ini_get('upload_max_filesize'));
			$max_filesize = intval(min($post_max_size, $upload_max_filesize));
		}

    	return $max_filesize;
	}
	
	
	public function completeTpl() {
		$json = array();
		
		if (isset($this->request->post['filter_name'])) {
			$this->load->model('catalog/product');
			
			$data = array(
				'filter_name' => $this->request->post['filter_name'],
				'start'       => 0,
				'limit'       => 20
			);
			
			$results = $this->model_catalog_product->getProducts($data);
			
			foreach ($results as $result) {
				$option_data = array();
				
				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'),	
					'model'      => $result['model'],
					'price'      => $result['price']
				);	
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	
	/*
		PARAMETERS:
			file - string of 1-10Kb data with file data.
			
		RETURNS:
			charset   - empty or string with charset code (value from the select box)
			delimiter - empty or value from the select box
			error     - string. If error is not empty then show the error text to the user
			
	*/
	public function examineFileData() {
		$json = array();

		if (isset($this->request->post['file_data'])) {

			$data = $this->model_tool_ka_product_import->examineFileData($this->request->post['file_data']);
		
			if (!empty($data['error'])) {
				$json = array(
					'error' => $data['error'],
				);
				
			} else {
				$json = array(
					'charset'   => html_entity_decode($data['charset'], ENT_QUOTES, 'UTF-8'),
					'delimiter' => html_entity_decode($data['delimiter'], ENT_QUOTES, 'UTF-8'),
					'error'     => '',
				);
			}
		} else {
			$json = array(
				'error' => 'File data not found',
			);
		}

		$this->response->setOutput(json_encode($json));
	}
	
	public function saveWarning() {
		$this->session->data['hide_backup_warning'] = true;
	}

	public function importRelated() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/ka_product_import/importRelated', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		
		$data['action'] = $this->url->link('tool/ka_product_import/impRelated','token=' . $this->session->data['token'] , 'SSL');
		

		

		$data['heading_title'] = "CSV Related Product Import";
		$this->document->setTitle($data['heading_title']);
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['help_sync_product'] = $this->language->get('help_sync_product');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['text_refresh'] = "Select Links and Click Sync Button to get started";
		$data['sort'] = $sort;
		$data['token'] = $this->request->get['token'];
		$data['error'] = array('text'=>"","status"=>"success");
        

		
        
        

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

	

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/ka_related_import.tpl', $data));
	}
	public function impRelated() {
		//die("HEHE");
		//echo "FIle added";
		//return;
		$response = array();
		$errors['msg'] = array();
		$debug = false;
		if ($debug){
			$uploadfile = "";
		}else{
			$uploaddir = DIR_UPLOAD;
	    	$uploadfile = $uploaddir . date("Y-m-d h:i:s") ."-" . basename($_FILES['fileToUpload']['name']);
	    	$FileType = strtolower(pathinfo($uploadfile,PATHINFO_EXTENSION));
	    	if ($FileType != "csv"){
	    		$errors['msg'][] = "Not a valid csv";
	    		echo json_encode($errors);
	    		return;
	    		//$this->redirect($this->url->link('tool/ka_product_import/importRelated', 'token=' . $this->session->data['token'], 'SSL'));
	    	}

	    	if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadfile)) {
	      		$errors['msg'][] = 'Uploaded';
	    	} else {
	       		$errors['msg'][] = 'Cannot write to UPLOAD directory. Permission Error';
	       		echo json_encode($errors);
	    		return;
	       		//$this->redirect($this->url->link('tool/ka_product_import/importRelated', 'token=' . $this->session->data['token'], 'SSL'));
	    	}
	  }
	  //print_r($errors);
		// Check if file is a actually submitted
		//$validationCheck = isset($_POST["submit"]);
		//$validationCheck = $debug;
	  	$validationCheck = true; //for ajax calls
		if($validationCheck) {
			$file = $uploadfile;
			$handle = fopen($file,'r');
			$line_no = 0;
			if($handle !== false) {
				while(!feof($handle)) {
					$line = fgets($handle);
					++$line_no;
					// process the line read.
					if ($line_no == 1)	continue;
					//echo $line;
					$cols = explode(';',$line);
					//echo "<pre>";print_r($cols);echo "</pre>";
					//die();
					//echo "Starting";
					if (isset($cols[3])){
						$rproducts = explode(',',$cols[3]);
						//echo "<pre>";print_r($rp);echo "</pre>";
						foreach ($rproducts as $rp){
							if (isset($rp)){
								//get for related product
								$que = "SELECT product_id FROM `".DB_PREFIX."product` WHERE model ='".$rp."'";
								$result = $this->db->query($que);
								if ($result->num_rows){
									$id  = $cols[0];
									$rid = $result->row['product_id'];
									if ($id != $rid){ //not refer to itself
									$que = "INSERT INTO `".DB_PREFIX."product_related` SET product_id = $id,related_id = $rid,is_wwell = 0 ON DUPLICATE KEY UPDATE is_wwell = 0";
									//echo $que;
									$this->db->query($que);
								}
							}
						}
					}
				 }
				//die();
					//related product ends
					//get for well works product
				 //echo "second";
				 if (isset($cols[4])){
						$wproducts = explode(',',$cols[4]);
						//echo "<pre>";print_r($rp);echo "</pre>";
						foreach ($wproducts as $rp){
							if (isset($rp)){
								//get for related product
								$que = "SELECT product_id FROM `".DB_PREFIX."product` WHERE model ='".$rp."'";
								$result = $this->db->query($que);
								if ($result->num_rows){
									$id  = $cols[0];
									$rid = $result->row['product_id'];
									if ($id != $rid){ //not refer to itself
									$que = "INSERT INTO `".DB_PREFIX."product_related` SET product_id = $id,related_id = $rid,is_wwell = 1 ON DUPLICATE KEY UPDATE is_wwell = 1";
									//echo $que;
									$this->db->query($que);
								}
							}
						}
					}
				 }
				

				//die("HEHEHHE");
				 //echo "Completed";
					
		}
				fclose($handle);
				$errors['msg'][] = "Import successful.";
				echo json_encode($errors);
	    		return;
				//$this->redirect($this->url->link('tool/ka_product_import/importRelated', 'token=' . $this->session->data['token'], 'SSL'));
				//print_r($related);
			} else {
				$errors['msg'][] = "Cannot Open CSV File.";
				echo json_encode($errors);
	    		return;
				//$this->redirect($this->url->link('tool/ka_product_import/importRelated', 'token=' . $this->session->data['token'], 'SSL'));
				
			}
		}
	}
		
}
?>