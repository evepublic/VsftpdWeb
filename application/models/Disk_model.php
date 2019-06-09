<?php

class Disk_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('settings_model');
	}

	public function getDiskSpaceData()
	{
		$user_path = $this->settings_model->get('user_base_path');

		$result['disk']['path'] = $user_path;

		$bytes = disk_free_space($user_path);
		$si_prefix = ['B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB'];
		$base = 1024;
		$si_class = min((int)log($bytes, $base), count($si_prefix) - 1);
		$result['disk']['space'] = sprintf('%1.2f', $bytes / pow($base, $si_class)) . ' ' . $si_prefix[$si_class];

		return $result;
	}
}
