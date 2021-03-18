<?php
	session_start();

	function __autoload($class_name)
	{
		include $class_name.'.php';
	}

	$user = new User;
	$user->view->display_form('header');

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
					
					$user->database->display_reviews();
						
				echo "</div>";
				
			echo "</div>";
		echo "</div>";
	echo "</body>";

	echo "</html>";

?>