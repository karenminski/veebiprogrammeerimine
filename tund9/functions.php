<?php
  require("../../../config.php");
  $database = "if18_karen_mi_1";
  // Kasutan sessiooni 
  session_start();
  //SQL käsk andmete uuendamiseks
//Kõigi valideeritud sõnumite lugemine valideerija kaupa
  
  function addUserProfilePic($filename){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("INSERT INTO vpuserprofilepic (userid, filename) VALUES(?, ?)");
	echo $mysqli->error;
	$stmt->bind_param("is", $_SESSION["userId"], $filename);
	/* if(!empty filename){
		$myImage = ("../vp_picfiles/vp_user_generic.png");
	else{
		
	}
		
	} */
	if($stmt->execute()){
		echo "Profiilipilt laeti edukalt üles";
	}	else {
			echo "Profiilipildi laadimisel läks midagi viltu" . $stmt->error;
		} 
	if(!empty($_FILES["fileToUpload"]["name"])){
			
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
			
			$timeStamp = microtime(1) * 10000;
			
			$target_file_name = $_SESSION["userFirstName"] ."_" .$_SESSION["userLastName"] ."_" .$timeStamp ."." .$imageFileType;
			$target_file = $target_dir .$target_file_name;
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				$teade = "Fail on " . $check["mime"] . " pilt.";
			} else {
				$teade = "Fail ei ole pilt!";
				$uploadOk = 0;
			}
			// Check if file already exists
			if (file_exists($target_file)) {
				$teade = "Vabandage, selline pilt on juba olemas!";
				$uploadOk = 0;
			}
			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 2500000) {
				$teade = "Vabandust, pilt on liiga suur!";
				$uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				$teade = "Vabandage, ainult JPG, JPEG, PNG ja GIF failid on lubatud!";
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				$teade = "Kahjuks faili üles ei laeta!";
			// if everything is ok, try to upload file
			} else {
				//loome vastavalt failitüübile pildiobjekti
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
					$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "png"){
					$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "gif"){
					$myTempImage = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
				}
				$imageWidth = imagesx($myTempImage);
				$imageHeight = imagesy($myTempImage);
				//arvutan suuruse suhtarvu
				if($imageWidth > $imageHeight){
					$sizeRatio = $imageWidth / 300;
				} else {
					$sizeRatio = $imageHeight / 300;
				}
				$newWidth = round($imageWidth / $sizeRatio);
				$newHeight = round($imageHeight / $sizeRatio);
				$myImage = resizeImage($myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight);
				//lähtudes failitüübist, kirjutan pildifaili
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
					if(imagejpeg($myImage, $target_file, 95)){
						$teade =  "Fail " . basename( $_FILES["fileToUpload"]["name"]). " on edukalt üles laetud!";
					} else {
						$teade =  "Vabandage, faili ülelaadimisel tekkis tehniline viga!";
					}
				}
				if($imageFileType == "png"){
					if(imagepng($myImage, $target_file, 6)){
						$teade =  "Fail " . basename( $_FILES["fileToUpload"]["name"]). " on edukalt üles laetud!";
					} else {
						$teade =  "Vabandage, faili ülelaadimisel tekkis tehniline viga!";
					}
				}
				if($imageFileType == "gif"){
					if(imagegif($myImage, $target_file)){
						$teade =  "Fail " . basename( $_FILES["fileToUpload"]["name"]). " on edukalt üles laetud!";;
					} else {
						$teade =  "Vabandage, faili ülelaadimisel tekkis tehniline viga!";
					}
				}
				imagedestroy($myTempImage);
				imagedestroy($myImage);
			}	
		}
	$stmt->close();
	$mysqli->close();	
	return $stmt;
  }

  function addPhotoData($filename, $alttext, $privacy){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("INSERT INTO vpphotos (userid, filename, alttext, privacy) VALUES(?, ?, ?, ?)");
	echo $mysqli->error;
	if(empty($privacy) or $privacy > 3 or $privacy < 1){
		$privacy = 3;
	}
	$stmt->bind_param("issi", $_SESSION["userId"], $filename, $alttext, $privacy);
	if($stmt->execute()){
	}	else {
			echo "Andmebaasiga läks midagi viltu" . $stmt->error;
		}

	$stmt->close();
	$mysqli->close();
}

  function readprofilecolors(){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT bgcolor, txtcolor FROM vpuserprofiles WHERE userid=?");
	echo $mysqli->error;
	$stmt->bind_param("i", $_SESSION["userId"]);
	$stmt->bind_result($bgcolor, $txtcolor);
	$stmt->execute();
	$profile = new Stdclass();
	if($stmt->fetch()){
		$_SESSION["bgColor"] = $bgcolor;
		$_SESSION["txtColor"] = $txtcolor;
	} else {
		$_SESSION["bgColor"] = "#FFFFFF";
		$_SESSION["txtColor"] = "#000000";
	}
	$stmt->close();
	$mysqli->close();
  }
  
 function readuserprofile(){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT description, bgcolor, txtcolor FROM vpuserprofiles WHERE userid=?");
	echo $mysqli->error;
	$stmt->bind_param("i", $_SESSION["userId"]);
	$stmt->bind_result($description, $bgcolor, $txtcolor);
	$stmt->execute();
	$profile = new Stdclass();
	if($stmt->fetch()){
		$profile->description = $description;
		$profile->bgcolor = $bgcolor;
		$profile->txtcolor = $txtcolor;
	} else {
		$profile->description = "";
		$profile->bgcolor = "";
		$profile->txtcolor = "";
	}
	$stmt->close();
	$mysqli->close();
	return $profile;
  }

  function createuserprofile($desc, $bgcol, $txtcol){
	$userprofile = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT description, bgcolor, txtcolor FROM vpuserprofiles WHERE userid=?");
	echo $mysqli->error;
	$stmt->bind_param("i", $_SESSION["userId"]);
	$stmt->bind_result($description, $bgcolor, $txtcolor);
	$stmt->execute();
	if($stmt->fetch()){
		//profiil juba olemas, uuendame
		$stmt->close();
		$stmt = $mysqli->prepare("UPDATE vpuserprofiles SET description=?, bgcolor=?, txtcolor=? WHERE userid=?");
		echo $mysqli->error;
		$stmt->bind_param("sssi", $desc, $bgcol, $txtcol, $_SESSION["userId"]);
		if($stmt->execute()){
			$userprofile = "Profiil edukalt uuendatud!";
			$_SESSION["bgColor"] = $bgcol;
		    $_SESSION["txtColor"] = $txtcol;
		} else {
			$userprofile = "Profiili uuendamisel tekkis tõrge! " .$stmt->error;
		}
	} else {
		//profiili pole, salvestame
		$stmt->close();
		$stmt = $mysqli->prepare("INSERT INTO vpuserprofiles (userid, description, bgcolor, txtcolor) VALUES(?,?,?,?)");
		echo $mysqli->error;
		$stmt->bind_param("isss", $_SESSION["userId"], $desc, $bgcol, $txtcol);
		if($stmt->execute()){
			$userprofile = "Profiil edukalt salvestatud!";
			$_SESSION["bgColor"] = $bgcol;
		    $_SESSION["txtColor"] = $txtcol;
		} else {
			$userprofile = "Profiili salvestamisel tekkis tõrge! " .$stmt->error;
		}
	} 
	$stmt->close();
	$mysqli->close();
	return $userprofile;
  }

  function readallvalidatedmessagesbyuser(){
	$msghtml = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, firstname, lastname FROM vpusers");
	echo $mysqli->error;
	$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb);
	
	$stmt2 = $mysqli->prepare("SELECT message, accepted FROM vpamsg WHERE acceptedby=?");
	echo $mysqli->error;
	$stmt2->bind_param("i", $idFromDb);
	$stmt2->bind_result($messageFromDb, $acceptedFromDb);
	
	$stmt->execute();
	$stmt->store_result();//jätab saadu pikemalt meelde, nii saab ka järgmine päring seda kasutada
	
	while ($stmt -> fetch()){
			$userdata = "";
			$userdata .= "<h3>" . $firstnameFromDb . " " . $lastnameFromDb . "</h3> \n";
			$stmt2 -> execute();
			$counter = 0;
			while($stmt2 -> fetch()){
				$counter++;
				$userdata .= "<p><b>";
				if ($acceptedFromDb == 1){
					$userdata .= "Lubatud:";
				}
				else {
					$userdata .= "Keelatud:";
				}
				$userdata .= "</b> " . $messageFromDb . "</p> \n";
			}
			if ($counter > 0){ // Add to HTML if the user did (in)validate messages
				$msghtml .= $userdata;
			}
		}
		$stmt2 -> close();
		$stmt -> close();
		$mysqli -> close();
		
		return $msghtml;
	}
  
  function listusers(){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT firstname, lastname, email FROM vpusers WHERE id !=?");
	//$stmt = $mysqli->prepare("SELECT firstname, lastname, email, description FROM vpusers3, vpuserprofiles WHERE vpuserprofiles.userid=vpusers.id");
	
	$mysqli->error;
	$stmt->bind_param("i", $_SESSION["userId"]);
	$stmt->bind_result($firstname, $lastname, $email);
	//$stmt->bind_result($firstname, $lastname, $email, $description);
	if($stmt->execute()){
	  $notice .= "<ol> \n";
	  while($stmt->fetch()){
		  $notice .= "<li>" .$firstname ." " .$lastname .", kasutajatunnus: " .$email ."</li> \n";
		  //$notice .= "<li>" .$firstname ." " .$lastname .", kasutajatunnus: " .$email ."<br>" .$description ."</li> \n";
	  }
	  $notice .= "</ol> \n";
	} else {
		$notice = "<p>Kasutajate nimekirja lugemisel tekkis tehniline viga! " .$stmt->error;
	}
	
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
function allvalidmessages(){
		$notice = "";
		$accepted = 1;
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE accepted=? ORDER BY accepttime DESC");
		echo $mysqli -> error;
		
		$stmt->bind_param("i", $accepted);
		$stmt->bind_result($msg);
		$stmt->execute();
		
		while ($stmt -> fetch()){
			$notice .= "<p>" . $msg . "</p> \n";
		}
		
		$stmt->close();
		$mysqli->close();
	    return $notice;
  }
function validatemsg($editId, $validation){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("UPDATE vpamsg SET acceptedby=?, accepted=?, accepttime=now() WHERE id=?");
	$stmt->bind_param("iii", $_SESSION["userId"], $validation, $editId);
	if($stmt->execute()){
	  echo "Õnnestus";
	  header("Location: validatemsg.php");
	  exit();
	} else {
	  echo "Tekkis viga: " .$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
  }
  
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
		
		$stmt = $mysqli -> prepare("SELECT id, message FROM vpamsg WHERE accepted IS NULL"); //sort: ORDER BY id DESC
		echo $mysqli -> error;
		$stmt -> bind_result($msgid, $msg);
		
		if ($stmt -> execute()){
			while($stmt -> fetch()){
				$notice .= "<li>" . $msg . '<br><a href = "validatemessage.php?id=' . $msgid . '" >Valideeri</a></li>' . "\n";
			}
		}
		else {
			$notice .= "<li>Sõnumite lugemisel tekkis viga: " . $stmt -> error . "</li> \n";
		}
		
		$notice .= "</ul> \n";
		
		$stmt -> close();
		$mysqli -> close();
		
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
		     $_SESSION["userFirstName"] = $firstnameFromDb;
		     $_SESSION["userLastName"] = $lastnameFromDb;
		     $_SESSION["userEmail"] = $email;
			 $stmt->close();
	         $mysqli->close();
			 readprofilecolors();
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
		  $notice = "Kasutaja loomine õnnestus!";
	    } else {
	      $notice = "Kasutaja loomisel tekkis viga" .$stmt->error;	
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