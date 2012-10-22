<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../img/favicon.ico" />
	<title>The 10% Campaign Admin: Business</title>
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

<h2 class="blue">Delete a Business</h2>

<?php
$pkey = mysql_real_escape_string($_GET['p']);

if ($pkey == '') {
	echo 'Invalid parameters';
} else if ($_GET['mode'] == '') {
	
	echo 'Are you sure you want to delete '.__get_member_field($pkey, 'bname').' as a business? ALL REPORTED DATA WILL ALSO BE DELETED!<br /><br />';
	
	echo '<a style="background-color:#F55;" href="business_delete.php?mode=ok&amp;p='.$pkey.'">Yes, Delete '.__get_member_field($pkey, 'bname').'</a><br /><br />';
		
} else if ($_GET['mode'] == 'ok') {
	
	$result = __do_sql("delete from members where pkey = '$pkey' LIMIT 1");
	if ($result && mysql_affected_rows() == 1) {
		echo '<p style="color:green;">Successfully deleted ';
		
		$result = __do_sql("delete from business_data where fkey = '$pkey' LIMIT 1");
		$result = __do_sql("delete from business_partner where fkey = '$pkey'");
		$result = __do_sql("delete from restaurant_data_initial where fkey = '$pkey'");
		$result = __do_sql("delete from restaurant_data_weekly where fkey = '$pkey'");
		
	} else {
		echo '<p style="color:red;">There was an error ('.mysql_error().'/'.mysql_errno().') deleting the business</p>';
	}
	
	echo '<p><a href="business_add.php">Add a Business</a><br /><span style="font-size:smaller;">Need to add a business to replace this one?</span></p>';
	
}

echo '<p><a href="business_list.php">&laquo; Return to List</a></p>';

?>
</body>
</html>
