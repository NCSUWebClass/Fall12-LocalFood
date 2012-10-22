<?php
$source_local_this_week = mysql_real_escape_string($_POST['source_local_this_week']);
$source_local_farms = mysql_real_escape_string($_POST['source_local_farms']);
$source_local_why_not = mysql_real_escape_string($_POST['source_local_why_not']);
$purchase_this_week = mysql_real_escape_string($_POST['purchase_this_week']);

$result = __do_sql("update restaurant_data_weekly set answered_on = Now(), source_local_this_week = '$source_local_this_week', source_local_farms = '$source_local_farms', source_local_why_not = '$source_local_why_not', purchase_this_week = '$purchase_this_week' where pkey = '$weekly_pkey' and fkey = '$pkey' and answered_on = '0000-00-00 00:00:00' LIMIT 1");
if (mysql_errno() == 0 && mysql_affected_rows() == 1) { 

	?>
	
	<h2>Thank you!</h2>

<?php

$annual_food_cost = __get_member_field_initial_survey($pkey, 'annual_food_cost');
if ($annual_food_cost) {
	$percent = round($purchase_this_week / $annual_food_cost * 100);
	if ($percent <= 0) 
		$percent = "< 1";
	echo "<p>You spent $".$purchase_this_week." this week on local foods. ";
	echo "That's $percent% of your weekly average food expenditures based on your initial annual estimate of $$annual_food_cost.</p>";
}

?>	
	<p>Thank you for your commitment to North Carolina’s local food economy and the 10% campaign!</p>

	<p>You are making a difference in your community and we’re making a difference in North Carolina!</p>

	<p>See you next week.<br />Thank you for your support<br /> - the 10% Campaign team</p>

	<?php
	
} else {
	echo "<h2>Already Answered?</h2><p>There was an error submitting your weekly survey - maybe you already answered the questions. Please contact us if you need assistance.</p>";
}
?>