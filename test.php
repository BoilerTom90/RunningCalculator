<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8'> 
	
	<!--
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
	<script src="menu.js"></script>
	-->
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
	<script type="text/javascript" src="rtd-tabs.js"></script>
	
	<link rel="stylesheet" type="text/css" href="myStyles.css" />
	<link rel="stylesheet" type="text/css" href="rtd-tabs.css" />
	
	<title>The Running Calculator</title>
	
</head>
<body>
	<header>
		<?php include("header.inc.php"); ?>
	</header>

</body>
	<section id="test">
	    <!-- <img src="background.jpg"> -->
		<ul id="tabs">
			<li><a href="#" title="tab1">Race Information</a></li>
    		<li><a href="#" title="tab2">Training Paces</a></li>
    		<li><a href="#" title="tab3">Race Times</a></li>
    		<li><a href="#" title="tab4">Treadmill Incline</a></li>
         <li><a href="#" title="tab5">Pace Band</a></li>
    		<li><a href="#" title="tab6">About</a></li>
		</ul>

		<div id="content"> 
    		<div id="tab1">
				<table>
				<tr>
					<td rowspan=2><?php include("inputRaceParameters.inc.php"); ?></td>
					<td><?php include("raceInfoDisplay.inc.php"); ?></td>
				</tr>
				<tr>
					<td><?php include("raceSplits.inc.php"); ?></td>
				</tr>
				</table>
				<br>
			</div>
			
    		<div id="tab2">
				<?php include("raceInfoDisplay.inc.php"); ?>
				<br>
				<?php include("trainingPaces.inc.php"); ?>
			</div>
			
    		<div id="tab3">
				<?php include("raceInfoDisplay.inc.php"); ?>
				<?php include("raceTimes.inc.php"); ?>
			</div>
			
    		<div id="tab4">
				<?php include("treadmill.inc.php"); ?>
			</div>

    		<div id="tab5">
				<?php include("paceBand.inc.php"); ?>
			</div>

    		<div id="tab6">
				<?php include("about.inc.php"); ?>
			</div>
		</div>

	</section>
</html>