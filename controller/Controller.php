<?php

namespace session\controller;
use session\model\Account,
	session\model\Login,
	session\model\Session,
	session\model\SignUp,
	session\model\View,
	Exception;

/**
 * Controller class.
 */
class Controller
{
	private $template;

	/**
	 * Constructor.
	 *
	 * @param string $template   Name of the template.
	 */
	public function __construct($template) {
		$this->template = $template;
	}

	/**
	 * This is the default function that will be called by index.php.
	 *
	 * @param array $formData   The POST variables posted to index.php.
	 * @return bool
	 */
	public function main(array $formData) {
		$notice = '';
		$message = '';
		// Check if there's a session id in cookie.
		if (!empty($_COOKIE['mySessionId'])) {
			// Get the session id from cookie.
			$sessionId = $_COOKIE['mySessionId'];
			try {
				$session = new Session($sessionId);
				// Get the username from the session data.
				$username = $session->get('username');
				if (!empty($username)) {
					if (!empty($formData['logout'])) {
						// Destroy the session.
						$session->destroy();
						// Clear session id cookie.
						setcookie('mySessionId', '', time() - 3600);
						// Set a notice about the logout.
						$notice = $username . ' has successfully logged out.';
						$this->template = 'login';
					}
					// Change password page.
					elseif ($formData['page'] == 'password') {
						// Refresh the session.
						$session->refresh();
						// Set the session id cookie for 30 minutes.
						setcookie('mySessionId', $sessionId, time() + 1800);
						$formData['currentPassword'] = isset($formData['currentPassword'])
													 ? trim(htmlspecialchars($formData['currentPassword'], ENT_QUOTES, 'UTF-8'))
													 : '';
						$formData['newPassword'] = isset($formData['newPassword'])
												 ? trim(htmlspecialchars($formData['newPassword'], ENT_QUOTES, 'UTF-8'))
												 : '';
						if (!empty($formData['changePassword'])) {
							if (empty($formData['currentPassword'])) {
								$message = 'Please type in your current password.';
							}
							elseif (empty($formData['newPassword'])) {
								$message = 'Please set a new password.';
							}
							elseif ($formData['currentPassword'] == $formData['newPassword']) {
								$message = 'Current and new passwords are the same.';
							}
							else {
								$account = new Account($username);
								$result = $account->changePassword($formData['currentPassword'], $formData['newPassword']);
								$message = $result['success']
										 ? 'Password changed successfully!'
										 : 'Password change failed! Reason: ' . $result['message'];
							}
						}
						// Create new view.
						$view = new View($this->template);
						// Assign data to the view.
						$view->assign('currentPassword', $formData['currentPassword']);
						$view->assign('newPassword', $formData['newPassword']);
						$view->assign('message', $message);
						return true;
					}
					// Account page.
					else {
						$this->template = 'account';
						// Create new view.
						$view = new View($this->template);
						// Assign data to the view.
						$view->assign('notice', $username . ' is logged in.');
						return true;
					}
				}
				else {
					// No session, delete session id cookie.
					setcookie('mySessionId', '', time() - 3600);
				}
			}
			catch (Exception $e) {
				$view->assign('message', $e->getMessage());
				return false;
			}
		}
		// Without a session id the user cannot be on the account page.
		elseif (in_array($this->template, array('account', 'password'))) {
			$this->template = 'login';
		}
		$formData['username'] = isset($formData['username'])
		                      ? trim(htmlspecialchars($formData['username'], ENT_QUOTES, 'UTF-8'))
		                      : '';
		$formData['password'] = isset($formData['password'])
		                      ? trim(htmlspecialchars($formData['password'], ENT_QUOTES, 'UTF-8'))
		                      : '';
		// Sign up page.
		if ($this->template == 'signup') {
			$formData['email'] = isset($formData['email'])
			                   ? trim(htmlspecialchars($formData['email'], ENT_QUOTES, 'UTF-8'))
			                   : '';
			if (!empty($formData['username']) && !empty($formData['password']) && !empty($formData['email'])) {
				$signUp = new SignUp();
				$result = $signUp->doSignUp($formData['username'], $formData['password'], $formData['email']);
				$message = $result['success']
						 ? 'Sign up successful!'
						 : 'Sign up failed! Reason: ' . $result['message'];
			}
		}
		// Login page.
		else {
			if (!empty($formData['username']) && !empty($formData['password'])) {
				$login = new Login();
				$success = $login->doLogin($formData['username'], $formData['password']);
				if ($success) {
					// Valid username and password.
					try {
						$session = new Session();
					}
					catch (Exception $e) {
						$message = 'Login failed! Reason: ' . $e->getMessage();
						return false;
					}
					// Let's store some data in the session.
					$data = array(
						'username'    => $formData['username'],
						'ip'          => $_SERVER['REMOTE_ADDR'],
						'browserLang' => substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)
					);
					$sessionId = $session->create($data);
					// Set the session id cookie for 30 minutes.
					setcookie('mySessionId', $sessionId, time() + 1800);
					// Login successful, display account page.
					$notice = 'Login successful! ' . $data['username'] . ' is logged in.';
					$this->template = 'account';
				}
				else {
					$message = 'Login failed! Reason: Invalid username or password.';
				}
			}
		}
		// Create new view.
		$view = new View($this->template);
		// Assign data to the view.
		switch ($this->template) {
			case 'signup':
				$view->assign('username', $formData['username']);
				$view->assign('password', $formData['password']);
				$view->assign('email', $formData['email']);
				$view->assign('message', $message);
				break;
			case 'login':
				$view->assign('username', $formData['username']);
				$view->assign('password', $formData['password']);
				$view->assign('message', $message);
				// Intentional missing break.
			case 'account':
				$view->assign('notice', $notice);
				break;
		}
		$view->assign('message', $message);
		return true;
	}
}
