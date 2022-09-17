<?php 
class ModelLocalisationStreet extends Model {
    public function getStreetCode($data = array()) {
    	$sql = "SELECT * FROM " . DB_PREFIX . "street WHERE district_id = '" . (int)$data['district_id'] . "' AND status = '1'";
		if (!empty($data['filter_name'])) {
			$sql .= " AND name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		$sql .= " ORDER BY name";
		$query = $this->db->query($sql);
		$street_data = $query->rows;
		return $street_data;
    }
}