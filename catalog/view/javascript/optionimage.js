/*
Option Image by MarketInSG (http://www.MarketInSG.com)
*/
$(function() {
    $('.hoverimage').click(
        function() {
			var newsrc = $(this).attr('rel');
			var arr = newsrc.split('!');
			$('#image').attr({src : ''+arr[0]+''});
			$('.image .colorbox').attr({href : ''+arr[1]+''});	
        }
    );
});

$(function(){
    $('.options .option select').change(
        function() {
            var newsrc = $(this).find('option:selected').attr('rel');
			var arr = newsrc.split('!');
            if(newsrc.indexOf('no_image') > 0) {
                // do nothing!
            } else {
                $('#image').attr({src : ''+arr[0]+''});
				$('.image .colorbox').attr({href : ''+arr[1]+''});
            }
        }
    );
});