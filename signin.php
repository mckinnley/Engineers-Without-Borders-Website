<?php 
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	include $_SERVER["DOCUMENT_ROOT"].'/project/include/functions.php';
	
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		//var_dump($_POST);
		if(!isnullorempty($_POST['email']) && !isnullorempty($_POST['password']))
		{
			$mysqli = opendb();
			$sql = "SELECT profileid, team from users where email=? and passwordhash = ?";
					
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param('ss', $_POST['email'], md5($_POST['password']));
			$success = $stmt->execute();
			$profileid = NULL;
			
			if($success) 
			{
				$stmt->bind_result($profileid, $team);
				$stmt->fetch();
			}
			$stmt->close();
			
			closedb($mysqli);
			
			if($success && !isNOE($profileid))
			{
				$expire=time()+60*60*24*30;  // expire in 1 month
				setcookie("profileid", $profileid, $expire, "/", "project.ewbucf.org", false, true);
				setcookie("email", $_POST["email"], $expire, "/", "project.ewbucf.org", false, true);
				setcookie("passwordhash", md5($_POST['password']), $expire, "/", "project.ewbucf.org", false, true);
				setcookie("team", $team, $expire, "/", "project.ewbucf.org", false, true);
				
				header('Location: /');
			}
			else
				panic("Invalid username or password.");			
		}
	}
	
	require('/templates/header.php');
?>
<!DOCTYPE html>

<html>
	<head>
		<title>integrateU.com</title>
		<link href="/css/signin.css?=<?php echo date('l jS \of F Y h:i:s A')?>" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="wrapper">
			<section>
				<form action="signin.php" method="post">
    				<input class="main-form" type="text" name="email" placeholder="username" required value="<?= htmlspecialchars($_POST["email"]); ?>">
    				<input class="main-form" type="password" name="password" placeholder="password" required value="<?= htmlspecialchars($_POST["password"])?>">
    				<button>Sign in</button>
   				</form>
 			</section>
  			<footer style="bottom: 140px; left: 37%;">
  				<a href="signup.php" style="color: #fff; padding: 0px 80px 0px 0px; font-size: 18px;">Sign Up</a>
  				<a href="#" style="color: #fff; padding: 0px 0px 0px 0px; font-size: 18px;">Forgot your password?</a>
  			</footer>
		</div>
	</body>
</html>

<?php require('templates/footer.php'); ?>