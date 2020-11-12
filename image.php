<?php
	set_time_limit(0);
	ini_set('memory_limit', '1024000000000M');
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	
	include_once('config.php');
	include_once('system/helper/utf8.php');
	include_once('system/library/image.php');
	
	$db=mysqli_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
	
	
	
	change_company_id($db);
	//change_image_name($db);
	//images_not_found($db);
	function change_company_id($db){
		
		echo $sql = "SELECT * FROM `oc_customer` WHERE 1";
			
		$customers = mysqli_query($db,$sql);
		while($customer = mysqli_fetch_array($customers)){
					echo $sql = "SELECT * FROM `oc_address` WHERE customer_id = ".$customer['customer_id']." AND (company_id !='' OR company !='') limit 0,1";
			
					$addresses = mysqli_query($db,$sql);
					$address = mysqli_fetch_array($addresses);
					if(mysqli_num_rows($addresses)){
						if($address['company'] != ''){
							$company_id = $address['company'];
						}else if($address['company_id'] != ''){
							$company_id = $address['company_id'];
						}else{
							$company_id = '';
						}
					}else{
						$company_id = '';
					}
					$sql_update = "UPDATE `oc_customer` SET company_id='".$company_id."' WHERE customer_id = ".$customer['customer_id'];
					mysqli_query($db,$sql_update);
		}
			
	}
	
	function update_image_name($db){
		$sql = "SELECT * FROM `manufacturer` WHERE 1";
			
		$color_images = mysqli_query($db,$sql);
		mysqli_num_rows($color_images);
		$output = '';
		foreach($color_images as $color_image){
			$image = $color_image['image'];
			$image = str_replace('data/','manufacturer/',$color_image['image']);
			$manufacturer_id = $color_image['manufacturer_id'];
			$sql_update = "UPDATE `manufacturer` SET image='".$image."' WHERE manufacturer_id =".$manufacturer_id;
			mysqli_query($db,$sql_update);
		}
	}
	function change_image_name($db){
		
		$sql = "SELECT product_id,image FROM `product` pd WHERE 1";
			
		$color_images = mysqli_query($db,$sql);
		mysqli_num_rows($color_images);
		$output = '';
		foreach($color_images as $color_image){
			$image = str_replace('data/','product/',$color_image['image']);
			$product_id = $color_image['product_id'];
			
			if (file_exists(DIR_IMAGE . $image)) {
				$sql_update = "UPDATE `product` SET image='".$image."' WHERE product_id =".$product_id;
				mysqli_query($db,$sql_update);
			}else{
				/*$image = str_replace('product/','',$image);
				//$des_image = 'alpha_color_image/'.$image;
				$forntimage = 'alpha_color_image_low/'.$image;
				
				//$desimage = $image;
				//echo '<br>';
				 
					$source_image = 'https://www.alphabroder.com/images/alp/prodDetail/'.$image;
					$desimage = $image;
					$status = download_image($source_image,$desimage);
					
					if($status == 200){
						resize($forntimage, 260, 325);	
						
					}*/
				
				$output .= $image.",";
				$output .="\n";	
			}
			
		}
		
			$filename = "product.csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename='.$filename);
		
			echo $output;
			exit;
			
	}
	
	function images_not_found($db){
		
		$sql = "SELECT DISTINCT pd.product_id,pd.color_code,pd.color_image FROM `product_detail` pd WHERE 1";
			
		$color_images = mysqli_query($db,$sql);
		mysqli_num_rows($color_images);
		$output = '';
		foreach($color_images as $color_image){
			$image = $color_image['color_image'];
			if (file_exists(DIR_IMAGE . $image)) {
				
			}else{
				/*$image = str_replace('product/','',$image);
				//$des_image = 'alpha_color_image/'.$image;
				$forntimage = 'alpha_color_image_low/'.$image;
				
				//$desimage = $image;
				//echo '<br>';
				 
					$source_image = 'https://www.alphabroder.com/images/alp/prodDetail/'.$image;
					$desimage = $image;
					$status = download_image($source_image,$desimage);
					
					if($status == 200){
						resize($forntimage, 260, 325);	
						
					}*/
				$output .= $image.",";
				$output .="\n";	
			}
			
		}
		
		$filename = "detail_missing.csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename='.$filename);
		echo $output;
		exit;
			
		
	}
	//missingimage($db);
	//$sql = "SELECT DISTINCT pd.color_image FROM `product_detail` pd WHERE pd.`image_found` =0 LIMIT 8000 , 7000";
			
	//$color_images = mysqli_query($db,$sql);
	//mysqli_num_rows($color_images);
	
	//imagelocal($color_images,$db);
	//image($color_images);
	
	/*function image($color_images){
	
		while($colorimage = mysqli_fetch_array($color_images)){
			$image = str_replace('data/','',$colorimage['color_image']);
			$des_image = 'alpha_color_image/'.$image;

			if (!file_exists(DIR_IMAGE . $des_image)) {
				$image = str_replace('_p.jpg','_z.jpg',$image);
				$source_image = 'http://marketing.peaksystems.com/imglib/mresjpg/176999/'.$image;
				$des_image = str_replace('alpha_color_image/','',$des_image);
				$status = download_image($source_image,$des_image);
				if($status == 404){
					$image_breakdown = explode('_',$image);
					$image_new = strtoupper($image_breakdown[0]).'_'.strtoupper($image_breakdown[1]).'_'.$image_breakdown[2];
					$des_image = str_replace('alpha_color_image/','',$des_image);
					echo $source_image = 'http://marketing.peaksystems.com/imglib/mresjpg/176999/'.$image_new;
					echo '<br>';
					$status = download_image($source_image,$des_image);
					if($status == 404){
						$image_breakdown = explode('_',$image);
						$image_new = strtoupper($image_breakdown[0]).'_'.strtolower($image_breakdown[1]).'_'.$image_breakdown[2];
						$des_image = str_replace('alpha_color_image/','',$des_image);
						echo $source_image = 'http://marketing.peaksystems.com/imglib/mresjpg/176999/'.$image_new;
						echo '<br>';
						$status = download_image($source_image,$des_image);
						if($status == 404){
							echo '<br>';
							echo $image_new.'--Missing--';	
							echo '<br>';
						}
					}
				}
			}
		}
		
	}*/
			//resize($colorimage['image'],260,325);
			 //missingimage($color_images);
	function imagelocal($color_images,$db){
	
		while($colorimage = mysqli_fetch_array($color_images)){
			$image = str_replace('product/','',$colorimage['color_image']);
			$des_image = 'alpha_color_image/'.$image;
			//echo '<br>';
			
			if (file_exists(DIR_IMAGE . $des_image)) {
				/*
				$image = str_replace('_p.jpg','.jpg',$image);
				$source_image = 'http://dwaalpha.serverhtf2.info/image/data3/'.$image;
				$des_image = str_replace('alpha_color_image/','',$des_image);
				$status = download_image($source_image,$des_image);
				if($status == 404){
					
					/*if($status == 404){
					$image_breakdown = explode('_',$image);
					$image_new = strtoupper($image_breakdown[0]).'_'.strtoupper($image_breakdown[1]).'_'.$image_breakdown[2];
					$des_image = str_replace('alpha_color_image/','',$des_image);
					$source_image = 'http://dwaalpha.serverhtf2.info/image/data3/'.$image_new;
					
					$status = download_image($source_image,$des_image);
					if($status == 404){
						$image_breakdown = explode('_',$image);
						$image_new = strtoupper($image_breakdown[0]).'_'.strtolower($image_breakdown[1]).'_'.$image_breakdown[2];
						$des_image = str_replace('alpha_color_image/','',$des_image);
						$source_image = 'http://dwaalpha.serverhtf2.info/image/data3/'.$image_new;
						
						$status = download_image($source_image,$des_image);
						if($status == 404){
							echo '<br>';
							echo $image_new.'--Missing--';	
							echo '<br>';
						}else{
							echo $filename = $des_image;
							echo '<br>';
							resize($filename, 500, 625, $type = "");
						}
					}else{
						echo $filename = $des_image;
						echo '<br>';
						resize($filename, 500, 625, $type = "");
					}
				}
					$image = str_replace('_p.jpg','.jpg',$image);
					$source_image = 'http://dwaalpha.serverhtf2.info/image/Lo_res/'.$image;
					$des_image = str_replace('alpha_color_image/','',$des_image);
					$status = download_image($source_image,$des_image);
					if($status == 200){
						$des_image = 'alpha_color_image/'.$des_image;
						echo $filename = $des_image;
						echo '<br>';
						resize($filename, 260, 325, $type = "");
					}
				}else{
					$des_image = 'alpha_color_image/'.$des_image;
					echo $filename = $des_image;
					echo '<br>';
					resize($filename, 500, 625, $type = "");
				}*/
			}else{
				//'g500b_00_p.jpg'
				echo $forntimage = 'alpha_color_image_low/'.$image;
				
					$desimage = $image;
				echo '<br>';
				 if (!file_exists(DIR_IMAGE . $forntimage)) {
					$source_image = 'https://www.alphabroder.com/images/alp/prodDetail/'.$image;
					//$des_image = str_replace('alpha_color_image/','',$des_image);
					$status = download_image($source_image,$desimage);
					
					if($status == 200){
						resize($forntimage, 260, 325);	
						
					}
				}
				/*echo $filename = $des_image;
				echo '<br>';
				resize($filename, 500, 625, $type = "");*/
				//$sql_update = "update `product_detail` set image_found = 1 WHERE color_image = '".$colorimage['color_image']."'";
			
				//mysqli_query($db,$sql_update);
			}
			/*if (!file_exists(DIR_IMAGE . $des_image)) {
				image/Lo_res/
			}*/
			
		}
		
	}
	function checkimage($image2){
		if (file_exists(DIR_IMAGE . $image2)) {
			return false;
		}else{
			return true;	
		}
	}
	
	function updateman($db){
		$sql ="SELECT * FROM `manufacturer` WHERE 1;";
		$manufacturers = mysqli_query($db,$sql);
		while($manufacturer = mysqli_fetch_array($manufacturers)){
			$manufacturer_id = $manufacturer['manufacturer_id'];
			$image = $manufacturer['image'];
			$image = str_replace('data/','data/logos/',$image);
			echo $sql_update ="UPDATE `manufacturer` SET image ='".$image."' Where manufacturer_id = ".$manufacturer_id;
			echo '<br>';
			mysqli_query($db,$sql_update);
		}
	}
	function download_image($source_url, $new_file_name = "") {
		
		
	//$url = 'https://images.nga.gov/en/web_images/constable.jpg';
		$destination_path = DIR_IMAGE.'alpha_color_image_low/';
		if($new_file_name == "") {
			$tmp_filename = "";
			$tmp_filename = explode("/", $source_url);
			$tmp_filename = $tmp_filename[count($tmp_filename)-1];
			
			$destination_file = $destination_path.$tmp_filename;
			
		}
		else {
			$destination_file = $destination_path.$new_file_name;
		}
	
		if(file_exists($destination_file)) {
			return $tmp_filename;
		}else {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $source_url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.1 Safari/537.11');
			$res = curl_exec($ch);
			$rescode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
			
			curl_close($ch) ;
			
			if($rescode == 200){
			
				$fp = fopen($destination_file,'w');
				if(fwrite($fp, $res)) {
					return 200;
				}
				else {
					return 400;
				}
				fclose($fp);
			}else{
				
				return 404;	
			}
		
			
		}
	}
	
	function missingimage($db){
		$output = '';
			 $sql  = "SELECT DISTINCT p.model, pd.color_code,CONCAT(LCASE(p.model),'_',LCASE(pd.color_code),'_p.jpg') AS image";
        $sql .= " FROM `product_detail` pd";
        $sql .= " INNER JOIN `product` p ON p.product_id = pd.product_id";
 		
			$color_images = mysqli_query($db,$sql);
			$total_found = 0;
			while($colorimage = mysqli_fetch_array($color_images)){
				$image = 'data4/data3/'.$colorimage['image'];
				
				if (file_exists(DIR_IMAGE . $image)) {
					$total_found = $total_found + 1;
					
				} else {
						
						$output .= $colorimage['model'].",";
						$output .= $colorimage['color_code'].",";
						$output .= $image.",";
						$output .="\n";
				}
			
			}
			$output .=$total_found.",";
			$output .= ",";
			$output .= ",";
			$output .="\n";
			$filename = "model.csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename='.$filename);
			
			echo $output;
			exit;
	}
	
	function resize($filename, $width, $height, $type = "") {
		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
			return;
		} 
		
		$info = pathinfo($filename);
		//print_
		$extension = $info['extension'];
		
		$old_image = $filename;
		$new_image = 'product/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) .'.'. $extension;
		$new_image = strtolower($new_image);
		if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
			$path = '';
			
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				
				if (!file_exists(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}		
			}

			list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_IMAGE . $old_image);
				$image->resize($width, $height, $type);
				$image->save(DIR_IMAGE . $new_image);
			} else {
				copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
			}
		}

	}
?>