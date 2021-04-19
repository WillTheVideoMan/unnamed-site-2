<?php
session_start();

if(isset($_SESSION['loggedIn']))
{
	$msg = "Logged In As" . $_SESSION['currUser'];
}
else
{
	$msg = "Please Log In...";
}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>User Login</title>
<link href="/myStyle.css" rel="stylesheet" type="text/css">
</head>

<body>

<p><?php echo $msg; ?></p>

<form>
	<input type = "text" placeholder = "Username" name = "uName" style = "display:block;">
	<input type = "password" placeholder = "Password" name = "Pass" style = "display:block;">
	<input type = "button" id = "btnLogIn" value = "Login!">
</form>

<p>Or Sign Up Below:</p>

<form>
	<input type = "text" placeholder = "Username" name = "uNameS" style = "display:block;">
	<input type = "password" placeholder = "Password" name = "PassS" style = "display:block;">
	<input type = "button" id = "btnSignUp" value = "Sign Up!">
</form>

</body>

<script>
	
	window.onload = function () 
	{
		document.getElementById("btnLogIn").addEventListener('click', function()
		{
			verifyLogin();
		});
		
		document.getElementById("btnSignUp").addEventListener('click', function()
		{
			addUser();
		});
	}
	
	function verifyLogin ()
	{
		var uName = document.getElementById("uName").innerHTML;
		var password = document.getElementById("Pass").innerHTML;
		var preObj = {uName:uName, pass:password};
		var request = new XMLHttpRequest();
		
		request.open("POST", "verifyCreds.php", true);
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send("creds=" + preObj.stringify);
		
		request.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) 
			{
			    if(this.responseText == "valid")
					{
						
					}
 			}
		}
	}
	
	function addUser ()
	{
		var uName = document.getElementById("uNameS").innerHTML;
		var password = document.getElementById("PassS").innerHTML;
		var preObj = {uName:uName, pass:password};
		var request = new XMLHttpRequest();
		
		request.open("POST", "newUser.php", true);
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send("creds=" + preObj.stringify);
		
		request.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) 
			{
			    if(this.responseText == "valid")
					{
						
					}
 			}
		}
	}
	
</script>

</html>