<?php echo $header; ?>
<style>
	.osp-orderdetails {
		margin: 30px 0 10px 0;
	}
	.table {
		width: 100%;
		max-width: 100%;
		margin-bottom: 20px;
		border-spacing: 0;
		border-collapse: collapse;
		background-color: transparent;
	}
	.table-bordered {
		border: 1px solid #ddd;
	}
	.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
		padding: 8px;
		line-height: 1.42857143;
		vertical-align: top;
		border-top: 1px solid #ddd;
	}
	.table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
		border-bottom-width: 2px;
	}
	.table>caption+thead>tr:first-child>td, .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
		border-top: 0;
	}
	.table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
		border: 1px solid #ddd;
	}
	.table-responsive {
		min-height: .01%;
		overflow-x: auto;
	}
	@media screen and (max-width: 767px) {
		.table-responsive {
			width: 100%;
			margin-bottom: 15px;
			overflow-y: hidden;
			-ms-overflow-style: -ms-autohiding-scrollbar;
			border: 1px solid #ddd;
		}
		.table-responsive>.table {
			margin-bottom: 0;
		}
		.table-responsive>.table-bordered {
			border: 0;
		}
		.table-responsive>.table>tbody>tr>td, .table-responsive>.table>tbody>tr>th, .table-responsive>.table>tfoot>tr>td, .table-responsive>.table>tfoot>tr>th, .table-responsive>.table>thead>tr>td, .table-responsive>.table>thead>tr>th {
			white-space: nowrap;
		}
		.table-responsive>.table-bordered>tbody>tr>td:first-child, .table-responsive>.table-bordered>tbody>tr>th:first-child, .table-responsive>.table-bordered>tfoot>tr>td:first-child, .table-responsive>.table-bordered>tfoot>tr>th:first-child, .table-responsive>.table-bordered>thead>tr>td:first-child, .table-responsive>.table-bordered>thead>tr>th:first-child {
			border-left: 0;
		}
		.table-responsive>.table-bordered>tbody>tr:last-child>td, .table-responsive>.table-bordered>tbody>tr:last-child>th, .table-responsive>.table-bordered>tfoot>tr:last-child>td, .table-responsive>.table-bordered>tfoot>tr:last-child>th {
			border-bottom: 0;
		}
	}
</style>
<?php if(!empty($OSP['CustomCSS'])): ?>
	<style>
        <?php echo htmlspecialchars_decode($OSP['CustomCSS']); ?>
    </style>
<?php endif; ?>
<?php if(!empty($OSP['CustomJS'])): ?>
	<script type="text/javascript">
        <?php echo htmlspecialchars_decode($OSP['CustomJS']); ?>
    </script>
<?php endif; ?>
<div id="container" class="container j-container success-page">
    <ul class="breadcrumb">
    	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
    		<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	    <?php } ?>
    </ul>

	<div class="row"><?php echo $column_left; ?><?php echo $column_right; ?>
        <?php if ($column_left && $column_right) { ?>
            <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
            <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
            <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>">
            <?php echo $content_top; ?>
            <h1 class="heading-title h1-osp"><?php echo $heading_title; ?></h1>
            <div class='osp-content'>
            	<?php if (!empty($OSP['PageText'][$current_language])) { ?>
                	<span class='osp-pagetext'>
                    	<?php echo $OSP['PageText'][$current_language]; ?>
                    </span>
                <?php } ?>
                
                <?php if (isset($OSP['AddThisShow']) && $OSP['AddThisShow'] == 'yes' && !empty($OSP['AddThisURL'])) { ?>
                    <div class="osp-sharing" style="text-align:center;">
                        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-55534c28671b3948" async="async"></script>
                        <div class="addthis_sharing_toolbox" data-url="<?php echo $OSP['AddThisURL']; ?>"></div>
                    </div>
                <?php } ?>
                
                <?php if ($OSP['OrderDetailsShow'] == 'yes' || $OSP['OrderPaymentShippingShow'] == 'yes' || $OSP['OrderProductsShow'] == 'yes') { ?>
                	<h2 class="osp-h2 osp-orderdetails"><?php echo $text_order_detail; ?></h2>
                <?php } ?>
                
                <?php if (isset($OSP['OrderDetailsShow']) && $OSP['OrderDetailsShow'] == 'yes') { ?>
                    <table class="table table-bordered table-hover table-orderdetails">
                        <thead>
                            <tr>
                                <td class="text-left" colspan="2"><?php echo $text_order_detail; ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-left" style="width: 50%;">
                                    <?php if ($OSP_orderinfo['invoice_no']) { ?>
                                        <b><?php echo $text_invoice_no; ?></b> <?php echo $OSP_orderinfo['invoice_no']; ?><br />
                                    <?php } ?>
                                    <b><?php echo str_replace('%s', $osp_order_id, $text_order_id); ?></b><br />
                                    <b><?php echo $text_date_added; ?></b> <?php echo $OSP_orderinfo['date_added']; ?>
                                </td>
                                <td class="text-left">
                                    <?php if ($OSP_orderinfo['payment_method']) { ?>
                                        <b><?php echo $text_payment_method; ?></b> <?php echo $OSP_orderinfo['payment_method']; ?><br />
                                    <?php } ?>
                                        <?php if ($OSP_orderinfo['shipping_method']) { ?>
                                    <b><?php echo $text_shipping_method; ?></b> <?php echo $OSP_orderinfo['shipping_method']; ?>
                                    <?php } ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php } ?>
				
                <?php if (isset($OSP['OrderPaymentShippingShow']) && $OSP['OrderPaymentShippingShow'] == 'yes') { ?>
                    <table class="table table-bordered table-hover table-orderpaymentshipping">
                        <thead>
                            <tr>
                                <td class="text-left" style="width: 50%;"><?php echo $text_payment_address; ?></td>
                                <?php if ($OSP_orderinfo['shipping_address']) { ?>
                                    <td class="text-left"><?php echo $text_shipping_address; ?></td>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-left"><?php echo $OSP_orderinfo['payment_address']; ?></td>
                                <?php if ($OSP_orderinfo['shipping_address']) { ?>
                                    <td class="text-left"><?php echo $OSP_orderinfo['shipping_address']; ?></td>
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>                
                 <?php } ?>
                 
                <?php if (isset($OSP['OrderProductsShow']) && $OSP['OrderProductsShow'] == 'yes') { ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-orderproducts">
                            <thead>
                                <tr>
                                	<td class="text-left"></td>
                                    <td class="text-left"><?php echo $column_name; ?></td>
                                    <td class="text-left"><?php echo $column_model; ?></td>
                                    <td class="text-right"><?php echo $column_quantity; ?></td>
                                    <td class="text-right"><?php echo $column_price; ?></td>
                                    <td class="text-right"><?php echo $column_total; ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($OSP_orderinfo['products'] as $product) { ?>
                                    <tr>
                                    	<td class="text-center"><a href="<?php echo $product['href']; ?>"><img class="osp-productimage" src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" /></a></td>
                                        <td class="text-left"><?php echo $product['name']; ?>
                                        <?php foreach ($product['option'] as $option) { ?>
                                          <br />
                                          <?php  $option_name = (explode(" ",$option['name']));
                                            $option_unit_name = trim(end($option_name)); ?>
                                            <?php if($option_unit_name == 'units') {
                                                $option['name'] = $option_unit_name;
                                            } ?>
                                          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                                          <?php } ?><br />
                                
                                          <?php
                                              //print_r($product['unit']);
                                              if(!empty($product['unit']) && $product['unit']['convert_price'] !=1) { ?>
                                           <!--  - <small><?php echo $product['unit']['unit_name']; ?>: <?php echo $product['unit']['unit_value_name']; ?></small><br /> -->
                                           - <small style="color:#DD0205; font-weight:bold;"><?php echo number_format(($product['quantity']),2); ?> <?php echo $product['unit_dates_default']['name']; ?> = <?php echo number_format(($product['quantity']/$product['unit']['convert_price']),2); ?> <?php echo $product['unit']['unit_value_name']; ?></small><br />
                                           <?php } ?></td>
                                        <td class="text-left"><?php echo $product['model']; ?></td>
                                        <td class="text-right"><?php 
                    			//print_r($product['unit']);
                            if(!empty($product['unit']) && $product['unit']['convert_price'] !=1) {
                          		
		                             echo $product['quantity'] .' '.$product['DefaultUnitName']['unit_plural'];
      	               			
                    		}
                            else{ 
                    			echo $product['quantity'];
                     		} 
                  ?><?php //echo $product['quantity']; ?></td>
                                        <td class="text-right"><?php echo $product['price']; ?>
                  <?php if(!empty($product['unit']) && $product['unit']['convert_price'] !=1) { ?>
                             <br> <b style="font-weight:bold;"> per <?php echo $product['DefaultUnitName']['unit_singular']; ?> </b>
                             <?php }?></td>
                                        <td class="text-right"><?php echo $product['total']; ?></td>
                                    </tr>
                                    <?php } ?>
                                    <?php foreach ($OSP_orderinfo['vouchers'] as $voucher) { ?>
                                        <tr>
                                        	<td class="text-left"></td>
                                            <td class="text-left"><?php echo $voucher['description']; ?></td>
                                            <td class="text-left"></td>
                                            <td class="text-right">1</td>
                                            <td class="text-right"><?php echo $voucher['amount']; ?></td>
                                            <td class="text-right"><?php echo $voucher['amount']; ?></td>
                                        </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <?php foreach ($OSP_orderinfo['totals'] as $total) { ?>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td class="text-right"><b><?php echo $total['title']; ?></b></td>
                                        <td class="text-right"><?php echo $total['text']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tfoot>
                        </table>
                    </div>
				<?php } ?>
                
                <?php if (isset($OSP['OrderCommentsShow']) && $OSP['OrderCommentsShow'] == 'yes') { ?>
                    <?php if ($OSP_orderinfo['comment']) { ?>
                        <table class="table table-bordered table-hover table-ordercomments">
                            <thead>
                                <tr>
                                    <td class="text-left"><?php echo $text_comment; ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-left"><?php echo $OSP_orderinfo['comment']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php } ?>                
				<?php } ?>
				
                <?php if (isset($OSP['ShowProducts']) && $OSP['ShowProducts'] == 'yes' && isset($promoted_products) && sizeof($promoted_products)>0) { ?>
                	<div class="box oc-module">
                        <h2 class="box-heading osp-h2 osp-promotedproducts"><?php echo $OSP['PromotedTitle'][$current_language]; ?></h2>
                        <div class="box-content row osp-promotedwidget">
                        	<div class="box-product">
                                <?php foreach ($promoted_products as $product) { ?>
                                <div class="product-grid-item <?php echo $this->journal2->settings->get('product_grid_classes'); ?> display-<?php echo $this->journal2->settings->get('product_grid_wishlist_icon_display'); ?> <?php echo $this->journal2->settings->get('product_grid_button_block_button'); ?>">
                                    <div class="product-thumb product-wrapper <?php echo isset($product['labels']) && is_array($product['labels']) && isset($product['labels']['outofstock']) ? 'outofstock' : ''; ?>">
                                        <div class="image">
                                        	<a href="<?php echo $product['href']; ?>" <?php if(isset($product['thumb2']) && $product['thumb2']): ?> class="has-second-image" style="background: url('<?php echo $product['thumb2']; ?>') no-repeat;" <?php endif; ?>>
                                                <img class="lazy first-image" src="<?php echo $this->journal2->settings->get('product_dummy_image'); ?>" data-src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" />
                                        	</a>
                                            <?php if (isset($product['labels']) && is_array($product['labels'])): ?>
                                              <?php foreach ($product['labels'] as $label => $name): ?>
                                              <?php if ($label === 'outofstock'): ?>
                                              <img class="outofstock" <?php echo Journal2Utils::getRibbonSize($this->journal2->settings->get('out_of_stock_ribbon_size')); ?> style="position: absolute; top: 0; left: 0" src="<?php echo Journal2Utils::generateRibbon($name, $this->journal2->settings->get('out_of_stock_ribbon_size'), $this->journal2->settings->get('out_of_stock_font_color'), $this->journal2->settings->get('out_of_stock_bg')); ?>" alt="" />
                                              <?php else: ?>
                                              <span class="label-<?php echo $label; ?>"><b><?php echo $name; ?></b></span>
                                              <?php endif; ?>
                                              <?php endforeach; ?>
                                              <?php endif; ?>
                                              <?php if($this->journal2->settings->get('product_grid_wishlist_icon_position') === 'image' && $this->journal2->settings->get('product_grid_wishlist_icon_display', '') === 'icon'): ?>
                                                  <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');" class="hint--top" data-hint="<?php echo $button_wishlist; ?>"><i class="wishlist-icon"></i><span class="button-wishlist-text"><?php echo $button_wishlist;?></span></a></div>
                                                  <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');" class="hint--top" data-hint="<?php echo $button_compare; ?>"><i class="compare-icon"></i><span class="button-compare-text"><?php echo $button_compare;?></span></a></div>
                                              <?php endif; ?>
                                        </div>
                                        <div class="product-details">
                                          <div class="caption">
                                            <h4 class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                                            <p class="description"><?php echo $product['description']; ?></p>
                                            <?php if ($product['rating']) { ?>
                                            <div class="rating">
                                              <?php for ($i = 1; $i <= 5; $i++) { ?>
                                              <?php if ($product['rating'] < $i) { ?>
                                              <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                                              <?php } else { ?>
                                              <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                                              <?php } ?>
                                              <?php } ?>
                                            </div>
                                            <?php } ?>
                                            <?php if ($product['price']) { ?>
                                            <p class="price">
                                              <?php if (!$product['special']) { ?>
                                              <?php echo $product['price']; ?>
                                              <?php } else { ?>
                                              <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new" <?php echo isset($product['date_end']) && $product['date_end'] ? "data-end-date='{$product['date_end']}'" : ""; ?>><?php echo $product['special']; ?></span>
                                              <?php } ?>
                                              <?php if ($product['tax']) { ?>
                                              <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                                              <?php } ?>
                                            </p>
                                            <?php } ?>
                                          </div>
                                          <div class="button-group">
                                            <?php if (Journal2Utils::isEnquiryProduct($this, $product['product_id'])): ?>
                                            <div class="cart enquiry-button">
                                              <a href="javascript:Journal.openPopup('<?php echo $this->journal2->settings->get('enquiry_popup_code'); ?>', '<?php echo $product['product_id']; ?>');" data-clk="addToCart('<?php echo $product['product_id']; ?>');" class="button hint--top" data-hint="<?php echo $this->journal2->settings->get('enquiry_button_text'); ?>"><?php echo $this->journal2->settings->get('enquiry_button_icon') . '<span class="button-cart-text">' . $this->journal2->settings->get('enquiry_button_text') . '</span>'; ?></a>
                                            </div>
                                            <?php else: ?>
                                            <div class="cart <?php echo isset($product['labels']) && is_array($product['labels']) && isset($product['labels']['outofstock']) ? 'outofstock' : ''; ?>">
                                              <a onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button hint--top" data-hint="<?php echo $button_cart; ?>"><i class="button-left-icon"></i><span class="button-cart-text"><?php echo $button_cart; ?></span><i class="button-right-icon"></i></a>
                                            </div>
                                            <?php endif; ?>
                                            <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');" class="hint--top" data-hint="<?php echo $button_wishlist; ?>"><i class="wishlist-icon"></i><span class="button-wishlist-text"><?php echo $button_wishlist;?></span></a></div>
                                            <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');" class="hint--top" data-hint="<?php echo $button_compare; ?>"><i class="compare-icon"></i><span class="button-compare-text"><?php echo $button_compare;?></span></a></div>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                        	</div>
                        </div> 
                    </div>                 
                <?php } ?>
            </div>
            <div class="buttons">
                <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary button"><?php echo $button_continue; ?></a></div>
            </div>
            <?php echo $content_bottom; ?>
        </div>
	</div>
</div>
<?php echo $footer; ?>