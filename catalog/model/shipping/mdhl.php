<?php
/*
	MINTEGRA DHL Express EXTENSION
	COPYRIGHT: MINTEGRA 2014
	DISTRIBUTED BY: MINTEGRA.COM / MAYOO.COM
	SUPPORT: support@mintegra.com 
*/
class ModelShippingMdhl extends Model {
	function getQuote($address) {
		include(DIR_SYSTEM . 'library/mintegra/api/dhlquote.php');
		$this->load->language('shipping/mdhl');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('mdhl_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
	
		if (!$this->config->get('mdhl_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
			
		$mdhl_enabled = false;
		if ($this->config->get('mdhl_status')) {
      		
        		$mdhl_enabled = true;
      	
		} else { 
				$mdhl_enabled = false;
		}


		$method_data = array();
		$quote_data = array();
		$shipping_quote = array();
		$error_msg = '';
		//$this->log->write(print_r($status,TRUE));
		//$this->log->write(print_r($mdhl_enabled,TRUE));
		if ($status && $mdhl_enabled) {
		
			//Fedex Key
			$mdhl_site_id = $this->config->get('mdhl_site_id');
			
			//Fedex Password
			$mdhl_password = $this->config->get('mdhl_password');
			
			$mdhl_from_zip = $this->config->get('mdhl_zip');
			
			$mdhl_from_country = $this->config->get('mdhl_country');
			
			$shipping_address = $address;
			
			$mdhl_to_zip = $shipping_address['postcode'];
			
			$mdhl_to_country = $shipping_address['iso_code_2'];
			

			
			//Products info
			if ($this->cart->hasProducts()) {
					$products = $this->cart->getProducts();
					$sudo_wunits = $this->weight->format('1',$this->config->get('config_weight_class_id'));
					$sunits = strtoupper(substr($sudo_wunits,-2)) ;	
					$sudo_lunits =  $this->length->format('1',$this->config->get('config_length_class_id'));
					$slength = strtoupper(substr($sudo_lunits,-2)); 
					$mt = new dhlquote($mdhl_site_id,$mdhl_password,$this->log,$this->config->get('mdhl_mode'),$sunits,$slength);
					$response = $mt->mt_get_quote($products,$mdhl_from_zip,$mdhl_from_country,$mdhl_to_zip,$mdhl_to_country);
					//$this->log->write(print_r($response,TRUE));
					$xml = simplexml_load_string($response);
					
					//$this->log->write(print_r($xml,TRUE));
					
					 if( $xml && $xml->GetQuoteResponse && $xml->GetQuoteResponse->BkgDetails && $xml->GetQuoteResponse->BkgDetails->QtdShp ){
						if( count( $xml->GetQuoteResponse->BkgDetails->QtdShp ) > 1 ){
							for( $i=0; $i<count( $xml->GetQuoteResponse->BkgDetails->QtdShp ); $i++ ){
								
								if ( (int)$xml->GetQuoteResponse->BkgDetails->QtdShp[$i]->ShippingCharge > 0 ){
									$quote_data[ (string)$xml->GetQuoteResponse->BkgDetails->QtdShp[$i]->ProductShortName ] = array(
										'code'          => 'mdhl.'. (string)$xml->GetQuoteResponse->BkgDetails->QtdShp[$i]->ProductShortName,
										'title'        => (string)$xml->GetQuoteResponse->BkgDetails->QtdShp[$i]->ProductShortName,
										'cost'         => $this->currency->convert( (string)$xml->GetQuoteResponse->BkgDetails->QtdShp[$i]->ShippingCharge, 'USD', $this->currency->getCode()),
										'tax_class_id' => 0,
										'text'         => $this->currency->format($this->currency->convert( (string)$xml->GetQuoteResponse->BkgDetails->QtdShp[$i]->ShippingCharge, 'USD', $this->currency->getCode()))
										); 
								}
								
								//$rates[] = array( 'rate_code' => $this->get_product_code( $xml->GetQuoteResponse->BkgDetails->QtdShp[$i]->ProductShortName ),
								//'rate' => (string) $xml->GetQuoteResponse->BkgDetails->QtdShp[$i]->ShippingCharge
								//);
							}
						}else{
								if ( (int)$xml->GetQuoteResponse->BkgDetails->QtdShp->WeightCharge > 0 ){
									$quote_data[(string)$xml->GetQuoteResponse->BkgDetails->QtdShp->ProductShortName] = array(
									'code'          => 'mdhl.'. (string)$xml->GetQuoteResponse->BkgDetails->QtdShp->ProductShortName,
									'title'        => (string)$xml->GetQuoteResponse->BkgDetails->QtdShp->ProductShortName,
									'cost'         => $this->currency->convert( (string)$xml->GetQuoteResponse->BkgDetails->QtdShp->WeightCharge, 'USD', $this->currency->getCode()),
									'tax_class_id' => 0,
									'text'         => $this->currency->format($this->currency->convert( (string)$xml->GetQuoteResponse->BkgDetails->QtdShp->WeightCharge, 'USD', $this->currency->getCode()))
									); 
								}	
							//$rates[] = array( 'rate_code' => $this->get_product_code( $xml->GetQuoteResponse->BkgDetails->QtdShp->ProductShortName ),
							//'rate' => (string) $xml->GetQuoteResponse->BkgDetails->QtdShp->WeightCharge
							//);
						}
					} 
					//printSuccess($client, $response);
					/*if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR' ){  	
					
						
					}else{
						
						$error_msg ="DHL Rates not available";	
					}*/
				

			
			}
			
			
			/*$quote_data['FEDEX_FIRST_OVERNIGHT'] = array(
								'code'          => 'mdhl.'.'FEDEX_FIRST_OVERNIGHT',
								'title'        => 'Fedex First Overnight',
								'cost'         => $this->currency->convert($first_overnight_quote, 'USD', $this->currency->getCode()),
								'tax_class_id' => 0,
								'text'         => $this->currency->format($this->currency->convert($first_overnight_quote, 'USD', $this->currency->getCode()))
								); */
		

		}
		//$this->log->write(print_r($quote_data,TRUE));
		$title = $this->language->get('text_title');
		$method_data = array(
				'code'        => 'mdhl',
				'title'      => $title,				
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('mdhl_sort_order'),
				'error'      => $error_msg
			);
		return $method_data;
		
	}
	
}
?>