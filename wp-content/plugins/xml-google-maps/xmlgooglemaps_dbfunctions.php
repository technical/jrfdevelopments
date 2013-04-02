<?php
/*
 * Diese Klasse enthält sämtliche DB Funktionen
 */
class xmlgooglemaps_dbfunctions {		

	function install() {
		global $wpdb;
		$charset_collate = '';
		if ( version_compare(mysql_get_server_info(), '4.1.0', '>=') ) {
			if ( ! empty($wpdb->charset) )
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if ( ! empty($wpdb->collate) )
				$charset_collate .= " COLLATE $wpdb->collate";
		}
		
		$ret = true;
		if ($ret === true) { $ret = xmlgooglemaps_dbfunctions::update001($charset_collate); }
		if ($ret === true) { $ret = xmlgooglemaps_dbfunctions::update002($charset_collate); }

	}
	
	//Installiert Datenbank Version 1 war bis und mit Version 1.11 des Plugins aktuell
	function update001($charset_collate) {
		global $wpdb;
		$ret = true;
		if (($ret !== false) && !xmlgooglemaps_dbfunctions::tableExists($wpdb->xmlgooglemapsdb)) {
			$create_sql = "CREATE TABLE $wpdb->xmlgooglemapsdb (".
				"xml_id BIGINT(20) NOT NULL auto_increment,".
				"xml_path TEXT NOT NULL ,".			
				"PRIMARY KEY (xml_id)) $charset_collate;";
			$ret = $wpdb->query($wpdb->prepare($create_sql));
		}
		if (($ret !== false) && !xmlgooglemaps_dbfunctions::columnExists($wpdb->xmlgooglemapsdb,"xml_varstring")) {
			$column_sql = "ALTER TABLE $wpdb->xmlgooglemapsdb ADD COLUMN xml_varstring TEXT NOT NULL;";
			$ret = $wpdb->query($wpdb->prepare($column_sql));
		}
		if (($ret !== false) && xmlgooglemaps_dbfunctions::columnExists($wpdb->xmlgooglemapsdb,"xml_path_resolved")) {
			$column_sql = "ALTER TABLE $wpdb->xmlgooglemapsdb DROP xml_path_resolved;";
			$ret = $wpdb->query($wpdb->prepare($column_sql));
		}
		if (($ret !== false) && !xmlgooglemaps_dbfunctions::tableExists($wpdb->xmlgooglemapsdbcache)) {
			$create_sql = "CREATE TABLE $wpdb->xmlgooglemapsdbcache (".
				"xml_id BIGINT(20) NOT NULL,".
				"xml_cache_key VARCHAR(20) NOT NULL ,".
				"xml_cache_lastupdated DATETIME NOT NULL ,".
				"xml_cache_content LONGTEXT NOT NULL ,".
				"PRIMARY KEY (xml_id, xml_cache_key)) $charset_collate;";
			$ret = $wpdb->query($wpdb->prepare($create_sql));
		}
		if (($ret !== false) && strtolower(xmlgooglemaps_dbfunctions::columnType($wpdb->xmlgooglemapsdbcache,"xml_cache_content")) == "text") {
			$column_sql = "ALTER TABLE $wpdb->xmlgooglemapsdbcache CHANGE xml_cache_content xml_cache_content LONGTEXT NOT NULL";
			$ret = $wpdb->query($wpdb->prepare($column_sql));
			if ($ret !== false) { xmlgooglemaps_dbfunctions::cacheClearAll(); }
		}
		if (($ret !== false) && (get_option(key_xmlgm_current_db_version)<1)) { update_option(key_xmlgm_current_db_version, 1); }
		return $ret;			
	}
	
	//Installiert Datenbank Version 2
	function update002($charset_collate) {
		global $wpdb;
		$ret = true;
		if (($ret !== false) && strtolower(xmlgooglemaps_dbfunctions::columnType($wpdb->xmlgooglemapsdbcache,"xml_id")) == "int(11)") {
			$column_sql = "ALTER TABLE $wpdb->xmlgooglemapsdbcache CHANGE xml_id xml_id BIGINT NOT NULL auto_increment";
			$ret = $wpdb->query($wpdb->prepare($column_sql));		
		}
		if (($ret !== false) && strtolower(xmlgooglemaps_dbfunctions::columnType($wpdb->xmlgooglemapsdb,"xml_id")) == "int(11)") {
			$column_sql = "ALTER TABLE $wpdb->xmlgooglemapsdb CHANGE xml_id xml_id BIGINT NOT NULL auto_increment";
			$ret = $wpdb->query($wpdb->prepare($column_sql));			
		}	

		if (($ret !== false) && !xmlgooglemaps_dbfunctions::tableExists($wpdb->xmlgooglemapsdbgpxfile)) {
			$create_sql = "CREATE TABLE $wpdb->xmlgooglemapsdbgpxfile (".
  						"`gpxfile_id` bigint(20) NOT NULL AUTO_INCREMENT,".
  						"`filepath` text NOT NULL,".
  						"`fileupdatetime` datetime NOT NULL,".
  						"`maxlat` double DEFAULT NULL,".
  						"`maxlon` double DEFAULT NULL,".
  						"`minlat` double DEFAULT NULL,".
  						"`minlon` double DEFAULT NULL,".
  						"PRIMARY KEY (`gpxfile_id`)) $charset_collate";
			$ret = $wpdb->query($wpdb->prepare($create_sql));
		}
		
		if (($ret !== false) && !xmlgooglemaps_dbfunctions::tableExists($wpdb->xmlgooglemapsdbgpxfileitem)) {
			$create_sql = "CREATE TABLE $wpdb->xmlgooglemapsdbgpxfileitem (".
					  "`item_id` bigint(20) NOT NULL AUTO_INCREMENT,".
					  "`item_nr` bigint(20) NOT NULL,".
					  "`gpxfile_id` bigint(20) NOT NULL,".
					  "`element` ENUM( 'RTE', 'RTEPT', 'TRK', 'TRKPT', 'WPT' ),".
					  "`parent_item_id` int(11) DEFAULT NULL,".
					  "`name` text,".
					  "`description` text,".
					  "`symbol` text,".
					  "`link` text,".
					  "`time` datetime DEFAULT NULL,".
					  "`lat` double DEFAULT NULL,".
					  "`lon` double DEFAULT NULL,".
					  "`ele` double DEFAULT NULL,".
					  "`heartrate` double DEFAULT NULL,".
					  "PRIMARY KEY (`item_id`)) $charset_collate";
			$ret = $wpdb->query($wpdb->prepare($create_sql));
		}		
		if (($ret !== false) && (get_option(key_xmlgm_current_db_version)<2)) { 
			update_option(key_xmlgm_current_db_version, 2);
			xmlgooglemaps_dbfunctions::gpxClearAll();
		}
		return $ret;
	}	
	
	

	function tableExists($tablename) {
		global $wpdb;
		foreach ($wpdb->get_col("SHOW TABLES",0) as $table ) {
			if ($table == $tablename) {
				return true;
			}
		}
		return false;
	}

	function columnExists($tablename, $columnname) {
		global $wpdb;
		$col1 = $wpdb->get_col("DESC $tablename", 0);
		foreach ($col1 as $column ) {
			if ($column == $columnname) {
				return true;
			}
		}
		return false;
	}
	
	function columnType($tablename, $columnname) {
		global $wpdb;
		$rows = $wpdb->get_results("DESC $tablename");
		foreach ($rows as $key => $value) {
			if ($value->Field == $columnname) {
				return $value->Type;
			}
		}
		return "";		
	}

	function cacheClear($id, $key, $cacheDuration) {
		global $wpdb;
		if ($cacheDuration==-1) {
			$wpdb->query($wpdb->prepare("DELETE FROM $wpdb->xmlgooglemapsdbcache WHERE xml_id=%d AND xml_cache_key=%s",$id,$key));			
		} else {
			if ($cacheDuration>0) {
				$expirationTime = date('Y-m-d H:i:s', time() - 60 * $cacheDuration);
				$wpdb->query($wpdb->prepare("DELETE FROM $wpdb->xmlgooglemapsdbcache WHERE xml_id=%d AND xml_cache_key=%s AND xml_cache_lastupdated<%s",$id,$key,$expirationTime));
			}
		}
	}
	
	function cacheClearAll() {
		global $wpdb;
		return $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->xmlgooglemapsdbcache"));			
	}	
	
	function cacheHas($id, $key, $cacheDuration) {
		global $wpdb;
		xmlgooglemaps_dbfunctions::cacheClear($id,$key,$cacheDuration);		
		$ret = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->xmlgooglemapsdbcache WHERE xml_id=%d AND xml_cache_key=%s", $id, $key));
		return intval($ret)>0;	
	}
	
	function cacheGetCount() {
		global $wpdb;		
		$ret = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->xmlgooglemapsdbcache", $id, $key));
		return intval($ret);			
	}
	
	function cacheGet($id, $key, $cacheDuration) {
		global $wpdb;
		if (xmlgooglemaps_dbfunctions::cacheHas($id,$key,$cacheDuration)) {	
			$ret = $wpdb->get_var($wpdb->prepare("SELECT xml_cache_content FROM $wpdb->xmlgooglemapsdbcache WHERE xml_id=%d AND xml_cache_key=%s", $id, $key));
			return $ret;	
		} else {
			return false;
		} 
	}
	
	function cacheSet($id, $key, $value) {
		global $wpdb;
		xmlgooglemaps_dbfunctions::cacheClear($id,$key,-1);
		$now = date('Y-m-d H:i:s', time());	
		$wpdb->query($wpdb->prepare("INSERT INTO $wpdb->xmlgooglemapsdbcache (xml_id, xml_cache_key, xml_cache_lastupdated, xml_cache_content) VALUES (%d, %s, %s, %s)", $id, $key, $now, $value));
	}

	function xmlGetId($path, $varstring) {
		global $wpdb;
		// Check IP From IP Logging Database
		$ret = $wpdb->get_var($wpdb->prepare("SELECT xml_id FROM $wpdb->xmlgooglemapsdb WHERE xml_path = %s AND xml_varstring = %s",$path,$varstring));
		if ($ret == 0) {
			$wpdb->query($wpdb->prepare("INSERT INTO $wpdb->xmlgooglemapsdb (xml_path, xml_varstring) VALUES (%s,%s)",$path,$varstring));
			$ret = xmlgooglemaps_dbfunctions::xmlGetId($path, $varstring);
		}
		return intval($ret);
	}

	function xmlGetPath($id) {
		global $wpdb;
		// Check IP From IP Logging Database
		$ret = $wpdb->get_var($wpdb->prepare("SELECT xml_path FROM $wpdb->xmlgooglemapsdb WHERE xml_id = %d",$id));
		return $ret;
	}


	function xmlGetString($id) {
		global $wpdb;
		// Check IP From IP Logging Database
		$ret = $wpdb->get_var($wpdb->prepare("SELECT xml_varstring FROM $wpdb->xmlgooglemapsdb WHERE xml_id = %d",$id));
		return $ret;
	}
	
	function gpxClearAll() {
		global $wpdb;
		$sql = "TRUNCATE TABLE $wpdb->xmlgooglemapsdbgpxfile";
		$ret = $wpdb->query($wpdb->prepare($sql));
		$sql = "TRUNCATE TABLE $wpdb->xmlgooglemapsdbgpxfileitem"; 
		$ret = $wpdb->query($wpdb->prepare($sql));
	}
	
	function gpxFileExists($url) {
		global $wpdb;
		// Check IP From IP Logging Database
		$ret = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->xmlgooglemapsdbgpxfile WHERE filepath = %s",strtolower($url)));
		return intval($ret)>0;		
	}
	
	function gpxGetFileID($url) {
		global $wpdb;
		$ret = $wpdb->get_var($wpdb->prepare("SELECT gpxfile_id FROM $wpdb->xmlgooglemapsdbgpxfile WHERE filepath = %s",strtolower($url)));		
		return intval($ret);
	}
	
	function gpxGetFileModDate($id) {
		global $wpdb;
		$ret = $wpdb->get_var($wpdb->prepare("SELECT fileupdatetime FROM $wpdb->xmlgooglemapsdbgpxfile WHERE gpxfile_id = %d",$id));		
		return strtotime($ret);
	}
	
	function gpxUpdatePath($url, $modifiedtime) {
		global $wpdb;
		$modifiedtime = date('Y-m-d H:i:s', $modifiedtime);	
		$id=xmlgooglemaps_dbfunctions::gpxGetFileID($url);		
		if ($id<=0) {
			$sql = $wpdb->prepare("INSERT INTO $wpdb->xmlgooglemapsdbgpxfile (filepath,fileupdatetime,maxlat,maxlon,minlat,minlon) VALUES (%s,%s,NULL,NULL,NULL,NULL)",strtolower($url),$modifiedtime);
			$wpdb->query($sql);
			$id=xmlgooglemaps_dbfunctions::gpxGetFileID($url);				
		} else {
			$sql = $wpdb->prepare("UPDATE $wpdb->xmlgooglemapsdbgpxfile SET fileupdatetime=%s, maxlat=NULL, maxlon=NULL, minlat=NULL, minlon=NULL WHERE gpxfile_id=%d",$modifiedtime,$id);
			$wpdb->query($sql);											
		}		
		return $id;	
	}
	
	function gpxUpdateBounds($fileid, $maxlat, $minlat, $maxlon, $minlon) {
		global $wpdb;
		$sql = $wpdb->prepare("UPDATE $wpdb->xmlgooglemapsdbgpxfile SET maxlat=%f, maxlon=%f, minlat=%f, minlon=%f WHERE gpxfile_id=%d",$maxlat,$maxlon,$minlat,$minlon,$fileid);
		$wpdb->query($sql);
	}
	
	function gpxClearAllDetailData($fileid) {
		global $wpdb;
		$sql = $wpdb->prepare("DELETE FROM $wpdb->xmlgooglemapsdbgpxfileitem WHERE gpxfile_id=%d",$fileid);
		$wpdb->query($sql);		
	}
	
	function gpxInsertMainItem($fileid,$element,$itemnr) {
		global $wpdb;
		$sql = $wpdb->prepare("INSERT INTO $wpdb->xmlgooglemapsdbgpxfileitem (item_nr, gpxfile_id, element) VALUES (%d, %d, %s)",$itemnr,$fileid,strtoupper($element));		
		$wpdb->query($sql);		
		return $wpdb->insert_id;
	}
	
	function gpxInsertSubItem($fileid,$parentid,$element,$itemnr) {
		global $wpdb;		
		$sql = $wpdb->prepare("INSERT INTO $wpdb->xmlgooglemapsdbgpxfileitem (item_nr,gpxfile_id,element,parent_item_id) VALUES (%d, %d, %s, %d)",$itemnr,$fileid,strtoupper($element),$parentid);		
		$wpdb->query($sql);		
		return $wpdb->insert_id;
	}
	
	function gpxUpdateItemName($itemid,$name) {
		global $wpdb;
		$sql = $wpdb->prepare("UPDATE $wpdb->xmlgooglemapsdbgpxfileitem SET name=%s WHERE item_id=%d",$name,$itemid);
		$wpdb->query($sql);
	}
	
	function gpxUpdateItemDescription($itemid,$desc) {
		global $wpdb;
		$sql = $wpdb->prepare("UPDATE $wpdb->xmlgooglemapsdbgpxfileitem SET description=%s WHERE item_id=%d",$desc,$itemid);
		$wpdb->query($sql);
	}

	function gpxUpdateItemLink($itemid,$link) {
		global $wpdb;
		$sql = $wpdb->prepare("UPDATE $wpdb->xmlgooglemapsdbgpxfileitem SET link=%s WHERE item_id=%d",$link,$itemid);
		$wpdb->query($sql);
	}
	
	function gpxUpdateItemSymbol($itemid,$sym) {
		global $wpdb;
		$sql = $wpdb->prepare("UPDATE $wpdb->xmlgooglemapsdbgpxfileitem SET symbol=%s WHERE item_id=%d",$sym,$itemid);
		$wpdb->query($sql);
	}	
	
	function gpxUpdateItemLatLon($itemid,$lat,$lon) {		
		global $wpdb;
		$sql = $wpdb->prepare("UPDATE $wpdb->xmlgooglemapsdbgpxfileitem SET lat=%f, lon=%f WHERE item_id=%d",$lat,$lon,$itemid);
		$wpdb->query($sql);		
	}
	
	function gpxUpdateItemElevation($itemid,$ele) {
		global $wpdb;
		$sql = $wpdb->prepare("UPDATE $wpdb->xmlgooglemapsdbgpxfileitem SET ele=%f WHERE item_id=%d",$ele,$itemid);
		$wpdb->query($sql);		
	}
	
	function gpxUpdateItemTime($itemid,$time) {
		global $wpdb;
		$time = date('Y-m-d H:i:s', $time);
		$sql = $wpdb->prepare("UPDATE $wpdb->xmlgooglemapsdbgpxfileitem SET time=%s WHERE item_id=%d",$time,$itemid);
		$wpdb->query($sql);		
	}	
	
	function gpxUpdateItemHeartRate($itemid,$hr) {
		global $wpdb;
		$sql = $wpdb->prepare("UPDATE $wpdb->xmlgooglemapsdbgpxfileitem SET heartrate=%f WHERE item_id=%d",$hr,$itemid);
		$wpdb->query($sql);		
	}
	
	function gpxGetMainItemCount($fileid,$element) {
		global $wpdb;		
		$sql = $wpdb->prepare("SELECT COUNT(*) FROM $wpdb->xmlgooglemapsdbgpxfileitem WHERE gpxfile_id=%d AND element=%s AND parent_item_id IS NULL",$fileid,$element);
		return intval($wpdb->get_var($sql));		
	}
	
	function gpxGetMainItem($fileid, $element, $itemnr) {
		global $wpdb;		
		$sql = $wpdb->prepare("SELECT * FROM $wpdb->xmlgooglemapsdbgpxfileitem WHERE gpxfile_id=%d AND element=%s AND item_nr=%d AND parent_item_id IS NULL",$fileid,$element,$itemnr);
		return $wpdb->get_row($sql);	
	}
	
	function gpxGetMainItems($fileid,$element,$start=-1,$length=-1) {
		global $wpdb;
		$limit = "";
		if (($start>=0) && ($length>0)) {
			$limit = "AND item_nr>=%d AND item_nr<%d";
		}
		$sql = $wpdb->prepare("SELECT * FROM $wpdb->xmlgooglemapsdbgpxfileitem WHERE gpxfile_id=%d AND element=%s AND parent_item_id IS NULL $limit ORDER BY item_nr",$fileid,$element, $start, $start+$length);
		return $wpdb->get_results($sql);		
	}	
		
	function gpxGetSubItemCount($fileid,$element,$parentitemid) {
		global $wpdb;		
		$sql = $wpdb->prepare("SELECT COUNT(*) FROM $wpdb->xmlgooglemapsdbgpxfileitem WHERE gpxfile_id=%d AND element=%s AND parent_item_id=%d",$fileid,$element,$parentitemid);
		return intval($wpdb->get_var($sql));		
	}
	
	function gpxGetSubItem($fileid,$element,$itemnr,$parentitemid) {
		global $wpdb;		
		$sql = $wpdb->prepare("SELECT * FROM $wpdb->xmlgooglemapsdbgpxfileitem WHERE gpxfile_id=%d AND element=%s AND item_nr=%d AND parent_item_id=%d",$fileid,$element, $itemnr, $parentitemid);
		return $wpdb->get_row($sql);		
	}
	
	function gpxGetSubItems($fileid,$element,$parentitemid,$start=-1,$length=-1) {
		global $wpdb;
		$limit = "";
		if (($start>=0) && ($length>0)) {
			$limit = "AND item_nr>=%d AND item_nr<%d";
		}
		$sql = $wpdb->prepare("SELECT * FROM $wpdb->xmlgooglemapsdbgpxfileitem WHERE gpxfile_id=%d AND element=%s AND parent_item_id=%d $limit ORDER BY item_nr",$fileid,$element, $parentitemid, $start, $start+$length);
		return $wpdb->get_results($sql);		
	}
	
}
?>