<?php
class ControllerExtensionModuleLatest extends Controller {
	public function index($setting = array()) {

		if(!$setting) {
			$setting = array(
				'name' => 'Nhà cho thuê mới nhất',
			    'limit' => 10,
			    'width' => 200,
			    'height' => 200,
			    'status' => 1
			);
		}

		$layout = 'module';

		if(isset($this->request->get['header'])) {
			$layout  = $this->request->get['header'];
		}

		if(isset($this->request->get['layout'])) {
			$layout  = $this->request->get['layout'];
		}

		$this->load->language('extension/module/latest');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['type_id'])) {
			$type_id = $this->request->get['type_id'];
		} else {
			$type_id = '';
		}

		if (isset($this->request->get['price_from'])) {
			$price_from = $this->request->get['price_from'];
		} else {
			$price_from = '';
		}

		if (isset($this->request->get['price_to'])) {
			$price_to = $this->request->get['price_to'];
		} else {
			$price_to = '';
		}

		if (isset($this->request->get['acreage_from'])) {
			$acreage_from = $this->request->get['acreage_from'];
		} else {
			$acreage_from = '';
		}

		if (isset($this->request->get['acreage_to'])) {
			$acreage_to = $this->request->get['acreage_to'];
		} else {
			$acreage_to = '';
		}

		if (isset($this->request->get['bedroom'])) {
			$bedroom = $this->request->get['bedroom'];
		} else {
			$bedroom = '';
		}

		if (isset($this->request->get['bathroom'])) {
			$bathroom = $this->request->get['bathroom'];
		} else {
			$bathroom = '';
		}

		if (isset($this->request->get['direction_house_id'])) {
			$direction_house_id = $this->request->get['direction_house_id'];
		} else {
			$direction_house_id = '';
		}


		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.date_added';
		}

		$limit = $setting['limit'];

		$filter_data = array(
			'sort'  		=> 'p.date_added',
			'filter'  		=> $filter,
			'type_id'  		=> $type_id,
			'price_from'  	=> $price_from,
			'price_to'  	=> $price_to,
			'acreage_from'  => $acreage_from,
			'acreage_to'  	=> $acreage_to,
			'bedroom'  		=> $bedroom,
			'bathroom'  	=> $bathroom,
			'direction_house_id'  	=> $direction_house_id,
			'order' 		=> $order,
			'start'     	=> ($page - 1) * $limit,
			'limit'     	=> $limit
		);

		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

		$results = $this->model_catalog_product->getProducts($filter_data);


		
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
			}

			$price = $this->currency->format($result['price'], $this->config->get('config_currency'));

			

			if ($this->config->get('config_tax')) {
				$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
			} else {
				$tax = false;
			}

			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}

			if (is_file(DIR_IMAGE . $result['customer_image'])) {
				$customer_image = $this->model_tool_image->resize($result['customer_image'], 40, 40);
			} else {
				$customer_image = $this->model_tool_image->resize('data/avatar.jpg', 40, 40);
			}

			

			$data['products'][] = array(
				'product_id'  			=> $result['product_id'],
				'customer_full_name'  	=> $result['customer_full_name'] ? $result['customer_full_name'] : '---',
				'customer_image'  		=> $customer_image,
				'thumb'       			=> $image,
				'name'        			=> $result['name'],
				'video'        			=> $result['video'],
				'number_image'        	=> $result['number_image'],
				'acreage'        		=> $result['acreage'],
				'bedroom'        		=> $result['bedroom'],
				'bathroom'        		=> $result['bathroom'],
				'location'        		=> $result['zone_name'].', '.$result['district_name'],
				'link_location'   		=> $this->url->link('product/zone', 'zone_id=' . $result['zone_id'].'&district_id='.$result['district_id']),
				'wishlist'        		=> $result['wishlist'],
				'description' 			=> utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
				'price'       			=> $price,
				'tax'         			=> $tax,
				'rating'      			=> $rating,
				'href'        			=> $this->url->link('product/product', 'product_id=' . $result['product_id'])
			);
		}


		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = '{page}';
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

		$data['sorts'] = array();

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_default'),
			'href'  => URL_HOME
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_desc'),
			'href'  => URL_HOME.'&order=DESC'
		);

		$data['sorts'][] = array(
			'text'  => 'Video',
			'href'  => URL_HOME.'&filter=video'
		);

		$data['url_pagination'] = $this->url->link('extension/module/latest&layout=page');
		$data['url_current'] = URL_HOME;

		

		if($layout == 'module') {
			return $this->load->view('extension/module/latest', $data);
		} else {
			$this->response->setOutput($this->load->view('extension/module/latest', $data));
		}

		
	}


}
