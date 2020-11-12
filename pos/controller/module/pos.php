<?php

define('POS_VERSION', '1.11.1B');

class ControllerModulePos extends Controller {

    private $error = array();

    use RmaControllerTrait;

////// WAQAR WORK
// ===================================== update stocks
    public function updateStocks() {

        if (isset($this->request->post)) {
            if (isset($this->request->post['instock'])) {
                if ($this->request->post['instock'] == 1) {
                    $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity='" . $this->request->post['total_instock'] . "'  where product_id='" . $this->request->post['product_id'] . "' ");
                } else {
                    $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity='0'  where product_id='" . $this->request->post['product_id'] . "' ");
                }
            }
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_location_quantity WHERE product_id='" . $this->request->post['product_id'] . "' ");
            foreach ($this->request->post['locations'] as $location) {
                # code...
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_location_quantity SET location_id='" . $location['location_name'] . "' , location_quantity='" . $location['location_quantity'] . "' , product_id='" . $this->request->post['product_id'] . "' ");
            }
            return true;
        } else {
            return false;
        }
    }
            
    // ===================================== getting stock related data

    public function getStockRelatedData() {
        $json = array();
        if (isset($this->request->post['product_id'])) {
            $query = $this->db->query("SELECT p.*,pd.* FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON(p.product_id=pd.product_id) WHERE p.product_id='" . $this->request->post['product_id'] . "'");
            if ($query->num_rows) {
                $locations = array();
                $location = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_location_quantity WHERE product_id='" . $this->request->post['product_id'] . "'");
                if ($location->num_rows) {
                    $locations = $location->rows;
                }
                $json['product_data'] = $query->row;
                $json['location_data'] = $locations;
            }
        }
        $incoming_orders = array();
        $sql = "SELECT o.* , CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";
        $sql .= " LEFT JOIN `" . DB_PREFIX . "order_product` op  ON op.order_id=o.order_id";
        $sql .= " WHERE op.product_id = '" . $this->request->post['product_id'] . "'";
        $sql .= " AND o.order_type='2' ORDER BY o.order_id DESC LIMIT 3";
        $query = $this->db->query($sql);
        if ($query->rows) {
            foreach ($query->rows as $row) {
                $incoming_orders[] = array(
                    'order_id' => $row['order_id'],
                    'status' => $row['status'],
                    'manufacturer' => $this->getProductManufacturer($this->request->post['product_id']),
                    'no_of_items' => $this->getNoofProductsInOrder($row['order_id']),
                    'total' => $this->currency->format($row['total']),
                    'date_added' => date("m/d/Y", strtotime($row['date_added'])),
                    'view' => $this->url->link('sale/incoming/info', 'token=' . $this->session->data['token'] . '&order_id=' . $row['order_id'], 'SSL'),
                    'copy' => $this->url->link('sale/incoming/copy', 'token=' . $this->session->data['token'] . '&order_id=' . $row['order_id'], 'SSL'),
                    'pdfinv_packing' => $this->url->link('sale/order/pdf_packingslip', 'token=' . $this->session->data['token'] . '&type=incoming&order_id=' . $row['order_id'], 'SSL'),
                    'link_pdfinv_invoice' => $this->url->link('sale/order/pdf_invoice', 'token=' . $this->session->data['token'] . '&type=incoming&order_id=' . $row['order_id'], 'SSL'),
                    'link_pdf_order_request' => $this->url->link('sale/order/pdf_order_request', 'token=' . $this->session->data['token'] . '&order_id=' . $row['order_id'], 'SSL'),
                    'edit' => $this->url->link('sale/orderq/editincoming', 'token=' . $this->session->data['token'] . '&order_id=' . $row['order_id'], 'SSL'),
                );
            }
        }
        $json['incoming_orders'] = $incoming_orders;
        echo json_encode($json);
    }

    public function getNoofProductsInOrder($order_id) {
        $query = $this->db->query("SELECT count(product_id) as total FROM " . DB_PREFIX . "order_product WHERE order_id='" . $order_id . "'");
        return $query->row ? $query->row['total'] : 1;
    }

    public function getProductManufacturer($product_id) {
        $name = "";
        $query = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "product WHERE product_id='" . $product_id . "'");
        $manufacturer_id = $query->row ? $query->row['manufacturer_id'] : 0;
        if ($manufacturer_id) {
            $query = $this->db->query("SELECT name FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id='" . $manufacturer_id . "'");
            $name = $query->row ? $query->row['name'] : "";
        }
        return $name;
    }

    // ===================================== Print lables for order product
    public function saveLabel() {
        $serialized = '';
        $this->language->load('module/product_labels');
        $labeldata = array();
        $keys = array('type', 'ff', 'fs', 'fr', 'text', 'img', 'x', 'y', 'w', 'h', 'color', 'fill');

        foreach ($keys as $key)
            if (isset($this->request->post[$key]))
                $$key = $this->request->post[$key];
            else
                $$key = array();

        if ($type) {
            foreach (array_keys($type) as $i)
                foreach ($keys as $key)
                    $labeldata[$key][$i] = str_replace("'", "&#8217;", ${$key}[$i]);
            $labeldata['numrows'] = count(array_keys($type));
        } else
            $labeldata['numrows'] = 0;

        if ($labeldata['numrows'] > 0) {
            $serialized = $this->serialize($labeldata);
        }

        if (!$this->user->hasPermission('modify', 'module/pos'))
            $res['error'] = $this->language->get('error_permission');
        else {
            $this->load->model('module/product_labels');
            if (empty($this->request->get['id']) || !empty($this->request->get['saveas_label_name']))
                $res = $this->model_module_product_labels->setLabel($serialized);
            else
                $res = $this->model_module_product_labels->updateLabel($serialized);
        }
        $this->response->setOutput($res);
    }

    public function getLabel() {
        $this->language->load('module/product_labels');
        $res = array();
        if (!empty($this->request->get['id'])) {
            $this->load->model('module/product_labels');
            $res = $this->model_module_product_labels->getLabel($this->request->get['id']);
        }

        if (empty($res))
            foreach (array('id', 'name', 'data') as $key)
                $res[0][$key] = '';

        $this->response->setOutput($this->serialize($res[0]));
    }

    public function serialize($data) {
        return $this->alt_json_encode($data);
    }

    public function unserialize($data) {
        return json_decode($data);
    }

    private function alt_json_encode($arr) {
        @array_walk_recursive($arr, array('ControllerModuleProductLabels', 'alt_json_encode_callback'));
        return mb_decode_numericentity(json_encode($arr), array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
    }

    static private function alt_json_encode_callback(&$item, $key) {
        if (is_string($item))
            $item = mb_encode_numericentity($item, array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
    }

    public static function toggle($type, $id) {
        $toggle = array(
            "img" => array('hide', 'hide', 'show', 'show', 'show', 'show', 'show', 'hide', 'hide', 'hide'),
            "text" => array('show', 'show', 'hide', 'show', 'show', 'hide', 'hide', 'show', 'hide', 'show'),
            "rect" => array('hide', 'hide', 'hide', 'show', 'show', 'show', 'show', 'show', 'show', 'hide'),
            "barcode" => array('hide', 'show', 'hide', 'show', 'show', 'show', 'show', 'show', 'hide', 'hide'),
            "list" => array('show', 'hide', 'hide', 'show', 'show', 'show', 'show', 'show', 'hide', 'hide'),
        );
        return $toggle[$type][$id];
    }

    // ===================================== CONTROLLER PRODUCT
    public function labels() {

        ob_start();
        require_once(DIR_SYSTEM . 'library/product_labels/product_labels.php');

        $this->load->model('setting/setting');
        $this->load->model('module/product_labels');
        $this->load->model('sale/order');
        $settings = $this->model_setting_setting->getSetting('product_labels');

        //echo '<pre>';print_r($_POST);exit;

        $orientation = $settings['product_labels_default_orientation'];
        $sample = 0;
        $edit = 0;
        $templateid = $settings['product_labels_default_template'];
        $labelid = $settings['product_labels_default_label'];
        $orderids = array();
        $productid = -1;
        $blanks = array();
        $order_info = array();
        $download = $settings['product_labels_download'];
        $border = $settings['product_labels_border'];
        $printpreview = 0;

        foreach (array("orientation", "templateid", "labelid", "productid", "sample", "orderids", "blanks", "edit", "download", "border", "printpreview") as $key) {
            if (isset($_POST[$key]))
                if ($key == "blanks")
                    $$key = explode(",", $this->request->post[$key]);
                else
                    $$key = $this->request->post[$key];
            if (isset($_GET[$key]))
                if ($key == "blanks")
                    $$key = explode(",", $this->request->get[$key]);
                else
                    $$key = $this->request->get[$key];
        }

        $template_info = $this->model_module_product_labels->getLabelTemplate($templateid);
        $label_info = $this->model_module_product_labels->getLabel($labelid);
        $label_elements = json_decode($label_info[0]['data'], TRUE);

        $lw = $template_info[0]['width'];
        $lh = $template_info[0]['height'];
        $pw = $template_info[0]['page_w'];
        $ph = $template_info[0]['page_h'];
        $nw = $template_info[0]['number_h'];
        $nh = $template_info[0]['number_v'];
        $rounded = $template_info[0]['rounded'];
        $vspace = $template_info[0]['space_v'];
        $hspace = $template_info[0]['space_h'];
        $mt = $template_info[0]['margin_t'];
        $ml = $template_info[0]['margin_l'];
        if ($sample) {
            $nw = 1;
            $nh = 1;
            $ph = $lh + 2;
            $pw = $lw + 2;
            $blanks = array();
            $orderids = 1;
            $border = 1;
            $label_list = 0;
        } else {

            if ($printpreview) {

                $label_list = array_fill(0, $nw * $nh, -1);
                $border = 1;
                $label_elements = array();
                $orientation = "P";
                $label_elements['printpreview'] = 1;
                $labelids = array();
            } else {

                $this->load->model('catalog/product');
                $this->load->model('catalog/option');

                $label_elements['product_info'] = $this->model_catalog_product->getProduct($productid);
                $label_elements['product_info']['price_total'] = $label_elements['product_info']['price'];
                $label_elements['product_info']['weight_total'] = $label_elements['product_info']['weight'];
                $label_elements['product_info']['price_tax'] = $label_elements['product_info']['price'];

                $options = $this->model_catalog_product->getProductOptions($productid);

                foreach ($options as $ido => $id_option) {
                    if (isset($id_option['product_option_value'])) {
                        foreach ($id_option['product_option_value'] as $product_option_value => $option) {
                            if (!isset($option['name'])) {
                                $option_value = $this->model_catalog_option->getOptionValue($option['option_value_id']);
                                $options[$ido]['product_option_value'][$product_option_value]['name'] = $option_value['name'];
                                $options[$ido]['product_option_value'][$product_option_value]['image'] = $option_value['image'];
                            }
                        }
                    }
                }

                $this->load->model('catalog/manufacturer');
                $manufacturer = $this->model_catalog_manufacturer->getManufacturer($label_elements['product_info']['manufacturer_id']);

                $label_elements['product_info']['manufacturer_image'] = (isset($manufacturer['image'])) ? $manufacturer['image'] : "undefined";
                $label_elements['product_info']['manufacturer'] = (isset($manufacturer['name'])) ? $manufacturer['name'] : "";

                foreach ($options as $id_option) {

                    $label_elements['option_names'][$id_option['product_option_id']] = strtolower($id_option['name']);

                    $label_elements['product_info']['options'][$id_option['product_option_id']]['name'] = $id_option['name'];
                    if (isset($id_option['product_option_value'])) {
                        foreach ($id_option['product_option_value'] as $tov_id => $t_option_value) {
                            $t_option[$tov_id]['name'] = $t_option_value['name'];
                            $t_option[$tov_id]['image'] = $t_option_value['image'];
                            $t_option[$tov_id]['price'] = (float) ($t_option_value['price_prefix'] . $t_option_value['price']);
                            $t_option[$tov_id]['weight'] = (float) ($t_option_value['weight_prefix'] . $t_option_value['weight']);
                            $label_elements['product_info']['options'][$id_option['product_option_id']]['values'] = $t_option;
                        }
                    } else {
                        $label_elements['product_info']['options'][$id_option['product_option_id']]['values'] = array();
                    }
                }

                $labelids = array();
                $label_list = array();

                foreach ($this->request->post['pl_options_num'] as $id => $option_num) {
                    $labelids[$id]['num'] = $option_num;
                    for ($n = 0; $n < $option_num; $n++)
                        $label_list[] = $id;
                }
                $total_labels = count($label_list);
                foreach ($this->request->post['pl_options_name'] as $id => $option_name) {
                    $keyval = explode(",", $id);
                    $labelids[$keyval[0]]['pl_options_name'][$keyval[1]] = $option_name;
                }
                foreach ($this->request->post['pl_options_value'] as $id => $option_value) {
                    $keyval = explode(",", $id);
                    $labelids[$keyval[0]]['pl_options_value'][$keyval[1]] = urldecode($option_value);
                }
                foreach ($this->request->post['pl_options_string'] as $id => $option_string) {
                    $keyval = explode(",", $id);
                    $labelids[$keyval[0]]['pl_options_string'][$keyval[1]] = urldecode($option_string);
                }

                $this->load->model('localisation/tax_class');
                $this->load->model('localisation/tax_rate');
                $tax = $this->model_localisation_tax_class->getTaxClass($label_elements['product_info']['tax_class_id']);
                $taxes = $this->model_localisation_tax_class->getTaxClasses();
                $rules = $this->model_localisation_tax_class->getTaxRules($label_elements['product_info']['tax_class_id']);
            }
        }

        $label_elements['sample'] = $sample;
        $label_elements['rounded'] = $rounded;
        $label_elements['border'] = $border;

        if ($orientation == "P") {
            list($pagew, $pageh, $labelw, $labelh, $numw, $numh, $hspacing, $vspacing, $margint, $marginl) = array($pw, $ph, $lw, $lh, $nw, $nh, $hspace, $vspace, $mt, $ml);
        } else {
            list($pagew, $pageh, $labelw, $labelh, $numw, $numh, $hspacing, $vspacing, $margint, $marginl) = array($ph, $pw, $lh, $lw, $nh, $nw, $vspace, $hspace, $ml, $mt);
        }
        $n = $nw * $nh;

        if ($marginl == 'auto' || $sample)
            $marginl = ($pagew - (($numw * ($labelw + $hspacing)) - $hspacing)) / 2; //margin left;

        if ($margint == 'auto' || $sample)
            $margint = ($pageh - (($numh * ($labelh + $vspacing)) - $vspacing)) / 2; //margin top;

        if ($orientation == "L")
            $margint = round(abs($pageh - (($numh * ($labelh + $vspacing)) - $vspacing) - $margint), 2);

        $pdf = new ProductLabels("", 'mm', array($pagew, $pageh));
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('torcu.com');
        $pdf->SetTitle('Opencart labels');
        $pdf->SetSubject('Opencart labels');
        $pdf->SetKeywords('labels, opencart, torcu.com');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetMargins($marginl, $margint);
        $pdf->SetAutoPageBreak(false);
        $pdf->SetDisplayMode('real');

        $j = 1;
        $order_pos = 0;
        $current_options_id = -1;
        $total = count($label_list) + count($blanks);

        while ($order_pos < count($label_list)) {

            $pdf->AddPage();

            $labels = 0;
            for ($row = 1; $row <= $numh; $row++) {

                if ($order_pos >= count($label_list))
                    break;

                for ($i = 1; $i <= $numw; $i++) {

                    if ($order_pos >= count($label_list))
                        break;

                    $x = $pdf->getX();
                    $y = $pdf->getY();

                    if (in_array($j, $blanks))
                        $pdf->setXY($x + $labelw, $y);
                    else {

                        if ($current_options_id != $label_list[$order_pos] && !$sample) {

                            $current_options_id = $label_list[$order_pos];

                            $label_elements['product_info']['price_total'] = $label_elements['product_info']['price'];
                            $label_elements['product_info']['weight_total'] = $label_elements['product_info']['weight'];
                            $label_elements['product_info']['price_tax'] = $label_elements['product_info']['price'];
                            $label_elements['product_info']['taxes'] = 0;
                            $label_elements['product_info']['tax'] = array();
                            $label_elements['custom_text'] = array();
                            $label_elements['option_values'] = array();

                            $custom_id = 1;
                            $rate = array();

                            foreach ($labelids[$current_options_id]['pl_options_name'] as $lidon => $option_name) {

                                if ($option_name == "_c") {
                                    $label_elements['custom_text'][$custom_id] = $labelids[$current_options_id]['pl_options_string'][$lidon];
                                    $custom_id++;
                                }

                                if (isset($label_elements['product_info']['options'][$option_name]['values'])) {
                                    foreach ($label_elements['product_info']['options'][$option_name]['values'] as $option_data) {
                                        if ($labelids[$current_options_id]['pl_options_value'][$lidon] == $option_data['name']) {
                                            $label_elements['option_values'][$option_name] = $option_data['name'];
                                            $label_elements['product_info']['price_total'] += $option_data['price'];
                                            $label_elements['product_info']['weight_total'] += $option_data['weight'];
                                            break;
                                        } else {
                                            $label_elements['option_values'][$option_name] = $labelids[$current_options_id]['pl_options_string'][$lidon];
                                        }
                                    }
                                }
                            }

                            foreach ($rules as $rateid => $rule) {
                                $rate[$rateid] = $this->model_localisation_tax_rate->getTaxRate($rule['tax_rate_id']);
                                $rate[$rateid]['price'] = ($label_elements['product_info']['price_total'] * (1 + ($rate[$rateid]['rate'] / 100))) - $label_elements['product_info']['price_total'];
                                $label_elements['product_info']['tax'][$rateid] = $rate[$rateid]['price'];
                                $label_elements['product_info']['price_tax'] += $rate[$rateid]['price'];
                                //$label_elements['product_info']['price_tax']."\n";
                                //$label_elements['product_info']['taxes']."\n";
                            }
                            $label_elements['product_info']['taxes'] = $label_elements['product_info']['price_tax'] - $label_elements['product_info']['price_total'];

                            $weight = $this->getProductWeights($label_elements['product_info']['weight_total'], $label_elements['product_info']['weight_class_id']);
                            foreach ($weight as $unit => $unit_values) {
                                $label_elements['product_info'][$unit] = $unit_values['value'];
                            }
                        }

                        $pdf->printLabel($labelw, $labelh, $x, $y, $label_elements);
                        $order_pos++;
                    }
                    $j++;
                    if ($i < $numw && $hspacing > 0)
                        $pdf->setX($pdf->getX() + $hspacing);
                }
                if ($j > $total)
                    break;
                $pdf->setXY($marginl, $y + $labelh);
                if ($row < $numh && $vspacing > 0)
                    $pdf->setXY($marginl, $y + $labelh + $vspacing);
            }
        }

        if (!empty($_GET['debugpdf'])) {
            print_r($pdf);
            die();
        }

        if (ob_get_contents())
            ob_clean();

        if (($settings['product_labels_download'] || $download) && !$sample)
            $this->response->setOutput($pdf->Output($settings['product_labels_filename'], 'D'));
        else
            $this->response->setOutput($pdf->Output($settings['product_labels_filename'], 'I'));
    }

    // ===================================== CONTROLLER PRODUCT ORDERS
    public function labels_orders() {

        ob_start();
        require_once(DIR_SYSTEM . 'library/product_labels/order_labels.php');

        $this->load->model('setting/setting');
        $this->load->model('module/product_labels');
        $this->load->model('sale/order');
        $settings = $this->model_setting_setting->getSetting('product_labels');

        $orientation = isset($this->request->post['orientation']) ? $this->request->post['orientation'] : $settings['product_labels_default_orientation']; //$settings['product_labels_default_orientation'];
        $sample = 0;
        $edit = 0;
        $templateid = isset($this->request->post['orientation']) ? $this->request->post['orientation'] : $settings['product_labels_default_template']; //$settings['product_labels_default_template'];
        $labelid = isset($this->request->post['labelid']) ? $this->request->post['labelid'] : 8; //$settings['product_labels_default_label'];
        $orderids = array();
        $productids = array();
        $blanks = array();
        $order_info = array();
        $download = $settings['product_labels_download'];
        $border = $settings['product_labels_border'];
        $printpreview = 0;
        $orderid = 0;

        foreach (array("orderid", "orientation", "templateid", "labelid", "productid", "sample", "orderids", "blanks", "edit", "download", "border", "printpreview") as $key) {

            if (isset($_POST[$key]))
                if ($key == "blanks")
                    $$key = explode(",", $this->request->post[$key]);
                else
                    $$key = $this->request->post[$key];
            if (isset($_GET[$key]))
                if ($key == "blanks")
                    $$key = explode(",", $this->request->get[$key]);
                else
                    $$key = $this->request->get[$key];


            if (isset($_POST[$key]))
                if ($key == "productid") {
                    $productids = explode(",", $this->request->post[$key]);
                } else {
                    $$key = $this->request->post[$key];
                }
            if (isset($_POST[$key]))
                if ($productids == "productid")
                    $productids = explode(",", $this->request->post[$key]);
                else
                    $$key = $this->request->post[$key];
        }



        $label_id = isset($this->request->post['labelid']) ? $this->request->post['labelid'] : 8;

        $template_info = $this->model_module_product_labels->getLabelTemplate($templateid);
        $label_info = $this->model_module_product_labels->getLabel($label_id);

        $label_data = json_decode($label_info[0]['data']);

        $idscount = count($productids);
        if (!empty($productids)) {
            for ($i = 0; $i < $idscount; $i++) {
                $label_elements[$i] = json_decode($label_info[0]['data'], TRUE);
            }
        } else {
            $label_elements[$i] = json_decode($label_info[0]['data'], TRUE);
        }



        $lw = $template_info[0]['width']; //+20;
        $lh = $template_info[0]['height']; //+20;
        $pw = $template_info[0]['page_w']; //+15;
        $ph = $template_info[0]['page_h'];
        $nw = ($template_info[0]['number_h'] == 0) ? 1 : $template_info[0]['number_h'];
        $nh = ($template_info[0]['number_v'] == 0) ? 1 : $template_info[0]['number_v'];
        $rounded = $template_info[0]['rounded'];
        $vspace = $template_info[0]['space_v']; //+20;
        $hspace = $template_info[0]['space_h']; //+20;
        $mt = $template_info[0]['margin_t'];
        $ml = $template_info[0]['margin_l'];

        if ($sample) {
            $nw = 1;
            $nh = 1;
            $ph = $lh; //+2;
            $pw = $lw; //+2;
            $blanks = array();
            $orderids = 1;
            $border = 1;
            $label_list = 0;
        } else {
            if ($printpreview) {

                $label_list = array_fill(0, $nw * $nh, -1);
                $border = 1;
                $label_elements = array();
                $orientation = "P";
                $label_elements['printpreview'] = 1;
                $labelids = array();
            } else {

                $this->load->model('catalog/product');
                $this->load->model('catalog/option');
                $this->load->model('catalog/attribute');
                $idscount = count($productids);
                for ($i = 0; $i < $idscount; $i++) {
                    $label_elements[$i]['product_info'] = $this->model_catalog_product->getProduct($productids[$i]);
                    $label_elements[$i]['product_info']['price_total'] = $label_elements[$i]['product_info']['price'];
                    $label_elements[$i]['product_info']['weight_total'] = $label_elements[$i]['product_info']['weight'];
                    $label_elements[$i]['product_info']['price_tax'] = $label_elements[$i]['product_info']['price'];
                    $label_elements[$i]['order_info'] = $this->model_sale_order->getOrder($orderid);
                    $label_elements[$i]['order_product'] = $this->model_sale_order->getOrderProduct($orderid, $label_elements[$i]['product_info']['product_id']);
                    $label_elements[$i]['order_product']['total'] = $this->currency->format($label_elements[$i]['order_product']['total'], $label_elements[$i]['order_info']['currency_code'], $label_elements[$i]['order_info']['currency_value']);
                    $label_elements[$i]['order_product']['price'] = $this->currency->format($label_elements[$i]['order_product']['price'], $label_elements[$i]['order_info']['currency_code'], $label_elements[$i]['order_info']['currency_value']);
                    $label_elements[$i]['order_product']['quantity_supplied'] = number_format($label_elements[$i]['order_product']['quantity_supplied'], 2); //$this->currency->format($label_elements[$i]['order_product']['price'], $label_elements[$i]['order_info']['currency_code'], $label_elements[$i]['order_info']['currency_value']);

                    $options = $this->model_catalog_product->getProductOptions($productids[$i]);

                    foreach ($options as $ido => $id_option) {
                        if (isset($id_option['product_option_value'])) {
                            foreach ($id_option['product_option_value'] as $product_option_value => $option) {
                                if (!isset($option['name'])) {
                                    $option_value = $this->model_catalog_option->getOptionValue($option['option_value_id']);
                                    $options[$ido]['product_option_value'][$product_option_value]['name'] = $option_value['name'];
                                    $options[$ido]['product_option_value'][$product_option_value]['image'] = $option_value['image'];
                                }
                            }
                        }
                    }

                    $attributes = $this->model_catalog_product->getProductAttributesData($productids[$i]);
                    if (!empty($attributes)) {
                        $array_attr = array();
                        $all_attributes = $this->model_catalog_attribute->getAttributes();
                        if (!empty($all_attributes)) {
                            foreach ($all_attributes as $key => $value) {
                                $array_attr [] = $value["name"];
                            }
                        }
                        $label_elements[$i]['product_info']['attributes'] = $array_attr;
                        foreach ($attributes as $value) {
                            $label_elements[$i]['product_info'][$value["name"]] = $value["value"];
                        }
                    }
                    $this->load->model('catalog/manufacturer');
                    $manufacturer = $this->model_catalog_manufacturer->getManufacturer($label_elements[$i]['product_info']['manufacturer_id']);

                    $label_elements[$i]['product_info']['manufacturer_image'] = (isset($manufacturer['image'])) ? $manufacturer['image'] : "undefined";
                    $label_elements[$i]['product_info']['manufacturer'] = (isset($manufacturer['name'])) ? $manufacturer['name'] : "";

                    foreach ($options as $id_option) {

                        $label_elements[$i]['option_names'][$id_option['product_option_id']] = strtolower($id_option['name']);

                        $label_elements[$i]['product_info']['options'][$id_option['product_option_id']]['name'] = $id_option['name'];
                        if (isset($id_option['product_option_value'])) {
                            foreach ($id_option['product_option_value'] as $tov_id => $t_option_value) {
                                $t_option[$tov_id]['name'] = $t_option_value['name'];
                                $t_option[$tov_id]['image'] = $t_option_value['image'];
                                $t_option[$tov_id]['price'] = (float) ($t_option_value['price_prefix'] . $t_option_value['price']);
                                $t_option[$tov_id]['weight'] = (float) ($t_option_value['weight_prefix'] . $t_option_value['weight']);
                                $label_elements[$i]['product_info']['options'][$id_option['product_option_id']]['values'] = $t_option;
                            }
                        } else {
                            $label_elements[$i]['product_info']['options'][$id_option['product_option_id']]['values'] = array();
                        }
                    }

                    $labelids = array();
                    $label_list = array();

                    foreach ($this->request->post['pl_options_num'] as $id => $option_num) {
                        $labelids[$id]['num'] = $option_num;
                        for ($n = 0; $n < $option_num; $n++)
                            $label_list[] = $id;
                    }
                    $total_labels = count($label_list);
                    foreach ($this->request->post['pl_options_name'] as $id => $option_name) {
                        $keyval = explode(",", $id);
                        $labelids[$keyval[0]]['pl_options_name'][$keyval[1]] = $option_name;
                    }
                    foreach ($this->request->post['pl_options_value'] as $id => $option_value) {
                        $keyval = explode(",", $id);
                        $labelids[$keyval[0]]['pl_options_value'][$keyval[1]] = urldecode($option_value);
                    }
                    foreach ($this->request->post['pl_options_string'] as $id => $option_string) {
                        $keyval = explode(",", $id);
                        $labelids[$keyval[0]]['pl_options_string'][$keyval[1]] = urldecode($option_string);
                    }

                    $this->load->model('localisation/tax_class');
                    $this->load->model('localisation/tax_rate');
                    $tax = $this->model_localisation_tax_class->getTaxClass($label_elements[$i]['product_info']['tax_class_id']);
                    $taxes = $this->model_localisation_tax_class->getTaxClasses();
                    $rules = $this->model_localisation_tax_class->getTaxRules($label_elements[$i]['product_info']['tax_class_id']);
                }//for
            }
        }
        //echo '<pre>';print_r($label_elements);exit;

        if (!empty($productids)) {
            for ($i = 0; $i < $idscount; $i++) {
                $label_elements[$i]['sample'] = $sample;
                $label_elements[$i]['rounded'] = $rounded;
                $label_elements[$i]['border'] = $border;
            }
        } else {
            $label_elements[$i]['sample'] = $sample;
            $label_elements[$i]['rounded'] = $rounded;
            $label_elements[$i]['border'] = $border;
        }

        if ($orientation == "P") {
            list($pagew, $pageh, $labelw, $labelh, $numw, $numh, $hspacing, $vspacing, $margint, $marginl) = array($pw, $ph, $lw, $lh, $nw, $nh, $hspace, $vspace, $mt, $ml);
        } else {
            list($pagew, $pageh, $labelw, $labelh, $numw, $numh, $hspacing, $vspacing, $margint, $marginl) = array($ph, $pw, $lh, $lw, $nh, $nw, $vspace, $hspace, $ml, $mt);
        }

        $n = $nw * $nh;

        if ($marginl == 'auto' || $sample)
            $marginl = ($pagew - (($numw * ($labelw + $hspacing)) - $hspacing)) / 2; //margin left;

        if ($margint == 'auto' || $sample)
            $margint = ($pageh - (($numh * ($labelh + $vspacing)) - $vspacing)) / 2; //margin top;

        if ($orientation == "L")
            $margint = round(abs($pageh - (($numh * ($labelh + $vspacing)) - $vspacing) - $margint), 2);

        $pdf = new OrderLabels("", 'mm', array($pagew, $pageh));
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('torcu.com');
        $pdf->SetTitle('Opencart labels');
        $pdf->SetSubject('Opencart labels');
        $pdf->SetKeywords('labels, opencart, torcu.com');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetMargins($marginl, $margint);
        $pdf->SetAutoPageBreak(false);
        $pdf->SetDisplayMode('real');

        $j = 1;
        $order_pos = 0;
        $current_options_id = -1;
        $total = count($label_list) + count($blanks);
        //echo count($label_list);exit;
        //print(count($productids));
        //die;

        $label_e = array();
        $c = 0;
        for ($a = 0; $a < count($productids); $a++) {

            if ($order_pos < count($productids)) {

                $pdf->AddPage();

                $labels = 0;
                for ($row = 1; $row <= $numh; $row++) {

                    //if($order_pos >= count($productids)) break;

                    for ($i = 1; $i <= $numw; $i++) {

                        //if($order_pos >= count($productids)) break;

                        $x = $pdf->getX();
                        $y = $pdf->getY();

                        if (in_array($j, $blanks)) {
                            $pdf->setXY($x + $labelw, $y);
                        } else {


                            if ($current_options_id != $label_list[$order_pos] && !$sample) {

                                $current_options_id = $label_list[$order_pos];

                                $label_elements[$c]['product_info']['price_total'] = $label_elements[$c]['product_info']['price'];
                                $label_elements[$c]['product_info']['weight_total'] = $label_elements[$c]['product_info']['weight'];
                                $label_elements[$c]['product_info']['price_tax'] = $label_elements[$c]['product_info']['price'];
                                $label_elements[$c]['product_info']['taxes'] = 0;
                                $label_elements[$c]['product_info']['tax'] = array();
                                $label_elements[$c]['custom_text'] = array();
                                $label_elements[$c]['option_values'] = array();

                                $custom_id = 1;
                                $rate = array();

                                foreach ($labelids[$current_options_id]['pl_options_name'] as $lidon => $option_name) {

                                    if ($option_name == "_c") {
                                        $label_elements[$c]['custom_text'][$custom_id] = $labelids[$current_options_id]['pl_options_string'][$lidon];
                                        $custom_id++;
                                    }

                                    if (isset($label_elements[$c]['product_info']['options'][$option_name]['values'])) {
                                        foreach ($label_elements[$c]['product_info']['options'][$option_name]['values'] as $option_data) {
                                            if ($labelids[$current_options_id]['pl_options_value'][$lidon] == $option_data['name']) {
                                                $label_elements[$c]['option_values'][$option_name] = $option_data['name'];
                                                $label_elements[$c]['product_info']['price_total'] += $option_data['price'];
                                                $label_elements[$c]['product_info']['weight_total'] += $option_data['weight'];
                                                break;
                                            } else {
                                                $label_elements['option_values'][$option_name] = $labelids[$current_options_id]['pl_options_string'][$lidon];
                                            }
                                        }
                                    }
                                }

                                foreach ($rules as $rateid => $rule) {
                                    $rate[$rateid] = $this->model_localisation_tax_rate->getTaxRate($rule['tax_rate_id']);
                                    $rate[$rateid]['price'] = ($label_elements[$c]['product_info']['price_total'] * (1 + ($rate[$rateid]['rate'] / 100))) - $label_elements[$c]['product_info']['price_total'];
                                    $label_elements[$c]['product_info']['tax'][$rateid] = $rate[$rateid]['price'];
                                    $label_elements[$c]['product_info']['price_tax'] += $rate[$rateid]['price'];
                                    //$label_elements['product_info']['price_tax']."\n";
                                    //$label_elements['product_info']['taxes']."\n";
                                }
                                $label_elements[$c]['product_info']['taxes'] = $label_elements[$c]['product_info']['price_tax'] - $label_elements[$c]['product_info']['price_total'];

                                $weight = $this->getProductWeights($label_elements[$c]['product_info']['weight_total'], $label_elements[$c]['product_info']['weight_class_id']);
                                foreach ($weight as $unit => $unit_values) {
                                    $label_elements[$c]['product_info'][$unit] = $unit_values['value'];
                                }
                            }

                            //echo '<pre>';print_r($label_elements);exit;
                            $pdf->printLabel($labelw, $labelh, $x, $y, $label_elements[$c]);


                            $order_pos++;
                        }
                        $j++;
                        if ($i < $numw && $hspacing > 0)
                            $pdf->setX($pdf->getX() + $hspacing);
                    }
                    if ($j > $total)
                        break;
                    $pdf->setXY($marginl, $y + $labelh);
                    if ($row < $numh && $vspacing > 0)
                        $pdf->setXY($marginl, $y + $labelh + $vspacing);
                }
            }
            $c++;
        }
        //echo '<pre>';print_r($label_elements);exit;
        //die;

        if (!empty($_GET['debugpdf'])) {
            print_r($pdf);
            die();
        }

        if (ob_get_contents())
            ob_clean();

        if (($settings['product_labels_download'] || $download) && !$sample)
            $this->response->setOutput($pdf->Output($settings['product_labels_filename'], 'D'));
        else
            $this->response->setOutput($pdf->Output($settings['product_labels_filename'], 'I'));
    }

    public function updateStockAndStorageLocationsforlabel() {
        if (isset($this->request->post)) {
            if (isset($this->request->post['productid'])) {
                if (isset($this->request->post['instock'])) {
                    if ($this->request->post['instock'] == 1) {
                        $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity='" . $this->request->post['total_instock'] . "' WHERE product_id='" . $this->request->post['productid'] . "' ");
                    } else {
                        $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity='0' WHERE product_id='" . $this->request->post['productid'] . "' ");
                    }
                }
                if (!empty($this->request->post['locations'])) {
                    $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_location_quantity WHERE product_id='" . $this->request->post['productid'] . "' ");
                    foreach ($this->request->post['locations'] as $location) {
                        # code...
                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_location_quantity SET location_id='" . $location['location_name'] . "' , location_quantity='" . $location['location_quantity'] . "' , product_id='" . $this->request->post['productid'] . "' ");
                    }
                }
            }
        }
    }

    public function getProductWeights($weight, $weight_id) {
        $sSQL = "SELECT wc.weight_class_id, value, wcd.title, wcd.unit\n";
        $sSQL .= "FROM `" . DB_PREFIX . "weight_class` wc\n";
        $sSQL .= "LEFT JOIN `" . DB_PREFIX . "weight_class_description` wcd ON wcd.weight_class_id = wc.weight_class_id LIMIT 0, 30 ";
        $query = $this->db->query($sSQL);
        $res = array();
        foreach ($query->rows as $row)
            if ($row['weight_class_id'] == $weight_id)
                $factor = $row['value'];

        foreach ($query->rows as $row)
            $res[$row['unit']]['value'] = $weight * ($row['value'] / $factor);

        return $res;
    }

    ///// WAQAR WORK END

    public function updateStockAndStorageLocations() {
        if (isset($this->request->post)) {
            if (isset($this->request->post['productid'])) {
                if (isset($this->request->post['instock'])) {
                    if ($this->request->post['instock'] == 1) {
                        //$this->db->query("UPDATE ".DB_PREFIX."product SET front_quantity='".$this->request->post['front_quantity']."',storage_quantity='".$this->request->post['storage_quantity']."',quantity='".$this->request->post['total_instock']."' WHERE product_id='".$this->request->post['productid']."' ");
                        $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity='" . $this->request->post['total_instock'] . "' WHERE product_id='" . $this->request->post['productid'] . "' ");
                        $this->db->query("UPDATE " . DB_PREFIX . "product SET stock_status_id='7' WHERE product_id='" . $this->request->post['productid'] . "' ");
                    } else {
                        $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity='0' WHERE product_id='" . $this->request->post['productid'] . "' ");
                    }
                }
                if (!empty($this->request->post['locations'])) {
                    $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_location_quantity WHERE product_id='" . $this->request->post['productid'] . "' ");
                    foreach ($this->request->post['locations'] as $location) {

                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_location_quantity SET location_id='" . $location['location_name'] . "' , location_quantity='" . $location['location_quantity'] . "' ,assigned_stock='" . $location['location_assigned'] . "', product_id='" . $this->request->post['productid'] . "' ");
                    }
                }
            }
        }
    }

    public function updateLocation() {
        if (isset($this->request->post)) {
            if (isset($this->request->post['location'])) {
                $pid = $this->request->post['productid'];
                $sql = "UPDATE " . DB_PREFIX . "product SET location='" . $this->request->post['location'] . "' WHERE product_id='" . $pid . "' ";
                $this->db->query($sql);
            }
        }
    }

    public function updatePriceData() {
        if (isset($this->request->post)) {
            if (isset($this->request->post['price'])) {
                $pid = $this->request->post['productid'];
                $price = $this->request->post['price'];
                $labour = $this->request->post['labour'];
                $increment = $this->request->post['increment'];
                $sql = "UPDATE " . DB_PREFIX . "product SET price='$price',labour_cost = '$labour',unique_option_price='$increment' WHERE product_id='" . $pid . "' ";
                //echo $sql;
                $this->db->query($sql);
            }
        }
    }

    public function updateMetalPriceAndDiscount() {
        $json = array();
        if (isset($this->request->post['price']) && isset($this->request->post['productid'])) {
            $this->updatePriceData();
            $product_id = $this->request->post['productid'];
            $this->_processProductForUpdatePrice($product_id);
            $this->updateDiscountrates($product_id);
            $query = $this->db->query("SELECT price FROM oc_product WHERE product_id = '" . $product_id . "'");
            $json['price'] = $query->row['price'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function _processProductForUpdatePrice($product_id) {
        $getPricesQuery = $this->db->query("SELECT p.labour_cost , p.unique_option_price, p.multiplier_value, c.metal_price FROM oc_category c LEFT JOIN oc_product_to_category p2c ON c.category_id=p2c.category_id LEFT JOIN oc_product p ON p.product_id = p2c.product_id WHERE (c.metal_price > 0 OR p.multiplier_value <> '')  AND p.product_id = '" . $product_id . "'");

        if (empty($getPricesQuery->rows)) {
            $this->_productWithoutOption($product_id);
        } else {
            $labour_cost = $getPricesQuery->row['labour_cost'];
            $unique_option_price = $getPricesQuery->row['unique_option_price'];
            $metal_price = $getPricesQuery->row['multiplier_value'] != '' ? (float) $getPricesQuery->row['multiplier_value'] : $getPricesQuery->row['metal_price'];
            $this->_productWithOption($product_id, $labour_cost, $unique_option_price, $metal_price);
        }
    }

    public function _productWithoutOption($product_id) {
        $this->db->query("UPDATE oc_product SET price = labour_cost WHERE product_id = '" . $product_id . "' AND model != 'grouped'");
    }

    public function _productWithOption($product_id, $labour_cost, $unique_option_price, $metal_price) {
        $my_final_price = $labour_cost + ($unique_option_price * $metal_price);

        $this->db->query("UPDATE oc_product SET price = '" . (float) $my_final_price . "', is_updated= 1 WHERE product_id = '" . $product_id . "' AND model != 'grouped'");
    }

    public function updateDiscountrates($product_id) {

        $query = "UPDATE " . DB_PREFIX . "product_discount pd LEFT JOIN "
                . "" . DB_PREFIX . "product p ON pd.product_id=p.product_id SET "
                . "pd.price=p.price - (p.price * (pd.discount_percent/100)) WHERE pd.product_id = '" . $product_id . "'";
        $this->db->query($query);
    }

    public function updateCustomProductLabels() {
        if (isset($this->request->post)) {

            $this->db->query("UPDATE " . DB_PREFIX . "product SET show_product_label_1 ='" . $this->request->post['show_product_label_1'] . "',product_label_text_1='" . $this->request->post['product_label_text_1'] . "', show_product_label_2 ='" . $this->request->post['show_product_label_2'] . "',product_label_text_2='" . $this->request->post['product_label_text_2'] . "' WHERE product_id='" . $this->request->post['product_id'] . "'");
        }
    }

    public function insertOrderRma($data, $img_folder, $customer_id = 0) {

        $getDefaultStatus = $this->getDefaultStatus();

        $this->db->query("INSERT INTO `" . DB_PREFIX . "wk_rma_order` SET `order_id` = '" . (int) $data['order'] . "', `images` = '" . $this->db->escape($img_folder) . "',`add_info` = '" . $this->db->escape(nl2br($data['info'])) . "', `admin_status` = '" . ($getDefaultStatus ? $getDefaultStatus['status_id'] : 0 ) . "',`rma_auth_no` = '" . $this->db->escape($data['autono']) . "', `date`=NOW()");

        $rma_id = $this->db->getLastId();

        if (isset($this->session->data['rma_login']))
            $email = $this->session->data['rma_login'];
        else {
            $email = $this->db->query("SELECT email FROM `" . DB_PREFIX . "customer` WHERE `customer_id` = '" . (int) $customer_id . "'")->row['email'];
        }

        $this->db->query("INSERT INTO `" . DB_PREFIX . "wk_rma_customer` SET `rma_id` = '" . $rma_id . "', `customer_id` = '" . (int) $customer_id . "', `email` = '" . $this->db->escape($email) . "'");

        foreach ($data['selected'] as $key => $product) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "wk_rma_product` SET `rma_id` = '" . (int) $rma_id . "',`product_id` = '" . (int) $product . "',`quantity` = '" . (int) $data['quantity'][$key] . "',`reason` = '" . (int) $data['reason'][$key] . "', `order_product_id` = '" . (int) $data['product'][$key] . "'");
        }

        $data['rma_id'] = $rma_id;
        $data['customer_id'] = $customer_id;
        $data['customer_message'] = $data['info'];

        $this->mail($data, 'generate_admin');
        $this->mail($data, 'generate_customer');
    }

    public function getCustomerpos($rma = 0) {
        $customer = $this->db->query("SELECT o.* FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "wk_rma_order wro ON (wro.order_id = o.order_id) WHERE wro.id = '" . (int) $rma . "'")->row;
        return $customer;
    }

    public function mail($data, $mail_type = '') {

        $value_index = array();

        $this->load->language('catalog/rma_mail');

        $mail_message = '';

        switch ($mail_type) {

            case 'message_to_customer' :

                $mail_subject = $this->language->get('message_to_customer_subject');
                $mail_message = nl2br(sprintf($this->language->get('message_to_customer_message'), ucfirst($data['status'])));

                $customer_info = $this->getCustomerpos($data['rma_id']);
                $mail_to = $customer_info['email'];
                $mail_from = $this->config->get('config_email');

                $value_index = array(
                    'customer_name' => $customer_info['firstname'] . ' ' . $customer_info['lastname'],
                    'admin_message' => nl2br($data['message']),
                    'rma_id' => $data['rma_id'],
                );
                break;

            //admin changed status - message to customer
            case 'label_to_customer' :

                $mail_subject = $this->language->get('label_to_customer_subject');
                $mail_message = nl2br($this->language->get('label_to_customer_message'));

                $customer_info = $this->getCustomerpos($data['rma_id']);
                $mail_to = $customer_info['email'];
                $mail_from = $this->config->get('config_email');

                $value_index = array(
                    'customer_name' => $customer_info['firstname'] . ' ' . $customer_info['lastname'],
                    'rma_id' => $data['rma_id'],
                    'link' => $data['link']
                );

                if (file_exists(DIR_IMAGE . $data['label'])) {
                    $mail_attachment = DIR_IMAGE . $data['label'];
                }

                break;

            //customer added RMA
            case 'generate_customer' ://to customer

                $mail_subject = $this->language->get('generate_customer_subject');
                $mail_message = nl2br($this->language->get('generate_customer_message'));

                $customer_info = $this->getCustomerpos($data['rma_id']);
                $mail_to = $customer_info['email'];
                $mail_from = $this->config->get('config_email');

                $value_index = array(
                    'customer_name' => $customer_info['firstname'] . ' ' . $customer_info['lastname'],
                    'order_id' => $data['order'],
                    'rma_id' => $data['rma_id'],
                    'link' => defined('HTTP_CATALOG') ? HTTP_CATALOG . 'index.php?route=account/rma/viewrma&vid=' . $data['rma_id'] : $this->urlChange('account/rma/viewrma&vid=' . $data['rma_id'], '', 'SSL')
                );
                break;

            case 'generate_admin' ://to admin

                $mail_subject = $this->language->get('generate_admin_subject');
                $mail_message = nl2br($this->language->get('generate_admin_message'));

                $customer_info = $this->getCustomerpos($data['rma_id']);
                $mail_to = $this->config->get('config_email');
                $mail_from = $customer_info['email'];

                $value_index = array(
                    'customer_name' => $customer_info['firstname'] . ' ' . $customer_info['lastname'],
                    'rma_id' => $data['rma_id'],
                    'order_id' => $data['order'],
                );
                break;

            //customer send message to admin
            case 'message_to_admin' :

                $mail_subject = $this->language->get('message_to_admin_subject');
                $mail_message = nl2br($this->language->get('message_to_admin_message'));

                $customer_info = $this->getCustomerpos($data['rma_id']);
                $mail_to = $this->config->get('config_email');
                $mail_from = $customer_info['email'];

                $value_index = array(
                    'customer_name' => $customer_info['firstname'] . ' ' . $customer_info['lastname'],
                    'customer_message' => nl2br($data['message']),
                    'rma_id' => $data['rma_id'],
                );
                break;

            //customer changed status - message to admin
            case 'status_to_admin' :

                $mail_subject = $this->language->get('status_to_admin_subject');
                $mail_message = nl2br(sprintf($this->language->get('status_to_admin_message'), ucfirst($data['status'])));

                $customer_info = $this->getCustomerpos($data['rma_id']);
                $mail_to = $this->config->get('config_email');
                $mail_from = $customer_info['email'];

                $value_index = array(
                    'customer_name' => $customer_info['firstname'] . ' ' . $customer_info['lastname'],
                    'rma_id' => $data['rma_id'],
                );
                break;

            //customer changed status - message to customer
            case 'status_to_customer' :

                $mail_subject = $this->language->get('status_to_customer_subject');
                $mail_message = nl2br(sprintf($this->language->get('status_to_customer_message'), ucfirst($data['status'])));

                $customer_info = $this->getCustomerpos($data['rma_id']);
                $mail_to = $customer_info['email'];
                $mail_from = $this->config->get('config_email');

                $value_index = array(
                    'customer_name' => $customer_info['firstname'] . ' ' . $customer_info['lastname'],
                    'rma_id' => $data['rma_id'],
                );
                break;

            default :
                return;
        }


        if ($mail_message) {

            $data['store_name'] = $this->config->get('config_name');
            $data['store_url'] = HTTP_SERVER;
            $data['logo'] = HTTP_SERVER . 'image/' . $this->config->get('config_logo');

            $find = array(
                '{order_id}',
                '{rma_id}',
                '{product_name}',
                '{customer_name}',
                '{customer_message}',
                '{link}',
                '{config_logo}',
                '{config_icon}',
                '{config_currency}',
                '{config_image}',
                '{config_name}',
                '{config_owner}',
                '{config_address}',
                '{config_geocode}',
                '{config_email}',
                '{config_telephone}',
            );

            $replace = array(
                'order_id' => '',
                'rma_id' => '',
                'product_name' => '',
                'customer_name' => '',
                'customer_message' => '',
                'link' => '',
                'config_logo' => '<a href="' . HTTP_SERVER . '" title="' . $data['store_name'] . '"><img src="' . HTTP_SERVER . 'image/' . $this->config->get('config_logo') . '" alt="' . $data['store_name'] . '" style="max-width:200px;"/></a>',
                'config_icon' => '<img src="' . HTTP_SERVER . 'image/' . $this->config->get('config_icon') . '" style="max-width:200px;">',
                'config_currency' => $this->config->get('config_currency'),
                'config_image' => '<img src="' . HTTP_SERVER . 'image/' . $this->config->get('config_image') . '" style="max-width:200px;">',
                'config_name' => $this->config->get('config_name'),
                'config_owner' => $this->config->get('config_owner'),
                'config_address' => $this->config->get('config_address'),
                'config_geocode' => $this->config->get('config_geocode'),
                'config_email' => $this->config->get('config_email'),
                'config_telephone' => $this->config->get('config_telephone'),
            );

            $replace = array_merge($replace, $value_index);

            $mail_message = trim(str_replace($find, $replace, $mail_message));

            $data['subject'] = $mail_subject;
            $data['message'] = $mail_message;

            if (version_compare(VERSION, '2.2', '>=')) {
                $html = $this->load->view('catalog/rma_mail', $data);
            } else {
                if (isset($this->request->get['token']) && $this->session->data['token'] == $this->request->get['token']) {
                    $html = $this->load->view('catalog/rma_mail.tpl', $data);
                } else {
                    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/catalog/rma_mail.tpl')) {
                        $html = $this->load->view($this->config->get('config_template') . '/template/catalog/rma_mail.tpl', $data);
                    } else {
                        $html = $this->load->view('default/template/catalog/rma_mail.tpl', $data);
                    }
                }
            }

            if (preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $mail_to) AND preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $mail_from)) {
                if (version_compare(VERSION, '2.0.1.1', '<=')) {
                    $mail = new Mail($this->config->get('config_mail'));
                    $mail->setTo($mail_to);
                    $mail->setFrom($mail_from);
                    $mail->setSender($data['store_name']);
                    $mail->setSubject($data['subject']);
                    $mail->setHtml($html);
                    $mail->setText(strip_tags($html));
                    $status = $mail->send();
                } else {
                    $mail = new Mail();
                    $mail->protocol = $this->config->get('config_mail_protocol');
                    $mail->parameter = $this->config->get('config_mail_parameter');
                    $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
                    $mail->smtp_username = $this->config->get('config_mail_smtp_username');
                    $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
                    $mail->smtp_port = $this->config->get('config_mail_smtp_port');
                    $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
                    $mail->setTo($mail_to);
                    $mail->setFrom($mail_from);
                    $mail->setSender($data['store_name']);
                    $mail->setSubject($data['subject']);
                    $mail->setHtml($html);
                    $mail->setText(strip_tags($html));
                    $mail->send();
                }
            }
        }
    }

    public function getDefaultStatus() {
        $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "wk_rma_status` WHERE `default`='admin'");
        return $result->row;
    }

    private function validateAddRma() {

        if (!isset($this->request->post['order']) && !$this->request->post['order'] || utf8_strlen($this->request->post['order']) < 1) {
            $this->error['warning'] = $this->language->get('error_order');
        }

        if (!isset($this->error['warning']) && isset($this->request->post['selected'])) {
            foreach ($this->request->post['selected'] as $key => $value) {
                if (!(int) $this->request->post['quantity'][$key]) {
                    $this->error['warning'] = $this->language->get('error_product_qty');
                    break;
                } elseif (!(int) $this->request->post['reason'][$key]) {
                    $this->error['warning'] = $this->language->get('error_reason');
                    break;
                } elseif (!$this->validateOrder($this->request->post['quantity'][$key], $this->request->post['product'][$key])) {
                    $this->error['warning'] = $this->language->get('error_product_qty_error');
                    break;
                }
            }
        } else {
            $this->error['warning'] = $this->language->get('error_product');
        }

        if (utf8_strlen($this->request->post['info']) < 1 || utf8_strlen($this->request->post['info']) > 4000) {
            $this->error['warning'] = $this->language->get('error_info');
        }

        if ($this->config->get('wk_rma_system_information')) {
            if (!isset($this->request->post['agree'])) {
                $this->error['warning'] = $this->language->get('error_agree');
            }
        }

        return !$this->error;
    }

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

                if (!$allowed || $allowed != '*' && !in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), explode(',', $allowed))) {
                    $this->error['warning'] = sprintf($this->language->get('error_image'), $this->config->get('wk_rma_system_image'), $this->config->get('wk_rma_system_size'));
                }

                if ($image['size'] > ((int) $this->config->get('wk_rma_system_size') * 1024)) {
                    $this->error['warning'] = sprintf($this->language->get('error_image'), $this->config->get('wk_rma_system_image'), $this->config->get('wk_rma_system_size'));
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

    public function getrmaorders($data = array(), $customer_id = 0) {
        $customer_id = (int) $customer_id;
        $query = '';

        if (!$customer_id && isset($data['email']))
            $query .= "AND email = '" . $this->db->escape($data['email']) . "'";

        if ((int) $this->config->get('wk_rma_system_time'))
            $query .= " AND o.date_added >= DATE_SUB(CURDATE(), INTERVAL '" . (int) $this->config->get('wk_rma_system_time') . "' DAY) ";

        if ($this->config->get('wk_rma_system_orders')) {
            $sql = "SELECT o.order_id,o.total,SUM(op.quantity) as quantity FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) WHERE o.customer_id = '" . $customer_id . "' $query AND o.order_status_id IN (" . implode(',', $this->config->get('wk_rma_system_orders')) . ")";
        } else {
            return array();
        }

        $sql .= " GROUP BY o.order_id ORDER BY o.order_id DESC";

        $results = $this->db->query($sql)->rows;

        $order = array(
            '0' => 0
        );

        foreach ($results as $result) {
            $order[] = $result['order_id'];
        }

        $rma_array = $this->db->query("SELECT SUM(wrp.quantity) as quantity,order_id FROM `" . DB_PREFIX . "wk_rma_order` wro LEFT JOIN `" . DB_PREFIX . "wk_rma_product` wrp ON (wrp.rma_id = wro.id) WHERE admin_return <> 1 AND wro.order_id IN (" . implode(',', $order) . ") GROUP BY wro.order_id")->rows;

        foreach ($rma_array as $value) {
            foreach ($results as $key => $result) {
                if (!$result['quantity']) {
                    unset($results[$key]);
                    continue;
                }
                if ($result['order_id'] == $value['order_id']) {
                    $results[$key]['quantity'] = $result['quantity'] - $value['quantity'];
                    if ($results[$key]['quantity'] < 1) {
                        unset($results[$key]);
                    }
                }
            }
        }
        return $results;
    }

    public function AddRma() {

        if (!$this->config->get('wk_rma_status'))
            $this->response->redirect($this->urlChange('account/login', '', true));

        /* if (!$this->customer->isLogged() AND !isset($this->session->data['rma_login'])) {
          $this->session->data['redirect'] = $this->urlChange('account/rma/addrma', '', true);
          $this->response->redirect($this->urlChange('account/rma/rmalogin', '', true));;
          } */

        $data = $this->language->load('account/rma/addrma');

        $this->load->model('account/rma/rma');

        $this->document->addstyle('catalog/view/theme/default/stylesheet/rma/rma.css');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

        if (isset($this->request->get['filter_order'])) {
            $filter_order = $this->request->get['filter_order'];
        } else {
            $filter_order = null;
        }

        if (isset($this->request->get['filter_date'])) {
            $filter_date = $this->request->get['filter_date'];
        } else {
            $filter_date = null;
        }

        if (isset($this->request->get['filter_model'])) {
            $filter_model = $this->request->get['filter_model'];
        } else {
            $filter_model = null;
        }
        $order_id = $this->request->get['order_id'];
        $customer_info_data = $this->db->query("SELECT email, customer_id FROM `" . DB_PREFIX . "order` WHERE order_id='" . $order_id . "' ");

        if ($customer_info_data->num_rows) {
            $cust_email = $customer_info_data->row['email'];
            $customer_id = $customer_info_data->row['customer_id'];
        } else {
            $cust_email = "";
            $customer_id = 0;
        }

        $filter_data = array(
            'filter_model' => $filter_model,
            'filter_date' => $filter_date,
            'filter_order' => $filter_order,
            'email' => $cust_email
        );

        $data['text_allowed_ex'] = sprintf($this->language->get('text_allowed_ex'), $this->config->get('wk_rma_system_image'), $this->config->get('wk_rma_system_size'));
        $data['text_order_info'] = sprintf($this->language->get('text_order_info'), $this->config->get('wk_rma_system_time'));
        $data['error_image'] = sprintf($this->language->get('error_image'), $this->config->get('wk_rma_system_image'), $this->config->get('wk_rma_system_size'));

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {


            $img_folder = '';
            $image_array = false;

            if ($this->request->post['image_array'])
                $image_array = explode(',', $this->request->post['image_array']);

            $files = $this->request->files['rma_file'];
            $img_folder_rma = 'rma/' . ($customer_id ? $customer_id : 0 ) . '_' . $this->request->post['order'] . '_' . time();
            $img_folder = DIR_IMAGE . $img_folder_rma;
            $images = $this->validateImage($this->request->files['rma_file']);

            if (!is_dir($img_folder)) {
                @chmod(DIR_IMAGE . 'rma/', 0755);
                @mkdir($img_folder);
                @mkdir($img_folder . '/files');
            }
            if ($images) {
                foreach ($images as $key => $image) {
                    $target = $img_folder . '/' . $image['name'];
                    @move_uploaded_file($image['tmp_name'], $target);
                }
            }

            $this->insertOrderRma($this->request->post, $img_folder_rma, $customer_id);

            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('module/pos/main&token=' . $this->session->data['token'], '', true));
        }

        $wk_admin_rma_policy = $this->language->get('must_read_policy');
        $data['text_agree'] = '';
        /*   if ($this->config->get('wk_rma_system_information')) {
          $this->load->model('catalog/information');
          $information_info = $this->model_catalog_information->getInformation($this->config->get('wk_rma_system_information'));
          if($information_info)
          $data['text_agree'] = sprintf($this->language->get('text_agree'), $this->urlChange('information/information/agree', 'information_id=' . $this->config->get('wk_rma_system_information'), 'SSL'), $information_info['title'], $information_info['title']);
          } */

        $text = $this->config->get('wk_rma_admin_policy_setting');
        $text = trim(html_entity_decode($text));
        $text = str_replace("&lt;", "<", $text);
        $text = str_replace("&quot;", '"', $text);
        $data['wk_admin_rma_policy'] = str_replace("&gt;", ">", $text);

        $results = $this->getrmaorders($filter_data, $customer_id);

        foreach ($results as $key => $result) {
            $results[$key]['total'] = Membership::currencyFormat($result['total'], $this->session->data['currency']);
        }

        $data['order_result'] = $results;


        $url = '';

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order'])) {
            $url .= '&filter_order=' . $this->request->get['filter_order'];
        }

        if (isset($this->request->get['filter_date'])) {
            $url .= '&filter_date=' . urlencode(html_entity_decode($this->request->get['filter_date'], ENT_QUOTES, 'UTF-8'));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->urlChange('common/home' . $url, '', true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_list'),
            'href' => $this->urlChange('account/rma/rma' . $url, '', true),
            'separator' => $this->language->get('text_separator')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->urlChange('account/rma/addrma' . $url, '', true),
            'separator' => $this->language->get('text_separator')
        );

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
        } else {
            $data['success'] = '';
        }

        $config_data = array(
            'order',
            'status',
            'info',
            'autono',
            'selected',
            'reason',
            'quantity',
            'product',
            'agree',
        );

        foreach ($config_data as $value) {
            if (isset($this->request->post[$value])) {
                $data[$value] = $this->request->post[$value];
            } else {
                $data[$value] = '';
            }
        }

        $config_data = array(
            'selected',
            'reason',
            'quantity',
        );

        $data['json_selected'] = array();
        if (isset($this->request->post['json_select'])) {
            foreach ($this->request->post['json_select'] as $key => $select) {
                foreach ($this->request->post['product'] as $key2 => $value) {
                    if ($value == $select)
                        foreach ($config_data as $config) {
                            if (isset($this->request->post[$config][$key2])) {
                                $data['json_selected'][$key][$config] = $this->request->post[$config][$key2];
                            } else {
                                $data['json_selected'][$key][$config] = '';
                            }
                        }
                }
            }
        }

        $data['json_selected'] = json_encode($data['json_selected']);

        if (isset($this->request->get['order_id']) && $this->request->get['order_id']) {
            $data['order'] = (int) $this->request->get['order_id'];
        }

        if (isset($this->request->get['product_id']) && $this->request->get['product_id']) {
            $data['product_id'] = (int) $this->request->get['product_id'];
        } else {
            $data['product_id'] = '';
        }

        $data['filter_order'] = $filter_order;
        $data['filter_model'] = $filter_model;
        $data['filter_date'] = $filter_date;

        $data['reasons'] = $this->model_account_rma_rma->getCustomerReason();
        $data['statuss'] = $this->model_account_rma_rma->getCustomerStatus();

        $data['action'] = $this->urlChange('module/pos/AddRma&order_id=' . $this->request->get['order_id'] . '&token=' . $this->session->data['token'], '', true);
        $data['back'] = $this->urlChange('module/pos/main&token=' . $this->session->data['token'], '', true);

        echo '<div>
          <form action="' . $data['action'] . '" method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class="form-group required">
              <label class="col-sm-12 text-left control-label" for="input-order"><span class="modal-button" data-value="' . $data['text_order_info'] . '">' . $data['text_rma_order'] . '</span></label>
              <div class="col-sm-12">
                <select name="order" id="input-order" class="form-control">';
        if ($data['order_result']) {
            echo '<option value="">' . $data['text_select_o'] . '</option>';
            foreach ($data['order_result'] as $order_res) {
                if ($order_res['order_id'] == $order) {
                    echo '<option value="' . $order_res['order_id'] . '" selected># ' . $order_res['order_id'] . ' , ' . $order_res['total'] . '</option>';
                } else {
                    echo '<option value="' . $order_res['order_id'] . '" ># ' . $order_res['order_id'] . ' , ' . $order_res['total'] . '</option>';
                }
            }
        } else {
            echo '<option>' . $data['text_select_no_o'] . '</option>';
        }
        echo '</select>';
        echo '</div>';
        echo '</div>';

        echo '<div class="form-group required">';
        echo '<label class="col-sm-12 text-left control-label">' . $data['text_rma_itemorder'] . '</label>';
        echo '<div class="col-sm-12">
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <tr>
                            <td class="text-left">Select to Return</td>';
        echo '<td class="text-left">' . $data['text_rma_product'] . '</td>';
        echo '<td class="text-left">' . $data['text_rma_sku'] . '</td>';
        echo '<td class="text-left">' . $data['text_rma_quantity'] . '</td>';
        echo '<td class="text-left">' . $data['text_rma_reason'] . '</td>';
        echo '<td class="text-left">' . $data['text_rma_quantity'] . '</td>';
        echo '</tr>
                        </tr>
                      </thead>
                      <tbody id="add_rma_here"></tbody>
                    </table>
                  </div>
              </div>
            </div>';

        echo '<div class="form-group">
              <label class="col-sm-12 text-left control-label" for="input-images"><span class="modal-button" data-value="' . $data['text_allowed_ex'] . '">' . $data['text_rma_imagespro'] . '</span></label>';
        echo '<div class="col-sm-12">
                <label type="button" for="upload-file" class="btn btn-primary pull-right" data-toggle="tooltip" title="' . $data['text_upload_img'] . '"><i class="fa fa-picture-o"></i> </label>
                <input type="file" name="rma_file[]" id="upload-file" accept="image/*" multiple value="" style="display:none" >
                <input type="hidden" name="image_array">
                <div class="clearfix"></div>
                <div class="rma-images-div"></div>

              </div>
            </div>

            <div class="form-group required">
              <label class="col-sm-12 text-left control-label" for="input-addinfo">' . $data['text_rma_addinfo'] . '</label>
              <div class="col-sm-12">
                <textarea rows="4" name="info" class="form-control" id="input-addinfo">' . $data['info'] . '</textarea>
              </div>
            </div>';


        echo '<div class="form-group required">';
        if ($data['text_agree']) {
            echo '<div class="col-sm-12 clearfix">';
            if ($data['agree']) {
                echo '<input type="checkbox" name="agree" value="1" checked="checked"  id="input-agree" class="pull-left" />';
            } else {
                echo '<input type="checkbox" name="agree" value="1" id="input-agree" class="pull-left" />';
            }
            echo '<label class="control-label" for="input-agree">' . $data['text_agree'] . '</label>
                </div>';
        }
        echo '</div>

            <div class="buttons clearfix">
              <div class="pull-left"><a href="' . $data['back'] . '" class="btn btn-default">' . $data['button_back'] . '</a></div>
              <div class="pull-right">
                <input type="submit" value="' . $data['button_continue'] . '" class="btn btn-primary" />
              </div>
            </div>

        </form>
      </div>';

        echo "<script>

$('#input-order').change(function(){
  val_order = $('#input-order').val();

  $.ajax({
    type: 'POST',
    dataType: 'json',
    data: ({order : val_order }),
    url: 'index.php?route=module/pos/getorder&token=" . $this->session->data['token'] . "',
    success: function(data){
      $('#add_rma_here').html('');
      count = 0;
      $.each(data,function(index,element){
        if(element['product_id']!=undefined && element['product_id']!=''){
          html = '<tr>';
          html+= '<td class=\"text-left\"><input type=\"checkbox\" name=\"selected[]\" value=\"'+element['product_id']+'\" class=\"selectedCheck select_row' + count + '\"></td>';
          html+= '<td class=\"text-left\"><a target=\"_blank\" href=index.php?route=product/product&product_id='+element['product_id']+'>'+element['name']+'</a>';
          if(element['option']!=undefined && element['option']!=''){
            for(j in element['option']) {
              html+= '<br />';
              html+= '&nbsp;<small> - ' + element['option'][j]['name'] + ':' + element['option'][j]['value'] + '</small>';
            }
          }
          html+= '</td>';
          html+= '<td class=\"text-left\">'+element['model']+'</td>';
          html+= '<td class=\"text-left\">'+element['quantity']+'</td>';
          html+= '<td class=\"text-left\"><select name=\"reason[]\" class=\"form-control selectReason disableCheck select_row' + count + '\" disabled>';";
        if ($data['reasons']) {
            foreach ($data['reasons'] as $reasonss) {
                echo " html+= '<option value=\"";
                echo $reasonss['id'];
                echo "\">";
                echo $reasonss['reason'];
                echo "</option>';";
            }
        } else {
            echo " html+= '<option value=\"\"></option>';";
        }
        echo "html+= '</select></td>';";
        echo "html+= '<td class=\"text-left\"><input type=\"number\" name=\"quantity[]\" value=\"\" min=1 max=\"'+element['quantity']+'\" class=\"form-control disableCheck selectNumber select_row' + count + '\" disabled required><input type=\"hidden\" name=\"product[]\" value=\"'+element['order_product_id']+'\" disabled class=\"disableCheck\"><input type=\"hidden\" name=\"json_select['+count+']\" value=\"'+element['order_product_id']+'\"></td></tr>';
          $('#add_rma_here').append(html);
          count++;
        }
      });
      selectSelected();
     }
 });

});
var flag_first_check = 0";

        if ($data['order']) {
            echo "
  $(document).ready(function() {
    flag_first_check = 1;
    $('#input-order').trigger('change');
  });";
        }
        echo "
function selectSelected() {
  if (flag_first_check) {
  	";
        if ($data['json_selected']) {
            echo "
      var json_selected = JSON.parse(JSON.stringify(" . $data['json_selected'] . "));";
            echo "$.each(json_selected, function(i,e){
        $.each(e, function(index, element){
          class_row = 'select_row' + i;
          switch(index) {
            case 'selected':
                $('.selectedCheck.' + class_row).trigger('click');
                break;
            case 'reason':
                $('.selectReason.' + class_row).val(element);
                break;
            case 'quantity':
                $('.selectNumber.' + class_row).val(element);
                break;
          }
        });
      });";
        }
        echo "
    flag_first_check = 0;
  }
}
function imageArrayRemove(thisthis){
  $(image_array).each(function(key,value){
    if(thisthis.id==value)
      image_array[key] = '';
  });
  $(thisthis).next('div.tooltip').remove();
  $(thisthis).remove();
  $('input[name=\'image_array\']').val(image_array);
}

function readURL(input) {
    if (input.files && input.files[0]) {
      image_array = Array();
      for (i = 0; i < input.files.length; i++) {
        var reader = new FileReader();
        reader.onload = function (e) {
          image_array[$('.rma-images-div img').length] = 'rma-img-'+$('.rma-images-div img').length;
          $('.rma-images-div').append('<img class=\"img-thumbnail rma-image\" data-toggle=\"tooltip\" title=\"Click To Remove\" src=\"'+e.target.result+'\" id=\"rma-img-'+$('.rma-images-div img').length+'\" onclick=\"imageArrayRemove(this)\"/>');
        }
        reader.readAsDataURL(input.files[i]);
      }
    }
    $('body').tooltip({ selector: '[data-toggle=tooltip]' });
    $('input[name=\'image_array\']').val(image_array);
}

$('#upload-file').change(function(){
    $('.rma-images-div').html('');
    readURL(this);
});

$('.datetime').datetimepicker({
  pickTime: false
});

function changeAll(thisthis){
  $('input[name*=\'selected\']').prop('checked', thisthis.checked);
  if(thisthis.checked){
    $('.disableCheck').prop('disabled',false);
  }else{
    $('.disableCheck').prop('disabled',true);
  }
}

jQuery('.modal-button').bind('click', function() {

    var thisProp = $(this).data('value');
//    alert (thisProp);
    jQuery('#helpmodal .msg' ).html( thisProp );
    $( '#helpmodal' ).dialog();
});

jQuery('#add_rma_here').on('click','.selectedCheck', function(){
  if(this.checked)
    $(this).parents('tr').find('.disableCheck').prop('disabled',false);
   else
    $(this).parents('tr').find('.disableCheck').prop('disabled',true);
})
      </script>";
    }

    public function getOrderStatus($order = 0) {
        $result = $this->db->query("SELECT order_status_id FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $order . "'")->row;

        if ($result) {
            return $result['order_status_id'];
        }
        return false;
    }

    public function orderprodetails($order, $customer_id = 0) {
        if ($this->config->get('wk_rma_system_orders')) {
            $results = $this->db->query("SELECT op.name,op.product_id,op.model,op.quantity,op.order_product_id,(SELECT SUM(quantity) FROM `" . DB_PREFIX . "wk_rma_product` wrp LEFT JOIN `" . DB_PREFIX . "wk_rma_order` wro ON (wrp.rma_id = wro.id) WHERE order_product_id = op.order_product_id AND wro.cancel_rma <>1 AND admin_return <> 1 GROUP BY order_product_id) as rma_quantity_sum FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (o.order_id = op.order_id) WHERE o.order_id='" . $this->db->escape($order) . "' AND o.customer_id='" . (int) $customer_id . "' AND o.order_status_id IN (" . implode(',', $this->config->get('wk_rma_system_orders')) . ")")->rows;

            foreach ($results as $key => $result) {
                $results[$key]['quantity'] = $result['quantity'] - $result['rma_quantity_sum'];
                if ($results[$key]['quantity'] < 1) {
                    unset($results[$key]);
                }
            }
            return $results;
        }
        return array();
    }

    public function getOrderOptions($order_id, $order_product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $order_product_id . "'");

        return $query->rows;
    }

    public function getUploadByCode($code) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "upload` WHERE code = '" . $this->db->escape($code) . "'");

        return $query->row;
    }

    public function getorder() {

        if (isset($this->request->post['order']) && $this->request->post['order']) {


            $cust_id = $this->db->query("SELECT customer_id FROM `" . DB_PREFIX . "order` WHERE order_id='" . $this->request->post['order'] . "' ")->row['customer_id'];

            $products = $this->orderprodetails((int) $this->request->post['order'], (int) $cust_id);
            $order_status_id = $this->getOrderStatus((int) $this->request->post['order']);

            foreach ($products as $key => $product) {
                $option_data = array();

                $options = $this->getOrderOptions((int) $this->request->post['order'], $product['order_product_id']);

                foreach ($options as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $upload_info = $this->getUploadByCode($option['value']);
                        if ($upload_info) {
                            $value = $upload_info['name'];
                        } else {
                            $value = '';
                        }
                    }
                    $option_data[] = array(
                        'name' => $option['name'],
                        'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                    );
                }
                $products[$key]['option'] = $option_data;
                $products[$key]['order_status_id'] = $order_status_id;
            }
            $this->response->setOutput(json_encode($products));
        }
    }

    private function validateOrder($qty, $order_p_id) {
        $this->load->model('account/rma/rma');
        return $this->model_account_rma_rma->validateOrder($qty, $order_p_id);
    }

    public function index() {
        $this->language->load('module/pos');

        $heading_title = $this->language->get('pos_heading_title') . ' V' . POS_VERSION;
        $this->document->setTitle($heading_title);

        $this->load->model('setting/setting');
        $this->load->model('pos/pos');
        $this->load->model('account/rma/rma');
        $data['reasons'] = $this->model_account_rma_rma->getCustomerReason();
        $data['statuss'] = $this->model_account_rma_rma->getCustomerStatus();
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $data = $this->model_setting_setting->getSetting('POS');
            foreach ($data as $key => $value) {
                if (!array_key_exists($key, $this->request->post)) {
                    // for those unchecked checkbox, php will not submit the value, need to remove the values from the config
                    unset($data[$key]);
                }
            }
            foreach ($this->request->post as $key => $value) {
                $data['POS_' . $key] = $value;
                if ($key == 'p_logo' && strpos($value, '//') === false) {
                    if (strpos($value, '/pos/') === false) {
                        $data['POS_' . $key] = HTTP_CATALOG . 'image/' . $value;
                    } else {
                        $data['POS_' . $key] = $value;
                    }
                }
            }
            $this->model_setting_setting->editSetting('POS', $data);

            $this->session->data['success'] = $this->language->get('text_success');

            if (empty($this->session->data['is_label_user'])) {
                $this->response->redirect($this->url->link('module/pos', 'token=' . $this->session->data['token'], 'SSL'));
            }
        }

        $data['heading_title'] = $heading_title;
        $data['text_edit_pos_settings'] = $this->language->get('text_edit_pos_settings');

        $data['tab_settings_payment_type'] = $this->language->get('tab_settings_payment_type');
        $data['tab_settings_options'] = $this->language->get('tab_settings_options');
        $data['tab_settings_order'] = $this->language->get('tab_settings_order');
        $data['tab_settings_creditcard'] = $this->language->get('tab_settings_creditcard');
        $data['tab_settings_receipt'] = $this->language->get('tab_settings_receipt');
        $data['tab_settings_customer'] = $this->language->get('tab_settings_customer');
        $data['tab_settings_discount'] = $this->language->get('tab_settings_discount');
        $data['tab_settings_rounding'] = $this->language->get('tab_settings_rounding');
        $data['tab_settings_affiliate'] = $this->language->get('tab_settings_affiliate');
        $data['tab_settings_quote'] = $this->language->get('tab_settings_quote');
        $data['tab_settings_table_management'] = $this->language->get('tab_settings_table_management');
        $data['tab_settings_virtualmerchant'] = $this->language->get('tab_settings_virtualmerchant');
        $data['tab_settings_operations'] = $this->language->get('tab_settings_operations');

        $data['text_print_type_setting'] = $this->language->get('text_print_type_setting');
        $data['text_print_type_invoice'] = $this->language->get('text_print_type_invoice');
        $data['text_print_type_receipt'] = $this->language->get('text_print_type_receipt');
        $data['config_print_type'] = $this->config->get('POS_config_print_type') ? $this->config->get('POS_config_print_type') : 'receipt';
        $data['text_barcode_for_product'] = $this->language->get('text_barcode_for_product');
        $data['barcode_for_product'] = $this->config->get('POS_barcode_for_product') ? $this->config->get('POS_barcode_for_product') : 0;
        // add for cc receipt begin
        $data['text_email_receipt_cc_setting'] = $this->language->get('text_email_receipt_cc_setting');
        $data['text_email_receipt_cc'] = $this->language->get('text_email_receipt_cc');
        $data['enable_email_receipt_cc'] = $this->config->get('POS_enable_email_receipt_cc') ? $this->config->get('POS_enable_email_receipt_cc') : 0;
        $data['email_receipt_cc_account'] = $this->config->get('POS_email_receipt_cc_account') ? $this->config->get('POS_email_receipt_cc_account') : $this->config->get('config_email');
        // add for cc receipt end

        $data['text_lock_order_status_setting'] = $this->language->get('text_lock_order_status_setting');
        $data['text_lock_order_status_message'] = $this->language->get('text_lock_order_status_message');
        $order_locking_status = array();
        if ($this->config->get('POS_order_locking_status')) {
            $order_locking_status = $this->config->get('POS_order_locking_status');
        }
        $data['order_locking_status'] = $order_locking_status;

        $data['entry_more_settings'] = $this->language->get('entry_more_settings');
        $more_settings = array('rounding', 'discount', 'affiliate', 'quote', 'table_management', 'sales_report');
        foreach ($more_settings as $more_setting) {
            if ($this->config->get('POS_enable_settings_' . $more_setting)) {
                $data['enable_settings_' . $more_setting] = $this->config->get('POS_enable_settings_' . $more_setting);
            } else {
                $data['enable_settings_' . $more_setting] = 0;
            }
        }
        $data['more_settings'] = $more_settings;

        $data['payment_types'] = $this->config->get('POS_POS_payment_types') ? $this->config->get('POS_POS_payment_types') : array();
        $data['payment_type_enables'] = $this->config->get('POS_payment_type_enables') ? $this->config->get('POS_payment_type_enables') : array();

        $data['text_order_payment_type'] = $this->language->get('text_order_payment_type');
        $data['text_order_payment_eanble'] = $this->language->get('text_order_payment_eanble');
        $data['text_action'] = $this->language->get('text_action');
        $data['text_type_already_exist'] = $this->language->get('text_type_already_exist');
        $data['text_payment_type_setting'] = $this->language->get('text_payment_type_setting');
        // add for Openbay integration begin
        $data['text_openbay_setting'] = $this->language->get('text_openbay_setting');
        $data['text_openbay_enable'] = $this->language->get('text_openbay_enable');
        // add for Openbay integration end
        $data['text_display_setting'] = $this->language->get('text_display_setting');
        $data['text_display_once_login'] = $this->language->get('text_display_once_login');
        $data['column_exclude'] = $this->language->get('column_exclude');
        $data['text_select_all'] = $this->language->get('text_select_all');
        $data['text_unselect_all'] = $this->language->get('text_unselect_all');
        $data['button_delete'] = $this->language->get('button_delete');

        // add for Print begin
        $data['text_print_setting'] = $this->language->get('text_print_setting');
        $data['entry_print_log'] = $this->language->get('entry_print_log');
        $data['entry_print_width'] = $this->language->get('entry_print_width');
        $data['text_print_browse'] = $this->language->get('text_print_browse');
        $data['text_print_image_manager'] = $this->language->get('text_print_image_manager');
        $data['text_p_complete'] = $this->language->get('text_p_complete');
        $data['text_p_payment'] = $this->language->get('text_p_payment');
        $data['entry_term_n_cond'] = $this->language->get('entry_term_n_cond');
        // add for Print end
        // add for offline mode begin
        $data['text_offline_mode_setting'] = $this->language->get('text_offline_mode_setting');
        $data['text_offline_mode_enable'] = $this->language->get('text_offline_mode_enable');
        $data['enable_offline_mode'] = $this->config->get('POS_enable_offline_mode') ? $this->config->get('POS_enable_offline_mode') : 0;
        // add for offline mode end
        // add for Hiding Delete begin
        $data['text_hide_delete_setting'] = $this->language->get('text_hide_delete_setting');
        $data['text_hide_delete_enable'] = $this->language->get('text_hide_delete_enable');
        // add for Hiding Delete end
        // add for Hiding Order Status begin
        $data['text_hide_order_status_setting'] = $this->language->get('text_hide_order_status_setting');
        $data['text_hide_order_status_message'] = $this->language->get('text_hide_order_status_message');
        // add for Hiding Order Status end
        // add for User as Affiliate begin
        $data['text_user_affi_setting'] = $this->language->get('text_user_affi_setting');
        $data['column_ua_user'] = $this->language->get('column_ua_user');
        $data['column_ua_affiliate'] = $this->language->get('column_ua_affiliate');
        $data['column_ua_action'] = $this->language->get('column_ua_action');
        // add for User as Affiliate end
        // add for Default Customer begin
        $data['text_customer_setting'] = $this->language->get('text_customer_setting');
        $data['text_customer_system'] = $this->language->get('text_customer_system');
        $data['text_customer_custom'] = $this->language->get('text_customer_custom');
        $data['text_customer_existing'] = $this->language->get('text_customer_existing');
        $data['text_customer_info'] = $this->language->get('text_customer_info');
        $data['text_address_info'] = $this->language->get('text_address_info');
        $data['text_customer'] = $this->language->get('text_customer');
        $this->language->load('sale/order');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_address_1'] = $this->language->get('entry_address_1');
        $data['entry_address_2'] = $this->language->get('entry_address_2');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_postcode'] = $this->language->get('entry_postcode');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_autocomplete'] = $this->language->get('text_autocomplete');
        $data['text_customer_group'] = $this->language->get('text_customer_group');
        // add for Default Customer end
        // add for Maximum Discount begin
        $data['text_max_discount_setting'] = $this->language->get('text_max_discount_setting');
        $data['column_group'] = $this->language->get('column_group');
        $data['column_discount_limit'] = $this->language->get('column_discount_limit');
        $data['entry_max_discount_fixed'] = $this->language->get('entry_max_discount_fixed');
        $data['entry_max_discount_percentage'] = $this->language->get('entry_max_discount_percentage');
        // add for Maximum Discount end
        // add for Quotation begin
        $data['text_quote_status_setting'] = $this->language->get('text_quote_status_setting');
        $data['column_quote_status_name'] = $this->language->get('column_quote_status_name');
        $data['column_quote_status_action'] = $this->language->get('column_quote_status_action');
        $data['button_rename'] = $this->language->get('button_rename');
        $data['text_rename'] = $this->language->get('text_rename');
        $data['text_quote_status_already_exist'] = $this->language->get('text_quote_status_already_exist');
        // add for Quotation end
        // add for Empty order control begin
        $data['text_status_initial'] = $this->language->get('text_status_initial');
        $data['text_status_deleted'] = $this->language->get('text_status_deleted');
        $data['text_empty_order_control_setting'] = $this->language->get('text_empty_order_control_setting');
        $data['text_empty_order_control_delete_setting'] = $this->language->get('text_empty_order_control_delete_setting');
        $data['text_delete_order_with_no_products'] = $this->language->get('text_delete_order_with_no_products');
        $data['text_delete_order_with_inital_status'] = $this->language->get('text_delete_order_with_inital_status');
        $data['text_delete_order_with_deleted_status'] = $this->language->get('text_delete_order_with_deleted_status');
        $data['text_empty_order_control_action'] = $this->language->get('text_empty_order_control_action');
        // add for Empty order control end
        // add for Cash type begin
        $data['text_cash_type_setting'] = $this->language->get('text_cash_type_setting');
        $data['column_cash_type'] = $this->language->get('column_cash_type');
        $data['column_cash_image'] = $this->language->get('column_cash_image');
        $currency_symbol = $this->currency->getSymbolLeft($this->config->get('config_currency'));
        if ($currency_symbol == '') {
            $currency_symbol = $this->currency->getSymbolRight($this->config->get('config_currency'));
        }
        $data['column_cash_value'] = $this->language->get('column_cash_value') . ' (' . $currency_symbol . ')';
        $data['column_cash_action'] = $this->language->get('column_cash_action');
        $data['text_cash_type_note'] = $this->language->get('text_cash_type_note');
        $data['text_cash_type_coin'] = $this->language->get('text_cash_type_coin');
        $data['column_cash_display'] = $this->language->get('column_cash_display');
        // add for Cash type end
        // add for UPC/SKU/MPN begin
        $data['text_scan_type_setting'] = $this->language->get('text_scan_type_setting');
        $data['text_scan_type_upc'] = $this->language->get('text_scan_type_upc');
        $data['text_scan_type_sku'] = $this->language->get('text_scan_type_sku');
        $data['text_scan_type_mpn'] = $this->language->get('text_scan_type_mpn');
        $data['config_scan_type'] = $this->config->get('POS_config_scan_type');
        // add for UPC/SKU/MPN end
        // add for table management begin
        $data['text_table_management_setting'] = $this->language->get('text_table_management_setting');
        $data['text_table_management_enable'] = $this->language->get('text_table_management_enable');
        $data['entry_table_layout'] = $this->language->get('entry_table_layout');
        $data['text_table_layout'] = $this->language->get('text_table_layout');
        $data['entry_table_name'] = $this->language->get('entry_table_name');
        $data['entry_table_desc'] = $this->language->get('entry_table_desc');
        $data['button_set_table'] = $this->language->get('button_set_table');
        $data['button_delete_table'] = $this->language->get('button_delete_table');
        $data['text_table_name_empty'] = $this->language->get('text_table_name_empty');
        $data['text_table_name_exists'] = $this->language->get('text_table_name_exists');
        $data['entry_table_number'] = $this->language->get('entry_table_number');
        $data['button_table_set_number'] = $this->language->get('button_table_set_number');
        $data['column_table_id'] = $this->language->get('column_table_id');
        $data['column_table_desc'] = $this->language->get('column_table_desc');
        $data['column_table_action'] = $this->language->get('column_table_action');
        $data['button_table_modify'] = $this->language->get('button_table_modify');
        $data['button_table_remove'] = $this->language->get('button_table_remove');
        $data['enable_table_management'] = $this->config->get('POS_enable_table_management');

        $data['img_table_layout'] = $this->config->get('POS_img_table_layout');
        $data['tables'] = $this->model_pos_pos->getTables(0);
        $data['table_number'] = sizeof($data['tables']);
        // add for table management end
        // add for Complete Status begin
        $data['text_order_status_setting'] = $this->language->get('text_order_status_setting');
        $data['entry_complete_status'] = $this->language->get('entry_complete_status');
        $data['entry_return_complete_status'] = $this->language->get('entry_return_complete_status');
        $data['complete_status_id'] = $this->config->get('POS_complete_status_id') ? $this->config->get('POS_complete_status_id') : 5;
        $data['return_complete_status_id'] = $this->config->get('POS_return_complete_status_id') ? $this->config->get('POS_return_complete_status_id') : 3;
        $data['entry_parking_status'] = $this->language->get('entry_parking_status');
        $data['parking_status_id'] = $this->config->get('POS_parking_status_id') ? $this->config->get('POS_parking_status_id') : 1;
        $data['entry_void_status'] = $this->language->get('entry_void_status');
        $data['void_status_id'] = $this->config->get('POS_void_status_id') ? $this->config->get('POS_void_status_id') : 16;
        $data['entry_quote_complete_status'] = $this->language->get('entry_quote_complete_status');
        $data['quote_complete_status_id'] = $this->config->get('POS_quote_complete_status_id') ? $this->config->get('POS_quote_complete_status_id') : 1;
        $data['gift_receipt_status_id'] = $this->config->get('POS_gift_receipt_status_id') ? $this->config->get('POS_gift_receipt_status_id') : 0;
        $data['gift_collected_status_id'] = $this->config->get('gift_collected_status_id') ? $this->config->get('gift_collected_status_id') : 0;
        // add for Complete Status end
        // add for Rounding begin
        $data['text_rounding_setting'] = $this->language->get('text_rounding_setting');
        $data['text_rounding_enable'] = $this->language->get('text_rounding_enable');
        $data['text_rounding_5c'] = $this->language->get('text_rounding_5c');
        $data['text_rounding_10c'] = $this->language->get('text_rounding_10c');
        $data['text_rounding_50c'] = $this->language->get('text_rounding_50c');
        $data['enable_rounding'] = 0;
        if (isset($this->request->post['enable_rounding'])) {
            $data['enable_rounding'] = $this->request->post['enable_rounding'];
        } else {
            $data['enable_rounding'] = $this->config->get('POS_enable_rounding');
        }
        $data['config_rounding'] = '';
        if (isset($this->request->post['config_rounding'])) {
            $data['config_rounding'] = $this->request->post['config_rounding'];
        } else {
            $data['config_rounding'] = $this->config->get('POS_config_rounding');
        }
        // add for Rounding end
        // add for till control begin
        $data['text_till_control_setting'] = $this->language->get('text_till_control_setting');
        $data['text_till_control_enable'] = $this->language->get('text_till_control_enable');
        $data['enable_till_control'] = $this->config->get('POS_enable_till_control');
        $data['entry_till_control_key'] = $this->language->get('entry_till_control_key');
        $data['button_test'] = $this->language->get('button_test');
        $data['text_till_full_payment_enable'] = $this->language->get('text_till_full_payment_enable');
        $data['till_control_key'] = $this->config->get('POS_till_control_key');

        $data['enable_till_full_payment'] = $this->config->get('POS_enable_till_full_payment');

        $data['text_product_sn_setting'] = $this->language->get('text_product_sn_setting');
        $data['text_non_predefined_sn_enable'] = $this->language->get('text_non_predefined_sn_enable');
        $data['enable_non_predefined_sn'] = $this->config->get('POS_enable_non_predefined_sn');

        // add for till control end
        // add for Status Change Notification begin
        $data['text_notification_setting'] = $this->language->get('text_notification_setting');
        $data['text_notification_enable'] = $this->language->get('text_notification_enable');
        $data['enable_notification'] = $this->config->get('POS_enable_notification');
        // add for Status Change Notification end
        $data['text_complete_order_when_paid_full'] = $this->language->get('text_complete_order_when_paid_full');
        $data['enable_complete_order_when_paid_full'] = $this->config->get('POS_enable_complete_order_when_paid_full') ? $this->config->get('POS_enable_complete_order_when_paid_full') : 0;

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_add_type'] = $this->language->get('button_add_type');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['text_pos_wait'] = $this->language->get('text_pos_wait');
        $data['text_retrieve_area'] = $this->language->get('text_retrieve_area');

        $data['text_pos_ui_setting'] = $this->language->get('text_pos_ui_setting');
        $data['text_hide_pos_shipping'] = $this->language->get('text_hide_pos_shipping');
        $data['hide_pos_shipping'] = $this->config->get('POS_hide_pos_shipping') ? $this->config->get('POS_hide_pos_shipping') : 0;

        // add for auto payment begin
        $data['text_auto_payment_setting'] = $this->language->get('text_auto_payment_setting');
        $data['text_auto_payment_enable'] = $this->language->get('text_auto_payment_enable');
        $data['enable_auto_payment'] = $this->config->get('POS_enable_auto_payment') ? $this->config->get('POS_enable_auto_payment') : 0;
        $data['auto_payment_type'] = $this->config->get('POS_auto_payment_type') ? $this->config->get('POS_auto_payment_type') : 'cash';
        // add for auto payment end
        // add for product low stock begin
        $data['text_product_low_stock_setting'] = $this->language->get('text_product_low_stock_setting');
        $data['text_product_low_stock_enable'] = $this->language->get('text_product_low_stock_enable');
        $data['enable_product_low_stock'] = $this->config->get('POS_enable_product_low_stock') ? $this->config->get('POS_enable_product_low_stock') : 0;
        $data['text_set_product_low_stock'] = $this->language->get('text_set_product_low_stock');
        $data['entry_set_product_low_stock'] = $this->language->get('entry_set_product_low_stock');
        $data['button_set_product_low_stock'] = $this->language->get('button_set_product_low_stock');
        $data['categories'] = $this->model_pos_pos->getSubCategories(0);
        // add for product low stock end

        $data['entry_lock_group'] = $this->language->get('entry_lock_group');
        $order_lock_groups = array();
        if ($this->config->get('POS_order_lock_groups')) {
            $order_lock_groups = $this->config->get('POS_order_lock_groups');
        }
        $data['order_lock_groups'] = $order_lock_groups;

        // add for customer loyalty card begin
        $data['text_customer_card_setting'] = $this->language->get('text_customer_card_setting');
        $data['entry_customer_card_prefix'] = $this->language->get('entry_customer_card_prefix');
        $data['customer_card_prefix'] = $this->config->get('POS_customer_card_prefix') ? $this->config->get('POS_customer_card_prefix') : '';
        $data['currency_symbol'] = $currency_symbol;
        $data['text_reward_points'] = $this->language->get('text_reward_points');
        $data['text_reward_point_setting'] = $this->language->get('text_reward_point_setting');
        $data['reward_points_value'] = $this->config->get('POS_reward_points_value') ? $this->config->get('POS_reward_points_value') : '';
        $data['text_use_opencart_reward_points'] = $this->language->get('text_use_opencart_reward_points');
        $data['text_use_cash_converting'] = $this->language->get('text_use_cash_converting');
        $data['reward_points_usage'] = $this->config->get('POS_reward_points_usage') ? $this->config->get('POS_reward_points_usage') : '1';
        $data['text_reward_point_initial'] = $this->language->get('text_reward_point_initial');
        $data['text_point_needed'] = $this->language->get('text_point_needed');
        $data['text_reward_point_initial_customer_group'] = $this->language->get('text_reward_point_initial_customer_group');
        $data['text_reward_point_initial_price'] = $this->language->get('text_reward_point_initial_price');
        $data['button_reward_point_initial_set'] = $this->language->get('button_reward_point_initial_set');
        $data['points_ratio'] = $this->config->get('POS_points_ratio') ? $this->config->get('POS_points_ratio') : '';
        $data['reward_points_ratio'] = $this->config->get('POS_reward_points_ratio') ? $this->config->get('POS_reward_points_ratio') : '';
        // add for customer loyalty card end
        // add for sales report begin
        $data['tab_settings_sales_report'] = $this->language->get('tab_settings_sales_report');
        $data['text_sales_report_setting'] = $this->language->get('text_sales_report_setting');
        $data['text_stock_report_setting'] = $this->language->get('text_stock_report_setting');
        $data['button_add_item'] = $this->language->get('button_add_item');
        $data['text_display_title'] = $this->language->get('text_display_title');
        $data['text_data_source'] = $this->language->get('text_data_source');
        $data['text_item_speical'] = $this->language->get('text_item_speical');
        $data['text_title_prompt'] = $this->language->get('text_title_prompt');
        $data['button_export_to_csv'] = $this->language->get('button_export_to_csv');
        $data['text_new_line'] = $this->language->get('text_new_line');
        $data['text_today'] = $this->language->get('text_today');
        $data['text_average_cost'] = $this->language->get('text_average_cost');
        $data['text_average_cost_alert'] = $this->language->get('text_average_cost_alert');
        $data['text_use_title_in_report'] = $this->language->get('text_use_title_in_report');
        $data['text_or'] = $this->language->get('text_or');
        $data['text_today_date'] = $this->language->get('text_today_date');
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_value'] = $this->language->get('entry_value');
        $data['entry_value_in_quote'] = $this->language->get('entry_value_in_quote');
        // Get all available items from selected tables
        $data['sales_report_use_title'] = $this->config->get('POS_sales_report_use_title') ? $this->config->get('POS_sales_report_use_title') : '0';
        $data['sales_report_available_items'] = $this->model_pos_pos->getSalesAvailableReportItems();
        ksort($data['sales_report_available_items']);
        $data['sales_report_available_items'][$this->language->get('text_fixed_value')] = array('type' => '_pos_custom_field', 'title' => '');
        $data['sales_report_items'] = $this->config->get('POS_sales_report_items');
        if (empty($data['sales_report_items'])) {
            // set default fields
            $data['sales_report_items'] = array('(order) invoice_no' => array('title' => 'Invoice No', 'order' => 0, 'type' => 'int(11)'),
                '(order) date_modified' => array('title' => 'Date Modified', 'order' => 1, 'type' => 'datetime'),
                '(order) total' => array('title' => 'Sub Total', 'order' => 2, 'type' => 'decimal(15,4)'),
                '(order_product) tax' => array('title' => 'Tax', 'order' => 3, 'type' => 'decimal(15,4)'),
                '(order_product) quantity' => array('title' => 'Quantity', 'order' => 4, 'type' => 'int(4)'),
                '(order_product) model' => array('title' => 'Model', 'order' => 5, 'type' => 'varchar(64)'),
                '(order_product) cost' => array('title' => 'Cost', 'order' => 6, 'type' => 'decimal(15,4)'));
        }
        foreach ($data['sales_report_items'] as $table_field => $report_item) {
            if (isset($data['sales_report_available_items'][$table_field]) && $table_field != $this->language->get('text_fixed_value')) {
                unset($data['sales_report_available_items'][$table_field]);
            }
        }

        $data['stock_report_use_title'] = $this->config->get('POS_stock_report_use_title') ? $this->config->get('POS_stock_report_use_title') : '0';
        $data['stock_report_available_items'] = $this->model_pos_pos->getStockAvailableReportItems();
        ksort($data['stock_report_available_items']);
        $data['stock_report_available_items'][$this->language->get('text_fixed_value')] = array('type' => '_pos_custom_field', 'title' => '');
        ;
        $data['stock_report_items'] = $this->config->get('POS_stock_report_items');
        if (empty($data['stock_report_items'])) {
            // set default fields
            $data['stock_report_items'] = array('(product_description) name' => array('title' => 'Product Name', 'order' => 0, 'type' => 'varchar(255)'),
                '(manufacturer) name' => array('title' => 'Brand Name', 'order' => 1, 'type' => 'varchar(64)'),
                '(product) supplier' => array('title' => 'Supplier', 'order' => 2, 'type' => 'varchar(64)'),
                '(product) status' => array('title' => 'Is Active', 'order' => 3, 'type' => 'tinyint(1)'),
                '(product) model' => array('title' => 'Stock Code', 'order' => 4, 'type' => 'decimal(15,4)'),
                '(product) quantity' => array('title' => 'Stock On Hand', 'order' => 5, 'type' => 'int(4)'),
                '(product) price' => array('title' => 'Price', 'order' => 6, 'type' => 'decimal(15,4)'));
        }
        foreach ($data['stock_report_items'] as $table_field => $report_item) {
            if (isset($data['stock_report_available_items'][$table_field]) && $table_field != $this->language->get('text_fixed_value')) {
                unset($data['stock_report_available_items'][$table_field]);
            }
        }
        // add for sales report end
        // add for online order print begin
        $data['text_online_order_print_setting'] = $this->language->get('text_online_order_print_setting');
        $data['text_print_online_order_enable'] = $this->language->get('text_print_online_order_enable');
        $data['enable_online_order_print'] = $this->config->get('POS_enable_online_order_print') ? $this->config->get('POS_enable_online_order_print') : '0';
        // add for online order print end

        $data['token'] = $this->session->data['token'];

        $this->load->model('user/user');
        $data['users'] = $this->model_user_user->getUsers();
        $this->load->model('user/user_group');
        $data['user_groups'] = $this->model_user_user_group->getUserGroups();

        $excluded_groups = array();
        if ($this->config->get('POS_excluded_groups')) {
            $excluded_groups = $this->config->get('POS_excluded_groups');
        }
        $data['excluded_groups'] = $excluded_groups;

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('pos_heading_title'),
            'href' => $this->url->link('module/pos', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['action'] = $this->url->link('module/pos', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('module/pos/main', 'token=' . $this->session->data['token'], 'SSL');
        // add for Openbay integration begin
        $data['enable_openbay'] = $this->config->get('POS_enable_openbay');
        // add for Openbay integration end

        $data['display_once_login'] = $this->config->get('POS_display_once_login');

        $data['text_payment_post_action_setting'] = $this->language->get('text_payment_post_action_setting');
        $data['entry_payment_post_action'] = $this->language->get('entry_payment_post_action');
        $complete_status_id = $this->config->get('POS_complete_status_id') ? $this->config->get('POS_complete_status_id') : 5;
        $order_payment_post_status = array($complete_status_id => $complete_status_id);
        if ($this->config->get('POS_order_payment_post_status')) {
            $order_payment_post_status = $this->config->get('POS_order_payment_post_status');
        }
        $data['order_payment_post_status'] = $order_payment_post_status;

        // add for Print being
        $data['p_logo'] = $this->config->get('POS_p_logo') ? $this->config->get('POS_p_logo') : 'view/image/pos/no_image.jpg';
        $data['p_width'] = $this->config->get('POS_p_width') ? $this->config->get('POS_p_width') : '200';
        $data['p_complete'] = $this->config->get('POS_p_complete');
        $data['p_payment'] = $this->config->get('POS_p_payment');
        $data['p_term_n_cond'] = $this->config->get('POS_p_term_n_cond');
        if (!$data['p_term_n_cond']) {
            $data['p_term_n_cond'] = $this->language->get('term_n_cond_default');
        }
        // add for Print end
        // add for Hiding Delete begin
        $this->load->model('user/user_group');
        $data['user_groups'] = $this->model_user_user_group->getUserGroups();
        $data['enable_hide_delete'] = $this->config->get('POS_enable_hide_delete');
        $delete_excluded_groups = array();
        if ($this->config->get('POS_delete_excluded_groups')) {
            $delete_excluded_groups = $this->config->get('POS_delete_excluded_groups');
        }
        $data['delete_excluded_groups'] = $delete_excluded_groups;
        // add for Hiding Delete end
        // add for Hiding Order Status begin
        $order_hiding_status = array();
        if ($this->config->get('POS_order_hiding_status')) {
            $order_hiding_status = $this->config->get('POS_order_hiding_status');
        }
        $data['order_hiding_status'] = $order_hiding_status;
        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        // add for Hiding Order Status end
        $this->load->model('localisation/return_status');
        $data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();
        // add for Empty order control begin
        $data['initial_status_id'] = $this->config->get('POS_initial_status_id') ? $this->config->get('POS_initial_status_id') : '0';
        // add for Empty order contrl end
        // add for User as Affiliate begin
        // get affiliate list
        $this->load->model('marketing/affiliate');
        $affiliates = $this->model_marketing_affiliate->getAffiliates();
        $data['user_affis'] = $this->model_pos_pos->getUAs();
        $data['ua_users'] = array();
        $data['ua_affiliates'] = array();
        foreach ($data['users'] as $user) {
            $inlist = false;
            foreach ($data['user_affis'] as $user_affi) {
                if ($user['user_id'] == $user_affi['user_id']) {
                    $inlist = true;
                    break;
                }
            }
            if (!$inlist) {
                // not associated yet
                array_push($data['ua_users'], $user);
            }
        }
        foreach ($affiliates as $affiliate) {
            $inlist = false;
            foreach ($data['user_affis'] as $user_affi) {
                if ($affiliate['affiliate_id'] == $user_affi['affiliate_id']) {
                    $inlist = true;
                    break;
                }
            }
            if (!$inlist) {
                // not associated yet
                array_push($data['ua_affiliates'], $affiliate);
            }
        }
        // add for User as Affiliate end
        // add for Default Customer begin
        $this->load->model('localisation/country');
        $data['c_countries'] = $this->model_localisation_country->getCountries();
        $this->setDefaultCustomer($data);

        $data['c_groups'] = $this->model_pos_pos->getCustomerGroups();
        // add for Default Customer end
        // add for Maximum Discount begin
        foreach ($data['user_groups'] as $key => $user_group) {
            $max_discount_fixed = 0;
            if ($this->config->get('POS_' . $user_group['user_group_id'] . '_max_discount_fixed')) {
                $max_discount_fixed = $this->config->get('POS_' . $user_group['user_group_id'] . '_max_discount_fixed');
            }
            $data['user_groups'][$key]['max_discount_fixed'] = $max_discount_fixed;
            $max_discount_percentage = 0;
            if ($this->config->get('POS_' . $user_group['user_group_id'] . '_max_discount_percentage')) {
                $max_discount_percentage = $this->config->get('POS_' . $user_group['user_group_id'] . '_max_discount_percentage');
            }
            $data['user_groups'][$key]['max_discount_percentage'] = $max_discount_percentage;
        }
        // add for Maximum Discount end
        // add for Quotation begin
        $data['quote_statuses'] = $this->model_pos_pos->getQuoteStatuses();
        // add for Quotation end
        // add for Cash type begin
        $data['cash_types'] = $this->config->get('POS_cash_types');
        if (!empty($data['cash_types'])) {
            foreach ($data['cash_types'] as $key => $value) {
                if (empty($value['display'])) {
                    $data['cash_types'][$key]['display'] = $this->currency->formatFront($value['value']);
                }
            }
        }
        // add for Cash type end
        // add for label print begin
        $data['print_wait_message'] = $this->language->get('print_wait_message');
        $data['tab_settings_label'] = $this->language->get('tab_settings_label');
        $data['text_label_layout_setting'] = $this->language->get('text_label_layout_setting');
        $data['entry_label_top_margin'] = $this->language->get('entry_label_top_margin');
        $data['entry_label_height'] = $this->language->get('entry_label_height');
        $data['entry_label_side_margin'] = $this->language->get('entry_label_side_margin');
        $data['entry_label_width'] = $this->language->get('entry_label_width');
        $data['entry_label_vertical_pitch'] = $this->language->get('entry_label_vertical_pitch');
        $data['entry_label_number_across'] = $this->language->get('entry_label_number_across');
        $data['entry_label_horizontal_pitch'] = $this->language->get('entry_label_horizontal_pitch');
        $data['entry_label_number_down'] = $this->language->get('entry_label_number_down');
        $data['text_label_template_setting'] = $this->language->get('text_label_template_setting');
        $data['entry_label_barcode_for'] = $this->language->get('entry_label_barcode_for');
        $data['entry_label_barcode_type'] = $this->language->get('entry_label_barcode_type');
        $data['text_label_test_print'] = $this->language->get('text_label_test_print');
        $data['text_label_adjust_setting'] = $this->language->get('text_label_adjust_setting');
        $data['entry_label_adjust_width'] = $this->language->get('entry_label_adjust_width');
        $data['entry_label_adjust_height'] = $this->language->get('entry_label_adjust_height');
        $data['entry_label_template_name'] = $this->language->get('entry_label_template_name');
        $data['text_label_template_layout'] = $this->language->get('text_label_template_layout');
        $data['text_label_template_content'] = $this->language->get('text_label_template_content');
        $data['text_label_print'] = $this->language->get('text_label_print');
        $data['entry_label_product'] = $this->language->get('entry_label_product');
        $data['entry_label_quantity'] = $this->language->get('entry_label_quantity');
        $data['button_label_add'] = $this->language->get('button_label_add');
        $data['text_label_add_product_before_print'] = $this->language->get('text_label_add_product_before_print');
        $data['text_label_product_name_not_valid'] = $this->language->get('text_label_product_name_not_valid');
        $data['text_label_quantity_not_valid'] = $this->language->get('text_label_quantity_not_valid');
        $data['text_label_include_price'] = $this->language->get('text_label_include_price');
        $data['text_label_template_editor_title'] = $this->language->get('text_label_template_editor_title');
        $data['text_label_text'] = $this->language->get('text_label_text');
        $data['text_label_image'] = $this->language->get('text_label_image');
        $data['text_label_product_name'] = $this->language->get('text_label_product_name');
        $data['text_label_product_description'] = $this->language->get('text_label_product_description');
        $data['text_label_product_model'] = $this->language->get('text_label_product_model');
        $data['text_label_product_price'] = $this->language->get('text_label_product_price');
        $data['text_label_product_sku'] = $this->language->get('text_label_product_sku');
        $data['text_label_product_upc'] = $this->language->get('text_label_product_upc');
        $data['text_label_product_ean'] = $this->language->get('text_label_product_ean');
        $data['text_label_product_mpn'] = $this->language->get('text_label_product_mpn');
        $data['text_label_product_manufacturer'] = $this->language->get('text_label_product_manufacturer');
        $data['text_label_product_image'] = $this->language->get('text_label_product_image');
        $data['label_demos'] = array('product_name' => $this->language->get('text_label_product_name_demo'),
            'product_description' => $this->language->get('text_label_product_description_demo'),
            'product_model' => $this->language->get('text_label_product_model_demo'),
            'product_price' => $this->currency->formatFront($this->language->get('text_label_product_price_demo')),
            'product_sku' => $this->language->get('text_label_product_sku_demo'),
            'product_upc' => $this->language->get('text_label_product_upc_demo'),
            'product_ean' => $this->language->get('text_label_product_ean_demo'),
            'product_mpn' => $this->language->get('text_label_product_mpn_demo'),
            'product_manufacturer' => $this->language->get('text_label_product_manufacturer_demo'),
            'product_image' => 'view/image/pos/no_image.png');
        $data['entry_label_text'] = $this->language->get('entry_label_text');
        $data['text_new_template'] = $this->language->get('text_new_template');
        $data['text_barcode_selection'] = $this->language->get('text_barcode_selection');
        $data['text_use_barcode'] = $this->language->get('text_use_barcode');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['text_label_template_all_required'] = $this->language->get('text_label_template_all_required');
        $data['text_label_template_empty_content'] = $this->language->get('text_label_template_empty_content');
        $data['text_label_template_delete_confirm'] = $this->language->get('text_label_template_delete_confirm');
        $data['label_barcode_types'] = array('ean8', 'ean13', 'code11', 'code39', 'code128', 'codabar', 'std25', 'int25', 'code93');

        // get label templates data
        $label_templates = $this->model_pos_pos->get_label_templates();
        /* if (empty($label_templates)) {
          $label_templates = array(array('label_template_id'=>0, 'name'=>'Default', 'top_margin' => 3, 'side_margin' => 1, 'height' => 20, 'width' => 30, 'vertical_pitch' => 22, 'horizontal_pitch' => 32, 'number_across' => 3, 'number_down' => 5, 'content' => ''));
          } */
        $data['label_templates'] = $label_templates;
        $data['label_default_template'] = array('label_template_id' => 0, 'name' => 'Default', 'top_margin' => 3, 'side_margin' => 1, 'height' => 20, 'width' => 30, 'vertical_pitch' => 22, 'horizontal_pitch' => 32, 'number_across' => 3, 'number_down' => 5, 'content' => '');
        $data['label_adjust_width'] = $this->config->get('POS_label_adjust_width') ? $this->config->get('POS_label_adjust_width') : '1.0';
        $data['label_adjust_height'] = $this->config->get('POS_label_adjust_height') ? $this->config->get('POS_label_adjust_height') : '1.0';
        // add for label print end

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['is_label_user'] = (empty($this->session->data['is_label_user'])) ? false : true;
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $this->response->setOutput($this->load->view('pos/settings.tpl', $data));
    }

    // add for Default Customer begin
    private function setDefaultCustomer(&$data) {
        // add for Default Customer begin
        $default_country_id = $this->config->get('config_country_id');
        $default_zone_id = $this->config->get('config_zone_id');
        $data['c_id'] = $this->config->get('POS_c_id') ? $this->config->get('POS_c_id') : 0;
        $data['c_group_id'] = $this->config->get('POS_c_group_id') ? $this->config->get('POS_c_group_id') : 1;
        $use_default_general = true;
        $use_default_address = true;
        if ($this->config->get('POS_c_type') == 2 || $this->config->get('POS_c_type') == 3) {
            $data['c_type'] = $this->config->get('POS_c_type');
            if ($this->config->get('POS_c_type') == 2) {
                // use the configuration from settings table
                $data['a_country_id'] = $this->config->get('POS_a_country_id') ? $this->config->get('POS_a_country_id') : $default_country_id;
                $data['a_zone_id'] = $this->config->get('POS_a_zone_id') ? $this->config->get('POS_a_zone_id') : $default_zone_id;
                $data['c_firstname'] = $this->config->get('POS_c_firstname') ? $this->config->get('POS_c_firstname') : 'Instore';
                $data['c_lastname'] = $this->config->get('POS_c_lastname') ? $this->config->get('POS_c_lastname') : 'Dummy';
                $data['c_name'] = $data['c_firstname'] . ' ' . $data['c_lastname'];
                $data['c_email'] = $this->config->get('POS_c_email') ? $this->config->get('POS_c_email') : 'customer@instore.com';
                $data['c_telephone'] = $this->config->get('POS_c_telephone') ? $this->config->get('POS_c_telephone') : '1600';
                $data['c_fax'] = $this->config->get('POS_c_fax') ? $this->config->get('POS_c_fax') : '';
                $data['a_firstname'] = $this->config->get('POS_a_firstname') ? $this->config->get('POS_a_firstname') : 'Instore';
                $data['a_lastname'] = $this->config->get('POS_a_lastname') ? $this->config->get('POS_a_lastname') : 'Dummy';
                $data['a_address_1'] = $this->config->get('POS_a_address_1') ? $this->config->get('POS_a_address_1') : 'customer address';
                $data['a_address_2'] = $this->config->get('POS_a_address_2') ? $this->config->get('POS_a_address_2') : '';
                $data['a_city'] = $this->config->get('POS_a_city') ? $this->config->get('POS_a_city') : 'customer city';
                $data['a_postcode'] = $this->config->get('POS_a_postcode') ? $this->config->get('POS_a_postcode') : '1600';
                $use_default_general = false;
                $use_default_address = false;
            } else {
                // get the first address from customer address
                $this->load->model('pos/pos');
                $c_info = $this->model_pos_pos->getCustomer($data['c_id']);
                if ($c_info) {
                    $use_default_general = false;
                    $data['c_group_id'] = $c_info['customer_group_id'];
                    $data['c_firstname'] = $c_info['firstname'];
                    $data['c_lastname'] = $c_info['lastname'];
                    $data['c_name'] = $data['c_firstname'] . ' ' . $data['c_lastname'];
                    $data['c_email'] = $c_info['email'];
                    $data['c_telephone'] = $c_info['telephone'];
                    $data['c_fax'] = $c_info['fax'];
                    $data['c_address_id'] = $c_info['address_id'];
                }
                $c_addresses = $this->model_pos_pos->getAddresses($data['c_id']);
                $data['c_addresses'] = $c_addresses;
                ksort($c_addresses);
                if (count($c_addresses) > 0) {
                    $use_default_address = false;
                    foreach ($c_addresses as $c_address) {
                        $data['a_country_id'] = $c_address['country_id'];
                        $data['a_zone_id'] = $c_address['zone_id'];
                        $data['a_firstname'] = $c_address['firstname'];
                        $data['a_lastname'] = $c_address['lastname'];
                        $data['a_address_1'] = $c_address['address_1'];
                        $data['a_address_2'] = $c_address['address_2'];
                        $data['a_city'] = $c_address['city'];
                        $data['a_postcode'] = $c_address['postcode'];
                        break;
                    }
                }
            }
        } else {
            $data['c_type'] = 1;
        }

        $data['buildin'] = array();
        $data['buildin']['c_firstname'] = 'Instore';
        $data['buildin']['c_lastname'] = "Dummy";
        $data['buildin']['c_name'] = $data['buildin']['c_firstname'] . ' ' . $data['buildin']['c_lastname'];
        $data['buildin']['c_email'] = 'customer@instore.com';
        $data['buildin']['c_telephone'] = '1600';
        $data['buildin']['c_fax'] = '';
        $data['buildin']['a_country_id'] = $default_country_id;
        $data['buildin']['a_zone_id'] = $default_zone_id;
        $data['buildin']['a_firstname'] = 'Instore';
        $data['buildin']['a_lastname'] = "Dummy";
        $data['buildin']['a_address_1'] = 'customer address';
        $data['buildin']['a_address_2'] = '';
        $data['buildin']['a_city'] = 'customer city';
        $data['buildin']['a_postcode'] = '1600';

        if ($use_default_general) {
            $data['c_firstname'] = 'Instore';
            $data['c_lastname'] = "Dummy";
            $data['c_name'] = $data['c_firstname'] . ' ' . $data['c_lastname'];
            $data['c_email'] = 'customer@instore.com';
            $data['c_telephone'] = '1600';
            $data['c_fax'] = '';
        }
        if ($use_default_address) {
            $data['a_country_id'] = $default_country_id;
            $data['a_zone_id'] = $default_zone_id;
            $data['a_firstname'] = 'Instore';
            $data['a_lastname'] = "Dummy";
            $data['a_address_1'] = 'customer address';
            $data['a_address_2'] = '';
            $data['a_city'] = 'customer city';
            $data['a_postcode'] = '1600';
        }
    }

    // add for Default Customer end

    protected function validate() {
        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function install() {
        // install modification
        $this->installModifiation();

        $this->load->model('pos/pos');
        $this->language->load('module/pos');

        // create tables
        $this->model_pos_pos->createModuleTables();

        // copy language file is English not set to default
        $this->copyLangFile();

        // add cash types
        $this->load->model('setting/setting');
        $pos_settings = $this->model_setting_setting->getSetting('POS');
        if (!isset($pos_settings['POS_cash_types'])) {
            // no previous cash types defined, set Australia cash as default
            $text_note = $this->language->get('text_cash_type_note');
            $text_coin = $this->language->get('text_cash_type_coin');
            $cash_types = array(array('type' => $text_note, 'image' => 'view/image/pos/cash/cash_100.jpg', 'display' => '$100', 'value' => 100),
                array('type' => $text_note, 'image' => 'view/image/pos/cash/cash_50.jpg', 'display' => '$50', 'value' => 50),
                array('type' => $text_note, 'image' => 'view/image/pos/cash/cash_20.jpg', 'display' => '$20', 'value' => 20),
                array('type' => $text_note, 'image' => 'view/image/pos/cash/cash_10.jpg', 'display' => '$10', 'value' => 10),
                array('type' => $text_note, 'image' => 'view/image/pos/cash/cash_5.jpg', 'display' => '$5', 'value' => 5),
                array('type' => $text_coin, 'image' => 'view/image/pos/cash/cash_2.jpg', 'display' => '$2', 'value' => 2),
                array('type' => $text_coin, 'image' => 'view/image/pos/cash/cash_1.jpg', 'display' => '$1', 'value' => 1),
                array('type' => $text_coin, 'image' => 'view/image/pos/cash/cash_50c.jpg', 'display' => '50C', 'value' => 0.5),
                array('type' => $text_coin, 'image' => 'view/image/pos/cash/cash_20c.jpg', 'display' => '20C', 'value' => 0.2),
                array('type' => $text_coin, 'image' => 'view/image/pos/cash/cash_10c.jpg', 'display' => '10C', 'value' => 0.1),
                array('type' => $text_coin, 'image' => 'view/image/pos/cash/cash_5c.jpg', 'display' => '5C', 'value' => 0.05));
            $pos_settings['POS_cash_types'] = $cash_types;
        }
        if (!isset($pos_settings['POS_POS_payment_types'])) {
            // no previous cash types
            $pos_payments = array('cash' => 'Cash', 'credit_card' => 'Credit Card', 'purchase_order' => $this->language->get('purchase_order'), 'gift_voucher' => $this->language->get('text_gift_voucher'), 'reward_points' => $this->language->get('text_reward_points'));
            $pos_settings['POS_POS_payment_types'] = $pos_payments;
            $pos_settings['POS_payment_type_enables'] = array('cash' => 1);
        }

        // add permission for report
        $this->load->model('user/user_group');
        $this->model_user_user_group->addPermission($this->user->getId(), 'access', 'report/order_payment');
        $this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'report/order_payment');
        // add for commission begin
        $this->model_user_user_group->addPermission($this->user->getId(), 'access', 'report/pos_commission');
        $this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'report/pos_commission');
        // add for commission end
        $this->model_user_user_group->addPermission($this->user->getId(), 'access', 'pos/extended_product');
        $this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'pos/extended_product');
        $this->model_user_user_group->addPermission($this->user->getId(), 'access', 'pos/clean_tables');
        $this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'pos/clean_tables');
        $this->model_user_user_group->addPermission($this->user->getId(), 'access', 'pos/sn_manager');
        $this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'pos/sn_manager');
        // install in store shipping and payment method
        $this->load->model('extension/extension');
        if ($this->user->hasPermission('modify', 'extension/shipping')) {
            $this->model_extension_extension->install('shipping', 'instore');
            $this->model_user_user_group->addPermission($this->user->getId(), 'access', 'shipping/instore');
            $this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'shipping/instore');
            $this->model_setting_setting->editSetting('instore', array('instore_geo_zone_id' => '0', 'instore_status' => '1', 'instore_sort_order' => '1'));
        }
        if ($this->user->hasPermission('modify', 'extension/payment')) {
            $this->model_extension_extension->install('payment', 'in_store');
            $this->model_user_user_group->addPermission($this->user->getId(), 'access', 'payment/in_store');
            $this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'payment/in_store');
            $this->model_setting_setting->editSetting('in_store', array('in_store_geo_zone_id' => '0', 'in_store_status' => '1', 'in_store_sort_order' => '1'));
        }
        // add for Discount begin
        if ($this->user->hasPermission('modify', 'extension/total')) {
            $this->model_extension_extension->install('total', 'pos_discount');
            $this->model_user_user_group->addPermission($this->user->getId(), 'access', 'total/pos_discount');
            $this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'total/pos_discount');
            $default_sort_order = '1';
            if ($this->config->get('sub_total_sort_order')) {
                $default_sort_order = (int) $this->config->get('sub_total_sort_order') + 1;
            }
            $this->model_setting_setting->editSetting('pos_discount', array('pos_discount_status' => '1', 'pos_discount_sort_order' => $default_sort_order));
        }
        // add for Discount end
        // add for Rounding begin
        if ($this->user->hasPermission('modify', 'extension/total')) {
            $this->model_extension_extension->install('total', 'pos_rounding');
            $this->model_user_user_group->addPermission($this->user->getId(), 'access', 'total/pos_rounding');
            $this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'total/pos_rounding');
            $default_sort_order = '1';
            if ($this->config->get('total_sort_order')) {
                $default_sort_order = (int) $this->config->get('total_sort_order') - 1;
            }
            $this->model_setting_setting->editSetting('pos_rounding', array('pos_rounding_status' => '1', 'pos_rounding_sort_order' => $default_sort_order));
        }
        // add for Rounding end
        // add for Empty order control begin
        if (!isset($pos_settings['POS_initial_status_id'])) {
            $initial_status_id = $this->model_pos_pos->addAdditionalOrderStatus($this->language->get('text_status_initial'), $this->language->get('text_status_deleted'));
            $this->cache->delete('order_status');
            $pos_settings['POS_initial_status_id'] = $initial_status_id;
        }
        // add for Empty order control end
        // add for gift receipt begin
        if (!isset($pos_settings['POS_gift_receipt_status_id']) || !isset($pos_settings['POS_gift_collected_status_id'])) {
            $gift_status_ids = $this->model_pos_pos->addGiftReceiptOrderStatus($this->language->get('text_status_gift_receipt'), $this->language->get('text_status_gift_collected'));
            $this->cache->delete('order_status');
            $pos_settings['POS_gift_receipt_status_id'] = $gift_status_ids['gift_receipt_status_id'];
            $pos_settings['POS_gift_collected_status_id'] = $gift_status_ids['gift_collected_status_id'];
        }
        // add for gift receipt end
        // add for receipt begin
        // set print receipt once complete the order
        $pos_settings['POS_p_complete'] = 1;
        // add for receipt end

        $this->model_setting_setting->editSetting('POS', $pos_settings);

        // create new point of sale groups
        $ignore = array(
            'common/home',
            'common/startup',
            'common/login',
            'common/logout',
            'common/forgotten',
            'common/reset',
            'error/not_found',
            'error/permission',
            'common/footer',
            'common/header'
        );

        $data['permission'] = array();
        $data['permission']['access'] = array();
        $data['permission']['modify'] = array();

        $files = glob(DIR_APPLICATION . 'controller/*/*.php');

        foreach ($files as $file) {
            $file_data = explode('/', dirname($file));

            $permission = end($file_data) . '/' . basename($file, '.php');

            if (!in_array($permission, $ignore)) {
                $data['permission']['access'][] = $permission;
                $data['permission']['modify'][] = $permission;
            }
        }

        // create groups (POS and label)
        $this->load->model('user/user_group');
        $groups = $this->model_user_user_group->getUserGroups();

        $pos_groups = array('POS', 'label');
        foreach ($pos_groups as $pos_group) {
            $pos_group_defined = false;
            if (!empty($groups)) {
                foreach ($groups as $group) {
                    if ($group['name'] == $pos_group) {
                        $data['name'] = $pos_group;
                        $this->model_user_user_group->editUserGroup($group['user_group_id'], $data);
                        $pos_group_defined = true;
                        break;
                    }
                }
            }
            if (!$pos_group_defined) {
                $data['name'] = $pos_group;
                $this->model_user_user_group->addUserGroup($data);
            }
        }
    }

    private function installModifiation() {
        $installed = false;

        // install the modification
        $file = DIR_APPLICATION . 'model/pos/pos.ocmod.xml';

        if (file_exists($file)) {
            $this->load->model('extension/modification');
            $xml = file_get_contents($file);
            if ($xml) {
                try {
                    $dom = new DOMDocument('1.0', 'UTF-8');
                    $dom->loadXml($xml);

                    $attrs = array('name', 'author', 'version', 'link', 'code');
                    $modification_data = array('status' => 1);

                    foreach ($attrs as $attr) {
                        $value = $dom->getElementsByTagName($attr)->item(0);
                        $value = $value ? $value->nodeValue : '';
                        $modification_data[$attr] = $value;
                    }

                    $this->db->query("DELETE FROM `" . DB_PREFIX . "modification` WHERE name = '" . $this->db->escape($modification_data['name']) . "'");

                    $modification_query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "modification` LIKE 'xml'");
                    if ($modification_query->num_rows == 0) {
                        // old version of 2.0, does not have xml in the table
                        $modification_data['code'] = $xml;
                    } else {
                        $modification_data['xml'] = $xml;
                    }
                    $this->model_extension_modification->addModification($modification_data);
                    $installed = true;
                } catch (Exception $exception) {

                }
            }
        }

        return $installed;
    }

    private function uninstallModification() {
        // install the modification
        $file = DIR_APPLICATION . 'model/pos/pos.ocmod.xml';

        if (file_exists($file)) {
            $xml = file_get_contents($file);
            if ($xml) {
                try {
                    $dom = new DOMDocument('1.0', 'UTF-8');
                    $dom->loadXml($xml);

                    $name = $dom->getElementsByTagName('name')->item(0);
                    if ($name) {
                        $name = $name->nodeValue;
                        $this->db->query("DELETE FROM `" . DB_PREFIX . "modification` WHERE name = '" . $this->db->escape($name) . "'");
                    }
                } catch (Exception $exception) {

                }
            }
        }
    }

    public function uninstall() {
        // $this->load->model('pos/pos');
        // $this->model_pos_pos->deleteModuleTables();
        // remove the files
        // $this->deleteFile();
        // $this->load->model('setting/setting');
        // $this->model_setting_setting->deleteSetting('POS');
        $this->load->model('extension/extension');
        $this->load->model('setting/setting');
        if ($this->user->hasPermission('modify', 'extension/shipping')) {
            $this->model_extension_extension->uninstall('shipping', 'instore');
            $this->model_setting_setting->deleteSetting('instore');
        }
        if ($this->user->hasPermission('modify', 'extension/payment')) {
            $this->model_extension_extension->uninstall('payment', 'in_store');
            $this->model_setting_setting->deleteSetting('in_store');
        }
        // add for Discount begin
        if ($this->user->hasPermission('modify', 'extension/total')) {
            $this->model_extension_extension->uninstall('total', 'pos_discount');
        }
        // add for Discount end
        // add for Rounding begin
        if ($this->user->hasPermission('modify', 'extension/total')) {
            $this->model_extension_extension->uninstall('total', 'pos_rounding');
            $this->model_setting_setting->deleteSetting('pos_rounding');
        }
        // add for Rounding end
        // uninstall the modification, this line is commented out because opencart was not processing the uninstallation properly and there are errors when refresh
        // $this->uninstallModification();
    }

    private function copyLangFile() {
        $supported_languages = array();
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language`");
        foreach ($query->rows as $result) {
            $supported_languages[$result['code']] = $result;
        }
        $directory = $supported_languages[$this->config->get('config_admin_language')]['directory'];
        if ($directory != 'english') {
            copy(DIR_LANGUAGE . 'english/module/pos.php', DIR_LANGUAGE . $directory . '/module/pos.php');
        }
    }

    public function addOrderPayment() {
        $this->load->model('pos/pos');
        $this->language->load('module/pos');
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $order_payment_id = $this->model_pos_pos->addOrderPayment($this->request->post);
            $json = array();
            $json['success'] = $this->language->get('text_cash_success');
            $json['order_payment_id'] = $order_payment_id;
            if ($this->request->post['order_id']) {
                $order_id = $this->request->post['order_id'];
                $json['order_paid'] = $this->currency->formatFront($this->model_pos_pos->getOrderPaidAmount($order_id));
                $json['order_change_amount'] = $this->currency->formatFront($this->model_pos_pos->getOrderChangeAmount($order_id));
                $json['due_amount'] = $this->currency->formatFront($this->model_pos_pos->getOrderDueAmount($order_id));
                $json['order_total'] = $this->currency->formatFront($this->model_pos_pos->getOrderTotalAmount($order_id));
                $json['payment_status'] = $this->model_pos_pos->getOrderPaymentStatus($order_id);
            }
            // add for Gift Voucher Payment begin
            // check the voucher payment balance again to make sure the balance is enough
            if (!empty($this->request->post['payment_code']) && $this->request->post['payment_code'] == 'gift_voucher') {
                $voucher_code = $this->request->post['payment_note'];
                $voucher_id = $this->validate_gift_voucher($voucher_code, $json, $this->request->post['tendered_amount'], true);
                if (empty($json['error'])) {
                    // deduct the amount from the voucher
                    // update: use opencart similar logic, not deduct the amount from voucher table
                    // $this->db->query("UPDATE " . DB_PREFIX . "voucher SET amount=amount-" . (float)$this->request->post['tendered_amount'] . " WHERE code = '" . $this->db->escape($voucher_code) . "'");
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "voucher_history` SET voucher_id = '" . (int) $voucher_id . "', order_id = '" . (int) $this->request->post['order_id'] . "', amount = '" . (0 - (float) $this->request->post['tendered_amount']) . "', date_added = NOW()");
                }
            }
            // add for Gift Voucher Payment end
            // add for customer loyalty card begin
            if (!empty($this->request->post['payment_code']) && $this->request->post['payment_code'] == 'reward_points') {
                $this->process_reward_points_payment($this->request->post['order_id'], $this->request->post['payment_note'], $json);
            }
            // add for customer loyalty card end
            // add for Print begin
            $json['p_payment'] = $this->config->get('POS_p_payment') ? $this->config->get('POS_p_payment') : 0;
            // add for Print end
            $this->response->setOutput(json_encode($json));
        }
    }

    public function deleteOrderPayment() {
        $json = array();
        $this->load->model('pos/pos');
        if ($this->request->server['REQUEST_METHOD'] == 'GET') {
            $payment = $this->model_pos_pos->getOrderPayment($this->request->get['order_payment_id']);
            if ($payment) {
                $this->language->load('module/pos');
                if ($payment['payment_type'] == $this->language->get('text_gift_voucher')) {
                    // return the amount back to the voucher
                    // update: use opencart similar logic, do not deduct the amount from voucher table
                    // $this->db->query("UPDATE " . DB_PREFIX . "voucher SET amount=amount+" . $payment['tendered_amount'] . " WHERE code = '" . $payment['payment_note'] . "'");
                    $voucher_query = $this->db->query("SELECT voucher_id FROM " . DB_PREFIX . "voucher WHERE code = '" . $payment['payment_note'] . "'");
                    if ($voucher_query->row) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "voucher_history` SET voucher_id = '" . (int) $voucher_query->row['voucher_id'] . "', order_id = '" . (int) $payment['order_id'] . "', amount = '" . (float) $payment['tendered_amount'] . "', date_added = NOW()");
                    }
                }
                // add for customer loyalty card begin
                if ($payment['payment_type'] == $this->language->get('text_reward_points')) {
                    // return the reward points to the customer
                    if (strpos($payment['payment_note'], ',') !== false) {
                        // when the payment was made, it was using opencart settings
                        $parts = explode('|', $payment['payment_note']);
                        if ($parts) {
                            // deduct the reward points from the current order if the reward points are used to pay the order product as we already set the reward points in the earlier stage
                            $total_return = 0;
                            foreach ($parts as $part) {
                                if (!empty($part)) {
                                    $reward_details = explode(',', $part);
                                    if ($reward_details) {
                                        $order_product_id = $reward_details[0];
                                        $quantity = $reward_details[1];
                                        $return_points = $reward_details[2];
                                        $this->db->query("UPDATE `" . DB_PREFIX . "order_product` SET reward = reward + " . $return_points . " WHERE order_product_id = '" . (int) $order_product_id . "'");
                                        $total_return += $return_points;
                                    }
                                }
                            }

                            if ($total_return != 0) {
                                $this->db->query("UPDATE `" . DB_PREFIX . "customer_reward` SET points = points + " . $total_return . " WHERE order_id = '" . (int) $payment['order_id'] . "'");
                            }
                        }
                    } else {
                        // it's the reward points to cash conversion
                        $this->db->query("UPDATE `" . DB_PREFIX . "customer_reward` SET points = points + " . (int) $payment['payment_note'] . " WHERE order_id = '" . (int) $payment['order_id'] . "'");
                    }
                }
                // add for customer loyalty card end
                $this->model_pos_pos->deleteOrderPayment($this->request->get);
                $json['payment_status'] = $this->model_pos_pos->getOrderPaymentStatus($payment['order_id']);
            }
            $this->response->setOutput(json_encode($json));
        }
    }

    public function modifyOrderComment() {
        $this->load->model('pos/pos');
        if ($this->request->server['REQUEST_METHOD'] == 'GET') {
            $this->model_pos_pos->modifyOrderComment($this->request->get);
            $this->response->setOutput(json_encode(array()));
        }
    }

    public function uploadResaleImages() {
        if (isset($this->request->post['submit']) || isset($this->request->get['submit'])) {
            $json = array();
            $images_uploaded = array();
            if (!empty($this->request->post['order_id'])) {
                $order_id = $this->request->post['order_id'];
            } else {
                if (!empty($this->request->get['order_id'])) {
                    $order_id = $this->request->get['order_id'];
                }
            }
            $j = 0;     // Variable for indexing uploaded image.

            $error_upload = array();
            for ($i = 0; $i < count($_FILES['fileResale']['name']); $i++) {
                if ($_FILES['fileResale']['size'][$i] > 10485760) {
                    $error_upload[] = basename($_FILES['fileResale']['name'][$i]) . " file size exceeds 10 Mb";
                    continue;
                }
                $target_path = DIR_IMAGE;
                $validextensions = array("jpeg", "jpg", "png", "pdf", "zip");      // Extensions which are allowed.
                $ext = explode('.', basename($_FILES['fileResale']['name'][$i]));   // Explode file name from dot(.)

                $file_extension = end($ext); // Store extensions in the variable.
                $file_extension = strtolower($file_extension);
                if (!in_array($file_extension, $validextensions)) {
                    $error_upload[] = basename($_FILES['fileResale']['name'][$i]) . " file has not valid image extension";
                    continue;
                }
                $hashed_images = "orders/" . $order_id . "_" . md5(uniqid()) . "." . $ext[count($ext) - 1];
                $target_path = $target_path . $hashed_images;

                $j = $j + 1;

                if (move_uploaded_file($_FILES['fileResale']['tmp_name'][$i], $target_path)) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "order_images SET order_id='" . $order_id . "',image_path='" . $this->db->escape($hashed_images) . "'");
                    array_push($images_uploaded, $hashed_images);
                } else {
                    $error_upload[] = basename($_FILES['fileResale']['name'][$i]) . " file could not upload.";
                }
            }
            $this->session->data['error_upload'] = $error_upload;
            if (!empty($error_upload)) {
                $json['result'] = "error";
                $json['output'] = $error_upload;
                echo json_encode($json);
            } else {
                $json['result'] = "success";
                $json['output'] = $images_uploaded;
                echo json_encode($json);
            }
            if (isset($this->request->post['submit'])) {
                if (!empty($this->request->post['order_id'])) {
                    $this->response->redirect($this->url->link('module/pos/main', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->post['order_id'], 'SSL'));
                } else {
                    $this->response->redirect($this->url->link('module/pos/main', 'token=' . $this->session->data['token'], 'SSL'));
                }
            }
        }
    }

    public function getCompleteProductDetail() {
        $json = array();
        $data['permissions'] = $this->user->getPermissions((int) $this->session->data['user_id']);
        //print_r($data['permissions']);
        //return;
        $data['permissions']['update_stock'][0] = 'edit_access';
        if (isset($data['permissions']['update_stock'][0]) && !empty($data['permissions']['update_stock'][0])) {
            $json['permissions'] = $data['permissions'];
            if ($data['permissions']['update_stock'][0] == 'no_access') {
                $json['product_data'] = '';
                $json['location_data'] = '';
                $json['error'] = '<p style="color:red;font-weight:600;padding:10px;">You Don\'t Have Permission To View This Page!</p>';
            } else {
                if (isset($this->request->get['product_id'])) {
                    $query = $this->db->query("SELECT p.*,pd.* FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON(p.product_id=pd.product_id) WHERE p.product_id='" . $this->request->get['product_id'] . "'");
                    if ($query->num_rows) {
                        $locations = array();
                        $location = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_location_quantity WHERE product_id='" . $this->request->get['product_id'] . "' ");
                        if ($location->num_rows) {
                            $locations = $location->rows;
                        }
                        $json['product_data'] = $query->row;
                        $json['location_data'] = $locations;
                    }
                }
            }
        } else {
            if (isset($this->request->get['product_id'])) {
                $query = $this->db->query("SELECT p.*,pd.* FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON(p.product_id=pd.product_id) WHERE p.product_id='" . $this->request->get['product_id'] . "'");
                if ($query->num_rows) {
                    $locations = array();
                    $location = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_location_quantity WHERE product_id='" . $this->request->get['product_id'] . "' ");
                    if ($location->num_rows) {
                        $locations = $location->rows;
                    }
                    $json['product_data'] = $query->row;
                    $json['location_data'] = $locations;
                }
            }
        }
        echo json_encode($json);
    }

    public function getList() {
        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

        if (isset($this->request->get['filter_order_status'])) {
            $filter_order_status = $this->request->get['filter_order_status'];
        } else {
            $filter_order_status = null;
        }

        if (isset($this->request->get['filter_total'])) {
            $filter_total = $this->request->get['filter_total'];
        } else {
            $filter_total = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $filter_date_modified = $this->request->get['filter_date_modified'];
        } else {
            $filter_date_modified = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'o.order_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'], 'SSL');
        $data['shipping'] = $this->url->link('sale/order/shipping', 'token=' . $this->session->data['token'], 'SSL');
        $data['add'] = $this->url->link('sale/order/add', 'token=' . $this->session->data['token'], 'SSL');

        $data['orders'] = array();

        $filter_data = array(
            'filter_order_id' => $filter_order_id,
            'filter_customer' => $filter_customer,
            'filter_order_status' => $filter_order_status,
            'filter_total' => $filter_total,
            'filter_date_added' => $filter_date_added,
            'filter_date_modified' => $filter_date_modified,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => 100
        );
        $this->load->model('sale/order');

        $order_total = $this->model_sale_order->getTotalOrders($filter_data);

        $results = $this->model_sale_order->getOrders($filter_data);

        foreach ($results as $result) {
            $data['orders'][] = array(
                'order_id' => $result['order_id'],
                'customer' => $result['customer'],
                'status' => $result['status'],
                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
                'shipping_code' => $result['shipping_code'],
                'view' => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                'edit' => $this->url->link('sale/order/edit', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
            );
        }

        $data['returns'] = array();
        $this->load->model('sale/return');
        /* $filter_data = array(
          'filter_return_id'        => $filter_return_id,
          'filter_order_id'         => $filter_order_id,
          'filter_customer'         => $filter_customer,
          'filter_product'          => $filter_product,
          'filter_model'            => $filter_model,
          'filter_return_status_id' => $filter_return_status_id,
          'filter_date_added'       => $filter_date_added,
          'filter_date_modified'    => $filter_date_modified,
          'sort'                    => $sort,
          'order'                   => $order,
          'start'                   => ($page - 1) * $this->config->get('config_limit_admin'),
          'limit'                   => $this->config->get('config_limit_admin')
          );
         */
        $filter_data = array();
        $return_total = $this->model_sale_return->getTotalReturns($filter_data);

        //$results = $this->model_sale_return->getReturns($filter_data);
        //$filter_data['filter_pos'] = 1;

        /* foreach ($results as $result) {
          $data['returns'][] = array(
          'return_id'     => $result['return_id'],
          'order_id'      => $result['order_id'],
          'customer'      => $result['customer'],
          'product'       => $result['product'],
          'model'         => $result['model'],
          'price'         => $result['price'],
          'quantity'      => $result['quantity'],
          'status'        => $result['status'],
          'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
          'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
          'edit'          => str_replace("pos","admin",$this->url->link('catalog/wk_rma_admin/getForm', 'token=' . $this->session->data['token'] . '&id=' . $result['return_id'] .'&out_req'. $url, 'SSL'))
          );
          } */
        $results = $this->viewtotalForPOS($filter_data);
        foreach ($results as $result) {
            $status = $this->getOrderStat($result['rma_status']);
            $return_product_details = $this->viewProducts($result['id']);
            $data['returns'][] = array(
                'return_id' => $result['id'],
                'order_id' => $result['order_id'],
                'customer' => $result['name'],
                'type' => $result['return_pos'],
                'product' => $return_product_details[0]['name'],
                'model' => $return_product_details[0]['model'],
                'price' => $return_product_details[0]['price'],
                'quantity' => $return_product_details[0]['quantity'],
                'status' => $status[0]['name'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date'])),
                'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date'])),
                'edit' => str_replace("pos", "admin", $this->url->link('catalog/wk_rma_admin/getForm', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . '&out_req' . $url, 'SSL'))
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_missing'] = $this->language->get('text_missing');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_date_modified'] = $this->language->get('column_date_modified');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_return_id'] = $this->language->get('entry_return_id');
        $data['entry_order_id'] = $this->language->get('entry_order_id');
        $data['entry_customer'] = $this->language->get('entry_customer');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_date_added'] = $this->language->get('entry_date_added');
        $data['entry_date_modified'] = $this->language->get('entry_date_modified');

        $data['button_invoice_print'] = $this->language->get('button_invoice_print');
        $data['button_shipping_print'] = $this->language->get('button_shipping_print');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_view'] = $this->language->get('button_view');
        $data['button_ip_add'] = $this->language->get('button_ip_add');

        $data['token'] = $this->session->data['token'];
        $r_url = str_replace("pos", "admin", $this->url->link('catalog/wk_customer_orders_pos', 'token=' . $this->session->data['token'] . '&out_req' . $url, 'SSL'));
        $data['return_url'] = str_replace("wk_customer_orders_admin", "wk_customer_orders_pos", $r_url);

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
        $data['sort_customer'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
        $data['sort_total'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
        $data['sort_date_modified'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_modified'])) {
            $url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_order_id'] = $filter_order_id;
        $data['filter_customer'] = $filter_customer;
        $data['filter_order_status'] = $filter_order_status;
        $data['filter_total'] = $filter_total;
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_date_modified'] = $filter_date_modified;

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['store'] = HTTPS_CATALOG;
        return $data;

        /* $data['header'] = $this->load->controller('common/header');
          $data['column_left'] = $this->load->controller('common/column_left');
          $data['footer'] = $this->load->controller('common/footer');

          $this->response->setOutput($this->load->view('sale/order_list.tpl', $data)); */
    }

    public function main() {
        $this->language->load('module/pos');
        $this->language->load('sale/order');
        $this->language->load('module/product_labels');
        $this->load->model('pos/pos');
        $this->load->model('sale/order');
        if (isset($this->session->data['resale_id_number'])) {
            unset($this->session->data['resale_id_number']);
        }
        if (isset($this->session->data['outsite_california'])) {
            unset($this->session->data['outsite_california']);
        }

        $data = array();
        $data = $this->getList();
        unset($this->session->data['pos_discount']);
        if (empty($this->request->get['work_mode']) || $this->request->get['work_mode'] == '0' || $this->request->get['work_mode'] == '2') {
            $work_mode = empty($this->request->get['work_mode']) ? '0' : $this->request->get['work_mode'];

            if ($work_mode == '0') {
                // Get the number of orders that were not handled yet
                $data['new_order_num'] = $this->model_pos_pos->get_new_order_number();
            }

            if (!empty($this->request->get['order_id'])) {
                $this->getOrderProducts($this->request->get['order_id'], $data);
            } else {
                $this->getOrderProducts($this->getEmptyOrder(($work_mode == '2')), $data);
                if (empty($this->request->get['ajax'])) {
                    $empty_order_info = array();
                    foreach ($data as $data_key => $data_value) {
                        if ($data_key != 'browse_items' && $data_key != 'customer_groups' && $data_key != 'zones' && $data_key != 'customer_shipping_zones') {
                            $empty_order_info[$data_key] = $data_value;
                        }
                    }
                    $data['empty_order_info'] = $empty_order_info;
                }
            }
        } elseif ($this->request->get['work_mode'] == '1') {
            if (!empty($this->request->get['pos_return_id'])) {
                $return_order_id = $this->model_pos_pos->getReturnOrderId($this->request->get['pos_return_id']);
                $this->getReturnProducts($this->request->get['pos_return_id'], $return_order_id, $data);
            } elseif (!empty($this->request->get['order_id'])) {
                $this->getReturnProducts($this->getEmptyReturn($this->request->get['order_id']), $this->request->get['order_id'], $data);
            } else {
                $this->getReturnProducts($this->getEmptyReturn(0), 0, $data);
            }
        }

        if (!empty($this->request->get['ajax'])) {
            // if it's loading another order, just return order data
            $this->response->setOutput(json_encode($data));
        } else {
            $this->document->setTitle($this->language->get('pos_heading_title'));

            $this->load->model('setting/setting');

            if (isset($this->session->data['pos_user_login'])) {
                unset($this->session->data['pos_user_login']);
                $this->search_for_update();
            }
            $data['user'] = $this->model_pos_pos->get_full_username($this->user->getId(), false);

            $data['text_workmode_sale'] = $this->language->get('text_workmode_sale');
            $data['text_workmode_return'] = $this->language->get('text_workmode_return');
            $data['text_workmode_return_with_order'] = $this->language->get('text_workmode_return_with_order');
            $data['text_workmode_return_no_order'] = $this->language->get('text_workmode_return_no_order');
            $data['text_workmode_quote'] = $this->language->get('text_workmode_quote');
            $data['text_search_placeholder'] = $this->language->get('text_search_placeholder');
            $data['text_order_list'] = $this->language->get('text_order_list');
            $data['text_fetching_orders'] = $this->language->get('text_fetching_orders');
            $data['text_change_order_status'] = $this->language->get('text_change_order_status');
            $data['text_change_quote_status'] = $this->language->get('text_change_quote_status');
            $data['text_change_shipping'] = $this->language->get('text_change_shipping');
            $data['text_fetching_shipping'] = $this->language->get('text_fetching_shipping');
            $data['text_change_customer'] = $this->language->get('text_change_customer');
            $data['text_reset_customer'] = $this->language->get('text_reset_customer');
            $data['text_select_customer'] = $this->language->get('text_select_customer');
            $data['text_add_customer'] = $this->language->get('text_add_customer');
            $data['tab_customer_general'] = $this->language->get('tab_customer_general');
            $data['tab_customer_new_address'] = $this->language->get('tab_customer_new_address');
            $data['text_fetching_customers'] = $this->language->get('text_fetching_customers');
            $data['text_customer_list'] = $this->language->get('text_customer_list');
            $data['column_customer_id'] = $this->language->get('column_customer_id');
            $data['column_company_id'] = 'Company';
            $data['column_customer_name'] = $this->language->get('column_customer_name');
            $data['column_email'] = $this->language->get('column_email');
            $data['column_telephone'] = $this->language->get('column_telephone');
            $data['text_fetching_product_details'] = $this->language->get('text_fetching_product_details');
            $data['text_product_details'] = $this->language->get('text_product_details');
            $data['text_change_quantity'] = $this->language->get('text_change_quantity');
            $data['text_quantity_invalid'] = $this->language->get('text_quantity_invalid');
            $data['text_change_price_title'] = $this->language->get('text_change_price_title');
            $data['text_order_discount_title'] = $this->language->get('text_order_discount_title');
            $data['text_change_price'] = $this->language->get('text_change_price');
            $data['text_use_discount'] = $this->language->get('text_use_discount');
            $data['text_price_invalid'] = $this->language->get('text_price_invalid');
            $data['text_show_totals'] = $this->language->get('text_show_totals');
            $data['text_saving_order_status'] = $this->language->get('text_saving_order_status');
            $data['text_saving_quote_status'] = $this->language->get('text_saving_quote_status');
            $data['text_convert_quote_to_order'] = $this->language->get('text_convert_quote_to_order');
            $data['text_quote_sucess'] = $this->language->get('text_quote_sucess');
            $data['text_set_discount'] = $this->language->get('text_set_discount');
            $data['text_make_payment'] = $this->language->get('text_make_payment');

            $data['text_search_scope'] = $this->language->get('text_search_scope');
            $data['text_search_product_name'] = $this->language->get('text_search_product_name');
            $data['text_search_model_name'] = $this->language->get('text_search_model_name');
            $data['text_search_manufacturer'] = $this->language->get('text_search_manufacturer');
            $data['text_search_model_short'] = $this->language->get('text_search_model_short');
            $data['text_search_manufacturer_short'] = $this->language->get('text_search_manufacturer_short');
            $data['button_set_scope'] = $this->language->get('button_set_scope');

            $data['text_order_id'] = $this->language->get('text_order_id');
            $data['text_invoice_no'] = $this->language->get('text_invoice_no');
            $data['text_invoice_date'] = $this->language->get('text_invoice_date');
            $data['text_store_name'] = $this->language->get('text_store_name');
            $data['text_store_url'] = $this->language->get('text_store_url');
            $data['text_default'] = $this->language->get('text_default');
            $data['text_customer_group'] = $this->language->get('text_customer_group');
            $data['text_total'] = $this->language->get('text_total');
            $data['text_reward'] = $this->language->get('text_reward');
            $data['text_order_status'] = $this->language->get('text_order_status');
            $data['text_comment'] = $this->language->get('text_comment');
            $data['entry_firstname'] = $this->language->get('entry_firstname');
            $data['entry_lastname'] = $this->language->get('entry_lastname');
            $data['entry_email'] = $this->language->get('entry_email');
            $data['entry_telephone'] = $this->language->get('entry_telephone');
            $data['entry_fax'] = $this->language->get('entry_fax');
            $data['entry_company'] = $this->language->get('entry_company');
            $data['entry_company_id'] = $this->language->get('entry_company_id');
            $data['entry_tax_id'] = $this->language->get('entry_tax_id');
            $data['entry_address_1'] = $this->language->get('entry_address_1');
            $data['entry_address_2'] = $this->language->get('entry_address_2');
            $data['entry_city'] = $this->language->get('entry_city');
            $data['entry_postcode'] = $this->language->get('entry_postcode');
            $data['entry_zone'] = $this->language->get('entry_zone');
            $data['entry_country'] = $this->language->get('entry_country');
            $data['text_shipping_method'] = $this->language->get('text_shipping_method');
            $data['text_payment_method'] = $this->language->get('text_payment_method');
            $data['text_download'] = $this->language->get('text_download');
            $data['text_generate'] = $this->language->get('text_generate');
            $data['text_voucher'] = $this->language->get('text_voucher');
            $data['text_add_product_prompt'] = $this->language->get('text_add_product_prompt');
            $data['text_no_product'] = $this->language->get('text_no_product');
            $data['text_order_ready'] = $this->language->get('text_order_ready');
            $data['text_quote_ready'] = $this->language->get('text_quote_ready');
            $data['text_pos_wait'] = $this->language->get('text_pos_wait');
            $data['column_table_id'] = $this->language->get('column_table_id');

            $data['column_product'] = $this->language->get('column_product');
            $data['column_model'] = $this->language->get('column_model');
            $data['column_qty'] = $this->language->get('column_qty');
            $data['column_price'] = $this->language->get('column_price');

            $data['button_save'] = $this->language->get('button_save');
            $data['button_cancel'] = $this->language->get('button_cancel');
            $data['button_add_voucher'] = $this->language->get('button_add_voucher');
            $data['entry_to_name'] = $this->language->get('entry_to_name');
            $data['entry_to_email'] = $this->language->get('entry_to_email');
            $data['entry_from_name'] = $this->language->get('entry_from_name');
            $data['entry_from_email'] = $this->language->get('entry_from_email');
            $data['entry_theme'] = $this->language->get('entry_theme');
            $data['entry_message'] = $this->language->get('entry_message');
            $data['entry_amount'] = $this->language->get('entry_amount');
            $data['text_product'] = $this->language->get('text_product');
            $data['entry_product'] = $this->language->get('entry_product');
            $data['entry_name'] = $this->language->get('entry_name');
            $data['entry_description'] = $this->language->get('entry_description');
            $data['entry_price'] = $this->language->get('entry_price');
            $data['entry_quantity'] = $this->language->get('entry_quantity');
            $data['entry_location'] = $this->language->get('entry_location');
            $data['entry_minimum'] = $this->language->get('entry_minimum');
            $data['entry_thumb'] = $this->language->get('entry_thumb');
            $data['column_attr_name'] = $this->language->get('column_attr_name');
            $data['column_attr_value'] = $this->language->get('column_attr_value');
            // add for SKU begin
            $data['entry_sku'] = $this->language->get('entry_sku');
            $data['text_no_product_for_sku'] = $this->language->get('text_no_product_for_sku');
            // add for SKU end
            // add for UPC begin
            $data['entry_upc'] = $this->language->get('entry_upc');
            $data['text_no_product_for_upc'] = $this->language->get('text_no_product_for_upc');
            // add for UPC end
            // add for MPN begin
            $data['entry_mpn'] = $this->language->get('entry_mpn');
            $data['text_no_product_for_mpn'] = $this->language->get('text_no_product_for_mpn');
            // add for MPN end
            // add for Model begin
            $data['entry_model'] = $this->language->get('entry_model');
            // add for Model end
            $data['entry_quantity'] = $this->language->get('entry_quantity');
            $data['button_upload'] = $this->language->get('button_upload');
            $data['column_quote_id'] = $this->language->get('column_quote_id');
            $data['entry_options'] = $this->language->get('entry_options');

            ///// WAQAR ADDDED THIS CODE

            $data['text_template_settings'] = $this->language->get('text_template_settings');
            $data['text_select_template'] = $this->language->get('text_select_template');
            $data['text_preview'] = $this->language->get('text_preview');
            $data['text_page_size'] = $this->language->get('text_page_size');
            $data['text_label_width'] = $this->language->get('text_label_width');
            $data['text_label_height'] = $this->language->get('text_label_height');
            $data['text_labels_hor'] = $this->language->get('text_labels_hor');
            $data['text_labels_ver'] = $this->language->get('text_labels_ver');
            $data['text_spacer_hor'] = $this->language->get('text_spacer_hor');
            $data['text_spacer_ver'] = $this->language->get('text_spacer_ver');
            $data['text_margin_top'] = $this->language->get('text_margin_top');
            $data['text_margin_left'] = $this->language->get('text_margin_left');
            $data['text_rounded'] = $this->language->get('text_rounded');

            $data['text_select_label'] = $this->language->get('text_select_label');
            $data['text_text'] = $this->language->get('text_text');
            $data['text_list'] = $this->language->get('text_list');
            $data['text_img'] = $this->language->get('text_img');
            $data['text_barcode'] = $this->language->get('text_barcode');
            $data['text_addnew'] = $this->language->get('text_addnew');
            $data['text_add'] = $this->language->get('text_add');
            $data['text_font_f'] = $this->language->get('text_font_f');
            $data['text_font_s'] = $this->language->get('text_font_s');
            $data['text_font_a'] = $this->language->get('text_font_a');
            $data['text_xpos'] = $this->language->get('text_xpos');
            $data['text_ypos'] = $this->language->get('text_ypos');
            $data['text_width'] = $this->language->get('text_width');
            $data['text_height'] = $this->language->get('text_height');
            $data['text_color'] = $this->language->get('text_color');
            $data['text_fill'] = $this->language->get('text_fill');
            $data['text_rect'] = $this->language->get('text_rect');
            $data['text_option_delete'] = $this->language->get('text_option_delete');
            $data['text_option_new_template'] = $this->language->get('text_option_new_template');
            $data['text_option_new_label'] = $this->language->get('text_option_new_label');

            $data['text_placeholder_text'] = $this->language->get('text_placeholder_text');
            $data['text_placeholder_img'] = $this->language->get('text_placeholder_text');
            $data['text_placeholder_font_f'] = $this->language->get('text_placeholder_font_f');
            $data['text_placeholder_font_s'] = $this->language->get('text_placeholder_font_s');
            $data['text_placeholder_font_a'] = $this->language->get('text_placeholder_font_a');
            $data['text_placeholder_xpos'] = $this->language->get('text_placeholder_xpos');
            $data['text_placeholder_ypos'] = $this->language->get('text_placeholder_ypos');
            $data['text_placeholder_width'] = $this->language->get('text_placeholder_width');
            $data['text_placeholder_height'] = $this->language->get('text_placeholder_height');
            $data['text_placeholder_color'] = $this->language->get('text_placeholder_color');
            $data['text_placeholder_fill'] = $this->language->get('text_placeholder_fill');

            $data['text_tip_font_f'] = $this->language->get('text_tip_font_f');
            $data['text_tip_font_s'] = $this->language->get('text_tip_font_s');
            $data['text_tip_font_a'] = $this->language->get('text_tip_font_a');
            $data['text_tip_text'] = $this->language->get('text_tip_text');
            $data['text_tip_img'] = $this->language->get('text_tip_img');
            $data['text_tip_xpos'] = $this->language->get('text_tip_xpos');
            $data['text_tip_ypos'] = $this->language->get('text_tip_ypos');
            $data['text_tip_width'] = $this->language->get('text_tip_width');
            $data['text_tip_height'] = $this->language->get('text_tip_height');
            $data['text_tip_color'] = $this->language->get('text_tip_color');
            $data['text_tip_fill'] = $this->language->get('text_tip_fill');

            $data['text_portrait'] = $this->language->get('text_portrait');
            $data['text_landscape'] = $this->language->get('text_landscape');

            $data['error_saveas_template'] = $this->language->get('error_saveas_template');
            $data['error_saveas_label'] = $this->language->get('error_saveas_label');
            $data['error_delete_template'] = $this->language->get('error_delete_template');
            $data['error_delete_label'] = $this->language->get('error_delete_label');
            $data['error_hi_fields'] = $this->language->get('error_hi_fields');
            $data['error_pdf'] = $this->language->get('error_pdf');
            $data['error_nopdf'] = $this->language->get('error_nopdf');

            $data['button_save'] = $this->language->get('button_save');
            $data['button_cancel'] = $this->language->get('button_cancel');
            $data['button_remove'] = $this->language->get('button_remove');
            $data['button_add'] = $this->language->get('button_add');
            $data['button_delete'] = $this->language->get('button_delete');
            $data['button_saveas'] = $this->language->get('button_saveas');
            $data['button_preview'] = $this->language->get('button_preview');
            $data['button_printpreview'] = $this->language->get('button_printpreview');

            $data['row'] = 0;
            $data['type'] = "text";
            $data['i'] = 1;

            $data['update_needed'] = $this->language->get('text_update_needed');
            $data['new_version'] = $this->language->get('text_new_version');
            $data['please_update'] = $this->language->get('text_please_update');
            $data['this_version'] = "1.0";


            $this->load->model('module/product_labels');
            $data['settings'] = $this->model_setting_setting->getSetting('product_labels');
            $data['labels'] = $this->model_module_product_labels->getLabels();
            $data['label_templates'] = $this->model_module_product_labels->getLabelTemplates();

            $results = $this->model_sale_order->getOrder($data["order_id"]);
            $data["custom_order_details"] = (!empty($results) ? $results : "");
            ///// WAQAR ADDDED THIS CODE END


            if (isset($this->session->data['text_decimal_point']) && isset($this->session->data['text_thousand_point'])) {
                $data['text_decimal_point'] = $this->session->data['text_decimal_point'];
                $data['text_thousand_point'] = $this->session->data['text_thousand_point'];
            } else {
                // get the decimal point and thousand point from the front side language instead of the admin language
                $this->load->model('localisation/language');
                $languages = $this->model_localisation_language->getLanguages();
                $lang_dir = 'english';
                $lang_file = 'english';
                foreach ($languages as $language) {
                    if ($language['code'] == $this->config->get('config_language')) {
                        $lang_dir = $language['directory'];
                        $lang_file = isset($language['filename']) ? $language['filename'] : $language['directory'];
                        break;
                    }
                }
                include_once (DIR_CATALOG . 'language/' . $lang_dir . '/' . $lang_file . '.php');
                // include_once (DIR_CATALOG . 'language/english/default.php');
                $data['text_decimal_point'] = $_['decimal_point'];
                $data['text_thousand_point'] = $_['thousand_point'];

                $this->session->data['text_decimal_point'] = $_['decimal_point'];
                $this->session->data['text_thousand_point'] = $_['thousand_point'];
            }

            // add for Purchase Order Payment begin
            $pos_settings = $this->model_setting_setting->getSetting('POS');
            $payment_type_enables = $this->config->get('POS_payment_type_enables') ? $this->config->get('POS_payment_type_enables') : array();

            $data['text_purchase_order_number'] = $this->language->get('text_purchase_order_number');
            // add for Purchase Order Payment end
            // add for Gift Voucher Payment begin
            $data['text_gift_voucher_code'] = $this->language->get('text_gift_voucher_code');
            $data['text_voucher_code_required'] = $this->language->get('text_voucher_code_required');
            // add for Gift Voucher Payment end
            // add for customer loyalty card begin
            $data['text_reward_points'] = $this->language->get('text_reward_points');
            $data['text_reward_points_balance'] = $this->language->get('text_reward_points_balance');
            $data['column_product_in_order'] = $this->language->get('column_product_in_order');
            $data['column_rewarded_qty'] = $this->language->get('column_rewarded_qty');
            $data['column_points'] = $this->language->get('column_points');
            $data['column_pay_with_points'] = $this->language->get('column_pay_with_points');
            $data['text_no_product_for_points'] = $this->language->get('text_no_product_for_points');
            $data['text_not_enough_reward_points_balance'] = $this->language->get('text_not_enough_reward_points_balance');
            $data['customer_card_prefix'] = $this->config->get('POS_customer_card_prefix') ? $this->config->get('POS_customer_card_prefix') : '';
            $data['entry_card_number'] = $this->language->get('entry_card_number');
            $data['entry_resale_number'] = 'Resale Number';
            $data['entry_notes'] = 'Notes';
            $data['reward_points_value'] = $this->config->get('POS_reward_points_value') ? $this->config->get('POS_reward_points_value') : '';
            $data['reward_points_usage'] = $this->config->get('POS_reward_points_usage') ? $this->config->get('POS_reward_points_usage') : '1';
            $data['text_use_how_many_points'] = $this->language->get('text_use_how_many_points');
            $data['text_points_to_cash_ratio_required'] = $this->language->get('text_points_to_cash_ratio_required');
            $data['text_reward_points_payment_exist'] = $this->language->get('text_reward_points_payment_exist');
            // add for customer loyalty card end
            // add for table management begin
            $data['tables'] = $this->model_pos_pos->getTables(0);
            $data['table_orders'] = $this->model_pos_pos->getTables(1);
            // add for table management end

            $data['heading_title'] = $this->language->get('pos_heading_title');

            $data['text_terminal'] = $this->language->get('text_terminal');
            $data['text_register_mode'] = $this->language->get('text_register_mode');
            $data['text_date_added'] = $this->language->get('text_date_added');
            $data['text_date_modified'] = $this->language->get('text_date_modified');
            $data['text_customer'] = $this->language->get('text_customer');
            $data['text_product_quantity'] = $this->language->get('text_product_quantity');
            $data['text_items_in_cart'] = $this->language->get('text_items_in_cart');
            $data['text_amount_due'] = $this->language->get('text_amount_due');
            $data['text_change'] = $this->language->get('text_change');
            $data['text_payment_zero_amount'] = $this->language->get('text_payment_zero_amount');
            $data['text_quantity_zero'] = $this->language->get('text_quantity_zero');
            $data['text_comments'] = $this->language->get('text_comments');
            $data['text_order_success'] = $this->language->get('text_order_success');
            $data['text_load_order'] = $this->language->get('text_load_order');
            $data['text_filter_order_list'] = $this->language->get('text_filter_order_list');
            $data['text_load_order_list'] = $this->language->get('text_load_order_list');

            $data['text_product_name'] = $this->language->get('text_product_name');
            $data['text_product_upc'] = $this->language->get('text_product_upc');
            $data['text_no_order_selected'] = $this->language->get('text_no_order_selected');
            $data['text_confirm_delete_order'] = $this->language->get('text_confirm_delete_order');
            $data['text_not_available'] = $this->language->get('text_not_available');
            $data['text_del_payment_confirm'] = $this->language->get('text_del_payment_confirm');
            $data['text_autocomplete'] = $this->language->get('text_autocomplete');
            $data['text_customer_no_address'] = $this->language->get('text_customer_no_address');

            $data['column_payment_type'] = $this->language->get('column_payment_type');
            $data['column_payment_amount'] = $this->language->get('column_payment_amount');
            $data['column_payment_note'] = $this->language->get('column_payment_note');
            $data['column_payment_action'] = $this->language->get('column_payment_action');

            $data['button_add_payment'] = $this->language->get('button_add_payment');

            $data['button_existing_order'] = $this->language->get('button_existing_order');
            $data['button_new_order'] = $this->language->get('button_new_order');
            $data['button_complete_order'] = $this->language->get('button_complete_order');
            $data['button_print_invoice'] = $this->language->get('button_print_invoice');
            $data['button_full_screen'] = $this->language->get('button_full_screen');
            $data['button_normal_screen'] = $this->language->get('button_normal_screen');
            $data['button_discount'] = $this->language->get('button_discount');
            $data['button_cut'] = $this->language->get('button_cut');
            $data['button_delete'] = $this->language->get('button_delete');
            $data['button_add_product'] = $this->language->get('button_add_product');
            $data['button_scan_product'] = $this->language->get('button_scan_product');
            $data['text_none'] = $this->language->get('text_none');

            $data['tab_product_search'] = $this->language->get('tab_product_search');
            $data['tab_product_browse'] = $this->language->get('tab_product_browse');
            $data['tab_product_details'] = $this->language->get('tab_product_details');
            $data['tab_order_shipping'] = $this->language->get('tab_order_shipping');
            $data['tab_order_payments'] = $this->language->get('tab_order_payments');
            $data['tab_order_customer'] = $this->language->get('tab_order_customer');

            $data['text_no_results'] = $this->language->get('text_no_results');
            $data['text_missing'] = $this->language->get('text_missing');
            $data['text_wait'] = $this->language->get('text_wait');
            $data['text_not_valid_product'] = $this->language->get('text_not_valid_product');

            $data['column_order_id'] = $this->language->get('column_order_id');
            $data['column_customer'] = $this->language->get('column_customer');
            $data['column_status'] = $this->language->get('column_status');
            $data['column_total'] = $this->language->get('column_total');
            $data['column_date_added'] = $this->language->get('column_date_added');
            $data['column_date_modified'] = $this->language->get('column_date_modified');
            $data['column_action'] = $this->language->get('column_action');

            $data['button_invoice'] = $this->language->get('button_invoice');
            $data['button_insert'] = $this->language->get('button_insert');
            $data['button_filter'] = $this->language->get('button_filter');
            $data['entry_option'] = $this->language->get('entry_option');
            $data['error_required'] = $this->language->get('error_required');
            $data['text_park_order'] = $this->language->get('text_park_order');
            $data['text_park_quote'] = $this->language->get('text_park_quote');

            $data['text_set_shortcut'] = $this->language->get('text_set_shortcut');
            $data['text_no_shortcut'] = $this->language->get('text_no_shortcut');
            $data['text_more_shortcuts'] = $this->language->get('text_more_shortcuts');
            $data['text_title_more_shortcuts'] = $this->language->get('text_title_more_shortcuts');
            $data['text_display_as'] = $this->language->get('text_display_as');

            $data['text_table'] = $this->language->get('text_table');
            $data['text_table_not_selected'] = $this->language->get('text_table_not_selected');
            $data['text_cash_in'] = $this->language->get('text_cash_in');
            $data['text_cash_out'] = $this->language->get('text_cash_out');
            $data['text_stock_manager'] = $this->language->get('text_stock_manager');
            $data['text_order_comment'] = $this->language->get('text_order_comment');
            $data['text_print'] = $this->language->get('text_print');
            $data['text_clear'] = $this->language->get('text_clear');
            $data['text_email_receipt'] = $this->language->get('text_email_receipt');
            $data['text_email'] = $this->language->get('text_email');
            $data['entry_receipt_email'] = $this->language->get('entry_receipt_email');
            $data['text_display_locked_orders'] = $this->language->get('text_display_locked_orders');
            $data['column_order_total'] = $this->language->get('column_order_total');

            $data['text_return_list'] = $this->language->get('text_return_list');
            $data['column_pos_return_id'] = $this->language->get('column_pos_return_id');
            $data['column_return_total'] = $this->language->get('column_return_total');
            $data['text_fetching_returns'] = $this->language->get('text_fetching_returns');
            $data['text_change_return_status'] = $this->language->get('text_change_return_status');
            $data['text_saving_return_status'] = $this->language->get('text_saving_return_status');
            $data['text_return_success'] = $this->language->get('text_return_success');
            $this->load->model('localisation/return_status');
            $data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();
            $data['text_fetching_return_details'] = $this->language->get('text_fetching_return_details');
            $data['text_return_details_title'] = $this->language->get('text_return_details_title');
            $data['column_return_time'] = $this->language->get('column_return_time');
            $data['column_payment_time'] = $this->language->get('column_payment_time');
            $data['text_order_payment_details'] = $this->language->get('text_order_payment_details');
            $data['text_fetching_payment_details'] = $this->language->get('text_fetching_payment_details');

            $data['text_existing_returns'] = $this->language->get('text_existing_returns');
            $data['column_return_product'] = $this->language->get('column_return_product');
            $data['column_return_customer'] = $this->language->get('column_return_customer');
            $data['column_return_email'] = $this->language->get('column_return_email');
            $data['column_return_quantity'] = $this->language->get('column_return_quantity');
            $data['column_return_comment'] = $this->language->get('column_return_comment');

            // add for Report begin
            $data['report_heading_title'] = $this->language->get('report_heading_title');
            // add for Report end
            // add for Print begin
            $data['print_wait_title'] = $this->language->get('print_wait_title');
            $data['print_wait_message'] = $this->language->get('print_wait_message');
            $data['print_sign_message'] = $this->language->get('print_sign_message');
            $data['print_receipt_message'] = $this->language->get('print_receipt_message');
            // add for Print end
            // add for Invoice Print begin
            $data['print_invoice_message'] = $this->language->get('print_invoice_message');
            // add for Invoice Print end
            // add for Discount begin
            $data['tab_order_discount'] = $this->language->get('tab_order_discount');
            $data['text_discount_title'] = $this->language->get('text_discount_title');
            $data['text_discount_message'] = $this->language->get('text_discount_message');
            $data['text_discount_type_amount'] = $this->language->get('text_discount_type_amount');
            $data['text_discount_type_percentage'] = $this->language->get('text_discount_type_percentage');
            $data['text_discount_subtotal'] = $this->language->get('text_discount_subtotal');
            $data['text_discount_total'] = $this->language->get('text_discount_total');
            $data['text_discount'] = $this->language->get('text_discount');
            $data['text_discounted'] = $this->language->get('text_discounted');
            $data['text_discounted_title'] = $this->language->get('text_discounted_title');
            $data['button_discount'] = $this->language->get('button_discount');
            $data['text_apply_discount'] = $this->language->get('text_apply_discount');
            // add for Discount end
            // add for Manufacturer Product begin
            $data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
            // add for Manufacturer Product end
            // add for edit order address begin
            $data['text_order_shipping_address'] = $this->language->get('text_order_shipping_address');
            $data['text_order_payment_address'] = $this->language->get('text_order_payment_address');
            $data['entry_order_address'] = $this->language->get('entry_order_address');
            $data['button_edit_address'] = $this->language->get('button_edit_address');
            $data['entry_shipping_method'] = $this->language->get('entry_shipping_method');
            // add for edit order address end
            // add for Quotation begin
            $data['text_order_quote'] = $this->language->get('text_order_quote');
            $data['text_new_order'] = $this->language->get('text_new_order');
            $data['text_new_quote'] = $this->language->get('text_new_quote');
            $data['column_quote_id'] = $this->language->get('column_quote_id');
            $data['text_list_order'] = $this->language->get('text_list_order');
            $data['text_list_quote'] = $this->language->get('text_list_quote');
            $data['text_work_mode'] = '0';
            if (isset($this->request->get['work_mode'])) {
                $data['text_work_mode'] = $this->request->get['work_mode'];
            }
            $data['text_confirm_complete'] = $this->language->get('text_confirm_complete');
            $data['text_confirm_convert'] = $this->language->get('text_confirm_convert');
            $data['text_existing_quotes'] = $this->language->get('text_existing_quotes');
            $data['text_convert_to_order'] = $this->language->get('text_convert_to_order');
            // add for Quotation end
            // add for return begin
            $data['text_order_for_return'] = $this->language->get('text_order_for_return');
            $data['text_complete_return'] = $this->language->get('text_complete_return');
            $data['tab_order_return'] = $this->language->get('tab_order_return');
            $data['text_return_ready'] = $this->language->get('text_return_ready');
            $data['text_product_return_title'] = $this->language->get('text_product_return_title');
            $data['entry_opened'] = $this->language->get('entry_opened');
            $data['text_opened'] = $this->language->get('text_opened');
            $data['text_unopened'] = $this->language->get('text_unopened');
            $data['text_return_add'] = $this->language->get('text_return_add');
            $data['entry_return_reason'] = $this->language->get('entry_return_reason');
            $data['entry_return_action'] = $this->language->get('entry_return_action');
            $data['button_return_product'] = $this->language->get('button_return_product');
            $data['text_product_returned'] = $this->language->get('text_product_returned');
            $data['text_return_quantity_invalid'] = $this->language->get('text_return_quantity_invalid');
            $data['text_no_return_selected'] = $this->language->get('text_no_return_selected');
            $data['text_confirm_delete_return'] = $this->language->get('text_confirm_delete_return');
            // add for return end
            // add for Quick sale begin
            $data['text_title_quick_sale'] = $this->language->get('text_title_quick_sale');
            $data['text_quick_sale'] = $this->language->get('text_quick_sale');
            $data['entry_quick_sale_name'] = $this->language->get('entry_quick_sale_name');
            $data['entry_quick_sale_model'] = $this->language->get('entry_quick_sale_model');
            $data['entry_quick_sale_price'] = $this->language->get('entry_quick_sale_price');
            $data['entry_quick_sale_tax'] = $this->language->get('entry_quick_sale_tax');
            $data['text_quick_sale_include_tax'] = $this->language->get('text_quick_sale_include_tax');
            $data['text_quick_sale_shipping'] = $this->language->get('text_quick_sale_shipping');

            $data['date_format_short'] = $this->language->get('date_format_short');
            $data['time_format'] = $this->language->get('time_format');

            $this->load->model('localisation/tax_class');
            $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClass(5);
            // add for Quick sale end
            // add for Browse begin
            $data['text_top_category_id'] = '0';
            $data['text_top_category_name'] = $this->language->get('text_top_category_name');
            // add for Browse end
            // add for serial no begin
            $data['entry_product_sn'] = $this->language->get('entry_product_sn');
            $data['text_product_sn_not_found'] = $this->language->get('text_product_sn_not_found');
            // add for serial no end
            $data['column_product_name'] = $this->language->get('column_product_name');
            $data['column_product_options'] = $this->language->get('column_product_options');
            $data['button_ok'] = $this->language->get('button_ok');
            $data['text_customer_incoming'] = $this->language->get('text_customer_incoming');
            $data['text_confirm_complete_without_payment'] = $this->language->get('text_confirm_complete_without_payment');

            $text_week_0 = $this->language->get('text_week_0');
            $text_week_1 = $this->language->get('text_week_1');
            $text_week_2 = $this->language->get('text_week_2');
            $text_week_3 = $this->language->get('text_week_3');
            $text_week_4 = $this->language->get('text_week_4');
            $text_week_5 = $this->language->get('text_week_5');
            $text_week_6 = $this->language->get('text_week_6');
            $data['text_weeks'] = array($text_week_0, $text_week_1, $text_week_2, $text_week_3, $text_week_4, $text_week_5, $text_week_6);

            $text_month_1 = $this->language->get('text_month_1');
            $text_month_2 = $this->language->get('text_month_2');
            $text_month_3 = $this->language->get('text_month_3');
            $text_month_4 = $this->language->get('text_month_4');
            $text_month_5 = $this->language->get('text_month_5');
            $text_month_6 = $this->language->get('text_month_6');
            $text_month_7 = $this->language->get('text_month_7');
            $text_month_8 = $this->language->get('text_month_8');
            $text_month_9 = $this->language->get('text_month_9');
            $text_month_10 = $this->language->get('text_month_10');
            $text_month_11 = $this->language->get('text_month_11');
            $text_month_12 = $this->language->get('text_month_12');
            $data['text_monthes'] = array($text_month_1, $text_month_2, $text_month_3, $text_month_4, $text_month_5, $text_month_6, $text_month_7, $text_month_8, $text_month_9, $text_month_10, $text_month_11, $text_month_12);

            $data['text_enabled'] = $this->language->get('text_enabled');
            $data['text_disabled'] = $this->language->get('text_disabled');
            $data['text_select'] = $this->language->get('text_select');

            $data['entry_password'] = $this->language->get('entry_password');
            $data['entry_confirm'] = $this->language->get('entry_confirm');
            $data['entry_newsletter'] = $this->language->get('entry_newsletter');
            $data['entry_customer_group'] = $this->language->get('entry_customer_group');
            $data['entry_status'] = $this->language->get('entry_status');
            $data['entry_default'] = $this->language->get('entry_default');

            $data['button_add_address'] = $this->language->get('button_add_address');
            $data['button_remove'] = $this->language->get('button_remove');

            $data['tab_general'] = $this->language->get('tab_general');
            $data['tab_address'] = $this->language->get('tab_address');

            if (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
            } else {
                $data['error_warning'] = '';
            }

            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => false
            );
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('pos_heading_title'),
                'href' => $this->url->link('module/pos/main', 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => ' :: '
            );

            $data['action'] = $this->url->link('module/pos', 'token=' . $this->session->data['token'], 'SSL');

            $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

            $data['payment_types'] = array();
            $data['text_select'] = $this->language->get('text_select');

            // add for Discount begin
            // add for product based discount begin
            $data['text_product_discount_title'] = $this->language->get('text_product_discount_title');
            // add for product based discount end
            // add for Discount end
            // add for Hiding Delete begin
            $delete_excluded_groups = array();
            if ($this->config->get('POS_enable_hide_delete') && $this->config->get('POS_delete_excluded_groups')) {
                $delete_excluded_groups = $this->config->get('POS_delete_excluded_groups');
            }

            $order_lock_groups = array();
            if ($this->config->get('POS_order_lock_groups')) {
                $order_lock_groups = $this->config->get('POS_order_lock_groups');
            }

            $data['user_id'] = $this->user->getId();
            $this->load->model('user/user');
            $user = $this->model_user_user->getUser($this->user->getId());
            $user_group_id = 0;
            if ($user) {
                $user_group_id = $user['user_group_id'];
            }
            $data['display_delete'] = 0;
            if ($this->config->get('POS_enable_hide_delete') == null || in_array($user_group_id, $delete_excluded_groups)) {
                $data['display_delete'] = 1;
            }
            $data['display_lock'] = 0;
            if (in_array($user_group_id, $order_lock_groups)) {
                $data['display_lock'] = 1;
            }
            // add for Hiding Delete end
            // add for Maximum Discount begin
            $max_discount_fixed = 0;
            if ($this->config->get('POS_' . $user_group_id . '_max_discount_fixed')) {
                $max_discount_fixed = $this->config->get('POS_' . $user_group_id . '_max_discount_fixed');
            }
            $data['max_discount_fixed'] = $max_discount_fixed;
            $max_discount_percentage = 0;
            if ($this->config->get('POS_' . $user_group_id . '_max_discount_percentage')) {
                $max_discount_percentage = $this->config->get('POS_' . $user_group_id . '_max_discount_percentage');
            }
            $data['max_discount_percentage'] = $max_discount_percentage;
            $data['text_max_discount_limit'] = sprintf($this->language->get('text_max_discount_limit'), $data['max_discount_fixed'], $data['max_discount_percentage']);
            // add for Maximum Discount end

            $data['payment_types'] = $this->config->get('POS_POS_payment_types');
            // Control the payment type to be displayed as per enablement
            if (!empty($data['payment_types'])) {
                foreach ($data['payment_types'] as $payment_type => $payment_name) {
                    if (!(array_key_exists($payment_type, $payment_type_enables) && $payment_type_enables[$payment_type] == 1)) {
                        unset($data['payment_types'][$payment_type]);
                    }
                }
            }

            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $data['store_url'] = HTTPS_CATALOG;
            } else {
                $data['store_url'] = HTTP_CATALOG;
            }

            $data['full_screen_mode'] = 1;

            // add for product return begin
            $this->load->model('localisation/return_reason');
            $data['return_reasons'] = $this->model_localisation_return_reason->getReturnReasons();
            $this->load->model('localisation/return_action');
            $return_actions = $this->model_localisation_return_action->getReturnActions();
            $data['return_actions'] = $return_actions;
            // add for product return end
            $data['text_saving_customer'] = $this->language->get('text_saving_customer');

            // add for Cash type begin
            $cash_types = $this->config->get('POS_cash_types');
            $cash_notes = array();
            $cash_coins = array();
            $text_note = $this->language->get('text_cash_type_note');
            if (!empty($cash_types)) {
                foreach ($cash_types as $cash_type) {
                    $cash_type['image'] = $this->resize($cash_type['image'], 80, 80);
                    if (empty($cash_type['display'])) {
                        $cash_type['display'] = $this->currency->formatFront($cash_type['value']);
                    }
                    if ($cash_type['type'] == $text_note) {
                        array_push($cash_notes, $cash_type);
                    } else {
                        array_push($cash_coins, $cash_type);
                    }
                }
                $sort_order = array();
                foreach ($cash_notes as $cash_note) {
                    $sort_order[] = $cash_note['value'];
                }
                array_multisort($sort_order, SORT_DESC, $cash_notes);
                $sort_order = array();
                foreach ($cash_coins as $cash_coin) {
                    $sort_order[] = $cash_coin['value'];
                }
                array_multisort($sort_order, SORT_DESC, $cash_coins);
                $cash_types = array($cash_notes, $cash_coins);
            }
            $data['cash_types'] = $cash_types;
            // add for Cash type end
            // add for Complete Status begin
            $data['complete_status_id'] = $this->config->get('POS_complete_status_id') ? $this->config->get('POS_complete_status_id') : 5;
            $data['return_complete_status_id'] = $this->config->get('POS_return_complete_status_id') ? $this->config->get('POS_return_complete_status_id') : 3;
            $data['quote_complete_status_id'] = $this->config->get('POS_quote_complete_status_id') ? $this->config->get('POS_quote_complete_status_id') : 1;
            $data['parking_status_id'] = $this->config->get('POS_parking_status_id') ? $this->config->get('POS_parking_status_id') : 1;
            $data['void_status_id'] = $this->config->get('POS_void_status_id') ? $this->config->get('POS_void_status_id') : 16;
            $data['gift_receipt_status_id'] = $this->config->get('POS_gift_receipt_status_id') ? $this->config->get('POS_gift_receipt_status_id') : 0;
            $data['gift_collected_status_id'] = $this->config->get('POS_gift_collected_status_id') ? $this->config->get('POS_gift_collected_status_id') : 0;
            // add for Complete Status end

            $data['token'] = $this->session->data['token'];
            $data['total_orders'] = $this->model_pos_pos->getTotalOrders(array('filter_user_id'=>$this->user->getId()));
            $this->load->model('localisation/order_status');
            $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
            $data['sales_person'] = $this->model_pos_pos->getSalesPersons($this->config->get('config_users_group'));
            $data['order_statuses_incoming_orders'] = $data['order_statuses'];
            $complete_status_id = $this->config->get('POS_complete_status_id') ? $this->config->get('POS_complete_status_id') : 5;
            $order_payment_post_status = array($complete_status_id => $complete_status_id);
            $data['order_payment_post_status'] = $this->config->get('POS_order_payment_post_status') ? $this->config->get('POS_order_payment_post_status') : $order_payment_post_status;
            if (!empty($data['order_payment_post_status'])) {
                foreach ($data['order_payment_post_status'] as $post_order_status_id => $value) {
                    foreach ($data['order_statuses'] as $data_order_status) {
                        if ($data_order_status['order_status_id'] == $post_order_status_id) {
                            $data['order_payment_post_status'][$post_order_status_id] = $data_order_status['name'];
                            break;
                        }
                    }
                }
            }
            // add for Hiding Order Status begin
            $order_hiding_status = array();
            if ($this->config->get('POS_order_hiding_status')) {
                $order_hiding_status = $this->config->get('POS_order_hiding_status');
            }
            foreach ($data['order_statuses'] as $key => $value) {
                if (array_key_exists($value['order_status_id'], $order_hiding_status)) {
                    unset($data['order_statuses'][$key]);
                }
            }
            // add for Hiding Order Status end
            //$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
            $order_locking_status = array();
            if ($this->config->get('POS_order_locking_status')) {
                $order_locking_status = $this->config->get('POS_order_locking_status');
            }
            $data['order_locking_status'] = $order_locking_status;

            // add for Empty order control begin
            foreach ($data['order_statuses'] as $key => $value) {
                if ($value['order_status_id'] == $data['order_status_id']) {
                    $data['order_status_name'] = $value['name'];
                }
            }
            // add for Empty order control end
            $data['quote_statuses'] = $this->model_pos_pos->getQuoteStatuses();
            if (!empty($data['quote_statuses'])) {
                foreach ($data['quote_statuses'] as $quote_status) {
                    $data['empty_quote_status_id'] = $quote_status['quote_status_id'];
                    $data['empty_quote_status_name'] = $quote_status['name'];
                }
            }

            $data['customer_groups'] = $this->model_pos_pos->getCustomerGroups();
            $data['customer_countries'] = $this->model_localisation_country->getCountries();

            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $data['store_admin_url'] = HTTPS_SERVER;
            } else {
                $data['store_admin_url'] = HTTP_SERVER;
            }
            // add for new UI
            $data['text_order_options'] = $this->language->get('text_order_options');
            $data['text_shortcuts'] = $this->language->get('text_shortcuts');
            $data['text_shortcut_list_full'] = $this->language->get('text_shortcut_list_full');
            $data['text_shortcut_added'] = $this->language->get('text_shortcut_added');
            $data['text_subtotal'] = $this->language->get('text_subtotal');
            $data['text_grandtotal'] = $this->language->get('text_grandtotal');
            $data['text_pay'] = $this->language->get('text_pay');
            $data['button_void_order'] = $this->language->get('button_void_order');
            $data['button_park_order'] = $this->language->get('button_park_order');
            $data['button_close_order'] = $this->language->get('button_close_order');
            $data['button_complete_order'] = $this->language->get('button_complete_order');
            $data['text_give_discount'] = $this->language->get('text_give_discount');
            $data['text_cash_success'] = $this->language->get('text_cash_success');
            $data['text_accelerate'] = $this->language->get('text_accelerate');
            $data['column_cash_display'] = $this->language->get('column_cash_display');
            $data['column_details_price'] = $this->language->get('column_details_price');
            $data['column_details_model'] = $this->language->get('column_details_model');
            $data['column_details_quantity'] = $this->language->get('column_details_quantity');
            $data['column_details_manufacturer'] = $this->language->get('column_details_manufacturer');
            $data['column_details_sku'] = $this->language->get('column_details_sku');
            $data['column_details_upc'] = $this->language->get('column_details_upc');
            $data['column_details_location'] = $this->language->get('column_details_location');
            $data['column_details_minimum'] = $this->language->get('column_details_minimum');
            $data['column_details_requried'] = $this->language->get('column_details_requried');
            $data['text_yes'] = $this->language->get('text_yes');
            $data['text_no'] = $this->language->get('text_no');
            $data['text_discount_value_invalid'] = $this->language->get('text_discount_value_invalid');
            $data['button_quote_to_order'] = $this->language->get('button_quote_to_order');
            // add for offline support
            $data['text_local_save_order_status_success'] = $this->language->get('text_local_save_order_status_success');
            $data['text_local_save_quote_status_success'] = $this->language->get('text_local_save_quote_status_success');
            $data['text_local_save_return_status_success'] = $this->language->get('text_local_save_return_status_success');
            $data['text_order_not_in_local'] = $this->language->get('text_order_not_in_local');
            $data['text_return_not_in_local'] = $this->language->get('text_return_not_in_local');
            $data['text_local_product_not_found'] = $this->language->get('text_local_product_not_found');
            $data['text_local_order_not_initialized'] = $this->language->get('text_local_order_not_initialized');
            $data['text_local_return_not_initialized'] = $this->language->get('text_local_return_not_initialized');
            $data['text_customer_not_in_local'] = $this->language->get('text_customer_not_in_local');
            $data['text_product_not_in_local'] = $this->language->get('text_product_not_in_local');
            $data['text_return_order_not_loaded'] = $this->language->get('text_return_order_not_loaded');
            $data['text_cannot_send_email_when_offline'] = $this->language->get('text_cannot_send_email_when_offline');
            $data['text_cannot_process_cc_when_offline'] = $this->language->get('text_cannot_process_cc_when_offline');
            $data['text_cannot_check_online_when_offline'] = $this->language->get('text_cannot_check_online_when_offline');
            $data['text_no_order_payment_found'] = $this->language->get('text_no_order_payment_found');
            $data['text_no_return_details_found'] = $this->language->get('text_no_return_details_found');
            $data['text_cannot_check_return_when_offline'] = $this->language->get('text_cannot_check_return_when_offline');
            $data['text_wait_saving_data_to_local'] = $this->language->get('text_wait_saving_data_to_local');
            $data['text_wait_saving_data_to_server'] = $this->language->get('text_wait_saving_data_to_server');
            $data['text_product_returned_successfully'] = $this->language->get('text_product_returned_successfully');
            $data['text_return_for_order'] = $this->language->get('text_return_for_order');
            $data['text_pagination'] = $this->language->get('text_pagination');
            $data['text_return_tax'] = $this->language->get('text_return_tax');
            $data['text_return_sub_total'] = $this->language->get('text_return_sub_total');
            $data['text_return_total'] = $this->language->get('text_return_total');
            $data['user_info'] = $this->language->get('user_info');

            $data['symbol_left'] = $this->currency->getSymbolLeft();
            $data['symbol_right'] = $this->currency->getSymbolRight();

            $this->load->model('tool/image');
            $data['no_image_url'] = $this->model_tool_image->resize('no_image.jpg', 180, 180);
            $data['default_customer'] = $this->get_default_customer();

            // get all config and save it to the local js
            $data['config'] = array();
            $config_query = $this->db->query("SELECT `key` FROM " . DB_PREFIX . "setting WHERE store_id = '0'");
            foreach ($config_query->rows as $row) {
                $key = $row['key'];
                if (substr($key, 0, 4) == 'POS_') {
                    $key = substr($key, 4);
                }
                $data['config'][$key] = $this->config->get($row['key']);
            }

            // generate javascript for variables, that can be used for the order processing
            $var_js_content = '';
            foreach ($data as $key => $value) {
                if (strpos($key, '_') === 0 || $key == 'header' || $key == 'footer' || $key == 'orders') {
                    // some special variables, ignore
                } else {
                    if (is_array($value)) {
                        $var_js_content .= "var " . $key . " = " . json_encode($value) . ";\n";
                    } elseif (is_string($value)) {
                        $var_js_content .= "var " . $key . " = '" . addslashes($value) . "';\n";
                    } elseif (is_int($value) || is_numeric($value) || is_long($value)) {
                        $var_js_content .= "var " . $key . " = " . $value . ";\n";
                    }
                }
            }
            file_put_contents(DIR_APPLICATION . 'view/javascript/pos/pos_vars.js', $var_js_content);
            // sync the totals from front to the backend
            $this->sync_total_models();

            $data['header'] = $this->load->controller('common/header');
            // add in the offline support for this page only
            $enable_offline_mode = $this->config->get('POS_enable_offline_mode');
            if (!empty($enable_offline_mode)) {
                // update the manifest file
                $this->update_manifest();
                $data['header'] = str_replace('<html', '<html manifest="view/template/pos/offline/pos.manifest"', $data['header']);
            }
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');
            $data['pos_settings'] = $this->url->link('module/pos', 'token=' . $this->session->data['token'], 'SSL');
            $data['back_to_admin_dashboard'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
            $data['back_to_admin_dashboard'] = str_replace("/pos/", "/admin/", $data['back_to_admin_dashboard']);
            $data['is_paaid_order'] = $this->model_pos_pos->checkOrderIsPaid($data['order_id']);
            $data['payment_status'] = $this->model_pos_pos->getOrderPaymentStatus($data['order_id']);
            $data['order_shipping'] = $this->model_pos_pos->getOrderShippingTotal($data['order_id']);
            $data['order_has_tax'] = $this->model_pos_pos->checkOrderHasTax($data['order_id']);
            $data['current_order_subtotal'] = $this->model_pos_pos->getCurrentOrderTotal($data['order_id'], 'sub_total');
            $data['current_order_shipping'] = $this->model_pos_pos->getCurrentOrderTotal($data['order_id'], 'shipping');
            $data['current_order_tax'] = $this->model_pos_pos->getCurrentOrderTotal($data['order_id'], 'tax');
            $data['order_discount_amount'] = $this->currency->formatFront($this->model_pos_pos->getOrderDiscountAmount($data['order_id']));
            if (isset($this->session->data['discount_customer_group_id'])) {
                $data['discount_customer_group_id'] = $this->session->data['discount_customer_group_id'];
                $_info = $this->model_pos_pos->getCustomerGroup($data['discount_customer_group_id']);
                if ($_info) {
                    $data['c_customer_group'] = $_info['name'];
                }
            } else {
                $data['discount_customer_group_id'] = $data['customer_group_id'];
                $data['c_customer_group'] = $data['customer_group'];
            }
            $data['iosapptoken'] = $this->user->getUserName() . ':' . $this->user->getpassword();
            $data['resale_images'] = $this->getResaleImages($data['order_id_text']);

            $data['permissions'] = $this->user->getPermissions((int) $this->session->data['user_id']);
            $this->response->setOutput($this->load->view('pos/mainv2.tpl', $data));
        }
    }

    private function search_for_update() {
        $post_string = 'version=' . POS_VERSION . '&domain_name=' . $_SERVER['SERVER_NAME'];
        $url = 'http://www.pos4opencart.com/shop/pos_update.php';
        $parts = parse_url($url);

        $fp = @fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);

        if ($fp) {
            $out = "POST " . $parts['path'] . " HTTP/1.1\r\n";
            $out .= "Host: " . $parts['host'] . "\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "Content-Length: " . strlen($post_string) . "\r\n";
            $out .= "Connection: Close\r\n\r\n";
            $out .= $post_string;

            fwrite($fp, $out);
            fclose($fp);
        }
    }

    public function getOrderList() {
        $limit = 8;

        $this->load->model('pos/pos');
        $data = $this->request->post;
        if (!isset($data['limit'])) {
            $data['limit'] = $limit;
        }
        if (!isset($data['start'])) {
            $data['start'] = 0;
            if (isset($data['page'])) {
                $data['start'] = ((int) $data['page'] - 1) * $data['limit'];
            }
        }

        if (isset($this->request->get['work_mode']) && $this->request->get['work_mode'] == '2') {
            $data['filter_quote'] = 1;
        }
        // add for Hiding Order Status begin
        $order_hiding_status = array();
        if ($this->config->get('POS_order_hiding_status')) {
            $order_hiding_status = $this->config->get('POS_order_hiding_status');
        }
        $data['order_hiding_status'] = $order_hiding_status;
        $order_locking_status = array();
        if ($this->config->get('POS_order_locking_status')) {
            $order_locking_status = $this->config->get('POS_order_locking_status');
        }
        $data['order_locking_status'] = $order_locking_status;
        // add for user can only manage his/her own orders begin
        // the control is for POS users only
        $user_query = $this->db->query("SELECT g.name FROM `" . DB_PREFIX . "user` u LEFT JOIN `" . DB_PREFIX . "user_group` g ON u.user_group_id = g.user_group_id WHERE user_id = '" . $this->user->getId() . "'");
        if ($user_query->row) {
            if ($user_query->row['name'] == 'POS') {
                $data['filter_user_id'] = $this->user->getId();
            }
        }

        $order_total = $this->model_pos_pos->getTotalOrders($data);
        $results = $this->model_pos_pos->getOrders($data);

        $json = array();
        $json['orders'] = array();
        foreach ($results as $result) {
            $json['orders'][] = array(
                'order_id' => $result['order_id'],
                'table_name' => $result['name'] ? $result['name'] : '',
                'customer' => $result['customer'],
                'status' => $result['status'],
                'status_id' => $result['order_status_id'],
                'user_id' => $result['user_id'],
                'email' => $result['email'],
                'total' => $this->currency->formatFront($result['total'], $result['currency_code'], $result['currency_value']),
                'salesperson' => $result['salesperson'],
                'date_added' => strtotime($result['date_added']),
                'date_modified' => strtotime($result['date_modified'])
            );
        }
        $page = (!empty($data['page'])) ? $data['page'] : 1;
        $json['pagination'] = $this->getPagination($order_total, $page, $limit, 'selectOrderPage');

        // Get the number of orders that were not handled yet
        $json['new_order_num'] = $this->model_pos_pos->get_new_order_number();

        // calculate the timezone offset
        if (!empty($this->request->post['timezone_offset'])) {
            // get DB time
            $time_query = $this->db->query("SELECT NOW() AS now");
            $time_db = strtotime($time_query->row['now']);
            $json['timezone_offset'] = ((int) $this->request->post['browser_time'] - $time_db) / 60;
        }

        $this->response->setOutput(json_encode($json));
    }

    public function deleteOrder() {
        if (isset($this->request->post['order_selected'])) {
            $this->load->model('pos/pos');
            // selected orders to be deleted
            foreach ($this->request->post['order_selected'] as $order_id) {
                $this->model_pos_pos->deleteOrder($order_id);
            }
            $this->getOrderList();
        }
    }

    public function deleteEmptyOrders() {
        $this->load->model('pos/pos');
        $this->model_pos_pos->deleteEmptyPosOrders();
        $this->getOrderList();
    }

    public function getReturnListTab() {
        $limit = 8;

        $this->load->model('pos/pos');
        $data = $this->request->post;
        if (!isset($data['limit'])) {
            $data['limit'] = $limit;
        }
        if (!isset($data['start'])) {
            $data['start'] = 0;
            if (isset($data['page'])) {
                $data['start'] = ((int) $data['page'] - 1) * $data['limit'];
            }
        }

        // add for user can only manage his/her own orders begin
        // the control is for POS users only
        $user_query = $this->db->query("SELECT g.name FROM `" . DB_PREFIX . "user` u LEFT JOIN `" . DB_PREFIX . "user_group` g ON u.user_group_id = g.user_group_id WHERE user_id = '" . $this->user->getId() . "'");
        if ($user_query->row) {
            if ($user_query->row['name'] == 'POS') {
                $data['filter_user_id'] = $this->user->getId();
            }
        }
        $data['customer_id'] = $data['filter_user_id'];
        $return_total = $this->model_pos_pos->getTotalReturns($data);
        $results = $this->model_pos_pos->getReturnsTab($data);

        $json = array();
        $json['returns'] = array();
        if (!empty($results)) {
            foreach ($results as $result) {
                $json['returns'][] = array(
                    'name' => $result['name'],
                    'product_id' => $result['product_id'],
                    'model' => $result['model'],
                    'quantity' => $result['quantity'],
                    'order_product_id' => $result['order_product_id'],
                    'return_quantity' => $result['rma_quantity_sum'],
                );
            }
        }
        $page = (!empty($data['page'])) ? $data['page'] : 1;
        $json['pagination'] = $this->getPagination($return_total, $page, $limit, 'selectReturnPage');

        // Get the number of orders that were not handled yet
        $json['new_order_num'] = $this->model_pos_pos->get_new_order_number();

        // calculate the timezone offset
        if (!empty($this->request->post['timezone_offset'])) {
            // get DB time
            $time_query = $this->db->query("SELECT NOW() AS now");
            $time_db = strtotime($time_query->row['now']);
            $json['timezone_offset'] = ((int) $this->request->post['browser_time'] - $time_db) / 60;
        }

        $this->response->setOutput(json_encode($json));
    }

    public function getCustomerNotes() {
        if (isset($this->request->get['customer_id'])) {
            $customer_info = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_notes WHERE customer_id='" . $this->request->get['customer_id'] . "'");
            if ($customer_info->num_rows) {
                echo $customer_info->row['customer_notes'];
            } else {
                echo "0";
            }
        }
    }

    public function getReturnList() {
        $limit = 8;

        $this->load->model('pos/pos');
        $data = $this->request->post;
        if (!isset($data['limit'])) {
            $data['limit'] = $limit;
        }
        if (!isset($data['start'])) {
            $data['start'] = 0;
            if (isset($data['page'])) {
                $data['start'] = ((int) $data['page'] - 1) * $data['limit'];
            }
        }

        // add for user can only manage his/her own orders begin
        // the control is for POS users only
        $user_query = $this->db->query("SELECT g.name FROM `" . DB_PREFIX . "user` u LEFT JOIN `" . DB_PREFIX . "user_group` g ON u.user_group_id = g.user_group_id WHERE user_id = '" . $this->user->getId() . "'");
        if ($user_query->row) {
            if ($user_query->row['name'] == 'POS') {
                $data['filter_user_id'] = $this->user->getId();
            }
        }

        $return_total = $this->model_pos_pos->getTotalReturns($data);
        $results = $this->model_pos_pos->getReturns($data);

        $json = array();
        $json['returns'] = array();
        if (!empty($results)) {
            foreach ($results as $result) {
                $json['returns'][] = array(
                    'pos_return_id' => $result['pos_return_id'],
                    'customer' => $result['customer'],
                    'status' => $result['status'],
                    'status_id' => $result['return_status_id'],
                    'user_id' => $result['user_id'],
                    'email' => $result['email'],
                    'total' => $this->currency->formatFront($result['total']),
                    'date_added' => strtotime($result['date_added']),
                    'date_modified' => strtotime($result['date_modified'])
                );
            }
        }
        $page = (!empty($data['page'])) ? $data['page'] : 1;
        $json['pagination'] = $this->getPagination($return_total, $page, $limit, 'selectReturnPage');

        // Get the number of orders that were not handled yet
        $json['new_order_num'] = $this->model_pos_pos->get_new_order_number();

        // calculate the timezone offset
        if (!empty($this->request->post['timezone_offset'])) {
            // get DB time
            $time_query = $this->db->query("SELECT NOW() AS now");
            $time_db = strtotime($time_query->row['now']);
            $json['timezone_offset'] = ((int) $this->request->post['browser_time'] - $time_db) / 60;
        }

        $this->response->setOutput(json_encode($json));
    }

    public function deleteReturn() {
        if (isset($this->request->post['return_selected'])) {
            $this->load->model('pos/pos');
            // selected orders to be deleted
            foreach ($this->request->post['return_selected'] as $pos_return_id) {
                $this->model_pos_pos->deleteReturn($pos_return_id);
            }
            $this->getReturnList();
        }
    }

    private function getPagination($total, $page, $limit, $function) {
        $num_links = 10;
        $num_pages = ceil($total / $limit);
        $text = $this->language->get('text_pagination');

        $output = '<ul class="pos-pager">';
        if ($page > 1) {
            $output .= '<li><a onclick="' . $function . '(1);" class="arrow first"></a></li><li><a onclick="' . $function . '(' . ($page - 1) . ');" class="arrow prev"></a></li>';
        }

        if ($num_pages > 1) {
            if ($num_pages <= $num_links) {
                $start = 1;
                $end = $num_pages;
            } else {
                $start = $page - floor($num_links / 2);
                $end = $page + floor($num_links / 2);

                if ($start < 1) {
                    $end += abs($start) + 1;
                    $start = 1;
                }

                if ($end > $num_pages) {
                    $start -= ($end - $num_pages);
                    $end = $num_pages;
                }
            }

            for ($i = $start; $i <= $end; $i++) {
                if ($page == $i) {
                    $output .= '<li><a class="active">' . $i . '</a></li>';
                } else {
                    $output .= '<li><a onclick="' . $function . '(' . $i . ');">' . $i . '</a></li>';
                }
            }
        }

        if ($page < $num_pages) {
            $output .= '<li><a onclick="' . $function . '(' . ($page + 1) . ');" class="arrow next"></a></li><li><a onclick="' . $function . '(' . $num_pages . ');" class="arrow last"></a></li>';
        }

        $output .= '</ul>';

        $text = sprintf($text, ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, $num_pages);

        return '<span class="count">' . $text . '</span>' . $output;
    }

    private function getOrderIdText($order_id) {
        $order_id_text = '' . $order_id;
        $order_id_len = strlen($order_id_text);
        if ($order_id_len < 7) {
            for ($i = 0; $i < 7 - $order_id_len; $i++) {
                $order_id_text = '0' . $order_id_text;
            }
        }
        return $order_id_text;
    }

    private function getOrderProducts($order_id, &$data) {
        // unset the shipping method before load it again
        unset($this->session->data['shipping_method']);

        $order_info = $this->model_pos_pos->getOrder($order_id);
        if (!$order_info) {
            $data['order_error'] = sprintf($this->language->get('order_error'), $order_id);
            return;
        }

        if ($order_info['shipping_zone_id'] != 3624) {
            $this->session->data['outsite_california'] = true;
        } else {
            $this->session->data['outsite_california'] = false;
        }

        // for notification purposes
        $this->model_pos_pos->unsetOnlineOrderIndicator($order_id);

        // add for Quotation begin
        $order_query = $this->db->query("SELECT quote_status_id FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int) $order_id . "'");
        if ($order_query->row && $order_query->row['quote_status_id'] > 0) {
            $data['quote_status_id'] = $order_query->row['quote_status_id'];
            $data['quote_status'] = $this->model_pos_pos->getQuoteStatus($order_query->row['quote_status_id']);
        } else {
            $data['quote_status_id'] = 1;
            $data['quote_status'] = $this->model_pos_pos->getQuoteStatus(1);
        }
        // add for Quotation end

        if (empty($this->request->get['ajax'])) {
            // add for Browse begin
            $data['browse_items'] = $this->getCategoryItems(0, $order_info['currency_code'], $order_info['currency_value'], $order_info['customer_group_id']);
            // add for Brose end
        }

        $data['order_id'] = $order_id;
        $data['order_id_text'] = $this->getOrderIdText($order_id);

        $data['store_id'] = $order_info['store_id'];
        $data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'] . '&order_id=' . (int) $order_id, 'SSL');

        if ($order_info['invoice_no']) {
            $data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
        } else {
            $data['invoice_no'] = '';
        }

        $data['store_name'] = $order_info['store_name'];
        $data['store_url'] = $order_info['store_url'];
        $data['firstname'] = $order_info['firstname'];
        $data['lastname'] = $order_info['lastname'];

        if ($order_info['customer_id'] > 0) {
            $data['customer'] = $order_info['customer'];
            $data['customer_id'] = $order_info['customer_id'];
        } else {
            $data['customer'] = $order_info['firstname'] . ' ' . $order_info['lastname'];
            $data['customer_id'] = 0;
        }

        $this->load->model('pos/pos');

        $customer_group_info = $this->model_pos_pos->getCustomerGroup($order_info['customer_group_id']);
        $data['customer_groups'] = $this->model_pos_pos->getCustomerGroups();
        $data['customer_group_id'] = $order_info['customer_group_id'];

        if ($customer_group_info) {
            $data['customer_group'] = $customer_group_info['name'];
        } else {
            $data['customer_group'] = '';
        }

        $data['email'] = $order_info['email'];
        $data['telephone'] = $order_info['telephone'];
        $data['fax'] = $order_info['fax'];
        $data['comment'] = $order_info['comment'];
        $data['total'] = $this->currency->formatFront($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);

        if ($order_info['total'] < 0) {
            $data['credit'] = $order_info['total'];
        } else {
            $data['credit'] = 0;
        }
        // add for table management begin
        $data['order_table_id'] = $this->model_pos_pos->getOrderTableId($order_id);
        // add for table management end
        // use my sql default format yyyy-mm-dd hh:mm:ss across all the module
        // except the invoice, receipt and notifications, which are customer facing presentation
        // all pos user will use the standard date time format
        $data['date_added'] = $order_info['date_added'];
        $data['date_modified'] = $order_info['date_modified'];
        $data['payment_firstname'] = $order_info['payment_firstname'];
        $data['payment_lastname'] = $order_info['payment_lastname'];
        $data['payment_company'] = $order_info['payment_company'];
        $data['payment_address_1'] = $order_info['payment_address_1'];
        $data['payment_address_2'] = $order_info['payment_address_2'];
        $data['payment_city'] = $order_info['payment_city'];
        $data['payment_postcode'] = $order_info['payment_postcode'];
        $data['payment_zone'] = $order_info['payment_zone'];
        $data['payment_zone_code'] = $order_info['payment_zone_code'];
        $data['payment_country'] = $order_info['payment_country'];
        $data['payment_country_id'] = $order_info['payment_country_id'];
        $data['payment_zone_id'] = $order_info['payment_zone_id'];
        $data['shipping_firstname'] = $order_info['shipping_firstname'];
        $data['shipping_lastname'] = $order_info['shipping_lastname'];
        $data['shipping_company'] = $order_info['shipping_company'];
        $data['shipping_address_1'] = $order_info['shipping_address_1'];
        $data['shipping_address_2'] = $order_info['shipping_address_2'];
        $data['shipping_city'] = $order_info['shipping_city'];
        $data['shipping_postcode'] = $order_info['shipping_postcode'];
        $data['shipping_zone'] = $order_info['shipping_zone'];
        $data['shipping_zone_code'] = $order_info['shipping_zone_code'];
        $data['shipping_country'] = $order_info['shipping_country'];
        $data['shipping_country_id'] = $order_info['shipping_country_id'];
        $data['shipping_zone_id'] = $order_info['shipping_zone_id'];
        $data['shipping_code'] = $order_info['shipping_code'];
        $data['shipping_method'] = $order_info['shipping_method'];
        $data['payment_method'] = $order_info['payment_method'];
        $data['payment_code'] = $order_info['payment_code'];

        $this->getCustomer($order_info['customer_id'], $data);

        $zones = array();
        $this->load->model('localisation/country');
        $this->load->model('localisation/zone');
        $this->load->model('pos/product');
        $country_info = $this->model_localisation_country->getCountry($order_info['shipping_country_id']);
        if ($country_info) {
            $zones[$order_info['shipping_country_id']] = $this->model_localisation_zone->getZonesByCountryId($order_info['shipping_country_id']);
        }
        if ($order_info['payment_country_id'] != $order_info['shipping_country_id']) {
            $country_info = $this->model_localisation_country->getCountry($order_info['payment_country_id']);
            if ($country_info) {
                $zones[$order_info['payment_country_id']] = $this->model_localisation_zone->getZonesByCountryId($order_info['payment_country_id']);
            }
        }
        $data['zones'] = $zones;
        $data['customer_shipping_zones'] = $this->model_localisation_zone->getZonesByCountryId($data['shipping_country_id']);

        $data['products'] = array();

        $products = $this->model_pos_pos->getOrderProducts($order_id);

        $this->load->model('tool/image');

        $items_in_cart = 0;
        foreach ($products as $product) {
            $option_data = array();

            $options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);
            // add for serial no begin
            $sns = $this->model_pos_pos->getSoldProductSN($product['order_product_id']);
            // add for serial no end
            $product_total = $product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity'] * $product['weight']) : 0);

            // add for product based discount begin
            $discount_type = 0;
            $discount_value = 0;
            $text_discount = '';
            $text_before_discount = $this->currency->formatFront($product_total, $order_info['currency_code'], $order_info['currency_value']);
            $text_after_discount = $this->currency->formatFront($product_total, $order_info['currency_code'], $order_info['currency_value']);
            if (!empty($product['discount'])) {
                $discount_type = $product['discount']['discount_type'];
                $discount_value = $product['discount']['discount_value'];
                if ($discount_type == 1) {
                    if ($this->config->get('config_tax') && !$product['discount']['include_tax']) {
                        $discount_value += ($product['tax'] - $product['discount']['discounted_tax']) * $product['quantity'];
                    } else if (!$this->config->get('config_tax') && $product['discount']['include_tax']) {
                        $discount_value = $product['total'] - $product['discount']['discounted_total'];
                    }
                    $text_discount = $this->currency->formatFront($discount_value, $order_info['currency_code'], $order_info['currency_value']);
                    //$text_before_discount = $this->currency->formatFront($product_total+$discount_value, $order_info['currency_code'], $order_info['currency_value']);
                    $text_before_discount = $this->currency->formatFront($product_total, $order_info['currency_code'], $order_info['currency_value']);
                    $text_after_discount = $this->currency->formatFront($product_total - $discount_value, $order_info['currency_code'], $order_info['currency_value']);
                } elseif ($discount_type == 2) {
                    $text_discount = $discount_value . '%';
                    /* $text_before_discount = $this->currency->formatFront($product_total * 100 / (100 - $discount_value), $order_info['currency_code'], $order_info['currency_value']); */
                    $text_before_discount = $this->currency->formatFront($product_total, $order_info['currency_code'], $order_info['currency_value']);
                    $text_after_discount = $this->currency->formatFront($product_total - ( $product_total * ($discount_value / 100)), $order_info['currency_code'], $order_info['currency_value']);
                }
            }
            // add for product based discount end
            // add for product return begin
            $product_return = 0;
            $product_return_quantity = 0;
            $product_return_comment = '';
            if (!empty($order_product_returns)) {
                foreach ($order_product_returns as $order_product_return) {
                    if ($product['order_product_id'] == $order_product_return['order_product_id']) {
                        $product_return = 1;
                        $product_return_quantity += $order_product_return['quantity'];
                        $product_return_comment .= $order_product_return['comment'] . "\n";
                    }
                }
            }
            // add for product return end

            $product_total_text = $this->currency->formatFront($product_total, $order_info['currency_code'], $order_info['currency_value']);
            $normal_price = $this->model_pos_pos->getProductNormalPrice($product['product_id']);
            $product_unit_conversion_values = !empty($product['unit_conversion_values']) ? $this->model_pos_pos->getUnitConvertedValue($product['product_id'], $product['unit_conversion_values']) : 1;
            if (isset($this->session->data['discount_customer_group_id'])) {
                $discount_customer_group_id = $this->session->data['discount_customer_group_id'];
            } else {
                $discount_customer_group_id = $order_info['customer_group_id'];
            }
            $discounted_price_for_percent = $this->model_pos_pos->getProductDiscountedPrice($product['product_id'], $product['unit_conversion_values'], $discount_customer_group_id, $product['quantity'], $normal_price);
            $text_discounted_price_for_percent = $discounted_price_for_percent > 0 ? $this->currency->formatFront($discounted_price_for_percent) : $this->currency->formatFront($product['price']);

            // Unit conversion - S
            if (!empty($product['unit_conversion_values']) || $product['unit_conversion_values'] != 0) {
                $sql_unit_sql = "SELECT unit_id, unit_value_id, convert_price FROM " . DB_PREFIX . "unit_conversion_product WHERE unit_conversion_product_id = '" . $product['unit_conversion_values'] . "' AND product_id = '" . $product['product_id'] . "'";
                $res_unit_sql = $this->db->query($sql_unit_sql);
                if ($res_unit_sql->num_rows > 0) {
                    $unit_id = $res_unit_sql->row['unit_id'];
                    $unit_value_id = $res_unit_sql->row['unit_value_id'];
                    $convert_price = $res_unit_sql->row['convert_price'];

                    $sql_unit_name = "SELECT name FROM " . DB_PREFIX . "unit_conversion WHERE unit_id = $unit_id";
                    $res_unit_name = $this->db->query($sql_unit_name);
                    $unit_name = $res_unit_name->row['name'];

                    $sql_unit_value_name = "SELECT name FROM " . DB_PREFIX . "unit_conversion_value WHERE unit_value_id = $unit_value_id";
                    $res_unit_value_name = $this->db->query($sql_unit_value_name);
                    $unit_value_name = $res_unit_value_name->row['name'];

                    $unit_data = array(
                        'unit_conversion_values' => $product['unit_conversion_values'],
                        'unit_name' => $unit_name,
                        'unit_value_name' => $unit_value_name,
                        'convert_price' => $convert_price
                    );
                }
            }
            //print_r($unit_data);
            $unit_dates_default = $this->model_pos_product->getDefaultUnitDetails($product['product_id']);
            $DefaultUnitName = $this->model_pos_product->getDefaultUnitName($product['product_id']);

            if (isset($unit_data) && !empty($unit_data)) {
                $json['convert_price'] = $unit_data['convert_price'];
            }

            $converstion_line = '';
            if (isset($json['convert_price']) && $json['convert_price'] != 1 && !empty($unit_dates_default)) {
                $converstion_line = '-' . number_format(($json['convert_price'] * $product['quantity']), 2) . ' ' . $unit_dates_default['name'] . ' = ' . $product['quantity'] . ' ' . $unit_data['unit_value_name'] . '<br />';
            }
            // Unit Conversion - E

            $data['products'][] = array(
                'order_product_id' => $product['order_product_id'],
                'product_id' => $product['product_id'],
                'name' => html_entity_decode($product['name']),
                'model' => $product['model'],
                'image' => (!empty($product['image'])) ? $this->model_tool_image->resize($product['image'], 180, 180) : $this->model_tool_image->resize('no_image.jpg', 180, 180),
                'option' => $options,
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'normal_price' => $normal_price,
                'unit' => $DefaultUnitName,
                'converstion_line' => $converstion_line,
                'discounted_price_for_percent' => $discounted_price_for_percent > 0 ? $discounted_price_for_percent : $product['price'],
                'text_discounted_price_for_percent' => $text_discounted_price_for_percent,
                'subtract' => $product['subtract'],
                'price_text' => $this->currency->formatFront($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                'normal_price_text' => $this->currency->formatFront($normal_price + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                'normal_price_total_text' => $this->currency->formatFront(($normal_price * $product['quantity'] * $product_unit_conversion_values) + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                'total' => $product_total,
                'total_text' => $product_total_text,
                // add for serail no begin
                'sns' => $sns,
                // add for serial no end
                // add for weight based price begin
                'weight' => $product['weight'],
                'weight_price' => $product['weight_price'],
                'weight_name' => $product['weight_name'],
                'weight_class_id' => $product['weight_class_id'],
                'length' => $product['length'],
                'width' => $product['width'],
                'height' => $product['height'],
                // add for weight based price end
                // add for product based discount begin
                'discount_type' => $discount_type,
                'discount_value' => $discount_value,
                'text_discount' => $text_discount,
                'text_before_discount' => $text_before_discount,
                'text_after_discount' => $text_after_discount,
                'unique_option_price' => $product['unique_option_price'],
                'labour_cost' => $product['labour_cost'],
                // add for product based discount end
                // add for product return begin
                'return' => $product_return,
                'return_quantity' => $product_return_quantity,
                'return_comment' => $product_return_comment,
                // add for product return end
                'tax' => $product['tax'],
                'tax_class_id' => $product['tax_class_id'],
                'shipping' => $product['shipping'],
                'reward' => $product['reward'],
                'href' => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL'),
                'selected' => isset($this->request->post['selected']) && in_array($product['product_id'], $this->request->post['selected']),
                'convert_price_raw' => $product['price'] / $product_unit_conversion_values,
                'convert_price' => $this->currency->formatFront($product['price'] / $product_unit_conversion_values)
            );

            $items_in_cart += $product['quantity'];
        }
        $data['items_in_cart'] = $items_in_cart;
        $data['currency_code'] = $order_info['currency_code'];
        $data['currency_value'] = $order_info['currency_value'];
        $data['currency_symbol'] = $this->currency->getSymbolLeft($order_info['currency_code']);
        if ($data['currency_symbol'] == '') {
            $data['currency_symbol'] = $this->currency->getSymbolRight($order_info['currency_code']);
        }

        $data['totals'] = $this->model_sale_order->getOrderTotals($order_id);
        foreach ($data['totals'] as $key => $total) {
            $data['totals'][$key]['text'] = $this->currency->formatFront($total['value']);
            if ($data['totals'][$key]['code'] == 'total') {
                $data['order_total'] = $this->currency->formatFront($total['value']);
            }
        }
        if (!isset($data['order_total'])) {
            $data['order_total'] = $this->currency->formatFront(0);
        }
        $data['order_paid'] = $this->currency->formatFront($this->model_pos_pos->getOrderPaidAmount($order_id));
        $data['order_change_amount'] = $this->currency->formatFront($this->model_pos_pos->getOrderChangeAmount($order_id));
        // add for Discount begin
        // set default values for discount
        $data['discount_type'] = 'amount';
        $data['discount_value'] = 0;
        foreach ($data['totals'] as $order_total_data) {
            if ($order_total_data['code'] == 'pos_discount_fixed' || $order_total_data['code'] == 'pos_discount_percentage') {
                // load discount info and set the value into session
                $discount = array(
                    'order_id' => $order_id,
                    'code' => $order_total_data['code'],
                    'title' => $order_total_data['title'],
                    'value' => $order_total_data['value']
                );
                $this->session->data['pos_discount'] = $discount;
                $data['discount_type'] = 'amount';
                if ($order_total_data['code'] == 'pos_discount_percentage') {
                    $data['discount_type'] = 'percentage';
                }
                $data['discount_value'] = $order_total_data['value'];
                if ($order_total_data['code'] == 'pos_discount_percentage') {
                    $index1 = strpos($order_total_data['title'], '(');
                    $index2 = strpos($order_total_data['title'], ')');
                    if ($index1 !== false && $index2 !== false && $index2 > $index1 + 2) {
                        $data['discount_value'] = substr($order_total_data['title'], $index1 + 1, $index2 - $index1 - 3);
                    }
                }
            }
        }
        // add for Discount end
        // If no total for the current order, use an empty total
        if (empty($data['totals'])) {
            $data['totals'] = array(
                array('code' => 'total',
                    'title' => $this->language->get('text_pos_total'),
                    'text' => $this->currency->formatFront(0),
                    'value' => 0,
                    'sort_order' => $this->config->get('total_sort_order'))
            );
        }
        // instead of using the last object in the array, use the total code
        $totalPaymentAmount = 0;
        foreach ($data['totals'] as $order_total_data) {
            if ($order_total_data['code'] == 'total') {
                $totalPaymentAmount = $order_total_data['value'];
                if ($order_info['currency_value'])
                    $totalPaymentAmount = (float) $totalPaymentAmount * $order_info['currency_value'];
                break;
            }
        }

        $totalPaid = 0;
        $data['order_payments'] = array();
        $order_payments = $this->model_pos_pos->retrieveOrderPayments($order_id);
        if ($order_payments) {
            // reverse the order
            $order_payments = array_reverse($order_payments);
            foreach ($order_payments as $order_payment) {
                // update for customer loyalty card begin
                $amount = $order_payment['tendered_amount'];
                if ($order_payment['payment_type'] == $this->language->get('text_reward_points') && strpos($order_payment['payment_note'], ',') !== false) {
                    // it's the opencart reward point payment
                    $parts = explode('|', $order_payment['payment_note']);
                    if ($parts) {
                        // deduct the reward points from the current order if the reward points are used to pay the order product as we already set the reward points in the earlier stage
                        $total_value = 0;
                        foreach ($parts as $part) {
                            if (!empty($part)) {
                                $reward_details = explode(',', $part);
                                if ($reward_details) {
                                    $order_product_id = $reward_details[0];
                                    $quantity = $reward_details[1];

                                    $order_product_query = $this->db->query("SELECT price, tax FROM `" . DB_PREFIX . "order_product` WHERE order_product_id = '" . (int) $order_product_id . "'");
                                    $total_value += (int) $quantity * ((float) $order_product_query->row['price'] + (float) $order_product_query->row['tax']);
                                }
                            }
                        }

                        $amount = $total_value;
                    }
                }

                $totalPaid += $amount;
                $data['order_payments'][] = array(
                    'order_payment_id' => $order_payment['order_payment_id'],
                    'payment_type' => $order_payment['payment_type'],
                    'tendered_amount' => $amount,
                    'payment_note' => ($order_payment['payment_type'] == $this->language->get('text_reward_points')) ? '' : $order_payment['payment_note']
                );
            }
        }

        $data['payment_due_amount'] = $totalPaymentAmount - $totalPaid;
        $data['payment_change'] = 0;
        if ($data['payment_due_amount'] < 0) {
            $data['payment_change'] = 0 - $data['payment_due_amount'];
            $data['payment_due_amount'] = 0;
        }
        $data['payment_due_amount_text'] = $this->currency->formatFront($data['payment_due_amount'], $order_info['currency_code'], 1);
        $data['payment_change_text'] = $this->currency->formatFront($data['payment_change'], $order_info['currency_code'], 1);

        $data['order_status_id'] = $order_info['order_status_id'];
        $data['user_id'] = $order_info['user_id'];
        // add for order notification begin
        if (!empty($this->request->get['ajax']) && empty($data['user_id'])) {
            // when select the order from UI and the current order has no user id associated (online order), set online order as read
            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET user_id = '" . (-2 - (int) $this->user->getId()) . "' WHERE order_id = '" . $order_id . "'");
            $data['user_id'] = -2 - (int) $this->user->getId();
        }
        // add for order notification end
        $data['text_can_not_delete_current_order'] = sprintf($this->language->get('text_can_not_delete_current_order'), $order_id);
    }

    private function viewtotalForPOS($data = array()) {

        //print_r($data);
        $implodeInner = '';

        $sql = "SELECT CONCAT(c.firstname,' ', c.lastname) AS name,wro.id,wro.admin_return,wro.order_id,wro.return_pos,wro.add_info,wro.rma_auth_no,wro.date,wrs.color,wrs.name as admin_status,wro.admin_status as rma_status,wro.cancel_rma,wro.solve_rma FROM " . DB_PREFIX . "wk_rma_order wro LEFT JOIN " . DB_PREFIX . "wk_rma_customer wrc ON (wro.id = wrc.rma_id) LEFT JOIN `" . DB_PREFIX . "order` c ON ((wrc.customer_id = c.customer_id || wrc.email = c.email ) AND c.order_id = wro.order_id) LEFT JOIN " . DB_PREFIX . "wk_rma_status wrs ON (wro.admin_status = wrs.status_id) WHERE 1";

        $implode = array();

        if (isset($data['filter_pos']) && !is_null($data['filter_pos'])) {
            $implode[] = "wro.return_pos = '" . (int) $data['filter_pos'] . "'";
        }


        if (isset($data['filter_admin_status']) && !is_null($data['filter_admin_status'])) {
            if ($data['filter_admin_status'] == 'admin') {
                $implode[] = "wro.admin_return = 1";
            } else if ($data['filter_admin_status'] == 'solve') {
                $implode[] = "wro.solve_rma = 1";
            } else if ($data['filter_admin_status'] == 'cancel') {
                $implode[] = "wro.cancel_rma = 1";
            } else {
                $implode[] = " wrs.id = '" . (int) $data['filter_admin_status'] . "' AND admin_return <> 1 AND solve_rma <> 1 AND cancel_rma <> 1";
            }
        }

        if (!empty($data['filter_date'])) {
            $implode[] = "LCASE(wro.date) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_date'])) . "%'";
        }

        if ($implode) {
            $sql .= " AND " . implode(" AND ", $implode);
        }

        $sort_data = array(
            'c.firstname',
            'c.order_id',
            'wro.order_id',
            'wrr.id',
            'wro.date',
        );


        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY wro.order_id";
        }

        if (isset($data['order']) && ($data['order'] == 'ASC')) {
            $sql .= " ASC";
        } else {
            $sql .= " DESC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }
        //die($sql);

        $result = $this->db->query($sql);
        return $result->rows;
    }

    public function viewProducts($id) {
        $sql = "SELECT pd.name,wrp.quantity,wrp.order_product_id,wrr.reason,p.model,op.product_id,op.quantity `quantity_total`,op.quantity_supplied `quantity_shipped`,op.price,op.order_id, IFNULL(rf.refund_amount,0) `refund_amount` FROM " . DB_PREFIX . "product_description pd LEFT JOIN " . DB_PREFIX . "wk_rma_product wrp ON (wrp.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "wk_rma_reason wrr ON (wrp.reason = wrr.reason_id) LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "order_product op ON (op.order_product_id = wrp.order_product_id) LEFT JOIN " . DB_PREFIX . "return_refund rf ON (op.product_id = rf.product_id) WHERE wrp.rma_id = '" . (int) $id . "' AND pd.language_id = '" . $this->config->get('config_language_id') . "' AND wrr.language_id = '" . $this->config->get('config_language_id') . "'";
        return $this->db->query($sql)->rows;
        //die($sql);
    }

    public function getOrderStat($status_id) {
        $sql = "SELECT name FROM " . DB_PREFIX . "wk_rma_status WHERE status_id = $status_id";
        $result = $this->db->query($sql);
        if ($result) {
            return $result->rows;
        }
    }

    private function getReturnProducts($pos_return_id, $order_id, &$data) {
        $return_info = $this->model_pos_pos->getReturn($pos_return_id);
        if (!$return_info) {
            return;
        }

        $data['pos_return_id'] = $pos_return_id;
        $data['order_id'] = $order_id;
        $data['text_pos_return_id'] = $this->getOrderIdText($pos_return_id);
        $data['firstname'] = $return_info['firstname'];
        $data['lastname'] = $return_info['lastname'];

        if ($return_info['customer_id'] > 0) {
            $data['customer'] = $return_info['customer'];
            $data['customer_id'] = $return_info['customer_id'];
        } else {
            $data['customer'] = $return_info['firstname'] . ' ' . $return_info['lastname'];
            $data['customer_id'] = 0;
        }

        $data['email'] = $return_info['email'];
        $data['telephone'] = $return_info['telephone'];
        $data['return_tax'] = $return_info['tax'];
        $data['return_sub_total'] = $return_info['sub_total'];
        $data['return_total'] = $return_info['tax'] + $return_info['sub_total'];

        $this->load->model('localisation/return_status');
        $this->load->model('tool/image');

        $return_status_info = $this->model_localisation_return_status->getReturnStatus($return_info['return_status_id']);

        if ($return_status_info) {
            $data['return_status'] = $return_status_info['name'];
        } else {
            $data['return_status'] = '';
        }
        $data['return_status_id'] = $return_info['return_status_id'];
        $data['return_action_id'] = 0;

        $data['return_date_added'] = $return_info['date_added'];
        $data['return_date_modified'] = $return_info['date_modified'];

        $this->getCustomer($return_info['customer_id'], $data);

        $data['return_products'] = array();

        $products = $this->model_pos_pos->getReturnProducts($pos_return_id);

        $items_in_cart = 0;
        if (!empty($products)) {
            foreach ($products as $product) {
                $data['return_products'][] = array(
                    'order_id' => $product['order_id'],
                    'return_id' => $product['return_id'],
                    'order_product_id' => $product['order_product_id'],
                    'product_id' => $product['product_id'],
                    'name' => html_entity_decode($product['product']),
                    'model' => $product['model'],
                    'image' => (!empty($product['image'])) ? $this->model_tool_image->resize($product['image'], 180, 180) : $this->model_tool_image->resize('no_image.jpg', 180, 180),
                    'option' => $product['option'],
                    'quantity' => $product['quantity'],
                    'stock' => $product['quantity'],
                    'price' => $product['price'],
                    'tax' => $product['tax'],
                    'price_text' => $this->currency->formatFront($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0)),
                    'total' => $product['quantity'] * $product['weight'] * ($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0)),
                    'total_text' => $this->currency->formatFront($product['quantity'] * $product['weight'] * ($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0))),
                    // add for serail no begin
                    'sns' => array($product['sn']),
                    // add for serial no end
                    // add for weight based price begin
                    'weight' => $product['weight'],
                    'weight_price' => (!empty($product['weight_name'])) ? 1 : 0,
                    'weight_name' => $product['weight_name'],
                        // add for weight based price end
                );
                $data['return_action_id'] = $product['return_action_id'];

                $items_in_cart += $product['quantity'];
            }
        }
        $data['items_in_cart'] = $items_in_cart;

        $data['totals'] = array(array('code' => 'tax', 'title' => $this->language->get('text_return_tax'), 'value' => $data['return_tax'], 'sort_order' => 1),
            array('code' => 'subtotal', 'title' => $this->language->get('text_return_sub_total'), 'value' => $data['return_sub_total'], 'sort_order' => 2),
            array('code' => 'total', 'title' => $this->language->get('text_return_total'), 'value' => $data['return_total'], 'sort_order' => 3));

        $totalPaid = 0;
        $return_payments = $this->model_pos_pos->retrieveReturnPayments($pos_return_id);
        if ($return_payments) {
            // reverse the order
            $return_payments = array_reverse($return_payments);
            foreach ($return_payments as $return_payment) {
//				if ($order_payment['tendered_amount'] > 0) {
                $totalPaid += $return_payment['tendered_amount'];
                $data['return_payments'][] = array(
                    'order_payment_id' => $return_payment['order_payment_id'],
                    'payment_type' => $return_payment['payment_type'],
                    'tendered_amount' => $this->currency->formatFront($return_payment['tendered_amount']),
                    'payment_note' => $return_payment['payment_note']
                );
//				}
            }
        }

        $data['payment_due_amount'] = $return_info['tax'] + $return_info['sub_total'] - $totalPaid;
        $data['payment_change'] = 0;
        $data['payment_due_amount_text'] = $this->currency->formatFront($data['payment_due_amount']);
        $data['payment_change_text'] = $this->currency->formatFront($data['payment_change']);

        if ($order_id) {
            $data['text_return_for_order'] = sprintf($this->language->get('text_return_for_order'), $order_id);
            $data['browseItems'] = array();
            $results = $this->model_pos_pos->getBrowseProductsForReturn($order_id);
            $this->load->model('tool/image');

            foreach ($results as $result) {
                $data['browseItems'][] = array('type' => 'P',
                    'name' => $result['name'],
                    'order_id' => $order_id,
                    'date_ordered' => $result['date_ordered'],
                    'order_product_id' => $result['order_product_id'],
                    'product_id' => $result['product_id'],
                    'image' => !empty($result['image']) ? $this->model_tool_image->resize($result['image'], 180, 180) : $this->model_tool_image->resize('no_image.jpg', 180, 180),
                    'price_text' => $this->currency->formatFront($result['price'] + $result['tax']),
                    'stock' => $result['quantity'],
                    'hasOptions' => '0', // options are all selected as they are order products, no need to reselect from the ui
                    // add for (update) Weight based price begin
                    'weight_price' => $result['weight_price'],
                    'weight_name' => $result['weight_name'],
                    'weight' => $result['weight'],
                    // add for (update) Weight based price end
                    'has_sn' => '0', // sn is fixed as they are order products, no need to reselect from the ui
                    'sn' => $result['sn'],
                    'price' => $result['price'],
                    'tax' => $result['tax'],
                    'tax_class_id' => $result['tax_class_id'],
                    'shipping' => $result['shipping'],
                    'points' => 0,
                    'model' => $result['model'],
                    'option' => $result['option'],
                    'quantity' => $result['quantity'],
                    'id' => $result['product_id']);
            }

            $existing_returns = $this->model_pos_pos->getExistingReturns($order_id);
            if (!empty($existing_returns)) {
                $data['existing_returns'] = $existing_returns;
                foreach ($data['existing_returns'] as $key => $existing_return) {
                    $data['existing_returns'][$key]['date_modified'] = strtotime($existing_return['date_modified']);
                }
            }
        }
    }

    public function getProductDetails() {
        $json = array();

        $product_id = $this->request->get['product_id'];

        if (!empty($this->request->get['product_id'])) {
            $product_id = $this->request->get['product_id'];
            $this->load->model('catalog/product');

            $product_info = $this->model_catalog_product->getProduct($product_id);
            if (!empty($product_info)) {
                $json = $product_info;

                $this->load->model('tool/image');
                if ($product_info['image'] && file_exists(DIR_IMAGE . $product_info['image'])) {
                    $json['thumb'] = $this->model_tool_image->resize($product_info['image'], 300, 300);
                } else {
                    $json['thumb'] = $this->model_tool_image->resize('no_image.jpg', 300, 300);
                }

                $this->load->model('catalog/manufacturer');
                $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);
                if ($manufacturer_info) {
                    $json['manufacturer'] = $manufacturer_info['name'];
                } else {
                    $json['manufacturer'] = '';
                }

                // Options
                $this->load->model('catalog/option');
                $product_options = $this->model_catalog_product->getProductOptions($product_id);

                $json['product_options'] = array();
                foreach ($product_options as $product_option) {
                    if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
                        $product_option_value_data = array();

                        foreach ($product_option['product_option_value'] as $product_option_value) {
                            $product_option_value_data[] = array(
                                'product_option_value_id' => $product_option_value['product_option_value_id'],
                                'option_value_id' => $product_option_value['option_value_id'],
                                'quantity' => $product_option_value['quantity'],
                                'subtract' => $product_option_value['subtract'],
                                'price' => $product_option_value['price'],
                                'price_prefix' => $product_option_value['price_prefix'],
                                'points' => $product_option_value['points'],
                                'points_prefix' => $product_option_value['points_prefix'],
                                'weight' => $product_option_value['weight'],
                                'weight_prefix' => $product_option_value['weight_prefix']
                            );
                        }

                        $json['product_options'][] = array(
                            'product_option_id' => $product_option['product_option_id'],
                            'product_option_value' => $product_option_value_data,
                            'option_id' => $product_option['option_id'],
                            'name' => $product_option['name'],
                            'type' => $product_option['type'],
                            'required' => $product_option['required']
                        );
                    } elseif ($product_option['type'] != 'file') {
                        $json['product_options'][] = array(
                            'product_option_id' => $product_option['product_option_id'],
                            'option_id' => $product_option['option_id'],
                            'name' => $product_option['name'],
                            'type' => $product_option['type'],
                            'option_value' => $product_option['value'],
                            'required' => $product_option['required']
                        );
                    }
                }
                $json['option_values'] = array();
                foreach ($json['product_options'] as $product_option) {
                    if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
                        if (!isset($json['option_values'][$product_option['option_id']])) {
                            $json['option_values'][$product_option['option_id']] = $this->model_catalog_option->getOptionValues($product_option['option_id']);
                        }
                    }
                }
                $json['product_attributes'] = $this->model_catalog_product->getProductAttributesData($product_id);
                $json['product_discounts'] = $this->model_catalog_product->getProductDiscounts($product_id);
                $json['product_specials'] = $this->model_catalog_product->getProductSpecials($product_id);
                $json['product_reward'] = $this->model_catalog_product->getProductRewards($product_id);
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function getCustomerAjax() {
        $data = array();
        $this->getCustomer($this->request->get['customer_id'], $data);
        $this->response->setOutput(json_encode($data));
    }

    private function getCustomer($customer_id, &$data) {
        $data['customer_id'] = $customer_id;

        $this->load->model('pos/pos');
        $customer_info = $this->model_pos_pos->getCustomer($customer_id);

        if (!empty($customer_info)) {
            // add for customer loyalty card begin
            $data['customer_card_number'] = $customer_info['card_number'];
            $data['customer_resale_number'] = $customer_info['resale_number'];
            $data['customer_notes'] = $customer_info['notes'];
            // add for customer loyalty card end
            $data['customer_firstname'] = $customer_info['firstname'];
            $data['customer_lastname'] = $customer_info['lastname'];
            $data['customer_email'] = $customer_info['email'];
            $data['customer_telephone'] = $customer_info['telephone'];
            $data['customer_fax'] = $customer_info['fax'];
            $data['customer_newsletter'] = $customer_info['newsletter'];
            $data['customer_group_id'] = $customer_info['customer_group_id'];
            $data['customer_status'] = $customer_info['status'];
            // Raza start
            if (isset($customer_id)) {
                $cust_info = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_notes WHERE customer_id='" . $customer_id . "' ")->row;
            }

            if (isset($this->request->post['customer_image'])) {
                $data['customer_image'] = $this->request->post['customer_image'];
            } elseif (!empty($cust_info)) {
                $data['customer_image'] = $cust_info['customer_image'];
            } else {
                $data['customer_image'] = '';
            }

            $this->load->model('tool/image');

            if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['customer_image'])) {
                $data['thumb'] = $this->model_tool_image->resize($this->request->post['customer_image'], 100, 100);
            } elseif (!empty($cust_info) && is_file(DIR_IMAGE . $cust_info['customer_image'])) {
                $data['thumb'] = $this->model_tool_image->resize($cust_info['customer_image'], 100, 100);
            } else {
                $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
            }
            if (isset($this->request->post['customer_sales_notes'])) {
                $data['customer_sales_notes'] = $this->request->post['customer_sales_notes'];
            } elseif (!empty($cust_info)) {
                $data['customer_sales_notes'] = $cust_info['customer_notes'];
            } else {
                $data['customer_sales_notes'] = "";
            }

            $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

            // Raza
            $data['customer_addresses'] = $this->model_pos_pos->getAddresses($customer_id);
            $data['customer_address_id'] = $customer_info['address_id'];
            $data['customer_date_added'] = date('Y-m-d', strtotime($customer_info['date_added']));
            $data['hasAddress'] = 1;
            $this->load->model('localisation/zone');
            foreach ($data['customer_addresses'] as $key => $address) {
                if ($customer_info['address_id'] == $address['address_id']) {
                    $data['hasAddress'] = 2;
                }
                $data['customer_addresses'][$key]['zones'] = $this->model_localisation_zone->getZonesByCountryId($address['country_id']);
            }
            $data['customer_password'] = '';
            $data['customer_confirm'] = '';
        }
    }

    private function getStoreId() {
        if (isset($this->request->get['store_id'])) {
            $store_id = $this->request->get['store_id'];
        } else {
            $url_with_port = $this->request->server['SERVER_NAME'] . ':' . $this->request->server['SERVER_PORT'] . $this->request->server['PHP_SELF'];
            $url_without_port = $this->request->server['SERVER_NAME'] . $this->request->server['PHP_SELF'];
            $store_id = 0;
            // get the default store id
            $this->load->model('setting/store');
            $stores = $this->model_setting_store->getStores();
            if (!empty($stores)) {
                foreach ($stores as $store) {
                    $store_url = $store['url'];
                    $index = strpos($store['url'], '//');
                    if (!($index === false)) {
                        $store_url = substr($store_url, $index + 2);
                    }
                    if (!(strpos($url_with_port, $store_url) === false) || !(strpos($url_without_port, $store_url) === false)) {
                        $store_id = $store['store_id'];
                        break;
                    }
                }
            }
        }
        return $store_id;
    }

    public function createEmptyOrderAction() {
        $this->createEmptyOrder();
        echo "1";
    }

    public function saveCustomerNotes() {
        if (isset($this->request->post)) {
            $data = $this->request->post;
            $this->db->query("DELETE FROM " . DB_PREFIX . "customer_notes WHERE customer_id='" . $data['notes_customer_id'] . "' ");

            $this->db->query("INSERT INTO " . DB_PREFIX . "customer_notes SET customer_notes='" . $this->db->escape($data['customer_sales_notes']) . "',customer_image='" . $this->db->escape($data['customer_image']) . "',customer_id='" . $data['notes_customer_id'] . "' ");
            echo "1";
        }
    }

    public function getCustomersOrderList() {
        if (isset($this->request->get['customer_id'])) {
            $json['orders'] = array();

            $query = $this->db->query("SELECT o.order_status_id,o.is_pos,o.payment_method,o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int) $this->request->get['customer_id'] . "' AND o.order_status_id > '0' ORDER BY o.order_id DESC ");

            foreach ($query->rows as $result) {
                /* echo "<pre>";
                  var_dump($result['order_status_id']);
                  echo "</pre>"; */
                if ($result['is_pos'] == 0) {
                    $storename = "Gempacked";
                } else {
                    $storename = "POS";
                }
                $orderstatuscheck = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id='" . $result['order_status_id'] . "' ");

                if ($orderstatuscheck->num_rows && $result['order_status_id'] != '22') {
                    $currency_color = 'green';
                } else {
                    $currency_color = '#ccc';
                }
                if ($result['order_status_id'] == '1' && $result['is_pos'] == '1') {
                    $result['status'] = '<b>Parked</b>';
                    $currency_color = '#ccc';
                    $resume_button = true;
                } else {
                    $resume_button = false;
                }
                $json['orders'][] = array(
                    'order_id' => $result['order_id'],
                    'name' => $result['firstname'] . ' ' . $result['lastname'],
                    'status' => $result['status'],
                    'date_added' => $result['date_added'],
                    'resume_button' => $resume_button,
                    'payment_method' => $result['payment_method'],
                    'currency_code' => "<p style='color:" . $currency_color . "'>" . $this->currency->getSymbolLeft($result['currency_code']) . "</p>",
                    'store_name' => $storename,
                    'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                    'href' => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL'),
                );
            }

            $this->response->setOutput(json_encode($json));
        }
    }

    public function getUniqueOrderEmail() {
        if (isset($this->request->get['order_id'])) {
            $query = $this->db->query("SELECT email FROM `" . DB_PREFIX . "order` WHERE order_id='" . $this->request->get['order_id'] . "' ");

            if ($query->num_rows) {
                echo json_encode($query->row['email']);
            } else {
                echo json_encode("1");
            }
        }
    }

    public function createEmptyOrder($is_quote = false, $table_id = 0) {
        if (isset($this->session->data['discount_customer_group_id'])) {
            unset($this->session->data['discount_customer_group_id']);
        }
        $data = array();

        $data['store_id'] = $this->getStoreId();

        $default_country_id = $this->config->get('POS_a_country_id') ? $this->config->get('POS_a_country_id') : $this->config->get('config_country_id');
        $default_zone_id = $this->config->get('POS_a_zone_id') ? $this->config->get('POS_a_zone_id') : $this->config->get('config_zone_id');
        $data['shipping_country_id'] = $default_country_id;
        $data['shipping_zone_id'] = $default_zone_id;
        $data['payment_country_id'] = $default_country_id;
        $data['payment_zone_id'] = $default_zone_id;
        $data['customer_id'] = 0;
        $default_customer_group_id = $this->config->get('POS_c_group_id') ? $this->config->get('POS_c_group_id') : $this->config->get('config_customer_group_id');
        $data['customer_group_id'] = $default_customer_group_id ? (int) $default_customer_group_id : 1;
        $data['firstname'] = $this->config->get('POS_c_firstname');
        $data['lastname'] = $this->config->get('POS_c_lastname');
        $data['email'] = $this->config->get('POS_c_email');
        $data['telephone'] = $this->config->get('POS_c_telephone');
        $data['fax'] = $this->config->get('POS_c_fax');
        $data['payment_firstname'] = $this->config->get('POS_a_firstname');
        $data['payment_lastname'] = $this->config->get('POS_a_lastname');
        $data['payment_company'] = '';
        $data['payment_company_id'] = '';
        $data['payment_tax_id'] = '';
        $data['payment_address_1'] = $this->config->get('POS_a_address_1');
        $data['payment_address_2'] = $this->config->get('POS_a_address_2');
        $data['payment_city'] = $this->config->get('POS_a_city');
        $data['payment_postcode'] = $this->config->get('POS_a_postcode');
        $data['payment_country_id'] = $default_country_id;
        $data['payment_zone_id'] = $default_zone_id;
        $data['payment_method'] = 'In Store';
        $data['payment_code'] = 'in_store';
        $data['shipping_firstname'] = $this->config->get('POS_a_firstname');
        $data['shipping_lastname'] = $this->config->get('POS_a_lastname');
        $data['shipping_company'] = '';
        $data['shipping_address_1'] = $this->config->get('POS_a_address_1');
        $data['shipping_address_2'] = $this->config->get('POS_a_address_2');
        $data['shipping_city'] = $this->config->get('POS_a_city');
        $data['shipping_postcode'] = $this->config->get('POS_a_postcode');
        $data['shipping_country_id'] = $default_country_id;
        $data['shipping_zone_id'] = $default_zone_id;
        $data['shipping_method'] = 'Instore Shipping';
        $data['shipping_code'] = 'instore.instore';
        $data['comment'] = '';
        $data['order_status_id'] = 1;
        // add for Empty order control begin
        if ($this->config->get('POS_initial_status_id')) {
            $data['order_status_id'] = $this->config->get('POS_initial_status_id');
        }
        // add for Empty order control end
        $data['affiliate_id'] = 0;
        $data['user_id'] = $this->user->getId();

        // add for Default Customer begin
        $c_data = array();
        $this->setDefaultCustomer($c_data);
        $data['customer_id'] = $c_data['c_id'];
        $data['customer_group_id'] = $c_data['c_group_id'];
        foreach ($c_data as $c_key => $c_value) {
            if (substr($c_key, 0, 2) == 'c_' && isset($data[substr($c_key, 2)])) {
                $data[substr($c_key, 2)] = $c_value;
            } elseif (substr($c_key, 0, 2) == 'a_') {
                if (isset($data['payment_' . substr($c_key, 2)])) {
                    $data['payment_' . substr($c_key, 2)] = $c_value;
                }
                if (isset($data['shipping_' . substr($c_key, 2)])) {
                    $data['shipping_' . substr($c_key, 2)] = $c_value;
                }
            }
        }
        // add for Default Customer end
        // add for Quotation begin
        if ($is_quote) {
            $data['quote'] = $is_quote;
        }
        // add for Quotation end
        // add for table management begin
        if ($table_id) {
            $data['table_id'] = $table_id;
        }
        // add for table management end
        $data['order_total'] = array(
            array('code' => 'total',
                'title' => $this->language->get('text_pos_total'),
                'text' => $this->currency->formatFront(0),
                'value' => 0,
                'sort_order' => $this->config->get('total_sort_order'))
        );

        $this->load->model('pos/pos');
        $order_id = $this->model_pos_pos->addOrder($data);

        return $order_id;
    }

    public function createEmptyReturn($customer_id) {
        $data = array();

        if ($this->config->get('POS_initial_return_status_id')) {
            $data['return_status_id'] = $this->config->get('POS_initial_return_status_id');
        } else {
            $data['return_status_id'] = 1;
        }
        $data['user_id'] = $this->user->getId();

        $valid_customer = false;
        if ($customer_id) {
            $this->load->model('pos/pos');
            $c_info = $this->model_pos_pos->getCustomer($customer_id);
            if ($c_info) {
                $data['customer_id'] = $customer_id;
                $data['firstname'] = $c_info['firstname'];
                $data['lastname'] = $c_info['lastname'];
                $data['name'] = $data['firstname'] . ' ' . $data['lastname'];
                $data['email'] = $c_info['email'];
                $data['telephone'] = $c_info['telephone'];
                $data['resale_number'] = $c_info['resale_number'];
                $data['notes'] = $c_info['notes'];
                $valid_customer = true;
            }
        }

        if (!$valid_customer) {
            $data['customer_id'] = 0;
            $data['firstname'] = 'Instore';
            $data['lastname'] = "Dummy";
            $data['email'] = 'customer@instore.com';
            $data['telephone'] = '1600';

            // add for Default Customer begin
            $c_data = array();
            $this->setDefaultCustomer($c_data);
            $data['customer_id'] = $c_data['c_id'];
            foreach ($c_data as $c_key => $c_value) {
                if (substr($c_key, 0, 2) == 'c_' && isset($data[substr($c_key, 2)])) {
                    $data[substr($c_key, 2)] = $c_value;
                }
            }
            // add for Default Customer end
        }

        $this->load->model('pos/pos');
        $pos_return_id = $this->model_pos_pos->addPosReturn($data);

        return $pos_return_id;
    }

    public function detachCustomer() {
        $customer = array();

        $default_country_id = $this->config->get('config_country_id');
        $default_zone_id = $this->config->get('config_zone_id');
        $customer['customer_id'] = 0;
        $default_customer_group_id = $this->config->get('config_customer_group_id');
        $customer['customer_group_id'] = $default_customer_group_id ? (int) $default_customer_group_id : 1;
        $customer['firstname'] = 'Instore';
        $customer['lastname'] = "Dummy";
        $customer['email'] = 'customer@instore.com';
        $customer['telephone'] = '1600';
        $customer['fax'] = '';
        $customer['payment_firstname'] = 'Instore';
        $customer['payment_lastname'] = "Dummy";
        $customer['payment_company'] = '';
        $customer['payment_company_id'] = '';
        $customer['payment_tax_id'] = '';
        $customer['payment_address_1'] = 'customer address';
        $customer['payment_address_2'] = '';
        $customer['payment_city'] = 'customer city';
        $customer['payment_postcode'] = '1600';
        $customer['payment_country_id'] = $default_country_id;
        $customer['payment_zone_id'] = $default_zone_id;
        $customer['payment_method'] = 'In Store';
        $customer['payment_code'] = 'in_store';
        $customer['shipping_firstname'] = 'Instore';
        $customer['shipping_lastname'] = 'Dummy';
        $customer['shipping_company'] = '';
        $customer['shipping_address_1'] = 'customer address';
        $customer['shipping_address_2'] = '';
        $customer['shipping_city'] = 'customer city';
        $customer['shipping_postcode'] = '1600';
        $customer['shipping_country_id'] = $default_country_id;
        $customer['shipping_zone_id'] = $default_zone_id;

        $this->load->model('localisation/country');
        $this->load->model('localisation/zone');
        $country_info = $this->model_localisation_country->getCountry($default_country_id);
        if ($country_info) {
            $payment_country = $country_info['name'];
            $payment_address_format = $country_info['address_format'];
        } else {
            $payment_country = '';
            $payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
        }
        $shipping_country = $payment_country;
        $shipping_address_format = $payment_address_format;
        $zone_info = $this->model_localisation_zone->getZone($default_zone_id);
        if ($zone_info) {
            $payment_zone = $zone_info['name'];
        } else {
            $payment_zone = '';
        }
        $shipping_zone = $payment_zone;

        $order_id = $this->request->get['order_id'];
        $default_customer_group_id = $this->config->get('config_customer_group_id');
        $customer_group_id = $default_customer_group_id ? (int) $default_customer_group_id : 1;
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET customer_id = '0', customer_group_id = '" . $customer_group_id . "', firstname = '" . $this->db->escape($customer['firstname']) . "', lastname = '" . $this->db->escape($customer['lastname']) . "', email = '" . $this->db->escape($customer['email']) . "', telephone = '" . $this->db->escape($customer['telephone']) . "', fax = '" . $this->db->escape($customer['fax']) . "', payment_firstname = '" . $this->db->escape($customer['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($customer['payment_lastname']) . "', payment_company = '" . $this->db->escape($customer['payment_company']) . "', payment_company_id = '" . $this->db->escape($customer['payment_company_id']) . "', payment_tax_id = '" . $this->db->escape($customer['payment_tax_id']) . "', payment_address_1 = '" . $this->db->escape($customer['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($customer['payment_address_2']) . "', payment_city = '" . $this->db->escape($customer['payment_city']) . "', payment_postcode = '" . $this->db->escape($customer['payment_postcode']) . "', payment_country = '" . $this->db->escape($payment_country) . "', payment_country_id = '" . (int) $customer['payment_country_id'] . "', payment_zone = '" . $this->db->escape($payment_zone) . "', payment_zone_id = '" . (int) $customer['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($payment_address_format) . "', shipping_firstname = '" . $this->db->escape($customer['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($customer['shipping_lastname']) . "',  shipping_company = '" . $this->db->escape($customer['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($customer['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($customer['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($customer['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($customer['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($shipping_country) . "', shipping_country_id = '" . (int) $customer['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($shipping_zone) . "', shipping_zone_id = '" . (int) $customer['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
        $this->language->load('module/pos');
        $customer['success'] = $this->language->get('text_order_success');
        $this->response->setOutput(json_encode($customer));
    }

    public function modify_order($data) {
        $this->language->load('module/pos');
        $this->load->model('pos/pos');
        $this->load->model('pos/product');

        $json = array();
        $json['product_id'] = $data['product_id'];
        $product_id = $data['product_id'];
        $product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE product_id = '" . (int) $product_id . "' LIMIT 1");
        $json['sku'] = $product_query->row['sku'];
        $json['model'] = $product_query->row['model'];
        $json['unique_price_discount'] = $product_query->row['unique_price_discount'];
        $json['discounted_price_for_percent'] = $data['price'];
        $json['text_discounted_price_for_percent'] = $this->currency->formatFront($data['price']);
        $product_unit_conversion_values = !empty($data['coversion_id']) ? $this->model_pos_pos->getUnitConvertedValue($data['product_id'], $data['coversion_id']) : 1;
        $new_quantity = !empty($data['quantity_after']) ? $data['quantity_after'] : $data['quantity'];
        $json['product_actual_total'] = $product_query->row['price'] * $new_quantity * $product_unit_conversion_values;
        $json['text_product_actual_total'] = $this->currency->formatFront($json['product_actual_total']);
        $order_id = $data['order_id'];
        $action = $data['action'];

        $is_empty_order = false;
        if ($action == 'insert' || $action == 'insert_quick') {
            $open_bay_product_id = $data['product_id'];
            // update order creation time if it's an empty order
            $order_product_query = $this->db->query("SELECT order_product_id FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . (int) $order_id . "' LIMIT 1");
            if ($order_product_query->num_rows == 0) {
                $is_empty_order = true;
            }
            $tax = $this->getTax((float) $data['product']['price'], $data['product']['tax_class_id'], $data['customer_group_id']);
            $reward_points = (!empty($data['reward_points'])) ? (int) $data['quantity'] * (int) $data['reward_points'] : 0;
            $quantity = $data['quantity'];
            if (isset($data['coversion_id'])) {
                $unit_conversion_values = $data['coversion_id'];
            } else {
                $unit_conversion_values = 0;
            }
            $product_id = (int) $data['product_id'];
            $unit_data = array();
            $option_details = array();
            if (isset($data['option'])) {
                foreach ($data['option'] as $option_data) {
                    $query = "select price, option_id "
                            . "from " . DB_PREFIX . "product_option_value "
                            . " where product_id = '" . (int) $product_id . "' AND "
                            . " product_option_id = '" . (int) $option_data['product_option_id'] . "' AND "
                            . " product_option_value_id ='" . (int) $option_data['product_option_value_id'] . "'";
                    $result_price = $this->db->query($query);
                    $option_details = $result_price->row;
                }
            }
            if (!empty($unit_conversion_values) || $unit_conversion_values != 0) {
                $sql_unit_sql = "SELECT unit_id, unit_value_id, convert_price FROM " . DB_PREFIX . "unit_conversion_product WHERE unit_conversion_product_id = $unit_conversion_values AND product_id = $product_id";
                $res_unit_sql = $this->db->query($sql_unit_sql);
                if ($res_unit_sql->num_rows > 0) {
                    $unit_id = $res_unit_sql->row['unit_id'];
                    $unit_value_id = $res_unit_sql->row['unit_value_id'];
                    $convert_price = $res_unit_sql->row['convert_price'];

                    $sql_unit_name = "SELECT name FROM " . DB_PREFIX . "unit_conversion WHERE unit_id = $unit_id";
                    $res_unit_name = $this->db->query($sql_unit_name);
                    $unit_name = $res_unit_name->row['name'];

                    $sql_unit_value_name = "SELECT name FROM " . DB_PREFIX . "unit_conversion_value WHERE unit_value_id = $unit_value_id";
                    $res_unit_value_name = $this->db->query($sql_unit_value_name);
                    $unit_value_name = $res_unit_value_name->row['name'];

                    $unit_data = array(
                        'unit_conversion_values' => $unit_conversion_values,
                        'unit_name' => $unit_name,
                        'unit_value_name' => $unit_value_name,
                        'convert_price' => $convert_price
                    );
                    $json['unit_data'] = $unit_data;
                }
            }
            //print_r($unit_data);
            $json['unit_dates_default'] = $this->model_pos_product->getDefaultUnitDetails($product_id);
            $json['DefaultUnitName'] = $this->model_pos_product->getDefaultUnitName($product_id);

            if (isset($unit_data) && !empty($unit_data)) {
                $json['convert_price'] = $unit_data['convert_price'];
                $combinePrice = $unit_data['convert_price'] * $data['price'];
                //$this->model_pos_pos->geProductCalculatedPrice($product_id,$quantity,$unit_data['convert_price'],$data['customer_group_id']);
            } else {
                $combinePrice = $this->model_pos_pos->geProductCalculatedPrice($product_id, $quantity, null, $data['customer_group_id'], $option_details);
            }

            $json['converstion_line'] = '';
            if (isset($json['convert_price']) && $json['convert_price'] != 1) {
                $data['product']['Displayprice'] = $combinePrice * $json['convert_price'];
                $json['converstion_line'] = '-' . number_format(($json['convert_price'] * $quantity), 2) . ' ' . $json['unit_dates_default']['name'] . ' = ' . $quantity . ' ' . $json['unit_data']['unit_value_name'] . '<br />';
            }


            $this->db->query("INSERT INTO `" . DB_PREFIX . "order_product` SET order_id = '" . (int) $order_id . "', product_id = '" . (int) $data['product_id'] . "', name = '" . $this->db->escape($data['product']['name']) . "', model = '" . $this->db->escape($data['product']['model']) . "', quantity = '" . (float) $quantity . "', price = '" . (float) $combinePrice . "', total = '" . ((float) $combinePrice * (float) $quantity) . "', tax = '" . $tax . "', reward = '" . $reward_points . "',cost='" . ((float) $data['price'] * (float) $json['unique_price_discount']) . "',base_price='" . (float) $data['price'] . "', weight = '" . (float) $data['weight'] . "',unit_conversion_values= '" . (int) $unit_conversion_values . "'");

            $order_product_id = $this->db->getLastId();
            $getShippingMethod = $this->db->query("SELECT shipping_method FROM " . DB_PREFIX . "order WHERE order_id='" . (int) $order_id . "' ");
            if ($getShippingMethod->num_rows) {
                if ($getShippingMethod->row['shipping_method'] == 'instore') {
                    $this->db->query("UPDATE " . DB_PREFIX . "order_product SET quantity_supplied='" . (float) $quantity . "' WHERE order_product_id='" . $order_product_id . "' ");
                }
            }
            $json['order_product_id'] = $order_product_id;

            // add for customer loyalty card begin
            if (!empty($data['reward_points']) && (int) $data['customer_id'] > 0 && $data['work_mode'] == '0') {
                // update customer reward for this order
                $reward_query = $this->db->query("SELECT points FROM `" . DB_PREFIX . "customer_reward` WHERE customer_id = '" . (int) $data['customer_id'] . "' AND order_id = '" . (int) $order_id . "'");
                if ($reward_query->row) {
                    $this->db->query("UPDATE `" . DB_PREFIX . "customer_reward` SET points = points + " . (int) $data['quantity'] * (int) $data['reward_points'] . ", date_added = NOW() WHERE customer_id = '" . (int) $data['customer_id'] . "' AND order_id = '" . (int) $order_id . "'");
                } else {
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "customer_reward` SET customer_id = '" . (int) $data['customer_id'] . "', order_id = '" . (int) $order_id . "', points = '" . (int) $data['quantity'] * (int) $data['reward_points'] . "', description = 'Order ID: " . (int) $order_id . "', date_added = NOW()");
                }
            }
            // add for customer loyalty card end

            $this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity - " . (int) $data['quantity'] . ") WHERE product_id = '" . (int) $data['product_id'] . "' AND subtract = '1'");

            if (isset($data['option'])) {
                foreach ($data['option'] as $order_option) {
                    if (!empty($order_option['product_option_value_id'])) {
                        $product_option_value_ids = is_array($order_option['product_option_value_id']) ? $order_option['product_option_value_id'] : array($order_option['product_option_value_id'] => array('value' => $order_option['value']));
                        foreach ($product_option_value_ids as $product_option_value_id => $product_option_values) {
                            if (!empty($product_option_values['value'])) {
                                $this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int) $order_id . "', order_product_id = '" . (int) $order_product_id . "', product_option_id = '" . (int) $order_option['product_option_id'] . "', product_option_value_id = '" . (int) $product_option_value_id . "', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($product_option_values['value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");

                                $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int) $data['quantity'] . ") WHERE product_option_value_id = '" . (int) $product_option_value_id . "' AND subtract = '1'");
                            }
                        }
                    } else {
                        if (!empty($order_option['value'])) {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int) $order_id . "', order_product_id = '" . (int) $order_product_id . "', product_option_id = '" . (int) $order_option['product_option_id'] . "', product_option_value_id = '0', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($order_option['value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");
                        }
                    }
                }
            }
            if ($action == 'insert' && (!empty($data['product_sn_id']) || !empty($data['product_sn'])) && $data['work_mode'] == '0') {
                // add for serial no begin
                $json['product_sns'] = $this->model_pos_pos->sellProductSN($data['product_sn_id'], $data['product_sn'], $order_product_id, $data['product_id'], $order_id);
                // add for serial no end
            }
            $data['product']['price'] = $combinePrice;
            $json['mainprice'] = $this->model_pos_pos->getMainPrice($data['product_id']);
            $json['price'] = $data['product']['price'];
            $json['text_price'] = $this->currency->formatFront(($this->config->get('config_tax')) ? $data['product']['price'] + $tax : $data['product']['price']);
            $json['text_mainprice'] = $this->currency->formatFront(($this->config->get('config_tax')) ? $json['mainprice'] + $tax : $json['mainprice']);
            $json['text_total'] = $this->currency->formatFront(((float) $data['quantity']) * $data['product']['price']);
            $json['text_mainprice_total'] = $this->currency->formatFront(((float) $data['quantity']) * $json['mainprice']);
        } elseif ($action == 'modify_quantity') {
            $tax = $this->getTax((float) $data['product']['price'], $data['product']['tax_class_id'], $data['customer_group_id']);
            if (isset($data['coversion_id'])) {
                $unit_conversion_values = $data['coversion_id'];
            } else {
                $unit_conversion_values = 0;
            }
            $product_id = (int) $data['product_id'];
            $unit_data = array();
            $option_details = array();
            if (!empty($unit_conversion_values) || $unit_conversion_values != 0) {
                $sql_unit_sql = "SELECT unit_id, unit_value_id, convert_price FROM " . DB_PREFIX . "unit_conversion_product WHERE unit_conversion_product_id = $unit_conversion_values AND product_id = $product_id";
                $res_unit_sql = $this->db->query($sql_unit_sql);
                if ($res_unit_sql->num_rows > 0) {
                    $unit_id = $res_unit_sql->row['unit_id'];
                    $unit_value_id = $res_unit_sql->row['unit_value_id'];
                    $convert_price = $res_unit_sql->row['convert_price'];

                    $sql_unit_name = "SELECT name FROM " . DB_PREFIX . "unit_conversion WHERE unit_id = $unit_id";
                    $res_unit_name = $this->db->query($sql_unit_name);
                    $unit_name = $res_unit_name->row['name'];

                    $sql_unit_value_name = "SELECT name FROM " . DB_PREFIX . "unit_conversion_value WHERE unit_value_id = $unit_value_id";
                    $res_unit_value_name = $this->db->query($sql_unit_value_name);
                    $unit_value_name = $res_unit_value_name->row['name'];

                    $unit_data = array(
                        'unit_conversion_values' => $unit_conversion_values,
                        'unit_name' => $unit_name,
                        'unit_value_name' => $unit_value_name,
                        'convert_price' => $convert_price
                    );
                    $json['unit_data'] = $unit_data;
                }
            }
            //print_r($unit_data);
            $json['unit_dates_default'] = $this->model_pos_product->getDefaultUnitDetails($product_id);
            $json['DefaultUnitName'] = $this->model_pos_product->getDefaultUnitName($product_id);

            if (isset($unit_data) && !empty($unit_data)) {
                $json['convert_price'] = $unit_data['convert_price'];
                $combinePrice = $unit_data['convert_price'] * $data['price'];
                //$this->model_pos_pos->geProductCalculatedPrice($product_id,$quantity,$unit_data['convert_price'],$data['customer_group_id']);
            } else {
                $combinePrice = $this->model_pos_pos->geProductCalculatedPrice($product_id, $data['quantity_after'], null, $data['customer_group_id'], $option_details);
            }

            $json['converstion_line'] = '';
            if (isset($json['convert_price']) && $json['convert_price'] != 1) {
                $data['product']['Displayprice'] = $combinePrice * $json['convert_price'];
                $json['converstion_line'] = '-' . number_format(($json['convert_price'] * (float) $data['quantity_after']), 2) . ' ' . $json['unit_dates_default']['name'] . ' = ' . $data['quantity_after'] . ' ' . $json['unit_data']['unit_value_name'] . '<br />';
            }

            $quantity_change = (float) $data['quantity_after'] - (float) $data['quantity_before'];
            $sqlQuery = "UPDATE " . DB_PREFIX . "order_product SET quantity = " . (float) $data['quantity_after'] . ", price = '" . (float) $combinePrice . "', total = '" . ((float) $combinePrice * (float) $data['quantity_after']) . "', tax = '" . $tax . "' WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $data['order_product_id'] . "'";
            $this->db->query($sqlQuery);
            // add for product based discount begin
            $this->db->query("UPDATE `" . DB_PREFIX . "order_product_discount` SET discounted_total = discounted_price * " . (int) $data['quantity_after'] . " WHERE order_product_id = '" . (int) $data['order_product_id'] . "'");
            // add for product based discount end
            // add for customer loyalty card begin
            if ($data['work_mode'] == '0') {
                $reward_query = $this->db->query("SELECT reward FROM `" . DB_PREFIX . "order_product` WHERE order_product_id = '" . (int) $data['order_product_id'] . "'");
                if ($reward_query->row && (int) $reward_query->row['reward'] > 0) {
                    $reward_delta = $quantity_change * (int) $reward_query->row['reward'] / (int) $data['quantity_before'];
                    $this->db->query("UPDATE `" . DB_PREFIX . "order_product` SET reward = reward + " . $reward_delta . " WHERE order_product_id = '" . (int) $data['order_product_id'] . "'");
                    $this->db->query("UPDATE `" . DB_PREFIX . "customer_reward` SET points = points + " . $reward_delta . ", date_added = NOW() WHERE customer_id = '" . (int) $data['customer_id'] . "' AND order_id = '" . (int) $order_id . "'");
                }
            }
            // add for customer loyalty card end

            $this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity - " . (int) $quantity_change . ") WHERE product_id = '" . (int) $data['product_id'] . "' AND subtract = '1'");

            if (isset($data['order_option'])) {
                foreach ($data['order_option'] as $order_option) {
                    $product_option_value_ids = is_array($order_option['product_option_value_id']) ? $order_option['product_option_value_id'] : array($order_option['product_option_value_id'] => $order_option['value']);
                    foreach ($product_option_value_ids as $product_option_value_id => $value) {
                        $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int) $quantity_change . ") WHERE product_option_value_id = '" . (int) $product_option_value_id . "' AND subtract = '1'");
                    }
                }
            }
            // add for serial no begin
            if (isset($data['product_sn_id']) && isset($data['product_sn']) && $data['work_mode'] == '0') {
                $json['product_sns'] = $this->model_pos_pos->sellProductSN($data['product_sn_id'], $data['product_sn'], $data['order_product_id'], $data['product_id'], $order_id);
            }
            // add for serial no end
            $json['quantity_after'] = (float) $data['quantity_after'];
            $json['mainprice'] = $this->model_pos_pos->getMainPrice($data['product_id']);
            $json['text_mainprice'] = $this->currency->formatFront(($this->config->get('config_tax')) ? $json['mainprice'] + $tax : $json['mainprice']);
        } elseif ($action == 'modify_price') {
            $price_after = (float) $data['price_after'];
            if ($this->config->get('config_tax')) {
                $price_after = $this->getPriceFromPriceWithTax($price_after, $data['product']['tax_class_id'], $data['customer_group_id']);
            }
            $tax_after = (float) $data['price_after'] - $price_after;
            $this->db->query("UPDATE " . DB_PREFIX . "order_product SET price_change = '1', price = '" . $price_after . "', tax = '" . $tax_after . "', total = quantity * weight * " . $price_after . " WHERE order_product_id = '" . (int) $data['order_product_id'] . "'");
            // add for product based discount begin
            $this->db->query("UPDATE `" . DB_PREFIX . "order_product_discount` SET discounted_price = '" . $price_after . "', discounted_tax = '" . $tax_after . "', discounted_total = '" . $price_after * (int) $data['quantity'] . "' WHERE order_product_id = '" . (int) $data['order_product_id'] . "'");
            // add for product based discount end

            $json['price'] = $price_after;
            $json['product_total'] = number_format((float) ($price_after * (int) $data['quantity']), 2, '.', '');
        } elseif ($action == 'delete') {
            // waqar
            $this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int) $data['quantity'] . ") WHERE product_id = '" . (int) $data['product_id'] . "' AND subtract = '1'");
            // add for customer loyalty card begin
            if ((int) $data['customer_id'] > 0 && $data['work_mode'] == '0') {
                // update customer reward for this order
                $reward_query = $this->db->query("SELECT reward FROM `" . DB_PREFIX . "order_product` WHERE order_product_id= '" . (int) $data['order_product_id'] . "'");
                if ($reward_query->row && (int) $reward_query->row['reward'] > 0) {
                    $this->db->query("UPDATE `" . DB_PREFIX . "customer_reward` SET points = points - " . (int) $reward_query->row['reward'] . ", date_added = NOW() WHERE customer_id = '" . (int) $data['customer_id'] . "' AND order_id = '" . (int) $order_id . "'");
                }
            }
            // add for customer loyalty card end

            if (!empty($data['order_option'])) {
                foreach ($data['order_option'] as $order_option) {
                    $product_option_value_ids = is_array($order_option['product_option_value_id']) ? $order_option['product_option_value_id'] : array($order_option['product_option_value_id'] => '');
                    foreach ($product_option_value_ids as $product_option_value_id => $value) {
                        $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int) $data['quantity'] . ") WHERE product_option_value_id = '" . (int) $product_option_value_id . "' AND subtract = '1'");
                    }
                }
            }
            // add for product based discount begin
            $this->db->query("DELETE FROM " . DB_PREFIX . "order_product_discount WHERE order_product_id = '" . (int) $data['order_product_id'] . "'");
            // add for product based discount end
            $this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_product_id = '" . (int) $data['order_product_id'] . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $data['order_product_id'] . "'");
            // add for serial no begin
            if ($data['work_mode'] == '0') {
                $this->model_pos_pos->restoreSoldProductSN($data['order_product_id']);
            }
            // add for serial no end
        }

        $total_data = $this->recalculate_total($data);
        $json['order_total'] = $total_data['order_total'];
        if (!empty($total_data['discount'])) {
            $json['discount'] = $total_data['discount'];
        }
        if (!empty($total_data['extra_info'])) {
            $json['extra_info'] = $total_data['extra_info'];
        }
        $total = $total_data['total'];

        $cur_time = time();
        if ($is_empty_order) {
            $update_query = "UPDATE `" . DB_PREFIX . "order` SET total = '" . (float) $total . "', sales_person = '" . (int) $this->user->getId() . "', date_added = NOW(), date_modified = NOW()";
            if ($data['work_mode'] == '0') {
                $update_query .= ", quote_status_id = '0', quote_id = '0'";
            } elseif ($data['work_mode'] == '2') {
                $quote_status_id = '1';
                $quote_query = $this->db->query("SELECT MIN(quote_status_id) AS min_quote_status_id FROM `" . DB_PREFIX . "quote_status`");
                if ($quote_query->row) {
                    $quote_status_id = $quote_query->row['min_quote_status_id'];
                }
                $update_query .= ", quote_status_id = '" . $quote_status_id . "', quote_id = '0'";
            }
            if (!empty($data['table_id'])) {
                $update_query .= ", table_id = '" . (int) $data['table_id'] . "'";
            }
            $update_query .= " WHERE order_id = '" . (int) $order_id . "'";
            $this->db->query($update_query);
            $json['date_added'] = date('Y-m-d H:i:s', $cur_time);
        } else {
            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET total = '" . (float) $total . "', sales_person = '" . (int) $this->user->getId() . "', date_modified = NOW() WHERE order_id = '" . (int) $order_id . "'");
        }
        $json['date_modified'] = date('Y-m-d H:i:s', $cur_time);

        if (isset($order_product_id)) {
            $json['order_product_id'] = $order_product_id;
        }
        if ($action == 'insert_quick') {
            $json['product_id'] = $data['product_id'];
        }
        $json['success'] = $this->language->get('text_order_success');
        // add for Quotation begin
        if (isset($data['work_mode']) && $data['work_mode'] == '2') {
            $json['success'] = $this->language->get('text_quote_sucess');
        }
        // add for Quotation end
        // add for Openbay integration begin
        if ($action != 'modify_price') {
            // only if action is not modify_price
            $json['enable_openbay'] = $this->config->get('POS_enable_openbay');
        }
        // add for Openbay integration bend

        return $json;
    }

    public function update_total() {
        // only requires to update total
        $this->load->model('pos/pos');
        $this->language->load('module/pos');
        $json = $this->recalculate_total($this->request->post);
        $this->response->setOutput(json_encode($json));
    }

    private function recalculate_total($data) {
        $order_id = $data['order_id'];

        // recalculate the total
        if (version_compare(VERSION, '2.1.0', '<')) {
            $this->load->library('customer');
            $this->load->library('tax');
            $this->load->library('cart');
        } else {
            library('customer');
            library('tax');
            library('cart');
        }

        $this->customer = new Customer($this->registry);
        $this->tax = new Tax($this->registry);
        $this->cart = new Cart($this->registry);
        //$this->customer->logout();
        if ($data['customer_id']) {
            $this->load->model('pos/pos');
            $customer_info = $this->model_pos_pos->getCustomer($data['customer_id']);
            if ($customer_info) {
                $this->customer->login($customer_info['email'], '', true);
            }
        }

        // put all order product into the cart
        $order_products = $this->model_pos_pos->getOrderProducts($order_id);
        $this->session->data['pos_cart'] = 1;
        // only when insert a new product (not quick sale), or quantity changed, need get a potential new price from the cart in case there are product discount
        // add opencart product discount support begin
        $extra_info = array();
        $action = (isset($data['action'])) ? $data['action'] : '';
        /* if ($action == 'insert') {
          // get total quantity of the products with the same product_id with the product added or modified
          $discount_quantity = 0;
          $order_products_affected = array();
          foreach ($order_products as $order_product) {
          if ($order_product['product_id'] == $data['product_id'] && !$order_product['price_change'] && $order_product['quantity'] > 0) {
          $discount_quantity += $order_product['quantity'];
          $order_products_affected[$order_product['order_product_id']] = array('quantity' => $order_product['quantity'], 'weight' => $order_product['weight']);;
          }
          }
          $product_discount_query = $this->db->query("SELECT price, quantity FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$data['product_id'] . "' AND customer_group_id = '" . (int)$data['customer_group_id'] . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
          if ($product_discount_query->num_rows) {
          // change the product price as the price may be different from the original price as discount was used
          $changed_price = $product_discount_query->row['price'];
          $insert_tax = $this->getTax($changed_price, $data['product']['tax_class_id'], $data['customer_group_id']);
          // update all the totals and price in order_product table
          foreach ($order_products_affected as $add_order_product_id => $add_order_product_info) {
          $insert_total = (float)$changed_price * $add_order_product_info['quantity'] * $add_order_product_info['weight'];
          $this->db->query("UPDATE `" . DB_PREFIX . "order_product` SET price = '" . $changed_price . "', tax = '" . $insert_tax . "', total = '" . $insert_total . "' WHERE order_product_id = '" . $add_order_product_id . "'");
          $extra_info[$add_order_product_id] = array('price' => $this->config->get('config_tax') ? $changed_price + $insert_tax : $changed_price,
          'total' => $this->config->get('config_tax') ? $insert_total + $insert_tax * $add_order_product_info['quantity'] * $add_order_product_info['weight']: $insert_total);
          // also need update the order_products to be added into the cart
          foreach ($order_products as $key => $order_product) {
          if ($order_product['order_product_id'] == $add_order_product_id) {
          $order_products[$key]['price'] = $changed_price;
          $order_products[$key]['tax'] = $insert_tax;
          $order_products[$key]['total'] = $insert_total;
          }
          }
          }
          }
          }
          elseif ($action == 'modify_quantity') {
          // quantity change scenario is a little bit different
          // if before is qualified to have discount and after isn't, price will increase
          // if before isn't qualified to have discount and after is, price will decrease
          // for product price, only the after quantity is needed
          $modify_may_affect_other_order_products = false;
          foreach ($order_products as $order_product) {
          if ($order_product['order_product_id'] == $data['order_product_id'] && !$order_product['price_change'] && $order_product['quantity'] > 0) {
          $modify_may_affect_other_order_products = true;
          break;
          }
          }
          if ($modify_may_affect_other_order_products) {
          $curr_total_quantity = 0;
          $order_products_affected = array();
          foreach ($order_products as $order_product) {
          if ($order_product['product_id'] == $data['product_id'] && !$order_product['price_change']) {
          $curr_total_quantity += $order_product['quantity'];
          $order_products_affected[$order_product['order_product_id']] = array('quantity' => $order_product['quantity'], 'weight' => $order_product['weight']);;
          }
          }
          $prev_total_quantity = $curr_total_quantity + (int)$data['quantity_before'] - (int)$data['quantity_after'];
          $discount_quantity = $curr_total_quantity > $prev_total_quantity ? $curr_total_quantity : $prev_total_quantity;
          $product_discount_query = $this->db->query("SELECT price, quantity FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$data['product_id'] . "' AND customer_group_id = '" . (int)$data['customer_group_id'] . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
          if ($product_discount_query->num_rows) {
          $quantity_compared = $product_discount_query->row['quantity'];
          if (($curr_total_quantity >= $quantity_compared && $prev_total_quantity < $quantity_compared) || ($curr_total_quantity < $quantity_compared && $prev_total_quantity >= $quantity_compared)) {
          // only when the quantity change across the discount quantity, modify the database price, tax and total, and return the updated price and total
          $changed_price = $product_discount_query->row['price'];
          if ($curr_total_quantity < $prev_total_quantity) {
          $changed_price = $data['product']['price'];
          }
          $modify_tax = $this->getTax($changed_price, $data['product']['tax_class_id'], $data['customer_group_id']);
          foreach ($order_products_affected as $modify_order_product_id => $modify_order_product_info) {
          $modify_total = $changed_price * $modify_order_product_info['quantity'] * $modify_order_product_info['weight'];
          $this->db->query("UPDATE `" . DB_PREFIX . "order_product` SET price = '" . $changed_price . "', tax = '" . $modify_tax . "', total = '" . $modify_total . "' WHERE order_product_id = '" . $modify_order_product_id . "'");
          $extra_info[$modify_order_product_id] = array('price' => $this->config->get('config_tax') ? $changed_price + $modify_tax : $changed_price,
          'total' => $this->config->get('config_tax') ? $modify_total + $modify_tax * $modify_order_product_info['quantity'] * $modify_order_product_info['weight']: $modify_total);
          // also need update the order_products to be added into the cart
          foreach ($order_products as $key => $order_product) {
          if ($order_product['order_product_id'] == $modify_order_product_id) {
          $order_products[$key]['price'] = $changed_price;
          $order_products[$key]['tax'] = $modify_tax;
          $order_products[$key]['total'] = $modify_total;
          }
          }
          }
          }
          }
          }
          } */
        // add opencart product discount support end
        foreach ($order_products as $order_product) {
            $add_order_product_id = $order_product['order_product_id'];
            $extra_info[$add_order_product_id] = array(
                'price' => $order_product['price'],
                'total' => $order_product['total']
            );
        }
        $this->cart->add_pos_products($order_products);
        // for shipping method that requires products in the cart
        $this->session->data['pos_products'] = $order_products;
        // then set the tax addresses for calculating taxes for total
        if ($this->cart->hasShipping()) {
            $this->tax->setShippingAddress($data['shipping_country_id'], $data['shipping_zone_id']);
        } else {
            $this->tax->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
        }
        $this->tax->setPaymentAddress($data['payment_country_id'], $data['payment_zone_id']);
        $this->tax->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));

        $order_total = array();
        $total = 0;
        $sort_order = array();

        $this->load->model('extension/extension');
        $results = $this->model_extension_extension->getInstalled('total');
        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get($value . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        // check if discount total is there
        $discount_total = false;
        foreach ($results as $key => $result) {
            if ($result == 'pos_discount') {
                if ($this->config->get($result . '_status') && isset($this->session->data['pos_discount'])) {
                    $discount = $this->session->data['pos_discount'];
                    if ($discount['order_id'] == $order_id) {
                        $discount_total = true;
                    }
                }
                // move this out from the array that we can control when the discount will be applied
                unset($results[$key]);
                break;
            }
        }
        // need to get discount for the tax
        $taxes = $this->getTaxes($order_id, $order_products, $data['customer_group_id'], $discount_total);

        foreach ($results as $result) {
            if ($this->config->get($result . '_status') && !($result == 'shipping' && !empty($this->session->data['shipping_method']['code']) && $this->session->data['shipping_method']['code'] == 'instore.instore')) {
                $this->load->model('total/' . $result);
                $this->{'model_total_' . $result}->getTotal($order_total, $total, $taxes);

                if ($result == 'sub_total') {
                    // right after subtotal, apply the discount
                    if ($discount_total) {
                        $discount = $this->session->data['pos_discount'];
                        // only when the session data matched the current order, in case some session failure
                        // apply discount
                        /*
                          $this->load->model('total/pos_discount');
                          $this->model_total_pos_discount->getTotal($order_total, $total, $taxes);
                         */
                        // Not calling the total method, as it has no context of the current functions to get the tax from discount
                        // even though discount is given before tax, the taxes variable already contains the full sub total
                        $amount = $this->calc_discount_amount($total);

                        $order_total[] = array(
                            'code' => $discount['code'],
                            'title' => $discount['title'],
                            'text' => $this->currency->formatFront($amount),
                            'value' => $amount,
                            'sort_order' => $this->config->get('pos_discount_sort_order')
                        );

                        $total += $amount;
                    }
                }
            }
        }

        foreach ($order_total as $key => $value) {
            if ($value['code'] == 'shipping' && $value['title'] == 'Custom Shipping' && $value['value'] == 0) {
                unset($order_total[$key]);
            }
        }

        if ($this->model_pos_pos->orderHasShipping($order_id) && $this->orderTotalHasNoShipping($order_total)) {
            $shipping_total = $this->model_pos_pos->getOrderShippingTotal($order_id);
            $order_total[] = array(
                'code' => 'shipping',
                'title' => $shipping_total['title'],
                'text' => $this->currency->formatFront($shipping_total['value']),
                'value' => $shipping_total['value'],
                'sort_order' => $shipping_total['sort_order']
            );
        }

        if ($this->orderTotalHasSubTotal($order_total) && $this->orderTotalHasNoShipping($order_total)) {
            $order_total[] = array(
                'code' => 'shipping',
                'title' => 'Shipping',
                'text' => $this->currency->formatFront(0.00),
                'value' => 0.00,
                'sort_order' => 3
            );
        }

        $sort_order = array();

        foreach ($order_total as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
            // reformat the total text as it's not right if default currency is not USD, need to be fixed by Opencart, but fix here temporarily
            $order_total[$key]['text'] = $this->currency->formatFront($value['value'], $data['currency_code'], 1);
            if ($value['code'] == 'sub_total') {
                $order_total[$key]['title'] = $this->language->get('text_pos_sub_total');
            }
            if ($value['code'] == 'total') {
                $order_total[$key]['title'] = $this->language->get('text_pos_total');
            }
        }

        array_multisort($sort_order, SORT_ASC, $order_total);

        $this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "' AND code != 'shipping'");

        foreach ($order_total as $order_total_data) {
            if ($order_total_data['code'] == 'shipping') {
                continue;
            }
            $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', code = '" . $this->db->escape($order_total_data['code']) . "', title = '" . $this->db->escape($order_total_data['title']) . "', `value` = '" . (float) $order_total_data['value'] . "', sort_order = '" . (int) $order_total_data['sort_order'] . "'");
        }
        $q = $this->db->query("SELECT sum(value) as total FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "' AND code !='total'");
        $total = $q->row['total'];

        $this->db->query("UPDATE `" . DB_PREFIX . "order_total` SET value = '" . $total . "' WHERE order_id = '" . (int) $order_id . "' AND code ='total'");

        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET total = '" . $total . "' WHERE order_id = '" . (int) $order_id . "'");
        foreach ($order_total as $k => $order_total_data) {
            if ($order_total_data['code'] == 'total') {
                $order_total[$k]['value'] = $total;
                $order_total[$k]['text'] = $this->currency->formatFront($total);
            }
        }

        // clean up
        //$this->cart->clear();
        //$this->customer->logout();
        // add for product based discount begin
        $discount = array();
        foreach ($order_products as $order_product) {
            if (isset($data['action']) && isset($data['order_product_id']) && (int) $data['order_product_id'] == $order_product['order_product_id'] && !empty($order_product['discount']) && (float) $order_product['discount']['discount_value'] > 0) {
                $product_discount = $order_product['discount'];
                $discount_value = $product_discount['discount_value'];
                if ($data['action'] == 'modify_quantity' && $product_discount['discount_type'] == 1) {
                    $discount_value = $product_discount['discount_value'] * $data['quantity_after'] / $data['quantity_before'];
                    $this->db->query("UPDATE `" . DB_PREFIX . "order_product_discount` SET discount_value = '" . $discount_value . "' WHERE order_product_id = '" . $order_product['order_product_id'] . "'");
                }
                $discount['discount_type'] = $product_discount['discount_type'];
                $discount['discount_value'] = $discount_value;
                $discount['total_text'] = $this->config->get('config_tax') ? $order_product['total'] + $order_product['quantity'] * $order_product['tax'] * $order_product['weight'] : $order_product['total'];
                $discount['discounted_total_text'] = $this->config->get('config_tax') ? $product_discount['discounted_total'] + $order_product['quantity'] * $product_discount['discounted_tax'] : $product_discount['discounted_total'];
            }
        }
        //echo "<pre>$total : "; print_r($order_total); echo "</pre>"; exit;
        // add for product based discount end
        return array('order_total' => $order_total, 'total' => $total, 'discount' => $discount, 'extra_info' => $extra_info);
    }

    public function orderTotalHasNoShipping($order_total) {
        foreach ($order_total as $total) {
            if ($total['code'] == 'shipping') {
                return false;
                break;
            }
        }
        return true;
    }

    public function orderTotalHasSubTotal($order_total) {
        foreach ($order_total as $total) {
            if ($total['code'] == 'sub_total') {
                return true;
                break;
            }
        }
        return false;
    }

    private function calc_discount_amount($sub_total) {
        $amount = 0;
        if (isset($this->session->data['pos_discount'])) {
            $discount = $this->session->data['pos_discount'];
            if ($discount['code'] == 'pos_discount_fixed') {
                if ((0 - $discount['value']) > $sub_total) {
                    $amount = -$sub_total;
                } else {
                    $amount = $discount['value'];
                }
            } elseif ($discount['code'] == 'pos_discount_percentage') {
                $percentage_text = $discount['title'];
                $index1 = strpos($percentage_text, '(');
                $index2 = strpos($percentage_text, ')');
                $percentage = 0;
                if ($index1 !== false && $index2 !== false && $index2 > $index1 + 2) {
                    $percentage = (float) substr($percentage_text, $index1 + 1, $index2 - $index1 - 1);
                }

                // $total is sub total at the moment
                $amount = 0 - $sub_total * $percentage / 100;
            }
        }
        return $amount;
    }

    public function validateResale() {
        $this->load->model('pos/pos');
        $json = array();
        $flag = 0;
        $error_resale_number = "CA Resale number must be in the form of one of the following: \n ABCD-123456789 \n ABCD123456789 \n 123456789";
        if (isset($this->request->post['resale_id_number'])) {
            if ($this->request->post['resale_id_number'] == "") {
                $json['error'] = $error_resale_number;
                $json['error'] = $error_resale_number;
            } else {
                $this->request->post['resale_id_number'] = trim(str_replace('+', '', $this->request->post['resale_id_number']));

                if ((utf8_strlen($this->request->post['resale_id_number']) > 14)) {
                    $json['error'] = $error_resale_number;
                    $json['error_html'] = str_replace("\n", "<br>", $error_resale_number);
                } else {
                    if (preg_match('/^[A-Z]{4}-[0-9]{9}$/i', $this->request->post['resale_id_number'])) {
                        $flag = 1;
                    } else if (preg_match('/^[A-Z]{4}[0-9]{9}$/i', $this->request->post['resale_id_number'])) {
                        $flag = 1;
                    } else if (preg_match('/^[0-9]{9}$/', $this->request->post['resale_id_number'])) {
                        $flag = 1;
                    } else {
                        $json['error'] = $error_resale_number;
                        $json['error_html'] = str_replace("\n", "<br>", $error_resale_number);
                    }
                }
                if ($flag == 1) {
                    $json['success'] = "Success: Your Resale id Number has been applied! ";
                    $this->session->data['resale_id_number'] = $this->request->post['resale_id_number'];
                    $this->session->data['success'] = "Success: Your Resale id Number has been applied! ";
                    if (!empty($this->request->post['order_id'])) {
                        $this->model_pos_pos->saveResaleIdNumber($this->request->post['order_id'], $this->request->post['resale_id_number']);
                    }
                }
            }
        }
        $this->response->setOutput(json_encode($json));
    }

    public function validateResaleImage() {
        $this->load->model('pos/pos');
        $json = array();
        $flag = 0;
        $error_resale_number = "Resale Image ID is not uploaded Yet. Kindly First select and upload Image first.";
        $error_duplic_number = "Resale Image ID is already uploaded and Applied.";
        if (isset($this->request->post['resale_id_number'])) {
            if (($this->request->post['resale_id_number'] == "") && ($this->request->post['isUploaded'] == "")) {
                $json['error'] = $error_resale_number;
            } elseif (isset($this->request->post['isUploaded']) && !empty($this->request->post['isUploaded'])) {
                $json['error'] = $error_duplic_number;
            } else {
                $this->request->post['resale_id_number'] = trim(str_replace('+', '', $this->request->post['resale_id_number']));
                $flag = 1; //it is image no, no an actual id, so no checks only valid image name is passed to this function, hence flag true
                if ($flag == 1) {
                    $json['success'] = "Success: Your Resale id Number has been applied! ";
                    $this->session->data['resale_id_number'] = $this->request->post['resale_id_number'];
                    $this->session->data['success'] = "Success: Your Resale id Number has been applied! ";
                    if (!empty($this->request->post['order_id'])) {
                        $this->model_pos_pos->saveResaleIdNumber($this->request->post['order_id'], $this->request->post['resale_id_number']);
                    }
                }
            }
        }
        $this->response->setOutput(json_encode($json));
    }

    public function getResaleImages($order_id = 0) {
        $json = array();
        if (!empty($order_id)) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_images WHERE order_id='" . $order_id . "' ");
            if ($query->num_rows) {
                foreach ($query->rows as $key => $value) {
                    $json['dataRows'][] = array(
                        'image' => HTTPS_CATALOG . "image/" . $value['image_path'],
                        'value' => $value['image_path']
                    );
                }
            }
        }
        return $json;
    }

    public function saveOrderStatus() {
        $order_id = $this->request->post['order_id'];
        $order_status_id = $this->request->post['order_status_id'];
        $this->load->model('pos/pos');
        $this->model_pos_pos->saveOrderStatus($order_id, $order_status_id);

        $this->language->load('module/pos');
        $json['success'] = $this->language->get('text_order_success');
        $json['order_status_name'] = $this->model_pos_pos->getOrderStatusName($order_status_id);
        $complete_status_id = $this->config->get('POS_complete_status_id') ? $this->config->get('POS_complete_status_id') : 5;
        // add for Print begin
        if ($order_status_id == $complete_status_id) {
            $json['p_complete'] = $this->config->get('POS_p_complete') ? $this->config->get('POS_p_complete') : 0;
        }
        // add for Print end
        // add for Empty order control begin
        if ($this->config->get('POS_initial_status_id')) {
            $json['initial_status_id'] = $this->config->get('POS_initial_status_id');
        }
        // add for Empty order control end
        // add for status change notification begin
        if ($this->config->get('POS_enable_notification')) {
            $err_msg = $this->sendNotification($order_id, $order_status_id);
            if (!$err_msg) {
                $json['error'] = $err_msg;
            }
        }
        // add for status change notification end
        // add for commission begin
        if ($order_status_id == $complete_status_id) {
            $this->model_pos_pos->addOrderCommission($order_id, $this->user->getId());
        }
        // add for commission end
        $this->response->setOutput(json_encode($json));
    }

    public function updateCustomerGroup() {
        $json = array();
        $customer_group_id = $this->request->post['customer_group_id'];
        $customer_id = $this->request->post['customer_id'];
        $this->load->model('pos/pos');
        $this->model_pos_pos->updateCustomerGroup($customer_group_id, $customer_id);
        $this->response->setOutput(json_encode($json));
    }

    // add for Quotation begin
    public function saveQuoteStatus() {
        $order_id = $this->request->post['order_id'];
        $quote_status_id = $this->request->post['quote_status_id'];
        $this->load->model('pos/pos');
        $this->model_pos_pos->saveQuoteStatus($order_id, $quote_status_id);

        $this->language->load('module/pos');
        $json['success'] = $this->language->get('text_quote_sucess');
        $this->response->setOutput(json_encode($json));
    }

    // add for Quotation end

    public function saveReturnStatus() {
        $json = array();

        $pos_return_id = $this->request->post['pos_return_id'];
        $return_status_id = $this->request->post['return_status_id'];
        $this->load->model('pos/pos');
        $this->model_pos_pos->saveReturnStatus($pos_return_id, $return_status_id);

        $return_complete_status_id = $this->config->get('POS_return_complete_status_id') ? $this->config->get('POS_return_complete_status_id') : 3;
        // add for Print begin
        if ($return_status_id == $return_complete_status_id) {
            $json['p_complete'] = $this->config->get('POS_p_complete') ? $this->config->get('POS_p_complete') : 0;
        }

        $this->language->load('module/pos');
        $json['success'] = $this->language->get('text_return_success');

        $this->response->setOutput(json_encode($json));
    }

    /* public function saveCustomer() {
      $json = $this->request->post;

      $customer_id = (int)$this->request->post['customer_id'];
      $data = array();
      if ($customer_id > 0 || $customer_id == -1) {
      $json['hasAddress'] = 1;
      if ($this->user->isLogged() && $this->user->hasPermission('modify', 'sale/customer')) {
      $data['customer_id'] = $customer_id;
      $data['customer_group_id'] = $this->request->post['customer_group_id'];
      $data['safe'] = 0;
      $data['address_id'] = 0;
      $keys = array_keys($this->request->post);
      foreach ($keys as $key) {
      $value = $this->request->post[$key];
      if ($key == 'customer_addresses') {
      foreach ($value as $address) {
      if (isset($address['default']) && $address['default']) {
      $json['hasAddress'] = 2;
      $data['address_id'] = $address['address_id'];
      break;
      }
      }
      }
      if (strpos($key, 'customer_') === 0) {
      $dataKey = substr($key, 9);
      $data[$dataKey] = $value;
      }
      }

      $this->load->model('pos/pos');
      if ($customer_id > 0) {
      $this->model_pos_pos->editCustomer($customer_id, $data);
      } else {
      $customer_id = $this->model_pos_pos->addCustomer($data);
      }
      } else {
      $json['error']['warning'] = $this->language->get('error_permission');
      }
      } else {
      $keys = array_keys($this->request->post);
      foreach ($keys as $key) {
      $value = $this->request->post[$key];
      if (strpos($key, 'customer_') === 0) {
      $dataKey = substr($key, 9);
      $data[$dataKey] = $value;
      $json[$dataKey] = $value;
      }
      }
      }

      $order_id = (!empty($this->request->get['order_id'])) ? $this->request->get['order_id'] : false;
      $pos_return_id = (!empty($this->request->get['pos_return_id'])) ? $this->request->get['pos_return_id'] : false;
      $customer_group_id = (int)$this->request->post['customer_group_id'];
      $this->saveCustomerInfo($order_id, $pos_return_id, $data, $customer_id, $customer_group_id, $json);

      $this->response->setOutput(json_encode($json));
      } */

    public function checkCustomer() {
        $customer_id = false;
        $response = false;
        if (isset($this->request->get['customer_id'])) {

            $this->load->model('pos/pos');
            $customer_id = $this->request->get['customer_id'];
            $customer = $this->model_pos_pos->getCustomer($customer_id);
            if ($customer) {
                $response = true;
            }
        }
        echo json_encode($response);
    }

    public function addnewCustomer() {
        $this->load->model('pos/pos');

        $json = $this->request->post;
        $resp = array();
        $new_customer = false;
        $customer_id = (int) $this->request->post['customer_id'];

        $customer_email = isset($this->request->post['customer_email']) ? $this->request->post['customer_email'] : "";
        //if ($customer_email == "") return;
        $cust = $this->model_pos_pos->getCustomerByEmail($customer_email);
        if ($cust) {
            $msg = 'Warning: Customer with Email ' . $customer_email . '  already Exists.';
            $resp = array('msg' => $msg);
            $this->response->setOutput(json_encode($resp));
            return; //customer already exists, so does not do anything
        }


        $data = array();

        if ($customer_id > 0 || $customer_id == -1) {
            $json['hasAddress'] = 1;
            if ($this->user->isLogged() && ($this->user->hasPermission('modify', 'sale/customer') || $this->user->hasPermission('modify', 'customer/customer'))) {
                $data['customer_id'] = $customer_id;
                $data['customer_group_id'] = $this->request->post['customer_group_id'];
                $data['safe'] = 0;
                $data['address_id'] = 0;
                $keys = array_keys($this->request->post);
                foreach ($keys as $key) {
                    $value = $this->request->post[$key];
                    if ($key == 'customer_addresses') {
                        foreach ($value as $address) {
                            if (isset($address['default']) && $address['default']) {
                                $json['hasAddress'] = 2;
                                $data['address_id'] = $address['address_id'];
                                break;
                            }
                        }
                    }
                    if (strpos($key, 'customer_') === 0) {
                        $dataKey = substr($key, 9);
                        $data[$dataKey] = $value;
                    }
                }

                if (!empty($this->request->post['customer_resale_number'])) {
                    $this->session->data['resale_id_number'] = $this->request->post['customer_resale_number'];
                }

                if (isset($this->request->post['customer_sales_notes'])) {
                    $data['customer_sales_notes'] = $this->request->post['customer_sales_notes'];
                } else {
                    $data['customer_sales_notes'] = "";
                }

                if (isset($this->request->post['customer_image'])) {
                    $data['customer_image'] = $this->request->post['customer_image'];
                } else {
                    $data['customer_image'] = "";
                }



                if ($customer_id > 0) {
                    $this->model_pos_pos->editCustomer($customer_id, $data);
                } else {
                    $data['password'] = $this->random_password();
                    $customer_id = $this->model_pos_pos->addCustomer($data);
                    //echo 'new is added' . $customer_id;
                    $new_customer = true;
                }
            } else {
                $json['error']['warning'] = $this->language->get('error_permission');
            }
        } else {
            $keys = array_keys($this->request->post);
            foreach ($keys as $key) {
                $value = $this->request->post[$key];
                if (strpos($key, 'customer_') === 0) {
                    $dataKey = substr($key, 9);
                    $data[$dataKey] = $value;
                    $json[$dataKey] = $value;
                }
            }
        }

        $json['customer_id'] = $customer_id;

        $this->language->load('module/pos');
        $json['success'] = $this->language->get('text_order_success');

        $resp = array('msg' => 'Customer Created Successfully', 'customer_id' => $customer_id);
        $this->response->setOutput(json_encode($resp));
    }

    public function saveCustomer() {
        $json = $this->request->post;

        $new_customer = false;

        $customer_id = (int) $this->request->post['customer_id'];


        $data = array();

        if ($customer_id > 0 || $customer_id == -1) {
            $json['hasAddress'] = 1;
            if ($this->user->isLogged() && ($this->user->hasPermission('modify', 'sale/customer') || $this->user->hasPermission('modify', 'customer/customer'))) {
                $data['customer_id'] = $customer_id;
                $data['customer_group_id'] = $this->request->post['customer_group_id'];
                $data['safe'] = 0;
                $data['address_id'] = 0;
                $keys = array_keys($this->request->post);
                foreach ($keys as $key) {
                    $value = $this->request->post[$key];
                    if ($key == 'customer_addresses') {
                        foreach ($value as $address) {
                            if (isset($address['default']) && $address['default']) {
                                $json['hasAddress'] = 2;
                                $data['address_id'] = $address['address_id'];
                                break;
                            }
                        }
                    }
                    if (strpos($key, 'customer_') === 0) {
                        $dataKey = substr($key, 9);
                        $data[$dataKey] = $value;
                    }
                }

                if (!empty($this->request->post['customer_resale_number'])) {
                    $this->session->data['resale_id_number'] = $this->request->post['customer_resale_number'];
                }

                if (isset($this->request->post['customer_sales_notes'])) {
                    $data['customer_sales_notes'] = $this->request->post['customer_sales_notes'];
                } else {
                    $data['customer_sales_notes'] = "";
                }

                if (isset($this->request->post['customer_image'])) {
                    $data['customer_image'] = $this->request->post['customer_image'];
                } else {
                    $data['customer_image'] = "";
                }




                $this->load->model('pos/pos');
                if ($customer_id > 0) {
                    $this->model_pos_pos->editCustomer($customer_id, $data);
                } else {
                    $data['password'] = $this->random_password();
                    $customer_id = $this->model_pos_pos->addCustomer($data);
                    $new_customer = true;
                }
            } else {
                $json['error']['warning'] = $this->language->get('error_permission');
            }
        } else {
            $keys = array_keys($this->request->post);
            foreach ($keys as $key) {
                $value = $this->request->post[$key];
                if (strpos($key, 'customer_') === 0) {
                    $dataKey = substr($key, 9);
                    $data[$dataKey] = $value;
                    $json[$dataKey] = $value;
                }
            }
        }

        $order_id = (!empty($this->request->get['order_id'])) ? $this->request->get['order_id'] : false;
        $pos_return_id = (!empty($this->request->get['pos_return_id'])) ? $this->request->get['pos_return_id'] : false;
        $customer_group_id = (int) $this->request->post['customer_group_id'];
        $this->saveCustomerInfo($order_id, $pos_return_id, $data, $customer_id, $customer_group_id, $json, $new_customer);

        $session_discount_customer_group_id = isset($this->request->get['session_discount_customer_group_id']) ? $this->request->get['session_discount_customer_group_id'] : false;

        if (isset($this->session->data['discount_customer_group_id']) && $session_discount_customer_group_id == 'true') {
            $customer_group_id = $this->session->data['discount_customer_group_id'];
        } else {
            unset($this->session->data['discount_customer_group_id']);
        }

        $this->recalculateCartData($order_id, $customer_group_id);

        $this->response->setOutput(json_encode($json));
    }

    public function recalculateCartData($order_id, $customer_group_id) {
        $this->load->model('pos/pos');
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");
        $order_products = $query->rows;

        if ($order_products) {
            foreach ($order_products as $order_product) {
                $product_id = $order_product['product_id'];
                $quantity = $order_product['quantity'];
                $price = $order_product['price'];
                $unit_conversion_values = $order_product['unit_conversion_values'];
                //$new_price = $this->getCustomerGroupPriceOfProduct();
                if (!empty($unit_conversion_values) || $unit_conversion_values != 0) {
                    $sql_unit_sql = "SELECT unit_id, unit_value_id, convert_price FROM " . DB_PREFIX . "unit_conversion_product WHERE unit_conversion_product_id = $unit_conversion_values AND product_id = $product_id";
                    $res_unit_sql = $this->db->query($sql_unit_sql);
                    if ($res_unit_sql->num_rows > 0) {
                        $unit_id = $res_unit_sql->row['unit_id'];
                        $unit_value_id = $res_unit_sql->row['unit_value_id'];
                        $convert_price = $res_unit_sql->row['convert_price'];

                        $sql_unit_name = "SELECT name FROM " . DB_PREFIX . "unit_conversion WHERE unit_id = $unit_id";
                        $res_unit_name = $this->db->query($sql_unit_name);
                        $unit_name = $res_unit_name->row['name'];

                        $sql_unit_value_name = "SELECT name FROM " . DB_PREFIX . "unit_conversion_value WHERE unit_value_id = $unit_value_id";
                        $res_unit_value_name = $this->db->query($sql_unit_value_name);
                        $unit_value_name = $res_unit_value_name->row['name'];

                        $unit_data = array(
                            'unit_conversion_values' => $unit_conversion_values,
                            'unit_name' => $unit_name,
                            'unit_value_name' => $unit_value_name,
                            'convert_price' => $convert_price
                        );
                    }
                } //if(!empty($unit_conversion_values)

                if (isset($unit_data) && !empty($unit_data)) {
                    $combinePrice = $this->model_pos_pos->geProductCalculatedPrice($product_id, $quantity, $unit_data['convert_price'], $customer_group_id);
                } else {
                    $combinePrice = $this->model_pos_pos->geProductCalculatedPrice($product_id, $quantity, null, $customer_group_id);
                }

                $this->db->query("UPDATE " . DB_PREFIX . "order_product SET price = '" . (float) $combinePrice . "', total = '" . ((float) $combinePrice * (float) $quantity) . "' WHERE order_id = '" . (int) $order_id . "' AND product_id = '" . (int) $product_id . "'");
            }
        }
    }

    private function random_password($length = 8) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }

    private function saveCustomerInfo($order_id, $pos_return_id, $customer, $customer_id, $customer_group_id, &$json) {
        $customer_sql = "";
        if ($customer_id > 0 || $customer_id == -1) {
            $json['hasAddress'] = 1;

            $this->load->model('pos/pos');
            $customer_addresses = $this->model_pos_pos->getAddresses($customer_id);
            $customer_info = $this->model_pos_pos->getCustomer($customer_id);

            foreach ($customer_addresses as $address) {
                if ($customer_info['address_id'] == $address['address_id']) {
                    if (!empty($this->request->get['order_id'])) {
                        // update the order shipping address and payment address
                        $customer_sql .= ", payment_firstname = '" . $this->db->escape($address['firstname']) . "', payment_lastname = '" . $this->db->escape($address['lastname']) . "', payment_company = '" . $this->db->escape($address['company']) . "', payment_address_1 = '" . $this->db->escape($address['address_1']) . "', payment_address_2 = '" . $this->db->escape($address['address_2']) . "', payment_city = '" . $this->db->escape($address['city']) . "', payment_postcode = '" . $this->db->escape($address['postcode']) . "', payment_country = '" . $this->db->escape($address['country']) . "', payment_country_id = '" . (int) $address['country_id'] . "', payment_zone = '" . $this->db->escape($address['zone']) . "', payment_zone_id = '" . (int) $address['zone_id'] . "', shipping_firstname = '" . $this->db->escape($address['firstname']) . "', shipping_lastname = '" . $this->db->escape($address['lastname']) . "',  shipping_company = '" . $this->db->escape($address['company']) . "', shipping_address_1 = '" . $this->db->escape($address['address_1']) . "', shipping_address_2 = '" . $this->db->escape($address['address_2']) . "', shipping_city = '" . $this->db->escape($address['city']) . "', shipping_postcode = '" . $this->db->escape($address['postcode']) . "', shipping_country = '" . $this->db->escape($address['country']) . "', shipping_country_id = '" . (int) $address['country_id'] . "', shipping_zone = '" . $this->db->escape($address['zone']) . "', shipping_zone_id = '" . (int) $address['zone_id'] . "'";
                    }
                    $json['hasAddress'] = 2;
                    $json['customer_address_id'] = $address['address_id'];
                    $json['country_id'] = $address['country_id'];
                    $json['zone_id'] = $address['zone_id'];
                    break;
                }
            }
            if ($json['hasAddress'] == 1 && !empty($this->request->get['order_id'])) {
                $customer_sql .= ", payment_firstname = '', payment_lastname = '', payment_company = '', payment_address_1 = '', payment_address_2 = '', payment_city = '', payment_postcode = '', payment_country = '', payment_country_id = '', payment_zone = '', payment_zone_id = '', shipping_firstname = '', shipping_lastname = '',  shipping_company = '', shipping_address_1 = '', shipping_address_2 = '', shipping_city = '', shipping_postcode = '', shipping_country = '', shipping_country_id = '', shipping_zone = '', shipping_zone_id = ''";
            }
            try {
                //$this->model_pos_pos->approve($data['customer_id']);
            } catch (Exception $e) {

            }
            // add for Add Customer end
            // add for Edit order address begin
            $json['customer_addresses'] = $customer_addresses;
            // add for Edit order address end
        }

        $json['customer_id'] = $customer_id;
        if (!empty($order_id)) {
            $store_id = $this->getStoreId();
            $sql = "UPDATE `" . DB_PREFIX . "order` SET store_id = '" . $store_id . "', customer_group_id = '" . $customer_group_id . "', firstname = '" . $this->db->escape($customer['firstname']) . "', lastname = '" . $this->db->escape($customer['lastname']) . "', email = '" . $this->db->escape($customer['email']) . "', telephone = '" . $this->db->escape($customer['telephone']) . "', fax = '" . $this->db->escape($customer['fax']) . "', date_modified = NOW()";
            if ($customer_id > 0 || $customer_id == -1) {
                $sql .= $customer_sql;
            }
            $sql .= ", customer_id = '" . $customer_id . "' WHERE order_id = '" . (int) $order_id . "'";
            $this->db->query($sql);

            // add for customer loyalty card begin
            $new_reward = 0;
            // get reward points for new customer_group_id
            $order_product_query = $this->db->query("SELECT product_id, quantity, order_product_id FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . (int) $order_id . "'");
            if ($order_product_query->rows) {
                foreach ($order_product_query->rows as $row) {
                    $reward_query = $this->db->query("SELECT points FROM `" . DB_PREFIX . "product_reward` WHERE product_id = '" . $row['product_id'] . "' AND customer_group_id = '" . $customer_group_id . "'");
                    $reward = $reward_query->row ? $reward_query->row['points'] : 0;
                    $this->db->query("UPDATE `" . DB_PREFIX . "order_product` SET reward = '" . $row['quantity'] * $reward . "' WHERE order_product_id = '" . $row['order_product_id'] . "'");
                    $new_reward += $row['quantity'] * $reward;
                }
            }
            $reward_query = $this->db->query("SELECT customer_reward_id FROM `" . DB_PREFIX . "customer_reward` WHERE order_id = '" . (int) $order_id . "'");
            if ($reward_query->row) {
                $this->db->query("UPDATE `" . DB_PREFIX . "customer_reward` SET points = '" . $new_reward . "', date_added = NOW() WHERE customer_reward_id = '" . $reward_query->row['customer_reward_id'] . "'");
            } else {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "customer_reward` SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', points = '" . $new_reward . "', description = 'Order ID: " . (int) $order_id . "', date_added = NOW()");
            }
            // add for customer loyalty card end

            $this->language->load('module/pos');
            $json['success'] = $this->language->get('text_order_success');
        } elseif (!empty($pos_return_id)) {
            $this->db->query("UPDATE `" . DB_PREFIX . "pos_return` SET firstname = '" . $this->db->escape($customer['firstname']) . "', lastname = '" . $this->db->escape($customer['lastname']) . "', email = '" . $this->db->escape($customer['email']) . "', telephone = '" . $this->db->escape($customer['telephone']) . "', date_modified = NOW(), customer_id = '" . $customer_id . "' WHERE pos_return_id = '" . (int) $pos_return_id . "'");
            $this->db->query("UPDATE `" . DB_PREFIX . "return` SET firstname = '" . $this->db->escape($customer['firstname']) . "', lastname = '" . $this->db->escape($customer['lastname']) . "', email = '" . $this->db->escape($customer['email']) . "', telephone = '" . $this->db->escape($customer['telephone']) . "', date_modified = NOW(), customer_id = '" . $customer_id . "' WHERE pos_return_id = '" . (int) $pos_return_id . "'");

            $this->language->load('module/pos');
            $json['success'] = $this->language->get('text_return_success');
        }
    }

    // add for SKU begin
    public function handleSKUEntry() {
        $json = array();

        if (isset($this->request->get['sku'])) {
            $this->load->model('catalog/product');
            $this->load->model('catalog/option');
            $this->load->model('tool/image');

            $result = $this->getProductBySKU($this->request->get['sku']);

            if ($result) {
                $price = $result['price'];
                $product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int) $result['product_id'] . "' AND customer_group_id = '" . (int) $this->request->get['customer_group_id'] . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
                if ($product_special_query->num_rows) {
                    $price = $product_special_query->row['price'];
                }
                // Reward Points
                $product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int) $result['product_id'] . "' AND customer_group_id = '" . (int) $this->request->get['customer_group_id'] . "'");

                if ($product_reward_query->num_rows) {
                    $reward = $product_reward_query->row['points'];
                } else {
                    $reward = 0;
                }
                $json['product_id'] = $result['product_id'];
                $json['name'] = strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'));
                $json['model'] = $result['model'];
                $json['points'] = $result['points'];
                $json['reward_points'] = $reward;
                $json['price'] = $price;
                $json['tax'] = $this->getTax($price, $result['tax_class_id'], $this->request->get['customer_group_id']);
                $json['image'] = (!empty($result['image'])) ? $this->model_tool_image->resize($result['image'], 180, 180) : $this->model_tool_image->resize('no_image.jpg', 180, 180);

                $product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

                if (!empty($product_options)) {
                    $option_data = array();

                    foreach ($product_options as $product_option) {
                        $option_info = $this->model_catalog_option->getOption($product_option['option_id']);

                        if ($option_info) {
                            if ($option_info['type'] == 'select' || $option_info['type'] == 'radio' || $option_info['type'] == 'checkbox' || $option_info['type'] == 'image') {
                                $option_value_data = array();

                                foreach ($product_option['product_option_value'] as $product_option_value) {
                                    $option_value_name = '';
                                    if (version_compare(VERSION, '1.5.5', '<')) {
                                        $option_value_name = $product_option_value['name'];
                                    } else {
                                        $option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);
                                        if ($option_value_info) {
                                            $option_value_name = $option_value_info['name'];
                                        }
                                    }

                                    $option_value_data[] = array(
                                        'product_option_value_id' => $product_option_value['product_option_value_id'],
                                        'option_value_id' => $product_option_value['option_value_id'],
                                        'name' => $option_value_name,
                                        'price' => (float) $product_option_value['price'] ? $this->currency->formatFront($product_option_value['price'], $this->config->get('config_currency')) : false,
                                        'price_prefix' => $product_option_value['price_prefix']
                                    );
                                }

                                $option_data[] = array(
                                    'product_option_id' => $product_option['product_option_id'],
                                    'option_id' => $product_option['option_id'],
                                    'name' => $option_info['name'],
                                    'type' => $option_info['type'],
                                    'option_value' => $option_value_data,
                                    'required' => $product_option['required']
                                );
                            } elseif ($option_info['type'] != 'file') {
                                $option_data[] = array(
                                    'product_option_id' => $product_option['product_option_id'],
                                    'option_id' => $product_option['option_id'],
                                    'name' => $option_info['name'],
                                    'type' => $option_info['type'],
                                    'option_value' => $product_option['value'],
                                    'required' => $product_option['required']
                                );
                            }
                        }
                    }

                    $json['option'] = $option_data;
                }
                $this->load->model('catalog/unit_conversion');
                $unit_data = $this->model_catalog_unit_conversion->getProductUnits($result['product_id']);
                $DefaultUnitdata = $this->model_catalog_unit_conversion->getDefaultUnitDetails($result['product_id']);

                if (isset($unit_data)) {
                    $json['unit_datas'] = $unit_data;
                    $json['DefaultUnitdata'] = $DefaultUnitdata;
                } else {
                    $json['unit_datas'] = '';
                    $json['DefaultUnitdata'] = '';
                }


                // add for Weight based price begin
                $json['weight_price'] = $result['weight_price'];
                $json['weight_name'] = $result['weight_name'];
                // add for Weight based price end
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    private function getProductBySKU($sku) {
        $query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "product` p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.sku = '" . $this->db->escape($sku) . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

        return $query->row;
    }

    // add for SKU end
    // add for UPC begin
    public function handleUPCEntry() {
        $json = array();

        if (isset($this->request->get['upc'])) {
            $this->load->model('catalog/product');
            $this->load->model('catalog/option');
            $this->load->model('tool/image');

            $result = $this->getProductByUPC($this->request->get['upc']);

            if ($result) {
                $price = $result['price'];
                $product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int) $result['product_id'] . "' AND customer_group_id = '" . (int) $this->request->get['customer_group_id'] . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
                if ($product_special_query->num_rows) {
                    $price = $product_special_query->row['price'];
                }
                // Reward Points
                $product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int) $result['product_id'] . "' AND customer_group_id = '" . (int) $this->request->get['customer_group_id'] . "'");

                if ($product_reward_query->num_rows) {
                    $reward = $product_reward_query->row['points'];
                } else {
                    $reward = 0;
                }

                $json['product_id'] = $result['product_id'];
                $json['name'] = strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'));
                $json['model'] = $result['model'];
                $json['price'] = $price;
                $json['tax'] = $this->getTax($price, $result['tax_class_id'], $this->request->get['customer_group_id']);
                $json['image'] = (!empty($result['image'])) ? $this->model_tool_image->resize($result['image'], 180, 180) : $this->model_tool_image->resize('no_image.jpg', 180, 180);
                $json['points'] = $result['points'];
                $json['reward_points'] = $reward;

                $product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

                if (!empty($product_options)) {
                    $option_data = array();

                    foreach ($product_options as $product_option) {
                        $option_info = $this->model_catalog_option->getOption($product_option['option_id']);

                        if ($option_info) {
                            if ($option_info['type'] == 'select' || $option_info['type'] == 'radio' || $option_info['type'] == 'checkbox' || $option_info['type'] == 'image') {
                                $option_value_data = array();

                                foreach ($product_option['product_option_value'] as $product_option_value) {
                                    $option_value_name = '';
                                    if (version_compare(VERSION, '1.5.5', '<')) {
                                        $option_value_name = $product_option_value['name'];
                                    } else {
                                        $option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);
                                        if ($option_value_info) {
                                            $option_value_name = $option_value_info['name'];
                                        }
                                    }

                                    $option_value_data[] = array(
                                        'product_option_value_id' => $product_option_value['product_option_value_id'],
                                        'option_value_id' => $product_option_value['option_value_id'],
                                        'name' => $option_value_name,
                                        'price' => (float) $product_option_value['price'] ? $this->currency->formatFront($product_option_value['price'], $this->config->get('config_currency')) : false,
                                        'price_prefix' => $product_option_value['price_prefix']
                                    );
                                }

                                $option_data[] = array(
                                    'product_option_id' => $product_option['product_option_id'],
                                    'option_id' => $product_option['option_id'],
                                    'name' => $option_info['name'],
                                    'type' => $option_info['type'],
                                    'option_value' => $option_value_data,
                                    'required' => $product_option['required']
                                );
                            } elseif ($option_info['type'] != 'file') {
                                $option_data[] = array(
                                    'product_option_id' => $product_option['product_option_id'],
                                    'option_id' => $product_option['option_id'],
                                    'name' => $option_info['name'],
                                    'type' => $option_info['type'],
                                    'option_value' => $product_option['value'],
                                    'required' => $product_option['required']
                                );
                            }
                        }
                    }

                    $json['option'] = $option_data;
                }

                $this->load->model('catalog/unit_conversion');
                $unit_data = $this->model_catalog_unit_conversion->getProductUnits($result['product_id']);
                $DefaultUnitdata = $this->model_catalog_unit_conversion->getDefaultUnitDetails($result['product_id']);

                if (isset($unit_data)) {
                    $json['unit_datas'] = $unit_data;
                    $json['DefaultUnitdata'] = $DefaultUnitdata;
                } else {
                    $json['unit_datas'] = '';
                    $json['DefaultUnitdata'] = '';
                }


                if (isset($unit_data)) {
                    $json['unit_datas'] = $unit_data;
                    $json['DefaultUnitdata'] = $DefaultUnitdata;
                } else {
                    $json['unit_datas'] = '';
                    $json['DefaultUnitdata'] = '';
                }

                // add for Weight based price begin
                $json['weight_price'] = $result['weight_price'];
                $json['weight_name'] = $result['weight_name'];
                // add for Weight based price end
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    private function getProductByUPC($upc) {
        if (strlen($upc) == 13) {
            $query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "product` p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.ean = '" . $upc . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'");
        } else {
            $query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "product` p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.upc = '" . $upc . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'");
        }

        return $query->row;
    }

    // add for UPC end
    // add for MPN begin
    public function handleMPNEntry() {
        $json = array();

        if (isset($this->request->get['mpn'])) {
            $this->load->model('catalog/product');
            $this->load->model('catalog/option');
            $this->load->model('tool/image');

            $result = $this->getProductByMPN($this->request->get['mpn']);

            if ($result) {
                $price = $result['price'];
                $product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int) $result['product_id'] . "' AND customer_group_id = '" . (int) $this->request->get['customer_group_id'] . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
                if ($product_special_query->num_rows) {
                    $price = $product_special_query->row['price'];
                }
                // Reward Points
                $product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int) $result['product_id'] . "' AND customer_group_id = '" . (int) $this->request->get['customer_group_id'] . "'");

                if ($product_reward_query->num_rows) {
                    $reward = $product_reward_query->row['points'];
                } else {
                    $reward = 0;
                }
                $json['product_id'] = $result['product_id'];
                $json['name'] = strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'));
                $json['model'] = $result['model'];
                $json['points'] = $result['points'];
                $json['reward_points'] = $reward;
                $json['price'] = $price;
                $json['tax'] = $this->getTax($price, $result['tax_class_id'], $this->request->get['customer_group_id']);
                $json['image'] = (!empty($result['image'])) ? $this->model_tool_image->resize($result['image'], 180, 180) : $this->model_tool_image->resize('no_image.jpg', 180, 180);

                $product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

                if (!empty($product_options)) {
                    $option_data = array();

                    foreach ($product_options as $product_option) {
                        $option_info = $this->model_catalog_option->getOption($product_option['option_id']);

                        if ($option_info) {
                            if ($option_info['type'] == 'select' || $option_info['type'] == 'radio' || $option_info['type'] == 'checkbox' || $option_info['type'] == 'image') {
                                $option_value_data = array();

                                foreach ($product_option['product_option_value'] as $product_option_value) {
                                    $option_value_name = '';
                                    if (version_compare(VERSION, '1.5.5', '<')) {
                                        $option_value_name = $product_option_value['name'];
                                    } else {
                                        $option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);
                                        if ($option_value_info) {
                                            $option_value_name = $option_value_info['name'];
                                        }
                                    }

                                    $option_value_data[] = array(
                                        'product_option_value_id' => $product_option_value['product_option_value_id'],
                                        'option_value_id' => $product_option_value['option_value_id'],
                                        'name' => $option_value_name,
                                        'price' => (float) $product_option_value['price'] ? $this->currency->formatFront($product_option_value['price'], $this->config->get('config_currency')) : false,
                                        'price_prefix' => $product_option_value['price_prefix']
                                    );
                                }

                                $option_data[] = array(
                                    'product_option_id' => $product_option['product_option_id'],
                                    'option_id' => $product_option['option_id'],
                                    'name' => $option_info['name'],
                                    'type' => $option_info['type'],
                                    'option_value' => $option_value_data,
                                    'required' => $product_option['required']
                                );
                            } elseif ($option_info['type'] != 'file') {
                                $option_data[] = array(
                                    'product_option_id' => $product_option['product_option_id'],
                                    'option_id' => $product_option['option_id'],
                                    'name' => $option_info['name'],
                                    'type' => $option_info['type'],
                                    'option_value' => $product_option['value'],
                                    'required' => $product_option['required']
                                );
                            }
                        }
                    }

                    $json['option'] = $option_data;
                }
                $this->load->model('catalog/unit_conversion');
                $unit_data = $this->model_catalog_unit_conversion->getProductUnits($result['product_id']);
                $DefaultUnitdata = $this->model_catalog_unit_conversion->getDefaultUnitDetails($result['product_id']);

                if (isset($unit_data)) {
                    $json['unit_datas'] = $unit_data;
                    $json['DefaultUnitdata'] = $DefaultUnitdata;
                } else {
                    $json['unit_datas'] = '';
                    $json['DefaultUnitdata'] = '';
                }


                if (isset($unit_data)) {
                    $json['unit_datas'] = $unit_data;
                    $json['DefaultUnitdata'] = $DefaultUnitdata;
                } else {
                    $json['unit_datas'] = '';
                    $json['DefaultUnitdata'] = '';
                }

                // add for Weight based price begin
                $json['weight_price'] = $result['weight_price'];
                $json['weight_name'] = $result['weight_name'];
                // add for Weight based price end
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    private function getProductByMPN($mpn) {
        $query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "product` p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.mpn = '" . $mpn . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

        return $query->row;
    }

    // add for MPN end

    public function invoice() {
        $this->load->language('sale/order');
        $this->load->language('module/pos');

        $data['title'] = $this->language->get('text_invoice');
        $data['logo'] = $this->config->get('POS_p_logo');
        $data['term_n_cond'] = $this->language->get('term_n_cond_default');
        if ($this->config->get('POS_p_term_n_cond')) {
            $data['term_n_cond'] = $this->config->get('POS_p_term_n_cond');
        }

        if ($this->request->server['HTTPS']) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }

        $data['direction'] = $this->language->get('direction');
        $data['lang'] = $this->language->get('code');

        $data['text_invoice'] = $this->language->get('text_invoice');
        $data['text_order_detail'] = $this->language->get('text_order_detail');
        $data['text_order_id'] = $this->language->get('text_order_id');
        if (isset($this->request->get['work_mode']) && $this->request->get['work_mode'] == '2') {
            $data['text_invoice'] = $this->language->get('text_quote');
            $data['text_order_detail'] = $this->language->get('text_quote_detail');
            $data['text_order_id'] = $this->language->get('column_quote_id');
        }
        $data['text_invoice_no'] = $this->language->get('text_invoice_no');
        $data['text_invoice_date'] = $this->language->get('text_invoice_date');
        $data['text_date_added'] = $this->language->get('text_date_added');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_fax'] = $this->language->get('text_fax');
        $data['text_email'] = $this->language->get('text_email');
        $data['text_website'] = $this->language->get('text_website');
        $data['text_to'] = $this->language->get('text_to');
        $data['text_ship_to'] = $this->language->get('text_ship_to');
        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_shipping_method'] = $this->language->get('text_shipping_method');

        $data['column_product'] = $this->language->get('column_product');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_comment'] = $this->language->get('column_comment');
        $data['column_type'] = $this->language->get('text_order_payment_type');
        $data['column_amount'] = $this->language->get('column_amount');
        $data['column_note'] = $this->language->get('column_note');

        $this->load->model('sale/order');

        $this->load->model('setting/setting');

        $data['order'] = array();

        $order_id = $this->request->get['order_id'];
        $order_info = $this->model_sale_order->getOrder($order_id);

        if ($order_info) {
            $this->load->model('pos/pos');
            $username = $this->model_pos_pos->get_full_username($this->user->getId());
            $data['user_info'] = sprintf($this->language->get('user_info'), $username);
            $this->model_pos_pos->unsetOnlineOrderIndicator($order_id);

            $store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

            if ($store_info) {
                $store_address = $store_info['config_address'];
                $store_email = $store_info['config_email'];
                $store_telephone = $store_info['config_telephone'];
                $store_fax = $store_info['config_fax'];
            } else {
                $store_address = $this->config->get('config_address');
                $store_email = $this->config->get('config_email');
                $store_telephone = $this->config->get('config_telephone');
                $store_fax = $this->config->get('config_fax');
            }

            if ($order_info['invoice_no']) {
                $invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
            } else {
                $invoice_no = '';
            }

            if ($order_info['payment_address_format']) {
                $format = $order_info['payment_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['payment_firstname'],
                'lastname' => $order_info['payment_lastname'],
                'company' => $order_info['payment_company'],
                'address_1' => $order_info['payment_address_1'],
                'address_2' => $order_info['payment_address_2'],
                'city' => $order_info['payment_city'],
                'postcode' => $order_info['payment_postcode'],
                'zone' => $order_info['payment_zone'],
                'zone_code' => $order_info['payment_zone_code'],
                'country' => $order_info['payment_country']
            );

            $payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            if (( empty($payment_address) || strlen($payment_address) < 5 ) && $order_info['customer_id']) {
                $payment_address = $this->model_pos_pos->getAddressByCustomerId($order_info['customer_id']);
            }

            if ($order_info['shipping_address_format']) {
                $format = $order_info['shipping_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['shipping_firstname'],
                'lastname' => $order_info['shipping_lastname'],
                'company' => $order_info['shipping_company'],
                'address_1' => $order_info['shipping_address_1'],
                'address_2' => $order_info['shipping_address_2'],
                'city' => $order_info['shipping_city'],
                'postcode' => $order_info['shipping_postcode'],
                'zone' => $order_info['shipping_zone'],
                'zone_code' => $order_info['shipping_zone_code'],
                'country' => $order_info['shipping_country']
            );

            $shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            if (( empty($shipping_address) || strlen($shipping_address) < 5 ) && $order_info['customer_id']) {
                $shipping_address = $this->model_pos_pos->getAddressByCustomerId($order_info['customer_id']);
            }


            if ($shipping_address == "" || $payment_address == "") {
                $cust = $this->model_pos_pos->getCustomer($order_info['customer_id']);
            }

            if ($shipping_address == "") {
                if ($cust) {
                    $shipping_address = $cust['firstname'] . ' ' . $cust['lastname'] . ' (' . $cust['email'] . ') ,Telephone # ' . $cust['telephone'];
                }
            }

            if ($payment_address == "") {
                if ($cust) {
                    $payment_address = $cust['firstname'] . ' ' . $cust['lastname'] . ' (' . $cust['email'] . ') ,Telephone # ' . $cust['telephone'];
                }
            }

            $this->load->model('tool/upload');

            $product_data = array();

            $this->load->model('pos/pos');
            $products = $this->model_pos_pos->getOrderProducts($order_id);

            $this->load->model('tool/image');

            foreach ($products as $product) {
                $option_data = array();

                $options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);
                $sns = $this->model_pos_pos->getSoldProductSN($product['order_product_id']);
                $weight = 1;
                if (!empty($product['weight_price'])) {
                    $weigth = (int) $product['weight'];
                }

                foreach ($options as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                        if ($upload_info) {
                            $value = $upload_info['name'];
                        } else {
                            $value = '';
                        }
                    }

                    $option_data[] = array(
                        'name' => $option['name'],
                        'value' => $value
                    );
                }

                $image = $this->model_tool_image->resize($this->getProductImage($product['product_id']), $this->config->get('pdf_invoice_thumbwidth'), $this->config->get('pdf_invoice_thumbheight'));

                $image = str_replace('https://', 'http://', $image);


                $product_data[] = array(
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'image' => $image,
                    'option' => $option_data,
                    'sns' => $sns,
                    'weight_price' => $product['weight_price'],
                    'weight_name' => $product['weight_name'],
                    'weight' => $product['weight'],
                    'quantity' => $product['quantity'],
                    'price' => $this->currency->formatFront($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'total' => $this->currency->formatFront($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
                );
            }

            $total_data = array();

            $totals = $this->model_sale_order->getOrderTotals($order_id);

            foreach ($totals as $total) {
                $total_data[] = array(
                    'title' => $total['title'],
                    'text' => $this->currency->formatFront($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                );
            }

            $data['payments'] = array();

            $order_payments = $this->model_pos_pos->retrieveOrderPayments($order_id);
            if ($order_payments) {
                foreach ($order_payments as $order_payment) {
                    // update for customer loyalty card begin
                    $amount = $order_payment['tendered_amount'];
                    if ($order_payment['payment_type'] == $this->language->get('text_reward_points') && strpos($order_payment['payment_note'], ',') !== false) {
                        // it's the opencart reward point payment
                        $parts = explode('|', $order_payment['payment_note']);
                        if ($parts) {
                            // deduct the reward points from the current order if the reward points are used to pay the order product as we already set the reward points in the earlier stage
                            $total_value = 0;
                            foreach ($parts as $part) {
                                if (!empty($part)) {
                                    $reward_details = explode(',', $part);
                                    if ($reward_details) {
                                        $order_product_id = $reward_details[0];
                                        $quantity = $reward_details[1];

                                        $order_product_query = $this->db->query("SELECT price, tax FROM `" . DB_PREFIX . "order_product` WHERE order_product_id = '" . (int) $order_product_id . "'");
                                        $total_value += (int) $quantity * ((float) $order_product_query->row['price'] + (float) $order_product_query->row['tax']);
                                    }
                                }
                            }

                            $amount = $total_value;
                        }
                    }
                    $data['payments'][] = array(
                        'type' => $order_payment['payment_type'],
                        'amount_float' => $amount,
                        'amount' => $this->currency->formatFront($amount, $order_info['currency_code'], $order_info['currency_value']),
                        'note' => ($order_payment['payment_type'] == $this->language->get('text_reward_points')) ? '' : $order_payment['payment_note']
                    );
                }
            }
            $order_status = $this->getOrderStatusName($order_info['order_status_id']);
            $data['order'] = array(
                'order_id' => $order_id,
                'invoice_no' => $invoice_no,
                'date_added' => date("m/d/Y h:i:s A", strtotime($order_info['date_added'])),
                'store_name' => $order_info['store_name'],
                'store_url' => rtrim($order_info['store_url'], '/'),
                'store_address' => nl2br($store_address),
                'store_email' => $store_email,
                'store_telephone' => $store_telephone,
                'store_fax' => $store_fax,
                'email' => $order_info['email'],
                'telephone' => $order_info['telephone'],
                'shipping_address' => $shipping_address,
                'shipping_method' => $order_info['shipping_method'],
                'payment_address' => $payment_address,
                'payment_method' => $order_info['payment_method'],
                'product' => $product_data,
                'total' => $total_data,
                'order_status' => $order_status,
                'comment' => nl2br($order_info['comment'])
            );
        }

        $data['text_order_status'] = "Order Status:";
        $data['text_order_date'] = "Date Added:";
        $data['text_shipping_method'] = "Shipping Method:";
        $data['text_to'] = "Payment Address";
        $data['text_ship_to'] = "Ship To";
        $data['text_change'] = "Change";
        $data['column_quantity'] = "Quantity";
        $data['column_price'] = "Unit Price";
        $data['column_total'] = "Total";
        $data['column_comment'] = "Comment";
        $data['language'] = "en";
        if (isset($this->request->get['customer_order_invoice']) && $this->request->get['customer_order_invoice'] == '1') {
            $this->response->setOutput($this->load->view('pos/customer_order_invoice.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('pos/order_invoice_new.tpl', $data));
        }
    }

    public function getOrderStatusName($order_status_id) {
        $query = $this->db->query("SELECT name FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int) $order_status_id . "' AND language_id = 1");
        return $query->row ? $query->row['name'] : "";
    }

    private function getProductImage($product_id) {
        $query = $this->db->query("SELECT image FROM " . DB_PREFIX . "product WHERE product_id = '" . (int) $product_id . "' LIMIT 1");
        return isset($query->row['image']) ? $query->row['image'] : '';
    }

    public function invoicebackup() {
        $this->load->language('sale/order');
        $this->load->language('module/pos');

        $data['title'] = $this->language->get('text_invoice');

        if ($this->request->server['HTTPS']) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }

        $data['direction'] = $this->language->get('direction');
        $data['lang'] = $this->language->get('code');

        $data['text_invoice'] = $this->language->get('text_invoice');
        $data['text_order_detail'] = $this->language->get('text_order_detail');
        $data['text_order_id'] = $this->language->get('text_order_id');
        if (isset($this->request->get['work_mode']) && $this->request->get['work_mode'] == '2') {
            $data['text_invoice'] = $this->language->get('text_quote');
            $data['text_order_detail'] = $this->language->get('text_quote_detail');
            $data['text_order_id'] = $this->language->get('column_quote_id');
        }
        $data['text_invoice_no'] = $this->language->get('text_invoice_no');
        $data['text_invoice_date'] = $this->language->get('text_invoice_date');
        $data['text_date_added'] = $this->language->get('text_date_added');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_fax'] = $this->language->get('text_fax');
        $data['text_email'] = $this->language->get('text_email');
        $data['text_website'] = $this->language->get('text_website');
        $data['text_to'] = $this->language->get('text_to');
        $data['text_ship_to'] = $this->language->get('text_ship_to');
        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_shipping_method'] = $this->language->get('text_shipping_method');

        $data['column_product'] = $this->language->get('column_product');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_comment'] = $this->language->get('column_comment');
        $data['column_type'] = $this->language->get('text_order_payment_type');
        $data['column_amount'] = $this->language->get('column_amount');
        $data['column_note'] = $this->language->get('column_note');

        $this->load->model('sale/order');

        $this->load->model('setting/setting');

        $data['order'] = array();

        $order_id = $this->request->get['order_id'];
        $order_info = $this->model_sale_order->getOrder($order_id);

        if ($order_info) {
            $this->load->model('pos/pos');
            $this->model_pos_pos->unsetOnlineOrderIndicator($order_id);

            $store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

            if ($store_info) {
                $store_address = $store_info['config_address'];
                $store_email = $store_info['config_email'];
                $store_telephone = $store_info['config_telephone'];
                $store_fax = $store_info['config_fax'];
            } else {
                $store_address = $this->config->get('config_address');
                $store_email = $this->config->get('config_email');
                $store_telephone = $this->config->get('config_telephone');
                $store_fax = $this->config->get('config_fax');
            }

            if ($order_info['invoice_no']) {
                $invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
            } else {
                $invoice_no = '';
            }

            if ($order_info['payment_address_format']) {
                $format = $order_info['payment_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['payment_firstname'],
                'lastname' => $order_info['payment_lastname'],
                'company' => $order_info['payment_company'],
                'address_1' => $order_info['payment_address_1'],
                'address_2' => $order_info['payment_address_2'],
                'city' => $order_info['payment_city'],
                'postcode' => $order_info['payment_postcode'],
                'zone' => $order_info['payment_zone'],
                'zone_code' => $order_info['payment_zone_code'],
                'country' => $order_info['payment_country']
            );

            $payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            if (( empty($payment_address) || strlen($payment_address) < 5 ) && $order_info['customer_id']) {
                $payment_address = $this->model_pos_pos->getAddressByCustomerId($order_info['customer_id']);
            }

            if ($order_info['shipping_address_format']) {
                $format = $order_info['shipping_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['shipping_firstname'],
                'lastname' => $order_info['shipping_lastname'],
                'company' => $order_info['shipping_company'],
                'address_1' => $order_info['shipping_address_1'],
                'address_2' => $order_info['shipping_address_2'],
                'city' => $order_info['shipping_city'],
                'postcode' => $order_info['shipping_postcode'],
                'zone' => $order_info['shipping_zone'],
                'zone_code' => $order_info['shipping_zone_code'],
                'country' => $order_info['shipping_country']
            );

            $shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            if (( empty($shipping_address) || strlen($shipping_address) < 5 ) && $order_info['customer_id']) {
                $shipping_address = $this->model_pos_pos->getAddressByCustomerId($order_info['customer_id']);
            }

            $this->load->model('tool/upload');

            $product_data = array();

            $this->load->model('pos/pos');
            $products = $this->model_pos_pos->getOrderProducts($order_id);

            foreach ($products as $product) {
                $option_data = array();

                $options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);
                $sns = $this->model_pos_pos->getSoldProductSN($product['order_product_id']);
                $weight = 1;
                if (!empty($product['weight_price'])) {
                    $weigth = (int) $product['weight'];
                }

                foreach ($options as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                        if ($upload_info) {
                            $value = $upload_info['name'];
                        } else {
                            $value = '';
                        }
                    }

                    $option_data[] = array(
                        'name' => $option['name'],
                        'value' => $value
                    );
                }

                $product_data[] = array(
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'option' => $option_data,
                    'sns' => $sns,
                    'weight_price' => $product['weight_price'],
                    'weight_name' => $product['weight_name'],
                    'weight' => $product['weight'],
                    'quantity' => $product['quantity'],
                    'price' => $this->currency->formatFront($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'total' => $this->currency->formatFront($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
                );
            }

            $total_data = array();

            $totals = $this->model_sale_order->getOrderTotals($order_id);

            foreach ($totals as $total) {
                $total_data[] = array(
                    'title' => $total['title'],
                    'text' => $this->currency->formatFront($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                );
            }

            $data['payments'] = array();

            $order_payments = $this->model_pos_pos->retrieveOrderPayments($order_id);
            if ($order_payments) {
                foreach ($order_payments as $order_payment) {
                    // update for customer loyalty card begin
                    $amount = $order_payment['tendered_amount'];
                    if ($order_payment['payment_type'] == $this->language->get('text_reward_points') && strpos($order_payment['payment_note'], ',') !== false) {
                        // it's the opencart reward point payment
                        $parts = explode('|', $order_payment['payment_note']);
                        if ($parts) {
                            // deduct the reward points from the current order if the reward points are used to pay the order product as we already set the reward points in the earlier stage
                            $total_value = 0;
                            foreach ($parts as $part) {
                                if (!empty($part)) {
                                    $reward_details = explode(',', $part);
                                    if ($reward_details) {
                                        $order_product_id = $reward_details[0];
                                        $quantity = $reward_details[1];

                                        $order_product_query = $this->db->query("SELECT price, tax FROM `" . DB_PREFIX . "order_product` WHERE order_product_id = '" . (int) $order_product_id . "'");
                                        $total_value += (int) $quantity * ((float) $order_product_query->row['price'] + (float) $order_product_query->row['tax']);
                                    }
                                }
                            }

                            $amount = $total_value;
                        }
                    }
                    $data['payments'][] = array(
                        'type' => $order_payment['payment_type'],
                        'amount_float' => $amount,
                        'amount' => $this->currency->formatFront($amount, $order_info['currency_code'], $order_info['currency_value']),
                        'note' => ($order_payment['payment_type'] == $this->language->get('text_reward_points')) ? '' : $order_payment['payment_note']
                    );
                }
            }

            $data['order'] = array(
                'order_id' => $order_id,
                'invoice_no' => $invoice_no,
                'date_added' => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
                'store_name' => $order_info['store_name'],
                'store_url' => rtrim($order_info['store_url'], '/'),
                'store_address' => nl2br($store_address),
                'store_email' => $store_email,
                'store_telephone' => $store_telephone,
                'store_fax' => $store_fax,
                'email' => $order_info['email'],
                'telephone' => $order_info['telephone'],
                'shipping_address' => $shipping_address,
                'shipping_method' => $order_info['shipping_method'],
                'payment_address' => $payment_address,
                'payment_method' => $order_info['payment_method'],
                'product' => $product_data,
                'total' => $total_data,
                'comment' => nl2br($order_info['comment'])
            );
        }

        $this->response->setOutput($this->load->view('pos/order_invoice.tpl', $data));
    }

    // add for Print begin
    public function receipt() {
        $receipt_data = array();
        if (!empty($this->request->get['order_id'])) {
            $receipt_data['order_id'] = $this->request->get['order_id'];
        }
        if (!empty($this->request->get['pos_return_id'])) {
            $receipt_data['pos_return_id'] = $this->request->get['pos_return_id'];
        }
        if (!empty($this->request->post['change'])) {
            $receipt_data['change'] = $this->request->post['change'];
        }
        if (isset($this->request->get['work_mode'])) {
            $receipt_data['work_mode'] = $this->request->get['work_mode'];
        } else {
            $receipt_data['work_mode'] = '0';
        }

        $this->process_receipt($receipt_data);
    }

    public function email_receipt() {
        $data = $this->request->post;
        if (isset($data['change'])) {
            $change_query = $this->db->query("SELECT tendered_amount FROM `" . DB_PREFIX . "order_payment` WHERE order_id = '" . $data['order_id'] . "' AND payment_type = 'pos_change'");
            if ($change_query->row) {
                $data['change'] = $this->currency->formatFront($change_query->row['tendered_amount']);
            } else {
                unset($data['change']);
            }
        }
        $this->process_receipt($data);
    }

    private function process_receipt($receipt_data) {
        $this->language->load('sale/order');
        $this->load->model('pos/pos');

        $data['direction'] = $this->language->get('direction');
        $data['language'] = $this->language->get('code');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_total'] = $this->language->get('column_total');

        $this->language->load('module/pos');
        $data['title'] = $this->language->get('print_title');
        $data['column_desc'] = $this->language->get('column_desc');
        $data['column_qty'] = $this->language->get('column_qty');
        $data['column_type'] = $this->language->get('column_type');
        $data['column_amount'] = $this->language->get('column_amount');
        $data['column_note'] = $this->language->get('column_note');
        $username = $this->model_pos_pos->get_full_username($this->user->getId());
        $data['user_info'] = sprintf($this->language->get('user_info'), $username);
        $data['term_n_cond'] = $this->language->get('term_n_cond_default');
        if ($this->config->get('POS_p_term_n_cond')) {
            $data['term_n_cond'] = $this->config->get('POS_p_term_n_cond');
        }
        $data['text_gift_receipt_title'] = $this->language->get('text_gift_receipt_title');

        $data['p_logo'] = $this->config->get('POS_p_logo');
        $data['p_width'] = $this->config->get('POS_p_width');
        $data['date'] = date($this->language->get('date_format_short'));
        $data['time'] = date($this->language->get('time_format'));

        $data['barcode_for_product'] = $this->config->get('POS_barcode_for_product') ? $this->config->get('POS_barcode_for_product') : 0;

        $this->load->model('sale/order');

        $this->load->model('setting/setting');

        if (!empty($receipt_data['order_id']) && (int) $receipt_data['order_id'] > 0) {
            $data['order'] = array();
            /* if (!empty($receipt_data['change'])) {
              $data['change'] = $receipt_data['change'];
              } */

            $order_id = $receipt_data['order_id'];
            $change_amount = $this->model_pos_pos->getOrderChangeAmount($order_id);
            if ($change_amount > 0) {
                $data['change'] = $this->currency->formatFront($change_amount);
            }
            // unset the online order indicator
            $this->model_pos_pos->unsetOnlineOrderIndicator($order_id);

            $order_info = $this->model_sale_order->getOrder($order_id);

            if ($order_info) {
                $store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);
                if ($store_info) {
                    $store_address = $store_info['config_address'];
                    $store_email = $store_info['config_email'];
                    $store_telephone = $store_info['config_telephone'];
                    $store_fax = $store_info['config_fax'];
                } else {
                    $store_address = $this->config->get('config_address');
                    $store_email = $this->config->get('config_email');
                    $store_telephone = $this->config->get('config_telephone');
                    $store_fax = $this->config->get('config_fax');
                }

                $billing_address_info = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'] . ', ' . $order_info['payment_address_1'] . ', ' . $order_info['payment_address_2'] . ', ' . $order_info['payment_city'] . ', ' . $order_info['payment_zone'] . ' ' . $order_info['payment_postcode'] . ', ' . $order_info['payment_country'];
                $order_status_name = $this->model_pos_pos->getOrderStatusName($order_info['order_status_id']);

                $product_data = array();

                // update for product based discount begin
                $products = $this->model_pos_pos->getOrderProducts($order_id);
                // update for product based discount end

                foreach ($products as $product) {
                    $option_data = array();

                    $options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

                    // add for Weight based price begin
                    $weight = 1;
                    if (!empty($product['weight_price'])) {
                        $weigth = (int) $product['weight'];
                    }
                    // add for Weight based price end
                    foreach ($options as $option) {
                        $option_data[] = array(
                            'name' => $option['name'],
                            'value' => $option['value']
                        );
                    }

                    // add for Abbreviation begin
                    $abbreviation = html_entity_decode($product['name']);
                    if (!empty($product['abbreviation'])) {
                        $abbreviation = $product['abbreviation'];
                    }
                    // add for Abbreviation end
                    // add for serial no begin
                    $sns = $this->model_pos_pos->getSoldProductSN($product['order_product_id']);
                    // add for serial no end
                    // add for (update) Weight based price begin
                    // $product_total = $product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0);
                    $product_total = $product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity'] * $weight) : 0);
                    // add for (update) Weight based price end
                    // add for product based discount begin
                    $data['text_discount'] = $this->language->get('text_discount');
                    $discount_type = 0;
                    $discount_value = 0;
                    $text_discount = '';
                    $text_before_discount = $this->currency->formatFront($product_total, $order_info['currency_code'], $order_info['currency_value']);
                    $product_tax = $product['tax'];
                    if (!empty($product['discount'])) {
                        $discount_type = $product['discount']['discount_type'];
                        $discount_value = $product['discount']['discount_value'];
                        if ($discount_type == 1) {
                            if ($this->config->get('config_tax') && !$product['discount']['include_tax']) {
                                $discount_value += ($product['tax'] - $product['discount']['discounted_tax']) * $product['quantity'];
                            } else if (!$this->config->get('config_tax') && $product['discount']['include_tax']) {
                                $discount_value = $product['total'] - $product['discount']['discounted_total'];
                            }
                            $text_discount = $this->currency->formatFront($discount_value, $order_info['currency_code'], $order_info['currency_value']);
                            $product_total -= $discount_value;
                        } elseif ($discount_type == 2) {
                            $text_discount = $discount_value . '%';
                            $product_total = (100 - $discount_value) * $product_total / 100;
                        }
                        $product_tax = $product['discount']['discounted_tax'];
                    }
                    // add for product based discount end

                    $product_total_text = $this->currency->formatFront($product_total, $order_info['currency_code'], $order_info['currency_value']);
                    $normal_price = $this->model_pos_pos->getProductNormalPrice($product['product_id']);
                    $product_data[] = array(
                        // add for (update) Abbreviation begin
                        // 'name'     => $product['name'],
                        'name' => $abbreviation,
                        // add for (update) Abbreviation end
                        'model' => $product['model'],
                        'sku' => $product['model'],
                        'option' => $option_data,
                        'quantity' => $product['quantity'],
                        'ean' => $product['ean'],
                        'weight_price' => $product['weight_price'],
                        'weight_name' => $product['weight_name'],
                        'weight' => $product['weight'],
                        'discounted_price' => $product['price'],
                        'normal_price' => $normal_price,
                        'normal_price_text' => $this->currency->formatFront($normal_price + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'normal_price_total_text' => $this->currency->formatFront(($normal_price * $product['quantity']) + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                        // add for serial no begin
                        'sns' => $sns,
                        // add for serial no end
                        // add for product based discount begin
                        'discount_type' => $discount_type,
                        'discount_value' => $discount_value,
                        'text_discount' => $text_discount,
                        'text_before_discount' => $text_before_discount,
                        // add for product based discount end
                        'price' => $this->currency->formatFront($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'total' => $product_total_text
                    );
                }

                $voucher_data = array();

                $vouchers = $this->model_sale_order->getOrderVouchers($order_id);

                foreach ($vouchers as $voucher) {
                    $voucher_data[] = array(
                        'description' => $voucher['description'],
                        'amount' => $this->currency->formatFront($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
                    );
                }

                $total_data = $this->model_sale_order->getOrderTotals($order_id);
                foreach ($total_data as $key => $total) {
                    $total_data[$key]['text'] = $this->currency->formatFront($total['value']);
                }

                $data['order'] = array(
                    'order_id' => $order_id,
                    'store_name' => $order_info['store_name'],
                    'store_address' => nl2br($store_address),
                    'store_email' => $store_email,
                    'store_telephone' => $store_telephone,
                    'store_fax' => $store_fax,
                    'email' => $order_info['email'],
                    'telephone' => $order_info['telephone'],
                    'product' => $product_data,
                    'voucher' => $voucher_data,
                    'total' => $total_data,
                    'resale_text' => isset($order_info['resale_text']) ? $order_info['resale_text'] : false,
                    'date_added' => $order_info['date_added'],
                    'shipping_method' => $order_info['shipping_method'],
                    'order_status' => $order_status_name,
                    'billing_info' => $billing_address_info,
                    'comment' => $order_info['comment']
                );

                $data['payments'] = array();

                $this->load->model('pos/pos');
                $order_payments = $this->model_pos_pos->retrieveOrderPayments($order_id);
                $all_payment_types = '';
                if ($order_payments) {
                    foreach ($order_payments as $order_payment) {
                        // update for customer loyalty card begin
                        $amount = $order_payment['tendered_amount'];
                        if ($order_payment['payment_type'] == $this->language->get('text_reward_points') && strpos($order_payment['payment_note'], ',') !== false) {
                            // it's the opencart reward point payment
                            $parts = explode('|', $order_payment['payment_note']);
                            if ($parts) {
                                // deduct the reward points from the current order if the reward points are used to pay the order product as we already set the reward points in the earlier stage
                                $total_value = 0;
                                foreach ($parts as $part) {
                                    if (!empty($part)) {
                                        $reward_details = explode(',', $part);
                                        if ($reward_details) {
                                            $order_product_id = $reward_details[0];
                                            $quantity = $reward_details[1];

                                            $order_product_query = $this->db->query("SELECT price, tax FROM `" . DB_PREFIX . "order_product` WHERE order_product_id = '" . (int) $order_product_id . "'");
                                            $total_value += (int) $quantity * ((float) $order_product_query->row['price'] + (float) $order_product_query->row['tax']);
                                        }
                                    }
                                }

                                $amount = $total_value;
                            }
                        }
                        $data['payments'][] = array(
                            'type' => $order_payment['payment_type'],
                            'amount_float' => $amount,
                            'amount' => $this->currency->formatFront($amount, $order_info['currency_code'], $order_info['currency_value']),
                            'note' => ($order_payment['payment_type'] == $this->language->get('text_reward_points')) ? '' : $order_payment['payment_note']
                        );
                        $all_payment_types .= $order_payment['payment_type'] . ', ';
                    }
                    $all_payment_types = rtrim($all_payment_types, ', ');
                    $all_payment_types = implode(',', array_unique(explode(', ', $all_payment_types)));
                    $data['all_payment_types'] = $all_payment_types;
                } else {
                    $data['all_payment_types'] = '';
                }

                if ($data['all_payment_types']) {
                    $allpaymenttypes = explode(',', $data['all_payment_types']);
                    foreach ($allpaymenttypes as $allpaymenttype) {
                        $amountcombined = 0;
                        foreach ($data['payments'] as $payment) {
                            if ($payment['type'] == $allpaymenttype) {
                                $amountcombined += $payment['amount_float'];
                                $note = $payment['note'];
                            }
                        }
                        $data['payment_combined'][] = array(
                            'type' => $allpaymenttype,
                            'amount_float' => $amountcombined,
                            'amount' => $this->currency->formatFront($amountcombined, $order_info['currency_code'], $order_info['currency_value']),
                            'note' => $note
                        );
                    }
                }
            }

            $data['barcode_text'] = $order_id;
        } elseif (!empty($receipt_data['pos_return_id'])) {
            $data['return'] = array();
            $pos_return_id = $receipt_data['pos_return_id'];
            $return_info = $this->model_pos_pos->getReturn($pos_return_id);

            if ($return_info) {
                $store_info = $this->model_setting_setting->getSetting('config', 0);
                if ($store_info) {
                    $store_address = $store_info['config_address'];
                    $store_email = $store_info['config_email'];
                    $store_telephone = $store_info['config_telephone'];
                    $store_fax = $store_info['config_fax'];
                } else {
                    $store_address = $this->config->get('config_address');
                    $store_email = $this->config->get('config_email');
                    $store_telephone = $this->config->get('config_telephone');
                    $store_fax = $this->config->get('config_fax');
                }

                $product_data = array();

                $products = $this->model_pos_pos->getReturnProducts($pos_return_id);

                foreach ($products as $product) {
                    $option_data = array();

                    $options = $this->model_sale_order->getOrderOptions($product['order_id'], $product['order_product_id']);

                    // add for Weight based price begin
                    $weight = 1;
                    if (!empty($product['weight_price'])) {
                        $weigth = (int) $product['weight'];
                    }
                    // add for Weight based price end
                    foreach ($options as $option) {
                        $option_data[] = array(
                            'name' => $option['name'],
                            'value' => $option['value']
                        );
                    }

                    // add for Abbreviation begin
                    $abbreviation = html_entity_decode($product['product']);
                    if (!empty($product['abbreviation'])) {
                        $abbreviation = $product['abbreviation'];
                    }
                    // add for Abbreviation end
                    // add for serial no begin
                    $sns = $this->model_pos_pos->getSoldProductSN($product['order_product_id']);
                    // add for serial no end
                    $product_total = $product['quantity'] * $weight * ($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0));

                    $product_total_text = $this->currency->formatFront($product_total);
                    $product_data[] = array(
                        // add for (update) Abbreviation begin
                        // 'name'     => $product['name'],
                        'name' => $abbreviation,
                        // add for (update) Abbreviation end
                        'model' => $product['model'],
                        'option' => $option_data,
                        'quantity' => $product['quantity'],
                        // add for serial no begin
                        'sns' => $sns,
                        // add for serial no end
                        'price' => $this->currency->formatFront($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0)),
                        'total' => $product_total_text
                    );
                }

                $total_data = array(array('code' => 'tax', 'title' => $this->language->get('text_return_tax'), 'value' => $return_info['tax'], 'text' => $this->currency->formatFront($return_info['tax']), 'sort_order' => 1),
                    array('code' => 'subtotal', 'title' => $this->language->get('text_return_sub_total'), 'value' => $return_info['sub_total'], 'text' => $this->currency->formatFront($return_info['sub_total']), 'sort_order' => 2),
                    array('code' => 'total', 'title' => $this->language->get('text_return_total'), 'value' => ($return_info['tax'] + $return_info['sub_total']), 'text' => $this->currency->formatFront($return_info['tax'] + $return_info['sub_total']), 'sort_order' => 3));

                $data['payments'] = array();
                $return_payments = $this->model_pos_pos->retrieveReturnPayments($pos_return_id);
                if ($return_payments) {
                    // reverse the order
                    $return_payments = array_reverse($return_payments);
                    foreach ($return_payments as $return_payment) {
                        //				if ($return_payment['tendered_amount'] > 0) {
                        $data['payments'][] = array(
                            'type' => $return_payment['payment_type'],
                            'amount_float' => $return_payment['tendered_amount'],
                            'amount' => $this->currency->formatFront($return_payment['tendered_amount']),
                            'note' => $return_payment['payment_note']
                        );
                        //				}
                    }
                }

                $data['return'] = array(
                    'pos_return_id' => $pos_return_id,
                    'store_name' => '',
                    'store_address' => nl2br($store_address),
                    'store_email' => $store_email,
                    'store_telephone' => $store_telephone,
                    'store_fax' => $store_fax,
                    'email' => $return_info['email'],
                    'telephone' => $return_info['telephone'],
                    'product' => $product_data,
                    'total' => $total_data,
                );
            }

            $data['barcode_text'] = $pos_return_id;
        }

        $this->language->load('module/pos');
        $data['text_change'] = $this->language->get('text_change');

        if ($this->config->get('POS_config_print_type') == '318receipt') {
            $template_file = 'pos/318receipt_barcode.tpl';
        } else {
            $template_file = 'pos/receipt_barcode.tpl';
        }
        // add for gift receipt begin
        if (!empty($order_info) && $order_info['order_status_id'] == $this->config->get('POS_gift_receipt_status_id')) {
            $template_file = 'pos/receipt_gift.tpl';
        }
        // add for gift receipt end
        if (!empty($receipt_data['work_mode']) && $receipt_data['work_mode'] == '1') {
            $data['text_return_title'] = $this->language->get('text_return_title');
            $data['text_tax_adjustment_title'] = $this->language->get('text_tax_adjustment_title');
            $template_file = 'pos/receipt_return.tpl';
        }

        if (isset($receipt_data['email'])) {
            if (empty($receipt_data['email'])) {
                $data['email'] = $order_info['email'];
            } else {
                $data['email'] = $receipt_data['email'];
            }
            $data['store_name'] = $order_info['store_name'];
            $data['order_id'] = $receipt_data['order_id'];
            $enable_email_receipt_cc = $this->config->get('POS_enable_email_receipt_cc') ? $this->config->get('POS_enable_email_receipt_cc') : 0;
            $email_receipt_cc_account = $this->config->get('POS_email_receipt_cc_account') ? $this->config->get('POS_email_receipt_cc_account') : $this->config->get('config_email');
            $cc = ($enable_email_receipt_cc ? $email_receipt_cc_account : false);
            $err_msg = $this->send_html_email($template_file, $data, $cc);
            if ($err_msg) {
                $json = array('error' => $err_msg);
            } else {
                $json = array('success' => sprintf($this->language->get('text_receipt_email_success'), $data['email']));
            }
            $this->response->setOutput(json_encode($json));
        } else {
            $this->response->setOutput($this->load->view($template_file, $data));
        }
    }

    // add for Print end
    private function fetch_content($filename, $data) {
        $file = DIR_TEMPLATE . $filename;

        if (file_exists($file)) {
            extract($data);

            ob_start();

            include($file);

            $content = ob_get_contents();

            ob_end_clean();

            return $content;
        } else {
            return '';
        }
    }

    private function send_html_email($template_file, $data, $cc = false) {
        if (isset($data['order_id'])) {
            $subject = sprintf($this->language->get('text_receipt_email_subject'), $data['store_name'], $data['order_id']);
            $sender = $data['store_name'];
        } else {
            $subject = sprintf($this->language->get('text_transfer_subject'), $data['total_quantity']);
            $sender = $data['sender'];
        }
        $admin_email = $this->config->get('config_email');

        /* $mail = new Mail();
          $mail->protocol = $this->config->get('config_mail_protocol');
          $mail->parameter = $this->config->get('config_mail_parameter');
          $mail->hostname = $this->config->get('config_smtp_host');
          $mail->username = $this->config->get('config_smtp_username');
          $mail->password = $this->config->get('config_smtp_password');
          $mail->port = $this->config->get('config_smtp_port');
          $mail->timeout = $this->config->get('config_smtp_timeout'); */
        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
        $mail->setTo($data['email']);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($data['store_name']);
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setHtml($this->fetch_content($template_file, $data));
        $mail->setText(html_entity_decode('a', ENT_QUOTES, 'UTF-8'));

        // To send HTML mail, the Content-type header must be set
        /* $headers  = 'MIME-Version: 1.0' . "\r\n";
          $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

          // Create email headers
          $headers .= 'From: '.$this->config->get('config_email')."\r\n".
          'Reply-To: '.$this->config->get('config_email')."\r\n" .
          'X-Mailer: PHP/' . phpversion();


          if(mail($data['email'], html_entity_decode($subject, ENT_QUOTES, 'UTF-8'), $this->fetch_content($template_file, $data), $headers)){ */

        if ($mail->send()) {
//		$boundary = '----=_NextPart_' . md5(time());
            //$header = '';
            //$newline = "\n";
            //$header .= 'MIME-Version: 1.0' . $newline. 'To: ' . $data['email'] . $newline . 'Subject: ' . $subject . $newline;
//		$header .= 'MIME-Version: 1.0' . $newline;
            //$header .= 'Date: ' . date('D, d M Y H:i:s O') . $newline;
            //$header .= 'From: ' . '=?UTF-8?B?' . base64_encode($sender) . '?=' . '<' . $admin_email . '>' . $newline;
//		$header .= 'From: ' . '<support@pos4opencart.com>' . $newline;
//		$header .= 'Reply-To: ' . '=?UTF-8?B?' . base64_encode($sender) . '?=' . '<' . $admin_email . '>' . $newline;
//		$header .= 'Return-Path: ' . $admin_email . $newline;
//		$header .= 'X-Mailer: PHP/' . phpversion() . $newline;
//		$header .= 'Content-Type: multipart/related; boundary="' . $boundary . '"' . $newline . $newline;
//		$header  = '--' . $boundary . $newline;
            //$header .= 'Content-Type: text/html; charset=UTF-8' . $newline;
            //if ($cc) {
            //	$header .= 'Bcc: ' . $cc . $newline;
            //}
//		$header .= 'Content-Transfer-Encoding: 8bit' . $newline . $newline;
            //$message = $this->fetch_content($template_file, $data) . $newline;
//		$message .= '--' . $boundary . '--' . $newline;
//		ini_set('sendmail_from', $admin_email);
            //	if (mail($data['email'], '=?UTF-8?B?' . base64_encode($subject) . '?=', $message, $header)) {
            return false;
        } else {
            return false; //$this->language->get('text_email_cannot_sent');
        }
    }

    // add for Discount begin
    public function applyDiscount() {
        $this->language->load('module/pos');
        $this->load->model('pos/pos');
        $json = array();

        // check if the pos_discount is installed and enabled
        $installed = false;
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE type = 'total' AND code = 'pos_discount'");
        if ($query->row) {
            $installed = true;
        }
        if ($installed && $this->config->get('pos_discount_status')) {

            // update order to set the discount total
            $order_info = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE order_id = '" . $this->request->post['order_id'] . "'");

            if ($order_info->row) {
                // set the value into session
                $discount = array(
                    'order_id' => $this->request->post['order_id'],
                    'code' => $this->request->post['code'],
                    'title' => $this->request->post['title'],
                    'value' => $this->request->post['value']
                );
                $this->session->data['pos_discount'] = $discount;
                $this->session->data['discount_customer_group_id'] = $this->request->post['discount_customer_group_id'];
                $total_data = $this->recalculate_total($this->request->post);
                $json['totals'] = $total_data['order_total'];
                $json['success'] = $this->language->get('text_order_success');
                $json['discount_amount'] = "$0.00";
                if (!empty($json['totals'])) {
                    foreach ($json['totals'] as $order_total) {
                        if (strpos($order_total['code'], 'pos_discount_') !== false) {
                            $json['discount_amount'] = $this->currency->formatFront($order_total['value'] * -1);
                        }
                    }
                }
            } else {
                $json['error'] = sprintf($this->language->get('error_discount_order_not_exist'), $this->request->post['order_id']);
            }
        } else {
            $json['error'] = $this->language->get('error_discount_not_installed');
        }

        $this->response->setOutput(json_encode($json));
    }

    // add for Discount end
    // add for User as Affiliate begin
    public function addUA() {
        // add a record to user_affiliate mappint table
        $this->load->model('pos/pos');
        $this->model_pos_pos->addUA($this->request->post);
        $this->response->setOutput(json_encode(array()));
    }

    public function deleteUA() {
        // add a record to user_affiliate mappint table
        $this->load->model('pos/pos');
        $this->model_pos_pos->deleteUA($this->request->post);
        $this->response->setOutput(json_encode(array()));
    }

    // add for User as Affiliate end
    // add for Add Customer begin
    public function createEmptyCustomer() {
        $default_cusomter_group_id = $this->config->get('config_customer_group_id');
        $customer_group_id = $default_cusomter_group_id ? (int) $default_cusomter_group_id : 1;
        $this->db->query("INSERT INTO `" . DB_PREFIX . "customer` SET firstname = '', lastname = '', email = '', telephone = '', fax = '', newsletter = '0', customer_group_id = '" . $customer_group_id . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1('pa55w0rd')))) . "', status = '1', date_added = NOW()");
        $this->load->model('pos/pos');
        $customer_info = $this->model_pos_pos->getCustomer($this->db->getLastId());
        $json['customer_info'] = $customer_info;
        $this->load->model('localisation/country');
        $json['customer_countries'] = $this->model_localisation_country->getCountries();
        $this->response->setOutput(json_encode($json));
    }

    public function removeEmptyCustomer() {
        if (isset($this->request->get['customer_id'])) {
            $this->load->model('pos/pos');
            $this->model_pos_pos->deleteCustomer($this->request->get['customer_id']);
        }
    }

    // add for Add Customer end
    // add for edit order address begin
    public function saveOrderAddresses() {
        $this->load->model('pos/pos');
        $this->model_pos_pos->editOrderAddresses($this->request->get['order_id'], $this->request->post);
        if (!empty($this->request->post['shipping'])) {
            $this->model_pos_pos->UpdateOrderShipping($this->request->get['order_id'], $this->request->post);
        }
        if ($this->request->post['shipping_zone_id'] != 3624) {
            $this->session->data['outsite_california'] = true;
        } else {
            $this->session->data['outsite_california'] = false;
        }
        $json = array();
        if ($this->request->post['shipping'] == 'instore.instore') {
            $json['shipping_title'] = 'Shipping';
            $json['shipping_cost'] = 0.00;
        } else {
            $json['shipping_title'] = $this->request->post['shipping_title'];
            $json['shipping_cost'] = $this->request->post['shipping_cost'];
        }

        $this->response->setOutput(json_encode($json));
    }

    // add for edit order address end
    // add for Quotation begin
    public function addQuoteStatus() {
        $json = array();

        $this->load->model('pos/pos');
        $status_id = $this->model_pos_pos->addQuoteStatus($this->request->post['status']);
        if ($status_id) {
            $json['quote_status_id'] = $status_id;
        } else {
            $this->language->load('module/pos');
            $json['error'] = $this->language->get('text_quote_status_already_exist');
        }

        $this->response->setOutput(json_encode($json));
    }

    public function renameQuoteStatus() {
        $json = array();

        $this->load->model('pos/pos');
        $result = $this->model_pos_pos->renameQuoteStatus($this->request->post['status_id'], $this->request->post['status']);
        if (!$result) {
            $this->language->load('module/pos');
            $json['error'] = $this->language->get('text_quote_status_already_exist');
        }

        $this->response->setOutput(json_encode($json));
    }

    public function deleteQuoteStatus() {
        $json = array();

        $this->load->model('pos/pos');
        $order_ids = $this->model_pos_pos->deleteQuoteStatus($this->request->post['status_id']);
        if (!empty($order_ids)) {
            $order_id_s = implode(",", $order_ids);
            $this->language->load('module/pos');
            $json['error'] = sprintf($this->language->get('text_quote_status_in_use'), $order_id_s);
        }

        $this->response->setOutput(json_encode($json));
    }

    public function convertQuote2Order() {
        $json = array();

        $this->load->model('pos/pos');
        $new_order_id = $this->model_pos_pos->convertQuote2Order($this->request->post['order_id']);
        $this->language->load('module/pos');
        $json['success'] = $this->language->get('text_quote_converted');
        $json['order_id'] = $this->getOrderIdText($new_order_id);
        $this->language->load('sale/order');
        $json['order_text'] = $this->language->get('text_order_id');

        $this->response->setOutput(json_encode($json));
    }

    // add for Quotation end
    // add for Browse begin
    public function getCategoryTree() {
        // get the category tree in the catalog database
        $this->load->model('pos/pos');
        $categories = array(); //$this->model_pos_pos->getCategories();
        // convert the array to an tree-like array
        $category_tree = array();
        $parent_id_list = array();
        foreach ($categories as $category) {
            $parent_id_list[] = $category['parent_id'];
        }
        $this->convert2Tree($categories, $parent_id_list, $category_tree);

        $json = array();
        $json['category_tree'] = $category_tree;
        $this->response->setOutput(json_encode($json));
    }

    private function getCategoryItems($parent_category_id, $currency_code, $currency_value, $customer_group_id) {
        // get the direct sub-category and product in the given category
        $this->load->model('pos/pos');
        //$sub_categories = $this->model_pos_pos->getSubCategories($parent_category_id);
        //$products = $this->model_pos_pos->getProducts($parent_category_id);

        $this->language->load('module/pos');
        $this->load->model('tool/image');
        $this->load->model('catalog/product');
        $this->load->model('pos/extended_product');
        $browse_items = array();
        /* foreach ($sub_categories as $sub_category) {
          $category_items = 0;//$this->model_pos_pos->getTotalSubItems($sub_category['category_id']);
          $browse_items[] = array('type' => 'C',
          'name' => $sub_category['name'],
          'total_items' => $category_items,
          'image' => !empty($sub_category['image']) ? $this->model_tool_image->resize($sub_category['image'], 180, 180) : $this->model_tool_image->resize('no_image.jpg', 180, 180),
          'parent_category_id' => $parent_category_id,
          'category_id' => $sub_category['category_id']);
          } */
        /* foreach ($products as $product) {
          $quantity = $product['quantity'];
          $price = $product['price'];
          $product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product['product_id'] . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

          if ($product_special_query->num_rows) {
          $price = $product_special_query->row['price'];
          }
          // calculate price with tax
          $price_after_tax = ($this->config->get('config_tax')) ? $this->calculateTax($price, $product['tax_class_id'], true, $customer_group_id) : $price;
          $tax = $price_after_tax - $price;

          // Reward Points
          $product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product['product_id'] . "' AND customer_group_id = '" . (int)$customer_group_id . "'");

          if ($product_reward_query->num_rows) {
          $reward = $product_reward_query->row['points'];
          } else {
          $reward = 0;
          }

          $product_sn_query = $this->db->query("SELECT COUNT(product_sn_id) AS total FROM `" . DB_PREFIX . "product_sn` WHERE product_id = '" . (int)$product['product_id'] . "'");
          $has_sn = 0;
          if ($product_sn_query->row && $product_sn_query->row['total']) {
          $has_sn = 1;
          }

          $browse_items[] = array('type' => 'P',
          'name' => $product['name'],
          'image' => (!empty($product['image'])) ? $this->model_tool_image->resize($product['image'], 180, 180) : $this->model_tool_image->resize('no_image.jpg', 180, 180),
          'price_text' => $this->currency->formatFront($price_after_tax, $currency_code, $currency_value),
          'stock' => $quantity, // . ' ' . $this->language->get('text_remaining'),
          'hasOptions' => $product['options'] ? '1' : '0',
          // add for (update) Weight based price begin
          'weight_price' => $product['weight_price'],
          'weight_name' => $product['weight_name'],
          // add for (update) Weight based price end
          'has_sn' => $has_sn,
          'price' => $price,
          'subtract' => $product['subtract'],
          'tax_class_id' => $product['tax_class_id'],
          'shipping'  => $product['shipping'],
          'tax' => $tax,
          'points' => $product['points'],
          'reward_points' => $reward,
          'model' => $product['model'],
          'description' => $product['description'],
          'manufacturer' => $product['m_name'],
          'upc' => $product['upc'],
          'sku' => $product['sku'],
          'ean' => $product['ean'],
          'mpn' => $product['mpn'],
          'quick_sale' => $product['quick_sale'],
          'manufacturer' => $product['m_name'],
          'parent_category_id' => $parent_category_id,
          'product_discounts' => $this->model_catalog_product->getProductDiscounts($product['product_id']),
          'product_specials' => $this->model_catalog_product->getProductSpecials($product['product_id']),
          'product_sns' => $this->model_pos_extended_product->getProductSNs(array('product_id' => $product['product_id'], 'status' => 1)),
          'commission' => $this->model_pos_extended_product->getCommission($product['product_id']),
          'product_id' => $product['product_id']);
          } */

        return $browse_items;
    }

    public function calculateTax($value, $tax_class_id, $calculate = true, $customer_group_id) {
        if ($tax_class_id && $calculate) {
            $amount = $this->getTax($value, $tax_class_id, $customer_group_id);

            return $value + $amount;
        } else {
            return $value;
        }
    }

    public function getPriceFromPriceWithTax($price, $tax_class_id, $customer_group_id) {
        $cal_price = $price;
        if ($this->config->get('config_tax')) {
            // the changed price is with tax according to the settings
            // get all tax rates
            $base = 100;
            $tax_rates = $this->getRates($base, $tax_class_id, $customer_group_id);
            $rate_p = 0;
            foreach ($tax_rates as $tax_rate) {
                if ($tax_rate['type'] == 'F') {
                    // fixed amount rate
                    $cal_price -= $tax_rate['rate'];
                } elseif ($tax_rate['type'] == 'P') {
                    // percentage rate
                    $rate_p += $tax_rate['rate'];
                }
            }
            $cal_price = $cal_price / (1 + ((float) $rate_p) / 100);
        }
        return $cal_price;
    }

    public function getTax($value, $tax_class_id, $customer_group_id) {
        $amount = 0;

        $tax_rates = $this->getRates($value, $tax_class_id, $customer_group_id);

        foreach ($tax_rates as $tax_rate) {
            $amount += $tax_rate['amount'];
        }

        return $amount;
    }

    public function getRates($value, $tax_class_id, $customer_group_id, $discount = 0) {
        $tax_rates = $this->getTaxRates($tax_class_id, $customer_group_id);

        $tax_rate_data = array();
        foreach ($tax_rates as $tax_rate) {
            if (isset($tax_rate_data[$tax_rate['tax_rate_id']])) {
                $amount = $tax_rate_data[$tax_rate['tax_rate_id']]['amount'];
            } else {
                $amount = 0;
            }

            if ($tax_rate['type'] == 'F') {
                if (!$discount) {
                    // change for pos_discount, as the discount will not incur the fix amount tax
                    $amount += $tax_rate['rate'];
                }
            } elseif ($tax_rate['type'] == 'P') {
                $amount += ($value / 100 * $tax_rate['rate']);
            }

            $tax_rate_data[$tax_rate['tax_rate_id']] = array(
                'tax_rate_id' => $tax_rate['tax_rate_id'],
                'name' => $tax_rate['name'],
                'rate' => $tax_rate['rate'],
                'type' => $tax_rate['type'],
                'amount' => $amount
            );
        }
        return $tax_rate_data;
    }

    private function getTaxRates($tax_class_id, $customer_group_id) {
        $tax_rates = array();

        // use the default country id and zone id for POS
        $country_id = $this->config->get('config_country_id');
        $zone_id = $this->config->get('config_zone_id');

        $tax_query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " . DB_PREFIX . "tax_rule tr1 LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.tax_class_id = '" . (int) $tax_class_id . "' AND tr1.based = 'shipping' AND tr2cg.customer_group_id = '" . (int) $customer_group_id . "' AND z2gz.country_id = '" . (int) $country_id . "' AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int) $zone_id . "') ORDER BY tr1.priority ASC");

        foreach ($tax_query->rows as $result) {
            $tax_rates[$result['tax_rate_id']] = array(
                'tax_rate_id' => $result['tax_rate_id'],
                'name' => $result['name'],
                'rate' => $result['rate'],
                'type' => $result['type'],
                'priority' => $result['priority']
            );
        }

        $tax_query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " . DB_PREFIX . "tax_rule tr1 LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.tax_class_id = '" . (int) $tax_class_id . "' AND tr1.based = 'payment' AND tr2cg.customer_group_id = '" . (int) $customer_group_id . "' AND z2gz.country_id = '" . (int) $country_id . "' AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int) $zone_id . "') ORDER BY tr1.priority ASC");

        foreach ($tax_query->rows as $result) {
            $tax_rates[$result['tax_rate_id']] = array(
                'tax_rate_id' => $result['tax_rate_id'],
                'name' => $result['name'],
                'rate' => $result['rate'],
                'type' => $result['type'],
                'priority' => $result['priority']
            );
        }

        $tax_query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " . DB_PREFIX . "tax_rule tr1 LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id) WHERE tr1.tax_class_id = '" . (int) $tax_class_id . "' AND tr1.based = 'store' AND tr2cg.customer_group_id = '" . (int) $customer_group_id . "' AND z2gz.country_id = '" . (int) $country_id . "' AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int) $zone_id . "') ORDER BY tr1.priority ASC");

        foreach ($tax_query->rows as $result) {
            $tax_rates[$result['tax_rate_id']] = array(
                'tax_rate_id' => $result['tax_rate_id'],
                'name' => $result['name'],
                'rate' => $result['rate'],
                'type' => $result['type'],
                'priority' => $result['priority']
            );
        }

        return $tax_rates;
    }

    public function getTaxRatesAjax() {
        // return all tax rates
        $this->load->model('localisation/tax_class');
        $tax_classes = $this->model_localisation_tax_class->getTaxClasses();

        $customer_group_query = $this->db->query("SELECT customer_group_id FROM `" . DB_PREFIX . "customer_group`");
        $customer_groups = array();
        if ($customer_group_query->rows) {
            foreach ($customer_group_query->rows as $row) {
                array_push($customer_groups, $row['customer_group_id']);
            }
        }

        $json = array();
        foreach ($tax_classes as $tax_class) {
            foreach ($customer_groups as $customer_group_id) {
                $key = 'tax_' . $tax_class['tax_class_id'] . '_' . $customer_group_id;
                $tax_rates = $this->getTaxRates($tax_class['tax_class_id'], $customer_group_id);
                if (!empty($tax_rates)) {
                    $json[$key] = $tax_rates;
                }
            }
        }
        $this->response->setOutput(json_encode($json));
    }

    public function getCategoryItemsAjax() {
        $parent_category_id = 0;
        if (isset($this->request->post['category_id'])) {
            $parent_category_id = $this->request->post['category_id'];
        }

        $json = array();
        $customer_group_id = $this->config->get('config_customer_group_id');
        if (isset($this->request->post['customer_group_id'])) {
            $customer_group_id = $this->request->post['customer_group_id'];
        }
        $json['browse_items'] = $this->getCategoryItems($parent_category_id, $this->request->post['currency_code'], $this->request->post['currency_value'], $customer_group_id);
        // the above step already has model pos/pos include
        if (version_compare(VERSION, '1.5.5', '<')) {
            $category_path = $this->model_pos_pos->getCategoryFullPathOld($parent_category_id);
        } else {
            $category_path = $this->model_pos_pos->getCategoryFullPath($parent_category_id);
            if ($category_path) {
                $category_path = $category_path['name'];
            }
        }
        $json['path'] = array();
        $this->load->model('tool/image');
        if ($category_path) {
            $pathes = explode('!|||!', $category_path);
            $json['path'] = array();
            foreach ($pathes as $path) {
                $names = explode('|||', $path);
                $json['path'][] = array('id' => $names[0], 'name' => $names[1], 'image' => (!empty($names[2]) ? $this->model_tool_image->resize($names[2], 180, 180) : $this->model_tool_image->resize('no_image.jpg', 180, 180)));
            }
        }

        $this->response->setOutput(json_encode($json));
    }
    private function convert2Tree($categories, $parent_id_list, &$parent_category, $parent_id = 0) {
        // find the sub categories under the given parent category with id $parent_id
        foreach ($categories as $category) {
            if ($category['parent_id'] == $parent_id) {
                // add it into the parent category array
                $category_names = explode(' &gt; ', $category['name']);
                $category_name = $category_names[sizeof($category_names) - 1];
                $sub_category = array();
                if (in_array($category['category_id'], $parent_id_list)) {
                    // the category still has sub categories
                    $this->convert2Tree($categories, $parent_id_list, $sub_category, $category['category_id']);
                }
                array_push($parent_category, array('id' => $category['category_id'], 'name' => $category_name, 'subs' => $sub_category));
            }
        }
    }

    private function _getProductUnits($product_id) {
        $this->load->model('pos/product');
        $data = $this->model_pos_product->getUnitDetails($product_id);
        return $data;
    }

    public function getProductOptions() {
        $json = array();
        $option_data = array();

        $this->load->model('catalog/product');
        $this->load->model('pos/product_grouped');
        $this->load->model('pos/seo_url');
        $this->load->model('catalog/option');
        $this->load->model('pos/pos');
        $this->load->model('pos/product');
        // recalculate the total
        if (version_compare(VERSION, '2.1.0', '<')) {
            $this->load->library('customer');
            $this->load->library('tax');
            $this->load->library('cart');
        } else {
            library('customer');
            library('tax');
            library('cart');
        }
        $product_id = $this->request->get['product_id'];
        $product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
        $this->customer = new Customer($this->registry);
        $this->tax = new Tax($this->registry);
        $this->cart = new Cart($this->registry);
        $group_product_id = $this->model_pos_seo_url->isProductGrouped($product_id);
        $new_product_id = 0;
        $gp_master_q = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_grouped_type WHERE product_id = '" . (int) $group_product_id . "'");

        if ($gp_master_q->num_rows) {
            $new_product_id = $gp_master_q->row['product_id'];
        } else {
            $gp_slave_q = $this->db->query("SELECT pg.product_id FROM " . DB_PREFIX . "product_grouped pg LEFT JOIN " . DB_PREFIX . "product p ON (pg.grouped_id=p.product_id) WHERE p.model != 'grouped' AND p.pgvisibility!='1' AND p.status='1' AND pg.grouped_id='" . (int) $group_product_id . "' LIMIT 1");
            if ($gp_slave_q->num_rows) {
                $new_product_id = $gp_slave_q->row['product_id'];
            }
        }
        //$product
        $gp_tpl_q = $this->model_pos_product_grouped->getProductGroupedType($product_id);
        if (!$gp_tpl_q) {
            $data['text_groupby'] = $gp_tpl_q['pg_groupby'];
            $product_grouped = array();
            $product_grouped_name = $this->model_pos_pos->getGroupedProductName($product_id);
            $data['groupbyname'] = $product_grouped_name;
        } else {
            $product_info = false;
            $product_grouped_name = '';
        }
        $gruppi = $this->model_pos_product_grouped->getGrouped($group_product_id);

        //$product_options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
        if (!empty($gruppi)) {
            $group_indicator_id = $this->model_pos_product_grouped->getGroupIndicator($group_product_id);
            $data['group_indicator_id'] = $group_indicator_id;
            /**
             * REQUESTED PRODUCT CODE
             */
            if ($group_product_id != 0) {
                //check if requested "gp_product_id" is of same group
                $requested_product_data = $this->model_pos_product_grouped->getGroupedData($product_id, $group_indicator_id);
                $data['requested_product_data'] = $requested_product_data;
                if (isset($requested_product_data['groupbyvalue'])) {
                    $groupbyvaluename = $requested_product_data['groupbyvalue'];
                } else {
                    $groupbyvaluename = '';
                }
                //print_r($requested_product_data['groupbyvalue']);
                //if requested "gp_product_id" is not of same group then unset get variable
                if ($requested_product_data === FALSE) {
                    $group_indicator_id = 0;
                }
            }

            //print_r($data['requested_product_data']);
            /**
             * REQUESTED PRODUCT CODE
             */
            // print_r($gruppi);
            $requested_product_id = $gruppi[0]['grouped_id'];
            $product_grouped = array();
            foreach ($gruppi as $groups) {
                $product_name = $this->model_pos_product_grouped->getGroupedProductName($groups['grouped_id']);
                $name = str_replace($product_grouped_name, '', $product_name);

                //REQUESTED PRODUCT CODE

                $requested_product = FALSE;

                if ($product_id != 0 && trim($name) == $requested_product_data['groupbyvalue']) {
                    $requested_product = TRUE;
                    $requested_product_id = $groups['grouped_id'];
                }

                //REQUESTED PRODUCT CODE END

                $product_grouped[] = array(
                    'product_id' => $groups['grouped_id'],
                    'product_name' => $name,
                    'is_requested_product' => $requested_product //REQUESTED PRODUCT CODE
                );
            }

            // $product_id = $requested_product_id;
            $product_info = $this->model_pos_product_grouped->getProduct($product_id);
            if ($product_info['discounted_price']) {
                $discount_percent = $this->cart->calcMetalTypeDiscount($product_info['discounted_price'], $product_info['orignial_price']);
            } else {
                $discount_percent = 1;
            }

            $data['old_price'] = $product_info['orignial_price'];

            $data['options'] = array();

            //$data['unit_conversion_help'] = $this->model_pos_product_grouped->getConversionHelp($product_info['product_id']);
            $formula = FALSE;

            $data['base_price'] = $product_info['orignial_price'];

            $data['unit_datas'] = $this->_getProductUnits($product_info['product_id']);



            foreach ($this->model_pos_product->getProductOptions($product_id, $stat = 1) as $option) {
                if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
                    $option_value_data = array();
                    $size = 0;
                    //REQUESTED PRODUCT CODE
                    $is_requested_option = FALSE;
                    if ($product_id != 0 && $requested_product_data['option_count'] > 0) {
                        $requested_option_exists = array_search(trim($option['name']), array_reverse($requested_product_data));
                        if ($requested_option_exists !== FALSE) {
                            $is_requested_option = TRUE;
                        }
                    }
                    //REQUESTED PRODUCT CODE ENDS
                    foreach ($option['option_value'] as $option_value) {
                        //REQUESTED PRODUCT CODE
                        $is_requested_option_value = FALSE;
                        if ($is_requested_option === TRUE && $option_value['name'] == $requested_product_data['optionvalue' . filter_var($requested_option_exists, FILTER_SANITIZE_NUMBER_INT)]) {
                            $is_requested_option_value = TRUE;
                        }
                        //REQUESTED PRODUCT CODE ENDS
                        if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                            $option_unformated_price = $this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                            if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']) {
                                $price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                            } else {
                                $price = false;
                            }



                            $option_value_data[] = array(
                                'product_option_value_id' => $option_value['product_option_value_id'],
                                'quantity' => $option_value['quantity'],
                                'option_value_id' => $option_value['option_value_id'],
                                'name' => $option_value['name'],
                                'price' => $price,
                                'price2' => $option_unformated_price,
                                'price_prefix' => $option_value['price_prefix'],
                                'is_requested_option_value' => $is_requested_option_value//REQUESTED PRODUCT CODE
                            );
                        }
                    }

                    $option_data[] = array(
                        'product_option_id' => $option['product_option_id'],
                        'option_id' => $option['option_id'],
                        'metal_type' => $size,
                        'name' => $option['name'],
                        'type' => $option['type'],
                        'option_value' => $option_value_data,
                        'required' => $option['required']
                    );
                } elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {

                    $option_data[] = array(
                        'product_option_id' => $option['product_option_id'],
                        'option_id' => $option['option_id'],
                        'name' => $option['name'],
                        'type' => $option['type'],
                        'option_value' => $option['option_value'],
                        'required' => $option['required']
                    );
                }
            }
            $option_data = $this->getGroupOptions($product_id, $group_indicator_id, $requested_product_data, $groupbyvaluename);
        } else {
            foreach ($this->model_pos_pos->getProductOptions($this->request->get['product_id']) as $option) {
                if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
                    $option_value_data = array();
                    $size = 0;

                    foreach ($option['option_value'] as $option_value) {
                        if (!$option_value['subtract'] || $option_value['subtract']) {

                            $option_unformated_price = $this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                            if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']) {
                                $price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                            } else {
                                $price = false;
                            }
                            $option_value_data[] = array(
                                'default' => $option_value['default'], //Q: Default Product Option
                                'product_option_value_id' => $option_value['product_option_value_id'],
                                'quantity' => $option_value['quantity'],
                                'option_value_id' => $option_value['option_value_id'],
                                'name' => $option_value['name'],
                                'price' => $price,
                                'price2' => $option_unformated_price,
                                'price_prefix' => $option_value['price_prefix']
                            );
                        }
                    }

                    $option_data[] = array(
                        'product_option_id' => $option['product_option_id'],
                        'option_id' => $option['option_id'],
                        'metal_type' => $size,
                        'name' => $option['name'],
                        'type' => $option['type'],
                        'option_value' => $option_value_data,
                        'required' => $option['required']
                    );
                } elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {

                    $option_data[] = array(
                        'product_option_id' => $option['product_option_id'],
                        'option_id' => $option['option_id'],
                        'name' => $option['name'],
                        'type' => $option['type'],
                        'option_value' => $option['option_value'],
                        'required' => $option['required']
                    );
                }
            }
        }

        $data['product_grouped'] = $product_grouped;
        $this->load->model('catalog/unit_conversion');
        $DefaultUnitdata = $this->model_catalog_unit_conversion->getDefaultUnitDetails($this->request->get['product_id']);

        if (isset($data['unit_datas'])) {
            $json['unit_datas'] = $data['unit_datas'];
            $json['DefaultUnitdata'] = $DefaultUnitdata;
        } else {
            $json['unit_datas'] = '';
            $json['DefaultUnitdata'] = '';
        }
        $selected_options = '';
        $json['discounts'] = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
        $option_data = $this->_reOrderOption($this->request->get['product_id'], $option_data);

        $json['option_data'] = $option_data;
        $data['product_info'] = $product_info; // = $this->model_catalog_product->getProduct($this->request->get['product_id']);
        $data['orignial_price'] = !empty($product_info['orignial_price']) ? $this->currency->format($product_info['orignial_price']) : $this->currency->format($product_info['price']);
        if (!empty($this->request->get['customer_group_id'])) {
            $customer_group_id = $this->request->get['customer_group_id'];

            if (isset($this->session->data['discount_customer_group_id'])) {
                $customer_group_id = $this->session->data['discount_customer_group_id'];
            }

            $customer_group_prices = $this->model_catalog_product->getProductDiscountsByCustomerGroud($this->request->get['product_id'], $customer_group_id);
            foreach ($customer_group_prices as $k => $customer_group_price) {
                $customer_group_prices[$k]['price'] = $this->currency->format($customer_group_price['price']);
            }
            $data['customer_group_prices'] = $customer_group_prices;
        }
        $json['data'] = $data;
        $this->response->setOutput(json_encode($json));
    }

    private function _reOrderOption($product_id, $options) {
        $da = array();
        $where = array('product_id' => $product_id);
        $response = $this->model_pos_product_grouped->getCombination($where, '*');
        foreach ($response->row as $key => $value) {
            if (preg_match('/(optionname)/', $key)) {
                if ($value !== '') {
                    $key = preg_replace('/(optionname)/', '', $key);
                    $da[$key - 1] = $value;
                }
            }
        }
        $options = $this->msort($options, $da);

        return $options;
    }

    private function msort($array, $key) {
        $sortedItems = $sorted = array();
        foreach ($key as $value) {
            $opt = $this->findInArray($array, $value);
            if ($opt !== false) {
                $sorted[] = $opt['value'];
                $sortedItems[] = $opt['key'];
            }
        }
        foreach ($array as $k => $value) {
            if (!in_array($k, $sortedItems)) {
                array_push($sorted, $value);
            }
        }
        return $sorted;
    }

    private function findInArray($array, $item) {
        $rarray = array();
        foreach ($array as $key => $ar) {
            if ($ar['name'] == $item) {
                $rarray['value'] = $ar;
                $rarray['key'] = $key;
                return $rarray;
            }
        }
        return false;
    }

    /**
     * Get Product Options on the basis of selected otherwise default first
     */
    public function getGroupOptions($product_id, $groupIndicator, $requestedoprions, $groupbyname) {
        $this->load->model('pos/product_grouped');
        $this->load->model('pos/product');
        $this->load->model('catalog/product');
        $this->load->model('catalog/unit_conversion');

        if (isset($this->request->post['group_indicator']))
            $groupIndicator = $this->request->post['group_indicator'];
        if (isset($this->request->post['selChoice'])) {
            foreach ($this->request->post['selChoice'] as $opt) {
                $p = explode('~', $opt);
                $selOpt[] = $p[1];
            }
        } else {
            $selOpt = array();
        }

        if (!empty($requestedoprions)) {
            $opcount = $requestedoprions['option_count'];
            for ($i = 1; $i <= $opcount; $i++) { // ($requestedoprions as $opt) {
                $selOpt[] = $requestedoprions['optionvalue' . $i];
            }
        }
        //print_r($selOpt);
        if (isset($this->request->post['product_id']))
            $product_id = $this->request->post['product_id'];

        //echo $product_id;
        $product_info = $this->model_pos_product->getProduct($product_id);
        $product_info['group_indicator'] = $groupIndicator;

        /* if ($product_info['discounted_price']) {
          $discount_percent = $this->cart->calcMetalTypeDiscount($product_info['discounted_price'], $product_info['orignial_price']);
          } else { */
        $discount_percent = 1;
        //}
        //    $data['old_price'] = $product_info['orignial_price'];
        //             $data['options'] = array();
        //$data['unit_conversion_help'] = $this->model_pos_product_grouped->getConversionHelp($product_info['product_id']);
//                $formula = FALSE;
//                $data['base_price'] = $product_info['orignial_price'];
        //           $data['unit_datas'] = $this->_getProductUnits($product_info['product_id']);
        //echo $groupbyname;
        $data['option_data'] = $this->_getproductOptionData($product_id, $product_info, $selOpt, $groupIndicator, $groupbyname);

        //print_r($data['option_data']);
        //		$DefaultUnitdata   = $this->model_catalog_unit_conversion->getDefaultUnitDetails($product_id);

        /* 	if(isset( $data['unit_datas'])){
          $json['unit_datas'] =  $data['unit_datas'];
          $json['DefaultUnitdata'] = $DefaultUnitdata;
          }else{
          $json['unit_datas'] = '';
          $json['DefaultUnitdata'] = '';
          }
          $selected_options = '';
          $data['groupbyname'] = $this->request->post['groupbyname'];
          $data['group_indicator_id'] = $groupIndicator;
          $json['discounts'] = $this->model_catalog_product->getProductDiscounts($product_id);
          $json['data'] = $data;
          echo json_encode($json); */
        return $data['option_data'];
    }

    private function _getproductOptionData($product_id, $product_info, $selected_options, $groupIndicator, $groupbyname) {
        // pr(array('groupindicator'=>$groupIndicator,'product_id'=>$product_info['group_indicator']));
        $this->customer = new Customer($this->registry);
        $this->tax = new Tax($this->registry);
        $this->cart = new Cart($this->registry);
        $opData = $this->model_pos_product_grouped->getOptionsNames(array('groupindicator' => $groupIndicator, 'product_id' => $groupIndicator));
        $option_data = "";
        /* if ($product_info['discounted_price']) {
          $discount_percent = $this->cart->calcMetalTypeDiscount($product_info['discounted_price'], $product_info['orignial_price']);
          } else { */
        $discount_percent = 1;
        //}
        //$productOptions = $this->model_pos_product->getProductOptions($product_id, $stat = 1);
        $requested_product_data = $this->model_pos_product_grouped->getGroupedData($product_id, $groupIndicator);
        $data['requested_product_data'] = $requested_product_data;
        //print_r($data['requested_product_data']);
        foreach ($this->model_pos_product->getProductOptions($product_id, $stat = 1) as $option) {
            //print_r($option);
            if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
                $option_value_data = array();
                $size = 0;
                //REQUESTED PRODUCT CODE
                $is_requested_option = FALSE;
                if ($product_id != 0 && $requested_product_data['option_count'] > 0) {
                    $requested_option_exists = array_search(trim($option['name']), array_reverse($requested_product_data));
                    if ($requested_option_exists !== FALSE) {
                        $is_requested_option = TRUE;
                    }
                }
                //REQUESTED PRODUCT CODE ENDS
                foreach ($option['option_value'] as $option_value) {
                    //REQUESTED PRODUCT CODE
                    $is_requested_option_value = FALSE;
                    if ($is_requested_option === TRUE && $option_value['name'] == $requested_product_data['optionvalue' . filter_var($requested_option_exists, FILTER_SANITIZE_NUMBER_INT)]) {
                        $is_requested_option_value = TRUE;
                    }
                    //REQUESTED PRODUCT CODE ENDS
                    if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                        $option_unformated_price = $this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                        if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']) {
                            $price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                        } else {
                            $price = false;
                        }



                        $option_value_data[] = array(
                            'product_option_value_id' => $option_value['product_option_value_id'],
                            'quantity' => $option_value['quantity'],
                            'option_value_id' => $option_value['option_value_id'],
                            'name' => $option_value['name'],
                            'price' => $price,
                            'price2' => $option_unformated_price,
                            'price_prefix' => $option_value['price_prefix'],
                            'is_requested_option_value' => $is_requested_option_value//REQUESTED PRODUCT CODE
                        );
                    }
                }

                $data['options'][] = array(
                    'product_option_id' => $option['product_option_id'],
                    'option_id' => $option['option_id'],
                    'metal_type' => $size,
                    'name' => $option['name'],
                    'type' => $option['type'],
                    'option_value' => $option_value_data,
                    'required' => $option['required']
                );
            } elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {

                $data['options'][] = array(
                    'product_option_id' => $option['product_option_id'],
                    'option_id' => $option['option_id'],
                    'name' => $option['name'],
                    'type' => $option['type'],
                    'option_value' => $option['option_value'],
                    'required' => $option['required']
                );
            }
        }
        /* foreach ($productOptions as $option) {
          if ($option['type'] == 'select' || $option['type'] == 'radio') {
          $option_value_data = array();
          $size = 0;
          foreach ($option['option_value'] as $option_value) {
          //                    if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {

          if ($option['type'] == 'select' || $option['type'] == 'radio') {
          if ($option['metal_type'] == 1) {
          $formula = TRUE;
          $size = sizeof($option['option_value']);
          if ($size == 1) {
          $add_optionsprice = $option_value['price'];
          if ($add_optionsprice != 0) {
          $base_price = ($product_info['orignial_price'] + $add_optionsprice);
          $data['base_price'] = $base_price;
          $product_info['orignial_price'] = ($add_optionsprice + $product_info['orignial_price']) * $discount_percent;
          }
          } else {
          !isset($add_optionsprice) ? $add_optionsprice = 0 : '';
          $base_price = ($add_optionsprice + $product_info['orignial_price']);
          $data['base_price'] = $base_price;
          $product_info['orignial_price'] = $discount_percent * $base_price;
          }
          }
          }
          $option_unformated_price = $this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
          if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']) {
          $price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
          } else {
          $price = false;
          }
          $option_value_data[] = array(
          'product_option_value_id' => $option_value['product_option_value_id'],
          'option_value_id' => $option_value['option_value_id'],
          'name' => $option_value['name'],

          'price' => $price,
          'price2' => $option_unformated_price,
          'price_prefix' => $option_value['price_prefix'],
          'quantity' => $option_value['quantity']
          );
          //                    }
          }

          $data['options'][] = array(
          'product_option_id' => $option['product_option_id'],
          'option_id' => $option['option_id'],
          'metal_type' => $size,
          'name' => $option['name'],
          'type' => $option['type'],
          'option_value' => $option_value_data,
          'required' => $option['required']
          );
          }
          } */
        //print_r($productOptions);


        if (!isset($data['options'])) {
            $data['options'] = array();
        } else {
            $data['options'] = $this->_checkCombinationExist($opData, $data['options'], $product_info['group_indicator'], $selected_options, $groupbyname);
        }

        /* if (isset($this->request->post['is_quick_order'])) {
          $option_class = 'option_qv';
          } else {
          $option_class = 'option';
          }
          if (!empty($data['options'])) {
          foreach ($data['options'] as $key => $option) {
          if ($option['type'] == 'select') {
          $UnitArry = explode(' ', $option['name']);
          if (strtolower(end($UnitArry)) != "units") {
          $name = str_replace(' ', '', $option['name']);
          $name = str_replace('?', '', $name);
          $option_data .="<div id='option-" . $option['product_option_id'] . "' class='" . $option_class . " ig_" . str_replace(' ', '', $name) . "' title='ig_" . str_replace(' ', '', $option['name']) . "'>";
          if ($option['required']) {
          $option_data .="<span class='required'>*</span>";
          }

          $option_data.="<b>" . $option['name'] . ":</b>";
          $option_data.= "<select name='option[" . $option['product_option_id'] . "]'>";

          if ($option['metal_type'] > 1) {
          //$option_data.="<option value=''></option>";
          }
          $ophtml = array();
          $selectflag = false;
          foreach ($option['option_value'] as $option_value) {
          $option_price = $option_value['price2'] ? $option_value['price2'] : '';
          $selected = in_array($option_value['name'], (array) $selected_options) ? 'selected=selected' : '';
          if ($selectflag == false && $selected != '') {
          $selectflag = true;
          }
          $option_value_image = '';
          if (!empty($option_value['image']) && !strpos($option_value['image'], 'no_image')) {
          $option_value_image = $option_value['image'];
          }
          if ($option_value['quantity'] <= 0) {
          $ophtml[] = "<option qty='" . $option_value['quantity'] . "' data-option_value_image='" . $option_value_image . "' data-price='" . $option_price . "' " . $selected . " value='" . $option_value['product_option_value_id'] . "'>"
          . "" . $option_value['name'] . " - " . $this->language->get('option_out_of_stock') .
          "</option>";
          } else {
          $ophtml[] = "<option qty='" . $option_value['quantity'] . "' data-option_value_image='" . $option_value_image . "' data-price='" . $option_price . "' " . $selected . " value='" . $option_value['product_option_value_id'] . "'>"
          . "" . $option_value['name'] .
          "</option>";
          }
          }

          if ($selectflag == false) {
          $ophtml[0] = str_replace("value=", "selected='selected' value=", $ophtml[0]);
          }
          $option_data.=implode('', $ophtml);
          $option_data.="</select>";
          $option_data.="</div>";
          }
          }

          if ($option['type'] == 'radio') {
          $UnitArry = explode(' ', $option['name']);
          if (strtolower(end($UnitArry)) != "units") {
          $option_data .="<div id='option-" . $option['product_option_id'] . "' class='" . $option_class . " ig_" . str_replace(' ', '', $option['name']) . "'>";
          if ($option['required']) {
          $option_data .="<span class='required'>*</span>";
          }
          $option_data.="<b>" . $option['name'] . ":</b>";

          foreach ($option['option_value'] as $option_value) {
          $option_price = $option_value['price2'] ? $option_value['price2'] : '';
          $option_data.= "<input data-price='" . $option_price . "' type='radio' name='option[" . $option['product_option_id'] . "]' value='" . $option_value['product_option_value_id'] . "' id='option-value-." . $option_value['product_option_value_id'] . "' />";
          $option_data.= "<label for='option-value-" . $option_value['product_option_value_id'] . " data-val='" . $option_value['name'] . "' >" . $option_value['name'];
          $option_data.= "</label>";
          }

          $option_data.="</div>";
          }
          }
          }
          } */
        //print_r($data['options']);
        return $data['options'];
    }

    private function _checkCombinationExist($group, $options, $product_id, $selop, $groupbyname) {
        $disabledProducts = array();
        $owhere = $revisedOptions = $narray = $tmp = array();
        $gi = $product_id;
        $disabledProducts = $this->model_pos_product_grouped->getProductStatus($gi);
        $options = $this->_reOrderOption($product_id, $options);
        //print_r($options);
        if (isset($this->request->post['groupbyname'])) {
            $groupbyname = $this->request->post['groupbyname'];
        }
        //print_r($selop);

        foreach ($options as $key => $option) {
            if (!isset($selop[$key])) {
                $selop[$key] = $option['option_value'][0]['name'];
            }
            $inc = $key + 1;
            $optValues = $option['option_value'];
            unset($option['option_value']);
            foreach ($optValues as $optValue) {
                $where = array(
                    'groupindicator_id' => $gi,
                    'groupbyvalue' => $groupbyname,
                    'optionname' . $inc => $option['name'],
                    'optionvalue' . $inc => $optValue['name']
                );
                $owhere = array_merge($narray, $where);
                $response = $this->model_pos_product_grouped->getCombination($owhere);
                if ($response->num_rows > 0) {
                    if ($response->num_rows == 1 && !in_array($response->row['product_id'], $disabledProducts)) {
                        $option['option_value'][] = $optValue;
                    } else if ($response->num_rows > 1) {
                        $option['option_value'][] = $optValue;
                    }
                    if ($selop[$key] == $optValue['name']) {
                        $tmp = array_merge($narray, $where);
                    }
                }
            }
            if (!isset($option['option_value'])) {
                $option['option_value'] = array();
            }
            $revisedOptions[] = $option;
            $narray = array_merge($narray, $tmp);
            $key++;
        }
        return $revisedOptions;
    }

    /* 	 */

    public function getCombinationData() {
        $this->load->model('pos/product_grouped');
        $this->load->model('pos/product');
        $selected_options = array();

        if (isset($this->request->post['selChoice'])) {
            $selected_options = $this->request->post['selChoice'];
        }

        $groupbyvalue = $this->request->post['groupbyvalue'];
        $gi = $this->request->post['group_indicator'];
        $owhere = array(
            'groupindicator_id' => $gi,
            'groupbyvalue' => $groupbyvalue
        );
        foreach ($selected_options as $key => $op) {
            $o = explode('~', $op);
            $where = array();
            $k = $key + 1;
            $where['optionname' . $k] = $o[0];
            $where['optionvalue' . $k] = $o[1];
            $owhere = array_merge($owhere, $where);
        }

        $response = $this->model_pos_product_grouped->getCombination($owhere);
        // pr($response); die;
        if ($response->num_rows <= 0) {
            $data['error'] = "Product not found";
            echo json_encode($data);
            die;
        }
//        $sku=$response->row['sku'];
//        $product_id=$this->model_catalog_product_grouped->getproductIdBySku($sku);
        $product_id = $response->row['product_id'];

        $product_info = $this->model_pos_product->getProduct($product_id);
        $data['unit_dates_default'] = $this->model_pos_product->getDefaultUnitDetails($product_id);
        $data['DefaultUnitName'] = $this->model_pos_product->getDefaultUnitName($product_id);
        $data['name'] = $product_info['name'];
        $data['product_id'] = $product_id;
        $data['price'] = $this->currency->format($product_info['price']);
        if (isset($product_info['unit_singular'])) {
            $data['unit_singular'] = $product_info['unit_singular'];
        } else {
            $data['unit_singular'] = '';
        }
        if (isset($product_info['unit_plural'])) {
            $data['unit_plural'] = $product_info['unit_plural'];
        } else {
            $data['unit_plural'] = '';
        }
        /* $data['unit'] = $product_info['unit_singular'];
          if ($product_info['quantity'] <= 0) {
          $data['stock_status'] = "<span class='outofstock'></span>";
          //            $data['stock_status'] = $product_info['stock_status'];
          } elseif ($this->config->get('config_stock_display')) {
          $data['stock_status'] = $product_info['quantity'];
          } else {
          //            $data['stock_status'] = "In Stock";
          $data['stock_status'] = "<span class='inofstock'></span>";
          } */
        $data['sku'] = $product_info['sku'];
        $data['model'] = $product_info['model'];
        $data['base_price'] = $product_info['orignial_price'];
        $data['unit_datas'] = $this->_getProductUnits($product_info['product_id']);
        //$data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');

        /* $results = $this->model_catalog_product->getProductImages($product_id);

          $data['additional_images'] ='';
          foreach ($results as $result) {
          $popup = $this->model_tool_image->resize($result['image'], 500, 500);
          $thumb = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'));
          $data['additional_images'] .= '<a href="'.$popup.'" title="'.$product_info['name'].'" class="changeMainGroup"><img src="'.$thumb.'" title="'.$product_info['name'].'" /></a>';

          }

          if ($product_info['image']) {
          $data['image'] = $data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
          $data['large_image'] = $data['thumb'] = $this->model_tool_image->resize($product_info['image'], 500, 500);
          } else {
          $data['image'] = $data['thumb'] = $this->model_tool_image->resize('data/product/no_product.jpg', $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
          $data['large_image'] = $data['thumb'] = $this->model_tool_image->resize('data/product/no_product.jpg', 500, 500);
          }
         */



        $data['base_price'] = $product_info['orignial_price'];
        $data['discounted_price'] = $product_info['discounted_price'];
        // $data['reviews'] = $product_info['reviews'] . " Reviews";
        //$data['tab_review'] = "Reviews(" . $product_info['reviews'] . ")";
        //$data['rating'] = (int) $product_info['rating'];
        $data['sku'] = $product_info['sku'];
        // $data['attribute_html'] = $this->_getProductAtrriData($product_id);
        // $total_qas = $this->model_catalog_product->getTotalQAsByProductIdXML($product_id);
        //$data['text_tab_qa'] = "Q & A(" . $total_qas . ")";
        //$data['add_image_data'] = $this->_getAdditionalImageData($product_id, $data['name']);
        //$quantity = ($this->request->post['quantity'] != '') ? $this->request->post['quantity'] : 1;
        //$unit_conversion_text = $this->request->post['unit_conversion_text'];
        /* $unit_data = $this->_getProductUnitVariables($product_id, $unit_conversion_text);
          if (!empty($unit_data['unit_datas_html'])) {
          $data['product_unit_data_ajax'] = $unit_data['unit_datas_html'];
          $conversion_price = $unit_data['selected_unit_price'];
          if (!empty($data['product_unit_data_ajax']) && $conversion_price != '') {
          $data['price'] = $this->_calConvertPrice($data['base_price'], $quantity, $conversion_price, $product_id);
          $data['unit'] = $unit_conversion_text;
          } else {
          $data['price'] = $this->_getQuanityPrice($data['base_price'], $quantity, $product_id);
          $data['unit'] = ($quantity > 1) ? $product_info['unit_singular'] : $product_info['unit_plural'];
          }
          } else {
          $data['price'] = $data['discounted_price'] ? $this->currency->format($data['discounted_price']) : $data['price'];
          }
          if ($logged) {
          $data['get_product_discount'] = $this->_getproductdiscount($product_id, $product_info['tax_class_id'], $product_info['unit_plural']);
          } */

        echo json_encode($data);
    }

    // add for Browse end
    // add for Quick sale begin
    public function updateQSProduct() {
        $data = array();
        $keys = array_keys($this->request->post);
        foreach ($keys as $key) {
            $value = $this->request->post[$key];
            if (strpos($key, 'quick_sale_') === 0) {
                $dataKey = substr($key, 11);
                $data[$dataKey] = $value;
            }
        }
        $this->load->model('pos/pos');
        $product_id = $this->model_pos_pos->updateQSProduct($data);

        $json = array();
        if ($product_id > 0) {
            $json['product_id'] = $product_id;
            $data['product_id'] = $product_id;
        }

        // modify order
        $data['action'] = 'insert_quick';

        $this->response->setOutput(json_encode($json));
    }

    // add for Quick sale end
    // add for status change notification begin
    private function sendNotification($order_id, $order_status_id) {
        $this->load->model('sale/order');
        $order_info = $this->model_sale_order->getOrder($order_id);

        if ($order_info) {
            $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

            // Order Totals
            $order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int) $order_id . "' ORDER BY sort_order ASC");

            // Send out order confirmation mail
            $order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int) $order_status_id . "' AND language_id = '" . (int) $order_info['language_id'] . "'");

            if ($order_status_query->num_rows) {
                $order_status = $order_status_query->row['name'];
            } else {
                $order_status = '';
            }

            $subject = sprintf($this->language->get('text_new_subject'), $order_info['store_name'], $order_id);

            $data['title'] = sprintf($this->language->get('text_new_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);

            $data['text_greeting'] = sprintf($this->language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
            $data['text_link'] = $this->language->get('text_new_link');
            $data['text_download'] = $this->language->get('text_new_download');
            $data['text_order_detail'] = $this->language->get('text_new_order_detail');
            $data['text_instruction'] = $this->language->get('text_new_instruction');
            $data['text_order_id'] = $this->language->get('text_new_order_id');
            $data['text_date_added'] = $this->language->get('text_new_date_added');
            $data['text_payment_method'] = $this->language->get('text_new_payment_method');
            $data['text_shipping_method'] = $this->language->get('text_new_shipping_method');
            $data['text_email'] = $this->language->get('text_new_email');
            $data['text_telephone'] = $this->language->get('text_new_telephone');
            $data['text_ip'] = $this->language->get('text_new_ip');
            $data['text_payment_address'] = $this->language->get('text_new_payment_address');
            $data['text_shipping_address'] = $this->language->get('text_new_shipping_address');
            $data['text_product'] = $this->language->get('text_new_product');
            $data['text_model'] = $this->language->get('text_new_model');
            $data['text_quantity'] = $this->language->get('text_new_quantity');
            $data['text_price'] = $this->language->get('text_new_price');
            $data['text_total'] = $this->language->get('text_new_total');
            $data['text_footer'] = $this->language->get('text_new_footer');
            $data['text_powered'] = $this->language->get('text_new_powered');

            $data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
            $data['store_name'] = $order_info['store_name'];
            $data['store_url'] = $order_info['store_url'];
            $data['customer_id'] = $order_info['customer_id'];
            $data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;

            $data['download'] = '';

            $data['order_id'] = $order_id;
            $data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
            $data['payment_method'] = $order_info['payment_method'];
            $data['shipping_method'] = $order_info['shipping_method'];
            $data['email'] = $order_info['email'];
            $data['telephone'] = $order_info['telephone'];
            $data['ip'] = $order_info['ip'];
            $data['comment'] = '';

            if ($order_info['payment_address_format']) {
                $format = $order_info['payment_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['payment_firstname'],
                'lastname' => $order_info['payment_lastname'],
                'company' => $order_info['payment_company'],
                'address_1' => $order_info['payment_address_1'],
                'address_2' => $order_info['payment_address_2'],
                'city' => $order_info['payment_city'],
                'postcode' => $order_info['payment_postcode'],
                'zone' => $order_info['payment_zone'],
                'zone_code' => $order_info['payment_zone_code'],
                'country' => $order_info['payment_country']
            );

            $data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            if ($order_info['shipping_address_format']) {
                $format = $order_info['shipping_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['shipping_firstname'],
                'lastname' => $order_info['shipping_lastname'],
                'company' => $order_info['shipping_company'],
                'address_1' => $order_info['shipping_address_1'],
                'address_2' => $order_info['shipping_address_2'],
                'city' => $order_info['shipping_city'],
                'postcode' => $order_info['shipping_postcode'],
                'zone' => $order_info['shipping_zone'],
                'zone_code' => $order_info['shipping_zone_code'],
                'country' => $order_info['shipping_country']
            );

            $data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            // Products
            $data['products'] = array();

            foreach ($order_product_query->rows as $product) {
                $option_data = array();

                $order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $product['order_product_id'] . "'");

                foreach ($order_option_query->rows as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
                    }

                    $option_data[] = array(
                        'name' => $option['name'],
                        'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                    );
                }

                $data['products'][] = array(
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'option' => $option_data,
                    'quantity' => $product['quantity'],
                    'price' => $this->currency->formatFront($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'total' => $this->currency->formatFront($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
                );
            }

            $data['totals'] = $order_total_query->rows;
            foreach ($data['totals'] as $key => $total) {
                $data['totals'][$key]['text'] = $this->currency->formatFront($total['value']);
            }

            $template_file = 'mail/order.tpl';

            return $this->send_html_email($template_file, $data);
        }
        return true;
    }

    // add for status change notification end
    // add for table management begin
    public function addTable() {
        $json = array();

        $this->load->model('pos/pos');
        $table_id = $this->model_pos_pos->addTable($this->request->post);
        if ($table_id) {
            $json['table_id'] = $table_id;
        }

        $this->response->setOutput(json_encode($json));
    }

    public function deleteTable() {
        $json = array();

        $this->load->model('pos/pos');
        $this->model_pos_pos->deleteTable($this->request->post);

        $this->response->setOutput(json_encode($json));
    }

    public function addTableBatch() {
        $json = array();

        $this->load->model('pos/pos');
        $json['table_ids'] = $this->model_pos_pos->addTableBatch($this->request->post);

        $this->response->setOutput(json_encode($json));
    }

    public function deleteTableBatch() {
        $json = array();

        $this->load->model('pos/pos');
        $this->model_pos_pos->deleteTableBatch($this->request->post);

        $this->response->setOutput(json_encode($json));
    }

    public function saveOrderTableId() {
        $order_id = $this->request->post['order_id'];
        $table_id = $this->request->post['table_id'];
        $this->load->model('pos/pos');
        $this->model_pos_pos->saveOrderTableId($order_id, $table_id);

        $this->language->load('module/pos');
        $json['success'] = $this->language->get('text_order_success');

        $this->response->setOutput(json_encode($json));
    }

    // add for table management end
    public function image() {
        if (isset($this->request->get['image'])) {
            $filename = $this->request->get['image'];
            if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
                return;
            }

            $old_image = $filename;
            $new_image = 'cache/' . $filename;

            if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
                $path = '';

                $directories = explode('/', dirname(str_replace('../', '', $new_image)));

                foreach ($directories as $directory) {
                    $path = $path . '/' . $directory;

                    if (!file_exists(DIR_IMAGE . $path)) {
                        @mkdir(DIR_IMAGE . $path, 0777);
                    }
                }

                copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
            }

            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $this->response->setOutput(HTTPS_CATALOG . 'image/' . $new_image);
            } else {
                $this->response->setOutput(HTTP_CATALOG . 'image/' . $new_image);
            }
        }
    }

    // add for product return begin
    public function addReturn() {
        $this->load->model('pos/pos');
        $return_id = $this->model_pos_pos->addReturn($this->request->post);

        $this->language->load('module/pos');
        $json = array('success' => $this->language->get('text_product_returned_successfully'), 'return_id' => $return_id);
        $this->response->setOutput(json_encode($json));
    }

    public function editReturn() {
        $this->load->model('pos/pos');
        $data = $this->request->post;
        $this->language->load('module/pos');
        $json = array('success' => $this->language->get('text_product_returned_successfully'));

        if (isset($data['new_total'])) {
            // total price change, calculate the tax and price for the total
            $tax_class_id_query = $this->db->query("SELECT tax_class_id FROM `" . DB_PREFIX . "product` WHERE product_id = '" . $data['product_id'] . "'");
            $customer_group_id_query = $this->db->query("SELECT customer_group_id FROM `" . DB_PREFIX . "order` WHERE order_id = '" . $data['order_id'] . "'");
            $customer_group_id = $customer_group_id_query->row ? $customer_group_id_query->row['customer_group_id'] : $this->config->get('config_customer_group_id');
            $price = $this->getPriceFromPriceWithTax((float) $data['new_total'] / (float) $data['weight'] / (int) $data['org_quantity'], $tax_class_id_query->row['tax_class_id'], $customer_group_id);
            $data['tax_change'] = (float) $data['new_total'] - (int) $data['org_quantity'] * (float) $data['weight'] * ($price + (float) $data['tax']);
            $data['price_change'] = (int) $data['org_quantity'] * (float) $data['weight'] * ($price - (float) $data['price']);
            $data['tax'] = (float) $data['tax'] + $data['tax_change'] / (float) $data['weight'] / (int) $data['org_quantity'];
            $data['price'] = (float) $data['price'] + $data['price_change'] / (float) $data['weight'] / (int) $data['org_quantity'];

            $json['tax_change'] = $data['tax_change'];
            $json['price_change'] = $data['price_change'];
            $json['order_product_id'] = $data['order_product_id'];
        }
        $items_in_cart = $this->model_pos_pos->editReturn($data);
        $json['items_in_cart'] = $items_in_cart;

        $this->response->setOutput(json_encode($json));
    }

    public function saveReturnAction() {
        $this->load->model('pos/pos');
        $affected = $this->model_pos_pos->saveReturnAction($this->request->post);

        $this->language->load('module/pos');
        $json = array('success' => $this->language->get('text_product_returned_successfully'), 'affected' => $affected);
        $this->response->setOutput(json_encode($json));
    }

    public function getReturnDetails() {
        $json = array();

        $this->load->model('pos/pos');
        $return_details = $this->model_pos_pos->getReturnDetails($this->request->get['return_id']);

        $this->language->load('module/pos');
        $json['product'] = $return_details['product'];
        $json['model'] = $return_details['model'];
        $json['quantity'] = $return_details['quantity'];
        $json['opened'] = $return_details['opened'] ? $this->language->get('text_opened') : $this->language->get('text_unopened');
        $json['reason'] = $return_details['reason'];
        $json['comment'] = $return_details['comment'];
        $json['return_time'] = strtotime($return_details['date_modified']);
        $json['options'] = '';
        if (!empty($return_details['weight_name'])) {
            $json['options'] .= $return_details['weight_name'] . ": " . $return_details['weight'] . "\n";
        }
        if (!empty($return_details['sn'])) {
            $json['options'] .= "SN: " . $return_details['sn'] . "\n";
        }
        if (!empty($return_details['option'])) {
            foreach ($return_details['option'] as $option) {
                $json['options'] .= $option['name'] . ": " . $option['value'] . "\n";
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function checkReturn() {
        $this->load->model('pos/pos');
        $returned_quantity = $this->model_pos_pos->checkReturnedQuantity($this->request->post['order_product_id'], $this->request->post['return_id']);

        $json = array('quantity' => $returned_quantity);
        $this->response->setOutput(json_encode($json));
    }

    // add for product return end

    private function getEmptyOrder($is_quote = false) {
        $order_id = 0;
        // first find the order with the maximum id that were previously in cart and not fininshed
        $now = date("Y-m-d H:i:s");
        /* $query = $this->db->query("SELECT o.order_id, o.user_id FROM `" . DB_PREFIX . "order` o INNER JOIN `" .DB_PREFIX . "order_product` op ON o.order_id = op.order_id  WHERE o.user_id IS NOT NULL AND o.user_id = '" . (int)$this->user->getId() ."' AND o.order_status_id = 22 AND TIME_TO_SEC(TIMEDIFF('" . $now . "', `date_added`))/3600 < 24 ORDER BY o.order_id DESC LIMIT 1"); */

        $query = $this->db->query("SELECT order_id, user_id FROM `" . DB_PREFIX . "order` WHERE user_id IS NOT NULL AND user_id = '" . (int) $this->user->getId() . "' AND order_status_id = 2 AND is_pos = 1 AND TIME_TO_SEC(TIMEDIFF('" . $now . "', `date_added`))/3600 < 24 ORDER BY order_id DESC LIMIT 1");
        if ($query->row) {
            $order_id = $query->row['order_id'];
        } else {
            // find the order with the maximum id and with no order product added
            $max_order_id_query = $this->db->query("SELECT order_id, user_id FROM `" . DB_PREFIX . "order` WHERE user_id IS NOT NULL AND user_id <> '-1' AND user_id <> '-2' AND is_pos = 1 ORDER BY order_id DESC LIMIT 1");
            if ($max_order_id_query->row && $max_order_id_query->row['user_id'] == $this->user->getId()) {
                $order_products_query = $this->db->query("SELECT order_product_id FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . $max_order_id_query->row['order_id'] . "' LIMIT 1");
                if ($order_products_query->num_rows == 0) {
                    // no order product for this order, consider it as an empty order
                    $order_id = $max_order_id_query->row['order_id'];
                }
            }
        }
        if (!$order_id) {
            // the last order is not an empty order, create a new one
            $order_id = $this->createEmptyOrder($is_quote);
        } elseif ($is_quote) {
            // it's a quote
            $quote_query = $this->db->query("SELECT MIN(quote_status_id) AS min_quote_status_id FROM `" . DB_PREFIX . "quote_status`");
            if ($quote_query->row) {
                $this->db->query("UPDATE `" . DB_PREFIX . "order` SET quote_status_id = '" . $quote_query->row['min_quote_status_id'] . "' WHERE order_id = '" . $order_id . "'");
            }
        } else {
            // it's an order, reset status to initial status for order
            $order_status_id = 1;
            if ($this->config->get('POS_initial_status_id')) {
                $order_status_id = $this->config->get('POS_initial_status_id');
            }
            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . $order_status_id . "', quote_status_id = '0' WHERE order_id = '" . $order_id . "'");
        }
        return $order_id;
    }

    private function getEmptyReturn($order_id) {
        $pos_return_id = 0;
        // find the return with the maximum id and with no product added
        $max_return_id_query = $this->db->query("SELECT pos_return_id, user_id, customer_id FROM `" . DB_PREFIX . "pos_return` WHERE user_id IS NOT NULL ORDER BY pos_return_id DESC LIMIT 1");
        $customer_query = $this->db->query("SELECT customer_id FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $order_id . "'");
        if ($max_return_id_query->row && $max_return_id_query->row['user_id'] == $this->user->getId()) {
            if (((int) $order_id == 0 && (int) $max_return_id_query->row['customer_id'] == 0) || ($customer_query->row && $customer_query->row['customer_id'] == $max_return_id_query->row['customer_id'])) {
                $return_products_query = $this->db->query("SELECT order_product_id FROM `" . DB_PREFIX . "return` WHERE pos_return_id = '" . $max_return_id_query->row['pos_return_id'] . "' LIMIT 1");
                if ($return_products_query->num_rows == 0) {
                    // no product for this return and it's the right customer, consider it as an empty return
                    $pos_return_id = $max_return_id_query->row['pos_return_id'];
                }
            }
        }
        if (!$pos_return_id) {
            // the last return is not an empty return, create a new one
            if ($customer_query->row) {
                $pos_return_id = $this->createEmptyReturn($customer_query->row['customer_id']);
            } else {
                $pos_return_id = $this->createEmptyReturn(0);
            }
        } else {
            // reset status to initial status for return
            $return_status_id = 1;
            if ($this->config->get('POS_initial_return_status_id')) {
                $return_status_id = $this->config->get('POS_initial_return_status_id');
            }
            $this->db->query("UPDATE `" . DB_PREFIX . "pos_return` SET return_status_id = '" . $return_status_id . "', tax = '0', sub_total = '0' WHERE pos_return_id = '" . $pos_return_id . "'");
        }
        return $pos_return_id;
    }

    public function autocomplete_customers() {
        $json = array();

        if (isset($this->request->post['filter_name'])) {
            $this->load->model('pos/pos');
            $this->load->model('tool/image');
            $this->load->model('catalog/product');
            $this->load->model('pos/extended_product');

            $filter_name = $this->request->post['filter_name'];

            if (!empty($this->request->post['filter_scopes'])) {
                $filter_scopes = $this->request->post['filter_scopes'];
            } else {
                $filter_scopes = array('name');
            }

            if (isset($this->request->post['limit'])) {
                $limit = $this->request->post['limit'];
            } else {
                $limit = 20;
            }

            $data = array(
                'filter_name' => $filter_name,
                'filter_scopes' => $filter_scopes,
                'start' => 0,
                'limit' => $limit
            );

            if (isset($this->request->post['quick_sale'])) {
                $data['quick_sale'] = $this->request->post['quick_sale'];
            }
            if (isset($this->request->post['customer_group_id'])) {
                $customer_group_id = $this->request->post['customer_group_id'];
            } else {
                $customer_group_id = $this->config->get('config_customer_group_id');
            }
            $results = $this->model_pos_pos->getCustomers($data);

            foreach ($results as $result) {
                $json[] = array(
                    'customer_id' => $result['customer_id'],
                    'name' => $result['name'],
                    'email' => $result['email'],
                    'company' => $this->getCustomerCompany($result['customer_id'])
                );
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function getCustomerCompany($customer_id) {
        $company = $this->db->query("SELECT company FROM " . DB_PREFIX . "address WHERE customer_id='" . $customer_id . "' ");
        if ($company->num_rows) {
            return $company->row['company'];
        } else {
            return "";
        }
    }

    public function autocomplete_product_pos() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('pos/pos');
            $this->load->model('tool/image');
            $this->load->model('catalog/product');
            $this->load->model('pos/extended_product');

            $filter_name = $this->request->get['filter_name'];

            if (!empty($this->request->get['filter_scopes'])) {
                $filter_scopes = $this->request->get['filter_scopes'];
            } else {
                $filter_scopes = array('name');
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 20;
            }

            $data = array(
                'filter_name' => $filter_name,
                'filter_scopes' => $filter_scopes,
                'start' => 0,
                'limit' => $limit
            );

            if (isset($this->request->post['quick_sale'])) {
                $data['quick_sale'] = $this->request->post['quick_sale'];
            }
            if (isset($this->request->post['customer_group_id'])) {
                $customer_group_id = $this->request->post['customer_group_id'];
            } else {
                $customer_group_id = $this->config->get('config_customer_group_id');
            }
            $results = $this->model_pos_pos->getProductsForBrowse($data);

            foreach ($results as $result) {
                $quantity = $result['quantity'];
                $price = $result['price'];
                /* $product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$result['product_id'] . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

                  if ($product_special_query->num_rows) {
                  $price = $product_special_query->row['price'];
                  } */
                // calculate price with tax
                $price_after_tax = ($this->config->get('config_tax')) ? $this->calculateTax($price, $result['tax_class_id'], true, $customer_group_id) : $price;
                $tax = $price_after_tax - $price;

                // Reward Points
                /* $product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$result['product_id'] . "' AND customer_group_id = '" . (int)$customer_group_id . "'");

                  if ($product_reward_query->num_rows) {
                  $reward = $product_reward_query->row['points'];
                  } else {
                  $reward = 0;
                  } */
                $reward = 0;
                /* $product_sn_query = $this->db->query("SELECT COUNT(product_sn_id) AS total FROM `" . DB_PREFIX . "product_sn` WHERE product_id = '" . (int)$result['product_id'] . "'"); */
                $has_sn = 0;
                /* if ($product_sn_query->row && $product_sn_query->row['total']) {
                  $has_sn = 1;
                  } */

                $category_id = 0;
                /* $category_query = $this->db->query("SELECT category_id FROM `" . DB_PREFIX . "product_to_category` WHERE product_id = '" . (int)$result['product_id'] . "'");
                  if ($category_query->row) {
                  $category_id = $category_query->row['category_id'];
                  } */

                $json[] = array('type' => 'P',
                    'name' => $result['name'],
                    'image' => (!empty($result['image'])) ? $this->model_tool_image->resize($result['image'], 180, 180) : $this->model_tool_image->resize('no_image.jpg', 180, 180),
                    'price_text' => $this->currency->formatFront($price_after_tax),
                    'stock' => $quantity,
                    'hasOptions' => 1,
                    // add for (update) Weight based price begin
                    'weight_price' => $result['weight_price'],
                    'weight_name' => $result['weight_name'],
                    // add for (update) Weight based price end
                    'parent_category_id' => $category_id,
                    'has_sn' => $has_sn,
                    'price' => $price,
                    'subtract' => $result['subtract'],
                    'tax' => $tax,
                    'tax_class_id' => $result['tax_class_id'],
                    'points' => $result['points'],
                    'reward_points' => $reward,
                    'model' => $result['model'],
                    'description' => $result['description'],
                    'manufacturer' => $result['m_name'],
                    'upc' => $result['upc'],
                    'sku' => $result['sku'],
                    'ean' => $result['ean'],
                    'mpn' => $result['mpn'],
                    'quick_sale' => $result['quick_sale'],
                    'product_discounts' => $this->model_catalog_product->getProductDiscounts($result['product_id']),
                    'product_specials' => $this->model_catalog_product->getProductSpecials($result['product_id']),
                    'product_sns' => $this->model_pos_extended_product->getProductSNs(array('product_id' => $result['product_id'], 'status' => 1)),
                    'commission' => $this->model_pos_extended_product->getCommission($result['product_id']),
                    'product_id' => $result['product_id']);
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function autocomplete_product() {
        $json = array();

        if (isset($this->request->post['filter_name'])) {
            $this->load->model('pos/pos');
            $this->load->model('tool/image');
            $this->load->model('catalog/product');
            $this->load->model('pos/extended_product');

            $filter_name = $this->request->post['filter_name'];

            if (!empty($this->request->post['filter_scopes'])) {
                $filter_scopes = $this->request->post['filter_scopes'];
            } else {
                $filter_scopes = array('name');
            }

            if (isset($this->request->post['limit'])) {
                $limit = $this->request->post['limit'];
            } else {
                $limit = 20;
            }

            $data = array(
                'filter_name' => $filter_name,
                'filter_scopes' => $filter_scopes,
                'start' => 0,
                'limit' => $limit
            );
            if (isset($this->request->post['quick_sale'])) {
                $data['quick_sale'] = $this->request->post['quick_sale'];
            }
            if (isset($this->request->post['customer_group_id'])) {
                $customer_group_id = $this->request->post['customer_group_id'];
            } else {
                $customer_group_id = $this->config->get('config_customer_group_id');
            }
            $results = $this->model_pos_pos->getProductsForBrowse($data);

            foreach ($results as $result) {
                $quantity = $result['quantity'];
                $price = $result['price'];
                /* $product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$result['product_id'] . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

                  if ($product_special_query->num_rows) {
                  $price = $product_special_query->row['price'];
                  } */
                // calculate price with tax
                $price_after_tax = ($this->config->get('config_tax')) ? $this->calculateTax($price, $result['tax_class_id'], true, $customer_group_id) : $price;
                $tax = $price_after_tax - $price;

                // Reward Points
                /* $product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$result['product_id'] . "' AND customer_group_id = '" . (int)$customer_group_id . "'");

                  if ($product_reward_query->num_rows) {
                  $reward = $product_reward_query->row['points'];
                  } else {
                  $reward = 0;
                  } */
                $reward = 0;
                /* $product_sn_query = $this->db->query("SELECT COUNT(product_sn_id) AS total FROM `" . DB_PREFIX . "product_sn` WHERE product_id = '" . (int)$result['product_id'] . "'"); */
                $has_sn = 0;
                /* if ($product_sn_query->row && $product_sn_query->row['total']) {
                  $has_sn = 1;
                  } */

                $category_id = 0;
                /* $category_query = $this->db->query("SELECT category_id FROM `" . DB_PREFIX . "product_to_category` WHERE product_id = '" . (int)$result['product_id'] . "'");
                  if ($category_query->row) {
                  $category_id = $category_query->row['category_id'];
                  } */

                $json[] = array('type' => 'P',
                    'name' => $result['name'],
                    'image' => (!empty($result['image'])) ? $this->model_tool_image->resize($result['image'], 180, 180) : $this->model_tool_image->resize('no_image.jpg', 180, 180),
                    'price_text' => $this->currency->formatFront($price_after_tax),
                    'stock' => $quantity,
                    'hasOptions' => 1,
                    // add for (update) Weight based price begin
                    'weight_price' => $result['weight_price'],
                    'weight_name' => $result['weight_name'],
                    // add for (update) Weight based price end
                    'parent_category_id' => $category_id,
                    'has_sn' => $has_sn,
                    'price' => $price,
                    'subtract' => $result['subtract'],
                    'tax' => $tax,
                    'tax_class_id' => $result['tax_class_id'],
                    'points' => $result['points'],
                    'reward_points' => $reward,
                    'model' => $result['model'],
                    'labour_cost' => number_format((float) $result['labour_cost'], 2, '.', ''),
                    'unique_option_price' => number_format((float) $result['unique_option_price'], 5, '.', ''),
                    'description' => $result['description'],
                    'manufacturer' => $result['m_name'],
                    'upc' => $result['upc'],
                    'sku' => $result['sku'],
                    'ean' => $result['ean'],
                    'mpn' => $result['mpn'],
                    'quick_sale' => $result['quick_sale'],
                    'product_discounts' => $this->model_catalog_product->getProductDiscounts($result['product_id']),
                    'product_specials' => $this->model_catalog_product->getProductSpecials($result['product_id']),
                    'product_sns' => $this->model_pos_extended_product->getProductSNs(array('product_id' => $result['product_id'], 'status' => 1)),
                    'commission' => $this->model_pos_extended_product->getCommission($result['product_id']),
                    'product_id' => $result['product_id']);
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function getDiscountPriceOfProduct($product_id, $customer_group_id, $quantity, $price) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int) $product_id . "' AND customer_group_id = '" . (int) $customer_group_id . "' AND quantity <= '" . $quantity . "' ORDER BY quantity DESC LIMIT 1");

        return $query->row ? $query->row['price'] : $price;
    }

    public function getDiscountedQuantiy($product_id, $coversion_id, $quantity) {
        $query = $this->db->query("SELECT convert_price FROM " . DB_PREFIX . "unit_conversion_product WHERE product_id = '" . (int) $product_id . "' AND unit_conversion_product_id = '" . (int) $coversion_id . "'");

        $convert_price = $query->row ? $query->row['convert_price'] : 1;
        $discounted_quantity = $convert_price * $quantity;
        if ($discounted_quantity < 1) {
            $discounted_quantity = 1;
        }
        return $discounted_quantity;
    }

    public function getReturnData() {
        $output = "";
        $filter_data = array();
        $results = $this->viewtotalForPOS($filter_data);
        foreach ($results as $result) {
            $output .= "<tr>";
            $status = $this->getOrderStat($result['rma_status']);
            $return_product_details = $this->viewProducts($result['id']);
            $return_id = $result['id'];
            $order_id = $result['order_id'];
            $customer = $result['name'];
            $type = $result['return_pos'];
            $product = $return_product_details[0]['name'];
            $model = $return_product_details[0]['model'];
            $price = $return_product_details[0]['price'];
            $quantity = $return_product_details[0]['quantity'];
            $status = $status[0]['name'];
            $date_added = date($this->language->get('date_format_short'), strtotime($result['date']));
            $date_modified = date($this->language->get('date_format_short'), strtotime($result['date']));
            $edit = str_replace("pos", "admin", $this->url->link('catalog/wk_rma_admin/getForm', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . '&out_req', 'SSL'));



            //$output .= "<td><input type='checkbox' name='selected[]' value=".$return_id." /></td>";
            $output .= "<td class='text-right'>" . (($type == 0) ? 'Gempacked' : 'POS') . "</td>";
            $output .= "<td class='text-right'>" . $order_id . "</td>";
            $output .= "<td class='text-right'>" . $customer . "</td>";
            $output .= "<td class='text-right'>" . $product . "</td>";
            $output .= "<td class='text-right'>" . $model . "</td>";
            $output .= "<td class='text-right'>" . number_format((float) ($price * $quantity), 2, '.', '') . "</td>";
            $output .= "<td class='text-right'>" . $status . "</td>";
            $output .= "<td class='text-right'>" . $date_added . "</td>";
            $output .= "<td class='text-right'><a href='" . $edit . "' id='oit' data-toggle='tooltip' title='Edit' class='btn btn-primary'><i class='fa fa-pencil'></i></a></td>";


            $output .= "</tr>";
        }
        $this->response->setOutput($output);
    }

    public function check_and_save_order() {
        $json = array();

        $action = $this->request->post['action'];

        // check stock first
        $has_stock = true;
        if ($action == 'insert' || $action == 'modify_quantity') {
            // check stock for insert and modify quantity only
            if ($action == 'insert') {
                $quantity = $this->request->post['quantity'];
            } else {
                //	$quantity = (int)$this->request->post['quantity_after'] - (int)$this->request->post['quantity_before'];
                $quantity = (float) $this->request->post['quantity_after'];
            }
            if ($quantity > 0) {
                if (!empty($this->request->post['option'])) {
                    // has option, check the option stock first
                    foreach ($this->request->post['option'] as $option) {
                        if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'image' || $option['type'] == 'checkbox') {
                            if (!empty($option['product_option_value_id'])) {
                                $product_option_value_ids = is_array($option['product_option_value_id']) ? $option['product_option_value_id'] : array($option['product_option_value_id'] => '');
                                foreach ($product_option_value_ids as $product_option_value_id => $value) {
                                    $option_value_query = $this->db->query("SELECT subtract, quantity FROM `" . DB_PREFIX . "product_option_value` WHERE product_option_value_id = '" . (int) $product_option_value_id . "'");
                                    if ($option_value_query->row['subtract'] && $option_value_query->row['quantity'] < $quantity) {
                                        $has_stock = false;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                // check the product level stock as well
                $product_stock_query = $this->db->query("SELECT subtract, quantity FROM `" . DB_PREFIX . "product` WHERE product_id = '" . (int) $this->request->post['product_id'] . "'");
                if ($product_stock_query->row['subtract'] && $product_stock_query->row['quantity'] < $quantity) {
                    $has_stock = false;
                }
            }
        }

        // indicates we are going to access cart from pos
        $data = $this->request->post;
        if (isset($this->session->data['discount_customer_group_id'])) {
            $data['customer_group_id'] = $this->request->post['customer_group_id'] = $this->session->data['discount_customer_group_id'];
        }
        //print_r($data);
        if (empty($data['work_mode'])) {
            $data['work_mode'] = '0';
        }

        if ($action == 'insert_quick') {
            if (!empty($data['include_tax'])) {
                $price = $this->getPriceFromPriceWithTax((float) $data['price'], $data['tax_class_id'], $data['customer_group_id']);
                $data['price'] = $price;
            }
            $this->load->model('pos/pos');
            $product_id = $this->model_pos_pos->updateQSProduct($data);
            $product_info = $data;
            $data['product_id'] = $product_id;
            $product_info['product_id'] = $product_id;
            $product_info['points'] = 0;
            $product_info['subtract'] = 0;
        } else {
            $product_name = '';
            $product_info = $this->db->query("SELECT p.model, p.price, p.tax_class_id, p.points, p.subtract, pd.name FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id WHERE p.product_id = '" . (int) $this->request->post['product_id'] . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'")->row;
            if ($product_info) {
                $product_name = $product_info['name'];
                $product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int) $this->request->post['product_id'] . "' AND customer_group_id = '" . (int) $this->request->post['customer_group_id'] . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

                if ($product_special_query->num_rows) {
                    $product_info['price'] = $product_special_query->row['price'];
                }
                if (!empty($this->request->post['option'])) {
                    $product_info['price'] = $this->getOptionPrice($product_info['price'], $this->request->post['option']);
                } elseif (!empty($this->request->post['order_option'])) {
                    $product_info['price'] = $this->getOptionPrice($product_info['price'], $this->request->post['order_option']);
                }

                if (!isset($quantity)) {
                    $quantity = $this->request->post['quantity'];
                }
                if ($data['customer_group_id'] > 1) {
                    if (isset($this->request->post['coversion_id'])) {
                        $discounted_quantity = $this->getDiscountedQuantiy($this->request->post['product_id'], $this->request->post['coversion_id'], $quantity);
                    } else {
                        $discounted_quantity = $quantity;
                    }
                    $product_info['price'] = $this->getDiscountPriceOfProduct($this->request->post['product_id'], $data['customer_group_id'], $discounted_quantity, $product_info['price']);
                    $data['price'] = $product_info['price'];
                }
            }
            if (!$has_stock && $data['work_mode'] == '0') {
                $this->language->load('module/pos');
                // no enough stock, return error message
                $product_option_value_ids = '';
                if (!empty($this->request->post['option'])) {
                    $option_desc = '';
                    $option_size = count($this->request->post['option']);
                    $option_index = 0;
                    foreach ($this->request->post['option'] as $option) {
                        if (!empty($option['value'])) {
                            if (!empty($option['product_option_value_id']) && is_array($option['product_option_value_id'])) {
                                $option_desc .= $option['name'] . '=';
                                foreach ($option['product_option_value_id'] as $pov_id => $option_values) {
                                    $product_option_value_ids .= $pov_id . ',';
                                    if (!empty($option_values['value'])) {
                                        $option_desc .= $option_values['value'] . ';';
                                    }
                                }
                            } else {
                                $option_desc .= $option['name'] . '=' . $option['value'];
                            }
                            if ($option_index < $option_size - 1) {
                                $option_desc .= ', ';
                            }
                        }
                        $option_index ++;
                    }
                    $json['error']['stock'] = sprintf($this->language->get('error_stock_option'), $product_name, $option_desc);
                } else {
                    $product_option_value_ids = '0';
                    $json['error']['stock'] = sprintf($this->language->get('error_stock'), $product_name);
                }
                $json['error']['stock'] .= ' <a onclick="showOnlineReservation(' . $this->request->post['product_id'] . ', \'' . $product_option_value_ids . '\')">' . $this->language->get('text_transfer_check') . '</a>';
            }
        }
//		if ($has_stock)  {
        // modify order
        // get product information
        $data['product'] = $product_info;
        //echo "<pre>"; print_r($data); echo "</pre>"; exit;
        $save_json = $this->modify_order($data);
        if (!empty($json['error'])) {
            $save_json['error'] = $json['error'];
        }
//		}
        $this->load->model('pos/product');
        $save_json['unit'] = $this->model_pos_product->getDefaultUnitName($this->request->post['product_id']);
        $save_json['quantity'] =$this->request->post['quantity'];
        $save_json['payment_status'] = $this->model_pos_pos->getOrderPaymentStatus($data['order_id']);
        $this->response->setOutput(json_encode($save_json));
    }

    private function getOptionPrice($base_price, $options) {
        $order_product_price = $base_price;
        foreach ($options as $option) {
            // for select-like options
            if (!empty($option['price']) && !empty($option['price_prefix'])) {
                if ($option['price_prefix'] == '+') {
                    $order_product_price += (float) $option['price'];
                } else {
                    $order_product_price -= (float) $option['price'];
                }
            }
            // for checkbox options
            if (!empty($option['product_option_value_id']) && is_array($option['product_option_value_id'])) {
                foreach ($option['product_option_value_id'] as $product_option_value_id => $product_option_values) {
                    if (!empty($product_option_values['value']) && !empty($product_option_values['price']) && !empty($product_option_values['price_prefix'])) {
                        if ($product_option_values['price_prefix'] == '+') {
                            $order_product_price += (float) $product_option_values['price'];
                        } else {
                            $order_product_price -= (float) $product_option_values['price'];
                        }
                    }
                }
            }
        }
        return $order_product_price;
    }

    private function getTaxes($order_id, $order_products, $customer_group_id = 0, $discount_total = 0) {
        $this->load->model('sale/order');
        $order_customer_group_id = $customer_group_id;
        if (!$order_customer_group_id) {
            $order_query = $this->db->query("SELECT customer_group_id FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int) $order_id . "'");
            $order_customer_group_id = $order_query->row['customer_group_id'];
        }

        $tax_data = array();

        // get subtotal for order level pos_discount
        $sub_total = 0;
        $discount_tax_class_id = 0;
        $compare_total = 0; // used for comparing the total for each order product and pick the class id from the max total order product

        foreach ($order_products as $product) {
            if ($product['tax_class_id']) {
                if (!empty($product['discount'])) {
                    // product level discount
                    $tax_rates = $this->getRates($product['discount']['discounted_price'], $product['tax_class_id'], $order_customer_group_id);
                    // logic for order level discount
                    $sub_total += $product['discount']['discounted_total'];
                    if ($product['discount']['discounted_total'] > $compare_total && $this->hasPercentage($tax_rates)) {
                        $discount_tax_class_id = $product['tax_class_id'];
                        $compare_total = $product['discount']['discounted_total'];
                    }
                } else {
                    $tax_rates = $this->getRates($product['price'], $product['tax_class_id'], $order_customer_group_id);
                    // logic for order level discount
                    $sub_total += $product['total'];
                    if ($product['total'] > $compare_total && $this->hasPercentage($tax_rates)) {
                        $discount_tax_class_id = $product['tax_class_id'];
                        $compare_total = $product['total'];
                    }
                }

                foreach ($tax_rates as $tax_rate) {
                    if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
                        $tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['weight'] * $product['quantity']);
                    } else {
                        $tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['weight'] * $product['quantity']);
                    }
                }
            }
        }

        if ($discount_total) {
            // discount is enabled
            $amount = $this->calc_discount_amount($sub_total);
            $tax_rates = $this->getRates($amount, $discount_tax_class_id, $order_customer_group_id, true);
            foreach ($tax_rates as $tax_rate) {
                if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
                    // shouldn't be there but leave the logic here for now ###
                    $tax_data[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
                } else {
                    $tax_data[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
                }
            }
        }

        return $tax_data;
    }

    private function hasPercentage($tax_rates) {
        $has_percentage = false;
        foreach ($tax_rates as $tax_rate) {
            if ($tax_rate['type'] == 'P') {
                $has_percentage = true;
                break;
            }
        }
        return $has_percentage;
    }

    private function sync_total_models() {
        // synchronise the total models from catalog to admin
        $admin_model_root = DIR_APPLICATION . 'model/total';
        $catalog_model_root = DIR_CATALOG . 'model/total';
        $admin_totals = $this->get_totals_files($admin_model_root, '');
        $catalog_totals = $this->get_totals_files($catalog_model_root, '');
        $this->sync_files($catalog_totals, $catalog_model_root, $admin_totals, $admin_model_root);
    }

    private function get_totals_files($dir, $relative_dir) {
        $result = array();
        $cdir = scandir($dir);
        foreach ($cdir as $value) {
            if (!in_array($value, array(".", ".."))) {
                $filename = $dir . DIRECTORY_SEPARATOR . $value;
                if (is_dir($filename)) {
                    $result[$relative_dir . DIRECTORY_SEPARATOR . $value] = $this->get_totals_files($filename, $relative_dir . DIRECTORY_SEPARATOR . $value);
                } else {
                    $result[$relative_dir . DIRECTORY_SEPARATOR . $value] = filemtime($filename);
                }
            }
        }
        return $result;
    }

    private function sync_files($arr1, $parent1, $arr2, $parent2) {
        foreach ($arr1 as $key => $value) {
            if (array_key_exists($key, $arr2)) {
                if (is_dir($parent1 . $key)) {
                    $this->sync_files($value, $parent1, $arr2[$key], $parent2);
                } elseif ($value > $arr2[$key]) {
                    // catalog is the latest, copy it over
                    copy($parent1 . $key, $parent2 . $key);
                }
            } else {
                if (is_dir($parent1 . $key)) {
                    // create the directory
                    mkdir($parent2 . $key);
                    $this->mk_tree($parent1 . $key, $parent2 . $key);
                } else {
                    // no file exits, copy it over
                    copy($parent1 . $key, $parent2 . $key);
                }
            }
        }
        // remove the directories / files that not in the first directory
        foreach ($arr2 as $key => $value) {
            if (!array_key_exists($key, $arr1)) {
                $this->del_tree($parent2 . $key);
            }
        }
    }

    private function mk_tree($source, $destination) {
        $files = array_diff(scandir($source), array('.', '..'));
        foreach ($files as $file) {
            if (is_dir("$source/$file")) {
                mkdir("$destination/$file");
                $this->mk_tree("$source/$file", "$destination/$file");
            } else {
                copy("$source/$file", "$destination/$file");
            }
        }
    }

    private function del_tree($dir) {
        if (is_dir($dir)) {
            $files = array_diff(scandir($dir), array('.', '..'));
            foreach ($files as $file) {
                (is_dir("$dir/$file")) ? $this->del_tree("$dir/$file") : unlink("$dir/$file");
            }
            rmdir($dir);
        } else {
            unlink($dir);
        }
    }

    // add for product based discount begin
    public function applyProductDiscount() {
        $json = array();

        $this->load->model('pos/pos');
        $this->language->load('module/pos');
        //WA-S
        $product_id = $this->request->post['product_id'];
        $quantity = $this->request->post['quantity'];
        $order_product_id = $this->request->post['order_product_id'];
        $normal_price = $this->model_pos_pos->getProductNormalPrice($product_id);
        $normal_total = $normal_price * $quantity;
        $discount_type = $this->request->post['discount_type']; // 1. Fixed 2. Percentage
        $discount_value = $this->request->post['discount_value'];
        if ($discount_type == 1) {
            $discount_fixed = $discount_value / $quantity;
            $price = $normal_price - $discount_fixed;
            $price = number_format((float) $price, 2, '.', '');
        }

        if ($discount_type == 2) {
            $discount_percentage = $discount_value;
            $price = $normal_total - ( $normal_total * ( $discount_percentage / 100 ) );
            $price = number_format((float) $price / $quantity, 2, '.', '');
        }

        $this->db->query("UPDATE `" . DB_PREFIX . "order_product` SET price = '" . (float) $price . "', total = '" . (float) $price * $quantity . "' WHERE order_product_id = '" . (int) $this->request->post['order_product_id'] . "'");

        //WA-E
        // get order product price and product tax class id
        /* $price_query = $this->db->query("SELECT price FROM `" . DB_PREFIX . "order_product` WHERE order_product_id = '" . (int)$this->request->post['order_product_id'] . "'");
          $price = $price_query->row['price'];
          $tax_class_query = $this->db->query("SELECT tax_class_id FROM `" . DB_PREFIX . "product` WHERE product_id = '" . (int)$this->request->post['product_id'] . "'");
          $tax_class_id = $tax_class_query->row['tax_class_id'];
          $quantity = (float)$this->request->post['quantity'];
          $weight = (float)$this->request->post['weight'];
          $customer_group_id = $this->request->post['customer_group_id'];
          // calculate the discounted price, tax and total
          if ($this->config->get('config_tax')) {
          $before_discount_total_with_tax = $this->calculateTax($price, $tax_class_id, $this->config->get('config_tax'), $customer_group_id) * $quantity * $weight;
          $after_discount_total_with_tax = $before_discount_total_with_tax;
          if ((int)$this->request->post['discount_type'] == 1) {
          $after_discount_total_with_tax = $before_discount_total_with_tax - (float)$this->request->post['discount_value'];
          } elseif ((int)$this->request->post['discount_type'] == 2) {
          $after_discount_total_with_tax = $before_discount_total_with_tax * ( 1 - (float)$this->request->post['discount_value'] / 100);
          }
          $after_discount_price_with_tax = $after_discount_total_with_tax / $quantity / $weight;
          $json['discounted_price'] = $this->getPriceFromPriceWithTax($after_discount_price_with_tax, $tax_class_id, $customer_group_id);
          $json['discounted_tax'] = $after_discount_price_with_tax - $json['discounted_price'];
          $json['discounted_total'] = $json['discounted_price'] * $quantity * $weight;
          } else {
          if ((int)$this->request->post['discount_type'] == 1) {
          $json['discounted_price'] = $price - (float)$this->request->post['discount_value'] / $quantity / $weight;
          } elseif ((int)$this->request->post['discount_type'] == 2) {
          $json['discounted_price'] = $price * ( 1 - (float)$this->request->post['discount_value'] / 100);
          }
          $json['discounted_total'] = $json['discounted_price'] * $quantity * $weight;
          $json['discounted_tax'] = $this->getTax($json['discounted_price'], $tax_class_id, $customer_group_id);
          }

          // save the discount info to the database
          $this->db->query("REPLACE INTO `" . DB_PREFIX . "order_product_discount` SET order_product_id = '" . (int)$this->request->post['order_product_id'] . "', discount_type = '" . (int)$this->request->post['discount_type'] . "', discount_value = '" . (float)$this->request->post['discount_value'] . "', include_tax = '" . (int)$this->config->get('config_tax') . "', discounted_price = '" . $json['discounted_price'] . "', discounted_total = '" . $json['discounted_total'] . "', discounted_tax = '" . $json['discounted_tax'] . "'");
         */
        $total_data = $this->recalculate_total($this->request->post);
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET total = '" . (float) $total_data['total'] . "', date_modified = NOW() WHERE order_id = '" . (int) $this->request->post['order_id'] . "'");
        // update order product to indicate the price has been changed
        $this->db->query("UPDATE `" . DB_PREFIX . "order_product` SET price_change = '1' WHERE order_product_id = '" . (int) $this->request->post['order_product_id'] . "'");

        $json['order_total'] = $total_data['order_total'];
        $this->response->setOutput(json_encode($json));
    }

    // add for product based discount end

    public function get_default_customer_ajax() {
        $this->response->setOutput(json_encode($this->get_default_customer()));
    }

    private function get_default_customer() {
        $c_data = array();
        $this->setDefaultCustomer($c_data);

        $json = array();
        $json['customer_id'] = $c_data['c_id'];
        $json['customer_group_id'] = $c_data['c_group_id'];
        foreach ($c_data as $c_key => $c_value) {
            if (substr($c_key, 0, 2) == 'c_') {
                $json['customer_' . substr($c_key, 2)] = $c_value;
            }
        }
        if (!empty($json['customer_addresses'])) {
            $this->load->model('localisation/zone');
            foreach ($json['customer_addresses'] as $key => $customer_address) {
                $json['customer_addresses'][$key]['zones'] = $this->model_localisation_zone->getZonesByCountryId($customer_address['country_id']);
            }
        }
        return $json;
    }

    public function getCustomerList() {
        $limit = 8;

        $this->load->model('pos/pos');

        $data = $this->request->post;
        if (!isset($data['limit'])) {
            $data['limit'] = $limit;
        }
        if (!isset($data['start'])) {
            $data['start'] = 0;
            if (isset($data['page'])) {
                $data['start'] = ((int) $data['page'] - 1) * $data['limit'];
            }
        }

        $customer_total = $this->model_pos_pos->getTotalCustomers($data);
        $results = $this->model_pos_pos->getCustomers($data);
        if (!empty($data['filter_customer_id']) || !empty($data['filter_name']) || !empty($data['filter_email']) || !empty($data['filter_telephone']) || !empty($data['filter_date'])) {
            $results = $results;
            $customer_total = $customer_total;
        } else {
            $results = array();
            $customer_total = 0;
        }
        $json = array();
        $json['customers'] = array();
        foreach ($results as $result) {
            $json['customers'][] = array(
                'customer_id' => $result['customer_id'],
                'company_id' => $result['company_id'],
                'name' => $result['name'],
                'email' => $result['email'],
                'telephone' => $result['telephone'],
                'date_added' => date('Y-m-d', strtotime($result['date_added']))
            );
        }
        $page = (!empty($data['page'])) ? $data['page'] : 1;
        $json['pagination'] = $this->getPagination($customer_total, $page, $limit, 'selectCustomerPage');

        $this->response->setOutput(json_encode($json));
    }

    public function saveOrderComment() {
        $order_id = $this->request->post['order_id'];
        $comment = $this->request->post['comment'];
        $this->load->model('pos/pos');
        $this->model_pos_pos->saveOrderComment($order_id, $comment);

        $this->language->load('module/pos');
        $json['success'] = $this->language->get('text_order_success');
        $this->response->setOutput(json_encode($json));
    }

    public function sn_autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_sn']) && isset($this->request->get['filter_product_id'])) {
            $this->load->model('pos/extended_product');

            $data = array(
                'product_id' => $this->request->get['filter_product_id'],
                'sn' => $this->request->get['filter_sn'],
                'status' => '1'
            );

            $results = $this->model_pos_extended_product->getProductSNs($data);

            foreach ($results as $result) {
                $json[] = array(
                    'name' => $result['sn'],
                    'product_sn_id' => $result['product_sn_id']
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->setOutput(json_encode($json));
    }

    public function getPaymentsDetails() {
        $json = array();

        $this->load->model('pos/pos');
        $data['filter_order_id'] = $this->request->get['order_id'];
        $payments = $this->model_pos_pos->getOrderPayments($data);
        $json['payments'] = array();
        if (!empty($payments)) {
            foreach ($payments as $payment) {
                $json['payments'][] = array('payment_type' => $payment['payment_type'],
                    'tendered_amount' => $payment['tendered_amount'],
                    'payment_note' => $payment['payment_note'],
                    'payment_time' => strtotime($payment['payment_time']));
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    private function resize($filename, $width, $height) {
        if ($this->request->server['HTTPS']) {
            $local_filename = str_replace(HTTPS_CATALOG . '/image/', DIR_IMAGE, $filename);
        } else {
            $local_filename = str_replace(HTTP_CATALOG . '/image/', DIR_IMAGE, $filename);
        }

        if (!is_file($local_filename)) {
            return;
        }

        $path_parts = pathinfo($local_filename);

        $old_image = $local_filename;
        $new_image = DIR_APPLICATION . 'view/image/pos/cache/' . $path_parts['filename'] . '-' . $width . 'x' . $height . '.' . $path_parts['extension'];

        if (!is_file($new_image) || (filectime($old_image) > filectime($new_image))) {
            list($width_orig, $height_orig) = getimagesize($old_image);

            if ($width_orig != $width || $height_orig != $height) {
                $image = new Image($old_image);
                $image->resize($width, $height);
                $image->save($new_image);
            } else {
                copy($old_image, $new_image);
            }
        }

        return 'view/image/pos/cache/' . $path_parts['filename'] . '-' . $width . 'x' . $height . '.' . $path_parts['extension'];
    }

    private function update_manifest() {
        $login = file_get_contents(DIR_APPLICATION . 'view/template/pos/offline/login.html');
        if (strpos($login, 'onclick="ajaxLogin();"') !== false) {
            // if the file is already changed, leave it there
            $login = str_replace('onclick="ajaxLogin();"', 'onclick="login();"', $login);
            $login = str_replace('<script src="view/javascript/common.js" type="text/javascript"></script>', '<script src="view/javascript/common.js" type="text/javascript"> </script><script src="view/javascript/pos/pos_vars.js" type="text/javascript"></script>', $login);
            $login_control = '<script type="text/javascript">';
            $login_control .= 'function login() {';
            $login_control .= 'var savedHash = localStorage.getItem("pos_local_hash");';
            $login_control .= 'var matched = false;';
            $login_control .= 'if (savedHash) {';
            $login_control .= 'var tobeHashed = $("#input-username").val() + $("#input-password").val();';
            $login_control .= 'if (md5(tobeHashed) == savedHash) { matched = true; } }';
            $login_control .= 'if (matched) { window.location = "index.php?route=module/pos/main&token=" + token; } else {';
            $login_control .= '$(".panel-body .alert").remove(); $(".panel-body").prepend("<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i> ' . $this->language->get('text_wrong_user_password') . '<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></div>"); } }';
            $login_control .= '</script>';
            $login = str_replace('</body></html>', $login_control . '</body> </html>', $login);
            file_put_contents(DIR_APPLICATION . 'view/template/pos/offline/login.html', $login);

            $this->update_pos_manifest();
        }
    }

    private function update_pos_manifest() {
        $manifest = file(DIR_APPLICATION . 'view/template/pos/offline/pos.manifest');
        if (count($manifest) > 2) {
            $manifest[2] = "#" . strtotime("now") . "\n";
        }
        file_put_contents(DIR_APPLICATION . 'view/template/pos/offline/pos.manifest', implode('', $manifest));
    }

    // add for Gift Voucher payment begin
    public function check_gift_voucher() {
        $json = array();

        if (!empty($this->request->get['gift_voucher_code'])) {
            $voucher_code = $this->request->get['gift_voucher_code'];
            $tendered_amount = (float) $this->request->get['due_amount'];
            $this->validate_gift_voucher($voucher_code, $json, $tendered_amount);
        }

        $this->response->setOutput(json_encode($json));
    }

    private function validate_gift_voucher($voucher_code, &$json, $tendered_amount, $validate_amount = false) {
        // check the given gift voucher is valid
        $this->language->load('module/pos');

        $voucher = false;
        $voucher_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "voucher` WHERE code = '" . $this->db->escape($voucher_code) . "'");
        if ($voucher_query->num_rows) {
            $voucher = $voucher_query->row;

            $voucher_history_query = $this->db->query("SELECT SUM(amount) AS total FROM `" . DB_PREFIX . "voucher_history` WHERE voucher_id = '" . (int) $voucher['voucher_id'] . "' GROUP BY voucher_id");
            if ($voucher_history_query->num_rows) {
                $voucher['amount'] += $voucher_history_query->row['total'];
            }
        }

        if ($voucher) {
            if (((int) $voucher['status']) == 0) {
                $json['error'] = sprintf($this->language->get('text_voucher_not_enabled'), $voucher_code);
            } elseif ($voucher['amount'] <= 0) {
                $json['error'] = sprintf($this->language->get('text_voucher_no_balance'), $voucher_code);
            } else {
                if ($validate_amount) {
                    if ($tendered_amount > $voucher['amount']) {
                        $json['error'] = sprintf($this->language->get('text_voucher_not_engouht_balance'), $voucher_code, $tendered_amount, $voucher['amount']);
                    }
                } else {
                    $due_amount = $tendered_amount;
                    if ($due_amount > $voucher['amount']) {
                        $due_amount = $voucher['amount'];
                    }
                    $json['balance_message'] = sprintf($this->language->get('text_gift_voucher_balance'), $this->currency->formatFront($voucher['amount']), $this->currency->formatFront($due_amount));
                    $json['due_amount'] = $due_amount;
                }
            }
            return $voucher['voucher_id'];
        } else {
            $json['error'] = sprintf($this->language->get('text_voucher_not_found'), $voucher_code);
            return 0;
        }
    }

    // add for Gift Voucher payment end
    // add for customer loyalty card begin
    public function check_reward_points() {
        $this->language->load('module/pos');
        $json = array('success' => $this->language->get('text_points_retried_success'));
        if (!empty($this->request->get['order_id'])) {
            $order_id = (int) $this->request->get['order_id'];
            // get reward info from the order
            $points_balance = 0;
            $points_query = $this->db->query("SELECT sum(points) AS total FROM `" . DB_PREFIX . "customer_reward` WHERE customer_id IN (select customer_id FROM `" . DB_PREFIX . "order` WHERE order_id = '" . $order_id . "') AND order_id <> '" . $order_id . "'");
            if ($points_query->row && $points_query->row['total']) {
                $points_balance = $points_query->row['total'];
            }
            $json['reward_points_balance'] = $points_balance;

            if ($this->config->get('POS_reward_points_usage') == '1') {
                $points_details = array();
                $order_products_query = $this->db->query("SELECT order_product_id, product_id, quantity, name FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . $order_id . "'");
                if ($order_products_query->rows) {
                    foreach ($order_products_query->rows as $row) {
                        $order_product_points = 0;
                        $product_points_query = $this->db->query("SELECT points FROM `" . DB_PREFIX . "product` WHERE product_id = '" . $row['product_id'] . "'");
                        if ($product_points_query->row) {
                            $order_product_points = $product_points_query->row['points'];
                        }

                        $option_names = '';
                        $product_option_points_query = $this->db->query("SELECT points, points_prefix, name, value FROM `" . DB_PREFIX . "product_option_value` pov LEFT JOIN `" . DB_PREFIX . "order_option` op ON op.product_option_value_id = pov.product_option_value_id WHERE order_product_id = '" . $row['order_product_id'] . "'");
                        if ($product_option_points_query->rows) {
                            foreach ($product_option_points_query->rows as $points_row) {
                                $option_names .= $points_row['name'] . '=' . $points_row['value'] . ',';
                                if ($points_row['points_prefix'] == '+') {
                                    $order_product_points += $points_row['points'];
                                } elseif ($points_row['points_prefix'] == '-') {
                                    $order_product_points -= $points_row['points'];
                                }
                            }
                        }
                        if (!empty($option_names)) {
                            $option_names = '(' . substr($option_names, 0, -1) . ')';
                        }

                        if ($order_product_points != 0) {
                            $points_details[] = array('order_product_id' => $row['order_product_id'], 'points' => $order_product_points, 'name' => $row['name'] . $option_names, 'quantity' => $row['quantity']);
                        }
                    }
                }

                $json['reward_products'] = $points_details;
            }
        }
        $this->response->setOutput(json_encode($json));
    }

    private function process_reward_points_payment($order_id, $payment_note, &$json) {
        if ($this->config->get('POS_reward_points_usage') == '1') {
            $parts = explode('|', $payment_note);
            if ($parts) {
                // deduct the reward points from the current order if the reward points are used to pay the order product as we already set the reward points in the earlier stage
                $total_deduction = 0;
                $total_value = 0;
                foreach ($parts as $part) {
                    if (!empty($part)) {
                        $reward_details = explode(',', $part);
                        if ($reward_details) {
                            $order_product_id = $reward_details[0];
                            $quantity = $reward_details[1];
                            $deducted_points = $reward_details[2];
                            $reward_query = $this->db->query("SELECT reward FROM `" . DB_PREFIX . "order_product` WHERE order_product_id = '" . (int) $order_product_id . "'");
                            if ($reward_query->row) {
                                $deducted_points += $reward_query->row['reward'];
                            }
                            $this->db->query("UPDATE `" . DB_PREFIX . "order_product` SET reward = reward - " . $deducted_points . " WHERE order_product_id = '" . (int) $order_product_id . "'");
                            $total_deduction += $deducted_points;

                            $order_product_query = $this->db->query("SELECT price, tax FROM `" . DB_PREFIX . "order_product` WHERE order_product_id = '" . (int) $order_product_id . "'");
                            $total_value += (int) $quantity * ((float) $order_product_query->row['price'] + (float) $order_product_query->row['tax']);
                        }
                    }
                }

                if ($total_deduction != 0) {
                    $this->db->query("UPDATE `" . DB_PREFIX . "customer_reward` SET points = points - " . $total_deduction . " WHERE order_id = '" . (int) $order_id . "'");
                }
                // calculate how much payment the points equals to and return back to the payment
                $json['total_value'] = $total_value;
            }
        } elseif ($this->config->get('POS_reward_points_usage') == '2') {
            $this->db->query("UPDATE `" . DB_PREFIX . "customer_reward` SET points = points - " . (int) $payment_note . " WHERE order_id = '" . (int) $order_id . "'");
        }
    }

    public function setOrderCustomer() {
        $json = array();
        $customer_card = $this->request->get['customer_card'];
        $customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE card_number = '" . $this->db->escape($customer_card) . "'");
        if ($customer_query->row && $customer_query->row['customer_id'] > 0) {
            $json = $customer_query->row;
            $order_id = (!empty($this->request->get['order_id'])) ? $this->request->get['order_id'] : false;
            $pos_return_id = (!empty($this->request->get['pos_return_id'])) ? $this->request->get['pos_return_id'] : false;
            $customer_id = (int) $customer_query->row['customer_id'];
            $customer_group_id = (int) $customer_query->row['customer_group_id'];
            $this->saveCustomerInfo($order_id, $pos_return_id, $customer_query->row, $customer_id, $customer_group_id, $json);
        }
        $this->response->setOutput(json_encode($json));
    }

    public function set_products_reward_points() {
        // in case we have too many products, set the time limits to 30 mins
        set_time_limit(1800);
        $this->language->load('module/pos');
        $points_ratio = $this->request->post['points_ratio'];
        $reward_points_ratio = $this->request->post['reward_points_ratio'];
        $updated_product_count = 0;
        $updated_reward_count = 0;
        $inserted_reward_count = 0;
        if (!empty($points_ratio)) {
            $this->db->query("UPDATE `" . DB_PREFIX . "product` SET points = ROUND(price*" . (int) $points_ratio . ")");
            $updated_product_count = $this->db->countAffected();
        }
        if (!empty($reward_points_ratio)) {
            // check for all products
            $product_query = $this->db->query("SELECT product_id, price FROM `" . DB_PREFIX . "product`");
            foreach ($reward_points_ratio as $customer_group_id => $ratio) {
                if (!empty($ratio) && !empty($product_query->rows)) {
                    foreach ($product_query->rows as $row) {
                        $reward_points_query = $this->db->query("SELECT product_reward_id FROM `" . DB_PREFIX . "product_reward` WHERE product_id = '" . $row['product_id'] . "' AND customer_group_id = '" . $customer_group_id . "'");
                        if ($reward_points_query->row) {
                            $this->db->query("UPDATE `" . DB_PREFIX . "product_reward` SET points = '" . (int) round($row['price'] * (float) $ratio) . "' WHERE product_reward_id = '" . $reward_points_query->row['product_reward_id'] . "'");
                            $updated_reward_count ++;
                        } else {
                            $this->db->query("INSERT INTO `" . DB_PREFIX . "product_reward` SET product_id = '" . $row['product_id'] . "', customer_group_id = '" . $customer_group_id . "', points = '" . (int) round($row['price'] * (float) $ratio) . "'");
                            $inserted_reward_count ++;
                        }
                    }
                }
            }
        }
        $this->response->setOutput(json_encode(array('message' => sprintf($this->language->get('text_product_reward_points_updated'), $updated_product_count, $updated_reward_count, $inserted_reward_count))));
    }

    // add for customer loyalty card end
    // add for label print begin
    public function save_label_template() {
        $this->load->model('pos/pos');
        $label_template_id = $this->model_pos_pos->save_label_template($this->request->post);

        $json = array();
        $templates = $this->model_pos_pos->get_label_templates(array('label_template_id' => $label_template_id));
        if ($templates) {
            $json = $templates[0];
        }

        $this->response->setOutput(json_encode($json));
    }

    public function delete_label_template() {
        $this->load->model('pos/pos');
        $this->model_pos_pos->delete_label_template($this->request->get['label_template_id']);
        $this->response->setOutput(json_encode(array()));
    }

    public function getLabelProductDetails() {
        $json = array();
        if (!empty($this->request->post['products'])) {
            $product_ids = implode(',', $this->request->post['products']);
            $product_query = $this->db->query("SELECT pd.name, pd.description, p.product_id, p.model, p.price, p.sku, p.upc, p.ean, p.mpn, m.name AS manufacturer FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_description` pd ON pd.product_id = p.product_id LEFT JOIN `" . DB_PREFIX . "manufacturer` m ON p.manufacturer_id = m.manufacturer_id WHERE p.product_id in (" . $product_ids . ") AND language_id = '" . (int) $this->config->get('config_language_id') . "'");
            if ($product_query->rows) {
                foreach ($product_query->rows as $row) {
                    $json[] = $row;
                }
            }
        }
        if (!empty($json)) {
            foreach ($json as $key => $product) {
                $json[$key]['price'] = $this->currency->formatFront($product['price']);
            }
        }
        $this->response->setOutput(json_encode($json));
    }

    // add for label print end
    // add for product low stock begin
    public function set_products_low_stock() {
        // in case we have too many products, set the time limits to 30 mins
        set_time_limit(1800);

        $this->language->load('module/pos');
        $updated_product_count = 0;
        if (!empty($this->request->post['category_id'])) {
            $low_stock = $this->request->post['product_low_stock'];
            $this->load->model('pos/pos');
            foreach ($this->request->post['category_id'] as $category_id) {
                $updated_product_count += $this->set_product_low_stock_category($low_stock, $category_id);
            }
        }

        $this->response->setOutput(json_encode(array('message' => sprintf($this->language->get('text_product_low_stock_updated'), $updated_product_count))));
    }

    private function set_product_low_stock_category($low_stock, $category_id) {
        $updated_count = 0;
        $this->db->query("UPDATE `" . DB_PREFIX . "product` SET low_stock = '" . $low_stock . "' WHERE product_id IN (SELECT product_id FROM `" . DB_PREFIX . "product_to_category` WHERE category_id = '" . $category_id . "')");
        $updated_count += $this->db->countAffected();
        $this->db->query("UPDATE `" . DB_PREFIX . "product_option_value` SET low_stock = '" . $low_stock . "' WHERE product_id IN (SELECT product_id FROM `" . DB_PREFIX . "product_to_category` WHERE category_id = '" . $category_id . "')");
        $updated_count += $this->db->countAffected();

        $sub_categories = $this->model_pos_pos->getSubCategories($category_id);
        if (!empty($sub_categories)) {
            foreach ($sub_categories as $sub_category) {
                $updated_count += $this->set_product_low_stock_category($low_stock, $sub_category['category_id']);
            }
        }

        return $updated_count;
    }

    public function stock_manager() {
        $this->language->load('module/pos');

        $this->document->setTitle($this->language->get('stock_manager_title'));

        $this->load->model('pos/pos');

        if (isset($this->request->get['filter_product_id'])) {
            $filter_product_id = $this->request->get['filter_product_id'];
        } else {
            $filter_product_id = null;
        }

        if (isset($this->request->get['filter_product_name'])) {
            $filter_product_name = $this->request->get['filter_product_name'];
        } else {
            $filter_product_name = null;
        }

        if (isset($this->request->get['filter_model'])) {
            $filter_model = $this->request->get['filter_model'];
        } else {
            $filter_model = null;
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_product_id'])) {
            $url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
        }

        if (isset($this->request->get['filter_product_name'])) {
            $url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('stock_manager_title'),
            'href' => $this->url->link('module/pos/stock_manager', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => ' :: '
        );

        $data['stocks'] = array();

        $filter_data = array(
            'filter_product_id' => $filter_product_id,
            'filter_product_name' => $filter_product_name,
            'filter_model' => $filter_model,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $stock_total = $this->model_pos_pos->getTotalStocks($filter_data);

        $results = $this->model_pos_pos->getStocks($filter_data);

        foreach ($results as $result) {
            // check if the current record has child, if so, the quantity of this record will be calculated instead of modified manually
            $children = $this->get_stock_children($result);
            $data['stocks'][] = array(
                'product_id' => $result['product_id'],
                'product_name' => $result['product_name'],
                'model' => $result['model'],
                'option_name' => (!empty($result['option_name']) || !empty($result['option_value_name'])) ? $result['option_name'] . ':' . $result['option_value_name'] : '',
                'product_option_value_id' => $result['product_option_value_id'],
                'quantity' => $result['quantity'],
                'low_stock' => $result['low_stock'],
                'children' => $children
            );
        }

        $data['heading_title'] = $this->language->get('stock_manager_title');
        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['column_product_name'] = $this->language->get('column_product_name');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_product_option'] = $this->language->get('column_product_option');
        $data['column_product_quantity'] = $this->language->get('column_product_quantity');
        $data['column_alert'] = $this->language->get('column_alert');
        $data['button_print_stock_report'] = $this->language->get('button_print_stock_report');
        $data['button_print_restock_report'] = $this->language->get('button_print_restock_report');
        $data['button_sync_stock'] = $this->language->get('button_sync_stock');
        $data['text_sync_alert'] = $this->language->get('text_sync_alert');

        $data['button_filter'] = $this->language->get('button_filter');

        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $url = '';

        if (isset($this->request->get['filter_product_id'])) {
            $url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
        }

        if (isset($this->request->get['filter_product_name'])) {
            $url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        $pagination = new Pagination();
        $pagination->total = $stock_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('module/pos/stock_manager', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();
        $data['results'] = sprintf($this->language->get('text_pagination'), ($stock_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($stock_total - $this->config->get('config_limit_admin'))) ? $stock_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $stock_total, ceil($stock_total / $this->config->get('config_limit_admin')));

        $data['filter_product_id'] = $filter_product_id;
        $data['filter_product_name'] = $filter_product_name;
        $data['filter_model'] = $filter_model;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('pos/stock_manager.tpl', $data));
    }

    private function get_stock_children($record) {
        $children = '';
        if (empty($record['product_option_value_id'])) {
            // if the record has product_option_value_id, it's not a parent, and it will be in product_option_value
            $children = $record['product_id'];
        }
        return $children;
    }

    public function save_product_stock() {
        $product_id = 0;
        $product_option_value_id = 0;
        $quantity_change = (int) $this->request->post['quantity'] - (int) $this->request->post['quantity_before'];
        foreach ($this->request->post as $key => $value) {
            if (strpos($key, 'product_id') !== false) {
                $product_id = $value;
            } elseif (strpos($key, 'product_option_value_id') !== false) {
                $product_option_value_id = $value;
            }
        }

        $json = array('success' => 'success');

        if ((int) $product_option_value_id == 0) {
            // it's product level quantity change, just change the value
            $this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = '" . $this->request->post['quantity'] . "' WHERE product_id = '" . $product_id . "'");
        } else {
            // update option stock
            if ((int) $product_option_value_id > 0) {
                $this->db->query("UPDATE `" . DB_PREFIX . "product_option_value` SET quantity = '" . $this->request->post['quantity'] . "' WHERE product_option_value_id = '" . $product_option_value_id . "'");
                // update the total quantity by adding all option stock together for that product id, in case only a single option is presented
                $option_query = $this->db->query("SELECT DISTINCT option_id FROM `" . DB_PREFIX . "product_option_value` WHERE product_id = '" . $product_id . "'");
                if ($option_query->num_rows == 1) {
                    $prd_query = $this->db->query("SELECT SUM(quantity) AS total FROM `" . DB_PREFIX . "product_option_value` WHERE product_id = '" . $product_id . "'");
                    if ($prd_query->row) {
                        $json['update_product'] = array($product_id => $prd_query->row['total']);
                        // also update the product level opencart quantity
                        $this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = '" . $prd_query->row['total'] . "' WHERE product_id = '" . $product_id . "'");
                    }
                }
            }
        }
        $this->response->setOutput(json_encode($json));
    }

    public function save_product_low_stock() {
        $product_id = 0;
        $product_option_value_id = 0;
        $low_stock = (int) $this->request->post['low_stock'];
        foreach ($this->request->post as $key => $value) {
            if (strpos($key, 'product_id') !== false) {
                $product_id = $value;
            } elseif (strpos($key, 'product_option_value_id') !== false) {
                $product_option_value_id = $value;
            }
        }

        $json = array('success' => 'success');
        // update opencart stock
        if ((int) $product_option_value_id == 0) {
            $this->db->query("UPDATE `" . DB_PREFIX . "product` SET low_stock = '" . $low_stock . "' WHERE product_id = '" . $product_id . "'");
        } else {
            $this->db->query("UPDATE `" . DB_PREFIX . "product_option_value` SET low_stock = '" . $low_stock . "' WHERE product_option_value_id = '" . $product_option_value_id . "'");
        }
        $this->response->setOutput(json_encode($json));
    }

    private function render_print_stock($alert) {
        $this->language->load('module/pos');

        $this->document->setTitle($this->language->get('stock_manager_title'));

        $this->load->model('pos/pos');

        $data = array();
        $data['filter_alert'] = $alert;

        $data['stocks'] = array();

        $results = $this->model_pos_pos->getStocks($data);

        foreach ($results as $result) {
            // check if the current record has child, if so, the quantity of this record will be calculated instead of modified manually
            $children = $this->get_stock_children($result);
            $data['stocks'][] = array(
                'product_id' => $result['product_id'],
                'product_name' => $result['product_name'],
                'model' => $result['model'],
                'option_name' => (!empty($result['option_name']) || !empty($result['option_value_name'])) ? $result['option_name'] . ':' . $result['option_value_name'] : '',
                'product_option_value_id' => $result['product_option_value_id'],
                'quantity' => $result['quantity'],
                'low_stock' => $result['low_stock'],
                'children' => $children
            );
        }

        $data['heading_title'] = $this->language->get('stock_manager_title');

        $data['column_product_name'] = $this->language->get('column_product_name');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_product_option'] = $this->language->get('column_product_option');
        $data['column_product_quantity'] = $this->language->get('column_product_quantity');
        $data['column_alert'] = $this->language->get('column_alert');
        $data['text_print_stock_title'] = $this->language->get('text_print_stock_title');

        $this->response->setOutput($this->load->view('pos/stock_print.tpl', $data));
    }

    public function print_stock() {
        $this->render_print_stock(false);
    }

    public function print_restock() {
        $this->render_print_stock(true);
    }

    // add for product low stock end
    // add for sync offline order begin
    public function sync_data_start() {
        // sync the cash in and out if there is any
        if (!empty($this->request->post['cash_in_out'])) {
            $this->load->model('pos/pos');
            foreach ($this->request->post['cash_in_out'] as $cash_in_out) {
                $this->model_pos_pos->addOrderPayment($cash_in_out);
            }
        }
        // create temporary tables for use by the sync
        $temp_customer_table = $this->request->get['uid'] . '_customer';
        $temp_address_table = $this->request->get['uid'] . '_address';
        $temp_order_product_table = $this->request->get['uid'] . '_order_product';
        $this->db->query("CREATE TABLE `" . $temp_customer_table . "` LIKE `" . DB_PREFIX . "customer`");
        $this->db->query("CREATE TABLE `" . $temp_address_table . "` LIKE `" . DB_PREFIX . "address`");
        $this->db->query("CREATE TABLE `" . $temp_order_product_table . "` LIKE `" . DB_PREFIX . "order_product`");
        $this->db->query("ALTER TABLE `" . $temp_order_product_table . "` ADD shipping TINYINT(1) DEFAULT '0', ADD tax_class_id INT(11) DEFAULT'0'");
        $this->response->setOutput(json_encode(array('success' => 'sync was initialized successfully.')));
    }

    public function sync_return() {
        $this->load->model('pos/pos');
        if (!empty($this->request->post['pos_return_id']) && (int) $this->request->post['pos_return_id'] > 0) {
            $this->model_pos_pos->editPosReturn($this->request->post['pos_return_id'], $this->request->post);
            $pos_return_id = $this->request->post['pos_return_id'];
        } else {
            $pos_return_id = $this->model_pos_pos->addPosReturn($this->request->post);
        }
        $json = array('org_pos_return_id' => $this->request->post['pos_return_id'], 'pos_return_id' => $pos_return_id);
        // save the customers, only if the return is without order, otherwise, the customer info is with the order itself
        // local return will not have non-catalog product without an order_product_id, as return w/o order cannot use non-catalog,
        // and if non-catalog product is returned from an order, the order_product_id will refer to the non-catalog product
        if ((int) $this->request->post['order_id'] == 0) {
            $customer = array();
            foreach ($this->request->post as $key => $value) {
                if ($key == 'customer_id' || $key == 'customer_group_id') {
                    $customer[$key] = $value;
                } elseif (strpos($key, 'customer_') === 0) {
                    $customer[substr($key, 9)] = $value;
                }
            }
            if (empty($customer['card_number'])) {
                $customer['card_number'] = '';
            }

            if ((int) $customer['customer_id'] > 0) {
                // the existing customer
                $this->model_pos_pos->editCustomer($customer['customer_id'], $customer);
            } elseif ((int) $customer['customer_id'] < 0) {
                // add new customer
                $temp_customer_table = $this->request->get['uid'] . '_customer';
                $temp_address_table = $this->request->get['uid'] . '_address';
                $customer_id = $this->model_pos_pos->addCustomer($customer, $temp_customer_table, $temp_address_table);
                // use the local customer id which is referred for the order for now, need to move the record to customer table later
                $this->db->query("UPDATE `" . $temp_customer_table . "` SET customer_id = '" . $customer['customer_id'] . "' WHERE customer_id = '" . $customer_id . "'");
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function sync_order() {
        $this->load->model('pos/pos');
        if (!empty($this->request->post['order_id']) && (int) $this->request->post['order_id'] > 0) {
            $this->model_pos_pos->editOrder($this->request->post['order_id'], $this->request->post);
            $order_id = $this->request->post['order_id'];
        } else {
            $order_id = $this->model_pos_pos->addOrder($this->request->post);
            // update order id for the returns
            if (!empty($this->request->post['returns'])) {
                foreach ($this->request->post['returns'] as $return) {
                    // all info is there, only the customer_id needs to be changed
                    $this->db->query("UPDATE `" . DB_PREFIX . "return` SET order_id = '" . $order_id . "' WHERE pos_return_id = '" . $return['pos_return_id'] . "' AND order_id = '" . $this->request->post['order_id'] . "'");
                }
            }
        }

        $json = array('org_order_id' => $this->request->post['order_id'], 'order_id' => $order_id, 'order_products_ids' => array());
        // save the customers and products
        $customer = array();
        foreach ($this->request->post as $key => $value) {
            if ($key == 'customer_id' || $key == 'customer_group_id') {
                $customer[$key] = $value;
            } elseif (strpos($key, 'customer_') === 0) {
                $customer[substr($key, 9)] = $value;
            }
        }
        if (empty($customer['card_number'])) {
            $customer['card_number'] = '';
        }

        if ((int) $customer['customer_id'] > 0) {
            // the existing customer
            $this->model_pos_pos->editCustomer($customer['customer_id'], $customer);
        } elseif ((int) $customer['customer_id'] < 0) {
            // add new customer
            $temp_customer_table = $this->request->get['uid'] . '_customer';
            $temp_address_table = $this->request->get['uid'] . '_address';
            $customer_id = $this->model_pos_pos->addCustomer($customer, $temp_customer_table, $temp_address_table);
            // use the local customer id which is referred for the order for now, need to move the record to customer table later
            $this->db->query("UPDATE `" . $temp_customer_table . "` SET customer_id = '" . $customer['customer_id'] . "' WHERE customer_id = '" . $customer_id . "'");
        }

        if (!empty($this->request->post['products'])) {
            foreach ($this->request->post['products'] as $product) {
                if ((int) $product['product_id'] < 0) {
                    // add new product
                    $temp_order_product_table = $this->request->get['uid'] . '_order_product';
                    // use the local product id which is referred for the order for now, need to move the record to product table later, use sku to store name first
                    $this->db->query("INSERT INTO `" . $temp_order_product_table . "` SET order_id = '" . $order_id . "', product_id = '" . (int) $product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int) $product['quantity'] . "', price = '" . (float) $product['price'] . "', total = '" . (float) $product['total'] . "', tax = '" . $product['tax'] . "', reward = '" . (int) $product['reward'] . "', weight = '" . (float) $product['weight'] . "', shipping = '" . (isset($product['shipping']) ? $product['shipping'] : 0) . "', tax_class_id = '" . (isset($product['tax_class_id']) ? $product['tax_class_id'] : 0) . "'");
                    $this->db->query("UPDATE `" . $temp_order_product_table . "` SET order_product_id = '" . $product['order_product_id'] . "' WHERE order_product_id = '" . $this->db->getLastId() . "'");
                } else {
                    // add product into order product tables
                    if ((int) $product['order_product_id'] > 0) {
                        // for the order product that was in the order, get the quantity change
                        $quantity_query = $this->db->query("SELECT quantity FROM `" . DB_PREFIX . "order_product` WHERE order_product_id = '" . $product['order_product_id'] . "'");
                        if ($quantity_query->row && $quantity_query->row['quantity'] != (int) $product['quantity']) {
                            // quantity changed
                            $quantity_change = (int) $product['quantity'] - $quantity_query->row['quantity'];
                            $this->db->query("UPDATE `" . DB_PREFIX . "order_product` SET quantity = '" . $product['quantity'] . "' WHERE order_product_id = '" . $product['order_product_id'] . "'");
                            // add for customer loyalty card begin
                            if (!empty($product['reward']) && (int) $customer['customer_id'] > 0 && $this->request->post['work_mode'] == '0') {
                                // update customer reward for this order
                                $reward_query = $this->db->query("SELECT points FROM `" . DB_PREFIX . "customer_reward` WHERE customer_id = '" . (int) $customer['customer_id'] . "' AND order_id = '" . (int) $order_id . "'");
                                if ($reward_query->row) {
                                    $this->db->query("UPDATE `" . DB_PREFIX . "customer_reward` SET points = points + (" . $quantity_change * (int) $product['reward'] . "), date_added = NOW() WHERE customer_id = '" . (int) $customer['customer_id'] . "' AND order_id = '" . (int) $order_id . "'");
                                } else {
                                    $this->db->query("INSERT INTO `" . DB_PREFIX . "customer_reward` SET customer_id = '" . (int) $customer['customer_id'] . "', order_id = '" . (int) $order_id . "', points = '" . (int) $product['quantity'] * (int) $product['reward'] . "', description = 'Order ID: " . (int) $order_id . "', date_added = NOW()");
                                }
                            }
                            // add for customer loyalty card end

                            $this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = quantity - (" . $quantity_change . ") WHERE product_id = '" . (int) $product['product_id'] . "' AND subtract = '1'");

                            if (isset($product['option'])) {
                                foreach ($product['option'] as $order_option) {
                                    // if it's updating order product, only quantity needs to be changed
                                    if (!empty($order_option['product_option_value_id'])) {
                                        $product_option_value_ids = is_array($order_option['product_option_value_id']) ? $order_option['product_option_value_id'] : array($order_option['product_option_value_id'] => array('value' => $order_option['value']));
                                        foreach ($product_option_value_ids as $product_option_value_id => $product_option_values) {
                                            if (!empty($product_option_values['value'])) {
                                                $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = quantity - (" . $quantity_change . ") WHERE product_option_value_id = '" . (int) $product_option_value_id . "' AND subtract = '1'");
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_product` SET order_id = '" . $order_id . "', product_id = '" . (int) $product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int) $product['quantity'] . "', price = '" . (float) $product['price'] . "', total = '" . (float) $product['total'] . "', tax = '" . $product['tax'] . "', reward = '" . (isset($product['reward']) ? (int) $product['reward'] : 0) . "', weight = '" . (float) $product['weight'] . "'");

                        $order_product_id = $this->db->getLastId();
                        $json['order_products_ids'][] = $order_product_id;

                        // update returns
                        if (!empty($this->request->post['returns'])) {
                            foreach ($this->request->post['returns'] as $return) {
                                // the return is already added with the old order_product_id
                                $this->db->query("UPDATE `" . DB_PREFIX . "return` SET order_product_id = '" . $order_product_id . "' WHERE pos_return_id = '" . $return['pos_return_id'] . "' AND order_product_id = '" . $product['order_product_id'] . "'");
                            }
                        }

                        // add for customer loyalty card begin
                        if (!empty($product['reward']) && (int) $customer['customer_id'] > 0 && $this->request->post['work_mode'] == '0') {
                            // update customer reward for this order
                            $reward_query = $this->db->query("SELECT points FROM `" . DB_PREFIX . "customer_reward` WHERE customer_id = '" . (int) $customer['customer_id'] . "' AND order_id = '" . (int) $order_id . "'");
                            if ($reward_query->row) {
                                $this->db->query("UPDATE `" . DB_PREFIX . "customer_reward` SET points = points + " . (int) $product['quantity'] * (int) $product['reward'] . ", date_added = NOW() WHERE customer_id = '" . (int) $customer['customer_id'] . "' AND order_id = '" . (int) $order_id . "'");
                            } else {
                                $this->db->query("INSERT INTO `" . DB_PREFIX . "customer_reward` SET customer_id = '" . (int) $customer['customer_id'] . "', order_id = '" . (int) $order_id . "', points = '" . (int) $product['quantity'] * (int) $product['reward'] . "', description = 'Order ID: " . (int) $order_id . "', date_added = NOW()");
                            }
                        }
                        // add for customer loyalty card end

                        $this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity - " . (int) $product['quantity'] . ") WHERE product_id = '" . (int) $product['product_id'] . "' AND subtract = '1'");

                        if (isset($product['option'])) {
                            foreach ($product['option'] as $order_option) {
                                if (!empty($order_option['product_option_value_id'])) {
                                    $product_option_value_ids = is_array($order_option['product_option_value_id']) ? $order_option['product_option_value_id'] : array($order_option['product_option_value_id'] => array('value' => $order_option['value']));
                                    foreach ($product_option_value_ids as $product_option_value_id => $product_option_values) {
                                        if (!empty($product_option_values['value'])) {
                                            $this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int) $order_id . "', order_product_id = '" . (int) $order_product_id . "', product_option_id = '" . (int) $order_option['product_option_id'] . "', product_option_value_id = '" . (int) $product_option_value_id . "', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($product_option_values['value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");

                                            $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int) $product['quantity'] . ") WHERE product_option_value_id = '" . (int) $product_option_value_id . "' AND subtract = '1'");
                                        }
                                    }
                                } else {
                                    if (!empty($order_option['value'])) {
                                        $this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int) $order_id . "', order_product_id = '" . (int) $order_product_id . "', product_option_id = '" . (int) $order_option['product_option_id'] . "', product_option_value_id = '0', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($order_option['value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function sync_data_end() {
        // create a temporary table for use by the sync
        $temp_customer_table = $this->request->get['uid'] . '_customer';
        $temp_address_table = $this->request->get['uid'] . '_address';
        $temp_order_product_table = $this->request->get['uid'] . '_order_product';
        $this->load->model('pos/pos');

        $json = array('customer_ids' => array(), 'product_ids' => array());

        // update the customer id first
        $customer_query = $this->db->query("SELECT * FROM `" . $temp_customer_table . "`");
        if ($customer_query->rows) {
            foreach ($customer_query->rows as $customer) {
                $customer_id = $this->model_pos_pos->addCustomer($customer);
                $customer_data = array('org_customer_id' => $customer['customer_id'], 'synced_customer_id' => $customer_id, 'address_ids' => array());
                // get all address for the customer
                $address_query = $this->db->query("SELECT * FROM `" . $temp_address_table . "` WHERE customer_id = '" . $customer['customer_id'] . "'");
                if (!empty($address_query->rows)) {
                    foreach ($address_query->rows as $address) {
                        // new address
                        $this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int) $customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int) $address['country_id'] . "', zone_id = '" . (int) $address['zone_id'] . "', custom_field = ''");
                        $address_id = $this->db->getLastId();
                        $customer_data['address_ids'][] = array('org_address_id' => $address['address_id'], 'synced_address_id' => $address_id);
                        if ($customer['address_id'] == $address['address_id']) {
                            // when old address_id is the default one, set the new address_id accordingly
                            $this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . $address_id . "' WHERE customer_id = '" . $customer_id . "'");
                        }
                    }
                }
                $json['customer_ids'][] = $customer_data;

                // update order with customer_id
                if (!empty($this->request->post['orders'])) {
                    foreach ($this->request->post['orders'] as $order) {
                        // all info is there, only the customer_id needs to be changed
                        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET customer_id = '" . $customer_id . "' WHERE order_id = '" . $order['order_id'] . "' AND customer_id = '" . $customer['customer_id'] . "'");
                    }
                }
                // update return with customer_id
                if (!empty($this->request->post['returns'])) {
                    foreach ($this->request->post['returns'] as $return) {
                        // all info is there, only the customer_id needs to be changed
                        $this->db->query("UPDATE `" . DB_PREFIX . "return` SET customer_id = '" . $customer_id . "' WHERE pos_return_id = '" . $return['pos_return_id'] . "' AND customer_id = '" . $customer['customer_id'] . "'");
                        $this->db->query("UPDATE `" . DB_PREFIX . "pos_return` SET customer_id = '" . $customer_id . "' WHERE pos_return_id = '" . $return['pos_return_id'] . "' AND customer_id = '" . $customer['customer_id'] . "'");
                    }
                }
            }
        }

        // update products (those products will be non-catalog product only), it's only applicable for orders, not returns
        $order_product_ids = array();
        if (!empty($this->request->post['orders'])) {
            foreach ($this->request->post['orders'] as $order) {
                $product_query = $this->db->query("SELECT * FROM `" . $temp_order_product_table . "` WHERE order_id = '" . $order['order_id'] . "'");
                if (!empty($product_query->rows)) {
                    foreach ($product_query->rows as $product) {
                        // add the product to the product table
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "product` SET model = '" . $this->db->escape($product['model']) . "', sku = '', upc = '', ean = '', jan = '', isbn = '', mpn = '', location = '', quantity = '1', minimum = '1', subtract = '0', stock_status_id = '7', date_available = NOW() - INTERVAL 1 DAY, manufacturer_id = '0', shipping = '0', price = '" . (float) $product['price'] . "', points = '0', weight = '0', weight_class_id = '0', length = '0', width = '0', height = '0', length_class_id = '0', status = '0', tax_class_id = '" . $this->db->escape($product['tax_class_id']) . "', sort_order = '100', quick_sale = '2', date_added = NOW()");
                        $product_id = $this->db->getLastId();
                        $json['product_ids'][] = array('org_product_id' => $product['product_id'], 'synced_product_id' => $product_id);
                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int) $product_id . "', language_id = '" . (int) $this->config->get('config_language_id') . "', name = '" . $this->db->escape($product['name']) . "', meta_keyword = '', meta_description = '', description = '', tag = ''");
                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int) $product_id . "', store_id = '" . QUICK_SALE_STORE_ID . "'");
                        // add the order product as well
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_product` SET order_id = '" . $order['order_id'] . "', product_id = '" . (int) $product_id . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int) $product['quantity'] . "', price = '" . (float) $product['price'] . "', total = '" . (float) $product['total'] . "', tax = '" . $product['tax'] . "', reward = '" . (int) $product['reward'] . "', weight = '" . (float) $product['weight'] . "'");
                        $order_product_ids[] = array('org_order_product_id' => $product['order_product_id'], 'synced_product_id' => $product_id, 'synced_order_product_id' => $this->db->getLastId());
                    }
                }
            }
        }
        // update returns
        if (!empty($this->request->post['returns'])) {
            foreach ($this->request->post['returns'] as $return) {
                foreach ($order_product_ids as $order_product_id) {
                    // the return is already added with the old order_product_id, as it's non-catalog, no option is require
                    $this->db->query("UPDATE `" . DB_PREFIX . "return` SET order_product_id = '" . $order_product_id['synced_order_product_id'] . "', product_id = '" . $order_product_id['synced_product_id'] . "' WHERE pos_return_id = '" . $return['pos_return_id'] . "' AND order_product_id = '" . $order_product_id['org_order_product_id'] . "'");
                    $this->log->write("UPDATE `" . DB_PREFIX . "return` SET order_product_id = '" . $order_product_id['synced_order_product_id'] . "', product_id = '" . $order_product_id['synced_product_id'] . "' WHERE pos_return_id = '" . $return['pos_return_id'] . "' AND order_product_id = '" . $order_product_id['org_order_product_id'] . "'");
                }
            }
        }

        // sync is done, remove the temporary tables
        $this->db->query("DROP TABLE `" . $temp_customer_table . "`");
        $this->db->query("DROP TABLE `" . $temp_address_table . "`");
        $this->db->query("DROP TABLE `" . $temp_order_product_table . "`");
        $this->response->setOutput(json_encode($json));
    }

    // add for sync offline order end
    // add for sales report begin
    public function export_report_csv() {
        $type = $this->request->get['type'];

        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=report_' . $type . '.csv');

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        $this->language->load('module/pos');

        $data = $this->config->get('POS_' . $type . '_report_items');
        if (empty($data)) {
            if ($type == 'sales') {
                // set default fields
                $data = array('(order) invoice_no' => array('title' => 'Invoice No', 'order' => 0, 'type' => 'int(11)'),
                    '(order) date_modified' => array('title' => 'Date Modified', 'order' => 1, 'type' => 'datetime'),
                    '(order) total' => array('title' => 'Sub Total', 'order' => 2, 'type' => 'decimal(15,4)'),
                    '(order_product) tax' => array('title' => 'Tax', 'order' => 3, 'type' => 'decimal(15,4)'),
                    '(order_product) quantity' => array('title' => 'Quantity', 'order' => 4, 'type' => 'int(4)'),
                    '(order_product) model' => array('title' => 'Model', 'order' => 5, 'type' => 'varchar(64)'),
                    '(order_product) cost' => array('title' => 'Cost', 'order' => 6, 'type' => 'decimal(15,4)'));
            } else {
                $data = array('(product_description) name' => array('title' => 'Product Name', 'order' => 0, 'type' => 'varchar(255)'),
                    '(manufacturer) name' => array('title' => 'Brand Name', 'order' => 1, 'type' => 'varchar(64)'),
                    '(product) supplier' => array('title' => 'Supplier', 'order' => 2, 'type' => 'varchar(64)'),
                    '(product) status' => array('title' => 'Is Active', 'order' => 3, 'type' => 'tinyint(1)'),
                    '(product) model' => array('title' => 'Stock Code', 'order' => 4, 'type' => 'decimal(15,4)'),
                    '(product) quantity' => array('title' => 'Stock On Hand', 'order' => 5, 'type' => 'int(4)'),
                    '(product) price' => array('title' => 'Price', 'order' => 6, 'type' => 'decimal(15,4)'));
            }
        }

        $use_header = $this->config->get('POS_' . $type . '_report_use_title');
        if ($use_header && (int) $use_header > 0) {
            $header = array();
            foreach ($data as $field => $value) {
                // set the column header
                $header[] = $value['title'];
            }
            fputcsv($output, $header);
        }

        $this->load->model('pos/pos');
        $start_date = false;
        $end_date = false;
        if (isset($this->request->get['startDate'])) {
            $start_date = $this->request->get['startDate'];
        }
        if (isset($this->request->get['endDate'])) {
            $end_date = $this->request->get['endDate'];
        }
        $results = $this->model_pos_pos->getExportReportData($type, $data, $start_date, $end_date);

        $size = count($results);
        if ($size > 0) {
            $row_num = count($results[0]);
            for ($row = 0; $row < $row_num; $row++) {
                for ($col = 0; $col < $size; $col++) {
                    $mapped_result = array();
                    foreach ($results[$col][$row] as $key => $value) {
                        if (array_key_exists($key, $data)) {
                            if (strpos($data[$key]['type'], 'varchar') !== false || strpos($data[$key]['type'], 'text') !== false) {
                                $value = html_entity_decode($value);
                            }
                            if (strpos($value, '"') !== false) {
                                $value = str_replace('"', '""', $value);
                            }
                            if (($data[$key]['type'] == '_pos_custom_field' && $data[$key]['feature'] == '{in_quote}') || strpos($data[$key]['type'], 'varchar') !== false || strpos($data[$key]['type'], 'text') !== false) {
                                $value = "\"" . $value . "\"";
                            }
                            $mapped_result[$data[$key]['title']] = $value;
                        }
                    }
                    //fputcsv($output, $mapped_result, ',', chr(0));
                    $str = "";
                    foreach ($mapped_result as $value) {
                        $str .= $value . ",";
                    }
                    $str = substr($str, 0, -1);
                    $str .= "\n";
                    fwrite($output, $str);
                }
            }
        }
    }

    // add for sales report end

    public function country() {
        $json = array();

        $this->load->model('localisation/country');
        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

        if ($country_info) {
            $this->load->model('localisation/zone');

            $json = array(
                'country_id' => $country_info['country_id'],
                'name' => $country_info['name'],
                'iso_code_2' => $country_info['iso_code_2'],
                'iso_code_3' => $country_info['iso_code_3'],
                'address_format' => $country_info['address_format'],
                'postcode_required' => $country_info['postcode_required'],
                'zone' => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
                'status' => $country_info['status']
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function applyDiscountLevel() {
        $json = array();
        $this->load->model('pos/pos');
        $order_product_id = $this->request->post['order_product_id'];
        $price = $this->request->post['price'];
        $this->model_pos_pos->applyDiscountLevel($order_product_id, $price);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function loadUnitConversionData() {
        $json = array();
        $html = '';
        $this->load->model('pos/pos');
        $product_id = $this->request->get['product_id'];
        $order_product_id = $this->request->get['order_product_id'];
        $unit_conversion_data = $this->model_pos_pos->getProductUnitConversionData($product_id);
        if (empty($unit_conversion_data)) {
            $json['show_units'] = false;
        } else {
            $json['show_units'] = true;
            $selected_unit = $this->model_pos_pos->getSelectedOrderProductUnit($order_product_id);
            $html .= '<select id="quantity-unit-data" name="quantity_unit[]" class="display">';
            foreach ($unit_conversion_data as $unit_conversion_product) {
                if ($unit_conversion_product['unit_conversion_product_id'] == $selected_unit) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
                $html .= '<option data-value="' . $unit_conversion_product['unit_conversion_product_id'] . '" value="' . $unit_conversion_product['convert_price'] . '" ' . $selected . '>' . $unit_conversion_product['name'] . '</option>';
            }

            $html .= '</select>';
            $json['html_data'] = $html;
            // ali changes 02/03/2020
            $json['org_unit_conversion'] = $selected_unit;
            // end ali changes
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function loadDiscountLevelData() {
        $this->load->model('pos/pos');
        $this->load->model('catalog/product');
        $customer_group_id = $this->request->get['customer_group_id'];
        $product_id = $this->request->get['product_id'];
        $order_product_id = $this->request->get['order_product_id'];
        $quantity = $this->request->get['quantity'];
        $html = '';

        $customer_group_data = $this->model_pos_pos->getCustomerGroup($customer_group_id);
        $customer_group_name = !empty($customer_group_data) ? $customer_group_data['name'] : "";
        $orignial_price = $this->currency->format($this->model_pos_pos->getMainPrice($product_id));
        $customer_group_prices = $this->model_catalog_product->getProductDiscountsByCustomerGroud($product_id, $customer_group_id);
        foreach ($customer_group_prices as $k => $customer_group_price) {
            $customer_group_prices[$k]['db_price'] = $customer_group_price['price'];
            $customer_group_prices[$k]['price'] = $this->currency->format($customer_group_price['price']);
        }

        $html .= '<input type="hidden" id="current_order_product_id" value="' . $order_product_id . '">';
        $html .= '<strong style="font-size:15px;padding-left:10px;">' . ucfirst($customer_group_name) . '</strong>';
        $html .= '<table width="100%"><tr><td width="50%"><input type="radio" name="discount_level_radio_check"
			value="' . $this->model_pos_pos->getMainPrice($product_id) . '">&nbsp;&nbsp;&nbsp;Non-Wholesale</td><td width="20%">1.00</td>
								<td style="margin-right:20px;" class="red-price pull-right">' . $orignial_price . '</td></tr>';

        $checked = 0;
        switch ($quantity) {
            case $quantity < 10 : $checked = 0;
                break;
            case $quantity < 50 : $checked = 1;
                break;
            case $quantity >= 50 : $checked = 2;
                break;
        }

        foreach ($customer_group_prices as $k => $customer_group_price) {
            if ($k == $checked) {
                $redio_checked = "checked";
            } else {
                $redio_checked = "";
            }
            $customer_group_price_qty = "";
            switch ($customer_group_price['quantity']) {
                case '1' : $customer_group_price_qty = "1 to 9";
                    break;
                case '10' : $customer_group_price_qty = "10 to 49";
                    break;
                case '50' : $customer_group_price_qty = "50+ ";
                    break;
            }
            if ($customer_group_price['discount_percent'] == 0) {
                $percent_discount = 1;
            } else {
                $percent_discount = 1 - $customer_group_price['discount_percent'] / 100;
            }

            $percent_discount = number_format((float) $percent_discount, 2, '.', '');

            $html .= '<tr><td width="50%"><input type="radio" name="discount_level_radio_check" value="' . $customer_group_price['db_price'] . '" ' . $redio_checked . '>&nbsp;&nbsp;&nbsp;' . $customer_group_price_qty . '</td><td width="20%">' . $percent_discount . '</td><td style="margin-right:20px;" class="red-price pull-right">' . $customer_group_price['price'] . '</td></tr>';
        }

        $html .= '</table><div class="pull-right" style="clear:both;margin-top:20px;"><div class="keypad_wrap"><a class="btn ok" id="discount_level_btn" onclick="applyDiscountLevel()">OK</a>
								</div></div>';

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($html));
    }

    public function checkIfCustomerIsWholesaleAndHasDiscount() {

        $customer_id = $this->request->get['customer_id'];
        $product_id = $this->request->get['product_id'];
        $discount_quantity = $this->request->get['qty'];
        $customer_query = $this->db->query("SELECT customer_group_id FROM " . DB_PREFIX . "customer WHERE customer_id='" . $customer_id . "'");
        if ($customer_query->num_rows) {
            $customer_group_id = $customer_query->row['customer_group_id'];

            $m = "SELECT
                " . DB_PREFIX . "product_discount.price as discount,
                " . DB_PREFIX . "product.price as base_price

            FROM
                " . DB_PREFIX . "product_discount
            LEFT JOIN " . DB_PREFIX . "product ON " . DB_PREFIX . "product_discount.product_id = " . DB_PREFIX . "product.product_id
            WHERE
                " . DB_PREFIX . "product_discount.product_id = " . $product_id . "
            AND customer_group_id = " . $customer_group_id . "
            AND " . DB_PREFIX . "product_discount.quantity <= " . $discount_quantity . "
            AND (
                (
                    date_start = '0000-00-00'
                    OR date_start < NOW()
                )
                AND (
                    date_end = '0000-00-00'
                    OR date_end > NOW()
                )
            )
            ORDER BY
                " . DB_PREFIX . "product_discount.quantity DESC,
                " . DB_PREFIX . "product_discount.priority ASC,
                " . DB_PREFIX . "product_discount.price ASC
            LIMIT 1";

            $query = $this->db->query($m);
            $discount_result = $query->row;
            if (isset($discount_result['discount'])) {
                $discount_result['base_price'] = ($discount_result['base_price'] == 0) ? 1 : $discount_result['base_price'];
                echo json_encode(($discount_result['discount'] / $discount_result['base_price']));
            } else {
                echo json_encode(1);
            }
        }
    }

}

?>
