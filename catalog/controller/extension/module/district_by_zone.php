<?php
class ControllerExtensionModuleDistrictByZone extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/district_by_zone');

		$this->load->model('localisation/district');

		$this->load->model('tool/image');

		$data['products'] = array();

		$data['districts'] = array();
		
		if (!empty($setting['zone_id'])) {
				$zone_id = $setting['zone_id'];
				$districts = $this->model_localisation_district->getDistrictByZoneId($zone_id);
				if ($districts) {
					foreach($districts as $district) {
						$data['districts'][] = array(
							'district_id'  => $district['district_id'],
							'name'        => $district['name']. ($district['name'] ? ' ('.$district['total'].')' : ''),
							'href'        => $this->url->link('product/zone', 'zone_id=' . $zone_id.'&district_id='.$district['district_id'])
						);
					}
				}
			
		}

		$data['title'] = $setting['name'];

		return $this->load->view('extension/module/district_by_zone', $data);
	}

	public function calDate($date) {
		$date1 = new DateTime($date);
		$date2 = new DateTime(date('Y-m-d h:i:sa'));
		// The diff-methods returns a new DateInterval-object...
		$diff = $date2->diff($date1);
		// Call the format method on the DateInterval-object
		if($diff->h <=24) {
			return $diff->h .' giờ trước';
		}
		if($diff->d <=30) {
			return $diff->d;
		}

		if($diff->d >30) {
			return date($this->language->get('date_format_short'), strtotime($date));
		}
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

