<?php
class ControllerAccountWallet extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/wallet', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->document->addStyle('catalog/view/theme/beta/stylesheet/dashboard_customer.css?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/angular/controller/account.js?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/jquery.serializejson.min.js');
		//$this->document->addScript('https://unpkg.com/default-passive-events');

		$this->load->language('account/wallet');

		$this->load->model('account/wallet');

		$amount = $this->model_account_wallet->getAmountByCustomerId();

		$data['amount'] = $this->currency->format_default($amount, $this->config->get('config_currency'));



		$this->document->setTitle($this->language->get('heading_title'));

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
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/wallet')
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['continue'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('account/menu_left_profile');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/wallet', $data));
	}
}