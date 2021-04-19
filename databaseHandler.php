<?php 
define('DB_SERVER','cust-mysql-123-19');
define('DB_SERVER_USERNAME', '11wha');
define('DB_SERVER_PASSWORD', '11wha');
define('DB_DATABASE', '11wha');

function db_connect($server = DB_SERVER, $username =DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link' ) 
{
	global $$link;
	
	$$link = new mysqli($server, $username, $password, $database);
	
	if ($$link->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	return $$link;
}

function db_close($link = 'db_link'){
	global $$link;
	
	return $$link->close;
}
?>