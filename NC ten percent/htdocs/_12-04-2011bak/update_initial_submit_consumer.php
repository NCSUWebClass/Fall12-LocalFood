<?php
		$household = mysql_real_escape_string($_POST['household']);
		$weekly_spend_amount = mysql_real_escape_string($_POST['weekly_spend_amount']);
		$heard_about_campaign = mysql_real_escape_string($_POST['heard_about_campaign']);
		$heard_about_campaign_other = mysql_real_escape_string($_POST['heard_about_campaign_other']);
		$regular_local_sources = $_POST['regular_local_sources'] ;
		$regular_local_sources_string = '';
		foreach($regular_local_sources as $k => $v) {
			if ($regular_local_sources_string)
				$regular_local_sources_string .= '|';
			$regular_local_sources_string .= mysql_real_escape_string($v);
		}
		
		$result = __do_sql("insert into consumer_data_initial (fkey, answered_on, household, weekly_spend_amount, regular_local_sources, heard_about_campaign, heard_about_campaign_other) values ('$pkey', Now(), '$household', '$weekly_spend_amount', '$regular_local_sources_string','$heard_about_campaign','$heard_about_campaign_other')");
		if (mysql_errno() == 0 && mysql_affected_rows() == 1) { 
?>
		<h2>Thank you!</h2>
		
		<br /><br />
		
		<p>Now go have fun discovering local food!</p>
		
		<p>Start by exploring the <a href="local.php">Find Local Food</a> page for more ideas on where to eat, buy, or grow local; and see others who are committed to making it local on the <a href="learn.php">Learn More</a> page!</p>
		
		<p><h4><a href="dashboard.php?wp=<?php echo $weekly_pkey;?>&p=<?php echo $pkey;?>&h=<?php echo $hash;?>">Take me to my dashboard.</a></h4></p>

<?php
		} else {
/*		echo "<p>$pkey, Now(), $household, $weekly_spend_amount, $regular_local_sources_string,$heard_about_campaign,$heard_about_campaign_other";
*/ 
 			echo "<p>There was an error submitting your updated initial survey. Please contact us for assistance.</p>";
		}
?>