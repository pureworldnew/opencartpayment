<?php

class ModelModuleEmailTemplateEvent extends Model {

	/**
	 * Admin Create Customer
	 *
	 * @param int $customer_id
	 * @param array $data
	 * @return boolean
	 */
	public function customer_create($customer_id, $data = array()){
		$this->load->model('sale/customer');

		$customer_info = $this->model_sale_customer->getCustomer($customer_id);

		if (!$customer_info) {
			return false;
		}

		$file = DIR_SYSTEM . 'library/emailtemplate/email_template.php';

		if (file_exists($file)) {
			include_once($file);
		} else {
			trigger_error('Error: Could not load library ' . $file . '!');
			exit();
		}

		$template = new EmailTemplate($this->request, $this->registry);

		$template->addData($customer_info);

		if(!empty($data['password'])){
			$template->data['password'] = $data['password'];
		}

		$template->data['newsletter'] = $this->language->get(!empty($data['newsletter']) ? 'text_yes' : 'text_no');

		$template->data['account_login'] = $this->url->link('account/login', 'email=' . $data['email'], 'SSL');
		$template->data['account_login_tracking'] = $template->getTracking($template->data['account_login']);

		if (isset($data['customer_group_id']) && $data['customer_group_id']) {
			$this->load->model('sale/customer_group');

			$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($data['customer_group_id']);

			$template->data['customer_group'] = $customer_group_info['name'];
		}

		$template->load('admin.customer_create');

		return $template->send();
	}

}