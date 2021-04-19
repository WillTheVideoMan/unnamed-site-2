<?php
session_start();

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

define( 'secureAccessParameter', true );
require_once 'config.php';
require_once 'googleLoginHelper.php';

if(isset($_GET['code'])) {
	try {
		$gHelper = new GoogleLoginHelper();
		
		$data = $gHelper->GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $_GET['code']);

		$access_token = $data['access_token'];
		
		$user_info = $gHelper->GetUserProfileInfo($access_token);
		
		$_SESSION['login'] = json_decode($user_info, true);
		
		session_regenerate_id();
		
		header( 'Location:http://www.11wha.ashvillecomputing.co.uk/googleAuth/Landing' );	
		
	}
	catch(Exception $e) {
		echo $e->getMessage();
		exit();
	}
}
?>
