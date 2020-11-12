<?php
class ControllerFeedGoogleSitemap extends Controller {
	public function index() {
		if ($this->config->get('google_sitemap_status')) {
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

			$this->load->model('catalog/product');
			$this->load->model('tool/image');

			//$products = $this->model_catalog_product->getProducts();
			$products = $this->model_catalog_product->getProductsForSiteMap(); 

			foreach ($products as $product) { 
				if ( $product['image'] && is_file(DIR_IMAGE . $product['image']) ) {
					$pathx = $this->model_catalog_product->getProductPath($product['product_id']); 
					if ( $pathx )
					{
						$href = $this->url->link('product/product', 'path=' . $pathx . '&product_id=' . $product['product_id'] . '&engine=google');

					} else {
						$href =  $this->url->link('product/product', 'product_id=' . $product['product_id'] . '&engine=google');
					}
					$output .= '<url>';
					$output .= '<loc><![CDATA[' . $href . ']]></loc>';
					$output .= '<changefreq>weekly</changefreq>';
					$output .= '<priority>1.0</priority>';
					$output .= '<image:image>';
					$output .= '<image:loc>' . $this->model_tool_image->resize($product['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')) . '</image:loc>';
					$output .= '<image:caption><![CDATA[' . $product['name'] . ']]></image:caption>';
					$output .= '<image:title><![CDATA[' . $product['name'] . ']]></image:title>';
					$output .= '</image:image>';
					$output .= '</url>';
				}
			}
			
			$this->load->model('catalog/category');

			$output .= $this->getCategories(0);

			$this->load->model('catalog/information');

			$informations = $this->model_catalog_information->getInformations();

			foreach ($informations as $information) {
				$output .= '<url>';
				$output .= '<loc><![CDATA[' . $this->url->link('information/information', 'information_id=' . $information['information_id'] . '&engine=google') . ']]></loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.5</priority>';
				$output .= '</url>';
			}

			$output .= '</urlset>';

			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);
		}
	}

	protected function getCategories($parent_id, $current_path = '') {
		$output = '';

		//$results = $this->model_catalog_category->getCategories($parent_id);
		$results = $this->model_catalog_category->getCategoriesForSiteMap($parent_id);

		foreach ($results as $result) {
			$new_path = $result['category_id'];

			$output .= '<url>';
			$output .= '<loc><![CDATA[' . $this->url->link('product/category', 'path=' . $new_path . '&engine=google') . ']]></loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.7</priority>';
			$output .= '</url>';

			//$products = $this->model_catalog_product->getProducts(array('filter_category_id' => $result['category_id']));
			$products = $this->model_catalog_product->getProductsForSiteMap(array('filter_category_id' => $result['category_id']));

			foreach ($products as $product) {
				$output .= '<url>';
				$output .= '<loc><![CDATA[' . $this->url->link('product/product', 'path=' . $new_path . '&product_id=' . $product['product_id'] . '&engine=google') . ']]></loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>1.0</priority>';
				$output .= '</url>';
			}

			$output .= $this->getCategories($result['category_id'], $new_path);
		}
		
		return $output;
	}
}
