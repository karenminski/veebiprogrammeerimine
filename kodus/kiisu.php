<?php
 //lisan teise php faili
 require("../tund4/functions.php");
 $notice = null;
//Püüan POST andmed kinni
 //var_dump($_POST);
 // [] on massiiv
 if (isset($_POST["submitCatData"])){
	if (!empty($_POST["catname"]) and !empty($_POST["catcolor"])){
	  $catname = test_input($_POST["catname"]);
	  $catcolor = test_input($_POST["catcolor"]);
	  $cattaillength = test_input($_POST["cattaillength"]);
      $notice = addcat($catname, $catcolor, $cattaillength);
	}else {
	   $notice = "Palun kirjuta andmed!";
	}
   }

?>

<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
  <title>Kiisu tabel</title>
  </head>
  <body>
  <h1>Kiisu tabel</h1>
  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud leht ja see ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
  <hr>
  <form method ="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <label> Kiisu nimi :</label>
  <input type ="text" name="catname">
  <label> Kiisu värvus :</label>
  <input type ="text" name="catcolor">
  <label> Kiisu saba pikkus :</label>
  <input type ="number" name="cattaillength">
  <input type="submit" name="submitCatData" value="Saada andmed">
</form>
<hr>
<?php echo "<ol>" .$notice ."</ol>"; ?>


  </body>
  
</html>