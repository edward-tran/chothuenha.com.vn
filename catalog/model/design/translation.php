<?php
class ModelDesignTranslation extends Model {
	public function getTranslations($routing) {
		$language_code = !empty($this->session->data['language']) ? $this->session->data['language'] : $this->config->get('config_language');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "translation WHERE store_id = '" . (int)$this->config->get('config_store_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "' AND (routing = '" . $this->db->escape($routing) . "' OR routing = '" . $this->db->escape($language_code) . "')");

		return $query->rows;
	}
}
