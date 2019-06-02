<?php

class Data_validation extends CI_Form_validation
{
	public function __construct($rules = array())
	{
		parent::__construct($rules);

		/**
		 * @see https://github.com/bcit-ci/CodeIgniter/blob/3.1.9/system/language/english/form_validation_lang.php
		 */
		$messages = [
			'form_validation_alpha_numeric_underscore' => 'The {field} field may only contain alpha-numeric characters and underscores.',
			'form_validation_alpha_numeric_underscore_slash' => 'The {field} field may only contain alpha-numeric characters, underscores and slashes.',
			'form_validation_not_only_slashes' => 'The {field} field may not only contain slashes.',
		];
		$this->CI->lang->language += $messages;
	}


	public function alpha_numeric_underscore($str)
	{
		return (bool)preg_match('/^[a-z0-9_]+$/i', $str);
	}

	public function alpha_numeric_underscore_slash($str)
	{
		return (bool)preg_match('/^[a-z0-9_\/]+$/i', $str);
	}

	public function not_only_slashes($str)
	{
		return (bool)(strlen(trim($str, '/')) > 0);
	}
}
