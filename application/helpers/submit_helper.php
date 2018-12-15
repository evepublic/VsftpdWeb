<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('form_submit_flash_message')) {
	function form_submit_flash_message($session_var)
	{
		$html = '';

		if (isset($_SESSION[$session_var]['success'])) {
			$html .= '<div class="alert alert-success">' . "\n";
			$html .= "\t" . $_SESSION[$session_var]['success'] . "\n";
			$html .= '</div>' . "\n";
		}

		if (isset($_SESSION[$session_var]['error'])) {
			$html .= '<div class="alert alert-danger">' . "\n";
			$html .= "\t" . $_SESSION[$session_var]['error'] . "\n";
			$html .= '</div>' . "\n";
		}

		return $html;
	}
}

if (!function_exists('form_submit_scroll_script')) {
	function form_submit_scroll_script()
	{
		$script_html = '';

		if (isset($_SESSION['form_submit_scroll_id'])) {
			$script_html .= '<script>' . "\n";
			$script_html .= "\t" . 'document.querySelector("#' . $_SESSION['form_submit_scroll_id'] . '").scrollIntoView();' . "\n";
			$script_html .= '</script>' . "\n";
		}

		return $script_html;
	}
}
