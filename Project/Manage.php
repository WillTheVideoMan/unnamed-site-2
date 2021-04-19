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
	<title>Manage Walk</title>
	<link rel="stylesheet" type="text/css" href="main.css">
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
	<link rel="icon" href="../Project/Resources/favicon.png" type="image/x-icon"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script>
		$( document )
			.ready( function () {

				$( "#bodyDiv" )
					.fadeIn( 500 );

				//When the div with the 'toOverview' ID is clicked, redirect to the overview page.
				$( "#toOverview" )
					.click( function () {
						$( "#bodyDiv" )
							.fadeOut( 150 );
						setTimeout(
							function () {
								window.location.href = "../Project/Overview";
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

				//When the div with the 'btnPrintCodes' ID is clicked, begin the print code function.
				$( "#btnPrintCodes" )
					.click( function () {

						//Reset information text
						$( "#info4" )
							.html( "&nbsp;" );

						//Display the printCodes modal
						$( "#printCodesModal" )
							.fadeIn( 150 );

						//When the print all checkbox is toggles, toggle the print specific student code text box
						//to either be enabled or disabled for text entry.
						$( "#printAll" )
							.on( 'click', function () {

								//If checked, then...
								if ( $( this )
									.is( ':checked' ) ) {

									//Disable text-box.
									$( "#printCodeName" )
										.prop( 'disabled', true );

								} else {

									//Enable text-box.
									$( "#printCodeName" )
										.prop( 'disabled', false );
								}
							} );

						//When the div with the 'printCodesModalConfirm' ID is clicked, begin the print launcher.
						$( "#printCodesModalConfirm" )
							.click( function () {

								//Define Variables to hold print request data.
								var params = "";
								var valid = true;

								//If the specific student code text box is populated and the print all box is not checked, then...
								if ( $( "#printCodeName" )
									.val() != "" && !$( "#printAll" )
									.is( ':checked' ) ) {

									//Define AJAX call to a PHP handler page. This handler checks to see if the user code we entered is valid/exists.
									$.ajax( {
										url: "../Project/API/studentExists?stuCode=" + $( "#printCodeName" )
											.val(),
										success: function ( result ) {

											//Parse the returned JSON response.
											var returnObject = JSON.parse( result );

											//If the return status is OK, then...
											if ( returnObject.status == "OK" ) {
												params = "?stuCode=" + $( "#printCodeName" )
													.val();

												//Try to launch the print page.
												tryPrint();

											} else {

												//Set valid boolean to false, then diplay an error to let the user know that the user code was invalid.
												valid = false;
												$( "#info4" )
													.html( "Error: Student " + $( "#printCodeName" )
														.val() + " does not exist! Check the student code and try again." );
											}
										}
									} );

									//Else if the print-all checkbox is not checked and no code has been entered.
								} else if ( !$( "#printAll" )
									.is( ':checked' ) ) {

									//Set valid boolean to false, then diplay an error to let the user know that they need to enter a user code.
									valid = false;
									$( "#info4" )
										.html( "Please Enter A Student Code!" );

								} else {

									//Try to launch the print page.
									tryPrint();
								}

								//A function to launch the print code page, by first checking to see if all validation checkes have been passed.
								function tryPrint() {
									if ( valid ) window.location.href = "../Project/printCodes" + params;
								}
							} );

						//When the div with the 'printCodesModalCancel' ID is clicked, close the print code modal.
						$( "#printCodesModalCancel" )
							.click( function () {
								$( "#printCodesModal" )
									.fadeOut( 150 );
							} );

					} );
			} );
	</script>
	<?php if($_SESSION['level'] == 1) { ?>
	<!--If user is high-level, include hi-level scripts-->
	<script>
		$( document )
			.ready( function () {

				//When the div with the 'btnEndWalk' ID is clicked, begin the end walk confirmation sequence.
				$( "#btnEndWalk" )
					.click( function () {

						//Reset information text
						$( "#info5" )
							.html( "&nbsp;" );

						//Reset the end walk confirmation password value.
						$( "#endWalkPass" )
							.val( "" );

						//Display the end walk modal.
						$( "#endModal" )
							.fadeIn( 150 );

						//When the div with the 'btnEndWalk' ID is clicked, begin the password checks.
						$( "#endModalConfirm" )
							.click( function () {

								//Capture the password value from the textbox.
								var userPass = $( "#endWalkPass" )
									.val();

								//Define AJAX call to a PHP handler page. This handler checks to see if the admin password we entered was correct.
								$.ajax( {
									url: "../Project/AJAX/passwordCheck",
									method: "POST",
									data: {
										pass: userPass
									},
									success: function ( result ) {

										//Parse the returned JSON response.
										var returnObject = JSON.parse( result );

										//If the return status is OK, then...
										if ( returnObject.status == "OK" ) {

											//Fade out current modal elements, to make space for the loading spinner.
											$( "#endWalkPass" )
												.fadeOut( 150 );
											$( "#endModalConfirm" )
												.fadeOut( 150 );
											$( "#endModalCancel" )
												.fadeOut( 150 );

											//Display the loading spinner.
											$( "#endModalSpin" )
												.fadeIn( 150 );

											//Update the information text to let the user know what the current process is.
											$( "#info5" )
												.html( "Ending Tracking Session..." );

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

													//Parse the returned JSON response.
													var returnObject = JSON.parse( result );

													//If the state change was made succesfully, then redirect to the overview page.
													if ( returnObject.status == "OK" ) {
														setTimeout(
															function () {
																$( "#bodyDiv" )
																	.fadeOut( 150 );
																setTimeout(
																	function () {
																		window.location.href = "../Project/Overview";
																	}, 150 );
															}, 1000 );

														//Else, if the state change failed, change the modal elements.
													} else {

														//Fade out the loading spinner.
														$( "#endModalSpin" )
															.fadeOut( 150 );

														//Fade in the cancel button.
														$( "#endModalCancel" )
															.fadeIn( 150 );

														//Update the information text to display an error.
														$( "#info5" )
															.html( "Error: Could Not End Tracking. Please Try Again." );
													}
												}
											} );

											//Else, if the password entered was incorrect, tell the user.
										} else {

											//Reset the password value.
											$( "#endWalkPass" )
												.val( "" );

											//Update the information text to display an error telling the user there password was incorrect.
											$( "#info5" )
												.html( returnObject.message );
										}
									}
								} );
							} );

						//When the div with the 'endModalCancel' ID is clicked, close the end walk modal.
						$( "#endModalCancel" )
							.click( function () {
								$( "#endModal" )
									.fadeOut( 150 );
							} );
					} );

				//When the div with the 'btnNewUser' ID is clicked, begin the add new user sequence.
				$( "#btnNewUser" )
					.click( function () {

						//If the new user code textbox is populated, and the permissions have been selected, then proceed.
						if ( $( "#newUserCode" )
							.val() != "" && $( "#newPermSelect" )
							.val() != "" ) {

							//Set variables to contain the new user information.
							var user = $( "#newUserCode" )
								.val();
							var perm = $( "#newPermSelect" )
								.val();

							//Reset the information text.
							$( "#info" )
								.html( "&nbsp;" );

							//Display a message to confirm the user to be added.
							$( "#addModalName" )
								.html( "Are you sure you want to add: '" + user + "'?" );

							//Show the add user modal buttons.
							$( "#addModalConfirm" )
								.show();
							$( "#addModalCancel" )
								.show();

							//Hide the modal loading spinner.
							$( "#addModalSpin" )
								.hide();

							//Set the user code entry textbox to have a black border, since through validation checks, it may be set to 
							//red to indicate a lack of text entry.
							$( "#newUserCode" )
								.css( "border-color", "black" );

							//Fade in the add user modal.
							$( "#addModal" )
								.fadeIn( 150 );

							//When the div with the 'addModalConfirm' ID is clicked, begin the new user checks.
							$( "#addModalConfirm" )
								.click( function () {

									//Fade out the add user modal buttons.
									$( "#addModalConfirm" )
										.fadeOut( 150 );
									$( "#addModalCancel" )
										.fadeOut( 150 );

									//Fade in the loading spinner.
									$( "#addModalSpin" )
										.fadeIn( 150 );

									//Display a confirmation message of user invitation.
									$( "#addModalName" )
										.html( "Inviting " + user + "!" );

									//Define AJAX call to a PHP handler page. This handler checks to see if the new user already exists on the database, and
									//if not, then invites the user to the tracking system.
									$.ajax( {
										url: "../Project/AJAX/inviteNewUser",
										method: "POST",
										data: {
											newUname: user,
											newLevel: perm
										},
										success: function ( result ) {
											//Parse the returned JSON response.
											var returnObject = JSON.parse( result );

											//If the return status is OK, then...
											if ( returnObject.status == "OK" ) {

												$( "#addModalName" )
													.html( "Success!" );
												setTimeout(
													function () {
														$( "#addModal" )
															.fadeOut( 150 );
													}, 1000 );

												//Else, display an error letting the user know that they are trying to add a user that already exists.
											} else {
												$( "#addModalName" )
													.html( " Error:  " + user + " already exists! Please try a different user!" );
												$( "#addModalCancel" )
													.fadeIn( 150 );
												$( "#addModalSpin" )
													.fadeOut( 150 );

											}
										}
									} );
								} );

							//When the div with the 'addModalCancel' ID is clicked, close the add new user modal.
							$( "#addModalCancel" )
								.click( function () {
									$( "#addModal" )
										.fadeOut( 150 );
								} );

							//Else, if the user has not specified a usercode or permission, display a message to promt the user to try again.
						} else {

							//Set the textbox border colour to red to indicate faliure.
							$( "#newUserCode" )
								.css( "border-color", "red" );

							//Display the error message.
							$( "#info" )
								.html( "Please Enter User and Permission" );
						}
					} );

				//When the div with the 'btnUpUser' ID is clicked, begin the update user permissions sequence.
				$( "#btnUpUser" )
					.click( function () {

						//If the user has selected a user, and a new permission for that user, the proceed.
						if ( $( "#userSelect" )
							.val() != "" && $( "#permissionSelect" )
							.val() != "" ) {

							//Set variables to contain the new user information.
							var user = $( "#userSelect" )
								.val();
							var perm = $( "#permissionSelect" )
								.val();

							//Define AJAX call to a PHP handler page. This handler updates the user permissions, by recieving a
							//user and new permission, and updating the entry on the database.
							$.ajax( {
								url: "../Project/AJAX/updateUserPerms",
								method: "POST",
								data: {
									uName: user,
									uLevel: perm
								},
								success: function ( result ) {

									//Parse the returned JSON response.
									var returnObject = JSON.parse( result );

									//If the return status is OK, then...
									if ( returnObject.status == "OK" ) {
										$( "#info2" )
											.html( "Updated Permissions For " + user );

										//Else, display an error message.
									} else {
										$( "#info2" )
											.html( "Error: Could Not Update Permissions..." );
									}
								}
							} );

							//Else, if the selection boxes are empty, display an error message to let the user know what to do.
						} else {
							$( "#info2" )
								.html( "Please Select User and Permission" );
						}
					} );

				//When the div with the 'btnRemoveUser' ID is clicked, begin the remove user sequence.
				$( "#btnRemoveUser" )
					.click( function () {

						//If the selection is not populated.
						if ( $( "#removeUserSelect" )
							.val() != "" ) {

							//Set variable to capture the selection value.
							var user = $( "#removeUserSelect" )
								.val();

							//Reset the information text.
							$( "#info3" )
								.html( "&nbsp;" );

							//Display a message to confirm the removal of the user.
							$( "#removeModalName" )
								.html( "Are you sure you want to remove: '" + user + "' ?" );

							//Fade in the remove user modal.
							$( "#removeModal" )
								.fadeIn( 150 );

							//When the div with the 'removeModalConfirm' ID is clicked, remove the user.
							$( "#removeModalConfirm" )
								.click( function () {

									//Fade out the confirmation button.
									$( "#removeModalConfirm" )
										.fadeOut( 150 );
									$( "#removeModalCancel" )
										.fadeOut( 150 );

									//Fade in the loading spinner.
									$( "#removeModalSpin" )
										.fadeIn( 150 );

									//Define AJAX call to a PHP handler page. This handler removes the selected user from the users database table.
									$.ajax( {
										url: "../Project/AJAX/removeUser",
										method: "POST",
										data: {
											uName: user
										},
										success: function ( result ) {

											//Parse the returned JSON response.
											var returnObject = JSON.parse( result );

											//If the return status is OK, then...
											if ( returnObject.status == "OK" ) {

												$( "#removeModalName" )
													.html( "Removed " + user + ". Refreshing... Please Wait!" );
												setTimeout(
													function () {
														$( "#removeModal" )
															.fadeOut( 150 );
														$( "#bodyDiv" )
															.fadeOut( 150 );
														location.reload( true );
													}, 1000 );

												//Else, display an error message telling the user to try again.
											} else {

												//Display the error message.
												$( "#removeModalName" )
													.html( "Error: Could Not Remove User..." );

												//Fade out the loading spinner.
												$( "#removeModalSpin" )
													.fadeOut( 150 );

												//Fade in the modal cancel button.
												$( "#removeModalCancel" )
													.fadeIn( 150 );
											}
										}
									} );
								} );

							//When the div with the 'removeModalCancel' ID is clicked, close the remove user modal.
							$( "#removeModalCancel" )
								.click( function () {
									$( "#removeModal" )
										.fadeOut( 150 );
								} );

							//Else, if the selection was null, display an error message.
						} else {
							$( "#info3" )
								.html( "Please Select User." );
						}
					} );

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
			} );
	</script>
	<?php } ?>
</head>

<header>
	<h1>Sponsored Walk!</h1>
	<nav>
		<ul>
			<li id="toOverview">Overview</li>
			<li class="active">Manage</li>
		</ul>
	</nav>
</header>

<body>
	<div id="bodyDiv">
		
		<p style="text-align: center;">Welcome,
			<!-- Print the user name into the HTML title -->
			<?php echo $_SESSION['login'];?>
		</p>

		<h2>Student Tracking Codes</h2>
		<div class="wrapper">
			<h3>Print QR Code For Student</h3>
			<div class="btn" id="btnPrintCodes">Print!</div>
		</div>

		<!-- If the user is high level, include the user account management -->
		<?php if($_SESSION['level'] == 1) { ?>

		<h2>User Controls</h2>
		<div class="wrapper">
			<h3>Add New User:</h3>
			<p>Invite anyone with an Ashville Email Address to access the Tracking Suite:</p>
			<h4>Email Address</h4>
			<div class="sbsWrap">
				<input type="text" class="inText short" id="newUserCode" placeholder="USER">
				<p>@ashville.co.uk</p>
			</div>

			<h4>Permissions:</h4>
			<div class="sbsWrap">
				<select id="newPermSelect">
					<option value="">Select Permission...</option>
					<option value="0">View and Track</option>
					<option value="1">Full Administrator Access</option>
				</select>
				<img src="../Project/Resources/info.png" class="launchInfo" alt="info">
			</div>

			<div id="btnNewUser" class="btn">
				Invite User!
			</div>

			<p id="info" class="msg">&nbsp;</p>

			<hr>

			<h3>Update Current User Permissions:</h3>

			<div class="sbsWrap">
				<p>Change &nbsp;</p>
				<select id="userSelect">
					<option value="">Select User...</option>

					<?php 
						//Define and create a database correction.
						$conn = dbConnect();
						
						//Prepare an SQL statement to select all usernames from the admin user table.
						$sqlQuery = $conn->prepare( "SELECT uName FROM tblAdminUsers");
						
						//Run the SQL query.
						$sqlQuery->execute();

						//Set variable to the SQL result object.
						$result = $sqlQuery->get_result();
	
						//If the result has more than zero rows
						if ( $result->num_rows > 0 ) 
						{
							//Set a variable to store the data rows as an array.
							$data = $result->fetch_all();
							
							//For each row of the data
							foreach($data as $dataRow)
							{	
								//If the user is not the curretly logged in user, then echo an option into the HTML
								//document to contain that user.
								if($dataRow[0] != $_SESSION['login'])
								{?>

					<option value="<?php echo $dataRow[0]; ?>">
						<?php echo $dataRow[0]; ?>
					</option>

					<?php
					}
					}
					}

					//Close the prepared statement query.
					$sqlQuery->close();

					//Close the database connection.
					dbClose();
					?>

				</select>

				<p>&nbsp; to &nbsp;</p>


				<select id="permissionSelect">
					<option value="">Select Permission...</option>
					<option value="0">View and Track</option>
					<option value="1">Full Administrator Access</option>
				</select>

				<img src="../Project/Resources/info.png" class="launchInfo" alt="info">

			</div>

			<div id="btnUpUser" class="btn">
				Update User
			</div>

			<p id="info2" class="msg">&nbsp;</p>

			<hr>

			<h3>Remove User:</h3>
			<select id="removeUserSelect">
				<option value="">Select User...</option>
				<?php 
					
					//For each row of the data
					foreach($data as $dataRow)
					{	
						//If the user is not the curretly logged in user, then echo an option into the HTML
						//document to contain that user.
						if($dataRow[0] != $_SESSION['login'])
						{?>

				<option value="<?php echo $dataRow[0]; ?>">
					<?php echo $dataRow[0]; ?>
				</option>

				<?php
				}
				}
				?>
			</select>
			<div id="btnRemoveUser" class="btn">
				Remove!
			</div>
			<p id="info3" class="msg">&nbsp;</p>
		</div>

		<?php }?>

		<div id="btnLogOut" class="btn center">
			Log Out
		</div>
	</div>

	<!-- If the user is high level, and the walk is the Active State, include the walk ending button -->
	<?php if($walkStatus == 2 && $_SESSION['level'] == 1) {?>

	<div id="btnEndWalk" class="lowerFloat">
		End Tracking!
	</div>

	<?php } ?>

	<div class="spinner" id="mainSpin" style="display:none;"></div>

	<!-- If the user is high level, include the user account management modals -->
	<?php if($_SESSION['level'] == 1) {?>

	<div class="modal" id="removeModal">
		<div class="content">
			<h2>Confirm User Removal?</h2>
			<p id="removeModalName">&nbsp;</p>
			<div class="miniSpin center" id="removeModalSpin" style="display:none;"></div>
			<div class="sbsWrap">
				<div class="btn center" id="removeModalConfirm">Confirm?</div>
				<div class="btn center" id="removeModalCancel">Cancel?</div>
			</div>
		</div>
	</div>
	<div class="modal" id="addModal">
		<div class="content">
			<h2>Confirm Invite User?</h2>
			<p id="addModalName">&nbsp;</p>
			<div class="miniSpin center" id="addModalSpin" style="display:none;"></div>
			<div class="sbsWrap">
				<div class="btn center" id="addModalConfirm">Confirm?</div>
				<div class="btn center" id="addModalCancel">Cancel?</div>
			</div>
		</div>
	</div>
	<div class="modal" id="infoModal">
		<div class="content">
			<h2>Permission Info</h2>
			<p>View and Track - Users can View tracking data and search for students.</p>
			<p>Full Administrator Access - All permissions, including starting and stopping tracking, and adding new users.</p>
			<div class="btn center" id="infoModalClose">Got It!</div>
		</div>
	</div>
	<div class="modal" id="endModal">
		<div class="content">
			<h2>End Sponsored Walk Tracking?</h2>
			<p id="endModalText">Are You Sure You Want To Stop Tracking? This Action Will De-activate all tracking stations, delete any tracking logs, and reset the sponsored walk tracking suite. Only end tracking when ALL STUDENTS have completed the walk.</p>
			<input type="password" class="inText" id="endWalkPass" placeholder="PASSWORD">
			<div class="miniSpin center" id="endModalSpin" style="display:none;"></div>
			<div class="sbsWrap">
				<div class="btn center" id="endModalConfirm">Confirm?</div>
				<div class="btn center" id="endModalCancel">Cancel?</div>
			</div>
			<p id="info5" class="msg">&nbsp;</p>
		</div>
	</div>

	<?php } ?>

	<div class="modal" id="printCodesModal">
		<div class="content">
			<h2>Print QR Code For Student</h2>
			<p id="printCodesModalText">Choose Your QR Code Printing Options Here:</p>
			<input type="checkbox" id="printAll" unchecked>Print QR Code For All Students?</input>
			<input type="text" class="inText" id="printCodeName" placeholder="STUDENT CODE">
			<div class="miniSpin center" id="printCodesModalSpin" style="display:none;"></div>
			<div class="sbsWrap">
				<div class="btn center" id="printCodesModalConfirm">Confirm?</div>
				<div class="btn center" id="printCodesModalCancel">Cancel?</div>
			</div>
			<p id="info4" class="msg">&nbsp;</p>
		</div>
	</div>
</body>

</html>