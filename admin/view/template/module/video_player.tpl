<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-module" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if(isset($error['permission'])): ?>
            <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error['permission'];?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php elseif (isset($error['warning'])): ?>
            <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error['warning'];?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $save; ?>" method="post" enctype="multipart/form-data" id="form-module" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-message"><?php echo $entry_name; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $placeholder_name; ?>" id="input-message" class="form-control" />
                            <?php if (isset($error['name'])): ?>
                                <div class="text-danger"><?php echo $error['name'];?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-video_path"><?php echo $entry_message; ?> <span data-toggle="tooltip" title="<?php echo $help_message; ?>"></span></label>
                        <div class="col-sm-10">
                            <input type="text" name="video_path" value="<?php echo $video_path ?>" placeholder="<?php echo $placeholder_message; ?>" id="video_path" class="form-control" readonly="true"/>
                            <input type="text" name="file_type" value="<?php echo $file_type ?>" id="file_type" class="form-control" readonly="true"/>
                            <input type="file" onchange="checkFile()" name="video_file" id="video_file" accept="video/mp4,video/x-m4v,video/*"/>
                            <?php if (isset($error['video_path'])): ?>
                                <div class="text-danger"><?php echo $error_video_path; ?></div>
                            <?php endif; ?>
                            <?php if (isset($error['error_video_exists'])): ?>
                                <div class="text-danger"><?php echo $error_video_exists; ?> <button type="button" id="button-del" data-loading-text="<?php text_loading ?>" class="btn btn-primary"><?php button_del ?></button></div>
                            <?php endif; ?>
                            <?php if (isset($error['error_video_upload'])): ?>
                                <div class="text-danger"><?php echo $error_video_upload; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                            <select name="status" id="input-status" class="form-control">
                                <?php if ($status): ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                <?php else: ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden"name="module_id" value="<?php module_id ?>" />
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
$('#button-del').on('click', function () {
        $.ajax({
            url: 'index.php?route=extension/module/video_player/del&token=<?php echo $token; ?>',
            type: 'post',
            dataType: 'json',
            data: new FormData($('#form-module')[0]),
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#button-del').button('loading');
            },
            complete: function () {
                $('#button-del').button('reset');
            },
            success: function (json) {
                $('.alert-dismissible').remove();

                if (json['error']) {
                    $('#video_path').before('<div class="alert alert-warning alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                }

                if (json['success']) {
                    $('#video_path').before('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
                    $('.text-danger').remove();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    function checkFile(event) {
		ff = $("#video_file");
        var name = ff[0].files[0].name;
        $('#video_path').val(name);
        var file_type = ff[0].files[0].type;
        $('#file_type').val(file_type);
        $('.alert-dismissible').remove();

        var url = '<?php echo $path; ?>' + name;

        $.get(url)
                .done(function () {
                    $('#video_path').before('<div class="alert alert-warning alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_video_exists; ?> </div>');

                }).fail(function () {
            $('.alert-dismissible').remove();
        });
    }
//--></script> 
<?php echo $footer; ?>
