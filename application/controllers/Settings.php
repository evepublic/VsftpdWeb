<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MY_Controller
{
	protected $title = 'Settings';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('settings_model');
		$this->load->library('form_validation');
		$this->load->helper('submit');
	}

	public function index()
	{
		$data = $this->getSiteData();
		$data += $this->settings_model->getAll();

		$this->load->model('vsftpd_model');
		$data['user_config_dir'] = $this->vsftpd_model->get('user_config_dir');
		$data['xferlog_file'] = $this->vsftpd_model->get('xferlog_file');

		$this->load->model('security_model');
		$data['password_encryption_algorithm'] = $this->security_model->getEncryptionAlgorithm()->name;

		$data['content'] = 'settings/index';
		$this->load->view('templates/main', $data);
	}

	public function update()
	{
		$this->form_validation->set_rules('site_name', 'site name', 'max_length[64]');
		$this->form_validation->set_rules('default_permissions', 'default new user permissions', 'required|callback_validate_permissions');
		$this->form_validation->set_rules('vsftpd_config_path', 'vsftpd configuration file', 'required|callback_validate_vsftpdconf');

		if ($this->form_validation->run() === false) {
			$this->session->set_flashdata('settings_update', ['error' => validation_errors()]);
		} else {
			$this->load->helper('security');
			$data = [
				'site_name' => xss_clean($this->input->post('site_name')),
				'default_permissions' => $this->input->post('default_permissions'),
				'vsftpd_config_path' => $this->input->post('vsftpd_config_path'),
			];
			$this->settings_model->update($data);
			$this->session->set_flashdata('settings_update', ['success' => 'Settings updated successfully.']);
		}

		$this->session->set_flashdata('form_submit_scroll_id', 'settings_update');
		redirect('settings');
	}

	public function validate_vsftpdconf($vsftpd_conf_path)
	{
		// validate allowed vsftpd.conf path
		if (!in_array($vsftpd_conf_path, ['/etc/vsftpd.conf', '/etc/vsftpd/vsftpd.conf',])) {
			$this->form_validation->set_message(__FUNCTION__, 'The {field} field has an invalid value: ' . htmlentities($vsftpd_conf_path));
			return false;
		}

		// validate if vsftpd conf exists
		if (!file_exists($vsftpd_conf_path)) {
			$this->form_validation->set_message(__FUNCTION__, 'The {field} "' . htmlentities($vsftpd_conf_path) . '" does not exists.');
			return false;
		}

		return true;
	}

	public function changepassword()
	{
		$this->form_validation->set_rules('currentpassword', 'current password', 'required');
		$this->form_validation->set_rules('newpassword', 'new password', 'required|min_length[4]|max_length[32]');
		$this->form_validation->set_rules('confirmnewpassword', 'confirm new password', 'required|matches[newpassword]');

		$validation_error = ($this->form_validation->run() === false) ? true : false;

		$change_password_error = '';
		if (!$validation_error) {
			$this->load->model('login_model');
			$change_password_result = $this->login_model->changePassword(
				$_SESSION['username'],
				$this->input->post('currentpassword'),
				$this->input->post('newpassword'));

			if ($change_password_result !== true) {
				$change_password_error = '<p>' . $change_password_result['error'] . '</p>';
				$validation_error = true;
			}
		}

		if ($validation_error) {
			$this->session->set_flashdata('settings_change_password', ['error' => validation_errors() . $change_password_error]);
		} else {
			$this->session->set_flashdata('settings_change_password', ['success' => 'Password changed successfully.']);
		}
		$this->session->set_flashdata('form_submit_scroll_id', 'settings_change_password');
		redirect('settings');
	}
}
