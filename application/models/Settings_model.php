<?php

class Settings_model extends CI_Model
{
	private $table = 'settings';
	private $settings = [];

	public function __construct()
	{
		parent::__construct();
		$this->load->database();

		$query = $this->db->get($this->table);
		foreach ($query->result() as $setting) {
			$this->settings[$setting->name] = $setting->value;
		}
	}

	public function get($name)
	{
		return $this->settings[$name];
	}

	public function getAll()
	{
		return $this->settings;
	}

	public function getSiteNameDisplay()
	{
		$site_name_display = (empty($this->settings['site_name'])) ? exec('hostname') : $this->settings['site_name'];
		return htmlentities($site_name_display);
	}

	public function update($updated_settings)
	{
		foreach ($updated_settings as $name => $value) {
			$this->db->where('name', $name)->update($this->table, ['value' => $value]);
		}
	}
}
