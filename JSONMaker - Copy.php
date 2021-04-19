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

$sqlQuery = $conn->prepare( "SELECT * FROM tblPage" );

$sqlQuery->execute();

$result = $sqlQuery->get_result();

$data = $result->fetch_all();

$sqlQuery->close();

dbClose();

foreach ( $data as & $element ) {
	$encodedArray = $element[ 1 ];

	$element[ 1 ] = json_decode( $encodedArray );
}

$elementArray = buildElementArray( $data, 1 );

function buildElementArray( $data, $elementIndex ) {
	$elementIndex = $elementIndex - 1;

	$tempArray = array();
	
	array_push($tempArray, array("id" => $data[ $elementIndex ][ 0 ], "tag" => $data[ $elementIndex ][ 2 ], "attributes" => $data[ $elementIndex ][ 3 ], "content" => $data[ $elementIndex ][ 4 ], "elements" => array() ) );

	if ( count( $data[ $elementIndex ][ 1 ] ) > 0 ) {
		foreach ( $data[ $elementIndex ][ 1 ] as $contains ) {
			array_push( $tempArray[0][ 'elements' ], buildElementArray( $data, $contains ) );
		}
	}

	return $tempArray;
}

echo( json_encode( $elementArray ) );

?>