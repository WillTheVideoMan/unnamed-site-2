<?php
//Enable Error reporting for debug
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

//Defining the secure access parameter means that any config or functional files 
//can only be accessed via a 'require' rather than directly through URL and HTTP, improving saftey.
define( 'secureAccessParameter', true );
require '../../Project/Tools/config.php';

//Create a new database connection. The dbConnect fuction is defined in the config file.
$conn = dbConnect();

//Prepare a statment to select the start time from the walk status table. The start time was
//defined by an admisistrator user on the overview page.
$sqlQuery = $conn->prepare( "SELECT startTime FROM tblWalkStatus WHERE walkID = 1" );

//Execute the statment.
$sqlQuery->execute();

//Fetch the SQL return object.
$result = $sqlQuery->get_result();

//Fetch the first row of data from the SQL return object.
$dataRow = $result->fetch_assoc();
	
//Convert the string time to a PHP dateTime object. Store in a variable.
$startDateTime = strtotime($dataRow['startTime']);

//Make an accociative array to store our return object.
$returnJSON = array("status" => "OK", "timeToStart" => $startDateTime - time());

//Return the start time minus the current time to get the time to start.
echo json_encode($returnJSON);
?>