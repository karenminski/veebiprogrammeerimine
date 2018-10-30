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
  //Piltide üleslaadimise osa
	$target_dir = "../vppicuploads/";
	$uploadOk = 1;
	// Check if image file is a actual image or fake image
	if(isset($_POST["submitImage"])) {
		if(!empty($_FILES["fileToUpload"]["name"])){
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
			$timeStamp = microtime(1) * 10000;
			$target_file = $target_dir . "vp_" .$timeStamp ."." .$imageFileType;
			//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				echo "Fail on " . $check["mime"] . "pilt";
				//$uploadOk = 1;
			} else {
				echo "Fail ei ole pilt.";
				$uploadOk = 0;
			}
			// Check if file already exists
			if (file_exists($target_file)) {
				echo "Vabandage, selline pilt on juba olemas";
				$uploadOk = 0;
			}
			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 2500000) {
				echo "Vabandage, pilt on liiga suur";
				$uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				echo "Vabandage, ainult JPG, JPEG, PNG ja GIF failid on lubatud";
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo "Kahjuks pilti üles ei laeta";
			// if everything is ok, try to upload file
			} else {
				//Loome vastavalt faili tüübile pildi objekti
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
				$imageHeigth = imagesy($myTempImage);
				echo $imageWidth . "x" .$imageHeigth;
				//Arvutan suuruse suhtarvu
				if($imageWidth > $imageHeigth){
					$sizeRatio > $imageWidth / 600;
					//$sizeRatio > $imageWidth / 600;
				}	else {
					$sizeRatio = $imageHeigth / 600;
				}
					$newWidth = $imageWidth / $sizeRatio;
					$newHeigth = $imageHeigth / $sizeRatio;
					$myImage = resizeImage($myTempImage, $imageWidth, $imageHeigth, $newWidth, $newHeigth);
				//Lähtudes faili tüübist kirjutan pildi faili
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
					if(imagejpeg($myImage, $target_file, 95)){
						echo "Pilt ". basename( $_FILES["fileToUpload"]["name"]). " on edukalt üles laetud";
					} else {
						echo "Vabandage, pildi üleslaadimisel tekkis tehniline viga";
					}
				}
				if($imageFileType == "png"){
					if(imagepng($myImage, $target_file, 6)){
						echo "Pilt ". basename( $_FILES["fileToUpload"]["name"]). " on edukalt üles laetud";
					} else {
						echo "Vabandage, pildi üleslaadimisel tekkis tehniline viga";
					}
				}
				if($imageFileType == "gif"){
					if(imagegif($myImage, $target_file)){
						echo "Pilt ". basename( $_FILES["fileToUpload"]["name"]). " on edukalt üles laetud";
					} else {
						echo "Vabandage, pildi üleslaadimisel tekkis tehniline viga";
					}
				}
				imagedestroy($myTempImage);
				imagedestroy($myImage);

				/* if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					echo "Pilt ". basename( $_FILES["fileToUpload"]["name"]). " on edukalt üles laetud";
				} else {
					echo "Vabandage, pildi üleslaadimisel tekkis tehniline viga";
				} */
			}
		}
	}// Kontroll kas vajutati nuppu
	function resizeImage($image, $ow, $oh, $w, $h){ //parameetrid ülevalt
	$newImage = imagecreatetruecolor($w, $h);
	imagecopyresampled($newImage, $image, 0, 0, 0, 0, $w, $h, $ow, $oh);	
	return $newImage;
	}
	// Lehe päise laadimine
	$pageTitle = "Piltide üleslaadimine";
	require("header.php");
?>

<!DOCTYPE html>
<html>
  <body>
	<p>Olete sisse loginud nimega: 
	<?php
	echo $_SESSION["userFirstName"] ." " .$_SESSION["userLastName"];
	?>
	</p>
	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<ul>
	<li><a href="main.php">Tagasi</a> pealehele!</li>
    <li><a href="?logout=1">Logi välja</a></li>
	</ul>
	<hr>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <label>Vali üleslaetav pilt:</label>
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Lae pilt üles" name="submitImage">
</form>


  </body>
</html>