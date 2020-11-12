<?php 
//////////////////////////////////
// Author:  Joshua J. Gomes
// E-mail:  josh@techsleuth.com
// Web:  http://www.techsleuth.com
//////////////////////////////////
?>
<?php 
header('Content-Type:text/html; charset=UTF-8');
class ControllerFeedGoogleBaseTechSleuth extends Controller {
public function index() {
define('JG_9TVQEW', 'google_base_techsleuth');
if(!defined('LANGUAGE_CONFIG_FILE_PATH')){define('LANGUAGE_CONFIG_FILE_PATH',$this->jg_cse4yo());}
switch (VERSION)
{
case (VERSION=='1.4.7'||VERSION=='1.4.8'||VERSION=='1.4.9'||VERSION=='1.4.9.1'||VERSION=='1.4.9.2'||VERSION=='1.4.9.3'||VERSION=='1.4.9.4'||VERSION=='1.4.9.5'||VERSION=='1.4.9.6'):
break;
case (VERSION=='1.5.0'||VERSION=='1.5.0.1'||VERSION=='1.5.0.2'||VERSION=='1.5.0.3'||VERSION=='1.5.0.4'||VERSION=='1.5.0.5'||VERSION=='1.5.1'||VERSION=='1.5.1.1'||VERSION=='1.5.1.2'||VERSION=='1.5.1.3'||VERSION=='1.5.2'||VERSION=='1.5.2.1'||VERSION=='1.5.3'||VERSION=='1.5.3.1'||VERSION=='1.5.4'||VERSION=='1.5.4.1'||VERSION=='1.5.5'||VERSION=='1.5.5.1'||VERSION=='1.5.6'||VERSION=='1.5.6.1'):
break;
default:
require LANGUAGE_CONFIG_FILE_PATH;
echo "<h2>".$_['text_extension_title']." v".$_['text_extension_version']."</h2><b>".$_['text_opencart_version']." ".VERSION."</b><br />".$_['text_unsupported_version_of_opencart'].":  <a href=\"http://www.opencart.com/index.php?route=extension/extension/info&extension_id=3261\">http://www.opencart.com/index.php?route=extension/extension/info&extension_id=3261</a>";
exit;
break;
}
$this->load->language($this->jg_nbz6v());
switch (VERSION)
{
case (VERSION=='1.4.7'||VERSION=='1.4.8'||VERSION=='1.4.9'||VERSION=='1.4.9.1'||VERSION=='1.4.9.2'||VERSION=='1.4.9.3'||VERSION=='1.4.9.4'||VERSION=='1.4.9.5'||VERSION=='1.4.9.6'):
$this->document->title=$this->language->get('text_extension_title');
break;
case (VERSION=='1.5.0'||VERSION=='1.5.0.1'||VERSION=='1.5.0.2'||VERSION=='1.5.0.3'||VERSION=='1.5.0.4'||VERSION=='1.5.0.5'||VERSION=='1.5.1'||VERSION=='1.5.1.1'||VERSION=='1.5.1.2'||VERSION=='1.5.1.3'||VERSION=='1.5.2'||VERSION=='1.5.2.1'||VERSION=='1.5.3'||VERSION=='1.5.3.1'||VERSION=='1.5.4'||VERSION=='1.5.4.1'||VERSION=='1.5.5'||VERSION=='1.5.5.1'||VERSION=='1.5.6'||VERSION=='1.5.6.1'):
$this->document->setTitle($this->language->get('text_extension_title'));
break;
default:
break;
}
$this->load->model('setting/setting');
if (($this->request->server['REQUEST_METHOD']=='POST')&&$this->validate()) {
$this->model_setting_setting->editSetting(JG_9TVQEW, $this->request->post);
$this->session->data['success']=$this->language->get('text_success');
switch (VERSION)
{
case (VERSION=='1.4.7'):
$this->response->redirect(HTTPS_SERVER.'index.php?route=extension/feed');
break;
case (VERSION=='1.4.8'||VERSION=='1.4.9'||VERSION=='1.4.9.1'||VERSION=='1.4.9.2'||VERSION=='1.4.9.3'||VERSION=='1.4.9.4'||VERSION=='1.4.9.5'||VERSION=='1.4.9.6'):
$this->response->redirect(HTTPS_SERVER.'index.php?route=extension/feed&token='.$this->session->data['token']);
break;
case (VERSION=='1.5.0'||VERSION=='1.5.0.1'||VERSION=='1.5.0.2'||VERSION=='1.5.0.3'||VERSION=='1.5.0.4'||VERSION=='1.5.0.5'||VERSION=='1.5.1'||VERSION=='1.5.1.1'||VERSION=='1.5.1.2'||VERSION=='1.5.1.3'||VERSION=='1.5.2'||VERSION=='1.5.2.1'||VERSION=='1.5.3'||VERSION=='1.5.3.1'||VERSION=='1.5.4'||VERSION=='1.5.4.1'||VERSION=='1.5.5'||VERSION=='1.5.5.1'||VERSION=='1.5.6'||VERSION=='1.5.6.1'):
$this->response->redirect($this->url->link('extension/feed', 'token='.$this->session->data['token'], 'SSL'));
break;
default:
break;
}
}
$this->data['text_enabled']=$this->language->get('text_enabled');
$this->data['text_disabled']=$this->language->get('text_disabled');
$this->data['entry_status']=$this->language->get('entry_status');
$this->data['button_save']=$this->language->get('button_save');
$this->data['button_cancel']=$this->language->get('button_cancel');
$this->data['tab_general']=$this->language->get('tab_general');
$this->data['text_action']=$this->language->get('text_action');
$this->data['text_active']=$this->language->get('text_active');
$this->data['text_attribute_name']=$this->language->get('text_attribute_name');
$this->data['text_attribute_value']=$this->language->get('text_attribute_value');
$this->data['text_collapse_view']=$this->language->get('text_collapse_view');
$this->data['text_currency']=$this->language->get('text_currency');
$this->data['text_custom']=$this->language->get('text_custom');
$this->data['text_data_feeds']=$this->language->get('text_data_feeds');
$this->data['text_default']=$this->language->get('text_default');
$this->data['text_disable']=$this->language->get('text_disable');
$this->data['text_edit_currencies']=$this->language->get('text_edit_currencies');
$this->data['text_enable']=$this->language->get('text_enable');
$this->data['text_expand_view']=$this->language->get('text_expand_view');
$this->data['text_automatic_data_feed_urls']=$this->language->get('text_automatic_data_feed_urls');
$this->data['text_convert_non_compliant_character_entities']=$this->language->get('text_convert_non_compliant_character_entities');
$this->data['text_default_data_feed_format']=$this->language->get('text_default_data_feed_format');
$this->data['text_data_feed_urls']=$this->language->get('text_data_feed_urls');
$this->data['text_google_attribute']=$this->language->get('text_google_attribute');
$this->data['text_google_attribute_value']=$this->language->get('text_google_attribute_value');
$this->data['text_google_product_category']=$this->language->get('text_google_product_category');
$this->data['text_help_and_support']=$this->language->get('text_help_and_support');
$this->data['text_language']=$this->language->get('text_language');
$this->data['text_limit']=$this->language->get('text_limit');
$this->data['text_opencart_field']=$this->language->get('text_opencart_field');
$this->data['text_opencart_field_value']=$this->language->get('text_opencart_field_value');
$this->data['text_opencart_product_attribute']=$this->language->get('text_opencart_product_attribute');
$this->data['text_opencart_product_category']=$this->language->get('text_opencart_product_category');
$this->data['text_opencart_product_name']=$this->language->get('text_opencart_product_name');
$this->data['text_opencart_product_option']=$this->language->get('text_opencart_product_option');
$this->data['text_product_attribute']=$this->language->get('text_product_attribute');
$this->data['text_product_category']=$this->language->get('text_product_category');
$this->data['text_product_name']=$this->language->get('text_product_name');
$this->data['text_product_option']=$this->language->get('text_product_option');
$this->data['text_products']=$this->language->get('text_products');
$this->data['text_products_feed_specification']=$this->language->get('text_products_feed_specification');
$this->data['text_products_per_page']=$this->language->get('text_products_per_page');
$this->data['text_page']=$this->language->get('text_page');
$this->data['text_refresh']=$this->language->get('text_refresh');
$this->data['text_reset']=$this->language->get('text_reset');
$this->data['text_start']=$this->language->get('text_start');
$this->data['text_target_country']=$this->language->get('text_target_country');
$this->data['text_taxonomy_language']=$this->language->get('text_taxonomy_language');
$this->data['text_update_currency_cache']=$this->language->get('text_update_currency_cache');
$this->data['text_use_opencart_field_value']=$this->language->get('text_use_opencart_field_value');
if (isset($this->error['warning'])) {
$this->data['error_warning']=$this->error['warning'];
}else{
$this->data['error_warning']='';
}
switch (VERSION)
{
case ('1.4.7'):
$this->document->breadcrumbs=array();
$this->document->breadcrumbs[]=array(
'href'      => HTTPS_SERVER.'index.php?route=common/home',
'text'      => $this->language->get('text_home'),
'separator' => FALSE
);
$this->document->breadcrumbs[]=array(
'href'      => HTTPS_SERVER.'index.php?route=extension/feed',
'text'      => $this->language->get('text_feed'),
'separator' => ' :: '
);
$this->document->breadcrumbs[]=array(
'href'      => HTTPS_SERVER.'index.php?route=feed/'.JG_9TVQEW,
'text'      => $this->language->get('text_extension_title').' v'.$this->language->get('text_extension_version'),
'separator' => ' :: '
);
$this->data['action']=HTTPS_SERVER.'index.php?route=feed/'.JG_9TVQEW;
$this->data['cancel']=HTTPS_SERVER.'index.php?route=extension/feed';
break;
case (VERSION=='1.4.8'||VERSION=='1.4.9'||VERSION=='1.4.9.1'||VERSION=='1.4.9.2'||VERSION=='1.4.9.3'||VERSION=='1.4.9.4'||VERSION=='1.4.9.5'||VERSION=='1.4.9.6'):
$this->document->breadcrumbs=array();
$this->document->breadcrumbs[]=array(
'href'      => HTTPS_SERVER.'index.php?route=common/home&token='.$this->session->data['token'],
'text'      => $this->language->get('text_home'),
'separator' => FALSE
);
$this->document->breadcrumbs[]=array(
'href'      => HTTPS_SERVER.'index.php?route=extension/feed&token='.$this->session->data['token'],
'text'      => $this->language->get('text_feed'),
'separator' => ' :: '
);
$this->document->breadcrumbs[]=array(
'href'      => HTTPS_SERVER.'index.php?route=feed/'.JG_9TVQEW.'&token='.$this->session->data['token'],
'text'      => $this->language->get('text_extension_title').' v'.$this->language->get('text_extension_version'),
'separator' => ' :: '
);
$this->data['action']=HTTPS_SERVER.'index.php?route=feed/'.JG_9TVQEW.'&token='.$this->session->data['token'];
$this->data['cancel']=HTTPS_SERVER.'index.php?route=extension/feed&token='.$this->session->data['token'];
break;
case (VERSION=='1.5.0'||VERSION=='1.5.0.1'||VERSION=='1.5.0.2'||VERSION=='1.5.0.3'||VERSION=='1.5.0.4'||VERSION=='1.5.0.5'||VERSION=='1.5.1'||VERSION=='1.5.1.1'||VERSION=='1.5.1.2'||VERSION=='1.5.1.3'||VERSION=='1.5.2'||VERSION=='1.5.2.1'||VERSION=='1.5.3'||VERSION=='1.5.3.1'||VERSION=='1.5.4'||VERSION=='1.5.4.1'||VERSION=='1.5.5'||VERSION=='1.5.5.1'||VERSION=='1.5.6'||VERSION=='1.5.6.1'):
$this->data['breadcrumbs']=array();
$this->data['breadcrumbs'][]=array(
'text'      => $this->language->get('text_home'),
'href'      => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
'separator' => false
);
$this->data['breadcrumbs'][]=array(
'text'      => $this->language->get('text_feed'),
'href'      => $this->url->link('extension/feed', 'token='.$this->session->data['token'], 'SSL'),
'separator' => ' :: '
);
$this->data['breadcrumbs'][]=array(
'text'      => $this->language->get('text_extension_title').' v'.$this->language->get('text_extension_version'),
'href'      => $this->url->link('feed/'.JG_9TVQEW, 'token='.$this->session->data['token'], 'SSL'),
'separator' => ' :: '
);
$this->data['action']=$this->url->link('feed/'.JG_9TVQEW, 'token='.$this->session->data['token'], 'SSL');
$this->data['cancel']=$this->url->link('extension/feed', 'token='.$this->session->data['token'], 'SSL');
break;
default:
break;
}
if (isset($this->request->post['google_base_techsleuth_status'])) {
$this->data['google_base_techsleuth_status']=$this->request->post['google_base_techsleuth_status'];
}else{
$this->data['google_base_techsleuth_status']=$this->config->get('google_base_techsleuth_status');
}
$this->data['data_feed']=HTTP_CATALOG.'index.php?route=feed/'.JG_9TVQEW;
switch (VERSION)
{
case (VERSION=='1.4.7'):
$this->data['currency']=HTTPS_SERVER.'index.php?route=localisation/currency';
break;
case (VERSION=='1.4.8'||VERSION=='1.4.9'||VERSION=='1.4.9.1'||VERSION=='1.4.9.2'||VERSION=='1.4.9.3'||VERSION=='1.4.9.4'||VERSION=='1.4.9.5'||VERSION=='1.4.9.6'):
$this->data['currency']=HTTPS_SERVER.'index.php?route=localisation/currency&token='.$this->session->data['token'];
break;
case (VERSION=='1.5.0'||VERSION=='1.5.0.1'||VERSION=='1.5.0.2'||VERSION=='1.5.0.3'||VERSION=='1.5.0.4'||VERSION=='1.5.0.5'||VERSION=='1.5.1'||VERSION=='1.5.1.1'||VERSION=='1.5.1.2'||VERSION=='1.5.1.3'||VERSION=='1.5.2'||VERSION=='1.5.2.1'||VERSION=='1.5.3'||VERSION=='1.5.3.1'||VERSION=='1.5.4'||VERSION=='1.5.4.1'||VERSION=='1.5.5'||VERSION=='1.5.5.1'||VERSION=='1.5.6'||VERSION=='1.5.6.1'):
$this->data['currency']=$this->url->link('localisation/currency', 'token='.$this->session->data['token'], 'SSL');
break;
default:
break;
}
$this->cache->delete('currency');
$this->cache->delete('product');
$this->template='feed/'.JG_9TVQEW.'.tpl';
$this->children=array(
'common/header',
'common/footer'
);
switch (VERSION)
{
case (VERSION=='1.4.7'||VERSION=='1.4.8'||VERSION=='1.4.9'||VERSION=='1.4.9.1'||VERSION=='1.4.9.2'||VERSION=='1.4.9.3'||VERSION=='1.4.9.4'||VERSION=='1.4.9.5'||VERSION=='1.4.9.6'):
$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
break;
case (VERSION=='1.5.0'||VERSION=='1.5.0.1'||VERSION=='1.5.0.2'||VERSION=='1.5.0.3'||VERSION=='1.5.0.4'||VERSION=='1.5.0.5'||VERSION=='1.5.1'||VERSION=='1.5.1.1'||VERSION=='1.5.1.2'||VERSION=='1.5.1.3'||VERSION=='1.5.2'||VERSION=='1.5.2.1'||VERSION=='1.5.3'||VERSION=='1.5.3.1'||VERSION=='1.5.4'||VERSION=='1.5.4.1'||VERSION=='1.5.5'||VERSION=='1.5.5.1'||VERSION=='1.5.6'||VERSION=='1.5.6.1'):
$this->response->setOutput($this->render());
break;
default:
break;
}
} 
private function validate() {
if (!$this->user->hasPermission('modify', 'feed/'.JG_9TVQEW)) {
$this->error['warning']=$this->language->get('error_permission');
}
if (!$this->error) {
return true;
}else{
return false;
}
}
function jg_cse4yo()
{
$jg_jazyn=DIR_LANGUAGE.$this->jg_n2ue5($this->jg_2dpqq()).'/feed/'.JG_9TVQEW.'.php';
$jg_esxlzp=$jg_jazyn;
if (!file_exists($jg_esxlzp))
{
$jg_esxlzp='language/'.$this->jg_n2ue5($this->jg_2dpqq()).'/feed/'.JG_9TVQEW.'.php';
}
if (!file_exists($jg_esxlzp))
{
$jg_esxlzp='language/english/feed/'.JG_9TVQEW.'.php';
}
if (!file_exists($jg_esxlzp))
{
$jg_esxlzp=$jg_jazyn;
echo 'Unable to locate the extension language config file:  "'.$jg_esxlzp.'".&nbsp;&nbsp;Please report the problem to:  <a href="mailto:%6a%6f%73%68%40%74%65%63%68%73%6c%65%75%74%68%2e%63%6f%6d&subject='.rawurlencode('OpenCart extension issue').'&body='.rawurlencode('Unable to locate the extension language config file:  "'.$jg_esxlzp.'"').'">josh@techsleuth.com</a>';
}
return $jg_esxlzp;
}
protected function jg_nbz6v()
{
$jg_szhi1='feed/'.JG_9TVQEW;
return $jg_szhi1;
}
function jg_n2ue5($jg_d410tc)
{
$jg_vxozb='';
$jg_n7w3s=DB_PREFIX."language";
$jg_spm9h=0;
$jg_vxozb='';
$database_connection=mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
mysql_set_charset('utf8');
if (function_exists('mb_language')) {
mb_language('uni');
mb_internal_encoding('UTF-8');
}
mysql_query("SET NAMES 'utf8'", $database_connection);
mysql_query("SET CHARACTER SET utf8", $database_connection);
mysql_query("SET CHARACTER_SET_CONNECTION=utf8", $database_connection);
mysql_query("SET CHARACTER_SET_RESULTS=utf8", $database_connection);
mysql_query("SET SQL_MODE=''", $database_connection);
mysql_select_db(DB_DATABASE, $database_connection) or die (mysql_error());
$jg_m9agq=mysql_query("SELECT DISTINCT * FROM ".$jg_n7w3s." WHERE ".$jg_n7w3s.".code='".$jg_d410tc."'", $database_connection) or die (mysql_error());
while($jg_fzknb=mysql_fetch_array($jg_m9agq))
{
$jg_vxozb=$jg_fzknb["directory"];
}
return $jg_vxozb;
}
function jg_2dpqq()
{
$jg_vxozb='';
$jg_n7w3s=DB_PREFIX."setting";
$jg_spm9h=0;
$jg_vxozb='';
$database_connection=mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
mysql_set_charset('utf8');
if (function_exists('mb_language')) {
mb_language('uni');
mb_internal_encoding('UTF-8');
}
mysql_query("SET NAMES 'utf8'", $database_connection);
mysql_query("SET CHARACTER SET utf8", $database_connection);
mysql_query("SET CHARACTER_SET_CONNECTION=utf8", $database_connection);
mysql_query("SET CHARACTER_SET_RESULTS=utf8", $database_connection);
mysql_query("SET SQL_MODE=''", $database_connection);
mysql_select_db(DB_DATABASE, $database_connection) or die (mysql_error());
$jg_m9agq=mysql_query("SELECT DISTINCT * FROM ".$jg_n7w3s." WHERE ".$jg_n7w3s.".group='config' AND ".$jg_n7w3s.".key='config_admin_language'", $database_connection) or die (mysql_error());
while($jg_fzknb=mysql_fetch_array($jg_m9agq))
{
$jg_vxozb=$jg_fzknb["value"];
}
return $jg_vxozb;
}
}
?>
