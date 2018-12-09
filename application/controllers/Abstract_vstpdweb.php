<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

abstract class Abstract_Vstpdweb extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->checkIsvalidated();
	}

	private function checkIsvalidated()
	{
		if (!$this->session->userdata('validated')) {
			redirect('login');
		}
	}

	private function getDiskSpaceData()
	{
		$this->load->model('disk_model');
		return $this->disk_model->getDiskSpaceData();
	}

	protected function getSiteData()
	{
		$data = $this->getDiskSpaceData();
		$this->load->model('settings_model');
		$data['site_name'] = $this->settings_model->getSiteName();
		return $data;
	}
}