<?php
// ***************************************************
//               Advanced Smart Search   
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************


require_once(substr_replace(DIR_SYSTEM, '/leverod/index.php', -8));	// -8 = /system/

// Catalog Controller

class ControllerModuleAdsmartSearch extends LevController {

	private $extension;
	private $filename;
	private $current_route;

	
	public function __construct($registry) {
	
		parent::__construct($registry);

		// Paths and Files
		$this->extension = 'adsmart_search';
		$this->filename = 'adsmart_search'; 
		$this->current_route = 'module/adsmart_search';
		
	//	$this->language->load($this->current_route); // It doesn't exist on this module		
	}

	
	public function index($setting) {
	
		static $module = 0;
		
		$this->language->load('common/header');
		$data['text_search'] = $this->language->get('text_search');
		
		// Search		
		if (isset($this->request->get['search'])) {
			$data['search'] = $this->request->get['search'];
		} else {
			$data['search'] = '';
		}
		
		$data['adsmart_search_width']  = isset($setting['width'])?  $setting['width']  : '';
		$data['adsmart_search_height'] = isset($setting['height'])? $setting['height'] : '';
		
		$data['module'] = $module++;
		
		
		$view_path = 'module/' . $this->filename;

		return $this->load_view($view_path, $data, false);

	}



	public function update_search_cache(){
	
		$this->load->model('catalog/adsmart_search');
	
		if ( !isset($this->request->get['token'] ) || ( ($this->request->server['REQUEST_METHOD'] == 'GET' && $this->session->data['token'] != $this->request->get['token']) ) ) {		
			$this->response->setOutput('Permission Denied');
			return;
		}
		
		$label = 'search_string';
		$files = glob(DIR_CACHE . 'cache-'.$label.'.*');
		

		if ($files) {
		
			$this->load->model('catalog/product');
			
			foreach ($files as $file) {
			
				if (file_exists($file)) {
				
					$cache = file_get_contents($file);
					
					$data = unserialize($cache);
				
				// 1) Get the search string from the file name:
				
					// Full filename path sample:
					// /system/cache/cache-search_string.iphone.1382192679
					// basename($file) = cache-search_string.iphone.1382192679
					
					// get the splitted basename using the dot as string separator
					$basename_split = explode(".", basename($file));
					$search_string = $basename_split[1]; // iphone, from the above example
				
				
				// 2) delete the old cache file and Perform a new search for the same search string
				
					
					unlink($file);

					// The last element of the array $data is the the array "current search options"
					$current_search_options = $data['search_options'];

					// unset the array $data and reuse it to set the current search options
					unset($data);

					
					$filter_data = array(
						'store_id'					=> $current_search_options['sid'],
						'language_id'				=> $current_search_options['lid'],
						'customer_group_id'			=> $current_search_options['cgi'],
						'filter_name'				=> $current_search_options['fn'],
						'filter_tag'				=> $current_search_options['ft'], 
						'filter_filter'				=> $current_search_options['ff'],
						'filter_description'		=> $current_search_options['fd'],
						'filter_category_id'		=> $current_search_options['fci'], 
						'filter_sub_category'		=> $current_search_options['fsc'], 
						'filter_manufacturer_id'	=> $current_search_options['fmi'], 
						'sort'						=> $current_search_options['srt'],
						'order'						=> $current_search_options['o']
					);

					
					// Enable the options "start" and "limit" only when results are paginated by MySQL. When the pagination 
					// is made by PHP, the query will return the whole list of results which will be paginated in a second time.
					// Also the cache files contain the full set of results, not just a limited number of items like with the
					// MySQL pagination (LIMIT Clause). For this reason, cache files could get very big.
					// See also the file /catalog/model/catalog/adSmartSrc_product_inc.php, array $search_options.

					if ( isset($current_search_options['strt']) ) {				
						$filter_data['start'] 	= $current_search_options['strt'];		
					}
					if ( isset($current_search_options['l']) ) {
						$filter_data['limit']	= $current_search_options['l'];			
					}
					
					$product_data = $this->model_catalog_adsmart_search->getProducts($filter_data);

					$this->model_catalog_adsmart_search->adsmart_search_set($current_search_options, $product_data, $this->registry->get('product_total'), $label, $this->config->get('adsmart_search_cache_update_frequency'));
				}
			}
		}

		$this->response->setOutput(true);
	}
			

}
