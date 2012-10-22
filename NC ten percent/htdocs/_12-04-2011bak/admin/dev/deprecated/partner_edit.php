<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../img/favicon.ico" />
	<title>The 10% Campaign Admin: Partner Editor</title>
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
<?
	require_once('../defines.php');
	require_once('../db.php');
	require_once('../utilities.php');
	__get_table_count('members'); // We need to do this so mysql_real_escape_string works properly
?>
<body bgcolor="#F8F8F8">

<script type="text/javascript" charset="utf-8">

function calculate_percent()
{
	var t = $('#total_food').val();
	var p = $('#percent').val();
	var l = t * (p/100);
	$('#local_food').val(l);
}
		
</script>

<p><a href="index.php">&laquo; Return to Admin Home</a></p>

<h2 class="blue">Partner Editor</h2>

<?
$pkey = mysql_real_escape_string($_GET['p']);
if ($_POST['mode'] == '') {
	$name = __get_partner_field($pkey, 'name');
	$url = __get_partner_field($pkey, 'url');
	$description = __get_partner_field($pkey, 'description');
	$support_type = __get_partner_field($pkey, 'support_type');

	?>
	<form action="partner_edit.php" method="post" accept-charset="utf-8">
		<input type="hidden" name="mode" value="save" id="mode">
		<input type="hidden" name="pkey" value="<?=$pkey;?>" id="pkey">
		
		<table style="border: solid 1px gray; background-color: #eee; font-size: smaller;" border="0" cellspacing="3" cellpadding="3">
			<tr valign="top"><td>Campaign support type(s)</td>
			<td><? __create_checkbox_options(__get_list_type("SUPPORT"), "support_type", $support_type);?></td></tr>

			<tr><td>Name</td>
			<td><input type="text" name="name" value="<?=$name;?>" id="name" size="50"></td></tr>

			<tr><td>URL (include http://)</td>
			<td><input type="text" name="url" value="<?=$url;?>" id="url" size="50"></td></tr>

			<tr><td>Description</td>
			<td><textarea name="description" rows="8" cols="50"><?=$description;?></textarea></td></tr>
		</table>
		
		<p><input type="submit" value="Save Changes"></p>
	</form>
	<?
} else if ($_POST['mode'] == 'save') {
	
	$pkey = mysql_real_escape_string($_POST['pkey']);
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
	
	//echo "update partners set name = '$name', url = '$url', description = '$description', support_type = '$support_type_string' where pkey = '$pkey' LIMIT 1";
	$result = __do_sql("update partners set name = '$name', url = '$url', description = '$description', support_type = '$support_type_string' where pkey = '$pkey' LIMIT 1");
	if ($result && ((mysql_affected_rows() == 1) || (mysql_affected_rows() == 0 && mysql_errno() == 0))) {
		echo '<p style="color:green;">Changes Saved!</p>';
	} else {
		echo '<p style="color:red;">There was an error ('.mysql_error().'/'.mysql_errno().') saving changes to the partner</p>';
	}

	echo '<p><a href="partner_edit.php?p='.$pkey.'">&laquo; Return to Partner</a><br /></p>';
		
} else echo '<p>An error occurred.</p>';

echo '<p><a href="partner_list.php">&laquo; Return to List</a></p>';
?>
</body>
</html>
