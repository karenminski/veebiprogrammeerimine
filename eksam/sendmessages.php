<?php
  require("functions.php");
  if(!isset($_SESSION["userId"])){
	  header("Location: index.php");
	  exit();
  }
  if(isset ($_GET["logout"])){
	  session_destroy(); 
	  header("Location: index.php");
	  exit();
  }
   $notice = null;
   if (isset($_POST["submitMessage"])){
	   if ($_POST["message"] != "Kirjuta sõnum siia..." and !empty($_POST["message"]) and !empty($_POST["sentto"])){
		 $message = test_input($_POST["message"]);
		 $sentto = test_input($_POST["sentto"]);
		 $notice = sendamsg($message, $sentto);     
		} else {
		$notice = "Palun kirjuta sõnum!";
	}
    }
	$pageTitle = "Saada sõnumeid";
	require("header.php");
?>

<!DOCTYPE html>
<html>
<form method ="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <label>Sõnum (max 256 märki):</label>
  <br>
  <textarea name="message" rows="4" cols="64">Kirjuta sõnum siia...</textarea>
  <br>
  <label>Kasutaja kellele saadad sõnumi: </label>
  <input type="text" name="sentto">
  <br>
  <input type="submit" name="submitMessage" value="Salvesta sõnum">
 </form>
 <p><?php echo $notice; ?></p>
<?php require("footer.php"); ?>
  </body>
</html>