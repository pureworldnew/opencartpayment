<?php $reportp = json_decode($widget['pagespeed']['desktop'],true); ?>

<script type="text/javascript">
var report_desktop = <?php echo $widget['pagespeed']['desktop']; ?>;
var report_mobile = <?php echo $widget['pagespeed']['mobile']; ?>;
</script>
<table style="width:100%" class="pagespeedMainTable">
<tr>
    <td style="width:50%;vertical-align: top;">
    <div id="g1" class="bigGauge"></div>
    <div id="g2" class="bigGauge"></div>
    </td>
    <td style="width:50%; vertical-align:top;padding-top:5px;">
    	<h3>Steps you need to take</h3>
        <table class="table stepsToTake">
          <tbody>
            <tr class="<?php echo (!empty($data['Nitro']['PageCache']['Enabled']) && $data['Nitro']['PageCache']['Enabled'] == 'yes') ? 'disabled' : ''; ?>">
              <td>1</td>
              <td>Enable Page Caching</td>
              <td style="width: 100px"><a onclick="$('html, body').animate({ scrollTop: 0 }, 200, function() { $('a[href=#pagecache]').trigger('click'); });" class="btn btn-small btn-inverse">Setup Now</a></td>
            </tr>
            <tr class="<?php echo (!empty($data['Nitro']['BrowserCache']['Enabled']) && $data['Nitro']['BrowserCache']['Enabled'] == 'yes') ? 'disabled' : ''; ?>">
              <td>2</td>
              <td>Leverage Browser Caching</td>
              <td><a onclick="$('html, body').animate({ scrollTop: 0 }, 200, function() { $('a[href=#browsercache]').trigger('click'); });" class="btn btn-small btn-inverse">Setup Now</a></td>
            </tr>
            <tr class="<?php echo (!empty($data['Nitro']['Compress']['Enabled']) && $data['Nitro']['Compress']['Enabled'] == 'yes') ? 'disabled' : ''; ?>">
              <td>3</td>
              <td>Enable GZIP Compression</td>
              <td><a onclick="$('html, body').animate({ scrollTop: 0 }, 200, function() { $('a[href=#compression]').trigger('click'); });" class="btn btn-small btn-inverse">Setup Now</a></td>
            </tr>
            <tr class="<?php echo (!empty($data['Nitro']['Mini']['CSS']) && $data['Nitro']['Mini']['CSS'] == 'yes' && $data['Nitro']['Mini']['JS'] == 'yes') ? 'disabled' : ''; ?>">
              <td>4</td>
              <td>Minify CSS and JavaScript</td>
              <td><a onclick="$('html, body').animate({ scrollTop: 0 }, 200, function() { $('a[href=#minification]').trigger('click'); });" class="btn btn-small btn-inverse">Setup Now</a></td>
            </tr>
            <tr class="<?php echo (!empty($data['Nitro']['Mini']['HTML']) && $data['Nitro']['Mini']['HTML'] == 'yes') ? 'disabled' : ''; ?>">
              <td>5</td>
              <td>Minify HTML</td>
              <td><a onclick="$('html, body').animate({ scrollTop: 0 }, 200, function() { $('a[href=#minification]').trigger('click'); });" class="btn btn-small btn-inverse">Setup Now</a></td>
            </tr>
            <tr class="<?php echo (!empty($data['Nitro']['Tips']['CreatedSprites']) && $data['Nitro']['Tips']['CreatedSprites'] == 'yes') ? 'disabled' : ''; ?>">
              <td>6</td>
              <td>Combine images into sprites</td>
              <td><a onclick="$('html, body').animate({ scrollTop: 0 }, 200, function() { $('a[href=#qa]').trigger('click'); });" class="btn btn-small btn-inverse">Learn How</a></td>
            </tr>
          </tbody>
        </table>
    </td>
</tr>
</table>
<?php if (!empty($reportp['score']) && $reportp['score'] > 81): ?>
    <div class="text-greatscore"><span class="label label-success">Great Score</span>&nbsp;&nbsp;<a href="http://www.seochat.com/c/a/search-engine-optimization-help/google-page-speed-score-vs-website-loading-time/" target="_blank">Top-ranking websites</a> in Google have an average score of 80.78 and yours is <strong><?php echo $reportp['score']; ?></strong>!</div>
<?php endif; ?>


<ul class="nav nav-pills gaugeFilterUL" param="performers">
  <li class="active">
    <a href="javascript:void(0)" param="desktopScore">Desktop</a>
  </li>
  <li>
    <a href="javascript:void(0)" param="mobileScore">Mobile</a>
  </li>
</ul>
<div id="extendedInfo"></div>
<div class="alert alert-success performersSuccess" style="display:none"></div>

<script type="text/javascript">
// This fixes a firefox issue with gauges
if (jQuery.browser.mozilla) {
	$('base').remove();
}
var recognizedRules = ["AvoidLandingPageRedirects", "EnableGzipCompression", "LeverageBrowserCaching", "MainResourceServerResponseTime", "MinifyCss", "MinifyHTML", "MinifyJavaScript", "MinimizeRenderBlockingResources", "OptimizeImages", "PrioritizeVisibleContent"];

var loadMainGauges = function() {
	new JustGage({
	  id: "g1", 
	  value: (report_desktop.score) ? report_desktop.score : 0, 
	  min: 0,
	  max: 100,
	  title: (report_desktop.error) ? report_desktop.error.message : "Your Site Desktop Page Score",
	  label: "points",
	  levelColors: ["#B20000","#FF9326","#6DD900"],
	});
	
	new JustGage({
	  id: "g2", 
	  value: (report_mobile.score) ? report_mobile.score : 0,
	  min: 0,
	  max: 100,
	  title: (report_mobile.error) ? report_mobile.error.message : "Your Site Mobile Page Score",
	  label: "points",
	  levelColors: ["#B20000","#FF9326","#6DD900"],
	});
}

var loadDesktopReport = function() {
	var ruleResult = null;
	var isRulePassed = false;
	var desktopReportHtml = '<div id="desktopExtendedReport">';
	for(x in report_desktop.formattedResults.ruleResults) {
		ruleResult = report_desktop.formattedResults.ruleResults[x];
		isRulePassed = (parseInt(ruleResult.ruleImpact) == 0);
		for (t=0;t<ruleResult.urlBlocks.length;t++) {
			if (ruleResult.urlBlocks[t].header.args) {
				var formattedResult = ruleResult.urlBlocks[t].header.format;
				for(i=0;i<ruleResult.urlBlocks[t].header.args.length;i++) {
					if (ruleResult.urlBlocks[t].header.args[i].type != 'HYPERLINK') {
						formattedResult = formattedResult.replace('$'+(i+1), ruleResult.urlBlocks[t].header.args[i].value);
					} else {
						formattedResult = formattedResult.replace('Learn more', '<a target="_blank" href="'+ruleResult.urlBlocks[t].header.args[i].value+'">Learn more</a>');
					}
				}
				desktopReportHtml += '<li class="'+((isRulePassed) ? 'passedRule' : 'notPassedRule')+'"><span>' + formattedResult + '</span>';
			}
			
			if (ruleResult.urlBlocks[t].urls) {
				desktopReportHtml += '<ul>';
				for(y=0;y<ruleResult.urlBlocks[t].urls.length;y++) {
					if (ruleResult.urlBlocks[t].urls[y].result.args) {
						var formattedResult = ruleResult.urlBlocks[t].urls[y].result.format;
						for(z=0;z<ruleResult.urlBlocks[t].urls[y].result.args.length;z++) {
							if (ruleResult.urlBlocks[t].urls[y].result.args[z].type == 'URL') {
								formattedResult = formattedResult.replace('$'+(z+1), '<a target="_blank" href="'+ruleResult.urlBlocks[t].urls[y].result.args[z].value+'">'+ruleResult.urlBlocks[t].urls[y].result.args[z].value+'</a>');
							} else {
								formattedResult = formattedResult.replace('$'+(z+1), ruleResult.urlBlocks[t].urls[y].result.args[z].value);
							}
						}
						desktopReportHtml += '<li>' + formattedResult + '</li>'
					}
				}
				desktopReportHtml += '</ul>'
			}
			desktopReportHtml += '</li>';
		}
	}
	desktopReportHtml += '</div>'
	$('#extendedInfo').append(desktopReportHtml);
	$('#desktopExtendedReport').append('<ul class="passedRules"><li class="passedRulesCounter resultsToggle">{NUM} Passed rules</li></ul><ul class="notPassedRules"><li class="notPassedRulesCounter resultsToggle">{NUM} Rules not passed</li></ul>');
	$('#desktopExtendedReport li.passedRule').appendTo('#desktopExtendedReport ul.passedRules');
	$('#desktopExtendedReport ul.passedRules li.passedRulesCounter').text($('#desktopExtendedReport ul.passedRules li.passedRulesCounter').text().replace('{NUM}', $('#desktopExtendedReport li.passedRule').size()));
	$('#desktopExtendedReport li.notPassedRule').appendTo('#desktopExtendedReport ul.notPassedRules');
	$('#desktopExtendedReport ul.notPassedRules li.notPassedRulesCounter').text($('#desktopExtendedReport ul.notPassedRules li.notPassedRulesCounter').text().replace('{NUM}', $('#desktopExtendedReport li.notPassedRule').size()));
	$('#desktopExtendedReport li ul').hide().parent().addClass('foldable');
}

var loadMobileReport = function() {
	var ruleResult = null;
	var isRulePassed = false;
	var desktopReportHtml = '<div id="mobileExtendedReport" style="display: none;">';
	for(x in report_mobile.formattedResults.ruleResults) {
		if (recognizedRules.indexOf(x) < 0) continue;
		ruleResult = report_mobile.formattedResults.ruleResults[x];
		isRulePassed = (parseInt(ruleResult.ruleImpact) == 0);
		for (t=0;t<ruleResult.urlBlocks.length;t++) {
			if (ruleResult.urlBlocks[t].header.args) {
				var formattedResult = ruleResult.urlBlocks[t].header.format;
				for(i=0;i<ruleResult.urlBlocks[t].header.args.length;i++) {
					if (ruleResult.urlBlocks[t].header.args[i].type != 'HYPERLINK') {
						formattedResult = formattedResult.replace('$'+(i+1), ruleResult.urlBlocks[t].header.args[i].value);
					} else {
						formattedResult = formattedResult.replace('Learn more', '<a target="_blank" href="'+ruleResult.urlBlocks[t].header.args[i].value+'">Learn more</a>');
					}
				}
				desktopReportHtml += '<li class="'+((isRulePassed) ? 'passedRule' : 'notPassedRule')+'"><span>' + formattedResult + '</span>';
			}
			
			if (ruleResult.urlBlocks[t].urls) {
				desktopReportHtml += '<ul>';
				for(y=0;y<ruleResult.urlBlocks[t].urls.length;y++) {
					if (ruleResult.urlBlocks[t].urls[y].result.args) {
						var formattedResult = ruleResult.urlBlocks[t].urls[y].result.format;
						for(z=0;z<ruleResult.urlBlocks[t].urls[y].result.args.length;z++) {
							if (ruleResult.urlBlocks[t].urls[y].result.args[z].type == 'URL') {
								formattedResult = formattedResult.replace('$'+(z+1), '<a target="_blank" href="'+ruleResult.urlBlocks[t].urls[y].result.args[z].value+'">'+ruleResult.urlBlocks[t].urls[y].result.args[z].value+'</a>');
							} else {
								formattedResult = formattedResult.replace('$'+(z+1), ruleResult.urlBlocks[t].urls[y].result.args[z].value);
							}
						}
						desktopReportHtml += '<li>' + formattedResult + '</li>'
					}
				}
				desktopReportHtml += '</ul>'
			}
			desktopReportHtml += '</li>';
		}
	}
	desktopReportHtml += '</div>'
	$('#extendedInfo').append(desktopReportHtml);
	$('#mobileExtendedReport').append('<ul class="passedRules"><li class="passedRulesCounter resultsToggle">{NUM} Passed rules</li></ul><ul class="notPassedRules"><li class="notPassedRulesCounter resultsToggle">{NUM} Rules not passed</li></ul>');
	$('#mobileExtendedReport li.passedRule').appendTo('#mobileExtendedReport ul.passedRules');
	$('#mobileExtendedReport ul.passedRules li.passedRulesCounter').text($('#mobileExtendedReport ul.passedRules li.passedRulesCounter').text().replace('{NUM}', $('#mobileExtendedReport li.passedRule').size()));
	$('#mobileExtendedReport li.notPassedRule').appendTo('#mobileExtendedReport ul.notPassedRules');
	$('#mobileExtendedReport ul.notPassedRules li.notPassedRulesCounter').text($('#mobileExtendedReport ul.notPassedRules li.notPassedRulesCounter').text().replace('{NUM}', $('#mobileExtendedReport li.notPassedRule').size()));
	$('#mobileExtendedReport li ul').hide().parent().addClass('foldable');
}

$('.gaugeFilterUL a').click(function() {
	$(this).parents('ul').find('li').removeClass('active');
	$(this).parent().addClass('active');
	
	if ($(this).attr('param') == 'desktopScore') {
		$('#g2').hide();
		$('#g1').show();
		$('#mobileExtendedReport').hide();
		$('#desktopExtendedReport').show();
	}
	
	if ($(this).attr('param') == 'mobileScore') {
		$('#g1').hide();
		$('#g2').show();
		$('#desktopExtendedReport').hide();
		$('#mobileExtendedReport').show();
	}
});

$('li.foldable').live('click', function(){
	$(this).find('ul').slideToggle();
});

$('li.resultsToggle').live('click', function(){
	$(this).parent().find('li.passedRule, li.notPassedRule').slideToggle();
});

loadMainGauges();
$('#g2').hide();
loadDesktopReport();
loadMobileReport();

$('.stepsToTake tr.disabled .btn').text('Enabled').removeClass('btn-inverse').addClass('disabled');

</script>