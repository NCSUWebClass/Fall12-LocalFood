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
		$rand_value = rand(1,10000);
		if ($percent < 5) {
			if($rand_value <= 3000){
				echo '<h3>Keep working at it! Incorporating local, seasonal (<a href="seasonality.php">View Seasonality Chart</a>) foods into your weekly<br /><br /> meals will boost your numbers.</h3>';
			}
			else if(($rand_value > 3000) && ($rand_value <= 6000)){
				echo '<h3>Motivation is key to success! If you need inspiration, check out one of our partner restaurants,<br /><br />(<a href="about.php">About Us</a>) they are cooking with local ingredients every day.</h3>';
			}
			else if($rand_value > 6000){
				echo '<h3>You can make it to 10%! Join a CSA or Food Buying Club to make it an easy choice to go local!</h3>';
			}
		} else if ($percent >= 5 && $percent < 10) {
			if($rand_value <= 3000){
				echo '<h3>Now you’re motivated to Make it Local! Keep up the good work.</h3>';
			}
			else if($rand_value > 3000 && $rand_value <= 6000){
				echo '<h3>Good Work! You’ve earned a gold star this week.</h3>';
			}
			else if($rand_value > 6000){
				echo '<h3>Excellent, we like what you’re doing! Keep eating fresh, local foods.</h3>';
			}
		} else if ($percent >= 10) {
			if($rand_value <= 3000){
				echo '<h3>Congratulations! You are single-handedly improving the average local food spending for your<br /><br /> county.</h3>';
			}
			else if($rand_value > 3000 && $rand_value <= 6000){
				echo '<h3>Impressive! You are impacting our state’s local food economy every week.</h3>';
			}
			else if($rand_value > 6000){
				echo '<h3>Keep on doing what you do! Share your secrets to Making it Local this week on our website.<br /><br />(<a href="http://www.facebook.com/nc10percent">NC 10 Percent Campaign on Facebook</a>)</h3>';
			}
		}
		echo "<br /><br /><p>You spent $".$amount." this week on local foods. ";
		echo "That's $percent% of your weekly average food costs ($$weekly_spend_amount).<br /><br />";
		if ($garden_amount > 0){
			if($rand_value <= 2500){
				echo '<h3>Happy Harvesting! You are truly making it local.</h3>';
			}
			else if($rand_value > 2500 && $rand_value <= 5000){
				echo '<h3>Dig In! Growing your own fruits and veggies is a healthy way to exercise and eat good food!</h3>';
			}
			else if($rand_value > 5000 && $rand_value <= 7500){
				echo '<h3>Gardens make good eats! Thank you for growing local.</h3>';
			}	
			else if($rand_value > 7500){
				echo '<h3>Home Harvests are Healthy! Thank you for Making it local.</h3>';
			}	
			echo "<br /><br />You saved $$garden_amount by harvesting your own.";	
		}	
		echo "</p>";
	}
	
	echo "<br /><p class=\"dashboard\">Explore the <a href=\"partners.php\">Find Local Food</a> page for more ideas on where to eat, buy, or grow local. Take me back to my <a href=\"dashboard.php?wp=$weekly_pkey&p=$pkey&h=$hash\">progress page</a>.</p>";
	
	echo '<br /><p class="dashboard">See you next week.<br />Thank you for your support<br />- the 10% Campaign team</p>';
	
    } else {
		echo "<h2>Already Answered?</h2><p>There was an error submitting your weekly survey - maybe you already answered the questions. Please contact us if you need assistance.</p><br /><br /><br /><br /><br /><br />";
    }	
?>