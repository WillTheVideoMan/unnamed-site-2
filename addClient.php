<?php 
session_start();
include ("databaseHandler.php");
db_connect();

$currID = session_id();

    $sql_statement1 = "SELECT COUNT FROM tblCurrClients WHERE clientID = '$currID'";
    $queryResults = $db_link->query($sql_statement1) or die($db_link->error);

if($queryResults->fetch_assoc()==0) {
    $sql_statement2 = "INSERT INTO tblCurrClients (clientID) VALUES ('$currID')";
    $db_link->query($sql_statement2) or die($db_link->error); }
?>