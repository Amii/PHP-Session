<?php

namespace session\model;
use session\model\User;

/**
 * SignUp class.
 */
class SignUp
{
	/**
	 * Constructor.
	 */
	public function __construct() {

	}

	/**
	 * Creates a new user account.
	 *
	 * @param string $username   The username.
	 * @param string $password   The password.
	 * @param string $email      The email address.
	 * @return array   Sign up result and error message.
	 */
	public function doSignUp($username, $password, $email) {
		$user = new User($username);
		// Invalid username.
		if (!$user->validateUsername()) {
			return array('success' => false, 'message' => 'Invalid username.');
		}
		// Username already taken.
		if (!$user->checkFreeUsername()) {
			return array('success' => false, 'message' => 'Username already taken.');
		}
		// Invalid password.
		if (!$user->validatePassword($password)) {
			return array('success' => false, 'message' => 'Invalid password.');
		}
		// Invalid email address.
		if (!$user->validateEmail($email)) {
			return array('success' => false, 'message' => 'Invalid email address.');
		}
		return $user->createNewUser($password, $email)
			? array('success' => true, 'message' => '')
			: array('success' => false, 'message' => 'Database error.');
	}
}
