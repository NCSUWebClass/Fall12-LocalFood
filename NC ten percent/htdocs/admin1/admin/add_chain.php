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
	if ($_POST['mode']=='')
	{
?>
<body bgcolor="#F8F8F8">
<h3 class="blue"> Add a new business chain </h3>
<form action="add_chain.php" method="post" accept-charset="utf-8">
<input type="hidden" name="mode" value="save" id="mode">
<table style="border: solid 1px gray; background-color: #eee; font-size: smaller;" border="0" cellspacing="3" cellpadding="3">
<tr><td>Name of the chain </td><td><input type="text" name="cname" id="cname" size="50"></td></tr>
<?php 
$res=__do_sql("SELECT MAX(cnum) as max from business_chain");
while ($rr=mysql_fetch_row($res))
{
	$num=$rr[0];
	$num=$num+1;
}
?>
<tr><td>Chain number</td><td><input type="text" name="cnum" id="cnum" readonly="True" value=<?php echo $num;?>></td></tr>
<tr><td>Primary contact </td><td><input type="text" name="email" id="email" size="80"></td></tr>
<tr><td><input type="submit" value="add chain"/></td></tr>
</table>
<br/>
<a href="index.php">Back to admin home</a>
</form>
<?php }
if ($_POST['mode']=='save')
{
	$cname = mysql_real_escape_string($_POST['cname']);
	$cnum = mysql_real_escape_string($_POST['cnum']);
	$email = mysql_real_escape_string($_POST['email']);
	
	$ress=__do_sql ("insert into business_chain values (".$cnum.",'".$cname."','".$email."')");
	//echo "insert into business_chain values (".$cnum.",'".$cname."','".$email."')";
	if ($ress && ((mysql_affected_rows() == 1) || (mysql_affected_rows() == 0 && mysql_errno() == 0))) {
			echo '<p style="color:green;">New business added</p>';
		} else {
			echo '<p style="color:red;">There was an error ('.mysql_error().'/'.mysql_errno().') saving changes to extra data</p>';
		}
	echo '<br/><a href="index.php">Back to admin home</a>';
} ?>
</body>			
</html>
