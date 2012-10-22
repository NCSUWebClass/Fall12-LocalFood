#!/loc/PHP/bin/php -q
<?php

require_once("defines.php");
require_once("db.php");
require_once("utilities.php");
__get_table_count('members'); // We need to do this so mysql_real_escape_string works properly

// SETTINGS -------------------------------------------------------------------
$sleep_seconds = 1; // seconds to sleep between emails
$interval = "interval 6 day"; // do not send emails any more often than this no matter how many times this script runs (default=6 day)
function __br($newline = true) { echo $newline ? "\n" : "<br />"; }

// START -------------------------------------------------------------------
echo "SENDING EMAILS (".date(DATE_RSS).")"; __br();
echo "   $interval / sleep $sleep_seconds second"; __br();

// CONSUMERS -------------------------------------------------------------------
echo "CONSUMER EMAILS"; __br();
$sn = 0; $en = 0;
$sql = "select * from members where kind = 'consumer' and active = 'Y' and opted_out = 'N'";
$result = __do_sql($sql);
while ($r = mysql_fetch_assoc($result)) {
	$pkey = $r['pkey'];
	$sql2 = "select * from consumer_data_weekly where fkey = '$pkey' and (emailed_on > (date_sub(now(), $interval))) order by emailed_on desc";
	$result2 = __do_sql($sql2);
	if (mysql_num_rows($result2) == 0) {
		// print_r($r);
		$success = __send_weekly_consumer_email($pkey);
		if ($success == true) {
			$sn++;
			echo "   SUCCESS sending CONSUMER email to $pkey"; __br();
		} else {
			$en++;
			echo "   ERROR sending CONSUMER email to $pkey"; __br();
		}
		sleep($sleep_seconds);
	}
}
echo "   SUCCESS: $sn / ERROR: $en"; __br();

// RESTAURANTS -------------------------------------------------------------------
echo "RESTAURANT EMAILS"; __br();
$sn = 0; $en = 0;
$sql = "select * from members where kind = 'business' and btype = 'RESTAURANT' and active = 'Y' and opted_out = 'N'";
$result = __do_sql($sql);
while ($r = mysql_fetch_assoc($result)) {
	$pkey = $r['pkey'];
	$sql2 = "select * from restaurant_data_weekly where fkey = '$pkey' and (emailed_on > (date_sub(now(), $interval))) order by emailed_on desc";
	$result2 = __do_sql($sql2);
	if (mysql_num_rows($result2) == 0) {
		// print_r($r);
		$success = __send_weekly_restaurant_email($pkey);
		if ($success == true) {
			$sn++;
			echo "   SUCCESS sending RESTAURANT email to $pkey"; __br();
		} else {
			$en++;
			echo "   ERROR sending RESTAURANT email to $pkey"; __br();
		}
		sleep($sleep_seconds);
	}
}
echo "   SUCCESS: $sn / ERROR: $en"; __br();

// COMPLETED -------------------------------------------------------------------
echo "COMPLETED (".date(DATE_RSS).")"; __br();
exit;

// FUNCTIONS -------------------------------------------------------------------

function __send_weekly_consumer_email($pkey) 
{
	// Create a record for this week
	$result = __do_sql("insert into consumer_data_weekly (fkey, emailed_on) values ('$pkey', Now())");
	
	if ($result && mysql_affected_rows() == 1) {
		$weekly_pkey = mysql_insert_id();
	} else return false;
	
	$filename = "weekly_email_template_mime.txt";
	$handle = fopen($filename,"r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);

	$contents = str_replace("[WEEKLY_LINK]", __create_weekly_link($pkey, $weekly_pkey), $contents);
	$contents = str_replace("[UNSUBSCRIBE_LINK]", __create_unsubscribe_link($pkey), $contents);
	
//	echo $contents;
//	return true; // temporarly don't send emails during testing
	
	$to = __get_member_field($pkey, 'email');
	if ($to) {
		$subject = "The 10% Campaign Weekly Progress Request";
		$additional_headers = ADDITIONAL_MAIL_HEADERS_MULTIPART;
		$additional_parameters = ADDITIONAL_MAIL_PARAMETERS;
	 	return mail($to, $subject, $contents, $additional_headers, $additional_parameters);
	}
	
	return false;
}

function __send_weekly_restaurant_email($pkey) 
{
	// Create a record for this week
	$result = __do_sql("insert into restaurant_data_weekly (fkey, emailed_on) values ('$pkey', Now())");
	
	if ($result && mysql_affected_rows() == 1) {
		$weekly_pkey = mysql_insert_id();
	} else return false;
	
	$filename = "weekly_email_template_mime.txt";
	$handle = fopen($filename,"r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);

	$contents = str_replace("[WEEKLY_LINK]", __create_weekly_link($pkey, $weekly_pkey), $contents);
	$contents = str_replace("[UNSUBSCRIBE_LINK]", __create_unsubscribe_link($pkey), $contents);
	
//	echo $contents;
//	return true; // temporarly don't send emails during testing
	
	$to = __get_member_field($pkey, 'email');
	if ($to) {
		$subject = "The 10% Campaign Weekly Progress Request";
		$additional_headers = ADDITIONAL_MAIL_HEADERS_MULTIPART;
		$additional_parameters = ADDITIONAL_MAIL_PARAMETERS;
	 	return mail($to, $subject, $contents, $additional_headers, $additional_parameters);
	}
	
	return false;
}

?>