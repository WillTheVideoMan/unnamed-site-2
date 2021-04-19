<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>leave me</title>
<link href="myStyle.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action = "MyDatabaseViewer.php" method = "get" id = "trunkate">
<input type="hidden" name = "action" value = "trunk">
</form>

<div class = "btnNeg" style = "width:150px;" id = "btnTrunk"><p>Idiot Button</p></div>
</body>
<script>

	document.getElementById("btnTrunk").addEventListener("click", function(){ 
	document.getElementById("trunkate").submit();}) 

</script>
</html>