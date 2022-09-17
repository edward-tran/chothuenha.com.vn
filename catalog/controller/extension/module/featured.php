<?php
class ControllerExtensionModuleFeatured extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/featured');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		if (!empty($setting['product'])) {
			$products = array_slice($setting['product'], 0, (int)$setting['limit']);

			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					if ($product_info['image']) {
						$image = URL_HOME.'image/'.$product_info['image'];
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

					

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}

					$categorys = $this->model_catalog_product->getCategoriesByProductId($product_info['product_id']);
					$cate = [];
					if($categorys) {
						foreach($categorys as $category) {
							$cate[] = $category['name'];
						}
					}

					$cate_array = array_filter($cate);

					
					$data['products'][] = array(
						'product_id'  => $product_info['product_id'],
						'thumb'       => $image,
						'category'       => implode(", ", $cate_array),
						'viewed'      => $product_info['viewed'],
						'facade'      => $product_info['facade'],
						'direction'      => $product_info['direction'],
						'large_road'      => $product_info['large_road'],
						'height'      => str_replace('.00000000', '', $product_info['height']),
						'date_added'  => $this->calDate($product_info['date_added']),
						'name'        => $product_info['name'],
						'location'    => $product_info['location'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
						'short_description' => $product_info['short_description'],
						'price'       => $price,
						'rating'      => $rating,
						'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
					);
				}
			}
		}

		if(isset($setting['layout']) && $setting['layout'] == 'hot') {
			$layout = 'featured_hot';
		} else {
			$layout = 'featured';
		}

		$data['title'] = $setting['name'];

		

		if ($data['products']) {
			return $this->load->view('extension/module/'.$layout, $data);
		}
	}

	public function calDate($date) {
		$date1 = new DateTime($date);
		$date2 = new DateTime(date('Y-m-d h:i:sa'));
		// The diff-methods returns a new DateInterval-object...
		$diff = $date2->diff($date1);
		// Call the format method on the DateInterval-object
		if($diff->h <=24) {
			return $diff->h .' giờ trước';
		}
		if($diff->d <=30) {
			return $diff->d;
		}

		if($diff->d >30) {
			return date($this->language->get('date_format_short'), strtotime($date));
		}
	}
}
// $datetime1 = new DateTime("2021-05-20");

// $datetime2 = new DateTime("2021-06-22");

// $difference = $datetime1->diff($datetime2);

// $abc = array(
//     $difference->y,
// $difference->m,
// $difference->d,
//     );

