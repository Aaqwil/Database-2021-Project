<?php
	session_start();

	function __autoload($class_name)
	{
		include '../' . $class_name.'.php';
	}

	$manager = new DatabaseManager;
	
	if($_POST['title'] and $_POST['game_price'] and $_POST['genre'] and $_POST['publisher'] and $_POST['amount'] and $_POST['game_type'])
	{
		if($_POST['game_type'] == 'kod' or $_POST['game_type'] == 'kopia fizyczna')
		{
			$publisher_id = $manager->get_publisher_id($_POST['publisher']);
			
			if($publisher_id)
				$manager->insert_new_game($_POST['title'], $_POST['genre'], $_POST['game_price'], $_POST['game_type'], $_POST['amount'], $publisher_id);
			else
			{
				echo "Podany wydawca nie istnieje w bazie!";
				echo "<p><a href='../superuser.php'>Powrót do strony pracownika sklepu.</a></p>";
				return;		
			}

		}
		else
		{
			echo "Typem musi być kod lub kopia fizyczna!";
			echo "<p><a href='../superuser.php'>Powrót do strony pracownika sklepu.</a></p>";
			return;
		}
	}
	else
		echo "Proszę podać wszystkie prawidłowe dane do wprowadzenia!<br>";
	
	echo "<p><a href='../superuser.php'>Powrót do strony pracownika sklepu.</a></p>";
?>