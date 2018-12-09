<?php

class Vsftpd_model extends CI_Model
{
	private $config_file = '/etc/vsftpd.conf';
	private $config_defaults = [
		'xferlog_file' => '/var/log/xferlog'
	];

	private static $config_settings = null;

	public function __construct()
	{
		parent::__construct();
	}

	public function getConfigSettings()
	{
		if (self::$config_settings !== null) {
			return self::$this->vsftpd_config_settings;
		}

		self::$config_settings = $this->config_defaults;
		$handle = fopen($this->config_file, 'r');
		while (($line = fgets($handle)) !== false) {
			$line = trim($line);
			if (strlen($line) and $line[0] !== '#') {
				$setting = explode('=', $line, 2);
				self::$config_settings[trim($setting[0])] = trim($setting[1]);
			}
		}
		return self::$config_settings;
	}
}
