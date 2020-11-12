<?php if ($mitems) { ?>
<script type="text/javascript"> 
$(document).ready(function(){ 
<?php if ($usehoverintent) { ?>
	var setari = {   
		<?php if ($dropdowneffect == 'drop') { ?>
		over: function() { $(this).find('.bigdiv').slideDown('fast'); }, 
		out: function() { $(this).find('.bigdiv').slideUp('fast'); },
		<?php } elseif($dropdowneffect == 'show') { ?>
		over: function() { $(this).find('.bigdiv').show(); }, 
		out: function() { $(this).find('.bigdiv').hide(); },
		<?php } else { ?>
		over: function() { $(this).find('.bigdiv').fadeIn('fast'); }, 
		out: function() { $(this).find('.bigdiv').fadeOut('fast'); },
		<?php } ?>
		timeout: 150
	};
	$("#supermenu ul li.tlli").hoverIntent(setari);
	var setariflyout = {   
		over: function() { $(this).find('.flyouttoright').show(); }, 
		out: function() { $(this).find('.flyouttoright').hide(); },
		timeout: 200
	};
	$("#supermenu ul li div.bigdiv.withflyout > .withchildfo").hoverIntent(setariflyout);
<?php } else { ?>
	$("#supermenu ul li.tlli").hover(function() {
		<?php if ($dropdowneffect == 'drop') { ?>
		$(this).find('.bigdiv').slideDown('fast');
	
		} , function() {
		$(this).find('.bigdiv').slideUp('fast');
		<?php } elseif($dropdowneffect == 'show') { ?>
		$(this).find('.bigdiv').show();
	
		} , function() {
		$(this).find('.bigdiv').hide();
		<?php } else { ?>
		$(this).find('.bigdiv').fadeIn('fast');
	
		} , function() {
		$(this).find('.bigdiv').fadeOut('fast');
		<?php } ?>
	});
	$("#supermenu ul li div.bigdiv.withflyout > .withchildfo").hover(function() {
		$(this).find('.flyouttoright').show();
	
		} , function() {
		$(this).find('.flyouttoright').hide();
	});
<?php } ?>
});

function showImageOnHover(image_id){
$("img#img_no"+image_id).show();

}

function hideImageOnHover(image_id){
$("img#img_no"+image_id).hide();
}
</script>
<?php if(isset($set_topfont)){
	?>
<style type="text/css">
	
	#supermenu ul li a.tll, #supermenu-mobile ul li div .withchild a.theparent{
		font-size: <?php echo $set_topfont; ?> !important;
	}
</style>
	<?php
} ?>
<div id="supermenu">
	<ul>
		<?php if ($tophomelink == 'dark') { ?>
			<li class="tlli hometlli"><a href="" class="tll tllhome">&nbsp;</a></li>
		<?php } elseif ($tophomelink == 'light') { ?>
			<li class="tlli hometlli"><a href="" class="tll tllhomel">&nbsp;</a></li>
		<?php } ?>
		<?php foreach ($mitems as $mitem) { ?>
			<li class="tlli<?php if ($mitem['children']) { ?> mkids<?php } ?><?php if ($mitem['tlstyle']) { ?> <?php echo $mitem['tlstyle']; ?><?php } ?>">
				<a class="tll" <?php if ($mitem['tlcolor']) { ?>style="<?php if(isset($set_topfont)) echo 'font-size:'.$set_topfont.';'; ?>color: <?php echo $mitem['tlcolor']; ?>;" <?php } ?><?php if (($mitem['children'] || ($mitem['chtml'] && $mitem['chtml'] == 1)) && $linkoftopitem != 'topitem') { ?><?php } else { ?> <?php if ($mitem['href']) { ?>href="<?php echo $mitem['href']; ?>"<?php } ?> <?php } ?>><?php echo $mitem['name']; ?></a>
				
				<?php if ($mitem['children'] && ($mitem['view'] == 'f0' || $mitem['view'] == 'f1')) { ?>
					<div class="bigdiv withflyout"<?php if ($mitem['dwidth']) { ?> style="width: <?php echo $mitem['dwidth']; ?>px;"<?php } ?>>
						<?php if ($dropdowntitle) { ?>
							<div class="headingoftopitem">
								<?php if ($mitem['href'] && $linkoftopitem == 'heading') { ?>
									<h2><a href="<?php echo $mitem['href']; ?>"><?php echo $mitem['name']; ?></a></h2>
								<?php } else { ?>
									<h2><?php echo $mitem['name']; ?></h2>
								<?php } ?>
							</div>
						<?php } ?>
						<?php foreach ($mitem['children'] as $mildren) { ?>
							<div class="withchildfo<?php if ($mildren['gchildren']) { ?> hasflyout<?php } ?>">
								<a class="theparent" href="<?php echo $mildren['href']; ?>"><?php echo $mildren['name']; ?></a>
								<?php if ($mildren['gchildren']) { ?>
									<div class="flyouttoright">
										<div class="inflyouttoright" <?php echo ($flyout_width ? 'style="width: '.$flyout_width.'px"' : ''); ?>>
											<?php if ($mitem['view'] == 'f0') { ?>
												<?php foreach ($mildren['gchildren'] as $gmildren) { ?>
													<div class="withchild" <?php if ($mitem['iwidth']) { ?> style="width: <?php echo $mitem['iwidth']; ?>px;"<?php } ?>>
														<a class="theparent" href="<?php echo $gmildren['href']; ?>"><?php echo $gmildren['name']; ?></a>
														<?php if (isset($gmildren['ggchildren'])) { ?>
															<?php if ($gmildren['ggchildren']) { ?>
																<span class="mainexpand"></span>
																<ul class="child-level">
																	<?php foreach ($gmildren['ggchildren'] as $ggmildren) { ?>
																		<li><a href="<?php echo $ggmildren['href']; ?>"><?php echo $ggmildren['name']; ?></a></li>
																	<?php } ?>
																</ul>
															<?php } ?>
														<?php } ?>
													</div>
												<?php } ?>
											<?php } else { ?>
												<?php foreach ($mildren['gchildren'] as $vall=> $gmildren) { ?>
													<div class="withimage" <?php if ($mitem['iwidth']) { ?> style="width: <?php echo $mitem['iwidth']; ?>px;"<?php } ?>>
														<div class="image">
															<a onmouseover="showImageOnHover('<?php echo $vall; ?>');" href="<?php echo $gmildren['href']; ?>">
																<img onmouseleave="hideImageOnHover('<?php echo $vall; ?>')" id="img_no<?php echo $vall; ?>" style="display: none;width: 220px;" src="<?php echo $gmildren['thumb']; ?>" alt="<?php echo $gmildren['name']; ?>" title="<?php echo $gmildren['name']; ?>" />
															</a>
														</div>
														<div class="name">
															<a onmouseleave="hideImageOnHover('<?php echo $vall; ?>')"  onmouseover="showImageOnHover('<?php echo $vall; ?>');" href="<?php echo $gmildren['href']; ?>"><?php echo $gmildren['name']; ?></a>
															<?php if (isset($gmildren['ggchildren'])) { ?>
																<?php if ($gmildren['ggchildren']) { ?>
																	<span class="mainexpand"></span>
																	<ul class="child-level">
																		<?php foreach ($gmildren['ggchildren'] as $ggmildren) { ?>
																			<li><a href="<?php echo $ggmildren['href']; ?>">+ <?php echo $ggmildren['name']; ?></a></li>
																		<?php } ?>
																	</ul>
																<?php } ?>
															<?php } ?>
														</div>
													</div>
												<?php } ?>
											<?php } ?>
										</div>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
						<?php if ($mitem['href'] && $linkoftopitem == 'bottom') { ?>
							<div class="linkoftopitem">
								<a href="<?php echo $mitem['href']; ?>"><?php echo $viewalltext; ?> <?php echo $mitem['name']; ?></a>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
				<?php if (($mitem['children'] || ($mitem['chtml'] && $mitem['chtml'] == 1)) && ($mitem['view'] != 'f0' && $mitem['view'] != 'f1') || $mitem['cssid'] == 'login_drop')  { ?>
					<div class="bigdiv"<?php if ($mitem['cssid'] == 'login_drop' && !$mitem['dwidth']) { ?> style="width: 200px;"<?php } ?><?php if ($mitem['dwidth']) { ?> style="width: <?php echo $mitem['dwidth']; ?>px;"<?php } ?>>
						<?php if ($dropdowntitle) { ?>
							<div class="headingoftopitem">
								<?php if ($mitem['href'] && $linkoftopitem == 'heading') { ?>
									<h2><a href="<?php echo $mitem['href']; ?>"><?php echo $mitem['name']; ?></a></h2>
								<?php } else { ?>
									<h2><?php echo $mitem['name']; ?></h2>
								<?php } ?>
							</div>
						<?php } ?>
					
						<?php if ($mitem['add'] || ($mitem['chtml'] && $mitem['chtml'] == 2)) { ?>
							<div class="menu-add" <?php if ($bspace_width) { ?>style="width: <?php echo $bspace_width; ?>px;"<?php } ?>>
								<?php if ($mitem['chtml'] && $mitem['chtml'] == 2) { ?>
									<?php echo $mitem['cchtml']; ?>
								<?php } else { ?>
									<a <?php if ($mitem['addurl']) { ?>href="<?php echo $mitem['addurl']; ?>"<?php } ?>><img src="image/<?php echo $mitem['add']; ?>" alt="<?php echo $mitem['name']; ?>" /></a>
								<?php } ?>
							</div>
						<?php } ?>
						<?php if ($mitem['fbrands']) { ?>
							<div class="dropbrands">
								<span><?php echo $brands_text; ?></span>
								<ul>
									<?php foreach ($mitem['fbrands'] as $dropbrand) { ?>
										<li><a href="<?php echo $dropbrand['href']; ?>"><?php echo $dropbrand['name']; ?></a></li>
									<?php } ?>
								</ul>
							</div>
						<?php } ?>
	 
						<div class="supermenu-left" <?php if ($mitem['add'] || ($mitem['chtml'] && $mitem['chtml'] == 2)) { ?><?php if ($mitem['fbrands']) { ?><?php if ($bspace_width) { $slmarg = $bspace_width + 180; ?>style="margin-right: <?php echo $slmarg ?>px"<?php } else { ?>style="margin-right: 380px"<?php } ?><?php } else { ?><?php if ($bspace_width) {  $slmarg = $bspace_width + 10; ?>style="margin-right: <?php echo $slmarg ?>px"<?php } else { ?>style="margin-right: 210px"<?php } ?><?php } ?><?php } elseif ($mitem['fbrands'] && !($mitem['add'] || ($mitem['chtml'] && $mitem['chtml'] == 2))) { ?>style="margin-right: 170px"<?php } ?>>
							<?php if ($mitem['chtml'] && $mitem['chtml'] == 1) { ?><?php echo $mitem['cchtml']; ?><?php } ?>
	  
							<?php if ($mitem['chtml'] && $mitem['chtml'] == 3) { ?><div style="display: block;"><?php echo $mitem['cchtml']; ?></div><?php } ?>
							
							<?php if ($mitem['cssid'] == 'login_drop') { ?>
								<?php if ($logged) { ?>
									<div class="withchild"<?php if ($mitem['iwidth']) { ?> style="width: <?php echo $mitem['iwidth']; ?>px;"<?php } ?>><a class="theparent" href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></div>
									<div class="withchild"<?php if ($mitem['iwidth']) { ?> style="width: <?php echo $mitem['iwidth']; ?>px;"<?php } ?>><a class="theparent"  href="<?php echo $password; ?>"><?php echo $text_password; ?></a></div>
									<div class="withchild"<?php if ($mitem['iwidth']) { ?> style="width: <?php echo $mitem['iwidth']; ?>px;"<?php } ?>><a class="theparent"  href="<?php echo $address; ?>"><?php echo $text_address; ?></a></div>
									<div class="withchild"<?php if ($mitem['iwidth']) { ?> style="width: <?php echo $mitem['iwidth']; ?>px;"<?php } ?>><a class="theparent"  href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></div>
									<div class="withchild"<?php if ($mitem['iwidth']) { ?> style="width: <?php echo $mitem['iwidth']; ?>px;"<?php } ?>><a class="theparent"  href="<?php echo $order; ?>"><?php echo $text_order; ?></a></div>
									<div class="withchild"<?php if ($mitem['iwidth']) { ?> style="width: <?php echo $mitem['iwidth']; ?>px;"<?php } ?>><a class="theparent"  href="<?php echo $download; ?>"><?php echo $text_download; ?></a></div>
									<div class="withchild"<?php if ($mitem['iwidth']) { ?> style="width: <?php echo $mitem['iwidth']; ?>px;"<?php } ?>><a class="theparent"  href="<?php echo $return; ?>"><?php echo $text_return; ?></a></div>
									<div class="withchild"<?php if ($mitem['iwidth']) { ?> style="width: <?php echo $mitem['iwidth']; ?>px;"<?php } ?>><a class="theparent"  href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></div>
									<div class="withchild"<?php if ($mitem['iwidth']) { ?> style="width: <?php echo $mitem['iwidth']; ?>px;"<?php } ?>><a class="theparent"  href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></div>
									<div class="withchild"<?php if ($mitem['iwidth']) { ?> style="width: <?php echo $mitem['iwidth']; ?>px;"<?php } ?>><a class="theparent"  href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></div>
									<div class="withchild"<?php if ($mitem['iwidth']) { ?> style="width: <?php echo $mitem['iwidth']; ?>px;"<?php } ?>><a class="theparent"  href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></div>
								<?php } else { ?>
										<form action="<?php echo $actiond; ?>" method="post" enctype="multipart/form-data" id="sm-login">
												<div class="addingaspace" style="clear: none;"></div>
												<?php echo $entry_email; ?><br />
												<input type="text" name="email" value="" />
												<br />
												<?php echo $entry_password; ?><br />
												<input type="password" name="password" value="" />
												<br />
												<a href="<?php echo $forgottend; ?>"><?php echo $text_forgotten; ?></a><br />
												<a href="<?php echo $registerd; ?>"><?php echo $text_register; ?></a><br />
												<div class="linkoftopitem">
													<a onclick="$('#sm-login').submit();"><?php echo $button_login; ?></a>
							                    </div>
										</form>
								<?php } ?>
							<?php } ?>
	  
							<?php if ((!$mitem['chtml'] || $mitem['chtml'] == 2 || $mitem['chtml'] == 3) && $mitem['cssid'] != 'login_drop') { ?>
								<?php if (!$mitem['view']) { ?>
									<?php foreach ($mitem['children'] as $mildren) { ?>
										<div class="withchild<?php if ($mildren['gchildren']) { ?> haskids<?php } ?>"<?php if ($mitem['iwidth']) { ?> style="width: <?php echo $mitem['iwidth']; ?>px;"<?php } ?>>
											<a class="theparent" href="<?php echo $mildren['href']; ?>"><?php echo $mildren['name']; ?></a>
											<?php if ($mildren['gchildren']) { ?>
												<span class="mainexpand"></span>
												<ul class="child-level">
													<?php foreach ($mildren['gchildren'] as $gmildren) { ?>
														<li><a href="<?php echo $gmildren['href']; ?>"><?php echo $gmildren['name']; ?></a></li>
													<?php } ?>
												</ul>
											<?php } ?>
										</div>
									<?php } ?>
								<?php } elseif ($mitem['view'] == '1') { ?>
									<?php foreach ($mitem['children'] as $mildren) { ?>
										<div class="withimage"<?php if ($mitem['iwidth']) { ?> style="width: <?php echo $mitem['iwidth']; ?>px;"<?php } ?>>
											<div class="image">
												<a href="<?php echo $mildren['href']; ?>"><img src="<?php echo $mildren['thumb']; ?>" alt="<?php echo $mildren['name']; ?>" title="<?php echo $mildren['name']; ?>" /></a>
											</div>
											<div class="name">
												<?php if ($mildren['gchildren']) { ?><span class="mainexpand"></span><?php } ?>
												<a class="nname" href="<?php echo $mildren['href']; ?>"><?php echo $mildren['name']; ?></a>
												<?php if ($mildren['gchildren']) { ?>
													<ul class="child-level">
														<?php foreach ($mildren['gchildren'] as $gmildren) { ?>
															<li><a href="<?php echo $gmildren['href']; ?>">+ <?php echo $gmildren['name']; ?></a></li>
														<?php } ?>
													</ul>
												<?php } ?>
											</div>
											<?php if (isset($mildren['price'])) { ?>
												<?php if ($mildren['price']) { ?>
													<div class="dropprice">
														<?php if (!$mildren['special']) { ?>
															<?php echo $mildren['price']; ?>
														<?php } else { ?>
															<span><?php echo $mildren['price']; ?></span> <?php echo $mildren['special']; ?>
														<?php } ?>
													</div>
												<?php } ?>
											<?php } ?>
										</div>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						</div>
						<?php if ($mitem['href'] && $linkoftopitem == 'bottom' && $mitem['cssid'] != 'login_drop') { ?>
							<div class="linkoftopitem">
								<a href="<?php echo $mitem['href']; ?>"><?php echo $viewalltext; ?> <?php echo $mitem['name']; ?></a>
							</div>
						<?php } elseif ($mitem['cssid'] != 'login_drop') { ?>
							<div class="addingaspace"></div>
						<?php } ?>
					</div>
				<?php } ?>
			</li>
		<?php } ?>
	</ul>
</div>

<?php if ( !empty($supermenuisresponsive) ) { ?>
	<div id="supermenu-mobile">
		<ul>
			<li class="tlli"><a class="tll"></a>
				<div class="bigdiv">
					<?php foreach ($mitems as $mitem) { ?>
						<div class="withchild">
							<?php if ($mitem['children']) { ?><span class="toexpand"></span><?php } ?>
							<a class="theparent" id="<?php echo $mitem['cssid']; ?>" <?php if ($mitem['tlcolor']) { ?>style="color: <?php echo $mitem['tlcolor']; ?>;" <?php } ?><?php if ($mitem['href']) { ?>href="<?php echo $mitem['href']; ?>"<?php } ?>><?php echo $mitem['name']; ?></a>
							<?php if ($mitem['children']) { ?>
								<ul>
									<?php foreach ($mitem['children'] as $mildren) { ?>
										<li>
											<a  href="<?php echo $mildren['href']; ?>"><?php echo $mildren['name']; ?></a>
											<?php if ($mildren['gchildren']) { ?>
												<span class="toexpandkids"></span>
												<ul class="child-level">
													<?php foreach ($mildren['gchildren'] as $gmildren) { ?>
														<li><a href="<?php echo $gmildren['href']; ?>"><?php echo $gmildren['name']; ?></a></li>
													<?php } ?>
												</ul>
											<?php } ?>
										</li>
									<?php } ?>
								</ul>
							<?php } ?>
						</div> 
					<?php } ?>
				</div>
			</li>
		</ul> 
	</div>	
<?php } ?>
<?php } ?>
<style type="text/css">	
@media screen and (max-width: 768px) {
#supermenu ul li a.tll, #supermenu-mobile ul li div .withchild a.theparent{
	font-size: 16px !important;
}
}
</style>