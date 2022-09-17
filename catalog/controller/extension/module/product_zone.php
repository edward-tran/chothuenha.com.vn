<?php
class ControllerExtensionModuleProductZone extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/product_zone');

		$this->load->model('localisation/zone');

		$data['heading_title'] = $this->language->get('heading_title');
		$data['zones'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		if (!empty($setting['zone'])) {
			$product_zones = array_slice($setting['zone'], 0, (int)$setting['limit']);

			$data_filter = array(
				'zone_ids' => $product_zones
			);

			$zones = $this->model_localisation_zone->getZonesAndTotalProduct($data_filter);

			foreach ($zones as $zone) {
				$data['zones'][] = array(
					'zone_id'  	  => $zone['zone_id'],
					'name'        => $zone['name']. ($zone['total'] ? ' ('.$zone['total'].')' : ''),
					'href'        => $this->url->link('product/zone', 'zone_id=' . $zone['zone_id'])
				);
			}
		}

		return $this->load->view('extension/module/product_zone', $data);
	}

	
}
