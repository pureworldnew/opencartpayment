</div>
<!--footer starts here-->
<div id="footer" class="wrapper"> 
    <div class="container-fluid width1140px">
        <div class="row-fluid">
            <div class="span9">
                <div class="row-fluid">
                    <?php if ($informations) { ?>
                        <div class="span5">
                            <ul class="foot-list">
                                <?php foreach ($informations as $information) { ?>
                                    <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
                                <?php } ?>
                                <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
                            </ul>
                        </div>
                    <?php } ?>
                    <div class="span7">
                        <ul class="foot-list">
                            <li><a href="<?php echo HTTP_SERVER; ?>blog/">Blog</a></li>
                            <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
                            <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
                        </ul>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="row-fluid visible-desktop">
                    <div class="span4" style="margin-left: 0;">
                        <div class="footer-social-icons">
                            <p class="stay ">Stay Connected</p>
                            <div class="ig_social_large">
                                <ul>
                                <li><a target="_blank" href="https://www.facebook.com/pages/Gempacked/455209827860178/" class="fb ig_icon_large"></a></li>
                                <li><a target="_blank" href="https://twitter.com/gem_packed/" class="tw ig_icon_large"></a></li>
                                <li><a target="_blank" href="<?php echo HTTP_SERVER; ?>blog/?feed=atom" class="rss ig_icon_large"></a></li>
                                <li><a target="_blank" href="http://instagram.com/gempacked" class="ins ig_icon_large"></a></li>
                                <li><a target="_blank" href="http://pinterest.com/gempacked/" class="pin ig_icon_large"></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <p class="cpy">© <?php echo date('Y'); ?> Gempacked.com. All rights reserved.</p>
                </div>
            </div>
            <div class="span3">
                <div class="contact_info">
                    <p class="add"><strong>Contact Us</strong><br/>
                        <?php echo $address; ?><br/>                        
                        <a href="tel:<?php echo $telephone; ?>"><?php echo $telephone ;?></a>
                        <br/>
                        <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a> </p>
                    <p class="links"><img src="catalog/view/theme/default/img/satisfaction.png" alt="100% Satisfaction"/><img src="catalog/view/theme/default/img/paypal.png" alt="Paypal varified"/></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="visible-tablet visible-phone ">
<div class="footerSocial text-center">
	<div class="row-fluid">
		<div class="span12">
			<div class="footer-social-icons">
				<div class="ig_social_large">
					<ul>
					<li><a target="_blank" href="https://www.facebook.com/pages/Gempacked/455209827860178/" class="fb ig_icon_large"></a></li>
					<li><a target="_blank" href="https://twitter.com/gem_packed/" class="tw ig_icon_large"></a></li>
					<li><a target="_blank" href="<?php echo HTTP_SERVER; ?>blog/?feed=atom" class="rss ig_icon_large"></a></li>
					<li><a target="_blank" href="http://instagram.com/gempacked" class="ins ig_icon_large"></a></li>
					<li><a target="_blank" href="http://pinterest.com/gempacked/" class="pin ig_icon_large"></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="copy">
	<div class="row-fluid">                   
		<div class="span12">                   
			<p class="cpy">© <?php echo date('Y'); ?> Gempacked.com. All rights reserved.</p>
		</div>
	</div>
</div>
<div class="footerBottom">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<a href="#top"><span><img title="" alt="" src="catalog/view/theme/default/images/top.png"></span> Go to top</a>
			</div>
		</div>
	</div>
</div>
</div>

<!--footer end here--> 
<script type="text/javascript">
    // <![CDATA[
    $(function() {
        $('input, textarea').placeholder();
    });
    $('document').ready( function() {
        $(".search-block input[type='text']")
        .autocomplete(
        'index.php?route=common/header/ajaxsearch/ajaxsearch&search', {
            minChars: 3,
            max: 10,
            width: 583, 
            selectFirst: false,
            scroll: false,
            dataType: "json",
            formatItem: function(data, i, max, value, term) {
                return value;
            },  
            parse: function(data) {
                var mytab = new Array();
                for (var i = 0; i < data.length; i++)
                    mytab[mytab.length] = { data: data[i], value: '<img src ='+data[i].image+'>&nbsp;&nbsp;&nbsp;&nbsp;'+ data[i].pname };
                return mytab;
            },
            extraParams: {
                ajaxSearch: 1,
                id_lang: 1
            }
        }
    )   
        .result(function(event, data, formatted) {
            $(".search-block input[type='text']").val(data.pname);
            document.location.href = data.product_link;
        })
    });
</script>
<script type="text/javascript">

    $(document).ready(function()
    {
        var docHeight = $(window).height();//626
        var footerHeight = $('#footer').height();
        var footerTop = $('#footer').position().top + footerHeight;
        if (footerTop < docHeight) {
            $('#footer').css('margin-top', 10 + (docHeight - footerTop) + 'px');
        }
 	});

</script>
</body>
</html>