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
	<input type="submit" value="Show">
</form>
</p>

<p class="gray">
<table style="border: solid 1px gray; background-color: #eee; font-size: smaller;" border="0" cellspacing="3" cellpadding="3">
<?php
	if ($county) {
		$result = __do_sql("select * from members where kind = 'business'  and zipcode in (select zipcode from zipcodes where county = '".strtoupper($county)."') order by registered_on desc");
	} else {
		$result = __do_sql("select * from members where kind = 'business' order by registered_on desc");
	}
	echo '<tr><td colspan="10">'.mysql_num_rows($result).' found </td></tr>';
	echo '<tr style="font-weight:bold;">
	<td>Name</td>
	<td>Type</td>
	<td>Zip</td>
	<td>County</td>
	<td>Contact</td>
	<td>Phone</td>
	<td>Registered</td>
	<td>Active</td>
	<td>Opted Out</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>';
	while ($r = mysql_fetch_assoc($result)) {
		$county = __get_zipcode_county($r['zipcode']);
		echo '<tr><td>'.$r['bname'].'</td>
		<td>'.__get_list_description('BIZORG', $r['btype']).'</td>
		<td>'.$r['zipcode'].'</td>
		<td><a href="business_list.php?county='.$county.'">'.$county.'</a></td>
		<td><a href="mailto:'.$r['email'].'">'.$r['name'].'</a></td>
		<td>'.$r['bphone'].'</td>
		<td>'.$r['registered_on'].'</td>
		<td style="color:'.($r['active']=="Y"?"green":"red").';">'.$r['active'].'</td>
		<td style="color:red;">'.str_replace('0000-00-00 00:00:00', '', $r['opted_out_on']).'</td>
		<td><a href="business_edit.php?p='.$r['pkey'].'">Edit</a></td>
		<td><a href="business_delete.php?p='.$r['pkey'].'">Delete</a></td>';
		echo '</tr>';
	}
?>	
</table>
</body>
</html>
