<?php
include ("databaseHandler.php");
db_connect();

$lobbyId = $_POST["l"];

$sql_statement = "SELECT playerCount FROM tblLobbies WHERE lobbyId = '$lobbyId'";
$sqlResponse = $db_link->query($sql_statement) or die($db_link->error);
$pCount = $sqlResponse->fetch_assoc();
	
	if($pCount != 4)
	{
		$lobbyName = "l" . (string)$lobbyId;
		echo $lobbyName;
		$sql_statement = "SELECT pId FROM `$lobbyName` ORDER BY pId DESC LIMIT 1";
		$sqlResponse = $db_link->query($sql_statement) or die($db_link->error);
		$currPlayer = $sqlResponse->fetch_assoc();
		
		switch($currPlayer + 1)
		{
			case 1: $sql_statement = "INSERT INTO `$lobbyName` (pId, colour) VALUES ('$currPlayer + 1', 'black')";
		            $db_link->query($sql_statement) or die($db_link->error); break;
				case 2: $sql_statement = "INSERT INTO `$lobbyName` (pId, colour) VALUES ('$currPlayer + 1','blue')";
		            $db_link->query($sql_statement) or die($db_link->error); break;
				case 3: $sql_statement = "INSERT INTO `$lobbyName` (pId, colour) VALUES ('$currPlayer + 1','red')";
		            $db_link->query($sql_statement) or die($db_link->error); break;
				case 4: $sql_statement = "INSERT INTO `$lobbyName` (pId, colour) VALUES ('$currPlayer + 1','green')";
		            $db_link->query($sql_statement) or die($db_link->error); break;
		}
		
		echo $currPlayer + 1;
	}
else
{
	echo 0;
}
			
?> 