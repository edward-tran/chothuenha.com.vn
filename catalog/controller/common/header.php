<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		// Analytics
		$this->load->model('setting/extension');

		$data['analytics'] = array();

		$analytics = $this->model_setting_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get('analytics_' . $analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get('analytics_' . $analytic['code'] . '_status'));
			}
		}

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}

		$data['title'] = $this->document->getTitle();

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts('header');
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');
		$data['rand'] = rand();

		$data['name'] = $this->config->get('config_name');
		$data['link_add_product'] = 'dang-tin.html';

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));

		
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

		

		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['password'] = $this->url->link('account/password', '', true);
		$data['logged'] = $this->customer->isLogged();
		$data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['transaction_payment'] = $this->url->link('account/transaction_payment', '', true);
		$data['transaction_pay'] = $this->url->link('account/transaction_pay', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');
		
		$data['language'] = $this->load->controller('common/language');
		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');

		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');
		$data['menu'] = $this->load->controller('common/menu');


		$data['link_post_product'] = $this->url->link('account/post_project_sale');
		$data['account'] = URL_HOME.'dashboard';
		$data['payment'] = URL_HOME.'nap-tien';

		if (isset($this->request->get['routing'])) {
			if (isset($this->request->get['product_id'])) {
				$class = '-' . $this->request->get['product_id'];
			} elseif (isset($this->request->get['path'])) {
				$class = '-' . $this->request->get['path'];
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$class = '-' . $this->request->get['manufacturer_id'];
			} else {
				$class = '';
			}

			$data['class'] = str_replace('/', '-', $this->request->get['routing']) . $class;
		} else {
			$data['class'] = 'common-home';
		}

		$data['content_menu'] = $this->load->controller('common/content_menu');

		if (!isset($this->request->get['routing'])) {
			$data['redirect'] = $this->url->link('common/home');
			
		} else {
			$url_data = $this->request->get;

			unset($url_data['_routing_']);

			$routing = $url_data['routing'];

			unset($url_data['routing']);

			$url = '';

			if ($url_data) {
				$url = '&' . urldecode(http_build_query($url_data, '', '&'));
			}

			$data['redirect'] = $this->url->link($routing, $url, $this->request->server['HTTPS']);
		}

		if (isset($this->request->get['zone_id'])) {
			$data['zone_id'] = $this->request->get['zone_id'];
		} else {
			$data['zone_id'] = 0;
		}

		if (isset($this->request->get['district_id'])) {
			$data['district_id'] = $this->request->get['district_id'];
		} else {
			$data['district_id'] = 0;
		}

		if (isset($this->request->get['type_id'])) {
			$data['type_id'] = $this->request->get['type_id'];
		} else {
			$data['type_id'] = 0;
		}

		if (isset($this->request->get['price_id'])) {
			$data['price_id'] = $this->request->get['price_id'];
		} else {
			$data['price_id'] = 0;
		}

		if (isset($this->request->get['acreage_id'])) {
			$data['acreage_id'] = $this->request->get['acreage_id'];
		} else {
			$data['acreage_id'] = 0;
		}

		if (isset($this->request->get['bedroom'])) {
			$data['bedroom'] = $this->request->get['bedroom'];
		} else {
			$data['bedroom'] = 0;
		}

		if (isset($this->request->get['bathroom'])) {
			$data['bathroom'] = $this->request->get['bathroom'];
		} else {
			$data['bathroom'] = 0;
		}

		if (isset($this->request->get['direction_house_id'])) {
			$data['direction_house_id'] = $this->request->get['direction_house_id'];
		} else {
			$data['direction_house_id'] = 0;
		}

		
		
		$this->load->model('localisation/zone');
		$data['zones'] = $this->model_localisation_zone->getZones();

		return $this->load->view('common/header', $data);
	}
}
