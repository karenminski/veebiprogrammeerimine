<?php
 //echo "See on minu esimene php!"; //rumal teade
 $firstName = "Karen";
 $lastName = "Minski";
 $dateToday = date("d/m/y");
 $hourNow = date("G");
 $partOfDay = "";
 if ($hourNow < 8) {
	 $partOfDay = "varajane hommik";
 }
  if ($hourNow >= 8 and $hourNow < 16) {
	 $partOfDay = "koolipäev";
 }
  if ($hourNow > 16) {
	 $partOfDay = "loodetavasti vaba aeg";
 }
?>

<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
  <title>
  <?php
  echo $firstName;
  echo " ";
  echo $lastName;
  ?>
  , õppetöö</title>
  </head>
  <body>
  <h1>
  <?php
  echo $firstName ." " .$lastName; 
  ?>
  </h1>
  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud leht ja see ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
  <p>Lisatud tekst on tehtud kodusetöö raames ning on mõeldud vaid testimiseks.</p>
  <?php
  echo "<p> Tänane kuupäev on: " .$dateToday .".</p> \n";
  echo "<p> Lehe avamise hetkel oli kell " .date("H:i:s") .", käes oli " .$partOfDay .".</p> \n";
  ?>
  <img src="http://cdn.akc.org/content/hero/puppy-boundaries_header.jpg" alt="kutsikas">
  <p>Minu sõber teeb ka <a href="../../~kellrei" target="_blank">veebi</a></p>
  </body>
  
</html>