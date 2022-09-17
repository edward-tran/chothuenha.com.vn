<?php
class ControllerExtensionModuleReviewproFeatured extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/reviewpro_featured');
		$this->load->model('extension/reviewpro');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');
		
		$this->document->setTitle($this->config->get('reviewpro_fields')[$this->config->get('config_language_id')]['meta_title']);
		$this->document->setDescription($this->config->get('reviewpro_fields')[$this->config->get('config_language_id')]['meta_description']);
		$this->document->setKeywords($this->config->get('reviewpro_fields')[$this->config->get('config_language_id')]['meta_keyword']);
		
		$data['reviews'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}
		
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
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		
		$filter_data = array(
			'limit' => $noofreview,
			'start' => ($page - 1) * $noofreview,
		);

		if (!empty($setting['product'])) {
			$products = array_slice($setting['product'], 0, (int)$setting['limit']);

			foreach ($products as $product_id) {
				$review_info = $this->model_extension_reviewpro->getReviewsByProductId($product_id, $filter_data);

				foreach ($review_info as $result) {
					$rating = array();
					$replies = array();
					$getreviewrating = $this->model_extension_reviewpro->getreviewrating($result['review_id']);
					$total_yesvote = $this->model_extension_reviewpro->getyesvote($result['review_id']);
					$total_novote = $this->model_extension_reviewpro->getnovote($result['review_id']);
					$getadminreply = $this->model_extension_reviewpro->getadminreply($result['review_id']);
					$data['getreports'] = $this->model_extension_reviewpro->getreports();
					$getverifiespurchased = $this->model_extension_reviewpro->getverifiespurchased($product_id, $result['customer_id'],$order_status_id);
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
									$thumb = $this->model_tool_image->resize($imagelink, $setting['width'], $setting['height']);
									$popup = $this->model_tool_image->resize($imagelink, $setting['width'], $setting['height']);
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
			}
		}

		if ($data['reviews']) {
			return $this->load->view('extension/module/reviewpro_featured', $data);
		}
	}
}