<?php
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	ob_start();
	
	if(!isAdmin)
		header('Location: /'); // comment out this line to open applications
	
	require("./include/functions.php");
	require("./templates/header.php");
?>
<!DOCTYPE html>

<html>
	<head></head>

<body>
<?php	
	if(isAdmin() || isLiaison()):
		$mysqli = opendb();

		$AllApplications = $mysqli->query("SELECT * FROM applications order by created_on desc");
		while($app = $AllApplications->fetch_row())
		{ ?>
			<section class="application">
				<p style="line-height: 1.4;"> 
					<b style="color: #095aa6; font-size: 18px;>"> <?= ucwords($app[0]) . " " . ucwords($app[1]); ?> </b>
					<font class="timestamp" style="font-size: 16px;">Applied <?= formatDate($app[15])?></font> <br>
					<b>Gender: </b><?= $app[2];?><br>
					<b>Email: </b><?= $app[3];?><br>
					<b>Phone: </b><?= $app[4];?><br>
					<b>Major: </b><?= $app[5];?><br>
					<b>Minor: </b><?= $app[6];?><br>
					<b>GPA: </b><?= $app[7];?><br>
					<b>Expected Graduation: </b><?= $app[8];?><br>
					<b>Current Courses: </b><?= $app[9];?><br>
					<b>Experience: </b><?= $app[10];?><br>
					<b>Software/Computer Skills: </b><?= $app[11];?><br>
					<b>Awards/Honors: </b><?= $app[12];?><br>
					<b>Extracurriculars: </b><?= $app[13];?><br>
					<b>Time Available: </b><?= $app[14];?><br>
				</p>
			</section>
		<?php }?>
		<?php 
		closedb($mysqli); 
		renderFooter('Copyright &copy ' . date('Y', time()) .  ', Josh Kaplan');

	elseif($_SERVER['REQUEST_METHOD'] == 'POST'):
		//var_dump($_POST);
		if( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) )
			panic("Enter a valid email address.");
		elseif( isNOE($_POST['expgrad']) )
			panic("Select your Graduation Date.");
		else
		{

			$mysqli = opendb();
			
			$sql = "INSERT INTO applications ( `first_name`,`last_name`,`gender`,`email`,`phone`,
					                           `major`,`minor`,`gpa`,`graduation`,`current_courses`,
					                           `experience`,`comp_skills`,`awards`,`extracurricular`,`time`)     
					           VALUES ( ?,?,?,?,?, ?,?,?,?,?, ?,?,?,?,? )";
					
			// prevent SQL injection
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param('sssssssssssssss', 
				   removeHTML(strtolower($_POST['first_name'])),  removeHTML(strtolower($_POST['last_name'])), $_POST['gender'],
				   removeHTML(strtolower($_POST['email'])), removeHTML($_POST['phone']), removeHTML(strtolower($_POST['major'])), 
				   removeHTML(strtolower($_POST['minor'])), removeHTML($_POST['gpa']),  removeHTML($_POST['expgrad']), 
				   removeHTML($_POST['course']), removeHTML($_POST['exp']), removeHTML($_POST['comp']), 
				   removeHTML($_POST['awards']),removeHTML($_POST['extra']), removeHTML($_POST['time']) );	
			$success = $stmt->execute();
			$stmt->close();
			
			closedb($mysqli);			
			if(!$success)
				DIE("Oops! Something went wrong.");		
			ob_end_clean();
			//header('Location: /signin.php');
		}
		require("./templates/header.php");
?>
<h2 style="margin: 20px 0px 0px 100px;">Your application has been submitted. Thank you!</h2>
<?php else: ?>
<h1 style="margin-left: 100px;">EWB-UCF Project Team Application</h1>
	<form name="apply" action='application.php' method="post" class="apply" id="apply">	
		<section class="apply1"> 	
		<h2>Personal Info</h2>
			<input class="apply" type="text" name="first_name" placeholder="First Name" required value="<?= htmlspecialchars($_POST['first_name']); ?>">
			<input class="apply" type="text" name="last_name"  placeholder="Last Name" required value="<?= htmlspecialchars($_POST['last_name']); ?>">
			<br><br>
			<input class="signup" id="m" type="radio" name="gender" value="m" <?php if($_POST['gender'] == 'm') echo "checked";?>>
			<label for="m"></label>M
			<input class="signup" id="f" type="radio" name="gender" value="f" <?php if($_POST['gender'] == 'f') echo "checked";?>>
			<label for="f"></label>F
			
			<br><br>
			<h2>Contact Info</h2>
			<input class="apply" type="email" name="email"  placeholder="Email" size="25" required value="<?= htmlspecialchars($_POST['email']);?>">
			<input class="apply" type="text" name="phone"  placeholder="Phone #" maxlength="10" size="10" required value="<?= htmlspecialchars($_POST['phone']); ?>">
			<br><br>	
			<h2>Academic Info</h2>
			<input class="apply" type="text" name="major" placeholder="Major" size="25" required value="<?= htmlspecialchars($_POST['major']); ?>">
			<input class="apply" type="text" name="minor"  placeholder="Minor (if applicable)" size="15" value="<?= htmlspecialchars($_POST['minor']); ?>">
			<input class="apply" type="text" name="gpa"  placeholder="GPA" maxlength="5" size="5" required value="<?= htmlspecialchars($_POST['gpa']); ?>">
			<br><br>
			<select name="expgrad" class="apply">
				<option>Expected graduation . . .</option>
				<option value="2013" <?php if ($_POST['expgrad'] == '2013') echo "selected"?>>2013</option>
				<option value="2014" <?php if ($_POST['expgrad'] == '2014') echo "selected"?>>2014</option>
				<option value="2015" <?php if ($_POST['expgrad'] == '2015') echo "selected"?>>2015</option>
				<option value="2016" <?php if ($_POST['expgrad'] == '2016') echo "selected"?>>2016</option>
				<option value="2017" <?php if ($_POST['expgrad'] == '2017') echo "selected"?>>2017</option>
				<option value="2018" <?php if ($_POST['expgrad'] == '2018') echo "selected"?>>2018</option>
 			</select>
		</section>
		<section class="apply2"> 	
			<h2  style="font-size: 18px;">Current Courses</h2>
			<textarea name="course" class="apply" rows="3" cols="25" placeholder="Statics, diff. eq., physics II, measurements . . ." form="apply"></textarea>
			<br><br>
			<h2 style="font-size: 18px;">Experience</h2>	
	    	<textarea name="exp" class="apply" rows="5" cols="25" placeholder="Work, research, projects . . ." form="apply"></textarea>
	    	<br><br>
	    	<h2  style="font-size: 18px;">Software/Computer Skills</h2>
			<textarea name="comp" class="apply" rows="2" cols="25" placeholder="Microsoft office, MATLAB, AutoCAD, PHP, etc. . . ." form="apply"></textarea>
			
		</section>
	
		<section class="apply3">
			<h2 style="font-size: 18px;">Awards &amp;Honors</h2>	
	    	<textarea name="awards" class="apply" rows="3" cols="25" placeholder="List any awards or honors you have recieved . . ." form="apply"></textarea>
			<br><br>
			<h2 style="font-size: 18px;">Extracurricular Activities</h2>	
	    	<textarea name="extra" class="apply" rows="4" cols="25" placeholder="Clubs, societies, work, etc. . . ." form="apply"></textarea>
		
			<br><br>
			
			<h2 style="font-size: 16px;">How much time can you commit to the project?</h2>	
	    	<textarea name="time" class="apply" rows="1" cols="25" placeholder="ex. 6 hours per week . . ." form="apply"></textarea>
		
 			<!-- What is your favorite color? <input class="apply" type="color" name="color" value="#357ae8"> -->
		</section>	
	
		<section class="apply4">
			<input type="submit" value="Submit Application" class="submit">
		</section>
	</form>
<?php endif;?>
</body>
</html>
<?php ob_end_flush();
renderFooter('Copyright &copy ' . date('Y', time()) .  ', Josh Kaplan');
?>
