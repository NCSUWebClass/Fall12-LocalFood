<?php include('header.php'); ?>
<?php
	$pkey = mysql_real_escape_string($_GET['p']);
	$hash = mysql_real_escape_string($_GET['h']);
	$confirm = mysql_real_escape_string($_GET['confirm']);
?>

<div id="belowfold">
<div id="contentfullwidth">

	<?php 
	if ($confirm != 'yes') {
	?>
	<h1>Are you sure you want to leave the campaign?</h1>
	<br /><br />
	<p class="introtext">
		<a href="unsubscribe.php?p=<?php echo $pkey;?>&h=<?php echo $hash;?>&confirm=yes">Yes, Unsubscribe me.</a>
		<br /><br />
		<a href="/">No, I want to stay in the campaign</a>
	</p>
	
	<?php	
	} else if (__calculate_unsubscribe_hash($pkey) == $hash) {
		$result = __do_sql("update members set opted_out = 'Y', opted_out_on = Now() where pkey = '$pkey' and opted_out = 'N' limit 1");
		if (mysql_errno() == 0 && mysql_affected_rows() == 1)
    		echo "<h1>We're sorry to see you go!</h1><p class='introtext'>Thank you for being a part of the campaign! Your account is no longer active and you will not receive emails from us.</p><p>Please contact us at ".EMAIL_ADDRESS." if you would ever like your account reactivated.</p>";
		else
 			echo "<h1>Already Unsubscribed?</h1><p class='introtext'>There was an error unsubscribing your account. Maybe it has already been unsubscribed. Please contact us if you need assistance.</p>";
	} else {
		echo "<h1>Oops!</h1><p class='introtext'>That didn't seem to work right - please try clicking or copying the link in your email again. If you still need help, please don't hesitate to contact us.</p>";
	}
	?>
	
</div><!-- end #secondary -->

<?php include('footer.php'); ?>