<div class="description">
    <p> <span class="product_model"><?php echo $text_model;?></span><label class="model_quick"> <?php echo $model;?></label></p>
    <?php if ($reward) {?>
        <span><?php echo $text_reward;?></span> <?php echo $reward;?><br />
    <?php }?>
    <p class="flt quick_flt"> 
    	<span class="product_stock_availability"><?php echo $text_stock;?></span>
    	<label class="stock_quick">
    		
    		<?php if ($quantity <= 0) { ?>
                                            <span class='outofstock'></span>
                                            	<?php
                                            		//echo '<pre>';print_r($estimate_deliver_days);exit;
                                            		//echo $estimate_deliver_time;
                                            		$datetoday = date("Y-m-d");
                                            		//echo $datetoday;
                                            		if($datetoday > $date_ordered){
														//echo "<span class='stocktext'>Will take at least (".$estimate_deliver_time.") days to come back in stock" . "</span>";
														$count = count($estimate_deliver_days);
														$val = 0;
														foreach($estimate_deliver_days as $get_days){
															$val++;
															$availabledate = date("Y-m-d",strtotime($date_ordered ."+".$get_days['estimate_days']." days"));
															if($datetoday > $availabledate){
																if($count == $val){
																	echo "<span class='stocktext'> We expected this item back in stock a few weeks ago. There may be a manufacturer delay, please contact us for details </span>";
																}
																continue;
															}else{
																echo "<span class='stocktext'>".str_replace('%s',date( "M d", strtotime($availabledate) ),$get_days['text']) ."</span>";
																break;
															}
														}
													}else{

														if($date_ordered != "0000-00-00"){
															foreach($estimate_deliver_days as $get_days){
																$availabledate = date("Y-m-d",strtotime($date_ordered . " +".$get_days['estimate_days']." days"));
																$availabledate = date( "M d", strtotime($availabledate) );
																echo "<span class='stocktext'>".str_replace('%s',$availabledate,$get_days['text']) ."</span>";
																break;
															}		

														}
													}


                                            		/*$datetoday = date("Y-m-d");
                                            		if($datetoday > $date_ordered)
                                            		{
														echo "<span class='stocktext'>Will take at least (".$estimate_deliver_time.") days to come back in stock" . "</span>";
													}
													else
													{
														if($date_ordered != "0000-00-00")
														{
															$availabledate = date("Y-m-d",strtotime($date_ordered . " +".$estimate_deliver_time." days"));
															echo "<span class='stocktext'>Will be available on or around " . date( "M d", strtotime($availabledate) ) . "</span>";
														}
													}*/
                                            	?>
<!--                                            <img id="help_out_stock_tooltip" src="admin/view/image/information.png" alt="" title ="This item is currently out of stock, but you can add this item to your wishlist and we will notify you when they become available." class="help_tool_out_img"/>-->
                                        <?php } else { ?>
                                            <span class='inofstock'></span>
<!--                                            <img id="help_out_stock_tooltip" src="admin/view/image/information.png" alt="" title ="Product availability is updated every few days and may not be current. If we have sold out since the last update, we will contact you before shipping your order." class="help_tool_out_img"/>-->
                                        <?php } ?>
    		
    		<?php //echo $stock;?>
    		
    	</label>
<!--    <div class="help-box-tooltip">
        <?php if ($quantity <= 0) {?>
            <img id="helpsimpletooltip" src="admin/view/image/information.png" alt="" class="pay-img-tool" title ="This item is currently out of stock, but you can add this item to your wishlist and we will notify you when they become available." class="help_tool_out_img"/>
        <?php } else {?>
            <img id="helpsimpletooltip" src="admin/view/image/information.png" alt="" class="pay-img-tool" title ="Product availability is updated every few days and may not be current. If we have sold out since the last update, we will contact you before shipping your order." class="help_tool_out_img"/>
        <?php }?>
    </div>-->
</p>
<script>
    $('#helpsimpletooltip').tooltipsy({
        css: {
            'padding': '10px',
            'max-width': '200px',
            'color': '#33333',
            'background-color': '#DDDDD',
            '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
            '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
            'text-shadow': 'none'
        }
    });

    $('#cboxWrapper').not('#helpsimpletooltip').click(function(event) {
        $('#helpsimpletooltip').tooltipsy({
            hide: function(e, $el) {
                $el.hide();
            }
        });
    });

</script>
</div>
