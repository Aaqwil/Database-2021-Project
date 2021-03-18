<?php
	session_start();

	function __autoload($class_name)
	{
		include '../' . $class_name.'.php';
	}

	$user = new User;
	
	if($_POST['address_id'])
		$user->database->make_address_default($_SESSION['user'], $_POST['address_id']);
	else
		echo "Proszę podać identyfikator adresu!<br>";
	
	echo "<p><a href='../userpage.php'>Powrót do strony klienta.</a></p>";
	echo "<p><a href='../index.php'>Powrót do strony głównej.</a></p>";
?>