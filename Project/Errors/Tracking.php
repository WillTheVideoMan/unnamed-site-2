<?php
session_start();

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

define( 'secureAccessParameter', true );
require '../Project/Tools/config.php';
require '../Project/Tools/walkFunctions.php';

if ( !isset( $_SESSION[ 'login' ] ) ) {
	header( 'Location:http://www.11wha.ashvillecomputing.co.uk/Project/Login' );
	exit();
}

if(getWalkStatus() != 2)
{
	header( 'Location:http://www.11wha.ashvillecomputing.co.uk/Project/Overview' );
	exit();
}
?>

<!DOCTYPE html">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<link rel="stylesheet" type="text/css" href="main.css">
	<link rel="icon" href="../Project/Resources/favicon.png" type="image/x-icon" />
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<header>
	<h1 style="padding-top: 32px;">Tracker Login!</h1>
</header>

<body>
	<div id="bodyDiv">
      <h1>Oh Boy - Looks Nothing Is Here...</h1>
	</div>
</body>