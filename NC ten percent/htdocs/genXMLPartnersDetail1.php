<?php  

	include('defines.php');
	
	$type = $_GET['t'];
	$zc = $_GET['zip'];
	$r = $_GET['rad'];

	function parseToXML($htmlStr) { 
		$xmlStr=str_replace('<','&lt;',$htmlStr); 
		$xmlStr=str_replace('>','&gt;',$xmlStr); 
		$xmlStr=str_replace('"','&quot;',$xmlStr); 
		$xmlStr=str_replace("'",'&#39;',$xmlStr); 
		$xmlStr=str_replace("&",'&amp;',$xmlStr);
		$xmlStr=str_replace("?",'',$xmlStr);
		return $xmlStr; 
	} 

	// Start XML file, create parent node
	$dom = new DOMDocument("1.0");
	$node = $dom->createElement("markers");
	$parnode = $dom->appendChild($node); 

	// Opens a connection to a MySQL server
	$connection=mysql_connect (DATABASE_HOST, DATABASE_USER_NAME, DATABASE_USER_PASSWORD);
	if (!$connection) {  
		die('Not connected : ' . mysql_error());
	} 

	// Set the active MySQL database
	$db_selected = mysql_select_db(DATABASE_NAME, $connection);
	if (!$db_selected) {
	  die ('Can\'t use db : ' . mysql_error());
	} 

	// Select ? the rows in the markers table
	if (!empty($r) && !empty($zc) ) {

		$zipall=array(28615,28617,28626,28629,28631,28640,28643,28672,28684,28693,28694,28623,28627,28644,28663,28668,28675,27007,27017,27024,27030,27031,27041,27047,27049,27053,28621,28676,28606,28624,28635,28649,28651,28654,28656,28659,28665,28669,28670,28683,28685,28697,27011,27018,27020,27055,28642,28611,28630,28633,28638,28645,28661,28667,28636,28678,28681,28010,28115,28117,28123,28166,28625,28634,28660,28677,28687,28688,28689,28699,27006,27014,27028,27013,27054,28023,28039,28041,28071,28072,28088,28125,28138,28144,28145,28146,28147,28159,28612,28619,28628,28637,28641,28647,28655,28666,28671,28680,28690,28601,28602,28603,28609,28610,28613,28650,28658,28673,28682,28018,28019,28024,28040,28043,28074,28076,28139,28160,28167,28720,28746,28017,28020,28038,28042,28073,28086,28089,28090,28114,28136,28150,28151,28152,28169,28033,28037,28080,28092,28093,28168,28006,28012,28016,28021,28032,28034,28052,28053,28054,28055,28056,28077,28098,28101,28120,28164,28031,28035,28036,28070,28078,28105,28106,28126,28130,28134,28201,28202,28203,28204,28205,28206,28207,28208,28209,28210,28211,28212,28213,28214,28215,28216,28217,28218,28219,28220,28221,28222,28223,28224,28225,28226,28227,28228,28229,28230,28231,28232,28233,28234,28235,28236,28237,28241,28242,28243,28244,28246,28247,28250,28253,28254,28255,28256,28258,28260,28262,28263,28265,28266,28269,28270,28271,28272,28273,28274,28275,28277,28278,28280,28281,28282,28284,28285,28287,28288,28289,28290,28296,28297,28299,28604,28616,28622,28646,28652,28653,28657,28662,28664,28701,28704,28709,28711,28715,28728,28730,28748,28757,28770,28776,28778,28787,28801,28802,28803,28804,28805,28806,28810,28813,28814,28815,28816,28781,28901,28903,28905,28906,28902,28904,28909,28733,28771,28716,28721,28738,28745,28751,28785,28786,28710,28724,28726,28727,28729,28731,28732,28735,28739,28742,28758,28759,28760,28784,28790,28791,28792,28793,28707,28717,28723,28725,28736,28779,28783,28788,28789,28734,28741,28744,28763,28775,28734,28741,28744,28763,28775,28743,28753,28754,28737,28749,28752,28761,28762,28705,28765,28777,28722,28750,28756,28773,28782,28702,28713,28719,28708,28712,28718,28747,28766,28768,28772,28774,28605,28607,28608,28618,28679,28691,28692,28698,28714,28740,28755,27201,27202,27215,27216,27217,27244,27253,27258,27298,27302,27340,27349,27359,27212,27291,27305,27311,27314,27315,27379,27207,27208,27213,27228,27252,27256,27312,27344,27559,27239,28292,27293,28294,27295,27299,27351,27360,27361,27373,27374,27503,27572,27701,27702,27703,27704,27705,27706,27707,27708,27709,27710,27711,27712,27713,27715,27717,27722,27009,27010,27012,27023,27040,27045,27050,27051,27094,27098,27099,27101,27102,27103,27104,27105,27106,27107,27108,27109,27110,27111,27113,27114,27115,27116,27117,27120,27127,27130,27150,27152,27155,27157,27198,27199,27284,27285,27508,27525,27549,27596,27507,27509,27522,27564,27565,27581,27582,27214,27233,27235,27249,27260,27261,27262,27263,27264,27265,27282,27283,27301,27310,27313,27342,27357,27358,27377,27401,27402,27403,27404,27405,27406,27407,27408,27409,27410,27411,27412,27413,27415,27416,27417,27419,27420,27425,27427,27429,27435,27438,27455,27495,27495,27497,27498,27499,27231,27243,27278,27510,27514,27515,27516,27517,27599,27343,27541,27573,27574,27583,27203,27204,27205,27230,27248,27316,27317,27341,27350,27355,27370,27025,27027,27048,27288,27289,27320,27326,27375,27016,27019,27021,27022,27042,27043,27046,27052,27536,27537,27544,27553,27556,27584,27502,27511,27512,27513,27518,27519,27523,27526,27529,27502,27511,27512,27513,27518,27519,27523,27526,27529,27539,27540,27545,27560,27562,27571,27587,27588,27591,27592,27597,27601,27602,27603,27604,27605,27606,27607,27608,27609,27610,27611,27612,27613,27614,27615,27616,27617,27619,27620,27621,27622,27623,27624,27625,27626,27627,27628,27629,27634,27635,27636,27640,27650,27656,27658,27661,27668,27675,27676,27690,27695,27697,27698,27699,27551,27563,27570,27586,27589,27594,28007,28091,28102,28119,28133,28135,28170,28320,28332,28337,28392,28399,28433,28434,28448,28025,28026,28027,28075,28081,28082,28083,28107,28124,28423,28424,28430,28431,28432,28436,28438,28439,28442,28450,28455,28456,28463,28472,28301,28302,28303,28304,28305,28306,28307,28308,28309,28310,28311,28312,28314,28331,28342,28348,28356,28390,28391,28395,27501,27506,27521,27543,27546,27552,28323,28326,28334,28335,28339,28368,28376,27237,27330,27331,27332,27505,28355,27209,27229,27247,27306,27356,27371,27242,27259,27281,27325,27376,28315,28327,28350,28370,28373,28374,28387,28388,28394,28330,28338,28345,28347,28363,28367,28379,28380,28319,28340,28357,28358,28359,28360,28362,28364,28369,28371,28372,28375,28377,28378,28383,28384,28386,28343,28351,28352,28353,28396,28001,28002,28009,28097,28109,28127,28128,28129,28137,28163,28079,28103,28104,28108,28111,28112,28173,28174,27806,27808,27810,27814,27817,27821,27860,27865,27889,27805,27847,27849,27872,27924,27957,27967,27983,27921,27974,27976,27932,27980,27916,27917,27923,27927,27929,27939,27941,27947,27950,27956,27958,27964,27965,27966,27973,27915,27920,27936,27943,27948,27949,2795327954,27959,27968,27972,27978,27981,27982,27801,27802,27809,27815,27819,27852,27864,27881,27886,27926,27935,27937,27938,27946,27969,27979,27823,27839,27843,27844,27850,27870,27874,27887,27890,27818,27855,27910,27922,27942,27986,27824,27826,27875,27885,27960,27825,27840,27841,27846,27857,27861,27871,27892,27557,27803,27804,27807,27816,27856,27868,27878,27882,27891,27820,27831,27832,27842,27845,27853,27862,27866,27867,27869,27876,27877,27897,27906,27907,27909,27919,27930,27944,27985,27811,27812,27827,27828,27829,27833,27834,27835,27836,27837,27858,27879,27884,28513,28530,28590,27925,27928,27962,27970,28420,28422,28451,28452,28459,28461,28462,28465,28467,28468,28469,28470,28479,28511,28512,28516,28520,28524,28528,28531,28553,28557,28570,28575,28577,28579,28581,28582,28589,28594,28519,28523,28526,28527,28532,28533,28560,28561,28562,28563,28564,28586,28325,28341,28349,28398,28453,28458,28464,28466,28508,28518,28521,27888,28538,28554,28580,27504,27520,27524,27527,27528,27542,28555,27568,27569,27576,27577,27593,28522,28555,28573,28585,28501,28502,28503,28504,28525,28551,28572,28401,28402,28403,28404,28405,28406,28407,28408,28409,28410,28411,28412,28428,28429,28449,28480,28445,28460,28539,28540,28541,28542,28543,28544,28545,28546,28547,28574,28584,28509,28510,28515,28529,28537,28552,28556,28571,28583,28587,28421,28425,28435,28443,28454,28457,28478,28318,28328,28344,28366,28382,28385,28393,28441,28444,28447,27530,27531,27532,27533,27534,27830,27863,28333,28365,28578,27813,27822,27851,27873,27880,27883,27893,27894,27895,27896);
		$zips=$zipall;
		$numarr=count($zips);
		for($i=0; $i<$numarr;  $i++) {
	

			if ($zips[$i] == $zc) {
				$helper_query = "select min(latitude)+((max(latitude)-min(latitude))/2) as deltaLat , min(longitude)+((max(longitude)-min(longitude))/2) as deltaLng from zipcodes where zipcode = '$zc'";
				$res = mysql_fetch_assoc(mysql_query($helper_query));

				$deltaLat = $res['deltaLat'];
				$deltaLng = $res['deltaLng'];
				break;
			}
		}
		
		if ($type =='business') {
			$query = "SELECT *, ( 3959 * ACOS( COS( RADIANS(  '$deltaLat' ) ) * COS( RADIANS( lat ) ) * COS( RADIANS( lng ) - RADIANS( '$deltaLng' ) ) + SIN( RADIANS(  '$deltaLat' ) ) * SIN( RADIANS( lat ) ) ) ) AS distance FROM markers WHERE TYPE IN ('grocer',  'frmmkt') HAVING distance <  '$r' ORDER BY distance";
			$result = mysql_query($query);
		} else if ($type == 'restaurant') {
			$query = "SELECT address, name, lat, lng, ( 3959 * ACOS( COS( RADIANS(  '$deltaLat' ) ) * COS( RADIANS( lat ) ) * COS( RADIANS( lng ) - RADIANS( '$deltaLng' ) ) + SIN( RADIANS(  '$deltaLat' ) ) * SIN( RADIANS( lat ) ) ) ) AS distance FROM markers WHERE TYPE = 'restaurant' HAVING distance <  '$r' ORDER BY distance";
			$result = mysql_query($query);

		} else if ($type == 'farm') {
			$query = "SELECT address, name, lat, lng, ( 3959 * ACOS( COS( RADIANS(  '$deltaLat' ) ) * COS( RADIANS( lat ) ) * COS( RADIANS( lng ) - RADIANS( '$deltaLng' ) ) + SIN( RADIANS(  '$deltaLat' ) ) * SIN( RADIANS( lat ) ) ) ) AS distance FROM markers WHERE TYPE = 'farm' HAVING distance <  '$r' ORDER BY distance";
			$result = mysql_query($query);

		} else if ($type == 'community') {
			$query = "SELECT address, name, lat, lng, ( 3959 * ACOS( COS( RADIANS(  '$deltaLat' ) ) * COS( RADIANS( lat ) ) * COS( RADIANS( lng ) - RADIANS( '$deltaLng' ) ) + SIN( RADIANS(  '$deltaLat' ) ) * SIN( RADIANS( lat ) ) ) ) AS distance FROM markers WHERE TYPE = 'community' HAVING distance <  '$r' ORDER BY distance";
			$result = mysql_query($query);
		}
		

	} else if (!empty($zc)) {
		if ($type == 'business') {
			$query = "SELECT * FROM markers WHERE zip = '$zc' and type in ('grocer', 'frmmkt')";
			$result = mysql_query($query);

		} else if ($type == 'restaurant') {
			$query = "SELECT * FROM markers WHERE zip = '$zc' and type = 'restaurant'";
			$result = mysql_query($query);

		} else if ($type == 'farm') {
			$query = "SELECT * FROM markers WHERE zip = '$zc' and type = 'farm'";
			$result = mysql_query($query);

		} else if ($type == 'community') {
			$query = "SELECT * FROM markers WHERE zip = '$zc' and type = 'community'";
			$result = mysql_query($query);
		} 
	} else {
		if ($type == 'business') {
			$query = "SELECT * FROM markers WHERE type in ('grocer', 'frmmkt')";
			$result = mysql_query($query);
		} else if ($type == 'restaurant') {
			$query = "SELECT * FROM markers WHERE type = 'restaurant'";
			$result = mysql_query($query);
		} else if ($type == 'farm') {
			$query = "SELECT * FROM markers WHERE type = 'farm'";
			$result = mysql_query($query);
		} else if ($type == 'community') {
			$query = "SELECT * FROM markers WHERE type = 'community'";
			$result = mysql_query($query);
		}
	}

	if (!$result) {  
		  die('Invalid query: ' . mysql_error());
	} 

	header("content-type: text/xml");

	echo '<markers> ';
	while ($row=mysql_fetch_assoc($result))	{
		echo '<marker ';
		echo 'name="' . parseToXML($row['name']) . '" ';
		echo 'address="' . parseToXML($row['address']) . '" ';
		echo 'lat="' . $row['lat'] . '" ';
		echo 'lng="' . $row['lng'] . '" ';
		echo 'type="'.$row['type'].'" ';
		echo '/>';
	}		
	echo '</markers>';
?>