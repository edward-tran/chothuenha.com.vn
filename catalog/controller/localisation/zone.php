<?php 
class ControllerLocalisationZone extends Controller {


	public function getZonesByCountryId() {;
		$json = array();
        $this->load->model('localisation/zone');
        if(isset($this->request->get['country_id'])) {

            if(isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if(isset($this->request->get['country_id'])) {
                $country_id = $this->request->get['country_id'];
            } else {
                $country_id = '';
            }

            $filter_data = array(
				'filter_name' => $filter_name,
				'country_id'  => $country_id,
			);

            $zone_infos = $this->model_localisation_zone->getZonesByCountryId($filter_data);
            foreach ($zone_infos as $zone_info) {
                $json[] = array(
                    'zone_id'        => $zone_info['zone_id'],
                    'name'           => $zone_info['name'],
                );
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

    public function getZones() {;
        $json = array();
        $this->load->model('localisation/zone');
        if(isset($this->request->get['country_id'])) {

            if(isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if(isset($this->request->get['country_id'])) {
                $country_id = $this->request->get['country_id'];
            } else {
                $country_id = '';
            }

            $filter_data = array(
                'filter_name' => $filter_name,
                'country_id'  => $country_id,
            );

            $zone_infos = $this->model_localisation_zone->getZones($filter_data);
            foreach ($zone_infos as $zone_info) {
                $json[] = array(
                    'zone_id'        => $zone_info['zone_id'],
                    'name'           => $zone_info['name'],
                );
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}