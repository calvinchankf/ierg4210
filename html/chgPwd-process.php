<?php
include_once('lib/csrf.php');
session_start();

function ierg4210_changePassword(){
	//echo $_POST['acc'].' '.$_POST['nonce'].' '.$_POST['oldpw'].' '.$_POST['newpw1'].' '.$_POST['newpw2'];
	
	//validation of all post data
	if (empty($_POST['acc']) || empty($_POST['oldpw']) || empty($_POST['newpw1']) || empty($_POST['newpw2']) 
		|| !preg_match("/^[\w=+\-\/][\w='+\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$/", $_POST['acc'])
		|| !preg_match("/^[\w@#$%\^\&\*\-]+$/", $_POST['oldpw'])
		|| !preg_match("/^[\w@#$%\^\&\*\-]+$/", $_POST['newpw1'])
		|| !preg_match("/^[\w@#$%\^\&\*\-]+$/", $_POST['newpw2']))
		throw new Exception('Wrong Input(s)');
		
	$db = new PDO('sqlite:/var/www/cart.db');
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$q=$db->prepare('SELECT * FROM account WHERE email = ?');
	$q->execute(array($_POST['acc']));
	if($r=$q->fetch()){
		$saltPassword=sha1($r['salt'].$_POST['oldpw']);
		if($saltPassword == $r['password']){
			//echo 'old password verified';
			if ($_POST['newpw1']==$_POST['newpw2']){
				//echo 'same newpw';
				$salt=mt_rand();
				$newSaltPassword=sha1($salt.$_POST['newpw1']);
				//update the account with new password
				$q=$db->prepare('UPDATE account SET salt = ?, password = ? WHERE email = ?');
				$q->execute(array($salt,$newSaltPassword,$_POST['acc']));
				
				//expire the token and redirect to login.php
				setcookie('authtoken','',time()-3600);
				$_SESSION['authtoken']=null;
				echo "You have changed passqord successfully\nRedirecting to login page in 3 seconds";
				header('Refresh: 3; url=login.php');
				exit();
			}else {echo 'please confirm one more time...redirecting you to admin panel'; header('Refresh: 3; url=admin.php');}
		}else {echo 'Email or Password is wrong...redirecting you to admin panel'; header('Refresh: 3; url=admin.php');}
	}else {echo 'Email is not found...redirecting you to admin panel'; header('Refresh: 3; url=admin.php');}
	
}

header("Content-type: text/html; charset=utf-8");

try {
	// input validation
	if (empty($_REQUEST['action']) || !preg_match('/^\w+$/', $_REQUEST['action']))
		throw new Exception('Undefined Action');
	
	// check if the form request can present a valid nonce
	if ($_REQUEST['action']=='changePassword')
		csrf_verifyNonce($_REQUEST['action'], $_POST['nonce']);
	
	// run the corresponding function according to action
	if (($returnVal = call_user_func('ierg4210_' . $_REQUEST['action'])) === false) {
		if ($db && $db->errorCode()) 
			error_log(print_r($db->errorInfo(), true));
		throw new Exception('Failed');
	} else {
		// no functions are supposed to return anything
		// echo $returnVal;
	}

} catch(PDOException $e) {
	error_log($e->getMessage());
	header('Refresh: 3; url=login.php?error=db');
	echo '<strong>Error Occurred:</strong> DB <br/>Redirecting to login page in 3 seconds...';
} catch(Exception $e) {
	header('Refresh: 3; url=login.php?error=' . $e->getMessage());
	echo '<strong>Error Occurred:</strong> ' . $e->getMessage() . '<br/>Redirecting to login page in 3 seconds...';
}
?>