<?php
	require_once('../defines.php');
	require_once('../db.php');
	require_once('../ncce_connect.php');
	require_once('../utilities.php');
	__get_table_count('members'); // We need to do this so mysql_real_escape_string works properly
	if (!__is_admin_user()) { echo 'Access denied'; exit; }
	
	$pkey = $_GET['p'];
	$csv='';
	if ($csv) $csv.= ",";
	$csv .= '"' . str_replace('"', '\"', 'Data entered on') . '"';
	if ($csv) $csv.= ",";
	$csv .= '"' . str_replace('"', '\"', 'Data entered by') . '"';
	if ($csv) $csv.= ",";
	$csv .= '"' . str_replace('"', '\"', 'Last edited by') . '"';
	if ($csv) $csv.= ",";
	$csv .= '"' . str_replace('"', '\"', 'Last edited on') . '"';
	if ($csv) $csv.= ",";
	$csv .= '"' . str_replace('"', '\"', 'Amount till date') . '"';
	if ($csv) $csv.= ",";
	$csv .= '"' . str_replace('"', '\"', 'Amount for the period') . '"';
	$csv .= "\r\n";
	
	$result = __do_sql ("select data_entered_on,data_entered_by,last_edited_by,last_edited_on,total_food,local_food from business_data where fkey=".$pkey);
	while ($r=mysql_fetch_row($result))
	{
		$csv_line='';
		for ($x=0;$x<6;$x++)
		{
			if ($csv_line) $csv_line .= ",";
			$csv_line .= '"' . str_replace('"', '\"', $r[$x]) . '"';
		}
		$csv .= $csv_line."\r\n";
	}
	
	$res = __do_sql ("select bname from members where pkey=".$pkey);
	while ($rr=mysql_fetch_assoc($res))
	{
		$name=$rr['bname'];
	}
	$tok=strtok($name,' ');
	

$filename = "history_" . $tok."_".date("Y_m_d_H_i",time());
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header("Content-disposition: filename=".$filename.".csv");
print $csv;
exit;	
?>

