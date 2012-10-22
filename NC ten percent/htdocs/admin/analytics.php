<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="../img/favicon.ico" />
	<title>The 10% Campaign Admin: Business Editor</title>
	<style type="text/css" media="screen">
		body { font: 100% "Trebuchet MS", sans-serif;line-height: 1.2em; }
		a:link {color:#052d5d;}
		a:visited { color:#11335b;}
		a:hover {color:#0156bc;}
		.blue { color: #5D7EA3; }
		.gray { color: gray; }
	</style>
    <link type="text/css" href="../css/ui-lightness/jquery-ui-1.8.custom.css" rel="stylesheet" />	
    <script type="text/javascript" src="../js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../js/jquery-ui-1.8.custom.min.js"></script>
	<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="../js/additional-methods.js"></script>
	<script type="text/javascript">
	jQuery.validator.setDefaults({
		debug: true,
		success: "valid"
	});;
	</script>
</head>
<?php
	require_once('../defines.php');
	require_once('../db.php');
	require_once('../ncce_connect.php');
	require_once('../utilities.php');
	__get_table_count('members'); // We need to do this so mysql_real_escape_string works properly
	if (!__is_lfc() && !__is_admin_user()) { echo 'Access denied'; exit; }
?>
<body bgcolor="#F8F8F8">

<?php

require_once 'google-api-php-client-0.5.0/google-api-php-client/src/apiClient.php';
require_once 'google-api-php-client-0.5.0/google-api-php-client/src/contrib/apiAnalyticsService.php';

session_start();

$client = new apiClient();
$client->setApplicationName('Hello Analytics API Sample');

$client->setClientId('799675510967-o9m4nglmob1o7qh0oddh8vrq64smlmmr.apps.googleusercontent.com');
$client->setClientSecret('EFnTqJ12zVgpyTYrcZT_NNRx');
$client->setRedirectUri('http://www.ncsu.edu/project/nc10percent/admin/analytics.php');
$client->setDeveloperKey('AIzaSyC_Qqq4qRkbITQltiaUOujQ-OXS0DDbh20');
$client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));

$client->setUseObjects(true);

if (isset($_GET['code'])) {
  $client->authenticate();
  $_SESSION['token'] = $client->getAccessToken();
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}

if (!$client->getAccessToken()) {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Connect Me!</a>";

} else {

$analytics = new apiAnalyticsService($client);
runMainDemo($analytics);

}


function runMainDemo(&$analytics) {
  try {
    print '<h3>General site statistics</h3>';
    $profileId = getFirstProfileId($analytics);
    $results1 = getVisits($analytics, $profileId);
    printResults($results1);
    print '<h3>Generate report</h3>';
    print '<form action="analytics.php" method="post" accept-charset="utf-8">';
    print '<input type="hidden" name="mode" value="save" id="mode">';
    print '<table style="border: solid 1px gray; background-color: #eee; font-size: smaller;" border="0" cellspacing="3" cellpadding="3"><tr><td>Enter the date range for report   &nbsp &nbsp FROM:(choose a date after May 25th,2012)</td> <td>';
    __create_select_options(__get_list_type("YEAR"), "fromyear", false, $fromyear);
    __create_select_options(__get_list_type("MONTH"), "frommonth", false, $frommonth);
    __create_select_options(__get_list_type("DATE"), "fromdate", false, $fromdate);
    print '</td><td>&nbsp &nbsp &nbsp TO:';
    __create_select_options(__get_list_type("YEAR"), "toyear", false, $toyear);
    __create_select_options(__get_list_type("MONTH"), "tomonth", false, $tomonth);
    __create_select_options(__get_list_type("DATE"), "todate", false, $todate);
    print '</td></tr><tr><td><b>DIMENSIONS(choose maximum of 7)<b></td><td><b>Metrics<b></td></tr><tr><td>';
    __create_checkbox_options(__get_list_type("DIMENSION"), "dimension", $dimension);
    print '</td><td>';
    __create_checkbox_options(__get_list_type("METRICS"), "metric", $metric);
    print '</td></tr><tr><td><input type="submit" value="Get report"></td></tr></table>';
    
    // Step 2. Get the user's first profile ID.
    

    if (isset($profileId)) {

      // Step 3. Query the Core Reporting API.
        if ($_POST['mode']=='save')
        {
        $startday=$_POST['fromdate'];
        $startmonth=$_POST['frommonth'];
        $startyear=$_POST['fromyear'];
        $endday=$_POST['todate'];
        $endmonth=$_POST['tomonth'];
        $endyear=$_POST['toyear'];
        $startdate=''.$startyear.'-'.$startmonth.'-'.$startday;
        $enddate=''.$endyear.'-'.$endmonth.'-'.$endday;
        $metric=$_POST['metric'];
        $c=count($metric);
        for ($i=0;$i<$c;$i++)
        {   
            if ($i==($c-1))
            $b.=$metric[$i];
            else
            $b.=$metric[$i].",";
        }
        //print $b;
        $dim=$_POST['dimension'];
        $d=count($dim);
        for ($i=0;$i<$d;$i++)
        {   
            if ($i==($d-1))
            $e.=$dim[$i];
            else
            $e.=$dim[$i].",";
        }
        //print $e;
        $results = getResults($analytics, $profileId,$b , $e,$startdate,$enddate);
        }
      
      // Step 4. Output the results.
      
      printDataTable($results);
    }

  } catch (apiServiceException $e) {
    // Error from the API.
    print 'There was an API error : ' . $e->getCode() . ' : ' . $e->getMessage();

  } catch (Exception $e) {
    print 'There wan a general error : ' . $e->getMessage();
  }
}

function getFirstprofileId(&$analytics) {
  $accounts = $analytics->management_accounts->listManagementAccounts();

  if (count($accounts->getItems()) > 0) {
    $items = $accounts->getItems();
    $firstAccountId = $items[0]->getId();

    $webproperties = $analytics->management_webproperties
        ->listManagementWebproperties($firstAccountId);

    if (count($webproperties->getItems()) > 0) {
      $items = $webproperties->getItems();
      $firstWebpropertyId = $items[0]->getId();

      $profiles = $analytics->management_profiles
          ->listManagementProfiles($firstAccountId, $firstWebpropertyId);

      if (count($profiles->getItems()) > 0) {
        $items = $profiles->getItems();
        return $items[0]->getId();

      } else {
        throw new Exception('No profiles found for this user.');
      }
    } else {
      throw new Exception('No webproperties found for this user.');
    }
  } else {
    throw new Exception('No accounts found for this user.');
  }
}

function getResults(&$analytics, $profileId,$arr,$darr,$startd,$endd) {
    $optParams = array(
      'dimensions' => ''.$darr,
      'max-results' => '25');
   return $analytics->data_ga->get(
       'ga:' . $profileId,
       ''.$startd,
       ''.$endd,
       ''.$arr,
       $optParams);
}
function getVisits(&$analytics, $profileId) {
   $dat=date("Y-m-d");
   return $analytics->data_ga->get(
       'ga:' . $profileId,
       '2012-05-25',
       ''.$dat,
       'ga:visitors,ga:newVisits,ga:percentNewVisits,ga:visitsWithEvent');
}


function printResults(&$results) {
  if (count($results->getRows()) > 0) {
    //$profileName = $results->getProfileInfo()->getProfileName();
    $rows = $results->getRows();
    $visits = $rows[0][0];
    $newVisits=$rows[0][1];
    $percent=round($rows[0][2]);
    $lowes=$rows[0][3];

    //print "<p>First profile found: $profileName</p>";
    print "<p>Total visits: $visits</p>";
    print "<p>Total new visits : $newVisits </p>";
    print "<p>Percentage of new visits : $percent </p>";
    print "<p>Number of hits for Lowes foods link: $lowes </p>";
  } else {
    print '<p>No results found.</p>';
  }
}

function printDataTable(&$results) {
  if (count($results->getRows()) > 0) {
    $table .= '<table>';

    // Print headers.
    $table .= '<tr>';

    foreach ($results->getColumnHeaders() as $header) {
      $table .= '<th>' . $header->name . '</th>';
    }
    $table .= '</tr>';

    // Print table rows.
    foreach ($results->getRows() as $row) {
      $table .= '<tr>';
        foreach ($row as $cell) {
          $table .= '<td>'
                 . htmlspecialchars($cell, ENT_NOQUOTES)
                 . '</td>';
        }
      $table .= '</tr>';
    }
    $table .= '</table>';

  } else {
    $table .= '<p>No Results Found.</p>';
  }
  print $table;
}



?>