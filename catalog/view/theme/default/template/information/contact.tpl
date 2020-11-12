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

<div class="row-fluid " style="float: left;">
    <!--  contact form starts here-->
    <div class="contact-form span4">
        <h1 class="heading">Send us a message </h1>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
            <p><img src="catalog/view/theme/default/img/name-icon.jpg" alt="Name"/><input type="text" name="name" value="<?php echo $name; ?>" placeholder="Enter your Name" /></p>
            <?php if ($error_name) { ?>
                <span class="error"><?php echo $error_name; ?></span>
            <?php } ?>
            <p><img src="catalog/view/theme/default/img/mail-icon.jpg" alt="E-mail"/><input type="text" name="email" value="<?php echo $email; ?>" placeholder="E-mail address"  /></p>
            <?php if ($error_email) { ?>
                <span class="error"><?php echo $error_email; ?></span>
            <?php } ?>
            <p><img src="catalog/view/theme/default/img/massege-icon.jpg" alt="Message"/><input type="text" name="enquiry_subject" value="<?php echo $enquiry; ?>" placeholder="Message Subject" /></p>

            <p><img src="catalog/view/theme/default/img/message-icon.jpg" alt="Message"/>  <textarea  name="enquiry" placeholder="Enter your message"></textarea></p>
            <?php if ($error_enquiry) { ?>
                <span class="error"><?php echo $error_enquiry; ?></span>
            <?php } ?> 
                <div class="clearfix"></div>
            <input type="checkbox" name="sendcopy" class="chek"><span class="chek-text">Email a copy to yourself.</span>
            <div style="clear:both"></div>
            <?php echo $captcha; ?>
            <input type="submit" value="<?php echo $button_continue; ?>" class="send"/>
        </form>
    </div>
    <!--  contact form end here-->

    <!--  map starts here-->
    <div class="map span4">
        <h2 class="heading head1">Our Location</h2>
        <div class="clearfix"></div>
        <div class="flex-video widescreen">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3305.868709238841!2d-118.25574198441285!3d34.04723878060732!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c2c7b5268cc5e3%3A0x34680102563c9bb6!2sBella+Findings!5e0!3m2!1sen!2sus!4v1507591259221" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe> 
            <!-- <iframe  style="border:0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=+Hill+Street+Arcade+%236,+Los+Angeles&amp;aq=&amp;sll=39.181175,-97.294922&amp;sspn=8.547462,21.643066&amp;ie=UTF8&amp;hq=hill+street+arcade+6&amp;hnear=Los+Angeles,+California&amp;t=m&amp;fll=34.042539,-118.264158&amp;fspn=0.002231,0.005284&amp;st=115664277548083516147&amp;rq=1&amp;ev=p&amp;split=1&amp;ll=33.955175,-118.320958&amp;spn=0.002231,0.005284&amp;output=embed"></iframe> --> 
            <br /><small></small>
        </div>
        <p class="store"><strong>Store Hours</strong></p>
        <p class="map-time">(All times listed in Pacific Standard Time)<br/>
            <em> • </em>Monday-Friday: 9am-5pm<br>
<!--            <em> • </em>Saturday: 10am-4pm<br>  -->
            <em> • </em>Saturday and Sunday: Closed</p>
                <em> • </em>New Years Day, Memorial Day, July 4th/Independence Day, Labor Day, Thanksgiving, Christmas Day: Closed</p>						
                            </div>    </div>
    <!--  map end here-->

    <!--  connect  starts here-->
    <div class="connect-block span4">
        <h2 class="heading head1">Connect with us</h2>
        <div class="clearfix"></div>
        <p><span><img class="contact-icon" src="catalog/view/theme/default/img/call-us.png" alt="phone"/></span><strong>Phone:</strong> <a href="tel:<?php echo $telephone; ?>"><?php echo $telephone; ?></a></p>
        <p><span><img class="contact-icon" src="catalog/view/theme/default/img/email.png" alt="Email"/></span><strong><a href="mailto:<?php echo $admin_email; ?>">Contact by Email</a></strong> </p>
        <p><span><img src="catalog/view/theme/default/img/social-facebook.png" alt="Facebook"/> </span><strong><a target="_blank" href="https://www.facebook.com/pages/Gempacked/455209827860178">Like on Facebook</a></strong></p>
        <p><span> <img src="catalog/view/theme/default/img/instagram-icon.jpg" alt="instagram"/></span><strong><a target="_blank" href=" http://instagram.com/gempacked">Follow us on Instagram</a></strong></p>
         <p><span> <img src="catalog/view/theme/default/img/header_icon_pinterest.jpg" alt="pintrest"/></span><strong><a target="_blank" href="http://pinterest.com/gempacked">Share us on Pinterest</a></strong></p>
           <p> <span><img src="catalog/view/theme/default/img/twitter-logo.png" alt="Twitter"/></span><strong><a target="_blank" href="https://twitter.com/gem_packed/">Follow on Twitter</a></strong></p>
    </div>
    <!--  connect  end here-->
</div>
<?php echo $footer; ?>
