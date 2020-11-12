<?php
class ModelTotalPosRounding extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$enable_rounding = $this->config->get('enable_rounding');
		$config_rounding = $this->config->get('config_rounding');
		if ($enable_rounding && $config_rounding) {
			$amount = round(($total * 10 - (int)($total * 10)) / 10, 2);
			if ($config_rounding == '5c') {
				if ($amount < 0.03) {
					$amount = -$amount;
				} elseif ($amount < 0.05 || ($amount > 0.05 && $amount < 0.08)) {
					$amount = 0.05 - $amount;
				} elseif ($amount >= 0.08) {
					$amount = 0.1 - $amount;
				} else {
					$amount = 0;
				}
			} elseif ($config_rounding == '10c') {
				$amount = round($amount, 1) - $amount;
			} elseif ($config_rounding == '50c') {
				$amount = round($total - (int)($total), 2);
				if ($amount < 0.5) {
					$amount = -$amount;
				} elseif ($amount > 0.5) {
					$amount = 0.5 - $amount;
				}
			}
			
			$total_data[] = array(
				'code'       => 'pos_rounding',
				'title'      => 'Rounding',
				'text'       => $this->currency->format($amount),
				'value'      => $amount,
				'sort_order' => $this->config->get('pos_rounding_sort_order')
			);

			$total += $amount;
		}
	}
}
?>