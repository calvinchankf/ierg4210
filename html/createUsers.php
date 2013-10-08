<?php

include_once('lib/auth.php');
$db1=newDB();
$email='chan9118@hotmail.com'; // 1st email: ckf@dubcork.com, 2nd email: abc@dubcork.com, 3rd email: my email
 $salt=mt_rand();
$saltedPassword=sha1($salt.'abcdef'); //pwd: ckfdubcork,abcdubcork, abcdef
$q=$db1->prepare("INSERT INTO account (email, salt, password)VALUES (?,?,?)");
		if ($q->execute(array($email, $salt, $saltedPassword)))
			echo 'created already !';
		else echo 'created fail';
?>
<html>
<head>
	<meta charset="utf-8" />
	<title>Created?</title>
</head>
<body>:)</body>
</html>