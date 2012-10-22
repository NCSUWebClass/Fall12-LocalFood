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
			include('dashboard_consumer.php');
		} else echo '<h2>There was an error accessing your dashboard. Please contact us.</h2>';
	} else {
		echo "<h2>Oops!</h2><p>That didn't seem to work right. Please try using your browser's back button and clicking the link again.</p>";
	}
	?>
	
</div><!-- end #secondary -->

<?php include('footer.php'); ?>