<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="myStyle.css" rel="stylesheet" type="text/css">

<script>
	var player;

	function joinMe(lobby)
    {
		
			    var request = new XMLHttpRequest();
				request.open("POST", "createNewLobby.php", true);
				request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				request.send("l=" + lobby);
			request.onreadystatechange = function() {
    			if (this.readyState == 4 && this.status == 200) {
                      player = this.responseText;
 				}	
			  }
		
		if(player == 0)
			{
				alert("Sorry - This lobby has now become full...");
			}
		else
			{
				var windowParams = "l=" + lobby + "&p=" + player;
		        window.open("CoolClickGrid.php?" + windowParams, "_self");
			}
	
	}
</script>

</head>

<body>

<table width="400" height="400">
<?php 
	
	include ("databaseHandler.php");
    db_connect();
	
	$sql_statement = "SELECT * FROM tblLobbies ORDER BY lobbyId";
	$sqlResponse = $db_link->query($sql_statement) or die($db_link->error);

	
	if($sqlResponse->num_rows>0)
	{ 	
		while($data_row = $sqlResponse->fetch_assoc()){ 
	?>
	
     <tr>
		<td><?php echo $data_row[lobbyId] ?></td>
        <td><?php echo $data_row[playerCount]?>/4</td>
        <td onClick = "joinMe(<?php echo $data_row[lobbyId] ?>)">Click To Join!</td>
     </tr>
     
<?php }} ?>
</table>

</body>
</html>