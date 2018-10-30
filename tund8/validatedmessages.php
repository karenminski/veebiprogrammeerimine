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
  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
  <hr>
  <ul>
	<li><a href="?logout=1">Logi välja</a>!</li>
	<li><a href="main.php">Tagasi</a> pealehele!</li>
  </ul>
  <hr>
  <h2>Valideeritud sõnumid valideerijate kaupa</h2>
  <hr>
  <?php echo $msglist; ?>
</body>
</html>