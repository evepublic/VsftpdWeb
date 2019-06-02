<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{
	protected $title = 'Login';

	function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
		$this->load->library('form_validation');
		$this->load->helper('submit');
	}

	public function index()
	{
		$this->load->model('settings_model');
		$data['site_name_display'] = $this->settings_model->getSiteNameDisplay();
		$data['title'] = $this->title;
		$data['content'] = 'login/index';
		$this->load->view('templates/main_login', $data);
	}

	public function process()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		// Validate if the user can login
		if (!$this->login_model->validate($username, $password)) {
			// If user did not validate, show the login page again
			$this->session->set_flashdata('login', ['error' => 'Incorrect username or password.']);
			redirect('login');
		} else {
			// If the user did validate, set session variable and send to members area
			$userdata = [
				'username' => $username,
				'validated' => true,
			];
			$this->session->set_userdata($userdata);
			redirect();
		}
	}
}
