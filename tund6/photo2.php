<?php
 //echo "See on minu esimene php!";
 $firstName = "Karen";
 $lastName = "Minski";
 //loeme kataloogi sisu;
 $dirToRead = "../../pics/";
 $allFiles = scandir($dirToRead);
 $picNUM = mt_rand(2,6);
 $picFILE = $allFiles[$picNUM];
 
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
  <p>Lisatud tekst on tehtud kodusetöö raames ning on mõeldud vaid testimiseks. </p>

  <img src="<?php echo $dirToRead . $picFILE; ?>" alt="koer">
  </body>
  
</html>