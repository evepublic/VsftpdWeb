<?php

class Log_model extends CI_Model
{
	/*
	* @see https://www.systutorials.com/docs/linux/man/5-xferlog/
	*/
	private $xferlog_fields = [
		'current-time_DDD',
		'current-time_MMM',
		'current-time_dd',
		'current-time_hh:mm:ss',
		'current-time_YYYY',
		'transfer-time',
		'remote-host',
		'file-size',
		'filename',
		'transfer-type',
		'special-action-flag',
		'direction',
		'access-mode',
		'username',
		'service-name',
		'authentication-method',
		'authenticated-user-id',
		'completion-status',
	];

	public function __construct()
	{
		parent::__construct();
	}

	public function getLogData()
	{
		$this->load->model('settings_model');
		$log_path = $this->settings_model->get('log_path');

		if (!file_exists($log_path)) {
			return ['error' => 'The log file cannot be found. Check the log file path: ' . $log_path];
		} elseif (filesize($log_path) === 0) {
			return ['error' => 'The log file is empty'];
		}

		$result = [];
		$ic = 0;
		$ic_max = 200; // stops after this number of rows
		$handle = popen("tac $log_path ", "r");
		while ((($buffer = fgets($handle, 4096)) !== false) && ++$ic <= $ic_max) {
			// since xferlog is delimited by spaces, simply tokenize by spaces (spaces in file names are mapped to _)
			$logitem_tmp = explode(' ', trim($buffer));
			foreach ($this->xferlog_fields as $index => $field_name) {
				$logitem[$field_name] = $logitem_tmp[$index];
			}

			// size
			$size = $logitem['file-size'];
			$si_prefix = array('B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB');
			$base = 1024;
			if ($size == 0) {
				$msize = '0 ' . $si_prefix[0];
			} else {
				$class = min((int)log((int)$size, $base), count($si_prefix) - 1);
				$msize = sprintf('%1.2f', $size / pow($base, $class)) . ' ' . $si_prefix[$class];
			}

			// name
			$name = $logitem['filename'];

			// state
			if ($logitem['direction'] == 'i') $state = 'Uploaded';
			elseif ($logitem['direction'] == 'o') $state = 'Downloaded';
			elseif ($logitem['direction'] == 'd') $state = 'Deleted';

			// user
			$user = $logitem['username'];

			$date = $logitem['current-time_DDD'] . ' ' . $logitem['current-time_MMM'] . ' ' . $logitem['current-time_dd'];
			$time = $logitem['current-time_hh:mm:ss'];
			$remotehost = $logitem['remote-host'];
			$transfertime = $logitem['transfer-time'];

			$result[] = compact(['info', 'date', 'time', 'remotehost', 'transfertime', 'msize', 'state', 'user', 'name']);
		}
		pclose($handle);

		return $result;
	}
}
