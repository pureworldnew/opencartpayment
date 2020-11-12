<?php
/*
	Project: CSV Product Export
	Author : karapuz <support@ka-station.com>

	Version: 3.2 ($Revision$)
*/

/*

NOTE FOR DEVELOPERS:

This file is a part of experimental export plug-ins architecture. The csv export extension
can extend the export file with custom product-related data declared in schemes.
A sample class-scheme is available below. It is useful in extension upgrades because 
you will not need to add the same code everytime when a new update is loaded to the server.

If you are going to use this functionality please contact me at support@ka-station.com for any
questions/notes. I would be glad to get your feedback on this feature!

class ModelToolKaSchemesProductCost extends KaScheme {

	
	/*	Extend the list of fields available for customer's selection.
	
		Optional.
	
	public function extendFieldSets(&$sets) {
	
		$field  = array(
			'field'  => 'cost',
			'name'   => 'Cost',
			'descr'  => 'Product Cost',
			'model'  => &$this,
		);
	
		$sets['general'][] = $field;
		
		return true;
	}
	
	
	/* 
		Optional.
		
		The function fills in the product data array.
		
		RETURNS:			
			- true on success
			- false on error
	
	public function extendProduct($product, &$data, $columns) {
		$this->lastError = false;
		
		if (!empty($columns['general']['cost'])) {
			$data['general']['cost'] = $product['price'] * 2;
		}
		
		return true;
	}

	
	public function extendValue($product, $set, $column, $i) {
	
		$val = '';
		if ($set == 'general') {
			if ($column == 'cost') {
				$val = $product['cost'];
			}
		}
		
		return $val;
	}
	
	
	/*
		It extends/modifies the row.
	
	public function extendRow($product, $columns, &$row, $i) {
		return true;
	}
}
*/
?>