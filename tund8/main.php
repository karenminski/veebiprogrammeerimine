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
  
/*   $profiledetails = readuserprofile($_SESSION["userId"]);

	if ($profiledetails != null){
		if ($profiledetails[1] != null){
			$_SESSION["txtcolor2"] = $profiledetails[1];
		}
		
		if ($profiledetails[2] != null){
			$_SESSION["bgcolor2"] = $profiledetails[2];
		}
	} */
	// Lehe päise laadimine
	$pageTitle = "Pealeht";
	require("header.php");
?>

<!DOCTYPE html>
<html>
	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<p>Olete sisse loginud nimega: 
	<?php
	echo $_SESSION["userFirstName"] ." " .$_SESSION["userLastName"];
	?>
	</p>
	<ul>
	<li>Vaata oma<a href="userprofile.php"> profiili</a></li>
	<li>Süsteemi <a href="users.php">kasutajad</a>.</li>
	<li>Valideeri anonüümseid <a href="validatemsg.php">sõnumeid</a></li>
	<li>Vaata valideeritud <a href="validatedmessages.php">sõnumeid</a> kasutajate kaupa</li>
	<li>Piltide <a href="photoupload.php">üleslaadimine</a></li>
    <li><a href="?logout=1">Logi välja</a></li>
	</ul>
	
  </body>
</html>