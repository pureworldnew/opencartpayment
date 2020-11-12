<?php
/*
  Project: Ka Extensions
  Author : karapuz <support@ka-station.com>

  Version: 3 ($Revision: 27 $)

*/

/*
PARAMETERS:



  $name       - name of the select element;
  $options    - array of key-value pairs like this one:
								$options = arrray(
									'<key>' => '<value>'
								)
  $value      - selected option
  $first_line - insert y/n the first empty line to the select
  
  Example of usage:
  
  <?php 
  	KaElements::showSelector($name, $options, $value, $extra, $first_line)
  ?> 
*/

?>
<?php if (!empty($name)) { ?>
	<select name="<?php echo $name; ?>" <?php if (!empty($extra)) { echo $extra; } ?>>
		<?php if (!empty($first_line)) { ?>
		  <option value=""><?php echo $first_line; ?></option>
		<?php } ?> 
		<?php foreach ($options as $k => $v) { ?>
		  <option value="<?php echo $k;?>" <?php if ($value == $k) { ?> selected="selected" <?php } ?>><?php echo $v;?></option>
		<?php } ?>
	</select>

<?php } else { 

  if (isset($value) && isset($options[$value])) {
    echo $options[$value];
  } else {
    echo "Undefined";
  }
}

?>