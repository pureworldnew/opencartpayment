<?php

class ControllerFeedIeSorting extends Controller {

    private $error = array();
    public $error1 = array('text' => '', 'type' => '');
    
    /* public function index() {
        $this->language->load('feed/ie_sorting');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('ie_sorting', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->load->model('feed/ie_sorting');
//        $data['category_list'] = $this->model_feed_ie_tool->selectCategory();
        $data['category_list'] = $this->model_feed_ie_sorting->getCategories();
        $data['manufacture_list'] = $this->model_feed_ie_sorting->selectManufacture();

        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_or'] = $this->language->get('text_or');
        $data['text_select_cate'] = $this->language->get('text_select_cate');
        $data['text_select_manu'] = $this->language->get('text_select_manu');
        $data['button_export'] = $this->language->get('button_export');
        $data['button_concat_export'] = $this->language->get('button_concat_export');


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_feed'),
            'href' => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('feed/ie_sorting', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['action'] = $this->url->link('feed/ie_sorting', 'token=' . $this->session->data['token'], 'SSL');
        $data['action_export'] = $this->url->link('feed/ie_sorting/export', 'token=' . $this->session->data['token'], 'SSL');
        $data['action_concat_export'] = $this->url->link('feed/ie_sorting/concatExport', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');

        $data['export_link'] = $this->url->link('feed/ie_sorting/export', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['ie_sorting_status'])) {
            $data['ie_sorting_status'] = $this->request->post['ie_sorting_status'];
        } else {
            $data['ie_sorting_status'] = $this->config->get('ie_sorting_status');
        }

        $data['data_feed'] = HTTP_CATALOG . 'index.php?route=feed/ie_sorting';


        $this->template = 'feed/ie_sorting.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        $this->response->setOutput($this->render());
    } */
	
	public function index() {
        $this->language->load('feed/ie_sorting');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        

        $this->load->model('feed/ie_sorting');

        $data['heading_title'] = 'Import Sorting';
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_file'] = $this->language->get('text_file');
        $data['text_file2'] = $this->language->get('text_file2');
        $data['text_file3'] = $this->language->get('text_file3');
        $data['button_import'] = $this->language->get('button_import');


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else if(isset($this->request->get['warning'])){
            $data['error_warning'] = $this->request->get['warning'];
        } else {
		     $data['error_warning'] = '';
        }
		if(isset($this->request->get['success'])){
			$this->session->data['success'] = $this->language->get('text_success');
		}
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_feed'),
            'href' => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('feed/ie_sorting', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['action'] = $this->url->link('feed/ie_sorting', 'token=' . $this->session->data['token'], 'SSL');
        $data['action_import'] = $this->url->link('feed/ie_sorting/import', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['ie_sorting_status'])) {
            $data['ie_sorting_status'] = $this->request->post['ie_sorting_status'];
        } else {
            $data['ie_sorting_status'] = $this->config->get('ie_sorting_status');
        }

        $data['data_feed'] = HTTP_CATALOG . 'index.php?route=feed/ie_sorting';
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('feed/ie_sorting_import.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'feed/ie_sorting')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    private function processData($results,$headers) {
        $file = date('m-d-Y') . '_' . uniqid() . rand(0, 4500);
        $file = $file . '.csv';
        
        $fp = fopen(DIR_DOWNLOAD . $file, 'w');
        fputcsv($fp, $headers,$delimiter = ';');

        foreach ($results as $fields) {
            fputcsv($fp, $fields,$delimiter);
        }
 
       fclose($fp);

        $file = DIR_DOWNLOAD . $file; //path to the file on disk
        if (file_exists($file)) {

            //set appropriate headers
            header('Content-Description: File Transfer');
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            // ob_clean();
            flush();

            //read the file from disk and output the content.
            readfile($file);
            exit;
        }
    }
    
    private function createXLS($data){
       
/** Error reporting */
    error_reporting(E_ALL);

    /** Include path **/
    ini_set('include_path', ini_get('include_path').';../Classes/');

    /** PHPExcel */
//    include 'PHPExcel.php';

    /** PHPExcel_Writer_Excel2007 */
    include 'PHPExcel/Writer/Excel2007.php';

    // Create new PHPExcel object
    echo date('H:i:s') . " Create new PHPExcel object\n";
    $objPHPExcel = new PHPExcel();

    // Set properties
    echo date('H:i:s') . " Set properties\n";
    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
    $objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
    $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
    $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
    $objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");


    // Add some data
    echo date('H:i:s') . " Add some data\n";
    foreach($data as $key=>$product)
    {
        foreach ($product as $key2=>$value) {
           $new=$key+1;
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$new, $key2);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$new, $value);
        }
//        die;
    }
    // Rename sheet
    echo date('H:i:s') . " Rename sheet\n";
    $objPHPExcel->getActiveSheet()->setTitle('Simple');


    // Save Excel 2007 file
    echo date('H:i:s') . " Write to Excel2007 format\n";
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter->save(str_replace('.php', '.xlsx', __FILE__));

    // Echo done
    echo date('H:i:s') . " Done writing file.\r\n";
    }
    
    public function concatExport(){
        $concat_category_id = $_REQUEST['select_concat_category'];
        $concat_manufacture_id = $_REQUEST['select_concat_manufacture'];
        $this->load->model('feed/ie_sorting');
        $list_data = $this->model_feed_ie_sorting->getGroupProductData($concat_category_id, $concat_manufacture_id);//contains the data
        if($list_data){
            $headers = array('id','name', 'sku', 'product_id', 'groupindicator', 'groupindicator_id',
                                'groupbyname', 'groupbyvalue',
                                'optionname1', 'optionvalue1',
                                'optionname2', 'optionvalue2',
                                'optionname3', 'optionvalue3',
                                'optionname4', 'optionvalue4',
                                'optionname5', 'optionvalue5',
                                'optionname6', 'optionvalue6',
                                'optionname7', 'optionvalue7',
                                'optionname8', 'optionvalue8',
                                'optionname9', 'optionvalue9',
                                'optionname10', 'optionvalue10',
                                'optionname11', 'optionvalue11',
                                'groupedproductname'
                            );
            $this->processData($list_data,$headers);
        } else {
            echo "No product fall in this combination.";
//            $this->error1['text'] = "No product fall in this conbinatation.";
//            $this->error1['type'] = 'error';
//            $data[$this->error1];
        }
    }
    
    public function export(){
        $this->language->load('feed/ie_sorting');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('ie_sorting', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->load->model('feed/ie_sorting');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_export'] = $this->language->get('button_export');
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_feed'),
            'href' => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('feed/ie_sorting', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['action_export'] = $this->url->link('feed/ie_sorting/export_sorting_csv', 'token=' . $this->session->data['token'], 'SSL');   
        $data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');
        $data['data_feed'] = HTTP_CATALOG . 'index.php?route=feed/ie_sorting';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('feed/unit_export.tpl', $data));
		
        
    }
    public function import() {
        $this->load->model('feed/ie_sorting');
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);
		$warning = '';
		$success = '';
        if (!empty($_FILES['file']['name'])) {
           
            if (($_FILES["file"]["type"] == "application/vnd.ms-excel")||($_FILES["file"]["type"] == "text/comma-separated-values")||($_FILES["file"]["type"] == "text/csv")|| ($_FILES["file"]["type"] == "application/csv")) {
                if ($_FILES["file"]["error"] > 0) {
                    echo "Error: " . $_FILES["file"]["error"] . "<br>";
                } else {
                    if ($extension == "csv") {
                        $storagefile2 = DIR_DOWNLOAD . "uploaded_units_file.csv";
						
						echo '<br> File Name :'.$storagefile2.'<br>';
                        if(move_uploaded_file($_FILES["file"]["tmp_name"], $storagefile2)){
							echo '<br>File uploaded<br>';
						}

						$file_import = fopen($storagefile2, "r");
						
						$this->model_feed_ie_sorting->UpdateSortingData($file_import);
                        fclose($file_import);
						$msg = "&success=Success";
                    }
                }
            } else {
                $msg  ="&warning=Invalid file";
            }
        } else {
            $msg = "&warning=browse a file";
        }
		
		$this->response->redirect($this->url->link('feed/ie_sorting', 'token=' . $this->session->data['token'].$msg, 'SSL'));
		
    }
    public function export_sorting_csv(){
        $this->load->model('feed/ie_sorting');
        $list_data = $this->model_feed_ie_sorting->getUnitConversionData();//contains the data
        $headers = array('option_value_id', 'Name', 'sort_order');
        $this->processData($list_data,$headers);
    }
}
?>