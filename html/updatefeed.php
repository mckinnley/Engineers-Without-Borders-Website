<?php 
$mysqli = opendb();
					
$AllUsers= $mysqli->query("SELECT team FROM users WHERE profileid='" . 						// get user's team info
		                   $_COOKIE['profileid'] . "'");
$userTeam = $AllUsers->fetch_row();
$team = $userTeam[0];																		// user's team from db																							

//if($team != $_COOKIE['team'])																// if cookie doesn't match team in db
	//setcookie("team", $team, time()+60*60*24*30, "/", "project.ewbucf.org", false, true);	// reset cookie.

$parents= $mysqli->query("SELECT parent FROM teams WHERE id='$team'");
$parentTeamId = $parents->fetch_row();														// get parent team

$parentInfo = $mysqli->query("SELECT teamlead FROM teams WHERE id='$parentTeamId[0]'");	
$parentLeadId = $parentInfo->fetch_row();													// get parent TL

$parentTeam = $parentTeamId[0];					// parent team id number
$parentTL = $parentLeadId[0];					// parent TL profile id
					
// get sub-team
/* Note: this makes the assumption that each user 
   only leads one team, and only gets the first one
   it could cause problems later on */
$all_teams = $mysqli->query("SELECT id, parent FROM teams WHERE teamlead='" . $_COOKIE['profileid'] . "'");
$sub_team = $all_teams->fetch_row();
	
// Get Updates
if(isAdmin())		// from all users
	$updates = $mysqli->query("SELECT * FROM updates order by time desc LIMIT 30");
		
else 				// from relevant users
	$updates = $mysqli->query("SELECT * FROM updates WHERE team='" . $_COOKIE['team'] . "' OR 
			                   team='$sub_team[0]' OR team='$sub_team[1]' OR posterid='$parentTL' OR
				               team=0 OR posterid='{7EC271BF-1BAB-AC68-3638-539EB1935F94}' order by time desc LIMIT 40");
	
// Build Update Feed
while($update = $updates->fetch_row())
{
	$posters = $mysqli->query("SELECT first_name, last_name, chapter FROM users where profileid='" . $update[0] . "'");
    $poster = $posters->fetch_row();
						
	$teams = $mysqli->query("SELECT * FROM teams where id='" . $update[3] . "'");
	$teampost = $teams->fetch_row();
						
	echo "<p>" . 
		 "<b style=\"color: #b5cde4;\">" . 
		 	ucwords($poster[0]) . " " . ucwords($poster[1]) . 			// user name who posted update
		 "</b> " .
		 "<font style=\"font-size: 12px; color: #b5cde4;\">" . 
			$teampost[1] . 												// team associated w update (user's team at time of update)
		 "</font>" . 
	
		 "<font style=\"font-size: 11px; color: #095aa6; float: right;\">" . 
			formatDate($update[1]) . 											// timestamp
		 "</font> <br>" .
			$update[2] . 			   									    	// actual content of update
		 "<br></p>";
}
					
closedb($mysqli);
?>	