<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GriddyThingy</title>
<link href="cssGrid.css" rel="stylesheet" type="text/css"/>
<link href="menuMeme.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="menuMemeScript.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body id = "body">

<h1>The Drawing Area!</h1>

<div style = "width:800px;">
<table width="800" height="800">
<?php $count = 0 ?>
<?php for($i = 1; $i < 81; $i++){ ?>
     <tr>
<?php for($j = 1; $j < 81; $j++){ $count = $count + 1; ?>
		<td id = "td<?php echo ($count) ?>" onMouseOver="paintMe(<?php echo ($count) ?>);"></td>
<?php } ?>
     </tr>
<?php } ?>

</table>
</div>

<div id = "colCont" style = "width:800px;">
<table width="800" height="20">

<tr>
	<td id = "colBlack" class = "colours" style = "background-color:black;"></td>
	<td id = "colBlue" class = "colours" style = "background-color:blue"></td>
	<td id = "colRed" class = "colours" style = "background-color:red;"></td>
	<td id = "colGreen" class = "colours" style = "background-color:green;"></td>
    <td id = "erase" class = "colours" style = "background-color:white;"></td>
</tr>
</table>
</div>

<div style = "width:800px;">
<input id="brushSize" type="range" min="1" max="5" step="1" style = "width:400px;display:inline-block;" value = "1"/>
<p id = "text" style = "display:inline-block;"></p>
</div>

<div class = "settingsTrig" id = "setTrig">
	<img src = "http://iconshow.me/media/images/Mixed/line-icon/png/512/settings-512.png" alt = "c" height = "45px"  width = "45px">
</div>

	<div class = "sideMenu" id = "sMenu">
		
		<ul>
			<li><h1>Brush Options</h1></li>
			<li>
				<p>Color:</p>
				<div style = "background-color:red;" id = "colRed"></div>
				<div style = "background-color:blue;" id = "colBlue"></div>
				<div style = "background-color:green;" id = "colGreen"></div>
				<div style = "background-color:black;" id = "colBlack"></div>
			</li>
		</ul>
		
	</div>
</body>
</html>

<script>
	
	window.onload = startup();
	
	var myColour = "black";
	var brushSize = 1;
	var myCoOrds = [];
	var mouseDown = 0;
	var playerId = 1;
	
	window.onload = function(){
	document.getElementById("colBlack").addEventListener('click', function(){myColour = "black"})
	document.getElementById("colBlue").addEventListener('click', function(){myColour = "blue"})
	document.getElementById("colRed").addEventListener('click', function(){myColour = "red"})
	document.getElementById("colGreen").addEventListener('click', function(){myColour = "green"})
	document.getElementById("erase").addEventListener('click', function(){myColour = "white"})
	document.getElementById("brushSize").addEventListener('change', function()
	{
		brushSize = document.getElementById("brushSize").value;
		document.getElementById("text").innerHTML = brushSize;
	})
	document.getElementById("text").innerHTML = brushSize;
	}
	
	document.onkeydown = function() { 
  		mouseDown = 1;
		document.getElementById("colCont").style.borderColor = "#FFC600";
		}	
		
	document.onkeyup = function() {
		mouseDown = 0;
		document.getElementById("colCont").style.borderColor = "#CFD5DE";
		}
	
	function paintMe(coOrd)
	{
		var wideCoOrd;
		var dissolveArr = [];
		
			if(mouseDown == 1)
			{
				myCoOrds[myCoOrds.length] = coOrd;

				if(brushSize > 1)
				{
					for(var i = (-1 * (brushSize - 1)); i <= (brushSize - 1); i++)
					{
						for(var j = (-80 * (brushSize - 1)); j <= (80 * (brushSize - 1)); j = j + 80)
			  	 		 {
							wideCoOrd = coOrd + i + j;
							if(wideCoOrd > 0 && wideCoOrd < 6401)
							{
								var cell = document.getElementById("td" + wideCoOrd.toString() + "");
								cell.style.transition = "all 0s";
								cell.style.backgroundColor = myColour;
								
								if(myColour != "white")
								{
									dissolveArr[dissolveArr.length] = cell;

									setTimeout(function() {
										for(var x = 0; x < dissolveArr.length; x++)
										{
								   			 dissolveArr[x].style.transition = "all 2s";
								  			 dissolveArr[x].style.backgroundColor = "white";
										}
									}, 600000)
								}
							}
						}
					}
				}
				else
				{
					var cell = document.getElementById("td" + coOrd + "");
					cell.style.transition = "all 0s";
					cell.style.backgroundColor = myColour;

					setTimeout(function() {
					cell.style.transition = "all 2s";
					cell.style.backgroundColor = "white";
					}, 600000)
				}
			}    
		}
	
	var mainTick = window.setInterval(function()
	{	
		var JSONsendObj = {myCoSend: [], colour: "", pId: playerId, brush: 1};
		
		for(var i = 0; i < myCoOrds.length;i++)
			{
				JSONsendObj.myCoSend.push(myCoOrds[i]); 
			}
		
		myCoOrds = [];
		
		JSONsendObj.colour = myColour;
		JSONsendObj.brush = brushSize;
		
		var requestParams = JSON.stringify(JSONsendObj);
		
	    var request = new XMLHttpRequest();
		request.open("POST", "upCoords.php", true);
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send("x=" + requestParams);
		
	}, 1000);
	
</script>