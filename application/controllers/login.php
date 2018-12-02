<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$this->load->model('settings_model');
		$data['site_name'] = $this->settings_model->getSiteName();

		$data['header'] = 'templates/header_login';
		$data['content'] = 'login/index';
		$this->load->view('templates/main', $data);
	}

	public function process()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		// Validate if the user can login
		if (!$this->login_model->validate($username, $password)) {
			// If user did not validate, show login page again
			$this->session->set_flashdata('login_failed', true);
			redirect('login');
		} else {
			// If user did validate, set session variable and send to members area
			$userdata = array(
				'username' => $username,
				'validated' => true
			);
			$this->session->set_userdata($userdata);
			redirect();
		}
	}
}
