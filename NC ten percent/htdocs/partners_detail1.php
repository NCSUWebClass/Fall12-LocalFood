<?php include('header.php'); ?>

<?php
$col=$_GET['color'];
$type=$_GET['type'];
?>
<div id="belowfold">
<div id="contentfullwidth">
	<div id="leftcolumn">
    <h1><?php echo "Our ".$type." partners"; ?></h1>
    </div>
    <div id="rightcolumn">
    <?php echo '<img src="img/'.$col.'.png" />'; ?>
    </div>
    <br /><br /><br /><br /><br /><br />
    
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
	 $res= __get_restaurants_in_region($col);
          }
	 else if ($type=='farm')
{
$res= __get_farms_in_region($col);
}
else if ($type=='community')
{
$res= __get_community_in_region($col);
}

else if ($type=='business')
{
$res=__get_frmmkt_in_region($col);
$res1=__get_groc_in_region($col);
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
    
     <?php
if ($type=='restaurant')
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
?>
    
    
   
    
    
    <br /><br /><br />
</div><!-- end #contentright -->

<?php include('footer.php'); ?>
