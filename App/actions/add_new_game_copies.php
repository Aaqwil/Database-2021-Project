<?php
	session_start();

	function __autoload($class_name)
	{
		include '../' . $class_name.'.php';
	}

	$manager = new DatabaseManager;
	
	if($_POST['game_id'] and $_POST['additional_copies'])
		$manager->add_more_game_copies($_POST['game_id'], $_POST['additional_copies']);
	else
		echo "Proszę podać numer zwrotu!<br>";
	
	echo "<p><a href='../superuser.php'>Powrót do strony pracownika sklepu.</a></p>";
?>