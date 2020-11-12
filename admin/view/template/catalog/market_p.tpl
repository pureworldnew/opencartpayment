<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
        <div class="container-fluid">
                <h1><?php echo $heading_title; ?></h1>
                <div class="pull-right">
            	
                <a href="<?php echo $db_cron; ?>" target="_blank" class="btn btn-warning"><?php echo $button_db_cron; ?></a>
                <a href="<?php echo $discount_update; ?>" class="btn btn-success"><?php echo $button_discount_update; ?></a>
                <a onclick="saveAll();" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary">Save & Update Multiplier Values</a>	
            </div>
        </div>
        	<?php if (isset($error_warning)) { ?>
          		<div class="warning"><?php echo $error_warning; ?></div>
          	<?php } ?>
      </div>
<div class="container-fluid">
    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
   <table class="table table-bordered table-hover">
        <thead class='list'>
            <th><?php echo $text_name; ?></th>
            <th><?php echo $text_value; ?></th>
            <th>Action</th>
        </thead>
        <tbody>
            <?php foreach ($cats as $cat) { ?>
            <tr>
                <td class='left'><?php echo $cat['name'];?></td>
                <td><input type="text" class="form-control" name='market_price[<?php echo $cat['name'];?>]' value="<?php echo round($cat['market_price'], 5); ?>"></td>
                <td><button class="btn btn-primary" title="Edit" onclick="editMetalPrice('<?php echo $cat['option_value_id']; ?>','<?php echo $cat['name']; ?>','<?php echo round($cat['market_price'], 5); ?>');"><i class="fa fa-pencil"></i></button>
                <button class="btn btn-danger" title="Delete" onclick="deleteMetalPrice('<?php echo $cat['option_value_id']; ?>');"><i class="fa fa-trash-o"></i></button></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</form>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update</h4>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="metal_price_form">
          <input type="hidden" id="option_value_id" value="">
          <div class="form-group">
            <label for="multiplier_name"><?php echo $text_name; ?></label>
            <input type="text" class="form-control" id="multiplier_name" placeholder="<?php echo $text_name; ?>" value="">
          </div>
          <div style="margin-bottom:20px;">
            <label for="multiplier_value"><?php echo $text_value; ?></label>
            <input type="text" class="form-control" id="multiplier_value" placeholder="<?php echo $text_value; ?>" value="">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateMetalPrice();">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->

</div>
<?php echo $footer;?>
<script type="text/javascript">

function saveAll()
{
  $('#form').attr('onsubmit','return true;');
  $('#form').submit();
}


function deleteMetalPrice(option_value_id)
{
  $('#form').attr('onsubmit','return false;');
  var data = {};
  var result = confirm("Are you sure, you want to delete?");
  if (result) {
      $.ajax({
          url: 'index.php?route=catalog/marketp/deleteMetalPrice&option_value_id=' + option_value_id + '&token=<?php echo $token; ?>',
          type: 'post',
          data: data,
          dataType: 'json',
          success: function(json) {
            location.reload();
          }
      });
  }
}

function editMetalPrice(option_value_id, name, market_price)
{
    $('#form').attr('onsubmit','return false;');
    $("#multiplier_name").val(name);
    $("#multiplier_value").val(market_price);
    $("#option_value_id").val(option_value_id);
    $('#myModal').modal();
}

function updateMetalPrice()
{
      var multiplier_name       = $("#multiplier_name").val();
      var multiplier_value      = $("#multiplier_value").val();
      var option_value_id       = $("#option_value_id").val();

      var data = {};
      data['name']   = multiplier_name;
      data['metal_price']  = multiplier_value;
      data['option_value_id']   = option_value_id;

      $.ajax({
      url: 'index.php?route=catalog/marketp/updateMetalPrice&token=<?php echo $token; ?>',
      type: 'post',
		  data: data,
			dataType: 'json',
			success: function(json) {
        location.reload();
			}
    });
  }
</script>
