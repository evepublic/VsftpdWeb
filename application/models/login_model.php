<?php

class Login_model extends CI_Model
{
	private $table = 'users';

	function __construct()
	{
		$this->load->database();
		parent::__construct();
	}

	public function validate($username, $password)
	{
		// Run the query
		$query = $this->db->get_where($this->table, ['username' => $username]);

		// If the user is not found return false
		if ($query->num_rows() !== 1) {
			return false;
		}

		// Check if the password is correct
		$database_password_hash = $query->row_array()['password'];
		return password_verify($password, $database_password_hash);
	}
}
