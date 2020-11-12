<?php 
class ControllerFeedUksbSitemaps extends Controller {
	private $error = array(); 
	
	public function index() {
		$this->load->language('feed/uksb_sitemaps');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('uksb_sitemaps', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['heading_general_settings'] = $this->language->get('heading_general_settings');
		$this->data['heading_sitemap_content'] = $this->language->get('heading_sitemap_content');
		$this->data['heading_sitemap_urls'] = $this->language->get('heading_sitemap_urls');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_always'] = $this->language->get('text_always');
		$this->data['text_hourly'] = $this->language->get('text_hourly');
		$this->data['text_daily'] = $this->language->get('text_daily');
		$this->data['text_weekly'] = $this->language->get('text_weekly');
		$this->data['text_monthly'] = $this->language->get('text_monthly');
		$this->data['text_yearly'] = $this->language->get('text_yearly');
		$this->data['text_pg_home'] = $this->language->get('text_pg_home');
		$this->data['text_pg_specials'] = $this->language->get('text_pg_specials');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_image_sitemap'] = $this->language->get('entry_image_sitemap');
		$this->data['entry_in_sitemap'] = $this->language->get('entry_in_sitemap');
		$this->data['entry_products'] = $this->language->get('entry_products');
		$this->data['entry_categories'] = $this->language->get('entry_categories');
		$this->data['entry_manufacturers'] = $this->language->get('entry_manufacturers');
		$this->data['entry_pages'] = $this->language->get('entry_pages');
		$this->data['entry_pages_omit'] = $this->language->get('entry_pages_omit');
		$this->data['entry_split'] = $this->language->get('entry_split');
		$this->data['entry_fullpath'] = $this->language->get('entry_fullpath');
		$this->data['entry_info'] = $this->language->get('entry_info');
		$this->data['entry_sitemap_content'] = $this->language->get('entry_sitemap_content');
		$this->data['entry_frequency'] = $this->language->get('entry_frequency');
		$this->data['entry_priority'] = $this->language->get('entry_priority');
		$this->data['entry_google'] = $this->language->get('entry_google');
		$this->data['entry_yahoo'] = $this->language->get('entry_yahoo');
		$this->data['entry_bing'] = $this->language->get('entry_bing');
		$this->data['entry_data_feed1'] = $this->language->get('entry_data_feed1');
		$this->data['entry_data_feed2'] = $this->language->get('entry_data_feed2');
		$this->data['help_split'] = $this->language->get('help_split');
		$this->data['help_fullpath'] = $this->language->get('help_fullpath');
		$this->data['help_content'] = $this->language->get('help_content');
		$this->data['help_urls'] = $this->language->get('help_urls');
		$this->data['help_info'] = $this->language->get('help_info');

		$this->data['warning_submit'] = $this->language->get('warning_submit');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_feed'),
		'href'      => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
		'href'      => $this->url->link('feed/uksb_sitemaps', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('feed/uksb_sitemaps', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['uksb_sitemaps_status'])) {
			$this->data['uksb_sitemaps_status'] = $this->request->post['uksb_sitemaps_status'];
		} else {
			$this->data['uksb_sitemaps_status'] = $this->config->get('uksb_sitemaps_status');
		}
		
		if (isset($this->request->post['uksb_image_sitemap'])) {
			$this->data['uksb_image_sitemap'] = $this->request->post['uksb_image_sitemap'];
		} else {
			$this->data['uksb_image_sitemap'] = $this->config->get('uksb_image_sitemap');
		}
				
		if (isset($this->request->post['uksb_sitemaps_split'])) {
			$this->data['uksb_sitemaps_split'] = $this->request->post['uksb_sitemaps_split'];
		} else {
			$this->data['uksb_sitemaps_split'] = $this->config->get('uksb_sitemaps_split');
		}
				
		if (isset($this->request->post['uksb_sitemap_fullpath'])) {
			$this->data['uksb_sitemap_fullpath'] = $this->request->post['uksb_sitemap_fullpath'];
		} else {
			$this->data['uksb_sitemap_fullpath'] = $this->config->get('uksb_sitemap_fullpath');
		}
		
		if (isset($this->request->post['uksb_sitemap_products_on'])) {
			$this->data['uksb_sitemap_products_on'] = $this->request->post['uksb_sitemap_products_on'];
		} else {
			$this->data['uksb_sitemap_products_on'] = $this->config->get('uksb_sitemap_products_on');
		}
	
		if (isset($this->request->post['uksb_sitemap_products_fr'])) {
			$this->data['uksb_sitemap_products_fr'] = $this->request->post['uksb_sitemap_products_fr'];
		} else {
			$this->data['uksb_sitemap_products_fr'] = $this->config->get('uksb_sitemap_products_fr');
		}
	
		if (isset($this->request->post['uksb_sitemap_products_pr'])) {
			$this->data['uksb_sitemap_products_pr'] = $this->request->post['uksb_sitemap_products_pr'];
		} else {
			$this->data['uksb_sitemap_products_pr'] = $this->config->get('uksb_sitemap_products_pr');
		}
	
		if (isset($this->request->post['uksb_sitemap_categories_on'])) {
			$this->data['uksb_sitemap_categories_on'] = $this->request->post['uksb_sitemap_categories_on'];
		} else {
			$this->data['uksb_sitemap_categories_on'] = $this->config->get('uksb_sitemap_categories_on');
		}
	
		if (isset($this->request->post['uksb_sitemap_categories_fr'])) {
			$this->data['uksb_sitemap_categories_fr'] = $this->request->post['uksb_sitemap_categories_fr'];
		} else {
			$this->data['uksb_sitemap_categories_fr'] = $this->config->get('uksb_sitemap_categories_fr');
		}
	
		if (isset($this->request->post['uksb_sitemap_categories_pr'])) {
			$this->data['uksb_sitemap_categories_pr'] = $this->request->post['uksb_sitemap_categories_pr'];
		} else {
			$this->data['uksb_sitemap_categories_pr'] = $this->config->get('uksb_sitemap_categories_pr');
		}
	
		if (isset($this->request->post['uksb_sitemap_manufacturers_on'])) {
			$this->data['uksb_sitemap_manufacturers_on'] = $this->request->post['uksb_sitemap_manufacturers_on'];
		} else {
			$this->data['uksb_sitemap_manufacturers_on'] = $this->config->get('uksb_sitemap_manufacturers_on');
		}
	
		if (isset($this->request->post['uksb_sitemap_manufacturers_fr'])) {
			$this->data['uksb_sitemap_manufacturers_fr'] = $this->request->post['uksb_sitemap_manufacturers_fr'];
		} else {
			$this->data['uksb_sitemap_manufacturers_fr'] = $this->config->get('uksb_sitemap_manufacturers_fr');
		}
	
		if (isset($this->request->post['uksb_sitemap_manufacturers_pr'])) {
			$this->data['uksb_sitemap_manufacturers_pr'] = $this->request->post['uksb_sitemap_manufacturers_pr'];
		} else {
			$this->data['uksb_sitemap_manufacturers_pr'] = $this->config->get('uksb_sitemap_manufacturers_pr');
		}
	
		if (isset($this->request->post['uksb_sitemap_pages_on'])) {
			$this->data['uksb_sitemap_pages_on'] = $this->request->post['uksb_sitemap_pages_on'];
		} else {
			$this->data['uksb_sitemap_pages_on'] = $this->config->get('uksb_sitemap_pages_on');
		}
	
		if (isset($this->request->post['uksb_sitemap_pages_fr'])) {
			$this->data['uksb_sitemap_pages_fr'] = $this->request->post['uksb_sitemap_pages_fr'];
		} else {
			$this->data['uksb_sitemap_pages_fr'] = $this->config->get('uksb_sitemap_pages_fr');
		}
	
		if (isset($this->request->post['uksb_sitemap_pages_pr'])) {
			$this->data['uksb_sitemap_pages_pr'] = $this->request->post['uksb_sitemap_pages_pr'];
		} else {
			$this->data['uksb_sitemap_pages_pr'] = $this->config->get('uksb_sitemap_pages_pr');
		}
	
		if (isset($this->request->post['uksb_sitemap_pages_pr'])) {
			$this->data['uksb_sitemap_pages_pr'] = $this->request->post['uksb_sitemap_pages_pr'];
		} else {
			$this->data['uksb_sitemap_pages_pr'] = $this->config->get('uksb_sitemap_pages_pr');
		}
	
		if (isset($this->request->post['uksb_sitemap_pages_pr'])) {
			$this->data['uksb_sitemap_pages_pr'] = $this->request->post['uksb_sitemap_pages_pr'];
		} else {
			$this->data['uksb_sitemap_pages_pr'] = $this->config->get('uksb_sitemap_pages_pr');
		}
	
		if (isset($this->request->post['uksb_pages_omit_a'])) {
			$this->data['uksb_pages_omit_a'] = $this->request->post['uksb_pages_omit_a'];
		} else {
			$this->data['uksb_pages_omit_a'] = $this->config->get('uksb_pages_omit_a');
		}
	
		if (isset($this->request->post['uksb_pages_omit_b'])) {
			$this->data['uksb_pages_omit_b'] = $this->request->post['uksb_pages_omit_b'];
		} else {
			$this->data['uksb_pages_omit_b'] = $this->config->get('uksb_pages_omit_b');
		}
		
		$this->load->model('feed/uksb_sitemaps');

		$this->data['informations'] = array();
	
		$results = $this->model_feed_uksb_sitemaps->getInformationList();
 
	    	foreach ($results as $result) {
			$this->data['informations'][] = array(
				'information_id' => $result['information_id'],
				'title'          => $result['title']
			);
			
			if (isset($this->request->post['uksb_pages_omit_'.$result['information_id']])) {
				$this->data['uksb_pages_omit_'.$result['information_id']] = $this->request->post['uksb_pages_omit_'.$result['information_id']];
			} else {
				$this->data['uksb_pages_omit_'.$result['information_id']] = $this->config->get('uksb_pages_omit_'.$result['information_id']);
			}
		}	

		$this->load->model('feed/uksb_google_merchant');
		
		$this->data['data_feed'] = HTTP_CATALOG.'index.php?route=feed/uksb_sitemaps/google&store=0';
		$this->data['data_feed2'] = HTTP_CATALOG.'index.php?route=feed/uksb_sitemaps/all&store=0';

		$this->load->model('setting/store');

		if($this->model_setting_store->getTotalStores()>0){
			$stores = $this->model_setting_store->getStores();
			$stores = array_reverse($stores);
			
			foreach($stores as $store){
				$this->data['data_feed'] .= '^'.$store['url'].'index.php?route=feed/uksb_sitemaps/google&store='.$store['store_id'];
				$this->data['data_feed2'] .= '^'.$store['url'].'index.php?route=feed/uksb_sitemaps/all&store='.$store['store_id'];
			}
		}
		
		$this->template = 'feed/uksb_sitemaps.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	} 
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'feed/uksb_sitemaps')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}	
}
?>