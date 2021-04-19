<?php
session_start();
if(!isset($_SESSION['login']))
{
	echo("Improper Access");
	die();
} else
{
?>
	<div class = "item imgBackAdd" data-target = "0">
			<div class = "title">LIST NEW ITEM</div>
			<div class = "optionBack">
				<img class = "icon" src = "/TheDenDesigns/Images/plus.svg" width = "50px;">
			</div>
            <div class = "title">&nbsp;</div>
		</div>
	
<?php
 	for($i = 1; $i < 50; $i++)
 		{?>
 		
		<div class = "item" data-target = "<?php echo $i?>" style="background-image:
		url(http://i.imgur.com/LbDUJDk.jpg?fb);">
			<div class = "title">CUSHION</div>
			<div class = "optionBack">
				<img class = "icon" src = "/TheDenDesigns/Images/pencil.png" width = "50px;">
			</div>
            <div class = "title"><?php echo "#" . $i?></div>
		</div>
		 
<?php }} ?>
