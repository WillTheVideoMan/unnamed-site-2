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

//If the student is not logged in, or if a student code has not been defined, then redirect to the
//login area.
if(!isset($_SESSION['stuLogin']) or !isset($_GET['stuCode']))
{
	header( 'Location:http://11wha.ashvillecomputing.co.uk/Project/Student' );
	exit();
}
else
{
	//Get the segments of the email address.
	$emailSegments = explode("@", $_SESSION['stuLogin']['email']);
	
	//If the email address from google does not match the GET variable (tamper detection of GET 
	//variables), then redirect.
	if(strtoupper($emailSegments[0]) != strtoupper($_GET['stuCode']))
	{
		header( 'Location:http://11wha.ashvillecomputing.co.uk/Project/googleAuth/LogOut' );
		exit();
	}
}

?>

<!doctype html>
<html>
	<head>
<title><?php if(isset($_GET['stuCode'])) echo strtoupper($_GET['stuCode']) . " - "; ?>Student Card</title>
	<link rel="stylesheet" type="text/css" href="main.css">
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
	<link rel="icon" href="../Project/Resources/favicon.png" type="image/x-icon"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script>
		$( document ).ready( function () {

			//Fade in the main division.
			$( "#bodyDiv" ).fadeIn( 150 );
			
			//If the logout button is clicked, then visit the login page with the logout variable set.
			$( "#btnLogOut" ).click( function () {
				$( "#bodyDiv" ).fadeOut( 150 );
				setTimeout(
					function () {
						window.location.href = "../Project/googleAuth/LogOut.php";
					}, 150 );
			} );
		} );
	</script>
</head>
	
<header style="padding-top: 32px;">
	<h1><?php if(isset($_GET['stuCode'])) echo strtoupper($_GET['stuCode']) . " - "; ?>Student Card</h1>
</header>

<body>
	<div id = "bodyDiv">
	<?php
	
	//Make a new database connection.
	$conn = dbConnect();
	
	//Prepare and execute a statement to select the user with the matching student code from the database
	$sqlQuery = $conn->prepare( "SELECT surname, forename FROM tblStudents WHERE studentCode = ?" );
	$sqlQuery->bind_param("s", $stuCode);
	$stuCode = strtoupper($_GET['stuCode']);
	$sqlQuery->execute();

	//Fetch the SQL return object.
	$result = $sqlQuery->get_result();
		
	//Convert the SQL return object to an assoc. array.
	$dataRow = $result->fetch_assoc();
		
	//Then, print out the QR code, using the QR code creation API from qrserver.com.
	?>
	
		<div class = "loginWrap">
	 		<h1><?php echo $stuCode . ": " . $dataRow['forename'] . " " . $dataRow['surname'];?></h1>
			<a href = "https://api.qrserver.com/v1/create-qr-code/?size=350x350&data=<?php echo $stuCode;?>" download>
				<img src = "https://api.qrserver.com/v1/create-qr-code/?size=350x350&data=<?php echo $stuCode;?>"
					 class = "downloadQuery" width = 350px height = 350px>
				<p class = "btn center">DOWNLOAD</p>
			</a>
			<h2>Tracking Data:</h2>
			<div>
				<table class = "data center">
                <?php
				
				//Prepare and execute a statement to fetch the tracking information from the tracking table,
				//where the student code matches that of the currently logged in user.
				$sqlQuery = $conn->prepare( "SELECT locationID, regTime FROM tblStuStaffLocLink WHERE studentCode = ? ORDER BY locationID" );
				$sqlQuery->bind_param("s", $stuCode);
				$stuCode = strtoupper($_GET['stuCode']);
				$sqlQuery->execute();
				
				//Fetch and convert the SQL return object into an assoc. array.
				$result = $sqlQuery->get_result();
				$data = $result->fetch_all();
				
				//Define an array of the location names.
				$locNames = array('Soothill Foyer (S)', 'Pot Bank', 'Long Liberty' ,'Beckwithshaw', 'Soothill Foyer (F)');
				
				//A Looping counter to track our progress through the printing proccess.
				$count = 0;
				
				//For each location, print out the corresponding registration time. If no time
				//exists yet, then print a dash.
				for($i = 0; $i < 5; $i++)
				{		
						//If we have not yet printed all the locations that we have records for, then print
						//out the registration time in the row.
						if($count < count($data))
						{ 
							//If the location matches $i (Since it may be possible that registrations //occoured out of order)
							if($data[$count][0] == $i)
							{ ?>
                        
                        	<tr>
								<td><?php echo $locNames[$i];?></td>
								<td><?php echo substr($data[$count][1],11,5); ?></td>
							</tr>
							
							<?php 
							$count++;
							}
						}
						//Else, we don't have data for this location yet, so just print a dash.
						else
						{ ?>
                        
							<tr>
								<td><?php echo $locNames[$i];?></td>
								<td><?php echo "&nbsp;&nbsp;-&nbsp;&nbsp;" ?></td>
							</tr>
                        
					<?php }
				}
				?>
				</table>
			</div>
          
		</div>
	
      <div class = "btn center" id = "btnLogOut">
            	Log Out
            </div>
		</div>
</body>
</html>