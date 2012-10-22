<?
return; // already run - does not need to be run again!

require_once('../../defines.php');
require_once('../../db.php');
require_once('../../utilities.php');
__get_table_count('members'); // We need to do this so mysql_real_escape_string works properly

$result = __do_sql("select * from partners");
while ($r = mysql_fetch_assoc($result)) {
	$name = $r['name'];

	$result2 = __do_sql("select * from members where kind = 'business' and bname = '$name'");
	while ($r2 = mysql_fetch_assoc($result2)) {
		
		$pkey = $r2['pkey'];
		$description = $r['description'];
		$url = $r['url'];
		$support_types = explode('|', $r['support_type']);
		foreach ($support_types as $k => $v) {
		
			echo "$pkey $v $name $url $description <br />";
			$result2 = __do_sql("insert into business_partner (fkey, support_type, url, description, active) values ('$pkey', '$v','$url','$description','Y');");
		}
	}
}

?>
