<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Login page</title>
</head>
<body>
	<h3>Login page</h3>
	<p><?=$data['notice'];?></p>
	<form id="login" action="index.php" method="post">
		<input type="hidden" name="page" value="login" />
		Username: <input type="text" name="username" value="<?=$data['username'];?>" maxlength="160" /><br />
		Password: <input type="password" name="password" value="<?=$data['password'];?>" /><br /><br />
		<input type="submit" name="submit" value="Submit" />
	</form>
	<p><?=$data['message'];?></p><br />
	<form id="signup" action="index.php" method="post">
		<input type="hidden" name="page" value="signup" />
		Not registered? <input type="submit" name="submit" value="Sign up" />
	</form>
</body>
</html>