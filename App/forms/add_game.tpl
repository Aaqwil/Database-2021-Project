Dodaj nową grę do sklepu. Musi zawierać cenę oraz ilość.
<form action="actions/add_game.php" method="post">
	<input type="text" value="tytul" name="title">
	<input type="text" value="gatunek" name="genre">
	<input type="text" value="typ" name="game_type">
	<input type="text" value="wydawca" name="publisher">
	<input type="number" placeholder="cena" min="1" name="game_price">
	<input type="number" placeholder="ilość" min="1" name="amount">
	<input type="submit">
</form>	