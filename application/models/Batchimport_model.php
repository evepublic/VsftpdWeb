<?php

class Batchimport_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('users_model');
		$this->load->model('settings_model');
	}

	public function importCSVFile($filename)
	{
		// determine delimiter
		if (($delimiter = $this->determineDelimiter($filename)) === false) {
			return ['error' => 'Invalid CSV file'];
		}

		$users = [];
		$users_unique_check = [];
		$handle = fopen($filename, 'r');
		while (($row = fgetcsv($handle, 256, $delimiter)) !== false) {
			if (count($row) !== 3) { // check field count
				fclose($handle);
				return ['error' => 'Error at row ' . count($users) . ': exactly 3 fields expected instead of ' . count($row) . '.'];
			}
			$users[] = ['username' => $row[0], 'password' => $row[1], 'storage_directory' => $row[2]];

			if (isset($users_unique_check[$row[0]])) {
				return ['error' => 'Error at row ' . count($users) . ': duplicate user "' . htmlentities($row[0]) . '"'];
			} else {
				$users_unique_check[$row[0]] = true;
			}
		}
		fclose($handle);

		// remove headers if specified
		if ($users[0] === ['username' => 'username', 'password' => 'password', 'storage_directory' => 'storagedirectory']) {
			unset($users[0]);
		}

		// validate users
		$error = false;
		foreach ($users as $key => $user) {
			if (($validation_result = $this->users_model->validateUsername($user['username'])) !== true) {
				$error = $validation_result['error'];
				break;
			}

			if (($validation_result = $this->users_model->validatePassword($user['password'])) !== true) {
				$error = $validation_result['error'];
				break;
			}

			if (($validation_result = $this->users_model->validateStorageDirectory($user['storage_directory'])) !== true) {
				$error = $validation_result['error'];
				break;
			}
		}

		if ($error !== false) {
			return ['error' => 'Error at row ' . $key . ': ' . $error];
		}

		// import users
		$default_permissions = $this->settings_model->get('default_permissions');
		foreach ($users as $key => $user) {
			$create_user_result = $this->users_model->createUser($user['username'], $user['password'], $this->users_model->sanitizeStorageDirectory($user['storage_directory']), $default_permissions);
			if (isset($create_user_result['error'])) {
				return ['error' =>
					'<p>' . $create_user_result['error'] . '</p>' .
					"<p>$key users created so far.</p>"
				];
			}
		}

		return ['success' => count($users) . ' users imported successfully.'];
	}

	private function determineDelimiter($file)
	{
		$file_contents = file_get_contents($file);
		$count[','] = substr_count($file_contents, ',');
		$count[';'] = substr_count($file_contents, ';');
		$delimiter = ($count[','] > $count[';']) ? ',' : ';';
		return ($count[$delimiter] === 0) ? false : $delimiter;
	}
}
