<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
	      <button type="submit" form="form-latest-virtualcat" name="latest_virtualcat[continue]" data-toggle="tooltip" title="<?php echo $button_saveandcontinue; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></button>
        <button type="submit" form="form-latest-virtualcat" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-latest-virtualcat" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><span data-toggle="tooltip" title="<?php echo $help_name; ?>"><?php echo $entry_name; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="latest_virtualcat[name]" value="<?php echo $latest_virtualcat['name']; ?>" placeholder="<?php echo $entry_name . $entry_req; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
            <input type="hidden" name="latest_virtualcat[category_id]" value="<?php echo $latest_virtualcat['category_id']; ?>" id="input-category_id" class="form-control" />
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-meta-title"><span data-toggle="tooltip" title="<?php echo $help_meta_title; ?>"><?php echo $entry_meta_title; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="latest_virtualcat[meta_title]" value="<?php echo $latest_virtualcat['meta_title']; ?>" placeholder="<?php echo $entry_meta_title . $entry_req; ?>" id="input-meta-title" class="form-control" />
              <?php if ($error_meta_title) { ?>
              <div class="text-danger"><?php echo $error_meta_title; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort"><span data-toggle="tooltip" title="<?php echo $help_sort; ?>"><?php echo $entry_sort; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="latest_virtualcat[sort]" value="<?php echo $latest_virtualcat['sort']; ?>" placeholder="<?php echo $entry_sort; ?>" id="input-sort" class="form-control" />
              <?php if ($error_sort) { ?>
              <div class="text-danger"><?php echo $error_sort; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-limit"><span data-toggle="tooltip" title="<?php echo $help_limit; ?>"><?php echo $entry_limit; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="latest_virtualcat[limit]" value="<?php echo $latest_virtualcat['limit']; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
              <?php if ($error_limit) { ?>
              <div class="text-danger"><?php echo $error_limit; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label <?php if (isset($latest_virtualcat['sort_option'])) { ?> style="margin-top: -10px;" <?php } ?> class="col-sm-2 control-label" for="input-datelimit"><span data-toggle="tooltip" title="<?php echo $help_datelimit; ?>"><?php echo $entry_datelimit; ?></span><?php if (isset($latest_virtualcat['sort_option']) && $latest_virtualcat['sort_option']) { ?><p style="margin-bottom: 0;color:red;font-size:9px;line-height:12px;"><?php echo $info_datelimit; ?></p><?php } ?></label>
            <div class="col-sm-10">
              <input type="text" name="latest_virtualcat[datelimit]" value="<?php if(isset($latest_virtualcat['datelimit'])) { echo $latest_virtualcat['datelimit']; } ?>" placeholder="<?php echo $entry_datelimit; ?>" id="input-datelimit" class="form-control" />
              <?php if ($error_datelimit) { ?>
              <div class="text-danger"><?php echo $error_datelimit; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-option"><span data-toggle="tooltip" title="<?php echo $help_sort_option; ?>"><?php echo $entry_sort_option; ?></span></label>
            <div class="col-sm-10">
              <select name="latest_virtualcat[sort_option]" id="input-sort-option" class="form-control">
                <?php if ($latest_virtualcat['sort_option']) { ?>
                <option value="0"><?php echo $entry_limit; ?></option>
                <option value="1" selected="selected"><?php echo $entry_datelimit; ?></option>
                <?php } else { ?>
                <option value="0" selected="selected"><?php echo $entry_limit; ?></option>
                <option value="1"><?php echo $entry_datelimit; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label style="margin-top: -10px;" class="col-sm-2 control-label" for="input-sort-latest">
            	<span data-toggle="tooltip" title="<?php echo $help_sort_latest; ?>"><?php echo $entry_sort_latest; ?></span><br/>
            	<span data-toggle="tooltip" title="<?php echo $help_sort_default; ?>" style="margin-bottom: 0;"><?php echo $entry_sort_default; ?>
            		<input type="hidden" value="0" name="latest_virtualcat[sort_default]">
					<input type="checkbox" name="latest_virtualcat[sort_default]" value="1" id="input-sort-default" style="margin-left:5px;vertical-align:-2px;" <?php if ($latest_virtualcat['sort_default']) { ?> checked <?php } ?> />
            	</span>
            </label>
            <div class="col-sm-10">
              <select name="latest_virtualcat[sort_latest]" id="input-sort-latest" class="form-control">
                <?php if ($latest_virtualcat['sort_latest']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><span data-toggle="tooltip" title="<?php echo $help_status; ?>"><?php echo $entry_status; ?></span></label>
            <div class="col-sm-10">
              <select name="latest_virtualcat[status]" id="input-status" class="form-control">
                <?php if ($latest_virtualcat['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
        <?php if ($latest_virtualcat['category_id']) { echo "<a id='virtual_cat' href='{$category_link}'>{$text_info}</a>";}
        ?>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
<script type=text/javascript>
	$(document).delegate('#virtual_cat', 'click', function(e) {
		e.preventDefault();
		
		function changeMenu() {
			$('#menu #catalog li a').eq(0).trigger('click');
		}
		
		$.when( changeMenu() ).done(function() {
			$(location).attr('href',$('#virtual_cat').attr('href'));
		});
	});
</script>