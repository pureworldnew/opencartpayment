<?php /* commentluv
    Plugin Name: CommentLuv
    Plugin URI: http://comluv.com/
    Description: Reward your readers by automatically placing a link to their last blog post at the end of their comment. Encourage a community and discover new posts.
    Version: 2.92.7
    Author: Andy Bailey
    Author URI: http://www.commentluv.com
    Copyright (C) <2011>  <Andy Bailey>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
    */
    if (! class_exists ( 'commentluv' )) {
        // let class begin
        class commentluv {
            //localization domain
            var $plugin_domain = 'commentluv';
            var $plugin_url;
            var $plugin_dir;
            var $db_option = 'commentluv_options';
            var $version = "2.92.7";
            var $slug = 'commentluv-options';
            var $localize;
            var $is_commentluv_request = false;

            /** commentluv
            * This is the constructor, it runs as soon as the class is created
            * Use this to set up hooks, filters, menus and language config
            */
            function __construct() {
                global $wp_version, $pagenow, $wp_actions;
                $options = $this->get_options();
                // try to add jetpack_module_loaded_comments action so it doesn't load
                if(!isset($options['allow_jpc'])){
                    $wp_actions['jetpack_module_loaded_comments'] = 1;   
                }                                                        
                // pages where this plugin needs translation
                $local_pages = array ('plugins.php', 'options-general.php' );
                // check if translation needed on current page
                if (in_array ( $pagenow, $local_pages ) || (isset($_GET['page']) && in_array ( $_GET ['page'], $local_pages ))) {
                    $this->handle_load_domain ();
                }
                $exit_msg = __( 'CommentLuv requires Wordpress 3.0 or newer.', $this->plugin_domain ) . '<a href="http://codex.wordpress.org/Upgrading_Wordpress">' . __ ( 'Please Update!', $this->plugin_domain ) . '</a>';
                // can you dig it?
                if (version_compare ( $wp_version, "3.0", "<" )) {
                    deactivate_plugins(basename(__FILE__)); //deactivate me
                    wp_die ( $exit_msg ); // no diggedy
                }
                // activation/deactivation
                register_activation_hook(__FILE__, array(&$this,'activation'));
                register_deactivation_hook(__FILE__, array(&$this,'deactivation'));
                // manual set install and activate, wordpress wont fire the activation hook on auto upgrade plugin
                $cl_version = get_option('cl_version');
                if($this->version != $cl_version){        
                    $this->install();
                    $this->activation();
                }                       
                // plugin dir and url
                $this->plugin_url = trailingslashit ( WP_PLUGIN_URL . '/' . dirname ( plugin_basename ( __FILE__ ) ) );
                $this->plugin_dir = dirname(__FILE__);
                if(defined('DOING_AJAX') && DOING_AJAX){
                    add_action ( 'wp_ajax_removeluv', array (&$this, 'ajax_remove_luv') ); // handle the call to the admin-ajax for removing luv
                    add_action ( 'wp_ajax_notify_signup', array(&$this,'notify_signup')); // ajax handler for settings page subscribe button
                    add_action ( 'wp_ajax_nopriv_cl_ajax',array(&$this,'do_ajax'));
                    add_action ( 'wp_ajax_cl_ajax',array(&$this,'do_ajax'));
                } else {
                    add_action ( 'clversion', array (&$this,'check_version') ); // check commentluv version
                    add_action ( 'init', array (&$this,'init') ); // to register styles and scripts
                    add_action ( 'admin_init', array (&$this, 'admin_init' ) ); // to register settings group
                    add_action ( 'admin_menu', array (&$this, 'admin_menu' ) ); // to setup menu link for settings page
                    add_action ( 'admin_print_scripts-settings_page_commentluv-options', array(&$this,'add_settings_page_script')); // script for settings page ajax function
                    add_action ( 'admin_print_styles-settings_page_commentluv-options', array(&$this,'add_settings_page_style')); // script for settings page ajax function
                    add_action ( 'init', array(&$this,'detect_useragent'));
                }
                // filters
                add_filter ( 'cron_schedules', array (&$this, 'cron_schedules') ); // for my own recurrence
                add_filter ( 'plugin_action_links', array (&$this, 'plugin_action_link' ), - 10, 2 ); // add a settings page link to the plugin description. use 2 for allowed vars
                // add_filter ( 'found_posts', array(&$this,'send_feed'),-1,2); // sends post titles and urls only - deprecated in 2.90.9.9
                add_filter ( 'kindergarten_html', array(&$this,'kindergarten_html')); // for cleaning html 

                //$this->check_version();
                if(!isset($options['enable']) || ( isset($options['enable']) && $options['enable'] != 'no')){
                    $this->setup_hooks();
                }


            }
            /**
            * PHP4 constructor
            */
            function commentluv() {
                $this->__construct();
            }
            /** runs when plugin is activated
            * called by register_activation_hook
            * 
            */
            function activation(){
                // only add if it doesn't exist yet
                $sched = wp_next_scheduled('clversion');
                if(false === $sched){
                    // set up cron for version check
                    $rnd = mt_rand(5,604800);
                    wp_schedule_event(time() - $rnd,'clfortnightly','clversion');
                }
                // removed w3 total cache stuff due to Freds updates causing fatal errors
            }  
            /**
            * Adds fields to comment area
            * called by add_action('comment_form
            */
            function add_fields(){ 
                global $clbadgeshown;
                $options = $this->get_options();
                if(!$this->is_enabled()){
                    return;
                }
                $author_name = $options['author_name'];
                $email_name = $options['email_name'];
                $url_name = $options['url_name'];    
                // handle logged on user
                if(is_user_logged_in()){
                    global $userdata;
                    get_currentuserinfo();
                    $author = $userdata->display_name;
                    $userid = $userdata->ID;
                    $url = $userdata->user_url;
                    if(!strstr($url,'http://') && $url != ''){
                        $url = 'http://'.$url;
                    }
                    // check for s2 member pluin, add url from it's custom registration fields
                    if(defined('WS_PLUGIN__S2MEMBER_VERSION') && isset($userdata->wp_s2member_custom_fields['website'])){
                        $url = $userdata->wp_s2member_custom_fields['website'];
                    }
                    // check for multisite
                    if(is_multisite()){
                        if(!$url || $url == 'http://'){
                            $userbloginfo = get_blogs_of_user($userid,1);
                            $url = $userbloginfo[1] -> siteurl;
                        }
                    }
                    // final check of url
                    if($url == 'http://'){
                        $url = '';
                    }
                    // spit out hidden fields
                    echo '<input type="hidden" id="'.$author_name.'" name="'.$author_name.'" value="'.$author.'"/>';
                    // if buddypress, don't hide field
                    if(function_exists('bp_core_setup_globals')){
                        $input_type = 'text';
                    } else {
                        $input_type = 'hidden';
                    }
                    echo '<input type="'.$input_type.'" id="'.$url_name.'" name="'.$url_name.'" value="'.$url.'"/>';    
                }
                // add hidden fields for holding information about type,choice,html and request for every user
                echo '<input type="hidden" name="cl_post_title" id="cl_post_title"/>';
                echo '<input type="hidden" name="cl_post_url" id="cl_post_url"/>';
                echo '<input type="hidden" name="cl_prem" id="cl_prem"/>';
                // show badge (unless user set to manual insert)
                if(($clbadgeshown == false && !isset($options['template_insert'])) || (isset($options['template_insert']) && $options['template_insert'] == '')  ){
                    $this->display_badge();
                }
            }
            function add_footer(){
                $minifying = 'off';
                extract($this->get_options());
                if($minifying != 'on' || !$this->is_enabled()){
                    return;
                }
                // from the excellent book wp-ajax (http://www.wpajax.com/)
                $data = "var cl_settings = {"; 
                $arr = array();
                $vars = $this->localize;
                if(is_array($vars)){
                    foreach ($vars as $key => $value) {
                        $arr[count($arr)] = $key . " : '" . esc_js($value) . "'"; 
                    } 
                    $data .= implode(",",$arr); $data .= "};"; 
                    echo "<script type='text/javascript'>\n"; 
                    echo "/* <![CDATA[ */\n"; 
                    echo $data; 
                    echo "\n/* ]]> */\n"; 
                    echo "</script>\n";
                }
            }
            /**
            * called by add_filter('comment_row_actions
            * adds another link to the comment row in admin for removing the luv link
            * @param array $actions - the existing actions
            */
            function add_removeluv_link($actions){
                global $post;
                $user_can = current_user_can('edit_posts', $post->ID);
                $cid = get_comment_ID();
                $data = get_comment_meta($cid,'cl_data');
                if($data && is_array($data)){
                    if($user_can){
                        $nonce= wp_create_nonce  ('removeluv'.get_comment_ID());
                        $actions['Remove-luv'] = '<a class="removeluv :'.$cid.':'.$nonce.'" href="javascript:">Remove Luv</a>';
                    }
                }
                return $actions;
            }
            /** 
            * called by add_action('admin_print_scripts-edit-comments.php'
            * load the script to handle the removluv link
            * 
            */
            function add_removeluv_script(){
                wp_enqueue_script ( 'commentluv', $this->plugin_url . 'js/adminremoveluv.js', array ('jquery' ),$this->version );
            }
            /**
            * called by add_action('template_redirect in setup_hooks()
            * used to add the commentluv script and localized settings (if not using minifying compatibility)
            */
            function add_script(){
                $minifying = 'off';
                $template_insert = false;
                $options = $this->get_options();
                extract($options);
                if(!$this->is_enabled()){
                    return;
                }
                wp_enqueue_script('commentluv_script');
                $this->localize = array ('name' => $author_name, 'url' => $url_name, 'comment' => $comment_name, 'email' => $email_name,
                    'infopanel' => $infopanel, 'default_on' => $default_on, 'default_on_admin' => $default_on_admin,
                    'cl_version' => $this->version, 'images' => $this->plugin_url . 'images/', 'api_url' => $api_url,
                    '_fetch' => wp_create_nonce('fetch'), '_info' => wp_create_nonce('info'), 
                    'infoback' => $infoback, 'infotext'=>$infotext,'template_insert'=>$template_insert, 'logged_in'=>is_user_logged_in(),
                    'refer' => get_permalink(),
                    'no_url_message'=>__('Please enter a URL and then click the CommentLuv checkbox if you want to add your last blog post',$this->plugin_domain),
                    'no_http_message'=>__('Please use http:// in front of your url',$this->plugin_domain),
                    'no_url_logged_in_message'=>__('You need to visit your profile in the dashboard and update your details with your site URL',$this->plugin_domain),
                    'no_info_message'=>__('No info was available or an error occured',$this->plugin_domain));
                if($minifying != 'on'){
                    wp_localize_script('commentluv_script','cl_settings',$this->localize);
                }


            }
            /**
            * called by add_action('wp_print_styles in setup_hooks()
            * Used to add the stylesheet for commentluv
            */
            function add_style(){
                if(!$this->is_enabled()){
                    return;
                }
                wp_enqueue_style('commentluv_style');
            }
            /**
            * Adds scripts to settings page. Only loads scripts if the settings page is being shown
            * Called by add_action('admin_print_scripts-settings_page_commentluv-options'
            * use localize so messages in javascript are internationalized
            */
            function add_settings_page_script (){
                wp_enqueue_script ('notify_signup', $this->plugin_url . 'js/notify_signup.js', array('jquery'),$this->version );
                wp_localize_script ( 'notify_signup', 'notify_signup_settings', array('wait_message'=>__('Please wait',$this->plugin_domain),'notify_success1' => __('Please check your inbox, an email will be sent to',$this->plugin_domain), 'notify_success2'=>__('in the next few minutes with a confirmation link',$this->plugin_domain), 'notify_fail'=>__('An error happened with the request. Try signing up at the site',$this->plugin_domain),'image_url'=>$this->plugin_url.'images/','default_image'=>'CL91_default.png', 'white'=>'CL91_White.gif','black'=>'CL91_Black.gif','none'=>'nothing.gif'));
                wp_enqueue_script('thickbox',null,array('jquery'));
                echo "<link rel='stylesheet' href='/".WPINC."/js/thickbox/thickbox.css?ver=20080613' type='text/css' media='all' />\n";
            }
            /** 
            * adds the thickbox style to header for commentluv settings page
            * called by add_action('admin_print_styles-settings_page_commentluv-options
            */
            function add_settings_page_style(){
                wp_enqueue_style('thickbox');
            }
            /** admin_init
            * This function registers the settings group
            * it is called by add_action admin_init
            * options in the options page will need to be named using $this->db_option[option]
            */
            function admin_init(){ 
                // whitelist options
                register_setting( 'commentluv_options_group', $this->db_option ,array(&$this,'options_sanitize' ) );
                $options = $this->get_options();
                //if(isset($options['upgrade'])){
                if(isset($options['upgrade']) && version_compare($options['upgrade'],$this->php_version($this->version),'>')){
                    add_action('admin_notices',array(&$this,'show_upgrade_notice'));
                }
            }

            /** admin_menu
            * This function adds a link to the settings page to the admin menu
            * see http://codex.wordpress.org/Adding_Administration_Menus
            * it is called by add_action admin_menu
            */
            function admin_menu(){
                if(is_multisite()){
                    $level = 'manage_options'; // for wpmu sub blog admins
                } else {
                    $level = 'administrator'; // for single blog intalls
                }
                $menutitle = '<img src="' . $this->plugin_url . 'images/littleheart.gif" alt=""/> CommentLuv';                
                add_options_page ( 'CommentLuv settings', $menutitle, $level, $this->slug, array (&$this, 'options_page' ) );
            }
            /**
            * ajax handler
            * setup by add_action ( 'wp_ajax_removeluv'
            * called when remove luv link is clicked in comments edit page
            * with POST['action'] of removeluv, receives cid and _wpnonce
            */
            function ajax_remove_luv(){
                // check user is allowed to do this
                $nonce=$_REQUEST['_wpnonce'];
                $cid = $_REQUEST['cid'];
                if (! wp_verify_nonce($nonce, 'removeluv'.$cid) ) die("Epic fail");
                // delete meta if comment id sent with request
                if($cid){
                    // get meta and set vars if exists
                    $cmeta =get_comment_meta($cid,'cl_data','true');
                    if($cmeta) extract($cmeta);
                    // delete it and call comluv to tell it what happened
                    if(delete_comment_meta($cid,'cl_data')){
                        // can call originator blog here maybe
                        // return the comment id and status code for js processing to hide luv
                        echo "$cid*200";
                    }
                } else {
                    echo '0';
                }
                exit;
            }
            /**
            * checks for a new version
            * called by a cron action
            */
            function check_version(){
                //debugbreak();
                $version = $this->php_version($this->version);
                $options = $this->get_options();
                $url = 'http://version.commentluv.com/';
                $name = strip_tags(get_bloginfo('name'));
                $description = strip_tags(get_bloginfo('description'));
                $ioncube = extension_loaded('ionCube Loader')? 'yes' : 'no';
                $numluv = $this->get_numluv();
                $dofollow = $options['dofollow'];
                $whogets = $options['whogets'];
                $body = array('version'=>$version,'enabled'=>$options['enable'],'name'=>$name,'description'=>$description,'avatarmd5'=>md5(strtolower(get_bloginfo('admin_email'))),'numluv'=>$numluv,'dofollow'=>$dofollow,'whogets'=>$whogets,'ioncube'=>$ioncube);
                $response = wp_remote_head($url,array('method'=>'POST','body'=>$body));
                $latest = $this->php_version(wp_remote_retrieve_header($response,'version'));
                $message = wp_remote_retrieve_header($response,'message');
                if(version_compare($version,$latest,'<')){
                    $options['upgrade'] = $latest;
                    if($message){
                        $options['upgrade_message'] = apply_filters('kindergarten_html',$message);
                    }
                    update_option($this->db_option,$options);
                }
            }
            /**
            * called by add_action('comment_post
            * runs just after comment has been saved to the database
            * will save the luv link to comment meta if it exists
            * 
            * @param int $id - id of the comment
            * @param string $commentdata - status of comment
            */
            function comment_posted($id,$commentdata){
                if(isset($_POST['cl_post_url']) && $_POST['cl_post_url'] != '' && isset($_POST['cl_post_title']) && $_POST['cl_post_title'] != ''){
                    $title = strip_tags($_POST['cl_post_title']);                                                                                  
                    $link = esc_url($_POST['cl_post_url']);
                    $options = $this->get_options();
                    //debugbreak();                                                                                        
                    // check for spam or delete comment if no author url
                    // spam or delete comment if no author url depending on user settings 
                    //(for logged out users only because logged in users have no commentdata->comment_author_url)
                    if(!is_user_logged_in()){
                        if($options['hide_link_no_url'] == 'spam' && $commentdata->comment_author_url ==''){
                            $commentdata->comment_approved = 'spam';
                            $update = wp_update_comment((array)$commentdata);
                        }
                        if($options['hide_link_no_url'] == 'delete' && $commentdata->comment_author_url == ''){
                            wp_delete_comment($id);
                            return;
                        }
                        // check for matching comment
                        if(!isset($options['hide_link_no_url_match'])){
                            $options['hide_link_no_url_match'] = 'nothing';
                        }
                        $authorurlarr = parse_url($commentdata->comment_author_url);
                        $linkurlarr = parse_url($link);
                        if($options['hide_link_no_url_match'] != 'nothing'){
                            if($authorurlarr['host'] != $linkurlarr['host']){
                                // link has different domain
                                if($options['hide_link_no_url_match'] == 'spam'){
                                    $commentdata->comment_approved = 'spam';
                                    $update = wp_update_comment((array)$commentdata);
                                }
                                if($options['hide_link_no_url_match'] == 'delete'){
                                    wp_delete_comment($id);
                                    return;
                                }
                            }
                        }
                    }
                    $prem = 'p' == $_POST['cl_prem'] ? 'p' : 'u';
                    $data = array('cl_post_title'=>$title,'cl_post_url'=>$link,'cl_prem'=>$prem);
                    add_comment_meta($id,'cl_data',$data,'true');
                }   
            }
            /**
            * add my own recurrence schedule
            * called by add_filter('cron_schedules
            * 
            * @param mixed $schedules  - the current schedules
            */
            function cron_schedules($schedules){
                $schedules['clfortnightly'] = array(
                    'interval' => 1209600,
                    'display' => __('Twice Monthly')
                );
                return $schedules;
            }
            /** runs when plugin is deactivated
            * called by register_deactivation_hook
            * 
            */
            function deactivation(){
                wp_clear_scheduled_hook('clversion');
            }
            /**
            * detect if request is from a commentluv useragent
            * called by add_action('init
            * 
            * ignore if user has set disable_detect in settings
            * 
            * since 2.90.9.9 - add action for template redirect, we do the sending of the special feed there now
            */
            function detect_useragent(){
                $options = $this->get_options();
                // dont do anything if detect is disabled
                if(isset($_POST['version_check'])){
                    $this->check_version();
                }
                if(isset($options['disable_detect']) && $options['disable_detect'] == 'on'){
                    return;
                }
                // is this commentluv calling?
                if (preg_match("/Commentluv/i", $_SERVER['HTTP_USER_AGENT'])){
                    $this->is_commentluv_request = true;
                    ob_start();
                    if(!isset($options['disable_detect'])){
                        remove_all_actions('wp_head');
                        remove_all_actions('wp_footer');
                        // prevent wordpress.com stats from adding stats script
                        global $wp_query;
                        $wp_query->is_feed = true;
                        // use own function to output feed
                        add_action('template_redirect',array(&$this,'send_feed_file'),1);
                    }           
                }
            }
            /**
            * Called by add_fields or by manual insert
            * used to show the badge and extra bits for holding the ajax drop down box
            * 
            */
            function display_badge(){
                //DebugBreak();
                global $clbadgeshown;
                $badges = array('default'=>'CL91_default.png','default_image'=>'CL91_default.png','white'=>'CL91_White.gif','black'=>'CL91_Black.gif');
                $options = $this->get_options();
                if($clbadgeshown == true){
                    return;
                }
                // link to commentluv?
                $before = '';
                $after = '';
                // link
                if(isset($options['link'])){
                    $before = '<a href="http://www.commentluv.com" target="_blank" title="'.__('CommentLuv is enabled',$this->plugin_domain).'">';
                    $after = '</a>';
                }       
                // dropdown choice
                if($options['badge_choice'] == 'drop_down'){
                    if($options['badge_type'] != 'none'){
                        $imgurl = $this->plugin_url.'images/'.$badges[$options['badge_type']];
                    }
                }
                // custom image
                if($options['badge_choice'] == 'custom'){
                    if(isset($options['custom_image_url']) && $options['custom_image_url'] != ''){
                        if(!strstr($options['custom_image_url'],'http://')){
                            $imgurl = 'http://'.$options['custom_image_url'];
                        } else {
                            $imgurl = $options['custom_image_url'];
                        }
                    }
                }
                // create badge code (if not chosen 'none')
                if($options['badge_choice'] == 'drop_down' && $options['badge_type'] == 'none'){
                    $badgecode = '';
                } else {
                    if(!$imgurl){
                        $imgurl = $this->plugin_url.'images/'.$badges['default_image'];
                    }
                    $badgecode = $before.'<img alt="CommentLuv badge" src="'.$imgurl.'"/>'.$after;
                }
                // or using text
                if($options['badge_choice'] == 'text'){
                    $badgecode = $before.$options['badge_text'].$after;
                }
                // default on 
                $default_on = '';
                if($options['default_on'] == 'on'){
                    $default_on = ' checked="checked"';
                    if(is_user_logged_in() && current_user_can('manage_options')){
                        if($options['default_on_admin'] != 'on'){
                            $default_on = '';
                        }
                    }
                }
                // spit out code
                echo '<div id="commentluv"><div id="cl_messages"></div><input type="checkbox" id="doluv" name="doluv"'.$default_on.' /><span id="mylastpost">'.$badgecode.'</span><span id="showmorespan"><img class="clarrow" id="showmore" src="'.$this->plugin_url.'images/down-arrow.gif" alt="'.__('Show more posts',$this->plugin_domain).'" title="'.__('Show more posts',$this->plugin_domain).'" style="display:none;"/></span></div><div id="lastposts" style="display:none;"></div>';
                $clbadgeshown = true;
            }
            /**
            * ajax handler.
            * called by add_action('wp_ajax_(nopriv_)ajax 
            * handles all ajax requests, receives 'do' as POST var and calls relevant function
            * 
            */
            function do_ajax(){
                $oldchecknonce = $_POST['_ajax_nonce'];
                $newchecknonce = preg_replace("/[^A-Za-z0-9 ]/", '', $oldchecknonce);
                if($oldchecknonce != $newchecknonce){
                    die('error! nonce malformed');
                }                            
                switch($_POST['do']){
                    case 'fetch' :
                        $this->fetch_feed();
                        break;
                    case 'info' :
                        $this->do_info();
                        break;
                    case 'click' :
                        $this->do_click();
                        break;

                }
            }
            /**
            * called by do_ajax
            * receives cid and nonce and cl_prem as POST vars
            * stores the click in the comment meta
            */
            function do_click(){
                $cid = intval($_POST['cid']);
                $nonce = $_POST['_ajax_nonce'];
                $url = $_POST['url'];
                if(!wp_verify_nonce($nonce,$cid)){
                    exit;
                }
                $data = get_comment_meta($cid,'cl_data',true);
                if(is_array($data)){
                    $data['clicks'] = $data['clicks'] + 1;
                    update_comment_meta($cid,'cl_data',$data);
                }
                if($_POST['cl_prem'] == 'true'){
                    $comment = get_commentdata($cid);
                    $refer = get_permalink($comment['comment_post_ID']);
                    // set blocking to false because no response required
                    $response = wp_remote_post($url,array('blocking'=>false,'body'=>array('cl_request'=>'click','refer'=>$refer,'version'=>$this->version)));
                }
                exit;
            }
            /** 
            * called by do_ajax
            * receives cl_prem, url and cid as POST vars
            * sends back json encoded string for the content of the panel 
            */
            function do_info(){

                check_ajax_referer('info');
                global $wpdb;
                $options = $this->get_options();
                $isreg = false;
                $cid = intval($_POST['cid']);
                $cl_prem = $_POST['cl_prem'];
                $link = $_POST['link'];
                // is registered user?
                $email = get_comment_author_email($cid);
                $user = get_user_by_email($email);
                if($user){
                    $isreg = true;
                }
                // get comments and stats
                $query = $wpdb->prepare('SELECT m.meta_value, c.comment_post_ID FROM '.$wpdb->comments.' c JOIN '.$wpdb->commentmeta.' m ON c.comment_ID = m.comment_ID WHERE c.comment_approved = 1 AND c.comment_author_email = %s AND m.meta_key = %s ORDER BY c.comment_ID DESC',$email,'cl_data');
                $rows = $wpdb->get_results($query);
                $num_comments = $wpdb->num_rows;
                // get other comments and links left
                $appeared_on = array();
                $appeared_on_list = array();
                $my_other_posts = array();
                $my_other_posts_list = array();

                if($rows){
                    foreach($rows as $row){
                        $data = unserialize($row->meta_value);
                        if(!in_array($data['cl_post_url'],$my_other_posts_list) && sizeof($my_other_posts) < 5){
                            $my_other_posts[] = '<a target="_blank" href="'.$data['cl_post_url'].'">'.esc_js(substr($data['cl_post_title'],0,60)).'</a>';
                            $my_other_posts_list[] = $data['cl_post_url'];
                        }
                        if(!in_array($row->comment_post_ID,$appeared_on_list) && sizeof($appeared_on) < 5){
                            $appeared_on[] = '<a href="'.get_permalink($row->comment_post_ID).'">'.substr(get_the_title($row->comment_post_ID),0,60).'</a>';
                            $appeared_on_list[] = $row->comment_post_ID;
                        }
                        // stop if both lists at 5
                        if(count($appeared_on) >= 5 && count($my_other_posts) >= 5){
                            break;
                        }
                    }
                }
                if(empty($appeared_on)){
                    $appeared_on[] = __('I have only commented on this post',$this->plugin_domain);
                }
                if(empty($my_other_posts)){
                    $my_other_posts[] = '<a>'.__('If I had made more comments on this site, you would see more of my other posts here',$this->plugin_domain).'</a>';
                }
                // get click count on local site
                $data = get_comment_meta($cid,'cl_data',true);
                $clickcount = $data['clicks'] ? $data['clicks'] : 0;
                //DebugBreak();
                // prem member, try remote fetch of info if not registered on this blog
                if($cl_prem == 'p' && $isreg == false){ 
                    $response = wp_remote_post($link,array('body'=>array('cl_request'=>'info','version'=>$this->version,'clickcount'=>$clickcount,'num_comments'=>$num_comments,'appeared_on'=>$appeared_on)));
                    $enabled = wp_remote_retrieve_header($response,'cl_info');
                    if($enabled == 'enabled'){
                        $panel = apply_filters('kindergarten_html',wp_remote_retrieve_body($response));
                        $json = json_encode(array('panel'=>$panel));
                        header ( "Content-Type: application/x-javascript; " );
                        echo $json;
                        exit;
                    } else {
                        $cl_prem = 'u';
                    }
                }
                // show registered members panel
                if($isreg){
                    // get users info
                    $bio = $user->description;
                    if($bio == ''){
                        $bio = __('User has not saved a description in their profile page',$this->plugin_domain);
                    }
                    $username = $user->display_name;
                    if(is_multisite()){
                        $can = 'manage_options';
                    } else {
                        $can = 'administrator';
                    }
                    // find if user has cap, need to create new user object and use ->has_cap 
                    // from wp 3.1, you can use if(user_can($user,$cap))
                    $user = new WP_User($user->ID);
                    if($user->has_cap($can)){
                        $reg_member = __('is the administrator of this site',$this->plugin_domain);
                    } else {
                        $reg_member = __('is a registered member of my site',$this->plugin_domain);
                    }                                                                                                                                                            
                    $gravatar = '<img src="http://www.gravatar.com/avatar/' . md5 ( strtolower($email) ) . '.jpg" alt="' . $username . '" align="left" />';
                    $panel = $gravatar . "<p class=\"cl_title\"><span class=\"cl_username\">$username</span> ".$reg_member."</p><p class=\"cl_bio\">$bio</p><p class=\"cl_clicks\"> <span class=\"cl_clicks_count\">$clickcount</span> ".__('Clicks on this link on this comment',$this->plugin_domain)."</p><p class=\"cl_links\">".$num_comments.' '.__('approved comments on this site',$this->plugin_domain).'<br>'.__('Some other posts I have commented on',$this->plugin_domain)."</p><p class=\"cl_links_list\">".implode('<br>',$appeared_on)."</p><p class=\"cl_posts\">".__('Some of my other posts',$this->plugin_domain)."</p><p class=\"cl_posts_list\">".implode('<br>',$my_other_posts)."</p>";
                    $json = json_encode(array('panel'=>$panel));
                    header ( "Content-Type: application/x-javascript; " );
                    echo $json;
                    exit;
                }
                // show panel for everyone else
                $comment = get_comment($cid);
                $msg = '';
                $bio= get_comment_author_url($cid);
                $name = get_comment_author($cid);
                $gravatar = '<img src="http://www.gravatar.com/avatar/' . md5 ( strtolower($email) ) . '.jpg" alt="' . $name . '" align="left" />';
                if(get_option('users_can_register') && $options['whogets'] == 'registered'){
                    $msg = __('has not registered on this site',$this->plugin_domain);
                    $bio = $options['unreg_user_text_panel'];
                } 
                $panel = $gravatar . "<p class=\"cl_title\">
                <span class=\"cl_username\">".$comment->comment_author."</span> ".$msg."</p>
                <p class=\"cl_bio\">".$bio."</p>
                <p class=\"cl_clicks\"> <span class=\"cl_clicks_count\">$clickcount</span> ".__('Clicks on this link on this comment',$this->plugin_domain)."</p>
                <p class=\"cl_links\">".$num_comments.' '.__('approved comments on this site',$this->plugin_domain).
                '<br>'.__('Some other posts I have commented on',$this->plugin_domain)."</p>
                <p class=\"cl_links_list\">".implode('<br>',$appeared_on)."</p>";
                // dont show other links for non registered user to entice them to register
                //<p class=\"cl_posts\">".__('Some of my other posts',$this->plugin_domain)."</p>
                //<p class=\"cl_posts_list\">".implode('<br>',$my_other_posts)."</p>";
                $json = json_encode(array('panel'=>$panel));
                header ( "Content-Type: application/x-javascript; " );
                echo $json;
                exit;
            }
            /**
            * called by add_filter('comments_array
            * adds the link to the comments that are to be displayed
            * @param mixed $commentarray
            */
            function do_shortcode($commentarray){
                $isadminpage = false;
                $options= $this->get_options();
                if(!is_array($commentarray)){
                    // if it's an array then it was called by comments_array filter,
                    // otherwise it was called by comment_content (admin screen)
                    // has it been done before?
                    if(strpos($commentarray,'class="cluv"')){
                        return $commentarray;
                    }
                    // make a fake array of 1 object so below treats the comment_content filter nicely for admin screen
                    $temparray = array('comment_ID'=>get_comment_ID(),'comment_content'=>$commentarray,'comment_author'=>get_comment_author(), 'comment_author_email'=>get_comment_author_email());
                    $tempobject = (object) $temparray;
                    $commentarray = array($tempobject);
                    $isadminpage = true;
                }
                // add link to comments (need to do it this way so thesis works with commentluv links, thesis wont use comment_text filter but it does get an array of comments)
                $new_commentarray = array();
                foreach($commentarray as $comment){
                    $data = get_comment_meta($comment->comment_ID,'cl_data','true');
                    $commentcontent = $comment->comment_content;
                    // luvlink added?
                    if($data && is_array($data)){
                        if($data['cl_post_url'] != '' && $data['cl_post_title'] != ''){ 
                            // luvlink was saved to meta, dofollow the link?
                            $nofollow = ' rel="nofollow"';
                            $isreg = get_user_by_email($comment->comment_author_email);
                            if($options['dofollow'] == 'everybody'){
                                $nofollow = '';
                            } elseif ($options['dofollow'] == 'registered' && $isreg){
                                $nofollow = '';
                            }
                            // construct link
                            $pclass = $data['cl_prem'] == 'p' ? ' p' : '';
                            $ajaxnonce = wp_create_nonce($comment->comment_ID);
                            $class = ' class="'.$ajaxnonce.' '.$comment->comment_ID.$pclass.'"';
                            $luvlink = '<a'.$class.$nofollow.' href="'.$data['cl_post_url'].'">'.$data['cl_post_title'].'</a>';
                            $search = array ('[name]', '[lastpost]' );
                            $replace = array ($comment->comment_author, $luvlink );
                            $prepend_text = $options ['comment_text'];
                            $inserted = str_replace ( $search, $replace, $prepend_text );
                            // check if author has a url. do not add the link if user has set to hide links for comments with no url
                            $authurl = $comment->comment_author_url;
                            $showlink = true;   
                            if($authurl == '' && isset($options['hide_link_no_url']) && $options['hide_link_no_url'] == 'on'){
                                $showlink = false;
                            }
                            // check link domain matches author url domain
                            if(!isset($options['hide_link_no_url_match'])){
                                $options['hide_link_no_url_match'] = 'nothing';
                            }
                            $authorurlarr = parse_url($authurl);
                            $linkurlarr = parse_url($data['cl_post_url']);
                            if($options['hide_link_no_url_match'] != 'nothing'){
                                if($authorurlarr['host'] != $linkurlarr['host']){
                                    // link has different domain
                                    if($options['hide_link_no_url_match'] == 'on'){
                                        $showlink = false;
                                    }
                                }
                            }               
                            if($showlink){
                                // construct string to be added to comment
                                $commentcontent .= "\n<span class=\"cluv\">$inserted";
                                // prepare heart icon if infopanel is on
                                $hearticon = '';
                                if($data['cl_prem'] == 'p' || $isreg) {
                                    // use PLUS heart for members
                                    $hearticon = 'plus';
                                }
                                if ($options ['infopanel'] == 'on') {
                                    $commentcontent .= '<span class="heart_tip_box"><img class="heart_tip '.$data['cl_prem'].' '.$comment->comment_ID.'" alt="My Profile" style="border:0" width="16" height="14" src="' . $this->plugin_url . 'images/littleheart'.$hearticon.'.gif"/></span>';
                                } 
                                $commentcontent.= '</span>';
                            }
                        }
                    }
                    // store new content in this comments comment_content cell
                    $comment->comment_content = $commentcontent;
                    // fill new array with this comment
                    $new_commentarray[] = $comment;
                }
                // admin page or public page?
                if($isadminpage){
                    // is being called by comment_text filter so expecting just content
                    return $commentcontent;
                } else {
                    // called from comments_array filter so expecting array of objects
                    return $new_commentarray;
                }
            }
            /**
            * called by do_ajax())
            * takes action when ajax request is made with URL from the comment form
            * send back 1 or 10 last posts depending on rules
            */
            function fetch_feed(){      
                // check nonce
                //debugbreak();
                $checknonce = check_ajax_referer('fetch',false,false);
                if(!$checknonce){
                    die(' error! not authorized '.strip_tags($_REQUEST['_ajax_nonce']));
                }
                if(!$_POST['url']){
                    die('no url');
                }
                if(!defined('DOING_AJAX')){
                    define('DOING_AJAX',true);
                }
                // try to prevent deprecated notices
                @ini_set('display_errors',0);
                @error_reporting(0);
                include_once(ABSPATH.WPINC.'/class-simplepie.php');
                $options = $this->get_options();
                $num = 1;
                $url = esc_url($_POST['url']);
                $orig_url = $url;
                // add trailing slash (can help with some blogs)
                if(!strpos($url,'?')){
                    $url = trailingslashit($url);
                }
                // fetch 10 last posts?
                if((is_user_logged_in() && $options['whogets'] == 'registered') || (!is_user_logged_in() && $options['whogets'] == 'everybody')){
                    $num = 10;
                } elseif($options['whogets'] == 'everybody') {
                    $num = 10;
                } elseif(current_user_can('manage_options')){
                    $num = 10;
                }
                // check if request is for the blog we're on
                if(strstr($url, home_url())){
                    //DebugBreak();          
                    $posts = get_posts(array('numberposts'=>10));
                    $return = array();
                    $error = '';
                    if($posts){
                        foreach($posts as $post){
                            $return[] = array('type'=>'blog','title'=>htmlspecialchars_decode(strip_tags($post->post_title)),'link'=>get_permalink($post->ID),'p'=>'u');
                        }
                    } else {
                        $error = __('Could not get posts for home blog',$this->plugin_domain);
                    }
                    // check for admin only notices to add
                    $canreg = get_option('users_can_register');
                    $whogets = $options['whogets'];
                    if(!$canreg && $whogets == 'registered'){
                        $return[] = array('type'=>'message','title'=>__('Warning! You have set to show 10 posts for registered users but you have not enabled user registrations on your site. You should change the operational settings in the CommentLuv settings page to show 10 posts for everyone or enable user registrations',$this->plugin_domain),'link'=>'');
                    }
                    $response = json_encode(array('error'=>$error,'items'=>$return));
                    header( "Content-Type: application/json" );
                    echo $response;
                    exit;    
                }
                // get simple pie ready
                $rss = new SimplePie();
                if(!$rss){
                    die(' error! no simplepie');
                }
                $rss->set_useragent('Commentluv /'.$this->version.' (Feed Parser; http://www.commentluv.com; Allow like Gecko) Build/20110502' );
                $rss->set_feed_url ( add_query_arg(array('commentluv'=>'true'),$url) );
                $rss->enable_cache ( FALSE );
                // fetch the feed
                $rss->init();
                $su = $rss->subscribe_url();
                $ferror = $rss->error();
                // try a fall back and add /?feed=rss2 to the end of url if the found subscribe url hasn't already got it
                // also try known blogspot feed location if this is a blogspot url
                if($ferror || strstr($ferror,'could not be found') && !strstr($su,'feed')){
                    unset($rss);
                    $rss = new SimplePie();
                    $rss->set_useragent('Commentluv /'.$this->version.' (Feed Parser; http://www.commentluv.com; Allow like Gecko) Build/20110502' );
                    $rss->enable_cache ( FALSE );
                    // construct alternate feed url
                    if(strstr($url,'blogspot')){
                        $url = trailingslashit($url).'feeds/posts/default/';
                    } else {
                        $url = add_query_arg(array('feed'=>'atom'),$url);
                    } 
                    $rss->set_feed_url($url);
                    $rss->init();
                    $ferror = $rss->error();
                    if($ferror || stripos($ferror,'invalid')){
                        $suburl = $rss->subscribe_url() ? $rss->subscribe_url() : $orig_url;
                        unset($rss);
                        $rss = new SimplePie();
                        $rss->set_useragent('Commentluv /'.$this->version.' (Feed Parser; http://www.commentluv.com; Allow like Gecko) Build/20110502' );
                        $rss->enable_cache ( FALSE );                                                   
                        $rss->set_feed_url($orig_url);
                        $rss->init();
                        $ferror = $rss->error();
                        // go back to original URL if error persisted
                        if(stripos($ferror,'invalid')){
                            //get raw file to show any errors
                            @include_once(ABSPATH.WPINC.'/SimplePie/File.php');
                            if(class_exists('SimplePie_File')){
                                $rawfile = new SimplePie_File($suburl, $rss->timeout, 5, null, $rss->useragent, $rss->force_fsockopen);
                            } elseif (class_exists($rss->file_class)){
                                $rawfile = new $rss->file_class($suburl, $rss->timeout, 5, null, $rss->useragent, $rss->force_fsockopen);
                            }
                            if(isset($rawfile->body)){
                                $rawfile = $rawfile->body;
                            } else {
                                $rawfile = __('Raw file could not be found',$this->plugin_domain);
                            }
                        }
                    }
                }
                $rss->handle_content_type();
                $gen = $rss->get_channel_tags('','generator');          
                $prem_msg = $rss->get_channel_tags('','prem_msg');
                $g = $num;
                $p = 'u';   
                $meta = array();
                //DebugBreak();
                if($gen && strstr($gen[0]['data'],'commentluv')){
                    $generator = $gen[0]['data'];
                    $meta['generator'] = $generator;
                    $pos=stripos($generator,'v=');
                    if(substr($generator,$pos+2,1)=='3'){
                        $g=15;
                        $p='p';
                    }
                }
                if($prem_msg){
                    $prem_msg = $prem_msg[0]['data'];
                }
                //DebugBreak();
                $error = $rss->error();
                $meta['used_feed'] = $rss->subscribe_url();
                //DebugBreak();
                // no error, construct return json
                if(!$error){

                    $arr = array();

                    // save meta
                    $meta['used_feed'] = $rss->subscribe_url ();

                    $feed_items = $rss->get_items();
                    foreach($feed_items as $item){
                        //debugbreak();
                        $type = 'blog';
                        $itemtags = $item->get_item_tags('','type');
                        if($itemtags){
                            $type = $itemtags[0]['data'];
                        }
                        $arr[] = array('type'=>$type,'title'=>htmlspecialchars_decode(strip_tags($item->get_title())),'link'=>$item->get_permalink(),'p'=>$p);
                        $g--;
                        if($g < 1){
                            break;
                        }
                    }
                    // add message to unregistered user if set
                    if(!is_user_logged_in() && $options['unreg_user_text'] && $options['whogets'] != 'everybody' && $p=='u'){
                        if(get_option('users_can_register')){
                            $arr[] = array('type'=>'message','title'=>$options['unreg_user_text'],'link'=>'');
                            if(!strstr($options['unreg_user_text'],'action=register')){
                                $register_link = apply_filters('register','<a href="' . site_url('wp-login.php?action=register', 'login') . '">' . __('Register') . '</a>');
                                $arr[] = array('type'=>'message','title'=>$register_link,'link'=>'');
                            }
                        }
                        if($options['whogets'] == 'registered' && get_option('users_can_regsiter')){
                            $arr[] = array('type'=>'message','title'=>__('If you are registered, you need to log in to get 10 posts to choose from',$this->plugin_domain),'link'=>'');
                        }
                    }
                    if($prem_msg){
                        $arr[] = array('type'=>'alert','title'=>$prem_msg,'link'=>'');
                    }
                    $response = json_encode(array('error'=>'','items'=>$arr,'meta'=>$meta));
                } else {
                    // had an error trying to read the feed 
                    $response = json_encode(array('error'=>$error,'meta'=>$meta,'rawfile'=>htmlspecialchars($rawfile)));
                }
                unset($rss);
                header( "Content-Type: application/json" );
                echo $response;
                exit;                    
            }
            /**
            * find number of approved comments in the past 14 days to have a commentluv link	
            * called in check_version
            * since 2.90.8
            * @return int
            */
            function get_numluv(){
                global $wpdb;
                $query = $wpdb->prepare('SELECT count(*) FROM '.$wpdb->commentmeta.' m JOIN '.$wpdb->comments.' c ON m.comment_id = c.comment_ID WHERE m.meta_key = %s AND c.comment_approved = %s AND c.comment_date > NOW() - INTERVAL 14 DAY','cl_data','1');
                return intval($wpdb->get_var($query));
            }
            /** get_options
            * This function sets default options and handles a reset to default options
            * @param string $reset = 'no' - whether to return default settings
            * return array
            */
            function get_options($reset = 'no') {
                // see if we offer registration incentive
                $register_link = '';
                if(get_option('users_can_register')){
                    $register_link = apply_filters('register','<a href="' . site_url('wp-login.php?action=register', 'login') . '">' . __('Register') . '</a>');
                }
                // default values
                $this->handle_load_domain (); 
                $default = array ('version'=>$this->version,'enable'=>'yes','enable_for'=>'both', 'default_on' => 'on', 'default_on_admin'=>'on',
                    'badge_choice' => 'drop_down', 'badge_type'=>'default', 'link'=>'off','infopanel'=>'on', 'infoback'=>'white', 'infotext'=>'black',
                    'comment_text'=>'[name] '.__('recently posted',$this->plugin_domain).'...[lastpost]', 'whogets'=>'registered', 'dofollow' => 'registered',
                    'unreg_user_text'=>__('If you register as a user on my site, you can get your 10 most recent blog posts to choose from in this box.',$this->plugin_domain).' '.$register_link,
                    'unreg_user_text_panel'=>__('If this user had registered to my site then they could get 10 last posts to choose from when they comment and you would be able to see a list of their recent posts in this panel',$this->plugin_domain),
                    'template_insert'=>'','minifying'=>'','api_url'=>admin_url('admin-ajax.php'),'author_name'=>'author','email_name'=>'email','url_name'=>'url','comment_name'=>'comment',
                    'hide_link_no_url'=>'nothing','hide_link_no_url_match'=>'nothing');
                $options = get_option ( $this->db_option, $default);
                // return the options
                if($reset == 'yes'){  
                    return $default;
                }
                if(!$options['api_url']){
                    $options['api_url'] = admin_url('admin-ajax.php');
                }
                if(!$options['enable']){
                    $options['enable'] = 'yes';
                }

                return $options;
            }
            /** handle_load_domain
            * This function loads the localization files required for translations
            * It expects there to be a folder called /lang/ in the plugin directory
            * that has all the .mo files
            */
            function handle_load_domain() {
                // get current language
                $locale = get_locale ();
                // locate translation file
                $mofile = WP_PLUGIN_DIR . '/' . plugin_basename ( dirname ( __FILE__ ) ) . '/lang/' . $this->plugin_domain . '-' . $locale . '.mo';
                // load translation
                load_textdomain ( $this->plugin_domain, $mofile );
            }
            /** init
            * This function registers styles and scripts
            */
            function init(){
                wp_register_style( 'commentluv_style',$this->plugin_url.'css/commentluv.css' , $this->version);
                wp_register_script( 'commentluv_script', $this->plugin_url.'js/commentluv.js',array('jquery'),$this->version );
            }
            /** install
            * This function is called when the plugin activation hook is fired when
            * the plugin is first activated or when it is auto updated via admin.
            * use it to make any changes needed for updated version or to add/check
            * new database tables on first install.
            */
            function install(){        
                $options = $this->get_options();     
                if(!$installed_version = get_option('cl_version')){
                    // no installed version yet, set to version that was before big change
                    $installed_version = 2.8;
                } else {
                    // convert existing version to php type version number
                    $installed_version = $this->php_version($installed_version);
                }
                // for version before 2.9
                if(version_compare($installed_version,'2.9','<')){
                    // make any changes to this new versions options if needed and update
                    update_option($this->db_option,$this->get_options('yes'));
                }
                // new addition to technical settings after 2.90.1 release
                if(version_compare($installed_version,'2.9.0.1','<')){
                    $options['api_url'] = admin_url('admin-ajax.php');
                    $options['enable'] = 'yes';
                    update_option($this->db_option,$options);   
                }
                // update cl_version in db
                if($this->php_version($this->version) != $installed_version){
                    update_option('cl_version',$this->version);
                }
                // has the comment meta table?
                global $wpdb;
                $query = $wpdb->prepare("SHOW tables LIKE %s",$wpdb->commentmeta);
                $dbtable = $wpdb->get_var($query);
                //$o['dbtable'] = $dbtable;
                if(!$dbtable){
                    add_action('admin_notices',create_function('','echo "<div class=\"error\">'.__('Your Wordpress install is missing the <strong>wp_commentmeta</strong> table!',$pd).'<br>'.__(' CommentLuv cannot work without this table please see this wordpress forum post to learn how to add one ->',$pd).'<a target=\"_blank\" href=\"http://wordpress.org/support/topic/wp_commentmeta-table-a39xxxx2_blogwp_commentmeta-doesnt-exist?replies=7#post-1378281\">'.__('Missing wp_commentmeta table',$pd).'</a></div>";'));
                }
            }
            /**
            * helper function called by mulitple functions
            * used to determine if commentluv is enabled
            */
            function is_enabled(){
                $options = $this->get_options();
                // see if we need to add here or not
                if(($options['enable_for'] == 'posts' && is_page()) || ($options['enable_for'] == 'pages' && !is_page())){
                    return false;
                }
                if($options['enable'] != 'yes'){
                    return false;
                }
                return true;
            }
            /**
            * called by apply_filter('kindergarten_html
            * Used to clean $input to only allow a kiddy set of html tags
            * 
            * @param string $input - the string to be cleaned
            * @return string 
            */
            function kindergarten_html($input){
                $allowedtags = array(
                    'h1' => array(),
                    'br' => array(),
                    'a' => array('href' => array(),'title' => array(),'rel' => array(),'target'=>array(), 'class'=>array()),
                    'small' =>array(),
                    'p' =>array( 'class'=>array()),
                    'strong' => array(),
                    'img' => array('src' => array(),'alt' => array(),'width' => array(),'height' => array(),'align'=> array()),
                    'span' => array('class'=>array())
                );
                return wp_kses($input,$allowedtags);
            }
            /**
            * Ajax handler for the subscribe button on the settings page.
            * called by add_action ( 'wp_ajax_notify_signup'
            */
            function notify_signup(){
                //DebugBreak();
                global $current_user;
                $email = $current_user->user_email;
                $firstname = $current_user->first_name;
                if(!$firstname){
                    $firstname = $current_user->user_nicename;
                }
                $message = "\n Email: ".$email."\n\n Name: ".$firstname."\n\n Meta: settings_page_289"."\n\n";
                $to = 'cl_notify29@aweber.com';
                $headers = 'From: '.$firstname.' <'.$email.'>'."\r\n\\";
                $mail = wp_mail($to,'cl_notify',$message,$headers);
                if($mail === true){
                    $options = $this->get_options();
                    $options['subscribed'] = true;
                    update_option($this->db_option,$options);
                }
                $return = array('success'=>$mail,'email'=>$email);
                // return response
                $response = json_encode($return);
                // response output
                header( "Content-Type: application/json" );
                echo $response;
                // IMPORTANT: don't forget to "exit"
                exit;
            }
            /** options_sanitize
            * This is the callback function for when the settings get saved, use it to sanitize options
            * it is called by the callback setting of register_setting in admin_init
            * @param mixed $options - the options that were POST'ed
            * return mixed $options
            */
            function options_sanitize($options){
                //DebugBreak();
                $old_options = $this->get_options();
                // if not enabled, only save that so other settings remain unchanged
                if($options['enable'] == 'no'){
                    $old_options['enable'] = 'no';
                    return $old_options;
                }
                // check for reset
                if(isset($options['reset'])){
                    return $this->get_options('yes');
                }
                // if on multisite and this isnt super admin saving, 
                // only allow kindergarten html.
                if(is_multisite() && !is_super_admin()){
                    foreach($options as $key => $option){
                        $options[$key] = apply_filters('kindergarten_html',$option);
                    }
                }
                // add error notices if any
                $canreg = get_option('users_can_register');
                if($options['whogets']=='registered' && !$canreg){
                    add_settings_error('whogets','whogets',__('Warning! You have set to show 10 posts for registered users but you have not enabled user registrations on your site. You should change the operational settings in the CommentLuv settings page to show 10 posts for everyone or enable user registrations',$this->plugin_domain),'error');
                }
                return $options;
            }
            /**
            * converts a string into a php type version number
            * eg. 2.81.2 will become 2.8.1.2
            * used to prepare a number to be used with version_compare
            * 
            * @param mixed $string - the version to be converted to php type version
            * @return string
            */
            function php_version($string){
                if(empty($string)){
                    return;
                }
                $version = str_replace('.','',$string);
                $std = array();
                for($i=0; $i < strlen($version); $i++){
                    $std[] = $version[$i];
                }
                $php_version = implode('.',$std);
                return $php_version;
            }
            /** commentluv_action
            * This function adds a link to the settings page for the plugin on the plugins listing page
            * it is called by add filter plugin_action_links
            * @param $links - the links being filtered
            * @param $file - the name of the file
            * return array - the new array of links
            */
            function plugin_action_link($links, $file) {
                $this_plugin = plugin_basename ( __FILE__ );
                if ($file == $this_plugin) {
                    $links [] = "<a href='options-general.php?page={$this->slug}'>" . __ ( 'Settings', $this->plugin_domain ) . "</a>";
                }
                return $links;
            }
            /**
            * Detects if a commentluv api or plugin is requesting a feed
            * and sends back an xml feed of the  post titles and links that were found for the query
            * called by add_filter('found_posts' so we always have the posts found for the requested category/author/tag/ etc
            * @param (int) $foundposts - the number of posts that were found
            * @param (obj) $object - the query object
            * @return $foundposts - need to return this if the request is not from a commentluv api or plugin
            * 
            * deprecated in 2.90.9.9 due to new 3.4 wp query code messing up with static homepages.
            * have to use just 10 recent posts, does not detect author or category urls now. (no one uses them!)
            */
            function send_feed($foundposts,$object){               
                if(headers_sent() == true){
                    return $foundposts; 
                }
                $options = $this->get_options();
                // check if detection disabled
                if(isset($options['disable_detect']) && $options['disable_detect'] == 'on'){
                    return $foundposts;
                }
                if($this->is_commentluv_request === true){
                    // is commentluv useragent (set in init action)
                    // get rid of any output (prevents some themes on some hosts from outputting code before commentluv can show xml feed)
                    ob_clean();
                }
                $error = false;
                if($foundposts < 1 && ! $object->is_home){
                    $error = true;
                }           
                $enabled = $options['enable'];

                // General checking
                if (preg_match("/Commentluv/i", $_SERVER['HTTP_USER_AGENT'])) {
                    if($object->is_home){
                        // we're on the home page so just get the last 10 posts (prevents a slider or other featured posts widget from making the object full of the featured posts)'
                        wp_reset_query();
                        $query = new WP_Query();   
                        remove_filter('found_posts',array(&$this,'send_feed'),-1,2);
                        $object->posts = $query->query('showposts=10&post_type=post');     
                    }
                    $feed = '<?xml version="1.0" encoding="'.get_bloginfo('charset').'" ?>
                    <rss version="2.0">
                    <channel>
                    <title><![CDATA['. get_bloginfo('title') .']]></title>
                    <link>'. home_url() .'</link>
                    <description><![CDATA['. get_bloginfo('description') .']]></description>
                    <language>'.get_bloginfo('language').'</language>
                    <generator>commentluv?v='.$this->version.'</generator>
                    <commentluv>'.$enabled.'</commentluv>
                    <success>'.$error.'</success>';
                    if($object->posts){
                        foreach($object->posts as $post){
                            $feed .= '<item><title><![CDATA['.get_the_title($post->ID).']]></title>
                            <link>'.get_permalink($post->ID).'</link>
                            <type>blog</type>
                            </item>';
                        }
                    } else {
                        $feed .= '<item><title>'.__('No Posts Were Found!',$pd).'</title>
                        <link>'.get_permalink($post->ID).'</link>
                        </item>';
                    }
                    $feed .= '</channel></rss>';
                    header("Content-Type: application/xml; charset=".get_bloginfo('charset')); 
                    echo $feed;
                    exit;
                }
                return $foundposts;
            }
            /** send back a feed when another commentluv is asking
            * called by add_action(template_redirect) in detect_useragent
            * 
            */
            function send_feed_file(){
                //debugbreak();
                $postquery = array('numberposts'=>10,'post_type'=>'post');     
                if(is_category()){
                    $cat = get_query_var('cat');
                    $postquery['category']=$cat;
                }
                if(is_author()){
                    $author = get_query_var('author');
                    $postquery['author'] = $author;
                }
                if(is_tag()){
                    $tag = get_query_var('tag');
                    $postquery['tag'] = $tag;
                }             
                $posts = get_posts($postquery);
                $enabled = $this->is_enabled();
                $error = 'false';
                if(sizeof($posts) < 1){
                    $error = 'true';
                }
                $feed = '<?xml version="1.0" encoding="'.get_bloginfo('charset').'" ?>'.
                '<rss version="2.0">'.
                '<channel>'.
                '<title><![CDATA['. get_bloginfo('title') .']]></title>'.
                '<link>'. get_bloginfo('home') .'</link>'.
                '<description><![CDATA['. get_bloginfo('description') .']]></description>'.
                '<language>'.get_bloginfo('language').'</language>'.
                '<generator>commentluv?v='.$this->version.'</generator>'.
                '<commentluv>'.$enabled.'</commentluv>'.
                '<success>'.$error.'</success>';
                if(is_array($posts)){ 
                    foreach($posts as $post){
                        $title = get_the_title($post->ID);         
                        //$feed .= '<item><title>'.strip_tags($title).'</title>'.
                        $feed .= '<item><title><![CDATA['.$title.']]></title>'.
                        '<link>'.get_permalink($post->ID).'</link>'.
                        '<type>blog</type>'.
                        '</item>';
                    }    
                } else {
                    $feed .= '<item><title>'.__('No Posts Were Found!',$pd).'</title>'.
                    '<link>'.get_permalink($post->ID).'</link>'.
                    '</item>';
                }
                $feed .= '</channel></rss>';
                ob_end_clean();                                                       
                header("Content-Type: application/atom+xml; charset=".get_bloginfo('charset'));
                echo $feed;    
                exit;                        

            }
            /**
            * called by __construct
            * used to setup hooks and filters for enabled plugin
            */
            function setup_hooks(){   
                add_action ( 'comment_form',array(&$this,'add_fields')); // add fields to form
                add_action ( 'wp_print_styles',array(&$this,'add_style')); // add style
                add_action ( 'template_redirect',array(&$this,'add_script')); // add commentluv script
                add_action ( 'admin_print_scripts-edit-comments.php', array (&$this, 'add_removeluv_script') ); // add the removeluv script to admin page
                add_action ( 'wp_footer',array(&$this,'add_footer')); // add localize to footer

                add_action ( 'wp_insert_comment', array (&$this, 'comment_posted'),1,2); // add member id and other data to comment meta priority 1, 2 vars
                if(!is_admin()){
                    add_filter ( 'comments_array', array (&$this, 'do_shortcode' ), 1 ); // add last blog post data to comment content
                } else {
                    add_filter ( 'comment_text', array (&$this, 'do_shortcode' ), 1 ); // add last blog post data to comment content on admin screen
                }                                                                                                                                   
                add_filter ( 'comment_row_actions', array (&$this,'add_removeluv_link')); // adds a link to remove the luv from a comment on the comments admin screen
            }
            /**
            * called by add_action('admin_notices in admin_init()
            * Used to show a notice if there is a new version of CommentLuv available                                                   
            */ 
            function show_upgrade_notice(){
                $options = $this->get_options();
                $update_url = wp_nonce_url('update.php?action=upgrade-plugin&amp;plugin=commentluv%2Fcommentluv.php', 'upgrade-plugin_commentluv/commentluv.php');
                echo '<div id="clupgrade" class="update-nag">';
                if($options['upgrade_message']){
                    echo $options['upgrade_message'];
                    $details_link = '<br /><a href="'.admin_url().'plugin-install.php?tab=plugin-information&amp;plugin=commentluv&amp;TB_iframe=true&amp;width=640&amp;height=350" class="thickbox" title="commentluv"> View new version details</a>';
                    printf( __('%s or <a href="%s">update now</a>.', $this->plugin_domain),  $details_link, $update_url ) ;
                } else {
                    echo __('There is a new version of Commentluv available, please upgrade by visiting this site',$this->plugin_domain);
                    echo '<br><a href="http://www.commentluv.com" target="_blank">www.commentluv.com</a>';
                }
                //echo '<span style="float:right"><a href="'.admin_url('options-general.php?page='.$this->slug.'&dismiss=true').'">'.__('Dismiss notice',$this->plugin_domain).'</a></span>';
                echo '</div>';
            }

            /** options_page
            * This function shows the page for saving options
            * it is called by add_options_page
            * You can echo out or use further functions to display admin style widgets here
            */
            function options_page(){
                $o = $this->get_options();
                $dbo = $this->db_option;
                $pd = $this->plugin_domain;
                $badges = array('default_image'=>'CL91_default.png','default'=>'CL91_default.png','white'=>'CL91_White.gif','black'=>'CL91_Black.gif','none'=> 'nothing.gif');
                //DebugBreak();
                // remove notice if requested
                if(isset($_GET['dismiss'])){
                    unset($o['upgrade']);
                    if(array_key_exists('upgrade_message',$o)){
                        unset($o['upgrade_message']);
                    }
                    update_option($this->db_option,$o);
                    echo '<script>jQuery("#clupgrade").hide()</script>';
                }                      
            ?>
            <div class="wrap">
                <h2><?php _e('CommentLuv Settings v',$this->plugin_domain);?><?php echo $this->version;?></h2>
                <div id="poststuff" style="margin-top:10px; width: 965px;">
                    <div id="mainblock" style="float: left; width:710px">
                        <form method="post" action="options.php">
                            <?php settings_fields( 'commentluv_options_group' ); // the name given in admin init
                                // after here, put all the inputs and text fields needed
                            ?>
                            <div class="dbx-content">
                                <table class="widefat">
                                    <thead>
                                        <tr><th scope="col"><?php _e('Important!',$pd);?></th><th><?php _e('Subscription Information',$pd);?></th></tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td width="250">
                                                <h2 style="margin: 0 0 10px 0;"><?php _e('CommentLuv 3.0 Premium is here!',$pd);?> <a style="font-size:0.8em" title="Premium has some excellent features! try it out today. Full 30 day gaurantee" target="_blank" href="http://ql2.me/upgradetopremium">Upgrade to Premium</a></h2>
                                                <img align="left" src="<?php echo $this->plugin_url;?>images/privacy-guarantee.png"/><?php _e('I promise not to sell your details or send you spam. You will ONLY receive emails about plugin updates.',$pd);?>
                                            </td>
                                            <td>
                                                <p><?php _e('Do you like CommentLuv? How about an even better version with much more control over dofollow and some awesome social enticements that will make your posts go viral by offering your readers more choice of posts if they +1, Like or tweet your post? Get CommentLuv Premium Today!',$pd);?></p>
                                                <?php 
                                                    if(isset($o['subscribed'])){
                                                        echo '<div class="submit">'.__('You have already subscribed, if you have not received the verification within 12 hours, please click the button to resend or try the form at',$pd).' <a target="_blank" href="http://www.commentluv.com/">www.commentluv.com</a><br><input style="margin:0 auto; display: block;" type="button" id="cl_notify" value="'.__('Resend Verification',$pd).'"/></div>';
                                                    } else {
                                                        echo '<div class="submit" style=" background-color: green; padding-left: 5px; padding-right: 5px; border-radius: 15px; -moz-border-radius: 15px; text-align: center;"><input style="margin: 0 auto; display:block" id="cl_notify" type="button" name="cl_notify" value="'.__('Click for a special offer!',$pd).'" /></div>';
                                                    }
                                                ?>
                                                <div id="notify_message"></div>
                                            </td>    
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <?php _e('<b>Are you getting targeted by spammers?</b> CommentLuv links are valuable which is why it promotes comments but some nasty spammers try to take advantage of this by leaving spam just to get the link. Don\'t worry, there is answer!... you can get CommentLuv Premium which has advanced anti-spam features which has been proven to almost eliminate spam on users blogs. You can upgrade by clicking the link above. <p><b>Not ready to buy premium yet?</b> that\'s ok too! Why not try GASP which is a lite version of the anti spam plugin that CommentLuv Premium uses. You can get it for FREE by searching for GASP in your "add new" section of your plugins page in your dashboard.',$pd);?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>


                                <br/>


                                <table class="widefat">
                                    <thead>
                                        <tr><th><?php _e('Primary Setting',$pd);?></th><th colspan="3"></th><th style="text-align: center;"><?php _e('Help Video',$pd);?></th></tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2" style="width: 40%; vertical-align: middle; text-align: center; font-size: 1.3em; font-weight: bolder;"><label for="<?php echo $dbo;?>[enable]"><?php _e('Enable CommentLuv?',$pd);?></label></td>
                                            <td style="width:20%; vertical-align: middle; font-size: 1.3em;"><input class="clenable" type="radio" name="<?php echo $dbo;?>[enable]" value="yes" <?php checked($o['enable'],'yes');?>/><?php _e('Yes',$pd);?></td>
                                            <td style="width:20%; vertical-align: middle; font-size: 1.3em;"><input class="clenable" type="radio" name="<?php echo $dbo;?>[enable]" value="no" <?php checked($o['enable'],'no');?>/><?php _e('No',$pd);?></td>
                                            <td style="text-align: center; border: 2px dotted;"><a onclick="return false;" href="<?php echo $this->plugin_url . 'videos/'; ?>primarysettings.php?KeepThis=true&amp;TB_iframe=true&amp;height=355&width=545" class="thickbox"><img src="<?php echo $this->plugin_url;?>images/playbuttonsmall.png"/></a></td>
                                        </tr>
                                        <tr class="ifenable">
                                            <td style="text-align: center;" colspan="5">
                                                <input type="radio" name="<?php echo $dbo;?>[enable_for]" value="posts" <?php checked($o['enable_for'],'posts');?>/><?php _e('On Posts',$pd);?>
                                                <input style="margin-left: 100px;" type="radio" name="<?php echo $dbo;?>[enable_for]" value="pages" <?php checked($o['enable_for'],'pages');?>/><?php _e('On Pages',$pd);?>
                                                <input style="margin-left: 100px;" type="radio" name="<?php echo $dbo;?>[enable_for]" value="both" <?php checked($o['enable_for'],'both');?>/><?php _e('On Both',$pd);?>
                                            </td>
                                        </tr>
                                        <tr class="ifenable">
                                            <td style="text-align: center;" colspan="2">
                                                <input type="checkbox" name="<?php echo $dbo;?>[default_on]" <?php if(isset($o['default_on'])) checked($o['default_on'],'on');?> value="on"/> <label for="<?php echo $dbo;?>[default_on]"><?php _e('On by default?',$pd);?></label>
                                            </td>
                                            <td></td>
                                            <td style="text-align: center;" colspan="2">
                                                <input type="checkbox" name="<?php echo $dbo;?>[default_on_admin]" <?php if(isset($o['default_on_admin'])) checked($o['default_on_admin'],'on');?> value="on"/><label for="<?php echo $dbo;?>[default_on_admin]"> <?php _e('On for admin?',$pd);?></label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>


                                <br>


                                <table class="widefat ifenable display-settings">
                                    <thead>
                                        <tr><th><?php _e('Appearance',$pd);?></th><th colspan="3"></th><th style="text-align: center;"><?php _e('Help Video',$pd);?></th></tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><h3><label for="<?php echo $dbo;?>[badge_choice]"><?php _e('Badge',$pd);?></label></h3></td>
                                            <td><h3><label for="<?php echo $dbo;?>[badge_choice]"><?php _e('Custom Image URL',$pd);?></label></h3></td>
                                            <td><h3><label for="<?php echo $dbo;?>[badge_choice]"><?php _e('Use Text',$pd);?></label></h3></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="radio" class="radio" name="<?php echo $dbo;?>[badge_choice]" value="drop_down" <?php checked($o['badge_choice'],'drop_down');?>/> 
                                                <select id="badge_type" name="<?php echo $dbo;?>[badge_type]">
                                                    <option value="default_image" <?php selected($o['badge_type'],'default_image');?>><?php _e('Default',$pd);?></option>
                                                    <option value="white" <?php selected($o['badge_type'],'white');?>><?php _e('White',$pd);?></option>
                                                    <option value="black" <?php selected($o['badge_type'],'black');?>><?php _e('Black',$pd);?></option>
                                                    <option value="none" <?php selected($o['badge_type'],'none');?>><?php _e('None',$pd);?></option>
                                                </select>

                                                <p style="margin: 8px 0px 0px 8px;"><img id="display_badge" style="border: 1px solid #000; padding: 3px;" src="<?php echo $this->plugin_url;?>images/<?php echo $badges[$o['badge_type']];?>"/></p>
                                            </td>
                                            <td>
                                                <input type="radio" class="radio" name="<?php echo $dbo;?>[badge_choice]" value="custom" <?php checked($o['badge_choice'],'custom');?>/> 
                                                <input type="text" name="<?php echo $dbo;?>[custom_image_url]" value="<?php if(isset($o['custom_image_url'])) echo $o['custom_image_url'];?>"/>
                                                <?php 
                                                    if(isset($o['custom_image_url']) && $o['custom_image_url'] != ''){
                                                        // show image
                                                        echo '<p style="margin: 8px 0px 0px 8px;"><img id="custom_badge" style="border: 1px solid #000; padding: 3px;" src="'.$o['custom_image_url'].'"/></p>';
                                                }   ?>
                                            </td>
                                            <td>

                                                <input type="radio" class="radio" name="<?php echo $dbo;?>[badge_choice]" value="text" <?php checked($o['badge_choice'],'text');?>/> 
                                                <input type="text" name="<?php echo $dbo;?>[badge_text]" value="<?php if(isset($o['badge_text'])) echo $o['badge_text'];?>"/>
                                                <p style="margin: 8px 0px 0px 8px;"><input type="checkbox" name="<?php echo $dbo;?>[link]" value="on" <?php if(isset($o['link'])) checked($o['link'],'on');?>/> <label for="<?php echo $dbo;?>[link]"><?php _e('Link to Commentluv?',$pd);?></label>
                                            </td>
                                            <td></td>
                                            <td style="text-align: center; border: 2px dotted; width:125px"><a onclick="return false;" href="<?php echo $this->plugin_url . 'videos/'; ?>appearancesettings.php?KeepThis=true&amp;TB_iframe=true&amp;height=355&width=545" class="thickbox"><img src="<?php echo $this->plugin_url;?>images/playbuttonsmall.png"/></a></td>
                                        </tr>

                                        <tr>
                                            <td><br><input type="checkbox" name="<?php echo $dbo;?>[infopanel]" <?php checked($o['infopanel'],'on');?> value="on"/><label for="<?php echo $dbo;?>[infopanel]"> <?php _e('Enable info panel?',$pd);?></label></td>
                                            <td><label for="<?php echo $dbo;?>[infoback]"><?php _e('Info panel background color',$pd);?></label><br><input type="text" size="6" name="<?php echo $dbo;?>[infoback];?>" value="<?php echo $o['infoback'];?>"/></td>
                                            <td><label for="<?php echo $dbo;?>[infotext]"><?php _e('Info panel text color',$pd);?></label><br><input type="text" size="6" name="<?php echo $dbo;?>[infotext];?>" value="<?php echo $o['infotext'];?>"/></td>
                                            <td></td>
                                            <?php 
                                                $tdstyle = '"border: 1px solid #dfdfdf; vertical-align: middle; text-align: center; background-color: '.$o['infoback'].'"';
                                                $spanstyle = '"color: '.$o['infotext'].'"';
                                            ?>
                                            <td style=<?php echo $tdstyle;?>><span style=<?php echo $spanstyle;?>><?php _e('Example text and background color',$pd);?></span></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <br>

                                <table class="widefat ifenable messages">
                                    <thead>
                                        <tr>
                                            <th colspan="2"><?php _e('Messages',$pd);?></th><th scope="col"></th><th></th><th style="text-align: center;"><?php _e('Help Video',$pd);?></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2">
                                                <label for="<?php echo $dbo;?>[comment_text]"><?php _e('Text to be displayed in the comment',$pd);?></label>
                                                <br><input type="text" style="width: 95%" name="<?php echo $dbo;?>[comment_text]" value="<?php echo $o['comment_text'];?>"/>
                                            </td>
                                            <td style="border: 1px dashed #dfdfdf;"><?php _e('[name] = The users name',$this->plugin_domain);?><br><?php _e('[lastpost] = The last blog post link',$this->plugin_domain);?></td>
                                            <td>&nbsp;</td>
                                            <td style="text-align: center; border: 2px dotted; width:125px"><a onclick="return false;" href="<?php echo $this->plugin_url . 'videos/'; ?>messagessettings.php?KeepThis=true&amp;TB_iframe=true&amp;height=355&width=545" class="thickbox"><img src="<?php echo $this->plugin_url;?>images/playbuttonsmall.png"/></a></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">

                                                <?php _e('Message for unregistered user in the drop down box',$pd);?>
                                                <br>(<?php _e('Message will not be shown if you do not have registrations enabled',$this->plugin_domain);?>)
                                                <br><textarea rows="5" style="width: 95%" name="<?php echo $dbo;?>[unreg_user_text]"><?php echo $o['unreg_user_text'];?></textarea>
                                                <?php 
                                                    if(get_option('users_can_register')){
                                                        _e('Your register link code',$pd);
                                                        echo '<br>';
                                                        _e('(this will be automatically added if you have not added it yourself to the textarea above)',$pd);
                                                        $register_link = apply_filters('register','<a href="' . site_url('wp-login.php?action=register', 'login') . '">' . __('Register') . '</a>');
                                                        echo ' : <input style="width:95%" type="text" value="'.htmlspecialchars($register_link).'" disabled/>';
                                                    }
                                                ?>
                                            </td>
                                            <td colspan="2" style="width:125px;">
                                                <?php // show warning if registration is not enabled
                                                    if(!get_option('users_can_register')){
                                                        echo '<div style="border: 2px dashed red;">';
                                                        _e('You have NOT set your blog to allow registrations, you can do that in Settings/General',$pd);
                                                        echo ' <a href="'.admin_url('options-general.php').'">'.__('here',$pd).'</a>';
                                                        echo '</div>';
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <?php _e('Message for unregistered user in the info panel',$pd);?>
                                                <br>(<?php _e('Message will not be shown if you do not have registrations enabled',$this->plugin_domain);?>)
                                                <br><textarea rows="5" style="width:95%;" name="<?php echo $dbo;?>[unreg_user_text_panel]"><?php echo $o['unreg_user_text_panel'];?></textarea>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <br>

                                <table class="widefat ifenable operational-settings">
                                    <thead>
                                        <tr>
                                            <th colspan="2"><?php _e('Operational Settings',$pd);?></th><th scope="col"></th><th></th><th style="text-align: center;"><?php _e('Help Video',$pd);?></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="4">
                                                <?php _e('Who to give 10 last posts to choose from when they comment?',$pd);?>
                                                <p><input type="radio" name="<?php echo $dbo;?>[whogets]" value="registered" <?php checked($o['whogets'],'registered');?>/> <label for="<?php echo $dbo;?>[whogets]"><?php _e('Only Registered Members',$pd);?></label>
                                                <input style="margin-left: 25px;" type="radio" name="<?php echo $dbo;?>[whogets]" value="everybody" <?php checked($o['whogets'],'everybody');?>/> <label for="<?php echo $dbo;?>[whogets]"><?php _e('Everybody',$pd);?></label>
                                                <input style="margin-left: 25px;" type="radio" name="<?php echo $dbo;?>[whogets]" value="nobody" <?php checked($o['whogets'],'nobody');?>/> <label for="<?php echo $dbo;?>[whogets]"><?php _e('Nobody',$pd);?></label>

                                            </td>
                                            <td style="text-align: center; border: 2px dotted; width:125px"><a onclick="return false;" href="<?php echo $this->plugin_url . 'videos/'; ?>operationalsettings.php?KeepThis=true&amp;TB_iframe=true&amp;height=355&width=545" class="thickbox"><img src="<?php echo $this->plugin_url;?>images/playbuttonsmall.png"/></a></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">
                                                <?php _e('Whose links should be dofollow?',$pd);?>
                                                <p><input type="radio" name="<?php echo $dbo;?>[dofollow]" value="registered" <?php checked($o['dofollow'],'registered');?>/> <label for="<?php echo $dbo;?>[whogets]"><?php _e('Only Registered Members Links',$pd);?></label>
                                                <input style="margin-left: 25px;" type="radio" name="<?php echo $dbo;?>[dofollow]" value="everybody" <?php checked($o['dofollow'],'everybody');?>/> <label for="<?php echo $dbo;?>[whogets]"><?php _e('Everybody gets dofollow links',$pd);?></label>
                                                <input style="margin-left: 25px;" type="radio" name="<?php echo $dbo;?>[dofollow]" value="nobody" <?php checked($o['dofollow'],'nobody');?>/> <label for="<?php echo $dbo;?>[whogets]"><?php _e('Nobody gets dofollow links',$pd);?></label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <br>

                                <table class="widefat ifenable technical" style="border: 3px solid red">
                                    <thead>
                                        <tr>
                                            <th colspan="2"><?php _e('Technical Settings',$pd);?></th><th scope="col"><span id="opentech" style="color: blue; cursor: pointer;"><?php _e('Click to open technical settings',$pd);?></span></th><th></th><th style="text-align: center;"><?php _e('Help Video',$pd);?></th>

                                        </tr>
                                    </thead>
                                    <tbody id="techbody" style="display:none">
                                        <tr>
                                            <td colspan="4">
                                                <h3><?php _e('Please check the help video for this section before changing settings',$pd);?></h3>
                                                <?php _e('In most cases, you will NOT need to change the settings in this box unless you have a custom comment form, template or you are using minifying or caching plugins',$pd);?>
                                            </td>
                                            <td style="text-align: center; border: 2px dotted; width:125px"><a onclick="return false;" href="<?php echo $this->plugin_url . 'videos/'; ?>technicalsettings.php?KeepThis=true&amp;TB_iframe=true&amp;height=355&width=545" class="thickbox"><img src="<?php echo $this->plugin_url;?>images/playbuttonsmall.png"/></a></td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: #dfdfdf; text-align: center; font-weight: bolder;" colspan="5"><?php _e('Compatibility',$pd);?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <input type="checkbox" name="<?php echo $dbo;?>[template_insert]" <?php if(isset($o['template_insert'])) checked($o['template_insert'],'on');?> value="on"/><label for="<?php echo $dbo;?>[template_insert]"> <?php _e('Use manual insert of badge code?',$pd);?></label>
                                                <br>( <strong>&lt;?php cl_display_badge(); ?&gt;</strong> )
                                            </td>
                                            <td colspan="2">
                                                <input type="checkbox" name="<?php echo $dbo;?>[minifying]" <?php if(isset($o['minifying'])) checked($o['minifying'],'on');?> value="on"/><label for="<?php echo $dbo;?>[minifying]"> <?php _e('Enable minifying compatibility?',$pd);?></label>
                                                <br><?php _e('For caching plugins (places localized code in footer)',$pd);?>
                                            </td> 
                                            <td>
                                                <input type="checkbox" name="<?php echo $dbo;?>[disable_detect]" <?php if(isset($o['disable_detect'])) checked($o['disable_detect'],'on');?> value="on"/><label for="<?php echo $dbo;?>[disable_detect]"> <?php _e('Disable Detection?',$pd);?></label>
                                                <br><?php _e('For XML errors',$pd);?>
                                            </td> 
                                        </tr>
                                        <tr>
                                            <td style="background-color: #dfdfdf; text-align: center; font-weight: bolder;" colspan="5"><?php _e('API URL',$pd);?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <input type="text" size="60" name="<?php echo $dbo;?>[api_url]" value="<?php echo $o['api_url'];?>"/><label for="<?php echo $dbo;?>[api_url]"> <?php _e('URL to use for API',$pd);?></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: #dfdfdf; text-align: center; font-weight: bolder;" colspan="5"><?php _e('Comment Form Field Values',$pd);?></td>
                                        </tr>
                                        <tr> 
                                            <td colspan="2"><?php _e('Authors Name field name',$this->plugin_domain);?></td>
                                            <td colspan="2"><input type="text" value="<?php echo $o['author_name'];?>" name="<?php echo $dbo;?>[author_name]"/></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><?php _e('Email field name',$this->plugin_domain);?></td>
                                            <td colspan="2"><input value="<?php echo $o['email_name'];?>" type="text" name="<?php echo $dbo;?>[email_name]"/></td>
                                            <td></td>
                                        </tr>
                                        <tr> 
                                            <td colspan="2"><?php _e('Authors URL field name',$this->plugin_domain);?></td>
                                            <td colspan="2"><input value="<?php echo $o['url_name'];?>" type="text" name="<?php echo $dbo;?>[url_name]"/></td>
                                            <td></td>
                                        </tr>
                                        <tr>    
                                            <td colspan="2"><?php _e('Comment Text Area name',$this->plugin_domain);?></td>
                                            <td colspan="2"><input value="<?php echo $o['comment_name'];?>" type="text" name="<?php echo $dbo;?>[comment_name]"/></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: #dfdfdf; text-align: center; font-weight: bolder;" colspan="5"><?php _e('Extras',$pd);?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <select name="<?php echo $dbo;?>[hide_link_no_url]">
                                                    <option value="nothing" <?php selected($o['hide_link_no_url'],'nothing',true);?>><?php _e('Nothing',$this->plugin_domain);?></option>
                                                    <option value="on" <?php selected($o['hide_link_no_url'],'on',true);?>><?php _e('Hide Link',$this->plugin_domain);?></option>
                                                    <option value="spam" <?php selected($o['hide_link_no_url'],'spam',true);?>><?php _e('Spam Comment',$this->plugin_domain);?></option>
                                                    <option value="delete" <?php selected($o['hide_link_no_url'],'delete',true);?>><?php _e('Delete Comment',$this->plugin_domain);?></option>
                                                </select>
                                                <br/><label for="<?php echo $dbo;?>[hide_link_no_url]"><?php _e('Action to take if comment has no Author URL',$this->plugin_domain);?></label>
                                                <br /><strong>(<?php _e('Prevents spammer abuse',$this->plugin_domain);?>)</strong>

                                            </td>

                                            <td>
                                                <select name="<?php echo $dbo;?>[hide_link_no_url_match]">
                                                    <option value="nothing" <?php selected($o['hide_link_no_url_match'],'nothing',true);?>><?php _e('Nothing',$this->plugin_domain);?></option>
                                                    <option value="on" <?php selected($o['hide_link_no_url_match'],'on',true);?>><?php _e('Hide Link',$this->plugin_domain);?></option>
                                                    <option value="spam" <?php selected($o['hide_link_no_url_match'],'spam',true);?>><?php _e('Spam Comment',$this->plugin_domain);?></option>
                                                    <option value="delete" <?php selected($o['hide_link_no_url_match'],'delete',true);?>><?php _e('Delete Comment',$this->plugin_domain);?></option>
                                                </select>
                                                <br/><label for="<?php echo $dbo;?>[hide_link_no_url_match]"><?php _e('Action to take if link does not match domain of author',$this->plugin_domain);?></label>
                                                <br /><strong>(<?php _e('Prevents users from adding fake author URLs to get around Akismet',$this->plugin_domain);?>)</strong>

                                            </td>

                                            <td>
                                                <input type="checkbox" name="<?php echo $dbo;?>[allow_jpc]" <?php if(isset($o['allow_jpc'])) checked($o['allow_jpc'],'on');?> value="on"/><label for="<?php echo $dbo;?>[allow_jpc]"> <?php _e('Allow Jetpack comments module to activate?',$pd);?></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: #dfdfdf; text-align: center; font-weight: bolder;" colspan="5"><?php _e('Diagnostics Info',$pd);?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"><textarea style="width: 99%" rows="5">
                                                    <?php 
                                                        $options = $this->get_options();
                                                        $options['version'] = $this->version;
                                                        $options['home_url'] = get_home_url();
                                                        $options['wp_version'] = get_bloginfo('version');
                                                        $options['charset'] = get_bloginfo('charset');
                                                        $options['curl'] = function_exists('curl_init')? 'yes': 'no';

                                                        print_r($options);

                                                    ?>
                                                </textarea>
                                            </td>
                                            <td>
                                                <?php _e('You can copy this information and send it to me if I request it',$pd);?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                            <div class="submit"><input class="button-primary" id="clsubmit" type="submit" name="Submit" value="<?php _e('Save Settings',$this->plugin_domain);?>" /></div>
                        </form>
                        <h3><?php _e('Reset Settings',$this->plugin_domain);?></h3>
                        <form method="post" action="options.php">
                            <?php settings_fields( 'commentluv_options_group' ); // the name given in admin init
                                $javamsg =  __('Are you sure you want to reset your settings? Press OK to continue',$this->plugin_domain);
                            ?>
                            <input type="hidden" name="<?php echo $this->db_option;?>[reset]" value="yes"/>
                            <input style="background-color: red;" type="submit" onclick="<?php echo 'if(confirm(\''.$javamsg.'\') != true) { return false; } else { return true; } ';?>" value="<?php _e('Reset',$this->plugin_domain);?>" name="submit"/>    
                        </form>


                    </div> <!-- end main block div -->
                    <div style="float:left">
                        <table class="widefat" style="width: 230px; margin-left: 10px;">
                            <thead>
                                <tr><th scope="col"><?php _e('Plugin Info',$this->plugin_domain);?></th><th>&nbsp;</th></tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="2"><div style="background: url(<?php echo $this->plugin_url;?>images/playbutton.png); text-align: center; font-size: 1.4em; width: 228px; height: 44px; overflow: hidden;"><br><?php _e('Start Here',$this->plugin_domain);?></div><div><a onclick="return false;" href="<?php echo $this->plugin_url . 'videos/'; ?>starthere.php?KeepThis=true&amp;TB_iframe=true&amp;height=355&width=545" class="thickbox"><img src="<?php echo $this->plugin_url;?>images/playbuttonfront.png"/></a></div></td></tr>
                                <tr><td><strong><?php _e('Author',$this->plugin_domain);?>:</strong></td><td>Andy Bailey</td></tr>
                                <tr><td><strong><?php _e('Home Page',$this->plugin_domain);?>:</strong></td><td><a title="<?php _e('Visit www.commentluv.com!',$this->plugin_domain);?>" href="http://www.commentluv.com/" target="_blank">commentluv.com</a></td></tr>
                                <tr><td><strong><?php _e('Social',$this->plugin_domain);?>:</strong></td><td><a title="Follow CommentLuv on Twitter" href="http://twitter.com/commentluv/" target="_blank"><img src="<?php echo $this->plugin_url;?>images/twitter.png"/></a> <a title="Join me on LinkedIn" href="http://uk.linkedin.com/in/commentluv" target="_blank"><img src="<?php echo $this->plugin_url;?>images/linkedin.png"/></a> <a title="Join me on Facebook" href="http://www.facebook.com/CommentLuv" target="_blank"><img src="<?php echo $this->plugin_url;?>images/facebook.png"/></a></td></tr>
                                <tr><td><strong><?php _e('Help',$this->plugin_domain);?>:</strong></td><td><a href="http://support.commentluv.com/" target="_blank"><?php _e('Help Desk',$this->plugin_domain);?></a></td></tr>
                                <tr class="alt"><td colspan="2"><?php _e('Do you like this plugin?',$this->plugin_domain);?></td></tr>
                                <tr><td colspan="2"><iframe src="http://www.facebook.com/plugins/like.php?app_id=156518444414150&amp;href=www.facebook.com%2Fcommentluv&amp;send=false&amp;layout=standard&amp;width=230&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=50" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:220px; height:50px;" allowTransparency="true"></iframe></td></tr>
                                <tr class="alt"><td colspan="2"><?php _e('News',$this->plugin_domain);?>:</td></tr>
                                <tr><td colspan="2">
                                        <?php
                                            include_once(ABSPATH . WPINC . '/feed.php');
                                            if(function_exists('fetch_feed')){
                                                //debugBreak();
                                                $uri = 'http://comluv.com/category/newsletter/feed/';
                                                $feed = fetch_feed($uri);
                                                if(!is_wp_error($feed)){
                                                    $rss_items = $feed->get_items(0,3);
                                                    if($rss_items){
                                                        foreach($rss_items as $item){
                                                        ?>
                                                        <ul>
                                                            <li>
                                                                <a href='<?php echo esc_url( $item->get_permalink() ); ?>'
                                                                    title='<?php echo 'Posted '.$item->get_date('j F Y | g:i a'); ?>'>
                                                                <?php echo esc_html( $item->get_title() ); ?></a>
                                                            </li>
                                                        </ul>
                                                        <?php
                                                        }
                                                    }
                                                }
                                            }

                                        ?>
                                    </td></tr>
                                <tr class="alt"><td colspan="2"><?php _e('Thanks to the following for translations',$this->plugin_domain);?>:</td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/it.png"/> <?php _e('Italian',$this->plugin_domain);?></td><td><a target="_blank" href="http://gidibao.net/">Gianni Diuno</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/nl.png"/> <?php _e('Dutch',$this->plugin_domain);?></td><td><a target="_blank" href="http://wpwebshop.com/">Rene</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/pl.png"/> <?php _e('Polish',$this->plugin_domain);?></td><td><a target="_blank" href="http://techformator.pl/">Mariusz Kolacz</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/ge.png"/> <?php _e('Georgian',$this->plugin_domain);?></td><td><a target="_blank" href="http://www.findmyhosting.com">Kasia Ciszewski</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/lt.png"/> <?php _e('Lithuanian',$this->plugin_domain);?></td><td><a target="_blank" href="http://mantas.malcius.lt/">Mantas Malcius</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/br.png"/> <?php _e('Portuguese',$this->plugin_domain);?></td><td><a target="_blank" href="http://www.korvo.com.br/">Diego Uczak</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/my.png"/> <?php _e('Malaysian',$this->plugin_domain);?></td><td><a target="_blank" href="http://ariffshah.com/">Ariff Shah</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/in.png"/> <?php _e('Hindi',$this->plugin_domain);?></td><td><a target="_blank" href="http://outshinesolutions.com/">Outshine Solutions</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/id.png"/> <?php _e('Indonesian',$this->plugin_domain);?></td><td><a target="_blank" href="http://rainerflame.com/">Mokhamad Oky</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/cn.png"/> <?php _e('Chinese (s)',$this->plugin_domain);?></td><td><a target="_blank" href="http://obugs.net/">Third Eye</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/es.png"/> <?php _e('Spanish',$this->plugin_domain);?></td><td><a target="_blank" href="http://www.activosenred.com/">Valentin Yonte</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/de.png"/> <?php _e('German',$this->plugin_domain);?></td><td><a target="_blank" href="http://www.cloudliving.de/">Jan Ruehling</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/ir.png"/> <?php _e('Persian',$this->plugin_domain);?></td><td><a target="_blank" href="http://www.3eo.ir/">Amir heydari</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/ta.png"/> <?php _e('Tamil',$this->plugin_domain);?></td><td><a target="_blank" href="http://technostreak.com/">Tharun</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/ua.png"/> <?php _e('Ukranian',$this->plugin_domain);?></td><td><a target="_blank" href="http://www.designcontest.com/">Alyona Lompar</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/lv.png"/> <?php _e('Latvian',$this->plugin_domain);?></td><td><a target="_blank" href="http://www.yourwebagency.co.uk/">Edgars Bergs</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/ro.png"/> <?php _e('Romanian',$this->plugin_domain);?></td><td><a target="_blank" href="http://obisnuit.eu/">Manuel Cheta</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/no.png"/> <?php _e('Norwegian',$this->plugin_domain);?></td><td><a target="_blank" href="http://www.drommeland.com/">Hanna</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/fr.png"/> <?php _e('French',$this->plugin_domain);?></td><td><a target="_blank" href="http://etreheureux.fr/">Jean-Luc Matthys</a></td></tr>  
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/dk.png"/> <?php _e('Danish',$this->plugin_domain);?></td><td><a target="_blank" href="http://w3blog.dk/">Jimmy Sigenstroem</a></td></tr>  
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/ru.png"/> <?php _e('Russian',$this->plugin_domain);?></td><td><a target="_blank" href="http://lavo4nik.ru/">Max</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/bd.png"/> <?php _e('Bengali',$this->plugin_domain);?></td><td><a target="_blank" href="http://www.monetizeblogging.com/">Amrik Virdi</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/il.png"/> <?php _e('Hebrew',$this->plugin_domain);?></td><td><a target="_blank" href="http://makemoneyim.com/">Tobi</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/vn.png"/> <?php _e('Vietnamese',$this->plugin_domain);?></td><td><a target="_blank" href="http://thegioimanguon.com/">Xman</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/hu.png"/> <?php _e('Hungarian',$this->plugin_domain);?></td><td><a target="_blank" href="http://no1tutorials.net/">Bruno</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/sk.png"/> <?php _e('Slovak',$this->plugin_domain);?></td><td><a target="_blank" href="http://www.brozman.sk/blog">Viliam Brozman</a></td></tr>
                                <tr><td><img src="<?php echo $this->plugin_url;?>images/rs.png"/> <?php _e('Serbian',$this->plugin_domain);?></td><td><a target="_blank" href="http://wpcouponshop.com/">Diana</a></td></tr>


                                <tr><td><img src="<?php echo $this->plugin_url;?>images/sa.png"/> <?php _e('Arabic',$this->plugin_domain);?></td><td><!--<a target="_blank" href="http://www.melzarei.be/">Muhammad Elzarei</a>--></td></tr>
                                <tr><td><strong><?php _e('Want your link here?',$this->plugin_domain);?></strong></td><td><a target="_blank" href="http://support.commentluv.com/ticket/knowledgebase.php?article=1"><?php _e('How To Submit A Translation',$this->plugin_domain);?></a></td></tr>
                                <tr class="alt"><td colspan="2"><?php _e('Special thanks go to the following',$this->plugin_domain);?>:</td></tr>
                                <tr><td><strong><?php _e('CSS Help',$this->plugin_domain);?>:</strong></td><td><a href="http://www.famousbloggers.net" target="_blank">Hesham Zebida</a></td></tr>
                                <tr><td><strong><?php _e('Badge GFX',$this->plugin_domain);?>:</strong></td><td><a href="http://byteful.com/" target="_blank">Byteful Travel</a></td></tr>
                            </tbody>
                        </table>
                    </div>


                </div>
                <div class="clear"></div>
            </div>
            <?php

            }


        } // end class
    } // end if class not exists
    // Let's give commentluv plenty of room to work with
    $mem = abs(intval(@ini_get('memory_limit')));
    if( $mem and $mem < 128 ){
        @ini_set('memory_limit', '128M');
    }
    $clbadgeshown = false;
    // start commentluv class engines
    if (class_exists ( 'commentluv' )) :
        $commentluv = new commentluv ( );
        // confirm warp capability
        if (isset ( $commentluv )) {
            // engage
            register_activation_hook ( __FILE__, array (&$commentluv, 'install' ) );
        }
        endif;

    function cl_display_badge(){
        global $commentluv;
        if(isset($commentluv)){
            $commentluv->display_badge();
        }

    }
?>