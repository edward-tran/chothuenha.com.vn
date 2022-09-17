<?php
class ControllerProductZone extends Controller {
	public function index() {

		$this->load->language('product/zone');

		$this->load->model('catalog/zone');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (isset($this->request->get['header'])) {
			$load_header = false;
		} else {
			$load_header = true;
		}



		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
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

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['zone_id'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$zone_id = '';

			$district_id = '';
			if(isset($this->request->get['district_id'])) {
				$district_id  = $this->request->get['district_id'];
			}


			$parts = explode('_', (string)$this->request->get['zone_id']);

			$zone_id = (int)array_pop($parts);
			
		} else {
			$zone_id = 0;
		}


		$this->load->model('localisation/zone');
		
		$zone_info = $this->model_localisation_zone->getZone($zone_id);



		if ($zone_info) {
			$this->document->setTitle($zone_info['name']);
			$this->document->setDescription($zone_info['name']);
			$this->document->setKeywords($zone_info['name']);

			$district_info = [];
			if($district_id) {
				$this->load->model('localisation/district');
				$district_info = $this->model_localisation_district->getDistrict($district_id);
				if($district_info) {
					$this->document->setTitle($zone_info['name'].'-'.$district_info['name']);
					$this->document->setDescription($zone_info['name'].'-'.$district_info['name']);
					$this->document->setKeywords($zone_info['name'].'-'.$district_info['name']);
				}
			}

			if($district_info) {
				$data['heading_title'] = $district_info['name'];
			} else {
				$data['heading_title'] = $zone_info['name'];
			}

			
				      

			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));


			$data['breadcrumbs'][] = array(
				'text' => $zone_info['name'],
				'href' => $this->url->link('product/zone', 'zone_id=' . $this->request->get['zone_id'])
			);

			
			if ($district_info) {
				$data['breadcrumbs'][] = array(
					'text' => $district_info['name'],
					'href' => ''
				);
			}	

			

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			if (isset($this->request->get['type_id'])) {
				$type_id = $this->request->get['type_id'];
			} else {
				$type_id = '';
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

			$data['categories'] = array();


			$data['products'] = array();

			$filter_data = array(
				'filter_zone_id' 	 => $zone_id,
				'filter' 	 		 => $filter,
				'filter_type_id' 	 => $type_id,
				'filter_bedroom' 	 => $bedroom,
				'filter_bathroom' 	 => $bathroom,
				'filter_direction_house_id' 	 => $direction_house_id,
				'filter_district_id' => $district_id,
				'filter_filter'      => $filter,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);

			$product_total = $this->model_catalog_zone->getTotalProducts($filter_data);

			
			$results = $this->model_catalog_zone->getProducts($filter_data);
			
			

				foreach ($results as $result) {
					if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
						$image = URL_HOME.'image/'.$result['image'];
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
					}


					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

			

					if ($this->config->get('config_review_status')) {
						$rating = (int)$result['rating'];
					} else {
						$rating = false;
					}

					if (is_file(DIR_IMAGE . $result['customer_image'])) {
						$customer_image = $this->model_tool_image->resize($result['customer_image'], 40, 40);
					} else {
						$customer_image = $this->model_tool_image->resize('data/avatar.jpg', 40, 40);
					}
					

					$data['products'][] = array(
						'product_id'  => $result['product_id'],
						'customer_full_name'  => $result['customer_full_name'] ? $result['customer_full_name'] : '---',
						'customer_image'  => $customer_image,
						'thumb'       => $image,
						'name'        => $result['name'],
						'video'        => $result['video'],
						'number_image'        => $result['number_image'],
						'acreage'        => $result['acreage'],
						'bedroom'        => $result['bedroom'],
						'bathroom'        => $result['bathroom'],
						'location'        => $result['zone_name'].', '.$result['district_name'],
						'link_location'   => $this->url->link('product/zone', 'zone_id=' . $result['zone_id'].'&district_id='.$result['district_id']),
						'wishlist'        => $result['wishlist'],
						'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
						'price'       => $price,
						'rating'      => $rating,
						'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
					);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/zone', 'zone_id=' . $this->request->get['zone_id'] . '&sort=p.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/zone', 'zone_id=' . $this->request->get['zone_id'] . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/zone', 'zone_id=' . $this->request->get['zone_id'] . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/zone', 'zone_id=' . $this->request->get['zone_id'] . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/zone', 'zone_id=' . $this->request->get['zone_id'] . '&sort=p.price&order=DESC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/zone', 'zone_id=' . $this->request->get['zone_id'] . '&sort=rating&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/zone', 'zone_id=' . $this->request->get['zone_id'] . '&sort=rating&order=ASC' . $url)
				);
			}

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/zone', 'zone_id=' . $this->request->get['zone_id'] . '&sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/zone', 'zone_id=' . $this->request->get['zone_id'] . '&sort=p.model&order=DESC' . $url)
			);



			$data['sorts'] = array();

			if(isset($this->request->get['district_id'])) {
				$url_district  = '&district_id='.$this->request->get['district_id'];
			} else {
				$url_district = '';
			}

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'href'  => $this->url->link('product/zone', 'zone_id=' . $this->request->get['zone_id'].$url_district . '&sort=p.sort_order&order=DESC')
			);



			$data['sorts'][] = array(
				'text'  => 'Mới nhất',
				'href'  => $this->url->link('product/zone', 'zone_id=' . $this->request->get['zone_id'].$url_district . '&sort=p.sort_order&order=ASC')

			);

			$data['sorts'][] = array(
				'text'  => 'Video',
				'href'  => $this->url->link('product/zone', 'zone_id=' . $this->request->get['zone_id'].$url_district . '&filter=video')
			);



			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/zone', 'zone_id=' . $this->request->get['zone_id'] . $url . '&limit=' . $value)
				);
			}

			$url = '';

			if (isset($this->request->get['district_id'])) {
				$url .= '&district_id=' . $this->request->get['district_id'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}





			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = '{page}';

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));



			if ($page == 1) {
			    $this->document->addLink($this->url->link('product/zone', 'zone_id=' . $zone_info['zone_id']), 'canonical');
			} else {
				$this->document->addLink($this->url->link('product/zone', 'zone_id=' . $zone_info['zone_id'] . '&page='. $page), 'canonical');
			}
			
			if ($page > 1) {
			    $this->document->addLink($this->url->link('product/zone', 'zone_id=' . $zone_info['zone_id'] . (($page - 2) ? '&page='. ($page - 1) : '')), 'prev');
			}

			if ($limit && ceil($product_total / $limit) > $page) {
			    $this->document->addLink($this->url->link('product/zone', 'zone_id=' . $zone_info['zone_id'] . '&page='. ($page + 1)), 'next');
			}

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');

			if($load_header == true) {
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
			} else {
				$data['footer'] = '';
				$data['header'] = '';
			}

			$url = '';

			if(isset($this->request->get['zone_id'])) {
				$url .=  '&zone_id='.$this->request->get['zone_id'];
			}

			if(isset($this->request->get['district_id'])) {
				$url .=  '&district_id='.$this->request->get['district_id'];
			}

			$data['url_pagination'] = $this->url->link('product/zone', $url.'&header=false');

			$data['url_current'] = $this->url->link('product/zone', $url);

			$this->response->setOutput($this->load->view('product/zone', $data));

		} else {
			$url = '';

			if (isset($this->request->get['zone_id'])) {
				$url .= '&zone_id=' . $this->request->get['zone_id'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/zone', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			if($load_header == true) {
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
			} else {
				$data['footer'] = '';
				$data['header'] = '';
			}

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	
}
