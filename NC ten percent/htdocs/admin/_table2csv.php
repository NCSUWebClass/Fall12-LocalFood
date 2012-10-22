<?php
require_once('../defines.php');
require_once('../db.php');
require_once('../utilities.php');
__get_table_count('members'); // We need to do this so mysql_real_escape_string works properly
if (!__is_admin_user()) { echo 'Access denied'; exit; }

$table = mysql_escape_string($_POST['table']);
$csv = '';
$num_columns = 0;

$result = __do_sql("show columns from $table");
$num_columns = mysql_num_rows($result);
while ($r = mysql_fetch_assoc($result)) {
	if ($csv) $csv .= ",";
	$csv .= '"' . str_replace('"', '\"', $r['Field']) . '"';
}
$csv .= "\r\n";

$result = __do_sql("select * from $table");
while ($r = mysql_fetch_row($result)) {
	$csv_line = '';
	for ($x = 0; $x < $num_columns; $x++) {
		if ($csv_line) $csv_line .= ",";
		$csv_line .= '"' . str_replace('"', '\"', $r[$x]) . '"';
	}
	$csv .= $csv_line . "\r\n";
}

$filename = "nc10percent_" . $table."_".date("Y_m_d_H_i",time());
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header("Content-disposition: filename=".$filename.".csv");
print $csv;
exit;
?>