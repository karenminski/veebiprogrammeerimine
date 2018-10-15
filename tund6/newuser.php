<?php
  //lisan teise php faili
  require("functions.php");
  $notice = "";
  $firstName = "";
  $lastName = "";
  $birthMonth = null;
  $birthYear = null;
  $birthDay = null;
  $birthDate = "";
  $gender = null;
  $email = "";
  
  $monthNamesET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni","juuli", "august", "september", "oktoober", "november", "detsember"];
  
  $firstNameError = "";
  $lastNameError = "";
  $birthMonthError = "";
  $birthYearError = "";
  $birthDayError = "";
  $birthDateError = "";
  $genderError = "";
  $emailError = "";
  $passwordError = "";
  $passwordError2 = "";
  
  //püüan POST andmed kinni
  //var_dump($_POST);
  if(isset($_POST["submitUserData"])){ //  kas on nuppu vajutatud
	  
  if (isset($_POST["firstname"]) and !empty($_POST["firstname"])){ //! tähendab et ei ole
	$firstName = test_input($_POST["firstname"]);
  } else {
	  $firstNameError = "Palun sisesta oma eesnimi!";
	  
  }
  if (isset($_POST["lastname"])and !empty($_POST["lastname"])){
	$lastName = test_input($_POST["lastname"]);
  } else {
	  $lastNameError = "Palun sisesta oma perekonnanimi!";
	  
  } // soo kontroll
  if(isset($_POST["gender"]) and !empty($_POST["gender"])){
	  $gender = intval($_POST["gender"]);
	  
  } else{
	  $genderError = "Palun märgi oma sugu!";
  }
  // kuupäeva kontroll
  if(!empty($_POST["birthDay"]) and !empty($_POST["birthMonth"]) and !empty($_POST["birthYear"])){ 
		$birthDay = intval($_POST["birthDay"]);
		$birthMonth = intval($_POST["birthMonth"]);
		$birthYear = intval($_POST["birthYear"]);
		if(checkdate(intval($_POST["birthMonth"]), intval($_POST["birthDay"]), intval($_POST["birthYear"]))){ // Kas kuupäev on võimalik
			$birthDate = date_create($_POST["birthMonth"] . "/" . $_POST["birthDay"] . "/" .$_POST["birthYear"]); 
			
			// Vormindada andmebaasi
			$birthDate = date_format($birthDate, "Y-m-d");
		} else {
			$birthDateError = "Kahjuks on sisestatud võimatu kuupäev!";
		}
	  } else {
		$birthDateError = "Palun sisesta oma sünnikuupäev!";
	  }

 if (isset($_POST["email"]) and !empty($_POST["email"])){
		$email = test_input($_POST["email"]);
	  } else {
		$emailError = "Palun sisesta oma e-posti aadress!";
	  }
	  
  if (isset($_POST["password"]) and !empty($_POST["password"])){
		$password = test_input($_POST["password"]);
		if (strlen($password) < 8){
			$passwordError = "Palun sisesta piisavalt pikk parool!";
		}
	  } else {
		$passwordError = "Palun sisesta oma parool!";
	  }
	  
	  if (isset($_POST["passwordconfirm"]) and !empty($_POST["passwordconfirm"])){
		$password = test_input($_POST["password"]);
		if ($_POST["passwordconfirm"] != $_POST["password"]){
			$passwordError2 = "Palun sisesta samad paroolid!";
		}
	  } else {
		$passwordError2 = "Palun kinnita ka oma parooli!";
	  }
	  
  //parooli pikkuse kontroll
  
  if(empty($firstNameError) and empty($lastNameError) and empty($birthMonthError) and empty($birthYearError) and empty($birthDayError) and empty($birthDateError) and empty($genderError) and empty($emailError) and empty($passwordError)and empty($passwordError2)){
  $notice = signup($firstName, $lastName, $birthDate, $gender, $_POST["email"], $_POST["password"]);
  }
  } //kas on nuppu vajutatud lõppeb
  
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Uue kasutaja loomine</title>
</head>
<body>
  <h1>Loo kasutaja</h1>
  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
  <hr>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>Eesnimi: </label><br>
    <input type="text" name="firstname" value ="<?php echo $firstName; ?>"><span><?php echo $firstNameError; ?></span><br>
    <label>Perekonnanimi: </label><br>
    <input type="text" name="lastname"value ="<?php echo $lastName; ?>"><span><?php echo $lastNameError; ?></span><br>
	<label>Sünnipäev: </label>
	  <?php
	    echo '<select name="birthDay">' ."\n";
		echo '<option value="" selected disabled>Päev</option>' . "\n";
		for ($i = 1; $i < 32; $i ++){
			echo '<option value="' .$i .'"';
			if ($i == $birthDay){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <label>Sünnikuu: </label>
	  <?php
	    echo '<select name="birthMonth">' ."\n";
		echo '<option value="" selected disabled>Kuu</option>' . "\n";
		for ($i = 1; $i < 13; $i ++){
			echo '<option value="' .$i .'"';
			if ($i == $birthMonth){
				echo " selected ";
			}
			echo ">" .$monthNamesET[$i - 1] ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <label>Sünniaasta: </label>
	  <!--<input name="birthYear" type="number" min="1914" max="2003" value="1998">-->
	  <?php
	    echo '<select name="birthYear">' ."\n";
		echo '<option value="" selected disabled>Aasta</option>' ."\n";
		for ($i = date("Y") - 15; $i >= date("Y") - 100; $i --){
			echo '<option value="' .$i .'"';
			if ($i == $birthYear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n"; 
	  ?><span><?php echo $birthDateError; ?></span><br> 
	  <label>Sugu:</label><br>
	  <input name="gender" type="radio" value="2" <?php if($gender == 2){ echo "checked";}?>><label>Naine</label> <!-- radioga vormi sees saad valida punktida ühe valiku-->
	  <br>
	  <input name="gender" type="radio" value="1" <?php if($gender == 1){ echo "checked";}?>><label>Mees</label>
	  <br>
	  <span><?php echo $genderError; ?></span>
	  <br>
	  <label>E-postiaadress (kasutajatunnus): </label><br>
      <input type="email" name="email" value="<?php echo $email; ?>"><span><?php echo $emailError; ?></span><br>
      <label>Salasõna: (min 8 märki)</label><br>
      <input type="password" name="password" value=""><span><?php echo $passwordError; ?></span><br> <!-- ei tee echo password sest parooli ei jäeta meelde -->
	  <label>Salasõna uuesti: </label><br>
	  <input type="password" name="passwordconfirm" value=""><span><?php echo $passwordError2; ?></span><br>
	  <input type="submit" name="submitUserData" value ="Loo kasutaja">
  </form>
  <hr> <!-- Joon --> 
  <p><?php echo $notice; ?></p>
  
</body>
</html>