<?php
/*
 * Diese Datei enthält die Options Page
 */
function xmlgm_options_page() {
	global $wpdb;
	
	xmlgooglemaps_helper::clearCacheAfterUpdate();
	
	// If we are a postback, store the options
	if ((isset($_GET['hidedonation']))) {
		update_option(key_xmlgm_hidedonation_version, $_GET['hidedonation']);
	}
	if ((isset($_GET['hideadwords']))) {
		update_option(key_xmlgm_hideadwords_version, $_GET['hideadwords']);
	}	
	if ((isset($_POST['info_update'])) && wp_verify_nonce($_POST['xmlgm-nonce-key'], 'xml-google-maps')) {
		// Google Maps API
		$xmlgm_uid = $_POST[key_xmlgm_uid];
		if ($xmlgm_uid == '')
		$xmlgm_uid = xmlgm_uid_default;
		update_option(key_xmlgm_uid, $xmlgm_uid);

		//Width
		$xmlgm_width = intval($_POST[key_xmlgm_width]);
		if ($xmlgm_width == 0)
		$xmlgm_width = xmlgm_width_default;
		update_option(key_xmlgm_width, $xmlgm_width);

		//Height
		$xmlgm_height = intval($_POST[key_xmlgm_height]);
		if ($xmlgm_height == 0)
		$xmlgm_height = xmlgm_height_default;
		update_option(key_xmlgm_height, $xmlgm_height);

		// IFrame Align
		$xmlgm_align = $_POST[key_xmlgm_align];
		if ($xmlgm_align == '')
		$xmlgm_align = xmlgm_align_default;
		update_option(key_xmlgm_align, $xmlgm_align);
		
		//Cache
		$xmlgm_cache = intval($_POST[key_xmlgm_cache]);
		update_option(key_xmlgm_cache, $xmlgm_cache);

		// Line Color
		$xmlgm_linecolor = xmlgooglemaps_helper::colorName2Hex($_POST[key_xmlgm_linecolor]);
		if ($xmlgm_linecolor == '')
		$xmlgm_linecolor = xmlgm_linecolor_default;
		update_option(key_xmlgm_linecolor, $xmlgm_linecolor);

		// Elevation Line Color
		$xmlgm_elelinecolor = xmlgooglemaps_helper::colorName2Hex($_POST[key_xmlgm_elevlinecolor]);
		if ($xmlgm_elelinecolor == '')
		$xmlgm_elelinecolor = xmlgm_elevlinecolor_default;
		update_option(key_xmlgm_elevlinecolor, $xmlgm_elelinecolor);

		// Elevation Chart Visibility
		$xmlgm_elevationchart = $_POST[key_xmlgm_elevationchart];
		if ($xmlgm_elevationchart == '')
		$xmlgm_elevationchart = xmlgm_elevationchart_default;
		update_option(key_xmlgm_elevationchart, $xmlgm_elevationchart);

		//Elevation Line Width
		$xmlgm_elelinewidth = intval($_POST[key_xmlgm_elelinewidth]);
		if ($xmlgm_elelinewidth == 0)
		$xmlgm_elelinewidth = xmlgm_elelinewidth_default;
		update_option(key_xmlgm_elelinewidth, $xmlgm_elelinewidth);

		// Speed Line Color
		$xmlgm_speedlinecolor = xmlgooglemaps_helper::colorName2Hex($_POST[key_xmlgm_speedlinecolor]);
		if ($xmlgm_speedlinecolor == '')
		$xmlgm_speedlinecolor = xmlgm_speedlinecolor_default;
		update_option(key_xmlgm_speedlinecolor, $xmlgm_speedlinecolor);

		// Speed Chart Visibility
		$xmlgm_speedchart = $_POST[key_xmlgm_speedchart];
		if ($xmlgm_speedchart == '')
		$xmlgm_speedchart = xmlgm_speedchart_default;
		update_option(key_xmlgm_speedchart, $xmlgm_speedchart);

		//Speed Line Width
		$xmlgm_speedlinewidth = intval($_POST[key_xmlgm_speedlinewidth]);
		if ($xmlgm_speedlinewidth == 0)
		$xmlgm_speedlinewidth = xmlgm_speedlinewidth_default;
		update_option(key_xmlgm_speedlinewidth, $xmlgm_speedlinewidth);
		
		// heartrate Line Color
		$xmlgm_heartratelinecolor = xmlgooglemaps_helper::colorName2Hex($_POST[key_xmlgm_heartratelinecolor]);
		if ($xmlgm_heartratelinecolor == '')
		$xmlgm_heartratelinecolor = xmlgm_heartratelinecolor_default;
		update_option(key_xmlgm_heartratelinecolor, $xmlgm_heartratelinecolor);

		// heartrate Chart Visibility
		$xmlgm_heartratechart = $_POST[key_xmlgm_heartratechart];
		if ($xmlgm_heartratechart == '')
			$xmlgm_heartratechart = xmlgm_heartratechart_default;
		update_option(key_xmlgm_heartratechart, $xmlgm_heartratechart);

		//heartrate Line Width
		$xmlgm_heartratelinewidth = intval($_POST[key_xmlgm_heartratelinewidth]);
		if ($xmlgm_heartratelinewidth == 0)
		$xmlgm_heartratelinewidth = xmlgm_heartratelinewidth_default;
		update_option(key_xmlgm_heartratelinewidth, $xmlgm_heartratelinewidth);

		// URL Display Mode
		$xmlgm_urldisplaymode = $_POST[key_xmlgm_urldisplaymode];
		if ($xmlgm_urldisplaymode == '')
		$xmlgm_urldisplaymode = xmlgm_urldisplaymode_default;
		update_option(key_xmlgm_urldisplaymode, $xmlgm_urldisplaymode);

		//Map View Style
		$xmlgm_viewstyle = $_POST[key_xmlgm_viewstyle];
		if ($xmlgm_viewstyle == '')
		$xmlgm_viewstyle = xmlgm_viewstyle_default;
		update_option(key_xmlgm_viewstyle, $xmlgm_viewstyle);

		//Line Width
		$xmlgm_linewidth = intval($_POST[key_xmlgm_linewidth]);
		if ($xmlgm_linewidth == 0)
		$xmlgm_linewidth = xmlgm_linewidth_default;
		update_option(key_xmlgm_linewidth, $xmlgm_linewidth);

		//Elevation Height
		$xmlgm_eleheight = intval($_POST[key_xmlgm_elevationheight]);
		if ($xmlgm_eleheight == 0)
		$xmlgm_eleheight = xmlgm_elevationheight_default;
		update_option(key_xmlgm_elevationheight, $xmlgm_eleheight);

		//Speed Height
		$xmlgm_speedheight = intval($_POST[key_xmlgm_speedheight]);
		if ($xmlgm_speedheight == 0)
		$xmlgm_speedheight = xmlgm_speedheight_default;
		update_option(key_xmlgm_speedheight, $xmlgm_speedheight);
		
		//Speed Height
		$xmlgm_heartrateheight = intval($_POST[key_xmlgm_heartrateheight]);
		if ($xmlgm_heartrateheight == 0)
		$xmlgm_heartrateheight = xmlgm_heartrateheight_default;
		update_option(key_xmlgm_heartrateheight, $xmlgm_heartrateheight);
		
		//Line Opacity
		$xmlgm_lineopacity = intval($_POST[key_xmlgm_lineopacity]);
		if (($xmlgm_lineopacity < 0) || ($xmlgm_lineopacity > 100))
			$xmlgm_lineopacity = xmlgm_lineopacity_default;
		update_option(key_xmlgm_lineopacity, $xmlgm_lineopacity);

		//GPX View Style
		$xmlgm_gpxview = $_POST[key_xmlgm_gpxview];
		if ($xmlgm_gpxview == '')
		$xmlgm_gpxview = xmlgm_gpxview_default;
		update_option(key_xmlgm_gpxview, $xmlgm_gpxview);
		
		//GPX Parse Mode
		$xmlgm_gpxparsemode = intval($_POST[key_xmlgm_gpxparsemode]);
		if (($xmlgm_gpxparsemode < 1) || ($xmlgm_gpxparsemode > 2))
			$xmlgm_gpxparsemode = xmlgm_gpxparsemode_default;
		if ($xmlgm_gpxparsemode != get_option(key_xmlgm_gpxparsemode)) 
			xmlgooglemaps_dbfunctions::gpxClearAll();
		update_option(key_xmlgm_gpxparsemode, $xmlgm_gpxparsemode);

		//GPX Parse Mode 2 Max Limit
		$xmlgm_gpxparsemode2maxlimit = intval($_POST[key_xmlgm_gpxparsemode2maxlimit]);
		if (($xmlgm_gpxparsemode2maxlimit < 1))
			$xmlgm_gpxparsemode2maxlimit = xmlgm_gpxparsemode2maxlimit_default;
		update_option(key_xmlgm_gpxparsemode2maxlimit, $xmlgm_gpxparsemode2maxlimit);	

		//Pan/Zoom Control
		$xmlgm_panzoomcontrol = $_POST[key_xmlgm_panzoomcontrol];
		if ($xmlgm_panzoomcontrol == '')
		$xmlgm_panzoomcontrol = xmlgm_panzoomcontrol_default;
		update_option(key_xmlgm_panzoomcontrol, $xmlgm_panzoomcontrol);

		//Overview Map Control
		$xmlgm_overviewmap = $_POST[key_xmlgm_overviewmap];
		if ($xmlgm_overviewmap == '')
		$xmlgm_overviewmap = xmlgm_overviewmap_default;
		update_option(key_xmlgm_overviewmap, $xmlgm_overviewmap);

		//Scale Control
		$xmlgm_scalecontrol = $_POST[key_xmlgm_scalecontrol];
		if ($xmlgm_scalecontrol == '')
		$xmlgm_scalecontrol = xmlgm_scalecontrol_default;
		update_option(key_xmlgm_scalecontrol, $xmlgm_scalecontrol);

		//Map Type Control
		$xmlgm_maptypecontrol = $_POST[key_xmlgm_maptypecontrol];
		if ($xmlgm_maptypecontrol == '')
		$xmlgm_maptypecontrol = xmlgm_maptypecontrol_default;
		update_option(key_xmlgm_maptypecontrol, $xmlgm_maptypecontrol);

		//Measurement Type
		$xmlgm_elevationmeasurement = $_POST[key_xmlgm_elevationmeasurement];
		if ($xmlgm_elevationmeasurement == '')
		$xmlgm_elevationmeasurement = xmlgm_elevationmeasurement_default;
		update_option(key_xmlgm_elevationmeasurement, $xmlgm_elevationmeasurement);

		//Speed Scale
		$xmlgm_speedscale = $_POST[key_xmlgm_speedscale];
		if ($xmlgm_speedscale == '')
		$xmlgm_speedscale = xmlgm_speedscale_default;
		update_option(key_xmlgm_speedscale, $xmlgm_speedscale);

		//GPX Waypoint CSS Style
		$xmlgm_gpxcssstyle = $_POST[key_xmlgm_gpxcssstyle];
		if ($xmlgm_gpxcssstyle == '')
		$xmlgm_gpxcssstyle = xmlgm_gpxcssstyle_default;
		update_option(key_xmlgm_gpxcssstyle, $xmlgm_gpxcssstyle);

		//GPX Direct Open Waypoint Link
		$xmlgm_gpxdirectopenwplink = $_POST[key_xmlgm_gpxdirectopenwplink];
		if ($xmlgm_gpxdirectopenwplink == '')
		$xmlgm_gpxdirectopenwplink = xmlgm_gpxdirectopenwplink_default;
		update_option(key_xmlgm_gpxdirectopenwplink, $xmlgm_gpxdirectopenwplink);
		
		//Map Button Normal
		$xmlgm_mapnorm = $_POST[key_xmlgm_mapnorm];
		if ($xmlgm_mapnorm == '')
		$xmlgm_mapnorm = xmlgm_mapnorm_default;
		update_option(key_xmlgm_mapnorm, $xmlgm_mapnorm);

		//Map Satellite Normal
		$xmlgm_mapsat = $_POST[key_xmlgm_mapsat];
		if ($xmlgm_mapsat == '')
		$xmlgm_mapsat = xmlgm_mapsat_default;
		update_option(key_xmlgm_mapsat, $xmlgm_mapsat);

		//Map Hybrid Normal
		$xmlgm_maphyb = $_POST[key_xmlgm_maphyb];
		if ($xmlgm_maphyb == '')
		$xmlgm_maphyb = xmlgm_maphyb_default;
		update_option(key_xmlgm_maphyb, $xmlgm_maphyb);

		//Map Physical Normal
		$xmlgm_mapphy = $_POST[key_xmlgm_mapphy];
		if ($xmlgm_mapphy == '')
		$xmlgm_mapphy = xmlgm_mapphy_default;
		update_option(key_xmlgm_mapphy, $xmlgm_mapphy);

		//Map Google Earth  Plugin
		$xmlgm_mapearth = $_POST[key_xmlgm_mapearth];
		if ($xmlgm_mapearth == '')
		$xmlgm_mapearth = xmlgm_mapearth_default;
		update_option(key_xmlgm_mapearth, $xmlgm_mapearth);

		//Map Open Street Map		
		$xmlgm_maposm = $_POST[key_xmlgm_maposm];
		if ($xmlgm_maposm == '')
		$xmlgm_maposm = xmlgm_maposm_default;
		update_option(key_xmlgm_maposm, $xmlgm_maposm);
		
		//Map Open Cycle map
		$xmlgm_mapocm = $_POST[key_xmlgm_mapocm];
		if ($xmlgm_mapocm == '')
		$xmlgm_mapocm = xmlgm_mapocm_default;
		update_option(key_xmlgm_mapocm, $xmlgm_mapocm);
		
		//Dragging
		$xmlgm_dragging = $_POST[key_xmlgm_dragging];
		if ($xmlgm_dragging == '')
		$xmlgm_dragging = xmlgm_dragging_default;
		update_option(key_xmlgm_dragging, $xmlgm_dragging);

		//Double Click Zoom
		$xmlgm_doubleclickzoom = $_POST[key_xmlgm_doubleClickZoom];
		if ($xmlgm_doubleclickzoom == '')
		$xmlgm_doubleclickzoom = xmlgm_doubleClickZoom_default;
		update_option(key_xmlgm_doubleClickZoom, $xmlgm_doubleclickzoom);

		//Continuous Zoom
		$xmlgm_continuouszoom = $_POST[key_xmlgm_continuousZoom];
		if ($xmlgm_continuouszoom == '')
		$xmlgm_continuouszoom = xmlgm_continuousZoom_default;
		update_option(key_xmlgm_continuousZoom, $xmlgm_continuouszoom);

		//Google Bar
		$xmlgm_googlebar = $_POST[key_xmlgm_googleBar];
		if ($xmlgm_googlebar == '')
		$xmlgm_googlebar = xmlgm_googleBar_default;
		update_option(key_xmlgm_googleBar, $xmlgm_googlebar);

		//Scroll Wheel Zoom
		$xmlgm_scrollwheelzoom = $_POST[key_xmlgm_scrollWheelZoom];
		if ($xmlgm_scrollwheelzoom == '')
		$xmlgm_scrollwheelzoom = xmlgm_scrollWheelZoom_default;
		update_option(key_xmlgm_scrollWheelZoom, $xmlgm_scrollwheelzoom);

		//Elevation Chart Title
		update_option(key_xmlgm_elevationtitle, $_POST[key_xmlgm_elevationtitle]);

		//Speed Chart Title
		update_option(key_xmlgm_speedcharttitle, $_POST[key_xmlgm_speedcharttitle]);

		//Heart Rate Chart Title
		update_option(key_xmlgm_heartratecharttitle, $_POST[key_xmlgm_heartratecharttitle]);
		
		//Checkpoint Marker Color
		$xmlgm_cpcol = $_POST[key_xmlgm_checkpointmapmarkercolor];
		if ($xmlgm_cpcol == '')
		$xmlgm_cpcol = xmlgm_checkpointmapmarkercolor_default;
		update_option(key_xmlgm_checkpointmapmarkercolor, $xmlgm_cpcol);

		//Checkpoint Table
		$xmlgm_cptable = $_POST[key_xmlgm_checkpointtable];
		if ($xmlgm_cptable == '')
		$xmlgm_cptable = xmlgm_checkpointtable_default;
		update_option(key_xmlgm_checkpointtable, $xmlgm_cptable);
		
		//Checkpoint Interval
		$xmlgm_cpint = floatval($_POST[key_xmlgm_checkpointinterval]);
		update_option(key_xmlgm_checkpointinterval, $xmlgm_cpint);

		//Checkpoint Table Template
		$xmlgm_cptabletempl = $_POST[key_xmlgm_checkpointtabletempl];
		if ($xmlgm_cptabletempl == '') {
			$filename = dirname(__FILE__)."/templates/defaultcptable.template";
			$handle = fopen ($filename, "r");
			$xmlgm_cptabletempl = fread ($handle, filesize ($filename));
			fclose ($handle);
		} else {
			$xmlgm_cptabletempl = str_replace('\"','"',$xmlgm_cptabletempl);
			$xmlgm_cptabletempl = str_replace("\'","'",$xmlgm_cptabletempl);
		}
		update_option(key_xmlgm_checkpointtabletempl, $xmlgm_cptabletempl);

		//Checkpoint Table Row Template
		$xmlgm_cptablerowtempl = $_POST[key_xmlgm_checkpointtablerowtempl];
		if ($xmlgm_cptablerowtempl == '') {
			$filename = dirname(__FILE__)."/templates/defaultcptablerow.template";
			$handle = fopen ($filename, "r");
			$xmlgm_cptablerowtempl = fread ($handle, filesize ($filename));
			fclose ($handle);
		} else {
			$xmlgm_cptablerowtempl = str_replace('\"','"',$xmlgm_cptablerowtempl);
			$xmlgm_cptablerowtempl = str_replace("\'","'",$xmlgm_cptablerowtempl);
		}
		update_option(key_xmlgm_checkpointtablerowtempl, $xmlgm_cptablerowtempl);
		
		//GPX Marker Template
		$xmlgm_gpxmartempl = $_POST[key_xmlgm_gpxmarkertempl];
		if ($xmlgm_gpxmartempl == '') {
			$filename = dirname(__FILE__)."/templates/defaultgpxmarker.template";
			$handle = fopen ($filename, "r");
			$xmlgm_gpxmartempl = fread ($handle, filesize ($filename));
			fclose ($handle);
		} else {
			$xmlgm_gpxmartempl = str_replace('\"','"',$xmlgm_gpxmartempl);
			$xmlgm_gpxmartempl = str_replace("\'","'",$xmlgm_gpxmartempl);
		}
		update_option(key_xmlgm_gpxmarkertempl, $xmlgm_gpxmartempl);

		//NGG Marker Template
		$xmlgm_nggmartempl = $_POST[key_xmlgm_nggmarkertempl];
		if ($xmlgm_nggmartempl == '') {
			$filename = dirname(__FILE__)."/templates/defaultnggmarker.template";
			$handle = fopen ($filename, "r");
			$xmlgm_nggmartempl = fread ($handle, filesize ($filename));
			fclose ($handle);
		} else {
			$xmlgm_nggmartempl = str_replace('\"','"',$xmlgm_nggmartempl);
			$xmlgm_nggmartempl = str_replace("\'","'",$xmlgm_nggmartempl);
		}
		update_option(key_xmlgm_nggmarkertempl, $xmlgm_nggmartempl);		
		
		//KML Parsing
		$xmlgm_kmlparsing = $_POST[key_xmlgm_kmlparsing];
		if ($xmlgm_kmlparsing == '')
			$xmlgm_kmlparsing = xmlgm_kmlparsing_default;
		update_option(key_xmlgm_kmlparsing, $xmlgm_kmlparsing);
		
		
		// Give an updated message
		xmlgooglemaps_dbfunctions::cacheClearAll();
		echo "<div class='updated'><p><strong>XML Google Maps Options updated and Cache cleared</strong></p></div>";
	} else {
		//Default Werte setzen
		if (strlen(get_option(key_xmlgm_checkpointtabletempl))==0) {
			$filename = dirname(__FILE__)."/templates/defaultcptable.template";
			if (file_exists($filename)) {
				$handle = fopen ($filename, "r");
				$tmp = fread ($handle, filesize ($filename));
				fclose ($handle);
				update_option(key_xmlgm_checkpointtabletempl, $tmp);
			}
		}
		if (strlen(get_option(key_xmlgm_checkpointtablerowtempl))==0) {
			$filename = dirname(__FILE__)."/templates/defaultcptablerow.template";
			if (file_exists($filename)) {
				$handle = fopen ($filename, "r");
				$tmp = fread ($handle, filesize ($filename));
				fclose ($handle);
				update_option(key_xmlgm_checkpointtablerowtempl, $tmp);
			}
		}
		if (strlen(get_option(key_xmlgm_gpxmarkertempl))==0) {
			$filename = dirname(__FILE__)."/templates/defaultgpxmarker.template";
			if (file_exists($filename)) {
				$handle = fopen ($filename, "r");
				$tmp = fread ($handle, filesize ($filename));
				fclose ($handle);
				update_option(key_xmlgm_gpxmarkertempl, $tmp);
			}
		}	
		if (strlen(get_option(key_xmlgm_nggmarkertempl))==0) {
			$filename = dirname(__FILE__)."/templates/defaultnggmarker.template";
			if (file_exists($filename)) {
				$handle = fopen ($filename, "r");
				$tmp = fread ($handle, filesize ($filename));
				fclose ($handle);
				update_option(key_xmlgm_nggmarkertempl, $tmp);
			}
		}				
	}
	if (get_option(key_xmlgm_hidedonation_version) == XML_GOOGLE_MAPS_VERSION) {
		$navdonation = "visibility: hidden; height: 0px;";
	} else {
		$navdonation = "visibility: visible; ";
	}
	$navigation .= '<td  rowspan="100%" width="15%" valign="top">';
	$navigation .= '<div style="background: #FFEBE8; border: 1px solid #CC0000; padding: 5px;">';
	$navigation .= '<div style="text-align: right;"><p><b>Navigation:</b><br /><a href="#basic">Basic Options</a><br /><a href="#control">Control Options</a><br /><a href="#maptype">Map Type Options</a><br /><a href="#mapbehaviour">Map Behaviour Options</a><br /><a href="#gpx">GPX Options</a><br /><a href="#elev">Elevation Chart Options</a><br /><a href="#speedchart">Speed Chart Options</a><br /><a href="#heartrate">Heart Rate Chart Options</a><br /><a href="#checkpoint">Checkpoint Options</a><br /><a href="#templ">Templates</a></p></div>';
	$navigation .= '<div style="text-align: right;"><input class="button" type="submit" name="info_update" value="Save Changes" /></div>';
	$navigation .= '<div style="text-align: right; '.$navdonation.'"><a href="http://www.matusz.ch/donate_xmlgooglemapswpplugin.htm" target="_blank"><img alt="Conate now!" src="'.get_option('siteurl').'/wp-content/plugins/xml-google-maps/images/donatenow.png" style="border: 0; padding-top: 10px;" /></a><br /><a href="http://www.matusz.ch/donate_xmlgooglemapswpplugin.htm" target="_blank">Donate now!</a> - <a href="options-general.php?page=xmlgooglemaps.php&hidedonation='.XML_GOOGLE_MAPS_VERSION.'">Hide this!</a></div>';
	$navigation .= '</div>';
	$navigation .= '</td>';
	//TIPPS
	$ngg = new xmlgooglemaps_nggFunctions();
	$tipps = array();
	$tipps[] = "What about a <a href='http://www.matusz.ch/donate_xmlgooglemapswpplugin.htm' target='_blank'>donation</a>! I spent a lot of time in developing this plugin. <a href='http://www.matusz.ch/donate_xmlgooglemapswpplugin.htm' target='_blank'>Donate now</a>!";
	$tipps[] = "What about a <a href='http://www.matusz.ch/donate_xmlgooglemapswpplugin.htm' target='_blank'>donation</a>! I spent a lot of time in developing this plugin. <a href='http://www.matusz.ch/donate_xmlgooglemapswpplugin.htm' target='_blank'>Donate now</a>!";	
	$tipps[] = "What about a <a href='http://www.matusz.ch/donate_xmlgooglemapswpplugin.htm' target='_blank'>donation</a>! I spent a lot of time in developing this plugin. <a href='http://www.matusz.ch/donate_xmlgooglemapswpplugin.htm' target='_blank'>Donate now</a>!";
	$tipps[] = "What about a <a href='http://www.matusz.ch/donate_xmlgooglemapswpplugin.htm' target='_blank'>donation</a>! I spent a lot of time in developing this plugin. <a href='http://www.matusz.ch/donate_xmlgooglemapswpplugin.htm' target='_blank'>Donate now</a>!";
	if ($ngg->isNggInstalled()) {
		$tipps[] = "This plugin reads the gps position information out of your <b>NextGEN Gallery</b> Plugin images. Use ngg_gallery=&lt;gallerid&gt; to show the whole geotagged Gallery on your map or use ngg_picture=&lt;picture id&gt; to show a single picture!";
		$tipps[] = "Did you know that the <b>NextGEN</b> Parameters can be used more than once to show multiple galleries or pictures? [xmlgml ngg_gallery=1; ngg_gallery=2] will show the first and second gallery images on the map!";
	}
	$tipps[] = "This plugin can handle <b><a href='http://www.umapper.com' target='_blank'>umapper.com</a></b> KML links. Try this [xmlgm {&lt;link to the umapper kml file&gt;}] or &lt;a href=&#39;http://www.umapper.com/download/maps/kml/xxx.kml&apos&gt;Testmap&lt;/a&gt;!";
	$tipps[] = "There are a lot of <b>samples</b> how to use this plugin. Have a look at the <a href='http://www.matusz.ch/blog/projekte/xml-google-maps-wordpress-plugin-en/samples/' target='_blank'>English</a> or <a href='http://www.matusz.ch/blog/projekte/xml-google-maps-wordpress-plugin/beispiele/' target='_blank'>German</a> Sample Page!";
	$tipps[] = "Did you know that a <b>GeoRSS</b> Feed can be converted into a map with this plugin? Try this [xmlgm {&lt;link to the georss feed&gt;}]!";
	$tipps[] = "Try the <b>Google Earth Browser Plugin</b> in the Google Maps API - this rocks! Set the Default Map Type &#39;Google Earth Plugin&#39;!";
	$tipps[] = "Have a look at the Checkpoint Table feature if you're using a GPX file. This feature will extract additional data out of your GPX tracks (Average Speed, Moving Speed, Max Speed, Climbing up, Climbing down, Distances, Moving Time, Time)!";	
	$tipps[] = "This plugin supports metric, imperial and nautical measurement types!";
	$tipps[] = "Did the your KML maps won't show at the correct location or without the right zoomlevel? Try to change the KML Parsing parameter!";
	$tipps[] = "Check the <a href='#templ'>templates</a> options section! Define the look and feel of your GPX or NextGen Gallery markers!";
	$tipps[] = "Did you ever doubleclicked on a GPX Waypoint Marker that has a Link Element? Yes, this plugin will directly open the specified URL in a new window! You want to open the URL by one click or disable this function, no problem: simply set the specific option or parameter to the specific value!";
	$tipps[] = "Enable caching now within this plugin!";
	$tipps[] = "To clear cache, simply click button &#39;Save Changes&#39; here on this page!";
	$tipps[] = "Your GPS records your heart rate? Export to GPX and show a heart rate profile!";
	$tipps[] = "DIV, IFRAME and IMG-Tags generated from this plugin have a CSS Class now!";
	$tipps[] = "Large GPX files? Out of memory exceptions? Change the GPX parse mode!";
	$tipps[] = "Out of memory exceptions? Large GPX files? Change the GPX parse mode!";
	$tipps[] = "Interested in showing your Map as an Open Street Map layer! Change Map Type!";
	$tipps[] = "Interested in showing your Map as an Open Cycle Map layer! Change Map Type!";
	if (count($tipps)>0) {
		$tip =	"<div class='updated' style='height: 70px;'><img alt='Information' src='".get_option('siteurl')."/wp-content/plugins/xml-google-maps/icons/tipp48.png' style='margin: 10px; float: left;' /><div style='margin: 15px;'>".$tipps[rand(0,count($tipps)-1)]."</div></div>";
	}
	// Output the options page
	?>
<script type="text/javascript">
		<!--
			var enabling = function() {
				if (document.forms['xmlgmform'].<?php echo key_xmlgm_viewstyle; ?>.value=="G_NORMAL_MAP") {
					document.forms['xmlgmform'].<?php echo key_xmlgm_mapnorm; ?>.value = "show";					
					document.forms['xmlgmform'].<?php echo key_xmlgm_mapnorm; ?>.options[0].disabled = true;
				} else {
				 	document.forms['xmlgmform'].<?php echo key_xmlgm_mapnorm; ?>.options[0].disabled = false;
				}
				if (document.forms['xmlgmform'].<?php echo key_xmlgm_viewstyle; ?>.value=="G_HYBRID_MAP") {
					document.forms['xmlgmform'].<?php echo key_xmlgm_maphyb; ?>.value = "show";
					document.forms['xmlgmform'].<?php echo key_xmlgm_maphyb; ?>.options[0].disabled = true;
				} else {
				 	document.forms['xmlgmform'].<?php echo key_xmlgm_maphyb; ?>.options[0].disabled = false;
				}
				if (document.forms['xmlgmform'].<?php echo key_xmlgm_viewstyle; ?>.value=="G_PHYSICAL_MAP") {
					document.forms['xmlgmform'].<?php echo key_xmlgm_mapphy; ?>.value = "show";
					document.forms['xmlgmform'].<?php echo key_xmlgm_mapphy; ?>.options[0].disabled = true;
				} else {
				 	document.forms['xmlgmform'].<?php echo key_xmlgm_mapphy; ?>.options[0].disabled = false;
				}
				if (document.forms['xmlgmform'].<?php echo key_xmlgm_viewstyle; ?>.value=="G_SATELLITE_3D_MAP") {
					document.forms['xmlgmform'].<?php echo key_xmlgm_mapearth; ?>.value = "show";
					document.forms['xmlgmform'].<?php echo key_xmlgm_mapearth; ?>.options[0].disabled = true;
				} else {
				 	document.forms['xmlgmform'].<?php echo key_xmlgm_mapearth; ?>.options[0].disabled = false;
				}	
				if (document.forms['xmlgmform'].<?php echo key_xmlgm_viewstyle; ?>.value=="USER_MAP_OSM") {
					document.forms['xmlgmform'].<?php echo key_xmlgm_maposm; ?>.value = "show";
					document.forms['xmlgmform'].<?php echo key_xmlgm_maposm; ?>.options[0].disabled = true;
				} else {
				 	document.forms['xmlgmform'].<?php echo key_xmlgm_maposm; ?>.options[0].disabled = false;
				}
				if (document.forms['xmlgmform'].<?php echo key_xmlgm_viewstyle; ?>.value=="USER_MAP_OCM") {
					document.forms['xmlgmform'].<?php echo key_xmlgm_mapocm; ?>.value = "show";
					document.forms['xmlgmform'].<?php echo key_xmlgm_mapocm; ?>.options[0].disabled = true;
				} else {
				 	document.forms['xmlgmform'].<?php echo key_xmlgm_mapocm; ?>.options[0].disabled = false;
				}													
				if (document.forms['xmlgmform'].<?php echo key_xmlgm_elevationmeasurement; ?>.value == "imperial") {
					document.forms['xmlgmform'].<?php echo key_xmlgm_speedscale; ?>.options[0].text = "Foot per second (level1)"							 
					document.forms['xmlgmform'].<?php echo key_xmlgm_speedscale; ?>.options[1].text = "Miles per hour (level2)"
				}
				if (document.forms['xmlgmform'].<?php echo key_xmlgm_elevationmeasurement; ?>.value == "metric") {
					document.forms['xmlgmform'].<?php echo key_xmlgm_speedscale; ?>.options[0].text = "Metre per second (level1)"							 
					document.forms['xmlgmform'].<?php echo key_xmlgm_speedscale; ?>.options[1].text = "Kilometre per hour (level2)"				
				}
				if (document.forms['xmlgmform'].<?php echo key_xmlgm_elevationmeasurement; ?>.value == "nautic_metric") {
					document.forms['xmlgmform'].<?php echo key_xmlgm_speedscale; ?>.options[0].text = "Knots (level1)"							 
					document.forms['xmlgmform'].<?php echo key_xmlgm_speedscale; ?>.options[1].text = "Knots (level2)"				
				}
				if (document.forms['xmlgmform'].<?php echo key_xmlgm_elevationmeasurement; ?>.value == "nautic_imperial") {
					document.forms['xmlgmform'].<?php echo key_xmlgm_speedscale; ?>.options[0].text = "Knots (level1)"							 
					document.forms['xmlgmform'].<?php echo key_xmlgm_speedscale; ?>.options[1].text = "Knots (level2)"				
				}				
				document.forms['xmlgmform'].cpmarker.src = "<?php echo get_option('siteurl'); ?>/wp-content/plugins/xml-google-maps/icons/mm_20_" + document.forms['xmlgmform'].<?php echo key_xmlgm_checkpointmapmarkercolor; ?>.value + ".png";
				document.forms['xmlgmform'].cpmarker_start.src = "<?php echo get_option('siteurl'); ?>/wp-content/plugins/xml-google-maps/icons/mm_20_" + document.forms['xmlgmform'].<?php echo key_xmlgm_checkpointmapmarkercolor; ?>.value + "_s.png";
				document.forms['xmlgmform'].cpmarker_end.src = "<?php echo get_option('siteurl'); ?>/wp-content/plugins/xml-google-maps/icons/mm_20_" + document.forms['xmlgmform'].<?php echo key_xmlgm_checkpointmapmarkercolor; ?>.value + "_e.png";
				//Validation of Checkpoint Table Parameter
				var tmp = parseFloat(document.forms['xmlgmform'].<?php echo key_xmlgm_checkpointinterval; ?>.value);
				if (tmp <= 0 || isNaN(tmp)) {
					document.forms['xmlgmform'].<?php echo key_xmlgm_checkpointtable; ?>.value = "hide";
					document.forms['xmlgmform'].<?php echo key_xmlgm_checkpointtable; ?>.disabled = true;
				} else {
					document.forms['xmlgmform'].<?php echo key_xmlgm_checkpointtable; ?>.disabled = false;
				}
			}
		//-->
		</script>
<form method="post" name="xmlgmform"
	action="options-general.php?page=xmlgooglemaps.php">
	<?php xmlgm_nonce_field(); ?>	
<div class="wrap">
<h2>XML Google Maps Options</h2>	
<?php echo $tip;
if (get_option(key_xmlgm_hideadwords_version) != XML_GOOGLE_MAPS_VERSION) { 
?>
<div class='updated' style='height: 108px; text-align: right; width: 728px;'>
	<script type="text/javascript"><!--
	google_ad_client = "ca-pub-5683339957987086";
	/* XML Google Maps WP Plugin Settings */
	google_ad_slot = "0981272603";
	google_ad_width = 728;
	google_ad_height = 90;
	//-->
	</script>
	<script type="text/javascript"
	src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
	</script>
	<a href="options-general.php?page=xmlgooglemaps.php&hideadwords=<?php echo XML_GOOGLE_MAPS_VERSION;?>">Hide this!</a>
</div>
<?php } ?>
<h3 style="clear: left;"><a name="basic"></a>Basic Options</h3>
<p>First of all you have to get your own Google API Key from the
following page <a href="http://code.google.com/apis/maps/signup.html"
	target="_blank">http://code.google.com/apis/maps/signup.html</a>. Then
specify the width, height and align of the google maps plugin in your
posts.</p>
<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_uid; ?>">Your Google Maps API Key:</label>
		</th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_uid."' ";
		echo "id='".key_xmlgm_uid."' ";
		echo "value='".get_option(key_xmlgm_uid)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter your Google Maps API Key in this
		box. You get your Google Maps API Key from <a
			href="http://code.google.com/apis/maps/signup.html" target="_blank">http://code.google.com/apis/maps/signup.html</a>.</p>
		</td>
		<?php echo $navigation; ?>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_cache; ?>">Cache:</label></th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_cache."' ";
		echo "id='".key_xmlgm_cache."' ";
		echo "value='".get_option(key_xmlgm_cache)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter the cache duration in minutes (-1 = disabled / 0 = infinite). Cache get cleared by clicking the &#39;Save Changes&#39; Button here on this page!</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_width; ?>">Width:</label></th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_width."' ";
		echo "id='".key_xmlgm_width."' ";
		echo "value='".get_option(key_xmlgm_width)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter the width of your Map on your posts
		or pages (only numeric value allowed)</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_height; ?>">Height:</label></th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_height."' ";
		echo "id='".key_xmlgm_height."' ";
		echo "value='".get_option(key_xmlgm_height)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter the height of your Map on your
		posts or pages (only numeric values allowed)</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_align; ?>">Google Map alignement:</label></th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_align."' ";
		echo "id='".key_xmlgm_align."'>";
		switch (get_option(key_xmlgm_align))
		{
			case "none": $selnone = "selected='selected'"; break;
			case "right": $selright = "selected='selected'"; break;
			case "center": $selcenter = "selected='selected'"; break;
			default: $selleft = "selected='selected'"; break;
		}
		echo "<option value='none' $selnone>None (none)</option>";
		echo "<option value='left' $selleft>Left (left)</option>";
		echo "<option value='center' $selcenter>Center (center)</option>";
		echo "<option value='right' $selright>Right (right)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Specifies the alignement of the map in
		your posts or pages. If settings this value to none, style attribute won't be used for charts and map div (use css to style theme)!</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_urldisplaymode; ?>">URL Display Mode:</label>
		</th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_urldisplaymode."' ";
		echo "id='".key_xmlgm_urldisplaymode."'>";
		switch (get_option(key_xmlgm_urldisplaymode))
		{
			case "none": $selnoneud = "selected='selected'"; break;
			case "showvars": $selvars = "selected='selected'"; break;
			case "nomap": $selnomap = "selected='selected'"; break;
			default: $seldefault = "selected='selected'"; break;
		}
		echo "<option value='title' $seldefault>Map, URL Title without Parameters (title)</option>";
		echo "<option value='none' $selnoneud>Map only (none)</option>";
		echo "<option value='showvars' $selvars>Map, URL Title and Parameters (showvars)</option>";
		echo "<option value='nomap' $selnomap>URL Title without Paramaters and without Map (nomap)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Specifies how to tranform the added Link.
		If you want to completely remove it then select &#39;Map only&#39;.
		If you want to remove only the parameters, then select &#39;Map, URL
		Title without Parameters&#39;. Orelse if you want to show the whole
		link as you typed it in then select &#39;Map, URL Title and
		Parameters&#39;. If you wan't to disable the plugin by default,
		select &#39;URL Title without Paramaters and without Map&#39;!</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_elevationmeasurement; ?>">Measurement Type:</label>
		</th>
		<td width="50%"><?php
		echo "<select onchange='enabling();' ";
		echo "name='".key_xmlgm_elevationmeasurement."' ";
		echo "id='".key_xmlgm_elevationmeasurement."'>";
		switch (get_option(key_xmlgm_elevationmeasurement))
		{
			case "imperial": $selelemimp = "selected='selected'"; break;
			case "nautic_metric": $selelemnaumet = "selected='selected'"; break;
			case "nautic_imperial": $selelemnauimp = "selected='selected'"; break;
			default: $selelemmet = "selected='selected'"; break;
		}
		echo "<option value='imperial' $selelemimp>Miles / Feet (imperial)</option>";
		echo "<option value='metric' $selelemmet>Kilometres / Metres (metric)</option>";
		echo "<option value='nautic_metric' $selelemnaumet>Nautical miles (Altitude: metres) (nautic_metric)</option>";
		echo "<option value='nautic_imperial' $selelemnauimp>Nautical miles (Altitude: feet) (nautic_imperial)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Measurement Type</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_speedscale; ?>">Speed Scale:</label></th>
		<td width="50%"><?php
		echo "<select onchange='enabling();' ";
		echo "name='".key_xmlgm_speedscale."' ";
		echo "id='".key_xmlgm_speedscale."'>";
		switch (get_option(key_xmlgm_speedscale))
		{
			case "level1": $selspeedl1 = "selected='selected'"; break;
			default: $selspeedl2 = "selected='selected'"; break;
		}
		echo "<option value='level1' $selspeedl1>Level 1 (level2)</option>";
		echo "<option value='level2' $selspeedl2>Level 2 (level1)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Speed Scale</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_kmlparsing; ?>">KML Parsing:</label></th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_kmlparsing."' ";
		echo "id='".key_xmlgm_kmlparsing."'>";
		switch (get_option(key_xmlgm_kmlparsing))
		{
			case "disabled": $selkmlpdis = "selected='selected'"; break;
			default: $selkmlpen = "selected='selected'"; break;
		}
		echo "<option value='enabled' $selkmlpen>Enabled (enabled)</option>";
		echo "<option value='disabled' $selkmlpdis>Diabled (disabled)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Enables/Disables parsing KML files. If this option is set to enabled, this plugin tries to parse the kml file for coordinates, set the center and zoomlevel to the perfect values. Parsing only works for KML links that ends with the file ending .kml, it won't work with the following notation [xmlgm {xyz.kml}]!</p>
		</td>
	</tr>	
</table>
<h3><a name="control"></a>Control Options</h3>
<p>Define the controls you want to display on your map</p>
<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_panzoomcontrol; ?>">Pan/Zoom Control:</label>
		</th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_panzoomcontrol."' ";
		echo "id='".key_xmlgm_panzoomcontrol."'>";
		switch (get_option(key_xmlgm_panzoomcontrol))
		{
			case "hide": $selnonepz = "selected='selected'"; break;
			case "small": $selsmall = "selected='selected'"; break;
			case "zoom": $selzoom = "selected='selected'"; break;
			case "zoom3d": $selzoom3d = "selected='selected'"; break;
			case "large": $sellarge = "selected='selected'"; break;
			default: $sellarge3d = "selected='selected'"; break;
		}
		echo "<option value='hide' $selnonepz>Invisible (hide)</option>";
		echo "<option value='large' $sellarge>Large (large)</option>";
		echo "<option value='large3d' $sellarge3d>Large 3D (large3d)</option>";
		echo "<option value='small' $selsmall>Small (small)</option>";
		echo "<option value='zoom' $selzoom>Zoom only (zoom)</option>";
		echo "<option value='zoom' $selzoom>Zoom 3D only (zoom3d)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Specifies the display style of the
		Pan/Zoom Control</p>
		</td>
		<?php echo $navigation; ?>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_overviewmap; ?>">Overview Map Control:</label>
		</th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_overviewmap."' ";
		echo "id='".key_xmlgm_overviewmap."'>";
		switch (get_option(key_xmlgm_overviewmap))
		{
			case "show": $selshowom = "selected='selected'"; break;
			default: $selhideom = "selected='selected'"; break;
		}
		echo "<option value='hide' $selhideom>Invisible (hide)</option>";
		echo "<option value='show' $selshowom>Visible (show)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Shows/Hides the overview map in the
		bottom right corner</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_scalecontrol; ?>">Scale Control:</label></th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_scalecontrol."' ";
		echo "id='".key_xmlgm_scalecontrol."'>";
		switch (get_option(key_xmlgm_scalecontrol))
		{
			case "show": $selshowsc = "selected='selected'"; break;
			default: $selhidesc = "selected='selected'"; break;
		}
		echo "<option value='hide' $selhidesc>Invisible (hide)</option>";
		echo "<option value='show' $selshowsc>Visible (show)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Shows/Hides the scale ruler in the bottom
		left corner</p>
		</td>
	</tr>
</table>
<h3><a name="maptype"></a>Map Type Options</h3>
<p>This optionset helps you to define all Map Type Option. Even the Map
Type Control options you can change.</p>
<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_viewstyle; ?>">Default Map Type:</label></th>
		<td width="50%"><?php
		echo "<select onchange='enabling();' ";
		echo "name='".key_xmlgm_viewstyle."' ";
		echo "id='".key_xmlgm_viewstyle."'>";
		switch (get_option(key_xmlgm_viewstyle))
		{
			case "G_SATELLITE_TYPE":
			case "G_SATELLITE_MAP":
				$selsat = "selected='selected'"; break;
			case "G_HYBRID_TYPE":
			case "G_HYBRID_MAP":
				$selhyb = "selected='selected'"; break;
			case "G_PHYSICAL_TYPE":
			case "G_PHYSICAL_MAP":
				$selphy = "selected='selected'"; break;
			case "G_SATELLITE_3D_TYPE":
			case "G_SATELLITE_3D_MAP":
				$selearth = "selected='selected'"; break;
			case "USER_MAP_OSM":
				$selosm = "selected='selected'"; break;
			case "USER_MAP_OCM":
				$selocm = "selected='selected'"; break;				
			default:
				$selmap = "selected='selected'"; break;
		}
		echo "<option value='G_HYBRID_MAP' $selhyb>Hybrid (G_HYBRID_MAP)</option>";
		echo "<option value='G_SATELLITE_MAP' $selsat>Satellite (G_SATELLITE_MAP)</option>";
		echo "<option value='G_NORMAL_MAP' $selmap>Map (G_NORMAL_MAP)</option>";
		echo "<option value='G_PHYSICAL_MAP' $selphy>Physical (G_PHYSICAL_MAP)</option>";
		echo "<option value='G_SATELLITE_3D_MAP' $selearth>Google Earth Plugin (G_SATELLITE_3D_MAP)</option>";
		echo "<option value='USER_MAP_OSM' $selosm>Open Street Map (USER_MAP_OSM)</option>";
		echo "<option value='USER_MAP_OCM' $selocm>Open Cycle Map (USER_MAP_OCM)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Default Map Display Style</p>
		</td>
		<?php echo $navigation; ?>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_maptypecontrol; ?>">Map Type Control:</label>
		</th>
		<td width="50%"><?php
		echo "<select onchange='enabling();'";
		echo "name='".key_xmlgm_maptypecontrol."' ";
		echo "id='".key_xmlgm_maptypecontrol."'>";
		switch (get_option(key_xmlgm_maptypecontrol))
		{
			case "hide": $selhidemtc = "selected='selected'"; break;
			case "hierarchical": $selhidemhier = "selected='selected'"; break;
			default: $selshowmtc = "selected='selected'"; break;
		}
		echo "<option value='hide' $selhidemtc>Invisible (hide)</option>";
		echo "<option value='show' $selshowmtc>Visible Default (show)</option>";
		echo "<option value='hierarchical' $selhidemhier>Visible Hierarchical Control (hierarchical)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Show/Hide Map Type Control</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_mapnorm; ?>">Normal Map Type Button:</label>
		</th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_mapnorm."' ";
		echo "id='".key_xmlgm_mapnorm."'>";
		switch (get_option(key_xmlgm_mapnorm))
		{
			case "hide": $selhidemapnorm = "selected='selected'"; break;
			default: $selshowmapnorm = "selected='selected'"; break;
		}
		echo "<option value='hide' $selhidemapnorm>Invisible (hide)</option>";
		echo "<option value='show' $selshowmapnorm>Visible (show)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Show/Hide Normal Map Type Button</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_mapsat; ?>">Satellite Map Type Button:</label>
		</th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_mapsat."' ";
		echo "id='".key_xmlgm_mapsat."'>";
		switch (get_option(key_xmlgm_mapsat))
		{
			case "hide": $selhidemapsat = "selected='selected'"; break;
			default: $selshowmapsat = "selected='selected'"; break;
		}
		echo "<option value='hide' $selhidemapsat>Invisible (hide)</option>";
		echo "<option value='show' $selshowmapsat>Visible (show)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Show/Hide Satellite Map Type Button</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_maphyb; ?>">Hybrid Map Type Button:</label>
		</th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_maphyb."' ";
		echo "id='".key_xmlgm_maphyb."'>";
		switch (get_option(key_xmlgm_maphyb))
		{
			case "hide": $selhidemaphyb = "selected='selected'"; break;
			default: $selshowmaphyb = "selected='selected'"; break;
		}
		echo "<option value='hide' $selhidemaphyb>Invisible (hide)</option>";
		echo "<option value='show' $selshowmaphyb>Visible (show)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Show/Hide Hybrid Map Type Button</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_mapphy; ?>">Physical Map Type Button:</label>
		</th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_mapphy."' ";
		echo "id='".key_xmlgm_mapphy."'>";
		switch (get_option(key_xmlgm_mapphy))
		{
			case "hide": $selhidemapphy = "selected='selected'"; break;
			default: $selshowmapphy = "selected='selected'"; break;
		}
		echo "<option value='hide' $selhidemapphy>Invisible (hide)</option>";
		echo "<option value='show' $selshowmapphy>Visible (show)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Show/Hide Physical Map Type Button</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_mapearth; ?>">Google Earth Plugin Map Type
		Button:</label></th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_mapearth."' ";
		echo "id='".key_xmlgm_mapearth."'>";
		switch (get_option(key_xmlgm_mapearth))
		{
			case "hide": $selhidemapearth = "selected='selected'"; break;
			default: $selshowmapearth = "selected='selected'"; break;
		}
		echo "<option value='hide' $selhidemapearth>Invisible (hide)</option>";
		echo "<option value='show' $selshowmapearth>Visible (show)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Show/Hide Google Earth Plugin Map Type
		Button</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_maposm; ?>">Open Street Map Map Type
		Button:</label></th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_maposm."' ";
		echo "id='".key_xmlgm_maposm."'>";
		switch (get_option(key_xmlgm_maposm))
		{
			case "hide": $selhidemaposm = "selected='selected'"; break;
			default: $selshowmaposm = "selected='selected'"; break;
		}
		echo "<option value='hide' $selhidemaposm>Invisible (hide)</option>";
		echo "<option value='show' $selshowmaposm>Visible (show)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Show/Hide Open Street Map Map Type
		Button</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_mapocm; ?>">Open Cycle Map Map Type
		Button:</label></th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_mapocm."' ";
		echo "id='".key_xmlgm_mapocm."'>";
		switch (get_option(key_xmlgm_mapocm))
		{
			case "hide": $selhidemapocm = "selected='selected'"; break;
			default: $selshowmapocm = "selected='selected'"; break;
		}
		echo "<option value='hide' $selhidemapocm>Invisible (hide)</option>";
		echo "<option value='show' $selshowmapocm>Visible (show)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Show/Hide Open Cycle Map Map Type
		Button</p>
		</td>
	</tr>	
</table>
<h3><a name="mapbehaviour"></a>Map Behaviour Options</h3>
<p>Customize the behaviour of the map (e.g. zoom behaviours).</p>
<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_dragging; ?>">Dragging:</label></th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_dragging."' ";
		echo "id='".key_xmlgm_dragging."'>";
		switch (get_option(key_xmlgm_dragging))
		{
			case "disabled": $seldraggingdis = "selected='selected'"; break;
			default: $seldraggingen = "selected='selected'"; break;
		}
		echo "<option value='disabled' $seldraggingdis>Disabled (disabled)</option>";
		echo "<option value='enabled' $seldraggingen>Enabled (enabled)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Enables/Disables the dragging of the map</p>
		</td>
		<?php echo $navigation; ?>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_doubleClickZoom; ?>">Double Click Zoom:</label>
		</th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_doubleClickZoom."' ";
		echo "id='".key_xmlgm_doubleClickZoom."'>";
		switch (get_option(key_xmlgm_doubleClickZoom))
		{
			case "disabled": $seldczdis = "selected='selected'"; break;
			default: $seldczen = "selected='selected'"; break;
		}
		echo "<option value='disabled' $seldczdis>Disabled (disabled)</option>";
		echo "<option value='enabled' $seldczen>Enabled (enabled)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Enables/Disables double click to zoom in
		and out</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_continuousZoom; ?>">Continuous Zoom:</label>
		</th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_continuousZoom."' ";
		echo "id='".key_xmlgm_continuousZoom."'>";
		switch (get_option(key_xmlgm_continuousZoom))
		{
			case "disabled": $selczdis = "selected='selected'"; break;
			default: $selczen = "selected='selected'"; break;
		}
		echo "<option value='disabled' $selczdis>Disabled (disabled)</option>";
		echo "<option value='enabled' $selczen>Enabled (enabled)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Enables/Disables continuous smooth
		zooming for select browsers</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_scrollWheelZoom; ?>">Scroll Wheel Zoom:</label>
		</th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_scrollWheelZoom."' ";
		echo "id='".key_xmlgm_scrollWheelZoom."'>";
		switch (get_option(key_xmlgm_scrollWheelZoom))
		{
			case "disabled": $selswzdis = "selected='selected'"; break;
			default: $selswzen = "selected='selected'"; break;
		}
		echo "<option value='disabled' $selswzdis>Disabled (disabled)</option>";
		echo "<option value='enabled' $selswzen>Enabled (enabled)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Enables/Disables zooming using a mouse's
		scroll wheel</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_googleBar; ?>">Google Bar:</label></th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_googleBar."' ";
		echo "id='".key_xmlgm_googleBar."'>";
		switch (get_option(key_xmlgm_googleBar))
		{
			case "enabled": $selgben = "selected='selected'"; break;
			default: $selgbdis = "selected='selected'"; break;
		}
		echo "<option value='disabled' $selgbdis>Disabled (disabled)</option>";
		echo "<option value='enabled' $selgben>Enabled (enabled)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Enables/Disables he GoogleBar, an
		integrated search control, to the map. When enabled, this control
		takes the place of the default Powered By Google logo.</p>
		</td>
	</tr>
</table>
<h3><a name="gpx"></a>GPX Options</h3>
<p>Settings specific for GPX Files<br />Have a look at the <a href="#templ">Templates</a> Section, there you can set the look &amp; feel of your GPX Waypoint Markers!</p>
<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_linecolor; ?>">Line Color:</label></th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_linecolor."' ";
		echo "id='".key_xmlgm_linecolor."' ";
		echo "value='".get_option(key_xmlgm_linecolor)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter the default line color of any Track
		or Route</p>
		</td>
		<?php echo $navigation; ?>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_linewidth; ?>">Line Width:</label></th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_linewidth."' ";
		echo "id='".key_xmlgm_linewidth."' ";
		echo "value='".get_option(key_xmlgm_linewidth)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter the default line width of any Track
		or Route</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_lineopacity; ?>">Line Opacity:</label></th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_lineopacity."' ";
		echo "id='".key_xmlgm_lineopacity."' ";
		echo "value='".get_option(key_xmlgm_lineopacity)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter the default line opacity (1-100) of
		any Track or Route</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_gpxview; ?>">GPX View Style:</label></th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_gpxview."' ";
		echo "id='".key_xmlgm_gpxview."'>";
		switch (get_option(key_xmlgm_gpxview))
		{
			case "wpt": $selwpt = "selected='selected'"; break;
			case "rte": $selrte = "selected='selected'"; break;
			case "trk": $seltrk = "selected='selected'"; break;
			default: $selall = "selected='selected'"; break;
		}
		echo "<option value='all' $selall>Everything (all)</option>";
		echo "<option value='wpt' $selwpt>Waypoints (wpt)</option>";
		echo "<option value='rte' $selrte>Routes (rte)</option>";
		echo "<option value='trk' $seltrk>Tracks (trk)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Shows only the selected part of the GPX
		File.</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_gpxparsemode; ?>">GPX Parse Mode:</label></th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_gpxparsemode."' ";
		echo "id='".key_xmlgm_gpxparsemode."'>";
		switch (get_option(key_xmlgm_gpxparsemode))
		{
			case "2": $selparsemode2 = "selected='selected'"; break;
			default: $selparsemode1 = "selected='selected'"; break;
		}
		echo "<option value='1' $selparsemode1>Mode 1 - DOM (1)</option>";
		echo "<option value='2' $selparsemode2>Mode 2 - SAX and Database (2)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Defines the GPX parse mode. To clear database content for Mode 2 simply switch this option an press save. Then reswitch this option and resave!<br /><b>Mode 1 DOM</b> is a life time parsing and loads always the whole GPX file into your PHP application memory. For larger files you will often get an out of memory exception because of this (if your memory maximum is at 32mb, this happens already with files a little bit larger than 1mb).<br /><b>Mode 2 SAX</b> reads the GPX file sequentially and saves all needed data into the database. For showing the map and all the other charts and tables, the plugin reads the data from the database (if file updatetime get changed, there will be a reparse). With the database its is no longer necessary to load always all the data. With this mode you get a really large database.</p>
		</td>
	</tr>	
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_gpxparsemode2maxlimit; ?>">GPX Parse Mode 2 Max Rows Limit:</label></th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_gpxparsemode2maxlimit."' ";
		echo "id='".key_xmlgm_gpxparsemode2maxlimit."' ";
		echo "value='".get_option(key_xmlgm_gpxparsemode2maxlimit)."' />\n";
		?>
		<p style="margin: 5px 10px;">Defines the number of rows to be loaded in one step! Remember: more rows loaded = more memory needed - less rows loaded = slow down of loading because of more database accesses.</p>
		</td>
	</tr>	
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_gpxdirectopenwplink; ?>">GPX Direct Open Waypoint Link:</label></th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_gpxdirectopenwplink."' ";
		echo "id='".key_xmlgm_gpxdirectopenwplink."'>";
		switch (get_option(key_xmlgm_gpxdirectopenwplink))
		{
			case "disabled": $seldowpdis = "selected='selected'"; break;
			case "click": $seldowpcli = "selected='selected'"; break;
			default: $seldowpdbl = "selected='selected'"; break;
		}
		echo "<option value='dblclick' $seldowpdbl>Doubleclick (dblclick)</option>";
		echo "<option value='click' $seldowpcli>Click (click)</option>";
		echo "<option value='disabled' $seldowpdis>Disabled (disabled)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">If a GPX Waypoint has a link-Element you can Doubleclick or Click the marker to directly open this link! If you want to disable this behaviour, set disabled!</p>
		</td>
	</tr>
	
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_gpxcssstyle; ?>">Waypoint CSS Style:</label>
		</th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_gpxcssstyle."' ";
		echo "id='".key_xmlgm_gpxcssstyle."' ";
		echo "value='".get_option(key_xmlgm_gpxcssstyle)."' />\n";
		?>
		<p style="margin: 5px 10px;">Define the GPX Waypoints Info Window CSS
		Style! Have a look at the <a href="#templ">Templates</a> Section, it would be better to use these templates than this property!</p>
		</td>
	</tr>
</table>
<h3><a name="elev"></a>Elevation Chart Options</h3>
<p>Settings for the Elevation Chart (now supported by GPX Tracks only)</p>
<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_elevationchart; ?>">Elevation Chart:</label>
		</th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_elevationchart."' ";
		echo "id='".key_xmlgm_elevationchart."'>";
		switch (get_option(key_xmlgm_elevationchart))
		{
			case "hide": $selelevhide = "selected='selected'"; break;
			default: $selelevshow = "selected='selected'"; break;
		}
		echo "<option value='hide' $selelevhide>Invisible (hide)</option>";
		echo "<option value='show' $selelevshow>Visible (show)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Visibility of evelation chart for GPX
		tracks</p>
		</td>
		<?php echo $navigation; ?>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_elevationtitle; ?>">Elevation Chart Title:</label>
		</th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_elevationtitle."' ";
		echo "id='".key_xmlgm_elevationtitle."' ";
		echo "value='".get_option(key_xmlgm_elevationtitle)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter the Elevation Chart Title (<b>do
		not use special chars</b>)</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_elevationheight; ?>">Elevation Chart
		Height:</label></th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_elevationheight."' ";
		echo "id='".key_xmlgm_elevationheight."' ";
		echo "value='".get_option(key_xmlgm_elevationheight)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter the height of the elevation chart!</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_elevlinecolor; ?>">Elevation Chart Line
		Color:</label></th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_elevlinecolor."' ";
		echo "id='".key_xmlgm_elevlinecolor."' ";
		echo "value='".get_option(key_xmlgm_elevlinecolor)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter the default line color of the
		elevation charts line!</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_elelinewidth; ?>">Elevation Chart Line
		Width:</label></th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_elelinewidth."' ";
		echo "id='".key_xmlgm_elelinewidth."' ";
		echo "value='".get_option(key_xmlgm_elelinewidth)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter the default line width of the
		elevation chart</p>
		</td>
	</tr>
</table>
<h3><a name="speedchart"></a>Speed Chart Options</h3>
<p>Settings for the Speed Chart (now supported by GPX Tracks only)</p>
<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_speedchart; ?>">Speed Chart:</label></th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_speedchart."' ";
		echo "id='".key_xmlgm_speedchart."'>";
		switch (get_option(key_xmlgm_speedchart))
		{
			case "show": $selspeedshow = "selected='selected'"; break;
			default:  $selspeedhide = "selected='selected'"; break;
		}
		echo "<option value='hide' $selspeedhide>Invisible (hide)</option>";
		echo "<option value='show' $selspeedshow>Visible (show)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Visibility of speed chart for GPX tracks</p>
		</td>
		<?php echo $navigation; ?>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_speedcharttitle; ?>">Speed Chart Title:</label>
		</th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_speedcharttitle."' ";
		echo "id='".key_xmlgm_speedcharttitle."' ";
		echo "value='".get_option(key_xmlgm_speedcharttitle)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter the Speed Chart Title (<b>do not
		use special chars</b>)</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_speedheight; ?>">Speed Chart Height:</label>
		</th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_speedheight."' ";
		echo "id='".key_xmlgm_speedheight."' ";
		echo "value='".get_option(key_xmlgm_speedheight)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter the height of the speed chart!</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_speedlinecolor; ?>">Speed Chart Line Color:</label>
		</th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_speedlinecolor."' ";
		echo "id='".key_xmlgm_speedlinecolor."' ";
		echo "value='".get_option(key_xmlgm_speedlinecolor)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter the default line color of the speed
		charts line!</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_speedlinewidth; ?>">Speed Chart Line Width:</label>
		</th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_speedlinewidth."' ";
		echo "id='".key_xmlgm_speedlinewidth."' ";
		echo "value='".get_option(key_xmlgm_speedlinewidth)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter the default line width of the speed
		chart</p>
		</td>
	</tr>
</table>
<h3><a name="heartrate"></a>Heart Rate Chart Options</h3>
<p>Settings for the Heart Rate (now supported by GPX Tracks only)</p>
<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_heartratechart; ?>">Heart Rate Chart:</label></th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_heartratechart."' ";
		echo "id='".key_xmlgm_heartratechart."'>";
		switch (get_option(key_xmlgm_heartratechart))
		{
			case "show": $selhrshow = "selected='selected'"; break;
			default:  $selhrhide = "selected='selected'"; break;
		}
		echo "<option value='hide' $selhrhide>Invisible (hide)</option>";
		echo "<option value='show' $selhrshow>Visible (show)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Visibility of Heart Rate Chart for GPX tracks</p>
		</td>
		<?php echo $navigation; ?>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_heartratecharttitle; ?>">Heart Rate Chart Title:</label>
		</th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_heartratecharttitle."' ";
		echo "id='".key_xmlgm_heartratecharttitle."' ";
		echo "value='".get_option(key_xmlgm_heartratecharttitle)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter the Heart Rate Chart Title (<b>do not
		use special chars</b>)</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_heartrateheight; ?>">Heart Rate Chart Height:</label>
		</th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_heartrateheight."' ";
		echo "id='".key_xmlgm_heartrateheight."' ";
		echo "value='".get_option(key_xmlgm_heartrateheight)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter the height of the Heart Rate chart!</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_heartratelinecolor; ?>">Heart Rate Chart Line Color:</label>
		</th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_heartratelinecolor."' ";
		echo "id='".key_xmlgm_heartratelinecolor."' ";
		echo "value='".get_option(key_xmlgm_heartratelinecolor)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter the default line color of the Heart Rate
		charts line!</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_heartratelinewidth; ?>">Heart Rate Chart Line Width:</label>
		</th>
		<td width="50%"><?php
		echo "<input type='text' size='50' ";
		echo "name='".key_xmlgm_heartratelinewidth."' ";
		echo "id='".key_xmlgm_heartratelinewidth."' ";
		echo "value='".get_option(key_xmlgm_heartratelinewidth)."' />\n";
		?>
		<p style="margin: 5px 10px;">Enter the default line width of the Heart Rate
		chart</p>
		</td>
	</tr>
</table>
<h3><a name="checkpoint"></a>Checkpoint Options</h3>
<p>Settings for the Checkpoints (now supported by GPX Tracks only). To
activate the checkpoint you have to set the
gpxcheckpointinterval-Parameter on every map. The
gpxcheckpointinterval-parameter defines the interval in km or mi between
every checkpoint!<br />Have a look at the <a href="#templ">Templates</a> section, there you can set the look &amp; feel of your Checkpoint Table!</p>
<table class="form-table" cellspacing="2" cellpadding="5" width="100%">

	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_checkpointmapmarkercolor; ?>">Checkpoint
		Map Marker Color:</label></th>
		<td width="50%"><?php
		echo "<select onchange='enabling();' ";
		echo "name='".key_xmlgm_checkpointmapmarkercolor."' ";
		echo "id='".key_xmlgm_checkpointmapmarkercolor."'>";
		switch (get_option(key_xmlgm_checkpointmapmarkercolor))
		{
			case "red": $selcpmred = "selected='selected'"; break;
			case "green": $selcpmgreen = "selected='selected'"; break;
			case "yellow": $selcpmyellow = "selected='selected'"; break;
			case "blue": $selcpmblue = "selected='selected'"; break;
			default: $selcpmred = "selected='selected'"; break;
		}
		echo "<option value='red' $selcpmred>Red (red)</option>";
		echo "<option value='green' $selcpmgreen>Green (green)</option>";
		echo "<option value='yellow' $selcpmyellow>Yellow (yellow)</option>";
		echo "<option value='blue' $selcpmblue>Blue (blue)</option>";
		echo "</select>\n";
		?> <img alt="Default Marker" id="cpmarker" name="cpmarker" src="" /> <img alt="Start Marker"
			id="cpmarker_start" name="cpmarker_start" src="" /> <img
			alt="End Marker" id="cpmarker_end" name="cpmarker_end" src="" />
		<p style="margin: 5px 10px;">Define the Marker Color for the
		Checkpoint</p>
		</td>
		<?php echo $navigation; ?>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_checkpointinterval; ?>">Checkpoint
		Interval:</label></th>
		<td width="50%"><?php
		echo "<input type='text' size='50' onchange='enabling();'";
		echo "name='".key_xmlgm_checkpointinterval."' ";
		echo "id='".key_xmlgm_checkpointinterval."' ";
		echo "value='".get_option(key_xmlgm_checkpointinterval)."' />\n";
		?>
		<p style="margin: 5px 10px;">Set the checkpoint interval (0=disabled)!</p>
		</td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_checkpointtable; ?>">Checkpoint Table:</label>
		</th>
		<td width="50%"><?php
		echo "<select ";
		echo "name='".key_xmlgm_checkpointtable."' ";
		echo "id='".key_xmlgm_checkpointtable."'>";
		switch (get_option(key_xmlgm_checkpointtable))
		{
			case "show": $selcptabshow = "selected='selected'"; break;
			default: $selcptabhide = "selected='selected'"; break;
		}
		echo "<option value='hide' $selcptabhide>Invisible (hide)</option>";
		echo "<option value='show' $selcptabshow>Visible (show)</option>";
		echo "</select>\n";
		?>
		<p style="margin: 5px 10px;">Visibility of checkpoint table
		(checkpointinterval has to be set to a value &gt;0)</p>
		</td>
	</tr>	
	</table>
<h3><a name="templ"></a>Templates</h3>
<p>Define the look &amp; feel of your Checkpoint Table, GPX marker or NextGEN Gallery marker!</p>
<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_checkpointtabletempl; ?>">Checkpoint Table
		Template:</label></th>
		<td width="50%"><?php
		echo "<textarea rows='15' cols='50' style='width: 100%;'";
		echo "name='".key_xmlgm_checkpointtabletempl."' ";
		echo "id='".key_xmlgm_checkpointtabletempl."'>";
		echo get_option(key_xmlgm_checkpointtabletempl)."</textarea>\n";
		?>
		<p style="margin: 5px 10px;">Define your own table template. Use the
		following parameters for placeholders:<br />
		%WIDTH% - Placeholder of the Width setting<br />
		%ROW% - Row Template Placeholder<br />
		%DISTANCE% - Distance of the track<br />
		%TIME% - Time of the track<br />
		%MOVINGTIME% - Time of the track (without breaks)<br />
		%SPEED% - Average Speed of the track<br />
		%MOVINGSPEED% - Average Speed of the track (without breaks)<br />		
		%MAXSPEED% - Max Speed of the track<br />
		%CLIMBUP% - Climb Up of the track<br />
		%CLIMBDOWN% - Climb Down of the track<br />
		%HEARTRATE% - Heart Rate from the starting point<br />
		%ELEVATIONMAX%  - Max Elevation<br />
		%ELEVATIONMIN%  - Min Elevation<br />
		<br />
		Delete the whole content to restore the default value!</p>
		</td>
		<?php echo $navigation; ?>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_checkpointtablerowtempl; ?>">Checkpoint
		Table Row Template:</label></th>
		<td width="50%"><?php
		echo "<textarea rows='15' cols='50' style='width: 100%;'";
		echo "name='".key_xmlgm_checkpointtablerowtempl."' ";
		echo "id='".key_xmlgm_checkpointtablerowtempl."'>";
		echo get_option(key_xmlgm_checkpointtablerowtempl)."</textarea>\n";
		?>
		<p style="margin: 5px 10px;">Define your own row template. Use the
		following parameters for placeholders:<br />
		%MARKERIMAGEURL% - URL to the checkpoint Image <br />
		%CHECKPOINTNUMBER% - Number of the Checkpoint <br />
		%PARTDISTANCE% - Distance between checkpoints<br />
		%PARTTIME% - Timedifference between checkpoints<br />
		%PARTMOVINGTIME% - Timedifference between checkpoints (without breaks)<br />
		%PARTSPEED% - Average speed between checkpoints<br />
		%PARTMOVINGSPEED% - Average speed between checkpoints (without breaks)<br />
		%PARTMAXSPEED% - Max Speed between checkpoints<br />
		%PARTCLIMBUP% - Climb Up checkpoints<br />
		%PARTCLIMBDOWN% - Climb Down checkpoints<br />	
		%PARTHEARTRATE% - Average heart rate between checkpoints<br />			
		%DISTANCE% - Distance from starting point<br />
		%TIME% - Time from the starting point<br />
		%MOVINGTIME% - Time from the starting point (without breaks)<br />		
		%SPEED% - Average Speed from the starting point<br />
		%MOVINGSPEED% - Average Speed from the starting point (without breaks)<br />
		%MAXSPEED% - Max Speed from the starting point<br />
		%CLIMBUP% - Climb Up from the starting point<br />
		%CLIMBDOWN% - Climb Down from the starting point<br />
		%HEARTRATE% - Heart Rate from the starting point<br />
		%LATITUDE% - Latitude Information of the Checkpoint<br />
		%LONGITUDE% - Longitude Information of the Checkpoint<br />
		%ELEVATION% - Elevation Information of the Checkpoint<br />				
		<br />
		Delete the whole content to restore the default value!</p></td>
	</tr>
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_gpxmarkertempl; ?>">GPX Waypoint Marker Template:</label></th>
		<td width="50%"><?php
		echo "<textarea rows='15' cols='50' style='width: 100%;'";
		echo "name='".key_xmlgm_gpxmarkertempl."' ";
		echo "id='".key_xmlgm_gpxmarkertempl."'>";
		echo get_option(key_xmlgm_gpxmarkertempl)."</textarea>\n";
		?>
		<p style="margin: 5px 10px;">Define your own row template. Use the
		following parameters for placeholders:<br />
		%DESCRIPTION% - DESC Tag of the GPX Waypoint <br />
		%NAME% - NAME Tag of the GPX Waypoint <br />
		%LINK% - Attribute HREF of the LINK Tag of the GPX Waypoint <br />
		%LATITUDE% - Latitude Information of the GPX Waypoint<br />
		%LONGITUDE% - Longitude Information of the GPX Waypoint<br />
		%ELEVATION% - Elevation Information of the GPX Waypoint<br />		
		<br />
		Delete the whole content to restore the default value!</p></td>
	</tr>	
	<tr>
		<th valign="top" style="padding-top: 10px;" width="35%"><label
			for="<?php echo key_xmlgm_nggmarkertempl; ?>">NextGEN Gallery Image Marker Template:</label></th>
		<td width="50%"><?php
		echo "<textarea rows='15' cols='50' style='width: 100%;'";
		echo "name='".key_xmlgm_nggmarkertempl."' ";
		echo "id='".key_xmlgm_nggmarkertempl."'>";
		echo get_option(key_xmlgm_nggmarkertempl)."</textarea>\n";
		?>
		<p style="margin: 5px 10px;">Define your own row template. Use the
		following parameters for placeholders:<br />
		%FULLPATH% - Full Image Path <br />
		%THUMBPATH% - Thumb Image Path <br />
		%TITLE% - Title of the image <br />
		%DESCRIPTION% - Description of the Image <br />
		%CREATIONDATE% - Creation Date of the Image <br />
		%LATITUDE% - Latitude Information of the Image<br />
		%LONGITUDE% - Longitude Information of the Image<br />
		<br />
		Delete the whole content to restore the default value!</p></td>
	</tr>		
</table>
</div>
</form>
<script type="text/javascript">
		<!--
			enabling();
		-->
		</script>
		<?php
}
?>