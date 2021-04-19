<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Welcome Client!</title>
<link href="/myStyle.css" rel="stylesheet" type="text/css">
<script>
	
	var counter = 0;
	var banned = false;
	
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
	
});

document.getElementById("btn2").addEventListener("click", function () {
	var name = document.getElementById("nameIn").value;
	makeRequest('upCliName.php?value=' + name, 'name');
});

document.getElementById("btn3").addEventListener("click", function () {
	makeRequest('removeClient.php', 'textID');
});
	var loop = window.setInterval(function () {
	   if(document.getElementById("textID").innerHTML == "Banned!") 
	    {
		   banned = true;
		   document.getElementById("body").style.fontSize = '100px';
		   document.getElementById("body").innerHTML = "You're A Socialist    Crank!";
		}
		if (!banned) 
		{
		   makeRequest('upCliCount.php?value='+counter, 'textID');
		}
	}, 500);
}

window.onbeforeunload = closingCode;

function closingCode(){
   makeRequest('removeClient.php', 'textID');
   return false;
}
    
	
	function myFunction () 
{
	counter += 1;
	document.getElementById("text").innerHTML = counter;
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
	
</script>

</head>

<body style = "text-align:center;" id = "body">

<p id = "textID"></p>

<input type="text" id = "nameIn">
<div id = "btn2" class = "btnStatic"><p>Update</p></div>

<p id = "name">&nbsp;</p>

<h1>COOCKIE CLINKER</h1>

<p id = "text">0</p>

<img src="http://cdn.shopify.com/s/files/1/0810/3853/products/NomooCookies-155_BigChipperWalnuts_grande.png?v=1449175293" id = "btn1" alt="CoonKie" height="420" width="420">

<div id = "btn3" class = "btnStatic"><p>LEAVE</p></div>
</body>
</html>