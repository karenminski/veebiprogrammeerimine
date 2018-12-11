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
  $expire = date("Y-m-d");
  $news = readnews($expire);
  $pageTitle = "Uudised";
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
	  <div id="news">
	<?php
		echo $news;
	?>
	</div>
	<?php require("footer.php"); ?>
  </body>
</html>