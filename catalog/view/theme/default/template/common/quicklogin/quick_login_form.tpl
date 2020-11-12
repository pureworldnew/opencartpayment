
<div id="main_page">
    <form class="login_form" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

        <label id="label_email">
            <?php echo $entry_email; ?>
            <input type="text" name="email" id="email" class="datafields" value="<?php echo $email; ?>" />
        </label>
        <label id="label_password">
            <?php echo $entry_password; ?>
            <input type="password" name="password" id="password" class="datafields" value="<?php echo $password; ?>" />
        </label>


        <?php if (!empty($error_warning)) { ?>
            <label for="email" class="login_error" id="error_warning"><?php echo $error_warning; ?></label>
        <?php } ?>

        <input type="submit" class="button button_login" id="" name="button_login" value="<?php echo $button_login; ?>" />

        <div class="login_links">
            <a href="<?php echo $register; ?>" id="link_register"><?php echo $text_register; ?></a>
            <a href="<?php echo $forgotten; ?>" id="link_forgotten"><?php echo $text_forgotten; ?></a>
        </div>

    </form>
</div>