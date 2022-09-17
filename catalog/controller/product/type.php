<?php 
class ControllerProductType extends Controller {
	
	public function autocomplete() {

		$json = array();
		$json['status'] = true;

		$this->load->model('catalog/type');

		$results = $this->model_catalog_type->getTypes();
		$json['types'] = array();
		foreach ($results as $result) {
			$json['types'][] = array(
				'type_id' => $result['type_id'],
				'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
			);
		}

		$sort_order = array();


		foreach ($json['types'] as $key => $value) {
			$sort_order[$key] = $value['name'];
		}
		array_multisort($sort_order, SORT_ASC, $json['types']);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}