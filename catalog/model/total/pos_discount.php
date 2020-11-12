<?php
class ModelTotalPosDiscount extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if (isset($this->session->data['pos_discount'])) {
			$amount = 0;
			$discount = $this->session->data['pos_discount'];
			if ($discount['code'] == 'pos_discount_fixed') {
				if ((0-$discount['value']) > $total) {
					$amount = -$total;	
				} else {
					$amount = $discount['value'];	
				}				
			} elseif ($discount['code'] == 'pos_discount_percentage') {
				$percentage_text = $discount['title'];
				$index1 = strpos($percentage_text, '(');
				$index2 = strpos($percentage_text, ')');
				$percentage = 0;
				if ($index1 !== false && $index2 !== false && $index2 > $index1+2) {
					$percentage = (float)substr($percentage_text, $index1+1, $index2-$index1-1);
				}

				if ($this->cart->getSubTotal()) {
					$amount = 0 - $this->cart->getSubTotal() * $percentage / 100;
				}
			}
			
			$total_data[] = array(
				'code'       => $discount['code'],
				'title'      => $discount['title'],
				'text'       => $this->currency->format($amount),
				'value'      => $amount,
				'sort_order' => $this->config->get('pos_discount_sort_order')
			);

			$total += $amount;
		}
	}
}
?>