<?php
 //Enable Error reporting for debug
 error_reporting( E_ALL );
 ini_set( 'display_errors', 1 );
	if(isset($_GET['string']))
	{
		$string = $_GET['string'];
		$chars = str_split($string);
		$inverseString = "";
		
		for($i = (count($chars) - 1); $i > -1; $i--) $inverseString .= $chars[$i];
		
		sleep(5);
		
		echo hash('sha512', $string);
		echo hash('sha512', $inverseString);
		
	}
?>
