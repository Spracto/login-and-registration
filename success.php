<?php
	session_start();
	echo "howdy {$_SESSION['first_name']}!";
	echo "<a href='process.php'>LOG OFF! </a>"
?>