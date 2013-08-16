<?php

namespace session\model;
use session\model\Database;

/**
 * User class.
 */
class User
{
	// The user's username.
	private $username;

	// The database connection.
	private $db;

	/**
	 * Constructor.
	 *
	 * @param string $username   The user's username.
	 */
	public function __construct($username) {
		$this->username = $username;
		$this->db = new Database();
	}

	/**
	 * Checks if the username is free or is it taken.
	 *
	 * @return bool   True if username is free, false if taken.
	 */
	public function checkFreeUsername() {
		// Select the user's id.
		$query = 'SELECT `id` FROM `user` WHERE `username` = :username';
		$params = array(
			'username' => $this->username
		);
		return $this->db->query($query, $params, 'column') == false;
	}

	/**
	 * Creates a new user account.
	 *
	 * @param string $password   The user's password.
	 * @param string $email      The user's email address.
	 * @return bool   True if user creation is successful, false if not.
	 */
	public function createNewUser($password, $email) {
		// Generate a random salt for password encoding.
		$salt = $this->generateSalt();
		// Insert the new user into the database.
		$query = '
			INSERT INTO `user`(
				`username`,
				`password`,
				`salt`,
				`email`,
				`created`
			)
			VALUES (
				:username,
				:password,
				:salt,
				:email,
				:created
			)
		';
		$params = array(
			'username' => $this->username,
			'password' => $this->encodePassword($password, $salt),
			'salt'     => $salt,
			'email'    => $email,
			'created'  => time()
		);
		return $this->db->query($query, $params);
	}

	/**
	 * Returns the user's encoded password and salt.
	 *
	 * @return array   The user's encoded password and salt.
	 */
	public function getEncodedPasswordAndSalt() {
		// Select the user's encoded password and salt.
		$query = 'SELECT `password`, `salt` FROM `user` WHERE `username` = :username';
		$params = array(
			'username' => $this->username
		);
		return $this->db->query($query, $params, 'array');
	}

	/**
	 * Returns the user's salt.
	 *
	 * @return array   The user's salt.
	 */
	public function getSalt() {
		// Select the user's salt.
		$query = 'SELECT `salt` FROM `user` WHERE `username` = :username';
		$params = array(
				'username' => $this->username
		);
		return $this->db->query($query, $params, 'column');
	}

	/**
	 * Validates the username.
	 *
	 * @return bool   True if username is valid, false if not.
	 */
	public function validateUsername() {
		return preg_match('/^[a-z0-9]{4,16}$/i', $this->username) == 1;
	}

	/**
	 * Validates the password.
	 *
	 * @param string $password   The password.
	 * @return bool   True if password is valid, false if not.
	 */
	public function validatePassword($password) {
		// A good password should be at least 8 characters long, should contain
		// at least one digit, one lowercase and one uppercase letter.
		return preg_match('/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/', $password) == 1;
	}

	/**
	 * Validates the email address.
	 *
	 * @param string $email   The email address.
	 * @return bool   True if email address is valid, false if not.
	 */
	public function validateEmail($email) {
		return preg_match('/^[a-z0-9]+[a-z0-9\.\-_]*@([a-z0-9]+\.){1,}([a-z]{2,6})$/i', $email) == 1;
	}

	/**
	 * Encodes the password using the given salt.
	 *
	 * @param string $password   The password.
	 * @param string $salt       The salt.
	 * @return string   The encoded password.
	 */
	public function encodePassword($password, $salt) {
		return sha1($this->username . sha1($password . $salt));
	}

	/**
	 * Changes the user's password.
	 *
	 * @param string $password   The user's new password.
	 * @return bool   True if password change is successful, false if not.
	 */
	public function changePassword($password) {
		$salt = $this->getSalt();
		// Update the user's password in the database.
		$query = '
			UPDATE `user`
			SET
				`password` = :password
			WHERE
				`username` = :username
		';
		$params = array(
				'username' => $this->username,
				'password' => $this->encodePassword($password, $salt)
		);
		return $this->db->query($query, $params);
	}

	/**
	 * Generates a random salt for password encoding.
	 *
	 * @return string   The generated salt.
	 */
	protected function generateSalt() {
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$salt = '';
		for ($i = 0; $i < SALT_LENGTH; $i++) {
			$salt .= $chars[mt_rand(0, strlen($chars) - 1)];
		}
		return $salt;
	}
}
