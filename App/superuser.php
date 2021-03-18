<?php
	session_start();

	function __autoload($class_name)
	{
		include $class_name.'.php';
	}

	$manager = new DatabaseManager;
	$manager->view->display_form('header');

	echo "<header>";
		echo "Sklep z grami komputerowymi. Strona pracownika sklepu.";
	echo "</header>";

	echo "<body>";
	echo "<div class=\"whole_screen\">";
		echo "<div class=\"child left_side_of_screen\">";
			echo "<div id=\"menu\">";
				echo "<p><a href='index.php'>Powrót do strony głównej sklepu.</a></p>";
				
				$manager->view->display_form('add_delivery');
				$manager->view->display_form('add_game');
				$manager->view->display_form('add_publisher');
				$manager->view->display_form('order_status');
				$manager->view->display_form('return_status');
				$manager->view->display_form('add_more_game_copies');
				
			echo "</div>";
		echo "</div>";
			echo "<div class=\"child right_side_of_screen\">";
				echo "<div id=\"shopdata\">";
				
				echo "Zamowienia czekające na realizację.<br>";
				$manager->display_not_delivered_orders();
				echo "Zwroty czekające na przyjęcie.<br>";
				$manager->display_not_accepted_returns();
				echo "Sprzedane gry.<br>";
				$manager->display_sold_game_list();
				echo "Opcje dostawy dostępne dla klientów.<br>";
				print_data_as_HTML_table($manager->get_delivery_options());
				echo "Dane wydawców:<br>";
				$manager->display_publishers_data();
				
				echo "</div>";
				
			echo "</div>";
		echo "</div>";
	echo "</body>";

	echo "</html>";

?>