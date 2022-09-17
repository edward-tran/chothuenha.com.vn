<?php
class ControllerExtensionReviewpropopup extends Controller {
	public function index() {
		if($this->config->get('reviewpro_fields')['showpopup'] && $this->customer->isLogged()){
			$this->load->model('account/order');
			$this->load->model('extension/reviewpro');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			
			$data['reviewpro_fields'] = $this->config->get('reviewpro_fields');
			$data['entry_attachment'] = $this->language->get('entry_attachment');
			$data['reviewform'] = $this->config->get('reviewpro_fields')['reviewform'][$this->config->get('config_language_id')];
			$data['ratinglists'] = $this->model_extension_reviewpro->getratinglist();
			$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
			$data['customer_email'] = $this->customer->getEmail();
			
			$data['thankyoumessage'] = html_entity_decode($this->config->get('reviewpro_fields')[$this->config->get('config_language_id')]['typopupdesc']);
			$data['popupdesc'] = html_entity_decode($this->config->get('reviewpro_fields')[$this->config->get('config_language_id')]['popupdesc']);
			$data['popuptitle'] = $this->config->get('reviewpro_fields')[$this->config->get('config_language_id')]['popuptitle'];
			if ($this->request->server['HTTPS']) {
				$server = $this->config->get('config_ssl');
			} else {
				$server = $this->config->get('config_url');
			}
			if (is_file(DIR_IMAGE . $this->config->get('reviewpro_fields')['popuplogo'])) {
				$data['popuplogo'] = $server . 'image/' . $this->config->get('reviewpro_fields')['popuplogo'];
			} else {
				$data['popuplogo'] = '';
			}
			if (is_file(DIR_IMAGE . $this->config->get('reviewpro_fields')['popupbg'])) {
				$data['popupbg'] = $server . 'image/' . $this->config->get('reviewpro_fields')['popupbg'];
			} else {
				$data['popupbg'] = '';
			}
			
			$getOrders = $this->model_account_order->getOrders();
			$data['products'] = array();
			if($getOrders){
				foreach ($getOrders as $orders){
					$getOrderProducts = $this->model_account_order->getOrderProducts($orders['order_id']);
					
					foreach($getOrderProducts as $products){
						$reviewsbycustomer = $this->model_extension_reviewpro->pendingreviews($products['product_id']);
						if(!$reviewsbycustomer){
							$result = $this->model_catalog_product->getProduct($products['product_id']);
							if($result){
								if ($result['image']) {
									$image = $this->model_tool_image->resize($result['image'], 250, 250);
								} else {
									$image = $this->model_tool_image->resize('placeholder.png', 250, 250);
								}
								$data['products'][] = array(
									'product_id'  => $result['product_id'],
									'thumb'       => $image,
									'name'        => $result['name'],
									'href'        => $this->url->link('product/product', '&product_id=' . $result['product_id'])
								);
							}
						}
					}
				}
			}
			
			if(!isset($_COOKIE['reviewpropopup'])){
				setcookie('reviewpropopup',true);
				if($data['products']){
					$data['products'] = array_map("unserialize", array_unique(array_map("serialize", $data['products'])));
					return $this->load->view('extension/reviewpropopup', $data);
				}
			}
		}
	}
}