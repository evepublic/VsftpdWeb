<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'abstract_vstpdweb.php';

class Monitor extends Abstract_Vstpdweb
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('monitor_model');
	}

	public function index()
	{
		$data = $this->getSiteData();

		$data['title'] = 'FTP Monitor';
		header("refresh: 5;");

		$data['mon1'] = $this->monitor_model->getVsftpdProcesses();
		$data['mon2'] = $this->monitor_model->getVsftpdConnectedUsers(); // does not work, ftp users are shown 'gone - no logout'

		$data['header'] = 'templates/header';
		$data['content'] = 'monitor/index';
		$this->load->view('templates/main', $data);
	}
}
