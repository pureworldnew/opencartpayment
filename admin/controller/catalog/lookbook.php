<?php
class ControllerCatalogLookbook extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('catalog/lookbook');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/lookbook');

		$this->getList();
	}

	public function add() {
		$this->language->load('catalog/lookbook');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/lookbook');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_lookbook->addLookbookImage($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('catalog/lookbook', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getFormAdd();
	}

	public function edit() {
		$this->language->load('catalog/lookbook');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/lookbook');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			
			//print_r($_POST);
			//exit();
			$this->model_catalog_lookbook->editLookbook($this->request->get['lookbook_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('catalog/lookbook', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getFormEdit();
	}

	public function delete() {
		$this->language->load('catalog/lookbook');

		$this->document->setTitle($this->language->get('heading_title'));


		$this->load->model('catalog/lookbook');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			
			if($this->request->post['frmaction'] == "enable"){
				foreach ($this->request->post['selected'] as $lookbook_id) {
					$this->model_catalog_lookbook->changeStatusLookbook($lookbook_id,1);
				}
			}
			elseif($this->request->post['frmaction'] == "disable"){
				foreach ($this->request->post['selected'] as $lookbook_id) {
					$this->model_catalog_lookbook->changeStatusLookbook($lookbook_id,0);
					
				}
			}
			else{
				foreach ($this->request->post['selected'] as $lookbook_id) {
					$this->model_catalog_lookbook->deleteLookbook($lookbook_id);
				}
			}	

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('catalog/lookbook', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}
protected function compress_image($source_url, $destination_url, $quality) {

		list($width, $height) =  getimagesize($source_url);
		
		$percentage =  130/$width;
		$newHeight = $height * $percentage;
    	$info = getimagesize($source_url);
    		if ($info['mime'] == 'image/jpeg')
        			$img  = imagecreatefromjpeg($source_url);

    		elseif ($info['mime'] == 'image/gif')
        			$img  = imagecreatefromgif($source_url);

   		elseif ($info['mime'] == 'image/png')
        			$img  = imagecreatefrompng($source_url);
       
	   
	   $tmp_img = imagecreatetruecolor(130,$newHeight);
       imagecopyresized( $tmp_img, $img, 0, 0, 0, 0,130,$newHeight, $width, $height );
  
        			imagejpeg($tmp_img, $destination_url);

    		
		return $destination_url;
	}	
	
public function uploadTagThumb(){

   
$this->load->model('catalog/lookbook');
$output_dir = DIR_IMAGE."lookbook/tagthumbs/";
//print_r($_FILES["myfile"]);
if(isset($_FILES["myfile"]))
{
	$ret = array();

	$error =$_FILES["myfile"]["error"];
   {
        $RandomNum   = time();
            
            $ImageName      = str_replace(' ','-',strtolower($_FILES['myfile']['name']));
            $ImageType      = $_FILES['myfile']['type']; //"image/png", image/jpeg etc.
         
            $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt       = str_replace('.','',$ImageExt);
            $ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
            $NewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;
            $url = $output_dir. $NewImageName;
			
            $filename = $this->compress_image($_FILES["myfile"]["tmp_name"], $url, 100);
       	 	$NewImageName= $output_dir.$NewImageName;
		
    }
    echo $NewImageName;
 
}
else
echo "Error: File not found";
exit();

}
public function upload() {

$this->load->model('catalog/lookbook');
$output_dir = DIR_IMAGE."lookbook/";
	if(isset($_FILES["myfile"]))
	{
		$ret = array();
	
		$error =$_FILES["myfile"]["error"];
	   {
		
			if(!is_array($_FILES["myfile"]['name'])) //single file
			{
				$RandomNum   = time();
				
				$ImageName      = str_replace(' ','-',strtolower($_FILES['myfile']['name']));
				$ImageType      = $_FILES['myfile']['type']; //"image/png", image/jpeg etc.
			 
				$ImageExt = substr($ImageName, strrpos($ImageName, '.'));
				$ImageExt       = str_replace('.','',$ImageExt);
				$ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
				$NewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;
	
				move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir. $NewImageName);
				 //echo "<br> Error: ".$_FILES["myfile"]["error"];
				 
					 $ret[$NewImageName]= $output_dir.$NewImageName;
					 $this->model_catalog_lookbook->addLookbookImage($NewImageName);
			}
			else
			{
				$fileCount = count($_FILES["myfile"]['name']);
				for($i=0; $i < $fileCount; $i++)
				{
					$RandomNum   = time();
				
					$ImageName      = str_replace(' ','-',strtolower($_FILES['myfile']['name'][$i]));
					$ImageType      = $_FILES['myfile']['type'][$i]; //"image/png", image/jpeg etc.
				 
					$ImageExt = substr($ImageName, strrpos($ImageName, '.'));
					$ImageExt       = str_replace('.','',$ImageExt);
					$ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
					$NewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;
					
					$ret[$NewImageName]= $output_dir.$NewImageName;
					move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$NewImageName );
					$this->model_catalog_lookbook->addLookbookImage($NewImageName);
				}
			}
		}
		echo json_encode($ret);
	 
	}
	else{
		echo "Error: File not found";
		exit();
	}
}



	protected function getList() {
		

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$url = '';

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
			'href' => $this->url->link('catalog/lookbook', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$data['insert'] = $this->url->link('catalog/lookbook/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/lookbook/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		
		$data['disable'] = $this->url->link('catalog/lookbook/disable', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$data['enable'] = $this->url->link('catalog/lookbook/enable', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['addtag'] = $this->url->link('catalog/lookbook/addtag', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['lookbooks'] = array();

		$filter_data = array(
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$lookbook_total = $this->model_catalog_lookbook->getTotalLookbooks();

		$results = $this->model_catalog_lookbook->getLookbooks($filter_data);

		foreach ($results as $result) {
			$data['lookbooks'][] = array(
				'lookbook_id' => $result['lookbook_id'],
				'image_name'          => $result['image_name'],
				'no_of_tags'          => $result['no_of_tags'],
				'image_title'          => $result['image_title'],
				'status'          => $result['status']==1?$this->language->get('button_enable'):$this->language->get('button_disable'),
				'edit'           => $this->url->link('catalog/lookbook/edit', 'token=' . $this->session->data['token'] . '&lookbook_id=' . $result['lookbook_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_status'] = $this->language->get('column_status');
		
		$data['column_thumbnail'] = $this->language->get('column_thumbnail');
		$data['column_title'] = $this->language->get('column_title');
		$data['column_no_of_tags'] = $this->language->get('column_no_of_tags');

		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_enable'] = $this->language->get('button_enable');
		$data['button_disable'] = $this->language->get('button_disable');
		$data['button_insert_tags'] = $this->language->get('button_insert_tags');

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';
        if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'lookbook_id';
		}
		
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_title'] = $this->url->link('catalog/lookbook', 'token=' . $this->session->data['token'] . '&sort=id.title' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('catalog/lookbook', 'token=' . $this->session->data['token'] . '&sort=i.sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $lookbook_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/lookbook', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($lookbook_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($lookbook_total - $this->config->get('config_limit_admin'))) ? $lookbook_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $lookbook_total, ceil($lookbook_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('catalog/lookbook_list.tpl', $data));
	}

	protected function getFormAdd() {
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['lookbook_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
        $data['button_save'] = $this->language->get('button_save');
		$data['text_upload'] = $this->language->get('text_upload');
		
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
			'href' => $this->url->link('catalog/lookbook', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		$data['button_cancel'] = $this->language->get('button_cancel');		
		$data['action'] = $this->url->link('catalog/lookbook/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		 
		$data['cancel'] = $this->url->link('catalog/lookbook', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['upload'] = $this->url->link('catalog/lookbook/upload', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['text_go_back'] = $this->language->get('text_go_back1')."<a href='".$data['cancel']."'>". $this->language->get('text_go_back2')."</a>". $this->language->get('text_go_back3');
		
		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/lookbook_form_add.tpl', $data));
	}


protected function getFormEdit() {
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['lookbook_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_image_view'] = $this->language->get('entry_image_view');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['image_title'])) {
			$data['error_image_title'] = $this->error['image_title'];
		} 
		if (isset($this->error['image_meta_desciption'])) {
			$data['error_image_meta_desciption'] = $this->error['image_meta_desciption'];
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
			'href' => $this->url->link('catalog/lookbook', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');		
		$data['token'] = $this->session->data['token'];
        $data['action'] = $this->url->link('catalog/lookbook/edit', 'token=' . $this->session->data['token'] . '&lookbook_id=' . $this->request->get['lookbook_id'] . $url, 'SSL');
		$data['upload'] = $this->url->link('catalog/lookbook/uploadTagThumb', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['cancel'] = $this->url->link('catalog/lookbook', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		
		if (isset($this->request->get['lookbook_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$lookbook_info = $this->model_catalog_lookbook->getLookbook($this->request->get['lookbook_id']);
			$lookbook_info_tags = $this->model_catalog_lookbook->getLookbookTags($this->request->get['lookbook_id']);	
		}
		
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['image_name'])) {
			$data['image_name'] = $this->request->post['image_name'];
		} elseif (!empty($lookbook_info)) {
			$data['image_name'] = $lookbook_info['image_name'];
		} else {
			$data['image_name'] = '';
		}
		
		if (isset($this->request->post['image_title'])) {
			$data['image_title'] = $this->request->post['image_title'];
		} elseif (!empty($lookbook_info)) {
			$data['image_title'] = $lookbook_info['image_title'];
		} else {
			$data['image_title'] = '';
		}
         
		if (isset($this->request->post['image_meta_desciption'])) {
			$data['image_meta_desciption'] = $this->request->post['image_meta_desciption'];
		} elseif (!empty($lookbook_info)) {
			$data['image_meta_desciption'] = $lookbook_info['image_meta_desciption'];
		} else {
			$data['image_meta_desciption'] = '';
		}
		
		if (isset($this->request->post['tag_title'])) {
		
		     $image_tag_info_array = array();
		     $ivar = 0;
				foreach($this->request->post['tag_title'] as $tag_title)
				{
				   $tag_image = str_replace("../image/","",$this->request->post['tag_image'][$ivar]);
				   $tag_price = $this->request->post['tag_price'][$ivar];
				   $tag_link = $this->request->post['tag_link'][$ivar];
				   $tagy = $this->request->post['tagy'][$ivar];
				   $tagx = $this->request->post['tagx'][$ivar];
				   
				   $image_tag_info_array[$tagx."_".$tagy] = array($tag_title,$tag_price,$tag_link,$tagx,$tagy,$tag_image);
				   $ivar++;
				}
				
				$data['image_tag_info'] = $image_tag_info_array;
				//print_r($data['image_tag_info']);
				//exit();
		
		} elseif (count($lookbook_info_tags)>0) {		   
			$data['image_tag_info'] = $lookbook_info_tags;
		} else {
			$data['image_tag_info'] = array();
		}
		
		// Categories
		$this->load->model('catalog/category');
		
		if (isset($this->request->post['lookbook_category'])) {
			$categories = $this->request->post['lookbook_category'];
		}  elseif (!empty($lookbook_info)) {
			$categories = $this->model_catalog_lookbook->getLookbookCategories($this->request->get['lookbook_id']);
		} else {
			$categories = array();
		}
	
		$data['lookbook_categories'] = array();
		
		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);
			
			if ($category_info) {
				$data['lookbook_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name']
				);
			}
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/lookbook_form_edit.tpl', $data));
	}


	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/lookbook')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
       $value = $this->request->post;
			
			if ((utf8_strlen($value['image_title']) < 3) || (utf8_strlen($value['image_title']) > 255)) {
				$this->error['image_title'] = $this->language->get('error_image_title');
			}

			if (utf8_strlen($value['image_meta_desciption']) < 3) {
				$this->error['image_meta_desciption'] = $this->language->get('error_image_meta_desciption');
			}

		

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/lookbook')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');

	
		return !$this->error;
	}
}