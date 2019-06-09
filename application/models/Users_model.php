<?php

get_instance()->load->iface('Create_user_config_file_interface');

class Users_model extends CI_Model implements Create_user_config_file_interface
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

	public function get($user_id)
	{
		return $this->db->get_where($this->table, ['id' => $user_id])->row();
	}

	public function getAll()
	{
		return $this->db->get($this->table)->result();
	}

	public function createUser($username, $password, $storage_directory, $permissions)
	{
		$storage_directory = $this->sanitizeStorageDirectory($storage_directory);

		// add user to the database
		$account_data = [
			'username' => $username,
			'password' => $this->security_model->getPasswordHash($password),
			'permissions' => $permissions,
			'storage_directory' => $storage_directory,
		];
		$this->db->insert($this->table, $account_data);
		$account_data['id'] = $this->db->insert_id();

		$user = (object)$account_data;
		return $this->writeUserConfig($user);
	}

	public function deleteUser($user_id)
	{
		$query = $this->db->get_where($this->table, ['id' => (int)$user_id]);
		if ($query->num_rows() !== 1) return false;
		$username = $query->row()->username;

		if ($this->validateUsername($username, false) !== true) {
			throw new Exception('Something went wrong deleting user "' . $username . '"');
		}

		// remove user config file
		$config_file_user = $this->vsftpd_model->getConfigFileUser($username);
		if (strlen($config_file_user)) {
			unlink($config_file_user);
		}

		// remove user
		return $this->db->delete($this->table, ['id' => (int)$user_id]);
	}

	public function changePassword($user_id, $password)
	{
		$passwordhash = $this->security_model->getPasswordHash($password);
		$this->db->where('id', (int)$user_id)->update($this->table, ['password' => $passwordhash]);
	}

	public function updatePermissions($user_id, $permissions)
	{
		$this->db->where('id', (int)$user_id)->update($this->table, ['permissions' => $permissions]);
		return $this->writeUserConfig($this->get($user_id));
	}

	public function updateStorageDirectory($user_id, $storage_directory)
	{
		$storage_directory = $this->sanitizeStorageDirectory($storage_directory);
		$this->db->where('id', (int)$user_id)->update($this->table, ['storage_directory' => $storage_directory]);
		return $this->writeUserConfig($this->get($user_id));
	}

	private function writeUserConfig(stdClass $user)
	{
		$user_storage_path = $this->settings_model->get('user_base_path') . $user->storage_directory;
		if (!file_exists($user_storage_path)) {
			if (!mkdir($user_storage_path, 0777, true)) return ['error' => 'Failed to create user storage directory for user "' . htmlentities($username) . '"'];

			$current_dir = $this->settings_model->get('user_base_path');
			foreach (explode('/', $user->storage_directory) as $parts) {
				$current_dir .= $parts . '/';
				chmod($current_dir, 0777);
			}
		}

		$config_file_user = $this->vsftpd_model->getConfigFileUser($user->username);

		if (!file_exists($config_file_user)) {
			// create file with correct permissions and ownership
			$create_user_config_file_script = realpath(APPPATH . 'scripts/create_user_config_file.php');
			$command = 'sudo --user=#0 --group=#' . posix_getgid() . ' /usr/bin/php ' . $create_user_config_file_script . ' ' . $user->username;
			exec($command, $output, $return_var);
			if ($return_var !== self::CREATE_USER_CONFIG_SUCCESS) {
				if (isset(self::CREATE_USER_CONFIG_ERROR_MESSAGES[$return_var])) {
					return ['error' => self::CREATE_USER_CONFIG_ERROR_MESSAGES[$return_var]];
				} else {
					throw new Exception('unknown result (' . htmlentities($return_var) . ') running ' . basename($create_user_config_file_script));
				}
			}
		}

		$config_file_user_data = '';

		$config_file_user_data .= "local_root=$user_storage_path\n";

		switch ($user->permissions) {
			case 'r':
				$config_file_user_data .= "write_enable=NO\n";
				break;
			case 'wd':
				$config_file_user_data .= "write_enable=YES\n";
				$config_file_user_data .= "cmds_denied=DELE,RMD,RNFR,RNTO\n";
				break;
			case 'w':
				$config_file_user_data .= "write_enable=YES\n";
				break;
			default:
				throw new Exception();
		}

		file_put_contents($config_file_user, $config_file_user_data);
		return true;
	}

	public function validateUsername($username, $check_already_exists = true)
	{
		$this->load->library('data_validation');
		$this->data_validation->reset_validation();
		$this->data_validation->set_data(['username' => $username]);

		$rules = 'required|min_length[4]|max_length[32]|alpha_numeric_underscore';
		$rules .= ($check_already_exists) ? "|is_unique[{$this->table}.username]" : '';
		$this->data_validation->set_rules('username', 'username', $rules);

		if ($this->data_validation->run() === false) {
			return ['error' => $this->data_validation->error_string()];
		}

		return true;
	}

	public function validatePassword($password)
	{
		$this->load->library('data_validation');
		$this->data_validation->reset_validation();
		$this->data_validation->set_data(['password' => $password]);

		$rules = 'required|min_length[4]|max_length[32]';
		$this->data_validation->set_rules('password', 'password', $rules);

		if ($this->data_validation->run() === false) {
			return ['error' => $this->data_validation->error_string()];
		}

		return true;
	}

	public function validateStorageDirectory($storage_directory)
	{
		$this->load->library('data_validation');
		$this->data_validation->reset_validation();
		$this->data_validation->set_data(['storage_directory' => $storage_directory]);

		$rules = 'required|min_length[1]|max_length[128]|alpha_numeric_underscore_slash|not_only_slashes';
		$this->data_validation->set_rules('storage_directory', 'storage directory', $rules);

		if ($this->data_validation->run() === false) {
			return ['error' => $this->data_validation->error_string()];
		}

		return true;
	}

	public function sanitizeStorageDirectory($storage_directory)
	{
		$storage_directory = trim($storage_directory, '/');
		$storage_directory = preg_replace('#/+#', '/', $storage_directory);
		return $storage_directory;
	}
}
