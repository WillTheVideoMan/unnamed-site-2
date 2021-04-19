var codeField;
var textValid;

function defineMeTFA (tfaCodeDom)
{
	"use strict";
	codeField = document.getElementById(tfaCodeDom);
	textValid = false;
}

function verifyCode ()
{
	"use strict";

			var request = new XMLHttpRequest();
			request.open("POST", "/TFAVerify.php", true);
			request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			request.send("authCode=" + codeField.value);
			
			request.onreadystatechange = function() 
			{
    			if (this.readyState === 4 && this.status === 200) 
				{
					if(this.responseText === "OK")
						{
							return true;
						}
						else
						{
							return false;
						}
				}
			};
}
