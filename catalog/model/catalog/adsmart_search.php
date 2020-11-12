<?php
// ***************************************************
//               Advanced Smart Search   
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************


// Catalog Model 

class ModelCatalogAdsmartSearch extends Model {

	protected $registry;
	
	public function __construct($registry) {
		
		$this->registry = $registry;
	}

	// permuteUnique() permutes the input string. For example, the string
	// Apple iPhone 5s has the following permutations:
	
	// iPhone 5s Apple		Apple 5s iPhone		5s iPhone Apple
	// iPhone Apple 5s		Apple iPhone 5s		5s Apple iPhone

	// This function is no longer used
	protected function permuteUnique($string) {
	
		$array = explode(' ', trim(preg_replace('/\s\s+/', ' ', $string)));
	
		sort($array);
		$size = count($array);
		$permutation = array();
		while (true) {
			$permutation[] = $array;
			$invAt = $size - 2;
			for (;;$invAt--) {
				if ($invAt < 0) {
					break 2;
				}
				if ($array[$invAt] < $array[$invAt + 1]) {
					break;
				}
			}
			$swap1Num = $array[$invAt];
			$inv2At = $size - 1;
			while ($swap1Num >= $array[$inv2At]) {
				$inv2At--;
			}
			$array[$invAt] = $array[$inv2At];
			$array[$inv2At] = $swap1Num;
			$reverse1 = $invAt + 1;
			$reverse2 = $size - 1;
			while ($reverse1 < $reverse2) {
				$temp = $array[$reverse1];
				$array[$reverse1] = $array[$reverse2];
				$array[$reverse2] = $temp;
				$reverse1++;
				$reverse2--;
			}
		}
		foreach ( $permutation as &$arr){
			$arr = implode(' ', $arr);
		}
		return $permutation;
	}


	/* This method will be enabled in the next releases. */
	
	/*
	protected function sql_create_word_count(){
		$query = $this->db->query("DROP FUNCTION IF EXISTS word_count;");
	
		$query = $this->db->query("

			# uncomment this only for queries from the command line:
			# DELIMITER $$

			CREATE FUNCTION word_count( word VARCHAR(255), db_field TEXT )

			RETURNS INT
			
			DETERMINISTIC
			BEGIN
				RETURN (LENGTH(db_field) - LENGTH(REPLACE(db_field, word, ''))) DIV LENGTH(word)
			END
			
			# uncomment this only for queries from the command line:
			# $$
			# DELIMITER ;
		");
	}
	*/
		
	
	protected function sql_create_misspellings(){
		$query = $this->db->query("DROP FUNCTION IF EXISTS misspellings;");
	
		$query = $this->db->query("

			# uncomment this only for queries from the command line:
			# DELIMITER $$

			CREATE FUNCTION misspellings( word VARCHAR(255), db_field TEXT, word_tolerance TINYINT UNSIGNED )

RETURNS FLOAT
			
			DETERMINISTIC
			BEGIN
			

				DECLARE word_len, current_word_len, i, j, distance, temp_distance, cost INT;
				DECLARE word_char CHAR;
				-- max strlen=255
				DECLARE cv0, cv1 VARBINARY(256);

				DECLARE word_delimiter VARCHAR(12);
				DECLARE word_position INT;
				
				DECLARE current_word VARCHAR(255); 
				DECLARE field_length, partial_length, lowest_distance INT;
				
				
				
# ADDED:
DECLARE maxOffset int;
				
				

DECLARE currPos INT;
DECLARE matchCnt INT;
DECLARE wrkPos INT;
DECLARE word_offset INT;
DECLARE word_pos INT;
DECLARE word_dist INT;
DECLARE current_word_offset INT;
DECLARE current_word_char CHAR;
DECLARE current_word_pos INT;
DECLARE current_word_dist INT;
DECLARE returnVar FLOAT;
				
	
# ADDED:
SET maxOffset = 2;	
				
				
				
				
				SET field_length	= CHAR_LENGTH(db_field);
				SET partial_length	= 0; # counter that tells when we reach the end of db_field
				SET word_delimiter	= ' ';
				SET word_len 		= CHAR_LENGTH(word);
				SET word_position	= 0;

				
				SET maxOffset = IFNULL(maxOffset,2);
				SET word_offset = 0, current_word_offset = 0, matchCnt=0, currPos=0;
				
				SET lowest_distance = 1000;

				
loop_over_words: WHILE partial_length <= field_length DO
					
						# Select the subsequent word from db_field:

						# The following WHILE is just a performance optimization: do not calculate the levenshtein distance 
						# when the difference between the search string word and the current word is higher than the tolerance 
						# value. In this case the levenhstein distance is always greater than the tolerance value. 
						
			select_word: REPEAT
							
							SET word_position = word_position + 1;
							SET current_word = REPLACE(SUBSTRING(SUBSTRING_INDEX(db_field, word_delimiter, word_position), CHAR_LENGTH(SUBSTRING_INDEX(db_field, word_delimiter, word_position -1)) + 1), word_delimiter, '');				
							SET current_word_len = CHAR_LENGTH(current_word);
							SET partial_length = partial_length + current_word_len + 1;     # +1 is for spaces ' '	

# if debug:							
# INSERT INTO debug1 (db_field, partial_length,field_length, current_word, word, current_word_len, word_len, word_tolerance) VALUES (db_field, partial_length,field_length, current_word, word, current_word_len, word_len, word_tolerance);
			
							IF partial_length >= field_length THEN
							
								IF 	ABS(word_len - current_word_len) > word_tolerance THEN
									LEAVE loop_over_words;
								END IF;
							
								LEAVE select_word;
							END IF;
		
						UNTIL 
							ABS(word_len - current_word_len) <= word_tolerance
						END REPEAT;
						
						IF word = current_word THEN
							RETURN 0; # this is the best case (distance = 0)
						END IF;
						
						
					#	Start the Levenshtein algorithm
					

						SET distance = 0;

														
						IF word_len = 0 THEN
							SET lowest_distance = current_word_len;
						ELSEIF current_word_len = 0 THEN
							SET lowest_distance = word_len;
					
						ELSE

calc_distance:				WHILE ( (currPos + word_offset < word_len) AND (currPos + current_word_offset < current_word_len) ) DO

								SET wrkPos = currPos + 1;

								IF ( SUBSTRING(word, wrkPos+word_offset, 1) = SUBSTRING(current_word, wrkPos + current_word_offset, 1) ) THEN
									SET matchCnt = matchCnt + 1;
								ELSE

									SET word_offset = 0;
									SET current_word_offset = 0;
									SET word_char = SUBSTRING(word, wrkPos, 1), current_word_char = SUBSTRING(current_word, wrkPos, 1);
									SET word_pos = LOCATE(current_word_char, word, wrkPos)-1, current_word_pos = LOCATE(word_char, current_word, wrkPos)-1;
									SET word_dist = word_pos - currPos, current_word_dist = current_word_pos - currPos;

									IF (word_pos > 0 AND (word_dist <= current_word_dist OR current_word_pos < 1) AND word_dist < maxOffset) THEN

										SET word_offset = (word_pos-wrkPos) + 1;

									ELSEIF (current_word_pos > 0 AND (current_word_dist < word_dist OR word_pos < 1) AND current_word_dist < maxOffset) THEN
										SET current_word_offset = (current_word_pos-wrkPos) + 1;
									END IF;

								END IF;

								SET currPos = currPos + 1;
								
					
				# ADDED:
														
								SET temp_distance = ((currPos + word_offset) + (currPos + current_word_offset)) / 2 - matchCnt;
								IF (temp_distance > word_tolerance) THEN
									
									SET distance = ROUND(temp_distance);
									LEAVE calc_distance;
								END IF;
								
				# END ADDED														
								

							END WHILE;
						
							SET distance = ROUND((word_len + current_word_len) / 2 - matchCnt);
						

						
						END IF;
					
					#	End the Levenshtein algorithm for the current word and compare
					#	its distance with the lowest word distance previously found:
					
						IF distance < lowest_distance THEN 
							SET lowest_distance = distance; 
						END IF;
					
				
# If debug:					
# INSERT INTO debug1 (db_field, partial_length,field_length, current_word, word, current_word_len, word_len, word_tolerance) VALUES (db_field, partial_length,field_length, current_word, word, current_word_len, word_len, word_tolerance);
				
				

				END WHILE;		
			
				RETURN lowest_distance;

			END

			# uncomment this only for queries from the command line:
			# $$
			# DELIMITER ;
		");
	}
	
	
	function sanitizeRegexp($str) {

		// Remove all regex special characters from the string.
		// . \ + * ? [ ^ ] $ ( ) { } = ! < > | : -
		// A good reason for doing that: https://stackoverflow.com/questions/18901704/mysql-regexp-word-boundaries-and-double-quotes
		
		$pattern = '/[\.\\\+\*\?\[\^\]\$\(\)\{\}\=\!\<\>\|\:\-]/';
		
		return preg_replace($pattern, '', $str);
	}
	
	
	
	public function getProducts($data = array()){
	
		$this->load->model('catalog/product');
	
		// Multi Store Support: get where the current search comes from
		if (!isset($data['store_id'])) {
			$data['store_id'] = (int)$this->config->get('config_store_id'); 
		}
		
		// Multi Language Support: 
		if (!isset($data['language_id'])) {
			$data['language_id'] = (int)$this->config->get('config_language_id'); 
		}
			
		// ***************************************************************************************************************************
		// BUG FIX       BUG FIX       BUG FIX       BUG FIX       BUG FIX       BUG FIX       BUG FIX       BUG FIX       BUG FIX  
		// ***************************************************************************************************************************
		// The flag $data['filter_description']) MUST BE explicitly set on "true" or "false". That's because this flag is not
		// always set on true or false, some scripts don't set it at all.
		
		// See the array $current_search_options.
		// Since the array $current_search_options must be identical for a given set of results (we make an MD5 from that array), 
		// we cannot have two different values for $data['filter_description']) (empty string '' and false), even though the meaning is the same.
 		// Otherwise when the cache is enabled, the extension will create two identical cache files with a different MD5.
		
		if ( $data['filter_description'] === 0 || $data['filter_description'] === '' || $data['filter_description'] == 'false' ) {
			$data['filter_description'] = false;
		}
		if ( ($data['filter_description']) == 1 || $data['filter_description'] == 'true' ) {
			$data['filter_description'] = true;
		}
		// ***************************************************************************************************************************
		// ***************************************************************************************************************************
		


		$debug_output	= '';
		
		if ( (ADSMART_SRC_DEBUG || ADSMART_SRC_DEBUG_SHOW_SQL || ADSMART_SRC_SPEED_TEST) && $this->config->get('adsmart_search_enable_search_cache') ) {
			$debug_output .=  ' <br><br>***** DISABLE THE CACHE TO DISPLAY ALL THE DEBUG MESSAGES. ****<br><br>'; 
		}
		
		
		if (ADSMART_SRC_SPEED_TEST) {
			$mtime = microtime();
			$mtime = explode(" ",$mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;
		}

















		// Get the array $relevance
		// Structure:
		// $relevance['name'], $relevance['model'] ecc.
		$relevance = $this->config->get('adsmart_search_relevance');
	
		// Treat the field description separately. This field could not be present in the array $relevance
		// but users might have checked the checkbox "Search in descriptions". In this case we have a new 
		// field to search within but there is no relevance for it. The solution is to add to the array
		// relevance a new element and a relevance value of

		if ( !isset($relevance['description']) && ($data['filter_description']) == true ){
			
			// Calculate the relevance value in this way:
			// 1) find the minimum relevance value,
			// 2) divide its value by 2 (see the file adsmart_search.tpl to know how relevance values are calculated)
			
			// Now the field description has a relevance value equal to the value it would have if it
			// was on the last position of the field list.
			$relevance['description'] = min($relevance)/2;
		}
		
		// If the field description is set from the back end but unchecked from the frontend, we exclude it
		if ( isset($relevance['description']) && ($data['filter_description']) == false ){
			unset($relevance['description']);
		}

		
		// These variables set the weight for each type of word.
		// See also the variables $num_exact_words_found, $num_partial_words_found and $word_position_weight (Section "Relevance calculation")
		// The relevance of the word in the target string depends on its position, on the 
		// number of words found and if it matches the original word or is a variation of it (plural, singular, misspelled)
		$exact_match_ratio		= 1.2;
		$word_ratio				= 1;
		$inflection_ratio		= $word_ratio * 0.8;
		$partial_word_ratio		= $word_ratio * 0.6;
		$misspelled_word_ratio	= $word_ratio * 0.6;
		$first_word_weight = 1;
		$word_position_weight_ratio = 1.3; // If the first word weight is 1, the second will be 1/1.3, the third (1/1.3)/1.3 and so on
		
		// before v4.0, $include_misspellings:
		// 		Flag that tells if misspellings are enabled (exact matches don't include misspellings)
		// 		$include_misspellings = $this->config->get('adsmart_search_exact_broad') == 'broad' &&  $this->config->get('adsmart_search_include_misspellings');
		// From v4.0
		$include_misspellings = $this->config->get('adsmart_search_include_misspellings');
		
		// The list of available fields is retrieved from the array keys "relevance":
		$i=0;
		$product_fields = array();
		
		if (isset($relevance)){

			foreach ($relevance as $key => $rel) {
				$product_fields[$i]['field_name'] = $key;
				$i++;	
			}
			unset($i);
		

	
			//$product_fields will have this structure:
			//
			// $product_fields[$i]['field_name']
			// $product_fields[$i]['db_field_name']
			//
			foreach ($product_fields as $i => &$product_field){  // <-- assignment by reference
				
				
				$field_name = $product_field['field_name'];

				
				if ($product_field['field_name'] == 'name'				|| 
					$product_field['field_name'] == 'tag'				|| 
					$product_field['field_name'] == 'description' 		||
					$product_field['field_name'] == 'meta_keyword'		||
					$product_field['field_name'] == 'meta_description'	){
					
					$prefix = 'pd'; // db table product_description
				}
				else if ($product_field['field_name'] == 'manufacturer_name'){

					$prefix = 'm'; // db table manufacturer
					$field_name = 'name'; // the db field for the product field "manufacturer_name" is "name"
				
				}
				
				// Note: these fields must be referred to the current language
				// -----------------------------|-------------------------------|-------------	
				//		Table					|	Fields						|	Example
				// -----------------------------|-------------------------------|-------------
				//	attribute_group_description
				//									- language_id
				//									- name							"Shape"

				//	attribute_description
				//									- language_id
				//									- name							"Round"

				//	product_attribute
				//									- language_id
				//									- text (attribute description)	"Description for the round attribute"
				
				else if ( $product_field['field_name'] == 'attribute_group_name' ) {
				
					$prefix		= 'agd'; // db table_attribute_group_description
					$field_name = 'name'; // db field name
				}	
				
				else if ( $product_field['field_name'] == 'attribute_name' ) {
				
					$prefix		= 'ad'; // db table attribute_description
					$field_name	= 'name'; // db field name
				}
				
				else if ( $product_field['field_name'] == 'attribute_description' ) {
				
					$prefix		= 'pa'; // db table product_attribute
					$field_name	= 'text'; // db field name
				}
				
				else if ( $product_field['field_name'] == 'option_name' ) {
				
					$prefix		= 'od'; // db table option_description
					$field_name	= 'name'; // db field name
				}
				
				else if ( $product_field['field_name'] == 'option_value' ) {
				
					$prefix		= 'ovd'; // db table option_value_description
					$field_name	= 'name'; // db field name
				}
				
				else if ( $product_field['field_name'] == 'category_name' ) {
				
					$prefix		= 'cd'; // db table category_description
					$field_name	= 'name'; // db field name
				}
				
				else {
					$prefix = 'p'; // db table product (for the fields model, sku, upc, ean, jan, isbn, mpn, location)
				}
													

				$product_field['db_field_name'] = $prefix . '.' . $field_name;
			}
			
			// From the PHP manual, foreach and assignment by reference (&):
			// Reference of a $value and the last array element remain even after the foreach loop. 
			// It is recommended to destroy it by unset(). 

			unset($product_field);
		}

		
		
	
		// Get the full text minimum word length:
		$query = $this->db->query("SHOW VARIABLES LIKE 'ft_min_word_len'");
		$result = $query->row;
		$full_text_min_word_len = intval($result['Value']);
		
		

		// It's better to apply these functions just one time than doing it every time we need  $data['filter_name']
		if (!empty($data['filter_name'])) {
		
			// clean the search string from extra spaces and tabs:
			$data['filter_name'] = preg_replace('!\s+!', ' ', $data['filter_name']); // \s: spaces and tabs 
			
			// db-escape and convert uppercase to lowercase letters for the filter name 
			$data['filter_name'] =	$this->db->escape(utf8_strtolower($data['filter_name']));

			$search_string_to_words				= explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_name'])));
			
			$num_of_words_in_search_string		= count($search_string_to_words);
			$last_search_string_word			= end($search_string_to_words);	
			$unique_words_without_inflections	= array_unique($search_string_to_words);

						
			// Array of inflections
			$inflection = array();
			if ($this->config->get('adsmart_search_include_plurals')){

				$inflect = new inflect();
				foreach ($unique_words_without_inflections as $word) {
				
					$singularized	= $inflect->singularize($word);
					$pluralized		= $inflect->pluralize($word); 
					
					// we don't know if the user types the singular or plural form of a word, 
					// but we know that the functions singularize() and pluralize() return the same input
					// word if it is already in the singular/plural form 
					
					if ($word == $singularized) {
						$inflection[$word] = $pluralized; 
					}
					else {
						$inflection[$word] = $singularized;
					}																								
				}	
			}
			
			
			// $word_length: array of word lengths (the purpose of this array is avoiding to calculate many times the word lengths):
			$word_length = array();
			foreach ($unique_words_without_inflections as $word) {
				$word_length[$word] = mb_strlen($word,'UTF-8');
			}
			if ($this->config->get('adsmart_search_include_plurals')){
				
				// add the inflection lengths to the array word_length
				foreach ($inflection as $word) {
					$word_length[$word] = mb_strlen($word,'UTF-8');
				}
			}
			
			
			


			// Exact match string to be used with the MATCH AGAINST operator IN BOOLEAN MODE 
			// Exact matches should always be on top of results.
			// Example: 
			
			//  keywords: Apple iPhone 
			
			// - without any options enabled				: +(apple) +(iphone*)
			// - if inflections enabled						: +(apple <(apples)) +(iphone* <(iphones*))
			// - if partial words enabled					: +(apple*) +(iphone*)
			// - if partial words and inflections enabled	: +(apple* <(apples*)) +(iphone* <(iphones*))
			
			$fulltext_exact_match_boolmode = '';
			foreach ($unique_words_without_inflections as $word) {
				
				$include_partial_words = $word_length[$word] >= $this->config->get('adsmart_search_partial_word_length') && $this->config->get('adsmart_search_include_partial_words') == 1;
				
				
				// From the v. 4.1.0 the following "if" fixes a problem with full indexing and exact matches. 
				// For example, if we have the product with product name: 
				// Htc Touch HD    and ft_min_word_len = 3
				// The statement:
				// MATCH (pd.name) AGAINST ('(+(htc) +(touch) +(hd*) ) ' IN BOOLEAN MODE ) 
				// returns an empty result because the "+" sign indicates that a word must be present but since ft_min_word_len=3, that word
				// will never be included in the index and the query will return an empty result list (the word HD is required by the + but not found in the index).
				// The solution is to remove the + for words with a length shorter than the value set in the variable ft_min_word_len:
				
				// MATCH (pd.name) AGAINST ('(+(htc) +(touch) (hd*) ) ' IN BOOLEAN MODE )    OK
						

				if ($word_length[$word] < $full_text_min_word_len) {
					$required_op = '';
				} else {
					$required_op = '+';
				}
				
				$fulltext_exact_match_boolmode .= $required_op.'('.$word;

				// the last word must always contain the asterisk (for the Live Search)
				if ( $include_partial_words OR $word == $last_search_string_word ) {
				
					$fulltext_exact_match_boolmode .= '*';
				}
				
				if ($this->config->get('adsmart_search_include_plurals') && isset($inflection[$word]) ) {
					
					$fulltext_exact_match_boolmode .= ' <('.$inflection[$word];
					
					if ( $include_partial_words OR $word == $last_search_string_word ) {
				
						$fulltext_exact_match_boolmode .= '*';
					}
					$fulltext_exact_match_boolmode .= ')';	
				}
				
				$fulltext_exact_match_boolmode .= ') ';
			}

			
			// The array $words contains the string words and their inflections without repetitions		
			$words = array_unique(array_merge($unique_words_without_inflections, $inflection));
			// the array $inflection contains keys which are not numbers, convert keys to numbers with array_values() 
			// (it avoids foreach problems when we need to work with the array index)
			$words = array_values($words);
			
			
			 // $tolerance: associative array where the key is a search string word and the value is an integer value
			if ( $include_misspellings ){
			
				// create a MySql misspellings function if it doesn't exists:
				$this->sql_create_misspellings();

				$tolerance = array();
				$tolerance_perc = $this->config->get('adsmart_search_misspelling_tolerance');
			
				foreach ($unique_words_without_inflections as $word) {	
					// convert the tolerance percentage to integer
					$tolerance[$word] = intval(( $word_length[$word] * $tolerance_perc)/100);
				}
			}


		}
		
		$is_live_search = (isset($data['live_search']) && $data['live_search'] == true) ? true : false;
		
																													if (ADSMART_SRC_DEBUG){
																														$debug_output .= '<br>Is live Search request: ' . ($is_live_search == true ? 'yes' : 'no') .'<br>';
																														$debug_output .= '<br>Search string words (array): '; if (isset($search_string_to_words)) foreach ($search_string_to_words as $word) { $debug_output .=  ' - '.$word; }
																														$debug_output .= '<br><br>Search string words - unique (array): '; if (isset($unique_words_without_inflections)) foreach ($unique_words_without_inflections as $word) { $debug_output .=  ' - '.$word; }
																														$debug_output .= '<br><br>Inflections: (array): '; if (isset($inflection)) foreach ($inflection as $word) { $debug_output .=  ' - '.$word; }																														
																														$debug_output .= '<br><br>Word lengths (array): '; if (isset($word_length)) foreach ($word_length as $word => $length) { $debug_output .=  ' ___ '.$word.' => '.$length; }	
																														$debug_output .= '<br><br>';
																													}





		$sql_begin_string = "SELECT ";
		

		$sql_relevance_score = "";  // it will contain a match-against statement in case of sorting by relevance
		if ( !empty($data['filter_name']) && !empty($product_fields) ) {

			// If the default sort order is by relevance, calculate the relevance score that will be used by the clause ORDER BY
			// This can be done only when FULL TEXT indexes are enabled
 
			if ( $data['sort'] == 'relevance' && $this->config->get('adsmart_search_index_db') ) {
			
				$implode = array();
				
				// RELEVANCE COMPUTATION ON THE SQL STRING. It will be created a new string to be prepended to the main string "$sql" 
				//											with the necessary match() against() statements to compute a reliable
				//											relevance value.

				
				foreach ($product_fields as $product_field){
					
					$db_field = $product_field['db_field_name'];
					$rel = $relevance[$product_field['field_name']]; // relevance

					
					// Relevence for exact matches - they should always be on top of results (relevance is multiplied by 10)
					// NOTE THAT THE VARIABLE $fulltext_exact_match_boolmode CONTAINS OPERATORS + AND * WHICH REQUIRE THE 
					// MODIFIER "IN BOOLEAN MODE"
					$implode[] =  "(" . 10 * $rel * $exact_match_ratio  . " *  ( MATCH (".$db_field.") AGAINST ('".$fulltext_exact_match_boolmode."' IN BOOLEAN MODE) ) )";

					
					// The following "if" is an attempt to refine the relevance score computed by MySQL
					
					// $word_position_weight: a variable whose value is higher for words at the beginning of the search string
					$word_position_weight = $first_word_weight;

					// Relevance for regular words and inflections
					foreach ($words as $word) {
					
						// Multiply the field relevance set from the the "Product Field List" is in the Control Panel of
						// Advanced Smart Search (under the tab "General Search Options") with the relevance computed by
						// the MySQL "match() against()" statement, in order to give more weight to results whose keywords
						// are contained in the product fields on top of the Product Field list.
						// Then alter the relevance value with the ratios given for each word type (regular word, inflection)
						// to further improve the relvance accuracy:
						
						// NOTE: the array $words contains both regular words and inflections. The first n/2 elements are the regular words,
						// the second n/2 are inflections and the two groups follow the same order of the original search string ($data['filter_name']).
						// This thing allows to use the variable $word_position_weight to lower the weight of words as the loop advances.
						
						if (in_array($word,$inflection)) {
							$actual_relevance = $rel * $inflection_ratio * $word_position_weight;
						}
						else {
							$actual_relevance = $rel * $word_ratio * $word_position_weight;
						}
						
						
						$include_partial_words = $word_length[$word] >= $this->config->get('adsmart_search_partial_word_length') && $this->config->get('adsmart_search_include_partial_words') == 1;
						
						if ( $include_partial_words ) {
						
							// Add a weight if the option "Include partial words" is enabled 
							// The MATCH() AGAINST() operator can only detect words "starting with" (operator "*")
							$partial_word_weight = 	" + " . $actual_relevance ." * " . $partial_word_ratio . " * ( MATCH (".$db_field.") AGAINST ('".$word."*' IN BOOLEAN MODE ) ) ";
						}
						else {
							$partial_word_weight = "";
						}
						
						// filter words (by length) that cannot be searched by the operator MATCH() AGAINST()
						if ( $word_length[$word] >= $full_text_min_word_len ) {
						
							// Also rely on the relevance score (is an accurate floating point value) computed by MySQL, which is only available 
							// when the operator MATCH AGAINST doesn't include the modifier IN BOOLEAN MODE
							$implode[] =  "(" . $actual_relevance . " *  ( MATCH (".$db_field.") AGAINST ('".$word."'  ) ) " . $partial_word_weight . " )";
						}
						
						// If the following line is enabled instead of the preceding one, no relevance will be computed by MySQL. In boolean mode, the 
						// relevance score will be just 1, if there is at least a match, or 0, if there are no matches. Without a system (that would  
						// degrade search speed!) capable to count words within each product field and give a weight to each word, the values 0 and 1 are  
						// not enough for our purposes.
						
				//		$implode[] =  "(" . $actual_relevance . " *  ( MATCH (".$db_field.") AGAINST ('".$word."*' IN BOOLEAN MODE ) ) )";	
						
						
						// slightly lower word weight:
						$word_position_weight /= $word_position_weight_ratio; 
					}

					
					// Relevance for misspellings
					if ( $include_misspellings ){

						foreach ($unique_words_without_inflections as $word) {	
					
							if ($tolerance[$word] > 0) {
							
								// The if statememt prevents a division by 0 error when the function misspellings() returns 0. The MySQL optimizer ensures that the 
								// function misspelling() will be executed just once even if there are two calls.
								// A division by 0 might return a result list with less rows. Rows involved in the division by 0 are words without misspellings and would not be displayed!
								$implode[] = " ( IF ( misspellings('" . $word . "', " . $db_field . ", " . $tolerance[$word] . ") = 0, 0, ". $rel . " * " . $misspelled_word_ratio ." / misspellings('" . $word . "', " . $db_field . ", " . $tolerance[$word] . ") )) ";		
								
								// This query might return a "division by zero" error:
								// $implode[] = " (". - $rel * $misspelled_word_ratio . " * ( misspellings('".$word."', ".$db_field.", ".$tolerance[$word].") )) ";	
							}
						}
					}
				}

				if ($implode) {
					$sql_relevance_score .= implode(" + ", $implode). " AS RELEVANCE,";
				}
			}
		}
		

		
		$sql = " 		p.product_id, 
						(SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, 
						(SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$data['customer_group_id'] . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, 
						(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$data['customer_group_id'] . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special
				"; 
		

		if (version_compare(VERSION, '1.5.4.1', '<=')) {
	
			// for OC <= 1.5.4.1
			if ( !empty($data['filter_category_id']) || isset($relevance['category_name']) ) {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c  LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = p2c.product_id)";			
			} else {
				$sql .= " FROM " . DB_PREFIX . "product p";
			}
		}
		else {
		
			// for OC >= 1.5.5
			if ( !empty($data['filter_category_id']) || isset($relevance['category_name']) ) {
				if (!empty($data['filter_sub_category'])) {
					$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";			
				} else {
					$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
				}
			
				if (!empty($data['filter_filter'])) {
					$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
				} else {
					$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
				}
			} else {
				$sql .= " FROM " . DB_PREFIX . "product p";
			}
		}
		
				
		// Category name
		if ( isset($relevance['category_name']) ) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "category_description cd ON (cd.category_id = p2c.category_id) ";
		}




		
		// If the fields:
		//	- manufacturer_name 
		//	- attribute_group_name
		//	- attribute_name
		//	- attribute_description
		//  - option_name
		//  - option_value
		// are enabled, join the required tables along with the others:
		
		// Manufacturer
		if ( isset($relevance['manufacturer_name']) ){	
			$sql .= " LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id)";
		}
		
		// Attributes
		if ( isset($relevance['attribute_group_name']) || isset($relevance['attribute_name']) || isset($relevance['attribute_description']) ){		
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute pa ON (p.product_id = pa.product_id) LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id)";
		}
		
		// Options
		if ( isset($relevance['option_name']) ){
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_option po ON (p.product_id = po.product_id) LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id)";
		}
		if ( isset($relevance['option_value']) ){	
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (p.product_id = pov.product_id) LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id)";
		}
		
		// end
				
		
		
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$data['language_id'] . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$data['store_id'] . "'";
		
		
		// Search within the fields translated in the current language:
		// Note: agd.language_id, ad.language_id, pa.language_id, od.language_id and
		// ovd.language_id could be null when the LEFT JOIN doesn't find rows in the 
		// left table that match with the right table
		
		// For attributes:
		if ( isset($relevance['attribute_group_name'])  ){		
			$sql .= " AND (agd.language_id = pd.language_id OR agd.language_id IS NULL)";
		}
		if ( isset($relevance['attribute_name']) ){		
			$sql .= " AND (ad.language_id = pd.language_id OR ad.language_id IS NULL)";	
		}
		if ( isset($relevance['attribute_description']) ){		
			$sql .= " AND (pa.language_id = pd.language_id OR pa.language_id IS NULL)";
		}
		
		// For options:
		if ( isset($relevance['option_name']) ){	
		 $sql .= " AND (od.language_id = pd.language_id OR od.language_id IS NULL)"; 
		}
		if ( isset($relevance['option_value']) ){	
			$sql .= " AND (ovd.language_id = pd.language_id OR ovd.language_id IS NULL)";
		}

		// end
		
		
		
		
	
		if (version_compare(VERSION, '1.5.4.1', '<=')) {
	
			// For OC <= 1.5.4.1
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$implode_data = array();
					
					$implode_data[] = (int)$data['filter_category_id'];
					
					$this->load->model('catalog/category');
					
					$categories = $this->model_catalog_category->getCategoriesByParentId($data['filter_category_id']);
										
					foreach ($categories as $category_id) {
						$implode_data[] = (int)$category_id;
					}
								
					$sql .= " AND p2c.category_id IN (" . implode(', ', $implode_data) . ")";			
				} else {
					$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				}
			}	
		}
		else {
		
			// for OC >= 1.5.5
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";	
				} else {
					$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";			
				}	
			
				if (!empty($data['filter_filter'])) {
					$implode = array();
					
					$filters = explode(',', $data['filter_filter']);
					
					foreach ($filters as $filter_id) {
						$implode[] = (int)$filter_id;
					}
					
					$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";				
				}
			}	
		}
		
		
		
		
	
		// the variable $like_detected tells if the query string contains any LIKE operator in the block WHERE. This flag
		// will be used to choose the pagination method (by PHP if there is at least one LIKE,
		// and by MySQL if no LIKEs found.
		$like_detected = false;
		

		if ( !empty($data['filter_name']) ) {
			
			$sql .= " AND (";

			if (empty($product_fields)){
				// round brackets cannot be empty, print a "false" within them if no product fields
				// are selected from the control panel
				$sql .= " FALSE ";
			}
			else {

				# By default Opencart performs a search only for the fields:
				// SKU, model , UPC, EAN, JAN ISBN, MPN
				
				# Field description:
				// there is a filter on the search page that enables the search in the field description
			
				# Manufacturer
				// with /catalog/manufacturer.php we can filter products by manufacturer ID
			
				# What I did:
				// - Embedded the field description search option in Adsmart Search. This feature enables the search
				//   within the description field also for the ajax search
				
				// - Search for products not by a given manufacturer id but look for the product name 
				//   inside the manufacturer name (the filter_name must match with the manufacturer name)



				foreach ($product_fields as $product_field) {
				

					$db_field = $product_field['db_field_name'];
				
					if (!empty($data['filter_name'])) {
						
						$implode_like = array();
						$implode_match = array();


						
				// Exact/broad match conjunction

						if ($this->config->get('adsmart_search_exact_broad') == 'exact'){
							$conjunction = " AND ";
						}
						else {
							$conjunction = " OR ";	
						}


						if ($this->config->get('adsmart_search_algorithm') == 'default'){
					
							// From the version 3.2.1 when the search aglorithm is set to "default",
							// it always use the "LIKE" statement. 
					
							foreach ($unique_words_without_inflections as $word) {

								$sql_match ="";
								
								$include_partial_words = $word_length[$word] >= $this->config->get('adsmart_search_partial_word_length') && $this->config->get('adsmart_search_include_partial_words') == 1;

				// "LIKE" WORD BOUNDARIES	(		%		)		
								if ( $include_partial_words ) {
									
									$leading_partial_word_operator = "%";
								}
								else {
									$leading_partial_word_operator = "";
								}
								
								
								if ( $include_partial_words OR $word == $last_search_string_word ) {
									
									$trailing_partial_word_operator = "%";
								}
								else {
									$trailing_partial_word_operator = "";
								}
								

		// EXACT MATCH
								if ($this->config->get('adsmart_search_exact_broad') == 'exact'){
						
					// "REGEX" WORD BOUNDARIES	( [[:<:]]   and   [[:>:]]   )		
									$trailing_regex_word_boundary = "[[:>:]]";  
		
						
					// Partial words			
									if ( $include_partial_words ) {
										$regex_any_char = ".*";
									} else {
										$regex_any_char = "";
									}
							
									$sql_match = "LCASE(".$db_field.") REGEXP '[[:<:]]" . $this->sanitizeRegexp($regex_any_char . $word . $regex_any_char) . $trailing_regex_word_boundary . "' ";
					
					
					// Plurals		
									if ($this->config->get('adsmart_search_include_plurals')){
									
										$sql_match .= " OR LCASE(".$db_field.") REGEXP '[[:<:]]" . $this->sanitizeRegexp($regex_any_char . $inflection[$word] . $regex_any_char) . $trailing_regex_word_boundary . "' ";
									}
						
						
					// Misspellings
									if ( $include_misspellings ){
																														if (ADSMART_SRC_DEBUG){									
																															$debug_output .=  '<br> word: <b>'.$word. '</b> - misspelling tolerance %'.$tolerance_perc.' -  tolerance: ~ '.$tolerance[$word].' characters<br><br>';		
																														}				
										if ($tolerance[$word] > 0) {
											$sql_match .= " OR misspellings('".$word."', ".$db_field.", ".$tolerance[$word].") BETWEEN 0 AND ".$tolerance[$word];		
										}	
									}	
								}

															
		// BROAD MATCH			
								if ($this->config->get('adsmart_search_exact_broad') == 'broad'){

												
					// "REGEX" WORD BOUNDARIES	( [[:<:]]   and   [[:>:]]   )
							
									if ( $word == $last_search_string_word ) {
										$trailing_regex_word_boundary = "";   
									} else {
										$trailing_regex_word_boundary = "[[:>:]]";  
									}				
								
								
					// Partial words			
									if ( $include_partial_words ) {
									
										$sql_match = "LCASE(".$db_field.") LIKE '". $leading_partial_word_operator . $word . $trailing_partial_word_operator . "' ";
									
										if ($this->config->get('adsmart_search_include_plurals')){
										
											$sql_match .= "OR LCASE(".$db_field.") LIKE '". $leading_partial_word_operator . $inflection[$word] . $trailing_partial_word_operator . "' ";
										}
										
									} else {
									
										//		$sql_match = "LCASE(".$db_field.") LIKE '". $leading_partial_word_operator . $word . $trailing_partial_word_operator . "' ";
										$sql_match = "LCASE(".$db_field.") REGEXP '[[:<:]]". $this->sanitizeRegexp($word) . $trailing_regex_word_boundary . "' "; 							
									}
						
						
					// Plurals				
									if ($this->config->get('adsmart_search_include_plurals')){
									
										$sql_match .= "OR LCASE(".$db_field.") REGEXP '[[:<:]]". $this->sanitizeRegexp($inflection[$word]) . $trailing_regex_word_boundary . "' "; 
									}
									
									
					// Misspellings
									if ( $include_misspellings ){
																														if (ADSMART_SRC_DEBUG){									
																															$debug_output .=  '<br> word: <b>'.$word. '</b> - misspelling tolerance %'.$tolerance_perc.' -  tolerance: ~ '.$tolerance[$word].' characters<br><br>';		
																														}				
										if ($tolerance[$word] > 0) {
											$sql_match .= " OR misspellings('".$word."', ".$db_field.", ".$tolerance[$word].") BETWEEN 0 AND ".$tolerance[$word];		
										}	
									}
								}	
								
								
								
								if (!empty($sql_match)) {
								
									$implode_like[] =  " ( ".$sql_match." )" ;	
								}
							}
							
							$like_detected = true;
						}
						

						
						// MATCH() AGAINST() looks for entire words only or for words "starting with"
						// The feature "match words starting with" is only available when the search algorithm is set to 
						// "FAST" and it automatically disables the partial word matching based on the LIKE operator.
						
						// Note: the match statement is case insensitive.
						
						else if ( $this->config->get('adsmart_search_index_db') ) {
					
							$weighted_words = '';
							$misspelling = "";
							
							foreach ($words as $word) {
							
								// For exact matches this list of words ($weighted_words) must be excluded 
								if ($this->config->get('adsmart_search_exact_broad') == 'broad'){ 
								
									// Process the current word only if its length is greater than the FULL TEXT minimum word length
									if ( $word_length[$word] >= $full_text_min_word_len ) {
															   
										$include_partial_words = $word_length[$word] >= $this->config->get('adsmart_search_partial_word_length') && $this->config->get('adsmart_search_include_partial_words') == 1;
									
										// lower the weight of inflections by adding the operator "<"
										if ( in_array($word,$inflection) ) {
											$weighted_words .= " <(".$word;
										}
										else {
											$weighted_words .= " (".$word;
										}
										
										// If option "partial words" enabled (The MATCH() AGAINST() operator can only detect words "starting with" - operator "*")
										// OR we are processing the last word of of the search string (for the Live Search autosuggest)										
										
										if ( $include_partial_words OR $word == $last_search_string_word ) {
											$weighted_words .= "*";	
										}

										$weighted_words .= ") ";
									}	
								}
								
								
			// Include misspellings
								if ( $include_misspellings ){

									if ( in_array($word, $unique_words_without_inflections) ) {

																													if (ADSMART_SRC_DEBUG){									
																														$debug_output .=  '<br> word: <b>'.$word. '</b> - misspelling tolerance %'.$tolerance_perc.' -  tolerance: ~ '.$tolerance[$word].' characters<br><br>';		
																													}				
										if ($tolerance[$word] > 0) {
											$misspelling = " OR misspellings('".$word."', ".$db_field.", ".$tolerance[$word].") BETWEEN 0 AND ".$tolerance[$word];		
										}
									}
								} 	
							}

							// Exact matches are catched by the string variable $fulltext_exact_match_boolmode. THIS VARIABLE CONTAINS
							// OPERATORS + AND * WHICH REQUIRE THE MODIFIER "IN BOOLEAN MODE"
																				
							$sql_match = " MATCH (".$db_field.") AGAINST ('(".$fulltext_exact_match_boolmode.") ".$weighted_words."' IN BOOLEAN MODE ) " . $misspelling;
							//	$sql_match = " REGEXP '[[:<:]]" . $this->sanitizeRegexp($word) . "[[:>:]]' "; // [[:>:]] are the word-boundary markers
	

							$implode_match[] = "( ".$sql_match." )";										
						}	
					

					
						if ($implode_like) {
							$sql .= "" . implode($conjunction, $implode_like) . "";
						}
						
						if ($implode_like && $implode_match) {
							$sql .= " OR "; 
						}
					





						if ($implode_match) {
							$sql .= "" . implode($conjunction, $implode_match) . "";
						}
				



						$sql .= " OR ";
					}

				} // end foreach
				$sql = substr($sql, 0, -4); // cut the last unncesessary " OR " from the sql string
			}


			$sql .= ")";
		}
		

		if ( !empty($data['filter_tag']) ) {

			$sql .= " AND ( pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%' ) ";
		}
		
		

		
		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}
		
		if ($this->config->get('adsmart_search_hide_zeroqty_products')) {
			$sql .= " AND p.quantity > 0";
		}
		
		
		// The sql string built so far will be used to count the total number of products, it doesn't have to include
		// the clause ORDER BY and LIMIT
		$sql_count = $sql;
	

		
		$sql .= " GROUP BY p.product_id";
		
		
		
		// A handy way to know when we can paginate results by MySQL is to use the flag $we_can_paginate_by_mysql

		// Conditions that must be satisfied:
		// - the sort type is not by relevance		OR
		// - the sort type is by relevance AND there are not "LIKE" operators
		
		// Use the method registry->set() instead of a regular variable because this value will also be used outside of this method
		// (see the file /catalog/controller/module/adsmart_search.php, function update_search_cache(), array $data)
		
		$we_can_paginate_by_mysql = $data['sort'] != 'relevance' || ( $data['sort'] == 'relevance' && !$like_detected );

																											if (ADSMART_SRC_DEBUG){									
																												$debug_output .=  ($we_can_paginate_by_mysql)? '<b>Pagination: by MySQL</b>' : '<b>Pagination: by PHP</b>' . '<br>';		
																											}
		$sort_data = array(
		
			'relevance',
			
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);	
  
		if ( !empty($data['filter_name']) && !empty($product_fields) && isset($data['sort']) && in_array($data['sort'], $sort_data) ) {
					
			if ($data['sort'] == 'relevance' && $this->config->get('adsmart_search_index_db') && $we_can_paginate_by_mysql) {
				$sql .= " ORDER BY RELEVANCE";
			}
		
			elseif ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} 
			
			elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} 
		
			// check that the current sort type is not by relevance, there is already the first "if" that handles this sort type
			elseif ( $data['sort'] != 'relevance' ) {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} 
		else {
			$sql .= " ORDER BY p.sort_order";	

		}



		// The clause ORDER BY is active when pagination is made with MySQL:
		if ( $we_can_paginate_by_mysql ) {
		
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC, LCASE(pd.name) DESC";
			} else {
				$sql .= " ASC, LCASE(pd.name) ASC";
			}
		}
			
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ((int)$data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ((int)$data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			
		
			// If we cannot paginate results by MySQL, disable the LIMIT clause.
			// In some cases we need to use the clause LIKE instead of MATCH AGAINST (LIKE doesn't automatically sort results, 
			// we need to use the clause ORDER BY but it requires a query with a too complex set of conditions that can easily 
			// written in PHP once we have the full result list - see the relevance calculation).

			// When results will be ordered by relevance (by PHP) we can split the result list into pages.
			// You can find the pagination by searching this line:
			// $product_data = array_slice($product_data, $data['start'], $data['limit']);
			// at the bottom of this function.
		
			// The clause LIMIT is active when pagination is made with MySQL:
			if ( $we_can_paginate_by_mysql ) {

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
// This line is just for test purposes when the sorting by relevance is enabled, it speeds up the search:
//			$sql .= " LIMIT 0, 5";

		}
		
		$search_options = array('sid'	=> 'store_id',
								'lid'	=> 'language_id',
								'cgi'	=> 'customer_group_id',
								'fn'	=> 'filter_name',
								'ft'	=> 'filter_tag', 
								'ff'	=> 'filter_filter', 
								'fd'	=> 'filter_description', 
								'fci'	=> 'filter_category_id', 
								'fsc'	=> 'filter_sub_category',
								'fmi'	=> 'filter_manufacturer_id',
								'srt'	=> 'sort',
								'o'		=> 'order'
							);
					

		// Enable the options "start" and "limit" only when results are paginated by MySQL. When the pagination 
		// is made by PHP, the query will return the whole list of results which will be paginated in a second time.
		// Also the cache files contain the full set of results, not just a limited number of items like with the
		// MySQL pagination (LIMIT Clause). For this reason, cache files could get very big.

		// See also the file /catalog/controller/module/adsmart_search.php, function update_search_cache(), array $data.
								
		if ( $we_can_paginate_by_mysql ) {

			$search_options['strt'] 	= 'start';
			$search_options['l']		= 'limit';			
		}						

								
		
		
		$current_search_options = array();
		
				
		foreach ($search_options as $key => $search_option){
			if ( isset($data[$search_option]) ) {
			
				$current_search_options[$key] = (string)$data[$search_option];
			}
			else {
				$current_search_options[$key] = '';
			}
		}
		
/* DEBUG */ 	
// echo http_build_query($current_search_options); exit;
		
		// http_build_query() converts the associative array to string
		$hash = md5(http_build_query($current_search_options));
		
		
		// $there_are_filters is a flag that is "true" if there are filters posted from pages whose results
		// can be cached. Examples:
		//	|	FILTER							|	PAGE
		//  |-----------------------------------|---------------------------------------------------
		//  | $data['filter_name']				|	?route=product/search&search=x
		//  | $data['filter_tag']				|	?route=product/search&filter_tag=x (or &tag=x, for OC >= 1.5.5)
		//  | $data['filter_manufacturer_id']	|	?route=product/manufacturer/info&manufacturer_id=x
		
		$there_are_filters = !empty($data['filter_name']) || !empty($data['filter_tag']) || !empty($data['filter_manufacturer_id']);

		$product_data = array();
		
		if ($this->config->get('adsmart_search_enable_search_cache') && $there_are_filters ){
			// Get the products from the cache
			$product_data = $this->adsmart_search_get($hash, 'search_string');
			
			// together with $product_data, it will also be available the total number of products,
			// see the registry variable $this->registry->set('product_total', $data['product_total']) inside adsmart_search_get()

		
			// Pagination by PHP 
			if ( ! $we_can_paginate_by_mysql ) {
			
				if (isset($data['start']) && isset($data['limit'])){
					$product_data = array_slice($product_data, $data['start'], $data['limit']);
				}
			}	
		}


		// if the cache is empty:
		if ( !count($product_data) ) { 

			//******************************************************************************************************************************
			// PRODUCT COUNT   PRODUCT COUNT   PRODUCT COUNT   PRODUCT COUNT   PRODUCT COUNT   PRODUCT COUNT   PRODUCT COUNT   PRODUCT COUNT  
			// 											when the pagination is made by MySQL
		
			if ( $we_can_paginate_by_mysql )  {

				// We must also cut the beginning of that sting and replace it with this: "SELECT COUNT(DISTINCT p.product_id) AS total_products";
				// How I did: 
				// 1) find this string "ps.price ASC LIMIT 1) AS special" inside the variable $sql
				// 2) cut everything before that string, included that marker "ps.price ASC LIMIT 1) AS special"
				// 3) add COUNT to the beginning of the new string:
				
				$substrings = explode('ps.price ASC LIMIT 1) AS special', $sql_count);
				$substrings[0] = "SELECT COUNT(DISTINCT p.product_id) AS total_products";
				$sql_count = implode(' ', $substrings);				
				
				$query = $this->db->query($sql_count);
				
				// See the vQmod modification to the file controller/product/search.php where this registry var is used.
				$this->registry->set('product_total', $query->row['total_products']);				
			}
			
			//******************************************************************************************************************************
			//******************************************************************************************************************************
					
					
																													$debug_show_sql = '';
																													if (ADSMART_SRC_DEBUG_SHOW_SQL){
																														$debug_show_sql =  '<br><b>SQL Query</b>: <br><br>'.$sql_begin_string;
																														if ($we_can_paginate_by_mysql){
																															$debug_show_sql .= '<b style="color:red">'.$sql_relevance_score.'</b>';
																														}
																														$debug_show_sql .= $sql;	
																													}
			// The SQL query is made of 3 parts:
			// $sql_begin_string	: begin of the sql string: 
			// $sql_relevance_score	: the relevance score when pagination is made by MySQL
			// $sql					: the rest of the sql query
			
			if ( $we_can_paginate_by_mysql )  {
				$sql_str = $sql_begin_string.$sql_relevance_score.$sql;
			}
			else {
				$sql_str = $sql_begin_string.$sql;
			}
				
			$query = $this->db->query($sql_str);
			$results = $query->rows;	
			
			//******************************************************************************************************************************
			// PRODUCT COUNT   PRODUCT COUNT   PRODUCT COUNT   PRODUCT COUNT   PRODUCT COUNT   PRODUCT COUNT   PRODUCT COUNT   PRODUCT COUNT  
			// 								when the pagination is made by PHP and no products found in cache

						
			// Quick way to count the number of products instead to make a new query. This method can be 
			// used only when the query doesn't contain the LIMIT clause. See the variable $sql_count.
			// See also the vQmod modification to the file controller/product/search.php where this registry
			// variable has been used.

			if ( ! $we_can_paginate_by_mysql )  {
				$this->registry->set('product_total', count($results));
			}
			
			//******************************************************************************************************************************
			//******************************************************************************************************************************
			
			$product_total = $this->registry->get('product_total');
			
			
			// With $this->getProduct($result['product_id']) we get the fields:

			// * name 				* sku	* location			price			weight				subtract	date_added
			// * description		* upc	* manufacturer		special			weight_class_id		rating		date_modified
			// * meta_description	* ean	  quantity			reward			length				reviews		viewed
			// * meta_keyword		* jan	  stock_status		points			width				minimum
			// * tag				* isbn	  image				tax_class_id	height				sort_order
			// * model				* mpn	  manufacturer_id	date_available	length_class_id		status

			// For the calculation of the product score we need the fields marked with (*)
	
	
	
			// We need to take into account the store_id and the language_id of each search. Since the method getProduct()
			// doesn't allow to pass arguments like store_id and language_id, we will temporarily modify the two variables
			// 'config_store_id' and 'config_language_id'

			$tmp_config_store_id	= $this->config->get('config_store_id');
			$tmp_config_language_id = $this->config->get('config_language_id');
			
			$this->config->set('config_store_id', $data['store_id']);
			$this->config->set('config_language_id', $data['language_id']);
			
			foreach ( $results as $result) {
				$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
			}
			
			$this->config->set('config_store_id', $tmp_config_store_id);
			$this->config->set('config_language_id', $tmp_config_language_id);

		
		
		
		
			// Build the array $adsmart_product_data that contains all the fields we need.
			// We cannot use the array $product_data because it doesn't contain all the fields
			// (see function $this->getProduct($result['product_id']) and there are a lot of
			// unnecessary fields, while $adsmart_product_data also contains the Attribute and 
			// Option fields.
			$adsmart_product_data = array(); 
		
			foreach ($product_data as $product_id => $product){
				
				$adsmart_product_data[$product_id]['name']				= $product['name'];
				$adsmart_product_data[$product_id]['description']		= $product['description'];
				$adsmart_product_data[$product_id]['meta_description']	= $product['meta_description'];
				$adsmart_product_data[$product_id]['meta_keyword']		= $product['meta_keyword'];
				$adsmart_product_data[$product_id]['tag']				= $product['tag'];
				$adsmart_product_data[$product_id]['model']				= $product['model'];
				$adsmart_product_data[$product_id]['sku']				= $product['sku'];
				$adsmart_product_data[$product_id]['upc']				= $product['upc'];
				$adsmart_product_data[$product_id]['ean']				= $product['ean'];
				$adsmart_product_data[$product_id]['jan']				= $product['jan'];
				$adsmart_product_data[$product_id]['isbn']				= $product['isbn'];
				$adsmart_product_data[$product_id]['mpn']				= $product['mpn'];
				$adsmart_product_data[$product_id]['location']			= $product['location'];
				$adsmart_product_data[$product_id]['manufacturer_name']	= $product['manufacturer'];
				
				// Attributes (groups, names and descriptions) 
				// With  $this->model_catalog_product->getProductAttributes($product_id) we get the fields:
				
				// Attribute Group Name
				// Attribute Name
				// Attribute description
				
				// Note that for each product there can be many group names, attribute names and attribute descriptions
				// so we need three arrays to handle these info.
				
				$attribute_groups = $this->model_catalog_product->getProductAttributes($product_id);
				
				// Create 3 arrays:
				// $attribute_group_names	: contains the attribute group names
				// $attribute_names			: contains the attribute names
				// $attribute_descriptions	: contains the attribute descriptions
				
				// Example: 
				// The product Notebook Toshiba has 2 attribute groups: Processor and Ram
				// The Attribute group "Processor" has 3 attribute names: "i3", "i5", "i7"
				// The the description for the attribute name "i3" is "The new i3 is a dual core Processor" 
				// The Attribute group "Memory" has 4 attribute names: "4Gb", "8Gb", "16Gb", "32Gb"
				
				// $attribute_group_names	= ('Processor', 'Ram')
				// $attribute_names			= ('i3', 'i5', 'i7', '4Gb', '8Gb', '16Gb', '32Gb')
				// $attribute_descriptions	= ('The new i3 is a dual core Processor', '...i5 description...', '...i7 description...', 
				//							   '...4Gb description...', '...8Gb description...', '...16Gb description...', '...32Gb description...');
				
				$attribute_group_names = array();
				$attribute_names = array();
				$attribute_descriptions = array();
				
				if ($attribute_groups) {

					foreach ($attribute_groups as $attribute_group) { 
					
						$attribute_group_names[] = $attribute_group['name'];
						
						foreach ($attribute_group['attribute'] as $attribute) {
							$attribute_names[] = $attribute['name']; 
							$attribute_descriptions[] = $attribute['text']; 
						} 	
					}
				}
					
				// Add the three arrays to the array $adsmart_product_data
				$adsmart_product_data[$product_id]['attribute_group_name']	= $attribute_group_names;
				$adsmart_product_data[$product_id]['attribute_name'] 		= $attribute_names;
				$adsmart_product_data[$product_id]['attribute_description']	= $attribute_descriptions;
				

				// Options (names, values)
				// With  $this->model_catalog_product->getProductOptions($product_id) we get the fields:
				// Option name
				// Option value
				// (unnecessary fields are not listed here)
				// As we did for the attribute info, we must create two arrays for the options
				
				$product_options = $this->model_catalog_product->getProductOptions($product_id);		
				
				$option_names = array();
				$option_values = array();				
				
				if ($product_options){
				
					foreach ($product_options as $product_option) {
					
						$option_names[]  = $product_option['name'];
						
						// The variable $product_option['value'] can be a single string or a bidimensional array.
						// It is an array when contains options like "select", "radio", "checkbox" and "image".
						// For example, in a "select", the external array contains the single options:
						
						// <select>
						
						//	[0]	<option>
						//			Option 1 ( $option_value[0] )
						
						//			each Option value is an associative array of elements (array $option_value): 
						
						//							["product_option_value_id"]=> string(2) "20" 
						//							["option_value_id"]=> string(2) "47" 
						//							
						//			WE ARE INTERESTED IN THE FOLLOWING STRING ($option_value['name']):
						
						//							["name"]=> string(6) "Medium" 		
						
						//							["image"]=> string(0) "" 
						//							["quantity"]=> string(1) "3" 
						//							["subtract"]=> string(1) "1" 
						//							["price"]=> string(6) "4.0000" 
						//							["price_prefix"]=> string(1) "+" 
						//							["weight"]=> string(10) "4.00000000" 
						//							["weight_prefix"]=> string(1) "+" 
						//		</option> 
						
						//	[1]	<option>
						//			Option 2 ( $option_value[1] )
						//		</option> ...
						//	
						// </select>

						if (version_compare(VERSION, '1.5.6.4', '<=')) {

							if (is_array($product_option['option_value'])){
								
								foreach ($product_option['option_value'] as $option_value) {
									$option_values[] = $option_value['name'];
								}
							}
							else {
								$option_values[] = $product_option['option_value'];
							}	
						
						} else {
						// from Oc 2.0.0.0
						
							foreach ($product_option['product_option_value'] as $option_value) {
								$option_values[] = $option_value['name'];
							}
						}
						
						
					}
				}

				// Add the two arrays to the array $adsmart_product_data
				$adsmart_product_data[$product_id]['option_name']	= $option_names;
				$adsmart_product_data[$product_id]['option_value']	= $option_values;
				

				// Category name:
				$adsmart_product_data[$product_id]['category_name'] = '';
				$categories = $this->model_catalog_product->getCategories($product_id); 

				if ($categories){ 
					foreach ($categories as $category) {
						$this->load->model('catalog/category');
						$category_info = $this->model_catalog_category->getCategory($category['category_id']); 
						if ($category_info){
							$adsmart_product_data[$product_id]['category_name'] .= '▄'.$category_info['name']; 
						}
					}
				}
				// End category name
			}
	
	
		
			
			
			// Products are sorted by relevance >>> BY PHP <<< only when we cannot paginate by MySQL
			if ( ! $we_can_paginate_by_mysql )  {

// **********************
// RELEVANCE CALCULATION
// **********************
				
				// if the element $relevance[key] is set then the corresponding field is also enabled 
				// (field relevances are posted only if the corresponding checkboxes are checked)

				// Variable descriptions:
				
				// $adsmart_product_data				: array of products. Its index is the product_id
				// $relevance							: array of relevance values for each product field
				// $total_score							: score counter for the current product
				// $product_relevance					: array of product relevances. Its index is the product_id 
				// $product_data_sorted_by_relevance	: products sorted by relevance in DESC order


	
				if (!empty($data['filter_name']) && !empty($adsmart_product_data) ) {
				

					// for the arrays $product_data and $adsmart_product_data, the index is the product_id
					foreach ($adsmart_product_data as $product_id => $product){

						$total_score = 0;
						
						// Structure of the array $product:
						// $product['name']
						// $product['model'] ecc.
						
						// Note: this foreach is valid for both exact and broad match options		
						foreach ($product as $product_key => $product_value){

							if ( isset($relevance[$product_key]) && !empty($product_value) ) {

								// we have two types of product values, strings for all fields except 
								// for attributes and options and arrays for attributes and options
								// If $product_value is not an array, we convert it to array to use it
								// in the following foreach

								if ( !is_array($product_value) ){
									$product_value_array[0] = $product_value;
								}
								else {
									$product_value_array = $product_value;
								}

								// Relevance is scored in this way:
								
	//
	// Start value:							+		$relevance[$product_key]
								
	//										+		$num_exact_words_found * $word_ratio * $word_position_weight
	
	//										+		$num_partial_words_found * $partial_word_ratio * $word_position_weight
						
	//	 If there are inflected forms of words:		+		$num_inflections * $inflection_ratio * $word_position_weight
								
														//  ( Words at the beginning of the search strings must have more weight. See 
														//    the variable $word_position_weight.
														//    Number of words found for each product field. See the variable $num_exact_words_found and $num_partial_words_found.)

	// If there are misspellings:			+       $misspelled_word_ratio * $word_position_weight			

	//										+		$relevance[$product_key] * $num_of_detected_words / $num_of_words_in_search_string / $num_words_in_value;
														
														// (the purpose of these variables are commented below)
															

															
															
								foreach ($product_value_array as $value){
																													if (ADSMART_SRC_DEBUG){$debug_output .=  '<br><br><br><b>RELEVANCE CALCULATION</b>:<br>';}
										
									// Lowercase the product field($value) and clean it from extra spaces and tabs:
									$value = utf8_strtolower($value);
									
									// if the product field is "description" strip html tags
									if ($product_key == 'description'){
										$value = strip_tags(html_entity_decode($value));
									}
									

									$value = preg_replace('!\s+!', ' ', $value); // \s: spaces and tabs
									
									// count the word length of $value:
									$value_to_array = explode(' ',$value);
									$num_words_in_value = count($value_to_array);
									
									// Add two spaces at the beginning and the end of the $value, so ALL the words will be surrounded by spaces on both sides
									$value = ' ' .$value. ' ';

																		
									// $num_of_detected_words: number of words (inflections included) of the search string
									// present in the product value.
									// Example: 
									//		Search string: iPod Classic
									//		current value:(name) iPod touch black
									//		$num_of_detected_words = 1
									// The higher is this value, the more is the weight given to the current search result.
									$num_of_detected_words = 0;			
									
									
									// $word_position_weight: a variable whose value is higher for words at the beginning of the search string
									$word_position_weight = $first_word_weight;

									foreach ($search_string_to_words as $word) {	

										$num_exact_words_found 		= 0; 
										$num_partial_words_found	= 0;
										$num_inflections 			= 0;
										
										$matches = array();
										$matches_inflections = array();

																													if (ADSMART_SRC_DEBUG){ $debug_output .=  '<br> current word: <b style="color:red">'.$word.'</b> - value: <b style="color:green">'. $value . '</b>';		$debug_output .=  '<br>Word Position Weight: '. $word_position_weight . '<br>';		}							

																							
										preg_match_all('/\b('.preg_quote($word).')\b/', $value, $matches); // use preg_quote to quote regular expression characters
										$num_exact_words_found = count($matches[0]);
																													if (ADSMART_SRC_DEBUG){ $debug_output .=  '<br> Number of exact matches found: <b style="color:red">'.$num_exact_words_found.'</b>';	}
										

									// Sometimes (Default algorithm, partial words OFF )results have the same score, by removing the following condition, we give slightly more 
									// weight to results containing partial matches, for example: 
									// 	
									// Search string: ipod Cla
									//	 Results		Expected Score 		|	After commenting the "if"   New Score
									//										|					
									// Ipod Touch  		10485772500		    |  	  Ipod Classic				10485779615				
									// Ipod Shuffle		10485772500		    |  	  Ipod Touch				10485772500				
									// Ipod Nano		10485772500		    |  	  Ipod Shuffle				10485772500				
									// Ipod Classic		10485772500  	    |  	  Ipod Nano					10485772500    			
									
									//	if ($this->config->get('adsmart_search_include_partial_words')){
											$num_partial_words_found = substr_count($value, $word) - $num_exact_words_found; 
																													if (ADSMART_SRC_DEBUG){ $debug_output .=  '<br> Number of partial words found: <b style="color:red">'.$num_partial_words_found.'</b> <br>';	}			
									//	}
																																	


										
										
										if ($this->config->get('adsmart_search_include_plurals')){
																													if (ADSMART_SRC_DEBUG){ $debug_output .=  '<br>*************** Inflections ***************<br>'; }			

											if ( $inflection[$word] != $word ) {
																													if (ADSMART_SRC_DEBUG){$debug_output .=  '<br>Inflection: '.$inflection[$word].'<br>';}	
											
												preg_match_all('/\b('.preg_quote($inflection[$word]).')\b/', $value, $matches_inflections); // use preg_quote to quote regular expression characters
												$num_inflections = count($matches_inflections[0]);
											}	
										}

										//$num_of_detected_words is the sum of exact matches, partial words and inflections:
										$num_of_detected_words += $num_exact_words_found + $num_partial_words_found + $num_inflections;		

																													if (ADSMART_SRC_DEBUG){											
																														if ($this->config->get('adsmart_search_include_plurals')){$debug_output .=  '<br>inflected forms of words found: '.$num_inflections.'<br>';}	
																														$debug_output .=  '<br>num of detected words(exact matches, partial words and inflections, if present): '.$num_of_detected_words.'<br>';
																													}
										if ( $num_of_detected_words > 0 ) {	
		
											if ($total_score == 0) {
												$total_score += $relevance[$product_key];				
											}
											$total_score += $num_exact_words_found * $word_ratio * $word_position_weight;										
																													if (ADSMART_SRC_DEBUG){$debug_output .=  '<br>total score (partial words, inflections or misspellings): '.$total_score.'<br>';}	
											
											$total_score += $num_partial_words_found * $partial_word_ratio * $word_position_weight;										
																													if (ADSMART_SRC_DEBUG){$debug_output .=  '<br>total score with partial words (no infl. or miss.): '.$total_score.'<br>';}	
															
											$total_score += $num_inflections * $inflection_ratio * $word_position_weight;
											
																													if (ADSMART_SRC_DEBUG){ $debug_output .=  '<br>total score with inflections: '.$total_score.'<br>'; }
										}
										
										// slightly lower word weight:
										$word_position_weight /= $word_position_weight_ratio; 
									}
																													if (ADSMART_SRC_DEBUG){ $debug_output .=  '<br>----------------------------------------<br>Total score : '.$total_score.'<br>'; }
									
									
									// string matching the begin of	the value
									// if the search string exactly matches the begin of the field value, increment the total score for this result 
									if (strpos($value, $data['filter_name']) === 0){
										$total_score += $num_of_words_in_search_string; // the higher is the number of word in the search string, 
																						// the higher is the weight given to the match with the begin of the field value
										
																													if (ADSMART_SRC_DEBUG){
																														$debug_output .=  '<br>The search string matches the begin of the current product value<br>'; 
																														$debug_output .=  '<br>----------------------------------------<br>Total score : '.$total_score.'<br>'; 
																													}
									}
									
									


									
			// Move on top results with a lower levenshtein distance.
									if ( $include_misspellings ){
																													if (ADSMART_SRC_DEBUG){ $debug_output .=  '<br><b style="color:red">*************** Misspellings ***************</b><br>';}	
										$distances = array();
										
										foreach ($search_string_to_words as $word) { 	
																													if (ADSMART_SRC_DEBUG){ $debug_output .=  '<br>Word: <b>'. $word.'</b>. Computed Tolerance: <b>'.$tolerance[$word].'</b> <br>';}		
											$lowest_distance = 1000;
											$distance = $lowest_distance;
											
											// value_to_array: array of words from the current product field
											foreach ($value_to_array as $value_word) { 	
											
												// the function Levenshtein works only with string lengths <= 255
												$value_word_length = strlen($value_word);
												if ( $word_length[$word] <= 255 && $value_word_length <= 255 )  {
													
													// The following if is just a performance optimization: do not calculate the levenshtein distance 
													// when the difference between the search string word and the current word is higher than the tolerance 
													// value. In this case the levenhstein distance is always greater than the tolerance value. 
													if ( abs($value_word_length - $word_length[$word]) > $tolerance[$word]){
													
														$distance = $lowest_distance; // 1000
																													if (ADSMART_SRC_DEBUG){ $debug_output .=  '<br>distance between '. $word.' and  <b>'.$value_word.':</b> '.$distance.' <b>Distance is too big, Levenshtein skipped</b>.<br>';}		
													}
													else {
														$distance = levenshtein($value_word, $word);
																													if (ADSMART_SRC_DEBUG){ $debug_output .=  '<br>distance between '. $word.' and  <b>'.$value_word.':</b> '.$distance.' <br>';}													
														if ($distance <= $tolerance[$word]){
													
															if ($distance < $lowest_distance){
																$lowest_distance = $distance;
															}																													
														}
													}
													
	
												}
											}
											// save the lowest distance found between $word and each word in the product field
											$distances[$word] = $lowest_distance;
																													if (ADSMART_SRC_DEBUG){ $debug_output .=  '<br>Min distance found for: '. $word.': '.$distances[$word].'<br>--------<br>';}																					
										}
										
										$num_misspellings_found = 0;
										$distance_score = 0;
										foreach ($distances as $wrd => $dist) {
																													if (ADSMART_SRC_DEBUG){ $debug_output .=  '<b style="color:red">Current word:</b> <b>'. $wrd.'</b> -  <b style="color:red">Current distance</b>: <b>'.$dist.'</b><br>';}	
											// Prevents a division by 0 and skips the case in which $distances[$word] = 0 (word with no misspellings)
											if ($dist > 0) {

												// the lower is the distance, the higher is the match. Then:
												// If $dist = 0		=>	no misspellings
												// If $dist = 1		=>	1/$dist = 1	
												// If $dist = 2		=>	1/$dist = 0.5 ecc...
												
												$distance_score = 0;

												// if the computed distance for the current word is <= the tolerance for the same word:
												if ( $dist <= $tolerance[$wrd] ){
													
													$distance_score = (1/$dist) * $misspelled_word_ratio * $word_position_weight;
													
													// count the number of misspellings
													$num_misspellings_found += 1;
													
													// add the number of misspelled words to the total number of detected words 
													$num_of_detected_words += $num_misspellings_found;	
													
													$total_score += $relevance[$product_key];
													
													$total_score += $distance_score;
												}
																												if (ADSMART_SRC_DEBUG){ $debug_output .=  '<b style="color:red">Current total Score</b>: '.$total_score.'<br>';}				
											}
																												if (ADSMART_SRC_DEBUG){ $debug_output .=  'Score for the Levenshtein distance calculated on the word: <b>'. $wrd.'</b>: '.$distance_score.'<br>';}											
										}	
																												if (ADSMART_SRC_DEBUG){ $debug_output .=  '<br><br>Total number of misspelled words found: '.$num_misspellings_found.'<br>';}														
																												if (ADSMART_SRC_DEBUG){ $debug_output .=  '<br>----------------------------------------<br>Total score with misspellings: '.$total_score.'<br><br>';}																											
																												if (ADSMART_SRC_DEBUG){ $debug_output .=  '<br>************** End Misspellings *************<br>';}			
									}
												

												
									// check for exact matches (if the search string is made of one word only, we already run this preg_match_all 
									// (see preg_match_all('/\b('.preg_quote($word).')\b/', $value, $matches);  )
									$search_string_matches = array();

									if ( $num_of_words_in_search_string > 1 ){
										preg_match_all('/\b('.preg_quote($data['filter_name']).')\b/', $value, $search_string_matches);  // use preg_quote to quote regular expression characters
										$num_exact_matches = count($search_string_matches[0]);
									}
									else $num_exact_matches = 0;
								
								
									$total_score += $exact_match_ratio * $num_exact_matches;
																													if (ADSMART_SRC_DEBUG){
																													$debug_output .=  '<br>$data["filter_name"] :'. $data['filter_name'].'<br>';
																													$debug_output .=  '<br>Search string made of: '. $num_of_words_in_search_string.' words<br>';
																													$debug_output .=  '<br>(Num of exact matches returns 0 if the query string is made of 1 word only)<br>';
																													$debug_output .=  '<br>Num of exact matches: '.$num_exact_matches.'<br>';
																													$debug_output .=  '<br>First exact match: '; 	if ( $num_of_words_in_search_string > 1 ) { $debug_output .=  '>>>' . current($search_string_matches[0]); }
																													$debug_output .=  '<br>total with exact matches: '.$total_score.'<br>';									
																													}	
																													
									// Add an extra score for each additional word found (inflections and misspelled words included) and depending on the number of words
									// for the search string and the current product value
									
									// Notes: 
									// The higher is the ratio ($num_of_detected_words / $num_of_words_in_search_string), the higher is the weight given to
									// the search result.
									// The lower is the number of words in the current product value ($num_words_in_value), the higher is the weight given 
									// to the search result.
									
								$total_score += ( ($num_of_detected_words + $num_exact_matches) / $num_of_words_in_search_string ) / $num_words_in_value;
																													
																													if (ADSMART_SRC_DEBUG){$debug_output .=  '<br>----------------------------------------<br> TOTAL SCORE: '.$total_score.'<br>----------------------------------------<br><br>';}
								}			
							}
						}
						
						// Save the relevance for the current product in the array product_relevance 
						$product_relevance[$product_id] = round($total_score,4)*10000;
					}
					
			
					// use asort instead of sort to keep the key value (the product id) because it is a number
					asort($product_relevance); // ASC

					// reverse the array order (DESC) and preserve the keys with "true"
					$product_relevance = array_reverse($product_relevance, true);


					// Now that we have the product_ids ordered in descending order by relevance
					// we can create a new product array sorted by relevance:
					$product_data_sorted_by_relevance = array();
					foreach ($product_relevance as $product_id => $rel) {			
																													if (ADSMART_SRC_DEBUG){	
																													// Display the score before the product name
																													$product_data[$product_id]['name'] = $rel . ' ' . $product_data[$product_id]['name'];
																													}
						$product_data_sorted_by_relevance[] = $product_data[$product_id];
					}
					
					$product_data = $product_data_sorted_by_relevance;
				}

			}


			// Cache the results if the cache is enabled
			if ($this->config->get('adsmart_search_enable_search_cache') && $there_are_filters ){
				// cache the result
				// 3600		= 1 hour
				// 86400	= 1 day
				// 604800	= 1 week
				// 2419200	= 1 month
				// 29030400	= 1 year	

							
				$this->adsmart_search_set($current_search_options, $product_data, $product_total, 'search_string', $this->config->get('adsmart_search_cache_update_frequency'));
			}
			
			

			// Pagination for PHP 
			if ( ! $we_can_paginate_by_mysql )  {
			
				if (isset($data['start']) && isset($data['limit'])){
					$product_data = array_slice($product_data, $data['start'], $data['limit']);
				}
			}

			
			
			
		}
		// END ADDED	
		
																													if (ADSMART_SRC_DEBUG){	
																														$debug_output .= '<br><br>Number of results: <b>' . $this->registry->get('product_total'). '</b><br>';
																													}
																														
		$totaltime = '';
		if (ADSMART_SRC_SPEED_TEST) {
			$mtime = microtime();
			$mtime = explode(" ",$mtime);
			$mtime = $mtime[1] + $mtime[0];
			$endtime = $mtime;
			$totaltime =  '<br><br><b>Search executed in <span style="color:red"> '.($endtime - $starttime).'</span> seconds</b>.<br><br>';
		}
	
		$_SESSION['adsmart_src_debug'] = '';
		$adsmart_src_debug = '';
	



		if (!empty($debug_show_sql)) {
			$adsmart_src_debug .= $debug_show_sql;
		}
		if (!empty($debug_output)) {
			$adsmart_src_debug .= $debug_output;
		}
		if (!empty($totaltime)) {
			$adsmart_src_debug .= $totaltime;
		}
		
		$_SESSION['adsmart_src_debug'] = $adsmart_src_debug;

		return $product_data;
	}
	

	// Returns the product data or an empty array if nothing found
	
	public function adsmart_search_get($hash, $label='') {
	
		$data = array();

		$files = glob(DIR_CACHE . 'cache-'.$label.'.' . preg_replace('/[^A-Z0-9\._-]/i', '', $hash) . '.*');
// (2)	
		if ($files) {
	
			$cache = file_get_contents($files[0]); // if there are multiple files we pick only the first one
		
			$data = unserialize($cache);

			
			// Make sure that $data is an array:
			
			if (!is_array($data)) {
				$data = array();
			}
			
			
			// Delete old cache files:
			
			foreach ($files as $file) {
				$time = substr(strrchr($file, '.'), 1);

				if ($time < time()) {
					
					if (file_exists($file)) {
						unlink($file);
					}
				}
			} // End delete

			$this->registry->set('product_total', $data['product_total']);
			
			// delete the total number of products and search options from the array $data 
			unset($data['product_total']);
			unset($data['search_options']);

		}	
		
		return $data ; /* don't remove the extra space between $data and ; */
		
	}

	
	public function adsmart_search_set($current_search_options, $value, $product_total, $label='', $expire=3600) {
		
		// http_build_query() converts the associative array into string
		$hash = md5(http_build_query($current_search_options));
	
		
		$this->adsmart_search_delete($hash, $label);
		
		$file = DIR_CACHE . 'cache-'.$label.'.' . preg_replace('/[^A-Z0-9\._-]/i', '', $hash) . '.' . (time() + $expire);
		
		$handle = fopen($file, 'w');
		
		// Add the search options to the array so we know which settings have been used to make the query.
		// The array current_search_options contains the user search options and the hash for the 
		// control panel search options.
		// The search options are stored in the last array element. The array $value is an array of arrays,
		// whose structure is:
		
		// [0]					=> pruoduct 1 array 
		// ...
		// [n-2]				=> product n array 
		// [product_total]		=> total number of products 
		// [search_options]		=> search options array
		
		$value['product_total']	 = $product_total;
		$value['search_options'] = $current_search_options;

		$result = fwrite($handle, serialize($value));
		
		fclose($handle) ;  /* don't remove the extra space between fclose($handle) and ; */
		
		if ($result != false) return true;
		else return false;
	}
	

	public function adsmart_search_delete($hash, $label='') {
		$files = glob(DIR_CACHE . 'cache-'.$label.'.' . preg_replace('/[^A-Z0-9\._-]/i', '', $hash) . '.*');
		
		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					unlink($file);
				}
			}
		}
		return;
	}
	
	


	
}
