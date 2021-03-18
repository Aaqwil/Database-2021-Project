<form action="actions/register.php" id="register_form" method="post">
	<input type="text" value="email" id="register_mail" name="email">
	<input type="text" value="imie" id="register_first_name" name="fname">
	<input type="text" value="nazwisko" id="register_last_name" name="lname">
	<input type="text" value="pass" id="register_password" name="pass"><br><br>
	<input type="checkbox" id="regulamin" name="regulamin" value="regulamin">
	<label for="regulamin">Akceptuję regulamin.</label><br>
	<input type="checkbox" id="RODO" name="RODO" value="RODO">
	<label for="RODO">Wyrażam zgodę na przetwarzanie danych osobowych</label><br>
	<input type="checkbox" id="marketing" name="marketing" value="marketing">
	<label for="marketing">Wyrażam zgodę na przesyłanie danych marketingowych</label><br><br>
	<input type="submit">
</form>