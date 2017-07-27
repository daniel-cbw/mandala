
<!DOCTYPE html>
<html>

	<head>

	<style>
		body{ background-color: ivory; }
		svg, div {
			position: absolute;
			top: 0;
			left: 0;
		}
		.rotate {
			/* Firefox */
			-moz-transition: all 1s cubic-bezier(0.68, -0.55, 0.265, 1.55);
			/* WebKit */
			-webkit-transition: all 1s cubic-bezier(0.68, -0.55, 0.265, 1.55);
			/* Opera */
			-o-transition: all 1s cubic-bezier(0.68, -0.55, 0.265, 1.55);
			/* Standard */
			transition: all 1s cubic-bezier(0.68, -0.55, 0.265, 1.55);

		}
	</style>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/svg.js/2.6.2/svg.min.js"></script>



	<script type="text/javascript">

		var startTime = false;
		var staggerTime = false;
		var timerInterval;

		//'use strict'
		var currentRotationS = 0;
		var currentRotationM = 0;
		var currentRotationH = 0;
		var rotateCenterX;
		var rotateCenterY;

		var secondsSet = [];
		var minutesSet = [];
		var hoursSet = [];

		var svgIDArray = [];
		var svgArray = [];
		var wireframeArray = [];
		var shadeArray = [];

		var bgColorArray = [
			'#e6e6e6',
			'#a9a9a9',
			'#666666',
			'#000000'
		];

		var debug = [
			'#6b3e26',
			'#ffc5d9',
			'#c2f2d0',
			'#fdf5c9',
			'#ffcb85'
		];

		var debug_reverse = [
			'#ffffff',
			'#bba423',
			'#333333',
			'#ff00bb',
			'#9090aab',
		];

		var wireframe_color_scheme_1 = [
			"0c3378",
			"2158a8",
			"6b6b6b",
			"4f86f7",
			"d3646b"
		];

		var wireframe_color_scheme_2 = [
			"2b4051",
			"535c5b",
			"333333",
			"7b8b7e",
			"e9e9eb"
		];

		var wireframe_color_scheme_4 = [
			"063e6d",
			"260f17",
			"2f3d58",
			"3b2e3f",
			"52d9ed"
		];

		var wireframe_color_scheme_3 = [
			"1a1a1a",
			"3a3a3a",
			"ffffff",
			"9a9a9a",
			"eaeaea"
		];



		////

		var shade_color_scheme_1 = [
			"7f6d83",
			"d5e0f2",
			"e9e9eb",
			"fefefc",
			"d4e9f2"
		];

		var shade_color_scheme_2 = [
			"e9a6f6",
			"fbe8c0",
			"fced48",
			"feded3",
			"fffde6"
		];

		var shade_color_scheme_4 = [
			"b6e5f9",
			"d4e9f2",
			"e27300",
			"fc7000",
			"ff0000"
		];

		var shade_color_scheme_3 = [
			"1a1c4f",
			"504d73",
			"aca8bd",
			"eb5332",
			"f18169"
		];

		shade_color_scheme_1 = shade_color_scheme_1.reverse();
		shade_color_scheme_2 = shade_color_scheme_2.reverse();
		shade_color_scheme_3 = shade_color_scheme_3.reverse();

		var wireframe_color_schemes = [
			wireframe_color_scheme_1,
			wireframe_color_scheme_2,
			wireframe_color_scheme_3,
			//wireframe_color_scheme_4
		];

		var shade_color_schemes = [
			shade_color_scheme_1,
			shade_color_scheme_2,
			shade_color_scheme_3
		];

		var current_color_scheme_index = 0;
		var previous_color_scheme_index = wireframe_color_schemes.length - 1;

		$(document).ready(function(){

			


			

			//debug_reverse;

			//$('svg path').attr('style', "fill: inherit; fill-rule: nonzero; stroke-width: inherit");
			//$('svg path').attr('style', "fill: inherit; fill-rule: nonzero;");

			$('svg').each(function(){

				var id = $(this).attr('id');
				if ( !id.includes( 'ignore' ) )
				{
					svgIDArray.push( id );
				}


				
				// $(this).width(500);
				// $(this).height(500);
				$(this).find('g:first').attr('id', $(this).attr('id') + '_group' );
				$(this).attr('xlmns', 'http://www.w3.org/2000/svg');

			});

			for( var i = 0; i < svgIDArray.length; i++ )
			{


				var svg = {};

				svg.obj = SVG.get( svgIDArray[i] ).size(800);
				svg.ID = svgIDArray[i];
				svg.width = svg.obj.width();
				svg.height = svg.obj.height();
				svg.viewBox = svg.obj.viewbox();
				svg.relativeWidth = svg.width / svg.viewBox.zoom;
				svg.relativeHeight = svg.height / svg.viewBox.zoom;
				svg.groupID = $( '#' + svgIDArray[i] + ' g:first' ).attr('id');
				svg.group = SVG.get( svg.groupID );
				svg.groupWidth = ( svg.group.bbox().width * svg.viewBox.zoom ) / svg.viewBox.zoom + 500;
				svg.groupHeight = ( svg.group.bbox().height * svg.viewBox.zoom ) / svg.viewBox.zoom + 500;
				svg.groupCenterCX = svg.group.cx();
				svg.groupCenterCY = svg.group.cy();
				svg.parentID = svg.obj.node.parentElement.id;
				
				svgArray.push( svg );

				$('#' + svg.obj.node.parentElement.id ).width(svg.width).height(svg.width);

				svg = {};

			}

			svgArray.reverse();

			SVG.get( $('#__base_trim_ignore').attr('id') ).size( svgArray[0].width, svgArray[0].height );
			

			svgArray.map(function(o, i){
				//svgArray[i].group.fill('pink');

				var j = i;

				if ( svgArray[i].ID.indexOf( 'wireframe' ) !== -1 ) {

					wireframeArray.push( svgArray[i] );

				}
				else if ( svgArray[i].ID.indexOf( 'shade' ) !== -1 ) {
					shadeArray.push( svgArray[i] );
				}

			});

			//updateColors( wireframeArray, 'wireframe' );
			//updateColors( shadeArray, 'shade' );

			//setInterval('updateClock()', 1000);




		});

		function incrementColorSchemes() {

			if ( current_color_scheme_index == wireframe_color_schemes.length - 1 ) 
		    {
		    	console.log('reset');
		    	previous_color_scheme_index = wireframe_color_schemes.length - 1;
		    	current_color_scheme_index = 0;
		    }
		    else {
		    	console.log('dont reset');
		    	previous_color_scheme_index = current_color_scheme_index;
		    	current_color_scheme_index++;
		    }

		}

		$(window).keypress(function (e) {

			e.preventDefault();

			console.log(e.keyCode);

		  if (e.keyCode === 0 || e.keyCode === 32) {
		    e.preventDefault();

		    incrementColorSchemes();

		    updateColors( wireframeArray, 'wireframe' );
		    updateColors( shadeArray, 'shade' );

		  }

		  if ( e.keyCode == 100 ) {
		  	console.log('d');
		  	//svgArray.map(function(o, i){
		  	var set = $('svg');
		  	var len = set.length;
		  	set.each(function( i ) {

		  		console.log($(this));
				var id = $(this).attr('id');
				var svg = SVG.get( id );


				//svg.animate(500).width(500);
				var viewbox = svg.viewbox();
				svg.remember('oldviewbox', viewbox );
				if (id.includes('front'))
		  		{
					svg.animate().viewbox( viewbox.x - 1400 / viewbox.zoom, viewbox.y, 2400 / viewbox.zoom, viewbox.height);
				}
				else if (id.includes('middle'))
		  		{
					svg.animate().viewbox( viewbox.x - 850 / viewbox.zoom, viewbox.y, 2400 / viewbox.zoom, viewbox.height);
				}
				else {
					svg.animate().viewbox( viewbox.x, viewbox.y, 2400 / viewbox.zoom, viewbox.height);
				}
			//});


		  	});

		  }

		  if ( e.keyCode == 115 ) {

		  	if ( staggerTime ) {staggerTime = false;}
		  	else staggerTime = true;

		  }

		  if ( e.keyCode == 99 ) {

		  	if ( startTime ) {
		  		startTime = false;
		  		clearInterval(timerInterval);

		  	}
		  	else {
		  		timerInterval = setInterval('updateClock()', 1000);
		  		startTime = true;
		  	}
		  }

		  if ( e.keyCode == 102 ) {

		  	var set = $('svg');
		  	var len = set.length;
		  	set.each(function( i ) {

		  		console.log($(this));
				var id = $(this).attr('id');
				var svg = SVG.get( id );


				//svg.animate(500).width(500);
				var old_viewbox = svg.remember('oldviewbox');
				
				svg.animate().viewbox(old_viewbox);

		  	});

		  }

		});

		function commitToRotationSet( svg ) {

			var keyword = svg.ID;

			if ( keyword.includes('front') )
			{
				
				secondsSet.push( svg );
			}

			if ( keyword.includes('middle') )
			{
				
				minutesSet.push( svg );
			}

			if ( keyword.includes('background') )
			{
				
				hoursSet.push( svg );
			}

			rotateCenterX = svg.groupCenterCX;
			rotateCenterY = svg.groupCenterCY;

		}


		function updateColors( objArray, type ) {

			var offset = -20;
			var interval = 100;
			


				var color_arr;

				if ( type == 'wireframe' )
				{
					color_arr = wireframe_color_schemes;
				}
				else {
					color_arr = shade_color_schemes;
				}

				objArray.map(function(o, i){

					

					//console.log(i);

				//for( var i = 0; i < objArray.length; i++ ) {

					var color_scheme = color_arr[ current_color_scheme_index ];
					
					var previous_color_scheme = color_arr[ previous_color_scheme_index ];

					var maskGroup;

					if ( !objArray[ i ].hasOwnProperty('maskGroup') ) {

						maskGroup = objArray[i].obj.group();

						var gradient = '#ffffff';

						var mask = objArray[i].group.fill({ color: gradient });
						
						objArray[i].gradient = gradient;

						maskGroup.maskWith( mask );

						objArray[i].maskGroup = maskGroup;

						commitToRotationSet( objArray[i] );

					}

					

					/////

				

					var background_1;

					if ( !objArray[ i ].hasOwnProperty('background_1') ) {

						background_1 = objArray[i].obj.circle( 0 )
												    .fill({ color: '#' + color_scheme[ i ] })
												    .center( objArray[i].groupCenterCX, objArray[i].groupCenterCY );
						objArray[i].maskGroup.add(background_1);
						objArray[i].background_1 = background_1;
					}
					else {
						//background_1 = objArray[i].background_1.fill({ color: '#' + color_scheme[i] });
						objArray[i].background_1 = objArray[i].background_1.fill({ color: '#' + previous_color_scheme[i] });
					}

					var background_0;

					if ( !objArray[ i ].hasOwnProperty('background_0') ) {

						background_0 = objArray[i].obj.circle( objArray[i].relativeWidth )
												    .fill({ color: '#' + previous_color_scheme[i] });
						objArray[i].maskGroup.add(background_0);
						objArray[i].background_0 = background_0;
					}
					else {
						objArray[i].background_0 = objArray[i].background_0.fill({ color: '#' + color_scheme[i] });
					}
					
						
					if ( staggerTime ) {

						setTimeout(function(){
							animateMaskedElements( objArray[i] );
						}, interval + offset);    
	 			
	 					(offset += interval ) * 2;
	 				}
	 				else {
	 					animateMaskedElements( objArray[i] );
	 				}

				});

			
			//}

		}

		function animateMaskedElements( element ) {

			var front = element.background_0;
			var back =element.background_1;
			var groupW = element.groupWidth;
			var groupH = element.groupHeight;
			var bg_0 = element.background_0;
			var bg_1 = element.background_1;

			// resize and center function queue is set to false to fire simulaneously
			front.animate(500).center( front.cx(), front.cy() ).during(function(pos, morph, eased, situation) {

				front.radius( morph( 0, groupW ), morph( 0, groupH ) );

			}).afterAll(function() {
				//prepNextTransition();
				var previousColor = front.attr('fill');
				bg_0 = bg_0.radius(0);
				bg_1 = bg_1.fill({ color: previousColor }).radius( groupW );
			});


			// front.animate(300).transform({ scaleX: groupW, scaleY: groupH }, front.cx(), front.cy()).afterAll(function() {
			// 	//prepNextTransition();
			// 	var previousColor = front.attr('fill');
			// 	bg_1 = bg_1.fill({ color: previousColor }).transform({ scaleX: groupW, scaleY: groupH }, front.cx(), front.cy());
			// 	bg_0 = bg_0.transform({ scaleX: 0, scaleY: 0 }, front.cx(), front.cy());
				
			// });

		}


		function updateClock ( )
	 	{
		 	var currentTime = new Date ( );
		  	var currentHours = currentTime.getHours ( );
		  	var currentMinutes = currentTime.getMinutes ( );
		  	var currentSeconds = currentTime.getSeconds ( );

		  	//currentHours = currentHours % 12;

		  	var angleH = currentHours % 12 / 12 * 360 + ( currentMinutes / 12);
	        var angleM = currentMinutes * 6;
	        var angleS = ( currentSeconds * 6 );

		  	// Pad the minutes and seconds with leading zeros, if required
		  	currentMinutes = ( currentMinutes == 0 ) ? currentHours - 12 : currentHours;

		  	// Convert an hours component of "0" to "12"
		  	currentHours = ( currentHours == 0 ) ? 12 : currentHours;

		  	// Compose the string for display
		  	var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds;

		  	
		  	
		   	$("#clock").html( String( currentTimeString ) );

		   	rotate( secondsSet, angleS );
		   	rotate( minutesSet, angleM );

			console.log(currentMinutes);		   	
		   
		   	//rotate( minutesSet, angleM );
		   	//rotate( hoursSet, angleH );

		   	if ( currentSeconds == 0 )
		  	{
		  		//rotate( minutesSet, angleM );
		  	}
		  	if ( currentMinutes == 0 )
		  	{
		  		
		  	}
		   	  	
		 }

		 function rotate( set, degrees )
		 {
		 	var previousDegrees = degrees - 6;

		 	if ( previousDegrees <= 0 )
		 	{
		 		previousDegrees += 360;
		 	}

		 	if ( degrees == 0 ) {
		 		console.log('minute passes');
		  		incrementColorSchemes();
		  		updateColors( wireframeArray, 'wireframe' );
				updateColors( shadeArray, 'shade' );
		 	}

	 		//set
	 		set.map(function(o, i) {

	 			var _obj = set[i];

	 			var svg_wrapper = $( '#' + _obj.parentID );

				// For webkit browsers: e.g. Chrome
				svg_wrapper.css({ WebkitTransform: 'rotate(' + degrees + 'deg)'});
				// For Mozilla browser: e.g. Firefox
				svg_wrapper.css({ '-moz-transform': 'rotate(' + degrees + 'deg)'});



	 		});
		 }

		

	</script>

	</head>


	<body>

		<div id="clock"></div>

		<?php

			$files = glob('svg/*.{svg}', GLOB_BRACE);
			$i = 0;
			foreach($files as $file) {
			
				if ( $i < 99 )
				{
					echo '<div id="svg_wrapper_' . $i . '" class="rotate">';
					echo  file_get_contents($file) ;
					echo '</div>';
				}
				$i++;
			}

		?>


	</body>

</html>