<?php
	
	function __autoload($class_name)
	{
		include '../' . $class_name.'.php';
	}
	
	$user = new User;
	echo $user->_logout() ;
	echo "<p><a href='../index.php'>Powrót do strony głównej sklepu.</a></p>";
?>