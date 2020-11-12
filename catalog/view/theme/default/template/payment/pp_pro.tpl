<script type="text/javascript" src="catalog/view/javascript/paypalprocardvalid.js"></script>
<style>
.cvv {
	padding-left: 18px;
	text-align: left
}
.dateYearLI {
	width: 180px!important
}
#confirmorder {
	width: 100%
}
a, abbr, acronym, address, applet, article, aside, audio, b, big, blockquote, canvas, caption, center, cite, code, dd, del, details, dfn, dl, dt, em, embed, fieldset, figcaption, figure, footer, form, h1, h2, h3, h4, h5, h6, header, hgroup, i, iframe, img, ins, kbd, label, legend, li, mark, menu, nav, object, ol, output, p, pre, q, ruby, s, samp, section, small, strike, strong, sub, summary, sup, table, tbody, td, tfoot, th, thead, time, tr, tt, u, ul, var, video {
	border: 0;
	font: inherit;
	margin: 0;
	padding: 0;
	vertical-align: baseline
}
.payment .buttons {
	float: left;
	margin: 0 0 10px;
	text-align: left
}
.payment {
	float: left
}
.payment input.button {
	font-size: 16px;
	height: 32px;
	line-height: 0!important;
	padding: 6px 19px
}
.payment .onecheckout-heading {
	float: left;
	width: 350px
}
.payment_credit_card {
	float: left;
	width: 98%
}
.onecheckout-content.divclear .buttons {
	margin: 0!important
}
#payment_credit_card article, #payment_credit_card aside, #payment_credit_card details, #payment_credit_card figcaption, #payment_credit_card figure, #payment_credit_card footer, #payment_credit_card header, #payment_credit_card hgroup, #payment_credit_card menu, #payment_credit_card nav, #payment_credit_card section {
	display: block
}
#payment_credit_card ol, #payment_credit_card ul {
	list-style: none
}
#payment_credit_card q, #payment_credit_cardblockquote {
	quotes: none
}
#payment_credit_card blockquote:before, blockquote:after, q:after, q:before {
	content: none
}
#payment_credit_card table {
	border-collapse: collapse;
	border-spacing: 0
}
#payment_credit_card body {
	color: #333;
	font-family: "Helvetica Neue", Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size: 16px;
	line-height: 32px;
	padding: 16px
}
#payment_credit_card a {
	color: #17D;
	text-decoration: none
}
#payment_credit_card a:hover {
	border-bottom: 1px solid #17D
}
#payment_credit_card a.button {
	background: linear-gradient(#3290EF, #17D) #0F6AC5;
	border-bottom: 2px solid #0D5DAE;
	border-radius: 5px;
	color: #FFF;
	display: block;
	font-size: 24px;
	line-height: 62px;
	padding: 0 30px;
	text-align: center;
	text-shadow: 0 1px rgba(0,0,0,.4)
}
#payment_credit_card a.button:hover {
	background: linear-gradient(#499DF1, #1A84ED);
	border-bottom-color: #0F6AC5;
	color: #FFF
}
#payment_credit_card a.button:active {
	border-bottom: 0 none;
	box-shadow: 0 1px 5px rgba(0,0,0,.4) inset;
	margin-top: 2px
}
#payment_credit_card h1 {
	font-size: 56px;
	font-weight: 200;
	line-height: 64px
}
#payment_credit_card h2 {
	color: #888;
	font-family: serif;
	font-size: 20px;
	font-style: italic;
	margin-bottom: 32px
}
#payment_credit_card h3 {
	font-weight: 600;
	text-transform: uppercase
}
#payment_credit_card p {
	color: #707070;
	margin-bottom: 32px
}
#payment_credit_card .list {
	color: #707070;
	font-size: 14px;
	list-style: disc;
	margin-bottom: 32px;
	margin-left: 25px
}
#payment_credit_card .list .list {
	margin-bottom: 0
}
#payment_credit_card code {
	background-color: #F5F5F5;
	border: 1px solid #DDD;
	color: #555;
	font-family: monospace;
	font-size: 14px
}
#payment_credit_card pre code {
	display: block;
	line-height: 20px;
	margin-bottom: 32px;
	overflow: scroll;
	padding: 14px 20px
}
#payment_credit_card p+pre code {
	margin-top: -32px
}
#payment_credit_card .demo .numbers {
	background-color: #FFD;
	border: 1px solid #EEC;
	margin-bottom: 32px;
	padding: 16px 20px
}
#payment_credit_card .demo .numbers .list, #payment_credit_card .demo .numbers p {
	margin-bottom: 0
}
#payment_credit_card .example {
	clear: both;
	margin-bottom: 32px
}
#payment_credit_card .cards {
	overflow: hidden
}
#payment_credit_card .cards li {
	background-image: url(catalog/view/theme/default/image/card_logos.png);
	background-position: 0 0;
	float: left;
	height: 32px;
	margin-right: 8px;
	text-indent: -9999px;
	transition: all .2s ease 0s;
	width: 51px
}
#payment_credit_card .cards li:last-child {
	margin-right: 0
}
#payment_credit_card .cards .visa_electron {
	background-position: 204px 0
}
#payment_credit_card .cards .mastercard {
	background-position: 153px 0
}
#payment_credit_card .cards .maestro {
	background-position: 102px 0
}
#payment_credit_card .cards .discover {
	background-position: 51px 0
}
#payment_credit_card .cards .visa.off {
	background-position: 0 32px
}
#payment_credit_card .cards .visa_electron.off {
	background-position: 204px 32px
}
#payment_credit_card .cards .mastercard.off {
	background-position: 153px 32px
}
#payment_credit_card .cards .maestro.off {
	background-position: 102px 32px
}
#payment_credit_card .cards .discover.off {
	background-position: 51px 32px
}
#payment_credit_card form {
	/*background: linear-gradient(#FFF, #F5F5F5) #F8F8F8;
	border: 5px solid #FFF;
	box-shadow: 0 1px 3px #BBB;*/
	margin: 0 auto 32px;
	padding: 10px;
	width: 98%;
	float: left
}
#payment_credit_card form h2 {
	color: #555;
	font-family: "open sans";
	font-size: 16px;
	font-style: normal;
	margin-bottom: 0;
	font-weight: 700
}
#payment_credit_card form li {
	margin: 8px 0
}
#payment_credit_card form label {
	color: #555;
	display: block;
	font-size: 16px;
	font-weight: 700
}
#payment_credit_card form label small {
	color: #AAA;
	font-size: 11px;
	line-height: 11px;
	text-transform: uppercase
}
#payment_credit_card form input {
	-moz-border-bottom-colors: none;
	-moz-border-left-colors: none;
	-moz-border-right-colors: none;
	-moz-border-top-colors: none;
	-moz-box-sizing: content-box;
	border-color: #AAA #DDD #DDD #AAA;
	border-image: none;
	border-style: solid;
	border-width: 1px;
	box-shadow: 0 1px 3px -1px #AAA inset;
	color: #333;
	display: block;
	font-size: 18px;
	height: 30px;
	padding: 0 5px;
	width: 275px
}
#payment_credit_card form input.valid {
	background: url(image/tick.png) 260px center no-repeat
}
#payment_credit_card .vertical {
	overflow: hidden;
	width: 287px
}
#payment_credit_card .vertical li {
	float: left;
	width: 100px
}
#payment_credit_card .vertical li:last-child {
	float: right;
	text-align: right
}
#payment_credit_card .vertical li:last-child input {
	float: right
}
#payment_credit_card .vertical input {
	width: 68px
}
#payment_credit_card .footer {
	font-size: 12px;
	text-align: center
}
#payment_credit_card .fork_me {
	position: absolute;
	right: 0;
	top: 0
}
#payment_credit_card .fork_me a:hover {
	border: 0
}

@media screen and (max-width:767px) {
#payment_credit_card .demo .numbers ul {
	margin-left: 0;
	overflow: hidden
}
#payment_credit_card .demo .numbers ul li {
	float: left;
	margin-left: 24px
}
}

@media screen and (min-width:768px) {
#payment_credit_card body {
	margin: 0
}
#payment_credit_card #container {
	margin: 32px auto;
	width: 700px
}
#payment_credit_card form {
	float: left;
	margin: 0 0 20px;
	padding: 12px;
}
#payment_credit_card .demo .numbers {
	float: right
}
#payment_credit_card .download {
	clear: both
}
}
</style>

<div class="quickcheckout-heading"><i class="fa fa-credit-card"></i>Credit Card Details</div>
 <div class="quickcheckout-content">
	<div id="payment_credit_card" class="payment_credit_card"> 
<form id="creditCardForm">
                <ul>
                    <li>
                        <ul class="cards">
                            <li class="visa">Visa</li>
                            <li class="visa_electron">Visa Electron</li>
                            <li class="mastercard">MasterCard</li>
                            <li class="maestro">Maestro</li>
                            <li class="discover">Discover</li>
                        </ul>
                    </li>

                    <li>
                        <label for="card_number">Card number</label>
                        <input type="text" name="cc_number" id="card_number">
                        <input type="hidden" name="cc_type" id="card_type">
                    </li>

                    <li class="vertical">
                        <ul>
                            <li class="dateYearLI">
                                <label for="expiry_date">Expiry date <small>mm/yy</small></label>
                               <select name="cc_expire_date_month">
                                  <?php foreach ($months as $month) { ?>
                                  <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
                                  <?php } ?>
        					</select>
                            /
                            <select name="cc_expire_date_year">
                              <?php foreach ($year_valid as $year) { ?>
                              <option value="<?php echo $year['value']; ?>"><?php echo substr($year['text'], 2); ?></option>
                              <?php } ?>
                            </select>
                                <!--<input type="text" name="cc_expire_date_month" id="expiry_date" maxlength="5">-->
                            </li>

                            <li>
                                <label for="cvv" class="cvv">CVV</label>
                                <input type="text" name="cc_cvv2" id="cvv" maxlength="3">
                            </li>
                        </ul>
                    </li>

                    <li>
                        <label for="name_on_card">Name on card</label>
                        <input type="text" name="name_on_card" id="name_on_card">
                    </li>
                </ul>
            </form>
 
        </div>

   	<div class="buttons" style="display: block;">
  		<div class="" style="display: block;">
    		<input type="button" class="button" id="button-confirm" value="Pay Now">
	  </div>
	</div>
</div>
<!--<form class="form-horizontal">
<h2>Payment details</h2>

                <ul>
                    <li>
                        <ul class="cards">
                            <li class="visa">Visa</li>
                            <li class="visa_electron">Visa Electron</li>
                            <li class="mastercard">MasterCard</li>
                            <li class="maestro">Maestro</li>
                            <li class="discover">Discover</li>
                        </ul>
                    </li>

                    <li>
                        <label for="card_number">Card number</label>
                        <input type="text" name="cc_number" id="card_number">
                        <input type="hidden" name="cc_type" id="card_type">
                    </li>
  <fieldset id="payment">
    <legend><?php echo $text_credit_card; ?></legend>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-type"><?php echo $entry_cc_type; ?></label>
      <div class="col-sm-10">
        <select name="cc_type" id="input-cc-type" class="form-control">
          <?php foreach ($cards as $card) { ?>
          <option value="<?php echo $card['value']; ?>"><?php echo $card['text']; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-number"><?php echo $entry_cc_number; ?></label>
      <div class="col-sm-10">
        <input type="text" name="cc_number" value="" placeholder="<?php echo $entry_cc_number; ?>" id="input-cc-number" class="form-control" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-cc-start-date"><span data-toggle="tooltip" title="<?php echo $help_start_date; ?>"><?php echo $entry_cc_start_date; ?></span></label>
      <div class="col-sm-3">
        <select name="cc_start_date_month" id="input-cc-start-date" class="form-control">
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-3">
        <select name="cc_start_date_year" class="form-control">
          <?php foreach ($year_valid as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-expire-date"><?php echo $entry_cc_expire_date; ?></label>
      <div class="col-sm-3">
        <select name="cc_expire_date_month" id="input-cc-expire-date" class="form-control">
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-3">
        <select name="cc_expire_date_year" class="form-control">
          <?php foreach ($year_expire as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-cvv2"><?php echo $entry_cc_cvv2; ?></label>
      <div class="col-sm-10">
        <input type="text" name="cc_cvv2" value="" placeholder="<?php echo $entry_cc_cvv2; ?>" id="input-cc-cvv2" class="form-control" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-cc-issue"><span data-toggle="tooltip" title="<?php echo $help_issue; ?>"><?php echo $entry_cc_issue; ?></span></label>
      <div class="col-sm-10">
        <input type="text" name="cc_issue" value="" placeholder="<?php echo $entry_cc_issue; ?>" id="input-cc-issue" class="form-control" />
      </div>
    </div>
  </fieldset>
</form>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>-->
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({
		url: 'index.php?route=payment/pp_pro/send',
		type: 'post',
		data: $('#creditCardForm :input'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-confirm').attr('disabled', true);
			$('#creditCardForm').before('<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('.alert').remove();
			$('#button-confirm').attr('disabled', false);
		},
		success: function(json) {
			if (json['error']) {
				alert(json['error']);
			}
		
			if (json['success']) {
				location = json['success'];
			}
		}
	});
});
//--></script>