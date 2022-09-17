<?php
class ControllerAccountEdit extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/edit', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}


		$this->load->language('account/edit');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
		$this->document->addStyle('catalog/view/theme/beta/css/post_project.css?'.rand());
		$this->document->addStyle('catalog/view/theme/beta/css/icon-font.css');
		$this->document->addStyle('admin/view/stylesheet/select2/select2.min.css');
		$this->document->addStyle('admin/view/stylesheet/select2/select2-bootstrap.css');
		$this->document->addScript('admin/view/javascript/select2/select2.full.js');

		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_account_customer->editCustomer($this->customer->getId(), $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('account/account', '', true));
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
			'text' => $this->language->get('text_edit'),
			'href' => $this->url->link('account/edit', '', true)
		);

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

		if (isset($this->error['lastname'])) {
			$data['error_lastname'] = $this->error['lastname'];
		} else {
			$data['error_lastname'] = '';
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

		$data['action'] = $this->url->link('account/edit', '', true);

		if ($this->request->server['REQUEST_METHOD'] != 'POST') {
			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
		}

		

		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($customer_info)) {
			$data['firstname'] = $customer_info['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} elseif (!empty($customer_info)) {
			$data['lastname'] = $customer_info['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($customer_info)) {
			$data['email'] = $customer_info['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($customer_info)) {
			$data['telephone'] = $customer_info['telephone'];
		} else {
			$data['telephone'] = '';
		}

		if (isset($this->request->post['brithday'])) {
			$data['brithday'] = $this->request->post['brithday'];
		} elseif (!empty($customer_info)) {
			$data['brithday'] = $customer_info['brithday'];
		} else {
			$data['brithday'] = '';
		}

		if (isset($this->request->post['sex'])) {
			$data['sex'] = $this->request->post['sex'];
		} elseif (!empty($customer_info)) {
			$data['sex'] = $customer_info['sex'];
		} else {
			$data['sex'] = '';
		}

		if (isset($this->request->post['facebook'])) {
			$data['facebook'] = $this->request->post['facebook'];
		} elseif (!empty($customer_info)) {
			$data['facebook'] = $customer_info['facebook'];
		} else {
			$data['facebook'] = '';
		}

		if (isset($this->request->post['skype'])) {
			$data['skype'] = $this->request->post['skype'];
		} elseif (!empty($customer_info)) {
			$data['skype'] = $customer_info['skype'];
		} else {
			$data['skype'] = '';
		}

		if (isset($this->request->post['zalo'])) {
			$data['zalo'] = $this->request->post['zalo'];
		} elseif (!empty($customer_info)) {
			$data['zalo'] = $customer_info['zalo'];
		} else {
			$data['zalo'] = '';
		}

		if (isset($this->request->post['viber'])) {
			$data['viber'] = $this->request->post['viber'];
		} elseif (!empty($customer_info)) {
			$data['viber'] = $customer_info['viber'];
		} else {
			$data['viber'] = '';
		}

		$this->load->language('account/address');
		$this->load->model('account/address');

		$results = $this->model_account_address->getAddresses();

		$this->load->model('localisation/district');
		$this->load->model('localisation/ward');

		foreach ($results as $result) {
			$district = $this->model_localisation_district->getZoneDistrict($result['city']);
			$ward = $this->model_localisation_ward->getWard($result['ward']);
			
			$data['addresses'] = array(
				'zone'    				=>  $result['zone'],
				'postcode'    			=>  $result['postcode'],
				'zone_id'    			=>  $result['zone_id'],
				'district_id'    		=> $result['city'],
				'district'    			=> $district ? $district['name'] : '',
				'ward_id'    			=> $result['ward'],
				'ward'    				=> $ward ? $ward['name'] : '',
				'address_1'    			=> $result['address_1'],
				'address_2'    			=> $result['address_2'],
				
			);
		}



		// Custom Fields
		$data['custom_fields'] = array();
		
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'account') {
				$data['custom_fields'][] = $custom_field;
			}
		}

		if (isset($this->request->post['custom_field']['account'])) {
			$data['account_custom_field'] = $this->request->post['custom_field']['account'];
		} elseif (isset($customer_info)) {
			$data['account_custom_field'] = json_decode($customer_info['custom_field'], true);
		} else {
			$data['account_custom_field'] = array();
		}

		$data['back'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$data['menu_left_profile'] = $this->load->controller('account/menu_left_profile');
		$data['menu_right_profile'] = $this->load->controller('account/menu_right_profile');
		$data['config_country_id'] = $this->config->get('config_country_id');

		$this->response->setOutput($this->load->view('account/edit', $data));
	}

	protected function validate() {
		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['brithday'])) < 1) || (utf8_strlen(trim($this->request->post['brithday'])) > 32)) {
			$this->error['brithday'] = $this->language->get('error_brithday');
		}

		if ((utf8_strlen(trim($this->request->post['zone_id'])) <= 0) || (utf8_strlen(trim($this->request->post['zone_id'])) > 32)) {
			$this->error['zone_id'] = $this->language->get('error_zone_id');
		}

		if ((utf8_strlen(trim($this->request->post['district_id'])) <= 0) || (utf8_strlen(trim($this->request->post['district_id'])) > 32)) {
			$this->error['district_id'] = $this->language->get('error_district_id');
		}

		if ((utf8_strlen(trim($this->request->post['ward_id'])) <= 0) || (utf8_strlen(trim($this->request->post['ward_id'])) > 32)) {
			$this->error['ward_id'] = $this->language->get('error_ward_id');
		}

		if ((utf8_strlen(trim($this->request->post['telephone'])) < 1) || (utf8_strlen(trim($this->request->post['telephone'])) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen(trim($this->request->post['postcode'])) < 1) || (utf8_strlen(trim($this->request->post['postcode'])) > 32)) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}

		if (($this->customer->getEmail() != $this->request->post['email']) && $this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_exists');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		
		return !$this->error;
	}

	public function updateCustomer() {

		$this->load->language('account/edit');

		$json = array();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('account/customer');
			$this->model_account_customer->editCustomer($this->customer->getId(), $this->request->post);
			$data['message'] = $this->language->get('text_success');
		}

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
			$data['error_brithday'] = $this->error['brithday'];
		} else {
			$data['error_brithday'] = '';
		}

		if (isset($this->error['zone_id'])) {
			$data['error_zone_id'] = $this->error['zone_id'];
		} else {
			$data['error_zone_id'] = '';
		}

		if (isset($this->error['district_id'])) {
			$data['error_district_id'] = $this->error['district_id'];
		} else {
			$data['error_district_id'] = '';
		}

		if (isset($this->error['ward_id'])) {
			$data['error_ward_id'] = $this->error['ward_id'];
		} else {
			$data['error_ward_id'] = '';
		}

		if (isset($this->error['postcode'])) {
			$data['error_postcode'] = $this->error['postcode'];
		} else {
			$data['error_postcode'] = '';
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

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
}