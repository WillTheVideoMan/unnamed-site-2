<?php 
include ("databaseHandler.php");
db_connect();

     $value = $_REQUEST[value];

     $sql_statement = "UPDATE tblCurrClients SET banned = 1 WHERE clientID =         '$value'";
     $db_link->query($sql_statement) or die($db_link->error);
	 echo "BANNED CLIENT: " . $value;
?>