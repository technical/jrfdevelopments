<?php
/*
 * Diese Klasse enthält den GPX SAX Parser
 */

if(!function_exists("stripos")) 
{
  function stripos($str, $needle) 
  {
   return strpos(strtolower($str),strtolower($needle));
  }
}

class xmlgooglemaps_gpxParser2 {
   var $xml_obj = null;
   var $url = "";
   var $url_lastmodified = 0;
   var $state = "init";   
   var $innertext;
   
   //ID's
   var $gpxfileid = 0;
   var $trkid = 0;
   var $trkptid = 0;
   var $wptid = 0;
   var $rteid = 0;
   var $rteptid = 0;
   
   //Number
   var $trknr = -1;
   var $trkptnr = -1;
   var $wptnr = -1;
   var $rtenr = -1;
   var $rteptnr = -1;
   
   function hasToParse($url) {
   		$this->url = $url;
   		if (xmlgooglemaps_dbfunctions::gpxFileExists($this->url)) 
   		{
   			$this->gpxfileid = xmlgooglemaps_dbfunctions::gpxGetFileID($this->url);
   			$this->url_lastmodified = xmlgooglemaps_helper::getFileModifiedDate($this->url);
   			
   			if ((xmlgooglemaps_dbfunctions::gpxGetFileModDate($this->gpxfileid)!= $this->url_lastmodified) || ($this->url_lastmodified==0)) {
   				return true;
   			} else {
   				return false;
   			}   			
   		} else {
   			return true;
   		}			
   }
         
   function parse($url){
       $this->url = $url;
       $this->xml_obj = xml_parser_create();
       xml_set_object($this->xml_obj,$this);
       xml_set_character_data_handler($this->xml_obj, 'dataHandler');
       xml_set_element_handler($this->xml_obj, "startHandler", "endHandler");
   	
       $old = error_reporting(1);
       
       $filecontent = xmlgooglemaps_helper::getFileContent($url);
       $this->url_lastmodified = xmlgooglemaps_helper::getFileModifiedDate($url);
                    
       if ($filecontent === false) {
           xml_parser_free($this->xml_obj);
           $err=error_get_last();
           error_reporting($old);
           die(sprintf("PHP Error: %s in %s at line %d",$err["message"],$err["file"],$err["line"]));            
           return false;
       } else  {
       		if ($this->url_lastmodified==0) {
           		xml_parser_free($this->xml_obj);
           		error_reporting($old);   
           		die(sprintf("Cannot get modified date from the file you like to parse! Like this, it is not possible to use this file that way!"));               			
           		return false;		
       		} else {
		   		error_reporting($old);
       		}
       }
       
       if (!xml_parse($this->xml_obj, $filecontent)) {
	       die(sprintf("XML error: %s at line %d",
	                    xml_error_string(xml_get_error_code($this->xml_obj)),
	                    xml_get_current_line_number($this->xml_obj)));       	
       	   xml_parser_free($this->xml_obj);
           return false;
       }
       xml_parser_free($this->xml_obj);
             
       return true;
   }
   
   function startHandler($parser, $name, $attribs){   	
   		switch (strtoupper($name)) {
   			//Header
   			case "GPX":
   				if ($this->state=="init") {
   					$this->gpxfileid = xmlgooglemaps_dbfunctions::gpxUpdatePath($this->url, $this->url_lastmodified);
   					xmlgooglemaps_dbfunctions::gpxClearAllDetailData($this->gpxfileid);
   					$this->state="gpx";
   				}
   				break;
   			case "BOUNDS":
   				if ($this->state=="gpx") {
   					xmlgooglemaps_dbfunctions::gpxUpdateBounds($this->gpxfileid,floatval(trim($attribs[MAXLAT])),floatval(trim($attribs[MINLAT])),floatval(trim($attribs[MAXLON])),floatval(trim($attribs[MINLON])));
   				}
   				break;
   			//Waypoint Related
   			case "WPT":
   				if ($this->state=="gpx") {
   					$this->wptnr++;
   					$this->wptid = xmlgooglemaps_dbfunctions::gpxInsertMainItem($this->gpxfileid,'WPT',$this->wptnr);
   					xmlgooglemaps_dbfunctions::gpxUpdateItemLatLon($this->wptid,floatval(trim($attribs[LAT])),floatval(trim($attribs[LON])));
   					$this->state="wpt";
   				}
   				break;   	
   			//Route
   			case "RTE":
   				if ($this->state=="gpx") {
   					$this->rtenr++;
   					$this->rteptnr=-1;
   					$this->rteid = xmlgooglemaps_dbfunctions::gpxInsertMainItem($this->gpxfileid,'RTE',$this->rtenr);
   					$this->state="rte";
   				}
   				break;   	
   			case "RTEPT":
   				if ($this->state=="rte") {
   					$this->rteptnr++;
   					$this->rteptid = xmlgooglemaps_dbfunctions::gpxInsertSubItem($this->gpxfileid,$this->rteid, 'RTEPT', $this->rteptnr);
   					xmlgooglemaps_dbfunctions::gpxUpdateItemLatLon($this->rteptid,floatval(trim($attribs[LAT])),floatval(trim($attribs[LON])));
   					$this->state="rtept";   					
   				}   				
   				break;   								
   			//Track related
   			case "TRK":
   				if ($this->state=="gpx") {
   					$this->trknr++;  					
   					$this->trkptnr=-1;
   					$this->trkid = xmlgooglemaps_dbfunctions::gpxInsertMainItem($this->gpxfileid,'TRK',$this->trknr);
   					$this->state="trk";
   				}
   				break;
   			case "TRKPT":
   				if ($this->state=="trk") {
   					$this->trkptnr++; 
   					$this->trkptid = xmlgooglemaps_dbfunctions::gpxInsertSubItem($this->gpxfileid,$this->trkid, 'TRKPT', $this->trkptnr);
   					xmlgooglemaps_dbfunctions::gpxUpdateItemLatLon($this->trkptid,floatval(trim($attribs[LAT])),floatval(trim($attribs[LON])));
   					$this->state="trkpt";   					
   				}   				
   				break;
   			//General
   			case "NAME":   				
   				switch ($this->state) {
   					case "wpt":   					
   					case "trk":
   					case "rte":
   					case "rtept":   						
   					case "trkpt":
   						$this->innertext = "";
   						$this->state .= "_name"; 
   						break;
   				}				
   				break;  
   			case "DESCRIPTION":
   				switch ($this->state) { 
   					case "wpt":
   					case "rte":
   					case "rtept":     						  					
   					case "trk":
   					case "trkpt": 						
   						$this->innertext = "";
   						$this->state .= "_desc"; 
   						break;
   				}				
   				break;     				 				
   			case "ELE":
   				switch ($this->state) { 
   					case "wpt":  
   					case "rtept":   						 					
   					case "trkpt":   						
   						$this->innertext = "";
   						$this->state .= "_ele"; 
   						break;
   				}				
   				break;   
   			case "TIME":
   				switch ($this->state) {
   					case "wpt":
   					case "rtept":   						
   					case "trkpt":
   						$this->innertext = "";
   						$this->state .= "_time"; 
   						break;
   				}				
   				break;    
			case "GPXTPX:HR":
   				switch ($this->state) {   					
   					case "trkpt":
   						$this->innertext = "";
   						$this->state .= "_hr"; 
   						break;
   				}				
   				break;    
			case "LINK":
   				switch ($this->state) { 
   					case "wpt":
						xmlgooglemaps_dbfunctions::gpxUpdateItemLink($this->wptid,trim($attribs[HREF]));
   						break;   						
   					case "rtept":
						xmlgooglemaps_dbfunctions::gpxUpdateItemLink($this->rteptid,trim($attribs[HREF]));
   						break;   						
   					case "trkpt":
						xmlgooglemaps_dbfunctions::gpxUpdateItemLink($this->trkptid,trim($attribs[HREF]));
   						break;
   				}				
   				break;	
   			case "SYM":
   				switch ($this->state) { 
   					case "wpt":  					
   						$this->innertext = "";
   						$this->state .= "_sym"; 
   						break;
   				}				
   				break;    							
   		}
   }

   function dataHandler($parser, $data){
   	    if ((strright($this->state,5)=="_name") 
   	    	|| (strright($this->state,4)=="_ele")
   	    	|| (strright($this->state,4)=="_sym")  
   	    	|| (strright($this->state,5)=="_desc") 
   	    	|| (strright($this->state,5)=="_link") 
   	    	|| (strright($this->state,5)=="_time") 
   	    	|| (strright($this->state,3)=="_hr")) {
   	    	$this->innertext .= $data;
   	    }	   	
   }

   function endHandler($parser, $name){
      	switch (strtoupper($name)) {
      		//Header
   			case "GPX":
   				$this->state="end";
   				break;
   			//Waypoint
   			case "WPT":
   				$this->state="gpx";
   				break;   
   			//Route
   			case "RTE":
   				$this->state="gpx";
   				break;   
   			case "RTEPT":
   				$this->state="rte";
   				break;      				
   			//Track
   			case "TRK":
   				$this->state="gpx";
   				break;
   			case "TRKPT":
   				$this->state="trk"; 
   				break;
   			//General  				
   			case "NAME":
   				switch ($this->state) {
   					case "wpt_name":
						xmlgooglemaps_dbfunctions::gpxUpdateItemName($this->wptid,trim($this->innertext));
		   				$this->state = "wpt"; 
		   				break;
   					case "rte_name":   						
						xmlgooglemaps_dbfunctions::gpxUpdateItemName($this->rteid,trim($this->innertext));
		   				$this->state = "rte"; 
		   				break;
   					case "rtept_name":
						xmlgooglemaps_dbfunctions::gpxUpdateItemName($this->rteptid,trim($this->innertext));
		   				$this->state = "rtept"; 
		   				break;			   						   				   					
   					case "trk_name":
		   				xmlgooglemaps_dbfunctions::gpxUpdateItemName($this->trkid,trim($this->innertext));
		   				$this->state = "trk";   						
   						break;
   					case "trkpt_name":
						xmlgooglemaps_dbfunctions::gpxUpdateItemName($this->trkptid,trim($this->innertext));
		   				$this->state = "trkpt";   						
   						break;
 
   				}
   				break;
   			case "DESCRIPTION":
   				switch ($this->state) {
   					case "wpt_desc":
						xmlgooglemaps_dbfunctions::gpxUpdateItemDescription($this->wptid,trim($this->innertext));
		   				$this->state = "wpt";
		   				break;  
   					case "rte_desc":   						
						xmlgooglemaps_dbfunctions::gpxUpdateItemDescription($this->rteid,trim($this->innertext));
		   				$this->state = "rte";
		   				break;  
   					case "rtept_desc":
						xmlgooglemaps_dbfunctions::gpxUpdateItemDescription($this->rteptid,trim($this->innertext));
		   				$this->state = "rtept";
		   				break;  		   						   				   					
   					case "trk_desc":
		   				xmlgooglemaps_dbfunctions::gpxUpdateItemDescription($this->trkid,trim($this->innertext));
		   				$this->state = "trk";   						
   						break;
   					case "trkpt_desc":
						xmlgooglemaps_dbfunctions::gpxUpdateItemDescription($this->trkptid,trim($this->innertext));
		   				$this->state = "trkpt";   						
   						break;   

   				}
   				break;  				
   			case "ELE":
   				if (is_numeric(trim($this->innertext))) {
	   				switch ($this->state) {
	   					case "wpt_ele":
							xmlgooglemaps_dbfunctions::gpxUpdateItemElevation($this->wptid,floatval(trim($this->innertext)));
			   				$this->state = "wpt"; 
			   				break; 	
	   					case "rtept_ele":
							xmlgooglemaps_dbfunctions::gpxUpdateItemElevation($this->rteptid,floatval(trim($this->innertext)));
			   				$this->state = "rtept"; 
			   				break; 			   				   					
	   					case "trkpt_ele":
		   					xmlgooglemaps_dbfunctions::gpxUpdateItemElevation($this->trkptid,floatval(trim($this->innertext)));
		   					$this->state = "trkpt";   						 						
	   						break;	   						
	   				}
   				}
   				break;
   			case "TIME":
      	   		switch ($this->state) {
   					case "wpt_time":
	   					xmlgooglemaps_dbfunctions::gpxUpdateItemTime($this->wptid,xmlgooglemaps_helper::xmlDate2DateTime(trim($this->innertext)));
	   					$this->state = "wpt";	   						  						 						
   						break;   
   					case "rtept_time":
	   					xmlgooglemaps_dbfunctions::gpxUpdateItemTime($this->rteptid,xmlgooglemaps_helper::xmlDate2DateTime(trim($this->innertext)));
	   					$this->state = "rtept";	   						  						 						
   						break;    						   	   			
   					case "trkpt_time":
	   					xmlgooglemaps_dbfunctions::gpxUpdateItemTime($this->trkptid,xmlgooglemaps_helper::xmlDate2DateTime(trim($this->innertext)));
	   					$this->state = "trkpt";	   						  						 						
   						break;
   				}   				
   				break;
   			case "GPXTPX:HR":
      	   		if (is_numeric(trim($this->innertext))) {
	   				switch ($this->state) {
	   					case "trkpt_hr":
		   					xmlgooglemaps_dbfunctions::gpxUpdateItemHeartRate($this->trkptid,floatval(trim($this->innertext)));
		   					$this->state = "trkpt";   						 						
	   						break;
	   				}
   				}   				
				break;   	
   			case "SYM":
   				switch ($this->state) {
   					case "wpt_sym":
						xmlgooglemaps_dbfunctions::gpxUpdateItemSymbol($this->wptid,trim($this->innertext));
		   				$this->state = "wpt";
		   				break;  
   				}
   				break; 							   				
   		}
   }   
}

if(!function_exists("strright"))
{
	function strright($str, $charcount)
	{
		return substr($str,strlen($str)-$charcount,$charcount);
	}
}

?>