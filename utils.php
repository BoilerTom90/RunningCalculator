<?php

define('METERS_PER_MILE', '1609.344'); 
define('KM_PER_MILE', '1.609344'); 
define('MILE_PER_KM', (1.0 / KM_PER_MILE));
define('METERS_PER_KM',   '1000.00'); 
define('DEFAULT_DISTANCE', '5');
define('DEFAULT_UNITS', 'KM');
define('DEFAULT_HOURS', '0');
define('DEFAULT_MINUTES', '15');
define('DEFAULT_SECONDS', '35');
define('DEFAULT_TENTHS', '0');
define('DEFAULT_GENDER', 'M');
define('MAX_MINUTES', (100 * 60));

const METERS = "METERS";
const KM     = "KM";
const MILES  = "MILES";



$DistanceConversionFactor = array(	"METERS" => 0.001,
	  								"KM"     => 1.0,
	  								"MILES"  => 1.609344);
									
									
$distanceChoiceArray = array(
	array("disabled", "Pick Distance"),
	array(5, "5 km"),
	array(10, "10 km"),
	array(26.2187 / 2 * KM_PER_MILE, "1/2 Marathon"),
	array(26.2187 * KM_PER_MILE, "Marathon"),
	array("disabled", "-- Metric --"),
	array(800.0 / 1000, "800 meters"),
	array(1500.0 / 1000, "1500 meters"),
	array(1600.0 / 1000, "1600 meters"),
   array(2.0, "2000 meters"),
	array(3.0, "3000 meters"),
	array(3.2, "3200 meters"),
	array(4, "4 km"),
	array(10,  "10 km"),
	array(15,  "15 km"),
	array(20,  "20 km"),
	array(30,  "30 km"),
	array(50,  "50 km"),
	array("disabled", "-- Imperial--"),
	array(0.5 * KM_PER_MILE, "880 yards"),
	array(1.0 * KM_PER_MILE, "1 mile"),
	array(1.5 * KM_PER_MILE, "1.5 miles"),
	array(2.0 * KM_PER_MILE, "2 miles"),
	array(3.0 * KM_PER_MILE, "3 miles"),
	array(4.0 * KM_PER_MILE, "4 miles"),
	array(5.0 * KM_PER_MILE, "5 miles"),
	array(6.0 * KM_PER_MILE, "6 miles"),
	array(10.0 * KM_PER_MILE, "10 miles"),
	array(20.0 * KM_PER_MILE, "20 miles"),
	array(30.0 * KM_PER_MILE, "30 miles"),
	array(50.0 * KM_PER_MILE, "50 miles"),
	array(100.0 * KM_PER_MILE, "100 miles"),
	);									
	
function DisplayableDistanceValue($km)
{
	global $distanceChoiceArray;
	
    foreach ($distanceChoiceArray as $k => $v)
	{
		if ($km == $v[0])
			return($v[1]);
	}		
	return("unknown");
}

//
// these are the factors for metric race distances.
// We start with a race time normalized to a 3K time (hence, why the 3000m factor is 1.0, 
// then use this matrix to get an equivalent time for a different distance.
//									
$raceTimeFactorsMetric = array(	
	array("800m",  0.8,  0.2290666101), 
   array("1000m", 1.0, 0.2950627999), 
	array("1200m", 1.2,  0.3628802967), 
   array("1500m", 1.5, 0.4672897197),
	array("1600m", 1.6,  0.5026411124), 
   array("2000m", 2.0, 0.645003992574), // orig value: 0.6456728591
	array("2400m", 2.4,  0.7892993389), 
   array("3000m", 3.0, 1.0000000000),
	array("3200m", 3.2,  1.0707571179), 
   array("4km",	4.0, 1.3556493194),
	array("5km",     5,  1.7157906754), 
   array("6km",	6.0, 2.0801514774),
	array("8km",     8,  2.8204855858), 
   array("10km", 10.0, 3.5746490414),
	array("12km",    12,  4.3407817434), 
   array("15km",	15.0, 5.5087048524),
	array("20km",    20,  7.4927905834), 
   array("25km",	25.0, 9.5073052094),
	array("30km",    30, 11.5382142054), 
   array("Marathon", 42.1, 16.5202353314)
   );
		
// the 2nd value in each array is the corresponding KM distance
$raceTimeFactorsImperial= array(	
	array("880yd",    0.5 * KM_PER_MILE, 0.230584992), 
	array("1000yd",  (1000/1760) * KM_PER_MILE, 0.266573999),
	array("1320yd",  0.75 * KM_PER_MILE, 0.365285536), 
	array("1mi",      1.0 * KM_PER_MILE, 0.505955438),
	array("1.5mi",    1.5 * KM_PER_MILE, 0.794309879), 
	array("2mi",      2.0 * KM_PER_MILE, 1.07386144),
	array("3mi",      3.0 * KM_PER_MILE, 1.65355001),	 
	array("4mi",      4.0 * KM_PER_MILE, 2.240772822),
	array("5mi",      5.0 * KM_PER_MILE, 2.837952614), 
	array("6mi",      6.0 * KM_PER_MILE, 3.444054968),
	array("10mi",    10.0 * KM_PER_MILE, 5.939092753), 
	array("1/2 Marathon",  26.2187 / 2 * KM_PER_MILE, 7.93294), 
	array("20mi",    20.0 * KM_PER_MILE, 12.42927589), 
	array("Marathon",  26.2187 * KM_PER_MILE, 16.52055)
		);
						
									
function HHMMSSToSeconds($hh, $mm, $ss, $t = 0)
{
	$retVal = ($hh * 60 * 60) +
	          ($mm * 60) + 
	           $ss + 
			  ($t / 10);
	return($retVal);
}

function ConvertDistanceToKM($inDistance, $inDistanceUnits, &$outDistance)
{
	global $DistanceConversionFactor;
		
	$outDistance = 0;
	if (isset($DistanceConversionFactor[$inDistanceUnits]))
	{
		$outDistance = $inDistance * $DistanceConversionFactor[$inDistanceUnits];
	}
	return;
}

function HHMMSSToMinutes($hh, $mm, $ss, $t = 0)
{
	$retVal = ($hh * 60) + ($mm) + (float)($ss/60) + (float)($t /600);
	return($retVal);
}

function SecondsToHHMMSST($seconds, &$hh, &$mm, &$ss, &$tt)
{
	$hh = (int)($seconds / 3600);
	$seconds -= ($hh * 3600);
	$mm = (int)($seconds / 60);
	$seconds -= ($mm * 60);
	$ss = (int)($seconds % 60);
	$seconds -= $ss;
	$tt = round($seconds * 10, 0, PHP_ROUND_HALF_UP);
	// printf("Remaining Seconds: %f\n", $ss);
	// $tt = ($ss);
	
	// Now, build up a printable string. If the hours is 0, don't put it in the string
	// If there are no hours, don't include a leading 0 in the minutes.
	$hhmmsstt ="";
	if ($hh > 0)
	{
		$hhmmsstt = sprintf("%d:", $hh);
	}
	if ($hhmmsstt == "") 
	{
		$hhmmsstt = sprintf("%d:%02d.%1d", $mm, $ss, $tt);
	}
	else
	{
		$hhmmsstt = $hhmmsstt . sprintf("%02d:%02d.%1d", $mm, $ss, $tt);
	}
	return($hhmmsstt);
}

// Yet Another Time Formatter... Want a function that conditionally displays the tenths value
// depending on the magnitude of the total time. For example, when looking at a race distance over 
// 2 hours, the tenths isn't important. But, tenths is important for an 800m or 1600m run, or for a
// 200m split.
function FormatTime($inputSeconds, $tenthsThreshold = 120)
{
   $seconds = $inputSeconds;
   $hh = (int)($inputSeconds / 3600);
	$seconds -= ($hh * 3600);
	$mm = (int)($seconds / 60);
	$seconds -= ($mm * 60);
	$ss = (int)($seconds % 60);
	$seconds -= $ss;
	$tt = round($seconds * 10, 0, PHP_ROUND_HALF_UP);
   
	$s = "";
   if ($hh > 0) 
   {
      // If the hours is present, include everything, except the tenths
      // depending on the threshold
      if ($tenthsThreshold > $inputSeconds) 
      {
         // include everything, with leading zeros on all except for hours
         $s = sprintf("%d:%02d:%02d.%1d", $hh, $mm, $ss, $tt);
      }
      else
      {
         // include everything but tenths
         $s = sprintf("%d:%02d:%02d", $hh, $mm, $ss);
      }
      return($s);
   } 
  
  
   // no hours were present
   if ($mm > 0)
   {
      if ($tenthsThreshold > $inputSeconds) 
      {
         // include everything (mm, ss, t), with leading zeros on all except for minutes
         $s = sprintf("%d:%02d.%1d", $mm, $ss, $tt);
      }
      else
      {
         // include everything but tenths
         $s = sprintf("%d:%02d", $mm, $ss);
      }
      return($s);
   }
  
   $s = sprintf("%02d.%1d", $ss, $tt);
   return($s);
}

function SecondsToHHMMSS($seconds, &$hh, &$mm, &$ss)
{
	$hh = (int)($seconds / 3600);
	$seconds -= ($hh * 3600);
	$mm = (int)($seconds / 60);
	$seconds -= ($mm * 60);
	$ss = (int)($seconds % 60);
	$seconds -= $ss;
	$tt = round($seconds * 10, 0, PHP_ROUND_HALF_UP);
	// printf("Remaining Seconds: %f\n", $ss);
	// $tt = ($ss);
	
	// Now, build up a printable string. If the hours is 0, don't put it in the string
	// If there are no hours, don't include a leading 0 in the minutes.
	$hhmmss ="";
	if ($hh > 0)
	{
		$hhmmss = sprintf("%d:", $hh);
	}
	if ($hhmmss == "") 
	{
		$hhmmss = sprintf("%d:%02d", $mm, $ss);
	}
	else
	{
		$hhmmss = $hhmmss . sprintf("%02d:%02d", $mm, $ss);
	}
	return($hhmmss);
}

function MetersPerMinToMinPerMile($metersPerMin, &$mm, &$ss)
{
	$fractionalPace = 1 / ($metersPerMin / METERS_PER_MILE);
	$mm = $fractionalPace % 100;  //assume minutes is never greater than 100 when it comes to pace
	$remainder = (float)($fractionalPace - $mm);
	$ss = $remainder * 60.0;		
}

function MetersPerMinToMinPerKM($metersPerMin, &$mm, &$ss)
{
	$fractionalPace = 1 / ($metersPerMin / METERS_PER_KM);
	$mm = $fractionalPace % 100;  //assume minutes is never greater than 100 when it comes to pace
	$remainder = (float)($fractionalPace - $mm);
	$ss = $remainder * 60.0;		
}


function MinPerMileToMetersPerMinute($hh, $mm, $ss)
{
	// The hh,mm,ss passed in is presumed to be the hh:mm:ss/mile (i.e. pace)
	$totalSeconds = ($hh * 3600) + ($mm * 60) + $ss;
	$metersPerMin = (float)(1.0 / ($totalSeconds / (60 * METERS_PER_MILE)));
	return($metersPerMin);
}

function MinPerKMToMetersPerMinute($hh, $mm, $ss)
{
	// The hh,mm,ss passed in is presumed to be the hh:mm:ss/mile (i.e. pace)
	$totalSeconds = ($hh * 3600) + ($mm * 60) + $ss;
	$metersPerMin = (float)(1.0 / ($totalSeconds / (60 * METERS_PER_KM)));
	return($metersPerMin);
}

function MPHtoMPM($mph, &$mm, &$ss)
{
	$mpm = round(1 / ($mph / 60), 2);
	
	$mm = $mpm % 60;
	$mpm = $mpm - $mm; // what's left is the factional minutes (between 0 and 99). Need to convert to seconds
	$ss = $mpm * 60;
	
	$mmss = sprintf("%d:%02d", $mm, $ss);
	return($mmss);
}

function KMPerHourToMinPerKM($kmPerHour, &$mm, &$ss)
{
	$minPerKM = round(1 / ($kmPerHour / 60), 2);
	
	$mm = $minPerKM % 60;
	$minPerKM = $minPerKM - $mm; // what's left is the factional minutes (between 0 and 99). Need to convert to seconds
	$ss = $minPerKM * 60;
	
	$mmss = sprintf("%d:%02d", $mm, $ss);
	return($mmss);
}

function MinPerMiletoMinPerKM($mpm)
{  
   $mpkm = "n/a";
   return($mpkm);
}

function MinPerKMToMinPerMile($mpkm)
{  
   $mpm = "n/a";
   return($mpm);
}
									
?>
