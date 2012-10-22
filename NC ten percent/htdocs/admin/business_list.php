<?php echo $unity_id; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../img/favicon.ico" />
	<title>The 10% Campaign Admin: Business List</title>
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
	if (!__is_lfc() && !__is_admin_user()) { echo 'Access denied'; exit; }
?>
<body bgcolor="#F8F8F8">

<p><a href="index.php">&laquo; Return to Admin Home</a></p>

<h2 class="blue">Businesses</h2>

<?php if (__is_admin_user()) { ?>
<p>
	<a href="business_add.php">Add a Business</a><br /><span style="font-size:smaller;">Add a new business to the list</span>
</p>
<?php } ?>

<p>
	<form action="business_list.php" method="get" accept-charset="utf-8">
		Only display businesses in 
<?php 
	$county = mysql_real_escape_string($_GET['county']);
	__create_county_popup("county", $county, "", true);
?>
		Business Name Search
<?php 
	$bname_search = mysql_real_escape_string($_GET['bname_search']);
	echo "<input type='text' name='bname_search' value='".$bname_search."'></input>"; 
?>		
		Order By
<?php 
	$order_by = mysql_real_escape_string($_GET['order_by']);
		if($order_by == "")
			$order_by = registered_on;
		echo "<select name = 'order_by'>		
		<option value = 'registered_on'"; 
		if($order_by == registered_on) 
			echo "selected='selected'"; 
		echo ">Registered</option>
		<option value = 'bname'";
		if($order_by == bname) 
			echo "selected='selected'"; 
		echo ">Name</option>
		<option value = 'name'"; 
		if($order_by == name) 
			echo "selected='selected'"; 
		echo ">Contact</option>
		<option value = 'btype'";
		if($order_by == btype) 
			echo "selected='selected'"; 
		echo ">Type</option>
		</select>";
?>	
		Order as
<?php 
	$order_as = mysql_real_escape_string($_GET['order_as']);
	if($order_as == "")
		$order_as = desc;	
	echo "<select name = 'order_as'>	
	<option value = 'desc'"; 
	if($order_as == desc) 
		echo "selected='selected'"; 
	echo ">Descending</option>;
	<option value = 'asc'";
	if($order_as == asc)
		echo "selected='selected'"; 
	echo ">Ascending</option>";
?>

<?php
$check=$_GET['pop'];
?>
<input type="checkbox" name="pop[]" id="pop" value="PLP" <?php if (in_array('PLP',$check)) echo 'checked'; ?>> POP
<input type="checkbox" name="pop[]" id="pop" value="PO"  <?php if (in_array('PO',$check)) echo 'checked'; ?>>  Promotional
<input type="checkbox" name="pop[]" id="pop" value="EMP" <?php if (in_array('EMP',$check)) echo 'checked'; ?>> Employee
<?php 
if (!empty($check))
{	
	$checkstring='';
	$count = count($check);
	for ($i=0;$i<$count;$i++)
	{
		if ($i==($count-1))
		$checkstring=$checkstring."support_type='".$check[$i]."'";
		else
		$checkstring=$checkstring."support_type='".$check[$i]."' OR ";
	}	
}
?>
		
	<input type="submit" value="Show">
</form>
</p>

<p class="gray">
<table style="border: solid 1px gray; background-color: #eee; font-size: smaller;" border="0" cellspacing="3" cellpadding="3">
<?php
	if ($county && $bname_search) {
		if (empty($check))
		$result = __do_sql("select * from members where kind = 'business'  and zipcode in (select zipcode from zipcodes where county = '".strtoupper($county)."') and bname LIKE '%$bname_search%' order by $order_by $order_as");
		else
		$result= __do_sql("select * from members where kind = 'business'  and zipcode in (select zipcode from zipcodes where county = '".strtoupper($county)."') and bname LIKE '%$bname_search%' and pkey IN (select fkey from business_partner,members where members.pkey=business_partner.fkey and business_partner.active='Y' and ($checkstring)) order by $order_by $order_as");
	} elseif($county){
		if (empty($check))
		$result = __do_sql("select * from members where kind = 'business'  and zipcode in (select zipcode from zipcodes where county = '".strtoupper($county)."') order by $order_by $order_as");
		else
		$result= __do_sql("select * from members where kind = 'business'  and zipcode in (select zipcode from zipcodes where county = '".strtoupper($county)."') and pkey IN (select fkey from business_partner,members where members.pkey=business_partner.fkey and business_partner.active='Y' and ($checkstring)) order by $order_by $order_as");
	} elseif($bname_search){
		if (empty($check))
		$result = __do_sql("select * from members where kind = 'business' and bname LIKE '%$bname_search%' order by $order_by $order_as"); 
		else
		$result= __do_sql("select * from members where kind = 'business'  and bname LIKE '%$bname_search%' and pkey IN (select fkey from business_partner,members where members.pkey=business_partner.fkey and business_partner.active='Y' and ($checkstring)) order by $order_by $order_as");
	}else{
		if (empty($check))
		$result = __do_sql("select * from members where kind = 'business' order by $order_by $order_as");
		else
		$result = __do_sql("select * from members where kind = 'business' and pkey IN (select fkey from business_partner,members where members.pkey=business_partner.fkey and business_partner.active='Y' and ($checkstring)) order by $order_by $order_as");
	}
	echo '<tr><td colspan="10">'.mysql_num_rows($result).' found </td></tr>';
	echo '<tr style="font-weight:bold;">
	<td>Name</td>
	<td>Type</td>
	<td>Secondary Type</td>
	<td>Support type</td>
	<td>County</td>
	<td>Contact</td>
	<td>Registered</td>
	<td>Initial Contact date</td>
	<td>Active</td>
	<td>Opted Out</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>';
	while ($r = mysql_fetch_assoc($result)) {
		$county = __get_zipcode_county($r['zipcode']);
		echo '<tr><td>'.$r['bname'].'</td>
		<td>'.__get_list_description('BIZORG', $r['btype']).'</td>
		<td>'.__get_list_description('BIZORG', $r['sbtype']).'</td>';
		$string='';
		$suptype=__do_sql("select support_type from business_partner where fkey=".$r['pkey']." AND active='Y'");
		while ($rr=mysql_fetch_assoc($suptype))
		{
			$string=$string.' '.$rr['support_type'];
		}
		echo '<td>'.$string.'</td>';
		echo '<td><a href="business_list.php?county='.$county.'">'.$county.'</a></td>
		<td><a href="mailto:'.$r['email'].'">'.$r['name'].'</a></td>';
		echo '<td>'.$r['registered_on'].'</td><td>';
		if ($r['initial_contact']!='0000-00-00 00:00:00')
		{
			echo $r['initial_contact'];
		}
		
		echo '</td><td style="color:'.($r['active']=="Y"?"green":"red").';">'.$r['active'].'</td>
		<td style="color:red;">'.str_replace('0000-00-00 00:00:00', '', $r['opted_out_on']).'</td>
		<td><a href="business_edit.php?p='.$r['pkey'].'">Edit</a></td>
		<td><a href="business_delete.php?p='.$r['pkey'].'">Delete</a></td>
		<td><a href="business_dashboard.php?p='.$r['pkey'].'">Dashboard</a></td>';
		
		echo '</tr>';
	}
?>	
</table>
</body>
</html>
