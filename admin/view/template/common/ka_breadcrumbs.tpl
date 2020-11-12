<?php
/*
  Project: Ka Extensions
  Author : karapuz <support@ka-station.com>

  Version: 3 ($Revision: 29 $) 
*/

?>

<?php if (!empty($breadcrumbs)) { ?>
<ul class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php if (!empty($breadcrumb['href'])) { ?>
			<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		<?php } else { ?>
			<li><?php echo $breadcrumb['text']; ?></li>
		<?php } ?>
	<?php } ?>
</ul>
<?php } ?>