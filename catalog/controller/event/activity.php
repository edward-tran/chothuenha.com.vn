<?php
class ControllerEventActivity extends Controller {
	// model/account/customer/addCustomer/after
	public function addCustomer(&$routing, &$args, &$output) {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $output,
				'name'        => $args[0]['firstname'] . ' ' . $args[0]['lastname']
			);

			$this->model_account_activity->addActivity('register', $activity_data);
		}
	}
	
	// model/account/customer/editCustomer/after
	public function editCustomer(&$routing, &$args, &$output) {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('edit', $activity_data);
		}
	}
	
	// model/account/customer/editPassword/after
	public function editPassword(&$routing, &$args, &$output) {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');
			
			if ($this->customer->isLogged()) {
				$activity_data = array(
					'customer_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
				);
	
				$this->model_account_activity->addActivity('password', $activity_data);
			} else {
				$customer_info = $this->model_account_customer->getCustomerByEmail($args[0]);
		
				if ($customer_info) {
					$activity_data = array(
						'customer_id' => $customer_info['customer_id'],
						'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
					);
	
					$this->model_account_activity->addActivity('reset', $activity_data);
				}
			}	
		}
	}

		
	// model/account/customer/deleteLoginAttempts/after
	public function login(&$routing, &$args, &$output) {
		if (isset($this->request->get['routing']) && ($this->request->get['routing'] == 'account/login' || $this->request->get['routing'] == 'checkout/login/save') && $this->config->get('config_customer_activity')) {
			$customer_info = $this->model_account_customer->getCustomerByEmail($args[0]);

			if ($customer_info) {
				$this->load->model('account/activity');
	
				$activity_data = array(
					'customer_id' => $customer_info['customer_id'],
					'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
				);
	
				$this->model_account_activity->addActivity('login', $activity_data);
			}
		}	
	}
	
	// model/account/customer/editCode/after
	public function forgotten(&$routing, &$args, &$output) {
		if (isset($this->request->get['routing']) && $this->request->get['routing'] == 'account/forgotten' && $this->config->get('config_customer_activity')) {
			$this->load->model('account/customer');
			
			$customer_info = $this->model_account_customer->getCustomerByEmail($args[0]);

			if ($customer_info) {
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $customer_info['customer_id'],
					'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
				);

				$this->model_account_activity->addActivity('forgotten', $activity_data);
			}
		}	
	}
	
	// model/account/customer/addTransaction/after
	public function addTransaction(&$routing, &$args, &$output) {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/customer');
			
			$customer_info = $this->model_account_customer->getCustomer($args[0]);

			if ($customer_info) {
				$this->load->model('account/activity');
	
				$activity_data = array(
					'customer_id' => $customer_info['customer_id'],
					'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname'],
					'order_id'    => $args[3]
				);
	
				$this->model_account_activity->addActivity('transaction', $activity_data);
			}
		}
	}	
	
	// model/account/customer/addAffiliate/after
	public function addAffiliate(&$routing, &$args, &$output) {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $output,
				'name'        => $args[1]['firstname'] . ' ' . $args[1]['lastname']
			);

			$this->model_account_activity->addActivity('affiliate_add', $activity_data);
		}
	}	
	
	// model/account/customer/editAffiliate/after
	public function editAffiliate(&$routing, &$args, &$output) {
		if ($this->config->get('config_customer_activity') && $output) {
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('affiliate_edit', $activity_data);
		}
	}
	
	// model/account/address/addAddress/after
	public function addAddress(&$routing, &$args, &$output) { 
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('address_add', $activity_data);
		}	
	}
	
	// model/account/address/editAddress/after
	public function editAddress(&$routing, &$args, &$output) { 
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('address_edit', $activity_data);
		}	
	}
	
	// model/account/address/deleteAddress/after
	public function deleteAddress(&$routing, &$args, &$output) {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);
			
			$this->model_account_activity->addActivity('address_delete', $activity_data);
		}
	}
	
	// model/account/return/addReturn/after
	public function addReturn(&$routing, &$args, &$output) {
		if ($this->config->get('config_customer_activity') && $output) {
			$this->load->model('account/activity');

			if ($this->customer->isLogged()) {
				$activity_data = array(
					'customer_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
					'return_id'   => $output
				);

				$this->model_account_activity->addActivity('return_account', $activity_data);
			} else {
				$activity_data = array(
					'name'      => $args[0]['firstname'] . ' ' . $args[0]['lastname'],
					'return_id' => $output
				);

				$this->model_account_activity->addActivity('return_guest', $activity_data);
			}
		}
	}	
	
	// model/checkout/order/addOrderHistory/before
	public function addOrderHistory(&$routing, &$args) {	
		if ($this->config->get('config_customer_activity')) {
			// If last order status id is 0 and new order status is not then record as new order
			$this->load->model('checkout/order');
			
			$order_info = $this->model_checkout_order->getOrder($args[0]);

			if ($order_info && !$order_info['order_status_id'] && $args[1]) {
				$this->load->model('account/activity');
	
				if ($order_info['customer_id']) {
					$activity_data = array(
						'customer_id' => $order_info['customer_id'],
						'name'        => $order_info['firstname'] . ' ' . $order_info['lastname'],
						'order_id'    => $args[0]
					);
	
					$this->model_account_activity->addActivity('order_account', $activity_data);
				} else {
					$activity_data = array(
						'name'     => $order_info['firstname'] . ' ' . $order_info['lastname'],
						'order_id' => $args[0]
					);
	
					$this->model_account_activity->addActivity('order_guest', $activity_data);
				}
			}
		}
	}
}