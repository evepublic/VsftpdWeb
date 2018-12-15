<?php

class Settings_model extends CI_Model
{
	private $table = 'settings';
	private $settings;

	public function __construct()
	{
		parent::__construct();
		$this->settings = $this->getSettings();
	}

	public function get($name)
	{
		return $this->settings[$name];
	}

	public function getSiteName()
	{
		return (empty($this->settings['site_name'])) ? exec('hostname') : $this->settings['site_name'];
	}

	public function update($updated_settings)
	{
		foreach ($updated_settings as $name => $value) {
			$this->db->where('name', $name);
			$this->db->update($this->table, ['value' => $value]);
		}
	}

	private function getSettings()
	{
		$this->load->database();
		$query = $this->db->get($this->table)->result_array();
		$settings = [];
		foreach ($query as $value) {
			$settings[$value['name']] = $value['value'];
		}
		return $settings;
	}
}
