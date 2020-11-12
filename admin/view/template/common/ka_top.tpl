<?php
/*
  Project: Ka Extensions
  Author : karapuz <support@ka-station.com>

  Version: 3 ($Revision: 29 $) 
*/

?>

<?php if (!empty($top_messages)) { ?>
  <?php foreach ($top_messages as $top_message) { ?>
    <?php if ($top_message['type'] == 'E') { ?>
	    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
    <?php } else { ?>
  	  <div class="alert alert-success"><i class="fa fa-check-circle"></i>
    <?php } ?>
   	<?php echo $top_message['content']; ?>
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>    	
  <?php } ?>
<?php } ?>