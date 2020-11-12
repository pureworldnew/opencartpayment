
<div  id="options_tbl">
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
        <?php if ($options) { ?>
        <?php foreach ($options as $option) { ?>
        <tr>
          <td class="text-left"><?php echo $option['name']; ?></td>
          <td class="text-left"><?php echo $option['path']; ?></td>
          <td class="text-right"><input type="text" class="seo_word form-control" onblur="updateSeoWord2('opt_select_<?php echo $option['option_id']; ?>','<?php echo $option['path']; ?>','<?php echo $option['option_id']; ?>','o');" id="seo_word_o<?php echo $option['option_id']; ?>" name="seo_word_o<?php echo $option['option_id']; ?>" data-path="<?php echo $option['path']; ?>" value="<?php echo $option['seo_word']; ?>" ></td>
          <td class="text-left"><?php if ($option['suggest']) { ?>
            <select class="form-control" id="opt_select_<?php echo $option['option_id']; ?>" onchange="$('#seo_word_o<?php echo $option['option_id']; ?>').attr('value', $( '#opt_select_<?php echo $option['option_id']; ?>' ).val());updateSeoWord('opt_select_<?php echo $option['option_id']; ?>','<?php echo $option['path']; ?>','<?php echo $option['option_id']; ?>','o'); " >
              <option value="">Select Suggestion</option>
              <?php foreach ($option['suggest'] as $value) {?>
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
  <div class="row option">
    <div class="col-sm-6 text-left"><?php echo $pagination_opt; ?></div>
    <div class="col-sm-6 text-right"><?php echo $results_opt; ?></div>
  </div>
  <script type="text/javascript">
		  $("div.option ul.pagination li" ).on( "click","a",  function( event ) {
				event.preventDefault();
				var url_link=$(this).attr("href");
				
				 $.ajax({
			       success:showResponseoptionpagination,
			        url: url_link,
					type: "GET",
					cache: true,
					error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		    }
		   });});
		   
		   
		  function showResponseoptionpagination(responseText, statusText, xhr)  { $('#options_tbl').fadeOut('slow', function(){$("#options_tbl").html(''); $("#options_tbl").replaceWith(responseText).fadeIn(2000); $('.fa-spin').remove();});}
	 </script> 
</div>
