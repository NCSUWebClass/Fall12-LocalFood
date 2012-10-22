<h2>Hi<?php $name = __get_member_field($pkey, 'name'); if ($name) echo " $name";?>, How are you doing this week? Let's get started.</h2>

<br /><br />
	<?php 

/*	
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
		
*/		

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
			echo '<img src="http://chart.apis.google.com/chart?chf=bg,s,EEEEEE&chxl=1:|week+1|week+2|week+3|week+4|week+5|last+week&chxr=0,0,105&chxs=0,676767,11.5,0,lt,676767&chxt=y,x&chbh=23,0&chs=375x305&cht=bvg&chco=5d8fb2,82317c&chd=t:'.$ct[6].','.$ct[5].','.$ct[4].','.$ct[3].','.$ct[2].','.$ct[1].'|'.$avg6.','.$avg5.','.$avg4.','.$avg3.','.$avg2.','.$avg1.'&chdl=your+spending|household+average&chdlp=t&chg=0,10,0,0&chma=20,0,20|0,30&chtt=weekly+household+comparison of past six weeks&chts=676767,17.25" width="375" height="305" alt="weekly household comparison" />';
			?>
            <div class="marketing-message">
            <?php if ($ct[1] > 0) { ?>
            	<h1>Keep up the progress!</h1>
            <?php } else { ?>
            	<h1>Uh-oh, looks like we don't have last week's info from you!</h1>
            <?php } ?>    
            <p>Last week, <span class="figure">$<?php echo money_format('%i', $avg1) ?></span> was the average for people with the same household size, while you spent: <span class="figure">$<?php echo money_format('%i', $ct[1]) ?></span></p>
            <p>There were <span class="figure"><?php echo $week1; ?></span> people with the same household size who participated last week.</p><br />
            <p><span style="font-size: x-large; font-weight: bold; color: #82317c;"><?php echo __get_total_dollar_amount();?></span>&nbsp;<span style="color: #333;">spent on North Carolina Grown Food since launch.</span></p>
            </div>
		</div>	
        

<br /><br />


<form id="myform" action="weekly_submit.php" method="post" accept-charset="utf-8">

	<input type="hidden" name="wp" value="<?php echo $weekly_pkey;?>" id="wp">
	<input type="hidden" name="p" value="<?php echo $pkey;?>" id="p">
	<input type="hidden" name="h" value="<?php echo __calculate_weekly_hash($pkey, $weekly_pkey);?>" id="h">
					
	<table border="0" cellspacing="5" cellpadding="5">
		<?php
			$reminder_amount = 0;
			$reminder_str = '';
			$weekly_spend_amount = __get_member_field_initial_survey($pkey, 'weekly_spend_amount');
			if (!$weekly_spend_amount) {
				$household = __get_member_field_initial_survey($pkey, 'household');
				if ($household) {
					$weekly_spend_amount = $household * 50;
				}
			}
			if ($weekly_spend_amount) {
				$reminder_amount = $weekly_spend_amount*.10;
				$reminder_str = '($'.$reminder_amount.')';
			}
		?>
		
		<tr align="top">
			<td><p>How much did you spend on locally grown or produced food this week? (enter a whole dollar amount)&nbsp;&nbsp;</p></td>
			<td width="35%"><input class="digits" type="text" name="amount" value="" id="amount" size="5"></td>
		</tr>
		
		<tr>
			<td><p>Where did you find local foods this week? Check all that apply</p></td>
			<td><p><?php __create_checkbox_options(__get_list_type("SOURCE"), "sources"); ?></p></td>
		</tr>
		
		<tr>
			<td><p>* If you checked <span style="font-weight:bold;">'I grow my own!'</span> please enter the amount of money offset by this week's garden harvest (enter a whole dollar amount)&nbsp;&nbsp;</p></td>
			<td width="35%"><input class="digits" type="text" name="garden_amount" value="" id="garden_amount" size="5"></td>
		</tr>
						
		<tr>
			<td></td>
			<td><br /><br /><input type="submit" value="Submit"></td>
		</tr>
	</table>

</form>
