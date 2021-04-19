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

//Make a new database connection.
$conn = dbConnect();

//Reset the user information string.
$msg = "";

//Boolean to track if the invite expired.
$expired = false;

//Prepare a statement to select the prospective user's info, matching the inviteID from the GET
//variable encoded in the URL.
$sqlQuery = $conn->prepare( "SELECT userCode, level FROM tblInvites WHERE inviteID = ?" );
$sqlQuery->bind_param( "s", $inviteCode );

//Bind params and execute.
$inviteCode = $_GET[ 'inviteCode' ];
$sqlQuery->execute();

//Get the SQL return object.
$result = $sqlQuery->get_result();
	
//Check if the user exists in the table. 
if ( $result->num_rows > 0 ) {
	
	//If the user clicked the submit button, then proceed to add the user to the database.
	if ( isset( $_POST[ 'subBtn' ] ) ) {
		
		//Variable to contain the username.
		$uName = strtoupper( $_POST[ 'uName' ] );
		
		//Convert the SQL return object into an assoc. array.
		$dataRow = $result->fetch_assoc();
		
		//If the user entered the right user code into the text input, then continue. This is a 
		//security step to ensure that only the user who was send the email can get access to the 
		//tracking suite.
		if ( $uName == $dataRow[ 'userCode' ] ) {
			
			//Prepare a statement to insert the user into the user table.
			$sqlQuery = $conn->prepare( "INSERT INTO tblAdminUsers (uName,pass,level) VALUES (?,?,?)" );
			$sqlQuery->bind_param( "ssi", $uName, $pass, $level );
			
			//Hash the plaintext password.
			$pass = password_hash($_POST[ 'uPass' ], PASSWORD_DEFAULT);
			
			//Bind params and execute.
			$level = $dataRow[ 'level' ];
			$sqlQuery->execute();
			
			//Remove the entry for this user in the invite table.
			$sqlQuery = $conn->prepare( "DELETE FROM tblInvites WHERE userCode = ?" );
			$sqlQuery->bind_param( "s", $uName );
			$sqlQuery->execute();
			
			//Redirect to the login page with the fromReg GET variable set.
			header( "Location:http://www.11wha.ashvillecomputing.co.uk/Project/Login.php?fromReg=$uName");
			exit();
		} else {
			
			//If the usernames don't match, then alert the user.
			$msg = "Username Invalid. Please use the same Ashville Username that your invitation was sent to.";
		}
	}
//Else, the invite has expired.
} else {
		
		$msg = "Invite Expired.";
		$expired = true;
}

//Close connections. 
$sqlQuery->close();
dbClose();
?>

<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Register</title>
	<link rel="stylesheet" type="text/css" href="main.css">
	<link rel="icon" href="../Project/Resources/favicon.png" type="image/x-icon" />
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script>
		$( document ).ready( function () {
			
			//Fade in the main body division.
			$( "#bodyDiv" ).fadeIn( 500 );

			//Array to store the text input DOM references.
			var textDom = [];
			textDom[ 0 ] = $( "#uNameText" );
			textDom[ 1 ] = $( "#uPassText" );
			textDom[ 2 ] = $( "#conPassText" );

			//If the user clicks submit, run checks.
			$( "#submitBtn" ).click( function () {

				var valid = true;
				
				//For each text input, check if they entered some text. Update CSS to reflect 
				//the state of each box (i.e. Red for invalid, Black for valid).
				for ( var i = 0; i < 3; i++ ) {
					if ( textDom[ i ].val().length == 0 ) {

						textDom[ i ].css( "borderColor", "#d81015" );
						valid = false;
					} else {
						textDom[ i ].css( "borderColor", "black" );
					}
				}
				
				//If the new password entry is less than 8 characters, alert user with CSS and a message.
				if(textDom[ 1 ].val().length < 8)
				{
					valid = false;
					$( "#info" ).html( "Password Must Be At Least 8 Characters Long!" );
					textDom[ 1 ].css( "borderColor", "#d81015" )
				}
				//Else, if the two passwords don't match, then alert user with CSS and a message.
				else if(textDom[ 1 ].val() != textDom[ 2 ].val())
				{
					valid = false;
					$( "#info" ).html( "Passwords Do Not Match!" );
					textDom[ 1 ].css( "borderColor", "#d81015" )
					textDom[ 2 ].css( "borderColor", "#d81015" )
				}
				
				//If checkes passed, submit the form. This refreshes the page.
				if ( valid ) {
					$( "#bodyDiv" ).fadeOut( 150 );
					setTimeout(
						function () {
							$( '#regForm' ).submit();
						}, 150 );

				}
			} );
		} );
	</script>
</head>

<header>
	<h1 style="padding-top: 32px;">Tracker Register!</h1>
</header>

<body>
	<div id="bodyDiv">
		<div class="loginWrap">
			<form action="../Project/Register.php?inviteCode=<?php echo $_GET['inviteCode']; ?>" method="post" id="regForm" >
				<div class = "sbsWrap">
					<input type="text" name="uName" id="uNameText" class="inText short" placeholder="USERNAME" <?php if($expired) echo "disabled"; ?>>
					<p>@ashville.co.uk</p>
				</div>
				<input type="password" name="uPass" id="uPassText" class="inText" placeholder="NEW PASSWORD" <?php if($expired) echo "disabled"; ?>>
				<input type="password" name="conPass" id="conPassText" class="inText" placeholder="RE-TYPE PASSWORD" <?php if($expired) echo "disabled"; ?>>
				<input type="hidden" name="subBtn" <?php if($expired) echo "disabled"; ?>>
			</form>
			<div class="btn <?php if($expired) echo "disabled"; ?>" id = "submitBtn">Register</div>
			<p class="msg" id="info">
				<?php echo $msg;?>
			</p>
		</div>
	</div>
</body>