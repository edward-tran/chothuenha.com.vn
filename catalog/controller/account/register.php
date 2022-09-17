<?php
class ControllerAccountRegister extends Controller {
	private $error = array();

	public function index() {
		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', true));
		}

		$this->document->addStyle('catalog/view/theme/beta/stylesheet/account.css?'.rand());

		$this->load->language('account/register');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$customer_id = $this->model_account_customer->addCustomer($this->request->post);

			// Clear any previous login attempts for unregistered accounts.
			$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

			$this->customer->login($this->request->post['email'], $this->request->post['password']);

			unset($this->session->data['guest']);

			$this->response->redirect($this->url->link('account/success'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_register'),
			'href' => $this->url->link('account/register', '', true)
		);
		$data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', true));

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['firstname'])) {
			$data['error_firstname'] = $this->error['firstname'];
		} else {
			$data['error_firstname'] = '';
		}

		if (isset($this->error['brithday'])) {
			$json['error_brithday'] = $this->error['brithday'];
		} else {
			$json['error_brithday'] = '';
		}

		if (isset($this->error['zone_id'])) {
			$json['error_zone_id'] = $this->error['zone_id'];
		} else {
			$json['error_zone_id'] = '';
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

		if (isset($this->error['custom_field'])) {
			$data['error_custom_field'] = $this->error['custom_field'];
		} else {
			$data['error_custom_field'] = array();
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['confirm'])) {
			$data['error_confirm'] = $this->error['confirm'];
		} else {
			$data['error_confirm'] = '';
		}

		$data['action'] = $this->url->link('account/register', '', true);

		$data['customer_groups'] = array();

		if (is_array($this->config->get('config_customer_group_display'))) {
			$this->load->model('account/customer_group');

			$customer_groups = $this->model_account_customer_group->getCustomerGroups();

			foreach ($customer_groups as $customer_group) {
				if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
					$data['customer_groups'][] = $customer_group;
				}
			}
		}

		if (isset($this->request->post['customer_group_id'])) {
			$data['customer_group_id'] = $this->request->post['customer_group_id'];
		} else {
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}

		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->error['brithday'])) {
			$json['error_brithday'] = $this->error['brithday'];
		} else {
			$json['error_brithday'] = '';
		}

		if (isset($this->error['zone_id'])) {
			$json['error_zone_id'] = $this->error['zone_id'];
		} else {
			$json['error_zone_id'] = '';
		}

		

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} else {
			$data['telephone'] = '';
		}

		// Custom Fields
		$data['custom_fields'] = array();
		
		$this->load->model('account/custom_field');
		
		$custom_fields = $this->model_account_custom_field->getCustomFields();
		
		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'account') {
				$data['custom_fields'][] = $custom_field;
			}
		}
		
		if (isset($this->request->post['custom_field']['account'])) {
			$data['register_custom_field'] = $this->request->post['custom_field']['account'];
		} else {
			$data['register_custom_field'] = array();
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->post['confirm'])) {
			$data['confirm'] = $this->request->post['confirm'];
		} else {
			$data['confirm'] = '';
		}

		if (isset($this->request->post['newsletter'])) {
			$data['newsletter'] = $this->request->post['newsletter'];
		} else {
			$data['newsletter'] = '';
		}

		if (isset($this->request->post['zone_id'])) {
			$data['zone_id'] = (int)$this->request->post['zone_id'];
		}  elseif (!empty($address_info)) {
			$data['zone_id'] = $address_info['zone_id'];
		} else {
			$data['zone_id'] = '';
		}

		if (isset($this->request->post['district_id'])) {
			$data['district_id'] = (int)$this->request->post['district_id'];
		}  elseif (!empty($address_info)) {
			$data['district_id'] = $address_info['district_id'];
		} else {
			$data['district_id'] = '';
		}

		if (isset($this->request->post['ward_id'])) {
			$data['ward_id'] = (int)$this->request->post['ward_id'];
		}  elseif (!empty($address_info)) {
			$data['ward_id'] = $address_info['ward_id'];
		} else {
			$data['ward_id'] = '';
		}

		// Captcha
		if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'), $this->error);
		} else {
			$data['captcha'] = '';
		}

		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_account_id'), true), $information_info['title']);

			} else {
				$data['text_agree'] = '';
			}



		} else {
			$data['text_agree'] = '';
		}


	

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['redirect'] = $this->url->link('account/account', '', true);


		$this->response->setOutput($this->load->view('account/register', $data));
	}

	
	public function registerOtp() {

		$json = array();
		$json['status'] = false;

		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', true));
		}

		$this->load->language('account/register');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			// Send Otp
			$this->load->model('account/customer_otp');

			if($this->request->get['type'] == 'validate') {
				$otp = strtoupper(rand(1000, 9999));
				$send_otp = $this->model_account_customer_otp->sendOtp($this->request->post['telephone'], $otp);
				if($send_otp) {
					$json['status'] = true;
					$json['message'] = 'Mã xác thực đã được gửi đến số điện thoại '.$this->request->post['telephone'].'!Vui lòng kiểm tra tn.';
				} else {
					$json['status'] = false;
					$this->error['warning'] = 'Đã có lỗi xảy ra! Lỗi API gửi SMS từ đơn vị cung cấp Twilio, tài khoản đăng ký đã đang dưới hạn mức, vui lòng nạp tiền vào tài khoản để tiếp tục thưc hiện gửi SMS.';
				}	
			}
			
			if($this->request->get['type'] == 'confirm') {
				$check_input_otp = $this->model_account_customer_otp->getOtpByTelephoneAndOtp($this->request->post['telephone'], $this->request->post['otp']);
				if(!$check_input_otp) {
					$this->error['otp'] = 'Mã OTP không chính xác hoặt không tồn tại trong hệ thống của chúng tôi!</br> Vui lòng chọn quay lại để tạo lại mã OTP một lần nữa';
					$this->error['warning'] = $this->language->get('error_warning');
					$json['status'] = false;
				} else {
					$start = strtotime($check_input_otp['date_added']);
		            $end = strtotime(date('Y-m-d h:i:s'));

		            $min = ($end - $start) / 60;
		            if ($min > 1) {
		                $this->error['otp'] = 'Mã OTP của bạn đã hết hạn, vui lòng quay lại để tạo lại mã OTP mới';
		                $json['status'] = false;
		            } else {
		            	$customer_id = $this->model_account_customer->addCustomer($this->request->post);
						// Clear any previous login attempts for unregistered accounts.
						$this->model_account_customer->deleteLoginAttempts($this->request->post['telephone']);
						$this->customer->login_telephone($this->request->post['telephone'], $this->request->post['password']);
						unset($this->session->data['guest']);
						$this->model_account_customer_otp->deleteOtp($this->request->post['telephone']);
						$json['status'] = true;
						$json['register'] = true;
						$json['message'] = 'Chúc mừng bạn đã đăng ký thành viên thành công.';
		            }
					
				}

			}
			
		}

		if (isset($this->error['warning'])) {
			$json['error_warning'] = $this->error['warning'];
		} else {
			$json['error_warning'] = '';
		}

		if (isset($this->error['full_name'])) {
			$json['error_full_name'] = $this->error['full_name'];
		} else {
			$json['error_full_name'] = '';
		}

		if (isset($this->error['telephone'])) {
			$json['error_telephone'] = $this->error['telephone'];
		} else {
			$json['error_telephone'] = '';
		}

		if (isset($this->error['password'])) {
			$json['error_password'] = $this->error['password'];
		} else {
			$json['error_password'] = '';
		}

		if (isset($this->error['confirm'])) {
			$json['error_confirm'] = $this->error['confirm'];
		} else {
			$json['error_confirm'] = '';
		}

		if (isset($this->error['otp'])) {
			$json['error_otp'] = $this->error['otp'];
		} else {
			$json['error_otp'] = '';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	
	}

	private function validate() {

		if ((utf8_strlen(trim($this->request->post['full_name'])) < 1) || (utf8_strlen(trim($this->request->post['full_name'])) > 32)) {
			$this->error['full_name'] = $this->language->get('error_full_name');
		}

		if ((strlen(utf8_decode($this->request->post['telephone'])) < 9) || (strlen(utf8_decode($this->request->post['telephone'])) > 32) || preg_match('/[^\d]/is', $this->request->post['telephone'])) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		if ((utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if ($this->request->post['confirm'] != $this->request->post['password']) {
			$this->error['confirm'] = $this->language->get('error_confirm');
		}

		if ($this->model_account_customer->getTotalCustomersByTelephone($this->request->post['telephone'])) {
			$this->error['telephone'] = 'Số điện thoại của bạn đã tồn tại trong hệ thống! Vui lòng chọn đăng nhập để tiếp tục.';
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		
		return !$this->error;
	}

	public function showFormRegister() {
		$data = array();
		$this->response->setOutput($this->load->view('account/form_register', $data));
	}
}