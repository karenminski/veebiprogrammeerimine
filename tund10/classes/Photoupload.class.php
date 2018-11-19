<?php
	class Photoupload
	{
		private $myTempImage;
		private $imageFileType;
		private $tempName;
		private $myImage;
		public $imageSize;
		public $fileName;
		public $errorsForUpload;
		private $uploadOk;
		public $photoDate;

		public function __construct($tmpPic){
		$this->tempName = $tmpPic["tmp_name"];
		$this->imageFileType = strtolower(pathinfo($tmpPic["name"], PATHINFO_EXTENSION));
		$this->imageSize = $tmpPic["size"];
		$this->ImageFromFile();
		$this->uploadOk = 1;
	}
		
		public function __destruct(){
				imagedestroy($this->myTempImage);
				imagedestroy($this->myImage);
		}
		
		private function imageFromFile(){
			//loome vastavalt failitüübile pildiobjekti
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
				$this->myTempImage = imagecreatefromjpeg($this->tempName);
			}
			if($this->imageFileType == "png"){
				$this->myTempImage = imagecreatefrompng($this->tempName);
			}
			if($this->imageFileType == "gif"){
				$this->myTempImage = imagecreatefromgif($this->tempName);
			}
		}
		
		public function makeFileName($prefix){
		$timeStamp = microtime(1) * 10000;
		$this->fileName = $prefix .$timeStamp ."." .$this->imageFileType;
		}
	
		public function readExif(){
			// kõige ees on "@" märk, et vältida hoiatust
			@$exif = exif_read_data($this->tempName, "ANY_TAG", 0, true);
			//var_dump($exif);
			//echo $exif["DateTimeOriginal"];
			//echo $exif;
			if(!empty($exif["DateTimeOriginal"])){
				$this->photoDate = $exif["DateTimeOriginal"];
			} else {
				$this->photoDate = NULL;
			}
		}
		
		public function checkForImage(){
			$this->errorsForUpload = "";
			// kas on pilt, kontrollin pildi suuruse küsimise kaudu
			$check = getimagesize($this->tempName);
			if($check == false) {
			  $this->errorsForUpload .= "Fail ei ole pilt.";
			  $this->uploadOk = 0;
			}
		return $this->uploadOk;
		}
		
		public function checkForFileSize($size){
			// faili suurus
			if ($this->imageSize > $size) {
			  $this->errorsForUpload .= " Kahjuks on fail liiga suur!";
			  $this->uploadOk = 0;
			}
		return $this->uploadOk;
		}
		
		public function checkForFileType(){
		// kindlad failitüübid
			if($this->imageFileType != "jpg" && $this->imageFileType != "png" && $this->imageFileType != "jpeg"
				&& $this->imageFileType != "gif" ) {
				$this->errorsForUpload ." Kahjuks on lubatud vaid JPG, JPEG, PNG ja GIF failid!";
				$uploadOk = 0;
			}
		return $this->uploadOk;		
		}
		
		public function checkIfExists($target){
			// kas on juba olemas
			if (file_exists($target)) {
			  $this->errorsForUpload .= "Kahjuks on selline pilt juba olemas!";
			  $this->uploadOk = 0;
			}
		return $this->uploadOk;
		}
		
		public function resizeImage($width, $height){
			//vaatame pildi originaalsuuruse
			$imageWidth = imagesx($this->myTempImage);
			$imageHeight = imagesy($this->myTempImage);
			//leian vajaliku suurendusfaktori
			if($imageWidth > $imageHeight){
			$sizeRatio = $imageWidth / $width;
			} else {
			$sizeRatio = $imageHeight / $height;
			}
				
			$newWidth = round($imageWidth / $sizeRatio);
			$newHeight = round($imageHeight / $sizeRatio);
			$this->myImage = $this->changePhotoSize($this->myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight);
		}
	
		private function changePhotoSize($image, $ow, $oh, $w, $h){
			$newImage = imagecreatetruecolor($w, $h);
			//kui on läbipaistvusega png pildid, siis on vaja säilitada läbipaistvusega
			imagesavealpha($newImage, true);
			$transColor = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
			imagefill($newImage, 0, 0, $transColor);
			imagecopyresampled($newImage, $image, 0, 0 , 0, 0, $w, $h, $ow, $oh);
			return $newImage;
		}
		
		public function addWatermark($pathToWatermark){
	  //lisame vesimärgi
			$waterMark = imagecreatefrompng($pathToWatermark);
			$waterMarkWidth = imagesx($waterMark);
			$waterMarkHeight = imagesy($waterMark);
			$waterMarkPosX = imagesx($this->myImage) - $waterMarkWidth - 10;
			$waterMarkPosY = imagesy($this->myImage) - $waterMarkHeight - 10;
			  //kopeerin vesimärgi pikslid pildile
			imagecopy($this->myImage, $waterMark, $waterMarkPosX, $waterMarkPosY, 0, 0, $waterMarkWidth, $waterMarkHeight);
		}
		
		public function addText($textToImage){
			$textColor = imagecolorallocatealpha($this->myImage, 255, 255, 255, 60);
			imagettftext($this->myImage, 20, 0, 10, 30, $textColor, "../vp_picfiles/TIMESBD.TTF", $textToImage);
		}
		
		public function createThumbnail($directory, $size){
			$imageWidth = imagesx($this->myTempImage);
			$imageHeight = imagesy($this->myTempImage);
			if($imageWidth > $imageHeight){
				$cutSize = $imageHeight;
				$cutX = round(($imageWidth - $cutSize) / 2);
				$cutY = 0;
			} else {
				$cutSize = $imageWidth;
				$cutX = 0;
				$cutY = round(($imageHeight - $cutSize) / 2);
			}
			$myThumbnail = imagecreatetruecolor($size, $size);
		
			imagecopyresampled($myThumbnail, $this->myTempImage, 0, 0, $cutX, $cutY, $size, $size, $cutSize, $cutSize);
			$targetFile = $directory .$this->fileName;
			//thumbnail kirjutatakse pildifailiks
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
				imagejpeg($myThumbnail, $targetFile, 90);
			}
			if($this->imageFileType == "png"){
				imagepng($myThumbnail, $targetFile, 6);
			}
			if($this->imageFileType == "gif"){
				imagegif($myThumbnail, $targetFile);
			}
		}
	
		
		public function savePhoto($target_file){
			$notice = null;
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
				if(imagejpeg($this->myImage, $target_file, 95)){
					$notice = 1;
				} else {
					$notice = 0;
				}
			}
			if($this->imageFileType == "png"){
				if(imagepng($this->myImage, $target_file, 6)){
					$notice = 1;
				} else {
					$notice = 0;
				}
			}
			if($this->imageFileType == "gif"){
				if(imagegif($this->myImage, $target_file)){
					$notice = 1;
				} else {
					$notice = 0;
				}
			}
			return $notice;
		}
	} // class lõppeb
?>