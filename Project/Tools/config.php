<?php

//If the secure access parameter is not defined, then kill the process.
if ( !defined( 'secureAccessParameter' ) )die( 'Improper Access' );

//Define databas connection crededentials.
define( 'DB_USER', 'wha-v25-u-145153' );
define( 'DB_PASS', 'z4/6Dh/CN' );
define( 'DB_SERVER', '10.16.16.1' );
define('SERVER_SUBDOMAIN', '11wha');
define('SERVER_DOMAIN', 'ashvillecomputing.co.uk');

//A function that returns a super-global database connection.
function dbConnect( $server = DB_SERVER, $username = DB_USER, $password = DB_PASS, $database = 'wha-v25-u-145153', $link = 'conn' ) {
	global $$link;

	$$link = new mysqli( $server, $username, $password, $database );

	if ( $$link->connect_error ) {
		die( "Connection failed: " . $$link->connect_error );
	}

	return $$link;
}

//A function that closes a superglobal database connection.
function dbClose( $link = 'conn' ) {
	global $$link;

	return $$link->close();
}
?>