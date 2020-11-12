<?php
class ModelExtensionHbseoHbSitemap extends Model {
	public function install(){
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "sitemap_links` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `link` text NOT NULL,
			  `freq` varchar(10) NOT NULL,
			  `priority` varchar(10) NOT NULL,
			  `store_id` int(11) NOT NULL,
			  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			)");
	}
	
	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "sitemap_links`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "sitemap_index`");
	}
	
	public function getrecords($data){
		
		$sql = "SELECT * FROM `".DB_PREFIX."sitemap_links` WHERE store_id = '".(int)$data['store_id']."'";
		
		$sql .=  " ORDER BY date_added DESC";
		
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
	
	public function getTotalrecords($data){
		$sql = "SELECT * FROM `".DB_PREFIX."sitemap_links` WHERE store_id = '".(int)$data['store_id']."'";	
		$sql .=  " ORDER BY date_added DESC";
		$results = $this->db->query($sql);
		return $results->num_rows;
	}
	
	public function checkinvaliddate($page = 'product'){
		$sql = "SELECT count(*) as total FROM `".DB_PREFIX.$page."` WHERE date_modified = '0000-00-00 00:00:00' OR date_modified IS NULL OR date_modified = ''";
		$results = $this->db->query($sql);
		return $results->row['total'];
	}
	
	public function updateinvaliddate(){
		$this->db->query("UPDATE `".DB_PREFIX."product` SET date_modified = now() WHERE date_modified = '0000-00-00 00:00:00' OR date_modified IS NULL OR date_modified = ''");
		$this->db->query("UPDATE `".DB_PREFIX."category` SET date_modified = now() WHERE date_modified = '0000-00-00 00:00:00' OR date_modified IS NULL OR date_modified = ''");
	}
	
}
?>