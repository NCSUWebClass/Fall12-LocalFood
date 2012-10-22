<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../img/favicon.ico" />
	<title>The 10% Campaign Admin: Business Editor</title>
	<style type="text/css" media="screen">
		body { font: 100% "Trebuchet MS", sans-serif;line-height: 1.2em; }
		a:link {color:#052d5d;}
		a:visited { color:#11335b;}
		a:hover {color:#0156bc;}
		.blue { color: #5D7EA3; }
		.gray { color: gray; }
	</style>
    <link type="text/css" href="../css/ui-lightness/jquery-ui-1.8.custom.css" rel="stylesheet" />	
    <script type="text/javascript" src="../js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../js/jquery-ui-1.8.custom.min.js"></script>
	<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="../js/additional-methods.js"></script>
	<script type="text/javascript">
	jQuery.validator.setDefaults({
		debug: true,
		success: "valid"
	});;
	</script>
</head>
<?php
	require_once('../defines.php');
	require_once('../db.php');
	require_once('../ncce_connect.php');
	require_once('../utilities.php');
	__get_table_count('members'); // We need to do this so mysql_real_escape_string works properly
	if (!__is_lfc() && !__is_admin_user()) { echo 'Access denied'; exit; }
?>
<body bgcolor="#F8F8F8">
<?php
$pkey = mysql_real_escape_string($_GET['p']);
if ($_POST['mode'] == '')
{
$bname = __get_member_field($pkey, 'bname');
$number = __get_business_record_number($pkey);
if ($number>0)
{
$tfood = __get_business_data_field($pkey,'total_food');
}
else
{
	$tfood=0;
}
if ($number>0)
{
$notes = __get_business_data_field($pkey,'notes');
}
else
{
	$notes='';
}
echo '<a href="business_edit.php?p='.$pkey.'"> &laquo; Return to business </a>';
?>
<h3 class="blue"> Add a business data record for <?php echo $bname ?> </h3>
<form action="edit_record.php?p=<?php echo $pkey;?>" method="post" accept-charset="utf-8">
<input type="hidden" name="mode" value="save" id="mode">
<input type="hidden" name="pkey" value="<?php echo $pkey;?>" id="pkey">
<table style="border: solid 1px gray; background-color: #eee; font-size: smaller;" border="0" cellspacing="3" cellpadding="3">
<tr><td>Input the date </td> <td><?php __create_select_options(__get_list_type("MONTH"), "month", false);?> &nbsp; <?php __create_select_options(__get_list_type("DATE"), "date", false);?>&nbsp;<?php __create_select_options(__get_list_type("YEAR"), "year", false);?></td></tr>
<tr><td>Total amount so far</td><td><?php echo '$ '.$tfood;?></td></tr>
<tr><td>Enter the amount for the period</td><td><input class="digits" type="text" name="local_food" value="" id="local_food" size="10"></td></tr>
<tr valign="top"><td>Notes</td>
<td><textarea name="notes" rows="8" cols="40"><?php echo $notes;?></textarea></td></tr>
<br/>
<tr><td><input type="submit" value="add record"></td></tr>
</table>
</form>
<br/>
<a href=history.php?p=<?php echo $pkey;?>> &laquo; Back to History </a>
<?php } else if ($_POST['mode']=='save')
{	$pkey = mysql_real_escape_string($_POST['pkey']);
	$month=mysql_real_escape_string($_POST['month']);
	$day=mysql_real_escape_string($_POST['date']);
	$year=mysql_real_escape_string($_POST['year']);
	$tstamp=$year."-".$month."-".$day." 00:00:00";
	$lfood=$_POST['local_food'];
	$calc_freq = mysql_real_escape_string($_POST['calc_freq']);
	$notes2=mysql_real_escape_string($_POST['notes']);
	$sum=0;
	$max="0000-00-00 00:00:00";
	$res2=__do_sql("select data_entered_on from business_data where fkey=".$pkey);
	while ($rr2=mysql_fetch_assoc($res2))
	{
		if ($rr2['data_entered_on']>$max)
		{
			$max=$rr2['data_entered_on'];
		}
	}
	//echo 'Max is';
	//echo $max;
	
	if ($tstamp>$max)
	{
	$res=__do_sql("select local_food from business_data where fkey=".$pkey);
	//echo 'Greater';
	}
	else
	{
	$res=__do_sql("select local_food from business_data where fkey=".$pkey." and data_entered_on<'".$tstamp."'");
	//echo 'lesser';
	}
	while ($rr=mysql_fetch_assoc($res))
	{
		$sum=$sum+$rr['local_food'];
		//echo 'sum is';
		//echo $sum;
	}
	//echo 'sum before';
	//echo $sum;
	$sum=$sum+$lfood;
	//echo 'sum after';
	//echo $sum;
      __do_sql("insert into business_data (fkey,data_entered_on,data_entered_by, last_edited_on, last_edited_by, calc_freq, total_food, local_food, notes) 
			values ('$pkey', '$tstamp', '".__current_user_unity_id()."', Now() , '".__current_user_unity_id()."', '$calc_freq', '$sum', '$lfood', '$notes2')");
	if ($tstamp<$max)
	{
		$res=__do_sql("select pkey from business_data where fkey=".$pkey." and data_entered_on>'".$tstamp."'");
		while ($rr=mysql_fetch_assoc($res))
		{
			__do_sql("update business_data set total_food=total_food+".$lfood." where pkey=".$rr['pkey']);
		}
	}		
	echo 'Record inserted <br/>';
	echo '<a href=edit_record.php?p='.$pkey.'> &laquo;Go back to record insertion</a>';	
	}?>
