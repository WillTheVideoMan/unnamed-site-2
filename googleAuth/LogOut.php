<?php
session_start();

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

unset($_SESSION['login']);
session_destroy();

header( 'Location:http://www.11wha.ashvillecomputing.co.uk/googleAuth/Login' );
		
?>
