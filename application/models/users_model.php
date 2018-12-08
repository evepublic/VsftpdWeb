<?php

class Users_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('security_model');
	}

	public function get_users($id = FALSE)
	{
		if ($id === FALSE) {
			$query = $this->db->get('accounts');
			return $query->result_array();
		}

		$query = $this->db->get_where('accounts', array('id' => $id));
		return $query->row_array();
	}

	public function get_path($name)
	{
		$query = $this->db->get_where('settings', array('name' => $name));

		$query = $query->row_array(0);
		$query1 = $query['value'];
		return $query1;
	}

	public function new_user()
	{
		$username = $this->input->post('user');

		if ($this->input->post('dir') == 'def') {
			$query = $this->db->get_where('settings', array('name' => 'disk1'));
			$query = $query->row_array(0);
			$ppath = $query['value'];
			$ppath = $ppath . $username;
			if (!file_exists($ppath)) {
				if (!mkdir($ppath, 0777)) die("Failed to mkdir $ppath, did the dir existed? ");
				if (!chmod($ppath, 0777)) die('Failed to chmod');
			}
		}

		if ($this->input->post('dir') == 'custom') {
			$ppath = $this->input->post('path');

			$mkd = 1;

			$run = 'local_root=' . $ppath;
			exec("echo $run > /etc/vsftpd/vusers/$username");
			exec("sudo chown root /etc/vsftpd/vusers/$username");

			if (!file_exists($ppath)) {
				if (!mkdir($ppath, 0777)) die("Failed to mkdir $ppath, did the dir existed? If it did click <a href='index.php/users/'>here</a>.  ");
				if (!chmod($ppath, 0777)) die('Failed to chmod');
			}
		}

		$run = 'write_enable=YES';
		exec("echo $run >> /etc/vsftpd/vusers/$username");
		exec("sudo chown root /etc/vsftpd/vusers/$username");

		if ($this->input->post('dir') == 'def') {
			$ppath = 'none';
		}

		$account_data = array(
			'username' => $this->input->post('user'),
			'pass' => $this->security_model->getPasswordHash($this->input->post('upass')),
			'perm' => 'w',
			'path' => $ppath,
		);
		$this->db->insert('accounts', $account_data);
	}

	public function delete_user($id)
	{
		$q = "SELECT username FROM accounts WHERE id = $id ;";
		$username = $this->db->query($q)->row();
		$username = $username->username;
		exec("sudo rm /etc/vsftpd/vusers/$username");
		return $this->db->delete('accounts', array('id' => $id));
	}

	public function change()
	{
		$id = $this->input->post('id');
		$username = $this->input->post('username');

		$wr = $this->input->post('write');
		$del = $this->input->post('delete');
		if ($wr == 'yes' && $del == 'yes') $write = 'wd';
		else if ($wr == 'yes' && $del != 'yes') $write = 'w';
		else $write = 'r';

		$q = "UPDATE accounts SET perm = '$write' WHERE id = $id;";
		$this->db->query($q);

		$ppath = $this->input->post('path');
		$dir = $this->input->post('dir');

		if ($dir == 'disk1') $ppath = $this->input->post('disk1') . $ppath;
		if ($dir == 'disk2') $ppath = $this->input->post('disk2') . $ppath;

		if ($ppath == null) {
			$q = "UPDATE accounts SET path = 'none' WHERE id = $id;";
			$mkd = 0;
		} else {
			$q = "UPDATE accounts SET path = '$ppath' WHERE id = $id;";
			$mkd = 1;
		}
		$this->db->query($q);

		// make user file
		exec("sudo rm /etc/vsftpd/vusers/$username");

		$run = '';
		if ($ppath != null) {
			$run = 'local_root=' . $ppath;
			exec("echo $run > /etc/vsftpd/vusers/$username");
		}
		if ($write == 'w') {
			$run = 'write_enable=YES';
			exec("echo $run >> /etc/vsftpd/vusers/$username");
		}
		if ($write == 'wd') {
			$run = 'cmds_denied=DELE,RMD,RNFR,RNTO';
			exec("echo $run >> /etc/vsftpd/vusers/$username");
			$run = 'write_enable=YES';
			exec("echo $run >> /etc/vsftpd/vusers/$username");
		}

		exec("sudo chown root /etc/vsftpd/vusers/$username");

		if ($mkd == 1) {
			if (!file_exists($ppath)) {
				if (!mkdir($ppath, 0777)) die("Failed to mkdir $ppath, did the dir existed? ");
				if (!chmod($ppath, 0777)) die('Failed to chmod');
			}
		} else if ($mkd == 0) {
			$q = "SELECT value FROM settings WHERE name = 'disk1' ;";
			$ppath = $this->db->query($q)->row();
			$ppath = $ppath->value . $username;

			if (!file_exists($ppath)) {
				if (!mkdir($ppath, 0777)) die("Failed to mkdir $ppath, did the dir existed? ");
				if (!chmod($ppath, 0777)) die('Failed to chmod');
			}
		}
	}

	public function changePassword()
	{
		$this->db->where('id', (int)$this->input->post('id'));
		$this->db->update('accounts', ['pass' => $this->security_model->getPasswordHash($this->input->post('upass'))]);
	}
}
