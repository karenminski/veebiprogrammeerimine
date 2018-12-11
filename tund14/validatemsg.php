<?php
  require("functions.php");
  // kui pole sisse loginud
  if(!isset($_SESSION["userId"])){
	  header("Location: index2.php");
	  exit();
  }
  //Välja logimine
  if(isset ($_GET["logout"])){
	  session_destroy(); 
	  header("Location: index2.php");
	  exit();
  }
  $msglist = readallunvalidatedmessages();
  
  	$pageTitle = "anonüümsed sõnumid";
	require("header.php");
?>
<!DOCTYPE html>
<html>
<body>
  
<?php echo $msglist; ?>
<?php require("footer.php"); ?>
</body>
</html>