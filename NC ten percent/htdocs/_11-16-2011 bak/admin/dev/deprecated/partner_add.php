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

<h2 class="blue">Add a Partner</h2>

<?
if ($_POST['mode'] == '') {
?>
<form action="partner_add.php" method="post" accept-charset="utf-8">
	<input type="hidden" name="mode" value="add" id="mode">
	
	<table style="border: solid 1px gray; background-color: #eee; font-size: smaller;" border="0" cellspacing="3" cellpadding="3">
		
		<tr valign="top"><td>Campaign support type(s)</td>
		<td><? __create_checkbox_options(__get_list_type("SUPPORT"), "support_type", $support_type);?></td></tr>

		<tr><td>Name</td>
		<td><input type="text" name="name" value="<?=$name;?>" id="name" size="50"></td></tr>

		<tr><td>URL (include http://)</td>
		<td><input type="text" name="url" value="<?=$url;?>" id="url" size="50"></td></tr>

		<tr><td>Description</td>
		<td><textarea name="description" rows="8" cols="50"></textarea></td></tr>
	</table>
	
	<p><input type="submit" value="Add Partner"></p>
</form>
<?
} else if ($_POST['mode'] == 'add') {
	$name = mysql_real_escape_string($_POST['name']);
	$url = mysql_real_escape_string($_POST['url']);
	$description = mysql_real_escape_string($_POST['description']);
	$support_type = $_POST['support_type'];
	$support_type_string = '';
	foreach($support_type as $k => $v) {
		if ($support_type_string)
			$support_type_string .= '|';
		$support_type_string .= mysql_real_escape_string($v);
	}
	
	$result = __do_sql("insert into partners (name, url, description, support_type) values ('$name', '$url', '$description', '$support_type_string')");
	if ($result && mysql_affected_rows() == 1) {
		echo '<p style="color:green;">Successfully added ';
	} else {
		echo '<p style="color:red;">There was an error ('.mysql_error().'/'.mysql_errno().') adding the partner</p>';
	}
	echo '<p><a href="partner_add.php">Add a Partner</a><br /><span style="font-size:smaller;">Need to add another partner?</span></p>';
}

echo '<p><a href="partner_list.php">&laquo; Return to List</a></p>';
?>
</body>
</html>
