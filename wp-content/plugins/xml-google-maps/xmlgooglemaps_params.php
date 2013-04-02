<?php
/*
 * Diese Klasse behandelt sämtliche Parameter für die Anzeige der Karte
 */
class xmlgooglemaps_params {

	function xmlgooglemaps_params() {
		$this->width = intval(get_option(key_xmlgm_width));
		$this->height = intval(get_option(key_xmlgm_height));
		$this->align = get_option(key_xmlgm_align);
		$this->googleMapsAPIKey = get_option(key_xmlgm_uid);
		$this->viewStyle = get_option(key_xmlgm_viewstyle);
		$this->urlDisplayMode = get_option(key_xmlgm_urldisplaymode);
		$this->panZoomControl = get_option(key_xmlgm_panzoomcontrol);
		$this->overViewMapControl = get_option(key_xmlgm_overviewmap);
		$this->scaleControl = get_option(key_xmlgm_scalecontrol);
		$this->mapTypeControl = get_option(key_xmlgm_maptypecontrol);
		$this->mapNormal = get_option(key_xmlgm_mapnorm);
		$this->mapHybrid = get_option(key_xmlgm_maphyb);
		$this->mapSatellite = get_option(key_xmlgm_mapsat);
		$this->mapPhysical = get_option(key_xmlgm_mapphy);
		$this->mapEarth = get_option(key_xmlgm_mapearth);
		$this->mapOSM = get_option(key_xmlgm_maposm);
		$this->mapOCM = get_option(key_xmlgm_mapocm);
		$this->dragging = get_option(key_xmlgm_dragging);
		$this->doubleClickZoom = get_option(key_xmlgm_doubleClickZoom);
		$this->continuousZoom = get_option(key_xmlgm_continuousZoom);
		$this->googleBar = get_option(key_xmlgm_googleBar);
		$this->scrollWheelZoom = get_option(key_xmlgm_scrollWheelZoom);
		$this->measurement = get_option(key_xmlgm_elevationmeasurement);
		$this->speedscale = get_option(key_xmlgm_speedscale);
		$this->cacheDuration = intval(get_option(key_xmlgm_cache));

		$this->kmlParsing = get_option(key_xmlgm_kmlparsing);
			
		$this->gpxLineColor = get_option(key_xmlgm_linecolor);
		$this->gpxLineWidth = intval(get_option(key_xmlgm_linewidth));
		$this->gpxLineOpacity = intval(get_option(key_xmlgm_lineopacity));
		$this->gpxView = get_option(key_xmlgm_gpxview);
		$this->gpxElevationLineColor = str_replace("#","",get_option(key_xmlgm_elevlinecolor));
		$this->gpxElevationChart = get_option(key_xmlgm_elevationchart);
		$this->gpxElevationLineWidth = intval(get_option(key_xmlgm_elelinewidth));
		$this->gpxElevationChartHeight = intval(get_option(key_xmlgm_elevationheight));	
		$this->gpxElevationChartTitle = get_option(key_xmlgm_elevationtitle);
		$this->gpxSpeedLineColor = str_replace("#","",get_option(key_xmlgm_speedlinecolor));
		$this->gpxSpeedChart = get_option(key_xmlgm_speedchart);
		$this->gpxSpeedLineWidth = intval(get_option(key_xmlgm_speedlinewidth));
		$this->gpxSpeedChartHeight = intval(get_option(key_xmlgm_speedheight));
		$this->gpxSpeedChartTitle = get_option(key_xmlgm_speedcharttitle);
		$this->gpxHeartRateLineColor = str_replace("#","",get_option(key_xmlgm_heartratelinecolor));
		$this->gpxHeartRateChart = get_option(key_xmlgm_heartratechart);
		$this->gpxHeartRateLineWidth = intval(get_option(key_xmlgm_heartratelinewidth));
		$this->gpxHeartRateChartHeight = intval(get_option(key_xmlgm_heartrateheight));
		$this->gpxHeartRateChartTitle = get_option(key_xmlgm_heartratecharttitle);		
		$this->gpxCSSStyle = get_option(key_xmlgm_gpxcssstyle);
		$this->gpxCheckpointMarkerColor = get_option(key_xmlgm_checkpointmapmarkercolor);
		$this->gpxCheckpointInterval = get_option(key_xmlgm_checkpointinterval);
		$this->gpxCheckpointTable = get_option(key_xmlgm_checkpointtable);
		$this->gpxCheckpointTableTemplate = get_option(key_xmlgm_checkpointtabletempl);
		$this->gpxCheckpointTableRowTemplate = get_option(key_xmlgm_checkpointtablerowtempl);
		$this->gpxWaypointMarkerTemplate = get_option(key_xmlgm_gpxmarkertempl);
		$this->gpxDirectOpenWPLink = get_option(key_xmlgm_gpxdirectopenwplink);
		$this->gpxParseMode = get_option(key_xmlgm_gpxparsemode);
		$this->gpxParseMode2MaxLimit = get_option(key_xmlgm_gpxparsemode2maxlimit);
		$this->nggalMarkerTemplate = get_option(key_xmlgm_nggmarkertempl);
		

		if (strlen($this->gpxCheckpointTableTemplate)==0) {
			$filename = dirname(__FILE__)."/templates/defaultcptable.template";
			if (file_exists($filename)) {
				$handle = fopen ($filename, "r");
				$this->gpxCheckpointTableTemplate = fread ($handle, filesize ($filename));
				fclose ($handle);
			}
		}
		if (strlen($this->gpxCheckpointTableRowTemplate)==0) {
			$filename = dirname(__FILE__)."/templates/defaultcptablerow.template";
			if (file_exists($filename)) {
				$handle = fopen ($filename, "r");
				$this->gpxCheckpointTableRowTemplate = fread ($handle, filesize ($filename));
				fclose ($handle);
			}
		}
		if (strlen($this->gpxWaypointMarkerTemplate)==0) {
			$filename = dirname(__FILE__)."/templates/defaultgpxmarker.template";
			if (file_exists($filename)) {
				$handle = fopen ($filename, "r");
				$this->gpxWaypointMarkerTemplate = fread ($handle, filesize ($filename));
				fclose ($handle);
			}
		}
		if (strlen($this->nggalMarkerTemplate)==0) {
			$filename = dirname(__FILE__)."/templates/defaultnggmarker.template";
			if (file_exists($filename)) {
				$handle = fopen ($filename, "r");
				$this->nggalMarkerTemplate = fread ($handle, filesize ($filename));
				fclose ($handle);
			}
		}
	}

	var $width = 664;
	var $height = 400;
	var $align = "left";
	var $googleMapsAPIKey = "";
	var $viewStyle = "G_NORMAL_MAP";
	var $urlDisplayMode = "title";
	var $panZoomControl = "large";
	var $overViewMapControl = "hide";
	var $scaleControl = "hide";
	var $mapTypeControl = "show";
	var $mapNormal = "show";
	var $mapHybrid = "show";
	var $mapSatellite = "show";
	var $mapPhysical = "hide";
	var $mapEarth = "hide";
	var $mapOSM = "hide";
	var $mapOCM = "hide";
	var $dragging = "enabled";
	var $doubleClickZoom = "enabled";
	var $continuousZoom = "enabled";
	var $googleBar = "disabled";
	var $scrollWheelZoom = "enabled";
	var $kmlParsing = "enabled";
	var $cacheDuration = -1;

	var $manualZoom = 0;
	var $minLat = 0;
	var $maxLat = 0;
	var $minLon = 0;
	var $maxLon = 0;
	var $zoom = 0;
	var $deltazoom = 0;

	var $gpxLineColor = "#0000FF";
	var $gpxLineWidth = 5;
	var $gpxLineOpacity = 50;
	var $gpxView = "all";
	var $gpxInterval = 1;
	var $gpxElevationLineColor = "0000FF";
	var $gpxElevationLineWidth = 5;
	var $gpxElevationChart = "show";
	var $gpxElevationChartHeight = 200;
	var $measurement = "metric";
	var $speedscale = "level2";
	var $gpxSpeedLineColor = "0000FF";
	var $gpxSpeedLineWidth = 5;
	var $gpxSpeedChart = "hide";
	var $gpxSpeedChartHeight = 200;
	var $gpxHeartRateLineColor = "0000FF";
	var $gpxHeartRateLineWidth = 5;
	var $gpxHeartRateChart = "hide";
	var $gpxHeartRateChartHeight = 200;
	var $gpxHeartRateChartTitle = "Heart Rate Profile";
	var $gpxCSSStyle = "font-family: Verdana, Arial, Sans-Serif;";
	var $gpxSpeedChartTitle = "Speed Profile";
	var $gpxElevationChartTitle = "Elevation Profile";
	var $gpxCheckpointInterval = 0;
	var $gpxCheckpointMarkerColor = "red";
	var $gpxCheckpointTable = "hide";
	var $gpxDirectOpenWPLink = "dblclick";
	var $gpxParseMode = 1;
	var $gpxParseMode2MaxLimit = 2000;

	var $gpxCheckpointTableTemplate = "";
	var $gpxCheckpointTableRowTemplate = "";
	var $gpxWaypointMarkerTemplate = "";
	var $nggalMarkerTemplate = "";

	var $nggalGalleries = array();
	var $nggalPics = array();

	function getNggCount() {
		return count($this->nggalGalleries) + count($this->nggalPics);
	}

	function printObjectHTMLComment() {
		print_r("<!-- Debug Info Actual Parameter Object \n");
		print_r($this);
		print_r("\n -->");
	}
	
	function getStyleMapDiv() {
		switch (strtolower($this->align)) {
			case "none": return '';
			default: return ' style="text-align: '.$this->align.';" '; break;
		}
	}
		
	function getStyleImgDiv() {
		switch (strtolower($this->align)) {
			case "none": return '';			
			default: return ' style="text-align: '.$this->align.'; margin: 0px; padding: 0px; max-width: 100%;" '; break;
		}
	}

	function setParams($param_string) {
		$param_string = str_replace("####nnnn####","",$param_string);
		$param_string = str_replace("####rrrr####","",$param_string);
		$manualzoom = 0;
		$vars = split(";",$param_string);
		for ($i=0 ;$i<count($vars);$i++) {
			$varvalue=split("=",$vars[$i]);
			if (count($varvalue)==2) {
				$varvalue[0] = trim($varvalue[0]);
				$varvalue[1] = trim($varvalue[1]);
				switch (strtolower($varvalue[0])) {
					case "gpxparsemode": 
						if (is_numeric($varvalue[1]) && ((intval($varvalue[1])==1) || (intval($varvalue[1])==2))) {
							$this->gpxParseMode = intval($varvalue[1]);
						}
						break;
					case "gpxparsemode2maxlimit": 
						if (is_numeric($varvalue[1]) && (intval($varvalue[1])>0)) {
							$this->gpxParseMode2MaxLimit = intval($varvalue[1]);
						}						
						break;
					case "kmlparsing":
						$this->kmlParsing = $varvalue[1];
						break;
					case "ngg_gallery":
						if (is_numeric($varvalue[1])) {
							$this->nggalGalleries[] = intval($varvalue[1]);
						}
						break;
					case "ngg_picture":
						if (is_numeric($varvalue[1])) {
							$this->nggalPics[] = intval($varvalue[1]);
						}
						break;
					case "dragging":
						$this->dragging = $varvalue[1];
						break;
					case "doubleclickzoom":
						$this->doubleClickZoom = $varvalue[1];
						break;
					case "continuouszoom":
						$this->continuousZoom = $varvalue[1];
						break;
					case "googlebar":
						$this->googleBar = $varvalue[1];
						break;
					case "scrollwheelzoom":
						$this->scrollWheelZoom = $varvalue[1];
						break;
					case "urldisplaymode":
						$this->urlDisplayMode = $varvalue[1];
						break;
					case "maptype":
						$this->viewStyle = $varvalue[1];
						break;
					case "width":
						if (is_numeric($varvalue[1])) {
							$this->width = intval($varvalue[1]);
						}
						break;
					case "height":
						if (is_numeric($varvalue[1])) {
							$this->height = intval($varvalue[1]);
						}
						break;
					case "cache":
						if (is_numeric($varvalue[1])) {
							$this->cacheDuration = intval($varvalue[1]);
						}
						break;						
					case "align":
						$this->align = $varvalue[1];
						break;
					case "panzoomcontrol":
						$this->panZoomControl = $varvalue[1];
						break;
					case "overviewmapcontrol":
						$this->overViewMapControl = $varvalue[1];
						break;
					case "scalecontrol":
						$this->scaleControl = $varvalue[1];
						break;
					case "maptypecontrol":
						$this->mapTypeControl = $varvalue[1];
						break;
					case "mapnormal":
						$this->mapNormal = $varvalue[1];
						break;
					case "maphybrid":
						$this->mapHybrid = $varvalue[1];
						break;
					case "mapsat":
						$this->mapSatellite = $varvalue[1];
						break;
					case "mapearth":
						$this->mapEarth = $varvalue[1];
						break;
					case "mapphysical":
						$this->mapPhysical = $varvalue[1];
						break;
					case "maposm":
						$this->mapOSM = $varvalue[1];
						break;
					case "mapocm":
						$this->mapOCM = $varvalue[1];
						break;
					case "gpxinterval":
						if (is_numeric($varvalue[1])) {
							$this->gpxInterval = intval($varvalue[1]);
						}
						break;
					case "gpxcheckpointtable":
						$this->gpxCheckpointTable = $varvalue[1];
						break;
					case "gpxcolor":
						$this->gpxLineColor = xmlgooglemaps_helper::colorName2Hex($varvalue[1]);
						break;
					case "gpxelevationcolor":
						$this->gpxElevationLineColor = str_replace("#","",xmlgooglemaps_helper::colorName2Hex($varvalue[1]));
						break;
					case "gpxelevation":
						$this->gpxElevationChart = $varvalue[1];
						break;
					case "gpxelevationmeasurement":
					case "measurement":
						$this->measurement = $varvalue[1];
						break;
					case "speedscale":
						$this->speedscale = $varvalue[1];
						break;
					case "gpxspeedchartcolor":
						$this->gpxSpeedLineColor = str_replace("#","",xmlgooglemaps_helper::colorName2Hex($varvalue[1]));
						break;
					case "gpxspeedchart":
						$this->gpxSpeedChart = $varvalue[1];
						break;
					case "gpxheartratechartcolor":
						$this->gpxHeartRateLineColor = str_replace("#","",xmlgooglemaps_helper::colorName2Hex($varvalue[1]));
						break;
					case "gpxheartratechart":
						$this->gpxHeartRateChart = $varvalue[1];
						break;
					case "gpxlinewidth":
						if (is_numeric($varvalue[1])) {
							$this->gpxLineWidth = intval($varvalue[1]);
						}
						break;
					case "gpxelevationlinewidth":
						if (is_numeric($varvalue[1])) {
							$this->gpxElevationLineWidth = intval($varvalue[1]);
						}
						break;
					case "gpxelevationheight":
						if (is_numeric($varvalue[1])) {
							$this->gpxElevationChartHeight = intval($varvalue[1]);
						}
						break;
					case "gpxcheckpointinterval":
						if (is_numeric($varvalue[1])) {
							$this->gpxCheckpointInterval = floatval($varvalue[1]);
						}
						break;
					case "gpxcheckpointmarkercolor":
						$this->gpxCheckpointMarkerColor = $varvalue[1];
						break;
					case "gpxspeedchartlinewidth":
						if (is_numeric($varvalue[1])) {
							$this->gpxSpeedLineWidth = intval($varvalue[1]);
						}
						break;
					case "gpxspeedchartheight":
						if (is_numeric($varvalue[1])) {
							$this->gpxSpeedChartHeight = intval($varvalue[1]);
						}
						break;
					case "gpxheartratechartlinewidth":
						if (is_numeric($varvalue[1])) {
							$this->gpxHeartRateLineWidth = intval($varvalue[1]);
						}
						break;
					case "gpxheartbeatchartheight":
						if (is_numeric($varvalue[1])) {
							$this->gpxHeartRateChartHeight = intval($varvalue[1]);
						}
						break;
					case "gpxlineopacity":
						if (is_numeric($varvalue[1])) {
							$this->gpxLineOpacity = intval($varvalue[1]);
						}
						break;
					case "gpxdirectopenlink":
						$this->gpxDirectOpenWPLink = $varvalue[1];
						break;
					case "gpxview":
						$this->gpxView = $varvalue[1];
						break;
					case "maxlat":
						if (is_numeric($varvalue[1])) {
							$this->maxLat = doubleval($varvalue[1]);
							$this->manualZoom++;
						}
						break;
					case "maxlon":
						if (is_numeric($varvalue[1])) {
							$this->maxLon = doubleval($varvalue[1]);
							$this->manualZoom++;
						}
						break;
					case "minlat":
						if (is_numeric($varvalue[1])) {
							$this->minLat = doubleval($varvalue[1]);
							$this->manualZoom++;
						}
						break;
					case "minlon":
						if (is_numeric($varvalue[1])) {
							$this->minLon = doubleval($varvalue[1]);
							$this->manualZoom++;
						}
						break;
					case "zoom":
						if (is_numeric($varvalue[1])) {
							$this->zoom = intval($varvalue[1]);
						}
						break;
					case "deltazoom":
						if (is_numeric($varvalue[1])) {
							$this->deltazoom = intval($varvalue[1]);
						}
						break;
				}
			}
		}
	}
}

?>