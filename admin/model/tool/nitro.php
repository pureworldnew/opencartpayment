<?php
class ModelToolNitro extends Model {
	private $session_closed = false;
	
	public function clearImageCache() {
		$this->loadConfig();
		$output = array();
		
		if ($this->exec_enabled()) {
			exec('rm -rf '.DIR_IMAGE . 'cache/*',$output);
		} else {
			try {
				$this->trunc_folder(DIR_IMAGE . 'cache/');
			} catch (Exception $e) {
				$output = $e->getMessage();
			}
		}
		
		if (empty($output)) {
			return true;	
		} else {
			return false;	
		}
	}
	
	public function clearPageCache() {
		$this->loadConfig();
		$output = array();
		
		if ($this->exec_enabled()) {
			exec('rm -rf '.NITRO_PAGECACHE_FOLDER.'*',$output);
		} else {
			try {
				$this->trunc_folder(NITRO_PAGECACHE_FOLDER);
			} catch (Exception $e) {
				$output = $e->getMessage();
			}
		}
		
		if (empty($output)) {
			return true;	
		} else {
			return false;	
		}
	}
	
	public function clearDBCache() {
		$this->loadConfig();
		$output = array();
		
		if ($this->exec_enabled()) {
			exec('rm -rf ' . NITRO_DBCACHE_FOLDER.'*', $output);
		} else {
			try {
				$this->trunc_folder(NITRO_DBCACHE_FOLDER);
			} catch (Exception $e) {
				$output = $e->getMessage();
			}
		}
		
		if (empty($output)) {
			return true;	
		} else {
			return false;	
		}
	}
	
	public function clearJSCache() {
		$this->loadConfig();
		$output = array();
		$srcFolder = dirname(DIR_APPLICATION) . DS . 'assets' . DS . 'script'.DS;
		
		if ($this->exec_enabled()) {
			exec('rm -rf ' . $srcFolder.'*', $output);
		} else {
			try {
				$this->trunc_folder($srcFolder);
			} catch (Exception $e) {
				$output = $e->getMessage();
			}
		}
		
		if (empty($output)) {
			return true;	
		} else {
			return false;	
		}
	}
	
	public function clearTempJSCache() {
		$this->loadConfig();
		$output = array();
		$srcFolder = DIR_SYSTEM . DS . 'nitro' . DS . 'temp'.DS;
		
		if ($this->exec_enabled()) {
			exec('rm -rf ' . $srcFolder.'*', $output);
		} else {
			try {
				$this->trunc_folder($srcFolder);
			} catch (Exception $e) {
				$output = $e->getMessage();
			}
		}
		
		if (empty($output)) {
			return true;	
		} else {
			return false;	
		}
	}
	
	public function clearCSSCache() {
		$this->loadConfig();
		$output = array();
		$srcFolder = dirname(DIR_APPLICATION) . DS . 'assets' . DS . 'style'.DS;
		
		if ($this->exec_enabled()) {
			exec('rm -rf ' . $srcFolder.'*', $output);
		} else {
			try {
				$this->trunc_folder($srcFolder);
			} catch (Exception $e) {
				$output = $e->getMessage();
			}
		}
		
		if (empty($output)) {
			return true;	
		} else {
			return false;	
		}
	}
	
	public function clearVqmodCache() {
		$this->loadConfig();
		$output = array();
		$srcFolder = dirname(DIR_APPLICATION) . DS . 'vqmod' . DS . 'vqcache' . DS . '*';
		
		if ($this->exec_enabled()) {
			exec('rm -rf ' . $srcFolder, $output);
		} else {
			try {
				$this->trunc_folder($srcFolder);
			} catch (Exception $e) {
				$output = $e->getMessage();
			}
		}
		
		if (empty($output)) {
			$mods_cache = dirname(dirname($srcFolder)).DS.'mods.cache';
			if (file_exists($mods_cache)) {
				if (unlink($mods_cache)) {
					return true;
				} else {
					return false;
				}
			}
			return true;
		} else {
			return false;	
		}
	}
	
	public function loadConfig() {
		require_once(DIR_SYSTEM.'nitro/config.php');
	}
	
	public function loadCore() {
		require_once(DIR_SYSTEM.'nitro/core/core.php');
	}
	
	public function loadCDN() {
		$this->loadConfig();
		require_once(DIR_SYSTEM.'nitro/core/cdn.php');
	}

	public function setPersistence($data) {
		$this->loadConfig();
		$this->loadCore();
		return setNitroPersistence($data);
	}
	
	public function getPersistence() {
		$this->loadConfig();
		$this->loadCore();
		return getNitroPersistence();
	}
	
	public function getSmushitPersistence() {
		$this->loadConfig();
		$this->loadCore();
		return getNitroSmushitPersistence();
	}
	
	public function setSmushitPersistence($data) {
		$this->loadConfig();
		$this->loadCore();
		return setNitroSmushitPersistence($data);
	}
	
	public function refreshGooglePageSpeedReport() {
		$this->loadConfig();
		$this->loadCore();
		return refreshGooglePageSpeedReport();
	}
	
	public function getGoogleRawData() {
		$this->loadConfig();
		$this->loadCore();
		refreshGooglePageSpeedReport();
		return getGooglePageSpeedReport();
	}
	
	public function getGooglePageSpeedReport($setting = null, $strategies = array()) {
		$this->loadConfig();
		$this->loadCore();
		return getGooglePageSpeedReport($setting, $strategies);
	}
	
	public function setNitroPackModules($settings) {
		$ds = DIRECTORY_SEPARATOR;
		$dir = dirname(DIR_APPLICATION).$ds.'vqmod'.$ds.'xml'.$ds;
		if (!empty($settings['NitroTemp']['ActiveModule']['pagecache'])) {
			if (file_exists($dir.'nitro_pagecache.xml_')) {
				rename($dir.'nitro_pagecache.xml_', $dir.'nitro_pagecache.xml');
			}
		} else {
			if (file_exists($dir.'nitro_pagecache.xml')) {
				rename($dir.'nitro_pagecache.xml', $dir.'nitro_pagecache.xml_');
			}
		}
		if (!empty($settings['NitroTemp']['ActiveModule']['cdn_generic'])) {
			if (file_exists($dir.'nitro_cdn_generic.xml_')) {
				rename($dir.'nitro_cdn_generic.xml_', $dir.'nitro_cdn_generic.xml');
			}
		} else {
			if (file_exists($dir.'nitro_cdn_generic.xml')) {
				rename($dir.'nitro_cdn_generic.xml', $dir.'nitro_cdn_generic.xml_');
			}
		}
		if (!empty($settings['NitroTemp']['ActiveModule']['db_cache'])) {
			if (file_exists($dir.'nitro_db_cache.xml_')) {
				rename($dir.'nitro_db_cache.xml_', $dir.'nitro_db_cache.xml');
			}
		} else {
			if (file_exists($dir.'nitro_db_cache.xml')) {
				rename($dir.'nitro_db_cache.xml', $dir.'nitro_db_cache.xml_');
			}
		}
		if (!empty($settings['NitroTemp']['ActiveModule']['image_cache'])) {
			if (file_exists($dir.'nitro_image_cache.xml_')) {
				rename($dir.'nitro_image_cache.xml_', $dir.'nitro_image_cache.xml');
			}
		} else {
			if (file_exists($dir.'nitro_image_cache.xml')) {
				rename($dir.'nitro_image_cache.xml', $dir.'nitro_image_cache.xml_');
			}
		}
		if (!empty($settings['NitroTemp']['ActiveModule']['jquery'])) {
			if (file_exists($dir.'nitro_jquery.xml_')) {
				rename($dir.'nitro_jquery.xml_', $dir.'nitro_jquery.xml');
			}
		} else {
			if (file_exists($dir.'nitro_jquery.xml')) {
				rename($dir.'nitro_jquery.xml', $dir.'nitro_jquery.xml_');
			}
		}
		if (!empty($settings['NitroTemp']['ActiveModule']['minifier'])) {
			if (file_exists($dir.'nitro_minifier.xml_')) {
				rename($dir.'nitro_minifier.xml_', $dir.'nitro_minifier.xml');
			}
		} else {
			if (file_exists($dir.'nitro_minifier.xml')) {
				rename($dir.'nitro_minifier.xml', $dir.'nitro_minifier.xml_');
			}
		}
		if (!empty($settings['NitroTemp']['ActiveModule']['product_count_fix'])) {
			if (file_exists($dir.'nitro_product_count_fix.xml_')) {
				rename($dir.'nitro_product_count_fix.xml_', $dir.'nitro_product_count_fix.xml');
			}
		} else {
			if (file_exists($dir.'nitro_product_count_fix.xml')) {
				rename($dir.'nitro_product_count_fix.xml', $dir.'nitro_product_count_fix.xml_');
			}
		}
		if (!empty($settings['NitroTemp']['ActiveModule']['system_cache'])) {
			if (file_exists($dir.'nitro_system_cache.xml_')) {
				rename($dir.'nitro_system_cache.xml_', $dir.'nitro_system_cache.xml');
			}
		} else {
			if (file_exists($dir.'nitro_system_cache.xml')) {
				rename($dir.'nitro_system_cache.xml', $dir.'nitro_system_cache.xml_');
			}
		}
		if (!empty($settings['NitroTemp']['ActiveModule']['pagecache_widget'])) {
			if (file_exists($dir.'nitro_pagecache_widget.xml_')) {
				rename($dir.'nitro_pagecache_widget.xml_', $dir.'nitro_pagecache_widget.xml');
			}
		} else {
			if (file_exists($dir.'nitro_pagecache_widget.xml')) {
				rename($dir.'nitro_pagecache_widget.xml', $dir.'nitro_pagecache_widget.xml_');
			}
		}
	}
	
	public function getActiveNitroModules() {
		$active_modules = array();
		$ds = DIRECTORY_SEPARATOR;
		$dir = dirname(DIR_APPLICATION).$ds.'vqmod'.$ds.'xml'.$ds;
		if (file_exists($dir.'nitro_pagecache.xml')) {
				$active_modules[] = 'pagecache';
		}
		if (file_exists($dir.'nitro_cdn_generic.xml')) {
				$active_modules[] = 'cdn_generic';
		}
		if (file_exists($dir.'nitro_db_cache.xml')) {
				$active_modules[] = 'db_cache';
		}
		if (file_exists($dir.'nitro_image_cache.xml')) {
				$active_modules[] = 'image_cache';
		}
		if (file_exists($dir.'nitro_jquery.xml')) {
				$active_modules[] = 'jquery';
		}
		if (file_exists($dir.'nitro_minifier.xml')) {
				$active_modules[] = 'minifier';
		}
		if (file_exists($dir.'nitro_product_count_fix.xml')) {
				$active_modules[] = 'product_count_fix';
		}
		if (file_exists($dir.'nitro_system_cache.xml')) {
				$active_modules[] = 'system_cache';
		}
		if (file_exists($dir.'nitro_pagecache_widget.xml')) {
				$active_modules[] = 'pagecache_widget';
		}
		return $active_modules;
	}
	
	public function smushCachedImages() {
		$this->closeSession();
		require_once(DIR_APPLICATION.'../assets/smushit.php');
		$cacheImagesDir = DIR_IMAGE.'cache';
		$images = $this->onlyImages($this->directoryToArray($cacheImagesDir , true));
		$total_images = count($images);
		$smushedNumber = 0;
		$files = array_chunk($images, 3);
		
		//$file = DIR_SYSTEM . 'nitro/data/smush_refresh.cache';
		
		//file_put_contents($file, '');
		
		$this->openSession();
		$_SESSION['smush_progress'] = array(
			'smushed_images_count' => 0,
			'total_images' => count($total_images),
			'kb_saved' => 0,
			'last_smush_timestamp' => 0,
			'smushed_files' => array(),
			'messages' => array()
		);
		$this->setSmushitPersistence($_SESSION['smush_progress']);
		$this->closeSession();
		
		// Take a batch of three files
		foreach($files as $batch) {
			try {
				// Compress the batch 
				//$this->setSmushProgress($smushedNumber, 0, time(), $file);
				$smushit = new SmushIt($batch, SmushIt::LOCAL_ORIGIN);
				// And finaly, replace original files by their compressed version
				foreach($smushit->get() as $k => $file) {
					if (!$this->smushCanContinue()) return $smushedNumber;
					
					// Sometimes, Smush.it convert files. We don't want that to happen.
					
					$src = pathinfo($file[0]->source, PATHINFO_EXTENSION);
					$dst = pathinfo($file[0]->destination, PATHINFO_EXTENSION);
					if ($src == $dst AND copy($file[0]->destination, $file[0]->source)) {
						// Success !
						//echo 'Smushed File: '.$source.'<br>';
						$smushedNumber++;
						$this->setSmushProgress($smushedNumber, ($file[0]->sourceSize - $file[0]->destinationSize)/1024, time(), $file[0]->source, $file[0]->savings);
					} else {
						$this->setSmushProgressMessage('Skip: SmushIt converted from  ' . $src . ' to ' . $dst);
					}
				}
			} catch(Exception $e) {
				$this->setSmushProgressMessage($e->getMessage());
				//$this->log->write($e->getMessage());
				continue;
			}
		}

		return $smushedNumber;
	}
	
	public function smushImages($imageList) {
		$smushedNumber = $_SESSION['smush_progress']['smushed_images_count'];
		$alreadySmushedNumber = $_SESSION['smush_progress']['already_smushed_images_count'];
		require_once(DIR_APPLICATION.'../assets/smushit.php');
		$_SESSION['smush_progress']['smushed_files'] = array();
		$_SESSION['smush_progress']['messages'] = array();
		
		try {
			$smushit = new SmushIt($imageList, SmushIt::LOCAL_ORIGIN);
			foreach($smushit->get() as $k => $file) {
				$smushedNumber++;
				
				$src = pathinfo($file[0]->source, PATHINFO_EXTENSION);
				$dst = pathinfo($file[0]->destination, PATHINFO_EXTENSION);
				if ($src == $dst AND $this->urlCopy($file[0]->destination, $file[0]->source)) {
					$this->setSmushProgress($smushedNumber, ($file[0]->sourceSize - $file[0]->destinationSize)/1024, time(), $file[0]->source, $file[0]->savings);
				} else {
					$this->setSmushProgressMessage('Skip: SmushIt converted from  ' . $src . ' to ' . $dst);
				}
			}
		} catch(Exception $e) {
			$alreadySmushedNumber++;
			$this->setSmushProgress($smushedNumber, 0, false, false, false, $alreadySmushedNumber);
			$this->setSmushProgressMessage($e->getMessage());
		}

		return $smushedNumber;
	}
	
	public function getSmushImages($dir) {
		//$cacheImagesDir = DIR_IMAGE.'cache';
		if (is_dir($dir)) {
			return $this->onlyImages($this->directoryToArray($dir , true));
		} else {
			return array($dir);
		}
	}
	
	private function urlCopy($src, $dst) {
		if(ini_get('allow_url_fopen')) {
			return copy($src, $dst);
		} else {
			$tmpDest = $dst.'tmp';
		 
			$fp = fopen($tmpDest, 'w');
		 
			$ch = curl_init($src);
			curl_setopt($ch, CURLOPT_FILE, $fp);
		 
			$data = curl_exec($ch);
		 
			curl_close($ch);
			fclose($fp);
			if (file_exists($tmpDest)) {
				if (copy($dst, $tmpDest)) {
					unlink($tmpDest);
					return true;
				} else {
					unlink($tmpDest);
					return false;
				}
			}
		}
		return false;
	}
	
	private function onlyImages($files) {
		$imgs = array();
		foreach($files as $file) {
			$tmpfile = strtolower($file);
			if (strstr($tmpfile,'.png') === false && strstr($tmpfile,'.gif') === false && strstr($tmpfile,'.jpg') === false && strstr($tmpfile,'.jpeg') === false) {
				
			} else {
				if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
					$catalogBase = HTTPS_CATALOG;
				} else {
					$catalogBase = HTTP_CATALOG;
				}
				$imgs[] = $file;
			}
		}
		return $imgs;
	}
	
	private function directoryToArray($directory, $recursive) {
		$array_items = array();
		if (file_exists($directory) && is_dir($directory) && $handle = opendir($directory)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					if (is_dir($directory. "/" . $file)) {
						if($recursive) {
							$array_items = array_merge($array_items, $this->directoryToArray($directory. "/" . $file, $recursive));
						}
						$file = $directory . "/" . $file;
						$array_items[] = preg_replace("/\/\//si", "/", $file);
					} else {
						$file = $directory . "/" . $file;
						$array_items[] = preg_replace("/\/\//si", "/", $file);
					}
				}
			}
			closedir($handle);
		}
		return $array_items;
	}
	
	public function getCloudFlareStats() {
		$this->loadConfig();
		$this->loadCore();
	}
	
	
	public function applyNitroCacheHTRules($post) {
		$this->loadConfig();
		$this->loadCore();
		$pers = getNitroPersistence();
		
		$htaccessFileContent = $this->getHtaccessFileContent();
		$old_content = $this->extractNitrocodeFromHtaccessFile($htaccessFileContent);
		
		if ((string)$old_content != '') {
			$newHtaccessFileContent = str_replace($old_content,'',$htaccessFileContent);
		} else {
			$newHtaccessFileContent = $htaccessFileContent;
		}
		
		$this->setHtaccessFileContent($newHtaccessFileContent);
		
		if (empty($post['Nitro']['BrowserCache']['Enabled']) || $post['Nitro']['BrowserCache']['Enabled'] == 'no') {
			return false;
		}

		$htrules = '# STARTNITRO'.PHP_EOL;
		
		$htrules .= 'RewriteRule .* - [E=HTTP_IF_MODIFIED_SINCE:%{HTTP:If-Modified-Since}]'.PHP_EOL;
		$htrules .= 'ExpiresActive On'.PHP_EOL;
		
		if (!empty($post['Nitro']['BrowserCache']['CSSJS']['Period']) && $post['Nitro']['BrowserCache']['CSSJS']['Period'] != 'no-cache' && $post['Nitro']['BrowserCache']['Enabled'] == 'yes') {
			$maxage = $post['Nitro']['BrowserCache']['CSSJS']['Period'];
			$htrules .= ''.PHP_EOL;
			$htrules .= '#CSS JS XML TXT - '.strtoupper($maxage).PHP_EOL;
			$htrules .= '<FilesMatch "\.(xml|txt|css|js)$">'.PHP_EOL;
			$htrules .= 'Header set Cache-Control "max-age='.(string)(strtotime($maxage)-time()).', public"'.PHP_EOL;
			$htrules .= 'ExpiresDefault "access plus '.$maxage.'"'.PHP_EOL;
			$htrules .= 'SetOutputFilter DEFLATE'.PHP_EOL;
			$htrules .= '</FilesMatch>'.PHP_EOL;
		}
		
		if (!empty($post['Nitro']['BrowserCache']['Images']['Period']) && $post['Nitro']['BrowserCache']['Images']['Period'] != 'no-cache' && $post['Nitro']['BrowserCache']['Enabled'] == 'yes') {
			$maxage = $post['Nitro']['BrowserCache']['Images']['Period'];
			$htrules .= ''.PHP_EOL;
			$htrules .= '#JPG JPEG PNG GIF SWF SVG - '.strtoupper($maxage).PHP_EOL;
			$htrules .= '<FilesMatch "\.(jpg|jpeg|png|gif|swf|svg|JPG|JPEG|PNG|GIF|SWF|SVG)$">'.PHP_EOL;
			$htrules .= 'Header set Cache-Control "max-age='.(string)(strtotime($maxage)-time()).', public"'.PHP_EOL;
			$htrules .= 'Header set Last-Modified "Wed, 05 Jun 2009 06:40:46 GMT"'.PHP_EOL;
			$htrules .= 'ExpiresDefault "access plus '.$maxage.'"'.PHP_EOL;
			$htrules .= 'SetOutputFilter DEFLATE'.PHP_EOL;
			$htrules .= '</FilesMatch>'.PHP_EOL;
		}

		if (!empty($post['Nitro']['BrowserCache']['Icons']['Period']) && $post['Nitro']['BrowserCache']['Icons']['Period'] != 'no-cache' && $post['Nitro']['BrowserCache']['Enabled'] == 'yes') {
			$maxage = $post['Nitro']['BrowserCache']['Icons']['Period'];
			$htrules .= ''.PHP_EOL;
			$htrules .= '#OTF ICO PDF FLV - '.strtoupper($maxage).PHP_EOL;
			$htrules .= '<FilesMatch "\.(otf|ico|pdf|flv)$">'.PHP_EOL;
			$htrules .= 'Header set Cache-Control "max-age='.(string)(strtotime($maxage)-time()).', public"'.PHP_EOL;
			$htrules .= 'ExpiresDefault "access plus '.$maxage.'"'.PHP_EOL;
			$htrules .= 'SetOutputFilter DEFLATE'.PHP_EOL;
			$htrules .= '</FilesMatch>'.PHP_EOL;
		}

		if (!empty($post['Nitro']['BrowserCache']['Enabled']) && $post['Nitro']['BrowserCache']['Enabled'] == 'yes') {
			$htrules .= ''.PHP_EOL;
			$htrules .= '#HTML HTM PHP'.PHP_EOL;
			$htrules .= '<FilesMatch "\.(html|htm|php)$">'.PHP_EOL;
			$htrules .= 'SetOutputFilter DEFLATE'.PHP_EOL;
			$htrules .= '</FilesMatch>'.PHP_EOL;
		}

		$htrules .= '# ENDNITRO'.PHP_EOL;

		$newHtaccessFileContent = $htrules . $this->getHtaccessFileContent();

		return $this->setHtaccessFileContent($newHtaccessFileContent);
	}
	
	public function applyNitroCacheHTCompressionRules($post) {
		$this->loadConfig();
		$this->loadCore();
		$pers = getNitroPersistence();
		
		$htaccessFileContent = $this->getHtaccessFileContent();
		$old_content = $this->extractNitrocodeCompressFromHtaccessFile($htaccessFileContent);
		
		if ((string)$old_content != '') {
			$newHtaccessFileContent = str_replace($old_content,'',$htaccessFileContent);
		} else {
			$newHtaccessFileContent = $htaccessFileContent;
		}
		
		$this->setHtaccessFileContent($newHtaccessFileContent);
		
		if (empty($post['Nitro']['Compress']['Enabled']) || $post['Nitro']['Compress']['Enabled'] == 'no') {
			return false;
		}

		if (
			!empty($post['Nitro']['BrowserCache']['Enabled']) && 
			$post['Nitro']['BrowserCache']['Enabled'] == 'yes' &&
			!empty($post['Nitro']['BrowserCache']['Headers']['Pages']['Expires']) &&
			!empty($post['Nitro']['BrowserCache']['CSSJS']['Period'])
		) {
			switch($post['Nitro']['BrowserCache']['CSSJS']['Period']) {
				case '1 week' : {
					$browser_cache = 7 * 24 * 3600;
				} break;
				case '1 month' : {
					$browser_cache = 30 * 24 * 3600;
				} break;
				case '6 months' : {
					$browser_cache = 6 * 30 * 24 * 3600;
				} break;
				case '1 year' : {
					$browser_cache = 365 * 24 * 3600;
				} break;
				default : {
					$browser_cache = 0;
				}
			}
		}

		$htrules = '# STARTCOMPRESSNITRO'.PHP_EOL;
		
		if (!empty($post['Nitro']['Compress']['CSS']) && $post['Nitro']['Compress']['CSS'] == 'yes') {
			$htrules .= ''.PHP_EOL;
			$htrules .= 'RewriteCond %{SCRIPT_FILENAME} !-d'.PHP_EOL;
			$htrules .= 'RewriteRule ^(\/?((catalog)|(assets)).+)\.css$ assets/style.php?l=4&p=$1' . (!empty($browser_cache) ? '&c=' . $browser_cache : '') . ' [NC,L]'.PHP_EOL;
		}
		
		if (!empty($post['Nitro']['Compress']['JS']) && $post['Nitro']['Compress']['JS'] == 'yes') {
			$htrules .= ''.PHP_EOL;
			$htrules .= 'RewriteCond %{SCRIPT_FILENAME} !-d'.PHP_EOL;
			$htrules .= 'RewriteRule ^(\/?((catalog)|(assets)).+)\.js$ assets/script.php?l=4&p=$1' . (!empty($browser_cache) ? '&c=' . $browser_cache : '') . ' [NC,L]'.PHP_EOL;
		}

		$htrules .= PHP_EOL.'# ENDCOMPRESSNITRO'.PHP_EOL;

		$newHtaccessFileContent = $htrules . $this->getHtaccessFileContent();

		return $this->setHtaccessFileContent($newHtaccessFileContent);
	}
	
	public function ftp_upload() {
		$this->loadCDN();
		$persistence = $this->getPersistence();
		
		$data = $persistence['Nitro']['CDNStandardFTP'];
		
		$this->ftp_set_progress('Initializing connection...', 0, 0, true);
		
		if ($data['Protocol'] == 'ftps' && !function_exists('ftp_ssl_connect')) {
			throw new Exception('Your server does not support FTPS.');
		}
		if ($data['Protocol'] == 'ftp' && !function_exists('ftp_connect')) {
			throw new Exception('Your server does not support FTP.');
		}
		
		$port = !empty($data['Port']) ? (int)trim($data['Port']) : 21;
		
		$server = parse_url(trim($data['Host']));
		if (empty($server['scheme'])) {
			$server = $server['path'];
		} else $server = $server['host'];
		
		if (!empty($server)) {
			
			if ($data['Protocol'] == 'ftps') {
				$connection = ftp_ssl_connect($server, $port);
			} else {
				$connection = ftp_connect($server, $port);
			}
			
			if ($connection !== FALSE) {
				
				if (ftp_login($connection, $data['Username'], $data['Password'])) {
					
					$root = '/' . implode('/', array_filter(explode('/', $data['Root']))) . '/';
					if (ftp_chdir($connection, $root)) {
						$this->loadConfig();
						$this->loadCore();
						
						// The connection is successful. We can now start to upload :)
						// clearFTPPersistence();
						
						$this->ftp_set_progress('Scanning files...');
						
						$files = array();
						$site_root = dirname(DIR_SYSTEM) . '/';
						
						if (!empty($data['SyncCSS'])) {
							$files = array_merge($files, $this->list_files_with_ext($site_root, unserialize(NITRO_EXTENSIONS_CSS)));
						}
						
						if (!empty($data['SyncJavaScript'])) {
							$files = array_merge($files, $this->list_files_with_ext($site_root, unserialize(NITRO_EXTENSIONS_JS)));
						}
						
						if (!empty($data['SyncImages'])) {
							$files = array_merge($files, $this->list_files_with_ext($site_root, unserialize(NITRO_EXTENSIONS_IMG)));
						}
						
						$all_size = 0;
						$admin_folder_parts = array_filter(explode('/', DIR_APPLICATION));
						$admin_folder = array_pop($admin_folder_parts) . '/';
						$site_root = dirname(DIR_SYSTEM) . '/';
						
						clearstatcache(true);
						foreach ($files as $i => $file) {
							$destination = substr($file, strlen($site_root));
							// If in admin folder, omit
							if (stripos($destination, $admin_folder) === 0) {
								unset($files[$i]);
								continue;
							}
							if (file_exists($file) && is_file($file)) {
								$all_size += filesize($file);
							} else {
								unset($files[$i]);
							}
						}
						
						$this->ftp_set_progress('Uploading files...', 0, $all_size);
						
						$this->ftp_upload_files($connection, $root, $files);
						
						$this->ftp_set_progress('Task finished!');
						
						if ($this->session_closed) {
							session_start();
							$this->session_closed = false;
						}
					} else throw new Exception('Could not go to the specified directory.');
					
				} else throw new Exception('Could not login with the specified credentials.');
				
			} else throw new Exception('Could not connect to the specified server and port.');
			
		} else throw new Exception('Invalid server name.');
		
	}
	
	private function ftp_upload_files($connection, $root, &$output) {
		$this->loadConfig();
		$this->loadCore();
		
		$site_root = dirname(DIR_SYSTEM) . '/';
		
		foreach ($output as $file) {
			
			if ($this->session_closed) {
				session_start();
				$this->session_closed = false;
			}
			if (!empty($_SESSION['nitro_ftp_cancel'])) {
				unset($_SESSION['nitro_ftp_cancel']);
				session_write_close();
				$this->session_closed = true;
				break;
			}
			
			$source = $file;
			$destination = substr($file, strlen($site_root));
			
			$destination_folders = array_filter(explode('/', dirname($destination)));
			$destination_file = basename($destination);
			$destination_folder = $root;
			foreach ($destination_folders as $folder) {
				$listing = ftp_rawlist($connection, $destination_folder);
				
				$has_destination = false;
				
				foreach ($listing as $element) {
					if (preg_match('~^d(.*?) \d{1,2} \d{1,2}:\d\d ' . $folder . '$~', $element, $matches) !== FALSE && !empty($matches)) {
						$has_destination = true;
						break;
					}
				}
				
				$destination_folder .= $folder . '/';
				
				if (!$has_destination) {
					ftp_mkdir($connection, $destination_folder);
				}
				
				ftp_chdir($connection, $destination_folder);
			}
			
			if (ftp_put($connection, $destination_file, $source, FTP_BINARY)) {
				$this->ftp_set_progress('Uploaded ' . $destination_folder . $destination_file, filesize($source));
				setFTPPersistence($destination);
			} else {
				throw new Exception('Could not upload ' . $destination_folder . $destination_file);
			}
		}
	}
	
	private function ftp_set_progress($message, $uploaded = 0, $all = 0, $init = false) {
		if ($this->session_closed) {
			session_start();
			$this->session_closed = false;
		}
		if (!$this->session_closed) {
			if ($init) unset($_SESSION['nitro_ftp_progress']);
			
			if ($all) {
				$_SESSION['nitro_ftp_progress']['all_size'] = $all;
				$_SESSION['nitro_ftp_progress']['uploaded_size'] = 0;
				$_SESSION['nitro_ftp_progress']['percent'] = 0;
				$_SESSION['nitro_ftp_progress']['init_time'] = time();
			}
			
			unset($_SESSION['nitro_ftp_progress']['message']);
			$_SESSION['nitro_ftp_progress']['message'] = $message;
			
			if (!empty($_SESSION['nitro_ftp_progress']['all_size']) && !empty($uploaded)) {
				$_SESSION['nitro_ftp_progress']['uploaded_size'] += $uploaded;
				$_SESSION['nitro_ftp_progress']['percent'] = ceil(100*$_SESSION['nitro_ftp_progress']['uploaded_size']/$_SESSION['nitro_ftp_progress']['all_size']);
				$delta = (time() - $_SESSION['nitro_ftp_progress']['init_time']);
				$_SESSION['nitro_ftp_progress']['speed'] = $delta ? $_SESSION['nitro_ftp_progress']['uploaded_size']/(time() - $_SESSION['nitro_ftp_progress']['init_time']) : $_SESSION['nitro_ftp_progress']['uploaded_size'];
				$_SESSION['nitro_ftp_progress']['message'] .= '<br />Progress: ' . $_SESSION['nitro_ftp_progress']['percent'] . '%';
				$_SESSION['nitro_ftp_progress']['message'] .= '<br />Speed: ' . $this->sizeToString($_SESSION['nitro_ftp_progress']['speed']) . '/s';
				$time = ceil($_SESSION['nitro_ftp_progress']['all_size'] - $_SESSION['nitro_ftp_progress']['uploaded_size'])/$_SESSION['nitro_ftp_progress']['speed'];
				
				$_SESSION['nitro_ftp_progress']['message'] .= '<br />Time remaining: ' . (str_pad(floor($time / 3600), 2, '0', STR_PAD_LEFT) . ':' . str_pad(floor($time % 3600 / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad(($time % 60), 2, '0', STR_PAD_LEFT));
				
			}
			
			session_write_close();
			$this->session_closed = true;
		}
	}
	
	private function sizeToString($size) {
		$count = 0;
		for ($i = $size; $i >= 1024; $i /= 1024) $count++;
		switch ($count) {
			case 0 : $suffix = ' B'; break;
			case 1 : $suffix = ' KB'; break;
			case 2 : $suffix = ' MB'; break;
			case 3 : $suffix = ' GB'; break;
			case ($count >= 4) : $suffix = ' TB'; break;
		}
		return round($i, 2) . $suffix;
	}
	
	public function amazon_upload() {
		$this->loadCDN();
		$config = $this->getPersistence();

		if (!class_exists('NitroFiles')) require_once(DIR_SYSTEM . 'nitro/lib/NitroFiles.php');

		if (!class_exists('S3')) require_once(DIR_SYSTEM . 'nitro/lib/S3.php');
		$s3 = new S3($config['Nitro']['CDNAmazon']['AccessKeyID'], $config['Nitro']['CDNAmazon']['SecretAccessKey']);

		$result = array(
			'finished_upload' => false,
			'step' => $config['Nitro']['CDNAmazon']['Step'],
			'continue_from' => $config['Nitro']['CDNAmazon']['ContinueFrom'],
			'progress' => array(
				'message' => 'Uploading files...',
				'percent' => 0,
				'uploaded' => !empty($config['Nitro']['CDNAmazon']['Uploaded']) ? (int)$config['Nitro']['CDNAmazon']['Uploaded'] : 0
			)
		);

		$valid_extensions = array();

		if (!empty($config['Nitro']['CDNAmazon']['SyncCSS'])) {
			$valid_extensions = array_merge($valid_extensions, unserialize(NITRO_EXTENSIONS_CSS));
		}
		if (!empty($config['Nitro']['CDNAmazon']['SyncJavaScript'])) {
			$valid_extensions = array_merge($valid_extensions, unserialize(NITRO_EXTENSIONS_JS));
		}
		if (!empty($config['Nitro']['CDNAmazon']['SyncImages'])) {
			$valid_extensions = array_merge($valid_extensions, unserialize(NITRO_EXTENSIONS_IMG));
		}

		$sys_folder = array_filter(explode('/', DIR_APPLICATION));
		$admin_folder = array_pop($sys_folder);

		$files = new NitroFiles(array(
			'start' => $config['Nitro']['CDNAmazon']['ContinueFrom'],
			'ext' => $valid_extensions,
			'root' => dirname(DIR_SYSTEM),
			'start' => $config['Nitro']['CDNAmazon']['Step'] == 'list' ? $config['Nitro']['CDNAmazon']['ContinueFrom'] : '',
			'batch' => 100,
			'rules' => array(
				array(
					'ext' => unserialize(NITRO_EXTENSIONS_IMG),
					'rule' => '/(catalog|image\/cache)/i'
				),
				array(
					'rule' => '/' . $admin_folder . '/',
					'match' => false
				),
				array(
					'rule' => '/blog/',
					'match' => false
				)
			)
		));

		if ($config['Nitro']['CDNAmazon']['Init']) {
			unset($_SESSION['nitro_amazon_progress']);
			unset($_SESSION['nitro_amazon_cancel']);
			$this->amazon_init_db();
			$files->clearStatCache();
		} else {
			//session_write_close();
		}

		if (empty($_SESSION['nitro_amazon_progress'])) {
			/*$_SESSION['nitro_amazon_progress'] = array(
				'all' => $files->totalSize()
			);*/
		}

		if ($config['Nitro']['CDNAmazon']['Step'] == 'list') {
			$items = $files->find();

			if (!empty($items)) {
				$result['progress']['message'] = 'Looking for files...';
				foreach ($items as $item) {
					if (!empty($_SESSION['nitro_amazon_cancel'])) {
						$result['finished_upload'] = true;
						$result['progress']['message'] = 'Cancelled...';
						$result['progress']['cancelled'] = true;
						break;
					}

					$this->db->query("INSERT INTO " . DB_PREFIX . "nitro_amazon_files SET file='" . $this->db->escape($item['rel_path']) . "', size='" . $item['size'] . "', realpath='" . $item['full_path'] . "', uploaded=0");

					$result['progress']['message'] = 'Found ' . $item['rel_path'];
					$result['continue_from'] = $item['rel_path'];
				}
			} else {
				$result['progress']['message'] = 'Starting upload...';
				$result['step'] = 'upload';
				$result['continue_from'] = '0';

				$total_size = $this->db->query("SELECT SUM(size) as size FROM " . DB_PREFIX . "nitro_amazon_files");

				$_SESSION['nitro_amazon_progress'] = array(
					'all' => (int)$total_size->row['size']
				);
			}
			
		} else if ($config['Nitro']['CDNAmazon']['Step'] == 'upload') {
			$items = $this->db->query("SELECT * FROM " . DB_PREFIX . "nitro_amazon_files LIMIT " . (int)$config['Nitro']['CDNAmazon']['ContinueFrom'] . ', 50');

			if ($items->num_rows) {
				$buckets = $s3->listBuckets();

				foreach ($items->rows as $item) {
					if (!empty($_SESSION['nitro_amazon_cancel'])) {
						$result['finished_upload'] = true;
						$result['progress']['message'] = 'Cancelled...';
						$result['progress']['cancelled'] = true;
						break;
					}

					if (empty($_SESSION['nitro_amazon_progress']['start'])) {
						if (!session_id()) session_start();
						$_SESSION['nitro_amazon_progress']['start'] = time();
					}

					$this->amazon_upload_file($s3, $item['realpath'], $item['file'], $config, $buckets);

					$this->db->query("UPDATE " . DB_PREFIX . "nitro_amazon_files SET uploaded=1 WHERE id='" . $item['id'] . "'");

					$all = $_SESSION['nitro_amazon_progress']['all'];
					$start = $_SESSION['nitro_amazon_progress']['start'];

					$result['progress']['uploaded'] += $item['size'];
					$result['progress']['percent'] = ceil(($result['progress']['uploaded'] * 100) / $all);
					$result['continue_from'] = (int)$config['Nitro']['CDNAmazon']['ContinueFrom'] + 50;

					$interval = (time() - $start);
					$interval = empty($interval) ? 1 : $interval;
					$speed = ceil($result['progress']['uploaded'] / $interval);

					$time_remaining = ceil(($all - $result['progress']['uploaded']) / $speed);
					$time_remaining = (str_pad(floor($time_remaining / 3600), 2, '0', STR_PAD_LEFT) . ':' . str_pad(floor($time_remaining % 3600 / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad(($time_remaining % 60), 2, '0', STR_PAD_LEFT));

					$result['progress']['message'] = 'Uploaded ' . $item['file'] . '<br />Speed: ' . $this->sizeToString($speed) . '/s<br />Time remaining: ' . $time_remaining . '';
				}
			} else {
				$result['progress']['message'] = 'Upload finished!';
				$result['finished_upload'] = true;
			}
		}

		return $result;
	}
	
	private function amazon_init_db() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "nitro_amazon_files` ( `id` int(10) unsigned NOT NULL AUTO_INCREMENT, `file` text NOT NULL, `realpath` text NOT NULL, `size` int(10) unsigned NOT NULL, `uploaded` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`), KEY `file` (`file`(20),`uploaded`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		$this->db->query("TRUNCATE TABLE " . DB_PREFIX . "nitro_amazon_files");
	}

	private function amazon_upload_file(&$s3, $source, $destination, $config, $buckets) {
		$compress_data = $config['Nitro']['Compress'];
		$browser_cache_data = $config['Nitro']['BrowserCache'];

		$cssjs_browser_cache = 0;
		$images_browser_cache = 0;

		if (
			!empty($browser_cache_data['Enabled']) && 
			$browser_cache_data['Enabled'] == 'yes' &&
			!empty($browser_cache_data['Headers']['Pages']['Expires'])
		) {
			if (!empty($browser_cache_data['CSSJS']['Period'])) {
				switch($browser_cache_data['CSSJS']['Period']) {
					case '1 week' : {
						$cssjs_browser_cache = 7 * 24 * 3600;
					} break;
					case '1 month' : {
						$cssjs_browser_cache = 30 * 24 * 3600;
					} break;
					case '6 months' : {
						$cssjs_browser_cache = 6 * 30 * 24 * 3600;
					} break;
					case '1 year' : {
						$cssjs_browser_cache = 365 * 24 * 3600;
					} break;
					default : {
						$cssjs_browser_cache = 0;
					}
				}
			}

			if (!empty($browser_cache_data['Images']['Period'])) {
				switch($browser_cache_data['Images']['Period']) {
					case '1 week' : {
						$images_browser_cache = 7 * 24 * 3600;
					} break;
					case '1 month' : {
						$images_browser_cache = 30 * 24 * 3600;
					} break;
					case '6 months' : {
						$images_browser_cache = 6 * 30 * 24 * 3600;
					} break;
					case '1 year' : {
						$images_browser_cache = 365 * 24 * 3600;
					} break;
					default : {
						$images_browser_cache = 0;
					}
				}
			}
		}

		$this->loadConfig();
		$this->loadCore();

		$ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));

		if (!empty($config['Nitro']['CDNAmazon']['SyncCSS']) && in_array($ext, unserialize(NITRO_EXTENSIONS_CSS))) {
			if (is_array($buckets) && in_array($config['Nitro']['CDNAmazon']['CSSBucket'], $buckets)) {
				$bucket = $config['Nitro']['CDNAmazon']['CSSBucket'];
				$compress = !empty($compress_data['CSS']) && $compress_data['CSS'] == 'yes' && !empty($compress_data['CSSLevel']) ? (int)$compress_data['CSSLevel'] : false;
				$cache_time = $cssjs_browser_cache;
			} else throw new Exception('The CSS bucket does not exist. Please create it.');
		}

		if (!empty($config['Nitro']['CDNAmazon']['SyncJavaScript']) && in_array($ext, unserialize(NITRO_EXTENSIONS_JS))) {
			if (is_array($buckets) && in_array($config['Nitro']['CDNAmazon']['JavaScriptBucket'], $buckets)) {
				$bucket = $config['Nitro']['CDNAmazon']['JavaScriptBucket'];
				$compress = !empty($compress_data['JS']) && $compress_data['JS'] == 'yes' && !empty($compress_data['JSLevel']) ? (int)$compress_data['JSLevel'] : false;
				$cache_time = $cssjs_browser_cache;
			} else throw new Exception('The JS bucket does not exist. Please create it.');
		}

		if (!empty($config['Nitro']['CDNAmazon']['SyncImages']) && in_array($ext, unserialize(NITRO_EXTENSIONS_IMG))) {
			if (is_array($buckets) && in_array($config['Nitro']['CDNAmazon']['ImageBucket'], $buckets)) {
				$bucket = $config['Nitro']['CDNAmazon']['ImageBucket'];
				$compress = false;
				$cache_time = $images_browser_cache;
			} else throw new Exception('The images bucket does not exist. Please create it.');
		}

		$req = new S3Request('HEAD', $bucket, $destination);
		$res = $req->getResponse();

		$to_upload = 
			$res->code != 200 || // Either the file does not exist
			(
				$res->code == 200 && // Or the file exists and meets one of the following criteria:
				(
					( // Option A) The compression settings differ between NitroPack and the file
						!empty($compress) xor
						!empty($res->headers['x-amz-meta-compressed'])
					) ||
					( // Option B) The expires header settings differ between NitroPack and the file
						!empty($cache_time) xor
						!empty($res->headers['x-amz-meta-expires-time'])
					) ||
					( // Option C) The expires header is set, but the expiration values are different
						!empty($cache_time) &&
						!empty($res->headers['x-amz-meta-expires-time']) &&
						(int)$cache_time != $res->headers['x-amz-meta-expires-time']
					)
				)
			);

		if ($to_upload) {
			$headers = array();
			$meta_headers = array();

			if (!empty($cache_time)) {
				$headers['Expires'] = gmdate('D, d M Y H:i:s \G\M\T', time() + $cache_time);
				$meta_headers['expires-time'] = $cache_time;
			}

			switch($ext) {
				case 'css' : {
					$headers['Content-Type'] = 'text/css';
				} break;
				case 'js' : {
					$headers['Content-Type'] = 'text/javascript';
				} break;
			}

			if (!empty($compress) && is_readable($source) && !empty($headers['Content-Type'])) {
				$headers['Content-Encoding'] = 'gzip';
				$meta_headers['compressed'] = '1';

				$contents = gzencode(file_get_contents($source), $compress);

				$temp_file = tempnam(sys_get_temp_dir(), 'Nitro');
				$temp_handle = fopen($temp_file, "w");

				if (empty($temp_handle) || !fwrite($temp_handle, $contents)) throw new Exception('There was a problem writing to ' . $temp_file);

				fclose($temp_handle);

				$source = $temp_file;
			}

			if ($s3->putObject($s3->inputFile($source), $bucket, $destination, S3::ACL_PUBLIC_READ, $meta_headers, $headers)) {
				if (!empty($temp_file)) {
					unlink($temp_file);
					unset($temp_file);
				}
			} else {
				throw new Exception('Could not upload ' . $destination);
			}
		}
	}

	public function rackspace_upload() {
		$this->loadCDN();
		$persistence = $this->getPersistence();
		
		$data = $persistence['Nitro']['CDNRackspace'];
		
		$this->rackspace_set_progress('Initializing connection...', 0, 0, true);
		
		require_once(DIR_SYSTEM . 'nitro/lib/rackspace/php-opencloud.php');
		
		if (phpversion() >= '5.3.0') {
			require_once(DIR_SYSTEM . 'nitro/rackspace_init.php');
		} else {
			return false;
		}
		
		$buckets = $objstore->ContainerList();
		$b = array();
		
		while($con = $buckets->Next()) {
			$b[] = $con->Name();
		}
		
		if (!empty($data['SyncImages']) && !in_array($data['ImagesContainer'], $b)) throw new Exception('The Image container &quot;' . $data['ImagesContainer'] . '&quot; does not exist. Please create it.');
		if (!empty($data['SyncCSS']) && !in_array($data['CSSContainer'], $b)) throw new Exception('The CSS container &quot;' . $data['CSSContainer'] . '&quot; does not exist. Please create it.');
		if (!empty($data['SyncJavaScript']) && !in_array($data['JavaScriptContainer'], $b)) throw new Exception('The JavaScript container &quot;' . $data['JavaScriptContainer'] . '&quot; does not exist. Please create it.');
		
		$this->loadConfig();
		$this->loadCore();
		
		// The connection is successful. We can now start to upload :)
		// clearRackspacePersistence();
		
		$this->rackspace_set_progress('Scanning files...');
		
		$files = array();
		$site_root = dirname(DIR_SYSTEM) . '/';
		
		if (!empty($data['SyncCSS'])) {
			$files = array_merge($files, $this->list_files_with_ext($site_root, unserialize(NITRO_EXTENSIONS_CSS)));
		}
		
		if (!empty($data['SyncJavaScript'])) {
			$files = array_merge($files, $this->list_files_with_ext($site_root, unserialize(NITRO_EXTENSIONS_JS)));
		}
		
		if (!empty($data['SyncImages'])) {
			$files = array_merge($files, $this->list_files_with_ext($site_root, unserialize(NITRO_EXTENSIONS_IMG)));
		}
		
		$all_size = 0;
		$admin_folder_parts = array_filter(explode('/', DIR_APPLICATION));
		$admin_folder = array_pop($admin_folder_parts) . '/';
		$site_root = dirname(DIR_SYSTEM) . '/';
		
		clearstatcache(true);
		foreach ($files as $i => $file) {
			$destination = substr($file, strlen($site_root));
			// If in admin folder, omit
			if (stripos($destination, $admin_folder) === 0) {
				unset($files[$i]);
				continue;
			}
			if (file_exists($file) && is_file($file)) {
				$all_size += filesize($file);
			} else {
				unset($files[$i]);
			}
		}
		
		$this->rackspace_set_progress('Starting upload...', 0, $all_size);
		
		$this->rackspace_upload_files($objstore, $data, $files);
		
		$this->rackspace_set_progress('Task finished!', 'success');
		
		if ($this->session_closed) {
			session_start();
			$this->session_closed = false;
		}
			
		
	}
	
	private function rackspace_upload_files(&$ostore, &$data, &$files) {
		$this->loadConfig();
		$this->loadCore();
		
		$site_root = dirname(DIR_SYSTEM) . '/';
		//if (!function_exists('exec')) throw new Exception('Your server does not support the exec() function.');
		
		$containers = array(
			'js' => NULL,
			'image' => NULL,
			'css' => NULL
		);
		
		$mimeTypes = $this->generateUpToDateMimeArray('http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types');
		
		// Copy files
		foreach ($files as $file) {
			
			if ($this->session_closed) {
				session_start();
				$this->session_closed = false;
			}
			if (!empty($_SESSION['nitro_rackspace_cancel'])) {
				unset($_SESSION['nitro_rackspace_cancel']);
				session_write_close();
				$this->session_closed = true;
				break;
			}
			
			$source = $file;
			$destination = substr($file, strlen($site_root));
			
			$source_info = pathinfo($source);
			$container = '';
			if ($source_info['extension'] == 'js') {
				$container = $data['JavaScriptContainer'];
				if (empty($containers['js'])) $containers['js'] = $ostore->Container($container);
				$container = $containers['js'];
			} else if ($source_info['extension'] == 'css') {
				$container = $data['CSSContainer'];
				if (empty($containers['css'])) $containers['css'] = $ostore->Container($container);
				$container = $containers['css'];
			} else {
				$container = $data['ImagesContainer'];
				if (empty($containers['image'])) $containers['image'] = $ostore->Container($container);
				$container = $containers['image'];
			}
			
			$obj = $container->DataObject();
			$response = $obj->Create(array('name' => $destination, 'content_type' => $mimeTypes[$source_info['extension']]), $source);
			
			if ($response->errno() == 0) {
				$this->rackspace_set_progress('Uploaded ' . $destination, filesize($source));
				setRackspacePersistence($destination);
			} else {
				throw new Exception('Could not upload ' . $destination);
			}
		}
	}
	
	private function rackspace_set_progress($message, $uploaded = 0, $all = 0, $init = false) {
		if ($this->session_closed) {
			session_start();
			$this->session_closed = false;
		}
		if (!$this->session_closed) {
			if ($init) unset($_SESSION['nitro_rackspace_progress']);
			
			if ($all) {
				$_SESSION['nitro_rackspace_progress']['all_size'] = $all;
				$_SESSION['nitro_rackspace_progress']['uploaded_size'] = 0;
				$_SESSION['nitro_rackspace_progress']['percent'] = 0;
				$_SESSION['nitro_rackspace_progress']['init_time'] = time();
			}
			
			unset($_SESSION['nitro_rackspace_progress']['message']);
			$_SESSION['nitro_rackspace_progress']['message'] = $message;
			
			if (!empty($_SESSION['nitro_rackspace_progress']['all_size']) && !empty($uploaded)) {
				$_SESSION['nitro_rackspace_progress']['uploaded_size'] += $uploaded;
				$_SESSION['nitro_rackspace_progress']['percent'] = ceil(100*$_SESSION['nitro_rackspace_progress']['uploaded_size']/$_SESSION['nitro_rackspace_progress']['all_size']);
				$delta = (time() - $_SESSION['nitro_rackspace_progress']['init_time']);
				$_SESSION['nitro_rackspace_progress']['speed'] = $delta ? $_SESSION['nitro_rackspace_progress']['uploaded_size']/(time() - $_SESSION['nitro_rackspace_progress']['init_time']) : $_SESSION['nitro_rackspace_progress']['uploaded_size'];
				$_SESSION['nitro_rackspace_progress']['message'] .= '<br />Progress: ' . $_SESSION['nitro_rackspace_progress']['percent'] . '%';
				$_SESSION['nitro_rackspace_progress']['message'] .= '<br />Speed: ' . $this->sizeToString($_SESSION['nitro_rackspace_progress']['speed']) . '/s';
				$time = ceil($_SESSION['nitro_rackspace_progress']['all_size'] - $_SESSION['nitro_rackspace_progress']['uploaded_size'])/$_SESSION['nitro_rackspace_progress']['speed'];
				
				$_SESSION['nitro_rackspace_progress']['message'] .= '<br />Time remaining: ' . (str_pad(floor($time / 3600), 2, '0', STR_PAD_LEFT) . ':' . str_pad(floor($time % 3600 / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad(($time % 60), 2, '0', STR_PAD_LEFT));
				
			}
			
			session_write_close();
			$this->session_closed = true;
		}
	}
	
	private function generateUpToDateMimeArray($url){ //FUNCTION FROM Josh Sean @ http://www.php.net/manual/en/function.mime-content-type.php
		$s=array('gz' => 'application/x-gzip');
		foreach(@explode("\n",@file_get_contents($url))as $x)
			if(isset($x[0])&&$x[0]!=='#'&&preg_match_all('#([^\s]+)#',$x,$out)&&isset($out[1])&&($c=count($out[1]))>1)
				for($i=1;$i<$c;$i++)
					$s[$out[1][$i]]=$out[1][0];
		return ($s)?$s:false;
	}
	
	public function getServerInfo($permission) {
		$text_no_permission = '<div class="info-error">You do not have permissions to view this.</div>';
		$result = array();
		
		/* PHP VERSION */
		if (!$permission) $result['php_version'] = $text_no_permission;
		else {
			$result['php_version'] = PHP_VERSION;
		}
		
		/* PHP User */
		$nitro_folder = defined('NITRO_FOLDER') ? NITRO_FOLDER : (DIR_SYSTEM.'nitro'.DIRECTORY_SEPARATOR);
		$php_user = 'Cannot be determined';
		if (is_writable($nitro_folder)) {
			touch($nitro_folder.'test_user');
			if (file_exists($nitro_folder.'test_user')) {
				$user_info = @posix_getpwuid(fileowner($nitro_folder.'test_user'));
				if (!empty($user_info)) {
					$php_user = $user_info['name'];
				}
				unlink($nitro_folder.'test_user');
			}
		}
		$result['php_user'] = $php_user;
		
		/* WEB SERVER */
		if (!$permission) $result['web_server'] = $text_no_permission;
		else {
			if (ini_get('allow_url_fopen') == 1 || strtolower(ini_get('allow_url_fopen')) == 'off') {
				if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
					$url = HTTP_CATALOG;
				} else {
					$url = HTTPS_CATALOG;
				}
				
				$status_results = file_get_contents('http://tools.seobook.com/server-header-checker/?page=bulk&url=' . $url . '&useragent=1&typeProtocol=11');
				preg_match('~\<strong\>SERVER RESPONSE\<\/strong\>[^h]*(.*?)\<\/p\>~i', $status_results, $matches);
				$status = array_pop($matches);
				if (stripos($status, 'HTTP/1.1 2') !== FALSE) $type = 'success';
				else if (stripos($status, 'HTTP/1.1 3') !== FALSE) $type = 'warning';
				else $type = 'error';
				
				$status = '<span class="info-' . $type . '"><strong>' . $status . '</strong></span>';
				
			} else $status = '<span class="info-warning"><strong>Unknown (allow_url_fopen is Off)</strong></span>';
			$result['web_server'] = 'OS: ' . PHP_OS . ' | SAPI: ' . PHP_SAPI . ' | Status: ' . $status;
		}
		
		/* FTP FUNCTIONS */
		if (!$permission) $result['ftp_functions'] = $text_no_permission;
		else {
			$ftp = array();
			
			if (function_exists('ftp_ssl_connect')) {
				$ftp[] = 'ftp_ssl_connect()';
			}
			if (function_exists('ftp_connect')) {
				$ftp[] = 'ftp_connect()';
			}
			
			$result['ftp_functions'] = empty($ftp) ? 'No FTP functions available.' : implode(', ', $ftp);
		}
		
		/* OpenSSL */
		if (!$permission) $result['openssl'] = $text_no_permission;
		else {
			$result['openssl'] = function_exists('openssl_open') ? '<span class="info-success"><strong>YES</strong></span>' : '<span class="info-error"><strong>NO</strong></span>';
		}
		
		/* CURL */
		if (!$permission) $result['curl'] = $text_no_permission;
		else {
			if (function_exists('curl_init')) {
				$info = curl_version();
				$curl = '<span class="info-success"><strong>YES</strong></span> | Version: ' . $info['version'] . ' | Protocols: ' . implode(', ', $info['protocols']);
			} else {
				$curl = '<span class="info-error"><strong>NO</strong></span>';
			}
			
			$result['curl'] = $curl;
		}
		
		/* MemCache */
		if (!$permission) $result['memcache'] = $text_no_permission;
		else {
			$result['memcache'] = class_exists('Memcache') ? '<span class="info-success"><strong>YES</strong></span>' : '<span class="info-error"><strong>NO</strong></span>';
		}
		
		/* exec() */
		if (!$permission) $result['exec'] = $text_no_permission;
		else {
			$exec_enabled = $this->exec_enabled();
			
			$result['exec'] = $exec_enabled ? '<span class="info-success"><strong>YES</strong></span>' : '<span class="info-error"><strong>NO</strong></span>';
		}
		
		/* zlib */
		if (!$permission) $result['zlib'] = $text_no_permission;
		else {
			$result['zlib'] = function_exists('gzencode') ? '<span class="info-success"><strong>YES</strong></span>' : '<span class="info-error"><strong>NO</strong></span>';
		}
		
		/* safe mode */
		if (!$permission) $result['safe_mode'] = $text_no_permission;
		else {
			$safe_mode = (strtolower(ini_get('safe_mode')) != 'off' && ini_get('safe_mode') != 0);
			
			$result['safe_mode'] = $safe_mode ? '<span><strong>Enabled</strong></span>' : '<span><strong>Disabled</strong></span>';
		}
		
		if (function_exists('apache_get_modules')) {
			$modules = strtolower(implode('|', apache_get_modules()));
		} else {
			$shell_exec_enabled =
				 function_exists('shell_exec') &&
				 !in_array('shell_exec', array_map('trim',explode(', ', ini_get('disable_functions')))) &&
						  !(strtolower(ini_get('safe_mode')) != 'off' && ini_get('safe_mode') != 0);
						  
		    if ($shell_exec_enabled) {
				$modules = strtolower(shell_exec('/usr/local/apache/bin/apachectl -l'));
			} else {
				$modules = false;
			}
		}
		
		/* mod_deflate */
		if (!$permission) $result['mod_deflate'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_deflate') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_deflate'] = $mod_result;
		}
		
		/* mod_env */
		if (!$permission) $result['mod_env'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_env') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_env'] = $mod_result;
		}
		
		/* mod_expires */
		if (!$permission) $result['mod_expires'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_expires') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_expires'] = $mod_result;
		}
		
		/* mod_headers */
		if (!$permission) $result['mod_headers'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_headers') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_headers'] = $mod_result;
		}
		
		/* mod_mime */
		if (!$permission) $result['mod_mime'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_mime') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_mime'] = $mod_result;
		}
		
		/* mod_rewrite */
		if (!$permission) $result['mod_rewrite'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_rewrite') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_rewrite'] = $mod_result;
		}
		
		/* mod_setenvif */
		if (!$permission) $result['mod_setenvif'] = $text_no_permission;
		else {
			if ($modules === false) $mod_result = '<span class="info-warning"><strong>UNKNOWN</strong></span>';
			else if (stripos($modules, 'mod_setenvif') !== false) $mod_result = '<span class="info-success"><strong>YES</strong></span>';
			else $mod_result = '<span class="info-error"><strong>NO</strong></span>';
			$result['mod_setenvif'] = $mod_result;
		}
		
		/* path_system_nitro_cache */
		if (!$permission) $result['path_system_nitro_cache'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/cache') && is_writable(DIR_SYSTEM . 'nitro/cache')) $result['path_system_nitro_cache'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/cache')) $result['path_system_nitro_cache'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_cache'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_assets */
		if (!$permission) $result['path_assets'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . '../assets') && is_writable(DIR_SYSTEM . 'nitro/cache')) $result['path_assets'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . '../assets')) $result['path_assets'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_assets'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_system_nitro_data */
		if (!$permission) $result['path_system_nitro_data'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/data') && is_writable(DIR_SYSTEM . 'nitro/data')) $result['path_system_nitro_data'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/data')) $result['path_system_nitro_data'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_data'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_system_nitro_data_googlepagespeed-desktop */
		if (!$permission) $result['path_system_nitro_data_googlepagespeed-desktop'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/data/googlepagespeed-desktop.tpl') && is_writable(DIR_SYSTEM . 'nitro/data/googlepagespeed-desktop.tpl')) $result['path_system_nitro_data_googlepagespeed-desktop'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/data/googlepagespeed-desktop.tpl')) $result['path_system_nitro_data_googlepagespeed-desktop'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_data_googlepagespeed-desktop'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_system_nitro_data_googlepagespeed-mobile */
		if (!$permission) $result['path_system_nitro_data_googlepagespeed-mobile'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/data/googlepagespeed-mobile.tpl') && is_writable(DIR_SYSTEM . 'nitro/data/googlepagespeed-mobile.tpl')) $result['path_system_nitro_data_googlepagespeed-mobile'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/data/googlepagespeed-mobile.tpl')) $result['path_system_nitro_data_googlepagespeed-mobile'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_data_googlepagespeed-mobile'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_system_nitro_data_persistence */
		if (!$permission) $result['path_system_nitro_data_persistence'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/data/persistence.tpl') && is_writable(DIR_SYSTEM . 'nitro/data/persistence.tpl')) $result['path_system_nitro_data_persistence'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/data/persistence.tpl')) $result['path_system_nitro_data_persistence'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_data_persistence'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_system_nitro_data_amazon_persistence */
		if (!$permission) $result['path_system_nitro_data_amazon_persistence'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/data/amazon_persistence.tpl') && is_writable(DIR_SYSTEM . 'nitro/data/amazon_persistence.tpl')) $result['path_system_nitro_data_amazon_persistence'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/data/amazon_persistence.tpl')) $result['path_system_nitro_data_amazon_persistence'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_data_amazon_persistence'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		/* path_system_nitro_data_ftp_persistence */
		if (!$permission) $result['path_system_nitro_data_ftp_persistence'] = $text_no_permission;
		else {
			if (file_exists(DIR_SYSTEM . 'nitro/data/ftp_persistence.tpl') && is_writable(DIR_SYSTEM . 'nitro/data/ftp_persistence.tpl')) $result['path_system_nitro_data_ftp_persistence'] = '<span class="info-success"><strong>Writable</strong></span>';
			else if (!file_exists(DIR_SYSTEM . 'nitro/data/ftp_persistence.tpl')) $result['path_system_nitro_data_ftp_persistence'] = '<span><strong>Does not exist.</strong></span>';
			else $result['path_system_nitro_data_ftp_persistence'] = '<span class="info-error"><strong>Not writable.</strong></span>';
		}
		
		return $result;
	}
	
	private function setSmushProgress($smushedNumber, $kb_saved = 0, $smush_timestamp = false, $file = false, $percent = false, $already_smushed_number = 0) {
		$this->openSession();
		$_SESSION['smush_progress']['smushed_images_count'] = $smushedNumber;
		
		if (!empty($kb_saved)) $_SESSION['smush_progress']['kb_saved'] += (float)number_format($kb_saved, 2);
		if (!empty($smush_timestamp)) $_SESSION['smush_progress']['last_smush_timestamp'] = $smush_timestamp;
		if (!empty($already_smushed_number)) $_SESSION['smush_progress']['already_smushed_images_count'] = $already_smushed_number;
		if (!empty($file)) {
			if (empty($_SESSION['smush_progress']['smushed_files'])) {
				$_SESSION['smush_progress']['smushed_files'] = array();
			}
			
			$_SESSION['smush_progress']['smushed_files'][] = array(
				'filename' => $file,
				'percent' => (!empty($percent) ? $percent : 0)
			);
		}
		$this->setSmushitPersistence($_SESSION['smush_progress']);
	}
	
	private function setSmushProgressMessage($msg) {
		$this->openSession();
		$_SESSION['smush_progress']['messages'][] = $msg;
	}
	
	private function setHtaccessFileContent($newcontent) {
		$htaccessFile = DIR_SYSTEM.'../.htaccess';
		$htaccessFileBackup = DIR_SYSTEM.'../.htaccess-backup';
		if (!is_writable($htaccessFile)) {
			if (function_exists('chmod')) {
				if ($this->isCurrentUserFileOwner($htaccessFile)) {
					chmod($htaccessFile,0644);
				} else {
					$this->session->data['error'] = 'Your PHP user does not have write permission to the .htaccess file. Please set it or contact your hosting provider.';	
					return false;
				}
			}
		}
		if (!file_exists($htaccessFile)) {
			file_put_contents($htaccessFile,'');
		}
		if (!file_exists($htaccessFileBackup)) {
			if (!copy($htaccessFile,$htaccessFileBackup)) {
				$this->session->data['error'] = 'Your PHP user does not have permission to create the .htaccess-backup file. Please create it manually and for content set the current content of the .htaccess file.';	
				return false;
			}
		}

		if (is_writable($htaccessFile)) {
			file_put_contents($htaccessFile,$newcontent);
		}
		
		return true;
	}
	
	private function isCurrentUserFileOwner($filename) {
		$nitro_temp = DIR_SYSTEM.'nitro'.DIRECTORY_SEPARATOR.'temp';
		if (file_exists($nitro_temp) && is_writable($nitro_temp)) {
			$test_file = $nitro_temp . DIRECTORY_SEPARATOR . 'nitro_usercheck';
			if (@touch($test_file)) {
				$currentUserInfo = posix_getpwuid(fileowner($test_file));
				$htaccessUserInfo = posix_getpwuid(fileowner($filename));
				return ($currentUserInfo['name'] == $htaccessUserInfo['name']);
			}
		}
		return false;
	}
	
	private function getHtaccessFileContent() {
		$htaccessFile = DIR_SYSTEM.'../.htaccess';
		if (!file_exists($htaccessFile)) {
			file_put_contents($htaccessFile,'');
		}
		
		return file_get_contents($htaccessFile);
	}
	
	private function extractNitrocodeFromHtaccessFile($htaccessContent) {
		$nitrocode = $this->getStringBetween('# STARTNITRO'.PHP_EOL,'# ENDNITRO'.PHP_EOL,$htaccessContent);
		if (strpos($nitrocode,'STARTNITRO') == false) {
			return '';
		}
		return $nitrocode;
	}
	
	private function extractNitrocodeCompressFromHtaccessFile($htaccessContent) {
		$nitrocode = $this->getStringBetween('# STARTCOMPRESSNITRO'.PHP_EOL,'# ENDCOMPRESSNITRO'.PHP_EOL,$htaccessContent);
		if (strpos($nitrocode,'STARTCOMPRESSNITRO') == false) {
			return '';
		}
		return $nitrocode;
	}
	
	private function getStringBetween($var1="",$var2="",$pool){
		$temp1 = strpos($pool,$var1);
		$result = substr($pool,$temp1,strlen($pool));
		$dd=strpos($result,$var2);
		if($dd == 0){
			$dd = strlen($result);
		}
		return substr($result,0,$dd+strlen($var2));
	}
	
	private function delete_folder($folder) {
		if (in_array($folder, array('.', '..'))) return;
		
		if (file_exists($folder)) {
			if (is_writeable($folder)) {
				if (is_dir($folder)) {
					$folder = rtrim($folder, DS);
					
					$files = scandir($folder);
					foreach ($files as $file) {
						if (in_array($file, array('.', '..'))) continue;
						$this->delete_folder($folder . DS . $file);
					}
					
					if (!rmdir($folder)) throw new Exception('Delete not successful. The path ' . $folder . ' could not get deleted.');
				} else {
					if (!unlink($folder)) throw new Exception('Delete not successful. The path ' . $folder . ' could not get deleted.');
				}
			} else throw new Exception('Delete not successful. The path ' . $folder . ' is not writable.');
		}
	}
	
	private function trunc_folder($folder) {
		if (in_array($folder, array('.', '..'))) return;
		
		if (file_exists($folder)) {
			if (is_writeable($folder)) {
				if (is_dir($folder)) {
					$folder = rtrim($folder, DS);
					
					$files = scandir($folder);
					foreach ($files as $file) {
						if (in_array($file, array('.', '..'))) continue;
						$this->trunc_folder($folder . DS . $file);
					}
				} else {
					if (!unlink($folder)) throw new Exception('Delete not successful. The path ' . $folder . ' could not get deleted.');
				}
			} else throw new Exception('Delete not successful. The path ' . $folder . ' is not writable.');
		}
	}
	
	private function list_files_with_ext($site_root, $ext, $batch = false) {
		$output = array();
		
		if ($this->exec_enabled()) {
			if (!is_array($ext)) {
				exec('find ' . $site_root . ' -type f -name "*.' . $ext . '"', $output);
			} else {
				$output = array();
				foreach ($ext as $ex) {
					exec('find ' . $site_root . ' -type f -name "*.' . $ex . '"', $sub_output);
					$output = array_merge($output, $sub_output);
				}
			}
		} else {
			if (!is_array($ext)) {
				$output = $this->list_files_with_ext_rec($site_root, $ext);
			} else {
				$output = array();
				foreach ($ext as $ex) {
					$output = array_merge($output, $this->list_files_with_ext_rec($site_root, $ex));
				}
			}
		}
		
		$admin_folder_parts = array_filter(explode('/', DIR_APPLICATION));
		$admin_folder = array_pop($admin_folder_parts) . '/';

		// The images need to be served only from image/cache and the admin folder needs to be excluded
		foreach ($output as $i => $file) {
			if (stripos($file, DS . $admin_folder) !== FALSE) unset($output[$i]);

			if (is_array($ext) && serialize($ext) == NITRO_EXTENSIONS_IMG) {
				if (stripos($file, $site_root . 'image/cache') !== 0) unset($output[$i]);
			}
		}

		$output = array_values($output);

		if ($batch) {
			if ($_SESSION['nitro_amazon_count'] < $batch) {
				$pos = array_search($_SESSION['nitro_amazon_progress']['continue_from'], $output);
				$pos = empty($pos) ? 0 : (int)$pos;
				$output = array_slice($output, $pos + 1, $batch);
				//file_put_contents('./adebug.txt', print_r($output, true), FILE_APPEND);
				$_SESSION['nitro_amazon_count'] += count($output);
			} else {
				$output = array();
			}
		}

		return $output;
	}
	
	private function list_files_with_ext_rec($site_root, $ext) {
		$result = array();
		
		if (is_dir($site_root)) {
			if (substr($site_root, strlen($site_root) - 1, 1) == DS) $site_root = substr($site_root, 0, strlen($site_root) - 1);
					
			$files = scandir($site_root);
			foreach ($files as $file) {
				if (in_array($file, array('.', '..'))) continue;
				$result = array_merge($result, $this->list_files_with_ext_rec($site_root . DS . $file, $ext));
			}
		} else {
			if (strtolower(substr($site_root, strlen($site_root) - strlen($ext))) === $ext) $result[] = $site_root;
		}
		
		return $result;
	}

	private function size_files_with_ext($site_root, $ext) {
		$output = array();
		$size = 0;

		$admin_folder_parts = array_filter(explode('/', DIR_APPLICATION));
		$admin_folder = array_pop($admin_folder_parts) . '/';
		
		if ($this->exec_enabled()) {
			if (serialize($ext) == NITRO_EXTENSIONS_IMG) {
				$image_match = '.*image\/cache.*';
			} else {
				$image_match = '';
			}

			if (!is_array($ext)) {
				exec('du -c -b -a --exclude="\/' . $admin_folder . '\/" ' . $site_root . ' | grep "' . $image_match . '\.' . $ext . '$" | grep -o "^[0-9]*" | awk \'{s+=$1} END {print s}\'', $output);
			} else {
				$output = array();
				foreach ($ext as $ex) {
					exec('du -c -b -a --exclude="\/' . $admin_folder . '\/" ' . $site_root . ' | grep "' . $image_match . '\.' . $ex . '$" | grep -o "^[0-9]*" | awk \'{s+=$1} END {print s}\'', $sub_output);
					$output = array_merge($output, $sub_output);
				}
			}

			$size = !empty($output[0]) ? $output[0] : 0;
		} else {
			if (!is_array($ext)) {
				$output = $this->list_files_with_ext_rec($site_root, $ext);
			} else {
				$output = array();
				foreach ($ext as $ex) {
					$output = array_merge($output, $this->list_files_with_ext_rec($site_root, $ex));
				}
			}

			foreach ($output as $i => $file) {
				//if (stripos($file, 'image/cache') !== FALSE || stripos($file, DS . $admin_folder) !== FALSE) continue;
				$size += filesize($file);
			}
		}

		return $size;
	}
	
	private function exec_enabled() {
		return function_exists('exec') &&
			!in_array('exec', array_map('trim',explode(', ', ini_get('disable_functions')))) &&
			!(strtolower(ini_get('safe_mode')) != 'off' && ini_get('safe_mode') != 0) && strtolower(PHP_OS) == 'linux';
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
	
	private function smushCanContinue() {
		return true;
		$this->openSession();
		$stop_smushing = $_SESSION['stop_smushing'];
		$this->closeSession();
		return !$stop_smushing;
	}
}
?>