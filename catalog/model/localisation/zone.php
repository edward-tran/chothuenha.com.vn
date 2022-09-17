<?php
class ModelLocalisationZone extends Model {
	public function getZone($zone_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "' AND status = '1'");

		return $query->row;
	}

	public function getZonesByCountryId($data = array()) {

		// $zone_data = $this->cache->get('zone.' . (int)$data['country_id']);

		// if (!$zone_data) {

			$sql = "SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$data['country_id'] . "' AND status = '1'";

			if (!empty($data['filter_name'])) {
				$sql .= " AND name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			}

			$sql .= " ORDER BY sort_order";

			$query = $this->db->query($sql);

			$zone_data = $query->rows;

			//$this->cache->set('zone.' . (int)$data['country_id'], $zone_data);
		// }

		return $zone_data;
	}

	public function getZones($data = array()) {

			$zone_data = $this->cache->get('zone');

			if($data  || !$zone_data) {
				$sql = "SELECT * FROM " . DB_PREFIX . "zone WHERE status = '1'";

				if (!empty($data['country_id'])) {
					$sql .= " AND country_id = '" . (int)$data['country_id'] . "'";
				}

				if (!empty($data['filter_name'])) {
					$sql .= " AND name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}

				$sql .= " ORDER BY sort_order";

				$query = $this->db->query($sql);
				$zone_data = $query->rows;
				$this->cache->set('zone', $zone_data);
			}

			return $zone_data;
	}

	public function getZonesAndTotalProduct($data = array()) {

			$zone_data = $this->cache->get('zone_home.'.implode('.', $data['zone_ids']));

			if($data  || !$zone_data) {
				$sql = "SELECT p.*,";
				$sql .= "(SELECT COUNT(DISTINCT b.product_id)";
				$sql .= " FROM " . DB_PREFIX . "product b WHERE b.zone_id = p.zone_id AND b.status = 1) AS total";
				$sql .= " FROM " . DB_PREFIX . "zone p WHERE p.status = '1'";
			
				if (!empty($data['country_id'])) {
					$sql .= " AND p.country_id = '" . (int)$data['country_id'] . "'";
				}

				if(!empty($data['zone_ids'])) {
					$sql .= " AND p.zone_id IN (" . implode(',', $data['zone_ids']) . ")";
				}

				if (!empty($data['filter_name'])) {
					$sql .= " AND p.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}

				$sql .= " ORDER BY p.sort_order";

				$query = $this->db->query($sql);
				$zone_data = $query->rows;
				$this->cache->set('zone_home.'.implode('.', $data['zone_ids']), $zone_data);
			}

			return $zone_data;
	}
}