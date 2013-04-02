<?php
/*
 * Diese Klasse enthält sämtliche Google Charts API Funktionen
 */
class xmlgooglemaps_googleChartsApi {
	var $yscale; 
	var $nodes=50;
	var $ymin;
	var $ymax;
	var $xmax;
	var $xLabelPartitions=4;
    var $simpleEncoding = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789' ;
	var $valuesAtNodes;
    
	function xmlgooglemaps_googleChartsApi(&$xvalues, &$yvalues, $override_ymin=-9999, $override_ymax=-9999, $override_yscale=0) {
		$this->valuesAtNodes=xmlgooglemaps_googleChartsApi::getValuesAtNodes($xvalues,$yvalues) ;
		if ($override_ymin == -9999) {
			$min = min($this->valuesAtNodes);	
		} else {
			$min = $override_ymin;
		}
		if ($override_ymax == -9999) {
			$max = max($this->valuesAtNodes);
		} else {
			$max = $override_ymax;
		}	
		if ($override_yscale==0) {			
			$val1 = $max - $min;
			if ($val1 == 0) {
				$this->yscale = 1;
			} else {
				$val2 = $val1;
				$val3 = 1;
				while (round($val2,0)>10) {
					$val2 /= 10;
					$val3 *= 10;
				}	
				while (round($val2,0)<1) {
					$val2 *= 10;
					$val3 /= 10;
				}				
				$val4 = round($val2,0);
				switch ($val4)
				{
					case 10: $this->yscale = 10 * $val3 / 5;	break;
					case 1: $this->yscale = 1 * $val3 / 4;	break;
					case 2: $this->yscale = 2 * $val3 / 5;	break;
					case 3: $this->yscale = 3 * $val3 / 6;	break;
					case 4: $this->yscale = 4 * $val3 / 5;	break;
					case 5: $this->yscale = 5 * $val3 / 5;	break;
					case 6: $this->yscale = 6 * $val3 / 6;	break;
					case 7: $this->yscale = 6 * $val3 / 6;	break;
					case 8: $this->yscale = 8 * $val3 / 4;	break;
					case 9: $this->yscale = 8 * $val3 / 4;	break;			
				}
			}
		} else {
			$this->yscale = $override_yscale;
		}
		
		$this->ymin=floor($min/$this->yscale)*$this->yscale;
		$this->ymax=ceil($max/$this->yscale)*$this->yscale;
		$this->ypartitions=($this->ymax-$this->ymin)/$this->yscale;
		if ($this->ypartitions==0) {
			$this->ypartitions=1;	
		}
		$this->xmax=ceil($xvalues[count($xvalues)-1]);		 
	}
	
	function simpleEncodeValues(&$values, $min, $max) {
		$chartData= "" ;
		$distance= $max-$min;
		if ($distance==0) { $distance = 1; }
		for ($l = 0; $l < count($values); $l++) {
			$value=round(($values[$l]-$min)/$distance*61) ;
			$chartData = $chartData . $this->simpleEncoding[$value] ;
		}		
		return $chartData ;		
	}
		
	function getValuesAtNodes(&$xvalues,&$yvalues) {
//		$xmax= $xvalues[count($xvalues)-1];
//		$xstep= $xmax / ($this->nodes - 1 ) ;
//		$nextNode= 0;
//		$valuesAtNodes = array();
//		$k=0;
//		$l=0;
//		for ($j = 0; $j < count($xvalues); $j++) {
//			if ($xvalues[$j]>=$nextNode) {
//	    		$valuesAtNodes[$k++] = $yvalues[$j];
//				$nextNode += $xstep;
//			} else {
//				$l++;
//			}
//		}

		$valuesAtNodes = array();
		$xmax = $xvalues[count($xvalues)-1];
		$xstep = $xmax / ($this->nodes - 1) ;
		$xgrenze = $xstep / 2;
		$xweight = 0;
		$yweight = 0;
		$k=0;
		for ($j = 1; $j < count($xvalues); $j++) {
			$xvalue1 = $xvalues[$j-1];
			$xvalue2 = $xvalues[$j];
			$yvalue = $yvalues[$j];
			$xdiffweight=$xvalue2-$xvalue1;
			if ($xvalue1<$xgrenze) {
				$xweight += $xdiffweight;
				$yweight += $yvalue*$xdiffweight;					
			} else {				
				if ($xweight==0){
					$valuesAtNodes[$k++] = 0; 
				} else {
					$valuesAtNodes[$k++] = $yweight / $xweight;
				}
				$xweight = $xdiffweight;
				$yweight = $yvalue*$xdiffweight;
				$xgrenze += $xstep;
			}
		}
		if ($xweight==0){
			$valuesAtNodes[$k++] = 0; 
		} else {
			$valuesAtNodes[$k++] = $yweight / $xweight;
		}				
		return $valuesAtNodes;
	}

	function getEleYLabels($measurementtype) {
		$ylabels=array() ;
		for ($yentry=$this->ymin;$yentry <= $this->ymax;$yentry+=$this->yscale) {
			switch ($measurementtype){
				case "imperial":
				case "nautic_imperial":
					$ylabels[]=round($yentry,1)." ft";
					break;
				case "metric":
				case "nautic_metric":
				default:
					$ylabels[]=round($yentry,1)." m";
					break;					
			}  			
		}
		return $ylabels;
	}
	
	function getEleXLabels($measurementtype) {
		$xlabels=array();
		$xstep=$this->xmax/$this->xLabelPartitions;
		for ($xentry=0;$xentry <= $this->xLabelPartitions;$xentry++) {
			switch ($measurementtype){
				case "imperial":
					$xlabels[]=round($xentry * $xstep, 1)." mi";
					break;
				case "nautic_imperial":
				case "nautic_metric":
					$xlabels[]=round($xentry * $xstep, 1)." nm";
					break;
				case "metric":			
				default:
					$xlabels[]=round($xentry * $xstep, 1)." km";
					break;					
			}  					
		}
		return $xlabels ;
	}
	
	function getHRYLabels($measurementtype) {
		$ylabels=array() ;
		for ($yentry=$this->ymin;$yentry <= $this->ymax;$yentry+=$this->yscale) {
			$ylabels[]=round($yentry,1)." bpm";
		}
		return $ylabels;
	}	

	function getHRXLabels($measurementtype) {
		$xlabels=array();
		$xstep=$this->xmax/$this->xLabelPartitions;
		for ($xentry=0;$xentry <= $this->xLabelPartitions;$xentry++) {
			switch ($measurementtype){
				case "imperial":
					$xlabels[]=round($xentry * $xstep, 1)." mi";
					break;
				case "nautic_imperial":
				case "nautic_metric":
					$xlabels[]=round($xentry * $xstep, 1)." nm";
					break;
				case "metric":			
				default:
					$xlabels[]=round($xentry * $xstep, 1)." km";
					break;					
			}  					
		}
		return $xlabels ;
	}
	
	function getSpeedYLabels($measurementtype, $speedscale) {
		$ylabels=array() ;
		for ($yentry=$this->ymin;$yentry <= $this->ymax;$yentry+=$this->yscale) {
			switch (strtolower($measurementtype.$speedscale)) {
				case "imperiallevel2":
					$ylabels[]=round($yentry,1)." mph";
					break;
				case "imperiallevel1":
					$ylabels[]=round($yentry,1)." fps";
					break;
				case "metriclevel1":
					$ylabels[]=round($yentry,1)." m/s";
					break;
				case "nautic_metriclevel1":
				case "nautic_metriclevel2":
				case "nautic_imperiallevel1":
				case "nautic_imperiallevel2":
					$ylabels[]=round($yentry,1)." kn";
					break;
				default:
				case "metriclevel2":
					$ylabels[]=round($yentry,1)." km/h";
					break;
					
			}			  			
		}
		return $ylabels;
	}
	
	function getSpeedXLabels($measurementtype) {
		$xlabels=array();
		$xstep=$this->xmax/$this->xLabelPartitions;
		for ($xentry=0;$xentry <= $this->xLabelPartitions;$xentry++) {
			switch (strtolower($measurementtype)) {
				case "imperial":
					$xlabels[]=round($xentry * $xstep, 1)." mi";
					break;
				case "nautic_imperial":
				case "nautic_metric":
					$xlabels[]=round($xentry * $xstep, 1)." nm";
					break;					
				default:
				case "metric":
					$xlabels[]=round($xentry * $xstep, 1)." km";
					break;
			}
		}
		return $xlabels ;
	}	
	
	function getEleUrl($width, $height, $linecolor, $linewidth, $measurementtype,$title="", $checkpoint=0) {
		$chart = array() ;
		// Type
		$chart['cht']="lc" ;
		// line style
		$chart['chls']=$linewidth.",0,0";
		// fill with horizontal stripes (90 degree)		
		$stripeHeight=1/$this->ypartitions;
		$chart['chf']="c,ls,90,CCCCCC,".$stripeHeight.",FFFFFF,".$stripeHeight;
		// Labels 
		$chart['chxt'] = "x,y";
		$chart['chxl'] = "0:|".join("|",$this->getEleXLabels($measurementtype))."|1:|".join("|",$this->getEleYLabels($measurementtype)) ;
		// Checkpoint Line
		if ($checkpoint>0) {
			$checkpoint = $checkpoint*100/$this->xmax;
			$chart['chg'] =	$checkpoint.",0";
		}
		// Data		
		$chart['chd']="s:".$this->simpleEncodeValues($this->valuesAtNodes, $this->ymin, $this->ymax);
		// Size
		$chart['chs']=$width."x".$height;
		$chart['chco']=$linecolor;
		// Title
		if (strlen($title)>0){
			$chart['chtt'] = str_replace(" ","+",$title);
			$chart['chts'] = "555555,12";
		}
		$params=array() ;
		foreach ($chart as $key => $value){
	    	$params[] = $key."=".$value;
  		}
		return "http://chart.apis.google.com/chart?".join("&",$params);
	}

	function getHRUrl($width, $height, $linecolor, $linewidth, $measurementtype,$title="", $checkpoint=0) {
		$chart = array() ;
		// Type
		$chart['cht']="lc" ;
		// line style
		$chart['chls']=$linewidth.",0,0";
		// fill with horizontal stripes (90 degree)		
		$stripeHeight=1/$this->ypartitions;
		$chart['chf']="c,ls,90,CCCCCC,".$stripeHeight.",FFFFFF,".$stripeHeight;
		// Labels 
		$chart['chxt'] = "x,y";
		$chart['chxl'] = "0:|".join("|",$this->getHRXLabels($measurementtype))."|1:|".join("|",$this->getHRYLabels($measurementtype)) ;
		// Checkpoint Line
		if ($checkpoint>0) {
			$checkpoint = $checkpoint*100/$this->xmax;
			$chart['chg'] =	$checkpoint.",0";
		}
		// Data		
		$chart['chd']="s:".$this->simpleEncodeValues($this->valuesAtNodes, $this->ymin, $this->ymax);
		// Size
		$chart['chs']=$width."x".$height;
		$chart['chco']=$linecolor;
		// Title
		if (strlen($title)>0){
			$chart['chtt'] = str_replace(" ","+",$title);
			$chart['chts'] = "555555,12";
		}
		$params=array() ;
		foreach ($chart as $key => $value){
	    	$params[] = $key."=".$value;
  		}
		return "http://chart.apis.google.com/chart?".join("&",$params);
	}
	
	function getSpeedUrl($width, $height, $linecolor, $linewidth, $measurementtype, $speedscale, $title="", $checkpoint=0) {
		$chart = array() ;
		// Type
		$chart['cht']="lc" ;
		// line style
		$chart['chls']=$linewidth.",0,0";
		// fill with horizontal stripes (90 degree)
		$stripeHeight=1/$this->ypartitions;
		$chart['chf']="c,ls,90,CCCCCC,".$stripeHeight.",FFFFFF,".$stripeHeight;
		// Labels
		$chart['chxt']="x,y";
		$chart['chxl']="0:|".join("|",$this->getSpeedXLabels($measurementtype))."|1:|".join("|",$this->getSpeedYLabels($measurementtype, $speedscale)) ;
		// Data
		$chart['chd']="s:".$this->simpleEncodeValues($this->valuesAtNodes, $this->ymin, $this->ymax);
		// Checkpoint Line
		if ($checkpoint>0) {
			$checkpoint = $checkpoint*100/$this->xmax;
			$chart['chg'] =	$checkpoint.",0";
		}		
		// Size
		$chart['chs']=$width."x".$height;
		$chart['chco']=$linecolor;
		// Title
		if (strlen($title)>0) {
			$chart['chtt'] = str_replace(" ","+",$title);
			$chart['chts'] = "555555,12";
		}		
		$params=array() ;
		foreach ($chart as $key => $value){
	    	$params[] = $key."=".$value;
  		}
		return "http://chart.apis.google.com/chart?".join("&",$params);
	}
	
}
?>