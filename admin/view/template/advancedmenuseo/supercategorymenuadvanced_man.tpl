<div  id="manufacturer_tbl">
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
                  <?php if ($manufacturers) { ?>
                    <?php foreach ($manufacturers as $manufacturer) { ?>
                    <tr>
                      <td class="text-left"><?php echo $manufacturer['name']; ?></td>
                      <td class="text-left"><?php echo $manufacturer['path']; ?></td>
                      <td class="text-right"><input type="text" class="seo_word form-control"  onblur="updateSeoWord2('man_select_<?php echo $manufacturer['manufacturer_id']; ?>','<?php echo $manufacturer['path']; ?>','<?php echo $manufacturer['manufacturer_id']; ?>','m');" id="seo_word_m<?php echo $manufacturer['manufacturer_id']; ?>" name="seo_word_m<?php echo $manufacturer['manufacturer_id']; ?>" data-path="<?php echo $manufacturer['path']; ?>" value="<?php echo $manufacturer['seo_word']; ?>" /></td>
                    <td class="text-left"><?php if ($manufacturer['suggest']) { ?>
                      <select class="form-control"  id="man_select_<?php echo $manufacturer['manufacturer_id']; ?>" onchange="$('#seo_word_m<?php echo $manufacturer['manufacturer_id']; ?>').attr('value', $( '#man_select_<?php echo $manufacturer['manufacturer_id']; ?>' ).val());updateSeoWord('man_select_<?php echo $manufacturer['manufacturer_id']; ?>','<?php echo $manufacturer['path']; ?>','<?php echo $manufacturer['manufacturer_id']; ?>','m'); " >
                      <option value="">Select Suggestion</option>
                      <?php foreach ($manufacturer['suggest'] as $value) {?>
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
  <div class="row manufacturer">
    <div class="col-sm-6 text-left"><?php echo $pagination_man; ?></div>
    <div class="col-sm-6 text-right"><?php echo $results_man; ?></div>
  </div>
  <script type="text/javascript">
		  $("div.manufacturer ul.pagination li" ).on( "click","a",  function( event ) {
				event.preventDefault();
				var url_link=$(this).attr("href");
				
				 $.ajax({
			       success:showResponsemanufacturerpagination,
			        url: url_link,
					type: "GET",
					cache: true,
					error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		    }
		   });});
		   
		   
		  function showResponsemanufacturerpagination(responseText, statusText, xhr)  { $('#manufacturer_tbl').fadeOut('slow', function(){$("#manufacturer_tbl").html(''); $("#manufacturer_tbl").replaceWith(responseText).fadeIn(2000); $('.fa-spin').remove();});}
	 </script> 
</div>
