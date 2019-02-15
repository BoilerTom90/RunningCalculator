<?php

require_once('utils.php');

class Calculator {

	var $distanceInKMM;  // populated in the constructor
	var $timeInMinutesM; // populated in the constructor
   var $genderM;
	
	var $racePaceFactor;     // Race Pace Factor

	var $factor3KvVO2;       // 3K Factor %vVO2 max
	
	function __construct($distanceInKM, $timeInMinutes, $gender = "M")
	{
		$this->distanceInKMM = $distanceInKM;
		$this->timeInMinutesM = $timeInMinutes;
      $this->genderM = $gender;
	}
	
	function GetTime()          { return( $timeInMinutesM ); }
	function GetDistanceMiles() { return( $distanceInKMM / KM_PER_MILE ); }
	function GetDistanceKM()    { return( $distanceInKMM ); }

	function DistRatioTo3KM()
	{
		// formula:
		// =0.0000003711*(F3^4)-0.0000521117*(F3^3)+0.0028353624*(F3^2)+0.3376649722*(F3)-0.0371362206
		// where F3 is the distance converted in KM
	   $rv = 0.0000003711 * pow($this->distanceInKMM, 4) -
			   0.0000521117 * pow($this->distanceInKMM, 3) +
			   0.0028353624 * pow($this->distanceInKMM, 2) +
			   0.3376649722 * $this->distanceInKMM         - 
			   0.0371362206;
		// echo "Time Ratio to 3K: $rv";
      // printf("DistRatioTo3KM: %f\n", $rv);
		return($rv);
	}
	
	function Equivalent3KTimeInMinutes()
	{
	   $rv = $this->timeInMinutesM / $this->DistRatioTo3KM();
      // printf("Equivalent3KTimeInMinutes: %f\n", $rv);
	   return($rv);
	}
	
	function PacePerKM()
	{
		$rv = $this->Equivalent3KTimeInMinutes() / 3.0;
		return($rv);
	}
	
	function RacePaceFactor()
	{
		//  =-0.0528490338*(LN(G3))+1.1028394713
		$rv = -0.0528490338 * log($this->Equivalent3KTimeInMinutes()) + 1.1028394713;
      // printf("RacePaceFactor: %f\n", $rv);
		return($rv);
	}
	
	function VO2MaxInMinPerKM()
	{
		$rv = $this->PacePerKM() * $this->RacePaceFactor();
		return($rv);
	}
	
	function VO2MaxMetersPerMin()
	{
		$rv = 1000.0 / $this->VO2MaxInMinPerKM();
      // printf("VO2MaxMetersPerMin: %f\n", $rv);
		return($rv);
	}
	
	function ReferenceMetersPerMin()
	{
	   $rv = $this->VO2MaxMetersPerMin();
	   // printf("Ref Meters Per Min: %f\n", $rv);
	   return($rv);
	}

   function PerformanceRating()
   {
      $t = $this->VO2MaxMetersPerMin();
      $vo2Cost = 0.0000001135 * pow($t,3) + 0.19 *($t) + 2.2;
      
      if ($this->genderM == "F")
         $rv = $vo2Cost * 1.133;
      else
         $rv = $vo2Cost;
      $rv = 0.01129 * $rv;
      return($rv);
   }


	//
	// The intensity should be passed in as a percentage. 
	//		150% => 1.5, 100% => 1.0 50% => 0.50%
	// The distanceUnitMeters should be a value like 1609, 1600, 1200, 1000... 100
	// 		Basically to compute the training pace for that distance in minutes per _unit_
   //       The "minutes" returned are in fractional minutes as a floating point number
   //
   
   /* Old Function
	function CalcTrainingPaceMPM(
				$intensity,			 // percentage: 
				$distanceUnitMeters
				) 
	{
      if ($intensity < 1.0) 
         $f = 0.8;
      else
         $f = 0.798;
      
      $vVO2Percent = pow($intensity, $f);
      // printf("Intensity: %f, vVO2: %1.7f\n", $intensity, $vVO2Percent);
      
      $tempMetersPerMin = $this->VO2MaxMetersPerMin() * $vVO2Percent;
      $t = $distanceUnitMeters / $tempMetersPerMin;
		return($t);
	}
   */
   
   function TrainingPaceInMinutes($intensity, $distanceKM)
   {
      $t = $this->VO2MaxInMinPerKM() * $distanceKM / $intensity;
      return($t);
   }
}

?>
