<?php
namespace Opencart\Catalog\Controller\Extension\Tmdstripepayment\Payment;
require_once(DIR_EXTENSION.'/tmdstripepayment/system/library/stripe/init.php');
class Tmdstripepayment extends \Opencart\System\Engine\Controller {

    private $error = [];

    public function index() {
        $this->load->language('extension/tmdstripepayment/payment/tmd_stripe_payment');

        $this->load->model('extension/tmdstripepayment/payment/tmd_stripe_payment');
        $transaction_mode= $this->config->get('payment_tmd_stripe_payment_transaction_mode');
        if($transaction_mode=='live'){
            $data['publishable_key'] = $this->config->get('payment_tmd_stripe_payment_live_public_key');
        }else{
            $data['publishable_key'] = $this->config->get('payment_tmd_stripe_payment_test_public_key');
        }

       

        return $this->load->view('extension/tmdstripepayment/payment/tmd_stripe_payment', $data);
    }


    public function stripecheckout(string &$route, array &$args, mixed&$output): void {
        $modulestatus=$this->config->get('payment_tmd_stripe_payment_status');
        if(!empty($modulestatus)){
         $template_buffer =$this->getTemplateBuffer($route,$output); 
         $find='{{ footer }}';
         $replace='<script type="text/javascript" src="https://js.stripe.com/v3/"></script>'.'{{ footer }}';
         $output = str_replace($find, $replace, $template_buffer); 

          

        }
    }

   protected function getTemplateBuffer($route, $event_template_buffer ) {
        // if there already is a modified template from view/*/before events use that one
        if ($event_template_buffer) {
            return $event_template_buffer;
        }

        // load the template file (possibly modified by ocmod and vqmod) into a string buffer
        
            if ($this->config->get('config_theme') == 'default') {
                $theme = $this->config->get('theme_default_directory');
            } else {
                $theme = $this->config->get('config_theme');
            }
              $dir_template = DIR_TEMPLATE ;
            
        
     $template_file = $dir_template . $route . '.twig';
        if (file_exists( $template_file ) && is_file( $template_file )) {
            
            return file_get_contents( $template_file );
        }
        
        $dir_template = DIR_TEMPLATE . 'default/template/';
        $template_file = $dir_template . $route . '.twig';
        if (file_exists( $template_file ) && is_file( $template_file )) {
            
            return file_get_contents( $template_file );
        }
        trigger_error("Cannot find template file for route '$route'");
        exit;
    }


    public function send() {
        $json=[];
        $metaData=[];
        if(!empty($this->request->get['stripeToken'])) {
            try {
                $transaction_mode= $this->config->get('payment_tmd_stripe_payment_transaction_mode');
                if($transaction_mode=='live'){
                    $apikey= $this->config->get('payment_tmd_stripe_payment_live_secret_key');
                }else{
                    $apikey = $this->config->get('payment_tmd_stripe_payment_test_secret_key');
                }

                $this->load->model('checkout/order');
                $order_id   = !empty($this->session->data['order_id'])?$this->session->data['order_id']:0;
                $order_info = $this->model_checkout_order->getOrder($order_id);

                $description=$this->config->get('payment_tmd_stripe_payment_custom_description');
                if(empty($description)){
                    $description='{order_id}';
                }

                $find = array('{order_id}','{fullname}','{total}','{currency}','{store}');
                $replace = array(
                    'order_id' => $order_info['order_id'],
                    'store'    => $order_info['store_name'],
                    'fullname' => $order_info['payment_firstname'].' '.$order_info['payment_lastname'],
                    'total'    => round($order_info['total'],2),
                    'currency' => $order_info['currency_code']
                );

                $description = str_replace(array("\r\n", "\r", "\n"), '', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '', trim(str_replace($find, $replace, $description))));

                if (!empty($order_info)) {
                    $email            = $order_info["email"];
                    $name             = $order_info["firstname"].' '.$order_info["lastname"];
                    $address          = $order_info["shipping_address_1"];
                    $country          = $order_info["shipping_country"];
                    $postalCode       = $order_info["shipping_postcode"];
                    $notes            = $description;
                    $currency         = $order_info["currency_code"];
                    $orderReferenceId = $this->getToken();
                    $amount           = round($order_info["total"], 2);
                    $orderStatus      = "Pending";
                    $paymentType      = $order_info["payment_method"];
                    $customerDetailsArray = array(  
                        "email"      => $email,
                        "name"       => $name,
                        "address"    => $address,
                        "country"    => $country,
                        "postalCode" => $postalCode
                    );
                    $metaData = array(
                        "email"      => $email,
                        "order_id"   => $orderReferenceId
                    );
                }
                
                \Stripe\Stripe::setApiKey($apikey);

                $paymentIntent = \Stripe\PaymentIntent::create([
                    'description' => $notes,
                    'shipping'    => [
                        'name'    => $customerDetailsArray["name"],
                        'address' => [
                            'line1'       => $customerDetailsArray["address"],
                            'postal_code' => $customerDetailsArray["postalCode"],
                            'country'     => $customerDetailsArray["country"]
                        ]
                    ],
                    'amount'      => $amount * 100,
                    'currency'    => $currency,
                    'metadata'    => $metaData,
                    'payment_method_data' => [
                                    'type' => 'card',
                                    'card' => [
                                        'token' => $this->request->get['stripeToken'], // Replace with the token from frontend
                                    ],
                                ],
                    
                ]);

                  //newcode(15-11-2024)
                 // Check the status after confirmation
                if ($paymentIntent->status === 'succeeded') {
                    $json['status'] = 'success';
                    $json['response'] = $output['response'];
                    $orderHash = !empty($output['response']['orderHash'])?$output['response']['orderHash']:'';
                    $clientSecret = !empty($output['response']['clientSecret'])?$output['response']['clientSecret']:'';
              
                    $this->model_checkout_order->addHistory($order_id, $this->config->get('payment_tmd_stripe_payment_order_status_id'),'', false , false);
                    $json['redirect'] = $this->url->link('checkout/success');
                } elseif ($paymentIntent->status === 'requires_action') {
                     $json['action']=true;
                     $json['clientSecret']=$paymentIntent->client_secret;
                } else {
                    $json['action']=true;
                     $json['clientSecret']=$paymentIntent->client_secret;
                } 

                //newcode(15-11-2024)
              
            } catch (\Error $e) {
                $output = array(
                    "status" => "error",
                    "response" => $e->getMessage()
                );
            }
            
            
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getToken() {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < 17; $i ++) {
            $token .= $codeAlphabet[mt_rand(0, $max)];
        }
        return $token;
    }

      public function callback() {
        $json=array();
        if(!empty($this->request->post['status']))
        {   
            $this->load->model('checkout/order');
             $order_id   = !empty($this->session->data['order_id'])?$this->session->data['order_id']:0;
             $order_info = $this->model_checkout_order->getOrder($order_id);
            $amount=$this->currency->format($order_info["total"],$order_info['currency_code'],false,false)*100; 

            if($this->request->post['status']=='succeeded' && $amount==$this->request->post['amount'])
            {   

                    $this->model_checkout_order->addHistory($order_id, $this->config->get('payment_tmd_stripe_payment_order_status_id'),'', false , false);
                    $json['redirect'] = $this->url->link('checkout/success');
            }else{
                 $json['warning'] = 'Amount missmatch';
            }
        }
        else{
             $json['warning'] = $this->request->post['status'];
        }
         $this->response->addHeader('Content-Type: application/json');
         $this->response->setOutput(json_encode($json));
    }

}