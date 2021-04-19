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
require '../../Project/Tools/PHPMailer/src/Exception.php';
require '../../Project/Tools/PHPMailer/src/PHPMailer.php';
require '../../Project/Tools/PHPMailer/src/SMTP.php';

//Create an alias to the PHP mailer namespace.
use PHPMailer\ PHPMailer\ PHPMailer;
use PHPMailer\ PHPMailer\ Exception;

//Make an accociative array to store our return object.
$returnJSON = array( "status" => "", "message" => "" );

//If the user is logged in, and the user is high level, and the PHP POST params have been set.
if ( isset( $_SESSION[ 'login' ] ) && $_SESSION[ 'level' ] == 1 && isset( $_POST[ 'newUname' ] ) && isset( $_POST[ 'newLevel' ] ) ) {

	//Create a new database connection. The dbConnect fuction is defined in the config file.
	$conn = dbConnect();

	//Set variables to contain the POST params.
	$newUname = strtoupper( $_POST[ 'newUname' ] );
	$newLevel = $_POST[ 'newLevel' ];

	//Define a boolean to flag if the user already exists.
	$exists = false;

	//Prepare a statment to select all the usernames from the current admin user table.
	$sqlQuery = $conn->prepare( "SELECT uName FROM tblAdminUsers" );

	//Execute the statment.
	$sqlQuery->execute();

	//Fetch the SQL return object.
	$result = $sqlQuery->get_result();

	//If there is more than one returned row, then...
	if ( $result->num_rows > 0 ) {

		//While a new row can be read, loop.
		while ( $dataRow = $result->fetch_assoc() ) {

			//If the username we are trying to invite already exists in the user table, then set $exists to true;
			if ( $dataRow[ 'uName' ] == $newUname ) {

				$exists = true;
			}
		}
	}

	//If the user does not already exist, then...
	if ( !$exists ) {

		//Declare an empty variable to hold our invite code.
		$inviteCode = "";

		//Declare an array of characters to use in the invite code generation.
		$characters = str_split( "0123456789abcdefghijklmnopqrstuvwxyz" );

		//Select 10 characters at random and append them to the empty variable.
		for ( $i = 0; $i < 10; $i++ )$inviteCode .= $characters[ mt_rand( 0, count( $characters ) - 1 ) ];

		//Declare a variable to hold the HTML email we will send with the invite instructions and 
		//links to the registration page.
		$txt = '
<html>
<head>
<title>HTML email</title>
</head>
<body>
<h2>You Have Been Invited To Access The Sponsored Walk Tracker!</h2>
<br>
<p>Dear ' . $newUname . ',</p>
<br>
<p>You have been invited to access the sponsored walk tracking suite. From here, you can track the progress of the sponsored walk, and view key stastistics. To register, please follow the link below:</p>
<a href="http://11wha.ashvillecomputing.co.uk/Project/Register.php?inviteCode=' . $inviteCode . '">Click To Register!</a>
<br>
<p>Best Regards,</p>
<p>Sponsored Walk Admin</p>
<br>
<p>If your having problems with the button above, Copy and paste this URL: http://11wha.ashvillecomputing.co.uk/Project/Register.php?inviteCode=' . $inviteCode . '</p>
</body>
</html>';

		//Instantiate a new instance of the PHP mailer class. This is an API to send emails 
		//through a simple PHP server.
		$mail = new PHPMailer( true );

		//Try to send the email - since there are so many things that could go wrong...
		try {

			//Declare the parameters for the email, such as the email of the new user, and the 
			//subject line and body of the email.
			$mail->setFrom( 'sponsoredWalkTracking@ashville.co.uk', 'Tracking Invite' );
			$mail->addAddress( "$newUname@ashville.co.uk", $newUname );
			$mail->isHTML( true );
			$mail->Subject = "Invite To Access Sponsored Walk Tracking";
			$mail->Body = $txt;

			//Send the email.
			$mail->send();

			//Prepare a statment to insert this new invite code into the database along with the
			//new user code and the level of access they will have.
			$sqlQuery = $conn->prepare( "INSERT INTO tblInvites (inviteID, userCode, level) VALUES (?,?,?)" );

			//Bind the params to the query.
			$sqlQuery->bind_param( "ssi", $inviteCode, $newUname, $newLevel );

			//Execute the statment.
			$sqlQuery->execute();

			//Write messages to validate the success of the operation.
			$returnJSON[ 'status' ] = "OK";
			$returnJSON[ 'message' ] = "Complete!";

			//If an error is thrown, then let the user know what went wrong.
		} catch ( Exception $e ) {

			//Write error messages.
			$returnJSON[ 'status' ] = "ERROR";
			$returnJSON[ 'message' ] = "Mailer Error: " . $mail->ErrorInfo;
		}

	}
	//Else, the user exists.
	else {
		//Write error messages.
		$returnJSON[ 'status' ] = "ERROR";
		$returnJSON[ 'message' ] = "The user you are trying to invite already exists!";
	}

	//Close the SQL query.
	$sqlQuery->close();

	//Close the datbase connection.
	dbClose();


	//Else, if the conditions for operation are not met, then give an error and exit.
} else {
	//Write error messages.
	$returnJSON[ 'status' ] = "ERROR";
	$returnJSON[ 'message' ] = "Access Denied!";
}

//Return the JSON response.
echo json_encode( $returnJSON );
?>