<?php

require_once("utils.php");
//require_once("CalculatorFunctions.php");
require_once("RaceTimesClass.php");


// echo var_dump($_REQUEST); 
$distance = isset($_REQUEST['distance']) ? $_REQUEST['distance'] : 5;
$timeHH = isset($_REQUEST['timeHH']) ? $_REQUEST['timeHH'] : DEFAULT_HOURS;
$timeMM = isset($_REQUEST['timeMM']) ? $_REQUEST['timeMM'] : DEFAULT_MINUTES;
$timeSS = isset($_REQUEST['timeSS']) ? $_REQUEST['timeSS'] : DEFAULT_SECONDS;
$timeT  = isset($_REQUEST['timeT'])  ? $_REQUEST['timeT']  : DEFAULT_TENTHS;


/*
** Using the input values, need to compute the pace in Seconds Per Meter. Given that,
** we can easily compute the splits for the Mile, 1600m, ... 100m
*/
$minutes = HHMMSSToMinutes($timeHH, $timeMM, $timeSS, $timeT);
$km = $distance;
$calcObj = new RaceTimes($km, $minutes); // e.g. 5K in 15.50 minutes

function OutputRaceLabelRow($a, $startIndex, $endIndex)
{
	echo "<tr>";
	echo "<th></th>";
	$i = 0;
	foreach ($a as $k => $v)
	{
		if (($i >= $startIndex)	&& ($i < $endIndex))
		{
			echo "<th>$v[0]</th>";
		}
		$i++;
	}
	echo "</tr>";
}

function OutputRaceTimeRow($a, $startIndex, $endIndex)
{
	global $calcObj;

	echo "<tr>";
	echo "<td>Time</td>";
	$i = 0;
	foreach ($a as $k => $v)
	{
		if (($i >= $startIndex)	&& ($i < $endIndex))
		{
			$et = $calcObj->CalcEquivRaceTimeInMinutes($v[2]);
         if ($v[1] <= 10.1)
            $etString = FormatTime($et * 60.0, MAX_MINUTES);
         else
            $etString = FormatTime($et * 60.0);
			// $etString = SecondsToHHMMSS($et  * 60.0, $hh, $mm, $ss);
			echo "<td>$etString</td>";
		}
		$i++;
	}
	echo "</tr>";
}

function OutputRacePaceRowMPM($a, $startIndex, $endIndex)
{
	global $calcObj;

	echo "<tr>";
	echo "<td>Pace/mile</td>";
	$i = 0;
	foreach ($a as $k => $v)
	{
		if (($i >= $startIndex)	&& ($i < $endIndex))
		{
			$t = $calcObj->CalcEquivRaceTimeInMinutes($v[2]);
			$pace = $t / $v[1] * KM_PER_MILE; 
         if ($v[1] <= 10.1)
            $paceString = FormatTime($pace * 60.0, MAX_MINUTES);
         else
            $paceString = FormatTime($pace * 60.0);
			// $paceString = SecondsToHHMMSS($pace  * 60.0, $hh, $mm, $ss);
			echo "<td>$paceString</td>";
		}
		$i++;
	}
	echo "</tr>";
}

function OutputRacePaceRowKM($a, $startIndex, $endIndex)
{
	global $calcObj;

	echo "<tr>";
	echo "<td>Pace/km</td>";
	$i = 0;
	foreach ($a as $k => $v)
	{
		if (($i >= $startIndex)	&& ($i < $endIndex))
		{
			$t = $calcObj->CalcEquivRaceTimeInMinutes($v[2]);
			$pace = $t / $v[1]; 
         if ($v[1] <= 10.1)
            $paceString = FormatTime($pace * 60.0, MAX_MINUTES);
         else
            $paceString = FormatTime($pace * 60.0);
			// $paceString = SecondsToHHMMSS($pace  * 60.0, $hh, $mm, $ss);
			echo "<td>$paceString</td>";
		}
		$i++;
	}
	echo "</tr>";
}


echo "<td valign=\"top\">";
// echo "<table id=\"raceTimes\">";
echo "<table class=\"myTable\">";
echo "<caption>Equivalent Race Times - Metric Distances</caption>";
$a = $raceTimeFactorsMetric;
$start = 0;
$end = count($a)/2;
OutputRaceLabelRow($a, $start, $end);
OutputRaceTimeRow($a, $start, $end);
OutputRacePaceRowKM($a, $start, $end);
OutputRacePaceRowMPM($a, $start, $end);

$a = $raceTimeFactorsMetric;
$start = count($a)/2;
$end = count($a);
OutputRaceLabelRow($a, $start, $end);
OutputRaceTimeRow($a, $start, $end);
OutputRacePaceRowKM($a, $start, $end);
OutputRacePaceRowMPM($a, $start, $end);
echo "</table>";
echo "<br>";
echo "<td valign=\"top\">";
// echo "<table id=\"raceTimes\">";
echo "<table class=\"myTable\">";
echo "<caption>Equivalent Race Times - Imperial Distances</caption>";
$a = $raceTimeFactorsImperial;
$start = 0;
$end = count($a)/2;
OutputRaceLabelRow($a, $start, $end);
OutputRaceTimeRow($a, $start, $end);
OutputRacePaceRowMPM($a, $start, $end);
OutputRacePaceRowKM($a, $start, $end);
$a = $raceTimeFactorsImperial;
$start = count($a)/2;
$end = count($a);
OutputRaceLabelRow($a, $start, $end);
OutputRaceTimeRow($a, $start, $end);
OutputRacePaceRowMPM($a, $start, $end);
OutputRacePaceRowKM($a, $start, $end);
echo "</table>";

?>
