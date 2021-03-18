<?php

	function __autoload($class_name)
	{
		include '../' . $class_name.'.php';
	}

	$user = new User;
	$user->make_order();
	echo "Zamowienie złożone!<br>";
	echo "<p><a href='../index.php'>Powrót do strony głównej sklepu.</a></p>";

?>