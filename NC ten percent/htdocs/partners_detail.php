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
<h1>Look for Our Partners by Region</h1>

<?php
	if ($col=='purple')
    {
        echo '<p><em>Alexander, Alleghany, Ashe, Burke, Caldwell, Catawba, Cleveland, Davie, Gaston, Iredell, Lincoln, Mecklenburg, Rowan, Rutherford, Surry, Wilkes, and Yadkin Counties</em></p><p><a href="partners.php"><i>< back to map</i></a>';
    }
    if ($col=='green')
    {
        echo '<p><em>Avery, Buncombe, Cherokee, Cherokee Reservation, Clay, Graham, Haywood, Henderson, Jackson, Macon, Madison, McDowell, Mitchell, Polk, Swain, Transylvania, Watauga, Yancey</em></p><p><a href="partners.php"><i>< back to map</i></a>';
    }
    if ($col=='red')
    {

        echo '<p><em>Alamance, Caswell, Chatham, Davidson, Durham, Forsyth, Franklin, Granville, Guilford, Orange, Person, Randolph, Rockingham, Stokes, Vance, Wake, Warren</em></p><p><a href="partners.php"><i>< back to map</i></a>';
    }
    if ($col=='yellow')
    {

        echo '<p><em>Beaufort, Bertie, Camden, Chowan, Currituck, Dare, Edgecombe, Gates, Halifax, Hertford, Hyde, Martin, Nash, Northampton, Pasquotank, Perquimans, Pitt, Tyrrell, Washington</em></p><p><a href="partners.php"><i>< back to map</i></a>';
    }
    if ($col=='orange')
    {

        echo '<p><em>Brunswick, Carteret, Craven, Duplin, Greene, Johnston, Jones, Lenoir, New Hanover, Onslow, Pamlico, Pender, Sampson, Wayne, Wilson</em></p><p><a href="partners.php"><i>< back to map</i></a>';
    }
    if ($col=='blue')
    {

        echo '<p><em>Anson, Bladen, Cabarrus, Columbus, Cumberland, Harnett, Hoke, Lee, Montgomery, Moore, Richmond, Robeson, Scotland, Stanly, Union</em></p><p><a href="partners.php"><i>< back to map</i></a>';
    }
	
?>

</div>
<div id="rightcolumn">
<img src="img/lfl_map.png" alt="look for local" usemap="#Map2" style="padding-top:20px;padding-bottom:20px; width="340" height="170" />
<map name="Map2" id="Map2">
<area shape="poly" coords="0,92,118,26,90,90" href="partners_detail.php?color=green" alt="green" />
<area shape="poly" coords="178,70,282,70,279,108,306,148,279,160,238,110,178,110,180,108" href="partners_detail.php?color=blue" alt="blue" />
<area shape="poly" coords="125,0,200,8,203,60,180,100,110,90" href="partners_detail.php?color=purple" alt="purple" />
<area shape="poly" coords="200,0,310,0,285,65,205,65" href="partners_detail.php?color=red" alt="red" />
<area shape="poly" coords="315,3,400,3,420,20,375,35,420,35,400,75,365,80,307,40" href="partners_detail.php?color=yellow" alt="yellow" />
<area shape="poly" coords="285,55,300,48,365,92,388,123,318,140,310,176,290,100" href="partners_detail.php?color=orange" alt="orange" />
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
	</select>
County:
<?php
	if ($col=='purple')
    { 
    	echo '
        <select name="county">
        <option value=""></option>
		<option value="Alexander">Alexander</option>
		<option value="Alleghany">Alleghany</option>
		<option value="Ashe">Ashe</option>
		<option value="Burke">Burke</option>
		<option value="Caldwell">Caldwell</option>
		<option value="Catawba">Catawba</option>
		<option value="Davie">Davie</option>
		<option value="Gaston">Gaston</option>
		<option value="Mecklenburg">Mecklenburg</option>
		<option value="Rowan">Rowan</option>
		<option value="Rutherford">Rutherford</option>
		<option value="Surry">Surry</option>
		<option value="Wilkes">Wilkes</option>
		<option value="Yadkin">Yadkin</option>
		</select>';
    }
   if ($col=='green')
    {
    	echo '
    	<select name="county">
    	<option value=""></option>
        <option value="Avery">Avery</option>
        <option value="Buncombe">Buncombe</option>
        <option value="Cherokee">Cherokee</option>
        <option value="Cherokee Reservation">Cherokee Reservation</option>
        <option value="Clay">Clay</option>
        <option value="Graham">Graham</option>
        <option value="Haywood">Haywood</option>
        <option value="Henderson">Henderson</option>
        <option value="Jackson">Jackson</option>
        <option value="Macon">Macon</option>
        <option value="Madison">Madison</option>
        <option value="McDowell">McDowell</option>
        <option value="Mitchell">Mitchell</option>
        <option value="Polk">Polk</option>
        <option value="Swain">Swain</option>
        <option value="Transylvania">Transylvania</option>
        <option value="Watauga">Watauga</option>
        <option value="Yancey">Yancey</option>
        </select>';
    }
    if ($col=='red')
    {
    	echo '
    	<select name="county">
    	<option value=""></option>
        <option value="Alamance">Alamance</option>
        <option value="Caswell">Caswell</option>
        <option value="Chatham">Chatham</option>
        <option value="Davidson">Davidson</option>
        <option value="Durham">Durham</option>
        <option value="Forsyth">Forsyth</option>
        <option value="Franklin">Franklin</option>
        <option value="Granville">Granville</option>
        <option value="Guilford">Guilford</option>
        <option value="Orange">Orange</option>
        <option value="Person">Person</option>
        <option value="Randolph">Randolph</option>
        <option value="Rockingham">Rockingham</option>
        <option value="Stokes">Stokes</option>
        <option value="Vance">Vance</option>
        <option value="Wake">Wake</option>
        <option value="Warren">Warren</option>
        </select>';
    }
    if ($col=='yellow')
    {
    	echo '
    	<select name="county">
    	<option value=""></option>
        <option value="Beaufort">Beaufort</option>
        <option value="Bertie">Bertie</option>
        <option value="Camden">Camden</option>
        <option value="Chowan">Chowan</option>
        <option value="Currituck">Currituck</option>
        <option value="Dare">Dare</option>
        <option value="Edgecombe">Edgecombe</option>
        <option value="Gates">Gates</option>
        <option value="Halifax">Halifax</option>
        <option value="Hertford">Hertford</option>
        <option value="Hyde">Hyde</option>
        <option value="Martin">Martin</option>
        <option value="Nash">Nash</option>
        <option value="Northampton">Northampton</option>
        <option value="Pasquotank">Pasquotank</option>
        <option value="Perquimans">Perquimans</option>
        <option value="Pitt">Pitt</option>
        <option value="Tyrrell">Tyrrell</option>
        <option value="Washington">Washington</option>
        </select>' ;
    }
    if ($col=='orange')
    {
    	echo '
    	<select name="county">
    	<option value=""></option>
        <option value="Brunswick">Brunswick</option>
        <option value="Carteret">Carteret</option>
        <option value="Craven">Craven</option>
        <option value="Duplin">Duplin</option>
        <option value="Greene">Greene</option>
        <option value="Johnston">Johnston</option>
        <option value="Jones">Jones</option>
        <option value="Lenoir">Lenoir</option>
        <option value="New Hanover">New Hanover</option>
        <option value="Onslow">Onslow</option>
        <option value="Pamlico">Pamlico</option>
        <option value="Pender">Pender</option>
        <option value="Sampson">Sampson</option>
        <option value="Wayne">Wayne</option>
        <option value="Wilson">Wilson</option>
        </select>';
    }
    if ($col=='blue')
    {
    	echo '
    	<select name="county">
    	<option value=""></option>
       <option value="Anson">Anson</option>
       <option value="Bladen">Bladen</option>
       <option value="Cabarrus">Cabarrus</option>
       <option value="Columbus">Columbus</option>
       <option value="Cumberland">Cumberland</option>
       <option value="Harnett">Harnett</option>
       <option value="Hoke">Hoke</option>
       <option value="Lee">Lee</option>
       <option value="Montgomery">Montgomery</option>
       <option value="Moore">Moore</option>
       <option value="Richmond">Richmond</option>
       <option value="Robeson">Robeson</option>
       <option value="Scotland">Scotland</option>
       <option value="Stanly">Stanly</option>
       <option value="Union">Union</option>
       </select> ';
    }
?>
<input type="submit" value="Search">
<script language="JavaScript">
function openLinks(fromID,tableID){
	$('#' + tableID).show("slow");
	$('#' + fromID).attr('onClick','closeLinks("'+fromID+'","'+tableID+'")');
	var header = $('#' + fromID + ' .subhead').html();
	var save = header.substring(2,header.length);
	$('#' + fromID + ' .subhead').html(' &darr;' + save);
}
function closeLinks(fromID,tableID){
	$('#' + tableID).hide("slow");
	$('#' + fromID).attr('onClick','openLinks("'+fromID+'","'+tableID+'")');
	var header = $('#' + fromID + ' .subhead').html();
	var save = header.substring(2,header.length);
	$('#' + fromID + ' .subhead').html(' &rarr;' + save);
}
</script><br>
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
		var sh = "<?php echo $col ?>";
		cty.toLowerCase();

      downloadUrl("genXMLPartnersDetail.php?c=" + c + "&z=" + z + "&r=" + r + "&cty=" + cty + "&sh=" + sh, function(data) {
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
	<br>
	<p><em>Please click to show or hide lists</em></p>	
<?php	
if ( empty($category) || ($category == 'eatingout')) {
echo '
	<div id="eatingOutLinks" onclick="openLinks(\'eatingOutLinks\',\'eatingOut_partners\')">
	<p class="subhead">&rarr;
	Eating out
	</p>
	<p class="caption">
	The chefs at our partner restaurants are committed to sourcing 10 Percent or more seasonal, local ingredients from NC Farms.
	</p>
	</div>
	<p>

	<div id="eatingOut_partners" style="display:none;">
	<table id="partners">
	';

		if (empty($zipCode) && empty($radius) && empty($county) && (empty($category) || !empty($category)) ) {
			$res= __get_restaurants_in_region($col);
		} else if(!empty($radius) && !empty($zipCode) && !empty($county)) {
			$res=__get_restaurants_in_regionByZipAndRadiusAndCounty($col, $zipCode, $radius, $county);
		} else if(!empty($radius) && !empty($zipCode)) {
			$res=__get_restaurants_in_regionByZipAndRadius($col, $zipCode, $radius);
		} elseif (!empty($zipCode)) {
			$res=__get_restaurants_in_regionByZip($col, $zipCode);
			if (empty($res) ) {
				$res= __get_restaurants_in_region($col);
			}
			//echo "ZC: $zipCode res: $res </br>";
		} elseif (!empty($county)) {
			$res= __get_restaurants_in_regionByCounty($county);
		}
		//$res= __get_restaurants_in_region($col);
		$num= mysql_num_rows($res);
		//echo 'The number of rows is'.$num;
		$i=4;
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
				echo '<td>'.'<a href='.$row['url'].' target="_blank">'.$row['business'].'</a>'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'</td>';
			}
			$i++;
			if ($i % 4==0)
			{
				echo '</tr>';
				echo '<tr>';
			}
			
		}
		
	echo '
	<br/>
	</table>
	</div>
	</p>
	</br>';
}
?>

<?php	
if ( empty($category) || ($category == 'homebusiness')) {
	echo '
<div id="toGoLinks" onclick="openLinks(\'toGoLinks\',\'toGo_partners\')">
<p class="subhead">&rarr;

	Bring it Home...or to your Business
	</p>
	<p class="caption">
	Find Grocers, Farmers Markets, CSAs, CSFs, and more right in your neighborhood.
	</p>
	</div>
	<br />
	<div id="toGo_partners" style="display:none;">
	<p style="color:#5e8fb2">
	<i><b>Farmers Markets</b></i>
	</p>
	<table id="partners">
	';
	
	if (empty($zipCode) && empty($radius) && empty($county) && (empty($category) || !empty($category)) ) {
		$res= __get_frmmkt_in_region($col);
	} else if(!empty($radius) && !empty($zipCode) && !empty($county)) {
			$res=__get_frmmkt_in_regionByZipAndRadiusAndCounty($col, $zipCode, $radius, $county);
	} else if(!empty($radius) && !empty($zipCode)) {
			$res=__get_frmmkt_in_regionByZipAndRadius($col, $zipCode, $radius);
	} elseif (!empty($zipCode)) {
		$res=__get_frmmkt_in_regionByZip($col, $zipCode);
		if (empty($res) ) {
			$res= __get_frmmkt_in_region($col);
		}
		//echo "ZC: $zipCode res: $res </br>";
	} elseif (!empty($county)) {
		$res=__get_frmmkt_in_regionByCounty($county);
	}
	
    $num= mysql_num_rows($res);
    //echo 'The number of rows is'.$num;
	$i=4;
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
            echo '<td>'.'<a href='.$row['url'].' target="_blank">'.$row['business'].'</a>'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'</td>';
		}
		$i++;
		if ($i % 4==0)
		{
            echo '</tr>';
            echo '<tr>';
		}
		
	}
	echo '
	<br />
	<p style="color:#5e8fb2">
	<i><b>Retail, Grocers, Co-ops and Suppliers</b></i>
	</p>

	<table id="partners">';

	if (empty($zipCode) && empty($radius) && empty($county) && (empty($category) || !empty($category)) ) {
		$res= __get_groc_in_region($col);
	} else if(!empty($radius) && !empty($zipCode) && !empty($county)) {
			$res=__get_groc_in_regionByZipAndRadiusAndCounty($col, $zipCode, $radius, $county);
	} else if(!empty($radius) && !empty($zipCode)) {
			$res=__get_groc_in_regionByZipAndRadius($col, $zipCode, $radius);
	} elseif (!empty($zipCode)) {
		$res=__get_groc_in_regionByZip($col, $zipCode);
		if (empty($res) ) {
			$res= __get_groc_in_region($col);
		}
		//echo "ZC: $zipCode res: $res </br>";
	} elseif (!empty($county)) {
		$res=__get_groc_in_regionByCounty($county);
	}
	
    $num= mysql_num_rows($res);
    //echo 'The number of rows is'.$num;
	$i=4;
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
            echo '<td>'.'<a href='.$row['url'].' target="_blank">'.$row['business'].'</a>'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'</td>';
		}
		$i++;
		if ($i % 4==0)
		{
            echo '</tr>';
            echo '<tr>';
		}
		
	}
	
	echo '
	</table>
	<br />
	</div>
	</p>
	';
}
?>

<?php	
if ( empty($category) || ($category == 'partners')) {
	echo '
<div id="farmLinks" onclick="openLinks(\'farmLinks\',\'farm_partners\')">
<p class="subhead">&rarr;

	Our farm partners
	</p>
	<p class="caption">
	Our farm partners are providing food to North Carolinians in a variety of ways. Check out their websites for details on where you\'ll find their farms and their products.
	</p>
	</div>
	<div id="farm_partners" style="display:none;">
	<p>
	<table id="partners">
	';

	if (empty($zipCode) && empty($radius) && empty($county) && (empty($category) || !empty($category)) ) {
		$res= __get_farms_in_region($col);
	} else if(!empty($radius) && !empty($zipCode) && !empty($county)) {
			$res=__get_farms_in_regionByZipAndRadiusAndCounty($col, $zipCode, $radius, $county);
	} else if(!empty($radius) && !empty($zipCode)) {
			$res=__get_farms_in_regionByZipAndRadius($col, $zipCode, $radius);
	} elseif (!empty($zipCode)) {
		$res=__get_farms_in_regionByZip($col, $zipCode);
		if (empty($res) ) {
			$res= __get_farms_in_region($col);
		}
		//echo "ZC: $zipCode res: $res </br>";
	} elseif (!empty($county)) {
		$res=__get_farms_in_regionByCounty($county);
	}
	
    $num= mysql_num_rows($res);
    //echo 'The number of rows is'.$num;
	$i=4;
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
            echo '<td>'.'<a href='.$row['url'].' target="_blank">'.$row['business'].'</a>'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'</td>';
		}
		$i++;
		if ($i % 4==0)
		{
            echo '</tr>';
            if ($i != (num+4))
            {
                echo '<tr>';
            }
		}
		
	}
	echo '
	</tr>
	</table>
	</p>
	</div>
	</br></br>';
}
?>


<?php	
if ( empty($category) || ($category == 'commPartners')) {
	echo '
<div id="comLinks" onclick="openLinks(\'comLinks\',\'com_partners\')">
<p class="subhead">&rarr;

	Community partners
	</p>
	</div>
	<div id="com_partners" style="display:none;">
	<p>
	<table id="partners">
	';

	if (empty($zipCode) && empty($radius) && empty($county) && (empty($category) || !empty($category)) ) {
		$res= __get_community_in_region($col);
	} else if(!empty($radius) && !empty($zipCode) && !empty($county)) {
			$res=__get_community_in_regionByZipAndRadiusAndCounty($col, $zipCode, $radius, $county);
	} else if(!empty($radius) && !empty($zipCode)) {
			$res=__get_community_in_regionByZipAndRadius($col, $zipCode, $radius);
	} elseif (!empty($zipCode)) {
		$res=__get_community_in_regionByZip($col, $zipCode);
		if (empty($res) ) {
			$res= __get_community_in_region($col);
		}
		//echo "ZC: $zipCode res: $res </br>";
	} elseif (!empty($county)) {
		$res=__get_community_in_regionByCounty($county);
	}
	
    $num= mysql_num_rows($res);
    //echo 'The number of rows is'.$num;
	$i=4;
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
            echo '<td>'.'<a href='.$row['url'].' target="_blank">'.$row['business'].'</a>'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'</td>';
		}
		$i++;
		if ($i % 4==0)
		{
            echo '</tr>';
            if ($i != (num+4))
            {
                echo '<tr>';
            }
		}
		
	}
	echo '
	</tr>
	</table>
	</p>
	</div>
	</br></br>';
}
?>

<?php	
if ( empty($category) || ($category == 'statePartners')) {
	echo '
	<div id="stateLinks" onclick="openLinks(\'stateLinks\',\'state_partners\')">
	<p class="subhead">&rarr;
	
	Statewide partners
	</p>
	<p class="caption">
	These statewide businesses, organizations, and agencies are supporting North Carolina\'s local food system.
	</p>
	</div>
	<div id="state_partners" style="display:none;">
	<p>
	<table id="partners">
	';

	if (empty($zipCode) && empty($radius) && empty($county) && (empty($category) || !empty($category)) ) {
		$res= __get_statewide_in_region();
	} else if(!empty($radius) && !empty($zipCode) && !empty($county)) {
			$res=__get_statewide_in_regionByZipAndRadiusAndCounty($col, $zipCode, $radius, $county);
	} else if(!empty($radius) && !empty($zipCode)) {
			$res=__get_statewide_in_regionByZipAndRadius($col, $zipCode, $radius);
	} elseif (!empty($zipCode)) {
		$res=__get_statewide_in_regionByZip($col, $zipCode);
		if (empty($res) ) {
			$res= __get_statewide_in_region();
		}
		//echo "ZC: $zipCode res: $res </br>";
	} elseif (!empty($county)) {
		$res=__get_statewide_in_regionByCounty($county);
	}
	
	$num= mysql_num_rows($res);
    //echo 'The number of rows is'.$num;
	$i=4;
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
            echo '<td>'.'<a href='.$row['url'].' target="_blank">'.$row['business'].'</a>'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'</td>';
		}
		$i++;
		if ($i % 4==0)
		{
            echo '</tr>';
            echo '<tr>';
		}
		
	}

	echo'
	</table>

	</p>
	</div>
	<br /><br />
	';
}
?>

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
