<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GriddyThingy</title>
<link href="cssGrid.css" rel="stylesheet" type="text/css" />
<style type="text/css">
</style>
</head>

<body>

<table width="400" height="400">
<?php $count = 0 ?>
<?php for($i = 1; $i < 21; $i++){ ?>
     <tr>
<?php for($j = 1; $j < 21; $j++){ $count = $count + 1; ?>
		<td id = "td<?php echo ($count) ?>" onMouseOver="paintMe(<?php echo ($count) ?>);"></td>
<?php } ?>
     </tr>
<?php } ?>

</table>

<p id = "message"></p>

</body>
</html>

<script>
	
	var myLobbyId = <?php echo $_GET["l"];?>;
	var myPlayerId = <?php echo $_GET["p"];?>;
	var myColour = "";
	var myCoOrds = [];
	
	switch(myPlayerId)
		{
			case 1: myColour = "black";break;
			case 2: myColour = "blue";break;
			case 3: myColour = "red";break;
			case 4: myColour = "green";break;
		}
	
	function paintMe(coOrd)
	{
		myCoOrds[myCoOrds.length] = coOrd;
		
		var cell = document.getElementById("td" + coOrd.toString() + "");
		cell.style.transition = "all 0s";
		cell.style.backgroundColor = myColour;

		setTimeout(function() {
		cell.style.transition = "all 5s";
		cell.style.backgroundColor = "white";
		}, 15)	
	}
	
	function paintThem(coOrd, colour)
	{
		var cell = document.getElementById("td" + coOrd.toString() + "");
		cell.style.transition = "all 0s";
		cell.style.backgroundColor = colour;

		setTimeout(function() {
		cell.style.transition = "all 5s";
		cell.style.backgroundColor = "white";
		}, 15)	
	}
	
	var mainTick = window.setInterval(function()
	{	
		var JSONsendObj = {myCoSend: [], pID: null, lID: null};
		var JSONrecObj;
		
		for(var i = 0; i < myCoOrds.length;i++)
			{
				JSONsendObj.myCoSend.push(myCoOrds[i]); 
			}
		
		myCoOrds = [];
		
		JSONsendObj.pID = myPlayerId;
		JSONsendObj.lID = myLobbyId;
		
		var requestParams = JSON.stringify(JSONsendObj);
		
	    var request = new XMLHttpRequest();
		request.open("POST", "SendRecieve.php", true);
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send("x=" + requestParams);
		
		request.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
				JSONrecObj = JSON.parse(this.responseText);
				jsonAnalyser(JSONrecObj);
 			}
			
		}
		
	}, 1000);
		
	function jsonAnalyser(jsonObj)
	{
		if(jsonObj.terminated != true)
			{
				var timers = []; //Array of timer objects
                var counters = []; //Array of timer counters
				var intervals = []; //Array of timer intervals
				var maximums = []; //Array of number of coords received per player
				
				for(i in jsonObj.players)
  				{
					for(j in jsonObj.players[i].coOrds)
					{
						if(jsonObj.players[i].coOrds.length != 0)
						{
							maximums[i] = jsonObj.players[i].coOrds.length;
							intervals[i] = Math.floor(1000 / maximums[i]);
						} else {
							maximums[i] = 0;
							intervals[i] = 0; 
						}
        			}
  				}
				
				if(maximums[0] != 0) 
					{
					counters[0] = 0;
					timers[0] = setInterval(function()
						{
							paintThem(JSONobj.players[0].coOrds[counters[0]], JSONobj.players[0].colour);
                
							++counters[0];
			
							if(counters[0] == maximums[0] - 1)
								{
								clearInterval(timers[0]);
								}
			
						}, intervals[0])
					}
				
				if(maximums[1] != 0) 
					{
					counters[1] = 0;
					timers[1] = setInterval(function()
						{
							paintThem(JSONobj.players[1].coOrds[counters[1]], JSONobj.players[1].colour);
                
							++counters[1];
			
							if(counters[1] == maximums[1] - 1)
								{
								clearInterval(timers[1]);
								}
			
						}, intervals[1])
					}
				
				if(maximums[2] != 0) 
					{
					counters[2] = 0;
					timers[2] = setInterval(function()
						{
							paintThem(JSONobj.players[2].coOrds[counters[2]], JSONobj.players[2].colour);
                
							++counters[2];
			
							if(counters[2] == maximums[2] - 1)
								{
								clearInterval(timers[2]);
								}
			
						}, intervals[2])
					}
				
				if(maximums[3] != 0) 
					{
					counters[3] = 0;
					timers[3] = setInterval(function()
						{
							paintThem(JSONobj.players[3].coOrds[counters[3]], JSONobj.players[3].colour);
                
							++counters[3];
			
							if(counters[3] == maximums[3] - 1)
								{
								clearInterval(timers[3]);
								}
			
						}, intervals[3])
					}
			} 
		else
			{
			    document.getElementById("message").innerHTML = "Session Was Ended By Admin";
		        clearInterval(mainTick);
			}
	}
	
</script>