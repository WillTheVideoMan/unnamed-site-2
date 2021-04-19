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

//If we are requesting a logout, or if we are coming to login from the regisrtation page, then first
//destroy the session and any variables to ensure that any user that might be logged in do not remain 
//logged in. This is a major security action, to prevent shared sessions.
if ( isset( $_GET[ 'logOut' ] )or isset( $_GET[ 'fromReg' ] ) ) {
	unset( $_SESSION[ 'login' ] );
	unset( $_SESSION[ 'level' ] );
	session_destroy();

	//Update the user message.
	if ( isset( $_GET[ 'fromReg' ] ) ) {
		$msg = "Thank You For Registering - Now Login!";
	} else {
		$msg = "YOU SUCCESSFULLY LOGGED OUT";
	}
}

//If the user clicked the submit button, then begin the verification flow.
if ( isset( $_POST[ 'subBtn' ] ) ) {
	
	//Prepare a statement to select the user from the admin users table.
	$sqlQuery = $conn->prepare( "SELECT * FROM tblAdminUsers WHERE uName = ?" );
	$sqlQuery->bind_param( "s", $uName );
	
	//Bind params and execute.
	$uName = strtoupper( $_POST[ 'uName' ] );
	$sqlQuery->execute();
	
	//Get the SQL return object.
	$result = $sqlQuery->get_result();
	
	//Check if the user exists in the table
	if ( $result->num_rows > 0 ) {
		
		//Convert the SQL return objec to an accoc. array.
		$dataRow = $result->fetch_assoc();
		
		//Check if the password matches through a hashing algoirithm. 
		if ( password_verify( $_POST[ 'uPass' ], $dataRow[ 'pass' ] ) ) {
			
			//Is succesfull, update the session variables and regenerate the 
			//session id to prevent session hijacking.
			$_SESSION[ 'login' ] = $dataRow[ 'uName' ];
			$_SESSION[ 'level' ] = $dataRow[ 'level' ];
			session_regenerate_id();
			
		//Else, the password was incorrect. Update the message.
		} else {
			$msg = "INCORRECT PASSWORD.";
		}
	//Else, the username was incorrect. Update the message.
	} else {
		$msg = "INCORRECT USERNAME.";
	}
	
	//Close connections.
	$sqlQuery->close();
	dbClose();

}

//If the user is already logged in, then redirect.
if ( isset( $_SESSION[ "login" ] ) ) {
	header( 'Location:http://11wha.ashvillecomputing.co.uk/Project/Overview' );
	exit();
}

?>

<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="main.css">
	<link rel="icon" href="../Project/Resources/favicon.png" type="image/x-icon"/>
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

			//If the user clicks submit, run checks.
			$( "#submitBtn" ).click( function () {
				
				var valid = true;

				//For each text input, check if they entered some text. Update CSS to reflect 
				//the state of each box (i.e. Red for invalid, Black for valid).
				for ( var i = 0; i < 2; i++ ) {
					if ( textDom[ i ].val().length == 0 ) {

						textDom[ i ].css( "borderColor", "#d81015" );
						valid = false;
					} else {
						textDom[ i ].css( "borderColor", "black" );
					}
				}
				
				//If checkes passed, submit the form. This refreshes the page.
				if ( valid ) {
					$( "#bodyDiv" ).fadeOut( 150 );
					setTimeout(
						function () {
							$( '#loginForm' ).submit();
						}, 150 );
				}
			} );
		} );
	</script>
</head>

<header>
	<h1 style="padding-top: 32px;">Tracker Login!</h1>
</header>

<body>
	<div id="bodyDiv">
		<div class="loginWrap">
			<form action="../Project/Login.php" method="post" id="loginForm">
				<div class="sbsWrap">
					<input type="text" name="uName" id="uNameText" class="inText short" placeholder="USERNAME" value="<?php if(isset($_GET['fromReg'])) echo $_GET['fromReg']; ?>">
					<p>@ashville.co.uk</p>
				</div>
				<input type="password" name="uPass" id="uPassText" class="inText" placeholder="PASSWORD">
				<input type="hidden" name="subBtn">
			</form>
			<div class="btn" id="submitBtn">Login</div>
			<p class="msg">
				<?php echo $msg;?>
			</p>
		</div>
	</div>
</body>