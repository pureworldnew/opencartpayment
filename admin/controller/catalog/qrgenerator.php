<?php



class ControllerCatalogQrgenerator extends Controller {
	
	
	public function index() {   
	
		$data['cancel'] = $this->url->link('catalog/qrgenerator', 'token=' . $this->session->data['token'], 'SSL');
		$data['heading_title'] = 'Qrcode Generator';
		
		$data['button_cancel']="Cancel";
	
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => 'QrGenerator',
			'href'      => $this->url->link('catalog/qrgenerator', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => ' :: '
   		);
	
		$data['action'] = $this->url->link('catalog/qrgenerator/generateQrCOde', 'token=' . $this->session->data['token'], 'SSL');
		$data['action_csv'] = $this->url->link('catalog/qrgenerator/createCSVProducts', 'token=' . $this->session->data['token'], 'SSL');
	
	        $data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('catalog/qrgenerator.tpl', $data));
	}
	
	public function generateQrCOde(){
		
		if(isset($this->request->post['qrgenerator'])){
		
		$allProducts=$this->db->query("SELECT product_id,model FROM ".DB_PREFIX ."product WHERE processed='0' ");
		if($allProducts->num_rows){
			foreach($allProducts->rows as $product){
				
			  //  $urlqr= HTTPS_CATALOG.'phpqrcode/index.php?data='.$this->db->escape($product['model']);
			   
				//$imagedata=file_get_contents($urlqr);
				
			//	preg_match('/<img src="(.*?)" \/>/s', $imagedata, $matches);
			
			
			     //set it to writable location, a place for temp generated PNG files
    //$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
				$PNG_TEMP_DIR = DIR_ROUTE .'phpqrcode/temp/';
				
				//html PNG location prefix
				$PNG_WEB_DIR = 'temp/';

				include_once(DIR_ROUTE."phpqrcode/qrlib.php");    
				
				//ofcourse we need rights to create temp dir
				if (!file_exists($PNG_TEMP_DIR))
					mkdir($PNG_TEMP_DIR);
				
				
				$filename = $PNG_TEMP_DIR.'qrimage.png';
				
				//processing form input
				//remember to sanitize user input in real-life solution !!!
				$errorCorrectionLevel = 'L';
				   

				$matrixPointSize = 4;
			   


				if (isset($product['model'])) { 

						
					// user data
					$filename = $PNG_TEMP_DIR.'qrimage_'.md5($product['model'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
					QRcode::png($product['model'], $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
					
				} else {    
				   
					QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
					
				}    
        
    //display generated file
  //  echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" />';  
			
			
			
				
				$this->db->query("UPDATE ".DB_PREFIX."product SET qrimage_path='". HTTPS_CATALOG ."phpqrcode/".$PNG_WEB_DIR.basename($filename)."',processed='1' WHERE product_id='".$product['product_id']."' AND processed='0' ");
			}
		  }
		  $this->response->redirect($this->url->link('catalog/qrgenerator', 'token=' . $this->session->data['token'] , 'SSL'));
	}
	}
	
	public function createCSVProducts(){
		
		if(isset($this->request->post['exportcsv'])){
		
		$csv_filename = "products_".date("Y-m-d_H-i",time()).".csv";
        $filecontent="";
        $filecontent="Product ID,Name,Model,QrImage Path\n";

         $list=$this->db->query("SELECT DISTINCT p.product_id,pd.name,p.model,p.qrimage_path FROM ".DB_PREFIX ."product p LEFT JOIN ".DB_PREFIX."product_description pd ON(p.product_id=pd.product_id) WHERE 1");
		foreach ($list->rows as $lines)
		  {
			  
		  
	$filecontent.="".$lines['product_id'].",".$lines['name'].",".$lines['model'].",".$lines['qrimage_path']."\n";
			
		  }

		$fd = fopen (DIR_LOGS.$csv_filename, "w");
		fputs($fd, $filecontent);
		fclose($fd);
		 header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($csv_filename).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		readfile(DIR_LOGS.$csv_filename);
		//$this->response->redirect($this->url->link('catalog/qrgenerator', 'token=' . $this->session->data['token'] , 'SSL'));
	}
		
	}
	
	
}
?>