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
	
			<h4>Display:</h4><br />
<?php	
		$compare_me = mysql_real_escape_string($_GET['compare_me']);
		if($compare_me == "")
			$compare_me = myProgress;
		echo "<input type='radio' name = 'compare_me' value='myProgress'";		
		if($compare_me == myProgress) 
			echo "checked='true'"; 
		echo ">My Progress</input>";
?>		
			<br /><br /><h4>Compare me with:</h4><br />
<?php 
		echo "<input type='radio' name = 'compare_me' value='household'";		
		if($compare_me == household) 
			echo "checked='true'"; 
		echo ">Same Household Size</input>";		
		echo "<input type='radio' name = 'compare_me' value='county'";		
		if($compare_me == county) 
			echo "checked='true'"; 
		echo ">My County</input>";
		echo "<input type='radio' name = 'compare_me' value='zipcode'";		
		if($compare_me == zipcode) 
			echo "checked='true'"; 
		echo ">My Zipcode</input>";
/*		echo "<input type='radio' name = 'compare_me' value='friends' disabled='disabled'";		
		if($compare_me == friends) 
			echo "checked='true'"; 
		echo '>My Friends</input>';	
*/						
?>

<br /><br /><input type="submit" value="Show">
	
	<?php 

	if($compare_me == county){
		// COUNTITES ------------------------------------------->
		$compare_zipcode = __get_member_field($pkey, 'zipcode');
		$county = __get_consumers_in_county($compare_zipcode,7);
		$total = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);
		$week1=0;$week2=0;$week3=0;$week4=0;$week5=0;$week6=0;
		$count = 1;
		$current_total =0;
		$current_week = 0;		
		$currtime = time();
		while ($row = mysql_fetch_assoc($county)) {
			$x = strtotime($row['answered_on']);
			switch ($x) {
				case (($x < ($currtime)) && ($x > ($currtime - 604800 * 1))):
					$current_total += $row['amount'];
					$current_week++;
					break;				
				case (($x < ($currtime - 604800 * 1)) && ($x > ($currtime - 604800 * 2))):
					$total[1] += $row['amount'];
					$week1++;
					break;
				case (($x < ($currtime - 604800 * 2)) && ($x > ($currtime - 604800 * 3))):
					$total[2] += $row['amount'];
					$week2++;
					break;
				case (($x < ($currtime - 604800 * 3)) && ($x > ($currtime - 604800 * 4))):
					$total[3] += $row['amount'];
					$week3++;
					break;
				case (($x < ($currtime - 604800 * 4)) && ($x > ($currtime - 604800 * 5))):
					$total[4] += $row['amount'];
					$week4++;
					break;
				case (($x < ($currtime - 604800 * 5)) && ($x > ($currtime - 604800 * 6))):
					$total[5] += $row['amount'];
					$week5++;			
					break;
				case (($x < ($currtime - 604800 * 6)) && ($x > ($currtime - 604800 * 7))):
					$total[6] += $row['amount'];
					$week6++;			
					break;	
			}
			
			$total[0] += $row['amount'];
			$count++;
		}
		//$avg = $total/$count;
		$avg_current = $current_total/$current_week;
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
		$same_household_size = __get_consumers_in_household_size($household_size,7);
		$total = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);
		$week1=0;$week2=0;$week3=0;$week4=0;$week5=0;$week6=0;
		$count = 1;
		$current_total =0;
		$current_week = 0;
		$currtime = time();
		while ($row = mysql_fetch_assoc($same_household_size)) {
			$x = strtotime($row['answered_on']);
			switch ($x) {
				case (($x < ($currtime)) && ($x > ($currtime - 604800 * 1))):
					$current_total += $row['amount'];
					$current_week++;
					break;			
				case (($x < ($currtime - 604800 * 1)) && ($x > ($currtime - 604800 * 2))):
					$total[1] += $row['amount'];
					$week1++;
					break;
				case (($x < ($currtime - 604800 * 2)) && ($x > ($currtime - 604800 * 3))):
					$total[2] += $row['amount'];
					$week2++;
					break;
				case (($x < ($currtime - 604800 * 3)) && ($x > ($currtime - 604800 * 4))):
					$total[3] += $row['amount'];
					$week3++;
					break;
				case (($x < ($currtime - 604800 * 4)) && ($x > ($currtime - 604800 * 5))):
					$total[4] += $row['amount'];
					$week4++;
					break;
				case (($x < ($currtime - 604800 * 5)) && ($x > ($currtime - 604800 * 6))):
					$total[5] += $row['amount'];
					$week5++;			
					break;
				case (($x < ($currtime - 604800 * 6)) && ($x > ($currtime - 604800 * 7))):
					$total[6] += $row['amount'];
					$week6++;			
					break;	
			}
			
			$total[0] += $row['amount'];
			$count++;
		}
		//$avg = $total/$count;
		$avg_current = $current_total/$current_week;
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
//======================
	}else if($compare_me == zipcode){	
		// ZIPCODE ------------------------------------------->
		$compare_zipcode = __get_member_field($pkey, 'zipcode');
		$assoc_record = __get_consumers_in_zipcode($compare_zipcode,7);

		$total = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);
		$week1=0;$week2=0;$week3=0;$week4=0;$week5=0;$week6=0;
		$count = 1;
		$current_total =0;
		$current_week = 0;
		$currtime = time();
		while ($row = mysql_fetch_assoc($assoc_record)) {
			$x = strtotime($row['answered_on']);
			switch ($x) {
				case (($x < ($currtime)) && ($x > ($currtime - 604800 * 1))):
					$current_total += $row['amount'];
					$current_week++;
					break;			
				case (($x < ($currtime - 604800 * 1)) && ($x > ($currtime - 604800 * 2))):
					$total[1] += $row['amount'];
					$week1++;
					break;
				case (($x < ($currtime - 604800 * 2)) && ($x > ($currtime - 604800 * 3))):
					$total[2] += $row['amount'];
					$week2++;
					break;
				case (($x < ($currtime - 604800 * 3)) && ($x > ($currtime - 604800 * 4))):
					$total[3] += $row['amount'];
					$week3++;
					break;
				case (($x < ($currtime - 604800 * 4)) && ($x > ($currtime - 604800 * 5))):
					$total[4] += $row['amount'];
					$week4++;
					break;
				case (($x < ($currtime - 604800 * 5)) && ($x > ($currtime - 604800 * 6))):
					$total[5] += $row['amount'];
					$week5++;			
					break;
				case (($x < ($currtime - 604800 * 6)) && ($x > ($currtime - 604800 * 7))):
					$total[6] += $row['amount'];
					$week6++;			
					break;	
			}
			
			$total[0] += $row['amount'];
			$count++;
		}
		//$avg = $total/$count;
		$avg_current = $current_total/$current_week;
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
		
	
//======================		
	}else if($compare_me == myProgress){	

		//myProgress--------------------------------------------->
		$consumer_progress = __get_consumers_history(13,$pkey);
		$total = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
		$week1=0;$week2=0;$week3=0;$week4=0;$week5=0;$week6=0;$week7=0;$week8=0;$week9=0;$week10=0;$week11=0;$week12=0;
		$avg=0;$avg1=0;$avg2=0;$avg3=0;$avg4=0;$avg5=0;$avg6=0;$avg7=0;$avg8=0;$avg9=0;$avg10=0;$avg11=0;$avg12=0;
		$count = 1;
		$current_total =0;
		$current_week = 0;
		$currtime = time();
		while ($row = mysql_fetch_assoc($consumer_progress)) {
			$x = strtotime($row['answered_on']);
			switch ($x) {
				case (($x < ($currtime)) && ($x > ($currtime - 604800 * 1))):
					$current_total += $row['amount'];
					$current_week++;
					break;			
				case (($x < ($currtime - 604800 * 1)) && ($x > ($currtime - 604800 * 2))):
					$total[1] += $row['amount'];
					$week1++;
					break;
				case (($x < ($currtime - 604800 * 2)) && ($x > ($currtime - 604800 * 3))):
					$total[2] += $row['amount'];
					$week2++;
					break;
				case (($x < ($currtime - 604800 * 3)) && ($x > ($currtime - 604800 * 4))):
					$total[3] += $row['amount'];
					$week3++;
					break;
				case (($x < ($currtime - 604800 * 4)) && ($x > ($currtime - 604800 * 5))):
					$total[4] += $row['amount'];
					$week4++;
					break;
				case (($x < ($currtime - 604800 * 5)) && ($x > ($currtime - 604800 * 6))):
					$total[5] += $row['amount'];
					$week5++;			
					break;
				case (($x < ($currtime - 604800 * 6)) && ($x > ($currtime - 604800 * 7))):
					$total[6] += $row['amount'];
					$week6++;			
					break;
				case (($x < ($currtime - 604800 * 7)) && ($x > ($currtime - 604800 * 8))):
					$total[7] += $row['amount'];
					$week7++;
					break;
				case (($x < ($currtime - 604800 * 8)) && ($x > ($currtime - 604800 * 9))):
					$total[8] += $row['amount'];
					$week8++;
					break;
				case (($x < ($currtime - 604800 * 9)) && ($x > ($currtime - 604800 * 10))):
					$total[9] += $row['amount'];
					$week9++;
					break;
				case (($x < ($currtime - 604800 * 10)) && ($x > ($currtime - 604800 * 11))):
					$total[10] += $row['amount'];
					$week10++;
					break;
				case (($x < ($currtime - 604800 * 11)) && ($x > ($currtime - 604800 * 12))):
					$total[11] += $row['amount'];
					$week11++;			
					break;
				case (($x < ($currtime - 604800 * 12)) && ($x > ($currtime - 604800 * 13))):
					$total[12] += $row['amount'];
					$week12++;			
					break;							
			}
			
			$total[0] += $row['amount'];
			$count++;
		}
		//$avg = $total/$count;
		$avg_current = $current_total/$current_week;
		if($week1>0){
			$avg1 = $total[1]/$week1;
		}	
		//echo "total for week 1 is: {$total[1]} | count is {$week1} | average is {$avg1}<br />";
		if($week2>0){
			$avg2 = $total[2]/$week2;
		}	
		//echo "total for week 2 is: {$total[2]} | count is {$week2} | average is {$avg2}<br />";
		if($week3>0){
			$avg3 = $total[3]/$week3;
		}	
		//echo "total for week 3 is: {$total[3]} | count is {$week3} | average is {$avg3}<br />";
		if($week4>0){
			$avg4 = $total[4]/$week4;
		}	
		//echo "total for week 4 is: {$total[4]} | count is {$week4} | average is {$avg4}<br />";
		if($week5>0){
			$avg5 = $total[5]/$week5;
		}	
		//echo "total for week 5 is: {$total[5]} | count is {$week5} | average is {$avg5}<br />";
		if($week6>0){
			$avg6 = $total[6]/$week6;
		}	
		//echo "total for week 6 is: {$total[6]} | count is {$week6} | average is {$avg6}<br />";
		if($week7>0){
			$avg7 = $total[7]/$week7;
		}	
		//echo "total for week 1 is: {$total[1]} | count is {$week1} | average is {$avg1}<br />";
		if($week8>0){
			$avg8 = $total[8]/$week8;
		}	
		//echo "total for week 2 is: {$total[2]} | count is {$week2} | average is {$avg2}<br />";
		if($week9>0){
			$avg9 = $total[9]/$week9;
		}	
		//echo "total for week 3 is: {$total[3]} | count is {$week3} | average is {$avg3}<br />";
		if($week10>0){
			$avg10 = $total[10]/$week10;
		}	
		//echo "total for week 4 is: {$total[4]} | count is {$week4} | average is {$avg4}<br />";
		if($week11>0){
			$avg11 = $total[11]/$week11;
		}	
		//echo "total for week 5 is: {$total[5]} | count is {$week5} | average is {$avg5}<br />";
		if($week12>0){
			$avg12 = $total[12]/$week12;
		}	
		//echo "total for week 12 is: {$total[12]} | count is {$week12} | average is {$avg12}<br />";
				
	}
			
		// CONSUMERS ------------------------------------------->
		$consumer_table = __get_consumers_history(7,$pkey);
		
		$ct = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);
		$cc = 1;
		$ct_current = 0;
		while ($cr = mysql_fetch_assoc($consumer_table)) {
			$y = strtotime($cr['answered_on']);
			switch ($y) {
				case (($y < ($currtime)) && ($y > ($currtime - 604800 * 1))):
					$ct_current += $cr['amount'];
					break;			
				case (($y < ($currtime - 604800 * 1)) && ($y > ($currtime - 604800 * 2))):
					$ct[1] += $cr['amount'];
					break;
				case (($y < ($currtime - 604800 * 2)) && ($y > ($currtime - 604800 * 3))):
					$ct[2] += $cr['amount'];
					break;
				case (($y < ($currtime - 604800 * 3)) && ($y > ($currtime - 604800 * 4))):
					$ct[3] += $cr['amount'];
					break;
				case (($y < ($currtime - 604800 * 4)) && ($y > ($currtime - 604800 * 5))):
					$ct[4] += $cr['amount'];
					break;
				case (($y < ($currtime - 604800 * 5)) && ($y > ($currtime - 604800 * 6))):
					$ct[5] += $cr['amount'];
					break;
				case (($y < ($currtime - 604800 * 6)) && ($y > ($currtime - 604800 * 7))):
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
			else if($compare_me == zipcode){
				echo '<img src="http://chart.apis.google.com/chart?chf=bg,s,EEEEEE&chxl=1:|week+1|week+2|week+3|week+4|week+5|last+week|0:|$0|$10|$20|$30|$40|$50|$60|$70|$80|$90|$100&chxs=0,676767,11.5,0,lt,676767&chxt=y,x&chbh=23,0&chs=375x305&cht=bvg&chco=5d8fb2,82317c&chd=t:'.$ct[6].','.$ct[5].','.$ct[4].','.$ct[3].','.$ct[2].','.$ct[1].'|'.$avg6.','.$avg5.','.$avg4.','.$avg3.','.$avg2.','.$avg1.'&chdl=your+spending|zipcode+average&chdlp=t&chg=0,10,0,0&chma=20,0,20|0,30&chtt=weekly+zipcode+comparison of past six weeks&chts=676767,17.5" width="375" height="305" alt="weekly zipcode comparison" />';
			}			
			else if($compare_me == myProgress){
				echo '<img src="http://chart.apis.google.com/chart?chf=bg,s,EEEEEE&chxt=x,x,y,y&chxl=3:|amount|1:|week|0:|1|2|3|4|5|6|7|8|9|10|11+|last+week|2:|$0|$10|$20|$30|$40|$50|$60|$70|$80|$90|$100&chxs=0,676767,11.5,0,lt,676767&chxp=1,50|3,50&chbh=23,0&chs=475x305&cht=bvg&chco=5d8fb2,82317c&chd=t:'.$avg12.','.$avg11.','.$avg10.','.$avg9.','.$avg8.','.$avg7.','.$avg6.','.$avg5.','.$avg4.','.$avg3.','.$avg2.','.$avg1.'&chdl=your+spending&chdlp=t&chg=0,10,0,0&chma=20,0,20|0,30&chtt=your progress for past twelve weeks&chts=676767,17.5" width="375" height="305" alt="your weekly progress" />';
			}			
			?>
            <div class="marketing-message">
            <?php if ($ct_current > 0) { ?>
            	<h1>Keep up the progress!</h1>
            <?php } else { ?>
            	<h1>This week's current statistics:</h1>
            <?php } ?>
            <?php
            if($compare_me == household){    
            	echo "<p>This week, <span class=\"figure\">\$"?><?php echo money_format('%i', $avg_current) ?><?php echo "</span> has been the average for people with the same household size";
            	if ($ct_current > 0) { 
            		echo", while you spent: <span class=\"figure\">\$"?><?php echo money_format('%i', $ct_current)?><?php echo "</span></p>";
                } else { 
                	echo ".</p>";
            	} 
            	//echo "<p><span class=\"figure\">$current_week</span> people with the same household size have participated this week.</p><br />";
            	
            }
            else if($compare_me == county){
            	echo "<p>This week, your county's average has been: <span class=\"figure\">\$"?><?php echo money_format('%i', $avg_current) ?><?php echo "</span>";
            	if ($ct_current > 0) {
            		echo"<br /> while you spent: <span class=\"figure\">\$"?><?php echo money_format('%i', $ct_current) ?><?php echo "</span></p>";
                } else { 
                	echo ".</p>";
                }	            		
            	//echo "<p><span class=\"figure\">$week1</span> people have participated in your County this week.</p><br />";
                      
            }
            else if($compare_me == zipcode){    
            	echo "<p>This week, <span class=\"figure\">\$"?><?php echo money_format('%i', $avg_current) ?><?php echo "</span> has been the average for people with the same zipcode";
            	if ($ct_current > 0) {
            		echo", while you spent: <span class=\"figure\">\$"?><?php echo money_format('%i', $ct_current)?><?php echo "</span></p>";
                } else { 
                	echo ".</p>";
            	}            	
            	//echo "<p>There were <span class=\"figure\">$week1</span> people with the same zipcode who participated last week.</p><br />";
            	
            }
        	echo "<p><span style=\"font-size: x-large; font-weight: bold; color: #82317c;\">"?><?php echo __get_total_dollar_amount();?><?php echo "</span>&nbsp;<span style=\"color: #333;\">spent on North Carolina Grown Food since launch.</span></p>";
			?> 
            </div>
		</div>	
        

		
<br /><br />


</form>


