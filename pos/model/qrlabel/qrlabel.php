<?php
if (isset($argv)){
	@require_once('/var/www/html/gempackeddev/system/library/bgp.php');
	BGP::initOC();
	$stats = array();
	BGP::check(__FILE__,$argv,$stats);
}
class ModelQrlabelQrlabel extends Model {
	
	protected $fields_list = array();
	
	private function initFields() {
		$sql = "SELECT product_id FROM ". DB_PREFIX. "product limit 1";
		$result = $this->db->query($sql);
		if ($result->num_rows) {
			$this->load->model('catalog/product');
			$product = $this->model_catalog_product->getProduct($result->row['product_id']);
			$this->fields_list = array();
			foreach ($product as $key=>$val) {
				$this->fields_list[] = $key;
			}
		}else{
			$this->fields_list = array('model','product_id','sku','location');
		}
		return array();
	}
	
	public function getQrFields($mode = 0) {
		$this->initFields();
		if ($mode == 1) {
			return implode(",",$this->fields_list);
		}else{	
			return $this->fields_list;
		}
	}
	
	public function getProductQrFieldsRaw($product_id) {
		$query = $this->db->query("SELECT qrcode FROM " . DB_PREFIX . "product WHERE product_id ='".$product_id."'");
		if ($query->num_rows) {
			return base64_decode($query->row['qrcode']);
		}else{
			return null;
		}
	}
	
	public function getProductQrFieldsTotal($product_id) {
		$qrcode_string = "";
		$fields = $this->getProductQrFieldsRaw($product_id);
		$count = 0;
		$tokens = explode(":::",$fields);
		foreach ($tokens as $token) {
			$count++;
		}
		return $count;
	}
	
	public function getProductQrFields($product_id) {
		$fields = $this->getProductQrFieldsRaw($product_id);
		if ($fields == null) {
			return;
		}
		$tokens = explode(":::",$fields);
		$codes = array();
		foreach ($tokens as $token) {
			$token = ltrim($token,'{');
			$token = rtrim($token,'}');
			$sql = "SELECT ".$token." FROM " . DB_PREFIX . "product WHERE product_id ='".$product_id."'";
			$query = $this->db->query($sql);
			if ($query->num_rows) {
				$codes[] = $token;
			}
		}
		return $codes;
	}
	
	public function getProductQrString($product_id,$format = null) {
		$qrcode_string = "";
		$fields = "";
		if ($format != null) {
			$fields = $format;
		}else{
			$fields = $this->getProductQrFieldsRaw($product_id);
		}
		
		if ($fields == null) {
			return;
		}
		
		$tokens = explode(":::",$fields);
		$codes = array();
		foreach ($tokens as $token) {
			$token = ltrim($token,'{');
			$token = rtrim($token,'}');
			$this->load->model('catalog/product');
			$product = $this->model_catalog_product->getProduct($product_id);			
			if (isset($product[$token])) {
				$codes[] = array($token => $product[$token]);
			}
		}
		$qrcode = array();
		foreach ($codes as $code) {
			foreach ($code as $key=>$val) {
				$qrcode[] = '{'.$key.'///'.$val.'}';
			}	
		}
		$qrcode_string = implode(":::",$qrcode);
		return $qrcode_string;
	}
	
	public function parseQrFormat($product_id,$format) {
		$qrcode_string = "";
		$fields = "";
		if ($format != null) {
			$fields = $format;
		}else{
			$fields = $this->getProductQrFieldsRaw($product_id);
		}
		
		if ($fields == null) {
			return;
		}
		
		$tokens = explode(":::",$fields);
		$codes = array();
		foreach ($tokens as $token) {
			$token = ltrim($token,'{');
			$token = rtrim($token,'}');
			$this->load->model('catalog/product');
			$product = $this->model_catalog_product->getProduct($product_id);			
			if (isset($product[$token])) {
				$codes[] = array($token => $product[$token]);
			}
		}
		$qrcode = array();
		foreach ($codes as $code) {
			foreach ($code as $key=>$val) {
				$qrcode[] = $val;
			}	
		}
		$qrcode_string = implode(":::",$qrcode);
		return $qrcode_string;
	}
	
	public function generateQRCode($product_id) {
		require_once(DIR_SYSTEM.'/library/'."phpqrcode.php");
		$TEMP_DIR = DIR_IMAGE;
		$filename = $TEMP_DIR.$product_id.'.png';
		$qrcode_string = "";
		$qrcode_string = $this->getProductQrString($product_id);
		QRcode::png($qrcode_string, $filename, 'L', 4, 2);
		$file = HTTP_CATALOG.'image/'.basename($filename);
		return $file;
	}
	
	public function generateQRCodes($data = array(),$sets = array()) {
		require_once(DIR_SYSTEM.'/library/'."phpqrcode.php");
		$dir = 'qrcodes/qrcodes-'.date("Y-m-d-H:i")."/";
		$TEMP_DIR = DIR_IMAGE.$dir;
		$files = array();
		if (!file_exists($TEMP_DIR)) {
			mkdir($TEMP_DIR, 0777, true);
		}
		
		$files['baseDir'] = $TEMP_DIR;
		$files['qrFiles'] = array();
		
		foreach ($data as $product){
			$filename = $TEMP_DIR.$product['model'].'.png';
			$qrcode_string = "";
			$qrcode_string = $this->getProductQrString($product['product_id'],$product['bulk_format']);
			QRcode::png($qrcode_string, $filename, 'L', 4, 2);
			$files['qrFiles'][] = $filename;
		}
		return $files;
	}
	
	public function generateZip($base_dir,$files,$zip_name) {
		$zip = new ZipArchive;
		$zpath = $base_dir.$zip_name;
		if ($zip->open($zpath, ZipArchive::CREATE) === TRUE) {
			foreach ($files as $file) {
				$zip->addFile($file,basename($file));
			}
			$zip->close();
			return $zpath;
		}else{
			echo 'Error Creating Zip file';
		}
	}
	
	public function generateReport($filters = array(),$sets = array(),$custom_data = array()) {
		ini_set('max_execution_time', 300);
		include DIR_SYSTEM.'/library/product_labels'.'/tcpdf.php';
		$this->load->model('catalog/product');
		$products = array();
		if (!empty($custom_data)) {
			$products = $custom_data;
		}else{
			$products = $this->model_catalog_product->getProducts($filters);
		}
		//print_r($products);
		//return;
		if (!empty($products)) {
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('Gempack');
			$pdf->SetTitle('Product QR Codes');
			$pdf->SetSubject('QR CODES');
			$pdf->SetKeywords('QR,GEMPACK');
			
			// set default header data
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' QR CODES FOR PRODUCTS', PDF_HEADER_STRING);

			// set header and footer fonts
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			// set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			$pdf->SetFont('helvetica', '', 11);
			$pdf->AddPage();
			
			$txt = "NO.\n";
			//$pdf->MultiCell(70, 50, $txt, 0, 'J', false, 1, 125, 30, true, 0, false, true, 0, 'T', false);
			$pdf->SetFont('helvetica', '', 10);
			
			// set style for barcode
			$style = array(
				'border' => 2,
				'vpadding' => 'auto',
				'hpadding' => 'auto',
				'fgcolor' => array(0,0,0),
				'bgcolor' => false, 
				'module_width' => 1, // width of a single module in points
				'module_height' => 1 // height of a single module in points
			);
			$starty = 30;
			$startx = 20;
			$addpage = false;
			$_1cols = (isset($sets['filter_cpp']) && $sets['filter_cpp'] == 1)?true:false;
			$_2cols = (isset($sets['filter_cpp']) && $sets['filter_cpp'] == 2)?true:false;
			$_3cols = (isset($sets['filter_cpp']) && $sets['filter_cpp'] == 3)?true:false;
			foreach($products as $product) {
				$text = (isset($sets['filter_text']) && !empty($sets['filter_text']))?$this->parseQrFormat($product['product_id'],$sets['filter_text']):$product['model'];
				$custom = isset($sets['filter_custom'])?$sets['filter_custom']:null;
				$code = $this->getProductQrString($product['product_id'],$custom);
				
				if (isset($product['bulk_format'])) {
					$custom = $product['bulk_format'];
					$code = $this->getProductQrString($product['product_id'],$custom);
				}
				
				if (empty($code)) {
					if (isset($sets['default_format'])) {
						$code = $sets['default_format'];
					}else{
						$code = $product['model'];
					}
				}
				if ($starty >= 270) {
					$pdf->AddPage();
					$starty = 30;
					$addpage = true;
				}
				
				$pdf->write2DBarcode($code, 'QRCODE,Q', $startx, $starty, 50, 50, $style, 'N');
				$pdf->Text($startx, $starty - 5, $text);
				
				if ($_1cols) {
					$starty += 60;
				}
				
				if ($_2cols) {
					if ($startx >= 100){
						$startx = 20;
						$starty += 60;
					}else{
						$startx += 100;
					}
				}
				
				if ($_3cols) {
					if ($startx >= 120){
						$startx = 20;
						$starty += 60;
					}else{
						$startx += 60;
					}
				}
			}
			
			$dir = DIR_CACHE. 'qrcodes/';
			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}
			
			if (isset($sets['gen_name'])) {
				$file = $dir.'qrcodes'.$sets['gen_name'].'.pdf';
			}else{
				$file = $dir.'qrcodes.pdf';
			}
			//ob_end_clean();
			if (isset($sets['download']) && $sets['download']){
				$pdf->Output($file, 'F');
				return $file;
			}else{
				$pdf->Output($file, 'FI');
			}
		}
	}
	
	public function getDownloadURL($path) {
		$temp = DIR_APPLICATION;
		$temp = str_replace("/admin","",$temp);
		$fp = str_replace($temp,HTTPS_CATALOG,$path);
		$fp = str_replace("admin","",$fp);
		return $fp;
	}
	
	public function processImport() {
		while (1) {
			echo 'hello\n';
		}
		/*
		$f = fopen('lg',"w+");
		if ($f){
			for ($i = 0 ;; $i++) {
				fwrite($f,"$i\n");
			}
		}*/
	}
	public function impCSVFile($file,$ispdf=false) {
		$args = array();
		//$this->bgprocess->run(__FILE__,__CLASS__,"processImport",$args);
		$file_path = __FILE__;
		
		//return;
		//print_r($this->request->post);
		if ($file) {
			$log = new Log('testimp.log');
			$l = 0;
			$cl = fopen($file, "r");
			while (($data = fgetcsv($cl, 1000, ",")) !== FALSE) {
				$l++;
			}
			$l--; //for header row
			$log->write("==============================================");
			$log->write('Total Record Found for Import = '.$l);
			fclose($cl);

			$handle = fopen($file, "r");
			if (!$handle){
				echo "Can not open file";
				$log->write('Cannot Open Input File');
				die();
			}
			$i = 1;
			$pointer = 0;
			$in = array();
			$dets = array();
			$headers = array();
			$products = array();
			self::initFields();
			$fields = $this->fields_list;
			array_push($fields,"custom_text"); //for writtting purpose
			
			$this->load->model('catalog/product');
			while (($data = fgetcsv($handle, 100000, ",")) !== FALSE) {
				if ($i == 1){
					foreach ($data as $head) {
						array_push($headers,$head);
					}
					$i += 1; //skip header row
					$log->write("Searchable Fields = " . json_encode($fields));
					$log->write("Headers found = " . json_encode($headers));
					continue;
				}
			if (count($data) > 1) {
				$product = array();
				$_model = '';
				$_format= '{model}'; //default is model
				$_text  = '';
				$log->write("------------------------------------------");
				for ($j = 0 ; $j < count($data) ; $j++) {
					$_name = isset($headers[$j])?$headers[$j]:false;
					$_data = isset($data[$j])?$data[$j]:false;
					$_hcol = in_array($_name,$fields)?true:false;
					
					switch($_name){
						case "model":
							$_model  = $data[$j];
							break;
						case "qrcode":
							$_format = $data[$j];
							break;
						case "custom_text":
							$_text = $data[$j];
							break;
					}
					
					if ($_name && $_data && $_hcol){
						$log->write("writing for col=$_name,value=$_data,model = $_model");
					}else{
						$log->write("Error for col = $_name,value=$_data - Not exists");
					}
				}
				$_productid = $this->model_catalog_product->getProductIdByModel($_model);
				$product = $this->model_catalog_product->getProduct($_productid);
				if (!empty($product)){
					$product['bulk_format'] = $_format;
					$product['custom_text'] = $_text;
					$log->write("Get data for model = $_model");
					$log->write(json_encode($product));
					$products[] = $product;
				}
			}
			$i += 1; //next line
		}
		
		//$log->write("________________________________________");
		//$log->write("Got data is = ".json_encode($products));
		
		$sets = array();
		$sets['filter_cpp'] = 2;
		$sets['filter_custom'] = null;
		$sets['filter_text'] = '';
		$sets['default_format'] = null;
		$sets['gen_name'] = date("Y-m-d-h-i").rand();
		$sets['download'] = true;
		
		$resp = array();
		if ($ispdf === "true") {
			$resFile = $this->generateReport(array(),$sets,$products);
			
			$resp['mode'] = 'pdf';
			$resp['code'] = 200;
			$resp['file'] = $this->getDownloadURL($resFile);
		}else{
			$list = $this->generateQRCodes($products,$sets);
			$resFile = $this->generateZip($list['baseDir'],$list['qrFiles'],"qrcodes.zip");
			
			$resp['mode'] = 'zip';
			$resp['code'] = 200;
			$resp['file'] = $this->getDownloadURL($resFile);
		}
		
		echo json_encode($resp);
		
	  } //file ends
		return;
	}
	
	
}
?>
<?php
if (isset($argv)){
	BGP::execSync();
}
?>
