<?php
define('secureAccessParameter', true);
require '../../TheDenDesigns/Tools/config.php';

session_start();

$msg;

//Debug Section to be removed
if(isset($_SESSION['login']))
{
	$msg = "debug: logged in";
}
else
{
	$msg = "debug: not logged in";
}
//End Section

if(isset($_GET['logOut']))
{
	unset($_SESSION['login']);
	session_destroy();
	$msg = "YOU SUCCESSFULLY LOGGED OUT";
}	

if(isset($_POST['subBtn']))
{
	if($_POST['uName'] == adminU && password_verify($_POST['uPass'], adminP))
	{
		if(verifyTFA($_POST['uAuth']) == true)
		{
			$_SESSION['login'] = true;
			header('Location: http://www.11wha.ashvillecomputing.co.uk/TheDenDesigns/Admin/Manage.php');
			die();
		}
		else
		{
			$msg = "INCORRECT AUTH CODE.";
		}
	}
	else
	{
		$msg = "INCORRECT USERNAME / PASSWORD.";
	}
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Login</title>
<link href="https://fonts.googleapis.com/css?family=Cabin" rel="stylesheet">
<link href="/TheDenDesigns/Admin/CSS/Admin.css" rel="stylesheet" type="text/css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="../../TheDenDesigns/Images/lock.ico">
<script>
	window.onload = function()
	{
		var textDom = Array(3);
		textDom[0] = document.getElementById("uNameText");
		textDom[1] = document.getElementById("uPassText");
		textDom[2] = document.getElementById("uAuthText");
		
		document.getElementById("subBtn").addEventListener('click', function()
		{
			var valid = true;
			
			for(var i = 0; i < 3; i++)
			{
			if(textDom[i].value.length == 0)
				{
					textDom[i].style.borderColor = "red";
					valid = false;
				} 
			else
				{
					textDom[i].style.borderColor = "black";
				}
			}
			
			if(valid)
				{
					document.getElementById("loginForm").submit();
				}
		});
	}
</script>
</head>

<body>
<div class = "header">ADMIN LOGIN</div>

<form action = "/TheDenDesigns/Admin/Login.php" method = "post" id = "loginForm">
	<input type = "text" name = "uName" id = "uNameText" class = "inText" placeholder = "USERNAME">
	<input type = "password" name = "uPass" id = "uPassText" class = "inText" placeholder = "PASSWORD">
	<input type = "text" name = "uAuth" id = "uAuthText" class = "inText" placeholder = "000000">
	<input type = "hidden" name = "subBtn">
</form>

<div class = "inText submitBtn" id = "subBtn">LOG IN</div>

<p class = "msg"><?php echo $msg;?></p>

</body>
</html>