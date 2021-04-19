<?php 
session_start();
include ("databaseHandler.php");
db_connect();

$currID = session_id();

    $sql_statement2 = "DELETE FROM tblCurrClients WHERE clientID = '$currID'";
    $db_link->query($sql_statement2) or die($db_link->error);

session_destroy();
echo "Terminated";
?>