<?php include('header.php'); ?>

<?
	$support_type = mysql_real_escape_string($_GET['type']);
	$support_type_description = __get_list_description('SUPPORT', $support_type);
?>

<div id="belowfold">
	<div id="secondary">
		<h3>
			<?=$support_type_description;?> Partners
		</h3>
		<br /><br />
		<?
			if ($support_type == 'PLP') {
				echo "<p>These businesses and organizations are committing to sourcing at least 10 percent
				locally grown and produced foods (and many are sourcing much more!) for
				use in their restaurants, employee cafeterias, at events, or catered
				meetings. There are many ways to participate at this level, check our
				partners list to see how others are committed to a Pledge of Purchase
				partnership. Sign up today!</p>";
			} else if ($support_type == 'EMP') {
				echo "<p>Support the <span style=\"font-weight:bold;\">10% Campaign</span> by encouraging employees or members to join as individuals. There are many fun ways to promote internally. Join the participating businesses and organizations who are making a difference in their workplace. </p>";
			} else if ($support_type == 'PO') {
				echo "<p>Support the <span style=\"font-weight:bold;\">10% Campaign</span> on a website, to listserv(s), or through public events. Sign up today and help us build North Carolina's local food economy!</p>";
			}
		?>
		<br /><br />
		
		<?
		$result = __do_sql("select * from partners where support_type like '%$support_type%' order by name");
		while ($r = mysql_fetch_assoc($result)) {
		?>
			<p><span style="font-weight:bold;"><a href="<?=$r['url'];?>"><?=$r['name'];?></a></span> - <?=$r['description'];?></p>
		<?
		}
		?>

		<br /><br />
	</div><!-- end #secondary -->

<?php include('footer.php'); ?>