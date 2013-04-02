=== XML Google Maps ===
Contributors: dschuesae
Tags: google maps, google, maps, gpx, kml, kmz, tracks, routes, georss, flickr, nextgen gallery, geocode, geotag, gallery, exif, umapper, osm, open street map, open cycle map
Requires at least: 2.5
Tested up to: 3.2
Stable tag: 1.14.0
Donate link: http://www.matusz.ch/donate_xmlgooglemapswpplugin.htm

This plugin allows you to easily insert Google Map or Google Earth Plugin Maps into your blog. Supports KML, KMZ, GPX, geoRSS filelinks. Supports Default, Satellite, Hybrid, Physical, Google Earth Plugin, Open Street Map and Open Cycle Map Maptypes!

== Description ==
This plugin allows you to easily insert Google Map or Google Earth Plugin Maps into your blog. You just have to add a link to your self defined Map from My Google Maps, Picasa Webalbum Picture Map, any geoRSS Feed (like Flickr), your uploaded Google Earth file (kmz, kml) or any other dynamic or static Google Earth file (umapper.com, flickr.com, etc.).
 
Even if you have a GPX-File from your GPS, upload it, link it and it get displayed. For GPX-Tracks you can even display speed, elevation charts (Google Charts API) and a data table with checkpoints (distance, time, average speed, max speed, climb up, climb down).

If your NextGEN Gallery Images are already Geocoded (EXIF), this plugin shows your geocoded images on a map.
 
This plugin supports the Google Earth Plugin, Open Street Map and Open Cycle Map maptypes. 

Recently added:
- Support of Open Street Map and Open Cycle Map

It's easy - try it! Have a look at one of these two pages: 

<a href="http://www.matusz.ch/blog/projekte/xml-google-maps-wordpress-plugin/" target="_blank">Deutsche Plugin Homepage</a><br /><a href="http://www.matusz.ch/blog/projekte/xml-google-maps-wordpress-plugin-en/" target="_blank">English Plugin Homepage</a>


== Installation ==
<ul>
<li>Download the Plugin ZIP File</li>
<li>Upload the Folder xml-google-maps to your wordpress plugin folder</li>
<li>Activate the plugin</li>
<li>Create a Google Maps API Key <a href="http://www.google.com/apis/maps/signup.html" target="_blank">here</a></li>
<li>Enter the Google Key in the Option Pane</li>
<li>Set the size of your Google Map in the Option Pane</li>
<li>Developed a better algo for resolving the url to a local path (GPX and KML parsing).</li>
</ul>

For Updates:
<ul>
<li>Deactivate the plugin!</li>
<li>Continue with the installation steps!</li>
</ul>

== Frequently Asked Questions ==
<a href="http://www.matusz.ch/blog/projekte/xml-google-maps-wordpress-plugin/" target="_blank">Deutsche Plugin Homepage</a><br /><a href="http://www.matusz.ch/blog/projekte/xml-google-maps-wordpress-plugin-en/" target="_blank">English Plugin Homepage</a>

== Screenshots ==
1. Normal Map
2. KML map marker with image
3. Sample of GPX File with Elevation Graph 
4. Supports Google Bar in the bottom left Corner and Hierarchical Map Type Buttons on the top right corner
5. Use of checkpoints and the data table
6. Use of the speed profile chart
7. Use of Google Earth Plugin in Google Maps
8. Picasa Webalbum Integration
9. Flickr geoRSS Feed
10. NextGEN Gallery Integration

== Licence ==
This plugin is free for everyone! Since it's released under the GPL, you can use it free of charge on your personal or commercial blog. If you enjoy this plugin, you can thank me and leave a [small donation](http://www.matusz.ch/donate_xmlgooglemapswpplugin.htm "Donate with PayPal") for the time I've spent writing and supporting this plugin.

== Changes ==
Version 1.14.0
<ul>
<li>Should support Wordpress Multiuser Edition now</li>
<li>Replaced NextGen Gallery direct database access to NextGen Gallery API access</li>
<li>Removed another deprecated method call: has_cap</li>
</ul>

Version 1.13.4
<ul>
<li>Fixed a bug in filtering duplicate elevation values!</li>
</ul>

Version 1.13.3
<ul>
<li>Speed up elevation chart by filtering duplicate elevation values</li>
</ul>

Version 1.13.2
<ul>
<li>Decimal separator of PHP installation other than a decimal point, will work now too.</li>
</ul>

Version 1.13.1
<ul>
<li>Clears cache after every plugin update (strange beahviours after last update)</li>
</ul>

Version 1.13.0
<ul>
<li>Support Open Street Map and Open Cycle Map Maptypes</li>
<li>Depreciated add_option function replaced</li>
<li>HTML Validation should be correct now</li>
</ul>

Version 1.12.3
<ul>
<li>Supports picasa https URLs (thx to simo)</li>
<li>Limits for gpx parse mode with database won't work (thx to matthias)</li>
</ul>

Version 1.12.2
<ul>
<li>Replaced &apos; with &#39; - due to HTML 4 compatibility</li>
<li>Heart Rate chart settings won't be saved on settings page</li>
</ul>

Version 1.12.1
<ul>
<li>Plugin didn't work under PHP 4 - fixed! Please update your PHP installations anyway. PHP 4 isn't supported anymore.</li>
</ul>

Version 1.12
<ul>
<li>New GPX Parser included. This second parser can handle larger files than the first parser, but uses a lot of database space! You can choose between these two parsers!</li>
<li>Since plugin uses curl (if installed), gpx files will not work anymore in a password protected wordpress blog. You have to include user and password directly in the URL. Eg. http://testusr:testpwd@www.somedomain.ch/test.gpx - this plugin supports links with the '@'-sign now.</li>
<li>Database ID columns changed to bigint</li>
<li>Donation and settings link on all other plugins, will disappear!</li>
</ul>

Version 1.11
<ul>
<li>Database select commands for NGGallery uses now prepare statements.</li>
<li>Opacity won't work correct if decimal separator was a comma.</li>
<li>Added licence information!</li>
<li>MySQL database will now use the correct charset</li>
<li>Setting align didn't has any effect!</li>
<li>Removed end checkpoint if no track is available!</li>
<li>Added caching!</li>
<li>Removed some old Google Maps API functions!</li>
<li>Added classes and id to the div, iframe and img-tags generated with this plugin!</li>
<li>Added two new 3D Controls: GLargeMapControl3D and GSmallZoomControl3D</li>
<li>Added two new template variables for checkpoint table: Highest and deepest elevation!</li>
<li>Uses curl instead of fopen!</li>
<li>Plugin supports heartrate charts from GPX files!</li>
<li>Plugin supports full screen mode of map!</li>
</ul>

Version 1.10.2
<ul>
<li>Finally fixed bug 'Are you sure you want to do this?' that did not allow to save options on some wordpress installation. Thx to Pierre Burrin!</li>
</ul>

Version 1.10.1
<ul>
<li>Regular expression changes for Version 1.10 feature Linebreaks sometimes will not work (replaced whole text or part with the map). Fixed the regular expressions!</li>
</ul>

Version 1.10
<ul>
<li>Links with a Tilde-Sign (~) will work now!</li>
<li>Even GPX elevation and speed chart should now produce valid XHTML Transitional output<li>
<li>Images in Options Pane now produce valid XHTML Transitional output</li>
<li>Added some new variables to GPX Marker Template: Latitude, Longitude, Elevation</li>
<li>Added some new variables to NextGEN Gallery Marker Template: Latitude, Longitude</li>
<li>Added some new variables to Checkpoint Row Template: Latitude, Longitude, Elevation</li>
<li>Shows error message if PHP Installation does not support EXIF extension</li>
<li>Linebreaks are now supported in HTML-Tags</li>
</ul>

Version 1.9
<ul>
<li>Google Maps or Picasa links from UK will work too, now!</li>
<li>New parameter: kmlparsing (enabled/disabled). If this option is set to enabled, this plugin tries to parse the kml file for coordinates, set the center and zoomlevel to the perfect values. Parsing only works for KML links that ends with the file ending .kml, it won't work with the following notation [xmlgm {xyz.kml}]!</li>
<li>Supports templates for Marker text using GPX Waypoints or NextGEN Gallery Images</li>
<li>GPX Files throwed warnings when showing checkpoint data, this should not happen anymore!</li>
<li>NextGen Gallery Feature now supports Geotagged Images without GPSLongitudeRef or GPSLatitudeRef Exif data (iPhone images)</li>
<li>NextGen Gallery Description, AltText and Gallery Description will get entity decoded now!</li>
<li>New parameter: gpxdirectopenlink (disabled, click, dblclick) opens the URL in the Link Element of the specific Waypoint act as the given action</li>
</ul>

Version 1.8.1
<ul>
<li>Fixed error in calculating checkpoint data if two points are exactly the same (distance between two track points is 0).</li>
<li>New Checkpoint Table variables added: Movingtime, Movingspeed (without breaks)</li>
<li>Better rounding for Checkpoint Table Data (1.1 becomes 1.10)</li>
<li>Added nautic miles and knots as measurement types</li>
<li>Map Button Earth property on Plugin Settings (show / hide) page will work now!</li>
<li>Added error handling if adding a GGeoXML Overlay Object to a map fails!</li>
<li>Fixed some division by zero errors in Google Charts Helper Class!</li>
</ul> 

Version 1.8
<ul>
<li>Don't shows any warnings if KML File can't be opened for parsing!</li>
<li>NextGEN Gallery integration: Shows geocoded images from the NextGEN Gallery plugin directly on the map! (Parameter: ngg_gallery, ngg_picture --> multiuse of this parameter allowed)</li>
<li>Replaced GPX Parser with a new one: Now it's possible to set html-tags in the description of a waypoint</li>
<li>Checkpoint Templates can be changed now: didn't work till now!</li>
<li>New Checkpoint Table variables added: Maxspeed, Checkpointnumber, Climbup, Climbdown</li>
<li>Updated the default checkpoint templates</li>
<li>Checkpoint summary data can be added in the table template, too!</li>
<li>Tips added in the admin section</li>
</ul> 

Version 1.7.1
<ul>
<li>Tested up to Wordpress 2.6</li>
</ul> 

Version 1.7
<ul>
<li>Enumeration displayurlmode supports &#39;nomap&#39; for disabling the specified URL.</li>
<li>xmlgm supports now other links that are supported from the Google Maps API GeoXML Object - simply use it with [xmlgm {http://www.test.com/dynamic.php?output=kml} maptype=G_NORMAL_MAP]!</li>
</ul> 

Version 1.6
<ul>
<li>The XML Google Maps Plugin supports now even Picasa-KML links in your post to be converted into a google map!</li>
<li>Help for wrong My Google Maps link added. Now supports 'link to this page' to if a map has been saved (and the google earth link will be shown)!</li>
</ul>

Version 1.5
<ul>
<li>Added support to use the Google Earth Plugin in the Google Maps. New Parameter added: mapearth (show, hide) and extended value for parameter maptype with G_SATELLITE_3D_MAP</li>
</ul>

Version 1.4
<ul>
<li>Removed debug information from map (sometimes they get displayed - no idea why...)</li>
<li>Added Quick Navigation Links on Admin Page</li>
<li>Added Speed Chart to show the Speed Profile of a GPX Track (including new parameter: gpxspeedchartcolor, gpxspeedchart, gpxspeedchartlinewidth, gpxspeedchartheight)</li>
<li>Support of Chart Title for Elevation Chart and Speed Chart</li> 
<li>Improvement of displaying Charts and Auto-Scale</li>
<li>New Parameter: speedscale (level1 ms or fps / level2 kmh or mph)</li>
<li>Changed Parameter: gpxelevationmeasurement to measurement</li>
<li>New Parameter: gpxcheckpointinterval to set automatically checkpoints after x miles or kilometres (supported by checkpoint table, elevation charts and speed charts)</li>
<li>New Parameter: gpxcheckpointmarkercolor (red / green / yellow / blue) for setting the color of the checkpoint marker on the map</li>
<li>New Parameter: gpxcheckpointtable (show / hide) for showing a checkpoint data table</li>
<li>By setting the gpxcheckpointinterval-parameter checkpoints will be displayed automatically as markers on maps and as vertical lines on charts</li>
<li>Support of displaying a checkpoint table (distance, time, speed)</li>
</ul>

Version 1.3
<ul>
<li>Also supports a-tags with single quoted attributes now</li>
<li>New algorithm for GPX Polyline encoding</li>
<li>Shows XML Google Maps WP Plugin Banner on Map</li>
<li>Supports new Map Type Physical</li>
<li>New Parameters to change the MapType Control Buttons: mapnormal, maphybrid, mapsat, mapphysical (show, hide)</li>
<li>Support added for the new Hierarchical Map Type Control. Set parameter overviewmapcontrol to hierarchical</li>
<li>New parameters (enabled/disabled) for customize the map behaviour: dragging, googlebar, continuouszoom, doubleclickzoom, scrollwheelzoom</li>
</ul>

Version 1.2.4
<ul>
<li>GPX Elevation line color names didn't work (green, red, ...). The plugin now converts the known html color names into hex.</li>
</ul>

Version 1.2.3
<ul>
<li>Changed algorithm to get local path of a gpx file</li>
</ul>

Version 1.2.2
<ul>
<li>Changed admin pages for new Wordpress Admin Styles</li>
</ul>

Version 1.2.1
<ul>
<li>Tested for Wordpress 2.5</li>
</ul>

Version 1.2
<ul>
<li>The plugin does now validate for XHTML 1.0 Transitional (thx to fishtoes2000)</li>
<li>Link to a on My Google Maps created map now also works with domains other than google.com (examples: google.de, google.ch, ... - thx to marius).</li>
<li>Did not free xml parser and so did not accept more than one gpx file on the same page!</li>
<li>Solved problem with the apos sign in the gpx comment, description or name tag</li>
<li>New property 'CSS Style for GPX Waypoints' added in the settings section of the plugin!</li>
</ul>

Version 1.1
<ul>
<li>New Parameter: gpxelevationmeasurement: metric/imperial</li>
<li>Improvements in displaying the chart</li>
<li>Bug with missing description for GPX Waypoints in map fixed!</li>
<li>New Parameter: zoom to ovveride the default or calculated zoomlevel</li>
<li>New Parameter: deltazoom to add or subtract zoom level to calculated or default zoom level! (Only works for maps without parameter "zoom")</li>
<li>Set property frameborder=0 to iframe. IE now shows the iframe without any frame!</li>
<li>Changed required_once(<file>) to required_once(dirname(__FILE__).<file>)</li>
</ul>

Version 1.0
<ul>
<li>New feature: Reads elevation information from the gpx tracks and displays the data in a chart!</li>
<li>New Parameter: gpxelevation: show( only works for gpx tracks with elevation information), hide</li>
<li>New Parameter: gpxelevationcolor: line color of elevation chart</li>
<li>New Parameter: gpxelevationlinewidth: line width of elevation chart</li>
<li>New Parameter: gpxelevationheight: height of elevation chart</li>
<li>Bug with parameter value showvars=title fixed.</li>
<li>Bug with Google Maps URL with encoded ampersand '&' -> '&amp;' in it fixed (Wordpress replaces every '&' to '&amp;' during saving a post).</li>
</ul>

Version 0.8
<ul>
<li>Using Maps without Links possible with: [xmlgm &lt;parameters&gt;]</li>
</ul>

Version 0.7
<ul>
<li>KML Files loading problem (script duration) solved!</li>
<li>Marker from GPX Files won't show</li>
<li>New Parameters: width and height for single map dimensions</li>
<li>KML only correctly loaded with MapType G_MAP_TYPE, now delayed loading of other MapTypes!</li>
<li>Support for KMZ Files added!</li>
<li>New Parameter: panzoomcontrol for defining the left top pan/zoom control!</li>
<li>New Parameter: overviewmapcontrol for showing a small overview map of the region!</li>
<li>New Parameter: scalecontrol for showing a Scale Control!</li>
<li>New Parameter: maptypecontrol for showing the Map Type Selection Buttons!</li>
<li>New Parameter: align for aligning the IFrame</li>
</ul>

Version 0.6
<ul>
<li>Default values for parameters can be set on the Option Pane</li>
<li>The plugin now uses Encoded Polylines for faster loading and smaller files</li>
<li>Changed Google Maps API Version from 2.x to 2 (stable)</li>
</ul>

Version 0.5
<ul>
<li>Option Pane for Google Maps API Key and Dimension of the showing Google Maps</li>
<li>GPX and KML files don&#39;t need to be added to wp_check_filetypes anymore</li>
</ul>

Version 0.4
<ul>
<li>Internet Explorer didn&#39;t show Overlay&#39;s</li>
<li>Performance optimazation for Polylines with man points</li>
</ul>

Version 0.3
<ul>
<li>Use of Parameters: maptype, gpxinterval, gpxcolor, gpxlinewidth, gpxlineopacity, gpxview, maxlat, maxlon, minlat, minlon and urldisplaymode</li>
<li>"My Maps - KML"-Links also get converted</li>
<li>Georg&#39;s Solution for show.php</li>
<li>Tracks and Routes get displayed</li>
</ul>

Version 0.2
<ul>
<li>Some minor bugs</li>
</ul>