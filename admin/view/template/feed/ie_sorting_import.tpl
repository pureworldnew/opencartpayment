<?php echo $header;?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
            		</div>
                <h1><?php echo $heading_title; ?></h1>
              <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
              </ul>
        </div>
      </div>
        <div class="container-fluid">
            <h2>Update Sorting</h2>
            <form action="<?php echo $action_import;?>" method="post" enctype="multipart/form-data" id="form_import_units">
                <table class="table table-bordered table-hover">
                    <tr>
                        <td>
                            <label for="file">Browse file to Import sorting:</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="btn btn-default btn-file">
                                Browse <input  name="file" id="file2" type="file" style="display: none;">
                            </label>

                        </td>
                        <td>

                            <label class="btn btn-default btn-file">
                                import <input value="import" type="submit" style="display: none;">
                            </label>
                        </td>
                    </tr>
                    <label><b>Headings for CSV => </b></label><span style="margin-left: 20px;">option_value_id; name; sort_order</span>
                </table>
            </form>
        </div>
    </div>
</div>
<?php echo $footer;?>