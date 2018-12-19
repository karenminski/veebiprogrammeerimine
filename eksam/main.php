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

	$pageTitle = "Pealeht";
	require("header.php");
?>

<!DOCTYPE html>
<html>
	<ul>
	<li>Süsteemi <a href="users.php">kasutajad</a>.</li>
	<li>Saada <a href="sendmessages.php"> sõnumeid</a></li>
	<li>Vaata saadud <a href="messages.php"> sõnumeid</a></li>
	</ul>
<?php require("footer.php"); ?>
  </body>
</html>