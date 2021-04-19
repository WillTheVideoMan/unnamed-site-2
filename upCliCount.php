<?php 
session_start();
include ("databaseHandler.php");
db_connect();

$currID = session_id();
$value = $_REQUEST[value];

$sql_statement1 = "SELECT banned FROM tblCurrClients WHERE clientID = '$currID'";
$query_results1 = $db_link->query($sql_statement1) or die($db_link->error);
$data_row = $query_results1->fetch_assoc();
if ($data_row [banned] == 1) 
{
	 $sql_statement2 = "DELETE FROM tblCurrClients WHERE clientID = '$currID'";
     $db_link->query($sql_statement2) or die($db_link->error);
	 session_destroy();
	 echo "Banned!";
} else {

     $sql_statement = "UPDATE tblCurrClients SET count = '$value' WHERE clientID =     '$currID'";
     $db_link->query($sql_statement) or die($db_link->error);
}

echo password_hash('test', PASSWORD_DEFAULT);

?>