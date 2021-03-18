<?php
	session_start();

	function __autoload($class_name)
	{
		include $class_name.'.php';
	}

	$user = new User;

	echo "<!DOCTYPE html>";
	echo "<html lang=\"pl\">";

	echo "<head>";
		echo "<title>BD1 Projekt</title>";
		echo "<meta charset=\"UTF-8\">";
		echo "<meta name=\"viewport\" content=\"width=device-width\">";
		echo "<meta name=\"LANGUAGE\" content=\"pl\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />";
		echo "<script src=\"script.js\"></script>";
	echo "</head>";

	echo "<header>";
		echo "Sklep z grami komputerowymi.";
	echo "</header>";

	echo "<body>";
	echo "<div class=\"whole_screen\">";
		echo "<div class=\"child left_side_of_screen\">";
			echo "<div id=\"menu\">";

				if( !$user->_is_logged() )
				{
					echo "<button onclick=\"displayElementById('login_form')\">Logowanie</button><br>";
					echo "<button onclick=\"displayElementById('register_form')\">Rejestracja</button><br>";
					echo "<form action=\"superuser.php\">";
						echo "<button type=\"submit\">SuperUserButton.</button>";
					echo "</form>";
					echo "SuperUserButton może być użyty by zalogować jako pracownik sklepu.<br>";
					echo "Funckja zaimplementowana by w łatwy sposób przetestować pełnię możliwości bazy danych.";
				}
				else
				{
					echo "<form action=\"actions/logout.php\">";
						echo "<button type=\"submit\">Wyloguj</button>";
					echo "</form>";
					
					echo "<form action=\"userpage.php\">";
						echo "<button type=\"submit\">Sprawdź dane użytkownika.</button>";
					echo "</form>";					
					
					echo "<form action=\"community.php\">";
						echo "<button type=\"submit\">Sprawdz opinie klientów!.</button>";
					echo "</form>";
				}
				echo "</div>";
				
				if(!$user->_is_logged())
				{
					$user->view->display_form('login');
					$user->view->display_form('register');
				}
				
			echo "</div>";

			echo "<div class=\"child right_side_of_screen\">";
				echo "<div id=\"shop\">";
					$user->view->display_form('sorting_options');
					$user->database->display_shop_content($_POST['sortoption']);
					if($user->_is_logged())
					{
						print_delivery_selection($user->database->get_delivery_options());
						echo "<input type=\"submit\" value=\"Kup zaznaczone\">";					
						echo "</form>";
					}
					else
						echo "</form>";
						
				echo "</div>";
				
			echo "</div>";
		echo "</div>";
	echo "</body>";

	echo "</html>";

?>