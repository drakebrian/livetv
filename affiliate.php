<?php

//if (isset($_POST['callsign'])) {

	$callsign = 'WUHF'; //$_POST['callsign'];
	$queryURL = 'http://data.fcc.gov/mediabureau/v01/tv/facility/search/';
	$fileType = 'json';
	$file = $queryURL.$callsign.'.'.$fileType;

	$imgFolder = 'img/affiliates/';
	$imgType = 'png';
	$unknown = 'nomatch';

	$decode = file_get_contents($file);
	$station = json_decode($decode, true);

	$resultArray = array();

	foreach($station['results'] as $stationData) {

	  $call = $stationData[0]['facilityList'][0]['callSign'];
	  $network = $stationData[0]['facilityList'][0]['networkAfil'];
	  $fccId = $stationData[0]['facilityList'][0]['id'];

	  $rfChan = $stationData[0]['facilityList'][0]['rfChannel'];
	  $vChan = $stationData[0]['facilityList'][0]['virtualChannel'];

	  $city = $stationData[0]['facilityList'][0]['communityCity'];
	  $state = $stationData[0]['facilityList'][0]['communityState'];

	  

	  //Check if Affiliate Img is available
	  	if (file_exists($affilImg)) {
			$affilImg = $imgFolder.$network.'.'.$imgType;
		} else {
		    $affilImg = $imgFolder.$unknown.'.'.$imgType;
		}


	  $resultArray = array($call, $network, $fccId, $rfChan, $vChan, $city, $state, $affilImg);

	}

	//return $resultArray;
	print_r($resultArray);

//} else {}

?>