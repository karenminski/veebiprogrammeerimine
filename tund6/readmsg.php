<?php
 require("functions.php");
 $notice = readallmessages();
?>
<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
  <title>Anonüümsed sõnumid</title> 
  </head>
  <body>
  <h1>Sõnumid</h1>
  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud leht ja see ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
  <p>Lisatud tekst on tehtud kodusetöö raames ning on mõeldud vaid testimiseks. </p>
  <hr>

 <hr>
<?php echo $notice; ?>
 
</body> 
</html>