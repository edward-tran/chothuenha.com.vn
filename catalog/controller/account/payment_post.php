<?php 


class ControllerAccountPaymentPost extends Controller {

	private $error = array();

	public function index() {

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->document->addStyle('catalog/view/theme/beta/stylesheet/payment_post.css?'.rand());

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right_product');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if(isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('account/customer');

		$this->load->model('account/product');
		$data['product_info'] = $this->model_account_product->getProduct($product_id);

		$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());


		$this->load->model('localisation/post_type');
		$post_types = $this->model_localisation_post_type->getPostTypes();

		$data['post_types'] = array();

		foreach($post_types as $post_type) {
			if($post_type['post_type_id'] == $this->config->get('config_post_type_free_id')) {
				$name = $post_type['name'].'('.'Bạn còn '.$customer_info['number_free_post'].' tin miễn phí'.')';
			} else {
				$name = $post_type['name'];
			}
			$data['post_types'][] = array(
				'post_type_id' => $post_type['post_type_id'],
				'name' => $name,
				'sort_order' => $post_type['sort_order'],
				'price' => $this->currency->format_default($post_type['price'], $this->config->get('config_currency')),
			);
		} 

		$data['post_type_free_id'] = $this->config->get('config_post_type_free_id');


		$data['back'] = URL_HOME.'dashboard';

		$data['post_type'] = 'Tin miễn phí';
		$data['price'] = '0/ngày';
		$data['days'] = '30 ngày';
		$data['times'] = 'Đăng theo ngày';
		$data['total_payment'] = '0đ';

		$this->response->setOutput($this->load->view('account/payment_post', $data));

	}

	public function getInfoService() {

		$json = array();

		if (($this->request->server['REQUEST_METHOD'] == 'GET')) {
			if(!empty($this->request->get['post_type_id'])) {
				$post_type_id = $this->request->get['post_type_id'];
			} else {
				$post_type_id = 0;
			}

			if(!empty($this->request->get['package_type_time'])) {
				$package_type_time = $this->request->get['package_type_time'];
			} else {
				$package_type_time = '';
			}

			if(!empty($this->request->get['total_day'])) {
				$total_day = $this->request->get['total_day'];
			} else {
				$total_day = '';
			}

			if(!empty($this->request->get['total_week'])) {
				$total_week = $this->request->get['total_week'];
			} else {
				$total_week = '';
			}

			if(!empty($this->request->get['total_month'])) {
				$total_month = $this->request->get['total_month'];
			} else {
				$total_month = '';
			}

			$this->load->model('localisation/post_type');

			$post_type = $this->model_localisation_post_type->getPostType($post_type_id);

			if($post_type_id == $this->config->get('config_post_type_free_id')) {
				$total_days = 30; 
			} else {

				if($package_type_time == 'day') {
					$total_days = $total_day; 
				} elseif($package_type_time == 'week') {
					$total_days = $total_week*7; 
				} elseif($package_type_time == 'month') {
					$total_days = $total_month*30; 
				}

				
			}

			$json['post_type_name'] 	= $post_type['name'];
			$json['post_type_times'] 	= 'Đăng theo ngày';
			$json['post_type_price'] 	= $this->currency->format_default($post_type['price'], $this->config->get('config_currency'));
			$json['post_type_days'] 	= $total_days.'/ngày';
			$json['post_type_deadline'] 		= date('d/m/Y', strtotime(date('Y-m-d'). ' + '.$total_days.' days'));

			$post_type_total_payment = $post_type['price']*$total_days;

			$json['post_type_total_payment'] 		= $this->currency->format_default($post_type_total_payment, $this->config->get('config_currency'));

			$this->load->model('account/wallet');
			$amount = $this->model_account_wallet->getAmountByCustomerId();

			if((float)$post_type_total_payment > (float)$amount) {
				$json['note-outofmoney'] = 'Số tiền trong tài khoản của bạn không đủ để thực hiện thanh toán, vui lòng <a href="">nạp thêm</a> hoặc chọn phương thức khác bên dưới';
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	public function complete_post() {

		$json = array();
		$json['status'] = false;

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {


			$json['status'] = true;
		}

		if (isset($this->error['post_type_id'])) {
			$json['error_post_type_id'] = $this->error['post_type_id'];
		} else {
			$json['error_post_type_id'] = '';
		}

		if (isset($this->error['package_type_time'])) {
			$json['error_package_type_time'] = $this->error['package_type_time'];
		} else {
			$json['error_package_type_time'] = '';
		}

		if (isset($this->error['total_day'])) {
			$json['error_total_day'] = $this->error['total_day'];
		} else {
			$json['error_total_day'] = '';
		}

		if (isset($this->error['total_week'])) {
			$json['error_total_week'] = $this->error['total_week'];
		} else {
			$json['error_total_week'] = '';
		}

		if (isset($this->error['total_month'])) {
			$json['error_total_month'] = $this->error['total_month'];
		} else {
			$json['error_total_month'] = '';
		}

		if (isset($this->error['post_type_id'])) {
			$json['error_post_type_id'] = $this->error['post_type_id'];
		} else {
			$json['error_post_type_id'] = '';
		}

		if (isset($this->error['total_payment'])) {
			$json['error_total_payment'] = $this->error['total_payment'];
		} else {
			$json['error_total_payment'] = '';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validateForm() {

		if (utf8_strlen($this->request->post['post_type_id']) < 1) {
			$this->error['post_type_id'] = $this->language->get('error_post_type_id');
		}

		if (utf8_strlen($this->request->post['package_type_time']) < 1) {
			$this->error['package_type_time'] = $this->language->get('error_package_type_time');
		}

		if (utf8_strlen($this->request->post['total_day']) < 1) {
			$this->error['total_day'] = $this->language->get('error_total_day');
		}

		if (utf8_strlen($this->request->post['total_week']) < 1) {
			$this->error['total_week'] = $this->language->get('error_total_week');
		}


		if (utf8_strlen($this->request->post['total_month']) < 1) {
			$this->error['total_month'] = $this->language->get('error_total_month');
		}

		if (utf8_strlen($this->request->post['post_type_id']) < 1) {
			$this->error['post_type_id'] = $this->language->get('error_post_type_id');
		}

		$this->load->model('localisation/post_type');
		$post_type = $this->model_localisation_post_type->getPostType($this->request->post['post_type_id']);

		$this->load->model('account/wallet');
		$amount = $this->model_account_wallet->getAmountByCustomerId();

		if($this->request->post['post_type_id'] == $this->config->get('config_post_type_free_id')) {
				$total_days = 30; 
		} else {
			if($this->request->post['package_type_time'] == 'day') {
				$total_days = $this->request->post['total_day']; 
			} elseif($this->request->post['package_type_time'] == 'week') {
				$total_days = $this->request->post['total_week']*7; 
			} elseif($this->request->post['package_type_time'] == 'month') {
				$total_days = $this->request->post['total_month']*30; 
			}
		}

		$post_type_total_payment = $post_type['price']*$total_days;

		if((float)$post_type_total_payment > (float)$amount) {
			$this->error['total_payment'] = 'Số tiền trong tài khoản của bạn không đủ để thực hiện thanh toán, vui lòng <a href="">nạp thêm</a> hoặc chọn phương thức khác bên dưới';
		}


		return !$this->error;
	}

}