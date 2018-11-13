<?php
  require("functions.php");
  $teade = "";
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
  
  require("classes/Photoupload.class.php");
  /* require("classes/Test.class.php");
  $myNumber = new Test(7);
  echo "Avalik arv on: " .$myNumber->publicNumber;
  //echo "Salajane arv on: " .$myNumber->secretNumber;
  $myNumber->tellThings();
  $mySNumber = new Test(9);
  echo " Teine avalik arv on: " .$mySNumber->publicNumber;
  unset($myNumber); */
  
  //Piltide üleslaadimise osa
	$target_dir = "../vppicuploads/";
	
	$uploadOk = 1;
	
	// Check if image file is a actual image or fake image
	if(isset($_POST["submitImage"])) {
		if(!empty($_FILES["fileToUpload"]["name"])){
			
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
			
			$timeStamp = microtime(1) * 10000;
			
			$target_file_name = "vp_" .$timeStamp ."." .$imageFileType;
			
			$target_file = $target_dir .$target_file_name;
			//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				$notice = "Fail on " . $check["mime"] . " pilt.";
				//$uploadOk = 1;
			} else {
				$notice = "Fail ei ole pilt!";
				$uploadOk = 0;
			}
			
			// Check if file already exists
			if (file_exists($target_file)) {
				$notice = "Vabandage, selline pilt on juba olemas!";
				$uploadOk = 0;
			}
			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 2500000) {
				$notice = "Vabandust, pilt on liiga suur!";
				$uploadOk = 0;
			}
			
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				$notice = "Vabandage, ainult JPG, JPEG, PNG ja GIF failid on lubatud!";
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				$notice = "Kahjuks faili üles ei laeta!";
			// if everything is ok, try to upload file
			} else {
				$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
				$myPhoto->changePhotoSize(600, 400);
				$myPhoto->addWatermark();
				$myPhoto->addText();
				$myPhoto->saveFile($target_file);
				$saveSuccess = $myPhoto->saveFile($target_file);
				unset($myPhoto);
				
			}	if($savesuccess == 1){
				addPhotoData($target_file_name, $_POST["altText"], $_POST["privacy"]);
			} else {
				echo "Vabandage, tekkis tehniline viga";
			}
		}
	}//kontroll, kas vajutati nuppu
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