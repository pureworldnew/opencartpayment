<?php
	namespace google_marketing_tools;
	class abandoned_cart extends master{
	    public function get_events_code() {
	        if($this->get_current_view() == 'checkout') {
	            $is_logged = $this->customer->isLogged();

				if($is_logged)
				{
					$email = json_encode($this->customer->getEmail());
					$firstname = json_encode($this->customer->getFirstName());
					$lastname = json_encode($this->customer->getLastName());
					$result_subscribe = $this->abandoned_cart_insert($email, $firstname, $lastname);
				}

				$script = '<script type="text/javascript">
					var input_selector_firstname = "'.$this->config->get('google_ac_input_selector_firstname_'.$this->id_store).'";
					var input_selector_lastname = "'.$this->config->get('google_ac_input_selector_lastname_'.$this->id_store).'";
					var input_selector_email = "'.$this->config->get('google_ac_input_selector_email_'.$this->id_store).'";
					abandoned_carts_put_events_to_inputs();
				</script>';

				return $script;
            }
            return '';
        }

        function unsubscribe($order_info) {
	        $email = array_key_exists('email', $order_info) ? $order_info['email'] : '';

            if(!empty($email))
                $this->mailchimp_list_unsubscribe($email);
        }

		public function abandoned_cart_insert($email = '', $firstname = '', $lastname = '')
		{
			$array_return = array();
			$is_logged = $this->customer->isLogged();
			$continue = $is_logged || (!$is_logged && !empty($firstname) && !empty($email));

			if($continue)
			{
				$cart_products = $this->cart->getProducts();
				$session_cart = base64_encode(serialize($cart_products));
				$ip = $this->request->server['REMOTE_ADDR'];
				$customer_id = $is_logged;
				if($is_logged)
				{
					$email = $this->customer->getEmail();
					$firstname = $this->customer->getFirstName();
					$lastname = $this->customer->getLastName();
				}

				$id_cart_abandoned = $this->generate_uuid();

				$this->db->query('DELETE FROM '.DB_PREFIX.'gmt_abandoned_carts WHERE ip = "'.$ip.'" AND customer_id = "'.$is_logged.'" AND email = "'.$email.'"');
				$this->db->query('INSERT INTO '.DB_PREFIX.'gmt_abandoned_carts SET id="'.$id_cart_abandoned.'", ip = "'.$ip.'", customer_id = "'.$is_logged.'", email = "'.$email.'", firstname = "'.$firstname.'", lastname = "'.$lastname.'", session_cart = "'.$session_cart.'", created ="'.date('Y-m-d H:i:s').'"');

				$result_subscribe = $this->mailchimp_list_subscribe($email, $firstname, $lastname, $id_cart_abandoned);

				$array_return['result_subscribe'] = $result_subscribe;
			}

			return $array_return;
		}

		public function mailchimp_list_subscribe($email = '', $firstname = '', $lastname = '', $id_cart_abandoned = '')
		{
			if(!empty($email) && !empty($firstname) && !empty($lastname) && !empty($id_cart_abandoned))
			{
				$this->mailchimp_list_unsubscribe($email);

				$curl_url = 'https://'.$this->server.'.api.mailchimp.com/3.0/lists/'.$this->listid.'/members/';
				$auth = base64_encode( 'user:'.$this->apikey );
				$data = array(
					'apikey'        => $this->apikey,
					'email_address' => $email,
					'status'        => 'subscribed',
					'merge_fields'  => array(
						'FNAME' => $firstname,
						'LNAME' => $lastname,
						'CART_ID' => $id_cart_abandoned
					)
				);
				$json_data = json_encode($data);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $curl_url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
					'Authorization: Basic '.$auth));
				curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
				$result = curl_exec($ch);

				$result_decode = json_decode($result);

				if(is_array($result_decode) && !array_key_exists('id', $result_decode))
					return $result;
			}
			return 'OK';
		}
		public function mailchimp_list_unsubscribe($email = '')
		{
			if(!empty($email))
			{
				$userid = md5( strtolower( $email ) );
				$curl_url = 'https://'.$this->server.'.api.mailchimp.com/3.0/lists/'.$this->listid.'/members/'.$userid;

				$auth = base64_encode( 'user:'.$this->apikey );
				$data = array(
					'apikey'        => $this->apikey,
					'email_address' => $email,
				);
				$json_data = json_encode($data);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $curl_url);
				curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'DELETE' );
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
					'Authorization: Basic '.$auth));
				curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
				$result = curl_exec($ch);

				$result_decode = json_decode($result);
			}
			return 'OK';
		}

		public function restore_cart_products()
		{
			if(array_key_exists('cart_id', $_GET) && !empty($_GET['cart_id']))
			{
				$abandoned_cart_id = $_GET['cart_id'];
				$abandoned_cart = $this->db->query("SELECT session_cart FROM ".DB_PREFIX."gmt_abandoned_carts WHERE id = '".$abandoned_cart_id."' LIMIT 1");

				if($abandoned_cart->num_rows == 1)
				{
					//call to cart clear function
					$cart_products = unserialize(base64_decode($abandoned_cart->row['session_cart']));
					
					if(is_array($cart_products))
					{
						$this->cart->clear();
						foreach ($cart_products as $key => $cp) {
							$this->cart->add($cp['product_id'], $cp['quantity'], $cp['option']);
						}
						return true;
					}
				}
			}

			return false;
		}
	}
?>