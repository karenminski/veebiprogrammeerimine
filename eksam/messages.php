<?php
  require("functions.php");
  if(!isset($_SESSION["userId"])){
	  header("Location: index.php");
	  exit();
  }
  if(isset ($_GET["logout"])){
	  session_destroy(); 
	  header("Location: index.php");
	  exit();
  }
  
	$msglist = readallmessagesbyuser();
	$pageTitle = "Sõnumid";
	require("header.php");
?> 

<!DOCTYPE html>
<html>
    <p><?php echo $msglist; ?></p>
	<?php require("footer.php"); ?>
</html> 