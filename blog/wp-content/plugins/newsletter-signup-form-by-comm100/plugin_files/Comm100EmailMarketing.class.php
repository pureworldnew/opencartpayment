<?php

if(!function_exists('_log')){
  function _log( $message ) {
    if( WP_DEBUG === true ){
      if( is_array( $message ) || is_object( $message ) ){
        error_log( print_r( $message, true ) );
      } else {
        error_log( $message );
      }
    }
  }
}


class Comm100EmailMarketing
{
	// singleton pattern
	protected static $instance;
	public static $service_url = 'https://hosted.comm100.com/AdminPluginService/emailmarketingplugin.ashx';
	//public static $service_url = 'http://192.168.8.48/plugin/emailmarketingplugin.ashx';

	/**
	 * Absolute path to plugin files
	 */
	protected $plugin_url = null;
	protected $site_id = null;
    protected $email = null;
	protected $plan_id = null;

	/**
	 * Starts the plugin
	 */
	protected function __construct()
	{
		add_action('widgets_init', create_function('', 'register_widget("Comm100EmailMarketingWidget");'));
	}

	public static function get_instance()
	{
		if (!isset(self::$instance))
		{
			$c = __CLASS__;
			self::$instance = new $c;
		}

		return self::$instance;
	}

	/** 
	 * Returns plugin files absolute path
	 *
	 * @return string
	 */
	public function get_plugin_url()
	{
		if (is_null($this->plugin_url))
		{
			$this->plugin_url = WP_PLUGIN_URL.'/newsletter-signup-form-by-comm100/plugin_files';
		}

		return $this->plugin_url;
	}

	public function is_installed()
	{
		return $this->get_site_id() > 0;
	}

	public function get_site_id()
	{
		if (is_null($this->site_id))
		{
			$this->site_id = get_option('comm100emailmarketing_site_id');
		}

		// siteId must be >= 0
		// also, this prevents from NaN values
		$this->site_id = max(0, $this->site_id);

		return $this->site_id;
	}
	public function get_email()
	{
		if (is_null($this->email))
		{
			$this->email = get_option('comm100emailmarketing_email');
		}

		return $this->email;
	}
}

class Comm100EmailMarketingWidget extends WP_Widget
{
	public function __construct() {
		parent::__construct('comm100emailmarketing_widget', 'Comm100 Email Marketing', 
			array('description' => __('Add an embedded subscription form to your site. Your visitors can fill out the form to sign up to your newsletters.', 'text_domain'), 
				'classname' => 'comm100-setting-widget'), array('width' => 310));
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['code'] = $new_instance['code'];
		$instance['reset'] = $new_instance['reset'];
		
		return $instance;
	}

	public function form($instance)
	{
		$site_id = Comm100EmailMarketing::get_instance()->get_site_id();
		$code = isset($instance['code']) ? $instance['code'] : '';
		$reset = isset($instance['reset']) ? $instance['reset'] : '1';
		$title = (isset($instance['title']) && $instance['title'] != '' && $reset == '0') ? $instance['title'] : 'Subscribe';

		
		$base = Comm100EmailMarketing::get_instance()->get_plugin_url();

		if (!Comm100EmailMarketing::get_instance()->is_installed()) {
?>
			<div>Comm100 Email Marketing is not added to your site yet as you haven't linked up any Comm100 account.<br/><a href="admin.php?page=comm100emailmarketing_settings">Link Up your account now</a>.</div>
			<input type="hidden" id="<?php echo $this->get_field_id( 'reset' ); ?>" name="<?php echo $this->get_field_name( 'reset' ); ?>" value="0" />	
<?php
			return;
		}

?>

		<style type="text/css">
			.comm100-setting-item {
				padding-bottom: 20px;
			}
			.comm100-setting-content {
				padding-left: 5px;
				max-height: 300px;
				overflow: auto;
			}
			.comm100-setting-content a {
				text-decoration: none;
			}
			.comm100-setting-title {
				font-size: 11px;
				padding-bottom: 5px;
			}
			.comm100-setting-title input {
			 	vertical-align: middle !important;
			}
			.comm100-setting-title-label {
				padding-right:5px;
				font-size:1.2em;
				font-weight: bold;
			}
			.comm100-setting-control {
				width:250px;
			}
			.comm100-seeting-small-control {
				width:90px;

			}
			.comm100-setting-fields {
				margin:0;
				padding:0;
			}
			.comm100-setting-fields td {
				padding: 5px;
				padding-left:0;
			}
			.comm100-setting-fields tr td {
				text-align: center;
				vertical-align: middle;
			}
			.comm100-setting-fields thead {
				font-weight: bold;
				font-size: 12px;
			}
			.comm100-setting-add-field {
				padding-left:5px;
				text-decoration: underline;
			}
			.comm100-icon {
				width: 16px;
				height: 16px;
				display: inline-block;
				cursor: pointer;
			}
			.comm100-icon-delete {
				background: url('<?php echo $base?>/images/delete.gif') no-repeat;
			}
		</style>
		<script type="text/javascript" src="<?php echo $base ?>/js/plugin.js"></script>
<?php
		if (isset($instance['code']) && $reset == '0') {
?>
		<div class="comm100-setting-item">
			<div class="comm100-setting-title">
				<span class="comm100-setting-title-label">Title:</span>
			</div>
			<div class="comm100-setting-content">
				<input class="comm100-setting-control" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title?>" />				
			</div>
		</div>


		<div class="comm100-setting-item">
			<div class="comm100-setting-title">
				<span class="comm100-setting-title-label">Form code:</span>
			</div>
			<div class="comm100-setting-content">
				<textarea style="width:260px;height:150px;" id="<?php echo $this->get_field_id( 'code' ); ?>" name="<?php echo $this->get_field_name( 'code' ); ?>"><?php echo $code?></textarea>	
			</div>
				<input type="hidden" id="<?php echo $this->get_field_id( 'reset' ); ?>" name="<?php echo $this->get_field_name( 'reset' ); ?>" value="0" />	
		</div>
		<div style="padding-top:20px;">
			<input type="button" class="button-primary" value="Preview" onclick="comm100_preview_html(this.parentNode.parentNode);">
			<input type="button" class="button-primary" name="reset" value="Reset" onclick="comm100_reset(this.parentNode.parentNode);">
		</div>
		<script type="text/javascript">
			function comm100_preview_html(form_box) {
				var code = jQuery('#<?php echo $this->get_field_id( 'code' ); ?>').val();

				var pw=window.open('about:blank','','left=200,top=200,statusbar=no,width=300,height=300');
				pw.focus();

				pw.document.open();
				pw.document.write('<html><body>' + code + '</body></html>');
				pw.document.close();
			}
			function comm100_reset(form_box) {
				if (confirm('After you reset the settings, the customization you previously made to the Subscription Form will be discarded. All the settings will be restored to the default settings. Are you sure you want to continue?')) {
									
					jQuery('#<?php echo $this->get_field_id( 'reset' ); ?>').val('1');

					jQuery('#widget-<?php echo $this->id ?>-savewidget').click();
				}
			}
		</script>
<?php
		} else {
?>
		<div id="<?php echo $this->get_field_id('form')?>">
			<div class="comm100-setting-item">
				<div class="comm100-setting-title">
					<span class="comm100-setting-title-label">Title:</span>
				</div>
				<div class="comm100-setting-content">
					<input class="comm100-setting-control" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title?>" />				
				</div>
			</div>

			<div class="comm100-setting-item">
				<div class="comm100-setting-title">
					<span class="comm100-setting-title-label">Subscribe button text:</span>
				</div>
				<div class="comm100-setting-content">
					<input class="comm100-setting-control comm100-subscribe-button-text" value="Subscribe" />
				</div>
			</div>
			<div class="comm100-setting-item">
				<div class="comm100-setting-title">
					<span class="comm100-setting-title-label">Mailling List:</span>
				</div>
				<div class="comm100-setting-content comm100-mailling-list">
				</div>
			</div>


			<div class="comm100-setting-item">
				<div class="comm100-setting-title">
					<span class="comm100-setting-title-label">Fields:</span>
				</div>
				<div class="comm100-setting-content comm100-fields">
					<table class="comm100-setting-fields comm100-mailling-list-select">
						<thead>
							<tr><td>Fields</td><td>Text</td><td>Visible</td><td>Required</td></tr>
						</thead>
						<tbody>
							<tr>
								<td>Email<input value="0" type="hidden" class="comm100-email-id"></td>									
								<td><input value="" class="comm100-seeting-small-control comm100-email-text"></td>									
								<td><input type="checkbox" disabled="disabled" checked="checked" class="comm100-email-visible"></td>
								<td><input type="checkbox" disabled="disabled" checked="checked " class="comm100-email-required"></td>
							</tr>

							<tr><td>First Name<input value="1" type="hidden" class="comm100-first-name-id"></td>
								<td><input value="" class="comm100-seeting-small-control comm100-first-name-text"></td>
								<td><input type="checkbox" checked="checked" class="comm100-first-name-visible"></td>
								<td><input type="checkbox" class="comm100-first-name-required"></td>
							</tr>

							<tr><td>Last Name<input value="2" type="hidden" class="comm100-last-name-id"></td>
								<td><input value="" class="comm100-seeting-small-control comm100-last-name-text"></td>
								<td><input type="checkbox" checked="checked" class="comm100-last-name-visible"></td>
								<td><input type="checkbox" class="comm100-last-name-required"></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div> 
			<div class="comm100-setting-item">
				<div class="comm100-setting-title">
					<span class="comm100-setting-title-label">Show Comm100 Link:</span>
					<input type="checkbox" checked="checked" class="comm100-poweredby"/>
				</div>
			</div>

			<input type="hidden" id="<?php echo $this->get_field_id( 'reset' ); ?>" name="<?php echo $this->get_field_name( 'reset' ); ?>" value="0" />
			<input type="hidden" class="comm100-form-code" id="<?php echo $this->get_field_id( 'code' ); ?>" name="<?php echo $this->get_field_name( 'code' ); ?>"></textarea>

			<script type="text/javascript">
				var comm100_site_id = <?php echo $site_id?>;
				setTimeout(function() {

					comm100_plugin.get_mailling_lists(comm100_site_id, function(list) {

						var html = '<select class="comm100-setting-control comm100-mailling-list-select">';
						for (var i = 0, len = list.length; i < len; i++) {
							var item = list[i];
							html += '<option value="' + item.id + '">' + item.name + '</option>'
						}
						html += '</select>';

						jQuery('#<?php echo $this->get_field_id('form')?> .comm100-mailling-list').html(html);


						comm100_plugin.get_fields(comm100_site_id, function(fields) {

							var html = '<table class="comm100-setting-fields comm100-mailling-list-select">\
						<thead>\
							<tr><td>Fields</td><td>Text</td><td>Visible</td><td>Required</td></tr>\
						</thead>\
						<tbody>\
							<tr>\
								<td style="text-align:right;">Email<input value="' + fields.email_id + '" type="hidden" class="comm100-email-id"></td>\
								<td><input value="' + fields.email_text + '" class="comm100-seeting-small-control comm100-email-text"></td>\
								<td><input type="checkbox" disabled="disabled" checked="checked" class="comm100-email-visible"></td>\
								<td><input type="checkbox" disabled="disabled" checked="checked " class="comm100-email-required"></td>\
							</tr>\
							<tr><td style="text-align:right;">First Name<input value="' + fields.first_name_id + '" type="hidden" class="comm100-first-name-id"></td>\
								<td><input value="' + fields.first_name_text + '" class="comm100-seeting-small-control comm100-first-name-text"></td>\
								<td><input type="checkbox" checked="checked" class="comm100-first-name-visible"></td>\
								<td><input type="checkbox" class="comm100-first-name-required"></td>\
							</tr>\
							<tr><td style="text-align:right;">Last Name<input value="' + fields.last_name_id + '" type="hidden" class="comm100-last-name-id"></td>\
								<td><input value="' + fields.last_name_text + '" class="comm100-seeting-small-control comm100-last-name-text"></td>\
								<td><input type="checkbox" checked="checked" class="comm100-last-name-visible"></td>\
								<td><input type="checkbox" class="comm100-last-name-required"></td>\
							</tr>\
						</tbody>\
					</table>';

							var set_html = function(){
								if (jQuery('#<?php echo $this->get_field_id('form')?> .comm100-fields').is(':visible') == false) {
									setTimeout(set_html, 100);
								}
								jQuery('#<?php echo $this->get_field_id('form')?> .comm100-fields').html(html);
							};
							setTimeout(set_html, 100);
						});

					});
				}, 100);


				function comm100_preview(form_box) {
					var w = window.open('http://hosted.comm100.com/AdminPluginService/emailmarketingplugin.ashx?action=preview' + comm100_form_options(form_box),'','left=200,top=200,statusbar=no,width=300,height=300');
					w.focus();
				}
				function comm100_form_options(form_box) {
					var params = '&button_text=' + encodeURIComponent(jQuery('.comm100-subscribe-button-text', form_box).val());
					params += '&mailling_list=' + encodeURIComponent(jQuery('.comm100-mailling-list-select', form_box).val());
					params += '&show_poweredby=' + encodeURIComponent(jQuery('.comm100-poweredby', form_box).is(':checked'));
					
					params += '&email_id=' + encodeURIComponent(jQuery('.comm100-email-id', form_box).val());
					params += '&email_text=' + encodeURIComponent(jQuery('.comm100-email-text', form_box).val());

					params += '&first_name_id=' + encodeURIComponent(jQuery('.comm100-first-name-id', form_box).val());
					params += '&first_name_text=' + encodeURIComponent(jQuery('.comm100-first-name-text', form_box).val());
					params += '&first_name_visible=' + jQuery('.comm100-first-name-visible', form_box).is(':checked');
					params += '&first_name_required=' + jQuery('.comm100-first-name-required', form_box).is(':checked');

					params += '&last_name_id=' + encodeURIComponent(jQuery('.comm100-last-name-id', form_box).val());
					params += '&last_name_text=' + encodeURIComponent(jQuery('.comm100-last-name-text', form_box).val());
					params += '&last_name_visible=' + jQuery('.comm100-last-name-visible', form_box).is(':checked');
					params += '&last_name_required=' + jQuery('.comm100-last-name-required', form_box).is(':checked');
					return params;
				}
	/*			var fn_save;
				jQuery( ".widgets-sortables" ).bind( "sortstop", function(event, ui) {
					if (!fn_save) {
						fn_save = wpWidgets.save;

						wpWidgets.save = function(arg0, arg1, arg2, arg3, arg4, arg5) {
							if (!arg1 && arg0.attr('id').indexOf('emailmarketing') >= 0) {

								comm100_plugin.get_code(comm100_site_id, comm100_form_options(arg0), function(code) {
									jQuery('.comm100-form-code', arg0).val(html_encode(code));
									fn_save(arg0, arg1, arg2, arg3, arg4, arg5);
								});
							} else {
								fn_save(arg0, arg1, arg2, arg3, arg4, arg5);
							}
						};
					}			
				});*/
				//a("input.widget-control-save").live("click",function(){wpWidgets.save(a(this).closest("div.widget"),0,1,0);return false});
			</script>
			<div style="padding-left:5px;">
				<input type="button" class="button-primary" value="Preview" onclick="comm100_preview(jQuery(this.parentNode.parentNode));">
			</div>
		</div>
<?php
		}
	}

	public function widget($args, $instance)
	{
		if (Comm100EmailMarketing::get_instance()->is_installed()) {

		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$code = $instance['code'];

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;

		$base = Comm100EmailMarketing::get_instance()->get_plugin_url();

		echo html_entity_decode($code);
?>
		
<?php		


		echo $after_widget;
		}		
	}
}