$(document).ready(function() {
    var settings = $.unserialize( $( '#xxytvm-settings' ).attr( 'rel' ) );
    $( '.product-video a' ).each( function(){
        if( this.href.match( 'youtube.com' ) )
        {
            var href = this.href.replace(/watch\?v=/g, "embed/") + '?wmode=transparent';
            if( settings.norelated )
                href += '&rel=0';
        }
        else if( this.href.match( 'vimeo.com' ) )
            var href = this.href.replace(/(vimeo\.com)/g, "player.$1/video");

        if( settings.autoplay )
            if ( /\?/.test( href ) )
                href += '&autoplay=1';
            else
            	href += '?autoplay=1';

        if( typeof $.colorbox == 'function' )
        {
            $( this ).colorbox({
                iframe: true,
                innerWidth: settings.width,
                innerHeight: settings.height,
                href: href,
            });
        }
        else if( typeof $.fancybox == 'function' )
        {
            $( this ).fancybox({
                type: 'iframe',
                width: Number( settings.width ),
                height: Number( settings.height ),
                href: href,
                autoScale: false
            });
        }
    });
});
/**
 * $.unserialize
 *
 * Takes a string in format "param1=value1&param2=value2" and returns an object { param1: 'value1', param2: 'value2' }. If the "param1" ends with "[]" the param is treated as an array.
 *
 * Example:
 *
 * Input:  param1=value1&param2=value2
 * Return: { param1 : value1, param2: value2 }
 *
 * Input:  param1[]=value1&param1[]=value2
 * Return: { param1: [ value1, value2 ] }
 *
 * @todo Support params like "param1[name]=value1" (should return { param1: { name: value1 } })
 */
(function($){
	$.unserialize = function(serializedString){
		var str = decodeURI(serializedString);
		var pairs = str.split('&');
		var obj = {}, p, idx, val;
		for (var i=0, n=pairs.length; i < n; i++) {
			p = pairs[i].split('=');
			idx = p[0];

			if (idx.indexOf("[]") == (idx.length - 2)) {
				// Eh um vetor
				var ind = idx.substring(0, idx.length-2)
				if (obj[ind] === undefined) {
					obj[ind] = [];
				}
				obj[ind].push(p[1]);
			}
			else {
				obj[idx] = p[1];
			}
		}
		return obj;
	};
})(jQuery);