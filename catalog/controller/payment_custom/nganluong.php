<?php 
class ControllerPaymentCustomNganluong extends Controller {

	private $error = array();



	public function index() {

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/edit', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('payment_custom/nganluong');
		$this->document->addStyle('catalog/view/theme/beta/css/post_project.css?'.rand());
		$this->document->addStyle('catalog/view/theme/beta/stylesheet/nganluong.css?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/angular/payment.js?'.rand());
		$this->document->addStyle('catalog/view/theme/beta/css/icon-font.css');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['menu_left_profile'] = $this->load->controller('account/menu_left_profile');
		$data['menu_right_profile'] = $this->load->controller('account/menu_right_profile');
		$data['list_payment'] = $this->load->controller('payment_custom/list_payment');

		$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
		$data['firstname'] = $customer_info['firstname'];
		$data['telephone'] = $customer_info['telephone'];
		$data['email'] = $customer_info['email'];

		$this->response->setOutput($this->load->view('payment_custom/nganluong', $data));
	}

	public function payment() {

		$data = array();

		$this->load->language('payment_custom/nganluong');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$URL_API 				= 'https://sandbox.nganluong.vn:8088/nl35/checkout.api.nganluong.post.php';
			$RECEIVER 				= 'linhthongtin132@gmail.com';
			$MERCHANT_ID 			= '50416';
			$MERCHANT_PASS 			= '315325cde6b17a54091c5bef56fea153';

			include(DIR_SYSTEM.'library/nganluong/NL_Checkoutv3.php');	
			$nlcheckout= new NL_CheckOutV3($MERCHANT_ID, $MERCHANT_PASS, $RECEIVER, $URL_API);
			$total_amount= str_replace(',', '', $this->request->post['total_amount']);
		 
			$array_items[0]= array(
				 'item_name1' 		=> 'Nạp Tiền Vào Ví',
				 'item_quantity1' 	=> 1,
				 'item_amount1' 	=> $total_amount,
			);
			$payment_method 		= $this->request->post['option_payment'];
			$bank_code 				= $this->request->post['bankcode'];
			$order_code 			="macode_".time();
			$payment_type 			='';
			$discount_amount 		=0;
			$order_description		='';
			$tax_amount				=0;
			$fee_shipping			=0;
			$return_url 			= $this->url->link('payment_custom/nganluong/payment_success', '', true);
			$cancel_url 			= $this->url->link('payment_custom/nganluong/checkoutv3', 'orderid='.$order_code, true);
			$fullname 				= $this->request->post['fullname'];
			$email 					= $this->request->post['email'];
			$telephone 				= $this->request->post['telephone'];
			$address 				='';

			switch ($payment_method) {
				case 'VISA':
					$nl_result = $nlcheckout->VisaCheckout(
						$order_code,
						$total_amount,
						$payment_type,
						$order_description,
						$tax_amount,
						$fee_shipping,
						$discount_amount,
						$return_url,
						$cancel_url,
						$fullname,
						$email,
						$telephone,
						$address,
						$array_items,
						$bank_code
					);  
					break;
				case 'NL':
					$nl_result= $nlcheckout->NLCheckout(
						$order_code,
						$total_amount,
						$payment_type,
						$order_description,
						$tax_amount,
						$fee_shipping,
						$discount_amount,
						$return_url,
						$cancel_url,
						$fullname,
						$email,
						$telephone,
						$address,
						$array_items
					);
					break;
				case 'ATM_ONLINE':
					$nl_result= $nlcheckout->BankCheckout($order_code,
						$total_amount,
						$bank_code,
						$payment_type,
						$order_description,
						$tax_amount,
						$fee_shipping,
						$discount_amount,
						$return_url,
						$cancel_url,
						$fullname,
						$email,
						$telephone,
						$address,
						$array_items
					);
					break;
				case 'NH_OFFLINE':
					$nl_result= $nlcheckout->officeBankCheckout(
						$order_code,
						 $total_amount,
						 $bank_code,
						 $payment_type,
						 $order_description,
						 $tax_amount,
						 $fee_shipping,
						 $discount_amount,
						 $return_url,
						 $cancel_url,
						 $fullname,
						 $email,
						 $telephone,
						 $address,
						 $array_items
					);
					break;

				case 'ATM_OFFLINE':
					$nl_result= $nlcheckout->BankOfflineCheckout(
						$order_code,
						 $total_amount,
						 $bank_code,
						 $payment_type,
						 $order_description,
						 $tax_amount,
						 $fee_shipping,
						 $discount_amount,
						 $return_url,
						 $cancel_url,
						 $fullname,
						 $email,
						 $telephone,
						 $address,
						 $array_items
					);	
					break;

				case 'IB_ONLINE':
					$nl_result= $nlcheckout->IBCheckout(
						 $order_code,
						 $total_amount,
						 $bank_code,
						 $payment_type,
						 $order_description,
						 $tax_amount,
						 $fee_shipping,
						 $discount_amount,
						 $return_url,
						 $cancel_url,
						 $fullname,
						 $email,
						 $telephone,
						 $address,
						 $array_items
					);
					break;
				case 'CREDIT_CARD_PREPAID':
					$nl_result = $nlcheckout->PrepaidVisaCheckout(
						$order_code,
						 $total_amount,
						 $payment_type,
						 $order_description,
						 $tax_amount,
						 $fee_shipping,
						 $discount_amount,
						 $return_url,
						 $cancel_url,
						 $fullname,
						 $email,
						 $telephone,
						 $address,
						 $array_items,
						 $bank_code
					);
				default:
					break;
			}

			if($nl_result->error_code == '00') {
				$data['success'] 	= true;
				$data['message'] 	= $this->language->get('text_success');
				$data['url_redirect'] 	= (string)$nl_result->checkout_url;;
			} else {
				$data['success'] 	= false;
				$data['message'] 	= $nl_result->error_message;
			}
			
			
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}

		if (isset($this->error['fullname'])) {
			$data['error_fullname'] = $this->error['fullname'];
		} else {
			$data['error_fullname'] = '';
		}

		if (isset($this->error['option_payment'])) {
			$data['error_option_payment'] = $this->error['option_payment'];
		} else {
			$data['error_option_payment'] = '';
		}

		if (isset($this->error['total_amount'])) {
			$data['error_total_amount'] = $this->error['total_amount'];
		} else {
			$data['error_total_amount'] = '';
		}

		if (isset($this->error['bankcode'])) {
			$data['error_bankcode'] = $this->error['bankcode'];
		} else {
			$data['error_bankcode'] = '';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}

	protected function validateForm() {

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['telephone']) < 1) || (utf8_strlen(trim($this->request->post['telephone'])) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		if ((utf8_strlen($this->request->post['bankcode']) < 1) || (utf8_strlen(trim($this->request->post['bankcode'])) > 32)) {
			$this->error['bankcode'] = $this->language->get('error_bankcode');
		}

		if (($this->request->post['total_amount']) <= 0) {
			$this->error['total_amount'] = $this->language->get('error_total_amount');
		}

		if ((utf8_strlen($this->request->post['fullname']) <= 0) || (utf8_strlen(trim($this->request->post['fullname'])) > 64)) {
			$this->error['fullname'] = $this->language->get('error_fullname');
		}

		if (utf8_strlen($this->request->post['option_payment']) <= 0) {
			$this->error['option_payment'] = $this->language->get('error_option_payment');
		}


		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}


		return !$this->error;
	}

	public function payment_success() {

		$this->load->language('payment_custom/nganluong');

		$this->document->addStyle('catalog/view/theme/beta/stylesheet/style_v30.css?'.rand());

		if(isset($this->request->get['error_code'])) {
			$data['message'] = $this->getResutl($this->request->get['error_code']);
		} else {
			$data['message'] = '';
		}

		if(isset($this->request->get['order_code'])) {
			$data['order_code'] = $this->request->get['order_code'];
		} else {
			$data['order_code'] = '';
		}

		if(isset($this->request->get['token'])) {
			$data['token'] = $this->request->get['token'];
		} else {
			$data['token'] = '';
		}
		
		$data['config_email'] = $this->config->get('config_email');
		$data['config_telephone'] = $this->config->get('config_telephone');
		$data['config_name'] = $this->config->get('config_name');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		// $log = new Log('payment_success.txt');
		// $log->write('--------GET-----------');
		// $log = new Log('payment_success.txt');
		// $log->write($this->request->get);


		// $log = new Log('payment_success.txt');
		// $log->write('--------POST-----------');
		// $log = new Log('payment_success.txt');
		// $log->write($this->request->post);

		$result  = $this->checkStatusOrder($data['token']);

		if(isset($result->total_amount)) {
			$data['total_amount'] = $this->currency->format($result->total_amount, $this->config->get('config_currency'));
		} else {
			$data['total_amount'] = 0;
		}

		$this->response->setOutput($this->load->view('payment_custom/nganluong_payment_success', $data));
	}

	protected function getResutl($code) {
        switch ($code) {
            case "00" : $result = $this->language->get('error_00'); break;
            case "99" : $result = $this->language->get('error_99'); break;
            case "02" : $result = $this->language->get('error_02'); break;
            case "03" : $result = $this->language->get('error_03'); break;
            case "04" : $result = $this->language->get('error_04'); break;
            case "05" : $result = $this->language->get('error_05'); break;
            case "06" : $result = $this->language->get('error_06'); break;
            case "07" : $result = $this->language->get('error_07'); break;
            case "08" : $result = $this->language->get('error_08'); break;
            case "09" : $result = $this->language->get('error_109'); break;
            case "10" : $result = $this->language->get('error_110'); break;
            case "11" : $result = $this->language->get('error_11'); break;
            case "12" : $result = $this->language->get('error_112'); break;
            case "29" : $result = $this->language->get('error_129'); break;
            case "80" : $result = $this->language->get('error_180'); break;
            case "81" : $result = $this->language->get('error_181'); break;
            case "110" : $result = $this->language->get('error_110'); break;
            case "111" : $result = $this->language->get('error_111'); break;
            case "113" : $result = $this->language->get('error_113'); break;
            case "114" : $result = $this->language->get('error_114'); break;
            case "115" : $result = $this->language->get('error_115'); break;
            case "118" : $result = $this->language->get('error_118'); break;
            case "119" : $result = $this->language->get('error_119'); break;
            case "120" : $result = $this->language->get('error_120'); break;
            case "121" : $result = $this->language->get('error_121'); break;
            case "122" : $result = $this->language->get('error_122'); break;
            case "123" : $result = $this->language->get('error_123'); break;
            case "124" : $result = $this->language->get('error_124'); break;
            case "125" : $result = $this->language->get('error_125'); break;
            case "126" : $result = $this->language->get('error_126'); break;
            case "127" : $result = $this->language->get('error_127'); break;
            case "error_default" : $result = $this->language->get('error_default'); break;
            default  : $result = $this->language->get('error_default'); 
        }
        return $result;
    }

	public function checkoutv3() {
		$log = new Log('payment_cancel.txt');
		$log->write('--------GET-----------');
		$log = new Log('payment_cancel.txt');
		$log->write($this->request->get);


		$log = new Log('payment_cancel.txt');
		$log->write('--------POST-----------');
		$log = new Log('payment_cancel.txt');
		$log->write($this->request->post);
	}

	protected function checkStatusOrder($token) {
			//$URL_API 				= 'https://www.nganluong.vn/service/order/check'; //live
			// $URL_API 				= 'https://sandbox.nganluong.vn:8088/nl35/service/order/check'; //shanbox
			$URL_API 				= 'https://sandbox.nganluong.vn:8088/nl35/checkout.api.nganluong.post.php'; //shanbox
			$RECEIVER 				= 'linhthongtin132@gmail.com';
			$MERCHANT_ID 			= '50416';
			$MERCHANT_PASS 			= '315325cde6b17a54091c5bef56fea153';

			include(DIR_SYSTEM.'library/nganluong/NL_Checkoutv3.php');	


			$nlcheckout= new NL_CheckOutV3($MERCHANT_ID, $MERCHANT_PASS, $RECEIVER, $URL_API);

			$nl_result = $nlcheckout->GetTransactionDetail($token);

			return $nl_result;
		 
	}
}