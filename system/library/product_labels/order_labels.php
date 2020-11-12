<?php

require_once(DIR_SYSTEM . 'library/product_labels/tcpdf.php');

class OrderLabels extends TCPDF {

	public function printLabel($w,$h,$x,$y,$DATA) {
		
		//echo "<pre>"; print_r($DATA); exit;

		if ($DATA['border']) {
			if(isset($DATA['printpreview'])) {
				$this->setColour("000000");
			} else {
				$this->setColour("B4B4B4");
			}

			if ($DATA['rounded']) {
				$this->RoundedRect($x,$y,$w,$h,2.5, '1111', 'DF');
			} else {
				$this->Rect($x,$y,$w,$h,'DF');
			}
		}

		$this->setColour();

		if(isset($DATA['type'])) {

			foreach($DATA['type'] as $row => $type) {

				$DATA['x'][$row] = str_replace(" ","+",$DATA['x'][$row]);
				$DATA['y'][$row] = str_replace(" ","+",$DATA['y'][$row]);
				$DATA['w'][$row] = str_replace(" ","+",$DATA['w'][$row]);
				$DATA['h'][$row] = str_replace(" ","+",$DATA['h'][$row]);

				switch($type) {
					case "text":
						
						$this->setColour($DATA['color'][$row]);

						$keys_x = array('left','center','right','textw','width');
						$keys_y = array('top','center','bottom','texth','height');

						$DATA['text'][$row] = html_entity_decode($this->parseText($DATA['text'][$row],$DATA));

						$this->SetFont($DATA['ff'][$row],'',$DATA['fs'][$row]);

						$text_width	 = $this->getTextWidth($DATA['text'][$row],$w);
						$text_height = $this->getTextHeight($DATA['text'][$row],$w);

						$replace_x[0]=0;
						$replace_x[1]=($w-$text_width)/2;
						$replace_x[2]=$w-$text_width;
						$replace_x[3]=$text_width;
						$replace_x[4]=$w;

						$replace_y[0]=0;
						$replace_y[1]=($h-$text_height)/2;
						$replace_y[2]=$h-$text_height;
						$replace_y[3]=$text_height;
						$replace_y[4]=$h;

						eval("\$xpos = \$x+".str_ireplace($keys_x,$replace_x,$DATA['x'][$row]).";");
						eval("\$ypos = \$y+".str_ireplace($keys_y,$replace_y,$DATA['y'][$row]).";");

						if ($DATA['fr'][$row]!=0) {
							$this->StartTransform();
							$this->Rotate($DATA['fr'][$row], $xpos,$ypos);
						}

						if ($this->countTextLines($DATA['text'][$row]) > 1) {
							$this->Multicell($text_width,$text_height,$DATA['text'][$row],0,'L',false,1,$xpos,$ypos,true,1,false,false,$text_height,'T',true);
						} else {
							$this->Text($xpos,$ypos,$DATA['text'][$row],false,false,true);
						}

						if ($DATA['fr'][$row]!=0) {
							$this->StopTransform();
						}

					break;
					case "rect":

						$this->setColour($DATA['color'][$row],$DATA['fill'][$row]);

						$keys_x = array('left','center','right','width');
						$keys_y = array('top','center','bottom','height');

						$replace_x[0]=0;
						$replace_x[1]=($w-$DATA['w'][$row])/2;
						$replace_x[2]=$w-$DATA['w'][$row];
						$replace_x[3]=$w;

						$replace_y[0]=0;
						$replace_y[1]=($h-$DATA['h'][$row])/2;
						$replace_y[2]=$h-$DATA['h'][$row];
						$replace_y[3]=$h;

						// Waqar Work
						$rep_x = str_ireplace($keys_x,$replace_x,$DATA['x'][$row]);
						$rep_y = str_ireplace($keys_y,$replace_y,$DATA['y'][$row]);
						$rep_w = str_ireplace("width",$w,$DATA['w'][$row]);
						$rep_h = str_ireplace("height",$h,$DATA['h'][$row]);
						
 						eval("\$xpos  = \"$x + $rep_x\";");
						eval("\$ypos  = \"$y + $rep_y\";");
						eval("\$rectw = \"$rep_w\";");
						eval("\$recth = \"$rep_h\";");
						// Waqar Work End

						$this->Rect($xpos,$ypos,$rectw,$recth,'DF');

					break;
					case "img":
						
						if (strpos($DATA['img'][$row],'image}') !== false) {
							$img = DIR_IMAGE.$this->parseText($DATA['img'][$row],$DATA);
						} else {
							$DATA['img'][$row] = $this->parseText($DATA['img'][$row],$DATA);
							$img = str_replace("//","/", DIR_IMAGE.parse_url($DATA['img'][$row], PHP_URL_PATH));
						}

						if (empty($DATA['w'][$row])) {
							$imgw=$w;
						} else {
							$imgw=$DATA['w'][$row];
						}
						if (empty($DATA['h'][$row]) && empty($DATA['w'][$row])) {
							$imgh=$h;
						} else {
							$imgh=$DATA['h'][$row];
						}


						$keys_x = array('left','center','right','width');
						$keys_y = array('top','center','bottom','height');

						$replace_x[0]=0;
						$replace_x[1]=($w-$imgw)/2;
						$replace_x[2]=$w-$imgw;
						$replace_x[3]=$w;

						$replace_y[0]=0;
						$replace_y[1]=($h-$imgh)/2;
						$replace_y[2]=$h-$imgh;
						$replace_y[3]=$h;

						eval("\$xpos = \$x+".str_replace($keys_x,$replace_x,$DATA['x'][$row]).";");
						eval("\$ypos = \$y+".str_replace($keys_y,$replace_y,$DATA['y'][$row]).";");

						try {
							if (!file_exists($img)) {
								throw new Exception('File does not exists');
							}else{
							switch(strtolower(pathinfo($DATA['img'][$row], PATHINFO_EXTENSION))) {
								case "svg":
								case "svgz":
									$this->ImageSVG($img, $xpos, $ypos, $imgw, $imgh);
									break;
								case "eps":
									$this->ImageEPS($img, $xpos, $ypos, $imgw, $imgh);
									break;
								default:
									$this->Image($img, $xpos, $ypos, $imgw, $imgh);
							}
						 }	
						} catch(Exception $e) {
							//$this->ImageSVG(DIR_SYSTEM.'library/product_labels/include/missing.svg', $xpos, $ypos, $imgw, $imgh);
							$this->ImageSVG(DIR_SYSTEM.'library/product_labels/include/missing.svg', $xpos, $ypos, $imgw, $imgh,'');
						}

					break;
					case 'qrcode':
					
					//echo "<pre>"; print_r($DATA); exit;
					
						$DATA['text'][$row] = $this->parseText($DATA['text'][$row],$DATA);
						
						$style = array(
						    'border' => 0,
						    'vpadding' => '0',
						    'hpadding' => '0',
						    'fgcolor' => array(0,0,0),
						    'bgcolor' => false, //array(255,255,255)
						    'module_width' => 1, // width of a single module in points
						    'module_height' => 1 // height of a single module in points
						);

						// QRCODE,L : QR-CODE Low error correction
						$this->write2DBarcode($DATA['text'][$row], 'QRCODE,L', $DATA['x'][$row], $DATA['y'][$row], $DATA['w'][$row], $DATA['h'][$row], $style, 'N');
					break;
					case 'barcode':

						$keys_x = array('left','center','right','width');
						$keys_y = array('top','center','bottom','height');

						$replace_x[0]=0;
						$replace_x[1]=($w-$DATA['w'][$row])/2;
						$replace_x[2]=$w-$DATA['w'][$row];
						$replace_x[3]=$w;

						$replace_y[0]=0;
						$replace_y[1]=($h-$DATA['h'][$row])/2;
						$replace_y[2]=$h-$DATA['h'][$row];
						$replace_y[3]=$h;

						eval("\$xpos  = \$x + ".str_replace($keys_x,$replace_x,$DATA['x'][$row]).";");
						eval("\$ypos  = \$y + ".str_replace($keys_y,$replace_y,$DATA['y'][$row]).";");
						eval("\$bcw = ".str_replace("width",$w,$DATA['w'][$row]).";");
						eval("\$bch = ".str_replace("height",$h,$DATA['h'][$row]).";");

						if (!$DATA['sample']) {
							switch(strtolower($DATA['text'][$row])) {
								case "{ean}":
									$CODE = "EAN13";
								break;
								case "{upc}":
									$CODE = "UPC-A";
								break;
								default:
									$CODE = "C128";
								break;
							}
						} else {
							$CODE = "C128";
						}

						$DATA['text'][$row] = $this->parseText($DATA['text'][$row],$DATA);

						$barcode_style = array(
							'position' => '',
							'align' => 'C',
							'stretch' => true,
							'fitwidth' => true,
							'cellfitalign' => '',
							'border' => false,
							'hpadding' => '0',
							'vpadding' => '0',
							'bgcolor' => false,
							'text' => true,
							'font' => 'helvetica',
							'fontsize' => 8,
							'stretchtext' => 4
						);
						$barcode_style['fgcolor'] = $this->hex2rgb($DATA['color'][$row]);

						$this->write1DBarcode($DATA['text'][$row], $CODE, $xpos, $ypos, $bcw, $bch, 0.4, $barcode_style, 'N');

					break;
					default:
					break;
				}
			}//// end foreach
			
		}

		$this->SetXY($x+$w,$y);
	}

	function setColour($color="000000",$fill="FFFFFF") {
		$this->SetTextColorArray($this->hex2rgb($color));
		$this->SetDrawColorArray($this->hex2rgb($color));
		$this->SetFillColorArray($this->hex2rgb($fill));
	}

	function hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);
		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
		return array($r, $g, $b);
	}

	function parseText($text, $product) {
		
		if(strpos($text,"attribute:") !== false){
			$text = str_replace("attribute:", "", $text);
		}

		$order_info = $product['order_info'];
		$order_product = $product['order_product'];
		$replace=array();
		$replace_cs=array();

		if ($product['sample'])
			return $text;
		$main_Array=array('product_id','name', 'model', 'sku', 'upc', 'ean', 'jan', 'isbn', 'mpn', 'location','image', 'manufacturer');
		
		if(isset($product['product_info']["attributes"]) && !empty($product['product_info']["attributes"])){
			$main_Array = array_merge($main_Array,$product['product_info']["attributes"]);	
		}


		foreach($main_Array as $key)
			foreach($this->findTag($text, '{'.$key.'}') as $found)
				if (!empty($product['product_info'][$key]))
					if (strpos($key,'image') !== false)
						$replace_cs['{'.$found.'}'] = $product['product_info'][$key];
					else
					$replace_cs['{'.$found.'}'] = $this->convertCase($product['product_info'][$key],$found);
				else
					$replace_cs['{'.$found.'}'] = "";
			
		$text = str_replace(array_keys($replace_cs),array_values($replace_cs),$text);

		foreach(array("date_added", "date_modified") as $key)
			if (isset($product['product_info'][$key]))
				$replace['{'.$key.'}'] = date_format(date_create($product['product_info'][$key]), 'm/d/Y');

		$replace['{price}'] = isset($product['product_info']['price'])?number_format($product['product_info']['price'], 2, '.', ','):"NO_PRICE";
		$replace['{tax}'] = isset($product['product_info']['taxes'])?number_format($product['product_info']['taxes'], 2, '.', ','):"";
		$replace['{pricetax}'] = isset($product['product_info']['price_tax'])?number_format($product['product_info']['price_tax'], 2, '.', ','):"";

		$replace['{g}']  = isset($product['product_info']['g'])?number_format($product['product_info']['g'], 0, '.', ','):"";
		$replace['{kg}'] = isset($product['product_info']['kg'])?number_format($product['product_info']['kg'], 2, '.', ','):"";
		$replace['{oz}'] = isset($product['product_info']['oz'])?number_format($product['product_info']['oz'], 0, '.', ','):"";
		$replace['{lb}'] = isset($product['product_info']['lb'])?number_format($product['product_info']['lb'], 2, '.', ','):"";

		$replace['{date}']  = date('m/d/Y');
		$replace['{ORDERDATE}'] = date('m/d/Y',strtotime($order_info['date_added']));
		$replace['{ORDERQUANTITY}'] = "Quantity: ".$order_product['quantity'];
		$replace['{QUANTITYSHIPPED}'] = $order_product['quantity_supplied'];
		$replace['{QUANTITYORDERD}'] = $order_product['quantity'];
		$replace['{UNITPRICE}'] = $order_product['unit_price'];
		$replace['{ITEMTOTAL}'] = $order_product['total'];
		$replace['{SALESTAX}'] = $order_product['tax'];
		$replace['{ORDERTOTAL}'] = $order_info['total'];
		$replace['{ORDERID}'] = "Order ID: ".$order_info['order_id'];
		$replace['{unit_singular}'] = $product['product_info']['unit_singular'];
		$replace['{unit_plural}'] = $product['product_info']['unit_plural'];
		$replace['{order_item_sort_order}'] = $order_product['order_item_sort_order'];
		$replace['{blank}'] = " ";

		 $text = preg_replace_callback ('/{o:(\w+)\}/i',function($val) use($product) {
				if(isset($product['option_names'])) {
					$option_key = array_search(strtolower($val[1]),$product['option_names']);
					if($option_key !== FALSE && isset($product['option_values'][$option_key]))
						return $this->convertCase($product['option_values'][$option_key],$val[1]);
				}
				return "";
			},
			$text
		);

		$text = preg_replace_callback ('/{text_(\w+)\}/i',function($val) use($product) {
				if(isset($product['custom_text'][$val[1]]))
					return $product['custom_text'][$val[1]];
				return "";
			},
			$text
		);

		return str_ireplace(array_keys($replace),array_values($replace),$text);
	}

	public function convertCase($text, $tag)
	{   
		return $text;
		$tag = str_replace(array('_', '1', '2'), array('', '', ''), $tag);
		if (ctype_upper($tag))
			return mb_convert_case(html_entity_decode($text), MB_CASE_UPPER, 'UTF-8');
		else if (ctype_lower($tag))
			return mb_convert_case(html_entity_decode($text), MB_CASE_LOWER, 'UTF-8');
		else
			return mb_convert_case(html_entity_decode($text), MB_CASE_TITLE, 'UTF-8');
	}

	function findTag($hs, $ndl) {
		$s = 0;
		$i = 0;
		$res=array();
		while(is_integer($i)) {
			$i = mb_stripos($hs, $ndl, $s);
			if(is_integer($i)) {
				$res[] = substr($hs,$i+1,mb_strlen($ndl)-2);
				$s = $i + mb_strlen($ndl);
			}
		}
		return array_unique($res);
	}

	function getTextWidth($text,$maxw)
	{
		$max = 0;
		$len = array();
		$i=0;

		foreach(explode("\n",$text) as $line) {
			$len[$i]['string'] = $line;
			$len[$i]['width'] = $this->GetStringWidth($line);
			if ($len[$i]['width'] > $max)
				$max = $this->GetStringWidth($line);
			$i++;
		}
		if (ceil($max) > $maxw)
			return $maxw;
		else
			return ceil($max)+0;
	}

	function getTextHeight($text,$width) {
		return $this->GetStringHeight($width,$text,false,false,0,0);
	}

	function countTextLines($text) {
		$text = str_replace("\n", "\n", $text);
		$text = str_replace("\n\n", "\n", $text);
		return count(explode("\n",$text));
	}

	public function Error($msg) {
		$this->_destroy(true);
		$phpmainver = PHP_VERSION;
		if(!empty($_GET['forcepdf']))
			return;
		if ((intval($phpmainver[0]) < 5) OR !defined('K_TCPDF_THROW_EXCEPTION_ERROR') OR !K_TCPDF_THROW_EXCEPTION_ERROR)
			die('<strong>TCPDF ERROR: </strong>'.$msg.'<BR/> PHP version: '.$phpmainver);
		else
			throw new Exception('TCPDF ERROR: '.$msg);
	}
}