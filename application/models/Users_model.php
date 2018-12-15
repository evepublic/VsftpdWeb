<?php

class Users_model extends CI_Model
{
	private $table = 'accounts';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('security_model');
		$this->load->model('settings_model');
		$this->load->model('vsftpd_model');
	}

	public function get($id)
	{
		return $this->db->get_where($this->table, array('id' => $id))->row_array();
	}

	public function getAll()
	{
		return $this->db->get($this->table)->result_array();
	}

	public function createUser($username, $password, $permissions)
	{
		$storage_dir_user = $this->vsftpd_model->getStorageDirUser($username);
		if (file_exists($storage_dir_user)) return ['error' => 'User storage directory "' . $storage_dir_user . '" for user "' . htmlentities($username) . '" already exists.'];
		if (!mkdir($storage_dir_user, 0700)) return ['error' => 'Failed to create user storage directory for user "' . htmlentities($username) . '"'];

		$write_user_permissions_result = $this->writeUserPermissions($username, $permissions);
		if (isset($write_user_permissions_result['error'])) return $write_user_permissions_result;

		// add user to the database
		$account_data = array(
			'username' => $username,
			'pass' => $this->security_model->getPasswordHash($password),
			'perm' => $permissions,
		);
		$this->db->insert($this->table, $account_data);

		return true;
	}


	public function deleteUser($id)
	{
		$query = $this->db->get_where($this->table, ['id' => (int)$id]);
		if ($query->num_rows() !== 1) return false;
		$username = $query->row_array()['username'];

		if ($this->validateUsername($username, false) !== true) {
			throw new Exception('Something went wrong deleting user "' . $username . '"');
		}

		// remove user config file
		$config_file_user = $this->vsftpd_model->getConfigFileUser($username);
		if (strlen($config_file_user)) {
			$res = unlink($config_file_user);
		}

		// remove user storage directory
		$storage_dir_user = $this->vsftpd_model->getStorageDirUser($username);
		if (strlen($storage_dir_user)) {
			exec('rm -rf ' . $storage_dir_user);
		}

		// remove user
		return $this->db->delete($this->table, array('id' => (int)$id));
	}

	public function changePassword($user_id, $password)
	{
		$passwordhash = $this->security_model->getPasswordHash($password);
		$this->db->where('id', (int)$user_id);
		$this->db->update($this->table, ['pass' => $passwordhash]);
	}

	public function updatePermissions($user_id, $permissions)
	{
		$username = $this->get($user_id)['username'];
		$write_user_permissions_result = $this->writeUserPermissions($username, $permissions);
		if (isset($write_user_permissions_result['error'])) return $write_user_permissions_result;

		$this->db->where('id', (int)$user_id);
		$this->db->update($this->table, ['perm' => $permissions]);
	}

	public function validateUsername($username, $check_already_exists = true)
	{
		if (strlen($username) < 4) {
			return ['error' => 'username "' . htmlentities($username) . '" is too short, minimal 4 characters required.'];
		}

		if (strlen($username) > 32) {
			return ['error' => 'username "' . htmlentities(substr($username, 0, 32)) . '…" is too long, maximum 32 characters allowed.'];
		}

		$username_allowed_characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_';
		for ($i = 0; $i < strlen($username); $i++) {
			if (strpos($username_allowed_characters, $username[$i]) === false) {
				return ['error' => 'username "' . htmlentities($username) . '" must only contain the following characters: a…z A…Z 0…9 _'];
			}
		}

		if ($check_already_exists) {
			if ($this->db->get_where($this->table, ['username' => $username])->num_rows() === 1) {
				return ['error' => 'FTP user "' . htmlentities($username) . '" already exists.'];
			}
		}

		return true;
	}

	private function writeUserPermissions($username, $permissions)
	{
		$config_file_user = $this->vsftpd_model->getConfigFileUser($username);

		// create file with correct permissions and ownership
		if (!file_exists($config_file_user)) {
			touch($config_file_user);
			chmod($config_file_user, 0660); // set file permissions

			// chown file to root (PATH=$PATH is required, because on some systems chown won't be found otherwise)
			exec('PATH=$PATH sudo chown root ' . $config_file_user, $output, $return_var);
//			if ($return_var !== 0) return ['error' => 'Cannot chown user config file.'];
		}

		// write ftp permissions in user config file
		$handle = fopen($config_file_user, 'w');
		if ($permissions === 'r') {
			fwrite($handle, "write_enable=NO\n");
		} elseif ($permissions === 'wd') {
			fwrite($handle, "write_enable=YES\n");
			fwrite($handle, "cmds_denied=DELE,RMD,RNFR,RNTO\n");
		} elseif ($permissions === 'w') {
			fwrite($handle, "write_enable=YES\n");
		}
		fclose($handle);

		return true;
	}
}
