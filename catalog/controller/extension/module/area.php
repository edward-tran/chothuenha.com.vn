<?php
class ControllerExtensionModuleArea extends Controller {
	public function index($setting) {
	

		$this->load->model('catalog/area');

		$this->load->model('tool/image');

		$data['areas'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		if(isset($this->request->get['routing'])) {
			$routing = $this->request->get['routing'];
		} else {
			$routing = '';
		}

		$url  = '';
		
		if(isset($this->request->get['zone_id'])) {
			$url .= '&zone_id='.$this->request->get['zone_id'];
		}

		if(isset($this->request->get['district_id'])) {
			$url .= '&district_id='.$this->request->get['district_id'];
		}

		$areas = $this->model_catalog_area->getAreas();
		foreach ($areas as $area) {
			$data['areas'][] = array(
				'area_id'  => $area['area_id'],
				'name'      => $area['name'],
				'href'        => $this->url->link($routing, $url.'&area_from=' . $area['area_from'].'&area_to='.$area['area_to']),
			);
		}


		$data['title'] = $setting['name'];

		

		return $this->load->view('extension/module/area', $data);
	}


}
// $datetime1 = new DateTime("2021-05-20");

// $datetime2 = new DateTime("2021-06-22");

// $difference = $datetime1->diff($datetime2);

// $abc = array(
//     $difference->y,
// $difference->m,
// $difference->d,
//     );

