<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../img/favicon.ico" />
	<title>The 10% Campaign Admin: County Coordinators</title>
	<style type="text/css" media="screen">
		body { font: 100% "Trebuchet MS", sans-serif;line-height: 1.2em; }
		a:link {color:#052d5d;}
		a:visited { color:#11335b;}
		a:hover {color:#0156bc;}
		.blue { color: #5D7EA3; }
		.gray { color: gray; }
	</style>
</head>
<?php
	require_once('../defines.php');
	require_once('../db.php');
	require_once('../ncce_connect.php');
	require_once('../utilities.php');
	__get_table_count('members'); // We need to do this so mysql_real_escape_string works properly
	if (!__is_admin_user()) { echo 'Access denied'; exit; }
?>
<body bgcolor="#F8F8F8">

<p><a href="index.php">&laquo; Return to Admin Home</a></p>

<h2 class="blue">Delete a County Coordinator</h2>

<?php
$emp_id = mysql_real_escape_string($_GET['emp_id']);
$county_id = mysql_real_escape_string($_GET['county_id']);

if ($emp_id == '' || $county_id == '') {
	echo 'Invalid parameters';
} else if ($_GET['mode'] == '') {
	
	echo 'Are you sure you want to delete '.__get_emp_id_name($emp_id).' as a coordinator for '.__get_county_name($county_id).'?<br /><br />';
	
	echo '<a style="background-color:#F55;" href="coordinator_delete.php?mode=ok&amp;county_id='.$county_id.'&amp;emp_id='.$emp_id.'">Yes, Delete '.__get_emp_id_name($emp_id).'</a><br /><br />';
		
} else if ($_GET['mode'] == 'ok') {
	
	//$result = __do_db_sql(DATABASE_NAME_XEMP, "delete from nc10percent where county_id = '$county_id' and emp_id = '$emp_id' LIMIT 1");
	$result = __ncce_request('delete_county_coordinator', "$county_id|$emp_id", 'xemp');
	/*
	print_r($result);
	if ($result && mysql_affected_rows() == 1) {
		echo '<p style="color:green;">Successfully deleted ';
	} else {
		echo '<p style="color:red;">There was an error ('.mysql_error().'/'.mysql_errno().') deleting ';
	}
	*/
	echo '<p style="color:green;">Successfully deleted ';
	echo __get_emp_id_name($emp_id).' as a coordinator for '.__get_county_name($county_id).'</p>';
	
	echo '<p><a href="coordinator_add.php">Add a Coordinator</a><br /><span style="font-size:smaller;">Need to add a coordinator to replace this one?</span></p>';
	echo '<p><a href="coordinator_sync_list.php">Synchronize Mailing List</a><br /><span style="font-size:smaller;">Done making changes?</span></p>';
	
}

echo '<p><a href="coordinator_list.php">&laquo; Return to List</a></p>';

?>
</body>
</html>
