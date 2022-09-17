<?php 
class ModelCatalogType extends Model {
	public function index() {

	}

	public function getTypes() {
		$type_data = $this->cache->get('type.' . (int)$this->config->get('config_language_id'));

		if (!$type_data) {
			$query = $this->db->query("SELECT type_id, name FROM " . DB_PREFIX . "type WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

			$type_data = $query->rows;

			$this->cache->set('type.' . (int)$this->config->get('config_language_id'), $type_data);
		}

		return $type_data;
	}

	public function getType($type_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "type WHERE type_id = '" . (int)$type_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
}