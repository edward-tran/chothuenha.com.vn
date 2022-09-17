<?php 
class ControllerPaymentPayment extends Controller {
	public function index() {

		$this->load->language('payment/payment');
		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/payment', '', true)
		);

		$this->document->addStyle('catalog/view/theme/beta/stylesheet/dashboard_customer.css?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/angular/controller/account.js?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/jquery.serializejson.min.js');
		//$this->document->addScript('https://unpkg.com/default-passive-events');
		$this->document->addStyle('catalog/view/theme/beta/stylesheet/payment.css');


		$data['column_left'] = $this->load->controller('account/menu_left_profile');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$data['bank_transfer'] = URL_HOME.'chuyen-khoan.html';
		$data['momo'] = URL_HOME.'momo.html';
		$data['directpay'] = URL_HOME.'tien-mat.html';

		$this->response->setOutput($this->load->view('payment/payment', $data));
	}

	public function bank_transfer() {

		$this->document->addStyle('catalog/view/theme/beta/stylesheet/dashboard_customer.css?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/angular/controller/account.js?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/jquery.serializejson.min.js');
		$this->document->addScript('https://unpkg.com/default-passive-events');
		$this->document->addStyle('catalog/view/theme/beta/stylesheet/payment.css');

		$this->load->language('payment/payment');
		$this->document->setTitle($this->language->get('bank_transfer_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/payment', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('bank_transfer_title'),
			'href' => 'javascript:;'
		);



		$this->document->addStyle('catalog/view/theme/beta/stylesheet/payment.css');


		$data['column_left'] = $this->load->controller('account/menu_left_profile');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('payment/bank_transfer', $data));
	}


	public function momo() {

		$this->document->addStyle('catalog/view/theme/beta/stylesheet/dashboard_customer.css?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/angular/controller/account.js?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/jquery.serializejson.min.js');
		$this->document->addScript('https://unpkg.com/default-passive-events');
		$this->document->addStyle('catalog/view/theme/beta/stylesheet/payment.css');
		
		$this->load->language('payment/payment');
		$this->document->setTitle($this->language->get('momo_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/payment', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('momo_title'),
			'href' => 'javascript:;'
		);



		$this->document->addStyle('catalog/view/theme/beta/stylesheet/payment.css');


		$data['column_left'] = $this->load->controller('account/menu_left_profile');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('payment/momo', $data));
	}

	public function directpay() {

		$this->document->addStyle('catalog/view/theme/beta/stylesheet/dashboard_customer.css?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/angular/controller/account.js?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/jquery.serializejson.min.js');
		$this->document->addScript('https://unpkg.com/default-passive-events');
		$this->document->addStyle('catalog/view/theme/beta/stylesheet/payment.css');
		
		$this->load->language('payment/payment');
		$this->document->setTitle($this->language->get('momo_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/payment', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('directpay_title'),
			'href' => 'javascript:;'
		);



		$this->document->addStyle('catalog/view/theme/beta/stylesheet/payment.css');


		$data['column_left'] = $this->load->controller('account/menu_left_profile');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('payment/directpay', $data));
	}

}