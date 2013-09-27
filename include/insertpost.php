<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

//include $_SERVER["DOCUMENT_ROOT"].'/project/include/functions.php';
require("functions.php");

if (!isSignedIn())
	header('Location: http://project.ewbucf.org/signin.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	//echo "inserting post...";
	if (isNOE($_POST['wall']))
	{
		if(isNOE($_POST['comment']))
			header('Location: http://project.ewbucf.org');
		else 
		{
			$mysqli = opendb();
			
			// get team # for user
			$userInfo = $mysqli->query("Select chapter from users where profileid='" . $_COOKIE['profileid'] . "'");
			$chapter = $userInfo->fetch_row();
			
			// prepare to insert post to db
			$sql = "INSERT INTO wall ( `postid`, `parentpostid`, `fromprofileid`, `text`, `chapter`)
			        VALUES ('" . guid() . "', '" . $_POST['parentpostid'] . "', '" . $_COOKIE['profileid'] . 
			                "', ?, '" . $chapter[0] . "')";
			
			// insert post
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param('s', removeHTML($_POST['comment']));
			$success = $stmt->execute();
			$stmt->close();
			
			closedb($mysqli);
			if(!$success)
				DIE("Oops! Something went wrong.");
			else
				header('Location: http://project.ewbucf.org');
		}
	}
	else
	{
		$mysqli = opendb();

		// get team # for user
		$userInfo = $mysqli->query("Select chapter from users where profileid='" . $_COOKIE['profileid'] . "'");
		$chapter = $userInfo->fetch_row();

		// prepare to insert post to db
		$sql = "INSERT INTO wall ( `postid`, `fromprofileid`, `text`, `chapter`)
			        VALUES ('" . guid() . "', '" . $_COOKIE['profileid'] . "', ?, '" . $chapter[0] . "')";

		// insert post
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('s', removeHTML($_POST['wall']));
		$success = $stmt->execute();
		$stmt->close();

		closedb($mysqli);
		if(!$success)
			DIE("Oops! Something went wrong.");
		else 
			header('Location: http://project.ewbucf.org');
	}
}
header('Location: http://project.ewbucf.org');
?>