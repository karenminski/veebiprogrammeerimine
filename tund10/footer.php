
    <footer>
	<style>
	hr {
	border-top: 2px dotted #FFFFFF;
	border-bottom: none;
}
	</style>
	<hr>
    <p>See leht on valminud <a href="https://www.tlu.ee" target="_blank">TLÜ </a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<p>Olete sisse loginud nimega: 
	<?php
	echo $_SESSION["userFirstName"] ." " .$_SESSION["userLastName"];
	?>
	</p>
	<ul>
	<li><a href="?logout=1">Logi välja</a>!</li>
	<li><a href="main.php">Tagasi</a> pealehele!</li>
    </ul>
    </footer>
  </body>