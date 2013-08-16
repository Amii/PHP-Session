<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Account page</title>
</head>
<body>
	<h3>Account page</h3>
	<p><?=$data['notice'];?></p>
	<form id="password" action="index.php" method="post">
		<input type="hidden" name="page" value="password" />
		<input type="submit" name="submit" value="Change password" />
	</form>
	<br />
	<form id="login" action="index.php" method="post">
		<input type="hidden" name="page" value="login" />
		<input type="submit" name="logout" value="Logout" />
	</form>
</body>
</html>