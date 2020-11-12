<?php
class ModelModuleAdvProfitModule extends Model {
	public function getProductCostPriceQuantity($start, $limit, $categoryfilter, $manufacturerfilter, $supplierfilter, $statusfilter, $roundingfilter, $sort) {
		global $keepsort;
		global $keepdir;
		$total = 0;
		$product_data = array();

		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 20;
		}
		
		$sql = "SELECT p.product_id, p.quantity, pd.name, p.model, p.sku, p.price, p.subtract, p.cost, p.cost_amount, p.cost_percentage, p.cost_additional, p.costing_method, p.supplier_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (pd.product_id = p.product_id)";

		if ($categoryfilter != 0) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category ptc ON (p.product_id = ptc.product_id)";
		}
			
			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($categoryfilter)) {
			$sql .= " AND (";
			$implode = array();
			foreach ($categoryfilter as $categories_filter) {
				$implode[] = "ptc.category_id = '" . (int)$categories_filter . "'";
			}

			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= ")";
		}

		if (!empty($manufacturerfilter)) {
			$sql .= " AND (";
			$implode = array();
			foreach ($manufacturerfilter as $manufacturers_filter) {
				$implode[] = "p.manufacturer_id = '" . (int)$manufacturers_filter . "'";
			}

			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= ")";
		}

		if (!empty($supplierfilter)) {
			$sql .= " AND (";
			$implode = array();
			foreach ($supplierfilter as $suppliers_filter) {
				$implode[] = "p.supplier_id = '" . (int)$suppliers_filter . "'";
			}

			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= ")";
		}
		
		if (!empty($statusfilter)) {
			$sql .= " AND (";
			$implode = array();
			foreach ($statusfilter as $statuses_filter) {
				$implode[] = "p.status = '" . (int)$statuses_filter . "'";
			}

			if ($implode) {
				$sql .= implode(" OR ", $implode) . "";
			}
			$sql .= ")";
		}
		
		$sql .= " GROUP BY p.product_id ORDER BY p.quantity ASC";
		
		$query = $this->db->query($sql);
		
		foreach ($query->rows as $result) {
			$product_id = $result['product_id'];
			$product_name = $result['name'];

			$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");
		
			foreach ($product_option_query->rows as $product_option) {
				if ($product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'select' || $product_option['type'] == 'image' || $product_option['type'] == 'colour' || $product_option['type'] == 'size' || $product_option['type'] == 'multiple') {
					
					$product_option_value_data = array();	
					$product_option_value_query = $this->db->query("SELECT pov.product_id, pov.product_option_value_id, ovd.name AS option_value, od.name AS option_name, pov.sku, pov.quantity, pov.costing_method, pov.cost_amount, pov.cost, pov.price, pov.subtract FROM " . DB_PREFIX . "product_option_value pov, " . DB_PREFIX . "option_value ov, " . DB_PREFIX . "option_value_description ovd, " . DB_PREFIX . "option_description od WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND pov.option_value_id = ov.option_value_id AND ov.option_value_id = ovd.option_value_id AND ovd.option_id = od.option_id AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");
					
					foreach ($product_option_value_query->rows as $product_option_value) {
						if ($product_option_value['subtract'] == 1) {
							$subtract = 'Y';
							$option_quantity = $product_option_value['quantity'];
						} else {
							$subtract = 'N';
							$option_quantity = 1;
						}

						if ($product_option_value['costing_method'] == 1) {
							$costing_method = 'AVCO';
							$cost_amount = $product_option_value['cost_amount'];
						} else {
							$costing_method = 'FXCO';
							$cost_amount = $product_option_value['cost_amount'];
						}
						
						$product_data[] = array(
							'id'				=> $product_option_value['product_id'],
							'option_id'			=> $product_option_value['product_option_value_id'],
							'name'				=> $product_name,
							'option'			=> $product_option_value['option_name'] . ': ' . $product_option_value['option_value'],
							'sku'				=> $product_option_value['sku'],
							'model'				=> $result['model'],
							'supplier_id'   	=> $result['supplier_id'],							
							'stock_quantity'	=> $option_quantity,
							'restock_quantity' 	=> '0',
							'costing_method'	=> $costing_method,
							'cost_amount'		=> $product_option_value['cost'],
							'restock_cost' 		=> $cost_amount,
							'price'				=> $product_option_value['price'],
							'cost_multiplier' 	=> '0',
							'price_multiplier' 	=> '1',
							'set_price'   		=> $product_option_value['price'],
							'profit' 			=> '0',
							'subtract'			=> $subtract,
							'comment'			=> ''
						);
					
					}
				
				}
			}
		
			if ($result['subtract'] == 1) {
				$subtract = 'Y';
				$stock_quantity = $result['quantity'];
			} else {
				$subtract = 'N';
				$stock_quantity = $result['quantity'];
				// $stock_quantity = 1;
			}

			if ($result['costing_method'] == 1) {
				$costing_method = 'AVCO';
				$cost_amount = $result['cost_amount'] + (($result['cost_percentage'] / 100)*$result['price']) + $result['cost_additional'];
			} else {
				$costing_method = 'FXCO';
				$cost_amount = $result['cost_amount'] + (($result['cost_percentage'] / 100)*$result['price']) + $result['cost_additional'];
			}
			
			$product_data[] = array(
				'id'    			=> $result['product_id'],
				'option_id' 		=> '0',
				'name'    			=> $result['name'],
				'option'			=> '',
				'sku'  				=> $result['sku'],
				'model'  			=> $result['model'],
				'supplier_id'   	=> $result['supplier_id'],				
				'stock_quantity' 	=> $stock_quantity,
				'restock_quantity' 	=> '0',		
				'costing_method' 	=> $costing_method,
				'cost_amount' 		=> $result['cost'],
				'restock_cost' 		=> $cost_amount,
				'price'   			=> $result['price'],
				'cost_multiplier' 	=> '0',
				'price_multiplier' 	=> '1',
				'set_price'   		=> $result['price'],
				'profit' 			=> '0',
				'subtract' 			=> $subtract,
				'comment'			=> ''
			);
		}
		
		$quantity = array();
		global $keepsort;
		global $keepdir;

		if ($sort == '') {
			$sort = 'name';
			$direction = SORT_ASC;
		} else {
			$direction = substr($sort,-1) == 'a' ?  SORT_ASC : SORT_DESC;
			$sort = substr($sort,0,strlen($sort)-1);
		}
		
   		foreach ($product_data as $product) {
    		$quantity[] = $product[$sort];
   		}
		
		$array_lowercase = array_map('strtolower', $quantity);
		array_multisort($array_lowercase, $direction, $product_data);
		$return_data = array_slice($product_data, ($start - 1) * $limit, $limit);
		
		return $return_data;
	}	
	
	function uploadProducts(&$reader, &$database) {
		$data = $reader->getSheet(0);
		$products = array();
		$product = array();
		$isFirstRow = TRUE;
		$i = 0;
		$k = $data->getHighestRow();
		for ($i=0; $i<$k; $i+=1) {
			$j = 1;
			if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}
			
			$productId = trim($this->getCell($data,$i,$j++));
			$optionsId = trim($this->getCell($data,$i,$j++));
			$name = $this->getCell($data,$i,$j++);
			$name = htmlentities($name, ENT_QUOTES, $this->detect_encoding($name));
			$option = $this->getCell($data,$i,$j++);
			$option = htmlentities($option, ENT_QUOTES, $this->detect_encoding($option));			
			$sku = $this->getCell($data,$i,$j++,'   ');
			$model = $this->getCell($data,$i,$j++,'   ');
			$supplier_id = $this->getCell($data,$i,$j++,'0');
			$subtract = $this->getCell($data,$i,$j++,'true');
			$stock_quantity = $this->getCell($data,$i,$j++,'0');
			$restock_quantity = $this->getCell($data,$i,$j++,'0');			
			$quantity = $this->getCell($data,$i,$j++,'0');
			$costing_method = $this->getCell($data,$i,$j++,'0');			
			$cost_amount = str_replace(",","",$this->getCell($data,$i,$j++,'0.0000'));
			$restock_cost = str_replace(",","",$this->getCell($data,$i,$j++,'0.0000'));			
			$cost = str_replace(",","",$this->getCell($data,$i,$j++,'0.0000'));			
			$price_old = str_replace(",","",$this->getCell($data,$i,$j++,'0.0000'));
			$cost_multiplier = str_replace(",","",$this->getCell($data,$i,$j++,'0.00'));
			$price_multiplier = str_replace(",","",$this->getCell($data,$i,$j++,'0.00'));			
			$set_price = str_replace(",","",$this->getCell($data,$i,$j++,'0.0000'));
			$price = str_replace(",","",$this->getCell($data,$i,$j++,'0.0000'));
			$profit = str_replace(",","",$this->getCell($data,$i,$j++,'0.0000'));
			$comment = $this->getCell($data,$i,$j++,'');
			
			$product = array();
			$product['product_id'] = $productId;
			$product['option_id'] = $optionsId;
			$product['name'] = $name;
			$product['option'] = $option;			
			$product['sku'] = $sku;
			$product['model'] = $model;	
			$product['supplier_id'] = $supplier_id;	
			$product['subtract'] = $subtract;
			$product['stock_quantity'] = $stock_quantity;	
			$product['restock_quantity'] = $restock_quantity;
			$product['quantity'] = $quantity;
			$product['costing_method'] = $costing_method;
			$product['cost_amount'] = $cost_amount;	
			$product['restock_cost'] = $restock_cost;				
			$product['cost'] = $cost;	
			$product['price_old'] = $price_old;
			$product['cost_multiplier'] = $cost_multiplier;
			$product['price_multiplier'] = $price_multiplier;
			$product['set_price'] = $set_price;		
			$product['price'] = $price;	
			$product['profit'] = $profit;
			$product['comment'] = $comment;
			$products[$i] = $product;
		}
		
		foreach ($products as $product) {
		   $subtract = strtoupper($product['subtract']) == 'Y' ? 1 : 0;
		   $costing_method = strtoupper($product['costing_method']) == 'AVCO' ? 1 : 0;
			
			if ($product['option_id'] != 0) { //update item at option level
				$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = " . $product['quantity'] . ", price = " . (float)$product['price'] . ", cost = " . (float)$product['cost'] . ", cost_amount = " . (float)$product['restock_cost'] . ", costing_method = " . $costing_method . ", subtract = " . $subtract . " WHERE product_option_value_id = " . $product['option_id'] . ";");
				
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_stock_history (product_option_id, product_id, option_id, option_value_id, restock_quantity, stock_quantity, supplier_id, costing_method, restock_cost, cost, price, comment, date_added) SELECT (SELECT product_option_id FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id = '" . $product['option_id'] . "'), '" . (int)$product['product_id'] . "', (SELECT option_id FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id = '" . $product['option_id'] . "'), (SELECT option_value_id FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id = '" . $product['option_id'] . "'), '" . (int)$product['restock_quantity'] . "', '" . (int)$product['quantity'] . "', '" . (int)$product['supplier_id'] . "', '" . $costing_method . "', '" . (float)$product['restock_cost'] . "', '" . (float)$product['cost'] . "', '" . (float)$product['price'] . "', '" . $product['comment'] . "', NOW() FROM DUAL WHERE NOT EXISTS (SELECT product_id, option_id, option_value_id FROM " . DB_PREFIX . "product_option_stock_history WHERE product_id = '" . (int)$product['product_id'] . "' AND option_id = (SELECT option_id FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id = '" . $product['option_id'] . "') AND option_value_id = (SELECT option_value_id FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id = '" . $product['option_id'] . "')) OR EXISTS (SELECT p1.product_id, p1.option_id, p1.option_value_id FROM " . DB_PREFIX . "product_option_stock_history AS p1 LEFT JOIN " . DB_PREFIX . "product_option_stock_history AS p2 ON p1.product_id = p2.product_id AND p1.option_id = p2.option_id AND p1.option_value_id = p2.option_value_id AND p1.date_added < p2.date_added WHERE p2.product_id IS NULL AND p2.option_id IS NULL AND p2.option_value_id IS NULL AND p1.product_id = '" . (int)$product['product_id'] . "' AND p1.option_id = (SELECT option_id FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id = '" . $product['option_id'] . "') AND p1.option_value_id = (SELECT option_value_id FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id = '" . $product['option_id'] . "') AND (p1.cost <> '" . (float)$product['cost'] . "' OR p1.price <> '" . (float)$product['price'] . "' OR p1.stock_quantity <> '" . (int)$product['quantity'] . "'));");
			} else { //update item at product level
				$this->db->query("UPDATE " . DB_PREFIX . "product SET supplier_id = " . (int)$product['supplier_id'] . ", quantity = " . (int)$product['quantity'] . ", price = " . (float)$product['price'] . ", cost = " . (float)$product['cost'] . ", cost_amount = " . (float)$product['restock_cost'] . ", cost_percentage = '0.00', cost_additional = '0.0000', costing_method = " . $costing_method . ", subtract = " . $subtract . " WHERE product_id = " . $product['product_id'] . ";");
				
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_stock_history (product_id, restock_quantity, stock_quantity, supplier_id, costing_method, restock_cost, cost, price, comment, date_added) SELECT '" . (int)$product['product_id'] . "', '" . (int)$product['restock_quantity'] . "', '" . (int)$product['quantity'] . "', '" . (int)$product['supplier_id'] . "', '" . $costing_method . "', '" . (float)$product['restock_cost'] . "', '" . (float)$product['cost'] . "', '" . (float)$product['price'] . "', '" . $product['comment'] . "', NOW() FROM DUAL WHERE NOT EXISTS (SELECT product_id FROM " . DB_PREFIX . "product_stock_history WHERE product_id = '" . (int)$product['product_id'] . "') OR EXISTS (SELECT p1.product_id FROM " . DB_PREFIX . "product_stock_history AS p1 LEFT JOIN " . DB_PREFIX . "product_stock_history AS p2 ON p1.product_id = p2.product_id AND p1.date_added < p2.date_added WHERE p2.product_id IS NULL AND p1.product_id = '" . (int)$product['product_id'] . "' AND (p1.cost <> '" . (float)$product['cost'] . "' OR p1.price <> '" . (float)$product['price'] . "' OR p1.stock_quantity <> '" . (int)$product['quantity'] . "'));");
			}
		}
		
		return TRUE;
	}
	
	function getCell(&$worksheet,$row,$col,$default_val='') {
		$col -= 1; // we use 1-based, PHPExcel uses 0-based column index
		$row += 1; // we use 0-based, PHPExcel used 1-based row index
		return ($worksheet->cellExistsByColumnAndRow($col,$row)) ? $worksheet->getCellByColumnAndRow($col,$row)->getFormattedValue() : $default_val;
	}
	
	protected function detect_encoding($str) {
		// auto detect the character encoding of a string
		return mb_detect_encoding($str, 'UTF-8,ISO-8859-15,ISO-8859-1,cp1251,KOI8-R');
	}
	
	function validateUpload(&$reader) {
		if ($reader->getSheetCount() != 1) {
			error_log(date('Y-m-d H:i:s - ', time()).$this->language->get('error_sheet_count')."\n",3,DIR_LOGS."error.txt");
			return FALSE;
		}
		
		return TRUE;
	}
	
	function clearCache() {
		$this->cache->delete('*');
	}
	
	function upload($filename) {
		// we use our own error handler
		global $config;
		global $log;
		$config = $this->config;
		$log = $this->log;
	
		$database =& $this->db;

		// use a generous enough configuration, the Import can be quite memory hungry (this needs to be improved)
		ini_set("memory_limit","256M");
		ini_set("max_execution_time",180);
		set_time_limit(60);

		// we use the PHPExcel package from http://phpexcel.codeplex.com/
		$cwd = getcwd();
		chdir(DIR_SYSTEM . 'library/PHPExcel');
		require_once('Classes/PHPExcel.php');
		chdir($cwd);

		// parse uploaded spreadsheet file
		$inputFileType = PHPExcel_IOFactory::identify($filename);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objReader->setReadDataOnly(true);
		$reader = $objReader->load($filename);

		// read the various worksheets and load them to the database
		$ok = $this->validateUpload($reader);
		if (!$ok) {
			return FALSE;
		}
		
		$this->clearCache();
		
		$ok = $this->uploadProducts($reader, $database);
		if (!$ok) {
			return FALSE;
		}
		
		return TRUE;
	}

	protected function clearSpreadsheetCache() {
		$files = glob(DIR_CACHE . 'Spreadsheet_Excel_Writer' . '*');
		
		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					@unlink($file);
					clearstatcache();
				}
			}
		}
	}
	
	public function createXLS($categoryfilter, $manufacturerfilter, $supplierfilter, $statusfilter, $roundingfilter) {
		// we use our own error handler
		global $config;
		global $log;
		$config = $this->config;
		$log = $this->log;
		set_error_handler('error_handler_for_export',E_ALL);
		register_shutdown_function('fatal_error_shutdown_handler_for_export');
	
		$cwd = getcwd();
		chdir(DIR_SYSTEM . 'library/pear');
		require_once('Spreadsheet/Excel/Writer.php');
		chdir($cwd);
		
		// Creating a workbook
		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->setTempDir(DIR_CACHE);
		$workbook->setVersion(8); // Use Excel97/2000 BIFF8 Format

		$textFormat =& $workbook->addFormat(array('NumFormat' => "@"));

		$workbook->setCustomColor(40, 230, 230, 230);
		$supplierEditFormat =& $workbook->addFormat(array('Align' => 'right', 'FgColor' => '40', 'bordercolor' => 'silver'));
		$supplierEditFormat->setBorder(1);
		$supplierEditFormat->setColor('black');
		
		$workbook->setCustomColor(41, 249, 243, 219);
		$stockFormat =& $workbook->addFormat(array('Align' => 'right'));
		$stockFormat->setColor('brown');
		$stockEditFormat =& $workbook->addFormat(array('Align' => 'right', 'FgColor' => '41', 'bordercolor' => 'silver'));
		$stockEditFormat->setBorder(1);
		$stockEditFormat->setColor('brown');		
		$stockNewFormat =& $workbook->addFormat(array('Align' => 'right', 'bold' => 1));
		$stockNewFormat->setColor('brown');			
		$stockNewFormat->setLocked();		
		$textStockFormatYN =& $workbook->addFormat(array('Align' => 'right', 'FgColor' => '41', 'bordercolor' => 'silver', 'NumFormat' => "@"));
		$textStockFormatYN->setBorder(1);
		$textStockFormatYN->setColor('brown');	
		
		$workbook->setCustomColor(43, 247, 233, 227);
		$costFormat =& $workbook->addFormat(array('Align' => 'right'));
		$costFormat->setColor('red');		
		$costFormat->setNumFormat('0.0000');
		$costEditFormat =& $workbook->addFormat(array('Align' => 'right', 'FgColor' => '43', 'bordercolor' => 'silver'));
		$costEditFormat->setBorder(1);
		$costEditFormat->setColor('red');		
		$costEditFormat->setNumFormat('0.0000');		
		$costNewFormat =& $workbook->addFormat(array('Align' => 'right', 'bold' => 1));
		$costNewFormat->setColor('red');		
		$costNewFormat->setNumFormat('0.0000');		
		$costNewFormat->setLocked();		
		$textCostFormatYN =& $workbook->addFormat(array('Align' => 'right', 'FgColor' => '43', 'bordercolor' => 'silver', 'NumFormat' => "@"));
		$textCostFormatYN->setBorder(1);		
		$textCostFormatYN->setColor('red');		

		$workbook->setCustomColor(42, 241, 249, 233);
		$priceFormat =& $workbook->addFormat(array('Align' => 'right'));
		$priceFormat->setColor('green');	
		$priceFormat->setNumFormat('0.0000');
		$priceEditFormat =& $workbook->addFormat(array('Align' => 'right', 'FgColor' => '42', 'bordercolor' => 'silver'));
		$priceEditFormat->setBorder(1);
		$priceEditFormat->setColor('green');		
		$priceEditFormat->setNumFormat('0.0000');		
		$multiplierFormat =& $workbook->addFormat(array('Align' => 'right', 'FgColor' => '42', 'bordercolor' => 'silver'));
		$multiplierFormat->setBorder(1);		
		$multiplierFormat->setColor('green');
		$priceNewFormat =& $workbook->addFormat(array('Align' => 'right', 'bold' => 1));
		$priceNewFormat->setColor('green');		
		$priceNewFormat->setNumFormat('0.0000');		
		$priceNewFormat->setLocked();	

		$profitNewFormat =& $workbook->addFormat(array('Align' => 'right', 'bold' => 1));
		$profitNewFormat->setColor('56');		
		$profitNewFormat->setNumFormat('0.0000');		
		$profitNewFormat->setLocked();
		
		$boxFormatText =& $workbook->addFormat(array('FgColor' => 'silver', 'bold' => 1, 'bordercolor' => 'gray'));
		$boxFormatText->setBorder(1);
		$boxFormatTextYN =& $workbook->addFormat(array('Align' => 'right', 'FgColor' => 'silver', 'bold' => 1, 'bordercolor' => 'gray'));
		$boxFormatTextYN->setBorder(1);
		$boxFormatNumber =& $workbook->addFormat(array('Align' => 'right', 'FgColor' => 'silver', 'bold' => 1, 'bordercolor' => 'gray'));	
		$boxFormatNumber->setBorder(1);		

		$commentFormat =& $workbook->addFormat(array('FgColor' => '22', 'bordercolor' => 'gray'));
		$commentFormat->setBorder(1);
		
		// sending HTTP headers
		$workbook->send('adv_product_stock.xls');
		
		$worksheet =& $workbook->addWorksheet('Products');
		$worksheet->setInputEncoding('UTF-8');
		$worksheet->setZoom(90);

		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,10);
		$worksheet->setColumn($j,$j++,10);
		$worksheet->setColumn($j,$j++,25);
		$worksheet->setColumn($j,$j++,18);	
		$worksheet->setColumn($j,$j++,14);
		$worksheet->setColumn($j,$j++,14);
		$worksheet->setColumn($j,$j++,13);
		$worksheet->setColumn($j,$j++,9);
		$worksheet->setColumn($j,$j++,15);	
		$worksheet->setColumn($j,$j++,16);
		$worksheet->setColumn($j,$j++,18);
		$worksheet->setColumn($j,$j++,15);		
		$worksheet->setColumn($j,$j++,13);	
		$worksheet->setColumn($j,$j++,13);	
		$worksheet->setColumn($j,$j++,13);			
		$worksheet->setColumn($j,$j++,13);
		$worksheet->setColumn($j,$j++,14);	
		$worksheet->setColumn($j,$j++,14);			
		$worksheet->setColumn($j,$j++,13);	
		$worksheet->setColumn($j,$j++,13);	
		$worksheet->setColumn($j,$j++,13);
		$worksheet->setColumn($j,$j++,25);
		
		// The product headings row
		$i = 0;
		$j = 0;	
		$worksheet->writeString($i, $j++, $this->language->get('column_prod_id'), $boxFormatNumber);
		$worksheet->writeString($i, $j++, $this->language->get('column_option_id'), $boxFormatNumber);
		$worksheet->writeString($i, $j++, $this->language->get('column_name'), $boxFormatText);
		$worksheet->writeString($i, $j++, $this->language->get('column_option'), $boxFormatText);		
		$worksheet->writeString($i, $j++, $this->language->get('column_sku'), $boxFormatText);
		$worksheet->writeString($i, $j++, $this->language->get('column_model'), $boxFormatText);
		$worksheet->writeString($i, $j++, $this->language->get('column_supplier_id'), $boxFormatNumber);
		$worksheet->writeString($i, $j++, $this->language->get('column_subtract'), $boxFormatTextYN);
		$worksheet->writeString($i, $j++, $this->language->get('column_stock_quantity'), $boxFormatNumber);
		$worksheet->writeString($i, $j++, $this->language->get('column_restock_quantity'), $boxFormatNumber);
		$worksheet->writeString($i, $j++, $this->language->get('column_new_quantity'), $boxFormatNumber);
		$worksheet->writeString($i, $j++, $this->language->get('column_costing_method'), $boxFormatTextYN);		
		$worksheet->writeString($i, $j++, $this->language->get('column_cost'), $boxFormatNumber);
		$worksheet->writeString($i, $j++, $this->language->get('column_restock_cost'), $boxFormatNumber);		
		$worksheet->writeString($i, $j++, $this->language->get('column_new_cost'), $boxFormatNumber);
		$worksheet->writeString($i, $j++, $this->language->get('column_price'), $boxFormatNumber);
		$worksheet->writeString($i, $j++, $this->language->get('column_cost_multiplier'), $boxFormatNumber);
		$worksheet->writeString($i, $j++, $this->language->get('column_price_multiplier'), $boxFormatNumber);		
		$worksheet->writeString($i, $j++, $this->language->get('column_set_price'), $boxFormatNumber);	
		$worksheet->writeString($i, $j++, $this->language->get('column_new_price'), $boxFormatNumber);	
		$worksheet->writeString($i, $j++, $this->language->get('column_profit'), $boxFormatNumber);
		$worksheet->writeString($i, $j++, $this->language->get('column_comment'), $boxFormatText);
		
		// The actual products data
		$i += 1;
		$j = 0;

		$this->load->model('module/adv_profit_module');
    	$results = $this->model_module_adv_profit_module->getProductCostPriceQuantity(1, 100000, $categoryfilter, $manufacturerfilter, $supplierfilter, $statusfilter, $roundingfilter, '');
			
			foreach ($results as $result) {	
			$excelRow = $i+1;			
				$worksheet->write($i, $j++, $result['id']);
				$worksheet->write($i, $j++, $result['option_id']);
				$worksheet->write($i, $j++, html_entity_decode($result['name']), $textFormat);
				$worksheet->write($i, $j++, html_entity_decode($result['option']), $textFormat);
				$worksheet->write($i, $j++, $result['sku'], $textFormat);
				$worksheet->write($i, $j++, $result['model'], $textFormat);
				$worksheet->write($i, $j++, $result['supplier_id'], $supplierEditFormat);
				$worksheet->write($i, $j++, $result['subtract'], $textStockFormatYN);
				$worksheet->write($i, $j++, $result['stock_quantity'], $stockFormat);					
				$worksheet->write($i, $j++, $result['restock_quantity'], $stockEditFormat);
				$worksheet->writeFormula($i, $j++, '=(I' . $excelRow . ')+(J' . $excelRow .')', $stockNewFormat);
				$worksheet->write($i, $j++, $result['costing_method'], $textCostFormatYN);
				$worksheet->write($i, $j++, $result['cost_amount'], $costFormat);
				$worksheet->write($i, $j++, $result['restock_cost'], $costEditFormat);
				$worksheet->writeFormula($i, $j++, '=IF(L' . $excelRow . '="AVCO",IF((I' . $excelRow . '*M' . $excelRow .'+J' . $excelRow .'*N' . $excelRow .') > 0,((I' . $excelRow . '*M' . $excelRow .'+J' . $excelRow .'*N' . $excelRow .')/((I' . $excelRow . ')+(J' . $excelRow .'))),N' . $excelRow . '),N' . $excelRow . ')', $costNewFormat);	
				$worksheet->write($i, $j++, $result['price'], $priceFormat);
				$worksheet->write($i, $j++, $result['cost_multiplier'], $multiplierFormat);
				$worksheet->write($i, $j++, $result['price_multiplier'], $multiplierFormat);
				$worksheet->write($i, $j++, $result['set_price'], $priceEditFormat);				
				if ($roundingfilter == 'RD10DW') {
					$worksheet->writeFormula($i, $j++, '=IF(((Q' . $excelRow . ')+(R' . $excelRow .')) > 0,ROUNDDOWN((O' . $excelRow . '*Q' . $excelRow . ')+(P' . $excelRow . '*R' . $excelRow .'), 0)-0.10,0)', $priceNewFormat);					
				} elseif ($roundingfilter == 'RD5DW') {
					$worksheet->writeFormula($i, $j++, '=IF(((Q' . $excelRow . ')+(R' . $excelRow .')) > 0,ROUNDDOWN((O' . $excelRow . '*Q' . $excelRow . ')+(P' . $excelRow . '*R' . $excelRow .'), 0)-0.05,0)', $priceNewFormat);	
				} elseif ($roundingfilter == 'RD1DW') {
					$worksheet->writeFormula($i, $j++, '=IF(((Q' . $excelRow . ')+(R' . $excelRow .')) > 0,ROUNDDOWN((O' . $excelRow . '*Q' . $excelRow . ')+(P' . $excelRow . '*R' . $excelRow .'), 0)-0.01,0)', $priceNewFormat);	
				} elseif ($roundingfilter == 'RD00DW') {
					$worksheet->writeFormula($i, $j++, '=IF(((Q' . $excelRow . ')+(R' . $excelRow .')) > 0,ROUNDDOWN((O' . $excelRow . '*Q' . $excelRow . ')+(P' . $excelRow . '*R' . $excelRow .'), 0),0)', $priceNewFormat);					
				} elseif ($roundingfilter == 'RD0DW') {
					$worksheet->writeFormula($i, $j++, '=IF(((Q' . $excelRow . ')+(R' . $excelRow .')) > 0,ROUNDDOWN((O' . $excelRow . '*Q' . $excelRow . ')+(P' . $excelRow . '*R' . $excelRow .'), 1),0)', $priceNewFormat);				
				} elseif ($roundingfilter == 'RD') {
					$worksheet->writeFormula($i, $j++, '=IF(((Q' . $excelRow . ')+(R' . $excelRow .')) > 0,(O' . $excelRow . '*Q' . $excelRow . ')+(P' . $excelRow . '*R' . $excelRow .'),0)', $priceNewFormat);
				} elseif ($roundingfilter == 'RD0UP') {
					$worksheet->writeFormula($i, $j++, '=IF(((Q' . $excelRow . ')+(R' . $excelRow .')) > 0,ROUNDDOWN((O' . $excelRow . '*Q' . $excelRow . ')+(P' . $excelRow . '*R' . $excelRow .'), 1),0)', $priceNewFormat);
				} elseif ($roundingfilter == 'RD10UP') {
					$worksheet->writeFormula($i, $j++, '=IF(((Q' . $excelRow . ')+(R' . $excelRow .')) > 0,ROUNDDOWN((O' . $excelRow . '*Q' . $excelRow . ')+(P' . $excelRow . '*R' . $excelRow .')+0.01, 0)-0.10,0)', $priceNewFormat);
				} elseif ($roundingfilter == 'RD5UP') {
					$worksheet->writeFormula($i, $j++, '=IF(((Q' . $excelRow . ')+(R' . $excelRow .')) > 0,ROUNDDOWN((O' . $excelRow . '*Q' . $excelRow . ')+(P' . $excelRow . '*R' . $excelRow .')+0.01, 0)-0.05,0)', $priceNewFormat);
				} elseif ($roundingfilter == 'RD1UP') {
					$worksheet->writeFormula($i, $j++, '=IF(((Q' . $excelRow . ')+(R' . $excelRow .')) > 0,ROUNDDOWN((O' . $excelRow . '*Q' . $excelRow . ')+(P' . $excelRow . '*R' . $excelRow .')+0.01, 0)-0.01,0)', $priceNewFormat);
				} elseif ($roundingfilter == 'RD00UP') {
					$worksheet->writeFormula($i, $j++, '=IF(((Q' . $excelRow . ')+(R' . $excelRow .')) > 0,ROUNDDOWN((O' . $excelRow . '*Q' . $excelRow . ')+(P' . $excelRow . '*R' . $excelRow .'), 0),0)', $priceNewFormat);
				} else {
					$worksheet->writeFormula($i, $j++, '=IF(((Q' . $excelRow . ')+(R' . $excelRow .')) > 0,(O' . $excelRow . '*Q' . $excelRow . ')+(P' . $excelRow . '*R' . $excelRow .'),0)', $priceNewFormat);
				}
				$worksheet->writeFormula($i, $j++, '=(T' . $excelRow . '-O' . $excelRow . ')', $profitNewFormat);
				$worksheet->write($i, $j++, html_entity_decode($result['comment']), $commentFormat);
				
				$i += 1;
				$j = 0;
			}
		
		$worksheet->freezePanes(array(1, 1, 1, 1));
		
		// Let's send the file		
		$workbook->close();
		
		// Clear the spreadsheet caches
		$this->clearSpreadsheetCache();
		exit;
	}
	
	public function getProductsCategories($data = array()) {
		$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id ORDER BY name";
		
		$query = $this->db->query($sql);

		return $query->rows;
	}	

	public function getProductsSuppliers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "supplier ORDER BY name";
		
		$query = $this->db->query($sql);

		return $query->rows;
	}	
	
	public function getOrderStatuses($data = array()) {
		$query = $this->db->query("SELECT DISTINCT os.name, os.order_status_id FROM `" . DB_PREFIX . "order_status` os WHERE os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY LCASE(os.name) ASC");
		
		return $query->rows;	
	}	
}