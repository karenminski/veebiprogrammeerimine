<?php
 //lisan teise php faili
 require("functions.php");
 $firstName = "Tundmatu";
 $lastName = "Kodanik";
 $fullName = "";
 $monthToday = date("m");
 $monthNamesET = ["jaanuar","veebruar","märts","aprill","mai","juuni","juuli","august","september","oktoober","november","detsember"];
//Püüan POST andmed kinni
 //var_dump($_POST);
 if (isset($_POST["firstname"])){
 $firstName = test_input($_POST["firstname"]);
 }
  if (isset($_POST["lastname"])){
 $lastName = test_input($_POST["lastname"]);
 }
 
 // [] on massiiv
 function stupidfunction(){
  $GLOBALS["fullName"] = $GLOBALS["firstName"] ." " .$GLOBALS["lastName"];	 
 }
 
 stupidfunction();
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
  <hr>
  
<form method ="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <label> Eesnimi :</label>
  <input type ="text" name="firstname">
  <label> Perekonnanimi :</label>
  <input type ="text" name="lastname">
  <label>Sünniaasta:</label>
  <input type="number" min="1914" max="2000" value="1998" name="birthyear">
  <select name="birthmonth">
  <?php

	  for ($i = 1; $i < 13; $i++) {
	  if($i == $monthToday){
      echo '<option value="' . $i . '" selected>' . $monthNamesET[$i - 1] . '</option>';
}     
      else {
      echo '<option value="' . $i . '">' . $monthNamesET[$i - 1] . '</option>';
}
	  }

?>
 </select>
  <input type="submit" name="submitUserData" value="Saada andmed">
</form>
<hr>
<?php
if (isset($_POST["birthyear"])) {
	echo "<p>" .$fullName ."</p>";
	echo "<p>Olete elanud järgnevatel aastatel:</p> \n";
	echo "<ul> \n";
	  for ($i = $_POST["birthyear"]; $i <= date("Y"); $i++) {
		  echo"<li>" .$i ."</li> \n";
	  }
	echo "</ul> \n";	
}

?>
  
  </body>
  
</html>