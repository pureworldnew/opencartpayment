<?php 
/*
  Project: CSV Product Import
  Author : karapuz <support@ka-station.com>

  Version: 3 ($Revision: 62 $)

*/

echo $header;

?>

<style type="text/css">
<!--

span.important_note {
  color: red;
  font-weight: normal;
}

div.scroll {
  height: 200px;
  width: 100%;
  overflow: auto;
  border: 1px solid black;
  background-color: #ccc;
  padding: 8px;
}

span.note {
  font-weight: bold;
}

.list td a.link {
  text-decoration: underline;
  color: blue;
}

#import_status {
  color: black;
}

-->
</style>


<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>

  <?php if (!empty($ka_top_messages)) { ?>
    <?php foreach ($ka_top_messages as $top_message) { ?>
      <?php if ($top_message['type'] == 'E') { ?>
      <div class="warning"><?php echo $top_message['content']; ?></div>
      <?php } else { ?>
      <div class="success"><?php echo $top_message['content']; ?></div>
      <?php } ?>
    <?php } ?>
  <?php } ?>

  <div class="box">

    <?php if (!empty($is_wrong_db)) { ?>

      Database is not compatible with the extension. Please re-install the extension on the 'Product Feeds' page.
  
    <?php } elseif ($params['step'] == 1) { ?>

    <div class="heading">
      <h1><img src="view/image/backup.png" alt="" /> <?php echo $heading_title; ?>: STEP 1 of 3</h1>
      <div class="buttons">
        <a onclick="$('#form').submit();" class="button"><span>Next</span></a>
      </div>
    </div>
    <div class="content">

      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <input type="hidden" name="mode" value="" />
        <table class="form">
          <tr>
            <td colspan="3">
            This page allows you to import the data from a file in <a href="http://en.wikipedia.org/wiki/Comma-separated_values" target="_blank">CSV</a> format. <br /><br />
            
            <span class="important_note">It is recommended to create <a href="<?php echo $backup_link; ?>" target="_blank">a database backup</a> before starting the import procedure
            because these chagnes are irreversible.</span>
            </td>
          </tr>

          <tr>
            <td width="25%">Profile</td>
            <td>
              <?php if (!empty($profiles)) { ?>
                <?php echo $this->showSelector("profile_id", $profiles, $params['profile_id']); ?>

                <input type="button" value="Load" onclick="javascript: loadProfile();" />
                <input type="button" value="Delete" onclick="javascript: deleteProfile();" />
               <?php } else { ?>
                no profiles present
               <?php } ?>
            </td>
            <td width="50%"><span class="help">Profiles may store import parameters to simlify management of different import configurations. 
            You can save import parameters to a profile on the next step</td>
          </tr>
        </table>
        
      <div id="tabs" class="htabs">
        <a href="#tab-general">General</a>
        <a href="#tab-downloads">Downloads</a>
        <a href="#tab-extra">Extra</a>
      </div>   
      
      <div id="tab-general">
        <table class="form">          
          <tr>
            <td width="25%">Product Update Mode</td>
            <td>
              <select name="update_mode" style="width: 300px">
                <option <?php if ($params['update_mode'] == 'add') { ?>selected="selected" <?php } ?>value="add">Add new records (safe) </option>
                <option <?php if ($params['update_mode'] == 'replace') { ?>selected="selected" <?php } ?>value="replace">Replace old records </option>
              </select>
            </td>
            <td width="50%"><span class="help">In the 'add' mode all related information is just added to the product. In the 'Replace' mode old information related to the product is deleted first. For example, the 'Replace' mode is useful for updating special prices and discounts.</span></td>
          </tr>

          <tr>
            <td width="25%">Field Delimiter</td>
            <td>
              <select name="delimiter" style="width: 300px">
                <option <?php if ($params['delimiter'] == 't') { ?>selected="selected" <?php } ?>value="t">tab</option>
                <option <?php if ($params['delimiter'] == 's') { ?>selected="selected" <?php } ?>value="s">semicolon ";"</option>
                <option <?php if ($params['delimiter'] == 'c') { ?>selected="selected" <?php } ?>value="c">comma ","</option>
              </select>
            </td>
            <td width="50%">&nbsp;</td>
          </tr>

          <tr>
            <td width="25%">File Charset</td>
            <td colspan="2">
              <input type="hidden" id="charset_option" name="charset_option" value="<?php echo $params['charset_option']; ?>" />
              <table width="600px">

                <tr id="predefined_charset_row" <?php if ($params['charset_option'] != 'predefined') { ?> style="display:none" <?php } ?>>
                  <td width="280px">
                    <?php echo $this->showSelector("charset", $charsets, $params['charset'], 'style="width:300px;"'); ?>
                  </td>
                  <td><a href="javascript: void(0);" onclick="javascript: activateCharset('custom');">make editable</a></td>
                </tr>

                <tr id="custom_charset_row" <?php if ($params['charset_option'] == 'predefined') { ?> style="display:none" <?php } ?>>
                  <td width="250px">
                    <input type="text" style="width: 290px" id="custom_charset" name="custom_charset" value="<?php echo $params['charset']; ?>" />
                  </td>
                  <td><a href="javascript: void(0);" onclick="javascript: activateCharset('predefined');">select from predefined values</a></td>
                </tr>

              </table>
              <span class="help">You have to be aware of the import file charset. Use ISO-8859-1 if your data consists of Latin characters only.</span>
            </td>
          </tr>

          <tr>
            <td width="25%">File Location</td>
            <td>
              <input type="radio" name="location" value="local" onclick="javascript: activateLocation('local');" <?php if ($params['location'] == 'local') { ?> checked="checked" <?php } ?> />Local computer
              <input type="radio" name="location" value="server" onclick="javascript: activateLocation('server');" <?php if ($params['location'] == 'server') { ?> checked="checked" <?php } ?> />Server
            </td>
            <td width="50%">&nbsp;</td>
          </tr>

          <tr id="local_location" <?php if ($params['location'] != 'local') { ?>style="display:none" <?php } ?>>
            <td width="25%">File</td>
            <td><input type="file" name="file" size="40" /> <br />
              <span class="help">Max. file size: <?php echo $max_file_size; ?></span>
            </td>
            <td width="50%"><span class="help">If your file exceeds the max. file size limit then upload the file manually to the store 'temp' directory and specify the path.</span></td>
          </tr>

          <tr id="server_location" <?php if ($params['location'] != 'server') { ?>style="display:none" <?php } ?>>
            <td width="25%">File path</td>
            <td nowrap="nowrap" colspan="2"><?php echo $store_root_dir . DIRECTORY_SEPARATOR; ?><input type="text" name="file_path" size="50" value="<?php echo $params['file_path']; ?>" />
            <br />
            <input type="checkbox" name="rename_file" value="Y" <?php if (!empty($params['rename_file'])) { ?> checked="checked" <?php } ?> />
            Rename the file after successful import
            </td>
          </tr>

          <tr>
            <td width="25%">Sub-Category Separator</td>
            <td>
              <input type="text" name="cat_separator" maxlength="8" size="8" value="<?php echo $params['cat_separator']; ?>" />
            </td>
            <td width="50%"><span class="help">It is a sub-category separator. A separator of multiple product categories can be defined on the "<a href="<?php echo $settings_page;?>" target="settings_page">extension settings</a>" page.</span></td>
          </tr>

          <tr>
            <td width="25%">Path to Images Directory</td>
            <td colspan="2"><?php echo $store_images_dir . DIRECTORY_SEPARATOR; ?>
              <input type="text" name="images_dir" value="<?php echo $params['images_dir']?>" />
              <span class="help">File names must consist of Latin characters only. Files with national characters in names will not be imported.</span>
            </td>
          </tr>

          <tr>
            <td width="25%">Incoming Images Directory</td>
            <td colspan="2">
              <?php if ($params['image_urls_allowed']) { ?>
                <?php echo $store_images_dir . DIRECTORY_SEPARATOR; ?>
                <input type="text" name="incoming_images_dir" value="<?php echo $params['incoming_images_dir']?>" />
                <span class="help"><span class="note">Important:</span> Images provided as URLs will be downloaded to your server and it may dramatically decrease speed of the import. Avoid using URLs in the import as long as you can.
                </span>               
              <?php } else { ?>
                URLs are not allowed due to server configuration settings (curl library not found and allow_url_fopen=false).
              <?php } ?>
            </td>
          </tr>

          <tr>
            <td width="25%">Language</td>
            <td>
              <select name="language_id" style="width: 300px">
                <?php foreach ($languages as $language) { ?>
                  <option value="<?php echo $language['language_id']; ?>" <?php if ($language['language_id'] == $params['language_id']) { ?>selected="selected"<?php } ?>><?php echo $language['name']; ?></option>
                <?php } ?>
              </select>
            </td>
            <td width="50%">&nbsp;</td>
          </tr>
          
          <tr>
            <td width="25%">Store</td>
            <td>              
              <select name="store_ids[]" multiple="multiple" size="5" style="width: 300px">
                <?php foreach($stores as $store) { ?>
                  <option <?php if (in_array($store['store_id'], $params['store_ids'])) { ?>selected="selected" <?php } ?>value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                <?php } ?>
              </select>
            </td>
            <td width="50%">&nbsp;</td>
          </tr>


          <tr>
            <td width="25%">Default Category</td>
            <td colspan="2">
              <select name="default_category_id">
                <?php foreach($categories as $category) { ?>
                  <option <?php if ($category['category_id'] == $params['default_category_id']) { ?>selected="selected" <?php } ?>value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                <?php } ?>
              </select>
              <span class="help">New products will be placed into this category if another one is not specified in the file.</span>
            </td>            
          </tr>
        </table>
      </div>

      <div id="tab-extra">
      
        <table class="form">
          <tr>
            <td width="25%">Price Multiplier</td>
            <td>
              <input type="text" name="price_multiplier" maxlength="8" size="8" value="<?php echo $params['price_multiplier']; ?>" />
            </td>
            <td width="50%"><span class="help">Regular product price multiplier (leave empty or set to 1 if the price should not be updated)</span></td>
          </tr>
          <tr>
            <td width="25%">Disable store products not presented in the file</td>
            <td>
              <input type="checkbox" name="disable_not_imported_products" value="Y" <?php if (!empty($params['disable_not_imported_products'])) { ?> checked="checked" <?php } ?> />
            </td>
            <td width="50%">&nbsp;</td>
          </tr>
          <tr>
            <td width="25%">Do not create new products</td>
            <td>
              <input type="checkbox" name="skip_new_products" value="N" <?php if (!empty($params['skip_new_products'])) { ?> checked="checked" <?php } ?> />
            </td>
            <td width="50%">&nbsp;</td>
          </tr>
        </table>
        
      </div>

      <div id="tab-downloads">      
        <table class="form">
          <tr>
            <td width="25%">Path to Source Directory</td>
            <td colspan="2"><?php echo $store_root_dir . DIRECTORY_SEPARATOR; ?>
              <input type="text" name="download_source_dir" value="<?php echo $params['download_source_dir']?>" />
              <span class="help">File names must consist of Latin characters only. Files with national characters in names will not be imported.</span>
            </td>
          </tr>
          <tr>
            <td width="25%">Where to Get File Postfix</td>
            <td>
              <select name="file_name_postfix" style="width: 300px">
                <option <?php if ($params['file_name_postfix'] == 'generate') { ?>selected="selected" <?php } ?>value="generate">Generate Random Postfixes</option>
                <option <?php if ($params['file_name_postfix'] == 'detect') { ?>selected="selected" <?php } ?>value="detect">Detect Postfixes in File Names</option>
                <option <?php if ($params['file_name_postfix'] == 'skip') { ?>selected="selected" <?php } ?>value="skip">Do Not Use Postfixes</option>
              </select>
            </td>
            <td width="50%">&nbsp;</td>
          </tr>
        </table>        
      </div>
              
      </form>
    </div>

<script type="text/javascript"><!--

$(document).ready(function() {
  $('#tabs a').tabs();
});

function activateLocation(id) {
  if (id == 'server') {
    $('#local_location').hide();
    $('#server_location').show();
  } else if (id == 'local') {
    $('#local_location').show();
    $('#server_location').hide();
  }
}


function activateCharset(id) {
  if (id == 'predefined') {
    $('#predefined_charset_row').show();
    $('#custom_charset_row').hide();
    $('#charset_option').val('predefined');

  } else if (id == 'custom') {
    $('#predefined_charset_row').hide();
    $('#custom_charset_row').show();
    $('#charset_option').val('custom');
  }
}


function loadProfile() {

  $("#form input[name='mode']").attr('value', 'load_profile');
  $("#form").submit();
}


function deleteProfile() {

  $("#form input[name='mode']").attr('value', 'delete_profile');
  $("#form").submit();
}

//--></script> 

    <?php } elseif ($params['step'] == 2) { ?>

    <div class="heading">
      <h1><img src="view/image/backup.png" alt="" /> <?php echo $heading_title; ?>: STEP 2 of 3</h1>
      <div class="buttons">
        <a onclick="location='<?php echo $back_action; ?>'" class="button"><span><?php echo "Back"; ?></span></a>
        <a onclick="$('#form').submit();" class="button"><span>Next</span></a>    
      </div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <input type="hidden" name="mode" value="" />

        <table style="padding-bottom: 10px">
          <tr>
            <td width="100px">Profile</td>
            <td width="150px">
              <input type="hidden" name="profile_id" value="<?php echo $params['profile_id']; ?>" />
              <input type="text" name="profile_name" value="<?php echo $params['profile_name']; ?>" style="width: 90%" />
            </td>
            <td width="50px">
              <input type="button" value="Save" onclick="javascript: saveProfile();" />
            </td>
            <td width="100px">
            </td>
            <td width="200px">
              File size: <?php echo $filesize; ?>
            </td>
          </tr>
        </table>
    
      <div id="tabs" class="htabs">
        <a href="#tab-general">General</a>
        <a href="#tab-attributes">Attributes</a>
        <?php if ($filters_enabled) { ?>
          <a href="#tab-filters">Filters</a>
        <?php } ?>
        <a href="#tab-options">Options</a>
        <a href="#tab-discounts">Discounts</a>
        <a href="#tab-specials">Specials</a>
        <a href="#tab-reward_points">Reward Points</a>
        <?php if ($product_profiles_enabled) { ?>
          <a href="#tab-product_profiles">Product Profiles</a>
        <?php } ?>
      </div>
      
        <div id="tab-general">
          Match the product fields with columns from your file. 
          Some fields may be selected already but please verify all data before starting
          the import.<br /><br />

        <table class="list">

          <thead>
            <tr>
              <td class="left" width="25%">Product Field</td>
              <td>Column in File</td>
              <td width="50%">Notes</td>
            </tr>
          </thead>

          <tbody>
          <?php foreach($fields as $fk => $fv) { ?>
            <tr>
              <td width="25%"><?php echo $fv['name'] ?>
                <?php if (!empty($fv['required'])) { ?><span class="required">*</span><?php } ?>
              </td>
              <td>
                <?php 
                  $val = (isset($params['matches']['fields'][$fk]['column'])) ? $params['matches']['fields'][$fk]['column']:0;
                  echo $this->showSelector("fields[$fv[field]]", $columns, $val); 
                ?>
              </td>
              <td width="50%"><span class="help"><?php echo $fv['descr']; ?></span></td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
        </div>

        <div id="tab-attributes">
        Only attributes declared in the store will be imported. Create new attributes <a href="<?php echo $attribute_page_url; ?>">here</a><br /><br />

        <table class="list">
          <thead>
            <tr>
              <td class="left" width="25%">Atribute Name</td>
              <td>Column in File</td>
              <td width="50%">Attribute Group</td>
            </tr>
          </thead>

          <tbody>
          <?php foreach($params['matches']['attributes'] as $ak => $av) { ?>
            <tr>
              <td width="25%"><?php echo $av['name'] ?></td>
              <td>
                <?php echo $this->showSelector("attributes[$av[attribute_id]]", $columns, $params['matches']['attributes'][$ak]['column']); ?>
              </td>
              <td width="50%"><?php echo $av['attribute_group']; ?></td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
        </div>

        <?php if ($filters_enabled) { ?>
          <div id="tab-filters">
          Available filter groups are listed below. You can create new filter groups <a href="<?php echo $filter_page_url; ?>">here</a><br /><br />

          <table class="list">
            <thead>
              <tr>
                <td class="left" width="25%">Filter Group</td>
                <td>Column in File</td>
                <td width="50%">Notes</td>
              </tr>
            </thead>

            <tbody>
            <?php foreach($params['matches']['filter_groups'] as $fk => $fv) { ?>
              <tr>
                <td width="25%"><?php echo $fv['name'] ?></td>
                <td>
                  <?php echo $this->showSelector("filter_groups[$fv[filter_group_id]]", $columns, $params['matches']['filter_groups'][$fk]['column']); ?>
                </td>
                <td width="50%">&nbsp;</td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
          </div>
        <?php } ?>
        
        <div id="tab-options">
        
        There are two option formats available for importing the options. The <b>simple format</b> is used when your options
        have option values only without any extended data like quantity, price, weight, etc. Both formats can be combined in single import.
        
        <br /><br />
        
        <div id="option_tabs" class="htabs">
          <a href="#otab-simple_format">Simple format</a>
          <a href="#otab-extended_format">Extended format</a>
        </div>

      <div id="otab-simple_format">

        Select the columns containg simple option values (without weight, price, etc.). New options should be created beforehand at <a href="<?php echo $option_page_url; ?>">the options page</a>.
        You can import multiple values from one cell if you define the 'options separataor' on the extension settings page. <a href="javascript: void(0)" onclick="javascript: $('#simple_options_example').show()">See example</a>
        <div id="simple_options_example" style="display:none; color: green">
        <br />If you define the '|' character as a separator then you can define option values like shown below:<br />
        <br />Header line :...,Size,...
        <br />Product line:...,"S|M|L|XL",...
        <br /><br />
        The cell will add 4 option values to the product.
        
        </div>
        <br /><br />
        <table class="list">
          <thead>
            <tr>
              <td class="left" width="25%">Option Name</td>
              <td>Column in File</td>
              <td width="5%">Required</td>
              <td width="5%">Type</td>
            </tr>
          </thead>

          <tbody>
          <?php foreach($params['matches']['options'] as $ok => $ov) { ?>
            <tr>
              <td width="25%"><?php echo $ov['name'] ?></td>
              <td>
                <?php echo $this->showSelector("options[$ov[option_id]]", $columns, $params['matches']['options'][$ok]['column']); ?>
              </td>
              <td width="5%"><input type="checkbox" name="required_options[<?php echo $ov['option_id'];?>]" value="Y" <?php if (!empty($params['matches']['options'][$ok]['required'])) { ?> checked="checked" <?php } ?> /></td>
              <td width="45%"><?php echo $ov['type']; ?></td>
            </tr>
          <?php } ?>

        </table>
      </div>        

      <div id="otab-extended_format">

        If you need to import extra option properties like weight, price, quantity please use <a href="javascript: void(0)" onclick="javascript: $('#option_format').show()">reserved columns</a>. Options from <b>the resered columns</b> are pre-selected automatically.<br />

        <div id="option_format" style="display:none">
          <pre>
          COLUMN           | DESCRIPTION
          -----------------------------------------------------------------------------
          option:name      | option name (required)
          option:type      | option type (required)
          option:value     | option value (required)
          option:required  | option is required or not (Y/1 - yes, N/0 - no) (optional)
          option:image     | file path or URL (optional)
          option:sort_order| sort order of option value (optional)

          The following fields define options with extra attributes.

          option:quantity  | quantity (optional)
          option:subtract  | subtract flag (optional)
          option:price     | price (the value can be negative)
          option:points    | points (the value can be negative)
          option:weight    | weight (the value can be negative)
          </pre>
        </div>

        <br />

        <table class="list">
          <thead>
            <tr>
              <td class="left" width="25%">Atribute Name</td>
              <td>Column in File</td>
              <td width="50%">Notes</td>
            </tr>
          </thead>

          <tbody>

          <?php foreach($params['matches']['ext_options'] as $dk => $dv) { ?>
            <tr>
              <td width="25%"><?php echo $dv['name'] ?></td>
              <td>
                <?php echo $this->showSelector("ext_options[$dv[field]]", $columns, $params['matches']['ext_options'][$dk]['column']); ?>
              </td>
              <td width="50%"><span class="help"><?php echo $dv['descr']; ?></span></td>
            </tr>
          <?php } ?>

        </table>
      </div>
        
        </div>
        

        <div id="tab-discounts">

Product Discounts. You should specify at least 'quantity' and 'price' values to add new discount records.<br /><br />

        <table class="list">
          <thead>
            <tr>
              <td class="left" width="25%">Atribute Name</td>
              <td>Column in File</td>
              <td width="50%">Notes</td>
            </tr>
          </thead>

          <tbody>

          <?php foreach($discounts as $dk => $dv) { ?>
            <tr>
              <td width="25%"><?php echo $dv['name'] ?></td>
              <td>
                <?php echo $this->showSelector("discounts[$dv[field]]", $columns, $params['matches']['discounts'][$dk]['column']); ?>
              </td>
              <td width="50%"><span class="help"><?php echo $dv['descr']; ?></span></td>
            </tr>
          <?php } ?>

        </table>
        </div>
    
        <div id="tab-specials">

Product Special Prices. You should specify at least 'price' value to add new special price records.<br /><br />

        <table class="list">
          <thead>
            <tr>
              <td class="left" width="25%">Atribute Name</td>
              <td>Column in File</td>
              <td width="50%">Notes</td>
            </tr>
          </thead>

          <tbody>

          <?php foreach($specials as $dk => $dv) { ?>           
            <tr>
              <td width="25%"><?php echo $dv['name'] ?></td>
              <td>
                <?php echo $this->showSelector("specials[$dv[field]]", $columns, $params['matches']['specials'][$dk]['column']); ?>
              </td>
              <td width="50%"><span class="help"><?php echo $dv['descr']; ?></span></td>
            </tr>
          <?php } ?>

        </table>
        </div>

        <div id="tab-reward_points">

Product Reward Points.<br /><br />

        <table class="list">
          <thead>
            <tr>
              <td class="left" width="25%">Atribute Name</td>
              <td>Column in File</td>
              <td width="50%">Notes</td>
            </tr>
          </thead>

          <tbody>

          <?php foreach($reward_points as $dk => $dv) { ?>            
            <tr>
              <td width="25%"><?php echo $dv['name'] ?></td>
              <td>
                <?php echo $this->showSelector("reward_points[$dv[field]]", $columns, $params['matches']['reward_points'][$dk]['column']); ?>
              </td>
              <td width="50%"><span class="help"><?php echo $dv['descr']; ?></span></td>
            </tr>
          <?php } ?>

        </table>
        </div>

        <?php if ($product_profiles_enabled) { ?>        
          <div id="tab-product_profiles">

  Product Profiles (for recurring billing).<br /><br />

          <table class="list">
            <thead>
              <tr>
                <td class="left" width="25%">Name</td>
                <td>Column in File</td>
                <td width="50%">Notes</td>
              </tr>
            </thead>

            <tbody>

            <?php foreach($product_profiles as $dk => $dv) { ?>            
              <tr>
                <td width="25%"><?php echo $dv['name'] ?></td>
                <td>
                  <?php echo $this->showSelector("product_profiles[$dv[field]]", $columns, $params['matches']['product_profiles'][$dk]['column']); ?>
                </td>
                <td width="50%"><span class="help"><?php echo $dv['descr']; ?></span></td>
              </tr>
            <?php } ?>

          </table>
          </div>
        <?php } ?>
        
    </div>

<script type="text/javascript"><!--

$(document).ready(function() {
  $('#tabs a').tabs();
  $('#option_tabs a').tabs();
});

function saveProfile() {

  $("#form input[name='mode']").attr('value', 'save_profile');
  $("#form").submit();
}

//--></script> 

    <?php } elseif ($params['step'] == 3) { ?>

    <div class="heading">
      <h1><img src="view/image/backup.png" alt="" /> <?php echo $heading_title; ?>: STEP 3 of 3</h1>

      <div class="buttons" id="buttons_in_progress">
        <a onclick="javascript: ka_stop_import();" class="button"><span>Stop</span></a>
      </div>
      <div class="buttons" id="buttons_stopped" style="display: none">
        <a onclick="javascript: ka_continue_import();" class="button"><span>Continue</span></a>
      </div>
      <div class="buttons" id="buttons_completed" style="display: none">
        <a onclick="location='<?php echo $done_action; ?>'" class="button"><span>Done</span></a>
      </div>
    </div>
    <div class="content">

        <h2 id="import_status">Import is in progress</h2>
        <table class="form">
          <tr>
            <td colspan="2">The import statistics updates every <? echo $update_interval; ?> seconds. Please do not close the window.</td>
          </tr>
          <tr>
            <td width="25%">Completion at</td>
            <td id="completion_at">0%</td>
          </tr>
          <tr>
            <td width="25%">Time Passed</td>
            <td id="time_passed">0</td>
          </tr>
          <tr>
            <td width="25%">Lines Processed</td>
            <td id="lines_processed">0</td>
          </tr>
          <tr>
            <td width="25%">Products Created</td>
            <td id="products_created">0</td>
          </tr>
          <tr>
            <td width="25%">Products Updated</td>
            <td id="products_updated">0</td>
          </tr>
          <tr>
            <td width="25%">Products Deleted</td>
            <td id="products_deleted">0</td>
          </tr>
          <tr>
            <td width="25%">Categories Created</td>
            <td id="categories_created">0</td>
          </tr>

          <tr>
            <td colspan="2">
              <h4>Import messages:</h4>
              <div class="scroll" id="scroll">
              </div>
              <input type="checkbox" id="autoscroll" checked="checked" /> Autoscrolling
            </td>
          </tr>
        
        
        </table>
    </div>


<script type="text/javascript"><!--

var ka_page_url = '<?php echo $page_url; ?>';
var ka_timer    = null;

/*
  possible statuses
    not_started -
    in_progress -
    completed   -
    temp_error  -
    fatal_error -
*/
var ka_import_status = 'not_started';

/*
  possible ajax statuses:
    not_started -
    in_progress -
*/
var ka_ajax_status   = 'not_started';

function ka_update_interface(status) {
  $("#buttons_in_progress").hide();
  $("#buttons_completed").hide();
  $("#buttons_stopped").hide();

  if (status == 'fatal_error') {
    $("#import_status").html("Server Script Error. Please check the error logs");
    $("#buttons_completed").show();

  } else if (status == 'error') {
    $("#import_status").html("Fatal Import Error. Please see the import messages box");
    $("#buttons_completed").show();

  } else if (status == 'stopped') {
    $("#import_status").html("Import stopped");
    $("#buttons_stopped").show();

  } else if (status == 'completed') {
    $("#buttons_completed").show();
    $("#import_status").html("Import is complete!");
  
  } else if (status == 'in_progress') {
    $("#import_status").html('Import is in progress');
    $("#buttons_in_progress").show();
  }
}


function ka_stop_import() {
  ka_import_status = 'fatal_error';
  $("#import_status").html('Import has been stopped');
  ka_update_interface('stopped');
}


function ka_continue_import() {
  ka_import_status = 'in_progress';
  ka_update_interface('in_progress');
}


function ka_ajax_error(jqXHR, textStatus, errorThrown) {
  ka_import_status = 'temp_error';

  if ($.inArray(textStatus, ['abort', 'parseerror', 'error'])) {
    ka_import_status = 'fatal_error';

    if (jqXHR.status == '200') {
      ka_add_message("Server error (status=200). Details:" + jqXHR.responseText);
    } else {
      ka_add_message("Server error (status=" + jqXHR.status + ").");
    }
    ka_update_interface('stopped');
  } else {
    ka_add_message('Temporary connection problems.');
  }

  ka_ajax_status = 'not_started';
}


function ka_ajax_success(data, textStatus, jqXHR) {

  if (!data) {

    ka_import_status = 'fatal_error';
    ka_update_interface('fatal_error');

  } else {
    if (data['messages']) {
      for (i in data['messages']) {
        ka_add_message(data['messages'][i]);
      }
    }

    $("#completion_at").html(data['completion_at']);
    $("#lines_processed").html(data['lines_processed']);
    $("#products_created").html(data['products_created']);
    $("#products_updated").html(data['products_updated']);
    $("#products_deleted").html(data['products_deleted']);
    $("#categories_created").html(data['categories_created']);
    $("#time_passed").html(data['time_passed']);

    if (data['status'] == 'error') {
      ka_import_status = 'fatal_error';
      ka_update_interface('error');

    } else if (data['status'] == 'completed') {
      ka_import_status = 'completed';
      ka_update_interface('completed');
    }
  }

  ka_ajax_status = 'not_started';
}


function ka_add_message(msg) {
  var dt       = new Date();
  var log_time = "[" + dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds() + "] ";
  $("#scroll").append(log_time + msg + "<br />");

  if ($("#autoscroll").attr("checked")) {
    $("#scroll").scrollTop(999999);
  }
}

var ka_dots     = 0;
var status_text = '';

function ka_import_loop() {

  if ($.inArray(ka_import_status, ['fatal_error', 'completed']) >= 0) {
    return;
  }

  if ($.inArray(ka_import_status, ['in_progress']) >= 0) {

    // show animation

    if (ka_dots == 0) {
      status_text = "Import is in progress";
    } else {
      status_text = status_text + '.';
    }
    if (ka_dots++ > 5)
      ka_dots = 0;
    $("#import_status").html(status_text);
  }

  if ($.inArray(ka_ajax_status, ['not_started']) >= 0) {
    ka_ajax_status = 'in_progress';
    $.ajax({
      url: ka_page_url,
      dataType: 'json',
      cache : false,
      success: ka_ajax_success,
      error: ka_ajax_error
    });
  }
}

  
$(document).ready(function() {
  ka_import_status = 'in_progress';
  ka_timer = setInterval('ka_import_loop()', 750);
});

//--></script> 

    <?php } ?>

  </div>

  <span class="help">'CSV Product Import' extension developed by <a href="mailto:support@ka-station.com?subject=CSV Product Import">karapuz</a></span>
</div>

<?php echo $footer; ?>