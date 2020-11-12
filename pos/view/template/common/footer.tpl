<footer id="footer"><?php echo $text_footer; ?><br /><?php echo $text_version; ?></footer></div>
<?php if($text_version != '') { ?>
<!-- Ping Server to keep the session alive -->
<script>
	
		setInterval(function() {
			$.ajax({
				url: 'index.php?route=server/server/ping&token=<?php echo $token; ?>',
      			dataType: 'json',
				success: function(res) {
                    
                    if(res.status != 'true')
                    {
                        openAlert("POS Session Time Out!");
                        location.reload();
                    }
				},
				error: function() {

				}
			});
		}, 5 * 60 * 1000);
	
</script>

<!-- Ping Server to keep the session alive -->
<?php } ?>
<script type="text/javascript">
                    window.setInterval(function(){ $.get(location.url); }, 60000);
                </script>

</body></html>
