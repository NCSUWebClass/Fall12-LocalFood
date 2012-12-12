<?php  

	//require("defines.php"); 
	include('defines.php');
	include('genXMLPartnersDetailHelper.php');	

	$color = $_GET['sh'];
	$category = $_GET['c'];
	$zipCode = $_GET['z'];
	$radius = $_GET['r'];
	$county = $_GET['cty'];

	function parseToXML($htmlStr) { 
		$xmlStr=str_replace('<','&lt;',$htmlStr); 
		$xmlStr=str_replace('>','&gt;',$xmlStr); 
		$xmlStr=str_replace('"','&quot;',$xmlStr); 
		$xmlStr=str_replace("'",'&#39;',$xmlStr); 
		$xmlStr=str_replace("&",'&amp;',$xmlStr);
		$xmlStr=str_replace("?",'',$xmlStr);
		return $xmlStr; 
	} 

	// Start XML file, create parent node
	$dom = new DOMDocument("1.0");
	$node = $dom->createElement("markers");
	$parnode = $dom->appendChild($node); 

	// Opens a connection to a MySQL server
	$connection=mysql_connect (DATABASE_HOST, DATABASE_USER_NAME, DATABASE_USER_PASSWORD);
	if (!$connection) {  
		die('Not connected : ' . mysql_error());
	} 

	// Set the active MySQL database
	$db_selected = mysql_select_db(DATABASE_NAME, $connection);
	if (!$db_selected) {
	  die ('Can\'t use db : ' . mysql_error());
	} 


	// Start Logic for SQL Pulls
	if (!empty($color)) {
		if ($category == 'eatingout') {
			$type = 'restaurant';
			$result = _distinctType($type, $zipCode, $radius, $county, $color);
		} else if ($category == 'homebusiness') {
			$type = 'business';
			$result = _distinctType($type, $zipCode, $radius, $county, $color);
		} else if ($category == 'partners') {
			$type = 'farm';
			$result = _distinctType($type, $zipCode, $radius, $county, $color);
		} else if ($category == 'commPartners') {
			$type = 'community';
			$result = _distinctType($type, $zipCode, $radius, $county, $color);
		} else if ($category == 'statePartners') {
			$type = 'statewide';
			$result = _distinctType($type, $zipCode, $radius, $county, $color);
		} else {
			$result = _get_distinctTypeNoFilter($zipCode, $radius, $county, $color);
		}
	} else {
		if ($category == 'eatingout') {
			$type = 'restaurant';
			$result = _distinctTypeStatewide($type, $zipCode, $radius, $county);
		} else if ($category == 'homebusiness') {
			$type = 'business';
			$result = _distinctTypeStatewide($type, $zipCode, $radius, $county);
		} else if ($category == 'partners') {
			$type = 'farm';
			$result = _distinctTypeStatewide($type, $zipCode, $radius, $county);
		} else if ($category == 'commPartners') {
			$type = 'community';
			$result = _distinctTypeStatewide($type, $zipCode, $radius, $county);
		} else if ($category == 'statePartners') {
			$type = 'statewide';
			$result = _distinctTypeStatewide($type, $zipCode, $radius, $county);
		} else {
			$result = _get_distinctTypeStatewideNoFilter($zipCode, $radius, $county);
		}	
	}
	
	if (!$result) {  
		  die('Invalid query: ' . mysql_error());
	} 

	header("content-type: text/xml");

	echo '<markers> ';
	while ($row=mysql_fetch_assoc($result))	{
		echo '<marker ';
		echo 'name="' . parseToXML($row['name']) . '" ';
		echo 'address="' . parseToXML($row['address']) . '" ';
		echo 'lat="' . $row['lat'] . '" ';
		echo 'lng="' . $row['lng'] . '" ';
		echo 'type="'.$row['type'].'" ';
		echo '/>';
	}		
	echo '</markers>';
?>
