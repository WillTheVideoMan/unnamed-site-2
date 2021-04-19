<?php
//Enable Error reporting for debug
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

//Defining the secure access parameter means that any config or functional files 
//can only be accessed via a 'require' rather than directly through URL and HTTP, improving saftey.
define( 'secureAccessParameter', true );
require '../../Project/Tools/config.php';

//Make an accociative array to store our return object.
$returnJSON = array("status" => "", "message" => "", "fullName" => "", "yearGroup" => "");

//If the GET param is set, then run.
if(isset($_GET[ 'stuCode' ]))
{
	//Make a database connection.
	$conn = dbConnect();
	
	//Prepare and execute a statement which returns the student information which matches the given student code.
	$sqlQuery = $conn->prepare( "SELECT studentCode, surname, forename, yearGroup FROM tblStudents WHERE studentCode = ?" );
	$sqlQuery->bind_param( "s", $stuCode );
	$stuCode = strtoupper($_GET[ 'stuCode' ]);
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
		$returnJSON['yearGroup'] = $data['yearGroup'];
		$returnJSON['message'] = "The student does exists.";
	}
	else
	{
		//Write messages. 
		$returnJSON['status'] = "ERROR";
		$returnJSON['fullName'] = "NULL";
		$returnJSON['yearGroup'] = "NULL";
		$returnJSON['message'] = "The student does not exist.";
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
	$returnJSON['yearGroup'] = "NULL";
	$returnJSON['message'] = "Please ensure you have included all GET variables: 'stuCode'";
}

//echo the return object as a JSON string.
echo json_encode($returnJSON);
?>