<?php
require("functions.php");
$notice = "";
$firstName = "";
$lastName = "";
$email = "";

$firstNameError = "";
$lastNameError = "";
$emailError = "";
$passwordError = "";
$passwordError2 = "";

if(isset($_POST["submitUserData"])){ 
	if (isset($_POST["firstname"]) and !empty($_POST["firstname"])){ 
		$firstName = test_input($_POST["firstname"]);
	} else {
		$firstNameError = "Palun sisesta oma eesnimi!";

	}
	if (isset($_POST["lastname"])and !empty($_POST["lastname"])){
		$lastName = test_input($_POST["lastname"]);
	} else {
		$lastNameError = "Palun sisesta oma perekonnanimi!";

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

	if(empty($firstNameError) and empty($lastNameError) and empty($emailError) and empty($passwordError)and empty($passwordError2)){
		$notice = signup($firstName, $lastName, $_POST["email"], $_POST["password"]);
	}
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Uue kasutaja loomine</title>
</head>
<body>
  <h1>Loo kasutaja</h1>
  <hr>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>Eesnimi: </label><br>
    <input type="text" name="firstname" value ="<?php echo $firstName; ?>"><span><?php echo $firstNameError; ?></span><br>
    <label>Perekonnanimi: </label><br>
    <input type="text" name="lastname"value ="<?php echo $lastName; ?>"><span><?php echo $lastNameError; ?></span><br>
	<label>E-postiaadress (kasutajatunnus): </label><br>
    <input type="email" name="email" value="<?php echo $email; ?>"><span><?php echo $emailError; ?></span><br>
    <label>Salasõna: (min 8 märki)</label><br>
    <input type="password" name="password" value=""><span><?php echo $passwordError; ?></span><br> <!-- ei tee echo password sest parooli ei jäeta meelde -->
	<label>Salasõna uuesti: </label><br>
	<input type="password" name="passwordconfirm" value=""><span><?php echo $passwordError2; ?></span><br>
	<input type="submit" name="submitUserData" value ="Loo kasutaja">
  </form>
  <hr>
  <p><?php echo $notice; ?></p>
  <p><a href="index.php">Tagasi</a> avalehele!</p>
  
</body>
</html>