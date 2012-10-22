<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../img/favicon.ico" />
	<title>The 10% Campaign Admin: County Coordinators</title>
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
	if (!__is_admin_user()) { echo 'Access denied'; exit; }
?>
<body bgcolor="#F8F8F8">

<p><a href="index.php">&laquo; Return to Admin Home</a></p>

<h2 class="blue">County Coordinators Email List Synchronizer</h2>
<p>
You can send email to <a href="mailto:nc10100@lists.ncsu.edu">nc10100@lists.ncsu.edu</a> to reach the all of the county coordinators.
<br /><br />
This page synchronizes that mailing list with the entries in <a href="coordinator_list.php">county coordinators</a>.</p>
<p>
<?php
	$listname = 'nc10100'; // nc10100@lists.ncsu.edu
	$password = '2UpDateLocalFoods';
	$content = '';
	$content .= "approve $password unsubscribe-noinform-pattern-allmatching $listname %*%\n";
	$content .= "default list $listname\n";
	$content .= "default password $password\n";
	
	//$result = __do_db_sql(DATABASE_NAME_XEMP, "select distinct email from nc10percent nc, employees e where nc.emp_id = e.emp_id");
	//while ($r = mysql_fetch_assoc($result)) {
	$result = __ncce_request('coordinators_email', '', 'xemp');
	foreach ($result as $k => $r) {
		$content .= 'subscribe-nowelcome-noinform '.$r['email']."\n";
	}
	
    $to = "mj2@lists.ncsu.edu, joe_zobkiw@ncsu.edu"; //"mj2@lists.ncsu.edu";
    $subject = "Automated Update of $listname List";
    $headers = "From: joe_zobkiw@ncsu.edu\r\n";
//  $headers = "From: nc10percent@ncsu.edu\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    $parameters = "-fnc10percent@ncsu.edu";

    $result = mail($to, $subject, $content, $headers, $parameters);
	echo $result ? '<p style="color:green;">The command to the list server to synchronize has been sent.<br />Please give the list approximately 5 minutes to update before sending a message to it.</p>' : '<p style="color:red;">Error synchronizing list. Please try again later.</p>';
?>
</p>
</body>
</html>
