<?php
		$annual_food_cost = mysql_real_escape_string($_POST['annual_food_cost']);
		$customers_per_week = mysql_real_escape_string($_POST['customers_per_week']);
		$source_local = mysql_real_escape_string($_POST['source_local']);
		$percent_local = mysql_real_escape_string($_POST['percent_local']);
		$sourcing_assistance = mysql_real_escape_string($_POST['sourcing_assistance']);
		$farms = mysql_real_escape_string($_POST['farms']);
		
		$result = __do_sql("insert into restaurant_data_initial (fkey, answered_on, annual_food_cost, customers_per_week, source_local, percent_local, sourcing_assistance, farms) values ('$pkey', Now(), '$annual_food_cost', '$customers_per_week', '$source_local','$percent_local','$sourcing_assistance','$farms')");
		if (mysql_errno() == 0 && mysql_affected_rows() == 1) { 
?>
		<h2>Thank you for your investment in North Carolina’s sustainable local food economy.</h2>
		
		<br /><br />
		
		<p>We appreciate your input. Each week we’ll send you a progress request link. Just answer three simple but important questions and help us go local!</p>
		
		<p>We’ll see you next week.<br /><br />Thank you for your support<br /> - the 10% Campaign team</p>

<?php
		} else {
 			echo "<h2>Already Answered?</h2><p>There was an error submitting your initial survey - maybe you already answered the questions. Please contact us if you need assistance.</p>";
		}
?>