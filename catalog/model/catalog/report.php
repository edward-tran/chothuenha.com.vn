<?php
class ModelCatalogReport extends Model {

	public function addReport($product_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "report SET
			fullname = '" . $this->db->escape($data['fullname']) . "',
			email = '" . $this->db->escape($data['email']) . "',
			telephone = '" . $this->db->escape($data['telephone']) . "',
			content = '" . $this->db->escape($data['content']) . "',
			product_id = '" . (int)$product_id . "',
			info = '" . $this->db->escape(json_encode($data['info'])) . "',
			date_added = NOW()");
		$report_id = $this->db->getLastId();

		if($this->customer->getId()) {
			$this->db->query("UPDATE " . DB_PREFIX . "report SET customer_id = '" . (int)$this->customer->getId() . "' WHERE report_id = '" . (int)$report_id . "'");	
		}
	}


	public function getReports() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "report WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		return $query->rows;
	}

	public function getReportsByProductId($product_id, $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$query = $this->db->query("SELECT r.report_id, r.author, r.rating, r.text, p.product_id, pd.name, p.price, p.image, r.date_added FROM " . DB_PREFIX . "report r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalReportsByProductId($product_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "report r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}
}