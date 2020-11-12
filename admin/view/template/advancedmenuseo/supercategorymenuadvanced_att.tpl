<div  id="attributes_tbl">
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <td class="text-left"><?php echo $column_name; ?></td>
          <td class="text-left"><?php echo $path_name; ?></td>
          <td class="text-right"><?php echo $seo_word; ?></td>
          <td class="text-left"><?php echo $suggest; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php if ($attributes) { ?>
        <?php foreach ($attributes as $attribute) { ?>
        <tr>
          <td class="text-left"><?php echo $attribute['name']; ?></td>
          <td class="text-left"><?php echo $attribute['path']; ?></td>
          <td class="text-right"><input type="text" class="seo_word" onblur="updateSeoWord2('att_select_<?php echo $attribute['attribute_id']; ?>','<?php echo $attribute['path']; ?>','<?php echo $attribute['attribute_id']; ?>','a');" id="seo_word_a<?php echo $attribute['attribute_id']; ?>" name="seo_word_a<?php echo $attribute['attribute_id']; ?>" data-path="<?php echo $attribute['path']; ?>" value="<?php echo $attribute['seo_word']; ?>" ></td>
          <td class="text-left"><?php if ($attribute['suggest']) { ?>
            <select id="att_select_<?php echo $attribute['attribute_id']; ?>" onchange="$('#seo_word_a<?php echo $attribute['attribute_id']; ?>').attr('value', $( '#att_select_<?php echo $attribute['attribute_id']; ?>' ).val());updateSeoWord('att_select_<?php echo $attribute['attribute_id']; ?>','<?php echo $attribute['path']; ?>','<?php echo $attribute['attribute_id']; ?>','a'); " >
              <option value="">Select Suggestion</option>
              <?php foreach ($attribute['suggest'] as $value) {?>
              <option value="<?php  echo $value; ?>">
              <?php  echo $value; ?>
              </option>
              <?php } ?>
            </select>
            <?php } ?></td>
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
  <div class="row attribute">
    <div class="col-sm-6 text-left"><?php echo $pagination_att; ?></div>
    <div class="col-sm-6 text-right"><?php echo $results_att; ?></div>
  </div>
  <script type="text/javascript">
		  $("div.attribute ul.pagination li" ).on( "click","a",  function( event ) {
				event.preventDefault();
				var url_link=$(this).attr("href");
				
				 $.ajax({
			       success:showResponseAttributepagination,
			        url: url_link,
					type: "GET",
					cache: true,
					error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		    }
		   });});
		   
		   
		  function showResponseAttributepagination(responseText, statusText, xhr)  { $('#attributes_tbl').fadeOut('slow', function(){$("#attributes_tbl").html(''); $("#attributes_tbl").replaceWith(responseText).fadeIn(2000); $('.fa-spin').remove();});}
	 </script> 
</div>
