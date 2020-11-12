<?php
class ControllerExtensionHbseoSitemap extends Controller {
	public function index() {
		if ($this->config->get('hb_sitemap_enable')) {	
			$search = 'extension/hbseo/sitemap';
			$file = '.htaccess';
			$htaccess_enabled = false;
			if (file_exists($file)) {
				$lines = file($file);
				foreach($lines as $line) {
				  if(strpos($line, $search) !== false) {
					$htaccess_enabled = true;
				  }
				}
			}else{
				$htaccess_enabled = false;
			}
			
			
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			
			$limit = ($this->config->get('hb_sitemap_limit'))?$this->config->get('hb_sitemap_limit'):3000;
			$product_total = $this->getTotalProducts();
			$number_of_pages = ceil($product_total / $limit);
			
			$store_id = (int)$this->config->get('config_store_id');
			
			if ($store_id == 0){ 
				$store_url = HTTPS_SERVER;
			}else{
				$result = $this->db->query("SELECT `url` FROM `" . DB_PREFIX . "store` WHERE store_id = ".(int)$store_id);
				$store_url = $result->row['url'];
			}
				
			//LANGUAGES
			$languages = $this->db->query("SELECT * FROM ".DB_PREFIX."language WHERE status = 1");
			foreach ($languages->rows as $language) {
				$language_code = $language['code'];	
				if ($this->config->get('hb_sitemap_product')) {
					for ($x = 1; $x <= $number_of_pages; $x++) {
						$output .='<sitemap>';

						if ($htaccess_enabled){
							$output .= '<loc>'.$store_url.'sitemaps/'.$language_code.'/product_sitemap_'.$x.'.xml</loc>';
						}else{
							$output .= '<loc>'.$this->url->link('extension/hbseo/sitemap/product', '&hbxmllang='.$language_code.'&page=' . $x).'</loc>';
						}

						$output .= '<lastmod>'.date('c',strtotime($this->lastupdatedProduct(($x - 1)*$limit,$limit))).'</lastmod>';
						$output.='</sitemap>';
					}
				}
				
				if ($this->config->get('hb_sitemap_producttags')) {
					for ($x = 1; $x <= $number_of_pages; $x++) {
						$output .='<sitemap>';
						if ($htaccess_enabled){
							$output .= '<loc>'.$store_url.'sitemaps/'.$language_code.'/product_tags_sitemap_'.$x.'.xml</loc>';
						}else{
							$output .= '<loc>'.$this->url->link('extension/hbseo/sitemap/product_tags', '&hbxmllang='.$language_code.'&page=' . $x).'</loc>';
						}
						$output .= '<lastmod>'.date('c',strtotime($this->lastupdatedProduct(($x - 1)*$limit,$limit))).'</lastmod>';
						$output.='</sitemap>';
					}
				}
				
				if ($this->config->get('hb_sitemap_category')) {
					$output .='<sitemap>';
					if ($htaccess_enabled){
						$output .= '<loc>'.$store_url.'sitemaps/'.$language_code.'/category_sitemap.xml</loc>';
					}else{
						$output .= '<loc>'.$this->url->link('extension/hbseo/sitemap/category','hbxmllang='.$language_code).'</loc>';
					}
					$output .= '<lastmod>'.date('c',strtotime($this->lastupdatedCategory())).'</lastmod>';
					$output.='</sitemap>';
				}
				if ($this->config->get('hb_sitemap_brand')) {	
					$output .='<sitemap>';
					if ($htaccess_enabled){
						$output .= '<loc>'.$store_url.'sitemaps/'.$language_code.'/brand_sitemap.xml</loc>';
					}else{
						$output .= '<loc>'.$this->url->link('extension/hbseo/sitemap/brand','hbxmllang='.$language_code).'</loc>';
					}
					$output .= '<lastmod>'.date('c',strtotime($this->lastupdatedProduct())).'</lastmod>';
					$output.='</sitemap>';
				}
				if ($this->config->get('hb_sitemap_info')) {		
					$output .='<sitemap>';
					if ($htaccess_enabled){
						$output .= '<loc>'.$store_url.'sitemaps/'.$language_code.'/information_sitemap.xml</loc>';
					}else{
						$output .= '<loc>'.$this->url->link('extension/hbseo/sitemap/information','hbxmllang='.$language_code).'</loc>';
					}
					$output .= '<lastmod>'.date('c',strtotime($this->lastupdatedProduct())).'</lastmod>';
					$output.='</sitemap>';
				}
				if ($this->config->get('hb_sitemap_ctopr')) {		
					$output .='<sitemap>';
					if ($htaccess_enabled){
						$output .= '<loc>'.$store_url.'sitemaps/'.$language_code.'/category_to_product_sitemap.xml</loc>';
					}else{
						$output .= '<loc>'.$this->url->link('extension/hbseo/sitemap/category_to_product','hbxmllang='.$language_code).'</loc>';
					}
					$output .= '<lastmod>'.date('c',strtotime($this->lastupdatedProduct())).'</lastmod>';
					$output.='</sitemap>';
				}
				if ($this->config->get('hb_sitemap_btopr')) {		
					$output .='<sitemap>';
					if ($htaccess_enabled){
						$output .= '<loc>'.$store_url.'sitemaps/'.$language_code.'/brand_to_product_sitemap.xml</loc>';
					}else{
						$output .= '<loc>'.$this->url->link('extension/hbseo/sitemap/brand_to_product','hbxmllang='.$language_code).'</loc>';
					}
					$output .= '<lastmod>'.date('c',strtotime($this->lastupdatedProduct())).'</lastmod>';
					$output.='</sitemap>';
				}
				
				if ($this->config->get('hb_sitemap_others')) {
					$output .='<sitemap>';
					if ($htaccess_enabled){
						$output .= '<loc>'.$store_url.'sitemaps/'.$language_code.'/journalblog_sitemap.xml</loc>';
					}else{
						$output .= '<loc>'.$this->url->link('extension/hbseo/sitemap/journalblog','hbxmllang='.$language_code).'</loc>';
					}
					$output .= '<lastmod>'.date('c',strtotime($this->lastupdatedProduct())).'</lastmod>';
					$output.='</sitemap>';
				}
			}//endfor languages
			if ($this->config->get('hb_sitemap_misc')) {		
				$output .='<sitemap>';
				if ($htaccess_enabled){
					$output .= '<loc>'.$store_url.'sitemaps/misc_sitemap.xml</loc>';
				}else{
					$output .= '<loc>'.$this->url->link('extension/hbseo/sitemap/misc').'</loc>';
				}
				$output .= '<lastmod>'.date('c',strtotime($this->lastupdatedMisc())).'</lastmod>';
				$output.='</sitemap>';
			}
			
			$output .= '</sitemapindex>';

			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);
		}else{
			echo 'HuntBee SEO XML Sitemap Generator PRO is disabled.';
		}
	}
	
	public function products() {
		if ($this->config->get('hb_sitemap_product')) {
			
			if (isset($this->request->get['hbxmllang'])) {
				$language_id = $this->getset_language_id($this->request->get['hbxmllang']);
			}
			
			if (isset($this->request->get['page'])){
				$page = (int)$this->request->get['page'];
			}else{
				$page = 1;
			}
			
			$caption = $this->config->get('hb_sitemap_caption'.(int)$this->config->get('config_language_id'));
			$title = $this->config->get('hb_sitemap_title'.(int)$this->config->get('config_language_id'));
			$width = ($this->config->get('hb_sitemap_width'))?$this->config->get('hb_sitemap_width'):500;
			$height = ($this->config->get('hb_sitemap_height'))?$this->config->get('hb_sitemap_height'):500;
			
			$limit = $this->config->get('hb_sitemap_limit');
			
			$start = ($page - 1)*$limit;
			
			if ($start < 0) {
				$start = 0;
			}
			
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			
			$products = $this->getProducts($start,$limit);

			foreach ($products as $product) {
					$output .= '<url>';
					$output .= '<loc>' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . '</loc>';
					$output .= '<changefreq>weekly</changefreq>';
					$output .= '<lastmod>' . date('Y-m-d\TH:i:sP', strtotime($product['date_modified'])) . '</lastmod>';
					$output .= '<priority>1.0</priority>';
					if (trim($product['image']) <> '') {
						$resized_image = $this->model_tool_image->resize($product['image'], $width, $height);
						$image_name = htmlspecialchars($product['name']);
						if ($resized_image){
							$output .= '<image:image>';
							$output .= '<image:loc>' . $resized_image . '</image:loc>';
							$output .= '<image:caption>' . str_replace('{p}', $image_name, $caption) . '</image:caption>';
							$output .= '<image:title>' . str_replace('{p}', $image_name, $title) . '</image:title>';
							$output .= '</image:image>';
						}
					}
					if ($this->getadditionalimages($product['product_id'])) {
						$output .= $this->getadditionalimages($product['product_id']);
					}
					$output .= '</url>';
			}
			
			$output .= '</urlset>';

			$this->response->addHeader('Content-Type: application/xml');
			
			if ($this->config->get('hb_sitemap_beautify')) {
				$output = $this->xmlbeautify($output);
			}

			$this->response->setOutput($output);
		}
	}
	
	public function product_tags() {
		if ($this->config->get('hb_sitemap_producttags')) {
			if (isset($this->request->get['page'])){
				$page = (int)$this->request->get['page'];
			}else{
				$page = 1;
			}
			
			if (isset($this->request->get['hbxmllang'])) {
				$language_id = $this->getset_language_id($this->request->get['hbxmllang']);
			}
			
			$limit = $this->config->get('hb_sitemap_limit');
			
			$start = ($page - 1)*$limit;
			
			if ($start < 0) {
				$start = 0;
			}
			
			$products = $this->getProducts($start,$limit);
			
			if ($products) {
				$output  = '<?xml version="1.0" encoding="UTF-8"?>';
				$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
				
				foreach ($products as $product) {
					$tags = $product['tag'];
					$tags = explode(',',$tags);
					foreach ($tags as $tag) {
						if (trim($tag) <> ''){
							$output .= '<url>';
							$output .= '<loc>' . $this->url->link('product/search', 'tag=' . $tag) . '</loc>';
							$output .= '<changefreq>weekly</changefreq>';
							$output .= '<lastmod>' . date('Y-m-d\TH:i:sP', strtotime($product['date_modified'])) . '</lastmod>';
							$output .= '<priority>0.2</priority>';
							$output .= '</url>';
						}
					}
				}
				
				
				$output .= '</urlset>';
	
				$this->response->addHeader('Content-Type: application/xml');
				
				if ($this->config->get('hb_sitemap_beautify')) {
					$output = $this->xmlbeautify($output);
				}
			
				$this->response->setOutput($output);

			} //if products end
		}
	}
	
	public function category() {
		if ($this->config->get('hb_sitemap_category')) {
			if (isset($this->request->get['hbxmllang'])) {
				$language_id = $this->getset_language_id($this->request->get['hbxmllang']);
			}
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
			$this->load->model('catalog/category');
			$this->load->model('tool/image');
			
			$output .= $this->getCategories(0);			
			$output .= '</urlset>';
			$this->response->addHeader('Content-Type: application/xml');
			
			if ($this->config->get('hb_sitemap_beautify')) {
				$output = $this->xmlbeautify($output);
			}

			
			$this->response->setOutput($output);
		}
	}
	
	public function brand() {
		if ($this->config->get('hb_sitemap_brand')) {
			if (isset($this->request->get['hbxmllang'])) {
				$language_id = $this->getset_language_id($this->request->get['hbxmllang']);
			}
			
			$width = ($this->config->get('hb_sitemap_width'))?$this->config->get('hb_sitemap_width'):500;
			$height = ($this->config->get('hb_sitemap_height'))?$this->config->get('hb_sitemap_height'):500;
			
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

			$this->load->model('catalog/manufacturer');
			$this->load->model('tool/image');

			$manufacturers = $this->model_catalog_manufacturer->getManufacturers();

			foreach ($manufacturers as $manufacturer) {
				$output .= '<url>';
				$output .= '<loc>' . $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']) . '</loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.7</priority>';
				if (trim($manufacturer['image']) <> '') {
					$resized_image = $this->model_tool_image->resize($manufacturer['image'], $width, $height);
					$image_name = htmlspecialchars($manufacturer['name']);
					if ($resized_image) {
						$output .= '<image:image>';
						$output .= '<image:loc>' . $resized_image . '</image:loc>';
						$output .= '<image:caption>' . $image_name . '</image:caption>';
						$output .= '<image:title>' . $image_name . '</image:title>';
						$output .= '</image:image>';
					}
				}
				$output .= '</url>';
			}

			$output .= '</urlset>';

			$this->response->addHeader('Content-Type: application/xml');
			
			if ($this->config->get('hb_sitemap_beautify')) {
				$output = $this->xmlbeautify($output);
			}
			
			$this->response->setOutput($output);
		}
	}
	
	public function information() {
		if ($this->config->get('hb_sitemap_info')) {
			if (isset($this->request->get['hbxmllang'])) {
				$language_id = $this->getset_language_id($this->request->get['hbxmllang']);
			}
			
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

			$this->load->model('catalog/information');

			$informations = $this->model_catalog_information->getInformations();

			foreach ($informations as $information) {
				$output .= '<url>';
				$output .= '<loc>' . $this->url->link('information/information', 'information_id=' . $information['information_id']) . '</loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.5</priority>';
				$output .= '</url>';
			}

			$output .= '</urlset>';
		
			$this->response->addHeader('Content-Type: application/xml');
			
			if ($this->config->get('hb_sitemap_beautify')) {
				$output = $this->xmlbeautify($output);
			}
			
			$this->response->setOutput($output);
		}
	}
	
	public function category_to_product() {
		if ($this->config->get('hb_sitemap_ctopr')) {
			if (isset($this->request->get['hbxmllang'])) {
				$language_id = $this->getset_language_id($this->request->get['hbxmllang']);
			}
			
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			$this->load->model('catalog/category');
			$output .= $this->getCategoriesProduct(0);			
			$output .= '</urlset>';
						$this->response->addHeader('Content-Type: application/xml');
			
			if ($this->config->get('hb_sitemap_beautify')) {
				$output = $this->xmlbeautify($output);
			}
			$this->response->setOutput($output);
		}
	}
	
	
	public function brand_to_product() {
		if ($this->config->get('hb_sitemap_btopr')) {
			if (isset($this->request->get['hbxmllang'])) {
				$language_id = $this->getset_language_id($this->request->get['hbxmllang']);
			}
			
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			
			$this->load->model('catalog/manufacturer');

			$manufacturers = $this->model_catalog_manufacturer->getManufacturers();

			foreach ($manufacturers as $manufacturer) {
				$output .= '<url>';
				$output .= '<loc>' . $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']) . '</loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.7</priority>';
				$output .= '</url>';

				$products = $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => $manufacturer['manufacturer_id']));

				foreach ($products as $product) {
					$output .= '<url>';
					$output .= '<loc>' . $this->url->link('product/product', 'manufacturer_id=' . $manufacturer['manufacturer_id'] . '&product_id=' . $product['product_id']) . '</loc>';
					$output .= '<changefreq>weekly</changefreq>';
					$output .= '<priority>1.0</priority>';
					$output .= '</url>';
				}
			}
					
			$output .= '</urlset>';
						$this->response->addHeader('Content-Type: application/xml');
			
			if ($this->config->get('hb_sitemap_beautify')) {
				$output = $this->xmlbeautify($output);
			}
			$this->response->setOutput($output);
		}
	}
	
	public function misc() {
		if ($this->config->get('hb_sitemap_misc')) {
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

			$links = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sitemap_links` WHERE store_id = '".(int)$this->config->get('config_store_id')."'");
			$links = $links->rows;
			if ($links) {
				foreach ($links as $link) {
					$misclink = urldecode($link['link']);
					$misclink = htmlspecialchars($misclink);
					$output .= '<url>';
					$output .= '<loc>' . $misclink . '</loc>';
					$output .= '<changefreq>'. $link['freq'] .'</changefreq>';
					$output .= '<priority>'. $link['priority'] .'</priority>';
					$output .= '<lastmod>' . date('Y-m-d\TH:i:sP', strtotime($link['date_added'])) . '</lastmod>';
					$output .= '</url>';
				}
			}

			$output .= '</urlset>';
		
			$this->response->addHeader('Content-Type: application/xml');
			
			if ($this->config->get('hb_sitemap_beautify')) {
				$output = $this->xmlbeautify($output);
			}
			
			$this->response->setOutput($output);
		}
	}
	
	public function journalblog() {
		if ($this->config->get('hb_sitemap_others')) {
			if (isset($this->request->get['hbxmllang'])) {
				$language_id = $this->getset_language_id($this->request->get['hbxmllang']);
			}
			
			$width = ($this->config->get('hb_sitemap_width'))?$this->config->get('hb_sitemap_width'):500;
			$height = ($this->config->get('hb_sitemap_height'))?$this->config->get('hb_sitemap_height'):500;
			
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

			$this->load->model('catalog/manufacturer');
			$this->load->model('tool/image');

			$this->load->model('journal2/blog');

			foreach ($this->model_journal2_blog->getPosts() as $post) {
                $output .= '<url>';
                $output .= '<loc>' . $this->url->link('journal2/blog/post', 'journal_blog_post_id=' . $post['post_id']) . '</loc>';
                $output .= '<changefreq>monthly</changefreq>';
                $output .= '<priority>0.8</priority>';
				if (trim($post['image']) <> '') {
					$resized_image = $this->model_tool_image->resize($post['image'], $width, $height);
					$image_name = htmlspecialchars($post['name']);
					if ($resized_image) {
						$output .= '<image:image>';
						$output .= '<image:loc>' . $resized_image . '</image:loc>';
						$output .= '<image:caption>' . $image_name . '</image:caption>';
						$output .= '<image:title>' . $image_name . '</image:title>';
						$output .= '</image:image>';
					}
				}
                $output .= '</url>';
            }

			foreach ($this->model_journal2_blog->getCategories() as $category) {
				$output .= '<url>';
				$output .= '<loc>' . $this->url->link('journal2/blog', 'journal_blog_category_id=' . $category['category_id']) . '</loc>';
				$output .= '<changefreq>monthly</changefreq>';
				$output .= '<priority>0.8</priority>';
				$output .= '</url>';
			}

			$output .= '</urlset>';

			$this->response->addHeader('Content-Type: application/xml');
			
			if ($this->config->get('hb_sitemap_beautify')) {
				$output = $this->xmlbeautify($output);
			}
			$this->response->setOutput($output);
		}
	}
	
	//************************************************************ MODEL ************************************************************//
	//FOR PRODUCTS
	protected function getProducts($start=0, $limit=100){
		$records = $this->db->query("SELECT p.product_id, pd.name, p.image, pd.tag, p.date_modified FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY p.product_id ORDER BY p.date_modified LIMIT ".(int)$start.",".(int)$limit);
		if ($records->rows) {
			return $records->rows;
		}else{
			return false;
		}
	}
	
	protected function getadditionalimages($product_id){
		$a_caption = $this->config->get('hb_sitemap_a_caption'.(int)$this->config->get('config_language_id'));
		$a_title = $this->config->get('hb_sitemap_a_title'.(int)$this->config->get('config_language_id'));
		$width = ($this->config->get('hb_sitemap_width'))?$this->config->get('hb_sitemap_width'):500;
		$height = ($this->config->get('hb_sitemap_height'))?$this->config->get('hb_sitemap_height'):500;
			
		$results = $this->db->query("SELECT a.image, b.name FROM  `" . DB_PREFIX . "product_image` a LEFT JOIN`" . DB_PREFIX . "product_description` b ON a.product_id = b.product_id WHERE a.product_id = '".(int)$product_id."' AND b.language_id = '".(int)$this->config->get('config_language_id')."'");
		if ($results->rows){
			$images = $results->rows;
			$output = '';
			
			foreach ($images as $image){
				if (trim($image['image']) <> ''){
					$resized_image = $this->model_tool_image->resize($image['image'], $width, $height);
					$image_name = htmlspecialchars($image['name']);
					if ($resized_image) {
						$output .= '<image:image>';
						$output .= '<image:loc>' . $resized_image . '</image:loc>';
						$output .= '<image:caption>' . str_replace('{p}', $image_name, $a_caption) . '</image:caption>';
						$output .= '<image:title>' . str_replace('{p}', $image_name, $a_title) . '</image:title>';
						$output .= '</image:image>';
					}
				}
			}
			return $output;
		}else{
			return false;
		}
	}

	//FOR CATEGORY
	protected function getCategories($parent_id, $current_path = '') {
		$output = '';
		$results = $this->model_catalog_category->getCategories($parent_id);
		
		$width = ($this->config->get('hb_sitemap_width'))?$this->config->get('hb_sitemap_width'):500;
		$height = ($this->config->get('hb_sitemap_height'))?$this->config->get('hb_sitemap_height'):500;

		foreach ($results as $result) {
			if (!$current_path) {
				$new_path = $result['category_id'];
			} else {
				$new_path = $current_path . '_' . $result['category_id'];
			}

			$output .= '<url>';
			$output .= '<loc>' . $this->url->link('product/category', 'path=' . $new_path) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.7</priority>';
			if (trim($result['image']) <> '') {
				$resized_image = $this->model_tool_image->resize($result['image'], $width, $height);
				$image_name = htmlspecialchars($result['name']);
				if ($resized_image){
					$output .= '<image:image>';
					$output .= '<image:loc>' . $resized_image . '</image:loc>';
					$output .= '<image:caption>' . $image_name . '</image:caption>';
					$output .= '<image:title>' . $image_name . '</image:title>';
					$output .= '</image:image>';
				}
			}
			$output .= '</url>';

			$output .= $this->getCategories($result['category_id'], $new_path);
		}

		return $output;
	}
	
	//FOR CATEGORY TO PRODUCTS
	protected function getCategoriesProduct($parent_id, $current_path = '') {
		$output = '';

		$results = $this->model_catalog_category->getCategories($parent_id);

		foreach ($results as $result) {
			if (!$current_path) {
				$new_path = $result['category_id'];
			} else {
				$new_path = $current_path . '_' . $result['category_id'];
			}

			$output .= '<url>';
			$output .= '<loc>' . $this->url->link('product/category', 'path=' . $new_path) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.7</priority>';
			$output .= '</url>';

			$products = $this->model_catalog_product->getProducts(array('filter_category_id' => $result['category_id']));

			foreach ($products as $product) {
				$output .= '<url>';
				$output .= '<loc>' . $this->url->link('product/product', 'path=' . $new_path . '&product_id=' . $product['product_id']) . '</loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>1.0</priority>';
				$output .= '</url>';
			}

			$output .= $this->getCategoriesProduct($result['category_id'], $new_path);
		}

		return $output;
	}
	
	//FOR INDEX
	private function getTotalProducts(){
		//$records = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY p.product_id ORDER BY p.date_modified");
		$records = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		//$records = $this->db->query("SELECT count(*) as total FROM " . DB_PREFIX . "product WHERE status = '1' AND p.date_available <= NOW()");
		return $records->num_rows;
	}
	
	private function lastupdatedCategory(){
		$records = $this->db->query("SELECT `date_modified` FROM " . DB_PREFIX . "category ORDER BY date_modified DESC LIMIT 1");
		if ($records->row) {
			return $records->row['date_modified'];
		}else{
			return date('Y-m-d H:i:s');
		}
	}

	private function lastupdatedProduct($start=0, $limit=1){
		$records = $this->db->query("SELECT `date_modified` FROM " . DB_PREFIX . "product ORDER BY date_modified DESC LIMIT ".(int)$start.",".(int)$limit);
		if ($records->row) {
			return $records->row['date_modified'];
		}else{
			return date('Y-m-d H:i:s');
		}
	}
	
	private function lastupdatedMisc(){
		$records = $this->db->query("SELECT `date_added` FROM " . DB_PREFIX . "sitemap_links ORDER BY date_added DESC LIMIT 1");
		if ($records->row) {
			return $records->row['date_added'];
		}else{
			return date('Y-m-d H:i:s');
		}
	}
	
	private function xmlbeautify($string){
			$string = str_replace("<url>","\r\n\t<url>",$string);
			$string = str_replace("<loc>","\r\n\t\t<loc>",$string);
			$string = str_replace("<changefreq>","\r\n\t\t<changefreq>",$string);
			$string = str_replace("<priority>","\r\n\t\t<priority>",$string);
			$string = str_replace("</url>","\r\n\t</url>",$string);
			$string = str_replace("<image:image>","\r\n\t\t<image:image>",$string);
			$string = str_replace("<image:loc>","\r\n\t\t\t<image:loc>",$string);
			$string = str_replace("<image:caption>","\r\n\t\t\t<image:caption>",$string);
			$string = str_replace("<image:title>","\r\n\t\t\t<image:title>",$string);
			$string = str_replace("</image:image>","\r\n\t\t</image:image>",$string);
			$string = str_replace("</urlset>","\r\n</urlset>",$string);
			return $string;
	}
	
	private function getset_language_id($language_code){
		$language_id = $this->db->query("SELECT `language_id` FROM `".DB_PREFIX."language` WHERE `code` = '".$this->db->escape($language_code)."'");
		if (isset($language_id->row['language_id'])) {
			$language_id = (int)$language_id->row['language_id'];
		}else{
			$language_id = 1;
		}
		
		$this->session->data['language_id'] = $language_id;
		if (isset($this->session->data['language_id'])){
			$this->config->set('config_language_id',$this->session->data['language_id']);
			$this->session->data['language'] = $language_code;
			$this->language = new Language($this->session->data['language']);
			$this->language->load($this->session->data['language']);
			$this->registry->set('language', $this->language);
		}
		
		return $language_id;
	}

}
