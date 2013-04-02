<?php
/*
 Plugin Name: XML Google Maps
 Plugin URI: http://www.matusz.ch/blog/projekte/xml-google-maps-wordpress-plugin/
 Feed URI: http://www.matusz.ch/blog/tags/xml-google-maps-wp-plugin/feed/
 Description: This plugin allows you to easily insert Google Map or Google Earth Plugin Maps into your blog. Supports KML, KMZ, GPX, geoRSS filelinks. Supports Default, Satellite, Hybrid, Physical and Google Earth Plugin Maptypes
 Version: 1.14.0
 Author: Patrick Matusz
 Author URI: http://www.matusz.ch/blog/
 */

global $wpdb;

//Versionsinformationen
define("XML_GOOGLE_MAPS_VERSION","1.14.0",true);
define("XML_GOOGLE_MAPS_DB_VERSION",2,true);

//Tabellennamen
$wpdb->xmlgooglemapsdb = $table_prefix . 'xmlgooglemaps';
$wpdb->xmlgooglemapsdbcache = $table_prefix . 'xmlgooglemaps_cache';
$wpdb->xmlgooglemapsdbgpxfile = $table_prefix . 'xmlgooglemaps_gpxfile';
$wpdb->xmlgooglemapsdbgpxfileitem = $table_prefix . 'xmlgooglemaps_gpxfile_item';

////Google Maps Klassen hinzuladen
require_once(dirname(__FILE__).'/xmlgooglemaps_helper.php');
require_once(dirname(__FILE__).'/xmlgooglemaps_dbfunctions.php');
require_once(dirname(__FILE__).'/xmlgooglemaps_params.php');
require_once(dirname(__FILE__).'/xmlgooglemaps_gpxParser.php');
require_once(dirname(__FILE__).'/xmlgooglemaps_gpxParser2.php');
require_once(dirname(__FILE__).'/xmlgooglemaps_googleChartsAPI.php');
require_once(dirname(__FILE__).'/xmlgooglemaps_nggFunctions.php');


//Initialisierung Optionen
require_once dirname(__FILE__).'/xmlgooglemaps_optionsinit.php';

//Actions & Filters
add_filter('the_content', 'xmlgooglemaps_converter', 2);
add_filter('upload_mimes', 'xmlgooglemaps_mimes');
add_action('admin_menu', 'add_xmlgm_option_page');
add_filter('plugin_row_meta','xmlgooglemaps_shortcuts' ,10,2);

//Add Option Page
function add_xmlgm_option_page() {
	global $wpdb;
	add_options_page('XML Google Maps Options', 'XML Google Maps', 8, basename(__FILE__), 'xmlgm_options_page');
}

//Ersetzt Text mit Karten
function xmlgooglemaps_converter($content) {
	global $wpdb;	

	xmlgooglemaps_dbfunctions::install();
	xmlgooglemaps_helper::clearCacheAfterUpdate();

	$content = str_replace("\n","####nnnn####", $content);
	$content = str_replace("\r","####rrrr####", $content);

	//GPX
	$content = xmlgooglemaps_converter_gpx($content);

	//KMX
	$content = xmlgooglemaps_converter_kmx($content);

	// Google Maps Meine Karten: http://maps.google.com/maps/ms?ie=UTF8&hl=de&msa=0&output=nl&msid=102758581694521177745.00000111d56d20b14f816
	$content = xmlgooglemaps_converter_GoogleMaps($content);

	// Picasa Maps
	$content = xmlgooglemaps_converter_Picasa($content);

	//XML Google Maps Parts
	$content = xmlgooglemaps_converter_myxmlgm($content);

	$content = str_replace("####nnnn####","\n", $content);
	$content = str_replace("####rrrr####","\r", $content);

	return $content;
}

function xmlgooglemaps_mimes($mimes) {
	$mime_types = array ('gpx' => 'application/gpx', 'kml' => 'application/vnd.google-earth.kml+xml', 'kmz' => 'application/vnd.google-earth.kmz+xml');
	return array_merge($mimes,$mime_types);
}

function xmlgooglemaps_converter_Picasa($content) {
	
	preg_match_all('#\<a([^\>]*?)href=["\'](https?:\/\/picasaweb\.google\.[a-z\.]*\/data\/feed\/base\/user\/[a-zA-Z0-9\/:\-\.,_?&=;~]*?)["\']([^\>]*?)\>(.*?)\<\/a\>#i', $content, $found);

	for ($i=0; $i< count($found[0]);$i++) {
		$file = $found[2][$i];
		$file = str_replace("&amp;","&",$file);

		$displayurl = $found[0][$i];
		$urlstring = $found[4][$i];

		$iurlstring = stripos($urlstring,"[");
		if ($iurlstring !== false) {
			$jurlstring = stripos($urlstring,"]",$iurlstring);
			$iurlstring += 1;
			$varstring = substr($urlstring, $iurlstring, $jurlstring-$iurlstring);
		} else {
			$varstring = "";
		}

		$params = new xmlgooglemaps_params();
		$params->setParams($varstring);

		$displayurl = getDisplayURL($params->urlDisplayMode, $displayurl, $varstring);

		if (strtolower($params->urlDisplayMode) == "nomap") {
			$content = str_replace($found[0][$i],$displayurl,$content);
		} else {
			$mygooglemapid = xmlgooglemaps_dbfunctions::xmlGetId($file, $varstring);
			$content = str_replace($found[0][$i],'<div '.$params->getStyleMapDiv().' class="xmlgmdiv" id="xmlgmdiv_'.$mygooglemapid.'"><iframe class="xmlgm" id="xmlgm_'.$mygooglemapid.'" src="'.get_option('siteurl').'/wp-content/plugins/xml-google-maps/xmlgooglemaps_show.php?mygooglemapid='.$mygooglemapid.'" style="border: 0px; width: '.$params->width.'px; height: '.$params->height.'px;" name="Google_My_Map" frameborder="0"></iframe></div>'.$displayurl,$content);
		}
	}
	return $content;
}

function xmlgooglemaps_converter_GoogleMaps($content) {
	
	preg_match_all('#\<a([^\>]*?)href=["\'](https?:\/\/maps\.google\.[a-z\.]*\/maps\/ms[a-zA-Z0-9\/:\-\.,_?&=;~]*?)["\']([^\>]*?)\>(.*?)\<\/a\>#i', $content, $found);

	for ($i=0; $i< count($found[0]);$i++) {
		$file = $found[2][$i];
		$file = str_replace("&amp;","&",$file);

		if (stripos(strtolower($file),"msid=") !== false) {
				
			if (stripos(strtolower($file),"output=") === false) {
				$file .= "&output=nl";
			}

				
			$displayurl = $found[0][$i];
			$urlstring = $found[4][$i];
				
			$iurlstring = stripos($urlstring,"[");
			if ($iurlstring !== false) {
				$jurlstring = stripos($urlstring,"]",$iurlstring);
				$iurlstring += 1;
				$varstring = substr($urlstring, $iurlstring, $jurlstring-$iurlstring);
			} else {
				$varstring = "";
			}
				
			$params = new xmlgooglemaps_params();
			$params->setParams($varstring);
			$displayurl = getDisplayURL($params->urlDisplayMode, $displayurl, $varstring);
			if (strtolower($params->urlDisplayMode) == "nomap") {
				$content = str_replace($found[0][$i],$displayurl,$content);
			} else {
				$mygooglemapid = xmlgooglemaps_dbfunctions::xmlGetId($file, $varstring);
				$content = str_replace($found[0][$i],'<div '.$params->getStyleMapDiv().' class="xmlgmdiv" id="xmlgmdiv_'.$mygooglemapid.'"><iframe class="xmlgm" id="xmlgm_'.$mygooglemapid.'" src="'.get_option('siteurl').'/wp-content/plugins/xml-google-maps/xmlgooglemaps_show.php?mygooglemapid='.$mygooglemapid.'" style="border: 0px; width: '.$params->width.'px; height: '.$params->height.'px;" name="Google_My_Map" frameborder="0"></iframe></div>'.$displayurl,$content);
			}
		} else {
			$content = str_replace($found[0][$i],'<p style="color: red;"><b>Error in displaying map:</b><br />I think your link to the My Google Maps is not correct. Please have a look at the following image:<br /><img alt="Help" src="'.get_option('siteurl').'/wp-content/plugins/xml-google-maps/images/help01.jpg" /><br />You have to save your Google Map first!</p>'.$displayurl,$content);
		}
	}
	return $content;
}

function xmlgooglemaps_converter_kmx($content) {
	
	preg_match_all('#\<a([^\>]*?)href=["\']([a-zA-Z0-9@\/:\-\._?&=;~]*?\.km[lz])["\']([^\>]*?)\>(.*?)\<\/a\>#i', $content, $found);
		
	for ($i=0; $i< count($found[0]);$i++) {
		$file = $found[2][$i];

		$displayurl = $found[0][$i];
		$urlstring = $found[4][$i];
		$iurlstring = stripos($urlstring,"[");
		if ($iurlstring !== false) {
			$jurlstring = stripos($urlstring,"]",$iurlstring);
			$iurlstring += 1;
			$varstring = substr($urlstring, $iurlstring, $jurlstring-$iurlstring);
		} else {
			$varstring = "";
		}

		$params = new xmlgooglemaps_params();
		$params->setParams($varstring);
		$displayurl = getDisplayURL($params->urlDisplayMode, $displayurl, $varstring);
		if (strtolower($params->urlDisplayMode) == "nomap") {
			$content = str_replace($found[0][$i],$displayurl,$content);
		} else {
			$kmlid = xmlgooglemaps_dbfunctions::xmlGetId($file, $varstring);
			$content = str_replace($found[0][$i],'<div '.$params->getStyleMapDiv().' class="xmlgmdiv" id="xmlgmdiv_'.$kmlid.'"><iframe class="xmlgm" id="xmlgm_'.$kmlid.'" src="'.get_option('siteurl').'/wp-content/plugins/xml-google-maps/xmlgooglemaps_show.php?kmlid='.$kmlid.'" style="border: 0px; width: '.$params->width.'px; height: '.$params->height.'px;" name="Google_KML_Maps" frameborder="0"></iframe></div>'.$displayurl,$content);
		}
	}
	return $content;
}

function xmlgooglemaps_converter_myxmlgm($content) {

	preg_match_all('#\[xmlgm([^\]]*?)\]#i', $content, $found);
	
	for ($i=0; $i< count($found[0]);$i++) {
		$varstring = str_replace("[xmlgm","", $found[0][$i]);
		$varstring = str_replace("]","", $varstring);
		$varstring = trim($varstring);

		//Support added for any other GeoXML [xmlgm {url} parameter]
		$pathi = stripos($varstring,"{");
		$pathj = stripos($varstring,"}");

		if (($pathi !== false) && ($pathj !== false)) {
			$path = trim(substr($varstring,$pathi+1,$pathj-$pathi-1));
			$varstring = trim(substr($varstring,0,$pathi).substr($varstring,$pathj+1));
		}

		$params = new xmlgooglemaps_params();
		$params->setParams($varstring);

		if (strtolower($params->urlDisplayMode) != "nomap") {
			$myid = xmlgooglemaps_dbfunctions::xmlGetId($path, $varstring);
				
			$content = str_replace($found[0][$i],'<div '.$params->getStyleMapDiv().' class="xmlgmdiv" id="xmlgmdiv_'.$myid.'"><iframe class="xmlgm" id="xmlgm_'.$myid.'" src="'.get_option('siteurl').'/wp-content/plugins/xml-google-maps/xmlgooglemaps_show.php?myid='.$myid.'" style="border: 0px; width: '.$params->width.'px; height: '.$params->height.'px;" name="My_XML_Google_Maps" frameborder="0"></iframe></div>',$content);
		}
	}

	return $content;
}

function xmlgooglemaps_converter_gpx($content) {
	
	
	preg_match_all('#\<a([^\>]*?)href=["\']([a-zA-Z0-9@\/:\-\._?&=;~]*?\.gpx)["\']([^\>]*?)\>(.*?)\<\/a\>#i', $content, $found);

	for ($i=0; $i< count($found[0]);$i++) {
		$file = $found[2][$i];

		$urlstring = $found[4][$i];
		$iurlstring = stripos($urlstring,"[");
		$displayurl = $found[0][$i];
		if ($iurlstring !== false) {
			$jurlstring = stripos($urlstring,"]",$iurlstring);
			$iurlstring += 1;
			$varstring = substr($urlstring, $iurlstring, $jurlstring-$iurlstring);
		} else {
			$varstring = "";
		}

		$params = new xmlgooglemaps_params();
		$params->setParams($varstring);
		$displayurl = getDisplayURL($params->urlDisplayMode, $displayurl, $varstring);
		if (strtolower($params->urlDisplayMode) == "nomap") {
			$content = str_replace($found[0][$i],$displayurl,$content);
		} else {
			$gpxid = xmlgooglemaps_dbfunctions::xmlGetId($file, $varstring);
			
			$cachekey = "content";
			$cachestring = xmlgooglemaps_dbfunctions::cacheGet($gpxid,$cachekey,$params->cacheDuration);
			if ($cachestring === false) { 			
				//Altitudes / Speed Charts
				$altstring = "";
				$speedstring = "";
				$cptabstring = "";
				$heartratestring = "";
				if (($params->gpxElevationChart == "show") || ($params->gpxSpeedChart == "show") || ($params->gpxCheckpointTable == "show") || ($params->gpxHeartRateChart == "show") ) {
					//GPX Mode 1
					if ($params->gpxParseMode==1) { 					
						$gpxfile = new xmlgooglemaps_gpxParser();
					//GPX Mode 2
					} else if ($params->gpxParseMode==2) {  
						$gpxfile = new xmlgooglemaps_gpxParser2();
					}
					if ($gpxfile->hasToParse($file)===true) {
						$gpxfile->parse($file);
					}
					
					$gpxdata = $gpxfile->output;
					if (($params->gpxView == "all") || ($params->gpxView == "trk")) {						
						$x_ele = array();
						$y_ele = array();
						$dist_speed = array();
						$time_speed = array();
						$y_heartrate = array();
						$has_heartrates = 0;
						$ptcount = 0;
						$dist = 0;
						$lastele = -9999;
						//GPX Mode 1
						if ($params->gpxParseMode==1) { 					
							$trkmax = $gpxdata["trk_count"];
						//GPX Mode 2
						} else if ($params->gpxParseMode==2) {  
							$trkmax = xmlgooglemaps_dbfunctions::gpxGetMainItemCount($gpxfile->gpxfileid,'TRK');							
						}							
						for ($trkcount=0;$trkcount<$trkmax; $trkcount++) {
							//GPX Mode 1
							if ($params->gpxParseMode==1) { 					
								$trkptmax=$gpxdata["trk".$trkcount]["trkpt_count"];
							//GPX Mode 2
							} else if ($params->gpxParseMode==2) {  
								$trkrow = xmlgooglemaps_dbfunctions::gpxGetMainItem($gpxfile->gpxfileid,'TRK',$trkcount);
								$trkptmax = xmlgooglemaps_dbfunctions::gpxGetSubItemCount($gpxfile->gpxfileid,'TRKPT',$trkrow->item_id);
							}							
							
							for ($trkptcount=0;$trkptcount<$trkptmax; $trkptcount++, $trkptcountsub++) {
								//GPX Mode 1
								if ($params->gpxParseMode==1) { 													
									$pt = $gpxdata["trk".$trkcount]["trkpt".$trkptcount];
									if ($ptcount==0) {
										$lat1 = floatval($pt["lat"]);
										$lon1 = floatval($pt["lon"]);
										$time1 = xmlgooglemaps_helper::xmlDate2DateTime($pt["time"]);
									}
									$lat2 = floatval($pt["lat"]);
									$lon2 = floatval($pt["lon"]);
									$time2 = xmlgooglemaps_helper::xmlDate2DateTime($pt["time"]);
									$ele = intval($pt["ele"]);
									if (isset($pt["heartrate"])) {
										$heartrate = floatval($pt["heartrate"]);
										$has_heartrates++;
									} else {
										$heartrate = 0;
									}								
								//GPX Mode 2
								} else if ($params->gpxParseMode==2) {  
									if ($trkptcount % $params->gpxParseMode2MaxLimit == 0) {
										$trkpts = xmlgooglemaps_dbfunctions::gpxGetSubItems($gpxfile->gpxfileid,'TRKPT',$trkrow->item_id,$trkptcount,$params->gpxParseMode2MaxLimit);
										$trkptcountsub=0;
									}	
									if ($ptcount==0) {
										$lat1 = floatval($trkpts[$trkptcountsub]->lat);
										$lon1 = floatval($trkpts[$trkptcountsub]->lon);
										$time1 = xmlgooglemaps_helper::xmlDate2DateTime($trkpts[$trkptcountsub]->time);
									}
									$lat2 = floatval($trkpts[$trkptcountsub]->lat);
									$lon2 = floatval($trkpts[$trkptcountsub]->lon);
									$time2 = xmlgooglemaps_helper::xmlDate2DateTime($trkpts[$trkptcountsub]->time);
									$ele = intval($trkpts[$trkptcountsub]->ele);
									if (is_numeric($trkpts[$trkptcountsub]->heartrate)) {
										$heartrate = floatval($trkpts[$trkptcountsub]->heartrate);
										$has_heartrates++;
									} else {
										$heartrate = 0;
									}																		
								}									
								$tmp = xmlgooglemaps_helper::distBetween2PointsKM($lat1,$lon1,$lat2,$lon2);
								if (!is_nan($tmp)) {
									$dist += $tmp;
									if (($tmp>0) && ($time2-$time1>0)) {
										$dist_speed[] = $tmp;
										$time_speed[] = $time2-$time1;
									}
									
									$y_heartrate[] = $heartrate;
									
									if ($lastele != $ele) {
										switch ($params->measurement) {
											case "imperial":
												$x_ele[] = $dist/1.609344;
												$y_ele[] = $ele/0.3048;										
												break;
											case "nautic_imperial":
												$x_ele[] = $dist/1.852216;
												$y_ele[] = $ele/0.3048;										
												break;
											case "nautic_metric":
												$x_ele[] = $dist/1.852216;
												$y_ele[] = $ele;										
												break;
											case "metric":
											default:
												$x_ele[] = $dist;
												$y_ele[] = $ele;										
												break;
										}
									}
									$lastele = $ele;
									$lat1 = $lat2;
									$lon1 = $lon2;
									$time1 = $time2;
								}
								$ptcount++;
							}
						}
						if ($dist>0) {
							//Elevation Chart
							if ($params->gpxElevationChart == "show") {
								$alt = new xmlgooglemaps_googleChartsApi($x_ele,$y_ele);
								$altstring .= '<img class="xmlgmele" id="xmlgmele_'.$gpxid.'" '.$params->getStyleImgDiv().' alt="'.$params->gpxElevationChartTitle.'" src="'.$alt->getEleUrl($params->width, $params->gpxElevationChartHeight,$params->gpxElevationLineColor,$params->gpxElevationLineWidth, $params->measurement, $params->gpxElevationChartTitle, $params->gpxCheckpointInterval).'" /><br />';
							}				
							//Speedchart
							if ($params->gpxSpeedChart == "show") {
								$newdist=0;
								$newtime=0;
								$newtotaldist=0;
								$x_speed = array();
								$y_speed = array();
								for ($spi=0;$spi<count($dist_speed); $spi++) {
									$newdist += $dist_speed[$spi];
									$newtotaldist += $dist_speed[$spi];
									$newtime += $time_speed[$spi];
									switch (strtolower($params->measurement.$params->speedscale)) {
										case "imperiallevel2":
											$x_speed[] = $newtotaldist/1.609344;
											$y_speed[] = ($newdist/1.609344) / ($newtime/3600);
											break;
										case "imperiallevel1":
											$x_speed[] = $newtotaldist/1.609344;
											$y_speed[] = ($newdist/0.3048) / ($newtime);
											break;
										case "metriclevel1":
											$x_speed[] = $newtotaldist;
											$y_speed[] = ($newdist*1000) / ($newtime);
											break;
										case "nautic_metriclevel1":
										case "nautic_metriclevel2":
										case "nautic_imperiallevel1":
										case "nautic_imperiallevel2":										
											$x_speed[] = $newtotaldist/1.852216;
											$y_speed[] = ($newdist/1.852216) / ($newtime/3600);
											break;
										default:
										case "metriclevel2":
											$x_speed[] = $newtotaldist;
											$y_speed[] = ($newdist) / ($newtime/3600);
											break;
									}
									$newdist=0;
									$newtime=0;
								}
								$sp = new xmlgooglemaps_googleChartsApi($x_speed, $y_speed, 0);
								$speedstring .= '<img class="xmlgmspeed" id="xmlgmspeed_'.$gpxid.'" '.$params->getStyleImgDiv().' alt="'.$params->gpxSpeedChartTitle.'" src="'.$sp->getSpeedUrl($params->width, $params->gpxSpeedChartHeight,$params->gpxSpeedLineColor,$params->gpxSpeedLineWidth, $params->measurement, $params->speedscale, $params->gpxSpeedChartTitle, $params->gpxCheckpointInterval).'" /><br />';
							}
							//Heart Rate
							if (($params->gpxHeartRateChart == "show") && ($has_heartrates>0)) {
								$alt = new xmlgooglemaps_googleChartsApi($x_ele,$y_heartrate);
								$heartratestring .= '<img class="xmlgmheartrate" id="xmlgmheartrate_'.$gpxid.'" '.$params->getStyleImgDiv().' alt="'.$params->gpxHeartRateChartTitle.'" src="'.$alt->getHRUrl($params->width, $params->gpxHeartRateChartHeight,$params->gpxHeartRateLineColor,$params->gpxHeartRateLineWidth, $params->measurement, $params->gpxHeartRateChartTitle, $params->gpxCheckpointInterval).'" /><br />';
							}	
							//Checkpoint Table
							if ($params->gpxCheckpointTable == "show") {
								$cptabstring = $params->gpxCheckpointTableTemplate;
								$cptabstring = str_replace("%WIDTH%",$params->width,$cptabstring);
								//Vorbereiten des Masssystems
								switch ($params->measurement) {
									case "imperial":
										$divident = 1.609344;
										$measurement = "mi";
										$divident2 = 0.3048;
										$measurement2 = "ft";
										if ($params->speedscale == "level1") {
											$scale = "fps";
										} else {
											$scale = "mph";
										}
										break;
									case "nautic_metric":	
										$divident = 1.852216;
										$measurement = "nm";
										$divident2 = 1;
										$measurement2 = "m";
										$scale = "kn";
										break;
									case "nautic_imperial":	
										$divident = 1.852216;
										$measurement = "nm";
										$divident2 = 0.3048;
										$measurement2 = "ft";
										$scale = "kn";
										break;
									default:
										$divident = 1;
										$measurement = "km";
										$divident2 = 1;
										$measurement2 = "m";
										if ($params->speedscale == "level1") {
											$scale = "m/s";
										} else {
											$scale = "km/s";
										}
										break;
								}
								//Initialisierung								
								$nextcheckpointnumber=1;							
								$dist = 0;
								$lastdist=0;
								$totaltime = 0;
								$lasttotaltime=0;
								$movingtime = 0;							
								$lastmovingtime = 0;											
								$lastpt = "";
								$maxpartspeed = 0;
								$maxspeed = 0;
								$partclimbup = 0;
								$climbup = 0;
								$partclimbdown = 0;
								$climbdown = 0;
								$checkpointnumber = 1;
								$maxele = -999999;
								$minele = +999999;
								$heartrate = 0;
								$lastheartrate = 0;
								//GPX Mode 1
								if ($params->gpxParseMode==1) { 					
									$trkmax = $gpxdata["trk_count"];
								//GPX Mode 2
								} else if ($params->gpxParseMode==2) {  
									$trkmax = xmlgooglemaps_dbfunctions::gpxGetMainItemCount($gpxfile->gpxfileid,'TRK');							
								}														
								
								//Trackpunkte durchgehen
								for ($trkcount=0;$trkcount<$trkmax; $trkcount++) {									
									//GPX Mode 1
									if ($params->gpxParseMode==1) { 					
										$trkptmax=$gpxdata["trk".$trkcount]["trkpt_count"];
									//GPX Mode 2
									} else if ($params->gpxParseMode==2) {  
										$trkrow = xmlgooglemaps_dbfunctions::gpxGetMainItem($gpxfile->gpxfileid,'TRK',$trkcount);
										$trkptmax = xmlgooglemaps_dbfunctions::gpxGetSubItemCount($gpxfile->gpxfileid,'TRKPT',$trkrow->item_id);
									}
									for ($trkptcount=0;$trkptcount<$trkptmax; $trkptcount++, $trkptcountsub++) 
										if (($params->gpxCheckpointInterval>0) ) {																				
											//GPX Mode 1
											if ($params->gpxParseMode==1) { 	
												$include = $trkptcount % $params->gpxInterval;										
												$pt = $gpxdata["trk".$trkcount]["trkpt".$trkptcount];
												$curtime = xmlgooglemaps_helper::xmlDate2DateTime($pt["time"]);
												$pretime = xmlgooglemaps_helper::xmlDate2DateTime($lastpt["time"]);
												$curlat = floatval($pt["lat"]);
												$prelat = floatval($lastpt["lat"]);
												$curlon = floatval($pt["lon"]);
												$prelon = floatval($lastpt["lon"]);
												$curhr = floatval($pt["heartrate"]);
												$curele = intval($pt["ele"]);
												$preele = intval($lastpt["ele"]);
												$lastpt = $pt;
											//GPX Mode 2
											} else if ($params->gpxParseMode==2) {
												$include = 0;
												if ($trkptcount % $params->gpxParseMode2MaxLimit == 0) {
													$trkpts = xmlgooglemaps_dbfunctions::gpxGetSubItems($gpxfile->gpxfileid,'TRKPT',$trkrow->item_id,$trkptcount,$params->gpxParseMode2MaxLimit);
													$trkptcountsub=0;
												}																																
												$curtime = strtotime($trkpts[$trkptcountsub]->time);
												$pretime = strtotime($lastpt->time);
												$curlat = floatval($trkpts[$trkptcountsub]->lat);
												$prelat = floatval($lastpt->lat);
												$curlon = floatval($trkpts[$trkptcountsub]->lon);
												$prelon = floatval($lastpt->lon);
												$curhr = floatval($trkpts[$trkptcountsub]->heartrate);
												$curele = intval($trkpts[$trkptcountsub]->ele);
												$preele = intval($lastpt->ele);
												$lastpt = $trkpts[$trkptcountsub];																						
											}
											if ($include == 0) {
												if (($trkcount > 0) || ($trkptcount>0)) {
													//2. - n-1. Punkt
													$deltatime = $curtime-$pretime;
													$deltadist = xmlgooglemaps_helper::distBetween2PointsKM($prelat,$prelon,$curlat,$curlon);
													//Zeit und Distanz (Durchschnittsgeschwindigkeit)
													$totaltime += $deltatime;
													$dist += $deltadist;
													//Heartrate
													$heartrate += $curhr*$deltatime;									
													//Zeit (nur falls eine Distanz zurückgelegt wurde)
													if ($deltadist!=0) {
														$movingtime += $deltatime;
													}
													//Max Speed
													$maxpartspeed = max(xmlgooglemaps_helper::getSpeed($params->measurement,$params->speedscale,$deltadist,$deltatime),$maxpartspeed);
													$maxspeed = max($maxpartspeed,$maxspeed);
													//Höhenmeter
													$elediff = ($curele-$preele)/ $divident2;
													if ($elediff>0) {
														$climbup += $elediff;
														$partclimbup += $elediff;
													} else {
														$climbdown -= $elediff;
														$partclimbdown -= $elediff;
													}
													if ($dist/$divident>$nextcheckpointnumber*$params->gpxCheckpointInterval) {
														$cpadd="";
														$cptabstringrow = $params->gpxCheckpointTableRowTemplate;
														$cptabstringrow = str_replace("%MARKERIMAGEURL%" , get_option('siteurl').'/wp-content/plugins/xml-google-maps/icons/mm_20_'.$params->gpxCheckpointMarkerColor.$cpadd.'.png', $cptabstringrow);												
														$cptabstringrow = str_replace("%DISTANCE%" , sprintf("%01.3f",$dist/$divident).' '.$measurement, $cptabstringrow);
														$cptabstringrow = str_replace("%PARTDISTANCE%" , sprintf("%01.3f",($dist-$lastdist)/$divident).' '.$measurement, $cptabstringrow);
														$cptabstringrow = str_replace("%TIME%" , xmlgooglemaps_helper::seconds2Text($totaltime), $cptabstringrow);
														$cptabstringrow = str_replace("%PARTTIME%" , xmlgooglemaps_helper::seconds2Text($totaltime-$lasttotaltime), $cptabstringrow);
														$cptabstringrow = str_replace("%MOVINGTIME%" , xmlgooglemaps_helper::seconds2Text($movingtime), $cptabstringrow);
														$cptabstringrow = str_replace("%PARTMOVINGTIME%" , xmlgooglemaps_helper::seconds2Text($movingtime-$lastmovingtime), $cptabstringrow);
														$cptabstringrow = str_replace("%SPEED%" , xmlgooglemaps_helper::getSpeedString($params->measurement,$params->speedscale,$dist,$totaltime), $cptabstringrow);
														$cptabstringrow = str_replace("%PARTSPEED%" , xmlgooglemaps_helper::getSpeedString($params->measurement,$params->speedscale,$dist-$lastdist,$totaltime-$lasttotaltime), $cptabstringrow);
														$cptabstringrow = str_replace("%HEARTRATE%" , xmlgooglemaps_helper::getDivision($heartrate,$totaltime,0), $cptabstringrow);
														$cptabstringrow = str_replace("%PARTHEARTRATE%" , xmlgooglemaps_helper::getDivision(($heartrate-$lastheartrate),($totaltime-$lasttotaltime),0), $cptabstringrow);
														$cptabstringrow = str_replace("%MOVINGSPEED%" , xmlgooglemaps_helper::getSpeedString($params->measurement,$params->speedscale,$dist,$movingtime), $cptabstringrow);
														$cptabstringrow = str_replace("%PARTMOVINGSPEED%" , xmlgooglemaps_helper::getSpeedString($params->measurement,$params->speedscale,$dist-$lastdist,$movingtime-$lastmovingtime), $cptabstringrow);												
														$cptabstringrow = str_replace("%MAXSPEED%" , xmlgooglemaps_helper::getSpeedString2($params->measurement,$params->speedscale,$maxspeed), $cptabstringrow);
														$cptabstringrow = str_replace("%PARTMAXSPEED%" , xmlgooglemaps_helper::getSpeedString2($params->measurement,$params->speedscale,$maxpartspeed), $cptabstringrow);
														$cptabstringrow = str_replace("%CHECKPOINTNUMBER%" , $checkpointnumber++, $cptabstringrow);
														$cptabstringrow = str_replace("%CLIMBUP%" , round($climbup,0).' '.$measurement2, $cptabstringrow);
														$cptabstringrow = str_replace("%CLIMBDOWN%" , round($climbdown,0).' '.$measurement2, $cptabstringrow);
														$cptabstringrow = str_replace("%PARTCLIMBUP%" , round($partclimbup,0).' '.$measurement2, $cptabstringrow);
														$cptabstringrow = str_replace("%PARTCLIMBDOWN%" , round($partclimbdown,0).' '.$measurement2, $cptabstringrow);
														$cptabstringrow = str_replace("%LATITUDE%" , xmlgooglemaps_helper::latitude2Text($curlat), $cptabstringrow);
														$cptabstringrow = str_replace("%LONGITUDE%" , xmlgooglemaps_helper::longitude2Text($curlon), $cptabstringrow);
														$cptabstringrow = str_replace("%ELEVATION%" , xmlgooglemaps_helper::elevation2Text($curele,$params->measurement), $cptabstringrow);
														$cptabstring = str_replace("%ROW%",$cptabstringrow."%ROW%",$cptabstring);
														$nextcheckpointnumber = floor(($dist/$divident) / $params->gpxCheckpointInterval) + 1;
														$lastdist = $dist;
														$lasttotaltime = $totaltime;
														$lastmovingtime = $movingtime;
														$lastheartrate = $heartrate;
														$maxpartspeed = 0;
														$partclimbdown = 0;
														$partclimbup = 0;
													}
												} else {
													//1. Punkt
													$cpadd="_s";
													$cptabstringrow = $params->gpxCheckpointTableRowTemplate;
													$cptabstringrow = str_replace("%MARKERIMAGEURL%" , get_option('siteurl').'/wp-content/plugins/xml-google-maps/icons/mm_20_'.$params->gpxCheckpointMarkerColor.$cpadd.'.png', $cptabstringrow);
													$cptabstringrow = str_replace("%DISTANCE%" , sprintf("%01.3f",$dist/$divident).' '.$measurement, $cptabstringrow);
													$cptabstringrow = str_replace("%PARTDISTANCE%" , sprintf("%01.3f",($dist-$lastdist)/$divident).' '.$measurement, $cptabstringrow);
													$cptabstringrow = str_replace("%TIME%" , xmlgooglemaps_helper::seconds2Text($totaltime), $cptabstringrow);
													$cptabstringrow = str_replace("%PARTTIME%" , xmlgooglemaps_helper::seconds2Text($totaltime-$lasttotaltime), $cptabstringrow);
													$cptabstringrow = str_replace("%MOVINGTIME%" , xmlgooglemaps_helper::seconds2Text($movingtime), $cptabstringrow);
													$cptabstringrow = str_replace("%PARTMOVINGTIME%" , xmlgooglemaps_helper::seconds2Text($movingtime-$lastmovingtime), $cptabstringrow);											
													$cptabstringrow = str_replace("%SPEED%" , xmlgooglemaps_helper::getSpeedString($params->measurement,$params->speedscale,$dist,$totaltime), $cptabstringrow);
													$cptabstringrow = str_replace("%PARTSPEED%" , xmlgooglemaps_helper::getSpeedString($params->measurement,$params->speedscale,$dist-$lastdist,$totaltime-$lasttotaltime), $cptabstringrow);
													$cptabstringrow = str_replace("%HEARTRATE%" , xmlgooglemaps_helper::getDivision($heartrate, $totaltime,0), $cptabstringrow);
													$cptabstringrow = str_replace("%PARTHEARTRATE%" , xmlgooglemaps_helper::getDivision(($heartrate-$lastheartrate),($totaltime-$lasttotaltime),0), $cptabstringrow);											
													$cptabstringrow = str_replace("%MOVINGSPEED%" , xmlgooglemaps_helper::getSpeedString($params->measurement,$params->speedscale,$dist,$movingtime), $cptabstringrow);
													$cptabstringrow = str_replace("%PARTMOVINGSPEED%" , xmlgooglemaps_helper::getSpeedString($params->measurement,$params->speedscale,$dist-$lastdist,$movingtime-$lastmovingtime), $cptabstringrow);																							
													$cptabstringrow = str_replace("%MAXSPEED%" , xmlgooglemaps_helper::getSpeedString2($params->measurement,$params->speedscale,$maxspeed), $cptabstringrow);
													$cptabstringrow = str_replace("%PARTMAXSPEED%" , xmlgooglemaps_helper::getSpeedString2($params->measurement,$params->speedscale,$maxpartspeed), $cptabstringrow);
													$cptabstringrow = str_replace("%CHECKPOINTNUMBER%" , $checkpointnumber++, $cptabstringrow);
													$cptabstringrow = str_replace("%CLIMBUP%" , '-', $cptabstringrow);
													$cptabstringrow = str_replace("%CLIMBDOWN%" , '-', $cptabstringrow);
													$cptabstringrow = str_replace("%PARTCLIMBUP%" , '-', $cptabstringrow);
													$cptabstringrow = str_replace("%PARTCLIMBDOWN%" , '-', $cptabstringrow);
													$cptabstringrow = str_replace("%LATITUDE%" , xmlgooglemaps_helper::latitude2Text($curlat), $cptabstringrow);
													$cptabstringrow = str_replace("%LONGITUDE%" , xmlgooglemaps_helper::longitude2Text($curlon), $cptabstringrow);
													$cptabstringrow = str_replace("%ELEVATION%" , xmlgooglemaps_helper::elevation2Text($curele,$params->measurement), $cptabstringrow);											
													$cptabstring = str_replace("%ROW%",$cptabstringrow."%ROW%",$cptabstring);
												}
											}
										}
									}
									if (($params->gpxCheckpointInterval>0)) {
										$cpadd="_e";
										$cptabstringrow = $params->gpxCheckpointTableRowTemplate;
										$cptabstringrow = str_replace("%MARKERIMAGEURL%" , get_option('siteurl').'/wp-content/plugins/xml-google-maps/icons/mm_20_'.$params->gpxCheckpointMarkerColor.$cpadd.'.png', $cptabstringrow);
										$cptabstringrow = str_replace("%DISTANCE%" , sprintf("%01.3f",$dist/$divident).' '.$measurement, $cptabstringrow);
										$cptabstringrow = str_replace("%PARTDISTANCE%" , sprintf("%01.3f",($dist-$lastdist)/$divident).' '.$measurement, $cptabstringrow);
										$cptabstringrow = str_replace("%TIME%" , xmlgooglemaps_helper::seconds2Text($totaltime), $cptabstringrow);
										$cptabstringrow = str_replace("%PARTTIME%" , xmlgooglemaps_helper::seconds2Text($totaltime-$lasttotaltime), $cptabstringrow);
										$cptabstringrow = str_replace("%MOVINGTIME%" , xmlgooglemaps_helper::seconds2Text($movingtime), $cptabstringrow);
										$cptabstringrow = str_replace("%PARTMOVINGTIME%" , xmlgooglemaps_helper::seconds2Text($movingtime-$lastmovingtime), $cptabstringrow);																
										$cptabstringrow = str_replace("%SPEED%" , xmlgooglemaps_helper::getSpeedString($params->measurement,$params->speedscale,$dist,$totaltime), $cptabstringrow);
										$cptabstringrow = str_replace("%PARTSPEED%" , xmlgooglemaps_helper::getSpeedString($params->measurement,$params->speedscale,$dist-$lastdist,$totaltime-$lasttotaltime), $cptabstringrow);
										$cptabstringrow = str_replace("%HEARTRATE%" , xmlgooglemaps_helper::getDivision($heartrate,$totaltime,0), $cptabstringrow);
										$cptabstringrow = str_replace("%PARTHEARTRATE%" , xmlgooglemaps_helper::getDivision(($heartrate-$lastheartrate),($totaltime-$lasttotaltime),0), $cptabstringrow);								
										$cptabstringrow = str_replace("%MOVINGSPEED%" , xmlgooglemaps_helper::getSpeedString($params->measurement,$params->speedscale,$dist,$movingtime), $cptabstringrow);
										$cptabstringrow = str_replace("%PARTMOVINGSPEED%" , xmlgooglemaps_helper::getSpeedString($params->measurement,$params->speedscale,$dist-$lastdist,$movingtime-$lastmovingtime), $cptabstringrow);								
										$cptabstringrow = str_replace("%MAXSPEED%" , xmlgooglemaps_helper::getSpeedString2($params->measurement,$params->speedscale,$maxspeed), $cptabstringrow);
										$cptabstringrow = str_replace("%PARTMAXSPEED%" , xmlgooglemaps_helper::getSpeedString2($params->measurement,$params->speedscale,$maxpartspeed), $cptabstringrow);
										$cptabstringrow = str_replace("%CHECKPOINTNUMBER%" , $checkpointnumber++, $cptabstringrow);
										$cptabstringrow = str_replace("%CLIMBUP%" , round($climbup,0).' '.$measurement2, $cptabstringrow);
										$cptabstringrow = str_replace("%CLIMBDOWN%" , round($climbdown,0).' '.$measurement2, $cptabstringrow);
										$cptabstringrow = str_replace("%PARTCLIMBUP%" , round($partclimbup,0).' '.$measurement2, $cptabstringrow);
										$cptabstringrow = str_replace("%PARTCLIMBDOWN%" , round($partclimbdown,0).' '.$measurement2, $cptabstringrow);
										$cptabstringrow = str_replace("%LATITUDE%" , xmlgooglemaps_helper::latitude2Text($curlat), $cptabstringrow);
										$cptabstringrow = str_replace("%LONGITUDE%" , xmlgooglemaps_helper::longitude2Text($curlon), $cptabstringrow);
										$cptabstringrow = str_replace("%ELEVATION%" , xmlgooglemaps_helper::elevation2Text($curele,$params->measurement), $cptabstringrow);								
										$cptabstring = str_replace("%ROW%",$cptabstringrow."%ROW%",$cptabstring);
									}
								//Endbehandlung
								$cptabstring = str_replace("%DISTANCE%" , sprintf("%01.3f",$dist/$divident).' '.$measurement, $cptabstring);
								$cptabstring = str_replace("%TIME%" , xmlgooglemaps_helper::seconds2Text($totaltime), $cptabstring);
								$cptabstring = str_replace("%MOVINGTIME%" , xmlgooglemaps_helper::seconds2Text($movingtime), $cptabstring);
								$cptabstring = str_replace("%SPEED%" , xmlgooglemaps_helper::getSpeedString($params->measurement,$params->speedscale,$dist,$totaltime), $cptabstring);
								$cptabstring = str_replace("%HEARTRATE%" , xmlgooglemaps_helper::getDivision($heartrate , $totaltime,0), $cptabstring);
								$cptabstring = str_replace("%MOVINGSPEED%" , xmlgooglemaps_helper::getSpeedString($params->measurement,$params->speedscale,$dist,$movingtime), $cptabstring);
								$cptabstring = str_replace("%MAXSPEED%" , xmlgooglemaps_helper::getSpeedString2($params->measurement,$params->speedscale,$maxspeed), $cptabstring);							
								$cptabstring = str_replace("%CLIMBUP%" , round($climbup,0).' '.$measurement2, $cptabstring);
								$cptabstring = str_replace("%CLIMBDOWN%" , round($climbdown,0).' '.$measurement2, $cptabstring);
								$cptabstring = str_replace("%PARTCLIMBUP%" , round($partclimbup,0).' '.$measurement2, $cptabstring);
								$cptabstring = str_replace("%PARTCLIMBDOWN%" , round($partclimbdown,0).' '.$measurement2, $cptabstring);
								//Zusätzliche Berechnungen
								$maxele = -999999;
								$minele = +999999;
								for ($trkcount=0;$trkcount<$trkmax; $trkcount++) {
									//GPX Mode 1
									if ($params->gpxParseMode==1) { 										
										$trkptmax=$gpxdata["trk".$trkcount]["trkpt_count"];
										for ($trkptcount=0;$trkptcount<$trkptmax; $trkptcount++) {
											$ele = intval($gpxdata["trk".$trkcount]["trkpt".$trkptcount]["ele"]);
											$maxele = max($ele/$divident2,$maxele);
											$minele = min($ele/$divident2,$minele);
										}
									//GPX Mode 2										
									} else if ($params->gpxParseMode==2) { 				
										$trkrow = xmlgooglemaps_dbfunctions::gpxGetMainItem($gpxfile->gpxfileid,'TRK',$trkcount);
										$trkptmax = xmlgooglemaps_dbfunctions::gpxGetSubItemCount($gpxfile->gpxfileid,'TRKPT',$trkrow->item_id);
										for ($trkptcount=0;$trkptcount<$trkptmax; $trkptcount++, $trkptcountsub++) {
											if ($trkptcount % $params->gpxParseMode2MaxLimit == 0) {
												$trkpts = xmlgooglemaps_dbfunctions::gpxGetSubItems($gpxfile->gpxfileid,'TRKPT',$trkrow->item_id,$trkptcount,$params->gpxParseMode2MaxLimit);
												$trkptcountsub=0;
											}											
											$ele = intval($trkpts[$trkptcountsub]->ele);
											$maxele = max($ele/$divident2,$maxele);
											$minele = min($ele/$divident2,$minele);
										}										
									}
								}			
								$cptabstring = str_replace("%ELEVATIONMAX%" , round($maxele,0).' '.$measurement2, $cptabstring);				
								$cptabstring = str_replace("%ELEVATIONMIN%" , round($minele,0).' '.$measurement2, $cptabstring);
								//ROW noch entfernen
								$cptabstring = $cptabstring = str_replace("%ROW%","",$cptabstring);;
							}
						}
					}
				}
				
				$iframeurl = get_option('siteurl').'/wp-content/plugins/xml-google-maps/xmlgooglemaps_show.php?gpxid='.$gpxid;
				$repstring = '<div '.$params->getStyleMapDiv().' class="xmlgmdiv" id="xmlgmdiv_'.$gpxid.'">';
				$repstring .= '<iframe class="xmlgm" id="xmlgm_'.$gpxid.'" src="'.$iframeurl.'" style="border: 0px; width: '.$params->width.'px; height: '.$params->height.'px;" name="Google_Gpx_Maps" frameborder="0">';
				$repstring .= '</iframe>'.$banner.'</div>';
				$repstring .= $altstring.$speedstring.$heartratestring.$cptabstring.$displayurl.'';
				xmlgooglemaps_dbfunctions::cacheSet($gpxid,$cachekey,$repstring);
			} else {
				$repstring = $cachestring;			
			}
			$content = str_replace($found[0][$i],$repstring,$content);
		}
	}
	return $content;
}



//Optiona Page
require_once dirname(__FILE__).'/xmlgooglemaps_optionspage.php';

function xmlgm_nonce_field() {
	echo "<input type='hidden' name='xmlgm-nonce-key' value='" . wp_create_nonce('xml-google-maps') . "' />";
}

function getDisplayURL($urldisplaymode, $displayurl, $varstring) {
	switch ($urldisplaymode) {
		case "none":
			$displayurl = "";
			break;
		case "showvars":
			$displayurl = $displayurl;
			break;
		case "nomap":
			$displayurl = str_replace("[".$varstring."]","",$displayurl);
			break;
		default:
			$displayurl = str_replace("[".$varstring."]","",$displayurl);
			break;
	}
	return $displayurl;
}

function xmlgooglemaps_shortcuts($links, $file) {

	if ( $file == plugin_basename(__FILE__) )
	{
		$links[] = '<a href="options-general.php?page=xmlgooglemaps.php">' . __('Settings') . '</a>';	
		$links[] = '<a href="http://www.matusz.ch/donate_xmlgooglemapswpplugin.htm" target="_blank">' . __('Donate') . '</a>';
	}

	return $links;
}


// Not exististing Functions
if(!function_exists("stripos"))
{
	function stripos($str, $needle)
	{
		return strpos(strtolower($str),strtolower($needle));
	}
}

?>