<?php
namespace Opencart\Catalog\Model\Extension\TmdStripePayment\Payment;
class TmdStripePayment extends \Opencart\System\Engine\Model {

	public function getMethods(array $address = []): array {
		$this->load->language('extension/tmdstripepayment/payment/tmd_stripe_payment');
		
		$total = $this->cart->getTotal();

		if ($this->config->get('payment_tmd_stripe_payment_total') > 0 && $this->config->get('payment_tmd_stripe_payment_total') > $total) {
			$status = false;
		} elseif ($this->cart->hasSubscription()) {
			$status = false;
		} elseif (!$this->config->get('config_checkout_payment_address')) {
			$status = true;
		} elseif (!$this->config->get('payment_tmd_stripe_payment_geo_zone_id')) {
			$status = true;
		} else {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$this->config->get('payment_tmd_stripe_payment_geo_zone_id') . "' AND `country_id` = '" . (int)$address['country_id'] . "' AND (`zone_id` = '" . (int)$address['zone_id'] . "' OR `zone_id` = '0')");

			if ($query->num_rows) {
				$status = true;
			} else {
				$status = false;
			}
		}

		$payment_title = $this->config->get('payment_tmd_stripe_payment_title');
		if(!empty($payment_title[$this->config->get('config_language_id')]['title'])){
			$text_title = $payment_title[$this->config->get('config_language_id')]['title'];
		}else{
			$text_title = $this->language->get('text_title');
		}

		$method_data = [];

		if ($status) {
			$option_data['tmd_stripe_payment'] = [
                'code' => 'tmd_stripe_payment.tmd_stripe_payment',
                'name' => $text_title
            ];

			$method_data = [
				'code'       => 'tmd_stripe_payment',
				'name'      => $text_title,
				'option'     => $option_data,
				'sort_order' => $this->config->get('payment_tmd_stripe_payment_sort_order')
			];
		}

		return $method_data;
	}


	public function getMethod(array $address): array {
		$this->load->language('extension/tmdstripepayment/payment/tmd_stripe_payment');
		$total = $this->cart->getTotal();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_payubiz_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('payment_tmd_stripe_payment_total') > $total) {
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

		$method_data = [];
		if ($status) {
			$method_data = [
				'code'       => 'tmd_stripe_payment',
				'title'      => $text_title,
				'terms'      => '',
				'sort_order' => $this->config->get('payment_tmd_stripe_payment_sort_order')
			];
		}
		
		return $method_data;
	}

}
