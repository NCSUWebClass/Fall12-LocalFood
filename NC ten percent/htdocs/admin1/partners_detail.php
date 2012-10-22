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
    <?php echo '<a href="partners.php"><img src="img/'.$col.'.png" border="0" /></a>'; ?>
    </div>
    <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
    
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
	<p>
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
	<p>
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
