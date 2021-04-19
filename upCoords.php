<?php 
header("Content-Type: application/json; charset=UTF-8");
include ("databaseHandler.php");
db_connect();

$recCoord = json_decode($_POST["x"],false);

$sql_trunk = "TRUNCATE TABLE tblCoords";

$inputArray = $recCoord->myCoSend;
$currColour = $recCoord->colour;
$currBrush = $recCoord->brush;
$currId = $recCoord->pId;
$sqlCoordList;

if(count($inputArray) != 0)
{
	$tick = 1;
	foreach($inputArray as $coOrd)
	{
		$sqlCoordList = $sqlCoordList . "('$coOrd', '$tick'),";
		$tick = $tick + 1;
	}

	$sqlCoordList = substr($sqlCoordList, 0, -1);
	
	echo $sqlCoordList;
    
	$db_link->query($sql_trunk) or die($db_link->error);
    $sql_statement = "INSERT INTO tblCoords (coOrds, tick) VALUES $sqlCoordList";
	$db_link->query($sql_statement) or die($db_link->error);
	
	$sql_statement = "UPDATE tblLobby SET colour = '$currColour' WHERE playerID ='$currId'";
	$db_link->query($sql_statement) or die($db_link->error);
	
	$sql_statement = "UPDATE tblLobby SET brush = '$currBrush' WHERE playerID ='$currId'";
	$db_link->query($sql_statement) or die($db_link->error);
} else
{
	$db_link->query($sql_trunk) or die($db_link->error);
	$sqlCoordList = "(0, 1)";
	echo "default:" . $sqlCoordList;
	$sql_statement = "INSERT INTO tblCoords (coOrds, tick) VALUES $sqlCoordList";
	$db_link->query($sql_statement) or die($db_link->error);
}

?>