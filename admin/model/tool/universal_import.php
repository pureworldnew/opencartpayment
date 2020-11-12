<?php
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

function obuiErrorHandler($errno, $errstr, $errfile, $errline, array $errcontext) {
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }
    
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}

class ModelToolUniversalImport extends Model {
  private $simulation = true;
  private $line_decay = 1;
  
  private $processed = array(
    'processed' => 0,
    'inserted' => 0,
    'updated' => 0,
    'deleted' => 0,
    'skipped' => 0,
    'error' => 0,
  );
  
  private $pre_processed = false;
  private $file;
  private $filetype;
  private $xml_node;
  private $csv_separator;
  private $xfn_multiple_separator = array();
  private $OC_V2;
  private $OC_V21;
  private $OC_V22;
  private $token;
  private $order_statuses = array();
    
  public function pre_process($config) {
    // extra function handling before populate
    if ($this->pre_processed) return;
    
    // disable config options
    if (!empty($config['disable_cfg'])) {
      $toDisable = explode(',', $config['disable_cfg']);
      
      foreach ($toDisable as $cfgOption) {
        $this->config->set(trim($cfgOption), false);
      }
    }
    
    // extra functions
    if (isset($config['extra_func'])) {
      foreach ($config['extra_func'] as $extra_funcs) {
        foreach ($extra_funcs as $func_name => $func_values) {
          if ($func_name == 'multiple_separator') {
            $this->xfn_multiple_separator[$func_values['field']] = $func_values['value'];
          }
        }
      }
    }
    
    $this->pre_processed = true;
  }
  
  public function process($config, $limit = null) {
    if (isset($this->session->data['user_token'])) {
      $this->token = 'user_token='.$this->session->data['user_token'];
    } else if (isset($this->session->data['token'])) {
      $this->token = 'token='.$this->session->data['token'];
    }
    
    if (!$limit) {
      $limit = 200;
      
      if ((int) $this->config->get('gkd_impexp_batch_imp') > 0) {
        $limit = (int) $this->config->get('gkd_impexp_batch_imp');
      }
    }
    
    $this->OC_V2 = version_compare(VERSION, '2', '>=');
    $this->OC_V21 = version_compare(VERSION, '2.1', '>=');
    $this->OC_V22 = version_compare(VERSION, '2.2', '>=');
    
    if (defined('GKD_CRON')) {
      $this->simulation = false;
    } else if ($this->user->hasPermission('modify', 'module/universal_import')) {
      $this->simulation = !empty($config['simulation']);
    } else {
      $this->simulation = $config['simulation'] = true;
    }
    
    $simu_row = $this->simulation ? 'simu' : 'rows';
    
    $this->language->load('module/universal_import');
    
    set_error_handler('obuiErrorHandler');
    //file_put_contents(DIR_SYSTEM . 'logs/import.log', $res . "\n", FILE_APPEND | LOCK_EX);
    
    $this->filetype = !empty($config['import_filetype']) ? $config['import_filetype'] : strtolower(pathinfo($config['import_file'], PATHINFO_EXTENSION));
    
    if ($this->filetype == 'csv') {
      $this->csv_separator = !empty($config['csv_separator']) ? $config['csv_separator'] : ',';
    } else if ($this->filetype == 'xml') {
      $this->xml_node = $config['xml_node'];
    }
    
    $type = $config['import_type'];
    
    if (!in_array($type, array('product', 'product_update', 'order_status_update', 'category', 'information', 'manufacturer', 'customer'))) {
      die('Invalid type');
    }
    
    if (in_array($type, array('product', 'product_update', 'category', 'information', 'manufacturer'))) {
      $this->load->model('catalog/'.str_replace('_update', '', $type));
    } else if ($type == 'customer') {
      $this->load->model(($this->OC_V21 ? 'customer':'sale').'/customer');
    } else if ($type == 'order_status_update') {
      $this->load->model('sale/order');
      $this->load->model('gkd_import/order');
    }
    
    $this->session->data['obui_errors'] = array();
    $this->session->data['obui_log'] = array();
    
    // first init
    if (empty($this->session->data['obui_current_line'])) {
      // delete all items or init id array
      if (!empty($config['delete'])) {
        if ($config['delete'] == 'all' || $config['delete'] == 'batch') {
          $this->delete($config);
          $this->session->data['obui_current_line'] = 'preproc';
          return 1;
        } else {
          $this->session->data['obui_no_delete'] = array();
          $this->session->data['obui_delete_brand'] = array();
        }
      }
    } else if ($this->session->data['obui_current_line'] == 'preproc') {
      $this->session->data['obui_current_line'] = 0;
    }
    
    if (!empty($this->session->data['univimport_temp_file'])) {
      $config['import_file'] = $this->session->data['univimport_temp_file'];
    } else if ($config['import_source'] == 'upload') {
      $config['import_file'] = DIR_CACHE.'universal_import/'.str_replace(array('../', '..\\'), '', $config['import_file']);
    } else if ($config['import_source'] == 'ftp') {
      $config['import_file'] = $config['import_ftp'].$config['import_file'];
    }
    
    $this->file = $this->loadFile($config['import_file'], $config['import_filetype']);
    
    $first_row = $this->initFilePosition($this->file, $config);
    
    $usleep = $this->config->get('gkd_impexp_sleep');
    
    if (!empty($config['csv_header'])) {
      $this->line_decay = 2;
    }

    if ($this->file) {
      $i = 0;
      
      while ($i < $limit && ($line = $this->getNextRow($this->file))) {
        $i++;
        if ($first_row) {
          $first_row = false;
          continue;
        }
        
        // skip empty line
        if (!count(array_filter((array) $line))) {
          $this->session->data['obui_processed']['processed']++;
          $this->session->data['obui_processed']['skipped']++;
          
          if (defined('GKD_CRON')) {
            $this->cron_log($this->session->data['obui_current_line'] . ' - ' . $this->language->get('text_'.$simu_row.'_skipped') . ' - ' . $this->language->get('text_empty_line_skip'));
          } else {
            $this->session->data['obui_log'][] = array(
              'row' => $this->session->data['obui_current_line'],
              'status' => 'skipped',
              'title' => $this->language->get('text_'.$simu_row.'_skipped'),
              'msg' =>  $this->language->get('text_empty_line_skip'),
            );
          }
          
          continue;
        }
        
        try {
          if ($usleep) {
            usleep((int) $usleep * 1000); // 1 000 000 = 1s
          }
          
          $res = $this->{'process_' . $type}($config, $line);
          if (defined('GKD_CRON')) {
            $this->cron_log($this->session->data['obui_current_line'] . ' - ' . $this->language->get('text_'.$simu_row.'_'.$res['row_status']) . ' - ' . (!empty($res['row_msg']) ? strip_tags($res['row_msg']) : ''));
          } else {
            $this->session->data['obui_log'][] = array(
              'row' => $this->session->data['obui_current_line'],
              'status' => $res['row_status'],
              'title' => $this->language->get('text_'.$simu_row.'_'.$res['row_status']),
              'msg' => !empty($res['row_msg']) ? $res['row_msg'] : '',
            );
          }
        } catch (Exception $e) {
          isset($this->session->data['obui_processed']['processed']) && $this->session->data['obui_processed']['processed']++;
          isset($this->session->data['obui_processed']['processed']) && $this->session->data['obui_processed']['error']++;
          //$this->session->data['obui_errors'][] = $e->getMessage() . ' - line: ' . $e->getLine() . ' - file: ' . $e->getFile();
          
          $extraErrorInfo = '';
          
          // extra info about error
          if (strpos($e->getMessage(), 'Undefined index:') !== false) {
            preg_match('/Undefined index: (.*)$/', $e->getMessage(), $forCustomField);
            $extraErrorInfo = '<br/>This error is generally because you have some custom module that tries to insert some data into the database<br/>Try to set in Step 3 > Extra functions > Custom fields a custom field named "<b>'.$forCustomField[1].'</b>"';
          }
          
          if (defined('GKD_CRON')) {
            $this->cron_log($this->session->data['obui_current_line'] . ' - ' . $this->language->get('text_simu_error') . ' - ' . $e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine());
          } else {
            $this->session->data['obui_log'][] = array(
              'row' => $this->session->data['obui_current_line'],
              'status' => 'error',
              'title' => $this->language->get('text_simu_error'),
              'msg' => $e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine() . $extraErrorInfo,
            );
          }
        }
      }
      
      if ($this->filetype == 'csv') {
        fclose($this->file);
      }
    } else {
      // error opening the file.
    }
    
    restore_error_handler();
    
    return 1;
  }
  
  public function delete($config) {
    $type = $this->db->escape($config['import_type']);
    $mode = $config['delete'];
    $action = $config['delete_action'];
    
    $this->simulation = !empty($config['simulation']);
    
    $simu_row = $this->simulation ? 'simu' : 'rows';
    
    $deleted_array = array();
    
    $where = '';
      
    if (!$mode) {
      return;
    }
    
    if ($mode == 'missing' && !empty($this->session->data['obui_no_delete'])) {
      $where = " WHERE " . $type . "_id NOT IN (" . implode(',', $this->session->data['obui_no_delete']) . ")";
    } else if ($mode == 'batch') {
      $where = " WHERE `import_batch` = '" . $this->db->escape($config['delete_batch']) . "'";
    } else if ($mode == 'missing_brand') {
      if (!empty($this->session->data['obui_no_delete']) && !empty($this->session->data['obui_delete_brand'])) {
        $where = " WHERE " . $type . "_id NOT IN (" . implode(',', $this->session->data['obui_no_delete']) . ") AND manufacturer_id IN (" . implode(',', $this->session->data['obui_delete_brand']) . ")";
      } else {
        // no brands? do not run delete
        return;
      }
    }
    
    if (!empty($config['delete_batch'])) {
      $where .= $where ? ' AND ' : ' WHERE ';
      
      if ($config['delete_batch'] == 'defined') {
        $where .= "`import_batch` <> ''";
      } else if ($config['delete_batch'] == 'empty') {
        $where .= "`import_batch` = ''";
      } else {
        $where .= "`import_batch` = '" . $this->db->escape($config['delete_batch']) . "'";
      }
    }
    
    // if simulation count total
    if ($this->simulation) {
      $count = $this->db->query("SELECT COUNT(" . $type . "_id) AS total FROM " . DB_PREFIX . $type . $where)->row;
      if ($action == 'delete') {
        $this->session->data['obui_processed']['deleted'] = $count['total'];
      }
    } else {
      if (!in_array($type, array('product', 'category', 'information', 'manufacturer', 'customer'))) {
        die('Invalid type');
      }
      
      if (in_array($type, array('product', 'product_update', 'category', 'information', 'manufacturer'))) {
        $this->load->model('catalog/'.str_replace('_update', '', $type));
      } else if ($type == 'customer') {
        $this->load->model(($this->OC_V21 ? 'customer':'sale').'/customer');
      }
    }

    $to_delete = $this->db->query("SELECT " . $type . "_id FROM " . DB_PREFIX . $type . $where)->rows;
    
    if ($action == 'delete') {
      foreach ($to_delete as $del) {
        if (!$this->simulation) {
          $this->{'model_catalog_'.$type}->{'delete'.ucfirst($type)}($del[$type.'_id']);
        }
        
        $deleted_array[] = $del[$type.'_id'];
      }
    } else if ($action == 'disable') {
      foreach ($to_delete as $del) {
        if (!$this->simulation) {
          $this->db->query("UPDATE " . DB_PREFIX . $type . " SET status = 0 WHERE " . $type . "_id = '" . (int) $del[$type.'_id'] . "'");
        }
        
        $deleted_array[] = $del[$type.'_id'];
      }
    } else if ($action == 'zero') {
      foreach ($to_delete as $del) {
        if (!$this->simulation) {
          $this->db->query("UPDATE " . DB_PREFIX . $type . " SET quantity = 0 WHERE " . $type . "_id = '" . (int) $del[$type.'_id'] . "'");
        }
        
        $deleted_array[] = $del[$type.'_id'];
      }
    }
    
    $deleted_ids = '';
    foreach ($deleted_array as $deleted) {
      if (defined('GKD_CRON')) {
        $deleted_ids .= $deleted.', ';
      } else {
        $deleted_ids .= '<a target="_blank" href="'.$this->url->link('catalog/product/edit', 'product_id='.$deleted.'&' . $this->token, 'SSL').'">'.$deleted.'</a>, ';
      }
    }
     $deleted_ids = rtrim($deleted_ids, ', ');
    
    if (!$deleted_ids) {
      $deleted_ids = $this->language->get('text_nothing_deleted');
    }
    
    if ($action == 'delete') {
      if (defined('GKD_CRON')) {
        $this->cron_log($this->language->get('text_'.$simu_row.'_deleted') . ' - ' . ($mode == 'all') ? $this->language->get('text_delete_all') : strip_tags($deleted_ids));
      } else {
        $this->session->data['obui_log'][] = array(
          'row' => '',
          'status' => 'deleted',
          'title' => $this->language->get('text_'.$simu_row.'_deleted'),
          'msg' => ($mode == 'all' && empty($config['delete_batch'])) ? $this->language->get('text_delete_all') : $deleted_ids,
        );
      }
    } else if ($action == 'zero') {
      if (defined('GKD_CRON')) {
        $this->cron_log($this->language->get('text_'.$simu_row.'_qtyzero') . ' - ' . ($mode == 'all') ? $this->language->get('text_delete_all') : strip_tags($deleted_ids));
      } else {
        $this->session->data['obui_log'][] = array(
          'row' => '',
          'status' => 'error',
          'title' => $this->language->get('text_'.$simu_row.'_qtyzero'),
          'msg' => ($mode == 'all' && empty($config['delete_batch'])) ? $this->language->get('text_delete_all') : $deleted_ids,
        );
      }
    } else {
      if (defined('GKD_CRON')) {
        $this->cron_log($this->language->get('text_'.$simu_row.'_disabled') . ' - ' . ($mode == 'all') ? $this->language->get('text_delete_all') : strip_tags($deleted_ids));
      } else {
        $this->session->data['obui_log'][] = array(
          'row' => '',
          'status' => 'deleted',
          'title' => $this->language->get('text_'.$simu_row.'_disabled'),
          'msg' => ($mode == 'all' && empty($config['delete_batch'])) ? $this->language->get('text_delete_all') : $deleted_ids,
        );
      }
    }
  }
  
  public function process_product($config, $line) {
    $this->pre_process($config);

    // if ($config['item_exists'] == 'soft_update') {
      // $save_config = $config;
    // }
    if (empty($this->filetype)) {
      $this->filetype = !empty($config['import_filetype']) ? $config['import_filetype'] : strtolower(pathinfo($config['import_file'], PATHINFO_EXTENSION));
    }
    
    $config['columns_bindings'] = $config['columns'];
    
    // get default value if array is empty
    $config['columns']['product_category'] = array_filter($config['columns']['product_category'], array($this, 'array_filter_column'));
    
    if (empty($config['columns']['product_category'])) {
      $config['columns']['product_category'] = '';
    }
    
    $this->populate_fields($config, $line);
    $this->populate_extra_func($config, $line);
    
    $data = &$config['columns'];
    
    $item_id = $this->itemExists($config['import_type'], $config['item_identifier'], $data);
    
    // product will be updated, prepare values
    if (($item_id && $config['item_exists'] == 'soft_update') || ($item_id && $config['item_exists'] == 'update') || (!$item_id && $config['item_not_exists'] == 'insert')) {
      
        // var_dump($config['columns']);
      if ($item_id && $config['item_exists'] == 'soft_update') {
        //$config = $save_config;
        $config['columns'] = $config['columns_bindings'];
        
        $config['columns'] = $this->walk_recursive_remove($config['columns']);
        
        $this->populate_fields($config, $line);

        $data = &$config['columns'];
      }
      
      $data['import_batch'] = isset($config['import_label']) ? $config['import_label'] : '';
      
      // data formatters
      if (isset($data['product_category']))     $data['product_category'] = $this->categoryHandler('product_category', $config);
      if (isset($data['image']))                $data['image'] = $this->imageHandler('image', $config);
      if (isset($data['product_image']))        $data['product_image'] = $this->imageHandler('product_image', $config, true);
      if (isset($data['price']))                $data['price'] = $this->floatValue($data['price']);
      if (isset($data['weight']))               $data['weight'] = $this->floatValue($data['weight']);
      if (isset($data['width']))                $data['width'] = $this->floatValue($data['width']);
      if (isset($data['height']))               $data['height'] = $this->floatValue($data['height']);
      if (isset($data['manufacturer_id']))      $data['manufacturer_id'] = $this->manufacturerHandler($config);
      if (isset($data['product_store']))        $data['product_store'] = $this->simpleArrayHandler('product_store', $config); // @todo: detect by name
      if (isset($data['product_related']))      $data['product_related'] = $this->simpleArrayHandler('product_related', $config); // @todo: detect by name
      if (isset($data['product_option']))       $data['product_option'] = $this->optionHandler('product_option', $config); // 
      if (isset($data['product_attribute']))    $data['product_attribute'] = $this->attributeHandler('product_attribute', $config); // header > value | <attribute>:<text> | <attribute_group>:<attribute>:<text>
      if (isset($data['product_filter']))       $data['product_filter'] = $this->filterHandler('product_filter', $config);
      if (isset($data['product_discount']))     $data['product_discount'] = $this->discountHandler('product_discount', $config); // <customer_group_id>:<quantity>:<priority>:<price>:<date_start>:<date_end>
      if (isset($data['product_special']))      $data['product_special'] = $this->specialHandler('product_special', $config); // <customer_group_id>:<priority>:<price>:<date_start>:<date_end>
      if (isset($data['status']))               $data['status'] = $this->booleanHandler('status', $config); // enabled/disabled, on/off, true/false, 1/0
      
      // set default values for custom module compatibility
      if ($config['item_exists'] != 'soft_update') {
        $setDefault = array(
          'keyword', // seo modules
          'best',
          'priority',
          'frequency',
          'update_seo_url',
          // 'adwords_grouping',
          // 'gpf_status',
          // 'gtin',
          // 'google_product_category',
        );
        
        foreach ($setDefault as $v) {
          if (!isset($data[$v])) {
            $data[$v] = '';
          }
        }
      }

      // integrity checks
      /*
      if (empty($data['manufacturer_id'])) {
        $data['row_status'] = 'skipped';
        $this->session->data['obui_processed']['skipped']++;
        return $data;
      }
      if (empty($data['manufacturer_id'])) {
        throw new Exception('[skip] Manufacturer not defined for part model "' . $data['model'] . '" at line ' . $this->session->data['obui_current_line']);
      }
      */
      
      // unset if empty
      foreach (array('product_store', 'product_option', 'product_discount', 'product_special', 'product_image', 'product_download', 'product_attribute',
                     'product_category', 'product_filter', 'product_related', 'product_reward', 'product_layout', 'product_recurrings') as $key) {
        if (empty($data[$key])) {
          unset($data[$key]);
        }
      }
    }
    
    // handle extra function with populated values
    if (isset($config['extra_func'])) {
      foreach ($config['extra_func'] as $extra_funcs) {
        foreach ($extra_funcs as $func_name => $func_values) {
          if ($func_name == 'delete_item' && ($func_values['field'] == $func_values['value'])) {
            $item_to_delete = true;
          } else if ($func_name == 'skip')  {
            if (($func_values['comparator'] == 'is_equal' && $func_values['field'] == $func_values['value']) ||
                ($func_values['comparator'] == 'is_not_equal' && $func_values['field'] != $func_values['value'])) {
              $data['row_status'] = 'skipped';
              $this->session->data['obui_processed']['skipped']++;
              $data['row_msg'] = $this->language->get('text_rows_skipped');
              $this->session->data['obui_processed']['processed']++;
              return $data;
            }
          }
        }
      }
    }
    
    if ($item_id) {
      if (!empty($item_to_delete)) {
       if (!$this->simulation) {
          $this->model_catalog_product->deleteProduct($item_id);
        }
        $data['row_status'] = 'deleted';
        $this->session->data['obui_processed']['deleted']++;
      } else if ($config['item_exists'] == 'update') {
        if (!$this->simulation) {
          $data = $this->request->clean($data);
          $this->model_catalog_product->editProduct($item_id, $data);
        }
        $data['row_status'] = 'updated';
        $this->session->data['obui_processed']['updated']++;
      } else if ($config['item_exists'] == 'soft_update') {
        if (!$this->simulation) {
          $data = $this->request->clean($data);
          $this->editProduct($item_id, $data, $config);
        }
        $data['row_status'] = 'updated';
        $this->session->data['obui_processed']['updated']++;
      } else {
        // skip item - log
        $data['row_status'] = 'skipped';
        $this->session->data['obui_processed']['skipped']++;
      }
    } else {
      if ($config['item_not_exists'] == 'insert' && empty($item_to_delete)) {
        if (!$this->simulation) {
          $data = $this->request->clean($data);
          $item_id = $this->model_catalog_product->addProduct($data);
        }
        $data['row_status'] = 'inserted';
        $this->session->data['obui_processed']['inserted']++;
      } else {
        // skip item - log
        $data['row_status'] = 'skipped';
        $this->session->data['obui_processed']['skipped']++;
      }
    }
    
    $this->session->data['obui_processed']['processed']++;
    
    if ($item_id) {
      $data['item_to_update'] = 'Product ID '.$item_id;
    }
    
    if (!empty($data['product_description'][$this->config->get('config_language_id')]['name'])) {
      $item_name = $data['product_description'][$this->config->get('config_language_id')]['name'];
    } else if ($item_id) {
      $item_name = $data['item_to_update'];
    } else {
      $item_name = '';
    }
    
    if ($item_id && !empty($config['delete']) && $config['delete'] != 'all') {
      $this->session->data['obui_no_delete'][] = $item_id;
    }
    
    if ($item_id) {
      if (defined('GKD_CRON')) {
        $data['row_msg'] = '['.$item_id.'] '.$item_name;
      } else {
        $data['row_msg'] = '['.$item_id.'] <a target="_blank" href="'.$this->url->link('catalog/product/edit', 'product_id='.$item_id.'&' . $this->token, 'SSL').'">'.$item_name.'</a>';
      }
    } else {
      $data['row_msg'] = $item_name;
    }
    
    return $data;
  }
  
  public function process_product_update($config, $line) {
    $this->pre_process($config);
    
    $this->populate_fields($config, $line);
    
    $data = &$config['columns'];
    
    // unset if empty
    foreach (array('price', 'quantity', 'status') as $key) {
      if ($data[$key] === '') {
        unset($data[$key]);
      }
    }

    $data = $this->request->clean($data);
    
    $product_id = $this->itemExists('product', $config['item_identifier'], $data);
    
    if (isset($data['status'])) $data['status'] = $this->booleanHandler('status', $config); // enabled/disabled, on/off, true/false, 1/0
    //$data['product_group'] = $this->productGroupHandler('product_group', $config);
    
    $update_values = $update_desc_values = array();
    
    foreach ($data as $field => $value) {
      if (in_array($field, array('product_id', 'product_description'))) {
        continue;
      } else if (in_array($field, array('price', 'retail', 'map'))) {
        $update_values[] = $this->db->escape($field) . " = '" . (float) $value . "' ";
      } else if (in_array($field, array('quantity'))) {
        if (empty($config['quantity_modifier'])) {
          $update_values[] = $this->db->escape($field) . " = '" . (int) $value . "' ";
        } else if ($config['quantity_modifier'] == '+') {
          $update_values[] = $this->db->escape($field) . " = " . $this->db->escape($field) . " + '" . (int) $value . "' ";
        } else if ($config['quantity_modifier'] == '-') {
          $update_values[] = $this->db->escape($field) . " = " . $this->db->escape($field) . " - '" . (int) $value . "' ";
        }
      } else {
        $update_values[] = $this->db->escape($field) . " = '" . $this->db->escape($value) . "' ";
      }
    }
    
    $update_values = implode(', ', $update_values);
    
    if ($product_id) {
      if (!$this->simulation) {
        $this->db->query("UPDATE " . DB_PREFIX . "product SET " . $update_values . " WHERE product_id = '" . (int)$product_id . "'");
      }
      
      if (!empty($data['product_description'])) {
        foreach ($data['product_description'] as $language_id => $item_desc) {
          foreach ($item_desc as $field => $value) {
            $update_desc_values[] = $this->db->escape($field) . " = '" . $this->db->escape($value) . "'";
          }
          
          $update_desc_values = implode(', ', $update_desc_values);
          
          $this->db->query("UPDATE " . DB_PREFIX . "product_description SET " . $update_desc_values . " WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "' ");
        }
      }
      
      $data['row_status'] = 'updated';
      $this->session->data['obui_processed']['updated']++;
    } else {
      $data['row_status'] = 'skipped';
      $this->session->data['obui_processed']['skipped']++;
    }
    
    if ($product_id && !empty($config['delete']) && $config['delete'] != 'all') {
      $this->session->data['obui_no_delete'][] = $product_id;
    }
    
    $this->session->data['obui_processed']['processed']++;
    
    return $data;
  }
  
  public function process_order_status_update($config, $line) {
    $this->pre_process($config);
    
    $this->populate_fields($config, $line);
    
    $data = &$config['columns'];
    
    // unset if empty
    /*
    foreach (array('price', 'quantity') as $key) {
      if (empty($data[$key])) {
        unset($data[$key]);
      }
    }
    */
    
    $data = $this->request->clean($data);
    $item_id = $this->itemExists('order', $config['item_identifier'], $data);
    
    $data['order_status_id'] = $this->orderStatusHandler('order_status_id', $config);
    
    if (isset($data['notify'])) $data['notify'] = $this->booleanHandler('notify', $config); // enabled/disabled, on/off, true/false, 1/0
    //$data['product_group'] = $this->productGroupHandler('product_group', $config);
    
    /*
    $update_values = array();
    
    foreach ($data as $field => $value) {
      if (in_array($field, array('product_id', 'product_description'))) {
        continue;
      } else if (in_array($field, array('price', 'retail', 'map'))) {
        $update_values[] = $this->db->escape($field) . " = '" . (float) $value . "' ";
      } else if (in_array($field, array('quantity'))) {
        $update_values[] = $this->db->escape($field) . " = '" . (int) $value . "' ";
      } else {
        $update_values[] = $this->db->escape($field) . " = '" . $this->db->escape($value) . "' ";
      }
    }
    
    $update_values = implode(', ', $update_values);
    */
    
    if ($item_id) {
      if (!$this->simulation) {
        $this->model_gkd_import_order->addOrderHistory($item_id, $data['order_status_id'], $data['comment'], $data['notify'], false, $data);
      }
      
      $data['row_status'] = 'updated';
      $this->session->data['obui_processed']['updated']++;
    } else {
      $data['row_status'] = 'skipped';
      $this->session->data['obui_processed']['skipped']++;
    }
    
    /*
    if ($item_id && !empty($config['delete']) && $config['delete'] != 'all') {
      $this->session->data['obui_no_delete'][] = $item_id;
    }
    */
    
    $this->session->data['obui_processed']['processed']++;
    
    if ($this->simulation) {
      $data['comment'] = array_shift($data['comment']);
    }
    
    if ($item_id) {
      if (defined('GKD_CRON')) {
        $data['row_msg'] = $this->language->get('text_type_order').' #'.$item_id;
      } else {
        $data['row_msg'] = '<a target="_blank" href="'.$this->url->link('sale/order/info', 'order_id='.$item_id.'&' . $this->token, 'SSL').'">#'.$item_id.'</a>';
      }
    }
    
    return $data;
  }
  
  public function process_category($config, $line) {
    $this->pre_process($config);
    
    $config['columns_bindings'] = $config['columns'];
    
    $this->populate_fields($config, $line);
    
    $data = &$config['columns'];
    
    $data['parent_id'] = $this->parentCategoryHandler('parent_id', $config);
    
    $item_id = $this->categoryExists($data['category_description'], $data['parent_id']);
    
    // item will be processed, prepare values
    if (($item_id && $config['item_exists'] == 'soft_update') || ($item_id && $config['item_exists'] == 'update') || (!$item_id && $config['item_not_exists'] == 'insert')) {
      
      if ($item_id && $config['item_exists'] == 'soft_update') {
        //$config = $save_config;
        $config['columns'] = $config['columns_bindings'];
        
        $config['columns'] = $this->walk_recursive_remove($config['columns']);
        
        $this->populate_fields($config, $line);

        $data = &$config['columns'];
      }
      
      // data formatters
      if (isset($data['image']))              $data['image'] = $this->imageHandler('image', $config);
      if (isset($data['category_store']))     $data['category_store'] = $this->simpleArrayHandler('category_store', $config); // @todo: detect by name
      
      //$data['banner_image'] = $this->imageHandler('banner_image', $config);
      
      // unset if empty
      foreach (array('category_store', 'category_filter', 'category_layout') as $key) {
        if (empty($data[$key])) {
          unset($data[$key]);
        }
      }
    }
    
    if ($item_id) {
      if ($config['item_exists'] == 'update') {
        
        if (!$this->simulation) {
          $data = $this->request->clean($data);
          $this->model_catalog_category->editCategory($item_id, $data);
        }
        $data['row_status'] = 'updated';
        $this->session->data['obui_processed']['updated']++;
      } else if ($config['item_exists'] == 'soft_update') {
        if (!$this->simulation) {
          $data = $this->request->clean($data);
          $this->editCategory($item_id, $data);
        }
        $data['row_status'] = 'updated';
        $this->session->data['obui_processed']['updated']++;
      } else {
        // skip item - log
        $data['row_status'] = 'skipped';
        $this->session->data['obui_processed']['skipped']++;
      }
    } else {
      if ($config['item_not_exists'] == 'insert') {
        if (!$this->simulation) {
          $data = $this->request->clean($data);
          $item_id = $this->model_catalog_category->addCategory($data);
        }
        $data['row_status'] = 'inserted';
        $this->session->data['obui_processed']['inserted']++;
      } else {
        // skip item - log
        $data['row_status'] = 'skipped';
        $this->session->data['obui_processed']['skipped']++;
      }
    }
    
    $this->session->data['obui_processed']['processed']++;
    
    if (empty($data['category_description'][$this->config->get('config_language_id')]['name'])) {
      $data['category_description'][$this->config->get('config_language_id')]['name'] = 'Category ID '.$item_id;
    }
    
    if ($item_id && !empty($config['delete']) && $config['delete'] != 'all') {
      $this->session->data['obui_no_delete'][] = $item_id;
    }
    
    if ($item_id) {
      if (defined('GKD_CRON')) {
        $data['row_msg'] = '['.$item_id.'] ' . $data['category_description'][$this->config->get('config_language_id')]['name'];
      } else {
        $data['row_msg'] = '['.$item_id.'] <a target="_blank" href="'.$this->url->link('catalog/category/edit', 'category_id='.$item_id.'&' . $this->token, 'SSL').'">'.$data['category_description'][$this->config->get('config_language_id')]['name'].'</a>';
      }
    } else {
      $data['row_msg'] = $data['category_description'][$this->config->get('config_language_id')]['name'];
    }
    
    return $data;
  }
  
  public function process_information($config, $line) {
    $this->pre_process($config);
    
    $this->populate_fields($config, $line);
    
    $data = &$config['columns'];
    
    $item_id = $this->itemExists($config['import_type'], $config['item_identifier'], $data);
    
    // product will be updated, prepare values
    if (($item_id && $config['item_exists'] == 'update') || (!$item_id && $config['item_not_exists'] == 'insert')) {
      
      // data formatters
      $data['information_store'] = $this->simpleArrayHandler('information_store', $config); // @todo: detect by name
      
      // unset if empty
      foreach (array('information_store') as $key) {
        if (empty($data[$key])) {
          unset($data[$key]);
        }
      }
    }
    
    if ($item_id) {
      if ($config['item_exists'] == 'update') {
        
        if (!$this->simulation) {
          $data = $this->request->clean($data);
          $this->model_catalog_information->editInformation($item_id, $data);
        }
        $data['row_status'] = 'updated';
        $this->session->data['obui_processed']['updated']++;
      } else {
        // skip item - log
        $data['row_status'] = 'skipped';
        $this->session->data['obui_processed']['skipped']++;
      }
    } else {
      if ($config['item_not_exists'] == 'insert') {
        if (!$this->simulation) {
          $data = $this->request->clean($data);
          $this->model_catalog_information->addInformation($data);
        }
        $data['row_status'] = 'inserted';
        $this->session->data['obui_processed']['inserted']++;
      } else {
        // skip item - log
        $data['row_status'] = 'skipped';
        $this->session->data['obui_processed']['skipped']++;
      }
    }
    
    $this->session->data['obui_processed']['processed']++;
    
    if ($item_id && !empty($config['delete']) && $config['delete'] != 'all') {
      $this->session->data['obui_no_delete'][] = $item_id;
    }
    
    return $data;
  }
  
  public function process_manufacturer($config, $line) {
    $this->pre_process($config);
    
    $this->populate_fields($config, $line);
    
    $data = &$config['columns'];
    
    $item_id = $this->itemExists($config['import_type'], $config['item_identifier'], $data);
    
    // product will be updated, prepare values
    if (($item_id && $config['item_exists'] == 'update') || (!$item_id && $config['item_not_exists'] == 'insert')) {
      
      // data formatters
      $data['manufacturer_store'] = $this->simpleArrayHandler('manufacturer_store', $config); // @todo: detect by name
      $data['image'] = $this->imageHandler('image', $config);
      
      // unset if empty
      foreach (array('manufacturer_store') as $key) {
        if (empty($data[$key])) {
          unset($data[$key]);
        }
      }
    }
    
    if ($item_id) {
      if ($config['item_exists'] == 'update') {
        
        if (!$this->simulation) {
          $data = $this->request->clean($data);
          $this->model_catalog_manufacturer->editManufacturer($item_id, $data);
        }
        $data['row_status'] = 'updated';
        $this->session->data['obui_processed']['updated']++;
      } else {
        // skip item - log
        $data['row_status'] = 'skipped';
        $this->session->data['obui_processed']['skipped']++;
      }
    } else {
      if ($config['item_not_exists'] == 'insert') {
        if (!$this->simulation) {
          $data = $this->request->clean($data);
          $this->model_catalog_manufacturer->addManufacturer($data);
        }
        $data['row_status'] = 'inserted';
        $this->session->data['obui_processed']['inserted']++;
      } else {
        // skip item - log
        $data['row_status'] = 'skipped';
        $this->session->data['obui_processed']['skipped']++;
      }
    }
    
    $this->session->data['obui_processed']['processed']++;
    
    if ($item_id && !empty($config['delete']) && $config['delete'] != 'all') {
      $this->session->data['obui_no_delete'][] = $item_id;
    }
    
    return $data;
  }
  
  public function process_customer($config, $line) {
    $this->pre_process($config);
    
    $this->populate_fields($config, $line);
    
    $customer_model = 'model_'.($this->OC_V21 ? 'customer':'sale').'_customer';
    
    $data = &$config['columns'];
    
    $item_id = $this->itemExists($config['import_type'], $config['item_identifier'], $data);
    
    // item will be processed, prepare values
    if (($item_id && $config['item_exists'] == 'update') || (!$item_id && $config['item_not_exists'] == 'insert')) {
      
      // data formatters
      //$data['image'] = $this->imageHandler('image', $config);
      //$data['address'] = $this->dataArrayHandler('address', $config);
      
      // unset if empty
      foreach (array('address') as $key) {
        if (empty($data[$key])) {
          unset($data[$key]);
        }
      }
    }
    
    if ($item_id) {
      if ($config['item_exists'] == 'update') {
        
        if (!$this->simulation) {
          $data = $this->request->clean($data);
          // use remove and add because of address
          //$this->{$customer_model}->editCustomer($item_id, $data);
          $this->{$customer_model}->deleteCustomer($item_id);
          $this->{$customer_model}->addCustomer($data);
        }
        $data['row_status'] = 'updated';
        $this->session->data['obui_processed']['updated']++;
      } else {
        // skip item - log
        $data['row_status'] = 'skipped';
        $this->session->data['obui_processed']['skipped']++;
      }
    } else {
      if ($config['item_not_exists'] == 'insert') {
        if (!$this->simulation) {
          $data = $this->request->clean($data);
          $this->{$customer_model}->addCustomer($data);
        }
        $data['row_status'] = 'inserted';
        $this->session->data['obui_processed']['inserted']++;
      } else {
        // skip item - log
        $data['row_status'] = 'skipped';
        $this->session->data['obui_processed']['skipped']++;
      }
    }
    
    $this->session->data['obui_processed']['processed']++;
    
    if ($item_id && !empty($config['delete']) && $config['delete'] != 'all') {
      $this->session->data['obui_no_delete'][] = $item_id;
    }
    
    return $data;
  }
  
  protected function simpleArrayHandler($field, &$config) {
    $values_array = array();
    
    if (empty($config['columns'][$field])) {
      return $values_array;
    }
    
    if (count($config['columns'][$field]) == 1 && $config['columns'][$field][0] === '') {
      return array();
    }
    
    foreach ((array) $config['columns'][$field] as $value) {
      if (is_array($value)) {
        $values_array = array_merge($values_array, $value);
      } else if (!empty($config['multiple_separator'])) {
        $values_array = array_merge($values_array, explode(@html_entity_decode($config['multiple_separator']), $value));
      } else {
        $values_array[] = $value;
      }
    }
    
    return $values_array;
  }
  
  protected function dataArrayHandler($field, &$config) {
    $values_array = array();
    
    if (empty($config['columns'][$field])) {
      return $values_array;
    }
    
    if (count($config['columns'][$field]) == 1 && $config['columns'][$field][0] === '') {
      return array();
    }
    
    foreach ((array) $config['columns'][$field] as $value) {
      if (is_array($value)) {
        $values_array = array_merge($values_array, $value);
      } else if (!empty($config['multiple_separator'])) {
        $values_array = array_merge($values_array, explode(@html_entity_decode($config['multiple_separator']), $value));
      } else {
        $values_array[] = $value;
      }
    }
    
    return $values_array;
  }
  
  protected function optionHandler($field, &$config) {
    $return_values = $values_array = array();
    
    if (empty($config['columns'][$field])) {
      return $values_array;
    }
    
    foreach ((array) $config['columns'][$field] as $key => $value) {
      if ($value === '') {
        continue;
      } else if (is_array($value)) {
        $values_array = array_merge($values_array, $value);
      } else if (!empty($config['multiple_separator'])) {
        $values_array = array_merge($values_array, explode(@html_entity_decode($config['multiple_separator']), $value));
      } else {
        $values_array[$key] = $value;
      }
    }
    
    $this->load->model('localisation/language');
    $languages = $this->model_localisation_language->getLanguages();
    
    // xml fix
    if ($this->filetype == 'xml' && isset($values_array['option']) && !isset($values_array['option']['optionType'])) {
      $values_array = $values_array['option'];
    }

    foreach ($values_array as $current_key => &$value) {
      // xml fix
      if ($this->filetype == 'xml' && !empty($config['option_fields'])) {
        if (isset($config['option_fields']['type']) && !empty($value[$config['option_fields']['type']])) {
          $option_type = strtolower($value[$config['option_fields']['type']]);
        } else if (isset($config['option_fields_default']['type'])) {
          $option_type = $config['option_fields_default']['type'];
        } else {
          $option_type = 'select';
        }
        
        if (isset($config['option_fields']['name']) && !empty($value[$config['option_fields']['name']])) {
          $option_name = $value[$config['option_fields']['name']];
        } else if (isset($config['option_fields_default']['name'])) {
          $option_name = $config['option_fields_default']['name'];
        } else {
          $option_name = 'Size';
        }
        
        if (isset($config['option_fields']['value']) && strpos($config['option_fields']['value'], '|') !== false) {
          $valFields = explode('|', $config['option_fields']['value']);
          $option_value = '';
          
          foreach ($valFields as $valField) {
            if (!empty($value[$valField])) {
              $option_value = $value[$valField];
              break;
            }
          }
        } else if (isset($config['option_fields']['value']) && !empty($value[$config['option_fields']['value']])) {
          $option_value = $value[$config['option_fields']['value']];
        } else if (isset($config['option_fields_default']['value'])) {
          $option_value = $config['option_fields_default']['value'];
        } else {
          $option_value = '';
        }
        
        if (isset($config['option_fields']['price']) && !empty($value[$config['option_fields']['price']])) {
          $option_price = $value[$config['option_fields']['price']];
        } else if (isset($config['option_fields_default']['price'])) {
          $option_price = $config['option_fields_default']['price'];
        } else {
          $option_price = '';
        }
        
        if (isset($config['option_fields']['required']) && !empty($value[$config['option_fields']['required']])) {
          $option_required = $value[$config['option_fields']['required']];
        } else if (isset($config['option_fields_default']['required'])) {
          $option_required = $config['option_fields_default']['required'];
        } else {
          $option_required = '';
        }
        
        if (isset($config['option_fields']['quantity']) && !empty($value[$config['option_fields']['quantity']])) {
          $option_qty = $value[$config['option_fields']['quantity']];
        } else if (isset($config['option_fields_default']['quantity'])) {
          $option_qty = $config['option_fields_default']['quantity'];
        } else {
          $option_qty = '';
        }
        
        if (isset($config['option_fields']['subtract']) && !empty($value[$config['option_fields']['subtract']])) {
          $option_subtract = $value[$config['option_fields']['subtract']];
        } else if (isset($config['option_fields_default']['subtract'])) {
          $option_subtract = $config['option_fields_default']['subtract'];
        } else {
          $option_subtract = '';
        }
        
        if (isset($config['option_fields']['weight']) && !empty($value[$config['option_fields']['weight']])) {
          $option_weight = $value[$config['option_fields']['weight']];
        } else if (isset($config['option_fields_default']['weight'])) {
          $option_weight = $config['option_fields_default']['weight'];
        } else {
          $option_weight = '';
        }
        
        
        foreach ($languages as $language) {
          $option_name_ml[$language['code']] = !empty($value['optionName'.ucfirst(substr($language['code'], 0, 2))]) ? $value['optionName'.ucfirst(substr($language['code'], 0, 2))] : '';
          $option_value_ml[$language['code']] = !empty($value['optionValue'.ucfirst(substr($language['code'], 0, 2))]) ? $value['optionValue'.ucfirst(substr($language['code'], 0, 2))] : '';
        }
      } else if ($this->filetype == 'xml' && !empty($value['optionNameEn'])) {
        $option_type = strtolower($value['optionType']);
        $option_name = $value['optionNameEn'];
        $option_value = $value['optionValueEn'];
        $option_price = !empty($value['optionPrice']) ? $value['optionPrice'] : '';
        $option_required = !empty($value['optionRequired']) ? $value['optionRequired'] : '';
        
        foreach ($languages as $language) {
          $option_name_ml[$language['code']] = !empty($value['optionName'.ucfirst(substr($language['code'], 0, 2))]) ? $value['optionName'.ucfirst(substr($language['code'], 0, 2))] : '';
          $option_value_ml[$language['code']] = !empty($value['optionValue'.ucfirst(substr($language['code'], 0, 2))]) ? $value['optionValue'.ucfirst(substr($language['code'], 0, 2))] : '';
        }
      } else {
        if (strpos($value, ':') !== false) {
          $values = explode(':', $value);
        }
        
        $option_type = $option_name = $option_value = $option_price = $option_required = $option_weight = $option_qty = $option_subtract = '';
        
        if (!isset($values)) {
          // get column header
          $column_headers = json_decode(base64_decode($config['column_headers']));
          
          if (!empty($column_headers[ $config['columns_bindings'][$field][$current_key] ])) {
            $option_type = 'select';
            $option_name = $column_headers[ $config['columns_bindings'][$field][$current_key] ];
            $option_value = $value;
            $option_price = '';
            $option_qty = '';
            $option_subtract = '';
            $option_weight = '';
            $option_required = '';
          } else {
            $option_type = 'select';
            $option_name = $value;
            $option_value = $value;
            $option_price = '';
            $option_qty = '';
            $option_subtract = '';
            $option_weight = '';
            $option_required = '';
          }
        } else if (count($values) == 2) {
          // name:value
          $option_type = 'select';
          $option_name = $values[0];
          $option_value = $values[1];
          $option_price = '';
          $option_qty = '';
          $option_subtract = '';
          $option_weight = '';
          $option_required = '';
        } else if (count($values) == 3) {
          // type:name:value
          $option_type = strtolower($values[0]);
          $option_name = $values[1];
          $option_value = $values[2];
          $option_price = '';
          $option_qty = '';
          $option_subtract = '';
          $option_weight = '';
          $option_required = '';
        } else if (count($values) == 4) {
          // type:name:value:price
          $option_type = strtolower($values[0]);
          $option_name = $values[1];
          $option_value = $values[2];
          $option_price = $values[3];
          $option_qty = '';
          $option_subtract = '';
          $option_weight = '';
          $option_required = '';
        } else if (count($values) == 5) {
          // type:name:value:price:qty
          $option_type = strtolower($values[0]);
          $option_name = $values[1];
          $option_value = $values[2];
          $option_price = $values[3];
          $option_qty = $values[4];
          $option_subtract = '';
          $option_weight = '';
          $option_required = '';
        } else if (count($values) == 6) {
          // type:name:value:price:qty:subtract
          $option_type = strtolower($values[0]);
          $option_name = $values[1];
          $option_value = $values[2];
          $option_price = $values[3];
          $option_qty = $values[4];
          $option_subtract = $values[5];
          $option_weight = '';
          $option_required = '';
        } else if (count($values) == 7) {
          // type:name:value:price:qty:subtract:weight
          $option_type = strtolower($values[0]);
          $option_name = $values[1];
          $option_value = $values[2];
          $option_price = $values[3];
          $option_qty = $values[4];
          $option_subtract = $values[5];
          $option_weight = $values[6];
          $option_required = '';
        } else if (count($values) == 8) {
          // type:name:value:price:qty:subtract:weight:required
          $option_type = strtolower($values[0]);
          $option_name = $values[1];
          $option_value = $values[2];
          $option_price = $values[3];
          $option_qty = $values[4];
          $option_subtract = $values[5];
          $option_weight = $values[6];
          $option_required = $values[7];
        } else {
          // too much parts ?
          continue;
        }
      }
      
      if (!empty($config['filters_from_options'])) {
        $config['columns']['product_filter'][] = $option_name .':'. $option_value;
      }
      
      if ($this->simulation) {
        $return_values[] = $option_name .' > '. $option_value;
        
        continue;
      }
        // get option id or create
        $opt_group = $this->db->query("SELECT option_id FROM " . DB_PREFIX . "option_description WHERE name = '" . $this->db->escape($option_name) . "'")->row;

        // group exists - get id
        if (!empty($opt_group['option_id'])) {
          $option_id = $opt_group['option_id'];
        }
        //  group not exists - create
        else {
          $opt_group_data = array();
          $opt_group_data['sort_order'] = '';
          $opt_group_data['type'] = $option_type;
          $opt_group_data['option_description'] = array();
          $opt_group_data['option_value'] = array();
          
          foreach ($languages as $language) {
            $opt_group_data['option_description'][$language['language_id']]['name'] = !empty($option_name_ml[$language['code']]) ? $option_name_ml[$language['code']] : $option_name;
          }
          
          $this->load->model('catalog/option');
          $option_id = $this->model_catalog_option->addOption($this->request->clean($opt_group_data));
        }
        
        // get option value id or create
        $opt = $this->db->query("SELECT option_value_id FROM " . DB_PREFIX . "option_value_description WHERE option_id = '" . (int) $option_id . "' AND name = '" . $this->db->escape($option_value) . "'")->row;
        
        // option value exists ?
        if (!empty($opt['option_value_id'])) {
          $option_value_id = $opt['option_value_id'];
        }
        // not exists - create
        else {
          $opt_data = array();
          $opt_data['sort_order'] = '';
          $opt_data['image'] = '';
          
          // create option value
          $this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET option_id = '" . (int)$option_id . "', image = '" . $this->db->escape(@html_entity_decode($opt_data['image'], ENT_QUOTES)) . "', sort_order = '" . (int)$opt_data['sort_order'] . "'");

          $option_value_id = $this->db->getLastId();

          foreach ($languages as $language) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET option_value_id = '" . (int)$option_value_id . "', language_id = '" . (int) $language['language_id'] . "', option_id = '" . (int)$option_id . "', name = '" . $this->db->escape(!empty($option_value_ml[$language['code']]) ? $option_value_ml[$language['code']] : $option_value) . "'");
          }
        }
        
        // format values for product
        if (in_array($option_type, array('select', 'radio', 'checkbox', 'image'))) {
          if (!isset($return_values[$option_name])) {
            $return_values[$option_name] = array(
              'type' => $option_type,
              'required' => $option_required,
              'product_option_value' => array(),
              'option_id' => $option_id,
            );
          }
          
            $return_values[$option_name]['product_option_value'][] = array(
              'option_value_id' => $option_value_id,
              'quantity' => !empty($option_qty) ? $option_qty : '',
              'subtract' => !empty($option_subtract) ? $option_subtract : '',
              'price' => !empty($option_price) ? abs($option_price) : '',
              'price_prefix' => !empty($option_price) && ($option_price < 0) ? '-' : '+',
              'points' => '',
              'points_prefix' => '',
              'weight' => !empty($option_weight) ? abs($option_weight) : '',
              'weight_prefix' => !empty($option_weight) && ($option_weight < 0) ? '-' : '+',
            );
          
        } else {
          if (!isset($return_values[$option_name])) {
            $return_values[$option_name] = array(
              'type' => $option_type,
              'required' => $option_required,
              'option_id' => $option_id,
              'value' => $option_value,
            );
          }
        }
      /*
      $value = array(
        'attribute_id' => $attribute_id,
      );
      
      foreach ($languages as $language) {
        $value['product_attribute_description'][$language['language_id']]['text'] = !empty($attribute_text_ml[$language['code']]) ? $attribute_text_ml[$language['code']] : $attribute_text;
      }
      */
    }
    
    return $return_values;
  }
  /*
  protected function optionHandler($field, &$config) {
    $return_values = $values_array = array();
    
    if (empty($config['columns'][$field])) {
      return $values_array;
    }
    
    foreach ((array) $config['columns'][$field] as $value) {
      if (!$value) {
        continue;
      } else if (is_array($value)) {
        $values_array = array_merge($values_array, $value);
      } else if (!empty($config['multiple_separator'])) {
        $values_array = array_merge($values_array, explode(@html_entity_decode($config['multiple_separator']), $value));
      } else {
        $values_array[] = $value;
      }
    }
    
    if ($this->simulation) {
      return $values_array;
    }
    
    foreach ($values_array as &$value) {
      $values = explode(':', $value);
      $value = array(
        'field_name' => $values[0],
        'field_value' => $values[1],
      );
    }
    
    return $values_array;
  }
  */
  protected $customer_groups = array();
  
  protected function loadCustomerGroups() {
    if (!$this->customer_groups) {
      $query = $this->db->query("SELECT customer_group_id FROM " . DB_PREFIX . "customer_group")->rows;
      
      foreach ($query as $cg) {
        $this->customer_groups[] = $cg['customer_group_id'];
      }
    }
  }
  
  protected function discountHandler($field, &$config) {
    $return_values = $values_array = array();
    
    if (empty($config['columns'][$field])) {
      return $values_array;
    }
    
    foreach ((array) $config['columns'][$field] as $value) {
      if (!$value) {
        continue;
      } else if (is_array($value)) {
        $values_array = array_merge($values_array, $value);
      } else if (!empty($config['multiple_separator'])) {
        $values_array = array_merge($values_array, explode(@html_entity_decode($config['multiple_separator']), $value));
      } else {
        $values_array[] = $value;
      }
    }
    
    foreach ($values_array as $value) {
      if (strpos($value, ':') !== false) {
        $values = explode(':', $value);
        /* formats:
        - price
        - price:date_end
        - price:date_start:date_end
        - qty:price:date_start:date_end
        - customer_group_id:qty:price:date_start:date_end
        - customer_group_id:qty:priority:price:date_start:date_end
        */
        
        if (count($values) == 6) {
          $return_values[] = array(
            'customer_group_id' => $values[0],
            'quantity' => $values[1],
            'priority' => $values[2],
            'price' => $values[3],
            'date_start' => $values[4],
            'date_end' => $values[5],
          );
        } else if (count($values) == 5) {
          $return_values[] = array(
            'customer_group_id' => $values[0],
            'quantity' => $values[1],
            'priority' => 1,
            'price' => $values[2],
            'date_start' => $values[3],
            'date_end' => $values[4],
          );
        } else if (count($values) == 4) {
          $this->loadCustomerGroups();
        
          foreach ($this->customer_groups as $customer_group) {
            $return_values[] = array(
              'customer_group_id' => $customer_group,
              'quantity' => $values[0],
              'priority' => 1,
              'price' => $values[1],
              'date_start' => $values[2],
              'date_end' => $values[3],
            );
          }
        } else if (count($values) == 3) {
          $this->loadCustomerGroups();
        
          foreach ($this->customer_groups as $customer_group) {
            $return_values[] = array(
              'customer_group_id' => $customer_group,
              'quantity' => 999999,
              'priority' => 1,
              'price' => $values[0],
              'date_start' => $values[1],
              'date_end' => $values[2],
            );
          }
        } else if (count($values) == 2) {
          $this->loadCustomerGroups();
        
          foreach ($this->customer_groups as $customer_group) {
            $return_values[] = array(
              'customer_group_id' => $customer_group,
              'quantity' => 999999,
              'priority' => 1,
              'price' => $values[0],
              'date_start' => '2000-01-01',
              'date_end' => $values[1],
            );
          }
        }
      } else {
        $this->loadCustomerGroups();
        
        foreach ($this->customer_groups as $customer_group) {
          $return_values = array(
            'customer_group_id' => $customer_group,
            'quantity' => 999999,
            'priority' => 1,
            'price' => $value,
            'date_start' => '2000-01-01',
            'date_end' => '2100-01-01',
          );
        }
      }
      $values = explode(':', $value);
      
      /*
      if (count($values) != 6) {
        $this->session->data['obui_processed']['error']++;
        
        $this->session->data['obui_log'][] = array(
          'row' => $this->session->data['obui_current_line'],
          'status' => 'error',
          'title' => $this->language->get('warning'),
          'msg' => $this->language->get('warning_discount_format'),
        );
        
        continue;
      }
      */
    }
    
    if ($this->simulation) {
      $return_simu = array();
      
      foreach ($return_values as $item) {
        if (isset($item['date_start'])) {
          $return_simu[] = $item['date_start'] . ' > ' .  $item['date_end'] . ' : ' .  round($item['price'], 2);
        }
      }
      
      return $return_simu;
    }
    
    return $return_values;
  }
  
  protected function specialHandler($field, &$config) {
    $return_values = $values_array = array();
    
    if (empty($config['columns'][$field])) {
      return $return_values;
    }
    
    foreach ((array) $config['columns'][$field] as $value) {
      if (!$value) {
        continue;
      } else if (is_array($value) && !empty($value['gkd_formatted'])) {
        $values_array[] = $value;
      } else if (is_array($value)) {
        $values_array = array_merge($values_array, $value);
      } else if (!empty($config['multiple_separator'])) {
        $values_array = array_merge($values_array, explode(@html_entity_decode($config['multiple_separator']), $value));
      } else {
        $values_array[] = $value;
      }
    }
    
    foreach ($values_array as &$value) {
      if (is_array($value) && !empty($value['gkd_formatted'])) {
        if ($config['columns']['price'] == $this->floatValue($value['price'])) continue; // same price, do not add special
          
          $this->loadCustomerGroups();
        
          if (isset($value['customer_group_id']) && $value['customer_group_id'] !== '') {
            $return_values[] = array(
              'customer_group_id' => $value['customer_group_id'],
              'priority' => !empty($value['priority']) ? $value['priority'] : 1,
              'price' => $this->floatValue($value['price']),
              'date_start' => !empty($value['date_start']) ? $value['date_start'] : '2000-01-01',
              'date_end' => !empty($value['date_end']) ? $value['date_end'] : '2100-01-01',
            );
          } else {
            foreach ($this->customer_groups as $customer_group) {
              $return_values[] = array(
                'customer_group_id' => $customer_group,
                'priority' => !empty($value['priority']) ? $value['priority'] : 1,
                'price' => $this->floatValue($value['price']),
                'date_start' => !empty($value['date_start']) ? $value['date_start'] : '2000-01-01',
                'date_end' => !empty($value['date_end']) ? $value['date_end'] : '2100-01-01',
              );
            }
          }
      } else if (strpos($value, ':') !== false) {
        $values = explode(':', $value);
        
        if (count($values) == 5) {
          if ($config['columns']['price'] == $this->floatValue($values[2])) continue; // same price, do not add special

          $return_values[] = array(
            'customer_group_id' => $values[0],
            'priority' => $values[1],
            'price' => $this->floatValue($values[2]),
            'date_start' => $values[3],
            'date_end' => $values[4],
          );
        } else if (count($values) == 4) {
          if ($config['columns']['price'] == $this->floatValue($values[2])) continue; // same price, do not add special

          $return_values[] = array(
            'customer_group_id' => $values[0],
            'priority' => $values[1],
            'price' => $this->floatValue($values[2]),
            'date_start' => '2000-01-01',
            'date_end' => $values[3],
          );
        } else if (count($values) == 3) {
          if ($config['columns']['price'] == $this->floatValue($values[2])) continue; // same price, do not add special
          
          $return_values[] = array(
            'customer_group_id' => $values[0],
            'priority' => $values[1],
            'price' => $this->floatValue($values[2]),
            'date_start' => '2000-01-01',
            'date_end' => '2100-01-01',
          );
        } else if (count($values) == 2) {
          if ($config['columns']['price'] == $this->floatValue($values[1])) continue; // same price, do not add special
          
          $return_values[] = array(
            'customer_group_id' => $values[0],
            'priority' => 1,
            'price' => $this->floatValue($values[1]),
            'date_start' => '2000-01-01',
            'date_end' => '2100-01-01',
          );
        }
      } else {
        $this->loadCustomerGroups();
        
        foreach ($this->customer_groups as $customer_group) {
          if ($config['columns']['price'] == $this->floatValue($value)) continue; // same price, do not add special
          
          $return_values[] = array(
            'customer_group_id' => $customer_group,
            'priority' => 1,
            'price' => $this->floatValue($value),
            'date_start' => '2000-01-01',
            'date_end' => '2100-01-01',
          );
        }
      }
    }
    
    $return_values = array_filter($return_values, array($this, 'filterEmptyPrice'));
    
    if ($this->simulation) {
      $return_simu = array();
      
      foreach ($return_values as $item) {
        $return_simu[] = $item['date_start'] . ' -> ' .  $item['date_end'] . ' : ' .  round($item['price'], 2);
      }
      
      return $return_simu;
    }
    
    return $return_values;
  }
  
  protected function filterHandler($field, &$config) {
    $return_values = $values_array = array();
    
    if (empty($config['columns'][$field])) {
      return $values_array;
    }
    
    foreach ((array) $config['columns'][$field] as $key => $value) {
      # custom_filter_handler
      
       if (is_array($value)) {
        $values_array = array_merge($values_array, $value);
      } else if (!empty($config['multiple_separator'])) {
        $values_array = array_merge($values_array, explode(@html_entity_decode($config['multiple_separator']), $value));
      } else {
        $values_array[$key] = $value;
      }
    }
    
    $values_array = array_filter($values_array);

    $this->load->model('localisation/language');
    $languages = $this->model_localisation_language->getLanguages();
    
    // xml fix
    if ($this->filetype == 'xml' && isset($values_array['filter']) && !isset($values_array['attribute']['groupEn'])) {
      $values_array = $values_array['filter'];
    }
    
    foreach ($values_array as $current_key => &$value) {
      // xml fix
      if ($this->filetype == 'xml' && isset($values_array['filter']['groupEn'])) {
        $filter_group_name = $value['groupEn'];
        $filter_name = $value['nameEn'];
        
        // avoid empty tag to array conversion on some php versions
        if (is_array($filter_name)) {
          $filter_name = '';
        }
        
        foreach ($languages as $language) {
          $filter_group_name_ml[$language['code']] = !empty($value['group'.ucfirst(substr($language['code'], 0, 2))]) ? $value['group'.ucfirst(substr($language['code'], 0, 2))] : '';
          $filter_name_ml[$language['code']] = !empty($value['name'.ucfirst(substr($language['code'], 0, 2))]) ? $value['name'.ucfirst(substr($language['code'], 0, 2))] : '';
        }
      } else {
        if (strpos($value, ':') !== false) {
          $values = explode(':', $value);
        }
        
        if (!isset($values)) {
          // get column header
          $column_headers = (array) json_decode(base64_decode($config['column_headers']));
          
          if (!empty($column_headers[ $config['columns_bindings'][$field][$current_key] ])) {
            if ($this->filetype == 'xml') {
              $filter_group_name = basename($column_headers[ $config['columns_bindings'][$field][$current_key] ]);
            } else {
              $filter_group_name = $column_headers[ $config['columns_bindings'][$field][$current_key] ];
            }
            
            $filter_name = $value;
          } else {
            continue;
          }
        } else if (isset($values) && count($values) == 2) {
          $filter_group_name = $values[0];
          $filter_name = $values[1];
        } else {
          // too much parts ?
          continue;
        }
      }
      
      if ($this->simulation) {
        if (trim($filter_name)) {
          $return_values[] = $filter_group_name .' > '. $filter_name;
        }
        
        continue;
      }
      
      $filter = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "filter_description WHERE name = '" . $this->db->escape($filter_name) . "'")->row;
      
      // filter exists ?
      if (!empty($filter['filter_id'])) {
        $filter_id = $filter['filter_id'];
      }
      // not exists - create
      else {
        $filter_data = array();
        $filter_data['sort_order'] = 1;
        
        $filter_group = $this->db->query("SELECT filter_group_id FROM " . DB_PREFIX . "filter_group_description WHERE name = '" . $this->db->escape($filter_group_name) . "'")->row;
        
        // group exists - get id
        if (!empty($filter_group['filter_group_id'])) {
          $filter_data['filter_group_id'] = $filter_group['filter_group_id'];
        } 
        //  group not exists - create
        else {
          $filter_group_data = array();
          $filter_group_data['sort_order'] = 0;
          
          foreach ($languages as $language) {
            $filter_group_data['filter_group_description'][$language['language_id']] = !empty($filter_group_name_ml[$language['language_id']]) ? $filter_group_name_ml[$language['code']] : $filter_group_name;
          }
          
          $this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group` SET sort_order = '" . (int)$filter_group_data['sort_order'] . "'");

          $filter_data['filter_group_id'] = $this->db->getLastId();

          foreach ($languages as $language) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_description SET filter_group_id = '" . (int)$filter_data['filter_group_id'] . "', language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape($filter_group_data['filter_group_description'][$language['language_id']]) . "'");
          }
        }
        
        // create filter
        foreach ($languages as $language) {
          $filter_data['filter_description'][$language['language_id']] = !empty($filter_name_ml[$language['code']]) ? $filter_name_ml[$language['code']] : $filter_name;
        }
        
        $this->db->query("INSERT INTO " . DB_PREFIX . "filter SET filter_group_id = '" . (int)$filter_data['filter_group_id'] . "', sort_order = 0");

				$filter_id = $this->db->getLastId();

				foreach ($languages as $language) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter_id . "', language_id = '" . (int)$language['language_id'] . "', filter_group_id = '" . (int)$filter_data['filter_group_id'] . "', name = '" . $this->db->escape($filter_data['filter_description'][$language['language_id']]) . "'");
				}
      }
      
      // format values for product - only if not empty value
      if (trim($filter_name)) {
        $return_values[] = $filter_id;
      }
    }
    
    return $return_values;
  }
  
  protected function attributeHandler($field, &$config) {
    $return_values = $values_array = array();
    
    if (empty($config['columns'][$field])) {
      return $values_array;
    }
    
    foreach ((array) $config['columns'][$field] as $key => $value) {
      if (is_string($value) && strpos($value, '</li>') !== false) {
        $dom = new DOMDocument;
        @$dom->loadHTML($value);
        
        foreach ($dom->getElementsByTagName('li') as $node) {
          $values_array[] = $node->nodeValue;
        }
      } else if (is_array($value)) {
        $values_array = array_merge($values_array, $value);
      } else if (!empty($config['multiple_separator'])) {
        $values_array = array_merge($values_array, explode(@html_entity_decode($config['multiple_separator']), $value));
      } else {
        $values_array[$key] = $value;
      }
    }
    $values_array = array_filter($values_array);

    $this->load->model('localisation/language');
    $languages = $this->model_localisation_language->getLanguages();
    
    // xml fix
    if ($this->filetype == 'xml' && isset($values_array['attribute']) && !isset($values_array['attribute']['groupEn'])) {
      $values_array = $values_array['attribute'];
    }
    
    foreach ($values_array as $current_key => &$value) {
      // xml fix
      if ($this->filetype == 'xml' && isset($values_array['attribute']['groupEn'])) {
        $attribute_group_name = $value['groupEn'];
        $attribute_name = $value['nameEn'];
        $attribute_text = $value['valueEn'];
        
        // avoid empty tag to array conversion on some php versions
        if (is_array($attribute_text)) {
          $attribute_text = '';
        }
        
        foreach ($languages as $language) {
          $attribute_group_name_ml[$language['code']] = !empty($value['group'.ucfirst(substr($language['code'], 0, 2))]) ? $value['group'.ucfirst(substr($language['code'], 0, 2))] : '';
          $attribute_name_ml[$language['code']] = !empty($value['name'.ucfirst(substr($language['code'], 0, 2))]) ? $value['name'.ucfirst(substr($language['code'], 0, 2))] : '';
          $attribute_text_ml[$language['code']] = !empty($value['value'.ucfirst(substr($language['code'], 0, 2))]) ? $value['value'.ucfirst(substr($language['code'], 0, 2))] : '';
        }
      } else {
        if (strpos($value, ':') !== false) {
          $values = explode(':', $value);
        }
        
        if (!isset($values)) {
          // get column header
          $column_headers = (array) json_decode(base64_decode($config['column_headers']));
          
          if (!empty($column_headers[ $config['columns_bindings'][$field][$current_key] ])) {
            $attribute_group_name = 'Default';
            if ($this->filetype == 'xml') {
              $attribute_name = basename($column_headers[ $config['columns_bindings'][$field][$current_key] ]);
            } else {
              $attribute_name = $column_headers[ $config['columns_bindings'][$field][$current_key] ];
            }
            
            $attribute_text = $value;
          } else {
            continue;
          }
        } else if (isset($values) && count($values) == 2) {
          $attribute_group_name = 'Default';
          $attribute_name = $values[0];
          $attribute_text = $values[1];
        } else if (count($values) == 3) {
          $attribute_group_name = $values[0];
          $attribute_name = $values[1];
          $attribute_text = $values[2];
        } else {
          // too much parts ?
          continue;
        }
      }
      
      if (!empty($config['filters_from_attributes'])) {
        $config['columns']['product_filter'][] = $attribute_name .':'. $attribute_text;
      }
      
      if ($this->simulation) {
        if (trim($attribute_text)) {
          $return_values[] = $attribute_group_name .' > '. $attribute_name .' > '. $attribute_text;
        }
        
        continue;
      }
      
      $attr = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "attribute_description WHERE name = '" . $this->db->escape($attribute_name) . "'")->row;
      
      // attribute exists ?
      if (!empty($attr['attribute_id'])) {
        $attribute_id = $attr['attribute_id'];
      }
      // not exists - create
      else {
        $attr_data = array();
        $attr_data['sort_order'] = 1;
        
        $attr_group = $this->db->query("SELECT attribute_group_id FROM " . DB_PREFIX . "attribute_group_description WHERE name = '" . $this->db->escape($attribute_group_name) . "'")->row;
        
        // group exists - get id
        if (!empty($attr_group['attribute_group_id'])) {
          $attr_data['attribute_group_id'] = $attr_group['attribute_group_id'];
        } 
        //  group not exists - create
        else {
          $attr_group_data = array();
          $attr_group_data['sort_order'] = 1;
          
          foreach ($languages as $language) {
            $attr_group_data['attribute_group_description'][$language['language_id']]['name'] = !empty($attribute_group_name_ml[$language['code']]) ? $attribute_group_name_ml[$language['code']] : $attribute_group_name;
          }
          
          $this->load->model('catalog/attribute_group');
          $attr_data['attribute_group_id'] = $this->model_catalog_attribute_group->addAttributeGroup($this->request->clean($attr_group_data));
        }
        
        // create attribute
        foreach ($languages as $language) {
          $attr_data['attribute_description'][$language['language_id']]['name'] = !empty($attribute_name_ml[$language['code']]) ? $attribute_name_ml[$language['code']] : $attribute_name;
        }
        
        $this->load->model('catalog/attribute');
        $attribute_id = $this->model_catalog_attribute->addAttribute($attr_data);
      }
      
      // format values for product - only if not empty value
      if (trim($attribute_text)) {
        $return_values[$current_key]['attribute_id'] = $attribute_id;
      
        foreach ($languages as $language) {
          $return_values[$current_key]['product_attribute_description'][$language['language_id']]['text'] = !empty($attribute_text_ml[$language['code']]) ? $attribute_text_ml[$language['code']] : $attribute_text;
        }
      }
      /*
      $value = array(
        'attribute_id' => $attribute_id,
      );
      
      foreach ($languages as $language) {
        $value['product_attribute_description'][$language['language_id']]['text'] = !empty($attribute_text_ml[$language['code']]) ? $attribute_text_ml[$language['code']] : $attribute_text;
      }
      */
    }
    
    return $return_values;
  }
  
  protected function imageHandler($field, &$config, $multiple = false) {
    $image_array = array();
    
    if (empty($config['columns'][$field])) {
      if ($multiple) {
        return $image_array;
      } else {
        return '';
      }
    }
    
    $sort_order = 0;
    
    foreach ((array) $config['columns'][$field] as $images) {
      if (!empty($config['multiple_separator']) && is_string($images)) {
        $images = explode(@html_entity_decode($config['multiple_separator']), $images);
      }
      
      if ($multiple && is_array($images) && $config['columns']['image'] == $images[0]) {
        array_shift($images);
      }
        
      foreach ((array) $images as $image) {
        if ($config['image_download'] && $image) {
          if (substr($image, 0, 2) == '//') {
            $image = 'http:' . $image;
          }
          
          $file_info = pathinfo(parse_url(trim($image), PHP_URL_PATH));
          
          // if no extension, get it by mime
          if (empty($file_info['extension'])) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, trim($image));
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_NOBODY, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_exec($ch);
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            
            switch($contentType) {
              case 'image/bmp': $file_info['extension'] = 'bmp'; break;
              case 'image/gif': $file_info['extension'] = 'gif'; break;
              case 'image/jpeg': $file_info['extension'] = 'jpg'; break;
              case 'image/pipeg': $file_info['extension'] = 'jfif'; break;
              case 'image/tiff': $file_info['extension'] = 'tif'; break;
              case 'image/png': $file_info['extension'] = 'png'; break;
              default: $file_info['extension'] = '';
            }
          }

          if (substr_count($file_info['dirname'], 'http')) {
            // incorrect array extract
           if (!$multiple) {
              return '';
            } else {
              continue;
            }
          }
          
          if (!in_array(strtolower($file_info['extension']), array('gif', 'jpg', 'jpeg', 'png'))) {
            $this->session->data['obui_log'][] = array(
              'row' => $this->session->data['obui_current_line'],
              'status' => 'error',
              'title' => $this->language->get('warning'),
              'msg' => $this->language->get('warning_incorrect_image_format') . ' ' . str_replace(' ', '%20', $image),
            );
            
            continue;
          }
          
          if ($this->simulation) {
            if (!$multiple) {
              /* Now handled before
              if (!in_array(strtolower($file_info['extension']), array('gif', 'jpg', 'jpeg', 'png'))) {
                return array('error_format', $image);
              }*/
              return $image;
            } else {
              /* Now handled before
              if (!in_array(strtolower($file_info['extension']), array('gif', 'jpg', 'jpeg', 'png'))) {
                $image_array[] = 'error_format';
                continue;
              }*/
              $image_array[] = $image;
              continue;
            }
          }
          
          // detect if image is on actual server
          if (strpos($image, 'http') === false) {
            $filename = trim($image);
          
            if (!$multiple) {
              return $filename;
            } else {
              if (!empty($filename)) {
                $image_array[] = array(
                  'image' => $filename,
                  'sort_order' => $sort_order++,
                );
              }
              continue;
            }
          }
          
          if (version_compare(VERSION, '2', '>=')) {
            $path = 'catalog/';
            //$http_path = HTTP_CATALOG . 'image/catalog/';
          } else {
            $path = 'data/';
            //$http_path = HTTP_CATALOG . 'image/data/';
          }
          
          if (trim($config['image_location'], '/\\')) {
            $path .= trim($config['image_location'], '/\\') . '/';
          }
          
          if ($config['image_keep_path'] && trim($file_info['dirname'], '/\\')) {
            $path .= trim($file_info['dirname'], '/\\') . '/';
          }
          
          if (!is_dir(DIR_IMAGE . $path)) {
            mkdir(DIR_IMAGE . $path, 0777, true);
          }
          
          $filename = $path . urldecode($file_info['filename']) . '.' . $file_info['extension'];
          
          if ($config['image_exists'] == 'rename') {
            $x = 1;
            while (file_exists(DIR_IMAGE . $filename)) {
              $filename = $path . urldecode($file_info['filename']) . '-' . $x++ . '.' . $file_info['extension'];
            }
          } else if ($config['image_exists'] == 'keep' && file_exists(DIR_IMAGE . $filename)) {
            // image skipped
            if (!$multiple) {
              return $filename;
            } else {
              $image_array[] = array(
                'image' => $filename,
                'sort_order' => $sort_order++,
              );
              
              continue;
            }
          }
          
          // copy image, replace space chars for compatibility with copy()
          // if (!@copy(trim(str_replace(' ', '%20', $image)), DIR_IMAGE . $filename)) {
          $copyError = $this->copy_image(trim(str_replace(' ', '%20', $image)), DIR_IMAGE . $filename);
          
          if ($copyError !== true) {
            if (defined('GKD_CRON')) {
              $this->cron_log($this->session->data['obui_current_line'] . ' - ' . $copyError);
            } else {
              $this->session->data['obui_log'][] = array(
                'row' => $this->session->data['obui_current_line'],
                'status' => 'error',
                'title' => $this->language->get('warning'),
                'msg' => $copyError,
              );
            }
          }
          
        } else {
          // get direct value
          $filename = trim($image);
          
          if ($this->simulation) {
            if (!$multiple) {
              return $filename;
            } else {
              if (!empty($filename)) {
                $image_array[] = $filename;
              }
              continue;
            }
          }
        }
        
        // one field only, directly return first value
        if (!$multiple) {
          return $filename;
        }
        
        if (!empty($filename)) {
          $image_array[] = array(
            'image' => $filename,
            'sort_order' => $sort_order++,
          );
        }
      }
    }
    
    return $image_array;
  }
  
  private function copy_image($url, $path) {
    // try copy method first
    if (@copy($url, $path)) {
      return true;
    }
    
    $url = str_replace('https', 'http', $url);
    
    // if not working try curl method
    $fp = fopen ($path, 'w+');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

    // write data to local file
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);

    if (filesize($path) > 0) {
      return true;
    } else {
      if (!is_writable(dirname($path))) {
        return $this->language->get('warning') . ' - ' . 'Copy failed, make sure the image path is writable: ' . dirname($path);
      }
      return $this->language->get('warning_remote_image_not_found') . str_replace(' ', '%20', $url);
      return 'Image download failed';
    }
  }
  /*
  protected function productGroupHandler($field, &$config) {
    if (empty($config['columns'][$field])) {
      return array();
    }
    
    $array_values = array();
    
    foreach ((array) $config['columns'][$field] as $products) {
      if (!empty($config['multiple_separator'])) {
        $products = explode(@html_entity_decode($config['multiple_separator']), $products);
      }
        
      foreach ((array) $products as $value) {
        if (!$value) {
          continue;
        } else {
          if (is_numeric($value)) {
            $array_values[] = $value;
            continue;
          }
          
          $value = $this->request->clean(trim($value));
          
          $query = $this->db->query("SELECT product_group_id FROM " . DB_PREFIX . "product_group_description WHERE name = '" . $this->db->escape($value) . "'")->row;

          if (!empty($query['product_group_id'])) {
            $array_values[] = $query['product_group_id'];
          } 
        }
      }
    }
    
    return $array_values;
  }
  */
  
  protected function floatValue($val) {
    if (strpos($val, ',')) {
      return floatval(str_replace(',', '.', str_replace('.', '', $val)));
    }
    
    return $val;
  }
  
  protected function orderStatusHandler($field, &$config) {
    $value = $config['columns'][$field];

    if (is_numeric($value)) {
      // numeric, treat as status id
      if ($this->simulation) {
        $value = $this->getOrderStatusName($value);
      }
    } else if (is_string($value)) {
      // string, get status id
      $value = $this->getOrderStatusIdFromName($value);
    }
    
    return $value;
  }
  
  protected function booleanHandler($field, &$config) {
    // handle '', '0', 0
    if (empty($config['columns'][$field])) {
      return 0;
    }
    
    $value = $config['columns'][$field];

    // handle textual values
    if (is_string($value)) {
      switch (strtolower($value)) {
        case 'disabled':
        case 'inactive':
        case 'false':
        case 'off':
        case 'no':
        case '0':
          return 0; break;
          
        case 'enabled':
        case 'active':
        case 'true':
        case 'on':
        case 'yes':
        case '1':
          return 1; break;
      }
    }
    
    // in case not catched return value
    return $value;
  }
  
  protected function manufacturerHandler(&$config) {
    if (empty($config['columns']['manufacturer_id'])) {
      return '';
    }
    if (is_numeric($config['columns']['manufacturer_id'])) {
      $query = $this->db->query("SELECT DISTINCT manufacturer_id, name FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . $this->db->escape($this->request->clean($config['columns']['manufacturer_id'])) . "'")->row;
      
      if ($this->simulation) {
        if (!empty($query['name'])) {
          if ($query['manufacturer_id'] && !empty($config['delete']) && $config['delete'] == 'missing_brand') {
            $this->session->data['obui_delete_brand'][] = $query['manufacturer_id'];
          }
          return '['.$query['manufacturer_id'].'] '.$query['name'];
        } else {
          return $this->language->get('not_found');
        }
      } else {
        if (!empty($query['manufacturer_id']) && !empty($config['delete']) && $config['delete'] == 'missing_brand') {
          $this->session->data['obui_delete_brand'][] = $query['manufacturer_id'];
        }
        if (!empty($query['manufacturer_id'])) {
          return $query['manufacturer_id'];
        }
      }
    }
    
    $query = $this->db->query("SELECT DISTINCT manufacturer_id, name FROM " . DB_PREFIX . "manufacturer WHERE name = '" . $this->db->escape($this->request->clean($config['columns']['manufacturer_id'])) . "'")->row;

    $this->load->model('localisation/language');
    $languages = $this->model_localisation_language->getLanguages();
    
    if (!empty($query['manufacturer_id'])) {
      if ($query['manufacturer_id'] && !empty($config['delete']) && $config['delete'] == 'missing_brand') {
        $this->session->data['obui_delete_brand'][] = $query['manufacturer_id'];
      }
      
      if ($this->simulation) {
        return '['.$query['manufacturer_id'].'] '.$query['name'];
      } else {
        return $query['manufacturer_id'];
      }
    } else if (!empty($config['manufacturer_create'])) {
      // manufacturer does not exists, create it ?
      $manufacturer_data = array(
        'name' => $config['columns']['manufacturer_id'],
        'sort_order' => 0,
        'manufacturer_store' => isset($config['columns']['product_store']) ? $config['columns']['product_store'] : array(0),
        'keyword' => $this->filter_seo($config['columns']['manufacturer_id']),
      );
      
      foreach ($languages as $language) {
        $manufacturer_data['manufacturer_description'][$language['language_id']] = array(
         'description' => '',
         'meta_title' => $config['columns']['manufacturer_id'],
         'meta_description' => '',
         'meta_keyword' => '',
        );
      }
      
      $this->load->model('catalog/manufacturer');
      
      if (!$this->simulation) {
        $manufacturer_id = $this->model_catalog_manufacturer->addManufacturer($this->request->clean($manufacturer_data));
      } else {
        return '['.$this->language->get('new').'] '.$config['columns']['manufacturer_id'];
      }
      
      return $manufacturer_id;
    }
    
    return '';
  }
  
  protected function parentCategoryHandler($field, &$config) {
    if (empty($config['columns'][$field])) {
      if ($this->simulation) {
        return $this->language->get('text_none');
      } else {
        return 0;
      }
    }
    
    if (is_string($config['columns'][$field]) && !empty($config['col_binding'][$config['columns'][$field]])) {
     if ($this->simulation) {
        $query = $this->db->query("SELECT name, category_id FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int) $config['col_binding'][$config['columns'][$field]] . "'")->row;
        if (!empty($query['category_id'])) {
          return '['.$query['category_id'].'] ' . $query['name'];
        } else {
          $this->session->data['obui_processed']['error']++;
    
          $this->session->data['obui_log'][] = array(
            'row' => $this->session->data['obui_current_line'],
            'status' => 'error',
            'title' => $this->language->get('warning'),
            'msg' => $this->language->get('warning_category_id'),
          );
        }
      } else {
        return $config['col_binding'][$config['columns'][$field]];
      }
    } else if (!empty($config['col_binding_exclusive'])) {
      return 0;
    }
      
    $this->load->model('catalog/category');
      
    $this->load->model('localisation/language');
    $languages = $this->model_localisation_language->getLanguages();
    
    $parent_id = 0;
    $simu_text = '';
    
    if (!empty($config['subcategory_separator'])) {
      $subcategories = explode(@html_entity_decode($config['subcategory_separator']), $config['columns'][$field]);
    } else {
      $subcategories = (array) $config['columns'][$field];
    }
    
    foreach ($subcategories as $cat_name) {
      $searchById = '';
      
      if (is_numeric($cat_name) && ($cat_name == (int)$cat_name)) {
        $searchById = "(c.category_id = '" . (int) $cat_name . "') OR ";
      }
      
      //$cat_exists = $this->db->query("SELECT name, category_id FROM " . DB_PREFIX . "category_description WHERE name = '" . $this->db->escape(trim($cat_name)) . "' AND parent_id = '" . (int) $parent_id . "'")->row;
      $cat_exists = $this->db->query("SELECT cd.category_id, cd.name FROM " . DB_PREFIX . "category_description cd LEFT JOIN " . DB_PREFIX . "category c ON cd.category_id = c.category_id WHERE " . $searchById . "(cd.name = '" . $this->db->escape(trim($this->request->clean($cat_name))) . "' AND c.parent_id = '" . (int) $parent_id . "')")->row;
      
      if (empty($cat_exists['category_id'])) {
        $cat_data = array(
          'parent_id' => $parent_id,
          'column' => 3,
          'top' => 1,
          'category_store' => $config['columns']['category_store'],
          'sort_order' => 0,
          'status' => 1,
        );
        
        foreach ($languages as $language) {
          $cat_data['category_description'][$language['language_id']] = array(
           'name' => trim($cat_name),
           'description' => '',
           'meta_title' => trim($cat_name),
           'meta_description' => '',
           'meta_keyword' => '',
           'seo_h1' => '',
           'seo_keyword' => '',
          );
        }
        
        if (!$this->simulation) {
          $parent_id = $this->model_catalog_category->addCategory($this->request->clean($cat_data));
        } else {
          $simu_text .= $simu_text ? ' &gt; ' : '';
          $simu_text .= '['.$this->language->get('new').'] ' . $cat_name;
        }
      } else {
        $parent_id = $cat_exists['category_id'];
        
        if ($this->simulation) {
          $simu_text .= $simu_text ? ' &gt; ' : '';
          $simu_text .= '['.$cat_exists['category_id'].'] ' . $cat_exists['name'];
        }
      }
    }
    
    return $this->simulation ? $simu_text : $parent_id;
    
    /*
    $parent_query = $this->db->query("SELECT category_id, name FROM " . DB_PREFIX . "category_description WHERE name = '" . $this->db->escape($config['columns'][$field]) . "'")->row;
    
    if (!empty($parent_query['category_id'])) {
      if ($this->simulation) {
        return $parent_query['name'];
      } else {
        return $parent_query['category_id'];
      }
    } else {
      // category does not exists, create it ?
      
    }
    
    return 0;
    */
  }
  
  protected function categoryHandler($field, &$config) {
    $values = array();
    
    if (!isset($config['columns'][$field])) {
      return $values;
    }
      
    foreach ((array) $config['columns'][$field] as $categories) {
      # custom_category_handler
      
      if (!$categories) {
        continue;
      }
      
      if (is_string($categories) && !empty($config['col_binding'][md5($categories)])) {
       if ($this->simulation) {
          $query = $this->db->query("SELECT name, category_id FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int) $config['col_binding'][md5($categories)] . "'")->row;
          
          if (!empty($query['category_id'])) {
            $values[] = '['.$query['category_id'].'] ' . $query['name'];
          } else {
            $this->session->data['obui_processed']['error']++;
      
            $this->session->data['obui_log'][] = array(
              'row' => $this->session->data['obui_current_line'],
              'status' => 'error',
              'title' => $this->language->get('warning'),
              'msg' => $this->language->get('warning_category_id'),
            );
          }
        } else {
          $values[] = $config['col_binding'][md5($categories)];
        }
        
        continue;
      } else if (!empty($config['col_binding_exclusive'])) {
        continue;
      }
      
      if (is_string($categories) && !empty($config['multiple_separator'])) {
        $categories = explode(@html_entity_decode($config['multiple_separator']), $categories);
      }
      
      $this->load->model('localisation/language');
      $languages = $this->model_localisation_language->getLanguages();
      
      // xml fix
      if ($this->filetype == 'xml' && isset($categories['category']) && !isset($categories['category']['nameEn'])) {
        $categories = $categories['category'];
      }
      
      if (!empty($config['include_subcat'])) {
        $addCategories = array();
        
        foreach ((array) $categories as $category) {
          if (!empty($config['subcategory_separator'])) {
            $subcategories = explode(@html_entity_decode($config['subcategory_separator']), $category);
            $subcategories = array_map('trim', $subcategories);
            $subcategories = array_filter($subcategories);
            
            if ($config['include_subcat'] == 'parent') {
              array_pop($subcategories);
              
              $subcat = implode(@html_entity_decode($config['subcategory_separator']), $subcategories);
            
              if ($subcat) {
                $addCategories[] = $subcat;
              }
            } else if ($config['include_subcat'] == 'all') {
              while ($subcategories) {
                array_pop($subcategories);
                
                $subcat = implode(@html_entity_decode($config['subcategory_separator']), $subcategories);
              
                if ($subcat) {
                  $addCategories[] = $subcat;
                }
              }
            }
          }
          
        }
        
        $categories = array_merge((array) $categories, $addCategories);
      }
      
      $categories = array_unique((array) $categories);
      
      foreach ($categories as $category) {
        $full_categories_ml = array();
        
        // xml fix
        if ($this->filetype == 'xml' && isset($category['nameEn'])) {
          foreach ($languages as $language) {
            $category_ml[$language['code']] = !empty($value['name'.ucfirst(substr($language['code'], 0, 2))]) ? $value['name'.ucfirst(substr($language['code'], 0, 2))] : '';
            
            if (!empty($config['subcategory_separator'])) {
              $subcategories_ml[$language['code']] = explode(@html_entity_decode($config['subcategory_separator']), $category_ml[$language['code']]);
            } else {
              $subcategories_ml[$language['code']] = (array) $category_ml[$language['code']];
            }
            
            $full_categories_ml = $subcategories_ml;
          }
          
          $category = $category['nameEn'];
        }
        /*
        if (isset($category['nameEn'])) {
          $categoryFr = $category['nameFr'];
          if (!empty($config['subcategory_separator'])) {
            $subcategoriesFr = explode(@html_entity_decode($config['subcategory_separator']), $categoryFr);
          } else {
            $subcategoriesFr = (array) $categoryFr;
          }
          $full_categoriesFr = $subcategoriesFr;
          
          $category = $category['nameEn'];
        }
        */
        
        # else we treat as csv
        // direct cat id 
        if (ctype_digit($category) && $category > 0) {
          if ($this->simulation) {
            $query = $this->db->query("SELECT name, category_id FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int) $category . "'")->row;
            if (!empty($query['category_id'])) {
              $values[] = '['.$query['category_id'].'] ' . $query['name'];
            } else {
              $this->session->data['obui_processed']['error']++;
        
              $this->session->data['obui_log'][] = array(
                'row' => $this->session->data['obui_current_line'],
                'status' => 'error',
                'title' => $this->language->get('warning'),
                'msg' => $this->language->get('warning_category_id'),
              );
            }
          } else {
            $values[] = $category;
          }
          
          continue;
        }
        
        if (!empty($config['subcategory_separator']) && is_string($category)) {
          $subcategories = explode(@html_entity_decode($config['subcategory_separator']), $category);
          $subcategories = array_map('trim', $subcategories);
          $subcategories = array_filter($subcategories);
        } else {
          $subcategories = (array) $category;
        }
        
        $full_categories = $subcategories;
        
        $cat_name = array_pop($subcategories);
        
        $parent_name = $parent_lvl2_name = $parent_lvl3_name = false;
        
        if (count($subcategories)) {
          $parent_name = array_pop($subcategories);
        }
        
        if (count($subcategories)) {
          $parent_lvl2_name = array_pop($subcategories);
        }
        
        if (count($subcategories)) {
          $parent_lvl3_name = array_pop($subcategories);
        }
        
        // 2 parents levels detection, then 1, then 0
        if (!empty($parent_lvl3_name)) {
          $query = $this->db->query("SELECT cd.name, c.category_id FROM " . DB_PREFIX . "category_description cd LEFT JOIN " . DB_PREFIX . "category c ON cd.category_id = c.category_id LEFT JOIN " . DB_PREFIX . "category_description pcd ON pcd.category_id = c.parent_id LEFT JOIN " . DB_PREFIX . "category pc ON pc.category_id = pcd.category_id LEFT JOIN " . DB_PREFIX . "category_description ppcd ON ppcd.category_id = pc.parent_id LEFT JOIN " . DB_PREFIX . "category ppc ON ppc.category_id = ppcd.category_id LEFT JOIN " . DB_PREFIX . "category_description pppcd ON pppcd.category_id = ppc.parent_id WHERE cd.name = '" . $this->db->escape(trim($this->request->clean($cat_name))) . "' AND pcd.name = '" . $this->db->escape(trim($this->request->clean($parent_name))) . "' AND ppcd.name = '" . $this->db->escape(trim($this->request->clean($parent_lvl2_name))) . "' AND pppcd.name = '" . $this->db->escape(trim($this->request->clean($parent_lvl3_name))) . "' GROUP BY cd.category_id")->rows;
        } else if (!empty($parent_lvl2_name)) {
          $query = $this->db->query("SELECT cd.name, c.category_id FROM " . DB_PREFIX . "category_description cd LEFT JOIN " . DB_PREFIX . "category c ON cd.category_id = c.category_id LEFT JOIN " . DB_PREFIX . "category_description pcd ON pcd.category_id = c.parent_id LEFT JOIN " . DB_PREFIX . "category pc ON pc.category_id = pcd.category_id LEFT JOIN " . DB_PREFIX . "category_description ppcd ON ppcd.category_id = pc.parent_id WHERE cd.name = '" . $this->db->escape(trim($this->request->clean($cat_name))) . "' AND pcd.name = '" . $this->db->escape(trim($this->request->clean($parent_name))) . "' AND ppcd.name = '" . $this->db->escape(trim($this->request->clean($parent_lvl2_name))) . "' GROUP BY cd.category_id")->rows;
        } else if (!empty($parent_name)) {
          $query = $this->db->query("SELECT cd.name, c.category_id FROM " . DB_PREFIX . "category_description cd LEFT JOIN " . DB_PREFIX . "category c ON cd.category_id = c.category_id LEFT JOIN " . DB_PREFIX . "category_description pcd ON pcd.category_id = c.parent_id WHERE cd.name = '" . $this->db->escape(trim($this->request->clean($cat_name))) . "' AND pcd.name = '" . $this->db->escape(trim($this->request->clean($parent_name))) . "' GROUP BY cd.category_id")->rows;
        } else {
          $query = $this->db->query("SELECT name, category_id FROM " . DB_PREFIX . "category_description WHERE name = '" . $this->db->escape(trim($this->request->clean($cat_name))) . "' GROUP BY category_id")->rows;
        }
        
        if (count($query) === 1) {
          if ($this->simulation) {
            $values[] = '['.$query[0]['category_id'].'] ' . implode(' > ', $full_categories);
          } else {
            $values[] = $query[0]['category_id'];
          }
          /* no more useful, filtered by query
        } else if (!empty($parent_name) && count($query) > 1) {
          foreach ($query as $row) {
            $parent_query = $this->db->query("SELECT name FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int) $row['category_id'] . "'")->row;

            if (!empty($parent_query['name']) && trim($parent_query['name']) == trim($this->request->clean($parent_name))) {
              if ($this->simulation) {
                $values[] = '['.$row['category_id'].'] ' . implode(' > ', $full_categories);
              } else {
                $values[] = $row['category_id'];
              }
            }
          }
          */
        } else if (!empty($config['category_create'])) {
          // category does not exists, create it ?
          $this->load->model('catalog/category');
          
          $parent_id = 0;
          
          foreach ($full_categories as $cat_name) {
            $cat_name_ml = array();
            
            // xml fix
            foreach ($full_categories_ml as $lang_id => $cat) {
              $cat_name_ml[$lang_id] = trim(array_shift($cat));
            }
            
            /*
            if (isset($full_categoriesFr)) {
              $cat_name_ml['fr-fr'] = trim(array_shift($full_categoriesFr));
            }*/
            
            $cat_exists = $this->db->query("SELECT cd.category_id FROM " . DB_PREFIX . "category_description cd LEFT JOIN " . DB_PREFIX . "category c ON cd.category_id = c.category_id WHERE name = '" . $this->db->escape(trim($this->request->clean($cat_name))) . "' AND c.parent_id = '".(int) $parent_id."'")->row;
            
            if (empty($cat_exists['category_id'])) {
              $cat_data = array(
                'parent_id' => $parent_id,
                'column' => 3,
                'top' => 1,
                'sort_order' => 0,
                'category_store' => isset($config['columns']['product_store']) ? $config['columns']['product_store'] : array(0),
                'status' => 1,
                'keyword' => $this->urlify($cat_name),
              );
              
              foreach ($languages as $language) {
                $cat_data['category_description'][$language['language_id']] = array(
                 'name' => !empty($cat_name_ml[$language['code']]) ? $cat_name_ml[$language['code']] : trim($cat_name),
                 'description' => '',
                 'meta_title' => !empty($cat_name_ml[$language['code']]) ? $cat_name_ml[$language['code']] : trim($cat_name),
                 'meta_description' => '',
                 'meta_keyword' => '',
                 'seo_h1' => '',
                 'seo_keyword' => !empty($cat_name_ml[$language['code']]) ? $this->urlify($cat_name_ml[$language['code']]) : $this->urlify($cat_name),
                );
              }
              
              if (!$this->simulation) {
                $parent_id = $this->model_catalog_category->addCategory($this->request->clean($cat_data));
              }
            } else {
              $parent_id = $cat_exists['category_id'];
            }
          }
          
          // last id is assigned category
          if ($this->simulation) {
            $values[] = '['.$this->language->get('new').'] ' . implode(' > ', $full_categories);
          } else {
            $values[] = $parent_id;
          }
        }
      }
    }
    
    return array_unique($values);
  }
  
  protected function populate_extra_func(&$config, &$line) {
    if (!empty($config['extra_func'])) {
      foreach ($config['extra_func'] as &$extra_funcs) {
        foreach ($extra_funcs as $func_type => &$func) {
          if ($func['field'] !== '' && isset($line[$func['field']]) && in_array($func_type, array('skip'))) {
            if (isset($func['fieldval']) && $func['fieldval'] !== '' && isset($line[$func['fieldval']])) {
              $func['value'] = $line[$func['fieldval']];
            }
          }
        }
      }
    }
  }
  
  protected function populate_fields(&$config, &$line) {
    // populate extra functions
    if (!empty($config['extra_func'])) {
      foreach ($config['extra_func'] as &$extra_funcs) {
        foreach ($extra_funcs as $func_type => &$func) {
          if ($func['field'] !== '' && isset($line[$func['field']])) {
            $init_value = $line[$func['field']];
            
            if (!empty($func['target'])) {
              $target = $func['target'];
            } else {
              $target = $func['field'];
            }
            
            if (isset($func['fieldval']) && $func['fieldval'] !== '' && isset($line[$func['fieldval']])) {
              $value = $line[$func['fieldval']];
            } else {
              $value = isset($func['value']) ? $func['value'] : '';
            }
            
            // Math
            if ($func_type == 'add') {
              $line[$target] = (float) $value + (float) $init_value;
            } else if ($func_type == 'subtract') {
              $line[$target] = (float) $value - (float) $init_value;
            } else if ($func_type == 'multiply') {
              $line[$target] = (float) $value * (float) $init_value;
            } else if ($func_type == 'divide') {
                $line[$target] = (float) $init_value / (float) $value;
            } else if ($func_type == 'round') {
              $line[$target] = round((float) $init_value, (int) $value);
              
            // String
            } else if ($func_type == 'uppercase') {
              $line[$target] = mb_strtoupper($init_value);
            } else if ($func_type == 'lowercase') {
              $line[$target] = mb_strtolower($init_value);
            } else if ($func_type == 'ucfirst') {
              $line[$target] = ucfirst($init_value);
            } else if ($func_type == 'ucwords') {
              $line[$target] = ucwords($init_value);
            } else if ($func_type == 'prepend') {
              $line[$target] = $value . $init_value;
            } else if ($func_type == 'append') {
              $line[$target] = $init_value . $value;
            } else if ($func_type == 'replace') {
              $line[$target] = str_replace($value, (isset($func['value2']) ? $func['value2'] : ''), $init_value);
            } else if ($func_type == 'remove') {
              $line[$target] = str_replace($value, '', $init_value);
            } else if ($func_type == 'substr') {
              $line[$target] = mb_substr($init_value, 0, (int)$value);
            } else if ($func_type == 'urlify') {
               if (!empty($func['ascii'])) {
                 $line[$target] = $this->urlify($init_value, $func['ascii']);
               } else {
                 $line[$target] = $this->urlify($init_value);
               }
              
            // Regex
            } else if ($func_type == 'regex') {
              preg_match('/'.$value.'/', $init_value, $matches);
              $line[$target] = isset($matches[1]) ? $matches[1] : '';
            } else if ($func_type == 'regex_remove') {
              $line[$target]= preg_replace('/'.$value.'/', '', $init_value);
            } else if ($func_type == 'regex_replace') {
              $line[$target] = preg_replace('/'.$value.'/', $func['value2'], $init_value);
            
            // Web
            } else if ($func_type == 'remote_content') {
              if ($init_value) {
                $line[$target] = file_get_contents($init_value);
              }
            
            // HTML
            } else if ($func_type == 'nl2br') {
              $line[$target] = nl2br($init_value);
            } else if ($func_type == 'strip_tags') {
              $line[$target] = strip_tags($init_value);
            }
            
            # custom_extra_function_handler
            
            // save value for use in process
            $func['field'] = $line[$func['field']];
          }
        }
      }
    }
    
    
    // recursive populate
    array_walk_recursive($config['columns'], array($this,'array_walk_populate'), $line);
    
    // assign default values
    if (!empty($config['defaults'])) {
      foreach ($config['defaults'] as $key => &$val) {
        if (((!isset($config['item_exists']) || (isset($config['item_exists']) && $config['item_exists'] != 'soft_update')) && !isset($config['columns'][$key])) || (isset($config['columns'][$key]) && $config['columns'][$key] === '')) {
          $config['columns'][$key] = is_string($val) ? trim($val) : $val;
        }
      }
    }
  }
  
  protected function categoryExists($values, $parent_id) {
    foreach ((array) $values as $value) {
      if (empty($value['name'])) {
        continue;
      }
      
      if (strpos($parent_id, ']') !== false) {
        $parent_id = str_replace('[', '', strstr($parent_id, ']', true));
      }
      
      $cat_exists = $this->db->query("SELECT cd.category_id FROM " . DB_PREFIX . "category_description cd LEFT JOIN " . DB_PREFIX . "category c ON cd.category_id = c.category_id WHERE cd.name = '" . $this->db->escape(trim($this->request->clean($value['name']))) . "' AND c.parent_id = '" . (int) $parent_id . "'")->row;
      
      if (!empty($cat_exists['category_id'])) {
        return $cat_exists['category_id'];
      }
    }

    return false;
  }
  
  protected function itemExists($type, $field, &$data) {
    $values = array();
    $desc_field = '';
    
    if (($field == 'name' || $field == 'title') && !in_array($type, array('manufacturer', 'order'))) {
      foreach ($data[$type . '_description'] as $lang) {
        if (!empty($lang[$field])) {
          $values[] = $lang[$field];
        }
      }
      
      $desc_field = '_description';
    } else {
      if (!empty($data[$field])) {
        $values[] = $data[$field];
      }
    }
    
    if (empty($values)) {
      return false;
    }
    
    foreach ((array) $values as $value) {
      $query = $this->db->query("SELECT DISTINCT `".$this->db->escape($type)."_id` FROM `" . DB_PREFIX . $this->db->escape($type) . $desc_field . "` WHERE `" . $this->db->escape($field) . "` = '" . $this->db->escape(trim($this->request->clean($value))) . "'")->row;

      if (!empty($query[$type.'_id'])) {
        return $query[$type.'_id'];
      }
    }

    return false;
	}
  
  public function filter_seo($seo_kw) {
		$whitespace = '-';
		$seo_kw = mb_convert_case($seo_kw, MB_CASE_LOWER);
		$seo_kw = str_replace(' ', $whitespace, $seo_kw);
    $seo_kw = str_replace(array('"','&','&amp;','+','?','/','%','#','<','>'), '', $seo_kw);
		$seo_kw = mb_ereg_replace($whitespace.$whitespace.'+', $whitespace, $seo_kw);
    $seo_kw = trim($seo_kw, '_'.$whitespace);
		return $seo_kw;
	}
  
  protected function walk_recursive_remove(array $array) {
    foreach ($array as $k => $v) {
      if (is_array($v)) {
        $array[$k] = self::walk_recursive_remove($v);
      } else if ($v === '') {
        unset($array[$k]);
      }
    }
    
    return array_filter($array, array($this, 'filterEmptyArrays'));
  }
  
  protected function filterEmptyArrays($val) {
    return is_numeric($val) || (is_array($val) && !empty($val)) || !empty($val);
  }
  
   protected function filterEmptyPrice($val) {
    return isset($val['price']) && !empty($val['price']);
  }
  
  protected function array_walk_populate(&$val, &$key, &$line) {
    if ($val !== '') {
      if (isset($line[$val])) {
        if (is_string($line[$val]) && !empty($this->xfn_multiple_separator[$val])) {
          $val = explode($this->xfn_multiple_separator[$val], htmlspecialchars_decode(trim($line[$val])));
        } else if (is_string($line[$val])) {
          $val = htmlspecialchars_decode(trim($line[$val]));
        } else if (is_float($line[$val]) || is_int($line[$val])) {
          $val = $line[$val];
        } else if (is_array($line[$val])) {
          $val = $line[$val];
        }
      } else {
        $val = '';
      }
    }
  }
  
  protected function array_filter_column(&$val) {
    return ($val !== '');
  }
  
  public function loadFile(&$file, $filetype = '') {
    $extension = !empty($filetype) ? $filetype : strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if ($extension == 'csv') {
      $fh = fopen($file, 'r');
    } else if ($extension == 'xml') {
      $fh = new XMLReader;
      $fh->open($file);
    } else if ($extension == 'ods' || $extension == 'xlsx') {
      // Spout
      require_once DIR_SYSTEM.'library/Spout/Autoloader/autoload.php';
      
      libxml_disable_entity_loader(false);
      
      if ($extension == 'xlsx') {
        $fh = ReaderFactory::create(Type::XLSX);
      } else if ($extension == 'ods') {
        $fh = ReaderFactory::create(Type::ODS);
      }
      
      $fh->setShouldFormatDates(true);
      
      $fh->open($file);
      foreach ($fh->getSheetIterator() as $sheet) {
        break;
      }
      $fh = $sheet->getRowIterator();
    } else if ($extension == 'xls') {
      // PHPExcel
      require_once(DIR_SYSTEM.'library/PHPExcel/PHPExcel.php');
      $fh = PHPExcel_IOFactory::load($file);
    }
    
    return $fh;
  }
  
  public function initFilePosition(&$file, &$config) {
    if ($this->filetype == 'csv') {
      if (!empty($this->session->data['obui_last_position'])) {
        fseek($file, $this->session->data['obui_last_position']);
      } else if (!empty($config['last_position'])) {
        fseek($file, $config['last_position']);
      } else if (!empty($config['row_start'])) {
        if (!isset($this->session->data['obui_current_line'])) {
          $this->session->data['obui_current_line'] = 0;
        }
        
        while ($this->session->data['obui_current_line'] < $config['row_start'] -1) {
          if (!$this->getNextRow($file)) break;
        }
        
        if (!empty($config['csv_header'])) {
          $this->session->data['obui_processed']['processed'] = $this->session->data['obui_current_line']-1;
        } else {
          $this->session->data['obui_processed']['processed'] = $this->session->data['obui_current_line'];
        }
      } else {
        return !empty($config['csv_header']);
      }
    
      return false;
    } else if ($this->filetype == 'xml') {
      if (!empty($this->session->data['obui_current_line'])) {
        $i = 1;
        // search for node
        while ($file->read() && $file->name !== $this->xml_node);
        
        // and forward to current
        while ($file->name === $this->xml_node && $i <= $this->session->data['obui_current_line']) {
          $i++;
          $file->next($this->xml_node);
        }
      } else if (!empty($config['row_start'])) {
        if (!isset($this->session->data['obui_current_line'])) {
          $this->session->data['obui_current_line'] = 0;
        }
        
        while ($file->read() && $file->name !== $this->xml_node);
        
        while ($this->session->data['obui_current_line'] < $config['row_start']-1) {
           if (!$this->getNextRow($file)) break;
        }
        
        $this->session->data['obui_processed']['processed'] = $this->session->data['obui_current_line'];
      } else {
        while ($file->read() && $file->name !== $this->xml_node);
      }
      
      return false;
    } else if ($this->filetype == 'xlsx' || $this->filetype == 'ods') {
      // Spout
      if (!empty($this->session->data['obui_current_line'])) {
        $file->rewind();
        
        for ($i = 1; $i <= $this->session->data['obui_current_line']; $i++) {
          if ($file->valid()) {
            $file->next();
          } else {
            return false;
          }
        }
        /*foreach ($file as $i => $line) {if ($i > $this->session->data['obui_current_line']) {break;}}*/
      } else if (!empty($config['row_start'])) {
        $this->session->data['obui_current_line'] = $config['row_start']-1;
        
        $file->rewind();
        
        for ($i = 1; $i <= $this->session->data['obui_current_line']; $i++) {
          if ($file->valid()) {
            $file->next();
          } else {
            return false;
          }
        }
        
        if (!empty($config['csv_header'])) {
          $this->session->data['obui_processed']['processed'] = $this->session->data['obui_current_line']-1;
        } else {
          $this->session->data['obui_processed']['processed'] = $this->session->data['obui_current_line'];
        }
        
        return false;
      } else {
        $file->rewind();
        return !empty($config['csv_header']);
      }
      
      return false;
    } else if ($this->filetype == 'xls') {
      // PHPExcel
      if (empty($this->session->data['obui_current_line'])) {
        return !empty($config['csv_header']);
      }
    }
  }
  
  public function getNextRow(&$file) {
    $this->session->data['obui_current_line']++;
    
    if ($this->filetype == 'csv') {
      if (!feof($file) && $line = fgets($file)) {
        $this->session->data['obui_last_position'] = ftell($file);
        
        if (!trim($line)) {
          $this->session->data['obui_processed']['processed']++;
          return false;
        }
        
        return str_getcsv($line, $this->csv_separator);
      } else {
        return false;
      }
    } else if ($this->filetype == 'xml') {
      if ($file->name === $this->xml_node) {
        $node = new SimpleXMLElement($file->readOuterXML()); 

        $file->next($this->xml_node);
        
        return $this->XML2Array($node);
      } else {
        return false;
      }
    } else if ($this->filetype == 'xlsx' || $this->filetype == 'ods') {
      // Spout
      if ($file->valid()) {
        $values = $file->current();
        $file->next();
      } else {
        return false;
      }
      
      return $values;
    } else if ($this->filetype == 'xls') {
      // PHPExcel
      $sheet = $file->getSheet(0);
      $highestRow = $sheet->getHighestRow();
      $highestColumn = $sheet->getHighestColumn();
      $row = $this->session->data['obui_current_line'];
      
      if ($row > $highestRow) {
        return false;
      }
      
      $resrow = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, false, false);
      
      $values = $resrow[0];
      
      return $values;
    }
  }
  
  public function getTotalRows($file, $has_header = false, $xml_node, $filetype = '') {
    $extension = !empty($filetype) ? $filetype : strtolower(pathinfo($file, PATHINFO_EXTENSION));
    
    $i = 0;
    
    if ($extension == 'csv') {
      $fh = fopen($file, 'r');
      while (fgets($fh) !== false) $i++;
      
      fclose($fh);
    } else if ($extension == 'xml') {
      $xml = new XMLReader;
      $xml->open($file);
    
      // find the node name
      while ($xml->read() && $xml->name !== $xml_node);

      while ($xml->name === $xml_node) {
        $i++;
        $xml->next($xml_node);
      }
    } else if ($extension == 'ods' || $extension == 'xlsx') {
      // Spout
      require_once DIR_SYSTEM.'library/Spout/Autoloader/autoload.php';
      libxml_disable_entity_loader(false);
      
      if ($extension == 'xlsx') {
        $reader = ReaderFactory::create(Type::XLSX);
      } else if ($extension == 'ods') {
        $reader = ReaderFactory::create(Type::ODS);
      }
      
      $reader->setShouldFormatDates(true);
      
      $reader->open($file);

      foreach ($reader->getSheetIterator() as $sheet) {
        foreach ($sheet->getRowIterator() as $row) {
          $i++;
        }
      }
      
      $reader->close();
    } else if ($extension == 'xls') {
      // PHPExcel
      require_once(DIR_SYSTEM.'library/PHPExcel/PHPExcel.php');
      $objPHPExcel = PHPExcel_IOFactory::load($file);
      
      $sheet = $objPHPExcel->getSheet(0); 
      $i = $sheet->getHighestRow();
    }
    
    return $has_header ? $i-1 : $i;
  }
  
  public function XML2Array($xml, $level = 0) {
    $array = array();
    $level++;
    $m = 0;
    foreach ($xml as $key => $value) {
      if (is_object($value) && strpos(get_class($value), 'SimpleXML') !== false) {
        if ($value->count()) {
            if (isset($array[$key][$m])) $m++;
            
            $array[$key][$m] = $this->XML2Array($value, $level);
            
            if ($level < 2 && is_array($array[$key][$m])) {
              $i = 1;
              foreach ($array[$key][$m] as $sub_key => $sub_val) {
                if (isset($array[$key.'['.$m.']/'.$sub_key])) {
                  $array[$key.'['.$m.']/'.$sub_key.'['.$i++.']'] = $sub_val;
                } else {
                  $array[$key.'['.$m.']/'.$sub_key] = $sub_val;
                }
                
                if (is_array($sub_val) && isset($sub_val[0])) {
                  foreach ($sub_val as $subsub_key => $subsub_val) {
                    $array[$key.'['.$m.']/'.$sub_key.'['.$subsub_key.']'] = $subsub_val;
                  }
                }
              }
            }
          /*
          foreach ($value->children() as $ch_key => $ch_val) {
            $array[$key][$ch_key][] = $this->XML2Array($ch_val);
          }
          if (is_array($array[$key])) {
              $i = 1;
              foreach ($array[$key] as $sub_key => $sub_val) {
                if (isset($array[$key.'/'.$sub_key])) {
                  $array[$key.'/'.$sub_key.'['.$i++.']'] = $sub_val;
                } else {
                  $array[$key.'/'.$sub_key] = $sub_val;
                }
                
                if (is_array($sub_val) && isset($sub_val[0])) {
                  foreach ($sub_val as $subsub_key => $subsub_val) {
                    $array[$key.'/'.$sub_key.'['.$subsub_key.']'] = $subsub_val;
                  }
                }
              }
            }
            */
        } else {
          if (isset($array[$key])) {
            $array[$key] = (array) $array[$key];
            $array[$key][] = (string) $value;
          } else {
            $array[$key] = (string) $value;
          }
        }
        foreach ($value->attributes() as $at_key => $at_val) {
          $array[$key.'@'.$at_key] = (string) $at_val;
        }
      } else {
        $array[$key] = $value;
      }
      
    }
    
    return $array;
  }
  
  public function XML2Array__($xml) {
    $array = (array)$xml;

    if (count($array) == 0) {
      $array = (string)$xml;  
    }

    if (is_array($array)) {
      //recursive Parser
      foreach ($array as $key => $value) {
        if (is_object($value)) {
          if (strpos(get_class($value), 'SimpleXML') !== false) {
            $array[$key] = $this->XML2Array($value);
          }
          
          if (is_array($array[$key]) && !is_int($key)) {
              $i = 1;
              foreach ($array[$key] as $sub_key => $sub_val) {
                if (isset($array[$key.'/'.$sub_key])) {
                  $array[$key.'/'.$sub_key.'['.$i++.']'] = $sub_val;
                } else {
                  $array[$key.'/'.$sub_key] = $sub_val;
                }
                
                if (is_array($sub_val) && isset($sub_val[0])) {
                  foreach ($sub_val as $subsub_key => $subsub_val) {
                   $array[$key.'/'.$sub_key.'['.$subsub_key.']'] = $subsub_val;
                  }
                }
              }
            }
        } else if (is_array($value)) {
          $array[$key] = $this->XML2Array($value);
        } else {
          //$array[$key] = $this->XML2Array($value);
          $array[$key] = $value;
        }
      }
    }

    return $array;
  }
  
  public function XML2Array_old($xml) {
    $array = (array)$xml;

    if (count($array) == 0) {
      $array = (string)$xml;  
    }

    if (is_array($array)) {
      //recursive Parser
      foreach ($array as $key => $value) {
        if (is_object($value)) {
          if (strpos(get_class($value), 'SimpleXML') !== false) {
            $array[$key] = $this->XML2Array($value);
          }
        } else if (is_array($value)) {
          $array[$key] = $this->XML2Array($value);
        } else {
          //$array[$key] = $this->XML2Array($value);
          $array[$key] = $value;
        }
      }
    }

    return $array;
  }

  public function getOrderStatusName($order_status_id) {
    if (isset($this->order_statuses[$order_status_id])) {
      return $this->order_statuses[$order_status_id];
    }
    
		$row = $this->db->query("SELECT name FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT 1")->row;
    
    if (isset($row['name'])) {
      $this->order_statuses[$order_status_id] = $row['name'];
      return $row['name'];
    }
    
		return '';
	}
  
  public function getOrderStatusIdFromName($order_status) {
    if ($key = array_search($order_status, $this->order_statuses)) {
      if ($this->simulation) {
        return $this->order_statuses[$key];
      } else {
        return $key;
      }
    }
    
		$row = $this->db->query("SELECT order_status_id, name FROM " . DB_PREFIX . "order_status WHERE name = '" . $this->db->escape(trim($order_status)) . "' LIMIT 1")->row;
    
    if (isset($row['order_status_id'])) {
      $this->order_statuses[$row['order_status_id']] = $row['name'];
      if ($this->simulation) {
        return $row['name'];
      } else {
        return $row['order_status_id'];
      }
    }
    
		return '';
	}
  
  public function editProduct($product_id, $data, &$config) {
    $product_data = array('product_id', 'model', 'sku', 'upc', 'ean', 'jan', 'isbn', 'mpn', 'location', 'quantity', 'minimum', 'subtract', 'stock_status_id',
                          'date_available', 'manufacturer_id', 'shipping', 'price', 'points', 'weight', 'weight_class_id', 'length', 'width', 'height', 'length_class_id',
                          'status', 'tax_class_id', 'sort_order', 'image');
    
    if (!empty($config['extra'])) {
      $product_data = array_merge($product_data, $config['extra']);
    }
    
    $main_query = '';
    
    foreach ($product_data as $item_col) {
      if (isset($data[$item_col])) {
        $main_query .= "`" . $item_col . "` = '" . $this->db->escape($data[$item_col]) . "',";
      }
    }
    
		$this->db->query("UPDATE " . DB_PREFIX . "product SET " . $main_query . " date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");
    
    if (isset($data['product_description'])) {

      foreach ($data['product_description'] as $language_id => $desc_values) {
        $description_query = '';
        
        if ($this->config->get('mlseo_enabled') && !empty($desc_values['seo_keyword'])) {
          $seo_kw = @html_entity_decode($desc_values['seo_keyword'], ENT_QUOTES);
          
          if ($seo_kw) {
            $this->load->model('tool/seo_package');
            $seo_kw = $this->model_tool_seo_package->filter_seo($seo_kw, 'product', $product_id, $language_id);
          }
          
          if (version_compare(VERSION, '3', '>=')) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'product_id=" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "' AND store_id = 0");
            $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'product_id=" . (int)$product_id . "', language_id = '" . (int)$language_id . "', keyword = '" . $this->db->escape($seo_kw) . "', store_id = 0");
          } else if ($this->config->get('mlseo_ml_mode')) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', language_id = '" . (int)$language_id . "', keyword = '" . $this->db->escape($seo_kw) . "'");
          } else {
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($seo_kw) . "'");
          }
        }
        
        foreach ($desc_values as $desc_col => $desc_val) {
          $description_query .= $desc_col . " = '" . $this->db->escape($desc_val) . "',";
        }
        
        $description_query = rtrim($description_query, ',');
        
        $rowExists = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'")->row;
        
        if (!empty($rowExists)) {
          $this->db->query("UPDATE " . DB_PREFIX . "product_description SET " . $description_query . " WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");
        } else {
          $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', " . $description_query);
        }
      }
    }
    
    if (isset($data['product_store'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

      if (isset($data['product_store'])) {
        foreach ($data['product_store'] as $store_id) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
        }
      }
		}

    if (isset($data['product_attribute'])) {
      if (empty($config['preserve_attribute'])) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
      }

      if (!empty($data['product_attribute'])) {
        foreach ($data['product_attribute'] as $product_attribute) {
          if ($product_attribute['attribute_id']) {
            // Removes duplicates
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

            foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
              $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
            }
          }
        }
      }
		}

		if (isset($data['product_option'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
      $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");

			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['product_option_value'])) {
						//$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

						$product_option_id = $this->db->getLastId();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							//$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
              $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else {
          //$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}


    if (isset($data['product_discount'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
      
      foreach ($data['product_discount'] as $product_discount) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
      }
    }


		if (isset($data['product_special'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");

			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		if (isset($data['product_image'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");

			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}

		if (isset($data['product_download'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		if (isset($data['product_category'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		if (isset($data['product_filter'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['product_related'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
      $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");

			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		if (isset($data['product_reward'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

			foreach ($data['product_reward'] as $customer_group_id => $value) {
				if ((int)$value['points'] > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");
				}
			}
		}

		if (isset($data['product_layout'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

			foreach ($data['product_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}
    
    if (!$this->config->get('mlseo_enabled')) {
      // v3.x
      if (isset($data['product_seo_url'])) { 
        $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'product_id=" . (int)$product_id . "'");
        
        foreach ($data['product_seo_url']as $store_id => $language) {
          foreach ($language as $language_id => $keyword) {
            if (!empty($keyword)) {
              $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($keyword) . "'");
            }
          }
        }
      // v2.x
      } else if (isset($data['keyword'])) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");

        if ($data['keyword']) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }
      }
		}

		if (isset($data['product_recurring'])) {
      $this->db->query("DELETE FROM `" . DB_PREFIX . "product_recurring` WHERE product_id = " . (int)$product_id);

			foreach ($data['product_recurring'] as $product_recurring) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = " . (int)$product_id . ", customer_group_id = " . (int)$product_recurring['customer_group_id'] . ", `recurring_id` = " . (int)$product_recurring['recurring_id']);
			}
		}

		$this->cache->delete('product');
	}
  
  public function editCategory($category_id, $data) {
    $category_data = array('parent_id', 'top', 'column', 'sort_order', 'status', 'image');
    
    $main_query = '';
    
    foreach ($category_data as $item_col) {
      if (isset($data[$item_col])) {
        $main_query .= "`" . $item_col . "` = '" . $this->db->escape($data[$item_col]) . "',";
      }
    }
    
		$this->db->query("UPDATE " . DB_PREFIX . "category SET " . $main_query . " date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");

    if (isset($data['category_description'])) {

      foreach ($data['category_description'] as $language_id => $desc_values) {
        $description_query = '';
        
        if ($this->config->get('mlseo_enabled') && !empty($desc_values['seo_keyword'])) {
          $seo_kw = @html_entity_decode($desc_values['seo_keyword'], ENT_QUOTES);
          
          if ($seo_kw) {
            $this->load->model('tool/seo_package');
            $seo_kw = $this->model_tool_seo_package->filter_seo($seo_kw, 'category', $category_id, $language_id);
          }
          
          if (version_compare(VERSION, '3', '>=')) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'category_id=" . (int)$category_id . "' AND language_id = '" . (int)$language_id . "' AND store_id = 0");
            $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'category_id=" . (int)$category_id . "', language_id = '" . (int)$language_id . "', keyword = '" . $this->db->escape($seo_kw) . "', store_id = 0");
          } else if ($this->config->get('mlseo_ml_mode')) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "' AND language_id = '" . (int)$language_id . "'");
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', language_id = '" . (int)$language_id . "', keyword = '" . $this->db->escape($seo_kw) . "'");
          } else {
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "'");
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($seo_kw) . "'");
          }
        }
        
        foreach ($desc_values as $desc_col => $desc_val) {
          $description_query .= $desc_col . " = '" . $this->db->escape($desc_val) . "',";
        }
        
        $description_query = rtrim($description_query, ',');
        
        $rowExists = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "' AND language_id = '" . (int)$language_id . "'")->row;
        
        if (!empty($rowExists)) {
          $this->db->query("UPDATE " . DB_PREFIX . "category_description SET " . $description_query . " WHERE category_id = '" . (int)$category_id . "' AND language_id = '" . (int)$language_id . "'");
        } else {
          $this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', " . $description_query);
        }
      }
    }

		// MySQL Hierarchical Data Closure Table Pattern
    if (isset($data['parent_id'])) {
      $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE path_id = '" . (int)$category_id . "' ORDER BY level ASC");

      if ($query->rows) {
        foreach ($query->rows as $category_path) {
          // Delete the path below the current one
          $this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' AND level < '" . (int)$category_path['level'] . "'");

          $path = array();

          // Get the nodes new parents
          $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

          foreach ($query->rows as $result) {
            $path[] = $result['path_id'];
          }

          // Get whats left of the nodes current path
          $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' ORDER BY level ASC");

          foreach ($query->rows as $result) {
            $path[] = $result['path_id'];
          }

          // Combine the paths with a new level
          $level = 0;

          foreach ($path as $path_id) {
            $this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_path['category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

            $level++;
          }
        }
      } else {
        // Delete the path below the current one
        $this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_id . "'");

        // Fix for records with no paths
        $level = 0;

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

        foreach ($query->rows as $result) {
          $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

          $level++;
        }

        $this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', level = '" . (int)$level . "'");
      }
		}

		if (isset($data['category_filter'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");

			foreach ($data['category_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['category_store'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");

			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['category_layout'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");

			foreach ($data['category_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}
    
    if (!$this->config->get('mlseo_enabled')) {
      // v3.x
      if (isset($data['product_seo_url'])) { 
        $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'category_id=" . (int)$category_id . "'");
        
        foreach ($data['product_seo_url']as $store_id => $language) {
          foreach ($language as $language_id => $keyword) {
            if (!empty($keyword)) {
              $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($keyword) . "'");
            }
          }
        }
      // v2.x
      } else if (isset($data['keyword'])) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "'");

        if ($data['keyword']) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }
      }
		}
	}
  
  private function urlify($value, $lang = null) {
    if (!empty($lang)) {
      include_once(DIR_SYSTEM . 'library/gkd_urlify.php');
      $value = URLify::downcode($value, $lang);
    }
    
    $value = str_replace(array('\'','`','‘','’','|','%7C', "\n"), '-', $value);
    $value = str_replace(array('"','“','”','&','&amp;','+','?','!','/','%','#',',',':','&gt;','&lt;',';','<','>','(',')','™','®','©','&copy;','&reg;','&trade;'), '', $value);
    
    $value = trim(mb_ereg_replace('--+', '-', str_replace(' ', '-', mb_strtolower($value))), '-');
    
    return $value;
  }
  
  public function getFeedCategories() {
    $rows = array();
    $i = 0;
    
    $cat_field = ($this->request->post['import_type'] == 'category')  ? 'parent_id' : 'product_category';
    
    // set profile
    if (!empty($this->request->post['profile'])) {
      $profile = include DIR_APPLICATION . 'view/universal_import/profiles/'. str_replace(array('/','\\'), '', $this->request->post['import_type']) .'/' . str_replace(array('/','\\'), '', $this->request->post['profile']) . '.cfg';
    }
    
    $extension = !empty($this->request->post['import_filetype']) ? $this->request->post['import_filetype'] : strtolower(pathinfo($this->request->post['import_file'], PATHINFO_EXTENSION));
    
    if (!empty($this->session->data['univimport_temp_file'])) {
      $import_file = $this->session->data['univimport_temp_file'];
    } else if ($this->request->post['import_source'] == 'upload') {
      $import_file = DIR_CACHE.'universal_import/'.str_replace(array('../', '..\\'), '', $this->request->post['import_file']);
    } else if ($this->request->post['import_source'] == 'ftp') {
      $import_file = $this->request->post['import_ftp'].$this->request->post['import_file'];
    } else {
      $import_file = $this->request->post['import_file'];
    }
    
    if ($extension == 'csv') {
      $separator = !empty($this->request->post['csv_separator']) ? $this->request->post['csv_separator'] : ',';
      
      $file = fopen($import_file, 'r');
      
      if ($file) {
        if (!empty($this->request->post['csv_header'])) {
          fgets($file);
        }
        
        while (!feof($file)) {
          if ($line = trim(fgets($file))) {
            $config = $this->request->post;
            
            $row = str_getcsv($line, $separator);
            
            $this->populate_fields($config, $row);

            foreach ((array) $config['columns'][$cat_field] as $cat) {
              if ($cat && !isset($rows[$cat])) {
                $rows[$cat] = isset($profile['col_binding'][$cat]) ? $profile['col_binding'][$cat] : '';
              }
            }

            $i++;
          }
        }

        fclose($file);
      } else {
        // error opening the file.
      }
    } else if ($extension == 'xml') {
      $xml = new XMLReader;
      $xml->open($import_file);

      //$doc = new DOMDocument;
      
      $rows = array();
      $i = 0;
      
      $nodeName = $this->request->post['xml_node'];
      // find the node name
      while ($xml->read() && $xml->name !== $nodeName);

      // now that we're at the right depth, hop to the next <product/> until the end of the tree
      while ($xml->name === $nodeName) {
          $node = new SimpleXMLElement($xml->readOuterXML()); // other method to get data
          //$node = simplexml_import_dom($doc->importNode($xml->expand(), true));
          
          $config = $this->request->post;
          
          $row = $this->model_tool_universal_import->XML2Array($node);
          
          $this->populate_fields($config, $row);
          
          foreach ((array) $config['columns'][$cat_field] as $cat) {
            if ($cat && !isset($rows[$cat])) {
              $rows[$cat] = isset($profile['col_binding'][$cat]) ? $profile['col_binding'][$cat] : '';
            }
          }
          
          // go to next node
          $xml->next($nodeName);
          $i++;
      }
    } else if ($extension == 'ods' || $extension == 'xlsx') { // Spout
      require_once DIR_SYSTEM.'library/Spout/Autoloader/autoload.php';
      
      libxml_disable_entity_loader(false);
      
      if ($extension == 'xlsx') {
        $reader = ReaderFactory::create(Type::XLSX);
      } else if ($extension == 'ods') {
        $reader = ReaderFactory::create(Type::ODS);
      }

      $reader->setShouldFormatDates(true);
      //$reader = ReaderFactory::create(Type::CSV); // for CSV files

      $reader->open($import_file);

      foreach ($reader->getSheetIterator() as $sheet) {
        foreach ($sheet->getRowIterator() as $i => $row) {
          if (!empty($this->request->post['csv_header']) && $i === 1) {
            continue;
          }
          
          $config = $this->request->post;
          
          $this->populate_fields($config, $row);
          
          foreach ((array) $config['columns'][$cat_field] as $cat) {
            if ($cat && !isset($rows[$cat])) {
              $rows[$cat] = isset($profile['col_binding'][$cat]) ? $profile['col_binding'][$cat] : '';
            }
          }
        }
      }
      
      $reader->close();
      
      
    } else if ($extension == 'xls') {
      // PHPExcel
      require_once(DIR_SYSTEM.'library/PHPExcel/PHPExcel.php');
      /* to try for better perf:
      $objReader = PHPExcel_IOFactory::createReader('Excel2007');
      $objReader->setReadDataOnly(true);
      $objReader->load($import_file);
      */
      $objPHPExcel = PHPExcel_IOFactory::load($import_file);
      
      $sheet = $objPHPExcel->getSheet(0);
      $highestRow = $sheet->getHighestRow();
      $highestColumn = $sheet->getHighestColumn();

      $rows = array();
      
      $pop = false;
      
      for ($row = 1; $row <= $highestRow; $row++) {
        $arrRow = $row-1;
        $resrow = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, false, false);

        if ($row === 1 && !empty($this->request->post['csv_header'])) {
          continue;
        }
        
        foreach ((array) $this->request->post['columns'][$cat_field] as $cat) {
          if (isset($resrow[0][$cat]) && !isset($rows[$resrow[0][$cat]])) {
            $rows[$resrow[0][$cat]] = isset($profile['col_binding'][$resrow[0][$cat]]) ? $profile['col_binding'][$resrow[0][$cat]] : '';
          }
        }
      }
    }
    
    return $rows;
  }
  
  public function cron_log($msg = '') {
    $echo = false;

    if ($echo) {
      echo $msg . PHP_EOL;
    } else {
      file_put_contents(DIR_LOGS.'universal_import_cron.log', $msg . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
  }
}