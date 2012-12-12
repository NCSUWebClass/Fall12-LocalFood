<?php include('header.php'); ?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script src="./js/jquery-ui-1.8.7.custom.min.js"></script>
<script src="./js/jquery.slideto.min.js" type="text/javascript"></script>
<script>
	$(document).ready(function(){
		$("#jumpMap").click(function(){
			$("#map").slideto();
		});
	});
</script>

<?php
	$col		=$_GET['color'];
	$type		=$_GET['type'];
	$zipCode = $_POST['zipCode'];
	$radius 	= $_POST['radius'];
?>
<div id="belowfold">
<div id="contentfullwidth">
	<div id="leftcolumn">
    <h1><?php echo "Our ".$type." partners "; ?></h1>
	<a href="partners.php"><i>< back to map</i></a>

    </div>
    <div id="rightcolumn">
    <?php echo '<img src="img/'.$col.'.png" />'; ?>
	
    </div>
    <br /><br /><br /><br /><br /><br />

<?php
	echo "<p class=\"subhead\"></br></br>Searching using the following parameters: </p>";
		if (!empty($zipCode)) { echo "<p class=\"subheadZip Code: $zipCode ; "; }
		if (!empty($radius)) {echo "Radius: $radius "; }
	echo "</br></p>";
 
	if(!empty($radius) && empty($zipCode)) {
		echo "<h3>Please enter in zip code!</h3>";
	}
?>

<form method="post" action="<?php echo $PHP_SELF;?>">
<p class ="subhead">
Zip Code:<input name="zipCode" type="number" size="12" maxlength="5" >
Radius:<input name="radius" type="number" size="12" maxlength="5" > miles
<input type="submit" value="Search">
</form>
	<p class="caption" id="jumpMap"/><em><u>Jump to Map > </u></em></p>
<br /><br />

</p>

	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
	<script lanaguage="javascript" type="text/javascript">

	var customIcons = {
      restaurant: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      } ,
      frmmkt: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      } ,
	  community: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_yellow.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      } ,
	  grocer: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_green.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      } ,
	  farm: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_orange.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      }
    };

    function load() {
      var map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(35.5, -80),
        zoom: 6,
        mapTypeId: 'roadmap'
      });
      var infoWindow = new google.maps.InfoWindow;

      // Change this depending on the name of your PHP file

      var zipCd = "<?php echo $zipCode ?>";
      var rad = "<?php echo $radius ?>";
      var t = "<?php echo $type ?>";
      downloadUrl("genXMLPartnersDetail1.php?t=" + t + "&zip=" + zipCd + "&rad=" + rad, function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
          var name = markers[i].getAttribute("name");
          var address = markers[i].getAttribute("address");
          var type = markers[i].getAttribute("type");
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
          var html = "<b>" + name + "</b> </br>" + address;
          var icon = customIcons[type] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon,
            shadow: icon.shadow
          });
          bindInfoWindow(marker, map, infoWindow, html);
        }
      }); 

		var legendDiv = document.createElement('DIV');
		var legend = new Legend(legendDiv, map);
		legendDiv.index = 1;
		map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legendDiv);

    }

	function Legend(controlDiv, map) {
	  // Set CSS styles for the DIV containing the control
	  // Setting padding to 5 px will offset the control
	  // from the edge of the map
	  controlDiv.style.padding = '5px';

	  // Set CSS for the control border
	  var controlUI = document.createElement('DIV');
	  controlUI.style.backgroundColor = 'white';
	  controlUI.style.borderStyle = 'solid';
	  controlUI.style.borderWidth = '1px';
	  controlUI.title = 'Legend';
	  controlDiv.appendChild(controlUI);

	  // Set CSS for the control text
	  var controlText = document.createElement('DIV');
	  controlText.style.fontFamily = 'Arial,sans-serif';
	  controlText.style.fontSize = '12px';
	  controlText.style.paddingLeft = '4px';
	  controlText.style.paddingRight = '4px';
	  
	  // Add the text
	  controlText.innerHTML = '<small><b>Legend</b></small><br />' +
		     '<img src="http://labs.google.com/ridefinder/images/mm_20_red.png" /> Restaurant<br />' +
		     '<img src="http://labs.google.com/ridefinder/images/mm_20_yellow.png" /> Community<br />' +
		     '<img src="http://labs.google.com/ridefinder/images/mm_20_green.png" /> Grocer<br />' +
		     '<img src="http://labs.google.com/ridefinder/images/mm_20_blue.png" /> Farmers Market<br />' +
		     '<img src="http://labs.google.com/ridefinder/images/mm_20_orange.png" /> Farm<br />' 
		     
	  controlUI.appendChild(controlText);
	}

    function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function doNothing() {}

    //]]>


</script>





<p class="subhead">
  <?php
if ($type=='restaurant')
{
echo 'Eating out';
}
if ($type=='farm')
{
echo 'Our farm partners';
}
if ($type=='business')
{
echo 'Bring it home or to your business';
}
if ($type=='community')
{
echo '<br><br>';
//echo 'Our community partners';
}
?>
</p>
<p class="caption">
<?php
if ($type=='restaurant')
{
echo 'The chefs at our partner restaurants are committed to sourcing 10 Percent or more seasonal, local ingredients from NC Farms.';
}
if ($type=='community')
{
//echo 'Our community partners';
}
if ($type=='business')
{
echo 'Find Grocers, Farmers Markets, CSAs, CSFs, and more right in your neighborhood.';
}
if ($type=='farm')
{
echo 'Our farm partners are providing food to North Carolinians in a variety of ways. Check out their websites for details on where you will find their farms and their products.';
}
?>
</p>

<br/><br/>
    <p>
    
    <table id="partners">
    <?php
         if ($type=='restaurant')
         {
				if (empty($zipCode) && empty($radius) ) {
					$res= __get_restaurants_in_region($col);
				} else if(!empty($radius) && !empty($zipCode) && !empty($county)) {
					$res=__get_restaurants_in_regionByZipAndRadiusAndCounty($col, $zipCode, $radius, $county);
				} elseif (!empty($radius) && !empty($zipCode)) {
					$res=__get_restaurants_in_regionByZipAndRadius($col, $zipCode, $radius);
				} elseif (!empty($zipCode)) {
					$_SESSION['zip'] = $zipCode;
					$res= __get_restaurants_in_regionByZip($col, $zipCode);
					if (empty($res) ) {
						$res= __get_restaurants_in_region($col);
					}
					
				}
				
          }
	 else if ($type=='farm')
	{
		
		if (empty($zipCode) && empty($radius) ) {
			$res= __get_farms_in_region($col);
		} else if(!empty($radius) && !empty($zipCode) && !empty($county)) {
			$res=__get_farms_in_regionByZipAndRadiusAndCounty($col, $zipCode, $radius, $county);
		} elseif (!empty($radius) && !empty($zipCode)) {
			$res=__get_farms_in_regionByZipAndRadius($col, $zipCode, $radius);
		} elseif (!empty($zipCode)) {
			$res= __get_farms_in_regionByZip($col, $zipCode);
			if (empty($res) ) {
				$res= __get_farms_in_region($col);
			}
		}
		
	}
	else if ($type=='community')
	{
	
		if (empty($zipCode) && empty($radius) ) {
			$res= __get_community_in_region($col);
		} else if(!empty($radius) && !empty($zipCode) && !empty($county)) {
			$res=__get_community_in_regionByZipAndRadiusAndCounty($col, $zipCode, $radius, $county);
		} elseif (!empty($radius) && !empty($zipCode)) {
			$res=__get_community_in_regionByZipAndRadius($col, $zipCode, $radius);
		} elseif (!empty($zipCode)) {
			$res= __get_community_in_regionByZip($col, $zipCode);
			if (empty($res) ) {
				$res= __get_community_in_region($col);
			}
		}
	
	}

	else if ($type=='business')
	{
	
		if (empty($zipCode) && empty($radius) ) {
			$res=__get_frmmkt_in_region($col);
			$res1=__get_groc_in_region($col);
		} else if(!empty($radius) && !empty($zipCode) && !empty($county)) {
			$res=__get_frmmkt_in_regionByZipAndRadiusAndCounty($col, $zipCode, $radius, $county);
			$res1=__get_groc_in_regionByZipAndRadiusAndCounty($col, $zipCode, $radius, $county);
		} elseif (!empty($radius) && !empty($zipCode)) {
			$res=__get_frmmkt_in_regionByZipAndRadius($col, $zipCode, $radius);
			$res1=__get_groc_in_regionByZipAndRadius($col, $zipCode, $radius);
		} elseif (!empty($zipCode)) {
			$res= __get_frmmkt_in_regionByZip($col, $zipCode);
			$res1=__get_groc_in_regionByZip($col, $zipCode);
			if (empty($res) ) {
				$res=__get_frmmkt_in_region($col);
				$res1=__get_groc_in_region($col);
			}
		}
	
	}

$num= mysql_num_rows($res);
	 //echo 'The number of rows is'.$num;
	$i=4;
	if ($type=='business')
	{
		echo '<p class="subhead"> Farmers Markets </p>';
		}
	 while ($row=mysql_fetch_assoc($res))
	{ 	
	
		if ($i==4)
		{
		echo '<tr>';
		}
	      if ($row['url']=='')
		{
			echo '<td>'.$row['business'].'&nbsp'.'</td>';
			}
			else
			{
		echo '<td>'.'<a href='.$row['url'].'>'.$row['business'].'</a>'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'</td>';
		}
		$i++;
		if ($i % 4==0)
		{
		echo '</tr>';
		echo '<tr>';
		}
		
	}
	if ($type=='business')
	{
		echo '</table>';
	}
	
	if ($type=='business')
	{
		echo '<br /><br /><p class ="subhead"> Retail, Grocers, Co-ops and Suppliers </p>';
		echo '<table id="partners">';
		while ($row=mysql_fetch_assoc($res1))
	{ 	
	
		if ($i==4)
		{
		echo '<tr>';
		}
	      if ($row['url']=='')
		{
			echo '<td>'.$row['business'].'&nbsp'.'</td>';
			}
			else
			{
		echo '<td>'.'<a href='.$row['url'].'>'.$row['business'].'</a>'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'</td>';
		}
		$i++;
		if ($i % 4==0)
		{
		echo '</tr>';
		echo '<tr>';
		}
		
	}
	echo '</table>';
	
		
		}
	if ($type!='business')
	{
		echo '</table>';
		}
	?>
	

    </p>

    <br /><br /><br />
    


    <p class="subhead">
    Extra&nbsp;&nbsp;<img src="img/search.png" />
    </p>
    <p>
    Still can't find what you're looking for? <a href="databases.php">Search online databases</a> for NC local foods.<br />
<em>(Please note: these are not maintained by the 10% Campaign.)</em>
    </p>
    <br /><br />
    
<body onload="load()">
	<center>	<div id="map" style="width: 700px; height: 420px"></div> </center>
</body></br> 
	
<?php
/*if ($type=='restaurant')
{
$str = "<img src=\"img/spotlight.png\" /><br />";
$str .= "<iframe "; 
$str .= "src=\"http://www.facebook.com/plugins/likebox.php?href=www.facebook.com%2Fpages%2FEating-Out%2F123678064393600&amp;width=600";
$str .= "&amp;height=395&amp;colorscheme=light&amp;show_faces=false&amp;border_color&amp;stream=true&amp;header=false\"";
$str .= "scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:600px; height:395px;\" allowTransparency=\"true\"></iframe>";
$str .= "<br />";    
}
if ($type=='farm')
{
$str = "<img src=\"img/spotlight.png\" /><br />";
$str .= "<iframe "; 
$str .= "src=\"http://www.facebook.com/plugins/likebox.php?href=www.facebook.com%2Fpages%2FFarm-Partners%2F284915858211357&amp;width=600";
$str .= "&amp;height=395&amp;colorscheme=light&amp;show_faces=false&amp;border_color&amp;stream=true&amp;header=false\"";
$str .= "scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:600px; height:395px;\" allowTransparency=\"true\"></iframe>";
$str .= "<br />";    
}
if ($type=='business')
{
$str = "<img src=\"img/spotlight.png\" /><br />";
$str .= "<iframe "; 
$str .= "src=\"http://www.facebook.com/plugins/likebox.php?href=www.facebook.com%2Fpages%2FBring-it-Home%2F207332376013537&amp;width=600";
$str .= "&amp;height=395&amp;colorscheme=light&amp;show_faces=false&amp;border_color&amp;stream=true&amp;header=false\"";
$str .= "scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:600px; height:395px;\" allowTransparency=\"true\"></iframe>";
$str .= "<br />";

}
if ($type=='community')
{
$str = "<img src=\"img/spotlight.png\" /><br />";
$str .= "<iframe "; 
$str .= "src=\"http://www.facebook.com/plugins/likebox.php?href=www.facebook.com%2Fpages%2FCommunity-Partners%2F294726307207342&amp;width=600";
$str .= "&amp;height=395&amp;colorscheme=light&amp;show_faces=false&amp;border_color&amp;stream=true&amp;header=false\"";
$str .= "scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:600px; height:395px;\" allowTransparency=\"true\"></iframe>";
$str .= "<br />";    

}

echo $str;
*/?>
    
    
   
    
    
    <br /><br /><br />
</div><!-- end #contentright -->

<?php include('footer.php'); ?>
