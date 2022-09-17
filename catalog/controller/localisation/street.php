<?php 
class ControllerLocalisationStreet extends Controller {
    public function getStreetByDistrictId() {

        $json = array();
        $this->load->model('localisation/street');

        if(isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if(isset($this->request->get['district_id'])) {
            $district_id = $this->request->get['district_id'];
        } else {
            $district_id = '';
        }

        $filter_data = array(
            'filter_name'   => $filter_name,
            'district_id'       => $district_id,
        );

        if(isset($this->request->get['district_id'])) {
            $street_infos = $this->model_localisation_street->getStreetCode($filter_data);
            foreach ($street_infos as $street_info) {
                $json[] = array(
                    'street_id'       => $street_info['street_id'],
                    'name'              => $street_info['name']
                );
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));


    }
}