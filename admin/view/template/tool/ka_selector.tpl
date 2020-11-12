<?php 
/*
  Project: CSV Product Import
  Author : karapuz <support@ka-station.com>

  Version: 3 ($Revision: 6 $)

*/

/*
  parameters:
    $data     - array of key-value pairs
    $name     - selector name
    $selected - selected key
    $extra    - additional parameters (like style) for the select element
*/
?>
<select name="<?php echo $name; ?>" <?php echo $extra; ?>>
<?php if (!empty($data)) { ?>
  <?php foreach($data as $dk => $dv) { ?>
    <option <?php if ($dk == $selected) { ?>selected="selected" <?php } ?>value="<?php echo $dk; ?>"><?php echo $dv; ?></option>
  <?php } ?>
<?php } ?>
</select>