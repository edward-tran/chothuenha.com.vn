<?php
class ControllerExtensionMstoreCart extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('checkout/cart');
		$this->response->setOutput(json_encode(["success"=>1, "error"=>[], "data"=>$this->cart->getProducts()]));
	}

	/**
	 * @api {post} /index.php?routing=extension/mstore/cart/add Add products to cart
	 * @apiVersion 0.1.0
	 * @apiName Add products to cart
	 * @apiGroup Checkout
	 *
	 * 
	 * @apiParam {Array}  body
	 * @apiParam {String} body.product_id
	 * @apiParam {String} body.quantity
	 * 
	 * * @apiParamExample {array} Request-Example:
 	 *     [{
     *       "product_id": "40",
	 *        "quantity": "2"
 	 *     }]
 	 * 
	 * @apiSuccess {Number} success 1: Success, 0: Fail.
	 * @apiSuccess {Array} error  List error messages.
	 * @apiSuccess {Object} data
	 * @apiSuccess {Number} data.total_product_count
	 * 
	 */

	public function add() {
		$this->load->language('checkout/cart');

		$json = array();

		$json = file_get_contents('php://input');
		$params = json_decode($json);
		foreach ($params as $item) {
			$this->cart->add($item->product_id, $item->quantity);
		}
		// Unset all shipping and payment methods
		unset($this->session->data['shipping_method']);
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['payment_method']);
		unset($this->session->data['payment_methods']);

		$data = ["total_product_count"=>$this->cart->countProducts()];
		$this->response->setOutput(json_encode(["success"=>1, "error"=>[], "data"=>$data]));
	}

	/**
	 * @api {delete} /index.php?routing=extension/mstore/cart/empty Empty cart
	 * @apiVersion 0.1.0
	 * @apiName Empty cart
	 * @apiGroup Checkout
	 *
	 * @apiSuccess {Number} success 1: Success, 0: Fail.
	 * @apiSuccess {Array} error  List error messages.
	 * @apiSuccess {Object} data
	 * @apiSuccess {Number} data.total_product_count
	 * 
	 */
	public function empty() {
		$this->load->language('checkout/cart');
		if ($this->request->server['REQUEST_METHOD'] == 'DELETE') {
			$products = $this->cart->getProducts();
			foreach ($products as $item) {
				$this->cart->remove($item["cart_id"]);
			}
			// Unset all shipping and payment methods
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);

			$data = ["total_product_count"=>$this->cart->countProducts()];
			$this->response->setOutput(json_encode(["success"=>1, "error"=>[], "data"=>$data]));
		}else{
			$this->response->addHeader('HTTP/1.0 404 Not Found');
			$this->response->setOutput(json_encode(["success"=>0, "error"=>["Method not found"], "data"=>[]]));
		}
	}
}