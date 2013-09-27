<?php 
// A hard-coded files page.

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

require("./include/functions.php");

if(!isSignedIn)
	header('Location: /');

require("./templates/header.php");
?>

<!DOCTYPE html>
<html>
	<head></head>
	<body>
	<div style="padding: 30px 150px;">
	<h2>501 - New Program Application</h2><br>
		<a href="/files/501-Uganda.pdf" target="_blank">501 - Awelo, Uganda (pdf)</a>
		<br><br>
	<h2>502 - Application to Adopt a Program</h2><br>
		<a href="/files/502.doc" target="_blank">502 - Template/Instructions</a>
		<br><br>
	<h2>Meetings &amp; Presentations</h2><br>
		<a href="/files/EWB-UCF_Project_Team_Intro.pptx" target="_blank">Project Interest Meeting (.ppt)</a><br>
		<a href="/files/ProjectMeeting_2013-09-24.pptx" target="_blank">Project Meeting - Sept. 24 (.ppt)</a>
	</div>
	</body>
</html>

<?php renderFooter('Copyright &copy ' . date('Y', time()) .  ', Josh Kaplan'); ?>