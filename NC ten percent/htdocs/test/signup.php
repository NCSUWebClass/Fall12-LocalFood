<?php
	// Because we have to set a cookie we need to pre-include some stuff and pre-flight since the cookie needs to be sent before any other output, etc.
	require_once('defines.php');
	require_once('db.php');
	require_once('utilities.php');
	__get_table_count('members'); // We need to do this so mysql_real_escape_string works properly
	$name = mysql_real_escape_string($_POST['name']);
	$email = mysql_real_escape_string($_POST['email']);
	$zip = mysql_real_escape_string($_POST['zip']);
	if (is_numeric($zip) && (strlen($zip)==5) && preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $email)) { // same test as below
		setcookie("EMAIL_COOKIE", "$email", time()+COOKIE_EXPIRE);
		setcookie("ZIP_COOKIE", "$zip", time()+COOKIE_EXPIRE);
	}
?>
<?php include('header.php'); ?>

<div id="belowfold">
<div id="contentfullwidth">

	<?php 
	if (is_numeric($zip) && (strlen($zip)==5) && (__is_valid_zipcode($zip)) && preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $email)) { // same test as above
		$result = __do_sql("insert into members (name, email, zipcode) values ('$name', '$email', '$zip')");
		if ($result && mysql_errno() == 0) {
			$pkey = mysql_insert_id();
	?>
			<h1>Welcome to the 10% Campaign!</h1>
			<br /><br />
			<p class="introtext">You've taken the first step to building North Carolina's local food economy.</p>
			
			<p class="introtext">Check your email for a welcome message - it should arrive shortly. In it you will find a link to activate your account.</p>
	
	<?php		if (__send_signup_email($pkey) == false)
				echo "<br /><br /><p class='introtext'>An error occurred while trying to send your welcome message but don't worry - we will try again! If you don't receive an email within 24 hours please contact us for assistance and we will get you signed up!</p>";
		} else {
	    	echo "<h1>Already Registered?</h1><br /><br /><p class='introtext'>It seems that your email address ($email) is already in our database. Please check it and try again. If you still need help, please don't hesitate to contact us.</p>";
		}
	} else { // Bad zip or email
		echo "<h1>Oops!</h1><br /><br /><p class='introtext'>Something doesn't look quite right. Please check your email address ($email) and zip code ($zip) and try again. Only NC zip codes are allowed. Sorry! If you still need help, please don't hesitate to contact us.</p>";
	}
	?>
	
    <br /><br /><br /><br /><br /><br />
</div><!-- end #contentfullwidth -->

<?php include('footer.php'); ?>