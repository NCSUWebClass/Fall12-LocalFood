<?php include('header.php'); ?>

<?php
	$support_type = mysql_real_escape_string($_GET['type']);
	$support_type_description = __get_list_description('SUPPORT', $support_type);
	if ($support_type == '') {
		$btype = mysql_real_escape_string($_GET['btype']);
		$support_type_description = ucwords(strtolower($btype));
	}
?>

<div id="belowfold">
    <div id="secondary" class="two-column">

        <div id="leftcolumn">


                <h3>Our Partners</h3>  
				<ul>
					<li><a href="partners.php?type=PLP">Pledge of Purchase</a></li>
					<li><a href="partners.php?type=EMP">Employee/Member Programs</a></li>
					<li><a href="partners.php?type=PO">Promotion/Outreach</a></li>
					<li><a href="partners.php?btype=RESTAURANT">Restaurants</a></li>
				</ul>
			
        </div><!-- end leftcolumn -->

        <div id="contentcolumn">

		<h3>
			<?php echo $support_type_description;?> Partners
		</h3>
		<br /><br />
		<?php
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
			} else if ($support_type == '') {
				if ($btype == 'RESTAURANT') {
					echo "<p>The chefs at our partner restaurants strive to support North Carolina's farmers by selecting fresh, seasonal, and local ingredients, and by offering uniquely innovative menus. Supporting these businesses impacts our statewide demand for local foods, and continues to grow our local food economy.</p>";
				}
			}
		?>
		<br /><br />
		
		<?php
		if ($support_type) {
			//$result = __do_sql("select * from partners where support_type like '%$support_type%' order by name");
			$result = __do_sql("select m.bname as name, bp.url as url, bp.description as description from members m, business_partner bp where m.pkey = bp.fkey and m.kind = 'business' and m.active = 'Y' and bp.active = 'Y' and bp.support_type like '%$support_type%' order by m.bname");
			while ($r = mysql_fetch_assoc($result)) {
				if ($r['url']) {
					?>
					<p><span style="font-weight:bold;"><a href="<?php echo $r['url'];?>"><?php echo $r['name'];?></a></span><?php echo $r['description']?' - '.$r['description']:'';?></p>
					<?php
				} else {
					?>
					<p><span style="font-weight:bold;"><?php echo $r['name'];?></span><?php echo $r['description']?' - '.$r['description']:'';?></p>
					<?php
				}
			}
		} else if ($btype) {
			$result = __do_sql("select m.bname as name, m.zipcode as zipcode, bp.url as url, bp.description as description from members m, business_partner bp where m.pkey = bp.fkey and m.kind = 'business' and m.btype = 'RESTAURANT' and m.active = 'Y' and bp.active = 'Y' group by m.pkey order by m.bname");
			while ($r = mysql_fetch_assoc($result)) {
				if ($r['url']) {
					?>
					<p><span style="font-weight:bold;"><a href="<?php echo $r['url'];?>"><?php echo $r['name'];?></a></span><?php echo $r['description']?' - '.$r['description']:'';?></p>
					<?php
				} else {
					?>
					<p><span style="font-weight:bold;"><?php echo $r['name'];?></span><?php echo $r['description']?' - '.$r['description']:'';?></p>
					<?php
				}
			}
		}
		?>

		<br /><br />
        </div><!-- end #contentcolumn -->
		<br style="clear:both;" />
	</div><!-- end #secondary -->

<?php include('footer.php'); ?>