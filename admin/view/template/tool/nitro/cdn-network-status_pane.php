<div class="row-fluid">
  <div class="span8">
    <div class="box-heading">
      <h1>Generic CDN Service &nbsp;<i class="icon-refresh" onclick="checkGenericCDNstatus()" title="Refresh Status"></i><span class="serviceStatusSpan">Service Status: <?php echo (empty($data['Nitro']['CDNStandard']['Enabled']) || $data['Nitro']['CDNStandard']['Enabled'] == 'no') ? '<b class="disabled">Disabled</b>' : '<b class="enabled">Enabled</b>'?></span></h1>
    </div>
    
    <table class="form cdnstatustable">
      <tbody>
        <tr url="<?php echo(!empty($data['Nitro']['CDNStandard']['ImagesHttpUrl'])) ? $data['Nitro']['CDNStandard']['ImagesHttpUrl'] : ''?>">
          <td class="statusName">Images CDN HTTP URL<div class="pingURL"></div></td>
          <td class="statusTime">13 ms response time</td>
          <td class="statusConnected">Connected</td>
        </tr>
        <tr url="<?php echo(!empty($data['Nitro']['CDNStandard']['ImagesHttpsUrl'])) ? $data['Nitro']['CDNStandard']['ImagesHttpsUrl'] : ''?>">
          <td class="statusName">Images CDN HTTPS URL<div class="pingURL"></div></td>
          <td class="statusTime">12 ms response time</td>
          <td class="statusConnected">Connected</td>
        </tr>
        <tr url="<?php echo(!empty($data['Nitro']['CDNStandard']['CSSHttpUrl'])) ? $data['Nitro']['CDNStandard']['CSSHttpUrl'] : ''?>">
          <td class="statusName">CSS CDN HTTP URL<div class="pingURL"></div></td>
          <td class="statusTime">13 ms response time</td>
          <td class="statusConnected">Connected</td>
        </tr>
        <tr url="<?php echo(!empty($data['Nitro']['CDNStandard']['CSSHttpsUrl'])) ? $data['Nitro']['CDNStandard']['CSSHttpsUrl'] : ''?>">
          <td class="statusName">CSS CDN HTTPS URL<div class="pingURL"></div></td>
          <td class="statusTime">13 ms response time</td>
          <td class="statusConnected">Connected</td>
        </tr>
        <tr url="<?php echo(!empty($data['Nitro']['CDNStandard']['JavaScriptHttpUrl'])) ? $data['Nitro']['CDNStandard']['JavaScriptHttpUrl'] : ''?>">
          <td class="statusName">JavaScript CDN HTTP URL<div class="pingURL"></div></td>
          <td class="statusTime">13 ms response time</td>
          <td class="statusConnected">Connected</td>
        </tr>
        <tr url="<?php echo(!empty($data['Nitro']['CDNStandard']['JavaScriptHttpsUrl'])) ? $data['Nitro']['CDNStandard']['JavaScriptHttpsUrl'] : ''?>">
          <td class="statusName">JavaScript CDN HTTPS URL<div class="pingURL"></div></td>
          <td class="statusTime">12 ms response time</td>
          <td class="statusConnected">Connected</td>
        </tr>
        <!--
        <tr url="<?php echo(!empty($data['Nitro']['CDNStandard']['DownloadsHttpUrl'])) ? $data['Nitro']['CDNStandard']['DownloadsHttpUrl'] : ''?>">
          <td class="statusName">Downloads CDN HTTP URL<div class="pingURL"></div></td>
          <td class="statusTime">12 ms response time</td>
          <td class="statusConnected">Connected</td>
        </tr>
        <tr url="<?php echo(!empty($data['Nitro']['CDNStandard']['DownloadsHttpsUrl'])) ? $data['Nitro']['CDNStandard']['DownloadsHttpsUrl'] : ''?>">
          <td class="statusName">Downloads CDN HTTPS URL<div class="pingURL"></div></td>
          <td class="statusTime">13 ms response time</td>
          <td class="statusConnected">Connected</td>
        </tr>
        -->
      </tbody>
    </table>
    
    <div class="box-heading">
      <h1>Amazon CloudFront/S3 CDN Service &nbsp;<i class="icon-refresh" onclick="checkAmazonCDNstatus()" title="Refresh Status"></i><span class="serviceStatusSpan">Service Status: <?php echo (empty($data['Nitro']['CDNAmazon']['Enabled']) || $data['Nitro']['CDNAmazon']['Enabled'] == 'no') ? '<b class="disabled">Disabled</b>' : '<b class="enabled">Enabled</b>'?></span></h1>
    </div>
    
    <table class="form amazonstatustable">
      <tbody>
        <tr url="<?php echo(!empty($data['Nitro']['CDNAmazon']['ImageHttpUrl'])) ? $data['Nitro']['CDNAmazon']['ImageHttpUrl'] : ''?>">
          <td class="statusName">Amazon CloudFront/S3 CDN Image HTTP URL<div class="pingURL"></div></td>
          <td class="statusTime">13 ms response time</td>
          <td class="statusConnected">Connected</td>
        </tr>
        <tr url="<?php echo(!empty($data['Nitro']['CDNAmazon']['ImageHttpsUrl'])) ? $data['Nitro']['CDNAmazon']['ImageHttpsUrl'] : ''?>">
          <td class="statusName">Amazon CloudFront/S3 CDN Image HTTPS URL<div class="pingURL"></div></td>
          <td class="statusTime">13 ms response time</td>
          <td class="statusConnected">Connected</td>
        </tr>
        <tr url="<?php echo(!empty($data['Nitro']['CDNAmazon']['CSSHttpUrl'])) ? $data['Nitro']['CDNAmazon']['CSSHttpUrl'] : ''?>">
          <td class="statusName">Amazon CloudFront/S3 CDN CSS HTTP URL<div class="pingURL"></div></td>
          <td class="statusTime">13 ms response time</td>
          <td class="statusConnected">Connected</td>
        </tr>
        <tr url="<?php echo(!empty($data['Nitro']['CDNAmazon']['CSSHttpsUrl'])) ? $data['Nitro']['CDNAmazon']['CSSHttpsUrl'] : ''?>">
          <td class="statusName">Amazon CloudFront/S3 CDN CSS HTTPS URL<div class="pingURL"></div></td>
          <td class="statusTime">13 ms response time</td>
          <td class="statusConnected">Connected</td>
        </tr>
        <tr url="<?php echo(!empty($data['Nitro']['CDNAmazon']['JavaScriptHttpUrl'])) ? $data['Nitro']['CDNAmazon']['JavaScriptHttpUrl'] : ''?>">
          <td class="statusName">Amazon CloudFront/S3 CDN JavaScript HTTP URL<div class="pingURL"></div></td>
          <td class="statusTime">13 ms response time</td>
          <td class="statusConnected">Connected</td>
        </tr>
        <tr url="<?php echo(!empty($data['Nitro']['CDNAmazon']['JavaScriptHttpsUrl'])) ? $data['Nitro']['CDNAmazon']['JavaScriptHttpsUrl'] : ''?>">
          <td class="statusName">Amazon CloudFront/S3 CDN JavaScript HTTPS URL<div class="pingURL"></div></td>
          <td class="statusTime">13 ms response time</td>
          <td class="statusConnected">Connected</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="span4">
    <div class="box-heading">
      <h1><i class="icon-info-sign"></i>Network Status</h1>
    </div>
    <div class="box-content" style="min-height:100px; line-height:20px;"> This checks are performed by sending HEAD requests from your browser to each URL you have set. This requests emulate the response time your customers are getting to load the objects in their browsers. The response times in the table give you an idea of your CDN response time. </div>
  </div>
</div>
<style>
.cdnstandard {
		
}

</style>
<script>
function checkResponse(url, callback)
{
    var http = new XMLHttpRequest();
	var start = new Date().getTime();
    http.open('HEAD', url);
    http.onreadystatechange = function() {
        if (this.readyState == this.DONE) {
			var end = new Date().getTime();
			var time = end - start;
            callback(this.status != 404,time);
        }
    };
    http.send();
}

var checkGenericCDNstatus = function() {
	$('.cdnstatustable tbody tr').each(function(i,e) {
		checkResponse($(e).attr('url'),function(reachable, time) {
			var connected = 'Not Connected';
			if (reachable) connected = 'Connected';
			$(e).find('.statusTime').html(time + ' ms'); 
			$(e).find('.statusConnected').html(connected); 
			$(e).find('.pingURL').html($(e).attr('url')); 
			//.append('<tr><td class="statusName">'+e.name+'</td><td class="statusTime">'+time+' ms response time</td><td class="statusConnected">'+connected+'</td></tr>');
		});
	});
}

var checkAmazonCDNstatus = function() {
	$('.amazonstatustable tbody tr').each(function(i,e) {
		checkResponse($(e).attr('url'),function(reachable, time) {
			var connected = 'Not Connected';
			if (reachable) connected = 'Connected';
			$(e).find('.statusTime').html(time + ' ms'); 
			$(e).find('.statusConnected').html(connected); 
			$(e).find('.pingURL').html($(e).attr('url')); 
			//.append('<tr><td class="statusName">'+e.name+'</td><td class="statusTime">'+time+' ms response time</td><td class="statusConnected">'+connected+'</td></tr>');
		});
	});
}

var checkNetworkStatus = function() {
	checkGenericCDNstatus();	
}
</script>
<style>
.statusConnected {
	width: 100px;
	text-align: right;
}
.statusTime {
	text-align: right;		
}
.pingURL {
	font-size: 10px;
	margin-top: 2px;
	color:#555;	
}
.serviceStatusSpan {
	float: right;
	font-size: 11px;
}
b.enabled {
	color:#9C0;
}
b.disabled {
	color:#222;
}

</style>
