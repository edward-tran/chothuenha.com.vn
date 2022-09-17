<?php
class ControllerExtensionReviewpro extends Controller {
	public function index() {
		
		$this->load->language('extension/reviewpro');
		
		$this->load->model('extension/reviewpro');
		$this->load->model('tool/upload');
		$this->load->model('tool/image');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->config->get('reviewpro_fields')[$this->config->get('config_language_id')]['title'],
			'href' => $this->url->link('extension/reviewpro')
		);
		
		$this->document->setTitle($this->config->get('reviewpro_fields')[$this->config->get('config_language_id')]['meta_title']);
		$this->document->setDescription($this->config->get('reviewpro_fields')[$this->config->get('config_language_id')]['meta_description']);
		$this->document->setKeywords($this->config->get('reviewpro_fields')[$this->config->get('config_language_id')]['meta_keyword']);
		
		$data['theme_default_directory'] = $this->config->get('theme_default_directory');
		$data['heading_title'] = $this->config->get('reviewpro_fields')[$this->config->get('config_language_id')]['title'];
		$data['page_description'] = html_entity_decode($this->config->get('reviewpro_fields')[$this->config->get('config_language_id')]['description']);
		$data['text_sort'] = $this->language->get('text_sort');
		$data['text_limit'] = $this->language->get('text_limit');
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		}else{
			$sort = 'DESC';
		}
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		}else{
			$limit = 20;
		}
		
		$data['sort'] = $sort;
		$data['limit'] = $limit;
		
		$url = '';
	
		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
		if (isset($this->request->get['review_id'])) {
			$url .= '&review_id=' . $this->request->get['review_id'];
		}
		if (isset($this->request->get['product_id'])) {
			$url .= '&product_id=' . $this->request->get['product_id'];
		}
		
		$data['sorts'] = array();
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_descending'),
			'value' => 'DSC',
			'href'  => $this->url->link('extension/reviewpro', '&sort=DSC' . $url)
		);
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_asc'),
			'value' => 'ASC',
			'href'  => $this->url->link('extension/reviewpro', '&sort=ASC' . $url)
		);
		
		$data['limits'] = array();

		$limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));
		sort($limits);
		foreach($limits as $value) {
			$data['limits'][] = array(
				'text'  => $value,
				'value' => $value,
				'href'  => $this->url->link('extension/reviewpro', $url . '&limit=' . $value)
			);
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if(isset($this->request->get['review_id'])){
			$review_id = $this->request->get['review_id'];
		}
		
		if(isset($this->request->get['product_id'])){
			$product_id = $this->request->get['product_id'];
		}
		
		$filter_data = array(
			'review_id'  => isset($review_id) ? $review_id : '',
			'product_id'  => isset($product_id) ? $product_id : '',
			'sort'  => $sort,
			'limit' => $limit,
			'start' => ($page - 1) * $limit,
		);
		
		
		$results = $this->model_extension_reviewpro->getReviews($filter_data);
	
		$totalreview = $this->model_extension_reviewpro->getTotalReview($filter_data);
		
		
		$data['reviewpro_fields'] = $this->config->get('reviewpro_fields');
		
		if(isset($this->session->data['user_id'])){
			$data['adminlogin'] = $this->session->data['user_id'];
		}else{
			$data['adminlogin'] = '';
		}
		
		$data['textadmin'] = $this->config->get('reviewpro_fields')['overall'][$this->config->get('config_language_id')];
		
		$data['purchaseverify'] = $this->config->get('reviewpro_fields')['purchase'][$this->config->get('config_language_id')];
		$data['votingtext'] = $this->config->get('reviewpro_fields')['vote'][$this->config->get('config_language_id')];
		$data['reporttext'] = $this->config->get('reviewpro_fields')['report'][$this->config->get('config_language_id')];
		$order_status_id = $this->config->get('reviewpro_fields')['order_status_id'];
		
		$data['votetext'] = $this->config->get('reviewpro_fields')[$this->config->get('config_language_id')];
		
		if ($this->config->get('reviewpro_fields')['guestreview']) {
			$data['guestreview'] = true;
		}elseif($this->customer->isLogged() && !$this->config->get('reviewpro_fields')['guestreview']){
			$data['guestreview'] = true;
		}else{
			$data['guestreview'] = false;
		}
		
		if ($this->config->get('reviewpro_fields')['reviewabuseguest']) {
			$data['reviewabuseguest'] = true;
		}elseif($this->customer->isLogged() && !$this->config->get('reviewpro_fields')['reviewabuseguest']){
			$data['reviewabuseguest'] = true;
		}else{
			$data['reviewabuseguest'] = false;
		}
		
		if ($this->config->get('reviewpro_fields')['guestvote']) {
			$data['guestvote'] = true;
		}elseif($this->customer->isLogged() && !$this->config->get('reviewpro_fields')['guestvote']){
			$data['guestvote'] = true;
		}else{
			$data['guestvote'] = false;
		}
		
		if($this->config->get('reviewpro_fields')['noofreview']){
			$noofreview = $this->config->get('reviewpro_fields')['noofreview'];
		}else{
			$noofreview = 5;
		}
		
		if($this->config->get('reviewpro_fields')['reviewperrow']){
			$data['reviewperrow'] = $this->config->get('reviewpro_fields')['reviewperrow'];
		}else{
			$data['reviewperrow'] = 1;
		}

		$data['reviews'] = array();
		
		foreach ($results as $result) {
			$rating = array();
			$replies = array();
			$getreviewrating = $this->model_extension_reviewpro->getreviewrating($result['review_id']);
			$total_yesvote = $this->model_extension_reviewpro->getyesvote($result['review_id']);
			$total_novote = $this->model_extension_reviewpro->getnovote($result['review_id']);
			$getadminreply = $this->model_extension_reviewpro->getadminreply($result['review_id']);
			$data['getreports'] = $this->model_extension_reviewpro->getreports();
			$getverifiespurchased = $this->model_extension_reviewpro->getverifiespurchased($result['product_id'], $result['customer_id'],$order_status_id);
			$data['rating_desc'] = $this->model_extension_reviewpro->getratinglist();
			$attachments = $this->model_extension_reviewpro->getattachments($result['review_id']);
			$attachfiles = array();
			foreach($attachments as $attachment){
				$attachfiles[] = $this->model_tool_upload->getUploadByCode($attachment['file']);
			}
			
			if ($this->request->server['HTTPS']) {
				$server = $this->config->get('config_ssl');
			} else {
				$server = $this->config->get('config_url');
			}
			
			$thumb = '';
			$popup = '';
			$attachfile = '';
			$attachmentfiles = array();
			if($attachfiles){
				foreach($attachfiles as $attachfile){
					$filename = explode(".", $attachfile['name']);
					$filename = end($filename);
					if($filename == 'jpg' || $filename == 'png' || $filename == 'gif' || $filename == 'jpeg'){
						if(is_file(DIR_IMAGE.'reviewmanager/'.$attachfile['filename'])){
							$imagelink = 'reviewmanager/'.$attachfile['filename'];
							$thumb = $this->model_tool_image->resize($imagelink, $this->config->get('reviewpro_fields')['imagethumbwidth'], $this->config->get('reviewpro_fields')['imagethumbheight']);
							$popup = $this->model_tool_image->resize($imagelink, $this->config->get('reviewpro_fields')['imagepopupwidth'], $this->config->get('reviewpro_fields')['imagepopupheight']);
						}
					}else{
						$thumb = '';
						$popup = '';
					}
					
					$code = $attachfile['code'];
					
					$attachmentfiles[] = array(
						'thumb' => $thumb,
						'popup' => $popup,
						'file' => $code
					);
				}
			}
			
			if($getreviewrating){
				foreach($getreviewrating as $value){
					$rating[$value['rating_id']] = array(
						'rating'     => $value['rating'],
					);
				}
			}
			if($getadminreply){
				$i = 0;
				foreach($getadminreply as $value){
					$adminreply[$result['review_id']][] =array(
						'comment' => $value['comment'],
						'date' => date($this->language->get('date_format_short'), strtotime($value['date_added']))
					);
					$i++;
				}
				$data['totaladminreply'][$result['review_id']] = $i;
			}
			
			$data['reviews'][] = array(
				'author'     => $result['name'],
				'attachmentfiles'     => isset($attachmentfiles) ? $attachmentfiles : '',
				'verifyreview'     => isset($getverifiespurchased) ? $getverifiespurchased : '',
				'review_id'  => $result['review_id'],
				'title'      => nl2br($result['title']),
				'text'       => nl2br($result['comment']),
				'rating'     => $rating,
				'replies'     => isset($adminreply) ? $adminreply : '',
				'yesvotes'     => $total_yesvote,
				'novotes'     => $total_novote,
				'share'       => $this->url->link('extension/reviewpro', 'review_id=' . (int)$result['review_id']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}
		
		$pagination = new Pagination();
		$pagination->total = $totalreview;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('extension/reviewpro', $url . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($totalreview) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($totalreview - $limit)) ? $totalreview : ((($page - 1) * $limit) + $limit), $totalreview, ceil($totalreview / $limit));
		
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/reviewpro', $data));
	}
	
	public function reviewpro() {
		$this->load->language('product/product');
		$this->load->language('extension/reviewpro');
		$this->load->model('extension/reviewpro');
		$this->load->model('tool/upload');
		$this->load->model('tool/image');

		$data['reviewpro_fields'] = $this->config->get('reviewpro_fields');
		
		if(isset($this->session->data['user_id'])){
			$data['adminlogin'] = $this->session->data['user_id'];
		}else{
			$data['adminlogin'] = '';
		}
		
		$data['textadmin'] = $this->config->get('reviewpro_fields')['overall'][$this->config->get('config_language_id')];
		
		$data['purchaseverify'] = $this->config->get('reviewpro_fields')['purchase'][$this->config->get('config_language_id')];
		$data['votingtext'] = $this->config->get('reviewpro_fields')['vote'][$this->config->get('config_language_id')];
		$data['reporttext'] = $this->config->get('reviewpro_fields')['report'][$this->config->get('config_language_id')];
		$order_status_id = $this->config->get('reviewpro_fields')['order_status_id'];
		
		$data['votetext'] = $this->config->get('reviewpro_fields')[$this->config->get('config_language_id')];
		
		if ($this->config->get('reviewpro_fields')['guestreview']) {
			$data['guestreview'] = true;
		}elseif($this->customer->isLogged() && !$this->config->get('reviewpro_fields')['guestreview']){
			$data['guestreview'] = true;
		}else{
			$data['guestreview'] = false;
		}
		
		if ($this->config->get('reviewpro_fields')['reviewabuseguest']) {
			$data['reviewabuseguest'] = true;
		}elseif($this->customer->isLogged() && !$this->config->get('reviewpro_fields')['reviewabuseguest']){
			$data['reviewabuseguest'] = true;
		}else{
			$data['reviewabuseguest'] = false;
		}
		
		if ($this->config->get('reviewpro_fields')['guestvote']) {
			$data['guestvote'] = true;
		}elseif($this->customer->isLogged() && !$this->config->get('reviewpro_fields')['guestvote']){
			$data['guestvote'] = true;
		}else{
			$data['guestvote'] = false;
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['rating_id'])) {
			$rating_id = $this->request->get['rating_id'];
		}else{
			$rating_id = '0';
		}
		
		if (isset($this->request->get['ratings'])) {
			$ratings = $this->request->get['ratings'];
		}else{
			$ratings = '0';
		}
		
		if($this->config->get('reviewpro_fields')['noofreview']){
			$noofreview = $this->config->get('reviewpro_fields')['noofreview'];
		}else{
			$noofreview = 5;
		}
		
		$url = '';
		
		if (isset($this->request->get['ratings'])) {
			$url .= '&ratings=' . $this->request->get['ratings'];
		}
		
		if (isset($this->request->get['rating_id'])) {
			$url .= '&rating_id=' . $this->request->get['rating_id'];
		}
		
		$filter_data = array(
			'ratings'  => $ratings,
			'rating_id'  => $rating_id,
			'limit' => $noofreview,
			'start' => ($page - 1) * $noofreview,
		);
		
		$data['reviews'] = array();

		$review_total = $this->model_extension_reviewpro->getTotalReviewsByProductId($this->request->get['product_id']);
		
		$results = $this->model_extension_reviewpro->getReviewsByProductId($this->request->get['product_id'], $filter_data);
	
		$totalverifiespurchased = 0;
		foreach ($results as $result) {
			$rating = array();
			$replies = array();
			$getreviewrating = $this->model_extension_reviewpro->getreviewrating($result['review_id']);
			$total_yesvote = $this->model_extension_reviewpro->getyesvote($result['review_id']);
			$total_novote = $this->model_extension_reviewpro->getnovote($result['review_id']);
			$getadminreply = $this->model_extension_reviewpro->getadminreply($result['review_id']);
			$data['getreports'] = $this->model_extension_reviewpro->getreports();
			$getverifiespurchased = $this->model_extension_reviewpro->getverifiespurchased($this->request->get['product_id'], $result['customer_id'],$order_status_id);
			
			$data['rating_desc'] = $this->model_extension_reviewpro->getratinglist();
			$attachments = $this->model_extension_reviewpro->getattachments($result['review_id']);
			$attachfiles = array();
			foreach($attachments as $attachment){
				$attachfiles[] = $this->model_tool_upload->getUploadByCode($attachment['file']);
			}
			
			if ($this->request->server['HTTPS']) {
				$server = $this->config->get('config_ssl');
			} else {
				$server = $this->config->get('config_url');
			}
			
			$thumb = '';
			$popup = '';
			$attachfile = '';
			$attachmentfiles = array();
			if(isset($attachfiles)){
				foreach($attachfiles as $attachfile){
					$filename = explode(".", $attachfile['name']);
					$filename = end($filename);
					if($filename == 'jpg' || $filename == 'png' || $filename == 'gif' || $filename == 'jpeg'){
						if(is_file(DIR_IMAGE.'reviewmanager/'.$attachfile['filename'])){
							$imagelink = 'reviewmanager/'.$attachfile['filename'];
							$thumb = $this->model_tool_image->resize($imagelink, $this->config->get('reviewpro_fields')['imagethumbwidth'], $this->config->get('reviewpro_fields')['imagethumbheight']);
							$popup = $this->model_tool_image->resize($imagelink, $this->config->get('reviewpro_fields')['imagepopupwidth'], $this->config->get('reviewpro_fields')['imagepopupheight']);
						}
					}else{
						$thumb = '';
						$popup = '';
					}
					
					$code = $attachfile['code'];
					$attachmentfiles[] = array(
						'thumb' => $thumb,
						'popup' => $popup,
						'file' => $code,
					);
				}
				
			}
			//echo '<pre>';
			//print_r($attachmentfiles);
			if($getreviewrating){
				foreach($getreviewrating as $value){
					$rating[$value['rating_id']] = array(
						'rating'     => $value['rating'],
					);
				}
			}
			if($getadminreply){
				$i = 0;
				foreach($getadminreply as $value){
					$adminreply[$result['review_id']][] =array(
						'comment' => $value['comment'],
						'date' => date($this->language->get('date_format_short'), strtotime($value['date_added']))
					);
					$i++;
				}
				$data['totaladminreply'][$result['review_id']] = $i;
			}
			
			$data['featured'] = isset($result['featured']) ? $result['featured'] : '';
			
			if(!empty($this->request->get['ratings']) && $this->request->get['ratings'] == '3'){
				if($getverifiespurchased){
					$totalverifiespurchased++;
					$data['reviews'][] = array(
						'author'     => $result['name'],
						'attachmentfiles'     => isset($attachmentfiles) ? $attachmentfiles : array(),
						'verifyreview'     => isset($getverifiespurchased) ? $getverifiespurchased : '',
						'review_id'  => $result['review_id'],
						'title'      => nl2br($result['title']),
						'text'       => nl2br($result['comment']),
						'rating'     => $rating,
						'replies'     => isset($adminreply) ? $adminreply : '',
						'yesvotes'     => $total_yesvote,
						'novotes'     => $total_novote,
						'share'       => $this->url->link('extension/reviewpro', 'review_id=' . (int)$result['review_id']),
						'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
					);
				}
			}else{
				$data['reviews'][] = array(
					'author'     => $result['name'],
					'attachmentfiles'     => isset($attachmentfiles) ? $attachmentfiles : array(),
					'verifyreview'     => isset($getverifiespurchased) ? $getverifiespurchased : '',
					'review_id'  => $result['review_id'],
					'title'      => nl2br($result['title']),
					'text'       => nl2br($result['comment']),
					'rating'     => $rating,
					'replies'     => isset($adminreply) ? $adminreply : '',
					'yesvotes'     => $total_yesvote,
					'novotes'     => $total_novote,
					'share'       => $this->url->link('extension/reviewpro', 'review_id=' . (int)$result['review_id']),
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
				);
				
			}
			
			
		}
		if(!empty($this->request->get['ratings']) && $this->request->get['ratings'] == '3'){
			if($totalverifiespurchased){
				$review_total = $totalverifiespurchased;
			}else{
				$review_total = $review_total;
			}
		}
		
	
		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = $noofreview;
		$pagination->url = $this->url->link('extension/reviewpro/reviewpro', 'product_id=' . $this->request->get['product_id'] . $url. '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * $noofreview) + 1 : 0, ((($page - 1) * $noofreview) > ($review_total - $noofreview)) ? $review_total : ((($page - 1) * $noofreview) + $noofreview), $review_total, ceil($review_total / $noofreview));

		$this->response->setOutput($this->load->view('extension/reviewprolist', $data));
	}
	
	public function featuredreviewpro() {
		$this->load->language('product/product');
		$this->load->language('extension/reviewpro');
		$this->load->model('extension/reviewpro');
		$this->load->model('tool/upload');
		$this->load->model('tool/image');

		$data['reviewpro_fields'] = $this->config->get('reviewpro_fields');
		$data['adminlogin'] = '';
		
		$data['textadmin'] = $this->config->get('reviewpro_fields')['overall'][$this->config->get('config_language_id')];
		
		$data['purchaseverify'] = $this->config->get('reviewpro_fields')['purchase'][$this->config->get('config_language_id')];
		$data['votingtext'] = $this->config->get('reviewpro_fields')['vote'][$this->config->get('config_language_id')];
		$data['reporttext'] = $this->config->get('reviewpro_fields')['report'][$this->config->get('config_language_id')];
		$order_status_id = $this->config->get('reviewpro_fields')['order_status_id'];
		
		$data['votetext'] = $this->config->get('reviewpro_fields')[$this->config->get('config_language_id')];
		
		if ($this->config->get('reviewpro_fields')['guestreview']) {
			$data['guestreview'] = true;
		}elseif($this->customer->isLogged() && !$this->config->get('reviewpro_fields')['guestreview']){
			$data['guestreview'] = true;
		}else{
			$data['guestreview'] = false;
		}
		
		if ($this->config->get('reviewpro_fields')['reviewabuseguest']) {
			$data['reviewabuseguest'] = true;
		}elseif($this->customer->isLogged() && !$this->config->get('reviewpro_fields')['reviewabuseguest']){
			$data['reviewabuseguest'] = true;
		}else{
			$data['reviewabuseguest'] = false;
		}
		
		if ($this->config->get('reviewpro_fields')['guestvote']) {
			$data['guestvote'] = true;
		}elseif($this->customer->isLogged() && !$this->config->get('reviewpro_fields')['guestvote']){
			$data['guestvote'] = true;
		}else{
			$data['guestvote'] = false;
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if($this->config->get('reviewpro_fields')['noofreview']){
			$noofreview = $this->config->get('reviewpro_fields')['noofreview'];
		}else{
			$noofreview = 5;
		}
		
		$filter_data = array(
			'limit' => $noofreview,
			'start' => ($page - 1) * $noofreview,
		);
		
		$data['reviews'] = array();

		$review_total = $this->model_extension_reviewpro->getTotalfeaturedReviewsByProductId($this->request->get['product_id']);

		$results = $this->model_extension_reviewpro->getfeaturedReviewsByProductId($this->request->get['product_id'], $filter_data);
		
		foreach ($results as $result) {
			$rating = array();
			$replies = array();
			$getreviewrating = $this->model_extension_reviewpro->getreviewrating($result['review_id']);
			$total_yesvote = $this->model_extension_reviewpro->getyesvote($result['review_id']);
			$total_novote = $this->model_extension_reviewpro->getnovote($result['review_id']);
			$getadminreply = $this->model_extension_reviewpro->getadminreply($result['review_id']);
			$data['getreports'] = $this->model_extension_reviewpro->getreports();
			$getverifiespurchased = $this->model_extension_reviewpro->getverifiespurchased($this->request->get['product_id'], $result['customer_id'],$order_status_id);
			$data['rating_desc'] = $this->model_extension_reviewpro->getratinglist();
			$attachfiles = array();
			foreach($attachments as $attachment){
				$attachfiles[] = $this->model_tool_upload->getUploadByCode($attachment['file']);
			}
			if ($this->request->server['HTTPS']) {
				$server = $this->config->get('config_ssl');
			} else {
				$server = $this->config->get('config_url');
			}
			
			$data['featured'] = isset($result['featured']) ? $result['featured'] : '';
			
			$thumb = '';
			$popup = '';
			$attachfile = '';
			$attachmentfiles = array();
			if(isset($attachfiles)){
				foreach($attachfiles as $attachfile){
					$filename = explode(".", $attachfile['name']);
					$filename = end($filename);
					if($filename == 'jpg' || $filename == 'png' || $filename == 'gif' || $filename == 'jpeg'){
						if(is_file(DIR_IMAGE.'reviewmanager/'.$attachfile['filename'])){
							$imagelink = 'reviewmanager/'.$attachfile['filename'];
							$thumb = $this->model_tool_image->resize($imagelink, $this->config->get('reviewpro_fields')['imagethumbwidth'], $this->config->get('reviewpro_fields')['imagethumbheight']);
							$popup = $this->model_tool_image->resize($imagelink, $this->config->get('reviewpro_fields')['imagepopupwidth'], $this->config->get('reviewpro_fields')['imagepopupheight']);
						}
					}else{
						$thumb = '';
						$popup = '';
					}
					
					$code = $attachfile['code'];
					$attachmentfiles[] = array(
						'thumb' => $thumb,
						'popup' => $popup,
						'file' => $code,
					);
				}
				
			}
			
			if($getreviewrating){
				foreach($getreviewrating as $value){
					$rating[$value['rating_id']] = array(
						'rating'     => $value['rating'],
					);
				}
			}
			/* if($getadminreply){
				$i = 0;
				foreach($getadminreply as $value){
					$adminreply[$result['review_id']][] =array(
						'comment' => $value['comment'],
						'date' => date($this->language->get('date_format_short'), strtotime($value['date_added']))
					);
					$i++;
				}
				$data['totaladminreply'][$result['review_id']] = $i;
			} */
			
			$data['reviews'][] = array(
				'author'     => $result['name'],
				'attachmentfiles'     => isset($attachmentfiles) ? $attachmentfiles : '',
				'verifyreview'     => isset($getverifiespurchased) ? $getverifiespurchased : '',
				'review_id'  => $result['review_id'],
				'title'      => nl2br($result['title']),
				'text'       => nl2br($result['comment']),
				'rating'     => $rating,
				'replies'     => isset($adminreply) ? $adminreply : '',
				'yesvotes'     => $total_yesvote,
				'novotes'     => $total_novote,
				'share'       => $this->url->link('extension/reviewpro', 'review_id=' . (int)$result['review_id']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}
		
		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = $noofreview;
		$pagination->url = $this->url->link('extension/reviewpro/reviewpro', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * $noofreview) + 1 : 0, ((($page - 1) * $noofreview) > ($review_total - $noofreview)) ? $review_total : ((($page - 1) * $noofreview) + $noofreview), $review_total, ceil($review_total / $noofreview));

		$this->response->setOutput($this->load->view('extension/featuresreviewprolist', $data));
	}
	
	public function writereviewpro(){
		$this->load->language('product/product');
		$this->load->model('extension/reviewpro');
		
		$json = array();
		
		if($this->config->get('reviewpro_fields')['namefield'] == 2){
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'][1] = $this->config->get('reviewpro_fields')['reviewform'][$this->config->get('config_language_id')]['ename'];
			}
		}
		
		if($this->config->get('reviewpro_fields')['emailfield'] == 2){
			if ((utf8_strlen($this->request->post['email']) < 1) || (utf8_strlen($this->request->post['email']) > 1000) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
				$json['error'][2] = $this->config->get('reviewpro_fields')['reviewform'][$this->config->get('config_language_id')]['eemail'];
			}
		}
		
		if($this->config->get('reviewpro_fields')['titlefield'] == 2){
			if ((utf8_strlen($this->request->post['title']) < 1) || (utf8_strlen($this->request->post['title']) > 150)) {
				$json['error'][3] = $this->config->get('reviewpro_fields')['reviewform'][$this->config->get('config_language_id')]['ertitle'];
			}
		}
		
		if($this->config->get('reviewpro_fields')['commentfield'] == 2){
			if ((utf8_strlen($this->request->post['comment']) < 1) || (utf8_strlen($this->request->post['comment']) > 1000)) {
				$json['error'][4] = $this->config->get('reviewpro_fields')['reviewform'][$this->config->get('config_language_id')]['ecomment'];
			}
		}
		
		if($this->config->get('reviewpro_fields')['ratingreq'] == 2){
			$ratinglists = $this->model_extension_reviewpro->getratinglist();
			foreach($ratinglists as $ratinglist){
				if(empty($this->request->post['rating'][$ratinglist['rating_id']])){
					$json['error'][6] = $this->config->get('reviewpro_fields')['reviewform'][$this->config->get('config_language_id')]['erating'];
				}
			}
		}
		
		if($this->config->get('reviewpro_fields')['capcha']){
			if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');
				if ($captcha) {
					$json['error']['captcha'] = $this->config->get('reviewpro_fields')['reviewform'][$this->config->get('config_language_id')]['ecapcha'];
				}
			}
		}
		
		if (!isset($json['error'])) {
			$this->model_extension_reviewpro->addreview($this->request->post);
			$json['success'] = html_entity_decode($this->config->get('reviewpro_fields')['reviewform'][$this->config->get('config_language_id')]['successmsg']);
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function reviewvote(){
		$this->load->model('extension/reviewpro');
		$json = array();
		$review_id = $this->request->get['review_id'];
		$helpful = $this->request->get['helpful'];
		
		if(isset($_COOKIE['vote_'.$review_id])) {
			$json['error'] = $this->config->get('reviewpro_fields')['vote'][$this->config->get('config_language_id')]['alreadyvote'];
		}
		if (!isset($json['error'])) {
			$this->model_extension_reviewpro->addvoting($this->request->get);
			setcookie('vote_'.$review_id, 1);
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function abuse(){
		$this->load->model('extension/reviewpro');
		$json = array();
		
		$review_id = $this->request->post['review_id'];
		
		if(isset($_COOKIE['abused_'.$review_id])) {
			$json['error']['alreadyvote'] = $this->config->get('reviewpro_fields')['report'][$this->config->get('config_language_id')]['alreadyreport'];
		}
		
		if($this->config->get('reviewpro_fields')['selectreason'] == 2 && empty($this->request->post['abuse_reason'])){
			$json['error'][1] = $this->config->get('reviewpro_fields')['report'][$this->config->get('config_language_id')]['reviewtitle'];
		}
		
		if($this->config->get('reviewpro_fields')['reportcomment'] == 2){
			if ((utf8_strlen($this->request->post['comment']) < 1) || (utf8_strlen($this->request->post['comment']) > 150)) {
				$json['error'][2] = $this->config->get('reviewpro_fields')['report'][$this->config->get('config_language_id')]['commentfield'];
			}
		}
		
		if (!isset($json['error'])) {
			$this->model_extension_reviewpro->addabused($this->request->post);
			setcookie('abused_'.$review_id, 1);
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function reviewreply(){
		$this->load->model('extension/reviewpro');
		$json = array();
		
		if(!$this->request->post['comment']) {
			$json['error'] = 'Comment Empty';
		}
		
		if (!isset($json['error'])) {
			$this->model_extension_reviewpro->reviewreply($this->request->post);
		}
		$json['success'] = $this->language->get('button_continue');
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function emailinvitation(){
		if($this->config->get('reviewpro_fields')['status'] && $this->config->get('reviewprocj_crownjob')['status']){
			$this->load->model('extension/reviewpro');
			$this->load->model('account/customer');
			$this->load->model('checkout/order');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			$order_status_ids = $this->config->get('reviewprocj_orderstatus');
			$results = $this->model_extension_reviewpro->getemailinvitation();
			
			if($results){
			foreach($results as $value){
				$this->model_extension_reviewpro->updateemailinvitation($value['invitation_id']);
				if(isset($value['customer_id'])){
					$customers = $this->model_account_customer->getCustomer($value['customer_id']);
					$customeremail = $customers['email'];
					$customerfirstname = $customers['firstname'];
					$customerlastname = $customers['lastname'];
				}else{
					$customeremail = '';
					$customerfirstname = '';
					$customerlastname = '';
				}
				$getorder_status_id = $this->model_checkout_order->getOrder($value['order_id'])['order_status_id'];
				$data['products'] = array();
				$getOrderProducts = $this->model_checkout_order->getOrderProducts($value['order_id']);
				foreach($getOrderProducts as $product){
					$result = $this->model_catalog_product->getProduct($product['product_id']);
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], 100, 100);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', 100, 100);
					}

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

					if ((float)$result['special']) {
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
					} else {
						$tax = false;
					}
					
					$data['products'][] = array(
						'product_id'  => $result['product_id'],
						'thumb'       => $image,
						'name'        => $result['name'],
						'price'       => $price,
						'href'        => $this->url->link('product/product', '&product_id=' . $result['product_id'])
					);
				}
				$productemail = $this->load->view('extension/reviewemailproduct', $data);
				$subject = $this->config->get('reviewprocj_crownjob')[$this->config->get('config_language_id')]['subject'];
				$tempmessage = $this->config->get('reviewprocj_crownjob')[$this->config->get('config_language_id')]['message'];
				$store = $this->config->get('config_name');
				$logo = '<img src="'.$this->config->get('config_url') . 'image/' . $this->config->get('config_logo').'"/>';
				$find_shortcode = array(
					'{firstname}',
					'{lastname}',
					'{email}',
					'{store}',
					'{logo}',
					'{products}',
				);
				$replace_shotcode = array(
					$customerfirstname,
					$customerlastname,
					$customeremail,
					$store,
					$logo,
					$productemail,
				);
				$message = str_replace($find_shortcode, $replace_shotcode, $tempmessage);
				if(in_array($getorder_status_id,$order_status_ids) && $customeremail){
					$this->model_extension_reviewpro->updateemailinvitation($value['invitation_id']);
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
			}
		}
	}
	
	public function reviewfileupload() {
		$this->load->language('tool/upload');

		$json = array();

		if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
			// Sanitize the filename
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));

			// Validate the filename length
			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
				$json['error'] = $this->language->get('error_filename');
			}

			// Allowed file extension types
			$allowed = array();

			$extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('reviewpro_fields')['imageformat']);

			$filetypes = explode("\n", $extension_allowed);
		
			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}
			
			if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Allowed file mime types
			$allowed = array();

			$mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));

			$filetypes = explode("\n", $mime_allowed);

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array($this->request->files['file']['type'], $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Check to see if any PHP files are trying to be uploaded
			$content = file_get_contents($this->request->files['file']['tmp_name']);

			if (preg_match('/\<\?php/i', $content)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Return any upload error
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}

		if (!$json) {
			$file = token(32) . '.' . $filename;
			$imagemanager = DIR_IMAGE.'reviewmanager/';
			if (!is_dir($imagemanager)) {
				mkdir($imagemanager, 0777);
			}
			move_uploaded_file($this->request->files['file']['tmp_name'], $imagemanager . $file);

			// Hide the uploaded file name so people can not link to it directly.
			$this->load->model('tool/upload');

			$json['code'] = $this->model_tool_upload->addUpload($filename, $file);
			
			
			if(strtolower(substr(strrchr($filename, '.'), 1)) == 'zip'){
				$json['image'] = $filename;
				$json['href'] = HTTP_SERVER . 'image/reviewmanager/'.$file;
			}else{
				$json['image'] = HTTP_SERVER . 'image/reviewmanager/'.$file;
				$json['href'] = '';
			}
			$json['type'] = strtolower(substr(strrchr($filename, '.'), 1));
			
			$json['success'] = $this->language->get('text_upload');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function downloadattachment() {
		$json = array();
		$this->load->model('tool/upload');
		if (isset($this->request->get['review_id'])) {
			$review_id = $this->request->get['review_id'];
		} else {
			$review_id = 0;
		}
		$download_info = $this->model_tool_upload->getUploadByCode($review_id);
		if ($download_info) {
			$file = DIR_IMAGE . 'reviewmanager/'.$download_info['filename'];
			if(is_file($file)){
				$json['download'] = $this->url->link('extension/reviewpro/downloadfile&fname='.$download_info['filename']);
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function downloadfile() {
		if($this->request->get['fname']){
			$file = DIR_IMAGE . 'reviewmanager/'.$this->request->get['fname'];
			if(is_file($file)){
				header('Content-Type: application/octet-stream'); 
				header('Content-Disposition: attachment;filename='.basename($file)); 
				header('Cache-Control: max-age=0'); 
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));
				readfile($file, 'rb');
			}
			exit();
		}
	}
}