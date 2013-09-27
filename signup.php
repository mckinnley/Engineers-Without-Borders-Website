<?php
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	ob_start();
	require("./include/functions.php");
	require("./templates/header.php");
?>
<!DOCTYPE html>

<html>
	<head></head>

<body>
<?php	
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		//var_dump($_POST);
		if(isNOE($_POST['email']) || isNOE($_POST['password'])  || isNOE($_POST['psswd']) || isNOE($_POST['first_name'])  
		                          || isNOE($_POST['last_name']) || isNOE($_POST['phone']) || isNOE($_POST['a']))
			panic("Please fill out all fields.");
		elseif( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) )
			panic("Enter a valid email address.");
		elseif( isNOE($_POST['chapter']) )
			panic("Select your chapter.");
		elseif(isNOE($_POST['q']))
			panic("Select a security question.");
		elseif(strlen($_POST['phone']) != 10 || !is_numeric($_POST['phone']))
			panic("Enter a valid phone number. Do not include dashes or parentheses.");
		elseif($_POST['password'] != $_POST['psswd'])
			panic("Passwords do not match.");
		elseif(isNOE($_POST['invite']) || !isValidInvite($_POST['invite']))
			panic("Invalid invitation code.");		
		else
		{
			$team = (strcmp($_POST['chapter'],'scfl') == 0) ? '7' : '1';
			
			$mysqli = opendb();
			
			$sql = "INSERT INTO users ( `profileid` , `email` ,     `passwordhash`,
					                    `first_name`, `last_name` , `phone` ,
					                    `chapter` ,   `q` ,         `a`,      
					                    `team` )
					           VALUES ( '" . guid() . "',?,?,?,?,?,?,?,?,?   )";
					
			// prevent SQL injection
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param('sssssssss', strtolower($_POST['email']),       md5($_POST['password']), strtolower($_POST['first_name']), 
					                       strtolower($_POST['last_name']),       $_POST['phone'],                $_POST['chapter'],    
					                                  $_POST['q'], md5(strtolower($_POST['a'])),  
					                                 $team);	
			$success = $stmt->execute();
			$stmt->close();
			
			closedb($mysqli);			
			if(!$success)
				DIE("Oops! Something went wrong.");		

			deleteInvite($_POST['invite']);
			ob_end_clean();
			header('Location: /signin.php');
		}
	}
?>
	<form name="signup" action='signup.php' method="post" class="signup">
		<section class="formbox"> 	
			<input class="signup" type="text" name="first_name" placeholder="First Name" required value="<?= htmlspecialchars($_POST['first_name']); ?>">
			
		</section>
		<section class="formbox2"> 
			<input class="signup" type="text" name="last_name"  placeholder="Last Name" required value="<?= htmlspecialchars($_POST['last_name']); ?>">
			<br><br>
		</section>
 		<section class="formbox">
 			<input class="signup" type="text" name="email"  placeholder="Email" required value="<?= htmlspecialchars($_POST['email']); ?>">
			<input class="signup" type="text" name="phone"  placeholder="Phone #" required value="<?= htmlspecialchars($_POST['phone']); ?>">
			<br><br>		
			<input class="signup" type="password" name="password" placeholder="Password" required>
			<br>
			<input class="signup" type="password" name="psswd" placeholder="Confirm Password" required>
			<br><br><br>
			<input class="signup" type="text" name="invite" placeholder="Invitation Code" required><br>
 		</section>
		<section class="formbox2">
			Chapter<br>
			<input class="signup" id="ucf" type="radio" name="chapter" value="ucf" <?php if($_POST['chapter'] == 'ucf') echo "checked";?>>
			<label for="ucf"></label>UCF
			<br>
			<input class="signup" id="scfl" type="radio" name="chapter" value="scfl" <?php if($_POST['chapter'] == 'scfl') echo "checked";?>>
			<label for="scfl"></label>SCFL
			<br><br>
			<select name="q" class="signup">
				<option>Select a security question . . .</option>
				<?php 
				$mysqli = opendb();
				$questions = $mysqli->query("SELECT * from SecurityQuestions");
				while ($qRow = $questions->fetch_row())
					secOpt($qRow[0], $qRow[1]);
				closedb($mysqli); 
				?>
 			</select>
 			<input class="answer" type="text" name="a"  placeholder="Answer" value="<?= htmlspecialchars($_POST['a']);?>">
		</section>	
		<section class="formbox2">
			<input type="submit" value="Sign Up" class="submit">
		</section>
	</form>
</body>
</html>
<?php ob_end_flush();?>
