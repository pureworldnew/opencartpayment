<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
            	<a href="<?php echo $insert; ?>" data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="$('form').submit();"><?php echo $button_delete; ?></button>
                
            	
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
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_product; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_product; ?>"><?php echo $column_product; ?></a>
                <?php } ?></td>
              <td class="text-left"><?php if ($sort == 'qa.q_author') { ?>
                <a href="<?php echo $sort_question_author; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_question_author; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_question_author; ?>"><?php echo $column_question_author; ?></a>
                <?php } ?></td>
              <td class="text-left"><?php if ($sort == 'qa.q_author_email') { ?>
                <a href="<?php echo $sort_q_author_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_q_author_email; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_q_author_email; ?>"><?php echo $column_q_author_email; ?></a>
                <?php } ?></td>
              <td class="text-left"><?php if ($sort == 'qa.date_asked') { ?>
                <a href="<?php echo $sort_date_asked; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_asked; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_asked; ?>"><?php echo $column_date_asked; ?></a>
                <?php } ?></td>
              <td class="text-left"><?php if ($sort == 'qa.a_author') { ?>
                <a href="<?php echo $sort_answer_author; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_answer_author; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_answer_author; ?>"><?php echo $column_answer_author; ?></a>
                <?php } ?></td>
              <td class="text-left"><?php if ($sort == 'qa.answered') { ?>
                <a href="<?php echo $sort_answered; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_answered; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_answered; ?>"><?php echo $column_answered; ?></a>
                <?php } ?></td>
              <td class="text-left"><?php if ($sort == 'qa.date_answered') { ?>
                <a href="<?php echo $sort_date_answered; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_answered; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_answered; ?>"><?php echo $column_date_answered; ?></a>
                <?php } ?></td>
              <td class="text-left"><?php if ($sort == 'l.name') { ?>
                <a href="<?php echo $sort_language; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_language; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_language; ?>"><?php echo $column_language; ?></a>
                <?php } ?></td>
              <td class="text-left"><?php if ($sort == 'qa.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
              <td class="text-right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($qas) { ?>
            <?php foreach ($qas as $qa) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($qa['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $qa['qa_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $qa['qa_id']; ?>" />
                <?php } ?></td>
              <td class="text-left"><?php echo $qa['name']; ?></td>
              <td class="text-left">
              <?php if ($qa['customer_link'] != "") { ?>
              <a href="<?php echo $qa['customer_link']; ?>"><?php echo $qa['q_author']; ?></a>
              <?php } else { ?>
              <?php echo $qa['q_author']; ?>
              <?php } ?>
              </td>
              <td class="text-left"><?php echo $qa['q_author_email']; ?></td>
              <td class="text-left"><?php echo $qa['date_asked']; ?></td>
              <td class="text-left"><?php echo $qa['a_author']; ?></td>
              <td class="text-left"><?php echo $qa['answered']; ?></td>
              <td class="text-left"><?php echo $qa['date_answered']; ?></td>
              <td class="text-left"><?php echo $qa['language']; ?></td>
              <td class="text-left"><?php echo $qa['status']; ?></td>
              <td class="text-right"><?php foreach ($qa['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="10"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>