<?php
class ControllerModuleLookbook extends Controller {
	public function index($setting) {
		static $module = 0;
		
		$this->load->model('catalog/lookbook');
		//$this->document->addStyle('catalog/view/css/jquery.gridly.css');
	//	$this->document->addStyle('catalog/view/css/sample.css');	
		//$this->document->addScript('catalog/view/javascript/jquery.gridly.js');
        //$this->document->addScript('catalog/view/javascript/sample.js');
        //$this->document->addScript('catalog/view/javascript/rainbow.js');


    

		//$data['width'] = $setting['width'];
		//$data['height'] = $setting['height'];

		$data['lookbook'] = array();
		

       if(isset($this->request->get['route']) && $this->request->get['route'] == "product/category")
       {
		   	$data['route'] = $this->request->get['route'];
            $category = empty($this->request->get['path']) ? 0 : (int) array_pop(explode('_', $this->request->get['path']));
//echo $category;
			
			$results = $this->model_catalog_lookbook->getLookbooksByCategory($category);
			  $resultsTags = $this->model_catalog_lookbook->getLookbooksTagsByCategory($category);
			/*print_r( $results);
exit();*/
			if(count($results) < 4)
		    	return true;
			$ivar = 1;
			  foreach ($results as $result) {
				   if($ivar <= 8){
					  $data['lookbooks'][] = array(
						  'lookbook_id' => $result['lookbook_id'],
						  'image_name'          => $result['image_name'],
						  'no_of_tags'          => $result['no_of_tags'],
						  'image_meta_desciption'   => $result['image_meta_desciption'],
						  'image_tag_info'          => $resultsTags[$result['lookbook_id']],
						  'image_title'          => $result['image_title']
					  );
				   }
				   else
				   {
					   $data['remlookbooks'][] = array(
						  'lookbook_id' => $result['lookbook_id'],
						  'image_name'          => $result['image_name'],
						  'no_of_tags'          => $result['no_of_tags'],
						  'image_meta_desciption'   => $result['image_meta_desciption'],
						  'image_tag_info'          =>$resultsTags[$result['lookbook_id']],
						  'image_title'          => $result['image_title']
					  );
				   }
			    $ivar++;
		      }
			
	   }
	   else
	   {
		   	  $data['route'] = '';
			  $results = $this->model_catalog_lookbook->getLookbooks();
			  $resultsTags = $this->model_catalog_lookbook->getLookbooksTags();
			  $ivar = 1;
			  foreach ($results as $result) {
				   if($ivar <= 12){
					  	if(isset($resultsTags[$result['lookbook_id']])){
					   		
							  $data['lookbooks'][] = array(
								  'lookbook_id' => $result['lookbook_id'],
								  'image_name'          => $result['image_name'],
								  'no_of_tags'          => $result['no_of_tags'],
								  'image_meta_desciption'   => $result['image_meta_desciption'],
								  'image_tag_info'          => $resultsTags[$result['lookbook_id']],
								  'image_title'          => $result['image_title']
							  );
				   
						}else{
							
					  $data['lookbooks'][] = array(
						  'lookbook_id' => $result['lookbook_id'],
						  'image_name'          => $result['image_name'],
						  'no_of_tags'          => $result['no_of_tags'],
						  'image_meta_desciption'   => $result['image_meta_desciption'],
						  'image_tag_info'          => array(),
						  'image_title'          => $result['image_title']
					  );
				   	
						}
				   }
				   else
				   {
					  if(isset($resultsTags[$result['lookbook_id']])){
						   $data['remlookbooks'][] = array(
							  'lookbook_id' => $result['lookbook_id'],
							  'image_name'          => $result['image_name'],
							  'no_of_tags'          => $result['no_of_tags'],
							  'image_meta_desciption'   => $result['image_meta_desciption'],
							  'image_tag_info'          =>$resultsTags[$result['lookbook_id']],
							  'image_title'          => $result['image_title']
						  );
				   		}else{
							$data['remlookbooks'][] = array(
							  'lookbook_id' => $result['lookbook_id'],
							  'image_name'          => $result['image_name'],
							  'no_of_tags'          => $result['no_of_tags'],
							  'image_meta_desciption'   => $result['image_meta_desciption'],
							  'image_tag_info'          =>array(),
							  'image_title'          => $result['image_title']
						  );
							
						}
				   }
			    $ivar++;
		      }
		   
		}

		$data['module'] = $module++;
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/lookbook.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/lookbook.tpl', $data);
		} else {
			return $this->load->view('default/template/module/lookbook.tpl', $data);
		}
		
	}
}