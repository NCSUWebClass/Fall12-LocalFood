<?php
	// Because we have to set a cookie we need to pre-include some stuff and pre-flight since the cookie needs to be sent before any other output, etc.
	require_once('defines.php');
	require_once('db.php');
	require_once('utilities.php');
	__get_table_count('members'); // We need to do this so mysql_real_escape_string works properly
	$name = mysql_real_escape_string($_POST['name']);
	$email = mysql_real_escape_string($_POST['email']);
	$bname = mysql_real_escape_string($_POST['bname']);
	$bphone = mysql_real_escape_string($_POST['bphone']);
	$btype = mysql_real_escape_string($_POST['btype']);
	$zip = mysql_real_escape_string($_POST['zip']);
        $burl = mysql_real_escape_string($_POST['url']);
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
		$result = __do_sql("insert into members (kind, name, email, zipcode, bname, btype, bphone, url) values ('business', '$name', '$email', '$zip', '$bname', '$btype', '$bphone' , '$burl')");
		if ($result && mysql_errno() == 0) {
			$pkey = mysql_insert_id();
			
			if ($btype == 'RESTAURANT') {
				?>
				<h1>Welcome to the 10% Campaign!</h1>
				<br /><br />
				<p class="introtext">We’re thrilled that you are committed to making a difference in your community!  Being a part of the 10% Campaign strengthens our statewide effort in growing our local food economy!</p><br /><br />
				<p class="introtext">Check your email for a welcome message &mdash; it should arrive shortly. In it you will find a link to activate your account.</p><br /><br />
				<p class="introtext">Thank you for your commitment to North Carolina’s local food System.<br />&mdash; the 10% Campaign team</p>
				<?php
				
				if (__send_signup_email($pkey) == false)
					echo "<p class='introtext'>An error occurred while trying to send your welcome message but don't worry - we will try again! If you don't receive an email within 24 hours please contact us for assistance and we will get you signed up!</p>";
				
			} else {
				?>
				<h1>Welcome to the 10% Campaign!</h1>
				<br /><br />
				<p class="introtext">You've already made the choice to help build North Carolina's local food economy! By joining the 10% Campaign, you are showing a commitment to strengthen your community!</p><br /><br />
				<p class="introtext">Because we want to ensure a valuable experience to you and your business, we’d like to introduce ourselves and find out what we can do to help you support the 10% campaign. An area local food coordinator will contact you directly.</p><br /><br />
				<p class="introtext">Thank you for your commitment to North Carolina’s local food System.<br />&mdash; the 10% Campaign team</p>
				<?php		
			}
			
			__send_signup_email_to_coordinators($pkey);
			
		} else {
	    	echo "<h1>Already Registered?</h1><p class='introtext'>It seems that your email address ($email) is already in our database. Please check it and try again. If you still need help, please don't hesitate to contact us.</p>";
		}
	} else { // Bad zip or email
                 echo "<h1>Oops!</h1><p class='introtext'>Something doesn't look quite right. Please check your email address ($email) and zip code ($zip) and try again. Only NC zip codes are allowed. Sorry! If you still need help, please don't hesitate to contact us.</p>";
	}
	?>
	
    <br /><br /><br /><br /><br /><br />
</div><!-- end #contentfullwidth -->


<?php include('footer.php'); ?>