<style>
	.product_labels_page{
		background-color:#eee;
		width:<?php echo $pagew; ?>px;
		height:<?php echo $pageh; ?>px;
		box-shadow: 4px 4px 2px #cccccc;
		outline:1px solid #ddd;
		overflow:hidden;
		padding:0px;

	}
	.product_labels_page-margin {
		width:<?php echo $pagew; ?>px;
		overflow:hidden;
		padding-top:<?php echo $margint; ?>px;
		padding-left:<?php echo $marginl; ?>px;
	}
	.product_labels_label {
		float:left;
		width:<?php echo $labelw; ?>px;
		height:<?php echo $labelh; ?>px;
		margin-top:0px;
		margin-left:0px;
		margin-bottom:<?php echo $vspacing; ?>px;
		margin-right:<?php echo $hspacing; ?>px;
		border:1px solid #999;
		background-color:#fff;
		color: #999;
		-moz-border-radius: <?php echo $rounded*3; ?>px; border-radius: <?php echo $rounded*3; ?>px;
		vertical-align: middle;
	}
</style>
<div class="product_labels_page" style="margin-left:30px;">
	<div class="product_labels_page-margin">
	<?php for($i=1;$i<=($numw*$numh);$i++) { ?>
		<div class="product_labels_label"><small>&nbsp;<?php echo $i; ?></small></div>
	<?php } ?>
	</div>
</div>

