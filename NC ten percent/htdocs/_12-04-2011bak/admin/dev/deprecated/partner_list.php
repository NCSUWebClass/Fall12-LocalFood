<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../img/favicon.ico" />
	<title>The 10% Campaign Admin: Partners</title>
	<style type="text/css" media="screen">
		body { font: 100% "Trebuchet MS", sans-serif;line-height: 1.2em; }
		a:link {color:#052d5d;}
		a:visited { color:#11335b;}
		a:hover {color:#0156bc;}
		.blue { color: #5D7EA3; }
		.gray { color: gray; }
	</style>
</head>
<?
	require_once('../defines.php');
	require_once('../db.php');
	require_once('../utilities.php');
	__get_table_count('members'); // We need to do this so mysql_real_escape_string works properly
	if (!__is_admin_user()) { echo 'Access denied'; exit; }
?>
<body bgcolor="#F8F8F8">

<p><a href="index.php">&laquo; Return to Admin Home</a></p>

<h2 class="blue">Partners</h2>

<p>
	<a href="partner_add.php">Add a Partner</a><br /><span style="font-size:smaller;">Add a new partner to the list</span>
</p>
	
<p class="gray">
<table style="border: solid 1px gray; background-color: #eee; font-size: smaller;" border="0" cellspacing="3" cellpadding="3">
<?
	$result = __do_sql("select * from partners order by name");
	echo '<tr><td colspan="3">'.mysql_num_rows($result).' found</td></tr>';
	echo '<tr valign="top" style="font-weight:bold;"><td>Support Type(s)</td><td>Name</td><td>URL</td><td>Description</td></tr>';
	while ($r = mysql_fetch_assoc($result)) {
		echo '<tr valign="top"><td>'.$r['support_type'].'</td><td>'.$r['name'].'</td><td>'.$r['url'].'</td><td>'.$r['description'].'</td><td><a href="partner_edit.php?p='.$r['pkey'].'">Edit</a></td><td><a href="partner_delete.php?p='.$r['pkey'].'">Delete</a></td></tr>';
	}
?>	
</table>
</body>
</html>
