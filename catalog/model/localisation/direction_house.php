<?php
class ModelLocalisationDirectionHouse extends Model {
	public function getDirectionHouse($direction_house_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "direction_house WHERE direction_house_id = '" . (int)$direction_house_id . "' AND status = '1'");

		return $query->row;
	}

	public function getDirectionHouses() {
		$direction_house_data = $this->cache->get('direction_house.catalog');
		if (!$direction_house_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "direction_house ORDER BY name ASC");
			$direction_house_data = $query->rows;

			$this->cache->set('direction_house.catalog', $direction_house_data);
		}

		return $direction_house_data;
	}
}