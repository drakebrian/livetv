<?php

if (isset($_POST['tuner'])) {

	$tuner = $_POST['tuner'];
	$hdhrID = '1050BEFA';

	$format = 'hdhomerun_config '.$hdhrID.' get /tuner'.$tuner.'/streaminfo';
	$command = escapeshellcmd($format);
	$output = trim(shell_exec($command));

	if ($output == 'none') {
		echo 'Available';
	} else {
		//$len = strlen($output);
		preg_match_all('/:/', $output, $matches, PREG_OFFSET_CAPTURE);  
		$len = $matches[0][1][1] - 4;
		$channel = trim(substr($output, 3, $len));

		if (($channel == '0') || ($channel == '0 (no data)')) {
			echo 'Available';
		} else {
			echo $channel;
		}

		
	}
	


} else {
	echo 'No direct access.';
}


?>