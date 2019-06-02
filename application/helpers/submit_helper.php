<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('form_submit_flash_message')) {
	function form_submit_flash_message($session_var)
	{
		$html = '';

		if (isset($_SESSION[$session_var]['success'])) {
			$html .= <<<HTML
<div class="alert alert-success">
	{$_SESSION[$session_var]['success']}
</div>
HTML;
		}

		if (isset($_SESSION[$session_var]['error'])) {
			$html = <<<HTML
<div class="alert alert-danger">
	{$_SESSION[$session_var]['error']}
</div>
HTML;
		}

		return $html;
	}
}
