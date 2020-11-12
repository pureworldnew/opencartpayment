	</div>
</div>
<footer>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-2 col-md-3 col-sm-6">
       
			 <?php if ($informations) { ?>
                        <div class="span5">
                             <ul class="list-unstyled">
                                <?php foreach ($informations as $information) { ?>
                                    <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
                                <?php } ?>
                                <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
                            </ul>
                        </div>
                    <?php } ?>
          
      </div>
      <div class="col-lg-2 col-md-3 col-sm-6">
        <ul class="list-unstyled">
     <li><a href="<?php echo HTTP_SERVER; ?>blog/">Blog</a></li>
                            <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
                            <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-3 col-sm-6">
        <p><strong>Stay Connected</strong></p>
        <ul class="list-inline social-icons">
          <li><a target="_blank" href="https://www.facebook.com/pages/Gempacked/455209827860178/"><i class="fa fa-facebook"></i></a></li>
          <li><a target="_blank" href="https://twitter.com/gem_packed/"><i class="fa fa-twitter"></i></a></li>
          <li><a target="_blank" href="https://www.gempacked.com/blog/?feed=atom"><i class="fa fa-rss"></i></a></li>
          <li><a target="_blank" href="http://instagram.com/gempacked"><i class="fa fa-instagram"></i></a></li>
          <li><a target="_blank" href="http://pinterest.com/gempacked/"><i class="fa fa-pinterest"></i></a></li>
        </ul>
      </div>
      <div class="col-lg-offset-4 col-lg-2 col-md-3 col-sm-6">
        <ul class="list-unstyled">
          <li><strong>Contact Us</strong></li>
          <li> <?php echo $address; ?></li>                         
          <li><a href="tel:<?php echo $telephone; ?>"><?php echo $telephone ;?></a></li>
          <li><a href="mailto:sales@gempacked.com">sales@gempacked.com</a></li>
          <li><img src="catalog/view/theme/gempack/image/satisfaction.png" alt="100% Satisfaction" class="img-responsive pull-left"/> <img src="catalog/view/theme/gempack/image/paypal.png" alt="Paypal varified" class="img-responsive pull-left" /></li>
        </ul>
      </div>
    </div>
  </div>
</footer>
<div class="copyrights">
  <div class="container-fluid">&copy; <?php echo date("Y"); ?> Gempacked.com. All rights reserved.</div>
</div>
</body>
</html>