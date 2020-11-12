<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
            	<a onclick="$('#form').submit();" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></a>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i></a>
                
            	
            </div>
                <h1><?php echo $heading_title; ?></h1>
     
        </div>
        	<?php if (isset($error_warning)) { ?>
          		<div class="warning"><?php echo $error_warning; ?></div>
          	<?php } ?>
          	<?php if (isset($success)) { ?>
          		<div class="success"><?php echo $success; ?></div>
         	<?php } ?>
      </div>
  
  <div class="container-fluid">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="table table-bordered table-hover">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_question_author; ?></td>
            <td><input type="text" name="q_author" value="<?php echo $q_author?>" />
              <?php if ($customer != "") { ?>
              <span style="padding-left:3em;"><?php echo $text_customer; ?> <a href="<?php echo $customer_link; ?>"><?php echo $customer; ?></a></span>
              <?php } ?>
              <?php if ($error_question_author) { ?>
              <span class="error"><?php echo $error_question_author; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_q_author_email; ?></td>
            <td><input type="text" name="q_author_email" value="<?php echo $q_author_email; ?>" />
              <?php if ($error_q_author_email) { ?>
              <span class="error"><?php echo $error_q_author_email; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_product; ?></td>
            <td><select id="category" style="margin-bottom: 5px;" onchange="getProducts();">
                <option value="0"><?php echo $text_select; ?></option>
                <?php foreach ($categories as $category) { ?>
                <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                <?php } ?>
              </select>
              <br />
              <select name="product_id" id="product">
                <?php if ($product) { ?>
                <option value="<?php echo $product_id; ?>"><?php echo $product; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $text_none; ?></option>
                <?php } ?>
              </select>
              <?php if ($error_product) { ?>
              <span class="error"><?php echo $error_product; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_question; ?></td>
            <td><textarea name="question" cols="60" rows="8"><?php echo $question; ?></textarea>
              <?php if ($error_question) { ?>
              <span class="error"><?php echo $error_question; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_language; ?></td>
            <td>
              <select name="language">
                <?php foreach ($languages as $language) { ?>
                <option value="<?php echo $language['code']; ?>"<?php echo ($language['code'] == $lang_code) ? ' selected' : ''; ?>><?php echo $language['name']; ?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_answer_author; ?></td>
            <td><input type="text" name="a_author" value="<?php echo $a_author?>" />
              <?php if ($error_answer_author) { ?>
              <span class="error"><?php echo $error_answer_author; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_answer; ?></td>
            <td><textarea name="answer" cols="60" rows="8"><?php echo $answer; ?></textarea>
              <?php if ($error_answer) { ?>
              <span class="error"><?php echo $error_answer; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_notify; ?></td>
            <td>
                <input type="checkbox" name="notify" value="1" <?php echo ($notify) ? 'checked="checked"': ''; ?>/>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
                <input type="hidden" name="lang_code" value="<?php echo $lang_code; ?>" />
                <input type="hidden" name="date_asked" value="<?php echo $date_asked; ?>" />
              </select></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function getProducts() {
	$('#product option').remove();

	$.ajax({
		url: 'index.php?route=catalog/qa/category&token=<?php echo $token; ?>&category_id=' + $('#category').val(),
		dataType: 'json',
		success: function(data) {
			for (i = 0; i < data.length; i++) {
	 			$('#product').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + '</option>');
			}
		}
	});
}
//--></script>
<?php echo $footer; ?>