<?php
if (version_compare(VERSION,'3.0.0.0','>=' )) {
	define('TEMPLATE_FOLDER', 'oc3');
	define('EXTENSION_BASE', 'marketplace/extension');
	define('TOKEN_NAME', 'user_token');
	define('TEMPLATE_EXTN', '');
	define('EXTN_ROUTE', 'extension/hbseo');
}else if (version_compare(VERSION,'2.2.0.0','<=' )) {
	define('TEMPLATE_FOLDER', 'oc2');
	define('EXTENSION_BASE', 'extension/hbseo');
	define('TOKEN_NAME', 'token');
	define('TEMPLATE_EXTN', '.tpl');
	define('EXTN_ROUTE', 'hbseo');
}else{
	define('TEMPLATE_FOLDER', 'oc2');
	define('EXTENSION_BASE', 'extension/extension');
	define('TOKEN_NAME', 'token');
	define('TEMPLATE_EXTN', '');
	define('EXTN_ROUTE', 'extension/hbseo');
}
define('EXTN_VERSION', '5.2'); 
class ControllerHbseoHbSitemap extends Controller {
	
	private $error = array(); 
	
	public function index() {   
		$data['extension_version'] = EXTN_VERSION;
		
		if (isset($this->request->get['store_id'])){
			$data['store_id'] = (int)$this->request->get['store_id'];
		}else{
			$data['store_id'] = 0;
		}
		
		$this->load->language(EXTN_ROUTE.'/hb_sitemap');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/hbseo/hb_sitemap');
		$this->load->model('setting/setting');
		
		//Save the settings if the user has submitted the admin form (ie if someone has pressed save).
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('hb_sitemap', $this->request->post, $this->request->get['store_id']);	
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link(EXTN_ROUTE.'/hb_sitemap', TOKEN_NAME.'=' . $this->session->data[TOKEN_NAME].'&store_id='.$data['store_id'], true));
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		
		$text_strings = array(
				'heading_title','text_extension',
				'tab_dashboard','tab_setting','tab_misc','tab_tools',
				'button_save','button_cancel'
		);
		
		foreach ($text_strings as $text) {
			$data[$text] = $this->language->get($text);
		}
	
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', TOKEN_NAME.'=' . $this->session->data[TOKEN_NAME], true)
   		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link(EXTENSION_BASE, TOKEN_NAME.'=' . $this->session->data[TOKEN_NAME] . '&type=hbseo', true)
		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link(EXTN_ROUTE.'/hb_sitemap', TOKEN_NAME.'=' . $this->session->data[TOKEN_NAME].'&store_id='.$data['store_id'], true)
   		);
		
		$data['action'] = $this->url->link(EXTN_ROUTE.'/hb_sitemap', TOKEN_NAME.'=' . $this->session->data[TOKEN_NAME].'&store_id='.$data['store_id'], true);
		
		$data['cancel'] = $this->url->link(EXTENSION_BASE, TOKEN_NAME.'=' . $this->session->data[TOKEN_NAME] . '&type=hbseo', true);
		$data[TOKEN_NAME] = $this->session->data[TOKEN_NAME];
		$data['base_route'] = EXTN_ROUTE;
		
		$store_info = $this->model_setting_setting->getSetting('hb_sitemap', $this->request->get['store_id']);

		$search = 'extension/hbseo/sitemap';
		$file = '../.htaccess';
		
		$data['htaccess_enabled'] = false;
		if (file_exists($file)) {
			$lines = file($file);
			foreach($lines as $line) {
			  if(strpos($line, $search) !== false) {
				$data['htaccess_enabled'] = true;
			  }
			}
		}else{
			$data['htaccess_enabled'] = false;
		}
		
		$data['htaccess_code'] = $this->htaccesscode();
		
		//dashboard
		$this->load->model('setting/store');
		
		$data['stores'] = $this->model_setting_store->getStores();

		if ($data['store_id'] == 0){ 
			$data['store_url'] = HTTPS_CATALOG;
		}else{
			$results = $this->model_setting_store->getStore($data['store_id']);
			$data['store_url'] = $results['url'];
		}
		
		$data['google_index_link'] = 'https://www.google.com/search?q=site%3A'.urlencode($data['store_url']);
				
		if ($data['htaccess_enabled'] ==  true) {
			$data['sitemap_index_link'] = $data['store_url']."sitemap_index.xml";
		}else{
			$data['sitemap_index_link'] = $data['store_url']."index.php?route=extension/hbseo/sitemap/index";
		}
		$data['sitemaps'] = array();
		$data['sitemaps_loc'] = array();

		$xmldata = $this->getlinksfromsitemap($data['sitemap_index_link']);

		if ($xmldata) {
			foreach ($xmldata as $xml) {
				$data['sitemaps'][] = array(
					'loc'	=> $xml->loc,
					'last_modified' => $xml->lastmod
				);
				
				$data['sitemaps_loc'][] = $xml->loc;
			}
		}else{
			$data['sitemaps'] = false;
		}
		
		$data['ping_google_link'] = 'https://www.google.com/webmasters/sitemaps/ping?sitemap='.$data['sitemap_index_link'];
		$data['ping_bing_link'] = 'https://www.bing.com/webmaster/ping.aspx?siteMap='.$data['sitemap_index_link'];

		//settings
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		foreach ($data['languages'] as $language){
	 		$language_id = $language['language_id'];	
			$data['hb_sitemap_caption'][$language_id] =  isset($store_info['hb_sitemap_caption'.$language_id])?$store_info['hb_sitemap_caption'.$language_id]:'{p} Main Image';
			$data['hb_sitemap_title'][$language_id] =  isset($store_info['hb_sitemap_title'.$language_id])?$store_info['hb_sitemap_title'.$language_id]:'{p} Image';
			$data['hb_sitemap_a_caption'][$language_id] =  isset($store_info['hb_sitemap_a_caption'.$language_id])?$store_info['hb_sitemap_a_caption'.$language_id]:'Showing Additional Image for {p}';
			$data['hb_sitemap_a_title'][$language_id] =  isset($store_info['hb_sitemap_a_title'.$language_id])?$store_info['hb_sitemap_a_title'.$language_id]:'More {p} Images';
		}
	
		$data['hb_sitemap_enable'] = isset($store_info['hb_sitemap_enable'])?$store_info['hb_sitemap_enable']:'';
		$data['hb_sitemap_beautify'] = isset($store_info['hb_sitemap_beautify'])?$store_info['hb_sitemap_beautify']:'';
		$data['hb_sitemap_product'] = isset($store_info['hb_sitemap_product'])?$store_info['hb_sitemap_product']:'';
		$data['hb_sitemap_producttags'] = isset($store_info['hb_sitemap_producttags'])?$store_info['hb_sitemap_producttags']:'';
		$data['hb_sitemap_category'] = isset($store_info['hb_sitemap_category'])?$store_info['hb_sitemap_category']:'';
		$data['hb_sitemap_brand'] = isset($store_info['hb_sitemap_brand'])?$store_info['hb_sitemap_brand']:'';
		$data['hb_sitemap_info'] = isset($store_info['hb_sitemap_info'])?$store_info['hb_sitemap_info']:'';
		$data['hb_sitemap_ctopr'] = isset($store_info['hb_sitemap_ctopr'])?$store_info['hb_sitemap_ctopr']:'';
		$data['hb_sitemap_btopr'] = isset($store_info['hb_sitemap_btopr'])?$store_info['hb_sitemap_btopr']:'';
		$data['hb_sitemap_misc'] = isset($store_info['hb_sitemap_misc'])?$store_info['hb_sitemap_misc']:'';
		$data['hb_sitemap_others'] = isset($store_info['hb_sitemap_others'])?$store_info['hb_sitemap_others']:'';
		
		$data['hb_sitemap_limit'] = isset($store_info['hb_sitemap_limit'])?$store_info['hb_sitemap_limit']:'3000';
		$data['hb_sitemap_width'] = isset($store_info['hb_sitemap_width'])?$store_info['hb_sitemap_width']:'500';
		$data['hb_sitemap_height'] = isset($store_info['hb_sitemap_height'])?$store_info['hb_sitemap_height']:'500';
		
		//TOOLS
		$product_invalid_date = $this->model_extension_hbseo_hb_sitemap->checkinvaliddate('product');
		$category_invalid_date = $this->model_extension_hbseo_hb_sitemap->checkinvaliddate('category');

		if ($product_invalid_date > 0) {
			$data['invalid_date'] = $product_invalid_date.' product(s) has incorrect last modified date. Click on the below button to fix this.';
		}else if ($category_invalid_date > 0) {
			$data['invalid_date'] = $category_invalid_date.' category(s) has incorrect last modified date. Click on the below button to fix this.';
		}else{
			$data['invalid_date'] = 'Date Modified looks fine for product and category tables in database. No action required.';
		}
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/hbseo/'.TEMPLATE_FOLDER.'/hb_sitemap'.TEMPLATE_EXTN, $data));
	}
	
	public function customlinks() {  
		$store_id = (int)$this->request->get['store_id'];
		$this->load->model('extension/hbseo/hb_sitemap');
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data = array(
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin'),
			'store_id'=> $store_id
		);

		$data['token'] = $this->session->data[TOKEN_NAME];	
		
		$reports_total = $this->model_extension_hbseo_hb_sitemap->getTotalrecords($data); 		
		$records = $this->model_extension_hbseo_hb_sitemap->getrecords($data);
		$data['records'] = array();
		foreach ($records as $record) {
			$data['records'][] = array(
				'id' 			=> $record['id'],
				'link' 			=> urldecode($record['link']),
				'freq' 			=> $record['freq'],
				'priority' 		=> $record['priority'],
				'date_added'	=> date($this->language->get('date_format_short'), strtotime($record['date_added'])),
				'selected'  	=> isset($this->request->post['selected']) && in_array($record['id'], $this->request->post['selected'])
			);
		}
		
		$pagination = new Pagination();
		$pagination->total = $reports_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link(EXTN_ROUTE.'/hb_sitemap/customlinks', TOKEN_NAME.'=' . $this->session->data[TOKEN_NAME] . '&store_id='.$store_id.'&page={page}', true);

		$data['pagination'] = $pagination->render();
		$limit = $this->config->get('config_limit_admin');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($pagination->total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($pagination->total - $limit)) ? $pagination->total : ((($page - 1) * $limit) + $limit), $pagination->total, ceil($pagination->total / $limit));

		$this->response->setOutput($this->load->view('extension/hbseo/'.TEMPLATE_FOLDER.'/hb_sitemap_misc_links'.TEMPLATE_EXTN, $data));
	}	
	
	public function addlink(){
		$store_id = (int)$this->request->get['store_id'];
		$link = urlencode($this->request->post['link']);
		$freq = $this->request->post['freq'];
		$priority = $this->request->post['priority'];
		
		$count = $this->db->query("SELECT count(*) as count FROM  `" . DB_PREFIX . "sitemap_links` WHERE `link` = '".$link."' and store_id = '".(int)$store_id."'");
		if ($count->row['count'] == 0){
			$this->db->query("INSERT INTO `" . DB_PREFIX . "sitemap_links` (`link`,`freq`,`priority`,`store_id`) VALUES ('".$this->db->escape($link)."','".$this->db->escape($freq)."','".$this->db->escape($priority)."', '".(int)$store_id."')");
			$json['success'] = 'Link Added';
		}else{
			$json['error'] = 'Link Already Exists';
		}
		$this->response->setOutput(json_encode($json));	
	}
	
	public function deletelink(){
		$count = 0;
		if (!isset($this->request->post['selected'])){
			$json['warning'] = 'No Record Selected!';
		}else{
			foreach ($this->request->post['selected'] as $id) {
				$this->db->query("DELETE FROM `" . DB_PREFIX . "sitemap_links` WHERE `id` = '".(int)$id."'");
				$count = $count + 1;
			}
			$json['success'] = $count.' LINK(S) DELETED';
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function fixdates(){
		$this->load->model('extension/hbseo/hb_sitemap');
		$this->model_extension_hbseo_hb_sitemap->updateinvaliddate();
		$json['success'] = 'Incorrect last modified dates updated successfully';
		$this->response->setOutput(json_encode($json));
	}
	
	public function importbulk(){		
		$store_id = (int)$this->request->get['store_id'];
		$links = trim($this->request->post['links']);
		$links = str_replace(' ','',$links);
		$links = preg_replace('/\s+/','|',$links);
		$links = trim($links);
		
		if ($links == ''){
			$links = array();
		}else {
			$links = explode('|',$links);
		}
		
		if (empty($links)){
			$json['warning'] = 'No links detected. Add Links in the above field and click import!';
		}else{
			foreach ($links as $link) {
				$link = trim($link);
				$link = urlencode($link);
				$this->db->query("INSERT INTO ".DB_PREFIX."sitemap_links (link, freq, priority, store_id) VALUES ('".$this->db->escape($link)."', 'daily', '0.8', '0')");
			}
			$json['success'] = 'Links added to Custom Sitemap Page.';
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function addhtaccess(){
		$data['htaccess_code'] = $this->htaccesscode();
		$file = '../.htaccess';
		$backupfile = '../.htaccess.sitemap.BACKUP';
		
		if (!file_exists($file)){
			$json['warning'] = '.htaccess file not found in your server';
		} else {
			copy($file, $backupfile);
			
			$f = fopen($file, "r+");
			
			$oldstr = file_get_contents($file);
			
			if (version_compare(VERSION,'3.0.0.0','>=' )) {
				$specificLine = "RewriteRule ^system/storage/(.*) index.php?route=error/not_found [L]";
			}else if (version_compare(VERSION,'2.0.1.1','<=' )) {
				$specificLine = "RewriteRule ^download/(.*) /index.php?route=error/not_found [L]";
			} else{
				$specificLine = "RewriteRule ^system/download/(.*) index.php?route=error/not_found [L]";
			}
			
			$json['warning'] = 'Reference Line not found in .htaccess file';
			while (($buffer = fgets($f)) !== false) {
				if (strpos($buffer, $specificLine) !== false) {
					$pos = ftell($f); 
					$newstr = substr_replace($oldstr, $data['htaccess_code'], $pos, 0);
					file_put_contents($file, $newstr);
					$json['success'] = 'Code Added to .htaccess file';
					break;
				}
			}
			fclose($f); 
		}
		$this->response->setOutput(json_encode($json));	
	}
	
	private function htaccesscode(){
		return 'RewriteRule ^sitemap_index.xml$ index.php?route=extension/hbseo/sitemap [L,QSA] 
RewriteRule ^sitemaps/([^?]*)/product_sitemap_([0-9]+).xml$ index.php?route=extension/hbseo/sitemap/products&hbxmllang=$1&page=$2 [L,QSA] 
RewriteRule ^sitemaps/([^?]*)/product_tags_sitemap_([0-9]+).xml$ index.php?route=extension/hbseo/sitemap/product_tags&hbxmllang=$1&page=$2 [L,QSA] 
RewriteRule ^sitemaps/([^?]*)/category_sitemap.xml$ index.php?route=extension/hbseo/sitemap/category&hbxmllang=$1 [L,QSA] 
RewriteRule ^sitemaps/([^?]*)/brand_sitemap.xml$ index.php?route=extension/hbseo/sitemap/brand&hbxmllang=$1 [L,QSA] 
RewriteRule ^sitemaps/([^?]*)/information_sitemap.xml$ index.php?route=extension/hbseo/sitemap/information&hbxmllang=$1 [L,QSA] 
RewriteRule ^sitemaps/([^?]*)/category_to_product_sitemap.xml$ index.php?route=extension/hbseo/sitemap/category_to_product&hbxmllang=$1 [L,QSA] 
RewriteRule ^sitemaps/([^?]*)/brand_to_product_sitemap.xml$ index.php?route=extension/hbseo/sitemap/brand_to_product&hbxmllang=$1 [L,QSA] 
RewriteRule ^sitemaps/misc_sitemap.xml$ index.php?route=extension/hbseo/sitemap/misc [L,QSA] 
RewriteRule ^sitemaps/([^?]*)/journalblog_sitemap.xml$ index.php?route=extension/hbseo/sitemap/journalblog&hbxmllang=$1 [L,QSA] 
';
	}		
			
	public function install(){
			$this->load->model('extension/hbseo/hb_sitemap');
			$this->model_extension_hbseo_hb_sitemap->install();
	}
	
	public function uninstall(){
			$this->load->model('extension/hbseo/hb_sitemap');
			$this->model_extension_hbseo_hb_sitemap->uninstall();
	}
		
	
	private function validate() {
		if (!$this->user->hasPermission('modify', EXTN_ROUTE.'/hb_sitemap')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	
	private function getlinksfromsitemap($sitemap_index_link){
		if(ini_get('allow_url_fopen')) {
		   $xmldata = simplexml_load_file($sitemap_index_link,'SimpleXMLElement',LIBXML_NOERROR);
		} else{
			$curl = curl_init($sitemap_index_link);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$xmldata = curl_exec($curl);
			$xmldata = simplexml_load_string($xmldata);	
		}
		return $xmldata;
	}
	
	
}
?>