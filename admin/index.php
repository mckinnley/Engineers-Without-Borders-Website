<?php
/*
 * project.ewbucf.org
 * 
 * Admin page - allows admins to view applications from people.
 */

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

require("../include/functions.php"); 

require("../templates/header.php"); echo "test";

if(!isAdmin() || !isLiaison())		
	header('Location: /');
?>

<!DOCTYPE html>
<html>
	<head></head>
	<body>
	
	<?php $mysqli = opendb();

	$AllApplications = $mysqli->query("SELECT * FROM applications");
	while($app = $AllApplications->fetch_row())
	{ ?>
		<div class="wallpost">
			<p style="line-height: 1.2;"> 
				<b style="color: #095aa6;>"> <?= ucwords($app[0]) . " " . ucwords($app[1]); ?> </b>
				
				<font class="timestamp"><?= formatDate($post[3])?></font> <br>

				<br></p>
		</div>
	<?php }?>

	</body>
</html>
<?php 
closedb($mysqli); 
renderFooter('Copyright &copy ' . date('Y', time()) .  ', Josh Kaplan');
?>