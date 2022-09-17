<?php
class ModelLocalisationWard extends Model {

	public function getWardCode($data = array()) {
		// $ward_data = $this->cache->get('ward.' . (int)$district_id);
		// if (!$ward_data) {

		

			$sql = "SELECT * FROM " . DB_PREFIX . "ward WHERE district_id = '" . (int)$data['district_id'] . "' AND status = '1'";
			if (!empty($data['filter_name'])) {
				$sql .= " AND name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			}
			$sql .= " ORDER BY name";


			$query = $this->db->query($sql);

			

			$ward_data = $query->rows;
		//	$this->cache->set('ward.' . (int)$data['district_id'], $ward_data);
		// }
		return $ward_data;
	}

	public function getWard($ward_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "ward WHERE ward_id = '" . (int) $ward_id . "'");
        return $query->row;
    }
}