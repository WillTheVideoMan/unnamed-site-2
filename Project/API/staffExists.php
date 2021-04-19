<?php
//Enable Error reporting for debug
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

//Defining the secure access parameter means that any config or functional files 
//can only be accessed via a 'require' rather than directly through URL and HTTP, improving saftey.
define( 'secureAccessParameter', true );
require '../../Project/Tools/config.php';

//Make an accociative array to store our return object.
$returnJSON = array("status" => "", "message" => "", "fullName" => "");

//If the GET param is set, then run.
if(isset($_GET[ 'staffCode' ]))
{	
	//Make a database connection.
	$conn = dbConnect();
	
	//Prepare and execute a statement which returns the staff information which matches the given staff code.
	$sqlQuery = $conn->prepare( "SELECT staffCode, forename, surname FROM tblStaff WHERE staffCode = ?" );
	$sqlQuery->bind_param( "s", $staffCode );
	$staffCode = strtoupper($_GET[ 'staffCode' ]);
	$sqlQuery->execute();
	
	//Fetch the SQL return object.
	$result = $sqlQuery->get_result();
	
	//If there is more than zero rows, the staff member exists. 
	if($result->num_rows > 0)
	{
		//Convert SQL return object to assoc. array.
		$data = $result->fetch_assoc();
		
		//Write messages and data to the return array.
		$returnJSON['status'] = "OK";
		$returnJSON['fullName'] = $data['forename'] . " " . $data['surname'];
		$returnJSON['message'] = "The staff member does exists.";
	}
	else
	{
		//Write messages. 
		$returnJSON['status'] = "ERROR";
		$returnJSON['fullName'] = "NULL";
		$returnJSON['message'] = "The staff member does not exist.";
	}
	
	//Close connections.
	$sqlQuery->close();
	dbClose();
}
else
{
	//Write messages.
	$returnJSON['status'] = "ERROR";
	$returnJSON['fullName'] = "NULL";
	$returnJSON['message'] = "Please ensure you have included all GET variables: 'staffCode'";
}

//echo the return object as a JSON string.
echo json_encode($returnJSON);
?>