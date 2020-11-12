<?php
	$template_viewer=340;
	$scale = 1;
?>
<style>
	.template_viewer {
		width:<?php echo $template_viewer*$scale; ?>px;
		height:<?php echo $template_viewer*$scale-(($template_viewer-$pageh)*$scale/2) ?>px;
		padding-top: <?php echo ($template_viewer-$pageh)/2*$scale ?>px;
	}
	.product_labels_page{
		background-color:#eee;
		width:<?php echo $pagew*$scale; ?>px;
		height:<?php echo $pageh*$scale; ?>px;
		box-shadow: 4px 4px 2px #cccccc;
		outline:1px solid #ddd;
		overflow:hidden;
		padding:0px;
	}
	.product_labels_page-margin {
		width:<?php echo $pagew*$scale; ?>px;
		overflow:hidden;
		padding-top:<?php echo $margint*$scale; ?>px;
		padding-left:<?php echo $marginl*$scale; ?>px;
	}
	.product_labels_label {
		float:left;
		width:<?php echo $labelw*$scale; ?>px;
		height:<?php echo $labelh*$scale; ?>px;
		margin-top:0px;
		margin-left:0px;
		margin-bottom:<?php echo $vspacing*$scale; ?>px;
		margin-right:<?php echo $hspacing*$scale; ?>px;
		border:1px solid #999;
		background-color:#fff;
		color: #999;
		-moz-border-radius: <?php echo $rounded*3; ?>px; border-radius: <?php echo $rounded*3*$scale; ?>px;
		vertical-align: middle;
		cursor: pointer;
	}
</style>
<div class="table-responsive">
	<table id="print_dialog_table" class="table table-bordered">
	  <thead>
	    <tr>
	      <td style="width:<?php echo $template_viewer; ?>;" class="text-left"><?php echo $text_pd_preview_label; ?>
	      <a href="index.php?route=module/product_labels&token=<?php echo $token ?>" style="float:right" class="btn btn-info btn-sm" id="edit_labels">Edit labels</a>
	      </td>
	      <td style="width:100%" class="text-left"><?php echo "Product options"; ?></td>
	    </tr>
	  </thead>
	  <tbody>
	  	<tr>
	      <td class="text-left" style="padding:15px;vertical-align:top;width:<?php echo $template_viewer; ?>;">
	      	<div class="col-xs-12">
				<div class="row" style="margin-bottom:10px;">
					<div class="col-xs-2 oc2-pl-label-input"  for="input-tag1"><LABEL>Label:</LABEL></div>
					<div class="col-xs-10 oc2-pl-label-input">
						<select class="templateinput form-control" id="pl_labelid" onchange="update_preview();">
						<?php foreach($labels as $id => $label_name) { ?>
							<option value="<?php echo $id ?>"<?php if($settings['product_labels_default_label'] == $id) echo " selected" ?>><?php echo $label_name; ?></option>
						<?php } ?>
						</select>
					</div>
				</div>
				<div class="row" style="margin-bottom:20px;">
					<div class="col-xs-12" style="padding:0px;">
						<div id="preview_label" style="border:1px solid #bbb;width:100%;height:<?php echo round($template_viewer/1.3); ?>px;"></div>
						<div id="debug"></div>
					</div>
				</div>
				<div class="row"  style="margin-bottom:5px;">
					<div class="col-xs-3 oc2-pl-label-input"><LABEL>Template:</LABEL></div>
					<div class="col-xs-5 oc2-pl-label-input">
						<select class="templateinput form-control" id="pl_templateid" onchange="get_template($(this).val())">
						<?php foreach($label_templates as $id => $label_template) { ?>
							<option value="<?php echo $id ?>"<?php if($settings['product_labels_default_template'] == $id) echo " selected" ?>><?php echo $label_template; ?></option>
						<?php } ?>
						</select>
					</div>
					<div class="col-xs-4 oc2-pl-label-input">
						<select class="templateinput form-control" id="pl_orientation" onchange="toggle_orientation();">
							<option value="P" <?php if($settings['product_labels_default_orientation'] == "P") echo " selected" ?>><?php echo $text_portrait; ?></option>
							<option value="L" <?php if($settings['product_labels_default_orientation'] == "L") echo " selected" ?>><?php echo $text_landscape; ?></option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12  oc2-pl-label-input">
					<center>
						<div class="template_viewer">
							<div class="product_labels_page">
								<div class="product_labels_page-margin">
									<?php for($i=1;$i<=$n;$i++) { ?>
									<div onclick='toggle_label("label<?php echo $i; ?>");' id="label<?php echo $i; ?>" class="product_labels_label"><small><?php echo $i; ?></small></div>
									<?php } ?>
								</div>
							</div>
						</div>
					</center>
				</div>
	      </td>
	      <td class="text-left" style="vertical-align:top;width:100%;">
	      	<button class="btn btn-info btn-sm" type="button" id="add_options">Add more labels</button>&nbsp;
			<button class="btn btn-info btn-sm" type="button" id="generate_label_options">Auto-generate all options</button>&nbsp;
			<button class="btn btn-info btn-sm" type="button" id="pl_submit_button" onclick="pl_submit_form();"> Print labels </button>
			<hr>
			<div id="product-label-options">

			</div>
	      </td>
	    </tr>
	  </tbody>
	</table>
</div>


<script type="text/javascript">
<!--

var row=0;
var nlabels = 0;
var token = '<?php echo $token ?>';
var scale=<?php echo $scale ?>;
var options_list = new Array();
var label_active = new Array();
var blanks = new Array();

<?php
	foreach ($product['options'] as $product_options) {
		if(isset($product_options['product_option_id']) && isset($product_options['product_option_value'])) {
?>
options_list[<?php echo $product_options['product_option_id'] ?>] = ['<?php echo join('\',\'',array_map(function($element){return $element['name'];}, $product_options['product_option_value'])) ?>'];
<?php
		}
	}
?>

for (i=1;i<=<?php echo $n; ?>;i++) {
	label_active['label'+i] = 1;
}

var page_width = <?php echo $pagew; ?>;
var page_height = <?php echo $pageh; ?>;
var label_width = <?php echo $labelw; ?>;
var label_height = <?php echo $labelh; ?>;
var number_h = <?php echo $numw; ?>;
var number_v = <?php echo $numh; ?>;
var space_h = <?php echo $hspacing; ?>;
var space_v = <?php echo $vspacing; ?>;
var rounded = <?php echo $rounded; ?>;
var margint = <?php echo $margint; ?>;
var marginl = <?php echo $marginl; ?>;
var orientation = "<?php echo $orientation; ?>";
var template_id = <?php echo $settings['product_labels_default_template']; ?>;
var label_id = <?php echo $settings['product_labels_default_label']; ?>;
var template_name = "";
var template_viewer = <?php echo $template_viewer ?>;
var product_id = <?php echo $product_id ?>;

function add_select_dropdown(n,r,opt_selected) {
	select_name  = '<select name="pl_options_name['+n+','+r+']" class="templateinput pl_serializable form-control" onchange="populate_opt_dropdown(\'pl_options_'+n+'_'+r+'\',$(this).val(),0,'+n+','+r+')"><option value=""></option>';
	<?php
		if(isset($product['options'])) {
			foreach ($product['options'] as $product_options) {
				if(isset($product_options['product_option_id']) && isset($product_options['product_option_value'])) { ?>
				select_name += '<option value="<?php echo $product_options['product_option_id'] ?>"';
				if(opt_selected == '<?php echo $product_options['product_option_id'] ?>') {
					select_name += ' selected="selected"';
				}
				select_name += '><?php echo $product_options['name'] ?></option>';<?php
				}
			}
		}
	?>
	select_name += '<option value="_c">Custom Text</option></select>';
	return select_name;
}

get_template(template_id);
update_preview();

//-->
</script>
