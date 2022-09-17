<?php
class ControllerExtensionMstoreAccount extends Controller {
	private $error = array();

	/**
	 * @api {get} /index.php?routing=extension/mstore/account Get user info
	 * @apiVersion 0.1.0
	 * @apiName Get user info
	 * @apiGroup Account
	 *
	 * @apiSuccess {Number} success 1: Success, 0: Fail.
	 * @apiSuccess {Array} error  List error messages.
	 * @apiSuccess {Object} data  User info.
	 * @apiSuccess {String} data.customer_id
	 * @apiSuccess {String} data.customer_group_id
	 * @apiSuccess {String} data.store_id
	 * @apiSuccess {String} data.language_id
	 * @apiSuccess {String} data.firstname
	 * @apiSuccess {String} data.lastname
	 * @apiSuccess {String} data.email
	 * @apiSuccess {String} data.telephone
	 * @apiSuccess {String} data.fax
	 * @apiSuccess {String} data.status
	 * @apiSuccess {String} data.date_added
	 * 
	 */
	public function index() {
		$this->load->language('account/login');
		$this->load->model('account/customer');

		if (!$this->customer->isLogged()) {
			$this->response->addHeader('HTTP/1.0 401 Unauthorized');
			$this->response->setOutput(json_encode(["success"=>0, "error"=>["Please login to get user info"], "data"=>[]]));
		}else{
			$customer = $this->model_account_customer->getCustomer($this->customer->getId());
			$this->response->setOutput(json_encode(["success"=>1, "error"=>[], "data"=>$customer]));
		}
	}

	/**
	 * @api {post} /index.php?routing=extension/mstore/account/login Login
	 * @apiVersion 0.1.0
	 * @apiName Login
	 * @apiGroup Account
	 *
	 * @apiParam {String} email
	 * @apiParam {String} password
	 * 
	 * @apiSuccess {Number} success 1: Success, 0: Fail.
	 * @apiSuccess {Array} error  List error messages.
	 * @apiSuccess {Object} data  User info.
	 * @apiSuccess {String} data.customer_id
	 * @apiSuccess {String} data.customer_group_id
	 * @apiSuccess {String} data.store_id
	 * @apiSuccess {String} data.language_id
	 * @apiSuccess {String} data.firstname
	 * @apiSuccess {String} data.lastname
	 * @apiSuccess {String} data.email
	 * @apiSuccess {String} data.telephone
	 * @apiSuccess {String} data.fax
	 * @apiSuccess {String} data.status
	 * @apiSuccess {String} data.date_added
	 * 
	 */
	public function login() {

		$this->load->language('account/login');
		$this->load->model('account/customer');

		$json = file_get_contents('php://input');
		$params = (array) json_decode($json);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateLogin($params)) {
			$customer = $this->model_account_customer->getCustomer($this->customer->getId());
			$this->response->setOutput(json_encode(["success"=>1, "error"=>[], "data"=>$customer]));
		}else{
			$this->response->addHeader('HTTP/1.0 401 Unauthorized');
			$this->response->setOutput(json_encode(["success"=>0, "error"=>array_values($this->error), "data"=>[]]));
		}
	}

	private function validateLogin($params) {
		// Check how many login attempts have been made.
		$login_info = $this->model_account_customer->getLoginAttempts($params['email']);

		if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
			$this->error['warning'] = $this->language->get('error_attempts');
		}

		// Check if customer has been approved.
		$customer_info = $this->model_account_customer->getCustomerByEmail($params['email']);

		if ($customer_info && !$customer_info['status']) {
			$this->error['warning'] = $this->language->get('error_approved');
		}

		if (!$this->error) {
			if (!$this->customer->login($params['email'], $params['password'])) {
				$this->error['warning'] = $this->language->get('error_login');

				$this->model_account_customer->addLoginAttempt($params['email']);
			} else {
				$this->model_account_customer->deleteLoginAttempts($params['email']);
			}
		}

		return !$this->error;
	}

	/**
	 * @api {post} /index.php?routing=extension/mstore/account/register Register
	 * @apiVersion 0.1.0
	 * @apiName Register
	 * @apiGroup Account
	 *
	 * @apiParam {String} firstname
	 * @apiParam {String} lastname
	 * @apiParam {String} email
	 * @apiParam {String} telephone
	 * @apiParam {String} password
	 * @apiParam {String} confirm
	 * 
	 * @apiSuccess {Number} success 1: Success, 0: Fail.
	 * @apiSuccess {Array} error  List error messages.
	 * @apiSuccess {Object} data  User info.
	 * @apiSuccess {String} data.customer_id
	 * @apiSuccess {String} data.customer_group_id
	 * @apiSuccess {String} data.store_id
	 * @apiSuccess {String} data.language_id
	 * @apiSuccess {String} data.firstname
	 * @apiSuccess {String} data.lastname
	 * @apiSuccess {String} data.email
	 * @apiSuccess {String} data.telephone
	 * @apiSuccess {String} data.fax
	 * @apiSuccess {String} data.status
	 * @apiSuccess {String} data.date_added
	 * 
	 */
	public function register() {

		$this->load->language('account/register');
		$this->load->model('account/customer');

		$json = file_get_contents('php://input');
		$params = json_decode($json);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateRegister($params)) {
			$customer_id = $this->model_account_customer->addCustomer((array) $params);
			if ($this->validateLogin((array)$params)) {
				$customer = $this->model_account_customer->getCustomer($this->customer->getId());
				$this->response->setOutput(json_encode(["success"=>1, "error"=>[], "data"=>$customer]));
			}else{
				$this->response->addHeader('HTTP/1.0 400 Bad Request');
				$this->response->setOutput(json_encode(["success"=>0, "error"=>["Register fail"], "data"=>[]]));
			}
		}else{
			$this->response->addHeader('HTTP/1.0 400 Bad Request');
			$this->response->setOutput(json_encode(["success"=>0, "error"=>array_values($this->error), "data"=>[]]));
		}
	}

	private function validateRegister($params) {
		if (!isset($params->firstname) || (utf8_strlen(trim($params->firstname)) < 1) || (utf8_strlen(trim($params->firstname)) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}
		
		if (!isset($params->lastname) || (utf8_strlen(trim($params->lastname)) < 1) || (utf8_strlen(trim($params->lastname)) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if (!isset($params->telephone) || (utf8_strlen(trim($params->telephone)) < 1) || (utf8_strlen(trim($params->telephone)) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		if (!isset($params->email) || (utf8_strlen($params->email) > 96) || !filter_var($params->email, FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}elseif ($this->model_account_customer->getTotalCustomersByEmail($params->email)) {
			$this->error['warning'] = $this->language->get('error_exists');
		}

		if (!isset($params->password) || (utf8_strlen(html_entity_decode($params->password, ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($params->password, ENT_QUOTES, 'UTF-8')) > 40)) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!isset($params->confirm) || $params->confirm != $params->password) {
			$this->error['confirm'] = $this->language->get('error_confirm');
		}
		
		return !$this->error;
	}
}