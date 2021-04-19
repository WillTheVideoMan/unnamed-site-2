<?php
//Start the session
session_start();

//Enable Error reporting for debug
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

//Unset the student login details.
unset($_SESSION['stuLogin']);

//Destroy the session, so that session login checks will return false.
session_destroy();

//Redirect to the student login page.
header( 'Location:http://www.11wha.ashvillecomputing.co.uk/Project/Student' );
		
?>
