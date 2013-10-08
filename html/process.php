<?php
include_once('lib/db.inc.php');

function ierg4210_prod_fetch() {
	// input validation or sanitization
	$_POST['pid'] = (int) $_POST['pid'];
	// DB manipulation
	global $db;
	$db = ierg4210_DB();
	$q = $db->prepare("SELECT name, price, pid FROM products WHERE pid = ?;");
	if ($q->execute(array($_POST['pid'])))
		return $q->fetchAll();
}

function ierg4210_prod_list_fetch() {
	$array = json_decode($_POST['list_of_pid']);
	
	global $db;
	$db = ierg4210_DB();
	$a = sprintf('SELECT name, price, pid FROM products WHERE pid IN (%s);',implode(',',array_fill(1, count($array), '?'))); //select the array of list into query
	
	$q = $db->prepare($a);
	if ($q->execute($array))
		return $q->fetchAll();
}

//function should be above
header('Content-Type: application/json');

// input validation
if (empty($_REQUEST['action']) || !preg_match('/^\w+$/', $_REQUEST['action'])) {
	echo json_encode(array('failed'=>'undefined'));
	exit();
}

// The following calls the appropriate function based to the request parameter $_REQUEST['action'],
//   (e.g. When $_REQUEST['action'] is 'cat_insert', the function ierg4210_cat_insert() is called)
// the return values of the functions are then encoded in JSON format and used as output
try {
	if (($returnVal = call_user_func('ierg4210_' . $_REQUEST['action'])) === false) {
		if ($db && $db->errorCode()) 
			error_log(print_r($db->errorInfo(), true));
		echo json_encode(array('failed'=>'1'));
	}
	echo 'while(1);' . json_encode(array('success' => $returnVal));
} catch(PDOException $e) {
	error_log($e->getMessage());
	echo json_encode(array('failed'=>'error-db'));
} catch(Exception $e) {
	echo 'while(1);' . json_encode(array('failed' => $e->getMessage()));
}
?>