<?php
class ModelLocalisationArea extends Model {
	public function getArea($area_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "area WHERE area_id = '" . (int)$area_id . "' AND status = '1'");

		return $query->row;
	}

	public function getAreas() {
		$area_data = $this->cache->get('area.catalog');

		if (!$area_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "area ORDER BY name ASC");

			$area_data = $query->rows;

			$this->cache->set('area.catalog', $area_data);
		}

		return $area_data;
	}
}