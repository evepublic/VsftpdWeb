<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'Abstract_vstpdweb.php';

class Settings extends Abstract_Vstpdweb
{
	protected $title = 'Settings';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('settings_model');
		$this->load->library('form_validation');
		$this->load->helper('submit');
	}

	/**
	 * shows the index page
	 */
	public function index()
	{
		$data = $this->getSiteData();

		$data['site_name'] = $this->settings_model->get('site_name');
		$data['default_permissions'] = $this->settings_model->get('default_permissions');
		$data['vsftpd_config_path'] = $this->settings_model->get('vsftpd_config_path');

		$this->load->model('vsftpd_model');
		$data['local_root'] = $this->vsftpd_model->get('local_root');
		$data['user_config_dir'] = $this->vsftpd_model->get('user_config_dir');
		$data['xferlog_file'] = $this->vsftpd_model->get('xferlog_file');

		$data['content'] = 'settings/index';
		$this->load->view('templates/main', $data);
	}

	public function update()
	{
		$this->form_validation->set_rules('site_name', 'site name', 'max_length[64]');
		$this->form_validation->set_rules('default_permissions', 'default new user permissions', 'required|callback_validate_permissions');
		$this->form_validation->set_rules('vsftpd_config_path', 'vsftpd configuration file path', 'required|callback_validate_vsftpdconf');

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
			$this->session->set_flashdata('settings_update', ['success' => 'Settings updated.']);
		}

		$this->session->set_flashdata('form_submit_scroll_id', 'settings_update');
		redirect('settings');
	}

	public function validate_vsftpdconf($vsftpd_conf_path)
	{
		// validate ending with vsftpd.conf
		if (substr($vsftpd_conf_path, -11) !== 'vsftpd.conf') {
			$this->form_validation->set_message(__FUNCTION__, '{field} must end with vsftpd.conf');
			return false;
		}

		// validate absolute path
		if (strpos($vsftpd_conf_path, '..') !== false or substr($vsftpd_conf_path, 0, 1) !== '/') {
			$this->form_validation->set_message(__FUNCTION__, '{field} can only contain an absolute path.');
			return false;
		}

		// validate characters
		$vsftpd_conf_path_allowed_characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_/.';
		for ($i = 0; $i < strlen($vsftpd_conf_path); $i++) {
			if (strpos($vsftpd_conf_path_allowed_characters, $vsftpd_conf_path[$i]) === false) {
				$this->form_validation->set_message(__FUNCTION__, 'The {field} field must only contain the following characters: a…z A…Z 0…9 _ / .');
				return false;
			}
		}

		// check if vsftpd conf file exists
		if (!file_exists($vsftpd_conf_path)) {
			$this->form_validation->set_message(__FUNCTION__, '{field} "' . $vsftpd_conf_path . '" does not exists.');
			return false;
		}

		return true;
	}

	public function changepassword()
	{
		$this->form_validation->set_rules('currentpassword', 'current password', 'required');
		$this->form_validation->set_rules('newpassword', 'new password', 'required|min_length[4]');
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
		redirect('settings#change_password');
	}
}
