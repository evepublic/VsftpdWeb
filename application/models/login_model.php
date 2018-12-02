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

	public function changePassword($username, $currentpassword, $newpassword)
	{
		// validate current password
		$this->load->model('login_model');
		$validation_result = $this->login_model->validate($username, $currentpassword);
		if ($validation_result !== true) {
			return ['error' => 'Your current password is incorrect'];
		}

		// update password
		$this->db->where('username', $username);
		$this->db->update($this->table, ['password' => password_hash($newpassword, PASSWORD_DEFAULT)]);

		return true;
	}
}
