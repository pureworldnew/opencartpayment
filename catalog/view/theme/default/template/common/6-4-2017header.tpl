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
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
		<link href="catalog/view/theme/default/stylesheet/font.css" rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet.css" />
        <link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/style.css" />
        <link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/jquery.selectbox.css" />
        <link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/jquery.autocomplete.css" />
        <link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/flexslider.css" />
        <link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/jcarousel-tango-skin.css" />
        <link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/bootstrap-responsive.css" />
        
        <link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
        <link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/colorbox/colorbox.css" />
		<link rel="stylesheet" type="text/css" href="catalog/view/supermenu/newone.css" />
        <link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/quick_login.css" />
		<?php foreach ($styles as $style) { ?>
        	<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
        <?php } ?>
        
		<?php //echo $supermenu_settings; ?>
		<script type="text/javascript">
            var BASE_URL = "<?php echo HTTP_SERVER; ?>";
        </script>
        
		<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
        <script type="text/javascript" src="catalog/view/javascript/jquery/jquery.ui.touch-punch.min.js"></script>
        <script type="text/javascript" src="catalog/view/javascript/jquery/jquery.selectbox-0.2.min.js"></script>
        <script type="text/javascript" src="catalog/view/javascript/jquery/autocomplete.js"></script>
        <script type="text/javascript" src="catalog/view/javascript/jquery.accordion.source.js"></script>
        <script type="text/javascript" src="catalog/view/javascript/bootstrap.min.js"></script>
        <script type="text/javascript" src="catalog/view/javascript/common.js"></script>
        <script type="text/javascript" src="catalog/view/javascript/jquery.autocomplete.js"></script>
        <script type="text/javascript" src="catalog/view/javascript/jquery.myjcarousel.js"></script>
        <script type="text/javascript" src="catalog/view/javascript/jquery.flexslider-min.js"></script>
        <script type="text/javascript" src="catalog/view/javascript/jquery.placeholder.js"></script>
        <script type="text/javascript" src="catalog/view/javascript/jquery/jquery.validate.js"></script>
        
        
		
        
        <script type="text/javascript" src="catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js"></script>
        <script type="text/javascript" src="catalog/view/javascript/jquery/tabs.js"></script> 
        <script type="text/javascript" src="catalog/view/javascript/quick_login.js?v=<?php echo rand();?>"></script> 

<!--<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/bootstrap.css" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,700,600italic,700italic,800,800italic' rel='stylesheet' type='text/css'>
<link href="catalog/view/theme/gempacked2016/stylesheet/stylesheet.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="catalog/view/theme/gempacked2016/stylesheet/quick_login.css" />-->

<!--<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<script src="catalog/view/javascript/quick_login.js" type="text/javascript"></script>-->

<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php foreach ($analytics as $analytic) { ?>
<?php echo $analytic; ?>
<?php } ?>
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
			
<?php echo $supermenu_settings; ?>
</head>
<body class="<?php echo $class; ?>">
<!--<nav id="top">
  <div class="container">
    
	<div id="top-links" class="nav pull-right">
      <ul class="list-inline">
        <li><a href="<?php echo $contact; ?>"><i class="fa fa-phone"></i></a> <span class="hidden-xs hidden-sm hidden-md"><?php echo $telephone; ?></span></li>
        <li class="dropdown"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_account; ?></span> <span class="caret"></span></a>
          <ul class="dropdown-menu dropdown-menu-right">
            <?php if ($logged) { ?>
            <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
            <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
            <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
            <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
            <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
            <?php } else { ?>
            <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
            <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
            <?php } ?>
          </ul>
        </li>
        <li><a href="<?php echo $wishlist; ?>" id="wishlist-total" title="<?php echo $text_wishlist; ?>"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_wishlist; ?></span></a></li>
        <li><a href="<?php echo $shopping_cart; ?>" title="<?php echo $text_shopping_cart; ?>"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_shopping_cart; ?></span></a></li>
        <li><a href="<?php echo $checkout; ?>" title="<?php echo $text_checkout; ?>"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_checkout; ?></span></a></li>
      </ul>
    </div>
  </div>
</nav>-->
<header>
	<div id="header">
    				<div class="headerMiddle tab_menu visible-tablet visible-phone">
						<nav class="menu">
							<div class="main-menu-container">
								<?php echo $supermenu; ?>
								<?php if ($categories) { ?>
									</li>
								<?php } ?>  
							</div>
						</nav>
			
					</div>
					<div class="headerBottom visible-tablet">
						<div class="container">
							<div class="row-fluid">
								<div class="span7 col-xs-8 pull-right text-right">
									
									
								</div>
							</div>
						</div>
						<div id="menu-toggle" class="visible-xs">
							<div id="menu-icon">
								<span></span>
								<span></span>
								<span></span>
								<span></span>
								<span></span>
								<span></span>
							</div>
						</div>
					</div>
      <div class="header_strip">
		<div class="container-fluid width1140px">
			<div class="row-fluid head_line">
            	<div class="span5 col-xs-4 visible-tablet">
									<div class="logoHolder">
										 <a href="<?php echo $home; ?>">
										<img title="" alt="" src="catalog/view/theme/default/images/logo_gem.png">
										</a>
									</div>
								</div>
                <div class="span5 col-xs-4 visible-phone">
									<div class="logoHolder">
										 <a href="<?php echo $home; ?>">
										<img title="" alt="" src="catalog/view/theme/default/images/logo-phone.png">
										</a>
									</div>
								</div>
            	<div class="span7">
                	<div class="visible-tablet visible-phone">
						<div class="searchHolder">
										 <div class="row-fluid">
                                            <div class="search-block span12"> 
                                                <div id="search">
                                                    <div class="row-fluid" >
														<div class="newo">
                                                            <input type="text" class="span9 input-search" name="search" id="inner-search-mobile" placeholder="" value="" />
                                                            <input type="button" class="span3 search-btn" value="Search" id="search-mobile"/>
															</div>
															<div class="iconss newss"></div> 
                                                    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
										
										
									</div>
					</div>
                    
                    <div class="visible-tablet visible-phone">
										<div class="question">
										<?php  $text = $base; ?>


										
										 <a href="<?php echo $text.'?route=information/information&information_id=5'; ?>"><img src="catalog/view/theme/default/images/question.png" alt="" title="" />
											</a>
										</div>
									</div>
            		<?php echo $quick_login; ?>
               		<div class="visible-tablet visible-phone">
					

								<?php echo $cart_mobile;?>
											
							
										
									</div>
                </div>
                <div class="span5 visible-desktop">
                                    <div class="help">
                                        <div class="ig_social_small">
                                            <ul>
                                                <li><a target="_blank" href="https://www.facebook.com/pages/Gempacked/455209827860178/" class="fb ig_icon_small"></a></li>
                                                <li><a target="_blank" href="https://twitter.com/gem_packed/" class="tw ig_icon_small"></a></li>
                                                <li><a target="_blank" href="<?php echo HTTP_SERVER; ?>blog/?feed=atom" class="rss ig_icon_small"></a></li>
                                                <li><a target="_blank" href="http://instagram.com/gempacked" class="ins ig_icon_small"></a></li>
                                                <li><a target="_blank" href="http://pinterest.com/gempacked" class="pin ig_icon_small"></a></li>
                                            </ul>
                                        </div>
                                        <div class="ig_help">
                                            <div class="wrapselectorx"> 
                                                <?php //echo $currency; ?>
                                                <?php //echo $language; ?>
                                            </div>
                                            <span><a href="<?php echo $help_url; ?>" ><img src="catalog/view/theme/default/img/question-icon.png" alt="HELP"/></a></span>
                                        </div>
                                    </div>
                                </div>
                
            	
            		
          	</div>
          <div class="col-sm-5"><?php //echo $search; ?>
          </div>
          <div class="col-sm-3"><?php // echo $cart; ?></div>
        </div>
      </div>
      
	</div>
    <div class="container-fluid width1140px">
                        <div class="row-fluid header_top">

                            <div class="row-fluid">
                                <div class="span2 b1">
                                    <div class="logo">
                                        <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a>
                                    </div>
                                </div>
                             	 
                                 
                                <div class="span7 b1">
                                    <div class="row-fluid">
                                        <div class="span12 hide-call">
                                            <?php
                                            if ($callme != "call us"):
                                                ?>
                                                <div class="call row-fluid">
                                                    <div class="span9 feel-free">
                                                        <span class="call_now" ><?php echo $callme; ?></span>
                                                    </div>
                                                    <div class="span3 shipping_main">
                                                        <div class="shipping_day">
                                                            <div class="fast">
                                                                <p class=""><img style="max-width:99% !important;" src="catalog/view/theme/default/image/Free-shipping.png" alt=""/></p>
                                                                <p><a href="<?php echo HTTP_SERVER; ?>index.php?route=information/information&amp;information_id=6">View Details</a></p>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>
                                                <?php
                                            else:
                                                ?>
                                                <div class="call_off">
                                                    <div class="span3">
                                                        <div class="call_now_content">
                                                            <span class="call_now_off" >Call Now:</span>
                                                            <img src="catalog/view/theme/default/img/call-now-icon.png" alt="call now" />
                                                            <span class="contact_no"><?php echo $telephone; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="span9">
                                                        <div class="shipping">
                                                            <div class="clearfix fast">
                                                                <p class=""><img src="catalog/view/theme/default/img/fast.png" alt=""/></p>
                                                                <p><a href="<?php echo HTTP_SERVER; ?>index.php?route=information/information&amp;information_id=6">View Details</a></p>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>
                                            <?php
                                            endif;
                                            ?>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="search-block span12"> 
                                                <?php echo $search;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="span3 b1">
                                    <div class="row-fluid">
                                        <div class="span12">
                                        	<div class="shopping-cart">
                                          		<?php echo $cart;?>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <button class="quick-order">Click to View Quick Order</button>
                                                <div class="clearfix"></div>
                                                <div class ="quick_order_module ig_quick_order">
                                                    
                                                        <?php echo $ecquickbuy; ?>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                               
                            </div>
                        </div>
                    </div>
                    
                    <nav class="menu visible-desktop">
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
        	
    
</header>
<div class="container-fluid width1140px">
            <div id="notification"></div>
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
                                        $('#notification').html('<div class="success" >' + json['error']['wrong_id'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
                                    }

                                    if (json['error']['profile']) {
                                        $('select[name="profile_id"]').after('<span class="error">' + json['error']['profile'] + '</span>');
                                    }
                                }

                                if (json['redirect']) {
                                    location = json['redirect'];
                                }

                                if (json['success']) {
                                    $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

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
                var google_tag_params =
                        {ecomm_prodid: ['564', '39698', '1818', '5760', '566'], ecomm_pagetype: 'product', ecomm_totalvalue: ''};
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
						
$(document).ready(function() { 

	/*
    $('.filter-show-hide-search').click(function() {
         $('.filter-show_search').show();
    });
$('.filter-show-hide-search').click(function() {
         $('.filter-show_search').hide(); 
    });
	
	*/
	/*
	jQuery('.filter-show-hide-search').click(function()
	{
		alert('aaaaa');
			
	});
	*/
	
});


$(document).ready(function () {

		$('#search-btn').bind('click', function() {
			url = $('base').attr('href') + 'index.php?route=product/search';
					 
			var search = $('#inner-search').attr('value');
			
			if (search) {
				url += '&search=' + encodeURIComponent(search);
			}
			
			location = url;
		});

		$('#inner-search').bind('keydown', function(e) {
			
			if (e.keyCode == 13) {
				url = $('base').attr('href') + 'index.php?route=product/search';
				 
				var search = $('#inner-search').attr('value');
				
				if (search) {
					url += '&search=' + encodeURIComponent(search);
				}
				
				location = url;
			}
		});
		
		$('#search-mobile').bind('click', function() {
			url = $('base').attr('href') + 'index.php?route=product/search';
					 
			var search = $('#inner-search-mobile').attr('value');
			
			if (search) {
				url += '&search=' + encodeURIComponent(search);
			}
			
			location = url;
		});

		$('#inner-search-mobile').bind('keydown', function(e) {
			
			if (e.keyCode == 13) {
				url = $('base').attr('href') + 'index.php?route=product/search';
				 
				var search = $('#inner-search-mobile').attr('value');
				
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
  /*								
  jQuery('#cart').mouseover(function(){
	  alert('aaaa');
   jQuery('.cartpag').show();
	  
  }); 
  
  jQuery('.cartpag').mouseout(function()
  {
	  alert('bbbb');
	  jQuery('.cartpag').hide();
  });
  */
  
  jQuery('#cart').click(function()
  {
	  
		  
		  
		  
		  if(jQuery('.baljit').hasClass('on'))
		  {
			  
			  var effect = 'slide';

		  // Set the options for the effect type chosen
		  var options = { direction: 'right'};

		  // Set the duration (default: 400 milliseconds)
		  var duration = 500;

		  jQuery('.baljit').toggle(effect, options, duration); 
		  }
		  else
		  {
			  
			  var effect = 'slide';

		  // Set the options for the effect type chosen
		  var options = { direction: 'right'};

		  // Set the duration (default: 400 milliseconds)
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
