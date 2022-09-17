<?php
class ModelExtensionReviewpro extends Model {
	
	public function getratinglist() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "wrating r LEFT JOIN " . DB_PREFIX . "wrating_description rd ON r.rating_id = rd.rating_id WHERE rd.language_id = '". $this->config->get('config_language_id') ."'");
		
		return $query->rows;
	}
	
	public function getratingparcount($rating_id, $product_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "reviewpro rp LEFT JOIN " . DB_PREFIX . "reviewpro_rating rpr ON rp.review_id = rpr.reviewpro_id WHERE rpr.rating_id = '". $rating_id ."' AND rp.product_id = '". $product_id ."' AND rp.status = 1");
		return $query->row['total'];
	}
	
	public function getratingparsum($rating_id, $product_id) {
		$query = $this->db->query("SELECT SUM(rating) AS total FROM " . DB_PREFIX . "reviewpro rp LEFT JOIN " . DB_PREFIX . "reviewpro_rating rpr ON rp.review_id = rpr.reviewpro_id WHERE rpr.rating_id = '". $rating_id ."' AND rp.product_id = '". $product_id ."' AND rp.status = 1");
		return $query->row['total'];
	}
	
	public function getratingparsumm($product_id) {
		$query = $this->db->query("SELECT SUM(rating) AS total FROM " . DB_PREFIX . "reviewpro rp LEFT JOIN " . DB_PREFIX . "reviewpro_rating rpr ON rp.review_id = rpr.reviewpro_id WHERE rp.product_id = '". $product_id ."' AND rp.status = 1");
		return $query->row['total'];
	}
	
	public function getratingparavg($rating_id, $product_id) {
		$query = $this->db->query("SELECT AVG(rating) AS total FROM " . DB_PREFIX . "reviewpro rp LEFT JOIN " . DB_PREFIX . "reviewpro_rating rpr ON rp.review_id = rpr.reviewpro_id WHERE rpr.rating_id = '". $rating_id ."' AND rp.product_id = '". $product_id ."' AND rp.status = 1");
		return $query->row['total'];
	}
	
	public function reviewavr($rating_id, $product_id, $review_id) {
		$query = $this->db->query("SELECT rating FROM " . DB_PREFIX . "reviewpro rp LEFT JOIN " . DB_PREFIX . "reviewpro_rating rpr ON rp.review_id = rpr.reviewpro_id WHERE rpr.rating_id = '". $rating_id ."' AND rp.product_id = '". $product_id ."' AND rp.review_id = '". $review_id ."' AND rp.status = 1");
		return $query->row;
	}
	
	public function getreviewratings($reviewpro_id,$rating_id){
		$query = $this->db->query("SELECT * from " . DB_PREFIX . "reviewpro_rating WHERE reviewpro_id = '". $reviewpro_id ."' AND rating_id = '". $rating_id ."'");
		return $query->row;
	}
	
	public function addreview($data) {
		if($this->customer->isLogged()){
			$data['customer_id'] = $this->session->data['customer_id'];
		}else{
			$data['customer_id'] = 0;
		}
	
		if($this->config->get('reviewpro_fields')['appoverating']){
			if($this->config->get('reviewpro_fields')['appoverating'] == 1){
				if(!$this->customer->isLogged()){
					if($this->config->get('reviewpro_fields')['reviewifabove']){
						if(isset($data['rating'])){
							$i = 0;
							$ratingvalue = array();
							foreach($data['rating'] as $key => $value){
								$ratingvalue[] = $value;
								$i++;
							}
							$sumrating = array_sum($ratingvalue) / $i;
							if($sumrating >=  $this->config->get('reviewpro_fields')['reviewifabove']){
								$status = 1;
							}else{
								$status = 0;
							}
						}
					}
				}else{
					$status = 0;
				}
			}elseif($this->config->get('reviewpro_fields')['appoverating'] == 3){
				if($this->customer->isLogged()){
					if($this->config->get('reviewpro_fields')['reviewifabove']){
						if(isset($data['rating'])){
							$i = 0;
							$ratingvalue = array();
							foreach($data['rating'] as $key => $value){
								$ratingvalue[] = $value;
								$i++;
							}
							$sumrating = array_sum($ratingvalue) / $i;
							if($sumrating >=  $this->config->get('reviewpro_fields')['reviewifabove']){
								$status = 1;
							}else{
								$status = 0;
							}
						}
					}
				}else{
					$status = 0;
				}
			}else{
				if($this->config->get('reviewpro_fields')['reviewifabove']){
					if(isset($data['rating'])){
						$i = 0;
						$ratingvalue = array();
						foreach($data['rating'] as $key => $value){
							$ratingvalue[] = $value;
							$i++;
						}
						$sumrating = array_sum($ratingvalue) / $i;
						if($sumrating >=  $this->config->get('reviewpro_fields')['reviewifabove']){
							$status = 1;
						}else{
							$status = 0;
						}
					}else{
						$status = 0;
					}
				}
			}
			
		}else{
			$status = 0;
		}	
		
		
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "reviewpro SET name = '". $this->db->escape(isset($data['name']) ? $data['name'] : '') ."', email = '". (isset($data['email']) ? $data['email'] : '') ."', title = '". (isset($data['title']) ? $data['title'] : '') ."', comment = '". $this->db->escape(isset($data['comment']) ? $data['comment'] : '') ."', customer_id = '". $data['customer_id'] ."', product_id = '". $data['product_id'] ."', status = '". $status ."', date_added = NOW(), date_modify = NOW()");
		$reviewpro_id = $this->db->getLastId();
		if(isset($data['file'])){
			foreach($data['file'] as $key => $value){
				$this->db->query("INSERT INTO " . DB_PREFIX . "reviewpro_attachments SET review_id = '". $reviewpro_id ."', file = '". $value ."'");
			}
		}
		if(isset($data['rating'])){
			foreach($data['rating'] as $key => $value){
				$this->db->query("INSERT INTO " . DB_PREFIX . "reviewpro_rating SET reviewpro_id = '". $reviewpro_id ."', rating_id = '". $key ."',  	rating = '". $value ."'");
			}
		}
		
		$getverifiespurchased = $this->getverifiespurchased($data['product_id'], $data['customer_id'], $this->config->get('reviewpro_fields')['order_status_id']);
		
			if($this->config->get('reviewpro_fields')['couponstatus']){
				if($data['email']){
					$customeremail = $data['email'];
				}
				
				if($this->customer->isLogged()){
					$customeremail = $this->customer->getEmail();
					$customer_id = $this->customer->getId();
				}else{
					$customeremail = '';
					$customer_id = 0;
				}
				
				if($customeremail){
					$couponcode = $reviewpro_id.rand(pow(10, 3-1), pow(10, 3)-1);
					$this->addCoupon($customer_id, $couponcode);
					$subject = $this->config->get('reviewpro_fields')[$this->config->get('config_language_id')]['coupon_email_subject'];
					$tempmessage = $this->config->get('reviewpro_fields')[$this->config->get('config_language_id')]['coupon_email_msg'];
					$find_shortcode = array(
						'{name}',		
						'{email}',	
						'{title}',	
						'{comment}',
						'{coupon}',
					);
					$replace_shotcode = array(
						$data['name'],	
						$data['email'],	
						$data['title'],	
						$data['comment'],
						$couponcode,
					);
					$message = str_replace($find_shortcode, $replace_shotcode, $tempmessage);
					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password') , ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
					$mail->setTo($customeremail);
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender(html_entity_decode($this->config->get('config_name') , ENT_QUOTES, 'UTF-8'));
					$mail->setSubject($subject);
					$mail->setHtml(html_entity_decode($message));
					$mail->send();
				}
			}
			if($getverifiespurchased){
			if($this->config->get('reviewpro_fields')['reviewrewardpoints'] && $this->customer->isLogged()){
				$rewarddesc = isset($this->config->get('reviewpro_fields')['reward_description']) ? $this->config->get('reviewpro_fields')['reward_description'] : '';
				$this->load->model('account/customer');
				$currentcustomer = $this->model_account_customer->getCustomer($this->customer->getId());
				$data = array();
				$data = array(
					'desc' => $rewarddesc,
					'points' => $this->config->get('reviewpro_fields')[$currentcustomer['customer_group_id']]['points'],
					'customer_id' => $this->customer->getId(),
				);
				$this->addpoints($data);
			}
		}	
			
			if($this->config->get('reviewpro_fields')['adminemail']){
				$adminsubject = $this->config->get('reviewpro_fields')[$this->config->get('config_language_id')]['adminsubject'];
				$adminmsg = $this->config->get('reviewpro_fields')[$this->config->get('config_language_id')]['adminmsg'];
				
				$ratingdesc = $this->getratinglist();
				$reviewrating = '<table class="table table-bordered">';
				$reviewrating .= '<tr>';
				$reviewrating .= '<th>Rating Name</th><th>Rating</th>';
				$reviewrating .= '</tr>';
				if($ratingdesc){
					foreach($ratingdesc as $ratings){
						$rating = $this->getreviewratings($reviewpro_id,$ratings['rating_id']);
						$reviewrating .= '<tr>';
						$reviewrating .= '<td>'.$ratings['rating_name'].'</td>';
						$reviewrating .= '<td>'.$rating['rating'].'</td>';
						$reviewrating .= '</tr>';
					}
				}
				$reviewrating .= '</table>';
				
				$this->load->model('catalog/product');
				$product_info = $this->model_catalog_product->getProduct($data['product_id']);
				$name = isset($data['name']) ? $data['name'] : '';
				$email = isset($data['email']) ? $data['email'] : '';
				$title = isset($data['title']) ? $data['title'] : '';
				$comment = isset($data['comment']) ? $data['comment'] : '';
				$product = $product_info['name'];
			
				$find_shortcode = array(
					'{name}',
					'{email}',
					'{product}',
					'{title}',
					'{rating}',
					'{comment}',
				);
				$replace_shotcode = array(
					$name,
					$email,
					$product,
					$title,
					$reviewrating,
					$comment,
				);
				
				$adminmessage = str_replace($find_shortcode, $replace_shotcode, $adminmsg);
				$adminsub = str_replace($find_shortcode, $replace_shotcode, $adminsubject);
				
				$mail = new Mail();
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

				$mail->setTo($this->config->get('config_email'));
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode($adminsub));
				$mail->setHtml(html_entity_decode($adminmessage));
				$mail->send();

				// Send to additional alert emails
				$emails = explode(',', $this->config->get('config_alert_email'));

				foreach ($emails as $email) {
					if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$mail->setTo($email);
						$mail->send();
					}
				}
			}
		
	}
	
	public function getTotalReview($data){
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "reviewpro WHERE status = 1";
		if($data['review_id']){
			$sql .= " AND review_id = '". $data['review_id'] ."'";
		}
		
		if($data['product_id']){
			$sql .= " AND product_id = '". $data['product_id'] ."'";
		}
		
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
	public function getReviews($data) {
		$sql = "SELECT * from " . DB_PREFIX . "reviewpro r WHERE r.date_added <= NOW() AND r.status = '1'";
		
		if($data['review_id']){
			$sql .= " AND r.review_id = '". $data['review_id'] ."'";
		}
		
		if($data['product_id']){
			$sql .= " AND r.product_id = '". $data['product_id'] ."'";
		}
		
		if($data['sort'] == 'ASC'){
			$sql .= " ORDER BY r.date_added ASC";
		}else{
			$sql .= " ORDER BY r.date_added DESC";
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
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getTotalReviewsByProductId($product_id){
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "reviewpro WHERE status = 1 AND product_id = '". $product_id ."'");
		return $query->row['total'];
	}
	
	public function getTotalfeaturedReviewsByProductId($product_id){
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "reviewpro WHERE status = 1 AND featured = 1 AND product_id = '". $product_id ."'");
		return $query->row['total'];
	}
	
	public function getReviewsByProductId($product_id, $data) {
		
		$sql = "SELECT *,(SELECT AVG(rating) FROM " . DB_PREFIX . "reviewpro_rating rpr WHERE r.review_id = rpr.reviewpro_id) As avg from " . DB_PREFIX . "reviewpro r LEFT JOIN " . DB_PREFIX . "reviewpro_rating rr ON r.review_id = rr.reviewpro_id WHERE r.product_id = '" . (int)$product_id . "' AND r.date_added <= NOW() AND r.status = '1'";
		
		if(!empty($data['rating_id'])){
			$sql .= " AND rr.rating_id = '" . (int)$data['rating_id'] . "'";
		}
		$sql .= " GROUP BY r.review_id";
		
	
		if(isset($data['ratings']) && $data['ratings'] == '1'){
			$sql .= " ORDER BY avg DESC";
		}elseif(isset($data['ratings']) && $data['ratings'] == '2'){
			$sql .= " ORDER BY avg ASC";
		}elseif(!empty($data['rating_id'])){
			$sql .= " ORDER BY rr.rating DESC";
		}else{
			$sql .= " ORDER BY r.date_added DESC";
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
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getfeaturedReviewsByProductId($product_id,$data) {
		
		$sql = "SELECT * from " . DB_PREFIX . "reviewpro r WHERE r.product_id = '" . (int)$product_id . "' AND r.date_added <= NOW() AND r.status = '1' AND r.featured = '1' ORDER BY r.date_added DESC";
	
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getreviewrating($reviewpro_id){
		$query = $this->db->query("SELECT * from " . DB_PREFIX . "reviewpro_rating WHERE reviewpro_id = '". $reviewpro_id ."'");
		return $query->rows;
	}

	public function getyesvote($review_id){
		$query = $this->db->query("SELECT count(*) As total from " . DB_PREFIX . "review_vote WHERE review_id = '". $review_id ."' AND yes = 1");
		return $query->row['total'];
	}
	
	public function getnovote($review_id){
		$query = $this->db->query("SELECT count(*) As total from " . DB_PREFIX . "review_vote WHERE review_id = '". $review_id ."' AND no = 1");
		return $query->row['total'];
	}
	
	public function addvoting($data){
		if($this->customer->isLogged()){
			$customer_id = $this->session->data['customer_id'];
		}else{
			$customer_id = 0;
		}
		if($data['helpful']){
			$yes = 1;
			$no  = 0;
		}else{
			$yes = 0;
			$no  = 1;
		}
		$this->db->query("INSERT INTO " . DB_PREFIX . "review_vote SET review_id = '". $data['review_id'] ."', customer_id = '". $customer_id ."', yes = '". $yes ."', no = '". $no ."'");
	}
	
	public function addabused($data){
		if($this->customer->isLogged()){
			$customer_id = $this->session->data['customer_id'];
		}else{
			$customer_id = 0;
		}
		$this->db->query("INSERT INTO " . DB_PREFIX . "review_abuse SET review_id = '". $data['review_id'] ."', customer_id = '". $customer_id ."', reason = '". $data['abuse_reason'] ."', comment = '". $this->db->escape($data['comment']) ."'");
	}
	
	public function reviewreply($data){
		if(isset($this->session->data['user_id'])){
			$adminlogin_id = $this->session->data['user_id'];
			$this->db->query("INSERT INTO " . DB_PREFIX . "review_reply SET review_id = '". $data['review_id'] ."', comment = '". $data['comment'] ."', user_id = '". $this->session->data['user_id'] ."', date_added = NOW()");
		}
	}
	
	public function getadminreply($review_id){
		$query = $this->db->query("SELECT * from " . DB_PREFIX . "review_reply WHERE review_id = '" . (int)$review_id . "'");
		return $query->rows;
	}
	
	public function getverifiespurchased($product_id, $customer_id, $order_status_id){
		if($customer_id > '0'){
			$query = $this->db->query("SELECT * from " . DB_PREFIX . "order o LEFT JOIN " . DB_PREFIX . "order_product op ON o.order_id = op.order_id WHERE o.customer_id = '" . (int)$customer_id . "' AND op.product_id = '". (int)$product_id ."' AND o.order_status_id = '". (int)$order_status_id ."'");
			if ($query->num_rows) {
				return $query->rows;
			}
		}
	}
	
	public function gettotalverifiespurchased($product_id, $customer_id, $order_status_id){
		if($customer_id > '0'){
			$query = $this->db->query("SELECT count(o.customer_id) AS total from " . DB_PREFIX . "order o LEFT JOIN " . DB_PREFIX . "order_product op ON o.order_id = op.order_id WHERE o.customer_id = '" . (int)$customer_id . "' AND op.product_id = '". (int)$product_id ."' AND o.order_status_id = '". (int)$order_status_id ."'");
		
			return $query->row['total'];
		
		}
	}
	
	public function getreports(){
		$query = $this->db->query("SELECT * from " . DB_PREFIX . "reportreasons r LEFT JOIN " . DB_PREFIX . "reportreasons_description rd ON r.reason_id = rd.reason_id WHERE language_id = '". (int) $this->config->get('config_language_id') ."'");
		if ($query->num_rows) {
			return $query->rows;
		}
	}
	
	public function addCoupon($customer_id, $newcoupon) {
		$coupon = $this->config->get('reviewpro_fields');
		$this->load->model('account/customer');
		$currentcustomer = $this->model_account_customer->getCustomer($customer_id);
		$today = date('Y-m-d');
		if($coupon['couponvalidity']){
			$enddate = date('Y-m-d',strtotime("+". $coupon['couponvalidity'] ."day", strtotime($today)));
		}else{
			$enddate = date('Y-m-d',strtotime("+365day", strtotime($today)));
		}
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($coupon['couponname']) . "', code = '" . $newcoupon . "', discount = '" . (float)$coupon[$currentcustomer['customer_group_id']]['coupondiscount'] . "', type = '" . $this->db->escape($coupon[$currentcustomer['customer_group_id']]['type']) . "', total = '" . (float)$coupon['coupontotal_amount'] . "', logged = '1', shipping = '1', uses_total = '" . (int)$coupon['uses_coupon'] . "', uses_customer = '" . (int)$coupon['uses_customer'] . "', status = '1', 	date_start = NOW(), date_end = '". $enddate ."', date_added = NOW()");
		$coupon_id = $this->db->getLastId();
		if (isset($data['couponproducts'])) {
			foreach ($data['couponproducts'] as $product_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
			}
		}
		if (isset($data['couponcategorys'])) {
			foreach ($data['couponcategorys'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_category SET coupon_id = '" . (int)$coupon_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
		return $coupon_id;
	}
	
	public function addpoints($data){
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$data['customer_id'] . "', description = '" . $data['desc'] . "', points = '". $data['points'] ."', date_added = NOW()");
	}
	
	public function emailinvitation($order_id, $customer_id) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "reviewpro_invitation SET order_id = '" . $order_id . "', customer_id = '" . $customer_id . "', date_added = NOW(), status = 0");
	}
	
	public function getemailinvitation() {
		
		if($this->config->get('reviewprocj_crownjob')['repeated'] && $this->config->get('reviewprocj_crownjob')['date']){
			$status = 1;
			$date_added = $this->config->get('reviewprocj_crownjob')['date'];
		}
		
		$sql = "SELECT * from " . DB_PREFIX . "reviewpro_invitation WHERE invitation_id > 0";
		
		if(!isset($status)){
			$sql .= " AND status = 0";
		}
		
		if(isset($date_added)){
			$sql .= " AND (status = 0) OR (status = 1 AND date_added = cast((now() + interval -". $date_added ." day) as date))";
		}
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows) {
			return $query->rows;
		}
	}
	
	public function updateemailinvitation($invitation_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "reviewpro_invitation SET status = 1, date_added = NOW() WHERE invitation_id = '". $invitation_id ."'");
	}
	
	public function pendingreviews($data){
		$query = $this->db->query("SELECT * from " . DB_PREFIX . "reviewpro WHERE product_id = '". $data ."' AND customer_id = '" . (int)$this->customer->getId() . "'");
		return $query->row;
	}
	
	public function getattachments($review_id){
		$query = $this->db->query("SELECT * from " . DB_PREFIX . "reviewpro_attachments WHERE review_id = '". $review_id ."'");
		return $query->rows;
	}
	
}