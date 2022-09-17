<?php
class ControllerAccountReport extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/report', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->document->addStyle('catalog/view/theme/beta/stylesheet/dashboard_customer.css?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/angular/controller/account.js?'.rand());
		$this->document->addScript('catalog/view/theme/beta/js/jquery.serializejson.min.js');
		//$this->document->addScript('https://unpkg.com/default-passive-events');

		$this->load->language('account/report');

		$this->load->model('catalog/report');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (isset($this->request->get['remove'])) {
			// Remove Report
			$this->model_catalog_report->deleteReport($this->request->get['remove']);

			$this->session->data['success'] = $this->language->get('text_remove');

			$this->response->redirect($this->url->link('account/report'));
		}

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
			'href' => $this->url->link('account/report')
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['products'] = array();

		$results = $this->model_catalog_report->getReports();

		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		foreach ($results as $result) {

			$product_info = $this->model_catalog_product->getProduct($result['product_id']);

			if ($product_info) {

				if (is_file(DIR_IMAGE . $product_info['image'])) {
					$image = $this->model_tool_image->resize($product_info['image'], 40, 40);
				} else {
					$image = $this->model_tool_image->resize('no_image.png', 40, 40);
				}

				$info = json_decode($result['info'], true);
				if($info) {
					$info = implode(", ",$info);
				} else {
					$info = '';
				}

				$data['products'][] = array(
					'product_id'  => $product_info['product_id'],
					'model'  => $product_info['model'],
					'date_added'  => $product_info['date_added'],
					'info'  => $info,
					'image'       => $image,
					'name'        => $product_info['name'],
					);
			} else {
				$this->model_catalog_report->deleteReport($result['product_id']);
			}
		}

		$data['continue'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('account/menu_left_profile');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/report', $data));
	}

	public function add() {
		$this->load->language('account/report');

		$json = array();

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			if ($this->customer->isLogged()) {
				// Edit customers cart
				$this->load->model('catalog/report');

				$this->model_catalog_report->addReport($this->request->post['product_id']);

				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('account/report'));

				$json['total'] = sprintf($this->language->get('text_report'), $this->model_catalog_report->getTotalReport());
				$json['status'] = true;
			} else {
				if (!isset($this->session->data['report'])) {
					$this->session->data['report'] = array();
				}

				$this->session->data['report'][] = $this->request->post['product_id'];

				$this->session->data['report'] = array_unique($this->session->data['report']);

				$json['success'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('account/report'));
				$json['status'] = false;

				$json['total'] = sprintf($this->language->get('text_report'), (isset($this->session->data['report']) ? count($this->session->data['report']) : 0));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
