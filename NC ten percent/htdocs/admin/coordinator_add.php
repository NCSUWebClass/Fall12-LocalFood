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

<h2 class="blue">Add a County Coordinator</h2>

<?php
if ($_POST['mode'] == '') {
?>
<form action="coordinator_add.php" method="post" accept-charset="utf-8">
	<input type="hidden" name="mode" value="add" id="mode">
	
	<table style="border: solid 1px gray; background-color: #eee; font-size: smaller;" border="0" cellspacing="3" cellpadding="3">
		<tr><td>County</td>
		<td>
			<?php
			//$result = __do_db_sql(DATABASE_NAME_XEMP, "select distinct county_name as description, county_id as code from counties order by county_name");
			//__create_select_options($result, 'county_id');
			__create_county_popup_with_id('county_id');
			?>
		</td></tr>
		
		<tr><td>Employee</td>
		<td>
			<?php
			//$result = __do_db_sql(DATABASE_NAME_XEMP, "select distinct concat(last_name, ', ', preferred_name, ' (', unity_id, ')') as description, emp_id as code from employees where last_name <> '' and preferred_name <> '' and emp_status = 'A' order by last_name, preferred_name");
			$result = __ncce_request('employee_list', '', 'xemp');
			__create_select_options($result, 'emp_id');
			?>
		</td></tr>
	</table>

	<p><input type="submit" value="Add Coordinator"></p>
</form>
<?php
} else if ($_POST['mode'] == 'add') {
	$emp_id = mysql_real_escape_string($_POST['emp_id']);
	$county_id = mysql_real_escape_string($_POST['county_id']);
	
//	$result = __do_db_sql(DATABASE_NAME_XEMP, "insert into nc10percent (county_id, emp_id) values ('$county_id', '$emp_id')");
	$result = __ncce_request('add_county_coordinator', "$county_id|$emp_id", 'xemp');
	/*
	if ($result && mysql_affected_rows() == 1) {
		echo '<p style="color:green;">Successfully added ';
	} else {
		echo '<p style="color:red;">There was an error ('.mysql_error().'/'.mysql_errno().') adding ';
	}
	*/
	echo '<p style="color:green;">Successfully added ';
	echo __get_emp_id_name($emp_id).' as a coordinator for '.__get_county_name($county_id).'</p>';
	
	echo '<p><a href="coordinator_add.php">Add a Coordinator</a><br /><span style="font-size:smaller;">Need to add another coordinator?</span></p>';
	echo '<p><a href="coordinator_sync_list.php">Synchronize Mailing List</a><br /><span style="font-size:smaller;">Done making changes?</span></p>';
}

echo '<p><a href="coordinator_list.php">&laquo; Return to List</a></p>';
?>
</body>
</html>
