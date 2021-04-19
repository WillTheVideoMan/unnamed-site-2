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

//If the session variable for login is set, it means a user has been authenticated.
if ( isset( $_SESSION[ 'login' ] ) ) {
	
	//Create a new database connection. The dbConnect fuction is defined in the config file.
	$conn = dbConnect();

	//Define an associative array which will be used to store an organised store of all tracking
	//log data. This array will be returned later as a JSON string.
	$returnArray = array("logs" => array(array("stuCode" => '', "locID" => 0, "year" => 0, "regTime" => "")), "checkStaff" => array(array(), array(), array(), array(), array()), "stuCount" => 0, "serverTime" => '');

	//Define a pointer to track our position in the send array we will return.
	$returnArrayIndex = 0;

	//Prepare a statement to select all our tracking data from two tables in our database. An inner join
	//will be performed on two tables - 'tblStudents' and a link table 'tblStuStaffLocLink'. This inner join 
	//will return all students who have registered at a location, including that location and the registering
	//staff member. This will ensure that the database remains in normal form, for max efficiency.
	$sqlQuery = $conn->prepare( "SELECT tblStuStaffLocLink.studentCode, tblStuStaffLocLink.locationID,tblStuStaffLocLink.regTime, tblStudents.yearGroup FROM tblStuStaffLocLink INNER JOIN tblStudents ON tblStuStaffLocLink.studentCode = tblStudents.studentCode ORDER BY tblStuStaffLocLink.studentCode" );
	
	//Execute the statment.
	$sqlQuery->execute();

	//Fetch the SQL return object.
	$result = $sqlQuery->get_result();

	//If there is more than one returned row, then...
	if ( $result->num_rows > 0 ) {
		
			
		while ( $dataRow = $result->fetch_assoc() ) {
			
			//If the student code from the SQL data row does is not equal to the student code stored 
			//in the current index of our return array, it means that we are taking into consideration a new student. 
			//Since many students can register at many locations, there will be many data rows where the student
			//code is the same for different locations. Since our returned SQL object is ordered by 
			//student code, it means we can simply check if the new row's student code matches the last
			//index of the return array to determine wether we need to make a new entry to the array.
			if ($dataRow[ 'studentCode' ] != $returnArray['logs'][ $returnArrayIndex ][ 'stuCode' ] )
			{	
				//Increment the index pointer by one. This will make a new 'entry' to the array.
				$returnArrayIndex++;
				
				//Write the student details and location information to the return array.
				$returnArray['logs'][ $returnArrayIndex ][ 'stuCode' ] = $dataRow[ 'studentCode' ];
				$returnArray['logs'][ $returnArrayIndex ][ 'year' ] = $dataRow[ 'yearGroup' ];
				$returnArray['logs'][ $returnArrayIndex ][ 'locID' ] = $dataRow[ 'locationID' ];
				$returnArray['logs'][ $returnArrayIndex ][ 'regTime' ] = $dataRow[ 'regTime' ];
			}
			
			//If the registration location ID currently stored for that student is less than the new SQL
			//data row's location ID, then overwrite the location ID with the new SQL data row's value. 
			//This ensures that only the most current location ID is returned for each student in the array.
			if($returnArray['logs'][ $returnArrayIndex][ 'locID' ] < $dataRow[ 'locationID' ])
			{
				//Overwrite location ID and registration time.
				$returnArray['logs'][ $returnArrayIndex][ 'locID' ] = $dataRow[ 'locationID' ];
				$returnArray['logs'][ $returnArrayIndex][ 'regTime' ] = $dataRow[ 'regTime' ];
			}
		}
	}
	
	//Since the array always has an empty entry at index 0, remove the first entry.
	array_shift($returnArray['logs']);
	
	//Prepare a statement to select the staffCodes and location IDs from the link table.
	$sqlQuery = $conn->prepare( "SELECT staffCode, locationID FROM tblStuStaffLocLink ORDER BY staffCode" );
	
	//Execute the statment.
	$sqlQuery->execute();

	//Fetch the SQL return object.
	$result = $sqlQuery->get_result();
	
	//Define a tracking array. This will store information about wether we
	//have written any information to the checkstaff array, based on the index
	//of Location ID. This prevents zero-index errors in the comparison later on,
	//where we must check the previous index of the checkstaff array with the current
	//index. Without first knowing if the array is empty, we cannot know wether we can 
	//carry out a comparison operation.
	$first = array(true,true,true,true,true);
	
	//If there is more than one returned row, then...
	if($result->num_rows > 0)
	{
		//While a new row can be read, loop.
		while($dataRow = $result->fetch_assoc())
		{
			//If this is the first time any staff code will be written to the array, then...
			if($first[$dataRow['locationID']])
			{
				//Push that staff code to the staff information array.
				array_push($returnArray['checkStaff'][$dataRow['locationID']], $dataRow['staffCode']);
				
				//Now, the location has been written to! Set the first pointer to false.
				$first[$dataRow['locationID']] = false;
			} 
			
			//Else, if the new staff code from the SQL data row does not equal the previous index of the
			//staff information array, then push that staff member to the array. This ensures that each
			//staff code is only written to the array once, since our SQL return object is sorted by staff code.
			else if($returnArray['checkStaff'][$dataRow['locationID']][count($returnArray['checkStaff'][$dataRow['locationID']]) - 1] != $dataRow['staffCode'])
			{
				//Push that staff code to the staff information array.
				array_push($returnArray['checkStaff'][$dataRow['locationID']], $dataRow['staffCode']);
			}
		}
	}
	
	//Prepare a statment to select the number of students from the student table.
	$sqlQuery = $conn->prepare( "SELECT COUNT(*) studentCode FROM tblStudents" );
	
	//Execute the statment.
	$sqlQuery->execute();
	
	//Fetch the SQL return object.
	$result = $sqlQuery->get_result();
	
	//Convert the SQL return object into an associative array.
	$data = $result->fetch_all();

	//Write the student count to the return array.
	$returnArray[ 'stuCount' ] = $data[ 0 ][ 0 ];
	
	//Write the current server time to the return array.
	$returnArray[ 'serverTime' ] = date('Y-m-d\TH:i:s');

	//Echo/Return the encoded JSON string.
	echo json_encode( $returnArray );

	//Close the SQL query.
	$sqlQuery->close();
	
	//Close the datbase connection.
	dbClose();

//Else, if the user has not logged in, then display an error message and exit.
} else {
	echo( "Improper Access" );
	exit();
}

?>