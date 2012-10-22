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
	<script type='text/javascript'>
/*function validate()
{
	var chosen = "";
	var len = document.getElementByName(group1).length;
	alert ("The length is" + len);
	for (var i=0;i<len;i++)
	{
		chosen = document.f1.group1[i].value;
	}
	alert (chosen);
	if (chosen=="")
	{
		var y = confirm ("You have not selected any entries. Are you sure you want to continue?");
		if (y==false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	else
	{
		return true;
	}
}*/
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
$local_food = __get_business_data_field($pkey,'local_food');
echo '<a href="business_edit.php?p='.$pkey.'"> &laquo; Return to business </a>'; ?>
<h3 class="blue"> The history for <?php echo $bname ?> </h3>
<table style="border: solid 1px gray; background-color: #eee; font-size: smaller;" border="0" cellspacing="3" cellpadding="3">

	<tr>
		<th> Date Entered </th>
		<th> Entered by</th>
		<th> Edited on</th>
		<th> Edited by </th>
		<th> Cumulative amount till date</th>
		<th> Amount during the period</th>
	</tr>
<form action="history.php?p=<?php echo $pkey;?>" name ="f1" method="post" accept-charset="utf-8">
<input type="hidden" name="mode" value="save" id="mode">
<input type="hidden" name="pkey" value="<?php echo $pkey;?>" id="pkey">
<?php
$res=__do_sql("select * from business_data where fkey='$pkey' ORDER BY data_entered_on");
while ($rr = mysql_fetch_assoc($res))
{
	echo '<tr>';
	echo '<td>';
	echo '<input type="radio" name="group1" id="group1" value="'.$rr['pkey'].'">';
	echo $rr['data_entered_on'];
	echo '</td>';
	echo '<td>';
	echo $rr['data_entered_by'];
	echo '</td>';
	echo '<td>';
	echo $rr['last_edited_on'];
	echo '</td>';
	echo '<td>';
	echo $rr['last_edited_by'];
	echo '</td>';
	echo '<td>';
	echo '$ ';
	echo $rr['total_food'];
	echo '</td>';
	echo '<td>';
	echo '$ ';
	echo $rr['local_food'];
	echo '</td>';
	echo '</tr>';	
}
?>
</table>
<br/><br/>
<h3 class="blue"> Edit the selected record</h3>
<table style="border: solid 1px gray; background-color: #eee; font-size: smaller;" border="0" cellspacing="3" cellpadding="3">
<tr><td>Enter the new value</td>
<td><input class="digits" type="text" name="local_food1" value="" id="local_food1" size="10"></td></tr>
<tr><td><input type="checkbox" name="delete" value="del" id="delete"/> Delete the record </td></tr>
<tr> <td></td></tr>
</table>
<br/><br/>

<p><input type="submit" value="save"></p>
</form>
<br/>
<a href="export_history.php?p=<?php echo $pkey;?>"> Download history in CSV format </a>
<br/>
<a href="edit_record1.php?p=<?php echo $pkey;?>">Add old records</a>
<?php } else if ($_POST['mode'] == 'save')
{	$pkey = mysql_real_escape_string($_POST['pkey']);
	if (!__is_edit_user())
{
	echo 'You do not have permission to edit';
	echo '<br/>';
	echo '<a href="history.php?p='.$pkey.'">&laquo; Back to history </a>';
}
else{
	$chkpkey = $_POST['group1'];
	$res = __do_sql ("select local_food,total_food,data_entered_on from business_data where pkey=".$chkpkey);
	while ($rr=mysql_fetch_assoc($res))
	{
		$oldlocal=$rr['local_food'];
		$oldtotal=$rr['total_food'];
		$dataon=$rr['data_entered_on'];
	}
	$newlocal=$_POST['local_food1'];
	
	if ($_POST['delete'] == 'del')
	{
		$difference= -$oldlocal;
	}
	else
	{
	$difference=$newlocal-$oldlocal;
	}
	$arr=array();
	$arr1=array();
	$count=0;
	//echo 'key is';
	//echo $pkey;
	$res1=__do_sql("select pkey,data_entered_on from business_data where fkey=".$pkey." order by data_entered_on");
	while ($rr=mysql_fetch_assoc($res1))
	{
		$arr[]=$rr['pkey'];
		$arr1[]=$rr['data_entered_on'];
		$count++;
	}
	//echo 'Count is';
	//echo $count;
	if ($_POST['delete']=='del')
	{
		$arr=array();
		$result = __do_sql("select pkey,data_entered_on from business_data where fkey=".$pkey." order by data_entered_on");
		while ($res=mysql_fetch_assoc($result))
		{
			$arr[]=$res['pkey'];
			$arr1[]=$res['data_entered_on'];
		}
		$num=count($arr);
		if ($chkpkey==$arr[$num-1])
		{	
			$res= __do_sql("select notes from business_data where pkey=".$chkpkey);
			while ($rr=mysql_fetch_assoc($res))
			{
				$notess=mysql_real_escape_string($rr['notes']);
			}
			$ekey = $arr[$num-2];
			$res=__do_sql("update business_data set notes ='".$notess."' where pkey=".$ekey);
			__do_sql("delete from business_data where pkey=".$chkpkey);
		}
		else
		{
		__do_sql("delete from business_data where pkey=".$chkpkey);
	}
	}
	else
	{
	__do_sql("update business_data set local_food=".$newlocal." where pkey=".$chkpkey);
	$newtotal=$oldtotal+$difference;
	__do_sql("update business_data set total_food=".$newtotal." where pkey=".$chkpkey);
	__do_sql("update business_data set last_edited_by=".__current_user_unity_id()." where pkey=".$chkpkey);
	__do_sql("update business_data set last_edited_on= Now() where pkey=".$chkpkey);
	}
	
	for ($k=0;$k<$count;$k++)
	{
		//echo 'Entered for loop';
		echo 'count is';
		echo $count;
		echo 'pkey is';
		echo $arr[$k];
		echo 'corresponding date is';
		echo $arr1[$k];
		if ($arr1[$k]>$dataon)
		{
		 echo 'updating record';
		 $result=__do_sql("select total_food from business_data where pkey=".$arr[$k]);
	     while ($rr=mysql_fetch_assoc($result))
	     {
			 $oldtot=$rr['total_food'];
			 echo 'old total is';
			 echo $oldtot;
		 }
		 echo 'difference is';
		 echo $difference;
		$newtot=$oldtot+$difference;
		echo 'new total is';
		echo $newtot;
		$numm=__do_sql("update business_data set total_food=".$newtot." where pkey=".$arr[$k]);
		echo 'number of rows affected is';
		echo $numm;
		} 
	}
	echo 'Changes saved';
	echo '<br/>';
	echo '<a href="history.php?p='.$pkey.'">&laquo; Back to history </a>';
}
}
?>
</body>
</html>
