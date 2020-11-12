<h3><?php echo $PanelName; ?></h3>
<div class="product-grid">
                        <?php
                            foreach ($products as $product) {
                                ?>
                                <div class="category-newone">

                                    <?php if ($product['thumb']) {
                                        ?>
                                        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>"></a></div>
                                    <?php } else { ?>
                                        <div class="image"><a href="<?php echo $product['href']; ?>"><img style="width:150px; height:150px;" src="<?php echo HTTPS_SERVER; ?>catalog/view/theme/default/image/no-image.gif" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>"></a></div>
                                    <?php } ?>
                                    <div class="name prodcut_category_name"><a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php
                                            if (strlen($product['name']) > 36) {
                                                echo substr($product['name'], 0, 36) . "...";
                                            } else {
                                                echo $product['name'];
                                            }
                                            ?></a>
                                    </div>
                                    <div class="description catee-des"><?php echo $product['description']; ?></div>
                                    <?php if ($product['price']) { ?>
                                        <div class="price price_cat_product">
                                            <?php if (!$product['special']) { ?>
                                                <?php echo $product['price'] . $product['unit']; ?>
                                            <?php } else { ?>
                                                <span class="price-old"><b><?php echo $product['price']; ?></b></span> <span class="price-new"><b><?php echo $product['special']; ?></b></span>
                                            <?php } ?>
                                            <?php if ($product['tax']) { ?>
                                                <br>
                                                <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($product['rating']) { ?>

                                    <?php } ?>

                                    <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');"><?php echo $button_wishlist; ?></a></div>
                                    <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');"><?php echo $button_compare; ?></a></div>
                                </div>
                            <?php } ?>
                        </div>
