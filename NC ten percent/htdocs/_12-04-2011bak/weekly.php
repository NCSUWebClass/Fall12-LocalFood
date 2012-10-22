<?php include('header.php'); ?>
<?php
	$weekly_pkey = mysql_real_escape_string($_GET['wp']);
	$pkey = mysql_real_escape_string($_GET['p']);
	$hash = mysql_real_escape_string($_GET['h']);
?>

<script>
$(document).ready(function(){
	$("#myform").validate({
		submitHandler: function(form) {
  			form.submit();
		}
	});
});
</script>
<style>
	input.error { color: red; }
	label.error {
		padding-left: 16px;
		color: red;
		font-weight: bold;
		font-size: larger;
	}
</style>

<div id="belowfold">
<div id="secondary">

	<?php 
	if (__calculate_weekly_hash($pkey, $weekly_pkey) == $hash || $_GET['ok']) {
		if (__is_consumer($pkey) || $_GET['ok']==1) {
			include('weekly_consumer.php');
		} else if (__is_business_type($pkey, 'RESTAURANT') || $_GET['ok']==2) {
			include('weekly_restaurant.php');
		} else echo '<h2>There was an error. Please contact us.</h2>';
	} else {
		echo "<h2>Oops!</h2><p>That didn't seem to work right - please try clicking or copying the link in your email again. If you still need help, please don't hesitate to contact us.</p>";
	}
	?>
	
</div><!-- end #secondary -->

<?php include('footer.php'); ?>