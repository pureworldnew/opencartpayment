<?php
/*
  #file: catalog/language/english/product_grouped_mask.php
  #powered by fabiom7 - www.fabiom7.com - fabiome77@hotmail.it - copyright fabiom7 2012 - 2013 - 2014
*/

// Text (displayed in the pages category,manufacturers,specials,etc and modules bestseller,related,featured,etc)
$_['text_price_start']         = 'Starting at';
$_['text_price_start_special'] = '<sup style="font-size:100%;">Starting at</sup>';//Read Special Price Instruction below
$_['text_price_from']          = 'From: ';
$_['text_price_to']            = '<br />To: ';
$_['text_mask_stock']          = 'N/A';
//$_['text_mask_model']          = 'N/A';
$_['text_mask_model']          = 'grouped';

/* ***********************
Special Price Instruction:
If the style of special price not work correctly, Open: 
- catalog/view/theme/ YOUR THEME /template/product/ (category.tpl, compare.tpl, manufacturer_info.tpl, search.tpl)
- catalog/view/theme/ YOUR THEME /template/module/ (bestseller.tpl, featured.tpl, latest.tpl)
and find $product['price'] into the <tag class="price-old"> in product special price section.

By defaul theme, it is: <span class="price-old"><?php echo $product['price']; ?></span>
If in your theme are used, as example <div class="price-old"> or <p class="price-old"> or <p class="price-special"> or similar

REPLACE in this file:
  $_['text_price_start_special'] = '<sup style="font-size:100%;">Starting at</sup>';
with:  
  $_['text_price_start_special'] = '</span>Starting at <span class="price-old">';//this is fine for default and many themes
or:
  $_['text_price_start_special'] = '</div>Starting at <div class="price-old">';
or:
  $_['text_price_start_special'] = '</p>Starting at <p class="price-special">';
or:
  $_['text_price_start_special'] = 'Starting at';
etc...

Please note which the "text-decoration" can not be removed into tags.
*********************** */
?>