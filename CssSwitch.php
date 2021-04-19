<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link id="currCss" href="" rel="stylesheet" type="text/css"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CssSwitch</title>

<script>

window.onload = function() {
document.getElementById('css1').addEventListener('click', function () {changeCss('1', true)});
document.getElementById('css2').addEventListener('click', function () {changeCss('2', true)});

if (localStorage.getItem("currCss") === null) {
  	localStorage.setItem("currCss", 1);
    }
	
changeCss(localStorage.getItem("currCss"));
}


function changeCss (css, trans) 
{
	var newCss;
	var bodySelect = document.querySelector("#body");
	switch(css)
	{
		case'1':newCss = "Css1.css";break;
		case'2':newCss = "Css2.css";break;
		default:alert("Error - Selection Failed!");break;
	}
	localStorage.setItem("currCss", css);
	
	if(trans == true)
	{
		//bodySelect.classList.add("trans");
		document.getElementById('currCss').href = newCss;
		//bodySelect.classList.remove("trans");
	} else 
	{
		document.getElementById('currCss').href = newCss;
	}
	
	
}
</script>

</head>

<body id = "body" class = "trans">
<input name="css1" type="button" id = "css1" value = "CSS1"/>
<input name="css2" type="button" id = "css2" value = "CSS2"/>

<div class = "divText" style = "width:400px;height:150px;font-size:16px;">
</a><p>Maths Functions Below Now!</p>
    <button onclick="doMaths('add')">Add</button>
    <button onclick="doMaths('sub')">Sub</button>
    <button onclick="doMaths('mul')">Mul</button>
    <button onclick="doMaths('div')">Div</button>
    <br/>
    <br/>Enter first number:
    <input type="text" id="txt1" name="text1">
    <br> Enter second number:
    <input type="text" id="txt2" name="text2">
    <script>
      function doMaths(choice) {
        var x;
		var y = document.getElementById("txt1").value;
        var z = document.getElementById("txt2").value;
		  
		if	(choice == "add") {var x = +y + +z; }
        if	(choice == "sub") {var x = +y - +z; }
		if	(choice == "mul") {var x = +y * +z; }
		if	(choice == "div") {var x = +y / +z; }
		  
		if (isNaN(x) )
		{
			x = "ERROR - NUMBERS ONLY";
		}
		
        document.getElementById("mathsBox").innerHTML = x;
      }
    </script>
 
    <p id="mathsBox"></p>
</div>

</body>

</html>