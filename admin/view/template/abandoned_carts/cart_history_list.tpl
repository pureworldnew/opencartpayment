<div class="peoples">
	<?php if($peoples) { ?>
	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th class="text-center">
								<span><?php echo $text_cart_details; ?></span>
							</th>
							<th class="text-left"><?php echo $text_registered; ?></th>
							<th class="text-left"><?php echo $text_products; ?></th>
							<th class="text-left"><?php echo $text_ip; ?></th>
							<?php if($abandoned_carts_deletecart_status) { ?>
							<th class="text-right"><?php echo $text_action; ?></th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php $i = 0; foreach($peoples as $people) { ?>
						<tr>
							<td class="text-left">
								<div class="panel panel-default">
									<table class="table">
										<tbody>
											<tr>
												<td class="text-center" style="width: 1%;"> 
													<button class="btn btn-info btn-xs" title="" data-toggle="tooltip" data-original-title="<?php echo $text_name ?>"><i class="fa fa-user fa-fw"></i></button>
													<input type="hidden" name="selected_data[<?php echo $i; ?>][store_id]" value="<?php echo $people['store_id']; ?>" />
												</td>
											<td><?php echo $people['name']; ?></td>
											</tr>
											<tr>
												<td style="width: 1%;"><button class="btn btn-info btn-xs" title="" data-toggle="tooltip" data-original-title="<?php echo $text_email ?>"><i class="fa fa-envelope fa-fw"></i></button></td>
												<td><a  href="mailto:<?php echo $people['email']; ?>"><?php echo $people['email']; ?></a></td>
											</tr>
											<tr>
												<td><button class="btn btn-info btn-xs" title="" data-toggle="tooltip" data-original-title="<?php echo $text_telephone ?>"><i class="fa fa-phone fa-fw"></i></button></td>
												<td><?php echo $people['telephone']; ?></td>
											</tr>
											<tr>
												<td><button class="btn btn-info btn-xs" title="" data-toggle="tooltip" data-original-title="<?php echo $text_store ?>"><i class="fa fa-truck fa-fw"></i></button></td>
												<td><?php echo $people['store_name']; ?></td>
											</tr>
										</tbody>
									</table>
								</div>
							</td>
							<td class="text-center"><span class="label label-<?php echo $people['account_class'] ?>"><?php echo $people['account'] ?></span></td>
							<td>
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<th class="text-center"><?php echo $text_image ?></th>
											<th class="text-left"><?php echo $text_product ?></th>
											<th class="text-right"><?php echo $text_quantity ?></th>
											<th class="text-right"><?php echo $text_reminder ?></th>
											<th class="text-right"><?php echo $text_date_added ?></th>
											<?php if($abandoned_carts_deleteproduct_status) { ?>
											<th class="text-right"><?php echo $text_action ?></th>
											<?php } ?>
										</tr>
									</thead>
									<tbody>
										<?php if(!empty($people['products'])) { ?>
										<?php foreach($people['products'] as $product) { ?>
										<tr>
											<td class="text-center"><?php if ($product['image']) { ?>
												<img title="" data-toggle="tooltip" data-original-title="<?php echo $product['name'] ?>" src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" />
												<?php } else { ?>
												<span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
												<?php } ?>
											</td>
											<td class="text-left">
												<span title="" data-toggle="tooltip" data-original-title="<?php echo $text_product; ?>"><?php echo $product['name'] ?></span>
												<?php if ($product['option']) { ?>
												<?php foreach ($product['option'] as $option) { ?>
												<br />
												<small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
												<?php } ?>
												<?php } ?>
											</td>
											<td class="text-right"><span title="" data-toggle="tooltip" data-original-title="<?php echo $text_quantity; ?>" class="label label-info">x <?php echo $product['quantity'] ?></span></td>
											<td class="text-right"><span title="" data-toggle="tooltip" data-original-title="<?php echo $text_reminder; ?>" class="label label-<?php echo $product['email_notify_class'] ?>"><?php echo $product['email_notify'] ?></span></td>
											<td class="text-right"><span title="" data-toggle="tooltip" data-original-title="<?php echo $text_date_added; ?>"><?php echo $product['date_added']; ?></span></td>
											<?php if($abandoned_carts_deleteproduct_status) { ?>
											<td class="text-right"><button type="button" value="<?php echo $product['cart_id']; ?>"id="button-product-delete<?php echo $product['cart_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></td>
											<?php } ?>
										</tr>
										<?php } ?>
										<?php }else{ ?>
										<tr>
											<td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
										</tr>	
										<?php } ?>
									</tbody>
								</table>
							</td>
							<td class="text-left"><?php echo $people['ip'] ?></td>
							<?php if($abandoned_carts_deletecart_status) { ?>
							<td class="text-right"><button type="button" rel="<?php echo $people['store_id']; ?>" value="<?php echo $people['email']; ?>" id="button-cart-delete<?php echo $i; ?>" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></td>
							<?php } ?>
						</tr>
						<?php $i++; } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
		<div class="col-sm-6 text-right"><?php echo $results; ?></div>
	</div>
	<?php }else{ ?>
	<div class="row">
		<div class="col-md-12 text-center"><h3><?php echo $text_no_cart; ?></h3></div>
	</div>
	<?php } ?>
</div>
<script type="text/javascript"><!--
$('button[id^=\'button-product-delete\']').on('click', function(e) {
	if (confirm('<?php echo $text_confirm; ?>')) {
		var node = this;

		$.ajax({
			url: 'index.php?route=abandoned_carts/abandonedcarts_history/deleteCartProdcutHistory&token=<?php echo $token; ?>&cart_id=' + $(node).val(),
			dataType: 'json',
			crossDomain: true,
			beforeSend: function() {
				$(node).button('loading');
			},
			complete: function() {
				$(node).button('reset');
			},
			success: function(json) {
				$('.alert').remove();

				if (json['error']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					/************/
					getReloadCarts();
					/************/
					
					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});
//--></script>			
<script type="text/javascript"><!--
$('button[id^=\'button-cart-delete\']').on('click', function(e) {
	if (confirm('<?php echo $text_confirm; ?>')) {
		var node = this;

		$.ajax({
			url: 'index.php?route=abandoned_carts/abandonedcarts_history/deleteCartHistory&token=<?php echo $token; ?>&email=' + $(node).val() + '&store_id=' + $(node).attr('rel'),
			dataType: 'json',
			crossDomain: true,
			beforeSend: function() {
				$(node).button('loading');
			},
			complete: function() {
				$(node).button('reset');
			},
			success: function(json) {
				$('.alert').remove();

				if (json['error']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					/************/
					getReloadCarts();
					/************/
					
					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});
//--></script>
<script type="text/javascript"><!--
function getReloadCarts() {
	$.ajax({
		url: '<?php echo $reload_action; ?>',
		dataType: 'html',
		beforeSend: function() {
			$('#recovery_carts').html('<div class="loading_gif text-center"><img src="view/image/loading_icon.gif" class="img-reponsive"></div>');
		},
		complete: function() {
		},
		success: function(html) {
			$('#recovery_carts').html(html);
		}
	});
}
//--></script>