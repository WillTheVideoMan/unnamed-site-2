<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>

<script>
	
	var textFile = null; //New textfile is created as null on run.
	
	window.onload = function() {
		
	document.getElementById("openFile").addEventListener('change', function() 
	{
		var fr = new FileReader(); //Creates new FileReader Object
		//Reads the selected file as text. (this.files[0] simply means ready only this file, as is
		//possible to read multiple files one after another)
		fr.readAsText(this.files[0]); 
		
		fr.onload = function() //runs when the FileReader is finished reading.
		{
		    var lines = this.result.split('\n'); //Take the results and split them by 'return(\n)'.
			
			for(var i = 0; i < lines.length; i++) //prints any number of lines adjecently
				{
					var newLine = "<p>" + lines[i] +"</p>";
					document.getElementById("fl1").insertAdjacentHTML('beforebegin', newLine);
				}
		}
	});
	
	document.getElementById("createBtn").addEventListener('click', function()
    {
		//Utilises the 'download' functionality of the <a>.
		var downloadLink = document.getElementById("downloadlink"); //References the <a>.
		var longTxt = longTextGen(); //Gnerates the long text.
		var fileName = document.getElementById("filename").value; //References the <p> containg filename.
		downloadLink.download = fileName + ".txt";
		downloadLink.href = generateFile(longTxt); 
		//Sets the download URL to equal that as defined by the function below.
		downloadLink.click();
	    //'auto-click' the newly appended download link.
	});
	
	}
	
	//The following function generates a one-use URL that the browser can use to 
	//initiate a download, exploting the <a> dowload functionality.
		
	var generateFile = function(inputText) 
	{
		//creates a data 'blob' - simnply a type of data storage to hold data 
		//before writing beginning a download. The blob is defined as plain text.
		var rawDataBlob = new Blob([inputText], {type: 'text/plain'});
			
		//IMPORTANT - This will check to see wether a Text File has already been created for this runtime.
		//If so, and this function is called again, it will remove the old one time URL of the exisitng from 
		//existence. This means that only one URL will ever be in existance, making for efficient code.
		if(textFile !== null)
			{
				window.URL.revokeObjectURL(textFile);
			}
			
	    //Generate new one-time use URL (with the raw data attached) to download the file.
		textFile = window.URL.createObjectURL(rawDataBlob);
			
		//Since this function is used to return a one time URL, we must return to parent.
		return textFile;
	}
	
	//The Following function generates a 'line-by-line' text.
	
	var longTextGen = function() 
	{
		var longText = "";
		for(i = 1; i <= 5; i++)
		{
			alert(longText);
			var txt = document.getElementById("newLine" + i.toString()).value;
			longText += txt + '\n';
		}
		return longText;
	}
	
</script>

<link href="myStyle.css" rel="stylesheet" type="text/css">
</head>

<body>

<p>Enter Lines Of Text Here Please Now:</p>

<input type = "text" id = "newLine1" style = "display:list-item;">
<input type = "text" id = "newLine2" style = "display:list-item;">
<input type = "text" id = "newLine3" style = "display:list-item;">
<input type = "text" id = "newLine4" style = "display:list-item;">
<input type = "text" id = "newLine5" style = "display:list-item;">

<p>Filename:</p>

<input type = "text" id = "filename" style = "display:list-item;" value = "myFile">

<input type = "button" value = "Save File" id = "createBtn">

<a download="" id="downloadlink" style="display: none">Download</a>

<input type="file" id = "openFile">

<p id = "fl1"></p>


</body>
</html>