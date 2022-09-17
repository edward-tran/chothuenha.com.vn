<?php 
class ControllerLocalisationType extends Controller {
	public function getTypes() {
		$json = array();
        $this->load->model('localisation/type');
        $types = $this->model_localisation_type->getTypes();
        foreach ($types as $type) {
            $json[] = array(
                'type_id'           => $type['type_id'],
                'name'              => $type['name'] 
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

  
}