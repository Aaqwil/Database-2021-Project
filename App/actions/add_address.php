<?php
	session_start();

	function __autoload($class_name)
	{
		include '../' . $class_name.'.php';
	}

	$manager = new DatabaseManager;
	if($_POST['city'] and $_POST['street'] and $_POST['house'])
		$manager->insert_new_address($_POST['city'], $_POST['street'], $_POST['house'], $_POST['flat'], $_SESSION['user']);
	else
		echo "Pola miasto, ulica i numer domu muszą być wypełnione!<br>";
	
	echo "<p><a href='../userpage.php'>Powrót do strony pracownika sklepu.</a></p>";
?>