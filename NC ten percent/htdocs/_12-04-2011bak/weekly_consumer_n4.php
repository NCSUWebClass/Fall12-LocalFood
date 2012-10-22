<h2>Hi<?php $name = __get_member_field($pkey, 'name'); if ($name) echo " $name";?>, How are you doing this week? Let's get started.</h2>

<br /><br />

<?php
$inside_weekly_consumer = true;
include('dashboard_consumer_n4.php');
?>        

<br /><br />


<form id="myform" action="weekly_submit_n4.php" method="post" accept-charset="utf-8">

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
			<td><p>Many folks are harvesting gardens, collecting eggs, or hunting and fishing to feed their families. Please input the dollar amount you saved by harvesting your own food this week.</p></td>
			<td width="35%"><input class="digits" type="text" name="garden_amount" value="" id="garden_amount" size="5"></td>
		</tr>
			
		<tr>
			<td></td>
			<td><br /><br /><input type="submit" value="Submit"></td>
		</tr>
	</table>

</form>
