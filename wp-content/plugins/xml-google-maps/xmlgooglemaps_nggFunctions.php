<?php
/*
 * Dies Klasse beinhaltet sämtliche NextGEN Gallery relevanten Funktionen
 */
class xmlgooglemaps_nggFunctions {

	function isNggInstalled() {
		if (!function_exists('is_plugin_active')) {
			require_once(ABSPATH . '/wp-admin/includes/plugin.php');
		}		
		return is_plugin_active("nextgen-gallery/nggallery.php");
	}

	function isExifInstalled() {
		return xmlgooglemaps_exif::isInstalled();
	}

	function getNggPics($galleries,$pics) {
		global $wpdb;
		$ret = array();
		$pictures = array();
		foreach ($galleries as $gallery) {						
// 			Direct access to database by Patrick Matusz
// 			$gallery = $wpdb->escape($gallery);
// 			$pictures = array_merge($pictures, $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggallery AS t INNER JOIN $wpdb->nggpictures AS tt ON t.gid=tt.galleryid WHERE t.gid = '$gallery' AND tt.exclude != 1"));
			$pictures = array_merge($pictures, nggdb::get_gallery($gallery, false, false, true, $capture_date));
		}

		foreach ($pics as $pic) {
// 			Direct access to database by Patrick Matusz
// 			$pic = $wpdb->escape($pic);
// 			$pictures = array_merge($pictures , $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggallery AS t INNER JOIN $wpdb->nggpictures AS tt ON t.gid=tt.galleryid WHERE tt.pid = '$pic' AND tt.exclude != 1"));

			array_push($pictures, nggdb::find_image($pic));
		}
		
		foreach ($pictures as $picrow) {
			$item = array();
// 			Direct access to database by Patrick Matusz
// 			$thumb = $this->get_thumbnail_addition($picrow->path);
// 			$item["path"] = get_option('siteurl')."/".$picrow->path."/".$picrow->filename;
// 			$item["abspath"] = WINABSPATH."/".$picrow->path."/".$picrow->filename;
// 			$item["thumbpath"] = get_option('siteurl')."/".$picrow->path.$thumb.$picrow->filename;
// 			$item["description"] = html_entity_decode(stripslashes($picrow->description));
// 			$item["title"] = html_entity_decode(stripslashes($picrow->alttext));
// 			$item["galtitle"] = $picrow->title;
// 			$item["galdescription"] = html_entity_decode(stripslashes($picrow->galdesc));
// 			$exif = new xmlgooglemaps_exif($item["abspath"]);
			$item["nggimage"] = $picrow;
			$exif = new xmlgooglemaps_exif($item["nggimage"]->imagePath);			
			if ($exif->isValid) {				
				$item["gpslat"] = $exif->getGPSLatitude();
				$item["gpslon"] = $exif->getGPSLongitude();
				if (($item["gpslat"] != 0) || ($item["gpslon"] != 0)) {
					$ret[] = $item;
				}
			}
		}
		return $ret;
	}

	function get_thumbnail_addition($gallerypath) {
		$gallerypath = WINABSPATH.$gallerypath;
			
		if (!file_exists($gallerypath))
		return FALSE;
			
		if (is_dir($gallerypath."/thumbs")) {
			$thumbFolder 	= "/thumbs/";
			$thumbPrefix 	= "thumbs_";
			return $thumbFolder.$thumbPrefix;
		}
		// old mygallery check
		if (is_dir($gallerypath."/tumbs")) {
			$thumbFolder	= "/tumbs/";
			$thumbPrefix 	= "tmb_";
			return $thumbFolder.$thumbPrefix;
		}

		if (is_admin()) {
			if (!is_dir($gallerypath."/thumbs")) {
				if ( !wp_mkdir_p($gallerypath."/thumbs") )
				return "";
				$thumbFolder	= "/thumbs/";
				$thumbPrefix 	= "thumbs_";
				return $thumbFolder.$thumbPrefix;
			}
		}
		return "";
	}
}

?>