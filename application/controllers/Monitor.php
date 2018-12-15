<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'Abstract_vstpdweb.php';

class Monitor extends Abstract_Vstpdweb
{
	protected $title = 'Service Monitor';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('monitor_model');
	}

	public function index()
	{
		header("refresh: 5;");

		$data = $this->getSiteData();

		$data['mon1'] = $this->monitor_model->getVsftpdProcesses();
		$data['mon2'] = $this->monitor_model->getVsftpdConnectedUsers(); // does not work, ftp users are shown 'gone - no logout'

		$data['content'] = 'monitor/index';
		$this->load->view('templates/main', $data);
	}
}
