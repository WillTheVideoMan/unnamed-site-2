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

if($data->num_rows == 0)
{
	$passHash = password_hash($pass, PASSWORD_DEFAULT);
	
	$sql_statement = "INSERT INTO tblUsers (username, password) VALUES ('$uName', '$passHash')";
	$db_link->query($sql_statement) or die($db_link->error);
	
	echo "valid";
}
else
{
	echo "Error - Username is already taken...";
}
?>