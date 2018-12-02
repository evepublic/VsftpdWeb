<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'abstract_vstpdweb.php';

class Users extends Abstract_Vstpdweb
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('users_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data = $this->getSiteData();

		$data['users'] = $this->users_model->get_users();

		$data['title'] = 'FTP Users';
		$data['def_path'] = $this->users_model->get_path('user_path');

		$this->form_validation->set_rules('user', 'username', 'required|is_unique[accounts.username]');
		$this->form_validation->set_rules('upass', 'password', 'required|matches[repass]|min_length[4]');
		$this->form_validation->set_rules('repass', 'password confirmation', 'required');

		if ($this->form_validation->run() === false) {
			$data['header'] = 'templates/header';
			$data['content'] = 'users/index';
			$this->load->view('templates/main', $data);
		} else {
			$this->users_model->new_user();
			$this->session->set_flashdata('user_created', $this->input->post('user'));
			redirect('users');
		}
	}

	public function delete($id)
	{
		$this->users_model->delete_user($id);
		$data['header'] = 'templates/header';
		$data['content'] = 'users/delete';
		$this->load->view('templates/main', $data);
	}

	public function edit($slug)
	{
		$data = $this->getSiteData();

		$data['user_item'] = $this->users_model->get_users($slug);

		if (empty($data['user_item'])) {
			show_404();
		}

		$data['def_path'] = $this->users_model->get_path('user_path');
		$data['getdisk1'] = $this->users_model->get_path('disk1');
		$data['getdisk2'] = $this->users_model->get_path('disk2');

		$data['checkpath'] = "";
		$data['checked'] = 0;
		if ($data['user_item']['path'] == 'none') $data['checked'] = 1;
		else {
			$find = strpos($data['user_item']['path'], $data['getdisk1']);
			if ($find !== false) {
				$data['checked'] = 2;
				$data['checkpath'] = substr($data['user_item']['path'], strlen($data['getdisk1']));
			}

			$find = strpos($data['user_item']['path'], $data['getdisk2']);
			if ($find !== false) {
				$data['checked'] = 3;
				$data['checkpath'] = substr($data['user_item']['path'], strlen($data['getdisk2']));
			}
		}

		$data['title'] = 'Edit User ' . $data['user_item']['username'];

		$data['header'] = 'templates/header';
		$data['content'] = 'users/edit';
		$this->load->view('templates/main', $data);
	}

	public function create()
	{
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('text', 'text', 'required');

		if ($this->form_validation->run() === false) {
			$data['header'] = 'templates/header';
			$data['content'] = 'users/create';
			$this->load->view('templates/main', $data);
		} else {
			$this->news_model->set_news();
			$data['header'] = 'templates/header';
			$data['content'] = 'users/create';
			$this->load->view('templates/main', $data);
		}
	}

	public function change()
	{
		$this->users_model->change();
		redirect('users');
	}

	public function changepassword()
	{
		$this->form_validation->set_rules('upass', 'password', 'required|matches[repass]|min_length[4]');
		$this->form_validation->set_rules('repass', 'password confirmation', 'required');

		if ($this->form_validation->run() === false) {
			$data = $this->getSiteData();

			$data['header'] = 'templates/header';
			$data['content'] = 'users/error';
			$this->load->view('templates/main', $data);
		} else {
			$this->users_model->changePassword();
			redirect('users');
		}
	}
}
