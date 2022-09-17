<?php
class ControllerAccountAccount extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->document->addStyle('catalog/view/theme/beta/stylesheet/dashboard_customer.css?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/angular/controller/account.js?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/jquery.serializejson.min.js');
		//$this->document->addScript('https://unpkg.com/default-passive-events');


		$this->load->language('account/account');

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

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		} 

		if (isset($this->request->get['header'])) {
			$load_header = false;
		} else {
			$load_header = true;
		}
		
		$data['edit'] = $this->url->link('account/edit', '', true);
		$data['password'] = $this->url->link('account/password', '', true);
		$data['address'] = $this->url->link('account/address', '', true);
		
		$data['credit_cards'] = array();
		
		$files = glob(DIR_APPLICATION . 'controller/extension/credit_card/*.php');
		
		foreach ($files as $file) {
			$code = basename($file, '.php');
			
			if ($this->config->get('payment_' . $code . '_status') && $this->config->get('payment_' . $code . '_card')) {
				$this->load->language('extension/credit_card/' . $code, 'extension');

				$data['credit_cards'][] = array(
					'name' => $this->language->get('extension')->get('heading_title'),
					'href' => $this->url->link('extension/credit_card/' . $code, '', true)
				);
			}
		}
		
		$data['wishlist'] = $this->url->link('account/wishlist');
		$data['order'] = $this->url->link('account/order', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		
		if ($this->config->get('total_reward_status')) {
			$data['reward'] = $this->url->link('account/reward', '', true);
		} else {
			$data['reward'] = '';
		}		
		
		$data['return'] = $this->url->link('account/return', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);
		$data['recurring'] = $this->url->link('account/recurring', '', true);
		
		$this->load->model('account/customer');
		
		$affiliate_info = $this->model_account_customer->getAffiliate($this->customer->getId());
		
		if (!$affiliate_info) {	
			$data['affiliate'] = $this->url->link('account/affiliate/add', '', true);
		} else {
			$data['affiliate'] = $this->url->link('account/affiliate/edit', '', true);
		}
		
		if ($affiliate_info) {		
			$data['tracking'] = $this->url->link('account/tracking', '', true);
		} else {
			$data['tracking'] = '';
		}


		$this->load->model('account/product');
		$this->load->model('tool/image');

		$data['products'] = array();

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = 5;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.product_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = '';
		}

		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = '';
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_model'	  => $filter_model,
			'filter_price'	  => $filter_price,
			'filter_quantity' => $filter_quantity,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $limit
		);

		$product_total = $this->model_account_product->getTotalProducts($filter_data);
		$products = $this->model_account_product->getProducts($filter_data);

		foreach($products as $result) {



			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}


			$view = $this->url->link('product/product', 'product_id=' . $result['product_id']);

			if($result['order_status_id'] == $this->config->get('config_status_prosess_payment_id')) {
				$button_payment = $this->url->link('account/payment_post', 'product_id='.$result['product_id'], true);
			} else {
				$button_payment = false;
			}

			$data['products'][] = array(
				'product_id' => $result['product_id'],
				'image'      => $image,
				'name'       => $result['name'],
				'button_payment'       		=> $button_payment,
				'order_status_name'       	=> $result['order_status_name'],
				'model'       => $result['model'],
				'view'       => $view,
				'date_added'   	=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'from_date'   	=> ($result['from_date'] != '0000-00-00 00:00:00') ? date($this->language->get('date_format_short'), strtotime($result['from_date'])) : '0000-00-00 00:00:00',
				'expiration_date'   => date($this->language->get('date_format_short'), strtotime($result['expiration_date'])),
				'post_type_name'   => $result['post_type_name'],
				'model'      => $result['model'],
				'video'      => $result['video'],
				'sku'      => $result['sku'],
				'price'      => $this->currency->format($result['price'], $this->config->get('config_currency')),
				'status'     => $result['status'] ? 'Đã phê duyệt' : 'Chưa phê duyệt',
				'status_id'     => $result['status']
			);
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = '{page}';
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
		
		

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;

		$data['pagination'] = $pagination->render();

		$data['url_pagination'] = URL_HOME.'dashboard&header=false';

		$data['url_current'] = URL_HOME.'dashboard';

		if($load_header == true) {
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
		} else {
			$data['footer'] = '';
			$data['header'] = '';
		}

		
		$data['column_left'] = $this->load->controller('account/menu_left_profile');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		
		$this->response->setOutput($this->load->view('account/account', $data));
	}

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getNotification() {
		$this->response->setOutput($this->load->view('account/get_notification'));
	}

	public function getProfileCustomer() {
		$this->load->language('common/header');
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['account'] = URL_HOME.'dashboard';
		$data['payment'] = URL_HOME.'nap-tien';
		$data['wishlist'] = $this->url->link('account/wishlist');
		$data['password'] = $this->url->link('account/password', '', true);
		$data['transaction_payment'] = $this->url->link('account/transaction_payment', '', true);
		$data['transaction_pay'] = $this->url->link('account/transaction_pay', '', true);
		$data['order'] = $this->url->link('account/order', '', true);

		$this->load->model('tool/image');
		if ($this->customer->isLogged()) {
			$this->load->model('account/customer');
			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
			$data['full_name'] = $customer_info['full_name'];

			if ($customer_info['image']) {
				$data['avata'] = $this->model_tool_image->resize($customer_info['image'], 74, 74);
			} else {
				$data['avata'] = $this->model_tool_image->resize('data/avatar.jpg', 74, 74);
			}
		} else {
			$data['avata'] = $this->model_tool_image->resize('data/avatar.jpg', 74, 74);
		}

		$this->response->setOutput($this->load->view('account/profile_customer', $data));
	}
}
