<?php

	require_once("Viewer.php");
	
	class User
	{
		
		public $view;
		public $database;
		
		function __construct()
		{
			$this->view = new Viewer();
			$this->database = new DatabaseManager();
			session_start();
		}

		function _register()
		{
			$fname = $_POST['fname'];
			$lname = $_POST['lname'];
			$email = $_POST['email'];
			$pass = $_POST['pass'];
			
			$regulamin = $_POST['regulamin'];
			$marketing = $_POST['marketing'];
			$RODO = $_POST['RODO'];
			
			if( !$regulamin or !$RODO)
				echo "Regulamin serwisu oraz zgoda na przetwarzanie danych muszą być wyrażone by dokonać rejestracji.";
			else
			{
				if( $fname and $lname and $email and $pass)
				{
					$this->database->register_new_user_in_database($fname, $lname, $email, $pass);
					if($marketing)
						$this->database->add_marketing_agreement($email);
				}
					
				else
					echo "Proszę podać wszystkie konieczne dane";
			}
		}

		function _login()
		{
			$email = $_POST['email'];
			$pass = $_POST['pass'];

			$pass_from_db = $this->database->get_user_password($email);

			if($pass_from_db == $pass)
			{
				$_SESSION['auth'] = 'OK' ;
				$_SESSION['user'] = $email ;
				$access = true ;
			}

			return  $access ? 'Zalogowano pomyślnie.' : 'Proszę podać poprawny login i hasło!';
		}

		function _logout()
		{
			unset($_SESSION);
			session_destroy();
			return 'Wylogowano.';
		}

		function _is_logged()
		{
			if ( isset ( $_SESSION['auth'] ) )
				$logged = ($_SESSION['auth'] == 'OK');
			else
				$logged = false;
			return $logged ;
		}
		
		function make_order()
		{
			$games_count = $this->database->get_games_count();
			$digital_games_to_order = array();
			$games_to_order = array();
			$index = 1;
			$digital = false;
			$physical = false;
			
			while($index <= $games_count)
			{
				if($_POST[strval($index)] == 'true')
				{
					if($this->database->is_game_digital($index))
					{
						$digital_games_to_order[] = $index;
						$digital = true;
					}
					else
					{
						$games_to_order[] = $index;
						$physical = true;
					}
				}
				
				$index = $index + 1;
			}

			if($digital)
				$this->database->order_from_shop($_SESSION['user'], $digital_games_to_order, 1);
			if($physical)
				$this->database->order_from_shop($_SESSION['user'], $games_to_order, $_POST['delivery']);
		}
		
		function make_return()
		{
			$games_count = $this->database->get_games_count();
			$games_to_return = array();
			$index = 1;
			$digital = false;
			$physical = true;
			
			while($index <= $games_count)
			{
				if($_POST[strval($index)] == 'true')
				{
					if($this->database->is_game_digital($index))
						$digital = true;
					else
					{
						$games_to_return[] = $index;
						$physical = true;
					}
				}
				
				$index = $index + 1;
			}

			if($digital)
				echo "Klucze nie podlegają zwrotom.";
			if($physical)
				$this->database->return_games($_SESSION['user'], $games_to_return);
		}
	}

?>