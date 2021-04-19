<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="cssGrid.css" rel="stylesheet" type="text/css" />
<title>Untitled Document</title>
</head>

<body>

<h1>The Viewing Area!</h1>

<div style = "width:800px;" id = "boxCont">
<table width="800" height="800">
<?php $count = 0 ?>
<?php for($i = 1; $i < 81; $i++){ ?>
     <tr>
<?php for($j = 1; $j < 81; $j++){ $count = $count + 1; ?>
		<td id = "td<?php echo ($count) ?>"></td>
<?php } ?>
     </tr>
<?php } ?>

</table>
</div>

<p id = "text2"></p>
<p id = "text"></p>


</body>
</html>


<script>
	
var theirColour = "black";
var theirBrush = "1";
	
	window.onload = function()
	{
	    document.getElementById("boxCont").style.transition = "all 0.5s";
	}
	
function paintThem(coOrd)
	{
		var wideCoOrd;
		var brushSize = parseInt(theirBrush);
		var dissolveArr = [];
		
			if(brushSize > 1)
			{
					for(var i = (-1 * (brushSize - 1)); i <= (brushSize - 1); i++)
					{
						for(var j = (-80 * (brushSize - 1)); j <= (80 * (brushSize - 1)); j = j + 80)
			  	 		{
							wideCoOrd = parseInt(coOrd) + i + j;
							if(wideCoOrd > 0 && wideCoOrd < 6401)
							{
								var cell = document.getElementById("td" + wideCoOrd.toString() + "");
								cell.style.transition = "all 0s";
								cell.style.backgroundColor = theirColour;

								if(theirColour != "white")
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
					var cell = document.getElementById("td" + coOrd.toString() + "");
					cell.style.transition = "all 0s";
					cell.style.backgroundColor = theirColour;

					setTimeout(function() {
					cell.style.transition = "all 2s";
					cell.style.backgroundColor = "white";
					}, 600000)
				}
	} 
	
var mainTick = window.setInterval(function()
	{	
	    var preObj;
	    var request = new XMLHttpRequest();
		request.open("POST", "getCoords.php", true);
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send();
		
		request.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
				preObj = JSON.parse(this.responseText);
				theirColour = preObj.player.colour;
				theirBrush = preObj.player.brush;
				
				document.getElementById("boxCont").style.borderColor = theirColour;
				
				painter(preObj);
 			}
		}
	}, 1000);
	
function painter(obj)
	{
	
	if(obj.player.coOrds[0] != 0)
	{
		
    var interval = Math.floor(1000/obj.player.coOrds.length);
	var maximum = obj.player.coOrds.length;
	var counter = 0;
	
	document.getElementById("text").innerHTML = "interval:" + interval + " maximum:" + maximum;
	
	var timer = window.setInterval(function()
	{
		var coOrd = obj.player.coOrds[counter];
		counter++;
		
		if(counter == maximum)
		{
			clearInterval(timer);
		}
		
		paintThem(coOrd);
		
	}, interval); 
    }
	else 
	{
		document.getElementById("text").innerHTML = "Artist Idle...";	
	}
}
</script>