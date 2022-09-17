<?php
class ModelCatalogPrice extends Model {


	public function getPrices() {
		$price_data = $this->cache->get('price');
		if (!$price_data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "price";
			$sql .= " ORDER BY name";
			$query = $this->db->query($sql);
			$price_data = $query->rows;
			$this->cache->set('price', $price_data);
		}
		return $price_data;
	}
}