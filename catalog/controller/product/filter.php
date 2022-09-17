<?php
class ControllerProductFilter extends Controller {
	public function index() {

	}

	public function getUrlFilter() {

		$json = array();

		$json['url_filter'] = '';

		$url = '';

		if(isset($this->request->get['zone_id']) && (int)$this->request->get['zone_id']) {
			$url .= '&zone_id='.$this->request->get['zone_id'];
		} else {
			$url .= '';
		}


		if(isset($this->request->get['type_id']) && (int)$this->request->get['type_id']) {
			$url .= '&type_id='.$this->request->get['type_id'];
		} else {
			$url .= '';
		}

		if(isset($this->request->get['price_from'])) {
			$url .= '&price_from='.$this->request->get['price_from'];
		} else {
			$url .= '';
		}

		if(isset($this->request->get['price_to'])) {
			$url .= '&price_to='.$this->request->get['price_to'];
		} else {
			$url .= '';
		}


		if(isset($this->request->get['acreage_from'])) {
			$url .= '&acreage_from='.$this->request->get['acreage_from'];
		} else {
			$url .= '';
		}

		if(isset($this->request->get['acreage_to'])) {
			$url .= '&acreage_to='.$this->request->get['acreage_to'];
		} else {
			$url .= '';
		}

		if(isset($this->request->get['bedroom'])) {
			$url .= '&bedroom='.$this->request->get['bedroom'];
		} else {
			$url .= '';
		}

		if(isset($this->request->get['bathroom'])) {
			$url .= '&bathroom='.$this->request->get['bathroom'];
		} else {
			$url .= '';
		}


		if(isset($this->request->get['direction_house_id'])) {
			$url .= '&direction_house_id='.$this->request->get['direction_house_id'];
		} else {
			$url .= '';
		}
		

		if(empty($this->request->get['zone_id'])) {
			$url_filter = URL_HOME.$url;
		} else {
			$url_filter = $this->url->link('product/zone', $url);
		}

		if(isset($this->request->get['district_id']) && $this->request->get['district_id']) {

			$district_id = explode(",",$this->request->get['district_id']);
			$district_id = array_filter($district_id);
			$district_id = implode(',',$district_id);

			$url_filter .= '&district_id='.$district_id;
		} else {
			$url_filter .= '';
		}

		
		$query = str_replace('&amp;', '&', $url_filter);
		$json['url_filter'] = $query;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}