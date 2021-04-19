function startup ()
{
	"use strict";
	var settingsTriggerObj = document.querySelector("#setTrig");
	var settingsTriggerDOM = document.getElementById("setTrig");
	var sideMenuObj = document.querySelector("#sMenu");
	
	settingsTriggerDOM.addEventListener('mouseenter', function()
    {
		settingsTriggerObj.classList.add("hover");
	});
	
	settingsTriggerDOM.addEventListener('mouseleave', function()
    {
		settingsTriggerObj.classList.remove("hover");
	});
	
	settingsTriggerDOM.addEventListener('click', function()
    {
		sideMenuObj.classList.toggle("active");
		settingsTriggerObj.classList.toggle("active");
		document.querySelector("#body").classList.toggle("shifted");
	});
	
	document.getElementById("colRed").addEventListener('click', function()
	{
		toggler('#colRed', '#colBlue', '#colGreen', '#colBlack');
	});
	
	document.getElementById("colBlue").addEventListener('click', function()
	{
		toggler('#colBlue', '#colRed', '#colGreen', '#colBlack');
	});
	
	document.getElementById("colGreen").addEventListener('click', function()
	{
		toggler('#colGreen', '#colBlue', '#colRed', '#colBlack');
	});
	
	document.getElementById("colBlack").addEventListener('click', function()
	{
		toggler('#colBlack', '#colBlue', '#colGreen', '#colRed');
	});
}

function toggler (on, off1, off2, off3)
	{
		"use strict";
		document.querySelector(on).classList.add("selected");
		document.querySelector(off1).classList.remove("selected");
		document.querySelector(off2).classList.remove("selected");
		document.querySelector(off3).classList.remove("selected");
	}
