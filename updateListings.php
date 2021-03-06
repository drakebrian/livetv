<?php

if (isset($_POST['listings'])) {

	require_once('php/simple_html_dom.php');

	$mythWeb = $_SESSION['mythWeb'];

	//Listings!!
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
			$HDcheck = str_replace('HD', '', $rerunCheck);
			$HDcheckNext = str_replace('HD', '', $rerunCheckNext);

			if ($HDcheckNext == '') {
				$next = '';
			} else {
				$next = 'Next: <strong>'.$HDcheckNext.'</strong>';
			}

			$now = 'Now: <strong>'.$HDcheck.'</strong>';
			
			$listing = $now.'<br/><br />'.$next;

			$allListings[$channel] = $listing;

		} else {
			
		}
	}

	echo json_encode($allListings);
} else {echo 'Error returning listing';}
?>