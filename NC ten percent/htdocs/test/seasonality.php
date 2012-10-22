<?php include('header.php'); ?>

<div id="belowfold">
<div id="contentfullwidth">
	<h1>Seasonality Chart</h1><br />
<div id="leftcolumn">
	<p>Eating fruits, nuts and vegetables in season is an amazing experience!  It’s also relatively easy given North Carolina’s fertile lands, long growing season and growing interest in local foods.</p>
<br />


	<p>Peanuts and the official State Vegetable – the sweet potato – are available year round because they are well-suited for safe storage. The apple season (mid-August through mid-February) offers plenty of time to savor these crunchy delights, while the strawberry season -- nothing could be finer – is typically 4-6 weeks (mid-to-late April through late May to early June). </p><br />

	<p>As North Carolina’s local food economy grows, so too does the availability of local meats, dairy and eggs.  Visit <a href="partners.php">Our Partners</a> page to find a farmer near you.</p>
</div>
<div id="rightcolumn">
	<p>Did you know fish and shellfish are also seasonal? Clams can be enjoyed year round.  Summer and autumn are ideal for shrimp while soft-blue crabs are typically enjoyed in the spring and summer. <a href="http://www.ncseagrant.org/images/stories/ncsg_pdf/documents/products/guides/seafood_availability_chart.pdf">North Carolina Sea Grant</a> has a terrific on-line seasonal guide.</p>
<br />
	<p>Below is a sampling of local foods available in the coming months. The North Carolina Department of Agriculture and Consumer Services also has a useful on-line “<a href="http://www.ncagr.gov/markets/chart.htm" target="_blank">What’s in Season</a>” chart. </p>
<br />
	<p>Enjoy!</p><br /><br /><br /><br /><br />
</div>
	<table id="newspaper-c">
	<?php 
		// Get this month and the next two
		$month[0] = date('n');
		if ($month[0] == 12)
			$month[1] = 1;
		else
			$month[1] = $month[0]+1;
		if ($month[1] == 12)
			$month[2] = 1;
		else
			$month[2] = $month[1]+1;
		if ($month[2] == 12)
			$month[3] = 1;
		else
			$month[3] = $month[2]+1;
		
		echo '<tr>';
		foreach($month as $k => $v) {
			$month_name = date('F', strtotime(date('Y').'-'.$v));
			echo '<th style="font-weight: bold;">'.strtoupper($month_name).'</th>';
		}
		echo '</tr>';
		echo '<tr>';
		foreach($month as $k => $v) {
			$result = __do_sql("select * from food_calendar where FIND_IN_SET('$v', months) order by category, name");
			echo '<td>';
			$category = '';
			while ($r = mysql_fetch_assoc($result)) {
				if ($r['category'] != $category) {
					$category = $r['category'];
					echo '<br /><span style="text-decoration: underline;">'.strtoupper($r['category']).'</span><br />';
				}
				echo $r['name'].'<br />';
			}
			echo '</td>';
		}
		echo '</tr>';
	?>
	</table>

	<p>
		Learn about <a href="http://www.ncagr.gov/markets/chart.htm" target="_blank">North Carolina's Fruit &amp; Vegetable Availability</a> and <a href="http://www.ncagr.gov/markets/seafood/index.htm" target="_blank">North Carolina's Seafood Availability</a>.
	</p>
<br /><br />
</div>

<?php include('footer.php'); ?>