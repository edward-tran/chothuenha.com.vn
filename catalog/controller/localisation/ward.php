<?php 
class ControllerLocalisationWard extends Controller {
	public function getWardCode() {
		    $json = array();
            $this->load->model('localisation/ward');

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
				'filter_name'       => $filter_name,
				'district_id'       => $district_id,
			);

            if(isset($this->request->get['district_id'])) {
                $ward_infos = $this->model_localisation_ward->getWardCode($filter_data);
                foreach ($ward_infos as $ward_info) {
                    $json[] = array(
                        'ward_id'           => $ward_info['ward_id'],
                        'name'              => $ward_info['name'],
                        'status'            => $ward_info['status']     
                    );
                }
            }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}
}