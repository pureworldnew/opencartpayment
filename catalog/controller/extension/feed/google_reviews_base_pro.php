<?php
class ControllerExtensionFeedGoogleReviewsBasePro extends Controller {

	public function __construct($registry) {
        parent::__construct($registry);
        $this->useView = false;

        $this->language_id = !empty($this->request->get['language_id']) ? $this->request->get['language_id'] : $this->config->get('config_language_id');
        $this->config->set('config_language_id', $this->language_id);
        $result = $this->db->query('SELECT `code` FROM '.DB_PREFIX.'language WHERE language_id = '.$this->language_id);
        $this->session->data['language'] = $result->row['code'];

        //Devman Extensions - info@devmanextensions.com - 2017-09-18 19:42:42 - Get configuration
        	$store_id = $this->config->get('config_store_id');
        	$config_name = $this->request->get['configuration'] ? urlencode($this->request->get['configuration']) : 'No configuration name';

        	$reviews_base_pro_configurations_url = DIR_APPLICATION.'controller/extension/feed/google_reviews_base_pro_configurations_backup.json';
        	$reviews_base_pro_configurations_url_path = DIR_APPLICATION.'controller/feed/google_reviews_base_pro_configurations_backup.json';
        	$reviews_base_pro_configurations_url2 = DIR_APPLICATION.'controller/extension/feed/google_reviews_base_pro_configurations.json';

        	$str = file_exists($reviews_base_pro_configurations_url_path) ? file_get_contents($reviews_base_pro_configurations_url) : file_get_contents($reviews_base_pro_configurations_url2);
        	$configuration = json_decode($str, true);

        	$configuration_found = is_array($configuration) && array_key_exists('store_'.$store_id, $configuration) && array_key_exists($config_name, $configuration['store_'.$store_id]) ? true : die('Configuration not found');

        	$this->configuration = $configuration['store_'.$store_id][$config_name];
        //END

	}
	public function index() {

		ini_set('memory_limit',-1);
		ini_set('max_execution_time',500000000);

		$id = $this->config->get('config_store_id');

		$status = array_key_exists('google_reviews_base_pro_status_'.$id, $this->configuration) ? $this->configuration['google_reviews_base_pro_status_'.$id] : false;

		$more_oc_1541 = version_compare(VERSION, '1.5.4.1', '>');

		$this->load->model('extension/feed/google_base_pro');
		$this->load->model('extension/feed/google_reviews');

		//Categories allowed configuration
			if(version_compare(VERSION, '1.5.3.1', '>'))
				$categories = $this->model_extension_feed_google_base_pro->getCategories();
			else
				$categories = $this->model_extension_feed_google_base_pro->getCategories_oc_old();

			$categories_allowed = array();

			foreach ($categories as $key => $cat) {
				if(!empty($this->configuration['google_reviews_base_pro_category_allowed_'.$cat['category_id'].'_'.$id]))
					$categories_allowed[] = $cat['category_id'];
			}
		//END Categories allowed configuration

		if (!empty($status)) {
			//Get configuration
				$google_reviews_base_pro_product_title = array_key_exists('google_reviews_base_pro_product_title_'.$id, $this->configuration) && !empty($this->configuration['google_reviews_base_pro_product_title_'.$id]);

				$google_reviews_base_pro_ignore_products = explode(',', (!empty($this->configuration['google_reviews_base_pro_ignore_products_'.$id]) ? $this->configuration['google_reviews_base_pro_ignore_products_'.$id] : ''));

				$only_these_products = !empty($this->configuration['google_reviews_base_pro_only_these_products_'.$id]) ? $this->configuration['google_reviews_base_pro_only_these_products_'.$id] : false;

		        //Only one model to ignore
		        if (!empty($google_reviews_base_pro_ignore_products) && !is_array($google_reviews_base_pro_ignore_products))
		          $google_reviews_base_pro_ignore_products = array($google_reviews_base_pro_ignore_products);

		        //Remove spaces in models
		        foreach ($google_reviews_base_pro_ignore_products as $key => $value) {
		         	$google_reviews_base_pro_ignore_products[$key] = trim($value);
		        }

			//End Get configuration
			$output  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
			$output .= '<feed xmlns:vc="http://www.w3.org/2007/XMLSchema-versioning" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
 xsi:noNamespaceSchemaLocation=
 "http://www.google.com/shopping/reviews/schema/product/2.2/product_reviews.xsd">';


            $output .= '<aggregator>';
                $output .= '<name>'.$this->config->get('config_name').'</name>';
            $output .= '</aggregator>';
            $output .= '<publisher>';
                $output .= '<name>'.$this->config->get('config_name').'</name>';
                $output .= '<favicon>'.$this->config->get('config_image').'</favicon>';
            $output .= '</publisher>';


			$this->load->model('catalog/category');
			$this->load->model('catalog/product');

			$this->load->model('tool/image');

			$this->request->get['store_id'] = 0;

			$reviews = $this->model_extension_feed_google_reviews->getReviews($this->language_id, $only_these_products);

			$output .= '<reviews>'."\n";
			    if(!empty($reviews)) {
			        foreach ($reviews as $review)
                    {
                        $categories = $this->model_catalog_product->getCategories($review['product_id']);

                        $show = false;
                        foreach ($categories as $key => $cat) {
                            $category_id = $this->useView ? $cat : $cat['category_id'];
                            if(in_array($category_id, $categories_allowed))
                            {
                                $show = true;
                                break;
                            }
                        }

                        $ignored = in_array($review['model'], $google_reviews_base_pro_ignore_products);

                        if (!$ignored && $show)
                        {
                            $product_url = $this->url->link('product/product', 'product_id=' . $review['product_id']);
                            $output .= '<review>'."\n";
                                $output .= '<review_id>'.$review['review_id'].'</review_id>'."\n";
                                $output .= '<reviewer>'."\n";
                                    //<name is_anonymous="true">Anonymous</name>
                                    $output .= '<name>'.$review['author'].'</name>'."\n";
                                    if($review['customer_id'])
                                        $output .= '<reviewer_id>'.$review['review_id'].'</reviewer_id>'."\n";
                                $output .= '</reviewer>'."\n";

                                $output .= '<review_timestamp>'.date('Y-m-d\TH:i:s\Z', strtotime($review['date_added'])).'</review_timestamp>'."\n";
                                $output .= '<content>'.$review['text'].'</content>'."\n";
                                $output .= '<review_url type="group">'.$product_url.'</review_url>'."\n";
                                $output .= '<ratings>'."\n";
                                    $output .= '<overall min="1" max="5">'.$review['rating'].'</overall>'."\n";
                                $output .= '</ratings>'."\n";

                                $output .= '<products>'."\n";
                                    $output .= '<product>'."\n";
                                        $output .= '<product_ids>'."\n";
                                            if(!empty($review['sku'])) {
                                                $output .= '<skus>'."\n";
                                                    $output .= '<sku>'.$review['sku'].'</sku>'."\n";
                                                $output .= '</skus>'."\n";
                                            }
                                            if(!empty($review['mpn'])) {
                                                $output .= '<mpns>'."\n";
                                                    $output .= '<mpn>'.$review['mpn'].'</mpn>'."\n";
                                                $output .= '</mpns>'."\n";
                                            }
                                            if(!empty($review['manufacturer'])) {
                                                $output .= '<brands>'."\n";
                                                    $output .= '<brand>'.$review['manufacturer'].'</brand>'."\n";
                                                $output .= '</brands>'."\n";
                                            }
                                        $output .= '</product_ids>'."\n";
                                        $output .= '<product_name>'.$review['product_name'].'</product_name>'."\n";
                                        $output .= '<product_url>'.$product_url.'</product_url>'."\n";
                                    $output .= '</product>'."\n";
                                $output .= '</products>'."\n";
                                $output .= '<is_spam>false</is_spam>';
                            $output .= '</review>'."\n";
                        }
                    }
                }
            $output .= '</reviews>'."\n";

			$output .= '</feed>';

			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);
		}
	}

	protected function getPath($parent_id, $current_path = '') {
		$category_info = $this->model_catalog_category->getCategory($parent_id);

		if ($category_info) {
			if (!$current_path) {
				$new_path = $category_info['category_id'];
			} else {
				$new_path = $category_info['category_id'] . '_' . $current_path;
			}

			$path = $this->getPath($category_info['parent_id'], $new_path);

			if ($path) {
				return $path;
			} else {
				return $new_path;
			}
		}
	}

	public function plainText($string) {
		$table = array(
		'“'=>'&#39;', '”'=>'&#39;', '‘'=>"&#34;", '’'=>"&#34;", '•'=>'*', '—'=>'-', '–'=>'-', '¿'=>'?', '¡'=>'!', '°'=>' deg. ',
		'÷'=>' / ', '×'=>'X', '±'=>'+/-',
		'&nbsp;'=> ' ', '"'=> '&#34;', "'"=> '&#39;', '<'=> '&lt;', '>'=> '&gt;', "\n"=> ' ', "\r"=> ' '
		);

		$string = strip_tags(html_entity_decode($string));
		$string = strtr($string, $table);
		$string = preg_replace('/&#?[a-z0-9]+;/i',' ',$string);
		$string = preg_replace('/\s{2,}/i', ' ', $string );
		if($this->config->get('uksb_google_merchant_characters')){

			$table2 = array(
			'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a',
			'Þ'=>'B', 'þ'=>'b', 'ß'=>'Ss',
			'ç'=>'c',
			'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e',
			'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i',
			'ñ'=>'n',
			'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'œ'=>'o', 'ð'=>'o',
			'š'=>'s',
			'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u',
			'ý'=>'y', 'ÿ'=>'y',
			'ž'=>'z', 'ž'=>'z',
			'©'=>'(c)', '®'=>'(R)'
			);

			$string = strtr($string, $table2);
			$string = preg_replace('/[^(\x20-\x7F)]*/','', $string );
		}
		return substr($string, 0, 5000 );
	}
}
?>