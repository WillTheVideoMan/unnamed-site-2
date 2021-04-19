<?php

if(!defined('secureAccessParameter')) die ('Improper Access');

define('dbUser', '11wha');
define('dbPass', '11wha');
define('dbServer','cust-mysql-123-19');
define('adminU', 'test');
define('adminP', '$2y$10$4DfrAe5gyvgNMOomhadM5OLQ6FhdSKsJ4uAkhaF9Z2FrWmD3/A3WK'); //hash of string 'test'.
define('authSecret', 'V6XGDSZGXADUOJU5');

function dbConnect($server = dbServer, $username = dbUser, $password = dbPass, $database = '11wha', $link = 'db_link' ) 
{
	global $$link;
	
	$$link = new mysqli($server, $username, $password, $database);
	
	if ($$link->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	return $$link;
}

function dbClose($link = 'db_link')
{
	global $$link;
	
	return $$link->close;
}

function verifyTFA ($inputCode)
{
	require '../../PHPGansta_GoogleAuth.php';
	
	$ga = new PHPGangsta_GoogleAuthenticator();
	$checkResult = $ga->verifyCode(authSecret, $inputCode);
	return $checkResult;
}
?>