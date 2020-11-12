<?php
class ControllerModuleFeatured extends Controller {
	public function index($setting) {
		$this->load->language('module/featured');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		if (!empty($setting['product'])) {
			$products = array_slice($setting['product'], 0, (int)$setting['limit']);

			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
 if ($product_info['date_added']) {
                        $date = $product_info['date_added'];
                    } else {
                        $date = "";
                    }
                     //out of stock
                    if ($product_info['quantity']) {
                        $stock_quantity = $product_info['quantity'];
                    } else {
                        $stock_quantity = 0;
                    }

                    
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$price = false;
					}

					if ((float)$product_info['special']) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}

					$data['products'][] = array(
'percent'	=> sprintf($this->language->get('text_percent'), (round((($product_info['price'] -  $product_info['special'])/$product_info['price'])*100 ,0))),
                        'percent_value'	=> (round((($product_info['price'] -  $product_info['special'])/$product_info['price'])*100 ,0)),
                        'date'=>$date,
                         'stockquantity'=>$stock_quantity,
						'product_id'  => $product_info['product_id'],
						'thumb'       => $image,
						'name'        => $product_info['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'rating'      => $rating,
						'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
					);
				}
			}
		}

		if ($data['products']) {
   $this->load->model('localisation/products_label');
        $label_info = $this->model_localisation_products_label->getLabels();

        foreach($label_info as $labelget){
            $data['labels'][]=array(
                'label_id'=> $labelget['label_id'],
                'label_text'=> $labelget['label_text'],
                'label_color'=> $labelget['label_color'],
                'label_text_color'=> $labelget['label_text_color'],
                'condition_type'=> $labelget['condition_type'],
                'status'=> $labelget['status']

            );
            if($labelget['label_id']==1) {
               $data['new_product_db_days']= $labelget['condition_type'];
               $data['new_product_db_text']= $labelget['label_text'];
               $data['new_product_db_text_color']= $labelget['label_text_color'];
               $data['new_product_db_label_color']= $labelget['label_color'];
               $data['new_product_status']= $labelget['status'];
                $data['position_new']=$labelget['position'];

            }
            if($labelget['label_id']==2) {
                $data['discount_product_db_percent']= $labelget['condition_type'];
                $data['discount_product_db_text']= $labelget['label_text'];
                $data['discount_product_db_text_color']= $labelget['label_text_color'];
                $data['discount_product_db_label_color']= $labelget['label_color'];
                $data['discount_product_status']= $labelget['status'];
                $data['position_discount']= $labelget['position'];

            }
            if($labelget['label_id']==3) {
                $data['shipping_product_db_text']= $labelget['label_text'];
                $data['shipping_product_db_text_color']= $labelget['label_text_color'];
                $data['shipping_product_db_label_color']= $labelget['label_color'];
                $data['custom_product_label_2_status']= $labelget['status'];
                $data['position_shipping']= $labelget['position'];

            }
             if($labelget['label_id']==4) {
                $data['outofstock_product_db_text']= $labelget['label_text'];
                $data['outofstock_product_db_text_color']= $labelget['label_text_color'];
                $data['outofstock_product_db_label_color']= $labelget['label_color'];
                $data['outofstock_product_status']= $labelget['status'];
                $data['position_outofstock']= $labelget['position'];


            }
        }
        $data['shipping_charge']= $this->config->get('flat_cost');
        $data['install_status'] = $this->config->get('product_label_install_status');
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featured.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/featured.tpl', $data);
			} else {
				return $this->load->view('default/template/module/featured.tpl', $data);
			}
		}
	}
}