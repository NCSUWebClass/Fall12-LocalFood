<?php
function __current_user_unity_id()
{
	return getenv('WRAP_USERID');
}

// Admin users can do EVERYTHING in the admin section
function __is_admin_user($unity_id = '')
{
	if ($unity_id == '') $unity_id = __current_user_unity_id();
	return strstr('|jmzobkiw|ndsouza3|wjiang4|rconlon|rkimsey|mccollum|ncreamer|tlwymore|cjcrawfo|jplaiosa|klfrizze|ksjayara|gmbrigha|ejballar|krbaldwi|rdstout|kainger2|vcheruk',$unity_id);
}

function __is_edit_user($unity_id = '')
{
	if ($unity_id == '') $unity_id = __current_user_unity_id();
	return strstr('tlwymore|rdstout|cjcrawfo|vcheruk',$unity_id);
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
			//$calc_freq = mysql_result($result2, 0, "calc_freq");
			$local_food = mysql_result($result2, 0, "local_food");

			$total += $local_food;
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
		while ($rr=mysql_fetch_assoc($result2))
		{
			
			 $total += $rr['local_food']; // No multiplier

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

function __get_business_record_number($pkey)
{
	$getnum=__do_sql("select COUNT(*) as COUNT from business_data where fkey= '$pkey' ");
	while ($rr= mysql_fetch_assoc($getnum))
	{
		$retval=$rr['COUNT'];
	}
	return $retval;
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
 * Get the table to compute averages from certain county
 *
 * @return array
 */
function __get_consumers_in_county($zipcode, $weeks) { 
	$county = __get_zipcode_county($zipcode);
	$result = __do_sql("SELECT zipcodes.zipcode, members.pkey, members.name, consumer_data_weekly.fkey, consumer_data_weekly.answered_on, consumer_data_weekly.amount FROM zipcodes 
						INNER JOIN members ON zipcodes.zipcode = members.zipcode AND members.kind = 'consumer' AND zipcodes.primaryrecord = 'P' AND zipcodes.county = '".$county."' 
						INNER JOIN consumer_data_weekly ON members.pkey = consumer_data_weekly.fkey AND 
						(UNIX_TIMESTAMP(consumer_data_weekly.answered_on) > (UNIX_TIMESTAMP()-(604800*".$weeks."))) ORDER BY consumer_data_weekly.answered_on");
	return $result;
}

function __get_restaurants_in_zipcode($zipcode, $weeks) {
	$county = __get_zipcode_county($zipcode);
	$result = __do_sql("SELECT members.zipcode, members.pkey, members.name, restaurant_data_weekly.fkey, restaurant_data_weekly.answered_on, restaurant_data_weekly.purchase_this_week FROM members 
						INNER JOIN restaurant_data_weekly ON members.pkey = restaurant_data_weekly.fkey AND members.kind = 'business' AND members.zipcode = '".$zipcode."'  AND 
						(UNIX_TIMESTAMP(restaurant_data_weekly.answered_on) > (UNIX_TIMESTAMP()-(604800*".$weeks."))) ORDER BY restaurant_data_weekly.answered_on");
	return $result;
}


/**
 * Get the table to compute averages from certain zipcode
 *
 * @return array
 */
function __get_consumers_in_zipcode($zipcode, $weeks) { 
	$result = __do_sql("SELECT members.zipcode, members.pkey, members.name, consumer_data_weekly.fkey, consumer_data_weekly.answered_on, consumer_data_weekly.amount FROM members 
						INNER JOIN consumer_data_weekly ON members.pkey = consumer_data_weekly.fkey AND members.kind = 'consumer' AND members.zipcode = '".$zipcode."' AND
						(UNIX_TIMESTAMP(consumer_data_weekly.answered_on) > (UNIX_TIMESTAMP()-(604800*".$weeks."))) ORDER BY consumer_data_weekly.answered_on");
	return $result;
}

/**
 * Get the table to compute averages for same household size of consumer
 *
 * @return array
 */
function __get_consumers_in_household_size($household_size, $weeks) { 
	$result = __do_sql("SELECT members.pkey, members.name, consumer_data_weekly.fkey, consumer_data_weekly.answered_on, consumer_data_weekly.amount FROM members
						INNER JOIN (select c.fkey,household from (select fkey,max(answered_on) as max_answered_on from consumer_data_initial group by fkey) as d inner join consumer_data_initial as c on c.fkey = d.fkey and c.answered_on= d.max_answered_on) as c ON members.pkey = c.fkey AND members.kind = 'consumer' AND c.household = '".$household_size."' 
						INNER JOIN consumer_data_weekly ON members.pkey = consumer_data_weekly.fkey AND 
						(UNIX_TIMESTAMP(consumer_data_weekly.answered_on) > (UNIX_TIMESTAMP()-(604800*".$weeks."))) ORDER BY consumer_data_weekly.answered_on");
	return $result;
}

/**
 * Get the table of consumers history
 *
 * @return array
 */
function __get_consumers_history($weeks, $pkey) { 
	$result = __do_sql("SELECT members.pkey, members.name, consumer_data_weekly.fkey, consumer_data_weekly.answered_on, consumer_data_weekly.amount FROM members 
						INNER JOIN consumer_data_weekly ON members.pkey = consumer_data_weekly.fkey AND members.kind = 'consumer' AND members.pkey = ".$pkey." AND
						(UNIX_TIMESTAMP(consumer_data_weekly.answered_on) > (UNIX_TIMESTAMP()-(604800*".$weeks."))) ORDER BY consumer_data_weekly.answered_on");
	return $result;
}

function __get_restaurant_history($weeks, $pkey) {
	echo 'I reached here';
	$result = __do_sql("SELECT members.pkey, members.name, restaurant_data_weekly.fkey, restaurant_data_weekly.answered_on, restaurant_data_weekly.amount FROM members 
						INNER JOIN restaurant_data_weekly ON members.pkey = restaurant_data_weekly.fkey AND members.kind = 'business' AND members.pkey = ".$pkey." AND
						(UNIX_TIMESTAMP(restaurant_data_weekly.answered_on) > (UNIX_TIMESTAMP()-(604800*".$weeks."))) ORDER BY restaurant_data_weekly.answered_on");
	return $result;
}




/**
 * Get the table of consumers history with zip
 * 
 * @return array
 */
function __get_consumers_history_zip($zipcode, int $weeks, $pkey) { 
	$county = __get_zipcode_county($zipcode);
	$result = __do_sql("SELECT zipcodes.zipcode, members.pkey, members.name, consumer_data_weekly.fkey, consumer_data_weekly.answered_on, consumer_data_weekly.amount FROM zipcodes 
						INNER JOIN members ON zipcodes.zipcode = members.zipcode AND members.kind = 'consumer' AND zipcodes.primaryrecord = 'P' AND zipcodes.county = '".$county."' AND members.pkey = ".$pkey."
						INNER JOIN consumer_data_weekly ON members.pkey = consumer_data_weekly.fkey AND 
						(UNIX_TIMESTAMP(consumer_data_weekly.answered_on) > (UNIX_TIMESTAMP()-(604800*".$weeks."))) ORDER BY consumer_data_weekly.answered_on");
	return $result;
}

/**
 * Get the table to compute averages from certain county
 *
 * @return array
 */
function __get_consumer_zip($consumer_id) { 
	$result = __do_sql("SELECT zipcode FROM members WHERE pkey = ".$consumer_id);
	return $result;
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

/**
 * For look_for_local
 *
 */

function __get_restaurants_in_region($color)
{
$zippurple=array(28615,28617,28626,28629,28631,28640,28643,28672,28684,28693,28694,28623,28627,28644,28663,28668,28675,27007,27017,27024,27030,27031,27041,27047,27049,27053,28621,28676,28606,28624,28635,28649,28651,28654,28656,28659,28665,28669,28670,28683,28685,28697,27011,27018,27020,27055,28642,28611,28630,28633,28638,28645,28661,28667,28636,28678,28681,28010,28115,28117,28123,28166,28625,28634,28660,28677,28687,28688,28689,28699,27006,27014,27028,27013,27054,28023,28039,28041,28071,28072,28088,28125,28138,28144,28145,28146,28147,28159,28612,28619,28628,28637,28641,28647,28655,28666,28671,28680,28690,28601,28602,28603,28609,28610,28613,28650,28658,28673,28682,28018,28019,28024,28040,28043,28074,28076,28139,28160,28167,28720,28746,28017,28020,28038,28042,28073,28086,28089,28090,28114,28136,28150,28151,28152,28169,28033,28037,28080,28092,28093,28168,28006,28012,28016,28021,28032,28034,28052,28053,28054,28055,28056,28077,28098,28101,28120,28164,28031,28035,28036,28070,28078,28105,28106,28126,28130,28134,28201,28202,28203,28204,28205,28206,28207,28208,28209,28210,28211,28212,28213,28214,28215,28216,28217,28218,28219,28220,28221,28222,28223,28224,28225,28226,28227,28228,28229,28230,28231,28232,28233,28234,28235,28236,28237,28241,28242,28243,28244,28246,28247,28250,28253,28254,28255,28256,28258,28260,28262,28263,28265,28266,28269,28270,28271,28272,28273,28274,28275,28277,28278,28280,28281,28282,28284,28285,28287,28288,28289,28290,28296,28297,28299);
$zipgreen=array(28604,28616,28622,28646,28652,28653,28657,28662,28664,28701,28704,28709,28711,28715,28728,28730,28748,28757,28770,28776,28778,28787,28801,28802,28803,28804,28805,28806,28810,28813,28814,28815,28816,28781,28901,28903,28905,28906,28902,28904,28909,28733,28771,28716,28721,28738,28745,28751,28785,28786,28710,28724,28726,28727,28729,28731,28732,28735,28739,28742,28758,28759,28760,28784,28790,28791,28792,28793,28707,28717,28723,28725,28736,28779,28783,28788,28789,28734,28741,28744,28763,28775,28734,28741,28744,28763,28775,28743,28753,28754,28737,28749,28752,28761,28762,28705,28765,28777,28722,28750,28756,28773,28782,28702,28713,28719,28708,28712,28718,28747,28766,28768,28772,28774,28605,28607,28608,28618,28679,28691,28692,28698,28714,28740,28755);
$zipred=array(27201,27202,27215,27216,27217,27244,27253,27258,27298,27302,27340,27349,27359,27212,27291,27305,27311,27314,27315,27379,27207,27208,27213,27228,27252,27256,27312,27344,27559,27239,28292,27293,28294,27295,27299,27351,27360,27361,27373,27374,27503,27572,27701,27702,27703,27704,27705,27706,27707,27708,27709,27710,27711,27712,27713,27715,27717,27722,27009,27010,27012,27023,27040,27045,27050,27051,27094,27098,27099,27101,27102,27103,27104,27105,27106,27107,27108,27109,27110,27111,27113,27114,27115,27116,27117,27120,27127,27130,27150,27152,27155,27157,27198,27199,27284,27285,27508,27525,27549,27596,27507,27509,27522,27564,27565,27581,27582,27214,27233,27235,27249,27260,27261,27262,27263,27264,27265,27282,27283,27301,27310,27313,27342,27357,27358,27377,27401,27402,27403,27404,27405,27406,27407,27408,27409,27410,27411,27412,27413,27415,27416,27417,27419,27420,27425,27427,27429,27435,27438,27455,27495,27495,27497,27498,27499,27231,27243,27278,27510,27514,27515,27516,27517,27599,27343,27541,27573,27574,27583,27203,27204,27205,27230,27248,27316,27317,27341,27350,27355,27370,27025,27027,27048,27288,27289,27320,27326,27375,27016,27019,27021,27022,27042,27043,27046,27052,27536,27537,27544,27553,27556,27584,27502,27511,27512,27513,27518,27519,27523,27526,27529,27502,27511,27512,27513,27518,27519,27523,27526,27529,27539,27540,27545,27560,27562,27571,27587,27588,27591,27592,27597,27601,27602,27603,27604,27605,27606,27607,27608,27609,27610,27611,27612,27613,27614,27615,27616,27617,27619,27620,27621,27622,27623,27624,27625,27626,27627,27628,27629,27634,27635,27636,27640,27650,27656,27658,27661,27668,27675,27676,27690,27695,27697,27698,27699,27551,27563,27570,27586,27589,27594);
$zipblue=array(28007,28091,28102,28119,28133,28135,28170,28320,28332,28337,28392,28399,28433,28434,28448,28025,28026,28027,28075,28081,28082,28083,28107,28124,28423,28424,28430,28431,28432,28436,28438,28439,28442,28450,28455,28456,28463,28472,28301,28302,28303,28304,28305,28306,28307,28308,28309,28310,28311,28312,28314,28331,28342,28348,28356,28390,28391,28395,27501,27506,27521,27543,27546,27552,28323,28326,28334,28335,28339,28368,28376,27237,27330,27331,27332,27505,28355,27209,27229,27247,27306,27356,27371,27242,27259,27281,27325,27376,28315,28327,28350,28370,28373,28374,28387,28388,28394,28330,28338,28345,28347,28363,28367,28379,28380,28319,28340,28357,28358,28359,28360,28362,28364,28369,28371,28372,28375,28377,28378,28383,28384,28386,28343,28351,28352,28353,28396,28001,28002,28009,28097,28109,28127,28128,28129,28137,28163,28079,28103,28104,28108,28111,28112,28173,28174);
$zipyellow=array(27806,27808,27810,27814,27817,27821,27860,27865,27889,27805,27847,27849,27872,27924,27957,27967,27983,27921,27974,27976,27932,27980,27916,27917,27923,27927,27929,27939,27941,27947,27950,27956,27958,27964,27965,27966,27973,27915,27920,27936,27943,27948,27949,2795327954,27959,27968,27972,27978,27981,27982,27801,27802,27809,27815,27819,27852,27864,27881,27886,27926,27935,27937,27938,27946,27969,27979,27823,27839,27843,27844,27850,27870,27874,27887,27890,27818,27855,27910,27922,27942,27986,27824,27826,27875,27885,27960,27825,27840,27841,27846,27857,27861,27871,27892,27557,27803,27804,27807,27816,27856,27868,27878,27882,27891,27820,27831,27832,27842,27845,27853,27862,27866,27867,27869,27876,27877,27897,27906,27907,27909,27919,27930,27944,27985,27811,27812,27827,27828,27829,27833,27834,27835,27836,27837,27858,27879,27884,28513,28530,28590,27925,27928,27962,27970);
$ziporange=array(28420,28422,28451,28452,28459,28461,28462,28465,28467,28468,28469,28470,28479,28511,28512,28516,28520,28524,28528,28531,28553,28557,28570,28575,28577,28579,28581,28582,28589,28594,28519,28523,28526,28527,28532,28533,28560,28561,28562,28563,28564,28586,28325,28341,28349,28398,28453,28458,28464,28466,28508,28518,28521,27888,28538,28554,28580,27504,27520,27524,27527,27528,27542,28555,27568,27569,27576,27577,27593,28522,28555,28573,28585,28501,28502,28503,28504,28525,28551,28572,28401,28402,28403,28404,28405,28406,28407,28408,28409,28410,28411,28412,28428,28429,28449,28480,28445,28460,28539,28540,28541,28542,28543,28544,28545,28546,28547,28574,28584,28509,28510,28515,28529,28537,28552,28556,28571,28583,28587,28421,28425,28435,28443,28454,28457,28478,28318,28328,28344,28366,28382,28385,28393,28441,28444,28447,27530,27531,27532,27533,27534,27830,27863,28333,28365,28578,27813,27822,27851,27873,27880,27883,27893,27894,27895,27896);
if ($color=='purple')
{
	$zips=$zippurple;
	}
else if ($color=='red')
	{
		$zips=$zipred;				
		}
else if ($color=='yellow')
{
	$zips=$zipyellow;
	}
else if ($color=='orange')
{
	$zips=$ziporange;
	}
else if ($color=='blue')
{
	$zips=$zipblue;
	}
else if ($color=='green')
{
	$zips=$zipgreen;
}
$numarr=count($zips);
$where = ' zipcode=';
for($i=0; $i<$numarr;  $i++)
{ if ($i==($numarr-1))
   {$where.=$zips[$i].')';}
   else   
	{$where .= $zips[$i]." "."OR"." "."zipcode=";}
	}
if ($color=='all')
{
$result= __do_sql("SELECT MAX(business_partner.url) AS url,members.bname AS business FROM members,business_partner WHERE (btype='RESTAURANT' OR sbtype='RESTAURANT') AND business_partner.fkey=members.pkey AND members.active='Y' AND members.opted_out='N' GROUP BY members.bname");
}
else
{
$result= __do_sql("SELECT MAX(business_partner.url) AS url,members.bname AS business FROM members,business_partner WHERE (btype='RESTAURANT' OR sbtype='RESTAURANT') AND business_partner.fkey=members.pkey AND members.active='Y' AND members.opted_out='N' AND ( ".$where." GROUP BY members.bname");
}
return $result;
}


function __get_restaurants_in_region_by_county($county)
{
$zipcaswell=array(27212,27305,27291,27311,27314,27315,27379);
$ziporange=array(27243,27510,27231,27514,27515,27516,27517,27599,27243,27278);
if ($county=='caswell')
{
	$zips=$zipcaswell;
}
else if ($county=='orange')
{
	$zips=$ziporange;
}
$numarr=count($zips);
$where = ' zipcode=';
for($i=0; $i<$numarr;  $i++)
{ if ($i==($numarr-1))
   {$where.=$zips[$i].')';}
   else   
	{$where .= $zips[$i]." "."OR"." "."zipcode=";}
	}

$result= __do_sql("SELECT MAX(business_partner.url) AS url,members.bname AS business FROM members,business_partner WHERE (btype='RESTAURANT' OR sbtype='RESTAURANT') AND business_partner.fkey=members.pkey AND members.active='Y' AND members.opted_out='N' AND ( ".$where." GROUP BY members.bname");

return $result;
}




function __get_frmmkt_in_region($color)
{
$zippurple=array(28615,28617,28626,28629,28631,28640,28643,28672,28684,28693,28694,28623,28627,28644,28663,28668,28675,27007,27017,27024,27030,27031,27041,27047,27049,27053,28621,28676,28606,28624,28635,28649,28651,28654,28656,28659,28665,28669,28670,28683,28685,28697,27011,27018,27020,27055,28642,28611,28630,28633,28638,28645,28661,28667,28636,28678,28681,28010,28115,28117,28123,28166,28625,28634,28660,28677,28687,28688,28689,28699,27006,27014,27028,27013,27054,28023,28039,28041,28071,28072,28088,28125,28138,28144,28145,28146,28147,28159,28612,28619,28628,28637,28641,28647,28655,28666,28671,28680,28690,28601,28602,28603,28609,28610,28613,28650,28658,28673,28682,28018,28019,28024,28040,28043,28074,28076,28139,28160,28167,28720,28746,28017,28020,28038,28042,28073,28086,28089,28090,28114,28136,28150,28151,28152,28169,28033,28037,28080,28092,28093,28168,28006,28012,28016,28021,28032,28034,28052,28053,28054,28055,28056,28077,28098,28101,28120,28164,28031,28035,28036,28070,28078,28105,28106,28126,28130,28134,28201,28202,28203,28204,28205,28206,28207,28208,28209,28210,28211,28212,28213,28214,28215,28216,28217,28218,28219,28220,28221,28222,28223,28224,28225,28226,28227,28228,28229,28230,28231,28232,28233,28234,28235,28236,28237,28241,28242,28243,28244,28246,28247,28250,28253,28254,28255,28256,28258,28260,28262,28263,28265,28266,28269,28270,28271,28272,28273,28274,28275,28277,28278,28280,28281,28282,28284,28285,28287,28288,28289,28290,28296,28297,28299);
$zipgreen=array(28604,28616,28622,28646,28652,28653,28657,28662,28664,28701,28704,28709,28711,28715,28728,28730,28748,28757,28770,28776,28778,28787,28801,28802,28803,28804,28805,28806,28810,28813,28814,28815,28816,28781,28901,28903,28905,28906,28902,28904,28909,28733,28771,28716,28721,28738,28745,28751,28785,28786,28710,28724,28726,28727,28729,28731,28732,28735,28739,28742,28758,28759,28760,28784,28790,28791,28792,28793,28707,28717,28723,28725,28736,28779,28783,28788,28789,28734,28741,28744,28763,28775,28734,28741,28744,28763,28775,28743,28753,28754,28737,28749,28752,28761,28762,28705,28765,28777,28722,28750,28756,28773,28782,28702,28713,28719,28708,28712,28718,28747,28766,28768,28772,28774,28605,28607,28608,28618,28679,28691,28692,28698,28714,28740,28755);
$zipred=array(27201,27202,27215,27216,27217,27244,27253,27258,27302,27340,27349,27359,27212,27291,27305,27311,27314,27315,27379,27207,27208,27213,27228,27252,27256,27312,27344,27559,27239,28292,27293,28294,27295,27299,27351,27360,27361,27373,27374,27503,27572,27701,27702,27703,27704,27705,27706,27707,27708,27709,27710,27711,27712,27713,27715,27717,27722,27009,27010,27012,27023,27040,27045,27050,27051,27094,27098,27099,27101,27102,27103,27104,27105,27106,27107,27108,27109,27110,27111,27113,27114,27115,27116,27117,27120,27127,27130,27150,27152,27155,27157,27198,27199,27284,27285,27508,27525,27549,27596,27507,27509,27522,27564,27565,27581,27582,27214,27233,27235,27249,27260,27261,27262,27263,27264,27265,27282,27283,27301,27310,27313,27342,27357,27358,27377,27401,27402,27403,27404,27405,27406,27407,27408,27409,27410,27411,27412,27413,27415,27416,27417,27419,27420,27425,27427,27429,27435,27438,27455,27495,27495,27497,27498,27499,27231,27243,27278,27510,27514,27515,27516,27517,27599,27343,27541,27573,27574,27583,27203,27204,27205,27230,27248,27316,27317,27341,27350,27355,27370,27025,27027,27048,27288,27289,27320,27326,27375,27016,27019,27021,27022,27042,27043,27046,27052,27536,27537,27544,27553,27556,27584,27502,27511,27512,27513,27518,27519,27523,27526,27529,27502,27511,27512,27513,27518,27519,27523,27526,27529,27539,27540,27545,27560,27562,27571,27587,27588,27591,27592,27597,27601,27602,27603,27604,27605,27606,27607,27608,27609,27610,27611,27612,27613,27614,27615,27616,27617,27619,27620,27621,27622,27623,27624,27625,27626,27627,27628,27629,27634,27635,27636,27640,27650,27656,27658,27661,27668,27675,27676,27690,27695,27697,27698,27699,27551,27563,27570,27586,27589,27594);
$zipblue=array(28007,28091,28102,28119,28133,28135,28170,28320,28332,28337,28392,28399,28433,28434,28448,28025,28026,28027,28075,28081,28082,28083,28107,28124,28423,28424,28430,28431,28432,28436,28438,28439,28442,28450,28455,28456,28463,28472,28301,28302,28303,28304,28305,28306,28307,28308,28309,28310,28311,28312,28314,28331,28342,28348,28356,28390,28391,28395,27501,27506,27521,27543,27546,27552,28323,28326,28334,28335,28339,28368,28376,27237,27330,27331,27332,27505,28355,27209,27229,27247,27306,27356,27371,27242,27259,27281,27325,27376,28315,28327,28350,28370,28373,28374,28387,28388,28394,28330,28338,28345,28347,28363,28367,28379,28380,28319,28340,28357,28358,28359,28360,28362,28364,28369,28371,28372,28375,28377,28378,28383,28384,28386,28343,28351,28352,28353,28396,28001,28002,28009,28097,28109,28127,28128,28129,28137,28163,28079,28103,28104,28108,28111,28112,28173,28174);
$zipyellow=array(27806,27808,27810,27814,27817,27821,27860,27865,27889,27805,27847,27849,27872,27924,27957,27967,27983,27921,27974,27976,27932,27980,27916,27917,27923,27927,27929,27939,27941,27947,27950,27956,27958,27964,27965,27966,27973,27915,27920,27936,27943,27948,27949,2795327954,27959,27968,27972,27978,27981,27982,27801,27802,27809,27815,27819,27852,27864,27881,27886,27926,27935,27937,27938,27946,27969,27979,27823,27839,27843,27844,27850,27870,27874,27887,27890,27818,27855,27910,27922,27942,27986,27824,27826,27875,27885,27960,27825,27840,27841,27846,27857,27861,27871,27892,27557,27803,27804,27807,27816,27856,27868,27878,27882,27891,27820,27831,27832,27842,27845,27853,27862,27866,27867,27869,27876,27877,27897,27906,27907,27909,27919,27930,27944,27985,27811,27812,27827,27828,27829,27833,27834,27835,27836,27837,27858,27879,27884,28513,28530,28590,27925,27928,27962,27970);
$ziporange=array(28420,28422,28451,28452,28459,28461,28462,28465,28467,28468,28469,28470,28479,28511,28512,28516,28520,28524,28528,28531,28553,28557,28570,28575,28577,28579,28581,28582,28589,28594,28519,28523,28526,28527,28532,28533,28560,28561,28562,28563,28564,28586,28325,28341,28349,28398,28453,28458,28464,28466,28508,28518,28521,27888,28538,28554,28580,27504,27520,27524,27527,27528,27542,28555,27568,27569,27576,27577,27593,28522,28555,28573,28585,28501,28502,28503,28504,28525,28551,28572,28401,28402,28403,28404,28405,28406,28407,28408,28409,28410,28411,28412,28428,28429,28449,28480,28445,28460,28539,28540,28541,28542,28543,28544,28545,28546,28547,28574,28584,28509,28510,28515,28529,28537,28552,28556,28571,28583,28587,28421,28425,28435,28443,28454,28457,28478,28318,28328,28344,28366,28382,28385,28393,28441,28444,28447,27530,27531,27532,27533,27534,27830,27863,28333,28365,28578,27813,27822,27851,27873,27880,27883,27893,27894,27895,27896);
if ($color=='purple')
{
	$zips=$zippurple;
	}
else if ($color=='red')
	{
		$zips=$zipred;				
		}
else if ($color=='yellow')
{
	$zips=$zipyellow;
	}
else if ($color=='orange')
{
	$zips=$ziporange;
	}
else if ($color=='blue')
{
	$zips=$zipblue;
	}
else if ($color=='green')
{
	$zips=$zipgreen;
	}
$numarr=count($zips);
$where = ' zipcode=';
for($i=0; $i<$numarr;  $i++)
{ if ($i==($numarr-1))
   {$where.=$zips[$i].')';}
   else   
	{$where .= $zips[$i]." "."OR"." "."zipcode=";}
	}
if ($color=='all')
{
$result= __do_sql("SELECT MAX(business_partner.url) AS url,members.bname AS business FROM members,business_partner WHERE (btype = 'FRMMKT' OR sbtype='FRMMKT') AND business_partner.fkey=members.pkey AND members.active='Y' AND members.opted_out='N' GROUP BY members.bname");
}
else
{
$result= __do_sql("SELECT MAX(business_partner.url) AS url,members.bname AS business FROM members,business_partner WHERE (btype = 'FRMMKT' OR sbtype='FRMMKT') AND business_partner.fkey=members.pkey AND members.active='Y' AND members.opted_out='N' AND ( ".$where."GROUP BY members.bname");
}
return $result;
}

function __get_groc_in_region($color)
{
$zippurple=array(28615,28617,28626,28629,28631,28640,28643,28672,28684,28693,28694,28623,28627,28644,28663,28668,28675,27007,27017,27024,27030,27031,27041,27047,27049,27053,28621,28676,28606,28624,28635,28649,28651,28654,28656,28659,28665,28669,28670,28683,28685,28697,27011,27018,27020,27055,28642,28611,28630,28633,28638,28645,28661,28667,28636,28678,28681,28010,28115,28117,28123,28166,28625,28634,28660,28677,28687,28688,28689,28699,27006,27014,27028,27013,27054,28023,28039,28041,28071,28072,28088,28125,28138,28144,28145,28146,28147,28159,28612,28619,28628,28637,28641,28647,28655,28666,28671,28680,28690,28601,28602,28603,28609,28610,28613,28650,28658,28673,28682,28018,28019,28024,28040,28043,28074,28076,28139,28160,28167,28720,28746,28017,28020,28038,28042,28073,28086,28089,28090,28114,28136,28150,28151,28152,28169,28033,28037,28080,28092,28093,28168,28006,28012,28016,28021,28032,28034,28052,28053,28054,28055,28056,28077,28098,28101,28120,28164,28031,28035,28036,28070,28078,28105,28106,28126,28130,28134,28201,28202,28203,28204,28205,28206,28207,28208,28209,28210,28211,28212,28213,28214,28215,28216,28217,28218,28219,28220,28221,28222,28223,28224,28225,28226,28227,28228,28229,28230,28231,28232,28233,28234,28235,28236,28237,28241,28242,28243,28244,28246,28247,28250,28253,28254,28255,28256,28258,28260,28262,28263,28265,28266,28269,28270,28271,28272,28273,28274,28275,28277,28278,28280,28281,28282,28284,28285,28287,28288,28289,28290,28296,28297,28299);
$zipgreen=array(28604,28616,28622,28646,28652,28653,28657,28662,28664,28701,28704,28709,28711,28715,28728,28730,28748,28757,28770,28776,28778,28787,28801,28802,28803,28804,28805,28806,28810,28813,28814,28815,28816,28781,28901,28903,28905,28906,28902,28904,28909,28733,28771,28716,28721,28738,28745,28751,28785,28786,28710,28724,28726,28727,28729,28731,28732,28735,28739,28742,28758,28759,28760,28784,28790,28791,28792,28793,28707,28717,28723,28725,28736,28779,28783,28788,28789,28734,28741,28744,28763,28775,28734,28741,28744,28763,28775,28743,28753,28754,28737,28749,28752,28761,28762,28705,28765,28777,28722,28750,28756,28773,28782,28702,28713,28719,28708,28712,28718,28747,28766,28768,28772,28774,28605,28607,28608,28618,28679,28691,28692,28698,28714,28740,28755);
$zipred=array(27201,27202,27215,27216,27217,27244,27253,27258,27302,27340,27349,27359,27212,27291,27305,27311,27314,27315,27379,27207,27208,27213,27228,27252,27256,27312,27344,27559,27239,28292,27293,28294,27295,27299,27351,27360,27361,27373,27374,27503,27572,27701,27702,27703,27704,27705,27706,27707,27708,27709,27710,27711,27712,27713,27715,27717,27722,27009,27010,27012,27023,27040,27045,27050,27051,27094,27098,27099,27101,27102,27103,27104,27105,27106,27107,27108,27109,27110,27111,27113,27114,27115,27116,27117,27120,27127,27130,27150,27152,27155,27157,27198,27199,27284,27285,27508,27525,27549,27596,27507,27509,27522,27564,27565,27581,27582,27214,27233,27235,27249,27260,27261,27262,27263,27264,27265,27282,27283,27301,27310,27313,27342,27357,27358,27377,27401,27402,27403,27404,27405,27406,27407,27408,27409,27410,27411,27412,27413,27415,27416,27417,27419,27420,27425,27427,27429,27435,27438,27455,27495,27495,27497,27498,27499,27231,27243,27278,27510,27514,27515,27516,27517,27599,27343,27541,27573,27574,27583,27203,27204,27205,27230,27248,27316,27317,27341,27350,27355,27370,27025,27027,27048,27288,27289,27320,27326,27375,27016,27019,27021,27022,27042,27043,27046,27052,27536,27537,27544,27553,27556,27584,27502,27511,27512,27513,27518,27519,27523,27526,27529,27502,27511,27512,27513,27518,27519,27523,27526,27529,27539,27540,27545,27560,27562,27571,27587,27588,27591,27592,27597,27601,27602,27603,27604,27605,27606,27607,27608,27609,27610,27611,27612,27613,27614,27615,27616,27617,27619,27620,27621,27622,27623,27624,27625,27626,27627,27628,27629,27634,27635,27636,27640,27650,27656,27658,27661,27668,27675,27676,27690,27695,27697,27698,27699,27551,27563,27570,27586,27589,27594);
$zipblue=array(28007,28091,28102,28119,28133,28135,28170,28320,28332,28337,28392,28399,28433,28434,28448,28025,28026,28027,28075,28081,28082,28083,28107,28124,28423,28424,28430,28431,28432,28436,28438,28439,28442,28450,28455,28456,28463,28472,28301,28302,28303,28304,28305,28306,28307,28308,28309,28310,28311,28312,28314,28331,28342,28348,28356,28390,28391,28395,27501,27506,27521,27543,27546,27552,28323,28326,28334,28335,28339,28368,28376,27237,27330,27331,27332,27505,28355,27209,27229,27247,27306,27356,27371,27242,27259,27281,27325,27376,28315,28327,28350,28370,28373,28374,28387,28388,28394,28330,28338,28345,28347,28363,28367,28379,28380,28319,28340,28357,28358,28359,28360,28362,28364,28369,28371,28372,28375,28377,28378,28383,28384,28386,28343,28351,28352,28353,28396,28001,28002,28009,28097,28109,28127,28128,28129,28137,28163,28079,28103,28104,28108,28111,28112,28173,28174);
$zipyellow=array(27806,27808,27810,27814,27817,27821,27860,27865,27889,27805,27847,27849,27872,27924,27957,27967,27983,27921,27974,27976,27932,27980,27916,27917,27923,27927,27929,27939,27941,27947,27950,27956,27958,27964,27965,27966,27973,27915,27920,27936,27943,27948,27949,2795327954,27959,27968,27972,27978,27981,27982,27801,27802,27809,27815,27819,27852,27864,27881,27886,27926,27935,27937,27938,27946,27969,27979,27823,27839,27843,27844,27850,27870,27874,27887,27890,27818,27855,27910,27922,27942,27986,27824,27826,27875,27885,27960,27825,27840,27841,27846,27857,27861,27871,27892,27557,27803,27804,27807,27816,27856,27868,27878,27882,27891,27820,27831,27832,27842,27845,27853,27862,27866,27867,27869,27876,27877,27897,27906,27907,27909,27919,27930,27944,27985,27811,27812,27827,27828,27829,27833,27834,27835,27836,27837,27858,27879,27884,28513,28530,28590,27925,27928,27962,27970);
$ziporange=array(28420,28422,28451,28452,28459,28461,28462,28465,28467,28468,28469,28470,28479,28511,28512,28516,28520,28524,28528,28531,28553,28557,28570,28575,28577,28579,28581,28582,28589,28594,28519,28523,28526,28527,28532,28533,28560,28561,28562,28563,28564,28586,28325,28341,28349,28398,28453,28458,28464,28466,28508,28518,28521,27888,28538,28554,28580,27504,27520,27524,27527,27528,27542,28555,27568,27569,27576,27577,27593,28522,28555,28573,28585,28501,28502,28503,28504,28525,28551,28572,28401,28402,28403,28404,28405,28406,28407,28408,28409,28410,28411,28412,28428,28429,28449,28480,28445,28460,28539,28540,28541,28542,28543,28544,28545,28546,28547,28574,28584,28509,28510,28515,28529,28537,28552,28556,28571,28583,28587,28421,28425,28435,28443,28454,28457,28478,28318,28328,28344,28366,28382,28385,28393,28441,28444,28447,27530,27531,27532,27533,27534,27830,27863,28333,28365,28578,27813,27822,27851,27873,27880,27883,27893,27894,27895,27896);
if ($color=='purple')
{
	$zips=$zippurple;
	}
else if ($color=='red')
	{
		$zips=$zipred;				
		}
else if ($color=='yellow')
{
	$zips=$zipyellow;
	}
else if ($color=='orange')
{
	$zips=$ziporange;
	}
else if ($color=='blue')
{
	$zips=$zipblue;
	}
else if ($color=='green')
{
	$zips=$zipgreen;
	}
$numarr=count($zips);
$where = ' zipcode=';
for($i=0; $i<$numarr;  $i++)
{ if ($i==($numarr-1))
   {$where.=$zips[$i].')';}
   else   
	{$where .= $zips[$i]." "."OR"." "."zipcode=";}
	}
if ($color=='all')
{
$result= __do_sql("SELECT MAX(business_partner.url) AS url,members.bname AS business FROM members,business_partner WHERE (btype = 'RETAIL-GRO' OR btype = 'FOODSERV' OR sbtype='RETAIL-GRO' OR sbtype='FOODSERV') AND business_partner.fkey=members.pkey AND members.active='Y' AND members.opted_out='N' GROUP BY members.bname");
}
else
{
$result= __do_sql("SELECT MAX(business_partner.url) AS url,members.bname AS business FROM members,business_partner WHERE (btype = 'RETAIL-GRO' OR btype = 'FOODSERV' OR sbtype='RETAIL-GRO' OR sbtype='FOODSERV') AND business_partner.fkey=members.pkey AND members.active='Y' AND members.opted_out='N' AND ( ".$where."GROUP BY members.bname");
}
return $result;
}

function __get_other_in_region($color)
{
$zippurple=array(28615,28617,28626,28629,28631,28640,28643,28672,28684,28693,28694,28623,28627,28644,28663,28668,28675,27007,27017,27024,27030,27031,27041,27047,27049,27053,28621,28676,28606,28624,28635,28649,28651,28654,28656,28659,28665,28669,28670,28683,28685,28697,27011,27018,27020,27055,28642,28611,28630,28633,28638,28645,28661,28667,28636,28678,28681,28010,28115,28117,28123,28166,28625,28634,28660,28677,28687,28688,28689,28699,27006,27014,27028,27013,27054,28023,28039,28041,28071,28072,28088,28125,28138,28144,28145,28146,28147,28159,28612,28619,28628,28637,28641,28647,28655,28666,28671,28680,28690,28601,28602,28603,28609,28610,28613,28650,28658,28673,28682,28018,28019,28024,28040,28043,28074,28076,28139,28160,28167,28720,28746,28017,28020,28038,28042,28073,28086,28089,28090,28114,28136,28150,28151,28152,28169,28033,28037,28080,28092,28093,28168,28006,28012,28016,28021,28032,28034,28052,28053,28054,28055,28056,28077,28098,28101,28120,28164,28031,28035,28036,28070,28078,28105,28106,28126,28130,28134,28201,28202,28203,28204,28205,28206,28207,28208,28209,28210,28211,28212,28213,28214,28215,28216,28217,28218,28219,28220,28221,28222,28223,28224,28225,28226,28227,28228,28229,28230,28231,28232,28233,28234,28235,28236,28237,28241,28242,28243,28244,28246,28247,28250,28253,28254,28255,28256,28258,28260,28262,28263,28265,28266,28269,28270,28271,28272,28273,28274,28275,28277,28278,28280,28281,28282,28284,28285,28287,28288,28289,28290,28296,28297,28299);
$zipgreen=array(28604,28616,28622,28646,28652,28653,28657,28662,28664,28701,28704,28709,28711,28715,28728,28730,28748,28757,28770,28776,28778,28787,28801,28802,28803,28804,28805,28806,28810,28813,28814,28815,28816,28781,28901,28903,28905,28906,28902,28904,28909,28733,28771,28716,28721,28738,28745,28751,28785,28786,28710,28724,28726,28727,28729,28731,28732,28735,28739,28742,28758,28759,28760,28784,28790,28791,28792,28793,28707,28717,28723,28725,28736,28779,28783,28788,28789,28734,28741,28744,28763,28775,28734,28741,28744,28763,28775,28743,28753,28754,28737,28749,28752,28761,28762,28705,28765,28777,28722,28750,28756,28773,28782,28702,28713,28719,28708,28712,28718,28747,28766,28768,28772,28774,28605,28607,28608,28618,28679,28691,28692,28698,28714,28740,28755);
$zipred=array(27201,27202,27215,27216,27217,27244,27253,27258,27302,27340,27349,27359,27212,27291,27305,27311,27314,27315,27379,27207,27208,27213,27228,27252,27256,27312,27344,27559,27239,28292,27293,28294,27295,27299,27351,27360,27361,27373,27374,27503,27572,27701,27702,27703,27704,27705,27706,27707,27708,27709,27710,27711,27712,27713,27715,27717,27722,27009,27010,27012,27023,27040,27045,27050,27051,27094,27098,27099,27101,27102,27103,27104,27105,27106,27107,27108,27109,27110,27111,27113,27114,27115,27116,27117,27120,27127,27130,27150,27152,27155,27157,27198,27199,27284,27285,27508,27525,27549,27596,27507,27509,27522,27564,27565,27581,27582,27214,27233,27235,27249,27260,27261,27262,27263,27264,27265,27282,27283,27301,27310,27313,27342,27357,27358,27377,27401,27402,27403,27404,27405,27406,27407,27408,27409,27410,27411,27412,27413,27415,27416,27417,27419,27420,27425,27427,27429,27435,27438,27455,27495,27495,27497,27498,27499,27231,27243,27278,27510,27514,27515,27516,27517,27599,27343,27541,27573,27574,27583,27203,27204,27205,27230,27248,27316,27317,27341,27350,27355,27370,27025,27027,27048,27288,27289,27320,27326,27375,27016,27019,27021,27022,27042,27043,27046,27052,27536,27537,27544,27553,27556,27584,27502,27511,27512,27513,27518,27519,27523,27526,27529,27502,27511,27512,27513,27518,27519,27523,27526,27529,27539,27540,27545,27560,27562,27571,27587,27588,27591,27592,27597,27601,27602,27603,27604,27605,27606,27607,27608,27609,27610,27611,27612,27613,27614,27615,27616,27617,27619,27620,27621,27622,27623,27624,27625,27626,27627,27628,27629,27634,27635,27636,27640,27650,27656,27658,27661,27668,27675,27676,27690,27695,27697,27698,27699,27551,27563,27570,27586,27589,27594);
$zipblue=array(28007,28091,28102,28119,28133,28135,28170,28320,28332,28337,28392,28399,28433,28434,28448,28025,28026,28027,28075,28081,28082,28083,28107,28124,28423,28424,28430,28431,28432,28436,28438,28439,28442,28450,28455,28456,28463,28472,28301,28302,28303,28304,28305,28306,28307,28308,28309,28310,28311,28312,28314,28331,28342,28348,28356,28390,28391,28395,27501,27506,27521,27543,27546,27552,28323,28326,28334,28335,28339,28368,28376,27237,27330,27331,27332,27505,28355,27209,27229,27247,27306,27356,27371,27242,27259,27281,27325,27376,28315,28327,28350,28370,28373,28374,28387,28388,28394,28330,28338,28345,28347,28363,28367,28379,28380,28319,28340,28357,28358,28359,28360,28362,28364,28369,28371,28372,28375,28377,28378,28383,28384,28386,28343,28351,28352,28353,28396,28001,28002,28009,28097,28109,28127,28128,28129,28137,28163,28079,28103,28104,28108,28111,28112,28173,28174);
$zipyellow=array(27806,27808,27810,27814,27817,27821,27860,27865,27889,27805,27847,27849,27872,27924,27957,27967,27983,27921,27974,27976,27932,27980,27916,27917,27923,27927,27929,27939,27941,27947,27950,27956,27958,27964,27965,27966,27973,27915,27920,27936,27943,27948,27949,2795327954,27959,27968,27972,27978,27981,27982,27801,27802,27809,27815,27819,27852,27864,27881,27886,27926,27935,27937,27938,27946,27969,27979,27823,27839,27843,27844,27850,27870,27874,27887,27890,27818,27855,27910,27922,27942,27986,27824,27826,27875,27885,27960,27825,27840,27841,27846,27857,27861,27871,27892,27557,27803,27804,27807,27816,27856,27868,27878,27882,27891,27820,27831,27832,27842,27845,27853,27862,27866,27867,27869,27876,27877,27897,27906,27907,27909,27919,27930,27944,27985,27811,27812,27827,27828,27829,27833,27834,27835,27836,27837,27858,27879,27884,28513,28530,28590,27925,27928,27962,27970);
$ziporange=array(28420,28422,28451,28452,28459,28461,28462,28465,28467,28468,28469,28470,28479,28511,28512,28516,28520,28524,28528,28531,28553,28557,28570,28575,28577,28579,28581,28582,28589,28594,28519,28523,28526,28527,28532,28533,28560,28561,28562,28563,28564,28586,28325,28341,28349,28398,28453,28458,28464,28466,28508,28518,28521,27888,28538,28554,28580,27504,27520,27524,27527,27528,27542,28555,27568,27569,27576,27577,27593,28522,28555,28573,28585,28501,28502,28503,28504,28525,28551,28572,28401,28402,28403,28404,28405,28406,28407,28408,28409,28410,28411,28412,28428,28429,28449,28480,28445,28460,28539,28540,28541,28542,28543,28544,28545,28546,28547,28574,28584,28509,28510,28515,28529,28537,28552,28556,28571,28583,28587,28421,28425,28435,28443,28454,28457,28478,28318,28328,28344,28366,28382,28385,28393,28441,28444,28447,27530,27531,27532,27533,27534,27830,27863,28333,28365,28578,27813,27822,27851,27873,27880,27883,27893,27894,27895,27896);
if ($color=='purple')
{
	$zips=$zippurple;
	}
else if ($color=='red')
	{
		$zips=$zipred;				
		}
else if ($color=='yellow')
{
	$zips=$zipyellow;
	}
else if ($color=='orange')
{
	$zips=$ziporange;
	}
else if ($color=='blue')
{
	$zips=$zipblue;
	}
else if ($color=='green')
{
	$zips=$zipgreen;
	}
$numarr=count($zips);
$where = ' zipcode=';
for($i=0; $i<$numarr;  $i++)
{ if ($i==($numarr-1))
   {$where.=$zips[$i].')';}
   else   
	{$where .= $zips[$i]." "."OR"." "."zipcode=";}
	}
if ($color=='all')
{
$result= __do_sql("SELECT MAX(business_partner.url) AS url,members.bname AS business FROM members,business_partner WHERE (btype = 'OTHER' OR sbtype='OTHER') AND business_partner.fkey=members.pkey AND members.active='Y' AND members.opted_out='N' GROUP BY members.bname");
}
else
{
$result= __do_sql("SELECT MAX(business_partner.url) AS url,members.bname AS business FROM members,business_partner WHERE (btype='OTHER' OR sbtype='OTHER') AND business_partner.fkey=members.pkey AND members.active='Y' AND members.opted_out='N' AND ( ".$where."GROUP BY members.bname");
}
return $result;
}

function __get_farms_in_region($color)
{
$zippurple=array(28615,28617,28626,28629,28631,28640,28643,28672,28684,28693,28694,28623,28627,28644,28663,28668,28675,27007,27017,27024,27030,27031,27041,27047,27049,27053,28621,28676,28606,28624,28635,28649,28651,28654,28656,28659,28665,28669,28670,28683,28685,28697,27011,27018,27020,27055,28642,28611,28630,28633,28638,28645,28661,28667,28636,28678,28681,28010,28115,28117,28123,28166,28625,28634,28660,28677,28687,28688,28689,28699,27006,27014,27028,27013,27054,28023,28039,28041,28071,28072,28088,28125,28138,28144,28145,28146,28147,28159,28612,28619,28628,28637,28641,28647,28655,28666,28671,28680,28690,28601,28602,28603,28609,28610,28613,28650,28658,28673,28682,28018,28019,28024,28040,28043,28074,28076,28139,28160,28167,28720,28746,28017,28020,28038,28042,28073,28086,28089,28090,28114,28136,28150,28151,28152,28169,28033,28037,28080,28092,28093,28168,28006,28012,28016,28021,28032,28034,28052,28053,28054,28055,28056,28077,28098,28101,28120,28164,28031,28035,28036,28070,28078,28105,28106,28126,28130,28134,28201,28202,28203,28204,28205,28206,28207,28208,28209,28210,28211,28212,28213,28214,28215,28216,28217,28218,28219,28220,28221,28222,28223,28224,28225,28226,28227,28228,28229,28230,28231,28232,28233,28234,28235,28236,28237,28241,28242,28243,28244,28246,28247,28250,28253,28254,28255,28256,28258,28260,28262,28263,28265,28266,28269,28270,28271,28272,28273,28274,28275,28277,28278,28280,28281,28282,28284,28285,28287,28288,28289,28290,28296,28297,28299);
$zipgreen=array(28604,28616,28622,28646,28652,28653,28657,28662,28664,28701,28704,28709,28711,28715,28728,28730,28748,28757,28770,28776,28778,28787,28801,28802,28803,28804,28805,28806,28810,28813,28814,28815,28816,28781,28901,28903,28905,28906,28902,28904,28909,28733,28771,28716,28721,28738,28745,28751,28785,28786,28710,28724,28726,28727,28729,28731,28732,28735,28739,28742,28758,28759,28760,28784,28790,28791,28792,28793,28707,28717,28723,28725,28736,28779,28783,28788,28789,28734,28741,28744,28763,28775,28734,28741,28744,28763,28775,28743,28753,28754,28737,28749,28752,28761,28762,28705,28765,28777,28722,28750,28756,28773,28782,28702,28713,28719,28708,28712,28718,28747,28766,28768,28772,28774,28605,28607,28608,28618,28679,28691,28692,28698,28714,28740,28755);
$zipred=array(27201,27202,27215,27216,27217,27244,27253,27258,27302,27340,27349,27359,27212,27291,27305,27311,27314,27315,27379,27207,27208,27213,27228,27252,27256,27312,27344,27559,27239,28292,27293,28294,27295,27299,27351,27360,27361,27373,27374,27503,27572,27701,27702,27703,27704,27705,27706,27707,27708,27709,27710,27711,27712,27713,27715,27717,27722,27009,27010,27012,27023,27040,27045,27050,27051,27094,27098,27099,27101,27102,27103,27104,27105,27106,27107,27108,27109,27110,27111,27113,27114,27115,27116,27117,27120,27127,27130,27150,27152,27155,27157,27198,27199,27284,27285,27508,27525,27549,27596,27507,27509,27522,27564,27565,27581,27582,27214,27233,27235,27249,27260,27261,27262,27263,27264,27265,27282,27283,27301,27310,27313,27342,27357,27358,27377,27401,27402,27403,27404,27405,27406,27407,27408,27409,27410,27411,27412,27413,27415,27416,27417,27419,27420,27425,27427,27429,27435,27438,27455,27495,27495,27497,27498,27499,27231,27243,27278,27510,27514,27515,27516,27517,27599,27343,27541,27573,27574,27583,27203,27204,27205,27230,27248,27316,27317,27341,27350,27355,27370,27025,27027,27048,27288,27289,27320,27326,27375,27016,27019,27021,27022,27042,27043,27046,27052,27536,27537,27544,27553,27556,27584,27502,27511,27512,27513,27518,27519,27523,27526,27529,27502,27511,27512,27513,27518,27519,27523,27526,27529,27539,27540,27545,27560,27562,27571,27587,27588,27591,27592,27597,27601,27602,27603,27604,27605,27606,27607,27608,27609,27610,27611,27612,27613,27614,27615,27616,27617,27619,27620,27621,27622,27623,27624,27625,27626,27627,27628,27629,27634,27635,27636,27640,27650,27656,27658,27661,27668,27675,27676,27690,27695,27697,27698,27699,27551,27563,27570,27586,27589,27594);
$zipblue=array(28007,28091,28102,28119,28133,28135,28170,28320,28332,28337,28392,28399,28433,28434,28448,28025,28026,28027,28075,28081,28082,28083,28107,28124,28423,28424,28430,28431,28432,28436,28438,28439,28442,28450,28455,28456,28463,28472,28301,28302,28303,28304,28305,28306,28307,28308,28309,28310,28311,28312,28314,28331,28342,28348,28356,28390,28391,28395,27501,27506,27521,27543,27546,27552,28323,28326,28334,28335,28339,28368,28376,27237,27330,27331,27332,27505,28355,27209,27229,27247,27306,27356,27371,27242,27259,27281,27325,27376,28315,28327,28350,28370,28373,28374,28387,28388,28394,28330,28338,28345,28347,28363,28367,28379,28380,28319,28340,28357,28358,28359,28360,28362,28364,28369,28371,28372,28375,28377,28378,28383,28384,28386,28343,28351,28352,28353,28396,28001,28002,28009,28097,28109,28127,28128,28129,28137,28163,28079,28103,28104,28108,28111,28112,28173,28174);
$zipyellow=array(27806,27808,27810,27814,27817,27821,27860,27865,27889,27805,27847,27849,27872,27924,27957,27967,27983,27921,27974,27976,27932,27980,27916,27917,27923,27927,27929,27939,27941,27947,27950,27956,27958,27964,27965,27966,27973,27915,27920,27936,27943,27948,27949,2795327954,27959,27968,27972,27978,27981,27982,27801,27802,27809,27815,27819,27852,27864,27881,27886,27926,27935,27937,27938,27946,27969,27979,27823,27839,27843,27844,27850,27870,27874,27887,27890,27818,27855,27910,27922,27942,27986,27824,27826,27875,27885,27960,27825,27840,27841,27846,27857,27861,27871,27892,27557,27803,27804,27807,27816,27856,27868,27878,27882,27891,27820,27831,27832,27842,27845,27853,27862,27866,27867,27869,27876,27877,27897,27906,27907,27909,27919,27930,27944,27985,27811,27812,27827,27828,27829,27833,27834,27835,27836,27837,27858,27879,27884,28513,28530,28590,27925,27928,27962,27970);
$ziporange=array(28420,28422,28451,28452,28459,28461,28462,28465,28467,28468,28469,28470,28479,28511,28512,28516,28520,28524,28528,28531,28553,28557,28570,28575,28577,28579,28581,28582,28589,28594,28519,28523,28526,28527,28532,28533,28560,28561,28562,28563,28564,28586,28325,28341,28349,28398,28453,28458,28464,28466,28508,28518,28521,27888,28538,28554,28580,27504,27520,27524,27527,27528,27542,28555,27568,27569,27576,27577,27593,28522,28555,28573,28585,28501,28502,28503,28504,28525,28551,28572,28401,28402,28403,28404,28405,28406,28407,28408,28409,28410,28411,28412,28428,28429,28449,28480,28445,28460,28539,28540,28541,28542,28543,28544,28545,28546,28547,28574,28584,28509,28510,28515,28529,28537,28552,28556,28571,28583,28587,28421,28425,28435,28443,28454,28457,28478,28318,28328,28344,28366,28382,28385,28393,28441,28444,28447,27530,27531,27532,27533,27534,27830,27863,28333,28365,28578,27813,27822,27851,27873,27880,27883,27893,27894,27895,27896);
if ($color=='purple')
{
	$zips=$zippurple;
	}
else if ($color=='red')
	{
		$zips=$zipred;				
		}
else if ($color=='yellow')
{
	$zips=$zipyellow;
	}
else if ($color=='orange')
{
	$zips=$ziporange;
	}
else if ($color=='blue')
{
	$zips=$zipblue;
	}
else if ($color=='green')
{
	$zips=$zipgreen;
	}
$numarr=count($zips);
$where = ' zipcode=';
for($i=0; $i<$numarr;  $i++)
{ if ($i==($numarr-1))
   {$where.=$zips[$i].')';}
   else   
	{$where .= $zips[$i]." "."OR"." "."zipcode=";}
	}
if ($color=='all')
{
$result= __do_sql("SELECT MAX(business_partner.url) AS url, members.bname AS business FROM members,business_partner WHERE (btype='FARM' OR sbtype='FARM') AND members.pkey=business_partner.fkey AND members.active='Y' AND members.opted_out='N' GROUP BY members.bname");
}
else
{
$result= __do_sql("SELECT MAX(business_partner.url) AS url, members.bname AS business FROM members,business_partner WHERE (btype='FARM' OR sbtype='FARM') AND members.pkey=business_partner.fkey AND members.active='Y' AND members.opted_out='N' AND ( ".$where."GROUP BY members.bname");
}
return $result;
}

function __get_community_in_region($color)
{
$zippurple=array(28615,28617,28626,28629,28631,28640,28643,28672,28684,28693,28694,28623,28627,28644,28663,28668,28675,27007,27017,27024,27030,27031,27041,27047,27049,27053,28621,28676,28606,28624,28635,28649,28651,28654,28656,28659,28665,28669,28670,28683,28685,28697,27011,27018,27020,27055,28642,28611,28630,28633,28638,28645,28661,28667,28636,28678,28681,28010,28115,28117,28123,28166,28625,28634,28660,28677,28687,28688,28689,28699,27006,27014,27028,27013,27054,28023,28039,28041,28071,28072,28088,28125,28138,28144,28145,28146,28147,28159,28612,28619,28628,28637,28641,28647,28655,28666,28671,28680,28690,28601,28602,28603,28609,28610,28613,28650,28658,28673,28682,28018,28019,28024,28040,28043,28074,28076,28139,28160,28167,28720,28746,28017,28020,28038,28042,28073,28086,28089,28090,28114,28136,28150,28151,28152,28169,28033,28037,28080,28092,28093,28168,28006,28012,28016,28021,28032,28034,28052,28053,28054,28055,28056,28077,28098,28101,28120,28164,28031,28035,28036,28070,28078,28105,28106,28126,28130,28134,28201,28202,28203,28204,28205,28206,28207,28208,28209,28210,28211,28212,28213,28214,28215,28216,28217,28218,28219,28220,28221,28222,28223,28224,28225,28226,28227,28228,28229,28230,28231,28232,28233,28234,28235,28236,28237,28241,28242,28243,28244,28246,28247,28250,28253,28254,28255,28256,28258,28260,28262,28263,28265,28266,28269,28270,28271,28272,28273,28274,28275,28277,28278,28280,28281,28282,28284,28285,28287,28288,28289,28290,28296,28297,28299);
$zipgreen=array(28604,28616,28622,28646,28652,28653,28657,28662,28664,28701,28704,28709,28711,28715,28728,28730,28748,28757,28770,28776,28778,28787,28801,28802,28803,28804,28805,28806,28810,28813,28814,28815,28816,28781,28901,28903,28905,28906,28902,28904,28909,28733,28771,28716,28721,28738,28745,28751,28785,28786,28710,28724,28726,28727,28729,28731,28732,28735,28739,28742,28758,28759,28760,28784,28790,28791,28792,28793,28707,28717,28723,28725,28736,28779,28783,28788,28789,28734,28741,28744,28763,28775,28734,28741,28744,28763,28775,28743,28753,28754,28737,28749,28752,28761,28762,28705,28765,28777,28722,28750,28756,28773,28782,28702,28713,28719,28708,28712,28718,28747,28766,28768,28772,28774,28605,28607,28608,28618,28679,28691,28692,28698,28714,28740,28755);
$zipred=array(27201,27202,27215,27216,27217,27244,27253,27258,27302,27340,27349,27359,27212,27291,27305,27311,27314,27315,27379,27207,27208,27213,27228,27252,27256,27312,27344,27559,27239,28292,27293,28294,27295,27299,27351,27360,27361,27373,27374,27503,27572,27701,27702,27703,27704,27705,27706,27707,27708,27709,27710,27711,27712,27713,27715,27717,27722,27009,27010,27012,27023,27040,27045,27050,27051,27094,27098,27099,27101,27102,27103,27104,27105,27106,27107,27108,27109,27110,27111,27113,27114,27115,27116,27117,27120,27127,27130,27150,27152,27155,27157,27198,27199,27284,27285,27508,27525,27549,27596,27507,27509,27522,27564,27565,27581,27582,27214,27233,27235,27249,27260,27261,27262,27263,27264,27265,27282,27283,27301,27310,27313,27342,27357,27358,27377,27401,27402,27403,27404,27405,27406,27407,27408,27409,27410,27411,27412,27413,27415,27416,27417,27419,27420,27425,27427,27429,27435,27438,27455,27495,27495,27497,27498,27499,27231,27243,27278,27510,27514,27515,27516,27517,27599,27343,27541,27573,27574,27583,27203,27204,27205,27230,27248,27316,27317,27341,27350,27355,27370,27025,27027,27048,27288,27289,27320,27326,27375,27016,27019,27021,27022,27042,27043,27046,27052,27536,27537,27544,27553,27556,27584,27502,27511,27512,27513,27518,27519,27523,27526,27529,27502,27511,27512,27513,27518,27519,27523,27526,27529,27539,27540,27545,27560,27562,27571,27587,27588,27591,27592,27597,27601,27602,27603,27604,27605,27606,27607,27608,27609,27610,27611,27612,27613,27614,27615,27616,27617,27619,27620,27621,27622,27623,27624,27625,27626,27627,27628,27629,27634,27635,27636,27640,27650,27656,27658,27661,27668,27675,27676,27690,27695,27697,27698,27699,27551,27563,27570,27586,27589,27594);
$zipblue=array(28007,28091,28102,28119,28133,28135,28170,28320,28332,28337,28392,28399,28433,28434,28448,28025,28026,28027,28075,28081,28082,28083,28107,28124,28423,28424,28430,28431,28432,28436,28438,28439,28442,28450,28455,28456,28463,28472,28301,28302,28303,28304,28305,28306,28307,28308,28309,28310,28311,28312,28314,28331,28342,28348,28356,28390,28391,28395,27501,27506,27521,27543,27546,27552,28323,28326,28334,28335,28339,28368,28376,27237,27330,27331,27332,27505,28355,27209,27229,27247,27306,27356,27371,27242,27259,27281,27325,27376,28315,28327,28350,28370,28373,28374,28387,28388,28394,28330,28338,28345,28347,28363,28367,28379,28380,28319,28340,28357,28358,28359,28360,28362,28364,28369,28371,28372,28375,28377,28378,28383,28384,28386,28343,28351,28352,28353,28396,28001,28002,28009,28097,28109,28127,28128,28129,28137,28163,28079,28103,28104,28108,28111,28112,28173,28174);
$zipyellow=array(27806,27808,27810,27814,27817,27821,27860,27865,27889,27805,27847,27849,27872,27924,27957,27967,27983,27921,27974,27976,27932,27980,27916,27917,27923,27927,27929,27939,27941,27947,27950,27956,27958,27964,27965,27966,27973,27915,27920,27936,27943,27948,27949,2795327954,27959,27968,27972,27978,27981,27982,27801,27802,27809,27815,27819,27852,27864,27881,27886,27926,27935,27937,27938,27946,27969,27979,27823,27839,27843,27844,27850,27870,27874,27887,27890,27818,27855,27910,27922,27942,27986,27824,27826,27875,27885,27960,27825,27840,27841,27846,27857,27861,27871,27892,27557,27803,27804,27807,27816,27856,27868,27878,27882,27891,27820,27831,27832,27842,27845,27853,27862,27866,27867,27869,27876,27877,27897,27906,27907,27909,27919,27930,27944,27985,27811,27812,27827,27828,27829,27833,27834,27835,27836,27837,27858,27879,27884,28513,28530,28590,27925,27928,27962,27970);
$ziporange=array(28420,28422,28451,28452,28459,28461,28462,28465,28467,28468,28469,28470,28479,28511,28512,28516,28520,28524,28528,28531,28553,28557,28570,28575,28577,28579,28581,28582,28589,28594,28519,28523,28526,28527,28532,28533,28560,28561,28562,28563,28564,28586,28325,28341,28349,28398,28453,28458,28464,28466,28508,28518,28521,27888,28538,28554,28580,27504,27520,27524,27527,27528,27542,28555,27568,27569,27576,27577,27593,28522,28555,28573,28585,28501,28502,28503,28504,28525,28551,28572,28401,28402,28403,28404,28405,28406,28407,28408,28409,28410,28411,28412,28428,28429,28449,28480,28445,28460,28539,28540,28541,28542,28543,28544,28545,28546,28547,28574,28584,28509,28510,28515,28529,28537,28552,28556,28571,28583,28587,28421,28425,28435,28443,28454,28457,28478,28318,28328,28344,28366,28382,28385,28393,28441,28444,28447,27530,27531,27532,27533,27534,27830,27863,28333,28365,28578,27813,27822,27851,27873,27880,27883,27893,27894,27895,27896);
if ($color=='purple')
{
	$zips=$zippurple;
	}
else if ($color=='red')
	{
		$zips=$zipred;				
		}
else if ($color=='yellow')
{
	$zips=$zipyellow;
	}
else if ($color=='orange')
{
	$zips=$ziporange;
	}
else if ($color=='blue')
{
	$zips=$zipblue;
	}
else if ($color=='green')
{
	$zips=$zipgreen;
	}$numarr=count($zips);
$where = ' zipcode=';
for($i=0; $i<$numarr;  $i++)
{ if ($i==($numarr-1))
   {$where.=$zips[$i].')';}
   else   
	{$where .= $zips[$i]." "."OR"." "."zipcode=";}
}
if ($color=='all')
{
$result= __do_sql("SELECT MAX(business_partner.url) AS url,members.bname AS business FROM members,business_partner WHERE (btype='COMMUNITY-' OR btype='NONPROFIT' OR btype='CORPORATIO' OR btype='OTHER' OR btype='GOVT' OR btype='SCHOOL-UNI' OR sbtype='COMMUNITY-' OR sbtype='NONPROFIT' OR sbtype='CORPORATIO' OR sbtype='OTHER' OR sbtype='GOVT' OR sbtype='SCHOOL-UNI') AND business_partner.fkey=members.pkey AND members.active='Y' AND members.opted_out='N' GROUP BY members.bname");
}
else
{$result= __do_sql("SELECT MAX(business_partner.url) AS url,members.bname AS business FROM members,business_partner WHERE (btype='COMMUNITY-' OR btype='NONPROFIT' OR btype='CORPORATIO' OR btype='OTHER' OR btype='GOVT' OR btype='SCHOOL-UNI' OR sbtype='COMMUNITY-' OR sbtype='NONPROFIT' OR sbtype='CORPORATIO' OR sbtype='OTHER' OR sbtype='GOVT' OR sbtype='SCHOOL-UNI') AND business_partner.fkey=members.pkey AND members.active='Y' AND members.opted_out='N' AND ( ".$where."GROUP BY members.bname");
}
return $result;
}

function __get_statewide_in_region()
{
$result = __do_sql("SELECT MAX(business_partner.url) AS url,members.bname AS business FROM members,business_partner WHERE members.statewide='Y' AND business_partner.fkey=members.pkey AND members.active='Y' AND members.opted_out='N' "." GROUP BY members.bname");
$num=mysql_num_rows($result);
return $result;
}



?>

