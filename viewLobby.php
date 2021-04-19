<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Welcome Client!</title>
<link href="/myStyle.css" rel="stylesheet" type="text/css">
<script>
	
	var counter = 0;
	
	window.onload = function() {
	var loop = window.setInterval(function () {makeRequest('printTable.php', 'table');}, 250);
	}
	
	function makeRequest(target, objID)
{
	var xHtml = new XMLHttpRequest();
	var obj = document.getElementById(objID);
	xHtml.open("GET", target);
	xHtml.onreadystatechange = function() {
		if(xHtml.readyState == 4 && xHtml.status == 200) {
			obj.innerHTML = xHtml.responseText;
		}
	}
	xHtml.send(null);
}

function banClient (banID) 
{
	makeRequest('banClient.php?value=' + banID, 'log');
}
	
</script>

</head>

<body style = "text-align:center;" id = "body">
<h1>Leaderboard!</h1>

<div id = "table"></div>
<p id = "log"></p>

</body>
</html>