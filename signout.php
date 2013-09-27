<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
 
setcookie("profileid", "", time()-3600, "/", "project.ewbucf.org", false, true);
setcookie("email", "", time()-60*60*24*30, "/", "project.ewbucf.org", false, true);
setcookie("passwordhash", "", time()-60*60*24*30, "/", "project.ewbucf.org", false, true);
setcookie("team", "", time()-60*60*24*30, "/", "project.ewbucf.org", false, true);

header('Location: /index.php');
?>
