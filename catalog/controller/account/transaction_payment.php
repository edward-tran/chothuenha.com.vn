<?php
class ControllerAccountTransactionPayment extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/transaction_payment', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->document->addStyle('catalog/view/theme/beta/stylesheet/dashboard_customer.css?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/angular/controller/account.js?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/jquery.serializejson.min.js');
		//$this->document->addScript('https://unpkg.com/default-passive-events');

		$this->load->language('account/transaction_payment');

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
			'text' => $this->language->get('text_transaction_payment'),
			'href' => $this->url->link('account/transaction_payment', '', true)
		);

		$this->load->model('account/transaction_payment');
		
		$data['column_amount'] = sprintf($this->language->get('column_amount'), $this->config->get('config_currency'));

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['transaction_payments'] = array();

		$filter_data = array(
			'sort'  => 'date_added',
			'order' => 'DESC',
			'start' => ($page - 1) * 10,
			'limit' => 10
		);

		$this->load->model('localisation/order_status');

		$transaction_payment_total = $this->model_account_transaction_payment->getTotalTransactionPayments();
		$results = $this->model_account_transaction_payment->getTransactionPayments($filter_data);
		foreach ($results as $result) {

			$order_status = $this->model_localisation_order_status->getOrderStatus($result['order_status_id']);

			$data['transaction_payments'][] = array(
				'customer_transaction_payment_id' 	=> $result['customer_transaction_payment_id'],
				'code' 								=> $result['code'],
				'payment_method' 					=> $result['payment_method'],
				'price' 							=> $this->currency->format($result['price'], $this->config->get('config_currency')),
				'special' 							=> $this->currency->format($result['special'], $this->config->get('config_currency')),
				'order_status_id' 					=> $order_status ? $order_status['name'] : '',
				'note' 								=> $result['note'],
				'amount'      						=> $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'date_added'  						=> date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $transaction_payment_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/transaction_payment', 'page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_payment_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($transaction_payment_total - 10)) ? $transaction_payment_total : ((($page - 1) * 10) + 10), $transaction_payment_total, ceil($transaction_payment_total / 10));

		$data['total'] = $this->currency->format($this->customer->getBalance(), $this->session->data['currency']);

		$data['continue'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('account/menu_left_profile');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/transaction_payment', $data));
	}
}