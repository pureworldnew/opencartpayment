<?php echo $header; ?>
<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "JewelryStore",
  "name": "Bella Findings",
  "image": "https://www.gempacked.com/image/catalog/xlogo_gem.png.pagespeed.ic.ZydoVqMQi8.webp",
  "@id": "",
  "url": "https://www.gempacked.com/",
  "telephone": "2138789190",
  "priceRange": "2",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "607 S Hill St",
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
  "hasmap":"https://www.google.com/maps/place/Bella+Findings/@34.0473934,-118.2536521,21z/data=!4m12!1m6!3m5!1s0x0:0x34680102563c9bb6!2sBella+Findings!8m2!3d34.047301!4d-118.253606!3m4!1s0x0:0x34680102563c9bb6!8m2!3d34.047301!4d-118.253606?hl=en-US",
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
    "https://www.facebook.com/Gempacked-Wholesale-Jewelry-Parts-and-Beads-455209827860178/",
    "https://twitter.com/gem_packed/",
    "https://www.instagram.com/gempacked/",
    "https://www.pinterest.com/gempacked/"
  ],
  "subOrganization":{
  "@type":"Organization", 
  "@id":"www.gempacked.com",
  "name":"Gempacked"}
}
</script>
	<div class="row">
        <?php echo $column_left; ?>
<?php
if ($column_left and $column_right) {
    $class="col-lg-8 col-md-6 col-sm-4 col-xs-12";
} elseif ($column_left or $column_right) {
     $class="col-lg-10 col-md-9 col-sm-8 col-xs-12";
} else {
     $class="col-xs-12";
}
?>
        <div class="<?php echo $class; ?>" id="content">
            <?php echo $content_top; ?>
            <ul class="breadcrumb">
<?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
<?php } ?>
            </ul>
            <h1><?php echo $heading_title; ?></h1>
			<div class="row">
					<div class="col-md-4 col-sm-6 contact-form">
						<h3>Send us a message</h3>
				<form action="<?php echo $action; ?>" method="post" id="contact_us" enctype="multipart/form-data">
							<p><input type="text" value="<?php echo $name; ?>" name="name" placeholder="Enter your Name" class="form-control">
							<?php if ($error_name) { ?>
                <span class="error"><?php echo $error_name; ?></span>
            <?php } ?>
							</p>
							<p><input type="text" name="email" placeholder="E-mail address" class="form-control" value="<?php echo $email; ?>"></p>
							<?php if ($error_email) { ?>
                <span class="error"><?php echo $error_email; ?></span>
            <?php } ?>
							<p><input type="text" name="enquiry_subject" placeholder="Message Subject" value="<?php echo $enquiry; ?>" class="form-control"></p>
							<p><textarea name="enquiry" id="enquiry" placeholder="Enter your message" class="form-control"></textarea></p>
              <span class="error" id="ierror" style="display:none;color:red">Inquiry must be between 10 and 3000 characters.</span>
							 <?php if ($error_enquiry) { ?>
                <span class="error"><?php echo $error_enquiry; ?></span>
            <?php } ?>
							<div class="clearfix"></div>
							<p><input type="checkbox" name="sendcopy" class="chek"><span class="chek-text">Email a copy to yourself.</span></p>
							<div class="clearfix"></div>
              <?php echo $captcha; ?>
              <input type="hidden" name="current_route" value="<?php echo $current_route_page; ?>">
              <input type="button" id="sub" class="btn btn-default" value="<?php echo $button_continue; ?>" />
						</form>
					</div>
					<div class="col-md-4 col-sm-6">
						<hr class="hidden-lg hidden-md hidden-sm" />
						<h3>Connect with us</h3>
						<ul class="connect-info list-unstyled">
							<li><i class="fa fa-phone"></i> Phone: <a href="tel:<?php echo $telephone; ?>"><?php echo $telephone; ?></a></li>
							<li><i class="fa fa-envelope"></i> <a href="mailto:<?php echo $admin_email; ?>" >Contact by Email</a></li>
							<li><i class="fa fa-facebook"></i> <a target="_blank" href="https://www.facebook.com/pages/Gempacked/455209827860178">Like on Facebook</a></li>
							<li><i class="fa fa-instagram"></i> <a target="_blank" href=" http://instagram.com/gempacked">Follow us on Instagram</a></li>
							<li><i class="fa fa-pinterest"></i> <a target="_blank" href="http://pinterest.com/gempacked">Share us on Pinterest</a></li>
							<li><i class="fa fa-twitter"></i> <a target="_blank" href="https://twitter.com/gem_packed/">Follow on Twitter</a></li>
						</ul>
					</div>
					<div class="col-md-4 col-sm-12 col-xs-12">
						<hr class="hidden-lg hidden-md" />
						<h3>Our Location</h3>
						<div class="location-map">
	                         <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3305.868709238841!2d-118.25574198441285!3d34.04723878060732!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c2c7b5268cc5e3%3A0x34680102563c9bb6!2sBella+Findings!5e0!3m2!1sen!2sus!4v1507591259221" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
							
						</div>
						<hr />
						<h3>Store Hours</h3>
						<p>(All times listed in Pacific Standard Time)</p>
						<ul>
							<li>Monday-Friday: 9am-5pm</li>
				    <li>Saturday and Sunday: Closed</li>
            <li>New Years Day, Memorial Day, July 4th/Independence Day, Labor Day, Thanksgiving, Christmas Day: Closed</li>						
            </ul>
					</div>
				</div>
            <?php echo $content_bottom; ?>
	    </div>
        <?php echo $column_right; ?>
    </div>
    <script>
    $(document).ready(function(){
          $("#sub").click(function(){
              txt = $("#enquiry").val().length;
              if (txt < 10) {
                $("#ierror").css('display','block');
              }else{
                $("#ierror").css('display','none');
                $("#contact_us").submit();
              }
          });
    });
    </script>
<?php echo $footer; ?>