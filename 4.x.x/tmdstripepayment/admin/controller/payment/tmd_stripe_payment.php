<?php
namespace Opencart\Admin\Controller\Extension\TmdStripepayment\Payment;
// Lib Include 
require_once(DIR_EXTENSION.'/tmdstripepayment/system/library/tmd/system.php');
// Lib Include 
class TmdStripePayment extends \Opencart\System\Engine\Controller {
	public function index() {
		$this->registry->set('tmd', new  \Tmdstripepayment\System\Library\Tmd\System($this->registry));
		$keydata=array(
		'code'=>'tmdkey_tmd_stripe_payment',
		'eid'=>'NDY2OTQ=',
		'route'=>'extension/tmdstripepayment/payment/tmd_stripe_payment',
		);
		$tmd_stripe_payment=$this->tmd->getkey($keydata['code']);
		$data['getkeyform']=$this->tmd->loadkeyform($keydata);
		
		$this->load->language('extension/tmdstripepayment/payment/tmd_stripe_payment');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('setting/setting');
		
		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
		
			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/tmdstripepayment/payment/tmd_stripe_payment', 'user_token=' . $this->session->data['user_token'], true)
		];

		$data['action'] = $this->url->link('extension/tmdstripepayment/payment/tmd_stripe_payment.save', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->post['payment_tmd_stripe_payment_max_capture_delay'])) {
			$data['payment_tmd_stripe_payment_max_capture_delay'] = $this->request->post['payment_tmd_stripe_payment_max_capture_delay'];
		} else {
			$data['payment_tmd_stripe_payment_max_capture_delay'] = $this->config->get('payment_tmd_stripe_payment_max_capture_delay');
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

		if (isset($this->request->post['payment_tmd_stripe_payment_status'])) {
			$data['payment_tmd_stripe_payment_status'] = $this->request->post['payment_tmd_stripe_payment_status'];
		} else {
			$data['payment_tmd_stripe_payment_status'] = $this->config->get('payment_tmd_stripe_payment_status');
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

		$this->response->setOutput($this->load->view('extension/tmdstripepayment/payment/tmd_stripe_payment', $data));
	}
	public function save(): void {
		$this->load->language('extension/tmdstripepayment/payment/tmd_stripe_payment');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/tmdstripepayment/payment/tmd_stripe_payment')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}
		
		$tmd_stripe_payment=$this->config->get('tmdkey_tmd_stripe_payment');
		if (empty(trim($tmd_stripe_payment))) {			
		$json['error'] ='Module will Work after add License key!';
		}

		if($this->request->post['payment_tmd_stripe_payment_transaction_mode'] == 'test'){
			if (!$this->request->post['payment_tmd_stripe_payment_test_public_key']) {
				$json['error']['test_public_key'] = $this->language->get('error_test_public_key');
			}
			
			if (!$this->request->post['payment_tmd_stripe_payment_test_secret_key']) {
				$json['error']['test_secret_key'] = $this->language->get('error_test_secret_key');
			}
		} else {
			if (!$this->request->post['payment_tmd_stripe_payment_live_public_key']) {
				$json['error']['live_public_key'] = $this->language->get('error_live_public_key');
			}
			
			if (!$this->request->post['payment_tmd_stripe_payment_live_secret_key']) {
				$json['error']['live_secret_key'] = $this->language->get('error_live_secret_key');
			}
		}
		
		if (!$json) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('payment_tmd_stripe_payment', $this->request->post);

			$json['success'] = $this->language->get('text_success');

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	 public function install():void	{
	 	$this->load->model('user/user_group');
		
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/tmdstripepayment/payment/tmd_stripe_payment');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/tmdstripepayment/payment/tmd_stripe_payment');

      
	
	    // Tmd Checkout/checkout Event
		$this->load->model('setting/event');

		$this->model_setting_event->deleteEventByCode('tmd_stripecheckout');
		if(VERSION >= '4.0.2.0'){
			$eventaction = 'extension/tmdstripepayment/payment/tmd_stripe_payment.stripecheckout';
		}else{
			$eventaction = 'extension/tmdstripepayment/payment/tmd_stripe_payment|stripecheckout';
		}
		$eventrequest=[
					'code'=>'tmd_stripecheckout',
					'description'=>'TMD Stripepayment checkout event',
					'trigger'=>'catalog/view/checkout/checkout/before',
					'action'=>$eventaction,
					'status'=>'1',
					'sort_order'=>'1',
				];
				
		if(VERSION=='4.0.0.0'){
		    $this->model_setting_event->addEvent('tmd_stripecheckout', 'TMD Stripepayment checkout event', 'catalog/view/checkout/checkout/before', 'extension/tmdstripepayment/payment/tmd_stripe_payment|stripecheckout', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}

	}

	  public function uninstall():void {
      
		// Tmd SMs  OTP Verifiction Event
		$this->load->model('setting/event');
		$this->load->model('user/user_group');
		$this->model_setting_event->deleteEventByCode('tmd_stripecheckout');
		

		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/tmdstripepayment/payment/tmd_stripe_payment');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/tmdstripepayment/payment/tmd_stripe_payment');

	}
	public function keysubmit() {
		$json = array(); 
		
      	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$keydata=array(
			'code'=>'tmdkey_tmd_stripe_payment',
			'eid'=>'NDY2OTQ=',
			'route'=>'extension/tmdstripepayment/payment/tmd_stripe_payment',
			'moduledata_key'=>$this->request->post['moduledata_key'],
			);
			$this->registry->set('tmd', new  \Tmdstripepayment\System\Library\Tmd\System($this->registry));
		
            $json=$this->tmd->matchkey($keydata);       
		} 
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}