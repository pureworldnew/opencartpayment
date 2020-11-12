<div id="orderinfo" class="tab-pane" style="width:99%;overflow:hidden;">
    <div class="row">
      <div class="col-md-3">
        <h5><strong>Show main order details:</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Show a table with the order ID, date added, payment method and shipping method.</span>
      </div>
      <div class="col-md-4">
        <select name="<?php echo $moduleName; ?>[OrderDetailsShow]" class="form-control">
              <option value="yes" <?php echo (!empty($moduleData['OrderDetailsShow']) && $moduleData['OrderDetailsShow'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
              <option value="no"  <?php echo (empty($moduleData['OrderDetailsShow']) || $moduleData['OrderDetailsShow']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
        </select>
      </div>
    </div>
    <hr />
	<div class="row">
      <div class="col-md-3">
        <h5><strong>Show payment & shipping address:</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Show payment & shipping address in the success page.</span>
      </div>
      <div class="col-md-4">
        <select name="<?php echo $moduleName; ?>[OrderPaymentShippingShow]" class="form-control">
              <option value="yes" <?php echo (!empty($moduleData['OrderPaymentShippingShow']) && $moduleData['OrderPaymentShippingShow'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
              <option value="no"  <?php echo (empty($moduleData['OrderPaymentShippingShow']) || $moduleData['OrderPaymentShippingShow']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
        </select>
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-md-3">
        <h5><strong>Show ordered products:</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Show the ordered products in a nice table.</span>
      </div>
      <div class="col-md-4">
        <select id="OrderProductsChecker" name="<?php echo $moduleName; ?>[OrderProductsShow]" class="form-control">
              <option value="yes" <?php echo (!empty($moduleData['OrderProductsShow']) && $moduleData['OrderProductsShow'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
              <option value="no"  <?php echo (empty($moduleData['OrderProductsShow']) || $moduleData['OrderProductsShow']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
        </select>
      </div>
    </div>
    <div class="row orderproductimages">
     	<hr />
        <div class="col-md-3">
        	<h5><strong>Product images dimensions:</strong></h5>
        	<span class="help"><i class="fa fa-info-circle"></i>&nbsp;Specify here the image width & height.</span>
      	</div>
    	<div class="col-md-4">
            <div class="input-group">
              <span class="input-group-addon">Width:&nbsp;</span>
                <input type="text" name="<?php echo $moduleName; ?>[OrderPictureWidth]" class="form-control" value="<?php echo (isset($moduleData['OrderPictureWidth'])) ? $moduleData['OrderPictureWidth'] : '50' ?>" />
              <span class="input-group-addon">px</span>
            </div>
            <br />
            <div class="input-group">
              <span class="input-group-addon">Height:</span>
                <input type="text" name="<?php echo $moduleName; ?>[OrderPictureHeight]" class="form-control" value="<?php echo (isset($moduleData['OrderPictureHeight'])) ? $moduleData['OrderPictureHeight'] : '50' ?>" />
              <span class="input-group-addon">px</span>
            </div>
		</div>
    </div>
      <hr />
    <div class="row">
      <div class="col-md-3">
        <h5><strong>Show order comments:</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Show the comments added for the order.</span>
      </div>
      <div class="col-md-4">
        <select name="<?php echo $moduleName; ?>[OrderCommentsShow]" class="form-control">
              <option value="yes" <?php echo (!empty($moduleData['OrderCommentsShow']) && $moduleData['OrderCommentsShow'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
              <option value="no"  <?php echo (empty($moduleData['OrderCommentsShow']) || $moduleData['OrderCommentsShow']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
        </select>
      </div>
    </div>
    <hr />
</div>