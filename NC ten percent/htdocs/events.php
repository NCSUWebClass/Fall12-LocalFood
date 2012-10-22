<?php include('header.php'); ?>

<div id="belowfold">
<div id="contentleft" style="text-align:center">
<!--<img src="img/events_side_img.png" style="padding-bottom:20px" /><br />-->
<a href="http://www.lucky32.com/index.html" target="_blank"><img src="img/lucky32.png" style="padding-bottom:0px" border="0" /></a><br />
<a href="http://earlygirleatery.com" target="_blank"><img src="img/eatery.png" style="padding-bottom:20px; margin-top:-7px" border="0" /></a><br />
<img src="img/events-1.jpg" /><br /><br />
<img src="img/events-2.jpg" /><br /><br />
<img src="img/events-3.jpg" /><br /><br />
</div>
<div id="contentright">
	<h1>Events</h1>
	<br /><br />
    <img src="img/events_img.jpg" />
    <br /><br /><br />
	
	<table id="one-column-emphasis">
	<?php
	
	/*
	$result = __do_db_sql(DATABASE_NAME_CALENDAR, "select * from events where start_date >= Now() and 
		cancelled is null and
		event_id in (select event_id from event_program_area where pa_id = '16')
		order by start_date
		LIMIT 100");
	while ($r = mysql_fetch_assoc($result)) {
		$link = 'http://www.ces.ncsu.edu/index.php?page=events&event_id='.$r['event_id'];
		echo '<tr><td nowrap>'.date("M j, Y", strtotime($r['start_date'])).'</td><td nowrap><a href="'.$link.'">'.$r['title'].'</a></td><td nowrap>'.__format_city_state($r['city'], $r['state']).'</td></tr>';
	}
	*/
	
	$result = __ncce_request('get_calendar_items', '', 'gcal');
	foreach ($result as $k => $r) {
		$link = $r['link'];
		echo '<tr><td nowrap>'.date("M j, Y", strtotime($r['start_date'])).'</td><td nowrap><a href="'.$link.'">'.$r['title'].'</a><br /><span style="color:gray;">'.$r['city'].'</span></td></tr>';
	}

	?>
	</table>

</div><!-- end #contentright -->


    <br /><br /><br /><br /><br />

<?php include('footer.php'); ?>