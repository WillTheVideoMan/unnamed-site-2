<?php
include ("databaseHandler.php");
db_connect();
?>

<table width="500" border="1" style = "margin:auto;">
 <tr>
    <th>Student ID</th>
    <th>Forename</th>
    <th>Surname</th>
    <th>DOB</th>
  </tr>

<?php
$sql_statement = "SELECT * FROM tblStudents ORDER BY studentID";
$query_results = $db_link->query($sql_statement) or die("This did not work Prining Table");

if($query_results->num_rows>0)
{
	while($data_row = $query_results->fetch_assoc()){
if ($data_row[studentID] == $_REQUEST[studentID])
 { ?>
 
 <tr style = "background-color:#314E72;color:white;">
      <td><?php echo $data_row[studentID]?></td>
	  <td><?php echo $data_row[forename]?></td>
      <td><?php echo $data_row[surname]?></td>
      <td><?php echo $data_row[DOB]?></td>
  </tr>
  
 <?php } else { ?>
 
  <tr>
      <td><?php echo $data_row[studentID]?></td>
	  <td><?php echo $data_row[forename]?></td>
      <td><?php echo $data_row[surname]?></td>
      <td><?php echo $data_row[DOB]?></td>
  </tr>

<?php }}} ?>

</table>