<?php

require_once("utils.php");

/* echo var_dump($_REQUEST); */
$distance = isset($_REQUEST['distance']) ? $_REQUEST['distance'] : DEFAULT_DISTANCE;
$timeHH = isset($_REQUEST['timeHH']) ? $_REQUEST['timeHH'] : DEFAULT_HOURS;
$timeMM = isset($_REQUEST['timeMM']) ? $_REQUEST['timeMM'] : DEFAULT_MINUTES;
$timeSS = isset($_REQUEST['timeSS']) ? $_REQUEST['timeSS'] : DEFAULT_SECONDS;
$timeT = isset($_REQUEST['timeT']) ? $_REQUEST['timeT'] : DEFAULT_TENTHS;



/*
** Using the input values, need to compute the pace in Seconds Per Meter. Given that,
** we can easily compute the splits for the Mile, 1600m, ... 100m
*/
$totalSeconds = HHMMSSToSeconds($timeHH, $timeMM, $timeSS, $timeT);
$secsPerKM = $totalSeconds / $distance;
$secsPerM = $secsPerKM / 1000;
$secsPerMile = $totalSeconds / ($distance * MILE_PER_KM);
$totalMiles = $distance * MILE_PER_KM;

// Display the Pace Band info for mile splits
function DisplayMileBasedSplits()
{
   global $secsPerMile;
   global $totalMiles;
   global $totalSeconds;
   global $distance;
   
   echo "<table class=\"myTable\">";
   echo "<th>Mile</th><th>Split</th>";
   
   $i = 1;
   while ($i < $totalMiles) // $totalMiles will never be a whole number
   {
      $secondsSplit = $i * $secsPerMile;
      $timeFormatted =  FormatTime($secondsSplit);
      echo "<tr><td>$i</td><td>$timeFormatted</td></tr>";
      $i++;
   }
   $i--;
   if ($i <= $totalMiles)
   {
      $timeFormatted = FormatTime($totalSeconds);
      $displayDistance = DisplayableDistanceValue($distance);
      $displayDistance = round($distance * MILE_PER_KM, 1);
      echo "<tr><td>$displayDistance</td><td>$timeFormatted</td></tr>";
   }
   echo "</table>";
}

// Display the Pace Band info for mile splits
function DisplayKMBasedSplits()
{
   global $secsPerKM;
   global $totalSeconds;
   global $distance; // in KM
   
   echo "<table class=\"myTable\">";
   echo "<th>KM</th><th>Split</th>";
   
   $i = 1;
   $loopEnd = floor($distance);
   while ($i <= $loopEnd) 
   {
      $secondsSplit = $i * $secsPerKM;
      $timeFormatted =  FormatTime($secondsSplit);
      echo "<tr><td>$i</td><td>$timeFormatted</td></tr>";
      $i++;
   }
   
   if ($loopEnd < $distance)
   {
      $timeFormatted = FormatTime($totalSeconds);
      $displayDistance = DisplayableDistanceValue($distance);
      $displayDistance = round($distance, 2);
      echo "<tr><td>$displayDistance</td><td>$timeFormatted</td></tr>";
   }
   echo "</table>";
}

function Display400mSplits()
{
   global $secsPerM;
   global $totalSeconds;
   global $distance; // in KM
      
   $totalMeters = $distance * 1000;
   
   echo "<table class=\"myTable\">";
   echo "<th>Distance</th><th>Split</th>";
   
   $i = 400;
   $loopEnd = floor($totalMeters);
   while ($i <= $loopEnd) 
   {
      $secondsSplit = $i * $secsPerM;
      $timeFormatted =  FormatTime($secondsSplit, 10*60);
      echo "<tr><td>$i</td><td>$timeFormatted</td></tr>";
      $i += 400;
   }
   
   //echo "i: $i, ";
   //echo "loopEnd: $loopEnd, ";
   //echo "totalMeters: $totalMeters";
   
   
   if (($i - $loopEnd) < 400)
   {
      $timeFormatted = FormatTime($totalSeconds);
      $displayDistance = DisplayableDistanceValue($distance);
      echo "<tr><td>$displayDistance</td><td>$timeFormatted</td></tr>";
   }
   echo "</table>";
}

// for any distance <= 3200, give the pace every 400m and 440yds
if ($distance <= 3.201)
{
   echo "<table>";
   echo "<tr><td>";
   Display400mSplits();
   echo "</td></tr>";
}
else 
{
   echo "<table>";
   echo "<tr>";
   echo "<td>";
   DisplayMileBasedSplits();
   echo "</td>";

   echo "<td>";
   DisplayKMBasedSplits();
   echo "</td>";
   echo "</table>";
}
?>