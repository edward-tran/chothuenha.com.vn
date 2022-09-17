<?php
class ModelCatalogArea extends Model {


	public function getAreas() {
		$area_data = $this->cache->get('area');
		if (!$area_data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "area";
			$sql .= " ORDER BY name";
			$query = $this->db->query($sql);
			$area_data = $query->rows;
			$this->cache->set('area', $area_data);
		}
		return $area_data;
	}
}