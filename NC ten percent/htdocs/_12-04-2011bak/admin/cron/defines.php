<?php
	// COOKIE
	define("EMAIL_COOKIE", "EMAIL_COOKIE");
	define("ZIP_COOKIE", "ZIP_COOKIE");
	define("COOKIE_EXPIRE", (60*60*24*365*10)); // 10 years
	
	// MAGIC
	define("MAGIC_KEY", "ds&dh27dqkmcGzbASH10($@;[.,asdhi");
	
	// MAIL
	define("EMAIL_ADDRESS", "nc10percent@ncsu.edu");
	define("ADDITIONAL_MAIL_HEADERS", "MIME-Version: 1.0"."\r\n"."Content-type: text/html; charset=iso-8859-1"."\r\n"."From: ".EMAIL_ADDRESS."\r\n"."CC: ".EMAIL_ADDRESS."\r\n"."BCC: jmzobkiw@ncsu.edu\r\n"."Reply-To: ".EMAIL_ADDRESS);
	define("ADDITIONAL_MAIL_HEADERS_MULTIPART", "MIME-Version: 1.0"."\r\n"."Content-type: multipart/alternative; boundary=\"mime_boundary\""."\r\n"."From: ".EMAIL_ADDRESS."\r\n"."CC: ".EMAIL_ADDRESS."\r\n"."BCC: jmzobkiw@ncsu.edu\r\n"."Reply-To: ".EMAIL_ADDRESS);
	define("ADDITIONAL_MAIL_PARAMETERS", "-f".EMAIL_ADDRESS);
	
	// URLS
	/*
	define("ACTIVATION_URL", 'http://nc10percent.com/activate.php');
	define("UNSUBSCRIBE_URL", 'http://nc10percent.com/unsubscribe.php');
	define("WEEKLY_URL", 'http://nc10percent.com/weekly.php');
	*/
	
	// DEBUG URLS - REMOVE AFTER DEBUGGING and comment in above
	define("ACTIVATION_URL", 'http://nc10percent.com/activate.php');
	define("UNSUBSCRIBE_URL", 'http://nc10percent.com/unsubscribe.php');
	define("WEEKLY_URL", 'http://nc10percent.com/weekly.php');

	// FACEBOOK
	define("FACEBOOK_USER_NAME", 'nc10percent');
	define("FACEBOOK_USER_URL", 'http://facebook.com/nc10percent');
	
	// TWITTER
	define("TWITTER_USER_NAME", 'nc10percent');
	define("TWITTER_USER_URL", 'http://twitter.com/nc10percent');
	
	// CEFS FEED
	define("CEFS_RSS_FEED", 'http://www.cefs.ncsu.edu/cefsnews.xml');
	
	// DATABASES
	define('DATABASE_NAME', 'cefs_10per');	
	define('DATABASE_HOST', 'mysql01.unity.ncsu.edu');	
	define('DATABASE_USER_NAME', 'cefs_10per_admin');
	define('DATABASE_USER_PASSWORD', 'ce013111JL');
?>
