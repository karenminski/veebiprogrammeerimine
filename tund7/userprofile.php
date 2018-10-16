<?php
    require("functions.php");
  $notice = null;
  $userid = $_SESSION["userId"];
  $description = "";
  $bgcolor = "";
  $txtcolor = "";
  
  if(!isset($_SESSION["userId"])){
	header("Location: index2.php");
    exit();	
  }

  if(isset($_GET["logout"])){
	session_destroy();
	header("Location:  index2.php");
	exit();
  }
  
// $mydescription = createuserprofile($userid, $description, $bgcolor, $txtcolor);
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
  <textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea>
  <br>
  <br>
  <label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>">
  <br>
  <br>
  <label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $mytxtcolor; ?>">
  <br>
  <br>
  <input type="submit" name="submitUserProfile" value ="Loo kasutajaprofiil">
  </form>
  <ul>
	<li><a href="?logout=1">Logi välja</a>!</li>
	<li><a href="main.php">Tagasi</a> pealehele!</li>
  </ul>

</body>
</html>