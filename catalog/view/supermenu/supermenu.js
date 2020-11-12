function superdropdownalign() {
	$('#supermenu ul > li > a + div').each(function(index, element) {	
		
		var supermenu = $('#supermenu').offset();
		var ddown = $(this).parent().offset();
		
		i = (ddown.left + $(this).outerWidth()) - (supermenu.left + $('#supermenu').outerWidth('false'));
		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 4) + 'px');
		}
		y = ddown.left - supermenu.left;
		z = $('#supermenu').outerWidth('false') - (200 + y);
		if($(this).find('.inflyouttoright').outerWidth('false') > z ) {
		$(this).find('.inflyouttoright').css('width', (z - 25) + 'px');
		}
		
	});
}
$(document).ready(function() {
    superdropdownalign();
});