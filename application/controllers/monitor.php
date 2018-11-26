<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Monitor extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('disk_model');
		$this->load->model('monitor_model');
		$this->check_isvalidated();
		$this->disk_space();
	}

	public function disk_space()
	{
	}

	private function check_isvalidated()
	{
		if (!$this->session->userdata('validated')) {
			redirect('login');
		}
	}

	public function index()
	{
		$data['disk1'] = $this->disk_model->get_space('disk1');
		$data['disk2'] = $this->disk_model->get_space('disk2');
		$data['disk3'] = $this->disk_model->get_space('disk3');

		$data['title'] = 'FTP Monitor';

		header("refresh: 5;");

		$data['mon1'] = $this->monitor_model->getVsftpdProcesses();
		$data['mon2'] = $this->monitor_model->getVsftpdConnectedUsers(); // does not work, ftp users are shown 'gone - no logout'

		$this->load->view('templates/header', $data);
		$this->load->view('monitor/index', $data);
		$this->load->view('templates/footer');
	}
}
