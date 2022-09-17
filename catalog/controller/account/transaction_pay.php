<?php
class ControllerAccountTransactionPay extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/transaction_pay', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->document->addStyle('catalog/view/theme/beta/stylesheet/dashboard_customer.css?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/angular/controller/account.js?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/jquery.serializejson.min.js');
	//	$this->document->addScript('https://unpkg.com/default-passive-events');

		$this->load->language('account/transaction_pay');

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
			'text' => $this->language->get('text_transaction_pay'),
			'href' => $this->url->link('account/transaction_pay', '', true)
		);

		$this->load->model('account/transaction_pay');
		
		$data['column_amount'] = sprintf($this->language->get('column_amount'), $this->config->get('config_currency'));

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['transaction_pays'] = array();

		$filter_data = array(
			'sort'  => 'date_added',
			'order' => 'DESC',
			'start' => ($page - 1) * 10,
			'limit' => 10
		);

		$transaction_pay_total = $this->model_account_transaction_pay->getTotalTransactionPays();
		$results = $this->model_account_transaction_pay->getTransactionPays($filter_data);

		$this->load->model('catalog/product');
		$this->load->model('localisation/post_type');

		

		foreach ($results as $result) {
			$product_info = $this->model_catalog_product->getProduct($result['product_id']);
			$post_type = $this->model_localisation_post_type->getPostType($result['post_type_id']);
			$data['transaction_pays'][] = array(
				'customer_transaction_pay_id' 	=> $result['customer_transaction_pay_id'],
				'code' 								=> $result['code'],
				'product_name' 						=> $product_info ? $product_info['name'] : '',
				'product_id' 						=> $result['product_id'],
				'note' 								=> $result['note'],
				'post_type_name' 								=> $post_type ? $post_type['name'] : '',
				'amount'      						=> $this->currency->format_default($result['amount'], $this->config->get('config_currency')),
				'date_added'  						=> date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}



		$pagination = new Pagination();
		$pagination->total = $transaction_pay_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/transaction_pay', 'page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_pay_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($transaction_pay_total - 10)) ? $transaction_pay_total : ((($page - 1) * 10) + 10), $transaction_pay_total, ceil($transaction_pay_total / 10));

		$data['total'] = $this->currency->format($this->customer->getBalance(), $this->session->data['currency']);

		$data['continue'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('account/menu_left_profile');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/transaction_pay', $data));
	}
}