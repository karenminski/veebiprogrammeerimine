<?php
 //echo "See on minu esimene php!";
 $firstName = "Karen";
 $lastName = "Minski";
 $dateToday = date("d");
 $yearToday = date("Y");
 $weekdayToday = date("N");
 $monthToday = date("m");
 $weekdayNamesET = ["esmaspäev","teisipäev","kolmapäev","neljapäev","reede","laupäev","pühapäev"];
 $monthNamesET = ["jaanuar","veebruar","märts","aprill","mai","juuni","juuli","august","september","oktoober","november","detsember"];
 //echo $monthToday;
 //echo $monthNamesET[1];
 //var_dump($monthNamesET);
 //echo $weekdayNamesET;
 //var_dump($weekdayNamesET);
 //echo $weekdayNamesET[1];
 //echo $weekdayToday;
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
	 //juhusliku pildi valimine
	 $picURL = " http://www.cs.tlu.ee/~rinde/media/fotod/TLU_600x400/tlu_";
	 $picEXT = ".jpg";
	 $picNUM = mt_rand(2,43);
	 //echo $picNUM;
	 $picFILE = $picURL .$picNUM .$picEXT;
 
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
  <p><a href="page.php">Page.php</a>, <a href="photo.php">photo.php</a></p>
  
  <?php
  // echo "<p> Tänane kuupäev on: " .$dateToday .".</p> \n";
  echo "<p>Täna on " .$weekdayNamesET[$weekdayToday - 1] .", " .$dateToday ." " .$monthNamesET[$monthToday - 1] ." " .$yearToday .". </p> \n";
  echo "<p> Lehe avamise hetkel oli kell " .date("H:i:s") .", käes oli " .$partOfDay .".</p> \n";
  ?>
  <img src="<?php echo $picFILE; ?>" alt="TLÜ">
  <img src="http://cdn.akc.org/content/hero/puppy-boundaries_header.jpg" alt="kutsikas">
  <p>Minu sõber teeb ka <a href="../../../~kellrei/veebiprogrammeerimine/Tund3" target="_blank">veebi</a></p>
  </body>
  
</html>