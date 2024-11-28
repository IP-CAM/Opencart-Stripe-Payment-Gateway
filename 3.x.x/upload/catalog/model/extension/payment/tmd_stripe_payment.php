<?php
class ModelExtensionPaymentTmdStripePayment extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/tmd_stripe_payment');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_tmd_stripe_payment_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('payment_tmd_stripe_payment_total') > 0 && $this->config->get('payment_tmd_stripe_payment_total') > $total) {
			$status = false;
		} elseif (!$this->cart->hasShipping()) {
			$status = false;
		} elseif (!$this->config->get('payment_tmd_stripe_payment_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$payment_title = $this->config->get('payment_tmd_stripe_payment_title');
		if(!empty($payment_title[$this->config->get('config_language_id')]['title'])){
			$text_title = $payment_title[$this->config->get('config_language_id')]['title'];
		}else{
			$text_title = $this->language->get('text_title');
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'tmd_stripe_payment',
				'title'      => $text_title,
				'terms'      => '',
				'sort_order' => $this->config->get('payment_tmd_stripe_payment_sort_order')
			);
		}

		return $method_data;
	}
}
