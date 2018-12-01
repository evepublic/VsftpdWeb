<?php

class Disk_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function getDiskSpace($disk)
	{
		$query = $this->db->get_where('settings', array('name' => $disk));
		$query = $query->row_array();

		$result['disk'] = $query['defval'];

		$bytes = disk_free_space($query['value']);
		$si_prefix = ['B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB'];
		$base = 1024;
		$si_class = min((int)log($bytes, $base), count($si_prefix) - 1);
		$result['space'] = sprintf('%1.2f', $bytes / pow($base, $si_class)) . ' ' . $si_prefix[$si_class];

		return $result;
	}

	public function getDiskSpaceData()
	{
		$disk_data = [];
		foreach (['disk1', 'disk2', 'disk3'] as $disk) {
			$disk_data[$disk] = $this->getDiskSpace($disk);
		}
		return $disk_data;
	}
}
