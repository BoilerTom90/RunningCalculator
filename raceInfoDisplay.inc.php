<html>
<script>
	function DisplayHelp() {
		var $txt = "A performance rating of 100% is equal to the estimated natural limits of human performance for modern humans. An 11% difference between genders is part of the rating.";
		alert($txt);
   }
</script>
</html>


<?php

require_once("utils.php");
require_once("CalculatorFunctions.php");

/* echo var_dump($_REQUEST); */
$distance = isset($_REQUEST['distance']) ? $_REQUEST['distance'] : DEFAULT_DISTANCE;
$timeHH = isset($_REQUEST['timeHH']) ? $_REQUEST['timeHH'] : DEFAULT_HOURS;
$timeMM = isset($_REQUEST['timeMM']) ? $_REQUEST['timeMM'] : DEFAULT_MINUTES;
$timeSS = isset($_REQUEST['timeSS']) ? $_REQUEST['timeSS'] : DEFAULT_SECONDS;
$timeT  = isset($_REQUEST['timeT'])  ? $_REQUEST['timeT'] :  DEFAULT_TENTHS;
$sex = isset($_REQUEST['sex']) ? $_REQUEST['sex'] : DEFAULT_GENDER;


/*
** Using the input values, need to compute the pace in Seconds Per Meter. Given that,
** we can easily compute the splits for the Mile, 1600m, ... 100m
*/
$minutes = HHMMSSToMinutes($timeHH, $timeMM, $timeSS, $timeT);
$displayDistance = DisplayableDistanceValue($distance);

$calcObj = new Calculator($distance, $minutes, $sex); // e.g. 5K in 15.50 minutes
$rating = sprintf("%1.1f%%", $calcObj->PerformanceRating() * 100);
$displayTime = SecondsToHHMMSST($minutes * 60.0, $hh, $mm, $ss, $tt);

/* echo "<table id=\"raceInfo\">"; */
echo "<table class=\"myTable\">";
echo "<caption>Race Information</caption>";
echo "	<tr>";
echo "		<th>Distance</th>";
echo "		<th>Time</th>";
echo "		<th>Sex</th>";
echo "		<th>Rating <button onclick=\"DisplayHelp()\">?</button> </th>";
echo "	</tr>";

echo "	<tr>";
echo "		<td>$displayDistance</td>";
echo "    	<td>$displayTime</td>";
echo "		<td>$sex</td>";
echo "		<td>$rating</td>";
echo "	</tr>";
echo "</table>";


?>