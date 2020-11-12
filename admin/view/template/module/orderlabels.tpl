<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="table-responsive">
        <table class="table">
          <tr>
            <th>
              File #
            </th>
            <th>
              Action
            </th>
          </tr>
          <?php foreach($pdf_files as $pdf_file){
             ?>
          <tr>
            <td><a href="<?php echo $pdf_file; ?>" target="_blank"><?php echo $pdf_file; ?></a></td>
            <td><a href="<?php echo $pdf_file; ?>">Download</a></td>
          </tr>
             <?php
           } ?>
        </table>
      </div>
  </div>
  </div>
</div>
<?php echo $footer; ?>