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
   $col		= $_GET['color'];
	$zipCode = $_POST['zipCode'];
	$radius 	= $_POST['radius'];
	$category= $_POST['category'];
	$county 	= $_POST['county'];
?>
<div id="belowfold">
<div id="contentfullwidth">
<div id="leftcolumn">
<h1>Look for All Our Partners Statewide</h1>



</div>
<div id="rightcolumn">
<img src="img/lfl_map.png" alt="look for local" usemap="#Map2" style="padding-top:20px;padding-bottom:20px; width="340" height="170" />
<map name="Map2" id="Map2">

</map>
</div>
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />

<?php
	if (!empty($category)) {
		if ($category == 'eatingout') {
			$parmCat = 'Eating Out';
		} else if ($category == 'homebusiness' ) {
			$parmCat = 'Bring It Home...';
		} else if ($category == 'partners' ) {
			$parmCat = 'Farm Partners';
		} else if ($category == 'commPartners' ) {
			$parmCat = 'Community Partners';
		} else if ($category == 'statePartners' ) {
			$parmCat = 'Statewide Partners';
		} 
	}

	echo "<p class=\"subhead\">Searching using the following parameters: ";
		if (!empty($zipCode)) { echo "Zip Code: $zipCode ; "; }
		if (!empty($radius)) {echo "Radius: $radius ; "; }
		if (!empty($category)) {echo "Category: $parmCat ; "; }
		if (!empty($county)) {echo "County: $county"; }
	echo "<br></p>";
	
	if(!empty($radius) && empty($zipCode)) {
		echo "<h3>Please enter in zip code!</h3>";
	}
?>

<!--<form method="post" action="<?php echo $PHP_SELF;?>">-->
<form method="post" action="<?php echo $PHP_SELF;?>">
<p class ="subhead">
Zip Code:<input name="zipCode" type="number" size="12" maxlength="5" >
Radius:<input name="radius" type="number" size="12" maxlength="5" > miles
<br />
Category:
	<select name="category">
	<option value=""></option>
	<option value="eatingout">Eating Out</option>
	<option value="homebusiness">Bring It Home...Or To Your Business</option>
	<option value="partners">Our Farm Partners</option>
	<option value="commPartners">Community Partners</option>
	<option value="statePartners">Statewide Partners</option>

<input type="submit" value="Search">
</form>
<p class="caption" id="jumpMap"/><em><u>Jump to Map > </u></em></p>
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

		var c = "<?php echo $category ?>";
      var z = "<?php echo $zipCode ?>";
      var r = "<?php echo $radius ?>";
		var cty = "<?php echo $county ?>";
		cty.toLowerCase();

      downloadUrl("genXMLPartnersDetail.php?c=" + c + "&z=" + z + "&r=" + r + "&cty=" + cty, function(data) {
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

<body onload="load()">
	<center>	<div id="map" style="width: 700px; height: 420px"></div> </center>
</body></br> 

<p class="subhead">
Extra&nbsp;&nbsp;<img src="img/search.png" />
</p>
<p>
Still can't find what you're looking for? <a href="databases.php">Search online databases</a> for NC local foods.<br />
<em>(Please note: these are not maintained by the 10% Campaign.)</em>
</p>


<br /><br /><br />
</div><!-- end #contentright -->

<?php include('footer.php'); ?>
