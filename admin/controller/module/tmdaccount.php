<?php
class ControllerModuleTmdAccount extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/tmdaccount');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('tmdaccount', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			/* staysave */
			if(isset($this->request->get['status'])) {
			$this->response->redirect($this->url->link('module/tmdaccount', 'token=' . $this->session->data['token'], 'SSL'));
			} else {
			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
			}
			/* staysave */

			
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_bgimage'] = $this->language->get('entry_bgimage');
		$data['entry_bgimage_size'] = $this->language->get('entry_bgimage_size');
		$data['entry_login'] = $this->language->get('entry_login');
		$data['entry_register'] = $this->language->get('entry_register');
		$data['entry_forgot'] = $this->language->get('entry_forgot');
		$data['entry_myaccount'] = $this->language->get('entry_myaccount');
		$data['entry_editaccount'] = $this->language->get('entry_editaccount');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_address_book'] = $this->language->get('entry_address_book');
		$data['entry_wishlist'] = $this->language->get('entry_wishlist');
		$data['entry_newsletter'] = $this->language->get('entry_newsletter');
		$data['entry_logout'] = $this->language->get('entry_logout');
		$data['entry_order'] = $this->language->get('entry_order');
		$data['entry_downloads'] = $this->language->get('entry_downloads');
		$data['entry_payments'] = $this->language->get('entry_payments');
		$data['entry_reward'] = $this->language->get('entry_reward');
		$data['entry_returns'] = $this->language->get('entry_returns');
		$data['entry_transaction'] = $this->language->get('entry_transaction');
		$data['entry_totalorders'] = $this->language->get('entry_totalorders');
		$data['entry_totalwishlist'] = $this->language->get('entry_totalwishlist');
		$data['entry_totalreward'] = $this->language->get('entry_totalreward');
		$data['entry_totaldownload'] = $this->language->get('entry_totaldownload');
		$data['entry_totaltransaction'] = $this->language->get('entry_totaltransaction');
		$data['entry_latestorder'] = $this->language->get('entry_latestorder');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_defaultimage'] = $this->language->get('entry_defaultimage');
		$data['entry_totalorders_bg'] = $this->language->get('entry_totalorders_bg');
		$data['entry_totalwishlist_bg'] = $this->language->get('entry_totalwishlist_bg');
		$data['entry_totalreward_bg'] = $this->language->get('entry_totalreward_bg');
		$data['entry_totaldownload_bg'] = $this->language->get('entry_totaldownload_bg');
		$data['entry_totaltransaction_bg'] = $this->language->get('entry_totaltransaction_bg');
		$data['entry_latestorder_bg'] = $this->language->get('entry_latestorder_bg');
		$data['entry_midbgcolor'] = $this->language->get('entry_midbgcolor');
		$data['entry_primrybutoncolor'] = $this->language->get('entry_primrybutoncolor');
		$data['entry_primrybutontextcolor'] = $this->language->get('entry_primrybutontextcolor');
		$data['entry_sidebarbg'] = $this->language->get('entry_sidebarbg');
		$data['entry_sidebarcolor'] = $this->language->get('entry_sidebarcolor');
		$data['entry_sidebarhover'] = $this->language->get('entry_sidebarhover');
		$data['entry_accountlink'] = $this->language->get('entry_accountlink');
		$data['entry_custom_css'] = $this->language->get('entry_custom_css');
		$data['entry_sidebarbotomborder'] = $this->language->get('entry_sidebarbotomborder');
		$data['entry_sidebarleftborder'] = $this->language->get('entry_sidebarleftborder');
		$data['tab_Custom'] = $this->language->get('tab_Custom');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_sidebarmodule'] = $this->language->get('tab_sidebarmodule');
		$data['tab_modulesetting'] = $this->language->get('tab_modulesetting');
		//new code
		$data['tab_language'] = $this->language->get('tab_language');
		$data['text_changelabel'] = $this->language->get('text_changelabel');
		$data['entry_profilelabel'] = $this->language->get('entry_profilelabel');
		$data['entry_checklabel'] = $this->language->get('entry_checklabel');
		$data['entry_accountlabel'] = $this->language->get('entry_accountlabel');
		$data['entry_editaclabel'] = $this->language->get('entry_editaclabel');
		$data['entry_passlabel'] = $this->language->get('entry_passlabel');
		$data['entry_booklabel'] = $this->language->get('entry_booklabel');
		$data['entry_wishlabel'] = $this->language->get('entry_wishlabel');
		$data['entry_orderlabel'] = $this->language->get('entry_orderlabel');
		$data['entry_downlodlabel'] = $this->language->get('entry_downlodlabel');
		$data['entry_pointslabel'] = $this->language->get('entry_pointslabel');
		$data['entry_returnlabel'] = $this->language->get('entry_returnlabel');
		$data['entry_transctionlabel'] = $this->language->get('entry_transctionlabel');
		$data['entry_newslabel'] = $this->language->get('entry_newslabel');
		$data['entry_paylabel'] = $this->language->get('entry_paylabel');
		$data['entry_totalodrlabel'] = $this->language->get('entry_totalodrlabel');
		$data['entry_totalwishlabel'] = $this->language->get('entry_totalwishlabel');
		$data['entry_totalpointlabel'] = $this->language->get('entry_totalpointlabel');
		$data['entry_totaldownlabel'] = $this->language->get('entry_totaldownlabel');
		$data['entry_totaltranslabel'] = $this->language->get('entry_totaltranslabel');
		$data['entry_latestlabel'] = $this->language->get('entry_latestlabel');
		$data['entry_orderidlabel'] = $this->language->get('entry_orderidlabel');
		$data['entry_noprolabel'] = $this->language->get('entry_noprolabel');
		$data['entry_statuslabel'] = $this->language->get('entry_statuslabel');
		$data['entry_totalprolabel'] = $this->language->get('entry_totalprolabel');
		$data['entry_datelabel'] = $this->language->get('entry_datelabel');
		$data['entry_actionlabel'] = $this->language->get('entry_actionlabel');
		$data['entry_loginlabel'] = $this->language->get('entry_loginlabel');
		$data['entry_registerlabel'] = $this->language->get('entry_registerlabel');
		$data['entry_forgotlabel'] = $this->language->get('entry_forgotlabel');
		$data['entry_logoutlabel'] = $this->language->get('entry_logoutlabel');
		$data['entry_viewalllabel'] = $this->language->get('entry_viewalllabel');
		$data['button_stay'] = $this->language->get('button_stay');
		$data['text_accountsetting'] = $this->language->get('text_accountsetting');
		$data['text_ordersetting'] = $this->language->get('text_ordersetting');
		$data['text_colorsetting'] = $this->language->get('text_colorsetting');
		//new code
		

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'] . '&type=module', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title1'),
			'href' => $this->url->link('module/tmdaccount', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/tmdaccount', 'token=' . $this->session->data['token'], 'SSL');
		
		/* staysave */
		$data['staysave'] = $this->url->link('module/tmdaccount', '&status=1&token=' . $this->session->data['token'], 'SSL');
		/* staysave */

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		//// Control Panel Tab Start ////
		$this->load->model('tool/image');
		
		if (isset($this->request->post['tmdaccount_bgimage'])) {
			$data['tmdaccount_bgimage'] = $this->request->post['tmdaccount_bgimage'];
		}  else {
			$data['tmdaccount_bgimage'] = $this->config->get('tmdaccount_bgimage');;
		}
		
		if (isset($this->request->post['tmdaccount_bgimage']) && is_file(DIR_IMAGE . $this->request->post['tmdaccount_bgimage'])) {
			$data['tmdaccount_thumb'] = $this->model_tool_image->resize($this->request->post['tmdaccount_bgimage'], 100, 100);
		} elseif ($this->config->get('tmdaccount_bgimage') && is_file(DIR_IMAGE . $this->config->get('tmdaccount_bgimage'))) {
			$data['tmdaccount_thumb'] = $this->model_tool_image->resize($this->config->get('tmdaccount_bgimage'), 100, 100);
		} else {
			$data['tmdaccount_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		if (isset($this->request->post['tmdaccount_defaultimage'])) {
			$data['tmdaccount_defaultimage'] = $this->request->post['tmdaccount_defaultimage'];
		}  else {
			$data['tmdaccount_defaultimage'] = $this->config->get('tmdaccount_defaultimage');;
		}
		
		if (isset($this->request->post['tmdaccount_defaultimage']) && is_file(DIR_IMAGE . $this->request->post['tmdaccount_defaultimage'])) {
			$data['tmdaccount_defaultpic'] = $this->model_tool_image->resize($this->request->post['tmdaccount_defaultimage'], 100, 100);
		} elseif ($this->config->get('tmdaccount_defaultimage') && is_file(DIR_IMAGE . $this->config->get('tmdaccount_defaultimage'))) {
			$data['tmdaccount_defaultpic'] = $this->model_tool_image->resize($this->config->get('tmdaccount_defaultimage'), 100, 100);
		} else {
			$data['tmdaccount_defaultpic'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		if (isset($this->request->post['tmdaccount_bgwidth'])) {
			$data['tmdaccount_bgwidth'] = $this->request->post['tmdaccount_bgwidth'];
		} else {
			$data['tmdaccount_bgwidth'] = $this->config->get('tmdaccount_bgwidth');
		}
		
		if (isset($this->request->post['tmdaccount_bgheight'])) {
			$data['tmdaccount_bgheight'] = $this->request->post['tmdaccount_bgheight'];
		} else {
			$data['tmdaccount_bgheight'] = $this->config->get('tmdaccount_bgheight');
		}
		
		if (isset($this->request->post['tmdaccount_defaultwidth'])) {
			$data['tmdaccount_defaultwidth'] = $this->request->post['tmdaccount_defaultwidth'];
		} else {
			$data['tmdaccount_defaultwidth'] = $this->config->get('tmdaccount_defaultwidth');
		}
		
		if (isset($this->request->post['tmdaccount_defaultheight'])) {
			$data['tmdaccount_defaultheight'] = $this->request->post['tmdaccount_defaultheight'];
		} else {
			$data['tmdaccount_defaultheight'] = $this->config->get('tmdaccount_defaultheight');
		}
		
		if (isset($this->request->post['tmdaccount_myaccount'])) {
			$data['tmdaccount_myaccount'] = $this->request->post['tmdaccount_myaccount'];
		} else {
			$data['tmdaccount_myaccount'] = $this->config->get('tmdaccount_myaccount');
		}
		
		if (isset($this->request->post['tmdaccount_editaccount'])) {
			$data['tmdaccount_editaccount'] = $this->request->post['tmdaccount_editaccount'];
		} else {
			$data['tmdaccount_editaccount'] = $this->config->get('tmdaccount_editaccount');
		}
		
		if (isset($this->request->post['tmdaccount_password'])) {
			$data['tmdaccount_password'] = $this->request->post['tmdaccount_password'];
		} else {
			$data['tmdaccount_password'] = $this->config->get('tmdaccount_password');
		}
		
		if (isset($this->request->post['tmdaccount_address_book'])) {
			$data['tmdaccount_address_book'] = $this->request->post['tmdaccount_address_book'];
		} else {
			$data['tmdaccount_address_book'] = $this->config->get('tmdaccount_address_book');
		}
		
		if (isset($this->request->post['tmdaccount_wishlist'])) {
			$data['tmdaccount_wishlist'] = $this->request->post['tmdaccount_wishlist'];
		} else {
			$data['tmdaccount_wishlist'] = $this->config->get('tmdaccount_wishlist');
		}
		
		if (isset($this->request->post['tmdaccount_newsletter'])) {
			$data['tmdaccount_newsletter'] = $this->request->post['tmdaccount_newsletter'];
		} else {
			$data['tmdaccount_newsletter'] = $this->config->get('tmdaccount_newsletter');
		}
		
		if (isset($this->request->post['tmdaccount_logout'])) {
			$data['tmdaccount_logout'] = $this->request->post['tmdaccount_logout'];
		} else {
			$data['tmdaccount_logout'] = $this->config->get('tmdaccount_logout');
		}
		
		if (isset($this->request->post['tmdaccount_order'])) {
			$data['tmdaccount_order'] = $this->request->post['tmdaccount_order'];
		} else {
			$data['tmdaccount_order'] = $this->config->get('tmdaccount_order');
		}
		
		if (isset($this->request->post['tmdaccount_downloads'])) {
			$data['tmdaccount_downloads'] = $this->request->post['tmdaccount_downloads'];
		} else {
			$data['tmdaccount_downloads'] = $this->config->get('tmdaccount_downloads');
		}
		
		if (isset($this->request->post['tmdaccount_payments'])) {
			$data['tmdaccount_payments'] = $this->request->post['tmdaccount_payments'];
		} else {
			$data['tmdaccount_payments'] = $this->config->get('tmdaccount_payments');
		}
		
		if (isset($this->request->post['tmdaccount_reward'])) {
			$data['tmdaccount_reward'] = $this->request->post['tmdaccount_reward'];
		} else {
			$data['tmdaccount_reward'] = $this->config->get('tmdaccount_reward');
		}
		
		if (isset($this->request->post['tmdaccount_returns'])) {
			$data['tmdaccount_returns'] = $this->request->post['tmdaccount_returns'];
		} else {
			$data['tmdaccount_returns'] = $this->config->get('tmdaccount_returns');
		}
		
		if (isset($this->request->post['tmdaccount_transaction'])) {
			$data['tmdaccount_transaction'] = $this->request->post['tmdaccount_transaction'];
		} else {
			$data['tmdaccount_transaction'] = $this->config->get('tmdaccount_transaction');
		}
		
		if (isset($this->request->post['tmdaccount_login'])) {
			$data['tmdaccount_login'] = $this->request->post['tmdaccount_login'];
		} else {
			$data['tmdaccount_login'] = $this->config->get('tmdaccount_login');
		}
		
		if (isset($this->request->post['tmdaccount_register'])) {
			$data['tmdaccount_register'] = $this->request->post['tmdaccount_register'];
		} else {
			$data['tmdaccount_register'] = $this->config->get('tmdaccount_register');
		}

		if (isset($this->request->post['tmdaccount_forgot'])) {
			$data['tmdaccount_forgot'] = $this->request->post['tmdaccount_forgot'];
		} else {
			$data['tmdaccount_forgot'] = $this->config->get('tmdaccount_forgot');
		}
		
		if (isset($this->request->post['tmdaccount_totalorders'])) {
			$data['tmdaccount_totalorders'] = $this->request->post['tmdaccount_totalorders'];
		} else {
			$data['tmdaccount_totalorders'] = $this->config->get('tmdaccount_totalorders');
		}
		
		if (isset($this->request->post['tmdaccount_totalwishlist'])) {
			$data['tmdaccount_totalwishlist'] = $this->request->post['tmdaccount_totalwishlist'];
		} else {
			$data['tmdaccount_totalwishlist'] = $this->config->get('tmdaccount_totalwishlist');
		}
		
		if (isset($this->request->post['tmdaccount_totalreward'])) {
			$data['tmdaccount_totalreward'] = $this->request->post['tmdaccount_totalreward'];
		} else {
			$data['tmdaccount_totalreward'] = $this->config->get('tmdaccount_totalreward');
		}
		
		if (isset($this->request->post['tmdaccount_totaldownload'])) {
			$data['tmdaccount_totaldownload'] = $this->request->post['tmdaccount_totaldownload'];
		} else {
			$data['tmdaccount_totaldownload'] = $this->config->get('tmdaccount_totaldownload');
		}
		
		if (isset($this->request->post['tmdaccount_totaltransaction'])) {
			$data['tmdaccount_totaltransaction'] = $this->request->post['tmdaccount_totaltransaction'];
		} else {
			$data['tmdaccount_totaltransaction'] = $this->config->get('tmdaccount_totaltransaction');
		}
		
		if (isset($this->request->post['tmdaccount_latestorder'])) {
			$data['tmdaccount_latestorder'] = $this->request->post['tmdaccount_latestorder'];
		} else {
			$data['tmdaccount_latestorder'] = $this->config->get('tmdaccount_latestorder');
		}

		if (isset($this->request->post['tmdaccount_status'])) {
			$data['tmdaccount_status'] = $this->request->post['tmdaccount_status'];
		} else {
			$data['tmdaccount_status'] = $this->config->get('tmdaccount_status');
		}
		
		//color
		if (isset($this->request->post['tmdaccount_totalorders_bg'])) {
			$data['tmdaccount_totalorders_bg'] = $this->request->post['tmdaccount_totalorders_bg'];
		} else {
			$data['tmdaccount_totalorders_bg'] = $this->config->get('tmdaccount_totalorders_bg');
		}
		
		if (isset($this->request->post['tmdaccount_totalwishlist_bg'])) {
			$data['tmdaccount_totalwishlist_bg'] = $this->request->post['tmdaccount_totalwishlist_bg'];
		} else {
			$data['tmdaccount_totalwishlist_bg'] = $this->config->get('tmdaccount_totalwishlist_bg');
		}
		
		if (isset($this->request->post['tmdaccount_totalreward_bg'])) {
			$data['tmdaccount_totalreward_bg'] = $this->request->post['tmdaccount_totalreward_bg'];
		} else {
			$data['tmdaccount_totalreward_bg'] = $this->config->get('tmdaccount_totalreward_bg');
		}
		
		if (isset($this->request->post['tmdaccount_totaldownload_bg'])) {
			$data['tmdaccount_totaldownload_bg'] = $this->request->post['tmdaccount_totaldownload_bg'];
		} else {
			$data['tmdaccount_totaldownload_bg'] = $this->config->get('tmdaccount_totaldownload_bg');
		}
		
		if (isset($this->request->post['tmdaccount_totaltransaction_bg'])) {
			$data['tmdaccount_totaltransaction_bg'] = $this->request->post['tmdaccount_totaltransaction_bg'];
		} else {
			$data['tmdaccount_totaltransaction_bg'] = $this->config->get('tmdaccount_totaltransaction_bg');
		}
		
		if (isset($this->request->post['tmdaccount_latestorder_bg'])) {
			$data['tmdaccount_latestorder_bg'] = $this->request->post['tmdaccount_latestorder_bg'];
		} else {
			$data['tmdaccount_latestorder_bg'] = $this->config->get('tmdaccount_latestorder_bg');
		}
		
		if (isset($this->request->post['tmdaccount_pbtncolor'])) {
			$data['tmdaccount_pbtncolor'] = $this->request->post['tmdaccount_pbtncolor'];
		} else {
			$data['tmdaccount_pbtncolor'] = $this->config->get('tmdaccount_pbtncolor');
		}
		
		
		if (isset($this->request->post['tmdaccount_pbtntextcolor'])) {
			$data['tmdaccount_pbtntextcolor'] = $this->request->post['tmdaccount_pbtntextcolor'];
		} else {
			$data['tmdaccount_pbtntextcolor'] = $this->config->get('tmdaccount_pbtntextcolor');
		}
		
		if (isset($this->request->post['tmdaccount_midbgcolor'])) {
			$data['tmdaccount_midbgcolor'] = $this->request->post['tmdaccount_midbgcolor'];
		} else {
			$data['tmdaccount_midbgcolor'] = $this->config->get('tmdaccount_midbgcolor');
		}
		
		if (isset($this->request->post['tmdaccount_sidebarbg'])) {
			$data['tmdaccount_sidebarbg'] = $this->request->post['tmdaccount_sidebarbg'];
		} else {
			$data['tmdaccount_sidebarbg'] = $this->config->get('tmdaccount_sidebarbg');
		}
		
		if (isset($this->request->post['tmdaccount_sidebarcolor'])) {
			$data['tmdaccount_sidebarcolor'] = $this->request->post['tmdaccount_sidebarcolor'];
		} else {
			$data['tmdaccount_sidebarcolor'] = $this->config->get('tmdaccount_sidebarcolor');
		}
		
		if (isset($this->request->post['tmdaccount_sidebarhover'])) {
			$data['tmdaccount_sidebarhover'] = $this->request->post['tmdaccount_sidebarhover'];
		} else {
			$data['tmdaccount_sidebarhover'] = $this->config->get('tmdaccount_sidebarhover');
		}
		
		if (isset($this->request->post['tmdaccount_sidebarbotomborder'])) {
			$data['tmdaccount_sidebarbotomborder'] = $this->request->post['tmdaccount_sidebarbotomborder'];
		} else {
			$data['tmdaccount_sidebarbotomborder'] = $this->config->get('tmdaccount_sidebarbotomborder');
		}
		
		if (isset($this->request->post['tmdaccount_sidebarleftborder'])) {
			$data['tmdaccount_sidebarleftborder'] = $this->request->post['tmdaccount_sidebarleftborder'];
		} else {
			$data['tmdaccount_sidebarleftborder'] = $this->config->get('tmdaccount_sidebarleftborder');
		}
		
		if (isset($this->request->post['tmdaccount_lable1'])) {
			$data['tmdaccount_lable1'] = $this->request->post['tmdaccount_lable1'];
		} else {
			$data['tmdaccount_lable1'] = $this->config->get('tmdaccount_lable1');
		}
		
		if (isset($this->request->post['tmdaccount_lable2'])) {
			$data['tmdaccount_lable2'] = $this->request->post['tmdaccount_lable2'];
		} else {
			$data['tmdaccount_lable2'] = $this->config->get('tmdaccount_lable2');
		}
		
		//link account setting
		if (isset($this->request->post['tmdaccount_link_editaccount'])) {
			$data['tmdaccount_link_editaccount'] = $this->request->post['tmdaccount_link_editaccount'];
		} else {
			$data['tmdaccount_link_editaccount'] = $this->config->get('tmdaccount_link_editaccount');
		}
		
		if (isset($this->request->post['tmdaccount_link_password'])) {
			$data['tmdaccount_link_password'] = $this->request->post['tmdaccount_link_password'];
		} else {
			$data['tmdaccount_link_password'] = $this->config->get('tmdaccount_link_password');
		}
		
		if (isset($this->request->post['tmdaccount_link_address_book'])) {
			$data['tmdaccount_link_address_book'] = $this->request->post['tmdaccount_link_address_book'];
		} else {
			$data['tmdaccount_link_address_book'] = $this->config->get('tmdaccount_link_address_book');
		}
		
		if (isset($this->request->post['tmdaccount_link_wishlist'])) {
			$data['tmdaccount_link_wishlist'] = $this->request->post['tmdaccount_link_wishlist'];
		} else {
			$data['tmdaccount_link_wishlist'] = $this->config->get('tmdaccount_link_wishlist');
		}
		
		if (isset($this->request->post['tmdaccount_link_newsletter'])) {
			$data['tmdaccount_link_newsletter'] = $this->request->post['tmdaccount_link_newsletter'];
		} else {
			$data['tmdaccount_link_newsletter'] = $this->config->get('tmdaccount_link_newsletter');
		}
		
		if (isset($this->request->post['tmdaccount_link_order'])) {
			$data['tmdaccount_link_order'] = $this->request->post['tmdaccount_link_order'];
		} else {
			$data['tmdaccount_link_order'] = $this->config->get('tmdaccount_link_order');
		}
		
		if (isset($this->request->post['tmdaccount_link_downloads'])) {
			$data['tmdaccount_link_downloads'] = $this->request->post['tmdaccount_link_downloads'];
		} else {
			$data['tmdaccount_link_downloads'] = $this->config->get('tmdaccount_link_downloads');
		}
		
		if (isset($this->request->post['tmdaccount_link_reward'])) {
			$data['tmdaccount_link_reward'] = $this->request->post['tmdaccount_link_reward'];
		} else {
			$data['tmdaccount_link_reward'] = $this->config->get('tmdaccount_link_reward');
		}
		
		if (isset($this->request->post['tmdaccount_link_returns'])) {
			$data['tmdaccount_link_returns'] = $this->request->post['tmdaccount_link_returns'];
		} else {
			$data['tmdaccount_link_returns'] = $this->config->get('tmdaccount_link_returns');
		}
		
		if (isset($this->request->post['tmdaccount_link_transaction'])) {
			$data['tmdaccount_link_transaction'] = $this->request->post['tmdaccount_link_transaction'];
		} else {
			$data['tmdaccount_link_transaction'] = $this->config->get('tmdaccount_link_transaction');
		}
		
		if (isset($this->request->post['tmdaccount_link_payments'])) {
			$data['tmdaccount_link_payments'] = $this->request->post['tmdaccount_link_payments'];
		} else {
			$data['tmdaccount_link_payments'] = $this->config->get('tmdaccount_link_payments');
		}
		
		if (isset($this->request->post['tmdaccount_custom_css'])) {
			$data['tmdaccount_custom_css'] = $this->request->post['tmdaccount_custom_css'];
		} else {
			$data['tmdaccount_custom_css'] = $this->config->get('tmdaccount_custom_css');
		}
		
		//new code
		//label
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['tmdaccount_lable'])) {
			$data['tmdaccount_lable'] = $this->request->post['tmdaccount_lable'];
		} else {
			$data['tmdaccount_lable'] = $this->config->get('tmdaccount_lable');
		}
		//new code
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/tmdaccount.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/tmdaccount')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
