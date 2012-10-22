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
<a href="cinfo2csv.php">Export list as coordinators.csv</a>
</p>

<p>
You can email this list by sending a message to <a href="mailto:nc10100@lists.ncsu.edu">nc10100@lists.ncsu.edu</a>
</p>

<p class="gray">
<table style="border: solid 1px gray; background-color: #eee; font-size: smaller;" border="0" cellspacing="3" cellpadding="3">
<?php
/*
	$result = __do_db_sql(DATABASE_NAME_XEMP, "select distinct(nc.emp_id) from nc10percent nc, employees e, counties c, locations l where nc.emp_id = e.emp_id and nc.county_id = c.county_id and c.ind = 0 and l.id = nc.county_id order by c.county_name");
	$distinct = mysql_num_rows($result);
*/
	$result = __ncce_request('distinct_emp_id', '', 'xemp');
	$distinct = count($result);

//	$result = __do_db_sql(DATABASE_NAME_XEMP, "select * from nc10percent nc, employees e, counties c, vw_county_locations l where nc.emp_id = e.emp_id and nc.county_id = c.county_id and c.ind = 0 and l.county_id = nc.county_id order by c.county_name");
	$result = __ncce_request('county_coordinators', '', 'xemp');
	
	echo '<tr><td colspan="3">'.count($result).' found ('.$distinct.' distinct)</td></tr>';
	echo '<tr style="font-weight:bold;"><td>County</td><td>Coordinator</td><td>Email</td><td>Address</td><td>Phone</td><td>Mobile Phone</td></tr>';
//	while ($r = mysql_fetch_assoc($result)) {
	foreach ($result as $k => $r) {
		if ($r['w_phone']) {$phone=$r['w_phone'];} else {$phone=$r['phone'];}
		$phone=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1)$2-$3", $phone);

		$cell=preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1)$2-$3", $r['cell']);

		echo '<tr valign="top" style="color:'.($r['emp_status']=='A'?'black':'red').';"><td>'.$r['county_name'].'</td><td>'.$r['preferred_name'].' '.$r['last_name'].'</td><td>'.$r['email'].'</td><td>'.$r['m_address1'].'<br/>';

		if ($r['m_address2']) {echo $r['m_address2'].'<br/>'; }

		echo $r['m_city'].' '.$r['m_state'].' '.$r['m_zip'].'</td><td>'.$phone.'</td><td>'.$cell.'</td></tr>'."\n";
	}
?>	
</table>
</p>
</body>
</html>
