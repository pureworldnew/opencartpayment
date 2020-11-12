<?php
class ModelCatalogLookbook extends Model {
	public function addLookbook($data) {
		
	}

    public function addLookbookImage($data) {
	    
		$this->db->query("INSERT INTO " . DB_PREFIX . "lookbook SET image_name = '" . $data . "', status = '0',no_of_tags='0'");

		$lookbook_id = $this->db->getLastId();

		$this->cache->delete('lookbook');

		return $lookbook_id;
	}

	public function editLookbook($lookbook_id, $data) {
		$ivar = 0;
		
		$image_tag_info_array = array();
		// tags information
		$this->db->query("DELETE FROM " . DB_PREFIX . "lookbook_tags WHERE lookbook_id = '" . (int)$lookbook_id . "'");
		foreach($data['tag_title'] as $tag_title)
		{
		   $tag_image = str_replace("../../upload/image/","",$data['tag_image'][$ivar]);
		   $tag_price = $data['tag_price'][$ivar];
		   $tag_title = $data['tag_title'][$ivar];
		   $tag_link = $data['tag_link'][$ivar];
		   $tagy = $data['tagy'][$ivar];
		   $tagx = $data['tagx'][$ivar];
		   
		   //$image_tag_info_array[$tagx."_".$tagy] = array($tag_title,$tag_price,$tag_link,$tagx,$tagy,$tag_image);
		   $strQuery = "INSERT INTO " . DB_PREFIX . "lookbook_tags SET tag_title = '" . $tag_title . "',tag_image = '" . $tag_image . "', tag_price = '" . $tag_price . "',tag_link='$tag_link',tagy='$tagy',tagx='$tagx',lookbook_id = '" . (int)$lookbook_id . "'";
		  // echo $strQuery;
		  // exit();
		   $this->db->query($strQuery);
		   
		   $ivar++;
		}

//        $image_tag_info = serialize($image_tag_info_array); 
		// tags category information
		
		if (isset($data['lookbook_category']) && sizeof($data['lookbook_category']) > 0) {
			$categories = implode(',',$data['lookbook_category']);
			$categories = ",".$categories.",";
		}
		
		
		$this->db->query("UPDATE " . DB_PREFIX . "lookbook SET image_title = '" . $data['image_title'] . "', image_meta_desciption = '" . $data['image_meta_desciption'] . "',no_of_tags='$ivar',categories='$categories' WHERE lookbook_id = '" . (int)$lookbook_id . "'");

		$this->cache->delete('lookbook');

		
	}

	public function deleteLookbook($lookbook_id) {
		

		$this->db->query("DELETE FROM " . DB_PREFIX . "lookbook WHERE lookbook_id = '" . (int)$lookbook_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "lookbook_tags WHERE lookbook_id = '" . (int)$lookbook_id . "'");
		
		$this->cache->delete('lookbook');

		
	}
	
	public function changeStatusLookbook($lookbook_id,$status){
	
	   

		$this->db->query("UPDATE " . DB_PREFIX . "lookbook set status='$status' WHERE lookbook_id = '" . (int)$lookbook_id . "'");
		
		$this->cache->delete('lookbook');

		
		
	}

	public function getLookbook($lookbook_id) {
		
		$sqlQuery = "SELECT * FROM " . DB_PREFIX . "lookbook WHERE lookbook_id = '" . (int)$lookbook_id . "'";
		$query = $this->db->query($sqlQuery);
		return $query->row;
		
	}
	public function getLookbookTags($lookbook_id) {
		
		$sqlQuery = "SELECT " . DB_PREFIX . "lookbook_tags.* FROM " . DB_PREFIX . "lookbook INNER JOIN " . DB_PREFIX . "lookbook_tags ON " . DB_PREFIX . "lookbook.lookbook_id=" . DB_PREFIX . "lookbook_tags.lookbook_id WHERE " . DB_PREFIX . "lookbook.lookbook_id = '" . (int)$lookbook_id . "'";
		/*echo $sqlQuery;
		exit();*/
		$query = $this->db->query($sqlQuery);
		$ivar = 0;
		$image_tag_info_array=array();
		foreach($query->rows as $rowTag)
		{
			   $tag_image = $rowTag['tag_image'];
			    $tag_title = $rowTag['tag_title'];
			   $tag_price = $rowTag['tag_price'];
			   $tag_link = $rowTag['tag_link'];
			   $tagy = $rowTag['tagy'];
			   $tagx = $rowTag['tagx'];	   
			   $image_tag_info_array[$tagx."_".$tagy] = array($tag_title,$tag_price,$tag_link,$tagx,$tagy,$tag_image);
			   $ivar++;
		}
		return $image_tag_info_array;
		
	}

	public function getLookbooks($data = array()) {
			$sql = "SELECT * FROM " . DB_PREFIX . "lookbook ";
            $sql .= " ORDER BY lookbook_id desc" ;
			//print_r($data);

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);

			return $query->rows;
		
	}


	public function getTotalLookbooks() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "lookbook");

		return $query->row['total'];
	}
	
	public function getLookbookCategories($product_id) {
		
		$product_category_data = array();
		
		$query = $this->db->query("SELECT categories FROM " . DB_PREFIX . "lookbook WHERE lookbook_id = '" . (int)$product_id . "'");
		$rowres = $query->rows;
	//	print_r($rowres);
		$categories = $rowres[0]['categories'];
		$categories = trim($categories,',');
		$lookbook_category_data = explode(',',$categories);
//print_r($lookbook_category_data);
//exit();
		return $lookbook_category_data;
	}

	
}