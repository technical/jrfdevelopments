<?php
/*
 * Diese Klasse enthält einige Hilfsfunktionen
 */
class xmlgooglemaps_helper {
	
	function clearCacheAfterUpdate() {
			if (get_option(key_xmlgm_current_plugin_version) != XML_GOOGLE_MAPS_VERSION) {
				xmlgooglemaps_dbfunctions::cacheClearAll();
				update_option(key_xmlgm_current_plugin_version, XML_GOOGLE_MAPS_VERSION);
			}
	}

	function getFileContent($url, $user='', $password='') {
		$filecontent = "";
		if (function_exists("curl_init")) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 3);
			if (($user != '') || ($password != '')) {
				curl_setopt($ch, CURLAUTH_NTLM); 
				curl_setopt ($ch, CURLOPT_USERPWD, "$username:$password");
			}			
			$filecontent = curl_exec($ch);
			curl_close($ch);
			return $filecontent;
		} else {
			$url = xmlgooglemaps_helper::tryGetLocalPath($url);
			if (!($fp = fopen($url, "r"))) {
				return false;
			}
			while ($data = fread($fp, 4096)) {
				$filecontent .= $data;
			}
			fclose($fp);
			return $filecontent;
		}
	}
	
	function getFileModifiedDate($url, $user='', $password='') {
			if (function_exists("curl_init")) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
			    curl_setopt($ch, CURLOPT_HEADER, 1);
			    curl_setopt($ch, CURLOPT_NOBODY, 1);
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				if (($user != '') || ($password != '')) {
					curl_setopt($ch, CURLAUTH_NTLM); 
					curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
				}			
			    $headers = curl_exec($ch); 
				curl_close($ch);
				if ($headers===false) { return 0; }
				$headers = substr(stristr($headers, "Last-Modified:"),15);				
				$lines=split("\n",$headers);	
				if (count($lines)>0) {		
					return strtotime($lines[0]);			
				} else {
					return 0;
				}
			} else {
				$url = xmlgooglemaps_helper::tryGetLocalPath($url);
				$ret = filemtime($url);
				if ($ret === false) {
					return 0;
				} else {
					return $ret;
				}
			}		
	}

	function tryGetLocalPath($url) {
		if (defined('ABSPATH')) {
			$root_path = ABSPATH;
			$root_url = get_option('siteurl')."/";
	
			$ret = str_replace($root_url,$root_path,$url);
			return $ret;
		} else {
			return $url;
		}		
	}

	function longitude2Text($lon) {
		$lon = floatval($lon);
		if ($lon>=0) {$h="E";} else {$h="W"; $lon=-1*$lon;}
		$value1 = floor($lon);
		$lon = ($lon - $value1) * 60;
		$value2 = floor($lon);
		$lon = ($lon - $value2) * 60;
		$value3 = floor($lon);
		$lon = ($lon - $value3) * 100;
		$value4 = floor($lon);
		return $h." ".$value1."&deg; ".str_pad($value2,2,"0",STR_PAD_LEFT)."&#39; ".str_pad($value3,2,"0",STR_PAD_LEFT).".".str_pad($value4,2,"0",STR_PAD_LEFT)."&quot;";
	}

	function latitude2Text($lat) {
		$lat = floatval($lat);
		if ($lat>=0) {$h="N";} else {$h="S"; $lat=-1*$lat;}
		$value1 = floor($lat);
		$lat = ($lat - $value1) * 60;
		$value2 = floor($lat);
		$lat = ($lat - $value2) * 60;
		$value3 = floor($lat);
		$lat = ($lat - $value3) * 100;
		$value4 = floor($lat);
		return $h." ".$value1."&deg; ".str_pad($value2,2,"0",STR_PAD_LEFT)."&#39; ".str_pad($value3,2,"0",STR_PAD_LEFT).".".str_pad($value4,2,"0",STR_PAD_LEFT)."&quot;";
	}

	function elevation2Text($alt, $type) {
		switch ($params->measurement) {
			case "imperial":
			case "nautic_imperial":
				return round($alt/0.3048,0)." ft";
				break;
			case "nautic_metric":
			case "metric":
			default:
				return round($alt,0)." m";
				break;
		}
	}

	function seconds2Text($seconds) {
		$hours = floor($seconds / (60*60));
		$seconds -= $hours *(60*60);
		$minutes = floor($seconds / 60);
		$seconds -= $minutes*60;
		$seconds = floor($seconds);
		if ($hours != 0) {
			return  $hours."h ".str_pad($minutes,2,"0",STR_PAD_LEFT)."m ".str_pad($seconds,2,"0",STR_PAD_LEFT)."s";
		} else {
			if ($minutes != 0) {
				return $minutes."m ".str_pad($seconds,2,"0",STR_PAD_LEFT)."s";
			} else {
				return $seconds."s";
			}
		}
	}

	function getSpeedString($measurement,$scale,$distancekm,$timesec) {
		if ($timesec==0) {
			return "-";
		} else {
			return sprintf("%01.2f",xmlgooglemaps_helper::getSpeed($measurement,$scale,$distancekm,$timesec))." ".xmlgooglemaps_helper::getSpeedMeasurementString($measurement,$scale);
		}
	}
	
	function getDivision($v1, $v2, $round=0) {
		if ($v2==0) {
			return '-';
		} else {
			return round($v1 / $v2, $round);
		}
	}

	function getSpeedString2($measurement,$scale,$speed) {
		if (intval($speed)==0) {
			return "-";
		} else {
			return sprintf("%01.2f",$speed)." ".xmlgooglemaps_helper::getSpeedMeasurementString($measurement,$scale);
		}
	}

	function getSpeedMeasurementString($measurement,$scale) {
		switch (strtolower($measurement.$scale)) {
			case "imperiallevel2":
				return "mph";
				break;
			case "imperiallevel1":
				return "fph";
				break;
			case "metriclevel1":
				return "m/s";
				break;
			case "nautic_metriclevel1":
			case "nautic_metriclevel2":
			case "nautic_imperiallevel1":
			case "nautic_imperiallevel2":
				return "kn";
				break;
			default:
				return "km/h";
				break;
		}
	}

	function getSpeed($measurement,$scale,$distancekm,$timesec)
	{
		if ($timesec==0) {
			return 0;
		} else {
			switch (strtolower($measurement.$scale)) {
				case "imperiallevel2":
					return ($distancekm/1.609344) / ($timesec/3600);
					break;
				case "imperiallevel1":
					return ($distancekm/0.3048) / ($timesec);
					break;
				case "metriclevel1":
					return ($distancekm*1000) / ($timesec);
					break;
				case "nautic_metriclevel1":
				case "nautic_metriclevel2":
				case "nautic_imperiallevel1":
				case "nautic_imperiallevel2":
					return ($distancekm/1.852216) / ($timesec/3600);
					break;
				default:
					return ($distancekm) / ($timesec/3600);
					break;
			}
		}
	}

	function distBetween2PointsKM($lat1, $lon1, $lat2, $lon2) {
		$lat1 = deg2rad($lat1);
		$lon1 = deg2rad($lon1);
		$lat2 = deg2rad($lat2);
		$lon2 = deg2rad($lon2);
		$ret = rad2deg(acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lon2 - $lon1))) * 111.1206;
		if (is_nan($ret)) {
			$ret=0;
		}
		return $ret;
	}

	function xmlDate2DateTime($value) {
		$value = str_replace("Z","",$value);
		return strtotime($value);
	}

	function getJavascriptDecimalNumber($value,$rounding=3) {
		return number_format($value,$rounding,".","");
	}

	function colorName2Hex($name) {
		switch (strtolower($name)) {
			case "aliceblue": return "#F0F8FF"; break;
			case "antiquewhite": return "#FAEBD7"; break;
			case "aqua": return "#00FFFF"; break;
			case "aquamarine": return "#7FFFD4"; break;
			case "azure": return "#F0FFFF"; break;
			case "beige": return "#F5F5DC"; break;
			case "bisque": return "#FFE4C4"; break;
			case "black": return "#000000"; break;
			case "blanchedalmond": return "#FFEBCD"; break;
			case "blue": return "#0000FF"; break;
			case "blueviolet": return "#8A2BE2"; break;
			case "brown": return "#A52A2A"; break;
			case "burlywood": return "#DEB887"; break;
			case "cadetblue": return "#5F9EA0"; break;
			case "chartreuse": return "#7FFF00"; break;
			case "chocolate": return "#D2691E"; break;
			case "coral": return "#FF7F50"; break;
			case "cornflowerblue": return "#6495ED"; break;
			case "cornsilk": return "#FFF8DC"; break;
			case "crimson": return "#DC143C"; break;
			case "cyan": return "#00FFFF"; break;
			case "darkblue": return "#00008B"; break;
			case "darkcyan": return "#008B8B"; break;
			case "darkgoldenrod": return "#B8860B"; break;
			case "darkgray": return "#A9A9A9"; break;
			case "darkgrey": return "#A9A9A9"; break;
			case "darkgreen": return "#006400"; break;
			case "darkkhaki": return "#BDB76B"; break;
			case "darkmagenta": return "#8B008B"; break;
			case "darkolivegreen": return "#556B2F"; break;
			case "darkorange": return "#FF8C00"; break;
			case "darkorchid": return "#9932CC"; break;
			case "darkred": return "#8B0000"; break;
			case "darksalmon": return "#E9967A"; break;
			case "darkseagreen": return "#8FBC8F"; break;
			case "darkslateblue": return "#483D8B"; break;
			case "darkslategray": return "#2F4F4F"; break;
			case "darkslategrey": return "#2F4F4F"; break;
			case "darkturquoise": return "#00CED1"; break;
			case "darkviolet": return "#9400D3"; break;
			case "deeppink": return "#FF1493"; break;
			case "deepskyblue": return "#00BFFF"; break;
			case "dimgray": return "#696969"; break;
			case "dimgrey": return "#696969"; break;
			case "dodgerblue": return "#1E90FF"; break;
			case "firebrick": return "#B22222"; break;
			case "floralwhite": return "#FFFAF0"; break;
			case "forestgreen": return "#228B22"; break;
			case "fuchsia": return "#FF00FF"; break;
			case "gainsboro": return "#DCDCDC"; break;
			case "ghostwhite": return "#F8F8FF"; break;
			case "gold": return "#FFD700"; break;
			case "goldenrod": return "#DAA520"; break;
			case "gray": return "#808080"; break;
			case "grey": return "#808080"; break;
			case "green": return "#008000"; break;
			case "greenyellow": return "#ADFF2F"; break;
			case "honeydew": return "#F0FFF0"; break;
			case "hotpink": return "#FF69B4"; break;
			case "indianred ": return "#CD5C5C"; break;
			case "indigo ": return "#4B0082"; break;
			case "ivory": return "#FFFFF0"; break;
			case "khaki": return "#F0E68C"; break;
			case "lavender": return "#E6E6FA"; break;
			case "lavenderblush": return "#FFF0F5"; break;
			case "lawngreen": return "#7CFC00"; break;
			case "lemonchiffon": return "#FFFACD"; break;
			case "lightblue": return "#ADD8E6"; break;
			case "lightcoral": return "#F08080"; break;
			case "lightcyan": return "#E0FFFF"; break;
			case "lightgoldenrodyellow": return "#FAFAD2"; break;
			case "lightgray": return "#D3D3D3"; break;
			case "lightgrey": return "#D3D3D3"; break;
			case "lightgreen": return "#90EE90"; break;
			case "lightpink": return "#FFB6C1"; break;
			case "lightsalmon": return "#FFA07A"; break;
			case "lightseagreen": return "#20B2AA"; break;
			case "lightskyblue": return "#87CEFA"; break;
			case "lightslategray": return "#778899"; break;
			case "lightslategrey": return "#778899"; break;
			case "lightsteelblue": return "#B0C4DE"; break;
			case "lightyellow": return "#FFFFE0"; break;
			case "lime": return "#00FF00"; break;
			case "limegreen": return "#32CD32"; break;
			case "linen": return "#FAF0E6"; break;
			case "magenta": return "#FF00FF"; break;
			case "maroon": return "#800000"; break;
			case "mediumaquamarine": return "#66CDAA"; break;
			case "mediumblue": return "#0000CD"; break;
			case "mediumorchid": return "#BA55D3"; break;
			case "mediumpurple": return "#9370D8"; break;
			case "mediumseagreen": return "#3CB371"; break;
			case "mediumslateblue": return "#7B68EE"; break;
			case "mediumspringgreen": return "#00FA9A"; break;
			case "mediumturquoise": return "#48D1CC"; break;
			case "mediumvioletred": return "#C71585"; break;
			case "midnightblue": return "#191970"; break;
			case "mintcream": return "#F5FFFA"; break;
			case "mistyrose": return "#FFE4E1"; break;
			case "moccasin": return "#FFE4B5"; break;
			case "navajowhite": return "#FFDEAD"; break;
			case "navy": return "#000080"; break;
			case "oldlace": return "#FDF5E6"; break;
			case "olive": return "#808000"; break;
			case "olivedrab": return "#6B8E23"; break;
			case "orange": return "#FFA500"; break;
			case "orangered": return "#FF4500"; break;
			case "orchid": return "#DA70D6"; break;
			case "palegoldenrod": return "#EEE8AA"; break;
			case "palegreen": return "#98FB98"; break;
			case "paleturquoise": return "#AFEEEE"; break;
			case "palevioletred": return "#D87093"; break;
			case "papayawhip": return "#FFEFD5"; break;
			case "peachpuff": return "#FFDAB9"; break;
			case "peru": return "#CD853F"; break;
			case "pink": return "#FFC0CB"; break;
			case "plum": return "#DDA0DD"; break;
			case "powderblue": return "#B0E0E6"; break;
			case "purple": return "#800080"; break;
			case "red": return "#FF0000"; break;
			case "rosybrown": return "#BC8F8F"; break;
			case "royalblue": return "#4169E1"; break;
			case "saddlebrown": return "#8B4513"; break;
			case "salmon": return "#FA8072"; break;
			case "sandybrown": return "#F4A460"; break;
			case "seagreen": return "#2E8B57"; break;
			case "seashell": return "#FFF5EE"; break;
			case "sienna": return "#A0522D"; break;
			case "silver": return "#C0C0C0"; break;
			case "skyblue": return "#87CEEB"; break;
			case "slateblue": return "#6A5ACD"; break;
			case "slategray": return "#708090"; break;
			case "slategrey": return "#708090"; break;
			case "snow": return "#FFFAFA"; break;
			case "springgreen": return "#00FF7F"; break;
			case "steelblue": return "#4682B4"; break;
			case "tan": return "#D2B48C"; break;
			case "teal": return "#008080"; break;
			case "thistle": return "#D8BFD8"; break;
			case "tomato": return "#FF6347"; break;
			case "turquoise": return "#40E0D0"; break;
			case "violet": return "#EE82EE"; break;
			case "wheat": return "#F5DEB3"; break;
			case "white": return "#FFFFFF"; break;
			case "whitesmoke": return "#F5F5F5"; break;
			case "yellow": return "#FFFF00"; break;
			case "yellowgreen": return "#9ACD32"; break;
			default: return $name; break;
		}
	}

	function xml2array($contents, $get_attributes=1) {
		if(!$contents) return array();
			
		if(!function_exists('xml_parser_create')) {
			return array();
		}
		//Get the XML parser of PHP - PHP must have this module for the parser to work
		$parser = xml_parser_create();
		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 );
		xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 );
		xml_parse_into_struct( $parser, $contents, $xml_values );
		xml_parser_free( $parser );

		if(!$xml_values) return;//Hmm...

		//Initializations
		$xml_array = array();
		$parents = array();
		$opened_tags = array();
		$arr = array();

		$current = &$xml_array;

		//Go through the tags.
		foreach($xml_values as $data) {
			unset($attributes,$value);//Remove existing values, or there will be trouble

			//This command will extract these variables into the foreach scope
			// tag(string), type(string), level(int), attributes(array).
			extract($data);//We could use the array by itself, but this cooler.
			$tag = strtolower($tag);

			$result = '';
			if($get_attributes) {//The second argument of the function decides this.
				$result = array();
				if(isset($value)) $result['value'] = $value;

				//Set the attributes too.
				if(isset($attributes)) {
					foreach($attributes as $attr => $val) {
						if($get_attributes == 1) $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
						/**  :TODO: should we change the key name to '_attr'? Someone may use the tagname 'attr'. Same goes for 'value' too */
					}
				}
			} elseif(isset($value)) {
				$result = $value;
			}

			//See tag status and do the needed.
			if($type == "open") {//The starting of the tag '<tag>'
				$parent[$level-1] = &$current;

				if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
					$current[$tag] = $result;
					$current = &$current[$tag];

				} else { //There was another element with the same tag name
					if(isset($current[$tag][0])) {
						array_push($current[$tag], $result);
					} else {
						$current[$tag] = array($current[$tag],$result);
					}
					$last = count($current[$tag]) - 1;
					$current = &$current[$tag][$last];
				}

			} elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
				//See if the key is already taken.
				if(!isset($current[$tag])) { //New Key
					$current[$tag] = $result;

				} else { //If taken, put all things inside a list(array)
					if((is_array($current[$tag]) and $get_attributes == 0)//If it is already an array...
					or (isset($current[$tag][0]) and is_array($current[$tag][0]) and $get_attributes == 1)) {
						array_push($current[$tag],$result); // ...push the new element into that array.
					} else { //If it is not an array...
						$current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
					}
				}

			} elseif($type == 'close') { //End of tag '</tag>'
				$current = &$parent[$level-1];
			}
		}
			
		return($xml_array);
	}

}

?>