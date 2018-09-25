<?php
 require("functions.php");
   $notice = null;
   
   if (isset($_POST["submitMessage"])){
	   if ($_POST["message"] != "Kirjuta sõnum siia..." and !empty($_POST["message"])){
		 $message = test_input($_POST["message"]);
		 $notice = saveamsg($message);     
	} else {
		$notice = "Palun kirjuta sõnum!";
	}
   }
?>
<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
  <title>Anonüümse sõnumi lisamine</title> 
  </head>
  <body>
  <h1>Sõnumi lisamine</h1>
  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud leht ja see ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
  <p>Lisatud tekst on tehtud kodusetöö raames ning on mõeldud vaid testimiseks. </p>
  <hr>
<form method ="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <label>Sõnum (max 256 märki):</label>
  <br>
  <textarea name="message" rows="4" cols="64">Kirjuta sõnum siia...</textarea>
  <br>
  
  <input type="submit" name="submitMessage" value="Salvesta sõnum">
 </form>
 <hr>
 <p><?php echo $notice; ?></p>
 
</body> 
</html>