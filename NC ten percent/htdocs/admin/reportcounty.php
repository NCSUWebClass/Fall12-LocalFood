<?php
require_once('../defines.php');
require_once('../db.php');
require_once('../utilities.php');
__get_table_count('members'); // We need to do this so mysql_real_escape_string works properly
if (!__is_admin_user()) { echo 'Access denied'; exit; }
$month=mysql_escape_string($_POST['month']);
$year=mysql_escape_string($_POST['year']);
$county=mysql_escape_string($_POST['county']);
$mnth = $month-1;

$csv = '';
$csv .= '"' . str_replace('"', '\"', "REPORT GENERATED ON ".Date("m-d-Y")) . '"';
$csv .= "\r\n";
$csv .= '"' . str_replace('"', '\"', "COMSUMER DATA FOR $county COUNTY") . '"';
$csv .= "\r\n";





$date = $year."-".$month."-30 00:00:00";
$prevdate = $year."-".$mnth."-30 00:00:00";
$res = __do_sql("SELECT count(DISTINCT pkey) FROM members,zipcodes where members.zipcode IN (SELECT zipcode from zipcodes where zipcodes.county='$county') AND members.registered_on < '$date' AND members.kind='consumer'");
while ($a=mysql_fetch_row($res))
{
$members = $a[0];
}

$res = __do_sql("SELECT sum(consumer_data_weekly.amount) as total_amount,sum(consumer_data_weekly.garden_amount) as garden_amount from (consumer_data_weekly inner join members on members.kind='consumer' and consumer_data_weekly.fkey=members.pkey and members.registered_on < '$date' and consumer_data_weekly.answered_on < '$date' and members.zipcode in (select zipcode from zipcodes where zipcodes.county='$county'))");
while ($b=mysql_fetch_row($res))
{
$amt = $b[0];
$gamt = $b[1];
}
$csv_line='';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', 'NUMBER OF CONSUMERS') . '"';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', $members) . '"';
$csv.=$csv_line;
$csv .= "\r\n";
$csv_line='';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', 'TOTAL AMOUNT SPENT ($)') . '"';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', $amt) . '"';
$csv.=$csv_line;
$csv .= "\r\n";
$csv_line='';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', 'HOME HARVEST AMOUNT ($)') . '"';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', $gamt) . '"';
$csv.=$csv_line;
$csv .= "\r\n";
$csv .= "\r\n";
$csv .= "\r\n";

$csv .= '"' . str_replace('"', '\"', 'SOURCES CONSUMERS HEARD FROM IN the PAST ONE MONTH') . '"';
$csv .= "\r\n";
$res = __do_sql ("SELECT DISTINCT heard_about_campaign from consumer_data_initial where answered_on < '$date' AND answered_on > '$prevdate' AND heard_about_campaign <> 'OTHER' AND heard_about_campaign <> '' ");
while ($c=mysql_fetch_row($res))
{
$csv .= '"' . str_replace('"', '\"', $c[0]) . '"';
$csv .= "\r\n";
}
$res = __do_sql ("SELECT heard_about_campaign_other from consumer_data_initial where answered_on < '$date' AND answered_on > '$prevdate' AND heard_about_campaign = 'OTHER' ");
while ($d=mysql_fetch_row($res))
{
$csv .= '"' . str_replace('"', '\"', $d[0]) . '"';
$csv .= "\r\n";
}
$csv .= "\r\n";
$csv .= "\r\n";

//$csv .= '"' . str_replace('"', '\"', 'RESTAURANT DATA FOR '$county' COUNTY') . '"';
$csv .= "\r\n";
$csv .= '"' . str_replace('"', '\"', 'RESTAURANT NAME') . '"';
if ($csv) $csv .= ",";
$csv .= '"' . str_replace('"', '\"', 'REGISTERED ON') . '"';
if ($csv) $csv .= ",";
$csv .= '"' . str_replace('"', '\"', 'COUNTY') . '"';
if ($csv) $csv .= ",";
$csv .= '"' . str_replace('"', '\"', 'FARMS') . '"';
if ($csv) $csv .= ",";
$csv .= '"' . str_replace('"', '\"', 'TOTAL AMOUNT SPENT') . '"';
if ($csv) $csv .= ",";
$csv .= '"' . str_replace('"', '\"', 'LAST REPORTED ON') . '"';
$csv .= "\r\n";

$ress=__do_sql("SELECT bname,registered_on,pkey from members where kind='business' and btype='RESTAURANT' and members.zipcode in (SELECT zipcode from zipcodes where zipcodes.county='$county')");
while ($rr=mysql_fetch_row($ress))
{
	$csv_line = '';
	if ($csv_line) $csv_line .= ",";
	$csv_line .= '"' . str_replace('"', '\"', $rr[0]) . '"';
	if ($csv_line) $csv_line .= ",";
	$csv_line .= '"' . str_replace('"', '\"', $rr[1]) . '"';
	
	$reslt = __do_sql ("SELECT distinct county from zipcodes,members where zipcodes.zipcode=members.zipcode and members.pkey=".$rr[2]);
	while ($rrr=mysql_fetch_row($reslt))
	{
		$cnty=$rrr[0];
	}
	
	$reslt1 = __do_sql ("SELECT MAX(source_local_farms) from restaurant_data_weekly  where source_local_farms <> '' and restaurant_data_weekly.answered_on < '".$year."-".$month."-30 00:00:00' and fkey=".$rr[2]);
	//echo "SELECT MAX(source_local_farms) from restaurant_data_weekly  where source_local_farms <> '' and restaurant_data_weekly.answered_on < '".$year."-".$month."-30 00:00:00' and fkey=".$rr[2];
	while ($rrs=mysql_fetch_row($reslt1))
	{
		$farms=$rrs[0];
	}
	$reslt2 = __do_sql ("SELECT sum(purchase_this_week) from restaurant_data_weekly where fkey=".$rr[2]." and restaurant_data_weekly.answered_on < '".$year."-".$month."-30 00:00:00'");
	while ($rrq=mysql_fetch_row($reslt2))
	{
		$amount=$rrq[0];
	}
	$reslt3 = __do_sql ("SELECT MAX(answered_on) from restaurant_data_weekly where fkey=".$rr[2]." and answered_on <'".$date."'");
	while ($rrt=mysql_fetch_row($reslt3))
	{
	$lastrep = $rrt[0];
	}
	if ($csv_line) $csv_line .= ",";
	$csv_line .= '"' . str_replace('"', '\"', $cnty) . '"';
	if ($csv_line) $csv_line .= ",";
	$csv_line .= '"' . str_replace('"', '\"', $farms) . '"';
	if ($csv_line) $csv_line .= ",";
	$csv_line .= '"' . str_replace('"', '\"', $amount) . '"';
	if ($csv_line) $csv_line .= ",";
	$csv_line .= '"' . str_replace('"', '\"', $lastrep) . '"';
	$csv .= $csv_line . "\r\n";
}
$csv .= "\r\n";
$csv .= "\r\n";
$csv .= '"' . str_replace('"', '\"', "BUSINESS LIST FOR $county COUNTY") . '"';
$csv .= "\r\n";
$csv .= '"' . str_replace('"', '\"', "NAME OF BUSINESS") . '"';
if ($csv) $csv .= ",";
$csv .= '"' . str_replace('"', '\"', "LAST REPORTED ON") . '"';
$csv .= "\r\n";

$res1=__do_sql("SELECT DISTINCT pkey,bname FROM members,zipcodes where members.zipcode IN (SELECT zipcode from zipcodes where zipcodes.county='$county') AND members.registered_on < '$date' AND members.kind='business' AND members.btype <> 'RESTAURANT' AND members.active='Y' AND members.opted_out='N'");
while ($rr=mysql_fetch_row($res1))
{
	$csv_line = '';
	if ($csv_line) $csv_line .= ",";
	$csv_line .= '"' . str_replace('"', '\"', $rr[1]) . '"';
	$abc = __do_sql ("SELECT MAX(data_entered_on) FROM business_data where fkey='$rr[0]' and data_entered_on <'$date'");
	while ($aa = mysql_fetch_row($abc))
	{
	$mm=$aa[0];
	}
	if ($csv_line) $csv_line .= ",";
	$csv_line .= '"' . str_replace('"', '\"', $mm) . '"';
$csv .= $csv_line . "\r\n";
}


$filename = "report_county" . $county."-".$month."_". $year;
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header("Content-disposition: filename=".$filename.".csv");
print $csv;
exit;
?>