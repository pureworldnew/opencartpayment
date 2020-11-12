<?php
class ControllerCommonThemecss extends Controller {

	public function index() {
	
		header("Content-type: text/css", true);
		
		$data['tmdaccount_midbgcolor'] = $this->config->get('tmdaccount_midbgcolor');
		$data['primerybtnbgcolor'] = $this->config->get('tmdaccount_pbtncolor');
		$data['primerybtncolor'] = $this->config->get('tmdaccount_pbtntextcolor');
		$data['totalorders'] = $this->config->get('tmdaccount_totalorders_bg');
		$data['totalwishlist'] = $this->config->get('tmdaccount_totalwishlist_bg');
		$data['totalreward'] = $this->config->get('tmdaccount_totalreward_bg');
		$data['totaldownload'] = $this->config->get('tmdaccount_totaldownload_bg');
		$data['totaltransaction'] = $this->config->get('tmdaccount_totaltransaction_bg');
		$data['latestorder'] = $this->config->get('tmdaccount_latestorder_bg');
		// Side Bar
		$data['sidebarbg'] = $this->config->get('tmdaccount_sidebarbg');
		$data['sidebarcolor'] = $this->config->get('tmdaccount_sidebarcolor');
		$data['sidebarhover'] = $this->config->get('tmdaccount_sidebarhover');
		$data['sidebarbotomborder'] = $this->config->get('tmdaccount_sidebarbotomborder');
		$data['sidebarleftborder'] = $this->config->get('tmdaccount_sidebarleftborder');
		
	
		//echo ".menu-box,.dashboard #content{background:".$data['tmdaccount_midbgcolor']."!important}";
		echo ".dashboard .btn-primary{color:".$data['primerybtncolor']."!important}";
		echo ".dashboard .btn-primary{background:".$data['primerybtnbgcolor']."!important;border-color:".$data['primerybtnbgcolor']."!important;text-shadow:none;}";
		echo ".menu-box .icon-box span{background:".$data['totalorders']."!important}";
		echo ".menu-box .col-sm-6:nth-child(2) span{background:".$data['totalwishlist']."!important}";
		echo ".menu-box .col-sm-6:nth-child(3) span{background:".$data['totalreward']."!important}";
		echo ".menu-box .col-sm-6:nth-child(4) span{background:".$data['totaldownload']."!important}";
		echo ".menu-box .col-sm-6:nth-child(5) span{background:".$data['totaltransaction']."!important}";
		echo ".menu-box .table1 h3{background:".$data['latestorder']."!important}";
	
		// Side Bar
		echo ".dashboard .list-group h2{background:".$data['sidebarbg']."!important;}";
		echo ".dashboard .list-group a{color:".$data['sidebarcolor']."!important}";
		echo ".dashboard .list-group a.active, .dashboard .list-group a:hover{background:".$data['sidebarhover']."!important}";
		echo ".dashboard .list-group a{border-color:".$data['sidebarbotomborder']."!important}";
		echo ".dashboard .list-group a:hover{color:".$data['sidebarleftborder']."!important;}";
		echo ".dashboard .profile .image img{border:3px solid ".$data['latestorder']."!important;}";
		echo ".dashboard .profile .detail ul{border-top:1px solid ".$data['latestorder']."!important;}";
		echo ".dashboard .profile .btnedit + .btnedit{border-left:1px solid ".$data['latestorder']."!important;}";
		
	}
}
