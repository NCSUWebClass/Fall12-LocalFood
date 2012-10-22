<?php
require_once('../defines.php');
require_once('../db.php');
require_once('../utilities.php');
__get_table_count('members'); // We need to do this so mysql_real_escape_string works properly
if (!__is_admin_user()) { echo 'Access denied'; exit; }
$month=mysql_escape_string($_POST['month']);
$year=mysql_escape_string($_POST['year']);
//echo $month;
//echo $year;
$northcentral = array ('ALAMANCE','CASWELL','CHATHAM','DAVIDSON','DURHAM','FORSYTH','FRANKLIN','GRANVILLE','GUILFORD','ORANGE','PERSON','RANDOLPH','ROCKINGHAM','STOKES','VANCE','WAKE','WARREN');
$northeast = array ('BEAUFORT','BERTIE','CAMDEN','CHOWAN','CURRITUCK','DARE','EDGECOMBE','GATES','HALIFAX','HERTFORD','MARTIN','NASH','NORTHAMPTON','PASQUOTANK','PERQUIMANS','PITT');
$southcentral = array('ANSON','BLADEN','CABARRUS','COLUMBUS','CUMBERLAND','HARNETT','HOKE','LEE','MONTGOMERY','MOORE','RICHMOND','ROBESON','SCOTLAND','STANLY','UNION');
$southeast = array('BRUNSWICK','CARTERET','CRAVEN','DUPLIN','GREENE','JOHNSTON','JONES','LENOIR','NEW HANOVER','ONSLOW','PAMLICO','PENDER','SAMPSON','WAYNE','WILSON');
$west=array('AVERY','BUNCOMBE','CHEROKEE','CLAY','GRAHAM','HAYWOOD','HENDERSON','JACKSON','MACON','MADISON','MCDOWELL','MITCHELL','POLK','SWAIN','TRANSYLVANIA','WATAUGA','YANCEY');
$westcentral=array('ALEXANDER','ASHE','BURKE','CALDWELL','CATAWBA','CLEVELAND','DAVIE','GASTON','IREDELL','LINCOLN','MECKLENBURG','ROWAN','RUTHERFORD','SURRY','WILKES','YADKIN');

function arrstring ($input)
{
$count = count ($input);
$retstr = '';
for ($i=0;$i<$count;$i++)
{	
	if ($i==($count-1))
	{
		$retstr=$retstr."county= '".$input[$i]."')";
	}
	else if ($i==0)
	{
		$retstr=$retstr."(county= '".$input[$i]."' OR ";
	}
	else
	{
	$retstr=$retstr."county= '".$input[$i]."' OR ";
	}	
}
return $retstr;
}

$csv = '';
/*if ($csv) $csv .= ",";
$csv .= '"' . str_replace('"', '\"', "CAMPAIGN STATISTICS") . '"';
$csv .= "\r\n";
$starttime = $year."-".$month."-30";
$strstart= "2010-07-19";
$secs = strtotime($starttime) - strtotime($strstart);
$days = floor($secs/86400);
$csv_line='';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', 'NUMBER OF DAYS') . '"';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', $days) . '"';
$csv.=$csv_line;
$csv .= "\r\n";


$res= __do_sql ("SELECT count(distinct pkey) from members where kind='consumer' and registered_on < '".$year."-".$month."-30'");
while ($rr=mysql_fetch_row($res))
{
	$consumers=$rr[0];
}
$csv_line='';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', 'NUMBER OF CONSUMERS') . '"';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', $consumers) . '"';
$csv.=$csv_line;
$csv .= "\r\n";

$res= __do_sql ("SELECT count(distinct pkey) from members where kind='business' and registered_on < '".$year."-".$month."-30'");
while ($rr=mysql_fetch_row($res))
{
	$businesses=$rr[0];
}
$csv_line='';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', 'NUMBER OF BUSINESSES') . '"';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', $businesses) . '"';
$csv.=$csv_line;
$csv .= "\r\n";

$res= __do_sql ("SELECT SUM(amount) from consumer_data_weekly where answered_on < '".$year."-".$month."-30'");
while ($rr=mysql_fetch_row($res))
{
	$sumcons=$rr[0];
}
$csv_line='';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', 'TOTAL CONSUMER SPENDING') . '"';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', $sumcons) . '"';
$csv.=$csv_line;
$csv .= "\r\n";

$res= __do_sql ("SELECT SUM(local_food) from business_data,members where members.pkey=business_data.fkey and members.btype <> 'RESTAURANT'");
while ($rr=mysql_fetch_row($res))
{
	$sumbus=$rr[0];
}
$csv_line='';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', 'TOTAL BUSINESS SPENDING') . '"';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', $sumbus) . '"';
$csv.=$csv_line;
$csv .= "\r\n";

$res= __do_sql ("SELECT SUM(purchase_this_week) from restaurant_data_weekly,members where members.pkey=restaurant_data_weekly.fkey and members.btype = 'RESTAURANT' ");
while ($rr=mysql_fetch_row($res))
{
	$sumrest=$rr[0];
}
$csv_line='';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', 'TOTAL RESTAURANT SPENDING') . '"';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', $sumrest) . '"';
$csv.=$csv_line;
$csv .= "\r\n";

$totalspend= $sumcons+$sumbus+$sumrest;
$csv_line='';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', 'TOTAL LOCAL SPENDING') . '"';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', $totalspend) . '"';
$csv.=$csv_line;
$csv .= "\r\n";
*/
if ($csv) $csv .= ",";
$csv .= '"' . str_replace('"', '\"', "NUMBER OF CONSUMERS") . '"';
$csv .= "\r\n";

$ncentral=arrstring ($northcentral);
$neast = arrstring ($northeast);
$southcent = arrstring ($southcentral);
$southe = arrstring ($southeast);
$westt = arrstring ($west);
$westc = arrstring ($westcentral);
$arrregion = array ($ncentral,$neast,$southcent,$southe,$westt,$westc);
$cnt = count ($arrregion);
$sumarray = array (0,0,0,0,0,0);
$summ=0;
for ($j=0;$j<$cnt;$j++)
{
if ($j==0)
{	
$csv .= '"' . str_replace('"', '\"', 'NORTH CENTRAL') . '"';
$csv .= "\r\n";
}
if ($j==1)
{	
$csv .= '"' . str_replace('"', '\"', 'NORTHEAST') . '"';
$csv .= "\r\n";
}
if ($j==2)
{
	
$csv .= '"' . str_replace('"', '\"', 'SOUTH CENTRAL') . '"';
$csv .= "\r\n";
}
if ($j==3)
{
$csv .= '"' . str_replace('"', '\"', 'SOUTHEAST') . '"';
$csv .= "\r\n";
}
if ($j==4)
{
$csv .= '"' . str_replace('"', '\"', 'WEST') . '"';
$csv .= "\r\n";
}
if ($j==5)
{
$csv .= '"' . str_replace('"', '\"', 'WEST CENTRAL') . '"';
$csv .= "\r\n";
}	
$sumtot=0;	
$result = __do_sql("SELECT county,count(DISTINCT pkey) as count from members,zipcodes where members.zipcode=zipcodes.zipcode and members.kind='consumer' and ".$arrregion[$j]." and members.registered_on < '".$year."-".$month."-30 00:00:00' group by county");
//echo "SELECT county,count(DISTINCT pkey) as count from members,zipcodes where members.zipcode=zipcodes.zipcode and members.kind='consumer' and ".$arrregion[$j]." and members.registered_on < '".$year."-".$month."-01 00:00:00' group by county";
while ($rr=mysql_fetch_row($result))
{	
	$sumrow=0;
	$ress = __do_sql("SELECT sum(consumer_data_weekly.amount) as total_amount from (consumer_data_weekly inner join members on members.kind='consumer' and consumer_data_weekly.fkey=members.pkey and members.registered_on < '".$year."-".$month."-30 00:00:00' and consumer_data_weekly.answered_on < '".$year."-".$month."-30 00:00:00' and members.zipcode in (select zipcode from zipcodes where zipcodes.county='".$rr[0]."'))");
	//echo "SELECT sum(consumer_data_weekly.amount) as total_amount from (consumer_data_weekly inner join members on members.kind='consumer' and consumer_data_weekly.fkey=members.pkey and members.registered_on < '".$year."-".$month."-01 00:00:00' and consumer_data_weekly.answered_on < '".$year."-".$month."-01 00:00:00' and members.zipcode in (select zipcode from zipcodes where zipcodes.county='".$rr[0]."')) group by county";
	while ($rsum=mysql_fetch_row($ress))
	{
		$sumrow=$rsum[0];
	}
	$sumtot=$sumtot+$sumrow;
	$csv_line = '';
	if ($csv_line) $csv_line .= ",";
	$csv_line .= '"' . str_replace('"', '\"', $rr[0]) . '"';
	if ($csv_line) $csv_line .= ",";
	$csv_line .= '"' . str_replace('"', '\"', $rr[1]) . '"';
	$sumarray[$j]=$sumarray[$j]+$rr[1];
	if ($csv_line) $csv_line .= ",";
	$csv_line .= '"' . str_replace('"', '\"', $sumrow) . '"';
	$csv .= $csv_line . "\r\n";
}
$csv_line='';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', 'SUM IN REGION') . '"';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', $sumarray[$j]) . '"';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', $sumtot) . '"';
$csv .= $csv_line;
$csv .= "\r\n";

}
$csv .= "\r\n";
/*if ($csv) $csv .= ",";
$csv .= '"' . str_replace('"', '\"', 'BUSINESS STATISTICS') . '"';
$csv .= "\r\n";
$bsumarray = array (0,0,0,0,0,0);
for ($j=0;$j<$cnt;$j++)
{
	if ($j==0)
{	
$csv .= '"' . str_replace('"', '\"', 'NORTH CENTRAL') . '"';
$csv .= "\r\n";
}
if ($j==1)
{	
$csv .= '"' . str_replace('"', '\"', 'NORTHEAST') . '"';
$csv .= "\r\n";
}
if ($j==2)
{
	
$csv .= '"' . str_replace('"', '\"', 'SOUTH CENTRAL') . '"';
$csv .= "\r\n";
}
if ($j==3)
{
$csv .= '"' . str_replace('"', '\"', 'SOUTHEAST') . '"';
$csv .= "\r\n";
}
if ($j==4)
{
$csv .= '"' . str_replace('"', '\"', 'WEST') . '"';
$csv .= "\r\n";
}
if ($j==5)
{
$csv .= '"' . str_replace('"', '\"', 'WEST CENTRAL') . '"';
$csv .= "\r\n";
}
$bsumtot=0;
$bresult = __do_sql("SELECT county,count(DISTINCT pkey) as count from members,zipcodes where members.zipcode=zipcodes.zipcode and members.kind='business' and members.btype <> 'RESTAURANT' and members.opted_out='N' and ".$arrregion[$j]." and members.registered_on < '".$year."-".$month."-01 00:00:00' group by county");
while ($brr=mysql_fetch_row($bresult))
{	
	$bsumrow=0;
	$ressb = __do_sql("SELECT sum(business_data.local_food) as total_amount from (business_data inner join members on members.kind='business' and members.btype <> 'RESTAURANT' and business_data.fkey=members.pkey and members.registered_on < '".$year."-".$month."-01 00:00:00' and members.zipcode in (select zipcode from zipcodes where zipcodes.county='".$brr[0]."'))");
	//echo "SELECT sum(business_data.local_food) as total_amount from (business_data inner join members on members.kind='business' and members.btype <> 'RESTAURANT' and business_data.fkey=members.pkey and members.registered_on < '".$year."-".$month."-01 00:00:00' and members.zipcode in (select zipcode from zipcodes where zipcodes.county='".$brr[0]."'))";
	while ($brsum=mysql_fetch_row($ressb))
	{
		$bsumrow=$brsum[0];
	}
	$bsumtot=$bsumtot+$bsumrow;
	$csv_line = '';
	if ($csv_line) $csv_line .= ",";
	$csv_line .= '"' . str_replace('"', '\"', $brr[0]) . '"';
	if ($csv_line) $csv_line .= ",";
	$csv_line .= '"' . str_replace('"', '\"', $brr[1]) . '"';
	$bsumarray[$j]=$bsumarray[$j]+$brr[1];
	if ($csv_line) $csv_line .= ",";
	$csv_line .= '"' . str_replace('"', '\"', $bsumrow) . '"';
	$csv .= $csv_line . "\r\n";
}
$csv_line='';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', 'SUM IN REGION') . '"';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', $bsumarray[$j]) . '"';
if ($csv_line) $csv_line .= ",";
$csv_line .= '"' . str_replace('"', '\"', $bsumtot) . '"';
$csv .= $csv_line;
$csv .= "\r\n";

}

$csv .= '"' . str_replace('"', '\"', 'RESTAURANT DATA') . '"';
$csv .= "\r\n";
$ress= __do_sql ("SELECT bname,registered_on,pkey from members where kind='business' and btype='RESTAURANT'");
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
	if ($csv_line) $csv_line .= ",";
	$csv_line .= '"' . str_replace('"', '\"', $cnty) . '"';
	if ($csv_line) $csv_line .= ",";
	$csv_line .= '"' . str_replace('"', '\"', $farms) . '"';
	if ($csv_line) $csv_line .= ",";
	$csv_line .= '"' . str_replace('"', '\"', $amount) . '"';
	$csv .= $csv_line . "\r\n";
}
*/

$filename = "report_consumer" . $month."_". $year;
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header("Content-disposition: filename=".$filename.".csv");
print $csv;
exit;
/*
$result = __do_sql("SELECT county,count(DISTINCT pkey) as count from members,zipcodes where members.zipcode=zipcodes.zipcode and members.kind='consumer' and ".$neast." and members.registered_on < '".$year."-".$month."-01 00:00:00' group by county");
while ($rr=mysql_fetch_assoc($result))
{	
	echo $rr['county'];
	echo '&nbsp;';
	echo $rr['count'];
	echo '<br/>';
	$sum=$sum+$rr['count'];
}
echo $sum;
$result = __do_sql("SELECT county,count(DISTINCT pkey) as count from members,zipcodes where members.zipcode=zipcodes.zipcode and members.kind='consumer' and ".$southcent." and members.registered_on < '".$year."-".$month."-01 00:00:00' group by county");
while ($rr=mysql_fetch_assoc($result))
{	
	echo $rr['county'];
	echo '&nbsp;';
	echo $rr['count'];
	echo '<br/>';
	$sum=$sum+$rr['count'];
}
echo $sum;
$result = __do_sql("SELECT county,count(DISTINCT pkey) as count from members,zipcodes where members.zipcode=zipcodes.zipcode and members.kind='consumer' and ".$southe." and members.registered_on < '".$year."-".$month."-01 00:00:00' group by county");
while ($rr=mysql_fetch_assoc($result))
{	
	echo $rr['county'];
	echo '&nbsp;';
	echo $rr['count'];
	echo '<br/>';
	$sum=$sum+$rr['count'];
}
echo $sum;
$result = __do_sql("SELECT county,count(DISTINCT pkey) as count from members,zipcodes where members.zipcode=zipcodes.zipcode and members.kind='consumer' and ".$westt." and members.registered_on < '".$year."-".$month."-01 00:00:00' group by county");
while ($rr=mysql_fetch_assoc($result))
{	
	echo $rr['county'];
	echo '&nbsp;';
	echo $rr['count'];
	echo '<br/>';
	$sum=$sum+$rr['count'];
}
echo $sum;
$result = __do_sql("SELECT county,count(DISTINCT pkey) as count from members,zipcodes where members.zipcode=zipcodes.zipcode and members.kind='consumer' and ".$westc." and members.registered_on < '".$year."-".$month."-01 00:00:00' group by county");
while ($rr=mysql_fetch_assoc($result))
{	
	echo $rr['county'];
	echo '&nbsp;';
	echo $rr['count'];
	echo '<br/>';
	$sum=$sum+$rr['count'];
}
echo $sum;*/
?>


