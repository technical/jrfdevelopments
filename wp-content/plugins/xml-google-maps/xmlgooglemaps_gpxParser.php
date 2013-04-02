<?php
/*
 * Diese Klasse enthält den GPX DOM Parser
 */
class xmlgooglemaps_gpxParser{
	var $output = array();
	
	function hasToParse($url) {
		return true;
	}

	function parse($url) {
		$this->output = "";
		
		$filecontent = xmlgooglemaps_helper::getFileContent($url);
			
		if ($filecontent === false) {
			die("Cannot open XML data file: $url");
			return false;
		}
		
		$xml = xmlgooglemaps_helper::xml2array($filecontent);

		//Bounds
		$this->output["bounds"] = $xml["gpx"]["metadata"]["bounds"]["attr"];
		//Waypoints
		if (isset($xml["gpx"]["wpt"]["attr"])) {
			$waypts[0] = $xml["gpx"]["wpt"];
		} else {
			$waypts = $xml["gpx"]["wpt"];
		}
		for ($i=0; $i<count($waypts); $i++) {
			foreach($waypts[$i] AS $tagname => $element) {
				switch (strtolower($tagname)) {				
					case "attr":
						$this->output["wpt".$i] = $element;
						break;
					case "link":
						$this->output["wpt".$i]["link"] = $element["attr"]["href"];
						break;						
					default:
						$this->output["wpt".$i][$tagname] = $element["value"];
						break;		 
				}
			}
		}
		$this->output["wpt_count"] = count($waypts);
		//Routes
		if (isset($xml["gpx"]["rte"]["rtept"])) {
			$rtes[0] = $xml["gpx"]["rte"];
		} else {
			$rtes = $xml["gpx"]["rte"];
		}
		for ($i=0; $i<count($rtes); $i++) {
			if (isset($rtes[$i]["rtept"]["attr"])) {
				$rtepts[0] = $rtes[$i]["rtept"];
			} else {
				$rtepts = $rtes[$i]["rtept"];
			}
			for ($ii=0; $ii<count($rtepts); $ii++) {
				foreach($rtepts[$ii] AS $tagname => $element) {
					switch (strtolower($tagname)) {					
						case "attr":
							$this->output["rte".$i]["rtept".$ii] = $element;
							break;
						case "link":
							$this->output["rte".$i]["rtept".$ii]["link"] = $element["attr"]["href"];
							break;						
						default:
							$this->output["rte".$i]["rtept".$ii][$tagname] = $element["value"];
							break;		 
					}					
				}
			}
			$this->output["rte".$i]["rtept_count"] = count($rtepts);
		}
		$this->output["rte_count"] = count($rtes);
		//Tracks
		$trkcount = 0;
		if (isset($xml["gpx"]["trk"]["trkseg"])) {
			$trks[0] = $xml["gpx"]["trk"];
		} else {
			$trks = $xml["gpx"]["trk"];
		}
		for ($i=0; $i<count($trks); $i++) {
			if (isset($trks[$i]["trkseg"]["trkpt"])) {
				$trksegs[0] = $trks[$i]["trkseg"];
			} else {
				$trksegs = $trks[$i]["trkseg"];
			}
			for ($ii=0; $ii<count($trksegs); $ii++) {
				if (isset($trksegs[$ii]["trkpt"]["attr"])) {
					$trkpts[0] = $trksegs[$ii]["trkpt"];
				} else {
					$trkpts = $trksegs[$ii]["trkpt"];
				}
				for ($iiii=0; $iiii<count($trkpts); $iiii++) {					
					foreach($trkpts[$iiii] AS $tagname => $element) {
						switch (strtolower($tagname)) {
							case "extensions":
								if (isset($element["gpxtpx:trackpointextension"]["gpxtpx:hr"]["value"])) {								
									$this->output["trk".$trkcount]["trkpt".$iiii]["heartrate"] = $element["gpxtpx:trackpointextension"]["gpxtpx:hr"]["value"];
								}
								break;
							case "attr":
								$this->output["trk".$trkcount]["trkpt".$iiii] = $element;
								break;
							case "link":
								$this->output["trk".$trkcount]["trkpt".$iiii]["link"] = $element["attr"]["href"];
								break;						
							default:
								$this->output["trk".$trkcount]["trkpt".$iiii][$tagname] = $element["value"];
								break;		 
						}						
					}
				}
				$this->output["trk".$trkcount]["trkpt_count"] = count($trkpts);
				$trkcount++;
			}
		}
		$this->output["trk_count"] = $trkcount;

	}


}

?>