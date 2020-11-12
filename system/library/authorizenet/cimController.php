<?php 
/**
 * Contains part of the Opencart Authorize.Net CIM Payment Module code.
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to memiiso license.
 * Please see the LICENSE.txt file for more information.
 * All other rights reserved.
 *
 * @author     memiiso <gel.yine.gel@hotmail.com>
 * @copyright  2013-~ memiiso
 * @license    Commercial License. Please see the LICENSE.txt file
 */

abstract class CimFrontController extends Controller {
	protected $error = array();
	
	public function __construct($registry){
		parent::__construct($registry);		

		$this->load->model('authorizenet/cim_customer');
  		$this->load->model('account/address');
	}	   
	
	protected function loadVariables(){

		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_validate_billing_adress'] = $this->language->get('text_validate_billing_adress');
		
		$this->data['authorizenet_cim_enable_cim_adress'] = $this->config->get('authorizenet_cim_enable_cim_adress');
		$this->data['authorizenet_cim_use_jquerydialog'] = $this->config->get('authorizenet_cim_use_jquerydialog');
		$this->data['authorizenet_cim_require_billing_adress'] = $this->config->get('authorizenet_cim_require_billing_adress');
		$this->data['authorizenet_cim_disable_bank_payment'] = $this->config->get('authorizenet_cim_disable_bank_payment');
		
		
		$this->data['text_select_cimcard'] = $this->language->get('text_select_cimcard');
		$this->data['text_select_cimadress'] = $this->language->get('text_select_cimadress');
		$this->data['text_select_wanttouse_differentaccount'] = $this->language->get('text_select_wanttouse_differentaccount');
		$this->data['text_select_wanttouse_cim'] = $this->language->get('text_select_wanttouse_cim');
		$this->data['text_select_select_cimcard'] = $this->language->get('text_select_select_cimcard');
		$this->data['text_select_select_adress'] = $this->language->get('text_select_select_adress');
		$this->data['text_select_paymentaccount'] = $this->language->get('text_select_paymentaccount');
		$this->data['text_wanttouse_newcredit_card'] = $this->language->get('text_wanttouse_newcredit_card');
		$this->data['text_wanttouse_bank_account'] = $this->language->get('text_wanttouse_bank_account');
		$this->data['text_close'] = $this->language->get('text_close');
		$this->data['text_business'] = $this->language->get('text_business');
		$this->data['text_individual'] = $this->language->get('text_individual');
		$this->data['text_credit_card'] = $this->language->get('text_credit_card');
		$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_select_prfx_bank_account'] = $this->language->get('text_select_prfx_bank_account');
		$this->data['text_select_prfx_credit_card'] = $this->language->get('text_select_prfx_credit_card');
		$this->data['text_error_create_transaction_connecting'] = $this->language->get('text_error_create_transaction_connecting');
		$this->data['text_cim_held_notify_subj'] = $this->language->get('text_cim_held_notify_subj');
		$this->data['text_cim_held_notify_message'] = $this->language->get('text_cim_held_notify_message');
		$this->data['text_cim_held_user_message'] = $this->language->get('text_cim_held_user_message');
		$this->data['text_account'] = $this->language->get('text_account');
		$this->data['text_credit_card_entries'] = $this->language->get('text_credit_card_entries');
		$this->data['text_bank_accont_entries'] = $this->language->get('text_bank_accont_entries');
		$this->data['text_create_newcredit_card'] = $this->language->get('text_create_newcredit_card');
		$this->data['text_create_bank_account'] = $this->language->get('text_create_bank_account');
		$this->data['text_adress_entries'] = $this->language->get('text_adress_entries');
		$this->data['text_cim_payment_accounts'] = $this->language->get('text_cim_payment_accounts');
		$this->data['text_single_click_setup']  = $this->language->get('text_single_click_setup');
		$this->data['text_sc_billing_address']  = $this->language->get('text_sc_billing_address');
		$this->data['text_sc_shiping_address']  = $this->language->get('text_sc_shiping_address');
		$this->data['text_sc_shiping_method']  = $this->language->get('text_sc_shiping_method');
		$this->data['text_sc_payment_card']  = $this->language->get('text_sc_payment_card');
		$this->data['text_select_prfx_credit_card'] = $this->language->get('text_select_prfx_credit_card');
		$this->data['text_select'] = $this->language->get('text_select');
		
		$this->data['entry_customer_type'] = $this->language->get('entry_customer_type');
		$this->data['entry_cc_owner'] = $this->language->get('entry_cc_owner');
		$this->data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$this->data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$this->data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
		$this->data['entry_ba_bankname'] = $this->language->get('entry_ba_bankname');
		$this->data['entry_ba_echecktype'] = $this->language->get('entry_ba_echecktype');
		$this->data['entry_ba_nameonaccount'] = $this->language->get('entry_ba_nameonaccount');
		$this->data['entry_ba_accountnumber'] = $this->language->get('entry_ba_accountnumber');
		$this->data['entry_ba_routingnumber'] = $this->language->get('entry_ba_routingnumber');
		$this->data['entry_ba_accounttype'] = $this->language->get('entry_ba_accounttype');
		$this->data['entry_savings'] = $this->language->get('entry_savings');
		$this->data['entry_businesschecking'] = $this->language->get('entry_businesschecking');
		$this->data['entry_checking'] = $this->language->get('entry_checking');
		
		$this->data['button_new_pamet_account'] = $this->language->get('button_new_pamet_account');
		$this->data['button_edit'] = $this->language->get('button_edit');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['button_set_default'] = $this->language->get('button_set_default');		
		
		$this->data['insert'] = $this->url->link('account/authorizenetcim/createpaymentprofile', '', 'SSL');
		$this->data['delete'] = $this->url->link('account/authorizenetcim/deletepaymentprofile', '', 'SSL');
		$this->data['setdefaultpayment'] = $this->url->link('account/authorizenetcim/setdefaultpaymentprofile', '', 'SSL');
		$this->data['setdefaultaddress'] = $this->url->link('account/authorizenetcim/setdefaultpaymentaddress', '', 'SSL');
		$this->data['back'] = $this->url->link('account/account', '', 'SSL');
		$this->data['not_supported'] = $this->language->get('not_supported');
		
		//
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_sc_save']  = $this->language->get('button_sc_save');
		$this->data['button_sc_select_shipping_address']  = $this->language->get('button_sc_select_shipping_address');
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		
		$this->data['months'] = array();
		
		for ($i = 1; $i <= 12; $i++) {
			$this->data['months'][] = array(
					'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)),
					'value' => sprintf('%02d', $i)
			);
		}		
		$today = getdate();		
		$this->data['year_expire'] = array();		
		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$this->data['year_expire'][] = array(
					'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
					'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}		
		if(isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		}
		if (isset($this->error['error'])) {
			$this->data['error'] = $this->error['error'];
		}			
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];		
			unset($this->session->data['success']);
		}
		
		// Load Adress
		$this->language->load('account/address');		
		$this->data['text_edit_address'] = $this->language->get('text_edit_address');
		$this->data['entry_cim_pa_billing_address'] = $this->language->get('entry_cim_pa_billing_address');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_company_id'] = $this->language->get('entry_company_id');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_default'] = $this->language->get('entry_default');
					
		$this->load->model('localisation/country');
		$this->data['countries'] = $this->model_localisation_country->getCountries();
		if (isset($this->request->post['country_id'])) {
			$this->data['country_id'] = $this->request->post['country_id'];
		}else {
			$this->data['country_id'] = $this->config->get('config_country_id');
		}
	
	}
	
	protected function syncLocalPaymentProfiles($customer_id,$cim_payment_profiles,$local_payment_profiles){
		foreach ($cim_payment_profiles as $payment){
			if (!isset($local_payment_profiles['pid_'.$payment->customerPaymentProfileId])) {		
				// non existing payment id add it to local db		
				$this->model_authorizenet_cim_customer->addPaymentProfile($customer_id,$payment->customerPaymentProfileId);
			}
		}
	}
  	 protected  function getCimCustomerProfile($cim_customerID=false) {
  			$request = new AuthorizeNetCIM();
  		
  			if ($cim_customerID) {
			$cim_customer_profile = $request->getCustomerProfile($cim_customerID);	
			if (! isset($cim_customer_profile->xml)) {
				$this->error['error'] = $this->language->get('text_cim_error_accured');
				$this->authorizenet_cim_log->write('CIM getCustomerProfile('.$cim_customerID.') Failed. (Check your connection to Authorize.net Cim server!)');
				if ($this->config->get('authorizenet_cim_email_error') == 'emailerror') {
					$this->notifyError();
				}
				return false;
			}
			if (trim($cim_customer_profile->xml->messages->message->code) == "I00001") {
					
					//check payment profiles and make sure its sycronized with local
					$local_payment_profiles = $this->model_authorizenet_cim_customer->getCimPaymentProfiles($this->customer->getId());
					$cim_payment_profiles = (isset($cim_customer_profile->xml->profile->paymentProfiles)) ? $cim_customer_profile->xml->profile->paymentProfiles : array();					
					if( count($local_payment_profiles) <> count($cim_payment_profiles)){
						$this->syncLocalPaymentProfiles($this->customer->getId(), $cim_payment_profiles, $local_payment_profiles);
					}
					return $cim_customer_profile->xml->profile;
				}
			elseif(trim($cim_customer_profile->xml->messages->message->code ==  'E00040')) {
				$this->model_authorizenet_cim_customer->deleteCimCustomer($this->customer->getId());
				$this->authorizenet_cim_log->write('CIM Customer Profile('.$cim_customerID.') Not Found On AuthorizeNet Server! Cim Customer Profile ID Deleted From Local Database. Error Code:'.$cim_customer_profile->xml->messages->message->code.' Error Message'.$cim_customer_profile->xml->messages->message->text.'');
				// deleted reinit
				return $this->getCimCustomerProfile();
			}else{
				$this->error['error'] = $this->language->get('text_cim_error_accured');
				$this->authorizenet_cim_log->write('CIM getCustomerProfile('.$cim_customerID.') Failed. Error Code:'.$cim_customer_profile->xml->messages->message->code.' Error Message'.$cim_customer_profile->xml->messages->message->text.'');
				if ($this->config->get('authorizenet_cim_email_error') == 'emailerror') {
					$this->notifyError();
				}
				return false;
			}
		}else {
			$cim_customerID=$this->createCimProfile();
			if (!$cim_customerID) {
				$this->error['error']=$this->language->get('text_error_initial_profile');				   
				if ($this->config->get('authorizenet_cim_email_error') == 'emailerror') {
					$this->notifyError();
				}
				return false;
			}else {
				return $this->getCimCustomerProfile($cim_customerID);
			}
		}
  	}
  	
  	 protected  function addPaymentProfile(&$error){
  		
  		$error=array();
  		$request = new AuthorizeNetCIM();
  		$cim_customerID = $this->model_authorizenet_cim_customer->getCimCustomerCimID($this->customer->getId());
  		
	  		$default=0;
	  		$cc_type = '';
	  		$ptype = 'CC';
	  		$last_four = '';
  			// if new cart entered validate cart then add to cim
  			$paymentProfile = new AuthorizeNetPaymentProfile();
  			//TODO make secret name except first letters.
  			$paymentProfile->customerType = 'individual';
  			if($this->request->post['select_payment_account'] == 'create_new_credit_card'){
  				$paymentProfile->payment->creditCard->cardNumber = trim(preg_replace('/\D/', '', $this->request->post['cc_number']));
  				$paymentProfile->payment->creditCard->expirationDate = $this->request->post['cc_expire_date_year'].'-'.$this->request->post['cc_expire_date_month'];
  				$paymentProfile->payment->creditCard->cardCode = $this->request->post['cc_cvv2'];
  				$paymentProfile->customerType = $this->request->post['cc_payment_customer_type'];
  				$cc_type= CreditcardType::getType(trim($this->request->post['cc_number']));
  				$last_four=substr($this->request->post['cc_number'], -4);
  			}elseif($this->request->post['select_payment_account'] == 'create_new_bank_account'){
  				$paymentProfile->payment->bankAccount->routingNumber = $this->request->post['ba_routingnumber'];
  				$paymentProfile->payment->bankAccount->accountNumber = $this->request->post['ba_accountnumber'];
  				$paymentProfile->payment->bankAccount->nameOnAccount = $this->request->post['ba_nameonaccount'];
  				$paymentProfile->customerType = $this->request->post['ba_payment_customer_type'];
  				// Optional
  				//$paymentProfile->payment->bankAccount->accountType = $this->request->post['ba_accounttype'];
  				//$paymentProfile->payment->bankAccount->echeckType = $this->request->post['ba_echecktype'];
  				if(isset($this->request->post['ba_bankname'])) $paymentProfile->payment->bankAccount->bankName = $this->request->post['ba_bankname'];
  				$ptype = 'BA';
  				$last_four=substr($this->request->post['ba_accountnumber'], -4);
  			}  			

  			if($this->config->get('authorizenet_cim_require_billing_adress') == 'forcebillingadress') {
  				$address = new AuthorizeNetAddress;
  				$address->firstName = $this->request->post['firstname'];
  				$address->lastName =  $this->request->post['lastname'];
  				if (isset($this->request->post['company'])) $address->company =  $this->request->post['company'];
  				$address->address =  $this->request->post['address_1'];
  				$address->city =  $this->request->post['city'];
  				$address->zip =  $this->request->post['postcode']; 
  				$address->phoneNumber =  $this->request->post['telephone']; 
  				
  				$this->load->model('localisation/country');
  				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']); 
  				$address->country =  $country_info['name'];

  				$this->load->model('localisation/zone');
  				$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);				
  				$address->state =  $zone_info['name'];
  				
  				if(isset($this->request->post['phone'])) { $address->country =  $this->request->post['phone']; } ;
  				if(isset($this->request->post['fax'])) { $address->country =  $this->request->post['fax']; } ;
  				$paymentProfile->billTo = $address;
  			}
  			
  			// create payment profile.
  			$resp=$request->createCustomerPaymentProfile($cim_customerID, $paymentProfile,AUTHORIZENET_VALIDATIONMODE);
  			$payment_profile_id = false;
  			if (trim($resp->xml->messages->message->code)== "I00001"){
  				$payment_profile_id = $resp->xml->customerPaymentProfileId;
  				$this->model_authorizenet_cim_customer->addPaymentProfile($this->customer->getId(),$payment_profile_id,$cc_type,$default);
  				$error['success']=$this->language->get('text_sucess_insert');
  				$error['success_url']=$this->url->link('account/authorizenetcim', '', 'SSL');
  			}elseif(trim($resp->xml->messages->message->code)=="E00039"){
  				
  				if (($PaymentIDFound=$this->findDuplicateCard($cim_customerID, $last_four, $ptype)) !=false) {
  					$error['error']=$this->language->get('text_error_already_have_same_pp');
  					$payment_profile_id =  $PaymentIDFound;
  				}else{
  					$json['error'] = $this->language->get('text_error_duplicate_cim_account');
  					$json['error'] .= ' '.$resp->xml->messages->message->text;
  					$this->authorizenet_cim_log->write('AUTHNET CREATE PAYMENT ACCOUNT ERROR: Error Code:'.$resp->xml->messages->message->code.' Error Message'.$resp->xml->messages->message->text);
  					//$payment_profile_id =  false;
  				}
  			}else {
  				$error['error'] = $this->language->get('text_error_create_pament_account');
  				$error['error'] .= ' '.$resp->xml->messages->message->text;
  				$this->authorizenet_cim_log->write('AUTHNET CREATE PAYMENT ACCOUNT ERROR: Error Code:'.$resp->xml->messages->message->code.' Error Message'.$resp->xml->messages->message->text);
  				//$payment_profile_id = false;
  			}
  		
  		return $payment_profile_id;
  		
  	}    	  	  	
  	 protected  function notifyError(){
  	 	$email_address= $this->config->get('config_email');
  		$subj = $this->language->get('text_error_email_subj');
  		$body = $this->language->get('text_error_email_message');
 		$this->send_cim_email($email_address, $email_address, $subj, $body);
  	}  	
  	
  	protected function send_cim_email($from,$to,$subj,$body){
  		$mail = new Mail();
  		$mail->protocol = $this->config->get('config_mail_protocol');
  		$mail->parameter = $this->config->get('config_mail_parameter');
  		$mail->hostname = $this->config->get('config_smtp_host');
  		$mail->username = $this->config->get('config_smtp_username');
  		$mail->password = $this->config->get('config_smtp_password');
  		$mail->port = $this->config->get('config_smtp_port');
  		$mail->timeout = $this->config->get('config_smtp_timeout');
  		$email_address= $this->config->get('config_email');
  		$mail->setTo($to);	
  		$mail->setFrom($from);
  		$mail->setSender($email_address);
  		$mail->setSubject($subj);
  		$mail->setText($body);
  		$mail->send();
  	}
  	
  	protected  function notifyOnHoldOrderStatus($email_to,$order_id,$response_text){
  		$email_address= $this->config->get('config_email');
  		$subj = $order_id." ".$this->language->get('text_cim_held_notify_subj');
  		$body = $this->language->get('text_cim_held_notify_message')."\n CIM Response Details: ".$response_text;
  		$this->send_cim_email($email_address, $email_address, $subj, $body);  		
  		foreach ($email_to as $to){
  			$this->send_cim_email($email_address, $to, $subj, $body);
  		}
  	}
  	
  	 protected   function createCimProfile(){
  		// init customer cim profile
  		$request = new AuthorizeNetCIM();
  		$customerProfile = new AuthorizeNetCustomer;
  		$customerProfile->description = $this->customer->getFirstName().' '.$this->customer->getLastName();
  		$customerProfile->merchantCustomerId = $this->customer->getId();
  		$customerProfile->email = $this->customer->getEmail();
  		// $cim_customer_profile = $request->createCustomerProfile($customerProfile,AUTHORIZENET_VALIDATIONMODE);
  		$cim_customer_profile = $request->createCustomerProfile($customerProfile,'none');
  	
  		
  		if (trim($cim_customer_profile->xml->messages->message->code) == "I00001") {
  			$cim_customerID=$cim_customer_profile->getCustomerProfileId();
  			$this->model_authorizenet_cim_customer->addCimCustomer($this->customer->getId(),$cim_customerID);
  			$this->model_authorizenet_cim_customer->deletePaymentProfiles($this->customer->getId());
  			return $cim_customerID;
  		}elseif(trim($cim_customer_profile->xml->messages->message->code) == "E00039") {
  			$msg_text = $cim_customer_profile->xml->messages->message->text;
  			//$msg_text = str_replace(strtoupper(' ','',$msg_text));
  			preg_match('/\d+/', $msg_text, $match);
  			$cim_customerID =$match[0];
  			if(is_numeric($cim_customerID)){
	  			$this->model_authorizenet_cim_customer->addCimCustomer($this->customer->getId(),$cim_customerID);
	  			return $cim_customerID;
  			} else{
  				//$this->deleteCimProfile();
  				//return $this->createCimProfile();
  				return false;
  			}
  		}else{
  			$this->error['error'] = $this->language->get('text_error_initial_profile');
  			$this->authorizenet_cim_log->write('AUTHNET CREATE PROFILE ERROR: Error Code:'.$cim_customer_profile->xml->messages->message->code.' Error Message'.$cim_customer_profile->xml->messages->message->text);
  			return false;
  		}
  		
  		
  	}
  	
  	protected  function createCimOrder($cim_customerID, $cim_paymentID, $order_info, &$json){
  		$this->load->model('account/order');
  		// Create Auth & Capture Transaction
  		$transaction = new AuthorizeNetTransaction();
  		$transaction->amount = $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, false);
  		$transaction->customerProfileId = (string)$cim_customerID."";
  		$transaction->customerPaymentProfileId = (string)$cim_paymentID."";
  		//$transaction->currency_code = $this->currency->getCode();
  		
  		$order_total_info = $this->model_account_order->getOrderTotals($this->session->data['order_id']);
  		
  		foreach ($order_total_info as $order_total_item){
  			if ($order_total_item['code'] == 'tax') {
  				$transaction->tax->amount = (string)$this->currency->format($order_total_item['value'], $order_info['currency_code'], 1.00000, false);
  				$transaction->tax->name = $order_total_item['code'];
  				$transaction->tax->description = $order_total_item['title'];
  				break;
  			}
  		}
  		foreach ($order_total_info as $order_total_item){
  			if ($order_total_item['code'] == 'shipping') {
  				$transaction->shipping->amount = (string) $this->currency->format($order_total_item['value'], $order_info['currency_code'], 1.00000, false);
  				$transaction->shipping->name = $order_total_item['code'];
  				$transaction->shipping->description = $order_total_item['title'];
  				break;
  			}
  		}
  		
  		$transaction->order->purchaseOrderNumber = $order_info['order_id'];// order id  ??
  		$transaction->order->invoiceNumber = $order_info['invoice_prefix'].' '.$order_info['invoice_no'].'-'.$order_info['order_id'];
  		$transaction->order->description = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
  		$transaction->order->description .='. Customer ID:'.$this->customer->getId().'. Order ID:'.$order_info['order_id'].'. Order Comment:'.$order_info['comment'];
  		
  		if ( $this->config->get('authorizenet_cim_use_shipping_address') == 'usecimshippingaddress' ) {
	  		if (isset($this->session->data['shipping_address_id']) && ($this->session->data['shipping_address_id'])) {
	  			$customerCimShippingAddressId = $this->getCimShippingAdress($cim_customerID, $this->session->data['shipping_address_id']);
	  			if ($customerCimShippingAddressId && $customerCimShippingAddressId > 0) {
	  				$transaction->customerShippingAddressId = (string)$customerCimShippingAddressId."";
	  			}else {
	  				$this->authorizenet_cim_log->write('Error Occured getting Customer cim shipping adress createCimOrder(), Customer:'.$this->customer->getId().' customerPaymentProfileId: '.$cim_paymentID);
	  			}
	  		}
  		}
  		
  		if ( $this->config->get('authorizenet_cim_fill_line_items') == 'filllineitems' ) {
  			$order_products = $this->model_account_order->getOrderProducts($this->session->data['order_id']);
  			foreach ($order_products as $order_product){
  				$lineItem              = new AuthorizeNetLineItem();
  				$lineItem->itemId      = $order_product['product_id']; // $order_product['order_product_id']
  				$lineItem->name        = substr($order_product['name'],0, 31);
  				$lineItem->description = substr("Model:".$order_product['model'],0, 255);
  				$lineItem->quantity    = $order_product['quantity'];
  				$lineItem->unitPrice   = $order_product['price'];
  				$lineItem->taxable     = "true";

  				$transaction->lineItems[] = $lineItem;
  			}
  		}
  		
  		$transaction_method = $this->config->get('authorizenet_cim_method') ;
  		$cim_order_status =  $this->config->get('authorizenet_cim_order_status_id');
  		 		
  		if(isset($_SERVER['REMOTE_ADDR'])) {
  			$extra_opt = 'x_customer_ip='.$_SERVER['REMOTE_ADDR'].'&';
  			// <![CDATA[x_customer_ip=100.0.0.1&x_authentication_indicator=5&x_cardholder_authentication_value=uq3wDbqt8A26rfANAAAAAP]]>
  		}else{
  			$extra_opt = 'x_customer_ip=0.0.0.0&';
  		}

  		// $this->authorizenet_cim_log->write($extra_opt);
  		$request = new AuthorizeNetCIM();
  		$response = $request->createCustomerProfileTransaction($transaction_method, $transaction,$extra_opt);
  		$transactionResponse = $response->getTransactionResponse();
  		$message='';
  		$message .= 'Response Xml Code: ' . $response->xml->messages->message->code  . "\n";
  		$message .= 'Response Xml message: ' . $response->xml->messages->message->text  . "\n";
  		$message .= 'Transection Id: ' . $transactionResponse->transaction_id  . "\n";
  		$message .= 'Response Code: ' . $transactionResponse->response_code  . "\n";
  		$message .= 'Response Sub Code: ' . $transactionResponse->response_subcode  . "\n";
  		$message .= 'Response Reason Code: ' . $transactionResponse->response_reason_code  . "\n";
  		$message .= 'Response Reason Text: ' . $transactionResponse->response_reason_text  . "\n";
  		$message .= 'Authorization Code: ' . $transactionResponse->authorization_code  . "\n";
  		$message .= 'AVS Response: ' . $transactionResponse->avs_response . "\n";
  		$message .= 'Transaction ID: ' . $transactionResponse->transaction_id  . "\n";
  		$message .= 'Card Code Response: ' . $transactionResponse->card_code_response  . "\n";
  		$message .= 'Cardholder Authentication Verification Response: ' . $transactionResponse->cavv_response . "\n"; 		
  		$this->model_authorizenet_cim_customer->addCimOrderResponse(
  			$this->session->data['order_id']
  			,'CIM'
  			,$transaction->customerProfileId
  			,$transaction->customerPaymentProfileId
  			,$transactionResponse->transaction_id
  			,$transactionResponse->account_number
  			,$transactionResponse->response_code
  			,$transactionResponse->response_reason_code
  			,$transactionResponse->response_reason_text
  			,$message
  			,"-" //json_encode($transactionResponse)
  		); 		
  			
  		$order_statuses = $this->model_authorizenet_cim_customer->getOrderStatuses();
  		$order_status_id_list = array();
		foreach ($order_statuses as $order_status){
			$order_status_id_list[] = $order_status['order_status_id'];
		}
		$held_order_notify=false;
		if ( trim($this->config->get('authorizenet_cim_held_notify_customer')) == 'notifycustomeronhold'){
			$held_order_notify=true;
		}		
				

  		$held_rules = explode('|', trim($this->config->get('authorizenet_cim_held_rule_list'))); 
		
  		$held_rule_applied = false;
  		foreach ($held_rules as $held_rule){
  			$tmp = explode(';', trim($held_rule));
  				if (count($tmp) == 4 && ($tmp[0] == 'ALL' || $tmp[0] == $transactionResponse->response_code) ) {
	  				if ($tmp[1] == 'ALL' || $tmp[1] == $transactionResponse->response_reason_code) {
	  					if ($tmp[2] == 'ALL' || $tmp[2] == trim(strtoupper($transactionResponse->avs_response))) {
	  						if ($tmp[3] && is_numeric($tmp[3]) && in_array($tmp[3], $order_status_id_list)) {
		  						$cim_order_status = $tmp[3];
		  						$held_rule_applied = true;
		  						break;
	  						}
	  					}
	  				}
  				}
  		}  		
  		
  		// successful payment process
  		if (trim($response->xml->messages->message->code) == "I00001") {
  			// Check for On Hold Process
  			if (isset($response->xml->directResponse) && $held_rule_applied){
	  				// update order status
	  				$this->model_checkout_order->confirm($this->session->data['order_id'], $cim_order_status,$message,$held_order_notify);
	  				$json['success_held'] = $this->language->get('text_cim_held_user_message');	  				
	  				// notify on hold status to admin
	  				$email_to = explode(',', trim($this->config->get('authorizenet_cim_held_notificatin_emails')));
	  				if ($email_to && !empty($email_to) ) {
	  					$this->notifyOnHoldOrderStatus($email_to,$this->session->data['order_id'],$message);
	  				}
	  			}else{
	  				// Not On hold status
	  				$this->model_checkout_order->confirm($this->session->data['order_id'], $cim_order_status,$message,false);
	  			}			
  			$json['checkout_success_url'] = $this->url->link('checkout/success', '', 'SSL');
  			return $this->session->data['order_id'];
  		}else{
  			// Failed Process
  			$json['error'] = $this->language->get('text_error_create_transaction').' '.$transactionResponse->response_reason_text;  			
  			if (strpos(strtoupper($transactionResponse->error_message),'ERROR CONNECTING TO AUTHORIZENET') !== false) {
  				$json['error'] = $this->language->get('text_error_create_transaction_connecting').' '.$transactionResponse->response_reason_text;
  			}
  			$this->authorizenet_cim_log->write('AUTHNET CREATE TRANSECTION ERROR: createCimOrder(), Customer:'.$this->customer->getId().' customerPaymentProfileId: '.$cim_paymentID.' Response Code:'.$transactionResponse->response_code.' Error Message: '.$transactionResponse->error_message);
  			if($this->config->get('authorizenet_cim_debug_log')=='create'){
  				$this->authorizenet_cim_log->write($request->getPostString());  				
  			}
  			return false;
		}
  	}
  	

  	protected  function createAimOrder($order_info, &$json){
  		$this->load->model('account/order');
  		// CC fields validation sss
  		$this->request->post['select_payment_account'] = 'create_new_credit_card';
  		if(!$this->validateForm($json)){
  			return false;
  		} 		

  		$sale = new AuthorizeNetAIM;
  	
  		$creditCard = array(
  				'exp_date' => $this->request->post['cc_expire_date_month'].'/'.$this->request->post['cc_expire_date_year'],
  				'card_num' => trim(preg_replace('/\D/', '', $this->request->post['cc_number'])),
  				'card_code' => $this->request->post['cc_cvv2'],
  		);
  	
  		$transaction = array(
  				'amount' => $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, false),
  				'duplicate_window' => '10',
  				// 'email_customer' => 'true',
  				'footer_email_receipt' => 'thank you for your business!',
  				'header_email_receipt' => 'a copy of your receipt is below',
  		);
  	
  		$order = array(
  				'description' => 'Order ID:'.$order_info['order_id'].' Customer ID: 0-Quest Order Comment:'.$order_info['comment'] ,
  				'invoice_num' => $order_info['invoice_prefix'].' '.$order_info['invoice_no'].'-'.$order_info['order_id'],
  				'line_item' => '',
  		);
  	
  		$customer = (object)array();
  		$customer->first_name = $this->session->data['guest']['payment']['firstname'];
  		$customer->last_name = $this->session->data['guest']['payment']['lastname'];
  		$customer->company = $this->session->data['guest']['payment']['company'];
  		$customer->address = $this->session->data['guest']['payment']['address_1']." ".$this->session->data['guest']['payment']['address_2'];
  		$customer->city = $this->session->data['guest']['payment']['city'];
  		$customer->state = $this->session->data['guest']['payment']['zone_code'];
  		$customer->zip = $this->session->data['guest']['payment']['postcode'];
  		$customer->country = $this->session->data['guest']['payment']['iso_code_2'];
  		$customer->phone = $this->session->data['guest']['telephone'];
  		$customer->fax = $this->session->data['guest']['fax'];
  		$customer->email = $this->session->data['guest']['email'];
  		$customer->cust_id = "0";
  		$customer->customer_ip = getenv('REMOTE_ADDR');
  		
  		$order_total_info = $this->model_account_order->getOrderTotals($order_info['order_id']);
  	
  		$shipping_info = (object)array();
  		if ( $this->config->get('authorizenet_cim_use_shipping_address') == 'usecimshippingaddress' ) {
  			$shipping_info->ship_to_first_name = $this->session->data['guest']['shipping']['firstname'];
  			$shipping_info->ship_to_last_name = $this->session->data['guest']['shipping']['lastname'];
  			$shipping_info->ship_to_company = $this->session->data['guest']['shipping']['company'];
  			$shipping_info->ship_to_address = $this->session->data['guest']['shipping']['address_1']." ".$this->session->data['guest']['shipping']['address_2'];
  			$shipping_info->ship_to_city = $this->session->data['guest']['shipping']['city'];
  			$shipping_info->ship_to_state = $this->session->data['guest']['shipping']['zone_code'];
  			$shipping_info->ship_to_zip = $this->session->data['guest']['shipping']['postcode'];
  			$shipping_info->ship_to_country = $this->session->data['guest']['shipping']['iso_code_2'];
  			/*
  			 $shipping_info->tax = $this->session->data['guest']['fax'];"CA";
  			$shipping_info->freight = "Freight<|>ground overnight<|>12.95";
  			$shipping_info->duty = "Duty1<|>export<|>15.00";
  			$shipping_info->tax_exempt = "false";
  			$shipping_info->po_num = "12";
  			*/					
			// Set shippinf price 
  			foreach ($order_total_info as $order_total_item){
  				if ($order_total_item['code'] == 'shipping') {
  					$oc_freight =  $order_total_item['title'].'<|>';
  					$oc_freight .=  $order_total_item['code'].'<|>';
  					$oc_freight .= (string) $this->currency->format($order_total_item['value'], $order_info['currency_code'], 1.00000, false);  						
  					$shipping_info->freight = $oc_freight;
  					break;
  				}
  			}  			
  		}
  		  	
  		$sale->setFields($creditCard);
  		$sale->setFields($shipping_info);
  		$sale->setFields($customer);
  		$sale->setFields($order);
  		//  Set Tax amount 
  		foreach ($order_total_info as $order_total_item){
  			if ($order_total_item['code'] == 'tax') {
  				$sale->tax = (string)$this->currency->format($order_total_item['value'], $order_info['currency_code'], 1.00000, false);
  				break;
  			}
  		}

  		
  		if ( $this->config->get('authorizenet_cim_fill_line_items') == 'filllineitems' ) {
  			$this->load->model('account/order');
  			$order_products = $this->model_account_order->getOrderProducts($this->session->data['order_id']);
  			$line_items = array();
  			foreach ($order_products as $order_product){
  				$sale->addLineItem(
  						"itemID:".$order_product['product_id']
  						, substr("Model:".$order_product['model'],0, 255)
  						, substr("Model:".$order_product['model'],0, 255)
  						, $order_product['quantity']
  						, $order_product['price']
  						, "Y"
  				);
  			}
  		}  		
  		$sale->setFields($transaction);
  	
  		$transaction_method = $this->config->get('authorizenet_cim_method') ;
  		$cim_order_status =  $this->config->get('authorizenet_cim_order_status_id');
  		
  	
  		if($transaction_method=="AuthOnly") {
  			$response = $sale->authorizeOnly();
  		}elseif($transaction_method=="CaptureOnly"){
  			$response = $sale->captureOnly();
  		}else{
  			$response = $sale->authorizeAndCapture();
  		}
  	
  		$message='';
  		$message .= 'Response Xml Code: '  . "\n";
  		$message .= 'Response Xml message: ' . "\n";
  		$message .= 'Transection Id: ' . $response->transaction_id  . "\n";
  		$message .= 'Response Code: ' . $response->response_code  . "\n";
  		$message .= 'Response Sub Code: ' . $response->response_subcode  . "\n";
  		$message .= 'Response Reason Code: ' . $response->response_reason_code  . "\n";
  		$message .= 'Response Reason Text: ' . $response->response_reason_text  . "\n";
  		$message .= 'Authorization Code: ' . $response->authorization_code  . "\n";
  		$message .= 'AVS Response: ' . $response->avs_response . "\n";
  		$message .= 'Transaction ID: ' . $response->transaction_id  . "\n";
  		$message .= 'Card Code Response: ' . $response->card_code_response  . "\n";
  		$message .= 'Cardholder Authentication Verification Response: ' . $response->cavv_response . "\n";
  		$this->model_authorizenet_cim_customer->addCimOrderResponse(
  				$this->session->data['order_id']
  				,'AIM'
  				,'0'
  				,'0'
  				,$response->transaction_id
  				,$response->account_number
  				,$response->response_code
  				,$response->response_reason_code
  				,$response->response_reason_text
  				,$message
  				,"-" //json_encode($response)
  		);
  			
  		$order_statuses = $this->model_authorizenet_cim_customer->getOrderStatuses();
  		$order_status_id_list = array();
  		foreach ($order_statuses as $order_status){
  			$order_status_id_list[] = $order_status['order_status_id'];
  		}
  		$held_order_notify=false;
  		if ( trim($this->config->get('authorizenet_cim_held_notify_customer')) == 'notifycustomeronhold'){
  			$held_order_notify=true;
  		}
  		
  		$held_rules = explode('|', trim($this->config->get('authorizenet_cim_held_rule_list')));
  		
  		$held_rule_applied = false;
  		foreach ($held_rules as $held_rule){
  			$tmp = explode(';', trim($held_rule));
  			if (count($tmp) == 4 && ($tmp[0] == 'ALL' || $tmp[0] == $response->response_code) ) {
  				if ($tmp[1] == 'ALL' || $tmp[1] == $response->response_reason_code) {
  					if ($tmp[2] == 'ALL' || $tmp[2] == trim(strtoupper($response->avs_response))) {
  						if ($tmp[3] && is_numeric($tmp[3]) && in_array($tmp[3], $order_status_id_list)) {
  							$cim_order_status = $tmp[3];
  							$held_rule_applied = true;
  							break;
  						}
  					}
  				}
  			}
  		}
  		
  			// Check for On Hold Process
  			if (isset($response->response_code) && $held_rule_applied){
  				// update order status
  				$this->model_checkout_order->confirm($this->session->data['order_id'], $cim_order_status,$message,$held_order_notify);
  				$json['success_held'] = $this->language->get('text_cim_held_user_message');
  				// notify on hold status to admin
  				$email_to = explode(',', trim($this->config->get('authorizenet_cim_held_notificatin_emails')));
  				if ($email_to && !empty($email_to) ) {
  					$this->notifyOnHoldOrderStatus($email_to,$this->session->data['order_id'],$message);
  				}
  				$json['checkout_success_url'] = $this->url->link('checkout/success', '', 'SSL');
  				return $this->session->data['order_id'];
  				
  			}else if(trim($response->response_code) == 1){
  				// Not On hold status and succeded
  				$this->model_checkout_order->confirm($this->session->data['order_id'], $cim_order_status,$message,false);
  				$json['checkout_success_url'] = $this->url->link('checkout/success', '', 'SSL');
  				return $this->session->data['order_id'];
  			}else{
  			// Failed Process
  			$json['error'] = 'AUTHNET CREATE TRANSECTION ERROR: '.$response->response_reason_text;
  			$this->authorizenet_cim_log->write('AUTHNET CREATE TRANSECTION ERROR: Quest AIM Order: createCimOrder: 0 customerPaymentProfileId: 0 Response Code:'.$response->response_code.' Error Message: '.$response->error_message);
  			return false;
  		}
  	}
  	 
  	 protected  function findDuplicateCard($cim_customerID,$lastfourdigit,$type="CC"){
  		$match=array();
  		//$request = new AuthorizeNetCIM();
  		$customerProfile = $this->getCimCustomerProfile($cim_customerID);
  		if (! isset($customerProfile->paymentProfiles)) return false;
  		
  		foreach ($customerProfile->paymentProfiles as $paymentcard) {
  			$tmp="";
  			if (isset($paymentcard->payment->creditCard) && $type=="CC") {
  				$tmp=substr($paymentcard->payment->creditCard->cardNumber, -4);
  			}elseif (isset($paymentcard->payment->bankAccount) && $type=="BA") {
  				$tmp=substr($paymentcard->payment->bankAccount->accountNumber, -4);
  			}
  			if ($tmp==$lastfourdigit) {
  				$match[]=$paymentcard->customerPaymentProfileId;
  			}
  		}
  		if (count($match)==1){
  			return $match[0];
  		}else return false;
  	}
  	
  	protected function validateForm(&$json){
  		$err='';
  		if (isset($this->request->post['select_payment_account']) && $this->request->post['select_payment_account'] == 'create_new_bank_account' ) {
  			if ((utf8_strlen($this->request->post['ba_routingnumber']) < 1) || (utf8_strlen($this->request->post['ba_routingnumber']) > 32)) {
  				$err .= "\n".$this->language->get('text_error_required_field').' '.$this->language->get('entry_ba_routingnumber');
  			}
  			if ((utf8_strlen($this->request->post['ba_accountnumber']) < 1) || (utf8_strlen($this->request->post['ba_accountnumber']) > 32)) {
  				$err .= "\n".$this->language->get('text_error_required_field').' '.$this->language->get('entry_ba_accountnumber');
  			}
  			if ((utf8_strlen($this->request->post['ba_nameonaccount']) < 1) || (utf8_strlen($this->request->post['ba_nameonaccount']) > 32)) {
  				$err .= "\n".$this->language->get('text_error_required_field').' '.$this->language->get('entry_ba_nameonaccount');
  			}
  		}elseif (isset($this->request->post['select_payment_account']) && $this->request->post['select_payment_account'] == 'create_new_credit_card'){
  			if ((utf8_strlen($this->request->post['cc_number']) < 1) || (utf8_strlen($this->request->post['cc_number']) > 32)) {
  				$err .= "\n".$this->language->get('text_error_required_field').' '.$this->language->get('entry_cc_number');
  			}
  		}else{
  			$err = $this->language->get('error_unknown_account_type_selected');
  		}

  		// validate billing adress
  		if($this->config->get('authorizenet_cim_require_billing_adress') == 'forcebillingadress') {
  			$this->language->load('account/address');
  			if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
  				$err .= "\n".$this->language->get('error_firstname');
  			}  			
  			if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
  				$err .= "\n".$this->language->get('error_lastname');
  			}  			
  			if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
  				$err .= "\n".$this->language->get('error_address_1');
  			}  	
	    	if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
	      		$err .= "\n".$this->language->get('error_telephone');
	    	}		
  			if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
  				$err .= "\n".$this->language->get('error_city');
  			}  			
  			$this->load->model('localisation/country');  			
  			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);  			
  			if ($country_info) {
  				if ($country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
  					$err .= "\n".$this->language->get('error_postcode');
  				}
  				// VAT Validation
  				$this->load->helper('vat');  					
  				if ($this->config->get('config_vat') && !empty($this->request->post['tax_id']) && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
  					$err .= "\n".$this->language->get('error_vat');
  				}
  			}  			
  			if ($this->request->post['country_id'] == '') {
  				$err .= "\n".$this->language->get('error_country');
  			}  			
  			if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
  				$err .= "\n".$this->language->get('error_zone');
  			}
  		}
  	
  		if ($err && $err <> '') {
  			$json['error'] = $err;
  			return false;
  		} else {
  			return true;
  		}
  	}
  	
  	/* CIM SHIPPING  ADDRESSES */  	
  	protected function getCimShippingAdress($cim_customerID,$shipping_adress_id){

  		$cim_shipping_adress_id = $this->model_authorizenet_cim_customer->getCustomerCimShippingAddressId($this->customer->getId(),$shipping_adress_id);
  		$address_info = $this->model_account_address->getAddress($shipping_adress_id);
  		
  		/* We have adress check if its changed  */ 
 		if ($cim_shipping_adress_id) {
	  		$request = new AuthorizeNetCIM();
			$cim_adress_info = $request->getCustomerShippingAddress($cim_customerID, $cim_shipping_adress_id);
			/* if its changed update it */
			$flag_changed=true;
			$cim_shipping_adress= $cim_adress_info->xml->address;
  			if(
  					(trim($cim_adress_info->xml->messages->message->code)=='I00001') && 
  					$cim_shipping_adress->firstName == trim($address_info['firstname']) && 
  					$cim_shipping_adress->lastName == trim($address_info['lastname'])  && 
  					$cim_shipping_adress->address == trim(trim($address_info['address_1']).' '.trim($address_info['address_2']))  && 
  					$cim_shipping_adress->city == trim($address_info['city'])  && 
  					$cim_shipping_adress->state == trim($address_info['zone'])  && 
  					$cim_shipping_adress->zip == trim($address_info['postcode'])  && 
  					$cim_shipping_adress->country == trim($address_info['country'])
  			) $flag_changed = false;
	  		
			if($flag_changed){
				$this->authorizenet_cim_log->write('CIM getCimShippingAdress: Opencart Shipping adress changed updating Cim Shipping Adress ');
				/* update suceess */
				if($this->updateCimShippingAdress($cim_customerID, $cim_shipping_adress_id, $shipping_adress_id)){
					$this->authorizenet_cim_log->write('CIM getCimShippingAdress: updateCimShippingAdress Succesfull ');
					return $cim_shipping_adress_id;
					/* update failed delete and add it */
				}else {
					$this->authorizenet_cim_log->write('CIM getCimShippingAdress: updateCimShippingAdress Failed. Deleting and adding Shipping to Cim ');
		  			$this->deleteCimShippingAdress($cim_customerID, $cim_shipping_adress_id, $shipping_adress_id);
		  			return $this->addCimShippingAdress($cim_customerID, $shipping_adress_id);
				}
			}else{			
				/* Not Changed return it */ 
				return $cim_shipping_adress_id;
			}
 		}else{
 			$this->model_authorizenet_cim_customer->addCustomerCimShippingAddressId($this->customer->getId(),$shipping_adress_id,'0');
 			return $this->addCimShippingAdress($cim_customerID, $shipping_adress_id);
 		}
  	}
  	protected function updateCimShippingAdress($cim_customerID,$cim_shipping_adress_id,$shipping_adress_id){
  		$this->load->model('account/address');
  		$address_info = $this->model_account_address->getAddress($shipping_adress_id);
  		
  		$cim_address = new AuthorizeNetAddress();
  		
  		$cim_address->firstName = $address_info['firstname'] ;
  		$cim_address->lastName = $address_info['lastname'] ;
  		if(isset($address_info['company']) && $address_info['company']) { $cim_address->company = $address_info['company'] ;}
  		$cim_address->address = $address_info['address_1'].' '.$address_info['address_2'] ;
  		$cim_address->city = $address_info['city'] ;
  		$cim_address->state = $address_info['zone'] ;
  		$cim_address->zip = $address_info['postcode'] ;
  		$cim_address->country = $address_info['country'] ;
  		//$cim_address->phoneNumber = $address_info['telephone'] ;
  		//$cim_address->faxNumber = $address_info['fax'] ;
  		//$cim_address->customerAddressId = $address_id ;
  		
  		$request = new AuthorizeNetCIM();
  		$response = $request->updateCustomerShippingAddress($cim_customerID, $cim_shipping_adress_id,$cim_address);
  		if (trim($response->xml->messages->message->code) == "I00001") {
  			return true;
  		}else{
  			$this->authorizenet_cim_log->write('CIM updateCimShippingAdress('.$cim_customerID.') Failed. Error Code:'.$response->xml->messages->message->code.' Error Message'.$response->xml->messages->message->text.'');
  			$this->model_authorizenet_cim_customer->addCustomerCimShippingAddressId($this->customer->getId(),$shipping_adress_id,'0');  				
  			return false;	
  		}
  	}
  	protected function addCimShippingAdress($cim_customerID,$shipping_adress_id){  	
  		$this->load->model('account/address');
  		$address_info = $this->model_account_address->getAddress($shipping_adress_id);

  		$cim_address = new AuthorizeNetAddress();
  		
  		$cim_address->firstName = trim($address_info['firstname'] );
  		$cim_address->lastName = trim($address_info['lastname']) ;
  		if(isset($address_info['company']) && $address_info['company']) { $cim_address->company = trim($address_info['company']) ;}
  		$cim_address->address = trim(trim($address_info['address_1']).' '.trim($address_info['address_2'])) ;
  		$cim_address->city = trim($address_info['city'] );
  		$cim_address->state = trim($address_info['zone'] );
  		$cim_address->zip = trim($address_info['postcode']) ;
  		$cim_address->country = trim($address_info['country']) ;
  		//$cim_address->phoneNumber = $address_info['telephone'] ;
  		//$cim_address->faxNumber = $address_info['fax'] ;
  		//$cim_address->customerAddressId = $address_id ;
  		
  		$request = new AuthorizeNetCIM();
  		$response = $request->createCustomerShippingAddress($cim_customerID, $cim_address);
  		if (trim($response->xml->messages->message->code) == "I00001") { 				
  			$cim_shipping_adress_id = $response->getCustomerAddressId();
  			$this->model_authorizenet_cim_customer->addCustomerCimShippingAddressId($this->customer->getId(),$shipping_adress_id,$cim_shipping_adress_id);  				
  			return  $cim_shipping_adress_id;
  		}elseif (trim($response->xml->messages->message->code) == "E00039") {
  			$cim_customer = $this->getCimCustomerProfile($cim_customerID);
  			$cim_no_shipping_adress_id = false;
  			foreach ($cim_customer->shipToList as $cim_shipping_adress){
  				if(
  				$cim_shipping_adress->firstName == trim($address_info['firstname']) &&
  				$cim_shipping_adress->lastName == trim($address_info['lastname']) &&
  				$cim_shipping_adress->address == trim(trim($address_info['address_1']).' '.trim($address_info['address_2'])) &&
  				$cim_shipping_adress->city == trim($address_info['city']) &&
  				$cim_shipping_adress->state == trim($address_info['zone']) &&
  				$cim_shipping_adress->zip == trim($address_info['postcode']) &&
  				$cim_shipping_adress->country == trim($address_info['country'])
  				){
  					$cim_no_shipping_adress_id = $cim_shipping_adress->customerAddressId;
  					break;
  				}
  			}
  			if ($cim_no_shipping_adress_id) {
  				$this->model_authorizenet_cim_customer->addCustomerCimShippingAddressId($this->customer->getId(),$shipping_adress_id,$cim_no_shipping_adress_id);
  			}
  			return $cim_no_shipping_adress_id;
  		}else{
  			$this->authorizenet_cim_log->write('CIM updateCimAddress('.$cim_customerID.') Failed. Error Code:'.$response->xml->messages->message->code.' Error Message'.$response->xml->messages->message->text.'');
  			// remove cim shipping adress id
  			$this->model_authorizenet_cim_customer->addCustomerCimShippingAddressId($this->customer->getId(),$shipping_adress_id,'0');
  			return false;
  		}  		
  	}
  	protected function deleteCimShippingAdress($cim_customerID,$cim_shipping_adress_id,$shipping_adress_id){
  		$this->load->model('account/address');
  		
  		$request = new AuthorizeNetCIM();
  		$response = $request->deleteCustomerShippingAddress($cim_customerID, $cim_shipping_adress_id);
  		if (trim($response->xml->messages->message->code) == "I00001") {
  			$this->model_authorizenet_cim_customer->addCustomerCimShippingAddressId($this->customer->getId(),$shipping_adress_id,'');
  			return true;
  		}else{
  			$this->authorizenet_cim_log->write('CIM deleteCimAddress('.$cim_customerID.') Failed. Error Code:'.$response->xml->messages->message->code.' Error Message'.$response->xml->messages->message->text.'');
  			return false;
  		}
  	}
  	
  	
}
?>