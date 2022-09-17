<?php
class ModelCatalogLocation extends Model {
	public function getLocation($location_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "location m LEFT JOIN " . DB_PREFIX . "location_to_store m2s ON (m.location_id = m2s.location_id) WHERE m.location_id = '" . (int)$location_id . "' AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		return $query->row;
	}

	public function getLocations($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "location m LEFT JOIN " . DB_PREFIX . "location_to_store m2s ON (m.location_id = m2s.location_id) WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

			$sort_data = array(
				'name',
				'sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY name";
			}

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);

			return $query->rows;
		} else {
			$location_data = $this->cache->get('location.' . (int)$this->config->get('config_store_id'));

			if (!$location_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "location m LEFT JOIN " . DB_PREFIX . "location_to_store m2s ON (m.location_id = m2s.location_id) WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY name");

				$location_data = $query->rows;

				$this->cache->set('location.' . (int)$this->config->get('config_store_id'), $location_data);
			}

			return $location_data;
		}
	}
}