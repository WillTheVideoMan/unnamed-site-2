"use strict";
$(function(){
	
	var stage = new createjs.Stage("hashCanvas");
	var rectangle = new createjs.Shape();
	var lastMinuite = new Date();
	var lastSecond = new Date();
	var hourString = "";
	var minuiteString = "";
	var tick = true;
	
	var rects = [];
	var rectCount = 30;
	var rectsBuffer = [];
	
	window.onresize = function(){
		stage.canvas.width = window.innerWidth;
		stage.canvas.height = window.innerHeight;
	};
	
	init();
	
	function printRects() {
		
		var millisecondProgress = Date.now() % 60000;
		var currentMinuite = new Date();
		var currentSecond = new Date();
		
		var hourTextObject = new createjs.Text(hourString , "32px Source Sans Pro", "white" );
		var minuiteTextObject = new createjs.Text(minuiteString , "32px Source Sans Pro", "white" );
		var colonTextObject = new createjs.Text(":" , "32px Source Sans Pro", "white" );
		
		hourTextObject.x = (( stage.canvas.width - (hourTextObject.getMeasuredWidth())) / 2) - (hourTextObject.getMeasuredWidth() / 1.5);
		minuiteTextObject.x = (( stage.canvas.width - (minuiteTextObject.getMeasuredWidth() )) / 2) + (minuiteTextObject.getMeasuredWidth() / 1.5);
		colonTextObject.x = ( stage.canvas.width - (colonTextObject.getMeasuredWidth() )) / 2;
		
		hourTextObject.y = minuiteTextObject.y = colonTextObject.y = ((stage.canvas.height - 32) / 2);
		
		if(Math.floor(currentMinuite.getTime() / 60000) > Math.floor(lastMinuite.getTime() / 60000)){
			
			console.log("------------------------------");
			console.log("Minuite Elapsed!");
			
			lastMinuite.setMinutes(lastMinuite.getMinutes() + 1);
			
			writeBuffers();
			
			updateBuffer(function(msg){
				console.log(msg);
			});
		}
			
		if(Math.floor(currentSecond.getTime() / 1000) > Math.floor(lastSecond.getTime() / 1000)){
			
			if(tick) tick = false;
			else tick = true;
			
			lastSecond.setSeconds(lastSecond.getSeconds() + 1);
			
		}

		

		stage.removeAllChildren();
		stage.update();
		
		for(var i = 0; i < rects.length; i++) {
			
			var cloneRect = rectangle.clone( true );
		
			var colour = "rgba(" + 
				Math.floor(
					((interpolate(0, 59999, rects[i].c.r.a, rects[i].c.r.b, millisecondProgress)))) + "," + 
				Math.floor(
					((interpolate(0, 59999, rects[i].c.g.a, rects[i].c.g.b, millisecondProgress)))) + "," + 
				Math.floor(
					((interpolate(0, 59999, rects[i].c.b.a, rects[i].c.b.b, millisecondProgress)))) + ",1)";
		
			cloneRect.graphics.beginFill(colour).drawRect(0, 0, 
					interpolate(0, 59999, rects[i].s.a, rects[i].s.b, millisecondProgress), 
					interpolate(0, 59999, rects[i].s.a, rects[i].s.b, millisecondProgress));
			
			cloneRect.rotation = interpolate(0, 59999, rects[i].r.a, rects[i].r.b, millisecondProgress);
			
			cloneRect.x = interpolate(0, 255, 48, stage.canvas.width - 48, 
									  (interpolate(0, 59999, rects[i].x.a, rects[i].x.b, millisecondProgress)));
			
			cloneRect.y = interpolate(0, 255, 48, stage.canvas.height - 48,
									  (interpolate(0, 59999, rects[i].y.a, rects[i].y.b, millisecondProgress)));
			
			
			stage.addChild(cloneRect);
					
		}
		if(tick) stage.addChild(colonTextObject);
		stage.addChild(hourTextObject, minuiteTextObject);
		stage.update();
		
		window.requestAnimationFrame(printRects);
	}
	
	async function updateBuffer(done) {
		
		console.log("------------------------------");
  		console.log('Updating Buffer...');
		
		hourString = "";
		minuiteString = "";
				
		if(lastMinuite.getHours().toString().length === 1) { hourString += "0"; }
		hourString += lastMinuite.getHours().toString();
					
		if(lastMinuite.getMinutes().toString().length === 1) { minuiteString += "0"; }
		minuiteString += lastMinuite.getMinutes().toString();
				
		var preHashString = "Year:" + lastMinuite.getYear() + 
			"Month:" + lastMinuite.getMonth() + 
			"Date:" + lastMinuite.getDate() + 
			"Day:" + lastMinuite.getDay() + 
			"Hours:" + lastMinuite.getHours() + 
			"Mins:" + lastMinuite.getMinutes();
				
			$.ajax({
			url: "PassHash.php?string=" + encodeURIComponent( preHashString ),
			success: function(result) {
			
				var hashString = result;
		
				for(var i = 0; i < rectsBuffer.length; i++)
				{
					var code = hashString.substr((i * 8) + 16, 8);
						
					rectsBuffer[i].code = code;
						
					rectsBuffer[i].x = { a:rectsBuffer[i].x.b, b: parseInt(code.substr(0, 2) , 16) };
					rectsBuffer[i].y = { a:rectsBuffer[i].y.b, b: parseInt(code.substr(2, 2) , 16) };
			
					rectsBuffer[i].s = { a:rectsBuffer[i].s.b, b: 
									(((parseInt(code.substr(4, 2) , 16) % 16) + 8) * 2) };
						
					rectsBuffer[i].r = { a:rectsBuffer[i].r.b, b: parseInt(code.substr(6, 2) , 16) };
				
					rectsBuffer[i].c.r = { a: rectsBuffer[i].c.r.b, b: (parseInt(code.substr(0, 2) , 16)) };
					rectsBuffer[i].c.g = { a: rectsBuffer[i].c.g.b, b: (parseInt(code.substr(2, 2) , 16)) };
					rectsBuffer[i].c.b = { a: rectsBuffer[i].c.b.b, b: (parseInt(code.substr(4, 2) , 16)) };
				}
				console.log("preHashString:" + preHashString);
				done("Resolved! Buffers Updated!");
			}
		});
	}
	
	function writeBuffers() {
		
		for(var i = 0; i < rects.length; i++)
		{
						
			rects[i].code = rectsBuffer[i].code;
					
			rects[i].x = rectsBuffer[i].x;
			rects[i].y = rectsBuffer[i].y;
			
			rects[i].s = rectsBuffer[i].s;
			rects[i].r = rectsBuffer[i].r;
	
			rects[i].c.r = rectsBuffer[i].c.r;
			rects[i].c.g = rectsBuffer[i].c.g;
			rects[i].c.b = rectsBuffer[i].c.b;
		}
		
		console.log("------------------------------");
		console.log("Buffers Written To Live Object...");
		console.log("The Live Objects Are:");
		console.log(rects);
	}
	
	function interpolate(x0, x1, y0, y1, x) {
		return y0 + (x - x0) * ((y1 - y0) / (x1 - x0));
	}
	
	function init() {
		
		stage.canvas.width = window.innerWidth;
    	stage.canvas.height = window.innerHeight;
		
		var loadingText = new createjs.Text("Loading..." , "32px Source Sans Pro", "white" );
		loadingText.x = (( stage.canvas.width - (loadingText.getMeasuredWidth())) / 2);
		loadingText.y = ((stage.canvas.height - 32) / 2);
		stage.addChild(loadingText);
		stage.update();
		
		for(var i = 0; i < rectCount; i++){
		
		rects.push(
			{code: "", 
			 x:{a:0, b: 0}, 
			 y:{a:0, b:0}, 
			 c:{r:{a:0, b:0}, 
				g:{a:0, b:0},
				b:{a:0, b:0}}, 
			 r:{a:0, b:0}, 
			 s:{a:0, b:0}
			});
		
		rectsBuffer.push(
				{code: "", 
				 x:{a:0, b:122.5}, 
			 	y:{a:0, b:122.5}, 
			 	c:{r:{a:0, b:0}, 
				   g:{a:0, b:0},
					b:{a:0, b:0}}, 
			 	r:{a:0, b:0}, 
			 	s:{a:0, b:0}
				});
		}
	
		lastMinuite.setMinutes(lastMinuite.getMinutes() - 1);
		
		updateBuffer(function(msg){
			
			lastMinuite.setMinutes(lastMinuite.getMinutes() + 1);
			
			console.log(msg);
			writeBuffers();
			printRects();
			
			updateBuffer(function(msg){
				console.log(msg);
			});
			
		});
	}
	
});