<?php
	session_start();

	function __autoload($class_name)
	{
		include $class_name.'.php';
	}

	$user = new User;
	$user->view->display_form('header');
	

	echo "<header>";
		echo "Sklep z grami komputerowymi. Strona użytkownika";
	echo "</header>";

	echo "<body>";
	echo "<div class=\"whole_screen\">";
		echo "<div class=\"child left_side_of_screen\">";
			echo "<div id=\"menu\">";
				echo "<p><a href='index.php'>Powrót do strony głównej sklepu.</a></p>";

				if( $user->_is_logged() )
				{
					$user->view->display_form('add_address');
					$user->view->display_form('default_address');
					$user->view->display_form('review');
				}
				else
					echo "Niezalogowany! Proszę wrócić na stronę główną i dokonać logowania lub rejestracji.";
				
				
			echo "</div>";
		echo "</div>";
			echo "<div class=\"child right_side_of_screen\">";
				echo "<div id=\"userdata\">";
				
				if($user->_is_logged())
				{
					$user->database->display_user_orders($_SESSION['user']);
					echo "<input type=\"submit\" value=\"Zwróć zaznaczone\">";					
					echo "</form>";
					$user->database->display_returned_games($_SESSION['user']);
					$user->database->display_addresses($_SESSION['user']);
					
				}
					else
						echo "</form>";
						
				echo "</div>";
				
			echo "</div>";
		echo "</div>";
	echo "</body>";

	echo "</html>";

?>