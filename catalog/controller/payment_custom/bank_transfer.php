<?php
class ControllerPaymentCustomBankTransfer extends Controller {
	public function index() {
		$this->load->language('extension/payment/bank_transfer');
		$this->document->addStyle('catalog/view/theme/beta/css/post_project.css?'.rand());
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['payment_bank_transfer_bank'] = html_entity_decode($this->config->get('payment_bank_transfer_bank' . $this->config->get('config_language_id')), ENT_QUOTES, 'UTF-8');
		$data['menu_left_profile'] = $this->load->controller('account/menu_left_profile');
		$data['menu_right_profile'] = $this->load->controller('account/menu_right_profile');
		$data['list_payment'] = $this->load->controller('payment_custom/list_payment');

		$this->response->setOutput($this->load->view('payment_custom/bank_transfer', $data));
	}

	
}