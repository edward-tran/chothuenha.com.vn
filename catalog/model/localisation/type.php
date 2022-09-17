<?php
class ModelLocalisationType extends Model {

	public function getTypes($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "type";

			$sql .= " ORDER BY sort_order";

			$sql .= " ASC";

			$query = $this->db->query($sql);
			return $query->rows;
		} else {
			$type_data = $this->cache->get('type');
			if (!$type_data) {
				$query = $this->db->query("SELECT type_id, name FROM " . DB_PREFIX . "type ORDER BY sort_order");
				$type_data = $query->rows;

				$this->cache->set('type', $type_data);
			}
			return $type_data;
		}
	}
}