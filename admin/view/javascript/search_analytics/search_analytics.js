/***************************************************
                  Search Analytics    
  
       Standalone extension and component of 
               Advanced Smart Search

 Author : Francesco Pisanò - francesco1279@gmail.com
              
                   www.leverod.com		
               © All rights reserved	  
 ***************************************************/
 
 
 
 
// *****************************************************
// *******************  Utilities  *********************




	// ***   jQuery.browser support for jQuery > v1.9    ***
	//              (jQuery.flot.js uses it)
	
 	jQuery.uaMatch = function( ua ) {
		ua = ua.toLowerCase();
		var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
			/(webkit)[ \/]([\w.]+)/.exec( ua ) ||
			/(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
			/(msie) ([\w.]+)/.exec( ua ) ||
			ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) || [];
		return {
			browser: match[ 1 ] || "",
			version: match[ 2 ] || "0"
		};
	};
	if ( !jQuery.browser ) {
		var 
		matched = jQuery.uaMatch( navigator.userAgent ),
		browser = {};
		if ( matched.browser ) {
			browser[ matched.browser ] = true;
			browser.version = matched.version;
		}
		// Chrome is Webkit, but Webkit is also Safari.
		if ( browser.chrome ) {
			browser.webkit = true;
		} else if ( browser.webkit ) {
			browser.safari = true;
		}
		jQuery.browser = browser;
	}


	
	// ************   Date & time functions   **************

	function dateToTimestamp(date) {
		var arr = date.split("-");
		return new Date(arr[0], arr[1] - 1, arr[2]).getTime();
	}

	
	
	function timestampToDate(unixTimestamp,format) {

		var a = new Date(unixTimestamp*1000);
		var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
		var year = a.getFullYear();
		var month = months[a.getMonth()];
		var day = a.getDate();
		// var hour = a.getHours();
		// var min = a.getMinutes();
		// var sec = a.getSeconds();
		// var date = day + ',' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
		
		var date;
		
		if (typeof format != 'undefined') {
			date = format.replace('%d',day).replace('%m',month).replace('%y',year);
		} else {
		
			date = day + ' ' + month + ' ' + year;
		}
		return date;
	}

	
	
	// Converts dates from International Standard format (ISO 8601, eg.2015-04-15) to Calendar (eg 15 Apr 2015) 
	
	function standardToCalendarDate(standardDate) {
		return timestampToDate(dateToTimestamp(standardDate)/1000);
	}


	// Convert aggregation period (day, month or year) to date format
	
	function aggregationPeriodToDateFormat(aggregationPeriod) {

		var dateFormat;
		
		switch (aggregationPeriod) {
			case 'day':
				dateFormat = '%d %m %y';
				break;
			case 'month':
				dateFormat = '%m %y';
				break;
			case 'year':
				dateFormat = '%y';
				break;
		}
		return dateFormat;
	}

	// **********   End Date & time functions   ************
		
		
		
	// Hash code generator
	
	String.prototype.hashCode = function() {

		if (Array.prototype.reduce) {
			return this.split("").reduce(function(a,b){a=((a<<5)-a)+b.charCodeAt(0);return a&a},0);   
		} else {

			var hash = 0, i, chr, len;
			if (this.length == 0) return hash;
			for (i = 0, len = this.length; i < len; i++) {
			chr   = this.charCodeAt(i);
			hash  = ((hash << 5) - hash) + chr;
			hash |= 0; // Convert to 32bit integer
			}
			return hash;
		}
	};

// *****************  End Utilities  *******************
// *****************************************************
 
 
 
 
 
 
// ***************************************************** 
// ********************  Functions  ********************

	 
	// JTables v1.0  - Requires getText(), see search_analytics.tpl

	// Author  : Francesco Pisanò
	// Email   : francesco1279@gmail.com
	// Website : www.leverod.com	

	// © All rights reserved	

	// @param {String}	container				The id or class of the table container.
	// @param {String}	ajaxUrl					Ajax url that returns the table rows.
	// @param {String}	elToScroll				Element type on which to apply the scroll. Available values: "table", "document".
	// @param {String}	title					Title to display on top of the table.

	// @param {String}	[queryString=""]		Query string to append to the url.

	// @param {Int}		[initialPage=1]			Initial page.
	// @param {String}	[height=""]				Container height. It works only if elToScroll is set on "table".
	// @param {Boolean}	[showCounter="true"]	Displays a column containing the row number.


	function JTables(params){
		
		var self = this;
		
		this.init = function() {
				
			self.jContainer		= self.jContainer	|| $(params.container);
			self.ajaxUrl		= self.ajaxUrl		|| params.ajaxUrl;
			self.elToScroll		= self.elToScroll	|| params.elToScroll;												
			
			self.initialPage	= self.initialPage	|| params.initialPage	|| 1;
			self.queryString	= self.queryString	|| params.queryString 	|| '';
			self.height			= self.height		|| params.height		|| '';
			self.showCounter	= self.showCounter	|| params.showCounter	|| true;
			
			
			if ( !self.jContainer.html()){ 
				
				var title = (params.title != '')? '<div class="title">' + params.title + '</div>' : '';
				
				$(params.container).html( title + '<div class="header"></div><div class="results"></div><div class="pagination"></div>');

				self.jTitle			= $(params.container).find('.title');
				self.jHeader		= $(params.container).find('.header');
				self.jResults		= $(params.container).find('.results');
				self.jPagination	= $(params.container).find('.pagination');
			
			} else {	// if the object and properties have already been created, just empty them. Don't 
						// destroy and create them again or we will lose the reference to the old properties
						// (for example self.jResults.on('scroll', function()... wouldn't work anymore since the new 
						// self.jResults is not the old one created together with the object by the keyword "new"
			
				$(params.container).find('.header, .results, .pagination').empty();
			}
			
			self.page				= self.initialPage;
			self.total;							// total number of results
			self.updating			= false;
			self.noMoreResults		= false;	// tells whether all the results (for the current filter settings) have been displayed	


			switch(self.elToScroll) {
			
				case 'table':
				
					if (self.height) {
						
						self.jContainer.css('height', self.height);
		
					} else {
						self.jContainer.height(self.jContainer.parent().height());
					}
					

					self.jResults.on('scroll', function() {

						if ( self.jResults.scrollTop() + self.jResults.innerHeight() >= self.jResults.prop('scrollHeight') ) {
							self.getRows();
						}
					});	
					
					
					self.getRows();	
					break;
				
				
				case 'document':
				
					self.jContainer.css('height', 'auto');
					self.jHeader.css('overflow-y', 'visible');
					self.jResults.css('overflow-y', 'visible');
				
				
					$(window).load(function() {		// Prevent browsers from using the last scroll position
						 setTimeout(function() { 	// Some browser need a delay before setting the scrollTop position, 
													// without setTimeout, some browsers (IE) fire this scrollTop before
													// using the last saved position.
							document.documentElement.scrollTop =0;
							$(document).scrollTop(0);
							self.getRows();	
						}, 200);
					});
		
					$(window).on('beforeunload', function() {	// Reset the scrollbar position to 0
						$(window).scrollTop(0);
					});
		
				
					$(document).on('scroll', function() {

						if (  $(document).scrollTop() >= ( self.jContainer.offset().top + self.jContainer.height() - $(window).height() )  ) {

							self.getRows();
						}
					});	

					break;	
			}
		}
		
		
		this.update = function() {
		
			self.noMoreResults = false;
			self.page = 1;
			self.jContainer.find('.header, .results, .pagination').empty();

			self.getRows();	
		}
		
		
		self.createHeader = function(colNames) {
					
			var html = '<table><thead><tr>';
			
			if (self.showCounter) {
				html += '<th class="counter">#</th>';
			}
			
			var title;
			for (var j = 0; j < colNames.length; j++) {
				
				title = getText('text_'+colNames[j]);
				html += '<th class="' + colNames[j] + '">' + title + '</th>';
			}
			
			html += '</tr></thead></table>';

			self.jHeader.append(html);
			
			if (self.elToScroll == 'table' ) {
			
				self.jHeader.css('overflow-y','scroll'); // insert a dummy scroll to fix the header width
			}
			
			$(self).trigger('headerReady'); // custom event that tells when the header is ready to be modified (see customization of the delete button)
		};
		
		
		this.getRows = function() {
			
			if ( self.updating == false && self.noMoreResults == false ) {
			
				$.ajax({
					url: self.ajaxUrl + '&page=' + self.page + ((self.queryString)? '&' + self.queryString : ''),
					dataType: 'json',
					
					beforeSend : function (){
						self.updating = true;
						self.jPagination.prepend('<em class="wait-icon"></em>'); 
					},
					
					success: function(json) {
					
						self.jContainer.find('.wait-icon').remove();
				
						if (json.length == 0 ) {
							self.noMoreResults = true;
							return;
						}
					
						if (json) {

							var currentScrollPos;
							if (self.elToScroll == 'table' ) {

								currentScrollPos = self.jResults.scrollTop();
							}

							var offset;
							var content	= '';
							
							if (json['page']) {

								json['page'] = parseInt(json['page']);
							
								var colNames = Object.keys(json['columns'][0]); // get the key names (columns)
													
								if (json['page'] == self.initialPage) {
								
									self.total = json['total'];
									
									// Print out the header
									self.createHeader(colNames);

									self.jResults.append('<table class="content"></table>');	
								}

								for (var i = 0; i < json['columns'].length; i++) {
									
									var even = '';
									if (i % 2 == 0) {
										even = 'class="even"';
									}
									content += '<tr ' + even + '>';
									
									if (self.showCounter) {
										content += '<td class="counter">'+ parseInt( (json['page']-1) * json['limit'] + i+1) + '</td>';
									}
									
									for (var j = 0; j < colNames.length; j++) {
										content += '<td class="'+colNames[j]+'">' + json['columns'][i][colNames[j]] + '</td>';
									}	
									content += '</tr>';
								}
							
								self.jResults.find('.content').append(content);

								var numResDisplayed = self.jResults.find('.content tr').length;
								
								self.jPagination.html(getText('text_page_number', [numResDisplayed, self.total]));
								
								if ( (json['page'] * json['limit']) >= self.total ) {
								
									self.noMoreResults = true;
								}
								
								self.page = parseInt(json['page']) + 1;	// next page to display
							}
							
							
							if (self.elToScroll == 'table' ) {
							
								// Table height:
								self.jResults.outerHeight(self.jContainer.outerHeight() - self.jTitle.outerHeight() - self.jHeader.outerHeight() - self.jPagination.outerHeight());
								
								if (json['page'] == self.initialPage) {
								
									offset = 0;
								} else {	
									offset = currentScrollPos - 1; // The -1 prevents multiple ajax calls on page load
								}		

								self.jResults.animate({ scrollTop: offset }, 100);
							}

							setTimeout(function() { 	// delay the updating of the variable self.updating

								self.updating = false;
								
							}, 500); 						
						} 
					},
				});	
			}
		}
		
		self.init();
	}



	// Graphs

	function Graph(params){

		var self = this;

		this.init = function() {
			self.queryString = self.queryString || params.queryString || '';
			
			self.token	= self.token || params.token;	
		}
		
		this.update = function() {
			self.getGraph();
		}
		
		this.render  = function(json) {

		
			// clear graphs if there is no data to display
			if ( json.length == 0 ) {	
				$.plot($('#graph-h-bars, #graph-bars, #graph-pie, #graph-h-bars'),	[]);
				$('#graph-pie .pieLabel, #graph-bars .bar-label').remove();
			}
		
		
			var graphData		= [];
			
			var barGraphData	= [];
			var pieGraphData	= [];
			var hBarGraphData	= [];
			var lineGraphData	= [];
			
			//	 graphData structure:

			//		graphData = [
			//
			//			{
			//				data: json['keyphrase 1'],      where json['keyphrase'] = ([ [timestamp_1, total_1], [timestamp_2, total_2] ... ])
			//				color: '#123456',
			//				points: { radius: 4, fillColor: '#123456' }
			//			},
			//
			//			{	
			//				data: json['keyphrase 2'],       
			//				color: '#123456',
			//				points: { radius: 4, fillColor: '#123456' }
			//			},
			//
			//          ....
			//
			//		];	
		
		
			var colors = ['#c00000','#fd6a02','#fbb009','#fcff00','#aee700','#00ca85','#009939','#0089cd','#5730c1','#cc90ff'];	

			
			if ( self.filterKeyphrases && typeof json['columns'] == 'undefined' ) { 
				$('#graph ul.labels').empty();
			}

			var obj;
			var i;
			
		// Bar and line charts, no pie/hbar charts
		
			if (typeof json['columns'] == 'undefined') {
			
				// ****************************************************************
				// This block of code is for picking the right color, based on the 
				// number of results of each keyword (higher values get warmer colors
				
				var keywordTotals = []; // "Associative" ;) array of totals for each keyword: eg: [iphone: 6, mac: 10, sony: 4 ... ]
			
				for (var key in json) {

					 if (json.hasOwnProperty(key)) {	// make sure that the key we get is an actual property of an object, 
														// and doesn't come from the prototype (json is actually an object, 
														// js doesn't support associative arrays)
						keywordTotals[key] = 0;
						
						for (var j=0; j <json[key].length; j++) {

							keywordTotals[key] += parseInt(json[key][j][1]);
						}
					}
				}
				// Make an array of keys sorted in descending order on totals (from the previous example: ["mac", "iphone", "sony"] ).
				// It will be used to pick colors.
				var keysSorted = Object.keys(keywordTotals).sort(function(a,b){return keywordTotals[b]-keywordTotals[a]});

				// End of block
				// ****************************************************************


				// Labels on top (Sorted in descending order)
				for (var j = 0; j < keysSorted.length; j++) {
						
					// Get the index for a given key (compute the module to loop over the array colors when "number of keys" > "colors")
					
					// array  :  ["mac", "iphone", "sony"]
					// indexes:     0        1       2      <= use these indexes to pick colors from the array colors[]
					i = (keysSorted.indexOf(keysSorted[j]) % colors.length);  

					
					if ( self.filterKeyphrases ) { 
						var classname = keysSorted[j].hashCode();
						
						$('#graph ul.labels').append('<li class="' + classname + '"><i class="bullet"></i><span>' + keysSorted[j] + '</span></li>');

						$('li.' + classname + ' .bullet').css('background',colors[i]);
						$('li.' + classname + ' span').css('border-bottom-color',colors[i]);
					}
				}
				
				
				for (var key in json) {

					lineObj = {};
					barObj = {};
					// See how we did for the labels
					i = (keysSorted.indexOf(key) % colors.length);  

					lineObj.color = colors[i]; 
					lineObj.label = key;
					
					barObj.color = colors[i]; 
					barObj.label = key;
					
					if ( self.filterKeyphrases ) { 
					
						barObj.data = json[key];
						
						barGraphData.push(barObj);
						
						
						// Build the array lineObj.data for the LINE graph. It doesn't contain
						// values equal to 0, which are required by the STACKED bars (see barObj.data) in order 
						// to bypass a bug that doesn't allow bars to correctly stack if 0 values are missing.

						//json array:
						// key1 : [ [x,y], [x,y] ]
						// key2 : [ [x,0], [x,y] ] // the first pair must be excluded			
						// ...
						
						var data = [];	// this will be the new json array without 0 values
						data[key] = []; // each element is an array with only one element "key" => "value" where "value" is an array of points [x,y]
						var k = 0;
						
						for (var j=0; j<json[key].length; j++ ){

							if (json[key][j][1] != '0') {

								data[key][k] = [json[key][j][0], json[key][j][1]];							
								k++;
							}
						}
				
						// Object data rebuilt without 0 values
						lineObj.data = data[key];
					

						lineGraphData.push(lineObj);
		

					} else {
					
						barGraphData = [
							{
								data: json,
								color: colors[1],
								points: { radius: 4, fillColor: colors[2] }
							}
						];
						
						// Or alternatively, like for the case with keyphrase filter:
						//	var obj = {};
						//	obj.data = json;
						//	obj.color = '#71c73e';
						//	graphData.push(obj);
								
						lineGraphData = barGraphData;			
					}						
				}	
			}
			
			
		// Pie and horizontal bar charts, no line and bar charts:
			if ( typeof json['columns'] != 'undefined' ) { 
				
				var numResults = json['columns'].length;

				// Fix the sort order (descending) for horizontal bars and pie chart.
				// For some reason horizontal bars were sorted in the opposite way, 
				// even though json data is already in descending order.		
				json['columns'].reverse();
				
				var ticks = [];
				
				// Shift on top of the y axis results when they are less than 10
				var start = 0;
				if (numResults < 10) {
					start = (10 - numResults);
				}
				
				for (var j = 0; j < numResults; j++) {
					
					obj = {};
					
					i = colors.length - 1 - (j % colors.length) - start;  // Color index (in reverse order because json columns have also been reversed
			
					var label = json['columns'][j]['keyphrase'];
					var total = parseInt(json['columns'][j]['total']);
					
					
					// Pie data
					obj.label = label;
					obj.color = colors[i];
					obj.data  = total;
					pieGraphData.push(obj);
					
					
					// Horizontal bar data
					
					// hBarGraphData =	[	{data:[[x1, y1]], color: #111111 },
					//						{data:[[x2, y2]], color: #222222 },
					//					... ]

					// Color bars: 
					// Ref.: http://stackoverflow.com/questions/23273055/different-color-bars-for-flot-categories-bar-chart
					//		 http://jsfiddle.net/G9eCB/

					hBarGraphData.push( {data:[[total, start+j]], color: colors[i]} );	// can't put the label directly in the y, instead use the array "ticks" [ [0,label0], [1,label1] ...] - See hBarOptions, yaxis

					// array of labels
					ticks.push([start+j,label]);
				}

			}

			
			// Setup date start and date end:
			var dateStart	= $('input[name="filter_date_start"]').val();
			var dateEnd	= $('input[name="filter_date_end"]').val();
			
			dateStart		= dateStart.replace(/ +?/g, '');
			dateEnd			= dateEnd.replace(/ +?/g, '');
			
			if (dateStart == '') dateStart	= $('input[name="date_start"]').val();
			if (dateEnd == '')	 dateEnd	= $('input[name="date_end"]').val();

			var aggregationPeriod = $('input[name="aggregation_period"]:checked').val(); // day, month, year
			
			var timeToBarWidth = {
				"day": 86400000,
				"month": 86400000 * 30,
				"year": 86400000 * 365
			};
			
			if (typeof json['columns'] == 'undefined') { 
			
				var barWidth = timeToBarWidth[aggregationPeriod]; // to display a thick bar, convert milliseconds to days/months/years
				
				var xaxis_min = dateToTimestamp(dateStart) - barWidth; // subtract barWidth to increase the left margin
				var xaxis_max = dateToTimestamp(dateEnd) + barWidth;	 // add barWidth to increase the right margin
			}
			
			var sharedOptions = {
				legend: {
					show: false
				},
				grid: {
				//	color: '#646464',

				//	backgroundColor: { colors: ["#ffffff", "#f5f5f5"] },
					backgroundColor: "#ffffff",
					
					borderWidth: 0,	
				//	borderColor: 'transparent',
			
					hoverable: true
				},
				xaxis: {
					mode: "time",
					min: xaxis_min,
					max: xaxis_max,
				//	tickColor: 'transparent',
				//	tickDecimals: 2,
		
					minTickSize: [1, aggregationPeriod],
					autoscaleMargin: 0.1,
					labelHeight: 20,
					axisMargin: 10
				},
				yaxis: {
					tickSize: 10,
					labelWidth: 25,
					autoscaleMargin: 0.1
				}
			};
			
			if (typeof json['columns'] == 'undefined') { 
				
				var lineOptions = {

					series: {
						points: {
							show: true,
							radius: 5,
							dataLabels: true,		
						},
		//				stack:true,
						lines: {
		//					fill:true,
							show: true
						}
					}
				};

				var barOptions = {

					series: {
						bars: {
							show: true,
							barWidth: barWidth, 
							align: 'center',
							dataLabels: true,				
							fill:1
				
						},
						stack:true
					}
				};		
			}
			
			var pieOptions = {

				series: {
					pie: {
						show: true,               
						label: {
							show:true,
							radius: 0.8,
							formatter: function (label, series) {  
								return '<div class="pie-label" style="border-bottom:2px solid ' + series.color + '">' +
								label + ' : ' + /*series.data[0][1]  + */ Math.round(series.percent) + '%' + '</div>';
							}
						},
						combine: {
							color: '#999',
							threshold: 0.07,
							label: 'Other results'
						}
					}
				},
				
				grid: {		
					hoverable: true,
					clickable: true,	
				},

				legend: {
					show: false
				}
			};
			
		
		// Set a fixed min & max on the y axis, with max being numSeries + (maxSeries - numSeries) / 2 and min being -(maxSeries - numSeries) / 2. 
		// This reserves space above and below the bars even as their number drops below maxSeries.
		
			var hBarOptions = {
			
				series: {
					bars: {
						show: true
					}
				},
				bars: {
					align: "top",
					barWidth: 0.8,
					horizontal: true,
					lineWidth: 1,
					fill: 0.9
				},
				
				xaxis: {
					axisLabelUseCanvas: true,
					axisLabelFontSizePixels: 12,
					axisLabelFontFamily: 'Verdana, Arial',
					minTickSize: 1,
					tickDecimals: 0,
					tickColor: "#5E5E5E",                        
					tickColor: "#ccc"
				},
				yaxis: {
					axisLabelUseCanvas: true,
					axisLabelFontSizePixels: 12,
					axisLabelFontFamily: 'Verdana, Arial',
					ticks: ticks,
					tickColor: "#fff", // hide horizontal lines 
					labelWidth: 300,
					max:10,
					min:-1
				},
				legend: {
					show: false
				},
				grid: {
				//	backgroundColor: { colors: ["#ffffff", "#f5f5f5"] },
					hoverable: true,
					borderWidth: 0,
					tickColor: "#f5f5f5",   
				}
			};	


			// Draw the heading:
			// Add a white top border on #graph and move the heading on that border.
			// It will help Flot to compute the right height for the container 
			// #graph-container (height set to 100% and inherited from #graph).
			var labelHeight = ($('#graph ul.labels').is(':visible'))? $('#graph ul.labels').outerHeight() : 0;
			var headingHeight = $('#graph .heading').outerHeight() + labelHeight;
			
			$('#graph').css('border-top',  + headingHeight + 'px solid #ddd' );
			$('#graph .heading').css('top', '-' + headingHeight + 'px'); 
			$('#graph ul.labels').css('top', '-' + labelHeight + 'px'); 
			
			
			
			// Plot the graphs:	
			// Don't draw bar and line graphs when json['columns'] is defined, that column is returned  
			// from index.php?route=module/search_analytics/get_keyword_hits which doesn't contain valid
			// data for these graphs.
			if ( typeof json['columns'] == 'undefined' ) { 
			
				// Merge shared options into the graph defined options
				for (var attribute in sharedOptions) { 
				
					barOptions[attribute]	= sharedOptions[attribute];
					lineOptions[attribute]	= sharedOptions[attribute];
				}
			
				self.graphBars	= $.plot($('#graph-bars'),	barGraphData,	barOptions);	// Bars
				self.graphLines	= $.plot($('#graph-lines'),	lineGraphData,	lineOptions); 	// Lines
			}
			
			if ( typeof json['columns'] != 'undefined' ) {
			
				self.graphPie	= $.plot($('#graph-pie'),		pieGraphData,	pieOptions);	// Pie
				self.graphHBars	= $.plot($('#graph-h-bars'),	hBarGraphData,	hBarOptions);	// Horizontal bar
			}
			
		}
			
		this.getGraph = function() {
		
			self.filterKeyphrases = false;
			
			$('input[name="filter_keyphrases[]"]').each( function () { 

				if ( $(this).val() ) {
					self.filterKeyphrases = true;
					return false;
				}
			});

			$.ajax({
		
				url: 'index.php?route=module/search_analytics/get_chart' + self.queryString + '&token=' + self.token,
				dataType: 'json',
				
				success: function(json) {

					self.render(json);				
				}
			});
			

			$.ajax({

				url: 'index.php?route=module/search_analytics/get_keyword_hits' + self.queryString + '&limit=10' + '&token=' + self.token,
				dataType: 'json',
				
				success: function(json) {
					self.render(json);	
				}	
			});
			
		}

		self.init();
	}



	// Update the Bar Graph title

	function updateGraphTitle() {

		var aggregationPeriod	= $('input[name="aggregation_period"]:checked').val();
		var title = '';
		switch (aggregationPeriod) {
			case 'day':
				title = getText('text_total_daily_searches');
				break;
			case 'month':
				title = getText('text_total_monthly_searches');
				break;
			case 'year':
				title = getText('text_total_yearly_searches');
				break;
		} 
		
		$('.title-bars, .title-lines').html(title);
	}



	// Enable / Disable radio button
	
	function toggleExactBroadRadio() {
		
		var empty = true;

		$('input[id^=filter_keyphrase]').each(function(){

			if ($(this).val() != ''){

				empty = false;
				return false;
			}
		});

		if (empty) {
			$('input[name="match_type"]').attr('disabled', true);
		
		} else {
			$('input[name="match_type"]').removeAttr('disabled');
		}	
	}



	// Tooltip generator for vertical bar and line graphs

	function makeTooltip(label, total, timestamp) {

		var aggregationPeriod = $('input[name=aggregation_period_hidden]').val();
		
		var label	= (label != '')? '<b>'+label + '</b>: ' : '';
		var plural	= (total > 1)? 'es' : '';
		var prep	= (aggregationPeriod == 'day')? 'on' : 'in';
		var date	= timestampToDate(timestamp/1000, aggregationPeriodToDateFormat(aggregationPeriod));

		return getText('text_bar_line_label',[label, total, plural, prep, date]);
	}



	// Tooltip

	function showTooltip(contents) {

		$('<div id="tooltip" class="tooltip">' + $('<div>').html(contents).text() + '</div>').appendTo('body').fadeIn();
	}


// ****************  End  Functions  *******************
// *****************************************************
 

 
 
// *****************************************************
// ********************  Events  ***********************


	// On document ready:
	$(function() {
	
	
		// Tooltips
		
			var currentMousePos = { x: -1, y: -1 };
			var tooltipPause = false;

			$(document).on('mousemove', function (e) {
				
				if (!tooltipPause){
				
					currentMousePos.x = e.pageX;
					currentMousePos.y = e.pageY;
					$('.tooltip').css({ left: currentMousePos.x + 10, top: currentMousePos.y - 10 - $(window).scrollTop() });
					
					tooltipPause = setTimeout(function(){tooltipPause=false}, 50);
				}
			});
			
		// End Tooltips
		

		// Setting bar (filters) events
		
			$('#filter').on('click', function() {
			

				var filterDateStart		= $('input[name="filter_date_start"]').val();
				var filterDateEnd		= $('input[name="filter_date_end"]').val();
				var matchType			= $('input[name="match_type"]:checked').val();
				var aggregationPeriod	= $('input[name="aggregation_period"]:checked').val();
				
				// Save the current aggregation period
				$('input[name="aggregation_period_hidden"]').val(aggregationPeriod);
				
				var filterKeyphrases 	= new Array();
				
				$('input[name="filter_keyphrases[]"]').each(function(){
					
					if ($(this).val() != ''){
					
						filterKeyphrases.push($(this).attr('name') + '=' + $(this).val());
					}
				});
				
				var keyphraseQuery = (filterKeyphrases.length > 0)? '&' : '';
				keyphraseQuery += filterKeyphrases.join('&');
			
				// Match type appended to query only if there are keyphrases to filter by
				var matchTypeQuery = (filterKeyphrases.length > 0)? '&match_type=' + matchType : '';
			
				// Aggregation period
				var aggregationPeriodQuery = '&aggregation_period=' + aggregationPeriod;
			
				var queryString = '&filter_date_start=' + filterDateStart + '&filter_date_end=' + filterDateEnd + keyphraseQuery + matchTypeQuery + aggregationPeriodQuery ;

				
				if (typeof keywordHits != 'undefined'){ 
					keywordHits.queryString = queryString;
					keywordHits.update();
				
				}
				if (typeof searchHistory != 'undefined') {
					searchHistory.queryString = queryString;
					searchHistory.update();
				}
				if (typeof graph != 'undefined') {
				
					graph.queryString = queryString;
					graph.update();
				}
				
				// update the Bar Graph title
				updateGraphTitle();
			});
			
		
			$('input[id^=filter_keyphrase]').on('change textInput input focus', function() {

				toggleExactBroadRadio();
			});
			

			$('#keyphrase_reset').on('click', function() {
			
				$('input[name="filter_date_start"]').val($('input[name="date_start"]').val());
				$('input[name="filter_date_end"]').val($('input[name="date_end"]').val());
				$('input[name="filter_keyphrases[]"]').val('');
				$('#graph ul.labels').empty();
				
				toggleExactBroadRadio();
				
				$('#filter').trigger('click');
			});
			
			$('#date-reset').on('click', function() {
			
				$('input[name="filter_date_start"]').val($('input[name="date_start"]').val());
				$('input[name="filter_date_end"]').val($('input[name="date_end"]').val());
				$('#graph ul.labels').empty();
				
				$('#filter').trigger('click');
			});
	
		// Ene Setting bar (filters) events
		
	

		$(document).on('mouseenter', 'td.keyphrase', function() {
		
			$('<p class="tooltip"></p>').html(getText('text_compare')).appendTo('body').fadeIn('slow');			
		});
		
		
		$(document).on('mouseleave', 'td.keyphrase', function() {
			$('.tooltip').remove();
		});
		
		
		$(document).on('click', 'td.keyphrase', function() {
		
			var $that = $(this);
			var pickedKw = $that.html();

			$('input[name="filter_keyphrases[]"]').each(function() {
				
				$availableField = $(this);
				
				if ( $(this).val() === '' || pickedKw == $(this).val() ) {			
					return false;
				}	
			});

			
			$availableField.animate({
				color: "#fff",
				backgroundColor: "#FFA52A",
			}, 600).animate({
				color: "#000",
				backgroundColor: "#fff",
			}, 600);

			$availableField.val(pickedKw);

			toggleExactBroadRadio();
			
			$('.tooltip').html(getText('text_added'));
			
			setTimeout(function() {
				$('.tooltip').animate({
				   opacity: 0,
				   top: '-=80',
				}, 500, function() {
					$('.tooltip').remove();	
				});
			}, 1000);			
		});

 
	 
		$('.buttons .h-bars, .buttons .pie, .buttons .bars, .buttons .lines').on('click', function (e) {
			
			$('.title *').hide();
			
			var elem = $(this).attr('class');
			
			$('.buttons a, #graph-'+elem ).siblings().removeClass('active');
			
			$('.buttons .'+elem + ', #graph-'+elem ).addClass('active');
		
			var prevLabelHeight = $('#graph ul.labels').innerHeight();

			if ( elem == 'pie' || elem == 'h-bars' ) {
			
				$('#graph ul.labels').css('height','0px');
			} else {
			
				$('#graph ul.labels').css('height','auto');
			}
			
			var currLabelHeight = $('#graph ul.labels').innerHeight();
			
			$('.title-'+elem).show();
			
			// If the label container changed its height, update the graph:
			if (currLabelHeight != prevLabelHeight) {
				graph.update();
			}
			
			e.preventDefault();
		});	
		

		var previousPoint = null;

		// Line graph tooltips
		$('#graph-lines').on('plothover', function (event, pos, item) {
			if (item) {
				if (previousPoint != item.dataIndex) {
				
					previousPoint = item.dataIndex;
					$('#tooltip').remove();
					
					var timestamp = item.datapoint[0],
						total = item.datapoint[1];

					var label = (typeof item.series.label != 'undefined')? item.series.label : ''; 
					
					showTooltip(makeTooltip(label, total, timestamp));
				}
			} else {
			
				$('#tooltip').remove();
				previousPoint = null;
			}
		});

		// Bar graph tooltips 
		$(document).on('hover', '.bar-label', function() {

			var data		= $(this).find('.data').text();
			var dataArray	= data.split("-");

			var label 		= (dataArray[2] != '')? dataArray[2] : ''; 
			var total		= dataArray[0];
			var timestamp	= dataArray[1];

			showTooltip(makeTooltip(label, total, timestamp));
		 
		}).delegate('.bar-label', 'mouseleave', function() {
		
			$('.tooltip').remove();
		});
		
		
		// Pie and Horizontal bar graph tooltips
		$('#graph-pie, #graph-h-bars').on('plothover', function (event, pos, item) {
			
			var total;
			var percent = '';
				
			if (item) {	

				var dateStart	= standardToCalendarDate($('input[name=filter_date_start]').val());
				var dateEnd		= standardToCalendarDate($('input[name=filter_date_end]').val());
				
				switch ($(this).attr('id')) {
					
					case 'graph-pie':
					
						if (previousPoint != item.series.label) {
						
							$('#tooltip').remove();
							
							previousPoint	= item.series.label;	
							total			= item.series.data[0][1];
							percent			= '(' + Math.round(item.series.percent) + '%)';
							
							showTooltip(getText('text_hbar_pie_label',[total, percent, dateStart, dateEnd]));
						}
						break;
					
					case 'graph-h-bars':
					
						if (previousPoint != item.dataIndex) {
						
							previousPoint	= item.dataIndex;	
							total			= item.datapoint[0];

							showTooltip(getText('text_hbar_pie_label',[total, percent, dateStart, dateEnd]));
						}
						break;
				}

			} else {
				$('#tooltip').remove();
				previousPoint = null;
			}
		
		});
		
	});


// ******************  End Events  ********************* 
// ***************************************************** 

 