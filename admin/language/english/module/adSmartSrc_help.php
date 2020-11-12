<!--
// ***************************************************
//               Advanced Smart Search   
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************
-->

<style>

.nowrap {
	white-space: nowrap;
}

@media screen and (max-width: 768px) {
	.nowrap {
		white-space: normal;
	}
	
	#user-guide .content table td {
		font-size:12px;
	} 
	
}



#user-guide { 
	font-size:15px;
}


/* Index */
.hide-index {
	position:fixed;
		z-index: 99999999; /* For displaying the button "hide index" on top */
	top:260px;
	left:0; 
	width:50px;
	display:inline-block;
	padding:5px;
	font-size:14px;
	padding-right:15px;
	color:white;
	background:#158f02;
	cursor:pointer;
	-webkit-border-top-right-radius: 10px;
	-webkit-border-bottom-right-radius: 10px;
	-moz-border-radius-topright: 10px;
	-moz-border-radius-bottomright: 10px;
	border-top-right-radius: 10px;
	border-bottom-right-radius: 10px;
	
	-webkit-box-shadow: 0px 0px 5px 0px rgba(50, 50, 50, 0.91);
	-moz-box-shadow:    0px 0px 5px 0px rgba(50, 50, 50, 0.91);
	box-shadow:         0px 0px 5px 0px rgba(50, 50, 50, 0.91);
}

.hide-index:after {
	content: "Hide index";
}

.hide-index.hidden-index:after {
	content: "Show index";
}

#user-guide .index ul, #user-guide .index li{ 
	list-style-type:none;
	padding:0;margin:0;
}


#user-guide .index > ul > li:first-child{
	margin-top:10px;
} 

#user-guide .index > ul > li{ 
	margin-bottom:20px;
}

#user-guide .index > ul > li >  ul > li{ /* H3 block */
	margin-bottom:10px;
}

#user-guide .index > ul > li >  ul > li > ul > li a{  /* H4 block */
	padding-top:2px;
	padding-bottom:2px;
}

#user-guide .index { 
	float:left; 
	width: 290px; 
	background:#F5F5F5;
	line-height: 22px;
	z-index:1000;
	padding-top:10px;
	height:auto;
}

#user-guide .index.hidden-index { 
	display:none;
}

#user-guide .index a {
	display:block;
	text-decoration:none;
	color:#3d3d3d;
}

#user-guide .index a:hover  {
	background:#EEE;
	border-right: 2px solid #CCC;
}

#user-guide .index a.selected  {
	
	border-right: 2px solid #f0f0f0;
	-webkit-box-shadow: inset 0px -2px 15px 0px rgba(3, 112, 167, 0.36);
	-moz-box-shadow:    inset 0px -2px 15px 0px rgba(3, 112, 167, 0.36);
	box-shadow:         inset 0px -2px 15px 0px rgba(3, 112, 167, 0.36);
}

#user-guide .index h2 {
	padding:0;margin:0;
	text-transform:none;
	font-size:110%;
} 

#user-guide .index h2 a{
	padding:5px;
	padding-left:20px;
	color:#1c3b52;
} 

#user-guide .index h3 {
	padding:0;margin:0;
	text-transform:none;
	font-size:90%;
} 

#user-guide .index h3 a {
	padding:4px;
	padding-left:40px;
	color:#3a7a23;
} 

#user-guide .index h4 {
	padding:0;margin:0;
	text-transform:none;
	font-size:80%;
	font-weight:normal;	
} 

#user-guide .index h4 a {
	padding:3px;
	padding-left:55px;
	color:#615f61;
} 


#user-guide .index h5 {
	padding:0;margin:0;
	text-transform:none;
	font-size:70%;
	font-weight:normal;	
} 

#user-guide .index h5 a {
	padding:2px;
	padding-left:70px;
	color:#615f61;
} 



/* Content*/

#user-guide .content { 
	line-height: 24px;
	margin-left: 310px;
	text-align:justify;
}

#user-guide .content.hidden-index { 
	margin-left: 20px;
}

#user-guide .content h2,
#user-guide .content h3,
#user-guide .content h4,
#user-guide .content h5 {
	color:#2B4F6B;
}

#user-guide .content h2 {
	padding:0;margin:0;
	margin-top:100px;
	margin-bottom:20px;
	border-bottom: 2px solid #ccc;
	text-transform:none;
	font-size:160%;

} 

#user-guide .content .start h2 {
	margin-top:0px;
	padding-top:20px;
}

#user-guide .content  h3 {
	font-size:140%;
	margin-top:50px;
}


#user-guide .content  h4 {
	font-size:120%;
	margin-top: 25px;
	margin-bottom:0px;
}

#user-guide .content  h5 {
	font-size:100%;
	margin-top:25px;
	margin-bottom:0px;
}

#user-guide .content a {
	text-decoration:underline;
}


#user-guide .content .err {
	color:#e60000;
}

#user-guide .content span.number {
	display:inline-block;
	padding:4px;
	margin-right: 5px;
	background-color: #EDEDED;
	font-size: 9px;
	color:#424242;
}

#user-guide .content table  {
	width:100%;
}

#user-guide .content table .v-align-top  {
	vertical-align:top;
}

#user-guide .content table th {
	padding:10px;
	text-align:center;
}

#user-guide .content .txt {
	font-family:"Courier New", Courier, monospace;
	margin:0;
}

#user-guide .content .red {
	color:red;
}

#user-guide .content .green {
	color:green;
}

#user-guide .content div.example {
	background:#f5f5f5;
	padding:20px;
}

#user-guide .content p.example { /* title example*/
	margin:0;
	padding:5px;
	padding-left:10px;
	font-weight:bold;
	background:#E7E7E7;
}



#user-guide .content div.example p:nth-child(1) {
	margin-top:0;
}

#user-guide #license {
	width: 650px; 
	margin:0px auto; 
	padding:10px; 
	background:#fff;
}


</style>

<div id="user-guide">

	<div class="hide-index" ></div>
	<div class="index">
		<ul>
			
			<li>
				<h2><a href="#start">Getting Started</a></h2>
				<ul>
					<li><h3><a href="#requirements">System requirements</a></h3></li>
					<li><h3><a href="#how-it-works">How the extension works</a></h3></li>
				</ul>
			</li>
			
			<li>
				<h2><a href="#general-src-optn">General Search Options</a></h2>
				<ul>
				
					<li>
						<h3><a href="#search-algorithm">Search Algorithms</a></h3>
						<ul>
							<li><h4><a href="#fast-search">Fast</a></h4></li>	
							<li><h4><a href="#default-search">Default</a></h4></li>	
						</ul>
					</li>
				
					<li>
						<h3><a href="#match-type">Match type</a></h3>
						<ul>
							<li><h4><a href="#exact-match">Exact Match</a></h4></li>	
							<li><h4><a href="#broad-match">Broad Match</a></h4></li>	
						</ul>
					</li>
					
					<li>
						<h3><a href="#relevance">Field relevance</a></h3>
						
						<ul>
							<li><h4><a href="#field-relevance-tips">Tips to improve search speed</a></h4></li>				
						</ul>

					</li>
					
					<li><h3><a href="#plurals">Plurals</a></h3></li>
					
					<li>
						<h3><a href="#partial-words">Partial word matches</a></h3>
						<ul>
							<li><h4><a href="#when-to-enable-partial-words">When to enable this option</a></h4></li>	
							<li><h4><a href="#partial-words-and-speed">Differences between the two search algorithms</a></h4></li>		
							<li><h4><a href="#partial-word-tips">Tips</a></h4></li>	
						</ul>
					</li>
					
					<li><h3><a href="#misspellings">Misspellings</a></h3></li>
					
					<li><h3><a href="#zeroqty">Hide zero quantity products</a></h3></li>
					
					<li><h3><a href="#cache-manager">The cache manager</a></h3></li>
					
					<li>
						<h3><a href="#db-optimization">Database optimization</a></h3>
						<ul>
							<li><h4><a href="#index-tables"><b>Checkbox Index database tables</b></a></h4></li>
							<li>
								<h4><a href="#mysql-config"><b>Fine tuning MySQL configuration</b></a></h4>
								
								<ul>
									<li><h5><a href="#ft-min-word-length">Indexing and min word length</a></h5></li>
									<li><h5><a href="#ft-stop-words">FULL TEXT indexes and stop words</a></h5></li>
								</ul>
		
							</li>
							
							<li><h4><a href="#rebuild-indexes"><b>Button Rebuild indexes</b></a></h4></li>
							
						</ul>
					</li>
				</ul>
			</li>
			
			
			<li>
				<h2><a href="#live-src-optn">Live Search Options &amp; Style</a></h2>
				<ul>
					<li>
					
						<h3><a href="#refresh-mode">Refresh mode</a></h3>
						
						<ul>
							<li><h4><a href="#refresh-after-each-word"><b>After each word typed</b></a></h4></li>
							<li><h4><a href="#refresh-after-each-char"><b>After each character typed</b></a></h4></li>
						</ul>
					</li>
					
					<li><h3><a href="#live-src-style">Live search style</a></h3></li>
				</ul>
			</li>
	
		</ul>
		
		<br /> <!-- Without this, urls displayed on the browser status bar would cover the last index line -->
	</div>
	
	


	<div class="content">

		<div class="start">
			<h2><a name="start"></a>Getting Started</h2>
		

			<a name="requirements"></a>
		
			<div class="requirements">

				<h3>System requirements</h3>

				<p>You need <b>vQmod</b> in order to use this extension (<a href="http://code.google.com/p/vqmod/">click here to know what vQmod is</a>). If your system doesn't have vQmod installed, follow these instructions to install it:</p>
				<p><a href="http://code.google.com/p/vqmod/wiki/Install_OpenCart">http://code.google.com/p/vqmod/wiki/Install_OpenCart</a>.</p>

			</div>


			<div class="how-it-works">
				<h3><a name="how-it-works"></a>How it works</h3>
				
				<p>Advanced Smart Search is the full-featured search engine that extends and improves the default Opencart search engine. It provides more reliable and accurate results that really helps customers to quickly find what they are looking for.</p>
				
				<p>Its algorithm searches for products matching the query by scanning up to <b>20 product fields</b>, checking for <b><a href="#plurals">plurals</a></b>, <b><a href="#misspellings">misspelled words</a></b> and combining the <b><a href="#relevance">relevance</a></b> of the selected product fields with other parameters like the number and position of keywords in each product field, in the same way the big search engines like <b>Google™</b> or <b>Yahoo™</b> do.</p>
				
				<p>To further improve the overall performance, a <b><a href="#cache-manager">Cache System</a></b> saves the searches, making all the subsequent queries much faster.</p>
			
		<!--	<p>Also <b>Search by tags</b> and <b>Search by manufacturer names</b> can now benefit from the new algorithms which allow more efficient and faster searches.</p> -->



			</div>
			
		</div>
		
		 
		<div class="general-src-optn">
		
			<h2><a name="general-src-optn"></a>General Search Options</h2>

			
			<div class="search-algorithm">
				<h3><a name="search-algorithm"></a>Search Algorithms</h3>
				
				<p>Advanced Smart Search uses two different algorithms to perform searches. They are designed to provide the best performances under different configuration scenarios.</p>
				
				<h4><a name="fast-search"></a>The Fast Algorithm</h4>

				<ul>
					<li>This algorithm is suitable for small, medium and large databases. It guarantees high responsiveness and low resource consumption.</li>

					<li>When the option <a href="#partial-words-and-speed">Include partial words</a> is enabled, this algorithm is able to find <i>"words beginning with"</i>.</li>
					
					<li>It makes small cache files. For example, if the number of page results is set to 15 and the current search returns 50 results, each cache file will contain the current result page only. When users click on <i>"Page 2"</i>, a new cache file with next 15 results will be created.</li>
					
					<li>The minimum searchable word length depends on value of the variable <a href="#ft-min-word-length">ft_min_word_len</a>. This value can be changed as described <a href="#change-ft-min-word-length">here</a>.</li>
					 
					<li>All the terms of a query be searched, except for <a href="#ft-stop-words">stop words</a>. Stop words are articles, pronouns, prepositions, which are so commonly used that they have no impact on the relevancy of the search query.</li>
					
				</ul>
				
				<h4><a name="default-search"></a>The Default Algorithm</h4>
				
				<ul>
					<li>Suitable for small databases (on large databases, queries may become slow and the Live Search could result less responsive).</li>
					
					<li>When the option <a href="#partial-words-and-speed">Include partial words</a> is enabled, this algorithm is able to find <i>"words beginning with"</i> and <i>"words inside words"</i>.</li>
					
					<li>It produces bigger cache files. When caching is enabled, all result pages are cached in one single file (one file for each search query). For example, if a search returns 50 products, the resulting cache file will contain all the 50 results. The cache file will be bigger but all the subsequent page requests (<i>page 2</i>, <i>page 3</i> ecc.) will be instantly returned to clients.</li>
					
					<li>There is no size limit to minimum searchable word length.</li>
					
				</ul>
				
			</div>
		
		
		

			<div class="match-type">
				<h3><a name="match-type"></a>Match type</h3>
				
				<p>This paragraph explains how the <b>keyword match type</b> works. Match type helps to control when, and how broadly, the search engine will return product pages based on the query string.</p>
				
				<p>Advanced Smart Search manages two different match types: <b>Exact Match</b> and <b>Broad match</b>.</p>

				
				<h4><a name="exact-match"></a>Exact Match</h4>
				<p>Search results contain <b>all</b> the words in the search query, the order of words doesn't matter. Results matching exactly the search query (the distance between words is 0) are supposed to be more relevant and are displayed on top. Other results with a word distance greater than 0 are displayed by relevance order.</p>
				
				
				
				
				
				


				
				
				
				
				
				<p class="example">Example</p>
				<div class="example">
					
					<p>A database contains the products <i>iPhone</i> and <i>cover for iPhone</i> named '<b><i>Apple iPhone 5s</i></b>' and '<b><i>Cover for iPhone 5s</i></b>' (To make things easy, the only product field enabled is the product name, see the paragraph <a href="#relevance">Field relevance</a>).</p>
					
					<table>	
						<thead>
							<tr>
								<th>Database</th>
							</tr>
						</thead>
						
						<tbody>	
							<tr>
								<td>
									<table>
										<thead>
											<tr>
												<th>Product id</th>
												<th>Product name</th>
											</tr>
										</thead>
										
										<tbody>
										
											<tr>
												<td class="center">1</td>
												<td>Apple iPhone 5s</td>
											</tr>
											
											<tr>
												<td class="center">2</td>
												<td>Cover for iPhone 5s</td>
											</tr>
											
											<tr>
												<td class="center">3</td>
												<td>Two covers for iPhone 5s blue and green</td>
											</tr>
											
											
										</tbody>		
									</table>
								</td>					
							</tr>
						</tbody>	
					</table>
					
					
					<br />
					
					<table>
						<thead>
							<tr>
								<th>Search string</th>
								<th>Results</th>
								<th>Notes</th>
							</tr>
						</thead>
						
						<tbody>
						
							<tr>
								<td class="nowrap v-align-top">iphone</td>
								<td class="nowrap">
									<span class="number">1</span>Apple <span class="match">iPhone</span> 5s
									<br />
									<span class="number">2</span>Cover for <span class="match">iPhone</span> 5s</td>
								<td></td>
							</tr>
						
							<tr>
								<td class="nowrap v-align-top">apple iphone</td>
								<td class="nowrap"><span class="number">1</span><span class="match">Apple iPhone</span> 5s</td>
								<td></td>
							</tr>
							
							<tr>
								<td class="nowrap v-align-top">iphone apple</td>
								<td class="nowrap"><span class="number">1</span><span class="match">Apple iPhone</span> 5s</td>
								<td>The order of words doesn't affect the results.</td>
							</tr>
							
							<tr>
								<td class="nowrap v-align-top">apple iphone black</td>
								<td class="nowrap center">-</td>
								<td>No results because the word '<b><i>black</i></b>' doesn't appear in any product name.</td>
							</tr>
							
						</tbody>
								
					</table>

				
				</div>
				 
				<p>This option can be used in combination with <a href="#plurals">plurals</a>. When plurals are enabled, the word <i>'mobile<b>s</b>'</i> will also match <i>'mobile'</i> and will return the same result set.</p>
				
				<h4><a name="broad-match"></a>Broad Match</h4>
				<p>Results have <b>at least one or more keywords</b> contained in the search query, in any order. The search query may have words not present in any product field. Results matching exactly the search query are displayed on top. Other results are displayed by relevance order.</p>
				
				<p class="example">Example</p>
				<div class="example">

					<table>
						<thead>
							<tr>
								<th>Search string</th>
								<th>Results</th>
								<th>Notes</th>
							</tr>
						</thead>
						
						<tbody>
							<tr>
								<td class="nowrap v-align-top">apple</td>
								<td class="nowrap"><span class="number">1</span><span class="match">Apple</span> iPhone 5s</td>
								<td></td>
							</tr>
						
							<tr>
								<td class="nowrap v-align-top">iphone</td>
								<td class="nowrap"><span class="number">1</span>Apple <span class="match">iPhone</span> 5s<br /><span class="number">2</span>Cover for <span class="match">iPhone</span> 5s</td>
								<td></td>
							</tr>
							
							<tr>
								<td class="nowrap v-align-top">apple iphone black</td>
								<td class="nowrap"><span class="number">1</span><span class="match">Apple iPhone</span> 5s<br /><span class="number">2</span>Cover for <span class="match">iPhone</span> 5s</td>
								<td>Results match at least one word (Apple and iPhone) in the query string</td>
							</tr>
						</tbody>		
					</table>
						
				</div>

			</div>

			 
			<div class="relevance">

				<h3><a name="relevance"></a>Field relevance</h3>

				<p>The search is performed only within the product fields of your choice. A <b>product field</b> is a container of text stored in your database. Product fields may contain <b>keywords</b>, useful to identify products. For example, if the field <b>Product name</b> contains the word <i>'iPhone'</i>, probably the related item is an iPhone or maybe just a cover or a charger for iPhone. If your customer wants an iPhone and not a cover for iPhone, the product iPhone must be displayed before any iPhone accessory. This is where the <b>field relevance</b> comes into play.</p>
				
				
				<p>When you perform a search operation, Advanced Smart Search produces a result set that includes items matching the search query and, for each matching item, a <b>score</b>.</p> 
				
				<p>The score is a number that is calculated based on the position of each product field in the list <b>Field Relevance</b> and on other statistical information (<a href="#statistical-info">see below</a>). The total relevance of a returned search item is determined based on its score compared with other scores in the result set, items with higher scores are deemed to be more relevant to the search.</p>
				
				<p>By default, search results are returned in relevance order, so changing the field positions in the list <b>Field Relevance</b> can change the scores and then the order in which search results are returned.</p>
				
				<p>To set up the field relevance, select the checkboxes for the fields which contain the keywords useful to identify the product (for example, <b><i>Product name</i></b>, <b><i>Product tags</i></b> and <b><i>Model</i></b>) and choose which fields are more <i>relevant</i> for the search by moving them up or down in the list (move the mouse on a field and when you see the four headed arrow cursor, drag and drop the field in the desired position).</p>

				<p class="example">Example</p>
				<div class="example">

					<p>In our example, the word <i>'iPhone'</i> may be present in all or just in some of the three fields.</p>

					<p>If the search returns these three products:</p>

					<table>
						<thead>
							<tr>
								<th>Product Name</th>
								<th>Model</th>
								<th>Tags</th>
							</tr>
						</thead>
						
						<tbody>
							<tr>
								<td>Cover for <b>iPhone</b> 5s</td>
								<td>cover iP5s</td>
								<td>cover, <b>iPhone</b>, 5s</td>
							</tr>
							
							<tr>
								<td>Apple <b>iPhone</b> 5s</td>
								<td><b>iPhone</b> 5s</td>
								<td>Apple, mobiles</td>
							</tr>
							
							<tr>
								<td>Two covers for <b>iPhone</b> 5s blue and green</td>
								<td>iP5srg</td>
								<td>covers</td>
							</tr>
			
						</tbody>
								
					</table>

					<p>The field order:</p>

					<ul class="relevance-fields-sample">
						<li>1<input type="checkbox" checked="checked" disabled /><span class="arrow"></span>Product name</li>
						<li>2<input type="checkbox" checked="checked" disabled /><span class="arrow"></span>Model</li>
						<li>3<input type="checkbox" checked="checked" disabled /><span class="arrow"></span>Tags</li>
					</ul>

					<p>returns the sorted list:</p>

					<table>
						<thead>
							<tr>
								<th>Search string</th>
								<th>Results</th>
								<th>Matches</th>
							</tr>
						</thead>
						
						<tbody>
						
							<tr>
								<td rowspan="3" class="nowrap v-align-top">iphone</td>
								<td class="nowrap"><span class="number">1</span>Apple <span class="match">iPhone</span> 5s</td>
								<td>Product name, Model</td>
							</tr>
							
							<tr>	
								<td class="nowrap"><span class="number">2</span>Cover for <span class="match">iPhone</span> 5s</br></td>						
								<td>Product name, Tags</td>
							</tr>
							
							<tr>
								<td class="nowrap">	<span class="number">3</span>Two covers for <span class="match">iPhone</span> 5s blue and green</td>		
								<td>Product name</td>
							</tr>
							
						</tbody>			
					</table>
				

					<p>while the field order:</p>

					<ul class="relevance-fields-sample">
						<li>1<input type="checkbox" checked="checked" disabled /><span class="arrow"></span>Tags</li>
						<li>2<input type="checkbox" checked="checked" disabled /><span class="arrow"></span>Product name</li>
						<li>3<input type="checkbox" checked="checked" disabled /><span class="arrow"></span>Model</li>
					</ul>
					
					<p>returns the list:</p>
					
					<table>
						<thead>
							<tr>
								<th>Search string</th>
								<th>Results</th>
								<th>Matches</th>
							</tr>
						</thead>
						
						<tbody>
						
							<tr>	
								<td rowspan="3" class="nowrap v-align-top">iphone</td>
								<td class="nowrap"><span class="number">1</span>Cover for <span class="match">iPhone</span> 5s</br></td>						
								<td>Tags, Product name</td>
							</tr>
						
							<tr>
								<td class="nowrap"><span class="number">2</span>Apple <span class="match">iPhone</span> 5s</td>
								<td>Product name, Model</td>
							</tr>
												
							<tr>
								<td class="nowrap">	<span class="number">3</span>Two covers for <span class="match">iPhone</span> 5s blue and green</td>		
								<td>Product name</td>
							</tr>
							
						</tbody>			
					</table>
				
				</div>
				

				<p>	<a name="statistical-info"></a>To further improve the field relevance system, Advanced Smart Search takes into account other parameters to determine the position of a product in the result set. They include:</p>

				<ul>
					<li>The <b>number of words found</b> for each product field.</li>

					<li>The <b>position of each word into the text</b>. Words in sequence that match phrases entered by customers will carry more weight than words not in sequence. For example, if a customer searches for <i>'iPhone 5s'</i>, a product with the keywords <i>'iPhone'</i> and <i>'5s'</i> next to each other will have a higher relevancy score than a product containing the string: <i>'iPhone cover for 5s models'</i>.</li>

					<li><b>Type of words found</b>. Words can be (in order of relevancy score): <a href="#match-type"><b>exact words</b></a>, <a href="#plurals"><b>plurals/singulars</b></a>, <a href="#misspellings"><b>misspellings</b></a>. if a customer searches for <i>'iPhone'</i>, products with the keyword <i>'iPhone'</i> will rank higher than products with the plural <i>'iPhones'</i> or the misspelling <i>'iPhome'</i>.</li>
				</ul>


				<h4><a name="field-relevance-tips"></a>Tips to improve search speed</h4>

				<ul>
					<li>To speed up the database queries, select the minimum number of fields necessary to perform a good search. Also, enable the <a href="#db-optimization">database table indexing</a>.</li>

					<li>If the <a href="#cache-manager">cache manager</a> is enabled, only the first database query takes a certain amount of time, all the subsequent search results for the same keywords will be instantly returned to the clients.</li>
					
					<li>Optimize fields with a small amount of text like <i>name</i>, <i>tags</i>, ecc. instead to enable large product fields like <i>description</i>, it will speed up searches.</li>
					
					<li>Optimize texts for the fields <i>meta tag description</i> and <i>meta tag keyword</i>, useful to boost up pages in the external Search Engine Result Pages (SERPs) and also helpful to display relevant results in the Live Search.</li>			
				</ul>

			</div>

			
			<div class="plurals">
				<h3><a name="plurals"></a>Plurals</h3>
				<p>When plurals are enabled, Advanced Smart Search will also look for the inflected form of each english keyword contained in the query. When the keyword is a singular form, the search engine will perform the search for the origilal keyword and for the plural form, if the keyword is in the plural form, the search will also be made for the singular form.</p>
				
				<p>To improve the quality of search results, inflected forms carry slightly less weight than the original keywords. This ensures that products containing exact matches of keywords in the query string are displayed on top of products containing inflections for the same keywords.</p>

				<p class="example">Example</p>
				<div class="example">

					<table>
						<thead>
							<tr>
								<th>Search string</th>
								<th>Results</th>
								<th>Notes</th>
							</tr>
						</thead>
						
						<tbody>
						
							<tr>
								<td class="nowrap v-align-top">cover iphone</td>

								<td class="nowrap"><span class="number">1</span><span class="match">Cover</span> for <span class="match">iPhone</span> 5s<br /><span class="number">2</span>Two <span class="match">cover</span><b><u>s</u></b> for iPhone 5s blue and green</td>
								<td>Results with inflections (cover<b><u>s</u></b>) are displayed below results with exact matches (cover).</td>
							</tr>

						</tbody>
								
					</table>
				</div>

			</div>
			
								
			<div class="partial-words">
				<h3><a name="partial-words"></a>Partial word matches</h3>
				
				<p>This option extends results to products containing words partially matching a search term.</p>
				
				<p>While, in some cases, this option can really return more relevant results, it could also include results that have nothing to do with the search query. On large databases, is always recommended to use this option together with the <b>Fast algorithm</b>. If the search algorithm is set on <b>Default</b> and you enable this option on large databases, it might slow down SQL queries.</p>
				

				<h4><a name="when-to-enable-partial-words"></a>When to enable this option</h4>
				
				<p>Enable this option if your product main keywords are compound words (words made up of two or more words, like, <b><i><span class="match">book</span>let</i></b>, <b><i>bread<span class="match">knife</span></i></b>) and where single words (<b><i><span class="match">book</span></i></b>, <b><i><span class="match">knife</span></i></b>) might be used for searches in place of the main keywords.</p>				
				
				<p>You must be somewhat careful with this option as leaving your configuration too wide open can cause a lot of false positive results.</p>

				<h4><a name="partial-words-and-speed"></a>Differences between the two search algorithms</h4>
				
				<p>Partial word searches are performed by the two search algorithms <b>Defalut</b> and <b>Fast</b> in two slightly different ways.</p>
				
				<p><b>Fast algorithm</b>:</p>
				<ul>
					<li>is able to find "words starting with";</li>
					<li>the minimum partial word length depends on the value of the variable <a href="#ft-min-word-length"><b>ft_min_word_len</b></a>, that can be changed on VPS/Dedicated servers only;</li>
					<li>Search speed is not affected when this option is enabled.</li>
				</ul>
				
				<p><b>Default algorithm</b>:</p>
				<ul>
					<li>It can find "words starting with" and also "words inside" words;</li>
					<li>The minimum partial word length doesn't depend on any system variable, even though is always recommended to set up a partial word length greater than 3 or 4, to avoid a lot of false positive results and slow queries.</li>
					<li>Search speed can become slow on large databases.</li>
				</ul>
				
				<p>This table gives a visual explaination of the difference:</p>

				<table>
					<thead>
						<tr>
							<th>Search Algorithm</th>
							<th>Search string</th>
							<th>Matches</th>
						</tr>
					</thead>
					
					<tbody>
					
						<tr>
							<td class="nowrap"><b>Default</b></td>
							<td>phone</td>						
							<td class="nowrap">
								tele<span class="match">phone</span>s, 
								micro<span class="match">phone</span>, 
								<span class="match">phone</span>book
							</td>
						</tr>
					
						<tr>	
							<td class="nowrap"><b>Default</b></td>
							<td>book</td>
							<td class="nowrap">
								note<span class="match">book</span>,
								<span class="match">book</span>shelf,
								<span class="match">book</span>let
							</td>						
						</tr>
						
						<tr>
							<td class="nowrap"><b>Fast <span class="match" style="font-size:130%">*</span></b></td>
							<td>phone</td>
							<td class="nowrap">
								<span class="match">phone</span>book
							</td>
						</tr>
						
						<tr>	
							<td class="nowrap"><b>Fast <span class="match" style="font-size:130%">*<span></b></td>
							<td>book</td>
							<td class="nowrap">
								<span class="match">book</span>shelf,
								<span class="match">book</span>let
							</td>						
						</tr>
						
						
						
						<tr style="height:15px;background:#f3f3f3">	
							<td colspan="3"><b><span class="match" style="font-size:130%">*</span> <a href="#ft-min-word-length">ft_min_word_len</a> = 3</b></td>					
						</tr>

						
					</tbody>			
				</table>


				<h4><a name="partial-word-tips"></a>Tips</h4>
				<p>If you have products whose main keywords are compound words, rather than enabling the partial word matching, optimize product field contents. For example, if the main product keyword is <i>'bookshelf'</i>, enable fields like <b>meta description</b>, <b>meta keywords</b> or <b>tags</b> and add the words <i>'book'</i> and <i>'shelf'</i> in their contents. It will also help product pages to have a better ranking on Google.</p>
				
				
			</div>
			
			
			<div class="misspellings">
				<h3><a name="misspellings"></a>Misspellings</h3>
				<p>Up to 7% of search queries include misspellings, and the longer the query, the more likely it is to contain some misspelling. Advanced Smart Search is able to detect misspelled words within the given <b>tolerance</b>. Tolerance is the measure of how many spelling mistakes a word can contain.</p>
				
				<p class="example">Example</p>
				<div class="example">

					<p>Words with a misspelling tolerance of 20% (about 2 spelling mistakes for a 10 letter word)</p>
				
		
					<table>	
						<thead>
							<tr>
								<th>Misspelling tolerance = 20%</th>
							</tr>
						</thead>
						
						<tbody>	
							<tr>
								<td>
									<table>
										<thead>
											<tr>
												<th>Word length</th>
												<th>Misspelling Examples</th>
												<th>Match</th>
											</tr>
										</thead>
										
										<tbody>
											<tr>
												<th class="nowrap v-align-top">10 letters</th>
												<td >Bl<b class="err"><u>sd</u></b>kBerry<br />B<b class="err"><u>o</u></b>ackBer<b class="err"><u>d</u></b>y</td>
												<td class="nowrap v-align-top">BlackBerry</td>
											</tr>
										
											<tr>
												<th class="nowrap v-align-top">5 letters</th>
												<td>ap<b class="err"><u>k</u></b>le <br /><b class="err"><u>e</u></b>pple</td>
												<td class="nowrap v-align-top">Apple</td>
												
											</tr>

										</tbody>		
									</table>
									
									
								</td>					
							</tr>
						</tbody>	
					</table>


				</div>
				
				<p>When both the options <b><a href="#exact-match">Exact Match</a></b> and <b>Misspellings</b> are enabled, misspelled words will not be detected.</p>
				

			</div>

			 
			 
			
			
			<div class="zeroqty">
				<h3><a name="zeroqty"></a>Hide zero quantity products</h3>
				<p>When this option is enabled it allows to exclude out of stock products from searches (Products whose quantity is <= 0).</p>		
			</div>

			 			
			 
			<div class="cache-manager">
				<h3><a name="cache-manager"></a>The cache manager</h3>
				
				<p>Advanced Smart Search includes a reliable and efficient cache system that reduces the database query time to 0. Only the first time a customer does a search, it takes a certain amount of time. Results are then saved and when someone performs the same search, results are instantly delivered to the client.</p>
				
				<p>Cache results are automatically refreshed at the given <b>update frequency</b> to ensure synchronization with the database. The <em>Cache update frequency</em> defines the time period (hourly, daily, weekly, monthly, yearly) in which Advanced Smart Search updates the search cache.</p>

				<p>You can also manually control the cache by the two buttons, <b>Update cache</b> and <b>Clear cache</b>.</p>

				<p>There are a couple of situations in which the cache should be manually updated:</p>
				<ul>
					<li>
						<p><b>When you often add new products but you set the update frequency on a long period of time</b>. If you add new products <b>every day</b>, make sure to set the cache update frequency to <b>daily</b>. If not, the cache will return results without any new product. After the cache lifetime expires, new products that match the query string will be automatically added to the old cached results.</p>
					</li>
					
					<li>
						<p><b>When you change any of the options in the General Search Options tab</b>. Every time you change the match type, the field relevance, ecc., the same search query returns a different result set. Thus, the new settings will not be applied to old cached results. For this reason, if the cache manager is enabled and you forget to manually update it, when you save the new settings a pop-up window will ask you if you want to clear or update the old cache.</p>
					</li>
				</ul>
				
			</div>

			  
			<div class="db-optimization">
				<h3><a name="db-optimization"></a>Database optimization</h3>
				
				<p>On large databases, search operations can become very slow without indexing tables. On small-medium databases also, some configurations may slow down the server response time and that could negatively impact features requiring a high responsiveness (like the Live Search).</p>
				
				<p>The next paragraphs explain how to setup your MySQL database and server to speed up query response times. </p>

				<h4><a name="index-tables"></a>Checkbox "Index database tables"</h4> 
				
				<p>If you are not familiar with databases, think of of a database index as an index in a book. If you have a book about dogs and you are looking for the section on <i>Golden Retrievers</i>, then why would you flip through the entire book – which is the equivalent of a full table scan in database terminology – when you can just go to the index at the back of the book, which will tell you the exact pages where you can find information on <i>Golden Retrievers</i>. Similarly, as a book index contains a page number, a database index contains a pointer to the row containing the value that you are searching for in your SQL.</p>
								
				<p>A database index is a data structure that improves search operations on a database table. Indexes are used to quickly locate data without having to search every row in a database table every time the table is accessed. Indexes can be created using one or more columns of a database table, they are a copy of these columns of data and they can be searched very efficiently.</p>
						
				<p>The downside of indexes is that they require additional space on the disk to maintain the extra copy of data, so the larger a table is, the larger indexes related to that table will be.</p>
				
				<p>Another performance hit with indexes is the fact that whenever you add, delete, or update rows in the corresponding table (for example when inserting/updating/deleting product pages), the same operations will have to be done to your index. Remember that an index needs to contain the same up to the minute data as whatever is in the table column(s) that the index covers.</p>
				
				<p>Index database tables if you need faster searches and if you don't add/update products too often. To create the FULL TEXT indexes, just select the checkbox <b>Index database tables</b> and click on <b>Save</b>.</p>		
				
				<p>When tables are indexed, the search engine behaviour is slight different when searches includes <a href="#ft-min-word-length"><b>words with a length lower than a certain value</b></a> and <a href="#ft-stop-words"><b>stop words</b></a>. If searches seem slow or give inaccurate results for some common words such as "the" or "and", even when FULL TEXT indexes are enabled, it may be related to the MySQL variables <a href="#ft-min-word-length"><b>ft_min_word_len</b></a> and <a href="#ft-stop-words"><b>ft_stopword_file</b></a>. This topic is fully explained in the pharagraph <a href="#mysql-config"><b>Fine tuning MySQL configuration</b></a>.</p>
				
				

				
				
				<h4><a name="mysql-config"></a>Fine tuning MySQL configuration</h4>
				
				<p>If your website is hosted on a <b>Virtual Private Server (VPS)</b> or on a <b>Dedicated server</b> and you have a <b>root access</b>, you can change settings in the configuration file.</p>
				
				<p><b>It is strongly recommended to backup your MySQL database before making any changes to your database. We are not responsible of any data loss.</b></p>
				
				<p>On Shared servers the following settings cannot be changed. For more info contact your hosting provider.</p>
				
				<p>There are two MySQL variables that you might want to modify to alter the default search behaviour, <a href="#ft-min-word-length"><b>ft_min_word_len</b></a> and <a href="#ft-stop-words"><b>ft_stopword_file</b></a>. They can help to provide more accurate and faster results.</p>
				
				
				<p>On Linux, Unix and Mac, the configuration file that contains these variables is <b>my.cnf</b>, on Windows is <b>my.ini</b>, refer to this page <a target="_blank" href="https://dev.mysql.com/doc/refman/5.1/en/option-files.html">https://dev.mysql.com/doc/refman/5.1/en/option-files.html</a> to find the exact file location.</p>
				
				

				
				
				<h5><a name="ft-min-word-length"></a>Indexing and FULL TEXT minimum word length</h5>
				
				<p>When tables are indexed, words having a length lower than the value set in the MySQL variable <b>ft_min_word_len</b> cannot be searched by the <b><i>fast algorithm</i></b>. For those words searches can be only performed by the <b><i>default algorithm</i></b>.</p>
				
				<p>While the <b><i>fast algorithm</i></b> is optimized to use only FULL TEXT indexes and is bound to the minimum word length limit set by the variable ft_min_word_len, the <b><i>default algorithm</i></b> can bypass this limit at the cost of slower queries because it has to search every row in the database, losing the advantages of FULL TEXT indexes.</p>

				<p>On small databases and on websites hosted on shared servers (on which the value of the variable ft_min_word_len cannot be changed), the extra time necessary to scan all rows is trascurable, so both the <i>default</i> and <i>fast</i> algorithms can be used without losing much performance.</p>
				
				<p><u>On large databases is always recommended to use the <i>fast algorithm</i></u>. If you try to use the <i>default algorithm</i> on big databases and you also enable the <b><a href="#partial-words">partial word matching option</a></b>, queries can become slow, expecially when including very common words.</p>
				
				
				<a name="change-ft-min-word-length"></a>
				<p>Below you will find the instructions on how to setup the variable ft_min_word_len, if you never did it before or you don't want to mess up your MySQL configuration files, contact your hosting provider.</p>

				<p>The variable ft_min_word_len can be found within the section <b>[mysqld]</b> of your my.cnf/my.ini file. If there is no variable with that name within the [mysqld] section, add it as shown in the example below.</p>
							
				<p class="example">My.cnf configuration example</p>
				<div class="example">
					<p class="txt">
						[mysqld]<br />
						port= 3306<br />
						...<br />
						log_error="mysql_error.log"<br />
						#bind-address="127.0.0.1"<br />		
						ft_stopword_file = "/path/to/your/stopword/file.txt"<br />
						<b class="green">ft_min_word_len = 2</b>
					</p>
				</div>
				
				<p>After applying the changes, <b>restart the server</b> and <b>rebuild your FULL TEXT indexes</b> by clicking on the button <a href="#rebuild-indexes"><b>Rebuild indexes</b></a>.</p>
	

	
				<h5><a name="ft-stop-words"></a>FULL TEXT indexes and stop words</h5>
				
				<p>When someone makes a search, they often type phrases containing words that show up very frequently in pages and have little to do with the information being sought. Most people don't usually search these words anyway, even if they do include them in their queries.</p>
				
				<p>These common words (such as "and" or "the") are called <b>stop words</b> and, for the reasons cited above, should be excluded from searches.</p>
				
				<p>MySQL Server automatically excludes stop words from indexes, then if tables are indexed, stop words <b>do not match if present in the search string</b>. The primary reason for not indexing stop words is to provide more accurate results and increase the search speed.</p>

				<p>MySQL uses a built-in stopword list (in English language), it can be found here:</p>
				
				<p><a target="_blank" href="http://dev.mysql.com/doc/refman/5.5/en/fulltext-stopwords.html">http://dev.mysql.com/doc/refman/5.5/en/fulltext-stopwords.html</a></p>
				
				<p>You can also define your custom stop words, for example if your website language is different from English or if you want to add/remove stop words from the built-in list. Here is a good list in several languages:</p>
				
				<p><a target="_blank" href="http://www.ranks.nl/stopwords">http://www.ranks.nl/stopwords</a></p>
	
				<p>To override the default stopword list, set the MySQL variable <b>ft_stopword_file</b>. You can find it within the section <b>[mysqld]</b> of your my.cnf/my.ini file. If there is no variable with that name within the [mysqld] section, add it as shown in the example below. The variable value can be:</p>
				
				<ul>
					<li>the path name of the file containing the stopword list (the server looks for the file in the data directory unless an absolute path name is given to specify a different directory);</li>
					<li>An empty string, which <b>will disable stopword filtering</b>.</li>
				</ul>
				
				<p>If no variable is found, the MySQL will use the built-in stopword list.</p>

				<p>After changing the value of this variable or the contents of the stopword file, <b>restart the server</b> and <b>rebuild your FULL TEXT indexes</b> by clicking on the button <a href="#rebuild-indexes"><b>Rebuild indexes</b></a>.</p>

				<p class="example">My.cnf configuration example</p>
				<div class="example">
					<p class="txt">
						[mysqld]<br />
						port= 3306<br />
						...<br />
						log_error="mysql_error.log"<br />
						#bind-address="127.0.0.1"<br />
						ft_min_word_len = 4<br />
						<b class="green">ft_stopword_file = "/path/to/your/stopword/file.txt"</b>
					</p>
				</div>
			
			<h4><a name="rebuild-indexes"></a>Button "Rebuild indexes"</h4> 
			
			<p>Every time you modify your MySQL configuration files, always remember to: <b>1) restart the server</b>, <b>2) rebuild Indexes</b>. To know how to restart the server, contact your hosting provider. To rebuild indexes, just click on the button <b>Rebuild indexes</b>. On large tables, this operation could take several time to complete.</p>
			
			<p>If you don't make any changes to your configuration files, there is no need to rebuild indexes. When you just add/modify/remove products from your store, indexes are automatically kept in sync with the tables they refer to.</p>
				 			 
				 
				
			</div>
				
		</div>
		 
		 
		 
		<div class="live-src-optn">
		
			<h2><a name="live-src-optn"></a>Live Search Options &amp; Style</h2>
		

			<div class="refresh-mode">
			
				<h3><a name="refresh-mode"></a>Refresh mode</h3>
				
				<p>As you start typing a word, the live search seeks to find products matching the current query string. You can control the number of requests sent to the server by the following two options.</p>
				
				<h4><a name="refresh-after-each-word"></a>Refresh results after each word typed</h4>
				
				<p>Choose this option if you want to send a search request only after typing an entire word. This option is recommended for:
				<ul>
					<li><b>slow servers</b>;</li>
					<li><b>big databases</b>, where each search query takes several seconds to complete;</li>
					<li><b>a too heavy search engine configuration</b>. The query complexity increases along with the number of active options. If you select the whole list of product fields, include plurals and misspellings, disable the cache and don't index the database tables, each request might take a longer to complete and therefore the Live Search will be less responsive.</li>
				</ul>
				</p>
				
				<h4><a name="refresh-after-each-char"></a>Refresh results after each character typed</h4>
				
				<p>Choose this option if you want to send a search request after each character typed. This mode provides a higher responsiveness but consumes more system resources. This option is recommended for:
				<ul>
					<li><b>fast servers</b>;</li>
					<li><b>small-medium databases</b>;</li>
					<li><b>a well balanced search engine configuration</b>.</li>
				</ul>
				</p>
				
				
			</div>


			<div class="live-src-style">
				<h3><a name="live-src-style"></a>Live search style</h3>
			</div>
			
			<p>Styling the Live Search dropdown is very easy. Advanced Smart Search comes with <b>13 ready to use styles</b>, an handy start point to building your custom style. You can display/hide product images, set a custom width for the Live Search window, change colors to all elements and set your custom text for the link "Show all results" (you can set a different text for each language installed on your website). To change element colors, click on the color code and select a new one from the color picker tool or just type a code in exadecimal format into the input field.</p>

		</div>
		
		
		

		


	</div> <!-- end content -->
	
</div> <!-- end user guide  -->