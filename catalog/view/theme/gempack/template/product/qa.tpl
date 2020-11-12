<div class="ignitras">
  <?php if ($qas) { ?>
    <?php foreach ($qas as $qa) { ?>
      <table class="table table-striped table-bordered">
        <tr>
          <td style="width: 40%;"><strong><?php echo ucfirst($qa['q_author']); ?></strong></td>
          <td class="text-right"><?php echo $qa['date_asked']; ?></td>
        </tr>
        <tr>
          <td class="background_td">
            <?php if($qa['image']){ ?>
              <img src="<?php echo $qa['image']; ?>" alt="<?php echo $qa['name']; ?>" style="width:150px;" />
            <?php } else { ?> 
              <img src="<?php echo HTTP_SERVER; ?>catalog/view/theme/default/image/no_product.jpg" style="width:150px;" />
            <?php } ?>
            <div class="pull-right">
              <a href="<?php echo $qa['href']; ?>"><?php echo $qa['name']; ?> </a><br><?php echo ' - ' . $qa['sku'] . '<br />'; ?>
              <?php if ($qa['product_options']) { ?>
                <?php foreach ($qa['product_options'] as $key => $product_option) { ?>
                    <span><?php echo '- ' . $key . ' : ' . $product_option . '<br />'; ?></span>
                <?php } ?>
              <?php } ?>
            </div>
          </td>
          <td>
            <p><strong>Question: </strong><?php echo $qa['question']; ?></p>
            <p><strong>Answer: </strong><?php echo $qa['answer']; ?></p>
          </td>
        </tr>
      </table>
    <?php } ?>
    <div class="pagination"><?php echo $pagination; ?></div>
  <?php } else { ?>
    <div class="content"><?php echo $text_no_questions; ?></div>
  <?php } ?>
</div>