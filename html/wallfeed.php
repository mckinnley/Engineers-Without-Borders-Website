<?php 

//if (!)
$mysqli = opendb();
					
// get chapter
$userInfo = $mysqli->query("SELECT chapter FROM users WHERE profileid='" . $_COOKIE['profileid'] . "'");
$chapter = $userInfo->fetch_row();
			
// Get Updates
if(isAdmin() || isLiaison())		// from all users
	$posts = $mysqli->query("SELECT * FROM wall WHERE parentpostid IS NULL order by time desc");
	
else 				// from relevant users
	$posts = $mysqli->query("SELECT * FROM wall WHERE parentpostid IS NULL AND 
                             (chapter='$chapter[0]' OR fromprofileid='{7EC271BF-1BAB-AC68-3638-539EB1935F94}') order by time desc");

// Main wall post
while($post = $posts->fetch_row())
{
	$posters = $mysqli->query("SELECT first_name, last_name, chapter FROM users where profileid='" . $post[2] . "'");
    $poster = $posters->fetch_row();
    
    echo "<div class=\"wallpost\">".	
		 		"<p style=\"line-height: 1.2;\">" .						// begin wall post html
		 		"<b style=\"color: #095aa6;\">" . 
		 		 	ucwords($poster[0]) . " " . ucwords($poster[1]) . 	// user name
	     		"</b> " .
		
	 			"<font class=\"timestamp\">" . 
					formatDate($post[3]) . 								// display timestamp
	     		"</font> <br>" . 
	
	 			$post[4] . 												// actual post
    			"<br> </p>" . 
    	 "</div>";
	
	// Comments
	$comments = $mysqli->query("SELECT * FROM wall WHERE parentpostid='" . $post[0] ."' order by time");
	while($comment = $comments->fetch_row())
	{
		$commentposters = $mysqli->query("SELECT first_name, last_name, chapter FROM users where profileid='$comment[2]'");
		$commentposter = $commentposters->fetch_row();
		
		
		echo "<div class=\"wallcomment\">" .										// begin comment
				"<p style=\"line-height: 1.2;\">" . 
				"<b style=\"color: #b5cde4;\">" . 
					ucwords($commentposter[0]) . " " . ucwords($commentposter[1]) . // user name
				"</b> " . 
		
				"<font style=\"font-size: 11px; color: #333; float: right;\">" . 	// display timestamp
					formatDate($comment[3]) . 
				"</font> <br>".
					$comment[4] .													// actual comment 
				"<br></p>" . 
			 "</div>";
	}
	
	// New comment input
	echo "<div class=\"wallcomment\" style=\"border-radius: 0px 0px 4px 4px;\">" . 
	 		"<form action=\"include/insertpost.php\" method=\"post\">" . 
				"<input class=\"comment\" type=\"text\" name=\"comment\"  placeholder=\"Write a reply . . .\" value=\"\">" .
				"<input type=\"hidden\" name=\"parentpostid\" value=\"$post[0]\">" .
			"</form>" . 
		 "</div>";
}
					
closedb($mysqli);
?>	