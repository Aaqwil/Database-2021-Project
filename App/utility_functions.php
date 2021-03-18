<?php
	
	function print_data_as_HTML_table($query_result)
	{
		$column_names = get_column_names($query_result);
		$table = make_HTML_table($query_result);
		
		echo "<table>" . $column_names . implode('', $table) . "</table><br>";
	}
	
	function print_shop_data_with_buy_boxes($query_result)
	{
		$column_names = get_column_names($query_result) . "<th>Kup</th>";
		
		$table = array();
			
		while($row = pg_fetch_row($query_result))
		{
			$cells = array();
			foreach ($row as $cell)
				$cells[] = "<td>{$cell}</td>";
				
			$table[] = "<tr>" . implode('', $cells). "<td><input type=\"checkbox\" name=" . $row[0] . " value=\"true\" method=\"post\"></td></tr>";
		}
		
		echo "<form action=\"actions/buy.php\" method=\"post\"><table>" . $column_names . implode('', $table) . "</table><br>";
	}

	function print_games_for_possible_return($query_result)
	{
		$column_names = get_column_names($query_result) . "<th>Zwróć</th>";
		
		$table = array();
			
		while($row = pg_fetch_row($query_result))
		{
			$cells = array();
			foreach ($row as $cell)
				$cells[] = "<td>{$cell}</td>";
				
			$table[] = "<tr>" . implode('', $cells). "<td><input type=\"checkbox\" name=" . $row[0] . " value=\"true\" method=\"post\"></td></tr>";
		}
		
		echo "<form action=\"actions/return_games.php\" method=\"post\"><table>" . $column_names . implode('', $table) . "</table><br>";
	}
	
	function make_HTML_table($query_result)
	{
		$table = array();
		
		while($row = pg_fetch_row($query_result))
		{
			$cells = array();
			foreach ($row as $cell)
				$cells[] = "<td>{$cell}</td>";
				
			$table[] = "<tr>" . implode('', $cells) . "</tr>";
		}

		return $table;
	}
	
	function get_column_names($query_result)
	{
		$column_names = "";
		$column_number = 0;
		
		while($column_number < pg_num_fields($query_result))
		{
			$column_names = $column_names ."<th>" . pg_field_name($query_result, $column_number) . "</th>";
			$column_number = $column_number + 1;
		}
		
		return $column_names;
	}
	
	function print_delivery_selection($query_with_delivery)
	{
		$options = array();
		while($row = pg_fetch_array($query_with_delivery))
			if($row['id'] != 1)
				$options[] = ("<option value=\"" . $row['id'] . "\">" . $row['typ'] . " cena: " . $row['cena'] . " zł czas " . $row['czas'] . " dni</option>");
		echo "<select name=\"delivery\">" . implode('', $options) . "</select>";
	}

?>