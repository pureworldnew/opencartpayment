<?php 
class ControllerToolNitro extends Controller { 
	private $error = array();
	private $session_closed = false;
	
	public function index() {
		$this->language->load('tool/nitro');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('view/javascript/nitro/bootstrap/js/bootstrap.min.js');
		$this->document->addScript('view/javascript/nitro/justgage/resources/js/raphael.2.1.0.min.js');
		$this->document->addScript('view/javascript/nitro/justgage/resources/js/justgage.1.0.1.js');
		$this->document->addScript('view/javascript/nitro/nitro.js');
		$this->document->addStyle('view/javascript/nitro/bootstrap/css/bootstrap.min.css');
		$this->document->addStyle('view/javascript/nitro/font-awesome/css/font-awesome.min.css');
		$this->document->addStyle('view/stylesheet/nitro.css');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->user->hasPermission('modify', 'tool/nitro') == false) {
			$this->session->data['error'] = $this->language->get('text_nopermission');
			$this->response->redirect($this->url->link('tool/nitro', 'token=' . $this->session->data['token'], 'SSL'));				
		}
		
		$this->performGetHandlers();
		
		$this->load->model('tool/nitro');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->user->hasPermission('modify', 'tool/nitro')) {
				$this->session->data['success'] = '';
				
				$this->model_tool_nitro->applyNitroCacheHTCompressionRules($_POST);
				$this->model_tool_nitro->applyNitroCacheHTRules($_POST);
				if (empty($_POST['Nitro']['Enabled']) || $_POST['Nitro']['Enabled'] == 'no') {
					unset($_POST['NitroTemp']);
				} else {
					//Check if we are turning NitroPack on after it has been disabled. In such case we want to enable all NitroPack modules
					$persistence = $this->model_tool_nitro->getPersistence();
					if (empty($persistence['Nitro']['Enabled']) || $persistence['Nitro']['Enabled'] == 'no') {
						$_POST['NitroTemp']['ActiveModule'] = array(
							'pagecache' => 'on',
							'cdn_generic' => 'on',
							'db_cache' => 'on',
							'image_cache' => 'on',
							'jquery' => 'on',
							'minifier' => 'on',
							'product_count_fix' => 'on',
							'system_cache' => 'on',
							'pagecache_widget' => 'on'
						);
					}
				}
				$this->model_tool_nitro->setNitroPackModules($_POST);
				if (!empty($_POST['OaXRyb1BhY2sgLSBDb21'])) {
					$_POST['Nitro']['LicensedOn'] = $_POST['OaXRyb1BhY2sgLSBDb21'];
				}
				if (!empty($_POST['cHRpbWl6YXRpb24ef4fe'])) {
					$_POST['Nitro']['License'] = json_decode(base64_decode($_POST['cHRpbWl6YXRpb24ef4fe']),true);
				}
				if ($this->model_tool_nitro->setPersistence($_POST)) {
					$this->session->data['success'] .= $this->language->get('text_success');				
					//$this->response->redirect($this->url->link('tool/nitro', 'token=' . $this->session->data['token'], 'SSL'));
					$this->clearpagecache();
				}
		}
		
		

		if((file_exists(DIR_SYSTEM.'nitro/data/googlepagespeed-desktop.tpl') && !is_writable(DIR_SYSTEM.'nitro/data/googlepagespeed-desktop.tpl')) || (file_exists(DIR_SYSTEM.'nitro/data/googlepagespeed-mobile.tpl') && !is_writable(DIR_SYSTEM.'nitro/data/googlepagespeed-mobile.tpl'))) {
			$this->session->data['error'] = 'Your PHP user does not have permissions to write to files in system/nitro/data/ - Please contact your hosting provider.';	
			$this->response->redirect($this->url->link('tool/nitro', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$persistence = $this->model_tool_nitro->getPersistence();
		$this->data['data'] = $this->model_tool_nitro->getPersistence();
		$this->data['smushit_data'] = $this->model_tool_nitro->getSmushitPersistence();
		$this->data['activeModules'] = $this->model_tool_nitro->getActiveNitroModules();
		
		$hasGzip = (bool)$this->config->get('config_compression') && !empty($persistence['Nitro']['Compress']['HTML']) && $persistence['Nitro']['Compress']['HTML'] == 'yes';
		
		$this->data['hasGzip'] = $hasGzip;
		
		if ($hasGzip) {
			$persistence_temp = $persistence;
			$persistence_temp['Nitro']['Compress']['HTML'] = 'no';
			$this->model_tool_nitro->setPersistence($persistence_temp);
			$this->data['data'] = $persistence_temp;
		}
		
		if ($this->user->hasPermission('modify', 'tool/nitro') == false) {
			$this->data['data']['Nitro']['CDNStandardFTP']['Username'] = '&bull;&bull;&bull;&bull;&bull;&bull;';
			$this->data['data']['Nitro']['CDNStandardFTP']['Password'] = '&bull;&bull;&bull;&bull;&bull;&bull;';
		}
		
		$this->data['widget']['pagespeed'] = $this->model_tool_nitro->getGooglePageSpeedReport(null, array('mobile', 'desktop'));
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		if (isset($this->session->data['error'])) {
    		$this->data['error_warning'] = $this->session->data['error'];
    
			unset($this->session->data['error']);
 		} elseif (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/nitro', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->template = 'tool/nitro.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	public function performGetHandlers() {
		$this->language->load('tool/nitro');
		if ($this->request->server['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
			$this->load->model('tool/nitro');
			switch($_GET['action']) {
				case 'refreshgps': 
					if ($this->user->hasPermission('modify', 'tool/nitro') == false) {
						$this->session->data['error'] = $this->language->get('text_nopermission');
						$this->response->redirect($this->url->link('tool/nitro', 'token=' . $this->session->data['token'], 'SSL'));				
					}
					$this->session->data['success'] = $this->model_tool_nitro->refreshGooglePageSpeedReport();				
					$this->response->redirect($this->url->link('tool/nitro', 'token=' . $this->session->data['token'], 'SSL'));
				break;
					
			}
		}
	}
	
	public function getactivemodules() {
		$this->load->model('tool/nitro');
		if ($this->user->hasPermission('modify', 'tool/nitro') != false) {
			$modules = $this->model_tool_nitro->getActiveNitroModules();
			if ($modules) {
				echo json_encode($modules);
			}
		}
		exit;
	}
	
	public function googlerawrefresh() {
		$this->load->model('tool/nitro');
		
		if ($this->user->hasPermission('modify', 'tool/nitro') != false) {
			$data = $this->model_tool_nitro->getGoogleRawData();
			echo "======== Desktop ========\n";
			echo $data['desktop']."\n";
			echo "======== Mobile ========\n";
			echo $data['mobile']."\n";
			exit;
		} else {
			echo 'You do not have permissions to view this data.';
		}
	}
	
	public function serverinfo() {
		$this->load->model('tool/nitro');
		
		echo json_encode($this->model_tool_nitro->getServerInfo($this->user->hasPermission('modify', 'tool/nitro')));
		
	}
	
	public function performvalidation() {
		$this->load->model('tool/nitro');
		define('MID',12658);
		
		$lcode = (!empty($_POST['l'])) ? $_POST['l'] : '';
		$hostname = (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '' ;
		$hostname = (strstr($hostname,'http://') === false) ? 'http://'.$hostname: $hostname;
		$context = stream_context_create(array('http' => array('header' => 'Referer: '.$hostname)));
        $license = json_decode(file_get_contents('http://isenselabs.com/licenses/checklicense/'.base64_encode($lcode), false, $context),true);
		// check error 
		if (!empty($license['error'])) {
			echo '<div class="alert alert-error">'.$license['error'].'</div>';
			return false;
		}
		// check product match
		if ($license['productId'] != MID) {
			echo '<div class="alert alert-error">Incorrect code - you cannot use license code from another product!</div>';
			return false;			
		}
		// check expire date
		if (strtotime($license['licenseExpireDate']) < time()) {
			echo '<div class="alert alert-error">Your license has expired on '.$license['licenseExpireDate'].'</div>';
			return false;			
		}
		
		//checkdomains 
		$domainPresent = false;
		foreach ($license['licenseDomainsUsed'] as $domain) {
			if (strstr($hostname,$domain) !== false) {
				$domainPresent = true;	
			}
		}
		if ($domainPresent == false) {
			echo '<div class="alert alert-error">Unable to activate license for domain '.$domain.' - Please add your domain to your product license.</div>';
			return false;			
		}
		
		//success, acticvate the license
		$nitro = $this->model_tool_nitro->getPersistence(null,true);
		$nitro['Nitro']['LicensedOn'] = time();
		$nitro['Nitro']['License'] = $license;
		$this->model_tool_nitro->setPersistence($nitro,true);
		echo '<div class="alert alert-success">Licensing successful for domain '.$domain.' - please wait... </div><script> setTimeout(function() { document.location = document.location; } , 1000);  </script>';
	}
	
	public function clearimagecache() {
		$this->load->model('tool/nitro');
		if ($this->model_tool_nitro->clearImageCache() == true) {
			$this->session->data['success'] = 'The Image Cache has been cleared successfully!';
		}
		$this->response->redirect($this->url->link('tool/nitro', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function clearsystemcache() {
		$this->cache->delete('*');
		$this->session->data['success'] = 'The OpenCart System Cache has been cleared successfully!';
		$this->response->redirect($this->url->link('tool/nitro', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function clearpagecache() {
		$this->load->model('tool/nitro');
		if ($this->model_tool_nitro->clearPageCache() == true) {
			if (empty($this->session->data['success'])) {
				$this->session->data['success'] = '';	
			}
			$this->session->data['success'] .= 'The Nitro PageCache has been cleared successfully!';
		}
		$this->response->redirect($this->url->link('tool/nitro', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function cleardbcache() {
		$this->load->model('tool/nitro');
		if ($this->model_tool_nitro->clearDBCache() == true) {
			$this->session->data['success'] = 'The Nitro DB Cache has been cleared successfully!';
		}
		$this->response->redirect($this->url->link('tool/nitro', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function clearjscache() {
		$this->load->model('tool/nitro');
		if ($this->model_tool_nitro->clearJSCache() == true) {
			$this->session->data['success'] = 'The Nitro JS Cache has been cleared successfully!';
		}
		$this->response->redirect($this->url->link('tool/nitro', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function clearcsscache() {
		$this->load->model('tool/nitro');
		if ($this->model_tool_nitro->clearCSSCache() == true) {
			$this->session->data['success'] = 'The Nitro CSS Cache has been cleared successfully!';
		}
		$this->response->redirect($this->url->link('tool/nitro', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function clearvqmodcache() {
		$this->load->model('tool/nitro');
		if ($this->model_tool_nitro->clearVqmodCache() == true) {
			$this->session->data['success'] = 'The vQmod Cache has been cleared successfully!';
		}
		$this->response->redirect($this->url->link('tool/nitro', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function clearnitrocaches() {
		$this->load->model('tool/nitro');

		$result = 
			$this->model_tool_nitro->clearPageCache() &&
			$this->model_tool_nitro->clearDBCache() &&
			$this->model_tool_nitro->clearCSSCache() &&
			$this->model_tool_nitro->clearJSCache() &&
			$this->model_tool_nitro->clearTempJSCache();
			
		
		if ($result) {
			$this->session->data['success'] = 'All Nitro-generated Caches have been cleared successfully!';
		}
		$this->response->redirect($this->url->link('tool/nitro', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function clearallcaches() {
		$this->load->model('tool/nitro');
		
		$this->cache->delete('*');
		
		$result = 
			$this->model_tool_nitro->clearPageCache() &&
			$this->model_tool_nitro->clearDBCache() &&
			$this->model_tool_nitro->clearImageCache() &&
			$this->model_tool_nitro->clearCSSCache() &&
			$this->model_tool_nitro->clearJSCache() &&
			$this->model_tool_nitro->clearTempJSCache() &&
			$this->model_tool_nitro->clearVqmodCache();
			
		
		if ($result) {
			$this->session->data['success'] = 'All caches have been cleared successfully!';
		}
		$this->response->redirect($this->url->link('tool/nitro', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function smushimages() {
		if ($this->user->hasPermission('modify', 'tool/nitro') == false) {
			echo('<div class="smushingDiv"><b>You do not have modify permissions and thus you cannot perform this action!</b>');
			return;
		}
		$this->load->model('tool/nitro');
		$_SESSION['stop_smushing'] = false;
		$this->closeSession();
		$smushedImagesChunk = $this->model_tool_nitro->smushCachedImages();
		$this->openSession();
		$allImages = $_SESSION['smush_progress']['total_images']; //$this->getCacheImagesCount();
		$nonSmushedImgs =$allImages - $smushedImagesChunk;
		echo('<div class="smushingDiv"><b>'.$smushedImagesChunk.'</b> images smushed! <b>'.$nonSmushedImgs.'</b> images are already optimized.</div>');
	}
	
	/**
	 * This function returns json array of all images matching the requirements
	 * Requirements are passed with get parameters
	 */
	
	public function getsmushimages() {
		$this->openSession();
		$this->load->model('tool/nitro');
		$target_dir = DIR_IMAGE.'cache';
		if (!empty($this->request->get['targetDir'])) {
			$tmp_dir = dirname(DIR_APPLICATION).DIRECTORY_SEPARATOR.trim(str_replace('/', DIRECTORY_SEPARATOR, urldecode($this->request->get['targetDir'])), DIRECTORY_SEPARATOR);
			if (file_exists($tmp_dir)) {
				$target_dir = $tmp_dir;
			}
		}
		$total_images = $this->model_tool_nitro->getSmushImages($target_dir);
		$_SESSION['smush_progress'] = array(
			'smushed_images_count' => 0,
			'already_smushed_images_count' => 0,
			'total_images' => count($total_images),
			'kb_saved' => 0,
			'last_smush_timestamp' => 0
		);
		$this->model_tool_nitro->setSmushitPersistence($_SESSION['smush_progress']);
		$_SESSION['total_images'] = count($total_images);
		echo json_encode($total_images);
		exit;
	}
	
	/**
	 * This function smushes list of images
	 * the target images are received with POST parameters
	 */
	public function smushimagelist() {
		if (empty($this->request->post['imageList']) || !is_array($this->request->post['imageList'])) {
			echo json_encode(array('error' => 'Error: Image list not provided!'));
			exit;
		}
		
		$this->load->model('tool/nitro');
		if (empty($_SESSION['smush_progress'])) {
			$_SESSION['smush_progress'] = $this->model_tool_nitro->getSmushitPersistence();
		}
		
		$this->model_tool_nitro->smushImages($this->request->post['imageList']);
		echo json_encode($_SESSION['smush_progress']);
		exit;
	}
	
	public function getsmushprogress() {
		$this->openSession();
		if (empty($_SESSION['smush_progress'])) {
			$this->load->model('tool/nitro');
			$_SESSION['smush_progress'] = $this->model_tool_nitro->getSmushitPersistence();
		}
		
		echo json_encode($_SESSION['smush_progress']);
		$_SESSION['smush_progress']['smushed_files'] = array();
		$this->closeSession();
		exit;
	}
	
	public function stopsmushing() {
		$this->openSession();
		$_SESSION['stop_smushing'] = true;
		$this->closeSession();
		echo json_encode(array('error' => false));
		exit;
	}
	
	public function getcloudflarestats() {
		$this->load->model('tool/nitro');
		$stats = $this->model_tool_nitro->getCloudFlareStats();
		echo('<div class="statsDiv">'.$stats.'</div>');
	}
	
	public function gecacheimagescountnow() {
		echo $this->getCacheImagesCount();
	}
	

	public function getCacheImagesCount($path = '') {
		if (empty($path)) $path = DIR_IMAGE.'cache';
		$size = 0;
		$ignore = array('.','..','cgi-bin','.DS_Store','index.html');
		$files = scandir($path);
		foreach($files as $t) {
			if(in_array($t, $ignore)) continue;
			if (is_dir(rtrim($path, '/') . '/' . $t)) {
				$size += $this->getCacheImagesCount(rtrim($path, '/') . '/' . $t);
			} else {
				$size++;
			}   
		}

		return $size;

	}

	public function saveftp() {
		
		set_error_handler(create_function(
			'$severity, $message, $file, $line',
			'throw new Exception($message . " in file " . $file . " on line " . $line);'
		));
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->user->hasPermission('modify', 'tool/nitro') != false) {
			$this->load->model('tool/nitro');
			
			$persistence = $this->model_tool_nitro->getPersistence();
			
			$persistence['Nitro']['CDNStandardFTP']['Protocol'] = $this->request->post['protocol'];
			$persistence['Nitro']['CDNStandardFTP']['Host'] = $this->request->post['host'];
			$persistence['Nitro']['CDNStandardFTP']['Port'] = $this->request->post['port'];
			$persistence['Nitro']['CDNStandardFTP']['Username'] = $this->request->post['username'];
			$persistence['Nitro']['CDNStandardFTP']['Password'] = $this->request->post['password'];
			$persistence['Nitro']['CDNStandardFTP']['Root'] = $this->request->post['root'];
			$persistence['Nitro']['CDNStandardFTP']['SyncImages'] = $this->request->post['syncImages'] == 'true';
			$persistence['Nitro']['CDNStandardFTP']['SyncCSS'] = $this->request->post['syncCSS'] == 'true';
			$persistence['Nitro']['CDNStandardFTP']['SyncJavaScript'] = $this->request->post['syncJavaScript'] == 'true';
			
			$this->model_tool_nitro->setPersistence($persistence);
			
			try {
				$this->model_tool_nitro->ftp_upload();
				
				$persistence['Nitro']['CDNStandardFTP']['LastUpload'] = date($this->language->get('date_format_long') . ' ' . $this->language->get('time_format'));
				$this->model_tool_nitro->setPersistence($persistence);
				
				echo json_encode(array('success' => 'Success: Upload complete!', 'upload_time' => $persistence['Nitro']['CDNStandardFTP']['LastUpload']));
			} catch (Exception $e) {
				echo json_encode(array('error' => $e->getMessage()));
			}
		} else {
			echo json_encode(array('error' => 'You do not have permissions to upload to FTP.'));
		}
		
		restore_error_handler();
	}
	
	public function getftpprogress() {
		if ($this->user->hasPermission('modify', 'tool/nitro') != false && !empty($_SESSION['nitro_ftp_progress'])) {
			if (!session_id()) session_start();
			
			if (isset($this->request->get['init'])) {
				unset($_SESSION['nitro_ftp_progress']);
			} else {
				echo json_encode($_SESSION['nitro_ftp_progress']); exit;
			}
		}
	}
	
	public function cancelftp() {
		if ($this->user->hasPermission('modify', 'tool/nitro') != false && !empty($_SESSION['nitro_ftp_progress'])) {
			$_SESSION['nitro_ftp_cancel'] = true;
		}
	}
	
	public function saveamazon() {
		
		set_error_handler(create_function(
			'$severity, $message, $file, $line',
			'throw new Exception($message . " in file " . $file . " on line " . $line);'
		));
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->user->hasPermission('modify', 'tool/nitro') != false) {
			
			try {
				$this->load->model('tool/nitro');
				
				$persistence = $this->model_tool_nitro->getPersistence();
				
				$persistence['Nitro']['CDNAmazon']['AccessKeyID'] = $this->request->post['accessKeyID'];
				$persistence['Nitro']['CDNAmazon']['SecretAccessKey'] = $this->request->post['secretAccessKey'];
				$persistence['Nitro']['CDNAmazon']['SyncImages'] = $this->request->post['syncImages'] == 'true';
				$persistence['Nitro']['CDNAmazon']['SyncCSS'] = $this->request->post['syncCSS'] == 'true';
				$persistence['Nitro']['CDNAmazon']['SyncJavaScript'] = $this->request->post['syncJavaScript'] == 'true';
				$persistence['Nitro']['CDNAmazon']['ContinueFrom'] = !empty($this->request->post['continueFrom']) ? $this->request->post['continueFrom'] : '';
				$persistence['Nitro']['CDNAmazon']['Uploaded'] = !empty($this->request->post['uploaded']) ? (int)$this->request->post['uploaded'] : 0;
				$persistence['Nitro']['CDNAmazon']['Init'] = !empty($this->request->get['init']);
				$persistence['Nitro']['CDNAmazon']['Step'] = $this->request->post['step'];
				
				$this->model_tool_nitro->setPersistence($persistence);
			
				$progress = $this->model_tool_nitro->amazon_upload();
				
				$persistence['Nitro']['CDNAmazon']['LastUpload'] = date($this->language->get('date_format_long') . ' ' . $this->language->get('time_format'));

				$this->model_tool_nitro->setPersistence($persistence);
				
				$result = array(
					'finished_upload' => $progress['finished_upload'],
					'step' => $progress['step'],
					'continue_from' => $progress['continue_from'],
					'progress' => $progress['progress']
				);

				if ($progress['finished_upload']) {
					if (empty($result['progress']['cancelled'])) {
						$result['success'] = 'Success: Upload complete!';
					} else {
						$result['success'] = 'Task cancelled.';
					}
					
				}

				echo json_encode($result);
			} catch (Exception $e) {
				echo json_encode(array('error' => $e->getMessage(), 'finished_upload' => true));
			}
			
		} else {
			echo json_encode(array('error' => 'You do not have permissions to upload to Amazon CloudFront/S3 CDN.', 'finished_upload' => true));
		}
		
		restore_error_handler();
	}
	
	public function getamazonprogress() {
		if ($this->user->hasPermission('modify', 'tool/nitro') != false && !empty($_SESSION['nitro_amazon_progress'])) {
			if (!session_id()) session_start();
			
			if (isset($this->request->get['init'])) {
				unset($_SESSION['nitro_amazon_progress']);
			} else {
				echo json_encode($_SESSION['nitro_amazon_progress']); exit;
			}
		}
	}
	
	public function cancelamazon() {
		if ($this->user->hasPermission('modify', 'tool/nitro') != false && !empty($_SESSION['nitro_amazon_progress'])) {
			$_SESSION['nitro_amazon_cancel'] = true;
		}
	}
	
	public function saverackspace() {
		set_error_handler(create_function(
			'$severity, $message, $file, $line',
			'throw new Exception($message . " in file " . $file . " on line " . $line);'
		));
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->user->hasPermission('modify', 'tool/nitro') != false) {
			$this->load->model('tool/nitro');
			
			$persistence = $this->model_tool_nitro->getPersistence();
			
			$persistence['Nitro']['CDNRackspace']['Username'] = $this->request->post['username'];
			$persistence['Nitro']['CDNRackspace']['APIKey'] = $this->request->post['apiKey'];
			$persistence['Nitro']['CDNRackspace']['ServerRegion'] = $this->request->post['serverRegion'];
			$persistence['Nitro']['CDNRackspace']['SyncImages'] = $this->request->post['syncImages'] == 'true';
			$persistence['Nitro']['CDNRackspace']['SyncCSS'] = $this->request->post['syncCSS'] == 'true';
			$persistence['Nitro']['CDNRackspace']['SyncJavaScript'] = $this->request->post['syncJavaScript'] == 'true';
			
			$this->model_tool_nitro->setPersistence($persistence);
			
			try {
				$this->model_tool_nitro->rackspace_upload();
				
				$persistence['Nitro']['CDNRackspace']['LastUpload'] = date($this->language->get('date_format_long') . ' ' . $this->language->get('time_format'));
				$this->model_tool_nitro->setPersistence($persistence);
				
				echo json_encode(array('success' => 'Success: Upload complete!', 'upload_time' => $persistence['Nitro']['CDNRackspace']['LastUpload']));
			} catch (Exception $e) {
				echo json_encode(array('error' => $e->getMessage()));
			}
		} else {
			echo json_encode(array('error' => 'You do not have permissions to upload to Rackspace CloudFront/S3 CDN.'));
		}
		
		restore_error_handler();
	}
	
	public function getrackspaceprogress() {
		if ($this->user->hasPermission('modify', 'tool/nitro') != false && !empty($_SESSION['nitro_rackspace_progress'])) {
			if (!session_id()) session_start();
			
			if (isset($this->request->get['init'])) {
				unset($_SESSION['nitro_rackspace_progress']);
			} else {
				echo json_encode($_SESSION['nitro_rackspace_progress']); exit;
			}
		}
	}
	
	public function cancelrackspace() {
		if ($this->user->hasPermission('modify', 'tool/nitro') != false && !empty($_SESSION['nitro_rackspace_progress'])) {
			$_SESSION['nitro_rackspace_cancel'] = true;
		}
	}
	
	private function isSessionClosed() {
		return $this->session_closed;
	}
	
	private function closeSession() {
		if (session_id() && !$this->session_closed) session_write_close();
		$this->session_closed = true;
	}
	
	private function openSession() {
		if ($this->session_closed) session_start();
		$this->session_closed = false;
		return session_id();
	}
}
?>