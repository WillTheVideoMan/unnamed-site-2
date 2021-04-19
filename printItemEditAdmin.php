<?php
session_start();
if(!isset($_SESSION['login']))
{
	echo("Improper Access");
	die();
} else
{
?>
<div class = "header" id = "itemHead"></div>
<p>Insert some important item managing tasks here my dude...</p>
<div class = "inText submitBtn red" data-target = "itemExit" style = "width:205px;">EXIT WITHOUT SAVING</div>
<div class = "inText submitBtn" data-target = "itemSave" style = "width:205px;">SAVE AND EXIT</div>

<?php }?>