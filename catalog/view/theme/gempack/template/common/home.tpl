<?php echo $header; ?>
<script type='application/ld+json'>
{
    "@context":"http://schema.org",
    "@type":"Website",
    "url":"https://www.gempacked.com",
    "Name":"GemPacked by Bella Findings",
    "potentialAction":{
        "@type":"SearchAction",
        "target":"https://www.gempacked.com/index.php?route=product/search&search={search_term_string}",
        "query-input":"required name=search_term_string"
        }
}
</script>
<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "Corporation",
  "name": "Gempacked",
  "alternateName": "Bella Findings",
  "url": "https://www.gempacked.com/",
  "logo": "https://www.gempacked.com/image/catalog/xlogo_gem.png.pagespeed.ic.ZydoVqMQi8.webp",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+12136294840",
    "contactType": "customer service",
    "areaServed": "US",
    "availableLanguage": "English"
  },
    "potentialAction":{
        "@type":"SearchAction",
        "target":"https://www.gempacked.com/index.php?route=product/search&search={search_term_string}",
        "query-input":"required name=search_term_string"
        },
    "sameAs": [
    "https://www.facebook.com/Gempacked-Wholesale-Jewelry-Parts-and-Beads-455209827860178/",
    "https://twitter.com/gem_packed/",
    "https://www.instagram.com/gempacked/",
    "https://www.pinterest.com/gempacked/"
  ]
} </script>
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
            <?php echo $content_bottom; ?>
	    </div>
        <?php echo $column_right; ?>
    </div>
<?php echo $footer; ?>