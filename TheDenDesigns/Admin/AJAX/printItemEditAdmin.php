<?php
session_start();
if(!isset($_SESSION['login']))
{
	echo("Improper Access");
	die();
} else
{
?>

<div class = "header">Item Viewer <?php echo "#" . $_POST['iId'];?></div>

<h2 class = "fieldTitle">Title:</h2>
<input type = "text" id = "itemTitle" style = "width:400px;" class = "inText">

<h2 class = "fieldTitle">Images:</h2>

<h2 class = "fieldTitle">Description:</h2>
<textarea class = "inText" id = "itemDesc" style = "width:400px;height:300px;"></textarea>

<div class = "inText submitBtn" style = "width:205px;" data-save = "yes">SAVE AND EXIT</div>
<div class = "inText submitBtn red" style = "width:205px;">EXIT</div>

<?php } ?>