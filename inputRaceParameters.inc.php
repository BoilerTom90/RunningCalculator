<?php
require_once("utils.php");
// var_dump($_REQUEST); 
$distance = isset($_REQUEST['distance']) ? $_REQUEST['distance'] : DEFAULT_DISTANCE;
$timeHH = isset($_REQUEST['timeHH']) ? $_REQUEST['timeHH'] : DEFAULT_HOURS;
$timeMM = isset($_REQUEST['timeMM']) ? $_REQUEST['timeMM'] : DEFAULT_MINUTES;
$timeSS = isset($_REQUEST['timeSS']) ? $_REQUEST['timeSS'] : DEFAULT_SECONDS;
$timeT =  isset($_REQUEST['timeT']) ? $_REQUEST['timeT'] : DEFAULT_TENTHS;
$sex = isset($_REQUEST['sex']) ? $_REQUEST['sex'] : DEFAULT_GENDER;

?>

<script type="text/javascript">
   <!-- Form validation code will come here -->
   function validate()
   {
      // make sure the total time > 0. If not, it's not a 
      // valid submission.
      var timeHH = document.myForm.timeHH.value;
      var timeMM = document.myForm.timeMM.value;
      var timeSS = document.myForm.timeSS.value;
      var timeT  = document.myForm.timeT.value;
      var totalTime = timeHH + timeMM + timeSS + timeT;
      if (totalTime <= 0)
      {
         alert( "A non-zero race time must be entered!" );
         document.myForm.timeMM.focus();
         return(false);
      }
      return true;
   }
</script>

<form name="myForm"
      class="raceParams" 
      action="index.php" 
      onsubmit="return(validate());"
      method="POST">
   <fieldset>
   <legend>Input Race Time</legend>
   <ul>
   <li>
      <label for="distance">Distance</label>
   </li>
   <li>
		<?php
		//echo "<input type=\"text\" name=\"distance\" maxlength=\"6\" size=\"6\" value=$distance";
		//echo "	required pattern=\"[0-9\.]{1,6}\" placeholder=\"distance\">";
		echo "<select name=\"distance\" required>";
		foreach ($distanceChoiceArray as $k => $v)
		{
			if ($v[0] == "disabled") {
				echo "<option disabled>$v[1]</option>";
			}
			else{
				if ($v[0] == $distance) // see if we need to select this option
					echo "<option value=\"$v[0]\" selected>$v[1]</option>";
				else
					echo "<option value=\"$v[0]\" >$v[1]</option>";
			}
		}
		echo "</select>";
		?>
   </li>
   <li>
      <label for="time">Time <small>(hh:mm:ss.t)</small></label>
   </li>
   <li>
		<?php
		$pat = '[0-9]*';
		echo "<input type=\"text\" name=\"timeHH\" id=\"time\" maxlength=\"2\" size=\"2\" ";
      echo "	value=\"$timeHH\" pattern=\"$pat\" placeholder=\"hh\">";
		echo "<input type=\"text\" name=\"timeMM\" id=\"time\" maxlength=\"2\" size=\"2\" ";
      echo "  value=$timeMM required pattern=\"[0-5]{0,1}[0-9]\" placeholder=\"mm\">";
		echo "<input type=\"text\" name=\"timeSS\" id=\"time\" maxlength=\"2\" size=\"2\""; 
      echo "  value=$timeSS required pattern=\"[0-5]{0,1}[0-9]\" placeholder=\"ss\">";
		echo "<input type=\"text\" name=\"timeT\" id=\"time\" maxlength=\"1\" size=\"1\""; 
      echo "  value=$timeT required pattern=\"[0-9]\" placeholder=\"t\">";
		?>
   </li>
   <li>
      <label for="sex">Sex <small>(for Rating value)</small></label>
   </li>
   <li>
      <?php
      if ($sex == "M") {
         echo "<input type=\"radio\" id=\"sexMale\" name=\"sex\" value=\"M\" checked=\"checked\">";
         echo "   <label for=\"sexMale\">Male</label>";
         echo "<input type=\"radio\" name=\"sex\" value=\"F\">";
         echo "   <label for=\"sexFemale\">Female</label>";
      }
      else
      {
         echo "<input type=\"radio\" id=\"sexMale\" name=\"sex\" value=\"M\">";
         echo "   <label for=\"sexMale\">Male</label>";
         echo "<input type=\"radio\" name=\"sex\" value=\"F\" checked=\"checked\">";
         echo "   <label for=\"sexFemale\">Female</label>";
      }
      
      ?>
      
      <!--
		<?php
		echo "<select name=\"sex\">";
		if ($gender == "M") {
			echo "<option value=\"M\" selected=\"selected\">Male";
            echo "<option value=\"F\"                      >Female";
		}
		else {
			echo "<option value=\"M\"                      >Male";
            echo "<option value=\"F\" selected=\"selected\">Female";
		}
		echo "</select>";
		?>
      -->
   </li>
   </ul>
   <input type="hidden" name="compute" value="compute">
   <hr>
   <input type="submit" id="calcButton" value="Calculate">
   </fieldset>
</form>