<?php
require("../../../config.php");
$database = "if18_karen_mi_1";
session_start();

function messageseen($editId, $seen){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("UPDATE vpamsgeksam SET seen=? WHERE id=?");
	$stmt->bind_param("ii", $seen, $editId);
	if($stmt->execute()){
	  echo "Õnnestus";
	  exit();
	} else {
	  echo "Tekkis viga: " .$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
  }  
  
    function readmsgforseen($editId){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT message FROM vpamsgeksam WHERE id = ?");
	$stmt->bind_param("i", $editId);
	$stmt->bind_result($msglist);
	$stmt->execute();
	if($stmt->fetch()){
		$notice = $msglist;
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }

 function readallmessagesbyuser(){
	$msghtml = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, firstname, lastname FROM vpuserseksam");
	echo $mysqli->error;
	$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb);
	$stmt2 = $mysqli->prepare("SELECT message, created, sentby FROM vpamsgeksam WHERE sentto=? ORDER BY created ASC");
	echo $mysqli->error;
	$stmt2->bind_param("i", $idFromDb);
	$stmt2->bind_result($messageFromDb, $createdFromDb, $sentbyFromDb);
	$stmt->execute();
	$stmt->store_result();
	while ($stmt -> fetch()){
		$userdata = "";
		$userdata .= "<h3>" . $firstnameFromDb . " " . $lastnameFromDb . "</h3> \n";
		$stmt2 -> execute();
		while($stmt2 -> fetch()){
			$userdata .= "<p><b>";
			$userdata .= "</b> " . $messageFromDb .' |Saadetud: ' .$createdFromDb .' kasutajalt: ' .$sentbyFromDb ."</p> \n";
		}
			$msghtml .= $userdata ;
	}
		$stmt2 -> close();
		$stmt -> close();
		$mysqli -> close();
		return $msghtml;
	}
  function sendamsg($msg, $sentto){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("INSERT INTO vpamsgeksam (sentby, message, sentto) VALUES(?, ?, ?)");
	echo $mysqli->error;
	$stmt->bind_param("isi", $_SESSION["userId"], $msg, $sentto);
	if ($stmt->execute()){
	 $notice = 'Sõnum: "' .$msg .'" on edukalt saadetud!';	
    } else {
	  $notice = "Sõnumi saatmisel tekkis viga: " .$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
    function listusers(){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT firstname, lastname, email FROM vpuserseksam WHERE id !=?");	
	$mysqli->error;
	$stmt->bind_param("i", $_SESSION["userId"]);
	$stmt->bind_result($firstname, $lastname, $email);
	if($stmt->execute()){
	  $notice .= "<ol> \n";
	  while($stmt->fetch()){
		  $notice .= "<li>" .$firstname ." " .$lastname .", kasutajatunnus: " .$email ."</li> \n";
	  }
	  $notice .= "</ol> \n";
	} else {
		$notice = "<p>Kasutajate nimekirja lugemisel tekkis tehniline viga! " .$stmt->error;
	}
	
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  function signin($email, $password){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, firstname, lastname, password FROM vpuserseksam WHERE email=?");
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
		     $_SESSION["userFirstName"] = $firstnameFromDb;
		     $_SESSION["userLastName"] = $lastnameFromDb;
		     $_SESSION["userEmail"] = $email;
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
  
  
  function signup($name, $surname, $email, $password){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//kontrollime, ega kasutajat juba olemas pole
	$stmt = $mysqli->prepare("SELECT id FROM vpuserseksam WHERE email=?");
	echo $mysqli->error;
	$stmt->bind_param("s", $email);
	$stmt->execute();
	if($stmt->fetch()){
		//leiti selline, seega ei saa uut salvestada
		$notice = "Sellise kasutajatunnusega (" .$email .") kasutaja on juba olemas! Uut kasutajat ei salvestatud!";
	} else {
		$stmt->close();
		$stmt = $mysqli->prepare("INSERT INTO vpuserseksam (firstname, lastname, email, password) VALUES(?,?,?,?)");
    	echo $mysqli->error;
	    $options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
	    $pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
	    $stmt->bind_param("ssss", $name, $surname, $email, $pwdhash);
	    if($stmt->execute()){
		  $notice = "Kasutaja loomine õnnestus!";
	    } else {
	      $notice = "Kasutaja loomisel tekkis viga" .$stmt->error;	
	    }
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
 }
?>