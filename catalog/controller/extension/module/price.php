<?php
class ControllerExtensionModulePrice extends Controller {
	public function index($setting) {


		$this->load->model('catalog/price');

		$this->load->model('tool/image');

		$data['prices'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		$prices = $this->model_catalog_price->getPrices();

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

		foreach ($prices as $price) {
			$data['prices'][] = array(
				'price_id'  => $price['price_id'],
				'name'      => $price['name'],
				'href'        => $this->url->link($routing, $url.'&price_from=' . $price['price_from'].'&price_to='.$price['price_to']),

				
			);
		}


		$data['title'] = $setting['name'];

		

		return $this->load->view('extension/module/price', $data);
	}


}
