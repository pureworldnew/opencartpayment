<?php

/*******************************************************************************
*                                   Opencart Cache                             *
*                             Copyright : Ovidiu Fechete                       *
*                              email: ovife21@gmail.com                        *
*                Below source-code or any part of the source-code              *
*                          cannot be resold or distributed.                    *
*******************************************************************************/


class ModelCatalogNotFoundReport extends Model {
	
	public function getTotalPages() {
			
			$sql = "select count(*) as total from " . DB_PREFIX . "404s_report";
					
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		 
	}
	
	public function getPages($data) {
	
			$sql = "select a.* from " . DB_PREFIX . "404s_report a
					inner join	
					(select link, max(date) as maxdate from " . DB_PREFIX . "404s_report group by link) b on a.link = b.link and a.date = b.maxdate";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " limit " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			$query = $this->db->query($sql);						
		
			return $query->rows;
		 
	}
			
}
?>