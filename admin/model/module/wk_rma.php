<?php

class ModelModulewkrma extends Model {

	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "wk_rma_order` (
                        `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                        `order_id` INT(11) NOT NULL ,
                        `images` varchar(100) NOT NULL ,
                      	`add_info` varchar(4000) NOT NULL ,
                        `admin_status` INT(11) NOT NULL ,
                        `rma_auth_no` varchar(100) NOT NULL ,
                        `cancel_rma` INT(5) NOT NULL ,
                        `solve_rma` INT(5) NOT NULL ,
                        `admin_return` INT(100) NOT NULL ,
                        `date` DATETIME NOT NULL ,
                        `shipping_label` varchar(100) NOT NULL ,
                        PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8 ;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "wk_rma_product` (
                        `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                        `rma_id` INT(11) NOT NULL ,
                        `reason` varchar(4000) NOT NULL ,
                        `product_id` INT(11) NOT NULL ,
                        `quantity` INT(11) NOT NULL ,
                        `order_product_id` INT(11) NOT NULL ,
                        PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8 ;");

    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "wk_rma_customer` (
                        `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                        `rma_id` INT(11) NOT NULL ,
                        `customer_id` INT(11) NOT NULL ,
                        `email` varchar(50) NOT NULL ,
                        PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8 ;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "wk_rma_order_message` (
                        `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                        `rma_id` INT(11) NOT NULL ,
                        `writer` varchar(50) NOT NULL ,
                        `message` varchar(5000) NOT NULL ,
                        `date` DATETIME NOT NULL ,
                        `attachment` varchar(100) NOT NULL ,
                        PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8 ;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "wk_rma_status` (
				                 `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                         `status_id` INT(11) NOT NULL,
                         `language_id` INT(11) NOT NULL,
                         `name` varchar(100) NOT NULL,
                         `status` INT(1) NOT NULL ,
												 `color` varchar(100) NOT NULL,
                         `default` VARCHAR(10) NOT NULL,
                         PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8 ;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "wk_rma_reason` (
			       `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                         `reason_id` INT(11) NOT NULL,
                         `reason` varchar(200) NOT NULL,
                         `language_id` INT(11) NOT NULL,
                         `status` INT(11) NOT NULL ,
                         PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8 ;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "wk_rma_transaction` (
									      `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
						            `transaction_id` INT(11) NOT NULL,
						            `rma_id` INT(11) NOT NULL,
						            PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8 ;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "wk_rma_voucher` (
									      `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
						            `transaction_id` INT(11) NOT NULL,
						            `rma_id` INT(11) NOT NULL,
						            PRIMARY KEY (`id`) ) DEFAULT CHARSET=utf8 ;");

	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "wk_rma_order`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "wk_rma_product`");
    $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "wk_rma_customer`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "wk_rma_order_message`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "wk_rma_status`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "wk_rma_reason`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "wk_rma_transaction`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "wk_rma_voucher`");

	}

	public function removeOCMOD() {
		$this->db->query("UPDATE `" . DB_PREFIX . "modification` status = '0' WHERE code='Webkul RMA'");
	}

}
