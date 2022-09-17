<?php
class ControllerExtensionModuleLatestColumn extends Controller {
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

		if(isset($this->request->get['layout'])) {
			$layout  = $this->request->get['layout'];
		} else {
			$layout  = 'module';
		}

		$this->load->language('extension/module/latest_column');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = $setting['limit'];

		$filter_data = array(
			'sort'  => 'p.date_added',
			'order' => $order,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit
		);

		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

		$results = $this->model_catalog_product->getProducts($filter_data);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				
				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					// 'price'       => $price,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
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


			return $this->load->view('extension/module/latest_column', $data);

			
		}
	}


}
