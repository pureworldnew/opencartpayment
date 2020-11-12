<?php
class ModelAdvancedmenuSeoSupercategorymenuadvancedSeo extends Model {
	
	public function CreateSeo(){
	  $sql = "DELETE FROM ". DB_PREFIX ."url_alias WHERE `query` IN ('filtering=C','filtering=M')";
	  $this->db->query($sql);
	  $sql = "INSERT INTO ". DB_PREFIX ."url_alias (`url_alias_id`, `query`, `keyword`) VALUES (NULL, 'filtering=C', 'C'), (NULL, 'filtering=M', 'M')";
	  $query = $this->db->query($sql);
	}
	public function getOptionName($option_id){
	$sql= "SELECT name FROM ". DB_PREFIX ."option_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND option_id = '" . (int)$option_id . "'";	
	$query = $this->db->query($sql);
		return $query->row['name'];
	}
	public function getOptionDescriptions($data) {
		$sql = "SELECT * FROM " . DB_PREFIX . "option_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";
		$sql .= " ORDER BY option_id";
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
	public function getTotalOptionsDescriptions() {
		$sql = "SELECT * FROM " . DB_PREFIX . "option_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function CheckKeyword($data) {
		//CheckKeyword($this->request->post['seo_word'])){
		return true;
		}
	public function CheckKeywordExits($data) {
		//CheckKeywordExits($this->request->post['seo_word'])){
		    return true;
		}
	public function KeywordUpdate($word,$path) {
		//KeywordUpdate($this->request->post['seo_word'],$this->request->post['path']);
		//$this->request->get['seo_word'],$this->request->get['path']
		$this->load->model('module/supercategorymenuadvanced');
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($path) . "'");
		if (strrpos($path, "model") !== false) {
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_model', $word);
			$this->InsertSeoWords("model", "filter_model", $path, $word);
		}
		elseif (strrpos($path, "width") !== false) {
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_width', $word);
			$this->InsertSeoWords("width", "filter_width", $path, $word);
		}
		elseif (strrpos($path, "height") !== false) {
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_height', $word);
			$this->InsertSeoWords("height", "filter_height", $path, $word);
		}
	    elseif (strrpos($path, "length") !== false) {
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_length', $word);
			$this->InsertSeoWords("length", "filter_length", $path, $word);
		}
		elseif (strrpos($path, "sku") !== false) {
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_sku', $word);
			$this->InsertSeoWords("sku", "filter_sku", $path, $word);
		}
		elseif (strrpos($path, "upc") !== false) {
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_upc', $word);
			$this->InsertSeoWords("upc", "filter_upc", $path, $word);
		}
		elseif (strrpos($path, "location") !== false) {
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_location', $word);
			$this->InsertSeoWords("location", "filter_location", $path, $word);
		}
	    elseif (strrpos($path, "weight") !== false) {
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_weight', $word);
			$this->InsertSeoWords("weight", "filter_weight", $path, $word);
		}
		elseif (strrpos($path, "ean") !== false) {
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_ean', $word);
			$this->InsertSeoWords("ean", "filter_ean", $path, $word);
		}
		elseif (strrpos($path, "isbn") !== false) {
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_isbn', $word);
			$this->InsertSeoWords("isbn", "filter_isbn", $path, $word);
		}
		elseif (strrpos($path, "mpn") !== false) {
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_mpn', $word);
			$this->InsertSeoWords("mpn", "filter_mpn", $path, $word);
		}
	    elseif (strrpos($path, "jan") !== false) {
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_jan', $word);
			$this->InsertSeoWords("jan", "filter_jan", $path, $word);
		}
		elseif (strrpos($path, "arrivals") !== false) {
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_arrivals', $word);
			$this->InsertSeoWordsStock("filter_arrivals", $path, $word);
		}
		 elseif (strrpos($path, "clearance") !== false) {
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_clearance', $word);
			$this->InsertSeoWordsStock("filter_clearance", $path, $word);
		}
		 elseif (strrpos($path, "special") !== false) {
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_special', $word);
			$this->InsertSeoWordsStock("filter_special", $path, $word);
		}
		 elseif (strrpos($path, "stock") !== false) {
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_stock', $word);
			$this->InsertSeoWordsStock("filter_stock", $path, $word);
		}
		 elseif (strrpos($path, "rating") !== false) {
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_rating', $word);
			$this->InsertSeoWordsReviews("filter_rating", $path, $word);
		}
		elseif (strrpos($path, "opt") !== false) {
			preg_match('@\[([^*]+)\]@',$path, $coincidencias);
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_opt['.(int)$coincidencias[1].']', $word);
			$this->InsertSeoWordsopt($path, $word);
		}
		elseif (strrpos($path, "att") !== false) {
			preg_match('@\[([^*]+)\]@',$path, $coincidencias);
			$this->model_module_supercategorymenuadvanced->editOneSetting('supercategorymenuadvancedSEO','filter_att['.(int)$coincidencias[1].']', $word);
			$this->InsertSeoWordsatt($path, $word);
		}else{
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '" . $this->db->escape($path) . "', keyword = '" . $this->db->escape($word) . "'");
		}
		//insert the generic value
		//$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '" . $this->db->escape($path) . "', keyword = '" . $this->db->escape($word) . "'");
		return false;
}		
public function InsertSeoWordsatt($path, $word){
		preg_match('@\[([^*]+)\]@',$path, $coincidencias);
		$attribute_id = (int)$coincidencias[1];
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query LIKE 'filter_att[".$attribute_id."]%'");	
		$split_value=$this->config->get('att_'.$attribute_id);
		if (isset($split_value)){
			$split_value=$split_value;
		}else{
			$split_value="no";
		}
		$query=$this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE attribute_id='".$attribute_id."'");
		$all_attributes= array();
		$new_array_values= array();
		foreach ($query->rows as $row) {
			$value_row = empty($row['text']) ? 'NDDDDDN' : $row['text'];
			if ($split_value!="no"){
				$attributes = explode($split_value, $value_row);	
					foreach ($attributes as $attribute) {
						if (!array_key_exists(trim($attribute), $new_array_values)) {
							$new_array_values[trim($attribute)]=trim($attribute);				
						}								
					}				
			}else{
				$new_array_values[trim($value_row)]=trim($value_row);
			}
		}
			foreach ($new_array_values as $key=>$value) {
				$path2=str_replace("@value@",$value,$path);					
				$word2=$this->url_slug(str_replace("@value@",$value,$word));
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '" . $this->db->escape($path2) . "', keyword = '" . $this->db->escape($word2) . "'");
			}
	}
	public function InsertSeoWordsopt($path, $word){
		preg_match('@\[([^*]+)\]@',$path, $coincidencias);
		$option_id = (int)$coincidencias[1];
		//	
		$query=$this->db->query("SELECT * FROM " . DB_PREFIX . "option_value_description WHERE option_id='".$option_id."'");
		foreach ($query->rows as $row) {
         	$option_value_id=$row['option_value_id'];
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query LIKE 'filter_opt[".$option_value_id."]%'");
			//	$path2=str_replace("@value@",$row['name'],$path);
				$path2="filter_opt[".$option_value_id."]=".$row['name'];
				$word2=$this->url_slug(str_replace("@value@",$row['name'],$word));
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '" . $this->db->escape($path2) . "', keyword = '" . $this->db->escape($word2) . "'");
		}
	}
	public function InsertSeoWords($what, $where, $path, $word){
		$this->load->language('advancedmenuseo/supercategorymenuadvanced_seo');
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query LIKE '".$where."%'");	
			$query=$this->db->query("SELECT DISTINCT ".$what." FROM " . DB_PREFIX . "product");
			foreach ($query->rows as $row) {
				$value_row = empty($row[$what]) ? 'NDDDDDN' : $row[$what];
				$path2=str_replace("@value@",$value_row,$path);
				$word2=$this->url_slug(str_replace("@value@",$value_row,$word));
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '" . $this->db->escape($path2) . "', keyword = '" . $this->db->escape($word2) . "'");
			}
	}
	public function InsertSeoWordsStock($where, $path, $word){
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($path) . "'");	
		$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '" . $this->db->escape($path) . "', keyword = '" . $this->db->escape($this->url_slug($word)) . "'");
	}
	public function InsertSeoWordsReviews($where, $path, $word){
		$this->load->language('advancedmenuseo/supercategorymenuadvanced_seo');
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query LIKE '".$where."%'");	
			$values=array(1,2,3,4,5);
			foreach ($values as $value) {
				$path2=str_replace("@value@",$value,$path);
				$word2=$this->url_slug(str_replace("@value@",$value,$word));
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '" . $this->db->escape($path2) . "', keyword = '" . $this->db->escape($word2) . "'");
			}
	}	
	public function seo_type($str) {
		$search_query = $str.'=@value@';
		echo "SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($search_query). "'";
		echo "<br>";
		$query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($search_query). "'");
		if ($query->row) {
		return $query->row['keyword'];
		}else{
			return '';
		}
	}
	public function url_slug($str, $options = array()) {
	// Make sure string is in UTF-8 and strip invalid UTF-8 characters
	$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
	$defaults = array(
	'delimiter' => '-',
	'limit' => null,
	'lowercase' => true,
	'replacements' => array(),
	'transliterate' => true,
	);
	// Merge options
	$options = array_merge($defaults, $options);
	$char_map = array(
	//arabic 
	"ا"=>"a", "أ"=>"a", "آ"=>"a", "إ"=>"e", "ب"=>"b", "ت"=>"t", "ث"=>"th", "ج"=>"j",
	"ح"=>"h", "خ"=>"kh", "د"=>"d", "ذ"=>"d", "ر"=>"r", "ز"=>"z", "س"=>"s", "ش"=>"sh",
	"ص"=>"s", "ض"=>"d", "ط"=>"t", "ظ"=>"z", "ع"=>"'e", "غ"=>"gh", "ف"=>"f", "ق"=>"q",
	"ك"=>"k", "ل"=>"l", "م"=>"m", "ن"=>"n", "ه"=>"h", "و"=>"w", "ي"=>"y", "ى"=>"a",
	"ئ"=>"'e", "ء"=>"'",   
	"ؤ"=>"'e", "لا"=>"la", "ة"=>"h", "؟"=>"?", "!"=>"!", 
	"ـ"=>"", 
	"،"=>",", 
	"َ‎"=>"a", "ُ"=>"u", "ِ‎"=>"e", "ٌ"=>"un", "ً"=>"an", "ٍ"=>"en", "ّ"=>"",
	//persian
	"ا"=>"a", "أ"=>"a", "آ"=>"a", "إ"=>"e", "ب"=>"b", "ت"=>"t", "ث"=>"th",
	"ج"=>"j", "ح"=>"h", "خ"=>"kh", "د"=>"d", "ذ"=>"d", "ر"=>"r", "ز"=>"z",
	"س"=>"s", "ش"=>"sh", "ص"=>"s", "ض"=>"d", "ط"=>"t", "ظ"=>"z", "ع"=>"'e",
	"غ"=>"gh", "ف"=>"f", "ق"=>"q", "ك"=>"k", "ل"=>"l", "م"=>"m", "ن"=>"n",
	"ه"=>"h", "و"=>"w", "ي"=>"y", "ى"=>"a", "ئ"=>"'e", "ء"=>"'", 
	"ؤ"=>"'e", "لا"=>"la", "ک"=>"ke", "پ"=>"pe", "چ"=>"che", "ژ"=>"je", "گ"=>"gu",
	"ی"=>"a", "ٔ"=>"", "ة"=>"h", "؟"=>"?", "!"=>"!", 
	"ـ"=>"", 
	"،"=>",", 
	"َ‎"=>"a", "ُ"=>"u", "ِ‎"=>"e", "ٌ"=>"un", "ً"=>"an", "ٍ"=>"en", "ّ"=>"",
	//normalize
	'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
	'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
	'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
	'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
	'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
	'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
	'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f', 'Ğ'=>'G', 'Ş'=>'S', 'Ü'=>'U',
	'ü'=>'u', 'Ẑ'=>'Z', 'ẑ'=>'z', 'Ǹ'=>'N', 'ǹ'=>'n', 'Ò'=>'O', 'ò'=>'o', 'Ù'=>'U', 'ù'=>'u', 'Ẁ'=>'W',
	'ẁ'=>'w', 'Ỳ'=>'Y', 'ỳ'=>'y', 'č'=>'c', 'Č'=>'C', 'á'=>'a', 'Á'=>'A', 'č'=>'c', 'Č'=>'C', 'ď'=>'d', 
	'Ď'=>'D', 'é'=>'e', 'É'=>'E', 'ě'=>'e', 'Ě'=>'E', 'í'=>'i', 'Í'=>'I', 'ň'=>'n', 'Ň'=>'N', 'ó'=>'o', 
	'Ó'=>'O', 'ř'=>'r', 'Ř'=>'R', 'š'=>'s', 'Š'=>'S', 'ť'=>'t', 'Ť'=>'T', 'ú'=>'u', 'Ú'=>'U', 'ů'=>'u', 
	'Ů'=>'U', 'ý'=>'y', 'Ý'=>'Y', 'ž'=>'z', 'Ž'=>'Z', "ą"=>'a', 'Ą'=>'A', 'ć'=>'c', 'Ć'=>'C', 'ę'=>'e',
	'Ę'=>'E', 'ł'=>'l', 'ń'=>'n', 'ó'=>'o', 'ś'=>'s', 'Ś'=>'S', 'ż'=>'z', 'Ż'=>'Z', 'ź'=>'z', 'Ź'=>'Z',
	'İ'=>'i', 'ş'=>'s', 'ğ'=>'g', 'ı'=>'i',
	// Latin
	'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
	'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
	'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
	'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
	'ß' => 'ss',
	'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
	'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
	'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
	'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
	'ÿ' => 'y',
	// Latin symbols
	'©' => '(c)',
	// Greek
	'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
	'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
	'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
	'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
	'Ϋ' => 'Y',
	'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
	'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
	'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
	'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
	'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
	// Turkish
	'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
	'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
	// Russian
	'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
	'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
	'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
	'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
	'Я' => 'Ya',
	'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
	'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
	'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
	'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
	'я' => 'ya',
	// Ukrainian
	'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
	'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
	// Czech
	'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
	'Ž' => 'Z',
	'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
	'ž' => 'z',
	// Polish
	'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
	'Ż' => 'Z',
	'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
	'ż' => 'z',
	// Latvian
	'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
	'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
	'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
	'š' => 's', 'ū' => 'u', 'ž' => 'z',
	//other symbols
	'&amp;' => '-',
	'&quot;' => '-',
	)
	;
	// Make custom replacements
	$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
	// Transliterate characters to ASCII
	if ($options['transliterate']) {
	$str = str_replace(array_keys($char_map), $char_map, $str);
	}
	// Replace non-alphanumeric characters with our delimiter
	$str = preg_replace('/[^\p{L}\p{Nd}\\.]+/u', $options['delimiter'], $str);
	//$str = preg_replace('/[^a-zA-Z0-9]/', $options['delimiter'], $str);
	// Remove duplicate delimiters
	$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
	// Truncate slug to max. characters
	$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
	// Remove delimiter from ends
	$str = trim($str, $options['delimiter']);
	return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
	}
	}
?>