<?php

require_once("utils.php");


// 
// Class to compute Race Equivalence
// 
// The excel file from Tinman was based on normalizing the input distances and 
// times to a 3KM race. To convert the distance to 3KM units is straight forward.
// However, the formula to convert the time to 3KM based is the following:
// H2=0.0000003711*(G2^4)-0.0000521117*(G2^3)+0.0028353624*(G2^2)+0.3376649722*(G2)-0.0371362206
// where G2 is the factor to convert the input distance to 3KM units. Then the adjusted
// time is H2 x the input time.
//


// ----------------------------------------------------------------------
class TreadmillConversions {
	
	// 
	// This is the main function to use. The other functions are sub-functions 
	// to help partition the work and align with Tinman's excel spreadsheet.
	//
	// Given the input speed in meters/min and the incline, this returns the equivalent
	// speed in metersPerMin.
	// 
	// Row26 (equivalent Land Running Paces in mm:ss)
	//
	// Use Row25 as the basis. Row 25 is the fractional pace. For example: a 6:30 pace would be 6.5
	// Take Row25 and convert from fractional time and convert to mm:ss
	// mm and ss are reference parameters and results are returned in them.
	//
	public function ComputePace($meterPerMin, $incline)
	{
		$fractionalPace = $this->TMRow25($meterPerMin, $incline);
		return($fractionalPace);
	}
	
	//
	// This is the cost of running at a certain speed up an incline. 
	// $incline is passed in as a percentage (e.g. 1% should be passed in as 0.01)
	//
	private function TMRow19($meterPerMin, $incline)
	{
   		$retVal = (($meterPerMin * 0.22) - 5.00) * ($incline * 4.5 + 1.00);
   		return($retVal);
	}
	
	//
	// Reduced O2 cost (mL) for stationary running
	//
	//
	private function TMRow22($meterPerMin)
	{
   		$retVal = 0.0000001135 * pow($meterPerMin, 3);
   		return($retVal);
	}
	
	//
	// O2 Cost (mL)
	//
	private function TMRow23($meterPerMin, $incline)
	{
   		$retVal = $this->TMRow19($meterPerMin, $incline) - $this->TMRow22($meterPerMin);
   		return($retVal);
	}

	//
	// Meters/Minute 
	//
	// Row 24 C24=(C23+5)/0.22), D24=C24, E24=D24, etc..
	//
	private function TMRow24($meterPerMin, $incline)
	{
		$incline = 0; // for this fcn, $incline is always 0
		$retVal = ($this->TMRow23($meterPerMin, $incline) + 5) / 0.22;
		return($retVal);
	}
	
	//
	// Row 25  C25=1609.344/C24, D25=$C$25/D28
	//
	public function TMRow25($meterPerMin, $incline)
	{
		// $retVal = METERS_PER_MILE / TMRow24($meterPerMin, $incline) / TMRow28($meterPerMin, $incline);
		$retVal = $this->TMRow24($meterPerMin, $incline) * $this->TMRow28($meterPerMin, $incline);
		return($retVal);
	}
	
	//
	// Row27
	//
	// C27=C23/$C$23, D27=D23/$C$23 (expect the value or C27 to be 1.00)
	//
	private function TMRow27($meterPerMin, $incline)
	{
		$retVal = $this->TMRow23($meterPerMin, $incline) / $this->TMRow23($meterPerMin, 0);
		return($retVal);
	}
		
	//
	// Row28 (vVO2Change)
	//
	// C28=-0.186985*(C27^2)+1.1372018*(C27)+0.0497833 (should be 1 for 0% incline) 
	// D28=-0.186985*(D27^2)+1.1372018*(D27)+0.0497833
	//
	private function TMRow28($meterPerMin, $incline)
	{
		$r27 = $this->TMRow27($meterPerMin, $incline);
	
		$retVal = -0.186985 * pow($r27, 2) + 1.1372018 * $r27 + 0.0497833;
		return($retVal);
	}
}




class HillConversions {
	
	// This is the main function to use out of this class to compute an equivalent pace given a hill's grade
	// 
	public function ComputePace($metersPerMin, $grade)
	{
		// excel formula; =1609.344/$C$9/1440/C16
		
		$adjPace = $metersPerMin * $this->vVO2Change($metersPerMin, $grade);
		return($adjPace);
	}
	
	public function TotalVO2Cost($metersPerMin, $grade)
	{
		// Excel Formula: =(0.0000001135*(C9^3)+0.19*(C9)+2.2)*(C11*4.5+1)
		// Where C9 refers to the speed in metersPerMin and 
		// C11 refers to the grade as a percentage
		
		$retVal = (0.0000001135 * pow($metersPerMin, 3) + 0.19 * $metersPerMin + 2.2) * ($grade * 4.5 + 1);
		return($retVal);
	}
	
	
	public function VO2Change($metersPerMin, $grade)
	{
		// Excel Formula: 	=C12/$C$12, =D12/$C$12
		// Where C12 is TotalVO2Cost()
		
		$retVal = $this->TotalVO2Cost($metersPerMin, $grade) / $this->TotalVO2Cost($metersPerMin, 0);
		return($retVal); 
	}
	
	public function vVO2Change($metersPerMin, $grade)
	{
		// Excel Formula: -0.186985*(D15^2)+1.1372018*(D15)+0.0497833
		// Where D15 is VO2Change value for same $grade
		
		$vo2 = $this->VO2Change($metersPerMin, $grade);
		$retVal = -0.186985 * pow($vo2, 2) + 1.1372018 * $vo2 + 0.0497833;
		return($retVal);
	}
}

?>
