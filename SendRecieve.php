<?php 
header("Content-Type: application/json; charset=UTF-8");
include ("databaseHandler.php");
db_connect();

$recObj = json_decode($_POST["x"], false);
$sendObj = new stdClass();
$sendObj->players = array(
	'coOrds' => array(), 'colour' => '',
    'coOrds' => array(), 'colour' => '',
	'coOrds' => array(), 'colour' => '',
    'coOrds' => array(), 'colour' => '');
$sendObj->terminated = false;

$sql_statement = "SELECT COUNT(1) FROM tblLobbies WHERE lobbyId = '$recObj->lID'";
$sqlResponse = $db_link->query($sql_statement) or die($db_link->error);
$data = $sqlResponse->fetch_assoc();

if($data != 0)
{

$lobby = "l" . $recObj->lID;
$player = "p" . $recObj->pID;

$myTbl = $lobby . $player;

$sql_statement = "TRUNCATE TABLE '$myTbl'";
$db_link->query($sql_statement) or die($db_link->error);

$inArr = $recObj->myCoSend;
$sqlCoordList;

foreach($inArr as $coOrd)
{
	$sqlCoordList += "('$coOrd'),";
}

$sqlCoordList = substr($sqlCoordList, 0, -1);

$sql_statement = "INSERT INTO '$myTbl' ('coOrds') VALUES $sqlCoordList";
$db_link->query($sql_statement) or die($db_link->error);

$pTbls = array();

$sql_statement = "SELECT color FROM '$lobby' ORDER BY pId";
$sqlResponse = $db_link->query($sql_statement) or die($db_link->error);
$colours = $sqlResponse->fetch_assoc();

for($i = 1; $i <= 4; $i++)
{
	if($i != $player)
	{
		$pTbls[count($pTbls)] = $lobby . "p" . $i;
		$sendObj->players[i-1]->colour = $colours[i-1];
	}
}

for($i = 0; $i < 4; $i++)
{
	$sql_statement = "SELECT * FROM '$pTbls[i]'";
	$sqlResponse = $db_link->query($sql_statement) or die($db_link->error);
    $sendObj->players[i]->coOrds = $sqlResponse->fetch_assoc();
}
	
}
else
{
	$sendObj->terminated = true;
}

echo json_encode($sendObj);

?>


