<html>
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
$pkey = $_GET['p'];
$res = __do_sql("SELECT kind,btype from members where pkey=".$pkey);
while ($r=mysql_fetch_assoc($res))
{
$knd = $r['kind'];
$bt = $r['btype'];
}
if ($knd!= 'business')
{
echo 'This is not a business type, an error has occured';
exit;
}

if ($knd=='business' and $bt=='RESTAURANT')
{
echo ' This dashboard can be used only by non-restaurant members. Restaurant members can use our restaurant members dashboard';
echo '<br/>';
echo '<a href=business_list.php>Back to business list</a>';
exit;
}

else
{

echo '<h2 class="blue"> Welcome to your very own Interactive Dashboard! </h2>';
}

?>
<table style="border: solid 1px gray; background-color: #eee; font-size: smaller;" border="0" cellspacing="3" cellpadding="3">
<form action = "business_dashboard.php" method="post" accept-charset="utf-8">
<input type ="hidden" name="mode" value="view0" id="mode">
<input type="hidden" name="pkey" value="<?php echo $pkey;?>" id="pkey">
<tr>
<td><h3 class="blue"> Select an option</h3></td>
</tr>
<tr>
<td><input type="radio" name="group1" value="y" id="group1"> Yearly </td> </tr>
<tr>
<td><input type="radio" name="group1" value="q" id="group1"> Quarterly</td></tr>
<tr><td><input type="radio" name="group1" value="m" id="group1"> Monthly </td></tr>
<tr><td><input type="radio" name="group1" value="w" id="group1"> Weekly </td></tr>
<tr></tr>
<tr><td> Select the date range to view results </td> 
<td><?php echo __create_select_options(__get_list_type("MONTH"), "month", true, $month);?></td>
<td><?php echo __create_select_options(__get_list_type("YEAR"), "year", true, $yr);?></td>
<td><b>TO</b></td>
<td><?php echo __create_select_options(__get_list_type("MONTH"), "month", true, $month1);?></td>
<td><?php echo __create_select_options(__get_list_type("YEAR"), "year", true, $yr1);?></td></tr>
<tr><td><input type="submit" value="view results"></td></tr>
</form>

</table>

<div class="consumer-chart">
<h1>Hi guys!</h1>
</div>
</body>
</html>
