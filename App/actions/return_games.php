<?php

	function __autoload($class_name)
	{
		include '../' . $class_name.'.php';
	}

	$user = new User;
	$user->make_return();
	echo "Zwrot dokonany!<br>";
	echo "<p><a href='../index.php'>Powrót do strony głównej sklepu.</a></p>";

?>