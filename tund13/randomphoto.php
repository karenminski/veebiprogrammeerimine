<?php
require("../../../config.php");
$database = "if18_karen_mi_1";
$privacy = 2;
$limit = 10;
$html = NULL;
$photoList = []; //massiiv
$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
$stmt = $mysqli->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy <= ? AND deleted IS NULL ORDER By id DESC LIMIT ?");
$stmt->bind_param("ii", $privacy, $limit);
$stmt->bind_result($filenameFromDb, $alttextFromDb);
$stmt->execute();
while($stmt->fetch()){
	// <img src="fail" alt="tekst">
	//$html ='<img src"' .$picDir .$filenameFromDb .'" alt="' .$alttextFromDb .'">' ."\n";
	$myPhoto = new StdClass();
	$myPhoto->filename = $filenameFromDb;
	$myPhoto->alttext = $alttextFromDb;
	array_push($photoList, $myPhoto);
	}
	$photoCount = count($photoList);
	if($photoCount > 0){
		$randPic = mt_rand(0, $photoCount - 1);
		$html ='<img src="' .$picDir .$photoList[$randPic]->filename .'" alt="' .$photoList[$randPic]->alttext .'">' ."\n";
	}
	//massiivi läbimise tsükkel
	foreach($photoList as $pic){
		$html .= "<p>" .$pic->filename ." | " .$pic->alttext ."</p> \n";
	}
$stmt->close();
$mysqli->close();
echo $html
?>