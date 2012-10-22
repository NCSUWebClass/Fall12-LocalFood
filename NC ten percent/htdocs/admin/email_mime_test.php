<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../img/favicon.ico" />
	<title>The 10% Campaign Admin: Emailer</title>
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

<h2 class="blue">Emailer</h2>
<p>
This page sends a test email in MIME format</p>
<p>
<?php

// START -------------------------------------------------------------------
$filename = '/afs/unity.ncsu.edu/web/n/nc10percent/htdocs/email_templates/weekly_email_template_mime.txt';
$handle = fopen($filename, 'r');
if ($handle) {
	$contents = fread($handle, filesize($filename));
	fclose($handle);

	$contents = str_replace("[WEEKLY_LINK]", "http://weekly.com", $contents);
	$contents = str_replace("[UNSUBSCRIBE_LINK]", "http://unsubscribe.com", $contents);

	$subject = "TESTING MIME - PLEASE IGNORE";
	define("EMAIL_ADDRESS_TEST", "zobkiw.ncsu@gmail.com");
	define("ADDITIONAL_MAIL_HEADERS_MULTIPART", "MIME-Version: 1.0"."\r\n"."Content-type: multipart/alternative; boundary=\"mime_boundary\""."\r\n"."From: ".EMAIL_ADDRESS_TEST."\r\n"."CC: ".EMAIL_ADDRESS_TEST."\r\n"."BCC: zobkiw.ncsu@gmail.com\r\n"."Reply-To: ".EMAIL_ADDRESS_TEST);
	$additional_headers = ADDITIONAL_MAIL_HEADERS_MULTIPART;
	$additional_parameters = ADDITIONAL_MAIL_PARAMETERS;
	mail(EMAIL_ADDRESS_TEST, $subject, $contents, $additional_headers, $additional_parameters);

	// COMPLETED -------------------------------------------------------------------
	echo "COMPLETED<br />";
} else echo "No file handle<br />";

/*
define("ADDITIONAL_MAIL_HEADERS_MULTIPART", "MIME-Version: 1.0"."\r\n"."Content-type: text/html; charset=iso-8859-1"."\r\n"."From: ".EMAIL_ADDRESS_TEST."\r\n"."CC: ".EMAIL_ADDRESS_TEST."\r\n"."BCC: zobkiw.ncsu@gmail.com\r\n"."Reply-To: ".EMAIL_ADDRESS_TEST);


MIME-Version: 1.0
Content-Type: multipart/mixed; boundary="mime_boundary"

This is a message with multiple parts in MIME format.
--mime_boundary
Content-Type: text/plain

--mime_boundary
Content-type: text/html; charset=iso-8859-1

--mime_boundary--
*/

?>
</p>
</body>
</html>

