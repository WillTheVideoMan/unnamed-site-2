<!DOCTYPE html>
<head>
<link href="myStyle.css" rel="stylesheet" type="text/css">
<title>My Cool and Edgy Website</title>
</head>
<html>
<body>
<div class = "" id = "wrapper">
<h1>Welcome to my dank website</h1>

<p>***BROWSE AT YOUR OWN RISK***</p>

<a href="http://www.ashvillecomputing.co.uk/">
  <img src="images/FRASIERZ.jpg" alt="Back Home" style="width:110px;height:110px;border:0;">
  
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
  <form method = "LINK" action="memeBrowser.php">
  <button>Click For Memes...</button>
</form>
    <form method = "LINK" action="MyDatabaseViewer.php">
  <button>Secret Database...</button>
</form>
    <form method = "LINK" action="ClientLanding.php">
  <button>Coockie Clinker</button>
</form>
   <form method = "LINK" action="ViewGrid.php">
  <button>Pic-Shun-Rhys</button>
</form>
   <a href="">
  <img src="images/Capture.PNG" alt="New Site" width="105" height="101" style="border:0;">
</a>

<p>Below is the youtube player section:</p>
<br></br>

<form action="index.php">
  Video URL:<br>
  <input type="text" name="URL">
  <button>GO!</button>
</form>

<p></p>

<?php
$longURL = $_GET[URL];
if (substr($longURL, 0, 32) == "https://www.youtube.com/watch?v=" ) 
{
	$urlCode = substr($longURL, 32, 11);
}
?>

<iframe width="640" height="360" src="https://www.youtube.com/embed/<?php echo $urlCode; ?>?autoplay=1
&controls=0&
showinfo=0&
modestbranding=1
&loop=1&iv_load_policy=3&playlist=<?php echo $urlCode; ?>" frameborder="0" allowfullscreen></iframe>

</div>

</body>
<script>
	
	if (localStorage.getItem("selectedTheme") === null) {
  	localStorage.setItem("selectedTheme", "theme0");
    }
	
var wrapperDiv = document.querySelector("#wrapper");
	
	if (localStorage.getItem("selectedTheme") == "theme1")
		{
			wrapperDiv.classList.remove("wrapTheme0");
			wrapperDiv.classList.remove("wrapTheme2");
			wrapperDiv.classList.remove("wrapTheme3");
			wrapperDiv.classList.add("wrapTheme1");
		}
	
	if (localStorage.getItem("selectedTheme") == "theme2")
		{
			wrapperDiv.classList.remove("wrapTheme0");
			wrapperDiv.classList.remove("wrapTheme1");
			wrapperDiv.classList.remove("wrapTheme3");
			wrapperDiv.classList.add("wrapTheme2");
		}
	
	if (localStorage.getItem("selectedTheme") == "theme3")
		{
			wrapperDiv.classList.remove("wrapTheme0");
			wrapperDiv.classList.remove("wrapTheme2");
			wrapperDiv.classList.remove("wrapTheme1");
			wrapperDiv.classList.add("wrapTheme3");
		}
	
	if (localStorage.getItem("selectedTheme") == "theme0")
		{
			wrapperDiv.classList.remove("wrapTheme1");
			wrapperDiv.classList.remove("wrapTheme2");
			wrapperDiv.classList.remove("wrapTheme3");
			wrapperDiv.classList.add("wrapTheme0");
		}
	
</script>
</html>