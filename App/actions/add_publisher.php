<?php
	session_start();

	function __autoload($class_name)
	{
		include '../' . $class_name.'.php';
	}

	$manager = new DatabaseManager;
	
	if($_POST['publisher_name'] and $_POST['pubilsher_mail'] and $_POST['phone'])
		$manager->insert_new_publisher($_POST['publisher_name'], $_POST['pubilsher_mail'], $_POST['phone']);
	else
		echo "Proszę podać wszystkie prawidłowe dane do wprowadzenia!<br>";
	
	echo "<p><a href='../superuser.php'>Powrót do strony pracownika sklepu.</a></p>";
?>