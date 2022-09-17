<?php
class ModelAccountCustomerWallet extends Model {
	
	public function getTotalWallet() {
		$customer_id = $this->customer->getId();
		$query = $this->db->query("SELECT amount FROM " . DB_PREFIX . "customer_wallet WHERE customer_id = '" . (int)$customer_id . "'");
		return $query->row ? $query->row['amount'] : 0;
	}
	
}