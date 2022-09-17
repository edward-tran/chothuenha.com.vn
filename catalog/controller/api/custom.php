<?php 
class ControllerApiCustom extends Controller {

	public function index() {

	}

	public function getZonesByCountryId() {
		$json = array();
            $this->load->model('localisation/zone');
            if(isset($this->request->get['country_id'])) {
                $zone_infos = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);
                foreach ($zone_infos as $zone_info) {
                    $json[] = array(
                        'zone_id'        	=> $zone_info['zone_id'],
                        'country_id'        => $zone_info['country_id'],
                        'name'              => $zone_info['name'],
                        'status'            => $zone_info['status']     
                    );
                }
            }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

    public function getDistrictCode() {
        $json = array();
            $this->load->model('localisation/district');
            if(isset($this->request->get['zone_id'])) {
                $zone_infos = $this->model_localisation_district->getDistrictCode($this->request->get['zone_id']);
                foreach ($zone_infos as $zone_info) {
                    $json[] = array(
                        'district_id'        => $zone_info['district_id'],
                        'name'              => $zone_info['name'],
                        'status'            => $zone_info['status']     
                    );
                }
            }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}