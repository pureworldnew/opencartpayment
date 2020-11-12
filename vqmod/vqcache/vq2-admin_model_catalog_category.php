<?php
class ModelCatalogCategory extends Model {
	public function addCategory($data) {
		$this->event->trigger('pre.admin.category.add', $data);

		
      // handle item id for universal import
      if (!empty($data['category_id']) && defined('GKD_UNIV_IMPORT')) {
        $univimp_item_id = 'category_id = "' . (int) $data['category_id'] . '", ';
      } else {
        $univimp_item_id = '';
      }
      
			$this->db->query("INSERT INTO " . DB_PREFIX . "category SET " . $univimp_item_id . " parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', sort_by = '" . $this->db->escape($data['sort_by']) . "', sorting_order = '" . $this->db->escape($data['sorting_order']) . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");

		$category_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		if (isset($data['banner'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET banner = '" . $this->db->escape($data['banner']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$level = 0;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

			$level++;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");

		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		// Set which layout to use with this category
		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}


			
				require_once(DIR_APPLICATION . 'controller/catalog/seopack.php');
				$seo = new ControllerCatalogSeoPack($this->registry);
				
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `key` like 'seopack%'");
			
				foreach ($query->rows as $result) {
						if (!$result['serialized']) {
							$data[$result['key']] = $result['value'];
						} else {
							if ($result['value'][0] == '{') {$data[$result['key']] = json_decode($result['value'], true);} else {$data[$result['key']] = unserialize($result['value']);}
						}
					}
					
				if (isset($data)) {$seopack_parameters = $data['seopack_parameters'];}
				
				if ((isset($seopack_parameters['autourls'])) && ($seopack_parameters['autourls']))
					{
						require_once(DIR_APPLICATION . 'controller/catalog/seopack.php');
						$seo = new ControllerCatalogSeoPack($this->registry);
						
						$query = $this->db->query("SELECT cd.category_id, cd.name, cd.language_id, l.code FROM ".DB_PREFIX."category c 
								inner join ".DB_PREFIX."category_description cd on c.category_id = cd.category_id 
								inner join ".DB_PREFIX."language l on l.language_id = cd.language_id
								where c.category_id = '" . (int)$category_id . "'");

						
						foreach ($query->rows as $category_row){	

							
							if( strlen($category_row['name']) > 1 ){
							
								$slug = $seo->generateSlug($category_row['name']);
								$exist_query =  $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.query = 'category_id=" . $category_row['category_id'] . "' and language_id=".$category_row['language_id']);
								
								if(!$exist_query->num_rows){
									
									$exist_keyword = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.keyword = '" . $slug . "'");
									if($exist_keyword->num_rows){ 
										$exist_keyword_lang = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.keyword = '" . $slug . "' AND " . DB_PREFIX . "url_alias.query <> 'category_id=" . $category_row['category_id'] . "'");
										if($exist_keyword_lang->num_rows){
												$slug = $seo->generateSlug($category_row['name']).'-'.rand();
											}
											else
											{
												$slug = $seo->generateSlug($category_row['name']).'-'.$category_row['code'];
											}
										}
									
										
									
									$add_query = "INSERT INTO " . DB_PREFIX . "url_alias (query, keyword,language_id) VALUES ('category_id=" . $category_row['category_id'] . "', '" . $slug . "', " . $category_row['language_id'] . ")";
									$this->db->query($add_query);
									
								}
							}
						}
					}
			
		$this->cache->delete('category');

		$this->event->trigger('post.admin.category.add', $category_id);

		return $category_id;
	}

	public function editCategory($category_id, $data) {
		$this->event->trigger('pre.admin.category.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', sort_by = '" . $this->db->escape($data['sort_by']) . "', sorting_order = '" . $this->db->escape($data['sorting_order']) . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		if (isset($data['banner'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET banner = '" . $this->db->escape($data['banner']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE path_id = '" . (int)$category_id . "' ORDER BY level ASC");

		if ($query->rows) {
			foreach ($query->rows as $category_path) {
				// Delete the path below the current one
				$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' AND level < '" . (int)$category_path['level'] . "'");

				$path = array();

				// Get the nodes new parents
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Get whats left of the nodes current path
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Combine the paths with a new level
				$level = 0;

				foreach ($path as $path_id) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_path['category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

					$level++;
				}
			}
		} else {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_id . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', level = '" . (int)$level . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}


			
				require_once(DIR_APPLICATION . 'controller/catalog/seopack.php');
				$seo = new ControllerCatalogSeoPack($this->registry);
				
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `key` like 'seopack%'");
			
				foreach ($query->rows as $result) {
						if (!$result['serialized']) {
							$data[$result['key']] = $result['value'];
						} else {
							if ($result['value'][0] == '{') {$data[$result['key']] = json_decode($result['value'], true);} else {$data[$result['key']] = unserialize($result['value']);}
						}
					}
					
				if (isset($data)) {$seopack_parameters = $data['seopack_parameters'];}
				
				if ((isset($seopack_parameters['autourls'])) && ($seopack_parameters['autourls']))
					{
						require_once(DIR_APPLICATION . 'controller/catalog/seopack.php');
						$seo = new ControllerCatalogSeoPack($this->registry);
						
						$query = $this->db->query("SELECT cd.category_id, cd.name, cd.language_id, l.code FROM ".DB_PREFIX."category c 
								inner join ".DB_PREFIX."category_description cd on c.category_id = cd.category_id 
								inner join ".DB_PREFIX."language l on l.language_id = cd.language_id
								where c.category_id = '" . (int)$category_id . "'");

						
						foreach ($query->rows as $category_row){	

							
							if( strlen($category_row['name']) > 1 ){
							
								$slug = $seo->generateSlug($category_row['name']);
								$exist_query =  $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.query = 'category_id=" . $category_row['category_id'] . "' and language_id=".$category_row['language_id']);
								
								if(!$exist_query->num_rows){
									
									$exist_keyword = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.keyword = '" . $slug . "'");
									if($exist_keyword->num_rows){ 
										$exist_keyword_lang = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.keyword = '" . $slug . "' AND " . DB_PREFIX . "url_alias.query <> 'category_id=" . $category_row['category_id'] . "'");
										if($exist_keyword_lang->num_rows){
												$slug = $seo->generateSlug($category_row['name']).'-'.rand();
											}
											else
											{
												$slug = $seo->generateSlug($category_row['name']).'-'.$category_row['code'];
											}
										}
									
										
									
									$add_query = "INSERT INTO " . DB_PREFIX . "url_alias (query, keyword,language_id) VALUES ('category_id=" . $category_row['category_id'] . "', '" . $slug . "', " . $category_row['language_id'] . ")";
									$this->db->query($add_query);
									
								}
							}
						}
					}
			
		$this->cache->delete('category');

		$this->event->trigger('post.admin.category.edit', $category_id);
	}

	public function deleteCategory($category_id) {
		$this->event->trigger('pre.admin.category.delete', $category_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_path WHERE category_id = '" . (int)$category_id . "'");

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_path WHERE path_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$this->deleteCategory($result['category_id']);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "'");


			
				require_once(DIR_APPLICATION . 'controller/catalog/seopack.php');
				$seo = new ControllerCatalogSeoPack($this->registry);
				
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `key` like 'seopack%'");
			
				foreach ($query->rows as $result) {
						if (!$result['serialized']) {
							$data[$result['key']] = $result['value'];
						} else {
							if ($result['value'][0] == '{') {$data[$result['key']] = json_decode($result['value'], true);} else {$data[$result['key']] = unserialize($result['value']);}
						}
					}
					
				if (isset($data)) {$seopack_parameters = $data['seopack_parameters'];}
				
				if ((isset($seopack_parameters['autourls'])) && ($seopack_parameters['autourls']))
					{
						require_once(DIR_APPLICATION . 'controller/catalog/seopack.php');
						$seo = new ControllerCatalogSeoPack($this->registry);
						
						$query = $this->db->query("SELECT cd.category_id, cd.name, cd.language_id, l.code FROM ".DB_PREFIX."category c 
								inner join ".DB_PREFIX."category_description cd on c.category_id = cd.category_id 
								inner join ".DB_PREFIX."language l on l.language_id = cd.language_id
								where c.category_id = '" . (int)$category_id . "'");

						
						foreach ($query->rows as $category_row){	

							
							if( strlen($category_row['name']) > 1 ){
							
								$slug = $seo->generateSlug($category_row['name']);
								$exist_query =  $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.query = 'category_id=" . $category_row['category_id'] . "' and language_id=".$category_row['language_id']);
								
								if(!$exist_query->num_rows){
									
									$exist_keyword = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.keyword = '" . $slug . "'");
									if($exist_keyword->num_rows){ 
										$exist_keyword_lang = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.keyword = '" . $slug . "' AND " . DB_PREFIX . "url_alias.query <> 'category_id=" . $category_row['category_id'] . "'");
										if($exist_keyword_lang->num_rows){
												$slug = $seo->generateSlug($category_row['name']).'-'.rand();
											}
											else
											{
												$slug = $seo->generateSlug($category_row['name']).'-'.$category_row['code'];
											}
										}
									
										
									
									$add_query = "INSERT INTO " . DB_PREFIX . "url_alias (query, keyword,language_id) VALUES ('category_id=" . $category_row['category_id'] . "', '" . $slug . "', " . $category_row['language_id'] . ")";
									$this->db->query($add_query);
									
								}
							}
						}
					}
			
		$this->cache->delete('category');

		$this->event->trigger('post.admin.category.delete', $category_id);
	}

	public function repairCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$parent_id . "'");

		foreach ($query->rows as $category) {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category['category_id'] . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$parent_id . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$category['category_id'] . "', level = '" . (int)$level . "'");

			$this->repairCategories($category['category_id']);
		}
	}

	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "') AS keyword FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getCategories($data = array()) {
		$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY cp.category_id";

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
		}

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

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getCategoriesExport($data) {
		$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' &gt; ') AS name, c.parent_id, c.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c ON (cp.path_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (c.category_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY cp.category_id ORDER BY name";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
		 
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		//echo $sql;			
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function getCategoryDescriptions($category_id) {
		$category_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'description'      => $result['description']
			);
		}

		return $category_description_data;
	}

	public function getCategoryFilters($category_id) {
		$category_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_filter_data[] = $result['filter_id'];
		}

		return $category_filter_data;
	}

	public function getCategoryStores($category_id) {
		$category_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_store_data[] = $result['store_id'];
		}

		return $category_store_data;
	}

	public function getCategoryLayouts($category_id) {
		$category_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $category_layout_data;
	}

	public function getTotalCategories() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category");

		return $query->row['total'];
	}

	public function getTotalCategoriesSeourls() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "url_alias  WHERE query LIKE '%category_id%' AND language_id = 1");

		return $query->row['total'];
	}

	public function getCategoriesSeourls($data = array())
	{
		
			$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";
	
			if (!empty($data['filter_name'])) {
				$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			}
	
			$sql .= " GROUP BY cp.category_id";

			$sql .= " ORDER BY name";
	
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

			$categories = $query->rows;

			foreach ( $categories as $k => $category)
			{
				$seo_data = $this->getCategorySeoUrl($category['category_id']);

				$categories[$k]['url_alias_id'] = isset($seo_data['url_alias_id']) ? $seo_data['url_alias_id'] : 0;
				$categories[$k]['seo_url'] 		= isset($seo_data['keyword']) ? $seo_data['keyword'] : "";
				if ( $categories[$k]['url_alias_id'] == 0 )
				{
					unset($categories[$k]);
				}
			}
		return $categories;
	}

	public function getCategorySeoUrl($category_id)
	{
		$query = $this->db->query("SELECT url_alias_id,keyword FROM " . DB_PREFIX . "url_alias  WHERE query LIKE 'category_id=" . (int)$category_id . "' AND language_id = 1");
		
		return $query->row;
	}

	public function getCategorySeoUrlInfo($url_alias_id)
	{
		$query = $this->db->query("SELECT query,keyword FROM " . DB_PREFIX . "url_alias  WHERE url_alias_id = '" . (int)$url_alias_id . "' AND language_id = 1");
		return $query->row;
	}

	public function saveCategorySeoUrl($data)
	{
		if ( !empty( $data['seo_url'] ) && !empty( $data['url_alias_id'] ) )
		{
			$category_seo_url_info = $this->getCategorySeoUrlInfo($data['url_alias_id']);
			$old_seo_url = !empty($category_seo_url_info) ? $category_seo_url_info['keyword'] : "";
			$query = !empty($category_seo_url_info) ? $category_seo_url_info['query'] : "";
			$query = explode('=', $query);
			if ($query[0] == 'category_id') {
				$category_id = $query[1];
			} else {
				$category_id = 0;
			}

			if ( $category_id )
			{
				$this->db->query("UPDATE " . DB_PREFIX . "url_alias  SET keyword = '" . $this->db->escape($data['seo_url']) . "'  WHERE url_alias_id = '" . (int)$data['url_alias_id'] . "' AND language_id = 1");

				if ( $data['seo_url'] != $old_seo_url )
				{
					$this->addCategoryRedirect($category_id, $old_seo_url);
				}
			}
		}
	}

	public function saveCategoriesSeoUrl($categories)
	{
		foreach ( $categories as $category )
		{
			$this->saveCategorySeoUrl($category);
		}
	}

	public function addCategoryRedirect($category_id, $old_seo_url)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_redirect WHERE new_url LIKE 'category_id=" . (int)$category_id . "'");
		if( $query->row )
		{
			$this->db->query("UPDATE " . DB_PREFIX . "url_redirect SET old_url = '" . $this->db->escape($old_seo_url) . "' WHERE new_url LIKE 'category_id=" . (int)$category_id . "'");
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_redirect (old_url, new_url) VALUES ('" . $this->db->escape($old_seo_url) . "', 'category_id=" . (int)$category_id . "')");
		}
	}
	
	public function getTotalCategoriesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
	
	public function getLatestCategory()
	{
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category_description WHERE name = 'latest'");
		return !empty($query->row) ? $query->row['category_id'] : 0;
	}

	public function deleteOldProductsFromCat($category_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$category_id . "'");
	}

	public function getLatestProducts($limit)
	{
		$query = $this->db->query("SELECT product_id, date_added FROM " . DB_PREFIX . "product WHERE status = '1' AND date_added <= NOW() ORDER BY date_added DESC LIMIT " . (int)$limit . "");
		return $query->rows;
	}
	
	public function addProductToLatest($product_id, $category_id)
	{
		$this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'"); 
	}
}
