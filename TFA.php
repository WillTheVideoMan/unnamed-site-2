<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link href="TFAcss.css" rel="stylesheet" type="text/css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="TFAscript.js"></script>
<script>
	window.onload = function()
	{
		defineMeTFA('tfaCode');
		
		document.getElementById('subBtn').addEventListener('click', function()
		{
			verifyCode();
		})
	}
	
</script>
<title>TFA Test + CSS</title>
</head>

<input type = "text" id = "tfaCode" class = "veriText" placeholder="000000" maxlength="6">
<input type = "button" id = "subBtn" value = "Check Code">

<body>
</body>
</html>