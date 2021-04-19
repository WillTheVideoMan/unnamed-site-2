<?php
include "databaseHandler.php";
db_connect();

$currTbl = $_GET[tblSubmit];
$msg = "";

if ($currTbl != "") {
	$sql_insert = "INSERT INTO $currTbl(forename, surname) VALUES ('$_GET[forename]', '$_GET[surname]')";
    $db_link->query($sql_insert) or die($db_link->error);
	$msg = "'$_GET[forename]' has been added to the database";
} else {
	$msg = "No Table Selected! - Please return to reference a table...";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="myStyle.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class = "" id = "wrapper">
<p style = "text-align:left;padding-left:10px;">Adding To: <?php echo $_GET[tblSubmit];?></p>

<div style = "text-align:left;padding-left:10px;">
<form action = "newStudent.php" method = "get">
<input name="tblSubmit" type="hidden" value="<?php echo $_GET[tblSubmit];?>" />
<table width="200">
  <tr>
    <td>Surname</td>
    <td><input name = "surname" type = "text" required/></td>
  </tr>
  <tr>
    <td>Forename</td>
    <td><input name = "forename" type = "text"required/></td>
  </tr>
  <tr>
    <td>DOB</td>
    <td><input name = "DOB" type = "date" disabled/></td>
  </tr>
</table>

<br/>
<input name="btnSub" class = "btnStatic" type="submit" value = "Add" />

</form>

</div>

<p><?php echo $msg ?></p>

<a href = "databasesite.php"><div class ="btnSpread"><p>Return</p></div></a>	
        </div>
</div>
</body>

<script>
var wrapperDiv = document.querySelector("#wrapper");
	
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