<?php 
// ***************************************************
//                  Search Analytics    
//  
//       Standalone extension and component of 
//               Advanced Smart Search
//
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************
?>

<?php echo ($standalone)? $header : ''; ?>


<script>

// Translator for Js (used in search_analytics.js)

function getText(key, args) {  
	translation = new Array();
	
	<?php foreach ($translation as $key => $text) { ?>
		translation['<?php echo $key ?>'] = '<?php echo htmlentities($text, ENT_QUOTES) ?>';
	<?php } ?>

	if(translation.hasOwnProperty(key)){
	
		if (typeof args != 'undefined') {
			for (var i=0; i< args.length; i++){
				translation[key] = translation[key].replace('{s'+i+'}', args[i] ); 
			}
		}
		return translation[key];
	} else {
		return key;
	}
}

</script>

<script>



// On document ready:
$(function() {

	token = '<?php echo $token ?>';

	keywordHits = new JTables({	
							container:		'#keyword-hits', 
							title:			getText('text_top_searches'),
							ajaxUrl: 		'index.php?route=module/search_analytics/get_keyword_hits&token='+token,
							elToScroll:		'table'
	});

	searchHistory = new JTables({	
							container:		'#search-history', 
							title:			getText('text_search_history'),
							ajaxUrl: 		'index.php?route=module/search_analytics/get_search_history&token='+token,
							elToScroll:		'document'
	});

	graph = new Graph({
					token : token
	});


	// Header style
	$(searchHistory).on( 'headerReady', function( event ) {
		
		// Headers
		$('.ajax-table .header').css('background-color', $('.ajax-table th').css('background-color') + ' !important');
		
		// Button "delete"
		$('#search-history th.delete').wrapInner('<span class="button red"></span>');
		
	});
	
	
	// Add export CSV & delete buttons to the table headers 
	
	$('#keyword-hits > .title').append('<span class="csv button green" onclick=" location = \'<?php echo $export_csv; ?>&table=keyword_hits&\' + keywordHits.queryString;" ><?php echo $text_export_csv; ?></span>');
	
	$('#search-history > .title').append('<span class="clear-history button red" ><?php  echo $text_delete_history; ?></span>');
		
	$('#search-history > .title').append('<span class="csv button green" onclick=" location = \'<?php echo $export_csv; ?>&table=search_history&\' + searchHistory.queryString;" ><?php echo $text_export_csv; ?></span>');
	

	function deleteHistory(data) {
	
		data = data || []; // set "data" to empty array if not defined
	
		$.ajax({
			url: 'index.php?route=module/search_analytics/delete_search&token='+token,
			data: data, 
			dataType: 'json',
			
			beforeSend : function (){	
			},
			
			success: function(json) {
			
				keywordHits.update();
				searchHistory.update();			
				graph.update();			
			}
		});
	}	
		
	$(document).on('click', '#search-history th.delete', function() {
		
		var checkboxes = $('#search-history input[type="checkbox"]:checked');
		
		if ( checkboxes.length ) { // if at least one checkbox is checked
		
			var plural = (checkboxes.length > 1)? 's' : '';
			var confirmDelete = confirm(getText('text_confirm_delete_rows',[checkboxes.length, plural]));

			if (confirmDelete){
				deleteHistory(checkboxes);
			}
		}
	});
	
	$(document).on('click', '#search-history .clear-history', function() {
	
		var confirmDelete = confirm(getText('text_confirm_delete_history'));
		
		if (confirmDelete){
			deleteHistory();
		}
	});
	

	
	
	// Date picker
	if ($.fn.datetimepicker) { // Oc 2+
	
		$('#date-start, #date-end').datetimepicker({
			pickDate: true,
			pickTime: true
		});
	}
	else if ($.fn.datepicker) { // Oc <= 1.5.6.4
		
		$('#date-start, #date-end').datepicker({dateFormat: 'yy-mm-dd'});
	}

	
	
	
	
	// Graph Toggle
	toggleExactBroadRadio();
		

	// Load graphs on page load...
	$('#filter').trigger('click');
	
	// ...and display the h-bar chart:
	$('.buttons .h-bars').trigger('click');

});


</script> 


	<?php if ($standalone) { ?>	
	
		<?php
		if ( version_compare(VERSION, '2.0.0.0', '>=') ) {
			echo $column_left; 
		}
		?>
	
		<div id="content">
	
			<div class="box search-analytics">
	
				<div id="header-wrapper">
				
					<div class="breadcrumb">
						<?php  echo "&nbsp;&nbsp;"; foreach ($breadcrumbs as $breadcrumb) { ?>
						<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
						<?php } ?>
					</div>
					
					<div class="heading">
						<div class="image">
							<img src="view/image/src_ analytics_ logo.gif" alt="" />
						</div>
					
						<h1><?php echo $heading_title; ?></h1>

					</div>
					
						<?php if ($standalone && !defined('ADSMART_SRC_VERSION')) { ?>	
							<a class="full-version" title="Try now Advanced Smart Search!" href="http://www.opencart.com/index.php?route=extension/extension/info&extension_id=16756"> 
								<h1><span class="question">Want more?</span> Try Advanced Smart Search!</h1>
								<p>It includes Search Analytics, Live Search and many customizable search options!</p>
							</a>
						<?php } ?>
				
				</div>

	<?php } ?>
		

			<div class="setting-wrapper">
				<div class="settings">
				
					<div class="filters keyphrase">
						<table>
							<tr>

								<?php foreach($filter_keyphrases as $i => $filter_keyphrase) { ?>
								<td>
									<?php echo ${'entry_filter_keyphrase_'.$i}; ?><br />
									<input type="text" name="filter_keyphrases[]" value="<?php echo $filter_keyphrase; ?>" id="filter_keyphrase_<?php echo $i; ?>" size="12" />
								</td>
								<?php } ?>
								
								<td>
									<a class="button gray reset" id="keyphrase_reset"><?php echo $button_reset; ?></a>
								</td>
								
							</tr>
						</table>
						
						<table>
							<tr>
								<td>
									<?php echo $entry_exact_match; ?>
									<input type="radio" name="match_type" value="exact" <?php if ($match_type == 'exact'){ ?> checked="checked" <?php } ?> />
								</td>
								<td>
									<?php echo $entry_broad_match; ?>
									<input type="radio" name="match_type" value="broad" <?php if ($match_type == 'broad'){ ?> checked="checked" <?php } ?> />
								</td>
							</tr>
						</table>
			
					</div>

					<div class="filters date">
						<table>
							<tr>
								<td>
									<?php echo $entry_date_start; ?><br /> 
									<input type="text" data-date-format="YYYY-MM-DD" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" />
								</td>
								<td>
									<?php echo $entry_date_end; ?><br />
									<input type="text" data-date-format="YYYY-MM-DD" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" />
								</td>
								<td>
									<a class="button gray reset" id="date-reset"><?php echo $button_reset; ?></a>
								</td>
							</tr>
						</table>
						
						<!-- hidden fields to remember the min and max date -->
						<input type="hidden" name="date_start" value="<?php echo $filter_date_start; ?>" />
						<input type="hidden" name="date_end" value="<?php echo $filter_date_end; ?>"  />
							
						
						<table>
							<tr>
								<td>
									<?php echo $entry_day; ?> 
									<input type="radio" name="aggregation_period" value="day" <?php if ($aggregation_period == 'day'){ ?> checked="checked" <?php } ?> />
								</td>
								
								<td>
									<?php echo $entry_month; ?>
									<input type="radio" name="aggregation_period" value="month" <?php if ($aggregation_period == 'month'){ ?> checked="checked" <?php } ?> />
								</td>
								
								<td>
									<?php echo $entry_year; ?>
									<input type="radio" name="aggregation_period" value="year" <?php if ($aggregation_period == 'year'){ ?> checked="checked" <?php } ?> />
								</td>
								
								<!-- keep track of the last aggregation period selected and update it when the user clicks on the filter button -->
								<input type="hidden" name="aggregation_period_hidden" value="" />
							</tr>
						</table>
							
							
					</div>
					
					<div class="filters button-filter-container">				
						<div>
							<a class="button blue" id="filter" ><?php echo $button_filter; ?></a>
						</div>
					</div>
					
				</div>		
			</div>
		
			<div id="analytics-top">
			
				<!-- Graph HTML -->
				<div id="graph">
					<div class="heading">
					
						<div class="title">
							<span class="title-h-bars"><?php echo $text_top_searches_n; ?></span>
							<span class="title-pie"><?php echo $text_top_searches_percent; ?></span>
							<span class="title-bars"></span>
							<span class="title-lines"></span>
						</div>
						
						<span class="buttons">
							<a href="#" class="h-bars" class="active"><span></span></a>
							<a href="#" class="pie"><span></span></a>
							<a href="#" class="bars"><span></span></a>
							<a href="#" class="lines"><span></span></a>
						</span>	
					</div>
					
					<ul class="labels"></ul>
				 
					<div class="graph-container">
						
						<div id="graph-h-bars"></div>
						<div id="graph-pie"></div>
						<div id="graph-bars"></div>
						<div id="graph-lines"></div>
						
					</div>
				</div>
				<!-- end Graph HTML -->
		
				<div id="keyword-hits" class="ajax-table"></div>	
			</div>
			

			<div id="analytics-bottom">	
				<div id="search-history" class="ajax-table"></div>
			</div> 

			
<?php if ($standalone) { ?>	
	
		</div> <!-- End Box -->
		
	</div> <!-- End #content -->

 <?php } ?>
	
	
<?php echo ($standalone)? $footer : ''; ?>