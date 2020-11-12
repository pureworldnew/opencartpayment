<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-jax-search" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div><br/>
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-setting" data-toggle="tab"><?php echo $tab_setting; ?></a></li>
                <li><a href="#supporttabs" data-toggle="tab"><?php echo $tab_support; ?></a></li>
                <li id="mmos-offer"></li>
                <li class="pull-right"><a  class="link" href="http://www.opencart.com/index.php?route=extension/extension&filter_username=mmosolution" target="_blank" class="text-success"><img src="//mmosolution.com/image/opencart.gif"> More Extension...</a></li>
                <li class="pull-right"><a  class="text-link"  href="http://mmosolution.com" target="_blank" class="text-success"><img src="//mmosolution.com/image/mmosolution_20x20.gif">More Extension...</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab-setting">
                    <div class="panel-body">
                        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-jax-search" class="form-horizontal">
                            <div class="well">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" onchange="window.location = 'index.php?route=module/mmos_ajax_search&token=<?php echo $token; ?>&store_id=' + $(this).val();">
                                            <?php foreach($stores as $store){ ?>
                                            <option value="<?php echo $store['store_id']; ?>" <?php echo ($store_id == $store['store_id'])? 'selected' : ''; ?>><?php echo $store['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div> 
                            <div class="well">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-limit"><span data-toggle="tooltip" title="<?php echo $text_help; ?>"><?php echo $entry_limit; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="mmos_ajax_search[limit]" value="<?php echo isset($mmos_ajax_search['limit']) ? $mmos_ajax_search['limit'] : ''; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $text_help; ?>"><?php echo $entry_image; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="checkbox" name="mmos_ajax_search[image]" value="1" class="form-control" <?php echo isset($mmos_ajax_search['image'])?'checked':'';?>/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_width; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="mmos_ajax_search[width]" value="<?php echo isset($mmos_ajax_search['width']) ? $mmos_ajax_search['width']:''; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
                                        <?php if ($error_width) { ?>
                                        <div class="text-danger"><?php echo $error_width; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-height"><?php echo $entry_height; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="mmos_ajax_search[height]" value="<?php echo isset($mmos_ajax_search['height']) ? $mmos_ajax_search['height']:''; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
                                        <?php if ($error_height) { ?>
                                        <div class="text-danger"><?php echo $error_height; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-height"><?php echo $entry_maxtext; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="mmos_ajax_search[maxtext]" value="<?php echo isset($mmos_ajax_search['maxtext']) ? $mmos_ajax_search['maxtext']: 50; ?>" placeholder="<?php echo $entry_maxtext; ?>" id="input-height" class="form-control" />

                                    </div>
                                </div>
                              

                                    <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_search_in; ?></label>
                                    <div class="col-sm-10">
                                        <select name="mmos_ajax_search[search][]" id="input-status" class="form-control" multiple="multiple">                
                                            <option value="1" selected><?php echo $entry_search_name; ?></option>
                                            <option value="2" <?php if(isset($mmos_ajax_search['search']) && in_array(2 ,$mmos_ajax_search['search'] )) { echo 'selected';} ?>><?php echo $entry_search_tag; ?></option>
                                            <option value="3" <?php if(isset($mmos_ajax_search['search']) && in_array(3 ,$mmos_ajax_search['search'] )) { echo 'selected';} ?>><?php echo $entry_search_des; ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                                    <div class="col-sm-10">
                                        <select name="mmos_ajax_search[status]" id="input-status" class="form-control">
                                            <?php if (isset($mmos_ajax_search['status']) && ($mmos_ajax_search['status'] == 1)) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane" id="supporttabs">
                    <div class="panel">
                        <div class=" clearfix">
                            <div class="panel-body">
                                <h4> About <?php echo $heading_title; ?></h4>
                                <h5>Installed Version: V.<?php echo $MMOS_version; ?> </h5>
                                <h5>Latest version: <span id="mmos_latest_version"><a href="http://mmosolution.com/index.php?route=product/search&search=<?php echo trim(strip_tags($heading_title)); ?>" target="_blank">Unknown -- Check</a></span></h5>
                                <hr>
                                <h4>About Author</h4>
                                <div id="contact-infor">
                                    <i class="fa fa-envelope-o"></i> <a href="mailto:support@mmosolution.com?Subject=<?php echo trim(strip_tags($heading_title)).' OC '.VERSION; ?>" target="_top">support@mmosolution.com</a></br>
                                    <i class="fa fa-globe"></i> <a href="http://mmosolution.com" target="_blank">http://mmosolution.com</a> </br>
                                    <i class="fa fa-ticket"></i> <a href="http://mmosolution.com/support/" target="_blank">Open Ticket</a> </br>
                                    <br>
                                    <h4>Our on Social</h4>
                                    <a href="http://www.facebook.com/mmosolution" target="_blank"><i class="fa fa-2x fa-facebook-square"></i></a>
                                    <a class="text-success" href="http://plus.google.com/+Mmosolution" target="_blank"><i class="fa  fa-2x fa-google-plus-square"></i></a>
                                    <a class="text-warning" href="http://mmosolution.com/mmosolution_rss.rss" target="_blank"><i class="fa fa-2x fa-rss-square"></i></a>
                                    <a href="http://twitter.com/mmosolution" target="_blank"><i class="fa fa-2x fa-twitter-square"></i></a>
                                    <a class="text-danger" href="http://www.youtube.com/mmosolution" target="_blank"><i class="fa fa-2x fa-youtube-square"></i></a>
                                </div>
                                <div id="relate-products">
                                </div>
                            </div>
                        </div>
                    </div>	
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="//mmosolution.com/support.js"></script>
<script type="text/javascript"><!--
var productcode = '<?php echo $MMOS_code_id ;?>';
//--></script>
<?php echo $footer; ?>