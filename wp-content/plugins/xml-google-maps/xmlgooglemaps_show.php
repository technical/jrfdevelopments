<?php
/*
 * Diese Datei enthält die Anzeige der Karte
 */
//Systemklassen
require_once('../../../wp-config.php');
require_once('../../../wp-includes/functions.php');
//Google Maps Klassen hinzuladen
require_once(dirname(__FILE__).'/xmlgooglemaps_googleMapsAPI.php');
require_once(dirname(__FILE__).'/xmlgooglemaps_helper.php');
require_once(dirname(__FILE__).'/xmlgooglemaps_params.php');
require_once(dirname(__FILE__).'/xmlgooglemaps_nggFunctions.php');
require_once(dirname(__FILE__).'/xmlgooglemaps_exif.php');
require_once(dirname(__FILE__).'/xmlgooglemaps_dbfunctions.php');
require_once(dirname(__FILE__).'/xmlgooglemaps_gpxParser.php');
require_once(dirname(__FILE__).'/xmlgooglemaps_gpxParser2.php');
require_once(dirname(__FILE__).'/xmlgooglemaps_kmlParser.php');

$error = "";
$output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'."\n";;
$output .= '<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" style="height: 100%">'."\n";;
$output .= '<head>'."\n";;
$output .= '<meta http-equiv="content-type" content="text/html; charset=utf-8" />'."\n";;
$output .= '<title>Google Maps</title>'."\n";;



$id = -1;
$fullscreen=0;
if (isset($_GET["gpxid"])) {
	$id = intval($_GET["gpxid"]);
} else {
	if (isset($_GET["kmlid"])) {
		$id = intval($_GET["kmlid"]);
	} else {
		if (isset($_GET["mygooglemapid"])) {
			$id = intval($_GET["mygooglemapid"]);
		} else {
			if (isset($_GET["myid"])) {
				$id = intval($_GET["myid"]);
			}
		}
	}
}
$fullscreen=0;
if (isset($_GET["fullscreen"])) {
	$fullscreen = intval($_GET["fullscreen"]);
}

$varstring = xmlgooglemaps_dbfunctions::xmlGetString($id);
$params = new xmlgooglemaps_params();
$params->setParams($varstring);

$cacheKey = "map".$fullscreen;
$cache = xmlgooglemaps_dbfunctions::cacheGet($id,$cacheKey,$params->cacheDuration);
if ($cache === false) {
	$googlemaps = new xmlgooglemaps_googleMapAPI($params->googleMapsAPIKey);
	$googlemaps->setFullscreen($fullscreen);
	$googlemaps->width = "100%";
	$googlemaps->height = "100%";
	$googlemaps->setPanZoomControl($params->panZoomControl);
	$googlemaps->setOverviewMap($params->overViewMapControl);
	$googlemaps->setScaleControl($params->scaleControl);
	$googlemaps->setMapTypeControl($params->mapTypeControl);
	$googlemaps->setMapTypeControlButton("G_NORMAL_MAP",$params->mapNormal);
	$googlemaps->setMapTypeControlButton("G_HYBRID_MAP",$params->mapHybrid);
	$googlemaps->setMapTypeControlButton("G_PHYSICAL_MAP",$params->mapPhysical);
	$googlemaps->setMapTypeControlButton("G_SATELLITE_MAP",$params->mapSatellite);
	$googlemaps->setMapTypeControlButton("G_SATELLITE_3D_MAP",$params->mapEarth);
	$googlemaps->setMapTypeControlButton("USER_MAP_OSM",$params->mapOSM);
	$googlemaps->setMapTypeControlButton("USER_MAP_OCM",$params->mapOCM);
	$googlemaps->setMapType($params->viewStyle);
	$googlemaps->setContinuousZoom($params->continuousZoom);
	$googlemaps->setDragging($params->dragging);
	$googlemaps->setScrollWheelZoom($params->scrollWheelZoom);
	$googlemaps->setDoubleClickZoom($params->doubleClickZoom);
	$googlemaps->setGoogleBar($params->googleBar);

	//NGGallery Feature if Images are Geocoded
	if ($params->getNggCount()>0) {
		$ngg = new xmlgooglemaps_nggFunctions();
		
		if ($ngg->isNggInstalled()) {
			
			if ($ngg->isExifInstalled()) {
				$rows = $ngg->getNggPics($params->nggalGalleries,$params->nggalPics);
				foreach ($rows as $row) {
// 					Direct access to database by Patrick Matusz
// 					$creationdate = date_i18n(get_option('date_format').' '.get_option('time_format'), $row["creation"]);
					$creationdate = date_i18n(get_option('date_format').' '.get_option('time_format'), strtotime( $row["nggimage"]->imagedate ));
					$lat = xmlgooglemaps_helper::latitude2Text($row["gpslat"]);
					$lon = xmlgooglemaps_helper::longitude2Text($row["gpslon"]);
					$html = $params->nggalMarkerTemplate;
// 					Direct access to database by Patrick Matusz
// 					$html = str_replace("%FULLPATH%",$row["path"],$html);
// 					$html = str_replace("%THUMBPATH%",$row["thumbpath"], $html);
// 					$html = str_replace("%TITLE%",$row["title"],$html);
// 					$html = str_replace("%DESCRIPTION%", $row["description"], $html);
					$html = str_replace("%FULLPATH%",$row["nggimage"]->imageURL,$html);
					$html = str_replace("%THUMBPATH%",$row["nggimage"]->thumbURL, $html);
					$html = str_replace("%TITLE%",$row["nggimage"]->altext,$html);
					$html = str_replace("%DESCRIPTION%", $row["nggimage"]->description, $html);
					$html = str_replace("%CREATIONDATE%", $creationdate, $html);
					$html = str_replace("%LATITUDE%", $lat, $html);
					$html = str_replace("%LONGITUDE%", $lon, $html);
// 					Direct access to database by Patrick Matusz
// 					$googlemaps->addMarker($row["gpslat"],$row["gpslon"],$html,"","",$row["thumbpath"],$row["thumbpath"],32,32,32,32,16,16);
					$googlemaps->addMarker($row["gpslat"],$row["gpslon"],$html,"","",$row["nggimage"]->thumbURL,$row["nggimage"]->thumbURL,32,32,32,32,16,16);
				}
			} else {
				$error .= "Your PHP installation on your webserver doesn&#39;t support EXIF functions. This plugin needs these functions to read the coordinates from the image file. Please consult <a href=\"http://ch2.php.net/manual/en/book.exif.php\" target=\"_blank\">http://ch2.php.net/manual/en/book.exif.php</a> for more information!<br />";
			}
		} else {
			$error .= "NextGEN Gallery Plugin is not installed or activated!<br />";
		}
	}

	$output .= $googlemaps->getHeaderScript().''."\n";;

	$bannerbottom = max(0,min($params->height - 175, 50));

	$output .= '<style type="text/css">'."\n";;
	$output .= 'p.entry { '.$params->gpxCSSStyle.'}'."\n";;
	$output .= '</style>'."\n";;
	$output .= '</head>'."\n";;
	$output .= '<body style="margin: 0; height: 100%; overflow: hidden;" onunload="GUnload();">'."\n";;

	//GPX Files!
	if (isset($_GET["gpxid"])) {
		$url = xmlgooglemaps_dbfunctions::xmlGetPath($id);		
		if ($params->gpxParseMode==1) {
			$gpxfile = new xmlgooglemaps_gpxParser();
		} else {
			$gpxfile = new xmlgooglemaps_gpxParser2();			
		}
		if ($gpxfile->hasToParse($url)===true) {
			$gpxfile->parse($url);
		}
		
		$gpxdata = $gpxfile->output;
		
		$counter = 0;
		//Waypoints
		if (($params->gpxView == "all") || ($params->gpxView == "wpt")) {
			//GPX Mode 1
			if ($params->gpxParseMode==1) { 			
				$maxwpt = $gpxdata["wpt_count"];
				for ($counter=0; $counter<$maxwpt; $counter++) {
					$desc = $params->gpxWaypointMarkerTemplate;
					$lat = xmlgooglemaps_helper::latitude2Text($gpxdata["wpt".$counter]["lat"]);
					$lon = xmlgooglemaps_helper::longitude2Text($gpxdata["wpt".$counter]["lon"]);
					$elevation = xmlgooglemaps_helper::elevation2Text($gpxdata["wpt".$counter]["ele"],$params->measurement);
					$desc = str_replace("%NAME%",$gpxdata["wpt".$counter]["name"],$desc);
					$desc = str_replace("%DESCRIPTION%",$gpxdata["wpt".$counter]["desc"],$desc);
					$desc = str_replace("%LINK%",$gpxdata["wpt".$counter]["link"],$desc);
					$desc = str_replace("%LATITUDE%", $lat, $desc);
					$desc = str_replace("%LONGITUDE%", $lon, $desc);
					$desc = str_replace("%ELEVATION%", $elevation, $desc);
					$googlemaps->addMarker($gpxdata["wpt".$counter]["lat"],$gpxdata["wpt".$counter]["lon"],$desc,$gpxdata["wpt".$counter]["link"],$params->gpxDirectOpenWPLink);
				}
			//GPX Mode 2
			} else if ($params->gpxParseMode==2) { 
				$maxwpt = xmlgooglemaps_dbfunctions::gpxGetMainItemCount($gpxfile->gpxfileid,'WPT');
				for ($counter=0; $counter<$maxwpt; $counter++, $countersub++) {
					if ($counter % $params->gpxParseMode2MaxLimit == 0) {
						$wpts = xmlgooglemaps_dbfunctions::gpxGetMainItems($gpxfile->gpxfileid,'WPT',$counter,$params->gpxParseMode2MaxLimit);
						$countersub=0;
					}					
					$desc = $params->gpxWaypointMarkerTemplate;
					$lat = xmlgooglemaps_helper::latitude2Text($wpts[$countersub]->lat);
					$lon = xmlgooglemaps_helper::longitude2Text($wpts[$countersub]->lon);
					$elevation = xmlgooglemaps_helper::elevation2Text($wpts[$countersub]->ele,$params->measurement);
					$desc = str_replace("%NAME%",$wpts[$countersub]->name,$desc);
					$desc = str_replace("%DESCRIPTION%",$wpts[$countersub]->description,$desc);
					$desc = str_replace("%LINK%",$wpts[$countersub]->link,$desc);
					$desc = str_replace("%LATITUDE%", $lat, $desc);
					$desc = str_replace("%LONGITUDE%", $lon, $desc);
					$desc = str_replace("%ELEVATION%", $elevation, $desc);
					$googlemaps->addMarker($wpts[$countersub]->lat,$wpts[$countersub]->lon,$desc,$wpts[$countersub]->link,$params->gpxDirectOpenWPLink);
				}				
			}				
		}
		//Routen
		if (($params->gpxView == "all") || ($params->gpxView == "rte")) {			
			//GPX Mode 1
			if ($params->gpxParseMode==1) { 
				$rtemax = $gpxdata["rte_count"];
				for ($rtecount=0;$rtecount<$rtemax; $rtecount++) {
					$rteptmax = $gpxdata["rte".$rtecount]["rtept_count"];
					$pl = $googlemaps->addPolyline($params->gpxLineColor,$params->gpxLineWidth,$params->gpxLineOpacity);
					for ($rteptcount=0;$rteptcount<$rteptmax; $rteptcount+=$params->gpxInterval) {
						$pt = $gpxdata["rte".$rtecount]["rtept".$rteptcount];
						$googlemaps->addPolylinePoint($pl,$pt["lat"],$pt["lon"]);
					}	
					if ($rteptcount < $rteptmax - 1) {
						$rteptcount = $rteptmax - 1;
						$pt = $gpxdata["rte".$rtecount]["rtept".$rteptcount];
						$googlemaps->addPolylinePoint($pl,$pt["lat"],$pt["lon"]);
					}
				}
			//GPX Mode 2
			} else if ($params->gpxParseMode==2) { 
				$rtemax = xmlgooglemaps_dbfunctions::gpxGetMainItemCount($gpxfile->gpxfileid,'RTE');
				for ($rtecount=0;$rtecount<$rtemax; $rtecount++) {
					$rterow = xmlgooglemaps_dbfunctions::gpxGetMainItem($gpxfile->gpxfileid,'RTE',$rtecount);
					$rteptmax = xmlgooglemaps_dbfunctions::gpxGetSubItemCount($gpxfile->gpxfileid,'RTEPT',$rterow->item_id);
										
					$pl = $googlemaps->addPolyline($params->gpxLineColor,$params->gpxLineWidth,$params->gpxLineOpacity);
					
					for ($rteptcount=0;$rteptcount<$rteptmax; $rteptcount++, $rteptcountsub++) {
						if ($rteptcount % $params->gpxParseMode2MaxLimit == 0) {
							$rtepts = xmlgooglemaps_dbfunctions::gpxGetSubItems($gpxfile->gpxfileid,'RTEPT',$rterow->item_id,$rteptcount,$params->gpxParseMode2MaxLimit);
							$rteptcountsub=0;
						}
						if (($rteptcount % $params->gpxInterval == 0) || ($rteptcount == $rteptmax-1)) {
							$googlemaps->addPolylinePoint($pl,$rtepts[$rteptcountsub]->lat,$rtepts[$rteptcountsub]->lon);
						}
					}
				}				
			}
		}
		//Tracks
		if (($params->gpxView == "all") || ($params->gpxView == "trk")) {
			switch ($params->measurement) {
				case "imperial":
					$divident = 1.609344;
					break;
				case "nautic_imperial":
				case "nautic_metric":
					$divident = 1.852216;
					break;
				default:
					$divident = 1;
					break;
			}
			$dist = 0;
			$nextcheckpointnumber=1;
			$lastpt = "";
			//GPX Mode 1
			if ($params->gpxParseMode==1) { 			
				$trkmax = $gpxdata["trk_count"];
				for ($trkcount=0;$trkcount<$trkmax; $trkcount++) {
					$trkptmax=$gpxdata["trk".$trkcount]["trkpt_count"];
					$pl = $googlemaps->addPolyline($params->gpxLineColor,$params->gpxLineWidth,$params->gpxLineOpacity);
					for ($trkptcount=0;$trkptcount<$trkptmax; $trkptcount+=$params->gpxInterval) {
						$pt = $gpxdata["trk".$trkcount]["trkpt".$trkptcount];
						$googlemaps->addPolylinePoint($pl,$pt["lat"],$pt["lon"]);
						if (($params->gpxCheckpointInterval>0)) {
							if (($trkcount > 0) || ($trkptcount>0)) {
								$dist += xmlgooglemaps_helper::distBetween2PointsKM(floatval($lastpt["lat"]),floatval($lastpt["lon"]),floatval($pt["lat"]),floatval($pt["lon"]));
								if ($dist/$divident>$nextcheckpointnumber*$params->gpxCheckpointInterval) {
									$googlemaps->addMarker($pt["lat"],$pt["lon"],"","","","icons/mm_20_".$params->gpxCheckpointMarkerColor.".png","icons/mm_20_shadow.png");
									$nextcheckpointnumber = floor(($dist/$divident) / $params->gpxCheckpointInterval) + 1;
								}
							} else {
								$googlemaps->addMarker($pt["lat"],$pt["lon"],"","","","icons/mm_20_".$params->gpxCheckpointMarkerColor."_s.png","icons/mm_20_shadow.png");
							}
						}
						$lastpt = $pt;
					}
					if ($trkptcount < $trkptmax - 1) {
						$trkptcount = $trkptmax - 1;
						$pt = $gpxdata["trk".$trkcount]["trkpt".$trkptcount];
						$googlemaps->addPolylinePoint($pl,$pt["lat"],$pt["lon"]);
					}
				}
				if (($params->gpxCheckpointInterval>0) && ($trkmax>0)) {
					$googlemaps->addMarker($pt["lat"],$pt["lon"],"","","","icons/mm_20_".$params->gpxCheckpointMarkerColor."_e.png","icons/mm_20_shadow.png");
				}
			//GPX Mode 2
			} else if ($params->gpxParseMode==2) { 
				$trkmax = xmlgooglemaps_dbfunctions::gpxGetMainItemCount($gpxfile->gpxfileid,'TRK');
				for ($trkcount=0;$trkcount<$trkmax; $trkcount++) {
					$trkrow = xmlgooglemaps_dbfunctions::gpxGetMainItem($gpxfile->gpxfileid,'TRK',$trkcount);
					$trkptmax = xmlgooglemaps_dbfunctions::gpxGetSubItemCount($gpxfile->gpxfileid,'TRKPT',$trkrow->item_id);
										
					$pl = $googlemaps->addPolyline($params->gpxLineColor,$params->gpxLineWidth,$params->gpxLineOpacity);
					
					for ($trkptcount=0;$trkptcount<$trkptmax; $trkptcount++, $trkptcountsub++) {
						//Alle x Punkte Daten neu aus DB laden
						if ($trkptcount % $params->gpxParseMode2MaxLimit == 0) {
							$trkpts = xmlgooglemaps_dbfunctions::gpxGetSubItems($gpxfile->gpxfileid,'TRKPT',$trkrow->item_id,$trkptcount,$params->gpxParseMode2MaxLimit);
							$trkptcountsub=0;
						}
						//Alle GPX Intervall Punkte zeichnen und ebenfalls den letzten Punkt zeichnen
						if (($trkptcount % $params->gpxInterval == 0) || ($trkptcount == $trkptmax-1)) {
							$googlemaps->addPolylinePoint($pl,$trkpts[$trkptcountsub]->lat,$trkpts[$trkptcountsub]->lon);							
						}
						//Checkpoints
						if (($params->gpxCheckpointInterval>0)) {
							if (($trkcount > 0) || ($trkptcount>0)) {
								$dist += xmlgooglemaps_helper::distBetween2PointsKM(floatval($lastpt->lat),floatval($lastpt->lon),floatval($trkpts[$trkptcountsub]->lat),floatval($trkpts[$trkptcountsub]->lon));
								if ($dist/$divident>$nextcheckpointnumber*$params->gpxCheckpointInterval) {
									$googlemaps->addMarker($trkpts[$trkptcountsub]->lat,$trkpts[$trkptcountsub]->lon,"","","","icons/mm_20_".$params->gpxCheckpointMarkerColor.".png","icons/mm_20_shadow.png");
									$nextcheckpointnumber = floor(($dist/$divident) / $params->gpxCheckpointInterval) + 1;
								} else if ($trkptcount == $trkptmax-1) {
									$googlemaps->addMarker($trkpts[$trkptcountsub]->lat,$trkpts[$trkptcountsub]->lon,"","","","icons/mm_20_".$params->gpxCheckpointMarkerColor."_e.png","icons/mm_20_shadow.png");
								}
							} else {
								$googlemaps->addMarker($trkpts[$trkptcountsub]->lat,$trkpts[$trkptcountsub]->lon,"","","","icons/mm_20_".$params->gpxCheckpointMarkerColor."_s.png","icons/mm_20_shadow.png");
							}							
						}
						$lastpt = $trkpts[$trkptcountsub];						
					}
				}			
			}
		}
		if ($params->manualZoom == 4) {
			$googlemaps->clearCenterCoords();
			$googlemaps->adjustCenterCoords($params->maxLon,$params->maxLat);
			$googlemaps->adjustCenterCoords($params->minLon,$params->minLat);
		}
		$googlemaps->setZoom($params->zoom);
		$googlemaps->setDeltaZoom($params->deltazoom);
		if (strlen($error)==0) {
			$output .= $googlemaps->getContentElement().''."\n";;
		}
	} else {
		//KML Files
		if (isset($_GET["kmlid"])) {
			$url = xmlgooglemaps_dbfunctions::xmlGetPath($id);
			if (strtolower($params->kmlParsing) != "disabled") {				
				$kmlfile = new xmlgooglemaps_kmlParser();
				$kmlfile->parse($url);

				$kmldata = $kmlfile->output;

				for ($i=0; $i<count($kmldata["coordinates"]); $i++)
				{
					$googlemaps->adjustCenterCoords($kmldata["coordinates"][$i]["lon"],$kmldata["coordinates"][$i]["lat"]);
				}
			}

			if ($params->manualZoom == 4) {
				$googlemaps->clearCenterCoords();
				$googlemaps->adjustCenterCoords($params->maxLon,$params->maxLat);
				$googlemaps->adjustCenterCoords($params->minLon,$params->minLat);
			}

			$file = xmlgooglemaps_dbfunctions::xmlGetPath($id);
			$googlemaps->setZoom($params->zoom);
			$googlemaps->setDeltaZoom($params->deltazoom);
			if (strlen($error)==0) {
				$output .= $googlemaps->getKMLContentElementOnly($file).''."\n";;
			}
		} else {
			//My Google Maps
			if (isset($_GET["mygooglemapid"])) {
				if ($params->manualZoom == 4) {
					$googlemaps->clearCenterCoords();
					$googlemaps->adjustCenterCoords($params->maxLon,$params->maxLat);
					$googlemaps->adjustCenterCoords($params->minLon,$params->minLat);
				}
				$file = xmlgooglemaps_dbfunctions::xmlGetPath($id);
				$googlemaps->setZoom($params->zoom);
				$googlemaps->setDeltaZoom($params->deltazoom);
				if (strlen($error)==0) {
					$output .= $googlemaps->getKMLContentElementOnly($file).''."\n";;
				}
			} else {
				//XMLGM and other links
				if (isset($_GET["myid"])) {
					if ($params->manualZoom == 4) {
						$googlemaps->clearCenterCoords();
						$googlemaps->adjustCenterCoords($params->maxLon,$params->maxLat);
						$googlemaps->adjustCenterCoords($params->minLon,$params->minLat);
					}
					$path = xmlgooglemaps_dbfunctions::xmlGetPath($id);
					$googlemaps->setZoom($params->zoom);
					$googlemaps->setDeltaZoom($params->deltazoom);
					if (strlen($error)==0) {
						if (strlen($path)>0) {
							$output .= $googlemaps->getKMLContentElementOnly($path).''."\n";;
						} else {
							$output .= $googlemaps->getContentElement().''."\n";;
						}
					}
				}
			}
		}
	}
	if (strlen($error)>0) {
		$output .= '<p style="color: red; margin-left: 24px; text-align: justify;"><img alt="Error" src="icons/error48.png" style="float: left; margin-right: 10px;"><img alt="Error" src="icons/error48.png" style="float: right; margin-left: 10px; ">'.$error.'</p>'."\n";;
	}
	$output .= '</body>'."\n";;
	$output .= '</html>'."\n";;

	xmlgooglemaps_dbfunctions::cacheSet($id,$cacheKey,$output);	
	echo $output;
} else {
	echo $cache;
}

	?>