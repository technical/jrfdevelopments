-- WordPress Backup to Dropbox SQL Dump
-- Version 1.3
-- http://wpb2d.com
-- Generation Time: March 26, 2013 at 16:16

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Create and use the backed up database
--

CREATE DATABASE IF NOT EXISTS apeborgu_wp1;
USE apeborgu_wp1;

--
-- Table structure for table `wp_contact_form_7`
--

CREATE TABLE `wp_contact_form_7` (
  `cf7_unit_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '',
  `form` text NOT NULL,
  `mail` text NOT NULL,
  `mail_2` text NOT NULL,
  `messages` text NOT NULL,
  `additional_settings` text NOT NULL,
  PRIMARY KEY (`cf7_unit_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `wp_contact_form_7`
--

INSERT INTO `wp_contact_form_7` (`cf7_unit_id`, `title`, `form`, `mail`, `mail_2`, `messages`, `additional_settings`) VALUES
('1', 'Contact form 1', '<p>Your Name (required)<br />\r\n    [text* your-name] </p>\r\n\r\n<p>Your Email (required)<br />\r\n    [email* your-email] </p>\r\n\r\n<p>Subject<br />\r\n    [text your-subject] </p>\r\n\r\n<p>Your Message<br />\r\n    [textarea your-message] </p>\r\n\r\n<p>[submit \"Send\"]</p>', 'a:7:{s:7:\"subject\";s:14:\"[your-subject]\";s:6:\"sender\";s:26:\"[your-name] <[your-email]>\";s:4:\"body\";s:183:\"From: [your-name] <[your-email]>\r\nSubject: [your-subject]\r\n\r\nMessage Body:\r\n[your-message]\r\n\r\n--\r\nThis mail is sent via contact form on JRF Developments http://www.jrfdevelopments.net\";s:9:\"recipient\";s:32:\"j.ferguson@jrfdevelopments.co.uk\";s:18:\"additional_headers\";s:0:\"\";s:11:\"attachments\";s:0:\"\";s:8:\"use_html\";s:0:\"\";}', 'a:8:{s:6:\"active\";s:1:\"1\";s:7:\"subject\";s:14:\"[your-subject]\";s:6:\"sender\";s:26:\"[your-name] <[your-email]>\";s:4:\"body\";s:122:\"Message body:\r\n[your-message]\r\n\r\n--\r\nThis mail is sent via contact form on JRF Developments http://www.jrfdevelopments.net\";s:9:\"recipient\";s:24:\"mark.lewis@sparkytek.com\";s:18:\"additional_headers\";s:0:\"\";s:11:\"attachments\";s:0:\"\";s:8:\"use_html\";s:0:\"\";}', 'a:13:{s:12:\"mail_sent_ok\";s:43:\"Your message was sent successfully. Thanks.\";s:12:\"mail_sent_ng\";s:84:\"Failed to send your message. Please try later or contact administrator by other way.\";s:17:\"akismet_says_spam\";s:84:\"Failed to send your message. Please try later or contact administrator by other way.\";s:16:\"validation_error\";s:74:\"Validation errors occurred. Please confirm the fields and submit it again.\";s:12:\"accept_terms\";s:35:\"Please accept the terms to proceed.\";s:13:\"invalid_email\";s:28:\"Email address seems invalid.\";s:16:\"invalid_required\";s:31:\"Please fill the required field.\";s:23:\"quiz_answer_not_correct\";s:27:\"Your answer is not correct.\";s:17:\"captcha_not_match\";s:31:\"Your entered code is incorrect.\";s:13:\"upload_failed\";s:22:\"Failed to upload file.\";s:24:\"upload_file_type_invalid\";s:30:\"This file type is not allowed.\";s:21:\"upload_file_too_large\";s:23:\"This file is too large.\";s:23:\"upload_failed_php_error\";s:38:\"Failed to upload file. Error occurred.\";}', '');

--
-- Table structure for table `wp_popularpostsdata`
--

CREATE TABLE `wp_popularpostsdata` (
  `postid` int(10) NOT NULL,
  `day` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_viewed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pageviews` int(10) DEFAULT '1',
  UNIQUE KEY `id` (`postid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_popularpostsdata`
--

INSERT INTO `wp_popularpostsdata` (`postid`, `day`, `last_viewed`, `pageviews`) VALUES
('9', '2011-04-13 20:14:33', '2013-03-21 19:17:56', '77'),
('4', '2011-04-13 20:15:26', '2013-03-21 19:19:01', '253'),
('6', '2011-04-13 20:15:36', '2013-03-21 19:17:34', '169'),
('2', '2011-04-13 20:15:42', '2011-09-12 18:15:46', '8'),
('12', '2011-04-13 20:15:49', '2013-03-21 19:17:25', '235'),
('32', '2011-09-21 21:12:31', '2013-03-23 20:59:16', '341');

--
-- Table structure for table `wp_popularpostsdatacache`
--

CREATE TABLE `wp_popularpostsdatacache` (
  `id` int(10) NOT NULL,
  `day` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pageviews` int(10) DEFAULT '1',
  `day_no_time` date NOT NULL DEFAULT '0000-00-00',
  UNIQUE KEY `compositeID` (`id`,`day_no_time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_popularpostsdatacache`
--

INSERT INTO `wp_popularpostsdatacache` (`id`, `day`, `pageviews`, `day_no_time`) VALUES
('32', '2013-03-09 19:30:25', '1', '2013-03-09'),
('32', '2013-03-23 20:59:16', '1', '2013-03-23'),
('6', '2013-03-15 22:02:04', '2', '2013-03-15'),
('6', '2013-03-06 14:31:20', '1', '2013-03-06'),
('12', '2013-03-13 20:55:23', '4', '2013-03-13'),
('32', '2013-03-12 11:23:30', '1', '2013-03-12'),
('32', '2013-03-13 11:13:52', '3', '2013-03-13'),
('32', '2013-03-01 09:05:04', '1', '2013-03-01'),
('32', '2013-03-15 22:01:25', '5', '2013-03-15'),
('6', '2013-03-12 10:45:33', '1', '2013-03-12');

INSERT INTO `wp_popularpostsdatacache` (`id`, `day`, `pageviews`, `day_no_time`) VALUES
('6', '2013-03-11 23:13:11', '1', '2013-03-11'),
('4', '2013-03-21 19:19:01', '2', '2013-03-21'),
('9', '2013-03-21 19:17:56', '1', '2013-03-21'),
('12', '2013-02-28 07:45:51', '1', '2013-02-28'),
('6', '2013-03-21 19:17:34', '1', '2013-03-21'),
('12', '2013-02-26 15:05:45', '1', '2013-02-26'),
('32', '2013-03-06 14:31:15', '1', '2013-03-06'),
('12', '2013-03-06 14:30:57', '1', '2013-03-06'),
('12', '2013-03-21 19:17:25', '1', '2013-03-21'),
('4', '2013-03-15 22:02:35', '4', '2013-03-15');

INSERT INTO `wp_popularpostsdatacache` (`id`, `day`, `pageviews`, `day_no_time`) VALUES
('4', '2013-03-14 16:49:44', '3', '2013-03-14'),
('12', '2013-03-14 16:44:31', '1', '2013-03-14'),
('6', '2013-03-14 16:44:26', '1', '2013-03-14'),
('32', '2013-03-14 16:43:18', '1', '2013-03-14'),
('9', '2013-03-15 22:05:27', '2', '2013-03-15'),
('32', '2013-03-03 16:02:12', '2', '2013-03-03'),
('6', '2013-03-13 20:55:18', '3', '2013-03-13'),
('32', '2013-03-16 00:17:44', '1', '2013-03-16'),
('32', '2013-03-17 18:33:46', '4', '2013-03-17'),
('32', '2013-03-19 21:11:59', '1', '2013-03-19');

INSERT INTO `wp_popularpostsdatacache` (`id`, `day`, `pageviews`, `day_no_time`) VALUES
('6', '2013-02-26 09:16:40', '1', '2013-02-26'),
('4', '2013-03-13 20:55:30', '6', '2013-03-13'),
('32', '2013-03-21 19:18:15', '3', '2013-03-21'),
('4', '2013-03-06 14:31:36', '2', '2013-03-06'),
('4', '2013-03-02 14:57:02', '4', '2013-03-02'),
('12', '2013-03-01 12:11:19', '2', '2013-03-01'),
('4', '2013-03-01 09:03:52', '1', '2013-03-01'),
('12', '2013-03-09 19:31:01', '1', '2013-03-09'),
('4', '2013-02-25 12:07:02', '3', '2013-02-25'),
('32', '2013-02-25 12:06:16', '4', '2013-02-25');

INSERT INTO `wp_popularpostsdatacache` (`id`, `day`, `pageviews`, `day_no_time`) VALUES
('6', '2013-02-25 12:07:12', '5', '2013-02-25'),
('12', '2013-02-25 12:06:57', '2', '2013-02-25'),
('4', '2013-02-24 19:34:02', '2', '2013-02-24'),
('4', '2013-03-09 19:29:41', '1', '2013-03-09'),
('12', '2013-03-12 10:44:54', '1', '2013-03-12'),
('4', '2013-03-12 10:44:34', '1', '2013-03-12'),
('6', '2013-03-09 19:30:55', '1', '2013-03-09'),
('32', '2013-03-11 23:52:48', '2', '2013-03-11'),
('9', '2013-03-13 18:13:24', '3', '2013-03-13'),
('4', '2013-03-19 21:11:40', '1', '2013-03-19');

INSERT INTO `wp_popularpostsdatacache` (`id`, `day`, `pageviews`, `day_no_time`) VALUES
('12', '2013-03-20 17:12:26', '1', '2013-03-20'),
('9', '2013-02-25 12:07:24', '2', '2013-02-25'),
('9', '2013-02-28 08:03:00', '1', '2013-02-28'),
('4', '2013-02-28 16:18:03', '1', '2013-02-28'),
('12', '2013-03-02 13:56:03', '2', '2013-03-02'),
('32', '2013-03-02 14:57:15', '3', '2013-03-02'),
('6', '2013-03-02 13:56:10', '3', '2013-03-02'),
('4', '2013-03-03 16:03:17', '1', '2013-03-03'),
('12', '2013-03-15 22:02:10', '1', '2013-03-15'),
('12', '2013-03-16 00:19:39', '1', '2013-03-16');

INSERT INTO `wp_popularpostsdatacache` (`id`, `day`, `pageviews`, `day_no_time`) VALUES
('6', '2013-03-16 00:19:40', '1', '2013-03-16'),
('4', '2013-03-16 00:19:47', '1', '2013-03-16');

--
-- Table structure for table `wp_xmlgooglemaps`
--

CREATE TABLE `wp_xmlgooglemaps` (
  `xml_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `xml_path` text NOT NULL,
  `xml_varstring` text NOT NULL,
  PRIMARY KEY (`xml_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `wp_xmlgooglemaps`
--

INSERT INTO `wp_xmlgooglemaps` (`xml_id`, `xml_path`, `xml_varstring`) VALUES
('1', '', 'maxlat=47.0;minlat=46.5;maxlon=8.0;minlon=7.5;maptype=G_MAP_TYPE;zoom=9'),
('2', '', 'maxlat=53.0;minlat=51.5;maxlon=0.9;minlon=0.7;maptype=G_MAP_TYPE;zoom=9');

--
-- Table structure for table `wp_xmlgooglemaps_cache`
--

CREATE TABLE `wp_xmlgooglemaps_cache` (
  `xml_id` bigint(20) NOT NULL,
  `xml_cache_key` varchar(20) NOT NULL,
  `xml_cache_lastupdated` datetime NOT NULL,
  `xml_cache_content` longtext NOT NULL,
  PRIMARY KEY (`xml_id`,`xml_cache_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `wp_xmlgooglemaps_cache`
--

INSERT INTO `wp_xmlgooglemaps_cache` (`xml_id`, `xml_cache_key`, `xml_cache_lastupdated`, `xml_cache_content`) VALUES
('2', 'map0', '2013-03-07 03:10:31', '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"	\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\" style=\"height: 100%\">\n<head>\n<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\n<title>Google Maps</title>\n<script src=\"http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAA9MtpQSjzVDJRvOgW7kxyRhRHXxyFZhN1FxFatmZPv_jTSSw1yhSSbiJGbD7s101c2jC-0-zm8Ngifw\" type=\"text/javascript\"></script>\n<script src=\"xmlgooglemaps_googleMapsAPI.js\" type=\"text/javascript\"></script>\n\n<style type=\"text/css\">\np.entry { font-family: Verdana, Arial, Sans-Serif;}\n</style>\n</head>\n<body style=\"margin: 0; height: 100%; overflow: hidden;\" onunload=\"GUnload();\">\n<div id=\"map8a4f315a5e048b37665ea9d367b1bb35\" style=\"width: 100%; height: 100%;\"></div>\n<script type=\"text/javascript\">\n   <!--\n      var map8a4f315a5e048b37665ea9d367b1bb35 = null;\n      var smt_map8a4f315a5e048b37665ea9d367b1bb35 = function() {\n         map8a4f315a5e048b37665ea9d367b1bb35.setMapType(G_NORMAL_MAP);\n      }\n      function load_map8a4f315a5e048b37665ea9d367b1bb35() {\n         map8a4f315a5e048b37665ea9d367b1bb35 = new GMap2(document.getElementById(\"map8a4f315a5e048b37665ea9d367b1bb35\"));\n         map8a4f315a5e048b37665ea9d367b1bb35.setCenter(new GLatLng(52.250,0.800), 9);\n         map8a4f315a5e048b37665ea9d367b1bb35.addMapType(G_NORMAL_MAP);\n         map8a4f315a5e048b37665ea9d367b1bb35.addMapType(G_HYBRID_MAP);\n         map8a4f315a5e048b37665ea9d367b1bb35.addMapType(G_SATELLITE_MAP);\n         map8a4f315a5e048b37665ea9d367b1bb35.removeMapType(G_PHYSICAL_MAP);\n         map8a4f315a5e048b37665ea9d367b1bb35.removeMapType(USER_MAP_OSM);\n         map8a4f315a5e048b37665ea9d367b1bb35.removeMapType(USER_MAP_OCM);\n         map8a4f315a5e048b37665ea9d367b1bb35.removeMapType(G_SATELLITE_3D_MAP);\n         map8a4f315a5e048b37665ea9d367b1bb35.addControl(new GMapTypeControl());\n         map8a4f315a5e048b37665ea9d367b1bb35.addControl(new BannerButtonControl());\n         map8a4f315a5e048b37665ea9d367b1bb35.addControl(new FullScreenButtonControl());\n         map8a4f315a5e048b37665ea9d367b1bb35.disableDragging();\n         map8a4f315a5e048b37665ea9d367b1bb35.disableDoubleClickZoom();\n         map8a4f315a5e048b37665ea9d367b1bb35.disableContinuousZoom();\n         map8a4f315a5e048b37665ea9d367b1bb35.disableGoogleBar();\n         map8a4f315a5e048b37665ea9d367b1bb35.disableScrollWheelZoom();\n         setTimeout(smt_map8a4f315a5e048b37665ea9d367b1bb35, 100);\n      }\n      if (GBrowserIsCompatible()) {\n         setTimeout(load_map8a4f315a5e048b37665ea9d367b1bb35,100);\n      } else {\n         document.write(\"Javascript must be enabled in order to use Google Maps.\");\n      }\n   // -->\n</script>\n\n</body>\n</html>\n');

--
-- Table structure for table `wp_xmlgooglemaps_gpxfile`
--

CREATE TABLE `wp_xmlgooglemaps_gpxfile` (
  `gpxfile_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `filepath` text NOT NULL,
  `fileupdatetime` datetime NOT NULL,
  `maxlat` double DEFAULT NULL,
  `maxlon` double DEFAULT NULL,
  `minlat` double DEFAULT NULL,
  `minlon` double DEFAULT NULL,
  PRIMARY KEY (`gpxfile_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table `wp_xmlgooglemaps_gpxfile` is empty
--

--
-- Table structure for table `wp_xmlgooglemaps_gpxfile_item`
--

CREATE TABLE `wp_xmlgooglemaps_gpxfile_item` (
  `item_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `item_nr` bigint(20) NOT NULL,
  `gpxfile_id` bigint(20) NOT NULL,
  `element` enum('RTE','RTEPT','TRK','TRKPT','WPT') DEFAULT NULL,
  `parent_item_id` int(11) DEFAULT NULL,
  `name` text,
  `description` text,
  `symbol` text,
  `link` text,
  `time` datetime DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lon` double DEFAULT NULL,
  `ele` double DEFAULT NULL,
  `heartrate` double DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table `wp_xmlgooglemaps_gpxfile_item` is empty
--

--
-- Table structure for table `wp_yarpp_keyword_cache`
--

CREATE TABLE `wp_yarpp_keyword_cache` (
  `ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='YARPP''s keyword cache table';

--
-- Dumping data for table `wp_yarpp_keyword_cache`
--

INSERT INTO `wp_yarpp_keyword_cache` (`ID`, `body`, `title`, `date`) VALUES
('9', 'caption barn align alignnone width 259 id quote lovely 18th century invited roof ', 'vicarage ', '2012-10-25 18:47:01');

--
-- Table structure for table `wp_yarpp_related_cache`
--

CREATE TABLE `wp_yarpp_related_cache` (
  `reference_ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `score` float unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`reference_ID`,`ID`),
  KEY `score` (`score`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `wp_yarpp_related_cache`
--

INSERT INTO `wp_yarpp_related_cache` (`reference_ID`, `ID`, `score`, `date`) VALUES
('9', '0', '0', '2012-10-25 18:47:01');

