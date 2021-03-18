<?php
	session_start();

	function __autoload($class_name)
	{
		include '../' . $class_name.'.php';
	}

	$manager = new DatabaseManager;
	
	if($_POST['order_id'])
		$manager->realize_order($_POST['order_id']);
	else
		echo "Proszę podać numer zamówienia!<br>";
	
	echo "<p><a href='../superuser.php'>Powrót do strony pracownika sklepu.</a></p>";
?>