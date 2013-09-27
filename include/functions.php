<?php /********************************************************
       * functions.php
       * 
       * Author: Josh Kaplan
       * for use on project.ewbucf.org
       ********************************************************/


/*
 * Takes a string and prints red error message in header. 
 */

function panic($s)
{
	echo "<div id= \"panic\">" . $s . "</div>";
	return;
}


/*
 * Checks cookies to determine if a user is signed in.
 */

function isSignedIn()
{
	if (!isset($_COOKIE["profileid"]) || !isset($_COOKIE["email"]) || !isset($_COOKIE["passwordhash"]))
		return false;
	elseif (isNOE($_COOKIE["email"]) || isNOE($_COOKIE["passwordhash"]) || isNOE($_COOKIE['profileid']))
		return false;
	else
	{ 
		$mysqli = opendb();
		$allusers = $mysqli->query("SELECT email, passwordhash FROM users WHERE profileid='" . $_COOKIE['profileid'] ."'");
		$user = $allusers->fetch_row();
		if($user[0] != $_COOKIE['email'] || $user[1] != $_COOKIE['passwordhash'])
			return false;
		
		return true;	
	}
}


/*
 * Checks cookies for profileid then checks db if user is admin 
 */

function isAdmin()
{
	if(!isSignedIn())
		return false;
	
	$mysqli = opendb();
	$users = $mysqli->query("SELECT access FROM  users where profileid='" . $_COOKIE['profileid'] . "'");
	$user = $users->fetch_row();
	closedb($mysqli);
	
	return $user[0] == '1';
}

/*
 * Checks cookies for profileid then checks db if user is liaison
*/

function isLiaison()
{
	if(!isSignedIn())
		return false;

	$mysqli = opendb();
	$users = $mysqli->query("SELECT access FROM  users where profileid='" . $_COOKIE['profileid'] . "'");
	$user = $users->fetch_row();
	closedb($mysqli);

	return $user[0] == '2';
}


/*
 * Returns true if user is a team leader (or higher)
 */

function isTL()
{
	if(!isSignedIn())
		return false;
	if(isAdmin())
		return true;
	
	$mysqli = opendb();
	$users = $mysqli->query("SELECT teamlead FROM  teams");
	while($user = $users->fetch_row())
		if($user[0] == $_COOKIE['profileid'])
			return true;
	
	closedb($mysqli);
	return false;	
}


/*
 * 
 */

function isGuest()
{
	if(!isSignedIn())
		return false;
	if(isAdmin())
		return false;
	if($_COOKIE['profileid'] == 'guest')
		return true;
}


/*
 * Generates a globally unique identifier.
 */

function guid()
{
	mt_srand((double)microtime()*10000); //optional for php 4.2.0 and up.
	$charid = strtoupper(md5(uniqid(rand(), true)));
	$hyphen = chr(45);// "-"
	$uuid = chr(123)// "{"
	.substr($charid, 0, 8).$hyphen
	.substr($charid, 8, 4).$hyphen
	.substr($charid,12, 4).$hyphen
	.substr($charid,16, 4).$hyphen
	.substr($charid,20,12)
	.chr(125);// "}"
	return $uuid;
}


/*
 * Renders $N HTML spaces.
 */

function spaces($N)
{
	for($i = 0; $i < $N; $i++)
		echo "&nbsp";
	return;
}


/*
 * Renders $N HTML line breaks.
 */

function newline($N = 1)
{
	for($i = 0; $i < $N; $i++)
		echo "<br>";
	return;
}


/*
 * Checks if $s is null or empty.
 * Use isNOE. It is the same function. 
 */

function isnullorempty($s)
{
	return (is_null($s) || empty($s));	
}

function isNOE($s)
{
	return (is_null($s) || empty($s));
}


/*
 * Opens the ewbucfPM database and returns mysqli object.
 */

function opendb()
{
	$host = "ewbucfPM.db.10297362.hostedresource.com";
	$user = "ewbucfPM";
	$dbname = "ewbucfPM";
	$password = "MareBrignol#13";
	
	// Connecting to the database
	$mysqli = new mysqli($host, $user, $password, $dbname) OR DIE ("Unable to
		            connect to database! Please try again later.");
	
	return $mysqli;
}


/*
 * Takes a mysqli object and closes the db.
 */

function closedb($mysqli)
{
	$mysqli->close();
}


/*
 * Takes a question number and string and creates options for security questions.
 * Used in signup.php only.
 */

function secOpt($num, $str)
{
	echo "<option value=\"" . $num . "\""; 
	if ($_POST['q'] == $num) 
		echo "selected";
	echo ">" . $str . "</option>";
	return;
}


/*
 * Takes and invite code string.
 * Returns true if valid, false otherwise.
 */

function isValidInvite($num)
{
	if($num == 'ewb@UCF')
		return true;
	
	$mysqli = opendb();
	$invites = $mysqli->query("SELECT * from Invitations;");

	while($code = $invites->fetch_row())
	{
		//echo $code[0];
		if ($code[0] == $num)
		{
			closedb($mysqli);
			return true;
		}
	}
	
	closedb($mysqli);
	return false;
}


/*
 * Takes an invite code and deletes it from the database;
 */

function deleteInvite($num)
{
	echo "deleting ...<br>";
	$mysqli = opendb();
	$result = $mysqli->query("DELETE FROM Invitations WHERE code='" . $num . "'");
	
	closedb($mysqli);
	echo "deleted";
	return;
}


/*
 * Takes a timestamp and formats date for posts
 */

function formatDate($t)
{
	//date_default_timezone_set('America/New_York');
	
	if ( date('Y',time()) == date('Y',strtotime($t)) )
	{
		$year = ''; 		// if current year
		
		$meridian = (date('G', strtotime($t)) > 9 && date('G', strtotime($t)) < 21) ? 'pm' : 'am';
					
		// if posted today, only show time
		if ( date('M',time()) == date('M',strtotime($t)) && date('j',time()) == date('j',strtotime($t)) )	
		{      
			$day = '';												// today -- blank
			$hour = date('g', strtotime($t)) + 3;					// convert time zone
			$hour = ($hour > 12) ? $hour - 12 : $hour;
			$hour = date($hour . ':i',strtotime($t));				// full time
		}
		
		// if posted yesterday	
		elseif( date('M',time()) == date('M',strtotime($t)) && date('j',time()) == date('j',strtotime($t)) + 1 )  // yesterday
		{
			$day = 'Yesterday at ';								// day = yesterday
			$hour = date('g', strtotime($t)) + 3;				// convert time zone
			$hour = ($hour > 12) ? $hour - 12 : $hour;
			$hour = date('' . $hour . ':i',strtotime($t));		// disp full time
		}
		
		// if posted this month, show date AND time
		elseif( date('M',time()) == date('M',strtotime($t)) )
		{
			$day = date('M j ', strtotime($t)) . 'at ';			// format date (w/o year)
			$hour = date('g', strtotime($t)) + 3;				// time zone
			$hour = ($hour > 12) ? $hour - 12 : $hour;
			$hour = date('' . $hour . ':i',strtotime($t));		// full time
		}
		
		// if not this month, only display date, not time
		else 
		{
			$day = date('M j ', strtotime($t));					// formatted date
			$hour = '';											// blank hour
		}
	}
	
	// only display year for posts before current year
	else 
		$year = "\, " . date('Y',strtotime($t)) . ' ';

	return $day . $year . $hour . $meridian;	
}


/*
 * Takes a phone number string and formats it properly.
 */

function formatPhone($num)
{
	if(strlen($num) != 10)
		return $num;
	
	return '(' . $num[0] . $num[1] . $num[2] . ') ' . $num[3] . $num[4] . $num[5] . '-' . $num[6] . $num[7] . $num[8] . $num[9];
}

/*
 * Render the HTML for the a custom footer
 */

function renderFooter($str)
{
	echo "<footer class=\"short\"><p>" . $str . "</p></footer>";
}


/*
 * First, searches text for single or double quotes and replaces them with html.
 * Then call htmlspecialchars().
 */

function removeHTML($text)
{
	$text = str_replace ("\'", "&#39;" , $text);
	$text = str_replace ('\"', "&quot;" , $text);
	return ($_COOKIE['profileid'] == '{7EC271BF-1BAB-AC68-3638-539EB1935F94}') ? $text : htmlspecialchars($text);
}

?>