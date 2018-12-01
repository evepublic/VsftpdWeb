<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'abstract_vstpdweb.php';

class Log extends Abstract_Vstpdweb
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('log_model');
	}

	public function index()
	{
		$data = $this->getSiteData();

		$data['log_data'] = $this->log_model->getLogData();

		$data['title'] = 'FTP LOG';

		$this->load->view('templates/header', $data);
		$this->load->view('log/index', $data);
		$this->load->view('templates/footer');
	}
}
