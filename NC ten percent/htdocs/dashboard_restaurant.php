<?php
if($inside_weekly_restaurant != true){
	echo "<h2>Welcome to your dashboard"?><?php $name = __get_member_field($pkey, 'bname'); if ($name) echo " $name";?><?php echo".</h2>";
}
?>
<?php
if($inside_weekly_restaurant != true){
	echo '<form id="dashboard_restaurant" action="dashboard2.php" method="get" accept-charset="utf-8">';
}
else{
	echo '<form id="dashboard_weekly_restaurant" action="weekly.php" method="get" accept-charset="utf-8">';
}
?>
	<input type="hidden" name="wp" value="<?php echo $weekly_pkey;?>" id="wp">
	<input type="hidden" name="p" value="<?php echo $pkey;?>" id="p">
	<input type="hidden" name="h" value="<?php echo __calculate_weekly_hash($pkey, $weekly_pkey);?>" id="h">
	
			<h4>My Progress:</h4><br />


<?php	
		$compare_me = mysql_real_escape_string($_GET['compare_me']);
		if($compare_me == "")
			$compare_me = myProgress;
		echo "<input type='radio' name = 'compare_me' value='myProgress'";		
		if($compare_me == myProgress) 
			echo "checked='true'"; 
		echo ">My Progress</input>";
        
		
?>		
	<br/><br/><h4>Compare me with:</h4><br/>
	
<?php
 echo "<input type='radio' name = 'compare_me' value='zipcode'";		
		if($compare_me == zipcode) 
			echo "checked='true'"; 
		echo ">My zipcode</input>";
?>
	
	
<br /><br /><input type="submit" value="Show">
    
	<?php 
	if($compare_me == zipcode){	
		// ZIPCODE ------------------------------------------->
		$compare_zipcode = __get_member_field($pkey, 'zipcode');
		$assoc_record = __get_restaurants_in_zipcode($compare_zipcode,7);
		$total = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);
		$week1=0;$week2=0;$week3=0;$week4=0;$week5=0;$week6=0;
		$avg=0;$avg1=0;$avg2=0;$avg3=0;$avg4=0;$avg5=0;$avg6=0;
		$count = 1;
		$current_total =0;
		$current_week = 0;
		$currtime = time();
		while ($row = mysql_fetch_assoc($assoc_record)) {
			$x = strtotime($row['answered_on']);
			switch ($x) {
				case (($x < ($currtime)) && ($x > ($currtime - 604800 * 1))):
					$current_total += $row['purchase_this_week'];
					$current_week++;
					break;			
				case (($x < ($currtime - 604800 * 1)) && ($x > ($currtime - 604800 * 2))):
					$total[1] += $row['purchase_this_week'];
					$week1++;
					break;
				case (($x < ($currtime - 604800 * 2)) && ($x > ($currtime - 604800 * 3))):
					$total[2] += $row['purchase_this_week'];
					$week2++;
					break;
				case (($x < ($currtime - 604800 * 3)) && ($x > ($currtime - 604800 * 4))):
					$total[3] += $row['purchase_this_week'];
					$week3++;
					break;
				case (($x < ($currtime - 604800 * 4)) && ($x > ($currtime - 604800 * 5))):
					$total[4] += $row['purchase_this_week'];
					$week4++;
					break;
				case (($x < ($currtime - 604800 * 5)) && ($x > ($currtime - 604800 * 6))):
					$total[5] += $row['purchase_this_week'];
					$week5++;
					break;
				case (($x < ($currtime - 604800 * 6)) && ($x > ($currtime - 604800 * 7))):
					$total[6] += $row['purchase_this_week'];
					$week6++;			
					break;	
			}
			
			$total[0] += $row['purchase_this_week'];
			$count++;
			//echo $total[0];
		}
		
		
		//$avg = $total/$count;
		$avg_current = $current_total/$current_week;
		//echo $avg_current;
		
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
			$display1=$avg1/50;
			$display2=$avg2/50;
			$display3=$avg3/50;
			$display4=$avg4/50;
			$display5=$avg5/50;
			$display6=$avg6/50;
			//echo "total for week 6 is: {$total[6]} | count is {$week6} | average is {$avg6}<br />";
		
	
	//======================		
	/*}else if($compare_me == annualfoodcost){
		// ANNUAL FOOD COST ------------------------------------------->
		$compare_zipcode = __get_member_field($pkey, 'zipcode');
		$county = __get_consumers_in_county($compare_zipcode,7);
		$total = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);
		$week1=0;$week2=0;$week3=0;$week4=0;$week5=0;$week6=0;
		$avg=0;$avg1=0;$avg2=0;$avg3=0;$avg4=0;$avg5=0;$avg6=0;
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
		
	
*/	}
else if($compare_me == myProgress){	
		//myProgress--------------------------------------------->
		$sql = "SELECT members.pkey, members.name, restaurant_data_weekly.fkey, restaurant_data_weekly.answered_on, restaurant_data_weekly.purchase_this_week FROM members INNER JOIN restaurant_data_weekly ON members.pkey = restaurant_data_weekly.fkey AND members.kind = 'business' AND members.pkey = ".$pkey." AND (UNIX_TIMESTAMP(restaurant_data_weekly.answered_on) > (UNIX_TIMESTAMP()-(604800*13))) ORDER BY restaurant_data_weekly.answered_on";
		//myProgress--------------------------------------------->
		//$consumer_progress = __get_consumers_history(13,$pkey);
		$consumer_progress = __do_sql($sql);
		//echo $consumer_progress;
		$total = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
		$week1=0;$week2=0;$week3=0;$week4=0;$week5=0;$week6=0;$week7=0;$week8=0;$week9=0;$week10=0;$week11=0;$week12=0;
		$avg=0;$avg1=0;$avg2=0;$avg3=0;$avg4=0;$avg5=0;$avg6=0;$avg7=0;$avg8=0;$avg9=0;$avg10=0;$avg11=0;$avg12=0;
		$count = 1;
		$current_total =0;
		$current_week = 0;
		$currtime = time();
		while ($row = mysql_fetch_assoc($consumer_progress)) {
			$x = strtotime($row['answered_on']);
			
			//echo $x;
			//echo "<br>";
			//echo $currtime;
			//echo "<br>";
			//echo $currtime - 604800 * 1;
			//echo "<br>";
			//echo $currtime + 604800 * 13;
			
			switch ($x) {
				case (($x < ($currtime)) && ($x > ($currtime - 604800 * 1))):
					$current_total += $row['purchase_this_week'];
					$current_week++;
					break;			
				case (($x < ($currtime - 604800 * 1)) && ($x > ($currtime - 604800 * 2))):
					$total[1] += $row['purchase_this_week'];
					$week1++;
					break;
				case (($x < ($currtime - 604800 * 2)) && ($x > ($currtime - 604800 * 3))):
					$total[2] += $row['purchase_this_week'];
					$week2++;
					break;
				case (($x < ($currtime - 604800 * 3)) && ($x > ($currtime - 604800 * 4))):
					$total[3] += $row['purchase_this_week'];
					$week3++;
					break;
				case (($x < ($currtime - 604800 * 4)) && ($x > ($currtime - 604800 * 5))):
					$total[4] += $row['purchase_this_week'];
					$week4++;
					break;
				case (($x < ($currtime - 604800 * 5)) && ($x > ($currtime - 604800 * 6))):
					$total[5] += $row['purchase_this_week'];
					$week5++;			
					break;
				case (($x < ($currtime - 604800 * 6)) && ($x > ($currtime - 604800 * 7))):
					$total[6] += $row['purchase_this_week'];
					$week6++;			
					break;
				case (($x < ($currtime - 604800 * 7)) && ($x > ($currtime - 604800 * 8))):
					$total[7] += $row['purchase_this_week'];
					$week7++;
					break;
				case (($x < ($currtime - 604800 * 8)) && ($x > ($currtime - 604800 * 9))):
					$total[8] += $row['purchase_this_week'];
					$week8++;
					break;
				case (($x < ($currtime - 604800 * 9)) && ($x > ($currtime - 604800 * 10))):
					$total[9] += $row['purchase_this_week'];
					$week9++;
					break;
				case (($x < ($currtime - 604800 * 10)) && ($x > ($currtime - 604800 * 11))):
					$total[10] += $row['purchase_this_week'];
					$week10++;
					break;
				case (($x < ($currtime - 604800 * 11)) && ($x > ($currtime - 604800 * 12))):
					$total[11] += $row['purchase_this_week'];
					$week11++;			
					break;
				case (($x < ($currtime - 604800 * 12)) && ($x > ($currtime - 604800 * 13))):
					$total[12] += $row['purchase_this_week'];
					$week12++;			
					break;							
			}
			
			$total[0] += $row['purchase_this_week'];
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
		//echo "total for week 7 is: {$total[7]} | count is {$week7} | average is {$avg7}<br />";
		if($week8>0){
			$avg8 = $total[8]/$week8;
		}	
		//echo "total for week 8 is: {$total[8]} | count is {$week8} | average is {$avg8}<br />";
		if($week9>0){
			$avg9 = $total[9]/$week9;
		}	
		//echo "total for week 9 is: {$total[9]} | count is {$week9} | average is {$avg9}<br />";
		if($week10>0){
			$avg10 = $total[10]/$week10;
		}	
		//echo "total for week 10 is: {$total[10]} | count is {$week10} | average is {$avg10}<br />";
		if($week11>0){
			$avg11 = $total[11]/$week11;
		}	
		//echo "total for week 11 is: {$total[11]} | count is {$week11} | average is {$avg11}<br />";
		if($week12>0){			
			$avg12 = $total[12]/$week12;
		} 
		//echo "total for week 12 is: {$total[12]} | count is {$week12} | average is {$avg12}<br />";
		if ($avg_current>0){
			$avg12 = $avg_current;
		}
		    $display1=$avg1/50;
			$display2=$avg2/50;
			$display3=$avg3/50;
			$display4=$avg4/50;
			$display5=$avg5/50;
			$display6=$avg6/50;
			$display7=$avg7/50;
			$display8=$avg8/50;
			$display9=$avg9/50;
			$display10=$avg10/50;
			$display11=$avg11/50;
			$display12=$avg12/50;
		}
		
		$sql2 = "SELECT members.pkey, members.name, restaurant_data_weekly.fkey, restaurant_data_weekly.answered_on, restaurant_data_weekly.purchase_this_week FROM members INNER JOIN restaurant_data_weekly ON members.pkey = restaurant_data_weekly.fkey AND members.kind = 'business' AND members.pkey = ".$pkey." AND (UNIX_TIMESTAMP(restaurant_data_weekly.answered_on) > (UNIX_TIMESTAMP()-(604800*7))) ORDER BY restaurant_data_weekly.answered_on";

                //$consumer_table = __get_consumers_history(7,$pkey);

                $consumer_table = __do_sql($sql2);
                //echo $consumer_table."<br>";
		$ct = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);
		$cc = 1;
		$ct_current = 0;
		$numr=mysql_num_rows($consumer_table);
		while ($cr = mysql_fetch_assoc($consumer_table)) {
			$y = strtotime($cr['answered_on']);
			echo '<br/>';
			switch ($y) {
				case (($y < ($currtime)) && ($y > ($currtime - 604800 * 1))):
					$ct_current += $cr['purchase_this_week'];
					break;			
				case (($y < ($currtime - 604800 * 1)) && ($y > ($currtime - 604800 * 2))):
					$ct[1] += $cr['purchase_this_week'];
					break;
				case (($y < ($currtime - 604800 * 2)) && ($y > ($currtime - 604800 * 3))):
					$ct[2] += $cr['purchase_this_week'];
					break;
				case (($y < ($currtime - 604800 * 3)) && ($y > ($currtime - 604800 * 4))):
					$ct[3] += $cr['purchase_this_week'];
					break;
				case (($y < ($currtime - 604800 * 4)) && ($y > ($currtime - 604800 * 5))):
					$ct[4] += $cr['purchase_this_week'];
					break;
				case (($y < ($currtime - 604800 * 5)) && ($y > ($currtime - 604800 * 6))):
					$ct[5] += $cr['purchase_this_week'];
					break;
				case (($y < ($currtime - 604800 * 6)) && ($y > ($currtime - 604800 * 7))):
					$ct[6] += $cr['purchase_this_week'];
					break;	
			}
			$ct[0] += $cr['purchase_this_week'];
			$cc++;
			}
			$ct1=$ct[1]/50;
			$ct2=$ct[2]/50;
			$ct3=$ct[3]/50;
			$ct4=$ct[4]/50;
			$ct5=$ct[5]/50;
			$ct6=$ct[6]/50;
			
		?>
        <div class="consumer-chart">
        	<?php
			if($compare_me == myProgress){
				echo '<img src="http://chart.apis.google.com/chart?chf=bg,s,EEEEEE&chxt=x,x,y,y&chxl=3:|amount|1:|week|0:|1|2|3|4|5|6|7|8|9|10|11+|last+week|2:|$0|$500|$1000|$1500|$2000|$2500|$3000|$3500|$4000|$4500|$5000&chxs=0,676767,11.5,0,lt,676767&chxp=1,50|3,50&chbh=23,0&chs=475x305&cht=bvg&chco=5d8fb2,82317c&chd=t:'.$display12.','.$display11.','.$display10.','.$display9.','.$display8.','.$display7.','.$display6.','.$display5.','.$display4.','.$display3.','.$display2.','.$display1.'&chdl=your+spending&chdlp=t&chg=0,10,0,0&chma=20,0,20|0,30&chtt=your progress for past twelve weeks&chts=676767,17.5" width="375" height="305" alt="your weekly progress" />';
			}
        	else if ($compare_me==zipcode){ 
echo '<img src="http://chart.apis.google.com/chart?chf=bg,s,EEEEEE&chxl=1:|week+1|week+2|week+3|week+4|week+5|last+week|0:|$0|$500|$1000|$1500|$2000|$2500|$3000|$3500|$4000|$4500|$5000&chxs=0,676767,11.5,0,lt,676767&chxt=y,x&chbh=23,0&chs=375x305&cht=bvg&chco=5d8fb2,82317c&chd=t:'.$ct6.','.$ct5.','.$ct4.','.$ct3.','.$ct2.','.$ct1.'|'.$display6.','.$display5.','.$display4.','.$display3.','.$display2.','.$display1.'&chdl=your+spending|zipcode+average&chdlp=t&chg=0,10,0,0&chma=20,0,20|0,30&chtt=weekly+zipcode+comparison of past six weeks&chts=676767,17.5" width="375" height="305" alt="weekly zipcode comparison" />';
			}
			//}			
			?>
            <div class="marketing-message">
            
            <?php if ($current_total> 0) { ?>
            	<p class="intro">Keep up the progress!</p>
            <?php } else { ?>
            	<p class="intro">This week's current statistics:</p>
            <?php } ?>
            <?php
            
			if ($compare_me==zipcode)
			{
			echo "<p class=\"dashboard\">This week, <span class=\"figure\">\$"?><?php echo money_format('%i', $avg_current) ?><?php echo "</span> has been the average for restaurants within the same zipcode</p><br />";
		}
        	echo "<p class=\"dashboard\"><span style=\"font-size: x-large; font-weight: bold; color: #82317c;\">"?><?php echo __get_total_dollar_amount();?><?php echo "</span>&nbsp;<span style=\"color:#333; line-height:1.4\">spent on North Carolina Grown Food since launch.</span></p>";
			?> 
            
            <p><span style="font-size: x-large; font-weight: bold; color: #82317c; line-height:1.4">Thank you</span> for supporting the 10% Campaign. Please take a moment to fill out a few vital questions about your businesses' weekly food purchasing.</p>
            
            </div>
		</div>	
        

		
<br /><br />
<?php
if($inside_weekly_restaurant != true){
?>
	<p><h4>To update your initial information <a href="update_initial.php?wp=<?php echo $weekly_pkey;?>&p=<?php echo $pkey;?>&h=<?php echo __calculate_weekly_hash($pkey, $weekly_pkey);?>">click here.</a></h4></p>
	
<?php	
}
?>

</form>


