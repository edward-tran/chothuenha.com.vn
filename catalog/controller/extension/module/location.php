<?php
class ControllerExtensionModuleLocation extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/location');

		$this->load->model('catalog/location');

		$this->load->model('tool/image');

		$data['locations'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		if (!empty($setting['location'])) {
			$locations = array_slice($setting['location'], 0, (int)$setting['limit']);

			foreach ($locations as $location_id) {
				$location_info = $this->model_catalog_location->getLocation($location_id);
				if ($location_info) {
					if ($location_info['image']) {
						$image = URL_HOME.'image/'.$location_info['image'];
					} else {
						$image = URL_HOME.'image/placeholder.png';
					}
					$data['locations'][] = array(
						'location_id'  => $location_info['location_id'],
						'image'       => $image,
						'name'        => $location_info['name'],
						'location'        => '',
						'href'        => $this->url->link('product/location', 'location_id=' . $location_info['location_id'])
					);
				}
			}
		}

		if(isset($setting['layout']) && $setting['layout'] == 'hot') {
			$layout = 'location_hot';
		} else {
			$layout = 'location';
		}

		$data['title'] = $setting['name'];

		if ($data['locations']) {
			return $this->load->view('extension/module/'.$layout, $data);
		}
	}
}