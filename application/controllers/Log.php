<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Log extends MY_Controller
{
	protected $title = 'FTP Log';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('log_model');
	}

	public function index()
	{
		$data = $this->getSiteData();

		$data['log_data'] = $this->log_model->getLogData();

		$data['content'] = 'log/index';
		$this->load->view('templates/main', $data);
	}
}
