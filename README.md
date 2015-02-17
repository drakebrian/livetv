# livetv
View live OTA tv via HDHomeRun in browser, works in Chrome/FF on Windows, Mac and Linux.

# Requirements
* HDHomeRun Device - developed using HDHomeRun PLUS (HDTC-2US), requires transcoding
* _libhdhomerun_ - available from [Silicon Dust](http://www.silicondust.com/support/downloads/)
* *AMP server - Apache and PHP are required, MySQL is required for MythTV
* MythTV, MythWeb (_optional_) - Required for listing info (MythTV Backend + MythWeb minimum)
* VLC Plugin - included with [VLC](http://videolan.org) on Windows and Linux, no current Mac version
* WebChimera (_Mac only_) - VLC based plugin for Mac via [RSATom](https://github.com/RSATom/WebChimera)

# How It Works

Using PHP, it decodes the HDHomeRun's lineup.JSON file to return the channel listings/stream links and generate the channels UI. AJAX calls via jQuery return the device status (showing tuners available/in use), and get current listing info from MythWeb's listing interface. Tuner status updates every minute and can be run from the from the dropdown menu. Listing info updates every 5 minutes. Both the tuner status and the listing info refresh when opening a channel.

Video streams are provided by the HDHomeRun and can be configured through it's web interface under 'Transcode Configuration'. Internet540 is best for Wifi devices, Heavy works fine on LAN. Video is not routed through the Apache server but direct from device to client for best performance.

# Project Status

Requires editing of default settings in several files (IP addresses for HDHomeRun and MythWeb). All channel network info is hardcoded at the moment, but included is a script to return network affliates from the FCC's open API. This and auto discovery are at the top of development priorities, as it will apply to a much larger audience.

# What's Next

* Auto discovery of HDHomeRun devices
* Return network affiliates from FCC database and populate channel icons accordingly
* Add fullscreen button to UI (VLC plugin has built-in)
* Grab detailed listing info for current playing show
* Update listing at end-of-show (currently every 5 minutes)
