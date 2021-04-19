<?php
//If the secure access parameter is not defined, then kill the process.
if ( !defined( 'secureAccessParameter' ) )die( 'Improper Access' );

//A function which returns the current status of the walk.
function getWalkStatus() {

	//Define an accociative array to hold our return params.
	$returnJSON = array("status" => "OK", "walkStatus" => "");
	
	//Make a database connection.
	$conn = dbConnect();
	
	//Prepare and execute a statement to pull the current walk status from the walkStatus table. 
	$sqlQuery = $conn->prepare( "SELECT status, startTime FROM tblWalkStatus WHERE walkID = 1" );
	$sqlQuery->execute();

	//Fetch and convert the SQL return object.
	$result = $sqlQuery->get_result();
	$dataRow = $result->fetch_assoc();
	
	//If the walk is in the WAITING state (1), then run.
	if($dataRow['status'] == 1)
	{
		//Create a php date-time object from the SQL timestamp. 
		$startDateTime = DateTime::createFromFormat('Y-m-d\TH:i', $dataRow['startTime']);
		
		//If the start time has already passed, then the walk upgrades to the ACTIVE state (2). Else, hold at WAITING (1).
		if($startDateTime < new DateTime('now'))
		{
			$returnJSON['walkStatus'] = "2";
		}
		else
		{
			$returnJSON['walkStatus']=  "1";
		}
	}
	//Else, the walk is in the INACTIVE state (0).
	else
	{
		$returnJSON['walkStatus'] = "0";
	}
	
	//Close connections.
	$sqlQuery->close();
	dbClose();
	
	//Return the return object as a JSON string.
	return json_encode($returnJSON);	
}
?>