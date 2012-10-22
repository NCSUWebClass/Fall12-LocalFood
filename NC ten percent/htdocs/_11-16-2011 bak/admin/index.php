<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../img/favicon.ico" />
	<title>The 10% Campaign Admin</title>
	<style type="text/css" media="screen">
		body { font: 100% "Trebuchet MS", sans-serif;line-height: 1.2em; }
		a:link {color:#052d5d;}
		a:visited { color:#11335b;}
		a:hover {color:#0156bc;}
		.blue { color: #5D7EA3; }
		.gray { color: gray; }
	</style>
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
	
<span style="float:right;" >
	<img src="../img/logos/logo-radish.png" border="0" /><br /><br />
	<a style="position:relative; left:25px;" href="mailto:nc10percent@ncsu.edu">nc10percent@ncsu.edu</a><br />
	<a style="position:relative; left:25px;" href="http://nc10percent.com">nc10percent.com</a><br />
</span>

<h2 class="blue">Admin</h2>
	
<p class="gray">
	<?php
		echo "The campaign has been running for ".campaign_days()." days";
		function campaign_days()
		{
			$campaign_seconds = mktime() - strtotime('2010-07-19 16:03:41'); // start of campaign based on first activation
			$campaign_minutes = $campaign_seconds / 60;
			$campaign_hours = $campaign_minutes / 60;
			$campaign_days = $campaign_hours / 24;
			return floor($campaign_days);
		}

		function campaign_weeks()
		{
			$campaign_weeks = campaign_days() / 7;
			return floor($campaign_weeks);
		}

		function campaign_years()
		{
			$campaign_years = campaign_weeks() / 52;
			return floor($campaign_years);
		}

	?>
	
	<!-- MEMBERS -->
	<h3 style="position:relative; top:10px" class="blue">Members</h3>
	
	CONSUMERS<br />
	<blockquote>
	<?php
		$activated = __get_table_count('members', "where kind='consumer' and active='Y' and opted_out='N'");
		$members = __get_member_count_by_kind('consumer');
	?>
	<?php echo $activated;?> of <?php echo $members;?> consumers activated (<?php echo round($activated/$members*100,2).'%';?>) / 
	<?php
		$answered = __get_table_count('consumer_data_weekly', "where answered_on <> '0000-00-00 00:00:00'");
		$not_answered = __get_table_count('consumer_data_weekly', "where answered_on = '0000-00-00 00:00:00'");
	?>
	<?php echo $answered;?> of <?php echo ($answered+$not_answered);?> weekly responses answered (<?php echo round($answered/($answered+$not_answered)*100, 2).'%';?>)<br />
	<?php
		$reported_weekly_spend = __get_consumer_weekly_spend_amount();
		$actual_weekly_spend = __get_consumer_actual_spend_amount(true, false);
		$garden_weekly_offset = __get_consumer_actual_spend_amount(false, true);
	?>	
	total reported weekly spend amount from initial survey: <?php echo __fm($reported_weekly_spend);?> <!--(<? //=__fm($reported_weekly_spend * campaign_weeks());?> since start)--><br />
	total actual spend amount since campaign start: <?php echo __fm($actual_weekly_spend);?> <!--(<? //=round($actual_weekly_spend/($reported_weekly_spend * campaign_weeks())*100, 2);?>% overall since start)--><br />
	garden offset amount since campaign start: <?php echo __fm($garden_weekly_offset);?><br />
	</blockquote>
	
	BUSINESSES<br />
	<blockquote>
	<?php echo __get_member_count_by_kind('business', "and btype='RESTAURANT'");?> restaurants / <?php echo __get_member_count_by_kind('business', "and btype<>'RESTAURANT'");?> other businesses<br />
	<?php
		$restaurant_reported_annual_spend = __get_restaurant_annual_spend_amount();
		$restaurant_actual_weekly_spend = __get_restaurant_actual_spend_amount();
		// TODO - After 1st year revisit this calculation to show average spent per year or whatever
	?>	
	total reported annual spend amount for restaurants from initial survey: <?php echo __fm($restaurant_reported_annual_spend);?><br />
	total actual spend amount for restaurants since campaign start: <?php echo __fm($restaurant_actual_weekly_spend);?><br />
	total actual spend amount for all businesses based on recurring data: <?php echo __fm(__get_business_actual_spend_amount());?>
	</blockquote>
	
	TOTALS<br />
	<blockquote>
	<?php echo __get_total_dollar_amount();?> spent locally (as shown on front page, does not include garden offset)<br />
	</blockquote>

	<!-- ZIP CODES -->
	<h3 style="position:relative; top:10px" class="blue">Zip Codes</h3>
	<?php
	$result = __do_sql("select count(distinct zipcode) as dz from members where kind='consumer'");
	if ($result && mysql_num_rows($result) == 1)
		echo mysql_result($result, 0, "dz") . ' consumer<br />';
	$result = __do_sql("select count(distinct zipcode) as dz from members where kind='business'");
	if ($result && mysql_num_rows($result) == 1)
		echo mysql_result($result, 0, "dz") . ' business<br />';
	$result = __do_sql("select count(distinct zipcode) as dz from members");
	if ($result && mysql_num_rows($result) == 1)
		echo mysql_result($result, 0, "dz") . ' overall<br />';
	?>
	
	<!-- BUSINESSES -->
	<h3 style="position:relative; top:10px" class="blue">Businesses</h3>
	<a href="business_list.php">Businesses</a> &raquo; <span style="font-size:smaller;">keep up with the business/organization members in your county</span><br />
	
	<!-- REPORTS -->
	<h3 style="position:relative; top:10px" class="blue">Reports</h3>
	<a href="cinfo.php">County Coordinators</a> &raquo; <span style="font-size:smaller;">a list of county coordinator contact information</span><br />
	<a href="http://www.ces.ncsu.edu/admin/wiki/index.php/NC_10Percent">NC 10% Wiki</a> &raquo; <span style="font-size:smaller;">agent resources and more</span><br />
	
	<!-- SUPERUSER ONLY -->
	<h3 style="position:relative; top:10px" class="blue">Superuser Only</h3>
	<?php if (__is_admin_user()) { ?>
		<a href="coordinator_list.php">County Coordinators</a> &raquo; <span style="font-size:smaller;">an editable list of county coordinators</span><br />
		<!--<a href="partner_list.php">Partners</a> &raquo; <span style="font-size:smaller;">names, links, descriptions and support type</span><br />-->
		<a href="http://www.ces.ncsu.edu/admin/wiki/index.php/NC_10Percent_CEFS_Requests">CEFS Requests</a> &raquo; <span style="font-size:smaller;">document requests, reports, etc. here</span><br />
		<a href="http://google.com/analytics">Google Analytics</a> &raquo; <span style="font-size:smaller;">reports and statistics</span><br />
		
		<br />
		<!-- export tables as csv -->
		<form action="_table2csv.php" method="post" accept-charset="utf-8">
			CSV export of 
			<select name="table" id="table">
				<option value="business_data">business_data</option>
				<option value="business_partner">business_partner</option>
				<option value="consumer_data_initial">consumer_data_initial</option>
				<option value="consumer_data_weekly">consumer_data_weekly</option>
				<option value="food_calendar">food_calendar</option>
				<option value="list">list</option>
				<option value="members">members</option>
				<option value="pre_launch_members">pre_launch_members</option>
				<option value="restaurant_data_initial">restaurant_data_initial</option>
				<option value="restaurant_data_weekly">restaurant_data_weekly</option>
				<option value="zipcodes">zipcodes</option>
			</select>
			<input type="submit" value="Download">
		</form>

		<br />
		<a href="phpMyAdmin/">phpMyAdmin</a> &raquo; <span style="font-size:smaller;">full access to mysql database (additional login credentials required)</span><br />
		
	<?php } ?>
	
</p>

</body>
</html>
