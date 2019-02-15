<?php

require_once('utils.php');

class RaceTimes {

	var $distanceInKM;  // populated in the constructor
	var $timeInMinutes; // populated in the constructor
		
	function __construct($distanceInKM, $timeInMinutes)
	{
		$this->distanceInKM = $distanceInKM;
		$this->timeInMinutes = $timeInMinutes;
	}

   // 
   // The $factorFrom3K are fixed factors given to me by Tom. The caller/user
   // of this function has an array mapping the desired new distance to its equivalent
   // factor. It passes that factor into this function. Example factors are:
   //
   // 	array("800m",  0.8,  0.2290666101), 
   //    array("1000m", 1.0, 0.2950627999), 
	//    array("1200m", 1.2,  0.3628802967), 
   //    array("1500m", 1.5, 0.4672897197),
	//    array("1600m", 1.6,  0.5026411124), 
   //    array("2000m", 2.0, 0.6456728591),
	//    array("2400m", 2.4,  0.7892993389), 
   //    array("3000m", 3.0, 1.0000000000),   -- 1.0 since 3K is the "normalization" distance
	//    array("3200m", 3.2,  1.0707571179), 
   //    array("4km",	4.0, 1.3556493194),
   // 
   // what's passed to this function is the rightmost value (e.g. 0.2290666101)
   //
   
	public function CalcEquivRaceTimeInMinutes($factorFrom3K)
	{   
      // First, Convert the reference race distance into a ratio to 3KM:
     
      // We need to use a different formula for short races. Short being defined as 
      // anything less or equal to a 3k. Tom says this nly applies to equivalent 
      // race times, not to training paces. 
      $ratioTo3KM = 0;
      if ($this->distanceInKM <= 3.0)
      {
         // The formula below (received from Tom in early Feb 201, wants the distance in 
         // Meters, not KM, hence the multiplying by 1000
         $ratioTo3KM = 0.000000006871 * pow($this->distanceInKM*1000, 2) +
                       0.000324331753 * $this->distanceInKM*1000 -
                       0.034834259;
         // echo "Distance in KM: $this->distanceInKM <br>";
         // echo "Dist Ratio 3KM: $ratioTo3KM <br>";
      }
      else
      {
         // Longer races
         $ratioTo3KM = 0.0000003711 * pow($this->distanceInKM, 4) -
			            0.0000521117 * pow($this->distanceInKM, 3) +
			            0.0028353624 * pow($this->distanceInKM, 2) +
			            0.3376649722 * $this->distanceInKM         - 
			            0.0371362206;
      }
      
      // we have our distance ratio, now compute the time ratio.
      $equivTime3K = $this->timeInMinutes / $ratioTo3KM;
         
      // given the time ratio, apply the 3K factor that applies to the new race distance
      $retVal = $factorFrom3K * $equivTime3K;
      return($retVal);
   }
}

?>
