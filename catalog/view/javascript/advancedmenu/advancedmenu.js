/*! jQuery Migrate v1.2.1 | (c) 2005, 2013 jQuery Foundation, Inc. and other contributors | jquery.org/license */
jQuery.migrateMute===void 0&&(jQuery.migrateMute=!0),function(e,t,n){function r(n){var r=t.console;i[n]||(i[n]=!0,e.migrateWarnings.push(n),r&&r.warn&&!e.migrateMute&&(r.warn("JQMIGRATE: "+n),e.migrateTrace&&r.trace&&r.trace()))}function a(t,a,i,o){if(Object.defineProperty)try{return Object.defineProperty(t,a,{configurable:!0,enumerable:!0,get:function(){return r(o),i},set:function(e){r(o),i=e}}),n}catch(s){}e._definePropertyBroken=!0,t[a]=i}var i={};e.migrateWarnings=[],!e.migrateMute&&t.console&&t.console.log&&t.console.log("JQMIGRATE: Logging is active"),e.migrateTrace===n&&(e.migrateTrace=!0),e.migrateReset=function(){i={},e.migrateWarnings.length=0},"BackCompat"===document.compatMode&&r("jQuery is not compatible with Quirks Mode");var o=e("<input/>",{size:1}).attr("size")&&e.attrFn,s=e.attr,u=e.attrHooks.value&&e.attrHooks.value.get||function(){return null},c=e.attrHooks.value&&e.attrHooks.value.set||function(){return n},l=/^(?:input|button)$/i,d=/^[238]$/,p=/^(?:autofocus|autoplay|async|checked|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped|selected)$/i,f=/^(?:checked|selected)$/i;a(e,"attrFn",o||{},"jQuery.attrFn is deprecated"),e.attr=function(t,a,i,u){var c=a.toLowerCase(),g=t&&t.nodeType;return u&&(4>s.length&&r("jQuery.fn.attr( props, pass ) is deprecated"),t&&!d.test(g)&&(o?a in o:e.isFunction(e.fn[a])))?e(t)[a](i):("type"===a&&i!==n&&l.test(t.nodeName)&&t.parentNode&&r("Can't change the 'type' of an input or button in IE 6/7/8"),!e.attrHooks[c]&&p.test(c)&&(e.attrHooks[c]={get:function(t,r){var a,i=e.prop(t,r);return i===!0||"boolean"!=typeof i&&(a=t.getAttributeNode(r))&&a.nodeValue!==!1?r.toLowerCase():n},set:function(t,n,r){var a;return n===!1?e.removeAttr(t,r):(a=e.propFix[r]||r,a in t&&(t[a]=!0),t.setAttribute(r,r.toLowerCase())),r}},f.test(c)&&r("jQuery.fn.attr('"+c+"') may use property instead of attribute")),s.call(e,t,a,i))},e.attrHooks.value={get:function(e,t){var n=(e.nodeName||"").toLowerCase();return"button"===n?u.apply(this,arguments):("input"!==n&&"option"!==n&&r("jQuery.fn.attr('value') no longer gets properties"),t in e?e.value:null)},set:function(e,t){var a=(e.nodeName||"").toLowerCase();return"button"===a?c.apply(this,arguments):("input"!==a&&"option"!==a&&r("jQuery.fn.attr('value', val) no longer sets properties"),e.value=t,n)}};var g,h,v=e.fn.init,m=e.parseJSON,y=/^([^<]*)(<[\w\W]+>)([^>]*)$/;e.fn.init=function(t,n,a){var i;return t&&"string"==typeof t&&!e.isPlainObject(n)&&(i=y.exec(e.trim(t)))&&i[0]&&("<"!==t.charAt(0)&&r("$(html) HTML strings must start with '<' character"),i[3]&&r("$(html) HTML text after last tag is ignored"),"#"===i[0].charAt(0)&&(r("HTML string cannot start with a '#' character"),e.error("JQMIGRATE: Invalid selector string (XSS)")),n&&n.context&&(n=n.context),e.parseHTML)?v.call(this,e.parseHTML(i[2],n,!0),n,a):v.apply(this,arguments)},e.fn.init.prototype=e.fn,e.parseJSON=function(e){return e||null===e?m.apply(this,arguments):(r("jQuery.parseJSON requires a valid JSON string"),null)},e.uaMatch=function(e){e=e.toLowerCase();var t=/(chrome)[ \/]([\w.]+)/.exec(e)||/(webkit)[ \/]([\w.]+)/.exec(e)||/(opera)(?:.*version|)[ \/]([\w.]+)/.exec(e)||/(msie) ([\w.]+)/.exec(e)||0>e.indexOf("compatible")&&/(mozilla)(?:.*? rv:([\w.]+)|)/.exec(e)||[];return{browser:t[1]||"",version:t[2]||"0"}},e.browser||(g=e.uaMatch(navigator.userAgent),h={},g.browser&&(h[g.browser]=!0,h.version=g.version),h.chrome?h.webkit=!0:h.webkit&&(h.safari=!0),e.browser=h),a(e,"browser",e.browser,"jQuery.browser is deprecated"),e.sub=function(){function t(e,n){return new t.fn.init(e,n)}e.extend(!0,t,this),t.superclass=this,t.fn=t.prototype=this(),t.fn.constructor=t,t.sub=this.sub,t.fn.init=function(r,a){return a&&a instanceof e&&!(a instanceof t)&&(a=t(a)),e.fn.init.call(this,r,a,n)},t.fn.init.prototype=t.fn;var n=t(document);return r("jQuery.sub() is deprecated"),t},e.ajaxSetup({converters:{"text json":e.parseJSON}});var b=e.fn.data;e.fn.data=function(t){var a,i,o=this[0];return!o||"events"!==t||1!==arguments.length||(a=e.data(o,t),i=e._data(o,t),a!==n&&a!==i||i===n)?b.apply(this,arguments):(r("Use of jQuery.fn.data('events') is deprecated"),i)};var j=/\/(java|ecma)script/i,w=e.fn.andSelf||e.fn.addBack;e.fn.andSelf=function(){return r("jQuery.fn.andSelf() replaced by jQuery.fn.addBack()"),w.apply(this,arguments)},e.clean||(e.clean=function(t,a,i,o){a=a||document,a=!a.nodeType&&a[0]||a,a=a.ownerDocument||a,r("jQuery.clean() is deprecated");var s,u,c,l,d=[];if(e.merge(d,e.buildFragment(t,a).childNodes),i)for(c=function(e){return!e.type||j.test(e.type)?o?o.push(e.parentNode?e.parentNode.removeChild(e):e):i.appendChild(e):n},s=0;null!=(u=d[s]);s++)e.nodeName(u,"script")&&c(u)||(i.appendChild(u),u.getElementsByTagName!==n&&(l=e.grep(e.merge([],u.getElementsByTagName("script")),c),d.splice.apply(d,[s+1,0].concat(l)),s+=l.length));return d});var Q=e.event.add,x=e.event.remove,k=e.event.trigger,N=e.fn.toggle,T=e.fn.live,M=e.fn.die,S="ajaxStart|ajaxStop|ajaxSend|ajaxComplete|ajaxError|ajaxSuccess",C=RegExp("\\b(?:"+S+")\\b"),H=/(?:^|\s)hover(\.\S+|)\b/,A=function(t){return"string"!=typeof t||e.event.special.hover?t:(H.test(t)&&r("'hover' pseudo-event is deprecated, use 'mouseenter mouseleave'"),t&&t.replace(H,"mouseenter$1 mouseleave$1"))};e.event.props&&"attrChange"!==e.event.props[0]&&e.event.props.unshift("attrChange","attrName","relatedNode","srcElement"),e.event.dispatch&&a(e.event,"handle",e.event.dispatch,"jQuery.event.handle is undocumented and deprecated"),e.event.add=function(e,t,n,a,i){e!==document&&C.test(t)&&r("AJAX events should be attached to document: "+t),Q.call(this,e,A(t||""),n,a,i)},e.event.remove=function(e,t,n,r,a){x.call(this,e,A(t)||"",n,r,a)},e.fn.error=function(){var e=Array.prototype.slice.call(arguments,0);return r("jQuery.fn.error() is deprecated"),e.splice(0,0,"error"),arguments.length?this.bind.apply(this,e):(this.triggerHandler.apply(this,e),this)},e.fn.toggle=function(t,n){if(!e.isFunction(t)||!e.isFunction(n))return N.apply(this,arguments);r("jQuery.fn.toggle(handler, handler...) is deprecated");var a=arguments,i=t.guid||e.guid++,o=0,s=function(n){var r=(e._data(this,"lastToggle"+t.guid)||0)%o;return e._data(this,"lastToggle"+t.guid,r+1),n.preventDefault(),a[r].apply(this,arguments)||!1};for(s.guid=i;a.length>o;)a[o++].guid=i;return this.click(s)},e.fn.live=function(t,n,a){return r("jQuery.fn.live() is deprecated"),T?T.apply(this,arguments):(e(this.context).on(t,this.selector,n,a),this)},e.fn.die=function(t,n){return r("jQuery.fn.die() is deprecated"),M?M.apply(this,arguments):(e(this.context).off(t,this.selector||"**",n),this)},e.event.trigger=function(e,t,n,a){return n||C.test(e)||r("Global events are undocumented and deprecated"),k.call(this,e,t,n||document,a)},e.each(S.split("|"),function(t,n){e.event.special[n]={setup:function(){var t=this;return t!==document&&(e.event.add(document,n+"."+e.guid,function(){e.event.trigger(n,null,t,!0)}),e._data(this,n,e.guid++)),!1},teardown:function(){return this!==document&&e.event.remove(document,n+"."+e._data(this,n)),!1}}})}(jQuery,window);


/*GENERAL JAVASCRIPT Script*/
function tallestdiv(what){
 var currentTallest = 0;
jQuery(what).each(function(){
  	jQuery(this).children().each(function(i){
		if (jQuery(this).height() > currentTallest) { currentTallest = jQuery(this).height(); }
	});
});
return currentTallest;
}

jQuery(document).delegate(".filter_box dl.filters li em", "click", function(){
	   jQuery(this).siblings("a:not(.filter_del)").click();
});

jQuery(document).delegate("li.more_filters1 a", "click", function(){
	  
   var a = jQuery(this).closest("dl");
   jQuery("dd", a).toggle();
   jQuery("dd", a).each(function () {
   if (jQuery(this).height() > 250) {
      jQuery(this).height(250)
   }
   });
  return false
});
jQuery(document).delegate(".filter_box dt", "click", function(e){
   if (!jQuery(e.delegateTarget).is("img")) {
     var a = jQuery(this);
      a.closest("dl").toggleClass("closed")
   }
    return false
});

jQuery(document).delegate("a.all_filters", "click", function(){
  var jThis = jQuery(this);	  
  var hreflink=jThis.metadata().dnd;
  var a = jQuery(this).closest("dd");
  var b = jQuery(this).closest(".more_filters");
  var url = jQuery('base').attr('href')
  
  jQuery(b).prepend("<img src=\""+url+"image/advancedmenu/aloader.gif\" class=\"f_right\" />");
     jQuery(a).load(hreflink, function () {
       a.prev("dt").find("img").hide();
         if (jQuery(a).height() > 250) {
           jQuery(a).height(250)
        }
      });
    return false
}); 
var timers = {};
function delayShowData(url,is_ajax,url2) {
  clearTimeout(timers);
  timers = setTimeout(function() {
  
  if (is_ajax){ 
  
	  if (history.pushState) {
		History.pushState(null,url, url2);
	  }else{
		Ajaxmenu(url);
	  }
   }else{ 
	window.location.href =url2
  } 
   }, 500);

}
	
/*refine results*/
function refineResults(h,f,g,e){var h=h||window.event;var j=e;g=document.getElementById(g)||g;var k=jQuery(g).closest("dl");if(typeof g=="object"){g=g.getElementsByTagName("LI");var d=0;var a=null;for(i=0;i<g.length;i++){var c=(g[i].innerText||g[i].textContent).toLowerCase();if(c.indexOf("(")!=-1){c=c.substring(0,c.indexOf("("))}if(c.indexOf(f.value.toLowerCase())!=-1){g[i].style.display="block";a=g[i].getElementsByTagName("A")[0].href;d++}else{g[i].style.display="none"}}if(h){var b=h.keyCode||h.which;if(b==13&&d==1){window.location=a}}}var l=jQuery(j).height();jQuery("dd",k).each(function(){if(l>250){jQuery(this).height(250)}else{jQuery(this).height(l)}})}

/*AJAX MANAGER*/
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('(6($){"1P 1Q";f r={},g={};$.k=(6(){6 O(7,d){r[7]=Z $.k.C(7,d);a r[7]}6 10(7){5(r[7]){r[7].P(D);11 r[7]}}f 1n={O:O,10:10};a 1n})();$.k.C=6(7,d){3.h={};3.w=0;3.7=7;3.l=7;3.d=$.Q({},$.k.1o,d);5(d&&d.e&&d.e!==D&&R d.e===\'12\'&&d.e!==\'P\'){3.l=d.e}};$.k.C.J={1p:6(x,o){5(R x==\'1R\'){o=x}E 5(R x==\'12\'){o=$.Q(o||{},{x:x})}o=$.Q({},3.d,o);f K=o.1q||$.F,L=o.S||$.F,T=o.T||$.F,13=o.1r||$.F,14=(R o.j==\'12\')?o.j:$.1S(o.j||{}),b=o.1T+o.x+14,c=3,U=3.1s(b,o,L,K);5(o.M&&o.A){5(o.M){o.A=m}15(6(){1t("M 1U A 1V\'t 1W 1X D");},0)}5(3.h[b]&&o.M){a}U.b=b;o.b=b;o.T=6(4,d){f s=T.p(3,4,d);5(s===m){c.16(b)}4=q;a s};o.1q=6(4,9){c.17.p(c,3,K,4,9,b,o);4=q};o.S=6(j,9,4){c.18.p(c,3,L,j,9,4,o);4=q};o.1r=6(B,9,19){f 1a=\'\',1b=\'\';5(9!==\'1Y\'&&B){1a=B.9;1b=B.1c||B.1d}5(13){13.p(3,B,9,19,o)}E{15(6(){1t 9+\'| 9: \'+1a+\' | 1Z: \'+o.x+\' | j: \'+14+\' | 20: \'+19+\' | 21: \'+1b;},0)}B=q};5(o.e===\'P\'){$(u).1u(3.l)}5(o.e||(o.A&&3.h[b])){$.e(u,3.l,U);5(3.w<o.1v&&(!3.h[b]||!o.A)){$.1w(u,3.l)}a b}a U()},1s:6(8,o,L,K){f c=3;a 6(){5(o.1x.p(o.y||c,8,o)===m){a}c.w++;5(c.w===1){$.V.z(c.7+\'22\')}5(o.W&&g[8]){5(!g[8].G||g[8].G<0||((Z 1y().1z()-g[8].1A)<g[8].G)){c.h[8]={};15(6(){c.18.p(c,o.y||o,L,g[8].1B,\'S\',g[8],o);c.17.p(c,o.y||o,K,g[8],\'S\',8,o)},0)}E{11 g[8]}}5(!o.W||!g[8]){5(o.1C){c.h[8]=$.1D(o)}E{$.1D(o)}}a 8}},16:6(b){5(3.d.e||3.d.A){$.1w(u,3.l)}3.w--;3.h[b]=q;11 3.h[b]},23:6(){g={}},1e:6(4,9,o){5(!o.1E||(!4&&!9)){a m}f s=!!((!4||4.24===0||3.1f===o.b));4=q;a s},17:6(y,X,4,9,b,o){5(3.1e(4,9,o)){9=\'v\';o.v.p(y,4,9,o)}X.p(y,4,9,o);$.V.z(3.7+\'25\',[4,9,o]);5(o.1g){$(o.1g).z(3.7+\'1F\',[4,9,o]).z(\'1F\',[4,9,o])}3.16(b);5(!3.w){$.V.z(3.7+\'26\')}4=q},18:6(y,X,j,9,4,o){f c=3;5(3.1e(4,9,o)){4=q;a}5(o.27){$.N(3.h,6(7){5(7===o.b){a m}c.v(7)})}5(o.W&&!g[o.b]){5(!4){4={}}g[o.b]={9:4.9,1G:4.1G,1d:4.1d,1c:4.1c,1B:j,G:o.G,1A:Z 1y().1z()};5(\'1h\'1H 4){f 1i=4.1h();f H;f 1I=6(){5(H){a}H={};$.N(1i.28("\\n"),6(i,Y){f 1j=Y.1J(":");H[Y.1K(0,1j)]=Y.1K(1j+2)})};$.Q(g[o.b],{1h:6(){a 1i},29:6(7){1I();a(7 1H H)?H[7]:q}})}}X.p(y,j,9,4,o);$.V.z(3.7+\'2a\',[4,o,j]);5(o.1k){$(o.1k).z(3.7+\'1L\',[j,o]).z(\'1L\',[j,o])}4=q},1l:6(8){5(8){f s=3.h[8];5(!s&&3.d.e){s=$.1M($(u).e(3.l),6(I,i){a(I.b===8)})[0]}a s}a{h:3.h,e:(3.d.e)?$(u).e(3.l):[],w:3.w}},v:6(8){f 4;5(8){4=3.1l(8);5(4&&4.v){3.1f=8;4.v();3.1f=m}E{$(u).e(3.l,$.1M($(u).e(3.l),6(I,i){a(I!==4)}))}4=q;a}f c=3,1m=[];$.N(3.h,6(8){1m.2b(8)});$.N(1m,6(i,8){c.v(8)})},P:6(1N){$(u).1u(3.l);5(1N){3.v()}}};$.k.C.J.2c=$.k.C.J.1l;$.k.1o={1x:$.F,v:$.F,1E:D,1v:1,W:m,1C:D,1g:m,1k:m,M:D,A:m,G:-1,e:m};$.N($.k.C.J,6(n,I){5(n.1J(\'2d\')===0||!$.2e(I)){a}$.k[n]=6(7,o){5(!r[7]){5(n===\'1p\'){$.k.O(7,o)}E{a}}f 1O=2f.J.2g.p(2h,1);r[7][n].2i(r[7],1O)}})})(2j);',62,144,'|||this|xhr|if|function|name|id|status|return|xhrID|that|opts|queue|var|cache|requests||data|manageAjax|qName|false|||call|null|managed|ret||document|abort|inProgress|url|context|trigger|queueDuplicateRequests|ahr|_manager|true|else|noop|cacheTTL|parsedHeaders|fn|prototype|origCom|origSuc|preventDoubleRequests|each|create|clear|extend|typeof|success|beforeSend|ajaxFn|event|cacheResponse|origFn|headerLine|new|destroy|delete|string|origError|strData|setTimeout|_removeXHR|_complete|_success|errorStr|httpStatus|content|responseXML|responseText|_isAbort|lastAbort|domCompleteTrigger|getAllResponseHeaders|responseHeaders|delimiter|domSuccessTrigger|getData|ids|publicFns|defaults|add|complete|error|_createAjax|throw|clearQueue|maxRequests|dequeue|beforeCreate|Date|getTime|timestamp|_successData|async|ajax|abortIsNoSuccess|DOMComplete|statusText|in|parseHeaders|indexOf|substr|DOMSuccess|grep|shouldAbort|args|use|strict|object|param|type|and|can|be|both|timeout|URL|thrown|response|AjaxStart|clearCache|readyState|AjaxComplete|AjaxStop|abortOld|split|getResponseHeader|AjaxSuccess|push|getXHR|_|isFunction|Array|slice|arguments|apply|jQuery'.split('|'),0,{}))

//METADATA
;(function(e){e.extend({metadata:{defaults:{type:"class",name:"metadata",cre:/({.*})/,single:"metadata"},setType:function(b,a){this.defaults.type=b;this.defaults.name=a},get:function(b,a){a=e.extend({},this.defaults,a);if(!a.single.length)a.single="metadata";var c=e.data(b,a.single);if(c)return c;c="{}";var h=function(d){if(typeof d!="string")return d;return d=eval("("+d+")")};if(a.type=="html5"){var g={};e(b.attributes).each(function(){var d=this.nodeName;if(d.match(/^data-/))d=d.replace(/^data-/,
"");else return true;g[d]=h(this.nodeValue)})}else{if(a.type=="class"){var f=a.cre.exec(b.className);if(f)c=f[1]}else if(a.type=="elem"){if(!b.getElementsByTagName)return;f=b.getElementsByTagName(a.name);if(f.length)c=e.trim(f[0].innerHTML)}else if(b.getAttribute!=undefined)if(f=b.getAttribute(a.name))c=f;g=h(c.indexOf("{")<0?"{"+c+"}":c)}e.data(b,a.single,g);return g}}});e.fn.metadata=function(b){return e.metadata.get(this[0],b)}})(jQuery);
/** 
 * History.js jQuery Adapter
 * @author Benjamin Arthur Lupton <contact@balupton.com>
 * @copyright 2010-2011 Benjamin Arthur Lupton <contact@balupton.com>
 * @license New BSD License <http://creativecommons.org/licenses/BSD/>
 
*/
(function(e, f) {
    "use strict";
    var g = e.History = e.History || {},
        jQuery = e.jQuery;
    if (typeof g.Adapter !== 'undefined') {
        throw new Error('History.js Adapter has already been loaded...');
    }
    g.Adapter = {
        bind: function(a, b, c) {
            jQuery(a).bind(b, c)
        },
        trigger: function(a, b, c) {
            jQuery(a).trigger(b, c)
        },
        extractEventData: function(a, b, c) {
            var d = (b && b.originalEvent && b.originalEvent[a]) || (c && c[a]) || f;
            return d
        },
        onDomLoad: function(a) {
            jQuery(a)
        }
    };
    if (typeof g.init !== 'undefined') {
        g.init()
    }
})(window);

(function(g, h) {
    "use strict";
    var j = g.console || h,
        document = g.document,
        navigator = g.navigator,
        sessionStorage = g.sessionStorage || false,
        setTimeout = g.setTimeout,
        clearTimeout = g.clearTimeout,
        setInterval = g.setInterval,
        clearInterval = g.clearInterval,
        JSON = g.JSON,
        alert = g.alert,
        History = g.History = g.History || {},
        history = g.history;
    JSON.stringify = JSON.stringify || JSON.encode;
    JSON.parse = JSON.parse || JSON.decode;
    if (typeof History.init !== 'undefined') {
        throw new Error('History.js Core has already been loaded...');
    }
    History.init = function() {
        if (typeof History.Adapter === 'undefined') {
            return false
        }
        if (typeof History.initCore !== 'undefined') {
            History.initCore()
        }
        if (typeof History.initHtml4 !== 'undefined') {
            History.initHtml4()
        }
        return true
    };
    History.initCore = function() {
        if (typeof History.initCore.initialized !== 'undefined') {
            return false
        } else {
            History.initCore.initialized = true
        }
        History.options = History.options || {};
        History.options.hashChangeInterval = History.options.hashChangeInterval || 100;
        History.options.safariPollInterval = History.options.safariPollInterval || 500;
        History.options.doubleCheckInterval = History.options.doubleCheckInterval || 500;
        History.options.storeInterval = History.options.storeInterval || 1000;
        History.options.busyDelay = History.options.busyDelay || 250;
        History.options.debug = History.options.debug || false;
        History.options.initialTitle = History.options.initialTitle || document.title;
        History.intervalList = [];
        History.clearAllIntervals = function() {
            var i, il = History.intervalList;
            if (typeof il !== "undefined" && il !== null) {
                for (i = 0; i < il.length; i++) {
                    clearInterval(il[i])
                }
                History.intervalList = null
            }
        };
        History.debug = function() {
            if ((History.options.debug || false)) {
                History.log.apply(History, arguments)
            }
        };
        History.log = function() {
            var a = !(typeof j === 'undefined' || typeof j.log === 'undefined' || typeof j.log.apply === 'undefined'),
                textarea = document.getElementById('log'),
                message, i, n, args, arg;
            if (a) {
                args = Array.prototype.slice.call(arguments);
                message = args.shift();
                if (typeof j.debug !== 'undefined') {
                    j.debug.apply(j, [message, args])
                } else {
                    j.log.apply(j, [message, args])
                }
            } else {
                message = ("\n" + arguments[0] + "\n")
            }
            for (i = 1, n = arguments.length; i < n; ++i) {
                arg = arguments[i];
                if (typeof arg === 'object' && typeof JSON !== 'undefined') {
                    try {
                        arg = JSON.stringify(arg)
                    } catch (Exception) {}
                }
                message += "\n" + arg + "\n"
            }
            if (textarea) {
                textarea.value += message + "\n-----\n";
                textarea.scrollTop = textarea.scrollHeight - textarea.clientHeight
            } else if (!a) {
                alert(message)
            }
            return true
        };
        History.getInternetExplorerMajorVersion = function() {
            var a = History.getInternetExplorerMajorVersion.cached = (typeof History.getInternetExplorerMajorVersion.cached !== 'undefined') ? History.getInternetExplorerMajorVersion.cached : (function() {
                var v = 3,
                    div = document.createElement('div'),
                    all = div.getElementsByTagName('i');
                while ((div.innerHTML = '<!--[if gt IE ' + (++v) + ']><i></i><![endif]-->') && all[0]) {}
                return (v > 4) ? v : false
            })();
            return a
        };
        History.isInternetExplorer = function() {
            var a = History.isInternetExplorer.cached = (typeof History.isInternetExplorer.cached !== 'undefined') ? History.isInternetExplorer.cached : Boolean(History.getInternetExplorerMajorVersion());
            return a
        };
        History.emulated = {
            pushState: !Boolean(g.history && g.history.pushState && g.history.replaceState && !((/ Mobile\/([1-7][a-z]|(8([abcde]|f(1[0-8]))))/i).test(navigator.userAgent) || (/AppleWebKit\/5([0-2]|3[0-2])/i).test(navigator.userAgent))),
            hashChange: Boolean(!(('onhashchange' in g) || ('onhashchange' in document)) || (History.isInternetExplorer() && History.getInternetExplorerMajorVersion() < 8))
        };
        History.enabled = !History.emulated.pushState;
        History.bugs = {
            setHash: Boolean(!History.emulated.pushState && navigator.vendor === 'Apple Computer, Inc.' && /AppleWebKit\/5([0-2]|3[0-3])/.test(navigator.userAgent)),
            safariPoll: Boolean(!History.emulated.pushState && navigator.vendor === 'Apple Computer, Inc.' && /AppleWebKit\/5([0-2]|3[0-3])/.test(navigator.userAgent)),
            ieDoubleCheck: Boolean(History.isInternetExplorer() && History.getInternetExplorerMajorVersion() < 8),
            hashEscape: Boolean(History.isInternetExplorer() && History.getInternetExplorerMajorVersion() < 7)
        };
        History.isEmptyObject = function(a) {
            for (var b in a) {
                return false
            }
            return true
        };
        History.cloneObject = function(a) {
            var b, newObj;
            if (a) {
                b = JSON.stringify(a);
                newObj = JSON.parse(b)
            } else {
                newObj = {}
            }
            return newObj
        };
        History.getRootUrl = function() {
            var a = document.location.protocol + '//' + (document.location.hostname || document.location.host);
            if (document.location.port || false) {
                a += ':' + document.location.port
            }
            a += '/';
            return a
        };
        History.getBaseHref = function() {
            var a = document.getElementsByTagName('base'),
                baseElement = null,
                baseHref = '';
            if (a.length === 1) {
                baseElement = a[0];
                baseHref = baseElement.href.replace(/[^\/]+$/, '')
            }
            baseHref = baseHref.replace(/\/+$/, '');
            if (baseHref) baseHref += '/';
            return baseHref
        };
        History.getBaseUrl = function() {
            var a = History.getBaseHref() || History.getBasePageUrl() || History.getRootUrl();
            return a
        };
        History.getPageUrl = function() {
            var d = History.getState(false, false),
                stateUrl = (d || {}).url || document.location.href,
                pageUrl;
            pageUrl = stateUrl.replace(/\/+$/, '').replace(/[^\/]+$/, function(a, b, c) {
                return (/\./).test(a) ? a : a + '/'
            });
            return pageUrl
        };
        History.getBasePageUrl = function() {
            var d = document.location.href.replace(/[#\?].*/, '').replace(/[^\/]+$/, function(a, b, c) {
                return (/[^\/]$/).test(a) ? '' : a
            }).replace(/\/+$/, '') + '/';
            return d
        };
        History.getFullUrl = function(a, b) {
            var c = a,
                firstChar = a.substring(0, 1);
            b = (typeof b === 'undefined') ? true : b;
            if (/[a-z]+\:\/\//.test(a)) {} else if (firstChar === '/') {
                c = History.getRootUrl() + a.replace(/^\/+/, '')
            } else if (firstChar === '#') {
                c = History.getPageUrl().replace(/#.*/, '') + a
            } else if (firstChar === '?') {
                c = History.getPageUrl().replace(/[\?#].*/, '') + a
            } else {
                if (b) {
                    c = History.getBaseUrl() + a.replace(/^(\.\/)+/, '')
                } else {
                    c = History.getBasePageUrl() + a.replace(/^(\.\/)+/, '')
                }
            }
            return c.replace(/\#$/, '')
        };
        History.getShortUrl = function(a) {
            var b = a,
                baseUrl = History.getBaseUrl(),
                rootUrl = History.getRootUrl();
            if (History.emulated.pushState) {
                b = b.replace(baseUrl, '')
            }
            b = b.replace(rootUrl, '/');
            if (History.isTraditionalAnchor(b)) {
                b = './' + b
            }
            b = b.replace(/^(\.\/)+/g, './').replace(/\#$/, '');
            return b
        };
        History.store = {};
        History.idToState = History.idToState || {};
        History.stateToId = History.stateToId || {};
        History.urlToId = History.urlToId || {};
        History.storedStates = History.storedStates || [];
        History.savedStates = History.savedStates || [];
        History.normalizeStore = function() {
            History.store.idToState = History.store.idToState || {};
            History.store.urlToId = History.store.urlToId || {};
            History.store.stateToId = History.store.stateToId || {}
        };
        History.getState = function(a, b) {
            if (typeof a === 'undefined') {
                a = true
            }
            if (typeof b === 'undefined') {
                b = true
            }
            var c = History.getLastSavedState();
            if (!c && b) {
                c = History.createStateObject()
            }
            if (a) {
                c = History.cloneObject(c);
                c.url = c.cleanUrl || c.url
            }
            return c
        };
        History.getIdByState = function(a) {
            var b = History.extractId(a.url),
                str;
            if (!b) {
                str = History.getStateString(a);
                if (typeof History.stateToId[str] !== 'undefined') {
                    b = History.stateToId[str]
                } else if (typeof History.store.stateToId[str] !== 'undefined') {
                    b = History.store.stateToId[str]
                } else {
                    while (true) {
                        b = (new Date()).getTime() + String(Math.random()).replace(/\D/g, '');
                        if (typeof History.idToState[b] === 'undefined' && typeof History.store.idToState[b] === 'undefined') {
                            break
                        }
                    }
                    History.stateToId[str] = b;
                    History.idToState[b] = a
                }
            }
            return b
        };
        History.normalizeState = function(a) {
            var b, dataNotEmpty;
            if (!a || (typeof a !== 'object')) {
                a = {}
            }
            if (typeof a.normalized !== 'undefined') {
                return a
            }
            if (!a.data || (typeof a.data !== 'object')) {
                a.data = {}
            }
            b = {};
            b.normalized = true;
            b.title = a.title || '';
            b.url = History.getFullUrl(History.unescapeString(a.url || document.location.href));
            b.hash = History.getShortUrl(b.url);
            b.data = History.cloneObject(a.data);
            b.id = History.getIdByState(b);
            b.cleanUrl = b.url.replace(/\??\&_suid.*/, '');
            b.url = b.cleanUrl;
            dataNotEmpty = !History.isEmptyObject(b.data);
            if (b.title || dataNotEmpty) {
                b.hash = History.getShortUrl(b.url).replace(/\??\&_suid.*/, '');
                if (!/\?/.test(b.hash)) {
                    b.hash += '?'
                }
                b.hash += '&_suid=' + b.id
            }
            b.hashedUrl = History.getFullUrl(b.hash);
            if ((History.emulated.pushState || History.bugs.safariPoll) && History.hasUrlDuplicate(b)) {
                b.url = b.hashedUrl
            }
            return b
        };
        History.createStateObject = function(a, b, c) {
            var d = {
                'data': a,
                'title': b,
                'url': c
            };
            d = History.normalizeState(d);
            return d
        };
        History.getStateById = function(a) {
            a = String(a);
            var b = History.idToState[a] || History.store.idToState[a] || h;
            return b
        };
        History.getStateString = function(a) {
            var b, cleanedState, str;
            b = History.normalizeState(a);
            cleanedState = {
                data: b.data,
                title: a.title,
                url: a.url
            };
            str = JSON.stringify(cleanedState);
            return str
        };
        History.getStateId = function(a) {
            var b, id;
            b = History.normalizeState(a);
            id = b.id;
            return id
        };
        History.getHashByState = function(a) {
            var b, hash;
            b = History.normalizeState(a);
            hash = b.hash;
            return hash
        };
        History.extractId = function(a) {
            var b, parts, url;
            parts = /(.*)\&_suid=([0-9]+)$/.exec(a);
            url = parts ? (parts[1] || a) : a;
            b = parts ? String(parts[2] || '') : '';
            return b || false
        };
        History.isTraditionalAnchor = function(a) {
            var b = !(/[\/\?\.]/.test(a));
            return b
        };
        History.extractState = function(a, b) {
            var c = null,
                id, url;
            b = b || false;
            id = History.extractId(a);
            if (id) {
                c = History.getStateById(id)
            }
            if (!c) {
                url = History.getFullUrl(a);
                id = History.getIdByUrl(url) || false;
                if (id) {
                    c = History.getStateById(id)
                }
                if (!c && b && !History.isTraditionalAnchor(a)) {
                    c = History.createStateObject(null, null, url)
                }
            }
            return c
        };
        History.getIdByUrl = function(a) {
            var b = History.urlToId[a] || History.store.urlToId[a] || h;
            return b
        };
        History.getLastSavedState = function() {
            return History.savedStates[History.savedStates.length - 1] || h
        };
        History.getLastStoredState = function() {
            return History.storedStates[History.storedStates.length - 1] || h
        };
        History.hasUrlDuplicate = function(a) {
            var b = false,
                oldState;
            oldState = History.extractState(a.url);
            b = oldState && oldState.id !== a.id;
            return b
        };
        History.storeState = function(a) {
            History.urlToId[a.url] = a.id;
            History.storedStates.push(History.cloneObject(a));
            return a
        };
        History.isLastSavedState = function(a) {
            var b = false,
                newId, oldState, oldId;
            if (History.savedStates.length) {
                newId = a.id;
                oldState = History.getLastSavedState();
                oldId = oldState.id;
                b = (newId === oldId)
            }
            return b
        };
        History.saveState = function(a) {
            if (History.isLastSavedState(a)) {
                return false
            }
            History.savedStates.push(History.cloneObject(a));
            return true
        };
        History.getStateByIndex = function(a) {
            var b = null;
            if (typeof a === 'undefined') {
                b = History.savedStates[History.savedStates.length - 1]
            } else if (a < 0) {
                b = History.savedStates[History.savedStates.length + a]
            } else {
                b = History.savedStates[a]
            }
            return b
        };
        History.getHash = function() {
            var a = History.unescapeHash(document.location.hash);
            return a
        };
        History.unescapeString = function(a) {
            var b = a,
                tmp;
            return b
        };
        History.unescapeHash = function(a) {
            var b = History.normalizeHash(a);
            b = History.unescapeString(b);
            return b
        };
        History.normalizeHash = function(a) {
            var b = a.replace(/[^#]*#/, '').replace(/#.*/, '');
            return b
        };
        History.setHash = function(a, b) {
            var c, State, pageUrl;
            if (b !== false && History.busy()) {
                History.pushQueue({
                    scope: History,
                    callback: History.setHash,
                    args: arguments,
                    queue: b
                });
                return false
            }
            c = History.escapeHash(a);
            History.busy(true);
            State = History.extractState(a, true);
            if (State && !History.emulated.pushState) {
                History.pushState(State.data, State.title, State.url, false)
            } else if (document.location.hash !== c) {
                if (History.bugs.setHash) {
                    pageUrl = History.getPageUrl();
                    History.pushState(null, null, pageUrl + '#' + c, false)
                } else {
                    document.location.hash = c
                }
            }
            return History
        };
        History.escapeHash = function(a) {
            var b = History.normalizeHash(a);
            b = g.escape(b);
            if (!History.bugs.hashEscape) {
                b = b.replace(/\%21/g, '!').replace(/\%26/g, '&').replace(/\%3D/g, '=').replace(/\%3F/g, '?')
            }
            return b
        };
        History.getHashByUrl = function(a) {
            var b = String(a).replace(/([^#]*)#?([^#]*)#?(.*)/, '$2');
            b = History.unescapeHash(b);
            return b
        };
        History.setTitle = function(a) {
            var b = a.title,
                firstState;
            if (!b) {
                firstState = History.getStateByIndex(0);
                if (firstState && firstState.url === a.url) {
                    b = firstState.title || History.options.initialTitle
                }
            }
            try {
                document.getElementsByTagName('title')[0].innerHTML = b.replace('<', '&lt;').replace('>', '&gt;').replace(' & ', ' &amp; ')
            } catch (Exception) {}
            document.title = b;
            return History
        };
        History.queues = [];
        History.busy = function(a) {
            if (typeof a !== 'undefined') {
                History.busy.flag = a
            } else if (typeof History.busy.flag === 'undefined') {
                History.busy.flag = false
            }
            if (!History.busy.flag) {
                clearTimeout(History.busy.timeout);
                var b = function() {
                    var i, queue, item;
                    if (History.busy.flag) return;
                    for (i = History.queues.length - 1; i >= 0; --i) {
                        queue = History.queues[i];
                        if (queue.length === 0) continue;
                        item = queue.shift();
                        History.fireQueueItem(item);
                        History.busy.timeout = setTimeout(b, History.options.busyDelay)
                    }
                };
                History.busy.timeout = setTimeout(b, History.options.busyDelay)
            }
            return History.busy.flag
        };
        History.busy.flag = false;
        History.fireQueueItem = function(a) {
            return a.callback.apply(a.scope || History, a.args || [])
        };
        History.pushQueue = function(a) {
            History.queues[a.queue || 0] = History.queues[a.queue || 0] || [];
            History.queues[a.queue || 0].push(a);
            return History
        };
        History.queue = function(a, b) {
            if (typeof a === 'function') {
                a = {
                    callback: a
                }
            }
            if (typeof b !== 'undefined') {
                a.queue = b
            }
            if (History.busy()) {
                History.pushQueue(a)
            } else {
                History.fireQueueItem(a)
            }
            return History
        };
        History.clearQueue = function() {
            History.busy.flag = false;
            History.queues = [];
            return History
        };
        History.stateChanged = false;
        History.doubleChecker = false;
        History.doubleCheckComplete = function() {
            History.stateChanged = true;
            History.doubleCheckClear();
            return History
        };
        History.doubleCheckClear = function() {
            if (History.doubleChecker) {
                clearTimeout(History.doubleChecker);
                History.doubleChecker = false
            }
            return History
        };
        History.doubleCheck = function(a) {
            History.stateChanged = false;
            History.doubleCheckClear();
            if (History.bugs.ieDoubleCheck) {
                History.doubleChecker = setTimeout(function() {
                    History.doubleCheckClear();
                    if (!History.stateChanged) {
                        a()
                    }
                    return true
                }, History.options.doubleCheckInterval)
            }
            return History
        };
        History.safariStatePoll = function() {
            var a = History.extractState(document.location.href),
                newState;
            if (!History.isLastSavedState(a)) {
                newState = a
            } else {
                return
            }
            if (!newState) {
                newState = History.createStateObject()
            }
            History.Adapter.trigger(g, 'popstate');
            return History
        };
        History.back = function(a) {
            if (a !== false && History.busy()) {
                History.pushQueue({
                    scope: History,
                    callback: History.back,
                    args: arguments,
                    queue: a
                });
                return false
            }
            History.busy(true);
            History.doubleCheck(function() {
                History.back(false)
            });
            history.go(-1);
            return true
        };
        History.forward = function(a) {
            if (a !== false && History.busy()) {
                History.pushQueue({
                    scope: History,
                    callback: History.forward,
                    args: arguments,
                    queue: a
                });
                return false
            }
            History.busy(true);
            History.doubleCheck(function() {
                History.forward(false)
            });
            history.go(1);
            return true
        };
        History.go = function(a, b) {
            var i;
            if (a > 0) {
                for (i = 1; i <= a; ++i) {
                    History.forward(b)
                }
            } else if (a < 0) {
                for (i = -1; i >= a; --i) {
                    History.back(b)
                }
            } else {
                throw new Error('History.go: History.go requires a positive or negative integer passed.');
            }
            return History
        };
        if (History.emulated.pushState) {
            var f = function() {};
            History.pushState = History.pushState || f;
            History.replaceState = History.replaceState || f
        } else {
            History.onPopState = function(a, b) {
                var c = false,
                    newState = false,
                    currentHash, currentState;
                History.doubleCheckComplete();
                currentHash = History.getHash();
                if (currentHash) {
                    currentState = History.extractState(currentHash || document.location.href, true);
                    if (currentState) {
                        History.replaceState(currentState.data, currentState.title, currentState.url, false)
                    } else {
                        History.Adapter.trigger(g, 'anchorchange');
                        History.busy(false)
                    }
                    History.expectedStateId = false;
                    return false
                }
                c = History.Adapter.extractEventData('state', a, b) || false;
                if (c) {
                    newState = History.getStateById(c)
                } else if (History.expectedStateId) {
                    newState = History.getStateById(History.expectedStateId)
                } else {
                    newState = History.extractState(document.location.href)
                }
                if (!newState) {
                    newState = History.createStateObject(null, null, document.location.href)
                }
                History.expectedStateId = false;
                if (History.isLastSavedState(newState)) {
                    History.busy(false);
                    return false
                }
                History.storeState(newState);
                History.saveState(newState);
                History.setTitle(newState);
                History.Adapter.trigger(g, 'statechange');
                History.busy(false);
                return true
            };
            History.Adapter.bind(g, 'popstate', History.onPopState);
            History.pushState = function(a, b, c, d) {
                if (History.getHashByUrl(c) && History.emulated.pushState) {
                    throw new Error('History.js does not support states with fragement-identifiers (hashes/anchors).');
                }
                if (d !== false && History.busy()) {
                    History.pushQueue({
                        scope: History,
                        callback: History.pushState,
                        args: arguments,
                        queue: d
                    });
                    return false
                }
                History.busy(true);
                var e = History.createStateObject(a, b, c);
                if (History.isLastSavedState(e)) {
                    History.busy(false)
                } else {
                    History.storeState(e);
                    History.expectedStateId = e.id;
                    history.pushState(e.id, e.title, e.url);
                    History.Adapter.trigger(g, 'popstate')
                }
                return true
            };
            History.replaceState = function(a, b, c, d) {
                if (History.getHashByUrl(c) && History.emulated.pushState) {
                    throw new Error('History.js does not support states with fragement-identifiers (hashes/anchors).');
                }
                if (d !== false && History.busy()) {
                    History.pushQueue({
                        scope: History,
                        callback: History.replaceState,
                        args: arguments,
                        queue: d
                    });
                    return false
                }
                History.busy(true);
                var e = History.createStateObject(a, b, c);
                if (History.isLastSavedState(e)) {
                    History.busy(false)
                } else {
                    History.storeState(e);
                    History.expectedStateId = e.id;
                    history.replaceState(e.id, e.title, e.url);
                    History.Adapter.trigger(g, 'popstate')
                }
                return true
            }
        }
        if (sessionStorage) {
            try {
                History.store = JSON.parse(sessionStorage.getItem('History.store')) || {}
            } catch (err) {
                History.store = {}
            }
            History.normalizeStore()
        } else {
            History.store = {};
            History.normalizeStore()
        }
        History.Adapter.bind(g, "beforeunload", History.clearAllIntervals);
        History.Adapter.bind(g, "unload", History.clearAllIntervals);
        History.saveState(History.storeState(History.extractState(document.location.href, true)));
        if (sessionStorage) {
            History.onUnload = function() {
                var a, item;
                try {
                    a = JSON.parse(sessionStorage.getItem('History.store')) || {}
                } catch (err) {
                    a = {}
                }
                a.idToState = a.idToState || {};
                a.urlToId = a.urlToId || {};
                a.stateToId = a.stateToId || {};
                for (item in History.idToState) {
                    if (!History.idToState.hasOwnProperty(item)) {
                        continue
                    }
                    a.idToState[item] = History.idToState[item]
                }
                for (item in History.urlToId) {
                    if (!History.urlToId.hasOwnProperty(item)) {
                        continue
                    }
                    a.urlToId[item] = History.urlToId[item]
                }
                for (item in History.stateToId) {
                    if (!History.stateToId.hasOwnProperty(item)) {
                        continue
                    }
                    a.stateToId[item] = History.stateToId[item]
                }
                History.store = a;
                History.normalizeStore();
                sessionStorage.setItem('History.store', JSON.stringify(a))
            };
            History.intervalList.push(setInterval(History.onUnload, History.options.storeInterval));
            History.Adapter.bind(g, 'beforeunload', History.onUnload);
            History.Adapter.bind(g, 'unload', History.onUnload)
        }
        if (!History.emulated.pushState) {
            if (History.bugs.safariPoll) {
                History.intervalList.push(setInterval(History.safariStatePoll, History.options.safariPollInterval))
            }
            if (navigator.vendor === 'Apple Computer, Inc.' || (navigator.appCodeName || '') === 'Mozilla') {
                History.Adapter.bind(g, 'hashchange', function() {
                    History.Adapter.trigger(g, 'popstate')
                });
                if (History.getHash()) {
                    History.Adapter.onDomLoad(function() {
                        History.Adapter.trigger(g, 'hashchange')
                    })
                }
            }
        }
    };
    History.init()
})(window);
(function(c, d) {
    var e = c.History,
        State = e.getState();
    e.Adapter.bind(c, 'statechange', function() {
        var a = e.getState();
        var b = a.url;
        document.title = '';
        Ajaxmenu(a.title)
    })
})(window);



/**
  * Tipsy Facebook-style tooltip
  * Version: 0.1.7
  * Project Page:  http://onehackoranother.com/projects/jquery/tipsy/
  * Requirements:  jQuery
  * Usage Example: http://onehackoranother.com/projects/jquery/tipsy/#examples
  *
  * Author: Jason Frame
  * 
  * License: MIT License
  *
 **/ 
(function($) {
    $.fn.tipsy = function(options) {

        options = $.extend({}, $.fn.tipsy.defaults, options);
        
        return this.each(function() {
            
            var opts = $.fn.tipsy.elementOptions(this, options);
            
            $(this).hover(function() {

                $.data(this, 'cancel.tipsy', true);

                var tip = $.data(this, 'active.tipsy');
                if (!tip) {
                    tip = $('<div class="tipsy"><div class="tipsy-inner"/></div>');
                    tip.css({position: 'absolute', zIndex: 100000});
                    $.data(this, 'active.tipsy', tip);
                }

                if ($(this).attr('title') || typeof($(this).attr('original-title')) != 'string') {
                    $(this).attr('original-title', $(this).attr('title') || '').removeAttr('title');
                }

                var title;
                if (typeof opts.title == 'string') {
                    title = $(this).attr(opts.title == 'title' ? 'original-title' : opts.title);
                } else if (typeof opts.title == 'function') {
                    title = opts.title.call(this);
                }

                tip.find('.tipsy-inner')[opts.html ? 'html' : 'text'](title || opts.fallback);

                var pos = $.extend({}, $(this).offset(), {width: this.offsetWidth, height: this.offsetHeight});
                tip.get(0).className = 'tipsy'; // reset classname in case of dynamic gravity
                tip.remove().css({top: 0, left: 0, visibility: 'hidden', display: 'block'}).appendTo(document.body);
                var actualWidth = tip[0].offsetWidth, actualHeight = tip[0].offsetHeight;
                var gravity = (typeof opts.gravity == 'function') ? opts.gravity.call(this) : opts.gravity;

                switch (gravity.charAt(0)) {
                    case 'n':
                        tip.css({top: pos.top + pos.height, left: pos.left + pos.width / 2 - actualWidth / 2}).addClass('tipsy-north');
                        break;
                    case 's':
                        tip.css({top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2}).addClass('tipsy-south');
                        break;
                    case 'e':
                        tip.css({top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth}).addClass('tipsy-east');
                        break;
                    case 'w':
                        tip.css({top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width}).addClass('tipsy-west');
                        break;
                }

                if (opts.fade) {
                    tip.css({opacity: 0, display: 'block', visibility: 'visible'}).animate({opacity: 0.8});
                } else {
                    tip.css({visibility: 'visible'});
                }

            }, function() {
                $.data(this, 'cancel.tipsy', false);
                var self = this;
                setTimeout(function() {
                    if ($.data(this, 'cancel.tipsy')) return;
                    var tip = $.data(self, 'active.tipsy');
                    if (opts.fade) {
                        tip.stop().fadeOut(function() { $(this).remove(); });
                    } else {
                        tip.remove();
                    }
                }, 100);

            });
            
        });
        
    };
    
    // Overwrite this method to provide options on a per-element basis.
    // For example, you could store the gravity in a 'tipsy-gravity' attribute:
    // return $.extend({}, options, {gravity: $(ele).attr('tipsy-gravity') || 'n' });


    // (remember - do not modify 'options' in place!)
    $.fn.tipsy.elementOptions = function(ele, options) {
        return $.metadata ? $.extend({}, options, $(ele).metadata()) : options;
    };
    
    $.fn.tipsy.defaults = {
        fade: false,
        fallback: '',
        gravity: 'n',
        html: false,
        title: 'title'
    };
    
    $.fn.tipsy.autoNS = function() {
        return $(this).offset().top > ($(document).scrollTop() + $(window).height() / 2) ? 's' : 'n';
    };
    
    $.fn.tipsy.autoWE = function() {
        return $(this).offset().left > ($(document).scrollLeft() + $(window).width() / 2) ? 'e' : 'w';
    };
    
})(jQuery);

/* ===================================================
 *  jquery-sortable.js v0.9.12
 *  http://johnny.github.com/jquery-sortable/
 * ===================================================
 *  Copyright (c) 2012 Jonas von Andrian
 *  All rights reserved.
 *
 *  Redistribution and use in source and binary forms, with or without
 *  modification, are permitted provided that the following conditions are met:
 *  * Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *  * Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *  * The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 *  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 *  ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 *  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 *  DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
 *  DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 *  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 *  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 *  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 *  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 *  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * ========================================================== */


!function ( $, window, pluginName, undefined){
  var eventNames,
  containerDefaults = {
    // If true, items can be dragged from this container
    drag: true,
    // If true, items can be droped onto this container
    drop: true,
    // Exclude items from being draggable, if the
    // selector matches the item
    exclude: "",
    // If true, search for nested containers within an item
    nested: true,
    // If true, the items are assumed to be arranged vertically
    vertical: true
  }, // end container defaults
  groupDefaults = {
    // This is executed after the placeholder has been moved.
    // $closestItemOrContainer contains the closest item, the placeholder
    // has been put at or the closest empty Container, the placeholder has
    // been appended to.
    afterMove: function ($placeholder, container, $closestItemOrContainer) {
    },
    // The exact css path between the container and its items, e.g. "> tbody"
    containerPath: "",
    // The css selector of the containers
    containerSelector: "ol, ul",
    // Distance the mouse has to travel to start dragging
    distance: 0,
    // Time in milliseconds after mousedown until dragging should start.
    // This option can be used to prevent unwanted drags when clicking on an element.
    delay: 0,
    // The css selector of the drag handle
    handle: "",
    // The exact css path between the item and its subcontainers
    itemPath: "",
    // The css selector of the items
    itemSelector: "li",
    // Check if the dragged item may be inside the container.
    // Use with care, since the search for a valid container entails a depth first search
    // and may be quite expensive.
    isValidTarget: function ($item, container) {
      return true
    },
    // Executed before onDrop if placeholder is detached.
    // This happens if pullPlaceholder is set to false and the drop occurs outside a container.
    onCancel: function ($item, container, _super, event) {
    },
    // Executed at the beginning of a mouse move event.
    // The Placeholder has not been moved yet.
    onDrag: function ($item, position, _super, event) {
      $item.css(position)
    },
    // Called after the drag has been started,
    // that is the mouse button is beeing held down and
    // the mouse is moving.
    // The container is the closest initialized container.
    // Therefore it might not be the container, that actually contains the item.
    onDragStart: function ($item, container, _super, event) {
      $item.css({
        height: $item.height(),
        width: $item.width()
      })
      $item.addClass("dragged")
      $("body").addClass("dragging")
    },
    // Called when the mouse button is beeing released
    onDrop: function ($item, container, _super, event) {
      $item.removeClass("dragged").removeAttr("style")
      $("body").removeClass("dragging")
    },
    // Called on mousedown. If falsy value is returned, the dragging will not start.
    // If clicked on input element, ignore
    onMousedown: function ($item, _super, event) {
      if (!event.target.nodeName.match(/^(input|select)$/i)) {
        event.preventDefault()
        return true
      }
    },
    // Template for the placeholder. Can be any valid jQuery input
    // e.g. a string, a DOM element.
    // The placeholder must have the class "placeholder"
    placeholder: '<li class="placeholder"/>',
    // If true, the position of the placeholder is calculated on every mousemove.
    // If false, it is only calculated when the mouse is above a container.
    pullPlaceholder: true,
    // Specifies serialization of the container group.
    // The pair $parent/$children is either container/items or item/subcontainers.
    serialize: function ($parent, $children, parentIsContainer) {
      var result = $.extend({}, $parent.data())

      if(parentIsContainer)
        return [$children]
      else if ($children[0]){
        result.children = $children
      }

      delete result.subContainers
      delete result.sortable

      return result
    },
    // Set tolerance while dragging. Positive values decrease sensitivity,
    // negative values increase it.
    tolerance: 0
  }, // end group defaults
  containerGroups = {},
  groupCounter = 0,
  emptyBox = {
    left: 0,
    top: 0,
    bottom: 0,
    right:0
  },
  eventNames = {
    start: "touchstart.sortable mousedown.sortable",
    drop: "touchend.sortable touchcancel.sortable mouseup.sortable",
    drag: "touchmove.sortable mousemove.sortable",
    scroll: "scroll.sortable"
  },
  subContainerKey = "subContainers"

  /*
   * a is Array [left, right, top, bottom]
   * b is array [left, top]
   */
  function d(a,b) {
    var x = Math.max(0, a[0] - b[0], b[0] - a[1]),
    y = Math.max(0, a[2] - b[1], b[1] - a[3])
    return x+y;
  }

  function setDimensions(array, dimensions, tolerance, useOffset) {
    var i = array.length,
    offsetMethod = useOffset ? "offset" : "position"
    tolerance = tolerance || 0

    while(i--){
      var el = array[i].el ? array[i].el : $(array[i]),
      // use fitting method
      pos = el[offsetMethod]()
      pos.left += parseInt(el.css('margin-left'), 10)
      pos.top += parseInt(el.css('margin-top'),10)
      dimensions[i] = [
        pos.left - tolerance,
        pos.left + el.outerWidth() + tolerance,
        pos.top - tolerance,
        pos.top + el.outerHeight() + tolerance
      ]
    }
  }

  function getRelativePosition(pointer, element) {
    var offset = element.offset()
    return {
      left: pointer.left - offset.left,
      top: pointer.top - offset.top
    }
  }

  function sortByDistanceDesc(dimensions, pointer, lastPointer) {
    pointer = [pointer.left, pointer.top]
    lastPointer = lastPointer && [lastPointer.left, lastPointer.top]

    var dim,
    i = dimensions.length,
    distances = []

    while(i--){
      dim = dimensions[i]
      distances[i] = [i,d(dim,pointer), lastPointer && d(dim, lastPointer)]
    }
    distances = distances.sort(function  (a,b) {
      return b[1] - a[1] || b[2] - a[2] || b[0] - a[0]
    })

    // last entry is the closest
    return distances
  }

  function ContainerGroup(options) {
    this.options = $.extend({}, groupDefaults, options)
    this.containers = []

    if(!this.options.rootGroup){
      this.scrollProxy = $.proxy(this.scroll, this)
      this.dragProxy = $.proxy(this.drag, this)
      this.dropProxy = $.proxy(this.drop, this)
      this.placeholder = $(this.options.placeholder)

      if(!options.isValidTarget)
        this.options.isValidTarget = undefined
    }
  }

  ContainerGroup.get = function  (options) {
    if(!containerGroups[options.group]) {
      if(options.group === undefined)
        options.group = groupCounter ++

      containerGroups[options.group] = new ContainerGroup(options)
    }

    return containerGroups[options.group]
  }

  ContainerGroup.prototype = {
    dragInit: function  (e, itemContainer) {
      this.$document = $(itemContainer.el[0].ownerDocument)

      // get item to drag
      this.item = $(e.target).closest(this.options.itemSelector)
      this.itemContainer = itemContainer

      if(this.item.is(this.options.exclude) ||
         !this.options.onMousedown(this.item, groupDefaults.onMousedown, e)){
        return
      }

      this.setPointer(e)
      this.toggleListeners('on')

      this.setupDelayTimer()
      this.dragInitDone = true
    },
    drag: function  (e) {
      if(!this.dragging){
        if(!this.distanceMet(e) || !this.delayMet)
          return

        this.options.onDragStart(this.item, this.itemContainer, groupDefaults.onDragStart, e)
        this.item.before(this.placeholder)
        this.dragging = true
      }

      this.setPointer(e)
      // place item under the cursor
      this.options.onDrag(this.item,
                          getRelativePosition(this.pointer, this.item.offsetParent()),
                          groupDefaults.onDrag,
                          e)

      var x = e.pageX || e.originalEvent.pageX,
      y = e.pageY || e.originalEvent.pageY,
      box = this.sameResultBox,
      t = this.options.tolerance

      if(!box || box.top - t > y || box.bottom + t < y || box.left - t > x || box.right + t < x)
        if(!this.searchValidTarget())
          this.placeholder.detach()
    },
    drop: function  (e) {
      this.toggleListeners('off')

      this.dragInitDone = false

      if(this.dragging){
        // processing Drop, check if placeholder is detached
        if(this.placeholder.closest("html")[0])
          this.placeholder.before(this.item).detach()
        else
          this.options.onCancel(this.item, this.itemContainer, groupDefaults.onCancel, e)

        this.options.onDrop(this.item, this.getContainer(this.item), groupDefaults.onDrop, e)

        // cleanup
        this.clearDimensions()
        this.clearOffsetParent()
        this.lastAppendedItem = this.sameResultBox = undefined
        this.dragging = false
      }
    },
    searchValidTarget: function  (pointer, lastPointer) {
      if(!pointer){
        pointer = this.relativePointer || this.pointer
        lastPointer = this.lastRelativePointer || this.lastPointer
      }

      var distances = sortByDistanceDesc(this.getContainerDimensions(),
                                         pointer,
                                         lastPointer),
      i = distances.length

      while(i--){
        var index = distances[i][0],
        distance = distances[i][1]

        if(!distance || this.options.pullPlaceholder){
          var container = this.containers[index]
          if(!container.disabled){
            if(!this.$getOffsetParent()){
              var offsetParent = container.getItemOffsetParent()
              pointer = getRelativePosition(pointer, offsetParent)
              lastPointer = getRelativePosition(lastPointer, offsetParent)
            }
            if(container.searchValidTarget(pointer, lastPointer))
              return true
          }
        }
      }
      if(this.sameResultBox)
        this.sameResultBox = undefined
    },
    movePlaceholder: function  (container, item, method, sameResultBox) {
      var lastAppendedItem = this.lastAppendedItem
      if(!sameResultBox && lastAppendedItem && lastAppendedItem[0] === item[0])
        return;

      item[method](this.placeholder)
      this.lastAppendedItem = item
      this.sameResultBox = sameResultBox
      this.options.afterMove(this.placeholder, container, item)
    },
    getContainerDimensions: function  () {
      if(!this.containerDimensions)
        setDimensions(this.containers, this.containerDimensions = [], this.options.tolerance, !this.$getOffsetParent())
      return this.containerDimensions
    },
    getContainer: function  (element) {
      return element.closest(this.options.containerSelector).data(pluginName)
    },
    $getOffsetParent: function  () {
      if(this.offsetParent === undefined){
        var i = this.containers.length - 1,
        offsetParent = this.containers[i].getItemOffsetParent()

        if(!this.options.rootGroup){
          while(i--){
            if(offsetParent[0] != this.containers[i].getItemOffsetParent()[0]){
              // If every container has the same offset parent,
              // use position() which is relative to this parent,
              // otherwise use offset()
              // compare #setDimensions
              offsetParent = false
              break;
            }
          }
        }

        this.offsetParent = offsetParent
      }
      return this.offsetParent
    },
    setPointer: function (e) {
      var pointer = this.getPointer(e)

      if(this.$getOffsetParent()){
        var relativePointer = getRelativePosition(pointer, this.$getOffsetParent())
        this.lastRelativePointer = this.relativePointer
        this.relativePointer = relativePointer
      }

      this.lastPointer = this.pointer
      this.pointer = pointer
    },
    distanceMet: function (e) {
      var currentPointer = this.getPointer(e)
      return (Math.max(
        Math.abs(this.pointer.left - currentPointer.left),
        Math.abs(this.pointer.top - currentPointer.top)
      ) >= this.options.distance)
    },
    getPointer: function(e) {
      return {
        left: e.pageX || e.originalEvent.pageX,
        top: e.pageY || e.originalEvent.pageY
      }
    },
    setupDelayTimer: function () {
      var that = this
      this.delayMet = !this.options.delay

      // init delay timer if needed
      if (!this.delayMet) {
        clearTimeout(this._mouseDelayTimer);
        this._mouseDelayTimer = setTimeout(function() {
          that.delayMet = true
        }, this.options.delay)
      }
    },
    scroll: function  (e) {
      this.clearDimensions()
      this.clearOffsetParent() // TODO is this needed?
    },
    toggleListeners: function (method) {
      var that = this,
      events = ['drag','drop','scroll']

      $.each(events,function  (i,event) {
        that.$document[method](eventNames[event], that[event + 'Proxy'])
      })
    },
    clearOffsetParent: function () {
      this.offsetParent = undefined
    },
    // Recursively clear container and item dimensions
    clearDimensions: function  () {
      this.traverse(function(object){
        object._clearDimensions()
      })
    },
    traverse: function(callback) {
      callback(this)
      var i = this.containers.length
      while(i--){
        this.containers[i].traverse(callback)
      }
    },
    _clearDimensions: function(){
      this.containerDimensions = undefined
    },
    _destroy: function () {
      containerGroups[this.options.group] = undefined
    }
  }

  function Container(element, options) {
    this.el = element
    this.options = $.extend( {}, containerDefaults, options)

    this.group = ContainerGroup.get(this.options)
    this.rootGroup = this.options.rootGroup || this.group
    this.handle = this.rootGroup.options.handle || this.rootGroup.options.itemSelector

    var itemPath = this.rootGroup.options.itemPath
    this.target = itemPath ? this.el.find(itemPath) : this.el

    this.target.on(eventNames.start, this.handle, $.proxy(this.dragInit, this))

    if(this.options.drop)
      this.group.containers.push(this)
  }

  Container.prototype = {
    dragInit: function  (e) {
      var rootGroup = this.rootGroup

      if( !this.disabled &&
          !rootGroup.dragInitDone &&
          this.options.drag &&
          this.isValidDrag(e)) {
        rootGroup.dragInit(e, this)
      }
    },
    isValidDrag: function(e) {
      return e.which == 1 ||
        e.type == "touchstart" && e.originalEvent.touches.length == 1
    },
    searchValidTarget: function  (pointer, lastPointer) {
      var distances = sortByDistanceDesc(this.getItemDimensions(),
                                         pointer,
                                         lastPointer),
      i = distances.length,
      rootGroup = this.rootGroup,
      validTarget = !rootGroup.options.isValidTarget ||
        rootGroup.options.isValidTarget(rootGroup.item, this)

      if(!i && validTarget){
        rootGroup.movePlaceholder(this, this.target, "append")
        return true
      } else
        while(i--){
          var index = distances[i][0],
          distance = distances[i][1]
          if(!distance && this.hasChildGroup(index)){
            var found = this.getContainerGroup(index).searchValidTarget(pointer, lastPointer)
            if(found)
              return true
          }
          else if(validTarget){
            this.movePlaceholder(index, pointer)
            return true
          }
        }
    },
    movePlaceholder: function  (index, pointer) {
      var item = $(this.items[index]),
      dim = this.itemDimensions[index],
      method = "after",
      width = item.outerWidth(),
      height = item.outerHeight(),
      offset = item.offset(),
      sameResultBox = {
        left: offset.left,
        right: offset.left + width,
        top: offset.top,
        bottom: offset.top + height
      }
      if(this.options.vertical){
        var yCenter = (dim[2] + dim[3]) / 2,
        inUpperHalf = pointer.top <= yCenter
        if(inUpperHalf){
          method = "before"
          sameResultBox.bottom -= height / 2
        } else
          sameResultBox.top += height / 2
      } else {
        var xCenter = (dim[0] + dim[1]) / 2,
        inLeftHalf = pointer.left <= xCenter
        if(inLeftHalf){
          method = "before"
          sameResultBox.right -= width / 2
        } else
          sameResultBox.left += width / 2
      }
      if(this.hasChildGroup(index))
        sameResultBox = emptyBox
      this.rootGroup.movePlaceholder(this, item, method, sameResultBox)
    },
    getItemDimensions: function  () {
      if(!this.itemDimensions){
        this.items = this.$getChildren(this.el, "item").filter(":not(.placeholder, .dragged)").get()
        setDimensions(this.items, this.itemDimensions = [], this.options.tolerance)
      }
      return this.itemDimensions
    },
    getItemOffsetParent: function  () {
      var offsetParent,
      el = this.el
      // Since el might be empty we have to check el itself and
      // can not do something like el.children().first().offsetParent()
      if(el.css("position") === "relative" || el.css("position") === "absolute"  || el.css("position") === "fixed")
        offsetParent = el
      else
        offsetParent = el.offsetParent()
      return offsetParent
    },
    hasChildGroup: function (index) {
      return this.options.nested && this.getContainerGroup(index)
    },
    getContainerGroup: function  (index) {
      var childGroup = $.data(this.items[index], subContainerKey)
      if( childGroup === undefined){
        var childContainers = this.$getChildren(this.items[index], "container")
        childGroup = false

        if(childContainers[0]){
          var options = $.extend({}, this.options, {
            rootGroup: this.rootGroup,
            group: groupCounter ++
          })
          childGroup = childContainers[pluginName](options).data(pluginName).group
        }
        $.data(this.items[index], subContainerKey, childGroup)
      }
      return childGroup
    },
    $getChildren: function (parent, type) {
      var options = this.rootGroup.options,
      path = options[type + "Path"],
      selector = options[type + "Selector"]

      parent = $(parent)
      if(path)
        parent = parent.find(path)

      return parent.children(selector)
    },
    _serialize: function (parent, isContainer) {
      var that = this,
      childType = isContainer ? "item" : "container",

      children = this.$getChildren(parent, childType).not(this.options.exclude).map(function () {
        return that._serialize($(this), !isContainer)
      }).get()

      return this.rootGroup.options.serialize(parent, children, isContainer)
    },
    traverse: function(callback) {
      $.each(this.items || [], function(item){
        var group = $.data(this, subContainerKey)
        if(group)
          group.traverse(callback)
      });

      callback(this)
    },
    _clearDimensions: function  () {
      this.itemDimensions = undefined
    },
    _destroy: function() {
      var that = this;

      this.target.off(eventNames.start, this.handle);
      this.el.removeData(pluginName)

      if(this.options.drop)
        this.group.containers = $.grep(this.group.containers, function(val){
          return val != that
        })

      $.each(this.items || [], function(){
        $.removeData(this, subContainerKey)
      })
    }
  }

  var API = {
    enable: function() {
      this.traverse(function(object){
        object.disabled = false
      })
    },
    disable: function (){
      this.traverse(function(object){
        object.disabled = true
      })
    },
    serialize: function () {
      return this._serialize(this.el, true)
    },
    refresh: function() {
      this.traverse(function(object){
        object._clearDimensions()
      })
    },
    destroy: function () {
      this.traverse(function(object){
        object._destroy();
      })
    }
  }

  $.extend(Container.prototype, API)

  /**
   * jQuery API
   *
   * Parameters are
   *   either options on init
   *   or a method name followed by arguments to pass to the method
   */
  $.fn[pluginName] = function(methodOrOptions) {
    var args = Array.prototype.slice.call(arguments, 1)

    return this.map(function(){
      var $t = $(this),
      object = $t.data(pluginName)

      if(object && API[methodOrOptions])
        return API[methodOrOptions].apply(object, args) || this
      else if(!object && (methodOrOptions === undefined ||
                          typeof methodOrOptions === "object"))
        $t.data(pluginName, new Container($t, methodOrOptions))

      return this
    });
  };

}(jQuery, window, 'sortable');



/**
 * jquery.slider - Slider ui control in jQuery
 * 
 * Written by
 * Egor Khmelev (hmelyoff@gmail.com)
 *
 * Licensed under the MIT (MIT-LICENSE.txt).
 *
 * @author Egor Khmelev
 * @version 1.1.0-RELEASE ($Id$)
 * 
 * Dependencies
 * 
 * jQuery (http://jquery.com)
 * jquery.numberformatter (http://code.google.com/p/jquery-numberformatter/)
 * tmpl (http://ejohn.org/blog/javascript-micro-templating/)
 * jquery.dependClass
 * draggable
 * 
 **/

var Hashtable=function(){function n(t){var r;if(typeof t=="string")return t;if(typeof t.hashCode==e)return r=t.hashCode(),typeof r=="string"?r:n(r);if(typeof t.toString==e)return t.toString();try{return String(t)}catch(i){return Object.prototype.toString.call(t)}}function r(e,t){return e.equals(t)}function i(t,n){return typeof n.equals==e?n.equals(t):t===n}function s(e){return function(t){if(t===null)throw new Error("null is not a valid "+e);if(typeof t=="undefined")throw new Error(e+" must not be undefined")}}function a(e,t,n,r){this[0]=e,this.entries=[],this.addEntry(t,n),r!==null&&(this.getEqualityFunction=function(){return r})}function h(e){return function(t){var n=this.entries.length,r,i=this.getEqualityFunction(t);while(n--){r=this.entries[n];if(i(t,r[0]))switch(e){case f:return!0;case l:return r;case c:return[n,r[1]]}}return!1}}function p(e){return function(t){var n=t.length;for(var r=0,i=this.entries.length;r<i;++r)t[n+r]=this.entries[r][e]}}function d(e,t){var n=e.length,r;while(n--){r=e[n];if(t===r[0])return n}return null}function v(e,t){var n=e[t];return n&&n instanceof a?n:null}function m(r,i){var s=this,f=[],l={},c=typeof r==e?r:n,h=typeof i==e?i:null;this.put=function(e,t){o(e),u(t);var n=c(e),r,i,s=null;return r=v(l,n),r?(i=r.getEntryForKey(e),i?(s=i[1],i[1]=t):r.addEntry(e,t)):(r=new a(n,e,t,h),f[f.length]=r,l[n]=r),s},this.get=function(e){o(e);var t=c(e),n=v(l,t);if(n){var r=n.getEntryForKey(e);if(r)return r[1]}return null},this.containsKey=function(e){o(e);var t=c(e),n=v(l,t);return n?n.containsKey(e):!1},this.containsValue=function(e){u(e);var t=f.length;while(t--)if(f[t].containsValue(e))return!0;return!1},this.clear=function(){f.length=0,l={}},this.isEmpty=function(){return!f.length};var p=function(e){return function(){var t=[],n=f.length;while(n--)f[n][e](t);return t}};this.keys=p("keys"),this.values=p("values"),this.entries=p("getEntries"),this.remove=function(e){o(e);var n=c(e),r,i=null,s=v(l,n);return s&&(i=s.removeEntryForKey(e),i!==null&&(s.entries.length||(r=d(f,n),t(f,r),delete l[n]))),i},this.size=function(){var e=0,t=f.length;while(t--)e+=f[t].entries.length;return e},this.each=function(e){var t=s.entries(),n=t.length,r;while(n--)r=t[n],e(r[0],r[1])},this.putAll=function(t,n){var r=t.entries(),i,o,u,a,f=r.length,l=typeof n==e;while(f--)i=r[f],o=i[0],u=i[1],l&&(a=s.get(o))&&(u=n(o,a,u)),s.put(o,u)},this.clone=function(){var e=new m(r,i);return e.putAll(s),e}}var e="function",t=typeof Array.prototype.splice==e?function(e,t){e.splice(t,1)}:function(e,t){var n,r,i;if(t===e.length-1)e.length=t;else{n=e.slice(t+1),e.length=t;for(r=0,i=n.length;r<i;++r)e[t+r]=n[r]}},o=s("key"),u=s("value"),f=0,l=1,c=2;return a.prototype={getEqualityFunction:function(t){return typeof t.equals==e?r:i},getEntryForKey:h(l),getEntryAndIndexForKey:h(c),removeEntryForKey:function(e){var n=this.getEntryAndIndexForKey(e);return n?(t(this.entries,n[0]),n[1]):null},addEntry:function(e,t){this.entries[this.entries.length]=[e,t]},keys:p(0),values:p(1),getEntries:function(e){var t=e.length;for(var n=0,r=this.entries.length;n<r;++n)e[t+n]=this.entries[n].slice(0)},containsKey:h(f),containsValue:function(e){var t=this.entries.length;while(t--)if(e===this.entries[t][1])return!0;return!1}},m}();(function(e){function a(e,t,n){this.dec=e,this.group=t,this.neg=n}function f(){for(var e=0;e<u.length;e++){localeGroup=u[e];for(var n=0;n<localeGroup.length;n++)t.put(localeGroup[n],e)}}function l(e,n){t.size()==0&&f();var r=".",i=",",s="-";n==0&&(e.indexOf("_")!=-1?e=e.split("_")[1].toLowerCase():e.indexOf("-")!=-1&&(e=e.split("-")[1].toLowerCase()));var u=t.get(e);if(u){var l=o[u];l&&(r=l[0],i=l[1])}return new a(r,i,s)}var t=new Hashtable,n=["ae","au","ca","cn","eg","gb","hk","il","in","jp","sk","th","tw","us"],r=["at","br","de","dk","es","gr","it","nl","pt","tr","vn"],i=["cz","fi","fr","ru","se","pl"],s=["ch"],o=[[".",","],[",","."],[","," "],[".","'"]],u=[n,r,i,s];e.fn.formatNumber=function(t,n,r){return this.each(function(){n==null&&(n=!0),r==null&&(r=!0);var i;e(this).is(":input")?i=new String(e(this).val()):i=new String(e(this).text());var s=e.formatNumber(i,t);n&&(e(this).is(":input")?e(this).val(s):e(this).text(s));if(r)return s})},e.formatNumber=function(t,n){var n=e.extend({},e.fn.formatNumber.defaults,n),r=l(n.locale.toLowerCase(),n.isFullLocale),i=r.dec,s=r.group,o=r.neg,u="0#-,.",a="",f=!1;for(var c=0;c<n.format.length;c++){if(u.indexOf(n.format.charAt(c))!=-1){if(c==0&&n.format.charAt(c)=="-"){f=!0;continue}break}a+=n.format.charAt(c)}var h="";for(var c=n.format.length-1;c>=0;c--){if(u.indexOf(n.format.charAt(c))!=-1)break;h=n.format.charAt(c)+h}n.format=n.format.substring(a.length),n.format=n.format.substring(0,n.format.length-h.length);var p=new Number(t);return e._formatNumber(p,n,h,a,f)},e._formatNumber=function(t,n,r,i,s){var n=e.extend({},e.fn.formatNumber.defaults,n),o=l(n.locale.toLowerCase(),n.isFullLocale),u=o.dec,a=o.group,f=o.neg,c=!1;if(isNaN(t)){if(n.nanForceZero!=1)return null;t=0,c=!0}r=="%"&&(t*=100);var h="";if(n.format.indexOf(".")>-1){var p=u,d=n.format.substring(n.format.lastIndexOf(".")+1);if(n.round==1)t=new Number(t.toFixed(d.length));else{var v=t.toString();v=v.substring(0,v.lastIndexOf(".")+d.length+1),t=new Number(v)}var m=t%1,g=new String(m.toFixed(d.length));g=g.substring(g.lastIndexOf(".")+1);for(var y=0;y<d.length;y++){if(d.charAt(y)=="#"&&g.charAt(y)!="0"){p+=g.charAt(y);continue}if(d.charAt(y)=="#"&&g.charAt(y)=="0"){var b=g.substring(y);if(b.match("[1-9]")){p+=g.charAt(y);continue}break}d.charAt(y)=="0"&&(p+=g.charAt(y))}h+=p}else t=Math.round(t);var w=Math.floor(t);t<0&&(w=Math.ceil(t));var E="";n.format.indexOf(".")==-1?E=n.format:E=n.format.substring(0,n.format.indexOf("."));var S="";if(w!=0||E.substr(E.length-1)!="#"||c){var x=new String(Math.abs(w)),T=9999;E.lastIndexOf(",")!=-1&&(T=E.length-E.lastIndexOf(",")-1);var N=0;for(var y=x.length-1;y>-1;y--)S=x.charAt(y)+S,N++,N==T&&y!=0&&(S=a+S,N=0);if(E.length>S.length){var C=E.indexOf("0");if(C!=-1){var k=E.length-C,L=E.length-S.length-1;while(S.length<k){var A=E.charAt(L);A==","&&(A=a),S=A+S,L--}}}}return!S&&E.indexOf("0",E.length-1)!==-1&&(S="0"),h=S+h,t<0&&s&&i.length>0?i=f+i:t<0&&(h=f+h),n.decimalSeparatorAlwaysShown||h.lastIndexOf(u)==h.length-1&&(h=h.substring(0,h.length-1)),h=i+h+r,h},e.fn.parseNumber=function(t,n,r){n==null&&(n=!0),r==null&&(r=!0);var i;e(this).is(":input")?i=new String(e(this).val()):i=new String(e(this).text());var s=e.parseNumber(i,t);if(s){n&&(e(this).is(":input")?e(this).val(s.toString()):e(this).text(s.toString()));if(r)return s}},e.parseNumber=function(t,n){var n=e.extend({},e.fn.parseNumber.defaults,n),r=l(n.locale.toLowerCase(),n.isFullLocale),i=r.dec,s=r.group,o=r.neg,u="1234567890.-";while(t.indexOf(s)>-1)t=t.replace(s,"");t=t.replace(i,".").replace(o,"-");var a="",f=!1;if(t.charAt(t.length-1)=="%"||n.isPercentage==1)f=!0;for(var c=0;c<t.length;c++)u.indexOf(t.charAt(c))>-1&&(a+=t.charAt(c));var h=new Number(a);if(f){h/=100;var p=a.indexOf(".");if(p!=-1){var d=a.length-p-1;h=h.toFixed(d+2)}else h=h.toFixed(a.length-1)}return h},e.fn.parseNumber.defaults={locale:"us",decimalSeparatorAlwaysShown:!1,isPercentage:!1,isFullLocale:!1},e.fn.formatNumber.defaults={format:"#,###.00",locale:"us",decimalSeparatorAlwaysShown:!1,nanForceZero:!0,round:!0,isFullLocale:!1},Number.prototype.toFixed=function(t){return e._roundNumber(this,t)},e._roundNumber=function(e,t){var n=Math.pow(10,t||0),r=String(Math.round(e*n)/n);if(t>0){var i=r.indexOf(".");i==-1?(r+=".",i=0):i=r.length-(i+1);while(i<t)r+="0",i++}return r}})(jQuery),function(){var e={};this.tmpl=function t(n,r){var i=/\W/.test(n)?new Function("obj","var p=[],print=function(){p.push.apply(p,arguments);};with(obj){p.push('"+n.replace(/[\r\t\n]/g," ").split("<%").join("	").replace(/((^|%>)[^\t]*)'/g,"$1\r").replace(/\t=(.*?)%>/g,"',$1,'").split("	").join("');").split("%>").join("p.push('").split("\r").join("\\'")+"');}return p.join('');"):e[n]=e[n]||t(document.getElementById(n).innerHTML);return r?i(r):i}}(),function(e){e.baseClass=function(t){return t=e(t),t.get(0).className.match(/([^ ]+)/)[1]},e.fn.addDependClass=function(t,n){var r={delimiter:n?n:"-"};return this.each(function(){var n=e.baseClass(this);n&&e(this).addClass(n+r.delimiter+t)})},e.fn.removeDependClass=function(t,n){var r={delimiter:n?n:"-"};return this.each(function(){var n=e.baseClass(this);n&&e(this).removeClass(n+r.delimiter+t)})},e.fn.toggleDependClass=function(t,n){var r={delimiter:n?n:"-"};return this.each(function(){var n=e.baseClass(this);n&&(e(this).is("."+n+r.delimiter+t)?e(this).removeClass(n+r.delimiter+t):e(this).addClass(n+r.delimiter+t))})}}(jQuery),function(e){function t(){this._init.apply(this,arguments)}t.prototype.oninit=function(){},t.prototype.events=function(){},t.prototype.onmousedown=function(){this.ptr.css({position:"absolute"})},t.prototype.onmousemove=function(e,t,n){this.ptr.css({left:t,top:n})},t.prototype.onmouseup=function(){},t.prototype.isDefault={drag:!1,clicked:!1,toclick:!0,mouseup:!1},t.prototype._init=function(){if(arguments.length>0){this.ptr=e(arguments[0]),this.outer=e(".draggable-outer"),this.is={},e.extend(this.is,this.isDefault);var t=this.ptr.offset();this.d={left:t.left,top:t.top,width:this.ptr.width(),height:this.ptr.height()},this.oninit.apply(this,arguments),this._events()}},t.prototype._getPageCoords=function(e){return e.targetTouches&&e.targetTouches[0]?{x:e.targetTouches[0].pageX,y:e.targetTouches[0].pageY}:{x:e.pageX,y:e.pageY}},t.prototype._bindEvent=function(e,t,n){var r=this;this.supportTouches_?e.get(0).addEventListener(this.events_[t],n,!1):e.bind(this.events_[t],n)},t.prototype._events=function(){var t=this;this.supportTouches_=e.browser.webkit&&navigator.userAgent.indexOf("Mobile")!=-1,this.events_={click:this.supportTouches_?"touchstart":"click",down:this.supportTouches_?"touchstart":"mousedown",move:this.supportTouches_?"touchmove":"mousemove",up:this.supportTouches_?"touchend":"mouseup"},this._bindEvent(e(document),"move",function(e){t.is.drag&&(e.stopPropagation(),e.preventDefault(),t._mousemove(e))}),this._bindEvent(e(document),"down",function(e){t.is.drag&&(e.stopPropagation(),e.preventDefault())}),this._bindEvent(e(document),"up",function(e){t._mouseup(e)}),this._bindEvent(this.ptr,"down",function(e){return t._mousedown(e),!1}),this._bindEvent(this.ptr,"up",function(e){t._mouseup(e)}),this.ptr.find("a").click(function(){t.is.clicked=!0;if(!t.is.toclick)return t.is.toclick=!0,!1}).mousedown(function(e){return t._mousedown(e),!1}),this.events()},t.prototype._mousedown=function(t){this.is.drag=!0,this.is.clicked=!1,this.is.mouseup=!1;var n=this.ptr.offset(),r=this._getPageCoords(t);this.cx=r.x-n.left,this.cy=r.y-n.top,e.extend(this.d,{left:n.left,top:n.top,width:this.ptr.width(),height:this.ptr.height()}),this.outer&&this.outer.get(0)&&this.outer.css({height:Math.max(this.outer.height(),e(document.body).height()),overflow:"hidden"}),this.onmousedown(t)},t.prototype._mousemove=function(e){this.is.toclick=!1;var t=this._getPageCoords(e);this.onmousemove(e,t.x-this.cx,t.y-this.cy)},t.prototype._mouseup=function(t){var n=this;this.is.drag&&(this.is.drag=!1,this.outer&&this.outer.get(0)&&(e.browser.mozilla?this.outer.css({overflow:"hidden"}):this.outer.css({overflow:"visible"}),e.browser.msie&&e.browser.version=="6.0"?this.outer.css({height:"100%"}):this.outer.css({height:"auto"})),this.onmouseup(t))},window.Draggable=t}(jQuery),function(e){function t(e){return typeof e=="undefined"?!1:e instanceof Array||!(e instanceof Object)&&Object.prototype.toString.call(e)=="[object Array]"||typeof e.length=="number"&&typeof e.splice!="undefined"&&typeof e.propertyIsEnumerable!="undefined"&&!e.propertyIsEnumerable("splice")?!0:!1}function r(){return this.init.apply(this,arguments)}function i(){Draggable.apply(this,arguments)}e.slider=function(t,n){var i=e(t);return i.data("jslider")||i.data("jslider",new r(t,n)),i.data("jslider")},e.fn.slider=function(n,r){function o(e){return e!==undefined}function u(e){return e!=null}var i,s=arguments;return this.each(function(){var a=e.slider(this,n);if(typeof n=="string")switch(n){case"value":if(o(s[1])&&o(s[2])){var f=a.getPointers();u(f[0])&&u(s[1])&&(f[0].set(s[1]),f[0].setIndexOver()),u(f[1])&&u(s[2])&&(f[1].set(s[2]),f[1].setIndexOver())}else if(o(s[1])){var f=a.getPointers();u(f[0])&&u(s[1])&&(f[0].set(s[1]),f[0].setIndexOver())}else i=a.getValue();break;case"prc":if(o(s[1])&&o(s[2])){var f=a.getPointers();u(f[0])&&u(s[1])&&(f[0]._set(s[1]),f[0].setIndexOver()),u(f[1])&&u(s[2])&&(f[1]._set(s[2]),f[1].setIndexOver())}else if(o(s[1])){var f=a.getPointers();u(f[0])&&u(s[1])&&(f[0]._set(s[1]),f[0].setIndexOver())}else i=a.getPrcValue();break;case"calculatedValue":var l=a.getValue().split(";");i="";for(var c=0;c<l.length;c++)i+=(c>0?";":"")+a.nice(l[c]);break;case"skin":a.setSkin(s[1])}else!n&&!r&&(t(i)||(i=[]),i.push(a))}),t(i)&&i.length==1&&(i=i[0]),i||this};var n={settings:{from:1,to:10,step:1,smooth:!0,limits:!0,round:0,format:{format:"#,##0.##"},value:"5;7",dimension:""},className:"jslider",selector:".jslider-",template:tmpl('<span class="<%=className%>"><table><tr><td><div class="<%=className%>-bg"><i class="l"></i><i class="f"></i><i class="r"></i><i class="v"></i></div><div class="<%=className%>-pointer"></div><div class="<%=className%>-pointer <%=className%>-pointer-to"></div><div class="<%=className%>-label"><span><%=settings.from%></span></div><div class="<%=className%>-label <%=className%>-label-to"><span><%=settings.to%></span><%=settings.dimension%></div><div class="<%=className%>-value"><span></span><%=settings.dimension%></div><div class="<%=className%>-value <%=className%>-value-to"><span></span><%=settings.dimension%></div><div class="<%=className%>-scale"><%=scale%></div></td></tr></table></span>')};r.prototype.init=function(t,r){this.settings=e.extend(!0,{},n.settings,r?r:{}),this.inputNode=e(t).hide(),this.settings.interval=this.settings.to-this.settings.from,this.settings.value=this.inputNode.attr("value"),this.settings.calculate&&e.isFunction(this.settings.calculate)&&(this.nice=this.settings.calculate),this.settings.onstatechange&&e.isFunction(this.settings.onstatechange)&&(this.onstatechange=this.settings.onstatechange),this.is={init:!1},this.o={},this.create()},r.prototype.onstatechange=function(){},r.prototype.create=function(){var t=this;this.domNode=e(n.template({className:n.className,settings:{from:this.nice(this.settings.from),to:this.nice(this.settings.to),dimension:this.settings.dimension},scale:this.generateScale()})),this.inputNode.after(this.domNode),this.drawScale(),this.settings.skin&&this.settings.skin.length>0&&this.setSkin(this.settings.skin),this.sizes={domWidth:this.domNode.width(),domOffset:this.domNode.offset()},e.extend(this.o,{pointers:{},labels:{0:{o:this.domNode.find(n.selector+"value").not(n.selector+"value-to")},1:{o:this.domNode.find(n.selector+"value").filter(n.selector+"value-to")}},limits:{0:this.domNode.find(n.selector+"label").not(n.selector+"label-to"),1:this.domNode.find(n.selector+"label").filter(n.selector+"label-to")}}),e.extend(this.o.labels[0],{value:this.o.labels[0].o.find("span")}),e.extend(this.o.labels[1],{value:this.o.labels[1].o.find("span")}),t.settings.value.split(";")[1]||(this.settings.single=!0,this.domNode.addDependClass("single")),t.settings.limits||this.domNode.addDependClass("limitless"),this.domNode.find(n.selector+"pointer").each(function(e){var n=t.settings.value.split(";")[e];if(n){t.o.pointers[e]=new i(this,e,t);var r=t.settings.value.split(";")[e-1];r&&new Number(n)<new Number(r)&&(n=r),n=n<t.settings.from?t.settings.from:n,n=n>t.settings.to?t.settings.to:n,t.o.pointers[e].set(n,!0)}}),this.o.value=this.domNode.find(".v"),this.is.init=!0,e.each(this.o.pointers,function(e){t.redraw(this)}),function(t){e(window).resize(function(){t.onresize()})}(this)},r.prototype.setSkin=function(e){this.skin_&&this.domNode.removeDependClass(this.skin_,"_"),this.domNode.addDependClass(this.skin_=e,"_")},r.prototype.setPointersIndex=function(t){e.each(this.getPointers(),function(e){this.index(e)})},r.prototype.getPointers=function(){return this.o.pointers},r.prototype.generateScale=function(){if(this.settings.scale&&this.settings.scale.length>0){var e="",t=this.settings.scale,n=Math.round(100/(t.length-1)*10)/10;for(var r=0;r<t.length;r++)e+='<span style="left: '+r*n+'%">'+(t[r]!="|"?"<ins>"+t[r]+"</ins>":"")+"</span>";return e}return""},r.prototype.drawScale=function(){this.domNode.find(n.selector+"scale span ins").each(function(){e(this).css({marginLeft:-e(this).outerWidth()/2})})},r.prototype.onresize=function(){var t=this;this.sizes={domWidth:this.domNode.width(),domOffset:this.domNode.offset()},e.each(this.o.pointers,function(e){t.redraw(this)})},r.prototype.update=function(){this.onresize(),this.drawScale()},r.prototype.limits=function(e,t){if(!this.settings.smooth){var n=this.settings.step*100/this.settings.interval;e=Math.round(e/n)*n}var r=this.o.pointers[1-t.uid];return r&&t.uid&&e<r.value.prc&&(e=r.value.prc),r&&!t.uid&&e>r.value.prc&&(e=r.value.prc),e<0&&(e=0),e>100&&(e=100),Math.round(e*10)/10},r.prototype.redraw=function(e){if(!this.is.init)return!1;this.setValue(),this.o.pointers[0]&&this.o.pointers[1]&&this.o.value.css({left:this.o.pointers[0].value.prc+"%",width:this.o.pointers[1].value.prc-this.o.pointers[0].value.prc+"%"}),this.o.labels[e.uid].value.html(this.nice(e.value.origin)),this.redrawLabels(e)},r.prototype.redrawLabels=function(e){function t(e,t,r){return t.margin=-t.label/2,label_left=t.border+t.margin,label_left<0&&(t.margin-=label_left),t.border+t.label/2>n.sizes.domWidth?(t.margin=0,t.right=!0):t.right=!1,e.o.css({left:r+"%",marginLeft:t.margin,right:"auto"}),t.right&&e.o.css({left:"auto",right:0}),t}var n=this,r=this.o.labels[e.uid],i=e.value.prc,s={label:r.o.outerWidth(),right:!1,border:i*this.sizes.domWidth/100};if(!this.settings.single){var o=this.o.pointers[1-e.uid],u=this.o.labels[o.uid];switch(e.uid){case 0:s.border+s.label/2>u.o.offset().left-this.sizes.domOffset.left?(u.o.css({visibility:"hidden"}),u.value.html(this.nice(o.value.origin)),r.o.css({visibility:"visible"}),i=(o.value.prc-i)/2+i,o.value.prc!=e.value.prc&&(r.value.html(this.nice(e.value.origin)+"&nbsp;&ndash;&nbsp;"+this.nice(o.value.origin)),s.label=r.o.outerWidth(),s.border=i*this.sizes.domWidth/100)):u.o.css({visibility:"visible"});break;case 1:s.border-s.label/2<u.o.offset().left-this.sizes.domOffset.left+u.o.outerWidth()?(u.o.css({visibility:"hidden"}),u.value.html(this.nice(o.value.origin)),r.o.css({visibility:"visible"}),i=(i-o.value.prc)/2+o.value.prc,o.value.prc!=e.value.prc&&(r.value.html(this.nice(o.value.origin)+"&nbsp;&ndash;&nbsp;"+this.nice(e.value.origin)),s.label=r.o.outerWidth(),s.border=i*this.sizes.domWidth/100)):u.o.css({visibility:"visible"})}}s=t(r,s,i);if(u){var s={label:u.o.outerWidth(),right:!1,border:o.value.prc*this.sizes.domWidth/100};s=t(u,s,o.value.prc)}this.redrawLimits()},r.prototype.redrawLimits=function(){if(this.settings.limits){var e=[!0,!0];for(key in this.o.pointers)if(!this.settings.single||key==0){var t=this.o.pointers[key],n=this.o.labels[t.uid],r=n.o.offset().left-this.sizes.domOffset.left,i=this.o.limits[0];r<i.outerWidth()&&(e[0]=!1);var i=this.o.limits[1];r+n.o.outerWidth()>this.sizes.domWidth-i.outerWidth()&&(e[1]=!1)}for(var s=0;s<e.length;s++)e[s]?this.o.limits[s].fadeIn("fast"):this.o.limits[s].fadeOut("fast")}},r.prototype.setValue=function(){var e=this.getValue();this.inputNode.attr("value",e),this.onstatechange.call(this,e)},r.prototype.getValue=function(){if(!this.is.init)return!1;var t=this,n="";return e.each(this.o.pointers,function(e){this.value.prc!=undefined&&!isNaN(this.value.prc)&&(n+=(e>0?";":"")+t.prcToValue(this.value.prc))}),n},r.prototype.getPrcValue=function(){if(!this.is.init)return!1;var t=this,n="";return e.each(this.o.pointers,function(e){this.value.prc!=undefined&&!isNaN(this.value.prc)&&(n+=(e>0?";":"")+this.value.prc)}),n},r.prototype.prcToValue=function(e){if(this.settings.heterogeneity&&this.settings.heterogeneity.length>0){var t=this.settings.heterogeneity,n=0,r=this.settings.from;for(var i=0;i<=t.length;i++){if(t[i])var s=t[i].split("/");else var s=[100,this.settings.to];s[0]=new Number(s[0]),s[1]=new Number(s[1]);if(e>=n&&e<=s[0])var o=r+(e-n)*(s[1]-r)/(s[0]-n);n=s[0],r=s[1]}}else var o=this.settings.from+e*this.settings.interval/100;return this.round(o)},r.prototype.valueToPrc=function(e,t){if(this.settings.heterogeneity&&this.settings.heterogeneity.length>0){var n=this.settings.heterogeneity,r=0,i=this.settings.from;for(var s=0;s<=n.length;s++){if(n[s])var o=n[s].split("/");else var o=[100,this.settings.to];o[0]=new Number(o[0]),o[1]=new Number(o[1]);if(e>=i&&e<=o[1])var u=t.limits(r+(e-i)*(o[0]-r)/(o[1]-i));r=o[0],i=o[1]}}else var u=t.limits((e-this.settings.from)*100/this.settings.interval);return u},r.prototype.round=function(e){return e=Math.round(e/this.settings.step)*this.settings.step,this.settings.round?e=Math.round(e*Math.pow(10,this.settings.round))/Math.pow(10,this.settings.round):e=Math.round(e),e},r.prototype.nice=function(t){return t=t.toString().replace(/,/gi,".").replace(/ /gi,""),e.formatNumber?e.formatNumber(new Number(t),this.settings.format||{}).replace(/-/gi,"&minus;"):new Number(t)},i.prototype=new Draggable,i.prototype.oninit=function(e,t,n){this.uid=t,this.parent=n,this.value={},this.settings=this.parent.settings},i.prototype.onmousedown=function(e){this._parent={offset:this.parent.domNode.offset(),width:this.parent.domNode.width()},this.ptr.addDependClass("hover"),this.setIndexOver()},i.prototype.onmousemove=function(e,t){var n=this._getPageCoords(e);this._set(this.calc(n.x))},i.prototype.onmouseup=function(t){this.parent.settings.callback&&e.isFunction(this.parent.settings.callback)&&this.parent.settings.callback.call(this.parent,this.parent.getValue()),this.ptr.removeDependClass("hover")},i.prototype.setIndexOver=function(){this.parent.setPointersIndex(1),this.index(2)},i.prototype.index=function(e){this.ptr.css({zIndex:e})},i.prototype.limits=function(e){return this.parent.limits(e,this)},i.prototype.calc=function(e){var t=this.limits((e-this._parent.offset.left)*100/this._parent.width);return t},i.prototype.set=function(e,t){this.value.origin=this.parent.round(e),this._set(this.parent.valueToPrc(e,this),t)},i.prototype._set=function(e,t){t||(this.value.origin=this.parent.prcToValue(e)),this.value.prc=e,this.ptr.css({left:e+"%"}),this.parent.redraw(this)}}(jQuery);

/* Modernizr 2.6.2 (Custom Build) | MIT & BSD
 * Build: http://modernizr.com/download/#-fontface-backgroundsize-borderimage-borderradius-boxshadow-flexbox-hsla-multiplebgs-opacity-rgba-textshadow-cssanimations-csscolumns-generatedcontent-cssgradients-cssreflections-csstransforms-csstransforms3d-csstransitions-applicationcache-canvas-canvastext-draganddrop-hashchange-history-audio-video-indexeddb-input-inputtypes-localstorage-postmessage-sessionstorage-websockets-websqldatabase-webworkers-geolocation-inlinesvg-smil-svg-svgclippaths-touch-webgl-shiv-mq-cssclasses-addtest-prefixed-teststyles-testprop-testallprops-hasevent-prefixes-domprefixes-load
 */
;window.Modernizr=function(a,b,c){function D(a){j.cssText=a}function E(a,b){return D(n.join(a+";")+(b||""))}function F(a,b){return typeof a===b}function G(a,b){return!!~(""+a).indexOf(b)}function H(a,b){for(var d in a){var e=a[d];if(!G(e,"-")&&j[e]!==c)return b=="pfx"?e:!0}return!1}function I(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:F(f,"function")?f.bind(d||b):f}return!1}function J(a,b,c){var d=a.charAt(0).toUpperCase()+a.slice(1),e=(a+" "+p.join(d+" ")+d).split(" ");return F(b,"string")||F(b,"undefined")?H(e,b):(e=(a+" "+q.join(d+" ")+d).split(" "),I(e,b,c))}function K(){e.input=function(c){for(var d=0,e=c.length;d<e;d++)u[c[d]]=c[d]in k;return u.list&&(u.list=!!b.createElement("datalist")&&!!a.HTMLDataListElement),u}("autocomplete autofocus list placeholder max min multiple pattern required step".split(" ")),e.inputtypes=function(a){for(var d=0,e,f,h,i=a.length;d<i;d++)k.setAttribute("type",f=a[d]),e=k.type!=="text",e&&(k.value=l,k.style.cssText="position:absolute;visibility:hidden;",/^range$/.test(f)&&k.style.WebkitAppearance!==c?(g.appendChild(k),h=b.defaultView,e=h.getComputedStyle&&h.getComputedStyle(k,null).WebkitAppearance!=="textfield"&&k.offsetHeight!==0,g.removeChild(k)):/^(search|tel)$/.test(f)||(/^(url|email)$/.test(f)?e=k.checkValidity&&k.checkValidity()===!1:e=k.value!=l)),t[a[d]]=!!e;return t}("search tel url email datetime date month week time datetime-local number range color".split(" "))}var d="2.6.2",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k=b.createElement("input"),l=":)",m={}.toString,n=" -webkit- -moz- -o- -ms- ".split(" "),o="Webkit Moz O ms",p=o.split(" "),q=o.toLowerCase().split(" "),r={svg:"http://www.w3.org/2000/svg"},s={},t={},u={},v=[],w=v.slice,x,y=function(a,c,d,e){var f,i,j,k,l=b.createElement("div"),m=b.body,n=m||b.createElement("body");if(parseInt(d,10))while(d--)j=b.createElement("div"),j.id=e?e[d]:h+(d+1),l.appendChild(j);return f=["&#173;",'<style id="s',h,'">',a,"</style>"].join(""),l.id=h,(m?l:n).innerHTML+=f,n.appendChild(l),m||(n.style.background="",n.style.overflow="hidden",k=g.style.overflow,g.style.overflow="hidden",g.appendChild(n)),i=c(l,a),m?l.parentNode.removeChild(l):(n.parentNode.removeChild(n),g.style.overflow=k),!!i},z=function(b){var c=a.matchMedia||a.msMatchMedia;if(c)return c(b).matches;var d;return y("@media "+b+" { #"+h+" { position: absolute; } }",function(b){d=(a.getComputedStyle?getComputedStyle(b,null):b.currentStyle)["position"]=="absolute"}),d},A=function(){function d(d,e){e=e||b.createElement(a[d]||"div"),d="on"+d;var f=d in e;return f||(e.setAttribute||(e=b.createElement("div")),e.setAttribute&&e.removeAttribute&&(e.setAttribute(d,""),f=F(e[d],"function"),F(e[d],"undefined")||(e[d]=c),e.removeAttribute(d))),e=null,f}var a={select:"input",change:"input",submit:"form",reset:"form",error:"img",load:"img",abort:"img"};return d}(),B={}.hasOwnProperty,C;!F(B,"undefined")&&!F(B.call,"undefined")?C=function(a,b){return B.call(a,b)}:C=function(a,b){return b in a&&F(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=w.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(w.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(w.call(arguments)))};return e}),s.flexbox=function(){return J("flexWrap")},s.canvas=function(){var a=b.createElement("canvas");return!!a.getContext&&!!a.getContext("2d")},s.canvastext=function(){return!!e.canvas&&!!F(b.createElement("canvas").getContext("2d").fillText,"function")},s.webgl=function(){return!!a.WebGLRenderingContext},s.touch=function(){var c;return"ontouchstart"in a||a.DocumentTouch&&b instanceof DocumentTouch?c=!0:y(["@media (",n.join("touch-enabled),("),h,")","{#modernizr{top:9px;position:absolute}}"].join(""),function(a){c=a.offsetTop===9}),c},s.geolocation=function(){return"geolocation"in navigator},s.postmessage=function(){return!!a.postMessage},s.websqldatabase=function(){return!!a.openDatabase},s.indexedDB=function(){return!!J("indexedDB",a)},s.hashchange=function(){return A("hashchange",a)&&(b.documentMode===c||b.documentMode>7)},s.history=function(){return!!a.history&&!!history.pushState},s.draganddrop=function(){var a=b.createElement("div");return"draggable"in a||"ondragstart"in a&&"ondrop"in a},s.websockets=function(){return"WebSocket"in a||"MozWebSocket"in a},s.rgba=function(){return D("background-color:rgba(150,255,150,.5)"),G(j.backgroundColor,"rgba")},s.hsla=function(){return D("background-color:hsla(120,40%,100%,.5)"),G(j.backgroundColor,"rgba")||G(j.backgroundColor,"hsla")},s.multiplebgs=function(){return D("background:url(https://),url(https://),red url(https://)"),/(url\s*\(.*?){3}/.test(j.background)},s.backgroundsize=function(){return J("backgroundSize")},s.borderimage=function(){return J("borderImage")},s.borderradius=function(){return J("borderRadius")},s.boxshadow=function(){return J("boxShadow")},s.textshadow=function(){return b.createElement("div").style.textShadow===""},s.opacity=function(){return E("opacity:.55"),/^0.55$/.test(j.opacity)},s.cssanimations=function(){return J("animationName")},s.csscolumns=function(){return J("columnCount")},s.cssgradients=function(){var a="background-image:",b="gradient(linear,left top,right bottom,from(#9f9),to(white));",c="linear-gradient(left top,#9f9, white);";return D((a+"-webkit- ".split(" ").join(b+a)+n.join(c+a)).slice(0,-a.length)),G(j.backgroundImage,"gradient")},s.cssreflections=function(){return J("boxReflect")},s.csstransforms=function(){return!!J("transform")},s.csstransforms3d=function(){var a=!!J("perspective");return a&&"webkitPerspective"in g.style&&y("@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}",function(b,c){a=b.offsetLeft===9&&b.offsetHeight===3}),a},s.csstransitions=function(){return J("transition")},s.fontface=function(){var a;return y('@font-face {font-family:"font";src:url("https://")}',function(c,d){var e=b.getElementById("smodernizr"),f=e.sheet||e.styleSheet,g=f?f.cssRules&&f.cssRules[0]?f.cssRules[0].cssText:f.cssText||"":"";a=/src/i.test(g)&&g.indexOf(d.split(" ")[0])===0}),a},s.generatedcontent=function(){var a;return y(["#",h,"{font:0/0 a}#",h,':after{content:"',l,'";visibility:hidden;font:3px/1 a}'].join(""),function(b){a=b.offsetHeight>=3}),a},s.video=function(){var a=b.createElement("video"),c=!1;try{if(c=!!a.canPlayType)c=new Boolean(c),c.ogg=a.canPlayType('video/ogg; codecs="theora"').replace(/^no$/,""),c.h264=a.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/,""),c.webm=a.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/,"")}catch(d){}return c},s.audio=function(){var a=b.createElement("audio"),c=!1;try{if(c=!!a.canPlayType)c=new Boolean(c),c.ogg=a.canPlayType('audio/ogg; codecs="vorbis"').replace(/^no$/,""),c.mp3=a.canPlayType("audio/mpeg;").replace(/^no$/,""),c.wav=a.canPlayType('audio/wav; codecs="1"').replace(/^no$/,""),c.m4a=(a.canPlayType("audio/x-m4a;")||a.canPlayType("audio/aac;")).replace(/^no$/,"")}catch(d){}return c},s.localstorage=function(){try{return localStorage.setItem(h,h),localStorage.removeItem(h),!0}catch(a){return!1}},s.sessionstorage=function(){try{return sessionStorage.setItem(h,h),sessionStorage.removeItem(h),!0}catch(a){return!1}},s.webworkers=function(){return!!a.Worker},s.applicationcache=function(){return!!a.applicationCache},s.svg=function(){return!!b.createElementNS&&!!b.createElementNS(r.svg,"svg").createSVGRect},s.inlinesvg=function(){var a=b.createElement("div");return a.innerHTML="<svg/>",(a.firstChild&&a.firstChild.namespaceURI)==r.svg},s.smil=function(){return!!b.createElementNS&&/SVGAnimate/.test(m.call(b.createElementNS(r.svg,"animate")))},s.svgclippaths=function(){return!!b.createElementNS&&/SVGClipPath/.test(m.call(b.createElementNS(r.svg,"clipPath")))};for(var L in s)C(s,L)&&(x=L.toLowerCase(),e[x]=s[L](),v.push((e[x]?"":"no-")+x));return e.input||K(),e.addTest=function(a,b){if(typeof a=="object")for(var d in a)C(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof f!="undefined"&&f&&(g.className+=" "+(b?"":"no-")+a),e[a]=b}return e},D(""),i=k=null,function(a,b){function k(a,b){var c=a.createElement("p"),d=a.getElementsByTagName("head")[0]||a.documentElement;return c.innerHTML="x<style>"+b+"</style>",d.insertBefore(c.lastChild,d.firstChild)}function l(){var a=r.elements;return typeof a=="string"?a.split(" "):a}function m(a){var b=i[a[g]];return b||(b={},h++,a[g]=h,i[h]=b),b}function n(a,c,f){c||(c=b);if(j)return c.createElement(a);f||(f=m(c));var g;return f.cache[a]?g=f.cache[a].cloneNode():e.test(a)?g=(f.cache[a]=f.createElem(a)).cloneNode():g=f.createElem(a),g.canHaveChildren&&!d.test(a)?f.frag.appendChild(g):g}function o(a,c){a||(a=b);if(j)return a.createDocumentFragment();c=c||m(a);var d=c.frag.cloneNode(),e=0,f=l(),g=f.length;for(;e<g;e++)d.createElement(f[e]);return d}function p(a,b){b.cache||(b.cache={},b.createElem=a.createElement,b.createFrag=a.createDocumentFragment,b.frag=b.createFrag()),a.createElement=function(c){return r.shivMethods?n(c,a,b):b.createElem(c)},a.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+l().join().replace(/\w+/g,function(a){return b.createElem(a),b.frag.createElement(a),'c("'+a+'")'})+");return n}")(r,b.frag)}function q(a){a||(a=b);var c=m(a);return r.shivCSS&&!f&&!c.hasCSS&&(c.hasCSS=!!k(a,"article,aside,figcaption,figure,footer,header,hgroup,nav,section{display:block}mark{background:#FF0;color:#000}")),j||p(a,c),a}var c=a.html5||{},d=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,e=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,f,g="_html5shiv",h=0,i={},j;(function(){try{var a=b.createElement("a");a.innerHTML="<xyz></xyz>",f="hidden"in a,j=a.childNodes.length==1||function(){b.createElement("a");var a=b.createDocumentFragment();return typeof a.cloneNode=="undefined"||typeof a.createDocumentFragment=="undefined"||typeof a.createElement=="undefined"}()}catch(c){f=!0,j=!0}})();var r={elements:c.elements||"abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output progress section summary time video",shivCSS:c.shivCSS!==!1,supportsUnknownElements:j,shivMethods:c.shivMethods!==!1,type:"default",shivDocument:q,createElement:n,createDocumentFragment:o};a.html5=r,q(b)}(this,b),e._version=d,e._prefixes=n,e._domPrefixes=q,e._cssomPrefixes=p,e.mq=z,e.hasEvent=A,e.testProp=function(a){return H([a])},e.testAllProps=J,e.testStyles=y,e.prefixed=function(a,b,c){return b?J(a,b,c):J(a,"pfx")},g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+v.join(" "):""),e}(this,this.document),function(a,b,c){function d(a){return"[object Function]"==o.call(a)}function e(a){return"string"==typeof a}function f(){}function g(a){return!a||"loaded"==a||"complete"==a||"uninitialized"==a}function h(){var a=p.shift();q=1,a?a.t?m(function(){("c"==a.t?B.injectCss:B.injectJs)(a.s,0,a.a,a.x,a.e,1)},0):(a(),h()):q=0}function i(a,c,d,e,f,i,j){function k(b){if(!o&&g(l.readyState)&&(u.r=o=1,!q&&h(),l.onload=l.onreadystatechange=null,b)){"img"!=a&&m(function(){t.removeChild(l)},50);for(var d in y[c])y[c].hasOwnProperty(d)&&y[c][d].onload()}}var j=j||B.errorTimeout,l=b.createElement(a),o=0,r=0,u={t:d,s:c,e:f,a:i,x:j};1===y[c]&&(r=1,y[c]=[]),"object"==a?l.data=c:(l.src=c,l.type=a),l.width=l.height="0",l.onerror=l.onload=l.onreadystatechange=function(){k.call(this,r)},p.splice(e,0,u),"img"!=a&&(r||2===y[c]?(t.insertBefore(l,s?null:n),m(k,j)):y[c].push(l))}function j(a,b,c,d,f){return q=0,b=b||"j",e(a)?i("c"==b?v:u,a,b,this.i++,c,d,f):(p.splice(this.i++,0,a),1==p.length&&h()),this}function k(){var a=B;return a.loader={load:j,i:0},a}var l=b.documentElement,m=a.setTimeout,n=b.getElementsByTagName("script")[0],o={}.toString,p=[],q=0,r="MozAppearance"in l.style,s=r&&!!b.createRange().compareNode,t=s?l:n.parentNode,l=a.opera&&"[object Opera]"==o.call(a.opera),l=!!b.attachEvent&&!l,u=r?"object":l?"script":"img",v=l?"script":u,w=Array.isArray||function(a){return"[object Array]"==o.call(a)},x=[],y={},z={timeout:function(a,b){return b.length&&(a.timeout=b[0]),a}},A,B;B=function(a){function b(a){var a=a.split("!"),b=x.length,c=a.pop(),d=a.length,c={url:c,origUrl:c,prefixes:a},e,f,g;for(f=0;f<d;f++)g=a[f].split("="),(e=z[g.shift()])&&(c=e(c,g));for(f=0;f<b;f++)c=x[f](c);return c}function g(a,e,f,g,h){var i=b(a),j=i.autoCallback;i.url.split(".").pop().split("?").shift(),i.bypass||(e&&(e=d(e)?e:e[a]||e[g]||e[a.split("/").pop().split("?")[0]]),i.instead?i.instead(a,e,f,g,h):(y[i.url]?i.noexec=!0:y[i.url]=1,f.load(i.url,i.forceCSS||!i.forceJS&&"css"==i.url.split(".").pop().split("?").shift()?"c":c,i.noexec,i.attrs,i.timeout),(d(e)||d(j))&&f.load(function(){k(),e&&e(i.origUrl,h,g),j&&j(i.origUrl,h,g),y[i.url]=2})))}function h(a,b){function c(a,c){if(a){if(e(a))c||(j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}),g(a,j,b,0,h);else if(Object(a)===a)for(n in m=function(){var b=0,c;for(c in a)a.hasOwnProperty(c)&&b++;return b}(),a)a.hasOwnProperty(n)&&(!c&&!--m&&(d(j)?j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}:j[n]=function(a){return function(){var b=[].slice.call(arguments);a&&a.apply(this,b),l()}}(k[n])),g(a[n],j,b,n,h))}else!c&&l()}var h=!!a.test,i=a.load||a.both,j=a.callback||f,k=j,l=a.complete||f,m,n;c(h?a.yep:a.nope,!!i),i&&c(i)}var i,j,l=this.yepnope.loader;if(e(a))g(a,0,l,0);else if(w(a))for(i=0;i<a.length;i++)j=a[i],e(j)?g(j,0,l,0):w(j)?B(j):Object(j)===j&&h(j,l);else Object(a)===a&&h(a,l)},B.addPrefix=function(a,b){z[a]=b},B.addFilter=function(a){x.push(a)},B.errorTimeout=1e4,null==b.readyState&&b.addEventListener&&(b.readyState="loading",b.addEventListener("DOMContentLoaded",A=function(){b.removeEventListener("DOMContentLoaded",A,0),b.readyState="complete"},0)),a.yepnope=k(),a.yepnope.executeStack=h,a.yepnope.injectJs=function(a,c,d,e,i,j){var k=b.createElement("script"),l,o,e=e||B.errorTimeout;k.src=a;for(o in d)k.setAttribute(o,d[o]);c=j?h:c||f,k.onreadystatechange=k.onload=function(){!l&&g(k.readyState)&&(l=1,c(),k.onload=k.onreadystatechange=null)},m(function(){l||(l=1,c(1))},e),i?k.onload():n.parentNode.insertBefore(k,n)},a.yepnope.injectCss=function(a,c,d,e,g,i){var e=b.createElement("link"),j,c=i?h:c||f;e.href=a,e.rel="stylesheet",e.type="text/css";for(j in d)e.setAttribute(j,d[j]);g||(n.parentNode.insertBefore(e,n),m(c,0))}}(this,document),Modernizr.load=function(){yepnope.apply(window,[].slice.call(arguments,0))};




;(function ($, document, window) {
    var
    // default settings object.
        defaults = {
            label: 'MENU',
            duplicate: true,
            duration: 200,
            easingOpen: 'swing',
            easingClose: 'swing',
            closedSymbol: '&#9658;',
            openedSymbol: '&#9660;',
            prependTo: 'body',
			parentTag: 'a',
            closeOnClick: false,
            allowParentLinks: false,
            nestedParentLinks: true,
            showChildren: false,
            removeIds: false,
            removeClasses: false,
			brand: '',
            init: function () {},
            beforeOpen: function () {},
            beforeClose: function () {},
            afterOpen: function () {},
            afterClose: function () {}
        },
        mobileMenu = 'respNav',
        prefix = 'respNav';

    function Plugin(element, options) {
        this.element = element;

        // jQuery has an extend method which merges the contents of two or
        // more objects, storing the result in the first object. The first object
        // is generally empty as we don't want to alter the default options for
        // future instances of the plugin
        this.settings = $.extend({}, defaults, options);

        this._defaults = defaults;
        this._name = mobileMenu;

        this.init();
    }

    Plugin.prototype.init = function () {
        var $this = this,
            menu = $(this.element),
            settings = this.settings,
            iconClass,
            menuBar;

        // clone menu if needed
        if (settings.duplicate) {
            $this.mobileNav = menu.clone();
            //remove ids from clone to prevent css issues
            $this.mobileNav.removeAttr('id');
            $this.mobileNav.find('*').each(function (i, e) {
                $(e).removeAttr('id');
            });
        } else {
            $this.mobileNav = menu;
            
            // remove ids if set
            $this.mobileNav.removeAttr('id');
            $this.mobileNav.find('*').each(function (i, e) {
                $(e).removeAttr('id');
            });
        }
        
        // remove classes if set
        if (settings.removeClasses) {
            $this.mobileNav.removeAttr('class');
            $this.mobileNav.find('*').each(function (i, e) {
                $(e).removeAttr('class');
            });
        }

        // styling class for the button
        iconClass = prefix + '_icon'; 

        if (settings.label === '') {
            iconClass += ' ' + prefix + '_no-text';
        }

        if (settings.parentTag == 'a') {
            settings.parentTag = 'a href="#"';
        }

        // create menu bar
        $this.mobileNav.attr('class', prefix + '_nav');
        menuBar = $('<div class="' + prefix + '_menu" id="advancedm"></div>');
		if (settings.brand !== '') {
			var brand = $('<div class="' + prefix + '_brand">'+settings.brand+'</div>');
			$(menuBar).append(brand);
		}
        $this.btn = $(
            ['<' + settings.parentTag + ' aria-haspopup="true" tabindex="0" id="filtermenuadv" class="' + prefix + '_btn ' + prefix + '_collapsed">',
                '<span class="' + prefix + '_menutxt">' + settings.label + '</span>',
                '<span class="' + iconClass + '">',
                    '<span class="' + prefix + '_icon-bar"></span>',
                    '<span class="' + prefix + '_icon-bar"></span>',
                    '<span class="' + prefix + '_icon-bar"></span>',
                '</span>',
            '</' + settings.parentTag + '>'
            ].join('')
        );
        $(menuBar).append($this.btn);
        $(settings.prependTo).append(menuBar);
        menuBar.append($this.mobileNav);

        // iterate over structure adding additional structure
        var items = $this.mobileNav.find('li');
        $(items).each(function () {
            var item = $(this),
                data = {};
            data.children = item.children('ul').attr('role', 'menu');
            item.data('menu', data);

            // if a list item has a nested menu
            if (data.children.length > 0) {

                // select all text before the child menu
                // check for anchors

                var a = item.contents(),
                    containsAnchor = false;
                    nodes = [];

                $(a).each(function () {
                    if (!$(this).is('ul')) {
                        nodes.push(this);
                    } else {
                        return false;
                    }

                    if($(this).is("a")) {
                        containsAnchor = true;
                    }
                });

                var wrapElement = $(
                    '<' + settings.parentTag + ' role="menuitem" aria-haspopup="true" tabindex="-1" class="' + prefix + '_item"/>'
                );

                // wrap item text with tag and add classes unless we are separating parent links
                if ((!settings.allowParentLinks || settings.nestedParentLinks) || !containsAnchor) {
                    var $wrap = $(nodes).wrapAll(wrapElement).parent();
                    $wrap.addClass(prefix+'_row');
                } else
                    $(nodes).wrapAll('<span class="'+prefix+'_parent-link '+prefix+'_row"/>').parent();

                item.addClass(prefix+'_collapsed');
                item.addClass(prefix+'_parent');

                // create parent arrow. wrap with link if parent links and separating
                var arrowElement = $('<span class="'+prefix+'_arrow">'+settings.closedSymbol+'</span>');

                if (settings.allowParentLinks && !settings.nestedParentLinks && containsAnchor)
                    arrowElement = arrowElement.wrap(wrapElement).parent();

                //append arrow
                $(nodes).last().after(arrowElement);


            } else if ( item.children().length === 0) {
                 item.addClass(prefix+'_txtnode');
            }

            // accessibility for links
            item.children('a').attr('role', 'menuitem').click(function(event){
                //Ensure that it's not a parent
                if (settings.closeOnClick && !$(event.target).parent().closest('li').hasClass(prefix+'_parent')) {
                        //Emulate menu close if set
                        $($this.btn).click();
                    }
            });

            //also close on click if parent links are set
            if (settings.closeOnClick && settings.allowParentLinks) {
                item.children('a').children('a').click(function (event) {
                    //Emulate menu close
                    $($this.btn).click();
                });

                item.find('.'+prefix+'_parent-link a:not(.'+prefix+'_item)').click(function(event){
                    //Emulate menu close
                        $($this.btn).click();
                });
            }
        });

        // structure is in place, now hide appropriate items
        $(items).each(function () {
            var data = $(this).data('menu');
            if (!settings.showChildren){
                $this._visibilityToggle(data.children, null, false, null, true);
            }
        });

        // finally toggle entire menu
        $this._visibilityToggle($this.mobileNav, null, false, 'init', true);

        // accessibility for menu button
        $this.mobileNav.attr('role','menu');

        // outline prevention when using mouse
        $(document).mousedown(function(){
            $this._outlines(false);
        });

        $(document).keyup(function(){
            $this._outlines(true);
        });

        // menu button click
        $($this.btn).click(function (e) {
            e.preventDefault();
            $this._menuToggle();
        });

        // click on menu parent
        $this.mobileNav.on('click', '.' + prefix + '_item', function (e) {
            e.preventDefault();
            $this._itemClick($(this));
        });

        // check for enter key on menu button and menu parents
        $($this.btn).keydown(function (e) {
            var ev = e || event;
            if(ev.keyCode == 13) {
                e.preventDefault();
                $this._menuToggle();
            }
        });

        $this.mobileNav.on('keydown', '.'+prefix+'_item', function(e) {
            var ev = e || event;
            if(ev.keyCode == 13) {
                e.preventDefault();
                $this._itemClick($(e.target));
            }
        });

        // allow links clickable within parent tags if set
        if (settings.allowParentLinks && settings.nestedParentLinks) {
            $('.'+prefix+'_item a').click(function(e){
                    e.stopImmediatePropagation();
            });
        }
    };

    //toggle menu
    Plugin.prototype._menuToggle = function (el) {
        var $this = this;
        var btn = $this.btn;
        var mobileNav = $this.mobileNav;

        if (btn.hasClass(prefix+'_collapsed')) {
            btn.removeClass(prefix+'_collapsed');
            btn.addClass(prefix+'_open');
        } else {
            btn.removeClass(prefix+'_open');
            btn.addClass(prefix+'_collapsed');
        }
        btn.addClass(prefix+'_animating');
        $this._visibilityToggle(mobileNav, btn.parent(), true, btn);
    };

    // toggle clicked items
    Plugin.prototype._itemClick = function (el) {
        var $this = this;
        var settings = $this.settings;
        var data = el.data('menu');
        if (!data) {
            data = {};
            data.arrow = el.children('.'+prefix+'_arrow');
            data.ul = el.next('ul');
            data.parent = el.parent();
            //Separated parent link structure
            if (data.parent.hasClass(prefix+'_parent-link')) {
                data.parent = el.parent().parent();
                data.ul = el.parent().next('ul');
            }
            el.data('menu', data);
        }
        if (data.parent.hasClass(prefix+'_collapsed')) {
            data.arrow.html(settings.openedSymbol);
            data.parent.removeClass(prefix+'_collapsed');
            data.parent.addClass(prefix+'_open');
            data.parent.addClass(prefix+'_animating');
            $this._visibilityToggle(data.ul, data.parent, true, el);
        } else {
            data.arrow.html(settings.closedSymbol);
            data.parent.addClass(prefix+'_collapsed');
            data.parent.removeClass(prefix+'_open');
            data.parent.addClass(prefix+'_animating');
            $this._visibilityToggle(data.ul, data.parent, true, el);
        }
    };

    // toggle actual visibility and accessibility tags
    Plugin.prototype._visibilityToggle = function(el, parent, animate, trigger, init) {
        var $this = this;
        var settings = $this.settings;
        var items = $this._getActionItems(el);
        var duration = 0;
        if (animate) {
            duration = settings.duration;
        }

        if (el.hasClass(prefix+'_hidden')) {
            el.removeClass(prefix+'_hidden');
             //Fire beforeOpen callback
                if (!init) {
                    settings.beforeOpen(trigger);
                }
            el.slideDown(duration, settings.easingOpen, function(){

                $(trigger).removeClass(prefix+'_animating');
                $(parent).removeClass(prefix+'_animating');

                //Fire afterOpen callback
                if (!init) {
                    settings.afterOpen(trigger);
                }
            });
            el.attr('aria-hidden','false');
            items.attr('tabindex', '0');
            $this._setVisAttr(el, false);
        } else {
            el.addClass(prefix+'_hidden');
            	
            //Fire init or beforeClose callback
            if (!init){
                settings.beforeClose(trigger);
            }else if (trigger == 'init'){
                settings.init();
            }
            
            el.slideUp(duration, this.settings.easingClose, function() {
                el.attr('aria-hidden','true');
                items.attr('tabindex', '-1');
                $this._setVisAttr(el, true);
                el.hide(); //jQuery 1.7 bug fix
                
                $(trigger).removeClass(prefix+'_animating');
                $(parent).removeClass(prefix+'_animating');

                //Fire init or afterClose callback
                if (!init){
                    settings.afterClose(trigger);
                }
                else if (trigger == 'init'){
                    settings.init();
                }
            });
        }
    };

    // set attributes of element and children based on visibility
    Plugin.prototype._setVisAttr = function(el, hidden) {
        var $this = this;

        // select all parents that aren't hidden
        var nonHidden = el.children('li').children('ul').not('.'+prefix+'_hidden');

        // iterate over all items setting appropriate tags
        if (!hidden) {
            nonHidden.each(function(){
                var ul = $(this);
                ul.attr('aria-hidden','false');
                var items = $this._getActionItems(ul);
                items.attr('tabindex', '0');
                $this._setVisAttr(ul, hidden);
            });
        } else {
            nonHidden.each(function(){
                var ul = $(this);
                ul.attr('aria-hidden','true');
                var items = $this._getActionItems(ul);
                items.attr('tabindex', '-1');
                $this._setVisAttr(ul, hidden);
            });
        }
    };

    // get all 1st level items that are clickable
    Plugin.prototype._getActionItems = function(el) {
        var data = el.data("menu");
        if (!data) {
            data = {};
            var items = el.children('li');
            var anchors = items.find('a');
            data.links = anchors.add(items.find('.'+prefix+'_item'));
            el.data('menu', data);
        }
        return data.links;
    };

    Plugin.prototype._outlines = function(state) {
        if (!state) {
            $('.'+prefix+'_item, .'+prefix+'_btn').css('outline','none');
        } else {
            $('.'+prefix+'_item, .'+prefix+'_btn').css('outline','');
        }
    };

    Plugin.prototype.toggle = function(){
        var $this = this;
        $this._menuToggle();
    };

    Plugin.prototype.open = function(){
        var $this = this;
        if ($this.btn.hasClass(prefix+'_collapsed')) {
            $this._menuToggle();
        }
    };

    Plugin.prototype.close = function(){
        var $this = this;
        if ($this.btn.hasClass(prefix+'_open')) {
            $this._menuToggle();
        }
    };

    $.fn[mobileMenu] = function ( options ) {
        var args = arguments;

        // Is the first parameter an object (options), or was omitted, instantiate a new instance
        if (options === undefined || typeof options === 'object') {
            return this.each(function () {

                // Only allow the plugin to be instantiated once due to methods
                if (!$.data(this, 'plugin_' + mobileMenu)) {

                    // if it has no instance, create a new one, pass options to our plugin constructor,
                    // and store the plugin instance in the elements jQuery data object.
                    $.data(this, 'plugin_' + mobileMenu, new Plugin( this, options ));
                }
            });

        // If is a string and doesn't start with an underscore or 'init' function, treat this as a call to a public method.
        } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {

            // Cache the method call to make it possible to return a value
            var returns;

            this.each(function () {
                var instance = $.data(this, 'plugin_' + mobileMenu);

                // Tests that there's already a plugin-instance and checks that the requested public method exists
                if (instance instanceof Plugin && typeof instance[options] === 'function') {

                    // Call the method of our plugin instance, and pass it the supplied arguments.
                    returns = instance[options].apply( instance, Array.prototype.slice.call( args, 1 ) );
                }
            });

            // If the earlier cached method gives a value back return the value, otherwise return this to preserve chainability.
            return returns !== undefined ? returns : this;
        }
    };
}(jQuery, document, window));

