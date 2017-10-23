<?php
class ModelMarketingMarketing extends Model {
	public function getMarketingByCode($code) {
		$query = $this->db->query("SELECT DISTINCT * FROM oc_marketing WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function addMarketingReport($marketing_id, $ip, $country = '') {
		$this->db->query("INSERT INTO `oc_marketing_report` SET marketing_id = '" . (int)$marketing_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', ip = '" . $this->db->escape($ip) . "', country = '" . $this->db->escape($country) . "'");
	}
}