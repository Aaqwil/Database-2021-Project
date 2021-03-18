<?php
	
	require_once("utility_functions.php");
	require_once("Viewer.php");
	
	class DatabaseManager
	{
		public $view;
		
		private $connection;
		private $table;
		private $result;
		private $query_string;

		function __construct()
		{
			$this->view = new Viewer();
			$this->connection = pg_connect("host=dumbo.db.elephantsql.com dbname=kgyfndzd user=kgyfndzd password=kDsgwdofEBQ88vfB8-tVw48u2t5Th2Lt");
		}

		function __destruct()
		{
			pg_close($this->connection);
		}

		function get_user_password($email)
		{
			$this->query_string = "SELECT haslo FROM projekt.klient WHERE projekt.klient.email = '".$email."';";
			$this->execute_given_query_string($this->query_string);

			return pg_fetch_row($this->table)[0];
		}

		function register_new_user_in_database($fname, $lname, $email, $pass)
		{
			$insert_string = "INSERT INTO projekt.klient VALUES ('" . $email . "', '" . $fname . "', '" . $lname . "', '" . $pass ."');";
			$this->execute_insert_or_update($insert_string);
			if(!$this->result)
				echo "Podczas rejestracji coś poszło nie tak.<br>";
			else
				echo "Pomyślnie dokonano rejestracji!<br>";
		}
		
		function display_shop_content($sort_option)
		{
			print_shop_data_with_buy_boxes($this->get_shop_content($sort_option));
		}
		
		function display_not_accepted_returns()
		{
			print_data_as_HTML_table($this->get_not_accepted_returns());
		}
		
		function display_not_delivered_orders()
		{
			print_data_as_HTML_table($this->get_orders());
		}
		
		function display_reviews()
		{
			print_data_as_HTML_table($this->get_reviews());
		}

		function display_publishers_data()
		{
			print_data_as_HTML_table($this->get_publishers());
		}
		
		function display_sold_game_list()
		{
			print_data_as_HTML_table($this->get_sold_game_list());		
		}
		
		function display_addresses($email)
		{
			print_data_as_HTML_table($this->get_client_addresses($email));
		}
		
		function display_user_orders($email)
		{
			print_games_for_possible_return($this->get_bought_games($email));
		}
		
		function display_returned_games($email)
		{
			print_data_as_HTML_table($this->get_returned_games($email));
		}
		
		function get_games_count()
		{
			return pg_fetch_row($this->get_games_cout_from_db())[0];
		}
		
		function is_game_digital($id)
		{
			$type = pg_fetch_row($this->get_game_type($id))[0];
			return ($type == 'kod');
		}
		
		function is_game_available($id)
		{
			$game_amount = $this->get_available_game_copies_count($id);
			return ($game_amount > 0);
		}
		
		function get_publisher_id($name)
		{
			return pg_fetch_row($this->get_publisher($name))[0];
		}
		
		function order_from_shop($email, $games_to_order, $delivery)
		{
			$next_order_index = pg_fetch_row($this->get_last_order_index())[0] + 1;
			$insert_string = "INSERT INTO projekt.zamowienie VALUES (";
			if($delivery == 1)
				$insert_string = $insert_string . $next_order_index . ", 'zrealizowane', " . $delivery . ", '" . $email ."');";
			else
				$insert_string = $insert_string . $next_order_index . ", 'przyjete', " . $delivery . ", '" . $email ."');";
			$this->execute_insert_or_update($insert_string);
			if(!$this->result)
				echo "Zamowienie nieudane!";
			
			foreach($games_to_order as $game)
			{
				if($this->is_game_available($game))
				{
					$insert_string = "INSERT INTO projekt.zamowione_gry VALUES (" . $next_order_index . ", " . $game . ");";
					$this->execute_insert_or_update($insert_string);
					if(!$this->result)
						echo "Coś poszło nie tak podczas zamawiania!";
				}
				else
					echo "Gra o wybranym id (" . $game . ") jest niedostępna. Zostanie usunięta z zamówienia.";
			}
		}
		
		function return_games($email, $games_to_return)
		{
			$next_return_index = pg_fetch_row($this->get_last_return_index())[0] + 1;
			$insert_string = "INSERT INTO projekt.zwrot VALUES (" . $next_return_index . ", '" . $email . "', 'zgloszony');";
			$this->execute_insert_or_update($insert_string);
			if(!$this->result)
				echo "Zwrt się nie powiódł!";
			
			foreach($games_to_return as $game)
			{
				$insert_string = "INSERT INTO projekt.zwrocone_gry VALUES (" . $next_return_index . ", " . $game . ");";
				$this->execute_insert_or_update($insert_string);
				if(!$this->result)
					echo "Coś poszło nie tak podczas zwracania gry!";
			}
		}
		
		function insert_new_game($title, $genre, $price, $type, $amount, $publisher_id)
		{
			$new_game_id = $this->get_games_count() + 1;
			$insert_string = "INSERT INTO projekt.gra VALUES (" . $new_game_id . ", '" . $title . "', '" . $genre . "', " . $price . ", '" . $type . "', " . $amount . ", " . $publisher_id . ");";
			$this->execute_insert_or_update($insert_string);
			if(!$this->result)
				echo "Nie udało się przygotować nowej gry!";		
			else
				echo "Dodano nową grę do kupienia!";
		}
		
		function insert_new_delivery($type, $price, $time)
		{
			$next_index = $this->get_next_delivery_type_index();
			$insert_string = "INSERT INTO projekt.dostawa VALUES (" . $next_index . ", '" . $type . "', " . $price . ", " . $time . ");";
			$this->execute_insert_or_update($insert_string);
			
			if(!$this->result)
				echo "Dodanie nowego typu dostawy się nie powiodło.";
		}
		
		function insert_new_publisher($publisher_name, $pubilsher_mail, $phone)
		{
			$next_index = $this->get_next_publisher_id();
			$insert_string = "INSERT INTO projekt.wydawca VALUES (" . $next_index . ", '" . $publisher_name . "', '" . $pubilsher_mail . "', '" . $phone . "');";
			$this->execute_insert_or_update($insert_string);
			
			if(!$this->result)
				echo "Dodanie nowego wydawcy się nie powiodło.<br>";
			else
				echo "Pomyślnie dodano wydawcę do kontaktów sklepu!<br>";
		}
		
		function insert_new_address($city, $street, $house, $flat, $email)
		{
			$next_index = $this->get_next_address_index();
			$insert_string = "INSERT INTO projekt.adres";
			if($flat)
				$insert_string = $insert_string .  " VALUES (" . $next_index . ", '" . $city . "', '" . $street . "', " . $house . ", " . $flat . ");";
			else
				$insert_string = $insert_string .  "(id, miasto, ulica, nr_dom) VALUES (" . $next_index . ", '" . $city . "', '" . $street . "', " . $house . ");";
			$this->execute_insert_or_update($insert_string);
			
			if(!$this->result)
				echo "Dodanie nowego adresu się nie powiodło.<br>";
			else
				echo "Pomyślnie dodano adres do bazy!<br>";
				
			if($next_index != $this->get_next_address_index())
				$this->add_new_address_for_client($next_index, $email);
			else
				$this->add_existing_address_for_client($city, $street, $house, $flat, $email);
		}
		
		function insert_new_review($ordered_game_id, $user, $review)
		{
			$insert_string = "INSERT INTO projekt.ocena VALUES ('" . $user . "', " . $ordered_game_id . ", '" . $review . "');";
			$this->execute_insert_or_update($insert_string);
			
			if(!$this->result)
				echo "Operacja nieudana.<br>";
			else
				echo "Dane zapisano. Jesli nie kupiles gry nie opublikowano recenzji!";
		}
		
		function realize_order($order_id)
		{
			$update_string = "UPDATE projekt.zamowienie SET stan = 'zrealizowane' WHERE id = " . $order_id . ";";
			$this->execute_insert_or_update($update_string);
			
			if(!$this->result)
				echo "Zamówienie nie mogło zostać zrealizowane.<br>";
			else
				echo "Pomyślnie wprowadzono zmiany!<br>";
		}
		
		function accept_return($return_id)
		{
			$update_string = "UPDATE projekt.zwrot SET stan = 'przyjety' WHERE id = " . $return_id . ";";
			$this->execute_insert_or_update($update_string);
			
			if(!$this->result)
				echo "Zwrot nie mógł zostać przyjęty.<br>";
			else
				echo "Pomyślnie wprowadzono zmiany!<br>";			
		}
		
		function add_more_game_copies($game_id, $additional_copies)
		{
			$new_amount = $this->get_available_game_copies_count($game_id) + $additional_copies;
			$update_string = "UPDATE projekt.gra SET ilosc = " . $new_amount . " WHERE id = " . $game_id . ";";
			$this->execute_insert_or_update($update_string);
			
			if(!$this->result)
				echo "Nie udało się dodać nowej gry.<br>";
			else
				echo "Pomyślnie wprowadzono zmiany!<br>";			
		}
		
		function make_address_default($user, $address_id)
		{
			$update_string = "UPDATE projekt.adres_klienta SET domyslny = 'f' WHERE email = '" . $user . "';";
			$this->execute_insert_or_update($update_string);
			
			if(!$this->result)
				echo "Nie usunięto starego adresu domyślnego.<br>";
			else
				echo "Pomyślnie wprowadzono zmiany!<br>";

			$update_string = "UPDATE projekt.adres_klienta SET domyslny = 't' WHERE email = '" . $user . "' AND id_a = " . $address_id . ";";
			$this->execute_insert_or_update($update_string);

			if(!$this->result)
					echo "Nie ustawiono adresu domyślnego.<br>";
				else
					echo "Pomyślnie wprowadzono zmiany!<br>";
		}
		
		function get_delivery_options()
		{
			$this->query_string = "SELECT * FROM projekt.dostawa;";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table; 
		}
		
		private function add_existing_address_for_client($city, $street, $house, $flat, $email)
		{
			$id = $this->get_addresses_id($city, $street, $house, $flat);
			$this->add_address_for_client($id, $email);
		}
		
		private function add_new_address_for_client($index, $email)
		{
			$this->add_address_for_client($index, $email);
		}
		
		private function add_address_for_client($index, $email)
		{
			$insert_string = "INSERT INTO projekt.adres_klienta VALUES ('" . $email . "', " . $index . ", 'f');";
			$this->execute_insert_or_update($insert_string);
			
			if(!$this->result)
				echo "Dodanie nowego adresu się nie powiodło.<br>";
			else
				echo "Użytkownik ma teraz nowy adres!<br>";
		}
		
		private function get_addresses_id($city, $street, $house, $flat)
		{
			$this->query_string = "SELECT id FROM projekt.adres WHERE miasto = '" . $city . "' AND ulica = '" . $street . "' AND nr_dom = " . $house;
			if($flat)
				$this->query_string = $this->query_string . "AND nr_mieszkanie = " . $flat . ";";
			else
				$this->query_string = $this->query_string . ";";
				
			$this->execute_given_query_string($this->query_string);
			return pg_fetch_row($this->table)[0];
		}
		
		private function get_reviews()
		{
			$this->query_string = "SELECT * FROM oceny_uzytkownikow;";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table; 				
		}
		
		private function get_not_accepted_returns()
		{
			$this->query_string = "SELECT * FROM projekt.zwrot WHERE stan LIKE 'zgloszony'";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table; 				
		}
		
		private function get_publishers()
		{
			$this->query_string = "SELECT * FROM projekt.wydawca;";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table; 			
		}
		
		private function get_publisher($name)
		{
			$this->query_string = "SELECT id FROM projekt.wydawca WHERE nazwa = '" . $name . "';";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table; 
		}
		
		private function get_available_game_copies_count($id)
		{
			return pg_fetch_row($this->get_game_amount($id))[0];
		}
		
		private function get_next_address_index()
		{
			return (pg_fetch_row($this->get_addresses_count())[0] + 1);
		}		
		
		private function get_next_delivery_type_index()
		{
			return (pg_fetch_row($this->get_deliveries_count())[0] + 1);
		}
		
		private function get_next_publisher_id()
		{
			$index = pg_fetch_row($this->get_publishers_count())[0] + 1;
			return $index;
		}
		
		private function get_orders()
		{
			$this->query_string = "SELECT * FROM niezrealizowane_zamowienia;";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table;  
		}
		
		private function get_sold_game_list()
		{
			$this->query_string = "SELECT * FROM sprzedane_gry;";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table; 		
		}	
		
		private function get_game_amount($id)
		{
			$this->query_string = "SELECT ilosc FROM projekt.gra WHERE id = " . $id . ";";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table;
		}
		
		private function get_game_type($id)
		{
			$this->query_string = "SELECT typ FROM projekt.gra WHERE id = " . $id . ";";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table;
		}
		
		private function get_addresses_count()
		{
			$this->query_string = "SELECT MAX(id) FROM projekt.adres";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table;				
		}
		
		private function get_deliveries_count()
		{
			$this->query_string = "SELECT MAX(id) FROM projekt.dostawa";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table;			
		}
		
		private function get_games_cout_from_db()
		{
			$this->query_string = "SELECT MAX(id) FROM projekt.gra";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table;
		}
		
		private function get_publishers_count()
		{
			$this->query_string = "SELECT MAX(id) FROM projekt.wydawca";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table;			
		}
			
		private function get_last_order_index()
		{
			$this->query_string = "SELECT MAX(id) FROM projekt.zamowienie";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table;
		}
		
		private function get_last_return_index()
		{
			$this->query_string = "SELECT MAX(id) FROM projekt.zwrot";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table;			
		}
		
		private function get_client_addresses($email)
		{
			$this->query_string = "SELECT * FROM przypisane_adresy('" . $email . "');";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table;
		}
		
		private function get_returned_games($email)
		{
			$this->query_string = "SELECT * FROM pobierz_zwroty_klienta('" . $email . "');";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table;
		}
		
		private function get_bought_games($email)
		{
			$this->query_string = "SELECT * FROM pobierz_gry_klienta('" . $email . "');";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table;
		}
		
		private function get_shop_content($sort_option)
		{
			if($sort_option)
				$sort_option = "ORDER BY " . $sort_option;
			else
				$sort_option = "ORDER BY gra.id";
			$this->query_string = "SELECT gra.id, tytul, gatunek, cena, typ, ilosc, nazwa AS wydawca FROM projekt.gra JOIN projekt.wydawca ON projekt.gra.wydawca = projekt.wydawca.id " . $sort_option . ";";
			$this->execute_given_query_string($this->query_string);
			
			return $this->table;
		}
		
		private function execute_given_query_string($string)
		{
			$this->table = pg_query($this->connection, $string);
	
			if(!$this->table)
				echo("Query " . $string . " failed\n");
		}
		
		private function execute_insert_or_update($insert_string)
		{
			$this->result = pg_query($this->connection, $insert_string);
	
			if(!$this->result)
				echo("Query " . $insert_string . " failed\n");
		}
	}
?>