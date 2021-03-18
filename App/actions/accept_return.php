<?php
	session_start();

	function __autoload($class_name)
	{
		include '../' . $class_name.'.php';
	}

	$manager = new DatabaseManager;
	
	if($_POST['return_id'])
		$manager->accept_return($_POST['return_id']);
	else
		echo "Proszę podać numer zwrotu!<br>";
	
	echo "<p><a href='../superuser.php'>Powrót do strony pracownika sklepu.</a></p>";
?>