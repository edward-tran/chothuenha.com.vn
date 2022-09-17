<?php 
class ControllerLocalisationBathroom extends Controller {
	public function getBathrooms() {
		$json = array();
        $this->load->model('localisation/bathroom');

        $bathrooms = $this->model_localisation_bathroom->getBathrooms();
        foreach ($bathrooms as $bathroom) {
            $json[] = array(
                'bathroom_id'          => $bathroom['bathroom_id'],
                'name'              => $bathroom['name'],
                'bathroom'        => $bathroom['bathroom'],
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}
}