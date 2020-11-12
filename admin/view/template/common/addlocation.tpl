<style type="text/css">
  .btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}
</style>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="container-fluid">
     <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> Add Location</h3>
      </div>
      <div class="panel-body">
      <div class="row">
        <div class="col-sm-6">
          <form method="POST" action="<?php echo $location_action ?>">
        <div class="form-group">
          <label>Location Name</label>
          <input type="text" name="loc_name" class="form-control"><br>
          <input type="submit" name="submit" value="submit" class="btn btn-info">
        </div>
      </form>
        </div>
       </div>
      </div>
    </div>

     <!--listing-->
      <div class="panel panel-default"> 
        <div class="panel-heading">Details</div>
        <div class="panel-body">
          <table class="table data-table dataTable mytbl">
            <thead>
              <tr>
                <th>Sr.No</th>
                <th>Location</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            <?php 
            $sr=1;
            foreach($locationlist as $row):
             ?>
              <tr>
                <td><?php echo $sr ?></td>
                <td><?php echo $row['location_name']; ?></td>
                <td><a href="<?php echo $edit_act ?>&exp=<?php echo $row['location_id']; ?>" class="btn btn-sm btn-info"><span class="fa fa-edit"></span></a>
                <a href="<?php echo $del_act ?>&exp=<?php echo $row['location_id']; ?>" class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></a>
                </td>
              </tr>
              <?php $sr++; ?>
            <?php endforeach; ?>  
            </tbody>
          </table>
        </div>
      </div>


  </div>
  </div>
<?php echo $footer; ?> 
<script type="text/javascript">


</script>
