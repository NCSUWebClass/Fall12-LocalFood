<?php
/*
	This file contains utility functions that provide an interface to the 
	MySQL database
*/

require_once("defines.php");

// -----------------------------------------------------------------
// Execute the given SQL and report an error if necc 
// - called when we want to access phoenix/phoenix_dev
// -----------------------------------------------------------------
function __do_sql($sql, $read_only = false, $ignore_error = 0)
{
	return __do_db_sql(__get_db_name(), $sql, $read_only, $ignore_error);
}

// -----------------------------------------------------------------
// Execute sql on the given db, assuming access by abovementioned user id
// - called when we want to access ANY database, see site_config.php __get_db_domain_name()
// -----------------------------------------------------------------
function __do_db_sql($db_name, $sql, $read_only = false, $ignore_error = 0)
{
	// Connect to db. Use mysql_connect so connections close automatically when page exits.
	$db = mysql_connect(__get_db_domain_name($db_name), __get_db_user_name($read_only), __get_db_password($read_only));

	mysql_query("SET CHARACTER SET 'utf8'", $db);
	
	// Attempt to select the db
	if (!$db || !mysql_select_db($db_name)) {
		echo('DB Error: ' . mysql_errno() . ':' . mysql_error());
		return false;
	}
	
	// Execute the query
	$result = mysql_query($sql, $db);
	if (!$result) {
		if (mysql_errno() <> $ignore_error) {
			//echo('SQL Error: ' . mysql_errno() . ':' . mysql_error());
			//echo($sql);
			//echo('A SQL error occurred: ' . mysql_errno());
		}
		return false;
	}
	
	// Return the result
	return $result;
}

// -----------------------------------------------------------------
// Return the number of table records
// -----------------------------------------------------------------
function __get_table_count($table, $where = '')
{
	$result = __do_sql("select count(*) as count from $table $where");
	if (!$result)
		return 0;
	return mysql_result($result, 0, 'count');
}

// -----------------------------------------------------------------
// Return the field of a table where
// -----------------------------------------------------------------
function __get_table_field($table, $field, $where = '')
{
	//echo "select `$field` from $table $where";
	$result = __do_sql("select `$field` from $table $where");
	if (!$result || mysql_num_rows($result) == 0)
		return '';
	return mysql_result($result, 0, $field);
}

// -----------------------------------------------------------------
// Functions to return database access information
// -----------------------------------------------------------------
function __get_db_domain_name($db_name = 'default')
{
	$a = array(
		'default' 				=> 'localhost',
		DATABASE_NAME 			=> DATABASE_HOST
		);
	return $a[$db_name];
}
function __get_db_name()
{
 	return DATABASE_NAME;
}
function __get_db_user_name()
{
	return DATABASE_USER_NAME;
}
function __get_db_password()
{
	return DATABASE_USER_PASSWORD;
}

?>
