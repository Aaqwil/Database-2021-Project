<?php
	session_start();

	function __autoload($class_name)
	{
		include '../' . $class_name.'.php';
	}

	$user = new User;
	
	if($_POST['ordered_game_id'] and $_POST['review'])
		$user->database->insert_new_review($_POST['ordered_game_id'], $_SESSION['user'], $_POST['review']);
	else
		echo "Proszę podać wszystkie prawidłowe dane do wprowadzenia!<br>";
	
	echo "<p><a href='../userpage.php'>Powrót do strony klienta.</a></p>";
?>