<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AJAX Tester</title>

<script>

window.onload = function() {
document.getElementById("btn1").addEventListener("click", function() {
	makeRequest('ajaxInfo.php', 'text');}) }

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

</script>
<link href="myStyle.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id = "btn1" class = "btnStatic"><p>Click Me...</p></div>
<p id = "text"></p>
</body>
</html>