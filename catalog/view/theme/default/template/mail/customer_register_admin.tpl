<div class="emailContent">{$emailtemplate.content1}</div>

<?php if (isset($account_approve) && $account_approve) { ?>
<div class="link">
	<?php echo $text_approve; ?><br />
	<span class="icon">&raquo;</span>
	<a href="<?php echo $account_approve; ?>" target="_blank">
		<b><?php echo $account_approve; ?></b>
	</a>
	<br />
</div>
<?php } ?>

<?php if (isset($customer_group) && $customer_group) { ?>
<br />
<strong><?php echo $text_customer_group; ?></strong> <?php echo $customer_group; ?>
<?php } ?>

<div class="emailContent">{$emailtemplate.content2}</div>