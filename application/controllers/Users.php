<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_Controller
{
	protected $title = 'FTP User Management';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('users_model');
		$this->load->model('settings_model');
		$this->load->library('form_validation');
		$this->load->helper('submit');
	}

	public function index()
	{
		$data = $this->getSiteData();

		$data['users'] = $this->users_model->getAll();
		$data['user_base_path'] = $this->settings_model->get('user_base_path');
		$data['default_permissions'] = $this->settings_model->get('default_permissions');

		$data['content'] = 'users/index';
		$this->load->view('templates/main', $data);
	}

	public function create()
	{
		$this->form_validation->set_rules('username', 'username', 'callback_validate_username');
		$this->form_validation->set_rules('password', 'password', 'callback_validate_password');
		$this->form_validation->set_rules('confirmpassword', 'confirmation password', 'required|matches[password]');
		$this->form_validation->set_rules('storage_directory', 'storage directory', 'callback_validate_storage_directory');
		$this->form_validation->set_rules('permissions', 'permissions', 'required|callback_validate_permissions');

		$error = false;
		if ($this->form_validation->run() === false) {
			$error = true;
			$this->session->set_flashdata('users_create_user', ['error' => validation_errors()]);
		}

		if (!$error) {
			$create_user_result = $this->users_model->createUser($this->input->post('username'), $this->input->post('password'), $this->input->post('storage_directory'), $this->input->post('permissions'));
			if (isset($create_user_result['error'])) {
				$error = true;
				$this->session->set_flashdata('users_create_user', ['error' => $create_user_result['error']]);
			}
		}

		if (!$error) {
			$this->session->set_flashdata('users_create_user', ['success' => 'FTP user "' . $this->input->post('username') . '" created successfully.']);
		}

		$this->session->set_flashdata('form_submit_scroll_id', 'users_create_user');
		redirect('users');
	}


	public function edit($user_id = null)
	{
		if ($user_id === null or (($user = $this->users_model->get($user_id)) === null)) {
			redirect('users');
		}

		$data = $this->getSiteData();

		$data['title'] .= ' - edit user ' . htmlentities($user->username);
		$data['user_base_path'] = $this->settings_model->get('user_base_path');
		$data['user'] = $user;

		$data['content'] = 'users/edit';
		$this->load->view('templates/main', $data);
	}

	public function updatepassword()
	{
		$this->form_validation->set_rules('password', 'password', 'callback_validate_password');
		$this->form_validation->set_rules('confirmpassword', 'confirm password', 'required|matches[password]');

		if ($this->form_validation->run() === false) {
			$this->session->set_flashdata('users_update_password', ['error' => validation_errors()]);
		} else {
			$this->users_model->changePassword($this->input->post('user_id'), $this->input->post('password'));
			$this->session->set_flashdata('users_update_password', ['success' => 'Password updated successfully.']);
		}

		$this->session->set_flashdata('form_submit_scroll_id', 'users_update_password');
		redirect('users/edit/' . $this->input->post('user_id'));
	}

	public function updatepermissions()
	{
		$this->form_validation->set_rules('permissions', 'permissions', 'required|callback_validate_permissions');

		if ($this->form_validation->run() === false) {
			$this->session->set_flashdata('users_update_permissions', ['error' => validation_errors()]);
		} else {
			$update_permissions_result = $this->users_model->updatePermissions($this->input->post('user_id'), $this->input->post('permissions'));
			if (isset($update_permissions_result['error'])) {
				$this->session->set_flashdata('users_update_permissions', ['error' => $update_permissions_result['error']]);
			} else {
				$this->session->set_flashdata('users_update_permissions', ['success' => 'Permissions updated successfully.']);
			}
		}

		$this->session->set_flashdata('form_submit_scroll_id', 'users_update_permissions');
		redirect('users/edit/' . $this->input->post('user_id'));
	}

	public function updatestoragedirectory()
	{
		$this->form_validation->set_rules('storage_directory', 'storage directory', 'callback_validate_storage_directory');

		if ($this->form_validation->run() === false) {
			$this->session->set_flashdata('users_update_directory', ['error' => validation_errors()]);
		} else {
			$update_storage_directory_result = $this->users_model->updateStorageDirectory($this->input->post('user_id'), $this->input->post('storage_directory'));
			if (isset($update_storage_directory_result['error'])) {
				$this->session->set_flashdata('users_update_directory', ['error' => $update_storage_directory_result['error']]);
			} else {
				$this->session->set_flashdata('users_update_directory', ['success' => 'Storage directory updated successfully.']);
			}
		}

		$this->session->set_flashdata('form_submit_scroll_id', 'users_update_directory');
		redirect('users/edit/' . $this->input->post('user_id'));
	}

	public function delete()
	{
		$this->form_validation->set_rules('username', 'username', 'required');
		$this->form_validation->set_rules('inputusername', 'input username', 'required|matches[username]');

		$this->session->set_flashdata('form_submit_scroll_id', 'users_delete_user');
		if ($this->form_validation->run() === false) {
			$this->session->set_flashdata('users_delete_user', ['error' => validation_errors()]);
			redirect('users/edit/' . $this->input->post('user_id'));
		} else {
			$this->users_model->deleteUser($this->input->post('user_id'));
			$this->session->set_flashdata('users_delete_user', ['success' => 'User "' . $this->input->post('username') . '" deleted successfully.']);
			redirect('users');
		}
	}

	public function importcsv()
	{
		$this->load->model('batchimport_model');

		$error = false;
		if (!(isset($_FILES['users_csv_file']['tmp_name']) and strlen($_FILES['users_csv_file']['tmp_name']))) {
			$error = true;
			$this->session->set_flashdata('users_batchimport', ['error' => 'Please select a file.']);
		}

		if (!$error) {
			$batchimport_result = $this->batchimport_model->importCSVFile($_FILES['users_csv_file']['tmp_name']);
			if (isset($batchimport_result['error'])) {
				$error = true;
				$this->session->set_flashdata('users_batchimport', ['error' => $batchimport_result['error']]);
			} else {
				$this->session->set_flashdata('users_batchimport', ['success' => $batchimport_result['success']]);
			}
		}

		$this->session->set_flashdata('form_submit_scroll_id', 'users_batchimport');
		redirect('users');
	}

	public function validate_username($username)
	{
		if (($validation_result = $this->users_model->validateUsername($username)) === true) {
			return true;
		} else {
			$this->form_validation->set_message(__FUNCTION__, $validation_result['error']);
			return false;
		}
	}

	public function validate_password($password)
	{
		if (($validation_result = $this->users_model->validatePassword($password)) === true) {
			return true;
		} else {
			$this->form_validation->set_message(__FUNCTION__, $validation_result['error']);
			return false;
		}
	}

	public function validate_storage_directory($storage_directory)
	{
		if (($validation_result = $this->users_model->validateStorageDirectory($storage_directory)) === true) {
			return true;
		} else {
			$this->form_validation->set_message(__FUNCTION__, $validation_result['error']);
			return false;
		}
	}
}
