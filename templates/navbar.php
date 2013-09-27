<?php  require("/include/functions.php"); ?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="/css/navbar.css?=<?php echo date('l jS \of F Y h:i:s A')?>">   
	</head>
	<body>
	    <div id="subheader">
	            <nav class="menu-h">
	  				<ul>
						<li><a href="http://www.ewbucf.org/">EWB-UCF</a></li>
						<!-- <li><a href="#">Forums</a></li> -->
						<li><a href="/files.php">Files</a></li>
						
						<!-- <li><a href="#">Contact</a></li> -->
						<?php if(isSignedIn()): ?>
						<li><a href="/signout.php">Log Out</a></li>
						<?php else: ?>
						<!-- <li><a href="/signin.php">Sign In</a></li> -->
						<li><a href="/application.php">Apply</a></li> 
						<?php endif; ?>				
					</ul>
				</nav>
	    </div>
	</body>
</html>
        