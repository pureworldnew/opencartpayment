<?php
class ControllerCommonAddlocation extends Controller {
	    public function index(){

		$this->load->language('common/header');
		$this->load->model('common/addlocation');	
		$data['locationlist'] = $this->model_common_addlocation->getloc();
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['location_action'] = $this->url->link('common/add_location/add_loc', 'token=' . $this->session->data['token'], 'SSL');
		$data['edit_act'] = $this->url->link('common/add_location/edit_loc', 'token=' . $this->session->data['token'], true);
		$data['del_act'] = $this->url->link('common/add_location/del_loc', 'token=' . $this->session->data['token'], true);
			$this->response->setOutput($this->load->view('common/addlocation.tpl', $data));
		} 
		public function add_loc(){
			$this->load->model('common/addlocation');
			if ($this->request->server['REQUEST_METHOD'] == 'POST'){
			$loc_name = $_POST['loc_name'];
			$this->model_common_addlocation->addloc($loc_name);
			$this->response->redirect($this->url->link('common/add_location', 'token=' . $this->session->data['token'], true));

		   }
		}
		public function edit_loc(){
			$id = $_GET['exp'];
 			$this->load->model('common/addlocation');
 			$data['editdata'] = $this->model_common_addlocation->get_edit_amount($id);
 			$data['form_act'] = $this->url->link('common/add_location/updateloc', 'token=' . $this->session->data['token'], true);
 			$data['header'] = $this->load->controller('common/header');
		    $data['column_left'] = $this->load->controller('common/column_left');
		    $data['footer'] = $this->load->controller('common/footer');
 			$this->response->setOutput($this->load->view('common/editlocation.tpl', $data));
		}
		public function del_loc(){
			$this->load->model('common/addlocation');
			$id = $this->request->get['exp'];
		//echo "<pre>"; print_r($id); exit(); 
		$res = $this->model_common_addlocation->delloc($id);
			 $this->response->redirect($this->url->link('common/add_location', 'token=' . $this->session->data['token'], true));
		}
		public function updateloc(){
			$data = $_POST;
			$this->load->model('common/addlocation');
			$res = $this->model_common_addlocation->updateloc($data);
			 $this->response->redirect($this->url->link('common/add_location', 'token=' . $this->session->data['token'], true));

		}
	
}	
?>