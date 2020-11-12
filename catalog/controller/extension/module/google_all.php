<?php  
class ControllerExtensionModuleGoogleAll extends Controller {
	public function get_product_datas()
	{
		$key_cart = $this->request->post['product_id_key'];
		$json = array();

		if(!empty($key_cart))
		{
			$product_key_array = explode(':', $key_cart);

			$product_id = $product_key_array[0];

			$this->load->model('catalog/product');
			$product_info = $this->model_catalog_product->getProduct($product_id);

			$json['atc_variant'] = '';
			
			if (!empty($product_key_array[1])) {
				$options = unserialize(base64_decode($product_key_array[1]));

				$json['atc_variant'] = $this->GMTMaster->get_product_variant($product_info['product_id'], $options);
			}

			$quantity = $this->request->post['quantity'];

			if(!empty($product_info))
			{
				$manufacturer = $this->GMTMaster->get_product_manufacturer($product_info['product_id']);
				$category_name = $this->GMTMaster->get_product_category_name($product_info['product_id']);
				$price = $this->GMTMaster->format_price($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), true);

				$json['atc_status'] = true;
				$json['atc_id'] = $product_info['product_id'];
				$json['atc_name'] = $product_info['name'];
				$json['atc_brand'] = $manufacturer;
				$json['atc_category'] = $category_name;
				$json['atc_quantity'] = $quantity;
				$json['atc_price'] = $price;
			}
		}

		echo json_encode($json); die;
	}

	public function abandoned_cart_insert()
	{
		$email = array_key_exists('email', $this->request->post) ? $this->request->post['email'] : '';
		$firstname = array_key_exists('firstname', $this->request->post) ? $this->request->post['firstname'] : '';
		$lastname = array_key_exists('lastname', $this->request->post) ? $this->request->post['lastname'] : '';

		$result = $this->AbandonedCart->abandoned_cart_insert($email, $firstname, $lastname);

		echo json_encode($result); die;
	}

	public function abandoned_cart_delete()
	{
		$array_return = array('status' => 'done');
		$email = array_key_exists('email', $this->request->post) ? $this->request->post['email'] : '';
		if(!empty($email))
			$this->db->query('DELETE FROM '.DB_PREFIX.'gmt_abandoned_carts WHERE email = "'.$email.'"');

		echo json_encode($array_return); die;		
	}
}
?>