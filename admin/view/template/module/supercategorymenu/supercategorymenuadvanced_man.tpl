<?php 






if ($manufacturers) {?>

<div class="panel-group" id="accordion2<?php echo $store['store_id']; ?>">
  <?php $i=0;      

  foreach ($manufacturers['str'.$store['store_id']] as $manufacturer) { ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion2<?php echo $store['store_id']; ?>" href="#collapse_m<?php echo $i; ?><?php echo $store['store_id']; ?>"><?php echo $manufacturer['name']; ?></a></h4>
    </div>
    <div id="collapse_m<?php echo $i; ?><?php echo $store['store_id']; ?>" class="panel-collapse collapse">
      <div class="panel-body">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left"><?php echo $column_namem; ?></td>
              <td class="text-Letf"><?php echo $column_action; ?></td>
              <td class="text-right"><?php echo $entry_delete_cache_att; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($manufacturer['submanufacturer'] as $man) { ?>
            <tr id="M<?php echo $man['manufacturer_id']; ?>_<?php echo $store['store_id']; ?>">
              <td class="text-left"><?php echo $man['name']; ?></td>
              <td class="text-left"><a class="edit_manu" href="javascript:void(0)" store_id="<?php echo $store['store_id']; ?>" manufacturer_id="<?php echo $man['manufacturer_id']; ?>" ><?php echo $entry_select_att; ?> <?php echo $man['name']; ?></a></td>
              <td class="text-right"><a class="delete btn btn-danger" href="index.php?route=module/supercategorymenuadvanced/DeleteCacheManufacturer&token=<?php echo $token; ?>&store_id=<?php echo $store['store_id']; ?>&manufacturer_id=<?php echo $man['manufacturer_id']; ?>"><i class="fa fa-times"></i>&nbsp;<?php echo $entry_delete_cache_att; ?></a></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php $i++;  

                  } ?>
</div>
<div class="row" id="manufacturer_id_s<?php echo $store['store_id']; ?>" >
  <div class="col-sm-6 text-left"><?php echo ${"pagination_man".$store['store_id']}; ?></div>
  <div class="col-sm-6 text-right"><?php echo ${"results_man".$store['store_id']}; ?></div>
</div>
<?php } ?>
<script type="text/javascript"><!--

        

   $("div#manufacturer_id_s<?php echo $store; ?> ul.pagination li ").on( "click","a",  function( event ) {

		event.preventDefault();

		var hreflink = $(this).attr("href");

	    $('#tab-manufacturer_id_s<?php echo $store; ?>').load(hreflink);

		 });



//--></script> 

<script type="text/javascript"><!--



	

	

	function AjaxManufactures(id,store){        

		$.ajax({

			success:showResponseManufactures,  // post-submit callback 

			url: 'index.php?route=module/supercategorymenuadvanced/GetAllValuesManufacturer',

			data: 'token=<?php echo $token; ?>&manufacturer_id='+id+'&store_id='+store,

			type: "GET",

			cache: true,

			error: function(xhr, ajaxOptions, thrownError) {

			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

		    }



		});

	};



	function showResponseManufactures(responseText, statusText, xhr)  { $('#valores_nuevos_manu').fadeOut('slow', function(){ $("#valores_nuevos_manu").html(''); $("#valores_nuevos_manu").replaceWith(responseText).fadeIn(2000); $('.fa-spin').remove();});}

	

	

	$('a.edit_manu').click(function() {

		

		var  id= $(this).attr("manufacturer_id"); 	var store =$(this).attr("store_id"); 

		$(this).after('<i class="fa fa-cog fa-spin"></i>');

		$('#tr_nueva_m').remove();

		html='<tr id="tr_nueva_m"><td class="center" colspan="7"><div id="valores_nuevos_manu"></div></td></tr>';

		$('tr#M'+id+"_"+store).after(html);

		AjaxManufactures(id,store);

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
