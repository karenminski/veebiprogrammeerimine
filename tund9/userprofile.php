<?php
    require("functions.php");		
  $description2 = "Pole tutvustust lisanud!";
  $bgcolor2 = "#FFFFFF";
  $txtcolor2 = "#000000";
  $profilePic = "../vp_picfiles/vp_user_generic.png";
  $profilePicDirectory = "../vpuser_picfiles/";
  $addedPhotoId = null;
  $notice = "";
  
  $target_file = "";
  $uploadOk = 1;
  $imageFileType = "";
  
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
	//$notice = createuserprofile($_POST["description"], $_POST["bgcolor"], $_POST["txtcolor"]);
	
	//kohe uued väärtused näitamiseks kasutusele
	if(!empty($_POST["description"])){
	  $description2 = $_POST["description"];
	}
	$bgcolor2 = $_POST["bgcolor"];
	$txtcolor2 = $_POST["txtcolor"];
	//profiilipildi laadimine
	if(!empty($_FILES["fileToUpload"]["name"])){
			$imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
			$timeStamp = microtime(1) * 10000;
			$target_file_name = "vpuserpic_" .$timeStamp ."." .$imageFileType;
			$target_file = $profilePicDirectory .$target_file_name;
						
			// kas on pilt, kontrollin pildi suuruse küsimise kaudu
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				//echo "Fail on pilt - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
			echo "Fail ei ole pilt.";
				$uploadOk = 0;
			}
			
			// faili suurus
			if ($_FILES["fileToUpload"]["size"] > 2500000) {
				echo "Kahjuks on fail liiga suur!";
				$uploadOk = 0;
			}
			
			// kindlad failitüübid
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				echo "Kahjuks on lubatud vaid JPG, JPEG, PNG ja GIF failid!";
				$uploadOk = 0;
			}
			
			// kui on tekkinud viga
			if ($uploadOk == 0) {
				echo "Vabandame, faili ei laetud üles!";
			// kui kõik korras, laeme üles
			} else {
				//sõltuvalt failitüübist, loome pildiobjekti
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
					$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "png"){
					$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "gif"){
					$myTempImage = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
				}
				
				//vaatame pildi originaalsuuruse
				$imageWidth = imagesx($myTempImage);
				$imageHeight = imagesy($myTempImage);
				//leian vajaliku suurendusfaktori, siin arvestan, et lõikan ruuduks!!!
				if($imageWidth > $imageHeight){
					$sizeRatio = $imageHeight / 300;//ruuduks lõikamisel jagan vastupidi
				} else {
					$sizeRatio = $imageWidth / 300;
				}
				
				$newWidth = round($imageWidth / $sizeRatio);
				$newHeight = $newWidth;
				$myImage = resizeImagetoSquare($myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight);
				
				//lisame vesimärgi
				$waterMark = imagecreatefrompng("../vp_picfiles/vp_logo_w100_overlay.png");
				$waterMarkWidth = imagesx($waterMark);
				$waterMarkHeight = imagesy($waterMark);
				$waterMarkPosX = $newWidth - $waterMarkWidth - 10;
				$waterMarkPosY = $newHeight - $waterMarkHeight - 10;
				//kopeerin vesimärgi pikslid pildile
				imagecopy($myImage, $waterMark, $waterMarkPosX, $waterMarkPosY, 0, 0, $waterMarkWidth, $waterMarkHeight);
				
				//muudetud suurusega pilt kirjutatakse pildifailiks
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
				  if(imagejpeg($myImage, $target_file, 90)){
                    //echo "Korras!";
					//ja kohe see uus profiilipilt
		            $profilePic = $target_file;
					//kui pilt salvestati, siis lisame andmebaasi
					$addedPhotoId = addUserProfilePic($target_file_name);
					//echo "Lisatud pildi ID: " .$addedPhotoId;
				  } else {
					//echo "Pahasti!";
				  }
				}
				
				imagedestroy($myTempImage);
				imagedestroy($myImage);
				imagedestroy($waterMark);
				
			}
		}//pildi laadimine lõppes
		//profiili salvestamine koos pildiga
		$notice = createuserprofile($_POST["description"], $_POST["bgcolor"], $_POST["txtcolor"], $addedPhotoId);
		
	
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
	if($myprofile->picture != ""){
	  $profilePic = $profilePicDirectory .$myprofile->picture;
	}
  }
  
  function resizeImageToSquare($image, $ow, $oh, $w, $h){
	$newImage = imagecreatetruecolor($w, $h);
	if($ow > $oh){
		$cropX = round(($ow - $oh) / 2);
		$cropY = 0;
		$cropSize = $oh;
	} else {
		$cropX = 0;
		$cropY = round(($oh - $ow) / 2);
		$cropSize = $ow;
	}
    //imagecopyresampled($newImage, $image, 0, 0 , 0, 0, $w, $h, $ow, $oh);
	imagecopyresampled($newImage, $image, 0, 0, $cropX, $cropY, $w, $h, $cropSize, $cropSize); 
	return $newImage;
  }
  
  /* 
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
  $userProfilePic = addUserProfilePic($fileName);
  
  	function resizeImage($image, $ow, $oh, $w, $h){
		$newImage = imagecreatetruecolor($w, $h);
		imagecopyresampled($newImage, $image, 0, 0, 0, 0, $w, $h, $ow, $oh);
		return $newImage;
	} */
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
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
	  <div style="float: left">
	  <img src="<?php
	    echo $profilePic;
	  ?>" alt="<?php
	    echo $_SESSION["userFirstName"] ." " .$_SESSION["userLastName"];
	  ?>">
  
  <h1><?php echo $_SESSION["userFirstName"] ." " .$_SESSION["userLastName"]; ?></h1>
  <p>Kirjelda ennast</p>
  <form method ="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <textarea rows="10" cols="80" name="description"><?php echo $description2; ?></textarea>
  <br>
  <br>
  <label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $bgcolor2; ?>">
  <br>
  <br>
  <label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $txtcolor2; ?>">
  <br>
  <br>
  <label>Vali üleslaetav profiilipilt: </label>
  <input type="file" name="fileToUpload" id="fileToUpload">
  <br>
  <br>
  <input type="submit" name="submitProfile" value ="Salvesta profiil">
  <?php echo $notice; ?>
  </form>
<?php require("footer.php"); ?>
</body>
</html>