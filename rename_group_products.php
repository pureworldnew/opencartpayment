<?php
@ini_set("max_execution_time","0");
//date_default_timezone_set('America/New_York');
require(__DIR__."/config.php");
require_once(__DIR__."/system/library/db.php");
require_once(__DIR__."/system/library/db/mysqli.php");

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

//Functions

function getAllGroupProducts($db)
{
	$query = $db->query("SELECT * FROM  product_concat_temp_table WHERE updated = 0");
	return $query->rows;
}

function updateProductNames($db, $product)
{
        if( !empty( $product ) )
        {
            
			$groupbyvalue       = $product['groupbyvalue'];
			$optionvalue1       = $product['optionvalue1'];
			$optionvalue2       = $product['optionvalue2'];
			$groupedproductname = $product['groupedproductname'];
			$product_id         = $product['product_id'];

			if( !empty($groupbyvalue) && !empty($groupedproductname) )
			{
				$product_name = $groupbyvalue;
				if( !empty($optionvalue1) && strpos($groupedproductname, $optionvalue1) === false )
				{
					$product_name .= " " . $optionvalue1;
				}

				if( !empty($optionvalue2) && strpos($groupedproductname, $optionvalue2) === false )
				{
					$product_name .= " " . $optionvalue2;
				}

				$product_name .= " " . $groupedproductname;

				$db->query("UPDATE " . DB_PREFIX . "product_description SET name='" . $product_name . "' WHERE product_id='" . $product_id . "'");
				
			}

			$db->query("UPDATE product_concat_temp_table SET updated = 1 WHERE product_id='" . $product_id . "'");
            
        }
}



$products = getAllGroupProducts($db);
foreach($products as $product)
{
	updateProductNames($db, $product);
}

echo 'All products updated Successfully';