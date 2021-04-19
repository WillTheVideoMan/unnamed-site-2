<?php
//Start the session
session_start();

//Enable Error reporting for debug
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

//Defining the secure access parameter means that any config or functional files 
//can only be accessed via a 'require' rather than directly through URL and HTTP, improving saftey.
define( 'secureAccessParameter', true );
require '../Project/Tools/config.php';
require '../Project/Tools/walkFunctions.php';

//If the user has not logged in, then the session variable of 'login' will not be set,
//so redirect to the Login page.
if ( !isset( $_SESSION[ 'login' ] ) ) {
	header( 'Location:http://11wha.ashvillecomputing.co.uk/Project/Login' );
	exit();
}

//Call the get walk status function. Assign a variable to its returned value.
$walkStatus;
$returnObject = json_decode( getWalkStatus(), 1 );
if ( $returnObject[ 'status' ] == "OK" )$walkStatus = $returnObject[ 'walkStatus' ];

//If the walk is not in the Active state, then redirect to the overview page.
if($walkStatus != 2)
{
	header( 'Location:http://www.11wha.ashvillecomputing.co.uk/Project/Overview' );
	exit();
}
?>

<!DOCTYPE html">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Tracking</title>
<link rel="stylesheet" type="text/css" href="main.css">
<link rel="icon" href="../Project/Resources/favicon.png" type="image/x-icon" />
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$(document)
	.ready(function() {

			//Fade in the main loading spinner.
			$("#mainSpin")
				.fadeIn(150);

			//When the div with the 'toOverview' ID is clicked, redirect to the overview page.
			$("#toOverview")
				.click(function() {
					$("#bodyDiv")
						.fadeOut(150);
					setTimeout(
						function() {
							window.location.href = "../Project/Overview";
						}, 150);
				});

			//When the div with the 'btnLogOut' ID is clicked, redirect to the Login/Logout page.
			$("#btnLogOut")
				.click(function() {
					$("#bodyDiv")
						.fadeOut(150);
					setTimeout(
						function() {
							window.location.href = "../Project/Login?logOut";
						}, 150);
				});

			//Variable to track wether this is the first time running through the program.
			var first = true;

			//Variable to store the display order of the tracking table.
			var orderCode = "";

			//Call the ticker update function.
			ticker();

			//When anything in the table container division is clicked, then select the 
			//enclosed element that was clicked. 
			$("#tblContainer")
				.on('click', '#item', function() {
					
					//If the clicked items 'data' is set to A (Ascending).
					if ($(this)
						.data("sort") == "A") {
						
						//Change the sort direction to D (Decending).
						$(this)
							.data("sort", "D");

						//Contruct the orderCode with the new sort and location information.
						orderCode = 
							$(this)
								.data("loc") + 
							$(this)
								.data("sort");
					}
					//Else, if the clicked items 'data' is set to D (Decending).
					else if ($(this)
						.data("sort") == "D") {
						//Change the sort direction to null (There will be no sorting).
						$(this)
							.data("sort", "");

						//Construct a null order code.
						orderCode = "";
					}
					//Else, if the clicked items 'data' is set to Null (No sorting).
					else if ($(this)
						.data("sort") == "") {
						
						//Change the sort direction to A (Ascending).
						$(this)
							.data("sort", "A");

						//Contruct the orderCode with the new sort and location information.
						orderCode = 
							$(this)
								.data("loc") + 
							$(this)
								.data("sort");
					}
					
					//Call the print table function.
					printTable();
				});

			//If any on the year select radio buttons are toggles, then call the print table function.
			$("input[name='yearSelect']")
				.change(function() {
					printTable();
				});

			//If the name search box is typed in, then call the print table function.
			$("#nameSearch")
				.keyup(function() {
					printTable();
				});

			//Define a function that will call the print table function every 10 seconds. This will ensure that the table is 
			//updated automatically, without the need for user refreshing.
			function ticker() {
				printTable();
				setTimeout(ticker, 10000);
			}

			//A function that will gather options selected by the user, and print a table with the content returned
			//from an AJAX call.
			function printTable() {

				//Get the selected year value.
				var yearData = $("input:radio[name='yearSelect']:checked")
					.val();

				//Get the search term from the name searchbox.
				var nameSearch = $("#nameSearch")
					.val();

				//Define the base URL which points to the PHP handler.
				var url = "../Project/AJAX/printLogTable?";

				//Add the order code to the URL.
				url += ("order=" + orderCode);

				//If the selected year is not 'all', then add the year data to the URL.
				if (yearData != "all") url += ("&year=" + yearData);

				//If the selected name is not null, then add the name data to the URL.
				if (nameSearch != "") url += ("&query=" + nameSearch);

				//Define AJAX call to a PHP handler page. This handler will accept some data from the user, such as a 
				//yeargroup or name search terms, and returns a contructed HTML table, printed  from the database.
				$.ajax({
						url: url,
						success: function(result) {

							//Print the table to the screen. This will add the returned HTML from the server to the page.
							$("#tblContainer")
								.html(result);

							//If this is the first time we have printed the table, then remove the loading spinner and show
							//the table and the main body.
							if (first) {
								$("#mainSpin")
									.fadeOut(150);
								setTimeout(
									function() {
										$("#bodyDiv")
											.fadeIn(150);
									}, 150);
								first = false;
							}

							//For each column, bind mouse event handlers. We will then bind the cell highlighting to the table.
							for (let i = 1; i < 8; i++) {
								//Bind a mouse enter event to each columm.
								$(".data")
									.on('mouseenter', '.col' + i, function() {

										//Add the highlight CSS class to the entire column.
										$(".col" + i)
											.addClass("highlight");

										//Get the row which the cell is on.
										var row = $(this)
											.data("row");

										//Add the highlight CSS class to the entire row.
										$(".row" + row)
											.addClass("highlight");
									});

								//Bind a mouse leave event to each columm.
								$(".data")
									.on('mouseleave', '.col' + i, function() {

										//Remove the highlight CSS class from the entire column.
										$(".col" + i)
											.removeClass("highlight");

										//Get the row which the cell is on.
										var row = $(this)
											.data("row");

										//Remove the highlight CSS class from the entire row.
										$(".row" + row)
											.removeClass("highlight");
									});
							}
					}
			 	});
		}

		//When a div with the class 'launchInfo' is clicked, display the information modal.
		$(".launchInfo")
		.click(function() {
			$("#infoModal")
				.fadeIn(150);
		});

		//When a div with the id 'infoModalClose' is clicked, close the information modal.
		$("#infoModalClose")
		.click(function() {
			$("#infoModal")
				.fadeOut(150);
		});
	});
</script>
</head>


<header>
	<h1>Student Search!</h1>
	<nav>
		<ul>
			<li id="toOverview" >Return To Overview</li>
		</ul>
	</nav>
</header>

<body>
	<div id="bodyDiv">
      <table class = "yearContainer center">
        <tr>
        <td>
          <label>
            <input type="radio" name="yearSelect" value="all" checked>
            All
            </label>
           </td>
           <td>
          <td>
          <label class = "y7">
            <input type="radio" name="yearSelect" value="7">
            Year 7
            </label>
           </td>
           <td>
          <label class = "y8">
            <input type="radio" name="yearSelect" value="8">
            Year 8
            </label>
           </td>
           <td>
          <label class = "y9">
            <input type="radio" name="yearSelect" value="9">
            Year 9
            </label>
           </td>
           <td>
          <label class = "y10">
            <input type="radio" name="yearSelect" value="10">
            Year 10
            </label>
           </td>
           <td>
          <label class = "y11">
            <input type="radio" name="yearSelect" value="11">
            Year 11
            </label>
           </td>
           <td>
          <label class = "y12">
            <input type="radio" name="yearSelect" value="12">
            Year 12
            </label>
           </td>
           <td>
          <label class = "y13">
            <input type="radio" name="yearSelect" value="13">
            Year 13
            </label>
           </td>
           <td>
        </tr>
      </table>
		
	<div class = "short center">
    	<div class="sbsWrap">
			<input type="text" id = "nameSearch" class ="inText short" placeholder="Student Search">
			<img src="../Project/Resources/info.png" class="launchInfo" alt="info">
		</div>
	</div>
      
		<div id = "tblContainer">
	  		Loading...
		</div>
        
		<div id="btnLogOut" class="btn center">
			Log Out
		</div>
        
	</div>
    <div class="spinner" id="mainSpin" style="display:none;"></div>
    	<div class="modal" id="infoModal">
		<div class="content">
			<h2>Serach For Any Student</h2>
			<p>From here, you can search for any student, using their surname, forename or Ashville student code.</p>
			<p>The search will use all your data to help find a student. Don't worry about the order or format of your search.</p>
			<div class="btn center" id="infoModalClose">Got It!</div>
		</div>
	</div>
</body>