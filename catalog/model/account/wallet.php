<?php
class ModelAccountWallet extends Model {
	public function index() {

	}

	public function getAmountByCustomerId() {
		$customer_id = $this->customer->getId();
		$query = $this->db->query("SELECT amount FROM " . DB_PREFIX . "customer_wallet WHERE customer_id = '" . (int)$customer_id . "'");
		return $query->row ? $query->row['amount'] : 0;
	}
}