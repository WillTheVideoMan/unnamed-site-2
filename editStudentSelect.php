<?php
include ("databaseHandler.php");
db_connect();
?>

<option <?php if(isset($_REQUEST[studentID]) && $_REQUEST[studentID] == "") 
   { echo " selected";};?>>
   <?php if($_REQUEST[studentID] == "") { echo "Select Student";};?></option>
   
    <?php
	$sql_statement = "SELECT * FROM tblStudents ORDER BY studentID";
    $query_results = $db_link->query($sql_statement) or die("This did not work Select Option");
	if($query_results->num_rows>0)
	{
		while($data_row = $query_results->fetch_assoc()){
		?>
        <option value="<?php echo $data_row[studentID];?>"
        <?php 
		if($data_row[studentID]==$_REQUEST[studentID]) echo " selected"?>>
		<?php echo "$data_row[studentID]: $data_row[forename] $data_row[surname]"?></option>
        <?php }}?>
        