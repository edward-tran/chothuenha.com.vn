<?php
class ModelLocalisationDistrict extends Model {

	public function getDistrictCode($data = array()) {
	//	$district_data = $this->cache->get('district');
		// if (!$district_data) {

			$sql = "SELECT * FROM " . DB_PREFIX . "district WHERE zone_id = '" . (int)$data['zone_id'] . "' AND status = '1'";
			if (!empty($data['filter_name'])) {
				$sql .= " AND name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			}
			$sql .= " ORDER BY name";
			
			$query = $this->db->query($sql);

			$district_data = $query->rows;
			//$this->cache->set('district.' . (int)$data['zone_id'], $district_data);
		// }
		return $district_data;
	}

	public function getZoneDistrict($district_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "district WHERE district_id = '" . (int) $district_id . "'");
        return $query->row;
    }

    public function getDistrict($district_id) {
    	$district_data = $this->cache->get('district_data.' . (int)$district_id);
    	if(!$district_data) {
    		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "district WHERE district_id = '" . (int)$district_id . "' AND status = '1'");
			$district_data =  $query->row;
    	}
    	return $district_data;
	}

	public function getDistrictByZoneId($zone_id) {
		$district_data = $this->cache->get('district.'.(int)$zone_id);
		if (!$district_data) {
			$sql = "SELECT p.*,";

			$sql .= "(SELECT COUNT(DISTINCT b.product_id)";
				$sql .= " FROM " . DB_PREFIX . "product b WHERE b.district_id = p.district_id AND b.status = 1) AS total";

			$sql .= " FROM " . DB_PREFIX . "district p WHERE p.zone_id = '" . (int)$zone_id . "' AND p.status = '1'";
			$sql .= " ORDER BY p.name";
			$query = $this->db->query($sql);
			$district_data = $query->rows;
			$this->cache->set('district.' . (int)$zone_id, $district_data);
		}
		return $district_data;
	}
}