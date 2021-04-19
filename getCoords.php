<?php 
#header("Content-Type: application/json; charset=UTF-8");
include ("databaseHandler.php");
db_connect();

$sendArr = array( "player" => array("coOrds" => array(),"colour" => '',"brush" => ''));
$otherPlayer = 1;

	$sql_statement = "SELECT coOrds FROM tblCoords ORDER BY tick";
	$response = $db_link->query($sql_statement) or die($db_link->error);
	
	if($response->num_rows>0)
	{
		$count = 0;
		while($data = $response->fetch_assoc())
		{
			$sendArr['player']['coOrds'][$count] = $data['coOrds'];
			$count = $count + 1;
		}
	}

    $sql_statement = "SELECT colour FROM tblLobby WHERE playerID = '$otherPlayer'";
	$response = $db_link->query($sql_statement) or die($db_link->error);
    $data = $response->fetch_assoc();
	
    $sendArr['player']['colour'] = $data[colour];
	
	$sql_statement = "SELECT brush FROM tblLobby WHERE playerID = '$otherPlayer'";
	$response = $db_link->query($sql_statement) or die($db_link->error);
    $data = $response->fetch_assoc();
	
    $sendArr['player']['brush'] = $data[brush];
	
	echo json_encode($sendArr);
	
	
		
?>
		
