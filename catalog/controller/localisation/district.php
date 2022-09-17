<?php 
class ControllerLocalisationDistrict extends Controller {
	public function getDistrictCode() {
		$json = array();
            $this->load->model('localisation/district');

            if(isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if(isset($this->request->get['zone_id'])) {
                $zone_id = $this->request->get['zone_id'];
            } else {
                $zone_id = '';
            }

            $filter_data = array(
				'filter_name'   => $filter_name,
				'zone_id'       => $zone_id,
			);

            if(isset($this->request->get['zone_id'])) {
                $zone_infos = $this->model_localisation_district->getDistrictCode($filter_data);
                foreach ($zone_infos as $zone_info) {
                    $json[] = array(
                        'district_id'       => $zone_info['district_id'],
                        'name'              => $zone_info['name'],
                        'status'            => $zone_info['status']     
                    );
                }
            }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}
}