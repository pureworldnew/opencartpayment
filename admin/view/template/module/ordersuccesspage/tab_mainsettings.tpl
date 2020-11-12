<div id="maintext" class="tab-pane" style="width:99%;overflow:hidden;">
  <ul class="nav nav-tabs moduledata_tabs">
      <h5><strong>Multi-lingual settings:</strong></h5>
    <?php $i=0; foreach ($languages as $language) { ?>
      <li <?php if ($i==0) echo 'class="active"'; ?>><a href="#maintab-<?php echo $i; ?>-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>"/> <?php echo $language['name']; ?></a></li>
    <?php $i++; }?>
  </ul>
    <div class="tab-content">
    <?php $i=0; foreach ($languages as $language) { ?>
            <div id="maintab-<?php echo $i; ?>-<?php echo $language['language_id']; ?>" language-id="<?php echo $language['language_id']; ?>" class="row-fluid tab-pane language <?php if ($i==0) echo 'active'; ?>">
                <div class="row">
                  <div class="col-md-3">
          <h5><strong>Page Title:</strong></h5>
          <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Enter the page title here.</span>
                  </div>
                  <div class="col-md-4">
          <input placeholder="Page title" type="text" class="form-control" name="<?php echo $moduleName; ?>[PageTitle][<?php echo $language['language_id']; ?>]" value="<?php if(!empty($moduleData['PageTitle'][$language['language_id']])) echo $moduleData['PageTitle'][$language['language_id']]; else echo "Thank you for your order!"; ?>" />
                  </div>
                </div>
                <hr />
                <div class="row">
                  <div class="col-md-3">
          <h5><strong>Page Text:</strong></h5>
          <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Enter the page text here. You can use the following short codes:<br /><br />{first_name} - First name<br />{last_name} - Last name<br />{order_id} - Order ID<br /><br /><i class="fa fa-info-circle"></i>&nbsp;Make sure to use the short codes below if you offer discounts to the customers:<br /><br />{discount_code} - Discount code<br />{discount_value} - Discount value<br />{total_amount} - Total amount<br />{date_end} - End date of coupon validity</span>
                  </div>
                  <div class="col-md-8">
          <textarea id="PageText_<?php echo $language['language_id']; ?>" class="form-control" name="<?php echo $moduleName; ?>[PageText][<?php echo $language['language_id']; ?>]">
            <?php if(isset($moduleData['PageText'][$language['language_id']])) echo $moduleData['PageText'][$language['language_id']]; else 
            echo 'Hello, {first_name} {last_name}! Thank you for purchasing from our store. Your order ID is: <span style="font-weight: bold;">{order_id}</span>.'; ?>
                   </textarea>
                  </div>
                </div>
                <br/>
                <div class="row">
                   <div class="col-md-3">
                      <h5><strong>Select date format for the end date of coupon validity:</strong></h5>
                   </div>
                   <div class="col-md-4">
                      <select name="<?php echo $moduleName; ?>[DateFormat]" class="form-control">
                         <option value="d-m-Y" <?php echo (isset($moduleData['DateFormat']) && $moduleData['DateFormat'] == 'd-m-Y') ? 'selected=selected' : '' ?>>dd-mm-yyyy</option>
                         <option value="m-d-Y" <?php echo (isset($moduleData['DateFormat']) && $moduleData['DateFormat'] == 'm-d-Y') ? 'selected=selected' : '' ?>>mm-dd-yyyy</option>
                         <option value="Y-m-d" <?php echo (isset($moduleData['DateFormat']) && $moduleData['DateFormat'] == 'Y-m-d') ? 'selected=selected' : '' ?>>yyyy-mm-dd</option>
                         <option value="Y-d-m" <?php echo (isset($moduleData['DateFormat']) && $moduleData['DateFormat'] == 'Y-d-m') ? 'selected=selected' : '' ?>>yyyy-dd-mm</option>
                      </select>
                   </div>
                </div>
                <hr />
      </div>
        <?php $i++; } ?>
  </div>
    <br />
    <div class="row">
      <div class="col-md-3">
        <h5><strong>Offer discount to the customers:</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Choose if you want to offer a discount to the customers.</span>
      </div>
      <div class="col-md-4">
        <select id="DiscountChecker" name="<?php echo $moduleName; ?>[DiscountType]" class="form-control">
      <option value="N" <?php if(!empty($moduleData['DiscountType']) && $moduleData['DiscountType'] == "N") echo "selected"; ?>>No discount</option>
      <option value="P" <?php if(!empty($moduleData['DiscountType']) && $moduleData['DiscountType'] == "P") echo "selected"; ?>>Percentage</option>
      <option value="F" <?php if(!empty($moduleData['DiscountType']) && $moduleData['DiscountType'] == "F") echo "selected"; ?>>Fixed amount</option>
        </select>
      </div>
    </div>
    <div class="discountSettings">
      <hr />
        <div class="row">
          <div class="col-md-3">
            <h5><strong>Discount:</strong></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Enter the discount percent or value.</span>
          </div>
          <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control" name="<?php echo $moduleName; ?>[Discount]" value="<?php if(!empty($moduleData['Discount'])) echo $moduleData['Discount']; else echo '10'; ?>">
                <span class="input-group-addon">
                   <span style="display:none;" id="currencyAddon"><?php echo $currency; ?></span><span style="display:none;" id="percentageAddon">%</span>
               </span>
            </div>
          </div>
        </div>
        <hr />
        <div class="row">
          <div class="col-md-3">
            <h5><strong>Total amount:</strong></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;The total amount that must reached before the coupon is valid.</span>
          </div>
          <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control" name="<?php echo $moduleName; ?>[TotalAmount]" value="<?php if(!empty($moduleData['TotalAmount'])) echo $moduleData['TotalAmount']; else echo '20'; ?>">
        <span class="input-group-addon"><?php echo $currency; ?></span>
            </div>
          </div>
        </div>
        <hr />
        <div class="row">
          <div class="col-md-3">
            <h5><strong>Discount validity:</strong></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Define how many days the discount code will be active after sending the reminder.</span>
          </div>
          <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control" value="<?php if(!empty($moduleData['DiscountValidity'])) echo (int)$moduleData['DiscountValidity']; else echo 7; ?>" name="<?php echo $moduleName; ?>[DiscountValidity]">
        <span class="input-group-addon">days</span>
            </div>
          </div>
        </div>
    </div>    
  <hr />
    <div class="row">
      <div class="col-md-3">
        <h5><strong>Show AddThis sharing buttons:</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Shows the AddThis sharing buttons on the success page.</span>
      </div>
      <div class="col-md-4">
        <select id="OrderSharingChecker" name="<?php echo $moduleName; ?>[AddThisShow]" class="form-control">
              <option value="yes" <?php echo (!empty($moduleData['AddThisShow']) && $moduleData['AddThisShow'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
              <option value="no"  <?php echo (empty($moduleData['AddThisShow']) || $moduleData['AddThisShow']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
        </select>
      </div>
    </div>
    <div class="row addthisurl">
      <hr />
        <div class="col-md-3">
          <h5><strong>URL to use for the AddThis sharing buttons:</strong></h5>
          <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Specify the url which you would like to share.</span>
        </div>
      <div class="col-md-4">
           <input type="text" class="form-control" name="<?php echo $moduleName; ?>[AddThisURL]" value="<?php if(!empty($moduleData['AddThisURL'])) echo $moduleData['AddThisURL']; else echo HTTP_CATALOG; ?>" />
    </div>
    </div>
    <hr />
  <div class="row">
      <div class="col-md-3">
        <h5><strong>Custom CSS:</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;Enter your custom CSS here...<br /><br />
      </div>
      <div class="col-md-4">
       <textarea rows="5" name="<?php echo $moduleName; ?>[CustomCSS]" placeholder="Custom CSS" class="form-control"><?php echo (isset($moduleData['CustomCSS'])) ? $moduleData['CustomCSS'] : '' ?></textarea>
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-md-3">
        <h5><strong>Custom JavaScipt:</strong></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;You can use the custom JavaScript code for tracking system and etc...<br /><br />THe following short-codes are available:<br /><br />{order_id} - Order ID<br />{order_total} - Order total
      </div>
      <div class="col-md-4">
       <textarea rows="5" name="<?php echo $moduleName; ?>[CustomJS]" placeholder="Custom JavaScript code" class="form-control"><?php echo (isset($moduleData['CustomJS'])) ? $moduleData['CustomJS'] : '' ?></textarea>
      </div>
    </div>
    <hr />
</div>