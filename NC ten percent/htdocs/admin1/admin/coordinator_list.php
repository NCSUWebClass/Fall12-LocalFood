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

<h2 class="blue">County Coordinators</h2>

<p>
	<span style="float:right;">
		<a href="coordinator_sync_list.php">Synchronize Mailing List</a><br />
		<span style="font-size:smaller;">
			After adding or deleting users from this page<br />
			you will need to synchronize the mailing list<br />
			<a href="mailto:nc10100@lists.ncsu.edu">nc10100@lists.ncsu.edu</a>.
		</span>
		<br /><br />
		<span class="gray" style="font-size:smaller;">HELPFUL HINTS<br />
			&middot; County names displayed in red mean that the agent is no longer active.
		</span>
	</span>
	<a href="coordinator_add.php">Add a Coordinator</a><br /><span style="font-size:smaller;">Add a new coordinator to the list</span>
</p>

<p class="gray">
<table style="border: solid 1px gray; background-color: #eee; font-size: smaller;" border="0" cellspacing="3" cellpadding="3">
<?php
	//$result = __do_db_sql(DATABASE_NAME_XEMP, "select distinct(nc.emp_id) from nc10percent nc, employees e, counties c where nc.emp_id = e.emp_id and nc.county_id = c.county_id and c.ind = 0 order by c.county_name");
	$result = __ncce_request('distinct_emp_id_2', '', 'xemp');
	$distinct = count($result);
	//$result = __do_db_sql(DATABASE_NAME_XEMP, "select * from nc10percent nc, employees e, counties c where nc.emp_id = e.emp_id and nc.county_id = c.county_id and c.ind = 0 order by c.county_name");
	$result = __ncce_request('county_coordinators_2', '', 'xemp');
	echo '<tr><td colspan="3">'.count($result).' found ('.$distinct.' distinct)</td></tr>';
	echo '<tr style="font-weight:bold;"><td>County</td><td>Coordinator</td><td>&nbsp;</td></tr>';
	foreach ($result as $k => $r) {
	//while ($r = mysql_fetch_assoc($result)) {
		echo '<tr style="color:'.($r['emp_status']=='A'?'black':'red').';"><td>'.$r['county_name'].'</td><td><a href="mailto:'.$r['email'].'">'.$r['preferred_name'].' '.$r['last_name'].'</a></td><td><a href="coordinator_delete.php?county_id='.$r['county_id'].'&amp;emp_id='.$r['emp_id'].'">Delete</a></td></tr>';
	}
?>	
</table>
</p>
<h2 class="blue">County Coordinators Email Addresses</h2>
<p>You can also email the following addresses using the mailing list: <a href="mailto:nc10100@lists.ncsu.edu">nc10100@lists.ncsu.edu</a></p>
<p>
<?php
	//$result = __do_db_sql(DATABASE_NAME_XEMP, "select distinct email from nc10percent nc, employees e where nc.emp_id = e.emp_id");
	$result = __ncce_request('coordinators_email', '', 'xemp');
	foreach ($result as $k => $r) {
	//while ($r = mysql_fetch_assoc($result)) {
		echo $r['email'] . ', ';
	}
?>
</p>
</body>
</html>
