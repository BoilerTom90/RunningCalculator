<?php

require_once("utils.php");
require_once("TreadmillFunctions.php");

echo "<p id=\"treadmillNote\">";
echo "Running on a treadmill at 0% incline is easier than running outside on a flat ";
echo "road or track. This is because you don't have to overcome air resistance when running ";
echo "on a treadmill. The chart below shows equivalent paces given the treadmill speed and treadmill incline.";
echo " Note that at most speeds you should have an incline between 1% and 1.5% for an ";
echo "equivalent effort running outside.";
echo "<br>";
echo "<br>";
echo "</p>";


// echo "<caption>Treadmill Incline Pace Chart</caption>";

function DisplayMPHTable()
{
   $obj = new TreadmillConversions();

   echo "<table class=\"myTable\">";
   $numRowsOutput = 0;
   for ($mph = 12.0; ($mph >= 5.0); $mph -= 0.1)
   {
	   // Output the Heading Infomration every 20 rows
      // if (($numRowsOutput % 20) == 0)
      if ($numRowsOutput == 0)
      {
		   echo "<tr>";
		   echo "<th rowspan=\"2\">Treadmill<br>Speed (mph)</th>";
		   echo "<th rowspan=\"2\">Pace<br>(mpm)</th>";
		   echo "<th colspan=\"16\">Equivalent Pace By Incline (Min/Mile)</th>";
		   echo "</tr>";

		   echo "<tr>";
		   for ($incline = 0; $incline <= 15; $incline++)
		   {
			   echo "<th>$incline%</th>";
		   }
		   echo "</tr>";
	   }

	   echo "<tr>";
	   $mpm = MPHtoMPM($mph, $mm, $ss);
	   echo "<td>$mph</td><td>$mpm</td>";

	   for ($incline = 0; $incline <= 15; $incline++)
	   {
		   $metersPerMin = MinPerMileToMetersPerMinute(0, $mm, $ss);
		   $adjPace = $obj->ComputePace($metersPerMin , (float)($incline/100.0));
		   MetersPerMinToMinPerMile($adjPace, $tmm, $tss);
		   $paceString = sprintf("%d:%02d", $tmm, $tss);
		   echo "<td>$paceString</td>";
	   }
	   echo "</tr>";
      $numRowsOutput++;
   }
   echo "</table>";
}


function DisplayMPKMTable()
{
   $obj = new TreadmillConversions();

   echo "<table class=\"myTable\">";
   $numRowsOutput = 0;
   for ($kmphr = 19.5; ($kmphr >= 8.0); $kmphr -= 0.1)
   {
	   // Output the Heading Infomration every 20 rows
      // if (($numRowsOutput % 20) == 0)
      if ($numRowsOutput == 0)
      {
		   echo "<tr>";
		   echo "<th rowspan=\"2\">Treadmill<br>Speed (km/hr)</th>";
		   echo "<th rowspan=\"2\">Pace<br>(min/km)</th>";
		   echo "<th colspan=\"16\">Equivalent Pace By Incline (Min/KM)</th>";
		   echo "</tr>";

		   echo "<tr>";
		   for ($incline = 0; $incline <= 15; $incline++)
		   {
			   echo "<th>$incline%</th>";
		   }
		   echo "</tr>";
	   }

	   echo "<tr>";
	   // $minpkm = 1.0 / ($kmphr / 60.0);
      $minpkm = KMPerHourToMinPerKM($kmphr, &$mm, &$ss);
	   echo "<td>$kmphr</td><td>$minpkm</td>";

	   for ($incline = 0; $incline <= 15; $incline++)
	   {
         // Convert from KMPerHour to MetersPerMin
		   $metersPerMin = $kmphr / 60.0 * 1000.0;
         
         // get adjusted pace (in metersPerMin)
		   $adjPace = $obj->ComputePace($metersPerMin , (float)($incline/100.0));
         
         // Convert adjusted pace (in MetersPerMin to minPerKM)
         MetersPerMinToMinPerKM($adjPace, $tmm, $tss);
		   $paceString = sprintf("%d:%02d", $tmm, $tss);
		   echo "<td>$paceString</td>";
	   }
	   echo "</tr>";
      $numRowsOutput++;
   }
   echo "</table>";
}
echo "<p><a href=\"#KMTable\">Jump to Metric Table</a>";
echo "<a name=\"MITable\" id=\"MITable\"></a>";
echo "<br><br>";
DisplayMPHTable();
echo "<br><br>";

echo "<a href=\"#MITable\">Top</a>";
echo "<br>";
echo "<a name=\"KMTable\" id=\"KMTable\"></a>";
echo "<br>";
DisplayMPKMTable();
echo "<br>";
echo "<a href=\"#MITable\">Top</a>";
echo "<br>";

?>
