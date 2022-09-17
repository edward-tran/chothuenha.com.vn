<?php
class ModelCatalogBedroom extends Model {


	public function getBedrooms() {
		$bedroom_data = $this->cache->get('bedroom');
		if (!$bedroom_data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "bedroom";
			$sql .= " ORDER BY name";
			$query = $this->db->query($sql);
			$bedroom_data = $query->rows;
			$this->cache->set('bedroom', $bedroom_data);
		}
		return $bedroom_data;
	}
}