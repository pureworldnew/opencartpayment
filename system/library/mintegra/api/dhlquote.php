<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
use DHL\Entity\AM\GetQuote;
use DHL\Datatype\GB\Piece;
use DHL\Client\Web as WebserviceClient;
require(__DIR__ . '/../init.php');
		
class dhlquote {
	private $responce;
	private $log;
	private $mode;
	private $site_id;
	private $password;
	private $weight_unit;
	private $length_unit;
	
	public function __construct($mdhl_site_id,$mdhl_password,$plog,$mode,$sunits,$slength){
		$this->log = $plog;
		$this->site_id = $mdhl_site_id;
		$this->password = $mdhl_password;
		$this->weight_unit = $sunits;
		$this->length_unit = $slength;
		
		if ($mode == 1){
			$this->mode = 'production';
		}else{
			$this->mode = 'staging';
		}
	}
	
	function mt_get_quote($products,$mdhl_from_zip,$mdhl_from_country,$mdhl_to_zip,$mdhl_to_country){

		
		$sample = new GetQuote();

		//$sample->SiteID = $dhl['id'];
		//$sample->Password = $dhl['pass'];
		$sample->SiteID = $this->site_id;
		$sample->Password = $this->password;

		$sample->MessageTime = date('c');
		//'2014-01-01T24:30:47-05:00';
		$sample->MessageReference = '1234567890123456789012345678901';

		$sample->From->CountryCode = $mdhl_from_country;
		$sample->From->Postalcode = $mdhl_from_zip;
		$sample->From->City = $mdhl_from_zip;

		$sample->BkgDetails->PaymentCountryCode = $mdhl_from_country;
		$sample->BkgDetails->Date = date("Y-m-d");
		$sample->BkgDetails->ReadyTime = 'PT10H21M';
		$sample->BkgDetails->ReadyTimeGMTOffset = '+01:00';
		$sample->BkgDetails->DimensionUnit = $this->length_unit;
		$sample->BkgDetails->WeightUnit = $this->weight_unit;
		

		$PieceID = 0;	
		$product_total = 0;
		foreach ($products as $product) {
			$quantity = $product['quantity'];
			$product_total	  = $product_total	+ $product['total'];	
			for ($pkg=0; $pkg < $quantity ; $pkg++){
				$piece = new Piece();
				$piece->PieceID = $PieceID + 1;
				//$piece->PackageTypeCode = 'BOX';
				$piece->Height = round($product['height']);
				$piece->Depth = round($product['length']);
				$piece->Width = round($product['width']);
				$piece->Weight = $product['weight'];
				$sample->BkgDetails->addPiece($piece);
			}
				
		}
		
		if( $mdhl_from_country == $mdhl_to_country )
			$is_dutiable = 'N';
		else
			$is_dutiable = 'Y';

		$sample->BkgDetails->IsDutiable = $is_dutiable;
		$sample->BkgDetails->NetworkTypeCode = 'AL';
		//$sample->BkgDetails->QtdShp->GlobalProductCode = 'P';
		//$sample->BkgDetails->QtdShp->LocalProductCode = 'P';

		$sample->To->CountryCode = $mdhl_to_country;
		$sample->To->Postalcode = $mdhl_to_zip;

		if( $is_dutiable == 'Y' ){
			$sample->Dutiable->DeclaredCurrency = 'USD';
			$sample->Dutiable->DeclaredValue = $product_total;
		}

		$start = microtime(true);
		 
		//echo $sample->toXML();
		$client = new WebserviceClient($this->mode);
		//print_r($client);
		$this->log->write($sample);
		$this->responce =  $client->call($sample);
		return print_r($this->responce,TRUE);
	}

}

?>