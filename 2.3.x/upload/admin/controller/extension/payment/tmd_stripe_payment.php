<?php
//lib
require_once(DIR_SYSTEM.'library/tmd/system.php');
//lib
class ControllerExtensionPaymentTmdStripePayment extends Controller {
	private $error = array();

	public function index() {
		$this->registry->set('tmd', new TMD($this->registry));
		$keydata=array(
		'code'=>'tmdkey_tmd_stripe_payment',
		'eid'=>'',
		'route'=>'extension/payment/tmd_stripe_payment',
		);
		$tmd_stripe_payment=$this->tmd->getkey($keydata['code']);
		$data['getkeyform']=$this->tmd->loadkeyform($keydata);
		
		$this->load->language('extension/payment/tmd_stripe_payment');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->model_setting_setting->editSetting('payment_tmd_stripe_payment', $this->request->post);
			$this->model_setting_setting->editSetting('tmd_stripe_payment', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		/*Language*/
		$data['heading_title'] = $this->language->get('heading_title');
		$data['heading_title1'] = $this->language->get('heading_title1');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['text_success'] = $this->language->get('text_success');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_test'] = $this->language->get('text_test');
		$data['text_live'] = $this->language->get('text_live');
		$data['text_strip_setting'] = $this->language->get('text_strip_setting');
		$data['text_capture'] = $this->language->get('text_capture');
		$data['text_authorize'] = $this->language->get('text_authorize');
		$data['text_terminal_setting'] = $this->language->get('text_terminal_setting');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_pay_title'] = $this->language->get('entry_pay_title');
		$data['entry_transaction_p_key'] = $this->language->get('entry_transaction_p_key');
		$data['entry_transaction_mode'] = $this->language->get('entry_transaction_mode');
		$data['entry_live_p_key'] = $this->language->get('entry_live_p_key');
		$data['entry_live_s_key'] = $this->language->get('entry_live_s_key');
		$data['entry_test_p_key'] = $this->language->get('entry_test_p_key');
		$data['entry_test_s_key'] = $this->language->get('entry_test_s_key');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_custom_desc'] = $this->language->get('entry_custom_desc');
		$data['entry_charge_mode'] = $this->language->get('entry_charge_mode');
		$data['entry_subscription_status'] = $this->language->get('entry_subscription_status');
		$data['entry_max_capture_delay'] = $this->language->get('entry_max_capture_delay');

		$data['help_charge_mode'] = $this->language->get('help_charge_mode');
		$data['help_total'] = $this->language->get('help_total');
		$data['help_capture_delay'] = $this->language->get('help_capture_delay');

		$data['error_permission'] = $this->language->get('error_permission');
		$data['error_test_public_key'] = $this->language->get('error_test_public_key');
		$data['error_test_secret_key'] = $this->language->get('error_test_secret_key');
		$data['error_live_public_key'] = $this->language->get('error_live_public_key');
		$data['error_live_secret_key'] = $this->language->get('error_live_secret_key');
		/*Language*/

		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
		
			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['test_public_key'])) {
			$data['error_test_public_key'] = $this->error['test_public_key'];
		} else {
			$data['error_test_public_key'] = '';
		}

		if (isset($this->error['test_secret_key'])) {
			$data['error_test_secret_key'] = $this->error['test_secret_key'];
		} else {
			$data['error_test_secret_key'] = '';
		}

		if (isset($this->error['live_public_key'])) {
			$data['error_live_public_key'] = $this->error['live_public_key'];
		} else {
			$data['error_live_public_key'] = '';
		}

		if (isset($this->error['live_secret_key'])) {
			$data['error_live_secret_key'] = $this->error['live_secret_key'];
		} else {
			$data['error_live_secret_key'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/tmd_stripe_payment', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/tmd_stripe_payment', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

		if (isset($this->request->post['payment_tmd_stripe_payment_max_capture_delay'])) {
			$data['payment_tmd_stripe_payment_max_capture_delay'] = $this->request->post['payment_tmd_stripe_payment_max_capture_delay'];
		} else {
			$data['payment_tmd_stripe_payment_max_capture_delay'] = $this->config->get('payment_tmd_stripe_payment_max_capture_delay');
		}

		if (isset($this->request->post['payment_tmd_stripe_payment_subscription_status'])) {
			$data['payment_tmd_stripe_payment_subscription_status'] = $this->request->post['payment_tmd_stripe_payment_subscription_status'];
		} else {
			$data['payment_tmd_stripe_payment_subscription_status'] = $this->config->get('payment_tmd_stripe_payment_subscription_status');
		}

		if (isset($this->request->post['payment_tmd_stripe_payment_transaction_mode'])) {
			$data['payment_tmd_stripe_payment_transaction_mode'] = $this->request->post['payment_tmd_stripe_payment_transaction_mode'];
		} else {
			$data['payment_tmd_stripe_payment_transaction_mode'] = $this->config->get('payment_tmd_stripe_payment_transaction_mode');
		}

		if (isset($this->request->post['payment_tmd_stripe_payment_test_public_key'])) {
			$data['payment_tmd_stripe_payment_test_public_key'] = $this->request->post['payment_tmd_stripe_payment_test_public_key'];
		} else {
			$data['payment_tmd_stripe_payment_test_public_key'] = $this->config->get('payment_tmd_stripe_payment_test_public_key');
		}

		if (isset($this->request->post['payment_tmd_stripe_payment_test_secret_key'])) {
			$data['payment_tmd_stripe_payment_test_secret_key'] = $this->request->post['payment_tmd_stripe_payment_test_secret_key'];
		} else {
			$data['payment_tmd_stripe_payment_test_secret_key'] = $this->config->get('payment_tmd_stripe_payment_test_secret_key');
		}

		if (isset($this->request->post['payment_tmd_stripe_payment_live_public_key'])) {
			$data['payment_tmd_stripe_payment_live_public_key'] = $this->request->post['payment_tmd_stripe_payment_live_public_key'];
		} else {
			$data['payment_tmd_stripe_payment_live_public_key'] = $this->config->get('payment_tmd_stripe_payment_live_public_key');
		}

		if (isset($this->request->post['payment_tmd_stripe_payment_live_secret_key'])) {
			$data['payment_tmd_stripe_payment_live_secret_key'] = $this->request->post['payment_tmd_stripe_payment_live_secret_key'];
		} else {
			$data['payment_tmd_stripe_payment_live_secret_key'] = $this->config->get('payment_tmd_stripe_payment_live_secret_key');
		}

		if (isset($this->request->post['payment_tmd_stripe_payment_total'])) {
			$data['payment_tmd_stripe_payment_total'] = $this->request->post['payment_tmd_stripe_payment_total'];
		} else {
			$data['payment_tmd_stripe_payment_total'] = $this->config->get('payment_tmd_stripe_payment_total');
		}

		if (isset($this->request->post['payment_tmd_stripe_payment_order_status_id'])) {
			$data['payment_tmd_stripe_payment_order_status_id'] = $this->request->post['payment_tmd_stripe_payment_order_status_id'];
		} else {
			$data['payment_tmd_stripe_payment_order_status_id'] = $this->config->get('payment_tmd_stripe_payment_order_status_id');
		}

		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_tmd_stripe_payment_geo_zone_id'])) {
			$data['payment_tmd_stripe_payment_geo_zone_id'] = $this->request->post['payment_tmd_stripe_payment_geo_zone_id'];
		} else {
			$data['payment_tmd_stripe_payment_geo_zone_id'] = $this->config->get('payment_tmd_stripe_payment_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['tmd_stripe_payment_status'])) {
			$data['tmd_stripe_payment_status'] = $this->request->post['tmd_stripe_payment_status'];
		} else {
			$data['tmd_stripe_payment_status'] = $this->config->get('tmd_stripe_payment_status');
		}

		if (isset($this->request->post['payment_tmd_stripe_payment_sort_order'])) {
			$data['payment_tmd_stripe_payment_sort_order'] = $this->request->post['payment_tmd_stripe_payment_sort_order'];
		} else {
			$data['payment_tmd_stripe_payment_sort_order'] = $this->config->get('payment_tmd_stripe_payment_sort_order');
		}

		if (isset($this->request->post['payment_tmd_stripe_payment_charge_mode'])) {
			$data['payment_tmd_stripe_payment_charge_mode'] = $this->request->post['payment_tmd_stripe_payment_charge_mode'];
		} else {
			$data['payment_tmd_stripe_payment_charge_mode'] = $this->config->get('payment_tmd_stripe_payment_charge_mode');
		}

		if (isset($this->request->post['payment_tmd_stripe_payment_custom_description'])) {
			$data['payment_tmd_stripe_payment_custom_description'] = $this->request->post['payment_tmd_stripe_payment_custom_description'];
		} else {
			$data['payment_tmd_stripe_payment_custom_description'] = $this->config->get('payment_tmd_stripe_payment_custom_description');
		}

		if (isset($this->request->post['payment_tmd_stripe_payment_title'])) {
			$data['payment_tmd_stripe_payment_title'] = $this->request->post['payment_tmd_stripe_payment_title'];
		} else {
			$data['payment_tmd_stripe_payment_title'] = $this->config->get('payment_tmd_stripe_payment_title');
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/tmd_stripe_payment', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/tmd_stripe_payment')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		$tmd_stripe_payment=$this->config->get('tmdkey_tmd_stripe_payment');
		if (empty(trim($tmd_stripe_payment))) {			
		$this->session->data['warning'] ='Module will Work after add License key!';
		$this->response->redirect($this->url->link('extension/payment/tmd_stripe_payment', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		if($this->request->post['payment_tmd_stripe_payment_transaction_mode'] == 'test'){
			if (!$this->request->post['payment_tmd_stripe_payment_test_public_key']) {
				$this->error['test_public_key'] = $this->language->get('error_test_public_key');
			}
			
			if (!$this->request->post['payment_tmd_stripe_payment_test_secret_key']) {
				$this->error['test_secret_key'] = $this->language->get('error_test_secret_key');
			}
		} else {
			if (!$this->request->post['payment_tmd_stripe_payment_live_public_key']) {
				$this->error['live_public_key'] = $this->language->get('error_live_public_key');
			}
			
			if (!$this->request->post['payment_tmd_stripe_payment_live_secret_key']) {
				$this->error['live_secret_key'] = $this->language->get('error_live_secret_key');
			}
		}

		return !$this->error;
	}
	public function keysubmit() {
		$json = array(); 
		
      	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$keydata=array(
			'code'=>'tmdkey_tmd_stripe_payment',
			'eid'=>'NDY2OTQ=',
			'route'=>'extension/payment/tmd_stripe_payment',
			'moduledata_key'=>$this->request->post['moduledata_key'],
			);
			$this->registry->set('tmd', new TMD($this->registry));
            $json=$this->tmd->matchkey($keydata);       
		} 
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}