Dodaj nowy typ dostawy wraz z ceną oraz typowym czasem realizacji.
<form action="actions/add_delivery.php" method="post">
	<input type="text" value="typ" name="type">
	<input type="number" placeholder="cena" min="1" value="cena" name="price">
	<input type="number" placeholder="czas" min="1" value="czas" name="time">
	<input type="submit">
</form>