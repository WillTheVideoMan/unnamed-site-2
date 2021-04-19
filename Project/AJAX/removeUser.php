<?php
session_start();

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

//Make an accociative array to store our return object.
$returnJSON = array( "status" => "", "message" => "" );

//If the user is logged in, and the user is an admin, and the user has POSTed us a username, then...
if ( isset( $_SESSION[ 'login' ] ) && $_SESSION[ 'level' ] == 1 && isset($_POST[ 'uName' ])) {
	
	//Defining the secure access parameter means that any config or functional files 
	//can only be accessed via a 'require' rather than directly through URL and HTTP, improving saftey.
	define( 'secureAccessParameter', true );
	require '../../Project/Tools/config.php';

	//Define a database connection.
	$conn = dbConnect();

	//Define a statement to delete a user from the tracking users table. Bind the username as a param.
	$sqlQuery = $conn->prepare( "DELETE FROM tblAdminUsers WHERE uName = ?" );
	$sqlQuery->bind_param( "s", $uName );
	$uName = $_POST[ 'uName' ];
	
	//Execute the command.
	$sqlQuery->execute();
	
	//Close connections.
	$sqlQuery->close();
	dbClose();

	//Write messages to validate the success of the operation.
	$returnJSON[ 'status' ] = "OK";
	$returnJSON[ 'message' ] = "User was removed sucessfully!";
} else {
	//Write error messages.
	$returnJSON[ 'status' ] = "ERROR";
	$returnJSON[ 'message' ] = "Access Denied!";
}

//Return the JSON response.
echo json_encode( $returnJSON );
?>