<?php echo $header; ?>

<?php if (version_compare(VERSION, '2.0.0.0', '>=')) { ?>
    <script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
    <link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
    <script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>

    <?php echo $column_left; ?>
    <div id="content">
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <?php if (!empty($button_apply_allowed)){ ?>
                      <button onclick="ajax_loading_open();$('input[name=no_exit]').val(1);save_configuration_ajax($('form#<?= $extension_name; ?>'));" type="submit" form="form-account" data-toggle="tooltip" title="<?php echo $apply_changes; ?>" class="btn btn-primary"><i class="fa fa-check"></i></button>
                    <?php } ?>

                    <?php if (!empty($button_save_allowed)){ ?>
                      <button onclick="ajax_loading_open();$('input[name=no_exit]').val(0);$('form#<?= $extension_name; ?>').submit()" type="submit" form="form-account" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                    <?php } ?>
                    
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
                    <h1><?php echo $heading_title_2; ?></h1>
                    <ul class="breadcrumb">
                        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="container-fluid">
                <?php
                $session_msg_types = array('error', 'error_expired', 'info' ,'success');

                foreach ($session_msg_types as $key => $msn_type)
                {
                   foreach ($_SESSION as $session_key => $value)
                   {
                        if($msn_type == $session_key && !is_array($value))
                        {
                            ${'session_message_'.$msn_type} = $value;
                            unset($_SESSION[$session_key]);
                            break;
                        }
                        elseif(is_array($value))
                        {
                            foreach ($value as $session_key_2 => $value_2)
                            {
                                if($msn_type == $session_key_2 && !is_array($value_2))
                                {
                                    ${'session_message_'.$msn_type} = $value_2;
                                    unset($_SESSION[$session_key][$session_key_2]);
                                    break;
                                } 
                            }
                        }
                   }
                }
                ?>
                <?php if (!empty($session_message_error_expired)) { ?>
                    <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <?php echo $session_message_error_expired; ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php } ?>
                <?php if (!empty($session_message_error)) { ?>
                    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $session_message_error; ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php } ?>
                <?php if (!empty($session_message_info)) { ?>
                    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $session_message_info; ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php } ?>
                <?php if (!empty($session_message_success)) { ?>
                    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $session_message_success; ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php } ?>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
                    </div>
                    <div class="panel-body">
                        <?php if (!empty($form)) { ?>
                            <?= $form ?>
                        <?php } else { ?>
                            <div class="license_form_container opencart_<?= $oc_version ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form id="license_form">
                                            <h2 class="heading"><?= $text_validate_license ?></h2>
                                            <input type="text" id="license_id" name="license_id" class="form-control" placeholder="<?= $text_license_id ?>" required="" value="<?= $license_id ?>">
                                            <a class="btn btn-lg btn-primary btn-block" onclick="ajax_get_form();return false;"><?= $text_send ?></a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

<?php } else { ?>
    <div id="content">
        <div class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php } ?>
        </div>
        <?php if (!empty($this->session->data['error'])) { ?>
            <div class="warning"><?php echo $this->session->data['error']; unset($this->session->data['error']) ?></div>
        <?php } ?>
        <?php if (!empty($this->session->data['info'])) { ?>
            <div class="info"><?php echo $this->session->data['info']; unset($this->session->data['info']) ?></div>
        <?php } ?>
        <?php if (!empty($this->session->data['success'])) { ?>
            <div class="success"><?php echo $this->session->data['success']; unset($this->session->data['success']) ?></div>
        <?php } ?>
        <div class="box">
            <div class="heading">
                <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
                <div class="buttons">
                    <?php if (!empty($button_apply_allowed)){ ?>
                        <a onclick="$('input[name=no_exit]').val(1);save_configuration_ajax($('form#<?= $extension_name; ?>'));" class="button"><?php echo $apply_changes; ?></a>
                    <?php } ?>

                    <?php if (!empty($button_save_allowed)){ ?>
                        <a onclick="ajax_loading_open();$('input[name=no_exit]').val(0);$('form').submit();" class="button"><?php echo $button_save; ?></a>
                    <?php } ?>
                    <a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
                </div>
            </div>
            <div class="content">
                <?php if (!empty($form)) { ?>
                    <?= $form ?>
                <?php } else { ?>
                    <div class="license_form_container opencart_<?= $oc_version ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="license_form">
                                    <h2 class="heading"><?= $text_validate_license ?></h2>
                                    <input type="text" id="license_id" name="license_id" class="form-control" placeholder="<?= $text_license_id ?>" required="" value="<?= $license_id ?>">
                                    <a class="btn btn-lg btn-primary btn-block" onclick="ajax_get_form();return false;"><?= $text_send ?></a>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $( document ).ready(function() {
            $('#tabs a').tabs();
            $('input.date').datepicker({dateFormat: 'yy-mm-dd'});
        });
    </script>

<?php } ?>

<script type="text/javascript">
    var token = '<?php echo !empty($token) ? $token : ''; ?>';
    var text_none = '<?= !empty($text_none) ? $text_none : 'none'; ?>';
</script>

<?php if(!empty($jquery_variables)) { ?>
    <script type="text/javascript">
        <?php foreach ($jquery_variables as $var_name => $value) {
            $is_numeric = is_numeric($value);
            echo 'var '.$var_name.' = '.(!$is_numeric ? '"':'').$value.(!$is_numeric ? '"':'').';'."\n";
        } ?>
    </script>
<?php } ?>

<script type="text/javascript">
    $('input.switch_label').bootstrapSwitch();
</script>

<script type="text/javascript">
  function autocomplete_google_category(input)
  {
    var input_final_result = input.next('input[type="hidden"]');
    var url = 'index.php?route=<?= version_compare(VERSION, '2.3.0.0', '>=') ? 'extension/': '';?>module/google_all&force_function=autocomplete&token='+token+'&country_id=' +  $('select.merchantcenter_country:visible').val()+ '&text=' +  input.val();
    var id_name = 'id';

    autocomplete_input(input, input_final_result, id_name, url, token);
  }
</script>

<script type="text/javascript">
  $(document).on('ready', function(){
    <?php $fields = array('color', 'gender', 'age_group', 'size', 'material', 'custom_label_0', 'custom_label_1', 'custom_label_2', 'custom_label_3', 'custom_label_4', 'custom_label_5'); ?>
    <?php foreach ($stores as $key => $store) { ?>
      <?php foreach ($fields as $key2 => $field) { ?>
        $('input[name="google_base_pro_product_<?php echo $field; ?>_<?php echo $store["store_id"]; ?>"]').on('change', function(){
          changed_switch('<?php echo $field; ?>', <?php echo $store["store_id"]; ?>);     
        });
        changed_switch('<?php echo $field; ?>', <?php echo $store["store_id"]; ?>);

        $('select[name="google_base_pro_product_<?php echo $field; ?>_attribute_<?php echo $store["store_id"]; ?>"]').on('change', function(){
            if($(this).val() != "")
            {
              $('select[name="google_base_pro_product_<?php echo $field; ?>_filter_<?php echo $store["store_id"]; ?>"]').val('').selectpicker('refresh');
              $('select[name="google_base_pro_product_<?php echo $field; ?>_option_<?php echo $store["store_id"]; ?>"]').val('').selectpicker('refresh');
            }
        });

        $('select[name="google_base_pro_product_<?php echo $field; ?>_filter_<?php echo $store["store_id"]; ?>"]').on('change', function(){
            if($(this).val() != "")
            {
              $('select[name="google_base_pro_product_<?php echo $field; ?>_attribute_<?php echo $store["store_id"]; ?>"]').val('').selectpicker('refresh');
              $('select[name="google_base_pro_product_<?php echo $field; ?>_option_<?php echo $store["store_id"]; ?>"]').val('').selectpicker('refresh');
            }
        });

        $('select[name="google_base_pro_product_<?php echo $field; ?>_option_<?php echo $store["store_id"]; ?>"]').on('change', function(){
            if($(this).val() != "")
            {
              $('select[name="google_base_pro_product_<?php echo $field; ?>_attribute_<?php echo $store["store_id"]; ?>"]').val('').selectpicker('refresh');
              $('select[name="google_base_pro_product_<?php echo $field; ?>_filter_<?php echo $store["store_id"]; ?>"]').val('').selectpicker('refresh');
            }
        });
      <?php } ?>
    <?php } ?>
  });

  function changed_switch(input_name, store)
  {
    if($('input[name="google_base_pro_product_'+input_name+'_'+store+'"]').is(':checked'))
    {
        $('input[name="google_base_pro_product_'+input_name+'_split_'+store+'"]').closest('div.form-group').show();
        $('input[name="google_base_pro_product_'+input_name+'_fixed_word_'+store+'"]').closest('div.form-group').show();
        $('select[name="google_base_pro_product_'+input_name+'_attribute_'+store+'"]').closest('div.form-group').show();
        $('select[name="google_base_pro_product_'+input_name+'_filter_'+store+'"]').closest('div.form-group').show();
        $('select[name="google_base_pro_product_'+input_name+'_option_'+store+'"]').closest('div.form-group').show();
    }
    else
    {
        $('input[name="google_base_pro_product_'+input_name+'_split_'+store+'"]').closest('div.form-group').hide();
        $('input[name="google_base_pro_product_'+input_name+'_fixed_word_'+store+'"]').closest('div.form-group').hide();
        $('select[name="google_base_pro_product_'+input_name+'_attribute_'+store+'"]').closest('div.form-group').hide();
        $('select[name="google_base_pro_product_'+input_name+'_filter_'+store+'"]').closest('div.form-group').hide();
        $('select[name="google_base_pro_product_'+input_name+'_option_'+store+'"]').closest('div.form-group').hide();
    }
  }
</script>

<?php echo $footer; ?>