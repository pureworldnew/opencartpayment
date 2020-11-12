<?php
/**
 * Webkul Software.
 * @category  Webkul
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

trait RmaControllerTrait {

  private function validate($check = '') {
    if (!$this->user->hasPermission('modify', $check)) {
      $this->error['warning'] = $this->language->get('error_permission');
    }
    return !$this->error;
  }

  public function urlChange($route,$get = '',$extra = '') {
    if (version_compare(VERSION, '2.2', '>')) {
      return $this->url->link($route, $get, 'SSL');
    } else {
      return $this->url->link($route, $get, true);
    }

  }

  /**
   * returns array of image or false if invalid file is present
   * @param  array  $files [description]
   * @return [type]        [description]
   */
  public function validateImage($files = array()) {

    $this->load->language('common/rma');

    $images = array();
    if (is_array($files['name'])) {
      foreach ($files as $key1 => $file) {
        foreach ($file as $key2 => $value) {
          $images[$key2][$key1] = $value;
        }
      }
    } else {
      $images[] = $files;
    }


    if ($images) {
      foreach ($images as $image) {
        $filename = basename(html_entity_decode($image['name'], ENT_QUOTES, 'UTF-8'));

        // Validate the filename length
        if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 255)) {
          $this->error['warning'] = $this->language->get('error_filename');
        }

        // Allowed file extension types
        $allowed = $this->config->get('wk_rma_system_image');

        if (!$allowed || $allowed != '*' && !in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), explode(',',$allowed))) {
          $this->error['warning'] = sprintf($this->language->get('error_image'),$this->config->get('wk_rma_system_image'),$this->config->get('wk_rma_system_size'));
        }

        if ($image['size'] > ((int)$this->config->get('wk_rma_system_size') * 1024)) {
          $this->error['warning'] = sprintf($this->language->get('error_image'),$this->config->get('wk_rma_system_image'),$this->config->get('wk_rma_system_size'));
        }

        $content = @file_get_contents($image['tmp_name']);

        if (preg_match('/\<\?php/i', $content)) {
          $this->error['warning'] = $this->language->get('error_filetype');
        }

        // Return any upload error
        if ($image['error'] != UPLOAD_ERR_OK) {
          $this->error['warning'] = $this->language->get('error_upload_' . $image['error']);
        }
      }
    }
    if ($this->error) {
      return false;
    }
    return $images;
  }

  public function getFolderImage($imagePath) {
    if (!$imagePath) {
      return array();
    }
    $images = array();
    if($imagePath){
      $dir = DIR_IMAGE .$imagePath.'/';
      if ($this->config->get('wk_rma_system_image') == '*') {
        $image_allowed = 'jpg,jpeg,png,gif,JPEG,JPG,PNG';
      } else {
        $image_allowed = $this->config->get('wk_rma_system_image');
      }

      $files = glob($dir . "*.{" . $image_allowed . "}",GLOB_BRACE);
      foreach($files as $file) {
        $name = explode('/',$file);
        $file = str_replace(DIR_IMAGE,'',$file);
        $images[] = array(
         'resize' => $this->model_tool_image->resize($file,125,125),
         'image' => $this->model_tool_image->resize($file,500,500),
         'name' => end($name)
        );
      }
    }
    return $images;
  }


	/**
	 * [fileValidation method is used for validate the uploaded file and make sure that uploaded file is not a php file.]
	 * @param  [array] $value [it contains all the file information of uploaded file.]
	 * @return [boolean]      [it returns true on valid file, otherwise it returns false.]
	 */
	private function fileValidation($files = array()){

    $this->load->language('common/rma');

    $images = array();
    if (is_array($files['name'])) {
      foreach ($files as $key1 => $file) {
        foreach ($file as $key2 => $value) {
          $images[$key2][$key1] = $value;
        }
      }
    } else {
      $images[] = $files;
    }

    if ($images) {
      foreach ($images as $image) {
        $filename = basename(html_entity_decode($image['name'], ENT_QUOTES, 'UTF-8'));

        // Validate the filename length
        if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 255)) {
          $this->error['warning'] = $this->language->get('error_filename');
        }

        // Allowed file extension types
        $allowed = $this->config->get('wk_rma_system_file');

        if (!$allowed || $allowed != '*' && !in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), explode(',',$allowed))) {
          $this->error['warning'] = sprintf($this->language->get('error_image'),$this->config->get('wk_rma_system_image'),$this->config->get('wk_rma_system_size'));
        }

        if ($image['size'] > ((int)$this->config->get('wk_rma_system_size') * 1024)) {
          $this->error['warning'] = sprintf($this->language->get('error_image'),$this->config->get('wk_rma_system_image'),$this->config->get('wk_rma_system_size'));
        }

        $content = @file_get_contents($image['tmp_name']);

  			if (preg_match('/\<\?php/i', $content)) {
  				$this->error['warning'] = $this->language->get('error_filetype');
  			}

        // Return any upload error
        if ($image['error'] != UPLOAD_ERR_OK) {
          $this->error['warning'] = $this->language->get('error_upload_' . $image['error']);
        }
      }
    }
    if ($this->error) {
      return false;
    }
    return $images;
	}

  private function validateAddRma() {

    if(!isset($this->request->post['order']) && !$this->request->post['order'] || utf8_strlen($this->request->post['order']) < 1) {
      $this->error['warning'] = $this->language->get('error_order');
    }

    if(!isset($this->error['warning']) && isset($this->request->post['selected'])) {
      foreach ($this->request->post['selected'] as $key => $value) {
        if(!(int)$this->request->post['quantity'][$key]){
          $this->error['warning']  = $this->language->get('error_product_qty');
          break;
        }elseif(!(int)$this->request->post['reason'][$key]){
          $this->error['warning']  = $this->language->get('error_reason');
          break;
        }elseif(!$this->validateOrder($this->request->post['quantity'][$key],$this->request->post['product'][$key])){
          $this->error['warning']  = $this->language->get('error_product_qty_error');
          break;
        }
      }
    } else {
      $this->error['warning']  = $this->language->get('error_product');
    }

    if(utf8_strlen($this->request->post['info']) < 1 || utf8_strlen($this->request->post['info']) > 4000){
      $this->error['warning']  = $this->language->get('error_info');
    }

    if ($this->config->get('wk_rma_system_information')) {
      if(!isset($this->request->post['agree'])){
        $this->error['warning']  = $this->language->get('error_agree');
      }
    }

    return !$this->error;

  }

}
