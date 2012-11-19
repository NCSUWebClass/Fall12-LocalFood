<?php include('header.php'); ?>

<?php
    $col=$_GET['color'];
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
<img src="img/lfl_map.png" alt="look for local" usemap="#Map2" style="padding-top:20px;padding-bottom:20px; width="400" height="200" />
<map name="Map2" id="Map2">
<area shape="poly" coords="410,252,406,110,429,92,570,152,445,251,410,250" href="partners_detail.php?color=orange" alt="orange" />
<area shape="poly" coords="262,95,392,111,405,224,256,168" href="partners_detail.php?color=blue" alt="blue" />
<area shape="poly" coords="176,15,119,141,4,126" href="partners_detail.php?color=green" alt="green" />
<area shape="poly" coords="200,-10,278,-3,243,140,152,136" href="partners_detail.php?color=purple" alt="purple" />
<area shape="rect" coords="282,7,432,77" href="partners_detail.php?color=red" alt="red" />
<area shape="poly" coords="457,3,594,8,611,108,441,85" href="partners_detail.php?color=yellow" alt="yellow" />
</map>
</div>
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />


<form method="post" action="<?php echo $PHP_SELF;?>">
<p class ="subhead">
Zip Code:<input name="zipCode" type="number" size="12" maxlength="5" >
Radius:<input name="zipCode" type="number" size="12" maxlength="5" > miles
<br />
Category:
	<select name="category">
	<option value="null"></option>
	<option value="eatingout">Eating Out</option>
	<option value="homebusiness">Bring It Home...Or To Your Business</option>
	<option value="partners">Our Farm Partners</option>
	<option value="commPartners">Community Partners</option>
	</select>
County:
<?php
	if ($col=='purple')
    { 
    	echo '
        <select name="category">
        <option value="countyselect"></option>
		<option value="countyselect">Alexander</option>
		<option value="countyselect">Alleghany</option>
		<option value="countyselect">Ashe</option>
		<option value="countyselect">Burke</option>
		<option value="countyselect">Caldwell</option>
		<option value="countyselect">Catawba</option>
		<option value="countyselect">Davie</option>
		<option value="countyselect">Gaston</option>
		<option value="countyselect">Mecklenburg</option>
		<option value="countyselect">Rowan</option>
		<option value="countyselect">Rutherford</option>
		<option value="countyselect">Surry</option>
		<option value="countyselect">Wilkes</option>
		<option value="countyselect">Yadkin</option>
		</select>';
    }
   if ($col=='green')
    {
    	echo '
    	<select name="category">
    	<option value="countyselect"></option>
        <option value="countyselect">Avery</option>
        <option value="countyselect">Buncombe</option>
        <option value="countyselect">Cherokee</option>
        <option value="countyselect">Cherokee Reservation</option>
        <option value="countyselect">Clay</option>
        <option value="countyselect">Graham</option>
        <option value="countyselect">Haywood</option>
        <option value="countyselect">Henderson</option>
        <option value="countyselect">Jackson</option>
        <option value="countyselect">Macon</option>
        <option value="countyselect">Madison</option>
        <option value="countyselect">McDowell</option>
        <option value="countyselect">Mitchell</option>
        <option value="countyselect">Polk</option>
        <option value="countyselect">Swain</option>
        <option value="countyselect">Transylvania</option>
        <option value="countyselect">Watauga</option>
        <option value="countyselect">Yancey</option>
        </select>';
    }
    if ($col=='red')
    {
    	echo '
    	<select name="category">
    	<option value="countyselect"></option>
        <option value="countyselect">Alamance</option>
        <option value="countyselect">Caswell</option>
        <option value="countyselect">Chatham</option>
        <option value="countyselect">Davidson</option>
        <option value="countyselect">Durham</option>
        <option value="countyselect">Forsyth</option>
        <option value="countyselect">Franklin</option>
        <option value="countyselect">Granville</option>
        <option value="countyselect">Guilford</option>
        <option value="countyselect">Orange</option>
        <option value="countyselect">Person</option>
        <option value="countyselect">Randolph</option>
        <option value="countyselect">Rockingham</option>
        <option value="countyselect">Stokes</option>
        <option value="countyselect">Vance</option>
        <option value="countyselect">Wake</option>
        <option value="countyselect">Warren</option>
        </select>';
    }
    if ($col=='yellow')
    {
    	echo '
    	<select name="category">
    	<option value="countyselect"></option>
        <option value="countyselect">Beaufort</option>
        <option value="countyselect">Bertie</option>
        <option value="countyselect">Camden</option>
        <option value="countyselect">Chowan</option>
        <option value="countyselect">Currituck</option>
        <option value="countyselect">Dare</option>
        <option value="countyselect">Edgecombe</option>
        <option value="countyselect">Gates</option>
        <option value="countyselect">Halifax</option>
        <option value="countyselect">Hertford</option>
        <option value="countyselect">Hyde</option>
        <option value="countyselect">Martin</option>
        <option value="countyselect">Nash</option>
        <option value="countyselect">Northampton</option>
        <option value="countyselect">Pasquotank</option>
        <option value="countyselect">Perquimans</option>
        <option value="countyselect">Pitt</option>
        <option value="countyselect">Tyrrell</option>
        <option value="countyselect">Washington</option>
        </select>' ;
    }
    if ($col=='orange')
    {
    	echo '
    	<select name="category">
    	<option value="countyselect"></option>
        <option value="countyselect">Brunswick</option>
        <option value="countyselect">Carteret</option>
        <option value="countyselect">Craven</option>
        <option value="countyselect">Duplin</option>
        <option value="countyselect">Greene</option>
        <option value="countyselect">Johnston</option>
        <option value="countyselect">Jones</option>
        <option value="countyselect">Lenoir</option>
        <option value="countyselect">New Hanover</option>
        <option value="countyselect">Onslow</option>
        <option value="countyselect">Pamlico</option>
        <option value="countyselect">Pender</option>
        <option value="countyselect">Sampson</option>
        <option value="countyselect">Wayne</option>
        <option value="countyselect">Wilson</option>
        </select>';
    }
    if ($col=='blue')
    {
    	echo '
    	<select name="category">
    	<option value="countyselect"></option>
       <option value="countyselect">Anson</option>
       <option value="countyselect">Bladen</option>
       <option value="countyselect">Cabarrus</option>
       <option value="countyselect">Columbus</option>
       <option value="countyselect">Cumberland</option>
       <option value="countyselect">Harnett</option>
       <option value="countyselect">Hoke</option>
       <option value="countyselect">Lee</option>
       <option value="countyselect">Montgomery</option>
       <option value="countyselect">Moore</option>
       <option value="countyselect">Richmond</option>
       <option value="countyselect">Robeson</option>
       <option value="countyselect">Scotland</option>
       <option value="countyselect">Stanly</option>
       <option value="countyselect">Union</option>
       </select> ';
    }
?>
<input type="submit" value="Search">
</form>
<br /><br />
</p>

<p class="subhead">
Eating out
</p>
<p class="caption">
The chefs at our partner restaurants are committed to sourcing 10 Percent or more seasonal, local ingredients from NC Farms.
</p>
<p>

<table id="partners">
<?php
    $res= __get_restaurants_in_region($col);
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
	?>
</table>

</p>
<br /><br /><br />


<p class="subhead">
Bring it Home...or to your Business
</p>
<p class="caption">
Find Grocers, Farmers Markets, CSAs, CSFs, and more right in your neighborhood.
</p>

<br />
<p style="color:#5e8fb2">
<i><b>Farmers Markets</b></i>
</p>
<table id="partners">
<?php
    $res= __get_frmmkt_in_region($col);
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
	?>
</table>

<br />
<p style="color:#5e8fb2">
<i><b>Retail, Grocers, Co-ops and Suppliers</b></i>
</p>

<table id="partners">
<?php
    $res= __get_groc_in_region($col);
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
	?>
</table>

<br />



</p>
<br /><br /><br />


<p class="subhead">
Our farm partners
</p>
<p class="caption">
Our farm partners are providing food to North Carolinians in a variety of ways. Check out their websites for details on where you'll find their farms and their products.
</p>
<p>
<table id="partners">
<?php
    $res= __get_farms_in_region($col);
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
	?>
</tr>
</table>
</p>
<br /><br /><br />



<p class="subhead">
Community partners
</p>
<p>
<table id="partners">
<?php
    $res= __get_community_in_region($col);
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
	?>
</tr>
</table>


</p>
<br /><br /><br />



<p class="subhead">
Statewide partners
</p>
<p class="caption">
These statewide businesses, organizations, and agencies are supporting North Carolina's local food system.
</p>
<p>
<table id="partners">
<?php
    $res= __get_statewide_in_region();
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
	?>
</table>



</p>
<br /><br />



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
