<?php
class ControllerExtensionModuleBedroom extends Controller {
	public function index($setting) {
	

		$this->load->model('catalog/bedroom');

		$this->load->model('tool/image');

		$data['bedrooms'] = array();

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

		$bedrooms = $this->model_catalog_bedroom->getBedrooms();
		foreach ($bedrooms as $bedroom) {
			$data['bedrooms'][] = array(
				'bedroom_id'  => $bedroom['bedroom_id'],
				'name'      => $bedroom['name'],
				'href'        => $this->url->link($routing, $url.'&bedroom=' . $bedroom['bedroom']),
			);
		}


		$data['title'] = $setting['name'];

		

		return $this->load->view('extension/module/bedroom', $data);
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

