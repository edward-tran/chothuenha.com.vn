<?php 
class ModelCatalogProductType extends Model {
	public function index() {

	}

	public function getProductTypes() {
		$product_type_data = $this->cache->get('product_type.' . (int)$this->config->get('config_language_id'));

		if (!$product_type_data) {
			$query = $this->db->query("SELECT product_type_id, name FROM " . DB_PREFIX . "product_type WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

			$product_type_data = $query->rows;

			$this->cache->set('product_type.' . (int)$this->config->get('config_language_id'), $product_type_data);
		}
		
		return $product_type_data;
	}
}