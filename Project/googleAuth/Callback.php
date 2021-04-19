<?php
//Start the session
session_start();

//Enable Error reporting for debug
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

//Defining the secure access parameter means that any config or functional files 
//can only be accessed via a 'require' rather than directly through URL and HTTP, improving saftey.
define( 'secureAccessParameter', true );
require_once 'gconfig.php';
require_once 'googleLoginHelper.php';

//If a code has been set by Google (i.e. authorisation was succesfull), then proceed.
if(isset($_GET['code'])) {
	
	//Try the following code.
	try {
		
		//Instantiate a new googleloginhelper object.
		$gHelper = new GoogleLoginHelper();
		
		//Get the access token from the Google Servers.
		$data = $gHelper->GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $_GET['code']);

		//Extract the access token string and set to a variable.
		$access_token = $data['access_token'];
		
		//Get the user info from the Google Servers.
		$user_info = $gHelper->GetUserProfileInfo($access_token);
		
		//Set the student login session variable to equal the user info JSON decoded array.
		$_SESSION['stuLogin'] = json_decode($user_info, true);
		
		//Get the segments of the email address.
		$emailSegments = explode("@", $_SESSION['stuLogin']['email']);
		
		//If the domain is not 'ashville.co.uk', then redirect.
		if($emailSegments[1] != "ashville.co.uk")
		{
			//Redirect with the 'badDomain' GET variable set.
			header( 'Location:http://11wha.ashvillecomputing.co.uk/Project/Student?badDomain' );	
		}
		else
		{
			//Regenerate the session ID to prevent session hijack attacks.
			session_regenerate_id();
		
			//Redirect to the student card viewer page, with the student code email segement.
			header( 'Location:http://11wha.ashvillecomputing.co.uk/Project/studentCard?stuCode=' . $emailSegments[0] );	
		
		}
		
	}
	//If there was an error from one of the helper functions, then catch it and display the error.
	catch(Exception $e) {
		echo $e->getMessage();
		exit();
	}
}
?>
