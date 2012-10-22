<?php
	require_once('../defines.php');
	require_once('../db.php');
	require_once('../ncce_connect.php');
	require_once('../utilities.php');
	__get_table_count('members'); // We need to do this so mysql_real_escape_string works properly
	if (!__is_admin_user()) { echo 'Access denied'; exit; }

	header('Content-type: text/csv; charset=UTF-8');
	header("Content-Disposition: attachment; filename=coordinators.csv"); 

//	$result = __do_db_sql(DATABASE_NAME_XEMP, "select * from nc10percent nc, employees e, counties c, vw_county_locations l where nc.emp_id = e.emp_id and nc.county_id = c.county_id and c.ind = 0 and l.county_id = nc.county_id order by c.county_name");
	$result = __ncce_request('county_coordinators', '', 'xemp');
	echo '"County","FirstName","LastName","Email","Address","Address2","City","State","Zip","Phone","MobilePhone"'."\r\n";
	foreach ($result as $k => $r) {
//	while ($r = mysql_fetch_assoc($result)) {

		if ($r['w_phone']) {$phone=$r['w_phone'];} else {$phone=$r['phone'];}

		echo '"'.$r['county_name'].'","'.$r['preferred_name'].'","'.$r['last_name'].'","'.$r['email'].'","'.$r['m_address1'].'","'.$r['m_address2'].'","'.$r['m_city'].'","'.$r['m_state'].'","'.$r['m_zip'].'","'.$phone.'","'.$r['cell'].'"'."\r\n";

	}
?>	
