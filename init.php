<?php

session_start();

$mythWeb = $_SESSION['mythWeb'];

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

//Channel Listing Info
require_once('php/simple_html_dom.php');

$html = file_get_html($mythWeb.'/tv/list');
foreach($html->find('tr') as $row) {
    $item['channel'] = $row->find('td', 0)->plaintext;
    $item['listing'] = $row->find('td', 1)->plaintext;
    $item['upnext'] = $row->find('td', 2)->plaintext;
    $listings[] = $item;
}

$len = count($listings);

for ($i=0;$i<$len;$i++) {
	$listingData = (array) $listings[$i];

	$listingChan= $listingData['channel'];
	$listingName = $listingData['listing'];
	$listingNext = $listingData['upnext'];

	if (strpos($listingChan, '_') !== false) {

		$channelTemp = str_replace('_', '.', $listingChan);
		$channel = trim(substr($channelTemp, 0, strpos($channelTemp, ".") + 2));
		$rerunCheck = str_replace('(Repetir)', '</strong>(Rerun)<strong>', $listingName);
		$rerunCheckNext = str_replace('(Repetir)', '</strong>(Rerun)<strong>', $listingNext);

		if ($rerunCheckNext == '') {
			$next = '';
		} else {
			$next = 'Next: <strong>'.$rerunCheckNext.'</strong>';
		}

		$now = 'Now: <strong>'.$rerunCheck.'</strong>';
			
		$listing = $now.'<br/><br />'.$next;

		$allListings[$channel] = $listing;
	
	} else {
		
	}
}
?>