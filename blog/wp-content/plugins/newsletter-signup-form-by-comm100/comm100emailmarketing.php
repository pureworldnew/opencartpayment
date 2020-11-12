<?php
/*
Plugin Name: Email Newsletter Subscribe/Signup Form by Comm100 Email Marketing 
Plugin URI: http://emailmarketing.comm100.com/
Description: Have an email newsletter subscribe form set up on your site or blog in seconds. Create, send and track email newsletters without leaving WordPress!
Author: Comm100 Email Marketing
Version: 1.2
Author URI: http://emailmarketing.comm100.com/
*/

if (is_admin())
{
	require_once(dirname(__FILE__).'/plugin_files/Comm100EmailMarketingAdmin.class.php');
	Comm100EmailMarketingAdmin::get_instance();
}
else
{
	require_once(dirname(__FILE__).'/plugin_files/Comm100EmailMarketing.class.php');
	Comm100EmailMarketing::get_instance();
}
