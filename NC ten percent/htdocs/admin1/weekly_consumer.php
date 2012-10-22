<p class="intro">Hi<?php $name = __get_member_field($pkey, 'name'); if ($name) echo " $name";?>, How are you doing this week? Let's get started.</p>

<br /><br />

<?php
$inside_weekly_consumer = true;
include('dashboard_consumer.php');
?>        

<br /><br />

<script type='text/javascript'>
function validateAmount()
{
	var x = document.getElementById('amount');
	var xval = x.value;
	var xFloat=parseFloat(xval);
	if (xval>1000)
	{
	var y = confirm ("You have entered: $" + xFloat.toFixed(2) + '\n' + "Are you sure?");
	if (y==false)
	{
		return false;
	}
	if (y==true)
	{
		return true;
	}
	}
	else
	{
	return true;
	}
}

</script>


<form id="myform" action="weekly_submit.php" method="post" accept-charset="utf-8">

	<input type="hidden" name="wp" value="<?php echo $weekly_pkey;?>" id="wp">
	<input type="hidden" name="p" value="<?php echo $pkey;?>" id="p">
	<input type="hidden" name="h" value="<?php echo __calculate_weekly_hash($pkey, $weekly_pkey);?>" id="h">
					
	<table border="0" cellspacing="5" cellpadding="5" style="line-height:1.3; font-size:12px">
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
		<tr><td colspan="2" height="5"></td></tr>
		<tr>
			<td><p>Where did you find local foods this week? Check all that apply</p></td>
			<td><p><?php __create_checkbox_options(__get_list_type("SOURCE"), "sources"); ?></p></td>
		</tr>
		<tr><td colspan="2" height="5"></td></tr>
		
		<tr>
			<td><p>Many folks are harvesting gardens, collecting eggs, or hunting and fishing to feed their families. Please input the dollar amount you saved by harvesting your own food this week.</p></td>
			<td width="35%"><input class="digits" type="text" name="garden_amount" value="" id="garden_amount" size="5"></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="Submit" onclick="return validateAmount()"><br /><br /></td>
		</tr>
		<tr><td colspan="2" height="5"></td></tr>
			
	</table>

</form>