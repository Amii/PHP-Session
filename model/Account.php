<?php

namespace session\model;
use session\model\User;

/**
 * Account class.
 */
class Account
{
	// The user's username.
	private $username;

	/**
	 * Constructor.
	 *
	 * @param string $username   The user's username.
	 */
	public function __construct($username) {
		$this->username = $username;
	}

	/**
	 * Changes the user's password.
	 *
	 * @param string $currentPassword   The user's current password.
	 * @param string $newPassword       The new password.
	 * @return array   Password change result and error message.
	 */
	public function changePassword($currentPassword, $newPassword) {
		$user = new User($this->username);
		// Get the user's encoded password and salt from the database.
		if (($data = $user->getEncodedPasswordAndSalt()) == false) {
			return array('success' => false, 'message' => 'Database error.');
		}
		// Encoded passwords do not match.
		if ($user->encodePassword($currentPassword, $data['salt']) != $data['password']) {
			return array('success' => false, 'message' => 'Wrong current password.');
		}
		// Invalid new password.
		if (!$user->validatePassword($newPassword)) {
			return array('success' => false, 'message' => 'Invalid new password.');
		}
		return $user->changePassword($newPassword)
			? array('success' => true, 'message' => '')
			: array('success' => false, 'message' => 'Database error.');
	}
}
