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
		while (($row = fgetcsv($handle, 128, $delimiter)) !== false) {
			if (count($row) !== 2) { // check field count
				fclose($handle);
				return ['error' => 'Error at row ' . count($users) . ': exactly 2 fields expected instead of ' . count($row) . '.'];
			}
			$users[] = ['username' => $row[0], 'password' => $row[1]];

			if (isset($users_unique_check[$row[0]])) {
				return ['error' => 'Error at row ' . count($users) . ': duplicate user "' . htmlentities($row[0]) . '"'];
			} else {
				$users_unique_check[$row[0]] = true;
			}
		}
		fclose($handle);

		// remove headers if specified
		if ($users[0] === ['username' => 'username', 'password' => 'password'])
			unset($users[0]);

		// validate users
		foreach ($users as $key => $user) {
			$validation_result = $this->users_model->validateUsername($user['username']);
			if ($validation_result !== true) {
				return ['error' => "Error at row $key: " . $validation_result['error']];
			}

			if (strlen($user['password']) < 4) {
				return ['error' => 'Error at row ' . $key . ': password for user "' . htmlentities($user['username']) . '" is too short, minimal 4 characters required.'];
			}
		}

		// import users
		$default_permissions = $this->settings_model->get('default_permissions');
		foreach ($users as $key => $user) {
			$create_user_result = $this->users_model->createUser($user['username'], $user['password'], $default_permissions);
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
