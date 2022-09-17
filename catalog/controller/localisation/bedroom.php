<?php 
class ControllerLocalisationBedroom extends Controller {
	public function getBedrooms() {
		$json = array();
        $this->load->model('localisation/bedroom');

        $bedrooms = $this->model_localisation_bedroom->getBedrooms();
        foreach ($bedrooms as $bedroom) {
            $json[] = array(
                'bedroom_id'          => $bedroom['bedroom_id'],
                'name'              => $bedroom['name'],
                'bedroom'        => $bedroom['bedroom'],
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}
}