<?php
//Enable Error reporting for debug
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

//Defining the secure access parameter means that any config or functional files 
//can only be accessed via a 'require' rather than directly through URL and HTTP, improving saftey.
define( 'secureAccessParameter', true );
require '../../Project/Tools/config.php';

//Make an accociative array to store our return object.
$returnJSON = array("status" => "", "message" => "");

//Define a database connection.
$conn = dbConnect();

//If all the GET params are set, then run.
if(isset($_GET[ 'stuCode' ]) && isset($_GET[ 'staffCode' ]) && isset($_GET[ 'locID' ]))
{
	//Prepare a statement to select the student code from the student table. This is a check exists statement. Bind params too.
	$sqlQuery = $conn->prepare( "SELECT studentCode FROM tblStudents WHERE studentCode = ?" );
	$sqlQuery->bind_param( "s", $stuCode );
	$stuCode = $_GET[ 'stuCode' ];
	$staffCode = $_GET[ 'staffCode' ];
	$locID = $_GET[ 'locID' ];

	//Execute the statement and fetch the SQL return object.
	$sqlQuery->execute();
	$result = $sqlQuery->get_result();
	
	//If the student exists, then insert a registration event for them.
	if ( $result->num_rows > 0) {

		//Statement to insert tracking data
		$sqlQuery = $conn->prepare( "INSERT INTO tblStuStaffLocLink (studentCode, staffCode, locationID) VALUES (?,?,?)" );
		$sqlQuery->bind_param( "sss", $stuCode, $staffCode, $locID );
		$sqlQuery->execute();
	
		//Write messages.
		$returnJSON['status'] = "OK";
		$returnJSON['message'] = "Complete!";
	}
	else
	{
		//Write messages.
		$returnJSON['status'] = "ERROR";
		$returnJSON['message'] = "The student you are trying to add new data for does not exist.";
	}

//Close connections.
$sqlQuery->close();
dbClose();

}
else
{
	//Write messages.
	$returnJSON['status'] = "ERROR";
	$returnJSON['message'] = "Please ensure you have included all GET variables: 'stuCode', 'staffCode', 'locID'";
}

//echo the return object as a JSON string .
echo json_encode($returnJSON);

?>