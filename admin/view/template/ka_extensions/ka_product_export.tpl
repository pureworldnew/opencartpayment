<?php
/*
  Project: CSV Product Export
  Author : Karapuz <support@ka-station.com>

  Version: 4 ($Revision: 34 $)

*/
?>
<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">

  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-input" data-toggle="tooltip" title="<?php echo $this->t('button_save'); ?>" class="btn btn-primary"><i class="fa fa-check-circle"></i> <?php echo $this->t('button_save'); ?></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $this->t('button_cancel'); ?>" class="btn btn-default"><i class="fa fa-reply"></i>  <?php echo $this->t('button_cancel'); ?></a>
      </div>
      <h1><i class="fa fa-puzzle-piece"></i> <?php echo $heading_title; ?></h1>
      <?php echo $ka_breadcrumbs; ?>
    </div>
  </div>


	<div class="container-fluid">
		<?php echo $ka_top; ?>
  
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-input">
    
      <div id="service_line">
      <table>
        <tr>
          <td><b>Full Version</b>: <?php echo $extension_version; ?>&nbsp;&nbsp;&nbsp;</td>
          <td><b>Author</b>: karapuz&nbsp;&nbsp;&nbsp;</td>
          <td><b>Contact Us</b>: <a href="mailto:support@ka-station.com">via email</a>&nbsp;&nbsp;&nbsp;</td>
          <td><a href="https://www.ka-station.com/index.php?route=information/contact" target="_blank">via secure form at www.ka-station.com</a>&nbsp;&nbsp;&nbsp;</td>
        </tr>
      </table>
      </div>
      
      <br />
      There is a direct link to access the export page <a href="<?php echo $export_page; ?>">CSV Product Export</a>.
      <br /><br />
    
      <table class="table table-striped table-bordered table-hover">
        <thead> 
          <tr>
            <td style="width: 360px" class="left">Setting</td>
            <td class="text-left">Value</td>
          </tr>
        </thead>
        <tbody>

          <tr>
            <td class="left">Script update interval in seconds (5-25)<span class="help">Reduce this value if you experience server connection issues during the export. Default value is 10.</span></td>
            <td class="left">
              <input type="text" name="ka_pe_update_interval" value="<?php echo $ka_pe_update_interval; ?>" />
            </td>
          </tr>

          <tr>
            <td class="left">Provide a direct download link to the generated file</span></td>
            <td class="left">
              <input type="checkbox" name="ka_pe_direct_download" value="Y" <?php if ($ka_pe_direct_download == 'Y') { ?> checked="checked" <?php }; ?> />
            </td>
          </tr>

          <tr>
            <td class="left">Add space before values to prevent automatic conversion in MS Excel on opening</td>
            <td class="left">
              <input type="checkbox" name="ka_pe_prefix_with_space" value="Y" <?php if ($ka_pe_prefix_with_space == 'Y') { ?> checked="checked" <?php }; ?> />
            </td>
          </tr>

          <tr>
            <td class="left">Enable 'product ID' column in the export</td>
            <td class="left">
              <input type="checkbox" name="ka_pe_enable_product_id" value="Y" <?php if ($ka_pe_enable_product_id == 'Y') { ?> checked="checked" <?php }; ?> />
            </td>
          </tr>

          <tr>
            <td class="left">General separator for multiple values in one cell</span></td>
            <td class="left">
              <input type="text" name="ka_pe_general_sep" value="<?php echo $ka_pe_general_sep; ?>" />              
            </td>
          </tr>

          <tr>
            <td class="left">Export all categories in one cell</td>
            <td class="left">
              <input type="checkbox" name="ka_pe_cats_in_one_cell" value="Y" <?php if ($ka_pe_cats_in_one_cell == 'Y') { ?> checked="checked" <?php }; ?> />
            </td>
          </tr>
          
          <tr>
            <td class="left">Export all related products in one cell</td>
            <td class="left">
              <input type="checkbox" name="ka_pe_related_in_one_cell" value="Y" <?php if ($ka_pe_related_in_one_cell == 'Y') { ?> checked="checked" <?php }; ?> />
            </td>
          </tr>

          <tr>
            <td class="left">Export additional images in one cell</td>
            <td class="left">
              <input type="checkbox" name="ka_pe_images_in_one_cell" value="Y" <?php if ($ka_pe_images_in_one_cell == 'Y') { ?> checked="checked" <?php }; ?> />
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>

<script type="text/javascript"><!--

//--></script>

<?php echo $footer; ?>