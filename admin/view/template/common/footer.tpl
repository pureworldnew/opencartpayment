<footer id="footer"><?php echo $text_footer; ?><br /><?php echo $text_version; ?></footer></div>
<?php if($text_version != '') { ?>
<!-- Ping Server to keep the session alive -->
<script>
	$(document).ready(function() {
		setInterval(function() {
			$.ajax({
				url: 'index.php?route=server/server/ping&token=<?php echo $token; ?>',
      			dataType: 'json',
				success: function(res) {
					console.log(res);
				},
				error: function() {

				}
			});
		}, 5 * 60 * 1000);
	});
</script>

<!-- Ping Server to keep the session alive -->
<?php } ?>
</body></html>
