<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

abstract class Abstract_Vstpdweb extends CI_Controller
{
	protected $title;

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
		$data['site_name_display'] = $this->settings_model->getSiteName();
		$data['title'] = $this->title;
		$data['header'] = 'templates/header';
		return $data;
	}

	public function validate_permissions($permissions)
	{
		$this->form_validation->set_message(__FUNCTION__, 'The {field} field has an invalid value: ' . htmlentities($permissions));
		return in_array($permissions, ['r', 'wd', 'w']);
	}
}