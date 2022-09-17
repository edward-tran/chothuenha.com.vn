<?php
class ModelAccountProduct extends Model {
	
	public function addProduct($data = array()) {
		$customer_id = $this->customer->getId();


		$this->db->query("INSERT INTO " . DB_PREFIX . "product SET
			model = '" . strtoupper(substr(md5(uniqid(mt_rand(), true)) , 0, 9)) . "',
			type_id = '" . (int)$data['type_id'] . "',
			post_type_id = '" . (int)$data['post_type_id'] . "',
			video = '" . $this->db->escape($data['video']) . "',
			price = '" . (float)$data['price'] . "',
			zone_id = '" . (int)$data['zone_id'] . "',
			ward_id = '" . (int)$data['ward_id'] . "',
			district_id = '" . (int)$data['district_id'] . "',
			street_id = '" . (int)$data['street_id'] . "',
			location = '" . $this->db->escape($data['location']) . "',
			customer_id = '" . (int)$customer_id . "',
			acreage = '" . $this->db->escape($data['acreage']) . "',
			bedroom = '" . $this->db->escape($data['bedroom']) . "',
			bathroom = '" . $this->db->escape($data['bathroom']) . "',
			quantity = '" . 1 . "',
			from_date = '" . $data['from_date'] . "',
			expiration_date = '" . $data['expiration_date'] . "', 
			direction_house_id = '" . (int)$data['direction_house_id'] . "',
			sort_order = '" . 0 . "',
			date_available = NOW(),
			date_added = NOW(),
			date_modified = NOW()");

		$product_id = $this->db->getLastId();

		$this->db->query("UPDATE " . DB_PREFIX . "product SET
				from_date = '" . $this->db->escape($data['from_date']) . "', expiration_date = '" . $this->db->escape($data['expiration_date']) . "' WHERE product_id = '" . (int)$product_id . "'");

		$this->db->query("UPDATE " . DB_PREFIX . "product SET
				image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET
				image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}



		if ($this->config->get('config_approve_product')) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET
				status = '" . 1 . "' WHERE product_id = '" . (int)$product_id . "'");
			$this->db->query("UPDATE " . DB_PREFIX . "product SET admin_approve_id = '" . 1 . "' WHERE product_id = '" . (int)$product_id . "'");	
		}
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET
				product_id = '" . (int)$product_id . "',
				language_id = '" . (int)$this->config->get('config_language_id') . "',
				name = '" . $this->db->escape($data['name']) . "',
				description = '" . $this->db->escape($data['description']) . "',
				meta_title = '" . $this->db->escape($data['name']) . "', meta_description = '" . $this->db->escape($data['name']) . "', meta_keyword = '" . $this->db->escape($data['name']) . "'");
		
		$store_id = 0;
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");

		if (!empty($data['product_image'])) {

			$path_folder = DIR_IMAGE.'catalog/product_customer';	

			$image = 'catalog/product_customer/'.$data['product_image']['name'][0];

			$this->db->query("UPDATE " . DB_PREFIX . "product SET
					image = '" . $this->db->escape($image) . "' WHERE product_id = '" . (int)$product_id . "'");

			foreach($data['product_image']['tmp_name']  as $key => $tmp_name) {

				$name = $data['product_image']['name'][$key];

				$targetFile = $path_folder.'/'.$name;

				if (!file_exists($path_folder)){
        			mkdir($path_folder, 0777, true);
        		}

				if (move_uploaded_file($tmp_name, $targetFile)) {

	             	$name_save = 'catalog/product_customer/'.$data['product_image']['name'][$key];
	             	$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($name_save) . "', sort_order = '" . (int)$key . "'");
	        	}
			}

		}


        $name = $data['name'];
        $keyword = $this->url->seoURL(array(
            'string' => $name,
            'query' => 'product_id=' . (int) $product_id,
            'db' => $this->db
        ));
        
		if (!empty($keyword)) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$this->config->get('config_language_id') . "', query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($keyword) . "'");
		}


		$this->load->model('localisation/post_type');

		// $date_post_id = $data['date_post_id'];
		// $post_type = $this->model_localisation_post_type->getPostType($data['post_type_id']);
		// $post_type_name = $post_type ? $post_type['name'] : '';
		// $price_service = $post_type ? $post_type['price'] : 0;
		// $price_tax  = $this->tax->calculate($price_service * $date_post_id, 9) - ($price_service * $date_post_id);
		// $price_finnal  = ($price_service * $date_post_id) + $price_tax;


		// $this->load->model('account/customer_wallet');
		// $total  = $this->tax->calculate($price_service * $date_post_id, 9);
		// $amount = $this->model_account_customer_wallet->getTotalWallet();

		$order_status_id = (int)$this->config->get('config_status_prosess_payment_id');
		$this->db->query("UPDATE " . DB_PREFIX . "product SET status = '" . (int)0 . "' WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("UPDATE " . DB_PREFIX . "product SET order_status_id = '" . (int)$order_status_id . "' WHERE product_id = '" . (int)$product_id . "'");

		 // $this->db->query("UPDATE " . DB_PREFIX . "customer_wallet SET amount = (amount - '" . (int)$total . "') WHERE customer_id = '" . (int)$customer_id . "'");
	
		// $this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction_pay SET
		// 	customer_id = '" . (int)$customer_id . "',
		// 	product_id = '" . (int)$product_id . "',
		// 	amount = '" . (float)$total . "',
		// 	note = '" . 'Thanh toán post tin' . "',
		// 	code = '" . strtoupper(substr(md5(uniqid(mt_rand(), true)) , 0, 9)) . "',
		// 	post_type_id = '" . (int)$data['post_type_id'] . "',
		// 	date_added = NOW()");
		// }

		// $this->db->query("INSERT INTO " . DB_PREFIX . "customer_product SET
		// 	customer_id = '" . (int)$customer_id . "',
		// 	product_id = '" . (int)$product_id . "',
		// 	post_type_name = '" . $post_type_name . "',
		// 	date_post_id = '" . $date_post_id . "',
		// 	price_service = '" . (float)$price_service . "',
		// 	price_tax = '" . (float)$price_tax . "',
		// 	price_finnal = '" . (float)$price_finnal . "',
		//  	tax_class_id = '" . 9 . "',
		//  	expiration_date = '" . $this->db->escape($data['expiration_date']) . "',
		// 	from_date = '" . $this->db->escape($data['from_date']) . "'");

		$data['order_status_id'] 		= $order_status_id;
		// $data['price_tax'] 		 		= $price_tax;
		// $data['price_finnal'] 		 	= $price_finnal;
		// $data['post_type_name'] 		= $post_type_name;
		// $data['price_service'] 		 	= $price_service;

		$this->sendMail($product_id, $data);
		
		$this->cache->delete('product');
		return $product_id;


	}

	protected function sendMail($product_id, $data = array()) {
		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSubject('THÔNG BÁO BÀI ĐĂNG MỚI VỪA POST');

		$mail->setReplyTo($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_email'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject('THÔNG BÁO BÀI ĐĂNG MỚI VỪA POST');

		$this->load->model('localisation/order_status');

		$product_info = $this->getProduct($product_id);
		
		$data['date_added'] 		= date('Y-m-d');
		$data['from_date'] 			= $data['from_date'];
		$data['name'] 				= $product_info['name'];
		$data['model'] 				= $product_info['model'];
		$order_status 				= $this->model_localisation_order_status->getOrderStatus($data['order_status_id']);
		$data['order_status_name'] 	= $order_status['name'];
		$data['customer_fullname']  = $this->customer->getFullName();
		$mail->setHtml($this->load->view('mail/alert_add_product', $data));
		$mail->setTo($this->config->get('config_email'));
		$mail->send();
	}

	public function getProducts($data = array()) {

		$customer_id = $this->customer->getId();

		$sql = "SELECT p.product_id,
		(SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating,

		(SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps
			WHERE ps.product_id = p.product_id
			AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'
			AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";



		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND  p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.customer_id = '" . (int)$customer_id . "'";



		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.product_id) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.product_id) ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getTotalProducts($data = array()) {

		$customer_id = $this->customer->getId();

		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND  p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.customer_id = '" . (int)$customer_id . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProductSpecials($data = array()) {
		$sql = "SELECT DISTINCT ps.product_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$product_data = array();

		$query = $this->db->query($sql);
		

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getProduct($product_id) {
		$customer_id = 0;
		if($this->customer->getId()) {
			$customer_id = $this->customer->getId();
		}
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, k.name AS post_type,
			(SELECT product_id FROM " . DB_PREFIX . "customer_wishlist pd3 WHERE pd3.product_id = p.product_id AND pd3.customer_id = '" . (int)$customer_id . "') AS wishlist,

			p.from_date,
			p.expiration_date,
	
			(SELECT name FROM " . DB_PREFIX . "post_type r3 WHERE r3.post_type_id = p.post_type_id) AS post_type_name,

			(SELECT name FROM " . DB_PREFIX . "order_status r9 WHERE r9.order_status_id = p.order_status_id AND language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status_name,

			

			(SELECT r3.name FROM " . DB_PREFIX . "zone r3 WHERE r3.zone_id = p.zone_id) AS zone_name,
			(SELECT r4.name FROM " . DB_PREFIX . "district r4 WHERE r4.district_id = p.district_id) AS district_name,

			(SELECT COUNT(product_image_id) FROM " . DB_PREFIX . "product_image r5 WHERE r5.product_id = p.product_id) AS number_image,

			(SELECT name FROM " . DB_PREFIX . "direction_house r6 WHERE r6.direction_house_id = p.direction_house_id) AS direction,

			

			(SELECT name FROM " . DB_PREFIX . "type r8 WHERE r8.type_id = p.type_id) AS type_name,

			 (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE
			 	pd2.product_id = p.product_id
			 	AND p.customer_id = '" . (int)$customer_id . "'
			 	AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN " . DB_PREFIX . "post_type k ON (p.post_type_id = k.post_type_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],
				'post_type_name'       => $query->row['post_type_name'],
				'type_id'       => $query->row['type_id'],
				'direction'       => $query->row['direction'],
				'video'       => $query->row['video'],
				'order_status_name'       => $query->row['order_status_name'],
				'direction_house_id'       => $query->row['direction_house_id'],
			
			
				'type_name'       => $query->row['type_name'],
				'from_date'       => $query->row['from_date'],
				'expiration_date'       => $query->row['expiration_date'],
				
				
				'customer_id'       => $query->row['customer_id'],
				'zone_name'       => $query->row['zone_name'],
				'district_name'       => $query->row['district_name'],
				'number_image'       => $query->row['number_image'] ? $query->row['number_image'] : 1,
				'wishlist'         => $query->row['wishlist'],
				'acreage'         => $query->row['acreage'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'short_description'      => $query->row['short_description'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
			
				'sku'              => $query->row['sku'],
				'height'              => $query->row['height'],
				'bedroom'              => $query->row['bedroom'],
				'upc'              => $query->row['upc'],
				'bathroom'              => $query->row['bathroom'],
				'zone_id'              => $query->row['zone_id'],
				'district_id'              => $query->row['district_id'],
				
				'customer_id'              => $query->row['customer_id'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'post_type_id'  => $query->row['post_type_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'post_type'     => $query->row['post_type'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'date_added'   		=> $query->row['date_added'],
				'expiration_date'   => $query->row['expiration_date'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed'],
				'order_status_id'  => $query->row['order_status_id'],
				
				
			);
		} else {
			return false;
		}
	}

	public function excelProducts($data = array()) {

		$customer_id = $this->customer->getId();

		$sql = "SELECT p.product_id,
		(SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating,

		(SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps
			WHERE ps.product_id = p.product_id
			AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'
			AND p.customer_id = '" . (int)$customer_id . "'
			AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";



		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND  p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.customer_id = '" . (int)$customer_id . "'";


		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if(!empty($data['arr_selected'])) {


			$ICDCodeSub_arr = $data['arr_selected'];


			$sql .= " AND p.product_id IN(" . $ICDCodeSub_arr . ") ";
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.product_id) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.product_id) ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$product_data = array();

		$query = $this->db->query($sql);

		

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}
}