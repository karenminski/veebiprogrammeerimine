<?php
  require("functions.php");
  require("classes/Photoupload.class.php");
  require("../../../config.php");
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
  
  /* require("classes/Test.class.php");
  $myNumber = new Test(7);
  echo "Avalik arv on: " .$myNumber->publicNumber;
  //echo "Salajane arv on: " .$myNumber->secretNumber;
  $myNumber->tellThings();
  $mySNumber = new Test(9);
  echo " Teine avalik arv on: " .$mySNumber->publicNumber;
  unset($myNumber); */
  
  //Piltide üleslaadimise osa
    $notice = "";
	//$target_dir = "../vppicuploads/";
	$target_dir = $picDir;
	//$thumb_dir = "../vp_thumb_uploads/";
	//$thumb_dir = $thumbDir;
	$thumbSize = 100;
	$targetFile = "";
	$uploadOk = 1;
	$imageNamePrefix = "vp_";
    $textToImage = "Veebiprogrammeerimine";
    $pathToWatermark = "../vp_picfiles/vp_logo_w100_overlay.png";
	
	// Check if image file is a actual image or fake image
	if(isset($_POST["submitImage"])) {
		if(!empty($_FILES["fileToUpload"]["name"])){
			$myPhoto = new Photoupload($_FILES["fileToUpload"]);
			$myPhoto->readExif();
			//echo $myPhoto->photoDate;
			if(!empty($myPhoto->photoDate)){
				$textToImage = $myPhoto->photoDate;
			} else {
				$textToImage = "Pildistamise aeg teadmata";
			}
			$myPhoto->makeFileName($imageNamePrefix);
			//määrame faili nime
			$targetFile = $target_dir .$myPhoto->fileName;
			
			//kas on pilt
			$uploadOk = $myPhoto->checkForImage();
			if($uploadOk == 1){
			  // kas on sobiv tüüp
			  $uploadOk = $myPhoto->checkForFileType();
			}
			
			if($uploadOk == 1){
			  // kas on sobiv suurus
			  $uploadOk = $myPhoto->checkForFileSize($_FILES["fileToUpload"], 2500000);
			}
			
			if($uploadOk == 1){
			  // kas on juba olemas
			  $uploadOk = $myPhoto->checkIfExists($targetFile);
			}
						
			// kui on tekkinud viga
			if ($uploadOk == 0) {
				$notice = "Vabandame, faili ei laetud üles! Tekkisid vead: ".$myPhoto->errorsForUpload;
			// kui kõik korras, laeme üles
			} else {
				$myPhoto->resizeImage(600, 400);
				$myPhoto->addWatermark($pathToWatermark);
				$myPhoto->addText($textToImage);
				$saveResult = $myPhoto->savePhoto($targetFile);
				//kui salvestus õnnestus, lisame andmebaasi
				if($saveResult == 1){
					$myPhoto->createThumbnail($thumbDir, $thumbSize);
					$notice = "Foto laeti üles! ";
					$notice .= addPhotoData($myPhoto->fileName, $_POST["altText"], $_POST["privacy"]);
				} else {
					$notice .= "Foto lisamisel andmebaasi tekkis viga!";
                }
				
			}
			unset($myPhoto);
		}
	}
	// Lehe päise laadimine
	$pageTitle = "Piltide üleslaadimine";
	require("header.php");
?>

<!DOCTYPE html>
<html>
  <body>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <label>Vali üleslaetav pilt:</label>
    <input type="file" name="fileToUpload" id="fileToUpload"><br><br>
	<label>Pildi kirjeldus (max 256 tähemärki): </label>
	<input type="text" name="altText">
	<br><br>
	<label>Pildi kasutusõigused</label><br>
	<input type="radio" name="privacy" value="1"><label>Avalik</Label><br>
	<input type="radio" name="privacy" value="2"><label>Sisseloginud kasutajatele</Label><br>
	<input type="radio" name="privacy" value="3" checked><label>Privaatne</Label><br><br>
    <input type="submit" value="Lae pilt üles" name="submitImage">
	<?php echo $notice;?>
</form>
<br>
<?php require("footer.php"); ?>
  </body>
</html>