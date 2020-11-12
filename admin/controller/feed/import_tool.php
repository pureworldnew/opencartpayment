<?php

class ControllerFeedImportTool extends Controller {

    private $error = array();

    public function index() {
        $this->language->load('feed/import_tool');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('import_tool', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->load->model('feed/ie_tool');

        $data['heading_title'] = $this->language->get('heading_title');
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
            'href' => $this->url->link('feed/import_tool', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['action'] = $this->url->link('feed/import_tool', 'token=' . $this->session->data['token'], 'SSL');
        $data['action_import'] = $this->url->link('feed/import_tool/import', 'token=' . $this->session->data['token'], 'SSL');
        $data['action_import_units'] = $this->url->link('feed/import_tool/importunits', 'token=' . $this->session->data['token'], 'SSL');
        $data['action_import_attribute'] = $this->url->link('feed/import_tool/importattribute', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['import_tool_status'])) {
            $data['import_tool_status'] = $this->request->post['import_tool_status'];
        } else {
            $data['import_tool_status'] = $this->config->get('import_tool_status');
        }

        $data['data_feed'] = HTTP_CATALOG . 'index.php?route=feed/import_tool';


       /* $this->template = 'feed/import_tool.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        $this->response->setOutput($this->render());*/
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('feed/import_tool.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'feed/import_tool')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function import() {
        $this->load->model('feed/ie_tool');
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);
        if (!empty($_FILES['file']['name'])) {
        if (($_FILES["file"]["type"] == "text/comma-separated-values")|| ($_FILES["file"]["type"] == "application/csv")) {
                if ($_FILES["file"]["error"] > 0) {
                    echo "Error: " . $_FILES["file"]["error"] . "<br>";
                } else {
                    if ($extension == "csv") {
                        $storagefile = DIR_DOWNLOAD . "uploaded_file.csv";
						echo $storagefile;
						
                        if(move_uploaded_file($_FILES["file"]["tmp_name"], $storagefile)){
								echo '<br>File uploaded<br>';	
						}
						
						if(file_exists($storagefile)){
							echo '<br>File Exisit</br>';
						}
                        $file_import = fopen($storagefile, "r");
                        
						$this->model_feed_ie_tool->saveData($file_import);
                        fclose($file_import);
                        unlink($storagefile);
						echo '<br> File Close</br>';
						
                        $this->model_feed_ie_tool->updatationData();
						echo '<br> Update data</br>';
                        $this->model_feed_ie_tool->dropTempTable();
						echo '<br> Drop table</br>';
                        echo "Success";
                    }
                }
            } else {
                echo "Invalid file";
            }
        } else {
            echo "browse a file";
        }
    }

    public function importunits() {
        $this->load->model('feed/ie_tool');
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);
        if (!empty($_FILES['file']['name'])) {
           
            if (($_FILES["file"]["type"] == "application/vnd.ms-excel")||($_FILES["file"]["type"] == "text/comma-separated-values")||($_FILES["file"]["type"] == "text/csv")|| ($_FILES["file"]["type"] == "application/csv")) {
                if ($_FILES["file"]["error"] > 0) {
                    echo "Error: " . $_FILES["file"]["error"] . "<br>";
                } else {
                    if ($extension == "csv") {
                        $this->backupDatabase();
						echo '<br> Taking backup .....</br>';
                        $storagefile2 = DIR_DOWNLOAD . "uploaded_units_file.csv";
						
						echo '<br> File Name :'.$storagefile2.'<br>';
                        if(move_uploaded_file($_FILES["file"]["tmp_name"], $storagefile2)){
							echo '<br>File uploaded<br>';
						}

						$file_import = fopen($storagefile2, "r");
						
						if(file_exists($storagefile2)){
							echo '<br>File Exisit</br>';
						}
                        
						$this->model_feed_ie_tool->saveUnitsData($file_import);
                        fclose($file_import);
						echo '<br> File Close</br>';
                        unlink($storagefile2);
                        $this->model_feed_ie_tool->updatationUnitsData();
						echo '<br> Update data</br>';
                        $this->model_feed_ie_tool->dropUnitsTempTable();
						echo '<br> Drop table</br>';
                        echo "Success";
                    }
                }
            } else {
                echo "Invalid file";
            }
        } else {
            echo "browse a file";
        }
    }

    public function importattribute() {
        $this->load->model('feed/ie_tool');
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);
        if (!empty($_FILES['file']['name'])) {
            if (($_FILES["file"]["type"] == "text/comma-separated-values")) {
                if ($_FILES["file"]["error"] > 0) {
                    echo "Error: " . $_FILES["file"]["error"] . "<br>";
                } else {
                    if ($extension == "csv") {
                        $storagefile3 = DIR_DOWNLOAD . "uploaded_attribute_file.csv";
                        move_uploaded_file($_FILES["file"]["tmp_name"], $storagefile3);
                        $file_import = fopen($storagefile3, "r");
                        $this->model_feed_ie_tool->saveAttributeData($file_import);
                        fclose($file_import);
                        unlink($storagefile3);
                        $this->model_feed_ie_tool->updatationAttributeData();
                        $this->model_feed_ie_tool->dropAttributeTempTable();
                        echo "Success";
                    }
                }
            } else {
                echo "Invalid file";
            }
        } else {
            echo "browse a file";
        }
    }
    private function backupDatabase(){
        $file = 'backup_unit_conversion'.date('m-d-Y') . '_' . uniqid() . rand(0, 4500). '.sql';
        $table = 'oc_unit_conversion_product oc_unit_conversion oc_unit_conversion_value';
        exec("mysqldump --user=".DB_USERNAME." --password=".DB_PASSWORD." --host=".DB_HOSTNAME." ". DB_DATABASE." ".$table." > ".DIR_DOWNLOAD."".$file."");
    }
}

?>