<?php
session_start();

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

if ( !isset( $_SESSION[ 'login' ] ) ) {
	header( 'Location:http://www.11wha.ashvillecomputing.co.uk/googleAuth/Login' );
	exit();
}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Google Account Details</title>
<link rel="icon" href="favicon.ico" type="image/x-icon" />
</head>

<body>
	<p><?php echo "WELCOME: " . $_SESSION['login']['name'];?></p>
    <p><?php
	$email = explode("@",$_SESSION['login']['email']);
	echo "CODE: " . $email[0]; 
	?></p>
	<p><?php echo "User ID: " . $_SESSION['login']['id'];?></p>
	<img src = "<?php echo $_SESSION['login']['picture'] ?>" width = "150px">
	<a href = "/googleAuth/LogOut.php">Log Out</a>
</body>
</html>