<?php
class ControllerExtensionMstoreCategory extends Controller {

	/**
	 * @api {get} /index.php?routing=extension/mstore/category Get all categories
	 * @apiVersion 0.1.0
	 * @apiName Get all categories
	 * @apiGroup Catalog
	 *
	 * 
	 * @apiParam {Boolean} [hide_empty]
	 * 
	 * @apiSuccess {Number} success 1: Success, 0: Fail.
	 * @apiSuccess {Array} error  List error messages.
	 * @apiSuccess {Array} data  List Category.
	 * @apiSuccess {String} data.id
	 * @apiSuccess {String} data.description
	 * @apiSuccess {String} data.count
	 * @apiSuccess {String} data.name
	 * @apiSuccess {String} data.image
	 * 
	 */
	public function index() {
		$this->load->model('catalog/category');
		$this->load->model('tool/image');
		$this->load->model('catalog/product');
		
		if (isset($this->request->get['hide_empty'])) {
			$hide_empty = $this->request->get['hide_empty'];
		} else {
			$hide_empty = false;
		}

		$data = array();
		$parents = $this->model_catalog_category->getCategories(0);
		foreach ($parents as $result) {

			$children = $this->model_catalog_category->getCategories($result['category_id']);
			foreach($children as $child) {
				$filter_data = array('filter_category_id'  => $child['category_id'], 'filter_sub_category' => true);
				$count = $this->config->get('config_product_count') ?  $this->model_catalog_product->getTotalProducts($filter_data)  : 0;
				if (!$hide_empty || $count > 0) {
					$data[] = array(
						'id' => $child['category_id'],
						'description' => $child['description'],
						'count' => $count,
						'name' => $child['name'],
						'image'    => $child['image'] ? $this->model_tool_image->resize($child['image'], 600 , 600) : ""
					);
				}
			}
				
			$filter_data = array('filter_category_id'  => $result['category_id'], 'filter_sub_category' => true);
			$count = $this->config->get('config_product_count') ?  $this->model_catalog_product->getTotalProducts($filter_data)  : 0;
			if (!$hide_empty || $count > 0) {
				$data[] = array(
					'id' => $result['category_id'],
					'description' => $result['description'],
					'count' => $count,
					'name' => $result['name'],
					'image'    => $result['image'] ? $this->model_tool_image->resize($result['image'], 600 , 600) : ""
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode(["success"=>1, "error"=>[], "data"=>$data]));
	}
}
