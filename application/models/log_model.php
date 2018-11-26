<?php

class Log_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
		parent::__construct();
	}

	public function get_settings($name)
	{
		$query = $this->db->get_where('settings', array('name' => $name));

		$query = $query->row_array(0);
		$query1 = $query['value'];
		return $query1;
	}
}
