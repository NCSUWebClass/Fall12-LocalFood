<?php
$amount = mysql_real_escape_string($_POST['amount']);
$garden_amount = mysql_real_escape_string($_POST['garden_amount']);
$sources = $_POST['sources'];
$sources_string = '';
foreach($sources as $k => $v) {
	if ($sources_string)
		$sources_string .= '|';
	$sources_string .= mysql_real_escape_string($v);
}

$result = __do_sql("update consumer_data_weekly set answered_on = Now(), amount = '$amount', garden_amount = '$garden_amount', sources = '$sources_string' where pkey = '$weekly_pkey' and fkey = '$pkey' and answered_on = '0000-00-00 00:00:00' LIMIT 1");
if (mysql_errno() == 0 && mysql_affected_rows() == 1) { 

	echo "<h2>Thank you!</h2><br /><br />";

	/*
	-If the response is > 0% but less than 5% then: The challenge is on; Better luck next week!
	-If the response is = > 5% but less than 10%, then: Congratulations, you’re making good progress!
	-If the response is = > than 10% of total food $, then: Congratulations! You’re ahead of the curve; encourage your friends to join us.
	*/
	$weekly_spend_amount = __get_member_field_initial_survey($pkey, 'weekly_spend_amount');
	if ($weekly_spend_amount) {
		$percent = round($amount / $weekly_spend_amount * 100);
		if ($percent < 5) {
			echo "<h3>The challenge is on; Better luck next week!</h3>";
		} else if ($percent >= 5 && $percent < 10) {
			echo "<h3>Congratulations, you’re making good progress!</h3>";
		} else if ($percent >= 10) {
			echo "<h3>Congratulations! You’re ahead of the curve; encourage your friends to join us.</h3>";
		}
		echo "<p>You spent $".$amount." this week on local foods. ";
		if ($garden_amount > 0)
			echo "You saved $$garden_amount by growing your own. ";
		echo "That's $percent% of your weekly average food costs ($$weekly_spend_amount).</p>";
	}
	
	echo '<br /><p>Explore the <a href="local.php">Find Local Food</a> page for more ideas on where to eat, buy, or grow local; and see others who are committed to making it local on the <a href="learn.php">Learn More</a> page!</p>';
	
	echo '<br /><p>See you next week.<br />Thank you for your support<br />- the 10% Campaign team</p>';
	
} else {
	echo "<h2>Already Answered?</h2><p>There was an error submitting your weekly survey - maybe you already answered the questions. Please contact us if you need assistance.</p>";
}
?>