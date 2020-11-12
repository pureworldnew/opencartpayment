<?php
/**
 * Name: Upload 
 * Description: 
 */
class ControllerProductConcatUpload extends Controller
{
    private $backup_status;
    public $error = array('text' => '', 'type' => '');
    public $csvFile='';
    public $columns=array(
        'id',
        'name',
        'sku',
        'product_id', 
        'groupindicator', 
        'groupindicator_id',
        'groupbyname','groupbyvalue','groupbysortorder',
        'optionname1','optionvalue1','optionsort1',
        'optionname2','optionvalue2','optionsort2',
        'optionname3','optionvalue3','optionsort3',
        'optionname4','optionvalue4','optionsort4',
        'optionname5','optionvalue5','optionsort5',
        'optionname6','optionvalue6','optionsort6',
        'optionname7','optionvalue7','optionsort7',
        'optionname8','optionvalue8','optionsort8',
        'optionname9','optionvalue9','optionsort9',
        'optionname10','optionvalue10','optionsort10',
        'optionname11','optionvalue11','optionsort11',
        'groupedproductname',
    );
    public $columnsD=array(
        'Id',
        'Name',
        'Sku',
        'Product Id',
        'Group indicator',
        'Group indicator Id',
        'Group by (name)','Group by value','Group by sortorder',
        'Option Name 1','Option Value 1','Option Sort 1',
        'Option Name 2','Option Value 2','Option Sort 2',
        'Option Name 3','Option Value 3','Option Sort 3',
        'Option Name 4','Option Value 4','Option Sort 4',
        'Option Name 5','Option Value 5','Option Sort 5',
        'Option Name 6','Option Value 6','Option Sort 6',
        'Option Name 7','Option Value 7','Option Sort 7',
        'Option Name 8','Option Value 8','Option Sort 8',
        'Option Name 9','Option Value 9','Option Sort 9',
        'Option Name 10','Option Value 10','Option Sort 10',
        'Option Name 11','Option Value 11','Option Sort 11',
        'Grouped product name',
    );
    public function index()
    {   
        $this->load->language('productconcat/productconcat');
        $this->document->setTitle($this->language->get('heading_title'));
        $data['heading_title']=$this->language->get('heading_title');
        $this->file();
        $data['error']=$this->error;
        $data['token']=$this->session->data['token'];
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('productconcat/upload', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
	
		$this->response->setOutput($this->load->view('productconcat/upload.tpl', $data));
		
        /*$this->template = 'productconcat/upload.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        $this->response->setOutput($this->render());*/
    }

    /**
     * Upload file to download folder custom name
     */
    public function file()
    {
        $data['systemColumns']=$this->columnsD;
        $ft = array('text/csv', 'application/vnd.ms-excel','text/comma-separated-values');
        if (isset($_POST['subPost']))
        {
            if (isset($_FILES['concatCsvFile']['name']) && $_FILES['concatCsvFile']['name']!='')
            { 
                $aName = $_FILES['concatCsvFile']['name'];
                $name = str_replace('.' ,'_'. time().'.',$aName);
                $destination = DIR_DOWNLOAD . $name;
                $fileType = $_FILES['concatCsvFile']['type'];
                if (in_array($fileType, $ft))
                {
                    $this->backupDatabase();
                    if (move_uploaded_file($_FILES['concatCsvFile']['tmp_name'], $destination))
                    {
                        $this->csvFile=$this->session->data['concatFile'] = $name;
                        $difference=$this->csv_to_array();
                        $this->error['columns']=$difference;
                        $this->error['text'] = "Step 1:Your CSV file uploaded successfully.";
                        $this->error['type'] = "success";
                    }
                }
                else
                {
                    $this->error['text'] = "You are trying to upload invalid file.";
                    $this->error['type'] = "warning";
                }
            }
            else
            {
                $this->error['text'] = "! Oops No file selected";
                $this->error['type'] = "warning";
            }
        }
        return $this->error;
    }
    public function csv_to_array()
    {
        $data=array();
        if (($handle = fopen(DIR_DOWNLOAD.'/'.$this->csvFile, 'r')) !== FALSE)
        {
            $data = fgetcsv($handle, 2000, ';');
            fclose($handle);
            $data=$this->_modifyColumns($data);
        }
        return $data;
    }
    private function _modifyColumns($columns)
    {
        $modified = array();
        foreach ($columns as $column)
        {
            if($column!=='')
            {
                $modified[] = strtolower(preg_replace("/[^\d\w]/i", "", $column));
            }
        }
        $difference=$this->_checkDiff($this->columns,$modified,$columns);
        return $difference; 
    }
    private function _checkDiff($system,$csvColumns,$columns)
    {
        $genDiff=array();
        $difference=array_diff($csvColumns,$system);
        if(!empty($difference))
        {
            foreach($difference as $diff)
            {
                if(!preg_match("/(optionvalue)|(optionname)/i",$diff))
                {
                    $genDiff[]=$columns[array_search($diff,$csvColumns)];
                }
            }
        }
        return $genDiff;
    }
    private function backupDatabase(){
        $file = 'backup_grouped_product'.date('m-d-Y') . '_' . uniqid() . rand(0, 4500). '.sql';
        $table = 'oc_product oc_product_description oc_product_grouped oc_product_grouped_configurable ';
        $table .= 'oc_product_grouped_discount oc_product_grouped_indicator oc_product_grouped_type ';
        $table .= 'oc_product_option oc_product_option_value oc_product_to_category oc_option oc_option_description ';
        $table .= 'oc_option_value  oc_option_value_description product_concat_temp_table oc_url_alias';
        exec("mysqldump --user=".DB_USERNAME." --password=".DB_PASSWORD." --host=".DB_HOSTNAME." ". DB_DATABASE." ".$table." > ".DIR_DOWNLOAD."".$file."");
    }
}