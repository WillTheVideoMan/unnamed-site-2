<?php
//Start the session
session_start();

//Enable Error reporting for debug
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

//Defining the secure access parameter means that any config or functional files 
//can only be accessed via a 'require' rather than directly through URL and HTTP, improving saftey.
define( 'secureAccessParameter', true );
require_once '../Project/googleAuth/gconfig.php';
require_once '../Project/googleAuth/googleLoginHelper.php';

//If the user has not logged in, then the session variable of 'login' will not be set,
//so redirect to the Login page.
if(isset($_SESSION['stuLogin']))
{
	header( 'Location:http://11wha.ashvillecomputing.co.uk/Project/studentCard?stuCode=' . $emailSegments[0] );	
	exit();
}

//Define an array of scopes which we will use to authorise access to the users information.
$scopes = array('https://www.googleapis.com/auth/userinfo.profile' ,'https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/plus.me');

//Instantiate a GoogleLoginHelper class. This helper gives us some methods to help with the authorization proccess.
$gHelper = new GoogleLoginHelper();

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
			//Fade in the main body division.
			$( "#bodyDiv" ).fadeIn( 150 );
			
		} );
	</script>
</head>
	
<header style="padding-top: 32px;">
	<h1>Welcome, Student!</h1>
</header>

<body>
	<div id = "bodyDiv">
    	<div class = "loginWrap">
        	<a class = "center" href = "
				<?php 
					//Echo the URL created by the gHelper object into the href of the link tag.
					echo $gHelper->GetAuthURL(CLIENT_ID, CLIENT_REDIRECT_URL, $scopes);
				?>
            ">
            <img width = "350px;" src="../Project/googleAuth/btn_google_signin_dark_normal_web@2x.png" ></a>
            <p class = "msg">
				<?php 
					//If we are returning to this page because of a bad domain error, print a message to let the user know to try again.
					if(isset($_GET['badDomain'])) echo "You must use your Ashville email address! Please try again..."
				?>
            </p>
        </div>
    </div>
</body>
</html>