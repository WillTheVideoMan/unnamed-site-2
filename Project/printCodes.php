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
?>

<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Print Student Codes</title>
	<link rel="stylesheet" type="text/css" href="main.css">
	<script>
		//When the window is loaded, run a function.
		window.onload = function () {

			//Call function to print the window content.
			window.print();

			//mediaQueryList : Stores Information About The Current Browser Media State. Media is any IO 
			//of any kind on the page. Printing is a form out output.

			//Look out for any Print Action.
			var mediaQueryList = window.matchMedia( 'print' );

			//Bind a listner to the print action. 
			mediaQueryList.addListener( function ( mql ) {

				//If printing has finished,then redirect to the manage page.
				if ( !mql.matches ) {
					window.location.href = "../Project/Manage.php";
				}
			} );
		}
	</script>
</head>

<body>
	<?php
	//If we are printing just one student, then create and display their QR code from the QRServer api.
	if ( isset( $_GET[ 'stuCode' ] ) ) {
		$stuCode = strtoupper( $_GET[ 'stuCode' ] );
		?>
	<div class="codeContainer">
		<img src="https://api.qrserver.com/v1/create-qr-code/?size=320x320&data=<?php echo $stuCode;?>" width=300px height=300px>
		<p>
			<?php echo $stuCode;?>
		</p>
	</div>
	<?php 
	
	//Else, we are printing all the students.
	} else {
	
	//Make a new database connection.
	$conn = dbConnect();
	
	//Prepare and execute a statement to select all the student codes from the student table.
	$sqlQuery = $conn->prepare( "SELECT studentCode FROM tblStudents" );
	$sqlQuery->execute();
	
	//Fetch the SQL return object.
	$result = $sqlQuery->get_result();
	
	//If there is more than one student returned, then...
	if($result->num_rows>0)
	{	
		//While there is another student to fetch, print out their QR code.
		while($dataRow = $result->fetch_assoc())
		{?>
	<div class="codeContainer">
		<img src="https://api.qrserver.com/v1/create-qr-code/?size=320x320&data=<?php echo $dataRow['studentCode'];?>" width=300px height=300px>
		<p>
			<?php echo $dataRow['studentCode'];?>
		</p>
	</div>
	<?php }}}?>
</body>

</html>