<?php
    require("functions.php");		
/*   $notice = null;
  $userid = $_SESSION["userId"];
  $description = "";
  $bgcolor = "";
  $txtcolor = ""; */
  $description2 = "Pole tutvustust lisanud!";
  $bgcolor2 = "#FFFFFF";
  $txtcolor2 = "#000000";
  
  if(!isset($_SESSION["userId"])){
	header("Location: index2.php");
    exit();	
  }

  if(isset($_GET["logout"])){
	session_destroy();
	header("Location:  index2.php");
	exit();
  }
  
  
  if(isset($_POST["submitProfile"])){
	$notice = createuserprofile($_POST["description"], $_POST["bgcolor"], $_POST["txtcolor"]);
	if(!empty($_POST["description"])){
	  $description2 = $_POST["description"];
	}
	$bgcolor2 = $_POST["bgcolor"];
	$txtcolor2 = $_POST["txtcolor"];
  } else {
	$myprofile = readuserprofile();
	if($myprofile->description != ""){
	  $description2 = $myprofile->description;
    }
    if($myprofile->bgcolor != ""){
	  $bgcolor2 = $myprofile->bgcolor;
    }
    if($myprofile->txtcolor != ""){
	  $txtcolor2 = $myprofile->txtcolor;
    }
  }
  
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
	<title>
	  <?php
	    echo $_SESSION["userFirstName"];
		echo " ";
		echo $_SESSION["userLastName"];
	  ?>
	 profiil</title>
	 	<style>
	  <?php
        echo "body{background-color: " .$_SESSION["bgColor"] ."; \n";
		echo "color: " .$_SESSION["txtColor"] ."} \n";
	  ?>
	</style>
</head>
<body>
  <h1>
	  <?php
	    echo $_SESSION["userFirstName"] ." " .$_SESSION["userLastName"];
	  ?>
	</h1>
	<b>Kirjelda ennast</b>
  <form method ="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <br>
  <textarea rows="10" cols="80" name="description"><?php echo $description2; ?></textarea>
  <br>
  <br>
  <label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $bgcolor2; ?>">
  <br>
  <br>
  <label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $txtcolor2; ?>">
  <br>
  <br>
  <input type="submit" name="submitProfile" value ="Salvesta profiil">
  </form>
  <hr>
  <ul>
	<li><a href="?logout=1">Logi välja</a>!</li>
	<li><a href="main.php">Tagasi</a> pealehele!</li>
  </ul>
  <hr>

</body>
</html>