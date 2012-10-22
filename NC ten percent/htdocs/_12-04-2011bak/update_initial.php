<?php include('header.php'); ?>
<?php
	$pkey = mysql_real_escape_string($_GET['p']);
	$hash = mysql_real_escape_string($_GET['h']);
	$weekly_pkey = mysql_real_escape_string($_GET['wp']);
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
		$result = __get_member_field($pkey, 'active');
		if (($result == 'Y') || $_GET['ok']) {
			if (__is_consumer($pkey) || $_GET['ok']==1) {
				include('update_initial_consumer.php');
			}else echo '<h2>There was an error in allowing you to update your account. Please contact us.</h2>';
		} else {
 			echo "<p>Your account has not been activated. Please activate your account before updating it. Contact us if you need assistance.</p>";
		}
	} else {
		echo "<h2>Oops!</h2><p>That didn't seem to work right - please try clicking or copying the link again. If you still need help, please don't hesitate to contact us.</p>";
	}
	?>
	
</div><!-- end #secondary -->

<?php include('footer.php'); ?>