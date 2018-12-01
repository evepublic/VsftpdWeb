<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'abstract_vstpdweb.php';

class Settings extends Abstract_Vstpdweb
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('settings_model');
	}

	public function index()
	{
		$data = $this->getSiteData();

		$data['title'] = 'FTP Settings';

		$data['site_url'] = $this->settings_model->get_settings('site_url');
		$data['def_path'] = $this->settings_model->get_settings('user_path');
		$data['getdisk1'] = $this->settings_model->get_settings('disk1');
		$data['getdisk2'] = $this->settings_model->get_settings('disk2');
		$data['getdisk3'] = $this->settings_model->get_settings('disk3');
		$data['log_path'] = $this->settings_model->get_settings('log_path');
		$data['def_path_def'] = $this->settings_model->get_settings_def('user_path');
		$data['getdisk1_def'] = $this->settings_model->get_settings_def('disk1');
		$data['getdisk2_def'] = $this->settings_model->get_settings_def('disk2');
		$data['getdisk3_def'] = $this->settings_model->get_settings_def('disk3');

		$data['mail_server'] = $this->settings_model->get_settings('mail_server');
		$data['mail_port'] = $this->settings_model->get_settings('mail_port');
		$data['mail_user'] = $this->settings_model->get_settings('mail_user');
		$data['mail_password'] = $this->settings_model->get_settings('mail_password');
		$data['mail_from'] = $this->settings_model->get_settings('mail_from');

		$this->load->view('templates/header', $data);
		$this->load->view('settings/index', $data);
		$this->load->view('templates/footer');
	}

	public function change()
	{
		$this->settings_model->change();
		header("Location: " . base_url() . "index.php/settings");
	}

	public function changepass()
	{
		$this->form_validation->set_rules('adminpass', 'Password', 'matches[repass]|min_length[4]');

		if ($this->form_validation->run() === false) {
			$data = $this->getSiteData();

			$this->load->view('templates/header', $data);
			$this->load->view('settings/error');
			$this->load->view('templates/footer');
		} else {
			$this->settings_model->changepass();
			header("Location: " . base_url() . "index.php/settings");
		}
	}
}
