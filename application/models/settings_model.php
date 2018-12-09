<?php

class Settings_model extends CI_Model
{
	private $table = 'settings';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_settings($name)
	{
		$query = $this->db->get_where($this->table, array('name' => $name));

		$query = $query->row_array(0);
		$query1 = $query['value'];
		return $query1;
	}

	public function get_settings_def($name)
	{
		$query = $this->db->get_where($this->table, array('name' => $name));

		$query = $query->row_array(0);
		$query1 = $query['defval'];
		return $query1;
	}

	public function get_all_settings()
	{
		$query = $this->db->get($this->table);

		$result = $query->result_array();
		return $result;
	}

	public function change()
	{
		$settings_variables = [
			'def_disk1',
			'def_disk2',
			'def_disk3',
			'disk1',
			'disk2',
			'disk3',
			'log_path',
			'mail_from',
			'mail_password',
			'mail_port',
			'mail_server',
			'mail_user',
			'site_name',
			'user_path',
		];

		$updated_settings = [];
		foreach ($settings_variables as $key) {
			if (array_key_exists($key, $this->input->post())) {
				if ((substr($key, 0, 8) === 'def_disk')) {
					$updated_settings[substr($key, 4)]['defval'] = $this->input->post($key);
				} else {
					$updated_settings[$key]['value'] = $this->input->post($key);
				}
			}
		}

		foreach ($updated_settings as $name => $data) {
			$this->db->where('name', $name);
			$this->db->update($this->table, $data);
		}
	}

	public function getSiteName()
	{
		$site_name = $this->get_settings('site_name');
		if (empty($site_name)) {
			$site_name = exec('hostname -f');
		}
		return $site_name;
	}
}
