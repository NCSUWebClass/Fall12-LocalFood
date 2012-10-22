<?php
function __current_user_unity_id()
{
	return getenv('WRAP_USERID');
}

// Admin users can do EVERYTHING in the admin section
function __is_admin_user($unity_id = '')
{
	if ($unity_id == '') $unity_id = __current_user_unity_id();
	return strstr('|jmzobkiw|ndsouza3|wjiang4|rconlon|rkimsey|mccollum|ncreamer|tlwymore|cjcrawfo|jplaiosa|klfrizze|ksjayara|gmbrigha|ejballar|krbaldwi|rdstout|',$unity_id);
}

// LFCs and Regional LFCs
function __is_lfc($unity_id = '')
{
	if ($unity_id == '') $unity_id = __current_user_unity_id();
	
	// Check for normal LFCs based on current list
	$result = __ncce_request('coordinators_unity', '', 'xemp');
	foreach ($result as $k => $r) {
		if ($r['unity_id'] == $unity_id)
			return true;
	}
	
	// Regional LFCs added 17-NOV-2010 as requested by Teisha
	return strstr('|cfhudson|lerogers|bbsutton|mogibbs|maseitz|sjcolucc|', $unity_id);
}

function __send_signup_email($pkey) 
{
	$result = false;
	
	if (__is_consumer($pkey)) {
		$filename = "/afs/unity.ncsu.edu/web/n/nc10percent/htdocs/email_templates/signup_email_to_consumers_template_mime.txt";
	} else if (__is_business_type($pkey, 'RESTAURANT')) {
		$bname = __get_member_field($pkey, 'bname');
		$filename = "/afs/unity.ncsu.edu/web/n/nc10percent/htdocs/email_templates/signup_email_to_restaurants_template_mime.txt";
	} else return false;
	$handle = fopen($filename,"r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);
	
	$contents = str_replace("[ACTIVATION_LINK]", __create_activation_link($pkey), $contents);
	$contents = str_replace("[UNSUBSCRIBE_LINK]", __create_unsubscribe_link($pkey), $contents);
	$contents = str_replace("[BNAME]", $bname, $contents);
	
//	echo $contents;
	
	$to = __get_member_field($pkey, 'email');
	if ($to) {
		$subject = "Welcome to The 10% Campaign";
		$additional_headers = ADDITIONAL_MAIL_HEADERS_MULTIPART;
		$additional_parameters = ADDITIONAL_MAIL_PARAMETERS;
	 	$result = mail($to, $subject, $contents, $additional_headers, $additional_parameters);
	}

	return $result;
}

function __send_signup_email_to_coordinators($pkey) 
{
	$result = false;
	
	$name = __get_member_field($pkey, 'name');
	$email = __get_member_field($pkey, 'email');
	$bname = __get_member_field($pkey, 'bname');
	$btype = __get_member_field($pkey, 'btype');
	$bphone = __get_member_field($pkey, 'bphone');
	$zipcode = __get_member_field($pkey, 'zipcode');
	$registered_on = __get_member_field($pkey, 'registered_on');
	
	$contact_emails = EMAIL_ADDRESS;
	if ($btype <> 'RESTAURANT') {
		$county_name = __get_zipcode_county($zipcode);
		if ($county_name) {
			$county_id = __get_county_id($county_name);
			if ($county_id) {
				$contacts = __get_county_contact_emp_ids($county_id);
				foreach ($contacts as $k => $v) {
					$e = __get_emp_id_email($v);
					if ($e)
						$contact_emails .= ', '.$e;
				}
			}
		}
	}
	
	$filename = "/afs/unity.ncsu.edu/web/n/nc10percent/htdocs/email_templates/signup_email_to_coordinators_template_mime.txt";
	$handle = fopen($filename,"r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);

	$contents = str_replace("[EMAIL_SENT_TO]", $contact_emails, $contents);
	$contents = str_replace("[BNAME]", $bname, $contents);
	$contents = str_replace("[BTYPE]", __get_list_description('BIZORG', $btype), $contents);
	$contents = str_replace("[BPHONE]", $bphone, $contents);
	$contents = str_replace("[NAME]", $name, $contents);
	$contents = str_replace("[EMAIL]", $email, $contents);
	$contents = str_replace("[ZIPCODE]", $zipcode, $contents);
	$contents = str_replace("[REGISTERED_ON]", $registered_on, $contents);

	// echo $contents;

	$to = $contact_emails;
	
	if ($to) {
		$subject = "New business supporter of the 10% Campaign - Action Required -";
		$additional_headers = ADDITIONAL_MAIL_HEADERS_MULTIPART;
		$additional_parameters = ADDITIONAL_MAIL_PARAMETERS;
	 	$result = mail($to, $subject, $contents, $additional_headers, $additional_parameters);
	}
	
	return $result;
}

function __is_consumer($pkey)
{
	return (__get_member_field($pkey, 'kind') == 'consumer');
}

function __is_business($pkey)
{
	return (__get_member_field($pkey, 'kind') == 'business');
}

function __is_business_type($pkey, $type)
{
	return __is_business($pkey) && (__get_member_field($pkey, 'btype') == $type);
}

function __get_previous_farms($pkey)
{
	if (__is_business_type($pkey, 'RESTAURANT')) {
		$result = __do_sql("select source_local_farms from restaurant_data_weekly where fkey = '$pkey' and source_local_farms <> '' order by pkey desc LIMIT 1");
		if ($result && mysql_num_rows($result) == 1) {
			$source_local_farms = mysql_result($result, 0, "source_local_farms");
		}
		if ($source_local_farms == '') {
			$result = __do_sql("select farms from restaurant_data_initial where fkey = '$pkey' LIMIT 1");
			if ($result && mysql_num_rows($result) == 1) {
				$source_local_farms = mysql_result($result, 0, "farms");
			}
		}
		return $source_local_farms;
		
	} else return '';
}

function __calculate_activation_hash($pkey)
{
	return sha1(md5($pkey.MAGIC_KEY).MAGIC_KEY); // md5 then sha1
}

function __create_activation_link($pkey)
{
	return ACTIVATION_URL . "?p=" . ($pkey) . "&h=" . (__calculate_activation_hash($pkey));
}

function __calculate_unsubscribe_hash($pkey)
{
	return md5(sha1($pkey.MAGIC_KEY).MAGIC_KEY); // sha1 then md5
}

function __create_unsubscribe_link($pkey)
{
	return UNSUBSCRIBE_URL . "?p=" . ($pkey) . "&h=" . (__calculate_unsubscribe_hash($pkey));
}

function __calculate_weekly_hash($pkey, $weekly_pkey)
{
	return md5(md5($pkey.MAGIC_KEY.$weekly_pkey).MAGIC_KEY); // md5 then md5
}

function __create_weekly_link($pkey, $weekly_pkey)
{
	return WEEKLY_URL . "?wp=" . ($weekly_pkey) . "&p=" . ($pkey) . "&h=" . (__calculate_weekly_hash($pkey, $weekly_pkey));
}

function __fm($money)
{
	setlocale(LC_MONETARY, 'en_US');
	$formatted = money_format('%i', $money);
	$formatted = str_replace('USD ', '', $formatted); // $1,234.00
	$formatted = substr($formatted, 0, strlen($formatted)-3); // drop decimal and cents
	return '$'.$formatted;
}

function __get_consumer_weekly_spend_amount()
{
	$total = 0;
	$result = __do_sql("select sum(weekly_spend_amount) as total_weekly_spend_amount from (select fkey,max(answered_on) as max_answered_on from consumer_data_initial group by fkey) as d inner join consumer_data_initial as c on c.fkey = d.fkey and c.answered_on= d.max_answered_on");
	
	if ($result && mysql_num_rows($result) == 1) {
		$total += mysql_result($result, 0, "total_weekly_spend_amount");
	}
	
	return $total;
}

function __get_consumer_actual_spend_amount($non_garden = true, $garden = true)
{
	$total = 0;
	
	if ($non_garden) {
		$result = __do_sql("select sum(amount) as total_actual_spend_amount from consumer_data_weekly");
		if ($result && mysql_num_rows($result) == 1) {
			$total += mysql_result($result, 0, "total_actual_spend_amount");
		}
	}

	if ($garden) {
		$result = __do_sql("select sum(garden_amount) as garden_offset from consumer_data_weekly");
		if ($result && mysql_num_rows($result) == 1) {
			$total += mysql_result($result, 0, "garden_offset");
		}
	}
	
	return $total;
}

function __get_restaurant_annual_spend_amount()
{
	$total = 0;
	
	$result = __do_sql("select sum(annual_food_cost) as annual_food_cost from restaurant_data_initial");
	if ($result && mysql_num_rows($result) == 1) {
		$total += mysql_result($result, 0, "annual_food_cost");
	}
	
	return $total;
}

function __get_restaurant_actual_spend_amount()
{
	$total = 0;
	
	$result = __do_sql("select sum(purchase_this_week) as purchase_this_week from restaurant_data_weekly");
	if ($result && mysql_num_rows($result) == 1) {
		$total += mysql_result($result, 0, "purchase_this_week");
	}

	return $total;
}

function __get_business_actual_spend_amount()
{
	$total = 0;
	
	$current_date = mktime();
//	$result = __do_sql("select * from members where kind='business' and btype<>'RESTAURANT'");
	$result = __do_sql("select * from members where kind='business' ");
	while ($r = mysql_fetch_assoc($result)) {
		$pkey = $r['pkey'];

		// Calculate time since registration
		$seconds = mktime() - strtotime($r['registered_on']);
		$days = floor($seconds / 86400);
		$weeks = $days / 7;
		$months = $weeks / 4.3;
		$quarters = $months / 3;
		$years = $months / 12;

		$result2 = __do_sql("select * from business_data where fkey = '$pkey'");
		if ($result2 && mysql_num_rows($result2) == 1) {
			$calc_freq = mysql_result($result2, 0, "calc_freq");
			$local_food = mysql_result($result2, 0, "local_food");

			if ($calc_freq == "1") { // weekly
				$total += ($local_food * $weeks);
			} else if ($calc_freq == "4") { // monthly
				$total += ($local_food * $months);
			} else if ($calc_freq == "13") { // quarterly
				$total += ($local_food * $quarters);
			} else if ($calc_freq == "52") { // annually
				$total += ($local_food * $years);
			} else $total += $local_food; // No multiplier		

		}
	}
	
	return $total;
}

// --- DISPLAYED ON FRONT PAGE
function __get_total_dollar_amount($cents = false)
{
	$total = 0;
	
	// Consumers
	$result = __do_sql("select sum(amount) as total_amount from consumer_data_weekly");
	if ($result && mysql_num_rows($result) == 1) {
		$total += mysql_result($result, 0, "total_amount");
	}
	
	/*
	$result = __do_sql("select sum(garden_amount) as total_amount from consumer_data_weekly");
	if ($result && mysql_num_rows($result) == 1) {
		$total += mysql_result($result, 0, "total_amount");
	}
	*/
	
	// Restaurants
	$result = __do_sql("select sum(purchase_this_week) as total_amount from restaurant_data_weekly");
	if ($result && mysql_num_rows($result) == 1) {
		$total += mysql_result($result, 0, "total_amount");
	}
	
	// Businesses (NOTE: This also counts restaurants if they have backend business data so double-counting may occur if they are also reporting weekly)
	$current_date = mktime();
//	$result = __do_sql("select * from members where kind='business' and btype<>'RESTAURANT'");
	$result = __do_sql("select * from members where kind='business' ");
	while ($r = mysql_fetch_assoc($result)) {
		$pkey = $r['pkey'];
		
		// Calculate time since registration
		$seconds = mktime() - strtotime($r['registered_on']);
		$days = floor($seconds / 86400);
		$weeks = $days / 7;
		$months = $weeks / 4.3;
		$quarters = $months / 3;
		$years = $months / 12;
		
		$result2 = __do_sql("select * from business_data where fkey = '$pkey'");
		if ($result2 && mysql_num_rows($result2) == 1) {
			$calc_freq = mysql_result($result2, 0, "calc_freq");
			$local_food = mysql_result($result2, 0, "local_food");

			if ($calc_freq == "1") { // weekly
				$total += ($local_food * $weeks);
			} else if ($calc_freq == "4") { // monthly
				$total += ($local_food * $months);
			} else if ($calc_freq == "13") { // quarterly
				$total += ($local_food * $quarters);
			} else if ($calc_freq == "52") { // annually
				$total += ($local_food * $years);
			} else $total += $local_food; // No multiplier

		}
	}
	
	setlocale(LC_MONETARY, 'en_US');
	$formatted_total = money_format('%i', $total); // USD 1,234.00
	$formatted_total = str_replace('USD ', '$', $formatted_total); // $1,234.00
	if ($cents == false)
		$formatted_total = substr($formatted_total, 0, strlen($formatted_total)-3); // drop decimal and cents
	//$formatted_total = str_replace('.00', '', $formatted_total); // $1,234
	return $formatted_total;
}

function __get_member_count_by_kind($kind = 'consumer', $additional_where = '') // ignore active/inactive/opt_out
{
	$result = __do_sql("select count(*) as c from members where kind = '$kind' $additional_where");
	if ($result && mysql_num_rows($result) == 1)
		return mysql_result($result, 0, "c");
	return 0;
}

function __get_member_field($pkey, $field)
{
	$result = __do_sql("select `$field` from members where pkey = '$pkey' limit 1");
	if ($result && mysql_num_rows($result) == 1)
		return mysql_result($result, 0, "$field");
	return '';
}

function __get_member_field_initial_survey($pkey, $field)
{
	if (__is_consumer($pkey)) {
		$result = __do_sql("select `$field` from consumer_data_initial where fkey = '$pkey' ORDER BY answered_on desc limit 1");
		if ($result && mysql_num_rows($result) == 1)
			return mysql_result($result, 0, "$field");
	} else if (__is_business_type($pkey, 'RESTAURANT')) {
		$result = __do_sql("select `$field` from restaurant_data_initial where fkey = '$pkey' limit 1");
		if ($result && mysql_num_rows($result) == 1)
			return mysql_result($result, 0, "$field");
	}
	return '';
}

function __get_business_data_field($pkey, $field)
{	
	$selres=__do_sql ("select pkey from business_data where fkey = '$pkey' ");
	$maxpkey=0;
	while ($rr = mysql_fetch_assoc($selres))
	{
		if ($rr['pkey']>$maxpkey)
		{
			$maxpkey=$rr['pkey'];
		}
	}
	$result = __do_sql("select `$field` from business_data where pkey = '$maxpkey' limit 1");
	if ($result && mysql_num_rows($result) == 1)
		return mysql_result($result, 0, "$field");
	return '';
}

function __get_business_partner_field($pkey, $field, $support_type)
{
	$result = __do_sql("select `$field` from business_partner where fkey = '$pkey' and support_type = '$support_type' limit 1");
	if ($result && mysql_num_rows($result) == 1)
		return mysql_result($result, 0, "$field");
	return '';
}

function __get_business_partner_active_support_types($pkey)
{
	$s = '';
	$result = __do_sql("select support_type from business_partner where fkey = '$pkey' and active ='Y'");
	while ($r = mysql_fetch_assoc($result)) {
		if ($s) $s .= '|';
		$s .= $r['support_type'];
	}
	return $s;
}

function __get_partner_field($pkey, $field)
{
	$result = __do_sql("select `$field` from partners where pkey = '$pkey' limit 1");
	if ($result && mysql_num_rows($result) == 1)
		return mysql_result($result, 0, "$field");
	return '';
}

function __is_valid_zipcode($zipcode)
{
	$result = __do_sql("select * from zipcodes where zipcode = '$zipcode' limit 1");
	if ($result && mysql_num_rows($result) == 1)
		return true;
	return false;
}

function __get_zipcode_county($zipcode)
{
	$result = __do_sql("select `county` from zipcodes where zipcode = '$zipcode' and primaryrecord = 'P' limit 1");
	if ($result && mysql_num_rows($result) == 1)
		return mysql_result($result, 0, 'county');
	return '';
}

function __create_county_popup($name = "", $select = "", $tags = "", $add_blank = false)
{
	$result = __ncce_request('create_county_popup', '', 'xemp');
	__create_select_options($result, $name, $add_blank, $select, false, $tags);
}

function __create_county_popup_with_id($name = "", $select = "", $tags = "", $add_blank = false)
{
	$result = __ncce_request('create_county_popup_with_id', '', 'xemp');
	__create_select_options($result, $name, $add_blank, $select, false, $tags);
}

function __get_county_id($county_name = "")
{
	$county_name = mysql_escape_string($county_name);
	$result = __ncce_request('get_county_id', $county_name, 'xemp');
	if (count($result) == 1)
		return $result[0]['county_id'];
	return 0;
}

function __get_county_name($county_id = "")
{
	$county_id = mysql_escape_string($county_id);
	$result = __ncce_request('get_county_name', $county_id, 'xemp');
	if (count($result) == 1)
		return $result[0]['county_name'];
	return 0;
}

function __get_county_contact_emp_ids($county_id)
{
	$contacts = array();
	$result = __ncce_request('get_county_contact_emp_ids', $county_id, 'xemp');
	foreach ($result as $k => $r) {
		$contacts[] = $r['emp_id'];
	}
	return $contacts;
}

function __get_emp_id_name($emp_id)
{
	$result = __ncce_request('get_emp_id_name', $emp_id, 'xemp');
	if (count($result) == 1)
		return $result[0]['thename'];
	return 'County Agent';
}

function __get_emp_id_email($emp_id)
{
	$result = __ncce_request('get_emp_id_email', $emp_id, 'xemp');
	if (count($result) == 1)
		return $result[0]['email'];
	return 'webmaster@ces.ncsu.edu';
}

function __format_city_state($city, $state)
{
	$str = '';
	if ($city) {
		$str .= ucwords(strtolower($city));
		if ($state)
			$str .= ', ' . strtoupper($state);
	} else if ($state) {
		$str .= strtoupper($state);
	}
	return $str;
}

// -----------------------------------------------------------------
// Echos HTML of select options with optional blank value based on number range
// -----------------------------------------------------------------
function __create_select_range($min, $max, $name, $by = 1, $add_blank = false, $select = "", $ignore_auto_select = false, $tags = "", $substitutions_array = false)
{
	echo "<select name='" . $name . "' id='" . $name . "' $tags>";
	if ($add_blank) {
		$add_blank_string = ($add_blank === true) ? '' : $add_blank;
		echo "<option label='$add_blank_string' value=''";
		if ($select == "")
			echo " selected";
		echo ">$add_blank_string</option>";
	}
	for ($i=$min;$i<=$max;$i+=$by) {
		$value = $i;
		if ($substitutions_array && is_array($substitutions_array) && array_key_exists($value, $substitutions_array)) {
			$value = $substitutions_array[$value];
		}
		echo "<option label='".$value."' value='".$i."'";
		if (($i === $select) || (($select == "") && ($add_blank === false) && ($ignore_auto_select == false)))
			echo " selected";
		echo ">".$value."</option>";
	}
	echo "</select>";
}

// -----------------------------------------------------------------
// Echos HTML of select options with optional blank value
// ie: __create_select_options(__get_list_type("DTYP"), "test");
// -----------------------------------------------------------------
function __create_select_options($result, $name, $add_blank = false, $select = "", $ignore_auto_select = false, $tags = "", $substitutions_array = false)
{
	if ($result) {
		echo "<select name='" . $name . "' id='" . $name . "' $tags>";
		if ($add_blank) {
			$add_blank_string = ($add_blank === true) ? '' : $add_blank;
			echo "<option label='$add_blank_string' value=''";
			if ($select == "")
				echo ' selected="selected"';
			echo ">$add_blank_string</option>";
		}
		if (is_array($result)) {
			$num_rows = count($result);
			for ($i=0;$i<$num_rows;$i++) {
				$r = $result[$i];
				$value = $r['description'];
				if ($substitutions_array && is_array($substitutions_array) && array_key_exists($value, $substitutions_array)) {
					$value = $substitutions_array[$value];
				}
				echo "<option label='".$value."' value='".$r['code']."'";
				if (((strtolower($r['code'])) == strtolower($select)) || (($select == "") && ($i == 0) && ($add_blank === false) &&  ($ignore_auto_select == false)))
					echo ' selected="selected"';
				echo ">".$value."</option>";
			}
		} else {
			$num_rows = mysql_num_rows($result);
			for ($i=0;$i<$num_rows;$i++) {
				$value = mysql_result($result, $i, 'description');
				if ($substitutions_array && is_array($substitutions_array) && array_key_exists($value, $substitutions_array)) {
					$value = $substitutions_array[$value];
				}
				echo "<option label='".$value."' value='".mysql_result($result, $i, 'code')."'";
				if ((strtolower(mysql_result($result, $i, 'code')) == strtolower($select)) || (($select == "") && ($i == 0) && ($add_blank === false) &&  ($ignore_auto_select == false)))
					echo ' selected="selected"';
				echo ">".$value."</option>";
			}
		}
		echo "</select>";
	}
}

// -----------------------------------------------------------------
// Echos HTML of checkbox options with optional "other"
// ie: __create_select_options(__get_list_type("DTYP"), "test");
// -----------------------------------------------------------------
function __create_checkbox_options($result, $name, $select = "", $ignore_auto_select = false, $tags = "", $break = true)
{
	if ($result) {
		$num_rows = mysql_num_rows($result);
		for ($i=0;$i<$num_rows;$i++) {
			$value = mysql_result($result, $i, 'description');
			$code = mysql_result($result, $i, 'code');
			echo "<input type=\"checkbox\" name='".$name."[]' id='".$name."[]' value='".$code."' $tags";
			if ((strstr($select, $code)) || (in_array($code, $select)) || 
				(($select == "") && (mysql_result($result, $i, 'selected') == "Y") && ($ignore_auto_select == false)))
				echo ' checked="checked"';
			echo "> ".$value.'&nbsp;';
			if ($break) echo '<br />'; else echo '&nbsp;&nbsp;&nbsp;';
		}
		echo "</select>";
	}
}

// LIST functions

function __get_list_description($type, $code)
{
	return __get_list_field_by_type_code($type, $code, 'description');
}

function __get_list_type($type, $where='', $active='Y')
{
	$sql = "select * from list where type = '$type' and active = '$active'";
	if ($where)
		$sql .= " and $where";
	$sql .= " order by ind, description";
	return __do_sql($sql);
}

function __get_list_type_code($type, $code, $where='', $active='Y', $order_by = "ind, description")
{
	$sql = "select * from list where type = '$type' and code = '$code' and active = '$active'";
	if ($where)
		$sql .= " and $where";
	if ($order_by)
		$sql .= " order by $order_by";
	return __do_sql($sql);
}

function __get_list_type_description_like($type, $like, $active='Y')
{
	return __do_sql("select * from list where type = '$type' and active = '$active' and description like '$like' order by ind, description");
}

function __get_list_field_by_type_code($type, $code, $field)
{
	$result = __do_sql("select * from list where type = '$type' and code = '$code'");
	if (($result) && (mysql_num_rows($result) == 1))
		return mysql_result($result, 0, $field);
	return false;
}

function __list_type_code_exists($type, $code)
{
	$result = __do_sql("select * from list where type = '$type' and code = '$code'");
	if (($result) && (mysql_num_rows($result) == 1))
		return true;
	return false;
}

function __list_type_code_is_active($type, $code)
{
	$result = __do_sql("select * from list where type = '$type' and code = '$code' and active='Y'");
	if (($result) && (mysql_num_rows($result) == 1))
		return true;
	return false;
}

/**
 * Loads Stylesheet depending on what page we're on
 *
 * @return string
 */
function loadStyles() {
	$currentFile = getCurrent();
	if (!strcmp($currentFile,"index.php")) {
		return '<link rel="stylesheet" href="css/import.css" type="text/css" />
				<style type="text/css">
					div#wrapper div#main div#belowfold { background-color:#FFFFFF; }
				</style>
				';
	} else {
		return '<link rel="stylesheet" href="css/import.css" type="text/css" />
				<style type="text/css">
					div#wrapper div#main div#belowfold { position:absolute; top:33px; z-index:1;  background-color:#c4d6eb; padding:80px 20px; }
				</style>
			   ';
	}
}

/**
 * Gets Current Filename
 *
 * @return string
 */
function getCurrent() {
	$currentFile = $_SERVER["SCRIPT_NAME"];
	$parts = Explode('/', $currentFile);
	$currentFile = $parts[count($parts) - 1];
	return $currentFile;
}

?>
