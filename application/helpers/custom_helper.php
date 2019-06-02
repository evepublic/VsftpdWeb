<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('public_path')) {
	function public_path()
	{
		$script_path_split = explode('/', $_SERVER['SCRIPT_FILENAME']);
		if (count($script_path_split) >= 2 && $script_path_split[count($script_path_split) - 2] === 'public') {
			return '';
		} else {
			return 'public/';
		}
	}
}

if (!function_exists('dd')) {
	function dd($arg1)
	{
		call_user_func_array('var_dump', func_get_args());
		exit(1);
	}
}
