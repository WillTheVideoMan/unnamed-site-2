<?php
//Start the session
session_start(); 

//Enable Error reporting for debug
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

//Defining the secure access parameter means that any config or functional files 
//can only be accessed via a 'require' rather than directly through URL and HTTP, improving saftey.
define( 'secureAccessParameter', true );
require '../Project/Tools/config.php';
require '../Project/Tools/walkFunctions.php';

//If the user is not logged in, then redirect to the login page.
if ( !isset( $_SESSION[ 'login' ] ) ) {
	header( 'Location:http://www.11wha.ashvillecomputing.co.uk/Project/Login.php' );
	exit();
}

//Call the get walk status function. Assign a variable to its returned value.
$walkStatus;
$returnObject = json_decode( getWalkStatus(), 1 );
if ( $returnObject[ 'status' ] == "OK" )$walkStatus = $returnObject[ 'walkStatus' ];

//If the walk has not begun yet, redirect to the overview page.
if ( $walkStatus == 0 ) {
	header( 'Location:http://www.11wha.ashvillecomputing.co.uk/Project/Overview' );
	exit();
}
?>

<!doctype html>
<html>
	<head>
<title><?php if(isset($_GET['staffCode'])) echo strtoupper($_GET['staffCode']) . " - "; ?>Staff Card</title>
	<link rel="stylesheet" type="text/css" href="main.css">
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
	<link rel="icon" href="../Project/Resources/favicon.png" type="image/x-icon"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script>
		$( document ).ready( function () {

			//Fade in the main division
			$( "#bodyDiv" ).fadeIn( 150 );
			
			//When the div with the 'toOverview' ID is clicked, redirect to the overview page.
			$( "#toOverview" ).click( function () {
				$( "#bodyDiv" ).fadeOut( 150 );
				setTimeout(
					function () {
						window.location.href = "../Project/Overview";
					}, 150 );
			} );
			
			//When the div with the 'btnLogOut' ID is clicked, redirect to the Login/Logout page.
			$( "#btnLogOut" ).click( function () {
				$( "#bodyDiv" ).fadeOut( 150 );
				setTimeout(
					function () {
						window.location.href = "../Project/Login?logOut";
					}, 150 );
			} );
		} );
	</script>
</head>
	
<header>
	<h1><?php if(isset($_GET['staffCode'])) echo strtoupper($_GET['staffCode']) . " - "; ?>Staff Card</h1>
	<nav>
		<ul>
			<li id="toOverview" >Return To Overview</li>
		</ul>
	</nav>
</header>

<body>
	<div id = "bodyDiv">
	<?php
	
	//If a staff code has been set in the GET params.
	if(isset($_GET['staffCode']))
	{ 
	
	//Make a database connection.
	$conn = dbConnect();
	
	//Prepare and execute a statement to select the staff from the database where the staffCode matches the 
	//set user.
	$sqlQuery = $conn->prepare( "SELECT surname, forename FROM tblStaff WHERE staffCode = ?" );
	$sqlQuery->bind_param("s", $staffCode);
	$staffCode = strtoupper($_GET['staffCode']);
	$sqlQuery->execute();
		
	//Fetch the SQL return object.
	$result = $sqlQuery->get_result();
	
	//If the staff member exists, then print.
	if($result->num_rows>0)
	{	
		//Convert the SQL return object to an assoc. array.
		$dataRow = $result->fetch_assoc();
		
		//Print the staff details:
		?>
	
		<div class = "loginWrap">
	 		<h1><?php echo $staffCode . ": " . $dataRow['forename'] . " " . $dataRow['surname'];?></h1>
		</div>
	
	<?php
	//Else, the staff member does not exist.
	} else { ?>
		
		<div class = "loginWrap">
	 		<h1>Staff Member Does Not Exist! Check Staff Code And Try Again!</h1>
		</div>
	
	<?php }} ?>
		</div>
</body>
</html>