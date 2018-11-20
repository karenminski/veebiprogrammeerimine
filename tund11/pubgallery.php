<?php
  require("functions.php");
  //kui pole sisse loginud
  if(!isset($_SESSION["userId"])){
	  header("Location: index2.php");
	  exit();
  }
  
  //väljalogimine
  if(isset($_GET["logout"])){
	session_destroy();
	header("Location: index2.php");
	exit();
  }
  
  //$userslist = listusers();
  $thumbs = allPublicPictureThumbsPage(2);
  $pageTitle = "Avalikud pildid";
  require("header.php");
?>

<!DOCTYPE html>
<html>
  <body>
    <h1>
	  <?php
	    echo $_SESSION["userFirstName"] ." " .$_SESSION["userLastName"];
	  ?>
	</h1>
	<?php echo $thumbs; ?>
	<?php require("footer.php"); ?>
  </body>
</html>