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
?>

<!DOCTYPE html">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Overview</title>
	<link rel="stylesheet" type="text/css" href="main.css">
	<link rel="icon" href="../Project/Resources/favicon.png" type="image/x-icon"/>
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

	<!-- Including the EASEL.JS API - this abstracts such processes as adding clickable areas and 
complex graphics on the canvas element. -->
	<script src="https://code.createjs.com/easeljs-0.8.2.min.js"></script>

	<script>
		$( document )
			.ready( function () {

				//When the div with the 'toManage' ID is clicked, redirect to the manage page.
				$( "#toManage" )
					.click( function () {
						$( "#bodyDiv" )
							.fadeOut( 150 );
						setTimeout(
							function () {
								window.location.href = "../Project/Manage";
							}, 150 );
					} );

				//When the div with the 'btnLogOut' ID is clicked, redirect to the Login/Logout page.
				$( "#btnLogOut" )
					.click( function () {
						$( "#bodyDiv" )
							.fadeOut( 150 );
						setTimeout(
							function () {
								window.location.href = "../Project/Login?logOut";
							}, 150 );
					} );
			} );
	</script>

	<!-- If the user is low level and the walk is status 'zero' i.e. not active, 
then load JavaScript to show just the main div. -->
	<?php if($_SESSION['level'] == 0 && $walkStatus == 0) {?>
	<script>
		$( document )
			.ready( function () {
				$( "#bodyDiv" ).fadeIn( 500 );
			} );
	</script>
	<?php } ?>

	<!-- If the user is high level and the walk is status 'zero' i.e. not active, 
then load JavaScript to show the scheduling screen -->
	<?php if($_SESSION['level'] == 1 && $walkStatus == 0) {?>
	<script>
		$( document )
			.ready( function () {

				$( "#bodyDiv" )
					.fadeIn( 150 );

				//When the scheduling button is clicked, run function.
				$( "#btnScheduleWalk" )
					.click( function () {

						//Get the start time value entered by the user.
						var startTrackTime = $( "#startTrackTime" )
							.val();

						//Create a comparable date object with the start time value.
						var dateStart = new Date( startTrackTime );

						//Set the minimum date to be entered to now.
						var dateMin = new Date();

						//Reset the info tag so that we don't show multiple errors in one tag. 
						$( "#info" )
							.html( "&nbsp;" );

						//If chosen start time is in the future, then proceed.
						if ( startTrackTime != "" && dateStart > dateMin ) {

							//Define AJAX call to a PHP handler page. This page updates the walks current status for all clients.
							//Here, we are setting the status to one, with a time in the future. Since we have to wait for the countdown, we
							//now enter the Waiting state.
							$.ajax( {
								url: "../Project/AJAX/updateWalkStatus",
								method: "POST",
								data: {
									status: 1,
									startTime: startTrackTime
								},
								success: function ( result ) {
									//Parse the returned JSON response.
									var returnObject = JSON.parse( result );

									//If the state change was made succesfully, then reload page.
									if ( returnObject.status == "OK" ) {
										$( "#bodyDiv" )
											.fadeOut( 150 );
										setTimeout(
											function () {
												window.location.href = "../Project/Overview";
											}, 150 );
									} else {
										$( "#info" )
											.html( "Error: Could not launch walk. Please refresh and try again." );
									}
								}
							} );
						} else {
							//Display the error - the time they chose was not in the future!
							$( "#info" )
								.html( "Please select a valid start time! Ensure it is a date and time in the future..." );
						}
					} );

				//Launch the walk immidiately when this button is clicked.
				$( "#btnLaunchWalk" )
					.click( function () {

						//Reset the info tag so that we don't show multiple errors in one tag. 
						$( "#info" )
							.html( "&nbsp;" );

						//Define AJAX call to a PHP handler page. This page updates the walks current status for all clients.
						//Here, we are setting the status to one, with the current time. Since we don't have any time to wait, we
						// now enter the Active State.
						$.ajax( {
							url: "../Project/AJAX/updateWalkStatus",
							method: "POST",
							data: {
								status: 1,
								startTime: new Date()
									.toISOString()
							},
							success: function ( result ) {
								//Parse the returned JSON response.
								var returnObject = JSON.parse( result );

								//If the state change was made succesfully, then reload page.
								if ( returnObject.status == "OK" ) {

									$( "#bodyDiv" )
										.fadeOut( 150 );
									setTimeout(
										function () {
											window.location.href = "../Project/Overview";
										}, 150 );
								} else {
									$( "#info" )
										.html( "Error: Could not launch walk. Please refresh and try again." );
								}
							}
						} );
					} );
			} );
	</script>

	<!-- If user is high level, and the walk is in Waiting State, then load the Javascript to show the countdown controls -->
	<?php } if($_SESSION['level'] == 1 && $walkStatus == 1){?>
	<script>
		$( document )
			.ready( function () {

				$( "#bodyDiv" )
					.fadeIn( 150 );

				//When the cancel button is clicked, run function.
				$( "#btnCancel" )
					.click( function () {

						//Define AJAX call to a PHP handler page. This page updates the walks current status for all clients.
						//Here, we are setting the status to zero, with a null string for the time. The system now enters the
						//Inactive state.
						$.ajax( {
							url: "../Project/AJAX/updateWalkStatus",
							method: "POST",
							data: {
								status: 0,
								startTime: ''
							},
							success: function ( result ) {
								//On success, reload the page. 
								$( "#bodyDiv" )
									.fadeOut( 150 );
								setTimeout(
									function () {
										location.reload( true );
									}, 150 );
							}
						} );
					} );
			} );
	</script>

	<!-- If user is any level, and the walk is in Waiting State, then load the Javascript to show the countdown timer -->
	<?php } if($walkStatus == 1){?>
	<script>
		$( document )
			.ready( function () {

				$( "#bodyDiv" )
					.fadeIn( 150 );

				var timeToLaunch;
				var lastTime;

				//Define an AJAX call to a PHP handler page. This page fetches the UNIX seconds time to launch. 
				$.ajax( {
					url: "../Project/API/getTimeToStart?",
					success: function ( result ) {

						//Parse the returned JSON response.
						var returnObject = JSON.parse( result );

						//If the return status is OK, then...
						if ( returnObject.status == "OK" ) {
							//On success, set timeToLaunch to the result.
							timeToLaunch = returnObject.timeToStart;

							//Set lastTime to equal the current time in seconds.
							lastTime = Math.floor( new Date()
								.getTime() / 1000 );

							//Call the ticker function.
							ticker();
						}

					}
				} );

				function ticker() {

					//Set currTime to the current time in seconds.
					var currTime = Math.floor( new Date()
						.getTime() / 1000 );

					//If current time is greater than last time, it means that one second has elapsed. 
					if ( currTime > lastTime ) {

						//Set lastTime to equal the current time in seconds.
						lastTime = Math.floor( new Date()
							.getTime() / 1000 );

						//Remove one second from the fetched UNIX start time.
						timeToLaunch--;

						//Using modulus calculations, we can find the number of days, hours, months and seconds to launch
						var d = Math.floor( timeToLaunch / ( 60 * 60 * 24 ) );
						var h = Math.floor( ( timeToLaunch % ( 60 * 60 * 24 ) ) / ( 60 * 60 ) );
						var m = Math.floor( ( timeToLaunch % ( 60 * 60 ) ) / ( 60 ) );
						var s = Math.floor( timeToLaunch % ( 60 ) );

						//Print the remaining time to the screen.
						$( "#timer" )
							.html( d + "d" + h + "h" + m + "m" + s + "s" );

					}

					//If the time to launch is zero seconds, then reload the page.
					if ( timeToLaunch == 0 ) {
						$( "#bodyDiv" )
							.fadeOut( 1500 );
						setTimeout(
							function () {
								window.location.href = "../Project/Overview";
							}, 1500 );
					} else {
						//Else, call the ticker function in another 333 milliseconds to check if a second has elapsed.
						setTimeout( ticker, 333 );
					}
				}
			} );
	</script>

	<!-- If user is any level, and the walk is in the Active state, the load the JavaScript code to show the graphical 
overview of the tracking data. -->
	<?php } if($walkStatus == 2){?>
	<script>
		$( document )
			.ready( function () {

				//Show the loading spinner element.
				$( "#mainSpin" )
					.fadeIn( 100 );

				//If the 'toTracking' button is clicked, then load the Tracking Page.
				$( "#toTracking" )
					.click( function () {
						$( "#bodyDiv" )
							.fadeOut( 150 );
						setTimeout(
							function () {
								window.location.href = "../Project/Tracking";
							}, 150 );
					} );

				//Instantiate an instace of an object which will hold the information about the checkpoints
				var checkpoints = new Object( {
					distance: new Array( 0, 4300, 8400, 13000, 16300 ),
					initials: new Array( "SF", "PB", "LL", "BW" )
				} );

				//Define an accociative array to store HEX colour information for each yeargroup.
				var colours = [ "#FFBF00", "#FF7300", "#FF0D00", "#00FF37", "#0037FF", "#FF01DD", "#8000FF" ];

				//Define the Easel.js Stage - A HTML canvas API. The stage will be where we print our graphic data display.
				var stage = new createjs.Stage( "trackingCanvas" );
				stage.enableMouseOver( 50 );

				//Define a variable that can be used to track wether this is the first time fetching tracking data from the server.
				var first = true;

				//Call the update function.
				update();

				//Bind click event listerers to each of the accordion pannels toggle switches 
				//- these panels will show checkpoint data.
				for ( let i = 1; i < 5; i++ ) {
					$( "#tog" + i.toString() )
						.click( function () {
							//Pass 'i' to the panelSwitch function.
							panelSwitch( i );
						} );
				}

				//When the div with the 'launchInfo' class is clicked, open the information modal.
				$( ".launchInfo" )
					.click( function () {
						$( "#infoModal" )
							.fadeIn( 150 );
					} );

				//When the div with the infoModalClose' ID is clicked, close the information modal.
				$( "#infoModalClose" )
					.click( function () {
						$( "#infoModal" )
							.fadeOut( 150 );
					} );

				function update() {

					//Call the getLogData function, which accepts checkpoints and an anonymous callback function
					//as parameters. Since the getLogData function contains an asynchronous AJAX function, we need a way
					//to execute more code after the AJAX function has finished. We cannot write inline code here since it will
					//run before the AJAX has finished its call. To get around this, we can pass a function to the parent function,
					//which will be called from within the parent function whenever the AJAX is successful.

					getLogData( checkpoints, function ( logData ) {

						//Clear the stage and prepare for graphics.
						stage.removeAllChildren();
						stage.update();

						//Run the 'plotTimeline' function with param 'logData'
						plotTimeline( logData );

						//Run the 'plotStudents' function with param 'logData'
						plotStudents( logData );

						//Run the 'plotCheckpoints' function with param 'logData'
						plotCheckpoints( checkpoints );

						//Run the 'populatePanels' function with param 'logData'
						populatePanels( logData );

						//If this is the first time getting some data, then we have to first fade out the 
						//CSS loading spinner element, so that the page below will be displayed.
						//By using a loading spinner, we can show the user that an asyncronous proccess is running.
						if ( first ) {
							first = false;
							$( "#mainSpin" )
								.fadeOut( 150 );
							setTimeout(
								function () {
									$( "#bodyDiv" )
										.fadeIn( 500 );
								}, 150 );
						}

						//Call the update function in 5 seconds, which contains the 'getLogData' function
						//since params cannot be passed through a setTimeout call.
						setTimeout( update, 5000 );

					} );
				}

				//A function which will open the sliding panel that contains the checkpoint information. It
				//takes a target panel, opening that panel and closing all other sliding panels that do not match the target.

				function panelSwitch( target ) {

					//For Each Panel
					for ( let j = 1; j < 5; j++ ) {

						//If panel is not the target
						if ( target != j ) {

							//Close that panel, using a jQuery selector to match the panel object, over a time of 75 milliseconds.
							$( "#pan" + j.toString() )
								.slideUp( 75 );
						}
					}

					//Set a timeout to the same length as the closing panel time.
					setTimeout(
						function () {

							//Open (or close) the target panel on click over a time of 75 milliseconds/
							$( "#pan" + target.toString() )
								.slideToggle( 75 );

							//Scroll the page to center the newly opened panel on the screen, over the course of 500 milliseconds.
							$( 'body, html' )
								.animate( {
									scrollTop: $( "#panelContainer" )
										.offset()
										.top - 170
								}, 500 );
						}, 75 );
				}

				//A function that will plot the ellipse on the EASEL.js stage, using tracking data to generate dynamic text statistics.
				function plotTimeline( trackingData ) {

					//Instantiate an EASEL.js shape.
					var timeLine = new createjs.Shape();

					//Instantiate an EASEL.js text element. Generate the text string based on how many people have started the walk.
					var startCountText = new createjs.Text( "Started: " + trackingData[ 'startCount' ] + "/" + trackingData[ 'stuCount' ], "50px Source Sans Pro", "black" );

					//Instantiate an EASEL.js text element. Generate the text string based on how many people has finished agains how many people started.
					var finishCountText = new createjs.Text( "Finished: " + trackingData[ 'checkCount' ][ 4 ] + "/" + trackingData[ 'startCount' ], "50px Source Sans Pro", "black" );

					//Set the graphic drawing properties. Then, draw the ellipse.
					timeLine.graphics
						.setStrokeStyle( 2 )
						.beginStroke( "#000" )
						.beginFill( "#fff" )
						.drawEllipse( 0, 0, stage.canvas.width - 50, stage.canvas.height - 50 );

					//Set the top-left-corner position of the ellipse - 25px indented here.
					timeLine.y = 25;
					timeLine.x = 25;

					//Set the start and finish count text based on the canvas width and the pixel-width of the generated text.
					startCountText.x = ( stage.canvas.width - startCountText.getMeasuredWidth() ) / 2;
					finishCountText.x = ( stage.canvas.width - finishCountText.getMeasuredWidth() ) / 2;

					//Position the text elements.
					startCountText.y = ( stage.canvas.height - 80 ) / 2 - 25;
					finishCountText.y = ( stage.canvas.height - 80 ) / 2 + 25;

					//Prepare the stage for a graphics update.
					stage.addChild( timeLine, startCountText, finishCountText );

					//Update the stage to the new graphics.
					stage.update();
				}

				//A function to plot each student on the timeline.
				function plotStudents( trackingData ) {

					//Instantiate an EASEL.js shape.
					var circle = new createjs.Shape();

					//For each student distance, plot the student.
					for ( var i = 0; i < trackingData[ 'distances' ].length; i++ ) {

						//Make a clone of the EASEL.js shape, copying the graphics instance too.
						var cloneCirc = circle.clone( true );

						//Draw the circle, with the colour as defined by the colour array and the yeargroup of the student. 
						cloneCirc.graphics.beginFill( colours[ trackingData[ 'distances' ][ i ][ 'year' ] - 7 ] )
							.drawCircle( 0, 0, 6 );

						//Calculate the coordinates of the student by calling the 'CalculateCoords' function, accepting the 
						//distance travelled, ellipse width and height as the parameters.
						var pointCoords = CalculateCoords(
							trackingData[ 'distances' ][ i ][ 'meters' ], ( stage.canvas.width - 50 ) / 2, ( stage.canvas.height - 50 ) / 2
						);

						//Set the students coordinates.
						cloneCirc.x = pointCoords[ "x" ] + stage.canvas.width / 2;
						cloneCirc.y = pointCoords[ "y" ] + stage.canvas.height / 2;

						//Prepare the stage for a graphics update.
						stage.addChild( cloneCirc );

						//Update the stage to the new graphics.
						stage.update();
					}
				}

				//A function to plot the clickable checkpoints at their locations on the timeline.
				function plotCheckpoints( checkpoints ) {

					//Instantiate an EASEL.js shape.
					var checkPoint = new createjs.Shape();

					//Instantiate an EASEL.js text element with an empty text string.
					var checkText = new createjs.Text( "", "24px Source Sans Pro", "black" );

					//For Each Checkpoint, plot a clickable circle.
					for ( let i = 0; i < 4; i++ ) {

						//Make a clone of the EASEL.js shape and text element, copying the graphics instance too.
						var cloneCheck = checkPoint.clone( true );
						var cloneText = checkText.clone( true );

						//Draw a grey circle.
						cloneCheck.graphics.beginFill( "#b2b2b2" )
							.drawCircle( 0, 0, 20 );

						//Set the text string to the checkpoint initials.
						cloneText.text = checkpoints[ 'initials' ][ i ];

						//Calculate the coordinates of the checkpoint circle by calling the 'CalculateCoords' function, accepting the 
						//checkpoint distance, ellipse width and height as the parameters.
						var pointCoords = CalculateCoords(
							checkpoints[ 'distance' ][ i ], ( stage.canvas.width - 50 ) / 2, ( stage.canvas.height - 50 ) / 2
						);

						//Set the checkpoint initals text based on the canvas width, top-left-corner coords and the 
						//pixel-width of the generated text.
						cloneText.x = pointCoords[ "x" ] + ( stage.canvas.width - cloneText.getMeasuredWidth() ) / 2;
						cloneText.y = pointCoords[ "y" ] + ( stage.canvas.height - cloneText.getMeasuredHeight() ) / 2 - 6;

						//Set the top-left-corner circle coords based on the canvas width.
						cloneCheck.x = pointCoords[ "x" ] + stage.canvas.width / 2;
						cloneCheck.y = pointCoords[ "y" ] + stage.canvas.height / 2;

						//Set the cursor to the pointer when hovering over the clickable element.
						cloneCheck.cursor = "pointer";

						//Prepare the stage for a graphics update.
						stage.addChild( cloneCheck, cloneText );

						//Update the stage to the new graphics.
						stage.update();

						//Add a new event which handles the click event, by switching to the relevant checkpoint info panel.
						cloneCheck.addEventListener( "click", function () {
							panelSwitch( i + 1 );
						} );
					}
				}

				//A function to populate the checkpoint information panels.
				function populatePanels( trackingData ) {

					//For each checkpoint panel, update the information.
					for ( var i = 0; i < 5; i++ ) {

						//Select the checkpoint information element in our panel, and update the text to display the checkpoint student count.
						$( "#check" + ( i + 1 ) )
							.html( trackingData[ 'checkCount' ][ i ] + "/" + trackingData[ 'startCount' ] );

						//Define a variable to store which staff are here.
						var staffString = "";

						//If there is more than zero staff memebers at the checkpoint, then add them to the staff string, with a clickable link to the
						//staff information card viewer.
						if ( trackingData[ 'checkStaff' ][ i ].length > 0 ) {

							//For each staff member, add them to the staff string, within a HTML 'a' tag.
							for ( var j = 0; j < trackingData[ 'checkStaff' ][ i ].length; j++ ) {
								staffString += '<a href = "../Project/staffCard?staffCode=' +
									trackingData[ 'checkStaff' ][ i ][ j ] + '">' +
									trackingData[ 'checkStaff' ][ i ][ j ] + '</a>&nbsp;&nbsp;';
							}
						} else {
							//If no-one is here, let the user know.
							staffString = "No-One Here Yet!";
						}

						///Select the staff displayer element in our panel, and update the html to show who is here with a clickable link.
						$( "#staffHere" + ( i + 1 ) )
							.html( staffString );
					}
				}

				//A function to get and format some log data from the server.
				function getLogData( checkpoints, callBack ) {

					//Outline a new object to populate with the log data.
					var logData = {
						distances: [],
						checkCount: [ 0, 0, 0, 0, 0 ],
						checkStaff: [
							[ '' ],
							[ '' ],
							[ '' ],
							[ '' ],
							[ '' ]
						],
						stuCount: 0,
						startCount: 0
					};

					//Define an AJAX call to a PHP handler page. This page fetches the log data from the server. 
					$.ajax( {
						url: "../Project/AJAX/getLogData",
						success: function ( result ) {

							//Turn the response into a usable Javascript object.
							var returnObj = JSON.parse( result );

							//The number of students is written to the data object.
							logData[ 'stuCount' ] = returnObj[ 'stuCount' ];

							//Make an array containing each server time element (day, hours, mins ect.).
							var serverTimeArray = returnObj[ 'serverTime' ].split( /[-T:]/ );

							//Make a javascript date object with the server time elements.
							var serverTime = new Date(
								serverTimeArray[ 0 ],
								serverTimeArray[ 1 ] - 1,
								serverTimeArray[ 2 ],
								serverTimeArray[ 3 ],
								serverTimeArray[ 4 ],
								serverTimeArray[ 5 ] );

							//The average human walking pace.
							const METERSPERSEC = 1.389;

							//For each returned log, decode the data and build up the usable log object.
							for ( var i = 0; i < returnObj[ 'logs' ].length; i++ ) {

								//Increment the student start count by one.
								logData[ 'startCount' ]++;

								//If the log states that the student last registered at the last checkpoint (i.e. finish point) then increment the checkpoint
								//student counter by one, and break. Else, we need to create a new student log to print. 
								if ( returnObj[ 'logs' ][ i ][ 'locID' ] == 4 ) {

									//Increment the student checkpoint counter by one.
									logData[ 'checkCount' ][ 4 ]++;

								} else {

									//Make an array containing each registration time element (day, hours, mins ect.).
									var timeStamp = returnObj[ 'logs' ][ i ][ 'regTime' ].split( /[- :]/ );

									//Make a javascript date object with the registration time elements.
									var regTime = new Date(
										timeStamp[ 0 ],
										timeStamp[ 1 ] - 1,
										timeStamp[ 2 ],
										timeStamp[ 3 ],
										timeStamp[ 4 ],
										timeStamp[ 5 ] );

									//Get the number of seconds since the student registered, according the the current server time.
									var secSinceReg = Math.round( ( serverTime - regTime ) / 1000 );

									//Calculate the distance the student has travelled since registering.
									var distance = Math.round( METERSPERSEC * secSinceReg );

									//Since the current server time may be ahead of the database timestamp time, we must account for this 
									//by setting any negative distance to zero meters.
									if ( distance < 0 ) distance = 0;

									//Add distance travelled since last registration to the distance of the last registration checkpoint from the
									//start line to get a total distance travelled.
									distance += checkpoints[ 'distance' ][ returnObj[ 'logs' ][ i ][ 'locID' ] ];

									//Since the student has to have travelled through checkpoint zero, increment the student checkpoint counter by one.
									logData[ 'checkCount' ][ 0 ]++;

									//The following 'if' block will increment student checkpoint counters by one, depending on what checkpoints have been passed
									//through. Each checkpoint counter will go up by one depending on the number of students who have passed through that
									//checkpoint.

									if ( returnObj[ 'logs' ][ i ][ 'locID' ] >= 1 ) {
										logData[ 'checkCount' ][ 1 ]++;
									}

									if ( returnObj[ 'logs' ][ i ][ 'locID' ] >= 2 ) {
										logData[ 'checkCount' ][ 2 ]++;
									}

									if ( returnObj[ 'logs' ][ i ][ 'locID' ] >= 3 ) {
										logData[ 'checkCount' ][ 3 ]++;
									}
									//End If Block

									//If the calculated distance is greater than the distance to the next checkpoint, set the distance to that value.
									//This accounts for the error where the distance travelled may be calculated to be greater than the distance to the next 
									//checkpoint, possibly leading to innacurate tracking data if the student is walking slower than expected.
									if ( distance > checkpoints[ 'distance' ][ returnObj[ 'logs' ][ i ][ 'locID' ] + 1 ] ) {
										distance = checkpoints[ 'distance' ][ returnObj[ 'logs' ][ i ][ 'locID' ] + 1 ];
									}

									//Push the calculated distance and student information to the log data distances array within the log data object.
									logData[ 'distances' ].push( new Object( {
										stuCode: returnObj[ 'logs' ][ i ][ 'stuCode' ],
										meters: distance,
										year: returnObj[ 'logs' ][ i ][ 'year' ]
									} ) );

								}

							}

							//Add the staff member and checkpoint location to log data object.
							logData[ 'checkStaff' ] = returnObj[ 'checkStaff' ];

							//After the asyncronous ajax call and processing has finished, continue to the next part of the code by calling the 
							//passed callback function with the log data object as the parameter.
							callBack( logData );
						}

					} );
				}

				function CalculateCoords( meters, yRad, xRad ) {
					//for a 16300 meter circuit
					const DEGPERMETER = 0.0220858896;
					//Nice Conversion Constant between deg and rad
					const DEGTORADS = Math.PI / 180;
					//Calculate the number of degrees through which the point moves
					var fullDeg = meters * DEGPERMETER;

					//'If' statement block to find out which quad we are in. Set a multiplier to adjust 
					//coords accordingly. Factor in the goofy co-ordinate system too. Calculate our adjusted quadrant 
					//specific angle, measured from the x-axis.
					var xMultiplier = 0;
					var yMultiplier = 0;

					var quadDeg = 0;

					if ( fullDeg <= 90 ) {
						//adjusted 1st Quad
						xMultiplier = -1;
						yMultiplier = 1;
						//Get adjusted angle
						quadDeg = 90 - fullDeg;
					} else if ( fullDeg > 90 && fullDeg <= 180 ) {
						//adjusted 2nd Quad
						xMultiplier = -1;
						yMultiplier = -1;
						//Get adjusted angle
						quadDeg = fullDeg - 90;
					} else if ( fullDeg > 180 && fullDeg <= 240 ) {
						//adjusted 3rd Quad
						xMultiplier = 1;
						yMultiplier = -1;
						//Get adjusted angle
						quadDeg = 270 - fullDeg;
					} else {
						//adjusted 4th Quad
						xMultiplier = 1;
						yMultiplier = 1;
						//Get adjusted angle
						quadDeg = fullDeg - 270;
					}

					var quadRad = quadDeg * DEGTORADS;

					//Radius at point equation, which is the polar form of the ellpise equation.
					var radiusAtPoint =
						xRad *
						yRad /
						Math.sqrt(
							Math.pow( yRad, 2 ) * Math.pow( Math.sin( quadRad ), 2 ) +
							Math.pow( xRad, 2 ) * Math.pow( Math.cos( quadRad ), 2 )
						);

					//Get are final x-coord using some Sine action!
					var xCoord = radiusAtPoint * Math.cos( quadRad );
					xCoord *= xMultiplier;

					//Get are final y-coord using some Sine action!
					var yCoord = radiusAtPoint * Math.sin( quadRad );
					yCoord *= yMultiplier;

					//Return a packaged object with our data inside.
					var returnObj = new Object();
					returnObj[ "x" ] = xCoord;
					returnObj[ "y" ] = yCoord;
					return returnObj;
				}

			} );
	</script>
	<?php } ?>
</head>

<header>
	<h1>Sponsored Walk!</h1>
	<nav>
		<ul>
			<li class="active">Overview</li>
			<li id="toManage">Manage</li>
		</ul>
	</nav>
</header>

<body>
	<!-- If user is any level, and the walk is in the Active state, the load the HTML to show the graphical 
    overview of the tracking data. -->
	<?php if($walkStatus == 2) {?>

	<div id="bodyDiv">
		<p style="text-align: center;">Welcome,
			<!-- Print the user name into the HTML title -->
			<?php echo $_SESSION['login'];?>
		</p>
		<div class="canvasWrap">
			<canvas id="trackingCanvas" width="1024" height="512" style="background-color:white;"></canvas>
			<div class="center sbsWrap">
				<table>
					<tr>
						<td>
							<div class="colourBox" style="background-color:#8000FF">&nbsp;</div>
						</td>
						<td>
							<div>Year 13</div>
						</td>
						<td>
							<div class="colourBox" style="background-color:#FF01DD">&nbsp;</div>
						</td>
						<td>
							<div>Year 12</div>
						</td>
						<td>
							<div class="colourBox" style="background-color:#0037FF">&nbsp;</div>
						</td>
						<td>
							<div>Year 11</div>
						</td>
						<td>
							<div class="colourBox" style="background-color:#00FF37">&nbsp;</div>
						</td>
						<td>
							<div>Year 10</div>
						</td>
						<td>
							<div class="colourBox" style="background-color:#FF0D00">&nbsp;</div>
						</td>
						<td>
							<div>Year 9</div>
						</td>
						<td>
							<div class="colourBox" style="background-color:#FF7300">&nbsp;</div>
						</td>
						<td>
							<div>Year 8</div>
						</td>
						<td>
							<div class="colourBox" style="background-color:#FFBF00">&nbsp;</div>
						</td>
						<td>
							<div>Year 7</div>
						</td>
					</tr>
				</table>
				<img src="../Project/Resources/info.png" class="launchInfo" alt="info">
			</div>
		</div>
		<h2>View Leg Information:</h2>
		<div id="panelContainer">
			<div class="togglePan top" id="tog1">
				<p><b>1. Soothill Foyer</b>
				</p>
			</div>
			<div class="panel" id="pan1">
				<div class="sbsWrap">
					<iframe width="45%" height="250" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=place_id:ChIJkbkmr09ReUgRiI-gV2HYJkc
					&key=AIzaSyD5aM9dwm_OV3Ktz1wif9t4er08-WZxZOo
					&maptype=satellite" allowfullscreen>
					</iframe>
				
					<div style="vertical-align: top;width:50%;">
						<h3 class="omitFromWrap"><b>Checkpoint Stats</b></h3>
						<p class="omitFromWrap"><b>Students Registered Here:</b>
						</p>
						<p class="omitFromWrap" id="check1">0/0</p>
						<p class="omitFromWrap"><b>Staff Registering Here:</b>
						</p>
						<p class="omitFromWrap" id="staffHere1">No-one Yet!</p>
					</div>
				</div>
			</div>
			<div class="togglePan" id="tog2">
				<p><b>2. Pot Bank</b>
				</p>
			</div>
			<div class="panel" id="pan2">
				<div class="sbsWrap">
					<iframe width="45%" height="250" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=place_id:ChIJr__hH5lWeUgRF6Bj3ufqWuc
				&key=AIzaSyD5aM9dwm_OV3Ktz1wif9t4er08-WZxZOo
				&maptype=satellite" allowfullscreen>
				</iframe>
				
					<div style="vertical-align: top;width:50%;">
						<h3 class="omitFromWrap"><b>Checkpoint Stats</b></h3>
						<p class="omitFromWrap"><b>Students Registered Here:</b>
						</p>
						<p class="omitFromWrap" id="check2">0/0</p>
						<p class="omitFromWrap"><b>Staff Registering Here:</b>
						</p>
						<p class="omitFromWrap" id="staffHere2">No-one Yet!</p>
					</div>
				</div>
			</div>
			<div class="togglePan" id="tog3">
				<p><b>3. Long Liberty</b>
				</p>
			</div>
			<div class="panel" id="pan3">
				<div class="sbsWrap">
					<iframe width="45%" height="250" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=place_id:ChIJuaggM-JVeUgRldxGHXtqtmY
				&key=AIzaSyD5aM9dwm_OV3Ktz1wif9t4er08-WZxZOo
				&maptype=satellite" allowfullscreen>
				</iframe>
				
					<div style="vertical-align: top;width:50%;">
						<h3 class="omitFromWrap"><b>Checkpoint Stats</b></h3>
						<p class="omitFromWrap"><b>Students Registered Here:</b>
						</p>
						<p class="omitFromWrap" id="check3">0/0</p>
						<p class="omitFromWrap"><b>Staff Registering Here:</b>
						</p>
						<p class="omitFromWrap" id="staffHere3">No-one Yet!</p>
					</div>
				</div>
			</div>
			<div class="togglePan" id="tog4">
				<p><b>4. Beckwithshaw</b>
				</p>
			</div>
			<div class="panel" id="pan4">
				<div class="sbsWrap">
					<iframe width="45%" height="250" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=place_id:ChIJo1Tok45WeUgRtACgrbu_cdc
				&key=AIzaSyD5aM9dwm_OV3Ktz1wif9t4er08-WZxZOo
				&maptype=satellite" allowfullscreen>
				</iframe>
				
					<div style="vertical-align: top;width:50%;">
						<h3 class="omitFromWrap"><b>Checkpoint Stats</b></h3>
						<p class="omitFromWrap"><b>Students Registered Here:</b>
						</p>
						<p class="omitFromWrap" id="check4">0/0</p>
						<p class="omitFromWrap"><b>Staff Registering Here:</b>
						</p>
						<p class="omitFromWrap" id="staffHere4">No-one Yet!</p>
					</div>
				</div>
			</div>
		</div>

		<div id="btnLogOut" class="btn center">
			Log Out
		</div>
		<div class="lowerFloat" id="toTracking">
			View Tracking Data
		</div>
	</div>

	<!-- If user is any level, and the walk is in the Waiting state, then load the HTML to show the timer. -->
	<?php } if($walkStatus == 1) {?>

	<div id="bodyDiv">
		<h1 id="timer" class="center largeText">Loading...</h1>

		<!-- If user is high level, load the HTML to show the cancel button. -->
		<?php if($_SESSION['level'] == 1) {?>

		<div id="btnCancel" class="btn large center">Cancel Countdown</div>

		<?php } ?>

		<div id="btnLogOut" class="btn center">
			Log Out
		</div>
	</div>

	<!-- If user is high level, and the walk is in the inactive state, the load the HTML to show the countdown start tools. -->
	<?php } if($_SESSION['level'] == 1 && $walkStatus == 0){?>

	<div id="bodyDiv">
		<div class="wrapper">
			<h2>Schedule Tracking Start</h2>
			<p>Select Launch Time. This is the time that the tracking will go live. It must be a date and time in the future. A countdown will commence to this time when you click 'Launch'.</p>
			<div class="sbsWrap">
				<h2>Launch Time: </h2>
				<input type="datetime-local" class="padInput" id="startTrackTime" min="<?php echo date(" Y-m-d\TH:i ");//Echo the server time as a minimum selction value.?>">
			</div>
			<p id="info" class="msg">&nbsp;</p>
			<div id="btnScheduleWalk" class="btn large center">
				Schedule Launch!
			</div>
			<h2>Or</h2>
			<div id="btnLaunchWalk" class="btn large center">
				Launch Now!
			</div>
			<div id="btnLogOut" class="btn center">
				Log Out
			</div>
		</div>
	</div>

	<!-- If user is low level, and the walk is in the inactive state, the load the HTML to show the friendly message. -->
	<?php } if($_SESSION['level'] == 0 && $walkStatus == 0) {?>

	<div id="bodyDiv">
		<h1>Check Back Later...</h1>
		<h2>Looks Like Tracking Hasnt Begun Just Yet. Come By Later For The Countdown!</h2>
		<div id="btnLogOut" class="btn center">
			Log Out
		</div>
	</div>

	<?php } ?>

	<div class="modal" id="infoModal">
		<div class="content">
			<h2>Tracking System Information</h2>
			<p>OVERVIEW PAGE - Here you can find a visual overview of the status of the walk. You can see an approximate position of everyone on the walk by observing the coloured dots making their way clockwise around the graphical representation of the walk. You can also explore each checkpint by clicking on its name or the grey bubble on the oval.</p>
			<p>TRACKING PAGE - By clicking on the 'View Tracking Data' button at the bottom of this page, you can see a table with all the exact registration events of each student on the walk. You can search for induvidual students or order the table to see who still to register at certain checkpoints.</p>
			<p>MANAGE PAGE - Here, you can print tracking QR codes, and if you have administrator permissions, make changes to who can view the sponsored walk tracker.</p>
			<div class="btn center" id="infoModalClose">Got It!</div>
		</div>
	</div>

	<div class="spinner" id="mainSpin" style="display:none;"></div>

</body>

</html>