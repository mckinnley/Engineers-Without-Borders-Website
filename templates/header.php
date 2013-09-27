 <?php  
 
 date_default_timezone_set('America/New_York');
 
 global $color1, $color2, $color3, $color4, $color5, $color6, $color7, $color8, $color9;
 
 $color1 = "#095aa6"; // dark blue
 $color2 = "#b5cde4"; // light blue
 $color3 = "#000000"; // black
 $color4 = "#bf7329"; // dark orange
 $color5 = "#cca147"; // light orange
 $color6 = "#c2cd23"; // bright green
 $color7 = "#8c944d"; // dark green
 $color8 = "#a9af7a"; // light green
 $color9 = "#e0e691"; // tan
  
 require("/include/functions.php");

 ?>
 <!DOCTYPE html>

 <html>
	 <head>
	 	<title>Project Team | EWB-UCF</title>
	 	<link rel="icon" type="image/png" href="/images/ewb-logo-small.png">
		<link rel="stylesheet" type="text/css" href="/css/reset.css">
		<link rel="stylesheet" type="text/css" href="/css/styles.css?=<?php echo date('l jS \of F Y h:i:s A')?>">
	</head>
	<body>
		<header>
			<div class="left">
				<a href="/"><img src="/images/ewb-ucf-logo-metallic-gold.png" width="550" height="90"></a>	
	       	</div> 	
		  	<?php require('./templates/navbar.php');?>  		
		</header>
	</body>
</html>
        