<?php 
header("Content-Type: application/json; charset=UTF-8");
include ("databaseHandler.php");
db_connect();

$credsObj = json_decode($_POST["creds"],false);
$uName = $credsObj->uName;
$pass = $credsObj->Pass;

$sql_statement = "SELECT * FROM tblUsers WHERE username = '$uName'";
$response = $db_link->query($sql_statement) or die($db_link->error);
$data = $response->fetch_assoc();

if($data->num_rows != 0)
{
	if(password_verify($pass, $data['password']))
	{
		echo "valid";
	}
	else
	{
		echo "Password Error";
	}
}
else
{
	echo "Username Error";
}

?>