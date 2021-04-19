<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Welcome Client!</title>

<script>
	
	var counter = 0;
	
	window.onload = function() {
		makeRequest('addClient.php', 'textID');
		
document.getElementById("btn1").addEventListener("mousedown", function () 
       { myFunction ()
        document.getElementById("btn1").height = 400;
document.getElementById("btn1").width = 400;
});

document.getElementById("btn1").addEventListener("mouseup", function () {
	document.getElementById("btn1").height = 420;
    document.getElementById("btn1").width = 420;
	
	document.getElementById("btn2").addEventListener("click", function () {
		var name = document.getElementById("nameIn").innerHTML;
		upRequest('upCliName.php', name);
	})
});
	
	var countLoop = window.setInterval(upRequest('upCliCounter.php', counter), 1000);
	}
	
	function myFunction () 
{
	counter += 1;
	document.getElementById("text").innerHTML = counter;
}
	function makeRequest(target, objID)
{
	alert("ALAX triggered");
	var xHtml = new XMLHttpRequest();
	var obj = document.getElementById(objID);
	xHtml.open("GET", target);
	xHtml.onreadystatechange = function() {
		if(xHtml.readyState == 4 && xHtml.status == 200) {
			obj.innerHTML = "Your Client ID: " + xHtml.responseText;
		}
	}
	xHtml.send(null);
}
	
	function upRequest(target, value)
{
	var xHtml = new XMLHttpRequest();
	xHtml.open("GET", target+"?value="+value);
	xHtml.send(null);
}
	
</script>

</head>

<body>
<p id = "textID"></p>

<input type="text" id = "nameIn">
<div id = "btn2"><p>Update</p></div>

<h1>COOCKIE CLINKER</h1>

<p id = "text">0</p>

<img src="http://cdn.shopify.com/s/files/1/0810/3853/products/NomooCookies-155_BigChipperWalnuts_grande.png?v=1449175293" id = "btn1" alt="CoonKie" height="420" width="420">
</body>
</html>