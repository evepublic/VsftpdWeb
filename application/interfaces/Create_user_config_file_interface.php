<?php

interface Create_user_config_file_interface
{
	const CREATE_USER_CONFIG_SUCCESS = 0;
	const CREATE_USER_CONFIG_SUDO_FAILED = 1;
	const CREATE_USER_CONFIG_NOT_CLI = 101;
	const CREATE_USER_CONFIG_NOT_ROOT = 102;
	const CREATE_USER_CONFIG_NO_PARAMETERS = 103;
	const CREATE_USER_CONFIG_TOO_MANY_PARAMETERS = 104;
	const CREATE_USER_CONFIG_USERNAME_TOO_SHORT = 105;
	const CREATE_USER_CONFIG_USERNAME_TOO_LONG = 106;
	const CREATE_USER_CONFIG_USERNAME_INVALID = 107;
	const CREATE_USER_CONFIG_FILE_ALREADY_EXISTS = 108;
	const CREATE_USER_CONFIG_FILE_CREATION_FAILED = 109;
	const CREATE_USER_CONFIG_CHGRP_FAILED = 110;
	const CREATE_USER_CONFIG_CHMOD_FAILED = 111;

	const CREATE_USER_CONFIG_ERROR_MESSAGES = [
		self::CREATE_USER_CONFIG_SUDO_FAILED => 'Create user config file: sudo failed.',
		self::CREATE_USER_CONFIG_NOT_CLI => 'Create user config file: script must run in CLI.',
		self::CREATE_USER_CONFIG_NOT_ROOT => 'Create user config file: script must run as root.',
		self::CREATE_USER_CONFIG_NO_PARAMETERS => 'Create user config file: no parameters specified.',
		self::CREATE_USER_CONFIG_TOO_MANY_PARAMETERS => 'Create user config file: too many parameters .',
		self::CREATE_USER_CONFIG_USERNAME_TOO_SHORT => 'Create user config file: username too short.',
		self::CREATE_USER_CONFIG_USERNAME_TOO_LONG => 'Create user config file: username too long.',
		self::CREATE_USER_CONFIG_USERNAME_INVALID => 'Create user config file: username invalid.',
		self::CREATE_USER_CONFIG_FILE_ALREADY_EXISTS => 'Create user config file: config file already exists.',
		self::CREATE_USER_CONFIG_FILE_CREATION_FAILED => 'Create user config file: config file creation failed.',
		self::CREATE_USER_CONFIG_CHGRP_FAILED => 'Create user config file: chgrp failed.',
		self::CREATE_USER_CONFIG_CHMOD_FAILED => 'Create user config file: chmod failed.',
	];
}
