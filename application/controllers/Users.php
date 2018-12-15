<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'Abstract_vstpdweb.php';

class Users extends Abstract_Vstpdweb
{
	protected $title = 'FTP User Management';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('users_model');
		$this->load->model('settings_model');
		$this->load->model('vsftpd_model');
		$this->load->library('form_validation');
		$this->load->helper('submit');
	}

	/**
	 * shows the index page
	 */
	public function index()
	{
		$data = $this->getSiteData();

		$data['users'] = $this->users_model->getAll();
		$data['default_permissions'] = $this->settings_model->get('default_permissions');

		$data['storage_dir'] = $this->vsftpd_model->getStorageDirUser('');

		$data['content'] = 'users/index';
		$this->load->view('templates/main', $data);
	}

	public function create()
	{
		$this->form_validation->set_rules('username', 'username', 'required|callback_validate_username');
		$this->form_validation->set_rules('password', 'password', 'required|min_length[4]');
		$this->form_validation->set_rules('confirmpassword', 'confirmation password', 'required|matches[password]');
		$this->form_validation->set_rules('permissions', 'permissions', 'required|callback_validate_permissions');

		$error = false;
		if ($this->form_validation->run() === false) {
			$error = true;
			$this->session->set_flashdata('users_create_user', ['error' => validation_errors()]);
		}

		if (!$error) {
			$create_user_result = $this->users_model->createUser($this->input->post('username'), $this->input->post('password'), $this->input->post('permissions'));
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

	public function validate_username($username)
	{
		if (($validation_result = $this->users_model->validateUsername($username)) === true) {
			return true;
		} else {
			$this->form_validation->set_message(__FUNCTION__, $validation_result['error']);
			return false;
		}
	}

	public function importcsv()
	{
		$this->load->model('batchimport_model');

		$error = false;
		if (!(isset($_FILES['import_file']['tmp_name']) and strlen($_FILES['import_file']['tmp_name']))) {
			$error = true;
			$this->session->set_flashdata('users_batchimport', ['error' => 'Please select a file.']);
		}

		if (!$error) {
			$batchimport_result = $this->batchimport_model->importCSVFile($_FILES['import_file']['tmp_name']);
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

	/**
	 * shows the edit page
	 *
	 * @param int $user_id the id of the user
	 */
	public function edit($user_id = null)
	{
		if ($user_id === null or (($user_item = $this->users_model->get($user_id)) === null)) {
			redirect('users');
		}

		$data = $this->getSiteData();

		$data['title'] .= ' - edit user ' . htmlentities($user_item['username']);

		$data['user_item'] = $user_item;

		$this->load->model('vsftpd_model');
		$data['storage_dir_user'] = $this->vsftpd_model->getStorageDirUser($user_item['username']);

		$data['content'] = 'users/edit';
		$this->load->view('templates/main', $data);
	}

	public function updatepassword()
	{
		$this->form_validation->set_rules('password', 'password', 'required|min_length[4]');
		$this->form_validation->set_rules('confirmpassword', 'confirm password', 'required|matches[password]');

		if ($this->form_validation->run() === false) {
			$this->session->set_flashdata('users_update_password', ['error' => validation_errors()]);
		} else {
			$this->session->set_flashdata('users_update_password', ['success' => 'Password updated.']);
			$this->users_model->changePassword($this->input->post('user_id'), $this->input->post('password'));
		}

		$this->session->set_flashdata('form_submit_scroll_id', 'users_update_password');
		redirect('users/edit/' . $this->input->post('user_id'));
	}

	public function updatepermissions()
	{
		$this->form_validation->set_rules('permissions', 'permissions', 'required');

		if ($this->form_validation->run() === false) {
		} else {
			$this->users_model->updatePermissions($this->input->post('user_id'), $this->input->post('permissions'));
			$this->session->set_flashdata('users_update_permissions', ['success' => 'Permissions updated.']);
		}

		$this->session->set_flashdata('form_submit_scroll_id', 'users_update_permissions');
		redirect('users/edit/' . $this->input->post('user_id'));
	}

	public function delete()
	{
		$this->form_validation->set_rules('username', 'username', 'required');
		$this->form_validation->set_rules('inputusername', 'username', 'required|matches[username]');

		$this->session->set_flashdata('form_submit_scroll_id', 'users_delete_user');
		if ($this->form_validation->run() === false) {
			$this->session->set_flashdata('users_delete_user', ['error' => validation_errors()]);
			redirect('users/edit/' . $this->input->post('user_id'));
		} else {
			$this->users_model->deleteUser($this->input->post('user_id'));
			$this->session->set_flashdata('users_delete_user', ['success' => 'User "' . $this->input->post('username') . '" deleted.']);
			redirect('users');
		}
	}
}
