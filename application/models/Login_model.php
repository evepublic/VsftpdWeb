<?php

class Login_model extends CI_Model
{
	private $table = 'users';

	function __construct()
	{
		parent::__construct();
		$this->load->database();
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
		$database_password_hash = $query->row()->password;
		return password_verify($password, $database_password_hash);
	}

	public function changePassword($username, $currentpassword, $newpassword)
	{
		// validate current password
		$validation_result = $this->validate($username, $currentpassword);
		if ($validation_result !== true) {
			return ['error' => 'The current password is incorrect.'];
		}

		// update password
		$this->db->where('username', $username)->update($this->table, ['password' => password_hash($newpassword, PASSWORD_DEFAULT)]);
		return true;
	}
}
