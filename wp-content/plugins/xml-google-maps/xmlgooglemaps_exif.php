<?php
/*
 * Diese Klasse enthält sämtliche EXIF Funktionen
 */
	class xmlgooglemaps_exif {
		
		var $imagePath = "";
		var $exifData = array();
		var $isValid = false;

		function xmlgooglemaps_exif($filePath) {
			$this->imagePath = $filePath;
	 		if (!file_exists( $this->imagePath ))
	 		{
				$this->isValid = false;
	 		} else {
				if (is_callable('exif_read_data'))
				{
					$this->exifData = @exif_read_data($this->imagePath, 0, true );
					$this->isValid = true;
				} else {
					$this->isValid = false;
				}	 					
			}	
		}
		
		function isInstalled() {
			return is_callable('exif_read_data');
		}
		
		function getGPSLongitude() {
			if ($this->isValid) {
				if (!empty($this->exifData["GPS"]["GPSLongitude"])) {
					$ret = $this->frac2dec($this->exifData["GPS"]["GPSLongitude"][0]);
					$ret += $this->frac2dec($this->exifData["GPS"]["GPSLongitude"][1])/60;
					$ret += $this->frac2dec($this->exifData["GPS"]["GPSLongitude"][2])/3600;
					if (strtolower($this->exifData["GPS"]["GPSLongitudeRef"])=="w") {
						$ret = -1 * $ret;
					}
					return $ret;
				} else {
					return 0;
				}
			} else {
				return 0;
			}			
		}
		
		function getGPSLatitude() {
			if ($this->isValid) {
				if (!empty($this->exifData["GPS"]["GPSLatitude"])) {
					$ret = $this->frac2dec($this->exifData["GPS"]["GPSLatitude"][0]);
					$ret += $this->frac2dec($this->exifData["GPS"]["GPSLatitude"][1])/60;
					$ret += $this->frac2dec($this->exifData["GPS"]["GPSLatitude"][2])/3600;
					if (strtolower($this->exifData["GPS"]["GPSLatitudeRef"])=="s") {
						$ret = -1 * $ret;
					}
					return $ret;
				} else {
					return 0;
				}
			} else {
				return 0;
			}			
		}
		
		function getCreationDateTime() {
			if (!empty($this->exifData["EXIF"]["DateTimeDigitized"])) {
				return $this->date2ts($this->exifData["EXIF"]["DateTimeDigitized"]);
			} else {
				return 0;
			}
		}
		
		function echoOutput() {
			if ($this->isValid) {
				foreach ($this->exifData as $key => $section) {
				    foreach ($section as $name => $val) {
				        echo "$key.$name: $val<br />\n";
				    }
				}			
			} else {
				echo "error";
			}
		}		

		// convert a fraction string to a decimal
		function frac2dec($str) {
			@list( $n, $d ) = explode( '/', $str );
			if ( !empty($d) )
				return $n / $d;
			return $str;
		}
	
		// convert the exif date format to a unix timestamp
		function date2ts($str) {
			// seriously, who formats a date like 'YYYY:MM:DD hh:mm:ss'?
			@list( $date, $time ) = explode( ' ', trim($str) );
			@list( $y, $m, $d ) = explode( ':', $date );
		
			return strtotime( "{$y}-{$m}-{$d} {$time}" );
		}
	}
?>