<?php
// ***************************************************
//           Leverod Framework for Opencart
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************


class ModelSeoSeo extends LevModel {

	public function checkKeyword($keyword, $query='') {
	
		$sql = "SELECT keyword, query FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $keyword . "'";		
		
		if ($query) {

			$sql .= " AND query != '" . $query . "'";
		}
		
		$query = $this->db->query($sql);	
		
		return $query->row;
	}

}