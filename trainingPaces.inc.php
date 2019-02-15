<?php

require_once("utils.php");
require_once("CalculatorFunctions.php");

/* echo var_dump($_REQUEST); */
$distance = isset($_REQUEST['distance']) ? $_REQUEST['distance'] : DEFAULT_DISTANCE;
$units = isset($_REQUEST['units']) ? $_REQUEST['units'] : DEFAULT_UNITS;
$timeHH = isset($_REQUEST['timeHH']) ? $_REQUEST['timeHH'] : DEFAULT_HOURS;
$timeMM = isset($_REQUEST['timeMM']) ? $_REQUEST['timeMM'] : DEFAULT_MINUTES;
$timeSS = isset($_REQUEST['timeSS']) ? $_REQUEST['timeSS'] : DEFAULT_SECONDS;
$gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : DEFAULT_GENDER;


/*
** Using the input values, need to compute the pace in Seconds Per Meter. Given that,
** we can easily compute the splits for the Mile, 1600m, ... 100m
*/
$minutes = HHMMSSToMinutes($timeHH, $timeMM, $timeSS);

// Want distance in KM
if ($units == 'KM') 
{   // Just use the value as is 
	$km = $distance;
}
else  
{ 	// convert from miles to KM
	$km = $distance * KM_PER_MILE;
}

$calcObj = new Calculator($km, $minutes); // e.g. 5K in 15.50 minutes

$paceDistances = array(array("Mile",  KM_PER_MILE), 
		                 array("1600m", 1.6),
		                 array("1000m", 1.0),
		                 array("800m",  0.8),
		                 array("400m",  0.4),
		                 array("200m",  0.2));
$paceIntensities = array(
                   array("Very Easy",            59.53, 63.87),
						 array("Easy",                 64.19, 66.48), 
						 array("Easy/Moderate",        68.75, 71.0),
						 array("Moderate",             73.22, 75.42),
						 array("Easy Tempo",           77.60, 79.75),
						 array("Tempo",                81.88, 83.99),
						 array("Threshold",            86.07, 88.13),
						 array("CV",                   90.17, 92.18),
						 array("Aerobic Power",        94.17, 96.14),
						 array("V.O2 Max",             98.08, 100),
						 array("Anaerobic Endurance", 103.77, 107.45),
						 array("Anaerobic Power",     111.03, 114.52),
						 array("Speed Endurance",     117.51, 121.21),
						 array("Speed",               124.42, 127.54)
						 );

// echo "<table id=\"trainingPaces\">";
echo "<table class=\"myTable\">";
   echo "<caption>Training Paces By Intensity (mm:ss)</caption>";
  
	echo "<tr>";
   	echo "<th>Intensity</th>";
   	foreach ($paceDistances as $k => $v)
   	{
      	echo "<th>$v[0]</th>";
   	}
   	echo "</tr>";
   	/* Now, go back and fill in the intensity values */
   
   	foreach ($paceIntensities as $kpi => $vpi)
   	{
      	echo "<tr>";
      	echo "<td>$vpi[0]</td>";

         $tiSlower = $vpi[1]; // training intensity (slower)
         $tiFaster = $vpi[2]; // training intensity (faster)
            
         foreach ($paceDistances as $kpd => $vpd)
      	{
            $dist = $vpd[1]; // Distance
   		 	$paceSlower = $calcObj->TrainingPaceInMinutes($tiSlower / 100.0, $dist);
            $paceFaster = $calcObj->TrainingPaceInMinutes($tiFaster / 100.0, $dist);
		 	   //$sSlower = SecondsToHHMMSS($paceSlower * 60.0, $hh, $mm, $ss, $tt);
            //$sFaster = SecondsToHHMMSS($paceFaster * 60.0, $hh, $mm, $ss, $tt);
            
            $sSlower = FormatTime($paceSlower * 60.0, 60);
            $sFaster = FormatTime($paceFaster * 60.0, 60);
			   echo "<td>$sSlower - $sFaster</td>";
         }
      	echo "</tr>";
      }
echo "</table>";
?>
