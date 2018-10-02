<?php
  require("../../../config.php");
  $database = "if18_karen_mi_1";
  
  function signup($firstName, $lastName, $birthDate, $gender, $email, $password){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES(?, ?, ?, ?, ?, ?)");
	echo $mysqli->error;
	// krüpteerime parooli
	$options = ["cost"=>12, "salt"=>substr(sha1(mt_rand()), 0,22)]; // cost/krüpt aeg 12 max []massiivis eraldame komadega
	$pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
	$stmt->bind_param("sssiss", $firstName, $lastName, $birthDate, $gender, $email, $pwdhash);
	if($stmt->execute()){
		$notice = "Kasutaja loomine õnnestus";
	} else {
		$notice = "Kasutaja loomisel tekkis viga: " .$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  
  function saveamsg($msg){
	$notice = "";
	//Loome andmebaasi ühenduse
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//valmistan ette andmebaasikäsu 
	$stmt = $mysqli->prepare("INSERT INTO vpamsg (message) VALUES(?)");
	echo $mysqli->error;
	//asendan küsimärgid päris andmetega
	//esimesena kirja andmetüübid siis andmed ise
	//s - string; i - integer; d - decimal
	$stmt->bind_param("s", $msg);
	//täidame ettevalmistatud käsu
	if ($stmt->execute()){
	 $notice = 'Sõnum: "' .$msg .'" on edukalt salvestatud!';	
    } else {
	  $notice = "Sõnumi salvestamisel tekkis viga: " .$stmt->error;
	}
	//sulgeme ettevalmistatud käsu
	$stmt->close();
	//sulgeme ühenduse
	$mysqli->close();
	return $notice;
  }
  
  function readallmessages(){
	  $notice = "";
	  $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	  $stmt = $mysqli->prepare("SELECT message FROM vpamsg");
	  echo $mysqli->error;
	  $stmt->bind_result($msg);
	  $stmt->execute();
	  while($stmt->fetch()){
		  $notice .= "<p>" .$msg ."</p> \n";
	  }
	  $stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  
 //teksti sisendi kontrollimine
 function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
 }
 
 function addcat($catname, $catcolor, $cattaillength) {
	$notice = ""; // Muutuja, kuhu lisatakse andmebaasi sisu
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]); // Muutujad andmebaasi sisselogimiseks
	// Sisesta kass
	$stmt = $mysqli->prepare("INSERT INTO kiisu (nimi, v2rv, saba) VALUES(?, ?, ?)"); // Valmista ette SQL-käsk
	echo $mysqli->error; // Vea korral teata sellest
	$stmt->bind_param("ssi", $catname, $catcolor, $cattaillength); // Lisa muutujate sisu SQL-käsku
	$stmt->close(); // sulge tabel 
	
	// Võta kassid välja
	$stmt = $mysqli->prepare("SELECT nimi, v2rv, saba FROM kiisu ORDER BY kiisu_id");
	echo $mysqli->error;
	$stmt->bind_result($catname, $catcolor, $cattaillength);
	$stmt->execute();
	while($stmt->fetch()){
		$notice .= "<li>" .$catname ." " .$catcolor ." " .$cattaillength ."</li> \n";
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
?>