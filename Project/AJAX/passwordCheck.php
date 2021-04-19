<?php
//Start the session
session_start();

//Enable Error reporting for debug
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

//Defining the secure access parameter means that any config or functional files 
//can only be accessed via a 'require' rather than directly through URL and HTTP, improving saftey.
define( 'secureAccessParameter', true );
require '../../Project/Tools/config.php';

//Make an accociative array to store our return object.
$returnJSON = array( "status" => "", "message" => "" );

//If the user is logged in, and the POST data has been set, then...
if (isset( $_SESSION[ 'login' ] ) && isset($_POST[ 'pass' ])) {

	//Make a database connection.
	$conn = dbConnect();
	
	//Define a query to select the hashed password for the currently logged in user. This
	//means that it is only possible to check the password of the currently logged in user, rather
	//than having the ability to check the password for any user, a potential security hole.
	$sqlQuery = $conn->prepare( "SELECT pass FROM tblAdminUsers WHERE uName = ?" );
	
	//Bind params
	$sqlQuery->bind_param( "s", $uName );
	$uName = $_SESSION[ 'login' ];
	
	//Execute the query.
	$sqlQuery->execute();
	$result = $sqlQuery->get_result();
	
	//If there was a response
	if($result->num_rows > 0)
	{
		//Fetch the data from the SQL return object.
		$dataRow = $result->fetch_assoc();
		
		//If the hashed version of the  password entered matches the hashed version from the 
		//database, then...
		if(password_verify($_POST[ 'pass' ], $dataRow['pass']))
		{
			//Write messages to validate the success of the operation.
			$returnJSON[ 'status' ] = "OK";
			$returnJSON[ 'message' ] = "Password is correct!";
		}
		else
		{
			//Write error messages.
			$returnJSON[ 'status' ] = "ERROR";
			$returnJSON[ 'message' ] = "Password is incorrect!";
		}
	}
	
	//Close the query and database connection.
	$sqlQuery->close();
	dbClose();

} else {
	//Write error messages.
	$returnJSON[ 'status' ] = "ERROR";
	$returnJSON[ 'message' ] = "Access Denied!";
}

//Return the JSON response.
echo json_encode( $returnJSON );
?>