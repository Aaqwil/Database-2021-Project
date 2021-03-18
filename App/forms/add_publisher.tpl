Dodaj nowego wydawcę do kontaktów sklepu.
<form action="actions/add_publisher.php" method="post">
	<input type="text" value="nazwa" name="publisher_name">
	<input type="text" value="email" name="pubilsher_mail">
	<input type="text" pattern=[0-9]{9} value="000000000" name="phone">
	<input type="submit">
</form>	