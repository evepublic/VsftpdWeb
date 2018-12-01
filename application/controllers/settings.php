<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'abstract_vstpdweb.php';

class Settings extends Abstract_Vstpdweb
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('settings_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data = $this->getSiteData();

		$data['title'] = 'FTP Settings';

		foreach ($this->settings_model->get_all_settings() as $setting) {
			if (strpos($setting['name'], 'disk') === 0) {
				$data['get' . $setting['name']] = $setting['value'];
				$data['get' . $setting['name'] . '_def'] = $setting['defval'];
			} else {
				$data[$setting['name']] = $setting['value'];
			}
		}

		$data['header'] = 'templates/header';
		$data['content'] = 'settings/index';
		$this->load->view('templates/main', $data);
	}

	public function change()
	{
		$this->settings_model->change();
		if ($this->input->post('general_settings')) {
			$this->session->set_flashdata('general_settings_updated', true);
		}

		if ($this->input->post('mail_settings')) {
			$this->session->set_flashdata('mail_settings_updated', true);
		}
		redirect('settings');
	}

	public function changepassword()
	{
		$this->form_validation->set_rules('currentpassword', 'current password', 'required|min_length[4]');
		$this->form_validation->set_rules('newpassword', 'new password', 'required|matches[newpasswordconfirm]|min_length[4]');
		$this->form_validation->set_rules('newpasswordconfirm', 'confirm new password', 'required|matches[newpasswordconfirm]|min_length[4]');

		$data = [];
		$error = ($this->form_validation->run() === false) ? true : false;

		if (!$error) {
			$username = $this->session->userdata('username');
			$currentpassword = $this->input->post('currentpassword');
			$newpassword = $this->input->post('newpassword');

			$this->load->model('login_model');

			$changepasswordresult = $this->login_model->changePassword($username, $currentpassword, $newpassword);
			if ($changepasswordresult !== true) {
				$data['password_change_error'] = $changepasswordresult['error'];
				$error = true;
			}
		}

		if ($error) {
			$data = array_merge($data, $this->getSiteData());
			$data['header'] = 'templates/header';
			$data['content'] = 'settings/error';
			$this->load->view('templates/main', $data);
		} else {
			$this->session->set_flashdata('password_changed', true);
			redirect('settings');
		}
	}
}
