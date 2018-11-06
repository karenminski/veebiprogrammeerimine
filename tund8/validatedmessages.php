<?php
    require("functions.php");
  //kui pole sisse loginud
  
  //kui pole sisselogitud
  if(!isset($_SESSION["userId"])){
	header("Location: index2.php");
    exit();	
  }
  
  //väljalogimine
  if(isset($_GET["logout"])){
	session_destroy();
	header("Location:  index2.php");
	exit();
  }
$msglist = readallvalidatedmessagesbyuser();
	$pageTitle = "Valideeritud sõnumid";
	require("header.php");

?>
<!DOCTYPE html>
<html>
<body>
  <h2>Valideeritud sõnumid valideerijate kaupa</h2>
  <hr>
  <?php echo $msglist; ?>
  <?php require("footer.php"); ?>
</body>
</html>