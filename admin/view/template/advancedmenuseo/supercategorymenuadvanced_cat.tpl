<div id="category_tbl">
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
        <?php if ($categories) { ?>
        <?php foreach ($categories as $category) { ?>
        <tr>
          <td class="text-left"><?php echo $category['name']; ?></td>
          <td class="text-left"><?php echo $category['path']; ?></td>
          <td class="text-right"><input type="text" class="seo_word form-control" onblur="updateSeoWord2('cat_select_<?php echo $category['category_id']; ?>','<?php echo $category['path']; ?>','<?php echo $category['category_id']; ?>','c');" id="seo_word_c<?php echo $category['category_id']; ?>" name="seo_word_c<?php echo $category['category_id']; ?>" data-path="<?php echo $category['path']; ?>" value="<?php echo $category['seo_word']; ?>" /></td>
          <td class="text-left"><?php if ($category['suggest']) { ?>
            <select class="form-control" id="cat_select_<?php echo $category['category_id']; ?>" onchange="$('#seo_word_c<?php echo $category['category_id']; ?>').attr('value', $( '#cat_select_<?php echo $category['category_id']; ?>' ).val());updateSeoWord('cat_select_<?php echo $category['category_id']; ?>','<?php echo $category['path']; ?>','<?php echo $category['category_id']; ?>','c'); " >
              <option value="">Select Suggestion</option>
              <?php foreach ($category['suggest'] as $value) {?>
              <option value="<?php  echo $value; ?>">
              <?php  echo $value; ?>
              </option>
              <?php } ?>
            </select>
            .
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
  <div class="row category">
    <div class="col-sm-6 text-left"><?php echo $pagination_cat; ?></div>
    <div class="col-sm-6 text-right"><?php echo $results_cat; ?></div>
  </div>
  <script type="text/javascript">
		  $("div.category ul.pagination li" ).on( "click","a",  function( event ) {
				event.preventDefault();
				var url_link=$(this).attr("href");
				
				 $.ajax({
			       success:showResponseCategoypagination,
			        url: url_link,
					type: "GET",
					cache: true,
					error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		    }
		   });});
		   
		   
		  function showResponseCategoypagination(responseText, statusText, xhr)  { $('#category_tbl').fadeOut('slow', function(){$("#category_tbl").html(''); $("#category_tbl").replaceWith(responseText).fadeIn(2000); $('.fa-spin').remove();});}
	 </script> 
</div>
