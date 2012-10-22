<?php 

	/**
	 * Loads Stylesheet depending on what page we're on
	 *
	 * @return string
	 */
	function loadStyles() {
		$currentFile = getCurrent();
		if (!strcmp($currentFile,"index.php")) {
			return '<link rel="stylesheet" href="css/import.css" type="text/css" />';
		} else {
			return '<link rel="stylesheet" href="css/import.css" type="text/css" />
					<style type="text/css">
						div#wrapper div#main div#belowfold { position:absolute; top:30px; z-index:1;  background-color:#FFFFFF; padding:80px 20px; }
					</style>
				   ';
		}
	}

	/**
	 * Gets Current Filename
	 *
	 * @return string
	 */
	function getCurrent() {
		$currentFile = $_SERVER["SCRIPT_NAME"];
		$parts = Explode('/', $currentFile);
		$currentFile = $parts[count($parts) - 1];
		return $currentFile;
	}



?>
