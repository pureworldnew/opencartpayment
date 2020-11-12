<div class="ignitras">
<?php if ($qas) { ?>
<?php foreach ($qas as $qa) { ?>
<div class="content">
  <b><?php echo $text_question; ?></b> <?php echo $qa['question']; ?>
  <span style="float:right"><?php echo ($qa_question_author || $qa_question_date) ? "(" : ""; ?>
  <?php if ($qa_question_author) { ?>
  <b><?php echo $qa['q_author']; ?></b><?php echo ($qa_question_date) ? ", " : ""; ?>
  <?php } ?>
  <?php if ($qa_question_date) { ?>
  <?php echo $qa['date_asked']; ?>
  <?php } ?>
  <?php echo ($qa_question_author || $qa_question_date) ? ")" : ""; ?>
  </span>
  <br />
  <br />
  <b><?php echo $text_answer; ?></b> <?php echo ($qa['answer'] != '') ? $qa['answer'] : "<i>" . $text_no_answer . "</i>" ; ?>
  <?php if ($qa['answer'] != '') { ?>
  <span style="float:right"><?php echo (($qa_answer_author && $qa['a_author'] != '') || $qa_answer_date) ? "(" : ""; ?>
  <?php if ($qa_answer_author && $qa['a_author'] != '') { ?>
  <b><?php echo $qa['a_author']; ?></b><?php echo ($qa_answer_date) ? ", " : ""; ?>
  <?php } ?>
  <?php if ($qa_answer_date) { ?>
  <?php echo $qa['date_answered']; ?>
  <?php } ?>
  <?php echo (($qa_answer_author && $qa['a_author'] != '') || $qa_answer_date) ? ")" : ""; ?>
  </span>
  <?php } ?>
  </div>
<?php } ?>
<div class="pagination"><?php echo $pagination; ?></div>
<?php } else { ?>
<div class="content"><?php echo $text_no_questions; ?></div>
<?php } ?>
</div>
