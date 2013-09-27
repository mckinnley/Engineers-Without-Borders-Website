<?php
$mysqli = opendb();
$users = $mysqli->query("SELECT first_name, last_name, email, phone, chapter, team FROM users
				                         WHERE profileid='" . $_COOKIE['profileid'] . "'");
$user = $users->fetch_row();
$chapter = ($user[4] == 'scfl') ? 'Space Coast Florida Professionals' : 'University of Central Florida';
$teams = $mysqli->query("SELECT name FROM teams WHERE id='" . $user[5] . "'");
$team = $teams->fetch_row();
?>

<h1 style="color: #b5cde4; border-bottom: 2px dashed #095aa6; margin: 0px 0px 3px 0px; padding: 0px 30px 4px 30px;">
	<b><?= ucwords($user[0]) . ' ' . ucwords($user[1]) ?></b>
</h1><br>
<?php 
echo "<font style=\"color: #c2cd23;\">" . $team[0] . "</font>";

echo "<p style=\"margin-top: 10px;\">" . 
	 	"<b>chapter:</b> <font style=\"color: #b5cde4;\">" . $chapter . "</font><br>" . 
     	"<b>email:</b> <font style=\"color: #b5cde4;\">" . $user[2] . "</font><br>" . 
     	"<b>phone:</b> <font style=\"color: #b5cde4;\">" . formatPhone($user[3]) . "</font>" .
     	"</p>";

closedb($mysqli);
?>