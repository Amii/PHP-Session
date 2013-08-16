<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Change password page</title>
</head>
<body>
	<h3>Change password page</h3>
	<form id="password" action="index.php" method="post">
		<input type="hidden" name="page" value="password" />
		Current password: <input type="password" name="currentPassword" value="<?=$data['currentPassword'];?>" /><br />
		New password: <input type="password" name="newPassword" value="<?=$data['newPassword'];?>" /> (Minimum 8 characters, min. 1 digit, 1 lowercase, 1 uppercase letter)<br /><br />
		<input type="submit" name="changePassword" value="Submit" />
	</form>
	<p><?=$data['message'];?></p><br />
	<form id="account" action="index.php" method="post">
		<input type="hidden" name="page" value="account" />
		<input type="submit" name="submit" value="Back to account page" />
	</form>
</body>
</html>