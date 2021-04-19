<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Meme Browser</title>
<link href="myStyle.css" rel="stylesheet" type="text/css">

</head>
<body>
<div class = "" id = "wrapper">
<div>
	<h1>Choose Your Meme</h1>

	    <img class = "imgCircle" id = "green" src="images/GABEN.png" alt="GABEN">
				
		<img class = "imgCircle" id = "blue" src="images/Frasier_Logo.JPG" alt="FRAZEIR"> 
		
		<img class = "imgCircle" id = "red" src="images/Wesser.jpeg" alt="Wesser">
		
		</div>
		
		<div class = "divText"><p id = "textHere"></p></div>

		<a href = "index.php"><div class ="btnSpread"><p>HOME</p></div></a>	
        </div>
</body>

<script>
	var greenImgCircle = document.querySelector("#green");
	var blueImgCircle = document.querySelector("#blue");
	var redImgCircle = document.querySelector("#red");
	var wrapperDiv = document.querySelector("#wrapper");
	
	if (localStorage.getItem("selectedTheme") == "theme1")
		{
			wrapperDiv.classList.remove("wrapTheme0");
			wrapperDiv.classList.remove("wrapTheme2");
			wrapperDiv.classList.remove("wrapTheme3");
			wrapperDiv.classList.add("wrapTheme1");
			greenImgCircle.classList.toggle("imgCircleSelected");
		    blueImgCircle.classList.remove("imgCircleSelected");
		    redImgCircle.classList.remove("imgCircleSelected");
			document.getElementById('textHere').innerHTML = "GABEN";
		}
	
	if (localStorage.getItem("selectedTheme") == "theme2")
		{
			wrapperDiv.classList.remove("wrapTheme0");
			wrapperDiv.classList.remove("wrapTheme1");
			wrapperDiv.classList.remove("wrapTheme3");
			wrapperDiv.classList.add("wrapTheme2");
			blueImgCircle.classList.toggle("imgCircleSelected");
			greenImgCircle.classList.remove("imgCircleSelected");
			redImgCircle.classList.remove("imgCircleSelected");
			document.getElementById('textHere').innerHTML = "FRASIER";
		}
	
	if (localStorage.getItem("selectedTheme") == "theme3")
		{
			wrapperDiv.classList.remove("wrapTheme0");
			wrapperDiv.classList.remove("wrapTheme2");
			wrapperDiv.classList.remove("wrapTheme1");
			wrapperDiv.classList.add("wrapTheme3");
			redImgCircle.classList.toggle("imgCircleSelected");
			blueImgCircle.classList.remove("imgCircleSelected");
			greenImgCircle.classList.remove("imgCircleSelected");
			document.getElementById('textHere').innerHTML = "MORGAN FREEMAN";
		}
	
	if (localStorage.getItem("selectedTheme") == "theme0")
		{
			wrapperDiv.classList.remove("wrapTheme1");
			wrapperDiv.classList.remove("wrapTheme2");
			wrapperDiv.classList.remove("wrapTheme3");
			wrapperDiv.classList.add("wrapTheme0");
			document.getElementById('textHere').innerHTML = "";
			
		}
	
    document.getElementById('green').addEventListener('click', function () {
		if(document.getElementById('textHere').innerHTML != "GABEN")
		{
			document.getElementById('textHere').innerHTML = "GABEN";
			localStorage.selectedTheme = "theme1";
			wrapperDiv.classList.remove("wrapTheme0");
			wrapperDiv.classList.remove("wrapTheme2");
			wrapperDiv.classList.remove("wrapTheme3");
			wrapperDiv.classList.add("wrapTheme1");
		} else {
			document.getElementById('textHere').innerHTML = "";
			localStorage.selectedTheme = "theme0";
			wrapperDiv.classList.remove("wrapTheme1");
			wrapperDiv.classList.remove("wrapTheme2");
			wrapperDiv.classList.remove("wrapTheme3");
			wrapperDiv.classList.add("wrapTheme0");
		}
		greenImgCircle.classList.toggle("imgCircleSelected");
		blueImgCircle.classList.remove("imgCircleSelected");
		redImgCircle.classList.remove("imgCircleSelected");
	}, false);
													  
    document.getElementById('blue').addEventListener('click', function () {
		if(document.getElementById('textHere').innerHTML != "FRASIER")
		{
			document.getElementById('textHere').innerHTML = "FRASIER";
			localStorage.selectedTheme = "theme2";
			wrapperDiv.classList.remove("wrapTheme0");
			wrapperDiv.classList.remove("wrapTheme1");
			wrapperDiv.classList.remove("wrapTheme3");
			wrapperDiv.classList.add("wrapTheme2");
			
		} else {
			document.getElementById('textHere').innerHTML = "";
			localStorage.selectedTheme = "theme0";
			wrapperDiv.classList.remove("wrapTheme1");
			wrapperDiv.classList.remove("wrapTheme2");
			wrapperDiv.classList.remove("wrapTheme3");
			wrapperDiv.classList.add("wrapTheme0");
		}
		blueImgCircle.classList.toggle("imgCircleSelected");
		greenImgCircle.classList.remove("imgCircleSelected");
		redImgCircle.classList.remove("imgCircleSelected");
	}, false);    
													 
	document.getElementById('red').addEventListener('click', function () { 
        if(document.getElementById('textHere').innerHTML != "MORGAN FREEMAN")
		{
			document.getElementById('textHere').innerHTML = "MORGAN FREEMAN";
			localStorage.selectedTheme = "theme3";
			wrapperDiv.classList.remove("wrapTheme0");
			wrapperDiv.classList.remove("wrapTheme2");
			wrapperDiv.classList.remove("wrapTheme1");
			wrapperDiv.classList.add("wrapTheme3");
		} else {
			document.getElementById('textHere').innerHTML = "";
			localStorage.selectedTheme = "theme0";
			wrapperDiv.classList.remove("wrapTheme1");
			wrapperDiv.classList.remove("wrapTheme2");
			wrapperDiv.classList.remove("wrapTheme3");
			wrapperDiv.classList.add("wrapTheme0");
		}
		redImgCircle.classList.toggle("imgCircleSelected");
		blueImgCircle.classList.remove("imgCircleSelected");
		greenImgCircle.classList.remove("imgCircleSelected");
	}, false);	
	

	
		
</script>

</html>