<?php

class Log_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getLogData()
	{
		$this->load->model('settings_model');
		$log_path = $this->settings_model->get_settings('log_path');

		if (!file_exists($log_path)) {
			return ['error' => 'The log file cannot be found. Check the log file path: ' . htmlentities($log_path)];
		} elseif (filesize($log_path) === 0) {
			return ['error' => 'The log file is empty'];
		}

		$result = [];
		$ic = 0;
		$ic_max = 200; // stops after this number of rows
		$handle = popen("tac $log_path ", "r");
		while (!feof($handle) && ++$ic <= $ic_max) {
			$buffer = fgets($handle, 4096);
			if ($buffer === false) { // prevent empty record
				continue;
			}

			//size
			$size = strstr($buffer, "/", true);
			$pos = strrpos($size, ".") + 1;

			$size = substr($size, $pos);
			$size = strstr($size, " ");

			$si_prefix = array('B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB');
			$base = 1024;
			if ($size == 0) {
				$msize = '0 ' . $si_prefix[0];
			} else {
				$class = min((int)log((int)$size, $base), count($si_prefix) - 1);
				$msize = sprintf('%1.2f', (int)$size / pow($base, $class)) . ' ' . $si_prefix[$class];
			}

			//name
			$name = strstr($buffer, "/");
			$name = strstr($name, " _ ", true);
			$name = substr($name, 0, -1);

			//state and user
			$state = strstr($buffer, " _ ");
			$user = $state;
			$state = strstr($state, "g", true);
			$state = substr($state, 3, -1);
			if ($state == 'i') $state = 'Uploaded';
			else if ($state == 'o') $state = 'Downloaded';

			$user = strstr($user, "g");
			$user = strstr($user, "ftp", true);
			$user = substr($user, 2, -1);

			$info = strstr($buffer, $size, true);

			$result[] = compact(['info', 'msize', 'state', 'user', 'name']);
		}
		pclose($handle);

		return $result;
	}
}
