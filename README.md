# livetv
View live OTA tv via HDHomeRun in browser, works in Chrome/FF on Windows, Mac and Linux.

<img src="http://dev.counttozero.com/img/listingexample2.png" alt="Channels and listing example" />
<img src="http://dev.counttozero.com/img/viewingexample2.png" alt="Channel viewer example" />

# Requirements
* HDHomeRun Device - developed using HDHomeRun PLUS (HDTC-2US), requires transcoding
* _hdhomerun_config_ - available from [Silicon Dust](http://www.silicondust.com/support/downloads/), part of libhdhomerun
* *AMP server - Apache and PHP5 are required, MySQL is required for MythTV
* MythTV, MythWeb (_optional_) - Required for listing info (MythTV Backend + MythWeb minimum)
* VLC Plugin - included with [VLC](http://videolan.org) on Windows and Linux, no current Mac version
* WebChimera (_Mac only_) - VLC based plugin for Mac via [RSATom](https://github.com/RSATom/WebChimera)

# How It Works

Using PHP, it finds HDHomeRun devices on the local network, decodes the lineup.JSON file to return the channel listings/stream links and generate the channels UI. It is currently configured to only show channels marked as Favorites on the HDHomeRun interface, to allow you to control which channels are visible.

AJAX calls via jQuery return the device status (showing tuners available/in use), and get current listing info from MythWeb's listing interface. Tuner status updates every minute and can be run from the from the dropdown menu. Listing info updates every 5 minutes. Both the tuner status and the listing info refresh when opening a channel.

Video streams are provided by the HDHomeRun and can be configured through it's web interface under 'Transcode Configuration'. Internet540 is best for Wifi devices, Heavy works fine on LAN. Video is not routed through the Apache server but direct from device to client for best performance.

# Configuration

Clone directory to web server. Requires editing of MythWeb server location in the index.php file. 

### Channel guide listing 

Listing info is retrieved from [MythWeb's](http://www.mythtv.org/wiki/MythWeb) listing interface. If you don't have a MythWeb install to retrieve listing info, set the $mythInstalled variable in the _index.php_ to false. 

### Channel logos and colors

Currently working on a way to add custom logos and background colors to channels that are not auto-detected after running the results against the FCC's API.

# What's Next

* Allow override/custom channel icons & colors
* Retrieve tuner count from HDHomeRun (currently hardcoded in AJAX calls)
* Add fullscreen button to UI (VLC plugin has built-in)
* Grab detailed listing info for current playing show
* Update listing at end-of-show (currently every 5 minutes)
* Add more channel logos to project, optimize size of pngs

