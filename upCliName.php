<?php 
session_start();
include ("databaseHandler.php");
db_connect();

$currID = session_id();

    $sql_statement1 = "UPDATE tblCurrClients SET nickName = '$_REQUEST[value]' WHERE clientID = '$currID'";
    $db_link->query($sql_statement1) or die($db_link->error);
	
	echo $_REQUEST[value];
?>