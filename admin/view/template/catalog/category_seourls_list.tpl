<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
          <button type="button" data-toggle="tooltip" title="Save All" class="btn btn-success" onclick="confirm('Are you sure? This will update all selected categories url.') ? $('#form-category').submit() : false;"><i class="fa fa-save"></i> &nbsp;Save All</button>
      </div>
      <h1><?php echo $heading_title_seourls; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid main-content">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_seo_list; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $saveall; ?>" method="post" enctype="multipart/form-data" id="form-category">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php echo $column_name; ?></td>
                  <td class="text-left"><?php echo $column_seourl; ?></td>
                  <td class="text-left"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($categories) { ?>
                <?php foreach ( $categories as $k => $category ) { ?>
                <tr>
                  <td class="text-center">
                  <input type="hidden" name="category[<?php echo $k; ?>][url_alias_id]" value="<?php echo $category['url_alias_id']; ?>" id="url-alias-id-<?php echo $k; ?>">
                  <?php if (in_array($category['url_alias_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $category['url_alias_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $category['url_alias_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $category['name']; ?></td>
                  <td class="text-left" width="50%"><input type="text" name="category[<?php echo $k; ?>][seo_url]" id="seo-url-<?php echo $k; ?>" class="form-control" value="<?php echo $category['seo_url']; ?>"></td>
                  <td class="text-left"><a href="javascript:void(0)" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary btn-sm" onclick="saveCategorySeoUrl('<?php echo $k ?>');"><i class="fa fa-save"></i> &nbsp;Save</a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>

<script type="text/javascript">

function saveCategorySeoUrl(i)
{ 

    data = {};
    data['url_alias_id'] = $("#url-alias-id-" + i).val();
    data['seo_url'] = $("#seo-url-" + i).val();
    
    $.ajax({
        url: 'index.php?route=catalog/category/saveCategorySeoUrl&token=<?php echo $token; ?>',
        type: 'post',
		    dataType: 'json',
		    data: data,
        success: function(json) {
            if ( json['success'] )
            {
              $(".alert-success").remove();
              $(".alert-danger").remove();
              var html = '<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
              $(".main-content").prepend(html);
            }

            if ( json['error'] )
            {
              $(".alert-success").remove();
              $(".alert-danger").remove();
              var html = '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
              $(".main-content").prepend(html);
            }
        }
    });
    
}
</script>