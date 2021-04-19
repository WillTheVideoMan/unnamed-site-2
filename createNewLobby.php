<?php 
include ("databaseHandler.php");
db_connect();

$nLobbyName = $_POST["lname"];

$sql_statement = "INSERT INTO tblLobbies (lobbyName) VALUES ('dad')";
$db_link->query($sql_statement) or die($db_link->error);

$sql_statement = "SELECT studentID FROM tblStudents ORDER BY studentID DESC LIMIT 1";
$response = $db_link->query($sql_statement) or die($db_link->error);
$data = $response->fetch_assoc();

echo $data;

$nLobbyTbl = "l" . $data;

$sql_statement = "CREATE TABLE  `11wha`.`$nLobbyTbl` (
					`pId` INT NOT NULL ,
					`colour` VARCHAR( 64 ) NOT NULL ,
					PRIMARY KEY (  `pId` )
					) ENGINE = INNODB;";

$db_link->query($sql_statement) or die($db_link->error);

for($i = 1; $i <= 4; $i++)
{
	$currName = $nLobbyTbl . "p" . $i;
	
	$sql_statement = "CREATE TABLE  `11wha`.`$currName` (
					`coOrds` INT NOT NULL,
					PRIMARY KEY (  `coOrds` )
					) ENGINE = INNODB;";
}
$db_link->query($sql_statement) or die($db_link->error);

#echo $nLobbyTbl;

?>