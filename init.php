<?php

session_start();

$mythInstalled = $_SESSION['mythInstalled'];
$mythWeb = $_SESSION['mythWeb'];
$deviceID = $_SESSION['deviceID'];
$deviceIP = $_SESSION['deviceIP'];

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


//Find affiliates for stations
function getAffiliate($callsign) {

	//$callsign = 'WROC'; //$_POST['callsign'];
	$queryURL = 'http://data.fcc.gov/mediabureau/v01/tv/facility/search/';
	$fileType = 'json';
	$file = $queryURL.$callsign.'.'.$fileType;

	$imgFolder = 'img/networks/';
	$imgType = 'png';
	$unknown = 'nomatch';

	$decode = file_get_contents($file);
	$station = json_decode($decode, true);

	$resultArray = array();

	foreach($station['results'] as $stationData) {

	  $call = $stationData[0]['facilityList'][0]['callSign'];
	  $network = $stationData[0]['facilityList'][0]['networkAfil'];
	  $networkLower = strtolower($stationData[0]['facilityList'][0]['networkAfil']);
	  $fccId = $stationData[0]['facilityList'][0]['id'];

	  $rfChan = $stationData[0]['facilityList'][0]['rfChannel'];
	  $vChan = $stationData[0]['facilityList'][0]['virtualChannel'];

	  $city = $stationData[0]['facilityList'][0]['communityCity'];
	  $state = $stationData[0]['facilityList'][0]['communityState'];

	  
	  $affilImg = $imgFolder.$networkLower.'.'.$imgType;
	  //Check if Affiliate Img is available
	  	if (file_exists($affilImg)) {
			//use that img
		} else {
		    $affilImg = $imgFolder.$unknown.'.'.$imgType;
		}


	  $resultArray = array($call, $network, $fccId, $rfChan, $vChan, $city, $state, $affilImg);

	  return $resultArray;
	} 

}

if ($mythInstalled == true) {
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
} else {}

/*

//Get Channels!!
$json = '/lineup.json';
$jsonFile = 'http://'.$deviceIP.$json;
$decode = file_get_contents($jsonFile);

$channels = json_decode($decode, true);

$len = count($channels);

for ($i=0;$i<$len;$i++) {
	 $channelData = (array) $channels[$i];

	 $channelNum = $channelData['GuideNumber'];
	 $channelName = $channelData['GuideName'];
	 $channelUrl = $channelData['URL'];
	 $channelFav = $channelData['Favorite'];

	foreach ($allListings as $key => $value) {
	    if ($key == $channelNum) {
	    	if (strpos($value, 'HD') !== false) {
	    		$listTemp = str_replace('HD', '', $value);
	    		$hd = true;
	    	} else {
	    		$listTemp = $value;
	    		$hd = false;
	    	}
	    	
	        $list = $listTemp;

	    }
	}



	 $top = '#888';
	 $bottom = '#555';

	 switch ($channelNum) {
	 	case '8.1':
	 		$img = 'img/networks/cbs.png';
	 		$top = '#ededed';
	 		$bottom = '#e4e4e4';
	 	break;
	 	case '8.2':
	 		$img = 'img/networks/bounce.png';
	 		$top = '#323232';
	 		$bottom = '#060606';
	 	break;
	 	case '10.1':
	 		$img = 'img/networks/nbc.png';
	 		$top = '#555555';
	 		$bottom = '#333333';
	 	break;
	 	case '10.2':
	 		$img = 'img/networks/me-tv.png';
	 		$top = '#023541';
	 		$bottom = '#001a21';
	 	break;
	 	case '10.3':
	 		$img = 'img/networks/antenna-tv.png';
	 		$top = '#453f35';
	 		$bottom = '#302b24';
	 	break;
	 	case '13.1':
	 		$img = 'img/networks/abc.png';
	 		$top = '#ffffff';
	 		$bottom = '#f2f2f2';
	 	break;
	 	case '13.2':
	 		$img = 'img/networks/cw.png';
	 		$top = '#404040';
	 		$bottom = '#292929';
	 	break;
	 	case '13.3':
	 		$img = 'img/networks/grit.png';
	 		$top = '#2c3638';
	 		$bottom = '#1c2124';
	 	break;
	 	case '21.1':
	 		$img = 'img/networks/pbs.png';
	 		$top = '#224761';
	 		$bottom = '#182e3d';
	 	break;
	 	case '21.3':
	 		$img = 'img/networks/pbs-kids.png';
	 		$top = '#00c3f7';
	 		$bottom = '#0ab1dd';
	 	break;
	 	case '21.2':
	 		$img = 'img/networks/world.png';
	 		$top = '#fff';
	 		$bottom = '#eee';
	 	break;
	 	
	 	case '31.1':
	 		$img = 'img/networks/fox.png';
	 		$top = '#090909';
	 		$bottom = '#000';
	 	break;
	 	case '31.2':
	 		$img = 'img/networks/get-tv.png';
	 		$top = '#fff';
	 		$bottom = '#dadada';
	 	break;
	 	case '51.1':
	 		$img = 'img/networks/ion.png';
	 		$top = '#68bcfc';
	 		$bottom = '#1ca9f8';
	 	break;
	 	case '51.2':
	 		$img = 'img/networks/qubo.png';
	 		$top = '#a5d72a';
	 		$bottom = '#90bc25';
	 	break;
	 	case '51.3':
	 		$img = 'img/networks/ion-life.png';
	 		$top = '#659459';
	 		$bottom = '#212b23';
	 	break;

	 	default:
	 		$img = 'http://placehold.it/148x148';
	 }

	 if ($channelFav == 1) {
	 	echo '<div id="'.$channelNum.'" class="channel" data-url="'.$channelUrl.'" style="background: linear-gradient('.$top.', '.$bottom.')">';
	 	echo '<div class="tv-guide">';
	 		echo '<div class="allow-scroll">';
		 		echo '<h4>'.$list.'</h4>';
		 	echo '</div>';
	 	if ($hd == true) {
	 		echo '<div class="bottom-fade"><img class="hd" src="img/hd.png"></div>';
	 	} else {
	 		echo '<div class="bottom-fade"></div>';
	 	}
	 	echo '</div>';	
	 	echo '<img class="channel-icon" src="'.$img.'" />';
	 	echo '<div class="channel-info">';
	 	echo $channelNum.' &nbsp;'.$channelName;
	 	echo '</div></div>';
	 

	 } else {
	 	//Not a favorite, hide
	 }


}
*/








?>