<?php
/*
 * Diese Klasse enthält den KML Parser
 */
if(!function_exists("stripos")) 
{
  function stripos($str, $needle) 
  {
   return strpos(strtolower($str),strtolower($needle));
  }
}

class xmlgooglemaps_kmlParser {
   var $xml_obj = null;
   var $output = array();
   var $coordinates = array();
   var $coordinatescount = 0;
   var $currentdata=null;
   var $currentarray=array();
   var $readingdata=0;
      
   function parse($url){
       $this->xml_obj = xml_parser_create();
       xml_set_object($this->xml_obj,$this);
       xml_set_character_data_handler($this->xml_obj, 'dataHandler');
       xml_set_element_handler($this->xml_obj, "startHandler", "endHandler");
   	
       $old = error_reporting(1);
       
       $filecontent = xmlgooglemaps_helper::getFileContent($url);
       
       if ($filecontent === false) {
           xml_parser_free($this->xml_obj);
           error_reporting($old);
           return false;
       } else  {
		   error_reporting($old);
       }
       
       if (!xml_parse($this->xml_obj, $filecontent)) {
       	   xml_parser_free($this->xml_obj);
           return false;
       }
       xml_parser_free($this->xml_obj);
       
       return true;
   }

   function startHandler($parser, $name, $attribs){
   		switch ($name) {
   			case "COORDINATES":
   				$this->currentdata=null;
   				$this->currentdataarray=array();
				$this->readingdata=1;   				
   				break;
			default:
				//echo $name."<br>";
				break;
   		}
   }

   function dataHandler($parser, $data){
   	if ($this->readingdata) {
   		$this->currentdata = $data;
   		$this->currentdataarray[$this->readingdata] = trim($data);
   		$this->readingdata++;
   	}
   }

   function endHandler($parser, $name){
   	  $this->readingdata=0;
      switch ($name) {
      	case "COORDINATES":      		
      	   for ($i=1; $i<=count($this->currentdataarray);$i++) {
      			preg_match_all('/([0-9\-]+?.[0-9]+?),/', $this->currentdataarray[$i], $found);
      			if ((count($found) == 2)) {
      				for ($j; $j<count($found[1]); $j+=2) {
	      				$this->output["coordinates"][$this->coordinatescount]["lon"] = $found[1][$j+0];
	      				$this->output["coordinates"][$this->coordinatescount]["lat"] = $found[1][$j+1];
	      				$this->coordinatescount++;
      				}
      			}
      	   }
      	   break;
      	default:
      	   break;
      }
   }
   
   
}
?>