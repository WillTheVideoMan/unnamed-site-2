<?php 
session_start();

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

define( 'secureAccessParameter', true );
require '../Project/Tools/config.php';
require '../Project/Tools/walkFunctions.php';

if ( !isset( $_SESSION[ 'login' ] ) ) {
	header( 'Location:http://www.11wha.ashvillecomputing.co.uk/Project/Login.php' );
	exit();
}

if ( getWalkStatus() == 0 ) {
	header( 'Location:http://www.11wha.ashvillecomputing.co.uk/Project/Launch.php' );
	exit();
}
	
$conn = dbConnect();
	
$sqlQuery = $conn->prepare( "SELECT studentCode FROM tblStudents" );
$sqlQuery->execute();

$result = $sqlQuery->get_result();

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Student Codes</title>
<link rel="stylesheet" type="text/css" href="main.css">
	
</head>

<body>
	<?php
	if($result->num_rows>0)
	{
		while($dataRow = $result->fetch_assoc())
		{?>
	<div class = "codeContainer">
	 <img src = "https://api.qrserver.com/v1/create-qr-code/?size=320x320&data=<?php echo $dataRow['studentCode'];?>" width = 320px height = 320px>
	 <p><?php echo $dataRow['studentCode'];?></p>
	</div>
	<?php }} ?>
</body>
</html>