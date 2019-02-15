<?php

require_once("CalculatorFunctions.php");

/* echo var_dump($_REQUEST); */
$distance = isset($_REQUEST['distance']) ? $_REQUEST['distance'] : DEFAULT_DISTANCE;
$units = isset($_REQUEST['units']) ? $_REQUEST['units'] : DEFAULT_UNITS;
$timeHH = isset($_REQUEST['timeHH']) ? $_REQUEST['timeHH'] : DEFAULT_HOURS;
$timeMM = isset($_REQUEST['timeMM']) ? $_REQUEST['timeMM'] : DEFAULT_MINUTES;
$timeSS = isset($_REQUEST['timeSS']) ? $_REQUEST['timeSS'] : DEFAULT_SECONDS;
$timeT = isset($_REQUEST['timeT']) ? $_REQUEST['timeT'] : DEFAULT_TENTHS;
$gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : DEFAULT_GENDER;


/*
** Using the input values, need to compute the pace in Seconds Per Meter. Given that,
** we can easily compute the splits for the Mile, 1600m, ... 100m
*/
$totalSeconds = HHMMSSToSeconds($timeHH, $timeMM, $timeSS, $timeT);
$totalMeters= $distance * 1000; 
$secsPerMeter = $totalSeconds / $totalMeters;

/* display the Race Splits in a table within a fieldset */
$splitDistances = array(
	array("Mile",  METERS_PER_MILE), 
	array("1600m", 1600),
	array("1200m", 1200),
	array("1000m", 1000),
	array("800m",   800),
	array("600m",   600),
	array("400m",   400),
	array("300m",   300),
	array("200m",   200)
	);
// echo "<table id=\"raceSplits\">";
echo "<table class=\"myTable\">";
echo "<caption>Race Splits</caption>";
echo "<tr>";
foreach ($splitDistances as $k => $v)
{
	echo "<th>$v[0]</th>";
}
echo "</tr>";
   
echo "<tr>";
foreach ($splitDistances as $k => $v)
{
   $split = $secsPerMeter * $v[1];
	$splitString = SecondsToHHMMSST($split, $hh, $mm, $ss, $tt);
   echo "<td>$splitString</td>";
}
echo "</tr>";
echo "</table>";