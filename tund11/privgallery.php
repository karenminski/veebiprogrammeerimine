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
  $page = 1;
  $totalImages = findTotalPrivateImages();
  //echo $totalImages;
  $limit = 10;
  
  if(!isset($_GET["page"]) or $_GET["page"] < 1){
	  $page = 1;
  } elseif (round(($_GET["page"] - 1) * $limit) > $totalImages){
	  $page = round($totalImages / $limit) - 1;
  } else {
	  $page = $_GET["page"];
  }
  //$publicThumbnails = readAllPublicPictureThumbs();
  $privateThumbnails = readAllPrivatePictureThumbsPage($page, $limit);
  
  //$userslist = listusers();
  //$thumbs = allPublicPictureThumbsPage(2);
  $pageTitle = "Privaatsed pildid";
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
	<?php
		echo "<p>";
		if ($page > 1){
			echo '<a href="?page=' .($page - 1) .'">Eelmised pildid</a> ';
		} else {
			echo "<span>Eelmised pildid</span> ";
		}
		if ($page * $limit < $totalImages){
			echo '| <a href="?page=' .($page + 1) .'">Järgmised pildid</a>';
		} else {
			echo "| <span>Järgmised pildid</span>";
		}
		echo "</p> \n";
		echo $privateThumbnails;
	?>
	<?php require("footer.php"); ?>
  </body>
</html>