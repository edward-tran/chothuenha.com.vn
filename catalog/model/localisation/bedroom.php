<?php
class ModelLocalisationBedroom extends Model {
	public function getBedroom($bedroom_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bedroom WHERE bedroom_id = '" . (int)$bedroom_id . "' AND status = '1'");

		return $query->row;
	}

	public function getBedrooms() {
		$bedroom_data = $this->cache->get('bedroom.catalog');

		if (!$bedroom_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bedroom ORDER BY name ASC");

			$bedroom_data = $query->rows;

			$this->cache->set('bedroom.catalog', $bedroom_data);
		}

		return $bedroom_data;
	}
}