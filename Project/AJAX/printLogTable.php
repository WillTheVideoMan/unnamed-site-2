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

	//Define a database connection
	$conn = dbConnect();

	//Define variables for use during sorting, printing and searching.
	$prevStu = null;
	$counter = 0;
	$logArray = array();
	$yearGroup = null;
	$numPointer = null;
	$tableHeaderArray = array( "Soothill Foyer", "Pot Bank", "Long Liberty", "Beckwithshaw", "Soothill Foyer" );

	//Instantiate a new instance of the DynamicSQLHelper object - an SQL prepared statement parameter helper class.
	$dynamicSQLHelper = new DynamicSQLHelper();

	//Store the colunm sort option from the GET param ['order]
	$columnSortOptions = $_GET[ 'order' ];

	//Define a number of template SQL strands from which we will build our final SQL string.
	$sqlName = "(tblStudents.forename LIKE ? OR tblStudents.surname LIKE ?)";
	$sqlStuCode = "tblStudents.studentCode LIKE ?";
	$sqlYear = "tblStudents.yearGroup = ?";

	//Define the main SQL statement to select student and tracking data from the database.
	$sql = "SELECT tblStudents.studentCode, tblStudents.surname, tblStudents.forename, tblStudents.yearGroup, tblStuStaffLocLink.locationID, tblStuStaffLocLink.regTime FROM tblStudents LEFT JOIN tblStuStaffLocLink ON tblStudents.studentCode = tblStuStaffLocLink.studentCode";

	//If a year has been defined or a name search term has been defined, add to the main SQL.
	if ( isset( $_GET[ 'year' ] )or isset( $_GET[ 'query' ] ) )$sql .= " WHERE ";

	//If a year has been defined, then add an SQL strand to query against yearGroup, and 
	//add a param to hold the yearGroup value.
	if ( isset( $_GET[ 'year' ] ) ) {
		$dynamicSQLHelper->addStrand( $sqlYear );
		$dynamicSQLHelper->addParam( 's', $_GET[ 'year' ] );
	}

	//If a name search term is defined, then...
	if ( isset( $_GET[ 'query' ] ) ) {

		//Get each induvidual string, split at each space.
		$queryStrings = explode( " ", $_GET[ 'query' ] );

		//For each query string, if it contains a number, then set numPointer to equal the index of the 
		//number-containing-string in the query string array.
		for ( $i = 0; $i < count( $queryStrings ); $i++ ) {
			if ( strpbrk( $queryStrings[ $i ], "0123456789" ) )$numPointer = $i;
		}

		//If a number was entered, then override the name search as we know that the user
		//has entered a studentCode directly instead. A student code has a more definite result
		//that a name search.
		if ( !is_null( $numPointer ) ) {
			$dynamicSQLHelper->addStrand( $sqlStuCode );
			$dynamicSQLHelper->addParam( 's', $dynamicSQLHelper->buildLike( $queryStrings[ $numPointer ] ) );
		} else {
			//For every term, add strand and term values to the SQL helper object.
			foreach ( $queryStrings as $term ) {
				$dynamicSQLHelper->addStrand( $sqlName );
				$dynamicSQLHelper->addParam( 's', $dynamicSQLHelper->buildLike( $term ) );
				$dynamicSQLHelper->addParam( 's', $dynamicSQLHelper->buildLike( $term ) );
			}
		}
	}

	//Get all strands.
	$sql .= $dynamicSQLHelper->getStrands();

	//No matter what, order by studentCode.
	$sql .= " ORDER BY studentCode";

	//Prepare the SQL server with our statement.
	$sqlQuery = $conn->prepare( $sql );

	//If a year or name search term has been defined, then call 'call_user_func_array' inbuild function. 
	//This function passes an array to, and then calls, a user defined method. In this case, we are passing the array of 
	//parameters stored within the dynamicSQLHelper object to the inbuild sqlQuery 'bind_param' method. This method 
	//normally accepts a range of parameters in the form of string, which we could not pass to it directly since we do not
	//know the number of parameters we will pass at the beginning of each run. By using the 'call_user_func_array' inbuild //function, we can have any number of params, since an array we define ACTS as the params themselves.
	if ( isset( $_GET[ 'year' ] )or isset( $_GET[ 'query' ] ) )call_user_func_array( array( $sqlQuery, 'bind_param' ), $dynamicSQLHelper->getParams() );

	//Execute the query and get the result object. Convert the result object to an accociative array.
	$sqlQuery->execute();
	$result = $sqlQuery->get_result();
	$data = $result->fetch_all();

	//For each row of data returned, build an array to store the log data.
	for ( $i = 0; $i < $result->num_rows; $i++ ) {

		//Since the same student could have multiple rows retuned for them (the nature of a left join), we must
		//check if we are viewing a new student, or a more recent registration event for the same student, by
		//comparing the unique key of 'studentCode'
		if ( $data[ $i ][ 0 ] != $prevStu ) {

			//Set the previous studentCode to the current studentCode.
			$prevStu = $data[ $i ][ 0 ];

			//Increment loop counter by one. This reference the next index of the array.
			$counter++;

			//Add a new entry to the log array.
			$logArray[ $counter ] = array( "stuCode" => '', "surname" => '', "forename" => '', "year" => '', "logData" => array( '', '', '', '', '' ) );

			//Add the registration event details.
			$logArray[ $counter ][ 'stuCode' ] = $data[ $i ][ 0 ];
			$logArray[ $counter ][ 'surname' ] = $data[ $i ][ 1 ];
			$logArray[ $counter ][ 'forename' ] = $data[ $i ][ 2 ];
			$logArray[ $counter ][ 'year' ] = $data[ $i ][ 3 ];
		}

		//Format and add the registration time to the log array, in the format [HH:MM].
		$logArray[ $counter ][ 'logData' ][ $data[ $i ][ 4 ] ] = substr( $data[ $i ][ 5 ], 11, 5 );
	}
	?>

	<table class="data center">
		<tr class="row1">
			<td class="col1" data-row="1">Surname</td>
			<td class="col2 thickRight" data-row="1">Forename</td>

			<?php
			//For each column (since there are five locations), add the location name to each header. Then, if any
			//column sort options are defined, echo those options back into the table html 'data' params and
			//print a unicode arrow to signify the sort direction.
			for ( $i = 0; $i < 5; $i++ ) {
				?>
			<td id="item" class="col<?php echo $i + 3; ?>" data-row="1" data-loc="<?php echo $i;?>" data-sort="<?php if(!empty($columnSortOptions)) echo $columnSortOptions[1];?>">
                
				<?php
				echo $tableHeaderArray[ $i ];
				if ( !empty( $columnSortOptions ) && $columnSortOptions[ 0 ] == $i ) {
					if ( strtoupper( $columnSortOptions[ 1 ] ) == "A" )echo "&nbsp;&nbsp;&#8657;";
					else echo "&nbsp;&nbsp;&#8659;";
				}
				?>
			</td>
			
			<?php } ?>

		</tr>

		<?php 
		
		//Define an array of saved rows.
		$savedArray = array();
		
		//The following block takes each log and print it according to the column sorting options. 
		//This allows for the ordering of students based on wether they have or have not registered
		//at a specific location.
		for($i = 1; $i <= count($logArray); $i++)
		{
			//If there are sorting options defined...
			if(!empty($columnSortOptions))
			{
				//If the student has not registered at the checkpoint id stated in the sort options...
				if(empty($logArray[$i]['logData'][$columnSortOptions[0]]))
				{	
					//If the column sorting is set to descending, then Print the log as a HTML table row.
					//Else, save the log for printing later.
					if($columnSortOptions[1] == "D") PrintLog($i, $logArray);
					else SaveLog($i, $savedArray);
				}
				else
				{
					//If the column sorting is set to ascending, then Print the log as a HTML table row.
					//Else, save the log for printing later.
					if($columnSortOptions[1] == "D") SaveLog($i, $savedArray);
					else PrintLog($i, $logArray);
				}
			}
			//Else, print the log as no sorting has been defined.
			else
			{
				PrintLog($i, $logArray);
			}
		}
		
		//For each saved log, print it out.
		for($i = 0; $i < count($savedArray); $i++)
		{
			PrintLog($savedArray[$i], $logArray);
		}
		
		?>



	</table>
	<?php

//Else, the user must be accessing improperly, so deny access...
} else {
	echo( "Improper Access" );
	exit();
}


//A function to print out a HTML table row from a tracking log array.
function PrintLog( $logRef, $logArray ) {
	?>
	<tr class="row<?php echo $logRef + 1;?>">
		<td class="col1 y<?php echo $logArray[$logRef]['year'];?>" data-row="<?php echo $logRef + 1;?>">
			<?php echo $logArray[$logRef]['surname'];?>
		</td>
		<td class="col2 thickRight y<?php echo $logArray[$logRef]['year'];?>" data-row="<?php echo $logRef + 1;?>">
			<?php echo $logArray[$logRef]['forename'];?>
		</td>

		<?php 
	
		//For each location, print out the registration time if log data exsists in the log array. Else print
		//a red dash.
		for($j = 0; $j < 5; $j++) {
				
		if(empty($logArray[$logRef]['logData'][$j]))
		{?>

		<td class="col<?php echo ($j + 3);?> red" data-row="<?php echo $logRef + 1;?>">-</td>

		<?php } else { ?>

		<td class="col<?php echo ($j + 3);?> green" data-row="<?php echo $logRef + 1;?>">
			<?php echo $logArray[$logRef]['logData'][$j];?>
		</td>

		<?php } } ?>

	</tr>

	<?php
}

//Function to push a log array index reference to the saved log array.
function SaveLog( $logRef, & $savedLogs ) {
	array_push( $savedLogs, $logRef );
}

class DynamicSQLHelper {
	//Declare Private Varibales to store our ‘type’ and ‘Values’;
	private $queryStrands = array(), $values = array(), $types = '';

	//Method to append a new ‘value’ to array and new ‘type’ to string.  
	public
	function addParam( $type, $value ) {
		$this->values[] = $value;
		$this->types .= $type;

	}

	//Method to add a new SQL strand to 'queryStrands'.
	public
	function addStrand( $strand ) {
		$this->queryStrands[] = $strand;
	}

	//Return a new array, which contains references to all the values we stored whilst running through the program, and our ‘type’ string.
	public
	function getParams() {
		$refs = array();

		foreach ( $this->values as $key => $value )
			$refs[ $key ] = & $this->values[ $key ];

		return array_merge( array( $this->types ), $refs );
	}

	//Method to return all the SQL strands in a formtted manor, with an AND between each strand.
	public
	function getStrands() {
		return implode( " AND ", $this->queryStrands );
	}

	//Method to build the SQL like statement from a search param.
	public
	function buildLike( $string ) {
		return $string . '%';
	}
}
?>