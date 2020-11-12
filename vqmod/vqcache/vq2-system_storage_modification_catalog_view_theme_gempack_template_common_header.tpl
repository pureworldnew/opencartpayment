<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $title; ?></title>
        <base href="<?php echo $base; ?>" />
<?php if ($canonical_link) {echo '<link href="'.$canonical_link.'" rel="canonical" />';} ?>
<?php if ($description) { ?>
            <meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
            <meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($search_page || $no_index_this_page) { ?>
<meta name="robots" content="noindex">
<?php } ?>
<?php $revision=55; ?>

            <link rel="stylesheet" type="text/css" href="catalog/view/theme/gempack/stylesheet/font-opensans/font-opensans.css" />
            <link rel="stylesheet" type="text/css" href="catalog/view/theme/gempack/stylesheet/font-awesome/css/font-awesome.min.css" />
            <link rel="stylesheet" type="text/css" href="catalog/view/theme/gempack/stylesheet/bootstrap/css/bootstrap.min.css" />
            <link rel="stylesheet" type="text/css" href="catalog/view/theme/gempack/stylesheet/stylesheet.css" />
            <link rel="stylesheet" type="text/css" href="catalog/view/supermenu/newone.css" />
<?php foreach ($styles as $style) { ?>
            <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<?php if($link['rel'] == 'canonical'){ ?>
            <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />	
<?php }else{ ?>
            <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php }?>
<?php } ?>

            <script type="text/javascript" src="catalog/view/theme/gempack/javascript/jquery/jquery-2.1.1.min.js"></script> 
            <script type="text/javascript" src="catalog/view/theme/gempack/stylesheet/bootstrap/js/bootstrap.min.js"></script>
            <script type="text/javascript" src="catalog/view/theme/gempack/javascript/common.js"></script>
            <script type="text/javascript" src="catalog/view/theme/gempack/javascript/generateThumbs.js"></script> 
            <script type="text/javascript" src="catalog/view/javascript/fancySelect.v2.js?_=<?php echo rand(100000,getrandmax()); ?>"></script>
<?php foreach ($scripts as $script) { ?>
            <script src="<?php echo $script; ?>" type="text/javascript"></script> 
<?php } ?>

<?php foreach ($analytics as $analytic) { ?>
<?php echo $analytic; ?>
<?php } ?>
<?php echo $supermenu_settings; ?>
<?php if ($include_contact_js) { ?>
<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "JewelryStore",
    "name": "Bella Findings",
    "image": "https://www.gempacked.com/image/catalog/xlogo_gem.png.pagespeed.ic.ZydoVqMQi8.webp",
    "@id": "",
    "url": "https://www.gempacked.com/",
    "telephone": "2136294840",
    "priceRange": "2",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "607 S. Hill Street Suite AR6",
        "addressLocality": "Los Angeles",
        "addressRegion": "CA",
        "postalCode": "90014",
        "addressCountry": "US"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": 34.0473934,
        "longitude": -118.2536521
    },
    "hasmap": "https://www.google.com/maps/place/Bella+Findings/@34.0473934,-118.2536521,21
    z / data = !4 m12!1 m6!3 m5!1 s0x0: 0x34680102563c9bb6!2 sBella + Findings!8 m2!3 d34 .047301!4 d - 1
    18.253606!3 m4!1 s0x0: 0x34680102563c9bb6!8 m2!3 d34 .047301!4 d - 118.253606 ? hl = en - US ",
    "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday"
        ],
        "opens": "09:00",
        "closes": "17:00"
    },
    "sameAs": [
        "https://www.facebook.com/Gempacked-Wholesale-Jewelry-Parts-and-Beads-45520982786017
        8 / ",
        "https://twitter.com/gem_packed/",
        "https://www.instagram.com/gempacked/",
        "https://www.pinterest.com/gempacked/"
    ],
    "subOrganization": {
        "@type": "Organization",
        "@id": "www.gempacked.com",
        "name": "Gempacked"
    }
}
</script>
<?php } ?>
<link href="index.php?route=common/themecss" rel="stylesheet"/>

			<link rel="stylesheet" href="catalog/view/javascript/jquery.cluetip.css" type="text/css" />
			<script src="catalog/view/javascript/jquery.cluetip.js" type="text/javascript"></script>
			
			<script type="text/javascript">
				$(document).ready(function() {
				$('a.title').cluetip({splitTitle: '|'});
				  $('ol.rounded a:eq(0)').cluetip({splitTitle: '|', dropShadow: false, cluetipClass: 'rounded', showtitle: false});
				  $('ol.rounded a:eq(1)').cluetip({cluetipClass: 'rounded', dropShadow: false, showtitle: false, positionBy: 'mouse'});
				  $('ol.rounded a:eq(2)').cluetip({cluetipClass: 'rounded', dropShadow: false, showtitle: false, positionBy: 'bottomTop', topOffset: 70});
				  $('ol.rounded a:eq(3)').cluetip({cluetipClass: 'rounded', dropShadow: false, sticky: true, ajaxCache: false, arrows: true});
				  $('ol.rounded a:eq(4)').cluetip({cluetipClass: 'rounded', dropShadow: false});  
				});
			</script>
			
<link href="catalog/view/javascript/jquery/starplugins-cloudzoom/cloudzoom.css" rel="stylesheet" type="text/css" />

<script type="text/JavaScript" src="catalog/view/javascript/jquery/starplugins-cloudzoom/cloudzoom.js"></script><script type="text/javascript">

	CloudZoom.quickStart();

		

	$(function(){

		// If you don't want colorbox pop-up on click, add back in the return; below this line.

		// return;

		if(!$.colorbox) return;

		// Bind a click event to a Cloud Zoom instance.

		$('.cloudzoom').bind('click',function(){

		 

			// On click, get the Cloud Zoom object,

		  var cloudZoom = $(this).data('CloudZoom');

			// Close the zoom window (from 2.1 rev 1211291557)

			cloudZoom.closeZoom();    

			

			// Get the list of images, use first one for ColorBox.

			var src;

			var list = cloudZoom.getGalleryList();		

			// Array of images, or just one?

			if (list instanceof Array) {

				a = list[0].href ? list[0].href:false;

				if (a === false)

					return false;

				src = list[0].href;

			}

			else src = list.href;

		  

			// Open colorbox.

			$.colorbox({html:"<img style='width:500px' src='"+src+"'/>", maxHeight:window.innerHeight,maxWidth:window.innerWidth});

		   

			return false;

		});

	});

	

</script>
</head>
<body class="common-home">
		<!-- Google Tag Manager -->
        <noscript>
        <iframe src="//www.googletagmanager.com/ns.html?id=GTM-KBQZ43Z" 
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
        <script>
			(function(w, d, s, l, i) {
			w[l] = w[l] || [];
			w[l].push({'gtm.start':
			new Date().getTime(), event: 'gtm.js'});
			var f = d.getElementsByTagName(s)[0],
			j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
			j.async = true;
			j.src =
			'//www.googletagmanager.com/gtm.js?id=' + i + dl;
			f.parentNode.insertBefore(j, f);
			})(window, document, 'script', 'dataLayer', 'GTM-KBQZ43Z');
		</script>
<header>
	 
		
    <div id="mobile-header" class="top-bar hidden-lg hidden-md">
        <div class="container-fluid">
            <nav class="navbar navbar-default" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle toggle-menu menu-left push-body pull-left" data-toggle="collapse" data-target="#bs-mobile-menu-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <i class="fa fa-navicon"></i>
                    </button>
                    <a class="navbar-brand" href="<?php echo $home; ?>"><img src="catalog/view/theme/gempack/image/logo-phone.png" title="Gempacked" alt="Gempacked" class="img-responsive" /></a>
                    <ul class="list-inline pull-right icon-menu-top">
                        <li><a href="./faq-and-help"><i class="fa fa-question"></i></a></li>
                        <li class="dropdown">
                            <a data-toggle="dropdown" href="<?php echo $account; ?>"><i class="fa fa-user"></i></a>
                            <ul class="dropdown-menu dropdown-menu-right">
<?php if ($logged) { ?>
                                <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
                                <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
                                <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
<?php } else { ?>
                                <li>
                                    <form class="login_form" action="<?php echo $quick_login_action; ?>" method="post" enctype="multipart/form-data">
                                        <?php if( !empty( $loginwarning ) ) : ?>
                                            <div class="alert alert-danger">
                                                <?php echo $loginwarning; ?>
                                            </div>
                                        <?php endif; ?>
                                        <label for="email">Email:</label>
                                        <input type="text" name="email" id="email" class="form-control" value="" />
                                        <label for="password">Password:</label>
                                        <input type="password" name="password" id="password" class="form-control" value="" />
                                        <input type="hidden" name="current_route" class="form-control" value="<?php echo $current_route; ?>" /> 
                                        <input type="hidden" name="quick_login" class="form-control" value="1" /> 
                                        <input type="submit" class="btn btn-yellow btn-block" id="" name="button_login" value="Login" />
                                        <a href="<?php echo $forgotten; ?>" id="link_forgotten">Forgotten Password?</a>
                                        <a href="<?php echo $register; ?>" class="btn btn-blue btn-block">Create An Account</a>
                                    </form>
                                </li>
<?php } ?>
                            </ul>
                        </li>
                        <li><a href="<?php echo $shopping_cart; ?>"><i class="fa fa-shopping-cart"></i></a></li>
                    </ul>
                </div>
                <div class="collapse navbar-collapse cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left <?php if( !empty( $loginwarning ) ) { echo 'in'; } ?>" id="bs-mobile-menu-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="user-menu">
                            <i class="fa fa-user"></i> Hello. <?php if ($logged) { ?><a href="<?php echo $account; ?>"><?php echo $username; ?></a><?php } else { ?><a href="#" data-toggle="collapse" data-target="#xs-form-login" id="mobile-sign-in">Sign in</a><?php } ?> <br />
<?php if ($logged) { ?>
                            <a href="<?php echo $account; ?>" class="btn btn-light-blue"><?php echo $text_account; ?></a>
                            <a href="<?php echo $order; ?>" class="btn btn-light-blue"><?php echo $text_order; ?></a>
<?php } else { ?>
<script>
$( "#mobile-sign-in" ).click(function( event ) {
  event.preventDefault();
});
</script>
                            <form id="xs-form-login" class="login_form collapse <?php if( !empty( $loginwarning ) ) { echo 'in'; } ?>" action="<?php echo $quick_login_action; ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="current_route" class="form-control" value="<?php echo $current_route; ?>" />
                                <input type="hidden" name="quick_login" class="form-control" value="1" />
                                <div class="row">
                                    <?php if( !empty( $loginwarning ) ) : ?>
                                            <div class="col-xs-12 alert alert-danger">
                                                <?php echo $loginwarning; ?>
                                            </div>
                                    <?php endif; ?>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label for="email" class="control-label">Email:</label>
                                            <input type="text" name="email" id="email" class="form-control" value="" />
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label for="password" class="control-label">Password:</label>
                                            <input type="password" name="password" id="password" class="form-control" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-xs-offset-3">
                                        <p class="text-center"><a href="<?php echo $forgotten; ?>" id="link_forgotten" class="btn btn-blue">Forgotten Password?</a><input type="submit" class="btn btn-yellow btn-block" id="" name="button_login" value="Login" /></p>
                                        <p class="text-center white font120">New to Gempacked?<a href="<?php echo $register; ?>" class="btn btn-block btn-blue btn-ouline">Create An Account</a></p>
                                    </div>
                                </div>
                            </form>
<?php } ?>
                            <a href="./index.php?route=product/wk_quick_order" class="btn btn-light-blue">Quick Add to Cart</a>
                        </li>
<?php if ($categories) { ?>
<?php foreach ($categories as $category) { ?>
<?php if ($category['children']) { ?>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
<?php foreach ($category['children'] as $child) { ?>
                                <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
<?php } ?>
                                <li><a href="<?php echo $category['href']; ?>"><strong><?php echo $text_all; ?> <?php echo $category['name']; ?></strong></a></li>
                            </ul>
                        </li>
<?php } else { ?>
                        <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
<?php } ?>
<?php } ?>
<?php } ?>
                    </ul>
                </div>
            </nav>
            <div id="xs-search" class="input-group">
                <input type="text" name="search" value="<?php echo $search; ?>" placeholder="Search" class="form-control input-lg" />
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default btn-lg"><i class="fa fa-search"></i></button>
                </span>
            </div>
<script>
    /* Search */
	$('#xs-search input[name=\'search\']').parent().find('button').on('click', function() {
		var url = $('base').attr('href') + 'index.php?route=product/search';

		var value = $('#xs-search input[name=\'search\']').val();

		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}

		location = url;
	});

	$('#xs-search input[name=\'search\']').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('#xs-search input[name=\'search\']').parent().find('button').trigger('click');
		}
	});
</script>

        </div>
    </div>
    <div id="tab-header" class="top-bar hidden">
        <div class="container-fluid">
            <ul class="list-unstyled pull-left">
                <li><a href="<?php echo $home; ?>" class="logo"><img src="catalog/view/theme/gempack/image/logo-phone.png" title="Gempacked" alt="Gempacked" class="img-responsive" /></a></li>
            </ul>
            <ul class="list-unstyled pull-right">
                <li class="dropdown">
                    <a data-toggle="dropdown" href="<?php echo $account; ?>" class="btn btn-yellow"><?php echo $text_account; ?></a>
                    <ul class="dropdown-menu dropdown-menu-right">
<?php if ($logged) { ?>
                        <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
                        <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
<!--                        <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>-->
<!--                        <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>-->
                        <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
<?php } else { ?>
                        <li>
                            <form class="login_form" action="<?php echo $quick_login_action; ?>" method="post" enctype="multipart/form-data">
                                <?php if( !empty( $loginwarning ) ) : ?>
                                    <div class="alert alert-danger">
                                        <?php echo $loginwarning; ?>
                                    </div>
                                <?php endif; ?>
                                <label for="email">Email:</label>
                                <input type="text" name="email" id="email" class="form-control" value="" />
                                <label for="password">Password:</label>
                                <input type="password" name="password" id="password" class="form-control" value="" />
                                <input type="hidden" name="current_route" class="form-control" value="<?php echo $current_route; ?>" />
                                <input type="hidden" name="quick_login" class="form-control" value="1" />
                                <input type="submit" class="btn btn-yellow" id="" name="button_login" value="Login" />
                                <a href="<?php echo $forgotten; ?>" id="link_forgotten" class="btn btn-blue">Forgotten Password</a>
                            </form>
                        </li>
<?php } ?>
                    </ul>
                </li>
                <li><a href="<?php echo $checkout; ?>" class="btn btn-yellow">Checkout</a></li>
            </ul>
        </div>
    </div>
    <div class="top-bar hidden-sm hidden-xs">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                      <ul class="list-inline account-menu">
<?php if ($logged) { ?>
                        <li>You are logged in as</li>
                        <li class="dropdown">
                            <a data-toggle="dropdown" href="<?php echo $account; ?>"><?php echo $username; ?></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
                                <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
<!--                                <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>-->
<!--                                <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>-->
                                <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>

                            </ul>
                        </li>
                        <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
<?php } else { ?>
                        <li>Welcome visitor!</li>
                        <li class="dropdown <?php if( !empty( $loginwarning ) ) { echo 'open'; } ?>">
                            <a data-toggle="dropdown" href="<?php echo $login; ?>">Login</a>
                            <ul class="dropdown-menu" id="login-desktop">
                                <li>
                                    <form class="login_form" action="<?php echo $quick_login_action; ?>" method="post" enctype="multipart/form-data">
                                        <?php if( !empty( $loginwarning ) ) : ?>
                                            <div class="alert alert-danger">
                                                <?php echo $loginwarning; ?>
                                            </div>
                                        <?php endif; ?>
                                        <label for="email">Email:</label>
                                        <input type="text" name="email" id="email" class="form-control" value="" />
                                        <label for="password">Password:</label>
                                        <input type="password" name="password" id="password" class="form-control" value="" />
                                        <input type="hidden" name="current_route" class="form-control" value="<?php echo $current_route; ?>" />
                                        <input type="hidden" name="quick_login" class="form-control" value="1" />
                                        <input type="submit" class="btn btn-yellow" id="" name="button_login" value="Login" />
                                        <a href="<?php echo $forgotten; ?>" id="link_forgotten" class="btn btn-blue">Forgotten Password</a>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        <li>or</li>
                        <li><a href="<?php echo $register; ?>">Register</a></li>
                        <li>for Wholesale Pricing.</li>
<?php } ?>
                    </ul>
                </div>
                <div class="col-sm-6">
                    <ul class="list-inline pull-right social-icons">
                        <li><a target="_blank" href="https://www.facebook.com/pages/Gempacked/455209827860178/"><i class="fa fa-facebook"></i></a></li>
                        <li><a target="_blank" href="https://twitter.com/gem_packed/"><i class="fa fa-twitter"></i></a></li>
                        <li><a target="_blank" href="https://www.gempacked.com/blog/?feed=atom"><i class="fa fa-rss"></i></a></li>
                        <li><a target="_blank" href="http://instagram.com/gempacked"><i class="fa fa-instagram"></i></a></li>
                        <li><a target="_blank" href="http://pinterest.com/gempacked"><i class="fa fa-pinterest"></i></a></li>
                        <li><a href="https://www.gempacked.com/faq-and-help"><i class="fa fa-question"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="logo-area hidden-sm hidden-xs">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 col-sm-4">
                    <div class="logo">
                        <a href="<?php echo $home; ?>"><img src="catalog/view/theme/gempack/image/logo_gem.png" title="Gempacked" alt="Gempacked" class="img-responsive" /></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-8">
                    <div class="top-cart text-right hidden-lg hidden-md">
                        <a href="<?php echo $shopping_cart; ?>" class="btn btn-blue"><i class="fa fa-shopping-cart"></i>Cart [ <span  id="cart-total-desktop2"></span> ]</a>
                        <a href="<?php echo $checkout; ?>" class="btn btn-yellow">Checkout</a>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="phone">
                                <i class="fa fa-comment"></i>Call Now:<br /><strong><a href="tel:+1 213.6294840"><?php echo $telephone; ?></a></strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                        </div>
                    </div>
                    <div id="search" class="input-group">
                        <input type="text" name="search" value="<?php echo $search; ?>" placeholder="Search" class="form-control input-lg" id="inner-search-mobile"/>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default btn-lg" id="search-mobile"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </div>
                <div class="col-lg-3 hidden-md">
                    <div class="top-space-align hidden-sm"></div>
                    <a href="/shipping-information"><img src="catalog/view/theme/gempack/image/top-shipping-banner.png" title="Free Shipping" alt="Free Shipping" class="img-responsive" /></a>
                </div>
                <div class="col-lg-3 col-md-4">
                    <div class="top-space-align hidden-sm"></div>
                    <?php echo $cart; ?>   
                    <div class="top-quick-addtocart">
                        <!-- <h3>Quick add to Cart</h3> -->
                        <a href="./index.php?route=product/wk_quick_order" class="btn btn-orange btn-block">Quick Add to Cart</a>
                        <!-- <p class="description">Enter Sku,Reference #, or Keyword</p>
                        <form method="GET" action="https://www.gempacked.com/product/search">
                            <input type="hidden" name="route" value="product/search" />
                            <div class="row" id="addtocart0">
                                <div class="col-sm-6"><input type="text" value="" autocomplete="off" placeholder="SKU,Ref #,Name" name="search" class="form-control" /></div>
                                <div class="col-sm-2"><input type="text" value="" name="quantity"  placeholder="Qty" class="form-control" /></div>
                                <div class="col-sm-4"><input type="button" class="btn btn-orange btn-block" onclick="addQuickBuy('#addtocart0')" value="Add To Cart" /></div>
                            </div>
                        </form> -->

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="quick-addtocart-tab-only hidden hidden-lg hidden-md hidden-xs">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                <a href="/shipping-information"><img src="catalog/view/theme/gempack/image/top-shipping-banner.png" title="Free Shipping" alt="Free Shipping" class="img-responsive" /></a>
                    <div class="row hidden">
                        <div class="col-sm-6">
                            <div class="phone">
                                <i class="fa fa-comment"></i>Call Now:<br /><strong><a href="tel:<?php echo $telephone; ?>"><?php echo $telephone; ?></a></strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="top-quick-addtocart">
                    <a href="./index.php?route=product/wk_quick_order" class="btn btn-orange btn-block">Quick Add to Cart</a>
                <!-- <h3>Quick add to Cart</h3>
                <p class="description">Enter Sku,Reference #, or Keyword</p>
                <form method="GET" action="https://www.gempacked.com/product/search">
                    <input type="hidden" name="route" value="product/search" />
                    <div class="row" id="addtocart0">
                        <div class="col-sm-6"><input type="text" value="" autocomplete="off" placeholder="SKU,Ref #,Name" name="search" class="form-control" /></div>
                        <div class="col-sm-2"><input type="text" value="" name="quantity"  placeholder="Qty" class="form-control" /></div>
                        <div class="col-sm-4"><input type="button" class="btn btn-orange btn-block" onclick="addQuickBuy('#addtocart0')" value="Add To Cart" /></div>
                    </div>
                </form> -->
            </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-menu hidden-sm hidden-xs">
        <div class="container">
            <nav class="menu">
                        <div class="main-menu-container">
                            <div id="container">
                                    <?php echo $supermenu; ?>
                                <?php if ($categories) { ?>
                                    </li>
                                <?php } ?>
                            </div>   
                        </div>
            		</nav>
            <script type="text/javascript">
                jQuery(document).ready(function() {
					var val=jQuery('.cartcount:eq(0)').html();
					jQuery('#cart-total').empty().html(val);
                    jQuery('.menutop').click(function() {
                        if (jQuery('.submenu').is(":hidden"))
                        {
                            jQuery('.submenu').slideDown("fast");
                        } else {
                            jQuery('.submenu').slideUp("fast");
                        }
                        return false;
                    });
                });
                window.addEventListener('orientationchange', function() {
                    if (window.orientation == 0 || window.orientation == 180) {
                        // Reset scroll position if in portrait mode.
                        window.scrollTo(0, 0);
                    }
                }, false);
            </script>
        </div>
    </div>  

</header>

<script type="text/javascript">
                $(document).ready(function() {
                    //tap on outside hide navigation menu
                    $(document).on('touchstart', function(e) {
                        var clickElement = e.target;  // get the dom element clicked.
                        var elementClassName = e.target.className;  // get the classname of the element clicked
                        if ($('.' + elementClassName).parents(".bigdiv").length == '0') {
                            $(".bigdiv").hide();
                        }
                    });
                    $("#ig_order_button").click(function() {
                        var product_sku = $(".ig_quick_order input#item_id ").val();
                        var item_quantity = $(".ig_quick_order input#item_quantity").val();

                        if (!product_sku) {
                            alert("Please Enter Product Reference Id.");
                            return false;
                        }
                        if (!item_quantity) {
                            alert("Please Enter Product Quantity.");
                            return false;
                        }
                        if ($.isNumeric(product_sku) == false) {
                            //alert("Product Reference Id must be numeric.");
                            //return false;
                        }
                        if ($.isNumeric(item_quantity) == false) {
                            alert("Product Quantity must be numeric.");
                            return false;
                        }
                        $.ajax({
                            url: 'index.php?route=checkout/cart/add',
                            type: 'post',
                            data: {'product_sku': product_sku, 'quantity': item_quantity},
                            dataType: 'json',
                            success: function(json) {
                                $('.success, .warning, .attention, information, .error').remove();

                                if (json['error']) {
                                    if (json['error']['option']) {
                                        for (i in json['error']['option']) {
                                            $('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
                                        }
                                    }

                                    if (json['error']['wrong_id']) {
                                        $('#notification').html('<div class="success" >' + json['error']['wrong_id'] + '<img src="catalog/view/theme/gempack/image/close.png" alt="" class="close" /></div>');
                                    }

                                    if (json['error']['profile']) {
                                        $('select[name="profile_id"]').after('<span class="error">' + json['error']['profile'] + '</span>');
                                    }
                                }

                                if (json['redirect']) {
                                    location = json['redirect'];
                                }

                                if (json['success']) {
                                    $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/gempack/image/close.png" alt="" class="close" /></div>');

                                    $('.success').fadeIn('slow');

                                    $('#cart-total').html(json['total']);

                                    $('html, body').animate({scrollTop: 0}, 'slow');
                                }
                            }
                        });
                    });
                    var wid = $(window).width();
                    if (wid <= 568) {
                        $(".ig_quick_order").hide();
                        $(".quick-order").show();
                        $(".quick-order").on('click', function() {
                            $(".ig_quick_order").slideToggle("fast");
                        });
                    }
                    else {
                        $(".quick-order").hide();
                    }
                });
</script>
<script type="text/javascript">
    var _mfq = _mfq || [];
        (function()
                {
                    var mf = document.createElement("script");
                    mf.type = "text/javascript";
                    mf.async = true;
                    mf.src = "//cdn.mouseflow.com/projects/651a5c16-12fc-4e7c-92f9-95ac9ca01be5.js";
                    document.getElementsByTagName("head")[0].appendChild(mf);
                }

    )();
</script>
<script type="text/javascript">
    var google_tag_params = {ecomm_prodid: ['564', '39698', '1818', '5760', '566'], ecomm_pagetype: 'product', ecomm_totalvalue: ''};
</script>
<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 984876444;
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
<noscript>  
<div style="display:inline;">
    <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/984876444/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<script type="text/javascript"><!--
                $(document).ready(function() {
						
					$('.welcome').find('a:eq(3)').addClass('resg'); 	
						$('iframe').wrap('<div class="iframe_div"></div>');

                });
</script>
<script>
$(document).ready(function () {

		$('#search-btn').bind('click', function() {
			url = $('base').attr('href') + 'index.php?route=product/search';
					 
			var search = $('#inner-search').val();
			
			if (search) {
				url += '&search=' + encodeURIComponent(search);
			}
			
			location = url;
		});

		$('#inner-search').bind('keydown', function(e) {
			
			if (e.keyCode == 13) {
				url = $('base').attr('href') + 'index.php?route=product/search';
				 
				var search = $('#inner-search').val();
				
				if (search) {
					url += '&search=' + encodeURIComponent(search);
				}
				
				location = url;
			}
		});
		
		$('#search-mobile').bind('click', function() {
			url = $('base').attr('href') + 'index.php?route=product/search';
					 
			var search = $('#inner-search-mobile').val();
			
			if (search) {
				url += '&search=' + encodeURIComponent(search);
			}
			
			location = url;
		});

		$('#inner-search-mobile').bind('keydown', function(e) {
			
			if (e.keyCode == 13) {
				url = $('base').attr('href') + 'index.php?route=product/search';
				 
				var search = $('#inner-search-mobile').val();
				
				if (search) {
					url += '&search=' + encodeURIComponent(search);
				}
				
				location = url;
			}
		});

		$('#add_to_cart').click(function () {
			$.ajax({
				
type: 'post',
url: 'index.php?route=module/cart/minicart',
dataType: 'html',
data: $('#product :input'),
success: function (html) {

var ggg = $('#header_cart .center').html(html);
alert(ggg);
}
			});
		});
});

jQuery(document).ready(function(){
	  
  jQuery('.ui-effects-wrapper').show();
  
  jQuery('#cart').click(function() {
		  if(jQuery('.baljit').hasClass('on')) {
			  
			  var effect = 'slide';

		  // Set the options for the effect type chosen
		  var options = { direction: 'right'};

		  // Set the duration (gempack: 400 milliseconds)
		  var duration = 500;

		  jQuery('.baljit').toggle(effect, options, duration); 
		  }
		  else
		  {
			  
			  var effect = 'slide';

		  // Set the options for the effect type chosen
		  var options = { direction: 'right'};

		  // Set the duration (gempack: 400 milliseconds)
		  var duration = 500;

		  jQuery('.baljit').toggle(effect, options, duration); 
		  }		
		  
		  

	  
  
	  
  });
  
  
  
  
  var is_touch_device = 'ontouchstart' in document.documentElement;


var viewport = jQuery(window).width();
if (viewport <=768) {
jQuery('.filters').addClass('closed'); 
}

jQuery('.newss').click(function()
{
jQuery('.logoHolder').toggleClass('open');

//jQuery('.logoHolder').hide();	
if(jQuery('.newo').hasClass('on'))
{

jQuery('.newo').removeClass('on');
jQuery('.newo').hide();
jQuery('.search-btn').hide();
jQuery('.input-search').removeClass('expanded'); 
jQuery('.ui-effects-wrapper').show();
}
else
{
jQuery('.newo').addClass('on');
jQuery('.newo').show();
jQuery('.search-btn').show();
jQuery('.input-search').addClass('expanded'); 
jQuery('.ui-effects-wrapper').show();
}  


});

  
  });
</script>

<div class="content-body">
	<div class="container-fluid">
		<div id="notification"></div>

