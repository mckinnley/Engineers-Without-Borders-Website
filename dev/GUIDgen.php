<?php
require("../include/functions.php");

if(!isAdmin())
	header('Location: /');

echo "php GUID Generator<br>";
?>

<form action="GUIDgen.php" method="post">
	<input name="num" type="text" value="<?php echo $_POST['num']; ?>">
	<input type="submit" value="Generate">
</form>

<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	for($i = 0; $i < $_POST['num']; $i++)
	{
		$x = guid();
	
		$mysqli = opendb();
		$video = $mysqli->query("INSERT INTO  `ewbucfPM`.`Invitations` (`code`)
								  VALUES ('$x');");
				
		echo $x . "<br>";
	}
}
?>