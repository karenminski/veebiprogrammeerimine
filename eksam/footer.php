<footer>
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