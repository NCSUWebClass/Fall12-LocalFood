<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../img/favicon.ico" />
	<title>The 10% Campaign Admin: Business Editor</title>
	<style type="text/css" media="screen">
		body { font: 100% "Trebuchet MS", sans-serif;line-height: 1.2em; }
		a:link {color:#052d5d;}
		a:visited { color:#11335b;}
		a:hover {color:#0156bc;}
		.blue { color: #5D7EA3; }
		.gray { color: gray; }
	</style>
    <link type="text/css" href="../css/ui-lightness/jquery-ui-1.8.custom.css" rel="stylesheet" />	
    <script type="text/javascript" src="../js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../js/jquery-ui-1.8.custom.min.js"></script>
	<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="../js/additional-methods.js"></script>
	<script type="text/javascript">
	jQuery.validator.setDefaults({
		debug: true,
		success: "valid"
	});;
	</script>
</head>
<?php
	require_once('../defines.php');
	require_once('../db.php');
	require_once('../ncce_connect.php');
	require_once('../utilities.php');
	__get_table_count('members'); // We need to do this so mysql_real_escape_string works properly
	if (!__is_lfc() && !__is_admin_user()) { echo 'Access denied'; exit; }
?>
<body bgcolor="#F8F8F8">

<script type="text/javascript" charset="utf-8">

function abc()
{
	if (document.getElementById('chain').checked==true)
	{
		document.getElementById('chainlist')style.visibility='hidden';
	}
	else
	{
		document.getElementById('chainlist').style.visibility='visible';
	}
}


function calculate_percent()
{
	var t = $('#total_food').val();
	var p = $('#percent').val();
	var l = t * (p/100);
	$('#local_food').val(l);
}
		
</script>

<p><a href="index.php">&laquo; Return to Admin Home</a></p>

<h2 class="blue">Business Editor</h2>

<?php
$pkey = mysql_real_escape_string($_GET['p']);
if (__is_business($pkey)) {
	if ($_POST['mode'] == '') {
		$bname = __get_member_field($pkey, 'bname');
		$btype = __get_member_field($pkey, 'btype');
		$sbtype = __get_member_field($pkey, 'sbtype');
		$zipcode = __get_member_field($pkey, 'zipcode');
		$statewide = __get_member_field($pkey, 'statewide');
		$name = __get_member_field($pkey, 'name');
		$email = __get_member_field($pkey, 'email');
		$bphone = __get_member_field($pkey, 'bphone');
		$registered_on = __get_member_field($pkey, 'registered_on');
		$active = __get_member_field($pkey, 'active'); 
		$opted_out_on = __get_member_field($pkey, 'opted_out_on');
                $burl = __get_member_field($pkey,'url');

		$support_type = __get_business_partner_active_support_types($pkey);
		$calc_freq = __get_business_data_field($pkey, 'calc_freq');
		$total_food = __get_business_data_field($pkey, 'total_food');
		$local_food = __get_business_data_field($pkey, 'local_food');
		$notes = __get_business_data_field($pkey, 'notes');
		$dept = __get_business_data_field($pkey, 'dept');

		?>
		<form action="business_edit.php" method="post" accept-charset="utf-8">
			<input type="hidden" name="mode" value="save" id="mode">
			<input type="hidden" name="pkey" value="<?php echo $pkey;?>" id="pkey">
			
			<table style="border: solid 1px gray; background-color: #eee; font-size: smaller;" border="0" cellspacing="3" cellpadding="3">
				<tr><td>Name</td>
				<td><input type="text" name="bname" value="<?php echo $bname;?>" id="bname" size="50"></td></tr>
				<td><input type="checkbox" name="chain" id="chain" <?php $res=__do_sql("SELECT * from members where pkey=".$pkey." and cnum <> 0");
				if (mysql_num_rows($res)!=0) echo 'checked';
				?> onclick="abc()">Is a part of a chain  &nbsp; &nbsp;
				<select name="chainlist" id="chainlist">
				<option value=0></option>
				<?php
				$res=__do_sql("SELECT distinct cname,cnum from business_chain");
				while ($rr=mysql_fetch_assoc($res))
				{
					$ress=__do_sql("SELECT distinct cnum from members where pkey=".$pkey." and cnum=".$rr['cnum']);
					echo "<option value=".$rr['cnum'];
					if (mysql_num_rows($ress)>0)
					{
						echo " selected";
					}
					echo ">".$rr['cname']."</option>";
				}
				?>
				</select>
				</td>

				<tr><td>Department/Branch</td>
				<td><input type="text" name="dept" value="<?php echo $dept;?>" id="dept" size="50"></td></tr>
				
				<tr><td>Type</td>
				<td><?php echo __create_select_options(__get_list_type("BIZORG"), "btype", true, $btype);?></td></tr>
				
				<tr><td>Secondary type</td>
                <td><?php echo __create_select_options(__get_list_type("BIZORG"), "sbtype",  true, $sbtype);?></td></tr>
                <tr><td>Statewide?<br />If Yes, will appear across statewide in 'Our partners'</td>
				<td><?php __create_select_options(__get_list_type("YN"), "statewide", true, $statewide);?></td></tr>
				
				<tr><td>Zip</td>
				<td><input type="text" name="zipcode" value="<?php echo $zipcode;?>" id="zipcode" size="50"></td></tr>
				
				<tr><td>Contact Name</td>
				<td><input type="text" name="name" value="<?php echo $name;?>" id="name" size="50"></td></tr>
				
				<tr><td>Contact Email (required)</td>
				<td><input type="text" name="email" value="<?php echo $email;?>" id="email" size="50"></td></tr>
				
				<tr><td>Contact Phone</td>
				<td><input type="text" name="bphone" value="<?php echo $bphone;?>" id="bphone" size="50"></td></tr>
				
				<tr><td>Registered</td>
				<td><?php echo $registered_on;?></td></tr>
				
				<tr><td>Initial Contact made</td><td><input type="checkbox" name="initial" id="initial" value="yes"
				<?php 
				/*$result= __do_sql("select initial_contact_yn,initial_contact from members where pkey=".$pkey);
				while ($rr=mysql_fetch_assoc($result))
				{
					$yn=$rr['initial_contact_yn'];
					$time=$rr['initial_contact'];
				}*/
				$yn=__get_member_field($pkey,'initial_contact_yn');
				$time=__get_member_field($pkey,'initial_contact');
				if ($yn=='Y')
				{	echo ' checked>';
					echo 'Initial contact made on ';
					echo $time;
					echo '<br/>';
				}
				if ($yn=='N')
				{	echo '>';
					echo 'Initial contact made';
					echo '<br/>';
				}
				?>
				</td></tr>
				
				<tr><td>Active<br />If Yes, emails may be sent</td>
				<td><?php __create_select_options(__get_list_type("YN"), "active", true, $active);?></td></tr>
				
				<tr><td>Opted Out</td>
				<td><?php echo str_replace('0000-00-00 00:00:00', 'No', $opted_out_on);?></td></tr>
								
				<tr><td colspan="2"><hr /></td></tr>
				<tr><td><input type="checkbox" name="checkemail" id="checkemail" <?php $d = __get_member_field($pkey,'automatic_email');  if($d=='Y') echo 'checked'; ?>> Automatic e-mail reminders enabled</td></tr>
				<tr><td>Reminder e-mail:</td><td><input type="text" name="cemail" id="cemail" value="<?php echo __get_member_field($pkey,'contact_email'); ?>" size="60"></td></tr>
				<tr><td>Frequency of reporting:</td><td>
				<?php $fr = __get_member_field($pkey,'freq_reminder'); ?>
				<input type="radio" name="group2" id="group2" value="M" <?php if($fr == 'M') echo ' checked'; ?>>Monthly<br/>
				<input type="radio" name="group2" id="group2" value="Q" <?php if($fr == 'Q') echo ' checked'; ?>>Quarterly<br/>
				<input type="radio" name="group2" id="group2" value="Y" <?php if($fr == 'Y') echo ' checked'; ?>>Yearly</td></tr>
				<tr><td>Whole dollar amount of TOTAL <br /> local food purchased so far </td><td>$ <?php if ($total_food!=0) echo $total_food; else echo '0';?></td></tr>
				
				<tr><td>Whole dollar amount of <br /> local food purchased during the period</td>
				<td><input class="digits" type="text" name="local_food" value="" id="local_food" size="10"> &nbsp; Month <?php echo __create_select_options(__get_list_type("MONTH"), "mth", true, $mth);?> &nbsp; Year <?php echo __create_select_options(__get_list_type("YEAR"), "yr", true, $yr);?></td>
				<?php if(__get_business_data_field($pkey,'last_edited_by')) { ?><td>Last edited by <?php echo __get_business_data_field($pkey,'last_edited_by');?> on <?php echo __get_business_data_field ($pkey, 'last_edited_on');?></td></tr><?php } ?>
				<tr><td> <a href= <?php echo "history.php?p=".$pkey?>> View past records</a></td></tr>

				<tr><td colspan="2"><hr /></td></tr>

				<tr valign="top"><td>Notes</td>
				<td><textarea name="notes" rows="8" cols="40"><?php echo $notes;?></textarea></td></tr>
				<tr><td colspan="2"><hr /></td></tr>
				
				<tr valign="top"><td>Campaign support type</td>
				<td><?php __create_checkbox_options(__get_list_type("SUPPORT"), "support_type", $support_type);?></td></tr>
                                <tr valign="top"><td>Member URL entered</td>
                                    <?php $murl = __get_member_field($pkey,'url');?>
                                    <td><input type="text" name="murl" value="<?php echo $murl?>" size="50"></input></tr></td>
                                
				
				<tr valign="top"><td>Campaign support details<br />URLs must contain http://</td>
				<td>
				<?php
					$result = __get_list_type("SUPPORT");
					while ($r = mysql_fetch_assoc($result)) {
						$description = $r['description'];
						$code = $r['code'];
						echo $description.' ';
						
						$name = $code.'_url';
						$value = __get_business_partner_field($pkey, 'url', $code);
						echo 'URL<br /><input type="text" name="'.$name.'" value="'.$value.'" id="'.$name.'" size="50"><br />';
						
						$name = $code.'_description';
						$value = __get_business_partner_field($pkey, 'description', $code);
						echo 'Description<br /><textarea name="'.$name.'" rows="4" cols="40">'.$value.'</textarea><br /><br />';
					}
				?>	
					
				</td></tr>
				
			</table>
			
			<p><input type="submit" value="Save Changes"></p>
		</form>
		<?php
	} else echo '<p>An error occurred.</p>';
} else  if ($_POST['mode'] == 'save') {
	
	$pkey = mysql_real_escape_string($_POST['pkey']);
	$bname = mysql_real_escape_string($_POST['bname']);
	$btype = mysql_real_escape_string($_POST['btype']);
	$statewide = mysql_real_escape_string($_POST['statewide']);
	$zipcode = mysql_real_escape_string($_POST['zipcode']);
	$name = mysql_real_escape_string($_POST['name']);
	$email = mysql_real_escape_string($_POST['email']);
	$active = mysql_real_escape_string($_POST['active']);
	$phnum = mysql_real_escape_string($_POST['bphone']);
	$sbtype = mysql_real_escape_string($_POST['sbtype']);
	$freq = mysql_real_escape_string($_POST['group2']);
	$yr = mysql_real_escape_string($_POST['yr']);
	$mth = mysql_real_escape_string($_POST['mth']);
	if (!isset($_POST['chain']))
	{
	$chainid=0;
	}
	if (isset($_POST['chain']))
	{
		$chainid=$_POST['chainlist'];
	}
	$cemail = mysql_real_escape_string($_POST['cemail']);
	if (isset($_POST['checkemail']))
	{
		__do_sql("update members set automatic_email='Y' , freq_reminder = '$freq' where pkey= '$pkey'");
	}
	else if (!isset($_POST['checkemail']))
	{	
		__do_sql("update members set automatic_email='N' where pkey= '$pkey'");
	}
	$result = __do_sql("update members set active = '$active', bname = '$bname', btype = '$btype', statewide = '$statewide', zipcode = '$zipcode', name = '$name', email = '$email' , bphone = '$phnum' , sbtype = '$sbtype' , cnum = '$chainid' , contact_email = '$cemail' where pkey = '$pkey' LIMIT 1");
	if ($result && ((mysql_affected_rows() == 1) || (mysql_affected_rows() == 0 && mysql_errno() == 0))) {
		
		// BUSINESS PARTNER
		$whocares = __do_sql("delete from business_partner where fkey = '$pkey'"); // do our best!
		$result = __get_list_type("SUPPORT");
		while ($r = mysql_fetch_assoc($result)) {
			$support_type = $r['code'];
			$active = in_array($support_type, $_POST['support_type']) ? 'Y' : 'N';
			$url = mysql_real_escape_string($_POST[$support_type.'_url']);
			$description = mysql_real_escape_string($_POST[$support_type.'_description']);;
			
			$result2 = __do_sql("insert into business_partner (fkey, support_type, url, description, active) 
				values ('$pkey', '$support_type', '$url', '$description', '$active')");
			
			if ($result2 && ((mysql_affected_rows() == 1) || (mysql_affected_rows() == 0 && mysql_errno() == 0))) {
				echo '<p style="color:green;">Changes saved to partner data '.$support_type.'</p>';
			} else {
				echo '<p style="color:red;">There was an error ('.mysql_error().'/'.mysql_errno().') saving changes to partner data '.$support_type.'!</p>';
			}
		}
		//echo 'The initial value is';
		//echo $_POST['initial'];
	 $res=__do_sql("select initial_contact_yn from members where pkey=".$pkey);
	 while ($rrr=mysql_fetch_assoc($res))
	 {
		 $intable=$rrr['initial_contact_yn'];
	 }
	 if ($_POST['initial'] == 'yes' && $intable == 'N')
	 {
		 __do_sql("update members set initial_contact = Now() where pkey=".$pkey);
		 __do_sql("update members set initial_contact_yn = 'Y' where pkey=".$pkey);
	 }
	 if ($_POST['initial'] == '' && $intable == 'Y')
	 {
		 __do_sql("update members set initial_contact='0000-00-00 00:00:00' where pkey=".$pkey);
		 __do_sql("update members set initial_contact_yn ='N' where pkey=".$pkey);
	 }
	 if ($_POST['initial'] == 'yes' && $intable == 'Y')
	 {
		 __do_sql("update members set initial_contact = Now() where pkey=".$pkey);
	 }
	 $notes1 = mysql_real_escape_string($_POST['notes']);
		if ($_POST['local_food'] != '')
		{// BUSINESS DATA
		$calc_freq = mysql_real_escape_string($_POST['calc_freq']);
		$total_food = mysql_real_escape_string($_POST['total_food']);
		$local_food = mysql_real_escape_string($_POST['local_food']);
		$dept = mysql_real_escape_string($_POST['dept']);
		
		//$whocares = __do_sql("delete from business_data where fkey = '$pkey' LIMIT 1"); // do our best!
		$sumlocal=0;
		$sumres = __do_sql ("select local_food from business_data WHERE fkey = '$pkey'");
		while ($rr =  mysql_fetch_assoc($sumres))
		{
			$sumlocal=$sumlocal+$rr['local_food'];
		}
		$sumlocal=$sumlocal+$local_food;
		$dat = $yr."-".$mth."-01 00:00:00";
		$result = __do_sql("insert into business_data (fkey, data_entered_on,data_entered_by, last_edited_on, last_edited_by, dept, calc_freq, total_food, local_food, notes) 
			values ('$pkey', '$dat' , '".__current_user_unity_id()."', Now(), '".__current_user_unity_id()."', '$dept', '$calc_freq', '$sumlocal', '$local_food', '$notes1')");
		if ($result && ((mysql_affected_rows() == 1) || (mysql_affected_rows() == 0 && mysql_errno() == 0))) {
			echo '<p style="color:green;">Changes saved to extra data</p>';
		} else {
			echo '<p style="color:red;">There was an error ('.mysql_error().'/'.mysql_errno().') saving changes to extra data</p>';
		}
	}
	else
	{
		$ress=__do_sql("select pkey from business_data where fkey=".$pkey);
		$max=0;
		while ($rr=mysql_fetch_assoc($ress))
		{
			if ($rr['pkey']>$max)
			{
				$max=$rr['pkey'];
			}
		}
		if ($max==0)
		{
			__do_sql("insert into business_data (fkey,last_edited_on,last_edited_by,notes) VALUES ('".$pkey."',Now(),'".__current_user_unity_id()."','".$notes1."')");
		}
		else
		{
		$result = __do_sql("update business_data set notes= '".$notes1."' where pkey=".$max);
		}
		}} else {
		echo '<p style="color:red;">There was an error ('.mysql_error().'/'.mysql_errno().') saving changes to main data</p>';
	}

	echo '<p><a href="business_edit.php?p='.$pkey.'">&laquo; Return to Business</a><br /></p>';
		
} else echo '<p>This member is not a business.</p>';

echo '<p><a href="business_list.php">&laquo; Return to List</a></p>';
?>
</body>
</html>
