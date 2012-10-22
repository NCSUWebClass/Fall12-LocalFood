<?php 
	// ini_set('display_errors', 1);
	// error_reporting(E_ALL);
	
	require_once('defines.php');
	require_once('db.php');
	require_once('ncce_connect.php');
	require_once('utilities.php');
	__get_table_count('members'); // We need to do this so mysql_real_escape_string works properly
		
 	$email_cookie = $_COOKIE["EMAIL_COOKIE"]; // get 'em if we got 'em
	$zip_cookie = $_COOKIE["ZIP_COOKIE"]; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>* The 10% Campaign *</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<?php 

	// Load Style Sheet depending on whether we're on the home page or a secondary page
	$linkstyles = loadStyles(); echo $linkstyles; 

	?>
	<link rel="shortcut icon" href="img/favicon.ico" />
    <link rel="stylesheet" href="css/fonts/stylesheet.css" type="text/css" charset="utf-8" />
    <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.custom.css" rel="stylesheet" />	
    <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.custom.min.js"></script>
	<script type="text/javascript" src="js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="js/additional-methods.js"></script>
	<script type="text/javascript">
	jQuery.validator.setDefaults({
		debug: true,
		success: "valid"
	});;
	</script>

    <script type="text/javascript">
		$(document).ready(function(){



			// Sign Up Button on Home page
			$('a.signupbutton').click(function() {
				$('#signuptabs').fadeIn('slow');
			});

			// Sign Up Button on Home page - header
			$('area.join').click(function() {
				$('#signuptabs').fadeIn('slow');
			});

			// Close Button on Home page
			$('span.joinlink').click(function() {
				$('#signuptabs').fadeIn('slow');
			});

			// Close Button on Home page
			$('#signuptabs span.close').click(function() {
				$('#signuptabs').fadeOut('slow');
			});


			// Tabs
			$('#tabs').tabs();
			$('#signuptabs').tabs();

			$("#newspaper-c tr td:even").addClass("even");

		});

    </script>
		<style type="text/css">
			/*demo page css*/

			.demoHeaders { margin-top: 0em; }
			#dialog_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative;}
			#dialog_link span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}
			ul#icons {margin: 0; padding: 0;}
			ul#icons li {margin: 2px; position: relative; padding: 4px 0; cursor: pointer; float: left;  list-style: none;}
			ul#icons span.ui-icon {float: left; margin: 0 4px;}
		</style>	

	<!-- IE Bugs -->

    <!--[if LTE IE 6]>
        <style type="text/css">
        div#wrapper { behavior: url(js/iepngfix.htc) }
        </style> 
        <script type="text/javascript" src="js/iepngfix_tilebg.js"></script>  
    <![endif]-->

<script type="text/javascript">
function recordOutboundLink(link, category, action) {
  try {
    var myTracker=_gat._getTrackerByName();
    _gaq.push(['myTracker._trackEvent', category ,  action ]);
    setTimeout('document.location = "' + link.href + '"', 100)
  }catch(err){}
}
</script>

</head>

<body>
<?php include_once("analyticstracking.php"); ?>
	<div id="wrapper">

		<div id="header">
			<h1>The 10% Campaign | Building North Carolina's Local Food Economy | Farm to Fork</h1>
			<!-- Here goes the logo ------------------------------------------------------------------------------>
		  <div id="logo">
			<?php
				//$logos = array('header_logo1.jpg','header_logo2.jpg','header_logo3.jpg','header_logo4.jpg','header_logo5.jpg');
				//echo '<img src="img/logos/'.$logos[rand(0,count($logos)-1)].'" width="229" height="220" alt="" />';
				$logos = array('header_logo5.jpg');				
				$curr_logo = $logos[rand(0,count($logos)-1)];
			?>
			<img src="img/logos/<?php echo $curr_logo; ?>" alt="" width="960" height="249" usemap="#Map" />
			<map name="Map" id="Map">
		      <area shape="rect" coords="559,6,886,37" href="#" alt="Join the 10% Campaign" class="join" />
			</map>
		  </div>

		
		<!-- signuptabs -->
            <div id="signuptabs">
                <ul>
                  <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#signuptabs-1">Person</a></li>
                  <li class="ui-state-default ui-corner-top"><a href="#signuptabs-2">Business/Organization</a></li>
                </ul>
                <div id="signuptabs-1">
                  <!-- PERSON -->
                        <form id="signupnowheader-person" class="ui-corner-top ui-corner-bottom" action="signup.php" method="post">
                            <fieldset><span class="signuptext">If you are purchasing food for yourself or your family, enter your information here to sign up:</span><br />
                                <label for="name">Your&nbsp;Name: </label>
                                <input id="name" type="text" name="name" size="30" maxlength="50" /><br />
                                <label for="email">Email: </label>
                                <input id="email" type="text" name="email" size="30" maxlength="50" /><br />
                                <label for="zip">Zip: </label>
                                <input id="zip" type="text" name="zip" size="10" maxlength="5" /><br />
                                <input type="submit" name="submit" id="submit" value="Submit" />
                                <br style="clear:both;" />
                            </fieldset>
                        </form>
						<hr />
                </div>
                <!-- end #signuptabstabs-1 -->
                <div id="signuptabs-2">
                  <!-- ORGANIZATION -->
                        <form id="signupnowheader-org" class="ui-corner-top ui-corner-bottom" action="signupb.php" method="post">
                            <fieldset><span class="signuptext">If you are a restaurant, organization or business, enter your information here to sign up:</span><br />
                            <label class="bname" for="bname">Name: </label>
                            <input class="bname" type="text" name="bname" size="30" maxlength="50" /><br />
                            <label for="btype">Type: </label>
							<?php __create_select_options(__get_list_type("BIZORG"), "btype", true); ?>
            				<br />
                            <label for="name">Your&nbsp;Name: </label>
                            <input id="name" type="text" name="name" size="30" maxlength="50" /><br />
                            <label for="bphone">Phone: </label>
                            <input id="bphone" type="text" name="bphone" size="30" maxlength="50" /><br />
                            <label for="email">Email: </label>
                            <input id="email" type="text" name="email" size="30" maxlength="50" /><br />
                            <label for="zip">Zip: </label>
                            <input id="zip" type="text" name="zip" size="10" maxlength="5" /><br />
                            <label for="url">URL: </label>
                            <input id="url" type="text" name="url" size="60" maxlength="60" /><br />
                            <input type="submit" name="submit" id="submit" value="Submit" />
                            <br style="clear:both;" />
                            </fieldset>
                            
                        </form>
						<hr />
                </div><!-- end #signuptabstabs-2 -->
				<div class="forminfo">
					<p><span class="close">[X] <u>Close this form</u></span></p>
				</div>
            </div><!-- end #signuptabs -->

           

		</div><!--end #header -->

		<div id="main">
			<div id="navcontainer">
              <ul id="nav">
                    <?php $curr = getCurrent(); ?>
                    <li <?php echo (!strcmp($curr,"index.php"))      ? "class='curr'" : ""; ?>><a href="index.php">Home</a></li>
                    <li <?php echo (!strcmp($curr,"partners.php"))   ? "class='curr'" : ""; ?>><a href="partners.php">Our Partners</a></li>

                    <li <?php echo (!strcmp($curr,"learnmore.php"))     ? "class='curr'" : ""; ?>><a href="learnmore.php">Learn More</a></li>
                    <li <?php echo (!strcmp($curr,"events.php"))   ? "class='curr'" : ""; ?>><a href="events.php">Events</a></li>
              </ul><!-- end #nav -->
			</div>