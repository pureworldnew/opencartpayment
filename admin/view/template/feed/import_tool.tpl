<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
    
    <?php if ($error_warning) {?>
        <div class="warning"><?php echo $error_warning;?></div>
    <?php }?>
        
        <div class="container-fluid">
            <form action="<?php echo $action;?>" method="post" enctype="multipart/form-data" id="form">
                <table class="table table-bordered table-hover">
                    <tr>
                        <td><?php echo $entry_status;?></td>
                        <td><select name="import_tool_status">
                                <?php if ($import_tool_status) {?>
                                    <option value="1" selected="selected"><?php echo $text_enabled;?></option>
                                    <option value="0"><?php echo $text_disabled;?></option>
                                <?php } else {?>
                                    <option value="1"><?php echo $text_enabled;?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled;?></option>
                                <?php }?>
                            </select>
                        </td>
                    </tr>
                </table>
            </form>
            <?php /*<h2>Update Options</h2>
            <form action="<?php echo $action_import;?>" method="post" enctype="multipart/form-data" id="form_import">
                <table class="form">
                    <tr>
                        <td>
                            <label for="file"><?php echo $text_file;?></label>
                        </td>
                        <td>
                            <input type="file" name="file" id="file">
                        </td>
                        <td>
                            <input type="submit" value="<?php echo $button_import;?>">
                        </td>
                    </tr>
                    <label><b>Headings for CSV => </b></label><span style="margin-left: 20px;">id; Options; Combination_Reference; Supplier_Reference; Ean13; upc; wholesale_price; price; ecotax; quantity; weight; combination_default</span>
                </table>
            </form> */ ?>
            <h2>Update And Add Units</h2>
            <form action="<?php echo $action_import_units;?>" method="post" enctype="multipart/form-data" id="form_import_units">
                <table class="form">
                    <tr>
                        <td>
                            <label for="file"><?php echo $text_file2;?></label>
                        </td>
                        <td>
                            <input type="file" name="file" id="file2">
                        </td>
                        <td>
                            <input type="submit" value="<?php echo $button_import;?>">
                        </td>
                    </tr>
                    <label><b>Headings for CSV => </b></label><span style="margin-left: 20px;">Id_Product; Product_Name; Name; Measure; Convert_units; Position</span>
                </table>
            </form>
            <?php /* <h2>Update And Add Attributes</h2>
            <form action="<?php echo $action_import_attribute; ?>" method="post" enctype="multipart/form-data" id="form_import_attribute">
                <table class="form">
                    <tr>
                        <td>
                            <label for="file"><?php echo $text_file3;?></label>
                        </td>
                        <td>
                            <input type="file" name="file" id="file3">
                        </td>
                        <td>
                            <input type="submit" value="<?php echo $button_import;?>">
                        </td>
                    </tr>
                    <label><b>Headings for CSV => </b></label><span style="margin-left: 20px;">Product ID; Product Name; Reference; Supplier Reference; Feature: Bead Type; Feature: Cleanness (1-10); Feature: Color; Feature: Country of Origin; Feature: Depth; Feature: Earring Back Type; Feature: Feature 1; Feature: Feature 2; Feature: Finish; Feature: Graduated Size; Feature: Height; Feature: Hole Size; Feature: Jump Ring; Feature: Jump Ring Type; Feature: Length; Feature: Luster (1-10); Feature: Material; Feature: Metal Color; Feature: Metal Hardness; Feature: Metal Treatment; Feature: Metal Type; Feature: Number of Rings; Feature: Pearl Type; Feature: Shape; Feature: Smooth; Feature: Stone Color; Feature: Stone Cutting; Feature: Stone Quality; Feature: Stone Type; Feature: Strand Length; Feature: Swarovski Color; Feature: Swarovski Shape Code; Feature: Texture; Feature: Uniformity (1-10); Feature: Weight; Feature: Width; Feature: Width (Inches); Feature: Wire Gauge</span>
                </table>
            </form> */ ?>
        </div>
        
</div>
<?php echo $footer;?>