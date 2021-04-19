<?php
session_start();
session_regenerate_id();

if(!isset($_SESSION['login']))
{
	header('Location: http://www.11wha.ashvillecomputing.co.uk/TheDenDesigns/Admin/Login.php');
	exit();
}
else
{?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Site</title>
<link href="https://fonts.googleapis.com/css?family=Cabin" rel="stylesheet">
<link href="/TheDenDesigns/Admin/CSS/Admin.css" rel="stylesheet" type="text/css">
<link href="/TheDenDesigns/Admin/CSS/Manage.css" rel="stylesheet" type="text/css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="../../TheDenDesigns/Images/lock.ico">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
	
	//A Sweet Collection of Code to handle all the managerial tasks...
	//By William Hall 2017
	//--------------------------
	//Overview Script Section Start
	//Overview Script End
	//--------------------------
	//Items Script Section Start
	
	printItems();
	
	$("#itemInput").keypress(function(){printItems(this.value)});
	
	$("#itemContainer").on('click', '.item', function()
	{
		editItemLaunch($(this).data("target"));
	});
	
	$("#section1 .contain2").on('click', '.submitBtn', function()
	{
		if($(this).data("save") == "yes")
		{
			alert("saving now my doode...");
		}
		
		$("#section1 .contain2").css("display", "none");
		$("#section1 .contain1").css("display", "block");
	});
	
   for(let i = 0; i < 4; i++)
		{
			$("#select" + i).click(function(){changer(i);});
		}
	
	function changer (target)
	{
		for(var j = 0; j < 4; j++)
			{
				if(j == target)
					{
						$("#section" + j).css("display", "block");
						$("#select" + j).addClass("active");
					} 
				else
					{
						$("#section" + j).css("display", "none");
						$("#select" + j).removeClass("active");
					}		
			}
	}
	
	function printItems(searchTerm)
	{
		$.ajax({
			type: 'POST',
			url: '/TheDenDesigns/Admin/AJAX/getItemsAdmin.php',
			data: {sT: searchTerm},
			success: function(result)
			{
				$("#itemContainer").html(result);
			}
		});
	}
	
	function editItemLaunch(itemId)
	{
		$("#section1 .contain1").css("display", "none");
		
		$(".loading").css("display", "block");
		
		$.ajax({
			type: 'POST',
			url: '/TheDenDesigns/Admin/AJAX/printItemEditAdmin.php',
			data: {iId: itemId},
			success: function(result)
			{
				$("#section1 .contain2").html(result);
				$(".loading").css("display", "none");
				$("#section1 .contain2").css("display", "block");

			}
		});
	}

	
	//Items Script End
	//--------------------------
	//Orders Script Section Start
	//Orders Script End
	//--------------------------
	//Finance Script Section Start
	//Finance Script End
	//--------------------------
});	


</script>
</head>

<body>

<div class = "header">ADMIN MANAGEMENT</div>

<div class = "sideSelect" id = "menu">
	<ul style = "padding:0px;">
		<li id = "select0" class = "active">Overview</li>
		<li id = "select1">Items</li>
		<li id = "select2">Orders</li>
		<li id = "select3">Finance</li>
	</ul>
	
	<a href="/TheDenDesigns/Admin/Login.php?logOut"><div class = "inText submitBtn" style = "width:100px;">LOG OUT</div></a>
	
</div>

<div id = "upperContainer" style = "margin-left:155px;">

<div class = "loading" style = "display:none;"></div>

<section id = "section0">
	<p>Here, you will be able to view a basic overview of this week/months/years sales and invertory.</p>
	<img src="https://thumbs.dreamstime.com/x/old-man-celebrating-laptop-16618166.jpg" width = "600px" height="900px">
</section>

<section id = "section1" style = "display:none;">

<div class = "contain1">
	<div class = "header">
		<input class = "inText" id = "itemInput" placeholder="Search Items">
	</div>
	
	<div id = "itemContainer"></div>
</div>

<div class = "contain2" style = "display:none;"></div>

</section>

<section id = "section2" style = "display:none;">
	<p>Here, you can view all the orders you got, print postage details, issue refunds, mark items as shipped, and get customer information.</p>
	<div class = "inText submitBtn">CLICK!</div>
</section>

<section id = "section3" style = "display:none;">
	<p>Here, you can view a style of balance sheet. See inflow, outflow, and view profit.</p>
	<input type = "image">
</section>

</div>

</body>
</html>
	
<?php } ?>