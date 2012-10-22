<h2>Hello<?php $name = __get_member_field($pkey, 'bname'); if ($name) echo " $name";?>!</h2>
<p>
	Thank you for joining the 10% Campaign.
</p>

<br /><br />
<h3>Help us track our statewide progress!</h3>
<p>
	We’ll send you a weekly email asking for a progress update. 
	Just answer three important questions about your businesses’ food purchasing. - Its that easy! - The campaign tracks this vital information statewide through you and others who’ve made the commitment to a sustainable local food economy. 
	<br /><br />
	<span style="font-weight: bold;">Encourage</span> family, friends and colleagues to join the 10% campaign!<br />
	<span style="font-weight: bold;">Source</span> North Carolina food, farmers, and businesses on our website.<br />
	<span style="font-weight: bold;">Build</span> North Carolina's sustainable future.<br />
</p>

<br /><br />
<h3>Let's Get Started</h3>

<form id="myform" action="activate_submit.php" method="post" accept-charset="utf-8">

	<input type="hidden" name="p" value="<?php echo $pkey;?>" id="p">
	<input type="hidden" name="h" value="<?php echo __calculate_activation_hash($pkey);?>" id="h">

	<table border="0" cellspacing="5" cellpadding="5">
		
		<tr align="top">
			<td><p>What is your annual food cost? (enter a whole dollar amount)</p></td>
			<td ><input class="digits" type="text" name="annual_food_cost" value="" id="annual_food_cost" size="5"></td>
		</tr>
		
		<tr align="top">
			<td><p>On average, estimate how many customers are you serving a week?</p></td>
			<td ><input class="digits" type="text" name="customers_per_week" value="" id="customers_per_week" size="5"></td>
		</tr>

		<tr>
			<td><p>Do you currently source locally produced food?</p></td>
			<td><?php __create_select_options(__get_list_type("YN"), "source_local", true); ?></td>
		</tr>

		<tr>
			<td><p>&nbsp;&nbsp;&nbsp;If YES, about what percent of your total food purchases are from local sources?&nbsp;&nbsp;&nbsp;</p></td>
			<td ><input class="digits" type="text" name="percent_local" value="" id="percent_local" size="5"></td>
		</tr>

		<tr>
			<td><p>&nbsp;&nbsp;&nbsp;If NO, would you like assistance sourcing local foods?</p></td>
			<td><?php __create_select_options(__get_list_type("YN"), "sourcing_assistance", true); ?></td>
		</tr>

		<tr>
			<td><p>What farms are you currently buying from?</p></td>
			<td ><input type="text" name="farms" value="" id="farms" size="50"></td>
		</tr>

		<tr>
			<td><p>Thank you, we’re looking forward to seeing you next week!</p></td>
			<td><p><input type="submit" value="Submit"></p></td>
		</tr>
	</table>

</form>
