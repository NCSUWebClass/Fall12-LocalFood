<h2>Hello<?php $name = __get_member_field($pkey, 'name'); if ($name) echo " $name";?>!</h2>
<p>
	Thank you for joining the 10% Campaign.
</p>

<br /><br />
<h3>Why 10 percent?</h3>
<p>
	North Carolinians spend about $35 billion a year on food. If individuals spend just 10% on local foods, ($1.05 per day) that $3.5 billion would be available in the local economy.
</p>

<br /><br />
<h3>Help us track our statewide progress!</h3>
<p>
	We’ll send you a weekly email asking a few important questions about your progress towards making your food choices 10% local.
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
		
		<tr>
			<td><p>How many people are in your household?</p></td>
			<td width="35%"><?php __create_select_options(__get_list_type("HOUSEHOLD"), "household", true); ?></td>
		</tr>
		
		<tr align="top">
			<td><p>Approximately how much money does your household spend on food per week? (The average cost per person per week is $77. Total food spending includes restaurants, grocery shopping, farmer's markets, etc.)</p></td>
			<td width="35%"><input class="digits" type="text" name="weekly_spend_amount" value="" id="weekly_spend_amount" size="5"></td>
		</tr>
		<!--
		<tr>
			<td>In the past year, have you purchased food from local sources on a regular basis? (ie: farmers' markets, road-side stands, pick-your-own farms, consumer-supported agriculture groups (CSA), etc.)<br /><br /></td>
			<td><?php __create_select_options(__get_list_type("YN"), "regular_local", true); ?></td>
		</tr>
		-->
		<tr>
			<td><p>Where do you find locally produced food? Check all that apply:<br /><br />* Growing your own food counts toward your local food purchases.</p></td>
			<td><p><?php __create_checkbox_options(__get_list_type("SOURCE"), "regular_local_sources"); ?></p></td>
		</tr>

		<tr>
			<td>Where did you hear about The 10% Campaign?<br /><br /></td>
			<td><?php __create_select_options(__get_list_type("HEAR"), "heard_about_campaign", true, "", false, 'onchange="if ($(\'#heard_about_campaign\').val() == \'OTHER\') $(\'#heard_about_campaign_other\').show(); else $(\'#heard_about_campaign_other\').hide();"'); ?><br /><br />
				<input style="display:none;" type="text" name="heard_about_campaign_other" value="" id="heard_about_campaign_other" />
			</td>
		</tr>

		<tr>
			<td><p>Thank you, we’re looking forward to seeing you next week!</p></td>
			<td><p><input type="submit" value="Submit"></p></td>
		</tr>
	</table>

</form>
