<?php
class ModelLocalisationPrice extends Model {
	public function getPrice($price_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "price WHERE price_id = '" . (int)$price_id . "' AND status = '1'");

		return $query->row;
	}

	public function getPrices() {
		$price_data = $this->cache->get('price.catalog');

		if (!$price_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "price ORDER BY sort_order ASC");

			$price_data = $query->rows;

			$this->cache->set('price.catalog', $price_data);
		}

		return $price_data;
	}
}