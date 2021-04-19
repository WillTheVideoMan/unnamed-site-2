<?php
session_start();

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );
	
//Make an accociative array to store our return object.
$returnJSON = array( "status" => "", "message" => "" );

if(isset($_SESSION['login']) && $_SESSION['level'] == 1 && isset($_POST[ 'status' ]) && isset($_POST[ 'startTime' ]))
{
	//Defining the secure access parameter means that any config or functional files 
	//can only be accessed via a 'require' rather than directly through URL and HTTP, improving saftey.
	define( 'secureAccessParameter', true );
	require '../../Project/Tools/config.php';
		
	//Define a database connection.
	$conn = dbConnect();

		$sqlQuery = $conn->prepare("UPDATE tblWalkStatus SET status = ?, startTime = ? WHERE walkID = 1");
		$sqlQuery->bind_param( "is", $status, $startString);

		$status =$_POST[ 'status' ];
		$startString =$_POST[ 'startTime' ];
		$sqlQuery->execute();
		
		if($status == 0)
		{
			$sqlQuery = $conn->prepare( "TRUNCATE tblStuStaffLocLink" );
			$sqlQuery->execute();
		}

		$sqlQuery->close();
		dbClose();

	//Write messages to validate the success of the operation.
	$returnJSON[ 'status' ] = "OK";
	$returnJSON[ 'message' ] = "Status Updated sucessfully!";
} else {
	//Write error messages.
	$returnJSON[ 'status' ] = "ERROR";
	$returnJSON[ 'message' ] = "Access Denied!";
}
//Return the JSON response.
echo json_encode( $returnJSON );
 ?>