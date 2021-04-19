<?php
session_start();

$msg;

if(isset($_SESSION['login']))
{
	unset($_SESSION['login']);
	$msg = "Logged Out Sucessfully!";
}
else
{
	$msg = "Hmm. There was no-one logged in at this time...";
}
	
session_destroy();
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>You Logged Out!</title>
</head>

<body>

<p><?php echo $msg; ?></p>

</body>
</html>