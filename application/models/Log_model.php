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
		$this->load->model('vsftpd_model');
		$xferlog_file = $this->vsftpd_model->get('xferlog_file');

		if (!file_exists($xferlog_file)) {
			return ['error' => 'The log file cannot be found. Check the log file path: ' . $xferlog_file];
		} elseif (filesize($xferlog_file) === 0) {
			return ['error' => 'The log file is empty'];
		}

		$result = [];
		$ic = 0;
		$ic_max = 200; // stops after this number of rows
		$handle = popen("tac $xferlog_file ", "r");
		while ((($buffer = fgets($handle, 4096)) !== false) && ++$ic <= $ic_max) {
			// since xferlog is delimited by spaces, simply tokenize by spaces (spaces in file names are mapped to _)
			$logitem_tmp = explode(' ', trim($buffer));

			// current-time_dd does not have a trailing 0, So shift field if current-time_dd < 10
			$current_time_dd_index = array_search('current-time_dd', $this->xferlog_fields, true);
			if ($logitem_tmp[$current_time_dd_index] === '') {
				array_splice($logitem_tmp, $current_time_dd_index, 1);
			}

			foreach ($this->xferlog_fields as $index => $field_name) {
				$logitem[$field_name] = $logitem_tmp[$index];
			}

			$date = $logitem['current-time_DDD'] . ' ' . $logitem['current-time_MMM'] . ' ' . $logitem['current-time_dd'];
			$time = $logitem['current-time_hh:mm:ss'];

			$transfertime = $logitem['transfer-time'] . ' s';

			$remotehost = $logitem['remote-host'];

			$size = $logitem['file-size'];
			$si_prefix = ['B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB'];
			$base = 1024;
			if ($size == 0) {
				$filesize = '0 ' . $si_prefix[0];
			} else {
				$class = min((int)log((int)$size, $base), count($si_prefix) - 1);
				$filesize = sprintf('%1.2f', $size / pow($base, $class)) . ' ' . $si_prefix[$class];
			}

			$filename = $logitem['filename'];

			if ($logitem['direction'] == 'i') $action = 'uploaded';
			elseif ($logitem['direction'] == 'o') $action = 'downloaded';
			elseif ($logitem['direction'] == 'd') $action = 'deleted';

			$username = $logitem['username'];

			if ($logitem['completion-status'] == 'c') $status = 'complete';
			elseif ($logitem['completion-status'] == 'i') $status = 'incomplete';

			$result[] = compact(['date', 'time', 'transfertime', 'remotehost', 'filesize', 'filename', 'action', 'username', 'status']);
		}
		pclose($handle);

		return $result;
	}
}
