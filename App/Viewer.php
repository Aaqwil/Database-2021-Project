<?php
	
	class Viewer
	{
		private $path;

		function __construct()
		{
			$this->path = "forms/";
		}

		function display_form($name)
		{
			$filepath = $this->path . $name . ".tpl";
			include($filepath);
			$output = ob_get_contents();
			ob_end_clean();
			echo $output;
		}
	}
?>