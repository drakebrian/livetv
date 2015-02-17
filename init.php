<?php

session_start();

$format = 'hdhomerun_config discover';
$command = escapeshellcmd($format);
$output = trim(shell_exec($command));

if (strpos($out,'no devices') !== false) {
	$_SESSION['deviceID'] = 'No HDHomeRun detected';
	$_SESSION['deviceIP'] = 'No HDHomeRun detected';
} else {
	//echo $output;
	$deviceID = substr($output, 17, 8);
	$deviceIP = substr($output, strpos($output, 'at') + 3);
	$_SESSION['deviceID'] = $deviceID;
	$_SESSION['deviceIP'] = $deviceIP;
}

?>