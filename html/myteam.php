<?php
$mysqli = opendb();
/*
$myTeamQuery = $mysqli->query("SELECT teamlead, name FROM teams" .							// get TL profile id and name
				               "WHERE id='" . $_COOKIE['team'] . "'" .  					// from user's team
		                       "and profileid!='" . $_COOKIE['profileid'] . "'");				
$myTeamInfo = $myTeamQuery->fetch_row();													


$teamLeadQuery = $mysqli->query("SELECT first_name, last_name, email, phone FROM users
				                 WHERE profileid='$myTeamInfo[0]'");
$myTeamLead = $teamLeadQuery->fetch_row();

echo "<b style=\"color: #b5cde4;\">" . ucwords($myTeamLead[0]) . ' ' . ucwords($myTeamLeas[1]) . "</b><br>" . 
		
	 "<font style=\"color: #b5cde4;\">$myTeamLead[0] . "</font><br>" .
		
echo "<p style=\"line-height: 1.2;\"><b>phone:</b> " . formatPhone($team_user[3]) . "<br>";
echo "<b>email:</b> " . $team_user[2] . "</p>";
*/

/* Add the following:
 * 		info for my team leader
 * 		info for all people on my team (done)
 *		info for people on the team I am the leader of 		
 */

$team_users = $mysqli->query("SELECT first_name, last_name, email, phone FROM users
				              WHERE team='" . $_COOKIE['team'] . "' and profileid!='" . $_COOKIE['profileid'] . "' 
		                      order by last_name");
while($team_user = $team_users->fetch_row())
{
	echo "<b style=\"color: #b5cde4;\">" . ucwords($team_user[0]) . ' ' . ucwords($team_user[1]) . "</b><br>";
	echo "<p style=\"line-height: 1.2;\"><b>phone:</b> " . formatPhone($team_user[3]) . "<br>";
	echo "<b>email:</b> " . $team_user[2] . "</p>";
		
}
closedb($mysqli);
?>