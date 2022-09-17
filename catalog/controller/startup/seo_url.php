<?php
class ControllerStartupSeoUrl extends Controller {
	public function index() {
		// Add rewrite to url class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}

		// Decode URL
		if (isset($this->request->get['_routing_'])) {
			$parts = explode('/', $this->request->get['_routing_']);

			// remove any empty arrays from trailing
			if (utf8_strlen(end($parts)) == 0) {
				array_pop($parts);
			}

			foreach ($parts as $part) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE keyword = '" . $this->db->escape($part) . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

				if ($query->num_rows) {
					$url = explode('=', $query->row['query']);

					if ($url[0] == 'product_id') {
						$this->request->get['product_id'] = $url[1];
					}

					if($url[0] == 'blog_article_id') { 
					 	$this->request->get['blog_article_id'] = $url[1];
					 }
					if($url[0] == 'blog_author_id') {
					 	$this->request->get['blog_author_id'] = $url[1];
					 } 
					if ($url[0] == 'blog_category_id') {
					 	$this->request->get['blog_category_id'] = $url[1];
					} 

					if ($url[0] == 'project_id') {
						$this->request->get['project_id'] = $url[1];
					}

					if ($url[0] == 'category_id') {
						if (!isset($this->request->get['path'])) {
							$this->request->get['path'] = $url[1];
						} else {
							$this->request->get['path'] .= '_' . $url[1];
						}
					}

					if ($url[0] == 'zone_id') {
						if (!isset($this->request->get['zone_id'])) {
							$this->request->get['zone_id'] = $url[1];
						} else {
							$this->request->get['zone_id'] .= '_' . $url[1];
						}
					}

					if ($url[0] == 'district_id') {
						if (!isset($this->request->get['district_id'])) {
							$this->request->get['district_id'] = $url[1];
						} else {
							$this->request->get['district_id'] .= '_' . $url[1];
						}
					}


					if ($url[0] == 'manufacturer_id') {
						$this->request->get['manufacturer_id'] = $url[1];
					}

					if ($url[0] == 'location_id') {
						$this->request->get['location_id'] = $url[1];
					}

					if ($url[0] == 'information_id') {
						$this->request->get['information_id'] = $url[1];
					}




					if ($query->row['query'] && $url[0] != 'information_id' && $url[0] != 'manufacturer_id' && $url[0] != 'location_id' && $url[0] != 'category_id'
						&& $url[0] != 'product_id'
						&& $url[0] != 'blog_article_id'
					 	&& $url[0] != 'blog_category_id'
					 	&& $url[0] != 'blog_author_id'
						&& $url[0] != 'project_id' && $url[0] != 'zone_id' && $url[0] != 'district_id') {

						$this->request->get['routing'] = $query->row['query'];
						
					}
				} else {
					$this->request->get['routing'] = 'error/not_found';

					break;
				}
			}

			if (!isset($this->request->get['routing'])) {
				if (isset($this->request->get['product_id'])) {
					$this->request->get['routing'] = 'product/product';
				} elseif (isset($this->request->get['project_id'])) {
					$this->request->get['routing'] = 'project/project';
				} elseif (isset($this->request->get['path'])) {
					$this->request->get['routing'] = 'product/category';
				} elseif (isset($this->request->get['zone_id'])) {
					$this->request->get['routing'] = 'product/zone';
				} elseif (isset($this->request->get['district_id'])) {
					$this->request->get['routing'] = 'product/district';
				} elseif (isset($this->request->get['manufacturer_id'])) {
					$this->request->get['routing'] = 'product/manufacturer/info';
				} elseif (isset($this->request->get['location_id'])) {
					$this->request->get['routing'] = 'product/location/info';
				} elseif (isset($this->request->get['information_id'])) {
					$this->request->get['routing'] = 'information/information';
				} else if (isset($this->request->get['blog_article_id'])) {
				 	$this->request->get['routing'] = 'blog/article/view';
				} else if (isset($this->request->get['blog_author_id'])) {
				 	$this->request->get['routing'] = 'blog/author';
				} else if (isset($this->request->get['blog_category_id'])) {
				 	$this->request->get['routing'] = 'blog/category';
				}
			}
		}
	}

	public function rewrite($link) {
		$url_info = parse_url(str_replace('&amp;', '&', $link));

		$url = '';

		$data = array();

		parse_str($url_info['query'], $data);

		foreach ($data as $key => $value) {
			if (isset($data['routing'])) {
				if (
				($data['routing'] == 'product/product' && $key == 'product_id')
				|| ($data['routing'] == 'project/project' && $key == 'project_id')
				 || (($data['routing'] == 'product/manufacturer/info'
				 || $data['routing'] == 'product/product') && $key == 'manufacturer_id')

				 || (($data['routing'] == 'product/location/info'
				 || $data['routing'] == 'product/product') && $key == 'location_id')
			

				 || ($data['routing'] == 'information/information' && $key == 'information_id')) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

					if ($query->num_rows && $query->row['keyword']) {
						$url .= '/' . $query->row['keyword'];

						unset($data[$key]);
					}
				} else if($data['routing'] == 'blog/article/view' && $key == 'blog_article_id') { 
						 $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
						 if ($query->num_rows) {
						 	$url .= '/' . $query->row['keyword'];
						 	unset($data[$key]);
						 }
				 } else if($data['routing'] == 'blog/author' && $key == 'blog_author_id') {
				 		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
				 
					 if ($query->num_rows) {
							 $url .= '/' . $query->row['keyword'];
							 unset($data[$key]);
					 }
				 }  else if($data['routing'] == 'blog/category' && $key == 'blog_category_id') { 
				 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
				 
					 if ($query->num_rows) {
					 	$url .= '/' . $query->row['keyword'];
					 	unset($data[$key]);
					 } 
				} else if($data['routing'] == 'blog/search') {
						 if(isset($key) && ($key == 'blog_search')) {
						 	$url .= '/search&blog_search=' . $value;
						 }
				} else if(isset($data['routing']) && $data['routing'] == 'blog/article' && $key != 'blog_article_id' && $key != 'blog_author_id' && $key != 'blog_category_id' && $key != 'page') { 
	 				$url .= '/tin-tuc';
 				} elseif ($key == 'path') {
					$categories = explode('_', $value);

					foreach ($categories as $category) {
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE `query` = 'category_id=" . (int)$category . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

						if ($query->num_rows && $query->row['keyword']) {
							$url .= '/' . $query->row['keyword'];
						} else {
							$url = '';

							break;
						}
					}

					unset($data[$key]);
				}

				elseif ($key == 'zone_id') {
					$zones = explode('_', $value);

					foreach ($zones as $zone) {
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE `query` = 'zone_id=" . (int)$zone . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

						if ($query->num_rows && $query->row['keyword']) {
							$url .= '/' . $query->row['keyword'];
						} else {
							$url = '';

							break;
						}
					}

					unset($data[$key]);
				} 

				elseif ($key == 'district_id') {
					$districts = explode('_', $value);

					foreach ($districts as $district) {
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE `query` = 'district_id=" . (int)$district . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

						if ($query->num_rows && $query->row['keyword']) {
							$url .= '/' . $query->row['keyword'];
						} else {
							$url = '';

							break;
						}
					}

					unset($data[$key]);
				}
			}
		}

		if ($url) {
			unset($data['routing']);

			$query = '';

			if ($data) {
				foreach ($data as $key => $value) {
					$query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((is_array($value) ? http_build_query($value) : (string)$value));
				}

				if ($query) {
					$query = '?' . str_replace('&', '&amp;', trim($query, '&'));
				}

				
			}

			return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
		} else {
			return $link;
		}
	}
}
