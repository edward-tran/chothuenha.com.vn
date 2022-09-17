<?php 
class ControllerLocalisationPrice extends Controller {
	public function getPrices() {
		$json = array();
        $this->load->model('localisation/price');

        $prices = $this->model_localisation_price->getPrices();
        foreach ($prices as $price) {
            $json[] = array(
                'price_id'          => $price['price_id'],
                'name'              => $price['name'],
                'price_from'        => (float)$price['price_from'],
                'price_to'          => (float)$price['price_to'],
                'sort_order'          => $price['sort_order']
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}
}