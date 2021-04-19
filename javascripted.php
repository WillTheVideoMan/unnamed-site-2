<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Get Scripted</title>
<script>

window.onload = function () {
document.getElementById("btn1").addEventListener("mousedown", function () 
{ myFunction ()
document.getElementById("btn1").height = 400;
document.getElementById("btn1").width = 400;
});

document.getElementById("btn1").addEventListener("mouseup", function () {
	document.getElementById("btn1").height = 420;
    document.getElementById("btn1").width = 420;
});}

var counter = 0;

function myFunction () 
{
	counter += 1;
	document.getElementById("text").innerHTML = counter;
}
</script>


<link href="myStyle.css" rel="stylesheet" type="text/css" />
</head>
<body style = "text-align:center;">

<h1>COOCKIE CLINKER</h1>

<p id = "text">0</p>

<img src="http://cdn.shopify.com/s/files/1/0810/3853/products/NomooCookies-155_BigChipperWalnuts_grande.png?v=1449175293" id = "btn1" alt="CoonKie" height="420" width="420">

</body>
</html>