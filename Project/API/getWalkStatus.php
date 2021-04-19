<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );
	
define( 'secureAccessParameter', true );
require '../../Project/Tools/config.php';
require '../../Project/Tools/walkFunctions.php';

echo getWalkStatus();
?>