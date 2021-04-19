<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Test</title>
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script>
		$( document ).ready( function () {

			var count = 0;
			var max = $( "#container div" ).length;
			var selected = [];

			animate();

			function animate() {
				$( ".box" + count.toString() ).animate( {
					left: '0px',
					top: '0px',
					opacity: 1.0
				}, 350, function(){
					
				$( this ).click( function () {
					$( this ).removeClass( "active" );
					$( this ).toggleClass( "clicked" );
					
					if($( this ).hasClass("clicked")) 
					{
						selected.push($( this ).data( "num" ));
					}
					else 
					{
						var boxToRemove = $( this ).data( "num" );
						var tempArr = selected.filter(function(box){
							return box != boxToRemove;
						});
						selected = tempArr;
					}
					
					$( "#someText2" ).html( "You Selected: " + selected.toString());

				} );

				$( this ).mouseenter( function () {
					$( "#someText" ).html( "You Hovered: " + $( this ).data( "num" ) );
					$( this ).addClass( "active" );
				} );

				$( this ).mouseleave( function () {
					$( "#someText" ).html( "You Hovered: " + $( this ).data( "num" ) );
					$( this ).removeClass( "active" );
				} );
					
				} );
				
				count++;
				if ( count < max ) setTimeout( animate, 30 );
			}

		} );
	</script>
	<style>
		.styler {
			width: 100px;
			height: 100px;
			border: 5px solid #950695;
			display: inline-block;
			margin: 8px;
			position: relative;
			left: 20px;
			top: 20px;
			opacity: 0;
			transform:scale(1);
		}
		
		.styler.active,
		.styler.clicked {
			cursor: pointer;
			background-color: #950695;
			transform:scale(1.1);
		}
		
		.styler.active svg,
		.styler.clicked svg {
			fill: white;
		}
		
		.styler svg {
			fill: #950695;
			width: 100px;
			height: 100px;
			margin-bottom: 5px;
		}
		
		.number {
			position: absolute;
			z-index: 99;
			font-size: 22px;
			font-weight: bold;
			margin-left: 34px;
			margin-top: 52px;
			color: white;
			font-family: 'Roboto Condensed', sans-serif;
		}
		
		.styler.active .number,
		.styler.clicked .number {
			color: #950695;
		}
	</style>
</head>

<body>
	<div id="container">
		<?php for($i = 0; $i < 100;$i++) { 
		$num = strval($i);
		if (strlen($num) == 1) {
			$num = "0" . $num;
		}?>
		<div class="styler box<?php echo $i; ?>" data-num="<?php echo $num;?>">
			<div class="number">
				<?php echo $num;?>
			</div>
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
				<path d="M383.1,257.4c0.6-5.4,0.9-10,0.9-13.8c0-19.6-3.3-19.7-16-19.7h-75.5c7.3-12,11.5-24.4,11.5-37c0-37.9-57.3-56.4-57.3-88
	c0-11.7,5.1-21.3,9.3-34.9c-26.5,7-47.4,33.5-47.4,61.6c0,48.3,56.3,48.7,56.3,84.8c0,4.5-1.4,8.5-2.1,13.5h-55.9
	c0.8-3,1.3-6.2,1.3-9.3c0-22.8-39.1-33.9-39.1-52.8c0-7,1-12.8,3.2-21c-12.9,5.1-28.3,20-28.3,36.8c0,26.7,31.9,29.3,36.8,46.3H80
	c-12.7,0-16,0.1-16,19.7c0,19.6,7.7,61.3,28.3,111c20.6,49.7,44.4,71.6,61.2,86.2l0.1-0.2c5.1,4.6,11.8,7.3,19.2,7.3h102.4
	c7.4,0,14.1-2.7,19.2-7.3l0.1,0.2c9-7.8,20-17.8,31.4-32.9c4.7,2,9.8,3.7,15.4,5c8.4,2,16.8,3,24.8,3c24,0,45.6-9.2,60.8-25.8
	c13.4-14.6,21.1-34.4,21.1-54.2C448,297,420,264.5,383.1,257.4z M366.1,384.2c-8.6,0-15.6-1.2-22.1-4.2c4-8,7.9-15.9,11.7-25.1
	c10.1-24.4,17.1-47,21.6-65.8c22,4.3,38.7,23.8,38.7,47.1C416,358.9,398.8,384.2,366.1,384.2z"/>
			</svg>
		</div>
		<?php } ?>
	</div>
	<p id="someText">&nbsp;</p>
	<p id="someText2">&nbsp;</p>
</body>

</html>