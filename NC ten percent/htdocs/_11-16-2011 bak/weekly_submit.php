<?php include('header.php'); ?>
<?php
//	print_r($_POST);
	$weekly_pkey = mysql_real_escape_string($_POST['wp']);
	$pkey = mysql_real_escape_string($_POST['p']);
	$hash = mysql_real_escape_string($_POST['h']);
?>

<div id="belowfold">
<div id="secondary">

	<?php 
	if (__calculate_weekly_hash($pkey, $weekly_pkey) == $hash) {
		if (__is_consumer($pkey)) {
			include('weekly_submit_consumer.php');
		} else if (__is_business_type($pkey, 'RESTAURANT')) {
			include('weekly_submit_restaurant.php');
		} else echo '<h2>There was an error submitting your data. Please contact us.</h2>';
	} else {
		echo "<h2>Oops!</h2><p>That didn't seem to work right. Please try using your browser's back button and submitting again.</p>";
	}
	?>
	
</div><!-- end #secondary -->

<?php include('footer.php'); ?>