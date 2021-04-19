<?php 
include ("databaseHandler.php");
db_connect();
$currAction = $_POST[action];

if ($currAction == "reset")
{
	$sql_statement = "TRUNCATE TABLE tblStudents";
    $db_link->query($sql_statement) or die("This did not work trunking");
	
	$sql_statement = "TRUNCATE TABLE tblStudentClassLink";
    $db_link->query($sql_statement) or die("This did not work trunking");
	
	$sql_statement = "INSERT INTO `tblStudents` (`studentID`, `forename`, `surname`, `DOB`) VALUES
(1, 'Harry', 'Roberts', '2000-06-05'),
(2, 'Will', 'Hall', '2000-08-10'),
(3, 'Sean', 'Davis', '2000-04-01'),
(4, 'Rhys', 'Meyer', '2000-06-10'),
(5, 'Joe', 'Lang', '2017-02-01'),
(6, 'Jason', 'Kwan', '1853-01-01'),
(7, 'Cone', 'R', '1999-11-26'),
(8, 'Juliette', 'Zhao ', '2000-12-01'),
(9, 'Glen', 'Mooreland ', '0001-01-01'),
(10, 'Martin', 'Costa', '2008-09-12');";
    $db_link->query($sql_statement) or die("This did not work trunking");
	
	$sql_statement = "INSERT INTO `tblStudentClassLink` (`classID`, `studentID`) VALUES
(5, 1),(7, 1),(8, 1),(1, 2),(4, 2),(5, 2),(8, 2),(1, 3),(8, 3),(5, 3),(7, 4),(8, 4),(16, 5),(1, 6),(2, 6),(3, 6),(4, 6),(5, 6),(6, 6),(7, 6),(8, 6),(9, 6),(11, 6),(5, 7),(7, 7),(8, 7),(7, 8),(10, 9),(5, 10),(7, 10),(12, 10), (16, 10);";
    $db_link->query($sql_statement) or die("This did not work addingstudents");
	}

if ($currAction == "add")
{
	//Add the new student here
	$sql_statement = "INSERT INTO tblStudents(forename, surname, DOB) VALUES ('$_POST[newForename]', '$_POST[newSurname]', '$_POST[newDOB]')";
    $db_link->query($sql_statement) or die("This did not work Adding");
	
	//find the student ID of the just added student.
	$sql_statement = "SELECT studentID FROM tblStudents ORDER BY studentID DESC LIMIT 1";
    $query_results = $db_link->query($sql_statement) or die("This did not work Finding Largest");
	$currID = $query_results->fetch_assoc();
	
	//Add each selected class to the approproate LINK table
if($_POST['newClassSelect'] != ""){
	foreach ($_POST['newClassSelect'] as $nextClassID) 
	{
		$sql_statement = "INSERT INTO tblStudentClassLink(studentID, classID) VALUES ('$currID[studentID]', '$nextClassID')";
         $db_link->query($sql_statement) or die("This did not work Adding The Classes");
	}
}
}

if ($currAction == "remove")
{
	//remove student from the student Database
	$sql_statement = "DELETE FROM tblStudents WHERE studentID = '$_POST[studentID]'";
    $db_link->query($sql_statement) or die("This did not work");
	
	//remove student from the Link Database
	$sql_statement = "DELETE FROM tblStudentClassLink WHERE studentID = '$_POST[studentID]'";
    $db_link->query($sql_statement) or die("This did not work");
	
} 

if ($currAction == "update")
{
	//update student credentials in the student database
	$sql_statement = "UPDATE tblStudents SET surname = '$_POST[upSurname]', forename = '$_POST[upForename]', DOB = '$_POST[upDOB]' WHERE studentID = '$_POST[studentID]'";
    $db_link->query($sql_statement) or die($db_link->error);
	
	//remove student classes from the Link Database
	$sql_statement = "DELETE FROM tblStudentClassLink WHERE studentID = '$_POST[studentID]'";
    $db_link->query($sql_statement) or die($db_link->error);
	
	//ONLY ADD THE Chosen updated class.
	if($_POST['upClassSelect'] != ""){
	foreach ($_POST['upClassSelect'] as $nextClassID) 
	{
		$sql_statement = "INSERT INTO tblStudentClassLink(studentID, classID) VALUES ('$_POST[studentID]', '$nextClassID')";
         $db_link->query($sql_statement) or die($db_link->error);
	}
}
} 

?>

<!DOCTYPE html>
<head>
<link href="myStyle.css" rel="stylesheet" type="text/css">
<title>Database Zone</title>

</head>
<html>
<body>
<div class = "" id = "wrapper">
<h1>Welcome To The Student Database!</h1>

<div style = "padding:10px;width:500px;display:inline-block;margin-top:10px;">
<h2>Add New Students</h2>

<form action = "MyDatabaseViewer.php" method = "post" id = "newStuForm">
<input type="hidden" name = "action" value = "add">
<table width="200" border="1" style = "margin:auto;">
  <tr>
    <td>Forename</td>
    <td>
     <input name="newForename" type="text" id="newForID" oninput="addText()" required>
     <div class = "miniMessageOff" id = "toolTip1">Empty...</div>
    </td>
  </tr>
  <tr>
    <td>Surname</td>
    <td><input name="newSurname" type="text" id="newSurID" oninput="addText()" required>
    <div class = "miniMessageOff" id = "toolTip2">Empty...</div>
    </td>
  </tr>
  <tr>
    <td>DOB</td>
    <td><input name="newDOB" type="date" id="newDOBID">
    <div class = "miniMessageOff" id = "toolTip2">Empty...</div>
    </td>
  </tr>
</table>

<h3>Choose Classes:</h3>

<table width="500px" style = "text-align:left;">
<tr>
<?php
	$sql_statement = "SELECT * FROM tblclasses ORDER BY subject";
    $query_results = $db_link->query($sql_statement) or die("This did not work Choose Class");
	$checkRow = 0;
	
	if($query_results->num_rows>0)
	{ 	
		while($data_row = $query_results->fetch_assoc()){ 
			
			 if($checkRow < 3)
			{
	?>
    <td><label>
      <input type="checkbox" name="newClassSelect[]" value="<?php echo $data_row[classID];?>" id="selectCheckbox">
		<?php echo $data_row[subject];?></label></td>
		
		<?php $checkRow = $checkRow + 1; ?>
  <?php } else { ?>
	</tr>
 	<tr>
 		<td><label>
      <input type="checkbox" name="newClassSelect[]" value="<?php echo $data_row[classID];?>" id="selectCheckbox">
		<?php echo $data_row[subject];?></label></td>
 		
  		<?php $checkRow = 1; }}}?>
  </tr>
</table>

</form>

<div class = "btnStatic" style = "width:90px;height:30px;margin-top:8px;" id = "stripTrig1"><p id = "btnTrip1">Add</p></div>

<div class = "stripDeactive pos" id = "strip1" style = "margin-top:5px";>
    <p style = "margin-top:1px;">Are You Sure?</p>
     <div class = "stripDeactiveBtn pos" id = "stripBtn1" >
     Confirm?
     </div>
     <p id="demo"></p>
</div>

</div>

<div style = "padding:10px;width:500px;display:inline-block;margin-top:10px;">
<h2>Edit Existing</h2>

<form action = "MyDatabaseViewer.php" method = "post" id = "getStuForm">
<select name="studentID" id = "editSelect" required>
</select>
</form>

<form action = "MyDatabaseViewer.php" method = "post" id = "remStuForm">
<input type="hidden" name = "studentID" value = "<?php echo $_POST[studentID];?>">
<input type="hidden" name = "action" value = "remove">

<?php
if(isset($_POST[studentID]) && $_POST[studentID] != "") {
$sql_statement = "SELECT * FROM tblStudents WHERE studentID = $_POST[studentID]";
$query_results = $db_link->query($sql_statement) or die("This did not work Filling Form");
$data_row = $query_results->fetch_assoc();
?>

<div class = "btnNeg" style = "width:125px;height:30px;margin-top:8px;" id = "stripTrig2"><p id = "btnTrip2">Remove</p></div>

<div class = "stripDeactive neg" id = "strip2" style = "margin-top:5px";>
    <p style = "margin-top:1px";>Are You Sure?</p>
     <div class = "stripDeactiveBtn neg" id = "stripBtn2">
     Confirm?
     </div>
     <p>Removing: <?php echo $data_row[forename];?> <?php echo $data_row[surname];?></p>
</div>
<br>
	</form>

<form action = "MyDatabaseViewer.php" method = "post" id = "upStuForm">
<input type="hidden" name = "studentID" value = "<?php echo $_POST[studentID];?>">
<input type="hidden" name = "action" value = "update">
<table width="200" border="1" style = "margin:auto;">
  <tr>
    <td>Forename</td>
    <td>
     <input name="upForename" type="text" value = "<?php echo $data_row[forename];?>">
    </td>
  </tr>
  <tr>
    <td>Surname</td>
    <td><input name="upSurname" type="text" value = "<?php echo $data_row[surname]?>"></td>
  </tr>
   <tr>
    <td>DOB</td>
    <td><input name="upDOB" type="date" value = "<?php echo $data_row[DOB]?>"></td>
  </tr>
</table>

<h3>Change Classes:</h3>

<table width="500px" style = "text-align:left;">
<tr>
<?php
	$sql_statement1 = "SELECT * FROM tblclasses ORDER BY subject"; 
	$query_results1 = $db_link->query($sql_statement1) or die("This did not work Change Classes");
			
	$checkRow = 0;
	
	if($query_results1->num_rows>0)
	{ 	
		while($data_row1 = $query_results1->fetch_assoc()){ 
			
			 if($checkRow < 3)
			{
	?>
    <td><label>
      <input type="checkbox" name="upClassSelect[]" value="<?php echo $data_row1[classID];?>" id="selectCheckbox" <?php 
				
			if(isset($_POST[studentID]) && $_POST[studentID] != "") {
				$sql_statement2 = "SELECT * FROM tblStudentClassLink WHERE studentID = $_POST[studentID] ORDER BY classID";
				$query_results2 = $db_link->query($sql_statement2) or die("This did not work"); }
				
		while($data_row2 = $query_results2->fetch_assoc())
	{
		if ($data_row1[classID] == $data_row2[classID]) {
			echo " checked";
		}
	}
		?>>
		<?php echo $data_row1[subject];?></label></td>
		
		<?php $checkRow = $checkRow + 1; ?>
  <?php } else { ?>
	</tr>
 	<tr>
 		<td><label>
      <input type="checkbox" name="upClassSelect[]" value="<?php echo $data_row1[classID];?>" id="selectCheckbox" <?php 
				
			if(isset($_POST[studentID]) && $_POST[studentID] != "") {
				$sql_statement2 = "SELECT * FROM tblStudentClassLink WHERE studentID = $_POST[studentID] ORDER BY classID";
				$query_results2 = $db_link->query($sql_statement2) or die("This did not work"); }
				
		while($data_row2 = $query_results2->fetch_assoc())
	{
		if ($data_row1[classID] == $data_row2[classID]) {
			echo " checked";
		}
	}
		?>>
		<?php echo $data_row1[subject];?></label></td>
 		
  		<?php $checkRow = 1; }}}?>
  </tr>
  
</table>
	</form>

<div class = "btnStatic" style = "width:125px;height:30px;margin-top:8px;" id = "stripTrig3"><p>Update</p></div>

<div class = "stripDeactive pos" id = "strip3" style = "margin-top:5px";>
    <p style = "margin-top:1px";>Are You Sure?</p>
     <div class = "stripDeactiveBtn pos" id = "stripBtn3">
     Confirm?
     </div>
     <p>Updating: <?php echo $data_row[forename];?> <?php echo $data_row[surname];?></p>
</div>
<?php } ?>
</div>

<div style = "padding:10px;max-width:500px;margin-top:10px;display:block;">
<h2>View Database</h2>
<div id = "studentTbl"></div>

<form action = "MyDatabaseViewer.php" method = "post" id = "resetDatabase">
<input type="hidden" name = "action" value = "reset">
</form>

<div class = "btnNeg" style = "width:150px;" id = "btnResetDB"><p>Idiot Button</p></div>

</div>

</div>

</body>

<script>
var currStudent;

	function makeRequest(target, objID)
{
	var xHtml = new XMLHttpRequest();
	var obj = document.getElementById(objID);
	xHtml.open("GET", target);
	xHtml.onreadystatechange = function() {
		if(xHtml.readyState == 4 && xHtml.status == 200) {
			obj.innerHTML = xHtml.responseText;
		}
	}
	xHtml.send(null);
}

window.onload = function(){
	makeRequest('editStudentSelect.php?studentID=', 'editSelect');
	makeRequest('ajaxInfo.php?studentID=', 'studentTbl');
	}

document.getElementById("editSelect").addEventListener("change", function() 
{
	currStudent = document.getElementById("editSelect").value;
	makeRequest('editStudentSelect.php?studentID=' + currStudent, 'editSelect');
	makeRequest('ajaxInfo.php?studentID=' + currStudent, 'studentTbl');
	});

	if (localStorage.getItem("selectedTheme") === null) {
  	localStorage.setItem("selectedTheme", "theme0");
    }
	
    var wrapperDiv = document.querySelector("#wrapper");
	var stripDiv1 = document.querySelector("#strip1");
	var stripBtn1 = document.querySelector("#stripBtn1");
	var stripDiv2 = document.querySelector("#strip2");
	var stripBtn2 = document.querySelector("#stripBtn2");
	var stripDiv3 = document.querySelector("#strip3");
	var stripBtn3 = document.querySelector("#stripBtn3");
	
	document.getElementById("btnResetDB").addEventListener("click", function(){ 
	document.getElementById("resetDatabase").submit();})
	
	document.getElementById("stripTrig1").addEventListener("click", function(){ stripActivator(stripDiv1, stripBtn1, 'btnTrip1', 'Add');})
	
	document.getElementById("stripBtn1").addEventListener("click", function(){ 
	if(document.getElementById("newForID").value != "" 
	&& document.getElementById("newSurID").value != ""
	&& document.getElementById("newDOBID").value != "")
	{
	document.getElementById("newStuForm").submit();
	} else 
	{
		document.getElementById("demo").innerHTML = "Cannot leave blank...";
	}})
	
	if (document.getElementById("stripTrig2") !== null) {
	document.getElementById("stripTrig2").addEventListener("click", function(){ stripActivator(stripDiv2, stripBtn2, 'btnTrip2', 'Remove');}) }
	
	if (document.getElementById("stripTrig3") !== null) {
	document.getElementById("stripTrig3").addEventListener("click", function(){ stripActivator(stripDiv3, stripBtn3, 'btnTrip3', 'Update');}) }
	
	if (document.getElementById("stripBtn2") !== null) {
	document.getElementById("stripBtn2").addEventListener("click", function(){ 
	document.getElementById("remStuForm").submit();}) }
	
	if (document.getElementById("stripBtn3") !== null) {
	document.getElementById("stripBtn3").addEventListener("click", function(){ 
	document.getElementById("upStuForm").submit();}) }

function stripActivator(stripDivision, stripBtn, triggerID, triggerText) 
	{
        stripDivision.classList.toggle("stripDeactive");
		stripDivision.classList.toggle("stripActive");
		stripBtn.classList.toggle("stripDeactiveBtn");
		stripBtn.classList.toggle("stripActiveBtn");
	}
	
	function addText() {
    var forename = document.getElementById("newForID").value;
	var surname =  document.getElementById("newSurID").value;
    document.getElementById("demo").innerHTML = "Adding: " + forename + " " + surname;
}
	
	if (localStorage.getItem("selectedTheme") == "theme1")
		{
			wrapperDiv.classList.remove("wrapTheme0");
			wrapperDiv.classList.remove("wrapTheme2");
			wrapperDiv.classList.remove("wrapTheme3");
			wrapperDiv.classList.add("wrapTheme1");
		}
	
	if (localStorage.getItem("selectedTheme") == "theme2")
		{
			wrapperDiv.classList.remove("wrapTheme0");
			wrapperDiv.classList.remove("wrapTheme1");
			wrapperDiv.classList.remove("wrapTheme3");
			wrapperDiv.classList.add("wrapTheme2");
		}
	
	if (localStorage.getItem("selectedTheme") == "theme3")
		{
			wrapperDiv.classList.remove("wrapTheme0");
			wrapperDiv.classList.remove("wrapTheme2");
			wrapperDiv.classList.remove("wrapTheme1");
			wrapperDiv.classList.add("wrapTheme3");
		}
	
	if (localStorage.getItem("selectedTheme") == "theme0")
		{
			wrapperDiv.classList.remove("wrapTheme1");
			wrapperDiv.classList.remove("wrapTheme2");
			wrapperDiv.classList.remove("wrapTheme3");
			wrapperDiv.classList.add("wrapTheme0");
		}
	
</script>
</html>