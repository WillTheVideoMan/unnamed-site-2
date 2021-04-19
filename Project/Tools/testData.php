<?php

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

define( 'secureAccessParameter', true );
require '../../Project/Tools/config.php';

$conn = dbConnect();

$sqlQuery = $conn->prepare( "TRUNCATE tblStuStaffLocLink" );
$sqlQuery->execute();

$sqlQuery = $conn->prepare( "SELECT studentCode FROM tblStudents" );
$sqlQuery->execute();

$result = $sqlQuery->get_result();
?>

<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="main.css">
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script>
		$( document ).ready( function () {



			$( "#logTbl" ).on( 'click', '.btn', function () {
				var staff = $("#staff").val();
				$(this).prop("disabled",true);
				$.ajax( {
					url: '/Project/API/newData.php?stuCode=' + $( this ).data( "stu" ) + 
					"&staffCode=" + staff + "&locID=" + $( this ).data( "loc" ),
					success: function ( result ) {}
				} );
			} );

		} );
	</script>
</head>
	
<style>
	table
	{
		text-align:center;
	}
	tr, td
	{
		border:1px solid black;
	}
	</style>

<body>

	<table id="logTbl">
		<tr>
			<td>Student</td>
			<td>Soothill Start</td>
			<td>Pot Bank</td>
			<td>A Farm</td>
			<td>Another Farm</td>
			<td>Soothill Finish</td>
		</tr>

		<?php
		if ( $result->num_rows > 0 ) {
			while ( $dataRow = $result->fetch_assoc() ) {
				?>

		<tr>
			<td>
				<?php echo $dataRow['studentCode'];?>
			</td>
			<td><input type="button" class="btn" data-stu="<?php echo $dataRow['studentCode'];?>" data-loc="0" value="Log"></input>
			</td>
			<td><input type="button" class="btn" data-stu="<?php echo $dataRow['studentCode'];?>" data-loc="1" value="Log"></input>
			</td>
			<td><input type="button" class="btn" data-stu="<?php echo $dataRow['studentCode'];?>" data-loc="2" value="Log"></input>
			</td>
			<td><input type="button" class="btn" data-stu="<?php echo $dataRow['studentCode'];?>" data-loc="3" value="Log"></input>
			</td>
			<td><input type="button" class="btn" data-stu="<?php echo $dataRow['studentCode'];?>" data-loc="4" value="Log"></input>
			</td>
		</tr>

		<?php
		}
		}

		$sqlQuery->close();
		dbClose();

		?>

	</table>
<input type="text" id = "staff" value = "SWA">
</body>

</html>