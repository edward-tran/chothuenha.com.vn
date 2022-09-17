<?php 
class ControllerLocalisationArea extends Controller {
	public function getAreas() {
		$json = array();
        $this->load->model('localisation/area');

        $areas = $this->model_localisation_area->getAreas();
        foreach ($areas as $area) {
            $json[] = array(
                'area_id'          => $area['area_id'],
                'name'              => $area['name'],
                'area_from'        => $area['area_from'],
                'area_to'          => $area['area_to']
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}
}