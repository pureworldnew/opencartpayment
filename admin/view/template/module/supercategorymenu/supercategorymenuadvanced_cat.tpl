<?php if ($categories) {?>

<div class="panel-group" id="accordion<?php echo $store; ?>">
  <?php $i=0; foreach ($categories['str'.$store] as $category) { ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion<?php echo $store; ?>" href="#collapse_c<?php echo $i; ?><?php echo $store; ?>"><?php echo $category['name']; ?></a></h4>
    </div>
    <div id="collapse_c<?php echo $i; ?><?php echo $store; ?>" class="panel-collapse collapse">
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left"><?php echo $column_name; ?></td>
                <td class="text-Letf"><?php echo $column_action; ?></td>
                <td class="text-right"><?php echo $entry_delete_cache_att; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($category['subcategories'] as $cat) { ?>
              <tr id="<?php echo $cat['category_id']; ?>_<?php echo $store; ?>">
                <td class="text-left"><?php echo $cat['name']; ?></td>
                <td class="text-left"><a class="edit" href="javascript:void(0)" store_id="<?php echo $store; ?>" category_id="<?php echo $cat['category_id']; ?>" ><?php echo $entry_select_att; ?> <?php echo $cat['name']; ?></a></td>
                <td class="text-right"><a class="delete btn btn-danger" href="index.php?route=module/supercategorymenuadvanced/DeleteCache&token=<?php echo $token; ?>&store_id=<?php echo $store; ?>&category_id=<?php echo $cat['category_id']; ?>"><i class="fa fa-times"></i>&nbsp;<?php echo $entry_delete_cache_att; ?></a></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php $i++;  

                  } ?>
</div>
<div class="row" id="category_id_s<?php echo $store; ?>" >
<div class="col-sm-6 text-left"><?php echo ${"pagination_cat".$store}; ?></div>
<div class="col-sm-6 text-right"><?php echo ${"results_cat".$store}; ?></div>
<?php } ?>
<script type="text/javascript"><!--

        

  $("div#category_id_s<?php echo $store; ?> ul.pagination li ").on( "click","a",  function( event ) {

    event.preventDefault();

	var hreflink = $(this).attr("href");

	$('#tab-category_id_s<?php echo $store; ?>').load(hreflink);

  });

//--></script> <script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--



	function AjaxCategories(id,store){        

		$.ajax({

			success:showResponseAttributes,

			url: 'index.php?route=module/supercategorymenuadvanced/GetAllValues',

			data: 'token=<?php echo $token; ?>&category_id='+id+'&store_id='+store,

			type: "GET",

			cache: true,

			error: function(xhr, ajaxOptions, thrownError) {

			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

		    }

      });};

	





		function showResponseAttributes(responseText, statusText, xhr)  { $('#valores_nuevos').fadeOut('slow', function(){$("#valores_nuevos").html(''); $("#valores_nuevos").replaceWith(responseText).fadeIn(2000); $('.fa-spin').remove();});}

	

	$('a.edit').click(function() {

	  

		var id = $(this).attr("category_id"); var store =$(this).attr("store_id"); 

		$(this).after('<i class="fa fa-cog fa-spin"></i>');

		$('#tr_nueva').remove();

		html='<tr id="tr_nueva"><td class="center" colspan="7"><div id="valores_nuevos"></div></td></tr>';

		$('tr#'+id+"_"+store).after(html);

		AjaxCategories(id,store);

		return false;		

	});

	



	



<?php if ($error_warning) { ?>

   $("#notification").html('<div class="warning" style="display: none;"><?php echo $error_warning; ?></div>');

   $(".warning").fadeIn("slow");

   $('.warning').delay(2500).fadeOut('slow');

   $("html, body").animate({

         scrollTop: 0

     }, "slow")

<?php } ?>

<?php if ($success) { ?>

 $("#notification").html('<div class="success" style="display: none;"><?php echo $success; ?></div>');

   $(".success").fadeIn("slow");

   $('.success').delay(2500).fadeOut('slow');

   $("html, body").animate({

      scrollTop: 0

   }, "slow")

<?php } ?>







//--></script> 
