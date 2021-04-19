<?php

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

define( 'DB_USER', 'wha-v25-u-145153' );
define( 'DB_PASS', 'z4/6Dh/CN' );
define( 'DB_SERVER', '10.16.16.1' );
define( 'SERVER_SUBDOMAIN', '11wha' );
define( 'SERVER_DOMAIN', 'ashvillecomputing.co.uk' );

function dbConnect( $server = DB_SERVER, $username = DB_USER, $password = DB_PASS, $database = 'wha-v25-u-145153', $link = 'conn' ) {
	global $$link;

	$$link = new mysqli( $server, $username, $password, $database );

	if ( $$link->connect_error ) {
		die( "Connection failed: " . $$link->connect_error );
	}

	return $$link;
}

function dbClose( $link = 'conn' ) {
	global $$link;

	return $$link->close();
}

$conn = dbConnect();

$sqlQuery = $conn->prepare( "UPDATE tblPage SET content = ? WHERE elementID = ?" );

$sqlQuery->bind_param("si", $text, $elemID);

$text = $_POST['text'];

$elemID = $_POST['elemID'];

$sqlQuery->execute();

$sqlQuery->close();

dbClose();

?>