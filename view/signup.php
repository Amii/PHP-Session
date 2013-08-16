<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Sign up page</title>
</head>
<body>
	<h3>Sign up page</h3>
	<form id="signup" action="index.php" method="post">
		<input type="hidden" name="page" value="signup" />
		Username: <input type="text" name="username" value="<?=$data['username'];?>" maxlength="16" /> (4-16 alphanumeric characters)<br />
		Password: <input type="password" name="password" value="<?=$data['password'];?>" /> (Minimum 8 characters, min. 1 digit, 1 lowercase, 1 uppercase letter)<br />
		Email: <input type="text" name="email" value="<?=$data['email'];?>" maxlength="100" /><br /><br />
		<input type="submit" name="submit" value="Submit" />
	</form>
	<p><?=$data['message'];?></p><br />
	<form id="login" action="index.php" method="post">
		<input type="hidden" name="page" value="login" />
		Already registered? <input type="submit" name="submit" value="Login" />
	</form>
</body>
</html>