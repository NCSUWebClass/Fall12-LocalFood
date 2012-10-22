

<h2>Hi<?php $name = __get_member_field($pkey, 'bname'); if ($name) echo " $name";?>, welcome back!</h2>

<br /><br />

<?php
$inside_weekly_restaurant = true;
include('dashboard_restaurant.php');
?>

<!--<p>Thank you for supporting the 10% Campaign. Please take a moment to fill out a few vital questions about your businesses’ weekly food purchasing.</p>-->

<form id="myform" action="weekly_submit.php" method="post" accept-charset="utf-8">

	<input type="hidden" name="wp" value="<?php echo $weekly_pkey;?>" id="wp">
	<input type="hidden" name="p" value="<?php echo $pkey;?>" id="p">
	<input type="hidden" name="h" value="<?php echo __calculate_weekly_hash($pkey, $weekly_pkey);?>" id="h">
					
	<table border="0">
		
		<tr>
			<td><p>Did you source locally this week? &nbsp;&nbsp; <?php __create_select_options(__get_list_type("YN"), "source_local_this_week", true); ?><br />
            
            &nbsp;&nbsp;&nbsp;If YES, what farms did you purchase from this week?  
			    <input type="text" name="source_local_farms" value="<?php echo __get_previous_farms($pkey);?>" id="source_local_farms" size="50">
			</p>
            
            </td>
			
		</tr>
		
        <tr>
			<td><p>If NO, why not? &nbsp;&nbsp;<?php __create_select_options(__get_list_type("WHYNOT"), "source_local_why_not", true); ?></p></td>
		</tr>

		<tr>
			<td><p>Please estimate your local food purchase this week. (enter a whole dollar amount)&nbsp;&nbsp;&nbsp;<input class="digits" type="text" name="purchase_this_week" value="" id="purchase_this_week" size="5"></td>
		</tr>

		
		
		
		<tr>
			<td colspan="2"><br /><br /><input type="submit" value="Submit"></td>
		</tr>
	</table>

</form>
