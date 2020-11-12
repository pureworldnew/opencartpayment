<?php
class ModelLocalisationProductslabel extends Model {

    public function editlabel($data) { 
        if($data['label_id'][1]) {
            $this->db->query("UPDATE " . DB_PREFIX . "products_label SET label_text = '" . $this->db->escape($data['label_text'][1]) . "', label_color = '" . $this->db->escape($data['label_color'][1]) . "', label_text_color = '" . $this->db->escape($data['label_text_color'][1]) . "', condition_type = '" . $this->db->escape($data['condition_type'][1]) . "', status = '" . $this->db->escape($data['status'][1]) . "', position = '" . $this->db->escape($data['position1']) . "'  WHERE label_id = '" . (int)$data['label_id'][1] . "'");
        }
        if($data['label_id'][2]) {
            $this->db->query("UPDATE " . DB_PREFIX . "products_label SET label_text = '" . $this->db->escape($data['label_text'][2]) . "', label_color = '" . $this->db->escape($data['label_color'][2]) . "', label_text_color = '" . $this->db->escape($data['label_text_color'][2]) . "', condition_type = '" . $this->db->escape($data['condition_type'][2]) . "', status = '" . $this->db->escape($data['status'][2]) . "', position = '" . $this->db->escape($data['position2']) . "' WHERE label_id = '" . (int)$data['label_id'][2] . "'");
        }
        if($data['label_id'][3]) {
            $this->db->query("UPDATE " . DB_PREFIX . "products_label SET label_text = '" . $this->db->escape($data['label_text'][3]) . "', label_color = '" . $this->db->escape($data['label_color'][3]) . "', label_text_color = '" . $this->db->escape($data['label_text_color'][3]) . "', status = '" . $this->db->escape($data['status'][3]) . "', position = '" . $this->db->escape($data['position3']) . "' WHERE label_id = '" . (int)$data['label_id'][3] . "' ");
        }
        if($data['label_id'][4]) {
            $this->db->query("UPDATE " . DB_PREFIX . "products_label SET label_text = '" . $this->db->escape($data['label_text'][4]) . "', label_color = '" . $this->db->escape($data['label_color'][4]) . "', label_text_color = '" . $this->db->escape($data['label_text_color'][4]) . "', status = '" . $this->db->escape($data['status'][4]) . "', position = '" . $this->db->escape($data['position4']) . "' WHERE label_id = '" . (int)$data['label_id'][4] . "' ");
        }
        if($data['label_id'][5]) {
            $this->db->query("UPDATE " . DB_PREFIX . "products_label SET label_text = '" . $this->db->escape($data['label_text'][5]) . "', label_color = '" . $this->db->escape($data['label_color'][5]) . "', label_text_color = '" . $this->db->escape($data['label_text_color'][5]) . "', status = '" . $this->db->escape($data['status'][5]) . "', position = '" . $this->db->escape($data['position5']) . "' WHERE label_id = '" . (int)$data['label_id'][5] . "' ");
        }
    }

    public function getLabels() {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "products_label");
        return $query->rows;
    }

}