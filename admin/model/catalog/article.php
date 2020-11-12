<?php
class ModelCatalogArticle extends Model {
	
	private $wp_db;
	
	public function __construct() {
        $this->wp_db = new DB('mysqli', 'localhost', 'root', '', 'gempack_blog_prod', '3306');
    }
	
	public function getArticles($data = array()) {
		$sql = "SELECT ID,post_title FROM wp_posts WHERE post_status = 'publish' AND post_type = 'post'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND post_title LIKE '%" . $this->wp_db->escape($data['filter_name']) . "%'";
		}

		
		$sql .= " ORDER BY post_title";
	

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->wp_db->query($sql);
		
		//echo "<pre>"; print_r($query->rows); echo "</pre>"; exit;

		return $query->rows;
	}

	
}