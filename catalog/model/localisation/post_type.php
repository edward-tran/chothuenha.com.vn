<?php
class ModelLocalisationPostType extends Model {
	public function getPostType($post_type_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_type WHERE post_type_id = '" . (int)$post_type_id . "'");
		return $query->row;
	}

	public function getPostTypes() {
		$post_type_data = $this->cache->get('post_type.catalog');

		if (!$post_type_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_type ORDER BY name ASC");

			$post_type_data = $query->rows;

			$this->cache->set('post_type.catalog', $post_type_data);
		}

		return $post_type_data;
	}

	public function getPostTypeById($post_type_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_type WHERE post_type_id = '" . (int)$post_type_id . "'");
		return $query->row;
	}
}