<?php

require_once __DIR__ . '/../interfaces/Create_user_config_file_interface.php';

class CreateUserConfigFile implements Create_user_config_file_interface
{
	private $vsftpd_users_directory = '/etc/vsftpd_users/';

	public function run($argv)
	{
		if (php_sapi_name() !== 'cli') {
			return self::CREATE_USER_CONFIG_NOT_CLI;
		}

		if (posix_geteuid() !== 0) {
			return self::CREATE_USER_CONFIG_NOT_ROOT;
		}

		if (count($argv) === 1) {
			return self::CREATE_USER_CONFIG_NO_PARAMETERS;
		}

		// validate, because sudoers create_user_config_file.php [a-zA-Z0-9_]* can break out
		if (count($argv) > 2) {
			return self::CREATE_USER_CONFIG_TOO_MANY_PARAMETERS;
		}

		$username = $argv[1];
		if (strlen($username) < 4) {
			return self::CREATE_USER_CONFIG_USERNAME_TOO_SHORT;
		}

		if (strlen($username) > 32) {
			return self::CREATE_USER_CONFIG_USERNAME_TOO_LONG;
		}

		if ((bool)preg_match('/^[a-z0-9_]+$/i', $username) === false) {
			return self::CREATE_USER_CONFIG_USERNAME_INVALID;
		}

		$user_config_file = $this->vsftpd_users_directory . $username;
		if (file_exists($user_config_file)) {
			return self::CREATE_USER_CONFIG_FILE_ALREADY_EXISTS;
		}

		if (($handle = fopen($user_config_file, 'w')) === false) {
			return self::CREATE_USER_CONFIG_FILE_CREATION_FAILED;
		}
		fclose($handle);

		if (chgrp($user_config_file, posix_getgid()) === false) {
			return self::CREATE_USER_CONFIG_CHGRP_FAILED;
		}

		if (chmod($user_config_file, 0660) === false) {
			return self::CREATE_USER_CONFIG_CHMOD_FAILED;
		}

		return self::CREATE_USER_CONFIG_SUCCESS;
	}
}

$create_user_config_file = new CreateUserConfigFile($argv);
$result = $create_user_config_file->run($argv);

exit($result);
