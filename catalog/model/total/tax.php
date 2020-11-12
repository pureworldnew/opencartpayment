<?php
class ModelTotalTax extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		foreach ($taxes as $key => $value) {
			if($this->customer->isLogged()){
						if($this->customer->getResaleNumber()){
							$this->session->data['resale_id_number'] = $this->customer->getResaleNumber();
						}
					}
					
                    if(isset($this->session->data['resale_id_number'])){
                        $value = 0;
                    }
			if ($value > 0) {
				$total_data[] = array(
					'code'       => 'tax',
					'title'      => $this->tax->getRateName($key),
					'value'      => $value,
					'sort_order' => $this->config->get('tax_sort_order')
				);

				$total += $value;
			}
		}
	}
}