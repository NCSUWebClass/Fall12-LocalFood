<?php include('header.php'); ?>
<?php
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
<div id="contentfullwidth">

	<?php 
	if (__calculate_activation_hash($pkey) == $hash || $_GET['ok']) {
		$result = __do_sql("update members set active = 'Y' where pkey = '$pkey' and active = 'N' limit 1");
		if ((mysql_errno() == 0 && mysql_affected_rows() == 1) || $_GET['ok']) {
			if (__is_consumer($pkey) || $_GET['ok']==1) {
				include('activate_consumer.php');
			} else if (__is_business_type($pkey, 'RESTAURANT') || $_GET['ok']==2) {
				include('activate_restaurant.php');
			} else echo '<br><br><p class="introtext">There was an error activating your account. Please contact us.</p>';
		} else {
 			echo "<br><br><p class='introtext'>Already Activated?</h2><p>There was an error activating your account. Maybe it has already been activated. Please contact us if you need assistance.</p><br><br><br>";
		}
	} else {
		echo "<br><br><p class='introtext'>Oops!</p><p>That didn't seem to work right - please try clicking or copying the link in your email again. If you still need help, please don't hesitate to contact us.</p><br><br><br>";
	}
	?>
	
</div><!-- end #secondary -->

<?php include('footer.php'); ?>