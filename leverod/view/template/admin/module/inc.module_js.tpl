
<script type="text/javascript"> 
$(function() {

	$('ul[id^="language"] a:first').tab('show');
	
	$('div[id^="tab-module"] div[id^="language"]:first').show();
	
	// Oc <=2.0.0.0 - Language tabs inside modules 
	$('#module li a').each(function(){
		$(this).click(function(){

			var id = $(this).attr('href');
			$(id + ' ul[id^="language"] a:first').click(); // Open the first language tab
		});
	});
});
</script>



<script type="text/javascript">
$(function() {
	// Oc <=2.0.0.0 - Module tabs : each page contains multiple modules.
	// Oc >=2.0.1.0 - There is just one module form but we must call $(...).tab.('show') to display it (tabs are hidden by the class .hidden-if-gte-2-0-1-0)
	$('#module li:first-child a').tab('show');
});
</script>


<?php //if ( version_compare(VERSION, '2.0.1.0', '>=') ) { ?>
<script type="text/javascript">	

$(function() {
	if ( $('.tab-pane').length == 0 ) { //	Check whether a module is already displayed, if not, display only one module.
										//	(On Oc >= 2.0.1.0 it must be displayed only one module per page)
		addModule();
	}
});

</script>
<?php // } ?>
