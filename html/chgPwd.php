<?php
include_once('lib/csrf.php');
include_once('lib/auth.php');
session_start();
if (!auth()){
	//header('Refresh:3; login.php');
	//echo 'You are not logined <br>Redirecting you to login page in 3 second...';
	header('Location: login.php');
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Change Password Page</title>
</head>
<body>
<fieldset>
	<form id="loginForm" method="POST" action="chgPwd-process.php?action=<?php echo ($action = 'changePassword'); ?>">
		
		<label for="pw">Original Password:</label>
		<div><input type="password" name="oldpw" required="true" pattern="^[\w@#$%\^\&\*\-]+$" /></div>
		<label for="pw">New Password:</label>
		<div><input type="password" name="newpw1" required="true" pattern="^[\w@#$%\^\&\*\-]+$" /></div>
		<label for="pw">Confirm New Password:</label>
		<div><input type="password" name="newpw2" required="true" pattern="^[\w@#$%\^\&\*\-]+$" /></div>
		
		<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/>
		<input type="hidden" name="acc" value="<?php $email=$_SESSION['authtoken']; $email=$email['em']; echo $email; ?>"/>
		<input type="submit" value="Confirm" />
	</form>
</fieldset>
</body>
</html>