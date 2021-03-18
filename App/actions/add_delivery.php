<?php
	session_start();

	function __autoload($class_name)
	{
		include '../' . $class_name.'.php';
	}

	$manager = new DatabaseManager;
	if($_POST['type'] and $_POST['price'] and $_POST['time'])
		$manager->insert_new_delivery($_POST['type'], $_POST['price'], $_POST['time']);
	else
		echo "Proszę podać wszystkie prawidłowe dane do wprowadzenia!<br>";
	
	echo "<p><a href='../superuser.php'>Powrót do strony pracownika sklepu.</a></p>";
?>