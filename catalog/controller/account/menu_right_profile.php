<?php 
class ControllerAccountMenuRightProfile extends Controller {
	public function index() {

		$this->load->language('account/customer');
		$this->load->language('account/edit');
		$this->load->model('account/customer');

		$data['edit'] = $this->url->link('account/edit', '', true);
		$data['password'] = $this->url->link('account/password', '', true);

		$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

		$data['firstname'] = $customer_info['firstname'];

		$data['lastname'] = $customer_info['lastname'];

		$data['email'] = $customer_info['email'];

		$data['telephone'] = $customer_info['telephone'];

		return $this->load->view('account/menu_right_profile', $data);
	}
}