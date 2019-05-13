<?php

class Vsftpd_model extends CI_Model
{
	private $config_defaults = [
		'xferlog_file' => '/var/log/xferlog'
	];
	private $config_settings;

	public function __construct()
	{
		parent::__construct();
		$this->config_settings = $this->getConfigSettings();
	}

	public function get($name)
	{
		return $this->config_settings[$name];
	}

	public function getStorageDirUser($username)
	{
		$result = str_replace($this->config_settings['user_sub_token'], $username, $this->config_settings['local_root'], $replace_count);
		if ($replace_count !== 1) throw new Exception('Invalid user storage directory');
		return $result;
	}

	public function getConfigFileUser($username)
	{
		$user_config_dir = $this->config_settings['user_config_dir'];
		if (substr('user_config_dir', -1) !== '/') $user_config_dir .= '/';
		return $user_config_dir .= $username;
	}

	private function getConfigSettings()
	{
		$this->load->model('settings_model');
		$config_file = $this->settings_model->get('vsftpd_config_path');
		$config_settings = $this->config_defaults;
		$handle = fopen($config_file, 'r');
		while (($line = fgets($handle)) !== false) {
			$line = trim($line);
			if (strlen($line) and $line[0] !== '#') {
				$setting = explode('=', $line, 2);
				$config_settings[trim($setting[0])] = trim($setting[1]);
			}
		}
		return $config_settings;
	}
}
