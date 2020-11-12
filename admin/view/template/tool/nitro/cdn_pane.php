<div class="row-fluid">
	<div class="span7">
    <div class="box-heading"><h1>Generic CDN Service</h1></div>
    <table class="form cdnpanetable">
      <tr>
        <td>CDN Service</td>
        <td>
        <select name="Nitro[CDNStandard][Enabled]" class="NitroCDNStandard">
            <option value="no" <?php echo (empty($data['Nitro']['CDNStandard']['Enabled']) || $data['Nitro']['CDNStandard']['Enabled'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
            <option value="yes" <?php echo( (!empty($data['Nitro']['CDNStandard']['Enabled']) && $data['Nitro']['CDNStandard']['Enabled'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
        </select>
        <div class="cdn-error cdn-generic-error">You cannot use the Generic CDN with the Amazon CDN or Rackspace CDN. Please enable only one.</div>
        </td>
      </tr>
      <tr class="cdnstandard-tabbable-parent">
        <td>Synchronize/Push via FTP<span class="help">This will upload your Images, CSS and JavaScript files to the specified FTP. If you have enabled this, then all of your CDN URL's should point to this FTP.</span></td>
        <td>
        <select name="Nitro[CDNStandardFTP][Enabled]" class="NitroCDNStandardFTP">
            <option value="no" <?php echo (empty($data['Nitro']['CDNStandardFTP']['Enabled']) || $data['Nitro']['CDNStandardFTP']['Enabled'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
            <option value="yes" <?php echo( (!empty($data['Nitro']['CDNStandardFTP']['Enabled']) && $data['Nitro']['CDNStandardFTP']['Enabled'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
        </select>
        </td>
      </tr>
    </table>
    <div class="cdnstandard-tabbable-parent">
    <div class="tabbable tabs-left"> 
          <ul class="nav nav-tabs">
            <li class="active"><a href="#cdn-standard-images" data-toggle="tab">Images</a></li>
            <li><a href="#cdn-standard-css" data-toggle="tab">CSS</a></li>
            <li><a href="#cdn-standard-js" data-toggle="tab">JavaScript</a></li>
            <!--<li><a href="#cdn-standard-downloads" data-toggle="tab">Downloads</a></li>-->
          </ul>
         <div class="tab-content">
         	<div id="cdn-standard-images" class="tab-pane active">
                <table class="form cdnstandard" style="margin-top:-10px;">
                  <tr>
                    <td>Images CDN HTTP URL<span class="help">This is the non-SSL URL of your CDN server</span></td>
                    <td>
                        <input type="text" name="Nitro[CDNStandard][ImagesHttpUrl]" value="<?php echo(!empty($data['Nitro']['CDNStandard']['ImagesHttpUrl'])) ? $data['Nitro']['CDNStandard']['ImagesHttpUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Images CDN HTTPS URL<span class="help">This is the SSL URL of your CDN server</span></td>
                    <td>
                        <input type="text" name="Nitro[CDNStandard][ImagesHttpsUrl]" value="<?php echo(!empty($data['Nitro']['CDNStandard']['ImagesHttpsUrl'])) ? $data['Nitro']['CDNStandard']['ImagesHttpsUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Serve Images from this CDN<span class="help">Your images will be served from your store web server if you have not synced them with the CDN via FTP, while FTP is enabled.</span></td>
                    <td>
                    <select name="Nitro[CDNStandard][ServeImages]">
                        <option value="no" <?php echo (empty($data['Nitro']['CDNStandard']['ServeImages']) || $data['Nitro']['CDNStandard']['ServeImages'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['CDNStandard']['ServeImages']) && $data['Nitro']['CDNStandard']['ServeImages'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                </table> 
            </div>
         	<div id="cdn-standard-css" class="tab-pane">
                <table class="form cdnstandard" style="margin-top:-10px;">
                  <tr>
                    <td>CSS CDN HTTP URL<span class="help">This is the non-SSL URL of your CDN server</span></td>
                    <td>
                        <input type="text" name="Nitro[CDNStandard][CSSHttpUrl]" value="<?php echo(!empty($data['Nitro']['CDNStandard']['CSSHttpUrl'])) ? $data['Nitro']['CDNStandard']['CSSHttpUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>CSS CDN HTTPS URL<span class="help">This is the SSL URL of your CDN server</span></td>
                    <td>
                        <input type="text" name="Nitro[CDNStandard][CSSHttpsUrl]" value="<?php echo(!empty($data['Nitro']['CDNStandard']['CSSHttpsUrl'])) ? $data['Nitro']['CDNStandard']['CSSHttpsUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Serve CSS from this CDN<span class="help">Note: This will not work if you have not synchronized with FTP and if Minification is enabled. Your CSS files will be served from your store web server if you have not synced them with the CDN via FTP, while FTP is enabled.</span></td>
                    <td>
                    <select name="Nitro[CDNStandard][ServeCSS]">
                        <option value="no" <?php echo (empty($data['Nitro']['CDNStandard']['ServeCSS']) || $data['Nitro']['CDNStandard']['ServeCSS'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['CDNStandard']['ServeCSS']) && $data['Nitro']['CDNStandard']['ServeCSS'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                </table> 
            </div>
         	<div id="cdn-standard-js" class="tab-pane">
                <table class="form cdnstandard" style="margin-top:-10px;">
                  <tr>
                    <td>JavaScript CDN HTTP URL<span class="help">This is the non-SSL URL of your CDN server</span></td>
                    <td>
                        <input type="text" name="Nitro[CDNStandard][JavaScriptHttpUrl]" value="<?php echo(!empty($data['Nitro']['CDNStandard']['JavaScriptHttpUrl'])) ? $data['Nitro']['CDNStandard']['JavaScriptHttpUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>JavaScript CDN HTTPS URL<span class="help">This is the SSL URL of your CDN server</span></td>
                    <td>
                        <input type="text" name="Nitro[CDNStandard][JavaScriptHttpsUrl]" value="<?php echo(!empty($data['Nitro']['CDNStandard']['JavaScriptHttpsUrl'])) ? $data['Nitro']['CDNStandard']['JavaScriptHttpsUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Serve JavaScript from this CDN<span class="help">Note: This will not work if you have not synchronized with FTP and if Minification is enabled. Your JavaScript files will be served from your store web server if you have not synced them with the CDN via FTP, while FTP is enabled.</span></td>
                    <td>
                    <select name="Nitro[CDNStandard][ServeJavaScript]">
                        <option value="no" <?php echo (empty($data['Nitro']['CDNStandard']['ServeJavaScript']) || $data['Nitro']['CDNStandard']['ServeJavaScript'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['CDNStandard']['ServeJavaScript']) && $data['Nitro']['CDNStandard']['ServeJavaScript'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                </table> 
            </div>
            <!--
         	<div id="cdn-standard-downloads" class="tab-pane">
                <table class="form cdnstandard" style="margin-top:-10px;">
                  <tr>
                    <td>Downloads CDN HTTP URL<span class="help">This is the non-SSL URL of your CDN server</span></td>
                    <td>
                        <input type="text" name="Nitro[CDNStandard][DownloadsHttpUrl]" value="<?php echo(!empty($data['Nitro']['CDNStandard']['DownloadsHttpUrl'])) ? $data['Nitro']['CDNStandard']['DownloadsHttpUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Downloads CDN HTTPS URL<span class="help">This is the SSL URL of your CDN server</span></td>
                    <td>
                        <input type="text" name="Nitro[CDNStandard][DownloadsHttpsUrl]" value="<?php echo(!empty($data['Nitro']['CDNStandard']['DownloadsHttpsUrl'])) ? $data['Nitro']['CDNStandard']['DownloadsHttpsUrl'] : ''?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Serve Downloads from this CDN</span></td>
                    <td>
                    <select name="Nitro[CDNStandard][ServeDownloads]">
                        <option value="no" <?php echo (empty($data['Nitro']['CDNStandard']['ServeDownloads']) || $data['Nitro']['CDNStandard']['ServeDownloads'] == 'no') ? 'selected=selected' : ''?>>No</option>
                        <option value="yes" <?php echo( (!empty($data['Nitro']['CDNStandard']['ServeDownloads']) && $data['Nitro']['CDNStandard']['ServeDownloads'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
                    </select>
                    </td>
                  </tr>
                </table> 
            </div>
            -->
         </div>
       </div>
    </div>
    </div>
    <div class="span5">
    	<div class="cdnstandard-tabbable-parent">
            <div class="box-heading cdnstandard-ftp-tabbable-parent"><h1><i class="icon-globe"></i>FTP Connection</h1></div>
            <div class="box-content cdnstandard-ftp-tabbable-parent" style="min-height:100px; line-height:20px;">
            	<div class="ftp-alert-info alert alert-info"><?php echo !empty($data['Nitro']['CDNStandardFTP']['LastUpload']) ? 'Last synchronization was on ' . $data['Nitro']['CDNStandardFTP']['LastUpload'] : 'No synchornization has been carried out yet.'; ?></div>
                <input type="hidden" name="Nitro[CDNStandardFTP][LastUpload]" value="<?php echo !empty($data['Nitro']['CDNStandardFTP']['LastUpload']) ? $data['Nitro']['CDNStandardFTP']['LastUpload'] : ''; ?>" />
            	<div class="ftp-alert alert alert-error"></div>
                <div class="ftp-alert alert alert-success"></div>
                <table class="form">
                  <tr class="cdnstandard-tabbable-parent">
                    <td>Protocol<span class="help">Note: Some servers may not support FTPS.</span></td>
                    <td>
                    <select name="Nitro[CDNStandardFTP][Protocol]">
                        <option value="ftp" <?php echo (empty($data['Nitro']['CDNStandardFTP']['Protocol']) || $data['Nitro']['CDNStandardFTP']['Protocol'] == 'ftp') ? 'selected=selected' : ''?>>FTP</option>
                        <option value="ftps" <?php echo( (!empty($data['Nitro']['CDNStandardFTP']['Protocol']) && $data['Nitro']['CDNStandardFTP']['Protocol'] == 'ftps')) ? 'selected=selected' : ''?>>FTPS</option>
                    </select>
                    </td>
                  </tr>
                  <tr>
                    <td>FTP Host</td>
                    <td>
                    <input type="text" name="Nitro[CDNStandardFTP][Host]" value="<?php echo !empty($data['Nitro']['CDNStandardFTP']['Host']) ? $data['Nitro']['CDNStandardFTP']['Host'] : ''; ?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>FTP Port<span class="help">If left empty, 21 will be considered as default.</span></td>
                    <td>
                    <input type="text" name="Nitro[CDNStandardFTP][Port]" value="<?php echo !empty($data['Nitro']['CDNStandardFTP']['Port']) ? $data['Nitro']['CDNStandardFTP']['Port'] : ''; ?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>FTP Username<span class="help">This value will be hidden for users without modify access.</span></td>
                    <td>
                    <input type="text" name="Nitro[CDNStandardFTP][Username]" value="<?php echo !empty($data['Nitro']['CDNStandardFTP']['Username']) ? $data['Nitro']['CDNStandardFTP']['Username'] : ''; ?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>FTP Password<span class="help">This value will be hidden for users without modify access.</span></td>
                    <td>
                    <input type="password" name="Nitro[CDNStandardFTP][Password]" value="<?php echo !empty($data['Nitro']['CDNStandardFTP']['Password']) ? $data['Nitro']['CDNStandardFTP']['Password'] : ''; ?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>FTP Root<span class="help">The directory your content is stored in. Normally 'public_html/'.</span></td>
                    <td>
                    <input type="text" name="Nitro[CDNStandardFTP][Root]" value="<?php echo !empty($data['Nitro']['CDNStandardFTP']['Root']) ? $data['Nitro']['CDNStandardFTP']['Root'] : ''; ?>" />
                    </td>
                  </tr>
                  <tr>
                  	<td colspan="2">
                    	<label class="ftp-upload-label"><input type="checkbox" value="1" name="Nitro[CDNStandardFTP][SyncImages]" <?php if (!empty($data['Nitro']['CDNStandardFTP']['SyncImages'])) echo 'checked="checked"'; ?>/> Upload all local images to CDN via FTP</label>
                        <label class="ftp-upload-label"><input type="checkbox" value="1" name="Nitro[CDNStandardFTP][SyncCSS]" <?php if (!empty($data['Nitro']['CDNStandardFTP']['SyncCSS'])) echo 'checked="checked"'; ?>/> Upload all local CSS files to CDN via FTP</label>
                        <label class="ftp-upload-label"><input type="checkbox" value="1" name="Nitro[CDNStandardFTP][SyncJavaScript]" <?php if (!empty($data['Nitro']['CDNStandardFTP']['SyncJavaScript'])) echo 'checked="checked"'; ?>/> Upload all local JavaScript files to CDN via FTP</label>
                    </td>
                  </tr>
                  <tr>
                  	<td colspan="2">
                    	
                        <a class="ftp-upload btn btn-success"><i class="icon-circle-arrow-up icon-white"></i> <span class="ftp-button-text">Upload to CDN Server</span></a>
                        <a class="ftp-cancel btn btn-inverse"><i class="icon-remove icon-white"></i> <span class="ftp-cancel-text">Abort</span></a>
                        <div class="progress active ftp-progress">
                          <div class="bar-success" style="width: 0%;"></div>
                        </div>
                      
                        <div class="empty-div"></div>
                        <div class="ftp-log">
                        </div>
                    </td>
                  </tr>
                </table>
            </div>
        </div>
        <div class="box-heading"><h1><i class="icon-info-sign"></i>Benefits of CDN</h1></div>
        <div class="box-content" style="min-height:100px; line-height:20px;">
            CDN (Content Delivery Network) is a web service used for global content delivery. When you integrate with CDN, your content gets copied on all the CDN servers around the world. Therefore, when a visitor tries to access your webstore, content is delivered from the nearest server geographically located to the visitor instead of your hosting server which may be on the other part of the globe. This makes your webstore load faster and improves overall speed.
        </div>
    </div>
</div>

<style>
.cdnstandard {
		
}

</style>

<script type="text/javascript">

$(window).load(function() {
	var showCDNForm = function() {
		if ($('.NitroCDNStandard').val() == 'yes') {
			$('.cdnstandard-tabbable-parent').fadeIn();
		} else {
			$('.cdnstandard-tabbable-parent').hide();
		}
	}
	
	$('.NitroCDNStandard').change(function() {
		$('.cdn-error').hide();
		if ($('.NitroCDNAmazon').val() == 'yes' || $('.NitroCDNRackspace').val() == 'yes') {
			$('.cdn-error.cdn-generic-error').fadeIn();
			$('.NitroCDNStandard').val('no');
		} else {
			$('.cdn-error.cdn-generic-error').hide();
			showCDNForm();
		}
	}).trigger('change');
	
	showCDNForm();
	
	var showCDNFTPForm = function() {
		if ($('.NitroCDNStandardFTP').val() == 'yes') {
			$('.cdnstandard-ftp-tabbable-parent').fadeIn();
		} else {
			$('.cdnstandard-ftp-tabbable-parent').hide();
		}
	}
	
	$('.NitroCDNStandardFTP').change(function() {
		showCDNFTPForm();
	});
	
	showCDNFTPForm();
	
	var refreshAjax;
	var uploadFTP;
	var interval;
	var ftpLog = $('.ftp-log');
	var ftp_uploading = false;
	
	var refresh_ftp_data = function(init) {
		if (typeof refreshAjax != 'undefined') refreshAjax.abort();
		if (typeof init != 'undefined') {
			$('.ftp-progress .bar-success').css({width: '0%'});
		}
		refreshAjax = $.ajax({
			url: 'index.php?route=tool/nitro/getftpprogress&token=' + getURLVar('token') + (typeof init != 'undefined' ? '&init=true' : ''),
			dataType: 'json',
			cache: false,
			success: function(data) {
				if (data == null) return;
				ftpLog.html(data.message);
				$('.ftp-progress .bar-success').css({height: '30px'}).animate({width: data.percent + '%'}, 100);
				ftpLog.css({width: $('.empty-div').width() + 'px'});
			}
		});
	}
	
	$('.ftp-upload').click(function() {
		if (ftp_uploading) return;
		var button = $(this);
		refresh_ftp_data(true);
		uploadFTP = $.ajax({
			url: 'index.php?route=tool/nitro/saveftp&token=' + getURLVar('token'),
			type: 'POST',
			cache: false,
			dataType: 'json',
			data: {
				protocol: $('select[name="Nitro[CDNStandardFTP][Protocol]"]').val(),
				host: $('input[name="Nitro[CDNStandardFTP][Host]"]').val(),
				port: $('input[name="Nitro[CDNStandardFTP][Port]"]').val(),
				username: $('input[name="Nitro[CDNStandardFTP][Username]"]').val(),
				password: $('input[name="Nitro[CDNStandardFTP][Password]"]').val(),
				root: $('input[name="Nitro[CDNStandardFTP][Root]"]').val(),
				syncImages: $('input[name="Nitro[CDNStandardFTP][SyncImages]"]').is(':checked'),
				syncCSS: $('input[name="Nitro[CDNStandardFTP][SyncCSS]"]').is(':checked'),
				syncJavaScript: $('input[name="Nitro[CDNStandardFTP][SyncJavaScript]"]').is(':checked')
			},
			beforeSend: function() {
				button.attr('disabled','disabled');
				$('.ftp-button-text').text('Uploading...');
				$('.ftp-cancel-text').removeAttr('disabled');
				ftpLog.empty();
				$('.ftp-alert').hide().empty();
				interval = setInterval(refresh_ftp_data, 1000);
				ftp_uploading = true;
			},
			success: function(data) {
				if (typeof data.success != 'undefined') {
					$('.ftp-alert.alert-success').html(data.success).slideDown();
				}
				if (typeof data.error != 'undefined') {
					$('.ftp-alert.alert-error').html(data.error).slideDown();
				}
				if (typeof data.upload_time != 'undefined') {
					$('.ftp-alert-info.alert-info').html('Last synchronization was on ' + data.upload_time);
					$('input[name="Nitro[CDNStandardFTP][LastUpload]"]').val(data.upload_time);
				}
			},
			complete: function() {
				button.removeAttr('disabled');
				$('.ftp-cancel-text').attr('disabled','disabled');
				$('.ftp-button-text').text('Upload to CDN Server');
				clearInterval(interval);
				refresh_ftp_data();
				ftp_uploading = false;
			}
		});
	});
	
	$('.ftp-cancel').click(function() {
		if (!ftp_uploading) return;
		$.ajax({
			url: 'index.php?route=tool/nitro/cancelftp&token=' + getURLVar('token'),
			cache: false
		});
	});
	
	$('.ftp-cancel-text').attr('disabled','disabled');
});

</script>