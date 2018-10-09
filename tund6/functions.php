<?php
  require("../../../config.php");
  $database = "if18_karen_mi_1";
  
  // Kasutan sessiooni 
  session_start();
  //SQL käsk andmete uuendamiseks
  //UPDATE vpamsg SET acceptedby=?, accepted=?, acceptedtime=now() WHERE id=?
  
  //Valitud sõnumi valideerimiseks
  
  function readmsgforvalidation($editId){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE id = ?");
	$stmt->bind_param("i", $editId);
	$stmt->bind_result($msg);
	$stmt->execute();
	if($stmt->fetch()){
		$notice = $msg;
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  //valideerimata sõnumite nimekiri
  function readallunvalidatedmessages(){
	$notice = "<ul> \n";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]); 
	$stmt = $mysqli->prepare("SELECT id, message FROM vpamsg WHERE accepted IS NULL");
	  //SELECT id, message FROM vpamsg WHERE accepted IS NULL ORDER BY id DESC
	echo $mysqli->error;
	$stmt->bind_result($msgid, $msg);
	if($stmt->execute()){
		while($stmt->fetch()){
			$notice .= "<li>" .$msg .'<br><a href="validatemessage.php?id=' .$msgid .'">Valideeri</a></li>' ."\n";
		}
	} else {
		$notice .= "<li>Sõnumite lugemisel tekkis viga!" .$stmt->error ."</li> \n";
	}
	$notice .= "</ul> \n";
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  //sisselogimine
  function signin($email, $password){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, firstname, lastname, password FROM vpusers WHERE email=?"); // Kui on ? siis on vaja ka bind parami
	echo $mysqli->error;
	$stmt->bind_param("s", $email);
	$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb, $passwordFromDb);
	if($stmt->execute()){
	 //Andmebaasi päring õnnestus
	  if($stmt->fetch()){
		 //Kasutaja on olemas
		 if(password_verify($password, $passwordFromDb)){
			 // Parool õige
			 $notice="Olete õnnelikult sisse logitud";
			 //määrame sessioonimuutujad
			 $_SESSION["userId"] = $idFromDb;
			 $_SESSION["FirstName"] = $firstnameFromDb;
			 $_SESSION["LastName"] = $lastnameFromDb;
			 $stmt->close();
	         $mysqli->close();
			 header("Location: main.php");
			 exit();
			 
		 } else {
			 $notice="Kahjuks vale salasõna!";
		 }
	 } else {
		$notice="Kahjuks sellise kasutajatunnusega (" .$email .") kasutajat ei leitud";	 
		 }
	} else {
	  $notice="Sisselogimisel tekkis tehniline viga" . $stmt->error;
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  
  function signup($name, $surname, $email, $gender, $birthDate, $password){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//kontrollime, ega kasutajat juba olemas pole
	$stmt = $mysqli->prepare("SELECT id FROM vpusers WHERE email=?");
	echo $mysqli->error;
	$stmt->bind_param("s",$email);
	$stmt->execute();
	if($stmt->fetch()){
		//leiti selline, seega ei saa uut salvestada
		$notice = "Sellise kasutajatunnusega (" .$email .") kasutaja on juba olemas! Uut kasutajat ei salvestatud!";
	} else {
		$stmt->close();
		$stmt = $mysqli->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)");
    	echo $mysqli->error;
	    $options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
	    $pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
	    $stmt->bind_param("sssiss", $name, $surname, $birthDate, $gender, $email, $pwdhash);
	    if($stmt->execute()){
		  $notice = "ok";
	    } else {
	      $notice = "error" .$stmt->error;	
	    }
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