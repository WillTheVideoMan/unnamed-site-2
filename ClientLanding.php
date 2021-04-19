<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Client Landing</title>
<link href="/myStyle.css" rel="stylesheet" type="text/css">
<script>
	window.onload = function () 
	{
		document.getElementById("btn1").addEventListener("click", function () 
		{
			window.open("joinLobby.php", "_self")
		})
		
		document.getElementById("btn2").addEventListener("click", function () 
		{
			window.open("viewLobby.php", "_self")
		})
	}
	
</script>

</head>
<body>

<div class = "btnStatic" id = "btn1"><p>Join</p></div>
<div class = "btnStatic" id = "btn2"><p>Host</p></div>

</body>
</html>