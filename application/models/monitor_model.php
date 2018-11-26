<?php

class Monitor_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getVsftpdProcesses()
	{
		$command = 'ps -ef';
		exec($command, $command_output);

		$headers = array_values(array_filter(explode(' ', array_shift($command_output)), function ($var) {
			return ($var !== '');
		}));

		// collect vsftpd processes
		$vsftpd_processes = [];
		foreach ($command_output as $line) {
			$process = [];
			foreach ($headers as $header) {
				if (count($process) === count($headers) - 1) {
					$process[$header] = $line;
					break;
				}
				$process[$header] = substr($line, 0, $pos = strpos($line, ' '));
				$line = ltrim(substr($line, $pos));
			}

			if (strpos($process['CMD'], 'vsftpd: ') === 0) {
				$vsftpd_processes[$process['PID']] = $process;
			}
		}

		$result = [];
		foreach ($vsftpd_processes as $process) {
			$pid = $process['PID'];
			$ppid = $process['PPID'];
			$parent_pid[$pid] = $ppid;

			$cmd = substr($process['CMD'], 8);
			if ($cmd === 'LISTENER') {
				$ref = &$result;
				$ref['name'] = 'listener';
			} elseif (substr($cmd, -11) === ': connected') {
				$ref = &$result['children'][$pid];
				$ref['name'] = 'connection';
				$ref['ip'] = substr($cmd, 0, $pos = strpos($cmd, ':'));
			} elseif (substr($cmd, -13) === ': SSL handler') {
				$ref = &$result[$ppid][$pid];
				$ref['name'] = 'sslhandler';
			} else {
				$ref = &$result['children'][$ppid]['children'][$pid];
				$ref['name'] = 'command';

				$ref['ip'] = substr($cmd, 0, $pos = strpos($cmd, '/'));

				$cmd = substr($cmd, $pos + 1);
				$ref['user'] = substr($cmd, 0, $pos = strpos($cmd, ':'));

				$cmd = substr($cmd, $pos + 2);
				$pos = strpos($cmd, ' ');
				if ($pos !== false) {
					$ref['command'] = substr($cmd, 0, $pos);
					$ref['parameter'] = substr($cmd, $pos + 1);
				} else {
					$ref['command'] = $cmd;
					$ref['parameter'] = null;
				}
			}

			$ref['starttime'] = $process['STIME'];
			$ref['pid'] = $pid;
			$ref['ppid'] = $ppid;
		}

		return $result;
	}

	public function getVsftpdConnectedUsers()
	{
		$command = 'last | grep vsftpd | grep still'; // does not work, ftp users are shown 'gone - no logout'
		exec($command, $command_output);
		return $command_output;
	}
}
