
<script
  type="text/javascript"
  id="ps_checkout"
  src="https://checkout.paystand.com/v4/js/paystand.checkout.js"
  ps-env="live"
  ps-publishableKey="<?php echo $publishable_key; ?>"
  ps-payerName="<?php echo $payer_data['name']; ?>"
  ps-payerEmail="<?php echo $payer_data['email']; ?>"
  ps-payerAddressStreet="<?php echo $payer_data['address']; ?>"
  ps-payerAddressCity="<?php echo $payer_data['city']; ?>"
  ps-payerAddressState="<?php echo $payer_data['state']; ?>"
  ps-payerAddressCountry="<?php echo $payer_data['country']; ?>"
  ps-payerAddressPostal="<?php echo $payer_data['postcode']; ?>"
></script>
<div class="buttons">
  <div class="pull-right">
<button id="button-confirm"
  class="ps-button btn btn-primary"
  ps-checkoutType="checkout_payment"
  ps-paymentAmount="<?php echo $order['amount']; ?>"
  ps-fixedAmount="true"
  ps-paymentCurrency="USD"
>Pay Now!</button>
</div>
</div> 
<script type="text/javascript" src="catalog/view/javascript/paystand.checkout.js"></script>
<script type="text/javascript"><!--

psCheckout.onComplete(function(data){
	OrderConfirmed();
});

function OrderConfirmed()
{
	$.ajax({
		type: 'get',
		url: 'index.php?route=payment/paystand/confirm',
		cache: false,
		success: function() {
			location = '<?php echo $continue; ?>';
		}
	});
}
//--></script>


