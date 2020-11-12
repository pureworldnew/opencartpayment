<?php if($logged){ ?>
<div class="profile">
	<div class="image">
		<?php if($prodileimage){ ?>
			<img src="<?php echo $prodileimage; ?>" class="img-responsive" alt="profile Image" title="profile Image"/>
		<?php } elseif($defaulpic) { ?>				
			<img src="<?php echo $defaulpic; ?>" class="img-responsive" alt="profile Image" title="profile Image"/>				
		<?php } else { ?>
			<img src="<?php echo $placeholder1; ?>" class="img-responsive img-thumbnail" alt="profile Image" title="profile Image"/>
		<?php } ?>
	</div>
	<div class="detail">
		<h3><?php echo $firstname; ?><?php echo $lastname; ?></h3>
		<p><?php echo $email; ?></p>
		<ul class="list-inline">
			<div class="btnedit">
				<a href="<?php echo $href; ?>" data-toggle="tooltip" title="<?php echo $text_edit; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
			</div>
			<div class="btnedit">
				<a href="<?php echo $logout; ?>" data-toggle="tooltip" title="<?php echo $text_logout; ?>"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
			</div>
		</ul>
	</div>
</div>
<?php } ?>
<?php if($account_status){?>
<div class="list-group">
	<h2><i class="fa fa-list" aria-hidden="true"></i> 
	
	<?php echo $text_myprofile; ?>

	</h2>
	<div class="box1">
			<?php if (!$logged) { ?>
				<?php if($account_login){?>
					<a href="<?php echo $login; ?>" class="list-group-item"><i class="fa fa-sign-in" aria-hidden="true"></i> <?php echo $text_login; ?></a>
				<?php } ?>
				<?php if($account_register){?>
					<a href="<?php echo $register; ?>" class="list-group-item"><i class="fa fa-user-plus" aria-hidden="true"></i> <?php echo $text_register; ?></a> 
				<?php } ?>
				<?php if($account_forgot){?>
					<a href="<?php echo $forgotten; ?>" class="list-group-item"><i class="fa fa-key" aria-hidden="true"></i> <?php echo $text_forgotten; ?></a>
				<?php } ?>
			<?php } ?>
			<?php if ($logged) { ?>
				<?php if($my_account){?>
					<a href="<?php echo $account; ?>" class="list-group-item"><i class="icon_profile" aria-hidden="true"></i> <?php echo $text_account; ?></a>
				<?php } ?>
				<?php if($edit_account){?>
					<a href="<?php echo $edit; ?>" class="list-group-item"><span class=" icon_pencil_alt"></span> <?php echo $text_edit; ?></a>
				<?php } ?>
				<?php if($change_password){?>
					<a href="<?php echo $password; ?>" class="list-group-item"><i class="fa fa-key" aria-hidden="true"></i> <?php echo $text_password; ?></a>
				<?php } ?>
				<?php if($address_book){?>
					<a href="<?php echo $address; ?>" class="list-group-item"><span class="icon_contacts_alt"></span> <?php echo $text_address; ?></a>
				<?php } ?>
				<?php if($my_wishlist){?>
					<a href="<?php echo $wishlist; ?>" class="list-group-item"><span class="icon_heart"></span> <?php echo $text_wishlist; ?></a>
				<?php } ?>
				<?php if($account_newsletter){?>
					<a href="<?php echo $newsletter; ?>" class="list-group-item"><i class="fa fa-envelope" aria-hidden="true"></i> <?php echo $text_newsletter; ?></a>
				<?php } ?>
				<?php if($account_logout){?>
					<a href="<?php echo $logout; ?>" class="list-group-item"><i class="fa fa-sign-out" aria-hidden="true"></i> <?php echo $text_logout; ?></a>
				<?php } ?>
			<?php } ?>
	</div>	
	<div class="clearfix"></div>
	<h2><span class="icon_document"></span> 
	
	<?php echo $text_order; ?>

	</h2>
	
		<div class="box1">
			<?php if($my_order){?>
				<a href="<?php echo $order; ?>" class="list-group-item"><i class="fa fa-undo" aria-hidden="true"></i><?php echo $text_order; ?></a>
			<?php } ?>
			<?php if($my_downloads){?>
				<a href="<?php echo $download; ?>" class="list-group-item"><i class="fa fa-cloud-download" aria-hidden="true"></i> <?php echo $text_download; ?></a>
			<?php } ?>
			<?php if($my_payments){?>
				<a href="<?php echo $recurring; ?>" class="list-group-item"><i class="fa fa-usd" aria-hidden="true"></i> <?php echo $text_recurring; ?></a> 
			<?php } ?>
			<?php if($my_reward){?>
				<a href="<?php echo $reward; ?>" class="list-group-item"><i class="fa fa-shield" aria-hidden="true"></i> <?php echo $text_reward_point; ?></a>
			<?php } ?>
			<?php if($my_returns){?>
				<a href="<?php echo $return; ?>" class="list-group-item"><i class="fa fa-retweet" aria-hidden="true"></i> <?php echo $text_return; ?></a>
			<?php } ?>
			<?php if($my_transaction){?>
				<a href="<?php echo $transaction; ?>" class="list-group-item"><i class="fa fa-money" aria-hidden="true"></i> <?php echo $text_transaction; ?></a>
			<?php } ?>
		</div>
</div>
<?php } ?>
