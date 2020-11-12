<div class="row-fluid">
	<div class="span7">
    <div class="box-heading"><h1>Rackspace CDN Service</h1></div>
    <table class="form cdnpanetable">
      <tr>
        <td>Rackspace CDN Service</td>
        <td>
        <select name="Nitro[CDNRackspace][Enabled]" class="NitroCDNRackspace">
            <option value="no" <?php echo (empty($data['Nitro']['CDNRackspace']['Enabled']) || $data['Nitro']['CDNRackspace']['Enabled'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
            <option value="yes" <?php echo( (!empty($data['Nitro']['CDNRackspace']['Enabled']) && $data['Nitro']['CDNRackspace']['Enabled'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
        </select>
        <div class="cdn-error rackspace-error">You cannot use Rackspace CDN with the Generic CDN or Amazon CDN. Please enable only one.</div>
        </td>
      </tr>
    </table>
    <div class="CDNRackspace-tabbable-parent">
    <div class="tabbable tabs-left"> 
          <ul class="nav nav-tabs">
            <li class="active"><a href="#cdn-rackspace-images" data-toggle="tab">Images</a></li>
            <li><a href="#cdn-rackspace-css" data-toggle="tab">CSS</a></li>
            <li><a href="#cdn-rackspace-js" data-toggle="tab">JavaScript</a></li>
          </ul>
         <div class="tab-content">
         	<div id="cdn-rackspace-images" class="tab-pane active">
                <table class="form cdnrackspace" style="margin-top:-10px;">
                  <tr>
                    <td>Images CDN HTTP URL<span class="help">This is the non-SSL URL of your CDN server</span></td>
                    <td>
                        <input type="text" name="Nitro[CDNRackspace][ImagesHttpUrl]" value="<?php echo(!empty($data['Nitro']['CDNRackspace']['ImagesHttpUrl'])) ? $data['Nitro']['CDNRackspace']['ImagesHttpUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Images CDN HTTPS URL<span class="help">This is the SSL URL of your CDN server</span></td>
                    <td>
                        <input type="text" name="Nitro[CDNRackspace][ImagesHttpsUrl]" value="<?php echo(!empty($data['Nitro']['CDNRackspace']['ImagesHttpsUrl'])) ? $data['Nitro']['CDNRackspace']['ImagesHttpsUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Rackspace Images Container<span class="help">This is the Rackspace container of your images</span></td>
                    <td>
                        <input type="text" name="Nitro[CDNRackspace][ImagesContainer]" value="<?php echo(!empty($data['Nitro']['CDNRackspace']['ImagesContainer'])) ? $data['Nitro']['CDNRackspace']['ImagesContainer'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Serve Images from this CDN<span class="help">Your images will be served from your store web server if you have not synced them with the CDN via FTP, while FTP is enabled.</span></td>
                    <td>
                    <select name="Nitro[CDNRackspace][ServeImages]">
                        <option value="no" <?php echo (empty($data['Nitro']['CDNRackspace']['ServeImages']) || $data['Nitro']['CDNRackspace']['ServeImages'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['CDNRackspace']['ServeImages']) && $data['Nitro']['CDNRackspace']['ServeImages'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                </table> 
            </div>
         	<div id="cdn-rackspace-css" class="tab-pane">
                <table class="form cdnrackspace" style="margin-top:-10px;">
                  <tr>
                    <td>CSS CDN HTTP URL<span class="help">This is the non-SSL URL of your CDN server</span></td>
                    <td>
                        <input type="text" name="Nitro[CDNRackspace][CSSHttpUrl]" value="<?php echo(!empty($data['Nitro']['CDNRackspace']['CSSHttpUrl'])) ? $data['Nitro']['CDNRackspace']['CSSHttpUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>CSS CDN HTTPS URL<span class="help">This is the SSL URL of your CDN server</span></td>
                    <td>
                        <input type="text" name="Nitro[CDNRackspace][CSSHttpsUrl]" value="<?php echo(!empty($data['Nitro']['CDNRackspace']['CSSHttpsUrl'])) ? $data['Nitro']['CDNRackspace']['CSSHttpsUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Rackspace CSS Container<span class="help">This is the Rackspace container of your CSS files</span></td>
                    <td>
                        <input type="text" name="Nitro[CDNRackspace][CSSContainer]" value="<?php echo(!empty($data['Nitro']['CDNRackspace']['CSSContainer'])) ? $data['Nitro']['CDNRackspace']['CSSContainer'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Serve CSS from this CDN<span class="help">Note: This will not work if you have not synchronized with FTP and if Minification is enabled. Your CSS files will be served from your store web server if you have not synced them with the CDN via FTP, while FTP is enabled.</span></td>
                    <td>
                    <select name="Nitro[CDNRackspace][ServeCSS]">
                        <option value="no" <?php echo (empty($data['Nitro']['CDNRackspace']['ServeCSS']) || $data['Nitro']['CDNRackspace']['ServeCSS'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['CDNRackspace']['ServeCSS']) && $data['Nitro']['CDNRackspace']['ServeCSS'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                </table> 
            </div>
         	<div id="cdn-rackspace-js" class="tab-pane">
                <table class="form cdnrackspace" style="margin-top:-10px;">
                  <tr>
                    <td>JavaScript CDN HTTP URL<span class="help">This is the non-SSL URL of your CDN server</span></td>
                    <td>
                        <input type="text" name="Nitro[CDNRackspace][JavaScriptHttpUrl]" value="<?php echo(!empty($data['Nitro']['CDNRackspace']['JavaScriptHttpUrl'])) ? $data['Nitro']['CDNRackspace']['JavaScriptHttpUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>JavaScript CDN HTTPS URL<span class="help">This is the SSL URL of your CDN server</span></td>
                    <td>
                        <input type="text" name="Nitro[CDNRackspace][JavaScriptHttpsUrl]" value="<?php echo(!empty($data['Nitro']['CDNRackspace']['JavaScriptHttpsUrl'])) ? $data['Nitro']['CDNRackspace']['JavaScriptHttpsUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Rackspace JavaScript Container<span class="help">This is the Rackspace container of your JavaScript files</span></td>
                    <td>
                        <input type="text" name="Nitro[CDNRackspace][JavaScriptContainer]" value="<?php echo(!empty($data['Nitro']['CDNRackspace']['JavaScriptContainer'])) ? $data['Nitro']['CDNRackspace']['JavaScriptContainer'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Serve JavaScript from this CDN<span class="help">Note: This will not work if you have not synchronized with FTP and if Minification is enabled. Your JavaScript files will be served from your store web server if you have not synced them with the CDN via FTP, while FTP is enabled.</span></td>
                    <td>
                    <select name="Nitro[CDNRackspace][ServeJavaScript]">
                        <option value="no" <?php echo (empty($data['Nitro']['CDNRackspace']['ServeJavaScript']) || $data['Nitro']['CDNRackspace']['ServeJavaScript'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['CDNRackspace']['ServeJavaScript']) && $data['Nitro']['CDNRackspace']['ServeJavaScript'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                </table> 
            </div>
         </div>
    </div>
</div>
    </div>
    <div class="span5">
    	<div class="CDNRackspace-tabbable-parent">
            <div class="box-heading"><h1><i class="icon-globe"></i>Synchronize with Rackspace CDN</h1></div>
            <div class="box-content" style="min-height:100px; line-height:20px;">
            	<div class="rackspace-alert-info alert alert-info"><?php echo !empty($data['Nitro']['CDNRackspace']['LastUpload']) ? 'Last synchronization was on ' . $data['Nitro']['CDNRackspace']['LastUpload'] : 'No synchornization has been carried out yet.'; ?></div>
                <input type="hidden" name="Nitro[CDNRackspace][LastUpload]" value="<?php echo !empty($data['Nitro']['CDNRackspace']['LastUpload']) ? $data['Nitro']['CDNRackspace']['LastUpload'] : ''; ?>" />
            	<div class="rackspace-alert alert alert-error"></div>
                <div class="rackspace-alert alert alert-success"></div>
                <table class="form">
                  <tr>
                    <td>Rackspace Username</td>
                    <td>
                    <input type="text" name="Nitro[CDNRackspace][Username]" value="<?php echo !empty($data['Nitro']['CDNRackspace']['Username']) ? $data['Nitro']['CDNRackspace']['Username'] : ''; ?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>API Key</td>
                    <td>
                    <input type="text" name="Nitro[CDNRackspace][APIKey]" value="<?php echo !empty($data['Nitro']['CDNRackspace']['APIKey']) ? $data['Nitro']['CDNRackspace']['APIKey'] : ''; ?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Server Region</td>
                    <td>
                    	<select name="Nitro[CDNRackspace][ServerRegion]">
                        	<option value="DFW" <?php if (!empty($data['Nitro']['CDNRackspace']['ServerRegion']) && $data['Nitro']['CDNRackspace']['ServerRegion'] == 'DFW') echo 'selected="selected"'; ?>>Dallas (DFW)</option>
                            <option value="ORD"<?php if (!empty($data['Nitro']['CDNRackspace']['ServerRegion']) && $data['Nitro']['CDNRackspace']['ServerRegion'] == 'ORD') echo 'selected="selected"'; ?>>Chicago (ORD)</option>
                            <option value="LON"<?php if (!empty($data['Nitro']['CDNRackspace']['ServerRegion']) && $data['Nitro']['CDNRackspace']['ServerRegion'] == 'LON') echo 'selected="selected"'; ?>>London (LON)</option>
                            <option value="SYD"<?php if (!empty($data['Nitro']['CDNRackspace']['ServerRegion']) && $data['Nitro']['CDNRackspace']['ServerRegion'] == 'SYD') echo 'selected="selected"'; ?>>Sydney (SYD)</option>
                        </select>
                    
                    </td>
                  </tr>
                  <tr>
                  	<td colspan="2">
                    	<label class="rackspace-upload-label"><input type="checkbox" value="1" name="Nitro[CDNRackspace][SyncImages]" <?php if (!empty($data['Nitro']['CDNRackspace']['SyncImages'])) echo 'checked="checked"'; ?>/> Upload all local images to Rackspace CDN</label>
                        <label class="rackspace-upload-label"><input type="checkbox" value="1" name="Nitro[CDNRackspace][SyncCSS]" <?php if (!empty($data['Nitro']['CDNRackspace']['SyncCSS'])) echo 'checked="checked"'; ?>/> Upload all local CSS files to Rackspace CDN</label>
                        <label class="rackspace-upload-label"><input type="checkbox" value="1" name="Nitro[CDNRackspace][SyncJavaScript]" <?php if (!empty($data['Nitro']['CDNRackspace']['SyncJavaScript'])) echo 'checked="checked"'; ?>/> Upload all local JavaScript files to Rackspace CDN</label>
                    </td>
                  </tr>
                  <tr>
                  	<td colspan="2">
                    	<a class="rackspace-upload btn btn-success"><i class="icon-circle-arrow-up icon-white"></i> <span class="rackspace-button-text">Upload to CDN Server</span></a>
                        <a class="rackspace-cancel btn btn-inverse"><i class="icon-remove icon-white"></i> <span class="rackspace-cancel-text">Abort</span></a>
                        <div class="progress active rackspace-progress">
                          <div class="bar-success" style="width: 0%;"></div>
                        </div>
                      
                        <div class="empty-rackspace-div"></div>
                        <div class="rackspace-log">
                        </div>
                    </td>
                  </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.CDNRackspace {
		
}

</style>

<script type="text/javascript">

$(window).load(function() {
	$('.cdn-error').hide();
	var showCDNForm = function() {
		if ($('.NitroCDNRackspace').val() == 'yes') {
			$('.CDNRackspace-tabbable-parent').fadeIn();
		} else {
			$('.CDNRackspace-tabbable-parent').hide();
		}
	}
	
	$('.NitroCDNRackspace').change(function() {
		$('.cdn-error').hide();
		if ($('.NitroCDNAmazon').val() == 'yes' || $('.NitroCDNStandard').val() == 'yes') {
			$('.cdn-error.rackspace-error').fadeIn();
			$('.NitroCDNRackspace').val('no');
		} else {
			$('.cdn-error.rackspace-error').hide();
			showCDNForm();
		}
	}).trigger('change');
	
	showCDNForm();
	
	var refreshAjax;
	var uploadRackspace;
	var interval;
	var rackspaceLog = $('.rackspace-log');
	var rackspace_uploading = false;
	
	var refresh_rackspace_data = function(init) {
		if (typeof refreshAjax != 'undefined') refreshAjax.abort();
		if (typeof init != 'undefined') {
			$('.rackspace-progress .bar-success').css({width: '0%'});
		}
		refreshAjax = $.ajax({
			url: 'index.php?route=tool/nitro/getrackspaceprogress&token=' + getURLVar('token') + (typeof init != 'undefined' ? '&init=true' : ''),
			dataType: 'json',
			cache: false,
			success: function(data) {
				if (data == null) return;
				rackspaceLog.html(data.message);
				$('.rackspace-progress .bar-success').css({height: '30px'}).animate({width: data.percent + '%'}, 100);
				rackspaceLog.css({width: $('.empty-rackspace-div').width() + 'px'});
			}
		});
	}
	
	$('.rackspace-upload').click(function() {
		if (rackspace_uploading) return;
		var button = $(this);
		refresh_rackspace_data(true);
		uploadRackspace = $.ajax({
			url: 'index.php?route=tool/nitro/saverackspace&token=' + getURLVar('token'),
			type: 'POST',
			cache: false,
			dataType: 'json',
			data: {
				username: $('input[name="Nitro[CDNRackspace][Username]"]').val(),
				apiKey: $('input[name="Nitro[CDNRackspace][APIKey]"]').val(),
				serverRegion: $('select[name="Nitro[CDNRackspace][ServerRegion]"]').val(),
				syncImages: $('input[name="Nitro[CDNRackspace][SyncImages]"]').is(':checked'),
				syncCSS: $('input[name="Nitro[CDNRackspace][SyncCSS]"]').is(':checked'),
				syncJavaScript: $('input[name="Nitro[CDNRackspace][SyncJavaScript]"]').is(':checked')
			},
			beforeSend: function() {
				button.attr('disabled','disabled');
				$('.rackspace-button-text').text('Uploading...');
				$('.rackspace-cancel-text').removeAttr('disabled');
				rackspaceLog.empty();
				$('.rackspace-alert').hide().empty();
				interval = setInterval(refresh_rackspace_data, 1000);
				rackspace_uploading = true;
			},
			success: function(data) {
				if (typeof data.success != 'undefined') {
					$('.rackspace-alert.alert-success').html(data.success).slideDown();
				}
				if (typeof data.error != 'undefined') {
					$('.rackspace-alert.alert-error').html(data.error).slideDown();
				}
				if (typeof data.upload_time != 'undefined') {
					$('.rackspace-alert-info.alert-info').html('Last synchronization was on ' + data.upload_time);
					$('input[name="Nitro[CDNRackspace][LastUpload]"]').val(data.upload_time);
				}
			},
			complete: function() {
				button.removeAttr('disabled');
				$('.rackspace-cancel-text').attr('disabled','disabled');
				$('.rackspace-button-text').text('Upload to CDN Server');
				clearInterval(interval);
				refresh_rackspace_data();
				rackspace_uploading = false;
			}
		});
	});
	
	$('.rackspace-cancel').click(function() {
		if (!rackspace_uploading) return;
		$.ajax({
			url: 'index.php?route=tool/nitro/cancelrackspace&token=' + getURLVar('token'),
			cache: false
		});
	});
	
	$('.rackspace-cancel-text').attr('disabled','disabled');
});

</script>