<?php

namespace session\model;
use session\model\User;

/**
 * Login class.
 */
class Login
{
	/**
	 * Constructor.
	 */
	public function __construct() {

	}

	/**
	 * Authenticates the user's username and password.
	 *
	 * @param string $username   The username.
	 * @param string $password   The password.
	 * @return bool   True if login is successful, false if not.
	 */
	public function doLogin($username, $password) {
		$user = new User($username);
		// Invalid username.
		if (!$user->validateUsername()) {
			return false;
		}
		// No such user in the database.
		if (($data = $user->getEncodedPasswordAndSalt()) == false) {
			return false;
		}
		// Encoded passwords do not match.
		if ($user->encodePassword($password, $data['salt']) != $data['password']) {
			return false;
		}
		return true;
	}
}
