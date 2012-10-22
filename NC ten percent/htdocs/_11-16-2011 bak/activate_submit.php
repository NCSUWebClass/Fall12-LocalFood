<?php include('header.php'); ?>
<?php
	//print_r($_POST);
	$pkey = mysql_real_escape_string($_POST['p']);
	$hash = mysql_real_escape_string($_POST['h']);
?>

<div id="belowfold">
<div id="secondary">

	<?php 
	if (__calculate_activation_hash($pkey) == $hash) {
		if (__is_consumer($pkey)) {
			include('activate_submit_consumer.php');
		} else if (__is_business_type($pkey, 'RESTAURANT')) {
			include('activate_submit_restaurant.php');
		} else echo '<h2>There was an error submitting your data. Please contact us.</h2>';
	} else {
		echo "<h2>Oops!</h2><p>That didn't seem to work right. Please try using your browser's back button and submit again.</p>";
	}
	?>
	
</div><!-- end #secondary -->

<?php include('footer.php'); ?>