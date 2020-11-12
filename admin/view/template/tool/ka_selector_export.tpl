<?php 
/*
  Project : CSV Product Export
  Author  : karapuz <support@ka-station.com>

  Version : 3.2 ($Revision: 4 $)

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
<?php foreach($data as $dk => $dv) { ?>
  <option <?php if ($dk == $selected) { ?>selected="selected" <?php } ?>value="<?php echo $dk; ?>"><?php echo $dv; ?></option>
<?php } ?>
</select>