<?php
class ModelCatalogLookbook extends Model {
	
	public function getLookbooks() {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "lookbook WHERE status=1 ORDER BY rand() ";
      //  $sql .= " ORDER BY lookbook_id desc" ;
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getLookbooksTags() {
		
		$sql = "SELECT " . DB_PREFIX . "lookbook_tags.*," . DB_PREFIX . "product.product_id," . DB_PREFIX . "product.tax_class_id," . DB_PREFIX . "product.price FROM " . DB_PREFIX . "lookbook INNER JOIN " . DB_PREFIX . "lookbook_tags ON " . DB_PREFIX . "lookbook.lookbook_id=" . DB_PREFIX . "lookbook_tags.lookbook_id  LEFT OUTER JOIN " . DB_PREFIX . "product ON " . DB_PREFIX . "lookbook_tags.tag_link=" . DB_PREFIX . "product.sku and " . DB_PREFIX . "product.sku <> ''  WHERE " . DB_PREFIX . "lookbook.status=1 ORDER BY rand() ";
      //  $sql .= " ORDER BY lookbook_id desc" ;
	//  echo $sql;
	  
		$query = $this->db->query($sql);
		$ivar = 0;
		$image_tag_info_array=array();
		foreach($query->rows as $rowTag)
		{
			
			if($rowTag['product_id'] > 0)
			{
			    $tag_link = $this->url->link('product/product', '' . '&product_id=' . $rowTag['product_id'] );
				
			    	 if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						
                        $tag_price = $this->currency->format($this->tax->calculate($this->cart->geProductCalculatedPrice($rowTag['product_id']),$rowTag['tax_class_id'], $this->config->get('config_tax')));
						// echo $tag_price."test<br/>";
				
                    } else {
                        $tag_price = '';
                    }
			
			}
			else
			{
				$tag_price = $rowTag['tag_price'];
			    $tag_link =  $rowTag['tag_link'];
			}
			
			//echo $tag_price."-<br/>";
			   $tag_title = $rowTag['tag_title'];
			   $tag_image = $rowTag['tag_image'];
			   $tagy = $rowTag['tagy'];
			   $tagx = $rowTag['tagx'];	   
			   $lookbook_id = $rowTag['lookbook_id'];
			   
			   $image_tag_info_array[$lookbook_id][$tagx.'_'.$tagy] = array($tag_title,$tag_price,$tag_link,$tagx,$tagy,$tag_image);
			   $ivar++;
		}
		//exit();
		return $image_tag_info_array;
	}

    public function getLookbooksByCategory($categoy) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "lookbook WHERE status=1 and categories like '%,$categoy,%' ORDER BY rand() ";
      //  $sql .= " ORDER BY lookbook_id desc" ;
		$query = $this->db->query($sql);
		return $query->rows;
	}

public function getLookbooksTagsByCategory($categoy) {
		
		
		$sql = "SELECT " . DB_PREFIX . "lookbook_tags.*," . DB_PREFIX . "product.product_id," . DB_PREFIX . "product.tax_class_id," . DB_PREFIX . "product.price FROM " . DB_PREFIX . "lookbook INNER JOIN " . DB_PREFIX . "lookbook_tags ON " . DB_PREFIX . "lookbook.lookbook_id=" . DB_PREFIX . "lookbook_tags.lookbook_id  LEFT OUTER JOIN " . DB_PREFIX . "product ON " . DB_PREFIX . "lookbook_tags.tag_link=" . DB_PREFIX . "product.sku and " . DB_PREFIX . "product.sku <> ''  WHERE " . DB_PREFIX . "lookbook.status=1 and " . DB_PREFIX . "lookbook.categories like '%,$categoy,%' ORDER BY rand() ";
      //  $sql .= " ORDER BY lookbook_id desc" ;
	//  echo $sql;
	  
		$query = $this->db->query($sql);
		$ivar = 0;
		$image_tag_info_array=array();
		foreach($query->rows as $rowTag)
		{
			
			if($rowTag['product_id'] > 0)
			{
			    $tag_link = $this->url->link('product/product', '' . '&product_id=' . $rowTag['product_id'] );
				
			    	 if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						
                        $tag_price = $this->currency->format($this->tax->calculate($this->cart->geProductCalculatedPrice($rowTag['product_id']),$rowTag['tax_class_id'], $this->config->get('config_tax')));
						// echo $tag_price."test<br/>";
				
                    } else {
                        $tag_price = '';
                    }
			
			}
			else
			{
				$tag_price = $rowTag['tag_price'];
			    $tag_link = '';
			}
			
			//echo $tag_price."-<br/>";
			   $tag_title = $rowTag['tag_title'];
			   $tag_image = $rowTag['tag_image'];
			   $tagy = $rowTag['tagy'];
			   $tagx = $rowTag['tagx'];	   
			   $lookbook_id = $rowTag['lookbook_id'];
			   $image_tag_info_array[$lookbook_id][$tagx.'_'.$tagy] = array($tag_title,$tag_price,$tag_link,$tagx,$tagy,$tag_image);
			   $ivar++;
		}
		//exit();
		return $image_tag_info_array;
		
		}

	
	
	public function getTotalLookbooks() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "lookbook");

		return $query->row['total'];
	}

	
}