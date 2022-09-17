<?php 
class ControllerPaymentCustomListPayment extends Controller {

	public function index() {
		$data = array();

		$data['bank_transfer'] 	= $this->url->link('payment_custom/bank_transfer');
		$data['directpay'] 		= $this->url->link('payment_custom/directpay');
		$data['nganluong'] 		= $this->url->link('payment_custom/nganluong');

		return $this->load->view('payment_custom/list_payment', $data);
	}
}