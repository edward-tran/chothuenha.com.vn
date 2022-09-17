<?php 
class ControllerLocalisationDirectionHouse extends Controller {
	public function getDirectionHouses() {
		$json = array();
        $this->load->model('localisation/direction_house');

        $direction_houses = $this->model_localisation_direction_house->getDirectionHouses();
        foreach ($direction_houses as $direction_house) {
            $json[] = array(
                'direction_house_id'          => $direction_house['direction_house_id'],
                'name'              => $direction_house['name'],
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}
}