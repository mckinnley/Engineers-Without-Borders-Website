<?php 
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	
	require("./include/functions.php");
	
	if (!isSignedIn())
		header('Location: http://project.ewbucf.org/signin.php');
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		if(isGuest())
			panic("Guests cannot make posts.");		
		elseif (isNOE($_POST['update']))
			panic("You cannot make an empty update post!");
		else
		{
			$mysqli = opendb();
	
			// get team # for user
			$userInfo = $mysqli->query("Select team, chapter from users where profileid='" . $_COOKIE['profileid'] . "'");
			$team = $userInfo->fetch_row();
	
			// prepare to insert post to db
			$sql = "INSERT INTO updates ( `posterid` , `text`, `team`)
			        VALUES ('" . $_COOKIE['profileid'] . "',?," . $team[0] . ")";
				
			// insert post
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param('s', removeHTML($_POST['update']));
			$success = $stmt->execute();
			$stmt->close();
				
			closedb($mysqli);
			if(!$success)
				DIE("Oops! Something went wrong.");
		}
	}
	
	include $_SERVER["DOCUMENT_ROOT"].'/project/templates/header.php';
?>
<!DOCTYPE html> 

<html>
	<head>
		<title>EWB-USA, University of Central Florida</title>
	</head>
	<body>
		<div id="content" >
			<section class="main">
				<h1 style="color: <?= $color6; ?>; border-bottom: 2px dashed <?= $color2;?>; padding: 0px 210px 4px 230px;">Wall</h1>
				<form action="<?php echo (!isGuest()) ? 'include/insertpost.php' : 'index.php'; ?>" method="post" style="border-bottom: 1px solid <?= $color6;?>; padding: 0px 0px 8px 0px; margin-bottom: 10px;">
					<input class="wall" type="text" name="wall"  placeholder="Post to the wall . . ." value="">
					<input type="submit" value="Post" class="btn">
				</form>
				<div class="wall"><?php require('html/wallfeed.php'); ?></div>
			</section>
		
			<section class="myinfo" style="text-align: center;">
				<?php require('html/myinfo.php'); ?>
			</section>
			<section class="contact">
				<h1 style="color: #b5cde4;">My Team</h1><br>
				<div style="overflow: scroll; height: 250px;"><?php require('html/myteam.php'); ?></div>
			</section>
			<section class="meetings">
				<h1 style="padding:  0px 31px 4px 31px;">Upcoming Meetings</h1><br>
				<?php require('html/meetings.php'); ?>
			</section>
			<section class="updates">
				<h1 style="color: <?= $color6;?>;">Updates</h1><br>
				<form action="index.php" method="post" style="border-bottom: 1px solid <?= $color6;?>; padding: 0px 0px 8px 0px; margin-bottom: 10px;">
					<input class="postit" type="text" name="update"  placeholder="Post an update . . ." value="" maxlength="160">
					<input type="submit" value="Post" class="btn">
				</form>
				<div class="updates">
				<?php require('html/updatefeed.php'); ?>
				</div>
			</section>
       	</div>
	<?php renderFooter('Copyright &copy ' . date('Y', time()) .  ', Josh Kaplan');?>
	</body>
</html>
