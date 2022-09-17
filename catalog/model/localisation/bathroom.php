<?php
class ModelLocalisationBathroom extends Model {
	public function getBathroom($bathroom_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bathroom WHERE bathroom_id = '" . (int)$bathroom_id . "' AND status = '1'");

		return $query->row;
	}

	public function getBathrooms() {
		$bathroom_data = $this->cache->get('bathroom.catalog');

		if (!$bathroom_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bathroom ORDER BY name ASC");

			$bathroom_data = $query->rows;

			$this->cache->set('bathroom.catalog', $bathroom_data);
		}

		return $bathroom_data;
	}
}