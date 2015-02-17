<!DOCTYPE html>
<?php
	session_start();

	require_once('init.php');

	$deviceIP = $_SESSION['deviceIP'];
	$deviceID = $_SESSION['deviceID'];
?>
<html>
<head>
	<title>Live TV</title>

	<link rel="stylesheet" type="text/css" href="css/style.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<div class="live-tv">
	<div class="above-tv">
		<span class="close">
			<img src="img/close-button.png" />
		</span>
	</div>
	<embed type="application/x-vlc-plugin" id="tv" name="live-tv" autoplay="yes" loop="no" width="80%" target="" allowfullscreen="true"/>

	<div class="now-playing">
		<h4 class="np"></h4>
		<h4 class="ci"></h4>
		<img class="current-icon" src="">
	</div>

</div>

<div class="hdhomerun-menu">
	<ul>
		<li>HDHomeRun <span>ID: <?php echo $deviceID ?></span></li>
		<li><?php echo $deviceIP ?><a href="http://<?php echo $deviceIP ?>" target="_blank"><span>Manage</span></a></li>
		<li id="refresh-tuner">Refresh Tuner Status</li>
		<!--<li>Restart HDHomeRun...</li>-->
	</ul>
</div>

<div class="nav">
	<div class="container">
		<h1>Live TV</h1>
		<h4>Tuner 0: <span id="tune0">Retrieving...</span> &nbsp; &nbsp; Tuner 1: <span id="tune1">Retrieving...</span></h4>
		<img src="img/hdhomerun.png" id="hdhomerun">
	</div>
</div>

<div class="container pad-top">

<?php

require_once('php/simple_html_dom.php');

//Listings!!
$html = file_get_html('http://192.168.0.254/mythweb/tv/list');
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

//Get Channels!!
$json = '/lineup.json';
$jsonFile = 'http://'.$hdhomerun.$json;
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

?>

<div class="stats">
	<h5>Listing info refreshed <span id="last-refresh">less than a minute ago.</span></h5>
</div>

</div>
<script type="text/javascript">

$(document).ready(function() {

	var count;
    var interval;

    $('.channel').mouseleave(function() {
    	var resetThis = $(this).children('.tv-guide').children('.allow-scroll');
    	window.setTimeout(function() {
    		$(resetThis).scrollTop(0);
    	}, 250);
    });



    $('.bottom-fade').on('mouseover', function() {
        var div = $(this).prev();

        interval = setInterval(function(){
            count = count || 1;
            var pos = div.scrollTop();
            div.scrollTop(pos + count);
        }, 10);
    }).click(function() {
        count < 6 && count++;
    }).on('mouseout', function() {
        // Uncomment this line if you want to reset the speed on out
        // count = 0;
        clearInterval(interval);
    });

    $('#hdhomerun').click(function() {
    	if ($('.hdhomerun-menu').is(':visible') == false) {
    		$('.hdhomerun-menu').slideDown('slow', function() {
    			//
    		});
    	} else {
    		$('.hdhomerun-menu').slideUp();
    	}
    });
    
    $('.hdhomerun-menu').click(function() {
    	$('.hdhomerun-menu').slideUp();
    });



    //Get tuner status
	function tunerStatus(tuner) {
		$.ajax({
	        type: "POST",
	        url: "tunerStatus.php",
	        data: {
	            'tuner': tuner
	        },
	        success: function(msg) {
	        	var stringBuild = '#tune' + tuner;
	        	$(stringBuild).html(msg);

	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown) {}
	    });
	}
	tunerStatus(0);
	tunerStatus(1);

	$('.channel').click(function() {
		var url = $(this).attr('data-url');
		$('#tv').attr('target', url);

		var thisId = $(this).attr('id');
		var playing = $(this).children('.tv-guide').children('.allow-scroll').children('h4').html();
		var channelInfo = $(this).find('.channel-info').html();
		var currentIcon = $(this).find('.channel-icon').attr('src');
		//Set plugin type Windows vs Mac
		var os = navigator.platform;
		if (os == 'MacIntel') {
			$('#tv').attr('type', 'application/x-chimera-plugin');
		} else {
			$('#tv').attr('type', 'application/x-vlc-plugin');
		}

		if ($('.hdhomerun-menu').is(':visible') == true) {
    		$('.hdhomerun-menu').slideUp();
    	} else {}

		$('.live-tv').attr('id', thisId);
		$('.now-playing').children('h4.np').html(playing);
		$('.now-playing').children('h4.ci').html(channelInfo);
		$('body').addClass('fixed');
		$('.current-icon').attr('src', currentIcon);

		$('.live-tv').fadeIn();

		tunerStatus(0);
		tunerStatus(1);
		
	});
	$('.close').click(function() {
		$('body').removeClass('fixed');
		$('.live-tv').fadeOut();
		tunerStatus(0);
		tunerStatus(1);

	});

	$('#refresh-tuner').click(function() {
		tunerStatus(0);
		tunerStatus(1);
    });

	refreshCounter = 0;

	setInterval(function() {
		refreshCounter ++;
		switch(refreshCounter) {
			case 1:
				$('#last-refresh').text('over a minute ago.');
			break;
			case 2:
				$('#last-refresh').text('a couple minutes ago.');
			break;
			case 3:
				$('#last-refresh').text('three minutes ago.');
			break;
			case 4:
				$('#last-refresh').text('four minutes ago.');
			break;
			case 5:
				$('#last-refresh').parent().html('Listing info refreshing... <span id="last-refresh"></span>');
				//$('#last-refresh').text('five minutes ago.');
			break;
		}
		tunerStatus(0);
		tunerStatus(1);
	}, 60000);

	setInterval(function() {
		$.ajax({
	        type: "POST",
	        url: "updateListings.php",
	        data: {
	            'listings': true
	        },
	        success: function(data) {
	        	var obj = jQuery.parseJSON(data);

	        	$('.channel').each(function() {
	        		var chan = $(this).attr('id');
	        		var updatedList = obj[chan];

	        		$(this).children('.tv-guide').children('.allow-scroll').children('h4').html(updatedList);
	        		refreshCounter = 0;

	        	});
	        	$('#last-refresh').parent().html('Listing info refreshed <span id="last-refresh">less than a minute ago.</span>');

	        	if ($('.live-tv').is(':visible') == true) {
	        		var currentTuned = $('.live-tv').attr('id');
	        		var updatedListing = obj[currentTuned];
	        		$('h4.np').html(updatedListing);
	        	} else {}

	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown) {}
	    });
	}, 300000);
	
});
	

</script>
</body>
</html>