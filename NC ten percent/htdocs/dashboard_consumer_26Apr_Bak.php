<?php
if($inside_weekly_consumer != true){
	echo "<h2>Welcome to your dashboard"?><?php $name = __get_member_field($pkey, 'name'); if ($name) echo " $name";?><?php echo".</h2>";
}
?>
<br /><br />
<?php
if($inside_weekly_consumer != true){
	echo '<form id="dashboard_consumer" action="dashboard.php" method="get" accept-charset="utf-8">';
}
else{
	echo '<form id="dashboard_weekly_consumer" action="weekly.php" method="get" accept-charset="utf-8">';
}
?>
	<input type="hidden" name="wp" value="<?php echo $weekly_pkey;?>" id="wp">
	<input type="hidden" name="p" value="<?php echo $pkey;?>" id="p">
	<input type="hidden" name="h" value="<?php echo __calculate_weekly_hash($pkey, $weekly_pkey);?>" id="h">
	
			<h4>Compare me with:</h4><br />
<?php 
		$compare_me = mysql_real_escape_string($_GET['compare_me']);
		if($compare_me == "")
			$compare_me = household;
		echo "<input type='radio' name = 'compare_me' value='household'";		
		if($compare_me == household) 
			echo "checked='true'"; 
		echo ">Same Household Size</input>";
		echo "<input type='radio' name = 'compare_me' value='county'";		
		if($compare_me == county) 
			echo "checked='true'"; 
		echo ">My County</input>";
/*		
		echo "<input type='radio' name = 'compare_me' value='zipcode' disabled='disabled'";		
		if($compare_me == zipcode) 
			echo "checked='true'"; 
		echo ">My Zipcode</input>";
		echo "<input type='radio' name = 'compare_me' value='friends' disabled='disabled'";		
		if($compare_me == friends) 
			echo "checked='true'"; 
		echo '>My Friends</input>';
*/		
						
?>

<input type="submit" value="Show">
	
	<?php 

	if($compare_me == county){
		// COUNTITES ------------------------------------------->
		$county = __get_consumers_in_county('27604',6);
		$total = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);
		$week1=0;$week2=0;$week3=0;$week4=0;$week5=0;$week6=0;
		$count = 1;
		$currtime = time();
		while ($row = mysql_fetch_assoc($county)) {
			$x = strtotime($row['answered_on']);
			switch ($x) {
				case (($x < ($currtime)) && ($x > ($currtime - 604800 * 1))):
					$total[1] += $row['amount'];
					$week1++;
					break;
				case (($x < ($currtime - 604800 * 1)) && ($x > ($currtime - 604800 * 2))):
					$total[2] += $row['amount'];
					$week2++;
					break;
				case (($x < ($currtime - 604800 * 2)) && ($x > ($currtime - 604800 * 3))):
					$total[3] += $row['amount'];
					$week3++;
					break;
				case (($x < ($currtime - 604800 * 3)) && ($x > ($currtime - 604800 * 4))):
					$total[4] += $row['amount'];
					$week4++;
					break;
				case (($x < ($currtime - 604800 * 4)) && ($x > ($currtime - 604800 * 5))):
					$total[5] += $row['amount'];
					$week5++;			
					break;
				case (($x < ($currtime - 604800 * 5)) && ($x > ($currtime - 604800 * 6))):
					$total[6] += $row['amount'];
					$week6++;			
					break;	
			}
			
			$total[0] += $row['amount'];
			$count++;
		}
		//$avg = $total/$count;
		$avg1 = $total[1]/$week1;
		//echo "total for week 1 is: {$total[1]} | count is {$week1} | average is {$avg1}<br />";
		$avg2 = $total[2]/$week2;
		//echo "total for week 2 is: {$total[2]} | count is {$week2} | average is {$avg2}<br />";
		$avg3 = $total[3]/$week3;
		//echo "total for week 3 is: {$total[3]} | count is {$week3} | average is {$avg3}<br />";
		$avg4 = $total[4]/$week4;
		//echo "total for week 4 is: {$total[4]} | count is {$week4} | average is {$avg4}<br />";
		$avg5 = $total[5]/$week5;
		//echo "total for week 5 is: {$total[5]} | count is {$week5} | average is {$avg5}<br />";
		$avg6 = $total[6]/$week6;
		//echo "total for week 6 is: {$total[6]} | count is {$week6} | average is {$avg6}<br />";
		
	}else if($compare_me == household){	

		//Household--------------------------------------------->
		$household_size = __get_member_field_initial_survey($pkey,'household');
		$same_household_size = __get_consumers_in_household_size($household_size,6);
		$total = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);
		$week1=0;$week2=0;$week3=0;$week4=0;$week5=0;$week6=0;
		$count = 1;
		$currtime = time();
		while ($row = mysql_fetch_assoc($same_household_size)) {
			$x = strtotime($row['answered_on']);
			switch ($x) {
				case (($x < ($currtime)) && ($x > ($currtime - 604800 * 1))):
					$total[1] += $row['amount'];
					$week1++;
					break;
				case (($x < ($currtime - 604800 * 1)) && ($x > ($currtime - 604800 * 2))):
					$total[2] += $row['amount'];
					$week2++;
					break;
				case (($x < ($currtime - 604800 * 2)) && ($x > ($currtime - 604800 * 3))):
					$total[3] += $row['amount'];
					$week3++;
					break;
				case (($x < ($currtime - 604800 * 3)) && ($x > ($currtime - 604800 * 4))):
					$total[4] += $row['amount'];
					$week4++;
					break;
				case (($x < ($currtime - 604800 * 4)) && ($x > ($currtime - 604800 * 5))):
					$total[5] += $row['amount'];
					$week5++;			
					break;
				case (($x < ($currtime - 604800 * 5)) && ($x > ($currtime - 604800 * 6))):
					$total[6] += $row['amount'];
					$week6++;			
					break;	
			}
			
			$total[0] += $row['amount'];
			$count++;
		}
		//$avg = $total/$count;
		$avg1 = $total[1]/$week1;
		//echo "total for week 1 is: {$total[1]} | count is {$week1} | average is {$avg1}<br />";
		$avg2 = $total[2]/$week2;
		//echo "total for week 2 is: {$total[2]} | count is {$week2} | average is {$avg2}<br />";
		$avg3 = $total[3]/$week3;
		//echo "total for week 3 is: {$total[3]} | count is {$week3} | average is {$avg3}<br />";
		$avg4 = $total[4]/$week4;
		//echo "total for week 4 is: {$total[4]} | count is {$week4} | average is {$avg4}<br />";
		$avg5 = $total[5]/$week5;
		//echo "total for week 5 is: {$total[5]} | count is {$week5} | average is {$avg5}<br />";
		$avg6 = $total[6]/$week6;
		//echo "total for week 6 is: {$total[6]} | count is {$week6} | average is {$avg6}<br />";
		
	}
		
		// CONSUMERS ------------------------------------------->
		$consumer_table = __get_consumers_history(6,$pkey);
		
		$ct = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);
		$cc = 1;
		while ($cr = mysql_fetch_assoc($consumer_table)) {
			$y = strtotime($cr['answered_on']);
			switch ($y) {
				case (($y < ($currtime)) && ($y > ($currtime - 604800 * 1))):
					$ct[1] += $cr['amount'];
					break;
				case (($y < ($currtime - 604800 * 1)) && ($y > ($currtime - 604800 * 2))):
					$ct[2] += $cr['amount'];
					break;
				case (($y < ($currtime - 604800 * 2)) && ($y > ($currtime - 604800 * 3))):
					$ct[3] += $cr['amount'];
					break;
				case (($y < ($currtime - 604800 * 3)) && ($y > ($currtime - 604800 * 4))):
					$ct[4] += $cr['amount'];
					break;
				case (($y < ($currtime - 604800 * 4)) && ($y > ($currtime - 604800 * 5))):
					$ct[5] += $cr['amount'];
					break;
				case (($y < ($currtime - 604800 * 5)) && ($y > ($currtime - 604800 * 6))):
					$ct[6] += $cr['amount'];
					break;	
			}
			
			$ct[0] += $cr['amount'];
			$cc++;
		}


		

		
		//echo "total for week 1 is: {$ct[1]}<br />";

		//echo "total for week 2 is: {$ct[2]}<br />";

		//echo "total for week 3 is: {$ct[3]}<br />";

		//echo "total for week 4 is: {$ct[4]}<br />";

		//echo "total for week 5 is: {$ct[5]}<br />";

		//echo "total for week 6 is: {$ct[6]}<br />";
		
		?>
        <div class="consumer-chart">
        	<?php
        	if($compare_me == household){
				echo '<img src="http://chart.apis.google.com/chart?chf=bg,s,EEEEEE&chxl=1:|week+1|week+2|week+3|week+4|week+5|last+week|0:|$0|$10|$20|$30|$40|$50|$60|$70|$80|$90|$100&chxs=0,676767,11.5,0,lt,676767&chxt=y,x&chbh=23,0&chs=375x305&cht=bvg&chco=5d8fb2,82317c&chd=t:'.$ct[6].','.$ct[5].','.$ct[4].','.$ct[3].','.$ct[2].','.$ct[1].'|'.$avg6.','.$avg5.','.$avg4.','.$avg3.','.$avg2.','.$avg1.'&chdl=your+spending|household+average&chdlp=t&chg=0,10,0,0&chma=20,0,20|0,30&chtt=weekly+household+comparison of past six weeks&chts=676767,17.25" width="375" height="305" alt="weekly household comparison" />';
			}
			else if($compare_me == county){
				echo '<img src="http://chart.apis.google.com/chart?chf=bg,s,EEEEEE&chxl=1:|week+1|week+2|week+3|week+4|week+5|last+week|0:|$0|$10|$20|$30|$40|$50|$60|$70|$80|$90|$100&chxs=0,676767,11.5,0,lt,676767&chxt=y,x&chbh=23,0&chs=375x305&cht=bvg&chco=5d8fb2,82317c&chd=t:'.$ct[6].','.$ct[5].','.$ct[4].','.$ct[3].','.$ct[2].','.$ct[1].'|'.$avg6.','.$avg5.','.$avg4.','.$avg3.','.$avg2.','.$avg1.'&chdl=your+spending|county+average&chdlp=t&chg=0,10,0,0&chma=20,0,20|0,30&chtt=weekly+county+comparison of past six weeks&chts=676767,17.5" width="375" height="305" alt="weekly county comparison" />';
			}
			?>
            <div class="marketing-message">
            <?php if ($ct[1] > 0) { ?>
            	<h1>Keep up the progress!</h1>
            <?php } else { ?>
            	<h1>Uh-oh, looks like we don't have last week's info from you!</h1>
            <?php } ?>
            <?php
            if($compare_me == household){    
            	echo "<p>Last week, <span class=\"figure\">\$"?><?php echo money_format('%i', $avg1) ?><?php echo "</span> was the average for people with the same household size, while you spent: <span class=\"figure\">\$"?><?php echo money_format('%i', $ct[1])?><?php echo "</span></p>";
            	echo "<p>There were <span class=\"figure\">$week1</span> people with the same household size who participated last week.</p><br />";
            	
            }
            else if($compare_me == county){
            	echo "<p>Last week, your county's average was: <span class=\"figure\">\$"?><?php echo money_format('%i', $avg1) ?><?php echo "</span><br /> while you spent: <span class=\"figure\">\$"?><?php echo money_format('%i', $ct[1]) ?><?php echo "</span></p>";
            	echo "<p>There were <span class=\"figure\">$week1</span> people who participated in your County last week.</p><br />";
                      
            }
        	echo "<p><span style=\"font-size: x-large; font-weight: bold; color: #82317c;\">"?><?php echo __get_total_dollar_amount();?><?php echo "</span>&nbsp;<span style=\"color: #333;\">spent on North Carolina Grown Food since launch.</span></p>";
			?> 
            </div>
		</div>	
        

		
<br /><br />


</form>


