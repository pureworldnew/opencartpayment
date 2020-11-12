<?php
class ControllerCatalogGroupedBatchSort extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('catalog/grouped_batch_sort');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');
		
		if (isset($this->request->get['mode']) && $this->request->get['mode'] == 'export' && isset($this->request->get['filter_groupindicator'])) {
			$this->model_catalog_product->exportProductsByGroupIndicator($this->request->get['filter_groupindicator']);
		}
		
		if (!empty($_FILES['csv_file']['name'])) 
		{
			$temp = explode(".", $_FILES["csv_file"]["name"]);
        	$extension = end($temp);
			
           
            if (($_FILES["csv_file"]["type"] == "application/vnd.ms-excel")||($_FILES["csv_file"]["type"] == "text/comma-separated-values")||($_FILES["csv_file"]["type"] == "text/csv")|| ($_FILES["csv_file"]["type"] == "application/csv")) {
                if ($_FILES["csv_file"]["error"] > 0) {
					$this->session->data['error_warning'] = $_FILES["csv_file"]["error"];
                } else {
                    if ($extension == "csv") {
                        $storagefile2 = DIR_DOWNLOAD . "uploaded_groupindicator_csv.csv";
                        if(move_uploaded_file($_FILES["csv_file"]["tmp_name"], $storagefile2)){
							$this->session->data['success'] = "File Imported";
						}

						$file_import = fopen($storagefile2, "r");
						while(! feof($file_import))
  						{
	  						$temp = fgetcsv($file_import);
							$this->model_catalog_product->UpdateCSVSortingData($temp);
						}
						
                        fclose($file_import);
						
                    } else {
						$this->session->data['error_warning'] = "Invalid file, please use csv file for import.";
					}
                }
            } else {
				$this->session->data['error_warning'] = "Invalid file, please use csv file for import.";
			}
        
		}

		$this->getList();
	}

	

	protected function getList() {
		if (isset($this->request->get['filter_groupindicator'])) {
			$filter_groupindicator = $this->request->get['filter_groupindicator'];
		} else {
			$filter_groupindicator = null;
		}

		

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_groupindicator'])) {
			$url .= '&filter_groupindicator=' . urlencode(html_entity_decode($this->request->get['filter_groupindicator'], ENT_QUOTES, 'UTF-8'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/product_grouped_sort', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['update'] = $this->url->link('catalog/product_grouped_sort/update', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['products'] = array();

		$filter_data = array(
			'filter_groupindicator'	  => $filter_groupindicator
		);

		$results = $this->model_catalog_product->getGroupProductsSorting($filter_data);

		foreach ($results['product_grouped'] as $result) { 
			$data['main_options'][] = array(
				'product_grouped_id' => $result['product_grouped_id'],
				'type' => $result['type'],
				'option_name' => $result['option_name'],
				'sort_order' => $result['sort_order']
			);
		}
		
		foreach ($results['options'] as $option) {
			
			foreach ( $option['product_option_value'] as $product_option_value )
			$data['other_options'][] = array(
				'name' 		 			  => $option['name'],
				'product_option_value_id' => $product_option_value['product_option_value_id'],
				'option_value_name' => $product_option_value['option_value_name'],
				'sort_order' => $product_option_value['sort_order']
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		
		$data['button_export'] = $this->language->get('button_export');
		

		$data['column_product_id'] = $this->language->get('column_product_id');
		$data['column_group_id'] = $this->language->get('column_group_id');
		$data['column_option'] = $this->language->get('column_option');
		$data['column_type'] = $this->language->get('column_type');
		$data['column_groupby_type'] = $this->language->get('column_groupby_type');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		

		$data['entry_groupindicator'] = $this->language->get('entry_groupindicator');
		$data['entry_groupby_option'] = $this->language->get('entry_groupby_option');
		$data['entry_other_option'] = $this->language->get('entry_other_option');
		$data['entry_nogroupby_option'] = $this->language->get('entry_nogroupby_option');
		$data['entry_noother_option'] = $this->language->get('entry_noother_option');
		
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_groupindicator'])) {
			$url .= '&filter_groupindicator=' . urlencode(html_entity_decode($this->request->get['filter_groupindicator'], ENT_QUOTES, 'UTF-8'));
		}

		$data['filter_groupindicator'] = $filter_groupindicator;
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/grouped_batch_sort.tpl', $data));
	}

}
