<?php echo $header; ?>
<div id="content">

<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>

<div class="box">
    <div class="heading">
     <h1><?php echo $heading_title; ?></h1>


  <div class="buttons">
  
 <a href="<?php echo $insert; ?>" data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-danger" style="text-decoration: none;padding: 2px 5px;border: 1px solid #999;border-radius: 2px;color: #000;background-color: #EDEDED;"><?php echo $button_insert; ?></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-lookbook').submit() : false;"><?php echo $button_delete; ?></button>
		<button type="button" data-toggle="tooltip" title="<?php echo $button_enable; ?>" class="btn btn-success" onclick="$('#frmaction').val('enable');$('#form-lookbook').submit();"><?php echo $button_enable; ?></button>
		<button type="button" data-toggle="tooltip" title="<?php echo $button_disable; ?>" class="btn btn-danger" onclick="$('#frmaction').val('disable');$('#form-lookbook').submit();"><?php echo $button_disable; ?></button>
		</div>
      
      
    </div>
  </div>
  <div class="content">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-lookbook">
		  <input type="hidden" name="frmaction" id="frmaction" value=""  />
          
            <table class="list">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" value="" class="selectall"  /></td>
                  <td class="left"><?php echo $column_thumbnail; ?></td>
                  <td class="left"><?php echo $column_title; ?></td>
				  <td class="left"><?php echo $column_no_of_tags; ?></td>
				  <td class="left"><?php echo $column_status; ?></td>
                  <td class="right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($lookbooks) { ?>
                <?php foreach ($lookbooks as $lookbook) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($lookbook['lookbook_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $lookbook['lookbook_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $lookbook['lookbook_id']; ?>" />
                    <?php } ?></td>
                  <td class="left"><img src="../image/lookbook/<?php echo $lookbook['image_name']; ?>" style="width:100px;"  /></td>
                  <td class="right"><?php echo $lookbook['image_title']; ?></td>
				  <td class="right"><?php echo $lookbook['no_of_tags']; ?></td>
				  <td class="right"><?php echo $lookbook['status']; ?></td>
                  <td class="right">
				     <a href="<?php echo $lookbook['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><?php echo $button_edit; ?></a>
					 
					 
				  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          
        </form>
       
          <div class="pagination" style="padding-bottom: 6px;"><?php echo $pagination; ?></div>
          
      </div>
    </div>
  </div>

<?php echo $footer; ?>
{literal}
<script language="JavaScript">
$('.selectall').click(function() {
    if ($(this).is(':checked')) {
        $("input[name='selected[]']").attr('checked', true);
    } else {
        $("input[name='selected[]']").attr('checked', false);
    }
});
</script>
{/literal}