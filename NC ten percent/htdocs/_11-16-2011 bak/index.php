<?php include('header.php'); ?>
		<div id="seasonality-how-it-works">
	  	  <div id="seasonality">
                <h2>In Season</h2>
				<?php
					$month = date('n');
					$result = __do_sql("select * from food_calendar where FIND_IN_SET('$month', months) and image <> '' and storage = 'N' order by rand() limit 1");
					if ($result && mysql_num_rows($result) == 1) {
						$name = mysql_result($result, 0, 'name');
						$image = mysql_result($result, 0, 'image');
						$image = substr($image, 1, strlen($image)-1); // remove preceding /
						echo '<img src="'.$image.'" width="224" height="168" alt="'.$name.'" style="border:1px #ccc solid;" />';
					} else {
						// Peanuts are always in season so we default to them in the event of a database issue
						echo '<img src="img/in-season/peanuts.png" width="224" height="168" alt="peanuts" style="border:1px #ccc solid;" />';
					}
				?>
            <span class="charts">
                <p><a href="seasonality.php">View Seasonality Chart</a></p>
            </span>
	  	</div><!-- end #seasonality -->

			<div id="about">
			  	<h2>Here’s How It Works</h2>
				<p>Join us in support of North Carolina's farmers, businesses and communities.</p>
				<ul>
                    <li>Pledge to spend 10 percent of your existing food dollars locally</li>
                    <li>We’ll email you with a few simple questions each week</li>
                    <li>We’ll track your progress, and you’ll see our progress statewide</li>
                </ul>
				<!-- <p><a href="about.php">Learn more</a></p> -->


				<a class="signupbutton"><img src="img/signupnow.png" width="298" height="53" alt="Sign Up Now" /></a>

		  </div><!-- end #upcoming-events -->
		</div><!-- end #seasonality-how-it-works -->

  	  	  	<div id="charts">
                <h2>Watch Us Grow</h2><br /><br />
				<p><span style="font-size: xx-large; font-weight: bold; color: white;"><?php echo __get_member_count_by_kind('consumer');?></span>&nbsp;&nbsp;&nbsp;<span style="font-size: x-large; color: #ddd;">people</span></p>
				<br />
				<p><span style="font-size: x-large; color: #ddd;">and</span>&nbsp;&nbsp;&nbsp;<span style="font-size: xx-large; font-weight: bold; color: white;"><?php echo __get_member_count_by_kind('business');?></span>&nbsp;&nbsp;&nbsp;<span style="font-size: x-large; color: #ddd;">businesses</span></p>
				<br />
				<p><span style="font-size: x-large; color: #ddd;">spent</span>&nbsp;&nbsp;&nbsp;<span style="font-size: xx-large; font-weight: bold; color: white;"><?php echo __get_total_dollar_amount();?></span>&nbsp;&nbsp;&nbsp;<span style="font-size: x-large; color: #ddd;">locally</span><br /><br /><span style="font-size:small; color: #ddd; font-style:italic;">Since July 2010.</span></p>
				<br />
				<p style="font-size: 14px; color: white;">Help us build North Carolina's local food economy by joining the campaign and encouraging your family, friends and neighbors to do the same.</p>
			</div><!-- end #charts -->

			<div id="belowfold">

                
				

				<!-- Tabs -->
                <div id="tabs">

					<div id="left" style="width:430px; float:left;">
					<h3>News &amp; Happenings</h3>
                    <ul>
						<?php if ($_GET['county']) { ?>
	                        <li class="ui-state-default ui-corner-top"><a href="#tabs-1">Statewide</a></li>
	                        <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-2"><?php echo $_GET['county'];?> County</a></li>
						<?php } else { ?>
	                        <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-1">Statewide</a></li>
	                        <li class="ui-state-default ui-corner-top"><a href="#tabs-2"><?php echo  $zip_cookie?ucwords(strtolower(__get_zipcode_county($zip_cookie))):'My'; ?> County</a></li>
						<?php } ?>
                    </ul>
                    <div id="tabs-1"> <!-- STATE -->
<?php
//echo CEFS_RSS_FEED;
						$curl_handle = curl_init();
						$options = array(CURLOPT_URL=>CEFS_RSS_FEED, CURLOPT_HEADER=>false, CURLOPT_RETURNTRANSFER=>true, CURLOPT_FOLLOWLOCATION=>true, CURLOPT_USERAGENT=>"PHP");
						curl_setopt_array($curl_handle, $options);
						$server_output = curl_exec($curl_handle);
						curl_close($curl_handle);
						$server_output = trim($server_output);
						//print_r($server_output);

						$doc = new DOMDocument();
						$doc->loadXML($server_output);
						$arrFeeds = array();
						foreach ($doc->getElementsByTagName('item') as $node) {
							$itemRSS = array ( 
								'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
								'description' => $node->getElementsByTagName('description')->item(0)->nodeValue,
								'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
								'pubDate' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
								'category' => $node->getElementsByTagName('category')->item(0)->nodeValue
								);
							//print_r($itemRSS);
							array_push($arrFeeds, $itemRSS);
						}
						//print_r($arrFeeds);
						unset($foundRSS);
						if (count($arrFeeds)) {
							$max = 3; // how many to show maximum
							$foundRSSArray = array();
							
							foreach ($arrFeeds as $k => $itemRSS) {
								if ($itemRSS['category'] == "10 percent") { // try to  find a 10 percent specific item
									$foundRSS = $itemRSS;
									$foundRSSArray[] = $foundRSS;
									if (count($foundRSSArray) >= $max)
										break;
								}
							}
							if (count($foundRSSArray)==0)
								$foundRSSArray[] = $arrFeeds[0]; // use most recent single entry if not a 10 percent specific item to be had
						
							echo '<br style="clear:both;" />';
		                    echo '<p><span style="font-weight:bold;">Just One Year in CEFS Campaign Tracks $5.7 Million in Local Food Purchases</span><br />';
							echo 'July 20, 2011 Press Release';
							echo '<br /><a href="http://www.ncsu.edu/project/nc10percent/img/10_percent_year1.pdf">Read Complete Article</a>';
							echo '</p>';
						
						
							foreach ($foundRSSArray as $k => $foundRSS) {
								echo '<br style="clear:both;" />';
		                        echo '<p><span style="font-weight:bold;">'.$foundRSS['title'].'</span><br />';
								echo str_replace('Read more', '<a href="'.$foundRSS['link'].'">Read more</a>', $foundRSS['description']);
								if ($foundRSS['link'])
									echo '<br /><a href="'.$foundRSS['link'].'">Read Complete Article</a>';
								echo '</p>';
							}
						} else {
							echo '<br style="clear:both;" />';
	                        echo '<p style="font-weight:bold;">Latest News</p>';
							echo '<p>Please visit the CEFS web site for the latest news on local food. <a href="http://www.cefs.ncsu.edu/recentnews.html">Read more</a></p>';
						}
?>

<?php
// Links
$result = __ncce_request('get_state_links', '', 'phoenix');
if (count($result) > 0) {
	echo '<hr style="border: 1px solid #ccc;" /><br />';
	echo '<p style="font-weight:bold;">Quick Links</p>';
	echo '<ul>';
	$previous_group = "";
	foreach ($result as $k => $r) {
		$group = htmlentities(stripslashes($r['data']));
		if ($group) break;
		$url = htmlentities(stripslashes($r['text']));
		$name = htmlentities(stripslashes($r['description']));
		if ($group && $group != $previous_group) {
			$previous_group = $group;
			echo '<br /><span style="font-weight:bold;">'.$group.'</span><br /><br />';
		}
		echo '<li><a href="'.$url.'">'.$name.'</a></li>';
	}
	echo '</ul>';
}

?>

					</div><!-- end #tabs-1 -->

                    <div id="tabs-2"> <!-- COUNTY -->

<?php
						unset($arrFeeds);
						$county_rss_url = '';
						$county_name = '';
						if ($zip_cookie)
							$county_name = __get_zipcode_county($zip_cookie);
						if ($_GET['county'])
							$county_name = $_GET['county'];
						if ($county_name)
							$county_rss_url = 'http://www.ces.ncsu.edu/site/rss.php?site='.strtolower($county_name).'&limit=10';
						
						// Popup menu
						echo '<br style="clear:both;" />';
						echo __create_county_popup('county_list', $county_name, 'onchange="location.href=\'index.php?county=\'+$(\'#county_list\').val();"').'<br />';
						
						// Contacts
						$county_id = __get_county_id($county_name);
						if ($county_id) {
							$contacts = __get_county_contact_emp_ids($county_id);
							if ($contacts) {
								echo '<p>The '.ucwords(strtolower($county_name)).' County Local Foods contact'.(count($contacts)==1?' is ':'s are ');
								foreach ($contacts as $k => $v) {
									if ($k > 0) echo ', ';
									echo '<a href="mailto:'.__get_emp_id_email($v).'?subject=Local Foods">'.__get_emp_id_name($v).'</a>';
								}
								echo ' from the <a href="http://'.str_replace(' ', '', strtolower($county_name)).'.ces.ncsu.edu/index.php?page=localfoods">NC Cooperative Extension Service</a></p>';
							}
						}

						if ($county_rss_url) {
	 						$curl_handle = curl_init();
							$options = array(CURLOPT_URL=>$county_rss_url, CURLOPT_HEADER=>false, CURLOPT_RETURNTRANSFER=>true, CURLOPT_FOLLOWLOCATION=>true, CURLOPT_USERAGENT=>"PHP");
							curl_setopt_array($curl_handle, $options);
							$server_output = curl_exec($curl_handle);
							curl_close($curl_handle);
							$server_output = trim($server_output);

							$doc = new DOMDocument();
							$doc->loadXML($server_output);
							$arrFeeds = array();
							foreach ($doc->getElementsByTagName('item') as $node) {
								$itemRSS = array ( 
									'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
									'description' => $node->getElementsByTagName('description')->item(0)->nodeValue,
									'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
									'pubDate' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
									'category' => $node->getElementsByTagName('category')->item(0)->nodeValue
									);
								array_push($arrFeeds, $itemRSS);
							}
						}
						unset($foundRSS);
						if (count($arrFeeds) && $county_rss_url) {
							foreach ($arrFeeds as $k => $itemRSS) {
								if ($itemRSS['category'] == "Local Foods") { // try to find a local food specific item
									$foundRSS = $itemRSS;
									break;
								}
							}
						
						}
						echo '<hr style="border: 1px solid #ccc;" /><br />';
						if (isset($foundRSS)) {
	                        echo '<h4>'.$foundRSS['title'].'</h4>';
							echo '<p>'.$foundRSS['description'].' <a href="'.$foundRSS['link'].'">Read more</a></p><br />';
						} else {
	                        echo '<p style="font-weight:bold;">Latest News</p>';
							echo '<p>'.ucwords(strtolower($county_name)).' County does not have any county-specific information listed. Please visit the CEFS web site for the latest news on local food. <a href="http://www.cefs.ncsu.edu/recentnews.html">Read more</a></p>';
						}
?>

<?php
// Links
$county_id = __get_county_id($county_name);
$result = __ncce_request('get_county_links', $county_id, 'phoenix');
if (count($result) == 0) // no county links - get the default state links
	$result = __ncce_request('get_state_links', '', 'phoenix');
if (count($result) > 0) {
	echo '<hr style="border: 1px solid #ccc;" /><br />';
	echo '<p style="font-weight:bold;">Quick Links</p>';
	echo '<ul>';
	$previous_group = "";
	foreach ($result as $k => $r) {
		$group = htmlentities(stripslashes($r['data']));
		$url = htmlentities(stripslashes($r['text']));
		$name = htmlentities(stripslashes($r['description']));
		if ($group && $group != $previous_group) {
			$previous_group = $group;
			echo '<br /><span style="font-weight:bold;">'.$group.'</span><br /><br />';
		}
		echo '<li><a href="'.$url.'">'.$name.'</a></li>';
	}
	echo '</ul>';
}

?>

					</div><!-- end #tabs-2 -->

					</div><!-- end left -->

				<div id="socialmedia">
					<h3>Social Media</h3>
                    
					<iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fnc10percent&amp;width=426&amp;colorscheme=light&amp;show_faces=false&amp;stream=true&amp;header=true&amp;height=427" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:426px; height:427px; margin:0 0 20px 0; background-color:#FFF;" allowTransparency="false"></iframe>

					

					<script src="http://widgets.twimg.com/j/2/widget.js"></script>
                    <script>
                    new TWTR.Widget({
                      version: 2,
                      type: 'profile',
                      rpp: 3,
                      interval: 6000,
                      width: 428,
                      height: 300,
                      theme: {
                        shell: {
                          background: '#02244c',
                          color: '#ffffff'
                        },
                        tweets: {
                          background: '#053164',
                          color: '#ffffff',
                          links: '#4aed05'
                        }
                      },
                      features: {
                        scrollbar: false,
                        loop: false,
                        live: false,
                        hashtags: true,
                        timestamp: true,
                        avatars: false,
                        behavior: 'all'
                      }
                    }).render().setUser('<?php echo TWITTER_USER_NAME;?>').start();
                    </script>
					
				</div><!--end #othersection-->

				<br style="clear:both;" />
                </div><!-- end #tabs -->			
				
				



<?php include('footer.php'); ?>