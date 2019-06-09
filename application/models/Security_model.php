<?php

class Security_model extends CI_Model
{
	private $table = 'security';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * @see https://github.com/NigelCunningham/pam-MySQL
	 */
	public function getPasswordHash($password)
	{
		$encryption_algorithm = $this->getEncryptionAlgorithm();
		$result = null;
		switch ($encryption_algorithm->code) {
			case 'PLAIN':
				$result = $password;
				break;

			case 'CRYPT':
				$result = $this->getCryptHash($password, $encryption_algorithm);
				break;

			case 'MYSQL':
				$sql = 'SELECT PASSWORD(' . $this->db->escape($password) . ') AS mysql_password_hash;';
				$query = $this->db->query($sql);
				$result = $query->row()->mysql_password_hash;
				break;

			case 'MD5':
				$result = md5($password);
				break;

			case 'SHA1':
				$result = sha1($password);
				break;

			case 'DRUPAL7':
				$result = $this->getDrupal7PasswordHash($password);
				break;

			case 'JOOMLA15':
				$salt = self::generateSalt(32);
				$result = md5($password . $salt) . ':' . $salt;
				break;

			default:
				throw new Exception('Unknown security algorithm: ' . (string)$encryption_algorithm->code);
		}

		return $result;
	}

	public function getEncryptionAlgorithm()
	{
		return $this->db->get_where($this->table, ['active' => 1])->row();
	}

	/*
	* @see http://php.net/manual/en/function.crypt.php
	*/
	private function getCryptHash($password, $encryption_algorithm)
	{
		$result = null;
		switch ($encryption_algorithm->subcode) {
			case 'STD_DES':
				if (!defined('CRYPT_STD_DES')) throw new Exception('CRYPT_STD_DES is not defined');
				$salt_param = self::generateSalt(2);
				$result = crypt($password, $salt_param);
				break;

			case 'EXT_DES': // does not work on Ubuntu 18.04
				if (!defined('CRYPT_EXT_DES')) throw new Exception('CRYPT_EXT_DES is not defined');
				$id = '_';
				$iteration_str = $this->getExtDESIterationString($encryption_algorithm->param); // /... is 1 time, zzzz = 16777215 times
				$salt = self::generateSalt(4);
				$salt_param = $id . $iteration_str . $salt;
				$result = crypt($password, $salt_param);
				break;

			case 'MD5':
				if (!defined('CRYPT_MD5')) throw new Exception('CRYPT_MD5 is not defined');
				$id = '$1';
				$salt = '$' . self::generateSalt(8) . '$';
				$salt_param = $id . $salt;
				$result = crypt($password, $salt_param);
				break;

			case 'BLOWFISH': // does not work on Ubuntu 18.04
				if (!defined('CRYPT_BLOWFISH')) throw new Exception('CRYPT_BLOWFISH is not defined');
				// $2y - algo
				// $xx - cost (range 04-31)
				// $<22 characters salt> - possible last digit: O e u .
				$result = password_hash($password, PASSWORD_BCRYPT, ['cost' => $encryption_algorithm->param]);
				break;

			case 'SHA256':
				if (!defined('CRYPT_SHA256')) throw new Exception('CRYPT_SHA256 is not defined');
				$id = '$5';
				$rounds_str = isset($encryption_algorithm->param) ? '$rounds=' . $encryption_algorithm->param : '';
				$salt = '$' . self::generateSalt(16);
				$salt_param = $id . $rounds_str . $salt;
				$result = crypt($password, $salt_param);
				break;

			case 'SHA512':
				if (!defined('CRYPT_SHA512')) throw new Exception('CRYPT_SHA512 is not defined');
				$id = '$6';
				$rounds_str = isset($encryption_algorithm->param) ? '$rounds=' . $encryption_algorithm->param : '';
				$salt = '$' . self::generateSalt(16);
				$salt_param = $id . $rounds_str . $salt;
				$result = crypt($password, $salt_param);
				break;

			case 'FALLBACK':
				$result = crypt($password, self::generateSalt(CRYPT_SALT_LENGTH));
				break;

			default:
				throw new Exception('Unknown crypt security algorithm: ' . $encryption_algorithm->subcode);
		}

		return $result;
	}

	public static function generateSalt($length = 8)
	{
		$salt_tokens = self::getSaltTokens();
		$base = strlen($salt_tokens);
		$salt = '';

		/*
		 * Start with a cryptographic strength random string, then convert it to a string with the numeric base of the salt.
		 * Shift the base conversion on each character so the character distribution is even, and randomize the start shift
		 * so it's not predictable.
		 */
		$random_bytes = openssl_random_pseudo_bytes($length + 1);
		$shift = ord($random_bytes[0]);
		for ($i = 1; $i <= $length; ++$i) {
			$salt .= $salt_tokens[($shift + ord($random_bytes[$i])) % $base];
			$shift += ord($random_bytes[$i]);
		}

		return $salt;
	}

	private static function getSaltTokens()
	{
		$salt_tokens = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; // 64 possible characters
		return $salt_tokens;
	}

	private function getExtDESIterationString($count)
	{
		$count = max(min($count, (1 << 24) - 1), 1); // count must be in range 1...16777215
		$salt_tokens = self::getSaltTokens();
		$iteration_string = '';
		for ($i = 0; $i <= 3; ++$i) {
			$shift = $i * 6;
			$iteration_string[$i] = $salt_tokens[($count & (63 << $shift)) >> $shift];
		}
		return $iteration_string;
	}

	private function getDrupal7PasswordHash($password)
	{
		require_once APPPATH . 'third_party/drupal-7/bootstrap.inc';
		require_once APPPATH . 'third_party/drupal-7/password.inc';

		$result = user_hash_password($password);
		return $result;
	}
}
