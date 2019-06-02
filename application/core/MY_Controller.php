<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

abstract class MY_Controller extends CI_Controller
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

	protected function getSiteData()
	{
		$this->load->model('disk_model');
		$data = $this->disk_model->getDiskSpaceData();

		$this->load->model('settings_model');
		$data['site_name_display'] = $this->settings_model->getSiteNameDisplay();
		$data['title'] = $this->title;

		return $data;
	}

	public function validate_permissions($permissions)
	{
		$this->form_validation->set_message(__FUNCTION__, 'The {field} field has an invalid value: ' . htmlentities($permissions));
		return in_array($permissions, ['r', 'wd', 'w']);
	}
}
