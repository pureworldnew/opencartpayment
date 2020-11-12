<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?php echo $keygen; ?>" class="btn btn-primary" id="keygen"><?php echo $button_keygen; ?>
                </a>
                <button type="submit" form="form-shipstation" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary">
                <i class="fa fa-save"></i>
                </button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default">
                <i class="fa fa-reply"></i>
                </a>
            </div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li>
                <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?>
                </a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-shipstation" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-9">
                            <select name="shipstation_status" id="input-status" class="form-control">
                                <?php if ($shipstation_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-3 control-label" for="entry-config_key"><?php echo $entry_config_key; ?></label>
                        <div class="col-sm-9">
                            <input type="text" name="shipstation_config_key" value="<?php echo $shipstation_config_key; ?>" placeholder="<?php echo $entry_config_key; ?>" id="entry-config_key" class="form-control"/>
                            <?php if ($error_config_key) { ?>
                            <div class="text-danger"><?php echo $error_config_key; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-3 control-label" for="entry-config_ver_key"><?php echo $entry_config_ver_key; ?></label>
                        <div class="col-sm-9">
                            <input type="text" name="shipstation_verify_key" value="<?php echo $shipstation_verify_key; ?>" placeholder="<?php echo $entry_config_ver_key; ?>" id="entry-config_ver_key" class="form-control"/>
                            <?php if ($error_verify_key) { ?>
                            <div class="text-danger"><?php echo $error_verify_key; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="pull-right">
                        <label class="show-log btn btn-danger control-label"><?php echo $button_error_log; ?></label>
                    </div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="clearfix">&nbsp;</div>
                    <div id="error-log" class="clearfix hide">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="entry-config_key"><?php echo $heading_error; ?></label>
                            <div class="col-sm-9">
                                <textarea name="google_hangouts_code" rows="10" id="input-code" class="form-control"><?php echo $log; ?></textarea>
                            </div>
                        </div>
                        <?php if($log):?>
                        <div class="pull-right">
                            <a href="<?php echo $clear; ?>" class="btn btn-danger"><?php echo $button_clear; ?></a>
                        </div>
                        <?php endif;?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        // Confirm Key Generation
        $('#keygen').click(function() {
            if ($(this).attr('href') != null && $(this).attr('href').indexOf('keygen', 1) != -1) {
                if ($('#input-status').val() == 1) {
                    if (confirm('<?php echo $text_confirm; ?>')) {
                        $(this).attr('href', $(this).attr('href') + '&status=1');
                    } else {
                        location.reload();
                        return false;
                    }
                } else {
                    alert('Please Enable the status to generate new keys!');
                    location.reload();
                    return false;
                }
            }
        });
        //Show the error log
        $('.show-log').click(function() {
            $('#error-log').toggleClass('hide');
        });

    });
</script>
<?php echo $footer; ?>