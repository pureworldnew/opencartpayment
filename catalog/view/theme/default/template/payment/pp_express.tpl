<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="buttons">
  <div class="pull-right">
    <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
    <br>
    <a href="javascript:void(0)" class="btn btn-primary pull-right" onclick="verifyCaptcha();"><?php echo $button_continue; ?></a>
  </div>
</div>
<script type="text/javascript">
function verifyCaptcha()
{
  var response = grecaptcha.getResponse();
  if(response.length == 0)
  {
      alert("Please verify reCaptcha first.");
  } else {
    window.location.href = "<?php echo $button_continue_action; ?>";
  }
}
</script>