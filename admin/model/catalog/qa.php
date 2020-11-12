<?php
class ModelCatalogQA extends Model {
    public function addQA($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "qa SET product_id = '" . $this->db->escape($data['product_id']) . "', q_author = '" . $this->db->escape($data['q_author']) . "', q_author_email = '" . $this->db->escape($data['q_author_email']) . "', a_author = '" . $this->db->escape($data['a_author']) . "', question = '" . $this->db->escape(strip_tags($data['question'])) . "', answer = '" . $this->db->escape(html_entity_decode($data['answer'], ENT_COMPAT, 'UTF-8')) . "', status = '" . (int)$data['status'] . "', date_asked = NOW()" . (($this->db->escape($data['answer']) != "") ? ", date_answered = NOW()" : "") . ", lang_code = '" . $data['language'] . "'");
    }

    public function editQA($qa_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "qa SET product_id = '" . $this->db->escape($data['product_id']) . "', q_author = '" . $this->db->escape($data['q_author']) . "', q_author_email = '" . $this->db->escape($data['q_author_email']) . "', a_author = '" . $this->db->escape($data['a_author']) . "', question = '" . $this->db->escape(strip_tags($data['question'])) . "', answer = '" . $this->db->escape(html_entity_decode($data['answer'], ENT_COMPAT, 'UTF-8')) . "', status = '" . (int)$data['status'] . "'" . (($this->db->escape($data['answer']) != "") ? ", date_answered = NOW()" : "") . ", lang_code = '" . $data['language'] . "' WHERE qa_id = '" . (int)$qa_id . "'");
    }

    public function deleteQA($qa_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "qa WHERE qa_id = '" . (int)$qa_id . "'");
    }

    public function getQA($qa_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "qa WHERE qa_id = '" . (int)$qa_id . "'");

        return $query->row;
    }

    public function getQAs($data = array()) {
        $sql = "SELECT qa.qa_id, qa.customer_id, pd.name, qa.q_author, qa.q_author_email, qa.a_author, qa.status, qa.date_asked, qa.answer, qa.date_answered, l.name AS language FROM " . DB_PREFIX . "qa qa LEFT JOIN " . DB_PREFIX . "product_description pd ON (qa.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "language l ON (qa.lang_code = l.code) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $sort_data = array(
            'pd.name',
            'l.name',
            'qa.q_author',
            'qa.q_author_email',
            'qa.a_author',
            'qa.answer',
            'qa.status',
            'qa.date_asked',
            'qa.date_answered'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY qa.date_asked";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalQuestions() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "qa");

        return $query->row['total'];
    }

    public function getTotalQuestionsAwaitingAnswer() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "qa WHERE answer = ''");

        return $query->row['total'];
    }
}
?>