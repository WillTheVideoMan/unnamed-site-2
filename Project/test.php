<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

define( 'secureAccessParameter', true );
require '../Project/Tools/config.php';
require '../Project/Tools/walkFunctions.php';

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="main.css">
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script>
		$( document ).ready( function () {
			for(let i = 1; i < 7; i++)
				{
					$(".data").on('mouseenter', '.col' + i, function(){
						$(".col" + i).addClass("highlight");
						var row = $(this).data("row");
						$(".row" + row).addClass("highlight");
					});
					$(".data").on('mouseleave', '.col' + i, function(){
						$(".col" + i).removeClass("highlight");
						var row = $(this).data("row");
						$(".row" + row).removeClass("highlight");
					});
				}
		});
	</script>
</head>

<body>
	<table class = "data center">
		<tr class = "row1">
			<td class = "col1" data-row = "1">Student Code</td>
			<td class = "col2" data-row = "1">Soothill Foyer</td>
			<td class = "col3" data-row = "1">Pot Bank</td>
			<td class = "col4" data-row = "1">Long Liberty</td>
			<td class = "col5" data-row = "1">Beckwithshaw</td>
			<td class = "col6" data-row = "1">Soothill Foyer</td>
		</tr>
		
		<?php 
		$conn = dbConnect();
		
		$prevStu = null;
		$counter = 0;
		$logArray = array();
		
		$sqlQuery = $conn->prepare( "SELECT COUNT(*) FROM tblStudents" );
		$sqlQuery->execute();
		$result = $sqlQuery->get_result();
		$countData = $result->fetch_all();
		$stuCount = $countData[0][0];
		
		$sqlQuery = $conn->prepare( "SELECT tblStudents.studentCode, tblStuStaffLocLink.locationID, tblStuStaffLocLink.regTime FROM tblStudents LEFT JOIN tblStuStaffLocLink ON tblStudents.studentCode = tblStuStaffLocLink.studentCode ORDER BY studentCode" );
		$sqlQuery->execute();
		$result = $sqlQuery->get_result();
		$data = $result->fetch_all();
		
		for($i = 0; $i < $result->num_rows; $i++)
		{	
			if($data[$i][0] != $prevStu)
			{
				$prevStu = $data[$i][0];
				$counter++;
				$logArray[$counter] = array("stuCode" => '', "logData" => array('', '', '', '', ''));
				$logArray[$counter]['stuCode'] = $data[$i][0];
			}
			
			$logArray[$counter]['logData'][$data[$i][1]] = substr($data[$i][2], 11, 5);
		}
		
		for($i = 1; $i < count($logArray); $i++) {?>
			
           <tr class = "row<?php echo $i + 1;?>">
            <td class = "col1" data-row = "<?php echo $i + 1;?>"><?php echo $logArray[$i]['stuCode'];?></td>
            
			<?php for($j = 0; $j < 5; $j++) {
				
			if(empty($logArray[$i]['logData'][$j]))
			{?>
            
             <td class = "col<?php echo ($j + 2);?> red" data-row = "<?php echo $i + 1;?>">-</td>
				
			<?php } else {
			?>
            
             <td class = "col<?php echo ($j + 2);?> green" data-row = "<?php echo $i + 1;?>"><?php echo $logArray[$i]['logData'][$j];?></td>
                
			<?php } } ?>
            
            </tr>
            
		<?php }  ?>	
		
	</table>
					
</body>
</html>