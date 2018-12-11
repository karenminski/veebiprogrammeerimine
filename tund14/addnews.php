<?php
  require("functions.php");
  
   if(!isset($_SESSION["userId"])){
	  header("Location: index2.php");
	  exit();
  }
  //Välja logimine
  if(isset ($_GET["logout"])){
	  session_destroy(); 
	  header("Location: index2.php");
	  exit();
  }
	$notice = "";
	$expiredate = date("Y-m-d");
	$pageTitle = "Uudise postitamine";
	require("header.php");
	
if (isset($_POST["newsBtn"])){
	   if (!empty($_POST["newsTitle"]) and !empty($_POST["newsEditor"]) and !empty($_POST["expiredate"]))	{
		 $title = test_input($_POST["newsTitle"]);
		 $content = $_POST["newsEditor"];
		 $expire = $_POST["expiredate"];
		 $notice = addnews($title, $content, $expire);     
	} else {
		$notice = "Palun kirjuta uudis!";
	}
   }
  ?>
  
 <!DOCTYPE html>
<html>
<!-- Lisame tekstiredaktory TinyMCE -->
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>
	tinymce.init({
		selector:'textarea#newsEditor',
		plugins: "link",
		menubar: 'edit',
	});
</script>
<body>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label>Uudise pealkiri:</label><br><input type="text" name="newsTitle" id="newsTitle" style="width: 100%;" value=""><br>
	<label>Uudise sisu:</label><br>
	<textarea name="newsEditor" id="newsEditor"></textarea>
	<br>
	<label>Uudis nähtav kuni (kaasaarvatud)</label>
	<input type="date" name="expiredate" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="<?php echo $expiredate; ?>">
	<input name="newsBtn" id="newsBtn" type="submit" value="Salvesta uudis!">
</form>
<br>
<?php require("footer.php"); ?>
  </body>
</html>