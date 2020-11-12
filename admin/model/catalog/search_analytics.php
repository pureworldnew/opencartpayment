<?php

// ***************************************************
//                  Search Analytics    
//  
//       Standalone extension and component of 
//               Advanced Smart Search
//
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************


class ModelCatalogSearchAnalytics extends Model {


	public function create_tables() {

		$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "search_history(id int(255) NOT NULL AUTO_INCREMENT, keyphrase varchar(255), customer_id int(255), ip varchar(255), timestamp TIMESTAMP, PRIMARY KEY(id)) ENGINE = MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
	}
	
		
 	
	// Get all the keywords (unique) from keyphrases
	public function get_unique_keywords($keyphrases) {
	
		$keywords = array();
		
		foreach ($keyphrases as $keyphrase) {
		
			$str = preg_replace('/\s+/S', '', $keyphrase); // strip off spaces and tabs
		
			if ( !empty($str)) { 
			
				$keyphrase = preg_replace('/\s+/S', ' ', $keyphrase); // strip off extra spaces and tabs
				
				$keyphrase = trim($keyphrase);
				
				$words = explode(' ', $keyphrase);
			
				foreach ($words as $word) {
					$keywords[] = $word;
				}
			}
		}
		
		if ( array_filter($keywords) ) {
			return array_unique($keywords);
		} else {
			return false;
		}
	}
	
	
	// Get the total number of keywords
	public function count_keywords($keyphrases) {
	
		$keywords = $this->get_unique_keywords($keyphrases);
		
		if (is_array($keywords)){
			return count($keywords);
				
		} else {
			return 0;
		}
	}
	

	private function get_filter_sql($filter_data) {
	
		$conditions = array();
		$keyphrase_statements = array();
		
		// Filter searches by date
		if( ($filter_data['filter_date_start']) != ''  ){

			$conditions[0] = " DATE(timestamp) >= '".$filter_data['filter_date_start']."' "; 			
		}
		
		if( ( $filter_data['filter_date_end']) != '' ){

			$conditions[1] = " DATE(timestamp) <= '".$filter_data['filter_date_end']."' "; 	
		}
		
		

		if ( isset($filter_data['filter_keyphrases']) ) {
			
			$search_analytics_lib = new Search_analytics();
			
			$keyphrases = array();
			
			// Sanitize keyphrases
			foreach ($filter_data['filter_keyphrases'] as $keyphrase ) {
			
				$sanitized = $search_analytics_lib->sanitize_keyphrases($keyphrase);
				if ($sanitized !== false) {
					$keyphrases[] = $sanitized; 
				}
			}
			
			
			if ( isset($filter_data['match_type']) && $filter_data['match_type'] == 'broad' ) {
			
				if (!empty($keyphrases)) {
				
					$keywords = $this->get_unique_keywords($keyphrases);
				
					foreach ($keywords as $keyword) {
					
					//	$keyphrase_statements[] = " sh.keyphrase = '" . $keyword . "' ";	
						$keyphrase_statements[] = " sh.keyphrase REGEXP '[[:<:]]" . $this->db->escape($keyword) . "[[:>:]]' ";
					}
				}
				
			} else {
			
				foreach ($keyphrases as $keyphrase) {
				
					$keyphrase_statements[] = " sh.keyphrase = '" . $this->db->escape($keyphrase) . "' ";	
				}
			}
			
			if ( array_filter($keyphrase_statements) ) {  // simple trick to check whether there is at least one array element  ( !array_filter($array) => all elements are empty )
				$conditions[2] = " (" . implode("OR", $keyphrase_statements ) . ") ";
			}
		}
		
		$sql = "";
		if ( array_filter($conditions) ) { // See array_filter($keyphrase_statements)
			$sql = " WHERE	" . implode(" AND ", $conditions );
		}
		// echo $sql;
		return $sql;
	}

	
	public function get_chart($filter_data) {
	

		$search_analytics_lib = new Search_analytics();
		
		$keyphrases = array();
		
		// Sanitize keyphrases
		foreach ($filter_data['filter_keyphrases'] as $keyphrase ) {
		
			$sanitized = $search_analytics_lib->sanitize_keyphrases($keyphrase);
			if ($sanitized !== false) {
				$keyphrases[] = $sanitized; 
			}
		}
	
  
		$date_format = '%Y-%m-%d'; // daily
		
		if ( isset($filter_data['aggregation_period']) ) {
			
			switch ($filter_data['aggregation_period']) {
				case 'day':
					$date_format = '%Y-%m-%d';
					break;
				case 'month':
					$date_format = '%Y-%m-01';
					break;
				case 'year':
					$date_format = '%Y-01-01';
					break;		
			}
		}
		
		if (!empty($keyphrases)) {

			$implode = array();
		
			if ( isset($filter_data['match_type']) && $filter_data['match_type'] == 'broad' ) {
				
				$strings = $this->get_unique_keywords($keyphrases);

			} else {
			
				$strings = $keyphrases;
			}
			
			foreach ($strings as $i => $string) {
			
				if ( isset($filter_data['match_type']) && $filter_data['match_type'] == 'broad' ) {
					
					$sql_operator = "LIKE '%" . $this->db->escape($string) . "%'";

				} else {
				
					$sql_operator = " = '" . $this->db->escape($string) . "'";
				//	$sql_operator = "LIKE '%" . $string . "%'";
				}
			
		//		$implode[] = " (SELECT COUNT(*) FROM " . DB_PREFIX . "search_history sh  WHERE sh.keyphrase ".$sql_operator."  AND date = DATE(sh.timestamp)  ) AS string_". $i ." ";	
				
				$implode[] = " (SELECT COUNT(*) FROM " . DB_PREFIX . "search_history sh  WHERE sh.keyphrase ".$sql_operator."  AND date = DATE_FORMAT(sh.timestamp, '".$date_format."') ) AS string_". $i ." ";	
			
								// " CASE WHEN sh.keyphrase LIKE '%" . $keyword . "%' THEN '".$keyword."' END AS keyword_". $i .", ";	
								// " (SELECT sh.keyphrase FROM " . DB_PREFIX . "search_history sh WHERE sh.keyphrase LIKE '%" . $keyword . "%' ) AS keyword_". $i .", ";	
			}
			

			$sub_query = implode(",", $implode); 
	
		}

		/*
		$sql =	"SELECT	
				
					DATE(sh.timestamp) as date ";
*/

		$sql =	"SELECT	
				
					DATE_FORMAT(sh.timestamp, '".$date_format."') as date ";

		
		if (empty($keyphrases)) {
			$sql .=	", COUNT(*) as total ";
		}
		
		
		if (isset($sub_query)) {
			$sql .= ", ". $sub_query;
		}	

		$sql .=	" FROM " . DB_PREFIX . "search_history sh ";

		// Filters		
		$sql .= $this->get_filter_sql($filter_data); // WHERE cond_1 AND cond_2 ...
		
		$sql .=	" GROUP BY date ";
		
		if (!empty($keyphrases)) { 
		
			$sql .=	", ";
		
			$grouping = array();

			foreach ($strings as $i => $string) {
				
				$grouping[] = " string_". $i;
			}
			$sql .=	implode (",", $grouping);

		}


		$result = $this->db->query($sql);
		// echo $sql;
		return $result->rows;
	}	
	
	
	public function get_searches($filter_data) {
	
		$sql =	"SELECT	sh.id,
						sh.keyphrase,
						sh.ip,
						IFNULL(CONCAT(c.firstname,' ', c.lastname), 'guest') as customer_name, 
						DATE(sh.timestamp) as date, 
						TIME(sh.timestamp) as time
				";
							
		$sql .=	" FROM " . DB_PREFIX . "search_history sh 
			
				LEFT JOIN " . DB_PREFIX . "customer c ON (sh.customer_id = c.customer_id) 
		";
		
		// Filters		
		$sql .= $this->get_filter_sql($filter_data); // WHERE cond_1 AND cond_2 ...


		if ( isset($filter_data['order_by'])  && !empty($filter_data['order_by']) ) {
		
			$sql .=	" ORDER BY " . $filter_data['order_by'];
		}
		
		// ASC / DESC
		if ( isset($filter_data['sort_order'])  && !empty($filter_data['sort_order']) ) {
		
			$sql .=	" " . $filter_data['sort_order'] . " ";
		}
		

		if ( isset($filter_data['start']) || isset($filter_data['limit']) ) {
			
			if ($filter_data['start'] < 0) {
				$filter_data['start'] = 0;
			}				
			if ($filter_data['limit'] < 1) {
				$filter_data['limit'] = 20;
			}	
			$sql .= " LIMIT " . (int)$filter_data['start'] . "," . (int)$filter_data['limit'];
		}

		$result = $this->db->query($sql);
		return $result->rows;
	}

	
	public function get_search_total($filter_data) {
	
		$sql =	"SELECT COUNT(*) AS total
			
				FROM " . DB_PREFIX . "search_history sh 
			
				LEFT JOIN " . DB_PREFIX . "customer c ON (sh.customer_id = c.customer_id) 
		";
		
		// Filters
		$sql .= $this->get_filter_sql($filter_data); // WHERE cond_1 AND cond_2 ...

		$result = $this->db->query($sql);
				
		return $result->row['total'];
	}
	
	
	public function get_total_unique_searches($filter_data) {
	
		$sql =	"SELECT count(DISTINCT keyphrase) AS total 
		
				FROM " . DB_PREFIX . "search_history sh ";
	
		// Filters
		$sql .= $this->get_filter_sql($filter_data); // WHERE cond_1 AND cond_2 ...
		
		$result = $this->db->query($sql);
		
		return $result->row['total'];
	}

	
	public function delete_search($id_list = array()) {

		// If $id_list is empty the whole table will be cleaned
	
		$sql = "";
		if ( !empty($id_list) ){
	
			$conditions = array();
		
			foreach ($id_list as $id) {
				$conditions[] = " id = ".(int)$id . " ";
			}
			$sql = " WHERE " . implode(" OR ", $conditions );	
		}
		return $this->db->query("DELETE FROM " . DB_PREFIX . "search_history  " . $sql);
	}
	
		
	public function get_search_period() {
	
		// First check if the table "search_history" exists, because the constructor in search_analtyics.php uses this function when
		// the table could not have already been created
		
		$result = $this->db->query("SELECT count((1)) as `count`  FROM INFORMATION_SCHEMA.TABLES where table_schema ='" . DB_DATABASE . "' and table_name='" . DB_PREFIX . "search_history'"); 
		
		$table_exists = $result->row['count'];

		if( $table_exists ) {
			
			$sql =	"SELECT MIN(DATE(sh.timestamp)) AS min, MAX(DATE(sh.timestamp)) AS max FROM " . DB_PREFIX . "search_history sh";
	
			$result = $this->db->query($sql);
	
			return $result->row;	
			
		} else {
			$period['min'] = 0;
			$period['max'] = 0;
			return $period;
		}
	}
	
	
	public function keyword_hits($filter_data) {

		$sql = "SELECT	keyphrase, 
						COUNT(*) AS total 
				FROM " . DB_PREFIX . "search_history sh ";
		
		// Filters
		$sql .= $this->get_filter_sql($filter_data); // WHERE cond_1 AND cond_2 ...
		
		
		$sql .= " GROUP BY keyphrase ORDER BY total ";
		
		// ASC / DESC
		if ( isset($filter_data['sort_order'])  && !empty($filter_data['sort_order']) ) {
		
			$sql .=	$filter_data['sort_order'] ;
		}
		
		if (isset($filter_data['start']) || isset($filter_data['limit'])) {
			if ($filter_data['start'] < 0) {
				$filter_data['start'] = 0;
			}				
			if ($filter_data['limit'] < 1) {
				$filter_data['limit'] = 20;
			}	
				$sql .= " LIMIT " . (int)$filter_data['start'] . "," . (int)$filter_data['limit'];
		}
		
	//	echo $sql;exit;
		
		$result = $this->db->query($sql);
		return $result->rows;
	}

	
	
	public function restore_demo() {
	
		$sql = "TRUNCATE TABLE `" . DB_PREFIX . "search_history`";
		$this->db->query($sql);
	
		
		$sql =	"INSERT INTO " . DB_PREFIX . "`search_history` (`id`, `keyphrase`, `customer_id`, `ip`, `timestamp`) VALUES
		
						(38, 'Macbook pro', 0, '61.29.71.9', '2015-09-20 19:54:26'),
						(37, 'Macbook air', 0, '74.79.18.40', '2015-09-29 20:54:20'),
						(5, 'Nikon d300', 0, '125.10.32.245', '2015-09-01 14:37:20'),
						(36, 'Macbook air', 0, '74.79.18.40', '2015-09-20 20:54:18'),
						(7, 'vaio', 0, '61.29.71.9', '2015-09-20 14:37:38'),
						(8, 'sony vaio', 0, '61.29.71.9', '2015-09-20 14:37:45'),
						(9, 'canon', 0, '61.29.71.9', '2015-09-20 14:37:59'),
						(10, 'canon eos', 0, '69.34.7.192', '2015-09-20 14:38:09'),
						(11, 'canon eos 5d', 0, '41.59.1.129', '2015-09-20 14:38:18'),
						(12, 'eos 5d', 0, '41.59.1.129', '2015-09-20 14:38:26'),
						(13, 'iphone', 0, '41.59.1.129', '2015-09-03 14:38:51'),
						(14, 'iphone 5', 0, '41.59.1.129', '2015-09-03 14:39:04'),
						(15, 'iphone 5s', 0, '74.79.18.40', '2015-09-03 14:39:23'),
						(16, 'iphone 4', 0, '69.34.7.192', '2015-09-03 14:39:46'),
						(17, 'iphone 4s', 0, '56.129.181.4', '2015-09-05 14:39:53'),
						(18, 'iphone 3', 0, '56.129.181.4', '2015-09-05 14:40:01'),
						(19, 'iphone 2', 0, '56.129.181.4', '2015-09-05 14:40:05'),
						(20, 'canon printer', 0, '56.129.181.4', '2015-09-05 14:40:13'),
						(21, 'samsung galaxy', 0, '56.129.181.4', '2015-09-05 14:40:24'),
						(22, 'smartphone samsung', 0, '31.9.1.129', '2015-09-05 14:40:33'),
						(23, 'samsung', 0, '31.9.1.129', '2015-09-05 15:50:49'),
						(24, 'samsung', 0, '31.9.1.129', '2015-09-20 16:45:29'),
						(25, 'samsung', 0, '31.9.1.129', '2015-09-08 16:46:02'),
						(26, 'iphone 5s', 0, '31.9.1.129', '2015-09-08 16:56:22'),
						(32, 'ipod classic', 0, '69.34.7.192', '2015-09-08 18:07:34'),
						(31, 'ipod classic', 0, '44.98.71.29', '2015-09-08 18:07:24'),
						(39, 'Macbook pro', 0, '44.98.71.29', '2015-09-20 20:54:29'),
						(33, 'ipod classic', 0, '44.98.71.29', '2015-09-11 18:07:37'),
						(34, 'apple ipod classic', 0, '146.231.81.9', '2015-09-11 18:07:45'),
						(35, 'apple cinema', 0, '146.231.81.9', '2015-09-11 18:08:06'),
						(40, 'Macbook pro', 0, '146.231.81.9', '2015-09-11 20:54:31'),
						(41, 'Samsung Galaxy', 0, '146.231.81.9', '2015-09-11 05:54:46'),
						(42, 'Samsung Galaxy', 0, '69.34.7.192', '2015-09-11 05:54:48'),
						(43, 'Samsung Galaxy', 0, '46.21.1.9', '2015-09-11 08:54:52'),
						(44, 'Samsung Galaxy', 0, '46.21.1.9', '2015-09-11 08:54:55'),
						(45, 'Philips tv', 0, '46.21.1.9', '2015-09-12 10:55:27'),
						(46, 'Android phone', 0, '46.21.1.9', '2015-09-12 10:56:09'),
						(47, 'Playstation 4', 0, '46.21.1.9', '2015-09-12 10:56:39'),
						(48, 'Playstation 4', 0, '46.21.1.9', '2015-09-12 14:56:45'),
						(49, 'Playstation 4', 0, '69.34.7.192', '2015-09-15 14:56:46'),
						(50, 'Playstation 3', 0, '78.123.45.232', '2015-09-15 14:56:53'),
						(51, 'Microsoft Lumia', 0, '78.123.45.232', '2015-09-15 14:57:15'),
						(52, 'xbox 360', 0, '69.34.7.192', '2015-09-16 16:58:40'),
						(53, 'xbox 360', 0, '89.231.245.32', '2015-09-17 16:58:42'),
						(54, 'xbox one', 0, '89.231.245.32', '2015-09-17 03:58:46'),
						(55, 'xbox one', 0, '89.231.245.32', '2015-09-18 03:58:48'),
						(56, 'xbox one', 0, '69.34.7.192', '2015-09-18 03:58:51'),
						(57, 'gamepad', 0, '65.41.45.211', '2015-09-18 03:59:39'),
						(58, 'wireless mouse', 0, '65.41.45.211', '2015-09-18 03:59:53'),
						(59, 'microsoft mouse', 0, '65.41.45.211', '2015-09-19 20:00:03'),
						(60, 'microsoft mouse', 0, '69.34.7.192', '2015-09-19 20:00:05'),
						(61, 'Amazon fire', 0, '165.199.55.56', '2015-09-19 20:00:41'),
						(62, 'Kindle Paperwhite', 0, '165.199.55.56', '2015-09-19 20:00:56'),
						(63, 'Google Chromecast HDMI', 0, '165.199.55.56', '2015-09-19 20:01:27'),
						(64, 'Google Chromecast HDMI', 0, '69.34.7.192', '2015-09-19 20:01:29'),
						(65, 'kindle 6''', 0, '75.29.135.46', '2015-09-19 20:01:54'),
						(66, 'Acer Chromebook', 0, '75.29.135.46', '2015-09-20 20:02:12'),
						(67, 'Acer Chromebook', 0, '75.29.135.46', '2015-09-20 20:02:12'),
						(68, 'Acer Chromebook', 0, '69.34.7.192', '2015-09-20 20:02:14'),
						(69, 'Acer Chromebook', 0, '21.139.35.246', '2015-09-20 20:02:15'),
						(70, 'Motorola Moto 360', 0, '21.139.35.246', '2015-09-20 20:03:44'),
						(71, 'Motorola Moto 360', 0, '69.34.7.192', '2015-09-20 20:04:07'),
						(72, 'Apple Watch Sport', 0, '67.39.185.24', '2015-09-20 20:04:13'),
						(73, 'Apple Watch Sport', 0, '67.39.185.24', '2015-09-20 20:04:16'),
						(74, 'Apple Watch', 0, '67.39.185.24', '2015-09-20 20:04:19'),
						(75, 'Samsung Gear Fit Smart Watch', 0, '67.39.185.24', '2015-09-20 20:04:35'),
						(76, 'Samsung Smart Watch', 0, '74.79.18.40', '2015-09-20 20:04:42'),
						(77, 'Samsung Smart Watch', 0, '74.79.18.40', '2015-09-20 20:04:46'),
						(78, 'mouse microsoft', 0, '69.34.7.192', '2015-09-20 20:05:42'),
						(79, 'Sony W800/B 20.1 MP Digital Camera', 0, '69.34.7.192', '2015-09-20 20:06:32'),
						(80, 'Fujifilm FinePix S8600', 0, '69.34.7.192', '2015-09-20 20:06:51'),
						(81, 'Fujifilm S8600', 0, '69.34.7.192', '2015-09-20 20:06:57'),
						(82, 'Canon PowerShot SX400', 0, '69.34.7.192', '2015-09-20 20:08:20'),
						(83, 'Canon EOS', 0, '69.34.7.192', '2015-09-20 20:08:45'),
						(84, 'LG Electronics 32LF500B 32-Inch', 0, '69.34.7.192', '2015-09-20 20:09:55'),
						(85, 'Samsung UN32J4000', 0, '69.34.7.192', '2015-09-20 20:10:04'),
						(86, 'Samsung UN32J4000', 0, '69.34.7.192', '2015-09-20 20:10:05'),
						(87, 'Samsung UN32J4000', 0, '69.34.7.192', '2015-09-20 20:10:09')";
				
		$this->db->query($sql);
	
	}
	
}
