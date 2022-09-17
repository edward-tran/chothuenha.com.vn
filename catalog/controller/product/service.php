<?php
class ControllerProductService extends Controller {
	public function index() {

			$this->document->addStyle('catalog/view/theme/beta/stylesheet/service.css?'.rand());

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => 'Bảng giá',
				'href' => $this->url->link('product/service')
			);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('product/service', $data));
		
	}

	public function getAllCategory() {

		$json = array();

		$this->load->model('catalog/category');

		$results = $this->model_catalog_category->getCategories(0); // custom

		$json['categories'] = array();
		if($results) {
			foreach ($results as $result) {

				$_childs = array();
				$childs = $this->model_catalog_category->getCategories($result['category_id']); 
				if($childs) {
					foreach ($childs as $child) {
						$_childs[] = array(
							'name' 				=> $child['name'],
							'category_id' 		=> $child['category_id'],
							'href' 				=> $this->url->link('product/category', 'path=' . $result['category_id'])
						);
					}
				}
				$json['categories'][] = array(
					'name' 				=> $result['name'],
					'category_id' 		=> $result['category_id'],
					'href' 				=> $this->url->link('product/category', 'path=' . $result['category_id']),
					'childs'			=> $_childs,
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


	
}
